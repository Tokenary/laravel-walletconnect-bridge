<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->exclude('bootstrap/cache')
    ->exclude('storage')
    ->exclude('vendor')
    ->exclude('routes')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('_ide_helper.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'no_unused_imports' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => true,
        'cast_spaces' => true,
        'function_typehint_space' => true,
        'increment_style' => ['style' => 'post'],
        'is_null' => true,
        'lowercase_cast' => true,
        'method_separation' => true,
        'modernize_types_casting' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_mixed_echo_print' => ['use' => 'print'],
        'no_short_bool_cast' => true,
        'no_short_echo_tag' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'phpdoc_align' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_types' => true,
        'pow_to_exponentiation' => true,
        'protected_to_private' => true,
        'return_type_declaration' => ['space_before' => 'one'],
        'self_accessor' => true,
        'short_scalar_cast' => true,
        'simplified_null_return' => true,
        'single_blank_line_before_namespace' => true,
        'single_line_comment_style' => true,
        'single_quote' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_not_equals' => true,
        //'strict_comparison' => true,
        //'strict_param' => true,
        'ternary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'void_return' => true,
        'whitespace_after_comma_in_array' => true,
        'ternary_to_null_coalescing' => true,

    ])
    ->setFinder($finder);
