<?php
/*
 * Dynamically change configuration variables based on the test suite name and a cookie value.
 * For details on how to configure a wiki for a Selenium test, see:
 * http://www.mediawiki.org/wiki/SeleniumFramework#Test_Wiki_configuration
 */
if ( !$wgEnableSelenium ) {
	return;
}
$cookiePrefix = $wgSitename . "-";
$name = $cookiePrefix . "Selenium";

//if we find a request parameter containing the test name, set a cookie with the test name
if ( array_key_exists( 'setupTestSuite', $_GET) ) {
	//TODO: do a check for valid testsuite names
	$setupTestSuiteName = $_GET['setupTestSuite'];
	if ( strlen( $setupTestSuiteName) > 0 ) {
		$expire = time() + 600;
		setcookie( $name,
			$setupTestSuiteName,
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			true );
	}
}
//clear the cookie based on a request param
if ( array_key_exists( 'clearTestSuite', $_GET) ) {
		$expire = time() - 600; 
		setcookie( $name,
			'',
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			true );
}

//if a cookie is found, run the appropriate callback to get the config params.
if ( array_key_exists( $name, $_COOKIE) ) {		
	$testSuiteName = $_COOKIE[$name];
	$testIncludes = array(); //array containing all the includes needed for this test
      $testGlobalConfigs = array(); //an array containg all the global configs needed for this test
      if ( isset( $wgSeleniumTestConfigs ) && array_key_exists($testSuiteName, $wgSeleniumTestConfigs) ) {
      	$callback = $wgSeleniumTestConfigs[$testSuiteName]; 
      	call_user_func_array( $callback, array( &$testIncludes, &$testGlobalConfigs));
      }
      
	foreach ( $testIncludes as $includeFile ) {
		$file = $IP . '/' . $includeFile;
		require_once( $file );
	}
	foreach ( $testGlobalConfigs as $key => $value ) {
		if ( is_array( $value ) ) {
			
			$configArray = array();
			if ( isset( $GLOBALS[$key] ) ) {
				$configArray = $GLOBALS[$key];
			}
			foreach ( $value as $configKey => $configValue ) {
				$configArray[$configKey] = $configValue;
			}
			$GLOBALS[$key] = $configArray;
		} else {
			$GLOBALS[$key] = $value;
		}
	}
}