<?php

/**
 * @author Adam Shorland
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	public static function provideValidConstruction() {
		return array(
			array( 'foo', CONTENT_MODEL_JSON, false, null ),
			array( FormatJson::encode( array() ), CONTENT_MODEL_JSON, true, array() ),
			array( FormatJson::encode( array( 'foo' ) ), CONTENT_MODEL_JSON, true, array( 'foo' ) ),
		);
	}

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruct( $text, $modelId, $isValid, $expected ) {
		$obj = new JsonContent( $text, $modelId );
		$this->assertEquals( $isValid, $obj->isValid() );
		$this->assertEquals( $expected, $obj->getJsonData() );
	}

	public static function provideDataToEncode() {
		return array(
			array(
				'[]',
				// Not important but test to track changes to this behaviour.
				'{}',
			),
			array(
				'{}',
				'{}',
			),
			array(
				'["foo"]',
				"[\n    \"foo\"\n]",
			),
			array(
				'["foo", "bar"]',
				"[\n    \"foo\",\n    \"bar\"\n]",
			),
			array(
				'{"foo": "bar"}',
				"{\n    \"foo\": \"bar\"\n}",
			),
			array(
				'{"foo": 1000}',
				"{\n    \"foo\": 1000\n}",
			),
			array(
				'{"foo": 1000, "0": "bar"}',
				"{\n    \"foo\": 1000,\n    \"0\": \"bar\"\n}",
			),
		);
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
		$newObj = $obj->preSaveTransform( $this->getMockTitle(), $this->getMockUser(), $this->getMockParserOptions() );
		$this->assertTrue( $newObj->equals( new JsonContent( $transformed ) ) );
	}

	private function getMockTitle() {
		return $this->getMockBuilder( 'Title' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function getMockUser() {
		return $this->getMockBuilder( 'User' )
			->disableOriginalConstructor()
			->getMock();
	}
	private function getMockParserOptions() {
		return $this->getMockBuilder( 'ParserOptions' )
			->disableOriginalConstructor()
			->getMock();
	}

	public static function provideDataAndParserText() {
		return array(
			array(
				array(),
				'<table class="mw-json"><tbody></tbody></table>'
			),
			array(
				array( 'foo' ),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">&quot;foo&quot;</td></tr></tbody></table>'
			),
			array(
				array( 'foo', 'bar' ),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">&quot;foo&quot;</td></tr>' .
				"\n" .
				'<tr><th>1</th><td class="value">&quot;bar&quot;</td></tr></tbody></table>'
			),
			array(
				array( 'baz' => 'foo', 'bar' ),
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="value">&quot;foo&quot;</td></tr>' .
				"\n" .
				'<tr><th>0</th><td class="value">&quot;bar&quot;</td></tr></tbody></table>'
			),
			array(
				array( 'baz' => 1000, 'bar' ),
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="value">1000</td></tr>' .
				"\n" .
				'<tr><th>0</th><td class="value">&quot;bar&quot;</td></tr></tbody></table>'
			),
			array(
				array( '<script>alert("evil!")</script>'),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">&quot;&lt;script&gt;alert(&quot;evil!&quot;)&lt;/script&gt;&quot;</td></tr></tbody></table>',
			),
		);
	}

	/**
	 * @dataProvider provideDataAndParserText
	 */
	public function testFillParserOutput( $data, $expected ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$parserOutput = $obj->getParserOutput( $this->getMockTitle(), null, null, true );
		$this->assertInstanceOf( 'ParserOutput', $parserOutput );
		$this->assertEquals( $expected, $parserOutput->getText() );
	}
}
