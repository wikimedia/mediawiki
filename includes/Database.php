<?php
# $Id$
# This file deals with MySQL interface functions 
# and query specifics/optimisations
#
require_once( "CacheManager.php" );

define( "LIST_COMMA", 0 );
define( "LIST_AND", 1 );
define( "LIST_SET", 2 );

class Database {

#------------------------------------------------------------------------------
# Variables
#------------------------------------------------------------------------------	
	/* private */ var $mLastQuery = "";
	/* private */ var $mBufferResults = true;
	/* private */ var $mIgnoreErrors = false;
	
	/* private */ var $mServer, $mUser, $mPassword, $mConn, $mDBname;
	/* private */ var $mOut, $mDebug, $mOpened = false;
	
	/* private */ var $mFailFunction; 

#------------------------------------------------------------------------------
# Accessors
#------------------------------------------------------------------------------
	# Set functions
	# These set a variable and return the previous state
	
	# Fail function, takes a Database as a parameter
	# Set to false for default, 1 for ignore errors
	function setFailFunction( $function ) { return wfSetVar( $this->mFailFunction, $function ); }
	
	# Output page, used for reporting errors
	# FALSE means discard output
	function &setOutputPage( &$out ) { $this->mOut =& $out; }
	
	# Boolean, controls output of large amounts of debug information 
	function setDebug( $debug ) { return wfSetVar( $this->mDebug, $debug ); }
	
	# Turns buffering of SQL result sets on (true) or off (false). Default is
	# "on" and it should not be changed without good reasons. 
	function setBufferResults( $buffer ) { return wfSetVar( $this->mBufferResults, $buffer ); }

	# Turns on (false) or off (true) the automatic generation and sending
	# of a "we're sorry, but there has been a database error" page on
	# database errors. Default is on (false). When turned off, the
	# code should use wfLastErrno() and wfLastError() to handle the
	# situation as appropriate.
	function setIgnoreErrors( $ignoreErrors ) { return wfSetVar( $this->mIgnoreErrors, $ignoreErrors ); }
	
	# Get functions
	
	function lastQuery() { return $this->mLastQuery; }
	function isOpen() { return $this->mOpened; }

#------------------------------------------------------------------------------
# Other functions
#------------------------------------------------------------------------------

	function Database( $server = false, $user = false, $password = false, $dbName = false, 
		$failFunction = false, $debug = false, $bufferResults = true, $ignoreErrors = false )
	{
		global $wgOut;
		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = NULL;
		}
		$this->mOut =& $wgOut;

