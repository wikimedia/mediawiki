<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * @brief Base class for all backends using particular storage medium.
 *
 * This class defines the methods as abstract that subclasses must implement.
 * Outside callers should *not* use functions with "Internal" in the name.
 *
 * The FileBackend operations are implemented using basic functions
 * such as storeInternal(), copyInternal(), deleteInternal() and the like.
 * This class is also responsible for path resolution and sanitization.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackendStore extends FileBackend {
	/** @var Array Map of paths to small (RAM/disk) cache items */
	protected $cache = array(); // (storage path => key => value)
	protected $maxCacheSize = 100; // integer; max paths with entries
	/** @var Array Map of paths to large (RAM/disk) cache items */
	protected $expensiveCache = array(); // (storage path => key => value)
	protected $maxExpensiveCacheSize = 10; // integer; max paths with entries

	/** @var Array Map of container names to sharding settings */
	protected $shardViaHashLevels = array(); // (container name => config array)

	protected $maxFileSize = 4294967296; // integer bytes (4GiB)

	/**
	 * Get the maximum allowable file size given backend
	 * medium restrictions and basic performance constraints.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * @return integer Bytes
	 */
	final public function maxFileSizeInternal() {
		return $this->maxFileSize;
	}

	/**
	 * Check if a file can be created at a given storage path.
	 * FS backends should check if the parent directory exists and the file is writable.
	 * Backends using key/value stores should check if the container exists.
	 *
	 * @param $storagePath string
	 * @return bool
	 */
	abstract public function isPathUsableInternal( $storagePath );

	/**
	 * Create a file in the backend with the given contents.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *     content       : the raw file contents
	 *     dst           : destination storage path
	 *     overwrite     : overwrite any file that exists at the destination
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function createInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		if ( strlen( $params['content'] ) > $this->maxFileSizeInternal() ) {
			$status = Status::newFatal( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doCreateInternal( $params );
			$this->clearCache( array( $params['dst'] ) );
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::createInternal()
	 */
	abstract protected function doCreateInternal( array $params );

	/**
	 * Store a file into the backend from a file on disk.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *     src           : source path on disk
	 *     dst           : destination storage path
	 *     overwrite     : overwrite any file that exists at the destination
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function storeInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		if ( filesize( $params['src'] ) > $this->maxFileSizeInternal() ) {
			$status = Status::newFatal( 'backend-fail-store', $params['dst'] );
		} else {
			$status = $this->doStoreInternal( $params );
			$this->clearCache( array( $params['dst'] ) );
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::storeInternal()
	 */
	abstract protected function doStoreInternal( array $params );

	/**
	 * Copy a file from one storage path to another in the backend.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *     src           : source storage path
	 *     dst           : destination storage path
	 *     overwrite     : overwrite any file that exists at the destination
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function copyInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doCopyInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::copyInternal()
	 */
	abstract protected function doCopyInternal( array $params );

	/**
	 * Delete a file at the storage path.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *     src                 : source storage path
	 *     ignoreMissingSource : do nothing if the source file does not exist
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function deleteInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doDeleteInternal( $params );
		$this->clearCache( array( $params['src'] ) );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::deleteInternal()
	 */
	abstract protected function doDeleteInternal( array $params );

	/**
	 * Move a file from one storage path to another in the backend.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *     src           : source storage path
	 *     dst           : destination storage path
	 *     overwrite     : overwrite any file that exists at the destination
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function moveInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doMoveInternal( $params );
		$this->clearCache( array( $params['src'], $params['dst'] ) );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::moveInternal()
	 * @return Status
	 */
	protected function doMoveInternal( array $params ) {
		// Copy source to dest
		$status = $this->copyInternal( $params );
		if ( $status->isOK() ) {
			// Delete source (only fails due to races or medium going down)
			$status->merge( $this->deleteInternal( array( 'src' => $params['src'] ) ) );
			$status->setResult( true, $status->value ); // ignore delete() errors
		}
		return $status;
	}

	/**
	 * @see FileBackend::concatenate()
	 * @return Status
	 */
	final public function concatenate( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		// Try to lock the source files for the scope of this function
		$scopeLockS = $this->getScopedFileLocks( $params['srcs'], LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			// Actually do the concatenation
			$status->merge( $this->doConcatenate( $params ) );
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::concatenate()
	 * @return Status
	 */
	protected function doConcatenate( array $params ) {
		$status = Status::newGood();
		$tmpPath = $params['dst']; // convenience

		// Check that the specified temp file is valid...
		wfSuppressWarnings();
		$ok = ( is_file( $tmpPath ) && !filesize( $tmpPath ) );
		wfRestoreWarnings();
		if ( !$ok ) { // not present or not empty
			$status->fatal( 'backend-fail-opentemp', $tmpPath );
			return $status;
		}

		// Build up the temp file using the source chunks (in order)...
		$tmpHandle = fopen( $tmpPath, 'ab' );
		if ( $tmpHandle === false ) {
			$status->fatal( 'backend-fail-opentemp', $tmpPath );
			return $status;
		}
		foreach ( $params['srcs'] as $virtualSource ) {
			// Get a local FS version of the chunk
			$tmpFile = $this->getLocalReference( array( 'src' => $virtualSource ) );
			if ( !$tmpFile ) {
				$status->fatal( 'backend-fail-read', $virtualSource );
				return $status;
			}
			// Get a handle to the local FS version
			$sourceHandle = fopen( $tmpFile->getPath(), 'r' );
			if ( $sourceHandle === false ) {
				fclose( $tmpHandle );
				$status->fatal( 'backend-fail-read', $virtualSource );
				return $status;
			}
			// Append chunk to file (pass chunk size to avoid magic quotes)
			if ( !stream_copy_to_stream( $sourceHandle, $tmpHandle ) ) {
				fclose( $sourceHandle );
				fclose( $tmpHandle );
				$status->fatal( 'backend-fail-writetemp', $tmpPath );
				return $status;
			}
			fclose( $sourceHandle );
		}
		if ( !fclose( $tmpHandle ) ) {
			$status->fatal( 'backend-fail-closetemp', $tmpPath );
			return $status;
		}

		clearstatcache(); // temp file changed

		return $status;
	}

	/**
	 * @see FileBackend::doPrepare()
	 * @return Status
	 */
	final protected function doPrepare( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );

		$status = Status::newGood();
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doPrepareInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPrepareInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepare()
	 * @return Status
	 */
	protected function doPrepareInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::doSecure()
	 * @return Status
	 */
	final protected function doSecure( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doSecureInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doSecure()
	 * @return Status
	 */
	protected function doSecureInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::doClean()
	 * @return Status
	 */
	final protected function doClean( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $status; // invalid storage path
		}

		// Attempt to lock this directory...
		$filesLockEx = array( $params['dir'] );
		$scopedLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
		if ( !$status->isOK() ) {
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $status; // abort
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doCleanInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doCleanInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doClean()
	 * @return Status
	 */
	protected function doCleanInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::fileExists()
	 * @return bool|null
	 */
	final public function fileExists( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return ( $stat === null ) ? null : (bool)$stat; // null => failure
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 * @return bool
	 */
	final public function getFileTimestamp( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $stat ? $stat['mtime'] : false;
	}

	/**
	 * @see FileBackend::getFileSize()
	 * @return bool
	 */
	final public function getFileSize( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $stat ? $stat['size'] : false;
	}

	/**
	 * @see FileBackend::getFileStat()
	 * @return bool
	 */
	final public function getFileStat( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return false; // invalid storage path
		}
		$latest = !empty( $params['latest'] );
		if ( isset( $this->cache[$path]['stat'] ) ) {
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $this->cache[$path]['stat']['latest'] ) {
				$this->pingCache( $path ); // LRU
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $this->cache[$path]['stat'];
			}
		}
		wfProfileIn( __METHOD__ . '-miss' );
		wfProfileIn( __METHOD__ . '-miss-' . $this->name );
		$stat = $this->doGetFileStat( $params );
		wfProfileOut( __METHOD__ . '-miss-' . $this->name );
		wfProfileOut( __METHOD__ . '-miss' );
		if ( is_array( $stat ) ) { // don't cache negatives
			$this->trimCache(); // limit memory
			$this->cache[$path]['stat'] = $stat;
			$this->cache[$path]['stat']['latest'] = $latest;
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $stat;
	}

	/**
	 * @see FileBackendStore::getFileStat()
	 */
	abstract protected function doGetFileStat( array $params );

	/**
	 * @see FileBackend::getFileContents()
	 * @return bool|string
	 */
	public function getFileContents( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$tmpFile = $this->getLocalReference( $params );
		if ( !$tmpFile ) {
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return false;
		}
		wfSuppressWarnings();
		$data = file_get_contents( $tmpFile->getPath() );
		wfRestoreWarnings();
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $data;
	}

	/**
	 * @see FileBackend::getFileSha1Base36()
	 * @return bool|string
	 */
	final public function getFileSha1Base36( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$path = $params['src'];
		if ( isset( $this->cache[$path]['sha1'] ) ) {
			$this->pingCache( $path ); // LRU
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $this->cache[$path]['sha1'];
		}
		wfProfileIn( __METHOD__ . '-miss' );
		wfProfileIn( __METHOD__ . '-miss-' . $this->name );
		$hash = $this->doGetFileSha1Base36( $params );
		wfProfileOut( __METHOD__ . '-miss-' . $this->name );
		wfProfileOut( __METHOD__ . '-miss' );
		if ( $hash ) { // don't cache negatives
			$this->trimCache(); // limit memory
			$this->cache[$path]['sha1'] = $hash;
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $hash;
	}

	/**
	 * @see FileBackendStore::getFileSha1Base36()
	 * @return bool
	 */
	protected function doGetFileSha1Base36( array $params ) {
		$fsFile = $this->getLocalReference( $params );
		if ( !$fsFile ) {
			return false;
		} else {
			return $fsFile->getSha1Base36();
		}
	}

	/**
	 * @see FileBackend::getFileProps()
	 * @return Array
	 */
	final public function getFileProps( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$fsFile = $this->getLocalReference( $params );
		$props = $fsFile ? $fsFile->getProps() : FSFile::placeholderProps();
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $props;
	}

	/**
	 * @see FileBackend::getLocalReference()
	 * @return TempFSFile|null
	 */
	public function getLocalReference( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$path = $params['src'];
		if ( isset( $this->expensiveCache[$path]['localRef'] ) ) {
			$this->pingExpensiveCache( $path );
			wfProfileOut( __METHOD__ . '-' . $this->name );
			wfProfileOut( __METHOD__ );
			return $this->expensiveCache[$path]['localRef'];
		}
		$tmpFile = $this->getLocalCopy( $params );
		if ( $tmpFile ) { // don't cache negatives
			$this->trimExpensiveCache(); // limit memory
			$this->expensiveCache[$path]['localRef'] = $tmpFile;
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $tmpFile;
	}

	/**
	 * @see FileBackend::streamFile()
	 * @return Status
	 */
	final public function streamFile( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		$info = $this->getFileStat( $params );
		if ( !$info ) { // let StreamFile handle the 404
			$status->fatal( 'backend-fail-notexists', $params['src'] );
		}

		// Set output buffer and HTTP headers for stream
		$extraHeaders = isset( $params['headers'] ) ? $params['headers'] : array();
		$res = StreamFile::prepareForStream( $params['src'], $info, $extraHeaders );
		if ( $res == StreamFile::NOT_MODIFIED ) {
			// do nothing; client cache is up to date
		} elseif ( $res == StreamFile::READY_STREAM ) {
			wfProfileIn( __METHOD__ . '-send' );
			wfProfileIn( __METHOD__ . '-send-' . $this->name );
			$status = $this->doStreamFile( $params );
			wfProfileOut( __METHOD__ . '-send-' . $this->name );
			wfProfileOut( __METHOD__ . '-send' );
		} else {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::streamFile()
	 * @return Status
	 */
	protected function doStreamFile( array $params ) {
		$status = Status::newGood();

		$fsFile = $this->getLocalReference( $params );
		if ( !$fsFile ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		} elseif ( !readfile( $fsFile->getPath() ) ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		return $status;
	}

	/**
	 * @see FileBackend::directoryExists()
	 * @return bool|null
	 */
	final public function directoryExists( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			return false; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			return $this->doDirectoryExists( $fullCont, $dir, $params );
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			$res = false; // response
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$exists = $this->doDirectoryExists( "{$fullCont}{$suffix}", $dir, $params );
				if ( $exists ) {
					$res = true;
					break; // found one!
				} elseif ( $exists === null ) { // error?
					$res = null; // if we don't find anything, it is indeterminate
				}
			}
			return $res;
		}
	}

	/**
	 * @see FileBackendStore::directoryExists()
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $params Array
	 * @return bool|null
	 */
	abstract protected function doDirectoryExists( $container, $dir, array $params );

	/**
	 * @see FileBackend::getSubdirectoryList()
	 * @return Array|null|Traversable
	 */
	final public function getSubdirectoryList( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getSubdirectoryListInternal( $fullCont, $dir, $params );
		} else {
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			// File listing spans multiple containers/shards
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			return new FileBackendStoreShardDirIterator( $this,
				$fullCont, $dir, $this->getContainerSuffixes( $shortCont ), $params );
		}
	}

	/**
	 * Do not call this function from places outside FileBackend
	 *
	 * @see FileBackendStore::getSubdirectoryList()
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $params Array
	 * @return Traversable|Array|null
	 */
	abstract public function getSubdirectoryListInternal( $container, $dir, array $params );

	/**
	 * @see FileBackend::getFileList()
	 * @return Array|null|Traversable
	 */
	final public function getFileList( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getFileListInternal( $fullCont, $dir, $params );
		} else {
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			// File listing spans multiple containers/shards
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			return new FileBackendStoreShardFileIterator( $this,
				$fullCont, $dir, $this->getContainerSuffixes( $shortCont ), $params );
		}
	}

	/**
	 * Do not call this function from places outside FileBackend
	 *
	 * @see FileBackendStore::getFileList()
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $params Array
	 * @return Traversable|Array|null
	 */
	abstract public function getFileListInternal( $container, $dir, array $params );

	/**
	 * Get the list of supported operations and their corresponding FileOp classes.
	 *
	 * @return Array
	 */
	protected function supportedOperations() {
		return array(
			'store'       => 'StoreFileOp',
			'copy'        => 'CopyFileOp',
			'move'        => 'MoveFileOp',
			'delete'      => 'DeleteFileOp',
			'create'      => 'CreateFileOp',
			'null'        => 'NullFileOp'
		);
	}

	/**
	 * Return a list of FileOp objects from a list of operations.
	 * Do not call this function from places outside FileBackend.
	 *
	 * The result must have the same number of items as the input.
	 * An exception is thrown if an unsupported operation is requested.
	 *
	 * @param $ops Array Same format as doOperations()
	 * @return Array List of FileOp objects
	 * @throws MWException
	 */
	final public function getOperations( array $ops ) {
		$supportedOps = $this->supportedOperations();

		$performOps = array(); // array of FileOp objects
		// Build up ordered array of FileOps...
		foreach ( $ops as $operation ) {
			$opName = $operation['op'];
			if ( isset( $supportedOps[$opName] ) ) {
				$class = $supportedOps[$opName];
				// Get params for this operation
				$params = $operation;
				// Append the FileOp class
				$performOps[] = new $class( $this, $params );
			} else {
				throw new MWException( "Operation `$opName` is not supported." );
			}
		}

		return $performOps;
	}

	/**
	 * @see FileBackend::doOperationsInternal()
	 * @return Status
	 */
	protected function doOperationsInternal( array $ops, array $opts ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		// Build up a list of FileOps...
		$performOps = $this->getOperations( $ops );

		// Acquire any locks as needed...
		if ( empty( $opts['nonLocking'] ) ) {
			// Build up a list of files to lock...
			$filesLockEx = $filesLockSh = array();
			foreach ( $performOps as $fileOp ) {
				$filesLockSh = array_merge( $filesLockSh, $fileOp->storagePathsRead() );
				$filesLockEx = array_merge( $filesLockEx, $fileOp->storagePathsChanged() );
			}
			// Optimization: if doing an EX lock anyway, don't also set an SH one
			$filesLockSh = array_diff( $filesLockSh, $filesLockEx );
			// Get a shared lock on the parent directory of each path changed
			$filesLockSh = array_merge( $filesLockSh, array_map( 'dirname', $filesLockEx ) );
			// Try to lock those files for the scope of this function...
			$scopeLockS = $this->getScopedFileLocks( $filesLockSh, LockManager::LOCK_UW, $status );
			$scopeLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
			if ( !$status->isOK() ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $status; // abort
			}
		}

		// Clear any cache entries (after locks acquired)
		$this->clearCache();

		// Actually attempt the operation batch...
		$subStatus = FileOp::attemptBatch( $performOps, $opts, $this->fileJournal );

		// Merge errors into status fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackend::clearCache()
	 */
	final public function clearCache( array $paths = null ) {
		if ( is_array( $paths ) ) {
			$paths = array_map( 'FileBackend::normalizeStoragePath', $paths );
			$paths = array_filter( $paths, 'strlen' ); // remove nulls
		}
		if ( $paths === null ) {
			$this->cache = array();
			$this->expensiveCache = array();
		} else {
			foreach ( $paths as $path ) {
				unset( $this->cache[$path] );
				unset( $this->expensiveCache[$path] );
			}
		}
		$this->doClearCache( $paths );
	}

	/**
	 * Clears any additional stat caches for storage paths
	 *
	 * @see FileBackend::clearCache()
	 *
	 * @param $paths Array Storage paths (optional)
	 * @return void
	 */
	protected function doClearCache( array $paths = null ) {}

	/**
	 * Move a cache entry to the top (such as when accessed)
	 *
	 * @param $path string Storage path
	 */
	protected function pingCache( $path ) {
		if ( isset( $this->cache[$path] ) ) {
			$tmp = $this->cache[$path];
			unset( $this->cache[$path] );
			$this->cache[$path] = $tmp;
		}
	}

	/**
	 * Prune the inexpensive cache if it is too big to add an item
	 *
	 * @return void
	 */
	protected function trimCache() {
		if ( count( $this->cache ) >= $this->maxCacheSize ) {
			reset( $this->cache );
			unset( $this->cache[key( $this->cache )] );
		}
	}

	/**
	 * Move a cache entry to the top (such as when accessed)
	 *
	 * @param $path string Storage path
	 */
	protected function pingExpensiveCache( $path ) {
		if ( isset( $this->expensiveCache[$path] ) ) {
			$tmp = $this->expensiveCache[$path];
			unset( $this->expensiveCache[$path] );
			$this->expensiveCache[$path] = $tmp;
		}
	}

	/**
	 * Prune the expensive cache if it is too big to add an item
	 *
	 * @return void
	 */
	protected function trimExpensiveCache() {
		if ( count( $this->expensiveCache ) >= $this->maxExpensiveCacheSize ) {
			reset( $this->expensiveCache );
			unset( $this->expensiveCache[key( $this->expensiveCache )] );
		}
	}

	/**
	 * Check if a container name is valid.
	 * This checks for for length and illegal characters.
	 *
	 * @param $container string
	 * @return bool
	 */
	final protected static function isValidContainerName( $container ) {
		// This accounts for Swift and S3 restrictions while leaving room
		// for things like '.xxx' (hex shard chars) or '.seg' (segments).
		// This disallows directory separators or traversal characters.
		// Note that matching strings URL encode to the same string;
		// in Swift, the length restriction is *after* URL encoding.
		return preg_match( '/^[a-z0-9][a-z0-9-_]{0,199}$/i', $container );
	}

	/**
	 * Splits a storage path into an internal container name,
	 * an internal relative file name, and a container shard suffix.
	 * Any shard suffix is already appended to the internal container name.
	 * This also checks that the storage path is valid and within this backend.
	 *
	 * If the container is sharded but a suffix could not be determined,
	 * this means that the path can only refer to a directory and can only
	 * be scanned by looking in all the container shards.
	 *
	 * @param $storagePath string
	 * @return Array (container, path, container suffix) or (null, null, null) if invalid
	 */
	final protected function resolveStoragePath( $storagePath ) {
		list( $backend, $container, $relPath ) = self::splitStoragePath( $storagePath );
		if ( $backend === $this->name ) { // must be for this backend
			$relPath = self::normalizeContainerPath( $relPath );
			if ( $relPath !== null ) {
				// Get shard for the normalized path if this container is sharded
				$cShard = $this->getContainerShard( $container, $relPath );
				// Validate and sanitize the relative path (backend-specific)
				$relPath = $this->resolveContainerPath( $container, $relPath );
				if ( $relPath !== null ) {
					// Prepend any wiki ID prefix to the container name
					$container = $this->fullContainerName( $container );
					if ( self::isValidContainerName( $container ) ) {
						// Validate and sanitize the container name (backend-specific)
						$container = $this->resolveContainerName( "{$container}{$cShard}" );
						if ( $container !== null ) {
							return array( $container, $relPath, $cShard );
						}
					}
				}
			}
		}
		return array( null, null, null );
	}

	/**
	 * Like resolveStoragePath() except null values are returned if
	 * the container is sharded and the shard could not be determined.
	 *
	 * @see FileBackendStore::resolveStoragePath()
	 *
	 * @param $storagePath string
	 * @return Array (container, path) or (null, null) if invalid
	 */
	final protected function resolveStoragePathReal( $storagePath ) {
		list( $container, $relPath, $cShard ) = $this->resolveStoragePath( $storagePath );
		if ( $cShard !== null ) {
			return array( $container, $relPath );
		}
		return array( null, null );
	}

	/**
	 * Get the container name shard suffix for a given path.
	 * Any empty suffix means the container is not sharded.
	 *
	 * @param $container string Container name
	 * @param $relStoragePath string Storage path relative to the container
	 * @return string|null Returns null if shard could not be determined
	 */
	final protected function getContainerShard( $container, $relPath ) {
		list( $levels, $base, $repeat ) = $this->getContainerHashLevels( $container );
		if ( $levels == 1 || $levels == 2 ) {
			// Hash characters are either base 16 or 36
			$char = ( $base == 36 ) ? '[0-9a-z]' : '[0-9a-f]';
			// Get a regex that represents the shard portion of paths.
			// The concatenation of the captures gives us the shard.
			if ( $levels === 1 ) { // 16 or 36 shards per container
				$hashDirRegex = '(' . $char . ')';
			} else { // 256 or 1296 shards per container
				if ( $repeat ) { // verbose hash dir format (e.g. "a/ab/abc")
					$hashDirRegex = $char . '/(' . $char . '{2})';
				} else { // short hash dir format (e.g. "a/b/c")
					$hashDirRegex = '(' . $char . ')/(' . $char . ')';
				}
			}
			// Allow certain directories to be above the hash dirs so as
			// to work with FileRepo (e.g. "archive/a/ab" or "temp/a/ab").
			// They must be 2+ chars to avoid any hash directory ambiguity.
			$m = array();
			if ( preg_match( "!^(?:[^/]{2,}/)*$hashDirRegex(?:/|$)!", $relPath, $m ) ) {
				return '.' . implode( '', array_slice( $m, 1 ) );
			}
			return null; // failed to match
		}
		return ''; // no sharding
	}

	/**
	 * Get the sharding config for a container.
	 * If greater than 0, then all file storage paths within
	 * the container are required to be hashed accordingly.
	 *
	 * @param $container string
	 * @return Array (integer levels, integer base, repeat flag) or (0, 0, false)
	 */
	final protected function getContainerHashLevels( $container ) {
		if ( isset( $this->shardViaHashLevels[$container] ) ) {
			$config = $this->shardViaHashLevels[$container];
			$hashLevels = (int)$config['levels'];
			if ( $hashLevels == 1 || $hashLevels == 2 ) {
				$hashBase = (int)$config['base'];
				if ( $hashBase == 16 || $hashBase == 36 ) {
					return array( $hashLevels, $hashBase, $config['repeat'] );
				}
			}
		}
		return array( 0, 0, false ); // no sharding
	}

	/**
	 * Get a list of full container shard suffixes for a container
	 *
	 * @param $container string
	 * @return Array
	 */
	final protected function getContainerSuffixes( $container ) {
		$shards = array();
		list( $digits, $base ) = $this->getContainerHashLevels( $container );
		if ( $digits > 0 ) {
			$numShards = pow( $base, $digits );
			for ( $index = 0; $index < $numShards; $index++ ) {
				$shards[] = '.' . wfBaseConvert( $index, 10, $base, $digits );
			}
		}
		return $shards;
	}

	/**
	 * Get the full container name, including the wiki ID prefix
	 *
	 * @param $container string
	 * @return string
	 */
	final protected function fullContainerName( $container ) {
		if ( $this->wikiId != '' ) {
			return "{$this->wikiId}-$container";
		} else {
			return $container;
		}
	}

	/**
	 * Resolve a container name, checking if it's allowed by the backend.
	 * This is intended for internal use, such as encoding illegal chars.
	 * Subclasses can override this to be more restrictive.
	 *
	 * @param $container string
	 * @return string|null
	 */
	protected function resolveContainerName( $container ) {
		return $container;
	}

	/**
	 * Resolve a relative storage path, checking if it's allowed by the backend.
	 * This is intended for internal use, such as encoding illegal chars or perhaps
	 * getting absolute paths (e.g. FS based backends). Note that the relative path
	 * may be the empty string (e.g. the path is simply to the container).
	 *
	 * @param $container string Container name
	 * @param $relStoragePath string Storage path relative to the container
	 * @return string|null Path or null if not valid
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		return $relStoragePath;
	}
}

/**
 * FileBackendStore helper function to handle listings that span container shards.
 * Do not use this class from places outside of FileBackendStore.
 *
 * @ingroup FileBackend
 */
abstract class FileBackendStoreShardListIterator implements Iterator {
	/** @var FileBackendStore */
	protected $backend;
	/** @var Array */
	protected $params;
	/** @var Array */
	protected $shardSuffixes;
	protected $container; // string
	protected $directory; // string

	/** @var Traversable */
	protected $iter;
	protected $curShard = 0; // integer
	protected $pos = 0; // integer

	/**
	 * @param $backend FileBackendStore
	 * @param $container string Full storage container name
	 * @param $dir string Storage directory relative to container
	 * @param $suffixes Array List of container shard suffixes
	 * @param $params Array
	 */
	public function __construct(
		FileBackendStore $backend, $container, $dir, array $suffixes, array $params
	) {
		$this->backend = $backend;
		$this->container = $container;
		$this->directory = $dir;
		$this->shardSuffixes = $suffixes;
		$this->params = $params;
	}

	/**
	 * @see Iterator::current()
	 * @return string|bool String or false
	 */
	public function current() {
		if ( is_array( $this->iter ) ) {
			return current( $this->iter );
		} else {
			return $this->iter->current();
		}
	}

	/**
	 * @see Iterator::key()
	 * @return integer
	 */
	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::next()
	 * @return void
	 */
	public function next() {
		++$this->pos;
		if ( is_array( $this->iter ) ) {
			next( $this->iter );
		} else {
			$this->iter->next();
		}
		// Find the next non-empty shard if no elements are left
		$this->nextShardIteratorIfNotValid();
	}

	/**
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->pos = 0;
		$this->curShard = 0;
		$this->setIteratorFromCurrentShard();
		// Find the next non-empty shard if this one has no elements
		$this->nextShardIteratorIfNotValid();
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		if ( $this->iter == null ) {
			return false; // some failure?
		} elseif ( is_array( $this->iter ) ) {
			return ( current( $this->iter ) !== false ); // no paths can have this value
		} else {
			return $this->iter->valid();
		}
	}

	/**
	 * If the list iterator for this container shard is out of items,
	 * then move on to the next container that has items.
	 * If there are none, then it advances to the last container.
	 */
	protected function nextShardIteratorIfNotValid() {
		while ( !$this->valid() ) {
			if ( ++$this->curShard >= count( $this->shardSuffixes ) ) {
				break; // no more container shards
			}
			$this->setIteratorFromCurrentShard();
		}
	}

	/**
	 * Set the list iterator to that of the current container shard
	 */
	protected function setIteratorFromCurrentShard() {
		$suffix = $this->shardSuffixes[$this->curShard];
		$this->iter = $this->listFromShard(
			"{$this->container}{$suffix}", $this->directory, $this->params );
	}

	/**
	 * Get the list for a given container shard
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $params Array
	 * @return Traversable|Array|null
	 */
	abstract protected function listFromShard( $container, $dir, array $params );
}

/**
 * Iterator for listing directories
 */
class FileBackendStoreShardDirIterator extends FileBackendStoreShardListIterator {
	protected function listFromShard( $container, $dir, array $params ) {
		return $this->backend->getSubdirectoryListInternal( $container, $dir, $params );
	}
}

/**
 * Iterator for listing regular files
 */
class FileBackendStoreShardFileIterator extends FileBackendStoreShardListIterator {
	protected function listFromShard( $container, $dir, array $params ) {
		return $this->backend->getFileListInternal( $container, $dir, $params );
	}
}
