<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\OutputTransform\OutputTransformPipeline;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\LanguageVariantConverter;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Mocks\MockSiteConfig;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Parser\Parsoid\LanguageVariantConverter
 */
class LanguageVariantConverterUnitTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideSourceLanguage */
	public function testSourceLanguage(
		?string $pageBundleLanguageCode,
		?string $titleLanguageCode,
		?string $contentLanguageOverride,
		?string $targetLanguageCode,
		?string $sourceLanguageCode,
		?string $expectedSourceCode
	) {
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
			$pageBundleLanguageCode,
			$contentLanguageOverride,
			$titleLanguageCode,
			$targetLanguageCode, // expected target language
			$expectedSourceCode
		);

		$targetLanguage = new Bcp47CodeValue( $targetLanguageCode );
		$sourceLanguage = $sourceLanguageCode ? new Bcp47CodeValue( $sourceLanguageCode ) : null;

		// convertParserOutputVariant is the method that exercises the language
		// detection logic; it avoids the full-document assembly in convertPageBundleVariant
		// which requires MediaWiki services unavailable in unit tests.
		$parserOutput = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			$pageBundleMock, siteConfig: new MockSiteConfig( [] )
		);
		$languageVariantConverter->convertParserOutputVariant( $parserOutput, $targetLanguage, $sourceLanguage );
	}

	public static function provideSourceLanguage() {
		yield 'content-language in HtmlPageBundle' => [
			'sr', // HtmlPageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'sr-Cyrl', // target
			'sr-Cyrl', // explicit source
			'sr-Cyrl'  // expected source
		];
		yield 'content-language but no source language' => [
			'en', // HtmlPageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			null, // explicit source
			null     // expected source
		];
		yield 'content-language is variant' => [
			'en-ca', // HtmlPageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			null, // explicit source
			'en-ca'  // expected source
		];
		yield 'Source variant is given' => [
			null, // HtmlPageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			'en-ca', // explicit source
			'en-ca'  // expected source
		];
		yield 'Source variant is a base language' => [
			null, // HtmlPageBundle content-language
			null, // Title PageLanguage
			null, // PageLanguage override
			'en', // target
			'en', // explicit source
			null     // expected source
		];
		yield 'Page language override is variant' => [
			null, // HtmlPageBundle content-language
			null, // Title content-language
			'en-ca', // PageLanguage override
			'en', // target
			'en-ca', // explicit source
			'en-ca'  // expected source
		];
	}

	/**
	 * @param string|null $pageBundleLanguageCode
	 * @param string|null $contentLanguageOverride
	 * @param string $titleLanguageCode
	 * @param string $targetLanguageCode
	 * @param string|null $sourceLanguageCode
	 *
	 * @return LanguageVariantConverter
	 */
	private function getLanguageVariantConverter(
		?string $pageBundleLanguageCode,
		?string $contentLanguageOverride,
		string $titleLanguageCode,
		string $targetLanguageCode,
		?string $sourceLanguageCode
	): LanguageVariantConverter {
		// If Content language is set, use language from there,
		// If HtmlPageBundle language code is set, use that
		// Else, fallback to title page language
		$pageLanguageCode = $contentLanguageOverride ?? $pageBundleLanguageCode ?? $titleLanguageCode;
		// The page language code should not be a variant
		$pageLanguageCode = preg_replace( '/-.*$/', '', $pageLanguageCode );

		$pageIdentityValue = PageIdentityValue::localIdentity( 1, NS_MAIN, 'hello_world' );

		// Create the necessary mocks
		$languageFactoryMock = $this->getLanguageFactoryMock();
		$pageLanguage = new Bcp47CodeValue( $pageLanguageCode );
		$sourceLanguage = $sourceLanguageCode ? new Bcp47CodeValue( $sourceLanguageCode ) : null;
		$targetLanguage = new Bcp47CodeValue( $targetLanguageCode );

		$parserOptionsMock = $this->createMock( ParserOptions::class );
		$parserOptionsMock->expects( $this->once() )
			->method( 'setUseParsoid' )
			->with( true );
		$parserOptionsMock->expects( $this->once() )
			->method( 'setTargetLanguage' )
			->with( $languageFactoryMock->getLanguage( $pageLanguage ) );
		$parserOptionsMock->expects( $this->once() )
			->method( 'setVariant' )
			->with( $languageFactoryMock->getLanguage( $targetLanguage ) );

		$titleFactoryMock = $this->getTitleFactoryMock( $pageIdentityValue, $titleLanguageCode );

		$languageVariantConverter = new LanguageVariantConverter(
			$this->getLanguageConverterPipelineMock( $parserOptionsMock ),
			$languageFactoryMock,
			$this->createStub( SiteConfig::class ),
			$titleFactoryMock,
			$pageIdentityValue,
		);
		// supply a mock parser options
		TestingAccessWrapper::newFromObject( $languageVariantConverter )
				->parserOptionsForTest = $parserOptionsMock;

		if ( $contentLanguageOverride ) {
			$languageVariantConverter->setPageLanguageOverride(
				$languageFactoryMock->getLanguage( $contentLanguageOverride )
			);
		}

		return $languageVariantConverter;
	}

	// Mock methods follow

	/**
	 * Mock constraint helper to compare equality when there are
	 * Bcp47Code instances involved.
	 * @param mixed $expected The expected value, with embedded Bcp47Codes
	 * @return Constraint a PHPUnit equality constraint
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
	 * @return MockObject|HtmlPageBundle
	 */
	private function getPageBundleMockWithoutLanguage() {
		return $this->getPageBundleMock( null );
	}

	/**
	 * @param string|null $languageCode
	 *
	 * @return MockObject|HtmlPageBundle
	 */
	private function getPageBundleMock( ?string $languageCode ) {
		$mock = HtmlPageBundle::newEmpty( 'test message' );
		if ( $languageCode !== null ) {
			$mock->headers = [
				'content-language' => LanguageCode::bcp47( $languageCode ),
			];
		}
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

	private function getLanguageConverterPipelineMock( $popts ) {
		$languageConverterPipelineMock = $this->createMock( OutputTransformPipeline::class );
		$languageConverterPipelineMock->expects( $this->once() )
			->method( 'run' )
			->with( $this->anything(), $popts, $this->anything() )
			->willReturnArgument( 0 );

		return $languageConverterPipelineMock;
	}
}
