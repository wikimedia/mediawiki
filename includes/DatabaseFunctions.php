<?
global $IP;
include_once( "$IP/FulltextStoplist.php" );
include_once( "$IP/CacheManager.php" );

$wgLastDatabaseQuery = "";

function wfGetDB( $altuser = "", $altpassword = "" )
{
	global $wgDBserver, $wgDBuser, $wgDBpassword;
	global $wgDBname, $wgDBconnection, $wgEmergencyContact;

	$noconn = str_replace( "$1", $wgDBserver, wfMsg( "noconnect" ) );
	$nodb = str_replace( "$1", $wgDBname, wfMsg( "nodb" ) );

	$helpme = "\n<p>If this error persists after reloading and clearing " .
	  "your browser cache, please notify the <a href=\"mailto:" .
	  $wgEmergencyContact . "\">Wikipedia developers</a>.</p>";

	if ( $altuser != "" ) {
		$wgDBconnection = mysql_connect( $wgDBserver, $altuser, $altpassword )
			or die( "bad sql user" );
		mysql_select_db( $wgDBname, $wgDBconnection ) or die(
		  htmlspecialchars(mysql_error()) );
	}

	if ( ! $wgDBconnection ) {
		@$wgDBconnection = mysql_pconnect( $wgDBserver, $wgDBuser, $wgDBpassword )
			or wfEmergencyAbort();
		
		if( !mysql_select_db( $wgDBname, $wgDBconnection ) ) {
			/* Persistent connections may become stuck in an unusable state */
			wfDebug( "Persistent connection is broken?\n", true );
			
			@$wgDBconnection = mysql_connect( $wgDBserver, $wgDBuser, $wgDBpassword )
				or wfEmergencyAbort();
			
			@mysql_select_db( $wgDBname, $wgDBconnection )
				or wfEmergencyAbort();
		}
	}
	# mysql_ping( $wgDBconnection );
	return $wgDBconnection;
}

/* Call this function if we couldn't contact the database...
   We'll try to use the cache to display something in the meantime */
function wfEmergencyAbort( $msg = "" ) {
	global $wgTitle, $wgUseFileCache, $title;
	
	if($msg == "") $msg = wfMsg( "noconnect" );
	$text = $msg;

	if($wgUseFileCache) {
		if($wgTitle) {
			$t =& $wgTitle;
		} else {
			if($title) {
				$t = Title::newFromURL( $title );
			} else {
				$t = Title::newFromText( wfMsg("mainpage") );
			}
		}

		$cache = new CacheManager( $t );
		if( $cache->isFileCached() ) {
			$msg = "<p style='color: red'><b>$msg<br>\n" .
				wfMsg( "cachederror" ) . "</b></p>\n";
			
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
	exit;
}

function wfQuery( $sql, $fname = "" )
{
	global $wgLastDatabaseQuery, $wgOut;
##	wfProfileIn( "wfQuery" );
	$wgLastDatabaseQuery = $sql;

	$conn = wfGetDB();
	$ret = mysql_query( $sql, $conn );

	if ( "" != $fname ) {
#		wfDebug( "{$fname}:SQL: {$sql}\n", true );
	} else {
#		wfDebug( "SQL: {$sql}\n", true );
	}
	if ( false === $ret ) {
		$wgOut->databaseError( $fname );
		exit;
	}
##	wfProfileOut();
	return $ret;
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

function wfLastDBquery()
{
	global $wgLastDatabaseQuery;
	return $wgLastDatabaseQuery;
}

function wfSetSQL( $table, $var, $value, $cond )
{
	$sql = "UPDATE $table SET $var = '" .
	  wfStrencode( $value ) . "' WHERE ($cond)";
	wfQuery( $sql, "wfSetSQL" );
}

function wfGetSQL( $table, $var, $cond )
{
	$sql = "SELECT $var FROM $table WHERE ($cond)";
	$result = wfQuery( $sql, "wfGetSQL" );

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

?>
