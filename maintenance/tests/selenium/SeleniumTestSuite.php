<?php

class SeleniumTestSuite extends PHPUnit_Framework_TestSuite {
	private $selenium;
	private $isSetUp = false;

	// Do not add line break after test output
	const CONTINUE_LINE = 1;
	const RESULT_OK = 2;
	const RESULT_ERROR = 3;

	public function setUp() {
		// Hack because because PHPUnit version 3.0.6 which is on prototype does not
		// run setUp as part of TestSuite::run
		if ( $this->isSetUp ) {
			return;
		}
		$this->isSetUp = true;
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
