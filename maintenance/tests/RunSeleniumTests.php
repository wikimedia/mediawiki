#!/usr/bin/php
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

require_once( dirname( dirname( __FILE__ ) )."/Maintenance.php" );

class SeleniumTester extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addOption( 'port', 'Port used by selenium server' );
		$this->addOption( 'host', 'Host selenium server' );
		$this->addOption( 'browser', 'The browser he used during testing' );
		$this->addOption( 'url', 'The Mediawiki installation to point to.' );
		$this->addOption( 'list-browsers', 'List the available browsers.' );

		$this->deleteOption( 'dbpass' );
		$this->deleteOption( 'dbuser' );
		$this->deleteOption( 'globals' );
		$this->deleteOption( 'wiki' );
	}

	public function listBrowsers() {
		global $wgSeleniumTestsBrowsers;

		$desc = "Available browsers:\n";
		foreach ($wgSeleniumTestsBrowsers as $k => $v) {
			$desc .= "  $k => $v\n";
		}

		echo $desc;
	}

	protected function runTests() {
		global $wgSeleniumLogger, $wgSeleniumTestSuites;

		SeleniumLoader::load();
		$result = new PHPUnit_Framework_TestResult;
		$wgSeleniumLogger = new SeleniumTestConsoleLogger;
		$result->addListener( new SeleniumTestListener( $wgSeleniumLogger ) );

		foreach ( $wgSeleniumTestSuites as $testSuiteName ) {
			$suite = new $testSuiteName;
			$suite->addTests();
			try {
				$suite->run( $result );
			} catch ( Testing_Selenium_Exception $e ) {
				throw new MWException( $e->getMessage() );
			}
		}
	}

	public function execute() {
		global $wgSeleniumServerPort, $wgSeleniumTestsSeleniumHost,
			$wgSeleniumTestsWikiUrl, $wgServer, $wgScriptPath;

		if( $this->hasOption( 'list-browsers' ) ) {
			$this->listBrowsers();
			exit(0);
		}

		$wgSeleniumServerPort = $this->getOption( 'port', 4444 );
		$wgSeleniumTestsSeleniumHost = $this->getOption( 'host', 'localhost' );
		$wgSeleniumTestsWikiUrl = $this->getOption( 'test-url', $wgServer . $wgScriptPath );
		$wgSeleniumTestsUseBrowser = $this->getOption( 'browser', 'firefox' );

		$this->runTests();
	}
}

$maintClass = "SeleniumTester";

require_once( DO_MAINTENANCE );
