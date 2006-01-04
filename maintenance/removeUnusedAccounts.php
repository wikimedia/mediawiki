<?php

/**
 * Remove unused user accounts from the database
 * An unused account is one which has made no edits
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
require_once( 'commandLine.inc' );
require_once( 'removeUnusedAccounts.inc' );
echo( "REMOVE UNUSED ACCOUNTS\nThis script will delete all users who have made no edits.\n\n" );
echo( "Syntax: removeUnusedAccounts.php [delete]\n	* delete -> delete the accounts\n	* The first user (usually the site owner) is left alone\n\n" );

# Handle parameters
if( isset( $args[0] ) ) {
	$param = array_shift( $args );
	if( $param == 'delete' ) {
		$action = ACTION_DELETE;
	} else {
		$action = ACTION_REPORT;
	}
} else {
	$action = ACTION_REPORT;
}

$count = 0;
$del = array();

# Right, who needs deleting?
$users = GetUsers();
echo( "Found " . count( $users ) . " accounts.\n\n" );
echo( "Locating inactive users..." );
foreach( $users as $user ) {
	if( $user != 1 ) {	# Don't *touch* the first user account, ever
		if( CountEdits( $user ) == 0 ) {
			# User has no edits, mark them for deletion
			$del[] = $user;
			$count++;
		}
	}
}
echo( "done.\n" );

# Purge the inactive accounts we found
echo( $count . " inactive accounts found." );
if( ( $action == ACTION_DELETE ) && ( $count > 0 ) ) {
	echo( " Deleting..." );
	DeleteUsers( $del );
	echo( "done.\n" );
}

?>