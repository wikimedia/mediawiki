<?php
if ( !defined( 'SELENIUMTEST' ) ) {
	die( 1 );
}

class SeleniumConfig {
	
	/*
	 * Retreives the Selenium configuration values from an ini file.
	 * See sample config file in selenium_settings.ini.sample
	 * 
	 */

	public static function getSeleniumSettings ( &$seleniumSettings, 
			&$seleniumBrowsers, 
			&$seleniumTestSuites, 
			$seleniumConfigFile = null ) {
		if ( strlen( $seleniumConfigFile ) == 0 ) {
			global $wgSeleniumConfigFile;
			if ( isset( $wgSeleniumConfigFile ) ) $seleniumConfigFile =  $wgSeleniumConfigFile ;
		}

		if ( strlen( $seleniumConfigFile ) == 0 || !file_exists( $seleniumConfigFile ) ) {
			throw new MWException( "Unable to read local Selenium Settings from " . $seleniumConfigFile . "\n" );
		}
		
		if ( !defined( 'PHP_VERSION_ID' ) ||
			( PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION < 3 ) ) {
			$configArray = self::parse_5_2_ini_file( $seleniumConfigFile );
		} else {
			$configArray = parse_ini_file( $seleniumConfigFile, true );
		}
		if ( $configArray === false ) {
			throw new MWException( "Error parsing " . $seleniumConfigFile . "\n" );
		}

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

	private static function parse_5_2_ini_file ( $ConfigFile ) {

		$configArray = parse_ini_file( $ConfigFile, true );
		if ( $configArray === false ) return false;

		// PHP 5.2 ini files have [browsers] and [testSuite] sections
		// to get around lack of support for array keys. It then
		// inserts the section arrays into the appropriate places in
		// the SeleniumSettings and SeleniumTests arrays.

		if ( isset( $configArray[browsers] ) ) {
			$configArray[SeleniumSettings][browsers] = $configArray[browsers];
			unset ( $configArray[browsers] );
		}

		if ( isset( $configArray[testSuite] ) ) {
			$configArray[SeleniumTests][testSuite] = $configArray[testSuite];
			unset ( $configArray[testSuite] );
		}

		return $configArray;

	}

}
