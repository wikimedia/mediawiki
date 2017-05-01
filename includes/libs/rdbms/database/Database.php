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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\ScopedCallback;

/**
 * Relational database abstraction object
 *
 * @ingroup Database
 * @since 1.28
 */
abstract class Database implements IDatabase, IMaintainableDatabase, LoggerAwareInterface {
	/** Number of times to re-try an operation in case of deadlock */
	const DEADLOCK_TRIES = 4;
	/** Minimum time to wait before retry, in microseconds */
	const DEADLOCK_DELAY_MIN = 500000;
	/** Maximum time to wait before retry */
	const DEADLOCK_DELAY_MAX = 1500000;

	/** How long before it is worth doing a dummy query to test the connection */
	const PING_TTL = 1.0;
	const PING_QUERY = 'SELECT 1 AS ping';

	const TINY_WRITE_SEC = .010;
	const SLOW_WRITE_SEC = .500;
	const SMALL_WRITE_ROWS = 100;

	/** @var string SQL query */
	protected $mLastQuery = '';
	/** @var float|bool UNIX timestamp of last write query */
	protected $mLastWriteTime = false;
	/** @var string|bool */
	protected $mPHPError = false;
	/** @var string */
	protected $mServer;
	/** @var string */
	protected $mUser;
	/** @var string */
	protected $mPassword;
	/** @var string */
	protected $mDBname;
	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	protected $tableAliases = [];
	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var string Agent name for query profiling */
	protected $agent;

	/** @var BagOStuff APC cache */
	protected $srvCache;
	/** @var LoggerInterface */
	protected $connLogger;
	/** @var LoggerInterface */
	protected $queryLogger;
	/** @var callback Error logging callback */
	protected $errorLogger;

	/** @var resource|null Database connection */
	protected $mConn = null;
	/** @var bool */
	protected $mOpened = false;

	/** @var array[] List of (callable, method name) */
	protected $mTrxIdleCallbacks = [];
	/** @var array[] List of (callable, method name) */
	protected $mTrxPreCommitCallbacks = [];
	/** @var array[] List of (callable, method name) */
	protected $mTrxEndCallbacks = [];
	/** @var callable[] Map of (name => callable) */
	protected $mTrxRecurringCallbacks = [];
	/** @var bool Whether to suppress triggering of transaction end callbacks */
	protected $mTrxEndCallbacksSuppressed = false;

	/** @var string */
	protected $mTablePrefix = '';
	/** @var string */
	protected $mSchema = '';
	/** @var integer */
	protected $mFlags;
	/** @var array */
	protected $mLBInfo = [];
	/** @var bool|null */
	protected $mDefaultBigSelects = null;
	/** @var array|bool */
	protected $mSchemaVars = false;
	/** @var array */
	protected $mSessionVars = [];
	/** @var array|null */
	protected $preparedArgs;
	/** @var string|bool|null Stashed value of html_errors INI setting */
	protected $htmlErrors;
	/** @var string */
	protected $delimiter = ';';
	/** @var DatabaseDomain */
	protected $currentDomain;

	/**
	 * Either 1 if a transaction is active or 0 otherwise.
	 * The other Trx fields may not be meaningfull if this is 0.
	 *
	 * @var int
	 */
	protected $mTrxLevel = 0;
	/**
	 * Either a short hexidecimal string if a transaction is active or ""
	 *
	 * @var string
	 * @see Database::mTrxLevel
	 */
	protected $mTrxShortId = '';
	/**
	 * The UNIX time that the transaction started. Callers can assume that if
	 * snapshot isolation is used, then the data is *at least* up to date to that
	 * point (possibly more up-to-date since the first SELECT defines the snapshot).
	 *
	 * @var float|null
	 * @see Database::mTrxLevel
	 */
	private $mTrxTimestamp = null;
	/** @var float Lag estimate at the time of BEGIN */
	private $mTrxReplicaLag = null;
	/**
	 * Remembers the function name given for starting the most recent transaction via begin().
	 * Used to provide additional context for error reporting.
	 *
	 * @var string
	 * @see Database::mTrxLevel
	 */
	private $mTrxFname = null;
	/**
	 * Record if possible write queries were done in the last transaction started
	 *
	 * @var bool
	 * @see Database::mTrxLevel
	 */
	private $mTrxDoneWrites = false;
	/**
	 * Record if the current transaction was started implicitly due to DBO_TRX being set.
	 *
	 * @var bool
	 * @see Database::mTrxLevel
	 */
	private $mTrxAutomatic = false;
	/**
	 * Array of levels of atomicity within transactions
	 *
	 * @var array
	 */
	private $mTrxAtomicLevels = [];
	/**
	 * Record if the current transaction was started implicitly by Database::startAtomic
	 *
	 * @var bool
	 */
	private $mTrxAutomaticAtomic = false;
	/**
	 * Track the write query callers of the current transaction
	 *
	 * @var string[]
	 */
	private $mTrxWriteCallers = [];
	/**
	 * @var float Seconds spent in write queries for the current transaction
	 */
	private $mTrxWriteDuration = 0.0;
	/**
	 * @var integer Number of write queries for the current transaction
	 */
	private $mTrxWriteQueryCount = 0;
	/**
	 * @var float Like mTrxWriteQueryCount but excludes lock-bound, easy to replicate, queries
	 */
	private $mTrxWriteAdjDuration = 0.0;
	/**
	 * @var integer Number of write queries counted in mTrxWriteAdjDuration
	 */
	private $mTrxWriteAdjQueryCount = 0;
	/**
	 * @var float RTT time estimate
	 */
	private $mRTTEstimate = 0.0;

	/** @var array Map of (name => 1) for locks obtained via lock() */
	private $mNamedLocksHeld = [];
	/** @var array Map of (table name => 1) for TEMPORARY tables */
	protected $mSessionTempTables = [];

	/** @var IDatabase|null Lazy handle to the master DB this server replicates from */
	private $lazyMasterHandle;

	/** @var float UNIX timestamp */
	protected $lastPing = 0.0;

	/** @var int[] Prior mFlags values */
	private $priorFlags = [];

	/** @var object|string Class name or object With profileIn/profileOut methods */
	protected $profiler;
	/** @var TransactionProfiler */
	protected $trxProfiler;

	/**
	 * Constructor and database handle and attempt to connect to the DB server
	 *
	 * IDatabase classes should not be constructed directly in external
	 * code. Database::factory() should be used instead.
	 *
	 * @param array $params Parameters passed from Database::factory()
	 */
	function __construct( array $params ) {
		$server = $params['host'];
		$user = $params['user'];
		$password = $params['password'];
		$dbName = $params['dbname'];

		$this->mSchema = $params['schema'];
		$this->mTablePrefix = $params['tablePrefix'];

		$this->cliMode = $params['cliMode'];
		// Agent name is added to SQL queries in a comment, so make sure it can't break out
		$this->agent = str_replace( '/', '-', $params['agent'] );

		$this->mFlags = $params['flags'];
		if ( $this->mFlags & self::DBO_DEFAULT ) {
			if ( $this->cliMode ) {
				$this->mFlags &= ~self::DBO_TRX;
			} else {
				$this->mFlags |= self::DBO_TRX;
			}
		}

		$this->mSessionVars = $params['variables'];

		$this->srvCache = isset( $params['srvCache'] )
			? $params['srvCache']
			: new HashBagOStuff();

		$this->profiler = $params['profiler'];
		$this->trxProfiler = $params['trxProfiler'];
		$this->connLogger = $params['connLogger'];
		$this->queryLogger = $params['queryLogger'];
		$this->errorLogger = $params['errorLogger'];

		// Set initial dummy domain until open() sets the final DB/prefix
		$this->currentDomain = DatabaseDomain::newUnspecified();

		if ( $user ) {
			$this->open( $server, $user, $password, $dbName );
		} elseif ( $this->requiresDatabaseUser() ) {
			throw new InvalidArgumentException( "No database user provided." );
		}

		// Set the domain object after open() sets the relevant fields
		if ( $this->mDBname != '' ) {
			// Domains with server scope but a table prefix are not used by IDatabase classes
			$this->currentDomain = new DatabaseDomain( $this->mDBname, null, $this->mTablePrefix );
		}
	}

