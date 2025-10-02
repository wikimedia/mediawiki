<?php
/**
 * Dynamically create a simple stylesheet for unit tests in MediaWiki.
 *
 * @license GPL-2.0-or-later
 * @file
 */

// This file doesn't run as part of MediaWiki
// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

header( 'Cache-Control: private, no-cache, must-revalidate' );
header( 'Content-Type: text/css; charset=utf-8' );

// Do basic sanitization
$params = array_map( static function ( $val ) {
	return is_string( $val ) ? preg_replace( '/[^A-Za-z0-9\.\- #]/', '', $val ) : null;
}, $_GET );

// Defaults
$selector = $params['selector'] ?? '.mw-test-example';
$property = $params['prop'] ?? 'float';
$value = $params['val'] ?? 'right';
$wait = isset( $params['wait'] ) ? (int)$params['wait'] : 0; // seconds

sleep( $wait );

$css = "
/**
 * Generated " . gmdate( 'r' ) . ".
 * Waited {$wait}s.
 */

$selector {
	$property: $value;
}
";

echo trim( $css ) . "\n";
