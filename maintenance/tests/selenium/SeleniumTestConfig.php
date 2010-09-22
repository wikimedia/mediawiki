<?php

class SeleniumTestConfig {
	
	/*
	 * Retreives the Selenium configuration values from an ini file.
	 * See sample config file in selenium_settings.ini.sample
	 * 
	 */
	public static function getSeleniumSettings ( &$seleniumSettings, 
										  		 &$seleniumBrowsers, 
										  		 &$seleniumTestSuites, 
										  		 $seleniumConfigFile = null ) {
		if ( $seleniumConfigFile == null ) {
			global $wgSeleniumConfigFile;
			$seleniumConfigFile =  ( isset( $wgSeleniumConfigFile ) ) ? 
									$wgSeleniumConfigFile : dirname( __FILE__ ) . "/../../../selenium_settings.ini";
		}

		if ( !file_exists( $seleniumConfigFile ) ) {
			throw new MWException( "Unable to read local Selenium Settings from " . $seleniumConfigFile . "\n" );
		}
		
		$configArray = parse_ini_file($seleniumConfigFile, true);
		if ( array_key_exists( 'SeleniumSettings', $configArray)  ) {
			wfSuppressWarnings();
			//we may need to change how this is set. But for now leave it in the ini file
			$seleniumBrowsers = $configArray['SeleniumSettings']['browsers'];
			
			$seleniumSettings['host'] = $configArray['SeleniumSettings']['host'];
			$seleniumSettings['port'] = $configArray['SeleniumSettings']['port'];
			$seleniumSettings['wikiUrl'] = $configArray['SeleniumSettings']['wikiUrl'];
			$seleniumSettings['username'] = $configArray['SeleniumSettings']['username'];
			$seleniumSettings['userPassword'] = $configArray['SeleniumSettings']['userPassword'];
			$seleniumSettings['testBrowser'] = $configArray['SeleniumSettings']['testBrowser'];	
			wfRestoreWarnings();
		}
		if ( array_key_exists( 'SeleniumTests', $configArray)  ) {
			wfSuppressWarnings();
			$seleniumTestSuites = $configArray['SeleniumTests']['testSuite'];
			wfRestoreWarnings();
		}
		return true;	
	}

}
