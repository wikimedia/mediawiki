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

	function Database()
	{
		global $wgOut;
		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = NULL;
		}
		$this->mOut =& $wgOut;
	
	}
	
	/* static */ function newFromParams( $server, $user, $password, $dbName, 
		$failFunction = false, $debug = false, $bufferResults = true, $ignoreErrors = false )
	{
		$db = new Database;
		$db->mFailFunction = $failFunction;
		$db->mIgnoreErrors = $ignoreErrors;
		$db->mDebug = $debug;
		$db->mBufferResults = $bufferResults;
		$db->open( $server, $user, $password, $dbName );
		return $db;
	}
	
	# Usually aborts on failure
	# If the failFunction is set to a non-zero integer, returns success
	function open( $server, $user, $password, $dbName )
	{
		global $wgEmergencyContact;
		
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
	function query( $sql, $fname = "" )
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
		if( $this->mBufferResults ) {
			$ret = mysql_query( $sql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $this->mConn );
		}
	
		if ( false === $ret ) {
			$error = mysql_error( $this->mConn );
			$errno = mysql_errno( $this->mConn );
			if( $this->mIgnoreErrors ) {
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
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}
	
 	function fetchRow( $res ) {
		@$row = mysql_fetch_array( $res );
		if (mysql_errno() ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( mysql_error() ) );
		}
		return $row;
	}	

	function numRows( $res ) {
		@$n = mysql_num_rows( $res ); 
		if( mysql_errno() ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( mysql_error() ) );
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
		$sql = "UPDATE $table SET $var = '" .
		  wfStrencode( $value ) . "' WHERE ($cond)";
		return !!$this->query( $sql, DB_WRITE, $fname );
	}
	
	# Simple SELECT wrapper, returns a single field, input must be encoded
	# Usually aborts on failure
	# If errors are explicitly ignored, returns FALSE on failure
	function get( $table, $var, $cond, $fname = "Database::get" )
	{
		$sql = "SELECT $var FROM $table WHERE ($cond)";
		$result = $this->query( $sql, DB_READ, $fname );
	
		$ret = "";
		if ( mysql_num_rows( $result ) > 0 ) {
			$s = mysql_fetch_object( $result );
			$ret = $s->$var;
			mysql_free_result( $result );
		}
		return $ret;
	}
	
	# More complex SELECT wrapper, single row only
	# Aborts or returns FALSE on error
	# Takes an array of selected variables, and a condition map, which is ANDed
	# e.g. getArray( "cur", array( "cur_id" ), array( "cur_namespace" => 0, "cur_title" => "Astronomy" ) )
	#   would return an object where $obj->cur_id is the ID of the Astronomy article
	function getArray( $table, $vars, $conds, $fname = "Database::getArray" )
	{
		$vars = implode( ",", $vars );
		if ( $conds !== false ) {
			$where = Database::makeList( $conds, LIST_AND );
			$sql = "SELECT $vars FROM $table WHERE $where LIMIT 1";
		} else {
			$sql = "SELECT $vars FROM $table LIMIT 1";
		}
		$res = $this->query( $sql, $fname );
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
		# SHOW INDEX works in MySQL 3.23.58, but SHOW INDEXES does not.
		# SHOW INDEX should work for 3.x and up:
		# http://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$sql = "SHOW INDEX FROM $table";
		$res = $this->query( $sql, DB_READ, $fname );
		if ( !$res ) {
			return NULL;
		}
		
		$found = false;
		
		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->Key_name == $index ) {
				$found = true;
				break;
			}
		}
		return $found;
	}
	
	function tableExists( $table )
	{
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
	# Keys are field names, values are values
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function insertArray( $table, $a, $fname = "Database::insertArray" )
	{
		$sql1 = "INSERT INTO $table (";
		$sql2 = "VALUES (" . Database::makeList( $a );
		$first = true;
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				$sql1 .= ",";
			}
			$first = false;
			$sql1 .= $field;
		}
		$sql = "$sql1) $sql2)";
		return !!$this->query( $sql, $fname );
	}
	
	# A cross between insertArray and getArray, takes a condition array and a SET array
	function updateArray( $table, $values, $conds, $fname = "Database::updateArray" )
	{
		$sql = "UPDATE $table SET " . $this->makeList( $values, LIST_SET );
		$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		$this->query( $sql, $fname );
	}
	
	# Makes a wfStrencoded list from an array
	# $mode: LIST_COMMA - comma separated, no field names
	#        LIST_AND   - ANDed WHERE clause (without the WHERE)
	#        LIST_SET   - comma separated with field names, like a SET clause
	/* static */ function makeList( $a, $mode = LIST_COMMA )
	{
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
			if ( $mode == LIST_AND || $mode == LIST_SET ) {
				$list .= "$field=";
			}
			# This will also quote numeric values. This should be harmless,
			# and protects against weird problems that occur when they really
			# _are_ strings such as article titles and string->number->string
			# conversion is not 1:1.
			$list .= "'" . wfStrencode( $value ) . "'";
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
		if( function_exists( "mysql_thread_id" ) ) {
			# This will kill the query if it's still running after $timeout seconds.
			$tid = mysql_thread_id( $this->mConn );
			exec( "php $IP/includes/killthread.php " . IntVal( $timeout ) . " $tid &>/dev/null &" );
		}
	}

	function stopTimer()
	{
	}
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

function wfStrencode( $s )
{
	return addslashes( $s );
}

function wfLimitResult( $limit, $offset ) {
	return " LIMIT ".(is_numeric($offset)?"{$offset},":"")."{$limit} ";
}


?>
