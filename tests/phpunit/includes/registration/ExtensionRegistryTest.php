<?php

class ExtensionRegistryTest extends MediaWikiTestCase {

	/**
	 * @covers ExtensionRegistry::exportExtractedData
	 * @dataProvider provideExportExtractedDataGlobals
	 * @@backupGlobals enabled
	 */
	public function testExportExtractedDataGlobals( $desc, $before, $globals, $expected ) {
		if ( $before ) {
			foreach ( $before as $key => $value ) {
				$GLOBALS[$key] = $value;
			}
		}
		$info = array(
			'globals' => $globals,
			'callbacks' => array(),
			'defines' => array(),
			'credits' => array(),
			'attributes' => array(),
		);
		$registry = new ExtensionRegistry();
		$class = new ReflectionClass( 'ExtensionRegistry' );
		$method = $class->getMethod( 'exportExtractedData' );
		$method->setAccessible( true );
		$method->invokeArgs( $registry, array( $info ) );
		foreach ( $expected as $name => $value ) {
			$this->assertArrayHasKey( $name, $GLOBALS, $desc );
			$this->assertEquals( $value, $GLOBALS[$name], $desc );
		}
	}

	public static function provideExportExtractedDataGlobals() {
		// "mwtest" prefix used instead of "$wg" to avoid potential conflicts
		return array(
			array(
				'Simple non-array values',
				array(
					'mwtestFooBarConfig' => true,
					'mwtestFooBarConfig2' => 'string',
				),
				array(
					'mwtestFooBarDefault' => 1234,
					'mwtestFooBarConfig' => false,
				),
				array(
					'mwtestFooBarConfig' => true,
					'mwtestFooBarConfig2' => 'string',
					'mwtestFooBarDefault' => 1234,
				),
			),
			array(
				'No global already set, simple array',
				null,
				array(
					'mwtestDefaultOptions' => array(
						'foobar' => true,
					)
				),
				array(
					'mwtestDefaultOptions' => array(
						'foobar' => true,
					)
				),
			),
			array(
				'Global already set, simple array',
				array(
					'mwtestDefaultOptions' => array(
						'foobar' => true,
						'foo' => 'string'
					),
				),
				array(
					'mwtestDefaultOptions' => array(
						'barbaz' => 12345,
						'foobar' => false,
					),
				),
				array(
					'mwtestDefaultOptions' => array(
						'barbaz' => 12345,
						'foo' => 'string',
						'foobar' => true,
					),
				)
			),
			array(
				'No global already set, $wgHooks',
				array(
					'wgHooks' => array(),
				),
				array(
					'wgHooks' => array(
						'FooBarEvent' => array(
							'FooBarClass::onFooBarEvent'
						),
					),
				),
				array(
					'wgHooks' => array(
						'FooBarEvent' => array(
							'FooBarClass::onFooBarEvent'
						),
					),
				),
			),
			array(
				'Global already set, $wgHooks',
				array(
					'wgHooks' => array(
						'FooBarEvent' => array(
							'FooBarClass::onFooBarEvent'
						),
						'BazBarEvent' => array(
							'FooBarClass::onBazBarEvent',
						),
					),
				),
				array(
					'wgHooks' => array(
						'FooBarEvent' => array(
							'BazBarClass::onFooBarEvent',
						),
					),
				),
				array(
					'wgHooks' => array(
						'FooBarEvent' => array(
							'FooBarClass::onFooBarEvent',
							'BazBarClass::onFooBarEvent',
						),
						'BazBarEvent' => array(
							'FooBarClass::onBazBarEvent',
						),
					),
				),
			),
			array(
				'Global already set, $wgGroupPermissions',
				array(
					'wgGroupPermissions' => array(
						'sysop' => array(
							'something' => true,
						),
						'user' => array(
							'somethingtwo' => true,
						)
					),
				),
				array(
					'wgGroupPermissions' => array(
						'customgroup' => array(
							'right' => true,
						),
						'user' => array(
							'right' => true,
							'somethingtwo' => false,
						)
					),
				),
				array(
					'wgGroupPermissions' => array(
						'customgroup' => array(
							'right' => true,
						),
						'sysop' => array(
							'something' => true,
						),
						'user' => array(
							'somethingtwo' => true,
							'right' => true,
						)
					),
				),
			)
		);
	}
}
