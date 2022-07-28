<?php

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class WikitextContentHandlerIntegrationTest extends TextContentHandlerIntegrationTest {
	public static function provideGetParserOutput() {
		yield 'Basic render' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "hello ''world''\n",
			'expectedHtml' => "<div class=\"mw-parser-output\"><p>hello <i>world</i>\n</p></div>",
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
				],
			],
		];
		yield 'Links' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "[[title that does not really exist]]",
			'expectedHtml' => null,
			'expectedFields' => [
				'Links' => [
					[ 'Title_that_does_not_really_exist' => 0, ],
				],
				'Sections' => [
				],
			],
		];
		yield 'TOC' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "==One==\n==Two==\n==Three==\n==Four==\n<h2>Five</h2>\n",
			'expectedHtml' => null,
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'One',
						'number' => '1',
						'index' => '1',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 0,
						'anchor' => 'One',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Two',
						'number' => '2',
						'index' => '2',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 8,
						'anchor' => 'Two',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Three',
						'number' => '3',
						'index' => '3',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 16,
						'anchor' => 'Three',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Four',
						'number' => '4',
						'index' => '4',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 26,
						'anchor' => 'Four',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Five',
						'number' => '5',
						'index' => '',
						'fromtitle' => false,
						'byteoffset' => null,
						'anchor' => 'Five',
					],
				],
			],
		];
	}
}
