<?php
/**
 * New version of MediaWiki web-based config/installation
 *
 * @file
 */

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );
define( 'MEDIAWIKI_INSTALL', true );

chdir( dirname( dirname( __FILE__ ) ) );
if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	require ( 'phase3/includes/WebStart.php' );
} else {
	require( dirname( dirname( __FILE__ ) ) . '/includes/WebStart.php' );
}

wfInstallerMain();

function wfInstallerMain() {
	global $wgRequest, $wgLang, $wgMetaNamespace, $wgCanonicalNamespaceNames;

	$installer = new WebInstaller( $wgRequest );

	if ( !$installer->startSession() ) {
		$installer->finish();
		exit;
	}

	$fingerprint = $installer->getFingerprint();
	if ( isset( $_SESSION['installData'][$fingerprint] ) ) {
		$session = $_SESSION['installData'][$fingerprint];
	} else {
		$session = array();
	}

	if ( !is_null( $wgRequest->getVal( 'uselang' ) ) ) {
		$langCode = $wgRequest->getVal( 'uselang' );
	} elseif ( isset( $session['settings']['_UserLang'] ) ) {
		$langCode = $session['settings']['_UserLang'];
	} else {
		$langCode = 'en';
	}
	$wgLang = Language::factory( $langCode );

	$installer->setParserLanguage( $wgLang );

	$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

	$session = $installer->execute( $session );

	$_SESSION['installData'][$fingerprint] = $session;

}
