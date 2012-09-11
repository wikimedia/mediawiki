<?php
/**
 * Base class for all backends using particular storage medium.
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
	/** @var BagOStuff */
	protected $memCache;
	/** @var ProcessCacheLRU */
	protected $cheapCache; // Map of paths to small (RAM/disk) cache items
	/** @var ProcessCacheLRU */
	protected $expensiveCache; // Map of paths to large (RAM/disk) cache items

	/** @var Array Map of container names to sharding settings */
	protected $shardViaHashLevels = array(); // (container name => config array)

	protected $maxFileSize = 4294967296; // integer bytes (4GiB)

	/**
	 * @see FileBackend::__construct()
	 *
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->memCache       = new EmptyBagOStuff(); // disabled by default
		$this->cheapCache     = new ProcessCacheLRU( 300 );
		$this->expensiveCache = new ProcessCacheLRU( 5 );
	}

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
	 *   - content       : the raw file contents
	 *   - dst           : destination storage path
	 *   - overwrite     : overwrite any file that exists at the destination
	 *   - disposition   : Content-Disposition header value for the destination
	 *   - async         : Status will be returned immediately if supported.
	 *                     If the status is OK, then its value field will be
	 *                     set to a FileBackendStoreOpHandle object.
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
			if ( !empty( $params['overwrite'] ) ) { // file possibly mutated
				$this->deleteFileCache( $params['dst'] ); // persistent cache
			}
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
	 *   - src           : source path on disk
	 *   - dst           : destination storage path
	 *   - overwrite     : overwrite any file that exists at the destination
	 *   - disposition   : Content-Disposition header value for the destination
	 *   - async         : Status will be returned immediately if supported.
	 *                     If the status is OK, then its value field will be
	 *                     set to a FileBackendStoreOpHandle object.
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function storeInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		if ( filesize( $params['src'] ) > $this->maxFileSizeInternal() ) {
			$status = Status::newFatal( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doStoreInternal( $params );
			$this->clearCache( array( $params['dst'] ) );
			if ( !empty( $params['overwrite'] ) ) { // file possibly mutated
				$this->deleteFileCache( $params['dst'] ); // persistent cache
			}
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
	 *   - src           : source storage path
	 *   - dst           : destination storage path
	 *   - overwrite     : overwrite any file that exists at the destination
	 *   - disposition   : Content-Disposition header value for the destination
	 *   - async         : Status will be returned immediately if supported.
	 *                     If the status is OK, then its value field will be
	 *                     set to a FileBackendStoreOpHandle object.
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function copyInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doCopyInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		if ( !empty( $params['overwrite'] ) ) { // file possibly mutated
			$this->deleteFileCache( $params['dst'] ); // persistent cache
		}
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
	 *   - src                 : source storage path
	 *   - ignoreMissingSource : do nothing if the source file does not exist
	 *   - async               : Status will be returned immediately if supported.
	 *                           If the status is OK, then its value field will be
	 *                           set to a FileBackendStoreOpHandle object.
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function deleteInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doDeleteInternal( $params );
		$this->clearCache( array( $params['src'] ) );
		$this->deleteFileCache( $params['src'] ); // persistent cache
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
	 *   - src           : source storage path
	 *   - dst           : destination storage path
	 *   - overwrite     : overwrite any file that exists at the destination
	 *   - disposition   : Content-Disposition header value for the destination
	 *   - async         : Status will be returned immediately if supported.
	 *                     If the status is OK, then its value field will be
	 *                     set to a FileBackendStoreOpHandle object.
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function moveInternal( array $params ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = $this->doMoveInternal( $params );
		$this->clearCache( array( $params['src'], $params['dst'] ) );
		$this->deleteFileCache( $params['src'] ); // persistent cache
		if ( !empty( $params['overwrite'] ) ) { // file possibly mutated
			$this->deleteFileCache( $params['dst'] ); // persistent cache
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::moveInternal()
	 * @return Status
	 */
	protected function doMoveInternal( array $params ) {
		unset( $params['async'] ); // two steps, won't work here :)
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
	 * No-op file operation that does nothing.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * @param $params Array
	 * @return Status
	 */
	final public function nullInternal( array $params ) {
		return Status::newGood();
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
			// Actually do the file concatenation...
			$start_time = microtime( true );
			$status->merge( $this->doConcatenate( $params ) );
			$sec = microtime( true ) - $start_time;
			if ( !$status->isOK() ) {
				wfDebugLog( 'FileOperation', get_class( $this ) . " failed to concatenate " .
					count( $params['srcs'] ) . " file(s) [$sec sec]" );
			}
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
	 * @see FileBackend::doPublish()
	 * @return Status
	 */
	final protected function doPublish( array $params ) {
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
			$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPublishInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doPublish()
	 * @return Status
	 */
	protected function doPublishInternal( $container, $dir, array $params ) {
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

		// Recursive: first delete all empty subdirs recursively
		if ( !empty( $params['recursive'] ) && !$this->directoriesAreVirtual() ) {
			$subDirsRel = $this->getTopDirectoryList( array( 'dir' => $params['dir'] ) );
			if ( $subDirsRel !== null ) { // no errors
				foreach ( $subDirsRel as $subDirRel ) {
					$subDir = $params['dir'] . "/{$subDirRel}"; // full path
					$status->merge( $this->doClean( array( 'dir' => $subDir ) + $params ) );
				}
			}
		}

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
			$this->deleteContainerCache( $fullCont ); // purge cache
		} else { // directory is on several shards
			wfDebug( __METHOD__ . ": iterating over all container shards.\n" );
			list( $b, $shortCont, $r ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doCleanInternal( "{$fullCont}{$suffix}", $dir, $params ) );
				$this->deleteContainerCache( "{$fullCont}{$suffix}" ); // purge cache
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
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( !$this->cheapCache->has( $path, 'stat' ) ) {
			$this->primeFileCache( array( $path ) ); // check persistent cache
		}
		if ( $this->cheapCache->has( $path, 'stat' ) ) {
			$stat = $this->cheapCache->get( $path, 'stat' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $stat['latest'] ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $stat;
			}
		}
		wfProfileIn( __METHOD__ . '-miss' );
		wfProfileIn( __METHOD__ . '-miss-' . $this->name );
		$stat = $this->doGetFileStat( $params );
		wfProfileOut( __METHOD__ . '-miss-' . $this->name );
		wfProfileOut( __METHOD__ . '-miss' );
		if ( is_array( $stat ) ) { // don't cache negatives
			$stat['latest'] = $latest;
			$this->cheapCache->set( $path, 'stat', $stat );
			$this->setFileCache( $path, $stat ); // update persistent cache
			if ( isset( $stat['sha1'] ) ) { // some backends store SHA-1 as metadata
				$this->cheapCache->set( $path, 'sha1',
					array( 'hash' => $stat['sha1'], 'latest' => $latest ) );
			}
		} else {
			wfDebug( __METHOD__ . ": File $path does not exist.\n" );
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
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( $this->cheapCache->has( $path, 'sha1' ) ) {
			$stat = $this->cheapCache->get( $path, 'sha1' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $stat['latest'] ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $stat['hash'];
			}
		}
		wfProfileIn( __METHOD__ . '-miss' );
		wfProfileIn( __METHOD__ . '-miss-' . $this->name );
		$hash = $this->doGetFileSha1Base36( $params );
		wfProfileOut( __METHOD__ . '-miss-' . $this->name );
		wfProfileOut( __METHOD__ . '-miss' );
		if ( $hash ) { // don't cache negatives
			$this->cheapCache->set( $path, 'sha1',
				array( 'hash' => $hash, 'latest' => $latest ) );
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $hash;
	}

	/**
	 * @see FileBackendStore::getFileSha1Base36()
	 * @return bool|string
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
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return null; // invalid storage path
		}
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( $this->expensiveCache->has( $path, 'localRef' ) ) {
			$val = $this->expensiveCache->get( $path, 'localRef' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $val['latest'] ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $val['object'];
			}
		}
		$tmpFile = $this->getLocalCopy( $params );
		if ( $tmpFile ) { // don't cache negatives
			$this->expensiveCache->set( $path, 'localRef',
				array( 'object' => $tmpFile, 'latest' => $latest ) );
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
	 * @see FileBackend::getDirectoryList()
	 * @return Traversable|Array|null Returns null on failure
	 */
	final public function getDirectoryList( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getDirectoryListInternal( $fullCont, $dir, $params );
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
	 * @see FileBackendStore::getDirectoryList()
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $params Array
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract public function getDirectoryListInternal( $container, $dir, array $params );

	/**
	 * @see FileBackend::getFileList()
	 * @return Traversable|Array|null Returns null on failure
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
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract public function getFileListInternal( $container, $dir, array $params );

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
	final public function getOperationsInternal( array $ops ) {
		$supportedOps = array(
			'store'       => 'StoreFileOp',
			'copy'        => 'CopyFileOp',
			'move'        => 'MoveFileOp',
			'delete'      => 'DeleteFileOp',
			'create'      => 'CreateFileOp',
			'null'        => 'NullFileOp'
		);

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
				throw new MWException( "Operation '$opName' is not supported." );
			}
		}

		return $performOps;
	}

	/**
	 * Get a list of storage paths to lock for a list of operations
	 * Returns an array with 'sh' (shared) and 'ex' (exclusive) keys,
	 * each corresponding to a list of storage paths to be locked.
	 *
	 * @param $performOps Array List of FileOp objects
	 * @return Array ('sh' => list of paths, 'ex' => list of paths)
	 */
	final public function getPathsToLockForOpsInternal( array $performOps ) {
		// Build up a list of files to lock...
		$paths = array( 'sh' => array(), 'ex' => array() );
		foreach ( $performOps as $fileOp ) {
			$paths['sh'] = array_merge( $paths['sh'], $fileOp->storagePathsRead() );
			$paths['ex'] = array_merge( $paths['ex'], $fileOp->storagePathsChanged() );
		}
		// Optimization: if doing an EX lock anyway, don't also set an SH one
		$paths['sh'] = array_diff( $paths['sh'], $paths['ex'] );
		// Get a shared lock on the parent directory of each path changed
		$paths['sh'] = array_merge( $paths['sh'], array_map( 'dirname', $paths['ex'] ) );

		return $paths;
	}

	/**
	 * @see FileBackend::getScopedLocksForOps()
	 * @return Array
	 */
	public function getScopedLocksForOps( array $ops, Status $status ) {
		$paths = $this->getPathsToLockForOpsInternal( $this->getOperationsInternal( $ops ) );
		return array(
			$this->getScopedFileLocks( $paths['sh'], LockManager::LOCK_UW, $status ),
			$this->getScopedFileLocks( $paths['ex'], LockManager::LOCK_EX, $status )
		);
	}

	/**
	 * @see FileBackend::doOperationsInternal()
	 * @return Status
	 */
	final protected function doOperationsInternal( array $ops, array $opts ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		// Build up a list of FileOps...
		$performOps = $this->getOperationsInternal( $ops );

		// Acquire any locks as needed...
		if ( empty( $opts['nonLocking'] ) ) {
			// Build up a list of files to lock...
			$paths = $this->getPathsToLockForOpsInternal( $performOps );
			// Try to lock those files for the scope of this function...
			$scopeLockS = $this->getScopedFileLocks( $paths['sh'], LockManager::LOCK_UW, $status );
			$scopeLockE = $this->getScopedFileLocks( $paths['ex'], LockManager::LOCK_EX, $status );
			if ( !$status->isOK() ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				return $status; // abort
			}
		}

		// Clear any file cache entries (after locks acquired)
		if ( empty( $opts['preserveCache'] ) ) {
			$this->clearCache();
		}

		// Load from the persistent file and container caches
		$this->primeFileCache( $performOps );
		$this->primeContainerCache( $performOps );

		// Actually attempt the operation batch...
		$subStatus = FileOpBatch::attempt( $performOps, $opts, $this->fileJournal );

		// Merge errors into status fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackend::doQuickOperationsInternal()
	 * @return Status
	 * @throws MWException
	 */
	final protected function doQuickOperationsInternal( array $ops ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = Status::newGood();

		$supportedOps = array( 'create', 'store', 'copy', 'move', 'delete', 'null' );
		$async = ( $this->parallelize === 'implicit' );
		$maxConcurrency = $this->concurrency; // throttle

		$statuses = array(); // array of (index => Status)
		$fileOpHandles = array(); // list of (index => handle) arrays
		$curFileOpHandles = array(); // current handle batch
		// Perform the sync-only ops and build up op handles for the async ops...
		foreach ( $ops as $index => $params ) {
			if ( !in_array( $params['op'], $supportedOps ) ) {
				wfProfileOut( __METHOD__ . '-' . $this->name );
				wfProfileOut( __METHOD__ );
				throw new MWException( "Operation '{$params['op']}' is not supported." );
			}
			$method = $params['op'] . 'Internal'; // e.g. "storeInternal"
			$subStatus = $this->$method( array( 'async' => $async ) + $params );
			if ( $subStatus->value instanceof FileBackendStoreOpHandle ) { // async
				if ( count( $curFileOpHandles ) >= $maxConcurrency ) {
					$fileOpHandles[] = $curFileOpHandles; // push this batch
					$curFileOpHandles = array();
				}
				$curFileOpHandles[$index] = $subStatus->value; // keep index
			} else { // error or completed
				$statuses[$index] = $subStatus; // keep index
			}
		}
		if ( count( $curFileOpHandles ) ) {
			$fileOpHandles[] = $curFileOpHandles; // last batch
		}
		// Do all the async ops that can be done concurrently...
		foreach ( $fileOpHandles as $fileHandleBatch ) {
			$statuses = $statuses + $this->executeOpHandlesInternal( $fileHandleBatch );
		}
		// Marshall and merge all the responses...
		foreach ( $statuses as $index => $subStatus ) {
			$status->merge( $subStatus );
			if ( $subStatus->isOK() ) {
				$status->success[$index] = true;
				++$status->successCount;
			} else {
				$status->success[$index] = false;
				++$status->failCount;
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Execute a list of FileBackendStoreOpHandle handles in parallel.
	 * The resulting Status object fields will correspond
	 * to the order in which the handles where given.
	 *
	 * @param $handles Array List of FileBackendStoreOpHandle objects
	 * @return Array Map of Status objects
	 * @throws MWException
	 */
	final public function executeOpHandlesInternal( array $fileOpHandles ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );
		foreach ( $fileOpHandles as $fileOpHandle ) {
			if ( !( $fileOpHandle instanceof FileBackendStoreOpHandle ) ) {
				throw new MWException( "Given a non-FileBackendStoreOpHandle object." );
			} elseif ( $fileOpHandle->backend->getName() !== $this->getName() ) {
				throw new MWException( "Given a FileBackendStoreOpHandle for the wrong backend." );
			}
		}
		$res = $this->doExecuteOpHandlesInternal( $fileOpHandles );
		foreach ( $fileOpHandles as $fileOpHandle ) {
			$fileOpHandle->closeResources();
		}
		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
		return $res;
	}

	/**
	 * @see FileBackendStore::executeOpHandlesInternal()
	 * @return Array List of corresponding Status objects
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		foreach ( $fileOpHandles as $fileOpHandle ) { // OK if empty
			throw new MWException( "This backend supports no asynchronous operations." );
		}
		return array();
	}

	/**
	 * @see FileBackend::preloadCache()
	 */
	final public function preloadCache( array $paths ) {
		$fullConts = array(); // full container names
		foreach ( $paths as $path ) {
			list( $fullCont, $r, $s ) = $this->resolveStoragePath( $path );
			$fullConts[] = $fullCont;
		}
		// Load from the persistent file and container caches
		$this->primeContainerCache( $fullConts );
		$this->primeFileCache( $paths );
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
			$this->cheapCache->clear();
			$this->expensiveCache->clear();
		} else {
			foreach ( $paths as $path ) {
				$this->cheapCache->clear( $path );
				$this->expensiveCache->clear( $path );
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
	 * Is this a key/value store where directories are just virtual?
	 * Virtual directories exists in so much as files exists that are
	 * prefixed with the directory path followed by a forward slash.
	 *
	 * @return bool
	 */
	abstract protected function directoriesAreVirtual();

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
	 * @param $relPath string Storage path relative to the container
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
	 * Check if a storage path maps to a single shard.
	 * Container dirs like "a", where the container shards on "x/xy",
	 * can reside on several shards. Such paths are tricky to handle.
	 *
	 * @param $storagePath string Storage path
	 * @return bool
	 */
	final public function isSingleShardPathInternal( $storagePath ) {
		list( $c, $r, $shard ) = $this->resolveStoragePath( $storagePath );
		return ( $shard !== null );
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

	/**
	 * Get the cache key for a container
	 *
	 * @param $container string Resolved container name
	 * @return string
	 */
	private function containerCacheKey( $container ) {
		return wfMemcKey( 'backend', $this->getName(), 'container', $container );
	}

	/**
	 * Set the cached info for a container
	 *
	 * @param $container string Resolved container name
	 * @param $val mixed Information to cache
	 */
	final protected function setContainerCache( $container, $val ) {
		$this->memCache->add( $this->containerCacheKey( $container ), $val, 14*86400 );
	}

	/**
	 * Delete the cached info for a container.
	 * The cache key is salted for a while to prevent race conditions.
	 *
	 * @param $container string Resolved container name
	 */
	final protected function deleteContainerCache( $container ) {
		if ( !$this->memCache->set( $this->containerCacheKey( $container ), 'PURGED', 300 ) ) {
			trigger_error( "Unable to delete stat cache for container $container." );
		}
	}

	/**
	 * Do a batch lookup from cache for container stats for all containers
	 * used in a list of container names, storage paths, or FileOp objects.
	 *
	 * @param $items Array
	 * @return void
	 */
	final protected function primeContainerCache( array $items ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );

		$paths = array(); // list of storage paths
		$contNames = array(); // (cache key => resolved container name)
		// Get all the paths/containers from the items...
		foreach ( $items as $item ) {
			if ( $item instanceof FileOp ) {
				$paths = array_merge( $paths, $item->storagePathsRead() );
				$paths = array_merge( $paths, $item->storagePathsChanged() );
			} elseif ( self::isStoragePath( $item ) ) {
				$paths[] = $item;
			} elseif ( is_string( $item ) ) { // full container name
				$contNames[$this->containerCacheKey( $item )] = $item;
			}
		}
		// Get all the corresponding cache keys for paths...
		foreach ( $paths as $path ) {
			list( $fullCont, $r, $s ) = $this->resolveStoragePath( $path );
			if ( $fullCont !== null ) { // valid path for this backend
				$contNames[$this->containerCacheKey( $fullCont )] = $fullCont;
			}
		}

		$contInfo = array(); // (resolved container name => cache value)
		// Get all cache entries for these container cache keys...
		$values = $this->memCache->getMulti( array_keys( $contNames ) );
		foreach ( $values as $cacheKey => $val ) {
			$contInfo[$contNames[$cacheKey]] = $val;
		}

		// Populate the container process cache for the backend...
		$this->doPrimeContainerCache( array_filter( $contInfo, 'is_array' ) );

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Fill the backend-specific process cache given an array of
	 * resolved container names and their corresponding cached info.
	 * Only containers that actually exist should appear in the map.
	 *
	 * @param $containerInfo Array Map of resolved container names to cached info
	 * @return void
	 */
	protected function doPrimeContainerCache( array $containerInfo ) {}

	/**
	 * Get the cache key for a file path
	 *
	 * @param $path string Storage path
	 * @return string
	 */
	private function fileCacheKey( $path ) {
		return wfMemcKey( 'backend', $this->getName(), 'file', sha1( $path ) );
	}

	/**
	 * Set the cached stat info for a file path.
	 * Negatives (404s) are not cached. By not caching negatives, we can skip cache
	 * salting for the case when a file is created at a path were there was none before.
	 *
	 * @param $path string Storage path
	 * @param $val mixed Information to cache
	 */
	final protected function setFileCache( $path, $val ) {
		$this->memCache->add( $this->fileCacheKey( $path ), $val, 7*86400 );
	}

	/**
	 * Delete the cached stat info for a file path.
	 * The cache key is salted for a while to prevent race conditions.
	 *
	 * @param $path string Storage path
	 */
	final protected function deleteFileCache( $path ) {
		if ( !$this->memCache->set( $this->fileCacheKey( $path ), 'PURGED', 300 ) ) {
			trigger_error( "Unable to delete stat cache for file $path." );
		}
	}

	/**
	 * Do a batch lookup from cache for file stats for all paths
	 * used in a list of storage paths or FileOp objects.
	 *
	 * @param $items Array List of storage paths or FileOps
	 * @return void
	 */
	final protected function primeFileCache( array $items ) {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-' . $this->name );

		$paths = array(); // list of storage paths
		$pathNames = array(); // (cache key => storage path)
		// Get all the paths/containers from the items...
		foreach ( $items as $item ) {
			if ( $item instanceof FileOp ) {
				$paths = array_merge( $paths, $item->storagePathsRead() );
				$paths = array_merge( $paths, $item->storagePathsChanged() );
			} elseif ( self::isStoragePath( $item ) ) {
				$paths[] = $item;
			}
		}
		// Get all the corresponding cache keys for paths...
		foreach ( $paths as $path ) {
			list( $cont, $rel, $s ) = $this->resolveStoragePath( $path );
			if ( $rel !== null ) { // valid path for this backend
				$pathNames[$this->fileCacheKey( $path )] = $path;
			}
		}
		// Get all cache entries for these container cache keys...
		$values = $this->memCache->getMulti( array_keys( $pathNames ) );
		foreach ( $values as $cacheKey => $val ) {
			if ( is_array( $val ) ) {
				$path = $pathNames[$cacheKey];
				$this->cheapCache->set( $path, 'stat', $val );
				if ( isset( $val['sha1'] ) ) { // some backends store SHA-1 as metadata
					$this->cheapCache->set( $path, 'sha1',
						array( 'hash' => $val['sha1'], 'latest' => $val['latest'] ) );
				}
			}
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		wfProfileOut( __METHOD__ );
	}
}

/**
 * FileBackendStore helper class for performing asynchronous file operations.
 *
 * For example, calling FileBackendStore::createInternal() with the "async"
 * param flag may result in a Status that contains this object as a value.
 * This class is largely backend-specific and is mostly just "magic" to be
 * passed to FileBackendStore::executeOpHandlesInternal().
 */
abstract class FileBackendStoreOpHandle {
	/** @var Array */
	public $params = array(); // params to caller functions
	/** @var FileBackendStore */
	public $backend;
	/** @var Array */
	public $resourcesToClose = array();

	public $call; // string; name that identifies the function called

	/**
	 * Close all open file handles
	 *
	 * @return void
	 */
	public function closeResources() {
		array_map( 'fclose', $this->resourcesToClose );
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
	protected $container; // string; full container name
	protected $directory; // string; resolved relative path

	/** @var Traversable */
	protected $iter;
	protected $curShard = 0; // integer
	protected $pos = 0; // integer

	/** @var Array */
	protected $multiShardPaths = array(); // (rel path => 1)

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
	 * @see Iterator::key()
	 * @return integer
	 */
	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		if ( $this->iter instanceof Iterator ) {
			return $this->iter->valid();
		} elseif ( is_array( $this->iter ) ) {
			return ( current( $this->iter ) !== false ); // no paths can have this value
		}
		return false; // some failure?
	}

	/**
	 * @see Iterator::current()
	 * @return string|bool String or false
	 */
	public function current() {
		return ( $this->iter instanceof Iterator )
			? $this->iter->current()
			: current( $this->iter );
	}

	/**
	 * @see Iterator::next()
	 * @return void
	 */
	public function next() {
		++$this->pos;
		( $this->iter instanceof Iterator ) ? $this->iter->next() : next( $this->iter );
		do {
			$continue = false; // keep scanning shards?
			$this->filterViaNext(); // filter out duplicates
			// Find the next non-empty shard if no elements are left
			if ( !$this->valid() ) {
				$this->nextShardIteratorIfNotValid();
				$continue = $this->valid(); // re-filter unless we ran out of shards
			}
		} while ( $continue );
	}

	/**
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->pos = 0;
		$this->curShard = 0;
		$this->setIteratorFromCurrentShard();
		do {
			$continue = false; // keep scanning shards?
			$this->filterViaNext(); // filter out duplicates
			// Find the next non-empty shard if no elements are left
			if ( !$this->valid() ) {
				$this->nextShardIteratorIfNotValid();
				$continue = $this->valid(); // re-filter unless we ran out of shards
			}
		} while ( $continue );
	}

	/**
	 * Filter out duplicate items by advancing to the next ones
	 */
	protected function filterViaNext() {
		while ( $this->valid() ) {
			$rel = $this->iter->current(); // path relative to given directory
			$path = $this->params['dir'] . "/{$rel}"; // full storage path
			if ( $this->backend->isSingleShardPathInternal( $path ) ) {
				break; // path is only on one shard; no issue with duplicates
			} elseif ( isset( $this->multiShardPaths[$rel] ) ) {
				// Don't keep listing paths that are on multiple shards
				( $this->iter instanceof Iterator ) ? $this->iter->next() : next( $this->iter );
			} else {
				$this->multiShardPaths[$rel] = 1;
				break;
			}
		}
	}

	/**
	 * If the list iterator for this container shard is out of items,
	 * then move on to the next container that has items.
	 * If there are none, then it advances to the last container.
	 */
	protected function nextShardIteratorIfNotValid() {
		while ( !$this->valid() && ++$this->curShard < count( $this->shardSuffixes ) ) {
			$this->setIteratorFromCurrentShard();
		}
	}

	/**
	 * Set the list iterator to that of the current container shard
	 */
	protected function setIteratorFromCurrentShard() {
		$this->iter = $this->listFromShard(
			$this->container . $this->shardSuffixes[$this->curShard],
			$this->directory, $this->params );
		// Start loading results so that current() works
		if ( $this->iter ) {
			( $this->iter instanceof Iterator ) ? $this->iter->rewind() : reset( $this->iter );
		}
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
	/**
	 * @see FileBackendStoreShardListIterator::listFromShard()
	 * @return Array|null|Traversable
	 */
	protected function listFromShard( $container, $dir, array $params ) {
		return $this->backend->getDirectoryListInternal( $container, $dir, $params );
	}
}

/**
 * Iterator for listing regular files
 */
class FileBackendStoreShardFileIterator extends FileBackendStoreShardListIterator {
	/**
	 * @see FileBackendStoreShardListIterator::listFromShard()
	 * @return Array|null|Traversable
	 */
	protected function listFromShard( $container, $dir, array $params ) {
		return $this->backend->getFileListInternal( $container, $dir, $params );
	}
}
