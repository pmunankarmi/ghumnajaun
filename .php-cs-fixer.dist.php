<?php
/** Readable PHP formatting using the formatter already available on this project. */
return ( new PhpCsFixer\Config() )
	->setIndent( "\t" )
	->setUsingCache( false )
	->setRules( [
		'@PSR12' => true,
		'array_syntax' => [ 'syntax' => 'long' ],
		'braces_position' => [
			'functions_opening_brace' => 'same_line',
			'classes_opening_brace' => 'same_line',
			'allow_single_line_anonymous_functions' => false,
		],
		'spaces_inside_parentheses' => [ 'space' => 'single' ],
		'concat_space' => [ 'spacing' => 'one' ],
		'array_indentation' => true,
		'not_operator_with_successor_space' => true,
		'cast_spaces' => [ 'space' => 'single' ],
		'no_extra_blank_lines' => true,
		'whitespace_after_comma_in_array' => true,
		'blank_line_before_statement' => [ 'statements' => [ 'return', 'if', 'foreach' ] ],
	] )
	->setFinder( PhpCsFixer\Finder::create()->in( __DIR__ . '/wordpress/ghumnajaun' )->name( '*.php' ) );
