<?php

/**
 * @coversNothing Just a sample
 */
class SampleTest extends MediaWikiLangTestCase {

	/**
	 * Anything that needs to happen before your tests should go here.
	 */
	protected function setUp() {
		// Be sure to call the parent setup and teardown functions.
		// This makes sure that all the various cleanup and restorations
		// happen as they should (including the restoration for setMwGlobals).
		parent::setUp();

		// This sets the globals and will restore them automatically
		// after each test.
		$this->setContentLang( 'en' );
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );
	}

	/**
	 * Anything cleanup you need to do should go here.
	 */
	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * Name tests so that PHPUnit can turn them into sentences when
	 * they run.  While MediaWiki isn't strictly an Agile Programming
	 * project, you are encouraged to use the naming described under
	 * "Agile Documentation" at
	 * https://www.phpunit.de/manual/3.4/en/other-uses-for-tests.html
	 */
	public function testTitleObjectStringConversion() {
		$title = Title::newFromText( "text" );
		$this->assertInstanceOf( Title::class, $title, "Title creation" );
		$this->assertEquals( "Text", $title, "Automatic string conversion" );

		$title = Title::newFromText( "text", NS_MEDIA );
		$this->assertEquals( "Media:Text", $title, "Title creation with namespace" );
	}

	/**
	 * If you want to run the same test with a variety of data, use a data provider.
	 * see: https://www.phpunit.de/manual/3.4/en/writing-tests-for-phpunit.html
	 */
	public static function provideTitles() {
		return [
			[ 'Text', NS_MEDIA, 'Media:Text' ],
			[ 'Text', null, 'Text' ],
			[ 'text', null, 'Text' ],
			[ 'Text', NS_USER, 'User:Text' ],
			[ 'Photo.jpg', NS_FILE, 'File:Photo.jpg' ]
		];
	}

	/**
	 * phpcs:disable Generic.Files.LineLength
	 * @dataProvider provideTitles
	 * See https://phpunit.de/manual/3.7/en/appendixes.annotations.html#appendixes.annotations.dataProvider
	 * phpcs:enable
	 */
	public function testCreateBasicListOfTitles( $titleName, $ns, $text ) {
		$title = Title::newFromText( $titleName, $ns );
		$this->assertEquals( $text, "$title", "see if '$titleName' matches '$text'" );
	}

	public function testSetUpMainPageTitleForNextTest() {
		$title = Title::newMainPage();
		$this->assertEquals( "Main Page", "$title", "Test initial creation of a title" );

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
	 * See https://phpunit.de/manual/3.7/en/appendixes.annotations.html#appendixes.annotations.depends
	 */
	public function testCheckMainPageTitleIsConsideredLocal( $title ) {
		$this->assertTrue( $title->isLocal() );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * See https://phpunit.de/manual/3.7/en/appendixes.annotations.html#appendixes.annotations.expectedException
	 */
	public function testTitleObjectFromObject() {
		$title = Title::newFromText( Title::newFromText( "test" ) );
		$this->assertEquals( "Test", $title->isLocal() );
	}
}
