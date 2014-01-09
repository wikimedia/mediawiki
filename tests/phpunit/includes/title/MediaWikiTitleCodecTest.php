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
 * @license GPL 2+
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
			'wgLocalInterwiki' => 'localtestiw',

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
			->will( $this->returnCallback( function( $userName ) {
				return preg_match( '/^[^- _]+a( |_|$)/u', $userName ) ? 'female' : 'male';
			} ) );

		return $genderCache;
	}

	protected function makeCodec( $lang ) {
		$gender = $this->getGenderCache();
		$lang = Language::factory( $lang );
		return new MediaWikiTitleCodec( $lang, $gender );
	}

	public function provideNormalizeForDisplay() {
		return array(
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar' ) ),

			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_MAIN, '', 'foo' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, '', 'foo' ) ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_TALK, '', 'foo' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_TALK, '', 'foo' ) ),

			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_USER, 'Hansi Maier' ) ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, '::1' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_USER, '0:0:0:0:0:0:0:1' ) ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER_TALK, 'hansi Maier', 'stuff' ),
				new TitleValue( TitleValue::TITLE_FORM, NS_USER_TALK, 'Hansi Maier', 'stuff' ) ),
		);
	}

	/**
	 * @dataProvider provideNormalizeForDisplay
	 */
	public function testNormalizeForDisplay( TitleValue $title, TitleValue $expected ) {
		$codec = $this->makeCodec( 'en' );
		$actual = $codec->normalizeForDisplay( $title );

		$this->assertEquals( $expected, $actual );
	}


	public function provideNormalizeForDatabase() {
		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier' ) ),

			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_MAIN, '', 'foo' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, '', 'foo' ) ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_TALK, '', 'foo' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_TALK, '', 'foo' ) ),

			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar' ) ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER, '::1' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_USER, '0:0:0:0:0:0:0:1' ) ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER_TALK, 'hansi_Maier', 'stuff' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_USER_TALK, 'Hansi_Maier', 'stuff' ) ),

			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar', '_foo_' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar', '_foo_' ) ),
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar', ':foo:' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar', ':foo:' ) ),
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar', '#foo#' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar', '#foo#' ) ),
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar', ' foo ' ),
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar', '_foo_' ) ),
		);
	}

	/**
	 * @dataProvider provideNormalizeForDatabase
	 */
	public function testNormalizeForDatabase( TitleValue $title, TitleValue $expected ) {
		$codec = $this->makeCodec( 'en' );
		$actual = $codec->normalizeForDatabase( $title );

		$this->assertEquals( $expected, $actual );
	}

	public function provideFormatForDisplay() {
		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar' ),
				TitleFormatter::INCLUDE_ALL, 'en', 'Foo Bar' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'stuff' ),
				TitleFormatter::INCLUDE_ALL, 'en', 'User:Hansi Maier#stuff' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'stuff' ),
				TitleFormatter::INCLUDE_BASE, 'en', 'Hansi Maier' ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER_TALK, 'hansi_Maier' ),
				TitleFormatter::INCLUDE_ALL, 'en', 'User talk:Hansi Maier' ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Lisa_Müller', 'stuff' ),
				TitleFormatter::INCLUDE_NAMESPACE, 'de', 'Benutzerin:Lisa Müller' ),
		);
	}

	/**
	 * @dataProvider provideFormatForDisplay
	 */
	public function testFormatForDisplay( TitleValue $title, $parts, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$actual = $codec->formatForDisplay( $title, $parts );

		$this->assertEquals( $expected, $actual );

		// test round trip
		if ( $title->getForm()  !== TitleValue::UNKNOWN_FORM ) {
			$full = $codec->formatForDisplay( $title, TitleFormatter::INCLUDE_ALL );
			$parsed = $codec->parseTitle( $full, NS_MAIN, $title->getForm() );

			$this->assertEquals( $title, $parsed );
		}
	}

	public function provideFormatForDatabase() {
		return array(
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar' ),
				'en', 'Foo_Bar' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'stuff' ),
				'en', 'Hansi_Maier' ),
		);
	}

	/**
	 * @dataProvider provideFormatForDatabase
	 */
	public function testFormatForDatabase( TitleValue $title, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$actual = $codec->formatForDatabase( $title );

		$this->assertEquals( $expected, $actual );

		// test round trip
		if ( $title->getForm()  !== TitleValue::UNKNOWN_FORM ) {
			$full = $codec->formatForDatabase( $title, TitleFormatter::INCLUDE_ALL );
			$parsed = $codec->parseTitle( $full, NS_MAIN, $title->getForm() );

			$this->assertEquals( $title, $parsed );
		}
	}

	public function provideParseTitle() {
		//TODO: test capitalization and trimming
		//TODO: test unicode normalization

		return array(
			array( '  : Hansi_Maier _ ', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Hansi Maier', '' ) ),
			array( 'User:::1', NS_MAIN, TitleValue::DBKEY_FORM, 'de',
				new TitleValue( TitleValue::DBKEY_FORM, NS_USER, '0:0:0:0:0:0:0:1', '' ) ),
			array( ' lisa Müller', NS_USER, TitleValue::DBKEY_FORM, 'de',
				new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Lisa_Müller', '' ) ),
			array( 'benutzerin:lisa Müller#stuff', NS_MAIN, TitleValue::TITLE_FORM, 'de',
				new TitleValue( TitleValue::TITLE_FORM, NS_USER, 'Lisa Müller', 'stuff' ) ),

			array( ':Category:Quux', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_CATEGORY, 'Quux', '' ) ),
			array( 'Category:Quux', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_CATEGORY, 'Quux', '' ) ),
			array( 'Category:Quux', NS_CATEGORY, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_CATEGORY, 'Quux', '' ) ),
			array( 'Quux', NS_CATEGORY, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_CATEGORY, 'Quux', '' ) ),
			array( ':Quux', NS_CATEGORY, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Quux', '' ) ),

			// for UNKNOWN_FORM, spaces and underscores are kept unchanged,
			// and no capitalization is applied.
			array( 'benutzerin:lisa_maria Müller#stuff', NS_MAIN, TitleValue::UNKNOWN_FORM, 'de',
				new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER, 'lisa_maria Müller', 'stuff' ) ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( 'benutzerin:lisa Müller#stuff', NS_MAIN, TitleValue::DBKEY_FORM, 'en',
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Benutzerin:lisa_Müller',
					'stuff' ) ),

			//NOTE: cases copied from TitleTest::testSecureAndSplit. Keep in sync.
			array( 'Sandbox', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( 'A "B"', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( 'A \'B\'', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( '.com', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( '~', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( '"', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),
			array( '\'', NS_MAIN, TitleValue::TITLE_FORM, 'en', ),

			array( 'Talk:Sandbox', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_TALK, 'Sandbox' ) ),
			array( 'Talk:Foo:Sandbox', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_TALK, 'Foo:Sandbox' ) ),
			array( 'File:Example.svg', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_FILE, 'Example.svg' ) ),
			array( 'File_talk:Example.svg', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_FILE_TALK, 'Example.svg' ) ),
			array( 'Foo/.../Sandbox', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				'Foo/.../Sandbox' ),
			array( 'Sandbox/...', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				'Sandbox/...' ),
			array( 'A~~', NS_MAIN, TitleValue::TITLE_FORM, 'en',
				'A~~' ),
			// Length is 256 total, but only title part matters
			array( 'Category:' . str_repeat( 'x', 248 ), NS_MAIN, TitleValue::TITLE_FORM, 'en',
				new TitleValue( TitleValue::TITLE_FORM, NS_CATEGORY,
					'X' . str_repeat( 'x', 247 ) ) ),
			array( str_repeat( 'x', 252 ), NS_MAIN, TitleValue::TITLE_FORM, 'en',
				'X' . str_repeat( 'x', 251 ) )
		);
	}

	/**
	 * @dataProvider provideParseTitle
	 */
	public function testParseTitle( $text, $ns, $form, $lang, $title = null ) {
		if ( $title === null ) {
			$title = $text;
		}

		if ( is_string( $title ) ) {
			$title = new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, $title, '' );
		}

		$codec = $this->makeCodec( $lang );
		$actual = $codec->parseTitle( $text, $ns, $form );

		$this->assertEquals( $title, $actual );
	}

	public function provideParseTitle_invalid() {
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
		$this->setExpectedException( 'InvalidArgumentException' );

		$codec = $this->makeCodec( 'en' );
		$codec->parseTitle( $text, NS_MAIN, TitleValue::DBKEY_FORM );
	}

	public function provideGetNamespaceName() {
		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo' ), 'en', '' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Foo' ), 'en', 'User' ),
			array( new TitleValue( TitleValue::TITLE_FORM, NS_USER, 'Hansi Maier' ), 'de', 'Benutzer' ),

			// getGenderCache() provides a mock that considers first
			// names ending in "a" to be female.
			array( new TitleValue( TitleValue::TITLE_FORM, NS_USER, 'Lisa Müller' ), 'de', 'Benutzerin' ),
		);
	}

	/**
	 * @dataProvider provideGetNamespaceName
	 *
	 * @param TitleValue $title
	 * @param $lang
	 * @param $expected
	 */
	public function testGetNamespaceName( TitleValue $title, $lang, $expected ) {
		$codec = $this->makeCodec( $lang );
		$name = $codec->getNamespaceName( $title );

		$this->assertEquals( $expected, $name );
	}
}
