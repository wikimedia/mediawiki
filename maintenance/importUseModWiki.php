<?php

/*
	Import data from a UseModWiki into a PediaWiki wiki
	2003-02-09 Brion VIBBER <brion@pobox.com>
	Based loosely on Magnus's code from 2001-2002

	  Updated limited version to get something working temporarily
	  2003-10-09
	  Be sure to run the link & index rebuilding scripts!
	  
	  Some more munging for charsets etc
	  2003-11-28

  */

/* Set these correctly! */
$wgImportEncoding = "CP1252"; /* We convert all to UTF-8 */
$wgRootDirectory = "/home/usemod/wiki-ia/lib-http/db/wiki";

/* globals */
$wgFieldSeparator = "\xb3"; # Some wikis may use different char
	$FS = $wgFieldSeparator ;
	$FS1 = $FS."1" ;
	$FS2 = $FS."2" ;
	$FS3 = $FS."3" ;

$conversiontime = wfTimestampNow(); # Conversions will be marked with this timestamp
$usercache = array();

wfSeedRandom();
importPages();

# ------------------------------------------------------------------------------

function importPages()
{
	global $wgRootDirectory;
	
	$letters = array(
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
		'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
		'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'other' );
	foreach( $letters as $letter ) {
		$dir = "$wgRootDirectory/page/$letter";
		if( is_dir( $dir ) )
			importPageDirectory( $dir );
	}
}

function importPageDirectory( $dir, $prefix = "" )
{
	echo "\n-- Checking page directory $dir\n";
	$mydir = opendir( $dir );
	while( $entry = readdir( $mydir ) ) {
		if( preg_match( '/^(.+)\.db$/', $entry, $m ) ) {
			echo importPage( $prefix . $m[1] );
		} else {
			if( is_dir( "$dir/$entry" ) ) {
				if( $entry != '.' && $entry != '..' ) {
					importPageDirectory( "$dir/$entry", "$entry/" );
				}
			} else {
				echo "-- File '$entry' doesn't seem to contain an article. Skipping.\n";
			}
		}
	}
}


# ------------------------------------------------------------------------------

/* fetch_ functions
	Grab a given item from the database
	*/
function fetchUser( $uid )
{
	die ("fetchUser not implemented" );
	
	global $FS,$FS2,$FS3, $wgRootDirectory;
	
	$fname = $wgRootDirectory . "/page/" . $title;
	if( !file_exists( $fname ) ) return false;
	
	$data = splitHash( implode( "", file( $fname ) ) );
	# enough?
	
	return $data;
}

function useModFilename( $title ) {
	$c = substr( $title, 0, 1 );
	if(preg_match( '/[A-Z]/', $c ) ) {
		return "$c/$title";
	}
	return "other/$title";
}

function fetchPage( $title )
{
	global $FS,$FS1,$FS2,$FS3, $wgRootDirectory;
	
	$fname = $wgRootDirectory . "/page/" . useModFilename( $title ) . ".db";
	if( !file_exists( $fname ) ) {
		die( "Couldn't open file '$fname' for page '$title'.\n" );
	}
	
	$page = splitHash( $FS1, file_get_contents( $fname ) );
	$section = splitHash( $FS2, $page["text_default"] );
	$text = splitHash( $FS3, $section["data"] );
	
	return array2object( array( "text" => $text["text"] , "summary" => $text["summary"] ,
		"minor" => $text["minor"] , "ts" => $section["ts"] ,
		"username" => $section["username"] , "host" => $section["host"] ) );
}

function fetchKeptPages( $title )
{
	global $FS,$FS1,$FS2,$FS3, $wgRootDirectory, $wgTimezoneCorrection;
	
	$fname = $wgRootDirectory . "/keep/" . useModFilename( $title ) . ".kp";
	if( !file_exists( $fname ) ) return array();
	
	$keptlist = explode( $FS1, file_get_contents( $fname ) );
	array_shift( $keptlist ); # Drop the junk at beginning of file
	
	$revisions = array();
	foreach( $keptlist as $rev ) {
		$section = splitHash( $FS2, $rev );
		$text = splitHash( $FS3, $section["data"] );
		if ( $text["text"] && $text["minor"] != "" && ( $section["ts"]*1 > 0 ) ) {
			array_push( $revisions, array2object( array ( "text" => $text["text"] , "summary" => $text["summary"] ,
				"minor" => $text["minor"] , "ts" => $section["ts"] ,
				"username" => $section["username"] , "host" => $section["host"] ) ) );
		} else {
			echo "-- skipped a bad old revision\n";
		}
	}
	return $revisions;
}

function splitHash ( $sep , $str ) {
	$temp = explode ( $sep , $str ) ;
	$ret = array () ;
	for ( $i = 0; $i+1 < count ( $temp ) ; $i++ ) {
		$ret[$temp[$i]] = $temp[++$i] ;
		}
	return $ret ;
	}


/* import_ functions
	Take a fetched item and produce SQL
	*/

/* importUser
	$uid is the UseMod user id number.
	The new ones will be assigned arbitrarily and are for internal use only.
	
	THIS IS DELAYED SINCE PUBLIC DUMPS DONT INCLUDE USER DIR
	*/
function importUser( $uid )
{
	global $last_uid, $user_list, $wgTimestampCorrection;
	die("importUser NYI");
	return "";

	$stuff = fetchUser( $uid );
	$last_uid++;

	$name = wfStrencode( $stuff->username );
	$hash = md5hash( $stuff->password ); # Doable?
	$tzoffset = $stuff['tzoffset'] - ($wgTimestampCorrection / 3600); # -8 to 0; +9 to +1
	$hideminor = ($stuff['rcall'] ? 0 : 1);
	$options = "cols={$stuff['editcols']}
rows={$stuff['editrows']}
rcdays={$stuff['rcdays']}
timecorrection={$tzoffset}
hideminor={$hideminor}
	";
	
	$sql = "INSERT
		INTO user (user_id,user_name,user_password,user_options)
		VALUES ({$last_uid},'{$name}','{$hash}','{$options}');\n";
	return $sql;
}

function checkUserCache( $name, $host )
{
	global $usercache;

	if( $name ) {
		if( in_array( $name, $usercache ) ) {
			$userid = $usercache[$name];
		} else {
			# If we haven't imported user accounts
			$userid = 0;
		}
		$username = wfStrencode( $name );
	} else {
		$userid = 0;
		$username = wfStrencode( $host );
	}
	return array( $userid, $username );
}

function importPage( $title )
{
	global $usercache;
	global $conversiontime;
	
	echo "\n-- Importing page $title\n";
	$page = fetchPage( $title );

	$newtitle = wfStrencode( recodeText( $title ) );
	$namespace = 0;
	
	# Current revision:
	$text = wfStrencode( recodeText( $page->text ) );
	$comment = wfStrencode( recodeText( $page->summary ) );
	$minor = ($page->minor ? 1 : 0);
	list( $userid, $username ) = checkUserCache( $page->username, $page->host );
	$username = wfStrencode( recodeText( $username ) );
	$timestamp = wfUnix2Timestamp( $page->ts );
	$redirect = ( preg_match( '/^#REDIRECT/', $page->text ) ? 1 : 0 );
	$random = mt_rand() / mt_getrandmax();
	$inverse = wfInvertTimestamp( $timestamp );
	$sql = "
INSERT
	INTO cur (cur_namespace,cur_title,cur_text,cur_comment,cur_user,cur_user_text,cur_timestamp,inverse_timestamp,cur_touched,cur_minor_edit,cur_is_redirect,cur_random) VALUES
	($namespace,'$newtitle','$text','$comment',$userid,'$username','$timestamp','$inverse','$conversiontime',$minor,$redirect,$random);\n";

	# History
	$revisions = fetchKeptPages( $title );
	if(count( $revisions ) == 0 ) {
		return $sql;
	}
	
	$any = false;
	$sql .= "INSERT
	INTO old (old_namespace,old_title,old_text,old_comment,old_user,old_user_text,old_timestamp,inverse_timestamp,old_minor_edit) VALUES\n";
	foreach( $revisions as $rev ) {
		$text = wfStrencode( recodeText( $rev->text ) );
		$minor = ($rev->minor ? 1 : 0);
		list( $userid, $username ) = checkUserCache( $rev->username, $rev->host );
		$username = wfStrencode( recodeText( $username ) );
		$timestamp = wfUnix2Timestamp( $rev->ts );
		$inverse = wfInvertTimestamp( $timestamp );
		$comment = wfStrencode( recodeText( $rev->summary ) );
		
		if($any) $sql .= ",";
		$sql .= "\n\t($namespace,'$newtitle','$text','$comment',$userid,'$username','$timestamp','$inverse',$minor)";
		$any = true;
	}
	$sql .= ";\n\n";
	return $sql;
}

# Whee!
function recodeText( $string ) {
	global $wgImportEncoding;
	# For currently latin-1 wikis
	$string = str_replace( "\r\n", "\n", $string );
	$string = iconv( $wgImportEncoding, "UTF-8", $string );
	$string = wfMungeToUtf8( $string ); # Any old &#1234; stuff
	return $string;
}

function wfUtf8Sequence($codepoint) {
	if($codepoint <     0x80) return chr($codepoint);
	if($codepoint <    0x800) return chr($codepoint >>  6 & 0x3f | 0xc0) .
                                     chr($codepoint       & 0x3f | 0x80);
    if($codepoint <  0x10000) return chr($codepoint >> 12 & 0x0f | 0xe0) .
                                     chr($codepoint >>  6 & 0x3f | 0x80) .
                                     chr($codepoint       & 0x3f | 0x80);
	if($codepoint < 0x100000) return chr($codepoint >> 18 & 0x07 | 0xf0) . # Double-check this
	                                 chr($codepoint >> 12 & 0x3f | 0x80) .
                                     chr($codepoint >>  6 & 0x3f | 0x80) .
                                     chr($codepoint       & 0x3f | 0x80);
	# Doesn't yet handle outside the BMP
	return "&#$codepoint;";
}

function wfMungeToUtf8($string) {
	$string = preg_replace ( '/&#([0-9]+);/e', 'wfUtf8Sequence($1)', $string );
	$string = preg_replace ( '/&#x([0-9a-f]+);/ie', 'wfUtf8Sequence(0x$1)', $string );
	# Should also do named entities here
	return $string;
}

function wfStrencode( $string ) {
	return mysql_escape_string( $string );
}

function wfUnix2Timestamp( $unixtime ) {
        return gmdate( "YmdHis", $unixtime );
}

function wfTimestamp2Unix( $ts )
{
        return gmmktime( ( (int)substr( $ts, 8, 2) ),
                  (int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
                  (int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
                  (int)substr( $ts, 0, 4 ) );
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

function wfSeedRandom()
{
	$seed = hexdec(substr(md5(microtime()),-8)) & 0x7fffffff;
	mt_srand( $seed );
	$wgRandomSeeded = true;
}

function array2object( $arr ) {
	$o = (object)0;
	foreach( $arr as $x => $y ) {
		$o->$x = $y;
	}
	return $o;
}

?>
