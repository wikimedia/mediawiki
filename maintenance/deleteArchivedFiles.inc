<?php

/**
 * Support functions for the deleteArchivedFiles script
 *
 * @file
 * @ingroup Maintenance
 * @author Aaron Schulz
 */

require_once( "$IP/includes/filerepo/File.php" );

function DeleteArchivedFiles( $delete = false ) {
	# Data should come off the master, wrapped in a transaction
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	$tbl_arch = $dbw->tableName( 'filearchive' );
	$repo = RepoGroup::singleton()->getLocalRepo();
	# Get "active" revisions from the filearchive table
	echo( "Searching for and deleting archived files...\n" );
	$res = $dbw->query( "SELECT fa_id,fa_storage_group,fa_storage_key FROM $tbl_arch" );
	$count = 0;
	while( $row = $dbw->fetchObject( $res ) ) {
		$key = $row->fa_storage_key;
		$group = $row->fa_storage_group;
		$id = $row->fa_id;
		$path = $repo->getZonePath( 'deleted' ).'/'.$repo->getDeletedHashPath($key).$key;
		$sha1 = substr( $key, 0, strcspn( $key, '.' ) );
		// Check if the file is used anywhere...
		$inuse = $dbw->selectField( 'oldimage', '1',
			array( 'oi_sha1' => $sha1,
			'oi_deleted & '.File::DELETED_FILE => File::DELETED_FILE ),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		if ( $path && file_exists($path) && !$inuse ) {
			unlink($path); // delete
			$count++;
			$dbw->query( "DELETE FROM $tbl_arch WHERE fa_id = $id" );
		} else {
			echo( "Notice - file '$key' not found in group '$group'\n" );
		}
	}
	$dbw->commit();
	echo( "Done! [$count file(s)]\n" );
}
