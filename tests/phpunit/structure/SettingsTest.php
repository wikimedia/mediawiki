<?php

namespace MediaWiki\Tests\Structure;

use ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\Format\YamlFormat;
use MediaWiki\Settings\Source\PhpSettingsSource;
use MediaWiki\Settings\Source\SettingsSource;
use MediaWiki\Shell\Shell;
use MediaWikiIntegrationTestCase;

/**
 * @coversNothing
 */
class SettingsTest extends MediaWikiIntegrationTestCase {

	/**
	 * Returns the contents of config-schema.yaml as an array.
	 *
	 * @return array
	 */
	private static function getSchemaData(): array {
		static $data = null;

		if ( !$data ) {
			$file = __DIR__ . '/../../../includes/config-schema.yaml';
			$data = file_get_contents( $file );
			$yaml = new YamlFormat();
			$data = $yaml->decode( $data );
		}

		return $data;
	}

	/**
	 * @return SettingsBuilder
	 */
	private function getSettingsBuilderWithSchema(): SettingsBuilder {
		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->loadArray( self::getSchemaData() );
		return $settingsBuilder;
	}

	public function testConfigSchemaIsLoadable() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$settingsBuilder->apply();

		// Assert we've read some random config value
		$this->assertTrue( $settingsBuilder->getConfig()->has( 'Server' ) );
	}

	/**
	 * Check that core default settings validate against the schema
	 */
	public function testConfigSchemaDefaultsValidate() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$validationResult = $settingsBuilder->apply()->validate();
		$this->assertArrayEquals(
			[],
			$validationResult->getErrorsByType( 'errors' ),
			false,
			false,
			"$validationResult"
		);
	}

	/**
	 * Check that currently loaded settings validate against the schema.
	 */
	public function testCurrentSettingsValidate() {
		global $wgSettings;
		$validationResult = $wgSettings->validate();
		$this->assertArrayEquals(
			[],
			$validationResult->getErrorsByType( 'error' ),
			false,
			false,
			"$validationResult"
		);
	}

	public function provideConfigGeneration() {
		yield 'docs/Configuration.md' => [
			'script' => __DIR__ . '/../../../maintenance/generateConfigDoc.php',
			'expectedFile' => __DIR__ . '/../../../docs/Configuration.md',
		];
		yield 'incl/Configuration.md' => [
			'script' => __DIR__ . '/../../../maintenance/generateConfigSchema.php',
			'expectedFile' => __DIR__ . '/../../../includes/config-schema.php',
		];
	}

	/**
	 * @dataProvider provideConfigGeneration
	 */
	public function testConfigGeneration( string $script, string $expectedFile ) {
		$schemaGenerator = Shell::makeScriptCommand( $script, [ '--output', 'php://stdout' ] );

		$result = $schemaGenerator->execute();
		$this->assertSame( 0, $result->getExitCode(), 'Config generation must finish successfully' );
		$this->assertSame( '', $result->getStderr(), 'Config generation must not have errors' );

		$oldGeneratedSchema = file_get_contents( $expectedFile );
		$relativePath = wfRelativePath( $script, __DIR__ . '/../../..' );

		$this->assertEquals(
			$oldGeneratedSchema,
			$result->getStdout(),
			"Configuration schema was changed. Rerun $relativePath script!"
		);
	}

	public function provideDefaultSettingsConsistency() {
		yield 'YAML' => [ new FileSource( 'includes/config-schema.yaml' ) ];
		yield 'PHP' => [ new PhpSettingsSource( 'includes/config-schema.php' ) ];
	}

	/**
	 * Check that the result of loading config-schema.yaml is the same as DefaultSettings.php
	 * This test can be removed when DefaultSettings.php is removed.
	 * @dataProvider provideDefaultSettingsConsistency
	 */
	public function testDefaultSettingsConsistency( SettingsSource $source ) {
		$defaultSettingsProps = ( static function () {
			require __DIR__ . '/../../../includes/DefaultSettings.php';
			$vars = get_defined_vars();
			unset( $vars['input'] );
			$result = [];
			foreach ( $vars as $key => $value ) {
				$result[substr( $key, 2 )] = $value;
			}
			return $result;
		} )();

		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->load( $source );
		$settingsBuilder->apply();

		foreach ( $defaultSettingsProps as $key => $value ) {
			if ( in_array( $key, [
				'Version', // deprecated alias to MW_VERSION
				'Conf', // instance of SiteConfiguration
				'AutoloadClasses', // conditionally initialized
			] ) ) {
				continue;
			}
			$this->assertTrue( $configBuilder->build()->has( $key ), "Missing $key" );
			$this->assertEquals( $value, $configBuilder->build()->get( $key ), "Wrong value for $key\n" );
		}
	}

	public function provideArraysHaveMergeStrategy() {
		[ 'config-schema' => $allSchemas ] = self::getSchemaData();

		foreach ( $allSchemas as $name => $schema ) {
			yield "Schema for $name" => [ $schema ];
		}
	}

	/**
	 * Check that the schema for each config variable contains all necessary information.
	 * @dataProvider provideArraysHaveMergeStrategy
	 */
	public function testArraysHaveMergeStrategy( $schema ) {
		$this->assertArrayHasKey(
			'default',
			$schema,
			'should specify a default value'
		);

		$type = $schema['type'] ?? null;
		$type = (array)$type;

		// If the default is an array, the type must be declared, so we know whether
		// it's a list (JS "array") or a map (JS "object").
		if ( is_array( $schema['default'] ) ) {
			$this->assertTrue(
				in_array( 'array', $type ) || in_array( 'object', $type ),
				'must be of type "array" or "object", since the default is an array'
			);
		}

		// If the default value of a list is not empty, check that it is an indexed array,
		// not an associative array.
		if ( in_array( 'array', $type ) && !empty( $schema['default'] ) ) {
			if ( empty( $schema['ignoreKeys'] ) ) {
				$this->assertArrayHasKey(
					0,
					$schema['default'],
					'should have a default value starting with index 0, since its type is "array", '
						. 'and ignoreKeys is not set.'
				);
			}
		}

		$mergeStrategy = $schema['mergeStrategy'] ?? null;

		// If a merge strategy is defined, make sure it makes sense for the given type.
		if ( $mergeStrategy ) {
			if ( in_array( 'array', $type ) ) {
				$this->assertNotSame(
					'array_merge',
					$mergeStrategy,
					'should not specify redundant mergeStrategy "array_merge" since '
						. 'it is implied by the type being "array"'
				);

				$this->assertNotSame(
					'array_plus',
					$mergeStrategy,
					'should not specify mergeStrategy "array_plus" since its type is "array"'
				);

				$this->assertNotSame(
					'array_plus_2d',
					$mergeStrategy,
					'should not specify mergeStrategy "array_plus_2d" since its type is "array"'
				);
			} elseif ( in_array( 'object', $type ) ) {
				$this->assertNotSame(
					'array_plus',
					$mergeStrategy,
					'should not specify redundant mergeStrategy "array_plus" since '
					. 'it is implied by the type being "object"'
				);

				$this->assertNotSame(
					'array_merge',
					$mergeStrategy,
					'should not specify mergeStrategy "array_merge" since its type is "object"'
				);
			}
		}
	}

	public function provideConfigStructureHandling() {
		yield 'NamespacesWithSubpages' => [
			'NamespacesWithSubpages',
			[ 0 => true, 1 => false,
				2 => true, 3 => true, 4 => true, 5 => true, 7 => true,
				8 => true, 9 => true, 10 => true, 11 => true, 12 => true,
				13 => true, 15 => true
			],
			[ 0 => true, 1 => false ]
		];
		yield 'InterwikiCache array' => [
			'InterwikiCache',
			[ 'x' => [ 'foo' => 1 ] ],
			[ 'x' => [ 'foo' => 1 ] ],
		];
		yield 'InterwikiCache string' => [
			'InterwikiCache',
			'interwiki.map',
			'interwiki.map',
		];
		yield 'InterwikiCache string over array' => [
			'InterwikiCache',
			'interwiki.map',
			[ 'x' => [ 'foo' => 1 ] ],
			'interwiki.map',
		];
		yield 'ProxyList array' => [
			'ProxyList',
			[ 'a', 'b', 'c' ],
			[ 'a', 'b', 'c' ],
		];
		yield 'ProxyList string' => [
			'ProxyList',
			'interwiki.map',
			'interwiki.map',
		];
		yield 'ProxyList string over array' => [
			'ProxyList',
			'interwiki.map',
			[ 'a', 'b', 'c' ],
			'interwiki.map',
		];
		yield 'ProxyList array over array' => [
			'ProxyList',
			[ 'a', 'b', 'c', 'd' ],
			[ 'a', 'b' ],
			[ 'c', 'd' ],
		];
		yield 'Logos' => [
			'Logos',
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
		];
		yield 'Logos clear' => [
			'Logos',
			false,
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
			false
		];
		yield 'RevokePermissions' => [
			'RevokePermissions',
			[ '*' => [ 'read' => true, 'edit' => true, ] ],
			[ '*' => [ 'edit' => true ] ],
			[ '*' => [ 'read' => true ] ]
		];
	}

	/**
	 * Ensure that some of the more complex/problematic config structures are handled
	 * correctly.
	 *
	 * @dataProvider provideConfigStructureHandling
	 */
	public function testConfigStructureHandling( $key, $expected, $value, $value2 = null ) {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$settingsBuilder->apply();

		$settingsBuilder->putConfigValue( $key, $value );

		if ( $value2 !== null ) {
			$settingsBuilder->putConfigValue( $key, $value2 );
		}

		$config = $settingsBuilder->getConfig();

		$this->assertSame( $expected, $config->get( $key ) );
	}

	public function provideConfigStructurePartialReplacement() {
		yield 'ObjectCaches' => [
			'ObjectCaches',
			[ // the spec for each cache should be replaced entirely
				1 => [ 'factory' => 'ObjectCache::newAnything' ],
				'test' => [ 'factory' => 'Testing' ]
			],
			[
				1 => [ 'factory' => 'ObjectCache::newAnything' ],
				'test' => [ 'factory' => 'Testing' ]
			],
		];
		yield 'GroupPermissions' => [
			'GroupPermissions',
			[ // permissions for each group should be merged
				'autoconfirmed' => [
					'autoconfirmed' => true,
					'editsemiprotected' => false,
					'patrol' => true,
				],
				'mygroup' => [ 'test' => true ],
			],
			[
				'autoconfirmed' => [
					'patrol' => true,
					'editsemiprotected' => false
				],
				'mygroup' => [ 'test' => true ],
			],
		];
		yield 'RateLimits' => [
			'RateLimits',
			[ // limits for each action should be merged, limits for each group get replaced
				'move' => [ 'newbie' => [ 1, 80 ], 'user' => [ 8, 60 ], 'ip' => [ 1, 60 ] ],
				'test' => [ 'ip' => [ 1, 60 ] ],
			],
			[
				'move' => [ 'ip' => [ 1, 60 ], 'newbie' => [ 1, 80 ], 'user' => [ 8, 60 ] ],
				'test' => [ 'ip' => [ 1, 60 ] ],
			]
		];
	}

	/**
	 * Ensure that some of the more complex/problematic config structures are
	 * correctly replacing parts of a complex default.
	 *
	 * @dataProvider provideConfigStructurePartialReplacement
	 */
	public function testConfigStructurePartialReplacement( $key, $expectedValue, $newValue ) {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$defaultValue = $settingsBuilder->getConfig()->get( $key );

		$settingsBuilder->putConfigValue( $key, $newValue );
		$mergedValue = $settingsBuilder->getConfig()->get( $key );

		// Check that the keys in $mergedValue that are also present
		// in $newValue now match $expectedValue.
		$updatedValue = array_intersect_key( $mergedValue, $newValue );
		$this->assertArrayEquals( $expectedValue, $updatedValue, false, true );

		// Check that the other keys in $mergedValue are still the same
		// as in $defaultValue.
		$mergedValue = array_diff_key( $mergedValue, $newValue );
		$defaultValue = array_diff_key( $defaultValue, $newValue );
		$this->assertArrayEquals( $defaultValue, $mergedValue, false, true );
	}

	/**
	 * Ensure that hook handlers are merged correctly.
	 */
	public function testHooksMerge() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();

		$f1 = static function () {
			// noop
		};

		$hooks = [
			'TestHook' => [
				'TestHookHandler1',
				[ 'TestHookHandler1', 'handler data' ],
				$f1,
			]
		];
		$settingsBuilder->putConfigValue( 'Hooks', $hooks );

		$f2 = static function () {
			// noop
		};

		$hooks = [
			'TestHook' => [
				'TestHookHandler2',
				[ 'TestHookHandler2', 'more handler data' ],
				$f2,
			]
		];
		$settingsBuilder->putConfigValue( 'Hooks', $hooks );

		$config = $settingsBuilder->getConfig();

		$hooks = [
			'TestHook' => [
				'TestHookHandler1',
				[ 'TestHookHandler1', 'handler data' ],
				$f1,
				'TestHookHandler2',
				[ 'TestHookHandler2', 'more handler data' ],
				$f2,
			]
		];
		$this->assertSame( $hooks, $config->get( 'Hooks' ) );
	}

	/**
	 * Ensure that PasswordPolicy are merged correctly.
	 */
	public function testPasswordPolicyMerge() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$defaultPolicies = $settingsBuilder->getConfig()->get( 'PasswordPolicy' );

		$newPolicies = [
			'policies' => [
				'sysop' => [
					'MinimalPasswordLength' => [
						'value' => 10,
						'suggestChangeOnLogin' => false,
					],
				],
				'bot' => [
					'MinimumPasswordLengthToLogin' => 2,
				],
			],
			'checks' => [
				'MinimalPasswordLength' => 'myLengthCheck',
				'SomeOtherCheck' => 'myOtherCheck',
			]
		];
		$settingsBuilder->putConfigValue( 'PasswordPolicy', $newPolicies );
		$mergedPolicies = $settingsBuilder->getConfig()->get( 'PasswordPolicy' );

		// check that the new policies have been applied
		$this->assertSame(
			[
				'MinimalPasswordLength' => [
					'value' => 10,
					'suggestChangeOnLogin' => false,
				],
				'MinimumPasswordLengthToLogin' => 1, // from defaults
			],
			$mergedPolicies['policies']['sysop']
		);
		$this->assertSame(
			[
				'MinimalPasswordLength' => 10, // from defaults
				'MinimumPasswordLengthToLogin' => 2,
			],
			$mergedPolicies['policies']['bot']
		);
		$this->assertSame(
			'myLengthCheck',
			$mergedPolicies['checks']['MinimalPasswordLength']
		);
		$this->assertSame(
			'myOtherCheck',
			$mergedPolicies['checks']['SomeOtherCheck']
		);

		// check that other stuff wasn't changed
		$this->assertSame(
			$defaultPolicies['checks']['PasswordCannotMatchDefaults'],
			$mergedPolicies['checks']['PasswordCannotMatchDefaults']
		);
		$this->assertSame(
			$defaultPolicies['policies']['bureaucrat'],
			$mergedPolicies['policies']['bureaucrat']
		);
		$this->assertSame(
			$defaultPolicies['policies']['default'],
			$mergedPolicies['policies']['default']
		);
	}

}
