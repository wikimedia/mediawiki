<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Mocks\MockSiteConfig;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @covers \MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter
 * @group Database
 */
class PageBundleParserOutputConverterIntegrationTest extends MediaWikiIntegrationTestCase {
	public function testLanguageTransfer() {
		$parserOutput = self::getParserOutput( HtmlPageBundle::newEmpty( '' ) );
		$parserOutput->setLanguage( new Bcp47CodeValue( 'de' ) );
		$siteConfig = new MockSiteConfig( [] );
		$pb = PageBundleParserOutputConverter::htmlPageBundleFromParserOutput(
			$parserOutput, $siteConfig, bodyOnly: false,
		);
		$this->assertIsString( $pb->headers['content-language'] );
		$this->assertEquals( 'de', $pb->headers['content-language'] );
	}

	public function testParserOutputFromPageBundleShouldPreserveMetadata() {
		$pageBundle = new HtmlPageBundle(
			html: 'html content',
			parsoid: [],
			mw: [],
			version: '1.x',
			headers: [ 'content-language' => null ]
		);

		$defaultExpiration = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::ParserCacheExpireTime
		);

		$original = new ParserOutput();
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( $defaultExpiration, $output->getCacheExpiry(),
			"Cache expiration doesn't match default expiry." );

		$original->updateCacheExpiry( 100 );
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( 100, $output->getCacheExpiry(),
			"Cache expiration doesn't matched updated reduced expiry." );
	}

	/** @dataProvider provideHtmlPageBundleFromParserOutputAsFullDocument */
	public function testHtmlPageBundleFromParserOutputAsFullDocument(
		ParserOutput $parserOutput,
		string $expectedTitle,
		string $expectedBodyContent,
		string $expectedLanguage,
		// T429391: in the future the @dataProvider may provide non-Parsoid
		// ParserOutputs as well.
		bool $isParsoid = true,
	) {
		$siteConfig = new MockSiteConfig( [] );
		$pageBundle = PageBundleParserOutputConverter::htmlPageBundleFromParserOutput(
			$parserOutput, $siteConfig, bodyOnly: false,
		);

		$html = $pageBundle->html;
		$this->assertStringStartsWith( '<!DOCTYPE html>', $html );
		$this->assertStringContainsString( '<meta charset="utf-8"', $html );
		$this->assertStringContainsString( '<base href=', $html );
		$this->assertStringContainsString( '<title>' . $expectedTitle . '</title>', $html );
		$this->assertStringContainsString( $expectedBodyContent, $html );
		$this->assertStringContainsString( 'class="', $html );
		$this->assertStringContainsString( 'mw-body-content', $html );

		$this->assertStringContainsString( 'lang="' . $expectedLanguage . '"', $html );
		$this->assertSame(
			$expectedLanguage,
			$pageBundle->headers['content-language']
		);
		// Test that this is valid HTML by round-tripping it: parse it to a
		// DOM and reserialize it, and verify that the output is unchanged.
		// This would catch things like (eg) forgetting to close the <body>
		// tag in the 'full document' form.
		$doc = DOMUtils::parseHTML( $html, validateXMLNames: true );
		$roundTripped = "<!DOCTYPE html>\n" .
			DOMCompat::getOuterHTML( $doc->documentElement );
		$this->assertSame( $html, $roundTripped, "Invalid HTML." );
		// Check for the presence or absence of Parsoid-specific features.
		$body = DOMCompat::querySelector( $doc, 'body' );
		$this->assertSame(
			$isParsoid,
			DOMCompat::getClassList( $body )->contains( 'parsoid-body' ),
			'parsoid-body class on body'
		);
		$this->assertSame(
			$isParsoid,
			$body->hasAttribute( 'data-mw-parsoid-version' ),
			'data-mw-parsoid-version on body'
		);
		$this->assertSame(
			$isParsoid,
			$body->hasAttribute( 'data-mw-html-version' ),
			'data-mw-html-version on body'
		);
		$this->assertSame(
			$isParsoid,
			DOMCompat::querySelector( $doc, 'meta[property="mw:htmlVersion"]' ) !== null,
			'mw:htmlVersion meta tag'
		);
	}

	public static function provideHtmlPageBundleFromParserOutputAsFullDocument() {
		$po = self::getParserOutput(
			new HtmlPageBundle(
				html: 'hello world',
				headers: [ 'content-language' => 'zh-Hant-TW' ],
				version: Parsoid::defaultHTMLVersion(),
			)
		);
		$po->setTitle( new TitleValue( NS_MAIN, 'Test_Page' ) );
		$po->setLanguage( new Bcp47CodeValue( 'zh-Hant-TW' ) );
		yield "with language and title" => [ $po, 'Test Page', 'hello world', 'zh-Hant-TW' ];
	}

	private static function getParserOutput(
		HtmlPageBundle $pb, $title = null
	): ParserOutput {
		return PageBundleParserOutputConverter::parserOutputFromPageBundle(
			$pb, title: $title, siteConfig: new MockSiteConfig( [] ),
		);
	}
}
