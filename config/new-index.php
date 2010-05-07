<?php

define( 'MW_NO_DB', 1 );
define( 'MW_NO_SESSION', 1 );
define( 'MW_CONFIG_CALLBACK', 'wfInstallerConfig' );

function wfInstallerConfig() {
	// Don't access the database
	$GLOBALS['wgUseDatabaseMessages'] = false;
	// Debug-friendly
	$GLOBALS['wgShowExceptionDetails'] = true;
	// Don't break forms
	$GLOBALS['wgExternalLinkTarget'] = '_blank';

	// Extended debugging. Maybe disable before release?
	$GLOBALS['wgShowSQLErrors'] = true;
	$GLOBALS['wgShowDBErrorBacktrace'] = true;
}

chdir( ".." );
require( './includes/WebStart.php' );
require_once( './maintenance/updaters.inc' ); // sigh...

// Disable the i18n cache and LoadBalancer
Language::getLocalisationCache()->disableBackend();
LBFactory::disableBackend();

// Load the installer's i18n file
$wgExtensionMessagesFiles['MediawikiInstaller'] = './includes/installer/Installer.i18n.php';

$installer = new WebInstaller( $wgRequest );
$wgParser->setHook( 'doclink', array( $installer, 'docLink' ) );

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

