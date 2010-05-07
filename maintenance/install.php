<?php

if ( php_sapi_name() != 'cli' ) {
	echo "This is a command-line script.\n";
	exit( 1 );
}

define( 'MEDIAWIKI', 1 );
define( 'MW_NO_DB', 1 );
define( 'MW_NO_SESSION', 1 );
define( 'MW_CONFIG_CALLBACK', 'wfInstallerConfig' );

$IP = dirname( dirname( __FILE__ ) );

function wfInstallerConfig() {
	// Don't access the database
	$GLOBALS['wgUseDatabaseMessages'] = false;
	// Debug-friendly
	$GLOBALS['wgShowExceptionDetails'] = true;
	// Don't break forms
	$GLOBALS['wgExternalLinkTarget'] = '_blank';
}

require_once( "$IP/includes/ProfilerStub.php" );
require_once( "$IP/includes/Defines.php" );
require_once( "$IP/includes/GlobalFunctions.php" );
require_once( "$IP/includes/AutoLoader.php" );
require_once( "$IP/includes/Hooks.php" );
require_once( "$IP/includes/DefaultSettings.php" );
require_once( "$IP/includes/Namespace.php" );

$wgContLang = Language::factory( 'en' ); // will be overridden later

// Disable the i18n cache and LoadBalancer
Language::getLocalisationCache()->disableBackend();
LBFactory::disableBackend();

$installer = new CliInstaller( $argv );

$langCode = 'en';

$wgLang = Language::factory( $langCode );

$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

$session = $installer->execute( $argv );

$_SESSION['installData'] = $session;

