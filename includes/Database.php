<?php
/**
 * This file deals with MySQL interface functions
 * and query specifics/optimisations
 * @package MediaWiki
 */

/** Number of times to re-try an operation in case of deadlock */
define( 'DEADLOCK_TRIES', 4 );
/** Minimum time to wait before retry, in microseconds */
define( 'DEADLOCK_DELAY_MIN', 500000 );
/** Maximum time to wait before retry */
define( 'DEADLOCK_DELAY_MAX', 1500000 );

/******************************************************************************
 * Utility classes
 *****************************************************************************/

class DBObject {
	public $mData;

	function DBObject($data) {
		$this->mData = $data;
	}

	function isLOB() {
		return false;
	}

	function data() {
		return $this->mData;
	}
};

/******************************************************************************
 * Error classes
 *****************************************************************************/

/**
 * Database error base class
 */
class DBError extends MWException {
	public $db;

	/**
	 * Construct a database error
	 * @param Database $db The database object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 */
	function __construct( Database &$db, $error ) {
		$this->db =& $db;
		parent::__construct( $error );
	}
}

class DBConnectionError extends DBError {
	public $error;
	
	function __construct( Database &$db, $error = 'unknown error' ) {
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
	
	function getText() {
		return $this->getMessage() . "\n";
	}

	function getLogMessage() {
		# Don't send to the exception log
		return false;
	}

	function getPageTitle() {
		global $wgSitename;
		return "$wgSitename has a problem";
	}

	function getHTML() {
		global $wgTitle, $wgUseFileCache, $title, $wgInputEncoding, $wgOutputEncoding;
		global $wgSitename, $wgServer, $wgMessageCache, $wgLogo;

		# I give up, Brion is right. Getting the message cache to work when there is no DB is tricky.
		# Hard coding strings instead.

		$noconnect = "<p><strong>Sorry! This site is experiencing technical difficulties.</strong></p><p>Try waiting a few minutes and reloading.</p><p><small>(Can't contact the database server: $1)</small></p>";
		$mainpage = 'Main Page';
		$searchdisabled = <<<EOT
<p style="margin: 1.5em 2em 1em">$wgSitename search is disabled for performance reasons. You can search via Google in the meantime.
<span style="font-size: 89%; display: block; margin-left: .2em">Note that their indexes of $wgSitename content may be out of date.</span></p>',
EOT;

		$googlesearch = "
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"$wgServer\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"$wgServer\" checked> $wgServer <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->";
		$cachederror = "The following is a cached copy of the requested page, and may not be up to date. ";

		# No database access
		if ( is_object( $wgMessageCache ) ) {
			$wgMessageCache->disable();
		}

		if ( trim( $this->error ) == '' ) {
			$this->error = $this->db->getProperty('mServer');
		}

		$text = str_replace( '$1', $this->error, $noconnect );
		$text .= wfGetSiteNotice();

		if($wgUseFileCache) {
			if($wgTitle) {
				$t =& $wgTitle;
			} else {
				if($title) {
					$t = Title::newFromURL( $title );
				} elseif (@/**/$_REQUEST['search']) {
					$search = $_REQUEST['search'];
					return $searchdisabled .
					  str_replace( array( '$1', '$2' ), array( htmlspecialchars( $search ),
					  $wgInputEncoding ), $googlesearch );
				} else {
					$t = Title::newFromText( $mainpage );
				}
			}

			$cache = new HTMLFileCache( $t );
			if( $cache->isFileCached() ) {
				$msg = '<p style="color: red"><b>'.$msg."<br />\n" .
					$cachederror . "</b></p>\n";

				$tag = '<div id="article">';
				$text = str_replace(
					$tag,
					$tag . $msg,
					$cache->fetchPageText() );
			}
		}

		return $text;
	}
}

class DBQueryError extends DBError {
	public $error, $errno, $sql, $fname;
	
	function __construct( Database &$db, $error, $errno, $sql, $fname ) {
		$message = "A database error has occurred\n" .
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
		if ( $this->useMessageCache() ) {
			return wfMsg( 'dberrortextcl', htmlspecialchars( $this->getSQL() ),
			  htmlspecialchars( $this->fname ), $this->errno, htmlspecialchars( $this->error ) ) . "\n";
		} else {
			return $this->getMessage();
		}
	}
	
	function getSQL() {
		global $wgShowSQLErrors;
		if( !$wgShowSQLErrors ) {
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
		if ( $this->useMessageCache() ) {
			return wfMsgNoDB( 'dberrortext', htmlspecialchars( $this->getSQL() ),
			  htmlspecialchars( $this->fname ), $this->errno, htmlspecialchars( $this->error ) );
		} else {
			return nl2br( htmlspecialchars( $this->getMessage() ) );
		}
	}
}

class DBUnexpectedError extends DBError {}

/******************************************************************************/

/**
 * Database abstraction object
 * @package MediaWiki
 */
class Database {

#------------------------------------------------------------------------------
# Variables
#------------------------------------------------------------------------------

	protected $mLastQuery = '';

	protected $mServer, $mUser, $mPassword, $mConn = null, $mDBname;
	protected $mOut, $mOpened = false;

	protected $mFailFunction;
	protected $mTablePrefix;
	protected $mFlags;
	protected $mTrxLevel = 0;
	protected $mErrorCount = 0;
	protected $mLBInfo = array();
	protected $mCascadingDeletes = false;
	protected $mCleanupTriggers = false;
	protected $mStrictIPs = false;

#------------------------------------------------------------------------------
# Accessors
#------------------------------------------------------------------------------
	# These optionally set a variable and return the previous state

