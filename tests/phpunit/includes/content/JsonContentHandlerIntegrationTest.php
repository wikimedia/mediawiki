<?php

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Json\FormatJson;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Title\Title;

class JsonContentHandlerIntegrationTest extends MediaWikiLangTestCase {

	public static function provideDataAndParserText() {
		return [
			[
				[],
				'<table class="mw-json"><tbody><tr><td>' .
				'<table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty array</td></tr>'
				. '</tbody></table></td></tr></tbody></table>'
			],
			[
				(object)[],
				'<table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty object</td></tr>' .
				'</tbody></table>'
			],
			[
				(object)[ 'foo' ],
				'<table class="mw-json"><tbody><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr></tbody></table>'
			],
			[
				(object)[ 'foo', 'bar' ],
				'<table class="mw-json"><tbody><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr><tr><th><span>1</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ 'baz' => 'foo', 'bar' ],
				'<table class="mw-json"><tbody><tr><th><span>baz</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ 'baz' => 1000, 'bar' ],
				'<table class="mw-json"><tbody><tr><th><span>baz</span></th>' .
				'<td class="mw-json-value">1000</td></tr><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ '<script>alert("evil!")</script>' ],
				'<table class="mw-json"><tbody><tr><th><span>0</span></th><td class="mw-json-value">"' .
				'&lt;script>alert("evil!")&lt;/script>"' .
				'</td></tr></tbody></table>',
			],
			[
				'{ broken JSON ]',
				'Invalid JSON: $1',
			],
		];
	}

	/**
	 * @dataProvider provideDataAndParserText
	 * @covers \JsonContentHandler::fillParserOutput
	 */
	public function testFillParserOutput( $data, $expected ) {
		if ( !is_string( $data ) ) {
			$data = FormatJson::encode( $data );
		}

		$title = $this->createMock( Title::class );
		$title->method( 'getPageLanguage' )
			->willReturn( $this->getServiceContainer()->getContentLanguage() );

		$content = new JsonContent( $data );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$parserOutput = $contentRenderer->getParserOutput(
			$content,
			$title,
			null,
			null,
			true
		);
		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		$this->assertEquals( $expected, $parserOutput->getText() );
	}

	/**
	 * @covers \JsonContentHandler::validateSave
	 */
	public function testValidateSave() {
		$handler = new JsonContentHandler();
		$validationParams = new ValidationParams(
			PageIdentityValue::localIdentity( 123, NS_MEDIAWIKI, 'Config.json' ),
			0
		);

		$validJson = new JsonContent( FormatJson::encode( [ 'test' => 'value' ] ) );
		$invalidJson = new JsonContent( '{"key":' );

		$this->assertStatusGood( $handler->validateSave( $validJson, $validationParams ) );
		$this->assertStatusError( 'invalid-json-data',
			$handler->validateSave( $invalidJson, $validationParams ) );

		$this->setTemporaryHook(
			'JsonValidateSave',
			static function ( JsonContent $content, PageIdentity $pageIdentity, StatusValue $status )
			{
				if ( $pageIdentity->getDBkey() === 'Config.json' &&
					!isset( $content->getData()->getValue()->foo ) ) {
					$status->fatal( 'missing-key-foo' );
				}
			}
		);

		$this->assertStatusError( 'invalid-json-data',
			$handler->validateSave( $invalidJson, $validationParams ) );
		$this->assertStatusError( 'missing-key-foo',
			$handler->validateSave( $validJson, $validationParams ) );
	}
}
