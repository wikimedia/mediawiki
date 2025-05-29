<?php

namespace MediaWiki\Tests\Registration;

use AutoLoader;
use Generator;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWikiIntegrationTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Registration\ExtensionRegistry
 */
class ExtensionRegistrationTest extends MediaWikiIntegrationTestCase {

	/** @var array */
	private $autoloaderState;

	/** @var ?ExtensionRegistry */
	private $originalExtensionRegistry = null;

	protected function setUp(): void {
		// phpcs:disable MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgHooks
		global $wgHooks;

		parent::setUp();

		$this->autoloaderState = AutoLoader::getState();

		// Make sure to restore globals
		$this->stashMwGlobals( [
			'wgHooks',
			'wgAutoloadClasses',
			'wgNamespaceProtection',
			'wgNamespaceModels',
			'wgAvailableRights',
			'wgAuthManagerAutoConfig',
			'wgGroupPermissions',
			'wgAddGroups',
			'wgRemoveGroups',
		] );

		// For the purpose of this test, make $wgHooks behave like a real global config array.
		$wgHooks = [];
	}

	protected function tearDown(): void {
		AutoLoader::restoreState( $this->autoloaderState );

		if ( $this->originalExtensionRegistry ) {
			$this->setExtensionRegistry( $this->originalExtensionRegistry );
		}

		parent::tearDown();
	}

	public function testExportNamespaces() {
		$manifest = [
			'namespaces' => [
				[
					'id' => 1300,
					'name' => 'ExtensionRegistrationTest',
					'constant' => 'NS_EXTENSION_REGISTRATION_TEST',
					'defaultcontentmodel' => 'Foo',
					'protection' => [ 'sysop' ],
				]
			]
		];

		$file = $this->makeManifestFile( $manifest );

		$registry = new ExtensionRegistry();
		$registry->queue( $file );
		$registry->loadFromQueue();

		$this->assertTrue( defined( 'NS_EXTENSION_REGISTRATION_TEST' ) );
		$this->assertSame( 1300, constant( 'NS_EXTENSION_REGISTRATION_TEST' ) );

		$expectedNamespaceNames = [ 1300 => 'ExtensionRegistrationTest' ];
		$this->assertSame( $expectedNamespaceNames, $registry->getAttribute( 'ExtensionNamespaces' ) );

		$this->assertArrayHasKey( 1300, $GLOBALS['wgNamespaceProtection'] );
		$this->assertArrayHasKey( 1300, $GLOBALS['wgNamespaceContentModels'] );
	}

	private function setExtensionRegistry( ExtensionRegistry $registry ) {
		$staticExtReg = TestingAccessWrapper::newFromClass( ExtensionRegistry::class );

		if ( !$this->originalExtensionRegistry ) {
			$this->originalExtensionRegistry = $staticExtReg->instance;
		}

		$staticExtReg->instance = $registry;
	}

	public function testExportHooks() {
		$manifest = [
			'Hooks' => [
				'AnEvent' => ExtensionRegistrationHookHandler::class . '::onAnEvent',
				'BooEvent' => 'main',
			],
			'HookHandlers' => [
				'main' => [ 'class' => ExtensionRegistrationHookHandler::class ]
			],
		];

		$file = $this->makeManifestFile( $manifest );

		$registry = new ExtensionRegistry();
		$this->setExtensionRegistry( $registry );

		$registry->queue( $file );
		$registry->loadFromQueue();

		$this->resetServices();
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$this->assertTrue( $hookContainer->isRegistered( 'AnEvent' ), 'AnEvent' );
		$this->assertTrue( $hookContainer->isRegistered( 'BooEvent' ), 'BooEvent' );
	}

	public function testRegisterDomainEventListeners() {
		$subscriber = [
			'events' => [ 'AnEvent', 'BooEvent' ],
		];

		$manifest = [
			'DomainEventIngresses' => [ $subscriber ]
		];

		$file = $this->makeManifestFile( $manifest );

		$registry = new ExtensionRegistry();
		$this->setExtensionRegistry( $registry );

		$registry->queue( $file );
		$registry->loadFromQueue();

		$actualSubscribers = [];
		$mockSource = $this->createMock( DomainEventSource::class );
		$mockSource->method( 'registerSubscriber' )->willReturnCallback(
			static function ( $subscriber ) use ( &$actualSubscribers ) {
				$actualSubscribers[] = $subscriber;
			}
		);
		$registry->registerListeners( $mockSource );

		$expectedSubscribers = [ $subscriber + [ 'extensionPath' => $file ] ];

		$this->assertArrayEquals(
			$expectedSubscribers,
			$actualSubscribers
		);
	}

