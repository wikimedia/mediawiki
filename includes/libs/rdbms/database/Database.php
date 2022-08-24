<?php
/**
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
 */
namespace Wikimedia\Rdbms;

use BagOStuff;
use HashBagOStuff;
use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\RequestTimeout\CriticalSectionScope;
use Wikimedia\ScopedCallback;

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
	/** @var CriticalSectionProvider|null */
	protected $csProvider;
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
	/** @var TransactionManager */
	private $transactionManager;

	/** @var DatabaseDomain */
	protected $currentDomain;

	// phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.ObjectTypeHintVar
	/** @var object|resource|null Database connection */
	protected $conn;

	/** @var ?IDatabase Lazy handle to the most authoritative primary server for the dataset */
	protected $topologicalPrimaryConnRef;

	/** @var string|null Server that this instance is currently connected to */
	protected $server;
	/** @var string|null User that this instance is currently connected under the name of */
	protected $user;
	/** @var string|null Password used to establish the current connection */
	protected $password;
	/** @var string|null Readable name or host/IP of the database server */
	protected $serverName;
	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var string Agent name for query profiling */
	protected $agent;
	/** @var string Replication topology role of the server; one of the class ROLE_* constants */
	protected $topologyRole;
	/** @var array<string,mixed> Connection parameters used by initConnection() and open() */
	protected $connectionParams;
	/** @var string[]|int[]|float[] SQL variables values to use for all new connections */
	protected $connectionVariables;
	/** @var int Row batch size to use for emulated INSERT SELECT queries */
	protected $nonNativeInsertSelectBatchSize;

	/** @var int Current bit field of class DBO_* constants */
	protected $flags;
	/** @var bool Whether to use SSL connections */
	protected $ssl;
	/** @var array Current LoadBalancer tracking information */
	protected $lbInfo = [];
	/** @var string|false Current SQL query delimiter */
	protected $delimiter = ';';

	/** @var string|bool|null Stashed value of html_errors INI setting */
	private $htmlErrors;
	/** @var int[] Prior flags member variable values */
	private $priorFlags = [];

	/** @var array<string,array> Map of (name => (UNIX time,trx ID)) for current lock() mutexes */
	protected $sessionNamedLocks = [];
	/** @var array<string,array> Map of (name => (type,pristine,trx ID)) for current temp tables */
	protected $sessionTempTables = [];

	/** @var array|null Replication lag estimate at the time of BEGIN for the last transaction */
	private $trxReplicaLagStatus = null;

	/** @var int|null Rows affected by the last query to query() or its CRUD wrappers */
	protected $affectedRowCount;

	/** @var float UNIX timestamp */
	private $lastPing = 0.0;
	/** @var string The last SQL query attempted */
	private $lastQuery = '';
	/** @var float|false UNIX timestamp of last write query */
	private $lastWriteTime = false;
	/** @var string|false */
	private $lastPhpError = false;
	/** @var float Query round trip time estimate */
	private $lastRoundTripEstimate = 0.0;

	/** @var int|null Current critical section numeric ID */
	private $csmId;
	/** @var string|null Last critical section caller name */
	private $csmFname;
	/** @var DBUnexpectedError|null Last unresolved critical section error */
	private $csmError;

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

	/** No errors occurred during the query */
	protected const ERR_NONE = 0;
	/** Retry query due to a connection loss detected while sending the query (session intact) */
	protected const ERR_RETRY_QUERY = 1;
	/** Abort query (no retries) due to a statement rollback (session/transaction intact) */
	protected const ERR_ABORT_QUERY = 2;
	/** Abort any current transaction, by rolling it back, due to an error during the query */
	protected const ERR_ABORT_TRX = 4;
	/** Abort and reset session due to server-side session-level state loss (locks, temp tables) */
	protected const ERR_ABORT_SESSION = 8;

	/** Assume that queries taking this long to yield connection loss errors are at fault */
	protected const DROPPED_CONN_BLAME_THRESHOLD_SEC = 3.0;

	/** @var string Idiom used when a cancelable atomic section started the transaction */
	private const NOT_APPLICABLE = 'n/a';

	/** @var int Writes to this temporary table do not affect lastDoneWrites() */
	private const TEMP_NORMAL = 1;
	/** @var int Writes to this temporary table effect lastDoneWrites() */
	private const TEMP_PSEUDO_PERMANENT = 2;

	/** @var int Number of times to re-try an operation in case of deadlock */
	private const DEADLOCK_TRIES = 4;
	/** @var int Minimum time to wait before retry, in microseconds */
	private const DEADLOCK_DELAY_MIN = 500000;
	/** @var int Maximum time to wait before retry */
	private const DEADLOCK_DELAY_MAX = 1500000;

	/** @var float How long before it is worth doing a dummy query to test the connection */
	private const PING_TTL = 1.0;
	/** @var string Dummy SQL query */
	private const PING_QUERY = 'SELECT 1 AS ping';

	/** @var string[] List of DBO_* flags that can be changed after connection */
	protected const MUTABLE_FLAGS = [
		'DBO_DEBUG',
		'DBO_NOBUFFER',
		'DBO_TRX',
		'DBO_DDLMODE',
	];
	/** @var int Bit field of all DBO_* flags that can be changed after connection */
	protected const DBO_MUTABLE = (
		self::DBO_DEBUG | self::DBO_NOBUFFER | self::DBO_TRX | self::DBO_DDLMODE
	);

	/** Hostname or IP address to use on all connections */
	protected const CONN_HOST = 'host';
	/** Database server username to use on all connections */
	protected const CONN_USER = 'user';
	/** Database server password to use on all connections */
	protected const CONN_PASSWORD = 'password';
	/** Database name to use on initial connection */
	protected const CONN_INITIAL_DB = 'dbname';
	/** Schema name to use on initial connection */
	protected const CONN_INITIAL_SCHEMA = 'schema';
	/** Table prefix to use on initial connection */
	protected const CONN_INITIAL_TABLE_PREFIX = 'tablePrefix';

	/** @var SQLPlatform */
	protected $platform;

	/**
	 * @note exceptions for missing libraries/drivers should be thrown in initConnection()
	 * @stable to call
	 * @param array $params Parameters passed from Database::factory()
	 */
	public function __construct( array $params ) {
		$this->transactionManager = new TransactionManager(
			$params['queryLogger'],
			$params['trxProfiler']
		);
		$this->connectionParams = [
			self::CONN_HOST => ( isset( $params['host'] ) && $params['host'] !== '' )
				? $params['host']
				: null,
			self::CONN_USER => ( isset( $params['user'] ) && $params['user'] !== '' )
				? $params['user']
				: null,
			self::CONN_INITIAL_DB => ( isset( $params['dbname'] ) && $params['dbname'] !== '' )
				? $params['dbname']
				: null,
			self::CONN_INITIAL_SCHEMA => ( isset( $params['schema'] ) && $params['schema'] !== '' )
				? $params['schema']
				: null,
			self::CONN_PASSWORD => is_string( $params['password'] ) ? $params['password'] : null,
			self::CONN_INITIAL_TABLE_PREFIX => (string)$params['tablePrefix']
		];

		$this->lbInfo = $params['lbInfo'] ?? [];
		$this->topologicalPrimaryConnRef = $params['topologicalPrimaryConnRef'] ?? null;
		$this->connectionVariables = $params['variables'] ?? [];
		// Set SQL mode, default is turning them all off, can be overridden or skipped with null
		if ( is_string( $params['sqlMode'] ?? null ) ) {
			$this->connectionVariables['sql_mode'] = $params['sqlMode'];
		}

		$this->flags = (int)$params['flags'];
		$this->ssl = $params['ssl'] ?? (bool)( $this->flags & self::DBO_SSL );
		$this->cliMode = (bool)$params['cliMode'];
		$this->agent = (string)$params['agent'];
		$this->serverName = $params['serverName'];
		$this->topologyRole = $params['topologyRole'];
		$this->nonNativeInsertSelectBatchSize = $params['nonNativeInsertSelectBatchSize'] ?? 10000;

		$this->srvCache = $params['srvCache'];
		$this->profiler = is_callable( $params['profiler'] ) ? $params['profiler'] : null;
		$this->connLogger = $params['connLogger'];
		$this->queryLogger = $params['queryLogger'];
		$this->replLogger = $params['replLogger'];
		$this->errorLogger = $params['errorLogger'];
		$this->deprecationLogger = $params['deprecationLogger'];

		$this->csProvider = $params['criticalSectionProvider'] ?? null;

		// Set initial dummy domain until open() sets the final DB/prefix
		$this->currentDomain = new DatabaseDomain(
			$params['dbname'] != '' ? $params['dbname'] : null,
			$params['schema'] != '' ? $params['schema'] : null,
			$params['tablePrefix']
		);
		$this->platform = new SQLPlatform(
			$this,
			$params['queryLogger'],
			$this->currentDomain
		);
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
			$this->connectionParams[self::CONN_HOST],
			$this->connectionParams[self::CONN_USER],
			$this->connectionParams[self::CONN_PASSWORD],
			$this->connectionParams[self::CONN_INITIAL_DB],
			$this->connectionParams[self::CONN_INITIAL_SCHEMA],
			$this->connectionParams[self::CONN_INITIAL_TABLE_PREFIX]
		);
	}

	/**
	 * Open a new connection to the database (closing any existing one)
	 *
	 * @param string|null $server Server host/address and optional port {@see connectionParams}
	 * @param string|null $user User name {@see connectionParams}
	 * @param string|null $password User password {@see connectionParams}
	 * @param string|null $db Database name
	 * @param string|null $schema Database schema name
	 * @param string $tablePrefix
	 * @throws DBConnectionError
	 */
	abstract protected function open( $server, $user, $password, $db, $schema, $tablePrefix );

	/**
	 * Construct a Database subclass instance given a database type and parameters
	 *
	 * This also connects to the database immediately upon object construction
	 *
	 * @param string $type A possible DB type (sqlite, mysql, postgres,...)
	 * @param array $params Parameter map with keys:
	 *   - host : The hostname or IP address of the database server
	 *   - user : The name of the database user the client operates under
	 *   - password : The password for the database user
	 *   - dbname : The name of the database to use where queries do not specify one.
	 *      The database must exist or an error might be thrown. Setting this to an empty string
	 *      will avoid any such errors and make the handle have no implicit database scope. This is
	 *      useful for queries like SHOW STATUS, CREATE DATABASE, or DROP DATABASE. Note that a
	 *      "database" in Postgres is roughly equivalent to an entire MySQL server. This the domain
	 *      in which user names and such are defined, e.g. users are database-specific in Postgres.
	 *   - schema : The database schema to use (if supported). A "schema" in Postgres is roughly
	 *      equivalent to a "database" in MySQL. Note that MySQL and SQLite do not use schemas.
	 *   - tablePrefix : Optional table prefix that is implicitly added on to all table names
	 *      recognized in queries. This can be used in place of schemas for handle site farms.
	 *   - flags : Optional bit field of DBO_* constants that define connection, protocol,
	 *      buffering, and transaction behavior. It is STRONGLY advised to leave the DBO_DEFAULT
	 *      flag in place UNLESS this database simply acts as a key/value store.
	 *   - driver: Optional name of a specific DB client driver. For MySQL, there is only the
	 *      'mysqli' driver; the old one 'mysql' has been removed.
	 *   - variables: Optional map of session variables to set after connecting. This can be
	 *      used to adjust lock timeouts or encoding modes and the like.
	 *   - serverName : Optional readable name for the database server.
	 *   - topologyRole: Optional IDatabase::ROLE_* constant for the database server.
	 *   - lbInfo: Optional map of field/values for the managing load balancer instance.
	 *      The "master" and "replica" fields are used to flag the replication role of this
	 *      database server and whether methods like getLag() should actually issue queries.
	 *   - topologicalPrimaryConnRef: lazy-connecting IDatabase handle to the most authoritative
	 *      primary database server for the cluster that this database belongs to. This hande is
	 *      used for replication status purposes. This is generally managed by LoadBalancer.
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
	 *   - criticalSectionProvider: Optional CriticalSectionProvider instance.
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
				'serverName' => null,
				'topologyRole' => null,
				// Objects and callbacks
				'topologicalPrimaryConnRef' => $params['topologicalPrimaryConnRef'] ?? null,
				'srvCache' => $params['srvCache'] ?? new HashBagOStuff(),
				'profiler' => $params['profiler'] ?? null,
				'trxProfiler' => $params['trxProfiler'] ?? new TransactionProfiler(),
				'connLogger' => $params['connLogger'] ?? new NullLogger(),
				'queryLogger' => $params['queryLogger'] ?? new NullLogger(),
				'replLogger' => $params['replLogger'] ?? new NullLogger(),
				'errorLogger' => $params['errorLogger'] ?? static function ( Throwable $e ) {
					trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
				},
				'deprecationLogger' => $params['deprecationLogger'] ?? static function ( $msg ) {
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
	 * @throws DBUnexpectedError
	 * @since 1.31
	 */
	final public static function attributesFromType( $dbType, $driver = null ) {
		static $defaults = [
			self::ATTR_DB_IS_FILE => false,
			self::ATTR_DB_LEVEL_LOCKING => false,
			self::ATTR_SCHEMAS_AS_TABLE_GROUPS => false
		];

		$class = self::getClass( $dbType, $driver );
		if ( class_exists( $class ) ) {
			return call_user_func( [ $class, 'getAttributes' ] ) + $defaults;
		} else {
			throw new DBUnexpectedError( null, "$dbType is not a supported database type." );
		}
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

		if ( !isset( $builtinTypes[$dbType] ) ) {
			// Not a built in type, assume standard naming scheme
			return 'Database' . ucfirst( $dbType );
		}

		$class = false;
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

	public function getTopologyBasedServerId() {
		return null;
	}

	public function getTopologyRole() {
		return $this->topologyRole;
	}

	/**
	 * Get important session state that cannot be recovered upon connection loss
	 *
	 * @return CriticalSessionInfo
	 */
	private function getCriticalSessionInfo(): CriticalSessionInfo {
		return new CriticalSessionInfo(
			$this->transactionManager->getTrxId(),
			$this->transactionManager->explicitTrxActive(),
			$this->transactionManager->pendingWriteCallers(),
			$this->transactionManager->pendingPreCommitCallbackCallers(),
			$this->sessionNamedLocks,
			$this->sessionTempTables
		);
	}

	public function tablePrefix( $prefix = null ) {
		$old = $this->currentDomain->getTablePrefix();

		if ( $prefix !== null ) {
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				$this->currentDomain->getSchema(),
				$prefix
			);
			$this->platform->setCurrentDomain( $this->currentDomain );
		}

		return $old;
	}

	public function dbSchema( $schema = null ) {
		$old = $this->currentDomain->getSchema();

		if ( $schema !== null ) {
			if ( $schema !== '' && $this->getDBname() === null ) {
				throw new DBUnexpectedError(
					$this,
					"Cannot set schema to '$schema'; no database set"
				);
			}

			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				// DatabaseDomain uses null for unspecified schemas
				( $schema !== '' ) ? $schema : null,
				$this->currentDomain->getTablePrefix()
			);
			$this->platform->setCurrentDomain( $this->currentDomain );
		}

		return (string)$old;
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

	public function lastQuery() {
		return $this->lastQuery;
	}

	public function lastDoneWrites() {
		return $this->lastWriteTime ?: false;
	}

	/**
	 * @return bool
	 * @since 1.39
	 * @internal For use by Database/LoadBalancer only
	 */
	public function sessionLocksPending() {
		return (bool)$this->sessionNamedLocks;
	}

	/**
	 * @return string|null ID of the active explicit transaction round being participating in
	 */
	final protected function getTransactionRoundId() {
		if ( $this->getFlag( self::DBO_TRX ) ) {
			// LoadBalancer transaction round participation is enabled for this DB handle;
			// get the ID of the active explicit transaction round (if any)
			$id = $this->getLBInfo( self::LB_TRX_ROUND_ID );

			return is_string( $id ) ? $id : null;
		}

		return null;
	}

	public function isOpen() {
		return (bool)$this->conn;
	}

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::DBO_MUTABLE ) {
			throw new DBUnexpectedError(
				$this,
				"Got $flag (allowed: " . implode( ', ', static::MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			$this->priorFlags[] = $this->flags;
		}

		$this->flags |= $flag;
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $flag & ~static::DBO_MUTABLE ) {
			throw new DBUnexpectedError(
				$this,
				"Got $flag (allowed: " . implode( ', ', static::MUTABLE_FLAGS ) . ')'
			);
		}

		if ( $remember === self::REMEMBER_PRIOR ) {
			$this->priorFlags[] = $this->flags;
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
	 * @return string|false
	 */
	protected function restoreErrorHandler() {
		restore_error_handler();
		if ( $this->htmlErrors !== false ) {
			ini_set( 'html_errors', $this->htmlErrors );
		}

		return $this->getLastPHPError();
	}

	/**
	 * @return string|false Last PHP error for this DB (typically connection errors)
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
	 *
	 * @internal This method should not be used outside of Database classes
	 *
	 * @param int|string $errno
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
				'db_server' => $this->getServerName(),
				'db_name' => $this->getDBname(),
				'db_user' => $this->connectionParams[self::CONN_USER] ?? null,
			],
			$extras
		);
	}

	final public function close( $fname = __METHOD__ ) {
		$error = null; // error to throw after disconnecting

		$wasOpen = (bool)$this->conn;
		// This should mostly do nothing if the connection is already closed
		if ( $this->conn ) {
			// Roll back any dangling transaction first
			if ( $this->trxLevel() ) {
				$error = $this->transactionManager->trxCheckBeforeClose( $this, $fname );
				// Rollback the changes and run any callbacks as needed
				$this->rollback( __METHOD__, self::FLUSHING_INTERNAL );
				$this->runTransactionPostRollbackCallbacks();
			}

			// Close the actual connection in the binding handle
			$closed = $this->closeConnection();
		} else {
			$closed = true; // already closed; nothing to do
		}

		$this->conn = null;

		// Log any unexpected errors after having disconnected
		if ( $error !== null ) {
			// T217819, T231443: this is probably just LoadBalancer trying to recover from
			// errors and shutdown. Log any problems and move on since the request has to
			// end one way or another. Throwing errors is not very useful at some point.
			$this->queryLogger->error( $error, [ 'db_log_category' => 'query' ] );
		}

		// Note that various subclasses call close() at the start of open(), which itself is
		// called by replaceLostConnection(). In that case, just because onTransactionResolution()
		// callbacks are pending does not mean that an exception should be thrown. Rather, they
		// will be executed after the reconnection step.
		if ( $wasOpen ) {
			// Double check that no callbacks are dangling
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
	 * Make sure there is an open connection handle (alive or not)
	 *
	 * This guards against fatal errors to the binding handle not being defined in cases
	 * where open() was never called or close() was already called.
	 *
	 * @throws DBUnexpectedError
	 */
	final protected function assertHasConnectionHandle() {
		if ( !$this->isOpen() ) {
			throw new DBUnexpectedError( $this, "DB connection was already closed" );
		}
	}

	/**
	 * Make sure that this server is not marked as a replica nor read-only
	 *
	 * @throws DBReadOnlyError
	 * @since 1.37
	 */
	protected function assertIsWritablePrimary() {
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
	 * Run a query and return a QueryStatus instance with the query result information
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
	 * For SELECT queries, the result field contains either:
	 *   - a) A driver-specific IResultWrapper describing the query results
	 *   - b) False, on any query failure
	 *
	 * For non-SELECT queries, the result field contains either:
	 *   - a) A driver-specific IResultWrapper, only on success
	 *   - b) True, only on success (e.g. no meaningful result other than "OK")
	 *   - c) False, on any query failure
	 *
	 * @param string $sql Single-statement SQL query
	 * @return QueryStatus
	 * @since 1.39
	 */
	abstract protected function doSingleStatementQuery( string $sql ): QueryStatus;

	/**
	 * Execute a batch of query statements, aborting remaining statements if one fails
	 *
	 * @see Database::doQuery()
	 *
	 * @stable to override
	 * @param string[] $sqls Non-empty map of (statement ID => SQL statement)
	 * @return array<string,QueryStatus> Map of (statement ID => QueryStatus)
	 * @since 1.39
	 */
	protected function doMultiStatementQuery( array $sqls ): array {
		$qsByStatementId = [];

		$aborted = false;
		foreach ( $sqls as $statementId => $sql ) {
			$qs = $aborted
				? new QueryStatus( false, 0, 'Query aborted', 0 )
				: $this->doSingleStatementQuery( $sql );
			$qsByStatementId[$statementId] = $qs;
			$aborted = ( $qs->res === false );
		}

		return $qsByStatementId;
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
		// For simplicity, this only looks for tables with sensible, alphanumeric, names;
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
				$tableType = $pseudoPermanent ? self::TEMP_PSEUDO_PERMANENT : self::TEMP_NORMAL;
			} elseif ( isset( $this->sessionTempTables[$table] ) ) {
				$tableType = $this->sessionTempTables[$table]['type'];
			} else {
				$tableType = null;
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
					$this->sessionTempTables[$table] = [
						'type' => $tmpTableType,
						'pristine' => true,
						'trxId' => $this->transactionManager->getTrxId()
					];
					break;
				case 'DROP':
					unset( $this->sessionTempTables[$table] );
					break;
				case 'TRUNCATE':
					if ( isset( $this->sessionTempTables[$table] ) ) {
						$this->sessionTempTables[$table]['pristine'] = true;
					}
					break;
				default:
					if ( isset( $this->sessionTempTables[$table] ) ) {
						$this->sessionTempTables[$table]['pristine'] = false;
					}
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

		return isset( $this->sessionTempTables[$rawTable] )
			? $this->sessionTempTables[$rawTable]['pristine']
			: false;
	}

	public function query( $sql, $fname = __METHOD__, $flags = 0 ) {
		$flags = (int)$flags; // b/c; this field used to be a bool

		// Make sure that this caller is allowed to issue this query statement
		$this->assertQueryIsCurrentlyAllowed( $sql, $fname );

		// Send the query to the server and fetch any corresponding errors
		/** @var QueryStatus $qs */
		$qs = $this->executeQuery( $sql, $fname, $flags, $sql );

		// Handle any errors that occurred
		if ( $qs->res === false ) {
			// An error occurred; log and report it as needed. Errors that corrupt the state of
			// the transaction/session cannot be silenced from the client.
			$ignore = (
				$this->fieldHasBit( $flags, self::QUERY_SILENCE_ERRORS ) &&
				!$this->fieldHasBit( $qs->flags, self::ERR_ABORT_SESSION ) &&
				!$this->fieldHasBit( $qs->flags, self::ERR_ABORT_TRX )
			);
			// Throw an error unless both the ignore flag was set and a rollback is not needed
			$this->reportQueryError( $qs->message, $qs->code, $sql, $fname, $ignore );
		}

		return $qs->res;
	}

	/**
	 * Run a batch of SQL query statements and return the results.
	 *
	 * @see Database::query()
	 *
	 * @param string[] $sqls Map of (statement ID => SQL statement)
	 * @param string $fname Name of the calling function
	 * @param int $flags Bit field of IDatabase::QUERY_* constants
	 * @param string|null $summarySql Virtual SQL for profiling (e.g. "UPSERT INTO TABLE 'x'")
	 * @return array<string,QueryStatus> Ordered map of (statement ID => QueryStatus)
	 * @since 1.39
	 */
	public function queryMulti(
		array $sqls, string $fname = __METHOD__, int $flags = 0, ?string $summarySql = null
	) {
		if ( !$sqls ) {
			return [];
		}
		if ( $summarySql === null ) {
			$summarySql = reset( $sqls );
		}
		// Make sure that this caller is allowed to issue these query statements
		foreach ( $sqls as $sql ) {
			$this->assertQueryIsCurrentlyAllowed( $sql, $fname );
		}

		// Send the query statements to the server and fetch the results
		$statusByStatementId = $this->executeQuery( $sqls, $fname, $flags, $summarySql );
		// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
		foreach ( $statusByStatementId as $statementId => $qs ) {
			if ( $qs->res === false ) {
				// An error occurred; log and report it as needed. Errors that corrupt the state of
				// the transaction/session cannot be silenced from the client.
				$ignore = (
					$this->fieldHasBit( $flags, self::QUERY_SILENCE_ERRORS ) &&
					!$this->fieldHasBit( $qs->flags, self::ERR_ABORT_SESSION ) &&
					!$this->fieldHasBit( $qs->flags, self::ERR_ABORT_TRX )
				);
				$this->reportQueryError(
					$qs->message,
					$qs->code,
					$sqls[$statementId],
					$fname,
					$ignore
				);
			}
		}

		return $statusByStatementId;
	}

	/**
	 * Execute a set of queries without enforcing public (non-Database) caller restrictions.
	 *
	 * Retry it if there is a recoverable connection loss (e.g. no important state lost).
	 *
	 * This does not precheck for transaction/session state errors or critical section errors.
	 *
	 * @see Database::query()
	 * @see Database::querMulti()
	 *
	 * @param string|string[] $sqls SQL statment or (statement ID => SQL statement) map
	 * @param string $fname Name of the calling function
	 * @param int $flags Bit field of class QUERY_* constants
	 * @param string $summarySql Actual/simplified SQL for profiling
	 * @return QueryStatus|array<string,QueryStatus> QueryStatus (when given a string statement)
	 *   or ordered map of (statement ID => QueryStatus) (when given an array of statements)
	 * @throws DBUnexpectedError
	 * @since 1.34
	 */
	final protected function executeQuery( $sqls, $fname, $flags, $summarySql ) {
		if ( is_array( $sqls ) ) {
			// Query consists of an atomic batch of statements
			$multiMode = true;
			$statementsById = $sqls;
		} else {
			// Query consists of a single statement
			$multiMode = false;
			$statementsById = [ '*' => $sqls ];
		}

		$this->assertHasConnectionHandle();

		$hasPermWrite = false;
		$cStatementsById = [];
		$tempTableChangesByStatementId = [];
		foreach ( $statementsById as $statementId => $sql ) {
			if ( $this->platform->isWriteQuery( $sql, $flags ) ) {
				$verb = $this->platform->getQueryVerb( $sql );
				// Temporary table writes are not "meaningful" writes, since they are only
				// visible to one (ephemeral) session, so treat them as reads instead. This
				// can be overridden during integration testing via $flags. For simplicity,
				// disallow CREATE/DROP statements during multi queries, avoiding the need
				// to speculatively track whether a table will be temporary at query time.
				if ( $multiMode && in_array( $verb, [ 'CREATE', 'DROP' ] ) ) {
					throw new DBUnexpectedError(
						$this,
						"Cannot issue CREATE/DROP as part of multi-queries"
					);
				}
				$pseudoPermanent = $this->fieldHasBit( $flags, self::QUERY_PSEUDO_PERMANENT );
				$tempTableChanges = $this->getTempTableWrites( $sql, $pseudoPermanent );
				$isPermWrite = !$tempTableChanges;
				foreach ( $tempTableChanges as list( $tmpType ) ) {
					$isPermWrite = $isPermWrite || ( $tmpType !== self::TEMP_NORMAL );
				}
				// Permit temporary table writes on replica connections, but require a writable
				// master connection for writes to persistent tables.
				if ( $isPermWrite ) {
					$this->assertIsWritablePrimary();
					// DBConnRef uses QUERY_REPLICA_ROLE to enforce replica roles during query()
					if ( $this->fieldHasBit( $flags, self::QUERY_REPLICA_ROLE ) ) {
						throw new DBReadOnlyRoleError(
							$this,
							"Cannot write; target role is DB_REPLICA"
						);
					}
				}
				$hasPermWrite = $hasPermWrite || $isPermWrite;
			} else {
				// No temporary tables written to either
				$tempTableChanges = [];
			}
			$tempTableChangesByStatementId[$statementId] = $tempTableChanges;
			// Add agent and calling method comments to the SQL
			$cStatementsById[$statementId] = $this->makeCommentedSql( $sql, $fname );
		}

		// Whether a silent retry attempt is left for recoverable connection loss errors
		$retryLeft = !$this->fieldHasBit( $flags, self::QUERY_NO_RETRY );
		$firstStatement = reset( $statementsById );

		$cs = $this->commenceCriticalSection( __METHOD__ );

		do {
			// Start a DBO_TRX wrapper transaction as needed (throw an error on failure)
			if ( $this->beginIfImplied( $firstStatement, $fname, $flags ) ) {
				// Since begin() was called, any connection loss was already handled
				$retryLeft = false;
			}
			// Send the query statements to the server and fetch any results. Retry all the
			// statements if the error was a recoverable connection loss on the first statement.
			// To reduce the risk of running queries twice, do not retry the statements if there
			// is a connection error during any of the subsequent statements.
			$statusByStatementId = $this->attemptQuery(
				$statementsById,
				$cStatementsById,
				$fname,
				$summarySql,
				$hasPermWrite,
				$multiMode
			);
		} while (
			// Query had at least one statement
			( $firstQs = reset( $statusByStatementId ) ) &&
			// An error occurred that can be recovered from via query retry
			$this->fieldHasBit( $firstQs->flags, self::ERR_RETRY_QUERY ) &&
			// The retry has not been exhausted (consume it now)
			$retryLeft && !( $retryLeft = false )
		);

		foreach ( $statusByStatementId as $statementId => $qs ) {
			// Register creation and dropping of temporary tables
			$this->registerTempWrites( $qs->res, $tempTableChangesByStatementId[$statementId] );
		}

		$this->completeCriticalSection( __METHOD__, $cs );

		return $multiMode ? $statusByStatementId : $statusByStatementId['*'];
	}

	/**
	 * Query method wrapper handling profiling, logging, affected row count tracking, and
	 * automatic reconnections (without retry) on query failure due to connection loss
	 *
	 * Note that this does not handle DBO_TRX logic.
	 *
	 * This method handles profiling, debug logging, reconnection and the tracking of:
	 *   - write callers
	 *   - last write time
	 *   - affected row count of the last write
	 *   - whether writes occurred in a transaction
	 *   - last successful query time (confirming that the connection was not dropped)
	 *
	 * @see doSingleStatementQuery()
	 * @see doMultiStatementQuery()
	 *
	 * @param string[] $statementsById Map of (statement ID => SQL statement)
	 * @param string[] $cStatementsById Map of (statement ID => commented SQL statement)
	 * @param string $fname Name of the calling function
	 * @param string $summarySql Actual/simplified SQL for profiling
	 * @param bool $hasPermWrite Whether any of the queries write to permanent tables
	 * @param bool $multiMode Whether this is for an anomic statement batch
	 * @return array<string,QueryStatus> Map of (statement ID => statement result)
	 * @throws DBUnexpectedError
	 */
	private function attemptQuery(
		array $statementsById,
		array $cStatementsById,
		string $fname,
		string $summarySql,
		bool $hasPermWrite,
		bool $multiMode
	) {
		// Treat empty multi-statement query lists as no query at all
		if ( !$statementsById ) {
			return [];
		}

		// Transaction attributes before issuing this query
		$priorSessInfo = $this->getCriticalSessionInfo();

		// Get the transaction-aware SQL string used for profiling
		$prefix = ( $this->topologyRole === self::ROLE_STREAMING_MASTER ) ? 'role-primary: ' : '';
		$generalizedSql = new GeneralizedSql( $summarySql, $prefix );

		$startTime = microtime( true );
		$ps = $this->profiler ? ( $this->profiler )( $generalizedSql->stringify() ) : null;
		$this->lastQuery = $summarySql;
		$this->affectedRowCount = null;
		if ( $hasPermWrite ) {
			$this->lastWriteTime = microtime( true );
			$this->transactionManager->transactionWritingIn(
				$this->getServerName(),
				$this->getDomainID()
			);
		}

		if ( $multiMode ) {
			$qsByStatementId = $this->doMultiStatementQuery( $cStatementsById );
		} else {
			$qsByStatementId = [ '*' => $this->doSingleStatementQuery( $cStatementsById['*'] ) ];
		}

		unset( $ps ); // profile out (if set)
		$queryRuntime = max( microtime( true ) - $startTime, 0.0 );

		$lastStatus = end( $qsByStatementId );
		// Use the last affected row count for consistency with lastErrno()/lastError()
		$this->affectedRowCount = $lastStatus->rowsAffected;
		// Compute the total number of rows affected by all statements in the query
		$totalAffectedRowCount = 0;
		foreach ( $qsByStatementId as $qs ) {
			$totalAffectedRowCount += $qs->rowsAffected;
		}

		if ( $lastStatus->res !== false ) {
			$this->lastPing = $startTime;
			if ( $hasPermWrite && $this->trxLevel() ) {
				$this->transactionManager->updateTrxWriteQueryReport(
					$summarySql,
					$queryRuntime,
					$totalAffectedRowCount,
					$fname
				);
			}
		}

		$errflags = 0;
		$numRowsReturned = 0;
		$numRowsAffected = 0;
		if ( !$multiMode && $lastStatus->res === false ) {
			$lastSql = end( $statementsById );
			$lastError = $lastStatus->message;
			$lastErrno = $lastStatus->code;
			if ( $this->isConnectionError( $lastErrno ) ) {
				// Connection lost before or during the query...
				// Determine how to proceed given the lost session state
				$connLossFlag = $this->assessConnectionLoss(
					$lastSql,
					$queryRuntime,
					$priorSessInfo
				);
				// Update session state tracking and try to reestablish a connection
				$reconnected = $this->replaceLostConnection( $lastErrno, __METHOD__ );
				// Check if important server-side session-level state was lost
				if ( $connLossFlag >= self::ERR_ABORT_SESSION ) {
					$ex = $this->getQueryException( $lastError, $lastErrno, $lastSql, $fname );
					$this->transactionManager->setSessionError( $ex );
				}
				// Check if important server-side transaction-level state was lost
				if ( $connLossFlag >= self::ERR_ABORT_TRX ) {
					$ex = $this->getQueryException( $lastError, $lastErrno, $lastSql, $fname );
					$this->transactionManager->setTransactionError( $ex );
				}
				// Check if the query should be retried (having made the reconnection attempt)
				if ( $connLossFlag === self::ERR_RETRY_QUERY ) {
					$errflags |= ( $reconnected ? self::ERR_RETRY_QUERY : self::ERR_ABORT_QUERY );
				} else {
					$errflags |= $connLossFlag;
				}
			} elseif ( $this->isKnownStatementRollbackError( $lastErrno ) ) {
				// Query error triggered a server-side statement-only rollback...
				$errflags |= self::ERR_ABORT_QUERY;
				if ( $this->trxLevel() ) {
					// Allow legacy callers to ignore such errors via QUERY_IGNORE_DBO_TRX and
					// try/catch. However, a deprecation notice will be logged on the next query.
					$cause = [ $lastError, $lastErrno, $fname ];
					$this->transactionManager->setTrxStatusIgnoredCause( $cause );
				}
			} elseif ( $this->trxLevel() ) {
				// Some other error occurred during the query, within a transaction...
				// Server-side handling of errors during transactions varies widely depending on
				// the RDBMS type and configuration. There are several possible results: (a) the
				// whole transaction is rolled back, (b) only the queries after BEGIN are rolled
				// back, (c) the transaction is marked as "aborted" and a ROLLBACK is required
				// before other queries are permitted. For compatibility reasons, pessimistically
				// require a ROLLBACK query (not using SAVEPOINT) before allowing other queries.
				$ex = $this->getQueryException( $lastError, $lastErrno, $lastSql, $fname );
				$this->transactionManager->setTransactionError( $ex );
				$errflags |= self::ERR_ABORT_TRX;
			} else {
				// Some other error occurred during the query, without a transaction...
				$errflags |= self::ERR_ABORT_QUERY;
			}
		}
		foreach ( $qsByStatementId as $qs ) {
			$qs->flags = $errflags;
			$numRowsReturned += $qs->rowsReturned;
			$numRowsAffected += $qs->rowsAffected;
		}

		if ( !$multiMode && $statementsById['*'] === self::PING_QUERY ) {
			$this->lastRoundTripEstimate = $queryRuntime;
		}

		$this->transactionManager->recordQueryCompletion(
			$generalizedSql,
			$startTime,
			$hasPermWrite,
			$hasPermWrite ? $numRowsAffected : $numRowsReturned,
			$this->getServerName()
		);

		// Avoid the overhead of logging calls unless debug mode is enabled
		if ( $this->getFlag( self::DBO_DEBUG ) ) {
			$this->queryLogger->debug(
				"{method} [{runtime}s] {db_server}: {sql}",
				$this->getLogContext( [
					'method' => $fname,
					'sql' => implode( "; ", $statementsById ),
					'domain' => $this->getDomainID(),
					'runtime' => round( $queryRuntime, 3 ),
					'db_log_category' => 'query'
				] )
			);
		}

		return $qsByStatementId;
	}

	/**
	 * @param string $sql
	 * @param string $fname
	 * @return string
	 */
	private function makeCommentedSql( $sql, $fname ): string {
		// Add trace comment to the begin of the sql string, right after the operator.
		// Or, for one-word queries (like "BEGIN" or COMMIT") add it to the end (T44598).
		// NOTE: Don't add varying ids such as request id or session id to the comment.
		// It would break aggregation of similar queries in analysis tools (see T193050#7512149)
		$encName = preg_replace( '/[\x00-\x1F\/]/', '-', "$fname {$this->agent}" );

		return preg_replace( '/\s|$/', " /* $encName */ ", $sql, 1 );
	}

	/**
	 * Start an implicit transaction if DBO_TRX is enabled and no transaction is active
	 *
	 * @param string $sql
	 * @param string $fname
	 * @param int $flags
	 * @return bool Whether an implicit transaction was started
	 * @throws DBError
	 */
	private function beginIfImplied( $sql, $fname, $flags ) {
		if (
			!$this->fieldHasBit( $flags, self::QUERY_IGNORE_DBO_TRX ) &&
			!$this->trxLevel() &&
			$this->getFlag( self::DBO_TRX ) &&
			$this->platform->isTransactableQuery( $sql )
		) {
			$this->begin( __METHOD__ . " ($fname)", self::TRANSACTION_INTERNAL );
			$this->transactionManager->turnOnAutomatic();

			return true;
		}

		return false;
	}

	/**
	 * Check if the given query is appropriate to run in a public context
	 *
	 * The caller is assumed to come from outside Database.
	 * In order to keep the DB handle's session state tracking in sync, certain queries
	 * like "USE", "BEGIN", "COMMIT", and "ROLLBACK" must not be issued directly from
	 * outside callers. Such commands should only be issued through dedicated methods
	 * like selectDomain(), begin(), commit(), and rollback(), respectively.
	 *
	 * This also checks if the session state tracking was corrupted by a prior exception.
	 *
	 * @param string $sql
	 * @param string $fname
	 * @throws DBUnexpectedError
	 * @throws DBTransactionStateError
	 */
	private function assertQueryIsCurrentlyAllowed( string $sql, string $fname ) {
		$verb = $this->platform->getQueryVerb( $sql );

		if ( $verb === 'USE' ) {
			throw new DBUnexpectedError( $this, "Got USE query; use selectDomain() instead" );
		}

		if ( $verb === 'ROLLBACK' ) {
			// Whole transaction rollback is used for recovery
			// @TODO: T269161; prevent "BEGIN"/"COMMIT"/"ROLLBACK" from outside callers
			return;
		}

		if ( $this->csmError ) {
			throw new DBTransactionStateError(
				$this,
				"Cannot execute query from $fname while session state is out of sync",
				[],
				$this->csmError
			);
		}

		$this->transactionManager->assertSessionStatus( $this, $fname );

		if ( $verb !== 'ROLLBACK TO SAVEPOINT' ) {
			$this->transactionManager->assertTransactionStatus(
				$this,
				$this->deprecationLogger,
				$fname
			);
		}
	}

	/**
	 * Determine how to handle a connection lost discovered during a query attempt
	 *
	 * This checks if explicit transactions, pending transaction writes, and important
	 * session-level state (locks, temp tables) was lost. Point-in-time read snapshot loss
	 * is considered acceptable for DBO_TRX logic.
	 *
	 * If state was lost, but that loss was discovered during a ROLLBACK that would have
	 * destroyed that state anyway, treat the error as recoverable.
	 *
	 * @param string $sql SQL query statement that encountered or caused the connection loss
	 * @param float $walltime How many seconds passes while attempting the query
	 * @param CriticalSessionInfo $priorSessInfo Session state just before the query
	 * @return int Recovery approach. One of the following ERR_* class constants:
	 *   - Database::ERR_RETRY_QUERY: reconnect silently, retry query
	 *   - Database::ERR_ABORT_QUERY: reconnect silently, do not retry query
	 *   - Database::ERR_ABORT_TRX: reconnect, throw error, enforce transaction rollback
	 *   - Database::ERR_ABORT_SESSION: reconnect, throw error, enforce session rollback
	 */
	private function assessConnectionLoss(
		string $sql,
		float $walltime,
		CriticalSessionInfo $priorSessInfo
	) {
		$verb = $this->platform->getQueryVerb( $sql );

		if ( $walltime < self::DROPPED_CONN_BLAME_THRESHOLD_SEC ) {
			// Query failed quickly; the connection was probably lost before the query was sent
			$res = self::ERR_RETRY_QUERY;
		} else {
			// Query took a long time; the connection was probably lost during query execution
			$res = self::ERR_ABORT_QUERY;
		}

		// List of problems causing session/transaction state corruption
		$blockers = [];
		// Loss of named locks breaks future callers relying on those locks for critical sections
		foreach ( $priorSessInfo->namedLocks as $lockName => $lockInfo ) {
			if ( $lockInfo['trxId'] && $lockInfo['trxId'] === $priorSessInfo->trxId ) {
				// Treat lost locks acquired during the lost transaction as a transaction state
				// problem. Connection loss on ROLLBACK (non-SAVEPOINT) is tolerable since
				// rollback automatically triggered server-side.
				if ( $verb !== 'ROLLBACK' ) {
					$res = max( $res, self::ERR_ABORT_TRX );
					$blockers[] = "named lock '$lockName'";
				}
			} else {
				// Treat lost locks acquired either during prior transactions or during no
				// transaction as a session state problem.
				$res = max( $res, self::ERR_ABORT_SESSION );
				$blockers[] = "named lock '$lockName'";
			}
		}
		// Loss of temp tables breaks future callers relying on those tables for queries
		foreach ( $priorSessInfo->tempTables as $tableName => $tableInfo ) {
			if ( $tableInfo['trxId'] && $tableInfo['trxId'] === $priorSessInfo->trxId ) {
				// Treat lost temp tables created during the lost transaction as a transaction
				// state problem. Connection loss on ROLLBACK (non-SAVEPOINT) is tolerable since
				// rollback automatically triggered server-side.
				if ( $verb !== 'ROLLBACK' ) {
					$res = max( $res, self::ERR_ABORT_TRX );
					$blockers[] = "temp table '$tableName'";
				}
			} else {
				// Treat lost temp tables created either during prior transactions or during
				// no transaction as a session state problem.
				$res = max( $res, self::ERR_ABORT_SESSION );
				$blockers[] = "temp table '$tableName'";
			}
		}
		// Loss of transaction writes breaks future callers and DBO_TRX logic relying on those
		// writes to be atomic and still pending. Connection loss on ROLLBACK (non-SAVEPOINT) is
		// tolerable since rollback automatically triggered server-side.
		if ( $priorSessInfo->trxWriteCallers && $verb !== 'ROLLBACK' ) {
			$res = max( $res, self::ERR_ABORT_TRX );
			$blockers[] = 'uncommitted writes';
		}
		if ( $priorSessInfo->trxPreCommitCbCallers && $verb !== 'ROLLBACK' ) {
			$res = max( $res, self::ERR_ABORT_TRX );
			$blockers[] = 'pre-commit callbacks';
		}
		if ( $priorSessInfo->trxExplicit && $verb !== 'ROLLBACK' && $sql !== 'COMMIT' ) {
			// Transaction automatically rolled back, breaking the expectations of callers
			// relying on that transaction to provide atomic writes, serializability, or use
			// one  point-in-time snapshot for all reads. Assume that connection loss is OK
			// with ROLLBACK (non-SAVEPOINT). Likewise for COMMIT (T127428).
			$res = max( $res, self::ERR_ABORT_TRX );
			$blockers[] = 'explicit transaction';
		}

		if ( $blockers ) {
			$this->connLogger->warning(
				"Silent reconnection to {db_server} could not be attempted: {error}",
				$this->getLogContext( [
					'error' => 'session state loss (' . implode( ', ', $blockers ) . ')',
					'exception' => new RuntimeException(),
					'db_log_category' => 'connection'
				] )
			);
		}

		return $res;
	}

	/**
	 * Clean things up after session (and thus transaction) loss before reconnect
	 */
	private function handleSessionLossPreconnect() {
		// Clean up tracking of session-level things...
		// https://dev.mysql.com/doc/refman/5.7/en/implicit-commit.html
		// https://www.postgresql.org/docs/9.2/static/sql-createtable.html (ignoring ON COMMIT)
		$this->sessionTempTables = [];
		// https://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_get-lock
		// https://www.postgresql.org/docs/9.4/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$this->sessionNamedLocks = [];
		// Session loss implies transaction loss (T67263)
		$this->transactionManager->clearPreEndCallbacks();
		// Clear additional subclass fields
		$oldTrxId = $this->transactionManager->consumeTrxId();
		$this->doHandleSessionLossPreconnect();
		$this->transactionManager->transactionWritingOut( $this, (string)$oldTrxId );
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
		// Handle callbacks in trxEndCallbacks, e.g. onTransactionResolution().
		// If callback suppression is set then the array will remain unhandled.
		$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
		// Handle callbacks in trxRecurringCallbacks, e.g. setTransactionListener().
		// If callback suppression is set then the array will remain unhandled.
		$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
	}

	/**
	 * Checks whether the cause of the error is detected to be a timeout.
	 *
	 * It returns false by default, and not all engines support detecting this yet.
	 * If this returns false, it will be treated as a generic query error.
	 *
	 * @stable to override
	 * @param int|string $errno Error number
	 * @return bool
	 * @since 1.39
	 */
	protected function isQueryTimeoutError( $errno ) {
		return false;
	}

	/**
	 * Report a query error
	 *
	 * If $ignore is set, emit a DEBUG level log entry and continue,
	 * otherwise, emit an ERROR level log entry and throw an exception.
	 *
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 * @param bool $ignore Whether to just log an error rather than throw an exception
	 * @throws DBQueryError
	 */
	public function reportQueryError( $error, $errno, $sql, $fname, $ignore = false ) {
		if ( $ignore ) {
			$this->queryLogger->debug(
				"SQL ERROR (ignored): $error",
				[ 'db_log_category' => 'query' ]
			);
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
				'db_log_category' => 'query',
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
		if ( $this->isQueryTimeoutError( $errno ) ) {
			return new DBQueryTimeoutError( $this, $error, $errno, $sql, $fname );
		} elseif ( $this->isConnectionError( $errno ) ) {
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
				'exception' => new RuntimeException(),
				'db_log_category' => 'connection',
			] )
		);

		return new DBConnectionError( $this, $error );
	}

	/**
	 * @inheritDoc
	 */
	public function newSelectQueryBuilder(): SelectQueryBuilder {
		return new SelectQueryBuilder( $this );
	}

	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( $var === '*' ) {
			throw new DBUnexpectedError( $this, "Cannot use a * field" );
		} elseif ( is_array( $var ) && count( $var ) !== 1 ) {
			throw new DBUnexpectedError( $this, 'Cannot use more than one field' );
		}

		$options = $this->platform->normalizeOptions( $options );
		$options['LIMIT'] = 1;

		$res = $this->select( $table, $var, $cond, $fname, $options, $join_conds );
		if ( $res === false ) {
			throw new DBUnexpectedError( $this, "Got false from select()" );
		}

		$row = $res->fetchRow();
		if ( $row === false ) {
			return false;
		}

		return reset( $row );
	}

	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	): array {
		if ( $var === '*' ) {
			throw new DBUnexpectedError( $this, "Cannot use a * field" );
		} elseif ( !is_string( $var ) ) {
			throw new DBUnexpectedError( $this, "Cannot use an array of fields" );
		}

		$options = $this->platform->normalizeOptions( $options );
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

	public function select(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$options = (array)$options;
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
		// Treat SELECT queries with FOR UPDATE as writes. This matches
		// how MySQL enforces read_only (FOR SHARE and LOCK IN SHADE MODE are allowed).
		$flags = in_array( 'FOR UPDATE', $options, true )
			? self::QUERY_CHANGE_ROWS
			: self::QUERY_CHANGE_NONE;

		return $this->query( $sql, $fname, $flags );
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

		if ( !$res->numRows() ) {
			return false;
		}

		return $res->fetchObject();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function estimateRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->platform->normalizeConditions( $conds, $fname );
		$column = $this->platform->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$res = $this->select(
			$tables, [ 'rowcount' => 'COUNT(*)' ], $conds, $fname, $options, $join_conds
		);
		$row = $res ? $res->fetchRow() : [];

		return isset( $row['rowcount'] ) ? (int)$row['rowcount'] : 0;
	}

	public function selectRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->platform->normalizeConditions( $conds, $fname );
		$column = $this->platform->extractSingleFieldFromList( $var );
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
		$row = $res ? $res->fetchRow() : [];

		return isset( $row['rowcount'] ) ? (int)$row['rowcount'] : 0;
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
			return false;
		}

		return !$indexInfo[0]->Non_unique;
	}

	public function insert( $table, $rows, $fname = __METHOD__, $options = [] ) {
		$sql = $this->platform->dispatchingInsertSqlText( $table, $rows, $options );
		if ( !$sql ) {
			return true;
		}
		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );

		return true;
	}

	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] ) {
		$sql = $this->platform->updateSqlText( $table, $set, $conds, $options );
		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );

		return true;
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
		$cs = $this->commenceCriticalSection( __METHOD__ );

		try {
			$this->doSelectDomain( DatabaseDomain::newFromId( $domain ) );
		} catch ( DBError $e ) {
			$this->completeCriticalSection( __METHOD__, $cs );
			throw $e;
		}

		$this->completeCriticalSection( __METHOD__, $cs );
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
		$this->platform->setCurrentDomain( $this->currentDomain );
	}

	public function getDBname() {
		return $this->currentDomain->getDatabase();
	}

	public function getServer() {
		return $this->connectionParams[self::CONN_HOST] ?? null;
	}

	public function getServerName() {
		return $this->serverName ?? $this->getServer() ?? 'unknown';
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

	public function nextSequenceValue( $seqName ) {
		return null;
	}

	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ ) {
		$identityKey = $this->platform->normalizeUpsertParams( $uniqueKeys, $rows );
		if ( !$rows ) {
			return;
		}
		if ( $identityKey ) {
			$this->doReplace( $table, $identityKey, $rows, $fname );
		} else {
			$sql = $this->platform->insertSqlText( $table, $rows );
			$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
		}
	}

	/**
	 * @param string $table
	 * @param string[] $identityKey List of columns defining a unique key
	 * @param array $rows Non-empty list of rows
	 * @param string $fname
	 * @see Database::replace()
	 * @stable to override
	 * @since 1.35
	 */
	protected function doReplace( $table, array $identityKey, array $rows, $fname ) {
		$affectedRowCount = 0;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Delete any conflicting rows (including ones inserted from $rows)
				$sqlCondition = $this->platform->makeKeyCollisionCondition( [ $row ], $identityKey );
				$this->delete( $table, [ $sqlCondition ], $fname );
				$affectedRowCount += $this->affectedRows();
				// Insert the new row
				$this->insert( $table, $row, $fname );
				$affectedRowCount += $this->affectedRows();
			}
			$this->endAtomic( $fname );
		} catch ( DBError $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->affectedRowCount = $affectedRowCount;
	}

	public function upsert( $table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__ ) {
		$identityKey = $this->platform->normalizeUpsertParams( $uniqueKeys, $rows );
		if ( !$rows ) {
			return true;
		}
		if ( $identityKey ) {
			$this->platform->assertValidUpsertSetArray( $set, $identityKey, $rows );
			$this->doUpsert( $table, $rows, $identityKey, $set, $fname );
		} else {
			$sql = $this->platform->insertSqlText( $table, $rows );
			$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
		}

		return true;
	}

	/**
	 * Perform an UPSERT query
	 *
	 * @see Database::upsert()
	 * @see Database::buildExcludedValue()
	 *
	 * @stable to override
	 *
	 * @param string $table Table name
	 * @param array[] $rows Non-empty list of rows to insert
	 * @param string[] $identityKey Columns of the (unique) identity key to UPSERT upon
	 * @param string[] $set Non-empty map of (column name => SQL expression or literal)
	 * @param string $fname
	 * @since 1.35
	 */
	protected function doUpsert(
		string $table,
		array $rows,
		array $identityKey,
		array $set,
		string $fname
	) {
		$encTable = $this->tableName( $table );
		$sqlColumnAssignments = $this->makeList( $set, self::LIST_SET );
		// Check if there is a SQL assignment expression in $set
		$useWith = isset( $set[0] );

		$affectedRowCount = 0;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Update any existing conflicting row (including ones inserted from $rows)
				[ $sqlColumns, $sqlTuples, $sqlVals ] = $this->platform->makeInsertLists( [ $row ], '__' );
				$sqlConditions = $this->platform->makeKeyCollisionCondition( [ $row ], $identityKey );
				// Since "WITH...AS (VALUES ...)" loses type information, subclasses should
				// override with method if that might cause problems with the SET clause.
				// https://www.sqlite.org/lang_update.html
				// https://mariadb.com/kb/en/with/
				// https://dev.mysql.com/doc/refman/8.0/en/update.html
				// https://www.postgresql.org/docs/9.2/sql-update.html
				$sql =
					( $useWith ? "WITH __VALS ($sqlVals) AS (VALUES $sqlTuples) " : "" ) .
					"UPDATE $encTable SET $sqlColumnAssignments " .
					"WHERE ($sqlConditions)";
				$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
				$rowsUpdated = $this->affectedRows();
				$affectedRowCount += $rowsUpdated;
				// Insert the new row if there are no conflicts
				if ( $rowsUpdated <= 0 ) {
					// https://sqlite.org/lang_insert.html
					// https://mariadb.com/kb/en/with/
					// https://dev.mysql.com/doc/refman/8.0/en/with.html
					// https://www.postgresql.org/docs/9.2/sql-insert.html
					$sql = "INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples";
					$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
					$affectedRowCount += $this->affectedRows();
				}
			}
		} catch ( DBError $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->endAtomic( $fname );
		// Set the affected row count for the whole operation
		$this->affectedRowCount = $affectedRowCount;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function deleteJoin(
		$delTable,
		$joinTable,
		$delVar,
		$joinVar,
		$conds,
		$fname = __METHOD__
	) {
		$sql = $this->platform->deleteJoinSqlText( $delTable, $joinTable, $delVar, $joinVar, $conds );
		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query(
			"SHOW COLUMNS FROM $table LIKE \"$field\"",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res->fetchObject();

		$m = [];

		if ( preg_match( '/\((.*)\)/', $row->Type, $m ) ) {
			$size = $m[1];
		} else {
			$size = -1;
		}

		return $size;
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$sql = $this->platform->deleteSqlText( $table, $conds );
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

		$insertOptions = $this->platform->normalizeOptions( $insertOptions );
		$selectOptions = $this->platform->normalizeOptions( $selectOptions );

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
	 * @param array $insertOptions
	 * @param array $selectOptions
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
		// on only the primary DB (without needing row-based-replication). It also makes it easy to
		// know how big the INSERT is going to be.
		$fields = [];
		foreach ( $varMap as $dstColumn => $sourceColumnOrSql ) {
			$fields[] = $this->platform->fieldNameWithAlias( $sourceColumnOrSql, $dstColumn );
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
		} catch ( DBError $e ) {
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
		$sql = $this->platform->insertSelectNativeSqlText(
			$destTable,
			$srcTable,
			$varMap,
			$conds,
			$fname,
			$insertOptions,
			$selectOptions,
			$selectJoinConds
		);
		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
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
		return $this->isConnectionError( $this->lastErrno() );
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
	 * @since 1.38
	 */
	protected function isConnectionError( $errno ) {
		return false;
	}

	/**
	 * @stable to override
	 * @param int|string $errno
	 * @return bool Whether it is known that the last query error only caused statement rollback
	 * @note This is for backwards compatibility for callers catching DBError exceptions in
	 *   order to ignore problems like duplicate key errors or foreign key violations
	 * @since 1.39
	 */
	protected function isKnownStatementRollbackError( $errno ) {
		return false; // don't know; it could have caused a transaction rollback
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function deadlockLoop( ...$args ) {
		$function = array_shift( $args );
		$tries = self::DEADLOCK_TRIES;

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
					usleep( mt_rand( self::DEADLOCK_DELAY_MIN, self::DEADLOCK_DELAY_MAX ) );
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
	 * @since 1.37
	 * @stable to override
	 */
	public function primaryPosWait( DBPrimaryPos $pos, $timeout ) {
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
	public function getPrimaryPos() {
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
		$this->transactionManager->onTransactionResolution( $this, $callback, $fname );
	}

	final public function onTransactionCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() && $this->getTransactionRoundId() ) {
			// This DB handle is set to participate in LoadBalancer transaction rounds and
			// an explicit transaction round is active. Start an implicit transaction on this
			// DB handle (setting trxAutomatic) similar to how query() does in such situations.
			$this->begin( __METHOD__, self::TRANSACTION_INTERNAL );
		}

		$this->transactionManager->addPostCommitOrIdleCallback( $callback, $fname );
		if ( !$this->trxLevel() ) {
			$dbErrors = [];
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_IDLE, $dbErrors );
			if ( $dbErrors ) {
				throw $dbErrors[0];
			}
		}
	}

	final public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->trxLevel() && $this->getTransactionRoundId() ) {
			// This DB handle is set to participate in LoadBalancer transaction rounds and
			// an explicit transaction round is active. Start an implicit transaction on this
			// DB handle (setting trxAutomatic) similar to how query() does in such situations.
			$this->begin( __METHOD__, self::TRANSACTION_INTERNAL );
		}

		if ( $this->trxLevel() ) {
			$this->transactionManager->addPreCommitOrIdleCallback(
				$callback,
				$fname
			);
		} else {
			// No transaction is active nor will start implicitly, so make one for this callback
			$this->startAtomic( __METHOD__, self::ATOMIC_CANCELABLE );
			try {
				$callback( $this );
			} catch ( Throwable $e ) {
				// Avoid confusing error reporting during critical section errors
				if ( !$this->csmError ) {
					$this->cancelAtomic( __METHOD__ );
				}
				throw $e;
			}
			$this->endAtomic( __METHOD__ );
		}
	}

	final public function onAtomicSectionCancel( callable $callback, $fname = __METHOD__ ) {
		$this->transactionManager->onAtomicSectionCancel( $this, $callback, $fname );
	}

	final public function setTransactionListener( $name, callable $callback = null ) {
		$this->transactionManager->setTransactionListener( $name, $callback );
	}

	/**
	 * Whether to disable running of post-COMMIT/ROLLBACK callbacks
	 *
	 * @internal This method should not be used outside of Database/LoadBalancer
	 *
	 * @since 1.28
	 * @param bool $suppress
	 */
	final public function setTrxEndCallbackSuppression( $suppress ) {
		$this->transactionManager->setTrxEndCallbackSuppression( $suppress );
	}

	/**
	 * Consume and run any "on transaction idle/resolution" callbacks
	 *
	 * @internal This method should not be used outside of Database/LoadBalancer
	 *
	 * @since 1.20
	 * @param int $trigger IDatabase::TRIGGER_* constant
	 * @param DBError[] &$errors DB exceptions caught [returned]
	 * @return int Number of callbacks attempted
	 * @throws DBUnexpectedError
	 * @throws Throwable Any non-DBError exception thrown by a callback
	 */
	public function runOnTransactionIdleCallbacks( $trigger, array &$errors = [] ) {
		if ( $this->trxLevel() ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ': a transaction is still open' );
		}

		if ( $this->transactionManager->isEndCallbacksSuppressed() ) {
			// Execution deferred by LoadBalancer for explicit execution later
			return 0;
		}

		$cs = $this->commenceCriticalSection( __METHOD__ );

		$count = 0;
		$autoTrx = $this->getFlag( self::DBO_TRX ); // automatic begin() enabled?
		// Drain the queues of transaction "idle" and "end" callbacks until they are empty
		do {
			$callbackEntries = $this->transactionManager->consumeEndCallbacks( $trigger );
			$count += count( $callbackEntries );
			foreach ( $callbackEntries as $entry ) {
				$this->clearFlag( self::DBO_TRX ); // make each query its own transaction
				try {
					$entry[0]( $trigger, $this );
				} catch ( DBError $ex ) {
					call_user_func( $this->errorLogger, $ex );
					$errors[] = $ex;
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
		} while ( $this->transactionManager->countPostCommitOrIdleCallbacks() );

		$this->completeCriticalSection( __METHOD__, $cs );

		return $count;
	}

	/**
	 * Actually run any "transaction listener" callbacks
	 *
	 * @internal This method should not be used outside of Database/LoadBalancer
	 *
	 * @since 1.20
	 * @param int $trigger IDatabase::TRIGGER_* constant
	 * @param DBError[] &$errors DB exceptions caught [returned]
	 * @throws Throwable Any non-DBError exception thrown by a callback
	 */
	public function runTransactionListenerCallbacks( $trigger, array &$errors = [] ) {
		if ( $this->transactionManager->isEndCallbacksSuppressed() ) {
			// Execution deferred by LoadBalancer for explicit execution later
			return;
		}

		// These callbacks should only be registered in setup, thus no iteration is needed
		foreach ( $this->transactionManager->getRecurringCallbacks() as $callback ) {
			try {
				$callback( $trigger, $this );
			} catch ( DBError $ex ) {
				( $this->errorLogger )( $ex );
				$errors[] = $ex;
			}
		}
	}

	/**
	 * Handle "on transaction idle/resolution" and "transaction listener" callbacks post-COMMIT
	 *
	 * @throws DBError The first DBError exception thrown by a callback
	 * @throws Throwable Any non-DBError exception thrown by a callback
	 */
	private function runTransactionPostCommitCallbacks() {
		$dbErrors = [];
		$this->runOnTransactionIdleCallbacks( self::TRIGGER_COMMIT, $dbErrors );
		$this->runTransactionListenerCallbacks( self::TRIGGER_COMMIT, $dbErrors );
		$this->affectedRowCount = 0; // for the sake of consistency
		if ( $dbErrors ) {
			throw $dbErrors[0];
		}
	}

	/**
	 * Handle "on transaction idle/resolution" and "transaction listener" callbacks post-ROLLBACK
	 *
	 * This will suppress and log any DBError exceptions
	 *
	 * @throws Throwable Any non-DBError exception thrown by a callback
	 */
	private function runTransactionPostRollbackCallbacks() {
		$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
		$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
		$this->affectedRowCount = 0; // for the sake of consistency
	}

	final public function startAtomic(
		$fname = __METHOD__,
		$cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		$cs = $this->commenceCriticalSection( __METHOD__ );

		if ( $this->trxLevel() ) {
			// This atomic section is only one part of a larger transaction
			$sectionOwnsTrx = false;
		} else {
			// Start an implicit transaction (sets trxAutomatic)
			try {
				$this->begin( $fname, self::TRANSACTION_INTERNAL );
			} catch ( DBError $e ) {
				$this->completeCriticalSection( __METHOD__, $cs );
				throw $e;
			}
			if ( $this->getFlag( self::DBO_TRX ) ) {
				// This DB handle participates in LoadBalancer transaction rounds; all atomic
				// sections should be buffered into one transaction (e.g. to keep web requests
				// transactional). Note that an implicit transaction round is considered to be
				// active when no there is no explicit transaction round.
				$sectionOwnsTrx = false;
			} else {
				// This DB handle does not participate in LoadBalancer transaction rounds;
				// each topmost atomic section will use its own transaction.
				$sectionOwnsTrx = true;
			}
			$this->transactionManager->setAutomaticAtomic( $sectionOwnsTrx );
		}

		if ( $cancelable === self::ATOMIC_CANCELABLE ) {
			if ( $sectionOwnsTrx ) {
				// This atomic section is synonymous with the whole transaction; just
				// use full COMMIT/ROLLBACK in endAtomic()/cancelAtomic(), respectively
				$savepointId = self::NOT_APPLICABLE;
			} else {
				// This atomic section is only part of the whole transaction; use a SAVEPOINT
				// query so that its changes can be cancelled without losing the rest of the
				// transaction (e.g. changes from other sections or from outside of sections)
				try {
					$savepointId = $this->nextSavepointId( $fname );
					$sql = $this->platform->savepointSqlText( $savepointId );
					$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
				} catch ( DBError $e ) {
					$this->completeCriticalSection( __METHOD__, $cs, $e );
					throw $e;
				}
			}
		} else {
			$savepointId = null;
		}

		$sectionId = new AtomicSectionIdentifier;
		$this->transactionManager->addToAtomicLevels( $fname, $sectionId, $savepointId );

		$this->completeCriticalSection( __METHOD__, $cs );

		return $sectionId;
	}

	final public function endAtomic( $fname = __METHOD__ ) {
		list( $savepointId, $sectionId ) = $this->transactionManager->onEndAtomic( $this, $fname );

		$runPostCommitCallbacks = false;

		$cs = $this->commenceCriticalSection( __METHOD__ );

		// Remove the last section (no need to re-index the array)
		$this->transactionManager->popAtomicLevel();

		try {
			if ( $this->transactionManager->isClean() ) {
				$this->commit( $fname, self::FLUSHING_INTERNAL );
				$runPostCommitCallbacks = true;
			} elseif ( $savepointId !== null && $savepointId !== self::NOT_APPLICABLE ) {
				$sql = $this->platform->releaseSavepointSqlText( $savepointId );
				$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
			}
		} catch ( DBError $e ) {
			$this->completeCriticalSection( __METHOD__, $cs, $e );
			throw $e;
		}

		$this->transactionManager->onEndAtomicInCriticalSection( $sectionId );

		$this->completeCriticalSection( __METHOD__, $cs );

		if ( $runPostCommitCallbacks ) {
			$this->runTransactionPostCommitCallbacks();
		}
	}

	final public function cancelAtomic(
		$fname = __METHOD__,
		AtomicSectionIdentifier $sectionId = null
	) {
		$this->transactionManager->onCancelAtomicBeforeCriticalSection( $this, $fname );
		$pos = $this->transactionManager->getPositionFromSectionId( $sectionId );
		if ( $pos < 0 ) {
			throw new DBUnexpectedError( $this, "Atomic section not found (for $fname)" );
		}

		$cs = $this->commenceCriticalSection( __METHOD__ );
		$runPostRollbackCallbacks = false;
		list( $savedFname, $excisedIds, $newTopSection, $savedSectionId, $savepointId ) =
			$this->transactionManager->cancelAtomic( $pos );

		try {
			if ( $savedFname !== $fname ) {
				$e = new DBUnexpectedError(
					$this,
					"Invalid atomic section ended (got $fname but expected $savedFname)"
				);
				$this->completeCriticalSection( __METHOD__, $cs, $e );
				throw $e;
			}

			// Remove the last section (no need to re-index the array)
			$this->transactionManager->popAtomicLevel();
			$excisedIds[] = $savedSectionId;
			$newTopSection = $this->transactionManager->currentAtomicSectionId();

			if ( $savepointId !== null ) {
				// Rollback the transaction changes proposed within this atomic section
				if ( $savepointId === self::NOT_APPLICABLE ) {
					// Atomic section started the transaction; rollback the whole transaction
					// and trigger cancellation callbacks for all active atomic sections
					$this->rollback( $fname, self::FLUSHING_INTERNAL );
					$runPostRollbackCallbacks = true;
				} else {
					// Atomic section nested within the transaction; rollback the transaction
					// to the state prior to this section and trigger its cancellation callbacks
					$sql = $this->platform->rollbackToSavepointSqlText( $savepointId );
					$this->query( $sql, $fname, self::QUERY_CHANGE_TRX );
					$this->transactionManager->setTrxStatusToOk(); // no exception; recovered
					$this->transactionManager->runOnAtomicSectionCancelCallbacks(
						$this,
						self::TRIGGER_CANCEL,
						$excisedIds
					);
				}
			} else {
				// Put the transaction into an error state if it's not already in one
				$trxError = new DBUnexpectedError(
					$this,
					"Uncancelable atomic section canceled (got $fname)"
				);
				$this->transactionManager->setTransactionError( $trxError );
			}
		} finally {
			// Fix up callbacks owned by the sections that were just cancelled.
			// All callbacks should have an owner that is present in trxAtomicLevels.
			$this->transactionManager->modifyCallbacksForCancel( $this, $excisedIds, $newTopSection );
		}

		$this->affectedRowCount = 0; // for the sake of consistency

		$this->completeCriticalSection( __METHOD__, $cs );

		if ( $runPostRollbackCallbacks ) {
			$this->runTransactionPostRollbackCallbacks();
		}
	}

	final public function doAtomicSection(
		$fname,
		callable $callback,
		$cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		$sectionId = $this->startAtomic( $fname, $cancelable );
		try {
			$res = $callback( $this, $fname );
		} catch ( Throwable $e ) {
			// Avoid confusing error reporting during critical section errors
			if ( !$this->csmError ) {
				$this->cancelAtomic( $fname, $sectionId );
			}

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
			$this->transactionManager->onBeginTransaction( $this, $fname );
		} elseif ( $this->getFlag( self::DBO_TRX ) && $mode !== self::TRANSACTION_INTERNAL ) {
			$msg = "$fname: implicit transaction expected (DBO_TRX set)";
			throw new DBUnexpectedError( $this, $msg );
		}

		$this->assertHasConnectionHandle();

		$cs = $this->commenceCriticalSection( __METHOD__ );
		try {
			$this->doBegin( $fname );
		} catch ( DBError $e ) {
			$this->completeCriticalSection( __METHOD__, $cs );
			throw $e;
		}
		$this->transactionManager->newTrxId( $mode, $fname );
		// With REPEATABLE-READ isolation, the first SELECT establishes the read snapshot,
		// so get the replication lag estimate before any transaction SELECT queries come in.
		// This way, the lag estimate reflects what will actually be read. Also, if heartbeat
		// tables are used, this avoids counting snapshot lag as part of replication lag.
		$this->trxReplicaLagStatus = null; // clear cached value first
		$this->trxReplicaLagStatus = $this->getApproximateLagStatus();

		$this->completeCriticalSection( __METHOD__, $cs );
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

		if ( !$this->transactionManager->onCommit( $this, $fname, $flush ) ) {
			return;
		}

		$this->assertHasConnectionHandle();

		$this->runOnTransactionPreCommitCallbacks();

		$cs = $this->commenceCriticalSection( __METHOD__ );
		try {
			$this->doCommit( $fname );
		} catch ( DBError $e ) {
			$this->completeCriticalSection( __METHOD__, $cs );
			throw $e;
		}
		$lastWriteTime = $this->transactionManager->onCommitInCriticalSection( $this );
		if ( $lastWriteTime ) {
			$this->lastWriteTime = $lastWriteTime;
		}
		// With FLUSHING_ALL_PEERS, callbacks will run when requested by a dedicated phase
		// within LoadBalancer. With FLUSHING_INTERNAL, callbacks will run when requested by
		// the Database caller during a safe point. This avoids isolation and recursion issues.
		if ( $flush === self::FLUSHING_ONE ) {
			$this->runTransactionPostCommitCallbacks();
		}
		$this->completeCriticalSection( __METHOD__, $cs );
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
		if (
			$flush !== self::FLUSHING_INTERNAL &&
			$flush !== self::FLUSHING_ALL_PEERS &&
			$this->getFlag( self::DBO_TRX )
		) {
			throw new DBUnexpectedError(
				$this,
				"$fname: Expected mass rollback of all peer transactions (DBO_TRX set)"
			);
		}

		if ( !$this->trxLevel() ) {
			$this->transactionManager->setTrxStatusToNone();
			$this->transactionManager->clearPreEndCallbacks();
			if ( $this->transactionManager->trxLevel() <= TransactionManager::STATUS_TRX_ERROR ) {
				$this->connLogger->info(
					"$fname: acknowledged server-side transaction loss on {db_server}",
					$this->getLogContext()
				);
			}

			return;
		}

		$this->assertHasConnectionHandle();

		$cs = $this->commenceCriticalSection( __METHOD__ );
		$sql = $this->platform->rollbackSqlText();
		if ( $this->trxLevel() ) {
			# Disconnects cause rollback anyway, so ignore those errors
			$this->query( $sql, $fname, self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_TRX );
		}
		$this->transactionManager->onRollback( $this );
		// With FLUSHING_ALL_PEERS, callbacks will run when requested by a dedicated phase
		// within LoadBalancer. With FLUSHING_INTERNAL, callbacks will run when requested by
		// the Database caller during a safe point. This avoids isolation and recursion issues.
		if ( $flush === self::FLUSHING_ONE ) {
			$this->runTransactionPostRollbackCallbacks();
		}
		$this->completeCriticalSection( __METHOD__, $cs );
	}

	/**
	 * @internal Only for tests and highly discouraged
	 * @param TransactionManager $transactionManager
	 */
	public function setTransactionManager( TransactionManager $transactionManager ) {
		$this->transactionManager = $transactionManager;
	}

	public function flushSession( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		if ( $this->trxLevel() ) {
			// Any existing transaction should have been rolled back already
			throw new DBUnexpectedError(
				$this,
				"$fname: transaction still in progress (not yet rolled back)"
			);
		}

		if (
			$flush !== self::FLUSHING_INTERNAL &&
			$flush !== self::FLUSHING_ALL_PEERS &&
			$this->getFlag( self::DBO_TRX )
		) {
			throw new DBUnexpectedError(
				$this,
				"$fname: Expected mass flush of all peer connections (DBO_TRX set)"
			);
		}

		// If the session state was already lost due to either an unacknowledged session
		// state loss error (e.g. dropped connection) or an explicit connection close call,
		// then there is nothing to do here. Note that such cases, even temporary tables and
		// server-side config variables are lost (the invocation of this method is assumed to
		// imply that such losses are tolerable).
		if ( $this->transactionManager->sessionStatus() <= TransactionManager::STATUS_SESS_ERROR ) {
			$this->connLogger->info(
				"$fname: acknowledged server-side session loss on {db_server}",
				$this->getLogContext()
			);
		} elseif ( $this->isOpen() ) {
			// Connection handle exists; server-side session state must be flushed
			$this->doFlushSession( $fname );
			$this->sessionNamedLocks = [];
		}

		$this->transactionManager->clearSessionError();
	}

	/**
	 * Reset the server-side session state for named locks and table locks
	 *
	 * Connection and query errors will be suppressed and logged
	 *
	 * @param string $fname
	 * @since 1.38
	 */
	protected function doFlushSession( $fname ) {
		// no-op
	}

	public function flushSnapshot( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		$this->transactionManager->onFlushSnapshot( $this, $fname, $flush, $this->getTransactionRoundId() );
		$this->commit( $fname, self::FLUSHING_INTERNAL );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function duplicateTableStructure(
		$oldName,
		$newName,
		$temporary = false,
		$fname = __METHOD__
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

	public function affectedRows() {
		return $this->affectedRowCount ?? $this->fetchAffectedRowCount();
	}

	/**
	 * @return int Number of retrieved rows according to the driver
	 */
	abstract protected function fetchAffectedRowCount();

	public function ping( &$rtt = null ) {
		// Avoid hitting the server if it was hit recently
		if ( $this->isOpen() ) {
			if ( ( microtime( true ) - $this->lastPing ) < self::PING_TTL &&
				( !func_num_args() || $this->lastRoundTripEstimate > 0 )
			) {
				$rtt = $this->lastRoundTripEstimate;
				return true; // don't care about $rtt
			}
			// This will reconnect if possible or return false if not
			$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_NONE;
			$ok = ( $this->query( self::PING_QUERY, __METHOD__, $flags ) !== false );
			if ( $ok ) {
				$rtt = $this->lastRoundTripEstimate;
			}
		} else {
			$ok = $this->replaceLostConnection( null, __METHOD__ );
		}

		return $ok;
	}

	/**
	 * Close any existing (dead) database connection and open a new connection
	 *
	 * @param int|null $lastErrno
	 * @param string $fname
	 * @return bool True if new connection is opened successfully, false if error
	 */
	protected function replaceLostConnection( $lastErrno, $fname ) {
		if ( $this->conn ) {
			$this->closeConnection();
			$this->conn = null;
			$this->handleSessionLossPreconnect();
		}

		try {
			$this->open(
				$this->connectionParams[self::CONN_HOST],
				$this->connectionParams[self::CONN_USER],
				$this->connectionParams[self::CONN_PASSWORD],
				$this->currentDomain->getDatabase(),
				$this->currentDomain->getSchema(),
				$this->tablePrefix()
			);
			$this->lastPing = microtime( true );
			$ok = true;

			$this->connLogger->warning(
				$fname . ': lost connection to {db_server} with error {errno}; reconnected',
				$this->getLogContext( [
					'exception' => new RuntimeException(),
					'db_log_category' => 'connection',
					'errno' => $lastErrno
				] )
			);
		} catch ( DBConnectionError $e ) {
			$ok = false;

			$this->connLogger->error(
				$fname . ': lost connection to {db_server} with error {errno}; reconnection failed: {connect_msg}',
				$this->getLogContext( [
					'exception' => new RuntimeException(),
					'db_log_category' => 'connection',
					'errno' => $lastErrno,
					'connect_msg' => $e->getMessage()
				] )
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
	 * This is useful given that transactions might use point-in-time read snapshots,
	 * in which case the lag estimate should be recorded just before the transaction
	 * establishes the read snapshot (either BEGIN or the first SELECT/write query).
	 *
	 * If snapshots are not used, it is still safe to be pessimistic.
	 *
	 * This returns null if there is no transaction or the lag status was not yet recorded.
	 *
	 * @return array|null ('lag': seconds or false, 'since': UNIX timestamp of BEGIN) or null
	 * @since 1.27
	 */
	final protected function getRecordedTransactionLagStatus() {
		return $this->trxLevel() ? $this->trxReplicaLagStatus : null;
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
		if ( $this->topologyRole === self::ROLE_STREAMING_REPLICA ) {
			// Avoid exceptions as this is used internally in critical sections
			try {
				$lag = $this->getLag();
			} catch ( DBError $e ) {
				$lag = false;
			}
		} else {
			$lag = 0;
		}

		return [ 'lag' => $lag, 'since' => microtime( true ) ];
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
	 * @param IDatabase|null ...$dbs
	 * Note: For backward compatibility, it is allowed for null values
	 * to be passed among the parameters. This is deprecated since 1.36,
	 * only IDatabase objects should be passed.
	 *
	 * @return array Map of values:
	 *   - lag: highest lag of any of the DBs or false on error (e.g. replication stopped)
	 *   - since: oldest UNIX timestamp of any of the DB lag estimates
	 *   - pending: whether any of the DBs have uncommitted changes
	 * @throws DBError
	 * @since 1.27
	 */
	public static function getCacheSetOptions( ?IDatabase ...$dbs ) {
		$res = [ 'lag' => 0, 'since' => INF, 'pending' => false ];

		foreach ( func_get_args() as $db ) {
			if ( $db instanceof IDatabase ) {
				$status = $db->getSessionLagStatus();

				if ( $status['lag'] === false ) {
					$res['lag'] = false;
				} elseif ( $res['lag'] !== false ) {
					$res['lag'] = max( $res['lag'], $status['lag'] );
				}
				$res['since'] = min( $res['since'], $status['since'] );
				$res['pending'] = $res['pending'] ?: $db->writesPending();
			}
		}

		return $res;
	}

	public function getLag() {
		if ( $this->topologyRole === self::ROLE_STREAMING_MASTER ) {
			return 0; // this is the primary DB
		} elseif ( $this->topologyRole === self::ROLE_STATIC_CLONE ) {
			return 0; // static dataset
		}

		return $this->doGetLag();
	}

	/**
	 * Get the amount of replication lag for this database server
	 *
	 * Callers should avoid using this method while a transaction is active
	 *
	 * @see getLag()
	 *
	 * @stable to override
	 * @return float|int|false Database replication lag in seconds or false on error
	 * @throws DBError
	 */
	protected function doGetLag() {
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
			return $this->sourceStream(
				$fp,
				$lineCallback,
				$resultCallback,
				$fname,
				$inputCallback
			);
		} finally {
			fclose( $fp );
		}
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
				$cmd = $this->platform->replaceVars( $cmd );

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
				'/' . preg_quote( $this->delimiter, '/' ) . '$/',
				'',
				$newLine
			);
			if ( $newLine != $prev ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function lockIsFree( $lockName, $method ) {
		// RDBMs methods for checking named locks may or may not count this thread itself.
		// In MySQL, IS_FREE_LOCK() returns 0 if the thread already has the lock. This is
		// the behavior chosen by the interface for this method.
		if ( isset( $this->sessionNamedLocks[$lockName] ) ) {
			$lockIsFree = false;
		} else {
			$lockIsFree = $this->doLockIsFree( $lockName, $method );
		}

		return $lockIsFree;
	}

	/**
	 * @see lockIsFree()
	 *
	 * @param string $lockName
	 * @param string $method
	 * @return bool Success
	 * @throws DBError
	 * @stable to override
	 */
	protected function doLockIsFree( string $lockName, string $method ) {
		return true; // not implemented
	}

	/**
	 * @inheritDoc
	 */
	public function lock( $lockName, $method, $timeout = 5, $flags = 0 ) {
		$lockTsUnix = $this->doLock( $lockName, $method, $timeout );
		if ( $lockTsUnix !== null ) {
			$locked = true;
			$this->sessionNamedLocks[$lockName] = [
				'ts' => $lockTsUnix,
				'trxId' => $this->transactionManager->getTrxId()
			];
		} else {
			$locked = false;
			$this->queryLogger->info(
				__METHOD__ . " failed to acquire lock '{lockname}'",
				[
					'lockname' => $lockName,
					'db_log_category' => 'locking'
				]
			);
		}

		return $this->fieldHasBit( $flags, self::LOCK_TIMESTAMP ) ? $lockTsUnix : $locked;
	}

	/**
	 * @see lock()
	 *
	 * @param string $lockName
	 * @param string $method
	 * @param int $timeout
	 * @return float|null UNIX timestamp of lock acquisition; null on failure
	 * @throws DBError
	 * @stable to override
	 */
	protected function doLock( string $lockName, string $method, int $timeout ) {
		return microtime( true ); // not implemented
	}

	/**
	 * @inheritDoc
	 */
	public function unlock( $lockName, $method ) {
		$released = $this->doUnlock( $lockName, $method );
		if ( $released ) {
			unset( $this->sessionNamedLocks[$lockName] );
		} else {
			$this->queryLogger->warning(
				__METHOD__ . " failed to release lock '$lockName'\n",
				[ 'db_log_category' => 'locking' ]
			);
		}

		return $released;
	}

	/**
	 * @see unlock()
	 *
	 * @param string $lockName
	 * @param string $method
	 * @return bool Success
	 * @throws DBError
	 * @stable to override
	 */
	protected function doUnlock( string $lockName, string $method ) {
		return true; // not implemented
	}

	public function getScopedLockAndFlush( $lockKey, $fname, $timeout ) {
		$this->transactionManager->onGetScopedLockAndFlush( $this, $fname );

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

	public function dropTable( $table, $fname = __METHOD__ ) {
		if ( !$this->tableExists( $table, $fname ) ) {
			return false;
		}

		$sql = $this->platform->dropTableSqlText( $table );
		$this->query( $sql, $fname, self::QUERY_CHANGE_SCHEMA );

		return true;
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
	public function setBigSelects( $value = true ) {
		// no-op
	}

	public function isReadOnly() {
		return ( $this->getReadOnlyReason() !== false );
	}

	/**
	 * @return array|false Tuple of (read-only reason, "role" or "lb") or false if it is not
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
	 * @param int $flags A bitfield of flags
	 * @param int $bit Bit flag constant
	 * @return bool Whether the bit field has the specified bit flag set
	 * @since 1.34
	 */
	final protected function fieldHasBit( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
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

	/**
	 * Demark the start of a critical section of session/transaction state changes
	 *
	 * Use this to disable potentially DB handles due to corruption from highly unexpected
	 * exceptions (e.g. from zend timers or coding errors) preempting execution of methods.
	 *
	 * Callers must demark completion of the critical section with completeCriticalSection().
	 * Callers should handle DBError exceptions that do not cause object state corruption by
	 * catching them, calling completeCriticalSection(), and then rethrowing them.
	 *
	 * @code
	 *     $cs = $this->commenceCriticalSection( __METHOD__ );
	 *     try {
	 *         //...send a query that changes the session/transaction state...
	 *     } catch ( DBError $e ) {
	 *         $this->completeCriticalSection( __METHOD__, $cs );
	 *         throw $expectedException;
	 *     }
	 *     try {
	 *         //...send another query that changes the session/transaction state...
	 *     } catch ( DBError $trxError ) {
	 *         // Require ROLLBACK before allowing any other queries from outside callers
	 *         $this->completeCriticalSection( __METHOD__, $cs, $trxError );
	 *         throw $expectedException;
	 *     }
	 *     // ...update session state fields of $this...
	 *     $this->completeCriticalSection( __METHOD__, $cs );
	 * @endcode
	 *
	 * @see Database::completeCriticalSection()
	 *
	 * @since 1.36
	 * @param string $fname Caller name
	 * @return CriticalSectionScope|null RAII-style monitor (topmost sections only)
	 * @throws DBUnexpectedError If an unresolved critical section error already exists
	 */
	protected function commenceCriticalSection( string $fname ) {
		if ( $this->csmError ) {
			throw new DBUnexpectedError(
				$this,
				"Cannot execute $fname critical section while session state is out of sync.\n\n" .
				$this->csmError->getMessage() . "\n" .
				$this->csmError->getTraceAsString()
			);
		}

		if ( $this->csmId ) {
			$csm = null; // fold into the outer critical section
		} elseif ( $this->csProvider ) {
			$csm = $this->csProvider->scopedEnter(
				$fname,
				null, // emergency limit (default)
				null, // emergency callback (default)
				function () use ( $fname ) {
					// Mark a critical section as having been aborted by an error
					$e = new RuntimeException( "A critical section from {$fname} has failed" );
					$this->csmError = $e;
					$this->csmId = null;
				}
			);
			$this->csmId = $csm->getId();
			$this->csmFname = $fname;
		} else {
			$csm = null; // not supported
		}

		return $csm;
	}

	/**
	 * Demark the completion of a critical section of session/transaction state changes
	 *
	 * @see Database::commenceCriticalSection()
	 *
	 * @since 1.36
	 * @param string $fname Caller name
	 * @param CriticalSectionScope|null $csm RAII-style monitor (topmost sections only)
	 * @param Throwable|null $trxError Error that requires setting STATUS_TRX_ERROR (if any)
	 */
	protected function completeCriticalSection(
		string $fname,
		?CriticalSectionScope $csm,
		Throwable $trxError = null
	) {
		if ( $csm !== null ) {
			if ( $this->csmId === null ) {
				throw new LogicException( "$fname critical section is not active" );
			} elseif ( $csm->getId() !== $this->csmId ) {
				throw new LogicException(
					"$fname critical section is not the active ({$this->csmFname}) one"
				);
			}

			$csm->exit();
			$this->csmId = null;
		}

		if ( $trxError ) {
			$this->transactionManager->setTransactionError( $trxError );
		}
	}

	public function __toString() {
		// spl_object_id is PHP >= 7.2
		$id = function_exists( 'spl_object_id' )
			? spl_object_id( $this )
			: spl_object_hash( $this );

		$description = $this->getType() . ' object #' . $id;
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.is_resource
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
			[
				'exception' => new RuntimeException(),
				'db_log_category' => 'connection'
			]
		);

		if ( $this->isOpen() ) {
			// Open a new connection resource without messing with the old one
			$this->conn = null;
			$this->transactionManager->clearEndCallbacks();
			$this->handleSessionLossPreconnect(); // no trx or locks anymore
			$this->open(
				$this->connectionParams[self::CONN_HOST],
				$this->connectionParams[self::CONN_USER],
				$this->connectionParams[self::CONN_PASSWORD],
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
	 * @return never
	 */
	public function __sleep() {
		throw new RuntimeException( 'Database serialization may cause problems, since ' .
			'the connection is not restored on wakeup' );
	}

	/**
	 * Run a few simple checks and close dangling connections
	 */
	public function __destruct() {
		if ( $this->transactionManager ) {
			// Tests mock this class and disable constructor.
			$this->transactionManager->onDestruct();
		}

		$danglingWriters = $this->pendingWriteAndCallbackCallers();
		if ( $danglingWriters ) {
			$fnames = implode( ', ', $danglingWriters );
			trigger_error( "DB transaction writes or callbacks still pending ($fnames)" );
		}

		if ( $this->conn ) {
			// Avoid connection leaks. Normally, resources close at script completion.
			// The connection might already be closed in PHP by now, so suppress warnings.
			AtEase::suppressWarnings();
			$this->closeConnection();
			AtEase::restoreWarnings();
			$this->conn = null;
		}
	}

	/* Start of methods delegated to TransactionManager. Avoid using them outside of rdbms library */

	final public function trxLevel() {
		// FIXME: A lot of tests disable constructor leading to trx manager being
		// null and breaking, this is unacceptable but hopefully this should
		// happen less by moving these functions to the transaction manager class.
		if ( !$this->transactionManager ) {
			$this->transactionManager = new TransactionManager( new NullLogger() );
		}
		return $this->transactionManager->trxLevel();
	}

	public function trxTimestamp() {
		return $this->transactionManager->trxTimestamp();
	}

	public function trxStatus() {
		return $this->transactionManager->trxStatus();
	}

	public function writesPending() {
		return $this->transactionManager->writesPending();
	}

	public function writesOrCallbacksPending() {
		return $this->transactionManager->writesOrCallbacksPending();
	}

	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL ) {
		return $this->transactionManager->pendingWriteQueryDuration( $this, $type );
	}

	public function pendingWriteCallers() {
		if ( !$this->transactionManager ) {
			return [];
		}
		return $this->transactionManager->pendingWriteCallers();
	}

	public function pendingWriteAndCallbackCallers() {
		if ( !$this->transactionManager ) {
			return [];
		}
		return $this->transactionManager->pendingWriteAndCallbackCallers();
	}

	public function runOnTransactionPreCommitCallbacks() {
		return $this->transactionManager->runOnTransactionPreCommitCallbacks( $this );
	}

	private function nextSavepointId( $fname ) {
		return $this->transactionManager->nextSavePointId( $this, $fname );
	}

	public function explicitTrxActive() {
		return $this->transactionManager->explicitTrxActive();
	}

	/* End of methods delegated to TransactionManager. */

	/* Start of methods delegated to SQLPlatform. Avoid using them outside of rdbms library */

	public function implicitOrderby() {
		return $this->platform->implicitOrderby();
	}

	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->platform->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	public function makeList( array $a, $mode = self::LIST_COMMA ) {
		return $this->platform->makeList( $a, $mode );
	}

	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		return $this->platform->makeWhereFrom2d( $data, $baseKey, $subKey );
	}

	public function factorConds( $condsArray ) {
		return $this->platform->factorConds( $condsArray );
	}

	public function bitNot( $field ) {
		return $this->platform->bitNot( $field );
	}

	public function bitAnd( $fieldLeft, $fieldRight ) {
		return $this->platform->bitAnd( $fieldLeft, $fieldRight );
	}

	public function bitOr( $fieldLeft, $fieldRight ) {
		return $this->platform->bitOr( $fieldLeft, $fieldRight );
	}

	public function buildConcat( $stringList ) {
		return $this->platform->buildConcat( $stringList );
	}

	public function buildGreatest( $fields, $values ) {
		return $this->platform->buildGreatest( $fields, $values );
	}

	public function buildLeast( $fields, $values ) {
		return $this->platform->buildLeast( $fields, $values );
	}

	public function buildSubstring( $input, $startPosition, $length = null ) {
		return $this->platform->buildSubstring( $input, $startPosition, $length );
	}

	public function buildStringCast( $field ) {
		return $this->platform->buildStringCast( $field );
	}

	public function buildIntegerCast( $field ) {
		return $this->platform->buildIntegerCast( $field );
	}

	public function tableName( $name, $format = 'quoted' ) {
		return $this->platform->tableName( $name, $format );
	}

	public function tableNames( ...$tables ) {
		return $this->platform->tableNames( ...$tables );
	}

	public function tableNamesN( ...$tables ) {
		return $this->platform->tableNamesN( ...$tables );
	}

	protected function indexName( $index ) {
		return $this->platform->indexName( $index );
	}

	public function addIdentifierQuotes( $s ) {
		return $this->platform->addIdentifierQuotes( $s );
	}

	public function isQuotedIdentifier( $name ) {
		return $this->platform->isQuotedIdentifier( $name );
	}

	public function buildLike( $param, ...$params ) {
		return $this->platform->buildLike( $param, ...$params );
	}

	public function anyChar() {
		return $this->platform->anyChar();
	}

	public function anyString() {
		return $this->platform->anyString();
	}

	public function limitResult( $sql, $limit, $offset = false ) {
		return $this->platform->limitResult( $sql, $limit, $offset );
	}

	public function unionSupportsOrderAndLimit() {
		return $this->platform->unionSupportsOrderAndLimit();
	}

	public function unionQueries( $sqls, $all ) {
		return $this->platform->unionQueries( $sqls, $all );
	}

	public function unionConditionPermutations(
		$table,
		$vars,
		array $permute_conds,
		$extra_conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	) {
		return $this->platform->unionConditionPermutations(
			$table,
			$vars,
			$permute_conds,
			$extra_conds,
			$fname,
			$options,
			$join_conds
		);
	}

	public function conditional( $cond, $caseTrueExpression, $caseFalseExpression ) {
		return $this->platform->conditional( $cond, $caseTrueExpression, $caseFalseExpression );
	}

	public function strreplace( $orig, $old, $new ) {
		return $this->platform->strreplace( $orig, $old, $new );
	}

	public function timestamp( $ts = 0 ) {
		return $this->platform->timestamp( $ts );
	}

	public function timestampOrNull( $ts = null ) {
		return $this->platform->timestampOrNull( $ts );
	}

	public function getInfinity() {
		return $this->platform->getInfinity();
	}

	public function encodeExpiry( $expiry ) {
		return $this->platform->encodeExpiry( $expiry );
	}

	public function decodeExpiry( $expiry, $format = TS_MW ) {
		return $this->platform->decodeExpiry( $expiry, $format );
	}

	public function setTableAliases( array $aliases ) {
		$this->platform->setTableAliases( $aliases );
	}

	public function getTableAliases() {
		return $this->platform->getTableAliases();
	}

	public function setIndexAliases( array $aliases ) {
		$this->platform->setIndexAliases( $aliases );
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		return $this->platform->buildGroupConcatField( $delim, $table, $field, $conds, $join_conds );
	}

	public function buildSelectSubquery(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->platform->buildSelectSubquery( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	public function buildExcludedValue( $column ) {
		return $this->platform->buildExcludedValue( $column );
	}

	public function setSchemaVars( $vars ) {
		$this->platform->setSchemaVars( $vars );
	}

	/* End of methods delegated to SQLPlatform. */
}

/**
 * @deprecated since 1.28
 */
class_alias( Database::class, 'DatabaseBase' );

/**
 * @deprecated since 1.29
 */
class_alias( Database::class, 'Database' );
