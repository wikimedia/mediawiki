<?php

namespace MediaWiki\Tests\Registration;

use Exception;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\SettingsBuilder;
use MediaWikiUnitTestCase;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Registration\ExtensionRegistry
 */
class ExtensionRegistryTest extends MediaWikiUnitTestCase {

	private $dataDir = __DIR__ . '/../../../data/registration';

	private $restoreGlobals = [];

	private $unsetGlobals = [];

	protected function tearDown(): void {
		foreach ( $this->restoreGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}

		foreach ( $this->unsetGlobals as $var ) {
			unset( $GLOBALS[$var] );
		}

		parent::tearDown();
	}

	private function getRegistry(): ExtensionRegistry {
		$registry = new ExtensionRegistry();
		// Mock the global SettingsBuilder dependencies, as this is a unit test. And because SettingsBuilder
		// has a reverse dependency on the global ExtensionRegistry instance, it would throw an exception
		// because access to the global ExtensionRegistry instance is forbidden in unit tests.
		$registry->setSettingsBuilder( $this->createMock( SettingsBuilder::class ) );
		return $registry;
	}

	private function setGlobal( $key, $value ) {
		if ( isset( $GLOBALS[$key] ) ) {
			$this->restoreGlobals[$key] = $GLOBALS[$key];
		} else {
			$this->unsetGlobals[] = $key;
		}

		$GLOBALS[$key] = $value;
	}

	public function testQueue_invalid() {
		$this->setGlobal( 'wgExtensionInfoMTime', false );

		$registry = $this->getRegistry();
		$path = __DIR__ . '/doesnotexist.json';
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( "file $path" );
		$registry->queue( $path );
	}

	public function testQueue() {
		$registry = $this->getRegistry();
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
		$registry = $this->getRegistry();
		$registry->loadFromQueue();
		$this->assertSame( [], $registry->getAllThings() );
	}

	public function testLoadFromQueue_late() {
		$registry = $this->getRegistry();
		$registry->finish();
		$registry->queue( "{$this->dataDir}/good.json" );
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage(
			"The following paths tried to load late: {$this->dataDir}/good.json" );
		$registry->loadFromQueue();
	}

	public function testLoadFromQueue() {
		$registry = $this->getRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		$this->assertArrayHasKey( 'FooBar', $registry->getAllThings() );
		$this->assertTrue( $registry->isLoaded( 'FooBar' ) );
		$this->assertTrue( $registry->isLoaded( 'FooBar', '*' ) );
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
		$this->assertSame( [], $registry->getAttribute( 'NotLoadedAttr' ) );
	}

	public function testLoadFromQueueWithConstraintWithVersion() {
		$registry = $this->getRegistry();
		$registry->queue( "{$this->dataDir}/good_with_version.json" );
		$registry->loadFromQueue();
		$this->assertTrue( $registry->isLoaded( 'FooBar', '>= 1.2.0' ) );
		$this->assertFalse( $registry->isLoaded( 'FooBar', '^1.3.0' ) );
	}

	public function testLoadFromQueueWithConstraintWithoutVersion() {
		$registry = $this->getRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		$this->expectException( LogicException::class );
		$registry->isLoaded( 'FooBar', '>= 1.2.0' );
	}

	public function testReadFromQueue_nonexistent() {
		$registry = $this->getRegistry();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unable to read' );
		$this->expectPHPError(
			E_WARNING,
			static function () use ( $registry ) {
				$registry->readFromQueue( [
					__DIR__ . '/doesnotexist.json' => 1
				] );
			}
		);
	}

	public function testExportExtractedDataNamespaceAlreadyDefined() {
		define( 'FOO_VALUE', 123 ); // Emulates overriding a namespace set in LocalSettings.php
		$registry = $this->getRegistry();
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
				if ( str_starts_with( $key, 'mw' ) ) {
					$GLOBALS[$key] = $value;
				} else {
					$this->setGlobal( $key, $value );
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
		$registry = $this->getRegistry();
		TestingAccessWrapper::newFromObject( $registry )->exportExtractedData( $info );
		$result = array_intersect_key( $GLOBALS, $expected );
		$this->assertEquals( $expected, $result, $desc );

		// Remove mw prefixed globals
		if ( $before ) {
			foreach ( $before as $key => $value ) {
				if ( str_starts_with( $key, 'mw' ) ) {
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
			],
			[
				'a configured value should not turn into a default null value',
				[
					'ArrayValue' => [],
					'BooleanValue' => false,
					'IntegerValue' => 0,
					'StringValue' => '',
				],
				[
					'ArrayValue' => null,
					'BooleanValue' => null,
					'IntegerValue' => null,
					'StringValue' => null,
				],
				[
					'ArrayValue' => [],
					'BooleanValue' => false,
					'IntegerValue' => 0,
					'StringValue' => '',
				],
			],
			[
				'a configured value should not turn into a default empty array value',
				[
					'BooleanValue' => false,
					'IntegerValue' => 0,
					'NullValue' => null,
					'StringValue' => '',
				],
				[
					'BooleanValue' => [],
					'IntegerValue' => [],
					'NullValue' => [],
					'StringValue' => [],
				],
				[
					'BooleanValue' => false,
					'IntegerValue' => 0,
					'NullValue' => null,
					'StringValue' => '',
				],
			],
		];
	}

	public function testSetAttributeForTest() {
		$registry = $this->getRegistry();
		$registry->queue( "{$this->dataDir}/good.json" );
		$registry->loadFromQueue();
		// Check that it worked
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
		$reset = $registry->setAttributeForTest( 'FooBarAttr', [ 'override' ] );
		// overridden properly
		$this->assertSame( [ 'override' ], $registry->getAttribute( 'FooBarAttr' ) );
		ScopedCallback::consume( $reset );
		// reset properly
		$this->assertSame( [ 'test' ], $registry->getAttribute( 'FooBarAttr' ) );
	}

	public function testSetAttributeForTestDuplicate() {
		$registry = $this->getRegistry();
		$reset1 = $registry->setAttributeForTest( 'foo', [ 'val1' ] );
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( "The attribute 'foo' has already been overridden" );
		$reset2 = $registry->setAttributeForTest( 'foo', [ 'val2' ] );
	}

	public function testGetLazyLoadedAttribute() {
		$registry = TestingAccessWrapper::newFromObject(
			$this->getRegistry()
		);
		// Verify the registry is absolutely empty
		$this->assertSame( [], $registry->getLazyLoadedAttribute( 'FooBarBaz' ) );
		// Indicate what paths should be checked for the lazy attributes
		$registry->queue( "{$this->dataDir}/attribute.json" );
		$registry->loadFromQueue();
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