		$this->mFailFunction = $failFunction;
		$this->mIgnoreErrors = $ignoreErrors;
		$this->mDebug = $debug;
		$this->mBufferResults = $bufferResults;
		if ( $server ) {
			$this->open( $server, $user, $password, $dbName );
		}
	}
	
	/* static */ function newFromParams( $server, $user, $password, $dbName, 
		$failFunction = false, $debug = false, $bufferResults = true, $ignoreErrors = false )
	{
		return new Database( $server, $user, $password, $dbName, $failFunction, $debug, 
		  $bufferResults, $ignoreErrors );
	}
	
	# Usually aborts on failure
	# If the failFunction is set to a non-zero integer, returns success
	function open( $server, $user, $password, $dbName )
	{
		# Test for missing mysql.so
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
		
		@$this->mConn = mysql_connect( $server, $user, $password );
		if ( $dbName != "" ) {
			if ( $this->mConn !== false ) {
				$success = @mysql_select_db( $dbName, $this->mConn );
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
	
	# Closes a database connection, if it is open
	# Returns success, true if already closed
	function close()
	{
		$this->mOpened = false;
		if ( $this->mConn ) {
			return mysql_close( $this->mConn );
		} else {
			return true;
		}
	}
	
	/* private */ function reportConnectionError( $msg = "")
	{
		if ( $this->mFailFunction ) {
			if ( !is_int( $this->mFailFunction ) ) {
				$ff = $this->mFailFunction;
				$ff( $this, mysql_error() );
			}
		} else {
			wfEmergencyAbort( $this, mysql_error() );
		}
	}
	
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function query( $sql, $fname = "", $tempIgnore = false )
	{
		global $wgProfiling, $wgCommandLineMode;
		
		if ( $wgProfiling ) {
			# generalizeSQL will probably cut down the query to reasonable
			# logging size most of the time. The substr is really just a sanity check.
			$profName = "query: " . substr( Database::generalizeSQL( $sql ), 0, 255 ); 
			wfProfileIn( $profName );
		}
		
		$this->mLastQuery = $sql;
		
		if ( $this->mDebug ) {
			$sqlx = substr( $sql, 0, 500 );
			$sqlx = wordwrap(strtr($sqlx,"\t\n","  "));
			wfDebug( "SQL: $sqlx\n" );
		}
		# Add a comment for easy SHOW PROCESSLIST interpretation
		if ( $fname ) {
			$commentedSql = "/* $fname */ $sql";
		} else {
			$commentedSql = $sql;
		}
		
		if( $this->mBufferResults ) {
			$ret = mysql_query( $commentedSql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $commentedSql, $this->mConn );
		}
	
		if ( false === $ret ) {
			# Ignore errors during error handling to avoid infinite recursion
			$ignore = $this->setIgnoreErrors( true );

			$error = mysql_error( $this->mConn );
			$errno = mysql_errno( $this->mConn );
			if( $ignore || $tempIgnore ) {
				wfDebug("SQL ERROR (ignored): " . $error . "\n");
			} else {
				$sql1line = str_replace( "\n", "\\n", $sql );
				wfLogDBError("$fname\t$errno\t$error\t$sql1line\n");
				wfDebug("SQL ERROR: " . $error . "\n");
				if ( $wgCommandLineMode ) {
					wfDebugDieBacktrace( "A database error has occurred\n" .
					  "Query: $sql\n" .
					  "Function: $fname\n" .
					  "Error: $errno $error\n"
					);
				} elseif ( $this->mOut ) {
					// this calls wfAbruptExit()
					$this->mOut->databaseError( $fname, $sql, $error, $errno ); 				
				}
			}
			$this->setIgnoreErrors( $ignore );
		}
		
		if ( $wgProfiling ) {
			wfProfileOut( $profName );
		}
		return $ret;
	}
	
	function freeResult( $res ) {
		if ( !@mysql_free_result( $res ) ) {
			wfDebugDieBacktrace( "Unable to free MySQL result\n" );
		}
	}
	function fetchObject( $res ) {
		@$row = mysql_fetch_object( $res );
		# FIXME: HACK HACK HACK HACK debug
		if( mysql_errno() ) {
			wfDebugDieBacktrace( "Error in fetchObject(): " . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}
	
 	function fetchRow( $res ) {
		@$row = mysql_fetch_array( $res );
		if (mysql_errno() ) {
			wfDebugDieBacktrace( "Error in fetchRow(): " . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}	

	function numRows( $res ) {
		@$n = mysql_num_rows( $res ); 
		if( mysql_errno() ) {
			wfDebugDieBacktrace( "Error in numRows(): " . htmlspecialchars( mysql_error() ) );
		}
		return $n;
	}
	function numFields( $res ) { return mysql_num_fields( $res ); }
	function fieldName( $res, $n ) { return mysql_field_name( $res, $n ); }
	function insertId() { return mysql_insert_id( $this->mConn ); }
	function dataSeek( $res, $row ) { return mysql_data_seek( $res, $row ); }
	function lastErrno() { return mysql_errno(); }
	function lastError() { return mysql_error(); }
	function affectedRows() { return mysql_affected_rows( $this->mConn ); }
	
	# Simple UPDATE wrapper
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function set( $table, $var, $value, $cond, $fname = "Database::set" )
	{
		$table = $this->tableName( $table );
		$sql = "UPDATE $table SET $var = '" .
		  wfStrencode( $value ) . "' WHERE ($cond)";
		return !!$this->query( $sql, DB_WRITE, $fname );
	}
	
	# Simple SELECT wrapper, returns a single field, input must be encoded
	# Usually aborts on failure
	# If errors are explicitly ignored, returns FALSE on failure
	function getField( $table, $var, $cond, $fname = "Database::get" )
	{
		$table = $this->tableName( $table );
		$from = $table?" FROM $table ":"";
		if ( is_array( $cond ) ) {
			$where = ' WHERE ' . $this->makeList( $cond, LIST_AND );
		} elseif ( $cond ) {
			$where = " WHERE ($cond)";
		} else {
			$where = '';
		}
		$sql = "SELECT $var $from $where LIMIT 1";
		$result = $this->query( $sql, $fname );
	
		$ret = false;
		if ( $this->numRows( $result ) > 0 ) {
			$s = $this->fetchRow( $result );
			$ret = $s[0];
			$this->freeResult( $result );
		}
		return $ret;
	}
	
	# SELECT wrapper
	function select( $table, $vars, $conds, $fname = "Database::select", $options = array() )
	{
		$vars = implode( ",", $vars );
		$table = $this->tableName( $table );
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

		if ( $conds !== false ) {
			$where = $this->makeList( $conds, LIST_AND );
			$sql = "SELECT $vars FROM $table $useIndex WHERE $where $tailOpts";
		} else {
			$sql = "SELECT $vars FROM $table $useIndex $tailOpts";
		}
		return $this->query( $sql, $fname );
	}
	
	# Single row SELECT wrapper
	# Aborts or returns FALSE on error
	#
	# $vars: the selected variables
	# $conds: a condition map, terms are ANDed together. 
	#    Items with numeric keys are taken to be literal conditions
	# Takes an array of selected variables, and a condition map, which is ANDed
	# e.g. getArray( "cur", array( "cur_id" ), array( "cur_namespace" => 0, "cur_title" => "Astronomy" ) )
	#   would return an object where $obj->cur_id is the ID of the Astronomy article
	function getArray( $table, $vars, $conds, $fname = "Database::getArray", $options = array() ) {
		$options['LIMIT'] = 1;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		if ( $res === false || !$this->numRows( $res ) ) {
			return false;
		}
		$obj = $this->fetchObject( $res );
		$this->freeResult( $res );
		return $obj;
		
	}
	
	# Removes most variables from an SQL query and replaces them with X or N for numbers.
	# It's only slightly flawed. Don't use for anything important.
	/* static */ function generalizeSQL( $sql )
	{	
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace ( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql);
	
		$sql = str_replace ( "\\\\", "", $sql);
		$sql = str_replace ( "\\'", "", $sql);
		$sql = str_replace ( "\\\"", "", $sql);
		$sql = preg_replace ("/'.*'/s", "'X'", $sql);
		$sql = preg_replace ('/".*"/s', "'X'", $sql);
	
		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace ( "/\s+/", " ", $sql);
	
		# All numbers => N	
		$sql = preg_replace ('/-?[0-9]+/s', "N", $sql);
	
		return $sql;
	}
	
	# Determines whether a field exists in a table
	# Usually aborts on failure
	# If errors are explicitly ignored, returns NULL on failure
	function fieldExists( $table, $field, $fname = "Database::fieldExists" )
	{
		$table = $this->tableName( $table );
		$res = $this->query( "DESCRIBE $table", DB_READ, $fname );
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
	
	# Determines whether an index exists
	# Usually aborts on failure
	# If errors are explicitly ignored, returns NULL on failure
	function indexExists( $table, $index, $fname = "Database::indexExists" ) 
	{
		$info = $this->indexInfo( $table, $index, $fname );
		if ( is_null( $info ) ) {
			return NULL;
		} else {
			return $info !== false;
		}
	}
	
	function indexInfo( $table, $index, $fname = "Database::indexInfo" ) {
		# SHOW INDEX works in MySQL 3.23.58, but SHOW INDEXES does not.
		# SHOW INDEX should work for 3.x and up:
		# http://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$table = $this->tableName( $table );
		$sql = "SHOW INDEX FROM $table";
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
	function tableExists( $table )
	{
		$table = $this->tableName( $table );
		$old = $this->mIgnoreErrors;
		$this->mIgnoreErrors = true;
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1" );
		$this->mIgnoreErrors = $old;
		if( $res ) {
			$this->freeResult( $res );
			return true;
		} else {
			return false;
		}
	}

	function fieldInfo( $table, $field )
	{
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

	# INSERT wrapper, inserts an array into a table
	#
	# $a may be a single associative array, or an array of these with numeric keys, for 
	# multi-row insert.
	#
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function insertArray( $table, $a, $fname = "Database::insertArray", $options = array() )
	{
		$table = $this->tableName( $table );
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
					$sql .= ",";
				}
				$sql .= '(' . $this->makeList( $row ) . ')';
			}
		} else {
			$sql .= '(' . $this->makeList( $a ) . ')';
		}
		return !!$this->query( $sql, $fname );
	}
	
	# UPDATE wrapper, takes a condition array and a SET array
	function updateArray( $table, $values, $conds, $fname = "Database::updateArray" )
	{
		$table = $this->tableName( $table );
		$sql = "UPDATE $table SET " . $this->makeList( $values, LIST_SET );
		$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		$this->query( $sql, $fname );
	}
	
	# Makes a wfStrencoded list from an array
	# $mode: LIST_COMMA         - comma separated, no field names
	#        LIST_AND           - ANDed WHERE clause (without the WHERE)
	#        LIST_SET           - comma separated with field names, like a SET clause
	function makeList( $a, $mode = LIST_COMMA )
	{
		if ( !is_array( $a ) ) {
			wfDebugDieBacktrace( 'Database::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = "";
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= " AND ";
				} else {
					$list .= ",";
				}
			} else {
				$first = false;
			}
			if ( $mode == LIST_AND && is_numeric( $field ) ) {
				$list .= "($value)";
			} else {
				if ( $mode == LIST_AND || $mode == LIST_SET ) {
					$list .= "$field=";
				}
				$list .= $this->addQuotes( $value );
			}
		}
		return $list;
	}
	
	function selectDB( $db ) 
	{
		$this->mDBname = $db;
		mysql_select_db( $db, $this->mConn );
	}

	function startTimer( $timeout )
	{
		global $IP;

		$tid = mysql_thread_id( $this->mConn );
		exec( "php $IP/killthread.php $timeout $tid &>/dev/null &" );
	}

	function stopTimer()
	{
	}

	function tableName( $name ) {
		return $name;
	}

	function strencode( $s ) {
		return addslashes( $s );
	}

	# If it's a string, adds quotes and backslashes
	# Otherwise returns as-is
	function addQuotes( $s ) {
		if ( !is_numeric( $s ) ) {
			$s = "'" . $this->strencode( $s ) . "'";
		} else if ( is_null( $s ) ) {
			$s = 'NULL';
		}
		return $s;
	}
		
	# Returns an appropriately quoted sequence value for inserting a new row.
	# MySQL has autoincrement fields, so this is just NULL. But the PostgreSQL
	# subclass will return an integer, and save the value for insertId()
	function nextSequenceValue( $seqName ) {
		return NULL;
	}

	# USE INDEX clause
	# PostgreSQL doesn't have them and returns ""
	function useIndexClause( $index ) {
		return "USE INDEX ($index)";
	}

	# REPLACE query wrapper
	# PostgreSQL simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a 
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide. 
	# However if you do this, you run the risk of encountering errors which wouldn't have 
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = "Database::replace" ) {
		$table = $this->tableName( $table );

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		$sql = "REPLACE INTO $table (" . implode( ',', array_flip( $rows[0] ) ) .") VALUES ";
		$first = true;
		foreach ( $rows as $row ) {
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ",";
			}
			$sql .= "(" . $this->makeList( $row ) . ")";
		}
		return $this->query( $sql, $fname );
	}

	# DELETE where the condition is a join
	# MySQL does this with a multi-table DELETE syntax, PostgreSQL does it with sub-selects
	#
	# $delTable is the table to delete from
	# $joinTable is the other table
	# $delVar is the variable to join on, in the first table
	# $joinVar is the variable to join on, in the second table
	# $conds is a condition array of field names mapped to variables, ANDed together in the WHERE clause
	#
	# For safety, an empty $conds will not delete everything. If you want to delete all rows where the 
	# join condition matches, set $conds='*'
	#
	# DO NOT put the join condition in $conds
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "Database::deleteJoin" ) {
		if ( !$conds ) {
			wfDebugDieBacktrace( 'Database::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE $delTable FROM $delTable, $joinTable WHERE $delVar=$joinVar ";
		if ( $conds != '*' ) {
			$sql .= " AND " . $this->makeList( $conds, LIST_AND );
		}
		
		return $this->query( $sql, $fname );
	}

	# Returns the size of a text field, or -1 for "unlimited"
	function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SHOW COLUMNS FROM $table LIKE \"$field\";";
		$res = $this->query( $sql, "Database::textFieldSize" );
		$row = wfFetchObject( $res );
		$this->freeResult( $res );

		if ( preg_match( "/\((.*)\)/", $row->Type, $m ) ) {
			$size = $m[1];
		} else {
			$size = -1;
		}
		return $size;
	}

	function lowPriorityOption() {
		return 'LOW_PRIORITY';
	}

	# Use $conds == "*" to delete all rows
	function delete( $table, $conds, $fname = "Database::delete" ) {
		if ( !$conds ) {
			wfDebugDieBacktrace( "Database::delete() called with no conditions" );
		}
		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table ";
		if ( $conds != '*' ) {
			$sql .= "WHERE " . $this->makeList( $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}

	# INSERT SELECT wrapper
	# $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	# Source items may be literals rather than field names, but strings should be quoted with Database::addQuotes()
	# $conds may be "*" to copy the whole table
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'Database::insertSelect' ) {
		$destTable = $this->tableName( $destTable );
		$srcTable = $this->tableName( $srcTable );
		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ")" .
			" SELECT " . implode( ',', $varMap ) . 
			" FROM $srcTable";
		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}

	function limitResult($limit,$offset) {
		return " LIMIT ".(is_numeric($offset)?"{$offset},":"")."{$limit} ";
	}
} 

class DatabaseMysql extends Database {
	# Inherit all
}

#------------------------------------------------------------------------------
# Global functions
#------------------------------------------------------------------------------

/* Standard fail function, called by default when a connection cannot be established
   Displays the file cache if possible */
function wfEmergencyAbort( &$conn, $error ) {
	global $wgTitle, $wgUseFileCache, $title, $wgInputEncoding, $wgSiteNotice, $wgOutputEncoding;
	
	if( !headers_sent() ) {
		header( "HTTP/1.0 500 Internal Server Error" );
		header( "Content-type: text/html; charset=$wgOutputEncoding" );
		/* Don't cache error pages!  They cause no end of trouble... */
		header( "Cache-control: none" );
		header( "Pragma: nocache" );
	}
	$msg = $wgSiteNotice;
	if($msg == "") $msg = wfMsgNoDB( "noconnect", $error );
	$text = $msg;

	if($wgUseFileCache) {
		if($wgTitle) {
			$t =& $wgTitle;
		} else {
			if($title) {
				$t = Title::newFromURL( $title );
			} elseif (@$_REQUEST['search']) {
				$search = $_REQUEST['search'];
				echo wfMsgNoDB( "searchdisabled" );
				echo wfMsgNoDB( "googlesearch", htmlspecialchars( $search ), $wgInputEncoding );
				wfAbruptExit();
			} else {
				$t = Title::newFromText( wfMsgNoDB( "mainpage" ) );
			}
		}

		$cache = new CacheManager( $t );
		if( $cache->isFileCached() ) {
			$msg = "<p style='color: red'><b>$msg<br />\n" .
				wfMsgNoDB( "cachederror" ) . "</b></p>\n";
			
			$tag = "<div id='article'>";
			$text = str_replace(
				$tag,
				$tag . $msg,
				$cache->fetchPageText() );
		}
	}
	
	echo $text;
	wfAbruptExit();
}

function wfLimitResult( $limit, $offset ) {
	return " LIMIT ".(is_numeric($offset)?"{$offset},":"")."{$limit} ";
}

?>
