<?php
wfLoadSkin( 'BlueSpiceCalumma' );
$GLOBALS['wgSkipSkins'] = [ 'chameleon' ];
$GLOBALS['wgDefaultSkin'] = "bluespicecalumma";

global $wgLogo, $wgResourceBasePath, $wgScriptPath;
if ( $wgLogo == "$wgResourceBasePath/resources/assets/wiki.png" ){
	$wgLogo = "$wgResourceBasePath/skins/BlueSpiceCalumma/resources/images/common/logo/bs3_logo.png";
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
		"src" => $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/BlueSpice.png",
		"url" => "http://bluespice.com",
		'height' => '27',
		'width' => '149'
	],
	'semanticmediawiki' => [
		'src' => $wgScriptPath . "/skins/BlueSpiceCalumma/resources/images/common/footer/SemanticMediaWiki.png",
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		'height' => '27',
		'width' => '149'
	]
];
/*
 * We want to display an extension's icon only if the
 * corresponding extension is enabled. Here we have mapping
 * between an extension name and the corresponding icon's
 * key in $GLOBALS['wgFooterIcons']['poweredby']
 * */
$extensionsToIconKeys = [
	//Extension name => Key of the corresponding icon:
	'SemanticMediaWiki' => 'semanticmediawiki',
];
foreach ( $extensionsToIconKeys as $extensionNameFE => $iconKeyFE ) {
	if ( !class_exists( $extensionNameFE )
		&& array_key_exists( $iconKeyFE, $GLOBALS['wgFooterIcons']['poweredby'] )
	){
		unset( $GLOBALS['wgFooterIcons']['poweredby'][$iconKeyFE] );
	}
}
