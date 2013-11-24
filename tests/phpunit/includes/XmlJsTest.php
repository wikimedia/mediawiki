<?php

/**
 * @group Xml
 */
class XmlJs extends MediaWikiTestCase {

	/**
	 * @covers XmlJsCode::__construct
	 * @dataProvider provideConstruction
	 */
	public function testConstruction( $value ) {
		$obj = new XmlJsCode( $value );
		$this->assertEquals( $value, $obj->value );
	}

	public function provideConstruction() {
		return array(
			array( null ),
			array( '' ),
		);
	}

}
