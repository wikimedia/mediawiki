<?php

/**
 * Support functions for the reassignEdits script
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

function ReassignEdits( $from, $to ) {

	# This stuff needs to come off the master, wrapped in a transaction
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->begin();

	$tbl_arc = $dbw->tableName( 'archive' );	
	$tbl_rev = $dbw->tableName( 'revision' );

	$from_txt = $from['text'];
	$to_id    = $to['id'];
	$to_txt   = $to['text'];

	echo( "Searching for current revisions..." );
	$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_user_text = \"$from_txt\"" );
	while( $row = $dbw->fetchObject( $res ) ) {
		$cur[] = $row->rev_id;
	}
	$ccount = count( $cur );
	echo( "found $ccount.\n" );

	echo( "Searching for deleted revisions..." );
	$res = $dbw->query( "SELECT ar_rev_id FROM $tbl_arc WHERE ar_user_text = \"$from_txt\"" );
	while( $row = $dbw->fetchObject( $res ) ){
		$old[] = $row->ar_rev_id;
	}
	$ocount = count( $old );
	echo( "found $ocount.\n" );

	if( $ccount > 0 || $ocount > 0 ) {
		echo( "Reassigning edits to $to_txt..." );
	}

	if( $ccount > 0 ) {
		$set = implode( ', ', $cur );
		$res = $dbw->query( "UPDATE $tbl_rev SET rev_user = $to_id, rev_user_text = \"$to_txt\" WHERE rev_id IN ( $set )" );
	}

	if( $ocount > 0 ) {
		$set = implode( ', ', $old );
		$res = $dbw->query( "UPDATE $tbl_arc SET ar_user = $to_id, ar_user_text = \"$to_txt\" WHERE ar_rev_id IN ( $set )" );
	}

	if( $ccount > 0 || $ocount > 0 ) {
		echo( "done.\n" );
	}

	$dbw->commit();
	return( true );	
	
}

function GetUserDetails( $spec ) {

	# IP addresses are quick to handle
	if( User::isIP( $spec ) ) {
		return( array( 'id' => 0, 'text' => $spec, 'valid' => true ) );
	}

	# Need to check the user exists and get ID and canonical username
	$user = User::newFromName( $spec );
	if( $user->getID() ) {
		# We have them
		return( array( 'id' => $user->getID(), 'text' => $user->getName(), 'valid' => true ) );
	} else {
		# No such user
		return( array( 'id' => 0, 'text' => $spec, 'valid' => false ) );
	}

}


?>
