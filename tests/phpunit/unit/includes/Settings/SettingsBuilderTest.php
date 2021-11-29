<?php

namespace phpunit\unit\includes\Settings;

use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSink;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \MediaWiki\Settings\SettingsBuilder
 */
class SettingsBuilderTest extends TestCase {

	/**
	 * @param ConfigSink|null $configBuilder
	 * @param PhpIniSink|null $phpIniSink
	 * @param CacheInterface|null $cache
	 * @return SettingsBuilder
	 */
	private function newSettingsBuilder(
		ConfigSink $configBuilder = null,
		PhpIniSink $phpIniSink = null,
		CacheInterface $cache = null
	): SettingsBuilder {
		return new SettingsBuilder(
			__DIR__,
			$configBuilder ?? new ArrayConfigBuilder(),
			$phpIniSink ?? new PhpIniSink(),
			$cache
		);
	}

	public function testLoadingFromFile() {
		$configBuilder = new ArrayConfigBuilder();

		$phpIniSinkMock = $this->createMock( PhpIniSink::class );
		$phpIniSinkMock->expects( $this->once() )->method( 'set' )->with( 'foo', 'bar' );

		$setting = $this->newSettingsBuilder( $configBuilder, $phpIniSinkMock );
		$setting->loadFile( 'fixtures/settings.json' )->apply();

		$config = $configBuilder->build();
		$this->assertSame( 'TEST', $config->get( 'Something' ) );
	}

	public function provideConfigOverrides() {
		yield 'sets a value from a single settings file' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
			],
			'expectedGlobals' => [
				'MySetting' => 'MyValue',
			],
		];
		yield 'merges different values from multiple settings files' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
				[ 'config' => [ 'MyOtherSetting' => 'MyOtherValue', ], ],
			],
			'expectedGlobals' => [
				'MySetting' => 'MyValue',
				'MyOtherSetting' => 'MyOtherValue',
			],
		];
		yield 'overrides value in config' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
				[ 'config' => [ 'MySetting' => 'MyOtherValue', ], ],
			],
			'expectedGlobals' => [
				'MySetting' => 'MyOtherValue',
			],
		];
		yield 'sets a default from schema' => [
			'settingsBatches' => [
				[ 'config-schema' => [ 'MySetting' => [ 'default' => 'MyDefault', ], ], ],
			],
			'expectedGlobals' => [
				'MySetting' => 'MyDefault',
			],
		];
		yield 'value in config overrides default from schema' => [
			'settingsBatches' => [
				[
					'config-schema' => [ 'MySetting' => [ 'default' => 'MyDefault', ], ],
					'config' => [ 'MySetting' => 'MyValue', ],
				],
			],
			'expectedGlobals' => [
				'MySetting' => 'MyValue',
			],
		];
		yield 'default null is applied' => [
			'settingsBatches' => [
				[ 'config-schema' => [ 'MySetting' => [ 'default' => null, ], ], ],
			],
			'expectedGlobals' => [
				'MySetting' => null,
			],
		];
		yield 'null value can override default' => [
			'settingsBatches' => [
				[
					'config-schema' => [ 'MySetting' => [ 'default' => 'default', ], ],
					'config' => [ 'MySetting' => null, ],
				],
			],
			'expectedGlobals' => [
				'MySetting' => null,
			],
		];
		yield 'merge strategy is applied when setting config' => [
			'settingsBatches' => [
				[
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE
					], ],
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'c' ], ], ],
				],
				[
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
				]
			],
			'expectedGlobals' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
		yield 'merge strategy is applied backwards setting schema default' => [
			'settingsBatches' => [
				[
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE,
						'default' => [ 'a' => [ 'b' => 'c' ], ],
					], ],
				]
			],
			'expectedGlobals' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
		yield 'merge strategy is applied backwards setting schema default in different batch' => [
			'settingsBatches' => [
				[
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
				], [
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE,
						'default' => [ 'a' => [ 'b' => 'c' ], ],
					], ],
				]
			],
			'expectedGlobals' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
	}

	/**
	 * @dataProvider provideConfigOverrides
	 */
	public function testConfigOverrides( array $settingsBatches, array $expectedGlobals ) {
		$configBuilder = new ArrayConfigBuilder();
		$setting = $this->newSettingsBuilder( $configBuilder );
		foreach ( $settingsBatches as $batch ) {
			$setting->loadArray( $batch );
		}
		$setting->apply();
		foreach ( $expectedGlobals as $key => $value ) {
			$this->assertSame( $value, $configBuilder->build()->get( $key ) );
		}
	}

	public function testApplyPurgesState() {
		$configBuilder = new ArrayConfigBuilder();
		$setting = $this->newSettingsBuilder( $configBuilder );
		$setting->loadArray( [ 'config' => [ 'MySetting' => 'MyValue', ], ] )
			->apply();
		$this->assertSame( 'MyValue', $configBuilder->build()->get( 'MySetting' ) );
		$configBuilder->set( 'MySetting', 'MyOtherValue' );
		// Calling apply a second time should not redefine the global
		// since the state should be cleared
		$setting->apply();
		$this->assertSame( 'MyOtherValue', $configBuilder->build()->get( 'MySetting' ) );
	}

	public function testApplyDefaultDoesNotOverwriteExisting() {
		$configBuilder = new ArrayConfigBuilder();
		$configBuilder->set( 'MySetting', 'existing' );
		$this->newSettingsBuilder( $configBuilder )
			->loadArray( [ 'config-schema' => [ 'MySetting' => [ 'default' => 'default' ], ], ] )
			->apply();
		$this->assertSame( 'existing', $configBuilder->build()->get( 'MySetting' ) );
	}

	public function testConfigSchemaOverrideNotAllowed() {
		$this->expectException( SettingsBuilderException::class );
		$this->newSettingsBuilder()
			->loadArray( [ 'config-schema' => [ 'MySetting' => [ 'default' => 'default' ], ], ] )
			->loadArray( [ 'config-schema' => [ 'MySetting' => [ 'default' => 'override' ], ], ] )
			->apply();
	}

	public function testLoadsCacheableSource() {
		$mockSource = $this->createMock( CacheableSource::class );
		$mockCache = $this->createMock( CacheInterface::class );
		$configBuilder = new ArrayConfigBuilder();
		$builder = $this
			->newSettingsBuilder( $configBuilder, null, $mockCache )
			->load( $mockSource );

		// Mock a cache miss
		$mockSource
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( 'abc123' );

		$mockCache
			->expects( $this->once() )
			->method( 'get' )
			->with( 'abc123' )
			->willReturn( null );

		$mockSource
			->expects( $this->once() )
			->method( 'load' )
			->willReturn( [ 'config' => [ 'MySetting' => 'BlaBla' ] ] );

		$builder->apply();

		$this->assertSame( 'BlaBla', $configBuilder->build()->get( 'MySetting' ) );
	}
}
