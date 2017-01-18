<?php

namespace JakeFr\TwigSet;

class Extension extends \Twig_Extension
{
    /** @var array */
    private $binaryOperators;

    /**
     * Constructor.
     *
     * @param array $binaryOperators Operators suoorted by tag
     */
    public function __construct(array $binaryOperators)
    {
        $this->binaryOperators = $binaryOperators;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [new TokenParser\Set($this->binaryOperators)];
    }
}
