<?php

/**
 * PHPUnit bootstrap file. This file loads MW classes, and optionally MW settings if integration tests
 * are probably going to be run. Note that MW settings are not loaded for unit tests, but a settings
 * file must still exist in order to determine what extensions should be loaded and to add any custom
 * tests that they might add.
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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigSchema;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionProcessor;
use MediaWiki\Registration\ExtensionRegistry;
use PHPUnit\TextUI\CliArguments\Builder;

require_once __DIR__ . '/bootstrap.common.php';

// Try to determine if this entry point is being used to run integration tests (possibly together with
// unit tests). If so, load all settings files. It's better to do this now, instead of later upon executing
// an integration test, to avoid scenarios where unit tests that run before the first integration test would
// run without the config being loaded, whereas the ones running after could potentially be affected by config.
// TODO This check is obviously imperfect. Once we upgrade to PHPUnit 10 we might be able to use its events
// (https://docs.phpunit.de/en/10.2/events.html) to get a list of tests that will be run, and check if there's
// any integration test in there.
/** @internal Only use this environment variable if you temporarily need it. */
$envVar = getenv( 'MEDIAWIKI_HAS_INTEGRATION_TESTS' );
if ( $envVar !== false ) {
	// Allow the developer to override the detection if necessary.
	if ( $envVar === 'true' || $envVar === '1' || $envVar === 'yes' ) {
		$hasIntegrationTests = true;
	} elseif ( $envVar === 'false' || $envVar === '0' || $envVar === 'no' ) {
		$hasIntegrationTests = false;
	} else {
		fwrite( STDERR,
			"Unsupported value in MEDIAWIKI_HAS_INTEGRATION_TESTS environment variable,\n" .
			"assuming there are integration tests (but you should fix the environment variable)\n"
		);
		$hasIntegrationTests = true;
	}
} elseif ( $GLOBALS['argc'] === 1 ) {
	// PHPUnit has been invoked with no arguments. We're going to execute all and every test (from core, and potentially
	// extension tests too), which includes integration tests.
	$hasIntegrationTests = true;
} else {
	// PHPUnit has been invoked with arguments. This can be very complex to handle, so the heuristic below is meant
	// to cover just the most common use cases.
	// Make PHPUnit not complain about unrecognized options when paratest options are passed in
	$paratestArgs = [ 'runner', 'processes', 'passthru-php', 'write-to' ];
	$phpunitArgs = ( new Builder )->fromParameters( $GLOBALS['argv'], $paratestArgs );
	if ( $phpunitArgs->hasArgument() ) {
		// A test or test directory was specified explicitly. Normalize line endings and case, and see if we likely
		// got a directory of unit tests only (or a file therein).
		$testArgument = strtolower( strtr( $phpunitArgs->argument(), '\\', '/' ) );
		$hasIntegrationTests = !( str_contains( $testArgument, '/unit/' ) || str_ends_with( $testArgument, '/unit' ) );
	} elseif ( $phpunitArgs->hasTestSuite() ) {
		// If only unit test suites are being run, there can't be integration tests. Otherwise,
		// there *might* be integration tests.
		// See TestSuiteMapper for handling of the 'testsuites' argument.
		$suites = explode( ',', $phpunitArgs->testSuite() );
		$unitSuites = [ 'core:unit', 'extensions:unit', 'skins:unit' ];
		$hasIntegrationTests = array_diff( $suites, $unitSuites ) !== [];
	} else {
		// Something more complex, just assume there might be integration tests
		$hasIntegrationTests = true;
	}
}

if ( !$hasIntegrationTests ) {
	fwrite( STDERR, "Running without MediaWiki settings because there are no integration tests\n" );
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

	// Shell out to another script that will give us a list of loaded extensions and skins. We need to do that in
	// another process, not in this one, because loading setting files may have non-trivial side effects that could be
	// hard to undo. This sucks, but there doesn't seem to be a way to get a list of extensions and skins without
	// loading all of MediaWiki, which we don't want to do for unit tests.

	// Disable XDEBUG, so it doesn't cause the sub-process to hang. T358097
	$env = getenv();
	unset( $env['XDEBUG_CONFIG'] );
	unset( $env['XDEBUG_MODE'] );
	unset( $env['XDEBUG_SESSION'] );

	// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.proc_open
	$process = proc_open(
		PHP_BINARY . ' ' . __DIR__ . '/getPHPUnitExtensionsAndSkins.php',
		[
			0 => [ 'pipe', 'r' ],
			1 => [ 'pipe', 'w' ],
			2 => [ 'pipe', 'w' ]
		],
		$pipes,
		null,
		$env
	);

	$extensionData = stream_get_contents( $pipes[1] );
	fclose( $pipes[1] );
	$cmdErr = stream_get_contents( $pipes[2] );
	fclose( $pipes[2] );

	$exitCode = proc_close( $process );
	if ( $exitCode !== 0 ) {
		echo "Cannot load list of extensions and skins. Output:\n$cmdErr\n";
		exit( 1 );
	}

	// For simplicity, getPHPUnitExtensionsAndSkins uses `\n\nTESTPATHS\n\n` to separate the lists of JSON files and
	// additional test paths, so split the output into the individual lists.
	[ $pathsToJsonFilesStr, $testPathsStr ] = explode( "\n\nTESTPATHS\n\n", $extensionData );
	$pathsToJsonFiles = $pathsToJsonFilesStr ? explode( "\n", $pathsToJsonFilesStr ) : [];
	$testPaths = explode( "\n", $testPathsStr );

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
} else {
	fwrite( STDERR, "Running with MediaWiki settings because there might be integration tests\n" );
	TestSetup::loadSettingsFiles();

	$extensionRegistry = ExtensionRegistry::getInstance();
	$extensionsAndSkins = $extensionRegistry->getQueue();

	$pathsToJsonFiles = array_keys( $extensionsAndSkins );

	$testPaths = [];
	foreach ( $extensionRegistry->getAllThings() as $info ) {
		$testPaths[] = dirname( $info['path'] ) . '/tests/phpunit';
	}

	( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )->onUnitTestsList( $testPaths );
}

/** @internal For use in ExtensionsUnitTestSuite and SkinsUnitTestSuite only */
define( 'MW_PHPUNIT_EXTENSIONS_PATHS', array_map( 'dirname', $pathsToJsonFiles ) );
/** @internal For use in ExtensionsTestSuite only */
define( 'MW_PHPUNIT_EXTENSIONS_TEST_PATHS', $testPaths );

TestSetup::maybeCheckComposerLockUpToDate();
