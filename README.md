# JakeFR / TwigSet

**`set`** tag for twig 2 with support of combined operators.

## Requirements

This librairy requires **Twig 2** and **PHP 7**

## Installation

Install via Composer

``` bash
$ composer require jakefr/twig-set
```

Add the extension to twig

The constructor expects one parameter:

An array of supported binary nodes with symbol as key and the binary node class name as value.

``` php
$operators = [
    '+' => 'Twig_Node_Expression_Binary_Add',
    '-' => 'Twig_Node_Expression_Binary_Sub',
    '~' => 'Twig_Node_Expression_Binary_Concat',
    '*' => 'Twig_Node_Expression_Binary_Mul',
    '/'  => 'Twig_Node_Expression_Binary_Div',
    '//' => 'Twig_Node_Expression_Binary_FloorDiv',
    '%' => 'Twig_Node_Expression_Binary_Mod',
    '**' => 'Twig_Node_Expression_Binary_Power',
];
$twig->addExtension(new \JakeFr\TwigSet\Extension($operators));
```

## Usage

``` jinja
{% set foo = 4 %}

{{ foo }} {# will ouput 4 #}

{% set foo += 3 %}

{{ foo }} {# will ouput 7 #}
```
