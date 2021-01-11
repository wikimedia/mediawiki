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

/*
 * Use an other image for MediaWiki.org
 */
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki']['src'] = $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/MediaWiki.png";
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] += [
	'height' => '27',
	'width' => '149'
];

/*
 * We want to use an other image for this extensions but the config files are processed to early.
 * So we have to set the complete items.
 */
$GLOBALS['wgFooterIcons']['poweredby'] += [
	'bluespice' => [
		'src' => $wgScriptPath . '/skins/BlueSpiceCalumma/resources/images/common/footer/BlueSpice.png',
		'url' => 'http://bluespice.com',
		'alt' => 'Powered by BlueSpice',
		'height' => '27',
		'width' => '149'
	],
	'semanticmediawiki' => [
		'src' => $wgScriptPath . '/skins/BlueSpiceCalumma/resources/images/common/footer/SemanticMediaWiki.png',
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		'height' => '27',
		'width' => '149'
	]
];
