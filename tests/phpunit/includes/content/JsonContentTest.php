<?php

/**
 * @author Addshore
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	public static function provideValidConstruction() {
		return [
			[ 'foo', false, null ],
			[ '[]', true, [] ],
			[ '{}', true, (object)[] ],
			[ '""', true, '' ],
			[ '"0"', true, '0' ],
			[ '"bar"', true, 'bar' ],
			[ '0', true, '0' ],
			[ '{ "0": "bar" }', true, (object)[ 'bar' ] ],
		];
	}

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testIsValid( $text, $isValid, $expected ) {
		$obj = new JsonContent( $text, CONTENT_MODEL_JSON );
		$this->assertEquals( $isValid, $obj->isValid() );
		$this->assertEquals( $expected, $obj->getData()->getValue() );
	}

	public static function provideDataToEncode() {
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
	public function testBeautifyJson( $input, $beautified ) {
		$obj = new JsonContent( $input );
		$this->assertEquals( $beautified, $obj->beautifyJSON() );
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testPreSaveTransform( $input, $transformed ) {
		$obj = new JsonContent( $input );
		$newObj = $obj->preSaveTransform(
			$this->getMockTitle(),
			$this->getMockUser(),
			$this->getMockParserOptions()
		);
		$this->assertTrue( $newObj->equals( new JsonContent( $transformed ) ) );
	}

	private function getMockTitle() {
		return $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
	}

	private function getMockUser() {
		return $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
	}

	private function getMockParserOptions() {
		return $this->getMockBuilder( ParserOptions::class )
			->disableOriginalConstructor()
			->getMock();
	}

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
				'<table class="mw-json"><tbody><tr><th>0</th><td class="mw-json-value">"foo"</td></tr>' .
				'</tbody></table>'
			],
			[
				(object)[ 'foo', 'bar' ],
				'<table class="mw-json"><tbody><tr><th>0</th><td class="mw-json-value">"foo"</td></tr>' .
				'<tr><th>1</th><td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ 'baz' => 'foo', 'bar' ],
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="mw-json-value">"foo"</td></tr>' .
				'<tr><th>0</th><td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ 'baz' => 1000, 'bar' ],
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="mw-json-value">1000</td></tr>' .
				'<tr><th>0</th><td class="mw-json-value">"bar"</td></tr></tbody></table>'
			],
			[
				(object)[ '<script>alert("evil!")</script>' ],
				'<table class="mw-json"><tbody><tr><th>0</th><td class="mw-json-value">"' .
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
		$parserOutput = $obj->getParserOutput( $this->getMockTitle(), null, null, true );
		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		$this->assertEquals( $expected, $parserOutput->getText() );
	}
}
