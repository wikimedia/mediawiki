<?php

wfLoadExtension( 'Arrays' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'DynamicPageList3' );
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
wfLoadExtension( 'RSS' );
wfLoadExtension( 'Echo');
wfLoadExtension( 'TitleKey');
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = [];
$wgUserMergeUnmergeable = [];
wfLoadExtension( 'Variables' );
wfLoadExtension( 'BlueSpiceEchoConnector' );
wfLoadExtension( 'BlueSpiceDistributionConnector' );
require_once __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
require_once __DIR__ . "/../extensions/UrlGetParameters/UrlGetParameters.php";
wfLoadExtension( 'FlexiSkin' );
wfLoadExtension( 'Loops' );

$GLOBALS['wgGroupTypes'] = [
	'*'                => 'implicit',
	'user'             => 'implicit',
	'autoconfirmed'    => 'implicit',
	'sysop'            => 'core-minimal',
	'bureaucrat'       => 'core-extended',
	'bot'              => 'core-extended',
	'interface-admin'  => 'core-extended',
	'suppress'         => 'core-extended',
	'autoreview'       => 'extension-extended',
	'editor'           => 'extension-minimal',
	'review'           => 'extension-extended',
	'reviewer'         => 'extension-minimal',
	'smwcurator'       => 'extension-extended',
	'smweditor'        => 'extension-extended',
	'smwadministrator' => 'extension-extended',
	'widgeteditor'     => 'extension-extended'
];

$bsImagesPath =
	$GLOBALS['wgScriptPath']
	. '/extensions/BlueSpiceDistributionConnector/resources/images';

/*
 * Use an other image for MediaWiki.org
 */
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] = [
	'src' => $bsImagesPath . '/footer/MediaWiki.png',
	'height' => '27',
	'width' => '149'
] + $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'];

/*
 * We want to use an other image for this extensions but the config files are processed to early.
 * So we have to set the complete items.
 */
$GLOBALS['wgFooterIcons']['poweredby'] += [
	'bluespice' => [
		'src' => $bsImagesPath . '/footer/BlueSpice.png',
		'url' => 'http://bluespice.com',
		'alt' => 'Powered by BlueSpice',
		'height' => '27',
		'width' => '149'
	]
];

if ( array_key_exists( 'semanticmediawiki', $GLOBALS['wgFooterIcons']['poweredby'] ) ) {
	$footerIcons = [
		'mediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'],
		'bluespice' => $GLOBALS['wgFooterIcons']['poweredby']['bluespice'],
		'semanticmediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki']
	];

	unset( $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['bluespice'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki'] );

	$footerIcons += $GLOBALS['wgFooterIcons']['poweredby'];

	$GLOBALS['wgFooterIcons']['poweredby'] = $footerIcons;
}

$defaultLogoPath = $GLOBALS['wgResourceBasePath'] . '/resources/assets/wiki.png';
$blueSpiceLogoPath = "$bsImagesPath/bs_logo.png";

if ( $GLOBALS['wgLogos']['1x'] === $defaultLogoPath ) {
	$GLOBALS['wgLogos'] = [ '1x' => $blueSpiceLogoPath ];
}

if ( $GLOBALS['wgFavicon'] == '/favicon.ico' ){
	$GLOBALS['wgFavicon'] = "$bsImagesPath/favicon.ico";
}

unset( $bsImagesPath );
unset( $defaultLogoPath );
unset( $blueSpiceLogoPath );
