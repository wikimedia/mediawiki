<?php

namespace phpunit\unit\includes\Settings;

use BagOStuff;
use ExtensionRegistry;
use InvalidArgumentException;
use MediaWiki\MainConfigSchema;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\SettingsBuilder
 */
class SettingsBuilderTest extends TestCase {

	/**
	 * @param array $params
	 * @return SettingsBuilder
	 */
	private function newSettingsBuilder( $params = [] ): SettingsBuilder {
		return new SettingsBuilder(
			__DIR__,
			$params['extensionRegistry'] ?? $this->createMock( ExtensionRegistry::class ),
			$params['configBuilder'] ?? new ArrayConfigBuilder(),
			$params['phpIniSink'] ?? $this->createMock( PhpIniSink::class ),
			$params['cache'] ?? null
		);
	}

	public function testLoadingFromFile() {
		$phpIniSinkMock = $this->createMock( PhpIniSink::class );
		$phpIniSinkMock->expects( $this->once() )->method( 'set' )->with( 'foo', 'bar' );

		$setting = $this->newSettingsBuilder( [
			'phpIniSink' => $phpIniSinkMock
		] );
		$setting->loadFile( 'fixtures/settings.json' )->apply();

		$config = $setting->getConfig();
		$this->assertSame( 'TEST', $config->get( 'Something' ) );
	}

	public function testLoadingIncludesRecursiveFromFile() {
		$configBuilder = new ArrayConfigBuilder();

		$phpIniSinkMock = $this->createMock( PhpIniSink::class );
		$phpIniSinkMock->expects( $this->once() )->method( 'set' )->with( 'foo', 'bar' );

		// Make sure the cache is hit twice, once for each file!
		$mockCache = $this->createMock( BagOStuff::class );
		$key = 'foo';

		$mockCache
			->expects( $this->atLeastOnce() )
			->method( 'makeGlobalKey' )
			->willReturn( $key );

		$mockCache
			->expects( $this->exactly( 2 ) )
			->method( 'get' )
			->willReturn( false );

		$mockCache
			->expects( $this->exactly( 2 ) )
			->method( 'lock' )
			->willReturn( true );

		$mockCache
			->expects( $this->exactly( 2 ) )
			->method( 'unlock' )
			->willReturn( true );

		$setting = $this->newSettingsBuilder( [
			'configBuilder' => $configBuilder,
			'phpIniSink' => $phpIniSinkMock,
			'cache' => $mockCache,
		] );
		$setting->loadFile( 'fixtures/settings-with-includes.json' )->apply();

		$config = $configBuilder->build();
		$this->assertSame( 'TEST', $config->get( 'Something' ) );
	}

	/**
	 * Ensure that includes work in a source that is not a SettingsIncludeLocator
	 */
	public function testLoadingIncludesRecursiveFromArray() {
		$configBuilder = new ArrayConfigBuilder();

		$phpIniSinkMock = $this->createMock( PhpIniSink::class );
		$phpIniSinkMock->expects( $this->once() )->method( 'set' )->with( 'foo', 'bar' );

		$setting = $this->newSettingsBuilder( [
			'configBuilder' => $configBuilder,
			'phpIniSink' => $phpIniSinkMock,
		] );
		$setting->loadArray( [ 'includes' => [ 'fixtures/settings.json' ] ] )->apply();

		$config = $configBuilder->build();
		$this->assertSame( 'TEST', $config->get( 'Something' ) );
	}

	public function testLoadingIncludesRecursiveLoop() {
		$setting = $this->newSettingsBuilder();

		$this->expectException( SettingsBuilderException::class );
		$setting->loadFile( 'fixtures/settings-with-self-includes.json' )->apply();
	}

	public function testLoadingAndApplyingFromFileAfterFinalize() {
		$configBuilder = new ArrayConfigBuilder();

		$phpIniSinkMock = $this->createMock( PhpIniSink::class );
		$phpIniSinkMock->expects( $this->once() )->method( 'set' )->with( 'foo', 'bar' );

		$setting = $this->newSettingsBuilder( [
			'configBuilder' => $configBuilder,
			'phpIniSink' => $phpIniSinkMock
		] );
		$setting->loadFile( 'fixtures/settings.json' )->apply();

		$config = $configBuilder->build();
		$this->assertSame( 'TEST', $config->get( 'Something' ) );

		// Finalize and lock loading & applying anymore settings
		$setting->finalize();

		$this->expectException( SettingsBuilderException::class );
		$setting->loadFile( 'fixtures/settings.json' )->apply();
	}

