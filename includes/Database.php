<?php
/**
 * This file deals with MySQL interface functions 
 * and query specifics/optimisations
 * @package MediaWiki
 */

/**
 * Depends on the CacheManager
 */
require_once( 'CacheManager.php' );

/** See Database::makeList() */
define( 'LIST_COMMA', 0 );
define( 'LIST_AND', 1 );
define( 'LIST_SET', 2 );
define( 'LIST_NAMES', 3);

/** Number of times to re-try an operation in case of deadlock */
define( 'DEADLOCK_TRIES', 4 );
/** Minimum time to wait before retry, in microseconds */
define( 'DEADLOCK_DELAY_MIN', 500000 );
/** Maximum time to wait before retry */
define( 'DEADLOCK_DELAY_MAX', 1500000 );

/**
 * Database abstraction object
 * @package MediaWiki
 */
class Database {

#------------------------------------------------------------------------------
# Variables
#------------------------------------------------------------------------------
	/**#@+
	 * @access private
	 */
	var $mLastQuery = '';
	
	var $mServer, $mUser, $mPassword, $mConn, $mDBname;
	var $mOut, $mOpened = false;
	
	var $mFailFunction; 
	var $mTablePrefix;
	var $mFlags;
	var $mTrxLevel = 0;
	/**#@-*/

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
	function &setOutputPage( &$out ) { 
		$this->mOut =& $out; 
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
	 * @param integer $level
	 */
	function trxLevel( $level = NULL ) {
		return wfSetVar( $this->mTrxLevel, $level );
	}

	/**#@+
	 * Get function
	 */
	function lastQuery() { return $this->mLastQuery; }
	function isOpen() { return $this->mOpened; }
	/**#@-*/

#------------------------------------------------------------------------------
# Other functions
#------------------------------------------------------------------------------

	/**#@+
	 * @param string $server database server host
	 * @param string $user database user name
	 * @param string $password database user password
	 * @param string $dbname database name
	 */
	 
	/**
	 * @param failFunction
	 * @param $flags
	 * @param string $tablePrefix Database table prefixes. By default use the prefix gave in LocalSettings.php
	 */
	function Database( $server = false, $user = false, $password = false, $dbName = false, 
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
	function newFromParams( $server, $user, $password, $dbName, 
		$failFunction = false, $flags = 0 )
	{
		return new Database( $server, $user, $password, $dbName, $failFunction, $flags );
	}
	
	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for missing mysql.so
		# First try to load it
		if (!@extension_loaded('mysql')) {
			@dl('mysql.so');
		}

		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !function_exists( 'mysql_connect' ) ) {
			die( "MySQL functions missing, have you compiled PHP with the --with-mysql option?\n" );
		}
		
		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;
		
		$success = false;
		
		@/**/$this->mConn = mysql_connect( $server, $user, $password );
		if ( $dbName != '' ) {
			if ( $this->mConn !== false ) {
				$success = @/**/mysql_select_db( $dbName, $this->mConn );
				if ( !$success ) {
					wfDebug( "Error selecting database \"$dbName\": " . $this->lastError() . "\n" );
				}
			} else {
				wfDebug( "DB connection error\n" );
				wfDebug( "Server: $server, User: $user, Password: " . 
					substr( $password, 0, 3 ) . "...\n" );
				$success = false;
			}
		} else {
			# Delay USE query
			$success = !!$this->mConn;
		}
		
		if ( !$success ) {
			$this->reportConnectionError();
			$this->close();
		}
		$this->mOpened = $success;
		return $success;
	}
	/**#@-*/
	
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
	 * @access private
	 * @param string $msg error message ?
	 * @todo parameter $msg is not used
	 */
	function reportConnectionError( $msg = '') {
		if ( $this->mFailFunction ) {
			if ( !is_int( $this->mFailFunction ) ) {
				$ff = $this->mFailFunction;
				$ff( $this, mysql_error() );
			}
		} else {
			wfEmergencyAbort( $this, mysql_error() );
		}
	}
	
