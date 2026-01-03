<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\CodeHighlighterMetadata;
use MediaWiki\Parser\ParserOutput;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Content\CodeHighlighterMetadata
 */
class CodeHighlighterMetadataTest extends MediaWikiUnitTestCase {

	public function testAddToParserOutput(): void {
		$parserOutput = new ParserOutput();
		$metadata = new CodeHighlighterMetadata(
			modules: [ 'ext.example' ],
			moduleStyles: [ 'ext.example.styles' ],
			categories: [ 'Example tracking category' ],
		);

		$metadata->addToParserOutput( $parserOutput );

		$this->assertSame( [ 'ext.example' ], $parserOutput->getModules() );
		$this->assertSame( [ 'ext.example.styles' ], $parserOutput->getModuleStyles() );
		$this->assertSame( [ 'Example tracking category' ], $parserOutput->getCategoryNames() );
	}
}
