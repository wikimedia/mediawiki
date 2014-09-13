<?php

class GlobalVarConfigTest extends MediaWikiTestCase {

	public function provideGet() {
		$set = array(
			'wgSomething' => 'default1',
			'wgFoo' => 'default2',
			'efVariable' => 'default3',
			'BAR' => 'default4',
		);

		foreach ( $set as $var => $value ) {
			$GLOBALS[$var] = $value;
		}

		return array(
			array( 'Something', 'wg', 'default1' ),
			array( 'Foo', 'wg', 'default2' ),
			array( 'Variable', 'ef', 'default3' ),
			array( 'BAR', '', 'default4' ),
		);
	}

	/**
	 * @param string $name
	 * @param string $prefix
	 * @param string $expected
	 * @dataProvider provideGet
	 * @covers GlobalVarConfig::get
	 */
	public function testGet( $name, $prefix, $expected ) {
		$config = new GlobalVarConfig( $prefix );
		$this->assertEquals( $config->get( $name ), $expected );
	}
}
