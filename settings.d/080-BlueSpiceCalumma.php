<?php
wfLoadSkin( 'BlueSpiceCalumma' );
$GLOBALS['wgSkipSkins'] = [ 'chameleon' ];
$GLOBALS['wgDefaultSkin'] = "bluespicecalumma";

if ( $GLOBALS['wgLogos']['1x'] == $GLOBALS['wgResourceBasePath'] . '/resources/assets/wiki.png' ){
	$GLOBALS['wgLogos'] = [ '1x' => $GLOBALS['wgResourceBasePath']
		. '/skins/BlueSpiceCalumma/resources/images/common/logo/bs3_logo.png' ];
}

if ( $wgFavicon == '/favicon.ico' ){
	$wgFavicon = "$wgResourceBasePath/skins/BlueSpiceCalumma/resources/images/common/favicon.ico";
}
