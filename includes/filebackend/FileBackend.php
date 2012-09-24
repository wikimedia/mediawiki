<?php
/**
 * @defgroup FileBackend File backend
 * @ingroup  FileRepo
 *
 * File backend is used to interact with file storage systems,
 * such as the local file system, NFS, or cloud storage systems.
 */

/**
 * Base class for all file backends.
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
 * @brief Base class for all file backend classes (including multi-write backends).
 *
 * This class defines the methods as abstract that subclasses must implement.
 * Outside callers can assume that all backends will have these functions.
 *
 * All "storage paths" are of the format "mwstore://<backend>/<container>/<path>".
 * The "<path>" portion is a relative path that uses UNIX file system (FS)
 * notation, though any particular backend may not actually be using a local
 * filesystem. Therefore, the relative paths are only virtual.
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
 * Methods of subclasses should avoid throwing exceptions at all costs.
 * As a corollary, external dependencies should be kept to a minimum.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackend {
	protected $name; // string; unique backend name
	protected $wikiId; // string; unique wiki name
	protected $readOnly; // string; read-only explanation message
	protected $parallelize; // string; when to do operations in parallel
	protected $concurrency; // integer; how many operations can be done in parallel

	/** @var LockManager */
	protected $lockManager;
	/** @var FileJournal */
	protected $fileJournal;

	/**
	 * Create a new backend instance from configuration.
	 * This should only be called from within FileBackendGroup.
	 *
	 * $config includes:
	 *   - name        : The unique name of this backend.
	 *                   This should consist of alphanumberic, '-', and '_' characters.
	 *                   This name should not be changed after use.
	 *   - wikiId      : Prefix to container names that is unique to this wiki.
	 *                   It should only consist of alphanumberic, '-', and '_' characters.
	 *   - lockManager : Registered name of a file lock manager to use.
	 *   - fileJournal : File journal configuration; see FileJournal::factory().
	 *                   Journals simply log changes to files stored in the backend.
	 *   - readOnly    : Write operations are disallowed if this is a non-empty string.
	 *                   It should be an explanation for the backend being read-only.
	 *   - parallelize : When to do file operations in parallel (when possible).
	 *                   Allowed values are "implicit", "explicit" and "off".
	 *   - concurrency : How many file operations can be done in parallel.
	 *
	 * @param $config Array
	 * @throws MWException
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
		$this->fileJournal = isset( $config['fileJournal'] )
			? ( ( $config['fileJournal'] instanceof FileJournal )
				? $config['fileJournal']
				: FileJournal::factory( $config['fileJournal'], $this->name ) )
			: FileJournal::factory( array( 'class' => 'NullFileJournal' ), $this->name );
		$this->readOnly = isset( $config['readOnly'] )
			? (string)$config['readOnly']
			: '';
		$this->parallelize = isset( $config['parallelize'] )
			? (string)$config['parallelize']
			: 'off';
		$this->concurrency = isset( $config['concurrency'] )
			? (int)$config['concurrency']
			: 50;
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
	 * Get the wiki identifier used for this backend (possibly empty)
	 *
	 * @return string
	 * @since 1.20
	 */
	final public function getWikiId() {
		return $this->wikiId;
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
	 * @return string|bool Returns false if the backend is not read-only
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
	 * Supported operations and their parameters. The supported actions are:
	 *  - create
	 *  - store
	 *  - copy
	 *  - move
	 *  - delete
	 *  - null
	 *
	 * a) Create a new file in storage with the contents of a string
	 * @code
	 *     array(
	 *         'op'                  => 'create',
	 *         'dst'                 => <storage path>,
	 *         'content'             => <string of new file contents>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     );
	 * @endcode
	 *
	 * b) Copy a file system file into storage
	 * @code
	 *     array(
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 *
	 * c) Copy a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 *
	 * d) Move a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 *
	 * e) Delete a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     )
	 * @endcode
	 *
	 * f) Do nothing (no-op)
	 * @code
	 *     array(
	 *         'op'                  => 'null',
	 *     )
	 * @endcode
	 *
	 * Boolean flags for operations (operation-specific):
	 *   - ignoreMissingSource : The operation will simply succeed and do
	 *                           nothing if the source file does not exist.
	 *   - overwrite           : Any destination file will be overwritten.
	 *   - overwriteSame       : An error will not be given if a file already
	 *                           exists at the destination that has the same
	 *                           contents as the new contents to be written there.
	 *   - disposition         : When supplied, the backend will add a Content-Disposition
	 *                           header when GETs/HEADs of the destination file are made.
	 *                           Backends that don't support file metadata will ignore this.
	 *                           See http://tools.ietf.org/html/rfc6266 (since 1.20).
	 *
	 * $opts is an associative of boolean flags, including:
	 *   - force               : Operation precondition errors no longer trigger an abort.
	 *                           Any remaining operations are still attempted. Unexpected
	 *                           failures may still cause remaning operations to be aborted.
	 *   - nonLocking          : No locks are acquired for the operations.
	 *                           This can increase performance for non-critical writes.
	 *                           This has no effect unless the 'force' flag is set.
	 *   - allowStale          : Don't require the latest available data.
	 *                           This can increase performance for non-critical writes.
	 *                           This has no effect unless the 'force' flag is set.
	 *   - nonJournaled        : Don't log this operation batch in the file journal.
	 *                           This limits the ability of recovery scripts.
	 *   - parallelize         : Try to do operations in parallel when possible.
	 *   - bypassReadOnly      : Allow writes in read-only mode (since 1.20).
	 *   - preserveCache       : Don't clear the process cache before checking files.
	 *                           This should only be used if all entries in the process
	 *                           cache were added after the files were already locked (since 1.20).
	 *
	 * @remarks Remarks on locking:
	 * File system paths given to operations should refer to files that are
	 * already locked or otherwise safe from modification from other processes.
	 * Normally these files will be new temp files, which should be adequate.
	 *
	 * @par Return value:
	 *
	 * This returns a Status, which contains all warnings and fatals that occurred
	 * during the operation. The 'failCount', 'successCount', and 'success' members
	 * will reflect each operation attempted.
	 *
	 * The status will be "OK" unless:
	 *   - a) unexpected operation errors occurred (network partitions, disk full...)
	 *   - b) significant operation errors occurred and 'force' was not set
	 *
	 * @param $ops Array List of operations to execute in order
	 * @param $opts Array Batch operation options
	 * @return Status
	 */
	final public function doOperations( array $ops, array $opts = array() ) {
		if ( empty( $opts['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		if ( empty( $opts['force'] ) ) { // sanity
			unset( $opts['nonLocking'] );
			unset( $opts['allowStale'] );
		}
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
		return $this->doOperation( array( 'op' => 'create' ) + $params, $opts );
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
		return $this->doOperation( array( 'op' => 'store' ) + $params, $opts );
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
		return $this->doOperation( array( 'op' => 'copy' ) + $params, $opts );
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
		return $this->doOperation( array( 'op' => 'move' ) + $params, $opts );
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
		return $this->doOperation( array( 'op' => 'delete' ) + $params, $opts );
	}

	/**
	 * Perform a set of independent file operations on some files.
	 *
	 * This does no locking, nor journaling, and possibly no stat calls.
	 * Any destination files that already exist will be overwritten.
	 * This should *only* be used on non-original files, like cache files.
	 *
	 * Supported operations and their parameters:
	 *  - create
	 *  - store
	 *  - copy
	 *  - move
	 *  - delete
	 *  - null
	 *
	 * a) Create a new file in storage with the contents of a string
	 * @code
	 *     array(
	 *         'op'                  => 'create',
	 *         'dst'                 => <storage path>,
	 *         'content'             => <string of new file contents>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 * b) Copy a file system file into storage
	 * @code
	 *     array(
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path>,
	 *         'dst'                 => <storage path>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 * c) Copy a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 * d) Move a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'disposition'         => <Content-Disposition header value>
	 *     )
	 * @endcode
	 * e) Delete a file within storage
	 * @code
	 *     array(
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     )
	 * @endcode
	 * f) Do nothing (no-op)
	 * @code
	 *     array(
	 *         'op'                  => 'null',
	 *     )
	 * @endcode
	 *
	 * @par Boolean flags for operations (operation-specific):
	 *   - ignoreMissingSource : The operation will simply succeed and do
	 *                           nothing if the source file does not exist.
	 *   - disposition         : When supplied, the backend will add a Content-Disposition
	 *                           header when GETs/HEADs of the destination file are made.
	 *                           Backends that don't support file metadata will ignore this.
	 *                           See http://tools.ietf.org/html/rfc6266 (since 1.20).
	 *
	 * $opts is an associative of boolean flags, including:
	 *   - bypassReadOnly      : Allow writes in read-only mode (since 1.20)
	 *
	 * @par Return value:
	 * This returns a Status, which contains all warnings and fatals that occurred
	 * during the operation. The 'failCount', 'successCount', and 'success' members
	 * will reflect each operation attempted for the given files. The status will be
	 * considered "OK" as long as no fatal errors occurred.
	 *
	 * @param $ops Array Set of operations to execute
	 * @param $opts Array Batch operation options
	 * @return Status
	 * @since 1.20
	 */
	final public function doQuickOperations( array $ops, array $opts = array() ) {
		if ( empty( $opts['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		foreach ( $ops as &$op ) {
			$op['overwrite'] = true; // avoids RTTs in key/value stores
		}
		return $this->doQuickOperationsInternal( $ops );
	}

	/**
	 * @see FileBackend::doQuickOperations()
	 * @since 1.20
	 */
	abstract protected function doQuickOperationsInternal( array $ops );

	/**
	 * Same as doQuickOperations() except it takes a single operation.
	 * If you are doing a batch of operations, then use that function instead.
	 *
	 * @see FileBackend::doQuickOperations()
	 *
	 * @param $op Array Operation
	 * @return Status
	 * @since 1.20
	 */
	final public function doQuickOperation( array $op ) {
		return $this->doQuickOperations( array( $op ) );
	}

	/**
	 * Performs a single quick create operation.
	 * This sets $params['op'] to 'create' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 * @since 1.20
	 */
	final public function quickCreate( array $params ) {
		return $this->doQuickOperation( array( 'op' => 'create' ) + $params );
	}

	/**
	 * Performs a single quick store operation.
	 * This sets $params['op'] to 'store' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 * @since 1.20
	 */
	final public function quickStore( array $params ) {
		return $this->doQuickOperation( array( 'op' => 'store' ) + $params );
	}

	/**
	 * Performs a single quick copy operation.
	 * This sets $params['op'] to 'copy' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 * @since 1.20
	 */
	final public function quickCopy( array $params ) {
		return $this->doQuickOperation( array( 'op' => 'copy' ) + $params );
	}

	/**
	 * Performs a single quick move operation.
	 * This sets $params['op'] to 'move' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 * @since 1.20
	 */
	final public function quickMove( array $params ) {
		return $this->doQuickOperation( array( 'op' => 'move' ) + $params );
	}

	/**
	 * Performs a single quick delete operation.
	 * This sets $params['op'] to 'delete' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param $params Array Operation parameters
	 * @return Status
	 * @since 1.20
	 */
	final public function quickDelete( array $params ) {
		return $this->doQuickOperation( array( 'op' => 'delete' ) + $params );
	}

	/**
	 * Concatenate a list of storage files into a single file system file.
	 * The target path should refer to a file that is already locked or
	 * otherwise safe from modification from other processes. Normally,
	 * the file will be a new temp file, which should be adequate.
	 *
	 * @param $params Array Operation parameters
	 * $params include:
	 *   - srcs : ordered source storage paths (e.g. chunk1, chunk2, ...)
	 *   - dst  : file system path to 0-byte temp file
	 * @return Status
	 */
	abstract public function concatenate( array $params );

	/**
	 * Prepare a storage directory for usage.
	 * This will create any required containers and parent directories.
	 * Backends using key/value stores only need to create the container.
	 *
	 * The 'noAccess' and 'noListing' parameters works the same as in secure(),
	 * except they are only applied *if* the directory/container had to be created.
	 * These flags should always be set for directories that have private files.
	 *
	 * @param $params Array
	 * $params include:
	 *   - dir            : storage directory
	 *   - noAccess       : try to deny file access (since 1.20)
	 *   - noListing      : try to deny file listing (since 1.20)
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return Status
	 */
	final public function prepare( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
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
	 * files whereas key/value store backends might revoke container
	 * access to the storage user representing end-users in web requests.
	 * This is not guaranteed to actually do anything.
	 *
	 * @param $params Array
	 * $params include:
	 *   - dir            : storage directory
	 *   - noAccess       : try to deny file access
	 *   - noListing      : try to deny file listing
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return Status
	 */
	final public function secure( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		return $this->doSecure( $params );
	}

	/**
	 * @see FileBackend::secure()
	 */
	abstract protected function doSecure( array $params );

	/**
	 * Remove measures to block web access to a storage directory and
	 * the container it belongs to. FS backends might remove .htaccess
	 * files whereas key/value store backends might grant container
	 * access to the storage user representing end-users in web requests.
	 * This essentially can undo the result of secure() calls.
	 *
	 * @param $params Array
	 * $params include:
	 *   - dir            : storage directory
	 *   - access         : try to allow file access
	 *   - listing        : try to allow file listing
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return Status
	 * @since 1.20
	 */
	final public function publish( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return Status::newFatal( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		return $this->doPublish( $params );
	}

	/**
	 * @see FileBackend::publish()
	 */
	abstract protected function doPublish( array $params );

	/**
	 * Delete a storage directory if it is empty.
	 * Backends using key/value stores may do nothing unless the directory
	 * is that of an empty container, in which case it will be deleted.
	 *
	 * @param $params Array
	 * $params include:
	 *   - dir            : storage directory
	 *   - recursive      : recursively delete empty subdirectories first (since 1.20)
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return Status
	 */
	final public function clean( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
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
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return bool|null Returns null on failure
	 */
	abstract public function fileExists( array $params );

	/**
	 * Get the last-modified timestamp of the file at a storage path.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|bool TS_MW timestamp or false on failure
	 */
	abstract public function getFileTimestamp( array $params );

	/**
	 * Get the contents of a file at a storage path in the backend.
	 * This should be avoided for potentially large files.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|bool Returns false on failure
	 */
	abstract public function getFileContents( array $params );

	/**
	 * Get the size (bytes) of a file at a storage path in the backend.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return integer|bool Returns false on failure
	 */
	abstract public function getFileSize( array $params );

	/**
	 * Get quick information about a file at a storage path in the backend.
	 * If the file does not exist, then this returns false.
	 * Otherwise, the result is an associative array that includes:
	 *   - mtime  : the last-modified timestamp (TS_MW)
	 *   - size   : the file size (bytes)
	 * Additional values may be included for internal use only.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return Array|bool|null Returns null on failure
	 */
	abstract public function getFileStat( array $params );

	/**
	 * Get a SHA-1 hash of the file at a storage path in the backend.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|bool Hash string or false on failure
	 */
	abstract public function getFileSha1Base36( array $params );

	/**
	 * Get the properties of the file at a storage path in the backend.
	 * Returns FSFile::placeholderProps() on failure.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return Array
	 */
	abstract public function getFileProps( array $params );

	/**
	 * Stream the file at a storage path in the backend.
	 * If the file does not exists, an HTTP 404 error will be given.
	 * Appropriate HTTP headers (Status, Content-Type, Content-Length)
	 * will be sent if streaming began, while none will be sent otherwise.
	 * Implementations should flush the output buffer before sending data.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src     : source storage path
	 *   - headers : list of additional HTTP headers to send on success
	 *   - latest  : use the latest available data
	 * @return Status
	 */
	abstract public function streamFile( array $params );

	/**
	 * Returns a file system file, identical to the file at a storage path.
	 * The file returned is either:
	 *   - a) A local copy of the file at a storage path in the backend.
	 *        The temporary copy will have the same extension as the source.
	 *   - b) An original of the file at a storage path in the backend.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * Write operations should *never* be done on this file as some backends
	 * may do internal tracking or may be instances of FileBackendMultiWrite.
	 * In that later case, there are copies of the file that must stay in sync.
	 * Additionally, further calls to this function may return the same file.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return FSFile|null Returns null on failure
	 */
	abstract public function getLocalReference( array $params );

	/**
	 * Get a local copy on disk of the file at a storage path in the backend.
	 * The temporary copy will have the same file extension as the source.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @param $params Array
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return TempFSFile|null Returns null on failure
	 */
	abstract public function getLocalCopy( array $params );

	/**
	 * Check if a directory exists at a given storage path.
	 * Backends using key/value stores will check if the path is a
	 * virtual directory, meaning there are files under the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @param $params array
	 * $params include:
	 *   - dir : storage directory
	 * @return bool|null Returns null on failure
	 * @since 1.20
	 */
	abstract public function directoryExists( array $params );

	/**
	 * Get an iterator to list *all* directories under a storage directory.
	 * If the directory is of the form "mwstore://backend/container",
	 * then all directories in the container will be listed.
	 * If the directory is of form "mwstore://backend/container/dir",
	 * then all directories directly under that directory will be listed.
	 * Results will be storage directories relative to the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @param $params array
	 * $params include:
	 *   - dir     : storage directory
	 *   - topOnly : only return direct child dirs of the directory
	 * @return Traversable|Array|null Returns null on failure
	 * @since 1.20
	 */
	abstract public function getDirectoryList( array $params );

	/**
	 * Same as FileBackend::getDirectoryList() except only lists
	 * directories that are immediately under the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @param $params array
	 * $params include:
	 *   - dir : storage directory
	 * @return Traversable|Array|null Returns null on failure
	 * @since 1.20
	 */
	final public function getTopDirectoryList( array $params ) {
		return $this->getDirectoryList( array( 'topOnly' => true ) + $params );
	}

	/**
	 * Get an iterator to list *all* stored files under a storage directory.
	 * If the directory is of the form "mwstore://backend/container",
	 * then all files in the container will be listed.
	 * If the directory is of form "mwstore://backend/container/dir",
	 * then all files under that directory will be listed.
	 * Results will be storage paths relative to the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @param $params array
	 * $params include:
	 *   - dir     : storage directory
	 *   - topOnly : only return direct child files of the directory (since 1.20)
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract public function getFileList( array $params );

	/**
	 * Same as FileBackend::getFileList() except only lists
	 * files that are immediately under the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @param $params array
	 * $params include:
	 *   - dir : storage directory
	 * @return Traversable|Array|null Returns null on failure
	 * @since 1.20
	 */
	final public function getTopFileList( array $params ) {
		return $this->getFileList( array( 'topOnly' => true ) + $params );
	}

	/**
	 * Preload persistent file stat and property cache into in-process cache.
	 * This should be used when stat calls will be made on a known list of a many files.
	 *
	 * @param $paths Array Storage paths
	 * @return void
	 */
	public function preloadCache( array $paths ) {}

	/**
	 * Invalidate any in-process file stat and property cache.
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
	 * Get an array of scoped locks needed for a batch of file operations.
	 *
	 * Normally, FileBackend::doOperations() handles locking, unless
	 * the 'nonLocking' param is passed in. This function is useful if you
	 * want the files to be locked for a broader scope than just when the
	 * files are changing. For example, if you need to update DB metadata,
	 * you may want to keep the files locked until finished.
	 *
	 * @see FileBackend::doOperations()
	 *
	 * @param $ops Array List of file operations to FileBackend::doOperations()
	 * @param $status Status Status to update on lock/unlock
	 * @return Array List of ScopedFileLocks or null values
	 * @since 1.20
	 */
	abstract public function getScopedLocksForOps( array $ops, Status $status );

	/**
	 * Get the root storage path of this backend.
	 * All container paths are "subdirectories" of this path.
	 *
	 * @return string Storage path
	 * @since 1.20
	 */
	final public function getRootStoragePath() {
		return "mwstore://{$this->name}";
	}

	/**
	 * Get the file journal object for this backend
	 *
	 * @return FileJournal
	 */
	final public function getJournal() {
		return $this->fileJournal;
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

	/**
	 * Check if a relative path has no directory traversals
	 *
	 * @param $path string
	 * @return bool
	 * @since 1.20
	 */
	final public static function isPathTraversalFree( $path ) {
		return ( self::normalizeContainerPath( $path ) !== null );
	}

	/**
	 * Build a Content-Disposition header value per RFC 6266.
	 *
	 * @param $type string One of (attachment, inline)
	 * @param $filename string Suggested file name (should not contain slashes)
	 * @return string
	 * @since 1.20
	 */
	final public static function makeContentDisposition( $type, $filename = '' ) {
		$parts = array();

		$type = strtolower( $type );
		if ( !in_array( $type, array( 'inline', 'attachment' ) ) ) {
			throw new MWException( "Invalid Content-Disposition type '$type'." );
		}
		$parts[] = $type;

		if ( strlen( $filename ) ) {
			$parts[] = "filename*=UTF-8''" . rawurlencode( basename( $filename ) );
		}

		return implode( ';', $parts );
	}

	/**
	 * Validate and normalize a relative storage path.
	 * Null is returned if the path involves directory traversal.
	 * Traversal is insecure for FS backends and broken for others.
	 *
	 * This uses the same traversal protection as Title::secureAndSplit().
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
}
