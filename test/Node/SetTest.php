<?php

namespace JakeFr\TwigSet\Node;

class SetTest extends \Twig_Test_NodeTestCase
{
    public function testConstructor()
    {
        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1)], [], 1);
        $values = new \Twig_Node([new \Twig_Node_Expression_Constant('foo', 1)], [], 1);
        $node = new Set(false, $names, $values, null, 1);

        $this->assertEquals($names, $node->getNode('names'));
        $this->assertEquals($values, $node->getNode('values'));
        $this->assertFalse($node->getAttribute('capture'));
    }

    public function getTests()
    {
        $tests = [];

        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1)], [], 1);
        $values = new \Twig_Node([new \Twig_Node_Expression_Constant('foo', 1)], [], 1);
        $node = new Set(false, $names, $values, null, 1);
        $tests[] = [$node, <<<'EOF'
// line 1
$context["foo"] = "foo";
EOF
        ];

        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1)], [], 1);
        $values = new \Twig_Node([new \Twig_Node_Print(new \Twig_Node_Expression_Constant('foo', 1), 1)], [], 1);
        $node = new Set(true, $names, $values, null, 1);
        $tests[] = [$node, <<<'EOF'
// line 1
ob_start();
echo "foo";
$context["foo"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
EOF
        ];

        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1)], [], 1);
        $values = new \Twig_Node_Text('foo', 1);
        $node = new Set(true, $names, $values, null, 1);
        $tests[] = [$node, <<<'EOF'
// line 1
$context["foo"] = ('' === $tmp = "foo") ? '' : new Twig_Markup($tmp, $this->env->getCharset());
EOF
        ];

        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1), new \Twig_Node_Expression_AssignName('bar', 1)], [], 1);
        $values = new \Twig_Node([new \Twig_Node_Expression_Constant('foo', 1), new \Twig_Node_Expression_Name('bar', 1)], [], 1);
        $node = new Set(false, $names, $values, null, 1);
        $tests[] = [$node, <<<EOF
// line 1
list(\$context["foo"], \$context["bar"]) = array("foo", {$this->getVariableGetter('bar')});
EOF
        ];

        $names = new \Twig_Node([new \Twig_Node_Expression_AssignName('foo', 1)], [], 1);
        $values = new \Twig_Node([new \Twig_Node_Expression_Constant('foo', 1)], [], 1);
        $node = new Set(false, $names, $values, 'Twig_Node_Expression_Binary_Add', 1);
        $tests[] = [$node, <<<'EOF'
// line 1
$context["foo"] = ($context["foo"] + "foo");
EOF
        ];

        return $tests;
    }
}
