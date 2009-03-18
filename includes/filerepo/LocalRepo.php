<?php
/**
 * A repository that stores files in the local filesystem and registers them
 * in the wiki's own database. This is the most commonly used repository class.
 * @ingroup FileRepo
 */
class LocalRepo extends FSRepo {
	var $fileFactory = array( 'LocalFile', 'newFromTitle' );
	var $oldFileFactory = array( 'OldLocalFile', 'newFromTitle' );
	var $fileFromRowFactory = array( 'LocalFile', 'newFromRow' );
	var $oldFileFromRowFactory = array( 'OldLocalFile', 'newFromRow' );

	function getMemcKey( $key ) {
		return wfWikiID( $this->getSlaveDB() ) . ":{$key}";
	}

	function newFileFromRow( $row ) {
		if ( isset( $row->img_name ) ) {
			return call_user_func( $this->fileFromRowFactory, $row, $this );
		} elseif ( isset( $row->oi_name ) ) {
			return call_user_func( $this->oldFileFromRowFactory, $row, $this );
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
			if( !$inuse ) {
				$sha1 = substr( $key, 0, strcspn( $key, '.' ) );
				$ext = substr( $key, strcspn($key,'.') + 1 );
				$ext = File::normalizeExtension($ext);
				$inuse = $dbw->selectField( 'oldimage', '1',
					array( 'oi_sha1' => $sha1,
						"oi_archive_name LIKE '%.{$ext}'",
						'oi_deleted & '.File::DELETED_FILE => File::DELETED_FILE ),
					__METHOD__, array( 'FOR UPDATE' ) );
			}
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
	protected function getArticleID( $title ) {
		if( !$title instanceof Title ) {
			return 0;
		}
		$dbr = $this->getSlaveDB();
		$id = $dbr->selectField(
			'page',	// Table
			'page_id',	//Field
			array(	//Conditions
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBKey(),
			),
			__METHOD__	//Function name
		);
		return $id;
	}


	
	function findBySha1( $hash ) {
		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			array( 'img_sha1' => $hash )
		);
		
		$result = array();
		while ( $row = $res->fetchObject() )
			$result[] = $this->newFileFromRow( $row );
		$res->free();
		return $result;
	}
	
	/*
	 * Find many files using one query
	 */
	function findFiles( $titles ) {
	 	// FIXME: Only accepts a $titles array where the keys are the sanitized
	 	// file names.
	 	 
		if ( count( $titles ) == 0 ) return array();		
	
		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			array( 'img_name' => array_keys( $titles ) )		
		);
		
		$result = array();
		while ( $row = $res->fetchObject() ) {
			$result[$row->img_name] = $this->newFileFromRow( $row );
		}
		$res->free();
		return $result;
	}
}
