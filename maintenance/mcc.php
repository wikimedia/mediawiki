<?php
/**
 * memcached diagnostic tool
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );

$mcc = new memcached( array('persistant' => true) );
$mcc->set_servers( $wgMemCachedServers );
$mcc->set_debug( true );

do {
	$bad = false;
	$quit = false;
	$line = readconsole( "> " );
	$args = explode( " ", $line );
	$command = array_shift( $args );
	switch ( $command ) {
		case "get":
			print "Getting {$args[0]}[{$args[1]}]\n";
			$res = $mcc->get( $args[0] );
			if ( array_key_exists( 1, $args ) ) {
				$res = $res[$args[1]];
			}
			if ( $res === false ) {
				#print 'Error: ' . $mcc->error_string() . "\n";
				print "MemCached error\n";
			} elseif ( is_string( $res ) ) {
				print "$res\n";
			} else {
				var_dump( $res );
			}
			break;
		case "getsock":
			$res = $mcc->get( $args[0] );
			$sock = $mcc->get_sock( $args[0] );
			var_dump( $sock );
			break;
		case "set":
			$key = array_shift( $args );
			if ( $args[0] == "#" && is_numeric( $args[1] ) ) {
				$value = str_repeat( "*", $args[1] );
			} else {
				$value = implode( " ", $args );
			}
			if ( !$mcc->set( $key, $value, 0 ) ) {
				#print 'Error: ' . $mcc->error_string() . "\n";
				print "MemCached error\n";
			}
			break;
		case "delete":
			$key = implode( " ", $args );
			if ( !$mcc->delete( $key ) ) {
				#print 'Error: ' . $mcc->error_string() . "\n";
				print "MemCached error\n";
			}
			break;				       
		case "dumpmcc":
			var_dump( $mcc );
			break;
		case "quit":
		case "exit":
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

?>
