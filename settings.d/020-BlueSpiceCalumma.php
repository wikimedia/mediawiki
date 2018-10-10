<?php
wfLoadSkin( 'BlueSpiceCalumma' );
$GLOBALS['wgSkipSkins'] = [ 'chameleon' ];
$GLOBALS['wgDefaultSkin'] = "bluespicecalumma";
$GLOBALS['egChameleonLayoutFile'] = "$IP/skins/BlueSpiceCalumma/layouts/calumma-master.xml";

global $wgLogo, $wgResourceBasePath, $wgScriptPath;
if( $wgLogo == "$wgResourceBasePath/resources/assets/wiki.png" ){
    $wgLogo = "$wgResourceBasePath/skins/BlueSpiceCalumma/resources/images/common/logo/bs3_logo.png";
}

/*
 * Use an other image for MediaWiki.org
 */
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki']['src'] = $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/MediaWiki.png";
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] += [
	"height" => "32",
	"width" => "150"
];

/*
 * We want to use an other image for this extensions but the config files are processed to early.
 * So we have to set the complete items.
 */
$GLOBALS['wgFooterIcons']['poweredby'] += [
	'bluespice' => [
		"src" => $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/BlueSpice.png",
		"url" => "http://bluespice.com",
		"alt" => "Powered by BlueSpice",
		"height" => "32",
		"width" => "150"
	],
	'semanticmediawiki' => [
		'src' => $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/SemanticMediaWiki.png",
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		"height" => "32",
		"width" => "150"
	]
];

