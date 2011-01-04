#!/usr/bin/php
<?php
/**
 * @file
 * @ingroup Maintenance
 * @copyright Copyright Â© Wikimedia Deuschland, 2009
 * @author Hallo Welt! Medienwerkstatt GmbH
 * @author Markus Glaser, Dan Nessett, Priyanka Dhanda
 * initial idea by Daniel Kinzler
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

$IP = dirname( dirname( __FILE__ ) );

define( 'SELENIUMTEST', true );

//require_once( dirname( __FILE__ ) . '/../maintenance/commandLine.inc' );
require( dirname( __FILE__ ) . '/../maintenance/Maintenance.php' );

require_once( 'PHPUnit/Runner/Version.php' );
if( version_compare( PHPUnit_Runner_Version::id(), '3.5.0', '>=' ) ) {
	# PHPUnit 3.5.0 introduced a nice autoloader based on class name
	require_once( 'PHPUnit/Autoload.php' );
} else {
	# Keep the old pre PHPUnit 3.5.0 behaviour for compatibility
	require_once( 'PHPUnit/TextUI/Command.php' );
}

require_once( 'PHPUnit/Extensions/SeleniumTestCase.php' );
include_once( 'PHPUnit/Util/Log/JUnit.php' );

require_once( dirname( __FILE__ ) . "/selenium/SeleniumServerManager.php" );

class SeleniumTester extends Maintenance {
	protected $selenium;
	protected $serverManager;
	protected $seleniumServerExecPath;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Selenium Test Runner. For documentation, visit http://www.mediawiki.org/wiki/SeleniumFramework";
		$this->addOption( 'port', 'Port used by selenium server. Default: 4444', false, true );
		$this->addOption( 'host', 'Host selenium server. Default: $wgServer . $wgScriptPath', false, true );
		$this->addOption( 'testBrowser', 'The browser used during testing. Default: firefox', false, true );
		$this->addOption( 'wikiUrl', 'The Mediawiki installation to point to. Default: http://localhost', false, true );
		$this->addOption( 'username', 'The login username for sunning tests. Default: empty', false, true );
		$this->addOption( 'userPassword', 'The login password for running tests. Default: empty', false, true );
		$this->addOption( 'seleniumConfig', 'Location of the selenium config file. Default: empty', false, true );
		$this->addOption( 'list-browsers', 'List the available browsers.' );
		$this->addOption( 'verbose', 'Be noisier.' );
		$this->addOption( 'startserver', 'Start Selenium Server (on localhost) before the run.' );
		$this->addOption( 'stopserver', 'Stop Selenium Server (on localhost) after the run.' );
		$this->addOption( 'jUnitLogFile', 'Log results in a specified JUnit log file. Default: empty', false, true );
		$this->addOption( 'runAgainstGrid', 'The test will be run against a Selenium Grid. Default: false.', false, true );
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

	protected function startServer() {
		if ( $this->seleniumServerExecPath == '' ) {
			die ( "The selenium server exec path is not set in " .
				  "selenium_settings.ini. Cannot start server \n" .
				  "as requested - terminating RunSeleniumTests\n" );
		}
		$this->serverManager = new SeleniumServerManager( 'true',
			$this->selenium->getPort(),
			$this->seleniumServerExecPath );
		switch ( $this->serverManager->start() ) {
			case 'started':
				break;
			case 'failed':
				die ( "Unable to start the Selenium Server - " .
					"terminating RunSeleniumTests\n" );
			case 'running':
				echo ( "Warning: The Selenium Server is " .
					"already running\n" );
				break;
		}

		return;
	}

	protected function stopServer() {
		if ( !isset ( $this->serverManager ) ) {
			echo ( "Warning: Request to stop Selenium Server, but it was " .
				"not stared by RunSeleniumTests\n" .
				"RunSeleniumTests cannot stop a Selenium Server it " .
				"did not start\n" );
		} else {
			switch ( $this->serverManager->stop() ) {
				case 'stopped':
					break;
				case 'failed':
					echo ( "unable to stop the Selenium Server\n" );
			}
		}
		return;
	}

	protected function runTests( $seleniumTestSuites = array() ) {
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener( new SeleniumTestListener( $this->selenium->getLogger() ) );
		if ( $this->selenium->getJUnitLogFile() ) {
			$jUnitListener = new PHPUnit_Util_Log_JUnit( $this->selenium->getJUnitLogFile(), true );
			$result->addListener( $jUnitListener );
		}

		foreach ( $seleniumTestSuites as $testSuiteName => $testSuiteFile ) {
			require( $testSuiteFile );
			$suite = new $testSuiteName();
			$suite->setName( $testSuiteName );
			$suite->addTests();

			try {
				$suite->run( $result );
			} catch ( Testing_Selenium_Exception $e ) {
				$suite->tearDown();
				throw new MWException( $e->getMessage() );
			}
		}

		if ( $this->selenium->getJUnitLogFile() ) {
			$jUnitListener->flush();
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
			$this->output("No command line, configuration file or configuration hook found.\n");
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

		// State for starting/stopping the Selenium server has nothing to do with the Selenium
		// class. Keep this state local to SeleniumTester class. Using getOption() is clumsy, but
		// the Maintenance class does not have a setOption()
		if ( ! isset( $seleniumSettings['startserver'] ) ) $this->getOption( 'startserver', true );
		if ( ! isset( $seleniumSettings['stopserver'] ) ) $this->getOption( 'stopserver', true );
		if ( !isset( $seleniumSettings['seleniumserverexecpath'] ) ) $seleniumSettings['seleniumserverexecpath'] = '';
		$this->seleniumServerExecPath = $seleniumSettings['seleniumserverexecpath'];

		//set reasonable defaults if we did not find the settings
		if ( !isset( $seleniumBrowsers ) ) $seleniumBrowsers = array ('firefox' => '*firefox');
		if ( !isset( $seleniumSettings['host'] ) ) $seleniumSettings['host'] = $wgServer . $wgScriptPath;
		if ( !isset( $seleniumSettings['port'] ) ) $seleniumSettings['port'] = '4444';
		if ( !isset( $seleniumSettings['wikiUrl'] ) ) $seleniumSettings['wikiUrl'] = 'http://localhost';
		if ( !isset( $seleniumSettings['username'] ) ) $seleniumSettings['username'] = '';
		if ( !isset( $seleniumSettings['userPassword'] ) ) $seleniumSettings['userPassword'] = '';
		if ( !isset( $seleniumSettings['testBrowser'] ) ) $seleniumSettings['testBrowser'] = 'firefox';
		if ( !isset( $seleniumSettings['jUnitLogFile'] ) ) $seleniumSettings['jUnitLogFile'] = false;
		if ( !isset( $seleniumSettings['runAgainstGrid'] ) ) $seleniumSettings['runAgainstGrid'] = false;

		// Setup Selenium class
		$this->selenium = new Selenium( );
		$this->selenium->setAvailableBrowsers( $seleniumBrowsers );
		$this->selenium->setRunAgainstGrid( $this->getOption( 'runAgainstGrid', $seleniumSettings['runAgainstGrid'] ) );
		$this->selenium->setUrl( $this->getOption( 'wikiUrl', $seleniumSettings['wikiUrl'] ) );
		$this->selenium->setBrowser( $this->getOption( 'testBrowser', $seleniumSettings['testBrowser'] ) );
		$this->selenium->setPort( $this->getOption( 'port', $seleniumSettings['port'] ) );
		$this->selenium->setHost( $this->getOption( 'host', $seleniumSettings['host'] ) );
		$this->selenium->setUser( $this->getOption( 'username', $seleniumSettings['username'] ) );
		$this->selenium->setPass( $this->getOption( 'userPassword', $seleniumSettings['userPassword'] ) );
		$this->selenium->setVerbose( $this->hasOption( 'verbose' ) );
		$this->selenium->setJUnitLogFile( $this->getOption( 'jUnitLogFile', $seleniumSettings['jUnitLogFile'] ) );

		if( $this->hasOption( 'list-browsers' ) ) {
			$this->listBrowsers();
			exit(0);
		}
		if ( $this->hasOption( 'startserver' ) ) {
			$this->startServer();
		}

		$logger = new SeleniumTestConsoleLogger;
		$this->selenium->setLogger( $logger );

		$this->runTests( $seleniumTestSuites );

		if ( $this->hasOption( 'stopserver' )  ) {
			$this->stopServer();
		}
	}
}

$maintClass = "SeleniumTester";

require_once( DO_MAINTENANCE );
