<?php

namespace MediaWiki\Tests\Unit\Parser\Parsoid\Config;

use HashConfig;
use ILanguageConverter;
use Language;
use MagicWord;
use MagicWordArray;
use MagicWordFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use MessageCache;
use MWException;
use NamespaceInfo;
use NullStatsdDataFactory;
use Parser;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;
use ZhConverter;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig
 * @package MediaWiki\Tests\Unit\Parser\Parsoid\Config
 */
class SiteConfigTest extends MediaWikiUnitTestCase {

	private const DEFAULT_CONFIG = [
		MainConfigNames::GalleryOptions => [],
		MainConfigNames::AllowExternalImages => false,
		MainConfigNames::AllowExternalImagesFrom => '',
		MainConfigNames::Server => 'localhost',
		MainConfigNames::ArticlePath => false,
		MainConfigNames::InterwikiMagic => true,
		MainConfigNames::ExtraInterlanguageLinkPrefixes => [],
		MainConfigNames::LocalInterwikis => [],
		MainConfigNames::LanguageCode => 'qqq',
		MainConfigNames::DisableLangConversion => false,
		MainConfigNames::NamespaceAliases => [],
		MainConfigNames::UrlProtocols => [ 'http://' ],
		MainConfigNames::Script => false,
		MainConfigNames::ScriptPath => '/wiki',
		MainConfigNames::LoadScript => false,
		MainConfigNames::LocalTZoffset => null,
		MainConfigNames::ThumbLimits => [ 4242 ],
		MainConfigNames::MaxTemplateDepth => 42,
		MainConfigNames::LegalTitleChars => 'abc',
		MainConfigNames::NoFollowLinks => true,
		MainConfigNames::NoFollowNsExceptions => [ 5 ],
		MainConfigNames::NoFollowDomainExceptions => [ 'www.mediawiki.org' ],
		MainConfigNames::ExternalLinkTarget => false,
	];

	private function createMockOrOverride( string $class, array $overrides ) {
		if ( array_key_exists( $class, $overrides ) ) {
			return $overrides[$class];
		}
		return $this->createNoOpMock( $class );
	}

