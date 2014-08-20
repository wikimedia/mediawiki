<?php

class GlobalVarConfigTest extends MediaWikiTestCase {

	public function testNewInstance() {
		$config = GlobalVarConfig::newInstance();
		$this->assertInstanceOf( 'GlobalVarConfig', $config );
	}

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
	 * @covers GlobalVarConfig::getWithPrefix
	 */
	public function testGet( $name, $prefix, $expected ) {
		$config = new GlobalVarConfig( $prefix );
		$this->assertEquals( $config->get( $name ), $expected );
	}

	public static function provideSet() {
		return array(
			array( 'Foo', 'wg', 'wgFoo' ),
			array( 'SomethingRandom', 'wg', 'wgSomethingRandom' ),
			array( 'FromAnExtension', 'eg', 'egFromAnExtension' ),
			array( 'NoPrefixHere', '', 'NoPrefixHere' ),
		);
	}

	/**
	 * @dataProvider provideSet
	 * @covers GlobalVarConfig::set
	 * @covers GlobalVarConfig::setWithPrefix
	 */
	public function testSet( $name, $prefix, $var ) {
		$config = new GlobalVarConfig( $prefix );
		$random = wfRandomString();
		$config->set( $name, $random );
		$this->assertArrayHasKey( $var, $GLOBALS );
		$this->assertEquals( $random, $GLOBALS[$var] );
	}
}
