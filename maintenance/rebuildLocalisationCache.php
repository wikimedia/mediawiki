<?php

/**
 * Rebuild the localisation cache. Useful if you disabled automatic updates
 * using $wgLocalisationCacheConf['manualRecache'] = true;
 *
 * Usage:
 *    php rebuildLocalisationCache.php [--force]
 *
 * Use --force to rebuild all files, even the ones that are not out of date.
 * Use --threads=N to fork more threads.
 */

require( dirname(__FILE__).'/commandLine.inc' );
ini_set( 'memory_limit', '200M' );

$force = isset( $options['force'] );
$threads = intval( isset( $options['threads'] ) ? $options['threads'] : 1 );

$conf = $wgLocalisationCacheConf;
$conf['manualRecache'] = false; // Allow fallbacks to create CDB files
if ( $force ) {
	$conf['forceRecache'] = true;
}
$lc = new LocalisationCache_BulkLoad( $conf );

$codes = array_keys( Language::getLanguageNames( true ) );
sort( $codes );

// Initialise and split into chunks
$numRebuilt = 0;
$total = count($codes);
$chunks = array_chunk( $codes, ceil(count($codes)/$threads) );
$pids = array();

foreach ( $chunks as $codes ) {
	// Do not fork for only one thread
	$pid = ( $threads > 1 ) ? pcntl_fork() : -1;

	if ( $pid === 0 ) {
		// Child
		doRebuild( $codes, $numRebuilt, $lc, $force );
		exit();
	} elseif ($pid === -1) {
		// Fork failed or one thread, do it serialized
		doRebuild( $codes, $numRebuilt, $lc, $force );
	} else {
		// Main thread
		$pids[] = $pid;
	}
}

// Wait for all children
foreach ( $pids as $pid ) pcntl_waitpid($pid, $status);

echo "$numRebuilt languages rebuilt out of $total.\n";
if ( $numRebuilt == 0 ) {
	echo "Use --force to rebuild the caches which are still fresh.\n";
}

function doRebuild( $codes, &$numRebuilt, $lc, $force ) {
	foreach ( $codes as $code ) {
		if ( $force || $lc->isExpired( $code ) ) {
			echo "Rebuilding $code...\n";
			$lc->recache( $code );
			$numRebuilt++;
		}
	}
}
