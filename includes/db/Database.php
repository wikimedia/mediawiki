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

/**
 * Database abstraction object
 * @ingroup Database
 */
abstract class DatabaseBase implements IDatabase {
	/** Number of times to re-try an operation in case of deadlock */
	const DEADLOCK_TRIES = 4;

	/** Minimum time to wait before retry, in microseconds */
	const DEADLOCK_DELAY_MIN = 500000;

	/** Maximum time to wait before retry */
	const DEADLOCK_DELAY_MAX = 1500000;

	protected $mLastQuery = '';
	protected $mDoneWrites = false;
	protected $mPHPError = false;

	protected $mServer, $mUser, $mPassword, $mDBname;

	/** @var BagOStuff APC cache */
	protected $srvCache;

	/** @var resource Database connection */
	protected $mConn = null;
	protected $mOpened = false;

	/** @var callable[] */
	protected $mTrxIdleCallbacks = [];
	/** @var callable[] */
	protected $mTrxPreCommitCallbacks = [];

	protected $mTablePrefix;
	protected $mSchema;
	protected $mFlags;
	protected $mForeign;
	protected $mLBInfo = [];
	protected $mDefaultBigSelects = null;
	protected $mSchemaVars = false;
	/** @var array */
	protected $mSessionVars = [];

	protected $preparedArgs;

	protected $htmlErrors;

	protected $delimiter = ';';

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
	 * @see DatabaseBase::mTrxLevel
	 */
	protected $mTrxShortId = '';

	/**
	 * The UNIX time that the transaction started. Callers can assume that if
	 * snapshot isolation is used, then the data is *at least* up to date to that
	 * point (possibly more up-to-date since the first SELECT defines the snapshot).
	 *
	 * @var float|null
	 * @see DatabaseBase::mTrxLevel
	 */
	private $mTrxTimestamp = null;

	/** @var float Lag estimate at the time of BEGIN */
	private $mTrxSlaveLag = null;

	/**
	 * Remembers the function name given for starting the most recent transaction via begin().
	 * Used to provide additional context for error reporting.
	 *
	 * @var string
	 * @see DatabaseBase::mTrxLevel
	 */
	private $mTrxFname = null;

	/**
	 * Record if possible write queries were done in the last transaction started
	 *
	 * @var bool
	 * @see DatabaseBase::mTrxLevel
	 */
	private $mTrxDoneWrites = false;

	/**
	 * Record if the current transaction was started implicitly due to DBO_TRX being set.
	 *
	 * @var bool
	 * @see DatabaseBase::mTrxLevel
	 */
	private $mTrxAutomatic = false;

	/**
	 * Array of levels of atomicity within transactions
	 *
	 * @var array
	 */
	private $mTrxAtomicLevels = [];

	/**
	 * Record if the current transaction was started implicitly by DatabaseBase::startAtomic
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
	 * Track the seconds spent in write queries for the current transaction
	 *
	 * @var float
	 */
	private $mTrxWriteDuration = 0.0;

	/** @var array Map of (name => 1) for locks obtained via lock() */
	private $mNamedLocksHeld = [];

	/** @var IDatabase|null Lazy handle to the master DB this server replicates from */
	private $lazyMasterHandle;

	/**
	 * @since 1.21
	 * @var resource File handle for upgrade
	 */
	protected $fileHandle = null;

	/**
	 * @since 1.22
	 * @var string[] Process cache of VIEWs names in the database
	 */
	protected $allViews = null;

	/** @var TransactionProfiler */
	protected $trxProfiler;

	public function getServerInfo() {
		return $this->getServerVersion();
	}

	/**
	 * @return string Command delimiter used by this database engine
	 */
	public function getDelimiter() {
		return $this->delimiter;
	}

	/**
	 * Boolean, controls output of large amounts of debug information.
	 * @param bool|null $debug
	 *   - true to enable debugging
	 *   - false to disable debugging
	 *   - omitted or null to do nothing
	 *
	 * @return bool|null Previous value of the flag
	 */
	public function debug( $debug = null ) {
		return wfSetBit( $this->mFlags, DBO_DEBUG, $debug );
	}

	public function bufferResults( $buffer = null ) {
		if ( is_null( $buffer ) ) {
			return !(bool)( $this->mFlags & DBO_NOBUFFER );
		} else {
			return !wfSetBit( $this->mFlags, DBO_NOBUFFER, !$buffer );
		}
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
		return wfSetBit( $this->mFlags, DBO_IGNORE, $ignoreErrors );
	}

	public function trxLevel() {
		return $this->mTrxLevel;
	}

	public function trxTimestamp() {
		return $this->mTrxLevel ? $this->mTrxTimestamp : null;
	}

	public function tablePrefix( $prefix = null ) {
		return wfSetVar( $this->mTablePrefix, $prefix );
	}

	public function dbSchema( $schema = null ) {
		return wfSetVar( $this->mSchema, $schema );
	}

	/**
	 * Set the filehandle to copy write statements to.
	 *
	 * @param resource $fh File handle
	 */
	public function setFileHandle( $fh ) {
		$this->fileHandle = $fh;
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

	/**
	 * Set a lazy-connecting DB handle to the master DB (for replication status purposes)
	 *
	 * @param IDatabase $conn
	 * @since 1.27
	 */
	public function setLazyMasterHandle( IDatabase $conn ) {
		$this->lazyMasterHandle = $conn;
	}

	/**
	 * @return IDatabase|null
	 * @see setLazyMasterHandle()
	 * @since 1.27
	 */
	public function getLazyMasterHandle() {
		return $this->lazyMasterHandle;
	}

	/**
	 * @return TransactionProfiler
	 */
	protected function getTransactionProfiler() {
		if ( !$this->trxProfiler ) {
			$this->trxProfiler = new TransactionProfiler();
		}

		return $this->trxProfiler;
	}

	/**
	 * @param TransactionProfiler $profiler
	 * @since 1.27
	 */
	public function setTransactionProfiler( TransactionProfiler $profiler ) {
		$this->trxProfiler = $profiler;
	}

	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 *
	 * @return bool
	 */
	public function cascadingDeletes() {
		return false;
	}

	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the page table)
	 *
	 * @return bool
	 */
	public function cleanupTriggers() {
		return false;
	}

	/**
	 * Returns true if this database is strict about what can be put into an IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 *
	 * @return bool
	 */
	public function strictIPs() {
		return false;
	}

	/**
	 * Returns true if this database uses timestamps rather than integers
	 *
	 * @return bool
	 */
	public function realTimestamps() {
		return false;
	}

	public function implicitGroupby() {
		return true;
	}

	public function implicitOrderby() {
		return true;
	}

	/**
	 * Returns true if this database can do a native search on IP columns
	 * e.g. this works as expected: .. WHERE rc_ip = '127.42.12.102/32';
	 *
	 * @return bool
	 */
	public function searchableIPs() {
		return false;
	}

	/**
	 * Returns true if this database can use functional indexes
	 *
	 * @return bool
	 */
	public function functionalIndexes() {
		return false;
	}

