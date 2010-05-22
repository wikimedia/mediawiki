<?php
if ( !defined( 'MEDIAWIKI' ) || !defined( 'SELENIUMTEST' ) ) {
	echo "This script cannot be run standalone";
	exit( 1 );
}

class SeleniumTestCase extends PHPUnit_Framework_TestCase { // PHPUnit_Extensions_SeleniumTestCase
	protected $selenium;

	public function setUp() {
		set_time_limit( 60 );
		$this->selenium = Selenium::getInstance();
	}

	public function tearDown() {

	}

	public function __call( $method, $args ) {
		return call_user_func_array( array( $this->selenium, $method ), $args );
	}

	public function assertSeleniumAttributeEquals( $attribute, $value ) {
		$attr = $this->getAttribute( $attribute );
		$this->assertEquals( $attr, $value );
	}

	public function assertSeleniumHTMLContains( $element, $text ) {
		$innerHTML = $this->getText( $element );
		// or assertContains
		$this->assertRegExp( "/$text/", $innerHTML );
	}

}