<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\LanguageVariantConverter;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;

/**
 * @group Database
 * @covers \MediaWiki\Parser\Parsoid\LanguageVariantConverter
 */
class LanguageVariantConverterTest extends MediaWikiIntegrationTestCase {
	public function setUp(): void {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
	}

	public static function provideConvertPageBundleVariant() {
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
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'crh' ]
			),
			null,
			'crh-Latn',
			null,
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
		];
		yield 'Source language is explicit' => [
			new PageBundle(
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'crh' ]
			),
			null,
			'crh-Latn',
			'crh-Cyrl',
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
		];
		yield 'Content language is provided via HTTP header' => [
			new PageBundle(
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[ 'content-language' => 'crh-Cyrl' ]
			),
			'crh',
			'crh-Latn',
			'crh-Cyrl',
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
		];
		yield 'Content language is variant' => [
			new PageBundle(
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			'crh-Cyrl',
			'crh-Latn',
			null,
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
		];
		yield 'No content-language, but source variant provided' => [
			new PageBundle(
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			null,
			'crh-Latn',
			'crh-Cyrl',
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
		];
		yield 'Source variant is a base language code' => [
			new PageBundle(
				'<p>Бутун инсанлар сербестлик, менлик ве укъукъларда мусавий олып дунйагъа келелер.</p>',
				[ 'parsoid-data' ],
				[ 'mw-data' ],
				Parsoid::defaultHTMLVersion(),
				[]
			),
			null,
			'crh-Latn',
			'crh',
			'>Butun insanlar serbestlik, menlik ve uquqlarda musaviy olıp dunyağa keleler.</'
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
		$expectedLanguage ??= $target;

		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$contentLanguage = $this->getLanguageBcp47( $contentLanguage );
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}
		$target = $this->getLanguageBcp47( $target );
		if ( $source ) {
			$source = $this->getLanguageBcp47( $source );
		}

		$outputPageBundle = $languageVariantConverter->convertPageBundleVariant( $pageBundle, $target, $source );

		$html = $outputPageBundle->toInlineAttributeHtml();
		$stripped = preg_replace( ':</?span[^>]*>:', '', $html );
		$this->assertStringContainsString( $expected, $stripped );

		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@<meta http-equiv=\"content-language\" content=\"($expectedLanguage)\"/>@i", $html );
			$this->assertMatchesRegularExpression( "@^$expectedLanguage@i", $outputPageBundle->headers['content-language'] );
		}
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $outputPageBundle->version );
	}

	public static function provideConvertParserOutputVariant() {
		foreach ( self::provideConvertPageBundleVariant() as $name => $case ) {
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
		$expectedLanguage ??= $target;

		$page = $this->getExistingTestPage();
		$languageVariantConverter = $this->getLanguageVariantConverter( $page );
		if ( $contentLanguage ) {
			$contentLanguage = $this->getLanguageBcp47( $contentLanguage );
			$languageVariantConverter->setPageLanguageOverride( $contentLanguage );
		}
		$target = $this->getLanguageBcp47( $target );
		if ( $source ) {
			$source = $this->getLanguageBcp47( $source );
		}

		// Set some misc metadata in $parserOutput so we can verify it was
		// preserved.
		$parserOutput->setExtensionData( 'my-key', 'my-data' );

		$modifiedParserOutput = $languageVariantConverter
			->convertParserOutputVariant( $parserOutput, $target, $source );

		$this->assertSame( 'my-data', $modifiedParserOutput->getExtensionData( 'my-key' ) );

		$html = $modifiedParserOutput->getRawText();
		$stripped = preg_replace( ':</?span[^>]*>:', '', $html );
		$this->assertStringContainsString( $expected, $stripped );
		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@<meta http-equiv=\"content-language\" content=\"($expectedLanguage)\"/>@i", $html );
		}

		$extensionData = $modifiedParserOutput
			->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertEquals( Parsoid::defaultHTMLVersion(), $extensionData['version'] );

		if ( $expectedLanguage !== false ) {
			$this->assertMatchesRegularExpression( "@^$expectedLanguage@i", $extensionData['headers']['content-language'] );
			$this->assertSame( $expectedLanguage, (string)$modifiedParserOutput->getLanguage() );
		}
	}

	private function getLanguageBcp47( $bcp47Code ): Language {
		$languageFactory = $this->getServiceContainer()->getLanguageFactory();
		return $languageFactory->getLanguage( new Bcp47CodeValue( $bcp47Code ) );
	}

	private function getLanguageVariantConverter( PageIdentity $pageIdentity ): LanguageVariantConverter {
		return new LanguageVariantConverter(
			$pageIdentity,
			$this->getServiceContainer()->getParsoidPageConfigFactory(),
			$this->getServiceContainer()->getService( '_Parsoid' ),
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getLanguageConverterFactory(),
			$this->getServiceContainer()->getLanguageFactory()
		);
	}
}
