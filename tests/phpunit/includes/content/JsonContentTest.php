<?php

/**
 * @author Adam Shorland
 * @covers JsonContent
 */
class JsonContentTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideValidConstruction
	 * @param $text
	 * @param $isValid
	 * @param $expected
	 */
	public function testValidConstruct( $text, $isValid, $expected ) {
		$obj = new JsonContent( $text, CONTENT_MODEL_JSON );
		$status = $obj->getJson();
		$this->assertEquals( $isValid, $status->isOK() );
		$this->assertEquals( $expected, $status->getValue() );
	}

	public function provideValidConstruction() {
		return array(
			array( 'foo', false, null ),
			array( FormatJson::encode( array() ), true, array() ),
			array( FormatJson::encode( array( 'foo' ) ), true, array( 'foo' ) ),
		);
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testPreSaveTransform( $data ) {
		$obj = new JsonContent( FormatJson::encode( $data ) );
		$newObj = $obj->preSaveTransform( $this->getMockTitle(), $this->getMockUser(), $this->getMockParserOptions() );
		$this->assertTrue( $newObj->equals( new JsonContent( FormatJson::encode( $data, false, FormatJson::ALL_OK ) ) ) );
	}

	public function provideDataToEncode() {
		return array(
			array( array() ),
			array( array( 'foo' ) ),
			array( array( 'foo', 'bar' ) ),
			array( array( 'baz' => 'foo', 'bar' ) ),
			array( array( 'baz' => 1000, 'bar' ) ),
		);
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

	public function provideDataAndParserText() {
		return array(
			array(
				array(),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
[]
</pre>

EOT
			),
			array(
				array( 'foo' ),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
[
    &quot;foo&quot;
]
</pre>

EOT
			),
			array(
				array( 'foo', 'bar' ),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
[
    &quot;foo&quot;,
    &quot;bar&quot;
]
</pre>

EOT
			),
			array(
				array( 'baz' => 'foo', 'bar' ),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
{
    &quot;baz&quot;: &quot;foo&quot;,
    &quot;0&quot;: &quot;bar&quot;
}
</pre>

EOT
			),
			array(
				array( 'baz' => 1000, 'bar' ),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
{
    &quot;baz&quot;: 1000,
    &quot;0&quot;: &quot;bar&quot;
}
</pre>

EOT
			),
			array(
				array( '<script>alert("evil!")</script>'),
				<<<EOT
<pre class='mw-code mw-json' dir='ltr'>
[
    &quot;\u003Cscript\u003Ealert(\&quot;evil!\&quot;)\u003C/script\u003E&quot;
]
</pre>

EOT
			),
		);
	}
}
