<?php
/**
 * @defgroup Database Database
 *
 * This file deals with database interface functions
 * and query specifics/optimisations.
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
 * @ingroup Database
 */
namespace Wikimedia\Rdbms;

use BagOStuff;
use Exception;
use HashBagOStuff;
use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Relational database abstraction object
 *
 * @stable to extend
 * @ingroup Database
 * @since 1.28
 */
abstract class Database implements IDatabase, IMaintainableDatabase, LoggerAwareInterface {
	/** @var BagOStuff APC cache */
	protected $srvCache;
	/** @var LoggerInterface */
	protected $connLogger;
	/** @var LoggerInterface */
	protected $queryLogger;
	/** @var LoggerInterface */
	protected $replLogger;
	/** @var callable Error logging callback */
	protected $errorLogger;
	/** @var callable Deprecation logging callback */
	protected $deprecationLogger;
	/** @var callable|null */
	protected $profiler;
	/** @var TransactionProfiler */
	protected $trxProfiler;

	/** @var DatabaseDomain */
	protected $currentDomain;

	/** @var object|resource|null Database connection */
	protected $conn;

	/** @var IDatabase|null Lazy handle to the master DB this server replicates from */
	private $lazyMasterHandle;

	/** @var string Server that this instance is currently connected to */
	protected $server;
	/** @var string User that this instance is currently connected under the name of */
	protected $user;
	/** @var string Password used to establish the current connection */
	protected $password;
	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var string Agent name for query profiling */
	protected $agent;
	/** @var string Replication topology role of the server; one of the class ROLE_* constants */
	protected $topologyRole;
	/** @var string|null Host (or address) of the root master server for the replication topology */
	protected $topologyRootMaster;
	/** @var array Parameters used by initConnection() to establish a connection */
	protected $connectionParams;
	/** @var string[]|int[]|float[] SQL variables values to use for all new connections */
	protected $connectionVariables;
	/** @var int Row batch size to use for emulated INSERT SELECT queries */
	protected $nonNativeInsertSelectBatchSize;

	/** @var int Current bit field of class DBO_* constants */
	protected $flags;
	/** @var array Current LoadBalancer tracking information */
	protected $lbInfo = [];
	/** @var string Current SQL query delimiter */
	protected $delimiter = ';';
	/** @var array[] Current map of (table => (dbname, schema, prefix) map) */
	protected $tableAliases = [];
	/** @var string[] Current map of (index alias => index) */
	protected $indexAliases = [];
	/** @var array|null Current variables use for schema element placeholders */
	protected $schemaVars;

	/** @var string|bool|null Stashed value of html_errors INI setting */
	private $htmlErrors;
	/** @var int[] Prior flags member variable values */
	private $priorFlags = [];

	/** @var array Map of (name => 1) for locks obtained via lock() */
	protected $sessionNamedLocks = [];
	/** @var array Map of (table name => 1) for current TEMPORARY tables */
	protected $sessionTempTables = [];
	/** @var array Map of (table name => 1) for current TEMPORARY tables */
	protected $sessionDirtyTempTables = [];

	/** @var string ID of the active transaction or the empty string otherwise */
	private $trxShortId = '';
	/** @var int Transaction status */
	private $trxStatus = self::STATUS_TRX_NONE;
	/** @var Exception|null The last error that caused the status to become STATUS_TRX_ERROR */
	private $trxStatusCause;
	/** @var array|null Error details of the last statement-only rollback */
	private $trxStatusIgnoredCause;
	/** @var float|null UNIX timestamp at the time of BEGIN for the last transaction */
	private $trxTimestamp = null;
	/** @var float Replication lag estimate at the time of BEGIN for the last transaction */
	private $trxReplicaLag = null;
	/** @var string Name of the function that start the last transaction */
	private $trxFname = null;
	/** @var bool Whether possible write queries were done in the last transaction started */
	private $trxDoneWrites = false;
	/** @var bool Whether the current transaction was started implicitly due to DBO_TRX */
	private $trxAutomatic = false;
	/** @var int Counter for atomic savepoint identifiers (reset with each transaction) */
	private $trxAtomicCounter = 0;
	/** @var array List of (name, unique ID, savepoint ID) for each active atomic section level */
	private $trxAtomicLevels = [];
	/** @var bool Whether the current transaction was started implicitly by startAtomic() */
	private $trxAutomaticAtomic = false;
	/** @var string[] Write query callers of the current transaction */
	private $trxWriteCallers = [];
	/** @var float Seconds spent in write queries for the current transaction */
	private $trxWriteDuration = 0.0;
	/** @var int Number of write queries for the current transaction */
	private $trxWriteQueryCount = 0;
	/** @var int Number of rows affected by write queries for the current transaction */
	private $trxWriteAffectedRows = 0;
	/** @var float Like trxWriteQueryCount but excludes lock-bound, easy to replicate, queries */
	private $trxWriteAdjDuration = 0.0;
	/** @var int Number of write queries counted in trxWriteAdjDuration */
	private $trxWriteAdjQueryCount = 0;
	/** @var array[] List of (callable, method name, atomic section id) */
	private $trxIdleCallbacks = [];
	/** @var array[] List of (callable, method name, atomic section id) */
	private $trxPreCommitCallbacks = [];
	/**
	 * @var array[] List of (callable, method name, atomic section id)
	 * @phan-var array<array{0:callable,1:string,2:AtomicSectionIdentifier|null}>
	 */
	private $trxEndCallbacks = [];
	/** @var array[] List of (callable, method name, atomic section id) */
	private $trxSectionCancelCallbacks = [];
	/** @var callable[] Map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var bool Whether to suppress triggering of transaction end callbacks */
	private $trxEndCallbacksSuppressed = false;

	/** @var integer|null Rows affected by the last query to query() or its CRUD wrappers */
	protected $affectedRowCount;

	/** @var float UNIX timestamp */
	private $lastPing = 0.0;
	/** @var string The last SQL query attempted */
	private $lastQuery = '';
	/** @var float|bool UNIX timestamp of last write query */
	private $lastWriteTime = false;
	/** @var string|bool */
	private $lastPhpError = false;
	/** @var float Query round trip time estimate */
	private $lastRoundTripEstimate = 0.0;

	/** @var int|null Integer ID of the managing LBFactory instance or null if none */
	private $ownerId;

	/** @var string Whether the database is a file on disk */
	public const ATTR_DB_IS_FILE = 'db-is-file';
	/** @var string Lock granularity is on the level of the entire database */
	public const ATTR_DB_LEVEL_LOCKING = 'db-level-locking';
	/** @var string The SCHEMA keyword refers to a grouping of tables in a database */
	public const ATTR_SCHEMAS_AS_TABLE_GROUPS = 'supports-schemas';

	/** @var int New Database instance will not be connected yet when returned */
	public const NEW_UNCONNECTED = 0;
	/** @var int New Database instance will already be connected when returned */
	public const NEW_CONNECTED = 1;

	/** @var int Transaction is in a error state requiring a full or savepoint rollback */
	public const STATUS_TRX_ERROR = 1;
	/** @var int Transaction is active and in a normal state */
	public const STATUS_TRX_OK = 2;
	/** @var int No transaction is active */
	public const STATUS_TRX_NONE = 3;

	/** @var string Idiom used when a cancelable atomic section started the transaction */
	private static $NOT_APPLICABLE = 'n/a';
	/** @var string Prefix to the atomic section counter used to make savepoint IDs */
	private static $SAVEPOINT_PREFIX = 'wikimedia_rdbms_atomic';

	/** @var int Writes to this temporary table do not affect lastDoneWrites() */
	private static $TEMP_NORMAL = 1;
	/** @var int Writes to this temporary table effect lastDoneWrites() */
	private static $TEMP_PSEUDO_PERMANENT = 2;

	/** @var int Number of times to re-try an operation in case of deadlock */
	private static $DEADLOCK_TRIES = 4;
	/** @var int Minimum time to wait before retry, in microseconds */
	private static $DEADLOCK_DELAY_MIN = 500000;
	/** @var int Maximum time to wait before retry */
	private static $DEADLOCK_DELAY_MAX = 1500000;

	/** @var int How long before it is worth doing a dummy query to test the connection */
	private static $PING_TTL = 1.0;
	/** @var string Dummy SQL query */
	private static $PING_QUERY = 'SELECT 1 AS ping';

	/** @var float Guess of how many seconds it takes to replicate a small insert */
	private static $TINY_WRITE_SEC = 0.010;
	/** @var float Consider a write slow if it took more than this many seconds */
	private static $SLOW_WRITE_SEC = 0.500;
	/** @var float Assume an insert of this many rows or less should be fast to replicate */
	private static $SMALL_WRITE_ROWS = 100;

	/** @var string[] List of DBO_* flags that can be changed after connection */
	protected static $MUTABLE_FLAGS = [
		'DBO_DEBUG',
		'DBO_NOBUFFER',
		'DBO_TRX',
		'DBO_DDLMODE',
	];
	/** @var int Bit field of all DBO_* flags that can be changed after connection */
	protected static $DBO_MUTABLE = (
		self::DBO_DEBUG | self::DBO_NOBUFFER | self::DBO_TRX | self::DBO_DDLMODE
	);

	/**
	 * @note exceptions for missing libraries/drivers should be thrown in initConnection()
	 * @stable to call
	 * @param array $params Parameters passed from Database::factory()
	 */
	public function __construct( array $params ) {
		$this->connectionParams = [
			'host' => strlen( $params['host'] ) ? $params['host'] : null,
			'user' => strlen( $params['user'] ) ? $params['user'] : null,
			'dbname' => strlen( $params['dbname'] ) ? $params['dbname'] : null,
			'schema' => strlen( $params['schema'] ) ? $params['schema'] : null,
			'password' => is_string( $params['password'] ) ? $params['password'] : null,
			'tablePrefix' => (string)$params['tablePrefix']
		];

		$this->lbInfo = $params['lbInfo'] ?? [];
		$this->lazyMasterHandle = $params['lazyMasterHandle'] ?? null;
		$this->connectionVariables = $params['variables'] ?? [];

		$this->flags = (int)$params['flags'];
		$this->cliMode = (bool)$params['cliMode'];
		$this->agent = (string)$params['agent'];
		$this->topologyRole = (string)$params['topologyRole'];
		$this->topologyRootMaster = (string)$params['topologicalMaster'];
		$this->nonNativeInsertSelectBatchSize = $params['nonNativeInsertSelectBatchSize'] ?? 10000;

		$this->srvCache = $params['srvCache'];
		$this->profiler = is_callable( $params['profiler'] ) ? $params['profiler'] : null;
		$this->trxProfiler = $params['trxProfiler'];
		$this->connLogger = $params['connLogger'];
		$this->queryLogger = $params['queryLogger'];
		$this->replLogger = $params['replLogger'];
		$this->errorLogger = $params['errorLogger'];
		$this->deprecationLogger = $params['deprecationLogger'];

		// Set initial dummy domain until open() sets the final DB/prefix
		$this->currentDomain = new DatabaseDomain(
			$params['dbname'] != '' ? $params['dbname'] : null,
			$params['schema'] != '' ? $params['schema'] : null,
			$params['tablePrefix']
		);

		$this->ownerId = $params['ownerId'] ?? null;
	}

	/**
	 * Initialize the connection to the database over the wire (or to local files)
	 *
	 * @throws LogicException
	 * @throws InvalidArgumentException
	 * @throws DBConnectionError
	 * @since 1.31
	 */
	final public function initConnection() {
		if ( $this->isOpen() ) {
			throw new LogicException( __METHOD__ . ': already connected' );
		}
		// Establish the connection
		$this->doInitConnection();
	}

	/**
	 * Actually connect to the database over the wire (or to local files)
	 *
	 * @throws DBConnectionError
	 * @since 1.31
	 */
	protected function doInitConnection() {
		$this->open(
			$this->connectionParams['host'],
			$this->connectionParams['user'],
			$this->connectionParams['password'],
			$this->connectionParams['dbname'],
			$this->connectionParams['schema'],
			$this->connectionParams['tablePrefix']
		);
	}

	/**
	 * Open a new connection to the database (closing any existing one)
	 *
	 * @param string|null $server Database server host
	 * @param string|null $user Database user name
	 * @param string|null $password Database user password
	 * @param string|null $dbName Database name
	 * @param string|null $schema Database schema name
	 * @param string $tablePrefix Table prefix
	 * @throws DBConnectionError
	 */
	abstract protected function open( $server, $user, $password, $dbName, $schema, $tablePrefix );

	/**
	 * Construct a Database subclass instance given a database type and parameters
	 *
	 * This also connects to the database immediately upon object construction
	 *
	 * @param string $type A possible DB type (sqlite, mysql, postgres,...)
	 * @param array $params Parameter map with keys:
	 *   - host : The hostname of the DB server
	 *   - user : The name of the database user the client operates under
	 *   - password : The password for the database user
	 *   - dbname : The name of the database to use where queries do not specify one.
	 *      The database must exist or an error might be thrown. Setting this to the empty string
	 *      will avoid any such errors and make the handle have no implicit database scope. This is
	 *      useful for queries like SHOW STATUS, CREATE DATABASE, or DROP DATABASE. Note that a
	 *      "database" in Postgres is rougly equivalent to an entire MySQL server. This the domain
	 *      in which user names and such are defined, e.g. users are database-specific in Postgres.
	 *   - schema : The database schema to use (if supported). A "schema" in Postgres is roughly
	 *      equivalent to a "database" in MySQL. Note that MySQL and SQLite do not use schemas.
	 *   - tablePrefix : Optional table prefix that is implicitly added on to all table names
	 *      recognized in queries. This can be used in place of schemas for handle site farms.
	 *   - flags : Optional bit field of DBO_* constants that define connection, protocol,
	 *      buffering, and transaction behavior. It is STRONGLY adviced to leave the DBO_DEFAULT
	 *      flag in place UNLESS this this database simply acts as a key/value store.
	 *   - driver: Optional name of a specific DB client driver. For MySQL, there is only the
	 *      'mysqli' driver; the old one 'mysql' has been removed.
	 *   - variables: Optional map of session variables to set after connecting. This can be
	 *      used to adjust lock timeouts or encoding modes and the like.
	 *   - topologyRole: Optional IDatabase::ROLE_* constant for the server.
	 *   - topologicalMaster: Optional name of the master server within the replication topology.
	 *   - lbInfo: Optional map of field/values for the managing load balancer instance.
	 *      The "master" and "replica" fields are used to flag the replication role of this
	 *      database server and whether methods like getLag() should actually issue queries.
	 *   - lazyMasterHandle: lazy-connecting IDatabase handle to the master DB for the cluster
	 *      that this database belongs to. This is used for replication status purposes.
	 *   - connLogger: Optional PSR-3 logger interface instance.
	 *   - queryLogger: Optional PSR-3 logger interface instance.
	 *   - profiler : Optional callback that takes a section name argument and returns
	 *      a ScopedCallback instance that ends the profile section in its destructor.
	 *      These will be called in query(), using a simplified version of the SQL that
	 *      also includes the agent as a SQL comment.
	 *   - trxProfiler: Optional TransactionProfiler instance.
	 *   - errorLogger: Optional callback that takes an Exception and logs it.
	 *   - deprecationLogger: Optional callback that takes a string and logs it.
	 *   - cliMode: Whether to consider the execution context that of a CLI script.
	 *   - agent: Optional name used to identify the end-user in query profiling/logging.
	 *   - srvCache: Optional BagOStuff instance to an APC-style cache.
	 *   - nonNativeInsertSelectBatchSize: Optional batch size for non-native INSERT SELECT.
	 *   - ownerId: Optional integer ID of a LoadBalancer instance that manages this instance.
	 * @param int $connect One of the class constants (NEW_CONNECTED, NEW_UNCONNECTED) [optional]
	 * @return Database|null If the database driver or extension cannot be found
	 * @throws InvalidArgumentException If the database driver or extension cannot be found
	 * @since 1.18
	 */
	final public static function factory( $type, $params = [], $connect = self::NEW_CONNECTED ) {
		$class = self::getClass( $type, $params['driver'] ?? null );

		if ( class_exists( $class ) && is_subclass_of( $class, IDatabase::class ) ) {
			$params += [
				// Default configuration
				'host' => null,
				'user' => null,
				'password' => null,
				'dbname' => null,
				'schema' => null,
				'tablePrefix' => '',
				'flags' => 0,
				'variables' => [],
				'lbInfo' => [],
				'cliMode' => ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ),
				'agent' => '',
				'ownerId' => null,
				'topologyRole' => null,
				'topologicalMaster' => null,
				// Objects and callbacks
				'lazyMasterHandle' => $params['lazyMasterHandle'] ?? null,
				'srvCache' => $params['srvCache'] ?? new HashBagOStuff(),
				'profiler' => $params['profiler'] ?? null,
				'trxProfiler' => $params['trxProfiler'] ?? new TransactionProfiler(),
				'connLogger' => $params['connLogger'] ?? new NullLogger(),
				'queryLogger' => $params['queryLogger'] ?? new NullLogger(),
				'replLogger' => $params['replLogger'] ?? new NullLogger(),
				'errorLogger' => $params['errorLogger'] ?? function ( Throwable $e ) {
					trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
				},
				'deprecationLogger' => $params['deprecationLogger'] ?? function ( $msg ) {
					trigger_error( $msg, E_USER_DEPRECATED );
				}
			];

			/** @var Database $conn */
			$conn = new $class( $params );
			if ( $connect === self::NEW_CONNECTED ) {
				$conn->initConnection();
			}
		} else {
			$conn = null;
		}

