#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

/* Configuration */

// Evaluate the include path relative to this file
$IP = dirname( dirname( dirname( __FILE__ ) ) );

// Set a flag which can be used to detect when other scripts have been entered through this entry point or not
define( 'MW_PHPUNIT_TEST', true );

// Start up MediaWiki in command-line mode
require_once( "$IP/maintenance/commandLine.inc" );

// Assume UTC for testing purposes
$wgLocaltimezone = 'UTC';

if( !in_array( '--configuration', $_SERVER['argv'] ) ) {
	//Hack to eliminate the need to use the Makefile (which sucks ATM)
	$_SERVER['argv'][] = '--configuration';
	$_SERVER['argv'][] = $IP . '/tests/phpunit/suite.xml';
}

require_once( 'PHPUnit/Runner/Version.php' );
if( version_compare( PHPUnit_Runner_Version::id(), '3.5.0', '>=' ) ) {
	# PHPUnit 3.5.0 introduced a nice autoloader based on class name
	require_once( 'PHPUnit/Autoload.php' );
} else {
	# Keep the old pre PHPUnit 3.5.0 behaviour for compatibility
	require_once( 'PHPUnit/TextUI/Command.php' );
}

$additionalMWCLIArgs = array(
	'verbose' => false,
);

require_once( "$IP/tests/phpunit/MediaWikiPHPUnitCommand.php" );
MediaWikiPHPUnitCommand::main();

