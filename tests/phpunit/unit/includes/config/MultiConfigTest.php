<?php

class MultiConfigTest extends \MediaWikiUnitTestCase {

	/**
	 * Tests that settings are fetched in the right order
	 *
	 * @covers MultiConfig::__construct
	 * @covers MultiConfig::get
	 */
	public function testGet() {
		$multi = new MultiConfig( [
			new HashConfig( [ 'foo' => 'bar' ] ),
			new HashConfig( [ 'foo' => 'baz', 'bar' => 'foo' ] ),
			new HashConfig( [ 'bar' => 'baz' ] ),
		] );

		$this->assertEquals( 'bar', $multi->get( 'foo' ) );
		$this->assertEquals( 'foo', $multi->get( 'bar' ) );
		$this->setExpectedException( ConfigException::class, 'MultiConfig::get: undefined option:' );
		$multi->get( 'notset' );
	}

	/**
	 * @covers MultiConfig::has
	 */
	public function testHas() {
		$conf = new MultiConfig( [
			new HashConfig( [ 'foo' => 'foo' ] ),
			new HashConfig( [ 'something' => 'bleh' ] ),
			new HashConfig( [ 'meh' => 'eh' ] ),
		] );

		$this->assertTrue( $conf->has( 'foo' ) );
		$this->assertTrue( $conf->has( 'something' ) );
		$this->assertTrue( $conf->has( 'meh' ) );
		$this->assertFalse( $conf->has( 'what' ) );
	}
}
