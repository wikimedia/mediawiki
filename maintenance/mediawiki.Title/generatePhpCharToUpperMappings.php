#!/usr/bin/env php
<?php
/**
 * Utility to generate mapping file used in mw.Title (phpCharToUpper.json)
 *
 * Compares output of String.toUpperCase in JavaScript with
 * mb_strtoupper in PHP, and outputs a list of lower:upper
 * mappings where they differ. This is then used by Title.js
 * to provide the same normalization in the client as on
 * the server.
 */

$data = [];

// phpcs:disable MediaWiki.Usage.ForbiddenFunctions.exec
$jsUpperChars = json_decode( exec( 'node generateJsToUpperCaseList.js' ) );
// phpcs:enable MediaWiki.Usage.ForbiddenFunctions.exec

for ( $i = 0; $i < 65536; $i++ ) {
	if ( $i >= 0xd800 && $i <= 0xdfff ) {
		// Skip surrogate pairs
		continue;
	}
	$char = mb_convert_encoding( '&#' . $i . ';', 'UTF-8', 'HTML-ENTITIES' );
	$phpUpper = mb_strtoupper( $char );
	$jsUpper = $jsUpperChars[$i];
	if ( $jsUpper !== $phpUpper ) {
		$data[$char] = $phpUpper;
	}
}

echo str_replace( '    ', "\t",
	json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
) . "\n";
