<?php
/**
 * PHP lacks an interactive mode, but this can be very helpful when debugging.
 * This script lets a command-line user start up the wiki engine and then poke
 * about by issuing PHP commands directly.
 *
 * Unlike eg Python, you need to use a 'return' statement explicitly for the
 * interactive shell to print out the value of the expression. Multiple lines
 * are evaluated separately, so blocks need to be input without a line break.
 * Fatal errors such as use of undeclared functions can kill the shell.
 *
 * To get decent line editing behavior, you should compile PHP with support 
 * for GNU readline (pass --with-readline to configure).
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );

do {
	$line = readconsole( "> " );
	$val = eval( $line . ";" );
	if( is_null( $val ) ) {
		echo "\n";
	} elseif( is_string( $val ) || is_numeric( $val ) ) {
		echo "$val\n";
	} else {
		var_dump( $val );
	}
	if ( function_exists( "readline_add_history" ) ) {
		readline_add_history( $line );
	}
} while ( 1 );

?>
