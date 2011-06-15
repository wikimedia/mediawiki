<?php

class TestSample extends MediaWikiLangTestCase {

	/**
	 * Anything that needs to happen before your tests should go here.
	 */
	function setUp() {
		global $wgContLang;
		parent::setUp();

		/* For example, we need to set $wgContLang for creating a new Title */
		$wgContLang = Language::factory( 'en' );
	}

	/**
	 * Anything cleanup you need to do should go here.
	 */
	function tearDown() {
		parent::tearDown();
	}

	/**
	 * Name tests so that PHPUnit can turn them into sentances when
	 * they run.  While MediaWiki isn't strictly an Agile Programming
	 * project, you are encouraged to use the naming described under
	 * "Agile Documentation" at
	 * http://www.phpunit.de/manual/3.4/en/other-uses-for-tests.html
	 */
	function testTitleObjectStringConversion() {
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
			array( 'Photo.jpg', NS_IMAGE, 'File:Photo.jpg' )
		);
	}

	/**
	 * @dataProvider provideTitles
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.dataProvider
	 */
	public function testCreateBasicListOfTitles($titleName, $ns, $text) {
		$title = Title::newFromText($titleName, $ns);
		$this->assertEquals($text, "$title", "see if '$titleName' matches '$text'");
	}

	public function testSetUpMainPageTitleForNextTest() {
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
	 * @depends testSetUpMainPageTitleForNextTest
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.depends
	 */
	public function testCheckMainPageTitleIsConsideredLocal( $title ) {
		$this->assertTrue( $title->isLocal() );
	}

	/**
	 * @expectedException MWException object
	 * See http://www.phpunit.de/manual/3.4/en/appendixes.annotations.html#appendixes.annotations.expectedException
	 */
	function testTitleObjectFromObject() {
		$title = Title::newFromText( Title::newFromText( "test" ) );
		$this->assertEquals( "Test", $title->isLocal() );
	}
}

