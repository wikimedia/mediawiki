<?php
/**
 * Doxygen filter to show correct member variable types in documentation.
 *
 * Should be filled in doxygen INPUT_FILTER as "php mwdoc-filter.php"
 *
 * Original source code by Goran Rakic
 * http://blog.goranrakic.com/
 * http://stackoverflow.com/questions/4325224
 *
 * @file
 */

if ( PHP_SAPI != 'cli' ) {
	die( "This filter can only be run from the command line.\n" );
}

$source = file_get_contents( $argv[1] );
$regexp = '#'
	. '\@var'
	. '\s+'
	// Type hint
	. '([^\s]+)'
	. '\s+'
	// Any text or line(s) between type hint and '/' closing the comment
	// (includes the star of "*/"). Descriptions containing a slash
	// are not supported. Those will have to to be rewritten to have their
	// description *before* the @var:
	// /**
	//  * Description with / in it.
	//  * @var array
	//  */
	// instead of:
	// /**
	//  * @var array Description with / in it.
	//  */
	. '([^/]+)'
	. '/'
	. '\s+'
	. '(var|public|protected|private)'
	. '\s+'
	// Variable name
	. '(\$[^\s;=]+)'
	. '#';
$replac = '${2}/ ${3} ${1} ${4}';
$source = preg_replace( $regexp, $replac, $source );

echo $source;
