<?php

class LanguageConverterTest extends PHPUnit_Framework_TestCase {
	protected $lang = null;
	protected $lc = null;

	function setUp() {
		global $wgMemc, $wgRequest, $wgUser, $wgContLang;

		$wgUser = new User;
		$wgRequest = new FauxRequest( array() );
		$wgMemc = new FakeMemCachedClient;
		$wgContLang = Language::factory( 'tg' );
		$this->lang = new LanguageTest();
		$this->lc = new TestConverter( $this->lang, 'tg',
									   array( 'tg', 'tg-latn' ) );
	}

	function tearDown() {
		global $wgMemc, $wgContLang;
		unset( $wgMemc );
		unset( $this->lc );
		unset( $this->lang );
		$wgContLang = null;
	}

	function testGetPreferredVariantDefaults() {
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantHeaders() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg-latn' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantHeaderWeight() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg;q=1' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantHeaderWeight2() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg-latn;q=1' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantHeaderMulti() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'en, tg-latn;q=1' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantUserOption() {
		global $wgUser;

		$wgUser = new User;
		$wgUser->setId( 1 );
		$wgUser->mDataLoaded = true;
		$wgUser->setOption( 'variant', 'tg-latn' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true,  false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true,  true ) );
	}

	function testGetPreferredVariantHeaderUserVsUrl() {
		global $wgRequest, $wgUser, $wgContLang;

		$wgContLang = Language::factory( 'tg-latn' );
		$wgRequest->setVal( 'variant', 'tg' );
		$wgUser = User::newFromId( "admin" );
		$wgUser->setId( 1 );
		$wgUser->setOption( 'variant', 'tg-latn' ); // The user's data is ignored
												  // because the variant is set in the URL.
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true,  false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( true,  true ) );
	}


	function testGetPreferredVariantDefaultLanguageVariant() {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = 'tg-latn';
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true, true ) );
	}

	function testGetPreferredVariantDefaultLanguageVsUrlVariant() {
		global $wgDefaultLanguageVariant, $wgRequest, $wgContLang;

		$wgContLang = Language::factory( 'tg-latn' );
		$wgDefaultLanguageVariant = 'tg';
		$wgRequest->setVal( 'variant', null );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, false ) );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( false, true ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true, false ) );
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( true, true ) );
	}
}

/**
 * Test converter (from Tajiki to latin orthography)
 */
class TestConverter extends LanguageConverter {
	private $table = array(
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg'      => new ReplacementArray()
		);
	}

}

class LanguageTest extends Language {
	function __construct() {
		parent::__construct();
		$variants = array( 'tg', 'tg-latn' );
		$this->mConverter = new TestConverter( $this, 'tg', $variants );
	}
}
