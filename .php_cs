<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return Config::create()
    ->setRules([
        '@Symfony' => true,
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => false,
        'cast_spaces' => ['space' => 'none'],
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'heredoc_to_nowdoc' => true,
        'list_syntax' => null,
        'no_extra_consecutive_blank_lines' => ['tokens' => ['continue', 'parenthesis_brace_block', 'extra', 'return']],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_null_property_initialization' => true,
        'no_short_echo_tag' => false,
        'no_superfluous_elseif' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_annotation_without_dot' => null,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_order' => true,
        'phpdoc_separation' => false,
        'phpdoc_summary' => null,
        'phpdoc_types_order' => true,
        'return_type_declaration' => true,
        'void_return' => false,
        'yoda_style' => false,
    ])
    ->setFinder(
        Finder::create()
            ->in([
                __DIR__ . '/examples',
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
    );
