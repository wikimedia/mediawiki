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
 */
use Wikimedia\Timestamp\ConvertibleTimestamp;

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
	/** @var WANObjectCache */
	protected $memCache;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var MapCacheLRU Map of paths to small (RAM/disk) cache items */
	protected $cheapCache;
	/** @var MapCacheLRU Map of paths to large (RAM/disk) cache items */
	protected $expensiveCache;

	/** @var array Map of container names to sharding config */
	protected $shardViaHashLevels = [];

	/** @var callable Method to get the MIME type of files */
	protected $mimeCallback;

	protected $maxFileSize = 4294967296; // integer bytes (4GiB)

	const CACHE_TTL = 10; // integer; TTL in seconds for process cache entries
	const CACHE_CHEAP_SIZE = 500; // integer; max entries in "cheap cache"
	const CACHE_EXPENSIVE_SIZE = 5; // integer; max entries in "expensive cache"

	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *   - srvCache     : BagOStuff cache to APC or the like.
	 *   - wanCache     : WANObjectCache object to use for persistent caching.
	 *   - mimeCallback : Callback that takes (storage path, content, file system path) and
	 *                    returns the MIME type of the file or 'unknown/unknown'. The file
	 *                    system path parameter should be used if the content one is null.
	 *
	 * @param array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->mimeCallback = $config['mimeCallback'] ?? null;
		$this->srvCache = new EmptyBagOStuff(); // disabled by default
		$this->memCache = WANObjectCache::newEmpty(); // disabled by default
		$this->cheapCache = new MapCacheLRU( self::CACHE_CHEAP_SIZE );
		$this->expensiveCache = new MapCacheLRU( self::CACHE_EXPENSIVE_SIZE );
	}

	/**
	 * Get the maximum allowable file size given backend
	 * medium restrictions and basic performance constraints.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * @return int Bytes
	 */
	final public function maxFileSizeInternal() {
		return $this->maxFileSize;
	}

	/**
	 * Check if a file can be created or changed at a given storage path.
	 * FS backends should check if the parent directory exists, files can be
	 * written under it, and that any file already there is writable.
	 * Backends using key/value stores should check if the container exists.
	 *
	 * @param string $storagePath
	 * @return bool
	 */
	abstract public function isPathUsableInternal( $storagePath );

	/**
	 * Create a file in the backend with the given contents.
	 * This will overwrite any file that exists at the destination.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - content     : the raw file contents
	 *   - dst         : destination storage path
	 *   - headers     : HTTP header name/value map
	 *   - async       : StatusValue will be returned immediately if supported.
	 *                   If the StatusValue is OK, then its value field will be
	 *                   set to a FileBackendStoreOpHandle object.
	 *   - dstExists   : Whether a file exists at the destination (optimization).
	 *                   Callers can use "false" if no existing file is being changed.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function createInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		if ( strlen( $params['content'] ) > $this->maxFileSizeInternal() ) {
			$status = $this->newStatus( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doCreateInternal( $params );
			$this->clearCache( [ $params['dst'] ] );
			if ( !isset( $params['dstExists'] ) || $params['dstExists'] ) {
				$this->deleteFileCache( $params['dst'] ); // persistent cache
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::createInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doCreateInternal( array $params );

	/**
	 * Store a file into the backend from a file on disk.
	 * This will overwrite any file that exists at the destination.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - src         : source path on disk
	 *   - dst         : destination storage path
	 *   - headers     : HTTP header name/value map
	 *   - async       : StatusValue will be returned immediately if supported.
	 *                   If the StatusValue is OK, then its value field will be
	 *                   set to a FileBackendStoreOpHandle object.
	 *   - dstExists   : Whether a file exists at the destination (optimization).
	 *                   Callers can use "false" if no existing file is being changed.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function storeInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		if ( filesize( $params['src'] ) > $this->maxFileSizeInternal() ) {
			$status = $this->newStatus( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doStoreInternal( $params );
			$this->clearCache( [ $params['dst'] ] );
			if ( !isset( $params['dstExists'] ) || $params['dstExists'] ) {
				$this->deleteFileCache( $params['dst'] ); // persistent cache
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::storeInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doStoreInternal( array $params );

	/**
	 * Copy a file from one storage path to another in the backend.
	 * This will overwrite any file that exists at the destination.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - src                 : source storage path
	 *   - dst                 : destination storage path
	 *   - ignoreMissingSource : do nothing if the source file does not exist
	 *   - headers             : HTTP header name/value map
	 *   - async               : StatusValue will be returned immediately if supported.
	 *                           If the StatusValue is OK, then its value field will be
	 *                           set to a FileBackendStoreOpHandle object.
	 *   - dstExists           : Whether a file exists at the destination (optimization).
	 *                           Callers can use "false" if no existing file is being changed.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function copyInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->doCopyInternal( $params );
		$this->clearCache( [ $params['dst'] ] );
		if ( !isset( $params['dstExists'] ) || $params['dstExists'] ) {
			$this->deleteFileCache( $params['dst'] ); // persistent cache
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::copyInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doCopyInternal( array $params );

	/**
	 * Delete a file at the storage path.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - src                 : source storage path
	 *   - ignoreMissingSource : do nothing if the source file does not exist
	 *   - async               : StatusValue will be returned immediately if supported.
	 *                           If the StatusValue is OK, then its value field will be
	 *                           set to a FileBackendStoreOpHandle object.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function deleteInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->doDeleteInternal( $params );
		$this->clearCache( [ $params['src'] ] );
		$this->deleteFileCache( $params['src'] ); // persistent cache
		return $status;
	}

	/**
	 * @see FileBackendStore::deleteInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doDeleteInternal( array $params );

	/**
	 * Move a file from one storage path to another in the backend.
	 * This will overwrite any file that exists at the destination.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - src                 : source storage path
	 *   - dst                 : destination storage path
	 *   - ignoreMissingSource : do nothing if the source file does not exist
	 *   - headers             : HTTP header name/value map
	 *   - async               : StatusValue will be returned immediately if supported.
	 *                           If the StatusValue is OK, then its value field will be
	 *                           set to a FileBackendStoreOpHandle object.
	 *   - dstExists           : Whether a file exists at the destination (optimization).
	 *                           Callers can use "false" if no existing file is being changed.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function moveInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->doMoveInternal( $params );
		$this->clearCache( [ $params['src'], $params['dst'] ] );
		$this->deleteFileCache( $params['src'] ); // persistent cache
		if ( !isset( $params['dstExists'] ) || $params['dstExists'] ) {
			$this->deleteFileCache( $params['dst'] ); // persistent cache
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::moveInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doMoveInternal( array $params ) {
		unset( $params['async'] ); // two steps, won't work here :)
		$nsrc = FileBackend::normalizeStoragePath( $params['src'] );
		$ndst = FileBackend::normalizeStoragePath( $params['dst'] );
		// Copy source to dest
		$status = $this->copyInternal( $params );
		if ( $nsrc !== $ndst && $status->isOK() ) {
			// Delete source (only fails due to races or network problems)
			$status->merge( $this->deleteInternal( [ 'src' => $params['src'] ] ) );
			$status->setResult( true, $status->value ); // ignore delete() errors
		}

		return $status;
	}

	/**
	 * Alter metadata for a file at the storage path.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * $params include:
	 *   - src           : source storage path
	 *   - headers       : HTTP header name/value map
	 *   - async         : StatusValue will be returned immediately if supported.
	 *                     If the StatusValue is OK, then its value field will be
	 *                     set to a FileBackendStoreOpHandle object.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function describeInternal( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		if ( count( $params['headers'] ) ) {
			$status = $this->doDescribeInternal( $params );
			$this->clearCache( [ $params['src'] ] );
			$this->deleteFileCache( $params['src'] ); // persistent cache
		} else {
			$status = $this->newStatus(); // nothing to do
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::describeInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doDescribeInternal( array $params ) {
		return $this->newStatus();
	}

	/**
	 * No-op file operation that does nothing.
	 * Do not call this function from places outside FileBackend and FileOp.
	 *
	 * @param array $params
	 * @return StatusValue
	 */
	final public function nullInternal( array $params ) {
		return $this->newStatus();
	}

	final public function concatenate( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Try to lock the source files for the scope of this function
		$scopeLockS = $this->getScopedFileLocks( $params['srcs'], LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			// Actually do the file concatenation...
			$start_time = microtime( true );
			$status->merge( $this->doConcatenate( $params ) );
			$sec = microtime( true ) - $start_time;
			if ( !$status->isOK() ) {
				$this->logger->error( static::class . "-{$this->name}" .
					" failed to concatenate " . count( $params['srcs'] ) . " file(s) [$sec sec]" );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::concatenate()
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doConcatenate( array $params ) {
		$status = $this->newStatus();
		$tmpPath = $params['dst']; // convenience
		unset( $params['latest'] ); // sanity

		// Check that the specified temp file is valid...
		Wikimedia\suppressWarnings();
		$ok = ( is_file( $tmpPath ) && filesize( $tmpPath ) == 0 );
		Wikimedia\restoreWarnings();
		if ( !$ok ) { // not present or not empty
			$status->fatal( 'backend-fail-opentemp', $tmpPath );

			return $status;
		}

		// Get local FS versions of the chunks needed for the concatenation...
		$fsFiles = $this->getLocalReferenceMulti( $params );
		foreach ( $fsFiles as $path => &$fsFile ) {
			if ( !$fsFile ) { // chunk failed to download?
				$fsFile = $this->getLocalReference( [ 'src' => $path ] );
				if ( !$fsFile ) { // retry failed?
					$status->fatal( 'backend-fail-read', $path );

					return $status;
				}
			}
		}
		unset( $fsFile ); // unset reference so we can reuse $fsFile

		// Get a handle for the destination temp file
		$tmpHandle = fopen( $tmpPath, 'ab' );
		if ( $tmpHandle === false ) {
			$status->fatal( 'backend-fail-opentemp', $tmpPath );

			return $status;
		}

		// Build up the temp file using the source chunks (in order)...
		foreach ( $fsFiles as $virtualSource => $fsFile ) {
			// Get a handle to the local FS version
			$sourceHandle = fopen( $fsFile->getPath(), 'rb' );
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

	final protected function doPrepare( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doPrepareInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPrepareInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepare()
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doPrepareInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	final protected function doSecure( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doSecureInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doSecure()
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doSecureInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	final protected function doPublish( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPublishInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPublish()
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doPublishInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	final protected function doClean( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Recursive: first delete all empty subdirs recursively
		if ( !empty( $params['recursive'] ) && !$this->directoriesAreVirtual() ) {
			$subDirsRel = $this->getTopDirectoryList( [ 'dir' => $params['dir'] ] );
			if ( $subDirsRel !== null ) { // no errors
				foreach ( $subDirsRel as $subDirRel ) {
					$subDir = $params['dir'] . "/{$subDirRel}"; // full path
					$status->merge( $this->doClean( [ 'dir' => $subDir ] + $params ) );
				}
				unset( $subDirsRel ); // free directory for rmdir() on Windows (for FS backends)
			}
		}

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		// Attempt to lock this directory...
		$filesLockEx = [ $params['dir'] ];
		$scopedLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
		if ( !$status->isOK() ) {
			return $status; // abort
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doCleanInternal( $fullCont, $dir, $params ) );
			$this->deleteContainerCache( $fullCont ); // purge cache
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doCleanInternal( "{$fullCont}{$suffix}", $dir, $params ) );
				$this->deleteContainerCache( "{$fullCont}{$suffix}" ); // purge cache
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doClean()
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doCleanInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	final public function fileExists( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$stat = $this->getFileStat( $params );

		return ( $stat === null ) ? null : (bool)$stat; // null => failure
	}

	final public function getFileTimestamp( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$stat = $this->getFileStat( $params );

		return $stat ? $stat['mtime'] : false;
	}

	final public function getFileSize( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$stat = $this->getFileStat( $params );

		return $stat ? $stat['size'] : false;
	}

	final public function getFileStat( array $params ) {
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$latest = !empty( $params['latest'] ); // use latest data?
		$requireSHA1 = !empty( $params['requireSHA1'] ); // require SHA-1 if file exists?

		if ( !$latest ) {
			$stat = $this->cheapCache->getField( $path, 'stat', self::CACHE_TTL );
			// Note that some backends, like SwiftFileBackend, sometimes set file stat process
			// cache entries from mass object listings that do not include the SHA-1. In that
			// case, loading the persistent stat cache will likely yield the SHA-1.
			if (
				$stat === null ||
				( $requireSHA1 && is_array( $stat ) && !isset( $stat['sha1'] ) )
			) {
				$this->primeFileCache( [ $path ] ); // check persistent cache
			}
		}

		$stat = $this->cheapCache->getField( $path, 'stat', self::CACHE_TTL );
		// If we want the latest data, check that this cached
		// value was in fact fetched with the latest available data.
		if ( is_array( $stat ) ) {
			if (
				( !$latest || $stat['latest'] ) &&
				( !$requireSHA1 || isset( $stat['sha1'] ) )
			) {
				return $stat;
			}
		} elseif ( in_array( $stat, [ 'NOT_EXIST', 'NOT_EXIST_LATEST' ], true ) ) {
			if ( !$latest || $stat === 'NOT_EXIST_LATEST' ) {
				return false;
			}
		}

		$stat = $this->doGetFileStat( $params );

		if ( is_array( $stat ) ) { // file exists
			// Strongly consistent backends can automatically set "latest"
			$stat['latest'] = $stat['latest'] ?? $latest;
			$this->cheapCache->setField( $path, 'stat', $stat );
			$this->setFileCache( $path, $stat ); // update persistent cache
			if ( isset( $stat['sha1'] ) ) { // some backends store SHA-1 as metadata
				$this->cheapCache->setField( $path, 'sha1',
					[ 'hash' => $stat['sha1'], 'latest' => $latest ] );
			}
			if ( isset( $stat['xattr'] ) ) { // some backends store headers/metadata
				$stat['xattr'] = self::normalizeXAttributes( $stat['xattr'] );
				$this->cheapCache->setField( $path, 'xattr',
					[ 'map' => $stat['xattr'], 'latest' => $latest ] );
			}
		} elseif ( $stat === false ) { // file does not exist
			$this->cheapCache->setField( $path, 'stat', $latest ? 'NOT_EXIST_LATEST' : 'NOT_EXIST' );
			$this->cheapCache->setField( $path, 'xattr', [ 'map' => false, 'latest' => $latest ] );
			$this->cheapCache->setField( $path, 'sha1', [ 'hash' => false, 'latest' => $latest ] );
			$this->logger->debug( __METHOD__ . ': File {path} does not exist', [
				'path' => $path,
			] );
		} else { // an error occurred
			$this->logger->warning( __METHOD__ . ': Could not stat file {path}', [
				'path' => $path,
			] );
		}

		return $stat;
	}

	/**
	 * @see FileBackendStore::getFileStat()
	 * @param array $params
	 */
	abstract protected function doGetFileStat( array $params );

	public function getFileContentsMulti( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );
		$contents = $this->doGetFileContentsMulti( $params );

		return $contents;
	}

	/**
	 * @see FileBackendStore::getFileContentsMulti()
	 * @param array $params
	 * @return array
	 */
	protected function doGetFileContentsMulti( array $params ) {
		$contents = [];
		foreach ( $this->doGetLocalReferenceMulti( $params ) as $path => $fsFile ) {
			Wikimedia\suppressWarnings();
			$contents[$path] = $fsFile ? file_get_contents( $fsFile->getPath() ) : false;
			Wikimedia\restoreWarnings();
		}

		return $contents;
	}

	final public function getFileXAttributes( array $params ) {
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( $this->cheapCache->hasField( $path, 'xattr', self::CACHE_TTL ) ) {
			$stat = $this->cheapCache->getField( $path, 'xattr' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $stat['latest'] ) {
				return $stat['map'];
			}
		}
		$fields = $this->doGetFileXAttributes( $params );
		$fields = is_array( $fields ) ? self::normalizeXAttributes( $fields ) : false;
		$this->cheapCache->setField( $path, 'xattr', [ 'map' => $fields, 'latest' => $latest ] );

		return $fields;
	}

	/**
	 * @see FileBackendStore::getFileXAttributes()
	 * @param array $params
	 * @return array[][]
	 */
	protected function doGetFileXAttributes( array $params ) {
		return [ 'headers' => [], 'metadata' => [] ]; // not supported
	}

	final public function getFileSha1Base36( array $params ) {
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( $this->cheapCache->hasField( $path, 'sha1', self::CACHE_TTL ) ) {
			$stat = $this->cheapCache->getField( $path, 'sha1' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $stat['latest'] ) {
				return $stat['hash'];
			}
		}
		$hash = $this->doGetFileSha1Base36( $params );
		$this->cheapCache->setField( $path, 'sha1', [ 'hash' => $hash, 'latest' => $latest ] );

		return $hash;
	}

	/**
	 * @see FileBackendStore::getFileSha1Base36()
	 * @param array $params
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

	final public function getFileProps( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$fsFile = $this->getLocalReference( $params );
		$props = $fsFile ? $fsFile->getProps() : FSFile::placeholderProps();

		return $props;
	}

	final public function getLocalReferenceMulti( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );

		$fsFiles = []; // (path => FSFile)
		$latest = !empty( $params['latest'] ); // use latest data?
		// Reuse any files already in process cache...
		foreach ( $params['srcs'] as $src ) {
			$path = self::normalizeStoragePath( $src );
			if ( $path === null ) {
				$fsFiles[$src] = null; // invalid storage path
			} elseif ( $this->expensiveCache->hasField( $path, 'localRef' ) ) {
				$val = $this->expensiveCache->getField( $path, 'localRef' );
				// If we want the latest data, check that this cached
				// value was in fact fetched with the latest available data.
				if ( !$latest || $val['latest'] ) {
					$fsFiles[$src] = $val['object'];
				}
			}
		}
		// Fetch local references of any remaning files...
		$params['srcs'] = array_diff( $params['srcs'], array_keys( $fsFiles ) );
		foreach ( $this->doGetLocalReferenceMulti( $params ) as $path => $fsFile ) {
			$fsFiles[$path] = $fsFile;
			if ( $fsFile ) { // update the process cache...
				$this->expensiveCache->setField( $path, 'localRef',
					[ 'object' => $fsFile, 'latest' => $latest ] );
			}
		}

		return $fsFiles;
	}

	/**
	 * @see FileBackendStore::getLocalReferenceMulti()
	 * @param array $params
	 * @return array
	 */
	protected function doGetLocalReferenceMulti( array $params ) {
		return $this->doGetLocalCopyMulti( $params );
	}

	final public function getLocalCopyMulti( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );
		$tmpFiles = $this->doGetLocalCopyMulti( $params );

		return $tmpFiles;
	}

	/**
	 * @see FileBackendStore::getLocalCopyMulti()
	 * @param array $params
	 * @return array
	 */
	abstract protected function doGetLocalCopyMulti( array $params );

	/**
	 * @see FileBackend::getFileHttpUrl()
	 * @param array $params
	 * @return string|null
	 */
	public function getFileHttpUrl( array $params ) {
		return null; // not supported
	}

	final public function streamFile( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Always set some fields for subclass convenience
		$params['options'] = $params['options'] ?? [];
		$params['headers'] = $params['headers'] ?? [];

		// Don't stream it out as text/html if there was a PHP error
		if ( ( empty( $params['headless'] ) || $params['headers'] ) && headers_sent() ) {
			print "Headers already sent, terminating.\n";
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		}

		$status->merge( $this->doStreamFile( $params ) );

		return $status;
	}

	/**
	 * @see FileBackendStore::streamFile()
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doStreamFile( array $params ) {
		$status = $this->newStatus();

		$flags = 0;
		$flags |= !empty( $params['headless'] ) ? HTTPFileStreamer::STREAM_HEADLESS : 0;
		$flags |= !empty( $params['allowOB'] ) ? HTTPFileStreamer::STREAM_ALLOW_OB : 0;

		$fsFile = $this->getLocalReference( $params );
		if ( $fsFile ) {
			$streamer = new HTTPFileStreamer(
				$fsFile->getPath(),
				[
					'obResetFunc' => $this->obResetFunc,
					'streamMimeFunc' => $this->streamMimeFunc
				]
			);
			$res = $streamer->stream( $params['headers'], true, $params['options'], $flags );
		} else {
			$res = false;
			HTTPFileStreamer::send404Message( $params['src'], $flags );
		}

		if ( !$res ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		return $status;
	}

	final public function directoryExists( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			return false; // invalid storage path
		}
		if ( $shard !== null ) { // confined to a single container/shard
			return $this->doDirectoryExists( $fullCont, $dir, $params );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );
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
	 * @param string $container Resolved container name
	 * @param string $dir Resolved path relative to container
	 * @param array $params
	 * @return bool|null
	 */
	abstract protected function doDirectoryExists( $container, $dir, array $params );

	final public function getDirectoryList( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getDirectoryListInternal( $fullCont, $dir, $params );
		} else {
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			// File listing spans multiple containers/shards
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );

			return new FileBackendStoreShardDirIterator( $this,
				$fullCont, $dir, $this->getContainerSuffixes( $shortCont ), $params );
		}
	}

	/**
	 * Do not call this function from places outside FileBackend
	 *
	 * @see FileBackendStore::getDirectoryList()
	 *
	 * @param string $container Resolved container name
	 * @param string $dir Resolved path relative to container
	 * @param array $params
	 * @return Traversable|array|null Returns null on failure
	 */
	abstract public function getDirectoryListInternal( $container, $dir, array $params );

	final public function getFileList( array $params ) {
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getFileListInternal( $fullCont, $dir, $params );
		} else {
			$this->logger->debug( __METHOD__ . ": iterating over all container shards.\n" );
			// File listing spans multiple containers/shards
			list( , $shortCont, ) = self::splitStoragePath( $params['dir'] );

			return new FileBackendStoreShardFileIterator( $this,
				$fullCont, $dir, $this->getContainerSuffixes( $shortCont ), $params );
		}
	}

	/**
	 * Do not call this function from places outside FileBackend
	 *
	 * @see FileBackendStore::getFileList()
	 *
	 * @param string $container Resolved container name
	 * @param string $dir Resolved path relative to container
	 * @param array $params
	 * @return Traversable|string[]|null Returns null on failure
	 */
	abstract public function getFileListInternal( $container, $dir, array $params );

	/**
	 * Return a list of FileOp objects from a list of operations.
	 * Do not call this function from places outside FileBackend.
	 *
	 * The result must have the same number of items as the input.
	 * An exception is thrown if an unsupported operation is requested.
	 *
	 * @param array $ops Same format as doOperations()
	 * @return FileOp[] List of FileOp objects
	 * @throws FileBackendError
	 */
	final public function getOperationsInternal( array $ops ) {
		$supportedOps = [
			'store' => StoreFileOp::class,
			'copy' => CopyFileOp::class,
			'move' => MoveFileOp::class,
			'delete' => DeleteFileOp::class,
			'create' => CreateFileOp::class,
			'describe' => DescribeFileOp::class,
			'null' => NullFileOp::class
		];

		$performOps = []; // array of FileOp objects
		// Build up ordered array of FileOps...
		foreach ( $ops as $operation ) {
			$opName = $operation['op'];
			if ( isset( $supportedOps[$opName] ) ) {
				$class = $supportedOps[$opName];
				// Get params for this operation
				$params = $operation;
				// Append the FileOp class
				$performOps[] = new $class( $this, $params, $this->logger );
			} else {
				throw new FileBackendError( "Operation '$opName' is not supported." );
			}
		}

		return $performOps;
	}

	/**
	 * Get a list of storage paths to lock for a list of operations
	 * Returns an array with LockManager::LOCK_UW (shared locks) and
	 * LockManager::LOCK_EX (exclusive locks) keys, each corresponding
	 * to a list of storage paths to be locked. All returned paths are
	 * normalized.
	 *
	 * @param array $performOps List of FileOp objects
	 * @return array (LockManager::LOCK_UW => path list, LockManager::LOCK_EX => path list)
	 */
	final public function getPathsToLockForOpsInternal( array $performOps ) {
		// Build up a list of files to lock...
		$paths = [ 'sh' => [], 'ex' => [] ];
		foreach ( $performOps as $fileOp ) {
			$paths['sh'] = array_merge( $paths['sh'], $fileOp->storagePathsRead() );
			$paths['ex'] = array_merge( $paths['ex'], $fileOp->storagePathsChanged() );
		}
		// Optimization: if doing an EX lock anyway, don't also set an SH one
		$paths['sh'] = array_diff( $paths['sh'], $paths['ex'] );
		// Get a shared lock on the parent directory of each path changed
		$paths['sh'] = array_merge( $paths['sh'], array_map( 'dirname', $paths['ex'] ) );

		return [
			LockManager::LOCK_UW => $paths['sh'],
			LockManager::LOCK_EX => $paths['ex']
		];
	}

	public function getScopedLocksForOps( array $ops, StatusValue $status ) {
		$paths = $this->getPathsToLockForOpsInternal( $this->getOperationsInternal( $ops ) );

		return $this->getScopedFileLocks( $paths, 'mixed', $status );
	}

	final protected function doOperationsInternal( array $ops, array $opts ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Fix up custom header name/value pairs...
		$ops = array_map( [ $this, 'sanitizeOpHeaders' ], $ops );

		// Build up a list of FileOps...
		$performOps = $this->getOperationsInternal( $ops );

		// Acquire any locks as needed...
		if ( empty( $opts['nonLocking'] ) ) {
			// Build up a list of files to lock...
			$paths = $this->getPathsToLockForOpsInternal( $performOps );
			// Try to lock those files for the scope of this function...

			$scopeLock = $this->getScopedFileLocks( $paths, 'mixed', $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}

		// Clear any file cache entries (after locks acquired)
		if ( empty( $opts['preserveCache'] ) ) {
			$this->clearCache();
		}

		// Build the list of paths involved
		$paths = [];
		foreach ( $performOps as $performOp ) {
			$paths = array_merge( $paths, $performOp->storagePathsRead() );
			$paths = array_merge( $paths, $performOp->storagePathsChanged() );
		}

		// Enlarge the cache to fit the stat entries of these files
		$this->cheapCache->setMaxSize( max( 2 * count( $paths ), self::CACHE_CHEAP_SIZE ) );

		// Load from the persistent container caches
		$this->primeContainerCache( $paths );
		// Get the latest stat info for all the files (having locked them)
		$ok = $this->preloadFileStat( [ 'srcs' => $paths, 'latest' => true ] );

		if ( $ok ) {
			// Actually attempt the operation batch...
			$opts = $this->setConcurrencyFlags( $opts );
			$subStatus = FileOpBatch::attempt( $performOps, $opts, $this->fileJournal );
		} else {
			// If we could not even stat some files, then bail out...
			$subStatus = $this->newStatus( 'backend-fail-internal', $this->name );
			foreach ( $ops as $i => $op ) { // mark each op as failed
				$subStatus->success[$i] = false;
				++$subStatus->failCount;
			}
			$this->logger->error( static::class . "-{$this->name} " .
				" stat failure; aborted operations: " . FormatJson::encode( $ops ) );
		}

		// Merge errors into StatusValue fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

		// Shrink the stat cache back to normal size
		$this->cheapCache->setMaxSize( self::CACHE_CHEAP_SIZE );

		return $status;
	}

	final protected function doQuickOperationsInternal( array $ops ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Fix up custom header name/value pairs...
		$ops = array_map( [ $this, 'sanitizeOpHeaders' ], $ops );

		// Clear any file cache entries
		$this->clearCache();

		$supportedOps = [ 'create', 'store', 'copy', 'move', 'delete', 'describe', 'null' ];
		// Parallel ops may be disabled in config due to dependencies (e.g. needing popen())
		$async = ( $this->parallelize === 'implicit' && count( $ops ) > 1 );
		$maxConcurrency = $this->concurrency; // throttle
		/** @var StatusValue[] $statuses */
		$statuses = []; // array of (index => StatusValue)
		$fileOpHandles = []; // list of (index => handle) arrays
		$curFileOpHandles = []; // current handle batch
		// Perform the sync-only ops and build up op handles for the async ops...
		foreach ( $ops as $index => $params ) {
			if ( !in_array( $params['op'], $supportedOps ) ) {
				throw new FileBackendError( "Operation '{$params['op']}' is not supported." );
			}
			$method = $params['op'] . 'Internal'; // e.g. "storeInternal"
			$subStatus = $this->$method( [ 'async' => $async ] + $params );
			if ( $subStatus->value instanceof FileBackendStoreOpHandle ) { // async
				if ( count( $curFileOpHandles ) >= $maxConcurrency ) {
					$fileOpHandles[] = $curFileOpHandles; // push this batch
					$curFileOpHandles = [];
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

		return $status;
	}

	/**
	 * Execute a list of FileBackendStoreOpHandle handles in parallel.
	 * The resulting StatusValue object fields will correspond
	 * to the order in which the handles where given.
	 *
	 * @param FileBackendStoreOpHandle[] $fileOpHandles
	 * @return StatusValue[] Map of StatusValue objects
	 * @throws FileBackendError
	 */
	final public function executeOpHandlesInternal( array $fileOpHandles ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		foreach ( $fileOpHandles as $fileOpHandle ) {
			if ( !( $fileOpHandle instanceof FileBackendStoreOpHandle ) ) {
				throw new InvalidArgumentException( "Expected FileBackendStoreOpHandle object." );
			} elseif ( $fileOpHandle->backend->getName() !== $this->getName() ) {
				throw new InvalidArgumentException( "Expected handle for this file backend." );
			}
		}

		$res = $this->doExecuteOpHandlesInternal( $fileOpHandles );
		foreach ( $fileOpHandles as $fileOpHandle ) {
			$fileOpHandle->closeResources();
		}

		return $res;
	}

	/**
	 * @see FileBackendStore::executeOpHandlesInternal()
	 *
	 * @param FileBackendStoreOpHandle[] $fileOpHandles
	 *
	 * @throws FileBackendError
	 * @return StatusValue[] List of corresponding StatusValue objects
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		if ( count( $fileOpHandles ) ) {
			throw new LogicException( "Backend does not support asynchronous operations." );
		}

		return [];
	}

	/**
	 * Normalize and filter HTTP headers from a file operation
	 *
	 * This normalizes and strips long HTTP headers from a file operation.
	 * Most headers are just numbers, but some are allowed to be long.
	 * This function is useful for cleaning up headers and avoiding backend
	 * specific errors, especially in the middle of batch file operations.
	 *
	 * @param array $op Same format as doOperation()
	 * @return array
	 */
	protected function sanitizeOpHeaders( array $op ) {
		static $longs = [ 'content-disposition' ];

		if ( isset( $op['headers'] ) ) { // op sets HTTP headers
			$newHeaders = [];
			foreach ( $op['headers'] as $name => $value ) {
				$name = strtolower( $name );
				$maxHVLen = in_array( $name, $longs ) ? INF : 255;
				if ( strlen( $name ) > 255 || strlen( $value ) > $maxHVLen ) {
					trigger_error( "Header '$name: $value' is too long." );
				} else {
					$newHeaders[$name] = strlen( $value ) ? $value : ''; // null/false => ""
				}
			}
			$op['headers'] = $newHeaders;
		}

		return $op;
	}

	final public function preloadCache( array $paths ) {
		$fullConts = []; // full container names
		foreach ( $paths as $path ) {
			list( $fullCont, , ) = $this->resolveStoragePath( $path );
			$fullConts[] = $fullCont;
		}
		// Load from the persistent file and container caches
		$this->primeContainerCache( $fullConts );
		$this->primeFileCache( $paths );
	}

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
	 * @param array|null $paths Storage paths (optional)
	 */
	protected function doClearCache( array $paths = null ) {
	}

	final public function preloadFileStat( array $params ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$success = true; // no network errors

		$params['concurrency'] = ( $this->parallelize !== 'off' ) ? $this->concurrency : 1;
		$stats = $this->doGetFileStatMulti( $params );
		if ( $stats === null ) {
			return true; // not supported
		}

		$latest = !empty( $params['latest'] ); // use latest data?
		foreach ( $stats as $path => $stat ) {
			$path = FileBackend::normalizeStoragePath( $path );
			if ( $path === null ) {
				continue; // this shouldn't happen
			}
			if ( is_array( $stat ) ) { // file exists
				// Strongly consistent backends can automatically set "latest"
				$stat['latest'] = $stat['latest'] ?? $latest;
				$this->cheapCache->setField( $path, 'stat', $stat );
				$this->setFileCache( $path, $stat ); // update persistent cache
				if ( isset( $stat['sha1'] ) ) { // some backends store SHA-1 as metadata
					$this->cheapCache->setField( $path, 'sha1',
						[ 'hash' => $stat['sha1'], 'latest' => $latest ] );
				}
				if ( isset( $stat['xattr'] ) ) { // some backends store headers/metadata
					$stat['xattr'] = self::normalizeXAttributes( $stat['xattr'] );
					$this->cheapCache->setField( $path, 'xattr',
						[ 'map' => $stat['xattr'], 'latest' => $latest ] );
				}
			} elseif ( $stat === false ) { // file does not exist
				$this->cheapCache->setField( $path, 'stat',
					$latest ? 'NOT_EXIST_LATEST' : 'NOT_EXIST' );
				$this->cheapCache->setField( $path, 'xattr',
					[ 'map' => false, 'latest' => $latest ] );
				$this->cheapCache->setField( $path, 'sha1',
					[ 'hash' => false, 'latest' => $latest ] );
				$this->logger->debug( __METHOD__ . ': File {path} does not exist', [
					'path' => $path,
				] );
			} else { // an error occurred
				$success = false;
				$this->logger->warning( __METHOD__ . ': Could not stat file {path}', [
					'path' => $path,
				] );
			}
		}

		return $success;
	}

	/**
	 * Get file stat information (concurrently if possible) for several files
	 *
	 * @see FileBackend::getFileStat()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 * @return array|null Map of storage paths to array|bool|null (returns null if not supported)
	 * @since 1.23
	 */
	protected function doGetFileStatMulti( array $params ) {
		return null; // not supported
	}

	/**
	 * Is this a key/value store where directories are just virtual?
	 * Virtual directories exists in so much as files exists that are
	 * prefixed with the directory path followed by a forward slash.
	 *
	 * @return bool
	 */
	abstract protected function directoriesAreVirtual();

	/**
	 * Check if a short container name is valid
	 *
	 * This checks for length and illegal characters.
	 * This may disallow certain characters that can appear
	 * in the prefix used to make the full container name.
	 *
	 * @param string $container
	 * @return bool
	 */
	final protected static function isValidShortContainerName( $container ) {
		// Suffixes like '.xxx' (hex shard chars) or '.seg' (file segments)
		// might be used by subclasses. Reserve the dot character for sanity.
		// The only way dots end up in containers (e.g. resolveStoragePath)
		// is due to the wikiId container prefix or the above suffixes.
		return self::isValidContainerName( $container ) && !preg_match( '/[.]/', $container );
	}

	/**
	 * Check if a full container name is valid
	 *
	 * This checks for length and illegal characters.
	 * Limiting the characters makes migrations to other stores easier.
	 *
	 * @param string $container
	 * @return bool
	 */
	final protected static function isValidContainerName( $container ) {
		// This accounts for NTFS, Swift, and Ceph restrictions
		// and disallows directory separators or traversal characters.
		// Note that matching strings URL encode to the same string;
		// in Swift/Ceph, the length restriction is *after* URL encoding.
		return (bool)preg_match( '/^[a-z0-9][a-z0-9-_.]{0,199}$/i', $container );
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
	 * @param string $storagePath
	 * @return array (container, path, container suffix) or (null, null, null) if invalid
	 */
	final protected function resolveStoragePath( $storagePath ) {
		list( $backend, $shortCont, $relPath ) = self::splitStoragePath( $storagePath );
		if ( $backend === $this->name ) { // must be for this backend
			$relPath = self::normalizeContainerPath( $relPath );
			if ( $relPath !== null && self::isValidShortContainerName( $shortCont ) ) {
				// Get shard for the normalized path if this container is sharded
				$cShard = $this->getContainerShard( $shortCont, $relPath );
				// Validate and sanitize the relative path (backend-specific)
				$relPath = $this->resolveContainerPath( $shortCont, $relPath );
				if ( $relPath !== null ) {
					// Prepend any wiki ID prefix to the container name
					$container = $this->fullContainerName( $shortCont );
					if ( self::isValidContainerName( $container ) ) {
						// Validate and sanitize the container name (backend-specific)
						$container = $this->resolveContainerName( "{$container}{$cShard}" );
						if ( $container !== null ) {
							return [ $container, $relPath, $cShard ];
						}
					}
				}
			}
		}

		return [ null, null, null ];
	}

	/**
	 * Like resolveStoragePath() except null values are returned if
	 * the container is sharded and the shard could not be determined
	 * or if the path ends with '/'. The latter case is illegal for FS
	 * backends and can confuse listings for object store backends.
	 *
	 * This function is used when resolving paths that must be valid
	 * locations for files. Directory and listing functions should
	 * generally just use resolveStoragePath() instead.
	 *
	 * @see FileBackendStore::resolveStoragePath()
	 *
	 * @param string $storagePath
	 * @return array (container, path) or (null, null) if invalid
	 */
	final protected function resolveStoragePathReal( $storagePath ) {
		list( $container, $relPath, $cShard ) = $this->resolveStoragePath( $storagePath );
		if ( $cShard !== null && substr( $relPath, -1 ) !== '/' ) {
			return [ $container, $relPath ];
		}

		return [ null, null ];
	}

	/**
	 * Get the container name shard suffix for a given path.
	 * Any empty suffix means the container is not sharded.
	 *
	 * @param string $container Container name
	 * @param string $relPath Storage path relative to the container
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
			$m = [];
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
	 * @param string $storagePath Storage path
	 * @return bool
	 */
	final public function isSingleShardPathInternal( $storagePath ) {
		list( , , $shard ) = $this->resolveStoragePath( $storagePath );

		return ( $shard !== null );
	}

	/**
	 * Get the sharding config for a container.
	 * If greater than 0, then all file storage paths within
	 * the container are required to be hashed accordingly.
	 *
	 * @param string $container
	 * @return array (integer levels, integer base, repeat flag) or (0, 0, false)
	 */
	final protected function getContainerHashLevels( $container ) {
		if ( isset( $this->shardViaHashLevels[$container] ) ) {
			$config = $this->shardViaHashLevels[$container];
			$hashLevels = (int)$config['levels'];
			if ( $hashLevels == 1 || $hashLevels == 2 ) {
				$hashBase = (int)$config['base'];
				if ( $hashBase == 16 || $hashBase == 36 ) {
					return [ $hashLevels, $hashBase, $config['repeat'] ];
				}
			}
		}

		return [ 0, 0, false ]; // no sharding
	}

	/**
	 * Get a list of full container shard suffixes for a container
	 *
	 * @param string $container
	 * @return array
	 */
	final protected function getContainerSuffixes( $container ) {
		$shards = [];
		list( $digits, $base ) = $this->getContainerHashLevels( $container );
		if ( $digits > 0 ) {
			$numShards = $base ** $digits;
			for ( $index = 0; $index < $numShards; $index++ ) {
				$shards[] = '.' . Wikimedia\base_convert( $index, 10, $base, $digits );
			}
		}

		return $shards;
	}

	/**
	 * Get the full container name, including the wiki ID prefix
	 *
	 * @param string $container
	 * @return string
	 */
	final protected function fullContainerName( $container ) {
		if ( $this->domainId != '' ) {
			return "{$this->domainId}-$container";
		} else {
			return $container;
		}
	}

	/**
	 * Resolve a container name, checking if it's allowed by the backend.
	 * This is intended for internal use, such as encoding illegal chars.
	 * Subclasses can override this to be more restrictive.
	 *
	 * @param string $container
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
	 * @param string $container Container name
	 * @param string $relStoragePath Storage path relative to the container
	 * @return string|null Path or null if not valid
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		return $relStoragePath;
	}

	/**
	 * Get the cache key for a container
	 *
	 * @param string $container Resolved container name
	 * @return string
	 */
	private function containerCacheKey( $container ) {
		return "filebackend:{$this->name}:{$this->domainId}:container:{$container}";
	}

	/**
	 * Set the cached info for a container
	 *
	 * @param string $container Resolved container name
	 * @param array $val Information to cache
	 */
	final protected function setContainerCache( $container, array $val ) {
		$this->memCache->set( $this->containerCacheKey( $container ), $val, 14 * 86400 );
	}

	/**
	 * Delete the cached info for a container.
	 * The cache key is salted for a while to prevent race conditions.
	 *
	 * @param string $container Resolved container name
	 */
	final protected function deleteContainerCache( $container ) {
		if ( !$this->memCache->delete( $this->containerCacheKey( $container ), 300 ) ) {
			trigger_error( "Unable to delete stat cache for container $container." );
		}
	}

	/**
	 * Do a batch lookup from cache for container stats for all containers
	 * used in a list of container names or storage paths objects.
	 * This loads the persistent cache values into the process cache.
	 *
	 * @param array $items
	 */
	final protected function primeContainerCache( array $items ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$paths = []; // list of storage paths
		$contNames = []; // (cache key => resolved container name)
		// Get all the paths/containers from the items...
		foreach ( $items as $item ) {
			if ( self::isStoragePath( $item ) ) {
				$paths[] = $item;
			} elseif ( is_string( $item ) ) { // full container name
				$contNames[$this->containerCacheKey( $item )] = $item;
			}
		}
		// Get all the corresponding cache keys for paths...
		foreach ( $paths as $path ) {
			list( $fullCont, , ) = $this->resolveStoragePath( $path );
			if ( $fullCont !== null ) { // valid path for this backend
				$contNames[$this->containerCacheKey( $fullCont )] = $fullCont;
			}
		}

		$contInfo = []; // (resolved container name => cache value)
		// Get all cache entries for these container cache keys...
		$values = $this->memCache->getMulti( array_keys( $contNames ) );
		foreach ( $values as $cacheKey => $val ) {
			$contInfo[$contNames[$cacheKey]] = $val;
		}

		// Populate the container process cache for the backend...
		$this->doPrimeContainerCache( array_filter( $contInfo, 'is_array' ) );
	}

	/**
	 * Fill the backend-specific process cache given an array of
	 * resolved container names and their corresponding cached info.
	 * Only containers that actually exist should appear in the map.
	 *
	 * @param array $containerInfo Map of resolved container names to cached info
	 */
	protected function doPrimeContainerCache( array $containerInfo ) {
	}

	/**
	 * Get the cache key for a file path
	 *
	 * @param string $path Normalized storage path
	 * @return string
	 */
	private function fileCacheKey( $path ) {
		return "filebackend:{$this->name}:{$this->domainId}:file:" . sha1( $path );
	}

	/**
	 * Set the cached stat info for a file path.
	 * Negatives (404s) are not cached. By not caching negatives, we can skip cache
	 * salting for the case when a file is created at a path were there was none before.
	 *
	 * @param string $path Storage path
	 * @param array $val Stat information to cache
	 */
	final protected function setFileCache( $path, array $val ) {
		$path = FileBackend::normalizeStoragePath( $path );
		if ( $path === null ) {
			return; // invalid storage path
		}
		$mtime = ConvertibleTimestamp::convert( TS_UNIX, $val['mtime'] );
		$ttl = $this->memCache->adaptiveTTL( $mtime, 7 * 86400, 300, 0.1 );
		$key = $this->fileCacheKey( $path );
		// Set the cache unless it is currently salted.
		$this->memCache->set( $key, $val, $ttl );
	}

	/**
	 * Delete the cached stat info for a file path.
	 * The cache key is salted for a while to prevent race conditions.
	 * Since negatives (404s) are not cached, this does not need to be called when
	 * a file is created at a path were there was none before.
	 *
	 * @param string $path Storage path
	 */
	final protected function deleteFileCache( $path ) {
		$path = FileBackend::normalizeStoragePath( $path );
		if ( $path === null ) {
			return; // invalid storage path
		}
		if ( !$this->memCache->delete( $this->fileCacheKey( $path ), 300 ) ) {
			trigger_error( "Unable to delete stat cache for file $path." );
		}
	}

	/**
	 * Do a batch lookup from cache for file stats for all paths
	 * used in a list of storage paths or FileOp objects.
	 * This loads the persistent cache values into the process cache.
	 *
	 * @param array $items List of storage paths
	 */
	final protected function primeFileCache( array $items ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$paths = []; // list of storage paths
		$pathNames = []; // (cache key => storage path)
		// Get all the paths/containers from the items...
		foreach ( $items as $item ) {
			if ( self::isStoragePath( $item ) ) {
				$paths[] = FileBackend::normalizeStoragePath( $item );
			}
		}
		// Get rid of any paths that failed normalization...
		$paths = array_filter( $paths, 'strlen' ); // remove nulls
		// Get all the corresponding cache keys for paths...
		foreach ( $paths as $path ) {
			list( , $rel, ) = $this->resolveStoragePath( $path );
			if ( $rel !== null ) { // valid path for this backend
				$pathNames[$this->fileCacheKey( $path )] = $path;
			}
		}
		// Get all cache entries for these file cache keys...
		$values = $this->memCache->getMulti( array_keys( $pathNames ) );
		foreach ( $values as $cacheKey => $val ) {
			$path = $pathNames[$cacheKey];
			if ( is_array( $val ) ) {
				$val['latest'] = false; // never completely trust cache
				$this->cheapCache->setField( $path, 'stat', $val );
				if ( isset( $val['sha1'] ) ) { // some backends store SHA-1 as metadata
					$this->cheapCache->setField( $path, 'sha1',
						[ 'hash' => $val['sha1'], 'latest' => false ] );
				}
				if ( isset( $val['xattr'] ) ) { // some backends store headers/metadata
					$val['xattr'] = self::normalizeXAttributes( $val['xattr'] );
					$this->cheapCache->setField( $path, 'xattr',
						[ 'map' => $val['xattr'], 'latest' => false ] );
				}
			}
		}
	}

	/**
	 * Normalize file headers/metadata to the FileBackend::getFileXAttributes() format
	 *
	 * @param array $xattr
	 * @return array
	 * @since 1.22
	 */
	final protected static function normalizeXAttributes( array $xattr ) {
		$newXAttr = [ 'headers' => [], 'metadata' => [] ];

		foreach ( $xattr['headers'] as $name => $value ) {
			$newXAttr['headers'][strtolower( $name )] = $value;
		}

		foreach ( $xattr['metadata'] as $name => $value ) {
			$newXAttr['metadata'][strtolower( $name )] = $value;
		}

		return $newXAttr;
	}

	/**
	 * Set the 'concurrency' option from a list of operation options
	 *
	 * @param array $opts Map of operation options
	 * @return array
	 */
	final protected function setConcurrencyFlags( array $opts ) {
		$opts['concurrency'] = 1; // off
		if ( $this->parallelize === 'implicit' ) {
			if ( !isset( $opts['parallelize'] ) || $opts['parallelize'] ) {
				$opts['concurrency'] = $this->concurrency;
			}
		} elseif ( $this->parallelize === 'explicit' ) {
			if ( !empty( $opts['parallelize'] ) ) {
				$opts['concurrency'] = $this->concurrency;
			}
		}

		return $opts;
	}

	/**
	 * Get the content type to use in HEAD/GET requests for a file
	 *
	 * @param string $storagePath
	 * @param string|null $content File data
	 * @param string|null $fsPath File system path
	 * @return string MIME type
	 */
	protected function getContentType( $storagePath, $content, $fsPath ) {
		if ( $this->mimeCallback ) {
			return call_user_func_array( $this->mimeCallback, func_get_args() );
		}

		$mime = ( $fsPath !== null ) ? mime_content_type( $fsPath ) : false;
		return $mime ?: 'unknown/unknown';
	}
}

/**
 * FileBackendStore helper class for performing asynchronous file operations.
 *
 * For example, calling FileBackendStore::createInternal() with the "async"
 * param flag may result in a StatusValue that contains this object as a value.
 * This class is largely backend-specific and is mostly just "magic" to be
 * passed to FileBackendStore::executeOpHandlesInternal().
 */
abstract class FileBackendStoreOpHandle {
	/** @var array */
	public $params = []; // params to caller functions
	/** @var FileBackendStore */
	public $backend;
	/** @var array */
	public $resourcesToClose = [];

	public $call; // string; name that identifies the function called

	/**
	 * Close all open file handles
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
abstract class FileBackendStoreShardListIterator extends FilterIterator {
	/** @var FileBackendStore */
	protected $backend;

	/** @var array */
	protected $params;

	/** @var string Full container name */
	protected $container;

	/** @var string Resolved relative path */
	protected $directory;

	/** @var array */
	protected $multiShardPaths = []; // (rel path => 1)

	/**
	 * @param FileBackendStore $backend
	 * @param string $container Full storage container name
	 * @param string $dir Storage directory relative to container
	 * @param array $suffixes List of container shard suffixes
	 * @param array $params
	 */
	public function __construct(
		FileBackendStore $backend, $container, $dir, array $suffixes, array $params
	) {
		$this->backend = $backend;
		$this->container = $container;
		$this->directory = $dir;
		$this->params = $params;

		$iter = new AppendIterator();
		foreach ( $suffixes as $suffix ) {
			$iter->append( $this->listFromShard( $this->container . $suffix ) );
		}

		parent::__construct( $iter );
	}

	public function accept() {
		$rel = $this->getInnerIterator()->current(); // path relative to given directory
		$path = $this->params['dir'] . "/{$rel}"; // full storage path
		if ( $this->backend->isSingleShardPathInternal( $path ) ) {
			return true; // path is only on one shard; no issue with duplicates
		} elseif ( isset( $this->multiShardPaths[$rel] ) ) {
			// Don't keep listing paths that are on multiple shards
			return false;
		} else {
			$this->multiShardPaths[$rel] = 1;

			return true;
		}
	}

	public function rewind() {
		parent::rewind();
		$this->multiShardPaths = [];
	}

	/**
	 * Get the list for a given container shard
	 *
	 * @param string $container Resolved container name
	 * @return Iterator
	 */
	abstract protected function listFromShard( $container );
}

/**
 * Iterator for listing directories
 */
class FileBackendStoreShardDirIterator extends FileBackendStoreShardListIterator {
	protected function listFromShard( $container ) {
		$list = $this->backend->getDirectoryListInternal(
			$container, $this->directory, $this->params );
		if ( $list === null ) {
			return new ArrayIterator( [] );
		} else {
			return is_array( $list ) ? new ArrayIterator( $list ) : $list;
		}
	}
}

/**
 * Iterator for listing regular files
 */
class FileBackendStoreShardFileIterator extends FileBackendStoreShardListIterator {
	protected function listFromShard( $container ) {
		$list = $this->backend->getFileListInternal(
			$container, $this->directory, $this->params );
		if ( $list === null ) {
			return new ArrayIterator( [] );
		} else {
			return is_array( $list ) ? new ArrayIterator( $list ) : $list;
		}
	}
}