	public function testLoadingExtensions() {
		$extensionRegistryMock = $this->createMock( ExtensionRegistry::class );
		$extensionRegistryMock
			->expects( $this->exactly( 3 ) )
			->method( 'queue' )->withConsecutive(
				[ '/test/extensions/Foo/extension.json' ],
				[ '/test/extensions/Bar/extension.json' ],
				[ '/test/skins/Quux/skin.json' ]
			);

		$setting = $this->newSettingsBuilder( [
			'extensionRegistry' => $extensionRegistryMock,
		] );
		$setting->loadFile( 'fixtures/default-schema.json' );
		$setting->loadFile( 'fixtures/settings.json' );
		$setting->apply();
	}

	public function provideConfigDefaults() {
		yield 'sets a value from a single settings file' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
			],
			'expected' => [
				'MySetting' => 'MyValue',
			],
		];
		yield 'merges different values from multiple settings files' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
				[ 'config' => [ 'MyOtherSetting' => 'MyOtherValue', ], ],
			],
			'expected' => [
				'MySetting' => 'MyValue',
				'MyOtherSetting' => 'MyOtherValue',
			],
		];
		yield 'overrides value in config' => [
			'settingsBatches' => [
				[ 'config' => [ 'MySetting' => 'MyValue', ], ],
				[ 'config' => [ 'MySetting' => 'MyOtherValue', ], ],
			],
			'expected' => [
				'MySetting' => 'MyOtherValue',
			],
		];
		yield 'sets a default from schema' => [
			'settingsBatches' => [
				[ 'config-schema' => [ 'MySetting' => [ 'default' => 'MyDefault', ], ], ],
			],
			'expected' => [
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
			'expected' => [
				'MySetting' => 'MyValue',
			],
		];
		yield 'default null is applied' => [
			'settingsBatches' => [
				[ 'config-schema' => [ 'MySetting' => [ 'default' => null, ], ], ],
			],
			'expected' => [
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
			'expected' => [
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
			'expected' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
		yield 'merge strategy is ignored when using config-overrides' => [
			'settingsBatches' => [
				[
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE
					], ],
					'config-overrides' => [ 'MySetting' => [ 'a' => [ 'b' => 'c' ], ], ],
				],
				[
					'config-overrides' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
				]
			],
			'expected' => [
				'MySetting' => [ 'a' => [ 'b' => 'd' ], ],
			],
		];
		yield 'config value is merged into default when setting both in the same settings file' => [
			'settingsBatches' => [
				[
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE,
						'default' => [ 'a' => [ 'b' => 'c' ], ],
					], ],
				]
			],
			'expected' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
		yield 'default is merged backwards into config value when set later' => [
			'settingsBatches' => [
				[
					'config' => [ 'MySetting' => [ 'a' => [ 'b' => 'd' ], ], ],
				],
				[
					'config-schema' => [ 'MySetting' => [
						'mergeStrategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE,
						'default' => [ 'a' => [ 'b' => 'c' ], ],
					], ],
				]
			],
			'expected' => [
				'MySetting' => [ 'a' => [ 'b' => [ 'c', 'd' ], ], ],
			],
		];
	}

	/**
	 * @dataProvider provideConfigDefaults
	 */
	public function testConfigDefaults( array $settingsBatches, array $expected ) {
		$configBuilder = new ArrayConfigBuilder();
		$setting = $this->newSettingsBuilder( [ 'configBuilder' => $configBuilder ] );
		foreach ( $settingsBatches as $batch ) {
			$setting->loadArray( $batch );
		}
		$setting->apply();
		foreach ( $expected as $key => $value ) {
			$this->assertSame( $value, $configBuilder->build()->get( $key ) );
		}
	}

	public function testSetConfig() {
		$setting = $this->newSettingsBuilder();

		$setting->loadArray( [
			'config-schema' => [
				'a' => [ 'mergeStrategy' => MergeStrategy::ARRAY_MERGE ]
			]
		] );

		$setting->putConfigValues( [ 'a' => [ 1 ], 'b' => 2 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 1 ], $config->get( 'a' ) );
		$this->assertSame( 2, $config->get( 'b' ) );

		$setting->putConfigValues( [ 'a' => [ 11 ], 'b' => 22 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 1, 11 ], $config->get( 'a' ) );
		$this->assertSame( 22, $config->get( 'b' ) );

		$setting->putConfigValue( 'a', [ 111 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 1, 11, 111 ], $config->get( 'a' ) );
		$this->assertSame( 22, $config->get( 'b' ) );
	}

	public function testOverrideConfig() {
		$setting = $this->newSettingsBuilder();

		$setting->loadArray( [
			'config-schema' => [
				'a' => [ 'mergeStrategy' => MergeStrategy::ARRAY_MERGE ]
			]
		] );

		$setting->overrideConfigValues( [ 'a' => [ 1 ], 'b' => 2 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 1 ], $config->get( 'a' ) );
		$this->assertSame( 2, $config->get( 'b' ) );

		$setting->overrideConfigValues( [ 'a' => [ 11 ], 'b' => 22 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 11 ], $config->get( 'a' ) );
		$this->assertSame( 22, $config->get( 'b' ) );

		$setting->overrideConfigValue( 'a', [ 111 ] );

		$config = $setting->getConfig();
		$this->assertSame( [ 111 ], $config->get( 'a' ) );
		$this->assertSame( 22, $config->get( 'b' ) );
	}

	public function testApplyPurgesState() {
		$configBuilder = new ArrayConfigBuilder();
		$setting = $this->newSettingsBuilder( [ 'configBuilder' => $configBuilder ] );
		$setting->putConfigValue( 'MySetting', 'MyValue' )
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

		// If we set a value via SettingsBuilder, defining a default later
		// should not override it.
		$this->newSettingsBuilder( [ 'configBuilder' => $configBuilder ] )
			->putConfigValue( 'MySetting', 'existing' )
			->loadArray( [ 'config-schema' => [ 'MySetting' => [ 'default' => 'default' ], ], ] )
			->apply();

		$this->assertSame( 'existing', $configBuilder->build()->get( 'MySetting' ) );
	}

	public function testApplyDefaultDoesNotOverwritePreexisting() {
		$configBuilder = new ArrayConfigBuilder();

		// If we set a value in the config directly, defining a default later
		// would override it, unless we call assumeDirtyConfig().
		$configBuilder->set( 'MySetting', 'existing' );

		$this->newSettingsBuilder( [ 'configBuilder' => $configBuilder ] )
			->assumeDirtyConfig()
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

	public function testConfigSchemaDefaultsEvaluation() {
		$settingsBuilder = $this->newSettingsBuilder();
		$settingsBuilder->loadArray( [
			'config-schema' => [
				'A' => [ 'default' => [ 'a' ], 'type' => 'array' ],
				'B' => [ 'default' => [ 'b' ], 'type' => 'array' ],
			],
			'config' => [
				'B' => [ 'b2' ],
				'C' => [ 'c2' ],
			]
		] );
		$settingsBuilder->loadArray( [
			'config-schema' => [
				'X' => [ 'default' => [ 'x' ], 'type' => 'array' ],
			],
			'config' => [
				'A' => [ 'a3' ],
				'B' => [ 'b3' ],
			],
			'config-overrides' => [
				'C' => [ 'c4' ],
			]
		] );

		$config = $settingsBuilder->getConfig();
		$this->assertSame( [ 'a', 'a3' ], $config->get( 'A' ) );
		$this->assertSame( [ 'b', 'b2', 'b3' ], $config->get( 'B' ) );
		$this->assertSame( [ 'c4' ], $config->get( 'C' ) );
		$this->assertSame( [ 'x' ], $config->get( 'X' ) );
	}

	public function provideValidate() {
		yield 'all good' => [
			'settings' => [
				'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
				'config' => [ 'foo' => 'bar', ],
			],
			'valid' => true,
		];
		yield 'missing key' => [
			'settings' => [
				'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
				'config' => [ 'bar' => 'bar' ],
			],
			'valid' => false,
		];
		yield 'invalid config' => [
			'settings' => [
				'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
				'config' => [ 'foo' => 1 ],
			],
			'valid' => false,
		];
		yield 'no schema was added' => [
			'settings' => [
				'config-schema' => [],
				'config' => [ 'foo' => 'bar', ],
			],
			'valid' => true,
		];
		yield 'key is in config but has no schema' => [
			'settings' => [
				'config-schema' => [ 'foo' => [ 'type' => 'array', 'mergeStrategy' => MergeStrategy::ARRAY_MERGE ], ],
				'config' => [ 'foo' => [], 'baz' => false, ],
			],
			'valid' => true,
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( array $settings, bool $valid ) {
		$status = $this->newSettingsBuilder()
			->loadArray( $settings )
			->apply()
			->validate();
		$this->assertSame( $valid, $status->isOK() );
	}

	public function testValidateInvalidSchema() {
		$this->expectException( InvalidArgumentException::class );
		$this->newSettingsBuilder()
			->loadArray( [
				'config-schema' => [ 'foo' => [ 'type' => 1 ] ],
				'config' => [ 'foo' => 'bar' ],
			] )
			->apply()
			->validate();
	}

	public function testLoadsCacheableSource() {
		$mockSource = $this->createMock( CacheableSource::class );
		$mockCache = $this->createMock( BagOStuff::class );
		$configBuilder = new ArrayConfigBuilder();
		$builder = $this
			->newSettingsBuilder( [
				'configBuilder' => $configBuilder,
				'cache' => $mockCache
			] )
			->load( $mockSource );

		$hashKey = 'abc123';
		$key = 'global:MediaWiki\Tests\Unit\Settings\Cache\CachedSourceTest:' . $hashKey;

		// Mock a cache miss
		$mockSource
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$mockCache
			->expects( $this->once() )
			->method( 'makeGlobalKey' )
			->with( 'MediaWiki\Settings\Cache\CachedSource', $hashKey )
			->willReturn( $key );

		$mockCache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key )
			->willReturn( false );

		$mockCache
			->expects( $this->once() )
			->method( 'lock' )
			->willReturn( true );

		$mockCache
			->expects( $this->once() )
			->method( 'unlock' )
			->willReturn( true );

		$mockSource
			->expects( $this->once() )
			->method( 'load' )
			->willReturn( [ 'config' => [ 'MySetting' => 'BlaBla' ] ] );

		$builder->apply();

		$this->assertSame( 'BlaBla', $configBuilder->build()->get( 'MySetting' ) );
	}

	public function testGetDefaultConfig() {
		$defaultConfig = $this->newSettingsBuilder()
			->loadArray( [ 'config-schema' => [
				'MySetting' => [ 'default' => 'bla' ],
				'OtherSetting' => [ 'type' => 'number' ], // no default
				] ] )
			->apply()
			->getDefaultConfig();

		$this->assertTrue( $defaultConfig->has( 'MySetting' ) );
		$this->assertSame( 'bla', $defaultConfig->get( 'MySetting' ) );

		$this->assertTrue( $defaultConfig->has( 'OtherSetting' ) );
		$this->assertNull( $defaultConfig->get( 'OtherSetting' ) );
	}

	public function testGetConfigSchema() {
		$configSchema = $this->newSettingsBuilder()
			->loadArray( [ 'config-schema' => [ 'MySetting' => [ 'default' => 'bla' ], ], ] )
			->apply()
			->getConfigSchema();
		$this->assertTrue( $configSchema->hasSchemaFor( 'MySetting' ) );
		$this->assertSame( 'bla', $configSchema->getDefaultFor( 'MySetting' ) );
	}

	/**
	 * Make sure that the 'config-schema' and 'config-schema-inverse' keys
	 * are fully processed and combined appropriately.
	 */
	public function testSchemaLoading() {
		$settings = $this->newSettingsBuilder();
		$settings->loadFile( 'fixtures/default-schema.json' );
		$schema = $settings->getConfigSchema();

		$this->assertSame(
			'/DEFAULT/',
			$schema->getDefaultFor( 'StyleDirectory' )
		);
		$this->assertSame(
			[ 'callback' => [ MainConfigSchema::class, 'getDefaultUsePathInfo' ] ],
			$schema->getDynamicDefaultDeclarationFor( 'UsePathInfo' )
		);
		$this->assertSame(
			'replace',
			$schema->getMergeStrategyFor( 'LBFactoryConf' )->getName()
		);
		$this->assertSame(
			'/DEFAULT/',
			$schema->getDefaultFor( 'ExtensionDirectory' )
		);
		$this->assertSame(
			[ 'use' => [ 'ScriptPath' ], 'callback' => [ MainConfigSchema::class, 'getDefaultRestPath' ] ],
			$schema->getDynamicDefaultDeclarationFor( 'RestPath' )
		);
		$this->assertSame(
			[ 'use' => [ 'ScriptPath' ], 'callback' => [ MainConfigSchema::class, 'getDefaultRestPath' ] ],
			$schema->getDynamicDefaultDeclarationFor( 'RestPath' )
		);
		$this->assertSame(
			'replace',
			$schema->getMergeStrategyFor( 'TiffThumbnailType' )->getName()
		);
	}

}
