<?php
/* 
 * Test cases for our Services_Json library. Requires PHP json support as well,
 * so we can compare output
 */
class ServicesJsonTest extends MediaWikiTestCase {
	/**
	 * Test to make sure core json_encode() and our Services_Json()->encode()
	 * produce the same output
	 *
	 * @dataProvider provideValuesToEncode
	 */
	public function testJsonEncode( $input, $desc ) {
		if ( !function_exists( 'json_encode' ) ) {
			$this->markTestIncomplete( 'No PHP json support, unable to test' );
			return;
		} elseif( strtolower( json_encode( "\xf0\xa0\x80\x80" ) ) != '"\ud840\udc00"' ) {
			$this->markTestIncomplete( 'Have buggy PHP json support, unable to test' );
			return;
		} else {
			$jsonObj = new Services_JSON();
			$this->assertEquals(
				$jsonObj->encode( $input ),
				json_encode( $input ),
				$desc
			);
		}
	}

	/**
	 * Test to make sure core json_decode() and our Services_Json()->decode()
	 * produce the same output
	 *
	 * @dataProvider provideValuesToDecode
	 */
	public function testJsonDecode( $input, $desc ) {
		if ( !function_exists( 'json_decode' ) ) {
			$this->markTestIncomplete( 'No PHP json support, unable to test' );
			return;
		} else {
			$jsonObj = new Services_JSON();
			$this->assertEquals(
				$jsonObj->decode( $input ),
				json_decode( $input ),
				$desc
			);
		}
	}

	function provideValuesToEncode() {
		$obj = new stdClass();
		$obj->property = 'value';
		$obj->property2 = null;
		$obj->property3 = 1.234;
		return array(
			array( 1, 'basic integer' ),
			array( true, 'basic bool true' ),
			array( false, 'basic bool false' ),
			array( 'some string', 'basic string test' ),
			array( array( 'some', 'string', 'values' ), 'basic array of strings' ),
			array( array( 'key1' => 'val1', 'key2' => 'val2' ), 'array with string keys' ),
			array( $obj, 'basic object test' ),
		);
	}

	function provideValuesToDecode() {
		return array(
			array( '{"key":"value"}', 'Basic key => value test' ),
		);
	}
}
