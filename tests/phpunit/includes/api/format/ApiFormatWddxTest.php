<?php

/**
 * @group API
 * @covers ApiFormatWddx
 */
class ApiFormatWddxTest extends ApiFormatTestBase {

	protected $printerName = 'wddx';

	public function provideGeneralEncoding() {
		if ( ApiFormatWddx::useSlowPrinter() ) {
			$this->markTestSkipped( 'Fast Wddx printer is unavailable' );
		}
		return $this->provideEncoding();
	}

	public function provideEncoding() {
		$p = '<wddxPacket version=\'1.0\'><header/><data><struct><var name=\'warnings\'><struct><var name=\'wddx\'><struct><var name=\'*\'><string>format=wddx has been deprecated. Please use format=json instead.</string></var></struct></var></struct></var>';
		$s = '</struct></data></wddxPacket>';

		return array(
			// Basic types
			array( array( null ), "{$p}<var name='0'><null/></var>{$s}" ),
			array( array( true ), "{$p}<var name='0'><boolean value='true'/></var>{$s}" ),
			array( array( false ), "{$p}<var name='0'><boolean value='false'/></var>{$s}" ),
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

			// Content
			array( array( '*' => 'foo' ), "{$p}<var name='*'><string>foo</string></var>{$s}" ),
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
