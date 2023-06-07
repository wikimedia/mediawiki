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
use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\Database\DatabaseFlags;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\Replication\ReplicationReporter;
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
	protected $logger;
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
	/** @var DatabaseFlags */
	protected $flagsHolder;

	// phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.ObjectTypeHintVar
	/** @var object|resource|null Database connection */
	protected $conn;

	/** @var string|null Readable name or host/IP of the database server */
	protected $serverName;
	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var int|null Maximum seconds to wait on connection attempts */
	protected $connectTimeout;
	/** @var int|null Maximum seconds to wait on receiving query results */
	protected $receiveTimeout;
	/** @var string Agent name for query profiling */
	protected $agent;
	/** @var array<string,mixed> Connection parameters used by initConnection() and open() */
	protected $connectionParams;
	/** @var string[]|int[]|float[] SQL variables values to use for all new connections */
	protected $connectionVariables;
	/** @var int Row batch size to use for emulated INSERT SELECT queries */
	protected $nonNativeInsertSelectBatchSize;

	/** @var bool Whether to use SSL connections */
	protected $ssl;
	/** @var array Current LoadBalancer tracking information */
	protected $lbInfo = [];
	/** @var string|false Current SQL query delimiter */
	protected $delimiter = ';';

	/** @var string|bool|null Stashed value of html_errors INI setting */
	private $htmlErrors;

	/** @var array<string,array> Map of (name => (UNIX time,trx ID)) for current lock() mutexes */
	protected $sessionNamedLocks = [];
	/** @var array<string,array> Map of (name => (type,pristine,trx ID)) for current temp tables */
	protected $sessionTempTables = [];

	/** @var int Affected row count for the last statement to query() */
	protected $lastQueryAffectedRows = 0;
	/** @var int|null Insert (row) ID for the last statement to query() (null if not supported) */
	protected $lastQueryInsertId;

	/** @var int|null Affected row count for the last query method call; null if unspecified */
	protected $lastEmulatedAffectedRows;
	/** @var int|null Insert (row) ID for the last query method call; null if unspecified */
	protected $lastEmulatedInsertId;

	/** @var string Last error during connection; empty string if none */
	protected $lastConnectError = '';

	/** @var float UNIX timestamp of the last server response */
	private $lastPing = 0.0;
	/** @var float|false UNIX timestamp of last write query */
	private $lastWriteTime = false;
	/** @var string|false The last PHP error from a query or connection attempt */
	private $lastPhpError = false;

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

	/** How long before it is worth doing a dummy query to test the connection */
	private const PING_TTL = 1.0;
	/** Dummy SQL query */
	private const PING_QUERY = 'SELECT 1 AS ping';

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

	/** @var ReplicationReporter */
	protected $replicationReporter;

	/**
	 * @note exceptions for missing libraries/drivers should be thrown in initConnection()
	 * @stable to call
	 * @param array $params Parameters passed from Database::factory()
	 */
	public function __construct( array $params ) {
		$this->logger = $params['logger'] ?? new NullLogger();
		$this->transactionManager = new TransactionManager(
			$this->logger,
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
		$this->connectionVariables = $params['variables'] ?? [];
		// Set SQL mode, default is turning them all off, can be overridden or skipped with null
		if ( is_string( $params['sqlMode'] ?? null ) ) {
			$this->connectionVariables['sql_mode'] = $params['sqlMode'];
		}
		$flags = (int)$params['flags'];
		$this->flagsHolder = new DatabaseFlags( $flags );
		$this->ssl = $params['ssl'] ?? (bool)( $flags & self::DBO_SSL );
		$this->connectTimeout = $params['connectTimeout'] ?? null;
		$this->receiveTimeout = $params['receiveTimeout'] ?? null;
		$this->cliMode = (bool)$params['cliMode'];
		$this->agent = (string)$params['agent'];
		$this->serverName = $params['serverName'];
		$this->nonNativeInsertSelectBatchSize = $params['nonNativeInsertSelectBatchSize'] ?? 10000;

		$this->srvCache = $params['srvCache'];
		$this->profiler = is_callable( $params['profiler'] ) ? $params['profiler'] : null;
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
			$this->logger,
			$this->currentDomain,
			$this->errorLogger
		);
		// Children classes must set $this->replicationReporter.
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
		$this->open(
			$this->connectionParams[self::CONN_HOST],
			$this->connectionParams[self::CONN_USER],
			$this->connectionParams[self::CONN_PASSWORD],
			$this->connectionParams[self::CONN_INITIAL_DB],
			$this->connectionParams[self::CONN_INITIAL_SCHEMA],
			$this->connectionParams[self::CONN_INITIAL_TABLE_PREFIX]
		);
		$this->lastPing = microtime( true );
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
	 *   - ssl : Whether to use TLS connections.
	 *   - driver: Optional name of a specific DB client driver. For MySQL, there is only the
	 *      'mysqli' driver; the old one 'mysql' has been removed.
	 *   - variables: Optional map of session variables to set after connecting. This can be
	 *      used to adjust lock timeouts or encoding modes and the like.
	 *   - serverName : Optional readable name for the database server.
	 *   - topologyRole: Optional IDatabase::ROLE_* constant that defines the replication role
	 *     that this server plays within the parent database cluster.
	 *   - lbInfo: Optional map of field/values for the managing load balancer instance.
	 *      The "master" and "replica" fields are used to flag the replication role of this
	 *      database server and whether methods like getLag() should actually issue queries.
	 *   - connectTimeout: Optional timeout, in seconds, for connection attempts.
	 *   - receiveTimeout: Optional timeout, in seconds, for receiving query results.
	 *   - logger: Optional PSR-3 logger interface instance.
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
	 * @deprecated since 1.39, use DatabaseFactory::create instead
	 * @since 1.18
	 */
	final public static function factory( $type, $params = [], $connect = self::NEW_CONNECTED ) {
		wfDeprecated( __METHOD__, '1.39' );
		return ( new DatabaseFactory() )->create( $type, $params, $connect );
	}

	/**
	 * @stable to override
	 * @return array Map of (Database::ATTR_* constant => value)
	 * @since 1.31
	 */
	public static function getAttributes() {
		return [];
	}

	/**
	 * Set the PSR-3 logger interface to use.
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function getServerInfo() {
		return $this->getServerVersion();
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
		if ( $this->flagsHolder->hasImplicitTrxFlag() ) {
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
			$this->logger->error( $error, [ 'db_log_category' => 'query' ] );
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
	 * @param Query $query
	 * @param bool $pseudoPermanent Treat any table from CREATE TEMPORARY as pseudo-permanent
	 * @return array[] List of change n-tuples with:
	 *   - int: self::TEMP_* constant for temp table operations
	 *   - string: SQL query verb from $sql
	 *   - string: Name of the temp table changed in $sql
	 */
	private function getTempTableWrites( Query $query, $pseudoPermanent ) {
		$tempTableChanges = [];
		$tables = [];
		foreach ( $query->getTables() as $table ) {
			$tables[] = $this->platform->tableName( $table, 'raw' );
		}
		foreach ( $tables as $table ) {
			if ( $query->getVerb() === 'CREATE' ) {
				// Record the type of temporary table being created
				$tableType = $pseudoPermanent ? self::TEMP_PSEUDO_PERMANENT : self::TEMP_NORMAL;
			} elseif ( isset( $this->sessionTempTables[$table] ) ) {
				$tableType = $this->sessionTempTables[$table]['type'];
			} else {
				$tableType = null;
			}

			if ( $tableType !== null ) {
				$tempTableChanges[] = [ $tableType, $query->getVerb(), $table ];
			}
		}

		return $tempTableChanges;
	}

	/**
	 * @param IResultWrapper|bool $ret
	 * @param array[] $changes List of change n-tuples with from getTempTableWrites()
	 */
	protected function registerTempWrites( $ret, array $changes ) {
		if ( $ret === false ) {
			return;
		}

		foreach ( $changes as [ $tmpTableType, $verb, $table ] ) {
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

	public function query( $sql, $fname = __METHOD__, $flags = 0 ) {
		if ( !( $sql instanceof Query ) ) {
			$flags = (int)$flags; // b/c; this field used to be a bool
			$sql = QueryBuilderFromRawSql::buildQuery( $sql, $flags, $this->currentDomain->getTablePrefix() );
		} else {
			$flags = $sql->getFlags();
		}

		// Make sure that this caller is allowed to issue this query statement
		$this->assertQueryIsCurrentlyAllowed( $sql->getVerb(), $fname );

		// Send the query to the server and fetch any corresponding errors
		$status = $this->executeQuery( $sql, $fname, $flags );
		if ( $status->res === false ) {
			// An error occurred; log, and, if needed, report an exception.
			// Errors that corrupt the transaction/session state cannot be silenced.
			$ignore = (
				$this->flagsHolder::contains( $flags, self::QUERY_SILENCE_ERRORS ) &&
				!$this->flagsHolder::contains( $status->flags, self::ERR_ABORT_SESSION ) &&
				!$this->flagsHolder::contains( $status->flags, self::ERR_ABORT_TRX )
			);
			$this->reportQueryError( $status->message, $status->code, $sql->getSQL(), $fname, $ignore );
		}

		return $status->res;
	}

	/**
	 * Execute a query without enforcing public (non-Database) caller restrictions.
	 *
	 * Retry it if there is a recoverable connection loss (e.g. no important state lost).
	 *
	 * This does not precheck for transaction/session state errors or critical section errors.
	 *
	 * @see Database::query()
	 *
	 * @param Query $sql SQL statement
	 * @param string $fname Name of the calling function
	 * @param int $flags Bit field of class QUERY_* constants
	 * @return QueryStatus
	 * @throws DBUnexpectedError
	 * @since 1.34
	 */
	final protected function executeQuery( $sql, $fname, $flags ) {
		$this->assertHasConnectionHandle();

		$isPermWrite = false;
		if ( $sql->isWriteQuery() ) {
			$pseudoPermanent = $this->flagsHolder::contains( $sql->getFlags(), self::QUERY_PSEUDO_PERMANENT );
			$tempTableChanges = $this->getTempTableWrites( $sql, $pseudoPermanent );
			$isPermWrite = !$tempTableChanges;
			foreach ( $tempTableChanges as [ $tmpType ] ) {
				$isPermWrite = $isPermWrite || ( $tmpType !== self::TEMP_NORMAL );
			}
			// Permit temporary table writes on replica connections, but require a writable
			// master connection for writes to persistent tables.
			if ( $isPermWrite ) {
				$info = $this->getReadOnlyReason();
				if ( $info ) {
					[ $reason, $source ] = $info;
					if ( $source === 'role' ) {
						throw new DBReadOnlyRoleError( $this, "Database is read-only: $reason" );
					} else {
						throw new DBReadOnlyError( $this, "Database is read-only: $reason" );
					}
				}
				// DBConnRef uses QUERY_REPLICA_ROLE to enforce replica roles during query()
				if ( $this->flagsHolder::contains( $sql->getFlags(), self::QUERY_REPLICA_ROLE ) ) {
					throw new DBReadOnlyRoleError(
						$this,
						"Cannot write; target role is DB_REPLICA"
					);
				}
			}
		} else {
			// No temporary tables written to either
			$tempTableChanges = [];
		}

		// Whether a silent retry attempt is left for recoverable connection loss errors
		$retryLeft = !$this->flagsHolder::contains( $flags, self::QUERY_NO_RETRY );

		$cs = $this->commenceCriticalSection( __METHOD__ );

		do {
			// Start a DBO_TRX wrapper transaction as needed (throw an error on failure)
			if ( $this->beginIfImplied( $sql, $fname, $flags ) ) {
				// Since begin() was called, any connection loss was already handled
				$retryLeft = false;
			}
			// Send the query statement to the server and fetch any results.
			$status = $this->attemptQuery( $sql, $fname, $isPermWrite );
		} while (
			// An error occurred that can be recovered from via query retry
			$this->flagsHolder::contains( $status->flags, self::ERR_RETRY_QUERY ) &&
			// The retry has not been exhausted (consume it now)
			$retryLeft && !( $retryLeft = false )
		);

		// Register creation and dropping of temporary tables
		$this->registerTempWrites( $status->res, $tempTableChanges );
		$this->completeCriticalSection( __METHOD__, $cs );

		return $status;
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
	 *
	 * @param Query $sql SQL statement
	 * @param string $fname Name of the calling function
	 * @param bool $isPermWrite Whether it's a query writing to permanent tables
	 * @return QueryStatus statement result
	 * @throws DBUnexpectedError
	 */
	private function attemptQuery(
		$sql,
		string $fname,
		bool $isPermWrite
	) {
		// Transaction attributes before issuing this query
		$priorSessInfo = new CriticalSessionInfo(
			$this->transactionManager->getTrxId(),
			$this->transactionManager->explicitTrxActive(),
			$this->transactionManager->pendingWriteCallers(),
			$this->transactionManager->pendingPreCommitCallbackCallers(),
			$this->sessionNamedLocks,
			$this->sessionTempTables
		);
		// Get the transaction-aware SQL string used for profiling
		$prefix = ( $this->getTopologyRole() === self::ROLE_STREAMING_MASTER ) ? 'role-primary: ' : '';

		// Start profile section
		if ( $sql->getCleanedSql() ) {
			$generalizedSql = $sql;
			$ps = $this->profiler ? ( $this->profiler )( $sql->getCleanedSql() ) : null;
		} else {
			$generalizedSql = new GeneralizedSql( $sql->getSQL(), $prefix );
			$ps = $this->profiler ? ( $this->profiler )( $generalizedSql->stringify() ) : null;
		}
		// Add agent and calling method comments to the SQL
		$cStatement = $this->makeCommentedSql( $sql->getSQL(), $fname );
		$startTime = microtime( true );

		// Clear any overrides from a prior "query method". Note that this does not affect
		// any such methods that are currently invoking query() itself since those query
		// methods set these fields before returning.
		$this->lastEmulatedAffectedRows = null;
		$this->lastEmulatedInsertId = null;

		if ( $isPermWrite ) {
			$this->lastWriteTime = $startTime;
			$this->transactionManager->transactionWritingIn(
				$this->getServerName(),
				$this->getDomainID()
			);
		}

		$status = $this->doSingleStatementQuery( $cStatement );

		// End profile section
		$endTime = microtime( true );
		$queryRuntime = max( $endTime - $startTime, 0.0 );
		unset( $ps );

		if ( $status->res !== false ) {
			$this->lastPing = $endTime;
		}

		$affectedRowCount = $status->rowsAffected;
		$returnedRowCount = $status->rowsReturned;
		$this->lastQueryAffectedRows = $affectedRowCount;

		if ( $status->res !== false ) {
			if ( $isPermWrite && $this->trxLevel() ) {
				$this->transactionManager->updateTrxWriteQueryReport(
					$sql->getSQL(),
					$queryRuntime,
					$affectedRowCount,
					$fname
				);
			}
		}

		$this->transactionManager->recordQueryCompletion(
			$generalizedSql,
			$startTime,
			$isPermWrite,
			$isPermWrite ? $affectedRowCount : $returnedRowCount,
			$this->getServerName()
		);

		// Check if the query failed...
		$status->flags = $this->handleErroredQuery( $status, $sql, $fname, $queryRuntime, $priorSessInfo );
		// Avoid the overhead of logging calls unless debug mode is enabled
		if ( $this->flagsHolder->getFlag( self::DBO_DEBUG ) ) {
			$this->logger->debug(
				"{method} [{runtime}s] {db_server}: {sql}",
				$this->getLogContext( [
					'method' => $fname,
					'sql' => $sql->getSQL(),
					'domain' => $this->getDomainID(),
					'runtime' => round( $queryRuntime, 3 ),
					'db_log_category' => 'query'
				] )
			);
		}

		return $status;
	}

	private function handleErroredQuery( QueryStatus $status, $sql, $fname, $queryRuntime, $priorSessInfo ) {
		$errflags = self::ERR_NONE;
		$error = $status->message;
		$errno = $status->code;
		if ( $status->res !== false ) {
			// Statement succeeded
			return $errflags;
		}
		if ( $this->isConnectionError( $errno ) ) {
			// Connection lost before or during the query...
			// Determine how to proceed given the lost session state
			$connLossFlag = $this->assessConnectionLoss(
				$sql->getVerb(),
				$queryRuntime,
				$priorSessInfo
			);
			// Update session state tracking and try to reestablish a connection
			$reconnected = $this->replaceLostConnection( $errno, __METHOD__ );
			// Check if important server-side session-level state was lost
			if ( $connLossFlag >= self::ERR_ABORT_SESSION ) {
				$ex = $this->getQueryException( $error, $errno, $sql->getSQL(), $fname );
				$this->transactionManager->setSessionError( $ex );
			}
			// Check if important server-side transaction-level state was lost
			if ( $connLossFlag >= self::ERR_ABORT_TRX ) {
				$ex = $this->getQueryException( $error, $errno, $sql->getSQL(), $fname );
				$this->transactionManager->setTransactionError( $ex );
			}
			// Check if the query should be retried (having made the reconnection attempt)
			if ( $connLossFlag === self::ERR_RETRY_QUERY ) {
				$errflags |= ( $reconnected ? self::ERR_RETRY_QUERY : self::ERR_ABORT_QUERY );
			} else {
				$errflags |= $connLossFlag;
			}
		} elseif ( $this->isKnownStatementRollbackError( $errno ) ) {
			// Query error triggered a server-side statement-only rollback...
			$errflags |= self::ERR_ABORT_QUERY;
			if ( $this->trxLevel() ) {
				// Allow legacy callers to ignore such errors via QUERY_IGNORE_DBO_TRX and
				// try/catch. However, a deprecation notice will be logged on the next query.
				$cause = [ $error, $errno, $fname ];
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
			$ex = $this->getQueryException( $error, $errno, $sql->getSQL(), $fname );
			$this->transactionManager->setTransactionError( $ex );
			$errflags |= self::ERR_ABORT_TRX;
		} else {
			// Some other error occurred during the query, without a transaction...
			$errflags |= self::ERR_ABORT_QUERY;
		}

		return $errflags;
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
	 * @param Query $sql SQL statement
	 * @param string $fname
	 * @param int $flags
	 * @return bool Whether an implicit transaction was started
	 * @throws DBError
	 */
	private function beginIfImplied( $sql, $fname, $flags ) {
		if (
			!$this->trxLevel() &&
			$this->flagsHolder->hasImplicitTrxFlag() &&
			!$this->flagsHolder::contains( $flags, self::QUERY_IGNORE_DBO_TRX )
		) {
			if ( $this->platform->isTransactableQuery( $sql ) ) {
				$this->begin( __METHOD__ . " ($fname)", self::TRANSACTION_INTERNAL );
				$this->transactionManager->turnOnAutomatic();

				return true;
			}
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
	 * @param string $verb
	 * @param string $fname
	 * @throws DBUnexpectedError
	 * @throws DBTransactionStateError
	 */
	private function assertQueryIsCurrentlyAllowed( string $verb, string $fname ) {
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
	 * @param string $verb SQL query verb
	 * @param float $walltime How many seconds passes while attempting the query
	 * @param CriticalSessionInfo $priorSessInfo Session state just before the query
	 * @return int Recovery approach. One of the following ERR_* class constants:
	 *   - Database::ERR_RETRY_QUERY: reconnect silently, retry query
	 *   - Database::ERR_ABORT_QUERY: reconnect silently, do not retry query
	 *   - Database::ERR_ABORT_TRX: reconnect, throw error, enforce transaction rollback
	 *   - Database::ERR_ABORT_SESSION: reconnect, throw error, enforce session rollback
	 */
	private function assessConnectionLoss(
		string $verb,
		float $walltime,
		CriticalSessionInfo $priorSessInfo
	) {
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
		if ( $priorSessInfo->trxExplicit && $verb !== 'ROLLBACK' && $verb !== 'COMMIT' ) {
			// Transaction automatically rolled back, breaking the expectations of callers
			// relying on that transaction to provide atomic writes, serializability, or use
			// one  point-in-time snapshot for all reads. Assume that connection loss is OK
			// with ROLLBACK (non-SAVEPOINT). Likewise for COMMIT (T127428).
			$res = max( $res, self::ERR_ABORT_TRX );
			$blockers[] = 'explicit transaction';
		}

		if ( $blockers ) {
			$this->logger->warning(
				"cannot reconnect to {db_server} silently: {error}",
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
		// https://mariadb.com/kb/en/create-table/#create-temporary-table
		// https://www.postgresql.org/docs/9.2/static/sql-createtable.html (ignoring ON COMMIT)
		$this->sessionTempTables = [];
		// https://mariadb.com/kb/en/get_lock/
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
			$this->logger->debug(
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
		$this->logger->error(
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
		// Connection was not fully initialized and is not safe for use.
		// Stash any error associated with the handle before destroying it.
		$this->lastConnectError = $error;
		$this->conn = null;

		$this->logger->error(
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
	 * Get a SelectQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @return SelectQueryBuilder
	 */
	public function newSelectQueryBuilder(): SelectQueryBuilder {
		return new SelectQueryBuilder( $this );
	}

	/**
	 * Get a UnionQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @return UnionQueryBuilder
	 */
	public function newUnionQueryBuilder(): UnionQueryBuilder {
		return new UnionQueryBuilder( $this );
	}

	/**
	 * Get an UpdateQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @return UpdateQueryBuilder
	 */
	public function newUpdateQueryBuilder(): UpdateQueryBuilder {
		return new UpdateQueryBuilder( $this );
	}

	/**
	 * Get a DeleteQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @return DeleteQueryBuilder
	 */
	public function newDeleteQueryBuilder(): DeleteQueryBuilder {
		return new DeleteQueryBuilder( $this );
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
		// Don't turn this into using platform directly, DatabaseMysqlBase overrides this.
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
		// Treat SELECT queries with FOR UPDATE as writes. This matches
		// how MySQL enforces read_only (FOR SHARE and LOCK IN SHADE MODE are allowed).
		$flags = in_array( 'FOR UPDATE', $options, true )
			? self::QUERY_CHANGE_ROWS
			: self::QUERY_CHANGE_NONE;

		$query = new Query( $sql, $flags, 'SELECT', $table );
		return $this->query( $query, $fname );
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
		if ( in_array( 'DISTINCT', (array)$options ) ) {
			if ( $column === null ) {
				throw new DBUnexpectedError( $this,
					'$var cannot be empty when the DISTINCT option is given' );
			}
			$innerVar = $column;
		} else {
			$innerVar = '1';
		}

		$res = $this->select(
			[
				'tmp_count' => $this->platform->buildSelectSubquery(
					$tables,
					$innerVar,
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
		if ( !$this->trxLevel() && !$this->flagsHolder->hasImplicitTrxFlag() ) {
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
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $table );
		$this->query( $query, $fname );

		return true;
	}

	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] ) {
		$sql = $this->platform->updateSqlText( $table, $set, $conds, $options );
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'UPDATE', $table );
		$this->query( $query, $fname );

		return true;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function databasesAreIndependent() {
		return false;
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
			$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $table );
			$this->query( $query, $fname );
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
		$insertId = null;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Delete any conflicting rows (including ones inserted from $rows)
				$query = $this->platform->deleteSqlText(
					$table,
					[ $this->platform->makeKeyCollisionCondition( [ $row ], $identityKey ) ]
				);
				$this->query( $query, $fname );
				// Insert the new row
				$query = new Query(
					$this->platform->dispatchingInsertSqlText( $table, $row, [] ),
					self::QUERY_CHANGE_ROWS,
					'INSERT',
					$table
				);
				$this->query( $query, $fname );
				$affectedRowCount += $this->lastQueryAffectedRows;
				$insertId = $insertId ?: $this->lastQueryInsertId;
			}
			$this->endAtomic( $fname );
		} catch ( DBError $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->lastEmulatedAffectedRows = $affectedRowCount;
		$this->lastEmulatedInsertId = $insertId;
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
			$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $table );
			$this->query( $query, $fname );
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
		// Get any AUTO_INCREMENT/SERIAL column for this table so we can set insertId()
		$autoIncrementColumn = $this->getInsertIdColumnForUpsert( $table );
		// Check if there is a SQL assignment expression in $set
		$useWith = isset( $set[0] );
		// Subclasses might need explicit type casting within "WITH...AS (VALUES ...)"
		// so that these CTE rows can be referenced within the SET clause assigments.
		$typeByColumn = $useWith ? $this->getValueTypesForWithClause( $table ) : [];

		$first = true;
		$affectedRowCount = 0;
		$insertId = null;
		$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
		try {
			foreach ( $rows as $row ) {
				// Update any existing conflicting row (including ones inserted from $rows)
				[ $sqlColumns, $sqlTuples, $sqlVals ] = $this->platform->makeInsertLists(
					[ $row ],
					'__',
					$typeByColumn
				);
				$sqlConditions = $this->platform->makeKeyCollisionCondition(
					[ $row ],
					$identityKey
				);
				$query = new Query(
					( $useWith ? "WITH __VALS ($sqlVals) AS (VALUES $sqlTuples) " : "" ) .
						"UPDATE $encTable SET $sqlColumnAssignments " .
						"WHERE ($sqlConditions)",
					self::QUERY_CHANGE_ROWS,
					'UPDATE',
					$table
				);
				$this->query( $query, $fname );
				$rowsUpdated = $this->lastQueryAffectedRows;
				$affectedRowCount += $rowsUpdated;
				if ( $rowsUpdated > 0 ) {
					// Conflicting row found and updated
					if ( $first && $autoIncrementColumn !== null ) {
						// @TODO: use "RETURNING" instead (when supported by SQLite)
						$query = new Query(
							"SELECT $autoIncrementColumn AS id FROM $encTable " .
							"WHERE ($sqlConditions)",
							self::QUERY_CHANGE_NONE,
							'SELECT',
							$table
						);
						$sRes = $this->query( $query, $fname, self::QUERY_CHANGE_ROWS );
						$insertId = (int)$sRes->fetchRow()['id'];
					}
				} else {
					// No conflicting row found
					$query = new Query(
						"INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples",
						self::QUERY_CHANGE_ROWS,
						'INSERT',
						$table
					);
					$this->query( $query, $fname );
					$affectedRowCount += $this->lastQueryAffectedRows;
				}
				$first = false;
			}
			$this->endAtomic( $fname );
		} catch ( DBError $e ) {
			$this->cancelAtomic( $fname );
			throw $e;
		}
		$this->lastEmulatedAffectedRows = $affectedRowCount;
		$this->lastEmulatedInsertId = $insertId;
	}

	/**
	 * @stable to override
	 * @param string $table
	 * @return string|null The AUTO_INCREMENT/SERIAL column; null if not needed
	 */
	protected function getInsertIdColumnForUpsert( $table ) {
		return null;
	}

	/**
	 * @stable to override
	 * @param string $table
	 * @return array<string,string> Map of (column => type); [] if not needed
	 */
	protected function getValueTypesForWithClause( $table ) {
		return [];
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
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'DELETE', [ $delTable, $joinTable ] );
		$this->query( $query, $fname );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function textFieldSize( $table, $field ) {
		$tableName = $this->tableName( $table );
		$query = new Query(
			"SHOW COLUMNS FROM $tableName LIKE \"$field\"",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW',
			$table
		);
		$res = $this->query( $query, __METHOD__ );
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
		$this->query( $this->platform->deleteSqlText( $table, $conds ), $fname );

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
	private function doInsertSelectGeneric(
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

		$affectedRowCount = 0;
		$insertId = null;
		if ( $res ) {
			$this->startAtomic( $fname, self::ATOMIC_CANCELABLE );
			try {
				$rows = [];
				foreach ( $res as $row ) {
					$rows[] = (array)$row;
				}
				// Avoid inserts that are too huge
				$rowBatches = array_chunk( $rows, $this->nonNativeInsertSelectBatchSize );
				foreach ( $rowBatches as $rows ) {
					$query = new Query(
						$this->platform->dispatchingInsertSqlText( $destTable, $rows, $insertOptions ),
						self::QUERY_CHANGE_ROWS,
						'INSERT',
						$destTable
					);
					$this->query( $query, $fname );
					$affectedRowCount += $this->lastQueryAffectedRows;
					$insertId = $insertId ?: $this->lastQueryInsertId;
				}
				$this->endAtomic( $fname );
			} catch ( DBError $e ) {
				$this->cancelAtomic( $fname );
				throw $e;
			}
		}
		$this->lastEmulatedAffectedRows = $affectedRowCount;
		$this->lastEmulatedInsertId = $insertId;
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
		if ( is_string( $destTable ) ) {
			$destTable = [ $destTable ];
		}
		if ( is_string( $srcTable ) ) {
			$srcTable = [ $srcTable ];
		}
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', array_merge( $destTable, $srcTable ) );
		$this->query( $query, $fname );
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
	public function wasReadOnlyError() {
		return false;
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
		$autoTrx = $this->flagsHolder->hasImplicitTrxFlag(); // automatic begin() enabled?
		// Drain the queues of transaction "idle" and "end" callbacks until they are empty
		do {
			$callbackEntries = $this->transactionManager->consumeEndCallbacks( $trigger );
			$count += count( $callbackEntries );
			foreach ( $callbackEntries as $entry ) {
				$this->flagsHolder->clearFlag( self::DBO_TRX ); // make each query its own transaction
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
						$this->flagsHolder->setFlag( self::DBO_TRX ); // restore automatic begin()
					} else {
						$this->flagsHolder->clearFlag( self::DBO_TRX ); // restore auto-commit
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
		$this->lastEmulatedAffectedRows = 0; // for the sake of consistency
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
		$this->lastEmulatedAffectedRows = 0; // for the sake of consistency
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
			if ( $this->flagsHolder->hasImplicitTrxFlag() ) {
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
					$savepointId = $this->transactionManager->nextSavePointId( $this, $fname );
					$sql = $this->platform->savepointSqlText( $savepointId );
					$query = new Query( $sql, self::QUERY_CHANGE_TRX, 'SAVEPOINT' );
					$this->query( $query, $fname );
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
		[ $savepointId, $sectionId ] = $this->transactionManager->onEndAtomic( $this, $fname );

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
				$query = new Query( $sql, self::QUERY_CHANGE_TRX, 'RELEASE SAVEPOINT' );
				$this->query( $query, $fname );
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
		[ $savedFname, $excisedIds, $newTopSection, $savedSectionId, $savepointId ] =
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
					$query = new Query( $sql, self::QUERY_CHANGE_TRX, 'ROLLBACK TO SAVEPOINT' );
					$this->query( $query, $fname );
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

		$this->lastEmulatedAffectedRows = 0; // for the sake of consistency

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
		} elseif ( $this->flagsHolder->hasImplicitTrxFlag() && $mode !== self::TRANSACTION_INTERNAL ) {
			$msg = "$fname: implicit transaction expected (DBO_TRX set)";
			throw new DBUnexpectedError( $this, $msg );
		}

		$this->assertHasConnectionHandle();

		$cs = $this->commenceCriticalSection( __METHOD__ );
		$timeStart = microtime( true );
		try {
			$this->doBegin( $fname );
		} catch ( DBError $e ) {
			$this->completeCriticalSection( __METHOD__, $cs );
			throw $e;
		}
		$timeEnd = microtime( true );
		// Treat "BEGIN" as a trivial query to gauge the RTT delay
		$rtt = max( $timeEnd - $timeStart, 0.0 );
		$this->transactionManager->newTrxId( $mode, $fname, $rtt );
		$this->replicationReporter->resetReplicationLagStatus( $this );
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
		$query = new Query( 'BEGIN', self::QUERY_CHANGE_TRX, 'BEGIN' );
		$this->query( $query, $fname );
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
			if ( $this->trxLevel() ) {
				$query = new Query( 'COMMIT', self::QUERY_CHANGE_TRX, 'COMMIT' );
				$this->query( $query, $fname );
			}
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

	final public function rollback( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		if (
			$flush !== self::FLUSHING_INTERNAL &&
			$flush !== self::FLUSHING_ALL_PEERS &&
			$this->flagsHolder->hasImplicitTrxFlag()
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
				$this->logger->info(
					"$fname: acknowledged server-side transaction loss on {db_server}",
					$this->getLogContext()
				);
			}

			return;
		}

		$this->assertHasConnectionHandle();

		if ( $this->csmError ) {
			// Since the session state is corrupt, we cannot just rollback the transaction
			// while preserving the non-transaction session state. The handle will remain
			// marked as corrupt until flushSession() is called to reset the connection
			// and deal with any remaining callbacks.
			$this->logger->info(
				"$fname: acknowledged client-side transaction loss on {db_server}",
				$this->getLogContext()
			);

			return;
		}

		$cs = $this->commenceCriticalSection( __METHOD__ );
		if ( $this->trxLevel() ) {
			// Disconnects cause rollback anyway, so ignore those errors
			$query = new Query(
				$this->platform->rollbackSqlText(),
				self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_TRX,
				'ROLLBACK'
			);
			$this->query( $query, $fname );
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
		if (
			$flush !== self::FLUSHING_INTERNAL &&
			$flush !== self::FLUSHING_ALL_PEERS &&
			$this->flagsHolder->hasImplicitTrxFlag()
		) {
			throw new DBUnexpectedError(
				$this,
				"$fname: Expected mass flush of all peer connections (DBO_TRX set)"
			);
		}

		if ( $this->csmError ) {
			// If a critical section error occurred, such as Excimer timeout exceptions raised
			// before a query response was marshalled, destroy the connection handle and reset
			// the session state tracking variables. The value of trxLevel() is irrelevant here,
			// and, in fact, might be 1 due to rollback() deferring critical section recovery.
			$this->logger->info(
				"$fname: acknowledged client-side session loss on {db_server}",
				$this->getLogContext()
			);
			$this->csmError = null;
			$this->csmFname = null;
			$this->replaceLostConnection( 2048, __METHOD__ );

			return;
		}

		if ( $this->trxLevel() ) {
			// Any existing transaction should have been rolled back already
			throw new DBUnexpectedError(
				$this,
				"$fname: transaction still in progress (not yet rolled back)"
			);
		}

		if ( $this->transactionManager->sessionStatus() <= TransactionManager::STATUS_SESS_ERROR ) {
			// If the session state was already lost due to either an unacknowledged session
			// state loss error (e.g. dropped connection) or an explicit connection close call,
			// then there is nothing to do here. Note that in such cases, even temporary tables
			// and server-side config variables are lost (invocation of this method is assumed
			// to imply that such losses are tolerable).
			$this->logger->info(
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
		$this->lastEmulatedAffectedRows ??= $this->lastQueryAffectedRows;

		return $this->lastEmulatedAffectedRows;
	}

	public function insertId() {
		if ( $this->lastEmulatedInsertId === null ) {
			// Guard against misuse of this method by checking affectedRows(). Note that calls
			// to insert() with "IGNORE" and calls to insertSelect() might not add any rows.
			if ( $this->affectedRows() ) {
				$this->lastEmulatedInsertId = $this->lastInsertId();
			} else {
				$this->lastEmulatedInsertId = 0;
			}
		}

		return $this->lastEmulatedInsertId;
	}

	/**
	 * Get a row ID from the last insert statement to implicitly assign one within the session
	 *
	 * If the statement involved assigning sequence IDs to multiple rows, then the return value
	 * will be any one of those values (database-specific). If the statement was an "UPSERT" and
	 * some existing rows were updated, then the result will either reflect only IDs of created
	 * rows or it will reflect IDs of both created and updated rows (this is database-specific).
	 *
	 * The result is unspecified if the statement gave an error.
	 *
	 * @return int Sequence ID, 0 (if none)
	 * @throws DBError
	 */
	abstract protected function lastInsertId();

	public function ping() {
		if ( $this->isOpen() ) {
			// If the connection was recently used, assume that it is still good
			if ( ( microtime( true ) - $this->lastPing ) < self::PING_TTL ) {
				return true;
			}
			// Send a trivial query to test the connection, triggering an automatic
			// reconnection attempt if the connection was lost
			$query = new Query(
				self::PING_QUERY,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_SILENCE_ERRORS | self::QUERY_CHANGE_NONE,
				'SELECT'
			);
			$res = $this->query( $query, __METHOD__ );
			$ok = ( $res !== false );
		} else {
			// Try to re-establish a connection
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

			$this->logger->warning(
				$fname . ': lost connection to {db_server} with error {errno}; reconnected',
				$this->getLogContext( [
					'exception' => new RuntimeException(),
					'db_log_category' => 'connection',
					'errno' => $lastErrno
				] )
			);
		} catch ( DBConnectionError $e ) {
			$ok = false;

			$this->logger->error(
				$fname . ': lost connection to {db_server} with error {errno}; reconnection failed: {connect_msg}',
				$this->getLogContext( [
					'exception' => new RuntimeException(),
					'db_log_category' => 'connection',
					'errno' => $lastErrno,
					'connect_msg' => $e->getMessage()
				] )
			);
		}

		// Handle callbacks in trxEndCallbacks, e.g. onTransactionResolution().
		// If callback suppression is set then the array will remain unhandled.
		$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
		// Handle callbacks in trxRecurringCallbacks, e.g. setTransactionListener().
		// If callback suppression is set then the array will remain unhandled.
		$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );

		return $ok;
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
			$this->logger->info(
				__METHOD__ . ": failed to acquire lock '{lockname}'",
				[
					'lockname' => $lockName,
					'db_log_category' => 'locking'
				]
			);
		}

		return $this->flagsHolder::contains( $flags, self::LOCK_TIMESTAMP ) ? $lockTsUnix : $locked;
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
		if ( !isset( $this->sessionNamedLocks[$lockName] ) ) {
			$released = false;
			$this->logger->warning(
				__METHOD__ . ": trying to release unheld lock '$lockName'\n",
				[ 'db_log_category' => 'locking' ]
			);
		} else {
			$released = $this->doUnlock( $lockName, $method );
			if ( $released ) {
				unset( $this->sessionNamedLocks[$lockName] );
			} else {
				$this->logger->warning(
					__METHOD__ . ": failed to release lock '$lockName'\n",
					[ 'db_log_category' => 'locking' ]
				);
			}
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
			// Note that the callback can be reached due to an exception making the calling
			// function end early. If the transaction/session is in an error state, avoid log
			// spam and confusing replacement of an original DBError with one about unlock().
			// Unlock query will fail anyway; avoid possibly triggering errors in rollback()
			if (
				$this->transactionManager->sessionStatus() <= TransactionManager::STATUS_SESS_ERROR ||
				$this->transactionManager->trxStatus() <= TransactionManager::STATUS_TRX_ERROR
			) {
				return;
			}
			if ( $this->trxLevel() ) {
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

		$query = new Query(
			$this->platform->dropTableSqlText( $table ),
			self::QUERY_CHANGE_SCHEMA,
			'DROP',
			$table
		);
		$this->query( $query, $fname );

		return true;
	}

	public function truncate( $tables, $fname = __METHOD__ ) {
		$tables = is_array( $tables ) ? $tables : [ $tables ];

		$tablesTruncate = [];
		foreach ( $tables as $table ) {
			// Skip TEMPORARY tables with no writes nor sequence updates detected.
			// This mostly is an optimization for integration testing.
			$rawTable = $this->tableName( $table, 'raw' );
			$isPristineTempTable = isset( $this->sessionTempTables[$rawTable] )
			? $this->sessionTempTables[$rawTable]['pristine']
			: false;
			if ( !$isPristineTempTable ) {
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
			$query = new Query( $sql, self::QUERY_CHANGE_SCHEMA, 'TRUNCATE', $table );
			$this->query( $query, $fname );
		}
	}

	public function isReadOnly() {
		return ( $this->getReadOnlyReason() !== null );
	}

	/**
	 * @return array|null Tuple of (reason string, "role" or "lb") if read-only; null otherwise
	 */
	protected function getReadOnlyReason() {
		$reason = $this->replicationReporter->getTopologyBasedReadOnlyReason();
		if ( $reason ) {
			return $reason;
		}

		$reason = $this->getLBInfo( self::LB_READ_ONLY_REASON );
		if ( is_string( $reason ) ) {
			return [ $reason, 'lb' ];
		}

		return null;
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
		$this->logger->warning(
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

	/* Start of methods delegated to DatabaseFlags. Avoid using them outside of rdbms library */

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		$this->flagsHolder->setFlag( $flag, $remember );
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		$this->flagsHolder->clearFlag( $flag, $remember );
	}

	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		$this->flagsHolder->restoreFlags( $state );
	}

	public function getFlag( $flag ) {
		return $this->flagsHolder->getFlag( $flag );
	}

	/* End of methods delegated to DatabaseFlags. */

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
		return $this->transactionManager->pendingWriteQueryDuration( $type );
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

	public function buildComparison( string $op, array $conds ): string {
		return $this->platform->buildComparison( $op, $conds );
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

	/* Start of methods delegated to ReplicationReporter. */
	public function primaryPosWait( DBPrimaryPos $pos, $timeout ) {
		return $this->replicationReporter->primaryPosWait( $this, $pos, $timeout );
	}

	public function getReplicaPos() {
		return $this->replicationReporter->getReplicaPos( $this );
	}

	public function getPrimaryPos() {
		return $this->replicationReporter->getPrimaryPos( $this );
	}

	public function getTopologyRole() {
		return $this->replicationReporter->getTopologyRole();
	}

	public function getTopologyBasedServerId() {
		return $this->replicationReporter->getTopologyBasedServerId( $this );
	}

	public function getLag() {
		return $this->replicationReporter->getLag( $this );
	}

	public function getSessionLagStatus() {
		return $this->replicationReporter->getSessionLagStatus( $this );
	}

	/* End of methods delegated to ReplicationReporter. */
}

/**
 * @deprecated since 1.28
 */
class_alias( Database::class, 'DatabaseBase' );

/**
 * @deprecated since 1.29
 */
class_alias( Database::class, 'Database' );
