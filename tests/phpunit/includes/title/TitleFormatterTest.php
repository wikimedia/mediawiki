<?php

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Title\TitleParser
 *
 * @group Title
 * @group Database
 */
class TitleFormatterTest extends TitleCodecTestBase {

	public static function provideFormat() {
		return [
			[ NS_MAIN, 'Foo_Bar', '', '', 'en', 'Foo Bar' ],
			[ NS_USER, 'Hansi_Maier', 'stuff_and_so_on', '', 'en', 'User:Hansi Maier#stuff and so on' ],
			[ false, 'Hansi_Maier', '', '', 'en', 'Hansi Maier' ],
			[
				NS_USER_TALK,
				'hansi__maier',
				'',
				'',
				'en',
				'User talk:hansi  maier',
				'User talk:Hansi maier'
			],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[ NS_USER, 'Lisa_Müller', '', '', 'de', 'Benutzerin:Lisa Müller' ],
			[ NS_MAIN, 'FooBar', '', 'remotetestiw', 'en', 'remotetestiw:FooBar' ],
		];
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( $namespace, $text, $fragment, $interwiki, $lang, $expected,
		$normalized = null
	) {
		$normalized ??= $expected;

		$parser = $this->makeParser( $lang );
		$formatter = $this->makeFormatter( $lang );
		$actual = $formatter->formatTitle( $namespace, $text, $fragment, $interwiki );

		$this->assertEquals( $expected, $actual, 'formatted' );

		// test round trip
		$parsed = $parser->parseTitle( $actual, NS_MAIN );
		$actual2 = $formatter->formatTitle(
			$parsed->getNamespace(),
			$parsed->getText(),
			$parsed->getFragment(),
			$parsed->getInterwiki()
		);

		$this->assertEquals( $normalized, $actual2, 'normalized after round trip' );
	}

	public static function provideGetText() {
		// $title = new TitleValue( $namespace, $dbkey, $fragment );
		return [
			[ new TitleValue( NS_MAIN, 'Foo_Bar', '' ), 'en', 'Foo Bar' ],
			[ new TitleValue( NS_USER, 'Hansi_Maier', 'stuff_and_so_on' ), 'en', 'Hansi Maier' ],
			[ PageIdentityValue::localIdentity( 37, NS_MAIN, 'Foo_Bar' ), 'en', 'Foo Bar' ],
			[ PageIdentityValue::localIdentity( 37, NS_USER, 'Hansi_Maier' ), 'en', 'Hansi Maier' ],
		];
	}

	/**
	 * @dataProvider provideGetText
	 */
	public function testGetText( $title, $lang, $expected ) {
		$formatter = $this->makeFormatter( $lang );
		$actual = $formatter->getText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetPrefixedText() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo_Bar', '' ), 'en', 'Foo Bar' ],
			[ new TitleValue( NS_USER, 'Hansi_Maier', 'stuff_and_so_on' ), 'en', 'User:Hansi Maier' ],

			// No capitalization or normalization is applied while formatting!
			[ new TitleValue( NS_USER_TALK, 'hansi__maier', '' ), 'en', 'User talk:hansi  maier' ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[
				new TitleValue( NS_USER, 'Lisa_Müller', '' ),
				'de', 'Benutzerin:Lisa Müller'
			],
			[
				new TitleValue( 1000000, 'Invalid_namespace', '' ),
				'en',
				'Special:Badtitle/NS1000000:Invalid namespace'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_MAIN, 'Foo_Bar' ),
				'en',
				'Foo Bar'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER, 'Hansi_Maier' ),
				'en',
				'User:Hansi Maier'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER_TALK, 'hansi__maier' ),
				'en',
				'User talk:hansi  maier'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER, 'Lisa_Müller' ),
				'de',
				'Benutzerin:Lisa Müller'
			],
			[
				PageIdentityValue::localIdentity( 37, 1000000, 'Invalid_namespace' ),
				'en',
				'Special:Badtitle/NS1000000:Invalid namespace'
			],
		];
	}

	/**
	 * @dataProvider provideGetPrefixedText
	 */
	public function testGetPrefixedText( $title, $lang, $expected ) {
		$formatter = $this->makeFormatter( $lang );
		$actual = $formatter->getPrefixedText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetPrefixedDBkey() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo_Bar', '', '' ), 'en', 'Foo_Bar' ],
			[ new TitleValue( NS_USER, 'Hansi_Maier', 'stuff_and_so_on', '' ), 'en', 'User:Hansi_Maier' ],

			// No capitalization or normalization is applied while formatting!
			[ new TitleValue( NS_USER_TALK, 'hansi__maier', '', '' ), 'en', 'User_talk:hansi__maier' ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[ new TitleValue( NS_USER, 'Lisa_Müller', '', '' ), 'de', 'Benutzerin:Lisa_Müller' ],

			[ new TitleValue( NS_MAIN, 'Remote_page', '', 'remotetestiw' ), 'en', 'remotetestiw:Remote_page' ],

			// non-existent namespace
			[ new TitleValue( 10000000, 'Foobar', '', '' ), 'en', 'Special:Badtitle/NS10000000:Foobar' ],

			[
				PageIdentityValue::localIdentity( 37, NS_MAIN, 'Foo_Bar' ),
				'en',
				'Foo_Bar'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER, 'Hansi_Maier' ),
				'en',
				'User:Hansi_Maier'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER_TALK, 'hansi__maier' ),
				'en',
				'User_talk:hansi__maier'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER, 'Lisa_Müller' ),
				'de',
				'Benutzerin:Lisa_Müller'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_MAIN, 'Remote_page' ),
				'en',
				'Remote_page'
			],
			[
				PageIdentityValue::localIdentity( 37, 10000000, 'Foobar' ),
				'en',
				'Special:Badtitle/NS10000000:Foobar'
			],
		];
	}

	/**
	 * @dataProvider provideGetPrefixedDBkey
	 */
	public function testGetPrefixedDBkey( $title, $lang, $expected
	) {
		$formatter = $this->makeFormatter( $lang );
		$actual = $formatter->getPrefixedDBkey( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetFullText() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo_Bar', '' ), 'en', 'Foo Bar' ],
			[ new TitleValue( NS_USER, 'Hansi_Maier', 'stuff_and_so_on' ), 'en', 'User:Hansi Maier#stuff and so on' ],

			// No capitalization or normalization is applied while formatting!
			[ new TitleValue( NS_USER_TALK, 'hansi__maier', '' ), 'en', 'User talk:hansi  maier' ],

			[ new TitleValue( NS_MAIN, 'Foo_Bar' ), 'en', 'Foo Bar' ],
			[ new TitleValue( NS_USER, 'Hansi_Maier' ), 'en', 'User:Hansi Maier' ],

			[
				PageIdentityValue::localIdentity( 37, NS_MAIN, 'Foo_Bar' ),
				'en',
				'Foo Bar'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER, 'Hansi_Maier' ),
				'en',
				'User:Hansi Maier'
			],
			[
				PageIdentityValue::localIdentity( 37, NS_USER_TALK, 'hansi__maier' ),
				'en',
				'User talk:hansi  maier'
			],
		];
	}

	/**
	 * @dataProvider provideGetFullText
	 */
	public function testGetFullText( $title, $lang, $expected ) {
		$formatter = $this->makeFormatter( $lang );
		$actual = $formatter->getFullText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetNamespaceName() {
		return [
			[ NS_MAIN, 'Foo', 'en', '' ],
			[ NS_USER, 'Foo', 'en', 'User' ],
			[ NS_USER, 'Hansi Maier', 'de', 'Benutzer' ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[ NS_USER, 'Lisa Müller', 'de', 'Benutzerin' ],
		];
	}

	/**
	 * @dataProvider provideGetNamespaceName
	 */
	public function testGetNamespaceName( $namespace, $text, $lang, $expected ) {
		$formatter = $this->makeFormatter( $lang );
		$name = $formatter->getNamespaceName( $namespace, $text );

		$this->assertEquals( $expected, $name );
	}
}
