<?php

use MediaWiki\Config\ConfigException;
use MediaWiki\Config\GlobalVarConfig;

/**
 * @covers \MediaWiki\Config\GlobalVarConfig
 */
class GlobalVarConfigTest extends MediaWikiUnitTestCase {

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	private function setGlobal( string $name, $value ) {
		$GLOBALS[$name] = $value;
	}

	public function testNewInstance() {
		$config = GlobalVarConfig::newInstance();
		$this->assertInstanceOf( GlobalVarConfig::class, $config );
		$this->setGlobal( 'wgBaz', 'somevalue' );
		// Check prefix is set to 'wg'
		$this->assertEquals( 'somevalue', $config->get( 'Baz' ) );
	}

	/**
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $prefix ) {
		$var = $prefix . 'GlobalVarConfigTest';
		$this->setGlobal( $var, 'testvalue' );
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

	public function testHas() {
		$this->setGlobal( 'wgGlobalVarConfigTestHas', 'testvalue' );
		$config = new GlobalVarConfig();
		$this->assertTrue( $config->has( 'GlobalVarConfigTestHas' ) );
		$this->assertFalse( $config->has( 'GlobalVarConfigTestNotHas' ) );
	}

	public function testGetForNonExistingVariable(): void {
		$config = new GlobalVarConfig( 'wg' );
		$this->expectException( ConfigException::class );
		$this->expectExceptionMessage( 'GlobalVarConfig::get: undefined option:' );
		$config->get( 'NonExistingVar' );
	}

	public static function provideGet() {
		return [
			[ 'Something', 'wg', 'default1' ],
			[ 'Foo', 'wg', 'default2' ],
			[ 'Variable', 'ef', 'default3' ],
			[ 'BAR', '', 'default4' ],
		];
	}

	/** @dataProvider provideGet */
	public function testGet( string $name, string $prefix, string $expected ) {
		$this->setGlobal( $prefix . $name, $expected );
		$config = new GlobalVarConfig( $prefix );
		$this->assertEquals( $expected, $config->get( $name ) );
	}
}
