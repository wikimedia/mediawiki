<?php

class LanguageConverterTest extends MediaWikiLangTestCase {
	protected $lang = null;
	protected $lc = null;
	protected $context = null;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgContLang' => Language::factory( 'tg' ),
			'wgLanguageCode' => 'tg',
			'wgDefaultLanguageVariant' => false,
			'wgMemc' => new EmptyBagOStuff,
		) );

		$this->lang = new LanguageToTest();
		$this->lc = new TestConverter(
			$this->lang, 'tg',
			array( 'tg', 'tg-latn' )
		);
		$this->context = new DerivativeContext( RequestContext::getMain() );
		$this->context->setRequest( new FauxRequest( array() ) );
		$this->context->setUser( new User );
	}

	protected function tearDown() {
		unset( $this->lc );
		unset( $this->lang );

		parent::tearDown();
	}

	function testGetPreferredVariantDefaults() {
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	function testGetPreferredVariantHeaders() {
		$this->context->getRequest()->setHeader( 'Accept-Language', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( $this->context ) );
	}

	function testGetPreferredVariantHeaderWeight() {
		$this->context->getRequest()->setHeader( 'Accept-Language', 'tg;q=1' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( $this->context ) );
	}

	function testGetPreferredVariantHeaderWeight2() {
		$this->context->getRequest()->setHeader( 'Accept-Language', 'tg-latn;q=1' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( $this->context ) );
	}

	function testGetPreferredVariantHeaderMulti() {
		$this->context->getRequest()->setHeader( 'Accept-Language', 'en, tg-latn;q=1' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( $this->context ) );
	}

	function testGetPreferredVariantUserOption() {
		$user = $this->context->getUser();
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->mOptionsLoaded = true;
		$user->setOption( 'variant', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant( $this->context ) );
	}

	function testGetPreferredVariantHeaderUserVsUrl() {
		global $wgContLang;

		$wgContLang = Language::factory( 'tg-latn' );
		$this->context->getRequest()->setVal( 'variant', 'tg' );

		$user = $this->context->getUser();
		$user = User::newFromId( "admin" );
		$user->setId( 1 );
		$user->mFrom = 'defaults';
		$user->mOptionsLoaded = true;
		// The user's data is ignored
		// because the variant is set in the URL.
		$user->setOption( 'variant', 'tg-latn' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant( $this->context ) );
	}


	function testGetPreferredVariantDefaultLanguageVariant() {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = 'tg-latn';
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	function testGetPreferredVariantDefaultLanguageVsUrlVariant() {
		global $wgDefaultLanguageVariant, $wgRequest, $wgContLang;

		$wgContLang = Language::factory( 'tg-latn' );
		$wgDefaultLanguageVariant = 'tg';
		$wgRequest->setVal( 'variant', null );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
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

class LanguageToTest extends Language {
	function __construct() {
		parent::__construct();
		$variants = array( 'tg', 'tg-latn' );
		$this->mConverter = new TestConverter( $this, 'tg', $variants );
	}
}
