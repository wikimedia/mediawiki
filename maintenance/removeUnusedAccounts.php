<?php

/**
 * Remove unused user accounts from the database
 * An unused account is one which has made no edits
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

define( 'ACTION_REPORT', 0 );
define( 'ACTION_DELETE', 1 );
$options = array( 'delete','help' );
require_once( 'commandLine.inc' );
require_once( 'userFunctions.inc' );

echo( "Remove Unused Accounts\nThis script will delete all users who have made no edits.\n\n" );

# Check parameters
if( @$options['help'] ) {
	echo( "USAGE: removeUnusedAccounts.php [--help|--delete]\n\nThe first (default) account is ignored.\n\n" );
	wfDie();
} else {
	$delete = @$options['delete'] ? true : false ;
}

$count = 0;
$del = array();

# Right, who needs deleting?
$users = GetUsers();
echo( "Found " . count( $users ) . " accounts.\n\n" );
echo( "Locating inactive users..." );
foreach( $users as $user ) {
	if( $user != 1 ) {	# Don't *touch* the first user account, ever
		if( CountEdits( $user, false ) == 0 ) {
			# User has no edits, mark them for deletion
			$del[] = $user;
			$count++;
		}
	}
}
echo( "done.\n" );

# Purge the inactive accounts we found
echo( $count . " inactive accounts found.\n" );
if( $count > 0 ) {
	if( $delete ) {
		echo( "Deleting..." );
		DeleteUsers( $del );
		echo( "done.\n" );
	} else {
		echo "Run the script with the --delete option to remove them from the database.\n";
	}
}
		
echo( "\n" );

?>
