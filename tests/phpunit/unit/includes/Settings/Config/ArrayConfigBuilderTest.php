<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSink;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ArrayConfigBuilder
 */
class ArrayConfigBuilderTest extends TestCase {
	use ConfigSinkTestTrait;

	/** @var ArrayConfigBuilder */
	private $builder;

	protected function setUp(): void {
		parent::setUp();
		$this->builder = new ArrayConfigBuilder();
	}

	protected function getConfigSink(): ConfigSink {
		return $this->builder;
	}

	protected function assertKeyHasValue( string $key, $value ) {
		$this->assertEquals( $value, $this->builder->build()->get( $key ) );
	}

	public function testBuild() {
		$this->builder
			->set( 'foo',  'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $this->builder->build()->get( 'foo' ) );
		$this->assertSame( 'quu', $this->builder->build()->get( 'baz' ) );
	}
}
