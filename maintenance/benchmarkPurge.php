<?php
/**
 * Squid purge benchmark script
 *
 * @file
 * @ingroup Maintenance
 */

/** */
require_once( "commandLine.inc" );

/** 
 * Run a bunch of URLs through SquidUpdate::purge()
 * to benchmark Squid response times.
 * @param $urls array A bunch of URLs to purge
 * @param $trials int How many times to run the test?
 */
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

/** 
 * Get an array of randomUrl()'s.
 * @param $length int How many urls to add to the array
 */
function randomUrlList( $length ) {
	$list = array();
	for( $i = 0; $i < $length; $i++ ) {
		$list[] = randomUrl();
	}
	return $list;
}

/** 
 * Return a random URL of the wiki. Not necessarily an actual title in the
 * database, but at least a URL that looks like one. 
 */
function randomUrl() {
	global $wgServer, $wgArticlePath;
	return $wgServer . str_replace( '$1', randomTitle(), $wgArticlePath );
}

/** 
 * Create a random title string (not necessarily a Title object). 
 * For use with randomUrl().
 */
function randomTitle() {
	$str = '';
	$length = mt_rand( 1, 20 );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= chr( mt_rand( ord('a'), ord('z') ) );
	}
	return ucfirst( $str );
}

if( !$wgUseSquid ) {
	wfDie( "Squid purge benchmark doesn't do much without squid support on.\n" );
} else {
	printf( "There are %d defined squid servers:\n", count( $wgSquidServers ) );
	#echo implode( "\n", $wgSquidServers ) . "\n";
	if( isset( $options['count'] ) ) {
		$lengths = array( intval( $options['count'] ) );
	} else {
		$lengths = array( 1, 10, 100 );
	}
	foreach( $lengths as $length ) {
		$urls = randomUrlList( $length );
		$trial = benchSquid( $urls );
		print "$trial\n";
	}
}
