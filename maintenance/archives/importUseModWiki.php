<?php
/**
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */

/** */
print "This script is obsolete!";
print "It is retained in the source here in case some of its
code might be useful for ad-hoc conversion tasks, but it is
not maintained and probably won't even work as is.";
exit();

/*
	Import data from a UseModWiki into a PediaWiki wiki
	2003-02-09 Brion VIBBER <brion@pobox.com>
	Based loosely on Magnus's code from 2001-2002

	  Pass one: collect data on links & title case, users
	  Pass two: spit out SQL for
	  Separately, be sure to run the link & index rebuilding scripts!

  */

/* globals
	*/
$wgRootDirectory = "/home/brion/vikio/wiki-ca/lib-http/db/wiki";
$wgFieldSeparator = "\xb3"; # Some wikis may use different char
	$FS = $wgFieldSeparator ;
	$FS1 = $FS."1" ;
	$FS2 = $FS."2" ;
	$FS3 = $FS."3" ;

# Images to import
$imageimport = '(http:\/\/(?:www\.|meta\.|)wikipedia\.(?:com|org)\/upload\/(?:[a-z]\/[a-z][0-9]\/)?(.*\.(?:gif|jpg|jpeg|png)))';

# Number of *seconds to add* to timestamp to get UTC/GMT
#$wgTimezoneCorrection = 0;		# GMT
$wgTimezoneCorrection = 8*3600;	# PST - California

# Other options...
$historyonly = false;		# Don't add converted revisions to cur table; just get old histories
$lasthistoryonly = false;	# Only add the _original_ form of the _current_ revision

/* Vary by language */
$namespaces = array( 0 => "", 1 => "Talk:", 2 => "User:", 3 => "User_talk:", 4
=> "Wikipedia:", 5 => "Wikipedia_talk:", 6 => "Image:", 7 => "Image_talk:" );
$talkending = "Talk";
$mediatext = "Media";
$conversionscript = "Conversion script";
$conversioncomment = "Automatic conversion";
$redirectcomment = "Automatic converion, moved to \$1";
$conversiontime = gmdate( "YmdHis" ); # Conversions will be marked with this timestamp

# Stats and caches
$oldtitles = array();
$usercache = array();
$titlecache = array();
$linkcache = array();

/**
 * Some oversimplified test types
 *
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */
class Title {
	var $title, $namespace;
	function fromData( $namespace, $title ) {
		$x = new Title;
		$x->namespace = $namespace;
		$x->title = $title;
		return $x;
	}
}

# See tests in importTests.php
if( ! $testingonly ) {
	firstPass();
	secondPass();
}

# ------------------------------------------------------------------------------

/* First pass:
	Information please!
	*/
