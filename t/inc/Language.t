#!/usr/bin/env php
<?php

require 'Test.php';

# Test offset usage for a given language::userAdjust
function test_userAdjust( $langObj, $date, $offset, $expected ) {
	global $wgLocalTZoffset;
	$wgLocalTZoffset = $offset;

	cmp_ok(
		$langObj->userAdjust( $date, '' ),
		'==',
		$expected,
		"User adjust $date by $offset minutes should give $expected"
	);
}

# Collection of parameters for Language_t_Offset.
# Format: date to be formatted, localTZoffset value, expected date
$userAdjust_tests = array(
	array( 20061231235959,   0, 20061231235959 ),
	array( 20061231235959,   5, 20070101000459 ),
	array( 20061231235959,  15, 20070101001459 ),
	array( 20061231235959,  60, 20070101005959 ),
	array( 20061231235959,  90, 20070101012959 ),
	array( 20061231235959, 120, 20070101015959 ),
	array( 20061231235959, 540, 20070101085959 ),
	array( 20061231235959,  -5, 20061231235459 ),
	array( 20061231235959, -30, 20061231232959 ),
	array( 20061231235959, -60, 20061231225959 ),
);

plan( 7 + count($userAdjust_tests) );

require_ok( 'includes/Defines.php' );

# require_ok() doesn't work for these, find out why
define( 'MEDIAWIKI', 1 );
require 'LocalSettings.php';
require 'includes/DefaultSettings.php';

# Create a language object
require_ok( 'languages/Language.php' );
require_ok( 'includes/Title.php' );
$wgContLang = $en = Language::factory( 'en' );

# We need an user to test the lang
require_ok( 'includes/GlobalFunctions.php' );
require_ok( 'includes/ProfilerStub.php' );
require_ok( 'includes/Exception.php' );
require_ok( 'includes/User.php' );
global $wgUser;
$wgUser = new User();

# Launch tests for language::userAdjust
foreach( $userAdjust_tests as $data ) {
	test_userAdjust( $en, $data[0], $data[1], $data[2] ); 
}

/* vim: set filetype=php: */
?>
