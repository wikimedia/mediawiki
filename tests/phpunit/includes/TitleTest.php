<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @group Title
 */
class TitleTest extends MediaWikiIntegrationTestCase {
	protected function setUp() : void {
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
	}

	protected function tearDown() : void {
		parent::tearDown();
		// delete dummy pages
		$this->getNonexistingTestPage( 'UTest1' );
		$this->getNonexistingTestPage( 'UTest2' );
	}

	/**
	 * @covers Title::legalChars
	 */
	public function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
				$this->assertFalse(
					(bool)preg_match( "/[$titlechars]/", $chr ),
					"chr($num) = $chr is not a valid titlechar"
				);
			} else {
				$this->assertTrue(
					(bool)preg_match( "/[$titlechars]/", $chr ),
					"chr($num) = $chr is a valid titlechar"
				);
			}
		}
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

	private function secureAndSplitGlobals() {
		$this->setMwGlobals( [
			'wgLocalInterwikis' => [ 'localtestiw' ],
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
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers Title::secureAndSplit
	 * @dataProvider provideValidSecureAndSplit
	 * @note This mainly tests MediaWikiTitleCodec::parseTitle().
	 */
	public function testSecureAndSplitValid( $text ) {
		$this->secureAndSplitGlobals();
		$this->assertInstanceOf( Title::class, Title::newFromText( $text ), "Valid: $text" );
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers Title::secureAndSplit
	 * @dataProvider provideInvalidSecureAndSplit
	 * @note This mainly tests MediaWikiTitleCodec::parseTitle().
	 */
	public function testSecureAndSplitInvalid( $text, $expectedErrorMessage ) {
		$this->secureAndSplitGlobals();
		try {
			Title::newFromTextThrow( $text ); // should throw
			$this->assertTrue( false, "Invalid: $text" );
		} catch ( MalformedTitleException $ex ) {
			$this->assertEquals( $expectedErrorMessage, $ex->getErrorMessage(), "Invalid: $text" );
		}
	}

	public static function provideConvertByteClassToUnicodeClass() {
		return [
			[
				' %!"$&\'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+',
				' %!"$&\'()*,\\-./0-9:;=?@A-Z\\\\\\^_`a-z~+\\u0080-\\uFFFF',
			],
			[
				'QWERTYf-\\xFF+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\xFD+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTYf-y+',
				'QWERTYf-y+',
			],
			[
				'QWERTYf-\\x80+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\x80+\\x23',
				'QWERTYf-\\x7F+#\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\x66-\\x80+\\xD3',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			],
			[
				'\\\\\\x99',
				'\\\\\\u0080-\\uFFFF',
			],
			[
				'-\\x99',
				'\\-\\u0080-\\uFFFF',
			],
			[
				'QWERTY\\-\\x99',
				'QWERTY\\-\\u0080-\\uFFFF',
			],
			[
				'\\\\x99',
				'\\\\x99',
			],
			[
				'A-\\x9F',
				'A-\\x7F\\u0080-\\uFFFF',
			],
			[
				'\\x66-\\x77QWERTY\\x88-\\x91FXZ',
				'f-wQWERTYFXZ\\u0080-\\uFFFF',
			],
			[
				'\\x66-\\x99QWERTY\\xAA-\\xEEFXZ',
				'f-\\x7FQWERTYFXZ\\u0080-\\uFFFF',
			],
		];
	}

	/**
	 * @dataProvider provideConvertByteClassToUnicodeClass
	 * @covers Title::convertByteClassToUnicodeClass
	 */
	public function testConvertByteClassToUnicodeClass( $byteClass, $unicodeClass ) {
		$this->assertEquals( $unicodeClass, Title::convertByteClassToUnicodeClass( $byteClass ) );
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

	/**
	 * Auth-less test of Title::userCan
	 *
	 * @param array $whitelistRegexp
	 * @param string $source
	 * @param string $action
	 * @param array|string|bool $expected Required error
	 *
	 * @covers \MediaWiki\Permissions\PermissionManager::checkReadPermissions
	 * @dataProvider dataWgWhitelistReadRegexp
	 */
	public function testWgWhitelistReadRegexp( $whitelistRegexp, $source, $action, $expected ) {
		$this->hideDeprecated( 'Title::userCan' );

		// $wgWhitelistReadRegexp must be an array. Since the provided test cases
		// usually have only one regex, it is more concise to write the lonely regex
		// as a string. Thus we cast to a [] to honor $wgWhitelistReadRegexp
		// type requisite.
		if ( is_string( $whitelistRegexp ) ) {
			$whitelistRegexp = [ $whitelistRegexp ];
		}

		$this->setMwGlobals( [
			// So PermissionManager::isEveryoneAllowed( 'read' ) === false
			'wgGroupPermissions' => [ '*' => [ 'read' => false ] ],
			'wgWhitelistRead' => [ 'some random non sense title' ],
			'wgWhitelistReadRegexp' => $whitelistRegexp,
		] );

		$title = Title::newFromDBkey( $source );

		// New anonymous user with no rights
		$user = new User;
		$this->overrideUserPermissions( $user, [] );
		$errors = $title->userCan( $action, $user );

		if ( is_bool( $expected ) ) {
			# Forge the assertion message depending on the assertion expectation
			$allowableness = $expected
				? " should be allowed"
				: " should NOT be allowed";
			$this->assertEquals(
				$expected,
				$errors,
				"User action '$action' on [[$source]] $allowableness."
			);
		} else {
			$errors = $this->flattenErrorsArray( $errors );
			foreach ( (array)$expected as $error ) {
				$this->assertContains( $error, $errors );
			}
		}
	}

	/**
	 * Provides test parameter values for testWgWhitelistReadRegexp()
	 */
	public function dataWgWhitelistReadRegexp() {
		$ALLOWED = true;
		$DISALLOWED = false;

		return [
			// Everything, if this doesn't work, we're really in trouble
			[ '/.*/', 'Main_Page', 'read', $ALLOWED ],
			[ '/.*/', 'Main_Page', 'edit', $DISALLOWED ],

			// We validate against the title name, not the db key
			[ '/^Main_Page$/', 'Main_Page', 'read', $DISALLOWED ],
			// Main page
			[ '/^Main/', 'Main_Page', 'read', $ALLOWED ],
			[ '/^Main.*/', 'Main_Page', 'read', $ALLOWED ],
			// With spaces
			[ '/Mic\sCheck/', 'Mic Check', 'read', $ALLOWED ],
			// Unicode multibyte
			// ...without unicode modifier
			[ '/Unicode Test . Yes/', 'Unicode Test Ñ Yes', 'read', $DISALLOWED ],
			// ...with unicode modifier
			[ '/Unicode Test . Yes/u', 'Unicode Test Ñ Yes', 'read', $ALLOWED ],
			// Case insensitive
			[ '/MiC ChEcK/', 'mic check', 'read', $DISALLOWED ],
			[ '/MiC ChEcK/i', 'mic check', 'read', $ALLOWED ],

			// From DefaultSettings.php:
			[ "@^UsEr.*@i", 'User is banned', 'read', $ALLOWED ],
			[ "@^UsEr.*@i", 'User:John Doe', 'read', $ALLOWED ],

			// With namespaces:
			[ '/^Special:NewPages$/', 'Special:NewPages', 'read', $ALLOWED ],
			[ null, 'Special:Newpages', 'read', $DISALLOWED ],

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
		# - Optional message
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
	public function testGetBaseText( $title, $expected, $msg = '' ) {
		$title = Title::newFromText( $title );
		$this->assertSame( $expected,
			$title->getBaseText(),
			$msg
		);
	}

	/**
	 * @dataProvider provideBaseTitleCases
	 * @covers Title::getBaseTitle
	 */
	public function testGetBaseTitle( $title, $expected, $msg = '' ) {
		$title = Title::newFromText( $title );
		$base = $title->getBaseTitle();
		$this->assertTrue( $base->isValid(), $msg );
		$this->assertTrue(
			$base->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) ),
			$msg
		);
	}

	public static function provideBaseTitleCases() {
		return [
			# Title, expected base, optional message
			[ 'User:John_Doe', 'John Doe' ],
			[ 'User:John_Doe/subOne/subTwo', 'John Doe/subOne' ],
			[ 'User:Foo / Bar / Baz', 'Foo / Bar ' ],
			[ 'User:Foo/', 'Foo' ],
			[ 'User:Foo/Bar/', 'Foo/Bar' ],
			[ 'User:/', '/' ],
			[ 'User://', '/' ],
			[ 'User:/oops/', '/oops' ],
			[ 'User:/Ramba/Zamba/Mamba/', '/Ramba/Zamba/Mamba' ],
			[ 'User://x//y//z//', '//x//y//z/' ],
		];
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers Title::getRootText
	 */
	public function testGetRootText( $title, $expected, $msg = '' ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expected,
			$title->getRootText(),
			$msg
		);
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers Title::getRootTitle
	 */
	public function testGetRootTitle( $title, $expected, $msg = '' ) {
		$title = Title::newFromText( $title );
		$root = $title->getRootTitle();
		$this->assertTrue( $root->isValid(), $msg );
		$this->assertTrue(
			$root->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) ),
			$msg
		);
	}

	public static function provideRootTitleCases() {
		return [
			# Title, expected base, optional message
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
	public function testGetSubpageText( $title, $expected, $msg = '' ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expected,
			$title->getSubpageText(),
			$msg
		);
	}

	public static function provideSubpageTitleCases() {
		return [
			# Title, expected base, optional message
			[ 'User:John_Doe', 'John Doe' ],
			[ 'User:John_Doe/subOne/subTwo', 'subTwo' ],
			[ 'User:John_Doe/subOne', 'subOne' ],
			[ 'User:/', '/' ],
			[ 'User://', '' ],
			[ 'User:/oops/', '' ],
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
		$interwikiLookup = $this->createMock( InterwikiLookup::class );
		$interwikiLookup->expects( $this->any() )
			->method( 'isValidInterwiki' )
			->willReturnCallback(
				function ( $prefix ) {
					return $prefix == 'wiki';
				}
			);

		$this->setService( 'InterwikiLookup', $interwikiLookup );

		$title = Title::newFromText( $title );
		$expected = Title::newFromLinkTarget( $expected );
		$actual = $title->getSubpage( $sub );

		// NOTE: convert to string for comparison
		$this->assertSame( $expected->getPrefixedText(), $actual->getPrefixedText(), 'text form' );
		$this->assertTrue( $expected->equals( $actual ), 'Title equality' );
	}

	public static function provideNewFromTitleValue() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo' ) ],
			[ new TitleValue( NS_MAIN, 'Foo', 'bar' ) ],
			[ new TitleValue( NS_USER, 'Hansi_Maier' ) ],
		];
	}

	/**
	 * @covers Title::newFromTitleValue
	 * @dataProvider provideNewFromTitleValue
	 */
	public function testNewFromTitleValue( TitleValue $value ) {
		$title = Title::newFromTitleValue( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	/**
	 * @covers Title::newFromLinkTarget
	 * @dataProvider provideNewFromTitleValue
	 */
	public function testNewFromLinkTarget( LinkTarget $value ) {
		$title = Title::newFromLinkTarget( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	/**
	 * @covers Title::newFromLinkTarget
	 */
	public function testNewFromLinkTarget_clone() {
		$title = Title::newFromText( __METHOD__ );
		$this->assertSame( $title, Title::newFromLinkTarget( $title ) );

		// The Title::NEW_CLONE flag should ensure that a fresh instance is returned.
		$clone = Title::newFromLinkTarget( $title, Title::NEW_CLONE );
		$this->assertNotSame( $title, $clone );
		$this->assertTrue( $clone->equals( $title ) );
	}

	public function provideCastFromLinkTarget() {
		return array_merge( [ [ null ] ], self::provideNewFromTitleValue() );
	}

	/**
	 * @covers Title::castFromLinkTarget
	 * @dataProvider provideCastFromLinkTarget
	 */
	public function testCastFromLinkTarget( $value ) {
		$title = Title::castFromLinkTarget( $value );

		if ( $value === null ) {
			$this->assertNull( $title );
		} else {
			$dbkey = str_replace( ' ', '_', $value->getText() );
			$this->assertSame( $dbkey, $title->getDBkey() );
			$this->assertSame( $value->getNamespace(), $title->getNamespace() );
			$this->assertSame( $value->getFragment(), $title->getFragment() );
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
		$iwLookup = $this->createMock( InterwikiLookup::class );
		$iwLookup->method( 'isValidInterwiki' )
			->willReturn( true );

		$this->setService(
			'InterwikiLookup',
			$iwLookup
		);

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
		$page->doEditContent( new WikitextContent( 'Some [[link]]' ), 'summary' );

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
			'Talke page is watchable' => [
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
		$actual = $title->canHaveTalkPage();
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
				function ( Title $title, &$result ) {
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
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'InterwikiLoadPrefix' => [
				function ( $prefix, &$iwdata ) {
					if ( $prefix === 'interwiki' ) {
						$iwdata = [
							'iw_url' => 'http://example.com/',
							'iw_local' => 0,
							'iw_trans' => 0,
						];
						return false;
					}
				},
			],
		] );

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
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'interwiki',
			[
				[
					'iw_prefix' => 'de',
					'iw_url' => 'http://de.wikipedia.org/wiki/',
					'iw_api' => 'http://de.wikipedia.org/w/api.php',
					'iw_wikiid' => 'dewiki',
					'iw_local' => 1,
					'iw_trans' => 0,
				],
				[
					'iw_prefix' => 'zz',
					'iw_url' => 'http://zzwiki.org/wiki/',
					'iw_api' => 'http://zzwiki.org/w/api.php',
					'iw_wikiid' => 'zzwiki',
					'iw_local' => 0,
					'iw_trans' => 0,
				],
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$title = Title::newFromText( $titleStr );
		self::assertEquals( $expected, $title->getFragmentForURL() );

		$dbw->delete( 'interwiki', '*', __METHOD__ );
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
		yield [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Main Page' ),
			true
		];
		yield [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Not The Main Page' ),
			false
		];
		yield [
			Title::newFromText( 'Main Page' ),
			Title::newFromText( 'Project:Main Page' ),
			false
		];
		yield [
			Title::newFromText( 'File:Example.png' ),
			Title::newFromText( 'Image:Example.png' ),
			true
		];
		yield [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Special:Version' ),
			true
		];
		yield [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Special:Recentchanges' ),
			false
		];
		yield [
			Title::newFromText( 'Special:Version' ),
			Title::newFromText( 'Main Page' ),
			false
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			true
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Bar', '', '' ),
			false
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_TALK, 'Foo', '', '' ),
			false
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			true
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', 'Baz', '' ),
			true
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', 'Bar', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			true
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			true
		];
		yield [
			Title::makeTitle( NS_MAIN, 'Foo', '', '' ),
			Title::makeTitle( NS_MAIN, 'Foo', '', 'baz' ),
			false
		];
	}

	/**
	 * @covers Title::equals
	 * @dataProvider provideEquals
	 */
	public function testEquals( Title $firstValue, /* LinkTarget */ $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->equals( $secondValue )
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
		$this->assertEquals(
			$title->getRestrictionExpiry( 'create' ),
			'infinity'
		);
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
		$title->mRestrictionsLoaded = false;
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
	 * @covers Title::getRestrictions
	 */
	public function testGetRestrictions() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$title->mRestrictions = [
			'a' => [ 'sysop' ],
			'b' => [ 'sysop' ],
			'c' => [ 'sysop' ]
		];
		$title->mRestrictionsLoaded = true;
		$this->assertArrayEquals( [ 'sysop' ], $title->getRestrictions( 'a' ) );
		$this->assertArrayEquals( [], $title->getRestrictions( 'error' ) );
		// TODO: maybe test if loadRestrictionsFromRows() is called?
	}

	/**
	 * @covers Title::getAllRestrictions
	 */
	public function testGetAllRestrictions() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$title->mRestrictions = [
			'a' => [ 'sysop' ],
			'b' => [ 'sysop' ],
			'c' => [ 'sysop' ]
		];
		$title->mRestrictionsLoaded = true;
		$this->assertArrayEquals(
			$title->mRestrictions,
			$title->getAllRestrictions()
		);
	}

	/**
	 * @covers Title::getRestrictionExpiry
	 */
	public function testGetRestrictionExpiry() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$reflection = new ReflectionClass( $title );
		$reflection_property = $reflection->getProperty( 'mRestrictionsExpiry' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $title, [
			'a' => 'infinity', 'b' => 'infinity', 'c' => 'infinity'
		] );
		$title->mRestrictionsLoaded = true;
		$this->assertSame( 'infinity', $title->getRestrictionExpiry( 'a' ) );
		$this->assertArrayEquals( [], $title->getRestrictions( 'error' ) );
	}

	/**
	 * @covers Title::getTitleProtection
	 */
	public function testGetTitleProtection() {
		$title = $this->getNonexistingTestPage( 'UTest1' )->getTitle();
		$title->mTitleProtection = false;
		$this->assertFalse(

			$title->getTitleProtection()
		);
	}

	/**
	 * @covers Title::isSemiProtected
	 */
	public function testIsSemiProtected() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$title->mRestrictions = [
			'edit' => [ 'sysop' ]
		];
		$this->setMwGlobals( [
			'wgSemiprotectedRestrictionLevels' => [ 'autoconfirmed' ],
			'wgRestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ]
		] );
		$this->assertFalse(

			$title->isSemiProtected( 'edit' )
		);
		$title->mRestrictions = [
			'edit' => [ 'autoconfirmed' ]
		];
		$this->assertTrue(

			$title->isSemiProtected( 'edit' )
		);
	}

	/**
	 * @covers Title::deleteTitleProtection
	 */
	public function testDeleteTitleProtection() {
		$title = $this->getExistingTestPage( 'UTest1' )->getTitle();
		$this->assertFalse(

			$title->getTitleProtection()
		);
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
		$title->mRestrictions = [
			'edit' => [ 'sysop' ]
		];
		$this->assertFalse(

			$title->isProtected( 'edit' )
		);
		$title->mRestrictions = [
			'edit' => [ 'test' ]
		];
		$this->assertFalse(

			$title->isProtected( 'edit' )
		);
	}

	/**
	 * @covers Title::isNamespaceProtected
	 */
	public function testIsNamespaceProtected() {
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
		$reflection = new ReflectionClass( $title );
		$reflection_property = $reflection->getProperty( 'mHasCascadingRestrictions' );
		$reflection_property->setAccessible( true );
		$reflection_property->setValue( $title, true );
		$this->assertTrue( $title->isCascadeProtected() );
		$reflection_property->setValue( $title, null );
		$this->assertFalse( $title->isCascadeProtected() );
		$reflection_property->setValue( $title, null );
		$cascade = 1;
		$anotherPage = $this->getExistingTestPage( 'UTest2' );
		$anotherPage->doEditContent( new WikitextContent( '{{:UTest1}}' ), 'test' );
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
		$anotherPage->doEditContent( new WikitextContent( '{{:UTest1}}' ), 'test' );
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
			$result[1],
			[ 'edit' => [ 'sysop' ] ]
		);
		$this->assertTrue(
			array_key_exists( $anotherPage->getTitle()->getArticleID(), $result[0] )
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
}
