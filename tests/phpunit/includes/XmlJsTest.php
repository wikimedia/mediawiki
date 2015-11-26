<?php

/**
 * @group Xml
 */
class XmlJs extends PHPUnit_Framework_TestCase {

	/**
	 * @covers XmlJsCode::__construct
	 * @dataProvider provideConstruction
	 */
	public function testConstruction( $value ) {
		$obj = new XmlJsCode( $value );
		$this->assertEquals( $value, $obj->value );
	}

	public static function provideConstruction() {
		return array(
			array( null ),
			array( '' ),
		);
	}

}
