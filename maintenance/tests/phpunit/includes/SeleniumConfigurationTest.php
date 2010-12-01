<?php

class SeleniumConfigurationTest extends PHPUnit_Framework_TestCase {

	/*
	 * The file where the test temporarity stores the selenium config.
	 * This should be cleaned up as part of teardown.
	 */
	private $tempFileName;

	/*
	 * String containing the a sample selenium settings
	 */
	private $testConfig0 =
'
[SeleniumSettings]
browsers[firefox] 	= "*firefox"
browsers[iexplorer] = "*iexploreproxy"
browsers[chrome] 	= "*chrome"
host 				= "localhost"
port 				= "foobarr"
wikiUrl 			= "http://localhost/deployment"
username 			= "xxxxxxx"
userPassword 		= ""
testBrowser 		= "chrome"
startserver		=
stopserver		=
jUnitLogFile	=
runAgainstGrid	= false

[SeleniumTests]
testSuite[SimpleSeleniumTestSuite] = "maintenance/tests/selenium/SimpleSeleniumTestSuite.php"
testSuite[TestSuiteName] = "testSuitePath"
';
	/*
	 * Array of expected browsers from $testConfig0
	 */
	private $testBrowsers0 = array(	'firefox' => '*firefox',
							'iexplorer' => '*iexploreproxy',
							'chrome' => '*chrome'
	);
	/*
	 * Array of expected selenium settings from $testConfig0
	 */
	private $testSettings0 = array(
		'host'			=> 'localhost',
		'port'			=> 'foobarr',
		'wikiUrl' 		=> 'http://localhost/deployment',
		'username' 		=> 'xxxxxxx',
		'userPassword' 	=> '',
		'testBrowser' 	=> 'chrome',
		'startserver' => null,
		'stopserver' => null,
		'seleniumserverexecpath' => null,
		'jUnitLogFile' => null,
		'runAgainstGrid' => null
	);
	/*
	 * Array of expected testSuites from $testConfig0
	 */
	private $testSuites0 = array(
		'SimpleSeleniumTestSuite' 	=> 'maintenance/tests/selenium/SimpleSeleniumTestSuite.php',
		'TestSuiteName' 			=> 'testSuitePath'
	);


	/*
	 * Another sample selenium settings file contents
	 */
	private $testConfig1 =
'
[SeleniumSettings]
host 				= "localhost"
testBrowser 		= "firefox"
';
	/*
	 * Expected browsers from $testConfig1
	 */
	private $testBrowsers1 = null;
	/*
	 * Expected selenium settings from $testConfig1
	 */
	private $testSettings1 = array(
		'host'			=> 'localhost',
		'port'			=> null,
		'wikiUrl' 		=> null,
		'username' 		=> null,
		'userPassword' 	=> null,
		'testBrowser' 	=> 'firefox',
		'startserver' => null,
		'stopserver' => null,
		'seleniumserverexecpath' => null,
		'jUnitLogFile' => null,
		'runAgainstGrid' => null
	);
	/*
	 * Expected test suites from $testConfig1
	 */
	private $testSuites1 = null;


	public function setUp() {
		if ( !defined( 'SELENIUMTEST' ) ) {
			define( 'SELENIUMTEST', true );
		}
	}

	/*
	 * Clean up the temporary file used to store the selenium settings.
	 */
	public function tearDown() {
		if ( strlen( $this->tempFileName ) > 0 ) {
			unlink( $this->tempFileName );
			unset( $this->tempFileName );
		}
		parent::tearDown();
	}

	/**
	 * @expectedException MWException
	 * @group SeleniumFramework
	 */
	public function testErrorOnIncorrectConfigFile() {
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;

		SeleniumConfig::getSeleniumSettings($seleniumSettings,
			$seleniumBrowsers,
			$seleniumTestSuites,
			"Some_fake_settings_file.ini" );

	}

	/**
	 * @expectedException MWException
	 * @group SeleniumFramework
	 */
	public function testErrorOnMissingConfigFile() {
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;
		global $wgSeleniumConfigFile;
		$wgSeleniumConfigFile = '';
		SeleniumConfig::getSeleniumSettings($seleniumSettings,
			$seleniumBrowsers,
			$seleniumTestSuites);
	}

	/**
	 * @group SeleniumFramework
	 */
	public function testUsesGlobalVarForConfigFile() {
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;
		global $wgSeleniumConfigFile;
		$this->writeToTempFile( $this->testConfig0 );
		$wgSeleniumConfigFile = $this->tempFileName;
		SeleniumConfig::getSeleniumSettings($seleniumSettings,
			$seleniumBrowsers,
			$seleniumTestSuites);
		$this->assertEquals($seleniumSettings, $this->testSettings0 ,
		'The selenium settings should have been read from the file defined in $wgSeleniumConfigFile'
		);
		$this->assertEquals($seleniumBrowsers, $this->testBrowsers0,
		'The available browsers should have been read from the file defined in $wgSeleniumConfigFile'
		);
		$this->assertEquals($seleniumTestSuites, $this->testSuites0,
		'The test suites should have been read from the file defined in $wgSeleniumConfigFile'
		);
	}

	/**
	 * @group SeleniumFramework
	 * @dataProvider sampleConfigs
	 */
	public function testgetSeleniumSettings($sampleConfig, $expectedSettings, $expectedBrowsers, $expectedSuites ) {
		$this->writeToTempFile( $sampleConfig );
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;

		SeleniumConfig::getSeleniumSettings($seleniumSettings,
			$seleniumBrowsers,
			$seleniumTestSuites,
			$this->tempFileName );

		$this->assertEquals($seleniumSettings, $expectedSettings,
		"The selenium settings for the following test configuration was not retrieved correctly" . $sampleConfig
		);
		$this->assertEquals($seleniumBrowsers, $expectedBrowsers,
		"The available browsers for the following test configuration was not retrieved correctly" . $sampleConfig
		);
		$this->assertEquals($seleniumTestSuites, $expectedSuites,
		"The test suites for the following test configuration was not retrieved correctly" . $sampleConfig
		);


	}

	/*
	 * create a temp file and write text to it.
	 * @param $testToWrite the text to write to the temp file
	 */
	private function writeToTempFile($textToWrite) {
		$this->tempFileName = tempnam(sys_get_temp_dir(), 'test_settings.');
		$tempFile =	 fopen( $this->tempFileName, "w" );
		fwrite($tempFile , $textToWrite);
		fclose($tempFile);
	}

	/*
	 * Returns an array containing:
	 * 	The contents of the selenium cingiguration ini file
	 *  The expected selenium configuration array that getSeleniumSettings should return
	 *  The expected available browsers array that getSeleniumSettings should return
	 *  The expected test suites arrya that getSeleniumSettings should return
	 */
	public function sampleConfigs() {
		return array(
			array($this->testConfig0, $this->testSettings0, $this->testBrowsers0, $this->testSuites0 ),
			array($this->testConfig1, $this->testSettings1, $this->testBrowsers1, $this->testSuites1 )
		);
	}


}
