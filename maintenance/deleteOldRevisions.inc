<?php

/**
 * Support functions for the deleteOldRevisions script
 *
 * @addtogroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
require_once( 'purgeOldText.inc' );

function DeleteOldRevisions( $delete = false ) {

	# Data should come off the master, wrapped in a transaction
	$dbw =& wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$tbl_pag = $dbw->tableName( 'page' );
	$tbl_rev = $dbw->tableName( 'revision' );
	
	# Get "active" revisions from the page table
	echo( "Searching for active revisions..." );
	$res = $dbw->query( "SELECT page_latest FROM $tbl_pag" );
	while( $row = $dbw->fetchObject( $res ) ) {
		$cur[] = $row->page_latest;
	}
	echo( "done.\n" );
	
	# Get all revisions that aren't in this set
	echo( "Searching for inactive revisions..." );
	$set = implode( ', ', $cur );
	$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_id NOT IN ( $set )" );
	while( $row = $dbw->fetchObject( $res ) ) {
		$old[] = $row->rev_id;
	}
	echo( "done.\n" );
	
	# Inform the user of what we're going to do
	$count = count( $old );
	echo( "$count old revisions found.\n" );
	
	# Delete as appropriate
	if( $delete && $count ) {
		echo( "Deleting..." );
		$set = implode( ', ', $old );
		$dbw->query( "DELETE FROM $tbl_rev WHERE rev_id IN ( $set )" );
		echo( "done.\n" );
	}
	
	# This bit's done
	# Purge redundant text records
	$dbw->commit();
	if( $delete ) {
		PurgeRedundantText( true );
	}

}

?>