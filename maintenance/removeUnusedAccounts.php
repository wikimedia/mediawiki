<?php

/**
 * Remove unused user accounts from the database
 * An unused account is one which has made no edits
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

# Options available
$options = array( 'delete','help' );
 
require_once( 'commandLine.inc' );
require_once( 'removeUnusedAccounts.inc' );

# Default action (just report):
$action = ACTION_REPORT;

# Handle parameters
if(@$options['help']) {
echo <<<END
This script will delete all users who have made no edits.

usage:removeUnusedAccounts.php [--help|--delete]
  --delete : delete the unused accounts
  --help   : this help message

NB: The first user (usually the site owner) is left alone

END;
die;
}

if(@$options['delete']) {
	$action = ACTION_DELETE;
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
echo( $count . " inactive accounts found.\n" );
if( ( $action == ACTION_DELETE ) && ( $count > 0 ) ) {
	echo( " Deleting..." );
	DeleteUsers( $del );
	echo( "done.\n" );
} else {
	echo "\nYou can delete them by using the '--delete' switch (see help).\n";
}

?>
