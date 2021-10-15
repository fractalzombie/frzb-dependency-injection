<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('docs')
    ->in(__DIR__)
;

$rules = [
    // Set of rules
    '@PSR2' => true,
    '@PSR12' => true,
    '@Symfony' => true,
    '@PhpCsFixer' => true,
    '@Symfony:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHPUnit84Migration:risky' => true,
    // Single rules
    'phpdoc_line_span' => true,
    'date_time_immutable' => true,
    'php_unit_test_class_requires_covers' => false,
    'php_unit_test_case_static_method_calls' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'class_attributes_separation' => ['elements' => ['const' => 'none', 'method' => 'one', 'property' => 'one', 'trait_import' => 'none']],
    'class_definition' => ['single_line' => false, 'space_before_parenthesis' => true, 'single_item_single_line' => false, 'multi_line_extends_each_single_line' => false],
];

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
;
