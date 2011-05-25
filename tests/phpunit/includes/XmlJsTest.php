<?php
class XmlJs extends MediaWikiTestCase {
	public function testConstruction() {
		$obj = new XmlJsCode( null );
		$this->assertNull( $obj->value );
		$obj = new XmlJsCode( '' );
		$this->assertSame( $obj->value, '' );
	}
}
