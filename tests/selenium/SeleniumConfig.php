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

	/**
	 * PHP 5.2 parse_ini_file() doesn't have support for array keys.
	 * This function parses simple ini files with such syntax using just
	 * 5.2 functions.
	 */
	private static function parse_5_2_ini_file( $ConfigFile ) {
		$file = fopen( $ConfigFile, "rt" );
		if ( !$file ) {
			return false;
		}
		$header = '';
		
		$configArray = array();
		
		while ( ( $line = fgets( $file ) ) !== false ) {
			$line = strtok( $line, "\r\n" );
			
			if ( !$line || $line[0] == ';' ) continue;
			
			if ( $line[0] == '[' && substr( $line, -1 ) == ']' ) {
				$header = substr( $line, 1, -1 );
				$configArray[$header] = array();
			} else {
				$configArray[$header] = array_merge_recursive( $configArray[$header], self::parse_ini_line( $line ) );
			}
		}
		var_dump($configArray);
		return $configArray;
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