	/**
	 * TODO it might save code to have this helper always return a
	 * TestingAccessWrapper?
	 *
	 * @param array $configOverrides Configuration options overriding default ServiceOptions config defined in
	 *                               DEFAULT_CONFIG above.
	 * @param array $parsoidSettings
	 * @param array $serviceOverrides
	 *
	 * @return SiteConfig
	 */
	private function createSiteConfig(
		array $configOverrides = [],
		array $parsoidSettings = [],
		array $serviceOverrides = []
	): SiteConfig {
		return new SiteConfig(
			new ServiceOptions(
				SiteConfig::CONSTRUCTOR_OPTIONS,
				array_replace( self::DEFAULT_CONFIG, $configOverrides )
			),
			$parsoidSettings,
			$this->createSimpleObjectFactory(),
			$this->createMockOrOverride( Language::class, $serviceOverrides ),
			new NullStatsdDataFactory(),
			$this->createMockOrOverride( MagicWordFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( NamespaceInfo::class, $serviceOverrides ),
			$this->createMockOrOverride( SpecialPageFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( InterwikiLookup::class, $serviceOverrides ),
			$this->createMockOrOverride( UserOptionsLookup::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageConverterFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageNameUtils::class, $serviceOverrides ),
			$this->createMockOrOverride( UrlUtils::class, $serviceOverrides ),
			$this->createMockOrOverride( Parser::class, $serviceOverrides ),
			new HashConfig( $configOverrides )
		);
	}

	public function provideConfigParameterPassed(): iterable {
		yield 'galleryOptions' => [
			[ MainConfigNames::GalleryOptions => [ 'blabla' ] ],
			'galleryOptions',
			[ 'blabla' ]
		];
		yield 'allowedExternalImagePrefixes, false' => [
			[ MainConfigNames::AllowExternalImages => true ],
			'allowedExternalImagePrefixes',
			[ '' ]
		];
		yield 'allowedExternalImagePrefixes, true' => [
			[
				MainConfigNames::AllowExternalImages => false,
				MainConfigNames::AllowExternalImagesFrom => [ 'blabla' ]
			],
			'allowedExternalImagePrefixes',
			[ 'blabla' ]
		];
		yield 'interwikiMagic' => [
			[ MainConfigNames::InterwikiMagic => true ],
			'interwikiMagic',
			true
		];
		yield 'lang' => [
			[ MainConfigNames::LanguageCode => 'qqx' ],
			'lang',
			'qqx'
		];
		// This is a setting from Cite extension
		yield 'responsiveReferences, absent' => [
			[],
			'responsiveReferences',
			[ 'enabled' => false, 'threshold' => 10 ]
		];
		// This is a setting from Cite extension
		yield 'responsiveReferences, true' => [
			[ 'CiteResponsiveReferences' => true ],
			'responsiveReferences',
			[ 'enabled' => true, 'threshold' => 10 ]
		];
		yield 'script' => [
			[ MainConfigNames::Script => 'blabla' ],
			'script',
			'blabla'
		];
		yield 'scriptpath' => [
			[ MainConfigNames::ScriptPath => 'blabla' ],
			'scriptpath',
			'blabla'
		];
		yield 'server' => [
			[ MainConfigNames::Server => 'blabla' ],
			'server',
			'blabla'
		];
		yield 'timezoneOffset' => [
			[ MainConfigNames::LocalTZoffset => 42 ],
			'timezoneOffset',
			42
		];
		yield 'getMaxTemplateDepth' => [
			[ MainConfigNames::MaxTemplateDepth => 42 ],
			'getMaxTemplateDepth',
			42
		];
		/* $wgLegalTitleChars can't be tested with this mechanism.
		yield 'legalTitleChars' => [
			[ MainConfigNames::LegalTitleChars => 'blabla' ],
			'legalTitleChars',
			'blabla'
		];
		*/
		yield 'getProtocols' => [
			[ MainConfigNames::UrlProtocols => [ 'blabla' ] ],
			'getProtocols',
			[ 'blabla' ]
		];
		yield 'getNoFollowConfig' => [
			[],
			'getNoFollowConfig',
			[ 'nofollow' => true, 'nsexceptions' => [ 5 ], 'domainexceptions' => [ 'www.mediawiki.org' ] ]
		];
		yield 'getExternalLinkTargetEmpty' => [
			[],
			'getExternalLinkTarget',
			false
		];
		yield 'getExternalLinkTargetString' => [
			[ MainConfigNames::ExternalLinkTarget => "_blank" ],
			'getExternalLinkTarget',
			"_blank"
		];
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::galleryOptions
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::allowedExternalImagePrefixes
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::interwikiMagic
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::lang
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::responsiveReferences
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::script
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::scriptpath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::server
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::timezoneOffset
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getMaxTemplateDepth
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::legalTitleChars
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getProtocols
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getNoFollowConfig
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getExternalLinkTarget
	 * @dataProvider provideConfigParameterPassed
	 * @param array $settings
	 * @param string $method
	 * @param mixed $expectedValue
	 */
	public function testConfigParametersPassed(
		array $settings,
		string $method,
		$expectedValue
	) {
		$config = $this->createSiteConfig( $settings );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame( $expectedValue, $config->$method() );
	}

	public function provideParsoidSettingPassed() {
		yield 'nativeGalleryEnabled' => [
			[ 'nativeGalleryEnabled' => true ],
			'nativeGalleryEnabled',
			true
		];
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::nativeGalleryEnabled()
	 * @dataProvider provideParsoidSettingPassed
	 * @param array $settings
	 * @param string $method
	 * @param mixed $expectedValue
	 */
	public function testParsoidSettingPassed(
		array $settings,
		string $method,
		$expectedValue
	) {
		$config = $this->createSiteConfig( [], $settings );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame( $expectedValue, $config->$method() );
	}

	public function provideServiceMethodProxied() {
		yield 'canonicalNamespaceId' => [
			NamespaceInfo::class, 'getCanonicalIndex', [ 'blabla_arg' ], 42, 'canonicalNamespaceId', 42
		];
		yield 'namespaceId' => [
			Language::class, 'getNsIndex', [ 'blabla_arg' ], 42, 'namespaceId', 42
		];
		yield 'namespaceName, NS_MAIN' => [
			Language::class, 'getFormattedNsText', [ NS_MAIN ], '', 'namespaceName', ''
		];
		yield 'namespaceName, NS_USER, null' => [
			Language::class, 'getFormattedNsText', [ NS_USER ], '', 'namespaceName', null
		];
		yield 'namespaceName, NS_USER' => [
			Language::class, 'getFormattedNsText', [ NS_USER ], 'User', 'namespaceName', 'User'
		];
		yield 'namespaceHasSubpages' => [
			NamespaceInfo::class, 'hasSubpages', [ 42 ], true, 'namespaceHasSubpages', true
		];
		yield 'namespaceCase, first letter' => [
			NamespaceInfo::class, 'isCapitalized', [ 42 ], true, 'namespaceCase', 'first-letter'
		];
		yield 'namespaceCase, case sensitive' => [
			NamespaceInfo::class, 'isCapitalized', [ 42 ], false, 'namespaceCase', 'case-sensitive'
		];
		yield 'namespaceIsTalk' => [
			NamespaceInfo::class, 'isTalk', [ 42 ], true, 'namespaceIsTalk', true
		];
		yield 'ucfirst' => [
			Language::class, 'ucfirst', [ 'bla' ], 'Bla', 'ucfirst', 'Bla'
		];
		yield 'linkTrail' => [
			Language::class, 'linkTrail', [], 'blabla', 'linkTrail', 'blabla'
		];
		yield 'rtl' => [
			Language::class, 'isRTL', [], true, 'rtl', true
		];
		yield 'getVariableIDs' => [
			MagicWordFactory::class, 'getVariableIDs', [], [ 'blabla' ], 'getVariableIDs', [ 'blabla' ]
		];
		yield 'getFunctionSynonyms' => [
			Parser::class, 'getFunctionSynonyms', [], [ 0 => [ 'blabla' ], 1 => [ 'blabla' ] ],
			'getFunctionSynonyms', [ 0 => [ 'blabla' ], 1 => [ 'blabla' ] ]
		];
		yield 'getMagicWords' => [
			Language::class, 'getMagicWords', [], [ 'blabla' ], 'getMagicWords', [ 'blabla' ]
		];
		yield 'getNonNativeExtensionTags' => [
			Parser::class, 'getTags', [], [ 'blabla' ], 'getNonNativeExtensionTags', [ 'blabla' => true ]
		];
	}

	/**
	 * @dataProvider provideServiceMethodProxied
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::canonicalNamespaceId
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::namespaceId
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::namespaceName
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::namespaceHasSubpages
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::namespaceCase
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::namespaceIsTalk
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::ucfirst
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::linkTrail
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::rtl
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::widthOption
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getVariableIDs
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getFunctionSynonyms
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getMagicWords
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getNonNativeExtensionTags
	 * @param string $serviceClass
	 * @param string $serviceMethod
	 * @param array $arguments
	 * @param mixed $returnValue
	 * @param string $method
	 * @param mixed $expectedValue
	 */
	public function testServiceMethodProxied(
		string $serviceClass,
		string $serviceMethod,
		array $arguments,
		$returnValue,
		string $method,
		$expectedValue
	) {
		$serviceMock = $this->createMock( $serviceClass );
		$serviceMock
			->expects( $this->once() )
			->method( $serviceMethod )
			->with( ...$arguments )
			->willReturn( $returnValue );
		$config = $this->createSiteConfig( [], [], [
			$serviceClass => $serviceMock
		] );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame( $expectedValue, $config->$method( ...$arguments ) );
	}

	public function provideArticlePath_exception() {
		yield 'No $1' => [ '/test/test' ];
		yield 'Wrong path' => [ 'test\\test/$1' ];
	}

	/**
	 * @dataProvider provideArticlePath_exception
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::determineArticlePath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::baseURI
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::relativeLinkPrefix
	 * @param string $articlePath
	 */
	public function testArticlePath_exception( string $articlePath ) {
		$this->expectException( UnexpectedValueException::class );
		$config = $this->createSiteConfig( [
			MainConfigNames::ArticlePath => $articlePath
		] );
		$config->baseURI();
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::determineArticlePath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::baseURI
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::relativeLinkPrefix
	 */
	public function testArticlePath_nopath() {
		$config = $this->createSiteConfig( [
			MainConfigNames::ArticlePath => '$1',
			MainConfigNames::Server => 'https://localhost'
		] );
		$this->assertSame( 'https://localhost/', $config->baseURI() );
		$this->assertSame( './', $config->relativeLinkPrefix() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::determineArticlePath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::baseURI
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::relativeLinkPrefix
	 */
	public function testArticlePath() {
		$config = $this->createSiteConfig( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::Server => 'https://localhost'
		] );
		$this->assertSame( './', $config->relativeLinkPrefix() );
		$this->assertSame( 'https://localhost/wiki/', $config->baseURI() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::mwaToRegex
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::redirectRegexp
	 */
	public function testRedirectRegexp() {
		$langMock = $this->createMock( Language::class );
		$magicWordFactoryMock = $this->createMock( MagicWordFactory::class );
		$magicWordFactoryMock
			->method( 'newArray' )
			->willReturn(
				new MagicWordArray( [ 'blabla_case_sen', 'blabla_case_insen' ], $magicWordFactoryMock )
			);
		$magicWordFactoryMock
			->method( 'get' )
			->willReturnOnConsecutiveCalls(
				new MagicWord( 'blabla_id', [ 'blabla_synonym1' ], true, $langMock ),
				new MagicWord( 'blabla_id', [ 'blabla_synonym2' ], false, $langMock )
			);
		$config = $this->createSiteConfig( [], [], [
			MagicWordFactory::class => $magicWordFactoryMock
		] );
		$this->assertSame( '@(?i:blabla_synonym2)|blabla_synonym1@Su', $config->redirectRegexp() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::categoryRegexp
	 */
	public function testCategoryRegexp() {
		$nsInfoMock = $this->createMock( NamespaceInfo::class );
		$nsInfoMock
			->method( 'getCanonicalName' )
			->willReturn( 'Bla bla' );
		$langMock = $this->createMock( Language::class );
		$langMock
			->method( 'getNamespaceAliases' )
			->willReturn( [ 'Bla_alias' => NS_CATEGORY, 'Ignored' => NS_MAIN ] );
		$config = $this->createSiteConfig( [], [], [
			NamespaceInfo::class => $nsInfoMock,
			Language::class => $langMock
		] );
		$this->assertSame( '@(?i:Bla[ _]bla|Bla[ _]alias)@', $config->categoryRegexp() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::bswRegexp
	 */
	public function testBswRegexp() {
		$langMock = $this->createMock( Language::class );
		$magicWordFactoryMock = $this->createMock( MagicWordFactory::class );
		$magicWordFactoryMock
			->method( 'getDoubleUnderscoreArray' )
			->willReturn(
				new MagicWordArray( [ 'blabla' ], $magicWordFactoryMock )
			);
		$magicWordFactoryMock
			->method( 'get' )
			->willReturn(
				new MagicWord( 'blabla_id', [ 'blabla_synonym' ], true, $langMock )
			);
		$config = $this->createSiteConfig( [], [], [
			MagicWordFactory::class => $magicWordFactoryMock
		] );
		$this->assertSame( '@(?i:(?!))|blabla_synonym@Su', $config->bswRegexp() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::specialPageLocalName
	 */
	public function testSpecialPageLocalName() {
		$specialPageFactoryMock = $this->createMock( SpecialPageFactory::class );
		$specialPageFactoryMock
			->method( 'resolveAlias' )
			->with( 'blabla_alias' )
			->willReturn( [ 'resolved_page', 'resolved_subpage' ] );
		$specialPageFactoryMock
			->method( 'getLocalNameFor' )
			->with( 'resolved_page', 'resolved_subpage' )
			->willReturn( 'blabla' );
		$config = $this->createSiteConfig( [], [], [
			SpecialPageFactory::class => $specialPageFactoryMock
		] );
		$this->assertSame( 'blabla', $config->specialPageLocalName( 'blabla_alias' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::interwikiMap
	 */
	public function testInterwikiMap() {
		$interwikiMock = $this->createMock( InterwikiLookup::class );
		$interwikiMock
			->method( 'getAllPrefixes' )
			->willReturn( [
				[ 'iw_prefix' => 'ru', 'iw_url' => '//test/', 'iw_local' => 1 ]
			] );
		$langNameUtilsMock = $this->createMock( LanguageNameUtils::class );
		$langNameUtilsMock
			->method( 'getLanguageNames' )
			->willReturn( [ 'ru' => 'Russian' ] );
		$messageCacheMock = $this->createMock( MessageCache::class );
		$messageCacheMock
			->method( 'get' )
			->willReturn( false );
		$urlUtilsMock = $this->createMock( UrlUtils::class );
		$urlUtilsMock
			->method( 'expand' )
			->with( '//test/', 1 )
			->willReturn( 'http://test/' );

		$config = $this->createSiteConfig( [
			MainConfigNames::ExtraInterlanguageLinkPrefixes => [ 'ru' ],
			MainConfigNames::LocalInterwikis => [ 'ru' ],
		], [], [
			InterwikiLookup::class => $interwikiMock,
			LanguageNameUtils::class => $langNameUtilsMock,
			MessageCache::class => $messageCacheMock,
			UrlUtils::class => $urlUtilsMock
		] );
		$this->assertSame( [
			'ru' => [
				'prefix' => 'ru',
				'url' => 'http://test/$1',
				'protorel' => true,
				'local' => true,
				'language' => true,
				'localinterwiki' => true,
				'extralanglink' => true,
			]
		], $config->interwikiMap() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::iwp
	 */
	public function testIwp() {
		$config = $this->createSiteConfig();
		$this->assertNotNull( $config->iwp() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::linkPrefixRegex
	 */
	public function testLinkPrefixRegex_disabled() {
		$langMock = $this->createMock( Language::class );
		$langMock
			->method( 'linkPrefixExtension' )
			->willReturn( false );
		$config = $this->createSiteConfig( [], [], [
			Language::class => $langMock
		] );
		$this->assertNull( $config->linkPrefixRegex() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::linkPrefixRegex
	 */
	public function testLinkPrefixRegex() {
		$langMock = $this->createMock( Language::class );
		$langMock
			->method( 'linkPrefixExtension' )
			->willReturn( true );
		$langMock
			->method( 'linkPrefixCharset' )
			->willReturn( 'blabla' );
		$config = $this->createSiteConfig( [], [], [
			Language::class => $langMock
		] );
		$this->assertStringContainsString( 'blabla', $config->linkPrefixRegex() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::mainpage
	 */
	public function testMainpage() {
		$this->markTestSkipped( 'Requires MessageCache; not a unit test' );
		$this->assertSame( 'Main Page', $this->createSiteConfig()->mainpage() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabled
	 */
	public function testLangConverterEnabled_disabled() {
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( true );
		$config = $this->createSiteConfig( [], [], [
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertFalse( $config->langConverterEnabled( 'zh' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabled
	 */
	public function testLangConverterEnabled_invalidCode() {
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( false );
		$config = $this->createSiteConfig( [], [], [
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertFalse( $config->langConverterEnabled( 'zhasdcasdc' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabled
	 */
	public function testLangConverterEnabled_valid() {
		$langMock = $this->createMock( Language::class );
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->with( 'zh' )
			->willReturn( $langMock );
		$langConverterMock = $this->createMock( ZhConverter::class );
		$langConverterMock
			->method( 'hasVariants' )
			->willReturn( true );
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'getLanguageConverter' )
			->with( $langMock )
			->willReturn( $langConverterMock );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( false );
		$config = $this->createSiteConfig( [], [], [
			LanguageFactory::class => $langFactoryMock,
			LanguageConverterFactory::class => $langConverterFactoryMock
		] );
		$this->assertTrue( $config->langConverterEnabled( 'zh' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabled
	 */
	public function testLangConverterEnabled_exception() {
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->with( 'zh' )
			->willThrowException( new MWException( 'TEST' ) );
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( false );
		$config = $this->createSiteConfig( [], [], [
			LanguageFactory::class => $langFactoryMock,
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertFalse( $config->langConverterEnabled( 'zh' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::variants
	 */
	public function testVariants_disabled() {
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( true );
		$config = $this->createSiteConfig( [], [], [
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertSame( [], $config->variants() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::variants
	 */
	public function testVariants() {
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->willReturnCallback( function ( $code ) {
				$langMock = $this->createMock( Language::class );
				$langMock->method( 'getCode' )
					->willReturn( $code );
				return $langMock;
			} );
		$converterMock = $this->createMock( ILanguageConverter::class );
		$converterMock
			->method( 'hasVariants' )
			->willReturn( true );
		$converterMock
			->method( 'getVariants' )
			->willReturn( [ 'zh-hans' ] );
		$converterMock
			->method( 'getVariantFallbacks' )
			->willReturn( 'zh-fallback' );
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( false );
		$langConverterFactoryMock
			->method( 'getLanguageConverter' )
			->willReturnCallback( function ( $l ) use ( $converterMock ) {
				if ( $l->getCode() === 'zh' ) {
					return $converterMock;
				}
				return $this->createMock( ILanguageConverter::class );
			} );
		$config = $this->createSiteConfig( [], [], [
			LanguageFactory::class => $langFactoryMock,
			LanguageConverterFactory::class => $langConverterFactoryMock
		] );
		$this->assertSame(
			[ 'zh-hans' => [ 'base' => 'zh', 'fallbacks' => [ 'zh-fallback' ] ] ],
			$config->variants()
		);
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::widthOption
	 */
	public function testWithOption() {
		$optionsLookupMock = $this->createMock( UserOptionsLookup::class );
		$optionsLookupMock
			->method( 'getDefaultOption' )
			->with( 'thumbsize' )
			->willReturn( 'small' );
		$config = $this->createSiteConfig( [
			MainConfigNames::ThumbLimits => [ 'small' => 42 ]
		], [], [
			UserOptionsLookup::class => $optionsLookupMock
		] );
		$this->assertSame( 42, $config->widthOption() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getMagicWordMatcher
	 */
	public function testGetMagicWordMatcher() {
		$magicWordMock = $this->createMock( MagicWord::class );
		$magicWordMock
			->expects( $this->once() )
			->method( 'getRegexStartToEnd' )
			->willReturn( 'blabla' );
		$magicWordFactoryMock = $this->createMock( MagicWordFactory::class );
		$magicWordFactoryMock
			->expects( $this->once() )
			->method( 'get' )
			->with( 'blabla_id' )
			->willReturn( $magicWordMock );
		$config = $this->createSiteConfig( [], [], [
			MagicWordFactory::class => $magicWordFactoryMock
		] );
		$this->assertSame( 'blabla', $config->getMagicWordMatcher( 'blabla_id' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getParameterizedAliasMatcher
	 */
	public function testGetParameterizedAliasMatcher() {
		$langMock = $this->createMock( Language::class );
		$magicWordFactoryMock = $this->createMock( MagicWordFactory::class );
		$magicWordFactoryMock
			->method( 'newArray' )
			->willReturn( new MagicWordArray( [ 'test' ], $magicWordFactoryMock ) );
		$magicWordFactoryMock
			->method( 'get' )
			->willReturn( new MagicWord( 'blabla_id', [ 'blabla_alias:$1' ], true, $langMock ) );
		$config = $this->createSiteConfig( [], [], [
			MagicWordFactory::class => $magicWordFactoryMock
		] );
		$matcher = $config->getParameterizedAliasMatcher( [ 'blabla' ] );
		$this->assertSame( [ 'k' => 'test', 'v' => 'blabla' ], $matcher( 'blabla_alias:blabla' ) );
		$this->assertNull( $matcher( 'Blabla_alias:blabla' ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getSpecialNSAliases
	 */
	public function testGetSpecialNSAliases() {
		$mockLang = $this->createMock( Language::class );
		$mockLang
			->method( 'getNsText' )
			->willReturn( 'Special_Special' );
		$mockLang
			->method( 'getNamespaceAliases' )
			->willReturn( [
				'From Language' => NS_SPECIAL,
				'Whatever' => NS_MAIN
			] );
		$config = $this->createSiteConfig( [
			MainConfigNames::NamespaceAliases => [
				'From Config' => NS_SPECIAL,
				'Whatever' => NS_MAIN
			]
		], [], [
			Language::class => $mockLang
		] );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame(
			[ 'Special', 'Special[ _]Special', 'From[ _]Language', 'From[ _]Config' ],
			$config->getSpecialNSAliases()
		);
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getSpecialPageAliases
	 */
	public function testGetSpecialPageAliases() {
		$mockLang = $this->createMock( Language::class );
		$mockLang
			->method( 'getSpecialPageAliases' )
			->willReturn( [
				'Page1' => [ 'Alias1', 'Alias2' ]
			] );
		$config = $this->createSiteConfig( [], [], [
			Language::class => $mockLang
		] );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame( [ 'Page1', 'Alias1', 'Alias2' ], $config->getSpecialPageAliases( 'Page1' ) );
		$this->assertSame( [ 'Page2' ], $config->getSpecialPageAliases( 'Page2' ) );
	}
}
