<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Base class for all file backend classes (including multi-write backends).
 * This class defines the methods as abstract that subclasses must implement.
 * Outside callers can assume that all backends will have these functions.
 * 
 * All "storage paths" are of the format "mwstore://backend/container/path".
 * The paths use typical file system (FS) notation, though any particular backend may
 * not actually be using a local filesystem. Therefore, the paths are only virtual.
 * 
 * FS-based backends are somewhat more restrictive due to the existence of real
 * directory files; a regular file cannot have the same name as a directory. Other
 * backends with virtual directories may not have this limitation.
 * 
 * Methods should avoid throwing exceptions at all costs.
 * As a corollary, external dependencies should be kept to a minimum.
 * 
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackendBase {
	protected $name; // unique backend name
	protected $wikiId; // unique wiki name
	/** @var LockManager */
	protected $lockManager;

	/**
	 * Build a new object from configuration.
	 * This should only be called from within FileBackendGroup.
	 * 
	 * $config includes:
	 *     'name'        : The name of this backend
	 *     'wikiId'      : Prefix to container names that is unique to this wiki
	 *     'lockManager' : Registered name of the file lock manager to use
	 * 
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		$this->name = $config['name'];
		$this->wikiId = isset( $config['wikiId'] )
			? $config['wikiId']
			: wfWikiID();
		$this->lockManager = LockManagerGroup::singleton()->get( $config['lockManager'] );
	}

	/**
	 * Get the unique backend name.
	 * 
	 * We may have multiple different backends of the same type.
	 * For example, we can have two Swift backends using different proxies.
	 * 
	 * @return string
	 */
	final public function getName() {
		return $this->name;
	}

	/**
	 * This is the main entry point into the backend for write operations.
	 * Callers supply an ordered list of operations to perform as a transaction.
	 * If any serious errors occur, all attempted operations will be rolled back.
	 * 
	 * $ops is an array of arrays. The outer array holds a list of operations.
	 * Each inner array is a set of key value pairs that specify an operation.
	 * 
	 * Supported operations and their parameters:
	 * a) Create a new file in storage with the contents of a string
	 *     array(
	 *         'op'                  => 'create',
	 *         'dst'                 => <storage path>,
	 *         'content'             => <string of new file contents>,
	 *         'overwriteDest'       => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * b) Copy a file system file into storage
	 *     array(
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path>,
	 *         'dst'                 => <storage path>,
	 *         'overwriteDest'       => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * c) Copy a file within storage
	 *     array(
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwriteDest'       => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * d) Move a file within storage
	 *     array(
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwriteDest'       => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * e) Delete a file within storage
	 *     array(
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     )
	 * f) Concatenate a list of files into a single file within storage
	 *     array(
	 *         'op'                  => 'concatenate',
	 *         'srcs'                => <ordered array of storage paths>,
	 *         'dst'                 => <storage path>,
	 *         'overwriteDest'       => <boolean>
	 *     )
	 * g) Do nothing (no-op)
	 *     array(
	 *         'op'                  => 'null',
	 *     )
	 * 
	 * Boolean flags for operations (operation-specific):
	 * 'ignoreMissingSource' : The operation will simply succeed and do
	 *                         nothing if the source file does not exist.
	 * 'overwriteDest'       : Any destination file will be overwritten.
	 * 'overwriteSame'       : An error will not be given if a file already
	 *                         exists at the destination that has the same
	 *                         contents as the new contents to be written there.
	 * 
	 * $opts is an associative of options, including:
	 * 'nonLocking'          : No locks are acquired for the operations.
	 *                         This can increase performance for non-critical writes.
	 * 'ignoreErrors'        : Serious errors that would normally cause a rollback
	 *                         do not. The remaining operations are still attempted.
	 * 
	 * Return value:
	 * This returns a Status, which contains all warnings and fatals that occured
	 * during the operation. The 'failCount', 'successCount', and 'success' members
	 * will reflect each operation attempted. The status will be "OK" unless any
	 * of the operations failed and the 'ignoreErrors' parameter was not set.
	 * 
	 * @param $ops Array List of operations to execute in order
	 * @param $opts Array Batch operation options
	 * @return Status
	 */
	abstract public function doOperations( array $ops, array $opts = array() );

	/**
	 * Same as doOperations() except it takes a single operation.
	 * If you are doing a batch of operations that should either
	 * all succeed or all fail, then use that function instead.
	 *
	 * @see FileBackendBase::doOperations()
	 *
	 * @param $op Array Operation
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function doOperation( array $op, array $opts = array() ) {
		return $this->doOperations( array( $op ), $opts );
	}

	/**
	 * Performs a single store operation.
	 * This sets $params['op'] to 'store' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function store( array $params, array $opts = array() ) {
		$params['op'] = 'store';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Performs a single copy operation.
	 * This sets $params['op'] to 'copy' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function copy( array $params, array $opts = array() ) {
		$params['op'] = 'copy';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Performs a single move operation.
	 * This sets $params['op'] to 'move' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function move( array $params, array $opts = array() ) {
		$params['op'] = 'move';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Performs a single delete operation.
	 * This sets $params['op'] to 'delete' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function delete( array $params, array $opts = array() ) {
		$params['op'] = 'delete';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Performs a single create operation.
	 * This sets $params['op'] to 'create' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function create( array $params, array $opts = array() ) {
		$params['op'] = 'create';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Performs a single concatenate operation.
	 * This sets $params['op'] to 'concatenate' and passes it to doOperation().
	 *
	 * @see FileBackendBase::doOperation()
	 *
	 * @param $params Array Operation parameters
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function concatenate( array $params, array $opts = array() ) {
		$params['op'] = 'concatenate';
		return $this->doOperation( $params, $opts );
	}

	/**
	 * Prepare a storage path for usage. This will create containers
	 * that don't yet exist or, on FS backends, create parent directories.
	 * 
	 * $params include:
	 *     dir : storage directory
	 * 
	 * @param $params Array
	 * @return Status
	 */
	abstract public function prepare( array $params );

	/**
	 * Take measures to block web access to a directory and
	 * the container it belongs to. FS backends might add .htaccess
	 * files wheras backends like Swift this might restrict container
	 * access to backend user that represents end-users in web request.
	 * This is not guaranteed to actually do anything.
	 * 
	 * $params include:
	 *     dir       : storage directory
	 *     noAccess  : try to deny file access
	 *     noListing : try to deny file listing
	 * 
	 * @param $params Array
	 * @return Status
	 */
	abstract public function secure( array $params );

	/**
	 * Clean up an empty storage directory.
	 * On FS backends, the directory will be deleted. Others may do nothing.
	 * 
	 * $params include:
	 *     dir : storage directory
	 * 
	 * @param $params Array
	 * @return Status
	 */
	abstract public function clean( array $params );

	/**
	 * Check if a file exists at a storage path in the backend.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return bool
	 */
	abstract public function fileExists( array $params );

	/**
	 * Get a SHA-1 hash of the file at a storage path in the backend.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return string|false Hash string or false on failure
	 */
	abstract public function getFileSha1Base36( array $params );

	/**
	 * Get the last-modified timestamp of the file at a storage path.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return string|false TS_MW timestamp or false on failure
	 */
	abstract public function getFileTimestamp( array $params );

	/**
	 * Get the properties of the file at a storage path in the backend.
	 * Returns FSFile::placeholderProps() on failure.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return Array
	 */
	abstract public function getFileProps( array $params );

	/**
	 * Stream the file at a storage path in the backend.
	 * Appropriate HTTP headers (Status, Content-Type, Content-Length)
	 * must be sent if streaming began, while none should be sent otherwise.
	 * Implementations should flush the output buffer before sending data.
	 * 
	 * $params include:
	 *     src     : source storage path
	 *     headers : additional HTTP headers to send on success
	 * 
	 * @param $params Array
	 * @return Status
	 */
	abstract public function streamFile( array $params );

	/**
	 * Get an iterator to list out all object files under a storage directory.
	 * If the directory is of the form "mwstore://container", then all items in
	 * the container should be listed. If of the form "mwstore://container/dir",
	 * then all items under that container directory should be listed.
	 * Results should be storage paths relative to the given directory.
	 * 
	 * $params include:
	 *     dir : storage path directory
	 *
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract public function getFileList( array $params );

	/**
	 * Returns a file system file, identical to the file at a storage path.
	 * The file returned is either:
	 * a) A local copy of the file at a storage path in the backend.
	 *    The temporary copy will have the same extension as the source.
	 * b) An original of the file at a storage path in the backend.
	 * Temporary files may be purged when the file object falls out of scope.
	 * 
	 * Write operations should *never* be done on this file as some backends
	 * may do internal tracking or may be instances of FileBackendMultiWrite.
	 * In that later case, there are copies of the file that must stay in sync.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return FSFile|null Returns null on failure
	 */
	abstract public function getLocalReference( array $params );

	/**
	 * Get a local copy on disk of the file at a storage path in the backend.
	 * The temporary copy will have the same file extension as the source.
	 * Temporary files may be purged when the file object falls out of scope.
	 * 
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return TempFSFile|null Returns null on failure
	 */
	abstract public function getLocalCopy( array $params );

	/**
	 * Lock the files at the given storage paths in the backend.
	 * This will either lock all the files or none (on failure).
	 * 
	 * Callers should consider using getScopedFileLocks() instead.
	 * 
	 * @param $paths Array Storage paths
	 * @param $type integer LockManager::LOCK_EX, LockManager::LOCK_SH
	 * @return Status
	 */
	final public function lockFiles( array $paths, $type ) {
		return $this->lockManager->lock( $paths, $type );
	}

	/**
	 * Unlock the files at the given storage paths in the backend.
	 * 
	 * @param $paths Array Storage paths
	 * @param $type integer LockManager::LOCK_EX, LockManager::LOCK_SH
	 * @return Status
	 */
	final public function unlockFiles( array $paths, $type ) {
		return $this->lockManager->unlock( $paths, $type );
	}

	/**
	 * Lock the files at the given storage paths in the backend.
	 * This will either lock all the files or none (on failure).
	 * On failure, the status object will be updated with errors.
	 * 
	 * Once the return value goes out scope, the locks will be released and
	 * the status updated. Unlock fatals will not change the status "OK" value.
	 * 
	 * @param $paths Array Storage paths
	 * @param $type integer LockManager::LOCK_EX, LockManager::LOCK_SH
	 * @param $status Status Status to update on lock/unlock
	 * @return ScopedLock|null Returns null on failure
	 */
	final public function getScopedFileLocks( array $paths, $type, Status $status ) {
		return ScopedLock::factory( $this->lockManager, $paths, $type, $status );
	}
}

