<?php

class MultiConfigTest extends MediaWikiTestCase {

	/**
	 * Tests that settings are fetched in the right order
	 *
	 * @covers MultiConfig::get
	 */
	public function testGet() {
		$multi = new MultiConfig( array(
			new HashConfig( array( 'foo' => 'bar' ) ),
			new HashConfig( array( 'foo' => 'baz', 'bar' => 'foo' ) ),
			new HashConfig( array( 'bar' => 'baz' ) ),
		) );

		$this->assertEquals( 'bar', $multi->get( 'foo' ) );
		$this->assertEquals( 'foo', $multi->get( 'bar' ) );
		$this->setExpectedException( 'ConfigException', 'MultiConfig::get: undefined option:' );
		$multi->get( 'notset' );
	}

	/**
	 * @covers MultiConfig::has
	 */
	public function testHas() {
		$conf = new MultiConfig( array(
			new HashConfig( array( 'foo' => 'foo' ) ),
			new HashConfig( array( 'something' => 'bleh' ) ),
			new HashConfig( array( 'meh' => 'eh' ) ),
		) );

		$this->assertTrue( $conf->has( 'foo' ) );
		$this->assertTrue( $conf->has( 'something' ) );
		$this->assertTrue( $conf->has( 'meh' ) );
		$this->assertFalse( $conf->has( 'what' ) );
	}
}
