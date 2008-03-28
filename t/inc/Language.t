#!/usr/bin/env php
<?php

require 't/Test.php';

# Test offset usage for a given language::userAdjust
function test_userAdjust( &$langObj, $date, $offset, $expected ) {
	global $wgLocalTZoffset;
	$wgLocalTZoffset = $offset;

	cmp_ok(
		strval( $langObj->userAdjust( $date, '' ) ),
		'==',
		strval( $expected ),
		"User adjust {$date} by {$offset} minutes should give {$expected}"
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

plan( count($userAdjust_tests) );
define( 'MEDIAWIKI', 1 );

# Don't use require_ok as these files need global variables

require 'includes/Defines.php';
require 'includes/ProfilerStub.php';

require 'LocalSettings.php';
require 'includes/DefaultSettings.php';

require 'includes/Setup.php';

# Create a language object
$wgContLang = $en = Language::factory( 'en' );

global $wgUser;
$wgUser = new User();

# Launch tests for language::userAdjust
foreach( $userAdjust_tests as $data ) {
	test_userAdjust( $en, $data[0], $data[1], $data[2] ); 
}

/* vim: set filetype=php: */