	public function lastQuery() {
		return $this->mLastQuery;
	}

	public function doneWrites() {
		return (bool)$this->mDoneWrites;
	}

	public function lastDoneWrites() {
		return $this->mDoneWrites ?: false;
	}

	public function writesPending() {
		return $this->mTrxLevel && $this->mTrxDoneWrites;
	}

	public function writesOrCallbacksPending() {
		return $this->mTrxLevel && (
			$this->mTrxDoneWrites || $this->mTrxIdleCallbacks || $this->mTrxPreCommitCallbacks
		);
	}

	public function pendingWriteQueryDuration() {
		return $this->mTrxLevel ? $this->mTrxWriteDuration : false;
	}

	public function pendingWriteCallers() {
		return $this->mTrxLevel ? $this->mTrxWriteCallers : [];
	}

	public function isOpen() {
		return $this->mOpened;
	}

	public function setFlag( $flag ) {
		$this->mFlags |= $flag;
	}

	public function clearFlag( $flag ) {
		$this->mFlags &= ~$flag;
	}

	public function getFlag( $flag ) {
		return !!( $this->mFlags & $flag );
	}

	public function getProperty( $name ) {
		return $this->$name;
	}

	public function getWikiID() {
		if ( $this->mTablePrefix ) {
			return "{$this->mDBname}-{$this->mTablePrefix}";
		} else {
			return $this->mDBname;
		}
	}

	/**
	 * Return a path to the DBMS-specific SQL file if it exists,
	 * otherwise default SQL file
	 *
	 * @param string $filename
	 * @return string
	 */
	private function getSqlFilePath( $filename ) {
		global $IP;
		$dbmsSpecificFilePath = "$IP/maintenance/" . $this->getType() . "/$filename";
		if ( file_exists( $dbmsSpecificFilePath ) ) {
			return $dbmsSpecificFilePath;
		} else {
			return "$IP/maintenance/$filename";
		}
	}

	/**
	 * Return a path to the DBMS-specific schema file,
	 * otherwise default to tables.sql
	 *
	 * @return string
	 */
	public function getSchemaPath() {
		return $this->getSqlFilePath( 'tables.sql' );
	}

