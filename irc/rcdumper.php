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
$optionsWithArgs = array( 'm' );
require_once("../maintenance/commandLine.inc" );

if ( !empty( $options['m'] ) ) {
	$channel = $options['m'];
} else {
	$channel = false;
}

if ($lang == "commons") $site = "wikimedia";

if ($wgWikiFarm) {
	$serverName="$lang.$site.org";
	$newPageURLFirstPart="http://$serverName/wiki/";
	$URLFirstPart="http://$serverName/w/wiki.phtml?title=";
} else {
	$newPageURLFirstPart="http://$serverName$wgScript/";
	$URLFirstPart="http://$serverName$wgScript?title=";
}

$wgTitle = Title::newFromText( "RC dumper" );
$wgCommandLineMode = true;
set_time_limit(0);

if ( empty($options['b']) ) {
	$bots = "AND NOT(rc_bot)";
} else {
	$bots = "";
}

if (isset($args[0]) && isset($args[1])) {
	$lowest = $args[0];
	$highest = $args[1];
}

$res = wfQuery( "SELECT MAX(rc_id) as m FROM recentchanges", DB_READ ); 
$row = wfFetchObject( $res );
$oldId = $row->m;
$serverCount = 0;

while (1) {
	$res = wfQuery( "SELECT rc_this_oldid,rc_timestamp,rc_user_text,rc_namespace,rc_title,rc_comment,rc_minor,
			rc_bot,rc_new,rc_cur_id,rc_last_oldid,rc_type,rc_moved_to_ns,rc_moved_to_title,rc_id,
			cur_text, old_text, old_flags FROM recentchanges LEFT OUTER JOIN old ON rc_last_oldid=old_id
			LEFT OUTER JOIN cur ON rc_cur_id=cur_id WHERE rc_id>'$oldId' $bots 
			ORDER BY rc_timestamp", DB_READ );

	$rowIndex = 0;
	while ( $row = wfFetchObject( $res ) ) {
		$oldtext = $row->old_text;
		if (strstr($row->old_flags, "gzip"))
			$oldtext = gzinflate($oldtext);
		$szdiff = strlen($row->cur_text) - strlen($oldtext);
		if ($szdiff >= 0)
			$szdiff = "+$szdiff";
		$ns = $wgLang->getNsText( $row->rc_namespace ) ;
		if ( $ns ) {
			$title = "$ns:{$row->rc_title}";
		} else {
			$title = $row->rc_title;
		}
		$comment = $row->rc_comment;
		$bad = array("\n", "\r");
		$empty = array("", "");
		$comment = str_replace($bad, $empty, $comment);
		$title = str_replace($bad, $empty, $title);
		$a = $title[0];
		if ($a < 'A' || $a > 'Z')
			$a = 'Z';
		if ((isset($highest) && ($a > $highest)) || (isset($lowest) && $a <= $lowest))
			continue;
		$user = str_replace($bad, $empty, $row->rc_user_text);
		$lastid = IntVal($row->rc_last_oldid);
		$flag = ($row->rc_minor ? "M" : "") . ($row->rc_new ? "N" : "");
		$stupid_urlencode = array("%2F", "%3A");
		$haha_take_that = array("/", ":");
		if ( $row->rc_new ) {
			$url = $newPageURLFirstPart . urlencode($title);
		} else {
			$url = $URLFirstPart . urlencode($title) .
				"&diff=0&oldid=$lastid";
		}
		$url = str_replace($stupid_urlencode, $haha_take_that, $url);
		$title = str_replace("_", " ", $title);
		# see http://www.irssi.org/?page=docs&doc=formats for some colour codes. prefix is \003, 
		# no colour (\003) switches back to the term default
		$comment = preg_replace("/\/\* (.*) \*\/(.*)/", "\00315\$1\003 - \00310\$2\003", $comment);
		$fullString = "\00314[[\00307$title\00314]]\0034 $flag\00310 " .
		              "\00302$url\003 \0035*\003 \00303$user\003 \0035*\003 ($szdiff) \00310$comment\003\n";
	        if ( $channel ) {
			$fullString = "$channel\t$fullString";
		}

		print( $fullString );
		$oldId = max( $row->rc_id, $oldId );
		sleep(1);
	}
	sleep(5);
}

exit();

?>
