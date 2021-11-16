<?php

namespace phpunit\unit\includes\Settings;

use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\SettingsBuilder
 */
class SettingsBuilderTest extends TestCase {

	/**
	 * @param ConfigSink|null $configBuilder
	 * @return SettingsBuilder
	 */
	private function newSettingsBuilder( ConfigSink $configBuilder = null ): SettingsBuilder {
		return new SettingsBuilder(
			__DIR__,
			$configBuilder ?? new ArrayConfigBuilder()
		);
	}

	public function testLoadingFromFile() {
		$configBuilder = new ArrayConfigBuilder();
		$setting = $this->newSettingsBuilder( $configBuilder );
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
		$configBuilder = ( new ArrayConfigBuilder() )
			->set( 'MySetting', 'existing' );
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
}