	/**
	 * Return a path to the DBMS-specific update key file,
	 * otherwise default to update-keys.sql
	 *
	 * @return string
	 */
	public function getUpdateKeysPath() {
		return $this->getSqlFilePath( 'update-keys.sql' );
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

	/**
	 * Constructor.
	 *
	 * FIXME: It is possible to construct a Database object with no associated
	 * connection object, by specifying no parameters to __construct(). This
	 * feature is deprecated and should be removed.
	 *
	 * DatabaseBase subclasses should not be constructed directly in external
	 * code. DatabaseBase::factory() should be used instead.
	 *
	 * @param array $params Parameters passed from DatabaseBase::factory()
	 */
	function __construct( array $params ) {
		global $wgDBprefix, $wgDBmwschema, $wgCommandLineMode;

		$this->srvCache = ObjectCache::getLocalServerInstance( 'hash' );

		$server = $params['host'];
		$user = $params['user'];
		$password = $params['password'];
		$dbName = $params['dbname'];
		$flags = $params['flags'];
		$tablePrefix = $params['tablePrefix'];
		$schema = $params['schema'];
		$foreign = $params['foreign'];

		$this->mFlags = $flags;
		if ( $this->mFlags & DBO_DEFAULT ) {
			if ( $wgCommandLineMode ) {
				$this->mFlags &= ~DBO_TRX;
			} else {
				$this->mFlags |= DBO_TRX;
			}
		}

		$this->mSessionVars = $params['variables'];

		/** Get the default table prefix*/
		if ( $tablePrefix === 'get from global' ) {
			$this->mTablePrefix = $wgDBprefix;
		} else {
			$this->mTablePrefix = $tablePrefix;
		}

		/** Get the database schema*/
		if ( $schema === 'get from global' ) {
			$this->mSchema = $wgDBmwschema;
		} else {
			$this->mSchema = $schema;
		}

		$this->mForeign = $foreign;

		if ( isset( $params['trxProfiler'] ) ) {
			$this->trxProfiler = $params['trxProfiler']; // override
		}

		if ( $user ) {
			$this->open( $server, $user, $password, $dbName );
		}
	}

	/**
	 * Called by serialize. Throw an exception when DB connection is serialized.
	 * This causes problems on some database engines because the connection is
	 * not restored on unserialize.
	 */
	public function __sleep() {
		throw new MWException( 'Database serialization may cause problems, since ' .
			'the connection is not restored on wakeup.' );
	}

	/**
	 * Given a DB type, construct the name of the appropriate child class of
	 * DatabaseBase. This is designed to replace all of the manual stuff like:
	 *    $class = 'Database' . ucfirst( strtolower( $dbType ) );
	 * as well as validate against the canonical list of DB types we have
	 *
	 * This factory function is mostly useful for when you need to connect to a
	 * database other than the MediaWiki default (such as for external auth,
	 * an extension, et cetera). Do not use this to connect to the MediaWiki
	 * database. Example uses in core:
	 * @see LoadBalancer::reallyOpenConnection()
	 * @see ForeignDBRepo::getMasterDB()
	 * @see WebInstallerDBConnect::execute()
	 *
	 * @since 1.18
	 *
	 * @param string $dbType A possible DB type
	 * @param array $p An array of options to pass to the constructor.
	 *    Valid options are: host, user, password, dbname, flags, tablePrefix, schema, driver
	 * @throws MWException If the database driver or extension cannot be found
	 * @return DatabaseBase|null DatabaseBase subclass or null
	 */
	final public static function factory( $dbType, $p = [] ) {
		$canonicalDBTypes = [
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
					throw new MWException( __METHOD__ .
						" cannot construct Database with type '$dbType' and driver '{$p['driver']}'" );
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
		if ( $driver === false ) {
			throw new MWException( __METHOD__ .
				" no viable database extension found for type '$dbType'" );
		}

		// Determine schema defaults. Currently Microsoft SQL Server uses $wgDBmwschema,
		// and everything else doesn't use a schema (e.g. null)
		// Although postgres and oracle support schemas, we don't use them (yet)
		// to maintain backwards compatibility
		$defaultSchemas = [
			'mssql' => 'get from global',
		];

		$class = 'Database' . ucfirst( $driver );
		if ( class_exists( $class ) && is_subclass_of( $class, 'DatabaseBase' ) ) {
			// Resolve some defaults for b/c
			$p['host'] = isset( $p['host'] ) ? $p['host'] : false;
			$p['user'] = isset( $p['user'] ) ? $p['user'] : false;
			$p['password'] = isset( $p['password'] ) ? $p['password'] : false;
			$p['dbname'] = isset( $p['dbname'] ) ? $p['dbname'] : false;
			$p['flags'] = isset( $p['flags'] ) ? $p['flags'] : 0;
			$p['variables'] = isset( $p['variables'] ) ? $p['variables'] : [];
			$p['tablePrefix'] = isset( $p['tablePrefix'] ) ? $p['tablePrefix'] : 'get from global';
			if ( !isset( $p['schema'] ) ) {
				$p['schema'] = isset( $defaultSchemas[$dbType] ) ? $defaultSchemas[$dbType] : null;
			}
			$p['foreign'] = isset( $p['foreign'] ) ? $p['foreign'] : false;

			return new $class( $p );
		} else {
			return null;
		}
	}

	protected function installErrorHandler() {
		$this->mPHPError = false;
		$this->htmlErrors = ini_set( 'html_errors', '0' );
		set_error_handler( [ $this, 'connectionErrorHandler' ] );
	}

	/**
	 * @return bool|string
	 */
	protected function restoreErrorHandler() {
		restore_error_handler();
		if ( $this->htmlErrors !== false ) {
			ini_set( 'html_errors', $this->htmlErrors );
		}
		if ( $this->mPHPError ) {
			$error = preg_replace( '!\[<a.*</a>\]!', '', $this->mPHPError );
			$error = preg_replace( '!^.*?:\s?(.*)$!', '$1', $error );

			return $error;
		} else {
			return false;
		}
	}

	/**
	 * @param int $errno
	 * @param string $errstr
	 */
	public function connectionErrorHandler( $errno, $errstr ) {
		$this->mPHPError = $errstr;
	}

	/**
	 * Create a log context to pass to wfLogDBError or other logging functions.
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
		if ( count( $this->mTrxIdleCallbacks ) ) { // sanity
			throw new MWException( "Transaction idle callbacks still pending." );
		}
		if ( $this->mConn ) {
			if ( $this->trxLevel() ) {
				if ( !$this->mTrxAutomatic ) {
					wfWarn( "Transaction still in progress (from {$this->mTrxFname}), " .
						" performing implicit commit before closing connection!" );
				}

				$this->commit( __METHOD__, 'flush' );
			}

			$closed = $this->closeConnection();
			$this->mConn = false;
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

	function reportConnectionError( $error = 'Unknown error' ) {
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
		return !preg_match( '/^(?:SELECT|BEGIN|ROLLBACK|COMMIT|SET|SHOW|EXPLAIN|\(SELECT)\b/i', $sql );
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
		$verb = substr( $sql, 0, strcspn( $sql, " \t\r\n" ) );
		return !in_array( $verb, [ 'BEGIN', 'COMMIT', 'ROLLBACK', 'SHOW', 'SET' ] );
	}

	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		global $wgUser;

		$this->mLastQuery = $sql;

		$isWriteQuery = $this->isWriteQuery( $sql );
		if ( $isWriteQuery ) {
			$reason = $this->getReadOnlyReason();
			if ( $reason !== false ) {
				throw new DBReadOnlyError( $this, "Database is read-only: $reason" );
			}
			# Set a flag indicating that writes have been done
			$this->mDoneWrites = microtime( true );
		}

		# Add a comment for easy SHOW PROCESSLIST interpretation
		if ( is_object( $wgUser ) && $wgUser->isItemLoaded( 'name' ) ) {
			$userName = $wgUser->getName();
			if ( mb_strlen( $userName ) > 15 ) {
				$userName = mb_substr( $userName, 0, 15 ) . '...';
			}
			$userName = str_replace( '/', '', $userName );
		} else {
			$userName = '';
		}

		// Add trace comment to the begin of the sql string, right after the operator.
		// Or, for one-word queries (like "BEGIN" or COMMIT") add it to the end (bug 42598)
		$commentedSql = preg_replace( '/\s|$/', " /* $fname $userName */ ", $sql, 1 );

		if ( !$this->mTrxLevel && $this->getFlag( DBO_TRX ) && $this->isTransactableQuery( $sql ) ) {
			$this->begin( __METHOD__ . " ($fname)" );
			$this->mTrxAutomatic = true;
		}

		# Keep track of whether the transaction has write queries pending
		if ( $this->mTrxLevel && !$this->mTrxDoneWrites && $isWriteQuery ) {
			$this->mTrxDoneWrites = true;
			$this->getTransactionProfiler()->transactionWritingIn(
				$this->mServer, $this->mDBname, $this->mTrxShortId );
		}

		$isMaster = !is_null( $this->getLBInfo( 'master' ) );
		# generalizeSQL will probably cut down the query to reasonable
		# logging size most of the time. The substr is really just a sanity check.
		if ( $isMaster ) {
			$queryProf = 'query-m: ' . substr( DatabaseBase::generalizeSQL( $sql ), 0, 255 );
			$totalProf = 'DatabaseBase::query-master';
		} else {
			$queryProf = 'query: ' . substr( DatabaseBase::generalizeSQL( $sql ), 0, 255 );
			$totalProf = 'DatabaseBase::query';
		}
		# Include query transaction state
		$queryProf .= $this->mTrxShortId ? " [TRX#{$this->mTrxShortId}]" : "";

		$profiler = Profiler::instance();
		if ( !$profiler instanceof ProfilerStub ) {
			$totalProfSection = $profiler->scopedProfileIn( $totalProf );
			$queryProfSection = $profiler->scopedProfileIn( $queryProf );
		}

		if ( $this->debug() ) {
			wfDebugLog( 'queries', sprintf( "%s: %s", $this->mDBname, $commentedSql ) );
		}

		$queryId = MWDebug::query( $sql, $fname, $isMaster );

		# Avoid fatals if close() was called
		$this->assertOpen();

		# Do the query and handle errors
		$startTime = microtime( true );
		$ret = $this->doQuery( $commentedSql );
		$queryRuntime = microtime( true ) - $startTime;
		# Log the query time and feed it into the DB trx profiler
		$this->getTransactionProfiler()->recordQueryCompletion(
			$queryProf, $startTime, $isWriteQuery, $this->affectedRows() );

		MWDebug::queryTime( $queryId );

		# Try reconnecting if the connection was lost
		if ( false === $ret && $this->wasErrorReissuable() ) {
			# Transaction is gone; this can mean lost writes or REPEATABLE-READ snapshots
			$hadTrx = $this->mTrxLevel;
			# T127428: for non-write transactions, a disconnect and a COMMIT are similar:
			# neither changed data and in both cases any read snapshots are reset anyway.
			$isNoopCommit = ( !$this->writesOrCallbacksPending() && $sql === 'COMMIT' );
			# Update state tracking to reflect transaction loss
			$this->mTrxLevel = 0;
			$this->mTrxIdleCallbacks = []; // bug 65263
			$this->mTrxPreCommitCallbacks = []; // bug 65263
			wfDebug( "Connection lost, reconnecting...\n" );
			# Stash the last error values since ping() might clear them
			$lastError = $this->lastError();
			$lastErrno = $this->lastErrno();
			if ( $this->ping() ) {
				wfDebug( "Reconnected\n" );
				$server = $this->getServer();
				$msg = __METHOD__ . ": lost connection to $server; reconnected";
				wfDebugLog( 'DBPerformance', "$msg:\n" . wfBacktrace( true ) );

				if ( ( $hadTrx && !$isNoopCommit ) || $this->mNamedLocksHeld ) {
					# Leave $ret as false and let an error be reported.
					# Callers may catch the exception and continue to use the DB.
					$this->reportQueryError( $lastError, $lastErrno, $sql, $fname, $tempIgnore );
				} else {
					# Should be safe to silently retry (no trx/callbacks/locks)
					$startTime = microtime( true );
					$ret = $this->doQuery( $commentedSql );
					$queryRuntime = microtime( true ) - $startTime;
					# Log the query time and feed it into the DB trx profiler
					$this->getTransactionProfiler()->recordQueryCompletion(
						$queryProf, $startTime, $isWriteQuery, $this->affectedRows() );
				}
			} else {
				wfDebug( "Failed\n" );
			}
		}

		if ( false === $ret ) {
			$this->reportQueryError(
				$this->lastError(), $this->lastErrno(), $sql, $fname, $tempIgnore );
		}

		$res = $this->resultObject( $ret );

		// Destroy profile sections in the opposite order to their creation
		ScopedCallback::consume( $queryProfSection );
		ScopedCallback::consume( $totalProfSection );

		if ( $isWriteQuery && $this->mTrxLevel ) {
			$this->mTrxWriteDuration += $queryRuntime;
			$this->mTrxWriteCallers[] = $fname;
		}

		return $res;
	}

	public function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		if ( $this->ignoreErrors() || $tempIgnore ) {
			wfDebug( "SQL ERROR (ignored): $error\n" );
		} else {
			$sql1line = mb_substr( str_replace( "\n", "\\n", $sql ), 0, 5 * 1024 );
			wfLogDBError(
				"{fname}\t{db_server}\t{errno}\t{error}\t{sql1line}",
				$this->getLogContext( [
					'method' => __METHOD__,
					'errno' => $errno,
					'error' => $error,
					'sql1line' => $sql1line,
					'fname' => $fname,
				] )
			);
			wfDebug( "SQL ERROR: " . $error . "\n" );
			throw new DBQueryError( $this, $error, $errno, $sql, $fname );
		}
	}

