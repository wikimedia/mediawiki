<?php
/**
 * @file
 * @ingroup Maintenance
 * @copyright Copyright © Wikimedia Deuschland, 2009
 * @author Hallo Welt! Medienwerkstatt GmbH
 * @author Markus Glaser
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

// Command line only
$wgSeleniumTestsRunMode = 'cli';
if ( $wgSeleniumTestsRunMode == 'cli' && php_sapi_name() != 'cli' ) {
	echo 'Must be run from the command line.';
	die( -1 );
}
// include path and installation instructions

// URL: http://localhost/tests/RunSeleniumTests.php
// set_include_path( get_include_path() . PATH_SEPARATOR . './PEAR/' );

// Hostname of selenium server
$wgSeleniumTestsSeleniumHost = 'http://localhost';

// URL of the wiki to be tested.
$wgSeleniumTestsWikiUrl = 'http://localhost';

// Wiki login. Used by Selenium to log onto the wiki
$wgSeleniumTestsWikiUser      = 'WikiSysop';
$wgSeleniumTestsWikiPassword  = 'password';

// Common browsers on Windows platform
// Use the *chrome handler in order to be able to test file uploads
// further solution suggestions: http://www.brokenbuild.com/blog/2007/06/07/testing-file-uploads-with-selenium-rc-and-firefoxor-reducing-javascript-security-in-firefox-for-fun-and-profit/
// $wgSeleniumTestsBrowsers['firefox']   = '*firefox c:\\Program Files (x86)\\Mozilla Firefox\\firefox.exe';
$wgSeleniumTestsBrowsers['firefox']   = '*chrome c:\\Program Files (x86)\\Mozilla Firefox\\firefox.exe';
$wgSeleniumTestsBrowsers['iexplorer'] = '*iexploreproxy';

// Actually, use this browser
$wgSeleniumTestsUseBrowser = 'firefox';

// requires PHPUnit 3.4
require_once 'Testing/Selenium.php';
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

// include uiTestsuite
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

// Todo: include automatically
# include_once '<your tests>';

// Here, you can override standard setting
if ( file_exists( 'LocalSeleniumSettings.php' ) ) {
	include_once 'LocalSeleniumSettings.php';
}

// run tests
foreach ( $wgSeleniumTestSuites as $suite ) {
	$suite->run( $result );
}