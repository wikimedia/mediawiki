<?php

/**
 * Support functions for the deleteArchivedFiles script
 *
 * @addtogroup Maintenance
 * @author Aaron Schulz
 */

require_once( "$IP/includes/FileStore.php" );

function DeleteArchivedFiles( $delete = false ) {

	# Data should come off the master, wrapped in a transaction
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	
	$transaction = new FSTransaction();
	if( !FileStore::lock() ) {
		wfDebug( __METHOD__.": failed to acquire file store lock, aborting\n" );
		return false;
	}
	
	$tbl_arch = $dbw->tableName( 'filearchive' );
	
	# Get "active" revisions from the filearchive table
	echo( "Searching for and deleting archived files...\n" );
	$res = $dbw->query( "SELECT fa_id,fa_storage_group,fa_storage_key FROM $tbl_arch" );
	while( $row = $dbw->fetchObject( $res ) ) {
		$key = $row->fa_storage_key;
		$group = $row->fa_storage_group;
		$id = $row->fa_id;
		
		$store = FileStore::get( $group );
		if ( $store ) {
			$path = $store->filePath( $key );
			if ( $path && file_exists($path) ) {
				$transaction->addCommit( FSTransaction::DELETE_FILE, $path );
				$dbw->query( "DELETE FROM $tbl_arch WHERE fa_id = $id" );
			} else {
				echo( "Notice - file '$key' not found in group '$group'\n" );
			}
		} else {
			echo( "Notice - invalid file storage group '$group'\n" );
		}
	}
	echo( "done.\n" );
	
	$transaction->commit();
	
	# This bit's done
	$dbw->commit();

}

?>