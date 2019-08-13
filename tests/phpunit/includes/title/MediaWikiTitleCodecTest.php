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

use MediaWiki\Interwiki\InterwikiLookup;
use Wikimedia\TestingAccessWrapper;

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

	/**
	 * Returns a mock InterwikiLookup that only has an isValidInterwiki() method, which recognizes
	 * 'localtestiw' and 'remotetestiw'. All other methods throw.
	 *
	 * @return InterwikiLookup
	 */
	private function getInterwikiLookup() : InterwikiLookup {
		$iwLookup = $this->createMock( InterwikiLookup::class );

		$iwLookup->expects( $this->any() )
			->method( 'isValidInterwiki' )
			->will( $this->returnCallback( function ( $prefix ) {
				return $prefix === 'localtestiw' || $prefix === 'remotetestiw';
			} ) );

		$iwLookup->expects( $this->never() )
			->method( $this->callback( function ( $name ) {
				return $name !== 'isValidInterwiki';
			} ) );

		return $iwLookup;
	}

	/**
	 * Returns a mock NamespaceInfo that has only the following methods:
	 *
	 *  * exists()
	 *  * getCanonicalName()
	 *  * hasGenderDistinction()
	 *  * isCapitalized()
	 *
	 * All other methods throw. The only namespaces that exist are NS_SPECIAL, NS_MAIN, NS_TALK,
	 * NS_USER, and NS_USER_TALK. NS_USER and NS_USER_TALK have gender distinctions. All namespaces
	 * are capitalized.
	 *
	 * @return NamespaceInfo
	 */
	private function getNamespaceInfo() : NamespaceInfo {
		$canonicalNamespaces = [
			NS_SPECIAL => 'Special',
			NS_MAIN => '',
			NS_TALK => 'Talk',
			NS_USER => 'User',
			NS_USER_TALK => 'User_talk',
		];

		$nsInfo = $this->createMock( NamespaceInfo::class );

		$nsInfo->method( 'exists' )
			->will( $this->returnCallback( function ( $ns ) use ( $canonicalNamespaces ) {
				return isset( $canonicalNamespaces[$ns] );
			} ) );

		$nsInfo->method( 'getCanonicalName' )
			->will( $this->returnCallback( function ( $ns ) use ( $canonicalNamespaces ) {
				return $canonicalNamespaces[$ns] ?? false;
			} ) );

		$nsInfo->method( 'hasGenderDistinction' )
			->will( $this->returnCallback( function ( $ns ) {
				return $ns === NS_USER || $ns === NS_USER_TALK;
			} ) );

		$nsInfo->method( 'isCapitalized' )->willReturn( true );

		$nsInfo->expects( $this->never() )->method( $this->anythingBut(
			'exists', 'getCanonicalName', 'hasGenderDistinction', 'isCapitalized'
		) );

		return $nsInfo;
	}

	protected function makeCodec( $lang ) {
		return new MediaWikiTitleCodec(
			Language::factory( $lang ),
			$this->getGenderCache(),
			[ 'localtestiw' ],
			$this->getInterwikiLookup(),
			$this->getNamespaceInfo()
		);
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
			[ 1000000, 'Invalid_namespace', '', 'en', 'Special:Badtitle/NS1000000:Invalid namespace' ],
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
			[ 10000000, 'Foobar', '', '', 'en', 'Special:Badtitle/NS10000000:Foobar' ],
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
				'X' . str_repeat( 'x', 251 ) ],
			// Test decoding and normalization
			[ '&quot;n&#x303;&#34;', NS_MAIN, 'en', new TitleValue( NS_MAIN, '"ñ"' ) ],
			[ 'X#n&#x303;', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', 'ñ' ) ],
			// target section parsing
			'empty fragment' => [ 'X#', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X' ) ],
			'double hash' => [ 'X##', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', '#' ) ],
			'fragment with hash' => [ 'X#z#z', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', 'z#z' ) ],
			'fragment with space' => [ 'X#z z', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', 'z z' ) ],
			'fragment with percent' => [ 'X#z%z', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', 'z%z' ) ],
			'fragment with amp' => [ 'X#z&z', NS_MAIN, 'en', new TitleValue( NS_MAIN, 'X', 'z&z' ) ],
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

	/**
	 * @dataProvider provideMakeTitleValueSafe
	 * @covers IP::sanitizeIP
	 */
	public function testMakeTitleValueSafe(
		$expected, $ns, $text, $fragment = '', $interwiki = '', $lang = 'en'
	) {
		$codec = $this->makeCodec( $lang );
		$this->assertEquals( $expected,
			$codec->makeTitleValueSafe( $ns, $text, $fragment, $interwiki ) );
	}

	/**
	 * @dataProvider provideMakeTitleValueSafe
	 * @covers Title::makeTitleSafe
	 * @covers Title::makeName
	 * @covers Title::secureAndSplit
	 * @covers IP::sanitizeIP
	 */
	public function testMakeTitleSafe(
		$expected, $ns, $text, $fragment = '', $interwiki = '', $lang = 'en'
	) {
		$codec = $this->makeCodec( $lang );
		$this->setService( 'TitleParser', $codec );
		$this->setService( 'TitleFormatter', $codec );

		$actual = Title::makeTitleSafe( $ns, $text, $fragment, $interwiki );
		// We need to reset some members so equality testing works
		if ( $actual ) {
			$wrapper = TestingAccessWrapper::newFromObject( $actual );
			$wrapper->mArticleID = $actual->getNamespace() === NS_SPECIAL ? 0 : -1;
			$wrapper->mLocalInterwiki = false;
			$wrapper->mUserCaseDBKey = null;
		}
		$this->assertEquals( Title::castFromLinkTarget( $expected ), $actual );
	}

	public static function provideMakeTitleValueSafe() {
		$ret = [
			'Nonexistent NS' => [ null, 942929, 'Test' ],
			'Simple page' => [ new TitleValue( NS_MAIN, 'Test' ), NS_MAIN, 'Test' ],

			// Fragments
			'Passed fragment' => [
				new TitleValue( NS_MAIN, 'Test', 'Fragment' ),
				NS_MAIN, 'Test', 'Fragment'
			],
			'Embedded fragment' => [
				new TitleValue( NS_MAIN, 'Test', 'Fragment' ),
				NS_MAIN, 'Test#Fragment'
			],
			'Passed fragment with spaces' => [
				// XXX Leading space is okay in fragment?
				new TitleValue( NS_MAIN, 'Test', ' Frag ment' ),
				NS_MAIN, ' Test ', " Frag_ment "
			],
			'Embedded fragment with spaces' => [
				// XXX Leading space is okay in fragment?
				new TitleValue( NS_MAIN, 'Test', ' Frag ment' ),
				NS_MAIN, " Test # Frag_ment "
			],
			// XXX Is it correct that these aren't normalized to spaces?
			'Passed fragment with leading tab' => [ null, NS_MAIN, "\tTest\t", "\tFragment" ],
			'Embedded fragment with leading tab' => [ null, NS_MAIN, "\tTest\t#\tFragment" ],
			'Passed fragment with trailing tab' => [ null, NS_MAIN, "\tTest\t", "Fragment\t" ],
			'Embedded fragment with trailing tab' => [ null, NS_MAIN, "\tTest\t#Fragment\t" ],
			'Passed fragment with interior tab' => [ null, NS_MAIN, "\tTest\t", "Frag\tment" ],
			'Embedded fragment with interior tab' => [ null, NS_MAIN, "\tTest\t#\tFrag\tment" ],

			// Interwikis
			'Passed local interwiki' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, 'Test', '', 'localtestiw'
			],
			'Embedded local interwiki' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, 'localtestiw:Test'
			],
			'Passed remote interwiki' => [
				new TitleValue( NS_MAIN, 'Test', '', 'remotetestiw' ),
				NS_MAIN, 'Test', '', 'remotetestiw'
			],
			'Embedded remote interwiki' => [
				new TitleValue( NS_MAIN, 'Test', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw:Test'
			],
			// XXX Are these correct? Interwiki prefixes are case-sensitive?
			'Passed local interwiki with different case' => [
				new TitleValue( NS_MAIN, 'LocalTestIW:Test' ),
				NS_MAIN, 'Test', '', 'LocalTestIW'
			],
			'Embedded local interwiki with different case' => [
				new TitleValue( NS_MAIN, 'LocalTestIW:Test' ),
				NS_MAIN, 'LocalTestIW:Test'
			],
			'Passed remote interwiki with different case' => [
				new TitleValue( NS_MAIN, 'RemoteTestIW:Test' ),
				NS_MAIN, 'Test', '', 'RemoteTestIW'
			],
			'Embedded remote interwiki with different case' => [
				new TitleValue( NS_MAIN, 'RemoteTestIW:Test' ),
				NS_MAIN, 'RemoteTestIW:Test'
			],
			'Passed local interwiki with lowercase page name' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, 'test', '', 'localtestiw'
			],
			'Embedded local interwiki with lowercase page name' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, 'localtestiw:test'
			],
			// For remote we don't auto-capitalize
			'Passed remote interwiki with lowercase page name' => [
				new TitleValue( NS_MAIN, 'test', '', 'remotetestiw' ),
				NS_MAIN, 'test', '', 'remotetestiw'
			],
			'Embedded remote interwiki with lowercase page name' => [
				new TitleValue( NS_MAIN, 'test', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw:test'
			],

			// Fragment and interwiki
			'Fragment and local interwiki' => [
				new TitleValue( NS_MAIN, 'Test', 'Fragment' ),
				NS_MAIN, 'Test', 'Fragment', 'localtestiw'
			],
			'Fragment and remote interwiki' => [
				new TitleValue( NS_MAIN, 'Test', 'Fragment', 'remotetestiw' ),
				NS_MAIN, 'Test', 'Fragment', 'remotetestiw'
			],
			'Fragment and local interwiki and non-main namespace' => [
				new TitleValue( NS_TALK, 'Test', 'Fragment' ),
				NS_TALK, 'Test', 'Fragment', 'localtestiw'
			],
			// We don't know the foreign wiki's namespaces, so it will always be NS_MAIN
			'Fragment and remote interwiki and non-main namespace' => [
				new TitleValue( NS_MAIN, 'Talk:Test', 'Fragment', 'remotetestiw' ),
				NS_TALK, 'Test', 'Fragment', 'remotetestiw'
			],

			// Whitespace normalization and Unicode stripping
			'Name with space' => [
				new TitleValue( NS_MAIN, 'Test_test' ),
				NS_MAIN, 'Test test'
			],
			'Unicode bidi override characters' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, "\u{200E}T\u{200F}e\u{202A}s\u{202B}t\u{202C}\u{202D}\u{202E}"
			],
			'Invalid UTF-8 sequence' => [ null, NS_MAIN, "Te\x80\xf0st" ],
			'Whitespace collapsing' => [
				new TitleValue( NS_MAIN, 'Test_test' ),
				NS_MAIN, "Test _\u{00A0}\u{1680}\u{180E}\u{2000}\u{2001}\u{2002}\u{2003}\u{2004}" .
				"\u{2005}\u{2006}\u{2007}\u{2008}\u{2009}\u{200A}\u{2028}\u{2029}\u{202F}" .
				"\u{205F}\u{3000}test"
			],
			'UTF8_REPLACEMENT' => [ null, NS_MAIN, UtfNormal\Constants::UTF8_REPLACEMENT ],

			// Namespace prefixes
			'Talk:Test' => [
				new TitleValue( NS_TALK, 'Test' ),
				NS_MAIN, 'Talk:Test'
			],
			'Test in talk NS' => [
				new TitleValue( NS_TALK, 'Test' ),
				NS_TALK, 'Test'
			],
			'Talkk:Test' => [
				new TitleValue( NS_MAIN, 'Talkk:Test' ),
				NS_MAIN, 'Talkk:Test'
			],
			'Talk:Talk:Test' => [ null, NS_MAIN, 'Talk:Talk:Test' ],
			'Talk:User:Test' => [ null, NS_MAIN, 'Talk:User:Test' ],
			'User:Talk:Test' => [
				new TitleValue( NS_USER, 'Talk:Test' ),
				NS_MAIN, 'User:Talk:Test'
			],
			'User:Test in talk NS' => [ null, NS_TALK, 'User:Test' ],
			'Talk:Test in talk NS' => [ null, NS_TALK, 'Talk:Test' ],
			'User:Test in user NS' => [
				new TitleValue( NS_USER, 'User:Test' ),
				NS_USER, 'User:Test'
			],
			'Talk:Test in user NS' => [
				new TitleValue( NS_USER, 'Talk:Test' ),
				NS_USER, 'Talk:Test'
			],

			// Initial colon
			':Test' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, ':Test'
			],
			':Talk:Test' => [
				new TitleValue( NS_TALK, 'Test' ),
				NS_MAIN, ':Talk:Test'
			],
			':localtestiw:Test' => [
				new TitleValue( NS_MAIN, 'Test' ),
				NS_MAIN, ':localtestiw:Test'
			],
			':remotetestiw:Test' => [
				new TitleValue( NS_MAIN, 'Test', '', 'remotetestiw' ),
				NS_MAIN, ':remotetestiw:Test'
			],
			// XXX Is this correct? Why is it different from remote?
			'localtestiw::Test' => [ null, NS_MAIN, 'localtestiw::Test' ],
			'remotetestiw::Test' => [
				new TitleValue( NS_MAIN, 'Test', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw::Test'
			],
			// XXX Is this correct? Why is it different from remote?
			'localtestiw:: Test' => [ null, NS_MAIN, 'localtestiw:: Test' ],
			'remotetestiw:: Test' => [
				new TitleValue( NS_MAIN, 'Test', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw:: Test'
			],

			// Empty titles
			'Empty title' => [ null, NS_MAIN, '' ],
			'Empty title with namespace' => [ null, NS_USER, '' ],
			'Local interwiki with empty page name' => [
				new TitleValue( NS_MAIN, 'Main_Page' ),
				NS_MAIN, 'localtestiw:'
			],
			'Remote interwiki with empty page name' => [
				// XXX Is this correct? This is supposed to redirect to the main page remotely?
				new TitleValue( NS_MAIN, '', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw:'
			],

			// Whitespace-only titles
			'Whitespace-only title' => [ null, NS_MAIN, "\t\n" ],
			'Whitespace-only title with namespace' => [ null, NS_USER, " _ " ],
			'Local interwiki with whitespace-only page name' => [
				// XXX Is whitespace-only really supposed to be different from empty?
				null,
				NS_MAIN, "localtestiw:_\t"
			],
			'Remote interwiki with whitespace-only page name' => [
				// XXX Is whitespace-only really supposed to be different from empty?
				null,
				NS_MAIN, "remotetestiw:\t_\n\r"
			],

			// Namespace and interwiki
			'Talk:localtestiw:Test' => [ null, NS_MAIN, 'Talk:localtestiw:Test' ],
			'Talk:remotetestiw:Test' => [ null, NS_MAIN, 'Talk:remotetestiw:Test' ],
			'User:localtestiw:Test' => [
				new TitleValue( NS_USER, 'Localtestiw:Test' ),
				NS_MAIN, 'User:localtestiw:Test'
			],
			'User:remotetestiw:Test' => [
				new TitleValue( NS_USER, 'Remotetestiw:Test' ),
				NS_MAIN, 'User:remotetestiw:Test'
			],
			'localtestiw:Test in user namespace' => [
				new TitleValue( NS_USER, 'Localtestiw:Test' ),
				NS_USER, 'localtestiw:Test'
			],
			'remotetestiw:Test in user namespace' => [
				new TitleValue( NS_USER, 'Remotetestiw:Test' ),
				NS_USER, 'remotetestiw:Test'
			],
			'localtestiw:talk:test' => [
				new TitleValue( NS_TALK, 'Test' ),
				NS_MAIN, 'localtestiw:talk:test'
			],
			'remotetestiw:talk:test' => [
				new TitleValue( NS_MAIN, 'talk:test', '', 'remotetestiw' ),
				NS_MAIN, 'remotetestiw:talk:test'
			],

			// Invalid chars
			'Test[test' => [ null, NS_MAIN, 'Test[test' ],

			// Long titles
			'255 chars long' => [
				new TitleValue( NS_MAIN, str_repeat( 'A', 255 ) ),
				NS_MAIN, str_repeat( 'A', 255 )
			],
			'255 chars long in user NS' => [
				new TitleValue( NS_USER, str_repeat( 'A', 255 ) ),
				NS_USER, str_repeat( 'A', 255 )
			],
			'User:255 chars long' => [
				new TitleValue( NS_USER, str_repeat( 'A', 255 ) ),
				NS_MAIN, 'User:' . str_repeat( 'A', 255 )
			],
			'256 chars long' => [ null, NS_MAIN, str_repeat( 'A', 256 ) ],
			'256 chars long in user NS' => [ null, NS_USER, str_repeat( 'A', 256 ) ],
			'User:256 chars long' => [ null, NS_MAIN, 'User:' . str_repeat( 'A', 256 ) ],

			'512 chars long in special NS' => [
				new TitleValue( NS_SPECIAL, str_repeat( 'A', 512 ) ),
				NS_SPECIAL, str_repeat( 'A', 512 )
			],
			'Special:512 chars long' => [
				new TitleValue( NS_SPECIAL, str_repeat( 'A', 512 ) ),
				NS_MAIN, 'Special:' . str_repeat( 'A', 512 )
			],
			'513 chars long in special NS' => [ null, NS_SPECIAL, str_repeat( 'A', 513 ) ],
			'Special:513 chars long' => [ null, NS_MAIN, 'Special:' . str_repeat( 'A', 513 ) ],

			// IP addresses
			'User:000.000.000' => [
				new TitleValue( NS_USER, '000.000.000' ),
				NS_MAIN, 'User:000.000.000'
			],
			'User:000.000.000.000' => [
				new TitleValue( NS_USER, '0.0.0.0' ),
				NS_MAIN, 'User:000.000.000.000'
			],
			'000.000.000.000' => [
				new TitleValue( NS_MAIN, '000.000.000.000' ),
				NS_MAIN, '000.000.000.000'
			],
			'User:1.1.256.000' => [
				new TitleValue( NS_USER, '1.1.256.000' ),
				NS_MAIN, 'User:1.1.256.000'
			],
			'User:1.1.255.000' => [
				new TitleValue( NS_USER, '1.1.255.0' ),
				NS_MAIN, 'User:1.1.255.000'
			],
			// TODO More IP address sanitization tests
		];

		// Invalid and valid dots
		foreach ( [ '.', '..', '...' ] as $dots ) {
			foreach ( [ '?', '?/', '?/Test', 'Test/?/Test', '/?', 'Test/?', '?Test', 'Test?Test',
			'Test?' ] as $pattern ) {
				$test = str_replace( '?', $dots, $pattern );
				if ( $dots === '...' || in_array( $pattern, [ '?Test', 'Test?Test', 'Test?' ] ) ) {
					$expectedMain = new TitleValue( NS_MAIN, $test );
					$expectedUser = new TitleValue( NS_USER, $test );
				} else {
					$expectedMain = $expectedUser = null;
				}
				$ret[$test] = [ $expectedMain, NS_MAIN, $test ];
				$ret["$test in user NS"] = [ $expectedUser, NS_USER, $test ];
				$ret["User:$test"] = [ $expectedUser, NS_MAIN, "User:$test" ];
			}
		}

		// Invalid and valid tildes
		foreach ( [ '~~', '~~~' ] as $tildes ) {
			foreach ( [ '?', 'Test?', '?Test', 'Test?Test' ] as $pattern ) {
				$test = str_replace( '?', $tildes, $pattern );
				if ( $tildes === '~~' ) {
					$expectedMain = new TitleValue( NS_MAIN, $test );
					$expectedUser = new TitleValue( NS_USER, $test );
				} else {
					$expectedMain = $expectedUser = null;
				}
				$ret[$test] = [ $expectedMain, NS_MAIN, $test ];
				$ret["$test in user NS"] = [ $expectedUser, NS_USER, $test ];
				$ret["User:$test"] = [ $expectedUser, NS_MAIN, "User:$test" ];
			}
		}

		return $ret;
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
