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
			'>Ovo je testna stranica<'
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
			'>Ovo je testna stranica<'
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
			'>Ovo je testna stranica<'
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
			'>Ovo je testna stranica<'
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
			'>Ovo je testna stranica<'
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
			'>Ovo je testna stranica<'
		];
	}

	/**
	 * @dataProvider provideConvertPageBundleVariant
	 */
	public function testConvertPageBundleVariant( PageBundle $pageBundle, $contentLanguage, $target, $source, $expected ) {
		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}

		$outputPageBundle = $languageVariantConverter->convertPageBundleVariant( $pageBundle, $target, $source );

		$html = $outputPageBundle->toHtml();
		$this->assertStringContainsString( $expected, $html );
		$this->assertStringContainsString( "<meta http-equiv=\"content-language\" content=\"$target\"/>", $html );
		$this->assertEquals( $target, $outputPageBundle->headers['content-language'] );
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
	public function testConvertParserOutputVariant( ParserOutput $parserOutput, $contentLanguage, $target, $source, $expected ) {
		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}

		$modifiedParserOutput = $languageVariantConverter
			->convertParserOutputVariant( $parserOutput, $target, $source );

		$html = $modifiedParserOutput->getRawText();
		$this->assertStringContainsString( $expected, $html );
		$this->assertStringContainsString( "<meta http-equiv=\"content-language\" content=\"$target\"/>", $html );

		$extensionData = $modifiedParserOutput
			->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertEquals( $target, $extensionData['headers']['content-language'] );
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $extensionData['version'] );
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
