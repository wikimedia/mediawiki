<?php
/*
 * Dynamically change configuration variables based on the test suite name and a cookie value.
 * For details on how to configure a wiki for a Selenium test, see:
 * http://www.mediawiki.org/wiki/SeleniumFramework#Test_Wiki_configuration
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

$fname = 'SeleniumWebSettings.php';
wfProfileIn( $fname );

$cookiePrefix = $wgSitename . "-";
$cookieName = $cookiePrefix . "Selenium";

//if we find a request parameter containing the test name, set a cookie with the test name
if ( isset( $_GET['setupTestSuite'] ) ) {
	$setupTestSuiteName = $_GET['setupTestSuite'];
	
	if ( preg_match( '/[^a-zA-Z0-9_-]/', $setupTestSuiteName ) || !isset( $wgSeleniumTestConfigs[$setupTestSuiteName] ) ) {
		return;
	}
	if ( strlen( $setupTestSuiteName) > 0 ) {
		$expire = time() + 600;
		setcookie( $cookieName,
			$setupTestSuiteName,
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			true );
	}
}
//clear the cookie based on a request param
if ( isset( $_GET['clearTestSuite'] ) ) {
		$expire = time() - 600; 
		setcookie( $cookieName,
			'',
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			true );
}

//if a cookie is found, run the appropriate callback to get the config params.
if ( isset( $_COOKIE[$cookieName] ) ) {		
	$testSuiteName = $_COOKIE[$cookieName];
	if ( !isset( $wgSeleniumTestConfigs[$testSuiteName] ) ) {
		return;
	}
	$testIncludes = array(); //array containing all the includes needed for this test
	$testGlobalConfigs = array(); //an array containg all the global configs needed for this test
	$callback = $wgSeleniumTestConfigs[$testSuiteName]; 
	call_user_func_array( $callback, array( &$testIncludes, &$testGlobalConfigs));
      
	foreach ( $testIncludes as $includeFile ) {
		$file = $IP . '/' . $includeFile;
		require_once( $file );
	}
	foreach ( $testGlobalConfigs as $key => $value ) {
		if ( is_array( $value ) ) {		
			$GLOBALS[$key] = array_merge( $GLOBALS[$key], $value );
			
		} else {
			$GLOBALS[$key] = $value;
		}
	}
}

wfProfileOut( $fname );
