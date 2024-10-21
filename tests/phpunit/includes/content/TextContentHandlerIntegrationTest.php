<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use Wikimedia\Parsoid\ParserTests\TestUtils;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\TextContentHandler
 */
class TextContentHandlerIntegrationTest extends MediaWikiLangTestCase {

	public static function provideGetParserOutput() {
		yield 'Basic render' => [
			'title' => 'TextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_TEXT,
			'text' => "hello ''world'' & [[stuff]]\n",
			'expectedHtml' => "<pre>hello ''world'' &amp; [[stuff]]\n</pre>",
			'expectedFields' =>	[ 'Links' => [] ]
		];
		yield 'Multi line render' => [
			'title' => 'TextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_TEXT,
			'text' => "Test 1\nTest 2\n\nTest 3\n",
			'expectedHtml' => "<pre>Test 1\nTest 2\n\nTest 3\n</pre>",
			'expectedFields' =>	[ 'Links' => [] ]
		];
	}

	/**
	 * @dataProvider provideGetParserOutput
	 */
	public function testGetParserOutput( $title, $model, $text, $expectedHtml,
		$expectedFields = null, $parserOptions = null
	) {
		$title = Title::newFromText( $title );
		$content = ContentHandler::makeContent( $text, $title, $model );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		if ( $parserOptions === null ) {
			$parserOptions = ParserOptions::newFromAnon();
		}
		$po = $contentRenderer->getParserOutput( $content, $title, null, $parserOptions );

		// TODO T371004
		$processedPo = $po->runOutputPipeline( $parserOptions, [] );
		$html = $processedPo->getContentHolderText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments
		$html = TestUtils::stripParsoidIds( $html );

		if ( $expectedHtml !== null ) {
			$this->assertEquals( TestUtils::stripParsoidIds( $expectedHtml ), trim( $html ) );
		}

		if ( $expectedFields ) {
			foreach ( $expectedFields as $field => $exp ) {
				$getter = 'get' . ucfirst( $field );
				$v = $processedPo->$getter();

				if ( is_array( $exp ) ) {
					$this->assertArrayEquals( $exp, $v );
				} else {
					$this->assertEquals( $exp, $v );
				}
			}
		}
	}
}