	/**
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 */
	function query( $sql, $fname = '', $tempIgnore = false ) {
		global $wgProfiling, $wgCommandLineMode;
		
		if ( $wgProfiling ) {
			# generalizeSQL will probably cut down the query to reasonable
			# logging size most of the time. The substr is really just a sanity check.
			$profName = 'query: ' . substr( Database::generalizeSQL( $sql ), 0, 255 ); 
			wfProfileIn( $profName );
		}
		
		$this->mLastQuery = $sql;
		
		if ( $this->debug() ) {
			$sqlx = substr( $sql, 0, 500 );
			$sqlx = wordwrap(strtr($sqlx,"\t\n",'  '));
			wfDebug( "SQL: $sqlx\n" );
		}
		# Add a comment for easy SHOW PROCESSLIST interpretation
		if ( $fname ) {
			$commentedSql = "/* $fname */ $sql";
		} else {
			$commentedSql = $sql;
		}
		
		# If DBO_TRX is set, start a transaction
		if ( ( $this->mFlags & DBO_TRX ) && !$this->trxLevel() && $sql != 'BEGIN' ) {
			$this->begin();
		}
		
		# Do the query and handle errors
		$ret = $this->doQuery( $commentedSql );
		if ( false === $ret ) {
			$this->reportQueryError( $this->lastError(), $this->lastErrno(), $sql, $fname, $tempIgnore );
		}
				
		if ( $wgProfiling ) {
			wfProfileOut( $profName );
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
		global $wgCommandLineMode, $wgFullyInitialised;
		# Ignore errors during error handling to avoid infinite recursion
		$ignore = $this->ignoreErrors( true );

		if( $ignore || $tempIgnore ) {
			wfDebug("SQL ERROR (ignored): " . $error . "\n");
		} else {
			$sql1line = str_replace( "\n", "\\n", $sql );
			wfLogDBError("$fname\t$errno\t$error\t$sql1line\n");
			wfDebug("SQL ERROR: " . $error . "\n");
			if ( $wgCommandLineMode || !$this->mOut || empty( $wgFullyInitialised ) ) {
				$message = "A database error has occurred\n" .
				  "Query: $sql\n" .
				  "Function: $fname\n" .
				  "Error: $errno $error\n";
				if ( !$wgCommandLineMode ) {	
					$message = nl2br( $message );
				}
				wfDebugDieBacktrace( $message );
			} else {
				// this calls wfAbruptExit()
				$this->mOut->databaseError( $fname, $sql, $error, $errno ); 				
			}
		}
		$this->ignoreErrors( $ignore );
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
	 * @param 
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
		$n = 0;
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
	 * @access private
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
				wfDebugDieBacktrace( '& mode is not implemented. If it\'s really needed, uncomment the line above.' );
			default:
				wfDebugDieBacktrace( 'Received invalid match. This should never happen!' );
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
			wfDebugDieBacktrace( "Unable to free MySQL result\n" );
		}
	}
	
