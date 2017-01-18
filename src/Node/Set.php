<?php

namespace JakeFr\TwigSet\Node;

class Set extends \Twig_Node
{
    public function __construct(
        $capture,
        \Twig_Node $names,
        \Twig_Node $values,
        $binaryNode,
        $lineno,
        $tag = null
    ) {
        parent::__construct(['names' => $names, 'values' => $values], ['capture' => $capture, 'safe' => false, 'binary_node' => $binaryNode], $lineno, $tag);

        /*
         * Optimizes the node when capture is used for a large block of text.
         *
         * {% Set foo %}foo{% endSet %} is compiled to $context['foo'] = new Twig_Markup("foo");
         */
        if ($this->getAttribute('capture')) {
            $this->setAttribute('safe', true);

            $values = $this->getNode('values');
            if ($values instanceof \Twig_Node_Text) {
                $this->setNode('values', new \Twig_Node_Expression_Constant($values->getAttribute('data'), $values->getTemplateLine()));
                $this->setAttribute('capture', false);
            }
        }
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        if (count($this->getNode('names')) > 1) {
            $compiler->write('list(');
            foreach ($this->getNode('names') as $idx => $node) {
                if ($idx) {
                    $compiler->raw(', ');
                }

                $compiler->subcompile($node);
            }
            $compiler->raw(')');
        } else {
            if ($this->getAttribute('capture')) {
                $compiler
                    ->write("ob_start();\n")
                    ->subcompile($this->getNode('values'))
                ;
            }

            $compiler->subcompile($this->getNode('names'), false);

            if ($this->getAttribute('capture')) {
                $compiler->raw(" = ('' === \$tmp = ob_get_clean()) ? '' : new Twig_Markup(\$tmp, \$this->env->getCharset())");
            }
        }

        if (!$this->getAttribute('capture')) {
            $compiler->raw(' = ');

            if (count($this->getNode('names')) > 1) {
                $compiler->write('array(');
                foreach ($this->getNode('values') as $idx => $value) {
                    if ($idx) {
                        $compiler->raw(', ');
                    }

                    $compiler->subcompile($value);
                }
                $compiler->raw(')');
            } else {
                if ($this->getAttribute('safe')) {
                    $compiler->raw("('' === \$tmp = ");
                }
                if ($this->getAttribute('binary_node')) {
                    $binaryNodeClass = $this->getAttribute('binary_node');

                    $compiler->subcompile(new $binaryNodeClass($this->getNode('names'), $this->getNode('values'), $this->getNode('names')->getTemplateLine()));
                } else {
                    $compiler->subcompile($this->getNode('values'));
                }
                if ($this->getAttribute('safe')) {
                    $compiler->raw(") ? '' : new Twig_Markup(\$tmp, \$this->env->getCharset())");
                }
            }
        }

        $compiler->raw(";\n");
    }
}
