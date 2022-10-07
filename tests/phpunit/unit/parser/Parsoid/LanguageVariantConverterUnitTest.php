<?php

namespace MediaWiki\Parser\Parsoid;

use InvalidArgumentException;
use Language;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\Config\PageConfig;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use TitleFactory;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;

/**
 * @covers MediaWiki\Parser\Parsoid\LanguageVariantConverter
 */
class LanguageVariantConverterUnitTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideSetConfig */
	public function testSetConfig( bool $shouldPageConfigFactoryBeUsed ) {
		// Decide what should be called and what should not be
		$shouldParsoidBeUsed = true;
		$isLanguageConversionEnabled = true;

		// Set expected language codes
		$pageBundleLanguageCode = 'zh';
		$titleLanguageCode = 'zh-hans';
		$targetLanguageCode = 'zh-hans';
		$sourceLanguageCode = null;

		// Create mocks
		$parsoidSettings = [];
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
			$sourceLanguageCode,
			$parsoidSettings
		);

		if ( !$shouldPageConfigFactoryBeUsed ) {
			$languageVariantConverter->setPageConfig( $pageConfigMock );
		}

		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguageCode );
	}

	public function provideSetConfig() {
		yield 'PageConfigFactory should not be used if PageConfig is set' => [ false ];

		yield 'PageConfigFactory should be used if PageConfig is not set' => [ true ];
	}

	/** @dataProvider provideSourceLanguage */
	public function testSourceLanguage(
		?string $pageBundleLanguageCode,
		string $titleLanguageCode,
		?string $sourceLanguageCode,
		?string $contentLanguage
	) {
		// Decide what should be called and what should not be
		$shouldParsoidBeUsed = true;
		$shouldPageConfigFactoryBeUsed = true;
		$isLanguageConversionEnabled = true;

		// Set expected language codes
		$targetLanguageCode = 'zh-hans';

		$parsoidSettings = [];
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
			$contentLanguage,
			$titleLanguageCode,
			$targetLanguageCode,
			$sourceLanguageCode,
			$parsoidSettings
		);

		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguageCode, $sourceLanguageCode );
	}

	public function provideSourceLanguage() {
		yield 'Content language is used when available' => [ 'sr-el', 'sr-ec', null, 'sr' ];
		yield 'PageBundle language is used when content language is not available' =>
			[ 'en', 'en-gb', null, null ];
		yield 'Title page language is used if PageBundle and content language are not available' =>
			[ null, 'en-ca', null, null ];
		yield 'Source language is used if given' =>
			[ null, 'en-ca', 'en-gb', null ];
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
		$parsoidSettings = [];

		if ( !$isLanguageConversionEnabled ) {
			$this->expectException( InvalidArgumentException::class );
			$this->expectExceptionMessage( 'LanguageConversion is not supported' );
		}

		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			null,
			$titleLanguageCode,
			$targetLanguageCode,
			$sourceLanguageCode,
			$parsoidSettings
		);
		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguageCode );
	}

	public function provideSiteConfiguration() {
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
	 * @param string|null $contentLanguage
	 * @param string $titleLanguageCode
	 * @param string $targetLanguageCode
	 * @param string|null $sourceLanguageCode
	 * @param array $parsoidSettings
	 *
	 * @return LanguageVariantConverter
	 */
	private function getLanguageVariantConverter(
		bool $shouldParsoidBeUsed,
		bool $shouldPageConfigFactoryBeUsed,
		bool $isLanguageConversionEnabled,
		?string $pageBundleLanguageCode,
		?string $contentLanguage,
		string $titleLanguageCode,
		string $targetLanguageCode,
		?string $sourceLanguageCode,
		array $parsoidSettings
	): LanguageVariantConverter {
		// If Content language is set, use language from there,
		// If PageBundle language code is set, use that
		// Else, fallback to title page language
		$pageLanguageCode = $contentLanguage ?? $pageBundleLanguageCode ?? $titleLanguageCode;

		$shouldSiteConfigBeUsed = true;
		$parsoidSettings = [];
		$pageIdentityValue = new PageIdentityValue( 1, NS_MAIN, 'hello_world', PageIdentity::LOCAL );

		// Create the necessary mocks
		$pageConfigMock = $this->getPageConfigMock();
		$pageConfigFactoryMock = $this->getPageConfigFactoryMock(
			$shouldPageConfigFactoryBeUsed,
			// Expected arguments to PageConfigFactory mock
			[ $pageIdentityValue, null, null, null, $pageLanguageCode, $parsoidSettings ],
			$pageConfigMock
		);
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$siteConfigMock = $this->getSiteConfigMock(
			$shouldSiteConfigBeUsed, $pageLanguageCode, $isLanguageConversionEnabled
		);
		$titleFactoryMock = $this->getTitleFactoryMock( $pageIdentityValue, $titleLanguageCode );
		$parsoidMock = $this->getParsoidMock(
			$shouldParsoidBeUsed,
			[
				$pageConfigMock,
				'variant',
				$pageBundleMock,
				[ 'variant' => [ 'source' => $sourceLanguageCode, 'target' => $targetLanguageCode ] ]
			]
		);

		$languageVariantConverter = new LanguageVariantConverter(
			$pageIdentityValue,
			$pageConfigFactoryMock,
			$parsoidMock,
			$parsoidSettings,
			$siteConfigMock,
			$titleFactoryMock
		);

		if ( $contentLanguage ) {
			$languageVariantConverter->setPageContentLanguage( $contentLanguage );
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
				->method( 'create' )
				->with( ...$arguments )
				->willReturn( $pageConfig );
		} else {
			$mock->expects( $this->never() )
				->method( 'create' );
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

		return $mock;
	}

	/**
	 * @param bool $shouldBeCalled
	 * @param string $baseLanguageCode
	 * @param bool $isLanguageConversionEnabled
	 *
	 * @return MockObject|SiteConfig
	 */
	private function getSiteConfigMock(
		bool $shouldBeCalled,
		string $baseLanguageCode,
		bool $isLanguageConversionEnabled
	) {
		$mock = $this->createMock( SiteConfig::class );
		if ( $shouldBeCalled ) {
			$mock->expects( $this->once() )
				->method( 'langConverterEnabledForLanguage' )
				->with( $baseLanguageCode )
				->willReturn( $isLanguageConversionEnabled );
		} else {
			$mock->expects( $this->never() )
				->method( 'langConverterEnabledForLanguage' );
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
		$languageMock = $this->createMock( Language::class );
		$languageMock->method( 'getCode' )
			->willReturn( $languageCode );

		$titleMock = $this->createMock( Title::class );
		$titleMock->method( 'getPageLanguage' )
			->willReturn( $languageMock );

		$mock = $this->createMock( TitleFactory::class );
		$mock->expects( $this->once() )
			->method( 'castFromPageIdentity' )
			->willReturn( $titleMock )
			->with( $pageIdentity );

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
			'content-language' => $languageCode
		];
		return $mock;
	}

	/**
	 * @return MockObject|PageConfig
	 */
	private function getPageConfigMock() {
		$mock = $this->createNoOpMock( PageConfig::class, [ 'setVariant' ] );
		return $mock;
	}
}
