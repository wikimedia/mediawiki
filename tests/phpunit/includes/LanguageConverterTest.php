<?php

class LanguageConverterTest extends MediaWikiLangTestCase {
	/** @var LanguageToTest */
	protected $lang = null;
	/** @var TestConverter */
	protected $lc = null;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgContLang' => Language::factory( 'tg' ),
			'wgLanguageCode' => 'tg',
			'wgDefaultLanguageVariant' => false,
			'wgMemc' => new EmptyBagOStuff,
			'wgRequest' => new FauxRequest( array() ),
			'wgUser' => new User,
		) );

		$this->lang = new LanguageToTest();
		$this->lc = new TestConverter(
			$this->lang, 'tg',
			array( 'tg', 'tg-latn' )
		);
	}

	protected function tearDown() {
		unset( $this->lc );
		unset( $this->lang );

		parent::tearDown();
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantDefaults() {
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getHeaderVariant
	 */
	public function testGetPreferredVariantHeaders() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getHeaderVariant
	 */
	public function testGetPreferredVariantHeaderWeight() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg;q=1' );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getHeaderVariant
	 */
	public function testGetPreferredVariantHeaderWeight2() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'tg-latn;q=1' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getHeaderVariant
	 */
	public function testGetPreferredVariantHeaderMulti() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'en, tg-latn;q=1' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantUserOption() {
		global $wgUser;

		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguage() {
		global $wgContLang, $wgUser;

		$wgContLang = Language::factory( 'en' );
		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant-tg', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantHeaderUserVsUrl() {
		global $wgContLang, $wgRequest, $wgUser;

		$wgContLang = Language::factory( 'tg-latn' );
		$wgRequest->setVal( 'variant', 'tg' );
		$wgUser = User::newFromId( "admin" );
		$wgUser->setId( 1 );
		$wgUser->mFrom = 'defaults';
		$wgUser->mOptionsLoaded = true;
		// The user's data is ignored because the variant is set in the URL.
		$wgUser->setOption( 'variant', 'tg-latn' );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVariant() {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = 'tg-latn';
		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVsUrlVariant() {
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
			'tg' => new ReplacementArray()
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