		return $conn;
	}

	/**
	 * @param string $dbType A possible DB type (sqlite, mysql, postgres,...)
	 * @param string|null $driver Optional name of a specific DB client driver
	 * @return array Map of (Database::ATTR_* constant => value) for all such constants
	 * @throws InvalidArgumentException
	 * @since 1.31
	 */
	final public static function attributesFromType( $dbType, $driver = null ) {
		static $defaults = [
			self::ATTR_DB_IS_FILE => false,
			self::ATTR_DB_LEVEL_LOCKING => false,
			self::ATTR_SCHEMAS_AS_TABLE_GROUPS => false
		];

		$class = self::getClass( $dbType, $driver );

		return call_user_func( [ $class, 'getAttributes' ] ) + $defaults;
	}

	/**
	 * @param string $dbType A possible DB type (sqlite, mysql, postgres,...)
	 * @param string|null $driver Optional name of a specific DB client driver
	 * @return string Database subclass name to use
	 * @throws InvalidArgumentException
	 */
	private static function getClass( $dbType, $driver = null ) {
		// For database types with built-in support, the below maps type to IDatabase
		// implementations. For types with multiple driver implementations (PHP extensions),
		// an array can be used, keyed by extension name. In case of an array, the
		// optional 'driver' parameter can be used to force a specific driver. Otherwise,
		// we auto-detect the first available driver. For types without built-in support,
		// an class named "Database<Type>" us used, eg. DatabaseFoo for type 'foo'.
		static $builtinTypes = [
			'mysql' => [ 'mysqli' => DatabaseMysqli::class ],
			'sqlite' => DatabaseSqlite::class,
			'postgres' => DatabasePostgres::class,
		];

		$dbType = strtolower( $dbType );
		$class = false;

		if ( isset( $builtinTypes[$dbType] ) ) {
			$possibleDrivers = $builtinTypes[$dbType];
			if ( is_string( $possibleDrivers ) ) {
				$class = $possibleDrivers;
			} elseif ( (string)$driver !== '' ) {
				if ( !isset( $possibleDrivers[$driver] ) ) {
					throw new InvalidArgumentException( __METHOD__ .
						" type '$dbType' does not support driver '{$driver}'" );
				}

				$class = $possibleDrivers[$driver];
			} else {
				foreach ( $possibleDrivers as $posDriver => $possibleClass ) {
					if ( extension_loaded( $posDriver ) ) {
						$class = $possibleClass;
						break;
					}
				}
			}
		} else {
			$class = 'Database' . ucfirst( $dbType );
		}

		if ( $class === false ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no viable database extension found for type '$dbType'" );
		}

		return $class;
	}

	/**
	 * @stable to override
	 * @return array Map of (Database::ATTR_* constant => value)
	 * @since 1.31
	 */
	protected static function getAttributes() {
		return [];
	}

	/**
	 * Set the PSR-3 logger interface to use for query logging. (The logger
	 * interfaces for connection logging and error logging can be set with the
	 * constructor.)
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->queryLogger = $logger;
	}

	public function getServerInfo() {
		return $this->getServerVersion();
	}

	public function getTopologyRole() {
		return $this->topologyRole;
	}

	public function getTopologyRootMaster() {
		return $this->topologyRootMaster;
	}

	final public function trxLevel() {
		return ( $this->trxShortId != '' ) ? 1 : 0;
	}

	public function trxTimestamp() {
		return $this->trxLevel() ? $this->trxTimestamp : null;
	}

	/**
	 * @return int One of the STATUS_TRX_* class constants
	 * @since 1.31
	 */
	public function trxStatus() {
		return $this->trxStatus;
	}

	public function tablePrefix( $prefix = null ) {
		$old = $this->currentDomain->getTablePrefix();
		if ( $prefix !== null ) {
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				$this->currentDomain->getSchema(),
				$prefix
			);
		}

		return $old;
	}

	public function dbSchema( $schema = null ) {
		if ( strlen( $schema ) && $this->getDBname() === null ) {
			throw new DBUnexpectedError( $this, "Cannot set schema to '$schema'; no database set" );
		}

		$old = $this->currentDomain->getSchema();
		if ( $schema !== null ) {
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				// DatabaseDomain uses null for unspecified schemas
				strlen( $schema ) ? $schema : null,
				$this->currentDomain->getTablePrefix()
			);
		}

		return (string)$old;
	}

	/**
	 * @stable to override
	 * @return string Schema to use to qualify relations in queries
	 */
	protected function relationSchemaQualifier() {
		return $this->dbSchema();
	}

	public function getLBInfo( $name = null ) {
		if ( $name === null ) {
			return $this->lbInfo;
		}

		if ( array_key_exists( $name, $this->lbInfo ) ) {
			return $this->lbInfo[$name];
		}

		return null;
	}

	public function setLBInfo( $nameOrArray, $value = null ) {
		if ( is_array( $nameOrArray ) ) {
			$this->lbInfo = $nameOrArray;
		} elseif ( is_string( $nameOrArray ) ) {
			if ( $value !== null ) {
				$this->lbInfo[$nameOrArray] = $value;
			} else {
				unset( $this->lbInfo[$nameOrArray] );
			}
		} else {
			throw new InvalidArgumentException( "Got non-string key" );
		}
	}

	/**
	 * Get a handle to the master server of the cluster to which this server belongs
	 *
	 * @return IDatabase|null
	 * @since 1.27
	 */
	protected function getLazyMasterHandle() {
		return $this->lazyMasterHandle;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function implicitOrderby() {
		return true;
	}

	public function lastQuery() {
		return $this->lastQuery;
	}

	public function lastDoneWrites() {
		return $this->lastWriteTime ?: false;
	}

	public function writesPending() {
		return $this->trxLevel() && $this->trxDoneWrites;
	}

	public function writesOrCallbacksPending() {
		return $this->trxLevel() && (
			$this->trxDoneWrites ||
			$this->trxIdleCallbacks ||
			$this->trxPreCommitCallbacks ||
			$this->trxEndCallbacks ||
			$this->trxSectionCancelCallbacks
		);
	}

	public function preCommitCallbacksPending() {
		return $this->trxLevel() && $this->trxPreCommitCallbacks;
	}

	/**
	 * @return string|null
	 */
	final protected function getTransactionRoundId() {
		// If transaction round participation is enabled, see if one is active
		if ( $this->getFlag( self::DBO_TRX ) ) {
			$id = $this->getLBInfo( self::LB_TRX_ROUND_ID );

			return is_string( $id ) ? $id : null;
		}

		return null;
	}

	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL ) {
		if ( !$this->trxLevel() ) {
			return false;
		} elseif ( !$this->trxDoneWrites ) {
			return 0.0;
		}

		switch ( $type ) {
			case self::ESTIMATE_DB_APPLY:
				return $this->pingAndCalculateLastTrxApplyTime();
			default: // everything
				return $this->trxWriteDuration;
		}
	}

	/**
	 * @return float Time to apply writes to replicas based on trxWrite* fields
	 */
	private function pingAndCalculateLastTrxApplyTime() {
		$this->ping( $rtt );

		$rttAdjTotal = $this->trxWriteAdjQueryCount * $rtt;
		$applyTime = max( $this->trxWriteAdjDuration - $rttAdjTotal, 0 );
		// For omitted queries, make them count as something at least
		$omitted = $this->trxWriteQueryCount - $this->trxWriteAdjQueryCount;
		$applyTime += self::$TINY_WRITE_SEC * $omitted;

		return $applyTime;
	}

	public function pendingWriteCallers() {
		return $this->trxLevel() ? $this->trxWriteCallers : [];
	}

	public function pendingWriteRowsAffected() {
		return $this->trxWriteAffectedRows;
	}

	/**
	 * List the methods that have write queries or callbacks for the current transaction
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @return string[]
	 * @since 1.32
	 */
	public function pendingWriteAndCallbackCallers() {
		$fnames = $this->pendingWriteCallers();
		foreach ( [
			$this->trxIdleCallbacks,
			$this->trxPreCommitCallbacks,
			$this->trxEndCallbacks,
			$this->trxSectionCancelCallbacks
		] as $callbacks ) {
			foreach ( $callbacks as $callback ) {
				$fnames[] = $callback[1];
			}
		}

		return $fnames;
	}

	/**
	 * @return string
	 */
	private function flatAtomicSectionList() {
		return array_reduce( $this->trxAtomicLevels, function ( $accum, $v ) {
			return $accum === null ? $v[0] : "$accum, " . $v[0];
		} );
	}

	public function isOpen() {
		return (bool)$this->conn;
	}

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::$DBO_MUTABLE ) {
			throw new DBUnexpectedError(
				$this,
				"Got $flag (allowed: " . implode( ', ', static::$MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			array_push( $this->priorFlags, $this->flags );
		}

		$this->flags |= $flag;
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::$DBO_MUTABLE ) {
			throw new DBUnexpectedError(
				$this,
				"Got $flag (allowed: " . implode( ', ', static::$MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			array_push( $this->priorFlags, $this->flags );
		}

		$this->flags &= ~$flag;
	}

	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		if ( !$this->priorFlags ) {
			return;
		}

		if ( $state === self::RESTORE_INITIAL ) {
			$this->flags = reset( $this->priorFlags );
			$this->priorFlags = [];
		} else {
			$this->flags = array_pop( $this->priorFlags );
		}
	}

	public function getFlag( $flag ) {
		return ( ( $this->flags & $flag ) === $flag );
	}

	public function getDomainID() {
		return $this->currentDomain->getId();
	}

	/**
	 * Get information about an index into an object
	 *
	 * @stable to override
	 * @param string $table Table name
	 * @param string $index Index name
	 * @param string $fname Calling function name
	 * @return mixed Database-specific index description class or false if the index does not exist
	 */
	abstract public function indexInfo( $table, $index, $fname = __METHOD__ );

	/**
	 * Wrapper for addslashes()
	 *
	 * @stable to override
	 * @param string $s String to be slashed.
	 * @return string Slashed string.
	 */
	abstract public function strencode( $s );

	/**
	 * Set a custom error handler for logging errors during database connection
	 */
	protected function installErrorHandler() {
		$this->lastPhpError = false;
		$this->htmlErrors = ini_set( 'html_errors', '0' );
		set_error_handler( [ $this, 'connectionErrorLogger' ] );
	}

	/**
	 * Restore the previous error handler and return the last PHP error for this DB
	 *
	 * @return bool|string
	 */
	protected function restoreErrorHandler() {
		restore_error_handler();
		if ( $this->htmlErrors !== false ) {
			ini_set( 'html_errors', $this->htmlErrors );
		}

		return $this->getLastPHPError();
	}

	/**
	 * @return string|bool Last PHP error for this DB (typically connection errors)
	 */
	protected function getLastPHPError() {
		if ( $this->lastPhpError ) {
			$error = preg_replace( '!\[<a.*</a>\]!', '', $this->lastPhpError );
			$error = preg_replace( '!^.*?:\s?(.*)$!', '$1', $error );

			return $error;
		}

		return false;
	}

	/**
	 * Error handler for logging errors during database connection
	 * This method should not be used outside of Database classes
	 *
	 * @param int $errno
	 * @param string $errstr
	 */
	public function connectionErrorLogger( $errno, $errstr ) {
		$this->lastPhpError = $errstr;
	}

	/**
	 * Create a log context to pass to PSR-3 logger functions.
	 *
	 * @param array $extras Additional data to add to context
	 * @return array
	 */
	protected function getLogContext( array $extras = [] ) {
		return array_merge(
			[
				'db_server' => $this->server,
				'db_name' => $this->getDBname(),
				'db_user' => $this->user,
			],
			$extras
		);
	}

	final public function close( $fname = __METHOD__, $owner = null ) {
		$error = null; // error to throw after disconnecting

		$wasOpen = (bool)$this->conn;
		// This should mostly do nothing if the connection is already closed
		if ( $this->conn ) {
			// Roll back any dangling transaction first
			if ( $this->trxLevel() ) {
				if ( $this->trxAtomicLevels ) {
					// Cannot let incomplete atomic sections be committed
					$levels = $this->flatAtomicSectionList();
					$error = "$fname: atomic sections $levels are still open";
				} elseif ( $this->trxAutomatic ) {
					// Only the connection manager can commit non-empty DBO_TRX transactions
					// (empty ones we can silently roll back)
					if ( $this->writesOrCallbacksPending() ) {
						$error = "$fname: " .
							"expected mass rollback of all peer transactions (DBO_TRX set)";
					}
				} else {
					// Manual transactions should have been committed or rolled
					// back, even if empty.
					$error = "$fname: transaction is still open (from {$this->trxFname})";
				}

				if ( $this->trxEndCallbacksSuppressed && $error === null ) {
					$error = "$fname: callbacks are suppressed; cannot properly commit";
				}

				// Rollback the changes and run any callbacks as needed
				$this->rollback( __METHOD__, self::FLUSHING_INTERNAL );
			}

			// Close the actual connection in the binding handle
			$closed = $this->closeConnection();
		} else {
			$closed = true; // already closed; nothing to do
		}

		$this->conn = null;

		// Log or throw any unexpected errors after having disconnected
		if ( $error !== null ) {
			// T217819, T231443: if this is probably just LoadBalancer trying to recover from
			// errors and shutdown, then log any problems and move on since the request has to
			// end one way or another. Throwing errors is not very useful at some point.
			if ( $this->ownerId !== null && $owner === $this->ownerId ) {
				$this->queryLogger->error( $error );
			} else {
				throw new DBUnexpectedError( $this, $error );
			}
		}

		// Note that various subclasses call close() at the start of open(), which itself is
		// called by replaceLostConnection(). In that case, just because onTransactionResolution()
		// callbacks are pending does not mean that an exception should be thrown. Rather, they
		// will be executed after the reconnection step.
		if ( $wasOpen ) {
			// Sanity check that no callbacks are dangling
			$fnames = $this->pendingWriteAndCallbackCallers();
			if ( $fnames ) {
				throw new RuntimeException(
					"Transaction callbacks are still pending: " . implode( ', ', $fnames )
				);
			}
		}

		return $closed;
	}

	/**
	 * Make sure there is an open connection handle (alive or not) as a sanity check
	 *
	 * This guards against fatal errors to the binding handle not being defined
	 * in cases where open() was never called or close() was already called
	 *
	 * @throws DBUnexpectedError
	 */
	final protected function assertHasConnectionHandle() {
		if ( !$this->isOpen() ) {
			throw new DBUnexpectedError( $this, "DB connection was already closed" );
		}
	}

	/**
	 * Make sure that this server is not marked as a replica nor read-only as a sanity check
	 *
	 * @throws DBReadOnlyError
	 */
	protected function assertIsWritableMaster() {
		$info = $this->getReadOnlyReason();
		if ( $info ) {
			list( $reason, $source ) = $info;
			if ( $source === 'role' ) {
				throw new DBReadOnlyRoleError( $this, "Database is read-only: $reason" );
			} else {
				throw new DBReadOnlyError( $this, "Database is read-only: $reason" );
			}
		}
	}

	/**
	 * Closes underlying database connection
	 * @return bool Whether connection was closed successfully
	 * @since 1.20
	 */
	abstract protected function closeConnection();

	/**
	 * Run a query and return a DBMS-dependent wrapper or boolean
	 *
	 * This is meant to handle the basic command of actually sending a query to the
	 * server via the driver. No implicit transaction, reconnection, nor retry logic
	 * should happen here. The higher level query() method is designed to handle those
	 * sorts of concerns. This method should not trigger such higher level methods.
	 *
	 * The lastError() and lastErrno() methods should meaningfully reflect what error,
	 * if any, occurred during the last call to this method. Methods like executeQuery(),
	 * query(), select(), insert(), update(), delete(), and upsert() implement their calls
	 * to doQuery() such that an immediately subsequent call to lastError()/lastErrno()
	 * meaningfully reflects any error that occurred during that public query method call.
	 *
	 * For SELECT queries, this returns either:
	 *   - a) A driver-specific value/resource, only on success. This can be iterated
	 *        over by calling fetchObject()/fetchRow() until there are no more rows.
	 *        Alternatively, the result can be passed to resultObject() to obtain an
	 *        IResultWrapper instance which can then be iterated over via "foreach".
	 *   - b) False, on any query failure
	 *
	 * For non-SELECT queries, this returns either:
	 *   - a) A driver-specific value/resource, only on success
	 *   - b) True, only on success (e.g. no meaningful result other than "OK")
	 *   - c) False, on any query failure
	 *
	 * @param string $sql SQL query
	 * @return mixed|bool An object, resource, or true on success; false on failure
	 */
	abstract protected function doQuery( $sql );

	/**
	 * Determine whether a query writes to the DB. When in doubt, this returns true.
	 *
	 * Main use cases:
	 *
	 * - Subsequent web requests should not need to wait for replication from
	 *   the master position seen by this web request, unless this request made
	 *   changes to the master. This is handled by ChronologyProtector by checking
	 *   doneWrites() at the end of the request. doneWrites() returns true if any
	 *   query set lastWriteTime; which query() does based on isWriteQuery().
	 *
	 * - Reject write queries to replica DBs, in query().
	 *
	 * @param string $sql
	 * @param int $flags Query flags to query()
	 * @return bool
	 */
	protected function isWriteQuery( $sql, $flags ) {
		if (
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_ROWS ) ||
			$this->fieldHasBit( $flags, self::QUERY_CHANGE_SCHEMA )
		) {
			return true;
		} elseif ( $this->fieldHasBit( $flags, self::QUERY_CHANGE_NONE ) ) {
			return false;
		}
		// BEGIN and COMMIT queries are considered read queries here.
		// Database backends and drivers (MySQL, MariaDB, php-mysqli) generally
		// treat these as write queries, in that their results have "affected rows"
		// as meta data as from writes, instead of "num rows" as from reads.
		// But, we treat them as read queries because when reading data (from
		// either replica or master) we use transactions to enable repeatable-read
		// snapshots, which ensures we get consistent results from the same snapshot
		// for all queries within a request. Use cases:
		// - Treating these as writes would trigger ChronologyProtector (see method doc).
		// - We use this method to reject writes to replicas, but we need to allow
		//   use of transactions on replicas for read snapshots. This is fine given
		//   that transactions by themselves don't make changes, only actual writes
		//   within the transaction matter, which we still detect.
		return !preg_match(
			'/^\s*(?:SELECT|BEGIN|ROLLBACK|COMMIT|SAVEPOINT|RELEASE|SET|SHOW|EXPLAIN|USE|\(SELECT)\b/i',
			$sql
		);
	}

	/**
	 * @param string $sql
	 * @return string|null
	 */
	protected function getQueryVerb( $sql ) {
		return preg_match( '/^\s*([a-z]+)/i', $sql, $m ) ? strtoupper( $m[1] ) : null;
	}

	/**
	 * Determine whether a SQL statement is sensitive to isolation level.
	 *
	 * A SQL statement is considered transactable if its result could vary
	 * depending on the transaction isolation level. Operational commands
	 * such as 'SET' and 'SHOW' are not considered to be transactable.
	 *
	 * Main purpose: Used by query() to decide whether to begin a transaction
	 * before the current query (in DBO_TRX mode, on by default).
	 *
	 * @stable to override
	 * @param string $sql
	 * @return bool
	 */
	protected function isTransactableQuery( $sql ) {
		return !in_array(
			$this->getQueryVerb( $sql ),
			[ 'BEGIN', 'ROLLBACK', 'COMMIT', 'SET', 'SHOW', 'CREATE', 'ALTER', 'USE', 'SHOW' ],
			true
		);
	}

	/**
	 * @param string $sql SQL query
	 * @param bool $pseudoPermanent Treat any table from CREATE TEMPORARY as pseudo-permanent
	 * @return array[] List of change n-tuples with:
	 *   - int: self::TEMP_* constant for temp table operations
	 *   - string: SQL query verb from $sql
	 *   - string: Name of the temp table changed in $sql
	 */
	protected function getTempTableWrites( $sql, $pseudoPermanent ) {
		// Regexes for basic queries that can create/change/drop temporary tables.
		// For simplicity, this only looks for tables with sane, alphanumeric, names;
		// temporary tables only need simple programming names anyway.
		static $regexes = null;
		if ( $regexes === null ) {
			// Regex with a group for quoted table 0 and a group for quoted tables 1..N
			$qts = '((?:\w+|`\w+`|\'\w+\'|"\w+")(?:\s*,\s*(?:\w+|`\w+`|\'\w+\'|"\w+"))*)';
			// Regex to get query verb, table 0, and tables 1..N
			$regexes = [
				// DML write queries
				"/^(INSERT|REPLACE)\s+(?:\w+\s+)*?INTO\s+$qts/i",
				"/^(UPDATE)(?:\s+OR\s+\w+|\s+IGNORE|\s+ONLY)?\s+$qts/i",
				"/^(DELETE)\s+(?:\w+\s+)*?FROM(?:\s+ONLY)?\s+$qts/i",
				// DDL write queries
				"/^(CREATE)\s+TEMPORARY\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+$qts/i",
				"/^(DROP)\s+(?:TEMPORARY\s+)?TABLE(?:\s+IF\s+EXISTS)?\s+$qts/i",
				"/^(TRUNCATE)\s+(?:TEMPORARY\s+)?TABLE\s+$qts/i",
				"/^(ALTER)\s+TABLE\s+$qts/i"
			];
		}

		$queryVerb = null;
		$queryTables = [];
		foreach ( $regexes as $regex ) {
			if ( preg_match( $regex, $sql, $m, PREG_UNMATCHED_AS_NULL ) ) {
				$queryVerb = $m[1];
				$allTables = preg_split( '/\s*,\s*/', $m[2] );
				foreach ( $allTables as $quotedTable ) {
					$queryTables[] = trim( $quotedTable, "\"'`" );
				}
				break;
			}
		}

		$tempTableChanges = [];
		foreach ( $queryTables as $table ) {
			if ( $queryVerb === 'CREATE' ) {
				// Record the type of temporary table being created
				$tableType = $pseudoPermanent ? self::$TEMP_PSEUDO_PERMANENT : self::$TEMP_NORMAL;
			} else {
				$tableType = $this->sessionTempTables[$table] ?? null;
			}

			if ( $tableType !== null ) {
				$tempTableChanges[] = [ $tableType, $queryVerb, $table ];
			}
		}

		return $tempTableChanges;
	}

	/**
	 * @param IResultWrapper|bool $ret
	 * @param array[] $changes List of change n-tuples with from getTempWrites()
	 */
	protected function registerTempWrites( $ret, array $changes ) {
		if ( $ret === false ) {
			return;
		}

		foreach ( $changes as list( $tmpTableType, $verb, $table ) ) {
			switch ( $verb ) {
				case 'CREATE':
					$this->sessionTempTables[$table] = $tmpTableType;
					break;
				case 'DROP':
					unset( $this->sessionTempTables[$table] );
					unset( $this->sessionDirtyTempTables[$table] );
					break;
				case 'TRUNCATE':
					unset( $this->sessionDirtyTempTables[$table] );
					break;
				default:
					$this->sessionDirtyTempTables[$table] = 1;
					break;
			}
		}
	}

	/**
	 * Check if the table is both a TEMPORARY table and has not yet received CRUD operations
	 *
	 * @param string $table
	 * @return bool
	 * @since 1.35
	 */
	protected function isPristineTemporaryTable( $table ) {
		$rawTable = $this->tableName( $table, 'raw' );

		return (
			isset( $this->sessionTempTables[$rawTable] ) &&
			!isset( $this->sessionDirtyTempTables[$rawTable] )
		);
	}

	public function query( $sql, $fname = __METHOD__, $flags = self::QUERY_NORMAL ) {
		$flags = (int)$flags; // b/c; this field used to be a bool
		// Sanity check that the SQL query is appropriate in the current context and is
		// allowed for an outside caller (e.g. does not break transaction/session tracking).
		$this->assertQueryIsCurrentlyAllowed( $sql, $fname );

		// Send the query to the server and fetch any corresponding errors
		list( $ret, $err, $errno, $unignorable ) = $this->executeQuery( $sql, $fname, $flags );
		if ( $ret === false ) {
			$ignoreErrors = $this->fieldHasBit( $flags, self::QUERY_SILENCE_ERRORS );
			// Throw an error unless both the ignore flag was set and a rollback is not needed
			$this->reportQueryError( $err, $errno, $sql, $fname, $ignoreErrors && !$unignorable );
		}

		return $this->resultObject( $ret );
	}

	/**
	 * Execute a query, retrying it if there is a recoverable connection loss
	 *
	 * This is similar to query() except:
	 *   - It does not prevent all non-ROLLBACK queries if there is a corrupted transaction
	 *   - It does not disallow raw queries that are supposed to use dedicated IDatabase methods
	 *   - It does not throw exceptions for common error cases
	 *
	 * This is meant for internal use with Database subclasses.
	 *
	 * @param string $sql Original SQL query
	 * @param string $fname Name of the calling function
	 * @param int $flags Bit field of class QUERY_* constants
	 * @return array An n-tuple of:
	 *   - mixed|bool: An object, resource, or true on success; false on failure
	 *   - string: The result of calling lastError()
	 *   - int: The result of calling lastErrno()
	 *   - bool: Whether a rollback is needed to allow future non-rollback queries
	 * @throws DBUnexpectedError
	 */
	final protected function executeQuery( $sql, $fname, $flags ) {
		$this->assertHasConnectionHandle();

		$priorTransaction = $this->trxLevel();

		if ( $this->isWriteQuery( $sql, $flags ) ) {
			// Do not treat temporary table writes as "meaningful writes" since they are only
			// visible to one session and are not permanent. Profile them as reads. Integration
			// tests can override this behavior via $flags.
			$pseudoPermanent = $this->fieldHasBit( $flags, self::QUERY_PSEUDO_PERMANENT );
			$tempTableChanges = $this->getTempTableWrites( $sql, $pseudoPermanent );
			$isPermWrite = !$tempTableChanges;
			foreach ( $tempTableChanges as list( $tmpType ) ) {
				$isPermWrite = $isPermWrite || ( $tmpType !== self::$TEMP_NORMAL );
			}

			// Permit temporary table writes on replica DB connections
			// but require a writable master connection for any persistent writes.
			if ( $isPermWrite ) {
				$this->assertIsWritableMaster();

				// DBConnRef uses QUERY_REPLICA_ROLE to enforce the replica role for raw SQL queries
				if ( $this->fieldHasBit( $flags, self::QUERY_REPLICA_ROLE ) ) {
					throw new DBReadOnlyRoleError( $this, "Cannot write; target role is DB_REPLICA" );
				}
			}
		} else {
			// No permanent writes in this query
			$isPermWrite = false;
			// No temporary tables written to either
			$tempTableChanges = [];
		}

		// Add trace comment to the begin of the sql string, right after the operator.
		// Or, for one-word queries (like "BEGIN" or COMMIT") add it to the end (T44598).
		$encAgent = str_replace( '/', '-', $this->agent );
		$commentedSql = preg_replace( '/\s|$/', " /* $fname $encAgent */ ", $sql, 1 );

		// Send the query to the server and fetch any corresponding errors.
		// This also doubles as a "ping" to see if the connection was dropped.
		list( $ret, $err, $errno, $recoverableSR, $recoverableCL, $reconnected ) =
			$this->executeQueryAttempt( $sql, $commentedSql, $isPermWrite, $fname, $flags );

		// Check if the query failed due to a recoverable connection loss
		$allowRetry = !$this->fieldHasBit( $flags, self::QUERY_NO_RETRY );
		if ( $ret === false && $recoverableCL && $reconnected && $allowRetry ) {
			// Silently resend the query to the server since it is safe and possible
			list( $ret, $err, $errno, $recoverableSR, $recoverableCL ) =
				$this->executeQueryAttempt( $sql, $commentedSql, $isPermWrite, $fname, $flags );
		}

		// Register creation and dropping of temporary tables
		$this->registerTempWrites( $ret, $tempTableChanges );

		$corruptedTrx = false;

		if ( $ret === false ) {
			if ( $priorTransaction ) {
				if ( $recoverableSR ) {
					# We're ignoring an error that caused just the current query to be aborted.
					# But log the cause so we can log a deprecation notice if a caller actually
					# does ignore it.
					$this->trxStatusIgnoredCause = [ $err, $errno, $fname ];
				} elseif ( !$recoverableCL ) {
					# Either the query was aborted or all queries after BEGIN where aborted.
					# In the first case, the only options going forward are (a) ROLLBACK, or
					# (b) ROLLBACK TO SAVEPOINT (if one was set). If the later case, the only
					# option is ROLLBACK, since the snapshots would have been released.
					$corruptedTrx = true; // cannot recover
					$this->trxStatus = self::STATUS_TRX_ERROR;
					$this->trxStatusCause = $this->getQueryException( $err, $errno, $sql, $fname );
					$this->trxStatusIgnoredCause = null;
				}
			}
		}

		return [ $ret, $err, $errno, $corruptedTrx ];
	}

	/**
	 * Wrapper for doQuery() that handles DBO_TRX, profiling, logging, affected row count
	 * tracking, and reconnects (without retry) on query failure due to connection loss
	 *
	 * @param string $sql Original SQL query
	 * @param string $commentedSql SQL query with debugging/trace comment
	 * @param bool $isPermWrite Whether the query is a (non-temporary table) write
	 * @param string $fname Name of the calling function
	 * @param int $flags Bit field of class QUERY_* constants
	 * @return array An n-tuple of:
	 *   - mixed|bool: An object, resource, or true on success; false on failure
	 *   - string: The result of calling lastError()
	 *   - int: The result of calling lastErrno()
	 * 	 - bool: Whether a statement rollback error occurred
	 *   - bool: Whether a disconnect *both* happened *and* was recoverable
	 *   - bool: Whether a reconnection attempt was *both* made *and* succeeded
	 * @throws DBUnexpectedError
	 */
	private function executeQueryAttempt( $sql, $commentedSql, $isPermWrite, $fname, $flags ) {
		$priorWritesPending = $this->writesOrCallbacksPending();

		if ( ( $flags & self::QUERY_IGNORE_DBO_TRX ) == 0 ) {
			$this->beginIfImplied( $sql, $fname );
		}

		// Keep track of whether the transaction has write queries pending
		if ( $isPermWrite ) {
			$this->lastWriteTime = microtime( true );
			if ( $this->trxLevel() && !$this->trxDoneWrites ) {
				$this->trxDoneWrites = true;
				$this->trxProfiler->transactionWritingIn(
					$this->server, $this->getDomainID(), $this->trxShortId );
			}
		}

		$prefix = $this->topologyRole ? 'query-m: ' : 'query: ';
		$generalizedSql = new GeneralizedSql( $sql, $this->trxShortId, $prefix );

		$startTime = microtime( true );
		$ps = $this->profiler
			? ( $this->profiler )( $generalizedSql->stringify() )
			: null;
		$this->affectedRowCount = null;
		$this->lastQuery = $sql;
		$ret = $this->doQuery( $commentedSql );
		$lastError = $this->lastError();
		$lastErrno = $this->lastErrno();

		$this->affectedRowCount = $this->affectedRows();
		unset( $ps ); // profile out (if set)
		$queryRuntime = max( microtime( true ) - $startTime, 0.0 );

		$recoverableSR = false; // recoverable statement rollback?
		$recoverableCL = false; // recoverable connection loss?
		$reconnected = false; // reconnection both attempted and succeeded?

		if ( $ret !== false ) {
			$this->lastPing = $startTime;
			if ( $isPermWrite && $this->trxLevel() ) {
				$this->updateTrxWriteQueryTime( $sql, $queryRuntime, $this->affectedRows() );
				$this->trxWriteCallers[] = $fname;
			}
		} elseif ( $this->wasConnectionError( $lastErrno ) ) {
			# Check if no meaningful session state was lost
			$recoverableCL = $this->canRecoverFromDisconnect( $sql, $priorWritesPending );
			# Update session state tracking and try to restore the connection
			$reconnected = $this->replaceLostConnection( __METHOD__ );
		} else {
			# Check if only the last query was rolled back
			$recoverableSR = $this->wasKnownStatementRollbackError();
		}

		if ( $sql === self::$PING_QUERY ) {
			$this->lastRoundTripEstimate = $queryRuntime;
		}

		$this->trxProfiler->recordQueryCompletion(
			$generalizedSql,
			$startTime,
			$isPermWrite,
			$isPermWrite ? $this->affectedRows() : $this->numRows( $ret )
		);

		// Avoid the overhead of logging calls unless debug mode is enabled
		if ( $this->getFlag( self::DBO_DEBUG ) ) {
			$this->queryLogger->debug(
				"{method} [{runtime}s] {db_host}: {sql}",
				[
					'method' => $fname,
					'db_host' => $this->getServer(),
					'sql' => $sql,
					'domain' => $this->getDomainID(),
					'runtime' => round( $queryRuntime, 3 )
				]
			);
		}

		return [ $ret, $lastError, $lastErrno, $recoverableSR, $recoverableCL, $reconnected ];
	}

	/**
	 * Start an implicit transaction if DBO_TRX is enabled and no transaction is active
	 *
	 * @param string $sql
	 * @param string $fname
	 */
	private function beginIfImplied( $sql, $fname ) {
		if (
			!$this->trxLevel() &&
			$this->getFlag( self::DBO_TRX ) &&
			$this->isTransactableQuery( $sql )
		) {
			$this->begin( __METHOD__ . " ($fname)", self::TRANSACTION_INTERNAL );
			$this->trxAutomatic = true;
		}
	}

	/**
	 * Update the estimated run-time of a query, not counting large row lock times
	 *
	 * LoadBalancer can be set to rollback transactions that will create huge replication
	 * lag. It bases this estimate off of pendingWriteQueryDuration(). Certain simple
	 * queries, like inserting a row can take a long time due to row locking. This method
	 * uses some simple heuristics to discount those cases.
	 *
	 * @param string $sql A SQL write query
	 * @param float $runtime Total runtime, including RTT
	 * @param int $affected Affected row count
	 */
	private function updateTrxWriteQueryTime( $sql, $runtime, $affected ) {
		// Whether this is indicative of replica DB runtime (except for RBR or ws_repl)
		$indicativeOfReplicaRuntime = true;
		if ( $runtime > self::$SLOW_WRITE_SEC ) {
			$verb = $this->getQueryVerb( $sql );
			// insert(), upsert(), replace() are fast unless bulky in size or blocked on locks
			if ( $verb === 'INSERT' ) {
				$indicativeOfReplicaRuntime = $this->affectedRows() > self::$SMALL_WRITE_ROWS;
			} elseif ( $verb === 'REPLACE' ) {
				$indicativeOfReplicaRuntime = $this->affectedRows() > self::$SMALL_WRITE_ROWS / 2;
			}
		}

		$this->trxWriteDuration += $runtime;
		$this->trxWriteQueryCount += 1;
		$this->trxWriteAffectedRows += $affected;
		if ( $indicativeOfReplicaRuntime ) {
			$this->trxWriteAdjDuration += $runtime;
			$this->trxWriteAdjQueryCount += 1;
		}
	}

	/**
	 * Error out if the DB is not in a valid state for a query via query()
	 *
	 * @param string $sql
	 * @param string $fname
	 * @throws DBTransactionStateError
	 */
	private function assertQueryIsCurrentlyAllowed( $sql, $fname ) {
		$verb = $this->getQueryVerb( $sql );
		if ( $verb === 'USE' ) {
			throw new DBUnexpectedError( $this, "Got USE query; use selectDomain() instead" );
		}

		if ( $verb === 'ROLLBACK' ) { // transaction/savepoint
			return;
		}

		if ( $this->trxStatus < self::STATUS_TRX_OK ) {
			throw new DBTransactionStateError(
				$this,
				"Cannot execute query from $fname while transaction status is ERROR",
				[],
				$this->trxStatusCause
			);
		} elseif ( $this->trxStatus === self::STATUS_TRX_OK && $this->trxStatusIgnoredCause ) {
			list( $iLastError, $iLastErrno, $iFname ) = $this->trxStatusIgnoredCause;
			call_user_func( $this->deprecationLogger,
				"Caller from $fname ignored an error originally raised from $iFname: " .
				"[$iLastErrno] $iLastError"
			);
			$this->trxStatusIgnoredCause = null;
		}
	}

	public function assertNoOpenTransactions() {
		if ( $this->explicitTrxActive() ) {
			throw new DBTransactionError(
				$this,
				"Explicit transaction still active. A caller may have caught an error. "
				. "Open transactions: " . $this->flatAtomicSectionList()
			);
		}
	}

	/**
	 * Determine whether it is safe to retry queries after a database connection is lost
	 *
	 * @param string $sql SQL query
	 * @param bool $priorWritesPending Whether there is a transaction open with
	 *     possible write queries or transaction pre-commit/idle callbacks
	 *     waiting on it to finish.
	 * @return bool True if it is safe to retry the query, false otherwise
	 */
	private function canRecoverFromDisconnect( $sql, $priorWritesPending ) {
		# Transaction dropped; this can mean lost writes, or REPEATABLE-READ snapshots.
		# Dropped connections also mean that named locks are automatically released.
		# Only allow error suppression in autocommit mode or when the lost transaction
		# didn't matter anyway (aside from DBO_TRX snapshot loss).
		if ( $this->sessionNamedLocks ) {
			return false; // possible critical section violation
		} elseif ( $this->sessionTempTables ) {
			return false; // tables might be queried latter
		} elseif ( $sql === 'COMMIT' ) {
			return !$priorWritesPending; // nothing written anyway? (T127428)
		} elseif ( $sql === 'ROLLBACK' ) {
			return true; // transaction lost...which is also what was requested :)
		} elseif ( $this->explicitTrxActive() ) {
			return false; // don't drop atomicity and explicit snapshots
		} elseif ( $priorWritesPending ) {
			return false; // prior writes lost from implicit transaction
		}

		return true;
	}

	/**
	 * Clean things up after session (and thus transaction) loss before reconnect
	 */
	private function handleSessionLossPreconnect() {
		// Clean up tracking of session-level things...
		// https://dev.mysql.com/doc/refman/5.7/en/implicit-commit.html
		// https://www.postgresql.org/docs/9.2/static/sql-createtable.html (ignoring ON COMMIT)
		$this->sessionTempTables = [];
		$this->sessionDirtyTempTables = [];
		// https://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_get-lock
		// https://www.postgresql.org/docs/9.4/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$this->sessionNamedLocks = [];
		// Session loss implies transaction loss
		$oldTrxShortId = $this->consumeTrxShortId();
		$this->trxAtomicCounter = 0;
		$this->trxIdleCallbacks = []; // T67263; transaction already lost
		$this->trxPreCommitCallbacks = []; // T67263; transaction already lost
		// Clear additional subclass fields
		$this->doHandleSessionLossPreconnect();
		// @note: leave trxRecurringCallbacks in place
		if ( $this->trxDoneWrites ) {
			$this->trxProfiler->transactionWritingOut(
				$this->server,
				$this->getDomainID(),
				$oldTrxShortId,
				$this->pendingWriteQueryDuration( self::ESTIMATE_TOTAL ),
				$this->trxWriteAffectedRows
			);
		}
	}

	/**
	 * Reset any additional subclass trx* and session* fields
	 * @stable to override
	 */
	protected function doHandleSessionLossPreconnect() {
		// no-op
	}

	/**
	 * Clean things up after session (and thus transaction) loss after reconnect
	 */
	private function handleSessionLossPostconnect() {
		try {
			// Handle callbacks in trxEndCallbacks, e.g. onTransactionResolution().
			// If callback suppression is set then the array will remain unhandled.
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
		} catch ( Throwable $ex ) {
			// Already logged; move on...
		}
		try {
			// Handle callbacks in trxRecurringCallbacks, e.g. setTransactionListener()
			$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
		} catch ( Throwable $ex ) {
			// Already logged; move on...
		}
	}

	/**
	 * Reset the transaction ID and return the old one
	 *
	 * @return string The old transaction ID or the empty string if there wasn't one
	 */
	private function consumeTrxShortId() {
		$old = $this->trxShortId;
		$this->trxShortId = '';

		return $old;
	}

	/**
	 * Checks whether the cause of the error is detected to be a timeout.
	 *
	 * It returns false by default, and not all engines support detecting this yet.
	 * If this returns false, it will be treated as a generic query error.
	 *
	 * @stable to override
	 * @param string $error Error text
	 * @param int $errno Error number
	 * @return bool
	 */
	protected function wasQueryTimeout( $error, $errno ) {
		return false;
	}

	/**
	 * Report a query error. Log the error, and if neither the object ignore
	 * flag nor the $ignoreErrors flag is set, throw a DBQueryError.
	 *
	 * @param string $error
	 * @param int $errno
	 * @param string $sql
	 * @param string $fname
	 * @param bool $ignore
	 * @throws DBQueryError
	 */
	public function reportQueryError( $error, $errno, $sql, $fname, $ignore = false ) {
		if ( $ignore ) {
			$this->queryLogger->debug( "SQL ERROR (ignored): $error" );
		} else {
			throw $this->getQueryExceptionAndLog( $error, $errno, $sql, $fname );
		}
	}

	/**
	 * @param string $error
	 * @param string|int $errno
	 * @param string $sql
	 * @param string $fname
	 * @return DBError
	 */
	private function getQueryExceptionAndLog( $error, $errno, $sql, $fname ) {
		// Information that instances of the same problem have in common should
		// not be normalized (T255202).
		$this->queryLogger->error(
			"Error $errno from $fname, {error} {sql1line} {db_server}",
			$this->getLogContext( [
				'method' => __METHOD__,
				'errno' => $errno,
				'error' => $error,
				'sql1line' => mb_substr( str_replace( "\n", "\\n", $sql ), 0, 5 * 1024 ),
				'fname' => $fname,
				'exception' => new RuntimeException()
			] )
		);
		return $this->getQueryException( $error, $errno, $sql, $fname );
	}

	/**
	 * @param string $error
	 * @param string|int $errno
	 * @param string $sql
	 * @param string $fname
	 * @return DBError
	 */
	private function getQueryException( $error, $errno, $sql, $fname ) {
		if ( $this->wasQueryTimeout( $error, $errno ) ) {
			return new DBQueryTimeoutError( $this, $error, $errno, $sql, $fname );
		} elseif ( $this->wasConnectionError( $errno ) ) {
			return new DBQueryDisconnectedError( $this, $error, $errno, $sql, $fname );
		} else {
			return new DBQueryError( $this, $error, $errno, $sql, $fname );
		}
	}

	/**
	 * @param string $error
	 * @return DBConnectionError
	 */
	final protected function newExceptionAfterConnectError( $error ) {
		// Connection was not fully initialized and is not safe for use
		$this->conn = null;

		$this->connLogger->error(
			"Error connecting to {db_server} as user {db_user}: {error}",
			$this->getLogContext( [
				'error' => $error,
				'exception' => new RuntimeException()
			] )
		);

		return new DBConnectionError( $this, $error );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function freeResult( $res ) {
	}

	/**
	 * @inheritDoc
	 */
	public function newSelectQueryBuilder() {
		return new SelectQueryBuilder( $this );
	}

	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field: got '$var'" );
		}

		$options = $this->normalizeOptions( $options );
		$options['LIMIT'] = 1;

		$res = $this->select( $table, $var, $cond, $fname, $options, $join_conds );
		if ( $res === false ) {
			throw new DBUnexpectedError( $this, "Got false from select()" );
		}

		$row = $this->fetchRow( $res );
		if ( $row === false ) {
			return false;
		}

		return reset( $row );
	}

	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field" );
		} elseif ( !is_string( $var ) ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use an array of fields" );
		}

		$options = $this->normalizeOptions( $options );
		$res = $this->select( $table, [ 'value' => $var ], $cond, $fname, $options, $join_conds );
		if ( $res === false ) {
			throw new DBUnexpectedError( $this, "Got false from select()" );
		}

		$values = [];
		foreach ( $res as $row ) {
			$values[] = $row->value;
		}

		return $values;
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query.
	 *
	 * @see Database::select()
	 *
	 * @stable to override
	 * @param array $options Associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	protected function makeSelectOptions( array $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = '';

		$noKeyOptions = [];

		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) {
			$postLimitTail .= ' LOCK IN SHARE MODE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		# Various MySQL extensions
		if ( isset( $noKeyOptions['STRAIGHT_JOIN'] ) ) {
			$startOpts .= ' /*! STRAIGHT_JOIN */';
		}

		if ( isset( $noKeyOptions['SQL_BIG_RESULT'] ) ) {
			$startOpts .= ' SQL_BIG_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_BUFFER_RESULT'] ) ) {
			$startOpts .= ' SQL_BUFFER_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_SMALL_RESULT'] ) ) {
			$startOpts .= ' SQL_SMALL_RESULT';
		}

		if ( isset( $noKeyOptions['SQL_CALC_FOUND_ROWS'] ) ) {
			$startOpts .= ' SQL_CALC_FOUND_ROWS';
		}

		if ( isset( $options['USE INDEX'] ) && is_string( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}
		if ( isset( $options['IGNORE INDEX'] ) && is_string( $options['IGNORE INDEX'] ) ) {
			$ignoreIndex = $this->ignoreIndexClause( $options['IGNORE INDEX'] );
		} else {
			$ignoreIndex = '';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
	}

	/**
	 * Returns an optional GROUP BY with an optional HAVING
	 *
	 * @param array $options Associative array of options
	 * @return string
	 * @see Database::select()
	 * @since 1.21
	 */
	protected function makeGroupByWithHaving( $options ) {
		$sql = '';
		if ( isset( $options['GROUP BY'] ) ) {
			$gb = is_array( $options['GROUP BY'] )
				? implode( ',', $options['GROUP BY'] )
				: $options['GROUP BY'];
			$sql .= ' GROUP BY ' . $gb;
		}
		if ( isset( $options['HAVING'] ) ) {
			$having = is_array( $options['HAVING'] )
				? $this->makeList( $options['HAVING'], self::LIST_AND )
				: $options['HAVING'];
			$sql .= ' HAVING ' . $having;
		}

		return $sql;
	}

	/**
	 * Returns an optional ORDER BY
	 *
	 * @param array $options Associative array of options
	 * @return string
	 * @see Database::select()
	 * @since 1.21
	 */
	protected function makeOrderBy( $options ) {
		if ( isset( $options['ORDER BY'] ) ) {
			$ob = is_array( $options['ORDER BY'] )
				? implode( ',', $options['ORDER BY'] )
				: $options['ORDER BY'];

			return ' ORDER BY ' . $ob;
		}

		return '';
	}

	public function select(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		return $this->query( $sql, $fname, self::QUERY_CHANGE_NONE );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function selectSQLText( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		if ( is_array( $vars ) ) {
			$fields = implode( ',', $this->fieldNamesWithAlias( $vars ) );
		} else {
			$fields = $vars;
		}

		$options = (array)$options;
		$useIndexes = ( isset( $options['USE INDEX'] ) && is_array( $options['USE INDEX'] ) )
			? $options['USE INDEX']
			: [];
		$ignoreIndexes = (
			isset( $options['IGNORE INDEX'] ) &&
			is_array( $options['IGNORE INDEX'] )
		)
			? $options['IGNORE INDEX']
			: [];

		if (
			$this->selectOptionsIncludeLocking( $options ) &&
			$this->selectFieldsOrOptionsAggregate( $vars, $options )
		) {
			// Some DB types (e.g. postgres) disallow FOR UPDATE with aggregate
			// functions. Discourage use of such queries to encourage compatibility.
			call_user_func(
				$this->deprecationLogger,
				__METHOD__ . ": aggregation used with a locking SELECT ($fname)"
			);
		}

		if ( is_array( $table ) ) {
			if ( count( $table ) === 0 ) {
				$from = '';
			} else {
				$from = ' FROM ' .
					$this->tableNamesWithIndexClauseOrJOIN(
						$table, $useIndexes, $ignoreIndexes, $join_conds );
			}
		} elseif ( $table != '' ) {
			$from = ' FROM ' .
				$this->tableNamesWithIndexClauseOrJOIN(
					[ $table ], $useIndexes, $ignoreIndexes, [] );
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ) =
			$this->makeSelectOptions( $options );

		if ( is_array( $conds ) ) {
			$conds = $this->makeList( $conds, self::LIST_AND );
		}

		if ( $conds === null || $conds === false ) {
			$this->queryLogger->warning(
				__METHOD__
				. ' called from '
				. $fname
				. ' with incorrect parameters: $conds must be a string or an array'
			);
			$conds = '';
		}

		if ( $conds === '' || $conds === '*' ) {
			$sql = "SELECT $startOpts $fields $from $useIndex $ignoreIndex $preLimitTail";
		} elseif ( is_string( $conds ) ) {
			$sql = "SELECT $startOpts $fields $from $useIndex $ignoreIndex " .
				"WHERE $conds $preLimitTail";
		} else {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with incorrect parameters' );
		}

		if ( isset( $options['LIMIT'] ) ) {
			$sql = $this->limitResult( $sql, $options['LIMIT'],
				$options['OFFSET'] ?? false );
		}
		$sql = "$sql $postLimitTail";

		if ( isset( $options['EXPLAIN'] ) ) {
			$sql = 'EXPLAIN ' . $sql;
		}

		return $sql;
	}

	public function selectRow( $table, $vars, $conds, $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		$options = (array)$options;
		$options['LIMIT'] = 1;

		$res = $this->select( $table, $vars, $conds, $fname, $options, $join_conds );
		if ( $res === false ) {
			throw new DBUnexpectedError( $this, "Got false from select()" );
		}

		if ( !$this->numRows( $res ) ) {
			return false;
		}

		return $this->fetchObject( $res );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function estimateRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->normalizeConditions( $conds, $fname );
		$column = $this->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$res = $this->select(
			$tables, [ 'rowcount' => 'COUNT(*)' ], $conds, $fname, $options, $join_conds
		);
		$row = $res ? $this->fetchRow( $res ) : [];

		return isset( $row['rowcount'] ) ? (int)$row['rowcount'] : 0;
	}

	public function selectRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->normalizeConditions( $conds, $fname );
		$column = $this->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$res = $this->select(
			[
				'tmp_count' => $this->buildSelectSubquery(
					$tables,
					'1',
					$conds,
					$fname,
					$options,
					$join_conds
				)
			],
			[ 'rowcount' => 'COUNT(*)' ],
			[],
			$fname
		);
		$row = $res ? $this->fetchRow( $res ) : [];

		return isset( $row['rowcount'] ) ? (int)$row['rowcount'] : 0;
	}

	/**
	 * @param string|array $options
	 * @return bool
	 */
	private function selectOptionsIncludeLocking( $options ) {
		$options = (array)$options;
		foreach ( [ 'FOR UPDATE', 'LOCK IN SHARE MODE' ] as $lock ) {
			if ( in_array( $lock, $options, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array|string $fields
	 * @param array|string $options
	 * @return bool
	 */
	private function selectFieldsOrOptionsAggregate( $fields, $options ) {
		foreach ( (array)$options as $key => $value ) {
			if ( is_string( $key ) ) {
				if ( preg_match( '/^(?:GROUP BY|HAVING)$/i', $key ) ) {
					return true;
				}
			} elseif ( is_string( $value ) ) {
				if ( preg_match( '/^(?:DISTINCT|DISTINCTROW)$/i', $value ) ) {
					return true;
				}
			}
		}

		$regex = '/^(?:COUNT|MIN|MAX|SUM|GROUP_CONCAT|LISTAGG|ARRAY_AGG)\s*\\(/i';
		foreach ( (array)$fields as $field ) {
			if ( is_string( $field ) && preg_match( $regex, $field ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array $rowOrRows A single (field => value) map or a list of such maps
	 * @return array[] List of (field => value) maps
	 * @since 1.35
	 */
	final protected function normalizeRowArray( array $rowOrRows ) {
		if ( !$rowOrRows ) {
			$rows = [];
		} elseif ( isset( $rowOrRows[0] ) ) {
			$rows = $rowOrRows;
		} else {
			$rows = [ $rowOrRows ];
		}

		foreach ( $rows as $row ) {
			if ( !is_array( $row ) ) {
				throw new DBUnexpectedError( $this, "Got non-array in row array" );
			} elseif ( !$row ) {
				throw new DBUnexpectedError( $this, "Got empty array in row array" );
			}
		}

		return $rows;
	}

	/**
	 * @param array|string $conds
	 * @param string $fname
	 * @return array
	 * @since 1.31
	 */
	final protected function normalizeConditions( $conds, $fname ) {
		if ( $conds === null || $conds === false ) {
			$this->queryLogger->warning(
				__METHOD__
				. ' called from '
				. $fname
				. ' with incorrect parameters: $conds must be a string or an array'
			);
			return [];
		} elseif ( $conds === '' ) {
			return [];
		}

		return is_array( $conds ) ? $conds : [ $conds ];
	}

	/**
	 * @param string|string[]|string[][] $uniqueKeys Unique indexes (first is identity key)
	 * @return string[][] Unique indexes as column lists (first index is the identity key)
	 * @since 1.35
	 */
	final protected function normalizeUpsertKeys( $uniqueKeys ) {
		if ( is_string( $uniqueKeys ) ) {
			return [ [ $uniqueKeys ] ];
		}

		if ( !is_array( $uniqueKeys ) || !$uniqueKeys ) {
			throw new DBUnexpectedError( $this, 'Invalid or empty unique key array' );
		}

		$oldStyle = false;
		$uniqueColumnSets = [];
		foreach ( $uniqueKeys as $i => $uniqueKey ) {
			if ( !is_int( $i ) ) {
				throw new DBUnexpectedError( $this, 'Unique key array should be a list' );
			} elseif ( is_string( $uniqueKey ) ) {
				$oldStyle = true;
				$uniqueColumnSets[] = [ $uniqueKey ];
			} elseif ( is_array( $uniqueKey ) && $uniqueKey ) {
				$uniqueColumnSets[] = $uniqueKey;
			} else {
				throw new DBUnexpectedError( $this, 'Invalid unique key array entry' );
			}
		}

		if ( count( $uniqueColumnSets ) > 1 ) {
			// If an existing row conflicts with new row X on key A and new row Y on key B,
			// it is not well defined how many UPDATEs should apply to the existing row and
			// in what order the new rows are checked
			$this->queryLogger->warning(
				__METHOD__ . " called with multiple unique keys",
				[ 'exception' => new RuntimeException() ]
			);
		}

		if ( $oldStyle ) {
			// Passing a list of strings for single-column unique keys is too
			// easily confused with passing the columns of composite unique key
			$this->queryLogger->warning(
				__METHOD__ . " called with deprecated parameter style: " .
				"the unique key array should be a string or array of string arrays",
				[ 'exception' => new RuntimeException() ]
			);
		}

		return $uniqueColumnSets;
	}

	/**
	 * @param string|array $options
	 * @return array Combination option/value map and boolean option list
	 * @since 1.35
	 */
	final protected function normalizeOptions( $options ) {
		if ( is_array( $options ) ) {
			return $options;
		} elseif ( is_string( $options ) ) {
			return ( $options === '' ) ? [] : [ $options ];
		} else {
			throw new DBUnexpectedError( $this, __METHOD__ . ': expected string or array' );
		}
	}

	/**
	 * @param string $option Query option flag (e.g. "IGNORE" or "FOR UPDATE")
	 * @param array $options Combination option/value map and boolean option list
	 * @return bool Whether the option appears as an integer-keyed value in the options
	 * @since 1.35
	 */
	final protected function isFlagInOptions( $option, array $options ) {
		foreach ( array_keys( $options, $option, true ) as $k ) {
			if ( is_int( $k ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param array|string $var Field parameter in the style of select()
	 * @return string|null Column name or null; ignores aliases
	 */
	final protected function extractSingleFieldFromList( $var ) {
		if ( is_array( $var ) ) {
			if ( !$var ) {
				$column = null;
			} elseif ( count( $var ) == 1 ) {
				$column = $var[0] ?? reset( $var );
			} else {
				throw new DBUnexpectedError( $this, __METHOD__ . ': got multiple columns' );
			}
		} else {
			$column = $var;
		}

		return $column;
	}

	public function lockForUpdate(
		$table, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( !$this->trxLevel() && !$this->getFlag( self::DBO_TRX ) ) {
			throw new DBUnexpectedError(
				$this,
				__METHOD__ . ': no transaction is active nor is DBO_TRX set'
			);
		}

		$options = (array)$options;
		$options[] = 'FOR UPDATE';

		return $this->selectRowCount( $table, '*', $conds, $fname, $options, $join_conds );
	}

	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		$info = $this->fieldInfo( $table, $field );

		return (bool)$info;
	}

	public function indexExists( $table, $index, $fname = __METHOD__ ) {
		if ( !$this->tableExists( $table, $fname ) ) {
			return null;
		}

		$info = $this->indexInfo( $table, $index, $fname );
		if ( $info === null ) {
			return null;
		} else {
			return $info !== false;
		}
	}

	abstract public function tableExists( $table, $fname = __METHOD__ );

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function indexUnique( $table, $index, $fname = __METHOD__ ) {
		$indexInfo = $this->indexInfo( $table, $index, $fname );

		if ( !$indexInfo ) {
			return null;
		}

		return !$indexInfo[0]->Non_unique;
	}

	public function insert( $table, $rows, $fname = __METHOD__, $options = [] ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return true;
		}

		$options = $this->normalizeOptions( $options );
		if ( $this->isFlagInOptions( 'IGNORE', $options ) ) {
			$this->doInsertNonConflicting( $table, $rows, $fname );
		} else {
			$this->doInsert( $table, $rows, $fname );
		}

		return true;
	}

	/**
	 * @see Database::insert()
	 * @stable to override
	 * @param string $table
	 * @param array $rows Non-empty list of rows
	 * @param string $fname
	 * @since 1.35
	 */
	protected function doInsert( $table, array $rows, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );

		$sql = "INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples";

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * @see Database::insert()
	 * @stable to override
	 * @param string $table
	 * @param array $rows Non-empty list of rows
	 * @param string $fname
	 * @since 1.35
	 */
	protected function doInsertNonConflicting( $table, array $rows, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );
		list( $sqlVerb, $sqlOpts ) = $this->makeInsertNonConflictingVerbAndOptions();

		$sql = rtrim( "$sqlVerb $encTable ($sqlColumns) VALUES $sqlTuples $sqlOpts" );

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * @stable to override
	 * @return string[] ("INSERT"-style SQL verb, "ON CONFLICT"-style clause or "")
	 * @since 1.35
	 */
	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT IGNORE INTO', '' ];
	}

	/**
	 * Make SQL lists of columns, row tuples for INSERT/VALUES expressions
	 *
	 * The tuple column order is that of the columns of the first provided row.
	 * The provided rows must have exactly the same keys and ordering thereof.
	 *
	 * @param array[] $rows Non-empty list of (column => value) maps
	 * @return array (comma-separated columns, comma-separated tuples)
	 * @since 1.35
	 */
	protected function makeInsertLists( array $rows ) {
		$firstRow = $rows[0];
		if ( !is_array( $firstRow ) || !$firstRow ) {
			throw new DBUnexpectedError( $this, 'Got an empty row list or empty row' );
		}
		// List of columns that define the value tuple ordering
		$tupleColumns = array_keys( $firstRow );

		$valueTuples = [];
		foreach ( $rows as $row ) {
			$rowColumns = array_keys( $row );
			// VALUES(...) requires a uniform correspondance of (column => value)
			if ( $rowColumns !== $tupleColumns ) {
				throw new DBUnexpectedError(
					$this,
					'Got row columns (' . implode( ', ', $rowColumns ) . ') ' .
					'instead of expected (' . implode( ', ', $tupleColumns ) . ')'
				);
			}
			// Make the value tuple that defines this row
			$valueTuples[] = '(' . $this->makeList( $row, self::LIST_COMMA ) . ')';
		}

		return [
			$this->makeList( $tupleColumns, self::LIST_NAMES ),
			implode( ',', $valueTuples )
		];
	}

	/**
	 * Make UPDATE options array for Database::makeUpdateOptions
	 *
	 * @stable to override
	 * @param array $options
	 * @return array
	 */
	protected function makeUpdateOptionsArray( $options ) {
		$options = $this->normalizeOptions( $options );

		$opts = [];

		if ( in_array( 'IGNORE', $options ) ) {
			$opts[] = 'IGNORE';
		}

		return $opts;
	}

	/**
	 * Make UPDATE options for the Database::update function
	 *
	 * @stable to override
	 * @param array $options The options passed to Database::update
	 * @return string
	 */
	protected function makeUpdateOptions( $options ) {
		$opts = $this->makeUpdateOptionsArray( $options );

		return implode( ' ', $opts );
	}

	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] ) {
		$this->assertConditionIsNotEmpty( $conds, __METHOD__, true );
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $set, self::LIST_SET );

		if ( $conds && $conds !== IDatabase::ALL_ROWS ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );

		return true;
	}

	public function makeList( array $a, $mode = self::LIST_COMMA ) {
		$first = true;
		$list = '';

		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == self::LIST_AND ) {
					$list .= ' AND ';
				} elseif ( $mode == self::LIST_OR ) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}

			if ( ( $mode == self::LIST_AND || $mode == self::LIST_OR ) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( $mode == self::LIST_SET && is_numeric( $field ) ) {
				$list .= "$value";
			} elseif (
				( $mode == self::LIST_AND || $mode == self::LIST_OR ) && is_array( $value )
			) {
				// Remove null from array to be handled separately if found
				$includeNull = false;
				foreach ( array_keys( $value, null, true ) as $nullKey ) {
					$includeNull = true;
					unset( $value[$nullKey] );
				}
				if ( count( $value ) == 0 && !$includeNull ) {
					throw new InvalidArgumentException(
						__METHOD__ . ": empty input for field $field" );
				} elseif ( count( $value ) == 0 ) {
					// only check if $field is null
					$list .= "$field IS NULL";
				} else {
					// IN clause contains at least one valid element
					if ( $includeNull ) {
						// Group subconditions to ensure correct precedence
						$list .= '(';
					}
					if ( count( $value ) == 1 ) {
						// Special-case single values, as IN isn't terribly efficient
						// Don't necessarily assume the single key is 0; we don't
						// enforce linear numeric ordering on other arrays here.
						$value = array_values( $value )[0];
						$list .= $field . " = " . $this->addQuotes( $value );
					} else {
						$list .= $field . " IN (" . $this->makeList( $value ) . ") ";
					}
					// if null present in array, append IS NULL
					if ( $includeNull ) {
						$list .= " OR $field IS NULL)";
					}
				}
			} elseif ( $value === null ) {
				if ( $mode == self::LIST_AND || $mode == self::LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == self::LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= 'NULL';
			} else {
				if (
					$mode == self::LIST_AND || $mode == self::LIST_OR || $mode == self::LIST_SET
				) {
					$list .= "$field = ";
				}
				$list .= $mode == self::LIST_NAMES ? $value : $this->addQuotes( $value );
			}
		}

		return $list;
	}

	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		$conds = [];

		foreach ( $data as $base => $sub ) {
			if ( count( $sub ) ) {
				$conds[] = $this->makeList(
					[ $baseKey => $base, $subKey => array_map( 'strval', array_keys( $sub ) ) ],
					self::LIST_AND );
			}
		}

		if ( $conds ) {
			return $this->makeList( $conds, self::LIST_OR );
		} else {
			// Nothing to search for...
			return false;
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuename;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitNot( $field ) {
		return "(~$field)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "GROUP_CONCAT($field SEPARATOR " . $this->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildGreatest( $fields, $values ) {
		return $this->buildSuperlative( 'GREATEST', $fields, $values );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildLeast( $fields, $values ) {
		return $this->buildSuperlative( 'LEAST', $fields, $values );
	}

	/**
	 * Build a superlative function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @stable to override
	 * @param string $sqlfunc Name of a SQL function
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return mixed
	 * @since 1.35
	 */
	protected function buildSuperlative( $sqlfunc, $fields, $values ) {
		$fields = is_array( $fields ) ? $fields : [ $fields ];
		$values = is_array( $values ) ? $values : [ $values ];

		$encValues = [];
		foreach ( $fields as $alias => $field ) {
			if ( is_int( $alias ) ) {
				$encValues[] = $this->addIdentifierQuotes( $field );
			} else {
				$encValues[] = $field; // expression
			}
		}
		foreach ( $values as $value ) {
			if ( is_int( $value ) || is_float( $value ) ) {
				$encValues[] = $value;
			} elseif ( is_string( $value ) ) {
				$encValues[] = $this->addQuotes( $value );
			} elseif ( $value === null ) {
				throw new DBUnexpectedError( $this, 'Null value in superlative' );
			} else {
				throw new DBUnexpectedError( $this, 'Unexpected value type in superlative' );
			}
		}

		return $sqlfunc . '(' . implode( ',', $encValues ) . ')';
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildSubstring( $input, $startPosition, $length = null ) {
		$this->assertBuildSubstringParams( $startPosition, $length );
		$functionBody = "$input FROM $startPosition";
		if ( $length !== null ) {
			$functionBody .= " FOR $length";
		}
		return 'SUBSTRING(' . $functionBody . ')';
	}

	/**
	 * Check type and bounds for parameters to self::buildSubstring()
	 *
	 * All supported databases have substring functions that behave the same for
	 * positive $startPosition and non-negative $length, but behaviors differ when
	 * given 0 or negative $startPosition or negative $length. The simplest
	 * solution to that is to just forbid those values.
	 *
	 * @param int $startPosition
	 * @param int|null $length
	 * @since 1.31
	 */
	protected function assertBuildSubstringParams( $startPosition, $length ) {
		if ( !is_int( $startPosition ) || $startPosition <= 0 ) {
			throw new InvalidArgumentException(
				'$startPosition must be a positive integer'
			);
		}
		if ( !( is_int( $length ) && $length >= 0 || $length === null ) ) {
			throw new InvalidArgumentException(
				'$length must be null or an integer greater than or equal to 0'
			);
		}
	}

	/**
	 * Check type and bounds conditions parameters for update
	 *
	 * In order to prevent possible performance or replication issues,
	 * empty condition for 'update' queries isn't allowed
	 *
	 * @param array|string $conds conditions to be validated on emptiness
	 * @param string $fname caller's function name to be passed to exception
	 * @param bool $deprecate define the assertion type. If true then
	 *   wfDeprecated will be called, otherwise DBUnexpectedError will be
	 *   raised.
	 * @since 1.35
	 */
	protected function assertConditionIsNotEmpty( $conds, string $fname, bool $deprecate ) {
		$isCondValid = ( is_string( $conds ) || is_array( $conds ) ) && $conds;
		if ( !$isCondValid ) {
			if ( $deprecate ) {
				wfDeprecated( $fname . ' called with empty $conds', '1.35', false, 3 );
			} else {
				throw new DBUnexpectedError( $this, $fname . ' called with empty conditions' );
			}
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildStringCast( $field ) {
		// In theory this should work for any standards-compliant
		// SQL implementation, although it may not be the best way to do it.
		return "CAST( $field AS CHARACTER )";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildIntegerCast( $field ) {
		return 'CAST( ' . $field . ' AS INTEGER )';
	}

	public function buildSelectSubquery(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return new Subquery(
			$this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds )
		);
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function databasesAreIndependent() {
		return false;
	}

	final public function selectDB( $db ) {
		$this->selectDomain( new DatabaseDomain(
			$db,
			$this->currentDomain->getSchema(),
			$this->currentDomain->getTablePrefix()
		) );

		return true;
	}

	final public function selectDomain( $domain ) {
		$this->doSelectDomain( DatabaseDomain::newFromId( $domain ) );
	}

	/**
	 * @stable to override
	 * @param DatabaseDomain $domain
	 * @throws DBConnectionError
	 * @throws DBError
	 * @since 1.32
	 */
	protected function doSelectDomain( DatabaseDomain $domain ) {
		$this->currentDomain = $domain;
	}

	public function getDBname() {
		return $this->currentDomain->getDatabase();
	}

	public function getServer() {
		return $this->server;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function tableName( $name, $format = 'quoted' ) {
		if ( $name instanceof Subquery ) {
			throw new DBUnexpectedError(
				$this,
				__METHOD__ . ': got Subquery instance when expecting a string'
			);
		}

		# Skip the entire process when we have a string quoted on both ends.
		# Note that we check the end so that we will still quote any use of
		# use of `database`.table. But won't break things if someone wants
		# to query a database table with a dot in the name.
		if ( $this->isQuotedIdentifier( $name ) ) {
			return $name;
		}

		# Lets test for any bits of text that should never show up in a table
		# name. Basically anything like JOIN or ON which are actually part of
		# SQL queries, but may end up inside of the table value to combine
		# sql. Such as how the API is doing.
		# Note that we use a whitespace test rather than a \b test to avoid
		# any remote case where a word like on may be inside of a table name
		# surrounded by symbols which may be considered word breaks.
		if ( preg_match( '/(^|\s)(DISTINCT|JOIN|ON|AS)(\s|$)/i', $name ) !== 0 ) {
			$this->queryLogger->warning(
				__METHOD__ . ": use of subqueries is not supported this way",
				[ 'exception' => new RuntimeException() ]
			);

			return $name;
		}

		# Split database and table into proper variables.
		list( $database, $schema, $prefix, $table ) = $this->qualifiedTableComponents( $name );

		# Quote $table and apply the prefix if not quoted.
		# $tableName might be empty if this is called from Database::replaceVars()
		$tableName = "{$prefix}{$table}";
		if ( $format === 'quoted'
			&& !$this->isQuotedIdentifier( $tableName )
			&& $tableName !== ''
		) {
			$tableName = $this->addIdentifierQuotes( $tableName );
		}

		# Quote $schema and $database and merge them with the table name if needed
		$tableName = $this->prependDatabaseOrSchema( $schema, $tableName, $format );
		$tableName = $this->prependDatabaseOrSchema( $database, $tableName, $format );

		return $tableName;
	}

	/**
	 * Get the table components needed for a query given the currently selected database
	 *
	 * @param string $name Table name in the form of db.schema.table, db.table, or table
	 * @return array (DB name or "" for default, schema name, table prefix, table name)
	 */
	protected function qualifiedTableComponents( $name ) {
		# We reverse the explode so that database.table and table both output the correct table.
		$dbDetails = explode( '.', $name, 3 );
		if ( count( $dbDetails ) == 3 ) {
			list( $database, $schema, $table ) = $dbDetails;
			# We don't want any prefix added in this case
			$prefix = '';
		} elseif ( count( $dbDetails ) == 2 ) {
			list( $database, $table ) = $dbDetails;
			# We don't want any prefix added in this case
			$prefix = '';
			# In dbs that support it, $database may actually be the schema
			# but that doesn't affect any of the functionality here
			$schema = '';
		} else {
			list( $table ) = $dbDetails;
			if ( isset( $this->tableAliases[$table] ) ) {
				$database = $this->tableAliases[$table]['dbname'];
				$schema = is_string( $this->tableAliases[$table]['schema'] )
					? $this->tableAliases[$table]['schema']
					: $this->relationSchemaQualifier();
				$prefix = is_string( $this->tableAliases[$table]['prefix'] )
					? $this->tableAliases[$table]['prefix']
					: $this->tablePrefix();
			} else {
				$database = '';
				$schema = $this->relationSchemaQualifier(); # Default schema
				$prefix = $this->tablePrefix(); # Default prefix
			}
		}

		return [ $database, $schema, $prefix, $table ];
	}

	/**
	 * @param string|null $namespace Database or schema
	 * @param string $relation Name of table, view, sequence, etc...
	 * @param string $format One of (raw, quoted)
	 * @return string Relation name with quoted and merged $namespace as needed
	 */
	private function prependDatabaseOrSchema( $namespace, $relation, $format ) {
		if ( strlen( $namespace ) ) {
			if ( $format === 'quoted' && !$this->isQuotedIdentifier( $namespace ) ) {
				$namespace = $this->addIdentifierQuotes( $namespace );
			}
			$relation = $namespace . '.' . $relation;
		}

		return $relation;
	}

	public function tableNames( ...$tables ) {
		$retVal = [];

		foreach ( $tables as $name ) {
			$retVal[$name] = $this->tableName( $name );
		}

		return $retVal;
	}

	public function tableNamesN( ...$tables ) {
		$retVal = [];

		foreach ( $tables as $name ) {
			$retVal[] = $this->tableName( $name );
		}

		return $retVal;
	}

	/**
	 * Get an aliased table name
	 *
	 * This returns strings like "tableName AS newTableName" for aliased tables
	 * and "(SELECT * from tableA) newTablename" for subqueries (e.g. derived tables)
	 *
	 * @see Database::tableName()
	 * @param string|Subquery $table Table name or object with a 'sql' field
	 * @param string|bool $alias Table alias (optional)
	 * @return string SQL name for aliased table. Will not alias a table to its own name
	 */
	protected function tableNameWithAlias( $table, $alias = false ) {
		if ( is_string( $table ) ) {
			$quotedTable = $this->tableName( $table );
		} elseif ( $table instanceof Subquery ) {
			$quotedTable = (string)$table;
		} else {
			throw new InvalidArgumentException( "Table must be a string or Subquery" );
		}

		if ( $alias === false || $alias === $table ) {
			if ( $table instanceof Subquery ) {
				throw new InvalidArgumentException( "Subquery table missing alias" );
			}

			return $quotedTable;
		} else {
			return $quotedTable . ' ' . $this->addIdentifierQuotes( $alias );
		}
	}

	/**
	 * Get an aliased field name
	 * e.g. fieldName AS newFieldName
	 *
	 * @stable to override
	 * @param string $name Field name
	 * @param string|bool $alias Alias (optional)
	 * @return string SQL name for aliased field. Will not alias a field to its own name
	 */
	protected function fieldNameWithAlias( $name, $alias = false ) {
		if ( !$alias || (string)$alias === (string)$name ) {
			return $name;
		} else {
			return $name . ' AS ' . $this->addIdentifierQuotes( $alias ); // PostgreSQL needs AS
		}
	}

	/**
	 * Gets an array of aliased field names
	 *
	 * @param array $fields [ [alias] => field ]
	 * @return string[] See fieldNameWithAlias()
	 */
	protected function fieldNamesWithAlias( $fields ) {
		$retval = [];
		foreach ( $fields as $alias => $field ) {
			if ( is_numeric( $alias ) ) {
				$alias = $field;
			}
			$retval[] = $this->fieldNameWithAlias( $field, $alias );
		}

		return $retval;
	}

	/**
	 * Get the aliased table name clause for a FROM clause
	 * which might have a JOIN and/or USE INDEX or IGNORE INDEX clause
	 *
	 * @param array $tables ( [alias] => table )
	 * @param array $use_index Same as for select()
	 * @param array $ignore_index Same as for select()
	 * @param array $join_conds Same as for select()
	 * @return string
	 */
	protected function tableNamesWithIndexClauseOrJOIN(
		$tables, $use_index = [], $ignore_index = [], $join_conds = []
	) {
		$ret = [];
		$retJOIN = [];
		$use_index = (array)$use_index;
		$ignore_index = (array)$ignore_index;
		$join_conds = (array)$join_conds;

		foreach ( $tables as $alias => $table ) {
			if ( !is_string( $alias ) ) {
				// No alias? Set it equal to the table name
				$alias = $table;
			}

			if ( is_array( $table ) ) {
				// A parenthesized group
				if ( count( $table ) > 1 ) {
					$joinedTable = '(' .
						$this->tableNamesWithIndexClauseOrJOIN(
							$table, $use_index, $ignore_index, $join_conds ) . ')';
				} else {
					// Degenerate case
					$innerTable = reset( $table );
					$innerAlias = key( $table );
					$joinedTable = $this->tableNameWithAlias(
						$innerTable,
						is_string( $innerAlias ) ? $innerAlias : $innerTable
					);
				}
			} else {
				$joinedTable = $this->tableNameWithAlias( $table, $alias );
			}

			// Is there a JOIN clause for this table?
			if ( isset( $join_conds[$alias] ) ) {
				list( $joinType, $conds ) = $join_conds[$alias];
				$tableClause = $joinType;
				$tableClause .= ' ' . $joinedTable;
				if ( isset( $use_index[$alias] ) ) { // has USE INDEX?
					$use = $this->useIndexClause( implode( ',', (array)$use_index[$alias] ) );
					if ( $use != '' ) {
						$tableClause .= ' ' . $use;
					}
				}
				if ( isset( $ignore_index[$alias] ) ) { // has IGNORE INDEX?
					$ignore = $this->ignoreIndexClause(
						implode( ',', (array)$ignore_index[$alias] ) );
					if ( $ignore != '' ) {
						$tableClause .= ' ' . $ignore;
					}
				}
				$on = $this->makeList( (array)$conds, self::LIST_AND );
				if ( $on != '' ) {
					$tableClause .= ' ON (' . $on . ')';
				}

				$retJOIN[] = $tableClause;
			} elseif ( isset( $use_index[$alias] ) ) {
				// Is there an INDEX clause for this table?
				$tableClause = $joinedTable;
				$tableClause .= ' ' . $this->useIndexClause(
						implode( ',', (array)$use_index[$alias] )
					);

				$ret[] = $tableClause;
			} elseif ( isset( $ignore_index[$alias] ) ) {
				// Is there an INDEX clause for this table?
				$tableClause = $joinedTable;
				$tableClause .= ' ' . $this->ignoreIndexClause(
						implode( ',', (array)$ignore_index[$alias] )
					);

				$ret[] = $tableClause;
			} else {
				$tableClause = $joinedTable;

				$ret[] = $tableClause;
			}
		}

		// We can't separate explicit JOIN clauses with ',', use ' ' for those
		$implicitJoins = implode( ',', $ret );
		$explicitJoins = implode( ' ', $retJOIN );

		// Compile our final table clause
		return implode( ' ', [ $implicitJoins, $explicitJoins ] );
	}

	/**
	 * Allows for index remapping in queries where this is not consistent across DBMS
	 *
	 * @param string $index
	 * @return string
	 */
	protected function indexName( $index ) {
		return $this->indexAliases[$index] ?? $index;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function addQuotes( $s ) {
		if ( $s instanceof Blob ) {
			$s = $s->fetch();
		}
		if ( $s === null ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return (string)(int)$s;
		} elseif ( is_int( $s ) ) {
			return (string)$s;
		} else {
			return "'" . $this->strencode( $s ) . "'";
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function addIdentifierQuotes( $s ) {
		return '"' . str_replace( '"', '""', $s ) . '"';
	}

	/**
	 * Returns if the given identifier looks quoted or not according to
	 * the database convention for quoting identifiers
	 *
	 * @stable to override
	 * @note Do not use this to determine if untrusted input is safe.
	 *   A malicious user can trick this function.
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return $name[0] == '"' && substr( $name, -1, 1 ) == '"';
	}

	/**
	 * @stable to override
	 * @param string $s
	 * @param string $escapeChar
	 * @return string
	 */
	protected function escapeLikeInternal( $s, $escapeChar = '`' ) {
		return str_replace( [ $escapeChar, '%', '_' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_" ],
			$s );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function buildLike( $param, ...$params ) {
		if ( is_array( $param ) ) {
			$params = $param;
		} else {
			$params = func_get_args();
		}

		$s = '';

		// We use ` instead of \ as the default LIKE escape character, since addQuotes()
		// may escape backslashes, creating problems of double escaping. The `
		// character has good cross-DBMS compatibility, avoiding special operators
		// in MS SQL like ^ and %
		$escapeChar = '`';

		foreach ( $params as $value ) {
			if ( $value instanceof LikeMatch ) {
				$s .= $value->toString();
			} else {
				$s .= $this->escapeLikeInternal( $value, $escapeChar );
			}
		}

		return ' LIKE ' .
			$this->addQuotes( $s ) . ' ESCAPE ' . $this->addQuotes( $escapeChar ) . ' ';
	}

	public function anyChar() {
		return new LikeMatch( '_' );
	}

	public function anyString() {
		return new LikeMatch( '%' );
	}

	public function nextSequenceValue( $seqName ) {
		return null;
	}

	/**
	 * USE INDEX clause.
	 *
	 * This can be used as optimisation in queries that affect tables with multiple
	 * indexes if the database does not pick the most optimal one by default.
	 * The "right" index might vary between database backends and versions thereof,
	 * as such in practice this is biased toward specifically improving performance
	 * of large wiki farms that use MySQL or MariaDB (like Wikipedia).
	 *
	 * @stable to override
	 * @param string $index
	 * @return string
	 */
	public function useIndexClause( $index ) {
		return '';
	}

	/**
	 * IGNORE INDEX clause.
	 *
	 * The inverse of Database::useIndexClause.
	 *
	 * @stable to override
	 * @param string $index
	 * @return string
	 */
	public function ignoreIndexClause( $index ) {
		return '';
	}

	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return;
		}

		if ( $uniqueKeys ) {
			$uniqueKeys = $this->normalizeUpsertKeys( $uniqueKeys );
			$this->doReplace( $table, $uniqueKeys, $rows, $fname );
		} else {
			$this->queryLogger->warning(
				__METHOD__ . " called with no unique keys",
				[ 'exception' => new RuntimeException() ]
			);
			$this->doInsert( $table, $rows, $fname );
		}
	}

	/**
	 * @see Database::replace()
	 * @stable to override
	 * @param string $table
	 * @param string[][] $uniqueKeys Non-empty list of unique keys
	 * @param array $rows Non-empty list of rows
	 * @param string $fname
	 * @since 1.35
	 */
	protected function doReplace( $table, array $uniqueKeys, array $rows, $fname ) {
		$affectedRowCount = 0;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Delete any conflicting rows (including ones inserted from $rows)
				$sqlCondition = $this->makeConditionCollidesUponKeys( [ $row ], $uniqueKeys );
				$this->delete( $table, [ $sqlCondition ], $fname );
				$affectedRowCount += $this->affectedRows();
				// Now insert the row
				$this->insert( $table, $row, $fname );
				$affectedRowCount += $this->affectedRows();
			}
			$this->endAtomic( $fname );
		} catch ( Throwable $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->affectedRowCount = $affectedRowCount;
	}

	/**
	 * @param array[] $rows Non-empty list of rows
	 * @param string[] $uniqueKey List of columns that define a single unique index
	 * @return string SQL conditions to filter existing rows to those with counterparts in $rows
	 */
	private function makeConditionCollidesUponKey( array $rows, array $uniqueKey ) {
		if ( !$rows ) {
			throw new DBUnexpectedError( $this, "Empty row array" );
		} elseif ( !$uniqueKey ) {
			throw new DBUnexpectedError( $this, "Empty unique key array" );
		}

		if ( count( $uniqueKey ) == 1 ) {
			// Use a simple IN(...) clause
			$column = reset( $uniqueKey );
			$values = array_column( $rows, $column );
			if ( count( $values ) !== count( $rows ) ) {
				throw new DBUnexpectedError( $this, "Missing values for unique key ($column)" );
			}

			return $this->makeList( [ $column => $values ], self::LIST_AND );
		}

		$disjunctions = [];
		foreach ( $rows as $row ) {
			$rowKeyMap = array_intersect_key( $row, array_flip( $uniqueKey ) );
			if ( count( $rowKeyMap ) != count( $uniqueKey ) ) {
				throw new DBUnexpectedError(
					$this,
					"Missing values for unique key (" . implode( ',', $uniqueKey ) . ")"
				);
			}
			$disjunctions[] = $this->makeList( $rowKeyMap, self::LIST_AND );
		}

		return count( $disjunctions ) > 1
			? $this->makeList( $disjunctions, self::LIST_OR )
			: $disjunctions[0];
	}

	/**
	 * @param array[] $rows Non-empty list of rows
	 * @param string[][] $uniqueKeys List of column lists that each define a unique index
	 * @return string SQL conditions to filter existing rows to those with counterparts in $rows
	 * @since 1.35
	 */
	final protected function makeConditionCollidesUponKeys( array $rows, array $uniqueKeys ) {
		if ( !$uniqueKeys ) {
			throw new DBUnexpectedError( $this, "Empty unique key array" );
		}

		$disjunctions = [];
		foreach ( $uniqueKeys as $uniqueKey ) {
			$disjunctions[] = $this->makeConditionCollidesUponKey( $rows, $uniqueKey );
		}

		return count( $disjunctions ) > 1
			? $this->makeList( $disjunctions, self::LIST_OR )
			: $disjunctions[0];
	}

	public function upsert( $table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__ ) {
		$rows = $this->normalizeRowArray( $rows );
		if ( !$rows ) {
			return true;
		}

		if ( $uniqueKeys ) {
			$uniqueKeys = $this->normalizeUpsertKeys( $uniqueKeys );
			$this->doUpsert( $table, $rows, $uniqueKeys, $set, $fname );
		} else {
			$this->queryLogger->warning(
				__METHOD__ . " called with no unique keys",
				[ 'exception' => new RuntimeException() ]
			);
			$this->doInsert( $table, $rows, $fname );
		}

		return true;
	}

	/**
	 * @see Database::upsert()
	 * @stable to override
	 * @param string $table
	 * @param array[] $rows Non-empty list of rows
	 * @param string[][] $uniqueKeys Non-empty list of unique keys
	 * @param array $set
	 * @param string $fname
	 * @since 1.35
	 */
	protected function doUpsert( $table, array $rows, array $uniqueKeys, array $set, $fname ) {
		$affectedRowCount = 0;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Update any existing conflicting rows (including ones inserted from $rows)
				$sqlConditions = $this->makeConditionCollidesUponKeys( [ $row ], $uniqueKeys );
				$this->update( $table, $set, [ $sqlConditions ], $fname );
				$rowsUpdated = $this->affectedRows();
				$affectedRowCount += $rowsUpdated;
				if ( $rowsUpdated <= 0 ) {
					// Now insert the row if there are no conflicts
					$this->insert( $table, $row, $fname );
					$affectedRowCount += $this->affectedRows();
				}
			}
			$this->endAtomic( $fname );
		} catch ( Throwable $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->affectedRowCount = $affectedRowCount;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds,
		$fname = __METHOD__
	) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, self::LIST_AND );
		}
		$sql .= ')';

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SHOW COLUMNS FROM $table LIKE \"$field\"";
		$res = $this->query( $sql, __METHOD__, self::QUERY_CHANGE_NONE );
		$row = $this->fetchObject( $res );

		$m = [];

		if ( preg_match( '/\((.*)\)/', $row->Type, $m ) ) {
			$size = $m[1];
		} else {
			$size = -1;
		}

		return $size;
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$this->assertConditionIsNotEmpty( $conds, __METHOD__, false );

		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";

		if ( $conds !== IDatabase::ALL_ROWS ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );

		return true;
	}

	final public function insertSelect(
		$destTable,
		$srcTable,
		$varMap,
		$conds,
		$fname = __METHOD__,
		$insertOptions = [],
		$selectOptions = [],
		$selectJoinConds = []
	) {
		static $hints = [ 'NO_AUTO_COLUMNS' ];

		$insertOptions = $this->normalizeOptions( $insertOptions );
		$selectOptions = $this->normalizeOptions( $selectOptions );

		if ( $this->cliMode && $this->isInsertSelectSafe( $insertOptions, $selectOptions ) ) {
			// For massive migrations with downtime, we don't want to select everything
			// into memory and OOM, so do all this native on the server side if possible.
			$this->doInsertSelectNative(
				$destTable,
				$srcTable,
				$varMap,
				$conds,
				$fname,
				array_diff( $insertOptions, $hints ),
				$selectOptions,
				$selectJoinConds
			);
		} else {
			$this->doInsertSelectGeneric(
				$destTable,
				$srcTable,
				$varMap,
				$conds,
				$fname,
				array_diff( $insertOptions, $hints ),
				$selectOptions,
				$selectJoinConds
			);
		}

		return true;
	}

	/**
	 * @stable to override
	 * @param array $insertOptions INSERT options
	 * @param array $selectOptions SELECT options
	 * @return bool Whether an INSERT SELECT with these options will be replication safe
	 * @since 1.31
	 */
	protected function isInsertSelectSafe( array $insertOptions, array $selectOptions ) {
		return true;
	}

	/**
	 * Implementation of insertSelect() based on select() and insert()
	 *
	 * @see IDatabase::insertSelect()
	 * @param string $destTable
	 * @param string|array $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 * @since 1.35
	 */
	protected function doInsertSelectGeneric(
		$destTable,
		$srcTable,
		array $varMap,
		$conds,
		$fname,
		array $insertOptions,
		array $selectOptions,
		$selectJoinConds
	) {
		// For web requests, do a locking SELECT and then INSERT. This puts the SELECT burden
		// on only the master (without needing row-based-replication). It also makes it easy to
		// know how big the INSERT is going to be.
		$fields = [];
		foreach ( $varMap as $dstColumn => $sourceColumnOrSql ) {
			$fields[] = $this->fieldNameWithAlias( $sourceColumnOrSql, $dstColumn );
		}
		$res = $this->select(
			$srcTable,
			implode( ',', $fields ),
			$conds,
			$fname,
			array_merge( $selectOptions, [ 'FOR UPDATE' ] ),
			$selectJoinConds
		);
		if ( !$res ) {
			return;
		}

		$affectedRowCount = 0;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			$rows = [];
			foreach ( $res as $row ) {
				$rows[] = (array)$row;
			}
			// Avoid inserts that are too huge
			$rowBatches = array_chunk( $rows, $this->nonNativeInsertSelectBatchSize );
			foreach ( $rowBatches as $rows ) {
				$this->insert( $destTable, $rows, $fname, $insertOptions );
				$affectedRowCount += $this->affectedRows();
			}
		} catch ( Throwable $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->endAtomic( $fname );
		$this->affectedRowCount = $affectedRowCount;
	}

	/**
	 * Native server-side implementation of insertSelect() for situations where
	 * we don't want to select everything into memory
	 *
	 * @see IDatabase::insertSelect()
	 * @param string $destTable
	 * @param string|array $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 * @since 1.35
	 */
	protected function doInsertSelectNative(
		$destTable,
		$srcTable,
		array $varMap,
		$conds,
		$fname,
		array $insertOptions,
		array $selectOptions,
		$selectJoinConds
	) {
		list( $sqlVerb, $sqlOpts ) = $this->isFlagInOptions( 'IGNORE', $insertOptions )
			? $this->makeInsertNonConflictingVerbAndOptions()
			: [ 'INSERT INTO', '' ];
		$encDstTable = $this->tableName( $destTable );
		$sqlDstColumns = implode( ',', array_keys( $varMap ) );
		$selectSql = $this->selectSQLText(
			$srcTable,
			array_values( $varMap ),
			$conds,
			$fname,
			$selectOptions,
			$selectJoinConds
		);

		$sql = rtrim( "$sqlVerb $encDstTable ($sqlDstColumns) $selectSql $sqlOpts" );

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function limitResult( $sql, $limit, $offset = false ) {
		if ( !is_numeric( $limit ) ) {
			throw new DBUnexpectedError(
				$this,
				"Invalid non-numeric limit passed to " . __METHOD__
			);
		}
		// This version works in MySQL and SQLite. It will very likely need to be
		// overridden for most other RDBMS subclasses.
		return "$sql LIMIT "
			. ( ( is_numeric( $offset ) && $offset != 0 ) ? "{$offset}," : "" )
			. "{$limit} ";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unionQueries( $sqls, $all ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';

		return '(' . implode( $glue, $sqls ) . ')';
	}

	public function unionConditionPermutations(
		$table, $vars, array $permute_conds, $extra_conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		// First, build the Cartesian product of $permute_conds
		$conds = [ [] ];
		foreach ( $permute_conds as $field => $values ) {
			if ( !$values ) {
				// Skip empty $values
				continue;
			}
			$values = array_unique( $values ); // For sanity
			$newConds = [];
			foreach ( $conds as $cond ) {
				foreach ( $values as $value ) {
					$cond[$field] = $value;
					$newConds[] = $cond; // Arrays are by-value, not by-reference, so this works
				}
			}
			$conds = $newConds;
		}

		$extra_conds = $extra_conds === '' ? [] : (array)$extra_conds;

		// If there's just one condition and no subordering, hand off to
		// selectSQLText directly.
		if ( count( $conds ) === 1 &&
			( !isset( $options['INNER ORDER BY'] ) || !$this->unionSupportsOrderAndLimit() )
		) {
			return $this->selectSQLText(
				$table, $vars, $conds[0] + $extra_conds, $fname, $options, $join_conds
			);
		}

		// Otherwise, we need to pull out the order and limit to apply after
		// the union. Then build the SQL queries for each set of conditions in
		// $conds. Then union them together (using UNION ALL, because the
		// product *should* already be distinct).
		$orderBy = $this->makeOrderBy( $options );
		$limit = $options['LIMIT'] ?? null;
		$offset = $options['OFFSET'] ?? false;
		$all = empty( $options['NOTALL'] ) && !in_array( 'NOTALL', $options );
		if ( !$this->unionSupportsOrderAndLimit() ) {
			unset( $options['ORDER BY'], $options['LIMIT'], $options['OFFSET'] );
		} else {
			if ( array_key_exists( 'INNER ORDER BY', $options ) ) {
				$options['ORDER BY'] = $options['INNER ORDER BY'];
			}
			if ( $limit !== null && is_numeric( $offset ) && $offset != 0 ) {
				// We need to increase the limit by the offset rather than
				// using the offset directly, otherwise it'll skip incorrectly
				// in the subqueries.
				$options['LIMIT'] = $limit + $offset;
				unset( $options['OFFSET'] );
			}
		}

		$sqls = [];
		foreach ( $conds as $cond ) {
			$sqls[] = $this->selectSQLText(
				$table, $vars, $cond + $extra_conds, $fname, $options, $join_conds
			);
		}
		$sql = $this->unionQueries( $sqls, $all ) . $orderBy;
		if ( $limit !== null ) {
			$sql = $this->limitResult( $sql, $limit, $offset );
		}

		return $sql;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function conditional( $cond, $trueVal, $falseVal ) {
		if ( is_array( $cond ) ) {
			$cond = $this->makeList( $cond, self::LIST_AND );
		}

		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getServerUptime() {
		return 0;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function wasDeadlock() {
		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function wasLockTimeout() {
		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function wasConnectionLoss() {
		return $this->wasConnectionError( $this->lastErrno() );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function wasReadOnlyError() {
		return false;
	}

	public function wasErrorReissuable() {
		return (
			$this->wasDeadlock() ||
			$this->wasLockTimeout() ||
			$this->wasConnectionLoss()
		);
	}

	/**
	 * Do not use this method outside of Database/DBError classes
	 *
	 * @stable to override
	 * @param int|string $errno
	 * @return bool Whether the given query error was a connection drop
	 */
	public function wasConnectionError( $errno ) {
		return false;
	}

	/**
	 * @stable to override
	 * @return bool Whether it is known that the last query error only caused statement rollback
	 * @note This is for backwards compatibility for callers catching DBError exceptions in
	 *   order to ignore problems like duplicate key errors or foriegn key violations
	 * @since 1.31
	 */
	protected function wasKnownStatementRollbackError() {
		return false; // don't know; it could have caused a transaction rollback
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function deadlockLoop( ...$args ) {
		$function = array_shift( $args );
		$tries = self::$DEADLOCK_TRIES;

		$this->begin( __METHOD__ );

		$retVal = null;
		/** @var Throwable $e */
		$e = null;
		do {
			try {
				$retVal = $function( ...$args );
				break;
			} catch ( DBQueryError $e ) {
				if ( $this->wasDeadlock() ) {
					// Retry after a randomized delay
					usleep( mt_rand( self::$DEADLOCK_DELAY_MIN, self::$DEADLOCK_DELAY_MAX ) );
				} else {
					// Throw the error back up
					throw $e;
				}
			}
		} while ( --$tries > 0 );

		if ( $tries <= 0 ) {
			// Too many deadlocks; give up
			$this->rollback( __METHOD__ );
			throw $e;
		} else {
			$this->commit( __METHOD__ );

			return $retVal;
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		# Real waits are implemented in the subclass.
		return 0;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getReplicaPos() {
		# Stub
		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getMasterPos() {
		# Stub
		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function serverIsReadOnly() {
		return false;
	}

	final public function onTransactionResolution( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() ) {
			throw new DBUnexpectedError( $this, "No transaction is active" );
		}
		$this->trxEndCallbacks[] = [ $callback, $fname, $this->currentAtomicSectionId() ];
	}

	final public function onTransactionCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() && $this->getTransactionRoundId() ) {
			// Start an implicit transaction similar to how query() does
			$this->begin( __METHOD__, self::TRANSACTION_INTERNAL );
			$this->trxAutomatic = true;
		}

		$this->trxIdleCallbacks[] = [ $callback, $fname, $this->currentAtomicSectionId() ];
		if ( !$this->trxLevel() ) {
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_IDLE );
		}
	}

	final public function onTransactionIdle( callable $callback, $fname = __METHOD__ ) {
		$this->onTransactionCommitOrIdle( $callback, $fname );
	}

	final public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() && $this->getTransactionRoundId() ) {
			// Start an implicit transaction similar to how query() does
			$this->begin( __METHOD__, self::TRANSACTION_INTERNAL );
			$this->trxAutomatic = true;
		}

		if ( $this->trxLevel() ) {
			$this->trxPreCommitCallbacks[] = [ $callback, $fname, $this->currentAtomicSectionId() ];
		} else {
			// No transaction is active nor will start implicitly, so make one for this callback
			$this->startAtomic( __METHOD__, self::ATOMIC_CANCELABLE );
			try {
				$callback( $this );
				$this->endAtomic( __METHOD__ );
			} catch ( Throwable $e ) {
				$this->cancelAtomic( __METHOD__ );
				throw $e;
			}
		}
	}

	final public function onAtomicSectionCancel( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() || !$this->trxAtomicLevels ) {
			throw new DBUnexpectedError( $this, "No atomic section is open (got $fname)" );
		}
		$this->trxSectionCancelCallbacks[] = [ $callback, $fname, $this->currentAtomicSectionId() ];
	}

	/**
	 * @return AtomicSectionIdentifier|null ID of the topmost atomic section level
	 */
	private function currentAtomicSectionId() {
		if ( $this->trxLevel() && $this->trxAtomicLevels ) {
			$levelInfo = end( $this->trxAtomicLevels );

			return $levelInfo[1];
		}

		return null;
	}

	/**
	 * Hoist callback ownership for callbacks in a section to a parent section.
	 * All callbacks should have an owner that is present in trxAtomicLevels.
	 * @param AtomicSectionIdentifier $old
	 * @param AtomicSectionIdentifier $new
	 */
	private function reassignCallbacksForSection(
		AtomicSectionIdentifier $old, AtomicSectionIdentifier $new
	) {
		foreach ( $this->trxPreCommitCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxPreCommitCallbacks[$key][2] = $new;
			}
		}
		foreach ( $this->trxIdleCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxIdleCallbacks[$key][2] = $new;
			}
		}
		foreach ( $this->trxEndCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxEndCallbacks[$key][2] = $new;
			}
		}
		foreach ( $this->trxSectionCancelCallbacks as $key => $info ) {
			if ( $info[2] === $old ) {
				$this->trxSectionCancelCallbacks[$key][2] = $new;
			}
		}
	}

	/**
	 * Update callbacks that were owned by cancelled atomic sections.
	 *
	 * Callbacks for "on commit" should never be run if they're owned by a
	 * section that won't be committed.
	 *
	 * Callbacks for "on resolution" need to reflect that the section was
	 * rolled back, even if the transaction as a whole commits successfully.
	 *
	 * Callbacks for "on section cancel" should already have been consumed,
	 * but errors during the cancellation itself can prevent that while still
	 * destroying the section. Hoist any such callbacks to the new top section,
	 * which we assume will itself have to be cancelled or rolled back to
	 * resolve the error.
	 *
	 * @param AtomicSectionIdentifier[] $sectionIds ID of an actual savepoint
	 * @param AtomicSectionIdentifier|null $newSectionId New top section ID.
	 * @throws UnexpectedValueException
	 */
	private function modifyCallbacksForCancel(
		array $sectionIds, AtomicSectionIdentifier $newSectionId = null
	) {
		// Cancel the "on commit" callbacks owned by this savepoint
		$this->trxIdleCallbacks = array_filter(
			$this->trxIdleCallbacks,
			function ( $entry ) use ( $sectionIds ) {
				return !in_array( $entry[2], $sectionIds, true );
			}
		);
		$this->trxPreCommitCallbacks = array_filter(
			$this->trxPreCommitCallbacks,
			function ( $entry ) use ( $sectionIds ) {
				return !in_array( $entry[2], $sectionIds, true );
			}
		);
		// Make "on resolution" callbacks owned by this savepoint to perceive a rollback
		foreach ( $this->trxEndCallbacks as $key => $entry ) {
			if ( in_array( $entry[2], $sectionIds, true ) ) {
				$callback = $entry[0];
				$this->trxEndCallbacks[$key][0] = function () use ( $callback ) {
					return $callback( self::TRIGGER_ROLLBACK, $this );
				};
				// This "on resolution" callback no longer belongs to a section.
				$this->trxEndCallbacks[$key][2] = null;
			}
		}
		// Hoist callback ownership for section cancel callbacks to the new top section
		foreach ( $this->trxSectionCancelCallbacks as $key => $entry ) {
			if ( in_array( $entry[2], $sectionIds, true ) ) {
				$this->trxSectionCancelCallbacks[$key][2] = $newSectionId;
			}
		}
	}

	final public function setTransactionListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->trxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->trxRecurringCallbacks[$name] );
		}
	}

	/**
	 * Whether to disable running of post-COMMIT/ROLLBACK callbacks
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @param bool $suppress
	 * @since 1.28
	 */
	final public function setTrxEndCallbackSuppression( $suppress ) {
		$this->trxEndCallbacksSuppressed = $suppress;
	}

	/**
	 * Actually consume and run any "on transaction idle/resolution" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @param int $trigger IDatabase::TRIGGER_* constant
	 * @return int Number of callbacks attempted
	 * @since 1.20
	 * @throws Exception
	 */
	public function runOnTransactionIdleCallbacks( $trigger ) {
		if ( $this->trxLevel() ) { // sanity
			throw new DBUnexpectedError( $this, __METHOD__ . ': a transaction is still open' );
		}

		if ( $this->trxEndCallbacksSuppressed ) {
			return 0;
		}

		$count = 0;
		$autoTrx = $this->getFlag( self::DBO_TRX ); // automatic begin() enabled?
		/** @var Throwable $e */
		$e = null; // first exception
		do { // callbacks may add callbacks :)
			$callbacks = array_merge(
				$this->trxIdleCallbacks,
				$this->trxEndCallbacks // include "transaction resolution" callbacks
			);
			$this->trxIdleCallbacks = []; // consumed (and recursion guard)
			$this->trxEndCallbacks = []; // consumed (recursion guard)

			// Only run trxSectionCancelCallbacks on rollback, not commit.
			// But always consume them.
			if ( $trigger === self::TRIGGER_ROLLBACK ) {
				$callbacks = array_merge( $callbacks, $this->trxSectionCancelCallbacks );
			}
			$this->trxSectionCancelCallbacks = []; // consumed (recursion guard)

			foreach ( $callbacks as $callback ) {
				++$count;
				list( $phpCallback ) = $callback;
				$this->clearFlag( self::DBO_TRX ); // make each query its own transaction
				try {
					call_user_func( $phpCallback, $trigger, $this );
				} catch ( Throwable $ex ) {
					call_user_func( $this->errorLogger, $ex );
					$e = $e ?: $ex;
					// Some callbacks may use startAtomic/endAtomic, so make sure
					// their transactions are ended so other callbacks don't fail
					if ( $this->trxLevel() ) {
						$this->rollback( __METHOD__, self::FLUSHING_INTERNAL );
					}
				} finally {
					if ( $autoTrx ) {
						$this->setFlag( self::DBO_TRX ); // restore automatic begin()
					} else {
						$this->clearFlag( self::DBO_TRX ); // restore auto-commit
					}
				}
			}
			// @phan-suppress-next-line PhanImpossibleConditionInLoop
		} while ( count( $this->trxIdleCallbacks ) );

		if ( $e instanceof Throwable ) {
			throw $e; // re-throw any first exception
		}

		return $count;
	}

	/**
	 * Actually consume and run any "on transaction pre-commit" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @since 1.22
	 * @return int Number of callbacks attempted
	 * @throws Exception
	 */
	public function runOnTransactionPreCommitCallbacks() {
		$count = 0;

		$e = null; // first exception
		do { // callbacks may add callbacks :)
			$callbacks = $this->trxPreCommitCallbacks;
			$this->trxPreCommitCallbacks = []; // consumed (and recursion guard)
			foreach ( $callbacks as $callback ) {
				try {
					++$count;
					list( $phpCallback ) = $callback;
					// @phan-suppress-next-line PhanUndeclaredInvokeInCallable
					$phpCallback( $this );
				} catch ( Throwable $ex ) {
					( $this->errorLogger )( $ex );
					$e = $e ?: $ex;
				}
			}
			// @phan-suppress-next-line PhanImpossibleConditionInLoop
		} while ( count( $this->trxPreCommitCallbacks ) );

		if ( $e instanceof Throwable ) {
			throw $e; // re-throw any first exception
		}

		return $count;
	}

	/**
	 * Actually run any "atomic section cancel" callbacks.
	 *
	 * @param int $trigger IDatabase::TRIGGER_* constant
	 * @param AtomicSectionIdentifier[]|null $sectionIds Section IDs to cancel,
	 *  null on transaction rollback
	 */
	private function runOnAtomicSectionCancelCallbacks(
		$trigger, array $sectionIds = null
	) {
		/** @var Throwable $e */
		$e = null; // first exception

		$notCancelled = [];
		do {
			$callbacks = $this->trxSectionCancelCallbacks;
			$this->trxSectionCancelCallbacks = []; // consumed (recursion guard)
			foreach ( $callbacks as $entry ) {
				if ( $sectionIds === null || in_array( $entry[2], $sectionIds, true ) ) {
					try {
						// @phan-suppress-next-line PhanUndeclaredInvokeInCallable
						$entry[0]( $trigger, $this );
					} catch ( Throwable $ex ) {
						( $this->errorLogger )( $ex );
						$e = $e ?: $ex;
					}
				} else {
					$notCancelled[] = $entry;
				}
			}
			// @phan-suppress-next-line PhanImpossibleConditionInLoop
		} while ( count( $this->trxSectionCancelCallbacks ) );
		$this->trxSectionCancelCallbacks = $notCancelled;

		if ( $e !== null ) {
			throw $e; // re-throw any first Throwable
		}
	}

	/**
	 * Actually run any "transaction listener" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @param int $trigger IDatabase::TRIGGER_* constant
	 * @throws Exception
	 * @since 1.20
	 */
	public function runTransactionListenerCallbacks( $trigger ) {
		if ( $this->trxEndCallbacksSuppressed ) {
			return;
		}

		/** @var Throwable $e */
		$e = null; // first exception

		foreach ( $this->trxRecurringCallbacks as $phpCallback ) {
			try {
				$phpCallback( $trigger, $this );
			} catch ( Throwable $ex ) {
				( $this->errorLogger )( $ex );
				$e = $e ?: $ex;
			}
		}

		if ( $e instanceof Throwable ) {
			throw $e; // re-throw any first exception
		}
	}

	/**
	 * Create a savepoint
	 *
	 * This is used internally to implement atomic sections. It should not be
	 * used otherwise.
	 *
	 * @stable to override
	 * @since 1.31
	 * @param string $identifier Identifier for the savepoint
	 * @param string $fname Calling function name
	 */
	protected function doSavepoint( $identifier, $fname ) {
		$sql = 'SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
		$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
	}

	/**
	 * Release a savepoint
	 *
	 * This is used internally to implement atomic sections. It should not be
	 * used otherwise.
	 *
	 * @stable to override
	 * @since 1.31
	 * @param string $identifier Identifier for the savepoint
	 * @param string $fname Calling function name
	 */
	protected function doReleaseSavepoint( $identifier, $fname ) {
		$sql = 'RELEASE SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
		$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
	}

	/**
	 * Rollback to a savepoint
	 *
	 * This is used internally to implement atomic sections. It should not be
	 * used otherwise.
	 *
	 * @stable to override
	 * @since 1.31
	 * @param string $identifier Identifier for the savepoint
	 * @param string $fname Calling function name
	 */
	protected function doRollbackToSavepoint( $identifier, $fname ) {
		$sql = 'ROLLBACK TO SAVEPOINT ' . $this->addIdentifierQuotes( $identifier );
		$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
	}

	/**
	 * @param string $fname
	 * @return string
	 */
	private function nextSavepointId( $fname ) {
		$savepointId = self::$SAVEPOINT_PREFIX . ++$this->trxAtomicCounter;
		if ( strlen( $savepointId ) > 30 ) {
			// 30 == Oracle's identifier length limit (pre 12c)
			// With a 22 character prefix, that puts the highest number at 99999999.
			throw new DBUnexpectedError(
				$this,
				'There have been an excessively large number of atomic sections in a transaction'
				. " started by $this->trxFname (at $fname)"
			);
		}

		return $savepointId;
	}

	final public function startAtomic(
		$fname = __METHOD__, $cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		$savepointId = $cancelable === self::ATOMIC_CANCELABLE ? self::$NOT_APPLICABLE : null;

		if ( !$this->trxLevel() ) {
			$this->begin( $fname, self::TRANSACTION_INTERNAL ); // sets trxAutomatic
			// If DBO_TRX is set, a series of startAtomic/endAtomic pairs will result
			// in all changes being in one transaction to keep requests transactional.
			if ( $this->getFlag( self::DBO_TRX ) ) {
				// Since writes could happen in between the topmost atomic sections as part
				// of the transaction, those sections will need savepoints.
				$savepointId = $this->nextSavepointId( $fname );
				$this->doSavepoint( $savepointId, $fname );
			} else {
				$this->trxAutomaticAtomic = true;
			}
		} elseif ( $cancelable === self::ATOMIC_CANCELABLE ) {
			$savepointId = $this->nextSavepointId( $fname );
			$this->doSavepoint( $savepointId, $fname );
		}

		$sectionId = new AtomicSectionIdentifier;
		$this->trxAtomicLevels[] = [ $fname, $sectionId, $savepointId ];
		$this->queryLogger->debug( 'startAtomic: entering level ' .
			( count( $this->trxAtomicLevels ) - 1 ) . " ($fname)" );

		return $sectionId;
	}

	final public function endAtomic( $fname = __METHOD__ ) {
		if ( !$this->trxLevel() || !$this->trxAtomicLevels ) {
			throw new DBUnexpectedError( $this, "No atomic section is open (got $fname)" );
		}

		// Check if the current section matches $fname
		$pos = count( $this->trxAtomicLevels ) - 1;
		list( $savedFname, $sectionId, $savepointId ) = $this->trxAtomicLevels[$pos];
		$this->queryLogger->debug( "endAtomic: leaving level $pos ($fname)" );

		if ( $savedFname !== $fname ) {
			throw new DBUnexpectedError(
				$this,
				"Invalid atomic section ended (got $fname but expected $savedFname)"
			);
		}

		// Remove the last section (no need to re-index the array)
		array_pop( $this->trxAtomicLevels );

		if ( !$this->trxAtomicLevels && $this->trxAutomaticAtomic ) {
			$this->commit( $fname, self::FLUSHING_INTERNAL );
		} elseif ( $savepointId !== null && $savepointId !== self::$NOT_APPLICABLE ) {
			$this->doReleaseSavepoint( $savepointId, $fname );
		}

		// Hoist callback ownership for callbacks in the section that just ended;
		// all callbacks should have an owner that is present in trxAtomicLevels.
		$currentSectionId = $this->currentAtomicSectionId();
		if ( $currentSectionId ) {
			$this->reassignCallbacksForSection( $sectionId, $currentSectionId );
		}
	}

	final public function cancelAtomic(
		$fname = __METHOD__, AtomicSectionIdentifier $sectionId = null
	) {
		if ( !$this->trxLevel() || !$this->trxAtomicLevels ) {
			throw new DBUnexpectedError( $this, "No atomic section is open (got $fname)" );
		}

		$excisedIds = [];
		$newTopSection = $this->currentAtomicSectionId();
		try {
			$excisedFnames = [];
			if ( $sectionId !== null ) {
				// Find the (last) section with the given $sectionId
				$pos = -1;
				foreach ( $this->trxAtomicLevels as $i => list( $asFname, $asId, $spId ) ) {
					if ( $asId === $sectionId ) {
						$pos = $i;
					}
				}
				if ( $pos < 0 ) {
					throw new DBUnexpectedError( $this, "Atomic section not found (for $fname)" );
				}
				// Remove all descendant sections and re-index the array
				$len = count( $this->trxAtomicLevels );
				for ( $i = $pos + 1; $i < $len; ++$i ) {
					$excisedFnames[] = $this->trxAtomicLevels[$i][0];
					$excisedIds[] = $this->trxAtomicLevels[$i][1];
				}
				$this->trxAtomicLevels = array_slice( $this->trxAtomicLevels, 0, $pos + 1 );
				$newTopSection = $this->currentAtomicSectionId();
			}

			// Check if the current section matches $fname
			$pos = count( $this->trxAtomicLevels ) - 1;
			list( $savedFname, $savedSectionId, $savepointId ) = $this->trxAtomicLevels[$pos];

			if ( $excisedFnames ) {
				$this->queryLogger->debug( "cancelAtomic: canceling level $pos ($savedFname) " .
					"and descendants " . implode( ', ', $excisedFnames ) );
			} else {
				$this->queryLogger->debug( "cancelAtomic: canceling level $pos ($savedFname)" );
			}

			if ( $savedFname !== $fname ) {
				throw new DBUnexpectedError(
					$this,
					"Invalid atomic section ended (got $fname but expected $savedFname)"
				);
			}

			// Remove the last section (no need to re-index the array)
			array_pop( $this->trxAtomicLevels );
			$excisedIds[] = $savedSectionId;
			$newTopSection = $this->currentAtomicSectionId();

			if ( $savepointId !== null ) {
				// Rollback the transaction to the state just before this atomic section
				if ( $savepointId === self::$NOT_APPLICABLE ) {
					$this->rollback( $fname, self::FLUSHING_INTERNAL );
					// Note: rollback() will run trxSectionCancelCallbacks
				} else {
					$this->doRollbackToSavepoint( $savepointId, $fname );
					$this->trxStatus = self::STATUS_TRX_OK; // no exception; recovered
					$this->trxStatusIgnoredCause = null;

					// Run trxSectionCancelCallbacks now.
					$this->runOnAtomicSectionCancelCallbacks( self::TRIGGER_CANCEL, $excisedIds );
				}
			} elseif ( $this->trxStatus > self::STATUS_TRX_ERROR ) {
				// Put the transaction into an error state if it's not already in one
				$this->trxStatus = self::STATUS_TRX_ERROR;
				$this->trxStatusCause = new DBUnexpectedError(
					$this,
					"Uncancelable atomic section canceled (got $fname)"
				);
			}
		} finally {
			// Fix up callbacks owned by the sections that were just cancelled.
			// All callbacks should have an owner that is present in trxAtomicLevels.
			$this->modifyCallbacksForCancel( $excisedIds, $newTopSection );
		}

		$this->affectedRowCount = 0; // for the sake of consistency
	}

	final public function doAtomicSection(
		$fname, callable $callback, $cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		$sectionId = $this->startAtomic( $fname, $cancelable );
		try {
			$res = $callback( $this, $fname );
		} catch ( Throwable $e ) {
			$this->cancelAtomic( $fname, $sectionId );

			throw $e;
		}
		$this->endAtomic( $fname );

		return $res;
	}

	final public function begin( $fname = __METHOD__, $mode = self::TRANSACTION_EXPLICIT ) {
		static $modes = [ self::TRANSACTION_EXPLICIT, self::TRANSACTION_INTERNAL ];
		if ( !in_array( $mode, $modes, true ) ) {
			throw new DBUnexpectedError( $this, "$fname: invalid mode parameter '$mode'" );
		}

		// Protect against mismatched atomic section, transaction nesting, and snapshot loss
		if ( $this->trxLevel() ) {
			if ( $this->trxAtomicLevels ) {
				$levels = $this->flatAtomicSectionList();
				$msg = "$fname: got explicit BEGIN while atomic section(s) $levels are open";
				throw new DBUnexpectedError( $this, $msg );
			} elseif ( !$this->trxAutomatic ) {
				$msg = "$fname: explicit transaction already active (from {$this->trxFname})";
				throw new DBUnexpectedError( $this, $msg );
			} else {
				$msg = "$fname: implicit transaction already active (from {$this->trxFname})";
				throw new DBUnexpectedError( $this, $msg );
			}
		} elseif ( $this->getFlag( self::DBO_TRX ) && $mode !== self::TRANSACTION_INTERNAL ) {
			$msg = "$fname: implicit transaction expected (DBO_TRX set)";
			throw new DBUnexpectedError( $this, $msg );
		}

		$this->assertHasConnectionHandle();

		$this->doBegin( $fname );
		$this->trxShortId = sprintf( '%06x', mt_rand( 0, 0xffffff ) );
		$this->trxStatus = self::STATUS_TRX_OK;
		$this->trxStatusIgnoredCause = null;
		$this->trxAtomicCounter = 0;
		$this->trxTimestamp = microtime( true );
		$this->trxFname = $fname;
		$this->trxDoneWrites = false;
		$this->trxAutomaticAtomic = false;
		$this->trxAtomicLevels = [];
		$this->trxWriteDuration = 0.0;
		$this->trxWriteQueryCount = 0;
		$this->trxWriteAffectedRows = 0;
		$this->trxWriteAdjDuration = 0.0;
		$this->trxWriteAdjQueryCount = 0;
		$this->trxWriteCallers = [];
		// First SELECT after BEGIN will establish the snapshot in REPEATABLE-READ.
		// Get an estimate of the replication lag before any such queries.
		$this->trxReplicaLag = null; // clear cached value first
		$this->trxReplicaLag = $this->getApproximateLagStatus()['lag'];
		// T147697: make explicitTrxActive() return true until begin() finishes. This way, no
		// caller will think its OK to muck around with the transaction just because startAtomic()
		// has not yet completed (e.g. setting trxAtomicLevels).
		$this->trxAutomatic = ( $mode === self::TRANSACTION_INTERNAL );
	}

	/**
	 * Issues the BEGIN command to the database server.
	 *
	 * @see Database::begin()
	 * @stable to override
	 * @param string $fname
	 * @throws DBError
	 */
	protected function doBegin( $fname ) {
		$this->query( 'BEGIN', $fname, self::QUERY_CHANGE_TRX );
	}

	final public function commit( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		static $modes = [ self::FLUSHING_ONE, self::FLUSHING_ALL_PEERS, self::FLUSHING_INTERNAL ];
		if ( !in_array( $flush, $modes, true ) ) {
			throw new DBUnexpectedError( $this, "$fname: invalid flush parameter '$flush'" );
		}

		if ( $this->trxLevel() && $this->trxAtomicLevels ) {
			// There are still atomic sections open; this cannot be ignored
			$levels = $this->flatAtomicSectionList();
			throw new DBUnexpectedError(
				$this,
				"$fname: got COMMIT while atomic sections $levels are still open"
			);
		}

		if ( $flush === self::FLUSHING_INTERNAL || $flush === self::FLUSHING_ALL_PEERS ) {
			if ( !$this->trxLevel() ) {
				return; // nothing to do
			} elseif ( !$this->trxAutomatic ) {
				throw new DBUnexpectedError(
					$this,
					"$fname: flushing an explicit transaction, getting out of sync"
				);
			}
		} elseif ( !$this->trxLevel() ) {
			$this->queryLogger->error(
				"$fname: no transaction to commit, something got out of sync" );
			return; // nothing to do
		} elseif ( $this->trxAutomatic ) {
			throw new DBUnexpectedError(
				$this,
				"$fname: expected mass commit of all peer transactions (DBO_TRX set)"
			);
		}

		$this->assertHasConnectionHandle();

		$this->runOnTransactionPreCommitCallbacks();

		$writeTime = $this->pendingWriteQueryDuration( self::ESTIMATE_DB_APPLY );
		$this->doCommit( $fname );
		$oldTrxShortId = $this->consumeTrxShortId();
		$this->trxStatus = self::STATUS_TRX_NONE;

		if ( $this->trxDoneWrites ) {
			$this->lastWriteTime = microtime( true );
			$this->trxProfiler->transactionWritingOut(
				$this->server,
				$this->getDomainID(),
				$oldTrxShortId,
				$writeTime,
				$this->trxWriteAffectedRows
			);
		}

		// With FLUSHING_ALL_PEERS, callbacks will be explicitly run later
		if ( $flush !== self::FLUSHING_ALL_PEERS ) {
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_COMMIT );
			$this->runTransactionListenerCallbacks( self::TRIGGER_COMMIT );
		}
	}

	/**
	 * Issues the COMMIT command to the database server.
	 *
	 * @stable to override
	 * @see Database::commit()
	 * @param string $fname
	 * @throws DBError
	 */
	protected function doCommit( $fname ) {
		if ( $this->trxLevel() ) {
			$this->query( 'COMMIT', $fname, self::QUERY_CHANGE_TRX );
		}
	}

	final public function rollback( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		$trxActive = $this->trxLevel();

		if ( $flush !== self::FLUSHING_INTERNAL
			&& $flush !== self::FLUSHING_ALL_PEERS
			&& $this->getFlag( self::DBO_TRX )
		) {
			throw new DBUnexpectedError(
				$this,
				"$fname: Expected mass rollback of all peer transactions (DBO_TRX set)"
			);
		}

		if ( $trxActive ) {
			$this->assertHasConnectionHandle();

			$this->doRollback( $fname );
			$oldTrxShortId = $this->consumeTrxShortId();
			$this->trxStatus = self::STATUS_TRX_NONE;
			$this->trxAtomicLevels = [];
			// Estimate the RTT via a query now that trxStatus is OK
			$writeTime = $this->pingAndCalculateLastTrxApplyTime();

			if ( $this->trxDoneWrites ) {
				$this->trxProfiler->transactionWritingOut(
					$this->server,
					$this->getDomainID(),
					$oldTrxShortId,
					$writeTime,
					$this->trxWriteAffectedRows
				);
			}
		}

		// Clear any commit-dependant callbacks. They might even be present
		// only due to transaction rounds, with no SQL transaction being active
		$this->trxIdleCallbacks = [];
		$this->trxPreCommitCallbacks = [];

		// With FLUSHING_ALL_PEERS, callbacks will be explicitly run later
		if ( $trxActive && $flush !== self::FLUSHING_ALL_PEERS ) {
			try {
				$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
			} catch ( Throwable $e ) {
				// already logged; finish and let LoadBalancer move on during mass-rollback
			}
			try {
				$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
			} catch ( Throwable $e ) {
				// already logged; let LoadBalancer move on during mass-rollback
			}

			$this->affectedRowCount = 0; // for the sake of consistency
		}
	}

	/**
	 * Issues the ROLLBACK command to the database server.
	 *
	 * @stable to override
	 * @see Database::rollback()
	 * @param string $fname
	 * @throws DBError
	 */
	protected function doRollback( $fname ) {
		if ( $this->trxLevel() ) {
			# Disconnects cause rollback anyway, so ignore those errors
			$this->query( 'ROLLBACK', $fname, self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_TRX );
		}
	}

	public function flushSnapshot( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		if ( $this->explicitTrxActive() ) {
			// Committing this transaction would break callers that assume it is still open
			throw new DBUnexpectedError(
				$this,
				"$fname: Cannot flush snapshot; " .
				"explicit transaction '{$this->trxFname}' is still open"
			);
		} elseif ( $this->writesOrCallbacksPending() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$this,
				"$fname: Cannot flush snapshot; " .
				"writes from transaction {$this->trxFname} are still pending ($fnames)"
			);
		} elseif (
			$this->trxLevel() &&
			$this->getTransactionRoundId() &&
			$flush !== self::FLUSHING_INTERNAL &&
			$flush !== self::FLUSHING_ALL_PEERS
		) {
			$this->queryLogger->warning(
				"$fname: Expected mass snapshot flush of all peer transactions " .
				"in the explicit transactions round '{$this->getTransactionRoundId()}'",
				[ 'exception' => new RuntimeException() ]
			);
		}

		$this->commit( $fname, self::FLUSHING_INTERNAL );
	}

	public function explicitTrxActive() {
		return $this->trxLevel() && ( $this->trxAtomicLevels || !$this->trxAutomatic );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function timestamp( $ts = 0 ) {
		$t = new ConvertibleTimestamp( $ts );
		// Let errors bubble up to avoid putting garbage in the DB
		return $t->getTimestamp( TS_MW );
	}

	public function timestampOrNull( $ts = null ) {
		if ( $ts === null ) {
			return null;
		} else {
			return $this->timestamp( $ts );
		}
	}

	public function affectedRows() {
		return ( $this->affectedRowCount === null )
			? $this->fetchAffectedRowCount() // default to driver value
			: $this->affectedRowCount;
	}

	/**
	 * @return int Number of retrieved rows according to the driver
	 */
	abstract protected function fetchAffectedRowCount();

	/**
	 * Take a query result and wrap it in an iterable result wrapper if necessary.
	 * Booleans are passed through as-is to indicate success/failure of write queries.
	 *
	 * Once upon a time, Database::query() returned a bare MySQL result
	 * resource, and it was necessary to call this function to convert it to
	 * a wrapper. Nowadays, raw database objects are never exposed to external
	 * callers, so this is unnecessary in external code.
	 *
	 * @param bool|IResultWrapper|resource $result
	 * @return bool|IResultWrapper
	 */
	protected function resultObject( $result ) {
		if ( !$result ) {
			return false; // failed query
		} elseif ( $result instanceof IResultWrapper ) {
			return $result;
		} elseif ( $result === true ) {
			return $result; // successful write query
		} else {
			return new ResultWrapper( $this, $result );
		}
	}

	public function ping( &$rtt = null ) {
		// Avoid hitting the server if it was hit recently
		if ( $this->isOpen() && ( microtime( true ) - $this->lastPing ) < self::$PING_TTL ) {
			if ( !func_num_args() || $this->lastRoundTripEstimate > 0 ) {
				$rtt = $this->lastRoundTripEstimate;
				return true; // don't care about $rtt
			}
		}

		// This will reconnect if possible or return false if not
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_NONE;
		$ok = ( $this->query( self::$PING_QUERY, __METHOD__, $flags ) !== false );
		if ( $ok ) {
			$rtt = $this->lastRoundTripEstimate;
		}

		return $ok;
	}

	/**
	 * Close any existing (dead) database connection and open a new connection
	 *
	 * @param string $fname
	 * @return bool True if new connection is opened successfully, false if error
	 */
	protected function replaceLostConnection( $fname ) {
		$this->closeConnection();
		$this->conn = null;

		$this->handleSessionLossPreconnect();

		try {
			$this->open(
				$this->server,
				$this->user,
				$this->password,
				$this->currentDomain->getDatabase(),
				$this->currentDomain->getSchema(),
				$this->tablePrefix()
			);
			$this->lastPing = microtime( true );
			$ok = true;

			$this->connLogger->warning(
				$fname . ': lost connection to {dbserver}; reconnected',
				[
					'dbserver' => $this->getServer(),
					'exception' => new RuntimeException()
				]
			);
		} catch ( DBConnectionError $e ) {
			$ok = false;

			$this->connLogger->error(
				$fname . ': lost connection to {dbserver} permanently',
				[ 'dbserver' => $this->getServer() ]
			);
		}

		$this->handleSessionLossPostconnect();

		return $ok;
	}

	public function getSessionLagStatus() {
		return $this->getRecordedTransactionLagStatus() ?: $this->getApproximateLagStatus();
	}

	/**
	 * Get the replica DB lag when the current transaction started
	 *
	 * This is useful when transactions might use snapshot isolation
	 * (e.g. REPEATABLE-READ in innodb), so the "real" lag of that data
	 * is this lag plus transaction duration. If they don't, it is still
	 * safe to be pessimistic. This returns null if there is no transaction.
	 *
	 * This returns null if the lag status for this transaction was not yet recorded.
	 *
	 * @return array|null ('lag': seconds or false on error, 'since': UNIX timestamp of BEGIN)
	 * @since 1.27
	 */
	final protected function getRecordedTransactionLagStatus() {
		return ( $this->trxLevel() && $this->trxReplicaLag !== null )
			? [ 'lag' => $this->trxReplicaLag, 'since' => $this->trxTimestamp() ]
			: null;
	}

	/**
	 * Get a replica DB lag estimate for this server at the start of a transaction
	 *
	 * This is a no-op unless the server is known a priori to be a replica DB
	 *
	 * @stable to override
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of estimate)
	 * @since 1.27
	 */
	protected function getApproximateLagStatus() {
		return [
			'lag' => ( $this->topologyRole === self::ROLE_STREAMING_REPLICA ) ? $this->getLag() : 0,
			'since' => microtime( true )
		];
	}

	/**
	 * Merge the result of getSessionLagStatus() for several DBs
	 * using the most pessimistic values to estimate the lag of
	 * any data derived from them in combination
	 *
	 * This is information is useful for caching modules
	 *
	 * @see WANObjectCache::set()
	 * @see WANObjectCache::getWithSetCallback()
	 *
	 * @param IDatabase $db1
	 * @param IDatabase|null $db2 [optional]
	 * @return array Map of values:
	 *   - lag: highest lag of any of the DBs or false on error (e.g. replication stopped)
	 *   - since: oldest UNIX timestamp of any of the DB lag estimates
	 *   - pending: whether any of the DBs have uncommitted changes
	 * @throws DBError
	 * @since 1.27
	 */
	public static function getCacheSetOptions( IDatabase $db1, IDatabase $db2 = null ) {
		$res = [ 'lag' => 0, 'since' => INF, 'pending' => false ];
		foreach ( func_get_args() as $db ) {
			/** @var IDatabase $db */
			$status = $db->getSessionLagStatus();
			if ( $status['lag'] === false ) {
				$res['lag'] = false;
			} elseif ( $res['lag'] !== false ) {
				$res['lag'] = max( $res['lag'], $status['lag'] );
			}
			$res['since'] = min( $res['since'], $status['since'] );
			$res['pending'] = $res['pending'] ?: $db->writesPending();
		}

		return $res;
	}

	public function getLag() {
		if ( $this->topologyRole === self::ROLE_STREAMING_MASTER ) {
			return 0; // this is the master
		} elseif ( $this->topologyRole === self::ROLE_STATIC_CLONE ) {
			return 0; // static dataset
		}

		return $this->doGetLag();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function doGetLag() {
		return 0;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function maxListLen() {
		return 0;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function encodeBlob( $b ) {
		return $b;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}
		return $b;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setSessionOptions( array $options ) {
	}

	public function sourceFile(
		$filename,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = false,
		callable $inputCallback = null
	) {
		AtEase::suppressWarnings();
		$fp = fopen( $filename, 'r' );
		AtEase::restoreWarnings();

		if ( $fp === false ) {
			throw new RuntimeException( "Could not open \"{$filename}\"" );
		}

		if ( !$fname ) {
			$fname = __METHOD__ . "( $filename )";
		}

		try {
			$error = $this->sourceStream(
				$fp, $lineCallback, $resultCallback, $fname, $inputCallback );
		} catch ( Throwable $e ) {
			fclose( $fp );
			throw $e;
		}

		fclose( $fp );

		return $error;
	}

	public function setSchemaVars( $vars ) {
		$this->schemaVars = is_array( $vars ) ? $vars : null;
	}

	public function sourceStream(
		$fp,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = __METHOD__,
		callable $inputCallback = null
	) {
		$delimiterReset = new ScopedCallback(
			function ( $delimiter ) {
				$this->delimiter = $delimiter;
			},
			[ $this->delimiter ]
		);
		$cmd = '';

		while ( !feof( $fp ) ) {
			if ( $lineCallback ) {
				call_user_func( $lineCallback );
			}

			$line = trim( fgets( $fp ) );

			if ( $line == '' ) {
				continue;
			}

			if ( $line[0] == '-' && $line[1] == '-' ) {
				continue;
			}

			if ( $cmd != '' ) {
				$cmd .= ' ';
			}

			$done = $this->streamStatementEnd( $cmd, $line );

			$cmd .= "$line\n";

			if ( $done || feof( $fp ) ) {
				$cmd = $this->replaceVars( $cmd );

				if ( $inputCallback ) {
					$callbackResult = $inputCallback( $cmd );

					if ( is_string( $callbackResult ) || !$callbackResult ) {
						$cmd = $callbackResult;
					}
				}

				if ( $cmd ) {
					$res = $this->query( $cmd, $fname );

					if ( $resultCallback ) {
						$resultCallback( $res, $this );
					}

					if ( $res === false ) {
						$err = $this->lastError();

						return "Query \"{$cmd}\" failed with error code \"$err\".\n";
					}
				}
				$cmd = '';
			}
		}

		ScopedCallback::consume( $delimiterReset );
		return true;
	}

	/**
	 * Called by sourceStream() to check if we've reached a statement end
	 *
	 * @stable to override
	 * @param string &$sql SQL assembled so far
	 * @param string &$newLine New line about to be added to $sql
	 * @return bool Whether $newLine contains end of the statement
	 */
	public function streamStatementEnd( &$sql, &$newLine ) {
		if ( $this->delimiter ) {
			$prev = $newLine;
			$newLine = preg_replace(
				'/' . preg_quote( $this->delimiter, '/' ) . '$/', '', $newLine );
			if ( $newLine != $prev ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Database independent variable replacement. Replaces a set of variables
	 * in an SQL statement with their contents as given by $this->getSchemaVars().
	 *
	 * Supports '{$var}' `{$var}` and / *$var* / (without the spaces) style variables.
	 *
	 * - '{$var}' should be used for text and is passed through the database's
	 *   addQuotes method.
	 * - `{$var}` should be used for identifiers (e.g. table and database names).
	 *   It is passed through the database's addIdentifierQuotes method which
	 *   can be overridden if the database uses something other than backticks.
	 * - / *_* / or / *$wgDBprefix* / passes the name that follows through the
	 *   database's tableName method.
	 * - / *i* / passes the name that follows through the database's indexName method.
	 * - In all other cases, / *$var* / is left unencoded. Except for table options,
	 *   its use should be avoided. In 1.24 and older, string encoding was applied.
	 *
	 * @stable to override
	 * @param string $ins SQL statement to replace variables in
	 * @return string The new SQL statement with variables replaced
	 */
	protected function replaceVars( $ins ) {
		$vars = $this->getSchemaVars();
		return preg_replace_callback(
			'!
				/\* (\$wgDBprefix|[_i]) \*/ (\w*) | # 1-2. tableName, indexName
				\'\{\$ (\w+) }\'                  | # 3. addQuotes
				`\{\$ (\w+) }`                    | # 4. addIdentifierQuotes
				/\*\$ (\w+) \*/                     # 5. leave unencoded
			!x',
			function ( $m ) use ( $vars ) {
				// Note: Because of <https://bugs.php.net/bug.php?id=51881>,
				// check for both nonexistent keys *and* the empty string.
				if ( isset( $m[1] ) && $m[1] !== '' ) {
					if ( $m[1] === 'i' ) {
						return $this->indexName( $m[2] );
					} else {
						return $this->tableName( $m[2] );
					}
				} elseif ( isset( $m[3] ) && $m[3] !== '' && array_key_exists( $m[3], $vars ) ) {
					return $this->addQuotes( $vars[$m[3]] );
				} elseif ( isset( $m[4] ) && $m[4] !== '' && array_key_exists( $m[4], $vars ) ) {
					return $this->addIdentifierQuotes( $vars[$m[4]] );
				} elseif ( isset( $m[5] ) && $m[5] !== '' && array_key_exists( $m[5], $vars ) ) {
					return $vars[$m[5]];
				} else {
					return $m[0];
				}
			},
			$ins
		);
	}

	/**
	 * Get schema variables. If none have been set via setSchemaVars(), then
	 * use some defaults from the current object.
	 *
	 * @return array
	 */
	protected function getSchemaVars() {
		return $this->schemaVars ?? $this->getDefaultSchemaVars();
	}

	/**
	 * Get schema variables to use if none have been set via setSchemaVars().
	 *
	 * Override this in derived classes to provide variables for tables.sql
	 * and SQL patch files.
	 *
	 * @stable to override
	 * @return array
	 */
	protected function getDefaultSchemaVars() {
		return [];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function lockIsFree( $lockName, $method ) {
		// RDBMs methods for checking named locks may or may not count this thread itself.
		// In MySQL, IS_FREE_LOCK() returns 0 if the thread already has the lock. This is
		// the behavior chosen by the interface for this method.
		return !isset( $this->sessionNamedLocks[$lockName] );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		$this->sessionNamedLocks[$lockName] = 1;

		return true;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unlock( $lockName, $method ) {
		unset( $this->sessionNamedLocks[$lockName] );

		return true;
	}

	public function getScopedLockAndFlush( $lockKey, $fname, $timeout ) {
		if ( $this->writesOrCallbacksPending() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$this,
				"$fname: Cannot flush pre-lock snapshot; " .
				"writes from transaction {$this->trxFname} are still pending ($fnames)"
			);
		}

		if ( !$this->lock( $lockKey, $fname, $timeout ) ) {
			return null;
		}

		$unlocker = new ScopedCallback( function () use ( $lockKey, $fname ) {
			if ( $this->trxLevel() ) {
				// There is a good chance an exception was thrown, causing any early return
				// from the caller. Let any error handler get a chance to issue rollback().
				// If there isn't one, let the error bubble up and trigger server-side rollback.
				$this->onTransactionResolution(
					function () use ( $lockKey, $fname ) {
						$this->unlock( $lockKey, $fname );
					},
					$fname
				);
			} else {
				$this->unlock( $lockKey, $fname );
			}
		} );

		$this->commit( $fname, self::FLUSHING_INTERNAL );

		return $unlocker;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function namedLocksEnqueue() {
		return false;
	}

	public function tableLocksHaveTransactionScope() {
		return true;
	}

	final public function lockTables( array $read, array $write, $method ) {
		if ( $this->writesOrCallbacksPending() ) {
			throw new DBUnexpectedError( $this, "Transaction writes or callbacks still pending" );
		}

		if ( $this->tableLocksHaveTransactionScope() ) {
			$this->startAtomic( $method );
		}

		return $this->doLockTables( $read, $write, $method );
	}

	/**
	 * Helper function for lockTables() that handles the actual table locking
	 *
	 * @stable to override
	 * @param array $read Array of tables to lock for read access
	 * @param array $write Array of tables to lock for write access
	 * @param string $method Name of caller
	 * @return true
	 */
	protected function doLockTables( array $read, array $write, $method ) {
		return true;
	}

	final public function unlockTables( $method ) {
		if ( $this->tableLocksHaveTransactionScope() ) {
			$this->endAtomic( $method );

			return true; // locks released on COMMIT/ROLLBACK
		}

		return $this->doUnlockTables( $method );
	}

	/**
	 * Helper function for unlockTables() that handles the actual table unlocking
	 *
	 * @stable to override
	 * @param string $method Name of caller
	 * @return true
	 */
	protected function doUnlockTables( $method ) {
		return true;
	}

	public function dropTable( $table, $fname = __METHOD__ ) {
		if ( !$this->tableExists( $table, $fname ) ) {
			return false;
		}

		$this->doDropTable( $table, $fname );

		return true;
	}

	/**
	 * @see Database::dropTable()
	 * @stable to override
	 * @param string $table
	 * @param string $fname
	 */
	protected function doDropTable( $table, $fname ) {
		// https://mariadb.com/kb/en/drop-table/
		// https://dev.mysql.com/doc/refman/8.0/en/drop-table.html
		// https://www.postgresql.org/docs/9.2/sql-truncate.html
		$sql = "DROP TABLE " . $this->tableName( $table ) . " CASCADE";
		$this->query( $sql, $fname, self::QUERY_IGNORE_DBO_TRX );
	}

	public function truncate( $tables, $fname = __METHOD__ ) {
		$tables = is_array( $tables ) ? $tables : [ $tables ];

		$tablesTruncate = [];
		foreach ( $tables as $table ) {
			// Skip TEMPORARY tables with no writes nor sequence updates detected.
			// This mostly is an optimization for integration testing.
			if ( !$this->isPristineTemporaryTable( $table ) ) {
				$tablesTruncate[] = $table;
			}
		}

		if ( $tablesTruncate ) {
			$this->doTruncate( $tablesTruncate, $fname );
		}
	}

	/**
	 * @see Database::truncate()
	 * @stable to override
	 * @param string[] $tables
	 * @param string $fname
	 */
	protected function doTruncate( array $tables, $fname ) {
		foreach ( $tables as $table ) {
			$sql = "TRUNCATE TABLE " . $this->tableName( $table );
			$this->query( $sql, $fname, self::QUERY_CHANGE_SCHEMA );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInfinity() {
		return 'infinity';
	}

	public function encodeExpiry( $expiry ) {
		return ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() )
			? $this->getInfinity()
			: $this->timestamp( $expiry );
	}

	public function decodeExpiry( $expiry, $format = TS_MW ) {
		if ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() ) {
			return 'infinity';
		}

		return ConvertibleTimestamp::convert( $format, $expiry );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setBigSelects( $value = true ) {
		// no-op
	}

	public function isReadOnly() {
		return ( $this->getReadOnlyReason() !== false );
	}

	/**
	 * @return array|bool Tuple of (read-only reason, "role" or "lb") or false if it is not
	 */
	protected function getReadOnlyReason() {
		if ( $this->topologyRole === self::ROLE_STREAMING_REPLICA ) {
			return [ 'Server is configured as a read-only replica database.', 'role' ];
		} elseif ( $this->topologyRole === self::ROLE_STATIC_CLONE ) {
			return [ 'Server is configured as a read-only static clone database.', 'role' ];
		}

		$reason = $this->getLBInfo( self::LB_READ_ONLY_REASON );
		if ( is_string( $reason ) ) {
			return [ $reason, 'lb' ];
		}

		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
	}

	/**
	 * @param int $field
	 * @param int $flags
	 * @return bool
	 * @since 1.34
	 */
	final protected function fieldHasBit( $field, $flags ) {
		return ( ( $field & $flags ) === $flags );
	}

	/**
	 * Get the underlying binding connection handle
	 *
	 * Makes sure the connection resource is set (disconnects and ping() failure can unset it).
	 * This catches broken callers than catch and ignore disconnection exceptions.
	 * Unlike checking isOpen(), this is safe to call inside of open().
	 *
	 * @stable to override
	 * @return mixed
	 * @throws DBUnexpectedError
	 * @since 1.26
	 */
	protected function getBindingHandle() {
		if ( !$this->conn ) {
			throw new DBUnexpectedError(
				$this,
				'DB connection was already closed or the connection dropped'
			);
		}

		return $this->conn;
	}

	public function __toString() {
		// spl_object_id is PHP >= 7.2
		$id = function_exists( 'spl_object_id' )
			? spl_object_id( $this )
			: spl_object_hash( $this );

		$description = $this->getType() . ' object #' . $id;
		if ( is_resource( $this->conn ) ) {
			$description .= ' (' . (string)$this->conn . ')'; // "resource id #<ID>"
		} elseif ( is_object( $this->conn ) ) {
			// spl_object_id is PHP >= 7.2
			$handleId = function_exists( 'spl_object_id' )
				? spl_object_id( $this->conn )
				: spl_object_hash( $this->conn );
			$description .= " (handle id #$handleId)";
		}

		return $description;
	}

	/**
	 * Make sure that copies do not share the same client binding handle
	 * @throws DBConnectionError
	 */
	public function __clone() {
		$this->connLogger->warning(
			"Cloning " . static::class . " is not recommended; forking connection",
			[ 'exception' => new RuntimeException() ]
		);

		if ( $this->isOpen() ) {
			// Open a new connection resource without messing with the old one
			$this->conn = null;
			$this->trxEndCallbacks = []; // don't copy
			$this->trxSectionCancelCallbacks = []; // don't copy
			$this->handleSessionLossPreconnect(); // no trx or locks anymore
			$this->open(
				$this->server,
				$this->user,
				$this->password,
				$this->currentDomain->getDatabase(),
				$this->currentDomain->getSchema(),
				$this->tablePrefix()
			);
			$this->lastPing = microtime( true );
		}
	}

	/**
	 * Called by serialize. Throw an exception when DB connection is serialized.
	 * This causes problems on some database engines because the connection is
	 * not restored on unserialize.
	 */
	public function __sleep() {
		throw new RuntimeException( 'Database serialization may cause problems, since ' .
			'the connection is not restored on wakeup' );
	}

	/**
	 * Run a few simple sanity checks and close dangling connections
	 */
	public function __destruct() {
		if ( $this->trxLevel() && $this->trxDoneWrites ) {
			trigger_error( "Uncommitted DB writes (transaction from {$this->trxFname})" );
		}

		$danglingWriters = $this->pendingWriteAndCallbackCallers();
		if ( $danglingWriters ) {
			$fnames = implode( ', ', $danglingWriters );
			trigger_error( "DB transaction writes or callbacks still pending ($fnames)" );
		}

		if ( $this->conn ) {
			// Avoid connection leaks for sanity. Normally, resources close at script completion.
			// The connection might already be closed in PHP by now, so suppress warnings.
			AtEase::suppressWarnings();
			$this->closeConnection();
			AtEase::restoreWarnings();
			$this->conn = null;
		}
	}
}

/**
 * @deprecated since 1.28
 */
class_alias( Database::class, 'DatabaseBase' );

/**
 * @deprecated since 1.29
 */
class_alias( Database::class, 'Database' );
