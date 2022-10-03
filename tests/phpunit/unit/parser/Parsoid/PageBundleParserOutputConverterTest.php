<?php

namespace MediaWiki\Parser\Parsoid;

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
	}

	public function provideParserOutputFromPageBundle() {
		yield 'should convert PageBundle containing data-parsoid and data-mw' => [
			new PageBundle(
				'html content',
				[ 'ids' => '1.33' ],
				[ 'ids' => '1.33' ],
				'1.x',
				[ 'content-language' => 'abc' ]
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
		$this->assertSame( $extensionData['version'] ?? null, $pageBundle->version );
		$this->assertSame( $extensionData['headers'] ?? [], $pageBundle->headers );
		$this->assertSame( $extensionData['headers']['content-language'], $pageBundle->headers['content-language'] );
	}

	public function providePageBundleFromParserOutput() {
		yield 'should convert ParsoidOutput containing data-parsoid and data-mw' => [
			$this->getParsoidOutput(
				'hello world',
				[
					'parsoid' => [ 'ids' => '1.22' ],
					'mw' => [],
					'version' => '2.x',
					'headers' => [ 'content-language' => 'xyz' ]
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
