<?php

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Json\FormatJson;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Content\JsonContentHandler
 */
class JsonContentHandlerIntegrationTest extends MediaWikiLangTestCase {

	public static function provideDataAndParserText() {
		return [
			[
				[],
				'<div class="noresize"><table class="mw-json"><tbody><tr><td>' .
				'<table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty array</td></tr>'
				. '</tbody></table></td></tr></tbody></table></div>'
			],
			[
				(object)[],
				'<div class="noresize"><table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty object</td></tr>' .
				'</tbody></table></div>'
			],
			[
				(object)[ 'foo' ],
				'<div class="noresize"><table class="mw-json"><tbody><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr></tbody></table></div>'
			],
			[
				(object)[ 'foo', 'bar' ],
				'<div class="noresize"><table class="mw-json"><tbody><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr><tr><th><span>1</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table></div>'
			],
			[
				(object)[ 'baz' => 'foo', 'bar' ],
				'<div class="noresize"><table class="mw-json"><tbody><tr><th><span>baz</span></th>' .
				'<td class="mw-json-value">"foo"</td></tr><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table></div>'
			],
			[
				(object)[ 'baz' => 1000, 'bar' ],
				'<div class="noresize"><table class="mw-json"><tbody><tr><th><span>baz</span></th>' .
				'<td class="mw-json-value">1000</td></tr><tr><th><span>0</span></th>' .
				'<td class="mw-json-value">"bar"</td></tr></tbody></table></div>'
			],
			[
				(object)[ '<script>alert("evil!")</script>' ],
				'<div class="noresize"><table class="mw-json"><tbody><tr><th><span>0</span></th><td class="mw-json-value">"' .
				'&lt;script>alert("evil!")&lt;/script>"' .
				'</td></tr></tbody></table></div>',
			],
			[
				'{ broken JSON ]',
				'Invalid JSON: $1',
			],
		];
	}

	/**
	 * @dataProvider provideDataAndParserText
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
		$opts = ParserOptions::newFromAnon();
		$parserOutput = $contentRenderer->getParserOutput(
			$content,
			$title,
			null,
			$opts,
			true
		);
		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		$this->assertEquals( $expected, $parserOutput->runOutputPipeline( $opts, [] )->getContentHolderText() );
	}

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
