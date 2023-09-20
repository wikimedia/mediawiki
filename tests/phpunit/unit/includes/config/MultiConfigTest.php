<?php

use MediaWiki\Config\ConfigException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;

/**
 * @covers \MediaWiki\Config\MultiConfig
 */
class MultiConfigTest extends \MediaWikiUnitTestCase {

	public function testGet() {
		// Assert that settings are applied in the right order
		$multi = new MultiConfig( [
			new HashConfig( [ 'foo' => 'bar' ] ),
			new HashConfig( [ 'foo' => 'baz', 'bar' => 'foo' ] ),
			new HashConfig( [ 'bar' => 'baz' ] ),
		] );

		$this->assertEquals( 'bar', $multi->get( 'foo' ) );
		$this->assertEquals( 'foo', $multi->get( 'bar' ) );
		$this->expectException( ConfigException::class );
		$this->expectExceptionMessage( 'MultiConfig::get: undefined option:' );
		$multi->get( 'notset' );
	}

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
