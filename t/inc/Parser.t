#!/usr/bin/env php
<?php

require 't/Test.php';
require 'maintenance/parserTests.inc';

error_reporting( E_ALL ^ E_NOTICE );

class ProveTestRecorder extends TestRecorder {

	function record( $name, $res ){}
	function report(){}
	function reportPercentage( $success, $total ){}
}

class ProveParserTest extends ParserTest {
	
	function showSuccess( $desc ){
		pass( $desc );
	}
	
	function showFailure( $desc, $exp, $got ){
		_proclaim( false, $desc, false, $got, $exp );
	}
	
	function showRunFile( $path ){}
}

$options = array( 'quick', 'quiet', 'compare' );
$tester = new ProveParserTest();
$tester->showProgress = false;
$tester->showFailure = false;
$tester->recorder = new ProveTestRecorder( $tester->term );

// Do not output the number of tests, if will be done automatically at the end

$tester->runTestsFromFiles( $wgParserTestFiles );

/* vim: set filetype=php: */
