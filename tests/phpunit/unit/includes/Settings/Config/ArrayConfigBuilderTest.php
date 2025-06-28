<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Config\IterableConfig;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ArrayConfigBuilder
 */
class ArrayConfigBuilderTest extends TestCase {
	use ConfigSinkTestTrait;
	use MediaWikiCoversValidator;

	/** @var ArrayConfigBuilder */
	private $builder;

	protected function setUp(): void {
		parent::setUp();
		$this->builder = new ArrayConfigBuilder();
	}

	protected function getConfigSink(): ConfigBuilder {
		return $this->builder;
	}

	protected function assertKeyHasValue( string $key, mixed $value ): void {
		$this->assertEquals( $value, $this->builder->build()->get( $key ) );
	}

	public function testBuild() {
		$this->builder
			->set( 'foo', 'bar' )
			->set( 'baz', 'quu' );

		$config = $this->builder->build();

		$this->assertInstanceOf( IterableConfig::class, $config );
		$this->assertSame( 'bar', $config->get( 'foo' ) );
		$this->assertSame( 'quu', $config->get( 'baz' ) );
		$this->assertSame( [ 'foo', 'baz' ], $config->getNames() );
	}
}
