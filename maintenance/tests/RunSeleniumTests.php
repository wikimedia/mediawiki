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
require_once( 'PHPUnit/Framework.php' );
require_once( 'PHPUnit/Extensions/SeleniumTestCase.php' );


class SeleniumTester extends Maintenance {
	protected $selenium;

	public function __construct() {
		parent::__construct();

		$this->addOption( 'port', 'Port used by selenium server' );
		$this->addOption( 'host', 'Host selenium server' );
		$this->addOption( 'browser', 'The browser he used during testing' );
		$this->addOption( 'url', 'The Mediawiki installation to point to.' );
		$this->addOption( 'list-browsers', 'List the available browsers.' );
		$this->addOption( 'verbose', 'Be noisier.' );

		$this->deleteOption( 'dbpass' );
		$this->deleteOption( 'dbuser' );
		$this->deleteOption( 'globals' );
		$this->deleteOption( 'wiki' );
	}

	public function listBrowsers() {
		$desc = "Available browsers:\n";

		$sel = new Selenium;
		foreach ($sel->setupBrowsers() as $k => $v) {
			$desc .= "  $k => $v\n";
		}

		echo $desc;
	}

	protected function getTestSuites() {
		return array( 'SimpleSeleniumTestSuite' );
	}

	protected function runTests( ) {
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener( new SeleniumTestListener( $this->selenium->getLogger() ) );

		foreach ( $this->getTestSuites() as $testSuiteName ) {
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
		global $wgServer, $wgScriptPath;

		/**
		 * @todo Add an alternative where settings are read from an INI file.
		 */
		$this->selenium = new Selenium( );
		$this->selenium->setUrl( $this->getOption( 'url', $wgServer . $wgScriptPath ) );
		$this->selenium->setBrowser( $this->getOption( 'browser', 'firefox' ) );
		$this->selenium->setPort( $this->getOption( 'port', 4444 ) );
		$this->selenium->setHost( $this->getOption( 'host', 'localhost' ) );
		$this->selenium->setUser( $this->getOption( 'user', 'WikiSysop' ) );
		$this->selenium->setPass( $this->getOption( 'pass', 'Password' ) );
		$this->selenium->setVerbose( $this->hasOption( 'verbose' ) );

		if( $this->hasOption( 'list-browsers' ) ) {
			$this->listBrowsers();
			exit(0);
		}

		$logger = new SeleniumTestConsoleLogger;
		$this->selenium->setLogger( $logger );

		$this->runTests( );
	}
}

$maintClass = "SeleniumTester";

require_once( DO_MAINTENANCE );
