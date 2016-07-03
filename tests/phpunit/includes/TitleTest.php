<?php

/**
 * @group Database
 * @group Title
 */
class TitleTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );
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
		$this->assertInstanceOf( 'Title', Title::newFromText( $text ), "Valid: $text" );
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
			"Bug 31100 regression check: Title->fixSpecialName() should preserve parameter"
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
	 * Auth-less test of Title::isValidMoveOperation
	 *
	 * @group Database
	 * @param string $source
	 * @param string $target
	 * @param array|string|bool $expected Required error
	 * @dataProvider provideTestIsValidMoveOperation
	 * @covers Title::isValidMoveOperation
	 * @covers Title::validateFileMoveOperation
	 */
	public function testIsValidMoveOperation( $source, $target, $expected ) {
		$this->setMwGlobals( 'wgContentHandlerUseDB', false );
		$title = Title::newFromText( $source );
		$nt = Title::newFromText( $target );
		$errors = $title->isValidMoveOperation( $nt, false );
		if ( $expected === true ) {
			$this->assertTrue( $errors );
		} else {
			$errors = $this->flattenErrorsArray( $errors );
			foreach ( (array)$expected as $error ) {
				$this->assertContains( $error, $errors );
			}
		}
	}

	public static function provideTestIsValidMoveOperation() {
		return [
			// for Title::isValidMoveOperation
			[ 'Some page', '', 'badtitletext' ],
			[ 'Test', 'Test', 'selfmove' ],
			[ 'Special:FooBar', 'Test', 'immobile-source-namespace' ],
			[ 'Test', 'Special:FooBar', 'immobile-target-namespace' ],
			[ 'MediaWiki:Common.js', 'Help:Some wikitext page', 'bad-target-model' ],
			[ 'Page', 'File:Test.jpg', 'nonfile-cannot-move-to-file' ],
			// for Title::validateFileMoveOperation
			[ 'File:Test.jpg', 'Page', 'imagenocrossnamespace' ],
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
	 * @covers Title::checkReadPermissions
	 * @dataProvider dataWgWhitelistReadRegexp
	 */
	public function testWgWhitelistReadRegexp( $whitelistRegexp, $source, $action, $expected ) {
		// $wgWhitelistReadRegexp must be an array. Since the provided test cases
		// usually have only one regex, it is more concise to write the lonely regex
		// as a string. Thus we cast to an array() to honor $wgWhitelistReadRegexp
		// type requisite.
		if ( is_string( $whitelistRegexp ) ) {
			$whitelistRegexp = [ $whitelistRegexp ];
		}

		$this->setMwGlobals( [
			// So User::isEveryoneAllowed( 'read' ) === false
			'wgGroupPermissions' => [ '*' => [ 'read' => false ] ],
			'wgWhitelistRead' => [ 'some random non sense title' ],
			'wgWhitelistReadRegexp' => $whitelistRegexp,
		] );

		$title = Title::newFromDBkey( $source );

		// New anonymous user with no rights
		$user = new User;
		$user->mRights = [];
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
		$this->assertInstanceOf( 'Title', $title,
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
		# - wgContLang (expected in most case)
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
		$this->assertEquals( $expected,
			$title->getBaseText(),
			$msg
		);
	}

	public static function provideBaseTitleCases() {
		return [
			# Title, expected base, optional message
			[ 'User:John_Doe/subOne/subTwo', 'John Doe/subOne' ],
			[ 'User:Foo/Bar/Baz', 'Foo/Bar' ],
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

	public static function provideRootTitleCases() {
		return [
			# Title, expected base, optional message
			[ 'User:John_Doe/subOne/subTwo', 'John Doe' ],
			[ 'User:Foo/Bar/Baz', 'Foo' ],
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
			[ 'User:John_Doe/subOne/subTwo', 'subTwo' ],
			[ 'User:John_Doe/subOne', 'subOne' ],
		];
	}

	public static function provideNewFromTitleValue() {
		return [
			[ new TitleValue( NS_MAIN, 'Foo' ) ],
			[ new TitleValue( NS_MAIN, 'Foo', 'bar' ) ],
			[ new TitleValue( NS_USER, 'Hansi_Maier' ) ],
		];
	}

	/**
	 * @dataProvider provideNewFromTitleValue
	 */
	public function testNewFromTitleValue( TitleValue $value ) {
		$title = Title::newFromTitleValue( $value );

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $dbkey, $title->getDBkey() );
		$this->assertEquals( $value->getNamespace(), $title->getNamespace() );
		$this->assertEquals( $value->getFragment(), $title->getFragment() );
	}

	public static function provideGetTitleValue() {
		return [
			[ 'Foo' ],
			[ 'Foo#bar' ],
			[ 'User:Hansi_Maier' ],
		];
	}

	/**
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
		$linkCache = LinkCache::singleton();

		$article = new Article( $title );
		$page = $article->getPage();
		$page->doEditContent( new WikitextContent( 'Some [[link]]' ), 'summary' );

		// Tell Title it doesn't know whether it exists
		$title->mArticleID = -1;

		// Tell the link cache it doesn't exists when it really does
		$linkCache->clearLink( $title );
		$linkCache->addBadLinkObj( $title );

		$this->assertEquals(
			false,
			$title->exists(),
			'exists() should rely on link cache unless GAID_FOR_UPDATE is used'
		);
		$this->assertEquals(
			true,
			$title->exists( Title::GAID_FOR_UPDATE ),
			'exists() should re-query database when GAID_FOR_UPDATE is used'
		);
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
}
