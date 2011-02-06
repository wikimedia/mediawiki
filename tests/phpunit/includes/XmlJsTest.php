<?php
class XmlJs extends PHPUnit_Framework_TestCase {
	public function testConstruction() {
		$obj = new XmlJsCode( null );
		$this->assertNull( $obj->value );
		$obj = new XmlJsCode( '' );
		$this->assertSame( $obj->value, '' );
	}
}
