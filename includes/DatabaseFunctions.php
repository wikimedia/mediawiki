<?
include_once( "FulltextStoplist.php" );
include_once( "CacheManager.php" );

define( "DB_READ", -1 );
define( "DB_WRITE", -2 );
define( "DB_LAST", -3 );

$wgLastDatabaseQuery = "";

/* private */ $wgBufferSQLResults = true;
/* private */ $wgIgnoreSQLErrors = false;

function wfGetDB( $altuser = "", $altpassword = "", $altserver = "", $altdb = "" )
{
	global $wgDBserver, $wgDBuser, $wgDBpassword;
	global $wgDBname, $wgDBconnection, $wgEmergencyContact;
	
	$noconn = wfMsgNoDB( "noconnect", $wgDBserver );
	$nodb = wfMsgNoDB( "nodb", $wgDBname );

	$helpme = "\n<p>If this error persists after reloading and clearing " .
	  "your browser cache, please notify the <a href=\"mailto:" .
	  $wgEmergencyContact . "\">Wikipedia developers</a>.</p>";

	if ( $altuser != "" ) {
		$serve = ($altserver ? $altserver : $wgDBserver );
		$db = ($altdb ? $altdb : $wgDBname );
		$wgDBconnection = mysql_connect( $serve, $altuser, $altpassword )
			or die( "bad sql user" );
		mysql_select_db( $db, $wgDBconnection ) or die(
		  htmlspecialchars(mysql_error()) );
	}

	if ( ! $wgDBconnection ) {
		@$wgDBconnection = mysql_connect( $wgDBserver, $wgDBuser, $wgDBpassword )
			or wfEmergencyAbort();
		
		@mysql_select_db( $wgDBname, $wgDBconnection )
			or wfEmergencyAbort();
	}
	# mysql_ping( $wgDBconnection );
	return $wgDBconnection;
}

/* Call this function if we couldn't contact the database...
   We'll try to use the cache to display something in the meantime */
