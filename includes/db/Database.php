<?php
/**
 * @defgroup Database Database
 *
 * @file
 * @ingroup Database
 * This file deals with database interface functions
 * and query specifics/optimisations
 */

/** Number of times to re-try an operation in case of deadlock */
define( 'DEADLOCK_TRIES', 4 );
/** Minimum time to wait before retry, in microseconds */
define( 'DEADLOCK_DELAY_MIN', 500000 );
/** Maximum time to wait before retry */
define( 'DEADLOCK_DELAY_MAX', 1500000 );

/**
 * Base interface for all DBMS-specific code. At a bare minimum, all of the
 * following must be implemented to support MediaWiki
 *
 * @file
 * @ingroup Database
 */
interface DatabaseType {
	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 *
	 * @return string
	 */
	function getType();

	/**
	 * Open a connection to the database. Usually aborts on failure
	 *
	 * @param $server String: database server host
	 * @param $user String: database user name
	 * @param $password String: database user password
	 * @param $dbName String: database name
	 * @return bool
	 * @throws DBConnectionError
	 */
	function open( $server, $user, $password, $dbName );

	/**
	 * The DBMS-dependent part of query()
	 * @todo Fixme: Make this private someday
	 *
	 * @param  $sql String: SQL query.
	 * @return Result object to feed to fetchObject, fetchRow, ...; or false on failure
	 * @private
	 */
	function doQuery( $sql );

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @param $res SQL result object as returned from DatabaseBase::query(), etc.
	 * @return Row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchObject( $res );

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form.  Fields are retrieved with $row['fieldname'].
	 *
	 * @param $res SQL result object as returned from DatabaseBase::query(), etc.
	 * @return Row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchRow( $res );

	/**
	 * Get the number of rows in a result object
	 *
	 * @param $res Mixed: A SQL result
	 * @return int
	 */
	function numRows( $res );

	/**
	 * Get the number of fields in a result object
	 * @see http://www.php.net/mysql_num_fields
	 *
	 * @param $res Mixed: A SQL result
	 * @return int
	 */
	function numFields( $res );

	/**
	 * Get a field name in a result object
	 * @see http://www.php.net/mysql_field_name
	 *
	 * @param $res Mixed: A SQL result
	 * @param $n Integer
	 * @return string
	 */
	function fieldName( $res, $n );

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue('page_page_id_seq');
	 * $dbw->insert('page',array('page_id' => $id));
	 * $id = $dbw->insertId();
	 *
	 * @return int
	 */
	function insertId();

	/**
	 * Change the position of the cursor in a result object
	 * @see http://www.php.net/mysql_data_seek
	 *
	 * @param $res Mixed: A SQL result
	 * @param $row Mixed: Either MySQL row or ResultWrapper
	 */
	function dataSeek( $res, $row );

	/**
	 * Get the last error number
	 * @see http://www.php.net/mysql_errno
	 *
	 * @return int
	 */
	function lastErrno();

	/**
	 * Get a description of the last error
	 * @see http://www.php.net/mysql_error
	 *
	 * @return string
	 */
	function lastError();

	/**
	 * mysql_fetch_field() wrapper
	 * Returns false if the field doesn't exist
	 *
	 * @param $table string: table name
	 * @param $field string: field name
	 */
	function fieldInfo( $table, $field );

	/**
	 * Get information about an index into an object
	 * @param $table string: Table name
	 * @param $index string: Index name
	 * @param $fname string: Calling function name
	 * @return Mixed: Database-specific index description class or false if the index does not exist
	 */
	function indexInfo( $table, $index, $fname = 'Database::indexInfo' );

	/**
	 * Get the number of rows affected by the last write query
	 * @see http://www.php.net/mysql_affected_rows
	 *
	 * @return int
	 */
	function affectedRows();

	/**
	 * Wrapper for addslashes()
	 *
	 * @param $s string: to be slashed.
	 * @return string: slashed string.
	 */
	function strencode( $s );

	/**
	 * Returns a wikitext link to the DB's website, e.g.,
	 *     return "[http://www.mysql.com/ MySQL]";
	 * Should at least contain plain text, if for some reason
	 * your database has no website.
	 *
	 * @return string: wikitext of a link to the server software's web site
	 */
	static function getSoftwareLink();

	/**
	 * A string describing the current software version, like from
	 * mysql_get_server_info().
	 *
	 * @return string: Version information from the database server.
	 */
	function getServerVersion();

	/**
	 * A string describing the current software version, and possibly
	 * other details in a user-friendly way.  Will be listed on Special:Version, etc.
	 * Use getServerVersion() to get machine-friendly information.
	 *
	 * @return string: Version information from the database server
	 */
	function getServerInfo();
}

/**
 * Database abstraction object
 * @ingroup Database
 */
abstract class DatabaseBase implements DatabaseType {

# ------------------------------------------------------------------------------
# Variables
# ------------------------------------------------------------------------------

	protected $mLastQuery = '';
	protected $mDoneWrites = false;
	protected $mPHPError = false;

	protected $mServer, $mUser, $mPassword, $mConn = null, $mDBname;
	protected $mOpened = false;

	protected $mTablePrefix;
	protected $mFlags;
	protected $mTrxLevel = 0;
	protected $mErrorCount = 0;
	protected $mLBInfo = array();
	protected $mFakeSlaveLag = null, $mFakeMaster = false;
	protected $mDefaultBigSelects = null;

# ------------------------------------------------------------------------------
# Accessors
# ------------------------------------------------------------------------------
	# These optionally set a variable and return the previous state

	/**
	 * A string describing the current software version, and possibly
	 * other details in a user-friendly way.  Will be listed on Special:Version, etc.
	 * Use getServerVersion() to get machine-friendly information.
	 *
	 * @return string: Version information from the database server
	 */
	public function getServerInfo() {
		return $this->getServerVersion();
	}

	/**
	 * Boolean, controls output of large amounts of debug information
	 */
	function debug( $debug = null ) {
		return wfSetBit( $this->mFlags, DBO_DEBUG, $debug );
	}

	/**
	 * Turns buffering of SQL result sets on (true) or off (false).
	 * Default is "on" and it should not be changed without good reasons.
	 */
	function bufferResults( $buffer = null ) {
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
	 */
	function ignoreErrors( $ignoreErrors = null ) {
		return wfSetBit( $this->mFlags, DBO_IGNORE, $ignoreErrors );
	}

	/**
	 * The current depth of nested transactions
	 * @param $level Integer: , default NULL.
	 */
	function trxLevel( $level = null ) {
		return wfSetVar( $this->mTrxLevel, $level );
	}

	/**
	 * Number of errors logged, only useful when errors are ignored
	 */
	function errorCount( $count = null ) {
		return wfSetVar( $this->mErrorCount, $count );
	}

	function tablePrefix( $prefix = null ) {
		return wfSetVar( $this->mTablePrefix, $prefix, true );
	}

	/**
	 * Properties passed down from the server info array of the load balancer
	 */
	function getLBInfo( $name = null ) {
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

	function setLBInfo( $name, $value = null ) {
		if ( is_null( $value ) ) {
			$this->mLBInfo = $name;
		} else {
			$this->mLBInfo[$name] = $value;
		}
	}

	/**
	 * Set lag time in seconds for a fake slave
	 */
	function setFakeSlaveLag( $lag ) {
		$this->mFakeSlaveLag = $lag;
	}

	/**
	 * Make this connection a fake master
	 */
	function setFakeMaster( $enabled = true ) {
		$this->mFakeMaster = $enabled;
	}

	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 */
	function cascadingDeletes() {
		return false;
	}

	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the page table)
	 */
	function cleanupTriggers() {
		return false;
	}

	/**
	 * Returns true if this database is strict about what can be put into an IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 */
	function strictIPs() {
		return false;
	}

	/**
	 * Returns true if this database uses timestamps rather than integers
	*/
	function realTimestamps() {
		return false;
	}

	/**
	 * Returns true if this database does an implicit sort when doing GROUP BY
	 */
	function implicitGroupby() {
		return true;
	}

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 */
	function implicitOrderby() {
		return true;
	}

	/**
	 * Returns true if this database requires that SELECT DISTINCT queries require that all
	   ORDER BY expressions occur in the SELECT list per the SQL92 standard
	 */
	function standardSelectDistinct() {
		return true;
	}

	/**
	 * Returns true if this database can do a native search on IP columns
	 * e.g. this works as expected: .. WHERE rc_ip = '127.42.12.102/32';
	 */
	function searchableIPs() {
		return false;
	}

	/**
	 * Returns true if this database can use functional indexes
	 */
	function functionalIndexes() {
		return false;
	}

	/**
	 * Return the last query that went through DatabaseBase::query()
	 * @return String
	 */
	function lastQuery() { return $this->mLastQuery; }


	/**
	 * Returns true if the connection may have been used for write queries.
	 * Should return true if unsure.
	 */
	function doneWrites() { return $this->mDoneWrites; }

	/**
	 * Is a connection to the database open?
	 * @return Boolean
	 */
	function isOpen() { return $this->mOpened; }

	/**
	 * Set a flag for this connection
	 *
	 * @param $flag Integer: DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_IGNORE: ignore errors (same as ignoreErrors())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_DEFAULT: automatically sets DBO_TRX if not in command line mode
	 *       and removes it in command line mode
	 *   - DBO_PERSISTENT: use persistant database connection
	 */
	function setFlag( $flag ) {
		$this->mFlags |= $flag;
	}

	/**
	 * Clear a flag for this connection
	 *
	 * @param $flag: same as setFlag()'s $flag param
	 */
	function clearFlag( $flag ) {
		$this->mFlags &= ~$flag;
	}

	/**
	 * Returns a boolean whether the flag $flag is set for this connection
	 *
	 * @param $flag: same as setFlag()'s $flag param
	 * @return Boolean
	 */
	function getFlag( $flag ) {
		return !!( $this->mFlags & $flag );
	}

	/**
	 * General read-only accessor
	 */
	function getProperty( $name ) {
		return $this->$name;
	}

	function getWikiID() {
		if ( $this->mTablePrefix ) {
			return "{$this->mDBname}-{$this->mTablePrefix}";
		} else {
			return $this->mDBname;
		}
	}

	/**
	 * Return a path to the DBMS-specific schema, otherwise default to tables.sql
	 */
	public function getSchema() {
		global $IP;
		if ( file_exists( "$IP/maintenance/" . $this->getType() . "/tables.sql" ) ) {
			return "$IP/maintenance/" . $this->getType() . "/tables.sql";
		} else {
			return "$IP/maintenance/tables.sql";
		}
	}

# ------------------------------------------------------------------------------
# Other functions
# ------------------------------------------------------------------------------

	/**
	 * Constructor.
	 * @param $server String: database server host
	 * @param $user String: database user name
	 * @param $password String: database user password
	 * @param $dbName String: database name
	 * @param $flags
	 * @param $tablePrefix String: database table prefixes. By default use the prefix gave in LocalSettings.php
	 */
	function __construct( $server = false, $user = false, $password = false, $dbName = false,
		$flags = 0, $tablePrefix = 'get from global'
	) {
		global $wgOut, $wgDBprefix, $wgCommandLineMode;

		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = null;
		}
		$this->mFlags = $flags;

		if ( $this->mFlags & DBO_DEFAULT ) {
			if ( $wgCommandLineMode ) {
				$this->mFlags &= ~DBO_TRX;
			} else {
				$this->mFlags |= DBO_TRX;
			}
		}

		/*
		// Faster read-only access
		if ( wfReadOnly() ) {
			$this->mFlags |= DBO_PERSISTENT;
			$this->mFlags &= ~DBO_TRX;
		}*/

		/** Get the default table prefix*/
		if ( $tablePrefix == 'get from global' ) {
			$this->mTablePrefix = $wgDBprefix;
		} else {
			$this->mTablePrefix = $tablePrefix;
		}

		if ( $server ) {
			$this->open( $server, $user, $password, $dbName );
		}
	}