	/**
	 * Fail function, takes a Database as a parameter
	 * Set to false for default, 1 for ignore errors
	 */
	function failFunction( $function = NULL ) {
		return wfSetVar( $this->mFailFunction, $function );
	}

	/**
	 * Output page, used for reporting errors
	 * FALSE means discard output
	 */
	function setOutputPage( $out ) {
		$this->mOut = $out;
	}

	/**
	 * Boolean, controls output of large amounts of debug information
	 */
	function debug( $debug = NULL ) {
		return wfSetBit( $this->mFlags, DBO_DEBUG, $debug );
	}

	/**
	 * Turns buffering of SQL result sets on (true) or off (false).
	 * Default is "on" and it should not be changed without good reasons.
	 */
	function bufferResults( $buffer = NULL ) {
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
	 * code should use wfLastErrno() and wfLastError() to handle the
	 * situation as appropriate.
	 */
	function ignoreErrors( $ignoreErrors = NULL ) {
		return wfSetBit( $this->mFlags, DBO_IGNORE, $ignoreErrors );
	}

	/**
	 * The current depth of nested transactions
	 * @param $level Integer: , default NULL.
	 */
	function trxLevel( $level = NULL ) {
		return wfSetVar( $this->mTrxLevel, $level );
	}

	/**
	 * Number of errors logged, only useful when errors are ignored
	 */
	function errorCount( $count = NULL ) {
		return wfSetVar( $this->mErrorCount, $count );
	}

	/**
	 * Properties passed down from the server info array of the load balancer
	 */
	function getLBInfo( $name = NULL ) {
		if ( is_null( $name ) ) {
			return $this->mLBInfo;
		} else {
			if ( array_key_exists( $name, $this->mLBInfo ) ) {
				return $this->mLBInfo[$name];
			} else {
				return NULL;
			}
		}
	}

	function setLBInfo( $name, $value = NULL ) {
		if ( is_null( $value ) ) {
			$this->mLBInfo = $name;
		} else {
			$this->mLBInfo[$name] = $value;
		}
	}

	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 */
	function cascadingDeletes() {
		return $this->mCascadingDeletes;
	}

	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the page table)
	 */
	function cleanupTriggers() {
		return $this->mCleanupTriggers;
	}

	/**
	 * Returns true if this database is strict about what can be put into an IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 */
	function strictIPs() {
		return $this->mStrictIPs;
	}

	/**#@+
	 * Get function
	 */
	function lastQuery() { return $this->mLastQuery; }
	function isOpen() { return $this->mOpened; }
	/**#@-*/

	function setFlag( $flag ) {
		$this->mFlags |= $flag;
	}

	function clearFlag( $flag ) {
		$this->mFlags &= ~$flag;
	}

	function getFlag( $flag ) {
		return !!($this->mFlags & $flag);
	}

	/**
	 * General read-only accessor
	 */
	function getProperty( $name ) {
		return $this->$name;
	}

#------------------------------------------------------------------------------
# Other functions
#------------------------------------------------------------------------------

	/**@{{
	 * @param string $server database server host
	 * @param string $user database user name
	 * @param string $password database user password
	 * @param string $dbname database name
	 */

	/**
	 * @param failFunction
	 * @param $flags
	 * @param $tablePrefix String: database table prefixes. By default use the prefix gave in LocalSettings.php
	 */
	function __construct( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' ) {

		global $wgOut, $wgDBprefix, $wgCommandLineMode;
		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = NULL;
		}
		$this->mOut =& $wgOut;

		$this->mFailFunction = $failFunction;
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
	 * @static
	 * @param failFunction
	 * @param $flags
	 */
	static function newFromParams( $server, $user, $password, $dbName,
		$failFunction = false, $flags = 0 )
	{
		return new Database( $server, $user, $password, $dbName, $failFunction, $flags );
	}

	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		global $wguname;
		wfProfileIn( __METHOD__ );

		# Test for missing mysql.so
		# First try to load it
		if (!@extension_loaded('mysql')) {
			@dl('mysql.so');
		}

		# Fail now
		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !function_exists( 'mysql_connect' ) ) {
			throw new DBConnectionError( $this, "MySQL functions missing, have you compiled PHP with the --with-mysql option?\n" );
		}

		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$success = false;

		wfProfileIn("dbconnect-$server");
		
		# LIVE PATCH by Tim, ask Domas for why: retry loop
		$this->mConn = false;
		$max = 3;
		for ( $i = 0; $i < $max && !$this->mConn; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			if ( $this->mFlags & DBO_PERSISTENT ) {
				@/**/$this->mConn = mysql_pconnect( $server, $user, $password );
			} else {
				# Create a new connection...
				@/**/$this->mConn = mysql_connect( $server, $user, $password, true );
			}
			if ($this->mConn === false) {
				$iplus = $i + 1;
				wfLogDBError("Connect loop error $iplus of $max ($server): " . mysql_errno() . " - " . mysql_error()."\n"); 
			}
		}
		
		wfProfileOut("dbconnect-$server");

		if ( $dbName != '' ) {
			if ( $this->mConn !== false ) {
				$success = @/**/mysql_select_db( $dbName, $this->mConn );
				if ( !$success ) {
					$error = "Error selecting database $dbName on server {$this->mServer} " .
						"from client host {$wguname['nodename']}\n";
					wfLogDBError(" Error selecting database $dbName on server {$this->mServer} \n");
					wfDebug( $error );
				}
			} else {
				wfDebug( "DB connection error\n" );
				wfDebug( "Server: $server, User: $user, Password: " .
					substr( $password, 0, 3 ) . "..., error: " . mysql_error() . "\n" );
				$success = false;
			}
		} else {
			# Delay USE query
			$success = (bool)$this->mConn;
		}

