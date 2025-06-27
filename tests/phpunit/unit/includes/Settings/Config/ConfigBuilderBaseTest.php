<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\ConfigBuilderBase;
use MediaWiki\Settings\Config\GlobalConfigBuilder;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ConfigBuilderBase
 */
class ConfigBuilderBaseTest extends TestCase {
	use ConfigSinkTestTrait;
	use MediaWikiCoversValidator;

	/** @var ConfigBuilder */
	private $builder;

	/**
	 * @before
	 */
	protected function configBuilderSetUp() {
		// Similar to ArrayConfigBuilder, but without optimizations,
		// so we can use the generic implementations.
		$this->builder = new class() extends ConfigBuilderBase {
			private $config = [];

			public function build(): Config {
				return new HashConfig( $this->config );
			}

			protected function has( string $key ): bool {
				return array_key_exists( $key, $this->config );
			}

			public function get( string $key ) {
				return $this->config[$key];
			}

			protected function update( string $key, $value ) {
				$this->config[$key] = $value;
			}
		};
	}

	protected function getConfigSink(): ConfigBuilder {
		return $this->builder;
	}

	protected function assertKeyHasValue( string $key, mixed $value ): void {
		$this->assertSame( $value, $this->builder->get( $key ) );
	}

	public function testBuild() {
		$builder = new GlobalConfigBuilder();
		$builder
			->set( 'foo', 'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $builder->build()->get( 'foo' ) );
		$this->assertSame( 'quu', $builder->build()->get( 'baz' ) );
	}
}
