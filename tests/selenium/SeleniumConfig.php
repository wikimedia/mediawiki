<?php
if ( !defined( 'SELENIUMTEST' ) ) {
	die( 1 );
}

class SeleniumConfig {

	/**
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

		$configArray = parse_ini_file( $seleniumConfigFile, true );
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
			$seleniumSettings['startserver'] = $configArray['SeleniumSettings']['startserver'];
			$seleniumSettings['stopserver'] = $configArray['SeleniumSettings']['stopserver'];
			$seleniumSettings['seleniumserverexecpath'] = $configArray['SeleniumSettings']['seleniumserverexecpath'];
			$seleniumSettings['jUnitLogFile'] = $configArray['SeleniumSettings']['jUnitLogFile'];
			$seleniumSettings['runAgainstGrid'] = $configArray['SeleniumSettings']['runAgainstGrid'];

			wfRestoreWarnings();
		}
		if ( array_key_exists( 'SeleniumTests', $configArray)  ) {
			wfSuppressWarnings();
			$seleniumTestSuites = $configArray['SeleniumTests']['testSuite'];
			wfRestoreWarnings();
		}
		return true;
	}

	private static function parse_ini_line( $iniLine ) {
		static $specialValues = array( 'false' => false, 'true' => true, 'null' => null );
		list( $key, $value ) = explode( '=', $iniLine, 2 );
		$key = trim( $key );
		$value = trim( $value );

		if ( isset( $specialValues[$value] ) ) {
			$value = $specialValues[$value];
		} else {
			$value = trim( $value, '"' );
		}

		/* Support one-level arrays */
		if ( preg_match( '/^([A-Za-z]+)\[([A-Za-z]+)\]/', $key, $m ) ) {
			$key = $m[1];
			$value = array( $m[2] => $value );
		}

		return array( $key => $value );
	}
}
