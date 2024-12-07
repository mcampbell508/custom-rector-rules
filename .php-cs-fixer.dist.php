<?php

$finder = \PhpCsFixer\Finder::create()
    ->in([
        __DIR__,
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests',
    ])
    ->name('.php-cs-fixer.dist.php')
    ->name('*.php')
    ->notPath('phpstan-baseline.php')
    ->exclude('docs')
    ->exclude('vendor')
    ->exclude('tests/*/Fixture/*')
    ->ignoreDotFiles(false);

return (new \PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true, //this covers @PSR1, @PSR2 and @PSR12
        '@PHPUnit100Migration:risky' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => true,
        'cast_spaces' => ['space' => 'single'],
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'trait_import' => 'none',
            ],
        ],
        'class_reference_name_casing' => true,
        'clean_namespace' => true,
        'concat_space' => ['spacing' => 'one'],
        'dir_constant' => true,
        'ereg_to_preg' => true,
        'function_declaration' => [
            'closure_fn_spacing' => 'none',
        ],
        'function_to_constant' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['author']],
        'get_class_to_class_keyword' => true,
        'include' => true,
        'lambda_not_used_import' => true,
        'list_syntax' => ['syntax' => 'short'],
        'logical_operators' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'modernize_strpos' => true,
        'modernize_types_casting' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_function_casing' => true,
        'no_alias_functions' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'no_homoglyph_names' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unneeded_braces' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_final_method' => true,
        'no_unneeded_import_alias' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_useless_concat_operator' => true,
        'no_useless_nullsafe_operator' => true,
        'no_useless_sprintf' => true,
        'no_whitespace_before_comma_in_array' => true,
        'non_printable_character' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'php_unit_attributes' => true,
        'php_unit_construct' => true,
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'pow_to_exponentiation' => true,
        'protected_to_private' => true,
        'psr_autoloading' => true,
        'random_api_migration' => true,
        'semicolon_after_instruction' => true,
        'set_type_to_cast' => true,
        'single_line_empty_body' => false,
        'space_after_semicolon' => true,
        'string_line_ending' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
        'trim_array_spaces' => true,
        'type_declaration_spaces' => true,
        'types_spaces' => [
            'space' => 'none',
            'space_multiple_catch' => 'single',
        ],
        'void_return' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder);
