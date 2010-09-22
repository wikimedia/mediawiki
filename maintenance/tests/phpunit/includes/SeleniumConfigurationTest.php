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
		'testBrowser' 	=> 'chrome'
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
		'testBrowser' 	=> 'firefox'
	);
	/*
	 * Expected test suites from $testConfig1
	 */
	private $testSuites1 = null;		
	
	 
	/*
	 * Clean up the temporary file used to sore the selenium settings.
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
     * This test will throw warnings unless you have the following setting in your php.ini
	 * allow_call_time_pass_reference = On
     */
	public function testErrorOnMissingConfigFile() {		
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;

		SeleniumTestConfig::getSeleniumSettings($seleniumSettings, 
										  		$seleniumBrowsers, 
										  		$seleniumTestSuites,
										  		"Some_fake_settings_file.ini" );
	}
	
	/**
     * @group SeleniumFramework
     * @dataProvider sampleConfigs
	 * This test will throw warnings unless you have the following setting in your php.ini
	 * allow_call_time_pass_reference = On
     */
	public function testgetSeleniumSettings($sampleConfig, $expectedSettings, $expectedBrowsers, $expectedSuites ) {	
		//print "SAMPLE_CONFIG " . $sampleConfig . "\n\n";	
		$this->writeToTempFile( $sampleConfig );
		$seleniumSettings;
		$seleniumBrowsers;
		$seleniumTestSuites;

		SeleniumTestConfig::getSeleniumSettings($seleniumSettings, 
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