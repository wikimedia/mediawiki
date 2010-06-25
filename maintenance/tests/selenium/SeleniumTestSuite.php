<?php

class SeleniumTestSuite extends PHPUnit_Framework_TestSuite {
	private $selenium;

	// Do not add line break after test output
	const CONTINUE_LINE = 1;
	const RESULT_OK = 2;
	const RESULT_ERROR = 3;

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

