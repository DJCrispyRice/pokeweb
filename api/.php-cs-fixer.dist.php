<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    ->exclude('tests')
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP80Migration' => true,
        'class_definition' => false,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_unreachable_default_argument_value' => true,
        'linebreak_after_opening_tag' => true,
        'semicolon_after_instruction' => true,
        'no_useless_return' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_useless_else' => true,
        'strict_comparison' => true,
        'binary_operator_spaces' => ['default' => 'single_space'],
        'list_syntax' => ['syntax' => 'short'],
        'mb_str_functions' => true,
        'multiline_whitespace_before_semicolons' => false,
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'no_php4_constructor' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'no_superfluous_phpdoc_tags' => ['remove_inheritdoc' => true],
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'self_accessor' => false,
        'single_import_per_statement' => false,
        'no_unused_imports' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'php_unit_strict' => true,
    ])
    ->setRiskyAllowed(true)
    // Some cache-related differences when running fix and phpCsFixer scripts
    ->setUsingCache(false)
    ->setFinder($finder);
