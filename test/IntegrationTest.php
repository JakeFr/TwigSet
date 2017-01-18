<?php

namespace JakeFr\TwigSet;

final class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new Extension([
                '+'  => 'Twig_Node_Expression_Binary_Add',
                '-'  => 'Twig_Node_Expression_Binary_Sub',
                '~'  => 'Twig_Node_Expression_Binary_Concat',
                '*'  => 'Twig_Node_Expression_Binary_Mul',
                '/'  => 'Twig_Node_Expression_Binary_Div',
                '//' => 'Twig_Node_Expression_Binary_FloorDiv',
                '%'  => 'Twig_Node_Expression_Binary_Mod',
                '**' => 'Twig_Node_Expression_Binary_Power',
            ]),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures';
    }
}
