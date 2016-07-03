<?php

class ExtensionRegistryTest extends MediaWikiTestCase {

	/**
	 * @covers ExtensionRegistry::exportExtractedData
	 * @dataProvider provideExportExtractedDataGlobals
	 */
	public function testExportExtractedDataGlobals( $desc, $before, $globals, $expected ) {
		// Set globals for test
		if ( $before ) {
			foreach ( $before as $key => $value ) {
				// mw prefixed globals does not exist normally
				if ( substr( $key, 0, 2 ) == 'mw' ) {
					$GLOBALS[$key] = $value;
				} else {
					$this->setMwGlobals( $key, $value );
				}
			}
		}

		$info = [
			'globals' => $globals,
			'callbacks' => [],
			'defines' => [],
			'credits' => [],
			'attributes' => [],
			'autoloaderPaths' => []
		];
		$registry = new ExtensionRegistry();
		$class = new ReflectionClass( 'ExtensionRegistry' );
		$method = $class->getMethod( 'exportExtractedData' );
		$method->setAccessible( true );
		$method->invokeArgs( $registry, [ $info ] );
		foreach ( $expected as $name => $value ) {
			$this->assertArrayHasKey( $name, $GLOBALS, $desc );
			$this->assertEquals( $value, $GLOBALS[$name], $desc );
		}

		// Remove mw prefixed globals
		if ( $before ) {
			foreach ( $before as $key => $value ) {
				if ( substr( $key, 0, 2 ) == 'mw' ) {
					unset( $GLOBALS[$key] );
				}
			}
		}
	}

	public static function provideExportExtractedDataGlobals() {
		// "mwtest" prefix used instead of "$wg" to avoid potential conflicts
		return [
			[
				'Simple non-array values',
				[
					'mwtestFooBarConfig' => true,
					'mwtestFooBarConfig2' => 'string',
				],
				[
					'mwtestFooBarDefault' => 1234,
					'mwtestFooBarConfig' => false,
				],
				[
					'mwtestFooBarConfig' => true,
					'mwtestFooBarConfig2' => 'string',
					'mwtestFooBarDefault' => 1234,
				],
			],
			[
				'No global already set, simple array',
				null,
				[
					'mwtestDefaultOptions' => [
						'foobar' => true,
					]
				],
				[
					'mwtestDefaultOptions' => [
						'foobar' => true,
					]
				],
			],
			[
				'Global already set, simple array',
				[
					'mwtestDefaultOptions' => [
						'foobar' => true,
						'foo' => 'string'
					],
				],
				[
					'mwtestDefaultOptions' => [
						'barbaz' => 12345,
						'foobar' => false,
					],
				],
				[
					'mwtestDefaultOptions' => [
						'barbaz' => 12345,
						'foo' => 'string',
						'foobar' => true,
					],
				]
			],
			[
				'Global already set, 1d array that appends',
				[
					'mwAvailableRights' => [
						'foobar',
						'foo'
					],
				],
				[
					'mwAvailableRights' => [
						'barbaz',
					],
				],
				[
					'mwAvailableRights' => [
						'barbaz',
						'foobar',
						'foo',
					],
				]
			],
			[
				'Global already set, array with integer keys',
				[
					'mwNamespacesFoo' => [
						100 => true,
						102 => false
					],
				],
				[
					'mwNamespacesFoo' => [
						100 => false,
						500 => true,
						ExtensionRegistry::MERGE_STRATEGY => 'array_plus',
					],
				],
				[
					'mwNamespacesFoo' => [
						100 => true,
						102 => false,
						500 => true,
					],
				]
			],
			[
				'No global already set, $wgHooks',
				[
					'wgHooks' => [],
				],
				[
					'wgHooks' => [
						'FooBarEvent' => [
							'FooBarClass::onFooBarEvent'
						],
						ExtensionRegistry::MERGE_STRATEGY => 'array_merge_recursive'
					],
				],
				[
					'wgHooks' => [
						'FooBarEvent' => [
							'FooBarClass::onFooBarEvent'
						],
					],
				],
			],
			[
				'Global already set, $wgHooks',
				[
					'wgHooks' => [
						'FooBarEvent' => [
							'FooBarClass::onFooBarEvent'
						],
						'BazBarEvent' => [
							'FooBarClass::onBazBarEvent',
						],
					],
				],
				[
					'wgHooks' => [
						'FooBarEvent' => [
							'BazBarClass::onFooBarEvent',
						],
						ExtensionRegistry::MERGE_STRATEGY => 'array_merge_recursive',
					],
				],
				[
					'wgHooks' => [
						'FooBarEvent' => [
							'FooBarClass::onFooBarEvent',
							'BazBarClass::onFooBarEvent',
						],
						'BazBarEvent' => [
							'FooBarClass::onBazBarEvent',
						],
					],
				],
			],
			[
				'Global already set, $wgGroupPermissions',
				[
					'wgGroupPermissions' => [
						'sysop' => [
							'something' => true,
						],
						'user' => [
							'somethingtwo' => true,
						]
					],
				],
				[
					'wgGroupPermissions' => [
						'customgroup' => [
							'right' => true,
						],
						'user' => [
							'right' => true,
							'somethingtwo' => false,
							'nonduplicated' => true,
						],
						ExtensionRegistry::MERGE_STRATEGY => 'array_plus_2d',
					],
				],
				[
					'wgGroupPermissions' => [
						'customgroup' => [
							'right' => true,
						],
						'sysop' => [
							'something' => true,
						],
						'user' => [
							'somethingtwo' => true,
							'right' => true,
							'nonduplicated' => true,
						]
					],
				],
			],
			[
				'False local setting should not be overridden (T100767)',
				[
					'mwtestT100767' => false,
				],
				[
					'mwtestT100767' => true,
				],
				[
					'mwtestT100767' => false,
				],
			],
		];
	}
}
