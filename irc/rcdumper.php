<?php

/*
Example command-line:
	php rcdumper.php | irc -d -c \#channel-to-join nick-of-bot some.irc.server
where irc is the name of the ircII executable.
The name of the the IRC should match $ircServer below.
*/

$ircServer = "irc.freenode.net";

# Set the below if this is running on a non-Wikimedia site:
#$serverName="your.site.here";


ini_set( "display_errors", 1 );
$wgCommandLineMode = true;
$fmB = chr(2);
$fmU = chr(31);

require_once("../maintenance/commandLine.inc" );

if ($wgWikiFarm) {
	$serverName="$lang.wikipedia.org";
	$newPageURLFirstPart="http://$serverName/wiki/";
	$URLFirstPart="http://$serverName/w/wiki.phtml?title=";
} else {
	$newPageURLFirstPart="http://$serverName$wgScript/";
	$URLFirstPart="http://$serverName$wgScript?title=";
}

$wgTitle = Title::newFromText( "RC dumper" );
$wgCommandLineMode = true;
set_time_limit(0);

sleep(30);

$res = wfQuery( "SELECT rc_timestamp FROM recentchanges ORDER BY rc_timestamp DESC LIMIT 1", DB_READ ); 
$row = wfFetchObject( $res );
$oldTimestamp = $row->rc_timestamp;
$serverCount = 0;

while (1) {
	$res = wfQuery( "SELECT * FROM recentchanges WHERE rc_timestamp>'$oldTimestamp' ORDER BY rc_timestamp", DB_READ );
	$rowIndex = 0;
	while ( $row = wfFetchObject( $res ) ) {
		if ( ++$serverCount % 20 == 0 ) {
			print "/server $ircServer\n";
		}
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
			$url = $newPageURLFirstPart . urlencode($title);
		} else {
			$url = $URLFirstPart . urlencode($title) .
				"&diff=0&oldid=$lastid";
		}
		
		$title = str_replace("_", " ", $title);
		# see http://www.irssi.org/?page=docs&doc=formats for some colour codes. prefix is \003, 
		# no colour (\003) switches back to the term default
		$fullString = "\00303$title\0037 $flag\00310 $url \0037*\003 $user \0037*\003 $comment\n";

		print( $fullString );
		$oldTimestamp = $row->rc_timestamp;
		sleep(2);
	}
	sleep(5);
}

exit();

?>
