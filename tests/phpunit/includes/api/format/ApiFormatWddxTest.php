<?php

/**
 * @group API
 * @covers ApiFormatWddx
 */
class ApiFormatWddxTest extends ApiFormatTestBase {

	protected $printerName = 'wddx';

	public static function provideGeneralEncoding() {
		if ( ApiFormatWddx::useSlowPrinter() ) {
			return array(
				array( array(), 'Fast Wddx printer is unavailable', array( 'SKIP' => true ) )
			);
		}
		return self::provideEncoding();
	}

	public static function provideEncoding() {
		$p = '<wddxPacket version=\'1.0\'><header/><data><struct><var name=\'warnings\'><struct><var name=\'wddx\'><struct><var name=\'*\'><string>format=wddx has been deprecated. Please use format=json instead.</string></var></struct></var></struct></var>';
		$s = '</struct></data></wddxPacket>';

		return array(
			// Basic types
			array( array( null ), "{$p}<var name='0'><null/></var>{$s}" ),
			array( array( true ), "{$p}<var name='0'><string></string></var>{$s}" ),
			array( array( false ), "{$p}{$s}" ),
			array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"{$p}<var name='0'><boolean value='true'/></var>{$s}" ),
			array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"{$p}<var name='0'><boolean value='false'/></var>{$s}" ),
			array( array( 42 ), "{$p}<var name='0'><number>42</number></var>{$s}" ),
			array( array( 42.5 ), "{$p}<var name='0'><number>42.5</number></var>{$s}" ),
			array( array( 1e42 ), "{$p}<var name='0'><number>1.0E+42</number></var>{$s}" ),
			array( array( 'foo' ), "{$p}<var name='0'><string>foo</string></var>{$s}" ),
			array( array( 'fóo' ), "{$p}<var name='0'><string>fóo</string></var>{$s}" ),

			// Arrays and objects
			array( array( array() ), "{$p}<var name='0'><array length='0'></array></var>{$s}" ),
			array( array( array( 1 ) ), "{$p}<var name='0'><array length='1'><number>1</number></array></var>{$s}" ),
			array( array( array( 'x' => 1 ) ), "{$p}<var name='0'><struct><var name='x'><number>1</number></var></struct></var>{$s}" ),
			array( array( array( 2 => 1 ) ), "{$p}<var name='0'><struct><var name='2'><number>1</number></var></struct></var>{$s}" ),
			array( array( (object)array() ), "{$p}<var name='0'><struct></struct></var>{$s}" ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), "{$p}<var name='0'><struct><var name='0'><number>1</number></var></struct></var>{$s}" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), "{$p}<var name='0'><array length='1'><number>1</number></array></var>{$s}" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), "{$p}<var name='0'><struct><var name='x'><number>1</number></var></struct></var>{$s}" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
				"{$p}<var name='0'><array length='1'><struct><var name='key'><string>x</string></var><var name='*'><number>1</number></var></struct></array></var>{$s}" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), "{$p}<var name='0'><struct><var name='x'><number>1</number></var></struct></var>{$s}" ),
			array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), "{$p}<var name='0'><array length='2'><string>a</string><string>b</string></array></var>{$s}" ),

			// Content
			array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
				"{$p}<var name='*'><string>foo</string></var>{$s}" ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
				"{$p}<var name='foo'><struct><var name='*'><string>foo</string></var></struct></var>{$s}" ),
		);
	}

	/**
	 * @dataProvider provideEncoding
	 */
	public function testSlowEncoding( array $data, $expect, array $params = array() ) {
		// Adjust expectation for differences between fast and slow printers.
		$expect = str_replace( '\'', '"', $expect );
		$expect = str_replace( '/>', ' />', $expect );
		$expect = '<?xml version="1.0"?>' . $expect;

		$this->assertSame( $expect, $this->encodeData( $params, $data, 'ApiFormatWddxTest_SlowWddx' ) );
	}
}

class ApiFormatWddxTest_SlowWddx extends ApiFormatWddx {
	public static function useSlowPrinter() {
		return true;
	}
}
