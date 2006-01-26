<?php

/**
 * Reassign edits from a user or IP address to another user
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

$options = array( 'force' );
require_once( 'commandLine.inc' );
require_once( 'reassignEdits.inc' );

echo( "Reassign Edits\n\n" );

if( @$args[0] && @$args[1] ) {

	$from = GetUserDetails( $args[0] );
	$to   = GetUserDetails( $args[1] );
	$tor  = $args[1];
	
	if( $to['valid'] || @$options['force'] ) {
		ReassignEdits( $from, $to );
	} else {
		echo( "User \"$tor\" not found.\n" );
	}

} else {
	ShowUsage();
}

/** Show script usage information */
function ShowUsage() {
	echo( "Reassign edits from one user to another.\n\n" );
	echo( "Usage: php reassignEdits.php <from> <to> [--force]\n\n" );
	echo( "    <from> : Name of the user to assign edits from\n" );
	echo( "      <to> : Name of the user to assign edits to\n" );
	echo( "   --force : Reassign even if the target user doesn't exist\n\n" );
}

?>