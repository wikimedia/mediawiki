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

use MediaWiki\MainConfigSchema;

require_once __DIR__ . '/bootstrap.common.php';
$IP = $GLOBALS['IP'];

/** @internal Should only be used in MediaWikiIntegrationTestCase::initializeForStandardPhpunitEntrypointIfNeeded() */
define( 'MW_PHPUNIT_UNIT', true );

// Faking in lieu of Setup.php
$GLOBALS['wgCommandLineMode'] = true;
$GLOBALS['wgAutoloadClasses'] = [];
$GLOBALS['wgBaseDirectory'] = MW_INSTALL_PATH;

TestSetup::requireOnceInGlobalScope( "$IP/includes/AutoLoader.php" );
TestSetup::requireOnceInGlobalScope( "$IP/tests/common/TestsAutoLoader.php" );
TestSetup::requireOnceInGlobalScope( "$IP/includes/Defines.php" );
TestSetup::requireOnceInGlobalScope( "$IP/includes/GlobalFunctions.php" );

// Extract the defaults into global variables.
// NOTE: this does not apply any dynamic defaults.
foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $var => $value ) {
	$GLOBALS[$var] = $value;
}

TestSetup::requireOnceInGlobalScope( "$IP/includes/DevelopmentSettings.php" );

TestSetup::applyInitialConfig();
MediaWikiCliOptions::initialize();

// Since we do not load settings, expect to find extensions and skins
// in their respective default locations.
$GLOBALS['wgExtensionDirectory'] = "$IP/extensions";
$GLOBALS['wgStyleDirectory'] = "$IP/skins";

// Populate classes and namespaces from extensions and skins present in filesystem.
$directoryToJsonMap = [
	$GLOBALS['wgExtensionDirectory'] => 'extension*.json',
	$GLOBALS['wgStyleDirectory'] => 'skin*.json'
];

$extensionProcessor = new ExtensionProcessor();

foreach ( $directoryToJsonMap as $directory => $jsonFilePattern ) {
	foreach ( new GlobIterator( $directory . '/*/' . $jsonFilePattern ) as $iterator ) {
		$jsonPath = $iterator->getPathname();
		$extensionProcessor->extractInfoFromFile( $jsonPath );
	}
}

$autoload = $extensionProcessor->getExtractedAutoloadInfo( true );
AutoLoader::loadFiles( $autoload['files'] );
AutoLoader::registerClasses( $autoload['classes'] );
AutoLoader::registerNamespaces( $autoload['namespaces'] );

// More faking in lieu of Setup.php
Profiler::init( [] );

// Check that composer dependencies are up-to-date
if ( !getenv( 'MW_SKIP_EXTERNAL_DEPENDENCIES' ) ) {
	$composerLockUpToDate = new CheckComposerLockUpToDate();
	$composerLockUpToDate->loadParamsAndArgs( 'phpunit', [ 'quiet' => true ] );
	$composerLockUpToDate->execute();
}
