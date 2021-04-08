<?php

/**
 * @group Xml
 */
class XmlJsTest extends MediaWikiUnitTestCase {

	/**
	 * @covers XmlJsCode::__construct
	 */
	public function testConstruction() {
		$obj = new XmlJsCode( '' );
		$this->assertSame( '', $obj->value );

		$obj = new XmlJsCode( null );
		$this->assertNull( $obj->value );
	}

}
