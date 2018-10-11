<?php

class LanguageConverterTest extends MediaWikiLangTestCase {
	/** @var LanguageToTest */
	protected $lang = null;
	/** @var TestConverter */
	protected $lc = null;

	protected function setUp() {
		parent::setUp();

		$this->setContentLang( 'tg' );

		$this->setMwGlobals( [
			'wgDefaultLanguageVariant' => false,
			'wgRequest' => new FauxRequest( [] ),
			'wgUser' => new User,
		] );

		$this->lang = new LanguageToTest();
		$this->lc = new TestConverter(
			$this->lang, 'tg',
			# Adding 'sgs' as a variant to ensure we handle deprecated codes
			# adding 'simple' as a variant to ensure we handle non BCP 47 codes
			[ 'tg', 'tg-latn', 'sgs', 'simple' ]
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
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantUrl() {
		global $wgRequest;
		$wgRequest->setVal( 'variant', 'tg-latn' );

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantUrlDeprecated() {
		global $wgRequest;
		$wgRequest->setVal( 'variant', 'bat-smg' );

		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantUrlBCP47() {
		global $wgRequest;
		$wgRequest->setVal( 'variant', 'en-simple' );

		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
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
	public function testGetPreferredVariantHeadersBCP47() {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', 'en-simple' );

		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
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
	 */
	public function testGetPreferredVariantUserOptionDeprecated() {
		global $wgUser;

		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant', 'bat-smg' );

		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantUserOptionBCP47() {
		global $wgUser;

		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant', 'en-simple' );

		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguage() {
		global $wgUser;

		$this->setContentLang( 'en' );
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
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguageDeprecated() {
		global $wgUser;

		$this->setContentLang( 'en' );
		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant-tg', 'bat-smg' );

		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguageBCP47() {
		global $wgUser;

		$this->setContentLang( 'en' );
		$wgUser = new User;
		$wgUser->load(); // from 'defaults'
		$wgUser->mId = 1;
		$wgUser->mDataLoaded = true;
		$wgUser->mOptionsLoaded = true;
		$wgUser->setOption( 'variant-tg', 'en-simple' );

		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantHeaderUserVsUrl() {
		global $wgRequest, $wgUser;

		$this->setContentLang( 'tg-latn' );
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
	 */
	public function testGetPreferredVariantDefaultLanguageVariantDeprecated() {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = 'bat-smg';
		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVariantBCP47() {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = 'en-simple';
		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVsUrlVariant() {
		global $wgDefaultLanguageVariant, $wgRequest;

		$this->setContentLang( 'tg-latn' );
		$wgDefaultLanguageVariant = 'tg';
		$wgRequest->setVal( 'variant', null );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * Test exhausting pcre.backtrack_limit
	 *
	 * @covers LanguageConverter::autoConvert
	 */
	public function testAutoConvertT124404() {
		$testString = '';
		for ( $i = 0; $i < 1000; $i++ ) {
			$testString .= 'xxx xxx xxx';
		}
		$testString .= "\n<big id='в'></big>";
		$this->setIniSetting( 'pcre.backtrack_limit', 200 );
		$result = $this->lc->autoConvert( $testString, 'tg-latn' );
		// The в in the id attribute should not get converted to a v
		$this->assertFalse(
			strpos( $result, 'v' ),
			"в converted to v despite being in attribue"
		);
	}
}

/**
 * Test converter (from Tajiki to latin orthography)
 */
class TestConverter extends LanguageConverter {
	private $table = [
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
	];

	function loadDefaultTables() {
		$this->mTables = [
			'sgs' => new ReplacementArray(),
			'simple' => new ReplacementArray(),
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg' => new ReplacementArray()
		];
	}
}

class LanguageToTest extends Language {
	function __construct() {
		parent::__construct();
		$variants = [ 'tg', 'tg-latn' ];
		$this->mConverter = new TestConverter( $this, 'tg', $variants );
	}
}
