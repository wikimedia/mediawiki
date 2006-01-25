<?php

/**
 * Support functions for the removeUnusedAccounts script
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

# Count the number of edits the specified user has made 
function CountEdits( $user_id ) {
	# We've *got* to pull this stuff off the master. If the user *has* made an edit, but it hasn't
	# been replicated to the slaves yet, we'll end up falsely marking them as inactive. This could
	# (and usually would) lead to their deletion.
	$dbw =& wfGetDB( DB_MASTER );
	$sql = 'SELECT COUNT(rev_id) AS count FROM ' . $dbw->tableName( 'revision' ) . ' WHERE rev_user = ' . $user_id;
	$res = $dbw->query( $sql );
	$row = $dbw->fetchObject( $res );
	return( $row->count );	
}

# Return an array containing all valid user IDs
function GetUsers() {
	# We're safe enough pulling this off a slave
	$dbr =& wfGetDB( DB_SLAVE );
	$sql = 'SELECT user_id FROM ' . $dbr->tableName( 'user' );
	$res = $dbr->query( $sql );
	$users = array();
	while( $row = $dbr->fetchObject( $res ) ) {
		$users[] = $row->user_id;
	}
	return( $users );
}

# Delete one or more users
function DeleteUsers( $users ) {
	# Need a master, obviously
	$dbw =& wfGetDB( DB_MASTER );
	# We'll do it all in one go, for speed
	$dbw->begin();
	$table = $dbw->tableName( 'user' );
	foreach( $users as $user ) {
		$dbw->query( 'DELETE FROM ' . $table . ' WHERE user_id = ' . $user . ' LIMIT 1' );
	}
	$dbw->commit();
}

?>