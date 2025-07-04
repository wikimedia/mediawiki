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

namespace Wikimedia\FileBackend;

use InvalidArgumentException;
use LockManager;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use Traversable;
use Wikimedia\AtEase\AtEase;
use Wikimedia\FileBackend\FileIteration\FileBackendStoreShardDirIterator;
use Wikimedia\FileBackend\FileIteration\FileBackendStoreShardFileIterator;
use Wikimedia\FileBackend\FileOpHandle\FileBackendStoreOpHandle;
use Wikimedia\FileBackend\FileOps\CopyFileOp;
use Wikimedia\FileBackend\FileOps\CreateFileOp;
use Wikimedia\FileBackend\FileOps\DeleteFileOp;
use Wikimedia\FileBackend\FileOps\DescribeFileOp;
use Wikimedia\FileBackend\FileOps\FileOp;
use Wikimedia\FileBackend\FileOps\MoveFileOp;
use Wikimedia\FileBackend\FileOps\NullFileOp;
use Wikimedia\FileBackend\FileOps\StoreFileOp;
use Wikimedia\FileBackend\FSFile\FSFile;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
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
 * @stable to extend
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

	/** @var array<string,array> Map of container names to sharding config */
	protected $shardViaHashLevels = [];

	/** @var callable|null Method to get the MIME type of files */
	protected $mimeCallback;

	/** @var int Size in bytes, defaults to 32 GiB */
	protected $maxFileSize = 32 * 1024 * 1024 * 1024;

	protected const CACHE_TTL = 10; // integer; TTL in seconds for process cache entries
	protected const CACHE_CHEAP_SIZE = 500; // integer; max entries in "cheap cache"
	protected const CACHE_EXPENSIVE_SIZE = 5; // integer; max entries in "expensive cache"

	/** @var false Idiom for "no result due to missing file" (since 1.34) */
	protected const RES_ABSENT = false;
	/** @var null Idiom for "no result due to I/O errors" (since 1.34) */
	protected const RES_ERROR = null;

	/** @var string File does not exist according to a normal stat query */
	protected const ABSENT_NORMAL = 'FNE-N';
	/** @var string File does not exist according to a "latest"-mode stat query */
	protected const ABSENT_LATEST = 'FNE-L';

	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *   - srvCache     : BagOStuff cache to APC or the like.
	 *   - wanCache     : WANObjectCache object to use for persistent caching.
	 *   - mimeCallback : Callback that takes (storage path, content, file system path) and
	 *                    returns the MIME type of the file or 'unknown/unknown'. The file
	 *                    system path parameter should be used if the content one is null.
	 *
	 * @stable to call
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
		return min( $this->maxFileSize, PHP_INT_MAX );
	}

	/**
	 * Check if a file can be created or changed at a given storage path in the backend
	 *
	 * FS backends should check that the parent directory exists, files can be written
	 * under it, and that any file already there is both readable and writable.
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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		if ( strlen( $params['content'] ) > $this->maxFileSizeInternal() ) {
			$status = $this->newStatus( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doCreateInternal( $params );
			$this->clearCache( [ $params['dst'] ] );
			if ( $params['dstExists'] ?? true ) {
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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		if ( filesize( $params['src'] ) > $this->maxFileSizeInternal() ) {
			$status = $this->newStatus( 'backend-fail-maxsize',
				$params['dst'], $this->maxFileSizeInternal() );
		} else {
			$status = $this->doStoreInternal( $params );
			$this->clearCache( [ $params['dst'] ] );
			if ( $params['dstExists'] ?? true ) {
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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$status = $this->doCopyInternal( $params );
		$this->clearCache( [ $params['dst'] ] );
		if ( $params['dstExists'] ?? true ) {
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
		/** @noinspection PhpUnusedLocalVariableInspection */
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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$status = $this->doMoveInternal( $params );
		$this->clearCache( [ $params['src'], $params['dst'] ] );
		$this->deleteFileCache( $params['src'] ); // persistent cache
		if ( $params['dstExists'] ?? true ) {
			$this->deleteFileCache( $params['dst'] ); // persistent cache
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::moveInternal()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doMoveInternal( array $params );

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
		/** @noinspection PhpUnusedLocalVariableInspection */
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
	 * @stable to override
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

	/** @inheritDoc */
	final public function concatenate( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Try to lock the source files for the scope of this function
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopeLockS = $this->getScopedFileLocks( $params['srcs'], LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			// Actually do the file concatenation...
			$hrStart = hrtime( true );
			$status->merge( $this->doConcatenate( $params ) );
			$sec = ( hrtime( true ) - $hrStart ) / 1e9;
			if ( !$status->isOK() ) {
				$this->logger->error( static::class . "-{$this->name}" .
					" failed to concatenate " . count( $params['srcs'] ) . " file(s) [$sec sec]" );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::concatenate()
	 * @stable to override
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doConcatenate( array $params ) {
		$status = $this->newStatus();
		$tmpPath = $params['dst'];
		unset( $params['latest'] );

		// Check that the specified temp file is valid...
		AtEase::suppressWarnings();
		$ok = ( is_file( $tmpPath ) && filesize( $tmpPath ) == 0 );
		AtEase::restoreWarnings();
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
					$status->fatal(
						$fsFile === self::RES_ERROR ? 'backend-fail-read' : 'backend-fail-notexists',
						$path
					);

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

	/**
	 * @inheritDoc
	 */
	final protected function doPrepare( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doPrepareInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPrepareInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepare()
	 * @stable to override
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	protected function doPrepareInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	/** @inheritDoc */
	final protected function doSecure( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doSecureInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doSecure()
	 * @stable to override
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	protected function doSecureInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	/** @inheritDoc */
	final protected function doPublish( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doPublishInternal( "{$fullCont}{$suffix}", $dir, $params ) );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPublish()
	 * @stable to override
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doPublishInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	/** @inheritDoc */
	final protected function doClean( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
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

		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );

			return $status; // invalid storage path
		}

		// Attempt to lock this directory...
		$filesLockEx = [ $params['dir'] ];
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopedLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
		if ( !$status->isOK() ) {
			return $status; // abort
		}

		if ( $shard !== null ) { // confined to a single container/shard
			$status->merge( $this->doCleanInternal( $fullCont, $dir, $params ) );
			$this->deleteContainerCache( $fullCont ); // purge cache
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$status->merge( $this->doCleanInternal( "{$fullCont}{$suffix}", $dir, $params ) );
				$this->deleteContainerCache( "{$fullCont}{$suffix}" ); // purge cache
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doClean()
	 * @stable to override
	 * @param string $container
	 * @param string $dir
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doCleanInternal( $container, $dir, array $params ) {
		return $this->newStatus();
	}

	/** @inheritDoc */
	final public function fileExists( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$stat = $this->getFileStat( $params );
		if ( is_array( $stat ) ) {
			return true;
		}

		return $stat === self::RES_ABSENT ? false : self::EXISTENCE_ERROR;
	}

	/** @inheritDoc */
	final public function getFileTimestamp( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$stat = $this->getFileStat( $params );
		if ( is_array( $stat ) ) {
			return $stat['mtime'];
		}

		return self::TIMESTAMP_FAIL; // all failure cases
	}

	/** @inheritDoc */
	final public function getFileSize( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$stat = $this->getFileStat( $params );
		if ( is_array( $stat ) ) {
			return $stat['size'];
		}

		return self::SIZE_FAIL; // all failure cases
	}

	/** @inheritDoc */
	final public function getFileStat( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return self::STAT_ERROR; // invalid storage path
		}

		// Whether to bypass cache except for process cache entries loaded directly from
		// high consistency backend queries (caller handles any cache flushing and locking)
		$latest = !empty( $params['latest'] );
		// Whether to ignore cache entries missing the SHA-1 field for existing files
		$requireSHA1 = !empty( $params['requireSHA1'] );

		$stat = $this->cheapCache->getField( $path, 'stat', self::CACHE_TTL );
		// Load the persistent stat cache into process cache if needed
		if ( !$latest ) {
			if (
				// File stat is not in process cache
				$stat === null ||
				// Key/value store backends might opportunistically set file stat process
				// cache entries from object listings that do not include the SHA-1. In that
				// case, loading the persistent stat cache will likely yield the SHA-1.
				( $requireSHA1 && is_array( $stat ) && !isset( $stat['sha1'] ) )
			) {
				$this->primeFileCache( [ $path ] );
				// Get any newly process-cached entry
				$stat = $this->cheapCache->getField( $path, 'stat', self::CACHE_TTL );
			}
		}

		if ( is_array( $stat ) ) {
			if (
				( !$latest || !empty( $stat['latest'] ) ) &&
				( !$requireSHA1 || isset( $stat['sha1'] ) )
			) {
				return $stat;
			}
		} elseif ( $stat === self::ABSENT_LATEST ) {
			return self::STAT_ABSENT;
		} elseif ( $stat === self::ABSENT_NORMAL ) {
			if ( !$latest ) {
				return self::STAT_ABSENT;
			}
		}

		// Load the file stat from the backend and update caches
		$stat = $this->doGetFileStat( $params );
		$this->ingestFreshFileStats( [ $path => $stat ], $latest );

		if ( is_array( $stat ) ) {
			return $stat;
		}

		return $stat === self::RES_ERROR ? self::STAT_ERROR : self::STAT_ABSENT;
	}

	/**
	 * Ingest file stat entries that just came from querying the backend (not cache)
	 *
	 * @param array<string,array|false|null> $stats Map of storage path => {@see doGetFileStat} result
	 * @param bool $latest Whether doGetFileStat()/doGetFileStatMulti() had the 'latest' flag
	 * @return bool Whether all files have non-error stat replies
	 */
	final protected function ingestFreshFileStats( array $stats, $latest ) {
		$success = true;

		foreach ( $stats as $path => $stat ) {
			if ( is_array( $stat ) ) {
				// Strongly consistent backends might automatically set this flag
				$stat['latest'] ??= $latest;

				$this->cheapCache->setField( $path, 'stat', $stat );
				if ( isset( $stat['sha1'] ) ) {
					// Some backends store the SHA-1 hash as metadata
					$this->cheapCache->setField(
						$path,
						'sha1',
						[ 'hash' => $stat['sha1'], 'latest' => $latest ]
					);
				}
				if ( isset( $stat['xattr'] ) ) {
					// Some backends store custom headers/metadata
					$stat['xattr'] = self::normalizeXAttributes( $stat['xattr'] );
					$this->cheapCache->setField(
						$path,
						'xattr',
						[ 'map' => $stat['xattr'], 'latest' => $latest ]
					);
				}
				// Update persistent cache (@TODO: set all entries in one batch)
				$this->setFileCache( $path, $stat );
			} elseif ( $stat === self::RES_ABSENT ) {
				$this->cheapCache->setField(
					$path,
					'stat',
					$latest ? self::ABSENT_LATEST : self::ABSENT_NORMAL
				);
				$this->cheapCache->setField(
					$path,
					'xattr',
					[ 'map' => self::XATTRS_FAIL, 'latest' => $latest ]
				);
				$this->cheapCache->setField(
					$path,
					'sha1',
					[ 'hash' => self::SHA1_FAIL, 'latest' => $latest ]
				);
				$this->logger->debug(
					__METHOD__ . ': File {path} does not exist',
					[ 'path' => $path ]
				);
			} else {
				$success = false;
				$this->logger->error(
					__METHOD__ . ': Could not stat file {path}',
					[ 'path' => $path ]
				);
			}
		}

		return $success;
	}

	/**
	 * @see FileBackendStore::getFileStat()
	 * @param array $params
	 * @return array|false|null
	 */
	abstract protected function doGetFileStat( array $params );

	/** @inheritDoc */
	public function getFileContentsMulti( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );
		$contents = $this->doGetFileContentsMulti( $params );
		foreach ( $contents as $path => $content ) {
			if ( !is_string( $content ) ) {
				$contents[$path] = self::CONTENT_FAIL; // used for all failure cases
			}
		}

		return $contents;
	}

	/**
	 * @see FileBackendStore::getFileContentsMulti()
	 * @stable to override
	 * @param array $params
	 * @return string[]|bool[]|null[] Map of (path => string, false (missing), or null (error))
	 */
	protected function doGetFileContentsMulti( array $params ) {
		$contents = [];
		foreach ( $this->doGetLocalReferenceMulti( $params ) as $path => $fsFile ) {
			if ( $fsFile instanceof FSFile ) {
				AtEase::suppressWarnings();
				$content = file_get_contents( $fsFile->getPath() );
				AtEase::restoreWarnings();
				$contents[$path] = is_string( $content ) ? $content : self::RES_ERROR;
			} else {
				// self::RES_ERROR or self::RES_ABSENT
				$contents[$path] = $fsFile;
			}
		}

		return $contents;
	}

	/** @inheritDoc */
	final public function getFileXAttributes( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return self::XATTRS_FAIL; // invalid storage path
		}
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
		if ( is_array( $fields ) ) {
			$fields = self::normalizeXAttributes( $fields );
			$this->cheapCache->setField(
				$path,
				'xattr',
				[ 'map' => $fields, 'latest' => $latest ]
			);
		} elseif ( $fields === self::RES_ABSENT ) {
			$this->cheapCache->setField(
				$path,
				'xattr',
				[ 'map' => self::XATTRS_FAIL, 'latest' => $latest ]
			);
		} else {
			$fields = self::XATTRS_FAIL; // used for all failure cases
		}

		return $fields;
	}

	/**
	 * @see FileBackendStore::getFileXAttributes()
	 * @stable to override
	 * @param array $params
	 * @return array[][]|false|null Attributes, false (missing file), or null (error)
	 */
	protected function doGetFileXAttributes( array $params ) {
		return [ 'headers' => [], 'metadata' => [] ]; // not supported
	}

	/** @inheritDoc */
	final public function getFileSha1Base36( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return self::SHA1_FAIL; // invalid storage path
		}
		$latest = !empty( $params['latest'] ); // use latest data?
		if ( $this->cheapCache->hasField( $path, 'sha1', self::CACHE_TTL ) ) {
			$stat = $this->cheapCache->getField( $path, 'sha1' );
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $stat['latest'] ) {
				return $stat['hash'];
			}
		}
		$sha1 = $this->doGetFileSha1Base36( $params );
		if ( is_string( $sha1 ) ) {
			$this->cheapCache->setField(
				$path,
				'sha1',
				[ 'hash' => $sha1, 'latest' => $latest ]
			);
		} elseif ( $sha1 === self::RES_ABSENT ) {
			$this->cheapCache->setField(
				$path,
				'sha1',
				[ 'hash' => self::SHA1_FAIL, 'latest' => $latest ]
			);
		} else {
			$sha1 = self::SHA1_FAIL; // used for all failure cases
		}

		return $sha1;
	}

	/**
	 * @see FileBackendStore::getFileSha1Base36()
	 * @stable to override
	 * @param array $params
	 * @return bool|string|null SHA1, false (missing file), or null (error)
	 */
	protected function doGetFileSha1Base36( array $params ) {
		$fsFile = $this->getLocalReference( $params );
		if ( $fsFile instanceof FSFile ) {
			$sha1 = $fsFile->getSha1Base36();

			return is_string( $sha1 ) ? $sha1 : self::RES_ERROR;
		}

		return $fsFile === self::RES_ERROR ? self::RES_ERROR : self::RES_ABSENT;
	}

	/** @inheritDoc */
	final public function getFileProps( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$fsFile = $this->getLocalReference( $params );

		return $fsFile ? $fsFile->getProps() : FSFile::placeholderProps();
	}

	/** @inheritDoc */
	final public function getLocalReferenceMulti( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );

		$fsFiles = []; // (path => FSFile)
		$latest = !empty( $params['latest'] ); // use latest data?
		// Reuse any files already in process cache...
		foreach ( $params['srcs'] as $src ) {
			$path = self::normalizeStoragePath( $src );
			if ( $path === null ) {
				$fsFiles[$src] = self::RES_ERROR; // invalid storage path
			} elseif ( $this->expensiveCache->hasField( $path, 'localRef' ) ) {
				$val = $this->expensiveCache->getField( $path, 'localRef' );
				// If we want the latest data, check that this cached
				// value was in fact fetched with the latest available data.
				if ( !$latest || $val['latest'] ) {
					$fsFiles[$src] = $val['object'];
				}
			}
		}
		// Fetch local references of any remaining files...
		$params['srcs'] = array_diff( $params['srcs'], array_keys( $fsFiles ) );
		foreach ( $this->doGetLocalReferenceMulti( $params ) as $path => $fsFile ) {
			$fsFiles[$path] = $fsFile;
			if ( $fsFile instanceof FSFile ) {
				$this->expensiveCache->setField(
					$path,
					'localRef',
					[ 'object' => $fsFile, 'latest' => $latest ]
				);
			}
		}

		return $fsFiles;
	}

	/**
	 * @see FileBackendStore::getLocalReferenceMulti()
	 * @stable to override
	 * @param array $params
	 * @return string[]|bool[]|null[] Map of (path => FSFile, false (missing), or null (error))
	 */
	protected function doGetLocalReferenceMulti( array $params ) {
		return $this->doGetLocalCopyMulti( $params );
	}

	/** @inheritDoc */
	final public function getLocalCopyMulti( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params = $this->setConcurrencyFlags( $params );

		return $this->doGetLocalCopyMulti( $params );
	}

	/**
	 * @see FileBackendStore::getLocalCopyMulti()
	 * @param array $params
	 * @return string[]|bool[]|null[] Map of (path => TempFSFile, false (missing), or null (error))
	 */
	abstract protected function doGetLocalCopyMulti( array $params );

	/**
	 * @see FileBackend::getFileHttpUrl()
	 * @stable to override
	 * @param array $params
	 * @return string|null
	 */
	public function getFileHttpUrl( array $params ) {
		return self::TEMPURL_ERROR; // not supported
	}

	/** @inheritDoc */
	public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params
	) {
		$ref = $this->getLocalReference( [ 'src' => $params['src'] ] );
		if ( $ref === false ) {
			return $this->newStatus( 'backend-fail-notexists', $params['src'] );
		} elseif ( $ref === null ) {
			return $this->newStatus( 'backend-fail-read', $params['src'] );
		} else {
			$file = $command->newInputFileFromFile( $ref->getPath() )
				->userData( __CLASS__, $ref );
			$command->inputFile( $boxedName, $file );
			return $this->newStatus();
		}
	}

	/** @inheritDoc */
	final public function streamFile( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Always set some fields for subclass convenience
		$params['options'] ??= [];
		$params['headers'] ??= [];

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
	 * @stable to override
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
				$this->getStreamerOptions()
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

	/** @inheritDoc */
	final public function directoryExists( array $params ) {
		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			return self::EXISTENCE_ERROR; // invalid storage path
		}
		if ( $shard !== null ) { // confined to a single container/shard
			return $this->doDirectoryExists( $fullCont, $dir, $params );
		} else { // directory is on several shards
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );
			$res = false; // response
			foreach ( $this->getContainerSuffixes( $shortCont ) as $suffix ) {
				$exists = $this->doDirectoryExists( "{$fullCont}{$suffix}", $dir, $params );
				if ( $exists === true ) {
					$res = true;
					break; // found one!
				} elseif ( $exists === self::RES_ERROR ) {
					$res = self::EXISTENCE_ERROR;
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

	/** @inheritDoc */
	final public function getDirectoryList( array $params ) {
		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			return self::EXISTENCE_ERROR; // invalid storage path
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getDirectoryListInternal( $fullCont, $dir, $params );
		} else {
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			// File listing spans multiple containers/shards
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );

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
	 * @return Traversable|array|null Iterable list or null (error)
	 */
	abstract public function getDirectoryListInternal( $container, $dir, array $params );

	/** @inheritDoc */
	final public function getFileList( array $params ) {
		[ $fullCont, $dir, $shard ] = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			return self::LIST_ERROR; // invalid storage path
		}
		if ( $shard !== null ) {
			// File listing is confined to a single container/shard
			return $this->getFileListInternal( $fullCont, $dir, $params );
		} else {
			$this->logger->debug( __METHOD__ . ": iterating over all container shards." );
			// File listing spans multiple containers/shards
			[ , $shortCont, ] = self::splitStoragePath( $params['dir'] );

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
	 * @return Traversable|string[]|null Iterable list or null (error)
	 */
	abstract public function getFileListInternal( $container, $dir, array $params );

	/**
	 * Return a list of FileOp objects from a list of operations.
	 * Do not call this function from places outside FileBackend.
	 *
	 * The result must have the same number of items as the input.
	 * An exception is thrown if an unsupported operation is requested.
	 *
	 * @param array[] $ops Same format as doOperations()
	 * @return FileOp[]
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
	 * @param FileOp[] $performOps List of FileOp objects
	 * @return string[][] (LockManager::LOCK_UW => path list, LockManager::LOCK_EX => path list)
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

	/** @inheritDoc */
	public function getScopedLocksForOps( array $ops, StatusValue $status ) {
		$paths = $this->getPathsToLockForOpsInternal( $this->getOperationsInternal( $ops ) );

		return $this->getScopedFileLocks( $paths, 'mixed', $status );
	}

	/** @inheritDoc */
	final protected function doOperationsInternal( array $ops, array $opts ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Fix up custom header name/value pairs
		$ops = array_map( $this->sanitizeOpHeaders( ... ), $ops );
		// Build up a list of FileOps and involved paths
		$fileOps = $this->getOperationsInternal( $ops );
		$pathsUsed = [];
		foreach ( $fileOps as $fileOp ) {
			$pathsUsed = array_merge( $pathsUsed, $fileOp->storagePathsReadOrChanged() );
		}

		// Acquire any locks as needed for the scope of this function
		if ( empty( $opts['nonLocking'] ) ) {
			$pathsByLockType = $this->getPathsToLockForOpsInternal( $fileOps );
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scopeLock = $this->getScopedFileLocks( $pathsByLockType, 'mixed', $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}

		// Clear any file cache entries (after locks acquired)
		if ( empty( $opts['preserveCache'] ) ) {
			$this->clearCache( $pathsUsed );
		}

		// Enlarge the cache to fit the stat entries of these files
		$this->cheapCache->setMaxSize( max( 2 * count( $pathsUsed ), self::CACHE_CHEAP_SIZE ) );

		// Load from the persistent container caches
		$this->primeContainerCache( $pathsUsed );
		// Get the latest stat info for all the files (having locked them)
		$ok = $this->preloadFileStat( [ 'srcs' => $pathsUsed, 'latest' => true ] );

		if ( $ok ) {
			// Actually attempt the operation batch...
			$opts = $this->setConcurrencyFlags( $opts );
			$subStatus = FileOpBatch::attempt( $fileOps, $opts );
		} else {
			// If we could not even stat some files, then bail out
			$subStatus = $this->newStatus( 'backend-fail-internal', $this->name );
			foreach ( $ops as $i => $op ) { // mark each op as failed
				$subStatus->success[$i] = false;
				++$subStatus->failCount;
			}
			$this->logger->error( static::class . "-{$this->name} stat failure",
				[ 'aborted_operations' => $ops ]
			);
		}

		// Merge errors into StatusValue fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

		// Shrink the stat cache back to normal size
		$this->cheapCache->setMaxSize( self::CACHE_CHEAP_SIZE );

		return $status;
	}

	/** @inheritDoc */
	final protected function doQuickOperationsInternal( array $ops, array $opts ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$status = $this->newStatus();

		// Fix up custom header name/value pairs
		$ops = array_map( $this->sanitizeOpHeaders( ... ), $ops );
		// Build up a list of FileOps and involved paths
		$fileOps = $this->getOperationsInternal( $ops );
		$pathsUsed = [];
		foreach ( $fileOps as $fileOp ) {
			$pathsUsed = array_merge( $pathsUsed, $fileOp->storagePathsReadOrChanged() );
		}

		// Clear any file cache entries for involved paths
		$this->clearCache( $pathsUsed );

		// Parallel ops may be disabled in config due to dependencies (e.g. needing popen())
		$async = ( $this->parallelize === 'implicit' && count( $ops ) > 1 );
		$maxConcurrency = $this->concurrency; // throttle
		/** @var StatusValue[] $statuses */
		$statuses = []; // array of (index => StatusValue)
		/** @var FileBackendStoreOpHandle[] $batch */
		$batch = [];
		foreach ( $fileOps as $index => $fileOp ) {
			$subStatus = $async
				? $fileOp->attemptAsyncQuick()
				: $fileOp->attemptQuick();
			if ( $subStatus->value instanceof FileBackendStoreOpHandle ) { // async
				if ( count( $batch ) >= $maxConcurrency ) {
					// Execute this batch. Don't queue any more ops since they contain
					// open filehandles which are a limited resource (T230245).
					$statuses += $this->executeOpHandlesInternal( $batch );
					$batch = [];
				}
				$batch[$index] = $subStatus->value; // keep index
			} else { // error or completed
				$statuses[$index] = $subStatus; // keep index
			}
		}
		if ( count( $batch ) ) {
			$statuses += $this->executeOpHandlesInternal( $batch );
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

		$this->clearCache( $pathsUsed );

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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		foreach ( $fileOpHandles as $fileOpHandle ) {
			if ( !( $fileOpHandle instanceof FileBackendStoreOpHandle ) ) {
				throw new InvalidArgumentException( "Expected FileBackendStoreOpHandle object." );
			} elseif ( $fileOpHandle->backend->getName() !== $this->getName() ) {
				throw new InvalidArgumentException( "Expected handle for this file backend." );
			}
		}

		$statuses = $this->doExecuteOpHandlesInternal( $fileOpHandles );
		foreach ( $fileOpHandles as $fileOpHandle ) {
			$fileOpHandle->closeResources();
		}

		return $statuses;
	}

	/**
	 * @see FileBackendStore::executeOpHandlesInternal()
	 * @stable to override
	 *
	 * @param FileBackendStoreOpHandle[] $fileOpHandles
	 *
	 * @throws FileBackendError
	 * @return StatusValue[] List of corresponding StatusValue objects
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		if ( count( $fileOpHandles ) ) {
			throw new FileBackendError( "Backend does not support asynchronous operations." );
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
					$this->logger->error( "Header '{header}' is too long.", [
						'filebackend' => $this->name,
						'header' => "$name: $value",
					] );
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
			[ $fullCont, , ] = $this->resolveStoragePath( $path );
			$fullConts[] = $fullCont;
		}
		// Load from the persistent file and container caches
		$this->primeContainerCache( $fullConts );
		$this->primeFileCache( $paths );
	}

	final public function clearCache( ?array $paths = null ) {
		if ( is_array( $paths ) ) {
			$paths = array_map( [ FileBackend::class, 'normalizeStoragePath' ], $paths );
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
	 * @stable to override
	 *
	 * @see FileBackend::clearCache()
	 *
	 * @param string[]|null $paths Storage paths (optional)
	 */
	protected function doClearCache( ?array $paths = null ) {
	}

	/** @inheritDoc */
	final public function preloadFileStat( array $params ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$params['concurrency'] = ( $this->parallelize !== 'off' ) ? $this->concurrency : 1;
		$stats = $this->doGetFileStatMulti( $params );
		if ( $stats === null ) {
			return true; // not supported
		}

		// Whether this queried the backend in high consistency mode
		$latest = !empty( $params['latest'] );

		return $this->ingestFreshFileStats( $stats, $latest );
	}

	/**
	 * Get file stat information (concurrently if possible) for several files
	 * @stable to override
	 *
	 * @see FileBackend::getFileStat()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 * @return array<string,array|false|null>|null Null if not supported. Otherwise a map of storage
	 *  path to attribute map, false (missing file), or null (I/O error).
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
		// might be used by subclasses. Reserve the dot character.
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
		[ $backend, $shortCont, $relPath ] = self::splitStoragePath( $storagePath );
		if ( $backend === $this->name && $relPath !== null ) { // must be for this backend
			$relPath = self::normalizeContainerPath( $relPath );
			if ( $relPath !== null && self::isValidShortContainerName( $shortCont ) ) {
				// Get shard for the normalized path if this container is sharded
				$cShard = $this->getContainerShard( $shortCont, $relPath );
				// Validate and sanitize the relative path (backend-specific)
				$relPath = $this->resolveContainerPath( $shortCont, $relPath );
				if ( $relPath !== null ) {
					// Prepend any domain ID prefix to the container name
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
		[ $container, $relPath, $cShard ] = $this->resolveStoragePath( $storagePath );
		if ( $cShard !== null && !str_ends_with( $relPath, '/' ) ) {
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
		[ $levels, $base, $repeat ] = $this->getContainerHashLevels( $container );
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
	 * @param string $storagePath
	 * @return bool
	 */
	final public function isSingleShardPathInternal( $storagePath ) {
		[ , , $shard ] = $this->resolveStoragePath( $storagePath );

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
		[ $digits, $base ] = $this->getContainerHashLevels( $container );
		if ( $digits > 0 ) {
			$numShards = $base ** $digits;
			for ( $index = 0; $index < $numShards; $index++ ) {
				$shards[] = '.' . \Wikimedia\base_convert( (string)$index, 10, $base, $digits );
			}
		}

		return $shards;
	}

	/**
	 * Get the full container name, including the domain ID prefix
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
	 * @stable to override
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
	 * @stable to override
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
		if ( !$this->memCache->set( $this->containerCacheKey( $container ), $val, 14 * 86400 ) ) {
			$this->logger->warning( "Unable to set stat cache for container {container}.",
				[ 'filebackend' => $this->name, 'container' => $container ]
			);
		}
	}

	/**
	 * Delete the cached info for a container.
	 * The cache key is salted for a while to prevent race conditions.
	 *
	 * @param string $container Resolved container name
	 */
	final protected function deleteContainerCache( $container ) {
		if ( !$this->memCache->delete( $this->containerCacheKey( $container ), 300 ) ) {
			$this->logger->warning( "Unable to delete stat cache for container {container}.",
				[ 'filebackend' => $this->name, 'container' => $container ]
			);
		}
	}

	/**
	 * Do a batch lookup from cache for container stats for all containers
	 * used in a list of container names or storage paths objects.
	 * This loads the persistent cache values into the process cache.
	 */
	final protected function primeContainerCache( array $items ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
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
			[ $fullCont, , ] = $this->resolveStoragePath( $path );
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
	 * @stable to override
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
		$mtime = (int)ConvertibleTimestamp::convert( TS_UNIX, $val['mtime'] );
		$ttl = $this->memCache->adaptiveTTL( $mtime, 7 * 86400, 300, 0.1 );
		$key = $this->fileCacheKey( $path );
		// Set the cache unless it is currently salted.
		if ( !$this->memCache->set( $key, $val, $ttl ) ) {
			$this->logger->warning( "Unable to set stat cache for file {path}.",
				[ 'filebackend' => $this->name, 'path' => $path ]
			);
		}
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
			$this->logger->warning( "Unable to delete stat cache for file {path}.",
				[ 'filebackend' => $this->name, 'path' => $path ]
			);
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
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$paths = []; // list of storage paths
		$pathNames = []; // (cache key => storage path)
		// Get all the paths/containers from the items...
		foreach ( $items as $item ) {
			if ( self::isStoragePath( $item ) ) {
				$path = FileBackend::normalizeStoragePath( $item );
				if ( $path !== null ) {
					$paths[] = $path;
				}
			}
		}
		// Get all the corresponding cache keys for paths...
		foreach ( $paths as $path ) {
			[ , $rel, ] = $this->resolveStoragePath( $path );
			if ( $rel !== null ) { // valid path for this backend
				$pathNames[$this->fileCacheKey( $path )] = $path;
			}
		}
		// Get all cache entries for these file cache keys.
		// Note that negatives are not cached by getFileStat()/preloadFileStat().
		$values = $this->memCache->getMulti( array_keys( $pathNames ) );
		// Load all of the results into process cache...
		foreach ( array_filter( $values, 'is_array' ) as $cacheKey => $stat ) {
			$path = $pathNames[$cacheKey];
			// This flag only applies to stat info loaded directly
			// from a high consistency backend query to the process cache
			unset( $stat['latest'] );

			$this->cheapCache->setField( $path, 'stat', $stat );
			if ( isset( $stat['sha1'] ) && strlen( $stat['sha1'] ) == 31 ) {
				// Some backends store SHA-1 as metadata
				$this->cheapCache->setField(
					$path,
					'sha1',
					[ 'hash' => $stat['sha1'], 'latest' => false ]
				);
			}
			if ( isset( $stat['xattr'] ) && is_array( $stat['xattr'] ) ) {
				// Some backends store custom headers/metadata
				$stat['xattr'] = self::normalizeXAttributes( $stat['xattr'] );
				$this->cheapCache->setField(
					$path,
					'xattr',
					[ 'map' => $stat['xattr'], 'latest' => false ]
				);
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
			if ( $opts['parallelize'] ?? true ) {
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
	 * @stable to override
	 *
	 * @param string $storagePath
	 * @param string|null $content File data
	 * @param string|null $fsPath File system path
	 * @return string MIME type
	 */
	protected function getContentType( $storagePath, $content, $fsPath ) {
		if ( $this->mimeCallback ) {
			return ( $this->mimeCallback )( $storagePath, $content, $fsPath );
		}

		$mime = ( $fsPath !== null ) ? mime_content_type( $fsPath ) : false;
		return $mime ?: 'unknown/unknown';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileBackendStore::class, 'FileBackendStore' );