	/**
	 * Fetch the next row from the given result object, in object form
	 */
	function fetchObject( $res ) {
		@/**/$row = mysql_fetch_object( $res );
		if( mysql_errno() ) {
			wfDebugDieBacktrace( 'Error in fetchObject(): ' . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}

	/**
	 * Fetch the next row from the given result object
	 * Returns an array
	 */
 	function fetchRow( $res ) {
		@/**/$row = mysql_fetch_array( $res );
		if (mysql_errno() ) {
			wfDebugDieBacktrace( 'Error in fetchRow(): ' . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}	

	/**
	 * Get the number of rows in a result object
	 */
	function numRows( $res ) {
		@/**/$n = mysql_num_rows( $res ); 
		if( mysql_errno() ) {
			wfDebugDieBacktrace( 'Error in numRows(): ' . htmlspecialchars( mysql_error() ) );
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
	 * See documentation for mysql_field_name()
	 */
	function fieldName( $res, $n ) { return mysql_field_name( $res, $n ); }

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue('cur_cur_id_seq');
	 * $dbw->insert('cur',array('cur_id' => $id));
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
	function lastErrno() { return mysql_errno(); }
	
	/**
	 * Get a description of the last error
	 * See mysql_error() for more details
	 */
	function lastError() { return mysql_error(); }
	
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
		return !!$this->query( $sql, DB_MASTER, $fname );
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
	 */
	function makeSelectOptions( $options ) {
		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		$tailOpts = '';

		if ( isset( $options['ORDER BY'] ) ) {
			$tailOpts .= " ORDER BY {$options['ORDER BY']}";
		} 
		if ( isset( $options['LIMIT'] ) ) {
			$tailOpts .= " LIMIT {$options['LIMIT']}";
		}

		if ( is_numeric( array_search( 'FOR UPDATE', $options ) ) ) {
			$tailOpts .= ' FOR UPDATE';
		}
		
		if ( is_numeric( array_search( 'LOCK IN SHARE MODE', $options ) ) ) {
			$tailOpts .= ' LOCK IN SHARE MODE';
		}

		if ( isset( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}
		return array( $useIndex, $tailOpts );
	}

	/**
	 * SELECT wrapper
	 */
	function select( $table, $vars, $conds='', $fname = 'Database::select', $options = array() )
	{
		if( is_array( $vars ) ) {
			$vars = implode( ',', $vars );
		}
		if( is_array( $table ) ) {
			$from = ' FROM ' . implode( ',', array_map( array( &$this, 'tableName' ), $table ) );
		} elseif ($table!='') {
			$from = ' FROM ' .$this->tableName( $table );
		} else {
			$from = '';
		}

		list( $useIndex, $tailOpts ) = $this->makeSelectOptions( $options );
		
		if( !empty( $conds ) ) {
			if ( is_array( $conds ) ) {
				$conds = $this->makeList( $conds, LIST_AND );
			}
			$sql = "SELECT $vars $from $useIndex WHERE $conds $tailOpts";
		} else {
			$sql = "SELECT $vars $from $useIndex $tailOpts";
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
	 * e.g. selectRow( "cur", array( "cur_id" ), array( "cur_namespace" => 0, "cur_title" => "Astronomy" ) )
	 *   would return an object where $obj->cur_id is the ID of the Astronomy article
	 *
	 * @todo migrate documentation to phpdocumentor format
	 */
	function selectRow( $table, $vars, $conds, $fname = 'Database::selectRow', $options = array() ) {
		$options['LIMIT'] = 1;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		if ( $res === false || !$this->numRows( $res ) ) {
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
	function generalizeSQL( $sql ) {	
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
		$res = $this->query( 'DESCRIBE '.$table, DB_SLAVE, $fname );
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
		
		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->Key_name == $index ) {
				return $row;
			}
		}
		return false;
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
		return !$indexInfo->Non_unique;
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
		return !!$this->query( $sql, $fname );
	}

	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 */
	function update( $table, $values, $conds, $fname = 'Database::update' ) {
		$table = $this->tableName( $table );
		$sql = "UPDATE $table SET " . $this->makeList( $values, LIST_SET );
		$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		$this->query( $sql, $fname );
	}
	
	/**
	 * Makes a wfStrencoded list from an array
	 * $mode: LIST_COMMA         - comma separated, no field names
	 *        LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *        LIST_SET           - comma separated with field names, like a SET clause
	 *	  LIST_NAMES	     - comma separated field names
	 */
	function makeList( $a, $mode = LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			wfDebugDieBacktrace( 'Database::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = '';
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= ' AND ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}
			if ( $mode == LIST_AND && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( $mode == LIST_AND && is_array ($value) ) {
				$list .= $field." IN (".$this->makeList($value).") ";
			} else {
				if ( $mode == LIST_AND || $mode == LIST_SET ) {
					$list .= $field.'=';
				}
				$list .= ($mode==LIST_NAMES?$value:$this->addQuotes( $value ));
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
	 * Starts a timer which will kill the DB thread after $timeout seconds
	 */
	function startTimer( $timeout ) {
		global $IP;
		if( function_exists( 'mysql_thread_id' ) ) {
			# This will kill the query if it's still running after $timeout seconds.
			$tid = mysql_thread_id( $this->mConn );
			exec( "php $IP/includes/killthread.php $timeout $tid &>/dev/null &" );
		}
	}

	/**
	 * Stop a timer started by startTimer()
	 * Currently unimplemented.
	 *
	 */
	function stopTimer() { }

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
			if ( isset( $wgSharedDB ) && 'user' == $name ) {
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
	 * Wrapper for addslashes()
	 * @param string $s String to be slashed.
	 * @return string slashed string.
	 */
	function strencode( $s ) {
		return addslashes( $s );
	}

	/**
	 * If it's a string, adds quotes and backslashes
	 * Otherwise returns as-is
	 */
	function addQuotes( $s ) {
		if ( is_null( $s ) ) {
			$s = 'NULL';
		} else {
			# This will also quote numeric values. This should be harmless,
			# and protects against weird problems that occur when they really
			# _are_ strings such as article titles and string->number->string
			# conversion is not 1:1.
			$s = "'" . $this->strencode( $s ) . "'";
		} 
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
		return 'USE INDEX ('.$index.')';
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
			wfDebugDieBacktrace( 'Database::deleteJoin() called with empty $conds' );
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
	 * @return string Always return 'LOW_PRIORITY'
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
			wfDebugDieBacktrace( 'Database::delete() called with no conditions' );
		}
		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather than field names, but strings should be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'Database::insertSelect' ) {
		$destTable = $this->tableName( $destTable );
		$srcTable = $this->tableName( $srcTable );
		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			' SELECT ' . implode( ',', $varMap ) . 
			" FROM $srcTable";
		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}

	/**
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 */
	function limitResult($limit,$offset) {
		return ' LIMIT '.(is_numeric($offset)?"{$offset},":"")."{$limit} ";
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
		
		$this->query( 'BEGIN', $myFname );
		$args = func_get_args();
		$function = array_shift( $args );
		$oldIgnore = $dbw->ignoreErrors( true );
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
				if ( $dbw->wasDeadlock() ) {
					# Retry
					usleep( mt_rand( DEADLOCK_DELAY_MIN, DEADLOCK_DELAY_MAX ) );
				} else {
					$dbw->reportQueryError( $error, $errno, $sql, $fname );
				}
			}
		} while( $dbw->wasDeadlock && --$tries > 0 );
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
		$encFile = $this->strencode( $file );
		$sql = "SELECT MASTER_POS_WAIT('$encFile', $pos, $timeout)";
		$res = $this->query( $sql, 'Database::masterPosWait' );
		if ( $res && $row = $this->fetchRow( $res ) ) {
			$this->freeResult( $res );
			return $row[0];
		} else {
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
	 * Begin a transaction, or if a transaction has already started, continue it
	 */
	function begin( $fname = 'Database::begin' ) {
		if ( !$this->mTrxLevel ) {
			$this->immediateBegin( $fname );
		} else {
			$this->mTrxLevel++;
		}
	}

	/**
	 * End a transaction, or decrement the nest level if transactions are nested
	 */
	function commit( $fname = 'Database::commit' ) {
		if ( $this->mTrxLevel ) {
			$this->mTrxLevel--;
		}
		if ( !$this->mTrxLevel ) {
			$this->immediateCommit( $fname );
		}
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
	 */
	function immediateBegin( $fname = 'Database::immediateBegin' ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}
	
	/**
	 * Commit transaction, if one is open
	 */
	function immediateCommit( $fname = 'Database::immediateCommit' ) {
		$this->query( 'COMMIT', $fname );
		$this->mTrxLevel = 0;
	}

	/**
	 * Return MW-style timestamp used for MySQL schema
	 */
	function timestamp( $ts=0 ) {
		return wfTimestamp(TS_MW,$ts);
	}
	
	/**
	 * @todo document
	 */
	function &resultObject( &$result ) {
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
		return mysql_get_server_info();
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
	function ResultWrapper( $database, $result ) {
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
	function &fetchObject() {
		return $this->db->fetchObject( $this->result );
	}
	
	/**
	 * @todo document
	 */
	function &fetchRow() {
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
}

#------------------------------------------------------------------------------
# Global functions
#------------------------------------------------------------------------------

/**
 * Standard fail function, called by default when a connection cannot be
 * established.
 * Displays the file cache if possible
 */
function wfEmergencyAbort( &$conn, $error ) {
	global $wgTitle, $wgUseFileCache, $title, $wgInputEncoding, $wgSiteNotice, $wgOutputEncoding;
	global $wgSitename, $wgServer;
	
	# I give up, Brion is right. Getting the message cache to work when there is no DB is tricky.
	# Hard coding strings instead.

	$noconnect = 'Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br />
$1';
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


	if( !headers_sent() ) {
		header( 'HTTP/1.0 500 Internal Server Error' );
		header( 'Content-type: text/html; charset='.$wgOutputEncoding );
		/* Don't cache error pages!  They cause no end of trouble... */
		header( 'Cache-control: none' );
		header( 'Pragma: nocache' );
	}
	$msg = $wgSiteNotice;
	if($msg == '') {
		$msg = str_replace( '$1', $error, $noconnect );
	}
	$text = $msg;

	if($wgUseFileCache) {
		if($wgTitle) {
			$t =& $wgTitle;
		} else {
			if($title) {
				$t = Title::newFromURL( $title );
			} elseif (@/**/$_REQUEST['search']) {
				$search = $_REQUEST['search'];
				echo $searchdisabled;
				echo str_replace( array( '$1', '$2' ), array( htmlspecialchars( $search ), 
				  $wgInputEncoding ), $googlesearch );
				wfErrorExit();
			} else {
				$t = Title::newFromText( $mainpage );
			}
		}

		$cache = new CacheManager( $t );
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
	
	echo $text;
	wfErrorExit();
}

?>
