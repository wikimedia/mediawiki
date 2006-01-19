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
	$res = $dbw->select( 'revision', 'COUNT(rev_id) AS count', array( 'rev_user' => $user ) );
	$row = $dbw->fetchObject( $res );
	return( $row->count );
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