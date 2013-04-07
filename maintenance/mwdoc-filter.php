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

//
// Match:
//     /**
//      * [freeform text]
//      * @var 1type 23[freeform text]
//      */
//      4<visibility [static]> 4$varName
// And turn into:
//     /**
//      * [freeform text]
//      * 23[freeform text]
//      */
//      4<visibility [static]> 1type 5$varName
//
$regexp = '#\@var\s+([^\s]+)(((?!\*/).)*)\*/\s+(var|public|protected|private|static|public static|protected static|private static)\s+(\$[^\s;=]+)#s';
$replac = '${2}${3} */ ${4} ${1} ${5}';
$source = preg_replace($regexp, $replac, $source);

echo $source;
