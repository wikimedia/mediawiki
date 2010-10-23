<?php

class TestSample extends PHPUnit_Framework_TestCase {

	/**
	 * Anything that needs to happen before your tests should go here.
	 */
	function setUp() {
	}

	/**
	 * Anything cleanup you need to do should go here.
	 */
	function tearDown() {
	}

	function testEqual() {
		$title = Title::newFromText("text");
		$this->assertEquals("Text", $title->__toString(), "Title creation");
		$this->assertEquals("Text", "Text", "Automatic string conversion");

		$title = Title::newFromText("text", NS_MEDIA);
		$this->assertEquals("Media:Text", $title->__toString(), "Title creation with namespace");

	}

	/**
	 * If you want to run a the same test with a variety of data. use a data provider.
	 * See: http://www.phpunit.de/manual/3.4/en/writing-tests-for-phpunit.html
	 */
	public function provideTitles() {
		return array(
			array( 'Text', NS_MEDIA, 'Media:Text' ),
			array( 'Text', null, 'Text' ),
			array( 'text', null, 'Text' ),
			array( 'Text', NS_USER, 'User:Text' ),
			array( 'Text', NS_USER, 'Blah' )
		);
	}

	/**
	 * @dataProvider provideTitles()
	 */
	public function testCreation($titleName, $ns, $text) {
		$title = Title::newFromText($titleName, $ns);
		$this->assertEquals($text, "$title", "see if '$titleName' matches '$text'");
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
