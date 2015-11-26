<?php

/**
 * @author Adam Shorland
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgWellFormedXml', true );
	}

	public static function provideValidConstruction() {
		return array(
			array( 'foo', false, null ),
			array( '[]', true, array() ),
			array( '{}', true, (object)array() ),
			array( '""', true, '' ),
			array( '"0"', true, '0' ),
			array( '"bar"', true, 'bar' ),
			array( '0', true, '0' ),
			array( '{ "0": "bar" }', true, (object)array( 'bar' ) ),
		);
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
		return array(
			array(
				// Round-trip empty array
				'[]',
				'[]',
			),
			array(
				// Round-trip empty object
				'{}',
				'{}',
			),
			array(
				// Round-trip empty array/object (nested)
				'{ "foo": {}, "bar": [] }',
				"{\n    \"foo\": {},\n    \"bar\": []\n}",
			),
			array(
				'{ "foo": "bar" }',
				"{\n    \"foo\": \"bar\"\n}",
			),
			array(
				'{ "foo": 1000 }',
				"{\n    \"foo\": 1000\n}",
			),
			array(
				'{ "foo": 1000, "0": "bar" }',
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
		$newObj = $obj->preSaveTransform(
			$this->getMockTitle(),
			$this->getMockUser(),
			$this->getMockParserOptions()
		);
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
				'<table class="mw-json"><tbody><tr><td>' .
				'<table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty array</td></tr>'
				. '</tbody></table></td></tr></tbody></table>'
			),
			array(
				(object)array(),
				'<table class="mw-json"><tbody><tr><td class="mw-json-empty">Empty object</td></tr>' .
				'</tbody></table>'
			),
			array(
				(object)array( 'foo' ),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">"foo"</td></tr>' .
				'</tbody></table>'
			),
			array(
				(object)array( 'foo', 'bar' ),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">"foo"</td></tr>' .
				'<tr><th>1</th><td class="value">"bar"</td></tr></tbody></table>'
			),
			array(
				(object)array( 'baz' => 'foo', 'bar' ),
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="value">"foo"</td></tr>' .
				'<tr><th>0</th><td class="value">"bar"</td></tr></tbody></table>'
			),
			array(
				(object)array( 'baz' => 1000, 'bar' ),
				'<table class="mw-json"><tbody><tr><th>baz</th><td class="value">1000</td></tr>' .
				'<tr><th>0</th><td class="value">"bar"</td></tr></tbody></table>'
			),
			array(
				(object)array( '<script>alert("evil!")</script>' ),
				'<table class="mw-json"><tbody><tr><th>0</th><td class="value">"' .
				'&lt;script>alert("evil!")&lt;/script>"' .
				'</td></tr></tbody></table>',
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
