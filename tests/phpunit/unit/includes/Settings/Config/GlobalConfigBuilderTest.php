<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\GlobalConfigBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\GlobalConfigBuilder
 */
class GlobalConfigBuilderTest extends TestCase {
	use ConfigSinkTestTrait;

	protected function getConfigSink(): ConfigBuilder {
		return new GlobalConfigBuilder( 'prefix_' );
	}

	protected function assertKeyHasValue( string $key, $value ) {
		$this->assertEquals( $value, $GLOBALS['prefix_' . $key] );
	}

	public function testBuild() {
		$builder = new GlobalConfigBuilder();
		$builder
			->set( 'foo',  'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $builder->build()->get( 'foo' ) );
		$this->assertSame( 'quu', $builder->build()->get( 'baz' ) );
	}

	public function testPrefix() {
		$builder = new GlobalConfigBuilder( 'prefix_' );
		$builder
			->set( 'foo',  'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $builder->build()->get( 'foo' ) );
		$this->assertSame( 'bar', $GLOBALS['prefix_foo'] );
		$this->assertSame( 'quu', $builder->build()->get( 'baz' ) );
		$this->assertSame( 'quu', $GLOBALS['prefix_baz'] );
	}
}
