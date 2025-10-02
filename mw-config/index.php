<?php
// phpcs:disable Generic.Arrays.DisallowLongArraySyntax
/**
 * New version of MediaWiki web-based config/installation
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\Installer\Installer;
use MediaWiki\Installer\InstallerOverrides;
use MediaWiki\MediaWikiServices;

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
// phpcs:ignore MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/../includes/PHPVersionCheck.php';
wfEntryPointCheck( 'html', dirname( dirname( $_SERVER['SCRIPT_NAME'] ) ) );

define( 'MW_CONFIG_CALLBACK', [ Installer::class, 'overrideConfig' ] );
define( 'MEDIAWIKI_INSTALL', true );

// Resolve relative to regular MediaWiki root
// instead of mw-config subdirectory.
chdir( dirname( __DIR__ ) );
require dirname( __DIR__ ) . '/includes/WebStart.php';

wfInstallerMain();

function wfInstallerMain() {
	global $wgLang, $wgMetaNamespace, $wgCanonicalNamespaceNames;
	$request = RequestContext::getMain()->getRequest();

	$installer = InstallerOverrides::getWebInstaller( $request );

	if ( !$installer->startSession() ) {
		if ( $installer->request->getCheck( 'css' ) ) {
			// Do not display errors on css pages
			$installer->outputCss();
			exit;
		}

		$errors = $installer->getPhpErrors();
		$installer->showError( 'config-session-error', $errors[0] );
		$installer->finish();
		exit;
	}

	$fingerprint = $installer->getFingerprint();
	if ( isset( $_SESSION['installData'][$fingerprint] ) ) {
		$session = $_SESSION['installData'][$fingerprint];
	} else {
		$session = array();
	}

	$services = MediaWikiServices::getInstance();
	$languageFactory = $services->getLanguageFactory();
	$languageNameUtils = $services->getLanguageNameUtils();

	$langCode = 'en';
	if ( isset( $session['settings']['_UserLang'] ) &&
		$languageNameUtils->isKnownLanguageTag( $session['settings']['_UserLang'] )
	) {
		$langCode = $session['settings']['_UserLang'];
	}
	$uselang = $request->getRawVal( 'uselang' );
	if ( $uselang !== null && $languageNameUtils->isKnownLanguageTag( $uselang ) ) {
		$langCode = $uselang;
	}
	$wgLang = $languageFactory->getRawLanguage( $langCode );

	RequestContext::getMain()->setLanguage( $wgLang );

	$installer->setParserLanguage( $wgLang );

	$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

	$session = $installer->execute( $session );

	$_SESSION['installData'][$fingerprint] = $session;
}
