<?php

/**
 * Support functions for the deleteOldRevisions script
 *
 * @addtogroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
require_once( 'purgeOldText.inc' );

function DeleteArchivedRevisions( $delete = false ) {

	# Data should come off the master, wrapped in a transaction
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$tbl_arch = $dbw->tableName( 'archive' );
	# Delete as appropriate
	echo( "Deleting archived revisions..." );
	$dbw->query( "TRUNCATE TABLE $tbl_arch" );
	echo( "done.\n" );
	
	$delete = $dbw->affectedRows() != 0;
	
	# This bit's done
	# Purge redundant text records
	$dbw->commit();
	if( $delete ) {
		PurgeRedundantText( true );
	}

}

?>