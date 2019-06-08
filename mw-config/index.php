<?php
// phpcs:disable Generic.Arrays.DisallowLongArraySyntax
/**
 * New version of MediaWiki web-based config/installation
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
// phpcs:ignore MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/../includes/PHPVersionCheck.php';
wfEntryPointCheck( 'html', dirname( dirname( $_SERVER['SCRIPT_NAME'] ) ) );

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );
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
		if ( $installer->request->getVal( 'css' ) ) {
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

	if ( $request->getCheck( 'uselang' ) ) {
		$langCode = $request->getVal( 'uselang' );
	} elseif ( isset( $session['settings']['_UserLang'] ) ) {
		$langCode = $session['settings']['_UserLang'];
	} else {
		$langCode = 'en';
	}
	$wgLang = Language::factory( $langCode );
	RequestContext::getMain()->setLanguage( $wgLang );

	$installer->setParserLanguage( $wgLang );

	$wgMetaNamespace = $wgCanonicalNamespaceNames[NS_PROJECT];

	$session = $installer->execute( $session );

	$_SESSION['installData'][$fingerprint] = $session;
}
