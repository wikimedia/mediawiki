<?php

/* Squid purge benchmark script */

require_once( "commandLine.inc" );

function benchSquid( $urls, $trials = 1 ) {
	$start = wfTime();
	for( $i = 0; $i < $trials; $i++) {
		SquidUpdate::purge( $urls );
	}
	$delta = wfTime() - $start;
	$pertrial = $delta / $trials;
	$pertitle = $pertrial / count( $urls );
	return sprintf( "%4d titles in %6.2fms (%6.2fms each)",
		count( $urls ), $pertrial * 1000.0, $pertitle * 1000.0 );
}

function randomUrlList( $length ) {
	$list = array();
	for( $i = 0; $i < $length; $i++ ) {
		$list[] = randomUrl();
	}
	return $list;
}

function randomUrl() {
	global $wgServer, $wgArticlePath;
	return $wgServer . str_replace( '$1', randomTitle(), $wgArticlePath );
}

function randomTitle() {
	$str = '';
	$length = mt_rand( 1, 20 );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= chr( mt_rand( ord('a'), ord('z') ) );
	}
	return ucfirst( $str );
}

if( !$wgUseSquid ) {
	die( "Squid purge benchmark doesn't do much without squid support on.\n" );
} else {
	printf( "There are %d defined squid servers:\n", count( $wgSquidServers ) );
	echo implode( "\n", $wgSquidServers ) . "\n";
	$lengths = array( 1, 10, 100 );
	foreach( $lengths as $length ) {
		$urls = randomUrlList( $length );
		$trial = benchSquid( $urls, 4 );
		print "$trial\n";
	}
}

?>
