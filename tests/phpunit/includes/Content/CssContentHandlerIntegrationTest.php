<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Title\TitleValue;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\CssContentHandler
 */
class CssContentHandlerIntegrationTest extends TextContentHandlerIntegrationTest {
	public static function provideGetParserOutput() {
		yield 'Basic render' => [
			'title' => 'MediaWiki:Test.css',
			'model' => null,
			'text' => "hello <world>x\n",
			'expectedHtml' => "<pre class=\"mw-code mw-css\" dir=\"ltr\">\nhello &lt;world>x\n\n</pre>",
			'expectedFields' => [
				'LinkList!LOCAL' => [
					'_args_' => [ ParserOutputLinkTypes::LOCAL ],
				],
				'Sections' => [
				],
			],
		];
		yield 'Links' => [
			'title' => 'MediaWiki:Test.css',
			'model' => null,
			'text' => "/* hello [[world]] */\n",
			'expectedHtml' => "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n/* hello [[world]] */\n\n</pre>",
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
			'title' => 'MediaWiki:Test.css',
			'model' => null,
			'text' => "==One==\n<h2>Two</h2>",
			'expectedHtml' => "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n==One==\n&lt;h2>Two&lt;/h2>\n</pre>",
			'expectedFields' => [
				'LinkList!LOCAL' => [
					'_args_' => [ ParserOutputLinkTypes::LOCAL ],
				],
				# T307691
				'Sections' => [
				],
			]
		];
	}
}
