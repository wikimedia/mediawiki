<?php

use MediaWiki\Linker\LinkTarget;

/**
 * @group Language
 */
class LanguageConverterTest extends MediaWikiLangTestCase {

	/** @var Language */
	protected $lang;

	/** @var DummyConverter */
	protected $lc;

	protected function setUp() : void {
		parent::setUp();
		$this->setContentLang( 'tg' );

		$this->setMwGlobals( [
			'wgDefaultLanguageVariant' => false,
			'wgRequest' => new FauxRequest( [] ),
			'wgUser' => new User,
		] );

		$this->lang = $this->createMock( Language::class );
		$this->lang->method( 'getNsText' )->with( NS_MEDIAWIKI )->willReturn( 'MediaWiki' );
		$this->lang->method( 'ucfirst' )->will( $this->returnCallback( function ( $s ) {
			return ucfirst( $s );
		} ) );
		$this->lang->expects( $this->never() )
			->method( $this->anythingBut( 'factory', 'getNsText', 'ucfirst' ) );
		$this->lc = new DummyConverter(
			$this->lang, 'tg',
			# Adding 'sgs' as a variant to ensure we handle deprecated codes
			# adding 'simple' as a variant to ensure we handle non BCP 47 codes
			[ 'tg', 'tg-latn', 'sgs', 'simple' ]
		);
	}

	protected function tearDown() : void {
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

		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant', 'tg-latn' );

		$wgUser = $user;

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantUserOptionDeprecated() {
		global $wgUser;

		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant', 'bat-smg' );

		$wgUser = $user;

		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantUserOptionBCP47() {
		global $wgUser;

		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant', 'en-simple' );

		$wgUser = $user;

		$this->assertEquals( 'simple', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguage() {
		global $wgUser;

		$this->setContentLang( 'en' );
		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant-tg', 'tg-latn' );

		$wgUser = $user;

		$this->assertEquals( 'tg-latn', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguageDeprecated() {
		global $wgUser;

		$this->setContentLang( 'en' );
		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant-tg', 'bat-smg' );

		$wgUser = $user;

		$this->assertEquals( 'sgs', $this->lc->getPreferredVariant() );
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 */
	public function testGetPreferredVariantUserOptionForForeignLanguageBCP47() {
		global $wgUser;

		$this->setContentLang( 'en' );
		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$user->setOption( 'variant-tg', 'en-simple' );

		$wgUser = $user;

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
		$user = User::newFromId( "admin" );
		$user->setId( 1 );
		$user->mFrom = 'defaults';
		// The user's data is ignored because the variant is set in the URL.
		$user->setOption( 'variant', 'tg-latn' );

		$wgUser = $user;

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

	/**
	 * @dataProvider provideTitlesToConvert
	 * @covers       LanguageConverter::convertTitle
	 *
	 * @param LinkTarget $linkTarget LinkTarget to convert
	 * @param string $expected
	 */
	public function testConvertTitle( LinkTarget $linkTarget, string $expected ) : void {
		$actual = $this->lc->convertTitle( $linkTarget );
		$this->assertSame( $expected, $actual );
	}

	public function provideTitlesToConvert() : array {
		return [
			'Title FromText default' => [
				Title::newFromText( 'Dummy_title' ),
				'Dummy title',
			],
			'Title FromText with NS' => [
				Title::newFromText( 'Dummy_title', NS_FILE ),
				'Акс:Dummy title',
			],
			'Title MainPage default' => [
				Title::newMainPage(),
				'Main Page',
			],
			'Title MainPage with MessageLocalizer' => [
				Title::newMainPage( new MockMessageLocalizer() ),
				'Main Page',
			],
			'TitleValue' => [
				new TitleValue( NS_FILE, 'Dummy page' ),
				'Акс:Dummy page',
			],
		];
	}
}
