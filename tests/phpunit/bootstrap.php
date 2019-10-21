<?php

/**
 * PHPUnit bootstrap file.
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
 * @ingroup Testing
 */

if ( PHP_SAPI !== 'cli' ) {
	die( 'This file is only meant to be executed indirectly by PHPUnit\'s bootstrap process!' );
}

/**
 * PHPUnit includes the bootstrap file inside a method body, while most MediaWiki startup files
 * assume to be included in the global scope.
 * This utility provides a way to include these files: it makes all globals available in the
 * inclusion scope before including the file, then exports all new or changed globals.
 *
 * @param string $fileName the file to include
 */
function wfRequireOnceInGlobalScope( $fileName ) {
	// phpcs:disable MediaWiki.Usage.ForbiddenFunctions.extract
	extract( $GLOBALS, EXTR_REFS | EXTR_SKIP );
	// phpcs:enable

	require_once $fileName;

	foreach ( get_defined_vars() as $varName => $value ) {
		$GLOBALS[$varName] = $value;
	}
}

define( 'MEDIAWIKI', true );
define( 'MW_PHPUNIT_TEST', true );

$IP = realpath( __DIR__ . '/../../' );
// We don't use a settings file here but some code still assumes that one exists
define( 'MW_CONFIG_FILE', "$IP/LocalSettings.php" );

// these variables must be defined before setup runs
$GLOBALS['IP'] = $IP;

require_once "$IP/tests/common/TestSetup.php";
TestSetup::snapshotGlobals();

// Faking in lieu of Setup.php
$GLOBALS['wgScopeTest'] = 'MediaWiki Setup.php scope test';
$GLOBALS['wgCommandLineMode'] = true;
$GLOBALS['wgAutoloadClasses'] = [];

wfRequireOnceInGlobalScope( "$IP/includes/AutoLoader.php" );
wfRequireOnceInGlobalScope( "$IP/tests/common/TestsAutoLoader.php" );
wfRequireOnceInGlobalScope( "$IP/includes/Defines.php" );
wfRequireOnceInGlobalScope( "$IP/includes/DefaultSettings.php" );
wfRequireOnceInGlobalScope( "$IP/includes/GlobalFunctions.php" );

// Populate classes and namespaces from extensions and skins present in filesystem.
$directoryToJsonMap = [
	$GLOBALS['wgExtensionDirectory'] => [ 'extension.json', 'extension-wip.json' ],
	$GLOBALS['wgStyleDirectory'] => [ 'skin.json', 'skin-wip.json' ]
];
foreach ( $directoryToJsonMap as $directory => $jsonFile ) {
	foreach ( new DirectoryIterator( $directory ) as $iterator ) {
		foreach ( $jsonFile as $file ) {

			$jsonPath = $iterator->getPathname() . '/' . $file;
			if ( file_exists( $jsonPath ) ) {
				// ExtensionRegistry->readFromQueue is not used as it checks extension/skin
				// dependencies, which we don't need or want for unit tests.
				$json = file_get_contents( $jsonPath );
				$info = json_decode( $json, true );
				$dir = dirname( $jsonPath );
				ExtensionRegistry::exportAutoloadClassesAndNamespaces( $dir, $info );
			}
		}
	}
}
