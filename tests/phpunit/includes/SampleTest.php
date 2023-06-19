<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

/**
 * @coversNothing Just a sample
 */
class SampleTest extends MediaWikiLangTestCase {

	/**
	 * Anything that needs to happen before your tests should go here.
	 */
	protected function setUp(): void {
		// Be sure to call the parent setup and teardown functions.
		// This makes sure that all the various cleanup and restorations
		// happen as they should (including the restoration for setMwGlobals).
		parent::setUp();

		// This sets the config settings, and will restore them automatically
		// after each test.
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::CapitalLinks => true,
		] );
	}

	/**
	 * Anything cleanup you need to do should go here.
	 */
	protected function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * Name tests so that PHPUnit can turn them into sentences when
	 * they run. You are encouraged to use the naming described at:
	 * https://phpunit.de/manual/6.5/en/other-uses-for-tests.html
	 */
	public function testTitleObjectStringConversion() {
		$title = Title::makeTitle( NS_MAIN, "Text" );
		$this->assertInstanceOf( Title::class, $title, "Title creation" );
		$this->assertEquals( "Text", $title, "Automatic string conversion" );

		$title = Title::makeTitle( NS_MEDIA, "Text" );
		$this->assertEquals( "Media:Text", $title, "Title creation with namespace" );
	}

	/**
	 * If you want to run the same test with a variety of data, use a data provider.
	 * See https://phpunit.de/manual/6.5/en/writing-tests-for-phpunit.html
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
	 * @dataProvider provideTitles
	 * See https://phpunit.de/manual/6.5/en/appendixes.annotations.html#appendixes.annotations.dataProvider
	 */
	public function testCreateBasicListOfTitles( $titleName, $ns, $text ) {
		$title = Title::newFromText( $titleName, $ns );
		$this->assertEquals( $text, "$title", "see if '$titleName' matches '$text'" );
	}

	/**
	 * Instead of putting a bunch of tests in a single test method,
	 * you should put only one or two tests in each test method.  This
	 * way, the test method names can remain descriptive.
	 */

	/**
	 * See https://phpunit.de/manual/6.5/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.exceptions
	 */
	public function testTitleObjectFromObject() {
		$this->expectException( InvalidArgumentException::class );
		Title::newFromText( Title::makeTitle( NS_MAIN, 'Test' ) );
	}
}
