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

define( 'SELENIUMTEST', true );

require( dirname( __FILE__ ) . "/../commandLine.inc" );
if ( isset( $options['help'] ) ) {
	echo <<<ENDS
MediaWiki $wgVersion Selenium Framework tester
Usage: php RunSeleniumTests.php [options...]
Options:
--port=<TCP port> Port used by selenium server to accept commands
--help            Show this help message
ENDS;
	exit( 1 );
}

if ( isset( $options['port'] ) ) {
	$wgSeleniumServerPort = (int) $options['port'];
}

SeleniumLoader::load();

$result = new PHPUnit_Framework_TestResult;
$logger = new SeleniumTestConsoleLogger;
$result->addListener( new SeleniumTestListener( $logger ) );

foreach ( $wgSeleniumTestSuites as $testSuiteName ) {
	$suite = new $testSuiteName;
	$suite->addTests();
	$suite->run( $result );
}

