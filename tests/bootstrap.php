<?php

/**
 * Set up the MediaWiki environment when running tests with "phpunit" command
 *
 * Warning: this file is not included from global scope!
 * @file
 */

global $wgCommandLineMode, $IP;
$wgCommandLineMode = true;
$IP = dirname( dirname( __FILE__ ) );

define( 'MEDIAWIKI', true );
define( 'MW_PHPUNIT_TEST', true );

require_once( "$IP/includes/Defines.php" );
require_once( "$IP/includes/AutoLoader.php" );
require_once( "$IP/LocalSettings.php" );
require_once( "$IP/includes/ProfilerStub.php" );
require_once( "$IP/includes/GlobalFunctions.php" );
require_once( "$IP/includes/Hooks.php" );
$self = __FILE__;
require_once( "$IP/includes/Setup.php" );


