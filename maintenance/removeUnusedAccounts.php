<?php
/**
 * Remove unused user accounts from the database
 * An unused account is one which has made no edits
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */


$options = array( 'help', 'delete' );
require_once( 'commandLine.inc' );
require_once( 'removeUnusedAccounts.inc' );
echo( "Remove Unused Accounts\n\n" );
$fname = 'removeUnusedAccounts';

if( isset( $options['help'] ) ) {
	showHelp();
	exit();
}

# Do an initial scan for inactive accounts and report the result
echo( "Checking for unused user accounts...\n" );
$del = array();
$dbr = wfGetDB( DB_SLAVE );
$res = $dbr->select( 'user', array( 'user_id', 'user_name', 'user_touched' ), '', $fname );
$excludedGroups = array( 'sysop', 'bureaucrat' );
while( $row = $dbr->fetchObject( $res ) ) {
	# Check the account, but ignore it if it's within the "sysop" or "bureaucrat" group.
	$instance = User::newFromId( $row->user_id );
	if( count( array_intersect( $instance->getGroups(), $excludedGroups ) ) == 0
		&& isInactiveAccount( $row->user_id, true ) 
		&& wfTimestamp( TS_UNIX, $row->user_touched ) < wfTimestamp( TS_UNIX, time() - 604800 ) 
		) {
		# Inactive; print out the name and flag it
		$del[] = $row->user_id;
		echo( $row->user_name . "\n" );
	}
}
$count = count( $del );
echo( "...found {$count}.\n" );

# If required, go back and delete each marked account
if( $count > 0 && isset( $options['delete'] ) ) {
	echo( "\nDeleting inactive accounts..." );
	$dbw = wfGetDB( DB_MASTER );
	$dbw->delete( 'user', array( 'user_id' => $del ), $fname );
	echo( "done.\n" );
	# Update the site_stats.ss_users field
	$users = $dbw->selectField( 'user', 'COUNT(*)', array(), $fname );
	$dbw->update( 'site_stats', array( 'ss_users' => $users ), array( 'ss_row_id' => 1 ), $fname );
} else {
	if( $count > 0 )
		echo( "\nRun the script again with --delete to remove them from the database.\n" );
}
echo( "\n" );