	/**
	 * Construct a Database subclass instance given a database type and parameters
	 *
	 * This also connects to the database immediately upon object construction
	 *
	 * @param string $dbType A possible DB type (sqlite, mysql, postgres)
	 * @param array $p Parameter map with keys:
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
	 *   - flags : Optional bitfield of DBO_* constants that define connection, protocol,
	 *      buffering, and transaction behavior. It is STRONGLY adviced to leave the DBO_DEFAULT
	 *      flag in place UNLESS this this database simply acts as a key/value store.
	 *   - driver: Optional name of a specific DB client driver. For MySQL, there is the old
	 *      'mysql' driver and the newer 'mysqli' driver.
	 *   - variables: Optional map of session variables to set after connecting. This can be
	 *      used to adjust lock timeouts or encoding modes and the like.
	 *   - connLogger: Optional PSR-3 logger interface instance.
	 *   - queryLogger: Optional PSR-3 logger interface instance.
	 *   - profiler: Optional class name or object with profileIn()/profileOut() methods.
	 *      These will be called in query(), using a simplified version of the SQL that also
	 *      includes the agent as a SQL comment.
	 *   - trxProfiler: Optional TransactionProfiler instance.
	 *   - errorLogger: Optional callback that takes an Exception and logs it.
	 *   - cliMode: Whether to consider the execution context that of a CLI script.
	 *   - agent: Optional name used to identify the end-user in query profiling/logging.
	 *   - srvCache: Optional BagOStuff instance to an APC-style cache.
	 * @return Database|null If the database driver or extension cannot be found
	 * @throws InvalidArgumentException If the database driver or extension cannot be found
	 * @since 1.18
	 */
	final public static function factory( $dbType, $p = [] ) {
		static $canonicalDBTypes = [
			'mysql' => [ 'mysqli', 'mysql' ],
			'postgres' => [],
			'sqlite' => [],
			'oracle' => [],
			'mssql' => [],
		];

		$driver = false;
		$dbType = strtolower( $dbType );
		if ( isset( $canonicalDBTypes[$dbType] ) && $canonicalDBTypes[$dbType] ) {
			$possibleDrivers = $canonicalDBTypes[$dbType];
			if ( !empty( $p['driver'] ) ) {
				if ( in_array( $p['driver'], $possibleDrivers ) ) {
					$driver = $p['driver'];
				} else {
					throw new InvalidArgumentException( __METHOD__ .
						" type '$dbType' does not support driver '{$p['driver']}'" );
				}
			} else {
				foreach ( $possibleDrivers as $posDriver ) {
					if ( extension_loaded( $posDriver ) ) {
						$driver = $posDriver;
						break;
					}
				}
			}
		} else {
			$driver = $dbType;
		}
		if ( $driver === false || $driver === '' ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no viable database extension found for type '$dbType'" );
		}

		$class = 'Database' . ucfirst( $driver );
		if ( class_exists( $class ) && is_subclass_of( $class, 'IDatabase' ) ) {
			// Resolve some defaults for b/c
			$p['host'] = isset( $p['host'] ) ? $p['host'] : false;
			$p['user'] = isset( $p['user'] ) ? $p['user'] : false;
			$p['password'] = isset( $p['password'] ) ? $p['password'] : false;
			$p['dbname'] = isset( $p['dbname'] ) ? $p['dbname'] : false;
			$p['flags'] = isset( $p['flags'] ) ? $p['flags'] : 0;
			$p['variables'] = isset( $p['variables'] ) ? $p['variables'] : [];
			$p['tablePrefix'] = isset( $p['tablePrefix'] ) ? $p['tablePrefix'] : '';
			$p['schema'] = isset( $p['schema'] ) ? $p['schema'] : '';
			$p['cliMode'] = isset( $p['cliMode'] ) ? $p['cliMode'] : ( PHP_SAPI === 'cli' );
			$p['agent'] = isset( $p['agent'] ) ? $p['agent'] : '';
			if ( !isset( $p['connLogger'] ) ) {
				$p['connLogger'] = new \Psr\Log\NullLogger();
			}
			if ( !isset( $p['queryLogger'] ) ) {
				$p['queryLogger'] = new \Psr\Log\NullLogger();
			}
			$p['profiler'] = isset( $p['profiler'] ) ? $p['profiler'] : null;
			if ( !isset( $p['trxProfiler'] ) ) {
				$p['trxProfiler'] = new TransactionProfiler();
			}
			if ( !isset( $p['errorLogger'] ) ) {
				$p['errorLogger'] = function ( Exception $e ) {
					trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
				};
			}

			$conn = new $class( $p );
		} else {
			$conn = null;
		}

		return $conn;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->queryLogger = $logger;
	}

	public function getServerInfo() {
		return $this->getServerVersion();
	}

	public function bufferResults( $buffer = null ) {
		$res = !$this->getFlag( self::DBO_NOBUFFER );
		if ( $buffer !== null ) {
			$buffer
				? $this->clearFlag( self::DBO_NOBUFFER )
				: $this->setFlag( self::DBO_NOBUFFER );
		}

		return $res;
	}

	/**
	 * Turns on (false) or off (true) the automatic generation and sending
	 * of a "we're sorry, but there has been a database error" page on
	 * database errors. Default is on (false). When turned off, the
	 * code should use lastErrno() and lastError() to handle the
	 * situation as appropriate.
	 *
	 * Do not use this function outside of the Database classes.
	 *
	 * @param null|bool $ignoreErrors
	 * @return bool The previous value of the flag.
	 */
	protected function ignoreErrors( $ignoreErrors = null ) {
		$res = $this->getFlag( self::DBO_IGNORE );
		if ( $ignoreErrors !== null ) {
			$ignoreErrors
				? $this->setFlag( self::DBO_IGNORE )
				: $this->clearFlag( self::DBO_IGNORE );
		}

		return $res;
	}

	public function trxLevel() {
		return $this->mTrxLevel;
	}

	public function trxTimestamp() {
		return $this->mTrxLevel ? $this->mTrxTimestamp : null;
	}

	public function tablePrefix( $prefix = null ) {
		$old = $this->mTablePrefix;
		if ( $prefix !== null ) {
			$this->mTablePrefix = $prefix;
			$this->currentDomain = ( $this->mDBname != '' )
				? new DatabaseDomain( $this->mDBname, null, $this->mTablePrefix )
				: DatabaseDomain::newUnspecified();
		}

		return $old;
	}

	public function dbSchema( $schema = null ) {
		$old = $this->mSchema;
		if ( $schema !== null ) {
			$this->mSchema = $schema;
		}

		return $old;
	}

	public function getLBInfo( $name = null ) {
		if ( is_null( $name ) ) {
			return $this->mLBInfo;
		} else {
			if ( array_key_exists( $name, $this->mLBInfo ) ) {
				return $this->mLBInfo[$name];
			} else {
				return null;
			}
		}
	}

	public function setLBInfo( $name, $value = null ) {
		if ( is_null( $value ) ) {
			$this->mLBInfo = $name;
		} else {
			$this->mLBInfo[$name] = $value;
		}
	}

	public function setLazyMasterHandle( IDatabase $conn ) {
		$this->lazyMasterHandle = $conn;
	}

	/**
	 * @return IDatabase|null
	 * @see setLazyMasterHandle()
	 * @since 1.27
	 */
	protected function getLazyMasterHandle() {
		return $this->lazyMasterHandle;
	}

	public function implicitGroupby() {
		return true;
	}

	public function implicitOrderby() {
		return true;
	}

	public function lastQuery() {
		return $this->mLastQuery;
	}

	public function doneWrites() {
		return (bool)$this->mLastWriteTime;
	}

	public function lastDoneWrites() {
		return $this->mLastWriteTime ?: false;
	}

	public function writesPending() {
		return $this->mTrxLevel && $this->mTrxDoneWrites;
	}

