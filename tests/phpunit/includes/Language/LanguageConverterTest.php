<?php

namespace MediaWiki\Tests\Language;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Tests\Mocks\Language\DummyConverter;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWikiLangTestCase;
use MockMessageLocalizer;

/**
 * @group Language
 * @covers \MediaWiki\Language\LanguageConverter
 */
class LanguageConverterTest extends MediaWikiLangTestCase {

	/** @var Language */
	protected $lang;

	/** @var DummyConverter */
	protected $lc;

	private function setContextUser( User $user ) {
		// LanguageConverter::getPreferredVariant() reads the user from
		// RequestContext::getMain(), so set it occordingly
		RequestContext::getMain()->setUser( $user );
	}

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::DefaultLanguageVariant => false,
		] );
		$this->setContentLang( 'tg' );
		$this->setContextUser( new User );

		$this->lang = $this->createNoOpMock( Language::class, [ 'factory', 'getNsText', 'ucfirst' ] );
		$this->lang->method( 'getNsText' )->with( NS_MEDIAWIKI )->willReturn( 'MediaWiki' );
		$this->lang->method( 'ucfirst' )->willReturnCallback( 'ucfirst' );
		$this->lc = new DummyConverter( $this->lang );
	}

	protected function tearDown(): void {
		unset( $this->lc );
		unset( $this->lang );

		parent::tearDown();
	}

	public function testGetPreferredVariantDefaults() {
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @dataProvider provideGetPreferredVariant
	 */
	public function testGetPreferredVariant( $requestVal, $expected ) {
		$request = RequestContext::getMain()->getRequest();
		$request->setVal( 'variant', $requestVal );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public static function provideGetPreferredVariant() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn' ];
		yield 'deprecated (bat-smg)' => [ 'bat-smg', 'sgs' ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple' ];
	}

	/**
	 * @dataProvider provideGetPreferredVariantHeaders
	 */
	public function testGetPreferredVariantHeaders( $headerVal, $expected ) {
		$request = RequestContext::getMain()->getRequest();
		$request->setHeader( 'Accept-Language', $headerVal );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public static function provideGetPreferredVariantHeaders() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn' ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple' ];
		yield 'with weight #1' => [ 'tg;q=1', 'tg' ];
		yield 'with weight #2' => [ 'tg-latn;q=1', 'tg-latn' ];
		yield 'with multi' => [ 'en, tg-latn;q=1', 'tg-latn' ];
	}

	/**
	 * @dataProvider provideGetPreferredVariantUserOption
	 */
	public function testGetPreferredVariantUserOption( $optionVal, $expected, $foreignLang ) {
		$optionName = 'variant';
		if ( $foreignLang ) {
			$this->setContentLang( 'en' );
			$optionName = 'variant-tg';
		}

		$user = new User;
		$user->load(); // from 'defaults'
		$user->mId = 1;
		$user->mDataLoaded = true;

		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->method( 'getOption' )
			->with( $user, $optionName )
			->willReturn( $optionVal );
		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		$this->setContextUser( $user );

		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public static function provideGetPreferredVariantUserOption() {
		yield 'normal (tg-latn)' => [ 'tg-latn', 'tg-latn', false ];
		yield 'deprecated (bat-smg)' => [ 'bat-smg', 'sgs', false ];
		yield 'BCP47 (en-simple)' => [ 'en-simple', 'simple', false ];
		yield 'for foreign language, normal (tg-latn)' => [ 'tg-latn', 'tg-latn', true ];
		yield 'for foreign language, deprecated (bat-smg)' => [ 'bat-smg', 'sgs', true ];
		yield 'for foreign language, BCP47 (en-simple)' => [ 'en-simple', 'simple', true ];
	}

	public function testGetPreferredVariantHeaderUserVsUrl() {
		$request = RequestContext::getMain()->getRequest();

		$this->setContentLang( 'tg-latn' );
		$request->setVal( 'variant', 'tg' );

		$user = User::newFromId( "admin" );
		$user->setId( 1 );
		$user->mFrom = 'defaults';
		// The user's data is ignored because the variant is set in the URL.
		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->method( 'getOption' )
			->with( $user, 'variant' )
			->willReturn( 'tg-latn' );
		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		$this->setContextUser( $user );

		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * @dataProvider provideGetPreferredVariant
	 */
	public function testGetPreferredVariantDefaultLanguageVariant( $globalVal, $expected ) {
		$this->overrideConfigValue( MainConfigNames::DefaultLanguageVariant, $globalVal );
		$this->assertEquals( $expected, $this->lc->getPreferredVariant() );
	}

	public function testGetPreferredVariantDefaultLanguageVsUrlVariant() {
		$request = RequestContext::getMain()->getRequest();

		$this->setContentLang( 'tg-latn' );
		$this->overrideConfigValue( MainConfigNames::DefaultLanguageVariant, 'tg' );
		$request->setVal( 'variant', null );
		$this->assertEquals( 'tg', $this->lc->getPreferredVariant() );
	}

	/**
	 * Test exhausting pcre.backtrack_limit
	 */
	public function testAutoConvertT124404() {
		$testString = str_repeat( 'xxx xxx xxx', 1000 );
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
	 * @param LinkTarget|PageReference|callable $title title to convert
	 * @param string $expected
	 */
	public function testConvertTitle( $title, string $expected ): void {
		if ( is_callable( $title ) ) {
			$title = $title();
		}
		$actual = $this->lc->convertTitle( $title );
		$this->assertSame( $expected, $actual );
	}

	public static function provideTitlesToConvert(): array {
		return [
			'Title FromText default' => [
				Title::makeTitle( NS_MAIN, 'Dummy_title' ),
				'Dummy title',
			],
			'Title FromText with NS' => [
				Title::makeTitle( NS_FILE, 'Dummy_title' ),
				'Акс:Dummy title',
			],
			'Title MainPage default' => [
				static function () {
					// Don't call this until services have been set up
					return Title::newMainPage();
				},
				'Саҳифаи аслӣ',
			],
			'Title MainPage with MessageLocalizer' => [
				static function () {
					// Don't call this until services have been set up
					return Title::newMainPage( new MockMessageLocalizer() );
				},
				'Саҳифаи аслӣ',
			],
			'TitleValue' => [
				new TitleValue( NS_FILE, 'Dummy page' ),
				'Акс:Dummy page',
			],
			'PageReference' => [
				PageReferenceValue::localReference( NS_FILE, 'Dummy page' ),
				'Акс:Dummy page',
			],
		];
	}
}
