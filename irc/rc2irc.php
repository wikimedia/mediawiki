<?php

$ircNick = "wikipedia_rc";
$rooms = array("en" => 1, "fr" => 1, "de" => 1);
$ircServer = "irc.freenode.net";
$ircSockName = "tcp://$ircServer";
$ircPort = 6667;
$minDelay = 0.5;
$ircReadTimeout = 200000; # us
$ircWriteTimeout = 30; # s
$fmB = chr(2);
$fmU = chr(31);
$queueId = 337055475;
$maxMessageSize = 16384;

#-----------------------------------------------------------------------------

# Get queue

$ircPassword = mt_rand(0xffffffff);
$hostname = getenv('SERVER_NAME');

$queue = msg_get_queue($queueId);

if ( !$queue ) {
	print "Could not open RC message queue\n";
	exit;
}
emptyQueue( $queue );

# Initialise the IRC connection
$sockIRC = fsockopen( $ircSockName, $ircPort );
if ( !$sockIRC ) {
	print "Could not open IRC connection\n";
	exit;
}
stream_set_timeout($sockIRC, 0, $ircWriteTimeout);

fwrite( $sockIRC, 
	"PASS $ircPassword\r\n" .
	"NICK $ircNick\r\n" . 
	"USER recentchanges $hostname $ircServer Wikipedia RC->IRC bot\r\n"
);

foreach ( $rooms as $room => $v ) {
	joinRoom( $sockIRC, $room );
}

$readObjs = array( $sockIRC, $queue );

# Main input loop
$die = false;
while ( !$die ) {
	# RC input
	$msgType = 0;
	$entry = false;
	if (!msg_receive($queue, 0, $msgType, $maxMessageSize, $entry, true, MSG_IPC_NOWAIT)) {
		$entry = false;
	}
	if (is_array( $entry )) {
		$out = getIrcOutput( $sockIRC, $entry );
		fwrite( $sockIRC, $out );
	}
	
	# IRC input
	stream_set_timeout($sockIRC, 0, $ircReadTimeout);
	$line = rtrim(fgets( $sockIRC ));
	stream_set_timeout($sockIRC, 0, $ircWriteTimeout);
	if ( $line ) {
		$die = processIrcInput( $sockIRC, $line );
	}
}
exit();

#--------------------------------------------------------------
function delayMin()
{
	static $lastTime = 0;
	global $minDelay;
	if ( !$lastTime ) {
		$lastTime = getMicroTime();
	}
	$curTime = getMicroTime();
	$timeDifference = $curTime - $lastTime;
	if ( $timeDifference < $minDelay ) {
		usleep( ($minDelay - $timeDifference) *1000000 );
	}
	$lastTime = $curTime;
}

function getMicroTime()
{
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}

function getIrcOutput( $socket, $in )
{
	global $rooms;
	
	delayMin();
	$bad = array("\n", "\r");
	$empty = array("", "");
	$comment =  $in['comment'];
	$title = $in['prefixedDBkey'];
	$user = $in['userText'];
	$lastid = IntVal($in['lastOldid']);
	$flag = ($in['minor'] ? "M" : "") . ($in['new'] ? "N" : "");
	$lang = $in['lang'];
	if ( $lang == "w" ) {
		$lang = "en";
	}
	
	if ( !array_key_exists( $rooms, $lang ) ) {
		return "";
	}
	$room = "#{$lang}rc.wikipedia";
		
	if ( $in['new'] ) {
	        $url = "http://$lang.wikipedia.org/wiki/" . urlencode($title);
	} else {
	        $url = "http://$lang.wikipedia.org/w/wiki.phtml?title=" . urlencode($title) .
	                "&diff=0&oldid=$lastid";
	}
	$spaceTitle = str_replace("_", " ", $title);
	
	$beep = "";
	if ( $patterns ) {
	        foreach ( $patterns as $pattern ) {
	                if ( preg_match( $pattern, $comment ) ) {
	                        $beep = chr(7);
	                        break;
	                }
	        }
	}
	if ( $comment !== "" ) {
	        $comment = "($comment)";
	}
	
	$fullString = str_replace($bad, $empty, 
		"$beep$fmB$spaceTitle$fmB   $flag   $url   $user $comment");
	$fullString = "PRIVMSG $room :$fullString\r\n";
	return $fullString;
}

function joinRoom( $sock, $room )
{
	global $rooms;
	$rooms[$room] = 1;
	fwrite( $sock, "JOIN #{$room}rc.wikipedia\r\n" );
}

function partRoom( $sock, $room ) 
{
	global $rooms;
	unset( $rooms[$room] );
	fwrite( $sock, "PART #{$room}rc.wikipedia\r\n" );
}

function processIrcInput( $sock, $line )
{
	global $rooms;
	
	$die = false;
	$args = explode( " ", $line );
	
	if ( $args[0] == "PING" ) {
		fwrite( $sock, "PONG {$args[1]}\r\n" );
	} elseif ( $args[0]{0} == ":" ) {
		$name = array_shift( $args );
		$name = substr($name, 1);
		$cmd = array_shift( $args );
		if ( $cmd == "PRIVMSG" ) {
			$msgRoom = array_shift( $args );
			if ( $args[0] == "die" ) {
				$die = true;
			} elseif ( $args[0] == "join" ) {
				joinRoom( $args[1] );
			} elseif ( $args[0] == "part" ) {
				partRoom( $args[1] );
			}
		}
	}
}

function emptyQueue( $id )
{
	while ( msg_receive($queue, 0, $msgType, $maxMessageSize, $entry, true, MSG_IPC_NOWAIT));
}
	
?>

