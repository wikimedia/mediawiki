<?php

$wgCommandLineMode = true;
$fmB = chr(2);
$fmU = chr(31);

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
if ( $argv[1] ) {
	$lang = $argv[1];
	putenv( "wikilang=$lang");
	$settingsFile = "/apache/htdocs/{$argv[1]}/w/LocalSettings.php";
	$newpath = "/apache/common/php$sep";
} else {
	$settingsFile = "../LocalSettings.php";
	$newpath = "";
}

if ( $argv[2] ) {
	$patterns = explode( ",", $argv[2]);
} else {
	$patterns = false;
}

if ( ! is_readable( $settingsFile ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

ini_set( "include_path", "$newpath$IP$sep$include_path" );

$wgCommandLineMode = true;
$DP = "../includes";
include_once( $settingsFile );
include_once( "Setup.php" );
$wgTitle = Title::newFromText( "RC dumper" );
$wgCommandLineMode = true;
set_time_limit(0);

sleep(30);

$res = wfQuery( "SELECT rc_timestamp FROM recentchanges ORDER BY rc_timestamp DESC LIMIT 1", DB_READ ); 
$row = wfFetchObject( $res );
$oldTimestamp = $row->rc_timestamp;

while (1) {
	$res = wfQuery( "SELECT * FROM recentchanges WHERE rc_timestamp>'$oldTimestamp' ORDER BY rc_timestamp", DB_READ );
	$rowIndex = 0;
	while ( $row = wfFetchObject( $res ) ) {
		$ns = $wgLang->getNsText( $row->rc_namespace ) ;
		if ( $ns ) {
			$title = "$ns:{$row->rc_title}";
		} else {
			$title = $row->rc_title;
		}
		/*if ( strlen( $row->rc_comment ) > 50 ) {
			$comment = substr( $row->rc_comment, 0, 50 );
		} else {*/
			$comment = $row->rc_comment;
//		}
		$bad = array("\n", "\r");
		$empty = array("", "");
		$comment = str_replace($bad, $empty, $comment);
		$title = str_replace($bad, $empty, $title);
		$user = str_replace($bad, $empty, $row->rc_user_text);
		$lastid = IntVal($row->rc_last_oldid);
		$flag = ($row->rc_minor ? "M" : "") . ($row->rc_new ? "N" : "");
		if ( $row->rc_new ) {
			$url = "http://$lang.wikipedia.org/wiki/" . urlencode($title);
		} else {
			$url = "http://$lang.wikipedia.org/w/wiki.phtml?title=" . urlencode($title) .
				"&diff=0&oldid=$lastid";
		}
		$boldTitle = $fmB . str_replace("_", " ", $title) . $fmB;

		if ( $patterns ) {
			foreach ( $patterns as $pattern ) {
				if ( preg_match( $pattern, $comment ) ) {
					print chr(7);
					break;
				}
			}
		}
		if ( $comment !== "" ) {
			$comment = "($comment)";
		}
		
		$fullString = "$boldTitle   $flag   $url   $user $comment\n"; 

		if ( $fullString{0} == "/" ) {
			$fullString = " $fullString";
		}
		print( $fullString );
		$oldTimestamp = $row->rc_timestamp;
		sleep(2);
	}
	sleep(5);
}

exit();

?>
