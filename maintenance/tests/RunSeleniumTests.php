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
		$this->mDescription = "Selenium Test Runner. For documentation, visit http://www.mediawiki.org/wiki/SeleniumFramework";
		$this->addOption( 'port', 'Port used by selenium server. Default: 4444' );
		$this->addOption( 'host', 'Host selenium server. Default: $wgServer . $wgScriptPath' );
		$this->addOption( 'testBrowser', 'The browser he used during testing. Default: firefox'  );
		$this->addOption( 'wikiUrl', 'The Mediawiki installation to point to. Default: http://localhost' );
		$this->addOption( 'username', 'The login username for sunning tests. Default: empty' );
		$this->addOption( 'userPassword', 'The login password for running tests. Default: empty' );
		$this->addOption( 'seleniumConfig', 'Location of the selenium config file. Default: empty' );
		$this->addOption( 'list-browsers', 'List the available browsers.' );
		$this->addOption( 'verbose', 'Be noisier.' );

		$this->deleteOption( 'dbpass' );
		$this->deleteOption( 'dbuser' );
		$this->deleteOption( 'globals' );
		$this->deleteOption( 'wiki' );
	}

	public function listBrowsers() {
		$desc = "Available browsers:\n";

		foreach ($this->selenium->getAvailableBrowsers() as $k => $v) {
			$desc .= "  $k => $v\n";
		}

		echo $desc;
	}

	protected function runTests( $seleniumTestSuites = array() ) {
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener( new SeleniumTestListener( $this->selenium->getLogger() ) );
		
		foreach ( $seleniumTestSuites as $testSuiteName => $testSuiteFile ) {
			require( $testSuiteFile ); 		
 			$suite = new $testSuiteName();
			$suite->addTests();
			
			try {
				$suite->run( $result );
			} catch ( Testing_Selenium_Exception $e ) {
				$suite->tearDown(); 
				throw new MWException( $e->getMessage() );
			}
		}
	}

	public function execute() {
		global $wgServer, $wgScriptPath, $wgHooks;
		
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;

		$configFile = $this->getOption( 'seleniumConfig', '' );
		if ( strlen( $configFile ) > 0 ) {
			$this->output("Using Selenium Configuration file: " . $configFile . "\n");
			SeleniumConfig::getSeleniumSettings( $seleniumSettings, 
				$seleniumBrowsers,
				$seleniumTestSuites,
				$configFile );
		} else if ( !isset( $wgHooks['SeleniumSettings'] ) ) {
			$this->output("No command line configuration file or configuration hook found.\n");
			SeleniumConfig::getSeleniumSettings( $seleniumSettings, 
				$seleniumBrowsers,
				$seleniumTestSuites
										  			);
		} else {
			$this->output("Using 'SeleniumSettings' hook for configuration.\n");
			wfRunHooks('SeleniumSettings', array( $seleniumSettings, 
				$seleniumBrowsers,
				$seleniumTestSuites ) );
		}
		

		//set reasonable defaults if we did not find the settings
		if ( !isset( $seleniumBrowsers ) ) $seleniumBrowsers = array ('firefox' => '*firefox');
		if ( !isset( $seleniumSettings['host'] ) ) $seleniumSettings['host'] = $wgServer . $wgScriptPath;
		if ( !isset( $seleniumSettings['port'] ) ) $seleniumSettings['port'] = '4444';
		if ( !isset( $seleniumSettings['wikiUrl'] ) ) $seleniumSettings['wikiUrl'] = 'http://localhost';
		if ( !isset( $seleniumSettings['username'] ) ) $seleniumSettings['username'] = '';
		if ( !isset( $seleniumSettings['userPassword'] ) ) $seleniumSettings['userPassword'] = '';
		if ( !isset( $seleniumSettings['testBrowser'] ) ) $seleniumSettings['testBrowser'] = 'firefox';
		
		$this->selenium = new Selenium( );
		$this->selenium->setAvailableBrowsers( $seleniumBrowsers );
		$this->selenium->setUrl( $this->getOption( 'wikiUrl', $seleniumSettings['wikiUrl'] ) );
		$this->selenium->setBrowser( $this->getOption( 'testBrowser', $seleniumSettings['testBrowser'] ) );
		$this->selenium->setPort( $this->getOption( 'port', $seleniumSettings['port'] ) );
		$this->selenium->setHost( $this->getOption( 'host', $seleniumSettings['host'] ) );
		$this->selenium->setUser( $this->getOption( 'username', $seleniumSettings['username'] ) );
		$this->selenium->setPass( $this->getOption( 'userPassword', $seleniumSettings['userPassword'] ) );
		$this->selenium->setVerbose( $this->hasOption( 'verbose' ) );

		if( $this->hasOption( 'list-browsers' ) ) {
			$this->listBrowsers();
			exit(0);
		}

		$logger = new SeleniumTestConsoleLogger;
		$this->selenium->setLogger( $logger );

		$this->runTests( $seleniumTestSuites );
	}
}

$maintClass = "SeleniumTester";

require_once( DO_MAINTENANCE );
