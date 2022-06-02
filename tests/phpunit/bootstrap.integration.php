<?php
/**
 * Bootstrap file used by default in phpunit.xml.dist.
 *
 * Takes care of preparation needed for integration tests to run.
 *
 * If you want to run unit tests with more strict isolation (ensuring MediaWiki does not bootstrap),
 * then pass the bootstrap.php file, not this one, to vendor/bin/phpunit --bootstrap.
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

require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/IntegrationBootstrapWrapper.php";

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	exit( 'This script must be run from the command line' );
}
if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/../..' ) );
}
if ( getenv( 'PHPUNIT_WIKI' ) ) {
	$wikiName = getenv( 'PHPUNIT_WIKI' );
	$bits = explode( '-', $wikiName, 2 );
	define( 'MW_DB', $bits[0] );
	define( 'MW_PREFIX', $bits[1] ?? '' );
	define( 'MW_WIKI_NAME', $wikiName );
}
$IP = getenv( 'MW_INSTALL_PATH' );
global $wgIntegrationBootstrapWrapper;
$wgIntegrationBootstrapWrapper = new IntegrationBootstrapWrapper();
$wgIntegrationBootstrapWrapper->setup();

require_once "$IP/includes/BootstrapHelperFunctions.php";
// ensure MW_INSTALL_PATH is defined
$IP = wfDetectInstallPath();
wfDetectLocalSettingsFile( $IP );

/**
 * Will be called when MW_SETUP_CALLBACK is evaluated during bootstrapping.
 *
 * @return void
 */
function wfPHPUnitSetup(): void {
	global $wgIntegrationBootstrapWrapper;
	$wgIntegrationBootstrapWrapper->finalSetup();
}

define( 'MW_SETUP_CALLBACK', 'wfPHPUnitSetup' );

MediaWikiIntegrationTestCase::initializeForStandardPhpunitEntrypointIfNeeded();

// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
// stays intact. Needs to happen after including Setup.php, which calls MWExceptionHandler::installHandle().
restore_error_handler();

$wgIntegrationBootstrapWrapper->execute();

// The TestRunner class will run each test suite and may call
// exit() with an exit status code. As such, we cannot run code "after the last test"
// by adding statements to PHPUnitMaintClass::execute.
// Instead, we work around it by registering a shutdown callback from the bootstrap
// file, which runs before PHPUnit starts.
// @todo Once we use PHPUnit 8 or higher, use the 'AfterLastTestHook' feature.
// https://phpunit.readthedocs.io/en/8.0/extending-phpunit.html#available-hook-interfaces
register_shutdown_function( static function () {
	// This will:
	// - clear the temporary job queue.
	// - allow extensions to delete any temporary tables they created.
	// - restore ability to connect to the real database.
	MediaWikiIntegrationTestCase::teardownTestDB();
} );
