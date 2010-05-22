<?php
if ( !defined( 'MEDIAWIKI' ) || !defined( 'SELENIUMTEST' ) ) {
	echo "This script cannot be run standalone";
	exit( 1 );
}

// Do not add line break after test output
define( 'MW_TESTLOGGER_CONTINUE_LINE', 1 );
define( 'MW_TESTLOGGER_RESULT_OK', 2 );
define( 'MW_TESTLOGGER_RESULT_ERROR', 3 );

class SeleniumTestSuite extends PHPUnit_Framework_TestSuite {
	private $selenium;

	public function setUp() {
		$this->selenium = Selenium::getInstance();
		$this->selenium->start();
		$this->login();
		// $this->loadPage( 'Testpage', 'edit' );
	}

	public function tearDown() {
		$this->selenium->stop();
	}

	public function login() {
		$this->selenium->login();
	}

	public function loadPage( $title, $action ) {
		$this->selenium->loadPage( $title, $action );
	}
}

