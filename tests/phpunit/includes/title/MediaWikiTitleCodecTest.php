<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Daniel Kinzler
 */

/**
 * @covers MediaWikiTitleCodec
 *
 * @group Title
 * @group Database
 *        ^--- needed because of global state in
 */
class MediaWikiTitleCodecTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
			'wgLocalInterwikis' => [ 'localtestiw' ],
			'wgCapitalLinks' => true,

			// NOTE: this is why global state is evil.
			// TODO: refactor access to the interwiki codes so it can be injected.
			'wgHooks' => [
				'InterwikiLoadPrefix' => [
					function ( $prefix, &$data ) {
						if ( $prefix === 'localtestiw' ) {
							$data = [ 'iw_url' => 'localtestiw' ];
						} elseif ( $prefix === 'remotetestiw' ) {
							$data = [ 'iw_url' => 'remotetestiw' ];
						}
						return false;
					}
				]
			]
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );
	}

	/**
	 * Returns a mock GenderCache that will consider a user "female" if the
	 * first part of the user name ends with "a".
	 *
	 * @return GenderCache
	 */
	private function getGenderCache() {
		$genderCache = $this->getMockBuilder( GenderCache::class )
			->disableOriginalConstructor()
			->getMock();

		$genderCache->expects( $this->any() )
			->method( 'getGenderOf' )
			->will( $this->returnCallback( function ( $userName ) {
				return preg_match( '/^[^- _]+a( |_|$)/u', $userName ) ? 'female' : 'male';
			} ) );

		return $genderCache;
	}

	protected function makeCodec( $lang ) {
		$gender = $this->getGenderCache();
		$lang = Language::factory( $lang );
		// language object can came from cache, which does not respect test settings
		$lang->resetNamespaces();
		return new MediaWikiTitleCodec( $lang, $gender );
	}

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
		if ( $normalized === null ) {
			$normalized = $expected;
		}

		$codec = $this->makeCodec( $lang );
		$actual = $codec->formatTitle( $namespace, $text, $fragment, $interwiki );

		$this->assertEquals( $expected, $actual, 'formatted' );

		// test round trip
		$parsed = $codec->parseTitle( $actual, NS_MAIN );
		$actual2 = $codec->formatTitle(
			$parsed->getNamespace(),
			$parsed->getText(),
			$parsed->getFragment(),
			$parsed->getInterwiki()
		);

		$this->assertEquals( $normalized, $actual2, 'normalized after round trip' );
	}

	public static function provideGetText() {
		return [
			[ NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ],
			[ NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'Hansi Maier' ],
		];
	}

	/**
	 * @dataProvider provideGetText
	 */
	public function testGetText( $namespace, $dbkey, $fragment, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$title = new TitleValue( $namespace, $dbkey, $fragment );

		$actual = $codec->getText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetPrefixedText() {
		return [
			[ NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ],
			[ NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'User:Hansi Maier' ],

			// No capitalization or normalization is applied while formatting!
			[ NS_USER_TALK, 'hansi__maier', '', 'en', 'User talk:hansi  maier' ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[ NS_USER, 'Lisa_Müller', '', 'de', 'Benutzerin:Lisa Müller' ],
			[ 1000000, 'Invalid_namespace', '', 'en', ':Invalid namespace' ],
		];
	}

	/**
	 * @dataProvider provideGetPrefixedText
	 */
	public function testGetPrefixedText( $namespace, $dbkey, $fragment, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$title = new TitleValue( $namespace, $dbkey, $fragment );

		$actual = $codec->getPrefixedText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetPrefixedDBkey() {
		return [
			[ NS_MAIN, 'Foo_Bar', '', '', 'en', 'Foo_Bar' ],
			[ NS_USER, 'Hansi_Maier', 'stuff_and_so_on', '', 'en', 'User:Hansi_Maier' ],

			// No capitalization or normalization is applied while formatting!
			[ NS_USER_TALK, 'hansi__maier', '', '', 'en', 'User_talk:hansi__maier' ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			[ NS_USER, 'Lisa_Müller', '', '', 'de', 'Benutzerin:Lisa_Müller' ],

			[ NS_MAIN, 'Remote_page', '', 'remotetestiw', 'en', 'remotetestiw:Remote_page' ],

			// non-existent namespace
			[ 10000000, 'Foobar', '', '', 'en', ':Foobar' ],
		];
	}

	/**
	 * @dataProvider provideGetPrefixedDBkey
	 */
	public function testGetPrefixedDBkey( $namespace, $dbkey, $fragment,
		$interwiki, $lang, $expected
	) {
		$codec = $this->makeCodec( $lang );
		$title = new TitleValue( $namespace, $dbkey, $fragment, $interwiki );

		$actual = $codec->getPrefixedDBkey( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetFullText() {
		return [
			[ NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ],
			[ NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'User:Hansi Maier#stuff and so on' ],

			// No capitalization or normalization is applied while formatting!
			[ NS_USER_TALK, 'hansi__maier', '', 'en', 'User talk:hansi  maier' ],
		];
	}

	/**
	 * @dataProvider provideGetFullText
	 */
	public function testGetFullText( $namespace, $dbkey, $fragment, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$title = new TitleValue( $namespace, $dbkey, $fragment );

		$actual = $codec->getFullText( $title );

		$this->assertEquals( $expected, $actual );
	}

	public static function provideParseTitle() {
		// TODO: test capitalization and trimming
		// TODO: test unicode normalization

		return [
			[ '  : Hansi_Maier _ ', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'Hansi_Maier', '' ) ],
			[ 'User:::1', NS_MAIN, 'de',
				new TitleValue( NS_USER, '0:0:0:0:0:0:0:1', '' ) ],
			[ ' lisa Müller', NS_USER, 'de',
				new TitleValue( NS_USER, 'Lisa_Müller', '' ) ],
			[ 'benutzerin:lisa Müller#stuff', NS_MAIN, 'de',
				new TitleValue( NS_USER, 'Lisa_Müller', 'stuff' ) ],

			[ ':Category:Quux', NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ],
			[ 'Category:Quux', NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ],
			[ 'Category:Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ],
			[ 'Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ],
			[ ':Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_MAIN, 'Quux', '' ) ],

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.

			[ 'a b c', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'A_b_c' ) ],
			[ ' a  b  c ', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'A_b_c' ) ],
			[ ' _ Foo __ Bar_ _', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'Foo_Bar' ) ],

			// NOTE: cases copied from TitleTest::testSecureAndSplit. Keep in sync.
			[ 'Sandbox', NS_MAIN, 'en', ],
			[ 'A "B"', NS_MAIN, 'en', ],
			[ 'A \'B\'', NS_MAIN, 'en', ],
			[ '.com', NS_MAIN, 'en', ],
			[ '~', NS_MAIN, 'en', ],
			[ '"', NS_MAIN, 'en', ],
			[ '\'', NS_MAIN, 'en', ],

			[ 'Talk:Sandbox', NS_MAIN, 'en',
				new TitleValue( NS_TALK, 'Sandbox' ) ],
			[ 'Talk:Foo:Sandbox', NS_MAIN, 'en',
				new TitleValue( NS_TALK, 'Foo:Sandbox' ) ],
			[ 'File:Example.svg', NS_MAIN, 'en',
				new TitleValue( NS_FILE, 'Example.svg' ) ],
			[ 'File_talk:Example.svg', NS_MAIN, 'en',
				new TitleValue( NS_FILE_TALK, 'Example.svg' ) ],
			[ 'Foo/.../Sandbox', NS_MAIN, 'en',
				'Foo/.../Sandbox' ],
			[ 'Sandbox/...', NS_MAIN, 'en',
				'Sandbox/...' ],
			[ 'A~~', NS_MAIN, 'en',
				'A~~' ],
			// Length is 256 total, but only title part matters
			[ 'Category:' . str_repeat( 'x', 248 ), NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY,
					'X' . str_repeat( 'x', 247 ) ) ],
			[ str_repeat( 'x', 252 ), NS_MAIN, 'en',
				'X' . str_repeat( 'x', 251 ) ]
		];
	}

	/**
	 * @dataProvider provideParseTitle
	 */
	public function testParseTitle( $text, $ns, $lang, $title = null ) {
		if ( $title === null ) {
			$title = str_replace( ' ', '_', trim( $text ) );
		}

		if ( is_string( $title ) ) {
			$title = new TitleValue( NS_MAIN, $title, '' );
		}

		$codec = $this->makeCodec( $lang );
		$actual = $codec->parseTitle( $text, $ns );

		$this->assertEquals( $title, $actual );
	}

	public static function provideParseTitle_invalid() {
		// TODO: test unicode errors

		return [
			[ '#' ],
			[ '::' ],
			[ '::xx' ],
			[ '::##' ],
			[ ' :: x' ],

			[ 'Talk:File:Foo.jpg' ],
			[ 'Talk:localtestiw:Foo' ],
			[ '::1' ], // only valid in user namespace
			[ 'User::x' ], // leading ":" in a user name is only valid of IPv6 addresses

			// NOTE: cases copied from TitleTest::testSecureAndSplit. Keep in sync.
			[ '' ],
			[ ':' ],
			[ '__  __' ],
			[ '  __  ' ],
			// Bad characters forbidden regardless of wgLegalTitleChars
			[ 'A [ B' ],
			[ 'A ] B' ],
			[ 'A { B' ],
			[ 'A } B' ],
			[ 'A < B' ],
			[ 'A > B' ],
			[ 'A | B' ],
			// URL encoding
			[ 'A%20B' ],
			[ 'A%23B' ],
			[ 'A%2523B' ],
			// XML/HTML character entity references
			// Note: Commented out because they are not marked invalid by the PHP test as
			// Title::newFromText runs Sanitizer::decodeCharReferencesAndNormalize first.
			// [ 'A &eacute; B' ],
			// [ 'A &#233; B' ],
			// [ 'A &#x00E9; B' ],
			// Subject of NS_TALK does not roundtrip to NS_MAIN
			[ 'Talk:File:Example.svg' ],
			// Directory navigation
			[ '.' ],
			[ '..' ],
			[ './Sandbox' ],
			[ '../Sandbox' ],
			[ 'Foo/./Sandbox' ],
			[ 'Foo/../Sandbox' ],
			[ 'Sandbox/.' ],
			[ 'Sandbox/..' ],
			// Tilde
			[ 'A ~~~ Name' ],
			[ 'A ~~~~ Signature' ],
			[ 'A ~~~~~ Timestamp' ],
			[ str_repeat( 'x', 256 ) ],
			// Namespace prefix without actual title
			[ 'Talk:' ],
			[ 'Category: ' ],
			[ 'Category: #bar' ]
		];
	}

	/**
	 * @dataProvider provideParseTitle_invalid
	 */
	public function testParseTitle_invalid( $text ) {
		$this->setExpectedException( MalformedTitleException::class );

		$codec = $this->makeCodec( 'en' );
		$codec->parseTitle( $text, NS_MAIN );
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
		$codec = $this->makeCodec( $lang );
		$name = $codec->getNamespaceName( $namespace, $text );

		$this->assertEquals( $expected, $name );
	}
}