		if ( $success ) {
			global $wgDBmysql5;
			if( $wgDBmysql5 ) {
				// Tell the server we're communicating with it in UTF-8.
				// This may engage various charset conversions.
				$this->query( 'SET NAMES utf8' );
			}
		} else {
			$this->reportConnectionError();
		}

		$this->mOpened = $success;
		wfProfileOut( __METHOD__ );
		return $success;
	}
	/**@}}*/

	/**
	 * Closes a database connection.
	 * if it is open : commits any open transactions
	 *
	 * @return bool operation success. true if already closed.
	 */
	function close()
	{
		$this->mOpened = false;
		if ( $this->mConn ) {
			if ( $this->trxLevel() ) {
				$this->immediateCommit();
			}
			return mysql_close( $this->mConn );
		} else {
			return true;
		}
	}

	/**
	 * @param string $error fallback error message, used if none is given by MySQL
	 */
	function reportConnectionError( $error = 'Unknown error' ) {
		$myError = $this->lastError();
		if ( $myError ) {
			$error = $myError;
		}

		if ( $this->mFailFunction ) {
			# Legacy error handling method
			if ( !is_int( $this->mFailFunction ) ) {
				$ff = $this->mFailFunction;
				$ff( $this, $error );
			}
		} else {
			# New method
			wfLogDBError( "Connection error: $error\n" );
			throw new DBConnectionError( $this, $error );
		}
	}

	/**
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 */
	function query( $sql, $fname = '', $tempIgnore = false ) {
		global $wgProfiling;

		if ( $wgProfiling ) {
			# generalizeSQL will probably cut down the query to reasonable
			# logging size most of the time. The substr is really just a sanity check.

			# Who's been wasting my precious column space? -- TS
			#$profName = 'query: ' . $fname . ' ' . substr( Database::generalizeSQL( $sql ), 0, 255 );

			if ( is_null( $this->getLBInfo( 'master' ) ) ) {
				$queryProf = 'query: ' . substr( Database::generalizeSQL( $sql ), 0, 255 );
				$totalProf = 'Database::query';
			} else {
				$queryProf = 'query-m: ' . substr( Database::generalizeSQL( $sql ), 0, 255 );
				$totalProf = 'Database::query-master';
			}
			wfProfileIn( $totalProf );
			wfProfileIn( $queryProf );
		}

		$this->mLastQuery = $sql;

		# Add a comment for easy SHOW PROCESSLIST interpretation
		if ( $fname ) {
			$commentedSql = preg_replace("/\s/", " /* $fname */ ", $sql, 1);
		} else {
			$commentedSql = $sql;
		}

		# If DBO_TRX is set, start a transaction
		if ( ( $this->mFlags & DBO_TRX ) && !$this->trxLevel() && 
			$sql != 'BEGIN' && $sql != 'COMMIT' && $sql != 'ROLLBACK' 
		) {
			$this->begin();
		}

		if ( $this->debug() ) {
			$sqlx = substr( $commentedSql, 0, 500 );
			$sqlx = strtr( $sqlx, "\t\n", '  ' );
			wfDebug( "SQL: $sqlx\n" );
		}

		# Do the query and handle errors
		$ret = $this->doQuery( $commentedSql );

		# Try reconnecting if the connection was lost
		if ( false === $ret && ( $this->lastErrno() == 2013 || $this->lastErrno() == 2006 ) ) {
			# Transaction is gone, like it or not
			$this->mTrxLevel = 0;
			wfDebug( "Connection lost, reconnecting...\n" );
			if ( $this->ping() ) {
				wfDebug( "Reconnected\n" );
				$ret = $this->doQuery( $commentedSql );
			} else {
				wfDebug( "Failed\n" );
			}
		}

		if ( false === $ret ) {
			$this->reportQueryError( $this->lastError(), $this->lastErrno(), $sql, $fname, $tempIgnore );
		}

		if ( $wgProfiling ) {
			wfProfileOut( $queryProf );
			wfProfileOut( $totalProf );
		}
		return $ret;
	}

	/**
	 * The DBMS-dependent part of query()
	 * @param string $sql SQL query.
	 */
	function doQuery( $sql ) {
		if( $this->bufferResults() ) {
			$ret = mysql_query( $sql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $this->mConn );
		}
		return $ret;
	}

	/**
	 * @param $error
	 * @param $errno
	 * @param $sql
	 * @param string $fname
	 * @param bool $tempIgnore
	 */
	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		global $wgCommandLineMode, $wgFullyInitialised, $wgColorErrors;
		# Ignore errors during error handling to avoid infinite recursion
		$ignore = $this->ignoreErrors( true );
		++$this->mErrorCount;