	/**
	 * Intended to be compatible with the PEAR::DB wrapper functions.
	 * http://pear.php.net/manual/en/package.database.db.intro-execute.php
	 *
	 * ? = scalar value, quoted as necessary
	 * ! = raw SQL bit (a function for instance)
	 * & = filename; reads the file and inserts as a blob
	 *     (we don't use this though...)
	 *
	 * @param string $sql
	 * @param string $func
	 *
	 * @return array
	 */
	protected function prepare( $sql, $func = 'DatabaseBase::prepare' ) {
		/* MySQL doesn't support prepared statements (yet), so just
		 * pack up the query for reference. We'll manually replace
		 * the bits later.
		 */
		return [ 'query' => $sql, 'func' => $func ];
	}

	/**
	 * Free a prepared query, generated by prepare().
	 * @param string $prepared
	 */
	protected function freePrepared( $prepared ) {
		/* No-op by default */
	}

	/**
	 * Execute a prepared query with the various arguments
	 * @param string $prepared The prepared sql
	 * @param mixed $args Either an array here, or put scalars as varargs
	 *
	 * @return ResultWrapper
	 */
	public function execute( $prepared, $args = null ) {
		if ( !is_array( $args ) ) {
			# Pull the var args
			$args = func_get_args();
			array_shift( $args );
		}

		$sql = $this->fillPrepared( $prepared['query'], $args );

		return $this->query( $sql, $prepared['func'] );
	}

	/**
	 * For faking prepared SQL statements on DBs that don't support it directly.
	 *
	 * @param string $preparedQuery A 'preparable' SQL statement
	 * @param array $args Array of Arguments to fill it with
	 * @return string Executable SQL
	 */
	public function fillPrepared( $preparedQuery, $args ) {
		reset( $args );
		$this->preparedArgs =& $args;

		return preg_replace_callback( '/(\\\\[?!&]|[?!&])/',
			[ &$this, 'fillPreparedArg' ], $preparedQuery );
	}