function firstPass()
{
	global $wgRootDirectory, $oldtitles;
	
	$letters = array(
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
		'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
		'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'other' );
	foreach( $letters as $letter ) {
		firstPassDirectory( "$wgRootDirectory/page/$letter" );
	}
}

function firstPassDirectory( $dir )
{
	global $titlecache;
	
	$mydir = opendir( $dir );
	while( $entry = readdir( $mydir ) ) {
		if( $entry != '.' && $entry != '..' ) {
			if( is_dir( "$dir/$entry" ) ) {
				firstPassDirectory( "$dir/$entry" );
			}
		} elseif( preg_match( '/$(.+)\.db$/', $entry, $m ) ) {
			$titlecache[$title] = transformTitle( $m[1] );
			countLinksFrom( $title );
		} else {
			echo "-- File '$entry' doesn't seem to contain an article. Skipping.\n";
		}
	}
}

/* Second pass:
	make the dang SQL
	*/
function secondPass()
{
	global $titlecache, $usercache, $redirects;
	
	foreach( $usercache as $oldname => $user ) {
		echo importUser( $oldname );
	}
	foreach( $titlecache as $oldtitle => $newtitle ) {
		echo importPage( $oldtitle );
	}
	
	echo "\n-- Done!\n";
}


# ------------------------------------------------------------------------------

/* fetch_ functions
	Grab a given item from the database
	*/
function fetchUser( $uid )
{
	global $FS,$FS2,$FS3, $wgRootDirectory;
	
	$fname = $wgRootDirectory . "/pages/" . $title;
	if( !file_exists( $fname ) ) return false;
	
	$data = splitHash( implode( "", file( $fname ) ) );
	# enough?
	
	return $data;
}

function fetchPage( $title )
{
	global $FS,$FS2,$FS3, $wgRootDirectory;
	
	$fname = $wgRootDirectory . "/pages/" . $title;
	if( !file_exists( $fname ) ) return false;
	
	$page = splitHash( implode( "", file( $fname ) ) );
	$section = splitHash( $FS2, $page["text_default"] );
	$text = splitHash( $FS3, $section["data"] );
	
	return array ( "text" => $text["text"] , "summary" => $text["summary"] ,
		"minor" => $text["minor"] , "ts" => $section["ts"] ,
		"username" => $section["username"] , "host" => $section["host"] ) ;
}

function fetchKeptPages( $title )
{
	global $FS,$FS2,$FS3, $wgRootDirectory, $wgTimezoneCorrection;
	
	$fname = $wgRootDirectory . "/keep/" . $title . ".kp";
	if( !file_exists( $fname ) ) return array();
	
	$keptlist = explode( $FS1, implode( "", file( $fname ) ) );
	array_shift( $keptlist ); # Drop the junk at beginning of file
	
	$revisions = array();
	foreach( $keptlist as $rev ) {
		$section = splitHash( $FS2, $rev );
		$text = splitHash( $FS3, $section["data"] );
		if ( $text["text"] && $text["minor"] != "" && ( $section["ts"]*1 > 0 ) ) {
			array_push( $revisions, array ( "text" => $text["text"] , "summary" => $text["summary"] ,
				"minor" => $text["minor"] , "ts" => $section["ts"] ,
				"username" => $section["username"] , "host" => $section["host"] ) );
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
	global $wgTimezoneCorrection, $titlecache, $usercache;
	global $conversionscript, $conversioncomment, $conversiontime;
	global $historyonly, $lasthistoryonly;
	
	$page = fetchPage( $title );

	$newtext = wfStrencode( rewritePage( $title, $page->text ) );
	$t = renamePage( $title );
	$newtitle = wfStrencode( $t->title );
	$namespace = $t->namespace;
		
	# Current revision:
	$text = wfStrencode( $page->text );
	$minor = ($page->minor ? 1 : 0);
	list( $userid, $username ) = checkUserCache( $page->username, $page->host );
	$timestamp = wfUnix2Timestamp( $page->timestamp + $wgTimezoneCorrection );
	$redirect = ( preg_match( '/^#REDIRECT/', $page->text ) ? 1 : 0 );
	$sql = "\n";
	if( !$historyonly ) {
		$sql .= "INSERT
		INTO cur (cur_namespace,cur_title,cur_text,cur_comment,cur_user,cur_user_text,cur_timestamp,cur_is_redirect,cur_minor_edit)
		VALUES ($namespace,'$newtitle','$newtext','$conversioncomment',0,'$conversionscript','$conversiontime',$redirect,$minor);\n";
	}
	$sql .= "INSERT
		INTO old (old_namespace,old_title,old_text,old_comment,old_user,old_user_text,old_timestamp,old_minor_edit)
		VALUES";
	$sqlfinal = "\t\t($namespace,'$newtitle','$text','$comment',$userid,'$username','$timestamp',$minor)\n";

	# History
	if( !$lasthistoryonly ) {
		$revisions = fetchKeptPages( $title );
		foreach( $revisions as $rev ) {
			$text = wfStrencode( $rev->text );
			$minor = ($rev->minor ? 1 : 0);
			list( $userid, $username ) = checkUserCache( $rev->username, $rev->host );
			$timestamp = wfUnix2Timestamp( $rev->timestamp + $wgTimezoneCorrection );
			$sql .= "\t\t($namespace,'$newtitle','$text','$comment',$userid,'$username','$timestamp',$redirect,$minor),\n";
		}
	}
	return $sql . $sqlfinal;
}


# Count up basic links
function countLinksFrom( $title )
{
	$page = fetchPage( $title );
	$page->text = preg_replace(
		'/<nowiki>.*<\/nowiki>/sDU',
		'',
		$page->text );
	$page->text = preg_replace(
		'/\[\[\s*([0-9a-zA-Z_ \x80-\xff]+)\s*(?:\|\s*([^]]+))?\s*\]\]/e',
		'countLinkTo( ucfirst( "$1" ) )',
		$page->text );
}

function countLinkTo( $title )
{
	global $linkcache;
	$t = transformTitle( $title );
	$linkform = FreeToNormal( $t->title );
	$x = $linkcache[$title];
	if ( count ( $x ) ) {
		$y = $x[$linkform] ;
		if ( $y ) $y++; else $y = 1 ;
		$x[$linkform] = $y ;
	} else {
		$x = array ( $linkform => 1 ) ;
	}
	$linkcache[$title] = $x;
}

# Preferentially change case
function renamePage( $title )
{
	global $linkcache;
	$t = transformTitle( $title );
	
	# We want to use the most frequently linked-to form as the title
	$maxcount = 0 ; $maxform = $t->title ;
	foreach ( $linkcache[$title] as $linkform => $count ) {
		if ( $count > $maxcount ) {
			$maxcount = $count ;
			$maxform = $linkform ;
		}
	}
	if( $maxform != $t->title) {
		doRenamePage( $t, $maxform );
	}
}

function doRenamePage( $title, $maxform )
{
	global $linkcache, $redirectcomment, $conversionscript, $conversiontime;
	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text,cur_comment,cur_user,cur_user_text,cur_timestamp,cur_is_redirect,cur_minor_edit)
	VALUES ";
	$redirsql = array();
	foreach( $linkcache[$title] as $linkform => $count ) {
		if( $linkform != $maxform ) {
			$comment = wfStrencode( str_replace( "$1", $maxform, $redirectcomment ) );
			array_push( $redirsql, "($namespace,'$redirtitle','$comment',0,'$conversionscript','$conversiontime',1,1)" );
		}
	}
	$sql .= implode( ",\n\t", $redirsql ) . ";\n";
	return $sql;
}

# Account for syntax changes
function rewritePage( $title, $text )
{
	# ...
	$text = removeTalkLink( $text );
	$text = preg_replace( '/(^|<nowiki>).+?(<\/nowiki>|$)/esD',
		'rewritePageBits( $title, "$1")',
		$text );
	return $text;
}

function rewritePageBits( $title, $text ) {
	$text = fixSubpages( $title, $text );
	$text = fixMedialinks( $text );
	$text = fixImagelinks( $text );
	return $text;
}

function removeTalkLink( &$text ) {
	global $talkending;
	return preg_replace( "[\\n*(?:\[\[)?/{$talkending}(?:\]\])?\\s*]sDi", '', $text );
}

function fixSubpages( $text, &$title ) {
	$old = preg_quote( $text );
	$text = preg_replace( "<(^|\s)/([A-Z\xc0-\xdf].*?)\b>",
		"$1[[$title/$2|/$2]]", $text );
	$text = preg_replace( "<\[\[/([^|]*?)\]\]>e",
		"\"[[$title/\" . ucfirst( \"$1|/$1]]\" )", $text );
	$text = preg_replace( "<\[\[/(.*?)\]\]>e",
		"\"[[$title/\" . ucfirst( \"$1]]\" )", $text );
	return $text;
}

function fixImagelinks( &$text ) {
	global $imageimport, $namespaces;
	return preg_replace( "/$imageimport/e",
		'"[[{$namespaces[6]}" . fetchMediaFile( "$1", "$2" ) . "]]"',
		$text );
}

function fixMedialinks( &$text ) {
	global $imageimport, $mediatext;
	$text = preg_replace( "/\[$imageimport\]/e",
		'"[[$mediatext:" . fetchMediaFile( "$1", "$2" ) . "]]"',
		$text );
	return preg_replace( "/\[$imageimport (.+?)\]/e",
		'"[[$mediatext:" . fetchMediaFile( "$1", "$2" ) . "|$3]]"',
		$text );
}

function fetchMediaFile( $url, $filename )
{
	# Copy an image file into local upload space
	# FIXME
	return ucfirst( $filename );
}

# Simple move of talk pages, etc
function transformTitle( $title, $dorename = false )
{
	global $talkending;
	if( preg_match( "/^(.+)[ _]?\\/[ _]?($talkending)/i", $title, $m ) ) {
		$thetitle = $m[1];
		$namespace = 1;
	} else {
		$thetitle = $title;
		$namespace = 0;
	}
	return Title::fromData( $namespace, $thetitle );
}

# Translated out of old usemod wiki...
function FreeToNormal ( $id , $FreeUpper = true ) {
  $id = str_replace ( " ", "_", $id ) ;
  $id = ucfirst($id);
  if (strstr($id, '_') != false) {  # Quick check for any space/underscores
    $id = preg_replace ( '/__+/' , "_" , $id ) ;
    $id = preg_replace ( '/^_/' , "", $id ) ;
    $id = preg_replace ( '/_$/' , "", $id ) ;
    #if ($UseSubpage) {
      $id = preg_replace ( '|_/|', "/" , $id ) ;
      $id = preg_replace ( '|/_|', "/" , $id ) ;
    #}
  }
  if ($FreeUpper) {
    # Note that letters after ' are *not* capitalized
    if (preg_match ( '|[-_.,\(\)/][a-z]|' , $id ) ) { # Quick check for non-canon
      $id = preg_replace ( '|([-_.,\(\)/])([a-z])|e' , '"$1" . strtoupper("$2")' , $id ) ;
    }
  }
  return $id;
}

# Whee!
function recodeInput( $text )
{
	return $text;
}

function wfUnix2Timestamp( $unixtime ) {
        return gmdate( "YmdHis", $timestamp );
}

function wfTimestamp2Unix( $ts )
{
        return gmmktime( ( (int)substr( $ts, 8, 2) ),
                  (int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
                  (int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
                  (int)substr( $ts, 0, 4 ) );
}

?>
