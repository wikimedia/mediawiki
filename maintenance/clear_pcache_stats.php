<?php
require_once('commandLine.inc');

foreach ( $wgLocalDatabases as $db ) {
	noisyDelete("$db:stats:pcache_hit");
	noisyDelete("$db:stats:pcache_miss_invalid");
	noisyDelete("$db:stats:pcache_miss_expired");
	noisyDelete("$db:stats:pcache_miss_absent");
}

function noisyDelete( $key ) {
	global $wgMemc;
	/*
	print "$key ";
	if ( $wgMemc->delete($key) ) {
		print "deleted\n";
	} else {
		print "FAILED\n";
	}*/
	$wgMemc->delete($key);
}
?>
