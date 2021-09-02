<?php

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @group Title
 */
class TitleTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
			'wgServer' => 'https://example.org',
			'wgCanonicalServer' => 'https://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgArticlePath' => '/wiki/$1',
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );

		// For testSecureAndSplitValid, testSecureAndSplitInvalid
		$this->setMwGlobals( 'wgLocalInterwikis', [ 'localtestiw' ] );

		// Define valid interwiki prefixes and their configuration
		// DummyServicesTrait::getDummyInterwikiLookup
		$interwikiLookup = $this->getDummyInterwikiLookup( [
			// testSecureAndSplitValid, testSecureAndSplitInvalid
			[ 'iw_prefix' => 'localtestiw', 'iw_url' => 'localtestiw' ],
			[ 'iw_prefix' => 'remotetestiw', 'iw_url' => 'remotetestiw' ],

			// testSubpages
			'wiki',

			// testIsValid
			'wikipedia',

			// testIsValidRedirectTarget
			'acme',

			// testGetFragmentForURL
			[ 'iw_prefix' => 'de', 'iw_local' => 1 ],
			[ 'iw_prefix' => 'zz', 'iw_local' => 0 ],
		] );
		$this->setService( 'InterwikiLookup', $interwikiLookup );
	}

	protected function tearDown(): void {
		parent::tearDown();
		// delete dummy pages
		$this->getNonexistingTestPage( 'UTest1' );
		$this->getNonexistingTestPage( 'UTest2' );
	}

	/**
	 * @covers Title::newFromID
	 * @covers Title::newFromIDs
	 * @covers Title::newFromRow
	 */
	public function testNewFromIds() {
		// First id
		$existingPage1 = $this->getExistingTestPage( 'UTest1' );
		$existingTitle1 = $existingPage1->getTitle();
		$existingId1 = $existingTitle1->getId();

		$this->assertGreaterThan( 0, $existingId1, 'Sanity: Existing test page should have a positive id' );

		$newFromId1 = Title::newFromID( $existingId1 );
		$this->assertInstanceOf( Title::class, $newFromId1, 'newFromID returns a title for an existing id' );
		$this->assertTrue(
			$newFromId1->equals( $existingTitle1 ),
			'newFromID returns the correct title'
		);

		// Second id
		$existingPage2 = $this->getExistingTestPage( 'UTest2' );
		$existingTitle2 = $existingPage2->getTitle();
		$existingId2 = $existingTitle2->getId();

		$this->assertGreaterThan( 0, $existingId2, 'Sanity: Existing test page should have a positive id' );

		$newFromId2 = Title::newFromID( $existingId2 );
		$this->assertInstanceOf( Title::class, $newFromId2, 'newFromID returns a title for an existing id' );
		$this->assertTrue(
			$newFromId2->equals( $existingTitle2 ),
			'newFromID returns the correct title'
		);

		// newFromIDs using both
		$titles = Title::newFromIDs( [ $existingId1, $existingId2 ] );
		$this->assertCount( 2, $titles );
		$this->assertTrue(
			$titles[0]->equals( $existingTitle1 ) &&
				$titles[1]->equals( $existingTitle2 ),
			'newFromIDs returns an array that matches the correct titles'
		);

		// newFromIds early return for an empty array of ids
		$this->assertSame( [], Title::newFromIDs( [] ) );
	}

	/**
	 * @covers Title::newFromID
	 */
	public function testNewFromMissingId() {
		// Testing return of null for an id that does not exist
		$maxPageId = (int)$this->db->selectField(
			'page',
			'max(page_id)',
			'',
			__METHOD__
		);
		$res = Title::newFromId( $maxPageId + 1 );
		$this->assertNull( $res, 'newFromID returns null for missing ids' );
	}

	public static function provideValidSecureAndSplit() {
		return [
			[ 'Sandbox' ],
			[ 'A "B"' ],
			[ 'A \'B\'' ],
			[ '.com' ],
			[ '~' ],
			[ '#' ],
			[ '"' ],
			[ '\'' ],
			[ 'Talk:Sandbox' ],
			[ 'Talk:Foo:Sandbox' ],
			[ 'File:Example.svg' ],
			[ 'File_talk:Example.svg' ],
			[ 'Foo/.../Sandbox' ],
			[ 'Sandbox/...' ],
			[ 'A~~' ],
			[ ':A' ],
			// Length is 256 total, but only title part matters
			[ 'Category:' . str_repeat( 'x', 248 ) ],
			[ str_repeat( 'x', 252 ) ],
			// interwiki prefix
			[ 'localtestiw: #anchor' ],
			[ 'localtestiw:' ],
			[ 'localtestiw:foo' ],
			[ 'localtestiw: foo # anchor' ],
			[ 'localtestiw: Talk: Sandbox # anchor' ],
			[ 'remotetestiw:' ],
			[ 'remotetestiw: Talk: # anchor' ],
			[ 'remotetestiw: #bar' ],
			[ 'remotetestiw: Talk:' ],
			[ 'remotetestiw: Talk: Foo' ],
			[ 'localtestiw:remotetestiw:' ],
			[ 'localtestiw:remotetestiw:foo' ]
		];
	}

	public static function provideInvalidSecureAndSplit() {
		return [
			[ '', 'title-invalid-empty' ],
			[ ':', 'title-invalid-empty' ],
			[ '__  __', 'title-invalid-empty' ],
			[ '  __  ', 'title-invalid-empty' ],
			// Bad characters forbidden regardless of wgLegalTitleChars
			[ 'A [ B', 'title-invalid-characters' ],
			[ 'A ] B', 'title-invalid-characters' ],
			[ 'A { B', 'title-invalid-characters' ],
			[ 'A } B', 'title-invalid-characters' ],
			[ 'A < B', 'title-invalid-characters' ],
			[ 'A > B', 'title-invalid-characters' ],
			[ 'A | B', 'title-invalid-characters' ],
			[ "A \t B", 'title-invalid-characters' ],
			[ "A \n B", 'title-invalid-characters' ],
			// URL encoding
			[ 'A%20B', 'title-invalid-characters' ],
			[ 'A%23B', 'title-invalid-characters' ],
			[ 'A%2523B', 'title-invalid-characters' ],
			// XML/HTML character entity references
			// Note: Commented out because they are not marked invalid by the PHP test as
			// Title::newFromText runs Sanitizer::decodeCharReferencesAndNormalize first.
			// 'A &eacute; B',
			// 'A &#233; B',
			// 'A &#x00E9; B',
			// Subject of NS_TALK does not roundtrip to NS_MAIN
			[ 'Talk:File:Example.svg', 'title-invalid-talk-namespace' ],
			// Directory navigation
			[ '.', 'title-invalid-relative' ],
			[ '..', 'title-invalid-relative' ],
			[ './Sandbox', 'title-invalid-relative' ],
			[ '../Sandbox', 'title-invalid-relative' ],
			[ 'Foo/./Sandbox', 'title-invalid-relative' ],
			[ 'Foo/../Sandbox', 'title-invalid-relative' ],
			[ 'Sandbox/.', 'title-invalid-relative' ],
			[ 'Sandbox/..', 'title-invalid-relative' ],
			// Tilde
			[ 'A ~~~ Name', 'title-invalid-magic-tilde' ],
			[ 'A ~~~~ Signature', 'title-invalid-magic-tilde' ],
			[ 'A ~~~~~ Timestamp', 'title-invalid-magic-tilde' ],
			// Length
			[ str_repeat( 'x', 256 ), 'title-invalid-too-long' ],
			// Namespace prefix without actual title
			[ 'Talk:', 'title-invalid-empty' ],
			[ 'Talk:#', 'title-invalid-empty' ],
			[ 'Category: ', 'title-invalid-empty' ],
			[ 'Category: #bar', 'title-invalid-empty' ],
			// interwiki prefix
			[ 'localtestiw: Talk: # anchor', 'title-invalid-empty' ],
			[ 'localtestiw: Talk:', 'title-invalid-empty' ]
		];
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers Title::secureAndSplit
	 * @dataProvider provideValidSecureAndSplit
	 * @note This mainly tests MediaWikiTitleCodec::parseTitle().
	 */
	public function testSecureAndSplitValid( $text ) {
		$this->assertInstanceOf( Title::class, Title::newFromText( $text ), "Valid: $text" );
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers Title::secureAndSplit
	 * @dataProvider provideInvalidSecureAndSplit
	 * @note This mainly tests MediaWikiTitleCodec::parseTitle().
	 */
	public function testSecureAndSplitInvalid( $text, $expectedErrorMessage ) {
		try {
			Title::newFromTextThrow( $text ); // should throw
			$this->fail( "Title::newFromTextThrow should have thrown with $text" );
		} catch ( MalformedTitleException $ex ) {
			$this->assertEquals( $expectedErrorMessage, $ex->getErrorMessage(), "Invalid: $text" );
		}
	}

	/**
	 * @dataProvider provideSpecialNamesWithAndWithoutParameter
	 * @covers Title::fixSpecialName
	 */
	public function testFixSpecialNameRetainsParameter( $text, $expectedParam ) {
		$title = Title::newFromText( $text );
		$fixed = $title->fixSpecialName();
		$stuff = explode( '/', $fixed->getDBkey(), 2 );
		if ( count( $stuff ) == 2 ) {
			$par = $stuff[1];
		} else {
			$par = null;
		}
		$this->assertEquals(
			$expectedParam,
			$par,
			"T33100 regression check: Title->fixSpecialName() should preserve parameter"
		);
	}

	public static function provideSpecialNamesWithAndWithoutParameter() {
		return [
			[ 'Special:Version', null ],
			[ 'Special:Version/', '' ],
			[ 'Special:Version/param', 'param' ],
		];
	}

	public function flattenErrorsArray( $errors ) {
		$result = [];
		foreach ( $errors as $error ) {
			$result[] = $error[0];
		}

		return $result;
	}

	/**
	 * @dataProvider provideGetPageViewLanguage
	 * @covers Title::getPageViewLanguage
	 */
	public function testGetPageViewLanguage( $expected, $titleText, $contLang,
		$lang, $variant, $msg = ''
	) {
		// Setup environnement for this test
		$this->setMwGlobals( [
			'wgDefaultLanguageVariant' => $variant,
			'wgAllowUserJs' => true,
		] );
		$this->setUserLang( $lang );
		$this->setContentLang( $contLang );

		$title = Title::newFromText( $titleText );
		$this->assertInstanceOf( Title::class, $title,
			"Test must be passed a valid title text, you gave '$titleText'"
		);
		$this->assertEquals( $expected,
			$title->getPageViewLanguage()->getCode(),
			$msg
		);
	}

	public static function provideGetPageViewLanguage() {
		# Format:
		# - expected
		# - Title name
		# - content language (expected in most cases)
		# - wgLang (on some specific pages)
		# - wgDefaultLanguageVariant
		return [
			[ 'fr', 'Help:I_need_somebody', 'fr', 'fr', false ],
			[ 'es', 'Help:I_need_somebody', 'es', 'zh-tw', false ],
			[ 'zh', 'Help:I_need_somebody', 'zh', 'zh-tw', false ],

			[ 'es', 'Help:I_need_somebody', 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', 'MediaWiki:About', 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', 'MediaWiki:About/', 'es', 'zh-tw', 'zh-cn' ],
			[ 'de', 'MediaWiki:About/de', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.js', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.css', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Common.js', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Monobook.css', 'es', 'zh-tw', 'zh-cn' ],

			[ 'zh-cn', 'Help:I_need_somebody', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', 'MediaWiki:About', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', 'MediaWiki:About/', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'de', 'MediaWiki:About/de', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-cn', 'MediaWiki:About/zh-cn', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', 'MediaWiki:About/zh-tw', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.js', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.css', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Common.js', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Monobook.css', 'zh', 'zh-tw', 'zh-cn' ],

			[ 'zh-tw', 'Special:NewPages', 'es', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', 'Special:NewPages', 'zh', 'zh-tw', 'zh-cn' ],

		];
	}

	/**
	 * @dataProvider provideBaseTitleCases
	 * @covers Title::getBaseText
	 */
	public function testGetBaseText( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->assertSame( $expected, $title->getBaseText() );
	}

	/**
	 * @dataProvider provideBaseTitleCases
	 * @covers Title::getBaseTitle
	 */
	public function testGetBaseTitle( $title, $expected ) {
		$title = Title::newFromText( $title );
		$base = $title->getBaseTitle();
		$this->assertTrue( $base->isValid() );
		$this->assertTrue(
			$base->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) )
		);
	}

	public static function provideBaseTitleCases() {
		return [
			# Title, expected base
			[ 'User:John_Doe', 'John Doe' ],
			[ 'User:John_Doe/subOne/subTwo', 'John Doe/subOne' ],
			[ 'User:Foo / Bar / Baz', 'Foo / Bar ' ],
			[ 'User:Foo/', 'Foo' ],
			[ 'User:Foo/Bar/', 'Foo/Bar' ],
			[ 'User:/', '/' ],
			[ 'User://', '/' ],
			[ 'User:/oops/', '/oops' ],
			[ 'User:/indeed', '/indeed' ],
			[ 'User://indeed', '/' ],
			[ 'User:/Ramba/Zamba/Mamba/', '/Ramba/Zamba/Mamba' ],
			[ 'User://x//y//z//', '//x//y//z/' ],
		];
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers Title::getRootText
	 */
	public function testGetRootText( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expected, $title->getRootText() );
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers Title::getRootTitle
	 */
	public function testGetRootTitle( $title, $expected ) {
		$title = Title::newFromText( $title );
		$root = $title->getRootTitle();
		$this->assertTrue( $root->isValid() );
		$this->assertTrue(
			$root->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) )
		);
	}

	public static function provideRootTitleCases() {
		return [
			# Title, expected base
			[ 'User:John_Doe', 'John Doe' ],
			[ 'User:John_Doe/subOne/subTwo', 'John Doe' ],
			[ 'User:Foo / Bar / Baz', 'Foo ' ],
			[ 'User:Foo/', 'Foo' ],
			[ 'User:Foo/Bar/', 'Foo' ],
			[ 'User:/', '/' ],
			[ 'User://', '/' ],
			[ 'User:/oops/', '/oops' ],
			[ 'User:/Ramba/Zamba/Mamba/', '/Ramba' ],
			[ 'User://x//y//z//', '//x' ],
			[ 'Talk:////', '///' ],
			[ 'Template:////', '///' ],
			[ 'Template:Foo////', 'Foo' ],
			[ 'Template:Foo////Bar', 'Foo' ],
		];
	}

	/**
	 * @todo Handle $wgNamespacesWithSubpages cases
	 * @dataProvider provideSubpageTitleCases
	 * @covers Title::getSubpageText
	 */
	public function testGetSubpageText( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expected, $title->getSubpageText() );
	}

	public static function provideSubpageTitleCases() {
		return [
			# Title, expected base
			[ 'User:John_Doe', 'John Doe' ],
			[ 'User:John_Doe/subOne/subTwo', 'subTwo' ],
			[ 'User:John_Doe/subOne', 'subOne' ],
			[ 'User:/', '/' ],
			[ 'User://', '' ],
			[ 'User:/oops/', '' ],
			[ 'User:/indeed', '/indeed' ],
			[ 'User://indeed', 'indeed' ],
			[ 'User:/Ramba/Zamba/Mamba/', '' ],
			[ 'User://x//y//z//', '' ],
			[ 'Template:Foo', 'Foo' ]
		];
	}

	public function provideSubpage() {
		// NOTE: avoid constructing Title objects in the provider, since it may access the database.
		return [
			[ 'Foo', 'x', new TitleValue( NS_MAIN, 'Foo/x' ) ],
			[ 'Foo#bar', 'x', new TitleValue( NS_MAIN, 'Foo/x' ) ],
			[ 'User:Foo', 'x', new TitleValue( NS_USER, 'Foo/x' ) ],
			[ 'wiki:User:Foo', 'x', new TitleValue( NS_MAIN, 'User:Foo/x', '', 'wiki' ) ],
		];
	}

	/**
	 * @dataProvider provideSubpage
	 * @covers Title::getSubpage
	 */
	public function testSubpage( $title, $sub, LinkTarget $expected ) {
		$title = Title::newFromText( $title );
		$expected = Title::newFromLinkTarget( $expected );
		$actual = $title->getSubpage( $sub );

		// NOTE: convert to string for comparison
		$this->assertSame( $expected->getPrefixedText(), $actual->getPrefixedText(), 'text form' );
		$this->assertTrue( $expected->equals( $actual ), 'Title equality' );
	}

	public function provideCastFromPageIdentity() {
		yield [ null ];

		$fake = $this->createMock( PageIdentity::class );
		$fake->method( 'getId' )->willReturn( 7 );
		$fake->method( 'getNamespace' )->willReturn( NS_MAIN );
		$fake->method( 'getDBkey' )->willReturn( 'Test' );

		yield [ $fake ];

		$fake = $this->createMock( Title::class );
		$fake->method( 'getId' )->willReturn( 7 );
		$fake->method( 'getNamespace' )->willReturn( NS_MAIN );
		$fake->method( 'getDBkey' )->willReturn( 'Test' );

		yield [ $fake ];
	}

	/**
	 * @covers Title::castFromPageIdentity
	 * @dataProvider provideCastFromPageIdentity
	 */
	public function testCastFromPageIdentity( ?PageIdentity $value ) {
		$title = Title::castFromPageIdentity( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} elseif ( $value instanceof Title ) {
			$this->assertSame( $value, $title );
		} else {
			$this->assertSame( $value->getId(), $title->getArticleID() );
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getDBkey(), $title->getDBkey() );
		}
	}

	public function provideCastFromPageReference() {
		yield [ new PageReferenceValue( NS_MAIN, 'Test', PageReference::LOCAL ) ];
	}

	/**
	 * @covers Title::castFromPageReference
	 * @dataProvider provideCastFromPageIdentity
	 * @dataProvider provideCastFromPageReference
	 */
	public function testCastFromPageReference( ?PageReference $value ) {
		$title = Title::castFromPageReference( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} elseif ( $value instanceof Title ) {
			$this->assertSame( $value, $title );
		} else {
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getDBkey(), $title->getDBkey() );
		}
	}

	public static function provideGetTitleValue() {
		return [
			[ 'Foo' ],
			[ 'Foo#bar' ],
			[ 'User:Hansi_Maier' ],
		];
	}

	/**
	 * @covers Title::getTitleValue
	 * @dataProvider provideGetTitleValue
	 */
	public function testGetTitleValue( $text ) {
		$title = Title::newFromText( $text );
		$value = $title->getTitleValue();

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $title->getDBkey(), $dbkey );
		$this->assertEquals( $title->getNamespace(), $value->getNamespace() );
		$this->assertEquals( $title->getFragment(), $value->getFragment() );
	}

	public static function provideGetFragment() {
		return [
			[ 'Foo', '' ],
			[ 'Foo#bar', 'bar' ],
			[ 'Foo#bär', 'bär' ],

			// Inner whitespace is normalized
			[ 'Foo#bar_bar', 'bar bar' ],
			[ 'Foo#bar bar', 'bar bar' ],
			[ 'Foo#bar   bar', 'bar bar' ],

			// Leading whitespace is kept, trailing whitespace is trimmed.
			// XXX: Is this really want we want?
			[ 'Foo#_bar_bar_', ' bar bar' ],
			[ 'Foo# bar bar ', ' bar bar' ],
		];
	}

	/**
	 * @covers Title::getFragment
	 * @dataProvider provideGetFragment
	 *
	 * @param string $full
	 * @param string $fragment
	 */
	public function testGetFragment( $full, $fragment ) {
		$title = Title::newFromText( $full );
		$this->assertEquals( $fragment, $title->getFragment() );
	}

	/**
	 * @covers Title::isAlwaysKnown
	 * @dataProvider provideIsAlwaysKnown
	 * @param string $page
	 * @param bool $isKnown
	 */
	public function testIsAlwaysKnown( $page, $isKnown ) {
		$title = Title::newFromText( $page );
		$this->assertEquals( $isKnown, $title->isAlwaysKnown() );
	}

	public static function provideIsAlwaysKnown() {
		return [
			[ 'Some nonexistent page', false ],
			[ 'UTPage', false ],
			[ '#test', true ],
			[ 'Special:BlankPage', true ],
			[ 'Special:SomeNonexistentSpecialPage', false ],
			[ 'MediaWiki:Parentheses', true ],
			[ 'MediaWiki:Some nonexistent message', false ],
		];
	}

	/**
	 * @covers Title::isValid
	 * @dataProvider provideIsValid
	 * @param Title $title
	 * @param bool $isValid
	 */
	public function testIsValid( Title $title, $isValid ) {
		$this->assertEquals( $isValid, $title->isValid(), $title->getFullText() );
	}

	public static function provideIsValid() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '|' ), false ],
			[ Title::makeTitle( NS_MAIN, '#' ), false ],
			[ Title::makeTitle( NS_PROJECT, '#' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test' ), true ],
			[ Title::makeTitle( NS_PROJECT, '#test' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test', 'wikipedia' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'wikipedia' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), true ],
			[ Title::makeTitle( NS_SPECIAL, 'Test' ), true ],
			[ Title::makeTitle( NS_MAIN, ' Test' ), false ],
			[ Title::makeTitle( NS_MAIN, '_Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test ' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test_' ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\nthis" ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\tthis" ), false ],
			[ Title::makeTitle( -33, 'Test' ), false ],
			[ Title::makeTitle( 77663399, 'Test' ), false ],
		];
	}

	/**
	 * @covers Title::isValidRedirectTarget
	 * @dataProvider provideIsValidRedirectTarget
	 * @param Title $title
	 * @param bool $isValid
	 */
	public function testIsValidRedirectTarget( Title $title, $isValid ) {
		// InterwikiLookup is configured in setUp()
		$this->assertEquals( $isValid, $title->isValidRedirectTarget(), $title->getFullText() );
	}

	public static function provideIsValidRedirectTarget() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Foo', 'test' ), true ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '_' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'acme' ), true ],
			[ Title::makeTitle( NS_SPECIAL, 'UserLogout' ), false ],
			[ Title::makeTitle( NS_SPECIAL, 'RecentChanges' ), true ],
		];
	}

	/**
	 * @covers Title::canExist
	 * @dataProvider provideCanExist
	 * @param Title $title
	 * @param bool $canExist
	 */
	public function testCanExist( Title $title, $canExist ) {
		$this->assertEquals( $canExist, $title->canExist(), $title->getFullText() );
	}

	public static function provideCanExist() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '|' ), false ],
			[ Title::makeTitle( NS_MAIN, '#' ), false ],
			[ Title::makeTitle( NS_PROJECT, '#test' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test', 'acme' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), true ],
			[ Title::makeTitle( NS_MAIN, ' Test' ), false ],
			[ Title::makeTitle( NS_MAIN, '_Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test ' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test_' ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\nthis" ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\tthis" ), false ],
			[ Title::makeTitle( -33, 'Test' ), false ],
			[ Title::makeTitle( 77663399, 'Test' ), false ],

			// Valid but can't exist
			[ Title::makeTitle( NS_MAIN, '', 'test' ), false ],
			[ Title::makeTitle( NS_SPECIAL, 'Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'acme' ), false ],
		];
	}

	/**
	 * @covers Title::isAlwaysKnown
	 */
	public function testIsAlwaysKnownOnInterwiki() {
		$title = Title::makeTitle( NS_MAIN, 'Interwiki link', '', 'externalwiki' );
		$this->assertTrue( $title->isAlwaysKnown() );
	}

	/**
	 * @covers Title::exists
	 */
	public function testExists() {
		$title = Title::makeTitle( NS_PROJECT, 'New page' );
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		$article = new Article( $title );
		$page = $article->getPage();
		$page->doUserEditContent(
			new WikitextContent( 'Some [[link]]' ),
			$this->getTestSysop()->getUser(),
			'summary'
		);

		// Tell Title it doesn't know whether it exists
		$title->mArticleID = -1;

		// Tell the link cache it doesn't exist when it really does
		$linkCache->clearLink( $title );
		$linkCache->addBadLinkObj( $title );

		$this->assertFalse(
			$title->exists(),
			'exists() should rely on link cache unless READ_LATEST is used'
		);
		$this->assertTrue(
			$title->exists( Title::READ_LATEST ),
			'exists() should re-query database when READ_LATEST is used'
		);
	}

	/**
	 * @covers Title::getArticleID
	 * @covers Title::getId
	 */
	public function testGetArticleID() {
		$title = Title::makeTitle( NS_PROJECT, __METHOD__ );
		$this->assertSame( 0, $title->getArticleID() );
		$this->assertSame( $title->getArticleID(), $title->getId() );

		$article = new Article( $title );
		$page = $article->getPage();
		$page->doUserEditContent(
			new WikitextContent( 'Some [[link]]' ),
			$this->getTestSysop()->getUser(),
			'summary'
		);

		$this->assertGreaterThan( 0, $title->getArticleID() );
		$this->assertSame( $title->getArticleID(), $title->getId() );
	}

	public function provideCanHaveTalkPage() {
		return [
			'User page has talk page' => [
				Title::makeTitle( NS_USER, 'Jane' ), true
			],
			'Talke page has talk page' => [
				Title::makeTitle( NS_TALK, 'Foo' ), true
			],
			'Special page cannot have talk page' => [
				Title::makeTitle( NS_SPECIAL, 'Thing' ), false
			],
			'Virtual namespace cannot have talk page' => [
				Title::makeTitle( NS_MEDIA, 'Kitten.jpg' ), false
			],
			'Relative link has no talk page' => [
				Title::makeTitle( NS_MAIN, '', 'Kittens' ), false
			],
			'Interwiki link has no talk page' => [
				Title::makeTitle( NS_MAIN, 'Kittens', '', 'acme' ), false
			],
		];
	}

	public function provideIsWatchable() {
		return [
			'User page is watchable' => [
				Title::makeTitle( NS_USER, 'Jane' ), true
			],
			'Talk page is watchable' => [
				Title::makeTitle( NS_TALK, 'Foo' ), true
			],
			'Special page is not watchable' => [
				Title::makeTitle( NS_SPECIAL, 'Thing' ), false
			],
			'Virtual namespace is not watchable' => [
				Title::makeTitle( NS_MEDIA, 'Kitten.jpg' ), false
			],
			'Relative link is not watchable' => [
				Title::makeTitle( NS_MAIN, '', 'Kittens' ), false
			],
			'Interwiki link is not watchable' => [
				Title::makeTitle( NS_MAIN, 'Kittens', '', 'acme' ), false
			],
			'Invalid title is not watchable' => [
				Title::makeTitle( NS_MAIN, '<' ), false
			]
		];
	}

	public static function provideGetTalkPage_good() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
		];
	}

	public static function provideGetTalkPage_bad() {
		return [
			[ Title::makeTitle( NS_SPECIAL, 'Test' ) ],
			[ Title::makeTitle( NS_MEDIA, 'Test' ) ],
		];
	}

	public static function provideGetTalkPage_broken() {
		// These cases *should* be bad, but are not treated as bad, for backwards compatibility.
		// See discussion on T227817.
		return [
			[
				Title::makeTitle( NS_MAIN, '', 'Kittens' ),
				Title::makeTitle( NS_TALK, '' ), // Section is lost!
				false,
			],
			[
				Title::makeTitle( NS_MAIN, 'Kittens', '', 'acme' ),
				Title::makeTitle( NS_TALK, 'Kittens', '' ), // Interwiki prefix is lost!
				true,
			],
		];
	}

	public static function provideGetSubjectPage_good() {
		return [
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
		];
	}

	public static function provideGetOtherPage_good() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
		];
	}

	/**
	 * @dataProvider provideCanHaveTalkPage
	 * @covers Title::canHaveTalkPage
	 *
	 * @param Title $title
	 * @param bool $expected
	 */
	public function testCanHaveTalkPage( Title $title, $expected ) {
		$actual = $title->canHaveTalkPage();
		$this->assertSame( $expected, $actual, $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideIsWatchable
	 * @covers Title::isWatchable
	 *
	 * @param Title $title
	 * @param bool $expected
	 */
	public function testIsWatchable( Title $title, $expected ) {
		$this->hideDeprecated( 'Title::isWatchable' );
		$actual = $title->isWatchable();
		$this->assertSame( $expected, $actual, $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_good
	 * @covers Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_good( Title $title, Title $expected ) {
		$actual = $title->getTalkPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_bad( Title $title ) {
		$this->expectException( MWException::class );
		$title->getTalkPage();
	}

	/**
	 * @dataProvider provideGetTalkPage_broken
	 * @covers Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_broken( Title $title, Title $expected, $valid ) {
		$errorLevel = error_reporting( E_ERROR );

		// NOTE: Eventually we want to throw in this case. But while there is still code that
		// calls this method without checking, we want to avoid fatal errors.
		// See discussion on T227817.
		$result = $title->getTalkPage();
		$this->assertTrue( $expected->equals( $result ) );
		$this->assertSame( $valid, $result->isValid() );

		error_reporting( $errorLevel );
	}

	/**
	 * @dataProvider provideGetTalkPage_good
	 * @covers Title::getTalkPageIfDefined
	 */
	public function testGetTalkPageIfDefined_good( Title $title, Title $expected ) {
		$actual = $title->getTalkPageIfDefined();
		$this->assertNotNull( $actual, $title->getPrefixedDBkey() );
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers Title::getTalkPageIfDefined
	 */
	public function testGetTalkPageIfDefined_bad( Title $title ) {
		$talk = $title->getTalkPageIfDefined();
		$this->assertNull(
			$talk,
			$title->getPrefixedDBkey()
		);
	}

	/**
	 * @dataProvider provideGetSubjectPage_good
	 * @covers Title::getSubjectPage
	 */
	public function testGetSubjectPage_good( Title $title, Title $expected ) {
		$actual = $title->getSubjectPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetOtherPage_good
	 * @covers Title::getOtherPage
	 */
	public function testGetOtherPage_good( Title $title, Title $expected ) {
		$actual = $title->getOtherPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers Title::getOtherPage
	 */
	public function testGetOtherPage_bad( Title $title ) {
		$this->expectException( MWException::class );
		$title->getOtherPage();
	}

	/**
	 * @dataProvider provideIsMovable
	 * @covers Title::isMovable
	 *
	 * @param string|Title $title
	 * @param bool $expected
	 * @param callable|null $hookCallback For TitleIsMovable
	 */
	public function testIsMovable( $title, $expected, $hookCallback = null ) {
		if ( $hookCallback ) {
			$this->setTemporaryHook( 'TitleIsMovable', $hookCallback );
		}
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$this->assertSame( $expected, $title->isMovable() );
	}

	public static function provideIsMovable() {
		return [
			'Simple title' => [ 'Foo', true ],
			// @todo Should these next two really be true?
			'Empty name' => [ Title::makeTitle( NS_MAIN, '' ), true ],
			'Invalid name' => [ Title::makeTitle( NS_MAIN, '<' ), true ],
			'Interwiki' => [ Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ), false ],
			'Special page' => [ 'Special:FooBar', false ],
			'Aborted by hook' => [ 'Hooked in place', false,
				static function ( Title $title, &$result ) {
					$result = false;
				}
			],
		];
	}

	public function provideCreateFragmentTitle() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), 'foo' ],
			[ Title::makeTitle( NS_TALK, 'Test', 'foo' ), '' ],
			[ Title::makeTitle( NS_CATEGORY, 'Test', 'foo' ), 'bar' ],
			[ Title::makeTitle( NS_MAIN, 'Test1', '', 'interwiki' ), 'baz' ]
		];
	}

	/**
	 * @covers Title::createFragmentTarget
	 * @dataProvider provideCreateFragmentTitle
	 */
	public function testCreateFragmentTitle( Title $title, $fragment ) {
		$fragmentTitle = $title->createFragmentTarget( $fragment );

		$this->assertEquals( $title->getNamespace(), $fragmentTitle->getNamespace() );
		$this->assertEquals( $title->getText(), $fragmentTitle->getText() );
		$this->assertEquals( $title->getInterwiki(), $fragmentTitle->getInterwiki() );
		$this->assertEquals( $fragment, $fragmentTitle->getFragment() );
	}

	public function provideGetPrefixedText() {
		return [
			// ns = 0
			[
				Title::makeTitle( NS_MAIN, 'Foo bar' ),
				'Foo bar'
			],
			// ns = 2
			[
				Title::makeTitle( NS_USER, 'Foo bar' ),
				'User:Foo bar'
			],
			// ns = 3
			[
				Title::makeTitle( NS_USER_TALK, 'Foo bar' ),
				'User talk:Foo bar'
			],
			// fragment not included
			[
				Title::makeTitle( NS_MAIN, 'Foo bar', 'fragment' ),
				'Foo bar'
			],
			// ns = -2
			[
				Title::makeTitle( NS_MEDIA, 'Foo bar' ),
				'Media:Foo bar'
			],
			// non-existent namespace
			[
				Title::makeTitle( 100777, 'Foo bar' ),
				'Special:Badtitle/NS100777:Foo bar'
			],
		];
	}

	/**
	 * @covers Title::getPrefixedText
	 * @dataProvider provideGetPrefixedText
	 */
	public function testGetPrefixedText( Title $title, $expected ) {
		$this->assertEquals( $expected, $title->getPrefixedText() );
	}

	public function provideGetPrefixedDBKey() {
		return [
			// ns = 0
			[
				Title::makeTitle( NS_MAIN, 'Foo_bar' ),
				'Foo_bar'
			],
			// ns = 2
			[
				Title::makeTitle( NS_USER, 'Foo_bar' ),
				'User:Foo_bar'
			],
			// ns = 3
			[
				Title::makeTitle( NS_USER_TALK, 'Foo_bar' ),
				'User_talk:Foo_bar'
			],
			// fragment not included
			[
				Title::makeTitle( NS_MAIN, 'Foo_bar', 'fragment' ),
				'Foo_bar'
			],
			// ns = -2
			[
				Title::makeTitle( NS_MEDIA, 'Foo_bar' ),
				'Media:Foo_bar'
			],
			// non-existent namespace
			[
				Title::makeTitle( 100777, 'Foo_bar' ),
				'Special:Badtitle/NS100777:Foo_bar'
			],
		];
	}

	/**
	 * @covers Title::getPrefixedDBKey
	 * @dataProvider provideGetPrefixedDBKey
	 */
	public function testGetPrefixedDBKey( Title $title, $expected ) {
		$this->assertEquals( $expected, $title->getPrefixedDBkey() );
	}

	/**
	 * @covers Title::getFragmentForURL
	 * @dataProvider provideGetFragmentForURL
	 *
	 * @param string $titleStr
	 * @param string $expected
	 */
	public function testGetFragmentForURL( $titleStr, $expected ) {
		$this->setMwGlobals( [
			'wgFragmentMode' => [ 'html5' ],
			'wgExternalInterwikiFragmentMode' => 'legacy',
		] );
		// InterwikiLookup is configured in setUp()

		$title = Title::newFromText( $titleStr );
		self::assertEquals( $expected, $title->getFragmentForURL() );
	}

	public function provideGetFragmentForURL() {
		return [
			[ 'Foo', '' ],
			[ 'Foo#ümlåût', '#ümlåût' ],
			[ 'de:Foo#Bå®', '#Bå®' ],
			[ 'zz:Foo#тест', '#.D1.82.D0.B5.D1.81.D1.82' ],
		];
	}

	/**
	 * @covers Title::isRawHtmlMessage
	 * @dataProvider provideIsRawHtmlMessage
	 */
	public function testIsRawHtmlMessage( $textForm, $expected ) {
		$this->setMwGlobals( 'wgRawHtmlMessages', [
			'foobar',
			'foo_bar',
			'foo-bar',
		] );

		$title = Title::newFromText( $textForm );
		$this->assertSame( $expected, $title->isRawHtmlMessage() );
	}

	public function provideIsRawHtmlMessage() {
		return [
			[ 'MediaWiki:Foobar', true ],
			[ 'MediaWiki:Foo bar', true ],
			[ 'MediaWiki:Foo-bar', true ],
			[ 'MediaWiki:foo bar', true ],
			[ 'MediaWiki:foo-bar', true ],
			[ 'MediaWiki:foobar', true ],
			[ 'MediaWiki:some-other-message', false ],
			[ 'Main Page', false ],
		];
	}

	public function provideEquals() {
		yield '(newFromText) same text' => [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Main Page' ),
			true
		];
		yield '(newFromText) different text' => [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Not The Main Page' ),
			false
		];
		yield '(newFromText) different namespace, same text' => [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Project:Main Page' ),
			false
		];
		yield '(newFromText) namespace alias' => [
			Title::newFromText( 'File:Example.png' ),
			Title::newFromText( 'Image:Example.png' ),
			true
		];
		yield '(newFromText) same special page' => [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Special:Version' ),
			true
		];
		yield '(newFromText) different special page' => [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Special:Recentchanges' ),
			false
		];
		yield '(newFromText) compare special and normal page' => [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Main Page' ),
			false
		];
		yield '(makeTitle) same text' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			true
		];
		yield '(makeTitle) different text' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Bar', '', '' ),
			false
		];
		yield '(makeTitle) different namespace, same text' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_TALK, 'Foo', '', '' ),
			false
		];
		yield '(makeTitle) same fragment' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			true
		];
		yield '(makeTitle) different fragment (ignored)' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Baz', '' ),
			true
		];
		yield '(makeTitle) fragment vs no fragment (ignored)' => [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			true
		];
		yield '(makeTitle) same interwiki' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			true
		];
		yield '(makeTitle) different interwiki' => [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			false
		];

		// Wrong type
		yield '(makeTitle vs PageIdentityValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new PageIdentityValue( 0, NS_MAIN, 'Foo', PageIdentity::LOCAL ),
			false
		];
		yield '(makeTitle vs TitleValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new TitleValue( NS_MAIN, 'Foo' ),
			false
		];
		yield '(makeTitle vs UserIdentityValue) name text' => [
			Title::makeTitle( NS_MAIN, 'Foo' ),
			new UserIdentityValue( 7, 'Foo' ),
			false
		];
	}

	/**
	 * @covers Title::equals
	 * @dataProvider provideEquals
	 */
	public function testEquals( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->equals( $secondValue )
		);
	}

	public function provideIsSamePageAs() {
		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 1 );
		yield '(PageIdentityValue) same text, title has ID 0' => [
			$title,
			new PageIdentityValue( 1, 0, 'Foo', PageIdentity::LOCAL ),
			true
		];

		$title = Title::makeTitle( 1, 'Bar_Baz' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) same text, PageIdentityValue has ID 0' => [
			$title,
			new PageIdentityValue( 0, 1, 'Bar_Baz', PageIdentity::LOCAL ),
			true
		];

		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different text, both IDs are 0' => [
			$title,
			new PageIdentityValue( 0, 0, 'Foozz', PageIdentity::LOCAL ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different namespace' => [
			$title,
			new PageIdentityValue( 0, 1, 'Foo', PageIdentity::LOCAL ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo', '' );
		$title->resetArticleID( 1 );
		yield '(PageIdentityValue) different wiki, different ID' => [
			$title,
			new PageIdentityValue( 1, 0, 'Foo', 'bar' ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo', '' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different wiki, both IDs are 0' => [
			$title,
			new PageIdentityValue( 0, 0, 'Foo', 'bar' ),
			false
		];
	}

	/**
	 * @covers Title::isSamePageAs
	 * @dataProvider provideIsSamePageAs
	 */
	public function testIsSamePageAs( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->isSamePageAs( $secondValue )
		);
	}

	public function provideIsSameLinkAs() {
		yield 'same text' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 0, 'Foo' ),
			true
		];
		yield 'same namespace' => [
			Title::makeTitle( 1, 'Bar_Baz' ),
			new TitleValue( 1, 'Bar_Baz' ),
			true
		];
		yield 'same text, different namespace' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 1, 'Foo' ),
			false
		];
		yield 'different text' => [
			Title::makeTitle( 0, 'Foo' ),
			new TitleValue( 0, 'Foozz' ),
			false
		];
		yield 'different fragment' => [
			Title::makeTitle( 0, 'Foo', '' ),
			new TitleValue( 0, 'Foo', 'Bar' ),
			false
		];
		yield 'different interwiki' => [
			Title::makeTitle( 0, 'Foo', '', 'bar' ),
			new TitleValue( 0, 'Foo', '', '' ),
			false
		];
	}

	/**
	 * @covers Title::isSameLinkAs
	 * @dataProvider provideIsSameLinkAs
	 */
	public function testIsSameLinkAs( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->isSameLinkAs( $secondValue )
		);
	}

	/**
	 * @covers Title::newMainPage
	 */
	public function testNewMainPage() {
		$mock = $this->createMock( MessageCache::class );
		$mock->method( 'get' )->willReturn( 'Foresheet' );
		$mock->method( 'transform' )->willReturn( 'Foresheet' );

		$this->setService( 'MessageCache', $mock );

		$this->assertSame(
			'Foresheet',
			Title::newMainPage()->getText()
		);
	}

	/**
	 * @covers Title::newMainPage
	 */
	public function testNewMainPageWithLocal() {
		$local = $this->createMock( MessageLocalizer::class );
		$local->method( 'msg' )->willReturn( new RawMessage( 'Prime Article' ) );

		$this->assertSame(
			'Prime Article',
			Title::newMainPage( $local )->getText()
		);
	}

	/**
	 * @covers Title::loadRestrictions
	 */
	public function testLoadRestrictions() {
		$title = Title::newFromText( 'UTPage1' );
		$title->loadRestrictions();
		$this->assertTrue( $title->areRestrictionsLoaded() );
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$title->loadRestrictions();
		$this->assertTrue( $title->areRestrictionsLoaded() );
		$this->assertFalse( $title->getRestrictionExpiry( 'create' ),
			"Existing page can't have create protection" );
		$this->assertSame( 'infinity', $title->getRestrictionExpiry( 'edit' ) );
		$page = $this->getNonexistingTestPage( 'UTest1' );
		$title = $page->getTitle();
		$protectExpiry = wfTimestamp( TS_MW, time() + 10000 );
		$cascade = 0;
		$page->doUpdateRestrictions(
			[ 'create' => 'sysop' ],
			[ 'create' => $protectExpiry ],
			$cascade,
			'test',
			$this->getTestSysop()->getUser()
		);
		$title->flushRestrictions();
		$title->loadRestrictions();
		$this->assertSame(
			$title->getRestrictionExpiry( 'create' ),
			$protectExpiry
		);
	}

	public function provideRestrictionsRows() {
		yield [ [ (object)[
			'pr_id' => 1,
			'pr_page' => 1,
			'pr_type' => 'edit',
			'pr_level' => 'sysop',
			'pr_cascade' => 0,
			'pr_user' => null,
			'pr_expiry' => 'infinity'
		] ] ];
		yield [ [ (object)[
			'pr_id' => 1,
			'pr_page' => 1,
			'pr_type' => 'edit',
			'pr_level' => 'sysop',
			'pr_cascade' => 0,
			'pr_user' => null,
			'pr_expiry' => 'infinity'
		] ] ];
		yield [ [ (object)[
			'pr_id' => 1,
			'pr_page' => 1,
			'pr_type' => 'move',
			'pr_level' => 'sysop',
			'pr_cascade' => 0,
			'pr_user' => null,
			'pr_expiry' => wfTimestamp( TS_MW, time() + 10000 )
		] ] ];
	}

	/**
	 * @covers Title::loadRestrictionsFromRows
	 * @dataProvider provideRestrictionsRows
	 */
	public function testloadRestrictionsFromRows( $rows ) {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$title->loadRestrictionsFromRows( $rows );
		$this->assertSame(
			$rows[0]->pr_level,
			$title->getRestrictions( $rows[0]->pr_type )[0]
		);
		$this->assertSame(
			$rows[0]->pr_expiry,
			$title->getRestrictionExpiry( $rows[0]->pr_type )
		);
	}

	/**
	 * @dataProvider provideRestrictionStoreForwarding
	 * @covers Title::getFilteredRestrictionTypes
	 * @covers Title::getRestrictionTypes
	 * @covers Title::getTitleProtection
	 * @covers Title::deleteTitleProtection
	 * @covers Title::isSemiProtected
	 * @covers Title::isProtected
	 * @covers Title::isCascadeProtected
	 * @covers Title::areCascadeProtectionSourcesLoaded
	 * @covers Title::getCascadeProtectionSources
	 * @covers Title::areRestrictionsLoaded
	 * @covers Title::getRestrictions
	 * @covers Title::getAllRestrictions
	 * @covers Title::getRestrictionExpiry
	 * @covers Title::areRestrictionsCascading
	 * @covers Title::loadRestrictionsFromRows
	 * @covers Title::loadRestrictions
	 * @covers Title::flushRestrictions
	 */
	public function testRestrictionStoreForwarding(
		string $method, array $params, $return, array $options = []
	) {
		$expectedParams = $options['expectedParams'] ?? $params;

		if ( isset( $options['static'] ) ) {
			$callee = 'Title';
		} else {
			$callee = $this->getExistingTestPage()->getTitle();
			$expectedParams = array_merge( [ $callee ], $expectedParams );
		}

		$mockRestrictionStore = $this->createMock( RestrictionStore::class );

		$expectedMethod = $options['expectedMethod'] ?? $method;

		// Don't try to forward to a method that doesn't exist!
		$this->assertIsCallable( [ $mockRestrictionStore, $expectedMethod ] );

		$expectedCall = $mockRestrictionStore->expects( $this->once() )
			->method( $expectedMethod )
			->with( ...$expectedParams );
		if ( !isset( $options['void'] ) ) {
			$expectedCall->willReturn( $return );
		}

		$mockRestrictionStore->expects( $this->never() )
			->method( $this->anythingBut( $expectedMethod ) );

		$this->setService( 'RestrictionStore', $mockRestrictionStore );

		$options['expectedReturn'] = $options['expectedReturn'] ?? $return;

		$comparisonMethod = isset( $options['weakCompareReturn'] ) ? 'assertEquals' : 'assertSame';

		$this->$comparisonMethod( $options['expectedReturn'], [ $callee, $method ]( ...$params ) );
	}

	public static function provideRestrictionStoreForwarding() {
		$pageIdentity = PageIdentityValue::localIdentity( 144, NS_MAIN, 'Sample' );
		$title = Title::castFromPageIdentity( $pageIdentity );
		return [
			[ 'getFilteredRestrictionTypes', [ true ], [ 'abc' ],
				[ 'static' => true, 'expectedMethod' => 'listAllRestrictionTypes' ] ],
			[ 'getFilteredRestrictionTypes', [ false ], [ 'def' ],
				[ 'static' => true, 'expectedMethod' => 'listAllRestrictionTypes' ] ],
			[ 'getRestrictionTypes', [], [ 'ghi' ],
				[ 'expectedMethod' => 'listApplicableRestrictionTypes' ] ],
			[ 'getTitleProtection', [], [ 'jkl' ], [ 'expectedMethod' => 'getCreateProtection' ] ],
			[ 'getTitleProtection', [], null,
				[ 'expectedMethod' => 'getCreateProtection', 'expectedReturn' => false ] ],
			[ 'deleteTitleProtection', [], null,
				[ 'expectedMethod' => 'deleteCreateProtection', 'void' => true ] ],
			[ 'isSemiProtected', [ 'phlebotomize' ], true ],
			[ 'isSemiProtected', [ 'splecotomize' ], false ],
			[ 'isProtected', [ 'strezotomize' ], true ],
			[ 'isProtected', [ 'chrelotomize' ], false ],
			[ 'isCascadeProtected', [], true ],
			[ 'isCascadeProtected', [], false ],
			[ 'areCascadeProtectionSourcesLoaded', [ true ], true, [ 'expectedParams' => [] ] ],
			[ 'areCascadeProtectionSourcesLoaded', [ true ], false, [ 'expectedParams' => [] ] ],
			[ 'areCascadeProtectionSourcesLoaded', [ false ], true, [ 'expectedParams' => [] ] ],
			[ 'areCascadeProtectionSourcesLoaded', [ false ], false, [ 'expectedParams' => [] ] ],
			[ 'getCascadeProtectionSources', [], [ [ $pageIdentity ], [ 'mno' ] ],
				[ 'expectedReturn' => [ [ $title ], [ 'mno' ] ], 'weakCompareReturn' => true ] ],
			[ 'getCascadeProtectionSources', [], [ [], [] ],
				[ 'expectedReturn' => [ false, [] ] ] ],
			[ 'getCascadeProtectionSources', [ true ], [ [ $pageIdentity ], [ 'mno' ] ],
				[ 'expectedParams' => [], 'expectedReturn' => [ [ $title ], [ 'mno' ] ],
				'weakCompareReturn' => true ] ],
			[ 'getCascadeProtectionSources', [ true ], [ [], [] ],
				[ 'expectedParams' => [], 'expectedReturn' => [ false, [] ] ] ],
			[ 'getCascadeProtectionSources', [ false ], false,
				[ 'expectedMethod' => 'isCascadeProtected', 'expectedParams' => [],
				'expectedReturn' => [ false, [] ] ] ],
			[ 'getCascadeProtectionSources', [ false ], true,
				[ 'expectedMethod' => 'isCascadeProtected', 'expectedParams' => [],
				'expectedReturn' => [ true, [] ] ] ],
			[ 'areRestrictionsLoaded', [], true ],
			[ 'areRestrictionsLoaded', [], false ],
			[ 'getRestrictions', [ 'stu' ], [ 'vwx' ] ],
			[ 'getAllRestrictions', [], [ 'yza' ] ],
			[ 'getRestrictionExpiry', [ 'bcd' ], 'efg' ],
			[ 'getRestrictionExpiry', [ 'hij' ], null, [ 'expectedReturn' => false ] ],
			[ 'areRestrictionsCascading', [], true ],
			[ 'areRestrictionsCascading', [], false ],
			[ 'loadRestrictionsFromRows', [ [ 'hij' ], 'klm' ], null, [ 'void' => true ] ],
			[ 'loadRestrictions', [ 'nop', 123 ], null,
				[ 'void' => true, 'expectedParams' => [ 123, 'nop' ] ] ],
			[ 'flushRestrictions', [], null, [ 'void' => true ] ],
		];
	}

	/**
	 * @covers Title::getRestrictions
	 */
	public function testGetRestrictions() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => [
				'a' => [ 'sysop' ],
				'b' => [ 'sysop' ],
				'c' => [ 'sysop' ]
			],
		] ];
		$this->assertArrayEquals( [ 'sysop' ], $title->getRestrictions( 'a' ) );
		$this->assertArrayEquals( [], $title->getRestrictions( 'error' ) );
		// TODO: maybe test if loadRestrictionsFromRows() is called?
	}

	/**
	 * @covers Title::getAllRestrictions
	 */
	public function testGetAllRestrictions() {
		$restrictions = [
			'a' => [ 'sysop' ],
			'b' => [ 'sysop' ],
			'c' => [ 'sysop' ],
		];
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => $restrictions
		] ];
		$this->assertArrayEquals(
			$restrictions,
			$title->getAllRestrictions()
		);
	}

	/**
	 * @covers Title::getRestrictionExpiry
	 */
	public function testGetRestrictionExpiry() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'expiry' => [
				'a' => 'infinity', 'b' => 'infinity', 'c' => 'infinity'
			],
			// XXX This is bogus, restrictions will never be empty when expiry is not
			'restrictions' => [],
		] ];
		$this->assertSame( 'infinity', $title->getRestrictionExpiry( 'a' ) );
		$this->assertArrayEquals( [], $title->getRestrictions( 'error' ) );
	}

	/**
	 * @covers Title::getTitleProtection
	 */
	public function testGetTitleProtection() {
		$title = $this->getNonexistingTestPage( 'UTest1' )->getTitle();
		$this->assertFalse( $title->getTitleProtection() );
	}

	/**
	 * @covers Title::isSemiProtected
	 */
	public function testIsSemiProtected() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$this->setMwGlobals( [
			'wgSemiprotectedRestrictionLevels' => [ 'autoconfirmed' ],
			'wgRestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ]
		] );
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => [ 'edit' => [ 'sysop' ] ],
		] ];
		$this->assertFalse( $title->isSemiProtected( 'edit' ) );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => [ 'edit' => [ 'autoconfirmed' ] ],
		] ];
		$this->assertTrue( $title->isSemiProtected( 'edit' ) );
	}

	/**
	 * @covers Title::deleteTitleProtection
	 */
	public function testDeleteTitleProtection() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$this->assertFalse( $title->getTitleProtection() );
	}

	/**
	 * @covers Title::isProtected
	 */
	public function testIsProtected() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$this->setMwGlobals( [
			'wgRestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ],
			'wgRestrictionTypes' => [ 'create', 'edit', 'move', 'upload' ]
		] );
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => [ 'edit' => [ 'sysop' ] ],
		] ];
		$this->assertTrue( $title->isProtected( 'edit' ) );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'restrictions' => [ 'edit' => [ 'test' ] ],
		] ];
		$this->assertFalse( $title->isProtected( 'edit' ) );
	}

	/**
	 * @covers Title::isNamespaceProtected
	 */
	public function testIsNamespaceProtected() {
		$this->hideDeprecated( 'Title::isNamespaceProtected' );
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$this->setMwGlobals( [
			'wgNamespaceProtection' => []
		] );
		$this->assertFalse(
			$title->isNamespaceProtected( $this->getTestUser()->getUser() )
		);
		$this->setMwGlobals( [
			'wgNamespaceProtection' => [
				NS_MAIN => [ 'edit-main' ]
			]
		] );
		$this->assertTrue(
			$title->isNamespaceProtected( $this->getTestUser()->getUser() )
		);
	}

	/**
	 * @covers Title::isCascadeProtected
	 */
	public function testIsCascadeProtected() {
		$page = $this->getExistingTestPage( 'UTest1' );
		$title = $page->getTitle();
		$rs = MediaWikiServices::getInstance()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $title ) => [
			'has_cascading' => true,
		] ];
		$this->assertTrue( $title->isCascadeProtected() );
		$wrapper->cache = [];
		$this->assertFalse( $title->isCascadeProtected() );
		$wrapper->cache = [];
		$cascade = 1;
		$anotherPage = $this->getExistingTestPage( 'UTest2' );
		$anotherPage->doUserEditContent(
			new WikitextContent( '{{:UTest1}}' ),
			$this->getTestSysop()->getUser(),
			'test'
		);
		$anotherPage->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'test',
			$this->getTestSysop()->getUser()
		);
		$this->assertTrue( $title->isCascadeProtected() );
	}

	/**
	 * @covers Title::getCascadeProtectionSources
	 * @group Broken
	 */
	public function testGetCascadeProtectionSources() {
		$page = $this->getExistingTestPage( 'UTest1' );
		$title = $page->getTitle();

		$title->mCascadeSources = [];
		$this->assertArrayEquals(
			[ [], [] ],
			$title->getCascadeProtectionSources( true )
		);

		$reflection = new ReflectionClass( $title );
		$reflection_property = $reflection->getProperty( 'mHasCascadingRestrictions' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $title, true );
		$this->assertArrayEquals(
			[ true, [] ],
			$title->getCascadeProtectionSources( false )
		);

		$title->mCascadeSources = null;
		$reflection_property->setValue( $title, null );
		$this->assertArrayEquals(
			[ false, [] ],
			$title->getCascadeProtectionSources( false )
		);

		$title->mCascadeSources = null;
		$reflection_property->setValue( $title, null );
		$this->assertArrayEquals(
			[ [], [] ],
			$title->getCascadeProtectionSources( true )
		);

		// TODO: this might partially duplicate testIsCascadeProtected method above

		$cascade = 1;
		$anotherPage = $this->getExistingTestPage( 'UTest2' );
		$anotherPage->doUserEditContent(
			new WikitextContent( '{{:UTest1}}' ),
			$this->getTestSysop()->getUser(),
			'test'
		);
		$anotherPage->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'test',
			$this->getTestSysop()->getUser()
		);

		$this->assertArrayEquals(
			[ true, [] ],
			$title->getCascadeProtectionSources( false )
		);

		$title->mCascadeSources = null;
		$result = $title->getCascadeProtectionSources( true );
		$this->assertArrayEquals(
			[ 'edit' => [ 'sysop' ] ],
			$result[1]
		);
		$this->assertArrayHasKey(
			$anotherPage->getTitle()->getArticleID(), $result[0]
		);
	}

	/**
	 * @covers Title::getCdnUrls
	 */
	public function testGetCdnUrls() {
		$this->assertEquals(
			[
				'https://example.org/wiki/Example',
				'https://example.org/w/index.php?title=Example&action=history',
			],
			Title::makeTitle( NS_MAIN, 'Example' )->getCdnUrls(),
			'article'
		);
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages() {
		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->setMwGlobals( 'wgNamespacesWithSubpages', [ $title->getNamespace() => true ] );

		$this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$notQuiteSubpageTitle = $title->getPrefixedDBkey() . 'X'; // no slash!
		$this->getExistingTestPage( $notQuiteSubpageTitle );

		$subpages = iterator_to_array( $title->getSubpages() );

		$this->assertCount( 2, $subpages );
		$this->assertCount( 1, $title->getSubpages( 1 ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages_disabled() {
		$this->setMwGlobals( 'wgNamespacesWithSubpages', [] );

		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$this->assertEmpty( $title->getSubpages() );
	}

	/**
	 * @covers Title::getNsText
	 * @dataProvider provideNamespaces
	 */
	public function testGetNsText( $namespace, $expected ) {
		if ( $namespace instanceof Title ) {
			$this->assertSame( $expected, $namespace->getNsText() );
		} else {
			$actual = Title::makeTitle( $namespace, 'Title_test' )->getNsText();
			$this->assertSame( $expected, $actual );
		}
	}

	public function provideNamespaces() {
		// For ->isExternal() code path, construct a title with interwiki
		$title = Title::makeTitle( NS_FILE, 'test', 'frag', 'meta' );
		return [
			[ NS_MAIN, '' ],
			[ NS_FILE, 'File' ],
			[ NS_MEDIA, 'Media' ],
			[ NS_TALK, 'Talk' ],
			[ NS_CATEGORY, 'Category' ],
			[ $title, 'File' ],
		];
	}

	/**
	 * @covers Title::getSubjectNsText
	 * @dataProvider providePagesWithSubjects
	 */
	public function testGetSubjectNsText( Title $title, $expected ) {
		$actual = $title->getSubjectNsText();
		$this->assertSame( $expected, $actual );
	}

	public function providePagesWithSubjects() {
		return [
			[ Title::makeTitle( NS_USER_TALK, 'User_test' ), 'User' ],
			[ Title::makeTitle( NS_PROJECT, 'Test' ), 'Project' ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), '' ],
			[ Title::makeTitle( NS_CATEGORY, 'Cat_test' ), 'Category' ],
		];
	}

	/**
	 * @covers Title::getTalkNsText
	 * @dataProvider provideTitlesWithTalkPages
	 */
	public function testGetTalkNsText( Title $title, $expected ) {
		$actual = $title->getTalkNsText();
		$this->assertSame( $expected, $actual );
	}

	public function provideTitlesWithTalkPages() {
		return [
			[ Title::makeTitle( NS_HELP, 'Help page' ), 'Help_talk' ],
			[ Title::newMainPage(), 'Talk' ],
			[ Title::makeTitle( NS_PROJECT, 'Test' ), 'Project_talk' ],
		];
	}

	/**
	 * @covers Title::isSpecial
	 */
	public function testIsSpecial() {
		$title = Title::makeTitle( NS_SPECIAL, 'Recentchanges/Subpage' );
		$this->assertTrue( $title->isSpecial( 'Recentchanges' ) );
	}

	/**
	 * @covers Title::isSpecial
	 */
	public function testIsNotSpecial() {
		$title = Title::newFromText( 'NotSpecialPage/Subpage', NS_SPECIAL );
		$this->assertFalse( $title->isSpecial( 'NotSpecialPage' ) );
	}

	/**
	 * @covers Title::isTalkPage
	 */
	public function testIsTalkPage() {
		$title = Title::newFromText( 'Talk page', NS_TALK );
		$this->assertTrue( $title->isTalkPage() );

		$titleNotInTalkNs = Title::makeTitle( NS_HELP, 'Test' );
		$this->assertFalse( $titleNotInTalkNs->isTalkPage() );
	}

	/**
	 * @covers Title::getBacklinkCache
	 */
	public function testGetBacklinkCache() {
		$backLinkCache = Title::makeTitle( NS_FILE, 'Test' )->getBacklinkCache();
		$this->assertInstanceOf( BacklinkCache::class, $backLinkCache );
	}

	/**
	 * @covers Title::isSubpage
	 * @covers Title::isSubpageOf
	 * @dataProvider provideNsWithSubpagesSupport
	 */
	public function testIsSubpageOfWithNamespacesSubpages( $namespace, $pageName, $subpageName ) {
		$page = Title::makeTitle( $namespace, $pageName, '', 'meta' );
		$subPage = Title::makeTitle( $namespace, $subpageName, '', 'meta' );

		$this->assertTrue( $subPage->isSubpageOf( $page ) );
		$this->assertTrue( $subPage->isSubpage() );
	}

	public function provideNsWithSubpagesSupport() {
		return [
			[ NS_HELP, 'Mainhelp', 'Mainhelp/Subhelp' ],
			[ NS_USER, 'Mainuser', 'Mainuser/Subuser' ],
			[ NS_TALK, 'Maintalk', 'Maintalk/Subtalk' ],
			[ NS_PROJECT, 'Mainproject', 'Mainproject/Subproject' ],
		];
	}

	/**
	 * @covers Title::isSubpage
	 * @covers Title::isSubpageOf
	 * @dataProvider provideNsWithNoSubpages
	 */
	public function testIsSubpageOfWithoutNamespacesSubpages( $namespace, $pageName, $subpageName ) {
		$page = Title::makeTitle( $namespace, $pageName, '', 'meta' );
		$subPage = Title::makeTitle( $namespace, $subpageName, '', 'meta' );

		$this->assertFalse( $page->isSubpageOf( $page ) );
		$this->assertFalse( $subPage->isSubpage() );
	}

	public function provideNsWithNoSubpages() {
		return [
			[ NS_CATEGORY, 'Maincat', 'Maincat/Subcat' ],
			[ NS_MAIN, 'Mainpage', 'Mainpage/Subpage' ]
		];
	}

	/**
	 * @covers Title::getEditURL
	 * @dataProvider provideTitleEditURLs
	 */
	public function testGetEditURL( Title $title, $expected ) {
		$actual = $title->getEditURL();
		$this->assertSame( $expected, $actual );
	}

	public function provideTitleEditURLs() {
		return [
			[ Title::newFromText( 'Title', NS_MAIN ), '/w/index.php?title=Title&action=edit' ],
			[ Title::makeTitle( NS_HELP, 'Test', '', 'mw' ), '' ],
			[ Title::newFromText( 'Test', NS_HELP ), '/w/index.php?title=Help:Test&action=edit' ],
		];
	}

	/**
	 * @covers Title::getEditURL
	 * @dataProvider provideTitleEditURLsWithActionPaths
	 */
	public function testGetEditUrlWithActionPaths( Title $title, $expected ) {
		$this->setMwGlobals( 'wgActionPaths', [ 'edit' => '/wiki/edit/$1' ] );
		$actual = $title->getEditURL();
		$this->assertSame( $expected, $actual );
	}

	public function provideTitleEditURLsWithActionPaths() {
		return [
			[ Title::newFromText( 'Title', NS_MAIN ), '/wiki/edit/Title' ],
			[ Title::makeTitle( NS_HELP, 'Test', '', 'mw' ), '' ],
			[ Title::newFromText( 'Test', NS_HELP ), '/wiki/edit/Help:Test' ],
		];
	}
}
