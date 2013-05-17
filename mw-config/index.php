<?php
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

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );
define( 'MEDIAWIKI_INSTALL', true );

chdir( dirname( __DIR__ ) );
require dirname( __DIR__ ) . '/includes/WebStart.php';

wfInstallerMain();

function wfInstallerMain() {
	global $wgRequest, $wgLang, $wgMetaNamespace, $wgCanonicalNamespaceNames;

	$installer = InstallerOverrides::getWebInstaller( $wgRequest );

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
