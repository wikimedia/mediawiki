<?php

/**
 * Set up the MediaWiki environment when running tests with "phpunit" command
 *
 * Warning: this file is not included from global scope!
 * @file
 */

global $wgCommandLineMode, $IP, $optionsWithArgs;
$IP = dirname( dirname( dirname( __FILE__ ) ) );
define( 'MW_PHPUNIT_TEST', true );

require_once( "$IP/maintenance/commandLine.inc" );

