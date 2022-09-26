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
	}

	public function provideParserOutputFromPageBundle() {
		yield 'should convert PageBundle containing data-parsoid and data-mw' => [
			new PageBundle(
				'html content',
				[ 'ids' => '1.33' ],
				[ 'ids' => '1.33' ]
			)
		];

		yield 'should convert PageBundle that contains no data-parsoid or data-mw' => [
			new PageBundle( 'html content' )
		];
	}

	/** @dataProvider providePageBundleFromParserOutput */
	public function testPageBundleFromParserOutput( ParserOutput $parserOutput ) {
		$pageBundle = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );

		$this->assertSame( $parserOutput->getRawText(), $pageBundle->html );

		$extensionData = $parserOutput->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY );
		$this->assertSame( $extensionData['parsoid'] ?? [], $pageBundle->parsoid );
		$this->assertSame( $extensionData['mw'] ?? [], $pageBundle->mw );
	}

	public function providePageBundleFromParserOutput() {
		yield 'should convert ParsoidOutput containing data-parsoid and data-mw' => [
			$this->getParsoidOutput(
				'hello world',
				[ 'parsoid' => [ 'ids' => '1.22' ] ],
				[ 'mw' => [] ]
			)
		];

		yield 'should convert ParsoidOutput that does not contain data-parsoid or data-mw' => [
			$this->getParsoidOutput( 'hello world', null, null )
		];
	}

	private function getParsoidOutput( string $rawText, ?array $parsoidData, ?array $mwData ): ParserOutput {
		$parserOutput = new ParserOutput( $rawText );
		$parserOutput->setExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY,
			$parsoidData,
			$mwData
		);

		return $parserOutput;
	}
}
