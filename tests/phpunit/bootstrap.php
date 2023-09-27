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

/** @internal Should only be used in MediaWikiIntegrationTestCase::initializeForStandardPhpunitEntrypointIfNeeded() */
define( 'MW_PHPUNIT_UNIT', true );

// Faking in lieu of Setup.php
$GLOBALS['wgAutoloadClasses'] = [];
$GLOBALS['wgBaseDirectory'] = MW_INSTALL_PATH;

TestSetup::requireOnceInGlobalScope( MW_INSTALL_PATH . "/includes/AutoLoader.php" );
TestSetup::requireOnceInGlobalScope( MW_INSTALL_PATH . "/tests/common/TestsAutoLoader.php" );
TestSetup::requireOnceInGlobalScope( MW_INSTALL_PATH . "/includes/Defines.php" );
TestSetup::requireOnceInGlobalScope( MW_INSTALL_PATH . "/includes/GlobalFunctions.php" );

// Extract the defaults into global variables.
// NOTE: this does not apply any dynamic defaults.
foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $var => $value ) {
	$GLOBALS[$var] = $value;
}

TestSetup::requireOnceInGlobalScope( MW_INSTALL_PATH . "/includes/DevelopmentSettings.php" );

TestSetup::applyInitialConfig();

// Shell out to another script that will give us a list of loaded extensions and skins. We need to do that in another
// process, not in this one, because loading setting files may have non-trivial side effects that could be hard
// to undo. This sucks, but there doesn't seem to be a way to get a list of extensions and skins without loading
// all of MediaWiki, which we don't want to do for unit tests.
// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.proc_open
$process = proc_open(
	__DIR__ . '/getPHPUnitExtensionsAndSkins.php',
	[
		0 => [ 'pipe', 'r' ],
		1 => [ 'pipe', 'w' ],
		2 => [ 'pipe', 'w' ]
	],
	$pipes
);

$pathsToJsonFilesStr = stream_get_contents( $pipes[1] );
fclose( $pipes[1] );
$cmdErr = stream_get_contents( $pipes[2] );
fclose( $pipes[2] );
$exitCode = proc_close( $process );
if ( $exitCode !== 0 ) {
	echo "Cannot load list of extensions and skins. Output:\n$cmdErr\n";
	exit( 1 );
}

$pathsToJsonFiles = explode( "\n", $pathsToJsonFilesStr );

/** @internal For use in ExtensionsUnitTestSuite and SkinsUnitTestSuite only */
define( 'MW_PHPUNIT_EXTENSIONS_PATHS', array_map( 'dirname', $pathsToJsonFiles ) );

$extensionProcessor = new ExtensionProcessor();

foreach ( $pathsToJsonFiles as $filePath ) {
	$extensionProcessor->extractInfoFromFile( $filePath );
}

$autoload = $extensionProcessor->getExtractedAutoloadInfo( true );
AutoLoader::loadFiles( $autoload['files'] );
AutoLoader::registerClasses( $autoload['classes'] );
AutoLoader::registerNamespaces( $autoload['namespaces'] );

// More faking in lieu of Setup.php
Profiler::init( [] );

TestSetup::maybeCheckComposerLockUpToDate();
