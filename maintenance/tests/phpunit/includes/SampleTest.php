<?php

class TestSample extends PHPUnit_Framework_TestCase {

	function setUp() {
	}

	function testEqual() {
		$title = Title::newFromText("text");
		$this->assertEquals("Text", $title->__toString(), "Title creation");
		$this->assertEquals("Text", "Text", "Automatic string conversion");

		$title = Title::newFromText("text", NS_MEDIA);
		$this->assertEquals("Media:Text", $title->__toString(), "Title creation with namespace");

	}

	/**
	 * @expectedException MWException object
	 *
	 * Above comment tells PHPUnit to expect an exception of the
	 * MWException class containing the string "object" in the
	 * message.
	 */
	function testException() {
		$title = Title::newFromText(new Title("test"));
	}
}
