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

$count = 0;
$del = array();

# Right, who needs deleting?
$users = GetUsers();
echo( "Found " . count( $users ) . " accounts.\n" );
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
echo( $count . " inactive accounts found. Deleting..." );
DeleteUsers( $del );
echo( "done.\n" );

# We're done
echo( "Complete.\n" );

?>