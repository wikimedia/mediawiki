#!/usr/bin/env php
<?php

require( dirname( __FILE__ ) . '/../commandLine.inc' );
// XXX: This can go away if everyone switches to PHPUnit 3.5
if ( @file_get_contents( 'PHPUnit/Autoload.php', true ) !== false ) { 
	// Works for PHPUnit >= 3.5
	require_once 'PHPUnit/Autoload.php';
} else {
	// Works for PHPUnit < 3.5
	require_once 'PHPUnit/TextUI/Command.php';
}
define( 'MW_PHPUNIT_TEST', 1 );

$wgLocaltimezone = 'UTC';

/* Tests were failing with sqlite */
global $wgCaches;
$wgCaches[CACHE_DB] = false;

if ( !version_compare( PHPUnit_Runner_Version::id(), "3.4.1", ">" ) ) {
	echo <<<EOF
************************************************************

These tests run best with version PHPUnit 3.4.2 or later.
Earlier versions may show failures because earlier versions
of PHPUnit do not properly implement dependencies.

************************************************************

EOF;
}

class MWPHPUnitCommand extends PHPUnit_TextUI_Command {
	protected function handleCustomTestSuite() {
		$suite = new PHPUnit_Framework_TestSuite;
		if ( !empty( $this->options[1] ) ) {
			$files = $this->options[1];
		} else {
			require( dirname( __FILE__ ) . '/TestFileList.php' );
			$files = $testFiles;
			wfRunHooks( 'UnitTestsList', array( &$files ) );
		}
		foreach ( $files as $file ) {
			$suite->addTestFile( $file );
		}
		$suite->setName( 'MediaWiki test suite' );
		$this->arguments['test'] = $suite;
	}
}

$command = new MWPHPUnitCommand;
$command->run( $argv );

