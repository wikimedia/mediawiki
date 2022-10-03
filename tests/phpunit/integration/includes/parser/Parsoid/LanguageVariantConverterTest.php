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

	public function testConvertPageBundleVariant() {
		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );

		$inputText = '<p>test language conversion</p>';
		$outputText = '>esttay anguagelay onversioncay<';

		$pageBundle = new PageBundle(
			$inputText,
			[ 'parsoid-data' ],
			[ 'mw-data' ],
			Parsoid::defaultHTMLVersion(),
			[ 'content-language' => 'en' ],
			$page->getContentModel()
		);

		$outputPageBundle = $languageVariantConverter->convertPageBundleVariant( $pageBundle, 'en-x-piglatin' );

		$this->assertStringContainsString( $outputText, $outputPageBundle->toHtml() );
		$this->assertEquals( 'en-x-piglatin', $outputPageBundle->headers['content-language'] );
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $outputPageBundle->version );
	}

	public function testConvertParserOutputVariant() {
		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );

		$inputText = '<p>test language conversion</p>';
		$outputText = '>esttay anguagelay onversioncay<';

		$parserOutput = new ParserOutput( $inputText );
		$parserOutput->setExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY,
			[
				'version' => Parsoid::defaultHTMLVersion(),
				'headers' => [
					'content-language' => 'en'
				]
			]
		);

		$modifiedParserOutput = $languageVariantConverter
			->convertParserOutputVariant( $parserOutput, 'en-x-piglatin' );

		$this->assertStringContainsString( $outputText, $modifiedParserOutput->getText() );

		$extensionData = $modifiedParserOutput
			->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertEquals( 'en-x-piglatin', $extensionData['headers']['content-language'] );
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $extensionData['version'] );
	}

	private function getLanguageVariantConverter( PageIdentity $pageIdentity ): LanguageVariantConverter {
		return new LanguageVariantConverter(
			$pageIdentity,
			$this->getServiceContainer()->getParsoidPageConfigFactory(),
			$this->getServiceContainer()->getService( '_Parsoid' ),
			MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidSettings ),
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getTitleFactory()
		);
	}
}