function wfEmergencyAbort( $msg = "" ) {
	global $wgTitle, $wgUseFileCache, $title, $wgOutputEncoding;
	
	header( "Content-type: text/html; charset=$wgOutputEncoding" );
	if($msg == "") $msg = wfMsgNoDB( "noconnect" );
	$text = $msg;

	if($wgUseFileCache) {
		if($wgTitle) {
			$t =& $wgTitle;
		} else {
			if($title) {
				$t = Title::newFromURL( $title );
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

# $db: DB_READ  = -1    read from slave (or only server)
#      DB_WRITE = -2    write to master (or only server)
#      0,1,2,...        query a database with a specific index
# Replication is not actually implemented just yet
function wfQuery( $sql, $db, $fname = "" )
{
	global $wgLastDatabaseQuery, $wgOut, $wgDebugDumpSql, $wgBufferSQLResults, 
		$wgIgnoreSQLErrors, $wgProfiling;

	if ( $wgProfiling ) {
		# wfGeneralizeSQL will probably cut down the query to reasonable
		# logging size most of the time. The substr is really just a sanity check.
		$profName = "wfQuery: " . substr( wfGeneralizeSQL( $sql ), 0, 255 ); 
		wfProfileIn( $profName );
	}

	if ( !is_numeric( $db ) ) {
		# Someone has tried to call this the old way
		$wgOut->fatalError( wfMsgNoDB( "wrong_wfQuery_params", $db, $sql ) );
	}
	
	$wgLastDatabaseQuery = $sql;
	
	if( $wgDebugDumpSql ) {
		$sqlx = substr( $sql, 0, 500 );
		$sqlx = wordwrap(strtr($sqlx,"\t\n","  "));
		wfDebug( "SQL: $sqlx\n" );
	}

	$conn = wfGetDB();
	if( $wgBufferSQLResults ) {
		$ret = mysql_query( $sql, $conn );
	} else {
		$ret = mysql_unbuffered_query( $sql, $conn );
	}

	if ( false === $ret ) {
		if( $wgIgnoreSQLErrors ) {
			wfDebug("SQL ERROR (ignored): " . mysql_error( $conn ) . "\n");
		} else {
			wfDebug("SQL ERROR: " . mysql_error( $conn ) . "\n");
			$wgOut->databaseError( $fname ); // calls wfAbruptExit()
		}
	}
	
	if ( $wgProfiling ) {
		wfProfileOut( $profName );
	}
	return $ret;
}

# Turns buffering of SQL result sets on (true) or off (false). Default is
# "on" and it should not be changed without good reasons. 
# Returns the previous state.

function wfBufferSQLResults( $newstate ){
	global $wgBufferSQLResults;
	$oldstate = $wgBufferSQLResults;
	$wgBufferSQLResults = $newstate;
	return $oldstate;
}

# Turns on (false) or off (true) the automatic generation and sending
# of a "we're sorry, but there has been a database error" page on
# database errors. Default is on (false). When turned off, the
# code should use wfLastErrno() and wfLastError() to handle the
# situation as appropriate.
# Returns the previous state.

function wfIgnoreSQLErrors( $newstate ){
	global $wgIgnoreSQLErrors;
	$oldstate = $wgIgnoreSQLErrors;
	$wgIgnoreSQLErrors = $newstate;	
	return $oldstate;
}

function wfFreeResult( $res ) { mysql_free_result( $res ); }
function wfFetchObject( $res ) { return mysql_fetch_object( $res ); }
function wfNumRows( $res ) { return mysql_num_rows( $res ); }
function wfNumFields( $res ) { return mysql_num_fields( $res ); }
function wfFieldName( $res, $n ) { return mysql_field_name( $res, $n ); }
function wfInsertId() { return mysql_insert_id( wfGetDB() ); }
function wfDataSeek( $res, $row ) { return mysql_data_seek( $res, $row ); }
function wfLastErrno() { return mysql_errno(); }
function wfLastError() { return mysql_error(); }
function wfAffectedRows() { return mysql_affected_rows( wfGetDB() ); }

function wfLastDBquery()
{
	global $wgLastDatabaseQuery;
	return $wgLastDatabaseQuery;
}

function wfSetSQL( $table, $var, $value, $cond )
{
	$sql = "UPDATE $table SET $var = '" .
	  wfStrencode( $value ) . "' WHERE ($cond)";
	wfQuery( $sql, DB_WRITE, "wfSetSQL" );
}

function wfGetSQL( $table, $var, $cond )
{
	$sql = "SELECT $var FROM $table WHERE ($cond)";
	$result = wfQuery( $sql, DB_READ, "wfGetSQL" );

	$ret = "";
	if ( mysql_num_rows( $result ) > 0 ) {
		$s = mysql_fetch_object( $result );
		$ret = $s->$var;
		mysql_free_result( $result );
	}
	return $ret;
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

# Removes most variables from an SQL query and replaces them with X or N for numbers.
# It's only slightly flawed. Don't use for anything important.
function wfGeneralizeSQL( $sql )
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

function wfFieldExists( $table, $field )
{
	$fname = "wfFieldExists";
	$res = wfQuery( "DESCRIBE $table", DB_READ, $fname );
	$found = false;
	
	while ( $row = wfFetchObject( $res ) ) {
		if ( $row->Field == $field ) {
			$found = true;
			break;
		}
	}
	return $found;
}

function wfIndexExists( $table, $index ) 
{
	global $wgDBname;
	$fname = "wfIndexExists";
	$sql = "SHOW INDEXES FROM $table";
	$res = wfQuery( $sql, DB_READ, $fname );
	$found = false;
	while ( $row = wfFetchObject( $res ) ) {
		if ( $row->Key_name == $index ) {
			$found = true;
			break;
		}
	}
	return $found;
}
?>
