<?php

/**
 * @group Xml
 */
class XmlJsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers XmlJsCode::__construct
	 * @dataProvider provideConstruction
	 */
	public function testConstruction( $value ) {
		$obj = new XmlJsCode( $value );
		$this->assertEquals( $value, $obj->value );
	}

	public static function provideConstruction() {
		return [
			[ null ],
			[ '' ],
		];
	}

}