/**
 * Base class for all single-write backends.
 * This class defines the methods as abstract that subclasses must implement.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackend extends FileBackendBase {
	/** @var Array */
	protected $cache = array(); // (storage path => key => value)
	protected $maxCacheSize = 50; // integer; max paths with entries

	/**
	 * Store a file into the backend from a file on disk.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     src           : source path on disk
	 *     dst           : destination storage path
	 *     overwriteDest : do nothing and pass if an identical file exists at destination
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function storeInternal( array $params ) {
		$status = $this->doStoreInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::storeInternal()
	 */
	abstract protected function doStoreInternal( array $params );

	/**
	 * Copy a file from one storage path to another in the backend.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     src           : source storage path
	 *     dst           : destination storage path
	 *     overwriteDest : do nothing and pass if an identical file exists at destination
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function copyInternal( array $params ) {
		$status = $this->doCopyInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::copyInternal()
	 */
	abstract protected function doCopyInternal( array $params );

	/**
	 * Delete a file at the storage path.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     src : source storage path
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function deleteInternal( array $params ) {
		$status = $this->doDeleteInternal( $params );
		$this->clearCache( array( $params['src'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::delete()
	 */
	abstract protected function doDeleteInternal( array $params );

	/**
	 * Move a file from one storage path to another in the backend.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     src           : source storage path
	 *     dst           : destination storage path
	 *     overwriteDest : do nothing and pass if an identical file exists at destination
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function moveInternal( array $params ) {
		$status = $this->doMoveInternal( $params );
		$this->clearCache( array( $params['src'], $params['dst'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::move()
	 */
	protected function doMoveInternal( array $params ) {
		// Copy source to dest
		$status = $this->backend->copyInternal( $params );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Delete source (only fails due to races or medium going down)
		$status->merge( $this->backend->deleteInternal( array( 'src' => $params['src'] ) ) );
		$status->setResult( true, $status->value ); // ignore delete() errors
		return $status;
	}

	/**
	 * Combines files from several storage paths into a new file in the backend.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     srcs          : ordered source storage paths (e.g. chunk1, chunk2, ...)
	 *     dst           : destination storage path
	 *     overwriteDest : do nothing and pass if an identical file exists at destination
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function concatenateInternal( array $params ) {
		$status = $this->doConcatenateInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::concatenate()
	 */
	abstract protected function doConcatenateInternal( array $params );

	/**
	 * Create a file in the backend with the given contents.
	 * Do not call this function from places outside FileBackend and FileOp.
	 * $params include:
	 *     content       : the raw file contents
	 *     dst           : destination storage path
	 *     overwriteDest : do nothing and pass if an identical file exists at destination
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function createInternal( array $params ) {
		$status = $this->doCreateInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
		return $status;
	}

	/**
	 * @see FileBackend::create()
	 */
	abstract protected function doCreateInternal( array $params );

	/**
	 * @see FileBackendBase::prepare()
	 */
	public function prepare( array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackendBase::secure()
	 */
	public function secure( array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackendBase::clean()
	 */
	public function clean( array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackendBase::getFileSha1Base36()
	 */
	public function getFileSha1Base36( array $params ) {
		$path = $params['src'];
		if ( isset( $this->cache[$path]['sha1'] ) ) {
			return $this->cache[$path]['sha1'];
		}
		$fsFile = $this->getLocalReference( array( 'src' => $path ) );
		if ( !$fsFile ) {
			return false;
		} else {
			$sha1 = $fsFile->getSha1Base36();
			if ( $sha1 !== false ) { // don't cache negatives
				$this->trimCache(); // limit memory
				$this->cache[$path]['sha1'] = $sha1;
			}
			return $sha1;
		}
	}

	/**
	 * @see FileBackendBase::getFileProps()
	 */
	public function getFileProps( array $params ) {
		$fsFile = $this->getLocalReference( array( 'src' => $params['src'] ) );
		if ( !$fsFile ) {
			return FSFile::placeholderProps();
		} else {
			return $fsFile->getProps();
		}
	}

	/**
	 * @see FileBackendBase::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		return $this->getLocalCopy( $params );
	}

	/**
	 * @see FileBackendBase::streamFile()
	 */
	function streamFile( array $params ) {
		$status = Status::newGood();

		$fsFile = $this->getLocalReference( array( 'src' => $params['src'] ) );
		if ( !$fsFile ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		}

		$extraHeaders = isset( $params['headers'] )
			? $params['headers']
			: array();

		$ok = StreamFile::stream( $fsFile->getPath(), $extraHeaders, false );
		if ( !$ok ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		}

		return $status;
	}

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
			'concatenate' => 'ConcatenateFileOp',
			'create'      => 'CreateFileOp',
			'null'        => 'NullFileOp'
		);
	}

	/**
	 * Return a list of FileOp objects from a list of operations.
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
	 * @see FileBackendBase::doOperations()
	 */
	final public function doOperations( array $ops, array $opts = array() ) {
		$status = Status::newGood();

		// Build up a list of FileOps...
		$performOps = $this->getOperations( $ops );

		if ( empty( $opts['nonLocking'] ) ) {
			// Build up a list of files to lock...
			$filesLockEx = $filesLockSh = array();
			foreach ( $performOps as $index => $fileOp ) {
				$filesLockSh = array_merge( $filesLockSh, $fileOp->storagePathsRead() );
				$filesLockEx = array_merge( $filesLockEx, $fileOp->storagePathsChanged() );
			}
			// Try to lock those files for the scope of this function...
			$scopeLockS = $this->getScopedFileLocks( $filesLockSh, LockManager::LOCK_UW, $status );
			$scopeLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}

		// Clear any cache entries (after locks acquired)
		$this->clearCache();
		// Actually attempt the operation batch...
		$status->merge( FileOp::attemptBatch( $performOps, $opts ) );

		return $status;
	}

	/**
	 * Invalidate the file existence and property cache
	 *
	 * @param $paths Array Clear cache for specific files
	 * @return void
	 */
	final public function clearCache( array $paths = null ) {
		if ( $paths === null ) {
			$this->cache = array();
		} else {
			foreach ( $paths as $path ) {
				unset( $this->cache[$path] );
			}
		}
	}

	/**
	 * Prune the cache if it is too big to add an item
	 * 
	 * @return void
	 */
	protected function trimCache() {
		if ( count( $this->cache ) >= $this->maxCacheSize ) {
			reset( $this->cache );
			$key = key( $this->cache );
			unset( $this->cache[$key] );
		}
	}

	/**
	 * Check if a given path is a mwstore:// path.
	 * This does not do any actual validation or existence checks.
	 * 
	 * @param $path string
	 * @return bool
	 */
	final public static function isStoragePath( $path ) {
		return ( strpos( $path, 'mwstore://' ) === 0 );
	}

	/**
	 * Split a storage path (e.g. "mwstore://backend/container/path/to/object")
	 * into a backend name, a container name, and a relative object path.
	 *
	 * @param $storagePath string
	 * @return Array (backend, container, rel object) or (null, null, null)
	 */
	final public static function splitStoragePath( $storagePath ) {
		if ( self::isStoragePath( $storagePath ) ) {
			// Note: strlen( 'mwstore://' ) = 10
			$parts = explode( '/', substr( $storagePath, 10 ), 3 );
			if ( count( $parts ) == 3 ) {
				return $parts; // e.g. "backend/container/path"
			} elseif ( count( $parts ) == 2 ) {
				return array( $parts[0], $parts[1], '' ); // e.g. "backend/container" 
			}
		}
		return array( null, null, null );
	}

	/**
	 * Validate a container name.
	 * Null is returned if the name has illegal characters.
	 * 
	 * @param $container string
	 * @return bool 
	 */
	final protected static function isValidContainerName( $container ) {
		// This accounts for Swift and S3 restrictions. Also note
		// that these urlencode to the same string, which is useful
		// since the Swift size limit is *after* URL encoding.
		return preg_match( '/^[a-zA-Z0-9._-]{1,256}$/u', $container );
	}

	/**
	 * Validate and normalize a relative storage path.
	 * Null is returned if the path involves directory traversal.
	 * Traversal is insecure for FS backends and broken for others.
	 *
	 * @param $path string
	 * @return string|null
	 */
	final protected static function normalizeStoragePath( $path ) {
		// Normalize directory separators
		$path = strtr( $path, '\\', '/' );
		// Use the same traversal protection as Title::secureAndSplit()
		if ( strpos( $path, '.' ) !== false ) {
			if (
				$path === '.' ||
				$path === '..' ||
				strpos( $path, './' ) === 0 ||
				strpos( $path, '../' ) === 0 ||
				strpos( $path, '/./' ) !== false ||
				strpos( $path, '/../' ) !== false
			) { 
				return null;
			}
		}
		return $path;
	}

	/**
	 * Split a storage path (e.g. "mwstore://backend/container/path/to/object")
	 * into an internal container name and an internal relative object name.
	 * This also checks that the storage path is valid and is within this backend.
	 *
	 * @param $storagePath string
	 * @return Array (container, object name) or (null, null) if path is invalid
	 */
	final protected function resolveStoragePath( $storagePath ) {
		list( $backend, $container, $relPath ) = self::splitStoragePath( $storagePath );
		if ( $backend === $this->name ) { // must be for this backend
			$relPath = self::normalizeStoragePath( $relPath );
			if ( $relPath !== null ) {
				$relPath = $this->resolveContainerPath( $container, $relPath );
				if ( $relPath !== null ) {
					$container = $this->fullContainerName( $container );
					if ( self::isValidContainerName( $container ) ) {
						$container = $this->resolveContainerName( $container );
						if ( $container !== null ) {
							return array( $container, $relPath );
						}
					}
				}
			}
		}
		return array( null, null );
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
	 * This is intended for internal use, such as encoding illegal chars
	 * or perhaps getting absolute paths (e.g. FS based backends).
	 *
	 * @param $container string Container the path is relative to
	 * @param $relStoragePath string Relative storage path
	 * @return string|null Path or null if not valid
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		return $relStoragePath;
	}
}
