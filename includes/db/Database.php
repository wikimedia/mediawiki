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

	/** @var resource Database connection */
	protected $mConn = null;
	protected $mOpened = false;

	/** @var callable[] */
	protected $mTrxIdleCallbacks = array();
	/** @var callable[] */
	protected $mTrxPreCommitCallbacks = array();

	protected $mTablePrefix;
	protected $mSchema;
	protected $mFlags;
	protected $mForeign;
	protected $mLBInfo = array();
	protected $mDefaultBigSelects = null;
	protected $mSchemaVars = false;
	/** @var array */
	protected $mSessionVars = array();

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
	 * @var SplStack
	 */
	private $mTrxAtomicLevels;

	/**
	 * Record if the current transaction was started implicitly by DatabaseBase::startAtomic
	 *
	 * @var bool
	 */
	private $mTrxAutomaticAtomic = false;

	/**
	 * Track the seconds spent in write queries for the current transaction
	 *
	 * @var float
	 */
	private $mTrxWriteDuration = 0.0;

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

	/**
	 * A string describing the current software version, and possibly
	 * other details in a user-friendly way. Will be listed on Special:Version, etc.
	 * Use getServerVersion() to get machine-friendly information.
	 *
	 * @return string Version information from the database server
	 */
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

	/**
	 * Turns buffering of SQL result sets on (true) or off (false). Default is
	 * "on".
	 *
	 * Unbuffered queries are very troublesome in MySQL:
	 *
	 *   - If another query is executed while the first query is being read
	 *     out, the first query is killed. This means you can't call normal
	 *     MediaWiki functions while you are reading an unbuffered query result
	 *     from a normal wfGetDB() connection.
	 *
	 *   - Unbuffered queries cause the MySQL server to use large amounts of
	 *     memory and to hold broad locks which block other queries.
	 *
	 * If you want to limit client-side memory, it's almost always better to
	 * split up queries into batches using a LIMIT clause than to switch off
	 * buffering.
	 *
	 * @param null|bool $buffer
	 * @return null|bool The previous value of the flag
	 */
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

	/**
	 * Gets the current transaction level.
	 *
	 * Historically, transactions were allowed to be "nested". This is no
	 * longer supported, so this function really only returns a boolean.
	 *
	 * @return int The previous value
	 */
	public function trxLevel() {
		return $this->mTrxLevel;
	}

	/**
	 * Get the UNIX timestamp of the time that the transaction was established
	 *
	 * This can be used to reason about the staleness of SELECT data
	 * in REPEATABLE-READ transaction isolation level.
	 *
	 * @return float|null Returns null if there is not active transaction
	 * @since 1.25
	 */
	public function trxTimestamp() {
		return $this->mTrxLevel ? $this->mTrxTimestamp : null;
	}

	/**
	 * Get/set the table prefix.
	 * @param string $prefix The table prefix to set, or omitted to leave it unchanged.
	 * @return string The previous table prefix.
	 */
	public function tablePrefix( $prefix = null ) {
		return wfSetVar( $this->mTablePrefix, $prefix );
	}

	/**
	 * Get/set the db schema.
	 * @param string $schema The database schema to set, or omitted to leave it unchanged.
	 * @return string The previous db schema.
	 */
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

	/**
	 * Get properties passed down from the server info array of the load
	 * balancer.
	 *
	 * @param string $name The entry of the info array to get, or null to get the
	 *   whole array
	 *
	 * @return array|mixed|null
	 */
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

	/**
	 * Set the LB info array, or a member of it. If called with one parameter,
	 * the LB info array is set to that parameter. If it is called with two
	 * parameters, the member with the given name is set to the given value.
	 *
	 * @param string $name
	 * @param array $value
	 */
	public function setLBInfo( $name, $value = null ) {
		if ( is_null( $value ) ) {
			$this->mLBInfo = $name;
		} else {
			$this->mLBInfo[$name] = $value;
		}
	}

	/**
	 * Set lag time in seconds for a fake slave
	 *
	 * @param mixed $lag Valid values for this parameter are determined by the
	 *   subclass, but should be a PHP scalar or array that would be sensible
	 *   as part of $wgLBFactoryConf.
	 */
	public function setFakeSlaveLag( $lag ) {
	}

	/**
	 * Make this connection a fake master
	 *
	 * @param bool $enabled
	 */
	public function setFakeMaster( $enabled = true ) {
	}

	/**
	 * @return TransactionProfiler
	 */
	protected function getTransactionProfiler() {
		return $this->trxProfiler
			? $this->trxProfiler
			: Profiler::instance()->getTransactionProfiler();
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

	/**
	 * Returns true if this database does an implicit sort when doing GROUP BY
	 *
	 * @return bool
	 */
	public function implicitGroupby() {
		return true;
	}

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 *
	 * @return bool
	 */
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

	/**
	 * Return the last query that went through DatabaseBase::query()
	 * @return string
	 */
	public function lastQuery() {
		return $this->mLastQuery;
	}

	/**
	 * Returns true if the connection may have been used for write queries.
	 * Should return true if unsure.
	 *
	 * @return bool
	 */
	public function doneWrites() {
		return (bool)$this->mDoneWrites;
	}

	/**
	 * Returns the last time the connection may have been used for write queries.
	 * Should return a timestamp if unsure.
	 *
	 * @return int|float UNIX timestamp or false
	 * @since 1.24
	 */
	public function lastDoneWrites() {
		return $this->mDoneWrites ?: false;
	}

	/**
	 * Returns true if there is a transaction open with possible write
	 * queries or transaction pre-commit/idle callbacks waiting on it to finish.
	 *
	 * @return bool
	 */
	public function writesOrCallbacksPending() {
		return $this->mTrxLevel && (
			$this->mTrxDoneWrites || $this->mTrxIdleCallbacks || $this->mTrxPreCommitCallbacks
		);
	}

	/**
	 * Get the time spend running write queries for this
	 *
	 * High times could be due to scanning, updates, locking, and such
	 *
	 * @return float|bool Returns false if not transaction is active
	 * @since 1.26
	 */
	public function pendingWriteQueryDuration() {
		return $this->mTrxLevel ? $this->mTrxWriteDuration : false;
	}

	/**
	 * Is a connection to the database open?
	 * @return bool
	 */
	public function isOpen() {
		return $this->mOpened;
	}

	/**
	 * Set a flag for this connection
	 *
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_DEFAULT: automatically sets DBO_TRX if not in command line mode
	 *       and removes it in command line mode
	 *   - DBO_PERSISTENT: use persistant database connection
	 */
	public function setFlag( $flag ) {
		global $wgDebugDBTransactions;
		$this->mFlags |= $flag;
		if ( ( $flag & DBO_TRX ) && $wgDebugDBTransactions ) {
			wfDebug( "Implicit transactions are now enabled.\n" );
		}
	}

	/**
	 * Clear a flag for this connection
	 *
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_DEFAULT: automatically sets DBO_TRX if not in command line mode
	 *       and removes it in command line mode
	 *   - DBO_PERSISTENT: use persistant database connection
	 */
	public function clearFlag( $flag ) {
		global $wgDebugDBTransactions;
		$this->mFlags &= ~$flag;
		if ( ( $flag & DBO_TRX ) && $wgDebugDBTransactions ) {
			wfDebug( "Implicit transactions are now disabled.\n" );
		}
	}

	/**
	 * Returns a boolean whether the flag $flag is set for this connection
	 *
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_PERSISTENT: use persistant database connection
	 * @return bool
	 */
	public function getFlag( $flag ) {
		return !!( $this->mFlags & $flag );
	}

	/**
	 * General read-only accessor
	 *
	 * @param string $name
	 * @return string
	 */
	public function getProperty( $name ) {
		return $this->$name;
	}

	/**
	 * @return string
	 */
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
		global $wgDBprefix, $wgDBmwschema, $wgCommandLineMode, $wgDebugDBTransactions;

		$this->mTrxAtomicLevels = new SplStack;

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
				if ( $wgDebugDBTransactions ) {
					wfDebug( "Implicit transaction open disabled.\n" );
				}
			} else {
				$this->mFlags |= DBO_TRX;
				if ( $wgDebugDBTransactions ) {
					wfDebug( "Implicit transaction open enabled.\n" );
				}
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
	final public static function factory( $dbType, $p = array() ) {
		$canonicalDBTypes = array(
			'mysql' => array( 'mysqli', 'mysql' ),
			'postgres' => array(),
			'sqlite' => array(),
			'oracle' => array(),
			'mssql' => array(),
		);

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
		$defaultSchemas = array(
			'mssql' => 'get from global',
		);

		$class = 'Database' . ucfirst( $driver );
		if ( class_exists( $class ) && is_subclass_of( $class, 'DatabaseBase' ) ) {
			// Resolve some defaults for b/c
			$p['host'] = isset( $p['host'] ) ? $p['host'] : false;
			$p['user'] = isset( $p['user'] ) ? $p['user'] : false;
			$p['password'] = isset( $p['password'] ) ? $p['password'] : false;
			$p['dbname'] = isset( $p['dbname'] ) ? $p['dbname'] : false;
			$p['flags'] = isset( $p['flags'] ) ? $p['flags'] : 0;
			$p['variables'] = isset( $p['variables'] ) ? $p['variables'] : array();
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
		set_error_handler( array( $this, 'connectionErrorHandler' ) );
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
	protected function getLogContext( array $extras = array() ) {
		return array_merge(
			array(
				'db_server' => $this->mServer,
				'db_name' => $this->mDBname,
				'db_user' => $this->mUser,
			),
			$extras
		);
	}

	/**
	 * Closes a database connection.
	 * if it is open : commits any open transactions
	 *
	 * @throws MWException
	 * @return bool Operation success. true if already closed.
	 */
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

	/**
	 * @param string $error Fallback error message, used if none is given by DB
	 * @throws DBConnectionError
	 */
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
		return !in_array( $verb, array( 'BEGIN', 'COMMIT', 'ROLLBACK', 'SHOW', 'SET' ) );
	}

	/**
	 * Run an SQL query and return the result. Normally throws a DBQueryError
	 * on failure. If errors are ignored, returns false instead.
	 *
	 * In new code, the query wrappers select(), insert(), update(), delete(),
	 * etc. should be used where possible, since they give much better DBMS
	 * independence and automatically quote or validate user input in a variety
	 * of contexts. This function is generally only useful for queries which are
	 * explicitly DBMS-dependent and are unsupported by the query wrappers, such
	 * as CREATE TABLE.
	 *
	 * However, the query wrappers themselves should call this function.
	 *
	 * @param string $sql SQL query
	 * @param string $fname Name of the calling function, for profiling/SHOW PROCESSLIST
	 *     comment (you can use __METHOD__ or add some extra info)
	 * @param bool $tempIgnore Whether to avoid throwing an exception on errors...
	 *     maybe best to catch the exception instead?
	 * @throws MWException
	 * @return bool|ResultWrapper True for a successful write query, ResultWrapper object
	 *     for a successful read query, or false on failure if $tempIgnore set
	 */
	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		global $wgUser, $wgDebugDBTransactions, $wgDebugDumpSqlLength;

		$this->mLastQuery = $sql;

		$isWriteQuery = $this->isWriteQuery( $sql );
		if ( $isWriteQuery ) {
			if ( !$this->mDoneWrites ) {
				wfDebug( __METHOD__ . ': Writes done: ' .
					DatabaseBase::generalizeSQL( $sql ) . "\n" );
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
			if ( $wgDebugDBTransactions ) {
				wfDebug( "Implicit transaction start.\n" );
			}
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
			static $cnt = 0;

			$cnt++;
			$sqlx = $wgDebugDumpSqlLength ? substr( $commentedSql, 0, $wgDebugDumpSqlLength )
				: $commentedSql;
			$sqlx = strtr( $sqlx, "\t\n", '  ' );

			$master = $isMaster ? 'master' : 'slave';
			wfDebug( "Query {$this->mDBname} ($cnt) ($master): $sqlx\n" );
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
			# Transaction is gone, like it or not
			$hadTrx = $this->mTrxLevel; // possible lost transaction
			$this->mTrxLevel = 0;
			$this->mTrxIdleCallbacks = array(); // bug 65263
			$this->mTrxPreCommitCallbacks = array(); // bug 65263
			wfDebug( "Connection lost, reconnecting...\n" );
			# Stash the last error values since ping() might clear them
			$lastError = $this->lastError();
			$lastErrno = $this->lastErrno();
			if ( $this->ping() ) {
				wfDebug( "Reconnected\n" );
				$server = $this->getServer();
				$msg = __METHOD__ . ": lost connection to $server; reconnected";
				wfDebugLog( 'DBPerformance', "$msg:\n" . wfBacktrace( true ) );

				if ( $hadTrx ) {
					# Leave $ret as false and let an error be reported.
					# Callers may catch the exception and continue to use the DB.
					$this->reportQueryError( $lastError, $lastErrno, $sql, $fname, $tempIgnore );
				} else {
					# Should be safe to silently retry (no trx and thus no callbacks)
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
		$queryProfSection = false;
		$totalProfSection = false;

		if ( $isWriteQuery && $this->mTrxLevel ) {
			$this->mTrxWriteDuration += $queryRuntime;
		}

		return $res;
	}

	/**
	 * Report a query error. Log the error, and if neither the object ignore
	 * flag nor the $tempIgnore flag is set, throw a DBQueryError.
	 *
	 * @param string $error
	 * @param int $errno
	 * @param string $sql
	 * @param string $fname
	 * @param bool $tempIgnore
	 * @throws DBQueryError
	 */
	public function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		if ( $this->ignoreErrors() || $tempIgnore ) {
			wfDebug( "SQL ERROR (ignored): $error\n" );
		} else {
			$sql1line = mb_substr( str_replace( "\n", "\\n", $sql ), 0, 5 * 1024 );
			wfLogDBError(
				"{fname}\t{db_server}\t{errno}\t{error}\t{sql1line}",
				$this->getLogContext( array(
					'method' => __METHOD__,
					'errno' => $errno,
					'error' => $error,
					'sql1line' => $sql1line,
					'fname' => $fname,
				) )
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
		return array( 'query' => $sql, 'func' => $func );
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
			array( &$this, 'fillPreparedArg' ), $preparedQuery );
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

	/**
	 * Free a result object returned by query() or select(). It's usually not
	 * necessary to call this, just use unset() or let the variable holding
	 * the result object go out of scope.
	 *
	 * @param mixed $res A SQL result
	 */
	public function freeResult( $res ) {
	}

	/**
	 * A SELECT wrapper which returns a single field from a single result row.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly
	 * ignored, returns false on failure.
	 *
	 * If no result rows are returned from the query, false is returned.
	 *
	 * @param string|array $table Table name. See DatabaseBase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See DatabaseBase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See DatabaseBase::select() for details.
	 *
	 * @return bool|mixed The value from the field, or false on failure.
	 */
	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = array()
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field: got '$var'" );
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
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

	/**
	 * A SELECT wrapper which returns a list of single field values from result rows.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly
	 * ignored, returns false on failure.
	 *
	 * If no result rows are returned from the query, false is returned.
	 *
	 * @param string|array $table Table name. See DatabaseBase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See DatabaseBase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See DatabaseBase::select() for details.
	 *
	 * @return bool|array The values from the field, or false on failure
	 * @throws DBUnexpectedError
	 * @since 1.25
	 */
	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = array()
	) {
		if ( $var === '*' ) { // sanity
			throw new DBUnexpectedError( $this, "Cannot use a * field: got '$var'" );
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		$res = $this->select( $table, $var, $cond, $fname, $options );
		if ( $res === false ) {
			return false;
		}

		$values = array();
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

		$noKeyOptions = array();

		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		// if (isset($options['LIMIT'])) {
		//	$tailOpts .= $this->limitResult('', $options['LIMIT'],
		//		isset($options['OFFSET']) ? $options['OFFSET']
		//		: false);
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

		return array( $startOpts, $useIndex, $preLimitTail, $postLimitTail );
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

	/**
	 * Execute a SELECT query constructed using the various parameters provided.
	 * See below for full details of the parameters.
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param array $options Query options
	 * @param array $join_conds Join conditions
	 *
	 *
	 * @param string|array $table
	 *
	 * May be either an array of table names, or a single string holding a table
	 * name. If an array is given, table aliases can be specified, for example:
	 *
	 *    array( 'a' => 'user' )
	 *
	 * This includes the user table in the query, with the alias "a" available
	 * for use in field names (e.g. a.user_name).
	 *
	 * All of the table names given here are automatically run through
	 * DatabaseBase::tableName(), which causes the table prefix (if any) to be
	 * added, and various other table name mappings to be performed.
	 *
	 *
	 * @param string|array $vars
	 *
	 * May be either a field name or an array of field names. The field names
	 * can be complete fragments of SQL, for direct inclusion into the SELECT
	 * query. If an array is given, field aliases can be specified, for example:
	 *
	 *   array( 'maxrev' => 'MAX(rev_id)' )
	 *
	 * This includes an expression with the alias "maxrev" in the query.
	 *
	 * If an expression is given, care must be taken to ensure that it is
	 * DBMS-independent.
	 *
	 *
	 * @param string|array $conds
	 *
	 * May be either a string containing a single condition, or an array of
	 * conditions. If an array is given, the conditions constructed from each
	 * element are combined with AND.
	 *
	 * Array elements may take one of two forms:
	 *
	 *   - Elements with a numeric key are interpreted as raw SQL fragments.
	 *   - Elements with a string key are interpreted as equality conditions,
	 *     where the key is the field name.
	 *     - If the value of such an array element is a scalar (such as a
	 *       string), it will be treated as data and thus quoted appropriately.
	 *       If it is null, an IS NULL clause will be added.
	 *     - If the value is an array, an IN (...) clause will be constructed
	 *       from its non-null elements, and an IS NULL clause will be added
	 *       if null is present, such that the field may match any of the
	 *       elements in the array. The non-null elements will be quoted.
	 *
	 * Note that expressions are often DBMS-dependent in their syntax.
	 * DBMS-independent wrappers are provided for constructing several types of
	 * expression commonly used in condition queries. See:
	 *    - DatabaseBase::buildLike()
	 *    - DatabaseBase::conditional()
	 *
	 *
	 * @param string|array $options
	 *
	 * Optional: Array of query options. Boolean options are specified by
	 * including them in the array as a string value with a numeric key, for
	 * example:
	 *
	 *    array( 'FOR UPDATE' )
	 *
	 * The supported options are:
	 *
	 *   - OFFSET: Skip this many rows at the start of the result set. OFFSET
	 *     with LIMIT can theoretically be used for paging through a result set,
	 *     but this is discouraged in MediaWiki for performance reasons.
	 *
	 *   - LIMIT: Integer: return at most this many rows. The rows are sorted
	 *     and then the first rows are taken until the limit is reached. LIMIT
	 *     is applied to a result set after OFFSET.
	 *
	 *   - FOR UPDATE: Boolean: lock the returned rows so that they can't be
	 *     changed until the next COMMIT.
	 *
	 *   - DISTINCT: Boolean: return only unique result rows.
	 *
	 *   - GROUP BY: May be either an SQL fragment string naming a field or
	 *     expression to group by, or an array of such SQL fragments.
	 *
	 *   - HAVING: May be either an string containing a HAVING clause or an array of
	 *     conditions building the HAVING clause. If an array is given, the conditions
	 *     constructed from each element are combined with AND.
	 *
	 *   - ORDER BY: May be either an SQL fragment giving a field name or
	 *     expression to order by, or an array of such SQL fragments.
	 *
	 *   - USE INDEX: This may be either a string giving the index name to use
	 *     for the query, or an array. If it is an associative array, each key
	 *     gives the table name (or alias), each value gives the index name to
	 *     use for that table. All strings are SQL fragments and so should be
	 *     validated by the caller.
	 *
	 *   - EXPLAIN: In MySQL, this causes an EXPLAIN SELECT query to be run,
	 *     instead of SELECT.
	 *
	 * And also the following boolean MySQL extensions, see the MySQL manual
	 * for documentation:
	 *
	 *    - LOCK IN SHARE MODE
	 *    - STRAIGHT_JOIN
	 *    - HIGH_PRIORITY
	 *    - SQL_BIG_RESULT
	 *    - SQL_BUFFER_RESULT
	 *    - SQL_SMALL_RESULT
	 *    - SQL_CALC_FOUND_ROWS
	 *    - SQL_CACHE
	 *    - SQL_NO_CACHE
	 *
	 *
	 * @param string|array $join_conds
	 *
	 * Optional associative array of table-specific join conditions. In the
	 * most common case, this is unnecessary, since the join condition can be
	 * in $conds. However, it is useful for doing a LEFT JOIN.
	 *
	 * The key of the array contains the table name or alias. The value is an
	 * array with two elements, numbered 0 and 1. The first gives the type of
	 * join, the second is an SQL fragment giving the join condition for that
	 * table. For example:
	 *
	 *    array( 'page' => array( 'LEFT JOIN', 'page_latest=rev_id' ) )
	 *
	 * @return ResultWrapper|bool If the query returned no rows, a ResultWrapper
	 *   with no rows in it will be returned. If there was a query error, a
	 *   DBQueryError exception will be thrown, except if the "ignore errors"
	 *   option was set, in which case false will be returned.
	 */
	public function select( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = array(), $join_conds = array() ) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		return $this->query( $sql, $fname );
	}

	/**
	 * The equivalent of DatabaseBase::select() except that the constructed SQL
	 * is returned, instead of being immediately executed. This can be useful for
	 * doing UNION queries, where the SQL text of each query is needed. In general,
	 * however, callers outside of Database classes should just use select().
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param string|array $join_conds Join conditions
	 *
	 * @return string SQL query string.
	 * @see DatabaseBase::select()
	 */
	public function selectSQLText( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = array(), $join_conds = array()
	) {
		if ( is_array( $vars ) ) {
			$vars = implode( ',', $this->fieldNamesWithAlias( $vars ) );
		}

		$options = (array)$options;
		$useIndexes = ( isset( $options['USE INDEX'] ) && is_array( $options['USE INDEX'] ) )
			? $options['USE INDEX']
			: array();

		if ( is_array( $table ) ) {
			$from = ' FROM ' .
				$this->tableNamesWithUseIndexOrJOIN( $table, $useIndexes, $join_conds );
		} elseif ( $table != '' ) {
			if ( $table[0] == ' ' ) {
				$from = ' FROM ' . $table;
			} else {
				$from = ' FROM ' .
					$this->tableNamesWithUseIndexOrJOIN( array( $table ), $useIndexes, array() );
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

	/**
	 * Single row SELECT wrapper. Equivalent to DatabaseBase::select(), except
	 * that a single row object is returned. If the query returns no rows,
	 * false is returned.
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param array|string $join_conds Join conditions
	 *
	 * @return stdClass|bool
	 */
	public function selectRow( $table, $vars, $conds, $fname = __METHOD__,
		$options = array(), $join_conds = array()
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

	/**
	 * Estimate the number of rows in dataset
	 *
	 * MySQL allows you to estimate the number of rows that would be returned
	 * by a SELECT query, using EXPLAIN SELECT. The estimate is provided using
	 * index cardinality statistics, and is notoriously inaccurate, especially
	 * when large numbers of rows have recently been added or deleted.
	 *
	 * For DBMSs that don't support fast result size estimation, this function
	 * will actually perform the SELECT COUNT(*).
	 *
	 * Takes the same arguments as DatabaseBase::select().
	 *
	 * @param string $table Table name
	 * @param string $vars Unused
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @return int Row count
	 */
	public function estimateRowCount(
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = array()
	) {
		$rows = 0;
		$res = $this->select( $table, array( 'rowcount' => 'COUNT(*)' ), $conds, $fname, $options );

		if ( $res ) {
			$row = $this->fetchRow( $res );
			$rows = ( isset( $row['rowcount'] ) ) ? (int)$row['rowcount'] : 0;
		}

		return $rows;
	}

	/**
	 * Get the number of rows in dataset
	 *
	 * This is useful when trying to do COUNT(*) but with a LIMIT for performance.
	 *
	 * Takes the same arguments as DatabaseBase::select().
	 *
	 * @param string $table Table name
	 * @param string $vars Unused
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @return int Row count
	 * @since 1.24
	 */
	public function selectRowCount(
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = array()
	) {
		$rows = 0;
		$sql = $this->selectSQLText( $table, '1', $conds, $fname, $options );
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
	static function generalizeSQL( $sql ) {
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

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param string $table Table name
	 * @param string $field Filed to check on that table
	 * @param string $fname Calling function name (optional)
	 * @return bool Whether $table has filed $field
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		$info = $this->fieldInfo( $table, $field );

		return (bool)$info;
	}

	/**
	 * Determines whether an index exists
	 * Usually throws a DBQueryError on failure
	 * If errors are explicitly ignored, returns NULL on failure
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 */
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

	/**
	 * Query whether a given table exists
	 *
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 */
	public function tableExists( $table, $fname = __METHOD__ ) {
		$table = $this->tableName( $table );
		$old = $this->ignoreErrors( true );
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1", $fname );
		$this->ignoreErrors( $old );

		return (bool)$res;
	}

	/**
	 * Determines if a given index is unique
	 *
	 * @param string $table
	 * @param string $index
	 *
	 * @return bool
	 */
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

	/**
	 * INSERT wrapper, inserts an array into a table.
	 *
	 * $a may be either:
	 *
	 *   - A single associative array. The array keys are the field names, and
	 *     the values are the values to insert. The values are treated as data
	 *     and will be quoted appropriately. If NULL is inserted, this will be
	 *     converted to a database NULL.
	 *   - An array with numeric keys, holding a list of associative arrays.
	 *     This causes a multi-row INSERT on DBMSs that support it. The keys in
	 *     each subarray must be identical to each other, and in the same order.
	 *
	 * $options is an array of options, with boolean options encoded as values
	 * with numeric keys, in the same style as $options in
	 * DatabaseBase::select(). Supported options are:
	 *
	 *   - IGNORE: Boolean: if present, duplicate key errors are ignored, and
	 *     any rows which cause duplicate key errors are not inserted. It's
	 *     possible to determine how many rows were successfully inserted using
	 *     DatabaseBase::affectedRows().
	 *
	 * @param string $table Table name. This will be passed through
	 *   DatabaseBase::tableName().
	 * @param array $a Array of rows to insert
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param array $options Array of options
	 *
	 * @throws DBQueryError Usually throws a DBQueryError on failure. If errors are explicitly ignored,
	 * returns success.
	 *
	 * @return bool
	 */
	public function insert( $table, $a, $fname = __METHOD__, $options = array() ) {
		# No rows to insert, easy just return now
		if ( !count( $a ) ) {
			return true;
		}

		$table = $this->tableName( $table );

		if ( !is_array( $options ) ) {
			$options = array( $options );
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
			$options = array( $options );
		}

		$opts = array();

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

	/**
	 * UPDATE wrapper. Takes a condition array and a SET array.
	 *
	 * @param string $table Name of the table to UPDATE. This will be passed through
	 *   DatabaseBase::tableName().
	 * @param array $values An array of values to SET. For each array element,
	 *   the key gives the field name, and the value gives the data to set
	 *   that field to. The data will be quoted by DatabaseBase::addQuotes().
	 * @param array $conds An array of conditions (WHERE). See
	 *   DatabaseBase::select() for the details of the format of condition
	 *   arrays. Use '*' to update all rows.
	 * @param string $fname The function name of the caller (from __METHOD__),
	 *   for logging and profiling.
	 * @param array $options An array of UPDATE options, can be:
	 *   - IGNORE: Ignore unique key conflicts
	 *   - LOW_PRIORITY: MySQL-specific, see MySQL manual.
	 * @return bool
	 */
	function update( $table, $values, $conds, $fname = __METHOD__, $options = array() ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET );

		if ( $conds !== array() && $conds !== '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * Makes an encoded list of strings from an array
	 *
	 * @param array $a Containing the data
	 * @param int $mode Constant
	 *    - LIST_COMMA: Comma separated, no field names
	 *    - LIST_AND:   ANDed WHERE clause (without the WHERE). See the
	 *      documentation for $conds in DatabaseBase::select().
	 *    - LIST_OR:    ORed WHERE clause (without the WHERE)
	 *    - LIST_SET:   Comma separated with field names, like a SET clause
	 *    - LIST_NAMES: Comma separated field names
	 * @throws MWException|DBUnexpectedError
	 * @return string
	 */
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
						$value = array_values( $value );
						$list .= $field . " = " . $this->addQuotes( $value[0] );
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

	/**
	 * Build a partial where clause from a 2-d array such as used for LinkBatch.
	 * The keys on each level may be either integers or strings.
	 *
	 * @param array $data Organized as 2-d
	 *    array(baseKeyVal => array(subKeyVal => [ignored], ...), ...)
	 * @param string $baseKey Field name to match the base-level keys to (eg 'pl_namespace')
	 * @param string $subKey Field name to match the sub-level keys to (eg 'pl_title')
	 * @return string|bool SQL fragment, or false if no items in array
	 */
	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		$conds = array();

		foreach ( $data as $base => $sub ) {
			if ( count( $sub ) ) {
				$conds[] = $this->makeList(
					array( $baseKey => $base, $subKey => array_keys( $sub ) ),
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

	/**
	 * @param string $field
	 * @return string
	 */
	public function bitNot( $field ) {
		return "(~$field)";
	}

	/**
	 * @param string $fieldLeft
	 * @param string $fieldRight
	 * @return string
	 */
	public function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	/**
	 * @param string $fieldLeft
	 * @param string $fieldRight
	 * @return string
	 */
	public function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	/**
	 * Build a concatenation list to feed into a SQL query
	 * @param array $stringList List of raw SQL expressions; caller is
	 *   responsible for any quoting
	 * @return string
	 */
	public function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

	/**
	 * Build a GROUP_CONCAT or equivalent statement for a query.
	 *
	 * This is useful for combining a field for several rows into a single string.
	 * NULL values will not appear in the output, duplicated values will appear,
	 * and the resulting delimiter-separated values have no defined sort order.
	 * Code using the results may need to use the PHP unique() or sort() methods.
	 *
	 * @param string $delim Glue to bind the results together
	 * @param string|array $table Table name
	 * @param string $field Field name
	 * @param string|array $conds Conditions
	 * @param string|array $join_conds Join conditions
	 * @return string SQL text
	 * @since 1.23
	 */
	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = array()
	) {
		$fld = "GROUP_CONCAT($field SEPARATOR " . $this->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, array(), $join_conds ) . ')';
	}

	/**
	 * Change the current database
	 *
	 * @todo Explain what exactly will fail if this is not overridden.
	 *
	 * @param string $db
	 *
	 * @return bool Success or failure
	 */
	public function selectDB( $db ) {
		# Stub. Shouldn't cause serious problems if it's not overridden, but
		# if your database engine supports a concept similar to MySQL's
		# databases you may as well.
		$this->mDBname = $db;

		return true;
	}

	/**
	 * Get the current DB name
	 * @return string
	 */
	public function getDBname() {
		return $this->mDBname;
	}

	/**
	 * Get the server hostname or IP address
	 * @return string
	 */
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
		$retVal = array();

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
		$retVal = array();

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
		$retval = array();
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
			return $name . ' AS ' . $alias; //PostgreSQL needs AS
		}
	}

	/**
	 * Gets an array of aliased field names
	 *
	 * @param array $fields Array( [alias] => field )
	 * @return string[] See fieldNameWithAlias()
	 */
	public function fieldNamesWithAlias( $fields ) {
		$retval = array();
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
		$tables, $use_index = array(), $join_conds = array()
	) {
		$ret = array();
		$retJOIN = array();
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
		return implode( ' ', array( $implicitJoins, $explicitJoins ) );
	}

	/**
	 * Get the name of an index in a given table.
	 *
	 * @protected Don't use outside of DatabaseBase and childs
	 * @param string $index
	 * @return string
	 */
	public function indexName( $index ) {
		// @FIXME: Make this protected once we move away from PHP 5.3
		// Needs to be public because of usage in closure (in DatabaseBase::replaceVars)

		// Backwards-compatibility hack
		$renamed = array(
			'ar_usertext_timestamp' => 'usertext_timestamp',
			'un_user_id' => 'user_id',
			'un_user_ip' => 'user_ip',
		);

		if ( isset( $renamed[$index] ) ) {
			return $renamed[$index];
		} else {
			return $index;
		}
	}

	/**
	 * Adds quotes and backslashes.
	 *
	 * @param string|Blob $s
	 * @return string
	 */
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

	/**
	 * LIKE statement wrapper, receives a variable-length argument list with
	 * parts of pattern to match containing either string literals that will be
	 * escaped or tokens returned by anyChar() or anyString(). Alternatively,
	 * the function could be provided with an array of aforementioned
	 * parameters.
	 *
	 * Example: $dbr->buildLike( 'My_page_title/', $dbr->anyString() ) returns
	 * a LIKE clause that searches for subpages of 'My page title'.
	 * Alternatively:
	 *   $pattern = array( 'My_page_title/', $dbr->anyString() );
	 *   $query .= $dbr->buildLike( $pattern );
	 *
	 * @since 1.16
	 * @return string Fully built LIKE statement
	 */
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

	/**
	 * Returns a token for buildLike() that denotes a '_' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyChar() {
		return new LikeMatch( '_' );
	}

	/**
	 * Returns a token for buildLike() that denotes a '%' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyString() {
		return new LikeMatch( '%' );
	}

	/**
	 * Returns an appropriately quoted sequence value for inserting a new row.
	 * MySQL has autoincrement fields, so this is just NULL. But the PostgreSQL
	 * subclass will return an integer, and save the value for insertId()
	 *
	 * Any implementation of this function should *not* involve reusing
	 * sequence numbers created for rolled-back transactions.
	 * See http://bugs.mysql.com/bug.php?id=30767 for details.
	 * @param string $seqName
	 * @return null|int
	 */
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
	 * REPLACE query wrapper.
	 *
	 * REPLACE is a very handy MySQL extension, which functions like an INSERT
	 * except that when there is a duplicate key error, the old row is deleted
	 * and the new row is inserted in its place.
	 *
	 * We simulate this with standard SQL with a DELETE followed by INSERT. To
	 * perform the delete, we need to know what the unique indexes are so that
	 * we know how to find the conflicting rows.
	 *
	 * It may be more efficient to leave off unique indexes which are unlikely
	 * to collide. However if you do this, you run the risk of encountering
	 * errors which wouldn't have occurred in MySQL.
	 *
	 * @param string $table The table to replace the row(s) in.
	 * @param array $uniqueIndexes Is an array of indexes. Each element may be either
	 *    a field name or an array of field names
	 * @param array $rows Can be either a single row to insert, or multiple rows,
	 *    in the same format as for DatabaseBase::insert()
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 */
	public function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ ) {
		$quotedTable = $this->tableName( $table );

		if ( count( $rows ) == 0 ) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
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
			$rows = array( $rows );
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

	/**
	 * INSERT ON DUPLICATE KEY UPDATE wrapper, upserts an array into a table.
	 *
	 * This updates any conflicting rows (according to the unique indexes) using
	 * the provided SET clause and inserts any remaining (non-conflicted) rows.
	 *
	 * $rows may be either:
	 *   - A single associative array. The array keys are the field names, and
	 *     the values are the values to insert. The values are treated as data
	 *     and will be quoted appropriately. If NULL is inserted, this will be
	 *     converted to a database NULL.
	 *   - An array with numeric keys, holding a list of associative arrays.
	 *     This causes a multi-row INSERT on DBMSs that support it. The keys in
	 *     each subarray must be identical to each other, and in the same order.
	 *
	 * It may be more efficient to leave off unique indexes which are unlikely
	 * to collide. However if you do this, you run the risk of encountering
	 * errors which wouldn't have occurred in MySQL.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly ignored,
	 * returns success.
	 *
	 * @since 1.22
	 *
	 * @param string $table Table name. This will be passed through DatabaseBase::tableName().
	 * @param array $rows A single row or list of rows to insert
	 * @param array $uniqueIndexes List of single field names or field name tuples
	 * @param array $set An array of values to SET. For each array element, the
	 *   key gives the field name, and the value gives the data to set that
	 *   field to. The data will be quoted by DatabaseBase::addQuotes().
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws Exception
	 * @return bool
	 */
	public function upsert( $table, array $rows, array $uniqueIndexes, array $set,
		$fname = __METHOD__
	) {
		if ( !count( $rows ) ) {
			return true; // nothing to do
		}

		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		if ( count( $uniqueIndexes ) ) {
			$clauses = array(); // list WHERE clauses that each identify a single row
			foreach ( $rows as $row ) {
				foreach ( $uniqueIndexes as $index ) {
					$index = is_array( $index ) ? $index : array( $index ); // columns
					$rowKey = array(); // unique key to this row
					foreach ( $index as $column ) {
						$rowKey[$column] = $row[$column];
					}
					$clauses[] = $this->makeList( $rowKey, LIST_AND );
				}
			}
			$where = array( $this->makeList( $clauses, LIST_OR ) );
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
			$ok = $this->insert( $table, $rows, $fname, array( 'IGNORE' ) ) && $ok;
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

	/**
	 * DELETE where the condition is a join.
	 *
	 * MySQL overrides this to use a multi-table DELETE syntax, in other databases
	 * we use sub-selects
	 *
	 * For safety, an empty $conds will not delete everything. If you want to
	 * delete all rows where the join condition matches, set $conds='*'.
	 *
	 * DO NOT put the join condition in $conds.
	 *
	 * @param string $delTable The table to delete from.
	 * @param string $joinTable The other table.
	 * @param string $delVar The variable to join on, in the first table.
	 * @param string $joinVar The variable to join on, in the second table.
	 * @param array $conds Condition array of field names mapped to variables,
	 *   ANDed together in the WHERE clause
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws DBUnexpectedError
	 */
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

		$m = array();

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

	/**
	 * DELETE query wrapper.
	 *
	 * @param array $table Table name
	 * @param string|array $conds Array of conditions. See $conds in DatabaseBase::select()
	 *   for the format. Use $conds == "*" to delete all rows
	 * @param string $fname Name of the calling function
	 * @throws DBUnexpectedError
	 * @return bool|ResultWrapper
	 */
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

	/**
	 * INSERT SELECT wrapper. Takes data from a SELECT query and inserts it
	 * into another table.
	 *
	 * @param string $destTable The table name to insert into
	 * @param string|array $srcTable May be either a table name, or an array of table names
	 *    to include in a join.
	 *
	 * @param array $varMap Must be an associative array of the form
	 *    array( 'dest1' => 'source1', ...). Source items may be literals
	 *    rather than field names, but strings should be quoted with
	 *    DatabaseBase::addQuotes()
	 *
	 * @param array $conds Condition array. See $conds in DatabaseBase::select() for
	 *    the details of the format of condition arrays. May be "*" to copy the
	 *    whole table.
	 *
	 * @param string $fname The function name of the caller, from __METHOD__
	 *
	 * @param array $insertOptions Options for the INSERT part of the query, see
	 *    DatabaseBase::insert() for details.
	 * @param array $selectOptions Options for the SELECT part of the query, see
	 *    DatabaseBase::select() for details.
	 *
	 * @return ResultWrapper
	 */
	public function insertSelect( $destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__,
		$insertOptions = array(), $selectOptions = array()
	) {
		$destTable = $this->tableName( $destTable );

		if ( !is_array( $insertOptions ) ) {
			$insertOptions = array( $insertOptions );
		}

		$insertOptions = $this->makeInsertOptions( $insertOptions );

		if ( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}

		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );

		if ( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( array( &$this, 'tableName' ), $srcTable ) );
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

	/**
	 * Returns true if current database backend supports ORDER BY or LIMIT for separate subqueries
	 * within the UNION construct.
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	/**
	 * Construct a UNION query
	 * This is used for providing overload point for other DB abstractions
	 * not compatible with the MySQL syntax.
	 * @param array $sqls SQL statements to combine
	 * @param bool $all Use UNION ALL
	 * @return string SQL fragment
	 */
	public function unionQueries( $sqls, $all ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';

		return '(' . implode( $glue, $sqls ) . ')';
	}

	/**
	 * Returns an SQL expression for a simple conditional. This doesn't need
	 * to be overridden unless CASE isn't supported in your DBMS.
	 *
	 * @param string|array $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	public function conditional( $cond, $trueVal, $falseVal ) {
		if ( is_array( $cond ) ) {
			$cond = $this->makeList( $cond, LIST_AND );
		}

		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	/**
	 * Returns a comand for str_replace function in SQL query.
	 * Uses REPLACE() in MySQL
	 *
	 * @param string $orig Column to modify
	 * @param string $old Column to seek
	 * @param string $new Column to replace with
	 *
	 * @return string
	 */
	public function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

	/**
	 * Determines how long the server has been up
	 * STUB
	 *
	 * @return int
	 */
	public function getServerUptime() {
		return 0;
	}

	/**
	 * Determines if the last failure was due to a deadlock
	 * STUB
	 *
	 * @return bool
	 */
	public function wasDeadlock() {
		return false;
	}

	/**
	 * Determines if the last failure was due to a lock timeout
	 * STUB
	 *
	 * @return bool
	 */
	public function wasLockTimeout() {
		return false;
	}

	/**
	 * Determines if the last query error was something that should be dealt
	 * with by pinging the connection and reissuing the query.
	 * STUB
	 *
	 * @return bool
	 */
	public function wasErrorReissuable() {
		return false;
	}

	/**
	 * Determines if the last failure was due to the database being read-only.
	 * STUB
	 *
	 * @return bool
	 */
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
	 *
	 * @return mixed
	 * @throws DBQueryError
	 */
	public function deadlockLoop() {
		$args = func_get_args();
		$function = array_shift( $args );
		$tries = self::DEADLOCK_TRIES;
		if ( is_array( $function ) ) {
			$fname = $function[0];
		} else {
			$fname = $function;
		}

		$this->begin( __METHOD__ );

		$retVal = null;
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

	/**
	 * Wait for the slave to catch up to a given master position.
	 *
	 * @param DBMasterPos $pos
	 * @param int $timeout The maximum number of seconds to wait for
	 *   synchronisation
	 * @return int Zero if the slave was past that position already,
	 *   greater than zero if we waited for some period of time, less than
	 *   zero if we timed out.
	 */
	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		# Real waits are implemented in the subclass.
		return 0;
	}

	/**
	 * Get the replication position of this slave
	 *
	 * @return DBMasterPos|bool False if this is not a slave.
	 */
	public function getSlavePos() {
		# Stub
		return false;
	}

	/**
	 * Get the position of this master
	 *
	 * @return DBMasterPos|bool False if this is not a master
	 */
	public function getMasterPos() {
		# Stub
		return false;
	}

	/**
	 * Run an anonymous function as soon as there is no transaction pending.
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 * Queries in the function will run in AUTO-COMMIT mode unless there are begin() calls.
	 * Callbacks must commit any transactions that they begin.
	 *
	 * This is useful for updates to different systems or when separate transactions are needed.
	 * For example, one might want to enqueue jobs into a system outside the database, but only
	 * after the database is updated so that the jobs will see the data when they actually run.
	 * It can also be used for updates that easily cause deadlocks if locks are held too long.
	 *
	 * @param callable $callback
	 * @since 1.20
	 */
	final public function onTransactionIdle( $callback ) {
		$this->mTrxIdleCallbacks[] = array( $callback, wfGetCaller() );
		if ( !$this->mTrxLevel ) {
			$this->runOnTransactionIdleCallbacks();
		}
	}

	/**
	 * Run an anonymous function before the current transaction commits or now if there is none.
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 * Callbacks must not start nor commit any transactions.
	 *
	 * This is useful for updates that easily cause deadlocks if locks are held too long
	 * but where atomicity is strongly desired for these updates and some related updates.
	 *
	 * @param callable $callback
	 * @since 1.22
	 */
	final public function onTransactionPreCommitOrIdle( $callback ) {
		if ( $this->mTrxLevel ) {
			$this->mTrxPreCommitCallbacks[] = array( $callback, wfGetCaller() );
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
			$this->mTrxIdleCallbacks = array(); // recursion guard
			foreach ( $callbacks as $callback ) {
				try {
					list( $phpCallback ) = $callback;
					$this->clearFlag( DBO_TRX ); // make each query its own transaction
					call_user_func( $phpCallback );
					$this->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
				} catch ( Exception $e ) {
					if ( $ePrior ) {
						MWExceptionHandler::logException( $ePrior );
					}
					$ePrior = $e;
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
			$this->mTrxPreCommitCallbacks = array(); // recursion guard
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

	/**
	 * Begin an atomic section of statements
	 *
	 * If a transaction has been started already, just keep track of the given
	 * section name to make sure the transaction is not committed pre-maturely.
	 * This function can be used in layers (with sub-sections), so use a stack
	 * to keep track of the different atomic sections. If there is no transaction,
	 * start one implicitly.
	 *
	 * The goal of this function is to create an atomic section of SQL queries
	 * without having to start a new transaction if it already exists.
	 *
	 * Atomic sections are more strict than transactions. With transactions,
	 * attempting to begin a new transaction when one is already running results
	 * in MediaWiki issuing a brief warning and doing an implicit commit. All
	 * atomic levels *must* be explicitly closed using DatabaseBase::endAtomic(),
	 * and any database transactions cannot be began or committed until all atomic
	 * levels are closed. There is no such thing as implicitly opening or closing
	 * an atomic section.
	 *
	 * @since 1.23
	 * @param string $fname
	 * @throws DBError
	 */
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

		$this->mTrxAtomicLevels->push( $fname );
	}

	/**
	 * Ends an atomic section of SQL statements
	 *
	 * Ends the next section of atomic SQL statements and commits the transaction
	 * if necessary.
	 *
	 * @since 1.23
	 * @see DatabaseBase::startAtomic
	 * @param string $fname
	 * @throws DBError
	 */
	final public function endAtomic( $fname = __METHOD__ ) {
		if ( !$this->mTrxLevel ) {
			throw new DBUnexpectedError( $this, 'No atomic transaction is open.' );
		}
		if ( $this->mTrxAtomicLevels->isEmpty() ||
			$this->mTrxAtomicLevels->pop() !== $fname
		) {
			throw new DBUnexpectedError( $this, 'Invalid atomic section ended.' );
		}

		if ( $this->mTrxAtomicLevels->isEmpty() && $this->mTrxAutomaticAtomic ) {
			$this->commit( $fname, 'flush' );
		}
	}

	/**
	 * Begin a transaction. If a transaction is already in progress,
	 * that transaction will be committed before the new transaction is started.
	 *
	 * Note that when the DBO_TRX flag is set (which is usually the case for web
	 * requests, but not for maintenance scripts), any previous database query
	 * will have started a transaction automatically.
	 *
	 * Nesting of transactions is not supported. Attempts to nest transactions
	 * will cause a warning, unless the current transaction was started
	 * automatically because of the DBO_TRX flag.
	 *
	 * @param string $fname
	 * @throws DBError
	 */
	final public function begin( $fname = __METHOD__ ) {
		global $wgDebugDBTransactions;

		if ( $this->mTrxLevel ) { // implicit commit
			if ( !$this->mTrxAtomicLevels->isEmpty() ) {
				// If the current transaction was an automatic atomic one, then we definitely have
				// a problem. Same if there is any unclosed atomic level.
				throw new DBUnexpectedError( $this,
					"Attempted to start explicit transaction when atomic levels are still open."
				);
			} elseif ( !$this->mTrxAutomatic ) {
				// We want to warn about inadvertently nested begin/commit pairs, but not about
				// auto-committing implicit transactions that were started by query() via DBO_TRX
				$msg = "$fname: Transaction already in progress (from {$this->mTrxFname}), " .
					" performing implicit commit!";
				wfWarn( $msg );
				wfLogDBError( $msg,
					$this->getLogContext( array(
						'method' => __METHOD__,
						'fname' => $fname,
					) )
				);
			} else {
				// if the transaction was automatic and has done write operations,
				// log it if $wgDebugDBTransactions is enabled.
				if ( $this->mTrxDoneWrites && $wgDebugDBTransactions ) {
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
		$this->mTrxAtomicLevels = new SplStack;
		$this->mTrxIdleCallbacks = array();
		$this->mTrxPreCommitCallbacks = array();
		$this->mTrxShortId = wfRandomString( 12 );
		$this->mTrxWriteDuration = 0.0;
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

	/**
	 * Commits a transaction previously started using begin().
	 * If no transaction is in progress, a warning is issued.
	 *
	 * Nesting of transactions is not supported.
	 *
	 * @param string $fname
	 * @param string $flush Flush flag, set to 'flush' to disable warnings about
	 *   explicitly committing implicit transactions, or calling commit when no
	 *   transaction is in progress. This will silently break any ongoing
	 *   explicit transaction. Only set the flush flag if you are sure that it
	 *   is safe to ignore these warnings in your context.
	 * @throws DBUnexpectedError
	 */
	final public function commit( $fname = __METHOD__, $flush = '' ) {
		if ( !$this->mTrxAtomicLevels->isEmpty() ) {
			// There are still atomic sections open. This cannot be ignored
			throw new DBUnexpectedError(
				$this,
				"Attempted to commit transaction while atomic sections are still open"
			);
		}

		if ( $flush === 'flush' ) {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			} elseif ( !$this->mTrxAutomatic ) {
				wfWarn( "$fname: Flushing an explicit transaction, getting out of sync!" );
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

	/**
	 * Rollback a transaction previously started using begin().
	 * If no transaction is in progress, a warning is issued.
	 *
	 * No-op on non-transactional databases.
	 *
	 * @param string $fname
	 * @param string $flush Flush flag, set to 'flush' to disable warnings about
	 *   calling rollback when no transaction is in progress. This will silently
	 *   break any ongoing explicit transaction. Only set the flush flag if you
	 *   are sure that it is safe to ignore these warnings in your context.
	 * @throws DBUnexpectedError
	 * @since 1.23 Added $flush parameter
	 */
	final public function rollback( $fname = __METHOD__, $flush = '' ) {
		if ( $flush !== 'flush' ) {
			if ( !$this->mTrxLevel ) {
				wfWarn( "$fname: No transaction to rollback, something got out of sync!" );
				return; // nothing to do
			} elseif ( $this->mTrxAutomatic ) {
				wfWarn( "$fname: Explicit rollback of implicit transaction. Something may be out of sync!" );
			}
		} else {
			if ( !$this->mTrxLevel ) {
				return; // nothing to do
			} elseif ( !$this->mTrxAutomatic ) {
				wfWarn( "$fname: Flushing an explicit transaction, getting out of sync!" );
			}
		}

		# Avoid fatals if close() was called
		$this->assertOpen();

		$this->doRollback( $fname );
		$this->mTrxIdleCallbacks = array(); // cancel
		$this->mTrxPreCommitCallbacks = array(); // cancel
		$this->mTrxAtomicLevels = new SplStack;
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

	/**
	 * List all tables on the database
	 *
	 * @param string $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @throws MWException
	 * @return array
	 */
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

	/**
	 * Convert a timestamp in one of the formats accepted by wfTimestamp()
	 * to the format used for inserting into timestamp fields in this DBMS.
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int $ts
	 *
	 * @return string
	 */
	public function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_MW, $ts );
	}

	/**
	 * Convert a timestamp in one of the formats accepted by wfTimestamp()
	 * to the format used for inserting into timestamp fields in this DBMS. If
	 * NULL is input, it is passed through, allowing NULL values to be inserted
	 * into timestamp fields.
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int $ts
	 *
	 * @return string
	 */
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
	 * callers, so this is unnecessary in external code. For compatibility with
	 * old code, ResultWrapper objects are passed through unaltered.
	 *
	 * @param bool|ResultWrapper|resource $result
	 * @return bool|ResultWrapper
	 */
	public function resultObject( $result ) {
		if ( empty( $result ) ) {
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

	/**
	 * Ping the server and try to reconnect if it there is no connection
	 *
	 * @return bool Success or failure
	 */
	public function ping() {
		# Stub. Not essential to override.
		return true;
	}

	/**
	 * Get slave lag. Currently supported only by MySQL.
	 *
	 * Note that this function will generate a fatal error on many
	 * installations. Most callers should use LoadBalancer::safeGetLag()
	 * instead.
	 *
	 * @return int Database replication lag in seconds
	 */
	public function getLag() {
		return 0;
	}

	/**
	 * Return the maximum number of items allowed in a list, or 0 for unlimited.
	 *
	 * @return int
	 */
	function maxListLen() {
		return 0;
	}

	/**
	 * Some DBMSs have a special format for inserting into blob fields, they
	 * don't allow simple quoted strings to be inserted. To insert into such
	 * a field, pass the data through this function before passing it to
	 * DatabaseBase::insert().
	 *
	 * @param string $b
	 * @return string
	 */
	public function encodeBlob( $b ) {
		return $b;
	}

	/**
	 * Some DBMSs return a special placeholder object representing blob fields
	 * in result objects. Pass the object through this function to return the
	 * original string.
	 *
	 * @param string|Blob $b
	 * @return string
	 */
	public function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}
		return $b;
	}

	/**
	 * Override database's default behavior. $options include:
	 *     'connTimeout' : Set the connection timeout value in seconds.
	 *                     May be useful for very long batch queries such as
	 *                     full-wiki dumps, where a single query reads out over
	 *                     hours or days.
	 *
	 * @param array $options
	 * @return void
	 */
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

	/**
	 * Set variables to be used in sourceFile/sourceStream, in preference to the
	 * ones in $GLOBALS. If an array is set here, $GLOBALS will not be used at
	 * all. If it's set to false, $GLOBALS will be used.
	 *
	 * @param bool|array $vars Mapping variable name to value.
	 */
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
		$that = $this;
		$vars = $this->getSchemaVars();
		return preg_replace_callback(
			'!
				/\* (\$wgDBprefix|[_i]) \*/ (\w*) | # 1-2. tableName, indexName
				\'\{\$ (\w+) }\'                  | # 3. addQuotes
				`\{\$ (\w+) }`                    | # 4. addIdentifierQuotes
				/\*\$ (\w+) \*/                     # 5. leave unencoded
			!x',
			function ( $m ) use ( $that, $vars ) {
				// Note: Because of <https://bugs.php.net/bug.php?id=51881>,
				// check for both nonexistent keys *and* the empty string.
				if ( isset( $m[1] ) && $m[1] !== '' ) {
					if ( $m[1] === 'i' ) {
						return $that->indexName( $m[2] );
					} else {
						return $that->tableName( $m[2] );
					}
				} elseif ( isset( $m[3] ) && $m[3] !== '' && array_key_exists( $m[3], $vars ) ) {
					return $that->addQuotes( $vars[$m[3]] );
				} elseif ( isset( $m[4] ) && $m[4] !== '' && array_key_exists( $m[4], $vars ) ) {
					return $that->addIdentifierQuotes( $vars[$m[4]] );
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
		return array();
	}

	/**
	 * Check to see if a named lock is available (non-blocking)
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method ) {
		return true;
	}

	/**
	 * Acquire a named lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to aquire
	 * @param string $method Name of method calling us
	 * @param int $timeout
	 * @return bool
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		return true;
	}

	/**
	 * Release a lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to release
	 * @param string $method Name of method calling us
	 *
	 * @return int Returns 1 if the lock was released, 0 if the lock was not established
	 * by this thread (in which case the lock is not released), and NULL if the named
	 * lock did not exist
	 */
	public function unlock( $lockName, $method ) {
		return true;
	}

	/**
	 * Check to see if a named lock used by lock() use blocking queues
	 *
	 * @return bool
	 * @since 1.26
	 */
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

	/**
	 * Find out when 'infinity' is. Most DBMSes support this. This is a special
	 * keyword for timestamps in PostgreSQL, and works with CHAR(14) as well
	 * because "i" sorts after all numbers.
	 *
	 * @return string
	 */
	public function getInfinity() {
		return 'infinity';
	}

	/**
	 * Encode an expiry time into the DBMS dependent format
	 *
	 * @param string $expiry Timestamp for expiry, or the 'infinity' string
	 * @return string
	 */
	public function encodeExpiry( $expiry ) {
		return ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() )
			? $this->getInfinity()
			: $this->timestamp( $expiry );
	}

	/**
	 * Decode an expiry time into a DBMS independent format
	 *
	 * @param string $expiry DB timestamp field value for expiry
	 * @param int $format TS_* constant, defaults to TS_MW
	 * @return string
	 */
	public function decodeExpiry( $expiry, $format = TS_MW ) {
		return ( $expiry == '' || $expiry == 'infinity' || $expiry == $this->getInfinity() )
			? 'infinity'
			: wfTimestamp( $format, $expiry );
	}

	/**
	 * Allow or deny "big selects" for this session only. This is done by setting
	 * the sql_big_selects session variable.
	 *
	 * This is a MySQL-specific feature.
	 *
	 * @param bool|string $value True for allow, false for deny, or "default" to
	 *   restore the initial value
	 */
	public function setBigSelects( $value = true ) {
		// no-op
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
			$callers = array();
			foreach ( $this->mTrxIdleCallbacks as $callbackInfo ) {
				$callers[] = $callbackInfo[1];
			}
			$callers = implode( ', ', $callers );
			trigger_error( "DB transaction callbacks still pending (from $callers)." );
		}
	}
}
