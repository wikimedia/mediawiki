<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;

/**
 * @group Language
 */
class LanguageConverterTest extends MediaWikiLangTestCase {

	/** @var Language */
	protected $lang;

	/** @var DummyConverter */
	protected $lc;

	/**
	 * @param User $user
	 */
	private function setContextUser( User $user ) {
		// LanguageConverter::getPreferredVariant() reads the user from
		// RequestContext::getMain(), so set it occordingly
		RequestContext::getMain()->setUser( $user );
	}

	protected function setUp(): void {
		parent::setUp();
		$this->setContentLang( 'tg' );

		$this->setMwGlobals( [
			'wgDefaultLanguageVariant' => false,
		] );
		$this->setContextUser( new User );

		$this->lang = $this->createMock( Language::class );
		$this->lang->method( 'getNsText' )->with( NS_MEDIAWIKI )->willReturn( 'MediaWiki' );
		$this->lang->method( 'ucfirst' )->willReturnCallback( 'ucfirst' );
		$this->lang->expects( $this->never() )
			->method( $this->anythingBut( 'factory', 'getNsText', 'ucfirst' ) );
		$this->lc = new DummyConverter( $this->lang );
	}

	protected function tearDown(): void {
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
	 * @dataProvider provideGetPreferredVariant
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariant( $requestVal, $expected ) {
		global $wgRequest;
		$wgRequest->setVal( 'variant', $requestVal );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public function provideGetPreferredVariant() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn' ];
		yield 'deprecated (bat-smg)' => [ 'bat-smg', 'sgs' ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple' ];
	}

	/**
	 * @dataProvider provideGetPreferredVariantHeaders
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getHeaderVariant
	 */
	public function testGetPreferredVariantHeaders( $headerVal, $expected ) {
		global $wgRequest;
		$wgRequest->setHeader( 'Accept-Language', $headerVal );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public function provideGetPreferredVariantHeaders() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn' ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple' ];
		yield 'with weight #1' => [ 'tg;q=1', 'tg' ];
		yield 'with weight #2' => [ 'tg-latn;q=1', 'tg-latn' ];
		yield 'with multi' => [ 'en, tg-latn;q=1', 'tg-latn' ];
	}

	/**
	 * @dataProvider provideGetPreferredVariantUserOption
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantUserOption( $optionVal, $expected, $foreignLang ) {
		$optionName = 'variant';
		if ( $foreignLang ) {
			$this->setContentLang( 'en' );
			$optionName = 'variant-tg';
		}

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;
		$userOptionsManager->setOption( $user, $optionName, $optionVal );

		$this->setContextUser( $user );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public function provideGetPreferredVariantUserOption() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn', false ];
		yield 'deprecated (bat-smg)' => [ 'bat-smg', 'sgs', false ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple', false ];
		yield 'for foreign language, normal (tg-latn)' => [ 'tg-latn', 'tg-latn', true ];
		yield 'for foreign language, deprecated (bat-smg)' => [ 'bat-smg', 'sgs', true ];
		yield 'for foreign language, BCP47 (en-simple)' => [ 'en-simple', 'simple', true ];
	}

	/**
	 * @covers LanguageConverter::getPreferredVariant
	 * @covers LanguageConverter::getUserVariant
	 * @covers LanguageConverter::getURLVariant
	 */
	public function testGetPreferredVariantHeaderUserVsUrl() {
		global $wgRequest;

		$this->setContentLang( 'tg-latn' );
		$wgRequest->setVal( 'variant', 'tg' );

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$user = User::newFromId( "admin" );
		$user->setId( 1 );
		$user->mFrom = 'defaults';
		// The user's data is ignored because the variant is set in the URL.
		$userOptionsManager->setOption( $user, 'variant', 'tg-latn' );

		$this->setContextUser( $user );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @dataProvider provideGetPreferredVariantDefaultLanguageVariant
	 * @covers LanguageConverter::getPreferredVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVariant( $globalVal, $expected ) {
		global $wgDefaultLanguageVariant;

		$wgDefaultLanguageVariant = $globalVal;
		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public function provideGetPreferredVariantDefaultLanguageVariant() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn' ];
		yield 'deprecated (bat-smg)' => [ 'bat-smg', 'sgs' ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple' ];
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
		$this->assertStringNotContainsString(
			'v',
			$result,
			"в converted to v despite being in attribue"
		);
	}

	/**
	 * @dataProvider provideTitlesToConvert
	 * @covers LanguageConverter::convertTitle
	 *
	 * @param LinkTarget|PageReference $title title to convert
	 * @param string $expected
	 */
	public function testConvertTitle( $title, string $expected ): void {
		$actual = $this->lc->convertTitle( $title );
		$this->assertSame( $expected, $actual );
	}

	public function provideTitlesToConvert(): array {
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
			'PageReference' => [
				new PageReferenceValue( NS_FILE, 'Dummy page', PageReference::LOCAL ),
				'Акс:Dummy page',
			],
		];
	}
}
