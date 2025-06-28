<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\GlobalConfigBuilder;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\GlobalConfigBuilder
 */
class GlobalConfigBuilderTest extends TestCase {
	use ConfigSinkTestTrait;
	use MediaWikiCoversValidator;

	protected function getConfigSink(): ConfigBuilder {
		return new GlobalConfigBuilder( 'GlobalConfigBuilderTestPrefix_' );
	}

	protected function assertKeyHasValue( string $key, mixed $value ): void {
		$this->assertEquals( $value, $GLOBALS['GlobalConfigBuilderTestPrefix_' . $key] );
	}

	public function testBuild() {
		$builder = new GlobalConfigBuilder();
		$builder
			->set( 'foo', 'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $builder->build()->get( 'foo' ) );
		$this->assertSame( 'quu', $builder->build()->get( 'baz' ) );
	}

	public function testPrefix() {
		$builder = new GlobalConfigBuilder( 'prefix_' );
		$builder
			->set( 'foo', 'bar' )
			->set( 'baz', 'quu' );
		$this->assertSame( 'bar', $builder->build()->get( 'foo' ) );
		$this->assertSame( 'bar', $GLOBALS['prefix_foo'] );
		$this->assertSame( 'quu', $builder->build()->get( 'baz' ) );
		$this->assertSame( 'quu', $GLOBALS['prefix_baz'] );
	}

	public function testMergeWithGlobal() {
		$GLOBALS['GlobalConfigBuilderTestPrefix_foo'] = [ 'a' => 1, 'b' => 2 ];

		$this->getConfigSink()
			->set(
				'foo',
				[ 'a' => 11, 'c' => 33 ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( 'foo', [ 'a' => 11, 'b' => 2, 'c' => 33 ] );
		$this->assertSame(
			[ 'a' => 11, 'b' => 2, 'c' => 33 ],
			$GLOBALS['GlobalConfigBuilderTestPrefix_foo']
		);
	}
}
