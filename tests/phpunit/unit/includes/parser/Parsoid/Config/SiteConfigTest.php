<?php

namespace MediaWiki\Tests\Unit\Parser\Parsoid\Config;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordArray;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use MessageCache;
use UnexpectedValueException;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Stats\NullStatsdDataFactory;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;
use ZhConverter;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig
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
		MainConfigNames::InterlanguageLinkCodeMap => [],
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
		MainConfigNames::EnableMagicLinks => [
			'ISBN' => true,
			'PMID' => true,
			'RFC' => true,
		],
		MainConfigNames::ParsoidExperimentalParserFunctionOutput => false,
	];

	private StatsFactory $statsFactory;

	protected function setUp(): void {
		parent::setUp();
		$this->statsFactory = StatsFactory::newNull();
	}

	private function createMockOrOverride( string $class, array $overrides ) {
		return $overrides[$class] ?? $this->createNoOpMock( $class );
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
			$this->statsFactory,
			$this->createMockOrOverride( MagicWordFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( NamespaceInfo::class, $serviceOverrides ),
			$this->createMockOrOverride( SpecialPageFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( InterwikiLookup::class, $serviceOverrides ),
			$this->createMockOrOverride( UserOptionsLookup::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageConverterFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( LanguageNameUtils::class, $serviceOverrides ),
			$this->createMockOrOverride( UrlUtils::class, $serviceOverrides ),
			$this->createMockOrOverride( IContentHandlerFactory::class, $serviceOverrides ),
			[],
			$this->createMockOrOverride( ParserFactory::class, $serviceOverrides ),
			new HashConfig( $configOverrides ),
			false
		);
	}

	public static function provideConfigParameterPassed(): iterable {
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

	public static function provideParsoidSettingPassed() {
		yield 'linterEnabled' => [
			[ 'linting' => true ],
			'linterEnabled',
			true
		];
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::linterEnabled()
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

	public static function provideServiceMethodProxied() {
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
		yield 'langBcp47' => [
			Language::class, null, [], null, 'langBcp47', '<service-mock>'
		];
		yield 'rtl' => [
			Language::class, 'isRTL', [], true, 'rtl', true
		];
		yield 'getVariableIDs' => [
			MagicWordFactory::class, 'getVariableIDs', [], [ 'blabla' ], 'getVariableIDs', [ 'blabla' ]
		];
		yield 'getFunctionSynonyms' => [
			[ ParserFactory::class, 'getMainInstance' ], [ Parser::class, 'getFunctionSynonyms' ], [], [ 0 => [ 'blabla' ], 1 => [ 'blabla' ] ],
			'getFunctionSynonyms', [ 0 => [ 'blabla' ], 1 => [ 'blabla' ] ]
		];
		yield 'getMagicWords' => [
			Language::class, 'getMagicWords', [], [ 'blabla' ], 'getMagicWords', [ 'blabla' ]
		];
		yield 'getNonNativeExtensionTags' => [
			[ ParserFactory::class, 'getMainInstance' ], [ Parser::class, 'getTags' ], [], [ 'blabla' ], 'getNonNativeExtensionTags', [ 'blabla' => true ]
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
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langBcp47
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::rtl
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::widthOption
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getVariableIDs
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getFunctionSynonyms
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getMagicWords
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getNonNativeExtensionTags
	 * @param string|array $serviceClassSpec
	 * @param ?string|array $serviceMethodSpec
	 * @param array $arguments
	 * @param mixed $returnValue
	 * @param string $method
	 * @param mixed $expectedValue
	 */
	public function testServiceMethodProxied(
		$serviceClassSpec,
		$serviceMethodSpec,
		array $arguments,
		$returnValue,
		string $method,
		$expectedValue
	) {
		$serviceClass = is_array( $serviceClassSpec ) ? $serviceClassSpec[0] : $serviceClassSpec;
		$serviceMock = $this->createMock( $serviceClass );
		if ( $serviceMethodSpec ) {
			if ( is_array( $serviceMethodSpec ) ) {
				// Service mock is a factory, create a object and let the factory return that object
				$mock = $this->createMock( $serviceMethodSpec[0] );
				$serviceMock->method( $serviceClassSpec[1] )->willReturn( $mock );
				$serviceMethod = $serviceMethodSpec[1];
			} else {
				// No factory, use the service mock directly
				$mock = $serviceMock;
				$serviceMethod = $serviceMethodSpec;
			}
			// Let the mock return the expected arguments
			$mock
				->expects( $this->once() )
				->method( $serviceMethod )
				->with( ...$arguments )
				->willReturn( $returnValue );
		}
		$config = $this->createSiteConfig( [], [], [
			$serviceClass => $serviceMock
		] );
		$config = TestingAccessWrapper::newFromObject( $config );
		if ( $expectedValue === '<service-mock>' ) {
			$expectedValue = $serviceMock;
		}
		$this->assertSame( $expectedValue, $config->$method( ...$arguments ) );
	}

	public static function provideArticlePath_exception() {
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
		$config = $this->createSiteConfig(
			[
				MainConfigNames::ArticlePath => $articlePath
			],
			[],
			[
				UrlUtils::class => $this->createMock( UrlUtils::class )
			]
		);
		$config->baseURI();
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::determineArticlePath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::baseURI
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::relativeLinkPrefix
	 */
	public function testArticlePath_nopath() {
		$config = $this->createSiteConfig(
			[
				MainConfigNames::ArticlePath => '$1',
				MainConfigNames::Server => 'https://localhost'
			],
			[],
			[
				UrlUtils::class => new UrlUtils()
			]
		);
		$this->assertSame( 'https://localhost/', $config->baseURI() );
		$this->assertSame( './', $config->relativeLinkPrefix() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::determineArticlePath
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::baseURI
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::relativeLinkPrefix
	 */
	public function testArticlePath() {
		$config = $this->createSiteConfig(
			[
				MainConfigNames::ArticlePath => '/wiki/$1',
				MainConfigNames::Server => 'https://localhost'
			],
			[],
			[
				UrlUtils::class => new UrlUtils()
			]
		);
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
				'bcp47' => 'ru',
				'localinterwiki' => true,
				'extralanglink' => true,
				'code' => 'ru',
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
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabledBcp47
	 */
	public function testLangConverterEnabledBcp47_disabled() {
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( true );
		$config = $this->createSiteConfig( [], [], [
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$zh = new Bcp47CodeValue( 'zh' );
		$this->assertFalse( $config->langConverterEnabledBcp47( $zh ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabledBcp47
	 */
	public function testLangConverterEnabledBcp47_invalidCode() {
		$langMock = $this->createMock( Language::class );
		$langMock
			->method( 'getCode' )
			->willReturn( 'bogus' );
		$langConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$langConverterFactoryMock
			->method( 'isConversionDisabled' )
			->willReturn( false );
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->with( $langMock )
			->willReturn( $langMock );
		$config = $this->createSiteConfig( [], [], [
			LanguageFactory::class => $langFactoryMock,
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertFalse( $config->langConverterEnabledBcp47( $langMock ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::langConverterEnabledBcp47
	 */
	public function testLangConverterEnabled_valid() {
		$langMock = $this->createMock( Language::class );
		$langMock
			->method( 'getCode' )
			->willReturn( 'zh' );
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
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->with( $langMock )
			->willReturn( $langMock );
		$config = $this->createSiteConfig( [], [], [
			LanguageFactory::class => $langFactoryMock,
			LanguageConverterFactory::class => $langConverterFactoryMock
		] );
		$this->assertTrue( $config->langConverterEnabledBcp47( $langMock ) );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::variantsFor
	 */
	public function testVariantsFor_disabled() {
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->willReturnCallback( function ( $code ) {
				if ( !is_string( $code ) ) {
					$code = strtolower( $code->toBcp47Code() );
				}
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
			->willReturn( true );
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
			LanguageConverterFactory::class => $langConverterFactoryMock,
		] );
		$this->assertNull(
						$config->variantsFor( new Bcp47CodeValue( 'zh-Hans' ) )
		);
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::variantsFor
	 */
	public function testVariantsFor() {
		$langFactoryMock = $this->createMock( LanguageFactory::class );
		$langFactoryMock
			->method( 'getLanguage' )
			->willReturnCallback( function ( $code ) {
				if ( !is_string( $code ) ) {
					$code = strtolower( $code->toBcp47Code() );
				}
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
		$variantsForZh = $config->variantsFor( new Bcp47CodeValue( 'zh-Hans' ) );
		$this->assertIsArray( $variantsForZh );
		$this->assertArrayHasKey( 'base', $variantsForZh );
		$this->assertEquals( 'zh', $variantsForZh['base']->getCode() );
		$this->assertArrayHasKey( 'fallbacks', $variantsForZh );
		$fallbacks = $variantsForZh['fallbacks'];
		$this->assertIsArray( $fallbacks );
		$this->assertCount( 1, $fallbacks );
		$this->assertEquals( 'zh-fallback', $fallbacks[0]->getCode() );
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

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::getMWConfigValue
	 */
	public function testGetMWConfigValue() {
		$config = $this->createSiteConfig( [
			'CiteResponsiveReferences' => true,
			'CiteResponsiveReferencesThreshold' => 10,
		], [], [] );
		$config = TestingAccessWrapper::newFromObject( $config );
		$this->assertSame( true, $config->getMWConfigValue( 'CiteResponsiveReferences' ) );
		$this->assertSame( 10, $config->getMWConfigValue( 'CiteResponsiveReferencesThreshold' ) );
		$this->assertSame( null, $config->getMWConfigValue( 'CiteUnknownConfig' ) );
	}

	public static function provideMetricsData(): iterable {
		return [ [
			"metric_name",
			[
				[ "label1" => "value1", "label2" => "value2" ],
				[ "label1" => "value1", "label2" => "value3" ]
			],
			[
				[ "value1", "value2" ],
				[ "value1", "value3" ] ]
		] ];
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::incrementCounter
	 * @dataProvider provideMetricsData
	 */
	public function testIncrementCounter( $name, $labels, $expectedValues ) {
		$config = $this->createSiteConfig();
		$config->incrementCounter( $name, $labels[0] );
		$config->incrementCounter( $name, $labels[1] );
		$counter = $this->statsFactory->withComponent( "Parsoid" )->getCounter( $name );
		$this->assertSame( 2, $counter->getSampleCount() );
		$this->assertSame( $expectedValues[0], $counter->getSamples()[0]->getLabelValues() );
		$this->assertSame( 1.0, $counter->getSamples()[0]->getValue() );
		$this->assertSame( $expectedValues[1], $counter->getSamples()[1]->getLabelValues() );
		$this->assertSame( 1.0, $counter->getSamples()[1]->getValue() );
		// Check zero $amount
		$config->incrementCounter( $name, $labels[0], 0 );
		$this->assertSame( 3, $counter->getSampleCount() );
		$this->assertSame( 0.0, $counter->getSamples()[2]->getValue() );
		// Check non-unit $amount
		$config->incrementCounter( $name, $labels[1], 12 );
		$this->assertSame( 4, $counter->getSampleCount() );
		$this->assertSame( 12.0, $counter->getSamples()[3]->getValue() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig::observeTiming
	 * @dataProvider provideMetricsData
	 */
	public function testObserveTiming( $name, $labels, $expectedValues ) {
		$config = $this->createSiteConfig();
		$config->observeTiming( $name, 1500.1, $labels[0] );
		$config->observeTiming( $name, 2500.2, $labels[1] );
		$counter = $this->statsFactory->withComponent( "Parsoid" )->getTiming( $name );
		$this->assertSame( 2, $counter->getSampleCount() );
		$this->assertSame( $expectedValues[0], $counter->getSamples()[0]->getLabelValues() );
		$this->assertSame( 1500.1, $counter->getSamples()[0]->getValue() );
		$this->assertSame( $expectedValues[1], $counter->getSamples()[1]->getLabelValues() );
		$this->assertSame( 2500.2, $counter->getSamples()[1]->getValue() );
	}
}
