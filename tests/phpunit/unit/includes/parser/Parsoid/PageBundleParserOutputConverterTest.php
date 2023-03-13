<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Parser\ParserOutputFlags;
use MediaWikiUnitTestCase;
use ParserOutput;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @covers MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter
 */
class PageBundleParserOutputConverterTest extends MediaWikiUnitTestCase {
	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundle( PageBundle $pageBundle ) {
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle );
		$this->assertSame( $pageBundle->html, $output->getRawText() );

		$extensionData = $output->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertSame( $pageBundle->mw, $extensionData['mw'] );
		$this->assertSame( $pageBundle->parsoid, $extensionData['parsoid'] );
		$this->assertSame( $pageBundle->headers, $extensionData['headers'] );
		$this->assertSame( $pageBundle->headers['content-language'], $extensionData['headers']['content-language'] );
		$this->assertSame( $pageBundle->version, $extensionData['version'] );
		$this->assertSame( $pageBundle->contentmodel, $extensionData['contentmodel'] );
	}

	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundleShouldPreserveMetadata( PageBundle $pageBundle ) {
		// Create a ParserOutput with some metadata properties already set.
		$output = new ParserOutput();
		$output->setExtensionData( 'test-key', 'test-data' );
		$output->setOutputFlag( ParserOutputFlags::NO_GALLERY );
		$output->setPageProperty( 'forcetoc', '' );

		// This should preserve the metadata.
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $output );
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
	}

	public function provideParserOutputFromPageBundle() {
		yield 'should convert PageBundle containing data-parsoid and data-mw' => [
			new PageBundle(
				'html content',
				[ 'ids' => '1.33' ],
				[ 'ids' => '1.33' ],
				'1.x',
				[ 'content-language' => 'abc' ],
				'testing'
			)
		];

		yield 'should convert PageBundle that contains no data-parsoid or data-mw' => [
			new PageBundle(
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

		$this->assertSame( $extensionData['parsoid'] ?? [], $pageBundle->parsoid );
		$this->assertSame( $extensionData['mw'] ?? [], $pageBundle->mw );

		// NOTE: We default to "0.0.0" as a fix for T325137. We can go back to null
		//       once PageBundle::responseData is more robust.
		$this->assertSame( $extensionData['version'] ?? '0.0.0', $pageBundle->version );

		$this->assertSame( $extensionData['headers'] ?? [], $pageBundle->headers );
		$this->assertSame( $extensionData['headers']['content-language'], $pageBundle->headers['content-language'] );
		$this->assertSame( $extensionData['contentmodel'] ?? null, $pageBundle->contentmodel );
	}

	public function providePageBundleFromParserOutput() {
		yield 'should convert ParsoidOutput containing data-parsoid and data-mw' => [
			$this->getParsoidOutput(
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
			$this->getParsoidOutput(
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

	private function getParsoidOutput(
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
