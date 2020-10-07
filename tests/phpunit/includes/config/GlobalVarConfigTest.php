<?php

class GlobalVarConfigTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers GlobalVarConfig::newInstance
	 */
	public function testNewInstance() {
		$config = GlobalVarConfig::newInstance();
		$this->assertInstanceOf( GlobalVarConfig::class, $config );
		$this->setMwGlobals( 'wgBaz', 'somevalue' );
		// Check prefix is set to 'wg'
		$this->assertEquals( 'somevalue', $config->get( 'Baz' ) );
	}

	/**
	 * @covers GlobalVarConfig::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $prefix ) {
		$var = $prefix . 'GlobalVarConfigTest';
		$this->setMwGlobals( $var, 'testvalue' );
		$config = new GlobalVarConfig( $prefix );
		$this->assertInstanceOf( GlobalVarConfig::class, $config );
		$this->assertEquals( 'testvalue', $config->get( 'GlobalVarConfigTest' ) );
	}

	public static function provideConstructor() {
		return [
			[ 'wg' ],
			[ 'ef' ],
			[ 'smw' ],
			[ 'blahblahblahblah' ],
			[ '' ],
		];
	}

	/**
	 * @covers GlobalVarConfig::has
	 * @covers GlobalVarConfig::hasWithPrefix
	 */
	public function testHas() {
		$this->setMwGlobals( 'wgGlobalVarConfigTestHas', 'testvalue' );
		$config = new GlobalVarConfig();
		$this->assertTrue( $config->has( 'GlobalVarConfigTestHas' ) );
		$this->assertFalse( $config->has( 'GlobalVarConfigTestNotHas' ) );
	}

	public static function provideGet() {
		$set = [
			'wgSomething' => 'default1',
			'wgFoo' => 'default2',
			'efVariable' => 'default3',
			'BAR' => 'default4',
		];

		foreach ( $set as $var => $value ) {
			$GLOBALS[$var] = $value;
		}

		return [
			[ 'Something', 'wg', 'default1' ],
			[ 'Foo', 'wg', 'default2' ],
			[ 'Variable', 'ef', 'default3' ],
			[ 'BAR', '', 'default4' ],
			[ 'ThisGlobalWasNotSetAbove', 'wg', false ]
		];
	}

	/**
	 * @dataProvider provideGet
	 * @covers GlobalVarConfig::get
	 * @covers GlobalVarConfig::getWithPrefix
	 * @param string $name
	 * @param string $prefix
	 * @param string $expected
	 */
	public function testGet( $name, $prefix, $expected ) {
		$config = new GlobalVarConfig( $prefix );
		if ( $expected === false ) {
			$this->expectException( ConfigException::class );
			$this->expectExceptionMessage( 'GlobalVarConfig::get: undefined option:' );
		}
		$this->assertEquals( $config->get( $name ), $expected );
	}
}