		if( $ignore || $tempIgnore ) {
			wfDebug("SQL ERROR (ignored): $error\n");
			$this->ignoreErrors( $ignore );
		} else {
			$sql1line = str_replace( "\n", "\\n", $sql );
			wfLogDBError("$fname\t{$this->mServer}\t$errno\t$error\t$sql1line\n");
			wfDebug("SQL ERROR: " . $error . "\n");
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
	function prepare( $sql, $func = 'Database::prepare' ) {
		/* MySQL doesn't support prepared statements (yet), so just
		   pack up the query for reference. We'll manually replace
		   the bits later. */
		return array( 'query' => $sql, 'func' => $func );
	}

	function freePrepared( $prepared ) {
		/* No-op for MySQL */
	}

	/**
	 * Execute a prepared query with the various arguments
	 * @param string $prepared the prepared sql
	 * @param mixed $args Either an array here, or put scalars as varargs
	 */
	function execute( $prepared, $args = null ) {
		if( !is_array( $args ) ) {
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
	 * @param string $query
	 * @param string $args ...
	 */
	function safeQuery( $query, $args = null ) {
		$prepared = $this->prepare( $query, 'Database::safeQuery' );
		if( !is_array( $args ) ) {
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
	 * @param string $preparedSql - a 'preparable' SQL statement
	 * @param array $args - array of arguments to fill it with
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
	 * @param array $matches
	 * @return string
	 * @private
	 */
	function fillPreparedArg( $matches ) {
		switch( $matches[1] ) {
			case '\\?': return '?';
			case '\\!': return '!';
			case '\\&': return '&';
		}
		list( $n, $arg ) = each( $this->preparedArgs );
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

	/**#@+
	 * @param mixed $res A SQL result
	 */
	/**
	 * Free a result object
	 */
	function freeResult( $res ) {
		if ( !@/**/mysql_free_result( $res ) ) {
			throw new DBUnexpectedError( $this, "Unable to free MySQL result" );
		}
	}

	/**
	 * Fetch the next row from the given result object, in object form
	 */
	function fetchObject( $res ) {
		@/**/$row = mysql_fetch_object( $res );
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	/**
	 * Fetch the next row from the given result object
	 * Returns an array
	 */
 	function fetchRow( $res ) {
		@/**/$row = mysql_fetch_array( $res );
		if ( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	/**
	 * Get the number of rows in a result object
	 */
	function numRows( $res ) {
		@/**/$n = mysql_num_rows( $res );
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in numRows(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $n;
	}

	/**
	 * Get the number of fields in a result object
	 * See documentation for mysql_num_fields()
	 */
	function numFields( $res ) { return mysql_num_fields( $res ); }

	/**
	 * Get a field name in a result object
	 * See documentation for mysql_field_name():
	 * http://www.php.net/mysql_field_name
	 */
	function fieldName( $res, $n ) { return mysql_field_name( $res, $n ); }

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue('page_page_id_seq');
	 * $dbw->insert('page',array('page_id' => $id));
	 * $id = $dbw->insertId();
	 */
	function insertId() { return mysql_insert_id( $this->mConn ); }

	/**
	 * Change the position of the cursor in a result object
	 * See mysql_data_seek()
	 */
	function dataSeek( $res, $row ) { return mysql_data_seek( $res, $row ); }

	/**
	 * Get the last error number
	 * See mysql_errno()
	 */
	function lastErrno() {
		if ( $this->mConn ) {
			return mysql_errno( $this->mConn );
		} else {
			return mysql_errno();
		}
	}

	/**
	 * Get a description of the last error
	 * See mysql_error() for more details
	 */
	function lastError() {
		if ( $this->mConn ) {
			# Even if it's non-zero, it can still be invalid
			wfSuppressWarnings();
			$error = mysql_error( $this->mConn );
			if ( !$error ) {
				$error = mysql_error();
			}
			wfRestoreWarnings();
		} else {
			$error = mysql_error();
		}
		if( $error ) {
			$error .= ' (' . $this->mServer . ')';
		}
		return $error;
	}
	/**
	 * Get the number of rows affected by the last write query
	 * See mysql_affected_rows() for more details
	 */
	function affectedRows() { return mysql_affected_rows( $this->mConn ); }
	/**#@-*/ // end of template : @param $result

	/**
	 * Simple UPDATE wrapper
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 *
	 * This function exists for historical reasons, Database::update() has a more standard
	 * calling convention and feature set
	 */
	function set( $table, $var, $value, $cond, $fname = 'Database::set' )
	{
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
	function selectField( $table, $var, $cond='', $fname = 'Database::selectField', $options = array() ) {
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
			$this->freeResult( $res );
			return $row[0];
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
	 * @param array $options an associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$tailOpts = '';
		$startOpts = '';

		$noKeyOptions = array();
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) $tailOpts .= " GROUP BY {$options['GROUP BY']}";
		if ( isset( $options['ORDER BY'] ) ) $tailOpts .= " ORDER BY {$options['ORDER BY']}";
		
		if (isset($options['LIMIT'])) {
			$tailOpts .= $this->limitResult('', $options['LIMIT'],
				isset($options['OFFSET']) ? $options['OFFSET'] : false);
		}

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) $tailOpts .= ' FOR UPDATE';
		if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) $tailOpts .= ' LOCK IN SHARE MODE';
		if ( isset( $noKeyOptions['DISTINCT'] ) && isset( $noKeyOptions['DISTINCTROW'] ) ) $startOpts .= 'DISTINCT';

		# Various MySQL extensions
		if ( isset( $noKeyOptions['STRAIGHT_JOIN'] ) ) $startOpts .= ' /*! STRAIGHT_JOIN */';
		if ( isset( $noKeyOptions['HIGH_PRIORITY'] ) ) $startOpts .= ' HIGH_PRIORITY';
		if ( isset( $noKeyOptions['SQL_BIG_RESULT'] ) ) $startOpts .= ' SQL_BIG_RESULT';
		if ( isset( $noKeyOptions['SQL_BUFFER_RESULT'] ) ) $startOpts .= ' SQL_BUFFER_RESULT';
		if ( isset( $noKeyOptions['SQL_SMALL_RESULT'] ) ) $startOpts .= ' SQL_SMALL_RESULT';
		if ( isset( $noKeyOptions['SQL_CALC_FOUND_ROWS'] ) ) $startOpts .= ' SQL_CALC_FOUND_ROWS';
		if ( isset( $noKeyOptions['SQL_CACHE'] ) ) $startOpts .= ' SQL_CACHE';
		if ( isset( $noKeyOptions['SQL_NO_CACHE'] ) ) $startOpts .= ' SQL_NO_CACHE';

		if ( isset( $options['USE INDEX'] ) && ! is_array( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}
		
		return array( $startOpts, $useIndex, $tailOpts );
	}

	/**
	 * SELECT wrapper
	 */
	function select( $table, $vars, $conds='', $fname = 'Database::select', $options = array() )
	{
		if( is_array( $vars ) ) {
			$vars = implode( ',', $vars );
		}
		if( !is_array( $options ) ) {
			$options = array( $options );
		}
		if( is_array( $table ) ) {
			if ( @is_array( $options['USE INDEX'] ) )
				$from = ' FROM ' . $this->tableNamesWithUseIndex( $table, $options['USE INDEX'] );
			else
				$from = ' FROM ' . implode( ',', array_map( array( &$this, 'tableName' ), $table ) );
		} elseif ($table!='') {
			$from = ' FROM ' . $this->tableName( $table );
		} else {
			$from = '';
		}

		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $options );

		if( !empty( $conds ) ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
			}
			$sql = "SELECT $startOpts $vars $from $useIndex WHERE $conds $tailOpts";
		} else {
			$sql = "SELECT $startOpts $vars $from $useIndex $tailOpts";
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * Single row SELECT wrapper
	 * Aborts or returns FALSE on error
	 *
	 * $vars: the selected variables
	 * $conds: a condition map, terms are ANDed together.
	 *   Items with numeric keys are taken to be literal conditions
	 * Takes an array of selected variables, and a condition map, which is ANDed
	 * e.g: selectRow( "page", array( "page_id" ), array( "page_namespace" =>
	 * NS_MAIN, "page_title" => "Astronomy" ) )   would return an object where
	 * $obj- >page_id is the ID of the Astronomy article
	 *
	 * @todo migrate documentation to phpdocumentor format
	 */
	function selectRow( $table, $vars, $conds, $fname = 'Database::selectRow', $options = array() ) {
		$options['LIMIT'] = 1;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		if ( $res === false )
			return false;
		if ( !$this->numRows($res) ) {
			$this->freeResult($res);
			return false;
		}
		$obj = $this->fetchObject( $res );
		$this->freeResult( $res );
		return $obj;

	}

	/**
	 * Removes most variables from an SQL query and replaces them with X or N for numbers.
	 * It's only slightly flawed. Don't use for anything important.
	 *
	 * @param string $sql A SQL Query
	 * @static
	 */
	static function generalizeSQL( $sql ) {
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace ( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql);

		$sql = str_replace ( "\\\\", '', $sql);
		$sql = str_replace ( "\\'", '', $sql);
		$sql = str_replace ( "\\\"", '', $sql);
		$sql = preg_replace ("/'.*'/s", "'X'", $sql);
		$sql = preg_replace ('/".*"/s', "'X'", $sql);

		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace ( "/\s+/", ' ', $sql);

		# All numbers => N
		$sql = preg_replace ('/-?[0-9]+/s', 'N', $sql);

		return $sql;
	}

	/**
	 * Determines whether a field exists in a table
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function fieldExists( $table, $field, $fname = 'Database::fieldExists' ) {
		$table = $this->tableName( $table );
		$res = $this->query( 'DESCRIBE '.$table, $fname );
		if ( !$res ) {
			return NULL;
		}

		$found = false;

		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->Field == $field ) {
				$found = true;
				break;
			}
		}
		return $found;
	}

	/**
	 * Determines whether an index exists
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexExists( $table, $index, $fname = 'Database::indexExists' ) {
		$info = $this->indexInfo( $table, $index, $fname );
		if ( is_null( $info ) ) {
			return NULL;
		} else {
			return $info !== false;
		}
	}


	/**
	 * Get information about an index into an object
	 * Returns false if the index does not exist
	 */
	function indexInfo( $table, $index, $fname = 'Database::indexInfo' ) {
		# SHOW INDEX works in MySQL 3.23.58, but SHOW INDEXES does not.
		# SHOW INDEX should work for 3.x and up:
		# http://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$table = $this->tableName( $table );
		$sql = 'SHOW INDEX FROM '.$table;
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return NULL;
		}

		$result = array();
		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->Key_name == $index ) {
				$result[] = $row;
			}
		}
		$this->freeResult($res);
		
		return empty($result) ? false : $result;
	}

	/**
	 * Query whether a given table exists
	 */
	function tableExists( $table ) {
		$table = $this->tableName( $table );
		$old = $this->ignoreErrors( true );
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1" );
		$this->ignoreErrors( $old );
		if( $res ) {
			$this->freeResult( $res );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * mysql_fetch_field() wrapper
	 * Returns false if the field doesn't exist
	 *
	 * @param $table
	 * @param $field
	 */
	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT * FROM $table LIMIT 1" );
		$n = mysql_num_fields( $res );
		for( $i = 0; $i < $n; $i++ ) {
			$meta = mysql_fetch_field( $res, $i );
			if( $field == $meta->name ) {
				return $meta;
			}
		}
		return false;
	}

	/**
	 * mysql_field_type() wrapper
	 */
	function fieldType( $res, $index ) {
		return mysql_field_type( $res, $index );
	}

	/**
	 * Determines if a given index is unique
	 */
	function indexUnique( $table, $index ) {
		$indexInfo = $this->indexInfo( $table, $index );
		if ( !$indexInfo ) {
			return NULL;
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
	 */
	function insert( $table, $a, $fname = 'Database::insert', $options = array() ) {
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
	 * Make UPDATE options for the Database::update function
	 *
	 * @private
	 * @param array $options The options passed to Database::update
	 * @return string
	 */
	function makeUpdateOptions( $options ) {
		if( !is_array( $options ) ) {
			$options = array( $options );
		}
		$opts = array();
		if ( in_array( 'LOW_PRIORITY', $options ) )
			$opts[] = $this->lowPriorityOption();
		if ( in_array( 'IGNORE', $options ) )
			$opts[] = 'IGNORE';
		return implode(' ', $opts);
	}

	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 *
	 * @param string $table  The table to UPDATE
	 * @param array  $values An array of values to SET
	 * @param array  $conds  An array of conditions (WHERE). Use '*' to update all rows.
	 * @param string $fname  The Class::Function calling this function
	 *                       (for the log)
	 * @param array  $options An array of UPDATE options, can be one or
	 *                        more of IGNORE, LOW_PRIORITY
	 */
	function update( $table, $values, $conds, $fname = 'Database::update', $options = array() ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET );
		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}
		$this->query( $sql, $fname );
	}

	/**
	 * Makes a wfStrencoded list from an array
	 * $mode:
	 *        LIST_COMMA         - comma separated, no field names
	 *        LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *        LIST_OR            - ORed WHERE clause (without the WHERE)
	 *        LIST_SET           - comma separated with field names, like a SET clause
	 *        LIST_NAMES         - comma separated field names
	 */
	function makeList( $a, $mode = LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, 'Database::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = '';
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= ' AND ';
				} elseif($mode == LIST_OR) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}
			if ( ($mode == LIST_AND || $mode == LIST_OR) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( ($mode == LIST_AND || $mode == LIST_OR) && is_array ($value) ) {
				$list .= $field." IN (".$this->makeList($value).") ";
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
	 * Change the current database
	 */
	function selectDB( $db ) {
		$this->mDBname = $db;
		return mysql_select_db( $db, $this->mConn );
	}

	/**
	 * Format a table name ready for use in constructing an SQL query
	 *
	 * This does two important things: it quotes table names which as necessary,
	 * and it adds a table prefix if there is one.
	 *
	 * All functions of this object which require a table name call this function
	 * themselves. Pass the canonical name to such functions. This is only needed
	 * when calling query() directly.
	 *
	 * @param string $name database table name
	 */
	function tableName( $name ) {
		global $wgSharedDB;
		# Skip quoted literals
		if ( $name{0} != '`' ) {
			if ( $this->mTablePrefix !== '' &&  strpos( '.', $name ) === false ) {
				$name = "{$this->mTablePrefix}$name";
			}
			if ( isset( $wgSharedDB ) && "{$this->mTablePrefix}user" == $name ) {
				$name = "`$wgSharedDB`.`$name`";
			} else {
				# Standard quoting
				$name = "`$name`";
			}
		}
		return $name;
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
	function tableNames() {
		$inArray = func_get_args();
		$retVal = array();
		foreach ( $inArray as $name ) {
			$retVal[$name] = $this->tableName( $name );
		}
		return $retVal;
	}

	/**
	 * @private
	 */
	function tableNamesWithUseIndex( $tables, $use_index ) {
		$ret = array();

		foreach ( $tables as $table )
			if ( @$use_index[$table] !== null )
				$ret[] = $this->tableName( $table ) . ' ' . $this->useIndexClause( implode( ',', (array)$use_index[$table] ) );
			else
				$ret[] = $this->tableName( $table );

		return implode( ',', $ret );
	}

	/**
	 * Wrapper for addslashes()
	 * @param string $s String to be slashed.
	 * @return string slashed string.
	 */
	function strencode( $s ) {
		return mysql_real_escape_string( $s, $this->mConn );
	}

	/**
	 * If it's a string, adds quotes and backslashes
	 * Otherwise returns as-is
	 */
	function addQuotes( $s ) {
		if ( is_null( $s ) ) {
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
	 * Escape string for safe LIKE usage
	 */
	function escapeLike( $s ) {
		$s=$this->strencode( $s );
		$s=str_replace(array('%','_'),array('\%','\_'),$s);
		return $s;
	}

	/**
	 * Returns an appropriately quoted sequence value for inserting a new row.
	 * MySQL has autoincrement fields, so this is just NULL. But the PostgreSQL
	 * subclass will return an integer, and save the value for insertId()
	 */
	function nextSequenceValue( $seqName ) {
		return NULL;
	}

	/**
	 * USE INDEX clause
	 * PostgreSQL doesn't have them and returns ""
	 */
	function useIndexClause( $index ) {
		return "FORCE INDEX ($index)";
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
	 * @todo migrate comment to phodocumentor format
	 */
	function replace( $table, $uniqueIndexes, $rows, $fname = 'Database::replace' ) {
		$table = $this->tableName( $table );

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		$sql = "REPLACE INTO $table (" . implode( ',', array_keys( $rows[0] ) ) .') VALUES ';
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
	 * @param string $delTable The table to delete from.
	 * @param string $joinTable The other table.
	 * @param string $delVar The variable to join on, in the first table.
	 * @param string $joinVar The variable to join on, in the second table.
	 * @param array $conds Condition array of field names mapped to variables, ANDed together in the WHERE clause
	 */
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = 'Database::deleteJoin' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'Database::deleteJoin() called with empty $conds' );
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
		$res = $this->query( $sql, 'Database::textFieldSize' );
		$row = $this->fetchObject( $res );
		$this->freeResult( $res );

		if ( preg_match( "/\((.*)\)/", $row->Type, $m ) ) {
			$size = $m[1];
		} else {
			$size = -1;
		}
		return $size;
	}

	/**
	 * @return string Returns the text of the low priority option if it is supported, or a blank string otherwise
	 */
	function lowPriorityOption() {
		return 'LOW_PRIORITY';
	}

	/**
	 * DELETE query wrapper
	 *
	 * Use $conds == "*" to delete all rows
	 */
	function delete( $table, $conds, $fname = 'Database::delete' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'Database::delete() called with no conditions' );
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
	 * Source items may be literals rather than field names, but strings should be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'Database::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );
		if ( is_array( $insertOptions ) ) {
			$insertOptions = implode( ' ', $insertOptions );
		}
		if( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}
		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );
		if( is_array( $srcTable ) ) {
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
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 * $sql string SQL query we will append the limit too
	 * $limit integer the SQL limit
	 * $offset integer the SQL offset (default false)
	 */
	function limitResult($sql, $limit, $offset=false) {
		if( !is_numeric($limit) ) {
			throw new DBUnexpectedError( $this, "Invalid non-numeric limit passed to limitResult()\n" );
		}
		return " $sql LIMIT "
				. ( (is_numeric($offset) && $offset != 0) ? "{$offset}," : "" )
				. "{$limit} ";
	}
	function limitResultForUpdate($sql, $num) {
		return $this->limitResult($sql, $num, 0);
	}

	/**
	 * Returns an SQL expression for a simple conditional.
	 * Uses IF on MySQL.
	 *
	 * @param string $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	function conditional( $cond, $trueVal, $falseVal ) {
		return " IF($cond, $trueVal, $falseVal) ";
	}

	/**
	 * Determines if the last failure was due to a deadlock
	 */
	function wasDeadlock() {
		return $this->lastErrno() == 1213;
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
		$myFname = 'Database::deadlockLoop';

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
		} while( $this->wasDeadlock() && --$tries > 0 );
		$this->ignoreErrors( $oldIgnore );
		if ( $tries <= 0 ) {
			$this->query( 'ROLLBACK', $myFname );
			$this->reportQueryError( $error, $errno, $sql, $fname );
			return false;
		} else {
			$this->query( 'COMMIT', $myFname );
			return $retVal;
		}
	}

	/**
	 * Do a SELECT MASTER_POS_WAIT()
	 *
	 * @param string $file the binlog file
	 * @param string $pos the binlog position
	 * @param integer $timeout the maximum number of seconds to wait for synchronisation
	 */
	function masterPosWait( $file, $pos, $timeout ) {
		$fname = 'Database::masterPosWait';
		wfProfileIn( $fname );


		# Commit any open transactions
		$this->immediateCommit();

		# Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		$encFile = $this->strencode( $file );
		$sql = "SELECT MASTER_POS_WAIT('$encFile', $pos, $timeout)";
		$res = $this->doQuery( $sql );
		if ( $res && $row = $this->fetchRow( $res ) ) {
			$this->freeResult( $res );
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
		$res = $this->query( 'SHOW SLAVE STATUS', 'Database::getSlavePos' );
		$row = $this->fetchObject( $res );
		if ( $row ) {
			return array( $row->Master_Log_File, $row->Read_Master_Log_Pos );
		} else {
			return array( false, false );
		}
	}

	/**
	 * Get the position of the master from SHOW MASTER STATUS
	 */
	function getMasterPos() {
		$res = $this->query( 'SHOW MASTER STATUS', 'Database::getMasterPos' );
		$row = $this->fetchObject( $res );
		if ( $row ) {
			return array( $row->File, $row->Position );
		} else {
			return array( false, false );
		}
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 */
	function begin( $fname = 'Database::begin' ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}

	/**
	 * End a transaction
	 */
	function commit( $fname = 'Database::commit' ) {
		$this->query( 'COMMIT', $fname );
		$this->mTrxLevel = 0;
	}

	/**
	 * Rollback a transaction
	 */
	function rollback( $fname = 'Database::rollback' ) {
		$this->query( 'ROLLBACK', $fname );
		$this->mTrxLevel = 0;
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 * @deprecated use begin()
	 */
	function immediateBegin( $fname = 'Database::immediateBegin' ) {
		$this->begin();
	}

	/**
	 * Commit transaction, if one is open
	 * @deprecated use commit()
	 */
	function immediateCommit( $fname = 'Database::immediateCommit' ) {
		$this->commit();
	}

	/**
	 * Return MW-style timestamp used for MySQL schema
	 */
	function timestamp( $ts=0 ) {
		return wfTimestamp(TS_MW,$ts);
	}

	/**
	 * Local database timestamp format or null
	 */
	function timestampOrNull( $ts = null ) {
		if( is_null( $ts ) ) {
			return null;
		} else {
			return $this->timestamp( $ts );
		}
	}

	/**
	 * @todo document
	 */
	function resultObject( $result ) {
		if( empty( $result ) ) {
			return NULL;
		} else {
			return new ResultWrapper( $this, $result );
		}
	}

	/**
	 * Return aggregated value alias
	 */
	function aggregateValue ($valuedata,$valuename='value') {
		return $valuename;
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	function getSoftwareLink() {
		return "[http://www.mysql.com/ MySQL]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		return mysql_get_server_info( $this->mConn );
	}

	/**
	 * Ping the server and try to reconnect if it there is no connection
	 */
	function ping() {
		if( function_exists( 'mysql_ping' ) ) {
			return mysql_ping( $this->mConn );
		} else {
			wfDebug( "Tried to call mysql_ping but this is ancient PHP version. Faking it!\n" );
			return true;
		}
	}

	/**
	 * Get slave lag.
	 * At the moment, this will only work if the DB user has the PROCESS privilege
	 */
	function getLag() {
		$res = $this->query( 'SHOW PROCESSLIST' );
		# Find slave SQL thread. Assumed to be the second one running, which is a bit
		# dubious, but unfortunately there's no easy rigorous way
		$slaveThreads = 0;
		while ( $row = $this->fetchObject( $res ) ) {
			/* This should work for most situations - when default db 
			 * for thread is not specified, it had no events executed, 
			 * and therefore it doesn't know yet how lagged it is.
			 *
			 * Relay log I/O thread does not select databases.
			 */
			if ( $row->User == 'system user' && 
				$row->State != 'Waiting for master to send event' &&
				$row->State != 'Connecting to master' && 
				$row->State != 'Queueing master event to the relay log' &&
				$row->State != 'Waiting for master update' &&
				$row->State != 'Requesting binlog dump'
				) {
				# This is it, return the time (except -ve)
				if ( $row->Time > 0x7fffffff ) {
					return false;
				} else {
					return $row->Time;
				}
			}
		}
		return false;
	}

	/**
	 * Get status information from SHOW STATUS in an associative array
	 */
	function getStatus($which="%") {
		$res = $this->query( "SHOW STATUS LIKE '{$which}'" );
		$status = array();
		while ( $row = $this->fetchObject( $res ) ) {
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

	function encodeBlob($b) {
		return $b;
	}

	function decodeBlob($b) {
		return $b;
	}

	/**
	 * Read and execute SQL commands from a file.
	 * Returns true on success, error string on failure
	 */
	function sourceFile( $filename ) {
		$fp = fopen( $filename, 'r' );
		if ( false === $fp ) {
			return "Could not open \"{$filename}\".\n";
		}

		$cmd = "";
		$done = false;
		$dollarquote = false;

		while ( ! feof( $fp ) ) {
			$line = trim( fgets( $fp, 1024 ) );
			$sl = strlen( $line ) - 1;

			if ( $sl < 0 ) { continue; }
			if ( '-' == $line{0} && '-' == $line{1} ) { continue; }

			## Allow dollar quoting for function declarations
			if (substr($line,0,4) == '$mw$') {
				if ($dollarquote) {
					$dollarquote = false;
					$done = true;
				}
				else {
					$dollarquote = true;
				}
			}
			else if (!$dollarquote) {
				if ( ';' == $line{$sl} && ($sl < 2 || ';' != $line{$sl - 1})) {
					$done = true;
					$line = substr( $line, 0, $sl );
				}
			}

			if ( '' != $cmd ) { $cmd .= ' '; }
			$cmd .= "$line\n";

			if ( $done ) {
				$cmd = str_replace(';;', ";", $cmd);
				$cmd = $this->replaceVars( $cmd );
				$res = $this->query( $cmd, 'dbsource', true );

				if ( false === $res ) {
					$err = $this->lastError();
					return "Query \"{$cmd}\" failed with error code \"$err\".\n";
				}

				$cmd = '';
				$done = false;
			}
		}
		fclose( $fp );
		return true;
	}

	/**
	 * Replace variables in sourced SQL
	 */
	protected function replaceVars( $ins ) {
		$varnames = array(
			'wgDBserver', 'wgDBname', 'wgDBintlname', 'wgDBuser',
			'wgDBpassword', 'wgDBsqluser', 'wgDBsqlpassword',
			'wgDBadminuser', 'wgDBadminpassword',
		);

		// Ordinary variables
		foreach ( $varnames as $var ) {
			if( isset( $GLOBALS[$var] ) ) {
				$val = addslashes( $GLOBALS[$var] ); // FIXME: safety check?
				$ins = str_replace( '{$' . $var . '}', $val, $ins );
				$ins = str_replace( '/*$' . $var . '*/`', '`' . $val, $ins );
				$ins = str_replace( '/*$' . $var . '*/', $val, $ins );
			}
		}

		// Table prefixes
		$ins = preg_replace_callback( '/\/\*(?:\$wgDBprefix|_)\*\/([a-z_]*)/',
			array( &$this, 'tableNameCallback' ), $ins );
		return $ins;
	}

	/**
	 * Table name callback
	 * @private
	 */
	protected function tableNameCallback( $matches ) {
		return $this->tableName( $matches[1] );
	}

}

/**
 * Database abstraction object for mySQL
 * Inherit all methods and properties of Database::Database()
 *
 * @package MediaWiki
 * @see Database
 */
class DatabaseMysql extends Database {
	# Inherit all
}


/**
 * Result wrapper for grabbing data queried by someone else
 *
 * @package MediaWiki
 */
class ResultWrapper {
	var $db, $result;

	/**
	 * @todo document
	 */
	function ResultWrapper( &$database, $result ) {
		$this->db =& $database;
		$this->result =& $result;
	}

	/**
	 * @todo document
	 */
	function numRows() {
		return $this->db->numRows( $this->result );
	}

	/**
	 * @todo document
	 */
	function fetchObject() {
		return $this->db->fetchObject( $this->result );
	}

	/**
	 * @todo document
	 */
	function fetchRow() {
		return $this->db->fetchRow( $this->result );
	}

	/**
	 * @todo document
	 */
	function free() {
		$this->db->freeResult( $this->result );
		unset( $this->result );
		unset( $this->db );
	}

	function seek( $row ) {
		$this->db->dataSeek( $this->result, $row );
	}

}

?>
