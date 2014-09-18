<?php

/**
 * @author Adam Shorland
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruct( $text, $modelId, $isValid, $expected ) {
		$obj = new JsonContent( $text, $modelId );
		$this->assertEquals( $isValid, $obj->isValid() );
		$this->assertEquals( $expected, $obj->getJsonData() );
	}

	public static function provideValidConstruction() {
		return array(
			array( 'foo', CONTENT_MODEL_JSON, false, null ),
			array( FormatJson::encode( array() ), CONTENT_MODEL_JSON, true, array() ),
			array( FormatJson::encode( array( 'foo' ) ), CONTENT_MODEL_JSON, true, array( 'foo' ) ),
		);
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testBeautifyUsesFormatJson( $data ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$this->assertEquals( FormatJson::encode( $data, true ), $obj->beautifyJSON() );
	}

	public static function provideDataToEncode() {
		return array(
			array( array() ),
			array( array( 'foo' ) ),
			array( array( 'foo', 'bar' ) ),
			array( array( 'baz' => 'foo', 'bar' ) ),
			array( array( 'baz' => 1000, 'bar' ) ),
		);
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testPreSaveTransform( $data ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$newObj = $obj->preSaveTransform( $this->getMockTitle(), $this->getMockUser(), $this->getMockParserOptions() );
		$this->assertTrue( $newObj->equals( new JsonContent( FormatJson::encode( $data, true ) ) ) );
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

	/**
	 * @dataProvider provideDataAndParserText
	 */
	public function testFillParserOutput( $data, $expected ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$parserOutput = $obj->getParserOutput( $this->getMockTitle(), null, null, true );
		$this->assertInstanceOf( 'ParserOutput', $parserOutput );
		$this->assertEquals( $expected, $parserOutput->getText() );
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
}
