<?php

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Search\ParserOutputSearchDataExtractor;
use MediaWiki\Title\Title;

/**
 * @group Search
 * @covers \MediaWiki\Search\ParserOutputSearchDataExtractor
 * @group Database
 */
class ParserOutputSearchDataExtractorTest extends MediaWikiLangTestCase {

	public function testGetCategories() {
		$categories = [
			'Foo_bar' => 'Bar',
			'New_page' => ''
		];

		$parserOutput = new ParserOutput( '', [], $categories );

		$searchDataExtractor = new ParserOutputSearchDataExtractor();

		$this->assertEquals(
			[ 'Foo bar', 'New page' ],
			$searchDataExtractor->getCategories( $parserOutput )
		);
	}

	public function testGetExternalLinks() {
		$parserOutput = new ParserOutput();

		$parserOutput->addExternalLink( 'https://foo' );
		$parserOutput->addExternalLink( 'https://bar' );

		$searchDataExtractor = new ParserOutputSearchDataExtractor();

		$this->assertEquals(
			[ 'https://foo', 'https://bar' ],
			$searchDataExtractor->getExternalLinks( $parserOutput )
		);
	}

	public function testGetOutgoingLinks() {
		$parserOutput = new ParserOutput();

		$parserOutput->addLink( Title::makeTitle( NS_MAIN, 'Foo_bar' ), 1 );
		$parserOutput->addLink( Title::makeTitle( NS_HELP, 'Contents' ), 2 );

		$searchDataExtractor = new ParserOutputSearchDataExtractor();

		// this indexes links with db key
		$this->assertEquals(
			[ 'Foo_bar', 'Help:Contents' ],
			$searchDataExtractor->getOutgoingLinks( $parserOutput )
		);
	}

	public function testGetTemplates() {
		$title = Title::makeTitle( NS_TEMPLATE, 'Cite_news' );

		$parserOutput = new ParserOutput();
		$parserOutput->addTemplate( $title, 10, 100 );

		$searchDataExtractor = new ParserOutputSearchDataExtractor();

		$this->assertEquals(
			[ 'Template:Cite news' ],
			$searchDataExtractor->getTemplates( $parserOutput )
		);
	}

}
