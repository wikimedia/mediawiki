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

		// Create mocks
		$parsoidSettings = [];
		$pageConfigMock = $this->getPageConfigMock();
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );

		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			$titleLanguageCode,
			$targetLanguageCode,
			$parsoidSettings,
			$pageConfigMock,
			$pageBundleMock
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
	public function testSourceLanguage( ?string $pageBundleLanguageCode, string $titleLanguageCode ) {
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
		$pageConfigMock = $this->getPageConfigMock();

		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			$titleLanguageCode,
			$targetLanguageCode,
			$parsoidSettings,
			$pageConfigMock,
			$pageBundleMock
		);

		$languageVariantConverter->convertPageBundleVariant( $pageBundleMock, $targetLanguageCode );
	}

	public function provideSourceLanguage() {
		yield 'PageBundle language is used as source when available' => [ 'en', 'en-gb' ];
		yield 'Title page language is used if PageBundle language is not available' => [ null, 'en-ca' ];
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

		// Create mocks
		$parsoidSettings = [];
		$pageConfigMock = $this->getPageConfigMock();
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );

		if ( !$isLanguageConversionEnabled ) {
			$this->expectException( InvalidArgumentException::class );
			$this->expectDeprecationMessageMatches( '/LanguageConversion is not enabled*/' );
		}

		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$languageVariantConverter = $this->getLanguageVariantConverter(
			$shouldParsoidBeUsed,
			$shouldPageConfigFactoryBeUsed,
			$isLanguageConversionEnabled,
			$pageBundleLanguageCode,
			$titleLanguageCode,
			$targetLanguageCode,
			$parsoidSettings,
			$pageConfigMock,
			$pageBundleMock
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

	private function getLanguageVariantConverter(
		bool $shouldParsoidBeUsed,
		bool $shouldPageConfigFactoryBeUsed,
		bool $isLanguageConversionEnabled,
		?string $pageBundleLanguageCode,
		string $titleLanguageCode,
		string $targetLanguageCode,
		array $parsoidSettings
	): LanguageVariantConverter {
		// If PageBundle language code is set, use that else, fallback to title page language
		$sourceLanguageCode = $pageBundleLanguageCode ?? $titleLanguageCode;

		$shouldSiteConfigBeUsed = true;
		$parsoidSettings = [];
		$pageIdentityValue = new PageIdentityValue( 1, NS_MAIN, 'hello_world', PageIdentity::LOCAL );

		// Create the necessary mocks
		$pageConfigMock = $this->getPageConfigMock();
		$pageConfigFactoryMock = $this->getPageConfigFactoryMock(
			$shouldPageConfigFactoryBeUsed,
			// Expected arguments to PageConfigFactory mock
			[ $pageIdentityValue, null, null, null, $sourceLanguageCode, $parsoidSettings ],
			$pageConfigMock
		);
		$pageBundleMock = $this->getPageBundleMock( $pageBundleLanguageCode );
		$siteConfigMock = $this->getSiteConfigMock(
			$shouldSiteConfigBeUsed, $sourceLanguageCode, $isLanguageConversionEnabled
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

		return new LanguageVariantConverter(
			$pageIdentityValue,
			$pageConfigFactoryMock,
			$parsoidMock,
			$parsoidSettings,
			$siteConfigMock,
			$titleFactoryMock
		);
	}

	// Mock methods follow
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

	private function getSiteConfigMock(
		bool $shouldBeCalled,
		string $sourceLanguageCode,
		bool $isLanguageConversionEnabled
	) {
		$mock = $this->createMock( SiteConfig::class );
		if ( $shouldBeCalled ) {
			$mock->expects( $this->once() )
				->method( 'langConverterEnabledForLanguage' )
				->with( $sourceLanguageCode )
				->willReturn( $isLanguageConversionEnabled );
		} else {
			$mock->expects( $this->never() )
				->method( 'langConverterEnabledForLanguage' );
		}

		return $mock;
	}

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

	private function getPageBundleMockWithoutLanguage() {
		return $this->getPageBundleMock( null );
	}

	private function getPageBundleMock( ?string $languageCode ) {
		$mock = $this->createMock( PageBundle::class );
		$mock->headers = [
			'content-language' => $languageCode
		];
		return $mock;
	}

	private function getPageConfigMock() {
		$mock = $this->createNoOpMock( PageConfig::class );
		return $mock;
	}
}
