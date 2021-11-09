<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ArrayConfigBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ArrayConfigBuilder
 */
class ArrayConfigBuilderTest extends TestCase {

	public function testSet() {
		$builder = new ArrayConfigBuilder();
		$builder->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $builder->build()->get( 'foo' ) );
	}

	public function testSetIfNotDefined() {
		unset( $GLOBALS['prefix_baz'] );
		$GLOBALS['prefix_foo'] = 'bar';
		$builder = new ArrayConfigBuilder( [ 'foo' => 'bar' ] );
		$builder->set( 'baz', 'quux' );
		$builder->setIfNotDefined( 'foo', 'baz' );

		$this->assertEquals( 'quux', $builder->build()->get( 'baz' ) );
		$this->assertEquals( 'bar', $builder->build()->get( 'foo' ) );
	}

	public function testBuildArray() {
		$configBuilder = new ArrayConfigBuilder( [ 'foo' => 'bar', ] );
		$configBuilder->set( 'baz', 'quux' );
		$this->assertEquals( [
			'foo' => 'bar',
			'baz' => 'quux',
		], $configBuilder->buildArray() );
	}
}
