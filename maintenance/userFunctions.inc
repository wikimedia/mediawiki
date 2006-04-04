<?php

/**
 * Support functions for dealing with user accounts at a database level
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Count the number of edits the specified user has made
 *
 * @param integer $user User ID
 * @param bool $slave Whether or not a slave can be used
 * @return integer
 */
function CountEdits( $user, $slave = true ) {
	$dbw =& wfGetDB( $slave ? DB_SLAVE: DB_MASTER );
	# Count current edits
	$res = $dbw->select( 'revision', 'COUNT(*) AS count', array( 'rev_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	$count = $row->count;
	# Count deleted edits
	$res = $dbw->select( 'archive', 'COUNT(*) AS count', array( 'ar_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	$count += $row->count;
	# Done
	return( $count );
}

/**
 * Count the number of images the specified user has uploaded
 *
 * @param integer $user User ID
 * @param bool $slave Whether or not a slave can be used
 * @return integer
 */
function CountImages( $user, $slave = true ) {
	$dbw =& wfGetDB( $slave ? DB_SLAVE: DB_MASTER );
	# Count current images
	$res = $dbw->select( 'image', 'COUNT(*) AS count', array( 'img_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	$count = $row->count;
	# Count deleted edits
	$res = $dbw->select( 'oldimage', 'COUNT(*) AS count', array( 'oi_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	$count += $row->count;
	# Done
	return( $count );
}

/**
 * Count the number of log entries associated with the specified user
 *
 * @param integer $user User ID
 * @param bool $slave Whether or not a slave can be used
 * @return integer
 */
function CountLogs( $user, $slave = true ) {
	$dbw =& wfGetDB( $slave ? DB_SLAVE: DB_MASTER );
	# Count log entries
	$res = $dbw->select( 'logging', 'COUNT(*) AS count', array( 'log_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	$count = $row->count;
	# Done
	return( $count );
}

/**
 * Retrieve all valid user IDs
 *
 * @return array
 */
function GetUsers() {
	$dbr =& wfGetDB( DB_SLAVE );
	$res = $dbr->select( 'user', 'user_id' );
	$users = array();
	while( $row = $dbr->fetchObject( $res ) ) {
		$users[] = $row->user_id;
	}
	return( $users );
}

/**
 * Resolve a username to a user ID
 *
 * @param string $username Username
 * @return mixed
 */
function GetUserID( $username ) {
	$dbr =& wfGetDB( DB_SLAVE );
	$res = $dbr->select( 'user', 'user_id', array( 'user_name' => '"' . $username . '"' ) );
	if( $res !== false ) {
		$row = $dbr->fetchObject( $res );
		return( $row->user_id );
	} else {
		return( false );
	}
}

/**
 * Delete one or more users
 *
 * @param mixed $users Single integer or array of integers corresponding to user IDs
 * @return bool
 */
function DeleteUsers( $users ) {
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->begin();
	foreach( $users as $user ) {
		$dbw->delete( 'user', array( 'user_id' => $user ) );
	}
	$dbw->commit();
	return( true );
}

/**
 * Add a user to the named group(s)
 *
 * @param integer $user User ID
 * @param mixed $groups Single string or array of strings corresponding to group names
 * @return bool
 */
function SetUserGroups( $user, $groups ) {
	$dbw =& wfGetDB( DB_MASTER );
	foreach( $groups as $group ) {
		$row = array( 'ug_user' => $user, 'ug_group' => $group );
		if( !$dbw->insert( 'user_groups', $row, 'SetUserGroups' ) ) {
			return( false );
		}
	}
	return( true );
}

?>