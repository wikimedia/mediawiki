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
	 * see: http://www.phpunit.de/manual/3.4/en/writing-tests-for-phpunit.html
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
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.dataProvider
	 */
	public function testTitleCreation($titleName, $ns, $text) {
		$title = Title::newFromText($titleName, $ns);
		$this->assertEquals($text, "$title", "see if '$titleName' matches '$text'");
	}

	public function testInitialCreation() {
		$title = Title::newMainPage();
		$this->assertEquals("Main Page", "$title", "Test initial creation of a title");

		return $title;
	}

	/**
	 * Instead of putting a bunch of tests in a single test method,
	 * you should put only one or two tests in each test method.  This
	 * way, the test method names can remain descriptive.
	 *
	 * If you want to make tests depend on data created in another
	 * method, you can create dependencies feed whatever you return
	 * from the dependant method (e.g. testInitialCreation in this
	 * example) as arguments to the next method (e.g. $title in
	 * testTitleDepends is whatever testInitialCreatiion returned.)
	 */
	/**
	 * @depends testInitialCreation
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.depends
	 */
	public function testTitleDepends( $title ) {
		$this->assertTrue( $title->isLocal() );
	}

	/**
	 * If the code you're testing can produce Exceptions, you can also
	 * test for them.  In the following example, the test expects a
	 * MWException containing the string "object" in the message..
	 */
	/**
	 * @expectedException MWException object
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.expectedException
	 */
	function testException() {
		$title = Title::newFromText(new Title("test"));
	}
}
