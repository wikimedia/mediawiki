<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\PageIdentity;
use MediaWikiIntegrationTestCase;
use ParserOutput;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;

/**
 * @group Database
 * @covers MediaWiki\Parser\Parsoid\LanguageVariantConverter
 */
class LanguageVariantConverterTest extends MediaWikiIntegrationTestCase {
	public function setUp(): void {
		// enable Pig Latin variant conversion
		$this->overrideConfigValue( 'UsePigLatinVariant', true );
	}

	public function provideConvertPageBundleVariant() {
		yield 'No source or base, rely on page language (en)' => [
			new PageBundle(
				'<p>test language conversion</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			null,
			'en-x-piglatin',
			null,
			'>esttay anguagelay onversioncay<'
		];
		yield 'Source variant is base language' => [
			new PageBundle(
				'<p>test language conversion</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'en' ]
			),
			null,
			'en-x-piglatin',
			'en',
			'>esttay anguagelay onversioncay<'
		];
		yield 'Source language is null' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'sr' ]
			),
			null,
			'sr-el',
			null,
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'Source language is explicit' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'sr' ]
			),
			null,
			'sr-el',
			'sr-ec',
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'Content language is provided via HTTP header' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'sr-ec' ]
			),
			'sr',
			'sr-el',
			'sr-ec',
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'Content language is variant' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			'sr-ec',
			'sr-el',
			null,
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'No content-language, but source variant provided' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			null,
			'sr-el',
			'sr-ec',
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'Source variant is a base language code' => [
			new PageBundle(
				'<p>Ово је тестна страница</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			null,
			'sr-el',
			'sr',
			'>Ovo je testna stranica<',
			'sr-el|sr-Latn' // sr-el is accepted for backwards compatibility for now
		];
		yield 'Base language does not support variants' => [
			new PageBundle(
				'<p>Hallo Wereld</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			'nl',
			'nl-be',
			null,
			'>Hallo Wereld<',
			false // The output language is currently not indicated. Should be expected to be 'nl' in the future.
		];
	}

	/**
	 * @dataProvider provideConvertPageBundleVariant
	 */
	public function testConvertPageBundleVariant(
		PageBundle $pageBundle,
		$contentLanguage,
		$target,
		$source,
		$expected,
		$expectedLanguage = null
	) {
		if ( $expectedLanguage === null ) {
			$expectedLanguage = $target;
		}

		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}

		$outputPageBundle = $languageVariantConverter->convertPageBundleVariant( $pageBundle, $target, $source );

		$html = $outputPageBundle->toHtml();
		$this->assertStringContainsString( $expected, $html );

		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@<meta http-equiv=\"content-language\" content=\"($expectedLanguage)\"/>@", $html );
			$this->assertMatchesRegularExpression( "@^$expectedLanguage@", $outputPageBundle->headers['content-language'] );
		}
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $outputPageBundle->version );
	}

	public function provideConvertParserOutputVariant() {
		foreach ( $this->provideConvertPageBundleVariant() as $name => $case ) {
			$case[0] = PageBundleParserOutputConverter::parserOutputFromPageBundle( $case[0] );
			yield $name => $case;
		}
	}

	/**
	 * @dataProvider provideConvertParserOutputVariant
	 */
	public function testConvertParserOutputVariant(
		ParserOutput $parserOutput,
		$contentLanguage,
		$target,
		$source,
		$expected,
		$expectedLanguage = null
	) {
		if ( $expectedLanguage === null ) {
			$expectedLanguage = $target;
		}

		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}

		$modifiedParserOutput = $languageVariantConverter
			->convertParserOutputVariant( $parserOutput, $target, $source );

		$html = $modifiedParserOutput->getRawText();
		$this->assertStringContainsString( $expected, $html );
		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@<meta http-equiv=\"content-language\" content=\"($expectedLanguage)\"/>@", $html );
		}

		$extensionData = $modifiedParserOutput
			->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $extensionData['version'] );

		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@^$expectedLanguage@", $extensionData['headers']['content-language'] );
		}
	}

	private function getLanguageVariantConverter( PageIdentity $pageIdentity ): LanguageVariantConverter {
		return new LanguageVariantConverter(
			$pageIdentity,
			$this->getServiceContainer()->getParsoidPageConfigFactory(),
			$this->getServiceContainer()->getService( '_Parsoid' ),
			MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidSettings ),
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getLanguageFactory()
		);
	}
}
