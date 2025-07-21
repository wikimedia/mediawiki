<?php
/**
 * @defgroup FileBackend File backend
 *
 * File backend is used to interact with file storage systems,
 * such as the local file system, NFS, or cloud storage systems.
 * See [the architecture doc](@ref filebackendarch) for more information.
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
 */

namespace Wikimedia\FileBackend;

use InvalidArgumentException;
use LockManager;
use NullLockManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ScopedLock;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use Wikimedia\FileBackend\FSFile\FSFile;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ScopedCallback;

/**
 * @brief Base class for all file backend classes (including multi-write backends).
 *
 * This class defines the methods as abstract that subclasses must implement.
 * Outside callers can assume that all backends will have these functions.
 *
 * All "storage paths" are of the format "mwstore://<backend>/<container>/<path>".
 * The "backend" portion is unique name for the application to refer to a backend, while
 * the "container" portion is a top-level directory of the backend. The "path" portion
 * is a relative path that uses UNIX file system (FS) notation, though any particular
 * backend may not actually be using a local filesystem. Therefore, the relative paths
 * are only virtual.
 *
 * Backend contents are stored under "domain"-specific container names by default.
 * A domain is simply a logical umbrella for entities, such as those belonging to a certain
 * application or portion of a website, for example. A domain can be local or global.
 * Global (qualified) backends are achieved by configuring the "domain ID" to a constant.
 * Global domains are simpler, but local domains can be used by choosing a domain ID based on
 * the current context, such as which language of a website is being used.
 *
 * For legacy reasons, the FSFileBackend class allows manually setting the paths of
 * containers to ones that do not respect the "domain ID".
 *
 * In key/value (object) stores, containers are the only hierarchy (the rest is emulated).
 * FS-based backends are somewhat more restrictive due to the existence of real
 * directory files; a regular file cannot have the same name as a directory. Other
 * backends with virtual directories may not have this limitation. Callers should
 * store files in such a way that no files and directories are under the same path.
 *
 * In general, this class allows for callers to access storage through the same
 * interface, without regard to the underlying storage system. However, calling code
 * must follow certain patterns and be aware of certain things to ensure compatibility:
 *   - a) Always call prepare() on the parent directory before trying to put a file there;
 *        key/value stores only need the container to exist first, but filesystems need
 *        all the parent directories to exist first (prepare() is aware of all this)
 *   - b) Always call clean() on a directory when it might become empty to avoid empty
 *        directory buildup on filesystems; key/value stores never have empty directories,
 *        so doing this helps preserve consistency in both cases
 *   - c) Likewise, do not rely on the existence of empty directories for anything;
 *        calling directoryExists() on a path that prepare() was previously called on
 *        will return false for key/value stores if there are no files under that path
 *   - d) Never alter the resulting FSFile returned from getLocalReference(), as it could
 *        either be a copy of the source file in /tmp or the original source file itself
 *   - e) Use a file layout that results in never attempting to store files over directories
 *        or directories over files; key/value stores allow this but filesystems do not
 *   - f) Use ASCII file names (e.g. base32, IDs, hashes) to avoid Unicode issues in Windows
 *   - g) Do not assume that move operations are atomic (difficult with key/value stores)
 *   - h) Do not assume that file stat or read operations always have immediate consistency;
 *        various methods have a "latest" flag that should always be used if up-to-date
 *        information is required (this trades performance for correctness as needed)
 *   - i) Do not assume that directory listings have immediate consistency
 *
 * Methods of subclasses should avoid throwing exceptions at all costs.
 * As a corollary, external dependencies should be kept to a minimum.
 *
 * See [the architecture doc](@ref filebackendarch) for more information.
 *
 * @stable to extend
 *
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileBackend implements LoggerAwareInterface {
	/** @var string Unique backend name */
	protected $name;

	/** @var string Unique domain name */
	protected $domainId;

	/** @var string Read-only explanation message */
	protected $readOnly;

	/** @var string When to do operations in parallel */
	protected $parallelize;

	/** @var int How many operations can be done in parallel */
	protected $concurrency;

	/** @var TempFSFileFactory */
	protected $tmpFileFactory;

	/** @var LockManager */
	protected $lockManager;
	/** @var LoggerInterface */
	protected $logger;
	/** @var callable|null */
	protected $profiler;

	/** @var callable */
	private $obResetFunc;
	/** @var callable */
	private $headerFunc;
	/** @var callable */
	private $asyncHandler;
	/** @var array Option map for use with HTTPFileStreamer */
	protected $streamerOptions;
	/** @var callable|null */
	protected $statusWrapper;

	/** Bitfield flags for supported features */
	public const ATTR_HEADERS = 1; // files can be tagged with standard HTTP headers
	public const ATTR_METADATA = 2; // files can be stored with metadata key/values
	public const ATTR_UNICODE_PATHS = 4; // files can have Unicode paths (not just ASCII)

	/** @var false Idiom for "no info; non-existant file" (since 1.34) */
	protected const STAT_ABSENT = false;

	/** @var null Idiom for "no info; I/O errors" (since 1.34) */
	public const STAT_ERROR = null;
	/** @var null Idiom for "no file/directory list; I/O errors" (since 1.34) */
	public const LIST_ERROR = null;
	/** @var null Idiom for "no temp URL; not supported or I/O errors" (since 1.34) */
	public const TEMPURL_ERROR = null;
	/** @var null Idiom for "existence unknown; I/O errors" (since 1.34) */
	public const EXISTENCE_ERROR = null;

	/** @var false Idiom for "no timestamp; missing file or I/O errors" (since 1.34) */
	public const TIMESTAMP_FAIL = false;
	/** @var false Idiom for "no content; missing file or I/O errors" (since 1.34) */
	public const CONTENT_FAIL = false;
	/** @var false Idiom for "no metadata; missing file or I/O errors" (since 1.34) */
	public const XATTRS_FAIL = false;
	/** @var false Idiom for "no size; missing file or I/O errors" (since 1.34) */
	public const SIZE_FAIL = false;
	/** @var false Idiom for "no SHA1 hash; missing file or I/O errors" (since 1.34) */
	public const SHA1_FAIL = false;

	/**
	 * Create a new backend instance from configuration.
	 * This should only be called from within FileBackendGroup.
	 * @stable to call
	 *
	 * @param array $config Parameters include:
	 *   - name : The unique name of this backend.
	 *      This should consist of alphanumberic, '-', and '_' characters.
	 *      This name should not be changed after use.
	 *      Note that the name is *not* used in actual container names.
	 *   - domainId : Prefix to container names that is unique to this backend.
	 *      It should only consist of alphanumberic, '-', and '_' characters.
	 *      This ID is what avoids collisions if multiple logical backends
	 *      use the same storage system, so this should be set carefully.
	 *   - lockManager : LockManager object to use for any file locking.
	 *      If not provided, then no file locking will be enforced.
	 *   - readOnly : Write operations are disallowed if this is a non-empty string.
	 *      It should be an explanation for the backend being read-only.
	 *   - parallelize : When to do file operations in parallel (when possible).
	 *      Allowed values are "implicit", "explicit" and "off".
	 *   - concurrency : How many file operations can be done in parallel.
	 *   - tmpDirectory : Directory to use for temporary files.
	 *   - tmpFileFactory : Optional TempFSFileFactory object. Only has an effect if
	 *      tmpDirectory is not set. If both are unset or null, then the backend will
	 *      try to discover a usable temporary directory.
	 *   - obResetFunc : alternative callback to clear the output buffer
	 *   - streamMimeFunc : alternative method to determine the content type from the path
	 *   - headerFunc : alternative callback for sending response headers
	 *   - asyncHandler : callback for scheduling deferred updated
	 *   - logger : Optional PSR logger object.
	 *   - profiler : Optional callback that takes a section name argument and returns
	 *      a ScopedCallback instance that ends the profile section in its destructor.
	 *   - statusWrapper : Optional callback that is used to wrap returned StatusValues
	 */
	public function __construct( array $config ) {
		if ( !array_key_exists( 'name', $config ) ) {
			throw new InvalidArgumentException( 'Backend name not specified.' );
		}
		$this->name = $config['name'];
		$this->domainId = $config['domainId'] // e.g. "my_wiki-en_"
			?? $config['wikiId'] // b/c alias
			?? null;
		if ( !is_string( $this->name ) || !preg_match( '!^[a-zA-Z0-9-_]{1,255}$!', $this->name ) ) {
			throw new InvalidArgumentException( "Backend name '{$this->name}' is invalid." );
		}
		if ( !is_string( $this->domainId ) ) {
			throw new InvalidArgumentException(
				"Backend domain ID not provided for '{$this->name}'." );
		}
		$this->lockManager = $config['lockManager'] ?? new NullLockManager( [] );
		$this->readOnly = isset( $config['readOnly'] )
			? (string)$config['readOnly']
			: '';
		$this->parallelize = isset( $config['parallelize'] )
			? (string)$config['parallelize']
			: 'off';
		$this->concurrency = isset( $config['concurrency'] )
			? (int)$config['concurrency']
			: 50;
		$this->obResetFunc = $config['obResetFunc']
			?? [ self::class, 'resetOutputBufferTheDefaultWay' ];
		$this->headerFunc = $config['headerFunc'] ?? 'header';
		$this->asyncHandler = $config['asyncHandler'] ?? null;
		$this->streamerOptions = [
			'obResetFunc' => $this->obResetFunc,
			'headerFunc' => $this->headerFunc,
			'streamMimeFunc' => $config['streamMimeFunc'] ?? null,
		];

		$this->profiler = $config['profiler'] ?? null;
		if ( !is_callable( $this->profiler ) ) {
			$this->profiler = null;
		}
		$this->logger = $config['logger'] ?? new NullLogger();
		$this->statusWrapper = $config['statusWrapper'] ?? null;
		// tmpDirectory gets precedence for backward compatibility
		if ( isset( $config['tmpDirectory'] ) ) {
			$this->tmpFileFactory = new TempFSFileFactory( $config['tmpDirectory'] );
		} else {
			$this->tmpFileFactory = $config['tmpFileFactory'] ?? new TempFSFileFactory();
		}
	}

	protected function header( $header ) {
		( $this->headerFunc )( $header );
	}

	/**
	 * @param callable $update
	 *
	 * @return void
	 */
	protected function callNowOrLater( callable $update ) {
		if ( $this->asyncHandler ) {
			( $this->asyncHandler )( $update );
		} else {
			$update();
		}
	}

	protected function resetOutputBuffer() {
		// By default, this ends up calling $this->defaultOutputBufferReset
		( $this->obResetFunc )();
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Get the unique backend name
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
	 * Get the domain identifier used for this backend (possibly empty).
	 *
	 * @return string
	 * @since 1.28
	 */
	final public function getDomainId() {
		return $this->domainId;
	}

	/**
	 * Alias to getDomainId()
	 *
	 * @return string
	 * @since 1.20
	 * @deprecated Since 1.34 Use getDomainId()
	 */
	final public function getWikiId() {
		return $this->getDomainId();
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
	 * Get the a bitfield of extra features supported by the backend medium
	 * @stable to override
	 *
	 * @return int Bitfield of FileBackend::ATTR_* flags
	 * @since 1.23
	 */
	public function getFeatures() {
		return self::ATTR_UNICODE_PATHS;
	}

	/**
	 * Check if the backend medium supports a field of extra features
	 *
	 * @param int $bitfield Bitfield of FileBackend::ATTR_* flags
	 * @return bool
	 * @since 1.23
	 */
	final public function hasFeatures( $bitfield ) {
		return ( $this->getFeatures() & $bitfield ) === $bitfield;
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
	 *  - describe (since 1.21)
	 *  - null
	 *
	 * FSFile/TempFSFile object support was added in 1.27.
	 *
	 * a) Create a new file in storage with the contents of a string
	 * @code
	 *     [
	 *         'op'                  => 'create',
	 *         'dst'                 => <storage path>,
	 *         'content'             => <string of new file contents>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * b) Copy a file system file into storage
	 * @code
	 *     [
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path, FSFile, or TempFSFile>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * c) Copy a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'ignoreMissingSource' => <boolean>, # since 1.21
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * d) Move a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'overwrite'           => <boolean>,
	 *         'overwriteSame'       => <boolean>,
	 *         'ignoreMissingSource' => <boolean>, # since 1.21
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * e) Delete a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     ]
	 * @endcode
	 *
	 * f) Update metadata for a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'describe',
	 *         'src'                 => <storage path>,
	 *         'headers'             => <HTTP header name/value map>
	 *     ]
	 * @endcode
	 *
	 * g) Do nothing (no-op)
	 * @code
	 *     [
	 *         'op'                  => 'null',
	 *     ]
	 * @endcode
	 *
	 * Boolean flags for operations (operation-specific):
	 *   - ignoreMissingSource : The operation will simply succeed and do
	 *                           nothing if the source file does not exist.
	 *   - overwrite           : Any destination file will be overwritten.
	 *   - overwriteSame       : If a file already exists at the destination with the
	 *                           same contents, then do nothing to the destination file
	 *                           instead of giving an error. This does not compare headers.
	 *                           This option is ignored if 'overwrite' is already provided.
	 *   - headers             : If supplied, the result of merging these headers with any
	 *                           existing source file headers (replacing conflicting ones)
	 *                           will be set as the destination file headers. Headers are
	 *                           deleted if their value is set to the empty string. When a
	 *                           file has headers they are included in responses to GET and
	 *                           HEAD requests to the backing store for that file.
	 *                           Header values should be no larger than 255 bytes, except for
	 *                           Content-Disposition. The system might ignore or truncate any
	 *                           headers that are too long to store (exact limits will vary).
	 *                           Backends that don't support metadata ignore this. (since 1.21)
	 *
	 * $opts is an associative of boolean flags, including:
	 *   - force               : Operation precondition errors no longer trigger an abort.
	 *                           Any remaining operations are still attempted. Unexpected
	 *                           failures may still cause remaining operations to be aborted.
	 *   - nonLocking          : No locks are acquired for the operations.
	 *                           This can increase performance for non-critical writes.
	 *                           This has no effect unless the 'force' flag is set.
	 *   - parallelize         : Try to do operations in parallel when possible.
	 *   - bypassReadOnly      : Allow writes in read-only mode. (since 1.20)
	 *   - preserveCache       : Don't clear the process cache before checking files.
	 *                           This should only be used if all entries in the process
	 *                           cache were added after the files were already locked. (since 1.20)
	 *
	 * @note Remarks on locking:
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
	 * The StatusValue will be "OK" unless:
	 *   - a) unexpected operation errors occurred (network partitions, disk full...)
	 *   - b) predicted operation errors occurred and 'force' was not set
	 *
	 * @param array[] $ops List of operations to execute in order
	 * @phan-param array<int,array{ignoreMissingSource?:bool,overwrite?:bool,overwriteSame?:bool,headers?:bool}> $ops
	 * @param array $opts Batch operation options
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{force?:bool,nonLocking?:bool,parallelize?:bool,bypassReadOnly?:bool,preserveCache?:bool} $opts
	 * @return StatusValue
	 */
	final public function doOperations( array $ops, array $opts = [] ) {
		if ( empty( $opts['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		if ( $ops === [] ) {
			return $this->newStatus(); // nothing to do
		}

		$ops = $this->resolveFSFileObjects( $ops );
		if ( empty( $opts['force'] ) ) {
			unset( $opts['nonLocking'] );
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts

		return $this->doOperationsInternal( $ops, $opts );
	}

	/**
	 * @see FileBackend::doOperations()
	 * @param array $ops
	 * @param array $opts
	 * @return StatusValue
	 */
	abstract protected function doOperationsInternal( array $ops, array $opts );

	/**
	 * Same as doOperations() except it takes a single operation.
	 * If you are doing a batch of operations that should either
	 * all succeed or all fail, then use that function instead.
	 *
	 * @see FileBackend::doOperations()
	 *
	 * @param array $op Operation
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function doOperation( array $op, array $opts = [] ) {
		return $this->doOperations( [ $op ], $opts );
	}

	/**
	 * Performs a single create operation.
	 * This sets $params['op'] to 'create' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function create( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'create' ] + $params, $opts );
	}

	/**
	 * Performs a single store operation.
	 * This sets $params['op'] to 'store' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function store( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'store' ] + $params, $opts );
	}

	/**
	 * Performs a single copy operation.
	 * This sets $params['op'] to 'copy' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function copy( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'copy' ] + $params, $opts );
	}

	/**
	 * Performs a single move operation.
	 * This sets $params['op'] to 'move' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function move( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'move' ] + $params, $opts );
	}

	/**
	 * Performs a single delete operation.
	 * This sets $params['op'] to 'delete' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 */
	final public function delete( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'delete' ] + $params, $opts );
	}

	/**
	 * Performs a single describe operation.
	 * This sets $params['op'] to 'describe' and passes it to doOperation().
	 *
	 * @see FileBackend::doOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.21
	 */
	final public function describe( array $params, array $opts = [] ) {
		return $this->doOperation( [ 'op' => 'describe' ] + $params, $opts );
	}

	/**
	 * Perform a set of independent file operations on some files.
	 *
	 * This does no locking, and possibly no stat calls.
	 * Any destination files that already exist will be overwritten.
	 * This should *only* be used on non-original files, like cache files.
	 *
	 * Supported operations and their parameters:
	 *  - create
	 *  - store
	 *  - copy
	 *  - move
	 *  - delete
	 *  - describe (since 1.21)
	 *  - null
	 *
	 * FSFile/TempFSFile object support was added in 1.27.
	 *
	 * a) Create a new file in storage with the contents of a string
	 * @code
	 *     [
	 *         'op'                  => 'create',
	 *         'dst'                 => <storage path>,
	 *         'content'             => <string of new file contents>,
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * b) Copy a file system file into storage
	 * @code
	 *     [
	 *         'op'                  => 'store',
	 *         'src'                 => <file system path, FSFile, or TempFSFile>,
	 *         'dst'                 => <storage path>,
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * c) Copy a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'copy',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>, # since 1.21
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * d) Move a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'move',
	 *         'src'                 => <storage path>,
	 *         'dst'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>, # since 1.21
	 *         'headers'             => <HTTP header name/value map> # since 1.21
	 *     ]
	 * @endcode
	 *
	 * e) Delete a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'delete',
	 *         'src'                 => <storage path>,
	 *         'ignoreMissingSource' => <boolean>
	 *     ]
	 * @endcode
	 *
	 * f) Update metadata for a file within storage
	 * @code
	 *     [
	 *         'op'                  => 'describe',
	 *         'src'                 => <storage path>,
	 *         'headers'             => <HTTP header name/value map>
	 *     ]
	 * @endcode
	 *
	 * g) Do nothing (no-op)
	 * @code
	 *     [
	 *         'op'                  => 'null',
	 *     ]
	 * @endcode
	 *
	 * @par Boolean flags for operations (operation-specific):
	 *   - ignoreMissingSource : The operation will simply succeed and do
	 *                           nothing if the source file does not exist.
	 *   - headers             : If supplied with a header name/value map, the backend will
	 *                           reply with these headers when GETs/HEADs of the destination
	 *                           file are made. Header values should be smaller than 256 bytes.
	 *                           Content-Disposition headers can be longer, though the system
	 *                           might ignore or truncate ones that are too long to store.
	 *                           Existing headers will remain, but these will replace any
	 *                           conflicting previous headers, and headers will be removed
	 *                           if they are set to an empty string.
	 *                           Backends that don't support metadata ignore this. (since 1.21)
	 *
	 * $opts is an associative of boolean flags, including:
	 *   - bypassReadOnly      : Allow writes in read-only mode (since 1.20)
	 *
	 * @par Return value:
	 * This returns a Status, which contains all warnings and fatals that occurred
	 * during the operation. The 'failCount', 'successCount', and 'success' members
	 * will reflect each operation attempted for the given files. The StatusValue will be
	 * considered "OK" as long as no fatal errors occurred.
	 *
	 * @param array $ops Set of operations to execute
	 * @phan-param list<array{op:?string,src?:string,dst?:string,ignoreMissingSource?:bool,headers?:array}> $ops
	 * @param array $opts Batch operation options
	 * @phan-param array{bypassReadOnly?:bool} $opts
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function doQuickOperations( array $ops, array $opts = [] ) {
		if ( empty( $opts['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		if ( $ops === [] ) {
			return $this->newStatus(); // nothing to do
		}

		$ops = $this->resolveFSFileObjects( $ops );
		foreach ( $ops as &$op ) {
			$op['overwrite'] = true; // avoids RTTs in key/value stores
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts

		return $this->doQuickOperationsInternal( $ops, $opts );
	}

	/**
	 * @see FileBackend::doQuickOperations()
	 * @param array $ops
	 * @param array $opts
	 * @return StatusValue
	 * @since 1.20
	 */
	abstract protected function doQuickOperationsInternal( array $ops, array $opts );

	/**
	 * Same as doQuickOperations() except it takes a single operation.
	 * If you are doing a batch of operations, then use that function instead.
	 *
	 * @see FileBackend::doQuickOperations()
	 *
	 * @param array $op Operation
	 * @param array $opts Batch operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function doQuickOperation( array $op, array $opts = [] ) {
		return $this->doQuickOperations( [ $op ], $opts );
	}

	/**
	 * Performs a single quick create operation.
	 * This sets $params['op'] to 'create' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function quickCreate( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'create' ] + $params, $opts );
	}

	/**
	 * Performs a single quick store operation.
	 * This sets $params['op'] to 'store' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function quickStore( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'store' ] + $params, $opts );
	}

	/**
	 * Performs a single quick copy operation.
	 * This sets $params['op'] to 'copy' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function quickCopy( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'copy' ] + $params, $opts );
	}

	/**
	 * Performs a single quick move operation.
	 * This sets $params['op'] to 'move' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function quickMove( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'move' ] + $params, $opts );
	}

	/**
	 * Performs a single quick delete operation.
	 * This sets $params['op'] to 'delete' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function quickDelete( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'delete' ] + $params, $opts );
	}

	/**
	 * Performs a single quick describe operation.
	 * This sets $params['op'] to 'describe' and passes it to doQuickOperation().
	 *
	 * @see FileBackend::doQuickOperation()
	 *
	 * @param array $params Operation parameters
	 * @param array $opts Operation options
	 * @return StatusValue
	 * @since 1.21
	 */
	final public function quickDescribe( array $params, array $opts = [] ) {
		return $this->doQuickOperation( [ 'op' => 'describe' ] + $params, $opts );
	}

	/**
	 * Concatenate a list of storage files into a single file system file.
	 * The target path should refer to a file that is already locked or
	 * otherwise safe from modification from other processes. Normally,
	 * the file will be a new temp file, which should be adequate.
	 *
	 * @param array $params Operation parameters, include:
	 *   - srcs        : ordered source storage paths (e.g. chunk1, chunk2, ...)
	 *   - dst         : file system path to 0-byte temp file
	 *   - parallelize : try to do operations in parallel when possible
	 * @return StatusValue
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
	 * However, setting them is not guaranteed to actually do anything.
	 * Additional server configuration may be needed to achieve the desired effect.
	 *
	 * @param array $params Parameters include:
	 *   - dir            : storage directory
	 *   - noAccess       : try to deny file access (since 1.20)
	 *   - noListing      : try to deny file listing (since 1.20)
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	final public function prepare( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts
		return $this->doPrepare( $params );
	}

	/**
	 * @see FileBackend::prepare()
	 * @param array $params
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	abstract protected function doPrepare( array $params );

	/**
	 * Take measures to block web access to a storage directory and
	 * the container it belongs to. FS backends might add .htaccess
	 * files whereas key/value store backends might revoke container
	 * access to the storage user representing end-users in web requests.
	 *
	 * This is not guaranteed to actually make files or listings publicly hidden.
	 * Additional server configuration may be needed to achieve the desired effect.
	 *
	 * @param array $params Parameters include:
	 *   - dir            : storage directory
	 *   - noAccess       : try to deny file access
	 *   - noListing      : try to deny file listing
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return StatusValue
	 */
	final public function secure( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts
		return $this->doSecure( $params );
	}

	/**
	 * @see FileBackend::secure()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doSecure( array $params );

	/**
	 * Remove measures to block web access to a storage directory and
	 * the container it belongs to. FS backends might remove .htaccess
	 * files whereas key/value store backends might grant container
	 * access to the storage user representing end-users in web requests.
	 * This essentially can undo the result of secure() calls.
	 *
	 * This is not guaranteed to actually make files or listings publicly viewable.
	 * Additional server configuration may be needed to achieve the desired effect.
	 *
	 * @param array $params Parameters include:
	 *   - dir            : storage directory
	 *   - access         : try to allow file access
	 *   - listing        : try to allow file listing
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return StatusValue
	 * @since 1.20
	 */
	final public function publish( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts
		return $this->doPublish( $params );
	}

	/**
	 * @see FileBackend::publish()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doPublish( array $params );

	/**
	 * Delete a storage directory if it is empty.
	 * Backends using key/value stores may do nothing unless the directory
	 * is that of an empty container, in which case it will be deleted.
	 *
	 * @param array $params Parameters include:
	 *   - dir            : storage directory
	 *   - recursive      : recursively delete empty subdirectories first (since 1.20)
	 *   - bypassReadOnly : allow writes in read-only mode (since 1.20)
	 * @return StatusValue
	 */
	final public function clean( array $params ) {
		if ( empty( $params['bypassReadOnly'] ) && $this->isReadOnly() ) {
			return $this->newStatus( 'backend-fail-readonly', $this->name, $this->readOnly );
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort(); // try to ignore client aborts
		return $this->doClean( $params );
	}

	/**
	 * @see FileBackend::clean()
	 * @param array $params
	 * @return StatusValue
	 */
	abstract protected function doClean( array $params );

	/**
	 * Check if a file exists at a storage path in the backend.
	 * This returns false if only a directory exists at the path.
	 *
	 * Callers that only care if a file is readily accessible can use non-strict
	 * comparisons on the result. If "does not exist" and "existence is unknown"
	 * must be distinguished, then strict comparisons to true/null should be used.
	 *
	 * @see FileBackend::EXISTENCE_ERROR
	 * @see FileBackend::directoryExists()
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return bool|null Whether the file exists or null (I/O error)
	 */
	abstract public function fileExists( array $params );

	/**
	 * Get the last-modified timestamp of the file at a storage path.
	 *
	 * @see FileBackend::TIMESTAMP_FAIL
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|false TS_MW timestamp or false (missing file or I/O error)
	 */
	abstract public function getFileTimestamp( array $params );

	/**
	 * Get the contents of a file at a storage path in the backend.
	 * This should be avoided for potentially large files.
	 *
	 * @see FileBackend::CONTENT_FAIL
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|false Content string or false (missing file or I/O error)
	 */
	final public function getFileContents( array $params ) {
		$contents = $this->getFileContentsMulti( [ 'srcs' => [ $params['src'] ] ] + $params );

		return $contents[$params['src']];
	}

	/**
	 * Like getFileContents() except it takes an array of storage paths
	 * and returns an order preserved map of storage paths to their content.
	 *
	 * @see FileBackend::getFileContents()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 *   - parallelize : try to do operations in parallel when possible
	 * @return string[]|false[] Map of (path name => file content or false on failure)
	 * @since 1.20
	 */
	abstract public function getFileContentsMulti( array $params );

	/**
	 * Get metadata about a file at a storage path in the backend.
	 * If the file does not exist, then this returns false.
	 * Otherwise, the result is an associative array that includes:
	 *   - headers  : map of HTTP headers used for GET/HEAD requests (name => value)
	 *   - metadata : map of file metadata (name => value)
	 * Metadata keys and headers names will be returned in all lower-case.
	 * Additional values may be included for internal use only.
	 *
	 * Use FileBackend::hasFeatures() to check how well this is supported.
	 *
	 * @see FileBackend::XATTRS_FAIL
	 *
	 * @param array $params
	 * $params include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return array|false File metadata array or false (missing file or I/O error)
	 * @since 1.23
	 */
	abstract public function getFileXAttributes( array $params );

	/**
	 * Get the size (bytes) of a file at a storage path in the backend.
	 *
	 * @see FileBackend::SIZE_FAIL
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return int|false File size in bytes or false (missing file or I/O error)
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
	 * @see FileBackend::STAT_ABSENT
	 * @see FileBackend::STAT_ERROR
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return array|false|null Attribute map, false (missing file), or null (I/O error)
	 */
	abstract public function getFileStat( array $params );

	/**
	 * Get a SHA-1 hash of the content of the file at a storage path in the backend.
	 *
	 * @see FileBackend::SHA1_FAIL
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return string|false Hash string or false (missing file or I/O error)
	 */
	abstract public function getFileSha1Base36( array $params );

	/**
	 * Get the properties of the content of the file at a storage path in the backend.
	 * This gives the result of FSFile::getProps() on a local copy of the file.
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return array Properties map; FSFile::placeholderProps() if file missing or on I/O error
	 */
	abstract public function getFileProps( array $params );

	/**
	 * Stream the content of the file at a storage path in the backend.
	 *
	 * If the file does not exists, an HTTP 404 error will be given.
	 * Appropriate HTTP headers (Status, Content-Type, Content-Length)
	 * will be sent if streaming began, while none will be sent otherwise.
	 * Implementations should flush the output buffer before sending data.
	 *
	 * @param array $params Parameters include:
	 *   - src      : source storage path
	 *   - headers  : list of additional HTTP headers to send if the file exists
	 *   - options  : HTTP request header map with lower case keys (since 1.28). Supports:
	 *                range             : format is "bytes=(\d*-\d*)"
	 *                if-modified-since : format is an HTTP date
	 *   - headless : do not send HTTP headers (including those of "headers") (since 1.28)
	 *   - latest   : use the latest available data
	 *   - allowOB  : preserve any output buffers (since 1.28)
	 * @return StatusValue
	 */
	abstract public function streamFile( array $params );

	/**
	 * Returns a file system file, identical in content to the file at a storage path.
	 * The file returned is either:
	 *   - a) A TempFSFile local copy of the file at a storage path in the backend.
	 *        The temporary copy will have the same extension as the source.
	 *        Temporary files may be purged when the file object falls out of scope.
	 *   - b) An FSFile pointing to the original file at a storage path in the backend.
	 *        This is applicable for backends layered directly on top of file systems.
	 *
	 * Never modify the returned file since it might be the original, it might be shared
	 * among multiple callers of this method, or the backend might internally keep FSFile
	 * references for deferred operations.
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return FSFile|null|false Local file copy or false (missing) or null (error)
	 */
	final public function getLocalReference( array $params ) {
		$fsFiles = $this->getLocalReferenceMulti( [ 'srcs' => [ $params['src'] ] ] + $params );

		return $fsFiles[$params['src']];
	}

	/**
	 * Like getLocalReference() except it takes an array of storage paths and
	 * yields an order-preserved map of storage paths to temporary local file copies.
	 *
	 * Never modify the returned files since they might be originals, they might be shared
	 * among multiple callers of this method, or the backend might internally keep FSFile
	 * references for deferred operations.
	 *
	 * @see FileBackend::getLocalReference()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 *   - parallelize : try to do operations in parallel when possible
	 * @return array Map of (path name => FSFile or false (missing) or null (error))
	 * @since 1.20
	 */
	abstract public function getLocalReferenceMulti( array $params );

	/**
	 * Get a local copy on disk of the file at a storage path in the backend.
	 * The temporary copy will have the same file extension as the source.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * Multiple calls to this method for the same path will create new copies.
	 *
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return TempFSFile|null|false Temporary local file copy or false (missing) or null (error)
	 */
	final public function getLocalCopy( array $params ) {
		$tmpFiles = $this->getLocalCopyMulti( [ 'srcs' => [ $params['src'] ] ] + $params );

		return $tmpFiles[$params['src']];
	}

	/**
	 * Like getLocalCopy() except it takes an array of storage paths and yields
	 * an order preserved-map of storage paths to temporary local file copies.
	 *
	 * Multiple calls to this method for the same path will create new copies.
	 *
	 * @see FileBackend::getLocalCopy()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 *   - parallelize : try to do operations in parallel when possible
	 * @return array Map of (path name => TempFSFile or false (missing) or null (error))
	 * @since 1.20
	 */
	abstract public function getLocalCopyMulti( array $params );

	/**
	 * Return an HTTP URL to a given file that requires no authentication to use.
	 * The URL may be pre-authenticated (via some token in the URL) and temporary.
	 * This will return null if the backend cannot make an HTTP URL for the file.
	 *
	 * This is useful for key/value stores when using scripts that seek around
	 * large files and those scripts (and the backend) support HTTP Range headers.
	 * Otherwise, one would need to use getLocalReference(), which involves loading
	 * the entire file on to local disk.
	 *
	 * @see FileBackend::TEMPURL_ERROR
	 *
	 * @param array $params Parameters include:
	 *   - src     : source storage path
	 *   - ttl     : lifetime (seconds) if pre-authenticated; default is 1 day
	 *   - latest  : use the latest available data
	 *   - method  : the allowed method; default GET
	 *   - ipRange : the allowed IP range; default unlimited
	 * @return string|null URL or null (not supported or I/O error)
	 * @since 1.21
	 */
	abstract public function getFileHttpUrl( array $params );

	/**
	 * Add a file to a Shellbox command as an input file.
	 *
	 * @param BoxedCommand $command
	 * @param string $boxedName
	 * @param array $params Parameters include:
	 *   - src    : source storage path
	 *   - latest : use the latest available data
	 * @return StatusValue
	 * @since 1.43
	 */
	abstract public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params );

	/**
	 * Check if a directory exists at a given storage path
	 *
	 * For backends using key/value stores, a directory is said to exist whenever
	 * there exist any files with paths using the given directory path as a prefix
	 * followed by a forward slash. For example, if there is a file called
	 * "mwstore://backend/container/dir/path.svg" then directories are said to exist
	 * at "mwstore://backend/container" and "mwstore://backend/container/dir". These
	 * can be thought of as "virtual" directories.
	 *
	 * Backends that directly use a filesystem layer might enumerate empty directories.
	 * The clean() method should always be used when files are deleted or moved if this
	 * is a concern. This is a trade-off to avoid write amplication/contention on file
	 * changes or read amplification when calling this method.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * @see FileBackend::EXISTENCE_ERROR
	 * @see FileBackend::clean()
	 *
	 * @param array $params Parameters include:
	 *   - dir : storage directory
	 * @return bool|null Whether a directory exists or null (I/O error)
	 * @since 1.20
	 */
	abstract public function directoryExists( array $params );

	/**
	 * Get an iterator to list *all* directories under a storage directory
	 *
	 * If the directory is of the form "mwstore://backend/container",
	 * then all directories in the container will be listed.
	 * If the directory is of form "mwstore://backend/container/dir",
	 * then all directories directly under that directory will be listed.
	 * Results will be storage directories relative to the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * Failures during iteration can result in FileBackendError exceptions (since 1.22).
	 *
	 * @see FileBackend::LIST_ERROR
	 * @see FileBackend::directoryExists()
	 *
	 * @param array $params Parameters include:
	 *   - dir     : storage directory
	 *   - topOnly : only return direct child dirs of the directory
	 * @return \Traversable|array|null Directory list enumerator or null (initial I/O error)
	 * @since 1.20
	 */
	abstract public function getDirectoryList( array $params );

	/**
	 * Same as FileBackend::getDirectoryList() except only lists
	 * directories that are immediately under the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * Failures during iteration can result in FileBackendError exceptions (since 1.22).
	 *
	 * @see FileBackend::LIST_ERROR
	 * @see FileBackend::directoryExists()
	 *
	 * @param array $params Parameters include:
	 *   - dir : storage directory
	 * @return \Traversable|array|null Directory list enumerator or null (initial I/O error)
	 * @since 1.20
	 */
	final public function getTopDirectoryList( array $params ) {
		return $this->getDirectoryList( [ 'topOnly' => true ] + $params );
	}

	/**
	 * Get an iterator to list *all* stored files under a storage directory
	 *
	 * If the directory is of the form "mwstore://backend/container", then all
	 * files in the container will be listed. If the directory is of form
	 * "mwstore://backend/container/dir", then all files under that directory will
	 * be listed. Results will be storage paths relative to the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * Failures during iteration can result in FileBackendError exceptions (since 1.22).
	 *
	 * @see FileBackend::LIST_ERROR
	 *
	 * @param array $params Parameters include:
	 *   - dir        : storage directory
	 *   - topOnly    : only return direct child files of the directory (since 1.20)
	 *   - adviseStat : set to true if stat requests will be made on the files (since 1.22)
	 *   - forWrite   : true if the list will inform a write operations (since 1.41)
	 * @return \Traversable|array|null File list enumerator or null (initial I/O error)
	 */
	abstract public function getFileList( array $params );

	/**
	 * Same as FileBackend::getFileList() except only lists
	 * files that are immediately under the given directory.
	 *
	 * Storage backends with eventual consistency might return stale data.
	 *
	 * Failures during iteration can result in FileBackendError exceptions (since 1.22).
	 *
	 * @see FileBackend::LIST_ERROR
	 *
	 * @param array $params Parameters include:
	 *   - dir        : storage directory
	 *   - adviseStat : set to true if stat requests will be made on the files (since 1.22)
	 * @return \Traversable|array|null File list enumerator or null on failure
	 * @since 1.20
	 */
	final public function getTopFileList( array $params ) {
		return $this->getFileList( [ 'topOnly' => true ] + $params );
	}

	/**
	 * Preload persistent file stat cache and property cache into in-process cache.
	 * This should be used when stat calls will be made on a known list of a many files.
	 *
	 * @see FileBackend::getFileStat()
	 *
	 * @param array $paths Storage paths
	 */
	abstract public function preloadCache( array $paths );

	/**
	 * Invalidate any in-process file stat and property cache.
	 * If $paths is given, then only the cache for those files will be cleared.
	 *
	 * @see FileBackend::getFileStat()
	 *
	 * @param array|null $paths Storage paths (optional)
	 */
	abstract public function clearCache( ?array $paths = null );

	/**
	 * Preload file stat information (concurrently if possible) into in-process cache.
	 *
	 * This should be used when stat calls will be made on a known list of a many files.
	 * This does not make use of the persistent file stat cache.
	 *
	 * @see FileBackend::getFileStat()
	 *
	 * @param array $params Parameters include:
	 *   - srcs        : list of source storage paths
	 *   - latest      : use the latest available data
	 * @return bool Whether all requests proceeded without I/O errors (since 1.24)
	 * @since 1.23
	 */
	abstract public function preloadFileStat( array $params );

	/**
	 * Lock the files at the given storage paths in the backend.
	 * This will either lock all the files or none (on failure).
	 *
	 * Callers should consider using getScopedFileLocks() instead.
	 *
	 * @param array $paths Storage paths
	 * @param int $type LockManager::LOCK_* constant
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.24)
	 * @return StatusValue
	 */
	final public function lockFiles( array $paths, $type, $timeout = 0 ) {
		$paths = array_map( [ self::class, 'normalizeStoragePath' ], $paths );

		return $this->wrapStatus( $this->lockManager->lock( $paths, $type, $timeout ) );
	}

	/**
	 * Unlock the files at the given storage paths in the backend.
	 *
	 * @param array $paths Storage paths
	 * @param int $type LockManager::LOCK_* constant
	 * @return StatusValue
	 */
	final public function unlockFiles( array $paths, $type ) {
		$paths = array_map( [ self::class, 'normalizeStoragePath' ], $paths );

		return $this->wrapStatus( $this->lockManager->unlock( $paths, $type ) );
	}

	/**
	 * Lock the files at the given storage paths in the backend.
	 * This will either lock all the files or none (on failure).
	 * On failure, the StatusValue object will be updated with errors.
	 *
	 * Once the return value goes out scope, the locks will be released and
	 * the StatusValue updated. Unlock fatals will not change the StatusValue "OK" value.
	 *
	 * @see ScopedLock::factory()
	 *
	 * @param array $paths List of storage paths or map of lock types to path lists
	 * @param int|string $type LockManager::LOCK_* constant or "mixed"
	 * @param StatusValue $status StatusValue to update on lock/unlock
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.24)
	 * @return ScopedLock|null RAII-style self-unlocking lock or null on failure
	 */
	final public function getScopedFileLocks(
		array $paths, $type, StatusValue $status, $timeout = 0
	) {
		if ( $type === 'mixed' ) {
			foreach ( $paths as &$typePaths ) {
				$typePaths = array_map( [ self::class, 'normalizeStoragePath' ], $typePaths );
			}
		} else {
			$paths = array_map( [ self::class, 'normalizeStoragePath' ], $paths );
		}

		return ScopedLock::factory( $this->lockManager, $paths, $type, $status, $timeout );
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
	 * @param array $ops List of file operations to FileBackend::doOperations()
	 * @param StatusValue $status StatusValue to update on lock/unlock
	 * @return ScopedLock|null RAII-style self-unlocking lock or null on failure
	 * @since 1.20
	 */
	abstract public function getScopedLocksForOps( array $ops, StatusValue $status );

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
	 * Get the storage path for the given container for this backend
	 *
	 * @param string $container Container name
	 * @return string Storage path
	 * @since 1.21
	 */
	final public function getContainerStoragePath( $container ) {
		return $this->getRootStoragePath() . "/{$container}";
	}

	/**
	 * Convert FSFile 'src' paths to string paths (with an 'srcRef' field set to the FSFile)
	 *
	 * The 'srcRef' field keeps any TempFSFile objects in scope for the backend to have it
	 * around as long it needs (which may vary greatly depending on configuration)
	 *
	 * @param array $ops File operation batch for FileBaclend::doOperations()
	 * @return array File operation batch
	 */
	protected function resolveFSFileObjects( array $ops ) {
		foreach ( $ops as &$op ) {
			$src = $op['src'] ?? null;
			if ( $src instanceof FSFile ) {
				$op['srcRef'] = $src;
				$op['src'] = $src->getPath();
			}
		}
		unset( $op );

		return $ops;
	}

	/**
	 * Check if a given path is a "mwstore://" path.
	 * This does not do any further validation or any existence checks.
	 *
	 * @param string|null $path
	 * @return bool
	 */
	final public static function isStoragePath( $path ) {
		return ( str_starts_with( $path ?? '', 'mwstore://' ) );
	}

	/**
	 * Split a storage path into a backend name, a container name,
	 * and a relative file path. The relative path may be the empty string.
	 * This does not do any path normalization or traversal checks.
	 *
	 * @param string $storagePath
	 * @return array (backend, container, rel object) or (null, null, null)
	 */
	final public static function splitStoragePath( $storagePath ) {
		if ( self::isStoragePath( $storagePath ) ) {
			// Remove the "mwstore://" prefix and split the path
			$parts = explode( '/', substr( $storagePath, 10 ), 3 );
			if ( count( $parts ) >= 2 && $parts[0] != '' && $parts[1] != '' ) {
				if ( count( $parts ) == 3 ) {
					return $parts; // e.g. "backend/container/path"
				} else {
					return [ $parts[0], $parts[1], '' ]; // e.g. "backend/container"
				}
			}
		}

		return [ null, null, null ];
	}

	/**
	 * Normalize a storage path by cleaning up directory separators.
	 * Returns null if the path is not of the format of a valid storage path.
	 *
	 * @param string $storagePath
	 * @return string|null Normalized storage path or null on failure
	 */
	final public static function normalizeStoragePath( $storagePath ) {
		[ $backend, $container, $relPath ] = self::splitStoragePath( $storagePath );
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
	 * @param string $storagePath
	 * @return string|null Parent storage path or null on failure
	 */
	final public static function parentStoragePath( $storagePath ) {
		// XXX dirname() depends on platform and locale! If nothing enforces that the storage path
		// doesn't contain characters like '\', behavior can vary by platform. We should use
		// explode() instead.
		$storagePath = dirname( $storagePath );
		[ , , $rel ] = self::splitStoragePath( $storagePath );

		return ( $rel === null ) ? null : $storagePath;
	}

	/**
	 * Get the final extension from a storage or FS path
	 *
	 * @param string $path
	 * @param string $case One of (rawcase, uppercase, lowercase) (since 1.24)
	 * @return string
	 */
	final public static function extensionFromPath( $path, $case = 'lowercase' ) {
		// This will treat a string starting with . as not having an extension, but store paths have
		// to start with 'mwstore://', so "garbage in, garbage out".
		$i = strrpos( $path, '.' );
		$ext = $i ? substr( $path, $i + 1 ) : '';

		if ( $case === 'lowercase' ) {
			$ext = strtolower( $ext );
		} elseif ( $case === 'uppercase' ) {
			$ext = strtoupper( $ext );
		}

		return $ext;
	}

	/**
	 * Check if a relative path has no directory traversals
	 *
	 * @param string $path
	 * @return bool
	 * @since 1.20
	 */
	final public static function isPathTraversalFree( $path ) {
		return ( self::normalizeContainerPath( $path ) !== null );
	}

	/**
	 * Build a Content-Disposition header value per RFC 6266.
	 *
	 * @param string $type One of (attachment, inline)
	 * @param string $filename Suggested file name (should not contain slashes)
	 * @return string
	 * @since 1.20
	 */
	final public static function makeContentDisposition( $type, $filename = '' ) {
		$parts = [];

		$type = strtolower( $type );
		if ( !in_array( $type, [ 'inline', 'attachment' ] ) ) {
			throw new InvalidArgumentException( "Invalid Content-Disposition type '$type'." );
		}
		$parts[] = $type;

		if ( $filename !== '' ) {
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
	 * @param string $path Storage path relative to a container
	 * @return string|null Normalized container path or null on failure
	 */
	final protected static function normalizeContainerPath( $path ) {
		// Normalize directory separators
		$path = strtr( $path, '\\', '/' );
		// Collapse any consecutive directory separators
		$path = preg_replace( '![/]{2,}!', '/', $path );
		// Remove any leading directory separator
		$path = ltrim( $path, '/' );
		// Use the same traversal protection as Title::secureAndSplit()
		if ( str_contains( $path, '.' ) ) {
			if (
				$path === '.' ||
				$path === '..' ||
				str_starts_with( $path, './' ) ||
				str_starts_with( $path, '../' ) ||
				str_contains( $path, '/./' ) ||
				str_contains( $path, '/../' )
			) {
				return null;
			}
		}

		return $path;
	}

	/**
	 * Yields the result of the status wrapper callback on either:
	 *   - StatusValue::newGood(), if this method is called without a message
	 *   - StatusValue::newFatal( ... ) with all parameters to this method, if a message was given
	 *
	 * @param null|string $message Message key
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return StatusValue
	 */
	final protected function newStatus( $message = null, ...$params ) {
		if ( $message !== null ) {
			$sv = StatusValue::newFatal( $message, ...$params );
		} else {
			$sv = StatusValue::newGood();
		}

		return $this->wrapStatus( $sv );
	}

	/**
	 * @param StatusValue $sv
	 * @return StatusValue Modified status or StatusValue subclass
	 */
	final protected function wrapStatus( StatusValue $sv ) {
		return $this->statusWrapper ? ( $this->statusWrapper )( $sv ) : $sv;
	}

	/**
	 * @param string $section
	 * @return ScopedCallback|null
	 */
	protected function scopedProfileSection( $section ) {
		return $this->profiler ? ( $this->profiler )( $section ) : null;
	}

	/**
	 * Default behavior of resetOutputBuffer().
	 * @codeCoverageIgnore
	 * @internal
	 */
	public static function resetOutputBufferTheDefaultWay() {
		// XXX According to documentation, ob_get_status() always returns a non-empty array and this
		// condition will always be true
		while ( ob_get_status() ) {
			if ( !ob_end_clean() ) {
				// Could not remove output buffer handler; abort now
				// to avoid getting in some kind of infinite loop.
				break;
			}
		}
	}

	/**
	 * Return options for use with HTTPFileStreamer.
	 *
	 * @internal
	 */
	public function getStreamerOptions(): array {
		return $this->streamerOptions;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( FileBackend::class, 'FileBackend' );
