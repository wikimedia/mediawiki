<?php

/**
 * See also unit tests at \MediaWiki\Tests\Unit\JsonContentTest
 *
 * @author Addshore
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	public function provideDataToEncode() {
		return [
			[
				// Round-trip empty array
				'[]',
				'[]',
			],
			[
				// Round-trip empty object
				'{}',
				'{}',
			],
			[
				// Round-trip empty array/object (nested)
				'{ "foo": {}, "bar": [] }',
				"{\n    \"foo\": {},\n    \"bar\": []\n}",
			],
			[
				'{ "foo": "bar" }',
				"{\n    \"foo\": \"bar\"\n}",
			],
			[
				'{ "foo": 1000 }',
				"{\n    \"foo\": 1000\n}",
			],
			[
				'{ "foo": 1000, "0": "bar" }',
				"{\n    \"foo\": 1000,\n    \"0\": \"bar\"\n}",
			],
		];
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testPreSaveTransform( $input, $transformed ) {
		$obj = new JsonContent( $input );
		$newObj = $obj->preSaveTransform(
			$this->createMock( Title::class ),
			$this->createMock( User::class ),
			$this->createMock( ParserOptions::class )
		);
		$this->assertTrue( $newObj->equals( new JsonContent( $transformed ) ) );
	}

	public function provideDataAndParserText() {
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
		];
	}

	/**
	 * @dataProvider provideDataAndParserText
	 */
	public function testFillParserOutput( $data, $expected ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$parserOutput = $obj->getParserOutput(
			$this->createMock( Title::class ),
			null,
			null,
			true
		);
		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		$this->assertEquals( $expected, $parserOutput->getText() );
	}
}
