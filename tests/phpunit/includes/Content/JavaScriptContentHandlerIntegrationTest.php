<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Title\TitleValue;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\JavaScriptContentHandler
 */
class JavaScriptContentHandlerIntegrationTest extends TextContentHandlerIntegrationTest {
	public static function provideGetParserOutput() {
		yield 'Basic render' => [
			'title' => 'MediaWiki:Test.js',
			'model' => null,
			'text' => "hello <world>\n",
			'expectedHtml' => "<pre dir=\"ltr\" class=\"mw-code mw-js\">\nhello &lt;world>\n\n</pre>",
			'expectedFields' => [
				'LinkList!LOCAL' => [
					'_args_' => [ ParserOutputLinkTypes::LOCAL ],
				],
				'Sections' => [
				],
			],
		];
		yield 'Links' => [
			'title' => 'MediaWiki:Test.js',
			'model' => null,
			'text' => "hello(); // [[world]]\n",
			'expectedHtml' => "<pre dir=\"ltr\" class=\"mw-code mw-js\">\nhello(); // [[world]]\n\n</pre>",
			'expectedFields' => [
				'LinkList!LOCAL' => [
					'_args_' => [ ParserOutputLinkTypes::LOCAL ],
					[
						'link' => new TitleValue( NS_MAIN, 'World' ),
						'pageid' => 0,
					],
				],
				'Sections' => [
				],
			],
		];
		yield 'TOC' => [
			'title' => 'MediaWiki:Test.js',
			'model' => null,
			'text' => "==One==\n<h2>Two</h2>",
			'expectedHtml' => "<pre dir=\"ltr\" class=\"mw-code mw-js\">\n==One==\n&lt;h2>Two&lt;/h2>\n</pre>",
			'expectedFields' => [
				'LinkList!LOCAL' => [
					'_args_' => [ ParserOutputLinkTypes::LOCAL ],
				],
				# T307691
				'Sections' => [
				],
			],
		];
	}
}
