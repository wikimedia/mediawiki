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
	
	function flattenErrorsArray( $errors ) {
		$result = array();
		foreach ( $errors as $error ) {
			$result[] = $error[0];
		}
		return $result;
	}
	
	function dataTestIsValidMoveOperation() {
		return array( 
			array( 'Test', 'Test', 'selfmove' ),
			array( 'File:Test.jpg', 'Page', 'imagenocrossnamespace' )
		);
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
