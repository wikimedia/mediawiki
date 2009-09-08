<?php
if ( !defined( 'MEDIAWIKI' ) ) die( 1 );

global $wgJSAutoloadLocalClasses, $wgMwEmbedDirectory;

// Load classes from  mv_embed.js

if ( is_file( $wgMwEmbedDirectory . 'mv_embed.js' ) ) {
	// Read the file
	$str = @file_get_contents( $wgMwEmbedDirectory . 'mv_embed.js' );

	// Call jsClassPathLoader() for each lcPaths() call in the JS source
	$str = preg_replace_callback(
		'/lcPaths\s*\(\s*{(.*)}\s*\)\s*/siU',
		'jsClassPathLoader',
		$str
	);
}
function jsClassPathLoader( $jvar ) {
	global $wgJSAutoloadLocalClasses, $wgMwEmbedDirectory;
	if ( !isset( $jvar[1] ) )
		return false;
	$jClassSet = json_decode( '{' . $jvar[1] . '}', true );
	foreach ( $jClassSet as $jClass => $jPath ) {
		// Strip $ from jClass (as they are stripped on URL request parameter input)
		$jClass = str_replace( '$', '', $jClass );
		$wgJSAutoloadLocalClasses[$jClass] = $wgMwEmbedDirectory . $jPath;
	}
}
