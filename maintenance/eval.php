<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );

do {
	$line = readconsole( "> " );
	eval( $line . ";" );
	if ( function_exists( "readline_add_history" ) ) {
		readline_add_history( $line );
	}
} while ( 1 );




?>
