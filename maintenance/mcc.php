<?php
include_once( "../includes/DefaultSettings.php" );
include_once( "../LocalSettings.php" );
include_once( "../includes/MemCachedClient.inc.php" );

$mcc = new MemCachedClient();
$mcc->set_servers( $wgMemCachedServers );

do {
	$bad = false;
	$quit = false;
	$line = readconsole( "> " );
	$args = explode( " ", $line );
	$command = array_shift( $args );
	switch ( $command ) {
		case "get":
			$res = $mcc->get( implode( " ", $args ) );
			if ( $res === false ) {
				print 'Error: ' . $mcc->error_string() . "\n";
			} elseif ( is_string( $res ) ) {
				print "$res\n";
			} else {
				var_dump( $res );
			}
			break;
		case "set":
			$key = array_shift( $args );
			if ( !$mcc->set( $key, implode( " ", $args ), 0 ) ) {
				print 'Error: ' . $mcc->error_string() . "\n";
			}
			break;
		case "quit":
			$quit = true;
			break;
		default:
			$bad = true;
	}
	if ( $bad ) {
		if ( $command ) {
			print "Bad command\n";
		}
	} else {
		if ( function_exists( "readline_add_history" ) ) {
			readline_add_history( $line );
		}
	}
} while ( !$quit );

function readconsole( $prompt = "" ) {
	if ( function_exists( "readline" ) ) {
		return readline( $prompt );
	} else {
		print $prompt;
		$fp = fopen( "php://stdin", "r" );
		$resp = trim( fgets( $fp, 1024 ) );
		fclose( $fp );
		return $resp;
	}
}



?>