	/**
	 * Same as new DatabaseMysql( ... ), kept for backward compatibility
	 * @param $server String: database server host
	 * @param $user String: database user name
	 * @param $password String: database user password
	 * @param $dbName String: database name
	 * @param $flags
	 */
	static function newFromParams( $server, $user, $password, $dbName, $flags = 0 ) {
		wfDeprecated( __METHOD__ );
		return new DatabaseMysql( $server, $user, $password, $dbName, $flags );
	}

	protected function installErrorHandler() {
		$this->mPHPError = false;
		$this->htmlErrors = ini_set( 'html_errors', '0' );
		set_error_handler( array( $this, 'connectionErrorHandler' ) );
	}

	protected function restoreErrorHandler() {
		restore_error_handler();
		if ( $this->htmlErrors !== false ) {
			ini_set( 'html_errors', $this->htmlErrors );
		}
		if ( $this->mPHPError ) {
			$error = preg_replace( '!\[<a.*</a>\]!', '', $this->mPHPError );
			$error = preg_replace( '!^.*?:(.*)$!', '$1', $error );
			return $error;
		} else {
			return false;
		}
	}

	protected function connectionErrorHandler( $errno,  $errstr ) {
		$this->mPHPError = $errstr;
	}

	/**
	 * Closes a database connection.
	 * if it is open : commits any open transactions
	 *
	 * @return Bool operation success. true if already closed.
	 */
	function close() {
		# Stub, should probably be overridden
		return true;
	}

	/**
	 * @param $error String: fallback error message, used if none is given by DB
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
	 * Determine whether a query writes to the DB.
	 * Should return true if unsure.
	 */
	function isWriteQuery( $sql ) {
		return !preg_match( '/^(?:SELECT|BEGIN|COMMIT|SET|SHOW|\(SELECT)\b/i', $sql );
	}

	/**
	 * Usually aborts on failure.  If errors are explicitly ignored, returns success.
	 *
	 * @param  $sql        String: SQL query
	 * @param  $fname      String: Name of the calling function, for profiling/SHOW PROCESSLIST
	 *     comment (you can use __METHOD__ or add some extra info)
	 * @param  $tempIgnore Boolean:   Whether to avoid throwing an exception on errors...
	 *     maybe best to catch the exception instead?
	 * @return boolean or ResultWrapper. true for a successful write query, ResultWrapper object for a successful read query,
	 *     or false on failure if $tempIgnore set
	 * @throws DBQueryError Thrown when the database returns an error of any kind
	 */
	public function query( $sql, $fname = '', $tempIgnore = false ) {
		global $wgProfiler;

		$isMaster = !is_null( $this->getLBInfo( 'master' ) );
		if ( isset( $wgProfiler ) ) {
			# generalizeSQL will probably cut down the query to reasonable
			# logging size most of the time. The substr is really just a sanity check.

			# Who's been wasting my precious column space? -- TS
			# $profName = 'query: ' . $fname . ' ' . substr( DatabaseBase::generalizeSQL( $sql ), 0, 255 );

			if ( $isMaster ) {
				$queryProf = 'query-m: ' . substr( DatabaseBase::generalizeSQL( $sql ), 0, 255 );
				$totalProf = 'DatabaseBase::query-master';
			} else {
				$queryProf = 'query: ' . substr( DatabaseBase::generalizeSQL( $sql ), 0, 255 );
				$totalProf = 'DatabaseBase::query';
			}

			wfProfileIn( $totalProf );
			wfProfileIn( $queryProf );
		}

		$this->mLastQuery = $sql;
		if ( !$this->mDoneWrites && $this->isWriteQuery( $sql ) ) {
			// Set a flag indicating that writes have been done
			wfDebug( __METHOD__ . ": Writes done: $sql\n" );
			$this->mDoneWrites = true;
		}

		# Add a comment for easy SHOW PROCESSLIST interpretation
		# if ( $fname ) {
			global $wgUser;
			if ( is_object( $wgUser ) && $wgUser->mDataLoaded ) {
				$userName = $wgUser->getName();
				if ( mb_strlen( $userName ) > 15 ) {
					$userName = mb_substr( $userName, 0, 15 ) . '...';
				}
				$userName = str_replace( '/', '', $userName );
			} else {
				$userName = '';
			}
			$commentedSql = preg_replace( '/\s/', " /* $fname $userName */ ", $sql, 1 );
		# } else {
		#	$commentedSql = $sql;
		# }

		# If DBO_TRX is set, start a transaction
		if ( ( $this->mFlags & DBO_TRX ) && !$this->trxLevel() &&
			$sql != 'BEGIN' && $sql != 'COMMIT' && $sql != 'ROLLBACK' ) {
			// avoid establishing transactions for SHOW and SET statements too -
			// that would delay transaction initializations to once connection
			// is really used by application
			$sqlstart = substr( $sql, 0, 10 ); // very much worth it, benchmark certified(tm)
			if ( strpos( $sqlstart, "SHOW " ) !== 0 and strpos( $sqlstart, "SET " ) !== 0 )
				$this->begin();
		}

		if ( $this->debug() ) {
			static $cnt = 0;

			$cnt++;
			$sqlx = substr( $commentedSql, 0, 500 );
			$sqlx = strtr( $sqlx, "\t\n", '  ' );

			if ( $isMaster ) {
				wfDebug( "Query $cnt (master): $sqlx\n" );
			} else {
				wfDebug( "Query $cnt (slave): $sqlx\n" );
			}
		}

		if ( istainted( $sql ) & TC_MYSQL ) {
			throw new MWException( 'Tainted query found' );
		}

		# Do the query and handle errors
		$ret = $this->doQuery( $commentedSql );

		# Try reconnecting if the connection was lost
		if ( false === $ret && $this->wasErrorReissuable() ) {
			# Transaction is gone, like it or not
			$this->mTrxLevel = 0;
			wfDebug( "Connection lost, reconnecting...\n" );

			if ( $this->ping() ) {
				wfDebug( "Reconnected\n" );
				$sqlx = substr( $commentedSql, 0, 500 );
				$sqlx = strtr( $sqlx, "\t\n", '  ' );
				global $wgRequestTime;
				$elapsed = round( microtime( true ) - $wgRequestTime, 3 );
				wfLogDBError( "Connection lost and reconnected after {$elapsed}s, query: $sqlx\n" );
				$ret = $this->doQuery( $commentedSql );
			} else {
				wfDebug( "Failed\n" );
			}
		}

		if ( false === $ret ) {
			$this->reportQueryError( $this->lastError(), $this->lastErrno(), $sql, $fname, $tempIgnore );
		}

		if ( isset( $wgProfiler ) ) {
			wfProfileOut( $queryProf );
			wfProfileOut( $totalProf );
		}

		return $this->resultObject( $ret );
	}

	/**
	 * @param $error String
	 * @param $errno Integer
	 * @param $sql String
	 * @param $fname String
	 * @param $tempIgnore Boolean
	 */
	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		# Ignore errors during error handling to avoid infinite recursion
		$ignore = $this->ignoreErrors( true );
		++$this->mErrorCount;

