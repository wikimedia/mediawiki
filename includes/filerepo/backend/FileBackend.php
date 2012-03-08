<?php
/**
 * @defgroup FileBackend File backend
 * @ingroup  FileRepo
 *
 * This module regroup classes meant for MediaWiki to interacts with
 */

/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Base class for all file backend classes (including multi-write backends).
 *
 * This class defines the methods as abstract that subclasses must implement.
 * Outside callers can assume that all backends will have these functions.
 * 
 * All "storage paths" are of the format "mwstore://backend/container/path".
 * The paths use UNIX file system (FS) notation, though any particular backend may
 * not actually be using a local filesystem. Therefore, the paths are only virtual.
 * 
 * Backend contents are stored under wiki-specific container names by default.
 * For legacy reasons, this has no effect for the FS backend class, and per-wiki
 * segregation must be done by setting the container paths appropriately.
 * 
 * FS-based backends are somewhat more restrictive due to the existence of real
 * directory files; a regular file cannot have the same name as a directory. Other
 * backends with virtual directories may not have this limitation. Callers should
 * store files in such a way that no files and directories are under the same path.
 * 
 * Methods should avoid throwing exceptions at all costs.
 * As a corollary, external dependencies should be kept to a minimum.
 * 
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackend {
	protected $name; // string; unique backend name
	protected $wikiId; // string; unique wiki name
	protected $readOnly; // string; read-only explanation message
	/** @var LockManager */
	protected $lockManager;

	/**
	 * Create a new backend instance from configuration.
	 * This should only be called from within FileBackendGroup.
	 * 
	 * $config includes:
	 *     'name'        : The unique name of this backend.
	 *                     This should consist of alphanumberic, '-', and '_' characters.
	 *                     This name should not be changed after use.
	 *     'wikiId'      : Prefix to container names that is unique to this wiki.
	 *                     This should consist of alphanumberic, '-', and '_' characters.
	 *     'lockManager' : Registered name of a file lock manager to use.
	 *     'readOnly'    : Write operations are disallowed if this is a non-empty string.
	 *                     It should be an explanation for the backend being read-only.
	 * 
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		$this->name = $config['name'];
		if ( !preg_match( '!^[a-zA-Z0-9-_]{1,255}$!', $this->name ) ) {
			throw new MWException( "Backend name `{$this->name}` is invalid." );
		}
		$this->wikiId = isset( $config['wikiId'] )
			? $config['wikiId']
			: wfWikiID(); // e.g. "my_wiki-en_"
		$this->lockManager = ( $config['lockManager'] instanceof LockManager )
			? $config['lockManager']
			: LockManagerGroup::singleton()->get( $config['lockManager'] );
		$this->readOnly = isset( $config['readOnly'] )
			? (string)$config['readOnly']
			: '';
	}

	/**
	 * Get the unique backend name.
	 * We may have multiple different backends of the same type.
	 * For example, we can have two Swift backends using different proxies.
	 * 
	 * @return string
	 */
	final public function getName() {
		return $this->name;
	}

	/**
	 * Check if this backend is read-only
	 * 
	 * @return bool
	 */
	final public function isReadOnly() {
		return ( $this->readOnly != '' );
	}

	/**
	 * Get an explanatory message if this backend is read-only
	 * 
	 * @return string|false Returns falls if the backend is not read-only
	 */
	final public function getReadOnlyReason() {
		return ( $this->readOnly != '' ) ? $this->readOnly : false;
	}

	/**
	 * This is the main entry point into the backend for write operations.
	 * Callers supply an ordered list of operations to perform as a transaction.
	 * Files will be locked, the stat cache cleared, and then the operations attempted.
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
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * b) Copy a file system file into storage
	 *     array(
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * c) Copy a file within storage
	 *     array(
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * d) Move a file within storage
	 *     array(
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>
	 *     )
	 * e) Delete a file within storage
	 *     array(
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     )
	 * f) Do nothing (no-op)
	 *     array(
	 *         'op'                  => 'null',
	 *     )
	 * 
	 * Boolean flags for operations (operation-specific):
	 * 'ignoreMissingSource' : The operation will simply succeed and do
	 *                         nothing if the source file does not exist.
	 * 'overwrite'           : Any destination file will be overwritten.
	 * 'overwriteSame'       : An error will not be given if a file already
	 *                         exists at the destination that has the same
	 *                         contents as the new contents to be written there.
	 * 
	 * $opts is an associative of boolean flags, including:
	 * 'force'               : Errors that would normally cause a rollback do not.
	 *                         The remaining operations are still attempted if any fail.
	 * 'nonLocking'          : No locks are acquired for the operations.
	 *                         This can increase performance for non-critical writes.
	 *                         This has no effect unless the 'force' flag is set.
	 * 'allowStale'          : Don't require the latest available data.
	 *                         This can increase performance for non-critical writes.
	 *                         This has no effect unless the 'force' flag is set.
	 * 
	 * Remarks on locking:
	 * File system paths given to operations should refer to files that are
	 * already locked or otherwise safe from modification from other processes.
	 * Normally these files will be new temp files, which should be adequate.
	 * 
	 * Return value:
	 * This returns a Status, which contains all warnings and fatals that occured
	 * during the operation. The 'failCount', 'successCount', and 'success' members
	 * will reflect each operation attempted. The status will be "OK" unless:
	 *     a) unexpected operation errors occurred (network partitions, disk full...)
	 *     b) significant operation errors occured and 'force' was not set
	 * 
	 * @param $ops Array List of operations to execute in order
	 * @param $opts Array Batch operation options
	 * @return Status
	 */
	final public function doOperations( array $ops, array $opts = array() ) {
		if ( $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		if ( empty( $opts['force'] ) ) { // sanity
			unset( $opts['nonLocking'] );
			unset( $opts['allowStale'] );
		}
		return $this->doOperationsInternal( $ops, $opts );
	}

	/**
	 * @see FileBackend::doOperations()
	 */
	abstract protected function doOperationsInternal( array $ops, array $opts );

	/**
	 * Same as doOperations() except it takes a single operation.
	 * If you are doing a batch of operations that should either
	 * all succeed or all fail, then use that function instead.
	 *
	 * @see FileBackend::doOperations()
	 *
	 * @param $op Array Operation
	 * @param $opts Array Operation options
	 * @return Status
	 */
	final public function doOperation( array $op, array $opts = array() ) {
		return $this->doOperations( array( $op ), $opts );
	}

	/**
	 * Performs a single create operation.
	 * This sets $params['op'] to 'create' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
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
	 * Performs a single store operation.
	 * This sets $params['op'] to 'store' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
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
	 * @see FileBackend::doOperation()
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
	 * @see FileBackend::doOperation()
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
	 * @see FileBackend::doOperation()
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
	 * Concatenate a list of storage files into a single file system file.
	 * The target path should refer to a file that is already locked or
	 * otherwise safe from modification from other processes. Normally,
	 * the file will be a new temp file, which should be adequate.
	 * $params include:
	 *     srcs          : ordered source storage paths (e.g. chunk1, chunk2, ...)
	 *     dst           : file system path to 0-byte temp file
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 */
	abstract public function concatenate( array $params );

	/**
	 * Prepare a storage directory for usage.
	 * This will create any required containers and parent directories.
	 * Backends using key/value stores only need to create the container.
	 * 
	 * $params include:
	 *     dir : storage directory
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function prepare( array $params ) {
		if ( $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		return $this->doPrepare( $params );
	}

	/**
	 * @see FileBackend::prepare()
	 */
	abstract protected function doPrepare( array $params );

	/**
	 * Take measures to block web access to a storage directory and
	 * the container it belongs to. FS backends might add .htaccess
	 * files whereas key/value store backends might restrict container
	 * access to the auth user that represents end-users in web request.
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
	final public function secure( array $params ) {
		if ( $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		$status = $this->doPrepare( $params ); // dir must exist to restrict it
		if ( $status->isOK() ) {
			$status->merge( $this->doSecure( $params ) );
		}
		return $status;
	}

	/**
	 * @see FileBackend::secure()
	 */
	abstract protected function doSecure( array $params );

	/**
	 * Delete a storage directory if it is empty.
	 * Backends using key/value stores may do nothing unless the directory
	 * is that of an empty container, in which case it should be deleted.
	 * 
	 * $params include:
	 *     dir : storage directory
	 * 
	 * @param $params Array
	 * @return Status
	 */
	final public function clean( array $params ) {
		if ( $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		return $this->doClean( $params );
	}

	/**
	 * @see FileBackend::clean()
	 */
	abstract protected function doClean( array $params );

	/**
	 * Check if a file exists at a storage path in the backend.
	 * This returns false if only a directory exists at the path.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return bool|null Returns null on failure
	 */
	abstract public function fileExists( array $params );

	/**
	 * Get the last-modified timestamp of the file at a storage path.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return string|false TS_MW timestamp or false on failure
	 */
	abstract public function getFileTimestamp( array $params );

	/**
	 * Get the contents of a file at a storage path in the backend.
	 * This should be avoided for potentially large files.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return string|false Returns false on failure
	 */
	abstract public function getFileContents( array $params );

	/**
	 * Get the size (bytes) of a file at a storage path in the backend.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return integer|false Returns false on failure
	 */
	abstract public function getFileSize( array $params );

	/**
	 * Get quick information about a file at a storage path in the backend.
	 * If the file does not exist, then this returns false.
	 * Otherwise, the result is an associative array that includes:
	 *     mtime  : the last-modified timestamp (TS_MW)
	 *     size   : the file size (bytes)
	 * Additional values may be included for internal use only.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return Array|false|null Returns null on failure
	 */
	abstract public function getFileStat( array $params );

	/**
	 * Get a SHA-1 hash of the file at a storage path in the backend.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return string|false Hash string or false on failure
	 */
	abstract public function getFileSha1Base36( array $params );

	/**
	 * Get the properties of the file at a storage path in the backend.
	 * Returns FSFile::placeholderProps() on failure.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return Array
	 */
	abstract public function getFileProps( array $params );

	/**
	 * Stream the file at a storage path in the backend.
	 * If the file does not exists, a 404 error will be given.
	 * Appropriate HTTP headers (Status, Content-Type, Content-Length)
	 * must be sent if streaming began, while none should be sent otherwise.
	 * Implementations should flush the output buffer before sending data.
	 * 
	 * $params include:
	 *     src     : source storage path
	 *     headers : additional HTTP headers to send on success
	 *     latest  : use the latest available data
	 * 
	 * @param $params Array
	 * @return Status
	 */
	abstract public function streamFile( array $params );

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
	 * Additionally, further calls to this function may return the same file.
	 * 
	 * $params include:
	 *     src    : source storage path
	 *     latest : use the latest available data
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
	 *     src    : source storage path
	 *     latest : use the latest available data
	 * 
	 * @param $params Array
	 * @return TempFSFile|null Returns null on failure
	 */
	abstract public function getLocalCopy( array $params );

	/**
	 * Get an iterator to list out all stored files under a storage directory.
	 * If the directory is of the form "mwstore://backend/container", 
	 * then all files in the container should be listed.
	 * If the directory is of form "mwstore://backend/container/dir",
	 * then all files under that container directory should be listed.
	 * Results should be storage paths relative to the given directory.
	 * 
	 * Storage backends with eventual consistency might return stale data.
	 * 
	 * $params include:
	 *     dir : storage path directory
	 *
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract public function getFileList( array $params );

	/**
	 * Invalidate any in-process file existence and property cache.
	 * If $paths is given, then only the cache for those files will be cleared.
	 *
	 * @param $paths Array Storage paths (optional)
	 * @return void
	 */
	public function clearCache( array $paths = null ) {}

	/**
	 * Lock the files at the given storage paths in the backend.
	 * This will either lock all the files or none (on failure).
	 * 
	 * Callers should consider using getScopedFileLocks() instead.
	 * 
	 * @param $paths Array Storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status
	 */
	final public function lockFiles( array $paths, $type ) {
		return $this->lockManager->lock( $paths, $type );
	}

	/**
	 * Unlock the files at the given storage paths in the backend.
	 * 
	 * @param $paths Array Storage paths
	 * @param $type integer LockManager::LOCK_* constant
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
	 * @param $type integer LockManager::LOCK_* constant
	 * @param $status Status Status to update on lock/unlock
	 * @return ScopedLock|null Returns null on failure
	 */
	final public function getScopedFileLocks( array $paths, $type, Status $status ) {
		return ScopedLock::factory( $this->lockManager, $paths, $type, $status );
	}

	/**
	 * Check if a given path is a "mwstore://" path.
	 * This does not do any further validation or any existence checks.
	 * 
	 * @param $path string
	 * @return bool
	 */
	final public static function isStoragePath( $path ) {
		return ( strpos( $path, 'mwstore://' ) === 0 );
	}

	/**
	 * Split a storage path into a backend name, a container name, 
	 * and a relative file path. The relative path may be the empty string.
	 * This does not do any path normalization or traversal checks.
	 *
	 * @param $storagePath string
	 * @return Array (backend, container, rel object) or (null, null, null)
	 */
	final public static function splitStoragePath( $storagePath ) {
		if ( self::isStoragePath( $storagePath ) ) {
			// Remove the "mwstore://" prefix and split the path
			$parts = explode( '/', substr( $storagePath, 10 ), 3 );
			if ( count( $parts ) >= 2 && $parts[0] != '' && $parts[1] != '' ) {
				if ( count( $parts ) == 3 ) {
					return $parts; // e.g. "backend/container/path"
				} else {
					return array( $parts[0], $parts[1], '' ); // e.g. "backend/container" 
				}
			}
		}
		return array( null, null, null );
	}

	/**
	 * Normalize a storage path by cleaning up directory separators.
	 * Returns null if the path is not of the format of a valid storage path.
	 * 
	 * @param $storagePath string
	 * @return string|null 
	 */
	final public static function normalizeStoragePath( $storagePath ) {
		list( $backend, $container, $relPath ) = self::splitStoragePath( $storagePath );
		if ( $relPath !== null ) { // must be for this backend
			$relPath = self::normalizeContainerPath( $relPath );
			if ( $relPath !== null ) {
				return ( $relPath != '' )
					? "mwstore://{$backend}/{$container}/{$relPath}"
					: "mwstore://{$backend}/{$container}";
			}
		}
		return null;
	}

	/**
	 * Validate and normalize a relative storage path.
	 * Null is returned if the path involves directory traversal.
	 * Traversal is insecure for FS backends and broken for others.
	 *
	 * @param $path string Storage path relative to a container
	 * @return string|null
	 */
	final protected static function normalizeContainerPath( $path ) {
		// Normalize directory separators
		$path = strtr( $path, '\\', '/' );
		// Collapse any consecutive directory separators
		$path = preg_replace( '![/]{2,}!', '/', $path );
		// Remove any leading directory separator
		$path = ltrim( $path, '/' );
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
	 * Get the parent storage directory of a storage path.
	 * This returns a path like "mwstore://backend/container",
	 * "mwstore://backend/container/...", or null if there is no parent.
	 * 
	 * @param $storagePath string
	 * @return string|null
	 */
	final public static function parentStoragePath( $storagePath ) {
		$storagePath = dirname( $storagePath );
		list( $b, $cont, $rel ) = self::splitStoragePath( $storagePath );
		return ( $rel === null ) ? null : $storagePath;
	}

	/**
	 * Get the final extension from a storage or FS path
	 * 
	 * @param $path string
	 * @return string
	 */
	final public static function extensionFromPath( $path ) {
		$i = strrpos( $path, '.' );
		return strtolower( $i ? substr( $path, $i + 1 ) : '' );
	}
}

/**
 * @brief Base class for all backends associated with a particular storage medium.
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

	protected $maxFileSize = 1000000000; // integer bytes (1GB)

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
		if ( strlen( $params['content'] ) > $this->maxFileSizeInternal() ) {
			$status = Status::newFatal( 'backend-fail-create', $params['dst'] );
		} else {
			$status = $this->doCreateInternal( $params );
			$this->clearCache( array( $params['dst'] ) );
		}
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
		if ( filesize( $params['src'] ) > $this->maxFileSizeInternal() ) {
			$status = Status::newFatal( 'backend-fail-store', $params['dst'] );
		} else {
			$status = $this->doStoreInternal( $params );
			$this->clearCache( array( $params['dst'] ) );
		}
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
		$status = $this->doCopyInternal( $params );
		$this->clearCache( array( $params['dst'] ) );
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
		$status = $this->doDeleteInternal( $params );
		$this->clearCache( array( $params['src'] ) );
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
		$status = $this->doMoveInternal( $params );
		$this->clearCache( array( $params['src'], $params['dst'] ) );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::moveInternal()
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
	 */
	final public function concatenate( array $params ) {
		wfProfileIn( __METHOD__ );
		$status = Status::newGood();

		// Try to lock the source files for the scope of this function
		$scopeLockS = $this->getScopedFileLocks( $params['srcs'], LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			// Actually do the concatenation
			$status->merge( $this->doConcatenate( $params ) );
		}

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::concatenate()
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
	 */
	final protected function doPrepare( array $params ) {
		wfProfileIn( __METHOD__ );

		$status = Status::newGood();
		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
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

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepare()
	 */
	protected function doPrepareInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::doSecure()
	 */
	final protected function doSecure( array $params ) {
		wfProfileIn( __METHOD__ );
		$status = Status::newGood();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
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

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doSecure()
	 */
	protected function doSecureInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::doClean()
	 */
	final protected function doClean( array $params ) {
		wfProfileIn( __METHOD__ );
		$status = Status::newGood();

		list( $fullCont, $dir, $shard ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			wfProfileOut( __METHOD__ );
			return $status; // invalid storage path
		}

		// Attempt to lock this directory...
		$filesLockEx = array( $params['dir'] );
		$scopedLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
		if ( !$status->isOK() ) {
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

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::doClean()
	 */
	protected function doCleanInternal( $container, $dir, array $params ) {
		return Status::newGood();
	}

	/**
	 * @see FileBackend::fileExists()
	 */
	final public function fileExists( array $params ) {
		wfProfileIn( __METHOD__ );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ );
		return ( $stat === null ) ? null : (bool)$stat; // null => failure
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 */
	final public function getFileTimestamp( array $params ) {
		wfProfileIn( __METHOD__ );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ );
		return $stat ? $stat['mtime'] : false;
	}

	/**
	 * @see FileBackend::getFileSize()
	 */
	final public function getFileSize( array $params ) {
		wfProfileIn( __METHOD__ );
		$stat = $this->getFileStat( $params );
		wfProfileOut( __METHOD__ );
		return $stat ? $stat['size'] : false;
	}

	/**
	 * @see FileBackend::getFileStat()
	 */
	final public function getFileStat( array $params ) {
		wfProfileIn( __METHOD__ );
		$path = self::normalizeStoragePath( $params['src'] );
		if ( $path === null ) {
			return false; // invalid storage path
		}
		$latest = !empty( $params['latest'] );
		if ( isset( $this->cache[$path]['stat'] ) ) {
			// If we want the latest data, check that this cached
			// value was in fact fetched with the latest available data.
			if ( !$latest || $this->cache[$path]['stat']['latest'] ) {
				wfProfileOut( __METHOD__ );
				return $this->cache[$path]['stat'];
			}
		}
		$stat = $this->doGetFileStat( $params );
		if ( is_array( $stat ) ) { // don't cache negatives
			$this->trimCache(); // limit memory
			$this->cache[$path]['stat'] = $stat;
			$this->cache[$path]['stat']['latest'] = $latest;
		}
		wfProfileOut( __METHOD__ );
		return $stat;
	}

	/**
	 * @see FileBackendStore::getFileStat()
	 */
	abstract protected function doGetFileStat( array $params );

	/**
	 * @see FileBackend::getFileContents()
	 */
	public function getFileContents( array $params ) {
		wfProfileIn( __METHOD__ );
		$tmpFile = $this->getLocalReference( $params );
		if ( !$tmpFile ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		wfSuppressWarnings();
		$data = file_get_contents( $tmpFile->getPath() );
		wfRestoreWarnings();
		wfProfileOut( __METHOD__ );
		return $data;
	}

	/**
	 * @see FileBackend::getFileSha1Base36()
	 */
	final public function getFileSha1Base36( array $params ) {
		wfProfileIn( __METHOD__ );
		$path = $params['src'];
		if ( isset( $this->cache[$path]['sha1'] ) ) {
			wfProfileOut( __METHOD__ );
			return $this->cache[$path]['sha1'];
		}
		$hash = $this->doGetFileSha1Base36( $params );
		if ( $hash ) { // don't cache negatives
			$this->trimCache(); // limit memory
			$this->cache[$path]['sha1'] = $hash;
		}
		wfProfileOut( __METHOD__ );
		return $hash;
	}

	/**
	 * @see FileBackendStore::getFileSha1Base36()
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
	 */
	final public function getFileProps( array $params ) {
		wfProfileIn( __METHOD__ );
		$fsFile = $this->getLocalReference( $params );
		$props = $fsFile ? $fsFile->getProps() : FSFile::placeholderProps();
		wfProfileOut( __METHOD__ );
		return $props;
	}

	/**
	 * @see FileBackend::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		wfProfileIn( __METHOD__ );
		$path = $params['src'];
		if ( isset( $this->expensiveCache[$path]['localRef'] ) ) {
			wfProfileOut( __METHOD__ );
			return $this->expensiveCache[$path]['localRef'];
		}
		$tmpFile = $this->getLocalCopy( $params );
		if ( $tmpFile ) { // don't cache negatives
			$this->trimExpensiveCache(); // limit memory
			$this->expensiveCache[$path]['localRef'] = $tmpFile;
		}
		wfProfileOut( __METHOD__ );
		return $tmpFile;
	}

	/**
	 * @see FileBackend::streamFile()
	 */
	final public function streamFile( array $params ) {
		wfProfileIn( __METHOD__ );
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
			$status = $this->doStreamFile( $params );
		} else {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * @see FileBackendStore::streamFile()
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
	 * @copydoc FileBackend::getFileList() 
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
			return new FileBackendStoreShardListIterator( $this,
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
	 */
	protected function doOperationsInternal( array $ops, array $opts ) {
		wfProfileIn( __METHOD__ );
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
				wfProfileOut( __METHOD__ );
				return $status; // abort
			}
		}

		// Clear any cache entries (after locks acquired)
		$this->clearCache();

		// Actually attempt the operation batch...
		$subStatus = FileOp::attemptBatch( $performOps, $opts );

		// Merge errors into status fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

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
 * FileBackendStore helper function to handle file listings that span container shards.
 * Do not use this class from places outside of FileBackendStore.
 *
 * @ingroup FileBackend
 */
class FileBackendStoreShardListIterator implements Iterator {
	/* @var FileBackendStore */
	protected $backend;
	/* @var Array */
	protected $params;
	/* @var Array */
	protected $shardSuffixes;
	protected $container; // string
	protected $directory; // string

	/* @var Traversable */
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

	public function current() {
		if ( is_array( $this->iter ) ) {
			return current( $this->iter );
		} else {
			return $this->iter->current();
		}
	}

	public function key() {
		return $this->pos;
	}

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
	 * If the iterator for this container shard is out of items,
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

	protected function setIteratorFromCurrentShard() {
		$suffix = $this->shardSuffixes[$this->curShard];
		$this->iter = $this->backend->getFileListInternal(
			"{$this->container}{$suffix}", $this->directory, $this->params );
	}

	public function rewind() {
		$this->pos = 0;
		$this->curShard = 0;
		$this->setIteratorFromCurrentShard();
		// Find the next non-empty shard if this one has no elements
		$this->nextShardIteratorIfNotValid();
	}

	public function valid() {
		if ( $this->iter == null ) {
			return false; // some failure?
		} elseif ( is_array( $this->iter ) ) {
			return ( current( $this->iter ) !== false ); // no paths can have this value
		} else {
			return $this->iter->valid();
		}
	}
}