	public function testExportAutoload() {
		global $wgAutoloadClasses;
		$oldAutoloadClasses = $wgAutoloadClasses;

		$manifest = [
			'AutoloadClasses' => [
				'TestAutoloaderClass' =>
					__DIR__ . '/../../data/autoloader/TestAutoloadedClass.php',
			],
			'AutoloadNamespaces' => [
				'Dummy\Test\Namespace\\' =>
					__DIR__ . '/../../data/autoloader/psr4/',
			],
			'HookHandler' => [
				'main' => [ 'class' => 'Whatever' ]
			],
		];

		$file = $this->makeManifestFile( $manifest );

		$registry = new ExtensionRegistry();
		$registry->setCache( new HashBagOStuff() );

		$registry->queue( $file );
		$registry->loadFromQueue();

		$this->assertArrayHasKey( 'TestAutoloaderClass', AutoLoader::getClassFiles() );
		$this->assertArrayHasKey( 'Dummy\Test\Namespace\\', AutoLoader::getNamespaceDirectories() );

		// Now, reset and do it again, but with the cached extension info.
		// This is needed because autoloader registration is currently handled
		// differently when loading from the cache (T240535).
		AutoLoader::restoreState( $this->autoloaderState );
		$wgAutoloadClasses = $oldAutoloadClasses;

		$registry->queue( $file );
		$registry->loadFromQueue();

		$this->assertArrayHasKey( 'TestAutoloaderClass', AutoLoader::getClassFiles() );
		$this->assertArrayHasKey( 'Dummy\Test\Namespace\\', AutoLoader::getNamespaceDirectories() );
	}

	/**
	 * @dataProvider provideExportConfigToGlobals
	 * @dataProvider provideExportAttributesToGlobals
	 */
	public function testExportGlobals( $before, $manifest, $expected ) {
		$this->setMwGlobals( $before );

		$file = $this->makeManifestFile( $manifest );

		$registry = new ExtensionRegistry();
		$registry->queue( $file );
		$registry->loadFromQueue();

		foreach ( $expected as $name => $expectedValue ) {
			$this->assertArrayHasKey( $name, $GLOBALS );
			$this->assertEquals( $expectedValue, $GLOBALS[$name] );
		}
	}

	private function newSettingsBuilder(): SettingsBuilder {
		$settings = new SettingsBuilder(
			__DIR__,
			$this->createMock( ExtensionRegistry::class ),
			new ArrayConfigBuilder(),
			$this->createMock( PhpIniSink::class ),
			null
		);

		return $settings;
	}

	public static function callbackForTest( array $ext, SettingsBuilder $settings ) {
		$settings->overrideConfigValue( 'RunCallbacksTest', 'foo' );
		self::assertSame( 'CallbackTest', $ext['name'] );
	}

	public function testRunCallbacks() {
		$manifest = [
			'name' => 'CallbackTest',
			'callback' => [ __CLASS__, 'callbackForTest' ],
		];

		$file = $this->makeManifestFile( $manifest );

		$settings = $this->newSettingsBuilder();

		$registry = new ExtensionRegistry();
		$registry->setSettingsBuilder( $settings );

		$settings->enterRegistrationStage();
		$registry->queue( $file );
		$registry->loadFromQueue();

		$this->assertSame( 'foo', $settings->getConfig()->get( 'RunCallbacksTest' ) );
	}

