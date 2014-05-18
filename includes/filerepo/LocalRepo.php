<?php
/**
 * Local repository that stores files in the local filesystem and registers them
 * in the wiki's own database.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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
	/** @var array */
	protected $fileFactory = array( 'LocalFile', 'newFromTitle' );

	/** @var array */
	protected $fileFactoryKey = array( 'LocalFile', 'newFromKey' );

	/** @var array */
	protected $fileFromRowFactory = array( 'LocalFile', 'newFromRow' );

	/** @var array */
	protected $oldFileFromRowFactory = array( 'OldLocalFile', 'newFromRow' );

	/** @var array */
	protected $oldFileFactory = array( 'OldLocalFile', 'newFromTitle' );

	/** @var array */
	protected $oldFileFactoryKey = array( 'OldLocalFile', 'newFromKey' );

	/**
	 * @throws MWException
	 * @param array $row
	 * @return LocalFile
	 */
	function newFileFromRow( $row ) {
		if ( isset( $row->img_name ) ) {
			return call_user_func( $this->fileFromRowFactory, $row, $this );
		} elseif ( isset( $row->oi_name ) ) {
			return call_user_func( $this->oldFileFromRowFactory, $row, $this );
		} else {
			throw new MWException( __METHOD__ . ': invalid row' );
		}
	}

	/**
	 * @param Title $title
	 * @param string $archiveName
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
	 * @param array $storageKeys
	 *
	 * @return FileRepoStatus
	 */
	function cleanupDeletedBatch( array $storageKeys ) {
		$backend = $this->backend; // convenience
		$root = $this->getZonePath( 'deleted' );
		$dbw = $this->getMasterDB();
		$status = $this->newGood();
		$storageKeys = array_unique( $storageKeys );
		foreach ( $storageKeys as $key ) {
			$hashPath = $this->getDeletedHashPath( $key );
			$path = "$root/$hashPath$key";
			$dbw->begin( __METHOD__ );
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
			$dbw->commit( __METHOD__ );
		}

		return $status;
	}

	/**
	 * Check if a deleted (filearchive) file has this sha1 key
	 *
	 * @param string $key File storage key (base-36 sha1 key with file extension)
	 * @param string|null $lock Use "lock" to lock the row via FOR UPDATE
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
	 * @param string $key File storage key (base-36 sha1 key with file extension)
	 * @param string|null $lock Use "lock" to lock the row via FOR UPDATE
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
	 * @param Title $title Title of file
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
		if ( $cachedValue === ' ' || $cachedValue === '' ) {
			// Does not exist
			return false;
		} elseif ( strval( $cachedValue ) !== '' && $cachedValue !== ' PURGED' ) {
			return Title::newFromText( $cachedValue, NS_FILE );
		} // else $cachedValue is false or null: cache miss

		$id = $this->getArticleID( $title );
		if ( !$id ) {
			$wgMemc->add( $memcKey, " ", $expiry );

			return false;
		}
		$dbr = $this->getSlaveDB();
		$row = $dbr->selectRow(
			'redirect',
			array( 'rd_title', 'rd_namespace' ),
			array( 'rd_from' => $id ),
			__METHOD__
		);

		if ( $row && $row->rd_namespace == NS_FILE ) {
			$targetTitle = Title::makeTitle( $row->rd_namespace, $row->rd_title );
			$wgMemc->add( $memcKey, $targetTitle->getDBkey(), $expiry );

			return $targetTitle;
		} else {
			$wgMemc->add( $memcKey, '', $expiry );

			return false;
		}
	}

	/**
	 * Function link Title::getArticleID().
	 * We can't say Title object, what database it should use, so we duplicate that function here.
	 *
	 * @param Title $title
	 * @return bool|int|mixed
	 */
	protected function getArticleID( $title ) {
		if ( !$title instanceof Title ) {
			return 0;
		}
		$dbr = $this->getSlaveDB();
		$id = $dbr->selectField(
			'page', // Table
			'page_id', //Field
			array( //Conditions
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
			),
			__METHOD__ //Function name
		);

		return $id;
	}

	public function findFiles( array $items, $flags = 0 ) {
		$finalFiles = array(); // map of (DB key => corresponding File) for matches

		$searchSet = array(); // map of (normalized DB key => search params)
		foreach ( $items as $item ) {
			if ( is_array( $item ) ) {
				$title = File::normalizeTitle( $item['title'] );
				if ( $title ) {
					$searchSet[$title->getDBkey()] = $item;
				}
			} else {
				$title = File::normalizeTitle( $item );
				if ( $title ) {
					$searchSet[$title->getDBkey()] = array();
				}
			}
		}

		$fileMatchesSearch = function ( File $file, array $search ) {
			// Note: file name comparison done elsewhere (to handle redirects)
			$user = ( !empty( $search['private'] ) && $search['private'] instanceof User )
				? $search['private']
				: null;

			return (
				$file->exists() &&
				(
					( empty( $search['time'] ) && !$file->isOld() ) ||
					( !empty( $search['time'] ) && $search['time'] === $file->getTimestamp() )
				) &&
				( !empty( $search['private'] ) || !$file->isDeleted( File::DELETED_FILE ) ) &&
				$file->userCan( File::DELETED_FILE, $user )
			);
		};

		$repo = $this;
		$applyMatchingFiles = function ( ResultWrapper $res, &$searchSet, &$finalFiles )
			use ( $repo, $fileMatchesSearch, $flags )
		{
			global $wgContLang;
			$info = $repo->getInfo();
			foreach ( $res as $row ) {
				$file = $repo->newFileFromRow( $row );
				// There must have been a search for this DB key, but this has to handle the
				// cases were title capitalization is different on the client and repo wikis.
				$dbKeysLook = array( str_replace( ' ', '_', $file->getName() ) );
				if ( !empty( $info['initialCapital'] ) ) {
					// Search keys for "hi.png" and "Hi.png" should use the "Hi.png file"
					$dbKeysLook[] = $wgContLang->lcfirst( $file->getName() );
				}
				foreach ( $dbKeysLook as $dbKey ) {
					if ( isset( $searchSet[$dbKey] )
						&& $fileMatchesSearch( $file, $searchSet[$dbKey] )
					) {
						$finalFiles[$dbKey] = ( $flags & FileRepo::NAME_AND_TIME_ONLY )
							? array( 'title' => $dbKey, 'timestamp' => $file->getTimestamp() )
							: $file;
						unset( $searchSet[$dbKey] );
					}
				}
			}
		};

		$dbr = $this->getSlaveDB();

		// Query image table
		$imgNames = array();
		foreach ( array_keys( $searchSet ) as $dbKey ) {
			$imgNames[] = $this->getNameFromTitle( File::normalizeTitle( $dbKey ) );
		}

		if ( count( $imgNames ) ) {
			$res = $dbr->select( 'image',
				LocalFile::selectFields(), array( 'img_name' => $imgNames ), __METHOD__ );
			$applyMatchingFiles( $res, $searchSet, $finalFiles );
		}

		// Query old image table
		$oiConds = array(); // WHERE clause array for each file
		foreach ( $searchSet as $dbKey => $search ) {
			if ( isset( $search['time'] ) ) {
				$oiConds[] = $dbr->makeList(
					array(
						'oi_name' => $this->getNameFromTitle( File::normalizeTitle( $dbKey ) ),
						'oi_timestamp' => $dbr->timestamp( $search['time'] )
					),
					LIST_AND
				);
			}
		}

		if ( count( $oiConds ) ) {
			$res = $dbr->select( 'oldimage',
				OldLocalFile::selectFields(), $dbr->makeList( $oiConds, LIST_OR ), __METHOD__ );
			$applyMatchingFiles( $res, $searchSet, $finalFiles );
		}

		// Check for redirects...
		foreach ( $searchSet as $dbKey => $search ) {
			if ( !empty( $search['ignoreRedirect'] ) ) {
				continue;
			}

			$title = File::normalizeTitle( $dbKey );
			$redir = $this->checkRedirect( $title ); // hopefully hits memcached

			if ( $redir && $redir->getNamespace() == NS_FILE ) {
				$file = $this->newFile( $redir );
				if ( $file && $fileMatchesSearch( $file, $search ) ) {
					$file->redirectedFrom( $title->getDBkey() );
					if ( $flags & FileRepo::NAME_AND_TIME_ONLY ) {
						$finalFiles[$dbKey] = array(
							'title' => $file->getTitle()->getDBkey(),
							'timestamp' => $file->getTimestamp()
						);
					} else {
						$finalFiles[$dbKey] = $file;
					}
				}
			}
		}

		return $finalFiles;
	}

	/**
	 * Get an array or iterator of file objects for files that have a given
	 * SHA-1 content hash.
	 *
	 * @param string $hash a sha1 hash to look for
	 * @return array
	 */
	function findBySha1( $hash ) {
		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			array( 'img_sha1' => $hash ),
			__METHOD__,
			array( 'ORDER BY' => 'img_name' )
		);

		$result = array();
		foreach ( $res as $row ) {
			$result[] = $this->newFileFromRow( $row );
		}
		$res->free();

		return $result;
	}

	/**
	 * Get an array of arrays or iterators of file objects for files that
	 * have the given SHA-1 content hashes.
	 *
	 * Overrides generic implementation in FileRepo for performance reason
	 *
	 * @param array $hashes An array of hashes
	 * @return array An Array of arrays or iterators of file objects and the hash as key
	 */
	function findBySha1s( array $hashes ) {
		if ( !count( $hashes ) ) {
			return array(); //empty parameter
		}

		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			array( 'img_sha1' => $hashes ),
			__METHOD__,
			array( 'ORDER BY' => 'img_name' )
		);

		$result = array();
		foreach ( $res as $row ) {
			$file = $this->newFileFromRow( $row );
			$result[$file->getSha1()][] = $file;
		}
		$res->free();

		return $result;
	}

	/**
	 * Return an array of files where the name starts with $prefix.
	 *
	 * @param string $prefix The prefix to search for
	 * @param int $limit The maximum amount of files to return
	 * @return array
	 */
	public function findFilesByPrefix( $prefix, $limit ) {
		$selectOptions = array( 'ORDER BY' => 'img_name', 'LIMIT' => intval( $limit ) );

		// Query database
		$dbr = $this->getSlaveDB();
		$res = $dbr->select(
			'image',
			LocalFile::selectFields(),
			'img_name ' . $dbr->buildLike( $prefix, $dbr->anyString() ),
			__METHOD__,
			$selectOptions
		);

		// Build file objects
		$files = array();
		foreach ( $res as $row ) {
			$files[] = $this->newFileFromRow( $row );
		}

		return $files;
	}

	/**
	 * Get a connection to the slave DB
	 * @return DatabaseBase
	 */
	function getSlaveDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * Get a connection to the master DB
	 * @return DatabaseBase
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
	 * @param Title $title Title of page
	 * @return void
	 */
	function invalidateImageRedirect( Title $title ) {
		global $wgMemc;
		$memcKey = $this->getSharedCacheKey( 'image_redirect', md5( $title->getDBkey() ) );
		if ( $memcKey ) {
			// Set a temporary value for the cache key, to ensure
			// that this value stays purged long enough so that
			// it isn't refreshed with a stale value due to a
			// lagged slave.
			$wgMemc->set( $memcKey, ' PURGED', 12 );
		}
	}

	/**
	 * Return information about the repository.
	 *
	 * @return array
	 * @since 1.22
	 */
	function getInfo() {
		global $wgFavicon;

		return array_merge( parent::getInfo(), array(
			'favicon' => wfExpandUrl( $wgFavicon ),
		) );
	}
}
