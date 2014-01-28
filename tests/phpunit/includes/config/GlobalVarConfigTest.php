<?php

class GlobalVarConfigTest extends MediaWikiTestCase {

	public function provideGet() {
		$this->setMwGlobals( array(
			'wgSitename' => 'default1',
			'wgFoo' => 'default2',
			'efVariable' => 'default3',
			'BAR' => 'default4',
		) );

		return array(
			array( 'Sitename', 'wg', 'default1' ),
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