	/**
	 * Provides defaults coming from extension, global values from custom settings.
	 * The global value should be merged on top of the default from the extension (backwards merge).
	 *
	 * @return Generator
	 */
	public static function provideExportConfigToGlobals() {
		yield 'Simple non-array values' => [
			[
				'mwtestFooBarConfig' => true,
				'mwtestFooBarConfig2' => 'string',
			],
			[
				'config_prefix' => 'mwtest',
				'config' => [
					'FooBarDefault' => [ 'value' => 1234 ],
					'FooBarConfig' => [ 'value' => false ],
				]
			],
			[
				'mwtestFooBarConfig' => true,
				'mwtestFooBarConfig2' => 'string',
				'mwtestFooBarDefault' => 1234,
			],
		];

		yield 'No global already set, simple assoc array' => [
			[],
			[
				'config_prefix' => 'mwtest',
				'config' => [
					'DefaultOptions' => [
						'value' => [
							'foobar' => true,
						]
					]
				]
			],
			[
				'mwtestDefaultOptions' => [
					'foobar' => true,
				]
			],
		];

		yield 'No global already set, simple assoc array, manifest version 1' => [
			[],
			[
				'manifest_version' => 1,
				'config' => [
					'_prefix' => 'mwtest',
					'SomeMap' => [
						'foobar' => true,
					]
				]
			],
			[
				'mwtestSomeMap' => [
					'foobar' => true,
				]
			],
		];

		yield 'Global already set, simple assoc array, manifest version 1' => [
			[
				'mwtestSomeMap' => [
					'foobar' => true,
					'foo' => 'string'
				],
			],
			[
				'manifest_version' => 1,
				'config' => [
					'_prefix' => 'mwtest',
					'SomeMap' => [
						'barbaz' => 12345,
						'foobar' => false,
					]
				]
			],
			[
				'mwtestSomeMap' => [
					'barbaz' => 12345,
					'foo' => 'string',
					'foobar' => true,
				],
			]
		];

		yield 'Global already set, simple list array' => [
			[
				'mwtestList' => [ 'x', 'y', 'z' ],
			],
			[
				'manifest_version' => 1,
				'config' => [
					'_prefix' => 'mwtest',
					'List' => [ 'a', 'b' ]
				]
			],
			[
				'mwtestList' => [ 'a', 'b', 'x', 'y', 'z' ],
			]
		];

		yield 'New variable, explicit merge strategy' => [
			[
				'wgNamespacesFoo' => [
					100 => true,
					102 => false
				],
			],
			[
				'config' => [
					'NamespacesFoo' => [
						'value' => [
							100 => false,
							500 => true,
						],
						'merge_strategy' => 'array_plus',
					],
				]
			],
			[
				'wgNamespacesFoo' => [
					100 => true,
					102 => false,
					500 => true,
				],
			]
		];

		yield 'New variable, explicit merge strategy, manifest version 1' => [
			[
				'wgNamespacesFoo' => [
					100 => true,
					102 => false
				],
			],
			[
				'manifest_version' => 1,
				'config' => [
					'NamespacesFoo' => [
						100 => false,
						500 => true,
						ExtensionRegistry::MERGE_STRATEGY => 'array_plus',
					],
				]
			],
			[
				'wgNamespacesFoo' => [
					100 => true,
					102 => false,
					500 => true,
				],
			]
		];

		yield 'False local setting should not be overridden by default (T100767)' => [
			[
				'wgT100767' => false,
			],
			[
				'config' => [
					'T100767' => [ 'value' => true ],
				]
			],
			[
				'wgT100767' => false,
			],
		];

		yield 'test array_replace_recursive' => [
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
				'config_prefix' => 'mwtest',
				'config' => [
					'JsonConfigs' => [
						'value' => [
							'JsonZeroConfig' => [
								'isLocal' => true,
							],
						],
						'merge_strategy' => 'array_replace_recursive',
					],
				]
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
		];

		yield 'Default doesn\'t override null' => [
			[
				'wgNullGlobal' => null,
			],
			[
				'config' => [
					'NullGlobal' => [ 'value' => 'not-null' ]
				]
			],
			[
				'wgNullGlobal' => null
			],
		];

		yield 'provide_default passive case' => [
			[
				'wgFlatArray' => [],
			],
			[
				'config' => [
					'FlatArray' => [
						'value' => [ 1 ],
						'merge_strategy' => 'provide_default'
					],
				]
			],
			[
				'wgFlatArray' => []
			],
		];

		yield 'provide_default active case' => [
			[],
			[
				'config' => [
					'FlatArray' => [
						'value' => [ 1 ],
						'merge_strategy' => 'provide_default'
					],
				]
			],
			[
				'wgFlatArray' => [ 1 ]
			],
		];
	}