	public function writesOrCallbacksPending() {
		return $this->mTrxLevel && (
			$this->mTrxDoneWrites || $this->mTrxIdleCallbacks || $this->mTrxPreCommitCallbacks
		);
	}

	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL ) {
		if ( !$this->mTrxLevel ) {
			return false;
		} elseif ( !$this->mTrxDoneWrites ) {
			return 0.0;
		}

		switch ( $type ) {
			case self::ESTIMATE_DB_APPLY:
				$this->ping( $rtt );
				$rttAdjTotal = $this->mTrxWriteAdjQueryCount * $rtt;
				$applyTime = max( $this->mTrxWriteAdjDuration - $rttAdjTotal, 0 );
				// For omitted queries, make them count as something at least
				$omitted = $this->mTrxWriteQueryCount - $this->mTrxWriteAdjQueryCount;
				$applyTime += self::TINY_WRITE_SEC * $omitted;

				return $applyTime;
			default: // everything
				return $this->mTrxWriteDuration;
		}
	}

	public function pendingWriteCallers() {
		return $this->mTrxLevel ? $this->mTrxWriteCallers : [];
	}

	protected function pendingWriteAndCallbackCallers() {
		if ( !$this->mTrxLevel ) {
			return [];
		}

		$fnames = $this->mTrxWriteCallers;
		foreach ( [
			$this->mTrxIdleCallbacks,
			$this->mTrxPreCommitCallbacks,
			$this->mTrxEndCallbacks
		] as $callbacks ) {
			foreach ( $callbacks as $callback ) {
				$fnames[] = $callback[1];
			}
		}

		return $fnames;
	}

	public function isOpen() {
		return $this->mOpened;
	}

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $remember === self::REMEMBER_PRIOR ) {
			array_push( $this->priorFlags, $this->mFlags );
		}
		$this->mFlags |= $flag;
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		if ( $remember === self::REMEMBER_PRIOR ) {
			array_push( $this->priorFlags, $this->mFlags );
		}
		$this->mFlags &= ~$flag;
	}

	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		if ( !$this->priorFlags ) {
			return;
		}

		if ( $state === self::RESTORE_INITIAL ) {
			$this->mFlags = reset( $this->priorFlags );
			$this->priorFlags = [];
		} else {
			$this->mFlags = array_pop( $this->priorFlags );
		}
	}

	public function getFlag( $flag ) {
		return !!( $this->mFlags & $flag );
	}

	/**
	 * @param string $name Class field name
	 * @return mixed
	 * @deprecated Since 1.28
	 */
	public function getProperty( $name ) {
		return $this->$name;
	}

	public function getDomainID() {
		return $this->currentDomain->getId();
	}

	final public function getWikiID() {
		return $this->getDomainID();
	}

	/**
	 * Get information about an index into an object
	 * @param string $table Table name
	 * @param string $index Index name
	 * @param string $fname Calling function name
	 * @return mixed Database-specific index description class or false if the index does not exist
	 */
	abstract function indexInfo( $table, $index, $fname = __METHOD__ );

	/**
	 * Wrapper for addslashes()
	 *
	 * @param string $s String to be slashed.
	 * @return string Slashed string.
	 */
	abstract function strencode( $s );

	protected function installErrorHandler() {
		$this->mPHPError = false;
		$this->htmlErrors = ini_set( 'html_errors', '0' );
		set_error_handler( [ $this, 'connectionErrorLogger' ] );
	}

	/**
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
		if ( $this->mPHPError ) {
			$error = preg_replace( '!\[<a.*</a>\]!', '', $this->mPHPError );
			$error = preg_replace( '!^.*?:\s?(.*)$!', '$1', $error );

			return $error;
		}

		return false;
	}

	/**
	 * This method should not be used outside of Database classes
	 *
	 * @param int $errno
	 * @param string $errstr
	 */
	public function connectionErrorLogger( $errno, $errstr ) {
		$this->mPHPError = $errstr;
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
				'db_server' => $this->mServer,
				'db_name' => $this->mDBname,
				'db_user' => $this->mUser,
			],
			$extras
		);
	}

	public function close() {
		if ( $this->mConn ) {
			if ( $this->trxLevel() ) {
				$this->commit( __METHOD__, self::FLUSHING_INTERNAL );
			}

			$closed = $this->closeConnection();
			$this->mConn = false;
		} elseif ( $this->mTrxIdleCallbacks || $this->mTrxEndCallbacks ) { // sanity
			throw new RuntimeException( "Transaction callbacks still pending." );
		} else {
			$closed = true;
		}
		$this->mOpened = false;

		return $closed;
	}

	/**
	 * Make sure isOpen() returns true as a sanity check
	 *
	 * @throws DBUnexpectedError
	 */
	protected function assertOpen() {
		if ( !$this->isOpen() ) {
			throw new DBUnexpectedError( $this, "DB connection was already closed." );
		}
	}

	/**
	 * Closes underlying database connection
	 * @since 1.20
	 * @return bool Whether connection was closed successfully
	 */
	abstract protected function closeConnection();

	public function reportConnectionError( $error = 'Unknown error' ) {
		$myError = $this->lastError();
		if ( $myError ) {
			$error = $myError;
		}

		# New method
		throw new DBConnectionError( $this, $error );
	}

	/**
	 * The DBMS-dependent part of query()
	 *
	 * @param string $sql SQL query.
	 * @return ResultWrapper|bool Result object to feed to fetchObject,
	 *   fetchRow, ...; or false on failure
	 */
	abstract protected function doQuery( $sql );

	/**
	 * Determine whether a query writes to the DB.
	 * Should return true if unsure.
	 *
	 * @param string $sql
	 * @return bool
	 */
	protected function isWriteQuery( $sql ) {
		return !preg_match(
			'/^(?:SELECT|BEGIN|ROLLBACK|COMMIT|SET|SHOW|EXPLAIN|\(SELECT)\b/i', $sql );
	}

	/**
	 * @param $sql
	 * @return string|null
	 */
	protected function getQueryVerb( $sql ) {
		return preg_match( '/^\s*([a-z]+)/i', $sql, $m ) ? strtoupper( $m[1] ) : null;
	}

	/**
	 * Determine whether a SQL statement is sensitive to isolation level.
	 * A SQL statement is considered transactable if its result could vary
	 * depending on the transaction isolation level. Operational commands
	 * such as 'SET' and 'SHOW' are not considered to be transactable.
	 *
	 * @param string $sql
	 * @return bool
	 */
	protected function isTransactableQuery( $sql ) {
		return !in_array(
			$this->getQueryVerb( $sql ),
			[ 'BEGIN', 'COMMIT', 'ROLLBACK', 'SHOW', 'SET', 'CREATE', 'ALTER' ],
			true
		);
	}

	/**
	 * @param string $sql A SQL query
	 * @return bool Whether $sql is SQL for creating/dropping a new TEMPORARY table
	 */
	protected function registerTempTableOperation( $sql ) {
		if ( preg_match(
			'/^CREATE\s+TEMPORARY\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"\']?(\w+)[`"\']?/i',
			$sql,
			$matches
		) ) {
			$this->mSessionTempTables[$matches[1]] = 1;

			return true;
		} elseif ( preg_match(
			'/^DROP\s+(?:TEMPORARY\s+)?TABLE\s+(?:IF\s+EXISTS\s+)?[`"\']?(\w+)[`"\']?/i',
			$sql,
			$matches
		) ) {
			unset( $this->mSessionTempTables[$matches[1]] );

			return true;
		} elseif ( preg_match(
			'/^(?:INSERT\s+(?:\w+\s+)?INTO|UPDATE|DELETE\s+FROM)\s+[`"\']?(\w+)[`"\']?/i',
			$sql,
			$matches
		) ) {
			return isset( $this->mSessionTempTables[$matches[1]] );
		}

		return false;
	}

	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		$priorWritesPending = $this->writesOrCallbacksPending();
		$this->mLastQuery = $sql;

		$isWrite = $this->isWriteQuery( $sql ) && !$this->registerTempTableOperation( $sql );
		if ( $isWrite ) {
			$reason = $this->getReadOnlyReason();
			if ( $reason !== false ) {
				throw new DBReadOnlyError( $this, "Database is read-only: $reason" );
			}
			# Set a flag indicating that writes have been done
			$this->mLastWriteTime = microtime( true );
		}

		// Add trace comment to the begin of the sql string, right after the operator.
		// Or, for one-word queries (like "BEGIN" or COMMIT") add it to the end (bug 42598)
		$commentedSql = preg_replace( '/\s|$/', " /* $fname {$this->agent} */ ", $sql, 1 );

		# Start implicit transactions that wrap the request if DBO_TRX is enabled
		if ( !$this->mTrxLevel && $this->getFlag( self::DBO_TRX )
			&& $this->isTransactableQuery( $sql )
		) {
			$this->begin( __METHOD__ . " ($fname)", self::TRANSACTION_INTERNAL );
			$this->mTrxAutomatic = true;
		}

		# Keep track of whether the transaction has write queries pending
		if ( $this->mTrxLevel && !$this->mTrxDoneWrites && $isWrite ) {
			$this->mTrxDoneWrites = true;
			$this->trxProfiler->transactionWritingIn(
				$this->mServer, $this->mDBname, $this->mTrxShortId );
		}

		if ( $this->getFlag( self::DBO_DEBUG ) ) {
			$this->queryLogger->debug( "{$this->mDBname} {$commentedSql}" );
		}

		# Avoid fatals if close() was called
		$this->assertOpen();

		# Send the query to the server
		$ret = $this->doProfiledQuery( $sql, $commentedSql, $isWrite, $fname );

		# Try reconnecting if the connection was lost
		if ( false === $ret && $this->wasErrorReissuable() ) {
			$recoverable = $this->canRecoverFromDisconnect( $sql, $priorWritesPending );
			# Stash the last error values before anything might clear them
			$lastError = $this->lastError();
			$lastErrno = $this->lastErrno();
			# Update state tracking to reflect transaction loss due to disconnection
			$this->handleSessionLoss();
			if ( $this->reconnect() ) {
				$msg = __METHOD__ . ": lost connection to {$this->getServer()}; reconnected";
				$this->connLogger->warning( $msg );
				$this->queryLogger->warning(
					"$msg:\n" . ( new RuntimeException() )->getTraceAsString() );

				if ( !$recoverable ) {
					# Callers may catch the exception and continue to use the DB
					$this->reportQueryError( $lastError, $lastErrno, $sql, $fname );
				} else {
					# Should be safe to silently retry the query
					$ret = $this->doProfiledQuery( $sql, $commentedSql, $isWrite, $fname );
				}
			} else {
				$msg = __METHOD__ . ": lost connection to {$this->getServer()} permanently";
				$this->connLogger->error( $msg );
			}
		}

		if ( false === $ret ) {
			# Deadlocks cause the entire transaction to abort, not just the statement.
			# http://dev.mysql.com/doc/refman/5.7/en/innodb-error-handling.html
			# https://www.postgresql.org/docs/9.1/static/explicit-locking.html
			if ( $this->wasDeadlock() ) {
				if ( $this->explicitTrxActive() || $priorWritesPending ) {
					$tempIgnore = false; // not recoverable
				}
				# Update state tracking to reflect transaction loss
				$this->handleSessionLoss();
			}

			$this->reportQueryError(
				$this->lastError(), $this->lastErrno(), $sql, $fname, $tempIgnore );
		}

		$res = $this->resultObject( $ret );

		return $res;
	}

	private function doProfiledQuery( $sql, $commentedSql, $isWrite, $fname ) {
		$isMaster = !is_null( $this->getLBInfo( 'master' ) );
		# generalizeSQL() will probably cut down the query to reasonable
		# logging size most of the time. The substr is really just a sanity check.
		if ( $isMaster ) {
			$queryProf = 'query-m: ' . substr( self::generalizeSQL( $sql ), 0, 255 );
		} else {
			$queryProf = 'query: ' . substr( self::generalizeSQL( $sql ), 0, 255 );
		}

		# Include query transaction state
		$queryProf .= $this->mTrxShortId ? " [TRX#{$this->mTrxShortId}]" : "";

		$startTime = microtime( true );
		if ( $this->profiler ) {
			call_user_func( [ $this->profiler, 'profileIn' ], $queryProf );
		}
		$ret = $this->doQuery( $commentedSql );
		if ( $this->profiler ) {
			call_user_func( [ $this->profiler, 'profileOut' ], $queryProf );
		}
		$queryRuntime = max( microtime( true ) - $startTime, 0.0 );

		unset( $queryProfSection ); // profile out (if set)

		if ( $ret !== false ) {
			$this->lastPing = $startTime;
			if ( $isWrite && $this->mTrxLevel ) {
				$this->updateTrxWriteQueryTime( $sql, $queryRuntime );
				$this->mTrxWriteCallers[] = $fname;
			}
		}

		if ( $sql === self::PING_QUERY ) {
			$this->mRTTEstimate = $queryRuntime;
		}

		$this->trxProfiler->recordQueryCompletion(
			$queryProf, $startTime, $isWrite, $this->affectedRows()
		);
		$this->queryLogger->debug( $sql, [
			'method' => $fname,
			'master' => $isMaster,
			'runtime' => $queryRuntime,
		] );

		return $ret;
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
	 */
	private function updateTrxWriteQueryTime( $sql, $runtime ) {
		// Whether this is indicative of replica DB runtime (except for RBR or ws_repl)
		$indicativeOfReplicaRuntime = true;
		if ( $runtime > self::SLOW_WRITE_SEC ) {
			$verb = $this->getQueryVerb( $sql );
			// insert(), upsert(), replace() are fast unless bulky in size or blocked on locks
			if ( $verb === 'INSERT' ) {
				$indicativeOfReplicaRuntime = $this->affectedRows() > self::SMALL_WRITE_ROWS;
			} elseif ( $verb === 'REPLACE' ) {
				$indicativeOfReplicaRuntime = $this->affectedRows() > self::SMALL_WRITE_ROWS / 2;
			}
		}

		$this->mTrxWriteDuration += $runtime;
		$this->mTrxWriteQueryCount += 1;
		if ( $indicativeOfReplicaRuntime ) {
			$this->mTrxWriteAdjDuration += $runtime;
			$this->mTrxWriteAdjQueryCount += 1;
		}
	}

	private function canRecoverFromDisconnect( $sql, $priorWritesPending ) {
		# Transaction dropped; this can mean lost writes, or REPEATABLE-READ snapshots.
		# Dropped connections also mean that named locks are automatically released.
		# Only allow error suppression in autocommit mode or when the lost transaction
		# didn't matter anyway (aside from DBO_TRX snapshot loss).
		if ( $this->mNamedLocksHeld ) {
			return false; // possible critical section violation
		} elseif ( $sql === 'COMMIT' ) {
			return !$priorWritesPending; // nothing written anyway? (T127428)
		} elseif ( $sql === 'ROLLBACK' ) {
			return true; // transaction lost...which is also what was requested :)
		} elseif ( $this->explicitTrxActive() ) {
			return false; // don't drop atomocity
		} elseif ( $priorWritesPending ) {
			return false; // prior writes lost from implicit transaction
		}

		return true;
	}

	private function handleSessionLoss() {
		$this->mTrxLevel = 0;
		$this->mTrxIdleCallbacks = []; // bug 65263
		$this->mTrxPreCommitCallbacks = []; // bug 65263
		$this->mSessionTempTables = [];
		$this->mNamedLocksHeld = [];
		try {
			// Handle callbacks in mTrxEndCallbacks
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
			$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
			return null;
		} catch ( Exception $e ) {
			// Already logged; move on...
			return $e;
		}
	}

	public function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		if ( $this->ignoreErrors() || $tempIgnore ) {
			$this->queryLogger->debug( "SQL ERROR (ignored): $error\n" );
		} else {
			$sql1line = mb_substr( str_replace( "\n", "\\n", $sql ), 0, 5 * 1024 );
			$this->queryLogger->error(
				"{fname}\t{db_server}\t{errno}\t{error}\t{sql1line}",
				$this->getLogContext( [
					'method' => __METHOD__,
					'errno' => $errno,
					'error' => $error,
					'sql1line' => $sql1line,
					'fname' => $fname,
				] )
			);
			$this->queryLogger->debug( "SQL ERROR: " . $error . "\n" );
			throw new DBQueryError( $this, $error, $errno, $sql, $fname );
		}
	}

	public function freeResult( $res ) {
	}

	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = []
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field: got '$var'" );
		}

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$options['LIMIT'] = 1;

		$res = $this->select( $table, $var, $cond, $fname, $options );
		if ( $res === false || !$this->numRows( $res ) ) {
			return false;
		}

		$row = $this->fetchRow( $res );

		if ( $row !== false ) {
			return reset( $row );
		} else {
			return false;
		}
	}

	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field" );
		} elseif ( !is_string( $var ) ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use an array of fields" );
		}

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$res = $this->select( $table, $var, $cond, $fname, $options, $join_conds );
		if ( $res === false ) {
			return false;
		}

		$values = [];
		foreach ( $res as $row ) {
			$values[] = $row->$var;
		}

		return $values;
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query.
	 *
	 * @param array $options Associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 * @see Database::select()
	 */
	protected function makeSelectOptions( $options ) {
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

		// if (isset($options['LIMIT'])) {
		// 	$tailOpts .= $this->limitResult('', $options['LIMIT'],
		// 		isset($options['OFFSET']) ? $options['OFFSET']
		// 		: false);
		// }

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

		if ( isset( $noKeyOptions['HIGH_PRIORITY'] ) ) {
			$startOpts .= ' HIGH_PRIORITY';
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

		if ( isset( $noKeyOptions['SQL_CACHE'] ) ) {
			$startOpts .= ' SQL_CACHE';
		}

		if ( isset( $noKeyOptions['SQL_NO_CACHE'] ) ) {
			$startOpts .= ' SQL_NO_CACHE';
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

	public function select( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = [] ) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		return $this->query( $sql, $fname );
	}

	public function selectSQLText( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		if ( is_array( $vars ) ) {
			$vars = implode( ',', $this->fieldNamesWithAlias( $vars ) );
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

		if ( is_array( $table ) ) {
			$from = ' FROM ' .
				$this->tableNamesWithIndexClauseOrJOIN(
					$table, $useIndexes, $ignoreIndexes, $join_conds );
		} elseif ( $table != '' ) {
			if ( $table[0] == ' ' ) {
				$from = ' FROM ' . $table;
			} else {
				$from = ' FROM ' .
					$this->tableNamesWithIndexClauseOrJOIN(
						[ $table ], $useIndexes, $ignoreIndexes, [] );
			}
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ) =
			$this->makeSelectOptions( $options );

		if ( !empty( $conds ) ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql = "SELECT $startOpts $vars $from $useIndex $ignoreIndex " .
				"WHERE $conds $preLimitTail";
		} else {
			$sql = "SELECT $startOpts $vars $from $useIndex $ignoreIndex $preLimitTail";
		}

		if ( isset( $options['LIMIT'] ) ) {
			$sql = $this->limitResult( $sql, $options['LIMIT'],
				isset( $options['OFFSET'] ) ? $options['OFFSET'] : false );
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
			return false;
		}

		if ( !$this->numRows( $res ) ) {
			return false;
		}

		$obj = $this->fetchObject( $res );

		return $obj;
	}

	public function estimateRowCount(
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = []
	) {
		$rows = 0;
		$res = $this->select( $table, [ 'rowcount' => 'COUNT(*)' ], $conds, $fname, $options );

		if ( $res ) {
			$row = $this->fetchRow( $res );
			$rows = ( isset( $row['rowcount'] ) ) ? (int)$row['rowcount'] : 0;
		}

		return $rows;
	}

	public function selectRowCount(
		$tables, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$rows = 0;
		$sql = $this->selectSQLText( $tables, '1', $conds, $fname, $options, $join_conds );
		$res = $this->query( "SELECT COUNT(*) AS rowcount FROM ($sql) tmp_count", $fname );

		if ( $res ) {
			$row = $this->fetchRow( $res );
			$rows = ( isset( $row['rowcount'] ) ) ? (int)$row['rowcount'] : 0;
		}

		return $rows;
	}

	/**
	 * Removes most variables from an SQL query and replaces them with X or N for numbers.
	 * It's only slightly flawed. Don't use for anything important.
	 *
	 * @param string $sql A SQL Query
	 *
	 * @return string
	 */
	protected static function generalizeSQL( $sql ) {
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql );

		$sql = str_replace( "\\\\", '', $sql );
		$sql = str_replace( "\\'", '', $sql );
		$sql = str_replace( "\\\"", '', $sql );
		$sql = preg_replace( "/'.*'/s", "'X'", $sql );
		$sql = preg_replace( '/".*"/s', "'X'", $sql );

		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace( '/\s+/', ' ', $sql );

		# All numbers => N,
		# except the ones surrounded by characters, e.g. l10n
		$sql = preg_replace( '/-?\d+(,-?\d+)+/s', 'N,...,N', $sql );
		$sql = preg_replace( '/(?<![a-zA-Z])-?\d+(?![a-zA-Z])/s', 'N', $sql );

		return $sql;
	}

	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		$info = $this->fieldInfo( $table, $field );

		return (bool)$info;
	}

	public function indexExists( $table, $index, $fname = __METHOD__ ) {
		if ( !$this->tableExists( $table ) ) {
			return null;
		}

		$info = $this->indexInfo( $table, $index, $fname );
		if ( is_null( $info ) ) {
			return null;
		} else {
			return $info !== false;
		}
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		$tableRaw = $this->tableName( $table, 'raw' );
		if ( isset( $this->mSessionTempTables[$tableRaw] ) ) {
			return true; // already known to exist
		}

		$table = $this->tableName( $table );
		$old = $this->ignoreErrors( true );
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1", $fname );
		$this->ignoreErrors( $old );

		return (bool)$res;
	}

	public function indexUnique( $table, $index ) {
		$indexInfo = $this->indexInfo( $table, $index );

		if ( !$indexInfo ) {
			return null;
		}

		return !$indexInfo[0]->Non_unique;
	}

	/**
	 * Helper for Database::insert().
	 *
	 * @param array $options
	 * @return string
	 */
	protected function makeInsertOptions( $options ) {
		return implode( ' ', $options );
	}

	public function insert( $table, $a, $fname = __METHOD__, $options = [] ) {
		# No rows to insert, easy just return now
		if ( !count( $a ) ) {
			return true;
		}

		$table = $this->tableName( $table );

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$fh = null;
		if ( isset( $options['fileHandle'] ) ) {
			$fh = $options['fileHandle'];
		}
		$options = $this->makeInsertOptions( $options );

		if ( isset( $a[0] ) && is_array( $a[0] ) ) {
			$multi = true;
			$keys = array_keys( $a[0] );
		} else {
			$multi = false;
			$keys = array_keys( $a );
		}

		$sql = 'INSERT ' . $options .
			" INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( $multi ) {
			$first = true;
			foreach ( $a as $row ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				$sql .= '(' . $this->makeList( $row ) . ')';
			}
		} else {
			$sql .= '(' . $this->makeList( $a ) . ')';
		}

		if ( $fh !== null && false === fwrite( $fh, $sql ) ) {
			return false;
		} elseif ( $fh !== null ) {
			return true;
		}

		return (bool)$this->query( $sql, $fname );
	}

	/**
	 * Make UPDATE options array for Database::makeUpdateOptions
	 *
	 * @param array $options
	 * @return array
	 */
	protected function makeUpdateOptionsArray( $options ) {
		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$opts = [];

		if ( in_array( 'IGNORE', $options ) ) {
			$opts[] = 'IGNORE';
		}

		return $opts;
	}

	/**
	 * Make UPDATE options for the Database::update function
	 *
	 * @param array $options The options passed to Database::update
	 * @return string
	 */
	protected function makeUpdateOptions( $options ) {
		$opts = $this->makeUpdateOptionsArray( $options );

		return implode( ' ', $opts );
	}

	public function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, self::LIST_SET );

		if ( $conds !== [] && $conds !== '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, self::LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	public function makeList( $a, $mode = self::LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with incorrect parameters' );
		}

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
					[ $baseKey => $base, $subKey => array_keys( $sub ) ],
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

	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuename;
	}

	public function bitNot( $field ) {
		return "(~$field)";
	}

	public function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	public function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	public function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "GROUP_CONCAT($field SEPARATOR " . $this->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	public function buildStringCast( $field ) {
		return $field;
	}

	public function selectDB( $db ) {
		# Stub. Shouldn't cause serious problems if it's not overridden, but
		# if your database engine supports a concept similar to MySQL's
		# databases you may as well.
		$this->mDBname = $db;

		return true;
	}

	public function getDBname() {
		return $this->mDBname;
	}

	public function getServer() {
		return $this->mServer;
	}

	public function tableName( $name, $format = 'quoted' ) {
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
			return $name;
		}

		# Split database and table into proper variables.
		# We reverse the explode so that database.table and table both output
		# the correct table.
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
					: $this->mSchema;
				$prefix = is_string( $this->tableAliases[$table]['prefix'] )
					? $this->tableAliases[$table]['prefix']
					: $this->mTablePrefix;
			} else {
				$database = '';
				$schema = $this->mSchema; # Default schema
				$prefix = $this->mTablePrefix; # Default prefix
			}
		}

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

	public function tableNames() {
		$inArray = func_get_args();
		$retVal = [];

		foreach ( $inArray as $name ) {
			$retVal[$name] = $this->tableName( $name );
		}

		return $retVal;
	}

	public function tableNamesN() {
		$inArray = func_get_args();
		$retVal = [];

		foreach ( $inArray as $name ) {
			$retVal[] = $this->tableName( $name );
		}

		return $retVal;
	}

	/**
	 * Get an aliased table name
	 * e.g. tableName AS newTableName
	 *
	 * @param string $name Table name, see tableName()
	 * @param string|bool $alias Alias (optional)
	 * @return string SQL name for aliased table. Will not alias a table to its own name
	 */
	protected function tableNameWithAlias( $name, $alias = false ) {
		if ( !$alias || $alias == $name ) {
			return $this->tableName( $name );
		} else {
			return $this->tableName( $name ) . ' ' . $this->addIdentifierQuotes( $alias );
		}
	}

	/**
	 * Gets an array of aliased table names
	 *
	 * @param array $tables [ [alias] => table ]
	 * @return string[] See tableNameWithAlias()
	 */
	protected function tableNamesWithAlias( $tables ) {
		$retval = [];
		foreach ( $tables as $alias => $table ) {
			if ( is_numeric( $alias ) ) {
				$alias = $table;
			}
			$retval[] = $this->tableNameWithAlias( $table, $alias );
		}

		return $retval;
	}

	/**
	 * Get an aliased field name
	 * e.g. fieldName AS newFieldName
	 *
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
			// Is there a JOIN clause for this table?
			if ( isset( $join_conds[$alias] ) ) {
				list( $joinType, $conds ) = $join_conds[$alias];
				$tableClause = $joinType;
				$tableClause .= ' ' . $this->tableNameWithAlias( $table, $alias );
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
				$tableClause = $this->tableNameWithAlias( $table, $alias );
				$tableClause .= ' ' . $this->useIndexClause(
						implode( ',', (array)$use_index[$alias] )
					);

				$ret[] = $tableClause;
			} elseif ( isset( $ignore_index[$alias] ) ) {
				// Is there an INDEX clause for this table?
				$tableClause = $this->tableNameWithAlias( $table, $alias );
				$tableClause .= ' ' . $this->ignoreIndexClause(
						implode( ',', (array)$ignore_index[$alias] )
					);

				$ret[] = $tableClause;
			} else {
				$tableClause = $this->tableNameWithAlias( $table, $alias );

				$ret[] = $tableClause;
			}
		}

		// We can't separate explicit JOIN clauses with ',', use ' ' for those
		$implicitJoins = !empty( $ret ) ? implode( ',', $ret ) : "";
		$explicitJoins = !empty( $retJOIN ) ? implode( ' ', $retJOIN ) : "";

		// Compile our final table clause
		return implode( ' ', [ $implicitJoins, $explicitJoins ] );
	}

	/**
	 * Get the name of an index in a given table.
	 *
	 * @param string $index
	 * @return string
	 */
	protected function indexName( $index ) {
		return $index;
	}

	public function addQuotes( $s ) {
		if ( $s instanceof Blob ) {
			$s = $s->fetch();
		}
		if ( $s === null ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return (int)$s;
		} else {
			# This will also quote numeric values. This should be harmless,
			# and protects against weird problems that occur when they really
			# _are_ strings such as article titles and string->number->string
			# conversion is not 1:1.
			return "'" . $this->strencode( $s ) . "'";
		}
	}

	/**
	 * Quotes an identifier using `backticks` or "double quotes" depending on the database type.
	 * MySQL uses `backticks` while basically everything else uses double quotes.
	 * Since MySQL is the odd one out here the double quotes are our generic
	 * and we implement backticks in DatabaseMysql.
	 *
	 * @param string $s
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		return '"' . str_replace( '"', '""', $s ) . '"';
	}

	/**
	 * Returns if the given identifier looks quoted or not according to
	 * the database convention for quoting identifiers .
	 *
	 * @note Do not use this to determine if untrusted input is safe.
	 *   A malicious user can trick this function.
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return $name[0] == '"' && substr( $name, -1, 1 ) == '"';
	}

	/**
	 * @param string $s
	 * @return string
	 */
	protected function escapeLikeInternal( $s ) {
		return addcslashes( $s, '\%_' );
	}

	public function buildLike() {
		$params = func_get_args();

		if ( count( $params ) > 0 && is_array( $params[0] ) ) {
			$params = $params[0];
		}

		$s = '';

		foreach ( $params as $value ) {
			if ( $value instanceof LikeMatch ) {
				$s .= $value->toString();
			} else {
				$s .= $this->escapeLikeInternal( $value );
			}
		}

		return " LIKE {$this->addQuotes( $s )} ";
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
	 * USE INDEX clause. Unlikely to be useful for anything but MySQL. This
	 * is only needed because a) MySQL must be as efficient as possible due to
	 * its use on Wikipedia, and b) MySQL 4.0 is kind of dumb sometimes about
	 * which index to pick. Anyway, other databases might have different
	 * indexes on a given table. So don't bother overriding this unless you're
	 * MySQL.
	 * @param string $index
	 * @return string
	 */
	public function useIndexClause( $index ) {
		return '';
	}

	/**
	 * IGNORE INDEX clause. Unlikely to be useful for anything but MySQL. This
	 * is only needed because a) MySQL must be as efficient as possible due to
	 * its use on Wikipedia, and b) MySQL 4.0 is kind of dumb sometimes about
	 * which index to pick. Anyway, other databases might have different
	 * indexes on a given table. So don't bother overriding this unless you're
	 * MySQL.
	 * @param string $index
	 * @return string
	 */
	public function ignoreIndexClause( $index ) {
		return '';
	}

	public function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ ) {
		$quotedTable = $this->tableName( $table );

		if ( count( $rows ) == 0 ) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = [ $rows ];
		}

		// @FXIME: this is not atomic, but a trx would break affectedRows()
		foreach ( $rows as $row ) {
			# Delete rows which collide
			if ( $uniqueIndexes ) {
				$sql = "DELETE FROM $quotedTable WHERE ";
				$first = true;
				foreach ( $uniqueIndexes as $index ) {
					if ( $first ) {
						$first = false;
						$sql .= '( ';
					} else {
						$sql .= ' ) OR ( ';
					}
					if ( is_array( $index ) ) {
						$first2 = true;
						foreach ( $index as $col ) {
							if ( $first2 ) {
								$first2 = false;
							} else {
								$sql .= ' AND ';
							}
							$sql .= $col . '=' . $this->addQuotes( $row[$col] );
						}
					} else {
						$sql .= $index . '=' . $this->addQuotes( $row[$index] );
					}
				}
				$sql .= ' )';
				$this->query( $sql, $fname );
			}

			# Now insert the row
			$this->insert( $table, $row, $fname );
		}
	}

	/**
	 * REPLACE query wrapper for MySQL and SQLite, which have a native REPLACE
	 * statement.
	 *
	 * @param string $table Table name
	 * @param array|string $rows Row(s) to insert
	 * @param string $fname Caller function name
	 *
	 * @return ResultWrapper
	 */
	protected function nativeReplace( $table, $rows, $fname ) {
		$table = $this->tableName( $table );

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = [ $rows ];
		}

		$sql = "REPLACE INTO $table (" . implode( ',', array_keys( $rows[0] ) ) . ') VALUES ';
		$first = true;

		foreach ( $rows as $row ) {
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ',';
			}

			$sql .= '(' . $this->makeList( $row ) . ')';
		}

		return $this->query( $sql, $fname );
	}

	public function upsert( $table, array $rows, array $uniqueIndexes, array $set,
		$fname = __METHOD__
	) {
		if ( !count( $rows ) ) {
			return true; // nothing to do
		}

		if ( !is_array( reset( $rows ) ) ) {
			$rows = [ $rows ];
		}

		if ( count( $uniqueIndexes ) ) {
			$clauses = []; // list WHERE clauses that each identify a single row
			foreach ( $rows as $row ) {
				foreach ( $uniqueIndexes as $index ) {
					$index = is_array( $index ) ? $index : [ $index ]; // columns
					$rowKey = []; // unique key to this row
					foreach ( $index as $column ) {
						$rowKey[$column] = $row[$column];
					}
					$clauses[] = $this->makeList( $rowKey, self::LIST_AND );
				}
			}
			$where = [ $this->makeList( $clauses, self::LIST_OR ) ];
		} else {
			$where = false;
		}

		$useTrx = !$this->mTrxLevel;
		if ( $useTrx ) {
			$this->begin( $fname, self::TRANSACTION_INTERNAL );
		}
		try {
			# Update any existing conflicting row(s)
			if ( $where !== false ) {
				$ok = $this->update( $table, $set, $where, $fname );
			} else {
				$ok = true;
			}
			# Now insert any non-conflicting row(s)
			$ok = $this->insert( $table, $rows, $fname, [ 'IGNORE' ] ) && $ok;
		} catch ( Exception $e ) {
			if ( $useTrx ) {
				$this->rollback( $fname, self::FLUSHING_INTERNAL );
			}
			throw $e;
		}
		if ( $useTrx ) {
			$this->commit( $fname, self::FLUSHING_INTERNAL );
		}

		return $ok;
	}

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

		$this->query( $sql, $fname );
	}

	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SHOW COLUMNS FROM $table LIKE \"$field\";";
		$res = $this->query( $sql, __METHOD__ );
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
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with no conditions' );
		}

		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";

		if ( $conds != '*' ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		return $this->query( $sql, $fname );
	}

	public function insertSelect(
		$destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__, $insertOptions = [], $selectOptions = []
	) {
		if ( $this->cliMode ) {
			// For massive migrations with downtime, we don't want to select everything
			// into memory and OOM, so do all this native on the server side if possible.
			return $this->nativeInsertSelect(
				$destTable,
				$srcTable,
				$varMap,
				$conds,
				$fname,
				$insertOptions,
				$selectOptions
			);
		}

		// For web requests, do a locking SELECT and then INSERT. This puts the SELECT burden
		// on only the master (without needing row-based-replication). It also makes it easy to
		// know how big the INSERT is going to be.
		$fields = [];
		foreach ( $varMap as $dstColumn => $sourceColumnOrSql ) {
			$fields[] = $this->fieldNameWithAlias( $sourceColumnOrSql, $dstColumn );
		}
		$selectOptions[] = 'FOR UPDATE';
		$res = $this->select( $srcTable, implode( ',', $fields ), $conds, $fname, $selectOptions );
		if ( !$res ) {
			return false;
		}

		$rows = [];
		foreach ( $res as $row ) {
			$rows[] = (array)$row;
		}

		return $this->insert( $destTable, $rows, $fname, $insertOptions );
	}

	protected function nativeInsertSelect( $destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__,
		$insertOptions = [], $selectOptions = []
	) {
		$destTable = $this->tableName( $destTable );

		if ( !is_array( $insertOptions ) ) {
			$insertOptions = [ $insertOptions ];
		}

		$insertOptions = $this->makeInsertOptions( $insertOptions );

		if ( !is_array( $selectOptions ) ) {
			$selectOptions = [ $selectOptions ];
		}

		list( $startOpts, $useIndex, $tailOpts, $ignoreIndex ) = $this->makeSelectOptions(
			$selectOptions );

		if ( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( [ &$this, 'tableName' ], $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		$sql = "INSERT $insertOptions" .
			" INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			" SELECT $startOpts " . implode( ',', $varMap ) .
			" FROM $srcTable $useIndex $ignoreIndex ";

		if ( $conds != '*' ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, self::LIST_AND );
			}
			$sql .= " WHERE $conds";
		}

		$sql .= " $tailOpts";

		return $this->query( $sql, $fname );
	}

	/**
	 * Construct a LIMIT query with optional offset. This is used for query
	 * pages. The SQL should be adjusted so that only the first $limit rows
	 * are returned. If $offset is provided as well, then the first $offset
	 * rows should be discarded, and the next $limit rows should be returned.
	 * If the result of the query is not ordered, then the rows to be returned
	 * are theoretically arbitrary.
	 *
	 * $sql is expected to be a SELECT, if that makes a difference.
	 *
	 * The version provided by default works in MySQL and SQLite. It will very
	 * likely need to be overridden for most other DBMSes.
	 *
	 * @param string $sql SQL query we will append the limit too
	 * @param int $limit The SQL limit
	 * @param int|bool $offset The SQL offset (default false)
	 * @throws DBUnexpectedError
	 * @return string
	 */
	public function limitResult( $sql, $limit, $offset = false ) {
		if ( !is_numeric( $limit ) ) {
			throw new DBUnexpectedError( $this,
				"Invalid non-numeric limit passed to limitResult()\n" );
		}

		return "$sql LIMIT "
		. ( ( is_numeric( $offset ) && $offset != 0 ) ? "{$offset}," : "" )
		. "{$limit} ";
	}

	public function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	public function unionQueries( $sqls, $all ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';

		return '(' . implode( $glue, $sqls ) . ')';
	}

	public function conditional( $cond, $trueVal, $falseVal ) {
		if ( is_array( $cond ) ) {
			$cond = $this->makeList( $cond, self::LIST_AND );
		}

		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	public function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

	public function getServerUptime() {
		return 0;
	}

	public function wasDeadlock() {
		return false;
	}

	public function wasLockTimeout() {
		return false;
	}

	public function wasErrorReissuable() {
		return false;
	}

	public function wasReadOnlyError() {
		return false;
	}

	/**
	 * Do not use this method outside of Database/DBError classes
	 *
	 * @param integer|string $errno
	 * @return bool Whether the given query error was a connection drop
	 */
	public function wasConnectionError( $errno ) {
		return false;
	}

	public function deadlockLoop() {
		$args = func_get_args();
		$function = array_shift( $args );
		$tries = self::DEADLOCK_TRIES;

		$this->begin( __METHOD__ );

		$retVal = null;
		/** @var Exception $e */
		$e = null;
		do {
			try {
				$retVal = call_user_func_array( $function, $args );
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

	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		# Real waits are implemented in the subclass.
		return 0;
	}

	public function getReplicaPos() {
		# Stub
		return false;
	}

	public function getMasterPos() {
		# Stub
		return false;
	}

	public function serverIsReadOnly() {
		return false;
	}

	final public function onTransactionResolution( callable $callback, $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			throw new DBUnexpectedError( $this, "No transaction is active." );
		}
		$this->mTrxEndCallbacks[] = [ $callback, $fname ];
	}

	final public function onTransactionIdle( callable $callback, $fname = __METHOD__ ) {
		$this->mTrxIdleCallbacks[] = [ $callback, $fname ];
		if ( !$this->mTrxLevel ) {
			$this->runOnTransactionIdleCallbacks( self::TRIGGER_IDLE );
		}
	}

	final public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		if ( $this->mTrxLevel ) {
			$this->mTrxPreCommitCallbacks[] = [ $callback, $fname ];
		} else {
			// If no transaction is active, then make one for this callback
			$this->startAtomic( __METHOD__ );
			try {
				call_user_func( $callback );
				$this->endAtomic( __METHOD__ );
			} catch ( Exception $e ) {
				$this->rollback( __METHOD__, self::FLUSHING_INTERNAL );
				throw $e;
			}
		}
	}

	final public function setTransactionListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->mTrxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->mTrxRecurringCallbacks[$name] );
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
		$this->mTrxEndCallbacksSuppressed = $suppress;
	}

	/**
	 * Actually run and consume any "on transaction idle/resolution" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @param integer $trigger IDatabase::TRIGGER_* constant
	 * @since 1.20
	 * @throws Exception
	 */
	public function runOnTransactionIdleCallbacks( $trigger ) {
		if ( $this->mTrxEndCallbacksSuppressed ) {
			return;
		}

		$autoTrx = $this->getFlag( self::DBO_TRX ); // automatic begin() enabled?
		/** @var Exception $e */
		$e = null; // first exception
		do { // callbacks may add callbacks :)
			$callbacks = array_merge(
				$this->mTrxIdleCallbacks,
				$this->mTrxEndCallbacks // include "transaction resolution" callbacks
			);
			$this->mTrxIdleCallbacks = []; // consumed (and recursion guard)
			$this->mTrxEndCallbacks = []; // consumed (recursion guard)
			foreach ( $callbacks as $callback ) {
				try {
					list( $phpCallback ) = $callback;
					$this->clearFlag( self::DBO_TRX ); // make each query its own transaction
					call_user_func_array( $phpCallback, [ $trigger ] );
					if ( $autoTrx ) {
						$this->setFlag( self::DBO_TRX ); // restore automatic begin()
					} else {
						$this->clearFlag( self::DBO_TRX ); // restore auto-commit
					}
				} catch ( Exception $ex ) {
					call_user_func( $this->errorLogger, $ex );
					$e = $e ?: $ex;
					// Some callbacks may use startAtomic/endAtomic, so make sure
					// their transactions are ended so other callbacks don't fail
					if ( $this->trxLevel() ) {
						$this->rollback( __METHOD__, self::FLUSHING_INTERNAL );
					}
				}
			}
		} while ( count( $this->mTrxIdleCallbacks ) );

		if ( $e instanceof Exception ) {
			throw $e; // re-throw any first exception
		}
	}

	/**
	 * Actually run and consume any "on transaction pre-commit" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @since 1.22
	 * @throws Exception
	 */
	public function runOnTransactionPreCommitCallbacks() {
		$e = null; // first exception
		do { // callbacks may add callbacks :)
			$callbacks = $this->mTrxPreCommitCallbacks;
			$this->mTrxPreCommitCallbacks = []; // consumed (and recursion guard)
			foreach ( $callbacks as $callback ) {
				try {
					list( $phpCallback ) = $callback;
					call_user_func( $phpCallback );
				} catch ( Exception $ex ) {
					call_user_func( $this->errorLogger, $ex );
					$e = $e ?: $ex;
				}
			}
		} while ( count( $this->mTrxPreCommitCallbacks ) );

		if ( $e instanceof Exception ) {
			throw $e; // re-throw any first exception
		}
	}

	/**
	 * Actually run any "transaction listener" callbacks.
	 *
	 * This method should not be used outside of Database/LoadBalancer
	 *
	 * @param integer $trigger IDatabase::TRIGGER_* constant
	 * @throws Exception
	 * @since 1.20
	 */
	public function runTransactionListenerCallbacks( $trigger ) {
		if ( $this->mTrxEndCallbacksSuppressed ) {
			return;
		}

		/** @var Exception $e */
		$e = null; // first exception

		foreach ( $this->mTrxRecurringCallbacks as $phpCallback ) {
			try {
				$phpCallback( $trigger, $this );
			} catch ( Exception $ex ) {
				call_user_func( $this->errorLogger, $ex );
				$e = $e ?: $ex;
			}
		}

		if ( $e instanceof Exception ) {
			throw $e; // re-throw any first exception
		}
	}

	final public function startAtomic( $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			$this->begin( $fname, self::TRANSACTION_INTERNAL );
			// If DBO_TRX is set, a series of startAtomic/endAtomic pairs will result
			// in all changes being in one transaction to keep requests transactional.
			if ( !$this->getFlag( self::DBO_TRX ) ) {
				$this->mTrxAutomaticAtomic = true;
			}
		}

		$this->mTrxAtomicLevels[] = $fname;
	}

	final public function endAtomic( $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			throw new DBUnexpectedError( $this, "No atomic transaction is open (got $fname)." );
		}
		if ( !$this->mTrxAtomicLevels ||
			array_pop( $this->mTrxAtomicLevels ) !== $fname
		) {
			throw new DBUnexpectedError( $this, "Invalid atomic section ended (got $fname)." );
		}

		if ( !$this->mTrxAtomicLevels && $this->mTrxAutomaticAtomic ) {
			$this->commit( $fname, self::FLUSHING_INTERNAL );
		}
	}

	final public function doAtomicSection( $fname, callable $callback ) {
		$this->startAtomic( $fname );
		try {
			$res = call_user_func_array( $callback, [ $this, $fname ] );
		} catch ( Exception $e ) {
			$this->rollback( $fname, self::FLUSHING_INTERNAL );
			throw $e;
		}
		$this->endAtomic( $fname );

		return $res;
	}

	final public function begin( $fname = __METHOD__, $mode = self::TRANSACTION_EXPLICIT ) {
		// Protect against mismatched atomic section, transaction nesting, and snapshot loss
		if ( $this->mTrxLevel ) {
			if ( $this->mTrxAtomicLevels ) {
				$levels = implode( ', ', $this->mTrxAtomicLevels );
				$msg = "$fname: Got explicit BEGIN while atomic section(s) $levels are open.";
				throw new DBUnexpectedError( $this, $msg );
			} elseif ( !$this->mTrxAutomatic ) {
				$msg = "$fname: Explicit transaction already active (from {$this->mTrxFname}).";
				throw new DBUnexpectedError( $this, $msg );
			} else {
				// @TODO: make this an exception at some point
				$msg = "$fname: Implicit transaction already active (from {$this->mTrxFname}).";
				$this->queryLogger->error( $msg );
				return; // join the main transaction set
			}
		} elseif ( $this->getFlag( self::DBO_TRX ) && $mode !== self::TRANSACTION_INTERNAL ) {
			// @TODO: make this an exception at some point
			$msg = "$fname: Implicit transaction expected (DBO_TRX set).";
			$this->queryLogger->error( $msg );
			return; // let any writes be in the main transaction
		}

		// Avoid fatals if close() was called
		$this->assertOpen();

		$this->doBegin( $fname );
		$this->mTrxTimestamp = microtime( true );
		$this->mTrxFname = $fname;
		$this->mTrxDoneWrites = false;
		$this->mTrxAutomaticAtomic = false;
		$this->mTrxAtomicLevels = [];
		$this->mTrxShortId = sprintf( '%06x', mt_rand( 0, 0xffffff ) );
		$this->mTrxWriteDuration = 0.0;
		$this->mTrxWriteQueryCount = 0;
		$this->mTrxWriteAdjDuration = 0.0;
		$this->mTrxWriteAdjQueryCount = 0;
		$this->mTrxWriteCallers = [];
		// First SELECT after BEGIN will establish the snapshot in REPEATABLE-READ.
		// Get an estimate of the replica DB lag before then, treating estimate staleness
		// as lag itself just to be safe
		$status = $this->getApproximateLagStatus();
		$this->mTrxReplicaLag = $status['lag'] + ( microtime( true ) - $status['since'] );
		// T147697: make explicitTrxActive() return true until begin() finishes. This way, no
		// caller will think its OK to muck around with the transaction just because startAtomic()
		// has not yet completed (e.g. setting mTrxAtomicLevels).
		$this->mTrxAutomatic = ( $mode === self::TRANSACTION_INTERNAL );
	}

	/**
	 * Issues the BEGIN command to the database server.
	 *
	 * @see Database::begin()
	 * @param string $fname
	 */
	protected function doBegin( $fname ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}

	final public function commit( $fname = __METHOD__, $flush = '' ) {
		if ( $this->mTrxLevel && $this->mTrxAtomicLevels ) {
			// There are still atomic sections open. This cannot be ignored
			$levels = implode( ', ', $this->mTrxAtomicLevels );
			throw new DBUnexpectedError(
				$this,
				"$fname: Got COMMIT while atomic sections $levels are still open."
			);
		}

		if ( $flush === self::FLUSHING_INTERNAL || $flush === self::FLUSHING_ALL_PEERS ) {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			} elseif ( !$this->mTrxAutomatic ) {
				throw new DBUnexpectedError(
					$this,
					"$fname: Flushing an explicit transaction, getting out of sync."
				);
			}
		} else {
			if ( !$this->mTrxLevel ) {
				$this->queryLogger->error(
					"$fname: No transaction to commit, something got out of sync." );
				return; // nothing to do
			} elseif ( $this->mTrxAutomatic ) {
				// @TODO: make this an exception at some point
				$msg = "$fname: Explicit commit of implicit transaction.";
				$this->queryLogger->error( $msg );
				return; // wait for the main transaction set commit round
			}
		}

		// Avoid fatals if close() was called
		$this->assertOpen();

		$this->runOnTransactionPreCommitCallbacks();
		$writeTime = $this->pendingWriteQueryDuration( self::ESTIMATE_DB_APPLY );
		$this->doCommit( $fname );
		if ( $this->mTrxDoneWrites ) {
			$this->mLastWriteTime = microtime( true );
			$this->trxProfiler->transactionWritingOut(
				$this->mServer, $this->mDBname, $this->mTrxShortId, $writeTime );
		}

		$this->runOnTransactionIdleCallbacks( self::TRIGGER_COMMIT );
		$this->runTransactionListenerCallbacks( self::TRIGGER_COMMIT );
	}

	/**
	 * Issues the COMMIT command to the database server.
	 *
	 * @see Database::commit()
	 * @param string $fname
	 */
	protected function doCommit( $fname ) {
		if ( $this->mTrxLevel ) {
			$this->query( 'COMMIT', $fname );
			$this->mTrxLevel = 0;
		}
	}

	final public function rollback( $fname = __METHOD__, $flush = '' ) {
		if ( $flush === self::FLUSHING_INTERNAL || $flush === self::FLUSHING_ALL_PEERS ) {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			}
		} else {
			if ( !$this->mTrxLevel ) {
				$this->queryLogger->error(
					"$fname: No transaction to rollback, something got out of sync." );
				return; // nothing to do
			} elseif ( $this->getFlag( self::DBO_TRX ) ) {
				throw new DBUnexpectedError(
					$this,
					"$fname: Expected mass rollback of all peer databases (DBO_TRX set)."
				);
			}
		}

		// Avoid fatals if close() was called
		$this->assertOpen();

		$this->doRollback( $fname );
		$this->mTrxAtomicLevels = [];
		if ( $this->mTrxDoneWrites ) {
			$this->trxProfiler->transactionWritingOut(
				$this->mServer, $this->mDBname, $this->mTrxShortId );
		}

		$this->mTrxIdleCallbacks = []; // clear
		$this->mTrxPreCommitCallbacks = []; // clear
		$this->runOnTransactionIdleCallbacks( self::TRIGGER_ROLLBACK );
		$this->runTransactionListenerCallbacks( self::TRIGGER_ROLLBACK );
	}

	/**
	 * Issues the ROLLBACK command to the database server.
	 *
	 * @see Database::rollback()
	 * @param string $fname
	 */
	protected function doRollback( $fname ) {
		if ( $this->mTrxLevel ) {
			# Disconnects cause rollback anyway, so ignore those errors
			$ignoreErrors = true;
			$this->query( 'ROLLBACK', $fname, $ignoreErrors );
			$this->mTrxLevel = 0;
		}
	}

	public function flushSnapshot( $fname = __METHOD__ ) {
		if ( $this->writesOrCallbacksPending() || $this->explicitTrxActive() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$this,
				"$fname: Cannot flush snapshot because writes are pending ($fnames)."
			);
		}

		$this->commit( $fname, self::FLUSHING_INTERNAL );
	}

	public function explicitTrxActive() {
		return $this->mTrxLevel && ( $this->mTrxAtomicLevels || !$this->mTrxAutomatic );
	}

	/**
	 * Creates a new table with structure copied from existing table
	 * Note that unlike most database abstraction functions, this function does not
	 * automatically append database prefix, because it works at a lower
	 * abstraction level.
	 * The table names passed to this function shall not be quoted (this
	 * function calls addIdentifierQuotes when needed).
	 *
	 * @param string $oldName Name of table whose structure should be copied
	 * @param string $newName Name of table to be created
	 * @param bool $temporary Whether the new table should be temporary
	 * @param string $fname Calling function name
	 * @throws RuntimeException
	 * @return bool True if operation was successful
	 */
	public function duplicateTableStructure( $oldName, $newName, $temporary = false,
		$fname = __METHOD__
	) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		throw new RuntimeException( __METHOD__ . ' is not implemented in descendant class' );
	}

	public function timestamp( $ts = 0 ) {
		$t = new ConvertibleTimestamp( $ts );
		// Let errors bubble up to avoid putting garbage in the DB
		return $t->getTimestamp( TS_MW );
	}

	public function timestampOrNull( $ts = null ) {
		if ( is_null( $ts ) ) {
			return null;
		} else {
			return $this->timestamp( $ts );
		}
	}

	/**
	 * Take the result from a query, and wrap it in a ResultWrapper if
	 * necessary. Boolean values are passed through as is, to indicate success
	 * of write queries or failure.
	 *
	 * Once upon a time, Database::query() returned a bare MySQL result
	 * resource, and it was necessary to call this function to convert it to
	 * a wrapper. Nowadays, raw database objects are never exposed to external
	 * callers, so this is unnecessary in external code.
	 *
	 * @param bool|ResultWrapper|resource|object $result
	 * @return bool|ResultWrapper
	 */
	protected function resultObject( $result ) {
		if ( !$result ) {
			return false;
		} elseif ( $result instanceof ResultWrapper ) {
			return $result;
		} elseif ( $result === true ) {
			// Successful write query
			return $result;
		} else {
			return new ResultWrapper( $this, $result );
		}
	}

	public function ping( &$rtt = null ) {
		// Avoid hitting the server if it was hit recently
		if ( $this->isOpen() && ( microtime( true ) - $this->lastPing ) < self::PING_TTL ) {
			if ( !func_num_args() || $this->mRTTEstimate > 0 ) {
				$rtt = $this->mRTTEstimate;
				return true; // don't care about $rtt
			}
		}

		// This will reconnect if possible or return false if not
		$this->clearFlag( self::DBO_TRX, self::REMEMBER_PRIOR );
		$ok = ( $this->query( self::PING_QUERY, __METHOD__, true ) !== false );
		$this->restoreFlags( self::RESTORE_PRIOR );

		if ( $ok ) {
			$rtt = $this->mRTTEstimate;
		}

		return $ok;
	}

	/**
	 * @return bool
	 */
	protected function reconnect() {
		$this->closeConnection();
		$this->mOpened = false;
		$this->mConn = false;
		try {
			$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );
			$this->lastPing = microtime( true );
			$ok = true;
		} catch ( DBConnectionError $e ) {
			$ok = false;
		}

		return $ok;
	}

	public function getSessionLagStatus() {
		return $this->getTransactionLagStatus() ?: $this->getApproximateLagStatus();
	}

	/**
	 * Get the replica DB lag when the current transaction started
	 *
	 * This is useful when transactions might use snapshot isolation
	 * (e.g. REPEATABLE-READ in innodb), so the "real" lag of that data
	 * is this lag plus transaction duration. If they don't, it is still
	 * safe to be pessimistic. This returns null if there is no transaction.
	 *
	 * @return array|null ('lag': seconds or false on error, 'since': UNIX timestamp of BEGIN)
	 * @since 1.27
	 */
	protected function getTransactionLagStatus() {
		return $this->mTrxLevel
			? [ 'lag' => $this->mTrxReplicaLag, 'since' => $this->trxTimestamp() ]
			: null;
	}

	/**
	 * Get a replica DB lag estimate for this server
	 *
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of estimate)
	 * @since 1.27
	 */
	protected function getApproximateLagStatus() {
		return [
			'lag'   => $this->getLBInfo( 'replica' ) ? $this->getLag() : 0,
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
	 * @param IDatabase ...
	 * @return array Map of values:
	 *   - lag: highest lag of any of the DBs or false on error (e.g. replication stopped)
	 *   - since: oldest UNIX timestamp of any of the DB lag estimates
	 *   - pending: whether any of the DBs have uncommitted changes
	 * @since 1.27
	 */
	public static function getCacheSetOptions( IDatabase $db1 ) {
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
		return 0;
	}

	public function maxListLen() {
		return 0;
	}

	public function encodeBlob( $b ) {
		return $b;
	}

	public function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}
		return $b;
	}

	public function setSessionOptions( array $options ) {
	}

	public function sourceFile(
		$filename,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = false,
		callable $inputCallback = null
	) {
		MediaWiki\suppressWarnings();
		$fp = fopen( $filename, 'r' );
		MediaWiki\restoreWarnings();

		if ( false === $fp ) {
			throw new RuntimeException( "Could not open \"{$filename}\".\n" );
		}

		if ( !$fname ) {
			$fname = __METHOD__ . "( $filename )";
		}

		try {
			$error = $this->sourceStream(
				$fp, $lineCallback, $resultCallback, $fname, $inputCallback );
		} catch ( Exception $e ) {
			fclose( $fp );
			throw $e;
		}

		fclose( $fp );

		return $error;
	}

	public function setSchemaVars( $vars ) {
		$this->mSchemaVars = $vars;
	}

	public function sourceStream(
		$fp,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = __METHOD__,
		callable $inputCallback = null
	) {
		$cmd = '';

		while ( !feof( $fp ) ) {
			if ( $lineCallback ) {
				call_user_func( $lineCallback );
			}

			$line = trim( fgets( $fp ) );

			if ( $line == '' ) {
				continue;
			}

			if ( '-' == $line[0] && '-' == $line[1] ) {
				continue;
			}

			if ( $cmd != '' ) {
				$cmd .= ' ';
			}

			$done = $this->streamStatementEnd( $cmd, $line );

			$cmd .= "$line\n";

			if ( $done || feof( $fp ) ) {
				$cmd = $this->replaceVars( $cmd );

				if ( !$inputCallback || call_user_func( $inputCallback, $cmd ) ) {
					$res = $this->query( $cmd, $fname );

					if ( $resultCallback ) {
						call_user_func( $resultCallback, $res, $this );
					}

					if ( false === $res ) {
						$err = $this->lastError();

						return "Query \"{$cmd}\" failed with error code \"$err\".\n";
					}
				}
				$cmd = '';
			}
		}

		return true;
	}

	/**
	 * Called by sourceStream() to check if we've reached a statement end
	 *
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
		if ( $this->mSchemaVars ) {
			return $this->mSchemaVars;
		} else {
			return $this->getDefaultSchemaVars();
		}
	}

	/**
	 * Get schema variables to use if none have been set via setSchemaVars().
	 *
	 * Override this in derived classes to provide variables for tables.sql
	 * and SQL patch files.
	 *
	 * @return array
	 */
	protected function getDefaultSchemaVars() {
		return [];
	}

	public function lockIsFree( $lockName, $method ) {
		return true;
	}

	public function lock( $lockName, $method, $timeout = 5 ) {
		$this->mNamedLocksHeld[$lockName] = 1;

		return true;
	}

	public function unlock( $lockName, $method ) {
		unset( $this->mNamedLocksHeld[$lockName] );

		return true;
	}

	public function getScopedLockAndFlush( $lockKey, $fname, $timeout ) {
		if ( $this->writesOrCallbacksPending() ) {
			// This only flushes transactions to clear snapshots, not to write data
			$fnames = implode( ', ', $this->pendingWriteAndCallbackCallers() );
			throw new DBUnexpectedError(
				$this,
				"$fname: Cannot flush pre-lock snapshot because writes are pending ($fnames)."
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

	public function namedLocksEnqueue() {
		return false;
	}

	/**
	 * Lock specific tables
	 *
	 * @param array $read Array of tables to lock for read access
	 * @param array $write Array of tables to lock for write access
	 * @param string $method Name of caller
	 * @param bool $lowPriority Whether to indicate writes to be LOW PRIORITY
	 * @return bool
	 */
	public function lockTables( $read, $write, $method, $lowPriority = true ) {
		return true;
	}

	/**
	 * Unlock specific tables
	 *
	 * @param string $method The caller
	 * @return bool
	 */
	public function unlockTables( $method ) {
		return true;
	}

	/**
	 * Delete a table
	 * @param string $tableName
	 * @param string $fName
	 * @return bool|ResultWrapper
	 * @since 1.18
	 */
	public function dropTable( $tableName, $fName = __METHOD__ ) {
		if ( !$this->tableExists( $tableName, $fName ) ) {
			return false;
		}
		$sql = "DROP TABLE " . $this->tableName( $tableName ) . " CASCADE";

		return $this->query( $sql, $fName );
	}

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

	public function setBigSelects( $value = true ) {
		// no-op
	}

	public function isReadOnly() {
		return ( $this->getReadOnlyReason() !== false );
	}

	/**
	 * @return string|bool Reason this DB is read-only or false if it is not
	 */
	protected function getReadOnlyReason() {
		$reason = $this->getLBInfo( 'readOnlyReason' );

		return is_string( $reason ) ? $reason : false;
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	/**
	 * @return bool Whether a DB user is required to access the DB
	 * @since 1.28
	 */
	protected function requiresDatabaseUser() {
		return true;
	}

	/**
	 * Get the underlying binding handle, mConn
	 *
	 * Makes sure that mConn is set (disconnects and ping() failure can unset it).
	 * This catches broken callers than catch and ignore disconnection exceptions.
	 * Unlike checking isOpen(), this is safe to call inside of open().
	 *
	 * @return resource|object
	 * @throws DBUnexpectedError
	 * @since 1.26
	 */
	protected function getBindingHandle() {
		if ( !$this->mConn ) {
			throw new DBUnexpectedError(
				$this,
				'DB connection was already closed or the connection dropped.'
			);
		}

		return $this->mConn;
	}

	/**
	 * @since 1.19
	 * @return string
	 */
	public function __toString() {
		return (string)$this->mConn;
	}

	/**
	 * Make sure that copies do not share the same client binding handle
	 * @throws DBConnectionError
	 */
	public function __clone() {
		$this->connLogger->warning(
			"Cloning " . get_class( $this ) . " is not recomended; forking connection:\n" .
			( new RuntimeException() )->getTraceAsString()
		);

		if ( $this->isOpen() ) {
			// Open a new connection resource without messing with the old one
			$this->mOpened = false;
			$this->mConn = false;
			$this->mTrxEndCallbacks = []; // don't copy
			$this->handleSessionLoss(); // no trx or locks anymore
			$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );
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
			'the connection is not restored on wakeup.' );
	}

	/**
	 * Run a few simple sanity checks and close dangling connections
	 */
	public function __destruct() {
		if ( $this->mTrxLevel && $this->mTrxDoneWrites ) {
			trigger_error( "Uncommitted DB writes (transaction from {$this->mTrxFname})." );
		}

		$danglingWriters = $this->pendingWriteAndCallbackCallers();
		if ( $danglingWriters ) {
			$fnames = implode( ', ', $danglingWriters );
			trigger_error( "DB transaction writes or callbacks still pending ($fnames)." );
		}

		if ( $this->mConn ) {
			// Avoid connection leaks for sanity. Normally, resources close at script completion.
			// The connection might already be closed in zend/hhvm by now, so suppress warnings.
			\MediaWiki\suppressWarnings();
			$this->closeConnection();
			\MediaWiki\restoreWarnings();
			$this->mConn = false;
			$this->mOpened = false;
		}
	}

	/**
	 * @deprecated since 1.28 use SearchEngineFactory::getSearchEngineClass instead
	 * @return string
	 */
	public function getSearchEngine() {
		wfDeprecated( __METHOD__, '1.28' );
		return SearchEngineFactory::getSearchEngineClass( $this );
	}
}

class_alias( 'Database', 'DatabaseBase' );
