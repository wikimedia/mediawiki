#!/usr/bin/env php
<?php
use MatthiasMullie\Minify;

// phpcs:disable MediaWiki.NamingConventions.PrefixedGlobalFunctions.wfPrefix

require_once __DIR__ . '/includes/libs/minify/src/Minify.php';
require_once __DIR__ . '/includes/libs/minify/src/JS.php';
require_once __DIR__ . '/includes/libs/JavaScriptMinifier.php';

try {
	$iterations = 10;

	$files = [
		"resources/lib/jquery/jquery.js",
		"resources/lib/oojs-ui/oojs-ui-core.js",
		"extensions/TimedMediaHandler/resources/videojs/video.js"
	];

	foreach ( $files as $id => $file ) {
		$data = file_get_contents( $file );
		fileIntro( $file, $data, $iterations );

		$total = 0;
		$max = -INF;
		echo "\n  ## JavaScriptMinifier\n";
		for ( $i = $iterations; $i > 0; $i-- ) {
			$start = microtime( true );
			$output = JavaScriptMinifier::minify( $data );
			$took = ( microtime( true ) - $start ) * 1000;
			$max = max( $max, $took );
			$total += ( microtime( true ) - $start ) * 1000;
		}
		outputStat( $total, $max, $iterations, $output );

		$total = 0;
		$max = -INF;
		echo "\n  ## MinifyJS\n";
		for ( $i = $iterations; $i > 0; $i-- ) {
			$start = microtime( true );
			$minifier = new Minify\JS( $data );
			$output = $minifier->minify();
			$took = ( microtime( true ) - $start ) * 1000;
			$max = max( $max, $took );
			$total += ( microtime( true ) - $start ) * 1000;
		}
		outputStat( $total, $max, $iterations, $output );
	}
} catch ( Exception $e ) {
	fwrite( STDERR, $e->getMessage(), PHP_EOL );
	exit( 1 );
}

function formatSize( $size ) {
	$i = floor( log( $size, 1024 ) );
	return round( $size / pow( 1024, $i ), [ 0, 0, 2, 2, 3 ][ $i ] )
			. ' ' . [ 'B', 'KB', 'MB', 'GB', 'TB' ][ $i ];
}

function fileIntro( $file, $fileContents, $iterations ) {
	echo "\n{$file}\n"
		. "- data length    : " . formatSize( strlen( $fileContents ) ) . "\n"
		. "- data hash      : " . hash( 'fnv132', $fileContents ) . "\n"
		. "- iterations     : " . $iterations . "\n";
}

function outputStat( $total, $max, $iterations, $output ) {
	$mean = $total / $iterations; // in milliseconds
	$ratePerSecond = 1.0 / ( $mean / 1000.0 );
	echo "  - max          : " . sprintf( '%.2fms', $max ) . "\n";
	echo "  - mean         : " . sprintf( '%.2fms', $mean ) . "\n";
	echo "  - rate         : " . sprintf( '%.1f/s', $ratePerSecond ) . "\n";
	echo "  - output size  : " . formatSize( strlen( $output ) ) . "\n";
}
