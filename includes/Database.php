<?php
include_once( "FulltextStoplist.php" );
include_once( "CacheManager.php" );

define( "DB_READ", -1 );
define( "DB_WRITE", -2 );
define( "DB_LAST", -3 );

define( "LIST_COMMA", 0 );
define( "LIST_AND", 1 );

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
	function &setOutputPage( &$out ) { return wfSetRef( $this->mOut, $out ); }
	
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
		$this->mOut = $wgOut;
	
	}
	
	/* static */ function newFromParams( $server, $user, $password, $dbName, 
		$failFunction = false, $debug = false, $bufferResults = true, $ignoreErrors = false )
	{
		$db = new Database;
		$db->mFailFunction = $failFunction;
		$db->mIgnoreErrors = $ignoreErrors;
		$db->mDebug = $debug;
		$db->open( $server, $user, $password, $dbName );
		return $db;
	}
	
	# Usually aborts on failure
	# If the failFunction is set to a non-zero integer, returns success
	function open( $server, $user, $password, $dbName )
	{
		global $wgEmergencyContact;
		
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
				wfDebug( "DB connect error: " . $this->lastError() . "\n" );
				wfDebug( "Server: $server, User: $user, Password: " . 
					substr( $password, 0, 3 ) . "...\n" );
				$success = false;
			}
		} else {
			# Delay USE
			$success = true;
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
				$this->$mFailFunction( $this );
			}
		} else {
			wfEmergencyAbort( $this );
		}
	}
	
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function query( $sql, $fname = "" )
	{
		global $wgProfiling;
		
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
			if( $this->mIgnoreErrors ) {
				wfDebug("SQL ERROR (ignored): " . mysql_error( $this->mConn ) . "\n");
			} else {
				wfDebug("SQL ERROR: " . mysql_error( $this->mConn ) . "\n");
				if ( $this->mOut ) {
					// this calls wfAbruptExit()
					$this->mOut->databaseError( $fname, $this ); 				
				}
			}
		}
		
		if ( $wgProfiling ) {
			wfProfileOut( $profName );
		}
		return $ret;
	}
	
	function freeResult( $res ) { mysql_free_result( $res ); }
	function fetchObject( $res ) { return mysql_fetch_object( $res ); }
	function numRows( $res ) { return mysql_num_rows( $res ); }
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
	
	# Simple SELECT wrapper
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
		$where = Database::makeList( $conds, LIST_AND );
		$sql = "SELECT $vars FROM $table WHERE $where";
		$res = $this->query( $sql, $fname );
		if ( $res === false || !$this->numRows( $res ) ) {
			return false;
		}
		return $this->fetchObject( $res );
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
		$sql = "SHOW INDEXES FROM $table";
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
		$old = $this->setIgnoreErrors( true );
		$res = $this->query( "SELECT 1 FROM $table LIMIT 1" );
		$this->setIgnoreErrors( $old );
		if( $res ) {
			$this->freeResult( $res );
			return true;
		} else {
			# Clear error flag
			wfLastError();
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
	
	# Makes a wfStrencoded list from an array
	# $mode: LIST_COMMA - comma separated
	#        LIST_AND   - ANDed WHERE clause (without the WHERE)
	/* static */ function makeList( $a, $mode = LIST_COMMA)
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
			if ( $mode == LIST_AND ) {
				$list .= "$field=";
			}
			if ( is_string( $value ) ) {
				$list .= "'" . wfStrencode( $value ) . "'";
			} else {
				$list .= $value;
			}
		}
		return $list;
	}
	
	function selectDB( $db ) 
	{
		$this->mDatabase = $db;
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

}

#------------------------------------------------------------------------------
# Global functions
#------------------------------------------------------------------------------

/* Standard fail function, called by default when a connection cannot be established
   Displays the file cache if possible */
function wfEmergencyAbort( &$conn ) {
	global $wgTitle, $wgUseFileCache, $title, $wgInputEncoding, $wgSiteNotice, $wgOutputEncoding;
	
	header( "Content-type: text/html; charset=$wgOutputEncoding" );
	$msg = $wgSiteNotice;
	if($msg == "") $msg = wfMsgNoDB( "noconnect" );
	$text = $msg;

	if($wgUseFileCache) {
		if($wgTitle) {
			$t =& $wgTitle;
		} else {
			if($title) {
				$t = Title::newFromURL( $title );
			} elseif ($_REQUEST['search']) {
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
			$msg = "<p style='color: red'><b>$msg<br>\n" .
				wfMsgNoDB( "cachederror" ) . "</b></p>\n";
			
			$tag = "<div id='article'>";
			$text = str_replace(
				$tag,
				$tag . $msg,
				$cache->fetchPageText() );
		}
	}
	
	/* Don't cache error pages!  They cause no end of trouble... */
	header( "Cache-control: none" );
	header( "Pragma: nocache" );
	echo $text;
	wfAbruptExit();
}

function wfStrencode( $s )
{
	return addslashes( $s );
}

# Ideally we'd be using actual time fields in the db
function wfTimestamp2Unix( $ts ) {
	return gmmktime( ( (int)substr( $ts, 8, 2) ),
		  (int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
		  (int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
		  (int)substr( $ts, 0, 4 ) );
}

function wfUnix2Timestamp( $unixtime ) {
	return gmdate( "YmdHis", $unixtime );
}

function wfTimestampNow() {
	# return NOW
	return gmdate( "YmdHis" );
}

# Sorting hack for MySQL 3, which doesn't use index sorts for DESC
function wfInvertTimestamp( $ts ) {
	return strtr(
		$ts,
		"0123456789",
		"9876543210"
	);
}
?>
