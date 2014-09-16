<?php

class HashConfigTest extends MediaWikiTestCase {

	/**
	 * @covers HashConfig::newInstance
	 */
	public function testNewInstance() {
		$conf = HashConfig::newInstance();
		$this->assertInstanceOf( 'HashConfig', $conf );
	}

	/**
	 * @covers HashConfig::__construct
	 */
	public function testConstructor() {
		$conf = new HashConfig();
		$this->assertInstanceOf( 'HashConfig', $conf );

		// Test passing arguments to the constructor
		$conf2 = new HashConfig( array(
			'one' => '1',
		) );
		$this->assertEquals( '1', $conf2->get( 'one' ) );
	}

	/**
	 * @covers HashConfig::get
	 */
	public function testGet() {
		$conf = new HashConfig( array(
			'one' => '1',
		));
		$this->assertEquals( '1', $conf->get( 'one' ) );
		$this->setExpectedException( 'ConfigException', 'HashConfig::get: undefined option' );
		$conf->get( 'two' );
	}

	/**
	 * @covers HashConfig::has
	 */
	public function testHas() {
		$conf = new HashConfig( array(
			'one' => '1',
		) );
		$this->assertTrue( $conf->has( 'one' ) );
		$this->assertFalse( $conf->has( 'two' ) );
	}

	/**
	 * @covers HashConfig::set
	 */
	public function testSet() {
		$conf = new HashConfig( array(
			'one' => '1',
		) );
		$conf->set( 'two', '2' );
		$this->assertEquals( '2', $conf->get( 'two' ) );
		// Check that set overwrites
		$conf->set( 'one', '3' );
		$this->assertEquals( '3', $conf->get( 'one' ) );
	}
}