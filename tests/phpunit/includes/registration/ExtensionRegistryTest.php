<?php

use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ExtensionRegistry
 */
class ExtensionRegistryTest extends MediaWikiIntegrationTestCase {

	private $dataDir;

	protected function setUp() : void {
		parent::setUp();
		$this->dataDir = __DIR__ . '/../../data/registration';
	}

	public function testQueue_invalid() {
		$registry = new ExtensionRegistry();
		$path = __DIR__ . '/doesnotexist.json';
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( "file $path" );
		$registry->queue( $path );
	}

	public function testQueue() {
		$registry = new ExtensionRegistry();
		$path = "{$this->dataDir}/good.json";
		$registry->queue( $path );
		$this->assertArrayHasKey(
			$path,
			$registry->getQueue()
		);
		$registry->clearQueue();
		$this->assertSame( [], $registry->getQueue() );
	}

	public function testLoadFromQueue_empty() {
		$registry = new ExtensionRegistry();
		$registry->loadFromQueue();
		$this->assertSame( [], $registry->getAllThings() );
	}

	public function testLoadFromQueue_late() {
		$registry = new ExtensionRegistry();
		$registry->finish();
		$registry->queue( "{$this->dataDir}/good.json" );
		$this->expectException( MWException::class );
		$this->expectExceptionMessage(
			"The following paths tried to load late: {$this->dataDir}/good.json" );
		$registry->loadFromQueue();
	}

	public function testLoadFromQueue() {
		$registry = new ExtensionRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		$this->assertArrayHasKey( 'FooBar', $registry->getAllThings() );
		$this->assertTrue( $registry->isLoaded( 'FooBar' ) );
		$this->assertTrue( $registry->isLoaded( 'FooBar', '*' ) );
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
		$this->assertSame( [], $registry->getAttribute( 'NotLoadedAttr' ) );
	}

	public function testLoadFromQueueWithConstraintWithVersion() {
		$registry = new ExtensionRegistry();
		$registry->queue( "{$this->dataDir}/good_with_version.json" );
		$registry->loadFromQueue();
		$this->assertTrue( $registry->isLoaded( 'FooBar', '>= 1.2.0' ) );
		$this->assertFalse( $registry->isLoaded( 'FooBar', '^1.3.0' ) );
	}

	public function testLoadFromQueueWithConstraintWithoutVersion() {
		$registry = new ExtensionRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		$this->expectException( LogicException::class );
		$registry->isLoaded( 'FooBar', '>= 1.2.0' );
	}

	public function testReadFromQueue_nonexistent() {
		$registry = new ExtensionRegistry();
		$this->expectException( PHPUnit\Framework\Error\Error::class );
		$registry->readFromQueue( [
			__DIR__ . '/doesnotexist.json' => 1
		] );
	}

	public function testReadFromQueueInitializeAutoloaderWithPsr4Namespaces() {
		$registry = new ExtensionRegistry();
		$registry->readFromQueue( [
			"{$this->dataDir}/autoload_namespaces.json" => 1
		] );
		$this->assertTrue(
			class_exists( 'Test\\MediaWiki\\AutoLoader\\TestFooBar' ),
			"Registry initializes Autoloader from AutoloadNamespaces"
		);
	}

	public function testExportExtractedDataNamespaceAlreadyDefined() {
		define( 'FOO_VALUE', 123 ); // Emulates overriding a namespace set in LocalSettings.php
		$registry = new ExtensionRegistry();
		$info = [ 'defines' => [ 'FOO_VALUE' => 456 ], 'globals' => [] ];
		$this->expectException( Exception::class );
		$this->expectExceptionMessage(
			"FOO_VALUE cannot be re-defined with 456 it has already been set with 123"
		);
		TestingAccessWrapper::newFromObject( $registry )->exportExtractedData( $info );
	}

	/**
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
		TestingAccessWrapper::newFromObject( $registry )->exportExtractedData( $info );
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
			[
				'test array_replace_recursive',
				[
					'mwtestJsonConfigs' => [
						'JsonZeroConfig' => [
							'namespace' => 480,
							'nsName' => 'Zero',
							'isLocal' => false,
							'remote' => [
								'username' => 'foo',
							],
						],
					],
				],
				[
					'mwtestJsonConfigs' => [
						'JsonZeroConfig' => [
							'isLocal' => true,
						],
						ExtensionRegistry::MERGE_STRATEGY => 'array_replace_recursive',
					],
				],
				[
					'mwtestJsonConfigs' => [
						'JsonZeroConfig' => [
							'namespace' => 480,
							'nsName' => 'Zero',
							'isLocal' => false,
							'remote' => [
								'username' => 'foo',
							],
						],
					],
				],
			],
			[
				'global is null before',
				[
					'NullGlobal' => null,
				],
				[
					'NullGlobal' => 'not-null'
				],
				[
					'NullGlobal' => null
				],
			],
			[
				'provide_default passive case',
				[
					'wgFlatArray' => [],
				],
				[
					'wgFlatArray' => [
						1,
						ExtensionRegistry::MERGE_STRATEGY => 'provide_default'
					],
				],
				[
					'wgFlatArray' => []
				],
			],
			[
				'provide_default active case',
				[
				],
				[
					'wgFlatArray' => [
						1,
						ExtensionRegistry::MERGE_STRATEGY => 'provide_default'
					],
				],
				[
					'wgFlatArray' => [ 1 ]
				],
			]
		];
	}

	public function testSetAttributeForTest() {
		$registry = new ExtensionRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		// Sanity check that it worked
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
		$reset = $registry->setAttributeForTest( 'FooBarAttr', [ 'override' ] );
		// overridden properly
		$this->assertSame( [ 'override' ], $registry->getAttribute( 'FooBarAttr' ) );
		ScopedCallback::consume( $reset );
		// reset properly
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
	}

	public function testSetAttributeForTestDuplicate() {
		$registry = new ExtensionRegistry();
		$reset1 = $registry->setAttributeForTest( 'foo', [ 'val1' ] );
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( "The attribute 'foo' has already been overridden" );
		$reset2 = $registry->setAttributeForTest( 'foo', [ 'val2' ] );
	}

	public function testGetLazyLoadedAttribute() {
		$registry = TestingAccessWrapper::newFromObject(
			new ExtensionRegistry()
		);
		// Verify the registry is absolutely empty
		$this->assertSame( [], $registry->getLazyLoadedAttribute( 'FooBarBaz' ) );
		// Indicate what paths should be checked for the lazy attributes
		$registry->loaded = [
			'FooBar' => [
				'path' => "{$this->dataDir}/attribute.json",
			]
		];
		// Set in attribute.json
		$this->assertEquals(
			[ 'buzz' => true ],
			$registry->getLazyLoadedAttribute( 'FooBarBaz' )
		);
		// Still return an array if nothing was set
		$this->assertSame(
			[],
			$registry->getLazyLoadedAttribute( 'NotSetAtAll' )
		);

		// Test test overrides
		$reset = $registry->setAttributeForTest( 'FooBarBaz',
			[ 'lightyear' => true ] );
		$this->assertEquals(
			[ 'lightyear' => true ],
			$registry->getLazyLoadedAttribute( 'FooBarBaz' )
		);
	}
}
