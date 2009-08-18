<?php
if ( !defined( 'MEDIAWIKI' ) ) die( 1 );

global $wgJSAutoloadLocalClasses, $wgMwEmbedDirectory;

//load classes from  mv_embed.js::

//read the file:
if( is_file( $wgMwEmbedDirectory . 'mv_embed.js' )){

	$str = @file_get_contents( $wgMwEmbedDirectory . 'mv_embed.js');

	$str = preg_replace_callback(
		'/lcPaths\s*\(\s*{(.*)}\s*\)\s*/siU',
		'jsClassPathLoader',
		$str
	);
}
function jsClassPathLoader($jvar){
	global $wgJSAutoloadLocalClasses,$wgMwEmbedDirectory;
	if( !isset( $jvar[1] ) )
		return false;
	$jClassSet = json_decode( '{' . $jvar[1] . '}', true );
	foreach( $jClassSet as $jClass => $jPath ){
		//strip $ from jsclass (as they are striped on url request param input)
		$jClass = str_replace('$', '', $jClass);
		$wgJSAutoloadLocalClasses[$jClass] = $wgMwEmbedDirectory . $jPath;
	}
}
