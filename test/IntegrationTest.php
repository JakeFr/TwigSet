<?php

namespace JakeFr\TwigSet;

final class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new Extension([
                '+'  => ['class' => 'Twig_Node_Expression_Binary_Add'],
                '-'  => ['class' => 'Twig_Node_Expression_Binary_Sub'],
                '~'  => ['class' => 'Twig_Node_Expression_Binary_Concat'],
                '*'  => ['class' => 'Twig_Node_Expression_Binary_Mul'],
                '/'  => ['class' => 'Twig_Node_Expression_Binary_Div'],
                '//' => ['class' => 'Twig_Node_Expression_Binary_FloorDiv'],
                '%'  => ['class' => 'Twig_Node_Expression_Binary_Mod'],
                '**' => ['class' => 'Twig_Node_Expression_Binary_Power'],
            ]),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures';
    }
}