	/**
	 * Provide global values as default coming from core, new value from extension attribute.
	 * The value coming from the extension should be merged on top of the global.
	 *
	 * @return Generator
	 */
	public static function provideExportAttributesToGlobals() {
		yield 'AvailableRights appends to default value, per config schema' => [
			[
				'wgAvailableRights' => [
					'aaa',
					'bbb'
				],
			],
			[ 'AvailableRights' => [ 'ccc', ] ],
			[
				// NOTE: This is backwards! Fortunately, the order in AvailableRights
				//       is not significant.
				'wgAvailableRights' => [
					'ccc',
					'aaa',
					'bbb',
				],
			]
		];

		yield 'AuthManagerAutoConfig appends to default value, per top level key' => [
			[
				'wgAuthManagerAutoConfig' => [
					'preauth' => [ 'default' => 'DefaultPreAuth' ],
					'primaryauth' => [ 'default' => 'DefaultPrimaryAuth' ],
					'secondaryauth' => [ 'default' => 'DefaultSecondaryAuth' ],
				],
			],
			[
				'AuthManagerAutoConfig' => [
					'primaryauth' => [ 'my' => 'MyPrimaryAuth' ],
				],
			],
			[
				'wgAuthManagerAutoConfig' => [
					'preauth' => [ 'default' => 'DefaultPreAuth' ],
					'primaryauth' => [ 'default' => 'DefaultPrimaryAuth', 'my' => 'MyPrimaryAuth' ],
					'secondaryauth' => [ 'default' => 'DefaultSecondaryAuth' ],
				],
			]
		];

		yield 'Global already set, $wgGroupPermissions' => [
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
				'GroupPermissions' => [
					'customgroup' => [
						'right' => true,
					],
					'user' => [
						'right' => true,
						'somethingtwo' => false,
						'nonduplicated' => true,
					],
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
						// NOTE: somethingtwo should be false here, since the value from
						//       the extension should override the core default!
						//       See e.g. https://www.mediawiki.org/wiki/Topic:W2ttbedo3apzno4w
						//       and https://phabricator.wikimedia.org/T98347#2589540.
						'somethingtwo' => true,
						'right' => true,
						'nonduplicated' => true,
					]
				],
			],
		];
		yield '$wgAddGroups and $wgRemoveGroups are merged correctly' => [
			[
				'wgAddGroups' => [
					'key1' => [
						'value1',
					],
					'key2' => [
						'value2',
					],
				],
				'wgRemoveGroups' => [
					'key1' => [
						'value1',
					],
					'key2' => [
						'value2',
					],
				],
			],
			[
				'AddGroups' => [
					'key1' => [
						'value1b',
					],
					'key3' => [
						'value3',
					],
				],
				'RemoveGroups' => [
					'key1' => [
						'value1b',
					],
					'key3' => [
						'value3',
					],
				],
			],
			[
				'wgAddGroups' => [
					'key1' => [
						'value1',
						'value1b',
					],
					'key2' => [
						'value2',
					],
					'key3' => [
						'value3',
					],
				],
				'wgRemoveGroups' => [
					'key1' => [
						'value1',
						'value1b',
					],
					'key2' => [
						'value2',
					],
					'key3' => [
						'value3',
					],
				],
			],
		];
	}

	private function makeManifestFile( array $manifest ): string {
		$manifest += [
			'name' => 'Test',
			'manifest_version' => 2,
			'config' => [],
			'callbacks' => [],
			'defines' => [],
			'credits' => [],
			'attributes' => [],
			'autoloaderPaths' => []
		];

		$file = $this->getNewTempFile();
		file_put_contents( $file, json_encode( $manifest ) );
		return $file;
	}

	public function testExportAutoloaderWithPsr4Namespaces() {
		$dir = __DIR__ . '/../../data/registration';
		$registry = new ExtensionRegistry();
		$data = $registry->readFromQueue( [
			"{$dir}/autoload_namespaces.json" => 1
		] );

		$access = TestingAccessWrapper::newFromObject( $registry );
		$access->exportExtractedData( $data );

		$this->assertTrue(
			class_exists( 'Test\\MediaWiki\\AutoLoader\\TestFooBar' ),
			"Registry initializes Autoloader from AutoloadNamespaces"
		);
	}

}

class ExtensionRegistrationHookHandler {

	public static function onAnEvent() {
		// no-op
	}

	public static function onBooEvent() {
		// no-op
	}

}
