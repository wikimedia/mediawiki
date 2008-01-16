<?php
/**
 * A repository that stores files in the local filesystem and registers them
 * in the wiki's own database. This is the most commonly used repository class.
 */
class LocalRepo extends FSRepo {
	var $fileFactory = array( 'LocalFile', 'newFromTitle' );
	var $oldFileFactory = array( 'OldLocalFile', 'newFromTitle' );

	function getSlaveDB() {
		return wfGetDB( DB_SLAVE );
	}

	function getMasterDB() {
		return wfGetDB( DB_MASTER );
	}

	function newFileFromRow( $row ) {
		if ( isset( $row->img_name ) ) {
			return LocalFile::newFromRow( $row, $this );
		} elseif ( isset( $row->oi_name ) ) {
			return OldLocalFile::newFromRow( $row, $this );
		} else {
			throw new MWException( __METHOD__.': invalid row' );
		}
	}
	
	function newFromArchiveName( $title, $archiveName ) {
		return OldLocalFile::newFromArchiveName( $title, $this, $archiveName );
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the 
	 * filearchive table. This needs to be done in the repo because it needs to 
	 * interleave database locks with file operations, which is potentially a 
	 * remote operation.
	 * @return FileRepoStatus
	 */
	function cleanupDeletedBatch( $storageKeys ) {
		$root = $this->getZonePath( 'deleted' );
		$dbw = $this->getMasterDB();
		$status = $this->newGood();
		$storageKeys = array_unique($storageKeys);
		foreach ( $storageKeys as $key ) {
			$hashPath = $this->getDeletedHashPath( $key );
			$path = "$root/$hashPath$key";
			$dbw->begin();
			$inuse = $dbw->selectField( 'filearchive', '1', 
				array( 'fa_storage_group' => 'deleted', 'fa_storage_key' => $key ),
				__METHOD__, array( 'FOR UPDATE' ) );
			if ( !$inuse ) {
				wfDebug( __METHOD__ . ": deleting $key\n" );
				if ( !@unlink( $path ) ) {
					$status->error( 'undelete-cleanup-error', $path );
					$status->failCount++;
				}
			} else {
				wfDebug( __METHOD__ . ": $key still in use\n" );
				$status->successCount++;
			}
			$dbw->commit();
		}
		return $status;
	}

	/**
	 * Function link Title::getArticleID().
	 * We can't say Title object, what database it should use, so we duplicate that function here.
	 */
	function getArticleID( $title ) {
		if( !$title instanceof Title ) {
			return 0;
		}
		$dbr = $this->getSlaveDB();
		$id = $dbr->selectField(
			'page',	// Table
			'page_id',	//Field
			array(	//Conditions
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDbKey(),
			),
			__METHOD__	//Function name
		);
		return $id;
	}

	function checkRedirect( $title ) {
		$id = $this->getArticleID( $title );
		if( !$id ) {
			return false;
		}
		$dbr = $this->getSlaveDB();
		$row = $dbr->selectRow(
			'redirect',
			array( 'rd_title', 'rd_namespace' ),
			array( 'rd_from' => $id ),
			__METHOD__
		);
		if( !$row ) {
			return false;
		}
		if( $row->rd_namespace != NS_IMAGE ) {
			return false;
		}
		return Title::makeTitle( $row->rd_namespace, $row->rd_title );
	}
}
