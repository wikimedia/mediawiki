<?php
/**
 * Dynamically change configuration variables based on the test suite name and a cookie value.
 * For details on how to configure a wiki for a Selenium test, see:
 * http://www.mediawiki.org/wiki/SeleniumFramework#Test_Wiki_configuration
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

require_once( "$IP/includes/GlobalFunctions.php" );

$fname = 'SeleniumWebSettings.php';
wfProfileIn( $fname );

$cookiePrefix = $wgSitename . '-';
$cookieName = $cookiePrefix . 'Selenium';

// this is a fallback SQL file
$testSqlFile = false;
$testImageZip = false;
	
// if we find a request parameter containing the test name, set a cookie with the test name
if ( isset( $_GET['setupTestSuite'] ) ) {
	$setupTestSuiteName = $_GET['setupTestSuite'];

	if (
		preg_match( '/[^a-zA-Z0-9_-]/', $setupTestSuiteName ) ||
		!isset( $wgSeleniumTestConfigs[$setupTestSuiteName] )
	)
	{
		return;
	}
	if ( strlen( $setupTestSuiteName ) > 0 ) {
		$expire = time() + 600;
		setcookie(
			$cookieName,
			$setupTestSuiteName,
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			true
		);
	}
	
	$testIncludes = array(); // array containing all the includes needed for this test
	$testGlobalConfigs = array(); // an array containg all the global configs needed for this test
	$testResourceFiles = array(); // an array containing all the resource files needed for this test
	$callback = $wgSeleniumTestConfigs[$setupTestSuiteName];
	call_user_func_array( $callback, array( &$testIncludes, &$testGlobalConfigs, &$testResourceFiles));

	if ( isset( $testResourceFiles['images'] ) ) {
		$testImageZip = $testResourceFiles['images'];
	}
	
	if ( isset( $testResourceFiles['db'] ) ) {
		$testSqlFile = $testResourceFiles['db'];
		$testResourceName = getTestResourceNameFromTestSuiteName( $setupTestSuiteName );
	
		switchToTestResources( $testResourceName, false ); // false means do not switch database yet
		setupTestResources( $testResourceName, $testSqlFile, $testImageZip );
	}
}

// clear the cookie based on a request param
if ( isset( $_GET['clearTestSuite'] ) ) {
	$testSuiteName = getTestSuiteNameFromCookie( $cookieName );

	$expire = time() - 600; 
	setcookie(
		$cookieName,
		'',
		$expire,
		$wgCookiePath,
		$wgCookieDomain,
		$wgCookieSecure,
		true
	);
	
	$testResourceName = getTestResourceNameFromTestSuiteName( $testSuiteName );
	teardownTestResources( $testResourceName );
}

// if a cookie is found, run the appropriate callback to get the config params.
if ( isset( $_COOKIE[$cookieName] ) ) {		
	$testSuiteName = getTestSuiteNameFromCookie( $cookieName );
	if ( !isset( $wgSeleniumTestConfigs[$testSuiteName] ) ) {
		return;
	}
	
	$testIncludes = array(); // array containing all the includes needed for this test
	$testGlobalConfigs = array(); // an array containg all the global configs needed for this test
	$testResourceFiles = array(); // an array containing all the resource files needed for this test
	$callback = $wgSeleniumTestConfigs[$testSuiteName]; 
	call_user_func_array( $callback, array( &$testIncludes, &$testGlobalConfigs, &$testResourceFiles));

	if ( isset( $testResourceFiles['db'] ) ) {
		$testResourceName = getTestResourceNameFromTestSuiteName( $testSuiteName );
		switchToTestResources( $testResourceName );
	}
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

function getTestSuiteNameFromCookie( $cookieName ) {
	$testSuiteName = null;
	if ( isset( $_COOKIE[$cookieName] ) ) {
		$testSuiteName = $_COOKIE[$cookieName];
	}
	return $testSuiteName;
}

function getTestResourceNameFromTestSuiteName( $testSuiteName ) {
	$testResourceName = null;
	if ( isset( $testSuiteName ) ) {
		$testResourceName = $testSuiteName;
	}
	return $testResourceName;
}

function getTestUploadPathFromResourceName( $testResourceName ) {
	global $IP;
	$testUploadPath = "$IP/images/$testResourceName";
	return $testUploadPath;
}

function setupTestResources( $testResourceName, $testSqlFile, $testImageZip ) {
	global $wgDBname;

	// Basic security. Do not allow to drop productive database.
	if ( $testResourceName == $wgDBname ) {
		die( 'Cannot override productive database.' );
	}
	if ( $testResourceName == '' ) {
		die( 'Cannot identify a test the resources should be installed for.' );
	}
	
	// create tables
	$dbw = wfGetDB( DB_MASTER );
	$dbw->query( 'DROP DATABASE IF EXISTS ' . $testResourceName );
	$dbw->query( 'CREATE DATABASE ' . $testResourceName );

	// do not set the new DB name before database is setup
	$wgDBname = $testResourceName;
	$dbw->selectDB( $testResourceName );
	// populate from SQL file
	if ( $testSqlFile ) {
		$dbw->sourceFile( $testSqlFile );
	}

	// create test image dir
	$testUploadPath = getTestUploadPathFromResourceName( $testResourceName );
	if ( !file_exists( $testUploadPath ) ) {
		mkdir( $testUploadPath );
	}

	if ( $testImageZip ) {
		$zip = new ZipArchive();
		$zip->open( $testImageZip );
		$zip->extractTo( $testUploadPath );
		$zip->close();
	}
}

function teardownTestResources( $testResourceName ) {
	// remove test database
	$dbw = wfGetDB( DB_MASTER );
	$dbw->query( 'DROP DATABASE IF EXISTS ' . $testResourceName );

	$testUploadPath = getTestUploadPathFromResourceName( $testResourceName );
	// remove test image dir
	if ( file_exists( $testUploadPath ) ) {
		wfRecursiveRemoveDir( $testUploadPath );
	}
}

function switchToTestResources( $testResourceName, $switchDB = true ) {
	global $wgDBuser, $wgDBpassword, $wgDBname;
	global $wgDBtestuser, $wgDBtestpassword;
	global $wgUploadPath;

	if ( $switchDB ) {
		$wgDBname = $testResourceName;
	}
	$wgDBuser = $wgDBtestuser;
	$wgDBpassword = $wgDBtestpassword;

	$testUploadPath = getTestUploadPathFromResourceName( $testResourceName );
	$wgUploadPath = $testUploadPath;
}
