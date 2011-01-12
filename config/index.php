<?php
/**
 * New version of MediaWiki web-based config/installation
 *
 * @file
 */

define( 'MW_CONFIG_CALLBACK', 'CoreInstaller::overrideConfig' );
define( 'MEDIAWIKI_INSTALL', true );

chdir( dirname( dirname( __FILE__ ) ) );
require( dirname( dirname( __FILE__ ) ) . '/includes/WebStart.php' );

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

	if ( isset( $session['settings']['_UserLang'] ) ) {
		$langCode = $session['settings']['_UserLang'];
	} elseif ( !is_null( $wgRequest->getVal( 'UserLang' ) ) ) {
		$langCode = $wgRequest->getVal( 'UserLang' );
	} else {
		$langCode = 'en';
	}
	$wgLang = Language::factory( $langCode );

	$installer->setParserLanguage( $wgLang );

	$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

	$session = $installer->execute( $session );

	$_SESSION['installData'][$fingerprint] = $session;

}
