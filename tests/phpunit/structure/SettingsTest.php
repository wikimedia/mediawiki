<?php

namespace MediaWiki\Tests\Structure;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\JsonSchemaTrait;
use MediaWiki\Settings\Source\PhpSettingsSource;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use MediaWiki\Settings\Source\SettingsSource;
use MediaWiki\Shell\Shell;
use MediaWikiIntegrationTestCase;

/**
 * @coversNothing
 */
class SettingsTest extends MediaWikiIntegrationTestCase {
	use JsonSchemaTrait;

	/**
	 * Returns the main configuration schema as a settings array.
	 */
	private static function getSchemaData(): array {
		$source = new ReflectionSchemaSource( MainConfigSchema::class, true );
		$settings = $source->load();
		return $settings;
	}

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
		$this->assertTrue( $settingsBuilder->getConfig()->has( MainConfigNames::Server ) );
	}

	/**
	 * Check that core default settings validate against the schema
	 */
	public function testConfigSchemaDefaultsValidate() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$validationResult = $settingsBuilder->apply()->validate();
		$this->assertStatusOK( $validationResult );
	}

	/**
	 * Check that currently loaded settings validate against the schema.
	 */
	public function testCurrentSettingsValidate() {
		$validationResult = SettingsBuilder::getInstance()->validate();
		$this->assertStatusOK( $validationResult );
	}

	/**
	 * Check that currently loaded config does not use deprecated settings.
	 */
	public function testCurrentSettingsNotDeprecated() {
		$deprecations = SettingsBuilder::getInstance()->detectDeprecatedConfig();
		$this->assertEquals( [], $deprecations );
	}

	/**
	 * Check that currently loaded config does not use obsolete settings.
	 */
	public function testCurrentSettingsNotObsolete() {
		$obsolete = SettingsBuilder::getInstance()->detectObsoleteConfig();
		$this->assertEquals( [], $obsolete );
	}

	/**
	 * Check that currently loaded config does not have warnings.
	 */
	public function testCurrentSettingsHaveNoWarnings() {
		$deprecations = SettingsBuilder::getInstance()->getWarnings();
		$this->assertEquals( [], $deprecations );
	}

	public static function provideConfigGeneration() {
		yield 'includes/config-schema.php' => [
			'option' => '--schema',
			'expectedFile' => MW_INSTALL_PATH . '/includes/config-schema.php',
		];
		yield 'docs/config-vars.php' => [
			'option' => '--vars',
			'expectedFile' => MW_INSTALL_PATH . '/docs/config-vars.php',
		];
		yield 'docs/config-schema.yaml' => [
			'option' => '--yaml',
			'expectedFile' => MW_INSTALL_PATH . '/docs/config-schema.yaml',
		];
		yield 'includes/MainConfigNames.php' => [
			'option' => '--names',
			'expectedFile' => MW_INSTALL_PATH . '/includes/MainConfigNames.php',
		];
	}

	/**
	 * @dataProvider provideConfigGeneration
	 */
	public function testConfigGeneration( string $option, string $expectedFile ) {
		$script = 'GenerateConfigSchema';
		$schemaGenerator = Shell::makeScriptCommand( $script, [ $option, 'php://stdout' ] );
		$result = $schemaGenerator->execute();
		$this->assertSame(
			0,
			$result->getExitCode(),
			'Config generation must finish successfully.' . "\n" . $result->getStderr()
		);

		$errors = $result->getStderr();
		$errors = preg_replace( '/^Xdebug:.*\n/m', '', $errors );
		$this->assertSame( '', $errors, 'Config generation must not have errors' );

		$oldGeneratedSchema = file_get_contents( $expectedFile );
		$relativePath = wfRelativePath( $script, MW_INSTALL_PATH );

		$this->assertEquals(
			$oldGeneratedSchema,
			$result->getStdout(),
			"Configuration schema was changed. Rerun $relativePath script!"
		);
	}

	public static function provideDefaultSettingsConsistency() {
		yield 'YAML' => [ new FileSource( MW_INSTALL_PATH . '/docs/config-schema.yaml' ) ];
		yield 'PHP' => [ new PhpSettingsSource( MW_INSTALL_PATH . '/includes/config-schema.php' ) ];
	}

	/**
	 * Check that the result of loading config-schema.yaml is the same as DefaultSettings.php
	 * This test can be removed when DefaultSettings.php is removed.
	 * @dataProvider provideDefaultSettingsConsistency
	 */
	public function testDefaultSettingsConsistency( SettingsSource $source ) {
		$this->expectDeprecationAndContinue( '/DefaultSettings\\.php/' );
		$defaultSettingsProps = ( static function () {
			require MW_INSTALL_PATH . '/includes/DefaultSettings.php';
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
		$defaults = iterator_to_array( $settingsBuilder->getDefaultConfig() );

		foreach ( $defaultSettingsProps as $key => $value ) {
			if ( in_array( $key, [
				'Version', // deprecated alias to MW_VERSION
				'Conf', // instance of SiteConfiguration
				'AutoloadClasses', // conditionally initialized
			] ) ) {
				continue;
			}
			$this->assertArrayHasKey( $key, $defaults, "Missing $key from $source" );
			$this->assertEquals( $value, $defaults[ $key ], "Wrong value for $key\n" );
		}

		$missingKeys = array_diff_key( $defaults, $defaultSettingsProps );
		$this->assertSame( [], $missingKeys, 'Keys missing from DefaultSettings.php' );
	}

	public static function provideArraysHaveMergeStrategy() {
		[ 'config-schema' => $allSchemas ] = self::getSchemaData();

		foreach ( $allSchemas as $name => $schema ) {
			yield "Schema for $name" => [ $schema ];
		}
	}

	/**
	 * Check that the schema for each config variable contains all necessary information.
	 * @dataProvider provideArraysHaveMergeStrategy
	 */
	public function testSchemaCompleteness( $schema ) {
		$type = $schema['type'] ?? null;
		$type = (array)$type;

		$this->assertArrayNotHasKey( 'obsolete', $schema, 'Obsolete schemas should have been filtered out' );

		if ( isset( $schema['properties'] ) ) {
			$this->assertContains(
				'object', $type,
				'must be of type "object", since it defines properties'
			);

			$defaults = $schema['default'] ?? [];
			foreach ( $schema['properties'] as $key => $sch ) {
				// must have a default in the schema, or in the top level default
				if ( !array_key_exists( 'default', $sch ) ) {
					$this->assertArrayHasKey( $key, $defaults, "property $key must have a default" );
				} else {
					$defaults[$key] = $sch['default'];
				}
			}
		} else {
			$this->assertArrayHasKey(
				'default',
				$schema,
				'should specify a default value'
			);
			$defaults = $schema['default'];
		}

		// If the default is an array, the type must be declared, so we know whether
		// it's a list (JS "array") or a map (JS "object").
		if ( is_array( $defaults ) ) {
			$this->assertTrue(
				in_array( 'array', $type ) || in_array( 'object', $type ),
				'must be of type "array" or "object", since the default is an array'
			);
		}

		// If the default value of a list is not empty, check that it is an indexed array,
		// not an associative array.
		if ( in_array( 'array', $type ) && !empty( $defaults ) ) {
			$this->assertArrayHasKey(
				0,
				$schema['default'],
				'should have a default value starting with index 0, since its type is "array".'
			);
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

		if ( isset( $schema['items'] ) ) {
			$this->assertContains(
				'array',
				$type,
				'should be declared to be an array if an "items" schema is defined'
			);
		}

		if ( isset( $schema['additionalProperties'] ) || isset( $schema['properties'] ) ) {
			$this->assertContains(
				'object',
				$type,
				'should be declared to be an object if schemas are defined for "properties" ' .
					'or "additionalProperties"'
			);
		}
	}

	public static function provideConfigStructureHandling() {
		yield 'NamespacesWithSubpages' => [
			MainConfigNames::NamespacesWithSubpages,
			[ 0 => true, 1 => false,
				2 => true, 3 => true, 4 => true, 5 => true, 7 => true,
				8 => true, 9 => true, 10 => true, 11 => true, 12 => true,
				13 => true, 15 => true
			],
			[ 0 => true, 1 => false ]
		];
		yield 'InterwikiCache array' => [
			MainConfigNames::InterwikiCache,
			[ 'x' => [ 'foo' => 1 ] ],
			[ 'x' => [ 'foo' => 1 ] ],
		];
		yield 'InterwikiCache string' => [
			MainConfigNames::InterwikiCache,
			'interwiki.map',
			'interwiki.map',
		];
		yield 'InterwikiCache string over array' => [
			MainConfigNames::InterwikiCache,
			'interwiki.map',
			[ 'x' => [ 'foo' => 1 ] ],
			'interwiki.map',
		];
		yield 'ProxyList array' => [
			MainConfigNames::ProxyList,
			[ 'a', 'b', 'c' ],
			[ 'a', 'b', 'c' ],
		];
		yield 'ProxyList string' => [
			MainConfigNames::ProxyList,
			'interwiki.map',
			'interwiki.map',
		];
		yield 'ProxyList string over array' => [
			MainConfigNames::ProxyList,
			'interwiki.map',
			[ 'a', 'b', 'c' ],
			'interwiki.map',
		];
		yield 'ProxyList array over array' => [
			MainConfigNames::ProxyList,
			[ 'a', 'b', 'c', 'd' ],
			[ 'a', 'b' ],
			[ 'c', 'd' ],
		];
		yield 'Logos' => [
			MainConfigNames::Logos,
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
		];
		yield 'Logos clear' => [
			MainConfigNames::Logos,
			false,
			[ '1x' => 'Logo1', '2x' => 'Logo2' ],
			false
		];
		yield 'RevokePermissions' => [
			MainConfigNames::RevokePermissions,
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

	public static function provideConfigStructurePartialReplacement() {
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
		$settingsBuilder->putConfigValue( MainConfigNames::Hooks, $hooks );

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
		$settingsBuilder->putConfigValue( MainConfigNames::Hooks, $hooks );

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
		$this->assertSame( $hooks, $config->get( MainConfigNames::Hooks ) );
	}

	/**
	 * Ensure that PasswordPolicy are merged correctly.
	 */
	public function testPasswordPolicyMerge() {
		$settingsBuilder = $this->getSettingsBuilderWithSchema();
		$defaultPolicies = $settingsBuilder->getConfig()->get( MainConfigNames::PasswordPolicy );

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
		$settingsBuilder->putConfigValue( MainConfigNames::PasswordPolicy, $newPolicies );
		$mergedPolicies = $settingsBuilder->getConfig()->get( MainConfigNames::PasswordPolicy );

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

	/**
	 * @covers \MediaWiki\MainConfigSchema
	 */
	public function testMainConfigSchemaDefaults() {
		$defaults = iterator_to_array( MainConfigSchema::listDefaultValues() );
		$prefixed = iterator_to_array( MainConfigSchema::listDefaultValues( 'wg' ) );

		$schema = self::getSchemaData();
		foreach ( $schema['config-schema'] as $name => $sch ) {
			$this->assertArrayHasKey( $name, $defaults );
			$this->assertArrayHasKey( "wg$name", $prefixed );

			$expected = self::getDefaultFromJsonSchema( $sch );

			$this->assertSame( $expected, $defaults[$name] );
			$this->assertSame( $expected, $prefixed["wg$name"] );

			$this->assertSame( $expected, MainConfigSchema::getDefaultValue( $name ) );
		}
	}

	/**
	 * @coversNothing Only covers code in global scope, no way to annotate that?
	 */
	public function testSetLocaltimezone(): void {
		// Make sure the configured timezone ewas applied to the PHP runtime.
		$tz = $this->getConfVar( MainConfigNames::Localtimezone );
		$this->assertSame( $tz, date_default_timezone_get() );
	}
}