		if ( $ignore || $tempIgnore ) {
			wfDebug( "SQL ERROR (ignored): $error\n" );
			$this->ignoreErrors( $ignore );
		} else {
			$sql1line = str_replace( "\n", "\\n", $sql );
			wfLogDBError( "$fname\t{$this->mServer}\t$errno\t$error\t$sql1line\n" );
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
	 */
	function prepare( $sql, $func = 'DatabaseBase::prepare' ) {
		/* MySQL doesn't support prepared statements (yet), so just
		   pack up the query for reference. We'll manually replace
		   the bits later. */
		return array( 'query' => $sql, 'func' => $func );
	}

	function freePrepared( $prepared ) {
		/* No-op by default */
	}

	/**
	 * Execute a prepared query with the various arguments
	 * @param $prepared String: the prepared sql
	 * @param $args Mixed: Either an array here, or put scalars as varargs
	 */
	function execute( $prepared, $args = null ) {
		if ( !is_array( $args ) ) {
			# Pull the var args
			$args = func_get_args();
			array_shift( $args );
		}

		$sql = $this->fillPrepared( $prepared['query'], $args );

		return $this->query( $sql, $prepared['func'] );
	}

	/**
	 * Prepare & execute an SQL statement, quoting and inserting arguments
	 * in the appropriate places.
	 * @param $query String
	 * @param $args ...
	 */
	function safeQuery( $query, $args = null ) {
		$prepared = $this->prepare( $query, 'DatabaseBase::safeQuery' );

		if ( !is_array( $args ) ) {
			# Pull the var args
			$args = func_get_args();
			array_shift( $args );
		}

		$retval = $this->execute( $prepared, $args );
		$this->freePrepared( $prepared );

		return $retval;
	}

	/**
	 * For faking prepared SQL statements on DBs that don't support
	 * it directly.
	 * @param $preparedQuery String: a 'preparable' SQL statement
	 * @param $args Array of arguments to fill it with
	 * @return string executable SQL
	 */
	function fillPrepared( $preparedQuery, $args ) {
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
	 * @param $matches Array
	 * @return String
	 * @private
	 */
	function fillPreparedArg( $matches ) {
		switch( $matches[1] ) {
			case '\\?': return '?';
			case '\\!': return '!';
			case '\\&': return '&';
		}

		list( /* $n */ , $arg ) = each( $this->preparedArgs );

		switch( $matches[1] ) {
			case '?': return $this->addQuotes( $arg );
			case '!': return $arg;
			case '&':
				# return $this->addQuotes( file_get_contents( $arg ) );
				throw new DBUnexpectedError( $this, '& mode is not implemented. If it\'s really needed, uncomment the line above.' );
			default:
				throw new DBUnexpectedError( $this, 'Received invalid match. This should never happen!' );
		}
	}

	/**
	 * Free a result object
	 * @param $res Mixed: A SQL result
	 */
	function freeResult( $res ) {
		# Stub.  Might not really need to be overridden, since results should
		# be freed by PHP when the variable goes out of scope anyway.
	}

	/**
	 * Simple UPDATE wrapper
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 *
	 * This function exists for historical reasons, DatabaseBase::update() has a more standard
	 * calling convention and feature set
	 */
	function set( $table, $var, $value, $cond, $fname = 'DatabaseBase::set' ) {
		$table = $this->tableName( $table );
		$sql = "UPDATE $table SET $var = '" .
		  $this->strencode( $value ) . "' WHERE ($cond)";

		return (bool)$this->query( $sql, $fname );
	}

	/**
	 * Simple SELECT wrapper, returns a single field, input must be encoded
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns FALSE on failure
	 */
	function selectField( $table, $var, $cond = '', $fname = 'DatabaseBase::selectField', $options = array() ) {
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
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query
	 *
	 * @private
	 *
	 * @param $options Array: associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return Array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = '';

		$noKeyOptions = array();

		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) {
			$preLimitTail .= " GROUP BY {$options['GROUP BY']}";
		}

		if ( isset( $options['HAVING'] ) ) {
			$preLimitTail .= " HAVING {$options['HAVING']}";
		}

		if ( isset( $options['ORDER BY'] ) ) {
			$preLimitTail .= " ORDER BY {$options['ORDER BY']}";
		}

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

		if ( isset( $options['USE INDEX'] ) && ! is_array( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}

		return array( $startOpts, $useIndex, $preLimitTail, $postLimitTail );
	}

	/**
	 * SELECT wrapper
	 *
	 * @param $table   Mixed:  Array or string, table name(s) (prefix auto-added)
	 * @param $vars    Mixed:  Array or string, field name(s) to be retrieved
	 * @param $conds   Mixed:  Array or string, condition(s) for WHERE
	 * @param $fname   String: Calling function name (use __METHOD__) for logs/profiling
	 * @param $options Array:  Associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *                         see DatabaseBase::makeSelectOptions code for list of supported stuff
	 * @param $join_conds Array: Associative array of table join conditions (optional)
	 *                           (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return mixed Database result resource (feed to DatabaseBase::fetchObject or whatever), or false on failure
	 */
	function select( $table, $vars, $conds = '', $fname = 'DatabaseBase::select', $options = array(), $join_conds = array() ) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		return $this->query( $sql, $fname );
	}

	/**
	 * SELECT wrapper
	 *
	 * @param $table   Mixed:  Array or string, table name(s) (prefix auto-added). Array keys are table aliases (optional)
	 * @param $vars    Mixed:  Array or string, field name(s) to be retrieved
	 * @param $conds   Mixed:  Array or string, condition(s) for WHERE
	 * @param $fname   String: Calling function name (use __METHOD__) for logs/profiling
	 * @param $options Array:  Associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *                         see DatabaseBase::makeSelectOptions code for list of supported stuff
	 * @param $join_conds Array: Associative array of table join conditions (optional)
	 *                           (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return string, the SQL text
	 */
	function selectSQLText( $table, $vars, $conds = '', $fname = 'DatabaseBase::select', $options = array(), $join_conds = array() ) {
		if ( is_array( $vars ) ) {
			$vars = implode( ',', $vars );
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		if ( is_array( $table ) ) {
			if ( !empty( $join_conds ) || ( isset( $options['USE INDEX'] ) && is_array( @$options['USE INDEX'] ) ) ) {
				$from = ' FROM ' . $this->tableNamesWithUseIndexOrJOIN( $table, @$options['USE INDEX'], $join_conds );
			} else {
				$from = ' FROM ' . implode( ',', $this->tableNamesWithAlias( $table ) );
			}
		} elseif ( $table != '' ) {
			if ( $table { 0 } == ' ' ) {
				$from = ' FROM ' . $table;
			} else {
				$from = ' FROM ' . $this->tableName( $table );
			}
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $preLimitTail, $postLimitTail ) = $this->makeSelectOptions( $options );

		if ( !empty( $conds ) ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
			}
			$sql = "SELECT $startOpts $vars $from $useIndex WHERE $conds $preLimitTail";
		} else {
			$sql = "SELECT $startOpts $vars $from $useIndex $preLimitTail";
		}

		if ( isset( $options['LIMIT'] ) )
			$sql = $this->limitResult( $sql, $options['LIMIT'],
				isset( $options['OFFSET'] ) ? $options['OFFSET'] : false );
		$sql = "$sql $postLimitTail";

		if ( isset( $options['EXPLAIN'] ) ) {
			$sql = 'EXPLAIN ' . $sql;
		}

		return $sql;
	}

	/**
	 * Single row SELECT wrapper
	 * Aborts or returns FALSE on error
	 *
	 * @param $table String: table name
	 * @param $vars String: the selected variables
	 * @param $conds Array: a condition map, terms are ANDed together.
	 *   Items with numeric keys are taken to be literal conditions
	 * Takes an array of selected variables, and a condition map, which is ANDed
	 * e.g: selectRow( "page", array( "page_id" ), array( "page_namespace" =>
	 * NS_MAIN, "page_title" => "Astronomy" ) )   would return an object where
	 * $obj- >page_id is the ID of the Astronomy article
	 * @param $fname String: Calling function name
	 * @param $options Array
	 * @param $join_conds Array
	 *
	 * @todo migrate documentation to phpdocumentor format
	 */
	function selectRow( $table, $vars, $conds, $fname = 'DatabaseBase::selectRow', $options = array(), $join_conds = array() ) {
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
	 * Estimate rows in dataset
	 * Returns estimated count - not necessarily an accurate estimate across different databases,
	 * so use sparingly
	 * Takes same arguments as DatabaseBase::select()
	 *
	 * @param $table String: table name
	 * @param $vars Array: unused
	 * @param $conds Array: filters on the table
	 * @param $fname String: function name for profiling
	 * @param $options Array: options for select
	 * @return Integer: row count
	 */
	public function estimateRowCount( $table, $vars = '*', $conds = '', $fname = 'DatabaseBase::estimateRowCount', $options = array() ) {
		$rows = 0;
		$res = $this->select ( $table, 'COUNT(*) AS rowcount', $conds, $fname, $options );

		if ( $res ) {
			$row = $this->fetchRow( $res );
			$rows = ( isset( $row['rowcount'] ) ) ? $row['rowcount'] : 0;
		}

		return $rows;
	}

	/**
	 * Removes most variables from an SQL query and replaces them with X or N for numbers.
	 * It's only slightly flawed. Don't use for anything important.
	 *
	 * @param $sql String: A SQL Query
	 */
	static function generalizeSQL( $sql ) {
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace ( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql);

		$sql = str_replace ( "\\\\", '', $sql );
		$sql = str_replace ( "\\'", '', $sql );
		$sql = str_replace ( "\\\"", '', $sql );
		$sql = preg_replace ( "/'.*'/s", "'X'", $sql );
		$sql = preg_replace ( '/".*"/s', "'X'", $sql );

		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace ( '/\s+/', ' ', $sql );

		# All numbers => N
		$sql = preg_replace ( '/-?[0-9]+/s', 'N', $sql );

		return $sql;
	}

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param $table String: table name
	 * @param $field String: filed to check on that table
	 * @param $fname String: calling function name (optional)
	 * @return Boolean: whether $table has filed $field
	 */
	function fieldExists( $table, $field, $fname = 'DatabaseBase::fieldExists' ) {
		$info = $this->fieldInfo( $table, $field );

		return (bool)$info;
	}

	/**
	 * Determines whether an index exists
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexExists( $table, $index, $fname = 'DatabaseBase::indexExists' ) {
		$info = $this->indexInfo( $table, $index, $fname );
		if ( is_null( $info ) ) {
			return null;
		} else {
			return $info !== false;
		}
	}

	/**
	 * Query whether a given table exists
	 */
	function tableExists( $table ) {
		$table = $this->tableName( $table );
		$old = $this->ignoreErrors( true );
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1", __METHOD__ );
		$this->ignoreErrors( $old );

		return (bool)$res;
	}

	/**
	 * mysql_field_type() wrapper
	 */
	function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return mysql_field_type( $res, $index );
	}

	/**
	 * Determines if a given index is unique
	 */
	function indexUnique( $table, $index ) {
		$indexInfo = $this->indexInfo( $table, $index );

		if ( !$indexInfo ) {
			return null;
		}

		return !$indexInfo[0]->Non_unique;
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $a may be a single associative array, or an array of these with numeric keys, for
	 * multi-row insert.
	 *
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 *
	 * @param $table   String: table name (prefix auto-added)
	 * @param $a	   Array: Array of rows to insert
	 * @param $fname   String: Calling function name (use __METHOD__) for logs/profiling
	 * @param $options Mixed: Associative array of options
	 *
	 * @return bool
	 */
	function insert( $table, $a, $fname = 'DatabaseBase::insert', $options = array() ) {
		# No rows to insert, easy just return now
		if ( !count( $a ) ) {
			return true;
		}

		$table = $this->tableName( $table );

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		if ( isset( $a[0] ) && is_array( $a[0] ) ) {
			$multi = true;
			$keys = array_keys( $a[0] );
		} else {
			$multi = false;
			$keys = array_keys( $a );
		}

		$sql = 'INSERT ' . implode( ' ', $options ) .
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

		return (bool)$this->query( $sql, $fname );
	}

	/**
	 * Make UPDATE options for the DatabaseBase::update function
	 *
	 * @private
	 * @param $options Array: The options passed to DatabaseBase::update
	 * @return string
	 */
	function makeUpdateOptions( $options ) {
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

		return implode( ' ', $opts );
	}

	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 *
	 * @param $table  String: The table to UPDATE
	 * @param $values Array:  An array of values to SET
	 * @param $conds  Array:  An array of conditions (WHERE). Use '*' to update all rows.
	 * @param $fname  String: The Class::Function calling this function
	 *                        (for the log)
	 * @param $options Array: An array of UPDATE options, can be one or
	 *                        more of IGNORE, LOW_PRIORITY
	 * @return Boolean
	 */
	function update( $table, $values, $conds, $fname = 'DatabaseBase::update', $options = array() ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET );

		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * Makes an encoded list of strings from an array
	 * $mode:
	 *        LIST_COMMA         - comma separated, no field names
	 *        LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *        LIST_OR            - ORed WHERE clause (without the WHERE)
	 *        LIST_SET           - comma separated with field names, like a SET clause
	 *        LIST_NAMES         - comma separated field names
	 */
	function makeList( $a, $mode = LIST_COMMA ) {
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
				if ( count( $value ) == 0 ) {
					throw new MWException( __METHOD__ . ': empty input' );
				} elseif ( count( $value ) == 1 ) {
					// Special-case single values, as IN isn't terribly efficient
					// Don't necessarily assume the single key is 0; we don't
					// enforce linear numeric ordering on other arrays here.
					$value = array_values( $value );
					$list .= $field . " = " . $this->addQuotes( $value[0] );
				} else {
					$list .= $field . " IN (" . $this->makeList( $value ) . ") ";
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
	 * @param $data Array: organized as 2-d array(baseKeyVal => array(subKeyVal => <ignored>, ...), ...)
	 * @param $baseKey String: field name to match the base-level keys to (eg 'pl_namespace')
	 * @param $subKey String: field name to match the sub-level keys to (eg 'pl_title')
	 * @return Mixed: string SQL fragment, or false if no items in array.
	 */
	function makeWhereFrom2d( $data, $baseKey, $subKey ) {
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
	 * Bitwise operations
	 */

	function bitNot( $field ) {
		return "(~$field)";
	}

	function bitAnd( $fieldLeft, $fieldRight ) {
		return "($fieldLeft & $fieldRight)";
	}

	function bitOr( $fieldLeft, $fieldRight ) {
		return "($fieldLeft | $fieldRight)";
	}

	/**
	 * Change the current database
	 *
	 * @todo Explain what exactly will fail if this is not overridden.
	 * @return bool Success or failure
	 */
	function selectDB( $db ) {
		# Stub.  Shouldn't cause serious problems if it's not overridden, but
		# if your database engine supports a concept similar to MySQL's
		# databases you may as well.
		return true;
	}

	/**
	 * Get the current DB name
	 */
	function getDBname() {
		return $this->mDBname;
	}

	/**
	 * Get the server hostname or IP address
	 */
	function getServer() {
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
	 * @param $name String: database table name
	 * @return String: full database name
	 */
	function tableName( $name ) {
		global $wgSharedDB, $wgSharedPrefix, $wgSharedTables;
		# Skip the entire process when we have a string quoted on both ends.
		# Note that we check the end so that we will still quote any use of
		# use of `database`.table. But won't break things if someone wants
		# to query a database table with a dot in the name.
		if ( $name[0] == '`' && substr( $name, -1, 1 ) == '`' ) {
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
		$dbDetails = array_reverse( explode( '.', $name, 2 ) );
		if ( isset( $dbDetails[1] ) ) {
			@list( $table, $database ) = $dbDetails;
		} else {
			@list( $table ) = $dbDetails;
		}
		$prefix = $this->mTablePrefix; # Default prefix

		# A database name has been specified in input. Quote the table name
		# because we don't want any prefixes added.
		if ( isset( $database ) ) {
			$table = ( $table[0] == '`' ? $table : "`{$table}`" );
		}

		# Note that we use the long format because php will complain in in_array if
		# the input is not an array, and will complain in is_array if it is not set.
		if ( !isset( $database ) # Don't use shared database if pre selected.
		 && isset( $wgSharedDB ) # We have a shared database
		 && $table[0] != '`' # Paranoia check to prevent shared tables listing '`table`'
		 && isset( $wgSharedTables )
		 && is_array( $wgSharedTables )
		 && in_array( $table, $wgSharedTables ) ) { # A shared table is selected
			$database = $wgSharedDB;
			$prefix   = isset( $wgSharedPrefix ) ? $wgSharedPrefix : $prefix;
		}

		# Quote the $database and $table and apply the prefix if not quoted.
		if ( isset( $database ) ) {
			$database = ( $database[0] == '`' ? $database : "`{$database}`" );
		}
		$table = ( $table[0] == '`' ? $table : "`{$prefix}{$table}`" );

		# Merge our database and table into our final table name.
		$tableName = ( isset( $database ) ? "{$database}.{$table}" : "{$table}" );

		return $tableName;
	}

	/**
	 * Fetch a number of table names into an array
	 * This is handy when you need to construct SQL for joins
	 *
	 * Example:
	 * extract($dbr->tableNames('user','watchlist'));
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
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
	 * list( $user, $watchlist ) = $dbr->tableNamesN('user','watchlist');
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
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
	 * @param $name string Table name, see tableName()
	 * @param $alias string Alias (optional)
	 * @return string SQL name for aliased table. Will not alias a table to its own name
	 */
	public function tableNameWithAlias( $name, $alias = false ) {
		if ( !$alias || $alias == $name ) {
			return $this->tableName( $name );
		} else {
			return $this->tableName( $name ) . ' `' . $alias . '`';
		}
	}

	/**
	 * Gets an array of aliased table names
	 *
	 * @param $tables array( [alias] => table )
	 * @return array of strings, see tableNameWithAlias()
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
	 * @private
	 */
	function tableNamesWithUseIndexOrJOIN( $tables, $use_index = array(), $join_conds = array() ) {
		$ret = array();
		$retJOIN = array();
		$use_index_safe = is_array( $use_index ) ? $use_index : array();
		$join_conds_safe = is_array( $join_conds ) ? $join_conds : array();

		foreach ( $tables as $alias => $table ) {
			if ( !is_string( $alias ) ) {
				// No alias? Set it equal to the table name
				$alias = $table;
			}
			// Is there a JOIN and INDEX clause for this table?
			if ( isset( $join_conds_safe[$alias] ) && isset( $use_index_safe[$alias] ) ) {
				$tableClause = $join_conds_safe[$alias][0] . ' ' . $this->tableNameWithAlias( $table, $alias );
				$tableClause .= ' ' . $this->useIndexClause( implode( ',', (array)$use_index_safe[$alias] ) );
				$on = $this->makeList( (array)$join_conds_safe[$alias][1], LIST_AND );
				if ( $on != '' ) {
					$tableClause .= ' ON (' . $on . ')';
				}

				$retJOIN[] = $tableClause;
			// Is there an INDEX clause?
			} else if ( isset( $use_index_safe[$alias] ) ) {
				$tableClause = $this->tableNameWithAlias( $table, $alias );
				$tableClause .= ' ' . $this->useIndexClause( implode( ',', (array)$use_index_safe[$alias] ) );
				$ret[] = $tableClause;
			// Is there a JOIN clause?
			} else if ( isset( $join_conds_safe[$alias] ) ) {
				$tableClause = $join_conds_safe[$alias][0] . ' ' . $this->tableNameWithAlias( $table, $alias );
				$on = $this->makeList( (array)$join_conds_safe[$alias][1], LIST_AND );
				if ( $on != '' ) {
					$tableClause .= ' ON (' . $on . ')';
				}

				$retJOIN[] = $tableClause;
			} else {
				$tableClause = $this->tableNameWithAlias( $table, $alias );
				$ret[] = $tableClause;
			}
		}

		// We can't separate explicit JOIN clauses with ',', use ' ' for those
		$straightJoins = !empty( $ret ) ? implode( ',', $ret ) : "";
		$otherJoins = !empty( $retJOIN ) ? implode( ' ', $retJOIN ) : "";

		// Compile our final table clause
		return implode( ' ', array( $straightJoins, $otherJoins ) );
	}

	/**
	 * Get the name of an index in a given table
	 */
	function indexName( $index ) {
		// Backwards-compatibility hack
		$renamed = array(
			'ar_usertext_timestamp'	=> 'usertext_timestamp',
			'un_user_id'		=> 'user_id',
			'un_user_ip'		=> 'user_ip',
		);

		if ( isset( $renamed[$index] ) ) {
			return $renamed[$index];
		} else {
			return $index;
		}
	}

	/**
	 * If it's a string, adds quotes and backslashes
	 * Otherwise returns as-is
	 */
	function addQuotes( $s ) {
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
	 */ 	 
	public function addIdentifierQuotes( $s ) {
		return '"' . str_replace( '"', '""', $s ) . '"';
	}

	/**
	 * Backwards compatibility, identifier quoting originated in DatabasePostgres
	 * which used quote_ident which does not follow our naming conventions
	 * was renamed to addIdentifierQuotes.
	 * @deprecated use addIdentifierQuotes
	 */
	function quote_ident( $s ) {
		wfDeprecated( __METHOD__ );
		return $this->addIdentifierQuotes( $s );
	}

	/**
	 * Escape string for safe LIKE usage.
	 * WARNING: you should almost never use this function directly,
	 * instead use buildLike() that escapes everything automatically
	 * Deprecated in 1.17, warnings in 1.17, removed in ???
	 */
	public function escapeLike( $s ) {
		wfDeprecated( __METHOD__ );
		return $this->escapeLikeInternal( $s );
	}

	protected function escapeLikeInternal( $s ) {
		$s = str_replace( '\\', '\\\\', $s );
		$s = $this->strencode( $s );
		$s = str_replace( array( '%', '_' ), array( '\%', '\_' ), $s );

		return $s;
	}

	/**
	 * LIKE statement wrapper, receives a variable-length argument list with parts of pattern to match
	 * containing either string literals that will be escaped or tokens returned by anyChar() or anyString().
	 * Alternatively, the function could be provided with an array of aforementioned parameters.
	 *
	 * Example: $dbr->buildLike( 'My_page_title/', $dbr->anyString() ) returns a LIKE clause that searches
	 * for subpages of 'My page title'.
	 * Alternatively: $pattern = array( 'My_page_title/', $dbr->anyString() ); $query .= $dbr->buildLike( $pattern );
	 *
	 * @since 1.16
	 * @return String: fully built LIKE statement
	 */
	function buildLike() {
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

		return " LIKE '" . $s . "' ";
	}

	/**
	 * Returns a token for buildLike() that denotes a '_' to be used in a LIKE query
	 */
	function anyChar() {
		return new LikeMatch( '_' );
	}

	/**
	 * Returns a token for buildLike() that denotes a '%' to be used in a LIKE query
	 */
	function anyString() {
		return new LikeMatch( '%' );
	}

	/**
	 * Returns an appropriately quoted sequence value for inserting a new row.
	 * MySQL has autoincrement fields, so this is just NULL. But the PostgreSQL
	 * subclass will return an integer, and save the value for insertId()
	 */
	function nextSequenceValue( $seqName ) {
		return null;
	}

	/**
	 * USE INDEX clause.  Unlikely to be useful for anything but MySQL.  This
	 * is only needed because a) MySQL must be as efficient as possible due to
	 * its use on Wikipedia, and b) MySQL 4.0 is kind of dumb sometimes about
	 * which index to pick.  Anyway, other databases might have different
	 * indexes on a given table.  So don't bother overriding this unless you're
	 * MySQL.
	 */
	function useIndexClause( $index ) {
		return '';
	}

	/**
	 * REPLACE query wrapper
	 * PostgreSQL simulates this with a DELETE followed by INSERT
	 * $row is the row to insert, an associative array
	 * $uniqueIndexes is an array of indexes. Each element may be either a
	 * field name or an array of field names
	 *
	 * It may be more efficient to leave off unique indexes which are unlikely to collide.
	 * However if you do this, you run the risk of encountering errors which wouldn't have
	 * occurred in MySQL
	 *
	 * @param $table String: The table to replace the row(s) in.
	 * @param $uniqueIndexes Array: An associative array of indexes
	 * @param $rows Array: Array of rows to replace
	 * @param $fname String: Calling function name (use __METHOD__) for logs/profiling
	 */
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabaseBase::replace' ) {
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
	 * DELETE where the condition is a join
	 * MySQL does this with a multi-table DELETE syntax, PostgreSQL does it with sub-selects
	 *
	 * For safety, an empty $conds will not delete everything. If you want to delete all rows where the
	 * join condition matches, set $conds='*'
	 *
	 * DO NOT put the join condition in $conds
	 *
	 * @param $delTable String: The table to delete from.
	 * @param $joinTable String: The other table.
	 * @param $delVar String: The variable to join on, in the first table.
	 * @param $joinVar String: The variable to join on, in the second table.
	 * @param $conds Array: Condition array of field names mapped to variables, ANDed together in the WHERE clause
	 * @param $fname String: Calling function name (use __METHOD__) for logs/profiling
	 */
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = 'DatabaseBase::deleteJoin' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'DatabaseBase::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE $delTable FROM $delTable, $joinTable WHERE $delVar=$joinVar ";

		if ( $conds != '*' ) {
			$sql .= ' AND ' . $this->makeList( $conds, LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 */
	function textFieldSize( $table, $field ) {
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
	 * MySQL's LOW_PRIORITY.  If no such feature exists, return an empty
	 * string and nothing bad should happen.
	 *
	 * @return string Returns the text of the low priority option if it is supported, or a blank string otherwise
	 */
	function lowPriorityOption() {
		return '';
	}

	/**
	 * DELETE query wrapper
	 *
	 * Use $conds == "*" to delete all rows
	 */
	function delete( $table, $conds, $fname = 'DatabaseBase::delete' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'DatabaseBase::delete() called with no conditions' );
		}

		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";

		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather than field names, but strings should be quoted with DatabaseBase::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabaseBase::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );

		if ( is_array( $insertOptions ) ) {
			$insertOptions = implode( ' ', $insertOptions );
		}

		if ( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}

		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );

		if ( is_array( $srcTable ) ) {
			$srcTable =  implode( ',', array_map( array( &$this, 'tableName' ), $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		$sql = "INSERT $insertOptions INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			" SELECT $startOpts " . implode( ',', $varMap ) .
			" FROM $srcTable $useIndex ";

		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$sql .= " $tailOpts";

		return $this->query( $sql, $fname );
	}

	/**
	 * Construct a LIMIT query with optional offset.  This is used for query
	 * pages.  The SQL should be adjusted so that only the first $limit rows
	 * are returned.  If $offset is provided as well, then the first $offset
	 * rows should be discarded, and the next $limit rows should be returned.
	 * If the result of the query is not ordered, then the rows to be returned
	 * are theoretically arbitrary.
	 *
	 * $sql is expected to be a SELECT, if that makes a difference.  For
	 * UPDATE, limitResultForUpdate should be used.
	 *
	 * The version provided by default works in MySQL and SQLite.  It will very
	 * likely need to be overridden for most other DBMSes.
	 *
	 * @param $sql String: SQL query we will append the limit too
	 * @param $limit Integer: the SQL limit
	 * @param $offset Integer the SQL offset (default false)
	 */
	function limitResult( $sql, $limit, $offset = false ) {
		if ( !is_numeric( $limit ) ) {
			throw new DBUnexpectedError( $this, "Invalid non-numeric limit passed to limitResult()\n" );
		}

		return "$sql LIMIT "
				. ( ( is_numeric( $offset ) && $offset != 0 ) ? "{$offset}," : "" )
				. "{$limit} ";
	}

	function limitResultForUpdate( $sql, $num ) {
		return $this->limitResult( $sql, $num, 0 );
	}

	/**
	 * Returns true if current database backend supports ORDER BY or LIMIT for separate subqueries
	 * within the UNION construct.
	 * @return Boolean
	 */
	function unionSupportsOrderAndLimit() {
		return true; // True for almost every DB supported
	}

	/**
	 * Construct a UNION query
	 * This is used for providing overload point for other DB abstractions
	 * not compatible with the MySQL syntax.
	 * @param $sqls Array: SQL statements to combine
	 * @param $all Boolean: use UNION ALL
	 * @return String: SQL fragment
	 */
	function unionQueries( $sqls, $all ) {
		$glue = $all ? ') UNION ALL (' : ') UNION (';
		return '(' . implode( $glue, $sqls ) . ')';
	}

	/**
	 * Returns an SQL expression for a simple conditional.  This doesn't need
	 * to be overridden unless CASE isn't supported in your DBMS.
	 *
	 * @param $cond String: SQL expression which will result in a boolean value
	 * @param $trueVal String: SQL expression to return if true
	 * @param $falseVal String: SQL expression to return if false
	 * @return String: SQL fragment
	 */
	function conditional( $cond, $trueVal, $falseVal ) {
		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	/**
	 * Returns a comand for str_replace function in SQL query.
	 * Uses REPLACE() in MySQL
	 *
	 * @param $orig String: column to modify
	 * @param $old String: column to seek
	 * @param $new String: column to replace with
	 */
	function strreplace( $orig, $old, $new ) {
		return "REPLACE({$orig}, {$old}, {$new})";
	}

	/**
	 * Determines if the last failure was due to a deadlock
	 * STUB
	 */
	function wasDeadlock() {
		return false;
	}

	/**
	 * Determines if the last query error was something that should be dealt
	 * with by pinging the connection and reissuing the query.
	 * STUB
	 */
	function wasErrorReissuable() {
		return false;
	}

	/**
	 * Determines if the last failure was due to the database being read-only.
	 * STUB
	 */
	function wasReadOnlyError() {
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
	 */
	function deadlockLoop() {
		$myFname = 'DatabaseBase::deadlockLoop';

		$this->begin();
		$args = func_get_args();
		$function = array_shift( $args );
		$oldIgnore = $this->ignoreErrors( true );
		$tries = DEADLOCK_TRIES;

		if ( is_array( $function ) ) {
			$fname = $function[0];
		} else {
			$fname = $function;
		}

		do {
			$retVal = call_user_func_array( $function, $args );
			$error = $this->lastError();
			$errno = $this->lastErrno();
			$sql = $this->lastQuery();

			if ( $errno ) {
				if ( $this->wasDeadlock() ) {
					# Retry
					usleep( mt_rand( DEADLOCK_DELAY_MIN, DEADLOCK_DELAY_MAX ) );
				} else {
					$this->reportQueryError( $error, $errno, $sql, $fname );
				}
			}
		} while ( $this->wasDeadlock() && --$tries > 0 );

		$this->ignoreErrors( $oldIgnore );

		if ( $tries <= 0 ) {
			$this->rollback( $myFname );
			$this->reportQueryError( $error, $errno, $sql, $fname );
			return false;
		} else {
			$this->commit( $myFname );
			return $retVal;
		}
	}

	/**
	 * Do a SELECT MASTER_POS_WAIT()
	 *
	 * @param $pos MySQLMasterPos object
	 * @param $timeout Integer: the maximum number of seconds to wait for synchronisation
	 */
	function masterPosWait( MySQLMasterPos $pos, $timeout ) {
		$fname = 'DatabaseBase::masterPosWait';
		wfProfileIn( $fname );

		# Commit any open transactions
		if ( $this->mTrxLevel ) {
			$this->commit();
		}

		if ( !is_null( $this->mFakeSlaveLag ) ) {
			$wait = intval( ( $pos->pos - microtime( true ) + $this->mFakeSlaveLag ) * 1e6 );

			if ( $wait > $timeout * 1e6 ) {
				wfDebug( "Fake slave timed out waiting for $pos ($wait us)\n" );
				wfProfileOut( $fname );
				return -1;
			} elseif ( $wait > 0 ) {
				wfDebug( "Fake slave waiting $wait us\n" );
				usleep( $wait );
				wfProfileOut( $fname );
				return 1;
			} else {
				wfDebug( "Fake slave up to date ($wait us)\n" );
				wfProfileOut( $fname );
				return 0;
			}
		}

		# Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		$encFile = $this->addQuotes( $pos->file );
		$encPos = intval( $pos->pos );
		$sql = "SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)";
		$res = $this->doQuery( $sql );

		if ( $res && $row = $this->fetchRow( $res ) ) {
			wfProfileOut( $fname );
			return $row[0];
		} else {
			wfProfileOut( $fname );
			return false;
		}
	}

	/**
	 * Get the position of the master from SHOW SLAVE STATUS
	 */
	function getSlavePos() {
		if ( !is_null( $this->mFakeSlaveLag ) ) {
			$pos = new MySQLMasterPos( 'fake', microtime( true ) - $this->mFakeSlaveLag );
			wfDebug( __METHOD__ . ": fake slave pos = $pos\n" );
			return $pos;
		}

		$res = $this->query( 'SHOW SLAVE STATUS', 'DatabaseBase::getSlavePos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			$pos = isset( $row->Exec_master_log_pos ) ? $row->Exec_master_log_pos : $row->Exec_Master_Log_Pos;
			return new MySQLMasterPos( $row->Relay_Master_Log_File, $pos );
		} else {
			return false;
		}
	}

	/**
	 * Get the position of the master from SHOW MASTER STATUS
	 */
	function getMasterPos() {
		if ( $this->mFakeMaster ) {
			return new MySQLMasterPos( 'fake', microtime( true ) );
		}

		$res = $this->query( 'SHOW MASTER STATUS', 'DatabaseBase::getMasterPos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			return new MySQLMasterPos( $row->File, $row->Position );
		} else {
			return false;
		}
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 */
	function begin( $fname = 'DatabaseBase::begin' ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}

	/**
	 * End a transaction
	 */
	function commit( $fname = 'DatabaseBase::commit' ) {
		if ( $this->mTrxLevel ) {
			$this->query( 'COMMIT', $fname );
			$this->mTrxLevel = 0;
		}
	}

	/**
	 * Rollback a transaction.
	 * No-op on non-transactional databases.
	 */
	function rollback( $fname = 'DatabaseBase::rollback' ) {
		if ( $this->mTrxLevel ) {
			$this->query( 'ROLLBACK', $fname, true );
			$this->mTrxLevel = 0;
		}
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 * @deprecated use begin()
	 */
	function immediateBegin( $fname = 'DatabaseBase::immediateBegin' ) {
		wfDeprecated( __METHOD__ );
		$this->begin();
	}

	/**
	 * Commit transaction, if one is open
	 * @deprecated use commit()
	 */
	function immediateCommit( $fname = 'DatabaseBase::immediateCommit' ) {
		wfDeprecated( __METHOD__ );
		$this->commit();
	}

	/**
	 * Creates a new table with structure copied from existing table
	 * Note that unlike most database abstraction functions, this function does not
	 * automatically append database prefix, because it works at a lower
	 * abstraction level.
	 *
	 * @param $oldName String: name of table whose structure should be copied
	 * @param $newName String: name of table to be created
	 * @param $temporary Boolean: whether the new table should be temporary
	 * @param $fname String: calling function name
	 * @return Boolean: true if operation was successful
	 */
	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabaseBase::duplicateTableStructure' ) {
		throw new MWException( 'DatabaseBase::duplicateTableStructure is not implemented in descendant class' );
	}
	
	/**
	 * List all tables on the database
	 *
	 * @param $prefix Only show tables with this prefix, e.g. mw_
	 * @param $fname String: calling function name
	 */
	function listTables( $prefix = null, $fname = 'DatabaseBase::listTables' ) {
		throw new MWException( 'DatabaseBase::listTables is not implemented in descendant class' );
	}

	/**
	 * Return MW-style timestamp used for MySQL schema
	 */
	function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_MW, $ts );
	}

	/**
	 * Local database timestamp format or null
	 */
	function timestampOrNull( $ts = null ) {
		if ( is_null( $ts ) ) {
			return null;
		} else {
			return $this->timestamp( $ts );
		}
	}

	/**
	 * @todo document
	 */
	function resultObject( $result ) {
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
	 * Return aggregated value alias
	 */
	function aggregateValue ( $valuedata, $valuename = 'value' ) {
		return $valuename;
	}

	/**
	 * Ping the server and try to reconnect if it there is no connection
	 *
	 * @return bool Success or failure
	 */
	function ping() {
		# Stub.  Not essential to override.
		return true;
	}

	/**
	 * Get slave lag.
	 * Currently supported only by MySQL
	 * @return Database replication lag in seconds
	 */
	function getLag() {
		return intval( $this->mFakeSlaveLag );
	}

	/**
	 * Get status information from SHOW STATUS in an associative array
	 */
	function getStatus( $which = "%" ) {
		$res = $this->query( "SHOW STATUS LIKE '{$which}'" );
		$status = array();

		foreach ( $res as $row ) {
			$status[$row->Variable_name] = $row->Value;
		}

		return $status;
	}

	/**
	 * Return the maximum number of items allowed in a list, or 0 for unlimited.
	 */
	function maxListLen() {
		return 0;
	}

	function encodeBlob( $b ) {
		return $b;
	}

	function decodeBlob( $b ) {
		return $b;
	}

	/**
	 * Override database's default connection timeout.  May be useful for very
	 * long batch queries such as full-wiki dumps, where a single query reads
	 * out over hours or days.  May or may not be necessary for non-MySQL
	 * databases.  For most purposes, leaving it as a no-op should be fine.
	 *
	 * @param $timeout Integer in seconds
	 */
	public function setTimeout( $timeout ) {}

	/**
	 * Read and execute SQL commands from a file.
	 * Returns true on success, error string or exception on failure (depending on object's error ignore settings)
	 * @param $filename String: File name to open
	 * @param $lineCallback Callback: Optional function called before reading each line
	 * @param $resultCallback Callback: Optional function called for each MySQL result
	 * @param $fname String: Calling function name or false if name should be generated dynamically
	 * 		using $filename
	 */
	function sourceFile( $filename, $lineCallback = false, $resultCallback = false, $fname = false ) {
		wfSuppressWarnings();
		$fp = fopen( $filename, 'r' );
		wfRestoreWarnings();

		if ( false === $fp ) {
			throw new MWException( "Could not open \"{$filename}\".\n" );
		}

		if ( !$fname ) {
			$fname = __METHOD__ . "( $filename )";
		}

		try {
			$error = $this->sourceStream( $fp, $lineCallback, $resultCallback, $fname );
		}
		catch ( MWException $e ) {
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
	 * @param $patch String The name of the patch, like patch-something.sql
	 * @return String Full path to patch file
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
	 * Read and execute commands from an open file handle
	 * Returns true on success, error string or exception on failure (depending on object's error ignore settings)
	 * @param $fp String: File handle
	 * @param $lineCallback Callback: Optional function called before reading each line
	 * @param $resultCallback Callback: Optional function called for each MySQL result
	 * @param $fname String: Calling function name
	 */
	function sourceStream( $fp, $lineCallback = false, $resultCallback = false, $fname = 'DatabaseBase::sourceStream' ) {
		$cmd = "";
		$done = false;
		$dollarquote = false;

		while ( ! feof( $fp ) ) {
			if ( $lineCallback ) {
				call_user_func( $lineCallback );
			}

			$line = trim( fgets( $fp, 1024 ) );
			$sl = strlen( $line ) - 1;

			if ( $sl < 0 ) {
				continue;
			}

			if ( '-' == $line { 0 } && '-' == $line { 1 } ) {
				continue;
			}

			# # Allow dollar quoting for function declarations
			if ( substr( $line, 0, 4 ) == '$mw$' ) {
				if ( $dollarquote ) {
					$dollarquote = false;
					$done = true;
				}
				else {
					$dollarquote = true;
				}
			}
			else if ( !$dollarquote ) {
				if ( ';' == $line { $sl } && ( $sl < 2 || ';' != $line { $sl - 1 } ) ) {
					$done = true;
					$line = substr( $line, 0, $sl );
				}
			}

			if ( $cmd != '' ) {
				$cmd .= ' ';
			}

			$cmd .= "$line\n";

			if ( $done ) {
				$cmd = str_replace( ';;', ";", $cmd );
				$cmd = $this->replaceVars( $cmd );
				$res = $this->query( $cmd, $fname );

				if ( $resultCallback ) {
					call_user_func( $resultCallback, $res, $this );
				}

				if ( false === $res ) {
					$err = $this->lastError();
					return "Query \"{$cmd}\" failed with error code \"$err\".\n";
				}

				$cmd = '';
				$done = false;
			}
		}

		return true;
	}

	/**
	 * Database independent variable replacement, replaces a set of named variables
	 * in a sql statement with the contents of their global variables.
	 * Supports '{$var}' `{$var}` and / *$var* / (without the spaces) style variables
	 * 
	 * '{$var}' should be used for text and is passed through the database's addQuotes method
	 * `{$var}` should be used for identifiers (eg: table and database names), it is passed through
	 *          the database's addIdentifierQuotes method which can be overridden if the database
	 *          uses something other than backticks.
	 * / *$var* / is just encoded, besides traditional dbprefix and tableoptions it's use should be avoided
	 * 
	 * @param $ins String: SQL statement to replace variables in
	 * @param $varnames Array: Array of global variable names to replace
	 * @return String The new SQL statement with variables replaced
	 */
	protected function replaceGlobalVars( $ins, $varnames ) {
		foreach ( $varnames as $var ) {
			if ( isset( $GLOBALS[$var] ) ) {
				$ins = str_replace( '\'{$' . $var . '}\'', $this->addQuotes( $GLOBALS[$var] ), $ins ); // replace '{$var}'
				$ins = str_replace( '`{$' . $var . '}`', $this->addIdentifierQuotes( $GLOBALS[$var] ), $ins ); // replace `{$var}`
				$ins = str_replace( '/*$' . $var . '*/', $this->strencode( $GLOBALS[$var] ) , $ins ); // replace /*$var*/
			}
		}
		return $ins;
	}

	/**
	 * Replace variables in sourced SQL
	 */
	protected function replaceVars( $ins ) {
		$varnames = array(
			'wgDBserver', 'wgDBname', 'wgDBintlname', 'wgDBuser',
			'wgDBpassword', 'wgDBsqluser', 'wgDBsqlpassword',
			'wgDBadminuser', 'wgDBadminpassword', 'wgDBTableOptions',
		);

		$ins = $this->replaceGlobalVars( $ins, $varnames );

		// Table prefixes
		$ins = preg_replace_callback( '!/\*(?:\$wgDBprefix|_)\*/([a-zA-Z_0-9]*)!',
			array( $this, 'tableNameCallback' ), $ins );

		// Index names
		$ins = preg_replace_callback( '!/\*i\*/([a-zA-Z_0-9]*)!',
			array( $this, 'indexNameCallback' ), $ins );

		return $ins;
	}

	/**
	 * Table name callback
	 * @private
	 */
	protected function tableNameCallback( $matches ) {
		return $this->tableName( $matches[1] );
	}

	/**
	 * Index name callback
	 */
	protected function indexNameCallback( $matches ) {
		return $this->indexName( $matches[1] );
	}

	/**
	 * Build a concatenation list to feed into a SQL query
	 * @param $stringList Array: list of raw SQL expressions; caller is responsible for any quoting
	 * @return String
	 */
	function buildConcat( $stringList ) {
		return 'CONCAT(' . implode( ',', $stringList ) . ')';
	}

	/**
	 * Acquire a named lock
	 *
	 * Abstracted from Filestore::lock() so child classes can implement for
	 * their own needs.
	 *
	 * @param $lockName String: name of lock to aquire
	 * @param $method String: name of method calling us
	 * @param $timeout Integer: timeout
	 * @return Boolean
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		return true;
	}

	/**
	 * Release a lock.
	 *
	 * @param $lockName String: Name of lock to release
	 * @param $method String: Name of method calling us
	 *
	 * @return Returns 1 if the lock was released, 0 if the lock was not established
	 * by this thread (in which case the lock is not released), and NULL if the named
	 * lock did not exist
	 */
	public function unlock( $lockName, $method ) {
		return true;
	}

	/**
	 * Lock specific tables
	 *
	 * @param $read Array of tables to lock for read access
	 * @param $write Array of tables to lock for write access
	 * @param $method String name of caller
	 * @param $lowPriority bool Whether to indicate writes to be LOW PRIORITY
	 */
	public function lockTables( $read, $write, $method, $lowPriority = true ) {
		return true;
	}

	/**
	 * Unlock specific tables
	 *
	 * @param $method String the caller
	 */
	public function unlockTables( $method ) {
		return true;
	}

	/**
	 * Delete a table
	 */
	public function dropTable( $tableName, $fName = 'DatabaseBase::dropTable' ) {
		if( !$this->tableExists( $tableName ) ) {
			return false;
		}
		$sql = "DROP TABLE " . $this->tableName( $tableName );
		if( $this->cascadingDeletes() ) {
			$sql .= " CASCADE";
		}
		return $this->query( $sql, $fName );
	}

	/**
	 * Get search engine class. All subclasses of this need to implement this
	 * if they wish to use searching.
	 *
	 * @return String
	 */
	public function getSearchEngine() {
		return 'SearchEngineDummy';
	}

	/**
	 * Allow or deny "big selects" for this session only. This is done by setting
	 * the sql_big_selects session variable.
	 *
	 * This is a MySQL-specific feature.
	 *
	 * @param $value Mixed: true for allow, false for deny, or "default" to restore the initial value
	 */
	public function setBigSelects( $value = true ) {
		// no-op
	}
}

/******************************************************************************
 * Utility classes
 *****************************************************************************/

/**
 * Utility class.
 * @ingroup Database
 */
class DBObject {
	public $mData;

	function __construct( $data ) {
		$this->mData = $data;
	}

	function isLOB() {
		return false;
	}

	function data() {
		return $this->mData;
	}
}

/**
 * Utility class
 * @ingroup Database
 *
 * This allows us to distinguish a blob from a normal string and an array of strings
 */
class Blob {
	private $mData;

	function __construct( $data ) {
		$this->mData = $data;
	}

	function fetch() {
		return $this->mData;
	}
}

/**
 * Base for all database-specific classes representing information about database fields
 * @ingroup Database
 */
interface Field {
	/**
	 * Field name
	 * @return string
	 */
	function name();

	/**
	 * Name of table this field belongs to
	 * @return string
	 */
	function tableName();

	/**
	 * Database type
	 * @return string
	 */
	function type();

	/**
	 * Whether this field can store NULL values
	 * @return bool
	 */
	function isNullable();
}

/******************************************************************************
 * Error classes
 *****************************************************************************/

/**
 * Database error base class
 * @ingroup Database
 */
class DBError extends MWException {
	public $db;

	/**
	 * Construct a database error
	 * @param $db Database object which threw the error
	 * @param $error A simple error message to be used for debugging
	 */
	function __construct( DatabaseBase &$db, $error ) {
		$this->db =& $db;
		parent::__construct( $error );
	}

	function getText() {
		global $wgShowDBErrorBacktrace;

		$s = $this->getMessage() . "\n";

		if ( $wgShowDBErrorBacktrace ) {
			$s .= "Backtrace:\n" . $this->getTraceAsString() . "\n";
		}

		return $s;
	}
}

/**
 * @ingroup Database
 */
class DBConnectionError extends DBError {
	public $error;

	function __construct( DatabaseBase &$db, $error = 'unknown error' ) {
		$msg = 'DB connection error';

		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		}

		$this->error = $error;

		parent::__construct( $db, $msg );
	}

	function useOutputPage() {
		// Not likely to work
		return false;
	}

	function useMessageCache() {
		// Not likely to work
		return false;
	}

	function getLogMessage() {
		# Don't send to the exception log
		return false;
	}

	function getPageTitle() {
		global $wgSitename, $wgLang;

		$header = "$wgSitename has a problem";

		if ( $wgLang instanceof Language ) {
			$header = htmlspecialchars( $wgLang->getMessage( 'dberr-header' ) );
		}

		return $header;
	}

	function getHTML() {
		global $wgLang, $wgMessageCache, $wgUseFileCache, $wgShowDBErrorBacktrace;

		$sorry = 'Sorry! This site is experiencing technical difficulties.';
		$again = 'Try waiting a few minutes and reloading.';
		$info  = '(Can\'t contact the database server: $1)';

		if ( $wgLang instanceof Language ) {
			$sorry = htmlspecialchars( $wgLang->getMessage( 'dberr-problems' ) );
			$again = htmlspecialchars( $wgLang->getMessage( 'dberr-again' ) );
			$info  = htmlspecialchars( $wgLang->getMessage( 'dberr-info' ) );
		}

		# No database access
		if ( is_object( $wgMessageCache ) ) {
			$wgMessageCache->disable();
		}

		if ( trim( $this->error ) == '' ) {
			$this->error = $this->db->getProperty( 'mServer' );
		}

		$noconnect = "<p><strong>$sorry</strong><br />$again</p><p><small>$info</small></p>";
		$text = str_replace( '$1', $this->error, $noconnect );

		if ( $wgShowDBErrorBacktrace ) {
			$text .= '<p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) );
		}

		$extra = $this->searchForm();

		if ( $wgUseFileCache ) {
			try {
				$cache = $this->fileCachedPage();
				# Cached version on file system?
				if ( $cache !== null ) {
					# Hack: extend the body for error messages
					$cache = str_replace( array( '</html>', '</body>' ), '', $cache );
					# Add cache notice...
					$cachederror = "This is a cached copy of the requested page, and may not be up to date. ";

					# Localize it if possible...
					if ( $wgLang instanceof Language ) {
						$cachederror = htmlspecialchars( $wgLang->getMessage( 'dberr-cachederror' ) );
					}

					$warning = "<div style='color:red;font-size:150%;font-weight:bold;'>$cachederror</div>";

					# Output cached page with notices on bottom and re-close body
					return "{$cache}{$warning}<hr />$text<hr />$extra</body></html>";
				}
			} catch ( MWException $e ) {
				// Do nothing, just use the default page
			}
		}

		# Headers needed here - output is just the error message
		return $this->htmlHeader() . "$text<hr />$extra" . $this->htmlFooter();
	}

	function searchForm() {
		global $wgSitename, $wgServer, $wgLang;

		$usegoogle = "You can try searching via Google in the meantime.";
		$outofdate = "Note that their indexes of our content may be out of date.";
		$googlesearch = "Search";

		if ( $wgLang instanceof Language ) {
			$usegoogle = htmlspecialchars( $wgLang->getMessage( 'dberr-usegoogle' ) );
			$outofdate = htmlspecialchars( $wgLang->getMessage( 'dberr-outofdate' ) );
			$googlesearch  = htmlspecialchars( $wgLang->getMessage( 'searchbutton' ) );
		}

		$search = htmlspecialchars( @$_REQUEST['search'] );

		$server = htmlspecialchars( $wgServer );
		$sitename = htmlspecialchars( $wgSitename );

		$trygoogle = <<<EOT
<div style="margin: 1.5em">$usegoogle<br />
<small>$outofdate</small></div>
<!-- SiteSearch Google -->
<form method="get" action="http://www.google.com/search" id="googlesearch">
	<input type="hidden" name="domains" value="$server" />
	<input type="hidden" name="num" value="50" />
	<input type="hidden" name="ie" value="UTF-8" />
	<input type="hidden" name="oe" value="UTF-8" />

	<input type="text" name="q" size="31" maxlength="255" value="$search" />
	<input type="submit" name="btnG" value="$googlesearch" />
  <div>
	<input type="radio" name="sitesearch" id="gwiki" value="$server" checked="checked" /><label for="gwiki">$sitename</label>
	<input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>
<!-- SiteSearch Google -->
EOT;
		return $trygoogle;
	}

	private function fileCachedPage() {
		global $wgTitle, $wgLang, $wgOut;

		if ( $wgOut->isDisabled() ) {
			return; // Done already?
		}

		$mainpage = 'Main Page';

		if ( $wgLang instanceof Language ) {
			$mainpage = htmlspecialchars( $wgLang->getMessage( 'mainpage' ) );
		}

		if ( $wgTitle ) {
			$t =& $wgTitle;
		} else {
			$t = Title::newFromText( $mainpage );
		}

		$cache = new HTMLFileCache( $t );
		if ( $cache->isFileCached() ) {
			return $cache->fetchPageText();
		} else {
			return '';
		}
	}

	function htmlBodyOnly() {
		return true;
	}
}

/**
 * @ingroup Database
 */
class DBQueryError extends DBError {
	public $error, $errno, $sql, $fname;

	function __construct( DatabaseBase &$db, $error, $errno, $sql, $fname ) {
		$message = "A database error has occurred.  Did you forget to run maintenance/update.php after upgrading?  See: http://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
		  "Query: $sql\n" .
		  "Function: $fname\n" .
		  "Error: $errno $error\n";

		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}

	function getText() {
		global $wgShowDBErrorBacktrace;

		if ( $this->useMessageCache() ) {
			$s = wfMsg( 'dberrortextcl', htmlspecialchars( $this->getSQL() ),
				htmlspecialchars( $this->fname ), $this->errno, htmlspecialchars( $this->error ) ) . "\n";

			if ( $wgShowDBErrorBacktrace ) {
				$s .= "Backtrace:\n" . $this->getTraceAsString() . "\n";
			}

			return $s;
		} else {
			return parent::getText();
		}
	}

	function getSQL() {
		global $wgShowSQLErrors;

		if ( !$wgShowSQLErrors ) {
			return $this->msg( 'sqlhidden', 'SQL hidden' );
		} else {
			return $this->sql;
		}
	}

	function getLogMessage() {
		# Don't send to the exception log
		return false;
	}

	function getPageTitle() {
		return $this->msg( 'databaseerror', 'Database error' );
	}

	function getHTML() {
		global $wgShowDBErrorBacktrace;

		if ( $this->useMessageCache() ) {
			$s = wfMsgNoDB( 'dberrortext', htmlspecialchars( $this->getSQL() ),
			  htmlspecialchars( $this->fname ), $this->errno, htmlspecialchars( $this->error ) );
		} else {
			$s = nl2br( htmlspecialchars( $this->getMessage() ) );
		}

		if ( $wgShowDBErrorBacktrace ) {
			$s .= '<p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) );
		}

		return $s;
	}
}

/**
 * @ingroup Database
 */
class DBUnexpectedError extends DBError {}


/**
 * Result wrapper for grabbing data queried by someone else
 * @ingroup Database
 */
class ResultWrapper implements Iterator {
	var $db, $result, $pos = 0, $currentRow = null;

	/**
	 * Create a new result object from a result resource and a Database object
	 */
	function __construct( $database, $result ) {
		$this->db = $database;

		if ( $result instanceof ResultWrapper ) {
			$this->result = $result->result;
		} else {
			$this->result = $result;
		}
	}

	/**
	 * Get the number of rows in a result object
	 */
	function numRows() {
		return $this->db->numRows( $this );
	}

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @return MySQL row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchObject() {
		return $this->db->fetchObject( $this );
	}

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form.  Fields are retrieved with $row['fieldname'].
	 *
	 * @return MySQL row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchRow() {
		return $this->db->fetchRow( $this );
	}

	/**
	 * Free a result object
	 */
	function free() {
		$this->db->freeResult( $this );
		unset( $this->result );
		unset( $this->db );
	}

	/**
	 * Change the position of the cursor in a result object
	 * See mysql_data_seek()
	 */
	function seek( $row ) {
		$this->db->dataSeek( $this, $row );
	}

	/*********************
	 * Iterator functions
	 * Note that using these in combination with the non-iterator functions
	 * above may cause rows to be skipped or repeated.
	 */

	function rewind() {
		if ( $this->numRows() ) {
			$this->db->dataSeek( $this, 0 );
		}
		$this->pos = 0;
		$this->currentRow = null;
	}

	function current() {
		if ( is_null( $this->currentRow ) ) {
			$this->next();
		}
		return $this->currentRow;
	}

	function key() {
		return $this->pos;
	}

	function next() {
		$this->pos++;
		$this->currentRow = $this->fetchObject();
		return $this->currentRow;
	}

	function valid() {
		return $this->current() !== false;
	}
}

/**
 * Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {
	var $result     = array();
	var $db         = null;	// And it's going to stay that way :D
	var $pos        = 0;
	var $currentRow = null;

	function __construct( $array ) {
		$this->result = $array;
	}

	function numRows() {
		return count( $this->result );
	}

	function fetchRow() {
		$this->currentRow = $this->result[$this->pos++];
		return $this->currentRow;
	}

	function seek( $row ) {
		$this->pos = $row;
	}

	function free() {}

	// Callers want to be able to access fields with $this->fieldName
	function fetchObject() {
		$this->currentRow = $this->result[$this->pos++];
		return (object)$this->currentRow;
	}

	function rewind() {
		$this->pos = 0;
		$this->currentRow = null;
	}
}

/**
 * Used by DatabaseBase::buildLike() to represent characters that have special meaning in SQL LIKE clauses
 * and thus need no escaping. Don't instantiate it manually, use DatabaseBase::anyChar() and anyString() instead.
 */
class LikeMatch {
	private $str;

	public function __construct( $s ) {
		$this->str = $s;
	}

	public function toString() {
		return $this->str;
	}
}
