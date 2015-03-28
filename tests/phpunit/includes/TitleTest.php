<?php

/**
 * @group Title
 */
class TitleTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgContLang' => Language::factory( 'en' ),
			// User language
			'wgLang' => Language::factory( 'en' ),
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
		) );
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
		return array(
			array( 'Sandbox' ),
			array( 'A "B"' ),
			array( 'A \'B\'' ),
			array( '.com' ),
			array( '~' ),
			array( '#' ),
			array( '"' ),
			array( '\'' ),
			array( 'Talk:Sandbox' ),
			array( 'Talk:Foo:Sandbox' ),
			array( 'File:Example.svg' ),
			array( 'File_talk:Example.svg' ),
			array( 'Foo/.../Sandbox' ),
			array( 'Sandbox/...' ),
			array( 'A~~' ),
			array( ':A' ),
			// Length is 256 total, but only title part matters
			array( 'Category:' . str_repeat( 'x', 248 ) ),
			array( str_repeat( 'x', 252 ) ),
			// interwiki prefix
			array( 'localtestiw: #anchor' ),
			array( 'localtestiw:' ),
			array( 'localtestiw:foo' ),
			array( 'localtestiw: foo # anchor' ),
			array( 'localtestiw: Talk: Sandbox # anchor' ),
			array( 'remotetestiw:' ),
			array( 'remotetestiw: Talk: # anchor' ),
			array( 'remotetestiw: #bar' ),
			array( 'remotetestiw: Talk:' ),
			array( 'remotetestiw: Talk: Foo' ),
			array( 'localtestiw:remotetestiw:' ),
			array( 'localtestiw:remotetestiw:foo' )
		);
	}

	public static function provideInvalidSecureAndSplit() {
		return array(
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
			//'A &eacute; B',
			//'A &#233; B',
			//'A &#x00E9; B',
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
			array( 'Talk:#' ),
			array( 'Category: ' ),
			array( 'Category: #bar' ),
			// interwiki prefix
			array( 'localtestiw: Talk: # anchor' ),
			array( 'localtestiw: Talk:' )
		);
	}

	private function secureAndSplitGlobals() {
		$this->setMwGlobals( array(
			'wgLocalInterwikis' => array( 'localtestiw' ),
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
		));
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
	public function testSecureAndSplitInvalid( $text ) {
		$this->secureAndSplitGlobals();
		$this->assertNull( Title::newFromText( $text ), "Invalid: $text" );
	}

	public static function provideConvertByteClassToUnicodeClass() {
		return array(
			array(
				' %!"$&\'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF+',
				' %!"$&\'()*,\\-./0-9:;=?@A-Z\\\\\\^_`a-z~+\\u0080-\\uFFFF',
			),
			array(
				'QWERTYf-\\xFF+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			),
			array(
				'QWERTY\\x66-\\xFD+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			),
			array(
				'QWERTYf-y+',
				'QWERTYf-y+',
			),
			array(
				'QWERTYf-\\x80+',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			),
			array(
				'QWERTY\\x66-\\x80+\\x23',
				'QWERTYf-\\x7F+#\\u0080-\\uFFFF',
			),
			array(
				'QWERTY\\x66-\\x80+\\xD3',
				'QWERTYf-\\x7F+\\u0080-\\uFFFF',
			),
			array(
				'\\\\\\x99',
				'\\\\\\u0080-\\uFFFF',
			),
			array(
				'-\\x99',
				'\\-\\u0080-\\uFFFF',
			),
			array(
				'QWERTY\\-\\x99',
				'QWERTY\\-\\u0080-\\uFFFF',
			),
			array(
				'\\\\x99',
				'\\\\x99',
			),
			array(
				'A-\\x9F',
				'A-\\x7F\\u0080-\\uFFFF',
			),
			array(
				'\\x66-\\x77QWERTY\\x88-\\x91FXZ',
				'f-wQWERTYFXZ\\u0080-\\uFFFF',
			),
			array(
				'\\x66-\\x99QWERTY\\xAA-\\xEEFXZ',
				'f-\\x7FQWERTYFXZ\\u0080-\\uFFFF',
			),
		);
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
		return array(
			array( 'Special:Version', null ),
			array( 'Special:Version/', '' ),
			array( 'Special:Version/param', 'param' ),
		);
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
		return array(
			// for Title::isValidMoveOperation
			array( 'Some page', '', 'badtitletext' ),
			array( 'Test', 'Test', 'selfmove' ),
			array( 'Special:FooBar', 'Test', 'immobile-source-namespace' ),
			array( 'Test', 'Special:FooBar', 'immobile-target-namespace' ),
			array( 'MediaWiki:Common.js', 'Help:Some wikitext page', 'bad-target-model' ),
			array( 'Page', 'File:Test.jpg', 'nonfile-cannot-move-to-file' ),
			// for Title::validateFileMoveOperation
			array( 'File:Test.jpg', 'Page', 'imagenocrossnamespace' ),
		);
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
			$whitelistRegexp = array( $whitelistRegexp );
		}

		$this->setMwGlobals( array(
			// So User::isEveryoneAllowed( 'read' ) === false
			'wgGroupPermissions' => array( '*' => array( 'read' => false ) ),
			'wgWhitelistRead' => array( 'some random non sense title' ),
			'wgWhitelistReadRegexp' => $whitelistRegexp,
		) );

		$title = Title::newFromDBkey( $source );

		// New anonymous user with no rights
		$user = new User;
		$user->mRights = array();
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

		return array(
			// Everything, if this doesn't work, we're really in trouble
			array( '/.*/', 'Main_Page', 'read', $ALLOWED ),
			array( '/.*/', 'Main_Page', 'edit', $DISALLOWED ),

			// We validate against the title name, not the db key
			array( '/^Main_Page$/', 'Main_Page', 'read', $DISALLOWED ),
			// Main page
			array( '/^Main/', 'Main_Page', 'read', $ALLOWED ),
			array( '/^Main.*/', 'Main_Page', 'read', $ALLOWED ),
			// With spaces
			array( '/Mic\sCheck/', 'Mic Check', 'read', $ALLOWED ),
			// Unicode multibyte
			// ...without unicode modifier
			array( '/Unicode Test . Yes/', 'Unicode Test Ñ Yes', 'read', $DISALLOWED ),
			// ...with unicode modifier
			array( '/Unicode Test . Yes/u', 'Unicode Test Ñ Yes', 'read', $ALLOWED ),
			// Case insensitive
			array( '/MiC ChEcK/', 'mic check', 'read', $DISALLOWED ),
			array( '/MiC ChEcK/i', 'mic check', 'read', $ALLOWED ),

			// From DefaultSettings.php:
			array( "@^UsEr.*@i", 'User is banned', 'read', $ALLOWED ),
			array( "@^UsEr.*@i", 'User:John Doe', 'read', $ALLOWED ),

			// With namespaces:
			array( '/^Special:NewPages$/', 'Special:NewPages', 'read', $ALLOWED ),
			array( null, 'Special:Newpages', 'read', $DISALLOWED ),

		);
	}

	public function flattenErrorsArray( $errors ) {
		$result = array();
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
		$this->setMwGlobals( array(
			'wgLanguageCode' => $contLang,
			'wgContLang' => Language::factory( $contLang ),
			'wgLang' => Language::factory( $lang ),
			'wgDefaultLanguageVariant' => $variant,
			'wgAllowUserJs' => true,
		) );

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
		return array(
			array( 'fr', 'Help:I_need_somebody', 'fr', 'fr', false ),
			array( 'es', 'Help:I_need_somebody', 'es', 'zh-tw', false ),
			array( 'zh', 'Help:I_need_somebody', 'zh', 'zh-tw', false ),

			array( 'es', 'Help:I_need_somebody', 'es', 'zh-tw', 'zh-cn' ),
			array( 'es', 'MediaWiki:About', 'es', 'zh-tw', 'zh-cn' ),
			array( 'es', 'MediaWiki:About/', 'es', 'zh-tw', 'zh-cn' ),
			array( 'de', 'MediaWiki:About/de', 'es', 'zh-tw', 'zh-cn' ),
			array( 'en', 'MediaWiki:Common.js', 'es', 'zh-tw', 'zh-cn' ),
			array( 'en', 'MediaWiki:Common.css', 'es', 'zh-tw', 'zh-cn' ),
			array( 'en', 'User:JohnDoe/Common.js', 'es', 'zh-tw', 'zh-cn' ),
			array( 'en', 'User:JohnDoe/Monobook.css', 'es', 'zh-tw', 'zh-cn' ),

			array( 'zh-cn', 'Help:I_need_somebody', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh', 'MediaWiki:About', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh', 'MediaWiki:About/', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'de', 'MediaWiki:About/de', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh-cn', 'MediaWiki:About/zh-cn', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh-tw', 'MediaWiki:About/zh-tw', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'en', 'MediaWiki:Common.js', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'en', 'MediaWiki:Common.css', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'en', 'User:JohnDoe/Common.js', 'zh', 'zh-tw', 'zh-cn' ),
			array( 'en', 'User:JohnDoe/Monobook.css', 'zh', 'zh-tw', 'zh-cn' ),

			array( 'zh-tw', 'Special:NewPages', 'es', 'zh-tw', 'zh-cn' ),
			array( 'zh-tw', 'Special:NewPages', 'zh', 'zh-tw', 'zh-cn' ),

		);
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
		return array(
			# Title, expected base, optional message
			array( 'User:John_Doe/subOne/subTwo', 'John Doe/subOne' ),
			array( 'User:Foo/Bar/Baz', 'Foo/Bar' ),
		);
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
		return array(
			# Title, expected base, optional message
			array( 'User:John_Doe/subOne/subTwo', 'John Doe' ),
			array( 'User:Foo/Bar/Baz', 'Foo' ),
		);
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
		return array(
			# Title, expected base, optional message
			array( 'User:John_Doe/subOne/subTwo', 'subTwo' ),
			array( 'User:John_Doe/subOne', 'subOne' ),
		);
	}

	public static function provideNewFromTitleValue() {
		return array(
			array( new TitleValue( NS_MAIN, 'Foo' ) ),
			array( new TitleValue( NS_MAIN, 'Foo', 'bar' ) ),
			array( new TitleValue( NS_USER, 'Hansi_Maier' ) ),
		);
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
		return array(
			array( 'Foo' ),
			array( 'Foo#bar' ),
			array( 'User:Hansi_Maier' ),
		);
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
		return array(
			array( 'Foo', '' ),
			array( 'Foo#bar', 'bar' ),
			array( 'Foo#bär', 'bär' ),

			// Inner whitespace is normalized
			array( 'Foo#bar_bar', 'bar bar' ),
			array( 'Foo#bar bar', 'bar bar' ),
			array( 'Foo#bar   bar', 'bar bar' ),

			// Leading whitespace is kept, trailing whitespace is trimmed.
			// XXX: Is this really want we want?
			array( 'Foo#_bar_bar_', ' bar bar' ),
			array( 'Foo# bar bar ', ' bar bar' ),
		);
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
		return array(
			array( 'Some nonexistent page', false ),
			array( 'UTPage', false ),
			array( '#test', true ),
			array( 'Special:BlankPage', true ),
			array( 'Special:SomeNonexistentSpecialPage', false ),
			array( 'MediaWiki:Parentheses', true ),
			array( 'MediaWiki:Some nonexistent message', false ),
		);
	}

	/**
	 * @covers Title::isAlwaysKnown
	 */
	public function testIsAlwaysKnownOnInterwiki() {
		$title = Title::makeTitle( NS_MAIN, 'Interwiki link', '', 'externalwiki' );
		$this->assertTrue( $title->isAlwaysKnown() );
	}
}
