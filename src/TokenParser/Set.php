<?php

namespace JakeFr\TwigSet\TokenParser;

use JakeFr\TwigSet\Node\Set as NodeSet;

class Set extends \Twig_TokenParser
{
    /** @var array */
    private $combinedBinaryOperators;

    /**
     * Constructor.
     *
     * @param array $combinedBinaryOperators
     */
    public function __construct(array $combinedBinaryOperators)
    {
        $this->combinedBinaryOperators = $combinedBinaryOperators;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $names = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $binaryNode = [];

        $capture = false;
        if ($stream->nextIf(\Twig_Token::OPERATOR_TYPE, '=')) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();

            $stream->expect(\Twig_Token::BLOCK_END_TYPE);

            if (count($names) !== count($values)) {
                throw new \Twig_Error_Syntax('When using Set, you must have the same number of variables and assignments.', $stream->getCurrent()->getLine(), $stream->getSourceContext()->getName());
            }
        } elseif ($binaryToken = $stream->nextIf(\Twig_Token::OPERATOR_TYPE, array_keys($this->combinedBinaryOperators))) {
            if (count($names) > 1) {
                throw new \Twig_Error_Syntax('When using Set with combined operators, you cannot have a multi-target.', $stream->getCurrent()->getLine(), $stream->getFilename());
            }

            $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');

            $values = $this->parser->getExpressionParser()->parseExpression();

            if (count($values) > 1) {
                throw new \Twig_Error_Syntax('When using Set with combined operators, you cannot have a multi-value.', $stream->getCurrent()->getLine(), $stream->getFilename());
            }

            $stream->expect(\Twig_Token::BLOCK_END_TYPE);

            $binaryNode = $this->combinedBinaryOperators[$binaryToken->getValue()];
        } else {
            $capture = true;

            if (count($names) > 1) {
                throw new \Twig_Error_Syntax('When using Set with a block, you cannot have a multi-target.', $stream->getCurrent()->getLine(), $stream->getSourceContext()->getName());
            }

            $stream->expect(\Twig_Token::BLOCK_END_TYPE);

            $values = $this->parser->subparse([$this, 'decideBlockEnd'], true);
            $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        }

        return new NodeSet($capture, $names, $values, $binaryNode, $lineno, $this->getTag());
    }

    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('endset');
    }

    public function getTag()
    {
        return 'set';
    }
}
