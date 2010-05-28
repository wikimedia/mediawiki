<?php
/**
 * @file
 * @ingroup Maintenance
 * @copyright Copyright © Wikimedia Deuschland, 2009
 * @author Hallo Welt! Medienwerkstatt GmbH
 * @author Markus Glaser, Dan Nessett
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
 */

define( 'MEDIAWIKI', true );
define( 'SELENIUMTEST', true );

// Here, you can override standard setting
if ( file_exists( 'selenium/LocalSeleniumSettings.php' ) ) {
	include_once 'selenium/LocalSeleniumSettings.php';
} else {
	echo "You must provide local settings in LocalSeleniumSettings.php\n";
	die( -1 );
}

// Command line only
if ( $wgSeleniumTestsRunMode == 'cli' && php_sapi_name() != 'cli' ) {
	echo "Must be run from the command line.\n";
	die( -1 );
}

// Get command line parameters
if ( $wgSeleniumTestsRunMode == 'cli' ) {
	require_once( dirname( __FILE__ ) . '/../commandLine.inc' );
	if ( isset( $options['help'] ) ) {
		echo <<<ENDS
MediaWiki $wgVersion Selenium Framework tester
Usage: php RunSeleniumTests.php [options...]
Options:
  --port=<TCP port> Port used by selenium server to accept commands
  --help            Show this help message
ENDS;
		exit( 0 );
	}

	if ( isset( $options['port'] ) ) {
		$wgSeleniumServerPort = (int) $options['port'];
	}
}

// requires PHPUnit 3.4
require_once 'Testing/Selenium.php';
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

// include seleniumTestsuite
require_once 'selenium/SeleniumTestHTMLLogger.php';
require_once 'selenium/SeleniumTestConsoleLogger.php';
require_once 'selenium/SeleniumTestListener.php';
require_once 'selenium/Selenium.php';
require_once 'selenium/SeleniumTestSuite.php';
require_once 'selenium/SeleniumTestCase.php';

$result = new PHPUnit_Framework_TestResult;
switch ( $wgSeleniumTestsRunMode ) {
	case 'html':
		$logger = new SeleniumTestHTMLLogger;
		break;
	case 'cli':
		$logger = new SeleniumTestConsoleLogger;
		break;
}

$result->addListener( new SeleniumTestListener( $logger ) );

$wgSeleniumTestSuites = array();

// include tests
// Todo: include automatically
if ( is_array( $wgSeleniumTestIncludes ) ) {
	foreach ( $wgSeleniumTestIncludes as $include ) {
		include_once $include;
	}
}

// run tests
foreach ( $wgSeleniumTestSuites as $suite ) {
	$suite->run( $result );
}