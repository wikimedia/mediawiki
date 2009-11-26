<?php
if ( !defined( 'MEDIAWIKI' ) ) die( 1 );

global $wgJSAutoloadLocalClasses, $wgMwEmbedDirectory;

// Load classes from  mv_embed.js
if ( is_file( $wgMwEmbedDirectory . 'mv_embed.js' ) ) {

	//read the head of the file::
	$f = fopen( $wgMwEmbedDirectory . 'mv_embed.js' , 'r');
	$jsvar = '';
	$file_head='';
	while (!feof($f)) {
		$file_head.= fread($f, 8192);
		// Call jsClassPathLoader() for each lcPaths() call in the JS source
		$replace_test = preg_replace_callback(
			'/lcPaths\s*\(\s*{(.*)}\s*\)\s*/siU',
			'jsClassPathLoader',
			$file_head
		);
		if( $replace_test !== false )
			break;
	}
	fclose( $f );
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
