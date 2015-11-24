<?php
/**
 * Doxygen filter to show correct member variable types in documentation.
 *
 * Should be set in Doxygen INPUT_FILTER as "php mwdoc-filter.php"
 *
 * Based on
 * <http://virtualtee.blogspot.co.uk/2012/03/tip-for-using-doxygen-for-php-code.html>
 *
 * Improved to resolve various bugs and better MediaWiki PHPDoc conventions:
 *
 * - Insert variable name after typehint instead of at end of line so that
 *   documentation text may follow after "@var Type".
 * - Insert typehint into source code before $variable instead of inside the comment
 *   so that Doxygen interprets it.
 * - Strip the text after @var from the output to avoid Doxygen warnings aboug bogus
 *   symbols being documented but not declared or defined.
 *
 * Copyright (C) 2012 Tamas Imrei <tamas.imrei@gmail.com> http://virtualtee.blogspot.com/
 * Copyright (C) 2015 Timo Tijhof
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

if ( PHP_SAPI != 'cli' ) {
	die( "This filter can only be run from the command line.\n" );
}

$source = file_get_contents( $argv[1] );
$tokens = token_get_all( $source );

$buffer = $bufferType = null;
foreach ( $tokens as $token ) {
	if ( is_string( $token ) ) {
		if ( $buffer !== null && $token === ';' ) {
			// If we still have a buffer and the statement has ended,
			// flush it and move on.
			echo $buffer;
			$buffer = $bufferType = null;
		}
		echo $token;
		continue;
	}
	list( $id, $content ) = $token;
	switch ( $id ) {
		case T_DOC_COMMENT:
			// Escape slashes so that references to namespaces are not
			// wrongly interpreted as a Doxygen "\command".
			$content = addcslashes( $content, '\\' );
			// Look for instances of "@var Type" not followed by $name.
			if ( preg_match( '#@var\s+([^\s]+)\s+([^\$]+)#s', $content ) ) {
				$buffer = preg_replace_callback(
					// Strip the "@var Type" part and remember the type
					'#(@var\s+)([^\s]+)#s',
					function ( $matches ) use ( &$bufferType ) {
						$bufferType = $matches[2];
						return '';
					},
					$content
				);
			} else {
				echo $content;
			}
			break;

		case T_VARIABLE:
			if ( $buffer !== null ) {
				echo $buffer;
				echo "$bufferType $content";
				$buffer = $bufferType = null;
			} else {
				echo $content;
			}
			break;

		default:
			if ( $buffer !== null ) {
				$buffer .= $content;
			} else {
				echo $content;
			}
			break;
	}
}
