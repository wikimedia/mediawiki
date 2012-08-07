<?php

class TitleTest extends MediaWikiTestCase {
	function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
				$this->assertFalse( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is not a valid titlechar" );
			} else {
				$this->assertTrue( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is a valid titlechar" );
			}
		}
	}

	/**
	 * @dataProvider dataBug31100
	 */
	function testBug31100FixSpecialName( $text, $expectedParam ) {
		$title = Title::newFromText( $text );
		$fixed = $title->fixSpecialName();
		$stuff = explode( '/', $fixed->getDbKey(), 2 );
		if ( count( $stuff ) == 2 ) {
			$par = $stuff[1];
		} else {
			$par = null;
		}
		$this->assertEquals( $expectedParam, $par, "Bug 31100 regression check: Title->fixSpecialName() should preserve parameter" );
	}

	function dataBug31100() {
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
	 * @param array|string|true $expected Required error
	 * @dataProvider dataTestIsValidMoveOperation
	 */
	function testIsValidMoveOperation( $source, $target, $expected ) {
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
	
	/**
	 * Provides test parameter values for testIsValidMoveOperation()
	 */
	function dataTestIsValidMoveOperation() {
		return array(
			array( 'Test', 'Test', 'selfmove' ),
			array( 'File:Test.jpg', 'Page', 'imagenocrossnamespace' )
		);
	}

	/**
	 * Auth-less test of Title::userCan
	 *
	 * @param array $whitelistRegexp
	 * @param string $source
	 * @param string $action
	 * @param string $user
	 * @param string $doExpensiveQueries
	 * @param array|string|true $expected Required error
	 * @dataProvider dataWgWhitelistReadRegexp
	 */
	function testWgWhitelistReadRegexp($whitelistRegexp, $source, $action, $user, $doExpensiveQueries, $expected) {
		//$title = Title::newFromText( $source );
		$title = Title::newFromDBkey( $source );

		global $wgGroupPermissions;
		$oldPermissions = $wgGroupPermissions;
		// Disallow all so we can ensure our regex works
		$wgGroupPermissions=array();
		$wgGroupPermissions['*']['read'] = false;

		global $wgWhitelistRead;
		$oldWhitelist = $wgWhitelistRead;
		// Undo any LocalSettings explicite whitelists so
		// they won't cause a failing test to succeed
		$wgWhitelistRead=array();

		global $wgWhitelistReadRegexp;
		$oldWhitelistRegexp = $wgWhitelistReadRegexp;
		$wgWhitelistReadRegexp=$whitelistRegexp ;

		$errors = $title->userCan( $action, $user, $doExpensiveQueries);

		$wgGroupPermissions = $oldPermissions;
		$wgWhitelistRead = $oldWhitelist;
		$wgWhitelistReadRegexp = $oldWhitelistRegexp;
		if ( $expected === true ) {
			$this->assertTrue( $errors );
		} else if ( $expected === false ) {
			$this->assertFalse ($errors );
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
	function dataWgWhitelistReadRegexp() {
		return array(
			// Everything
			// If this doesn't work, we're really in trouble
			array( array('/.*/'), 'Main_Page', 'read', '127.0.0.1', 1, true ),
			// Main page
			array( array('/^Main/'), 'Main_Page', 'read', '127.0.0.1', 1, true ),
			array( array('/^Main.*/'), 'Main_Page', 'read', '127.0.0.1', 1, true ),
			// With spaces
			array( array('/Mic\sCheck/'), 'Mic Check', 'read', '127.0.0.1', 1, true ),
			// Unicode multibyte
			// ...without unicode modifier
			array( array('/Unicode_Test_._Yes/'), 'Unicode_Test_Ñ_Yes', 'read', '127.0.0.1', 1, false ),
			// ...with unicode modifier
			array( array('/Unicode_Test_Ñ_Yes/u'), 'Unicode_Test_Ñ_Yes', 'read', '127.0.0.1', 1, true ),
			// Case insensitive
			array( array('/MiC ChEcK/'), 'mic check', 'read', '127.0.0.1', 1, false ),
			array( array('/MiC ChEcK/i'), 'mic check', 'read', '127.0.0.1', 1, true )
		);
	}

	function flattenErrorsArray( $errors ) {
		$result = array();
		foreach ( $errors as $error ) {
			$result[] = $error[0];
		}
		return $result;
	}
	
	/**
	 * @dataProvider provideCasesForGetpageviewlanguage
	 */
	function testGetpageviewlanguage( $expected, $titleText, $contLang, $lang, $variant, $msg='' ) {
		// Save globals
		global $wgContLang, $wgLang, $wgAllowUserJs, $wgLanguageCode, $wgDefaultLanguageVariant;
		$save['wgContLang']               = $wgContLang;
		$save['wgLang']                   = $wgLang;
		$save['wgAllowUserJs']            = $wgAllowUserJs;
		$save['wgLanguageCode']           = $wgLanguageCode;
		$save['wgDefaultLanguageVariant'] = $wgDefaultLanguageVariant;

		// Setup test environnement:
		$wgContLang = Language::factory( $contLang );
		$wgLang     = Language::factory( $lang );
		# To test out .js titles:
		$wgAllowUserJs = true;
		$wgLanguageCode = $contLang;
		$wgDefaultLanguageVariant = $variant;

		$title = Title::newFromText( $titleText );
		$this->assertInstanceOf( 'Title', $title,
			"Test must be passed a valid title text, you gave '$titleText'"
		);
		$this->assertEquals( $expected,
			$title->getPageViewLanguage()->getCode(),
			$msg
		);

		// Restore globals
		$wgContLang               = $save['wgContLang'];
		$wgLang                   = $save['wgLang'];
		$wgAllowUserJs            = $save['wgAllowUserJs'];
		$wgLanguageCode           = $save['wgLanguageCode'];
		$wgDefaultLanguageVariant = $save['wgDefaultLanguageVariant'];
	}

	function provideCasesForGetpageviewlanguage() {
		# Format:
		# - expected
		# - Title name
		# - wgContLang (expected in most case)
		# - wgLang (on some specific pages)
		# - wgDefaultLanguageVariant
		# - Optional message
		return array(
			array( 'fr', 'Main_page', 'fr', 'fr', false ),
			array( 'es', 'Main_page', 'es', 'zh-tw', false ),
			array( 'zh', 'Main_page', 'zh', 'zh-tw', false ),

			array( 'es',    'Main_page',                 'es', 'zh-tw', 'zh-cn' ),
			array( 'es',    'MediaWiki:About',           'es', 'zh-tw', 'zh-cn' ),
			array( 'es',    'MediaWiki:About/',          'es', 'zh-tw', 'zh-cn' ),
			array( 'de',    'MediaWiki:About/de',        'es', 'zh-tw', 'zh-cn' ),
			array( 'en',    'MediaWiki:Common.js',       'es', 'zh-tw', 'zh-cn' ),
			array( 'en',    'MediaWiki:Common.css',      'es', 'zh-tw', 'zh-cn' ),
			array( 'en',    'User:JohnDoe/Common.js',    'es', 'zh-tw', 'zh-cn' ),
			array( 'en',    'User:JohnDoe/Monobook.css', 'es', 'zh-tw', 'zh-cn' ),

			array( 'zh-cn', 'Main_page',                 'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh',    'MediaWiki:About',           'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh',    'MediaWiki:About/',          'zh', 'zh-tw', 'zh-cn' ),
			array( 'de',    'MediaWiki:About/de',        'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh-cn', 'MediaWiki:About/zh-cn',     'zh', 'zh-tw', 'zh-cn' ),
			array( 'zh-tw', 'MediaWiki:About/zh-tw',     'zh', 'zh-tw', 'zh-cn' ),
			array( 'en',    'MediaWiki:Common.js',       'zh', 'zh-tw', 'zh-cn' ),
			array( 'en',    'MediaWiki:Common.css',      'zh', 'zh-tw', 'zh-cn' ),
			array( 'en',    'User:JohnDoe/Common.js',    'zh', 'zh-tw', 'zh-cn' ),
			array( 'en',    'User:JohnDoe/Monobook.css', 'zh', 'zh-tw', 'zh-cn' ),

			array( 'zh-tw', 'Special:NewPages',       'es', 'zh-tw', 'zh-cn' ),
			array( 'zh-tw', 'Special:NewPages',       'zh', 'zh-tw', 'zh-cn' ),

		);
	}
}
