<?php
/**
 * Local repository that stores files in the local filesystem and registers them
 * in the wiki's own database.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A repository that stores files in the local filesystem and registers them
 * in the wiki's own database. This is the most commonly used repository class.
 *
 * @ingroup FileRepo
 */
class LocalRepo extends FileRepo {
	var $fileFactory           = array( 'LocalFile'   , 'newFromTitle' );
	var $fileFactoryKey        = array( 'LocalFile'   , 'newFromKey'   );
	var $fileFromRowFactory    = array( 'LocalFile'   , 'newFromRow'   );
	var $oldFileFactory        = array( 'OldLocalFile', 'newFromTitle' );
	var $oldFileFactoryKey     = array( 'OldLocalFile', 'newFromKey'   );
	var $oldFileFromRowFactory = array( 'OldLocalFile', 'newFromRow'   );

	/**
	 * @throws MWException
	 * @param $row
	 * @return File
	 */
	function newFileFromRow( $row ) {
		if ( isset( $row->img_name ) ) {
			return call_user_func( $this->fileFromRowFactory, $row, $this );
		} elseif ( isset( $row->oi_name ) ) {
			return call_user_func( $this->oldFileFromRowFactory, $row, $this );
		} else {
			throw new MWException( __METHOD__.': invalid row' );
		}
	}

