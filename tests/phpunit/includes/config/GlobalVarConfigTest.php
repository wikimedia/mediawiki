<?php

class GlobalVarConfigTest extends MediaWikiTestCase {

	/**
	 * @covers GlobalVarConfig::newInstance
	 */
	public function testNewInstance() {
		$config = GlobalVarConfig::newInstance();
		$this->assertInstanceOf( 'GlobalVarConfig', $config );
		$this->maybeStashGlobal( 'wgBaz' );
		$GLOBALS['wgBaz'] = 'somevalue';
		// Check prefix is set to 'wg'
		$this->assertEquals( 'somevalue', $config->get( 'Baz' ) );
	}

	/**
	 * @covers GlobalVarConfig::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $prefix ) {
		$var = $prefix . 'GlobalVarConfigTest';
		$rand = wfRandomString();
		$this->maybeStashGlobal( $var );
		$GLOBALS[$var] = $rand;
		$config = new GlobalVarConfig( $prefix );
		$this->assertInstanceOf( 'GlobalVarConfig', $config );
		$this->assertEquals( $rand, $config->get( 'GlobalVarConfigTest' ) );
	}

	public static function provideConstructor() {
		return array(
			array( 'wg' ),
			array( 'ef' ),
			array( 'smw' ),
			array( 'blahblahblahblah' ),
			array( '' ),
		);
	}

	/**
	 * @covers GlobalVarConfig::has
	 */
	public function testHas() {
		$this->maybeStashGlobal( 'wgGlobalVarConfigTestHas' );
		$GLOBALS['wgGlobalVarConfigTestHas'] = wfRandomString();
		$this->maybeStashGlobal( 'wgGlobalVarConfigTestNotHas' );
		$config = new GlobalVarConfig();
		$this->assertTrue( $config->has( 'GlobalVarConfigTestHas' ) );
		$this->assertFalse( $config->has( 'GlobalVarConfigTestNotHas' ) );
	}

	public static function provideGet() {
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
			array( 'ThisGlobalWasNotSetAbove', 'wg', false )
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
		if ( $expected === false ) {
			$this->setExpectedException( 'ConfigException', 'GlobalVarConfig::get: undefined option:' );
		}
		$this->assertEquals( $config->get( $name ), $expected );
	}

	private function maybeStashGlobal( $var ) {
		if ( array_key_exists( $var, $GLOBALS ) ) {
			// Will be reset after this test is over
			$this->stashMwGlobals( $var );
		}
	}
}
