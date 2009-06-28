<?php

/**
 * Rebuild the localisation cache. Useful if you disabled automatic updates
 * using $wgLocalisationCacheConf['manualRecache'] = true;
 *
 * Usage:
 *    php rebuildLocalisationCache.php [--force]
 *
 * Use --force to rebuild all files, even the ones that are not out of date.
 */

require( dirname(__FILE__).'/commandLine.inc' );
ini_set( 'memory_limit', '200M' );

$force = isset( $options['force'] );

$conf = $wgLocalisationCacheConf;
$conf['manualRecache'] = false; // Allow fallbacks to create CDB files
if ( $force ) {
	$conf['forceRecache'] = true;
}
$lc = new LocalisationCache_BulkLoad( $conf );

$codes = array_keys( Language::getLanguageNames( true ) );
sort( $codes );
$numRebuilt = 0;
foreach ( $codes as $code ) {
	if ( $force || $lc->isExpired( $code ) ) {
		echo "Rebuilding $code...\n";
		$lc->recache( $code );
		$numRebuilt++;
	}
}
echo "$numRebuilt languages rebuilt out of " . count( $codes ) . ".\n";
if ( $numRebuilt == 0 ) {
	echo "Use --force to rebuild the caches which are still fresh.\n";
}



