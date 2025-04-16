<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\Config\PageConfig;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Parser\Parsoid\LanguageVariantConverter;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Parser\Parsoid\LanguageVariantConverter
 */
class LanguageVariantConverterUnitTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideSetConfig */
	public function testSetConfig( bool $shouldPageConfigFactoryBeUsed ) {
		// Decide what should be called and what should not be
		$shouldParsoidBeUsed = true;
		$isLanguageConversionEnabled = true;

		// Set expected language codes
		// *These are all MediaWiki-internal codes*
		$pageBundleLanguageCode = 'zh';
		$titleLanguageCode = 'zh-hans';
		$targetLanguageCode = 'zh-hans';
		$sourceLanguageCode = null;

		// Create mocks
		$pageConfigMock = $this->getPageConfigMock();
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );

		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			null,
			$titleLanguageCode,
			$targetLanguageCode,
			$sourceLanguageCode
		);

		if ( !$shouldPageConfigFactoryBeUsed ) {
			$languageVariantConverter->setPageConfig( $pageConfigMock );
		}

		$languageFactoryMock = $this->getLanguageFactoryMock();
		$languageVariantConverter->convertPageBundleVariant(
			$pageBundleMock,
			new Bcp47CodeValue( $targetLanguageCode )
		);
	}

	public static function provideSetConfig() {
		yield 'PageConfigFactory should not be used if PageConfig is set' => [ false ];

		yield 'PageConfigFactory should be used if PageConfig is not set' => [ true ];
	}

	/** @dataProvider provideSourceLanguage */
	public function testSourceLanguage(
		?string $pageBundleLanguageCode,
		?string $titleLanguageCode,
		?string $contentLanguageOverride,
		?string $targetLanguageCode,
		?string $sourceLanguageCode,
		?string $expectedSourceCode
	) {
		// Decide what should be called and what should not be
		$shouldParsoidBeUsed = true;
		$shouldPageConfigFactoryBeUsed = true;
		$isLanguageConversionEnabled = true;

		// Set expected language codes
		$titleLanguageCode ??= 'en';
		$targetLanguageCode ??= $titleLanguageCode;

		// Create mocks
		if ( $pageBundleLanguageCode ) {
			$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		} else {
			$pageBundleMock = $this->getPageBundleMockWithoutLanguage();
		}

		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			$contentLanguageOverride,
			$titleLanguageCode,
			$targetLanguageCode, // expected target language
			$expectedSourceCode
		);

		$targetLanguage = new Bcp47CodeValue( $targetLanguageCode );
		$sourceLanguage = $sourceLanguageCode ? new Bcp47CodeValue( $sourceLanguageCode ) : null;

		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguage, $sourceLanguage );
	}

	public static function provideSourceLanguage() {
		yield 'content-language in PageBundle' => [
			'sr', // PageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'sr-Cyrl', // target
			'sr-Cyrl', // explicit source
			'sr-Cyrl'  // expected source
		];
		yield 'content-language but no source language' => [
			'en', // PageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			null, // explicit source
			null     // expected source
		];
		yield 'content-language is variant' => [
			'en-ca', // PageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			null, // explicit source
			'en-ca'  // expected source
		];
		yield 'Source variant is given' => [
			null, // PageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			'en-ca', // explicit source
			'en-ca'  // expected source
		];
		yield 'Source variant is a base language' => [
			null, // PageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			'en', // explicit source
			null     // expected source
		];
		yield 'Page language override is variant' => [
			null, // PageBundle content-language
			null, // PageBundle content-language
			'en-ca', // PageLanguage override
			'en', // target
			'en-ca', // explicit source
			'en-ca'  // expected source
		];
	}

	/** @dataProvider provideSiteConfiguration */
	public function testSiteConfiguration(
		bool $isLanguageConversionEnabled,
		bool $shouldParsoidBeUsed,
		bool $shouldPageConfigFactoryBeUsed
	) {
		// Set expected language codes
		$pageBundleLanguageCode = 'zh';
		$titleLanguageCode = 'zh-hans';
		$targetLanguageCode = 'zh-hans';
		$sourceLanguageCode = null;

		// Create mocks

		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			null,
			$titleLanguageCode,
			$targetLanguageCode,
			$sourceLanguageCode
		);
		$languageFactoryMock = $this->getLanguageFactoryMock();
		$targetLanguage = $languageFactoryMock->getLanguage( $targetLanguageCode );
		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguage );
	}

	public static function provideSiteConfiguration() {
		$isLanguageConversionEnabled = false;
		$shouldParsoidBeUsed = false;
		$shouldPageConfigFactoryBeUsed = false;
		yield 'If language conversion is disabled, parsoid and page config factory should not be used' =>
			[ $isLanguageConversionEnabled, $shouldParsoidBeUsed, $shouldPageConfigFactoryBeUsed ];

		$isLanguageConversionEnabled = true;
		$shouldParsoidBeUsed = true;
		$shouldPageConfigFactoryBeUsed = true;
		yield 'If language conversion is enabled, parsoid and page config factory should be used' =>
			[ $isLanguageConversionEnabled, $shouldParsoidBeUsed, $shouldPageConfigFactoryBeUsed ];
	}

	/**
	 * @param bool $shouldParsoidBeUsed
	 * @param bool $shouldPageConfigFactoryBeUsed
	 * @param bool $isLanguageConversionEnabled
	 * @param string|null $pageBundleLanguageCode
	 * @param string|null $contentLanguageOverride
	 * @param string $titleLanguageCode
	 * @param string $targetLanguageCode
	 * @param string|null $sourceLanguageCode
	 *
	 * @return LanguageVariantConverter
	 */
	private function getLanguageVariantConverter(
		bool $shouldParsoidBeUsed,
		bool $shouldPageConfigFactoryBeUsed,
		bool $isLanguageConversionEnabled,
		?string $pageBundleLanguageCode,
		?string $contentLanguageOverride,
		string $titleLanguageCode,
		string $targetLanguageCode,
		?string $sourceLanguageCode
	): LanguageVariantConverter {
		// If Content language is set, use language from there,
		// If PageBundle language code is set, use that
		// Else, fallback to title page language
		$pageLanguageCode = $contentLanguageOverride ?? $pageBundleLanguageCode ?? $titleLanguageCode;
		// The page language code should not be a variant
		$pageLanguageCode = preg_replace( '/-.*$/', '', $pageLanguageCode );

		$shouldSiteConfigBeUsed = true;
		$pageIdentityValue = new PageIdentityValue( 1, NS_MAIN, 'hello_world', PageIdentity::LOCAL );

		// Create the necessary mocks
		$languageFactoryMock = $this->getLanguageFactoryMock();
		$pageLanguage = new Bcp47CodeValue( $pageLanguageCode );
		$sourceLanguage = $sourceLanguageCode ? new Bcp47CodeValue( $sourceLanguageCode ) : null;
		$targetLanguage = new Bcp47CodeValue( $targetLanguageCode );

		$pageConfigMock = $this->getPageConfigMock();
		$parserOptionsMock = $this->createNoOpMock( ParserOptions::class );
		$pageConfigFactoryMock = $this->getPageConfigFactoryMock(
			$shouldPageConfigFactoryBeUsed,
			// Expected arguments to PageConfigFactory mock
			[ $parserOptionsMock, $pageIdentityValue, null, $this->constraintEquals( $pageLanguage ) ],
			$pageConfigMock
		);
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$siteConfigMock = $this->getSiteConfigMock(
			$shouldSiteConfigBeUsed, $pageLanguage, $isLanguageConversionEnabled
		);
		$titleFactoryMock = $this->getTitleFactoryMock( $pageIdentityValue, $titleLanguageCode );

		$parsoidMock = $this->getParsoidMock(
			$shouldParsoidBeUsed,
			[
				$pageConfigMock,
				'variant',
				$pageBundleMock,
				$this->constraintEquals( [
					'variant' => [
						'source' => $sourceLanguage,
						'target' => $targetLanguage,
					]
				] )
			]
		);

		$languageVariantConverter = new LanguageVariantConverter(
			$pageIdentityValue,
			$pageConfigFactoryMock,
			$parsoidMock,
			$siteConfigMock,
			$titleFactoryMock,
			$this->getLanguageConverterFactoryMock(),
			$languageFactoryMock
		);
		if ( $shouldPageConfigFactoryBeUsed ) {
			// supply a mock parser options
			TestingAccessWrapper::newFromObject( $languageVariantConverter )
				->parserOptionsForTest = $parserOptionsMock;
		}

		if ( $contentLanguageOverride ) {
			$languageVariantConverter->setPageLanguageOverride(
				$languageFactoryMock->getLanguage( $contentLanguageOverride )
			);
		}

		return $languageVariantConverter;
	}

	// Mock methods follow

	/**
	 * @param bool $shouldBeCalled
	 * @param array $arguments
	 * @param PageConfig $pageConfig
	 *
	 * @return MockObject|PageConfigFactory
	 */
	private function getPageConfigFactoryMock( bool $shouldBeCalled, array $arguments, PageConfig $pageConfig ) {
		$mock = $this->createMock( PageConfigFactory::class );

		if ( $shouldBeCalled ) {
			$mock->expects( $this->once() )
				->method( 'createFromParserOptions' )
				->with( ...$arguments )
				->willReturn( $pageConfig );
		} else {
			$mock->expects( $this->never() )
				->method( 'createFromParserOptions' );
		}

		return $mock;
	}

	/**
	 * @param bool $shouldBeCalled
	 * @param array $arguments
	 *
	 * @return MockObject|Parsoid
	 */
	private function getParsoidMock( bool $shouldBeCalled, array $arguments ) {
		$mock = $this->createMock( Parsoid::class );
		if ( $shouldBeCalled ) {
			$mock->expects( $this->once() )
				->method( 'pb2pb' )
				->with( ...$arguments );
		} else {
			$mock->expects( $this->never() )
				->method( 'pb2pb' );
		}

		$mock->method( 'implementsLanguageConversionBcp47' )
			->willReturn( true );

		return $mock;
	}

	/**
	 * Mock constraint helper to compare equality when there are
	 * Bcp47Code instances involved.
	 * @param mixed $expected The expected value, with embedded Bcp47Codes
	 * @return Constraint a PHPUnit equality constrait
	 */
	private function constraintEquals( $expected ): Constraint {
		return $this->callback( static function ( $actual ) use ( $expected ) {
			return self::arrayWithCodeEquals( $expected, $actual );
		} );
	}

	/**
	 * Compare two values for equality, using case-insensitive BCP-47 code
	 * comparisons for Bcp47Code instances.
	 * @param mixed $expected
	 * @param mixed $actual
	 * @return bool True if the objects should be considered equal, false otherwise.
	 */
	private static function arrayWithCodeEquals( $expected, $actual ) {
		if ( $actual === $expected ) {
			return true;
		}
		if ( is_array( $actual ) && is_array( $expected ) ) {
			if ( count( $actual ) !== count( $expected ) ) {
				return false;
			}
			foreach ( $expected as $key => $value ) {
				if ( !array_key_exists( $key, $actual ) ) {
					return false;
				}
				if ( !self::arrayWithCodeEquals( $value, $actual[$key] ) ) {
					return false;
				}
			}
			return true;
		}
		if ( $actual instanceof Bcp47Code && $expected instanceof Bcp47Code ) {
			# BCP-47 codes are case insensitive.
			return strcasecmp( $actual->toBcp47Code(), $expected->toBcp47Code() ) == 0;
		}
		return false;
	}

	/**
	 * @param bool $shouldBeCalled
	 * @param Bcp47Code $baseLanguage
	 * @param bool $isLanguageConversionEnabled
	 *
	 * @return MockObject|SiteConfig
	 */
	private function getSiteConfigMock(
		bool $shouldBeCalled,
		Bcp47Code $baseLanguage,
		bool $isLanguageConversionEnabled
	) {
		$mock = $this->createMock( SiteConfig::class );
		if ( $shouldBeCalled ) {
			$mock->expects( $this->once() )
				->method( 'langConverterEnabledBcp47' )
				->with( $this->constraintEquals( $baseLanguage ) )
				->willReturn( $isLanguageConversionEnabled );
		} else {
			$mock->expects( $this->never() )
				->method( 'langConverterEnabledBcp47' );
		}

		return $mock;
	}

	/**
	 * @param PageIdentity $pageIdentity
	 * @param string $languageCode
	 *
	 * @return MockObject|TitleFactory
	 */
	private function getTitleFactoryMock( PageIdentity $pageIdentity, string $languageCode ) {
		$languageMock = $this->getLanguageMock( $languageCode );

		$titleMock = $this->createMock( Title::class );
		$titleMock->method( 'getPageLanguage' )
			->willReturn( $languageMock );

		$mock = $this->createMock( TitleFactory::class );
		$mock->expects( $this->once() )
			->method( 'newFromPageIdentity' )
			->willReturn( $titleMock )
			->with( $pageIdentity );

		return $mock;
	}

	/**
	 * @return MockObject|LanguageFactory
	 */
	private function getLanguageFactoryMock() {
		$mock = $this->createMock( LanguageFactory::class );
		$mock->method( 'getLanguage' )
			->willReturnCallback( function ( $code ) {
				static $seen = [];
				if ( $code instanceof Bcp47Code ) {
					$code = LanguageCode::bcp47ToInternal( $code );
				}
				if ( !isset( $seen[$code] ) ) {
					$seen[$code] = $this->getLanguageMock( $code );
				}
				return $seen[$code];
			} );
		$mock->method( 'getParentLanguage' )
			->willReturnCallback( static function ( $code ) use ( $mock ) {
				if ( $code instanceof Bcp47Code ) {
					$code = LanguageCode::bcp47ToInternal( $code );
				}
				$code = preg_replace( '/-.*$/', '', $code );
				return $mock->getLanguage( $code );
			} );

		return $mock;
	}

	/**
	 * @return MockObject|PageBundle
	 */
	private function getPageBundleMockWithoutLanguage() {
		return $this->getPageBundleMock( null );
	}

	/**
	 * @param string|null $languageCode
	 *
	 * @return MockObject|PageBundle
	 */
	private function getPageBundleMock( ?string $languageCode ) {
		$mock = $this->createMock( PageBundle::class );
		$mock->headers = [
			# T320662: this should probably be a BCP-47 code, not internal
			'content-language' => $languageCode
		];
		$mock->html = 'test message';
		return $mock;
	}

	/**
	 * @return MockObject|PageConfig
	 */
	private function getPageConfigMock() {
		$mock = $this->createNoOpMock( PageConfig::class, [ 'setVariantBcp47' ] );
		return $mock;
	}

	/**
	 * @param string $languageCode
	 *
	 * @return MockObject|Language
	 */
	private function getLanguageMock( $languageCode ): Language {
		$languageMock = $this->createMock( Language::class );
		$languageMock->method( 'getCode' )
			->willReturn( $languageCode );
		$languageMock->method( 'toBcp47Code' )
			->willReturn( LanguageCode::bcp47( $languageCode ) );

		return $languageMock;
	}

	private function getLanguageConverterFactoryMock() {
		$languageConverterFactoryMock = $this->createMock( LanguageConverterFactory::class );
		$languageConverter = $this->createMock( LanguageConverter::class );
		$languageConverter->method( 'convertTo' )
			->willReturnCallback( static function ( $text, $code ) {
				return $text;
			} );
		$languageConverter->method( 'hasVariant' )
			->willReturnCallback( static function ( $code ) {
				return true;
			} );
		$languageConverterFactoryMock->method( 'getLanguageConverter' )
			->willReturn( $languageConverter );

		return $languageConverterFactoryMock;
	}
}
