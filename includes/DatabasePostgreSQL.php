<?php
# $Id$
#
# DO NOT USE !!!  Unless you want to help developping it.
#
# This file is an attempt to port the mysql database layer to postgreSQL. The
# only thing done so far is s/mysql/pg/ and dieing if function haven't been
# ported.
# 
# As said brion 07/06/2004 :
# "table definitions need to be changed. fulltext index needs to work differently
#  things that use the last insert id need to be changed. Probably other things
#  need to be changed. various semantics may be different."
#
# Hashar

require_once( "FulltextStoplist.php" );
require_once( "CacheManager.php" );

define( "DB_READ", -1 );
define( "DB_WRITE", -2 );
define( "DB_LAST", -3 );

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
	/* private */ var $mLastResult;

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
		
		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;
		
		$success = false;
		
		
		if ( "" != $dbName ) {
			# start a database connection
			@$this->mConn = pg_connect("host=$server dbname=$dbName user=$user password=$password");
			if ( $this->mConn == false ) {
				wfDebug( "DB connection error\n" );
				wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
				wfDebug( $this->lastError()."\n" );
			} else { 
				$this->mOpened = true;
			}
		}
		return $this->mConn;
	}
	
	# Closes a database connection, if it is open
	# Returns success, true if already closed
	function close()
	{
		$this->mOpened = false;
		if ( $this->mConn ) {
			return pg_close( $this->mConn );
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

		$ret = pg_query( $this->mConn , $sql);
		$this->mLastResult = $ret;
		if ( false == $ret ) {
			$error = pg_last_error( $this->mConn );
			// TODO FIXME : no error number function in postgre
			// $errno = mysql_errno( $this->mConn );
			if( $this->mIgnoreErrors ) {
				wfDebug("SQL ERROR (ignored): " . $error . "\n");
			} else {
				wfDebug("SQL ERROR: " . $error . "\n");
				if ( $this->mOut ) {
					// this calls wfAbruptExit()
					$this->mOut->databaseError( $fname, $sql, $error, 0 ); 				
				}
			}
		}
		
		if ( $wgProfiling ) {
			wfProfileOut( $profName );
		}
		return $ret;
	}
	
	function freeResult( $res ) {
		if ( !@pg_free_result( $res ) ) {
			wfDebugDieBacktrace( "Unable to free PostgreSQL result\n" );
		}
	}
	function fetchObject( $res ) {
		@$row = pg_fetch_object( $res );
		# FIXME: HACK HACK HACK HACK debug
		
		# TODO:
		# hashar : not sure if the following test really trigger if the object
		#          fetching failled.
		if( pg_last_error($this->mConn) ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function fetchRow( $res ) {
		@$row = pg_fetch_array( $res );
                if( pg_last_error($this->mConn) ) {
                        wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
                }
		return $row;
	}

	function numRows( $res ) {
		@$n = pg_num_rows( $res ); 
		if( pg_last_error($this->mConn) ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $n;
	}
	function numFields( $res ) { return pg_num_fields( $res ); }
	function fieldName( $res, $n ) { return pg_field_name( $res, $n ); }
	// TODO FIXME: need to implement something here
	function insertId() { 
		//return mysql_insert_id( $this->mConn );
		wfDebugDieBacktrace( "Database::insertId() error : not implemented for postgre, use sequences" );
	}
	function dataSeek( $res, $row ) { return pg_result_seek( $res, $row ); }
	function lastErrno() { return $this->lastError(); }
	function lastError() { return pg_last_error(); }
	function affectedRows() { 
		return pg_affected_rows( $this->mLastResult ); 
	}
	
	# Simple UPDATE wrapper
	# Usually aborts on failure
	# If errors are explicitly ignored, returns success
	function set( $table, $var, $value, $cond, $fname = "Database::set" )
	{
		$sql = "UPDATE \"$table\" SET \"$var\" = '" .
		  wfStrencode( $value ) . "' WHERE ($cond)";
		return !!$this->query( $sql, DB_WRITE, $fname );
	}
	
	# Simple SELECT wrapper, returns a single field, input must be encoded
	# Usually aborts on failure
	# If errors are explicitly ignored, returns FALSE on failure
	function get( $table, $var, $cond, $fname = "Database::get" )
	{
		$from=$table?" FROM \"$table\" ":"";
		$where=$cond?" WHERE ($cond)":"";

		$sql = "SELECT $var $from $where";

		$result = $this->query( $sql, DB_READ, $fname );
	
		$ret = "";
		if ( pg_num_rows( $result ) > 0 ) {
			$s = pg_fetch_array( $result );
			$ret = $s[0];
			pg_free_result( $result );
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
		$sql = "SELECT \"$vars\" FROM \"$table\" WHERE $where LIMIT 1";
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
		$res = $this->query( "DESCRIBE '$table'", DB_READ, $fname );
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
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='$table'";
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
		$res = $this->query( "SELECT 1 FROM '$table' LIMIT 1" );
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
		$res = $this->query( "SELECT * FROM '$table' LIMIT 1" );
		$n = pg_num_fields( $res );
		for( $i = 0; $i < $n; $i++ ) {
			// FIXME
			wfDebugDieBacktrace( "Database::fieldInfo() error : mysql_fetch_field() not implemented for postgre" );
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
		$sql1 = "INSERT INTO \"$table\" (";
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
		$sql = "UPDATE '$table' SET " . $this->makeList( $values, LIST_SET );
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
			if ( !is_numeric( $value ) ) {
				$list .= "'" . wfStrencode( $value ) . "'";
			} else {
				$list .= $value;
			}
		}
		return $list;
	}
	
	function startTimer( $timeout )
	{
		global $IP;
		wfDebugDieBacktrace( "Database::startTimer() error : mysql_thread_id() not implemented for postgre" );
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
	
	/* Don't cache error pages!  They cause no end of trouble... */
	header( "Cache-control: none" );
	header( "Pragma: nocache" );
	echo $text;
	wfAbruptExit();
}

function wfStrencode( $s )
{
	return pg_escape_string( $s );
}

function wfLimitResult( $limit, $offset ) {
        return " LIMIT $limit ".(is_numeric($offset)?" OFFSET {$offset} ":"");
}

?>
