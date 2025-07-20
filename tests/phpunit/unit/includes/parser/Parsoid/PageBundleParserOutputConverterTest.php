<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiUnitTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter
 */
class PageBundleParserOutputConverterTest extends MediaWikiUnitTestCase {
	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundle( HtmlPageBundle $pageBundle ) {
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle );
		$this->assertSame( $pageBundle->html, $output->getRawText() );

		$extensionData = $output->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertSame( $pageBundle->mw, $extensionData['mw'] );
		$this->assertSame( $pageBundle->parsoid, $extensionData['parsoid'] );
		$this->assertSame( $pageBundle->headers, $extensionData['headers'] );

		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$this->assertSame( $pageBundle->headers['content-language'], $extensionData['headers']['content-language'] );
			$this->assertSame( $pageBundle->headers['content-language'], (string)$output->getLanguage() );
		}

		$this->assertSame( $pageBundle->version, $extensionData['version'] );
		$this->assertSame( $pageBundle->contentmodel, $extensionData['contentmodel'] );
	}

	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundleShouldPreserveMetadata( HtmlPageBundle $pageBundle ) {
		// Create a ParserOutput with some metadata properties already set.
		$original = new ParserOutput();
		$original->setExtensionData( 'test-key', 'test-data' );
		$original->setOutputFlag( ParserOutputFlags::NO_GALLERY );
		$original->setPageProperty( 'forcetoc', '' );
		$original->recordOption( 'test1' );
		$original->recordOption( 'test2' );
		$original->setLanguage( new Bcp47CodeValue( 'fr' ) );

		// This should preserve the metadata.
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( $pageBundle->html, $output->getRawText() );

		// Check the page bundle data
		$extensionData = $output->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertSame( $pageBundle->mw, $extensionData['mw'] );
		$this->assertSame( $pageBundle->parsoid, $extensionData['parsoid'] );
		$this->assertSame( $pageBundle->headers, $extensionData['headers'] );
		$this->assertSame( $pageBundle->headers['content-language'], $extensionData['headers']['content-language'] );
		$this->assertSame( $pageBundle->version, $extensionData['version'] );
		$this->assertSame( $pageBundle->contentmodel, $extensionData['contentmodel'] );

		// Check our additional metadata properties
		$this->assertSame( 'test-data', $output->getExtensionData( 'test-key' ) );
		$this->assertSame( true, $output->getOutputFlag( ParserOutputFlags::NO_GALLERY ) );
		$this->assertSame( '', $output->getPageProperty( 'forcetoc' ) );
		$this->assertSame( [ 'test1', 'test2' ], $output->getUsedOptions() );

		// Some meta-data can be overridden
		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$this->assertSame(
				$pageBundle->headers['content-language'],
				(string)$output->getLanguage()
			);
		} else {
			$this->assertSame( 'fr', (string)$output->getLanguage() );
		}

		// Check that $original and $output can be modified independently of each other
		$original->setRawText( 'new text version' );
		$this->assertNotSame( 'new text version', $output->getRawText() );
	}

	public static function provideParserOutputFromPageBundle() {
		yield 'should convert HtmlPageBundle containing data-parsoid and data-mw' => [
			new HtmlPageBundle(
				'html content',
				[ 'ids' => '1.33' ],
				[ 'ids' => '1.33' ],
				'1.x',
				[ 'content-language' => 'abc' ],
				'testing'
			)
		];

		yield 'should convert HtmlPageBundle that contains no data-parsoid or data-mw' => [
			new HtmlPageBundle(
				'html content',
				[],
				[],
				'1.x',
				[ 'content-language' => null ]
			)
		];
	}

	/** @dataProvider providePageBundleFromParserOutput */
	public function testPageBundleFromParserOutput( ParserOutput $parserOutput ) {
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );

		$this->assertSame( $parserOutput->getRawText(), $pageBundle->html );

		$extensionData = $parserOutput->getExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY
		);

		$this->assertSame( $extensionData['parsoid'] ?? [ 'ids' => [] ], $pageBundle->parsoid );
		$this->assertSame( $extensionData['mw'] ?? null, $pageBundle->mw );

		// NOTE: We default to "0.0.0" as a fix for T325137. We can go back to null
		//       once HtmlPageBundle::responseData is more robust.
		$this->assertSame( $extensionData['version'] ?? '0.0.0', $pageBundle->version );

		$this->assertSame( $extensionData['headers'] ?? [], $pageBundle->headers );
		$this->assertSame( $extensionData['contentmodel'] ?? null, $pageBundle->contentmodel );

		if ( isset( $extensionData['headers']['content-language'] ) ) {
			$this->assertSame(
				$extensionData['headers']['content-language'],
				$pageBundle->headers['content-language']
			);
		}

		if ( $parserOutput->getLanguage() ) {
			$this->assertSame(
				$parserOutput->getLanguage(),
				$pageBundle->headers['content-language']
			);
		}
	}

	public static function providePageBundleFromParserOutput() {
		yield 'should convert ParsoidOutput containing data-parsoid and data-mw' => [
			self::getParsoidOutput(
				'hello world',
				[
					'parsoid' => [ 'ids' => '1.22' ],
					'mw' => [],
					'version' => '2.x',
					'headers' => [ 'content-language' => 'xyz' ],
					'testing'
				]
			)
		];

		yield 'should convert ParsoidOutput that does not contain data-parsoid or data-mw' => [
			self::getParsoidOutput(
				'hello world',
				[
					'parsoid' => null,
					'mw' => null,
					'version' => null,
					'headers' => [ 'content-language' => null ]
				]
			)
		];
	}

	public function testLanguageTransfer() {
		$parserOutput = new ParserOutput( '' );
		$parserOutput->setLanguage( new Bcp47CodeValue( 'de' ) );
		$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
		$this->assertIsString( $pb->headers['content-language'] );
		$this->assertEquals( 'de', $pb->headers['content-language'] );
	}

	private static function getParsoidOutput(
		string $rawText,
		?array $pageBundleData
	): ParserOutput {
		$parserOutput = new ParserOutput( $rawText );
		$parserOutput->setExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY, $pageBundleData
		);

		return $parserOutput;
	}
}
