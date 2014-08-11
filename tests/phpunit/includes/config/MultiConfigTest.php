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

	/**
	 * @covers MultiConfig::set
	 */
	public function testSet() {
		$conf1 = new HashConfig( array(
			'one' => '1',
		) );
		$conf2 = new HashConfig( array(
			'two' => '2',
		) );
		$multi = new MultiConfig( array( $conf1, $conf2 ) );
		$multi->set( 'three', '3' );
		$this->assertTrue( $multi->has( 'three' ) );
		$this->assertEquals( '3', $multi->get( 'three' ) );
		// Now check it was set in the first config object, and not the second.
		$this->assertTrue( $conf1->has( 'three' ) );
		$this->assertFalse( $conf2->has( 'three' ) );
	}
}