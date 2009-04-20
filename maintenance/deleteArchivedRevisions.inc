<?php

/**
 * Support functions for the deleteArchivedRevisions script
 *
 * @file
 * @ingroup Maintenance
 * @author Aaron Schulz
 */
 
require_once( 'purgeOldText.inc' );

function DeleteArchivedRevisions( $delete = false ) {

	# Data should come off the master, wrapped in a transaction
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$tbl_arch = $dbw->tableName( 'archive' );
	# Delete as appropriate
	echo( "Deleting archived revisions... " );

	if( $delete ) {
		$dbw->query( "TRUNCATE TABLE $tbl_arch" );
	
		$count = $dbw->affectedRows();
		$deletedRows = $count != 0;

		echo( "done. $count revisions deleted.\n" );

		# This bit's done
		# Purge redundant text records
		$dbw->commit();
		if( $deletedRows ) {
			PurgeRedundantText( true );
		}
	} else {
		$res = $dbw->selectRow( 'archive', 'COUNT(*) as count', array(), __FUNCTION__ );
		echo( "done. {$res->count} revisions to delete.\n" );
	}
}