	/**
	 * @param $title
	 * @param $archiveName
	 * @return OldLocalFile
	 */
	function newFromArchiveName( $title, $archiveName ) {
		return OldLocalFile::newFromArchiveName( $title, $this, $archiveName );
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the
	 * filearchive table. This needs to be done in the repo because it needs to
	 * interleave database locks with file operations, which is potentially a
	 * remote operation.
	 *
	 * @param $storageKeys array
	 *
	 * @return FileRepoStatus
	 */
	function cleanupDeletedBatch( $storageKeys ) {
		$backend = $this->backend; // convenience
		$root = $this->getZonePath( 'deleted' );
		$dbw = $this->getMasterDB();
		$status = $this->newGood();
		$storageKeys = array_unique( $storageKeys );
		foreach ( $storageKeys as $key ) {
			$hashPath = $this->getDeletedHashPath( $key );
			$path = "$root/$hashPath$key";
			$dbw->begin();
			// Check for usage in deleted/hidden files and pre-emptively
			// lock the key to avoid any future use until we are finished.
			$deleted = $this->deletedFileHasKey( $key, 'lock' );
			$hidden = $this->hiddenFileHasKey( $key, 'lock' );
			if ( !$deleted && !$hidden ) { // not in use now
				wfDebug( __METHOD__ . ": deleting $key\n" );
				$op = array( 'op' => 'delete', 'src' => $path );
				if ( !$backend->doOperation( $op )->isOK() ) {
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
	 * Check if a deleted (filearchive) file has this sha1 key
	 *
	 * @param $key String File storage key (base-36 sha1 key with file extension)
	 * @param $lock String|null Use "lock" to lock the row via FOR UPDATE
	 * @return bool File with this key is in use
	 */
	protected function deletedFileHasKey( $key, $lock = null ) {
		$options = ( $lock === 'lock' ) ? array( 'FOR UPDATE' ) : array();

		$dbw = $this->getMasterDB();
		return (bool)$dbw->selectField( 'filearchive', '1',
			array( 'fa_storage_group' => 'deleted', 'fa_storage_key' => $key ),
			__METHOD__, $options
		);
	}

	/**
	 * Check if a hidden (revision delete) file has this sha1 key
	 *
	 * @param $key String File storage key (base-36 sha1 key with file extension)
	 * @param $lock String|null Use "lock" to lock the row via FOR UPDATE
	 * @return bool File with this key is in use
	 */
	protected function hiddenFileHasKey( $key, $lock = null ) {
		$options = ( $lock === 'lock' ) ? array( 'FOR UPDATE' ) : array();

		$sha1 = self::getHashFromKey( $key );
		$ext = File::normalizeExtension( substr( $key, strcspn( $key, '.' ) + 1 ) );

		$dbw = $this->getMasterDB();
		return (bool)$dbw->selectField( 'oldimage', '1',
			array( 'oi_sha1' => $sha1,
				'oi_archive_name ' . $dbw->buildLike( $dbw->anyString(), ".$ext" ),
				$dbw->bitAnd( 'oi_deleted', File::DELETED_FILE ) => File::DELETED_FILE ),
			__METHOD__, $options
		);
	}

	/**
	 * Gets the SHA1 hash from a storage key
	 *
	 * @param string $key
	 * @return string
	 */
	public static function getHashFromKey( $key ) {
		return strtok( $key, '.' );
	}
	
	/**
	 * Checks if there is a redirect named as $title
	 *
	 * @param $title Title of file
	 * @return bool
	 */
	function checkRedirect( Title $title ) {
		global $wgMemc;

		$title = File::normalizeTitle( $title, 'exception' );

		$memcKey = $this->getSharedCacheKey( 'image_redirect', md5( $title->getDBkey() ) );
		if ( $memcKey === false ) {
			$memcKey = $this->getLocalCacheKey( 'image_redirect', md5( $title->getDBkey() ) );
			$expiry = 300; // no invalidation, 5 minutes
		} else {
			$expiry = 86400; // has invalidation, 1 day
		}
		$cachedValue = $wgMemc->get( $memcKey );
		if ( $cachedValue === ' '  || $cachedValue === '' ) {
			// Does not exist
			return false;
		} elseif ( strval( $cachedValue ) !== '' ) {
			return Title::newFromText( $cachedValue, NS_FILE );
		} // else $cachedValue is false or null: cache miss

		$id = $this->getArticleID( $title );
		if( !$id ) {
			$wgMemc->set( $memcKey, " ", $expiry );
			return false;
		}
		$dbr = $this->getSlaveDB();
		$row = $dbr->selectRow(
			'redirect',
			array( 'rd_title', 'rd_namespace' ),
			array( 'rd_from' => $id ),
			__METHOD__
		);

		if( $row && $row->rd_namespace == NS_FILE ) {
			$targetTitle = Title::makeTitle( $row->rd_namespace, $row->rd_title );
			$wgMemc->set( $memcKey, $targetTitle->getDBkey(), $expiry );
			return $targetTitle;
		} else {
			$wgMemc->set( $memcKey, '', $expiry );
			return false;
		}
	}


	/**
	 * Function link Title::getArticleID().
	 * We can't say Title object, what database it should use, so we duplicate that function here.
	 *
	 * @param $title Title
	 */
	protected function getArticleID( $title ) {
		if( !$title instanceof Title ) {
			return 0;
		}
		$dbr = $this->getSlaveDB();
		$id = $dbr->selectField(
			'page', // Table
			'page_id',  //Field
			array(  //Conditions
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
			),
			__METHOD__  //Function name
		);
		return $id;
	}

	/**
	 * Get an array or iterator of file objects for files that have a given
	 * SHA-1 content hash.
	 *
	 * @param $hash String a sha1 hash to look for
	 * @return Array
	 */
	function findBySha1( $hash ) {
		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			array( 'img_sha1' => $hash )
		);
		
		$result = array();
		foreach ( $res as $row ) {
			$result[] = $this->newFileFromRow( $row );
		}
		$res->free();

		return $result;
	}

	/**
	 * Get a connection to the slave DB
	 */
	function getSlaveDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * Get a connection to the master DB
	 */
	function getMasterDB() {
		return wfGetDB( DB_MASTER );
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site. 
	 * The parameters are the parts of the key, as for wfMemcKey().
	 *
	 * @return string
	 */
	function getSharedCacheKey( /*...*/ ) {
		$args = func_get_args();
		return call_user_func_array( 'wfMemcKey', $args );
	}

	/**
	 * Invalidates image redirect cache related to that image
	 *
	 * @param $title Title of page
	 * @return void
	 */
	function invalidateImageRedirect( Title $title ) {
		global $wgMemc;
		$memcKey = $this->getSharedCacheKey( 'image_redirect', md5( $title->getDBkey() ) );
		if ( $memcKey ) {
			$wgMemc->delete( $memcKey );
		}
	}
}

