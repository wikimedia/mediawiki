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

		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgContLang' => Language::factory( 'en' ),
			// User language
			'wgLang' => Language::factory( 'en' ),
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
			'wgLocalInterwikis' => array( 'localtestiw' ),
			'wgCapitalLinks' => true,

			// NOTE: this is why global state is evil.
			// TODO: refactor access to the interwiki codes so it can be injected.
			'wgHooks' => array(
				'InterwikiLoadPrefix' => array(
					function ( $prefix, &$data ) {
						if ( $prefix === 'localtestiw' ) {
							$data = array( 'iw_url' => 'localtestiw' );
						} elseif ( $prefix === 'remotetestiw' ) {
							$data = array( 'iw_url' => 'remotetestiw' );
						}
						return false;
					}
				)
			)
		) );
	}

	/**
	 * Returns a mock GenderCache that will consider a user "female" if the
	 * first part of the user name ends with "a".
	 *
	 * @return GenderCache
	 */
	private function getGenderCache() {
		$genderCache = $this->getMockBuilder( 'GenderCache' )
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
		return array(
			array( NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ),
			array( NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'User:Hansi Maier#stuff and so on' ),
			array( false, 'Hansi_Maier', '', 'en', 'Hansi Maier' ),
			array(
				NS_USER_TALK,
				'hansi__maier',
				'',
				'en',
				'User talk:hansi  maier',
				'User talk:Hansi maier'
			),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( NS_USER, 'Lisa_Müller', '', 'de', 'Benutzerin:Lisa Müller' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( $namespace, $text, $fragment, $lang, $expected, $normalized = null ) {
		if ( $normalized === null ) {
			$normalized = $expected;
		}

		$codec = $this->makeCodec( $lang );
		$actual = $codec->formatTitle( $namespace, $text, $fragment );

		$this->assertEquals( $expected, $actual, 'formatted' );

		// test round trip
		$parsed = $codec->parseTitle( $actual, NS_MAIN );
		$actual2 = $codec->formatTitle(
			$parsed->getNamespace(),
			$parsed->getText(),
			$parsed->getFragment()
		);

		$this->assertEquals( $normalized, $actual2, 'normalized after round trip' );
	}

	public static function provideGetText() {
		return array(
			array( NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ),
			array( NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'Hansi Maier' ),
		);
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
		return array(
			array( NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ),
			array( NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'User:Hansi Maier' ),

			// No capitalization or normalization is applied while formatting!
			array( NS_USER_TALK, 'hansi__maier', '', 'en', 'User talk:hansi  maier' ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( NS_USER, 'Lisa_Müller', '', 'de', 'Benutzerin:Lisa Müller' ),
		);
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

	public static function provideGetFullText() {
		return array(
			array( NS_MAIN, 'Foo_Bar', '', 'en', 'Foo Bar' ),
			array( NS_USER, 'Hansi_Maier', 'stuff_and_so_on', 'en', 'User:Hansi Maier#stuff and so on' ),

			// No capitalization or normalization is applied while formatting!
			array( NS_USER_TALK, 'hansi__maier', '', 'en', 'User talk:hansi  maier' ),
		);
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
		//TODO: test capitalization and trimming
		//TODO: test unicode normalization

		return array(
			array( '  : Hansi_Maier _ ', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'Hansi_Maier', '' ) ),
			array( 'User:::1', NS_MAIN, 'de',
				new TitleValue( NS_USER, '0:0:0:0:0:0:0:1', '' ) ),
			array( ' lisa Müller', NS_USER, 'de',
				new TitleValue( NS_USER, 'Lisa_Müller', '' ) ),
			array( 'benutzerin:lisa Müller#stuff', NS_MAIN, 'de',
				new TitleValue( NS_USER, 'Lisa_Müller', 'stuff' ) ),

			array( ':Category:Quux', NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ),
			array( 'Category:Quux', NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ),
			array( 'Category:Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ),
			array( 'Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_CATEGORY, 'Quux', '' ) ),
			array( ':Quux', NS_CATEGORY, 'en',
				new TitleValue( NS_MAIN, 'Quux', '' ) ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.

			array( 'a b c', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'A_b_c' ) ),
			array( ' a  b  c ', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'A_b_c' ) ),
			array( ' _ Foo __ Bar_ _', NS_MAIN, 'en',
				new TitleValue( NS_MAIN, 'Foo_Bar' ) ),

			//NOTE: cases copied from TitleTest::testSecureAndSplit. Keep in sync.
			array( 'Sandbox', NS_MAIN, 'en', ),
			array( 'A "B"', NS_MAIN, 'en', ),
			array( 'A \'B\'', NS_MAIN, 'en', ),
			array( '.com', NS_MAIN, 'en', ),
			array( '~', NS_MAIN, 'en', ),
			array( '"', NS_MAIN, 'en', ),
			array( '\'', NS_MAIN, 'en', ),

			array( 'Talk:Sandbox', NS_MAIN, 'en',
				new TitleValue( NS_TALK, 'Sandbox' ) ),
			array( 'Talk:Foo:Sandbox', NS_MAIN, 'en',
				new TitleValue( NS_TALK, 'Foo:Sandbox' ) ),
			array( 'File:Example.svg', NS_MAIN, 'en',
				new TitleValue( NS_FILE, 'Example.svg' ) ),
			array( 'File_talk:Example.svg', NS_MAIN, 'en',
				new TitleValue( NS_FILE_TALK, 'Example.svg' ) ),
			array( 'Foo/.../Sandbox', NS_MAIN, 'en',
				'Foo/.../Sandbox' ),
			array( 'Sandbox/...', NS_MAIN, 'en',
				'Sandbox/...' ),
			array( 'A~~', NS_MAIN, 'en',
				'A~~' ),
			// Length is 256 total, but only title part matters
			array( 'Category:' . str_repeat( 'x', 248 ), NS_MAIN, 'en',
				new TitleValue( NS_CATEGORY,
					'X' . str_repeat( 'x', 247 ) ) ),
			array( str_repeat( 'x', 252 ), NS_MAIN, 'en',
				'X' . str_repeat( 'x', 251 ) )
		);
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
		//TODO: test unicode errors

		return array(
			array( '#' ),
			array( '::' ),
			array( '::xx' ),
			array( '::##' ),
			array( ' :: x' ),

			array( 'Talk:File:Foo.jpg' ),
			array( 'Talk:localtestiw:Foo' ),
			array( 'remotetestiw:Foo' ),
			array( '::1' ), // only valid in user namespace
			array( 'User::x' ), // leading ":" in a user name is only valid of IPv6 addresses

			//NOTE: cases copied from TitleTest::testSecureAndSplit. Keep in sync.
			array( '' ),
			array( ':' ),
			array( '__  __' ),
			array( '  __  ' ),
			// Bad characters forbidden regardless of wgLegalTitleChars
			array( 'A [ B' ),
			array( 'A ] B' ),
			array( 'A { B' ),
			array( 'A } B' ),
			array( 'A < B' ),
			array( 'A > B' ),
			array( 'A | B' ),
			// URL encoding
			array( 'A%20B' ),
			array( 'A%23B' ),
			array( 'A%2523B' ),
			// XML/HTML character entity references
			// Note: Commented out because they are not marked invalid by the PHP test as
			// Title::newFromText runs Sanitizer::decodeCharReferencesAndNormalize first.
			//array( 'A &eacute; B' ),
			//array( 'A &#233; B' ),
			//array( 'A &#x00E9; B' ),
			// Subject of NS_TALK does not roundtrip to NS_MAIN
			array( 'Talk:File:Example.svg' ),
			// Directory navigation
			array( '.' ),
			array( '..' ),
			array( './Sandbox' ),
			array( '../Sandbox' ),
			array( 'Foo/./Sandbox' ),
			array( 'Foo/../Sandbox' ),
			array( 'Sandbox/.' ),
			array( 'Sandbox/..' ),
			// Tilde
			array( 'A ~~~ Name' ),
			array( 'A ~~~~ Signature' ),
			array( 'A ~~~~~ Timestamp' ),
			array( str_repeat( 'x', 256 ) ),
			// Namespace prefix without actual title
			array( 'Talk:' ),
			array( 'Category: ' ),
			array( 'Category: #bar' )
		);
	}

	/**
	 * @dataProvider provideParseTitle_invalid
	 */
	public function testParseTitle_invalid( $text ) {
		$this->setExpectedException( 'MalformedTitleException' );

		$codec = $this->makeCodec( 'en' );
		$codec->parseTitle( $text, NS_MAIN );
	}

	public static function provideGetNamespaceName() {
		return array(
			array( NS_MAIN, 'Foo', 'en', '' ),
			array( NS_USER, 'Foo', 'en', 'User' ),
			array( NS_USER, 'Hansi Maier', 'de', 'Benutzer' ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( NS_USER, 'Lisa Müller', 'de', 'Benutzerin' ),
		);
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