	/**
	 * preg_callback func for fillPrepared()
	 * The arguments should be in $this->preparedArgs and must not be touched
	 * while we're doing this.
	 *
	 * @param array $matches
	 * @throws DBUnexpectedError
	 * @return string
	 */
	protected function fillPreparedArg( $matches ) {
		switch ( $matches[1] ) {
			case '\\?':
				return '?';
			case '\\!':
				return '!';
			case '\\&':
				return '&';
		}

		list( /* $n */, $arg ) = each( $this->preparedArgs );

		switch ( $matches[1] ) {
			case '?':
				return $this->addQuotes( $arg );
			case '!':
				return $arg;
			case '&':
				# return $this->addQuotes( file_get_contents( $arg ) );
				throw new DBUnexpectedError(
					$this,
					'& mode is not implemented. If it\'s really needed, uncomment the line above.'
				);
			default:
				throw new DBUnexpectedError(
					$this,
					'Received invalid match. This should never happen!'
				);
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
	 * @see DatabaseBase::select()
	 */
	public function makeSelectOptions( $options ) {
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

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail ];
	}

	/**
	 * Returns an optional GROUP BY with an optional HAVING
	 *
	 * @param array $options Associative array of options
	 * @return string
	 * @see DatabaseBase::select()
	 * @since 1.21
	 */
	public function makeGroupByWithHaving( $options ) {
		$sql = '';
		if ( isset( $options['GROUP BY'] ) ) {
			$gb = is_array( $options['GROUP BY'] )
				? implode( ',', $options['GROUP BY'] )
				: $options['GROUP BY'];
			$sql .= ' GROUP BY ' . $gb;
		}
		if ( isset( $options['HAVING'] ) ) {
			$having = is_array( $options['HAVING'] )
				? $this->makeList( $options['HAVING'], LIST_AND )
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
	 * @see DatabaseBase::select()
	 * @since 1.21
	 */
	public function makeOrderBy( $options ) {
		if ( isset( $options['ORDER BY'] ) ) {
			$ob = is_array( $options['ORDER BY'] )
				? implode( ',', $options['ORDER BY'] )
				: $options['ORDER BY'];

			return ' ORDER BY ' . $ob;
		}

		return '';
	}

	// See IDatabase::select for the docs for this function
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

		if ( is_array( $table ) ) {
			$from = ' FROM ' .
				$this->tableNamesWithUseIndexOrJOIN( $table, $useIndexes, $join_conds );
		} elseif ( $table != '' ) {
			if ( $table[0] == ' ' ) {
				$from = ' FROM ' . $table;
			} else {
				$from = ' FROM ' .
					$this->tableNamesWithUseIndexOrJOIN( [ $table ], $useIndexes, [] );
			}
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $preLimitTail, $postLimitTail ) =
			$this->makeSelectOptions( $options );

		if ( !empty( $conds ) ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
			}
			$sql = "SELECT $startOpts $vars $from $useIndex WHERE $conds $preLimitTail";
		} else {
			$sql = "SELECT $startOpts $vars $from $useIndex $preLimitTail";
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
	 * Helper for DatabaseBase::insert().
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
	 * Make UPDATE options array for DatabaseBase::makeUpdateOptions
	 *
	 * @param array $options
	 * @return array
	 */
	protected function makeUpdateOptionsArray( $options ) {
		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$opts = [];

		if ( in_array( 'LOW_PRIORITY', $options ) ) {
			$opts[] = $this->lowPriorityOption();
		}

		if ( in_array( 'IGNORE', $options ) ) {
			$opts[] = 'IGNORE';
		}

		return $opts;
	}

	/**
	 * Make UPDATE options for the DatabaseBase::update function
	 *
	 * @param array $options The options passed to DatabaseBase::update
	 * @return string
	 */
	protected function makeUpdateOptions( $options ) {
		$opts = $this->makeUpdateOptionsArray( $options );

		return implode( ' ', $opts );
	}

	function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET );

		if ( $conds !== [] && $conds !== '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	public function makeList( $a, $mode = LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, 'DatabaseBase::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = '';

		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= ' AND ';
				} elseif ( $mode == LIST_OR ) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}

			if ( ( $mode == LIST_AND || $mode == LIST_OR ) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( ( $mode == LIST_SET ) && is_numeric( $field ) ) {
				$list .= "$value";
			} elseif ( ( $mode == LIST_AND || $mode == LIST_OR ) && is_array( $value ) ) {
				// Remove null from array to be handled separately if found
				$includeNull = false;
				foreach ( array_keys( $value, null, true ) as $nullKey ) {
					$includeNull = true;
					unset( $value[$nullKey] );
				}
				if ( count( $value ) == 0 && !$includeNull ) {
					throw new MWException( __METHOD__ . ": empty input for field $field" );
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
				if ( $mode == LIST_AND || $mode == LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= 'NULL';
			} else {
				if ( $mode == LIST_AND || $mode == LIST_OR || $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= $mode == LIST_NAMES ? $value : $this->addQuotes( $value );
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
					LIST_AND );
			}
		}

		if ( $conds ) {
			return $this->makeList( $conds, LIST_OR );
		} else {
			// Nothing to search for...
			return false;
		}
	}

	/**
	 * Return aggregated value alias
	 *
	 * @param array $valuedata
	 * @param string $valuename
	 *
	 * @return string
	 */
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

	/**
	 * Format a table name ready for use in constructing an SQL query
	 *
	 * This does two important things: it quotes the table names to clean them up,
	 * and it adds a table prefix if only given a table name with no quotes.
	 *
	 * All functions of this object which require a table name call this function
	 * themselves. Pass the canonical name to such functions. This is only needed
	 * when calling query() directly.
	 *
	 * @note This function does not sanitize user input. It is not safe to use
	 *   this function to escape user input.
	 * @param string $name Database table name
	 * @param string $format One of:
	 *   quoted - Automatically pass the table name through addIdentifierQuotes()
	 *            so that it can be used in a query.
	 *   raw - Do not add identifier quotes to the table name
	 * @return string Full database name
	 */
	public function tableName( $name, $format = 'quoted' ) {
		global $wgSharedDB, $wgSharedPrefix, $wgSharedTables, $wgSharedSchema;
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
			# In dbs that support it, $database may actually be the schema
			# but that doesn't affect any of the functionality here
			$prefix = '';
			$schema = null;
		} else {
			list( $table ) = $dbDetails;
			if ( $wgSharedDB !== null # We have a shared database
				&& $this->mForeign == false # We're not working on a foreign database
				&& !$this->isQuotedIdentifier( $table ) # Prevent shared tables listing '`table`'
				&& in_array( $table, $wgSharedTables ) # A shared table is selected
			) {
				$database = $wgSharedDB;
				$schema = $wgSharedSchema === null ? $this->mSchema : $wgSharedSchema;
				$prefix = $wgSharedPrefix === null ? $this->mTablePrefix : $wgSharedPrefix;
			} else {
				$database = null;
				$schema = $this->mSchema; # Default schema
				$prefix = $this->mTablePrefix; # Default prefix
			}
		}

		# Quote $table and apply the prefix if not quoted.
		# $tableName might be empty if this is called from Database::replaceVars()
		$tableName = "{$prefix}{$table}";
		if ( $format == 'quoted' && !$this->isQuotedIdentifier( $tableName ) && $tableName !== '' ) {
			$tableName = $this->addIdentifierQuotes( $tableName );
		}

		# Quote $schema and merge it with the table name if needed
		if ( strlen( $schema ) ) {
			if ( $format == 'quoted' && !$this->isQuotedIdentifier( $schema ) ) {
				$schema = $this->addIdentifierQuotes( $schema );
			}
			$tableName = $schema . '.' . $tableName;
		}

		# Quote $database and merge it with the table name if needed
		if ( $database !== null ) {
			if ( $format == 'quoted' && !$this->isQuotedIdentifier( $database ) ) {
				$database = $this->addIdentifierQuotes( $database );
			}
			$tableName = $database . '.' . $tableName;
		}

		return $tableName;
	}

	/**
	 * Fetch a number of table names into an array
	 * This is handy when you need to construct SQL for joins
	 *
	 * Example:
	 * extract( $dbr->tableNames( 'user', 'watchlist' ) );
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
	 *
	 * @return array
	 */
	public function tableNames() {
		$inArray = func_get_args();
		$retVal = [];

		foreach ( $inArray as $name ) {
			$retVal[$name] = $this->tableName( $name );
		}

		return $retVal;
	}

	/**
	 * Fetch a number of table names into an zero-indexed numerical array
	 * This is handy when you need to construct SQL for joins
	 *
	 * Example:
	 * list( $user, $watchlist ) = $dbr->tableNamesN( 'user', 'watchlist' );
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
	 *
	 * @return array
	 */
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
	public function tableNameWithAlias( $name, $alias = false ) {
		if ( !$alias || $alias == $name ) {
			return $this->tableName( $name );
		} else {
			return $this->tableName( $name ) . ' ' . $this->addIdentifierQuotes( $alias );
		}
	}

	/**
	 * Gets an array of aliased table names
	 *
	 * @param array $tables Array( [alias] => table )
	 * @return string[] See tableNameWithAlias()
	 */
	public function tableNamesWithAlias( $tables ) {
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
	public function fieldNameWithAlias( $name, $alias = false ) {
		if ( !$alias || (string)$alias === (string)$name ) {
			return $name;
		} else {
			return $name . ' AS ' . $this->addIdentifierQuotes( $alias ); // PostgreSQL needs AS
		}
	}

	/**
	 * Gets an array of aliased field names
	 *
	 * @param array $fields Array( [alias] => field )
	 * @return string[] See fieldNameWithAlias()
	 */
	public function fieldNamesWithAlias( $fields ) {
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
	 * which might have a JOIN and/or USE INDEX clause
	 *
	 * @param array $tables ( [alias] => table )
	 * @param array $use_index Same as for select()
	 * @param array $join_conds Same as for select()
	 * @return string
	 */
	protected function tableNamesWithUseIndexOrJOIN(
		$tables, $use_index = [], $join_conds = []
	) {
		$ret = [];
		$retJOIN = [];
		$use_index = (array)$use_index;
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
				$on = $this->makeList( (array)$conds, LIST_AND );
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
		// Backwards-compatibility hack
		$renamed = [
			'ar_usertext_timestamp' => 'usertext_timestamp',
			'un_user_id' => 'user_id',
			'un_user_ip' => 'user_ip',
		];

		if ( isset( $renamed[$index] ) ) {
			return $renamed[$index];
		} else {
			return $index;
		}
	}

	public function addQuotes( $s ) {
		if ( $s instanceof Blob ) {
			$s = $s->fetch();
		}
		if ( $s === null ) {
			return 'NULL';
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
					$clauses[] = $this->makeList( $rowKey, LIST_AND );
				}
			}
			$where = [ $this->makeList( $clauses, LIST_OR ) ];
		} else {
			$where = false;
		}

		$useTrx = !$this->mTrxLevel;
		if ( $useTrx ) {
			$this->begin( $fname );
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
				$this->rollback( $fname );
			}
			throw $e;
		}
		if ( $useTrx ) {
			$this->commit( $fname );
		}

		return $ok;
	}

	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds,
		$fname = __METHOD__
	) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this,
				'DatabaseBase::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		$sql .= ')';

		$this->query( $sql, $fname );
	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 *
	 * @param string $table
	 * @param string $field
	 * @return int
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SHOW COLUMNS FROM $table LIKE \"$field\";";
		$res = $this->query( $sql, 'DatabaseBase::textFieldSize' );
		$row = $this->fetchObject( $res );

		$m = [];

		if ( preg_match( '/\((.*)\)/', $row->Type, $m ) ) {
			$size = $m[1];
		} else {
			$size = -1;
		}

		return $size;
	}

	/**
	 * A string to insert into queries to show that they're low-priority, like
	 * MySQL's LOW_PRIORITY. If no such feature exists, return an empty
	 * string and nothing bad should happen.
	 *
	 * @return string Returns the text of the low priority option if it is
	 *   supported, or a blank string otherwise
	 */
	public function lowPriorityOption() {
		return '';
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'DatabaseBase::delete() called with no conditions' );
		}

		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";

		if ( $conds != '*' ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
			}
			$sql .= ' WHERE ' . $conds;
		}

		return $this->query( $sql, $fname );
	}

	public function insertSelect( $destTable, $srcTable, $varMap, $conds,
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

		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );

		if ( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( [ &$this, 'tableName' ], $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		$sql = "INSERT $insertOptions INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			" SELECT $startOpts " . implode( ',', $varMap ) .
			" FROM $srcTable $useIndex ";

		if ( $conds != '*' ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
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
			throw new DBUnexpectedError( $this, "Invalid non-numeric limit passed to limitResult()\n" );
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
			$cond = $this->makeList( $cond, LIST_AND );
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
	 * Determines if the given query error was a connection drop
	 * STUB
	 *
	 * @param integer|string $errno
	 * @return bool
	 */
	public function wasConnectionError( $errno ) {
		return false;
	}

	/**
	 * Perform a deadlock-prone transaction.
	 *
	 * This function invokes a callback function to perform a set of write
	 * queries. If a deadlock occurs during the processing, the transaction
	 * will be rolled back and the callback function will be called again.
	 *
	 * Usage:
	 *   $dbw->deadlockLoop( callback, ... );
	 *
	 * Extra arguments are passed through to the specified callback function.
	 *
	 * Returns whatever the callback function returned on its successful,
	 * iteration, or false on error, for example if the retry limit was
	 * reached.
	 * @return mixed
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
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

	public function getSlavePos() {
		# Stub
		return false;
	}

	public function getMasterPos() {
		# Stub
		return false;
	}

	final public function onTransactionIdle( $callback ) {
		$this->mTrxIdleCallbacks[] = [ $callback, wfGetCaller() ];
		if ( !$this->mTrxLevel ) {
			$this->runOnTransactionIdleCallbacks();
		}
	}

	final public function onTransactionPreCommitOrIdle( $callback ) {
		if ( $this->mTrxLevel ) {
			$this->mTrxPreCommitCallbacks[] = [ $callback, wfGetCaller() ];
		} else {
			$this->onTransactionIdle( $callback ); // this will trigger immediately
		}
	}

	/**
	 * Actually any "on transaction idle" callbacks.
	 *
	 * @since 1.20
	 */
	protected function runOnTransactionIdleCallbacks() {
		$autoTrx = $this->getFlag( DBO_TRX ); // automatic begin() enabled?

		$e = $ePrior = null; // last exception
		do { // callbacks may add callbacks :)
			$callbacks = $this->mTrxIdleCallbacks;
			$this->mTrxIdleCallbacks = []; // recursion guard
			foreach ( $callbacks as $callback ) {
				try {
					list( $phpCallback ) = $callback;
					$this->clearFlag( DBO_TRX ); // make each query its own transaction
					call_user_func( $phpCallback );
					if ( $autoTrx ) {
						$this->setFlag( DBO_TRX ); // restore automatic begin()
					} else {
						$this->clearFlag( DBO_TRX ); // restore auto-commit
					}
				} catch ( Exception $e ) {
					if ( $ePrior ) {
						MWExceptionHandler::logException( $ePrior );
					}
					$ePrior = $e;
					// Some callbacks may use startAtomic/endAtomic, so make sure
					// their transactions are ended so other callbacks don't fail
					if ( $this->trxLevel() ) {
						$this->rollback( __METHOD__ );
					}
				}
			}
		} while ( count( $this->mTrxIdleCallbacks ) );

		if ( $e instanceof Exception ) {
			throw $e; // re-throw any last exception
		}
	}

	/**
	 * Actually any "on transaction pre-commit" callbacks.
	 *
	 * @since 1.22
	 */
	protected function runOnTransactionPreCommitCallbacks() {
		$e = $ePrior = null; // last exception
		do { // callbacks may add callbacks :)
			$callbacks = $this->mTrxPreCommitCallbacks;
			$this->mTrxPreCommitCallbacks = []; // recursion guard
			foreach ( $callbacks as $callback ) {
				try {
					list( $phpCallback ) = $callback;
					call_user_func( $phpCallback );
				} catch ( Exception $e ) {
					if ( $ePrior ) {
						MWExceptionHandler::logException( $ePrior );
					}
					$ePrior = $e;
				}
			}
		} while ( count( $this->mTrxPreCommitCallbacks ) );

		if ( $e instanceof Exception ) {
			throw $e; // re-throw any last exception
		}
	}

	final public function startAtomic( $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			$this->begin( $fname );
			$this->mTrxAutomatic = true;
			// If DBO_TRX is set, a series of startAtomic/endAtomic pairs will result
			// in all changes being in one transaction to keep requests transactional.
			if ( !$this->getFlag( DBO_TRX ) ) {
				$this->mTrxAutomaticAtomic = true;
			}
		}

		$this->mTrxAtomicLevels[] = $fname;
	}

	final public function endAtomic( $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			throw new DBUnexpectedError( $this, 'No atomic transaction is open.' );
		}
		if ( !$this->mTrxAtomicLevels ||
			array_pop( $this->mTrxAtomicLevels ) !== $fname
		) {
			throw new DBUnexpectedError( $this, 'Invalid atomic section ended.' );
		}

		if ( !$this->mTrxAtomicLevels && $this->mTrxAutomaticAtomic ) {
			$this->commit( $fname, 'flush' );
		}
	}

	final public function doAtomicSection( $fname, $callback ) {
		if ( !is_callable( $callback ) ) {
			throw new UnexpectedValueException( "Invalid callback." );
		};

		$this->startAtomic( $fname );
		try {
			call_user_func_array( $callback, [ $this, $fname ] );
		} catch ( Exception $e ) {
			$this->rollback( $fname );
			throw $e;
		}
		$this->endAtomic( $fname );
	}

	final public function begin( $fname = __METHOD__ ) {
		if ( $this->mTrxLevel ) { // implicit commit
			if ( $this->mTrxAtomicLevels ) {
				// If the current transaction was an automatic atomic one, then we definitely have
				// a problem. Same if there is any unclosed atomic level.
				$levels = implode( ', ', $this->mTrxAtomicLevels );
				throw new DBUnexpectedError(
					$this,
					"Got explicit BEGIN from $fname while atomic section(s) $levels are open."
				);
			} elseif ( !$this->mTrxAutomatic ) {
				// We want to warn about inadvertently nested begin/commit pairs, but not about
				// auto-committing implicit transactions that were started by query() via DBO_TRX
				$msg = "$fname: Transaction already in progress (from {$this->mTrxFname}), " .
					" performing implicit commit!";
				wfWarn( $msg );
				wfLogDBError( $msg,
					$this->getLogContext( [
						'method' => __METHOD__,
						'fname' => $fname,
					] )
				);
			} else {
				// if the transaction was automatic and has done write operations
				if ( $this->mTrxDoneWrites ) {
					wfDebug( "$fname: Automatic transaction with writes in progress" .
						" (from {$this->mTrxFname}), performing implicit commit!\n"
					);
				}
			}

			$this->runOnTransactionPreCommitCallbacks();
			$writeTime = $this->pendingWriteQueryDuration();
			$this->doCommit( $fname );
			if ( $this->mTrxDoneWrites ) {
				$this->mDoneWrites = microtime( true );
				$this->getTransactionProfiler()->transactionWritingOut(
					$this->mServer, $this->mDBname, $this->mTrxShortId, $writeTime );
			}
			$this->runOnTransactionIdleCallbacks();
		}

		# Avoid fatals if close() was called
		$this->assertOpen();

		$this->doBegin( $fname );
		$this->mTrxTimestamp = microtime( true );
		$this->mTrxFname = $fname;
		$this->mTrxDoneWrites = false;
		$this->mTrxAutomatic = false;
		$this->mTrxAutomaticAtomic = false;
		$this->mTrxAtomicLevels = [];
		$this->mTrxIdleCallbacks = [];
		$this->mTrxPreCommitCallbacks = [];
		$this->mTrxShortId = wfRandomString( 12 );
		$this->mTrxWriteDuration = 0.0;
		$this->mTrxWriteCallers = [];
		// First SELECT after BEGIN will establish the snapshot in REPEATABLE-READ.
		// Get an estimate of the slave lag before then, treating estimate staleness
		// as lag itself just to be safe
		$status = $this->getApproximateLagStatus();
		$this->mTrxSlaveLag = $status['lag'] + ( microtime( true ) - $status['since'] );
	}

	/**
	 * Issues the BEGIN command to the database server.
	 *
	 * @see DatabaseBase::begin()
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
				"Got COMMIT while atomic sections $levels are still open"
			);
		}

		if ( $flush === 'flush' ) {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			} elseif ( !$this->mTrxAutomatic ) {
				throw new DBUnexpectedError(
					$this,
					"$fname: Flushing an explicit transaction, getting out of sync!"
				);
			}
		} else {
			if ( !$this->mTrxLevel ) {
				wfWarn( "$fname: No transaction to commit, something got out of sync!" );
				return; // nothing to do
			} elseif ( $this->mTrxAutomatic ) {
				wfWarn( "$fname: Explicit commit of implicit transaction. Something may be out of sync!" );
			}
		}

		# Avoid fatals if close() was called
		$this->assertOpen();

		$this->runOnTransactionPreCommitCallbacks();
		$writeTime = $this->pendingWriteQueryDuration();
		$this->doCommit( $fname );
		if ( $this->mTrxDoneWrites ) {
			$this->mDoneWrites = microtime( true );
			$this->getTransactionProfiler()->transactionWritingOut(
				$this->mServer, $this->mDBname, $this->mTrxShortId, $writeTime );
		}
		$this->runOnTransactionIdleCallbacks();
	}

	/**
	 * Issues the COMMIT command to the database server.
	 *
	 * @see DatabaseBase::commit()
	 * @param string $fname
	 */
	protected function doCommit( $fname ) {
		if ( $this->mTrxLevel ) {
			$this->query( 'COMMIT', $fname );
			$this->mTrxLevel = 0;
		}
	}

	final public function rollback( $fname = __METHOD__, $flush = '' ) {
		if ( $flush !== 'flush' ) {
			if ( !$this->mTrxLevel ) {
				wfWarn( "$fname: No transaction to rollback, something got out of sync!" );
				return; // nothing to do
			}
		} else {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			}
		}

		# Avoid fatals if close() was called
		$this->assertOpen();

		$this->doRollback( $fname );
		$this->mTrxIdleCallbacks = []; // cancel
		$this->mTrxPreCommitCallbacks = []; // cancel
		$this->mTrxAtomicLevels = [];
		if ( $this->mTrxDoneWrites ) {
			$this->getTransactionProfiler()->transactionWritingOut(
				$this->mServer, $this->mDBname, $this->mTrxShortId );
		}
	}

	/**
	 * Issues the ROLLBACK command to the database server.
	 *
	 * @see DatabaseBase::rollback()
	 * @param string $fname
	 */
	protected function doRollback( $fname ) {
		if ( $this->mTrxLevel ) {
			$this->query( 'ROLLBACK', $fname, true );
			$this->mTrxLevel = 0;
		}
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
	 * @throws MWException
	 * @return bool True if operation was successful
	 */
	public function duplicateTableStructure( $oldName, $newName, $temporary = false,
		$fname = __METHOD__
	) {
		throw new MWException(
			'DatabaseBase::duplicateTableStructure is not implemented in descendant class' );
	}

	function listTables( $prefix = null, $fname = __METHOD__ ) {
		throw new MWException( 'DatabaseBase::listTables is not implemented in descendant class' );
	}

	/**
	 * Reset the views process cache set by listViews()
	 * @since 1.22
	 */
	final public function clearViewsCache() {
		$this->allViews = null;
	}

	/**
	 * Lists all the VIEWs in the database
	 *
	 * For caching purposes the list of all views should be stored in
	 * $this->allViews. The process cache can be cleared with clearViewsCache()
	 *
	 * @param string $prefix Only show VIEWs with this prefix, eg. unit_test_
	 * @param string $fname Name of calling function
	 * @throws MWException
	 * @return array
	 * @since 1.22
	 */
	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		throw new MWException( 'DatabaseBase::listViews is not implemented in descendant class' );
	}

	/**
	 * Differentiates between a TABLE and a VIEW
	 *
	 * @param string $name Name of the database-structure to test.
	 * @throws MWException
	 * @return bool
	 * @since 1.22
	 */
	public function isView( $name ) {
		throw new MWException( 'DatabaseBase::isView is not implemented in descendant class' );
	}

	public function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_MW, $ts );
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
	 * Once upon a time, DatabaseBase::query() returned a bare MySQL result
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

	public function ping() {
		# Stub. Not essential to override.
		return true;
	}

	public function getSessionLagStatus() {
		return $this->getTransactionLagStatus() ?: $this->getApproximateLagStatus();
	}

	/**
	 * Get the slave lag when the current transaction started
	 *
	 * This is useful when transactions might use snapshot isolation
	 * (e.g. REPEATABLE-READ in innodb), so the "real" lag of that data
	 * is this lag plus transaction duration. If they don't, it is still
	 * safe to be pessimistic. This returns null if there is no transaction.
	 *
	 * @return array|null ('lag': seconds or false on error, 'since': UNIX timestamp of BEGIN)
	 * @since 1.27
	 */
	public function getTransactionLagStatus() {
		return $this->mTrxLevel
			? [ 'lag' => $this->mTrxSlaveLag, 'since' => $this->trxTimestamp() ]
			: null;
	}

	/**
	 * Get a slave lag estimate for this server
	 *
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of estimate)
	 * @since 1.27
	 */
	public function getApproximateLagStatus() {
		return [
			'lag'   => $this->getLBInfo( 'slave' ) ? $this->getLag() : 0,
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

	function maxListLen() {
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

	/**
	 * Read and execute SQL commands from a file.
	 *
	 * Returns true on success, error string or exception on failure (depending
	 * on object's error ignore settings).
	 *
	 * @param string $filename File name to open
	 * @param bool|callable $lineCallback Optional function called before reading each line
	 * @param bool|callable $resultCallback Optional function called for each MySQL result
	 * @param bool|string $fname Calling function name or false if name should be
	 *   generated dynamically using $filename
	 * @param bool|callable $inputCallback Optional function called for each
	 *   complete line sent
	 * @throws Exception|MWException
	 * @return bool|string
	 */
	public function sourceFile(
		$filename, $lineCallback = false, $resultCallback = false, $fname = false, $inputCallback = false
	) {
		MediaWiki\suppressWarnings();
		$fp = fopen( $filename, 'r' );
		MediaWiki\restoreWarnings();

		if ( false === $fp ) {
			throw new MWException( "Could not open \"{$filename}\".\n" );
		}

		if ( !$fname ) {
			$fname = __METHOD__ . "( $filename )";
		}

		try {
			$error = $this->sourceStream( $fp, $lineCallback, $resultCallback, $fname, $inputCallback );
		} catch ( Exception $e ) {
			fclose( $fp );
			throw $e;
		}

		fclose( $fp );

		return $error;
	}

	/**
	 * Get the full path of a patch file. Originally based on archive()
	 * from updaters.inc. Keep in mind this always returns a patch, as
	 * it fails back to MySQL if no DB-specific patch can be found
	 *
	 * @param string $patch The name of the patch, like patch-something.sql
	 * @return string Full path to patch file
	 */
	public function patchPath( $patch ) {
		global $IP;

		$dbType = $this->getType();
		if ( file_exists( "$IP/maintenance/$dbType/archives/$patch" ) ) {
			return "$IP/maintenance/$dbType/archives/$patch";
		} else {
			return "$IP/maintenance/archives/$patch";
		}
	}

	public function setSchemaVars( $vars ) {
		$this->mSchemaVars = $vars;
	}

	/**
	 * Read and execute commands from an open file handle.
	 *
	 * Returns true on success, error string or exception on failure (depending
	 * on object's error ignore settings).
	 *
	 * @param resource $fp File handle
	 * @param bool|callable $lineCallback Optional function called before reading each query
	 * @param bool|callable $resultCallback Optional function called for each MySQL result
	 * @param string $fname Calling function name
	 * @param bool|callable $inputCallback Optional function called for each complete query sent
	 * @return bool|string
	 */
	public function sourceStream( $fp, $lineCallback = false, $resultCallback = false,
		$fname = __METHOD__, $inputCallback = false
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

				if ( ( $inputCallback && call_user_func( $inputCallback, $cmd ) ) || !$inputCallback ) {
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
	 * @param string $sql SQL assembled so far
	 * @param string $newLine New line about to be added to $sql
	 * @return bool Whether $newLine contains end of the statement
	 */
	public function streamStatementEnd( &$sql, &$newLine ) {
		if ( $this->delimiter ) {
			$prev = $newLine;
			$newLine = preg_replace( '/' . preg_quote( $this->delimiter, '/' ) . '$/', '', $newLine );
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
		if ( !$this->lock( $lockKey, $fname, $timeout ) ) {
			return null;
		}

		$unlocker = new ScopedCallback( function () use ( $lockKey, $fname ) {
			$this->commit( __METHOD__, 'flush' );
			$this->unlock( $lockKey, $fname );
		} );

		$this->commit( __METHOD__, 'flush' );

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
		$sql = "DROP TABLE " . $this->tableName( $tableName );
		if ( $this->cascadingDeletes() ) {
			$sql .= " CASCADE";
		}

		return $this->query( $sql, $fName );
	}

	/**
	 * Get search engine class. All subclasses of this need to implement this
	 * if they wish to use searching.
	 *
	 * @return string
	 */
	public function getSearchEngine() {
		return 'SearchEngineDummy';
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
		return ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() )
			? 'infinity'
			: wfTimestamp( $format, $expiry );
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

	/**
	 * @since 1.19
	 * @return string
	 */
	public function __toString() {
		return (string)$this->mConn;
	}

	/**
	 * Run a few simple sanity checks
	 */
	public function __destruct() {
		if ( $this->mTrxLevel && $this->mTrxDoneWrites ) {
			trigger_error( "Uncommitted DB writes (transaction from {$this->mTrxFname})." );
		}
		if ( count( $this->mTrxIdleCallbacks ) || count( $this->mTrxPreCommitCallbacks ) ) {
			$callers = [];
			foreach ( $this->mTrxIdleCallbacks as $callbackInfo ) {
				$callers[] = $callbackInfo[1];
			}
			$callers = implode( ', ', $callers );
			trigger_error( "DB transaction callbacks still pending (from $callers)." );
		}
	}
}

/**
 * @since 1.27
 */
abstract class Database extends DatabaseBase {
	// B/C until nothing type hints for DatabaseBase
	// @TODO: finish renaming DatabaseBase => Database
}
