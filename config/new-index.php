<?php

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );

chdir( ".." );
require( './includes/WebStart.php' );
require_once( './maintenance/updaters.inc' ); // sigh...

$installer = new WebInstaller( $wgRequest );

if ( !$installer->startSession() ) {
	$installer->finish();
	exit;
}

$session = isset( $_SESSION['installData'] ) ? $_SESSION['installData'] : array();

if ( isset( $session['settings']['_UserLang'] ) ) {
	$langCode = $session['settings']['_UserLang'];
} elseif ( !is_null( $wgRequest->getVal( 'UserLang' ) ) ) {
	$langCode = $wgRequest->getVal( 'UserLang' );
} else {
	$langCode = 'en';
}
$wgLang = Language::factory( $langCode );

$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

$session = $installer->execute( $session );

$_SESSION['installData'] = $session;

