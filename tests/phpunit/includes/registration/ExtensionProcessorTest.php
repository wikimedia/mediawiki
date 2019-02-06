<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ExtensionProcessor
 */
class ExtensionProcessorTest extends MediaWikiTestCase {

	private $dir, $dirname;

	public function setUp() {
		parent::setUp();
		$this->dir = __DIR__ . '/FooBar/extension.json';
		$this->dirname = dirname( $this->dir );
	}

	/**
	 * 'name' is absolutely required
	 *
	 * @var array
	 */
	public static $default = [
		'name' => 'FooBar',
	];

	public function testExtractInfo() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default + [
			'@metadata' => [ 'foobarbaz' ],
			'AnAttribute' => [ 'omg' ],
			'AutoloadClasses' => [ 'FooBar' => 'includes/FooBar.php' ],
			'SpecialPages' => [ 'Foo' => 'SpecialFoo' ],
			'callback' => 'FooBar::onRegistration',
		], 1 );

		$extracted = $processor->getExtractedInfo();
		$attributes = $extracted['attributes'];
		$this->assertArrayHasKey( 'AnAttribute', $attributes );
		$this->assertArrayNotHasKey( '@metadata', $attributes );
		$this->assertArrayNotHasKey( 'AutoloadClasses', $attributes );
		$this->assertSame(
			[ 'FooBar' => 'FooBar::onRegistration' ],
			$extracted['callbacks']
		);
		$this->assertSame(
			[ 'Foo' => 'SpecialFoo' ],
			$extracted['globals']['wgSpecialPages']
		);
	}

	public function testExtractNamespaces() {
		// Test that namespace IDs can be overwritten
		if ( !defined( 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X' ) ) {
			define( 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X', 123456 );
		}

		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default + [
			'namespaces' => [
				[
					'id' => 332200,
					'constant' => 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_A',
					'name' => 'Test_A',
					'defaultcontentmodel' => 'TestModel',
					'gender' => [
						'male' => 'Male test',
						'female' => 'Female test',
					],
					'subpages' => true,
					'content' => true,
					'protection' => 'userright',
				],
				[ // Test_X will use ID 123456 not 334400
					'id' => 334400,
					'constant' => 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X',
					'name' => 'Test_X',
					'defaultcontentmodel' => 'TestModel'
				],
			]
		], 1 );

		$extracted = $processor->getExtractedInfo();

		$this->assertArrayHasKey(
			'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_A',
			$extracted['defines']
		);
		$this->assertArrayNotHasKey(
			'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X',
			$extracted['defines']
		);

		$this->assertSame(
			$extracted['defines']['MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_A'],
			332200
		);

		$this->assertArrayHasKey( 'ExtensionNamespaces', $extracted['attributes'] );
		$this->assertArrayHasKey( 123456, $extracted['attributes']['ExtensionNamespaces'] );
		$this->assertArrayHasKey( 332200, $extracted['attributes']['ExtensionNamespaces'] );
		$this->assertArrayNotHasKey( 334400, $extracted['attributes']['ExtensionNamespaces'] );

		$this->assertSame( 'Test_X', $extracted['attributes']['ExtensionNamespaces'][123456] );
		$this->assertSame( 'Test_A', $extracted['attributes']['ExtensionNamespaces'][332200] );
		$this->assertSame(
			[ 'male' => 'Male test', 'female' => 'Female test' ],
			$extracted['globals']['wgExtraGenderNamespaces'][332200]
		);
		// A has subpages, X does not
		$this->assertTrue( $extracted['globals']['wgNamespacesWithSubpages'][332200] );
		$this->assertArrayNotHasKey( 123456, $extracted['globals']['wgNamespacesWithSubpages'] );
	}

	public static function provideRegisterHooks() {
		$merge = [ ExtensionRegistry::MERGE_STRATEGY => 'array_merge_recursive' ];
		// Format:
		// Current $wgHooks
		// Content in extension.json
		// Expected value of $wgHooks
		return [
			// No hooks
			[
				[],
				self::$default,
				$merge,
			],
			// No current hooks, adding one for "FooBaz" in string format
			[
				[],
				[ 'Hooks' => [ 'FooBaz' => 'FooBazCallback' ] ] + self::$default,
				[ 'FooBaz' => [ 'FooBazCallback' ] ] + $merge,
			],
			// Hook for "FooBaz", adding another one
			[
				[ 'FooBaz' => [ 'PriorCallback' ] ],
				[ 'Hooks' => [ 'FooBaz' => 'FooBazCallback' ] ] + self::$default,
				[ 'FooBaz' => [ 'PriorCallback', 'FooBazCallback' ] ] + $merge,
			],
			// No current hooks, adding one for "FooBaz" in verbose array format
			[
				[],
				[ 'Hooks' => [ 'FooBaz' => [ 'FooBazCallback' ] ] ] + self::$default,
				[ 'FooBaz' => [ 'FooBazCallback' ] ] + $merge,
			],
			// Hook for "BarBaz", adding one for "FooBaz"
			[
				[ 'BarBaz' => [ 'BarBazCallback' ] ],
				[ 'Hooks' => [ 'FooBaz' => 'FooBazCallback' ] ] + self::$default,
				[
					'BarBaz' => [ 'BarBazCallback' ],
					'FooBaz' => [ 'FooBazCallback' ],
				] + $merge,
			],
			// Callbacks for FooBaz wrapped in an array
			[
				[],
				[ 'Hooks' => [ 'FooBaz' => [ 'Callback1' ] ] ] + self::$default,
				[
					'FooBaz' => [ 'Callback1' ],
				] + $merge,
			],
			// Multiple callbacks for FooBaz hook
			[
				[],
				[ 'Hooks' => [ 'FooBaz' => [ 'Callback1', 'Callback2' ] ] ] + self::$default,
				[
					'FooBaz' => [ 'Callback1', 'Callback2' ],
				] + $merge,
			],
		];
	}

	/**
	 * @dataProvider provideRegisterHooks
	 */
	public function testRegisterHooks( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( [ 'wgHooks' => $pre ] );
		$processor->extractInfo( $this->dir, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['globals']['wgHooks'] );
	}

	public function testExtractConfig1() {
		$processor = new ExtensionProcessor;
		$info = [
			'config' => [
				'Bar' => 'somevalue',
				'Foo' => 10,
				'@IGNORED' => 'yes',
			],
		] + self::$default;
		$info2 = [
			'config' => [
				'_prefix' => 'eg',
				'Bar' => 'somevalue'
			],
			'name' => 'FooBar2',
		];
		$processor->extractInfo( $this->dir, $info, 1 );
		$processor->extractInfo( $this->dir, $info2, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
		$this->assertArrayNotHasKey( 'wg@IGNORED', $extracted['globals'] );
		// Custom prefix:
		$this->assertEquals( 'somevalue', $extracted['globals']['egBar'] );
	}

	public function testExtractConfig2() {
		$processor = new ExtensionProcessor;
		$info = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
				'Foo' => [ 'value' => 10 ],
				'Path' => [ 'value' => 'foo.txt', 'path' => true ],
				'Namespaces' => [
					'value' => [
						'10' => true,
						'12' => false,
					],
					'merge_strategy' => 'array_plus',
				],
			],
		] + self::$default;
		$info2 = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
			],
			'config_prefix' => 'eg',
			'name' => 'FooBar2',
		];
		$processor->extractInfo( $this->dir, $info, 2 );
		$processor->extractInfo( $this->dir, $info2, 2 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
		$this->assertEquals( "{$this->dirname}/foo.txt", $extracted['globals']['wgPath'] );
		// Custom prefix:
		$this->assertEquals( 'somevalue', $extracted['globals']['egBar'] );
		$this->assertSame(
			[ 10 => true, 12 => false, ExtensionRegistry::MERGE_STRATEGY => 'array_plus' ],
			$extracted['globals']['wgNamespaces']
		);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testDuplicateConfigKey1() {
		$processor = new ExtensionProcessor;
		$info = [
			'config' => [
				'Bar' => '',
			]
		] + self::$default;
		$info2 = [
			'config' => [
				'Bar' => 'g',
			],
			'name' => 'FooBar2',
		];
		$processor->extractInfo( $this->dir, $info, 1 );
		$processor->extractInfo( $this->dir, $info2, 1 );
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testDuplicateConfigKey2() {
		$processor = new ExtensionProcessor;
		$info = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
			]
		] + self::$default;
		$info2 = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
			],
			'name' => 'FooBar2',
		];
		$processor->extractInfo( $this->dir, $info, 2 );
		$processor->extractInfo( $this->dir, $info2, 2 );
	}

	public static function provideExtractExtensionMessagesFiles() {
		$dir = __DIR__ . '/FooBar/';
		return [
			[
				[ 'ExtensionMessagesFiles' => [ 'FooBarAlias' => 'FooBar.alias.php' ] ],
				[ 'wgExtensionMessagesFiles' => [ 'FooBarAlias' => $dir . 'FooBar.alias.php' ] ]
			],
			[
				[
					'ExtensionMessagesFiles' => [
						'FooBarAlias' => 'FooBar.alias.php',
						'FooBarMagic' => 'FooBar.magic.i18n.php',
					],
				],
				[
					'wgExtensionMessagesFiles' => [
						'FooBarAlias' => $dir . 'FooBar.alias.php',
						'FooBarMagic' => $dir . 'FooBar.magic.i18n.php',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideExtractExtensionMessagesFiles
	 */
	public function testExtractExtensionMessagesFiles( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}

	public static function provideExtractMessagesDirs() {
		$dir = __DIR__ . '/FooBar/';
		return [
			[
				[ 'MessagesDirs' => [ 'VisualEditor' => 'i18n' ] ],
				[ 'wgMessagesDirs' => [ 'VisualEditor' => [ $dir . 'i18n' ] ] ]
			],
			[
				[ 'MessagesDirs' => [ 'VisualEditor' => [ 'i18n', 'foobar' ] ] ],
				[ 'wgMessagesDirs' => [ 'VisualEditor' => [ $dir . 'i18n', $dir . 'foobar' ] ] ]
			],
		];
	}

	/**
	 * @dataProvider provideExtractMessagesDirs
	 */
	public function testExtractMessagesDirs( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}

	public function testExtractCredits() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default, 1 );
		$this->setExpectedException( Exception::class );
		$processor->extractInfo( $this->dir, self::$default, 1 );
	}

	/**
	 * @dataProvider provideExtractResourceLoaderModules
	 */
	public function testExtractResourceLoaderModules(
		$input,
		array $expectedGlobals,
		array $expectedAttribs = []
	) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expectedGlobals as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
		foreach ( $expectedAttribs as $key => $value ) {
			$this->assertEquals( $value, $out['attributes'][$key] );
		}
	}

	public static function provideExtractResourceLoaderModules() {
		$dir = __DIR__ . '/FooBar';
		return [
			// Generic module with localBasePath/remoteExtPath specified
			[
				// Input
				[
					'ResourceModules' => [
						'test.foo' => [
							'styles' => 'foobar.js',
							'localBasePath' => '',
							'remoteExtPath' => 'FooBar',
						],
					],
				],
				// Expected
				[
					'wgResourceModules' => [
						'test.foo' => [
							'styles' => 'foobar.js',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						],
					],
				],
			],
			// ResourceFileModulePaths specified:
			[
				// Input
				[
					'ResourceFileModulePaths' => [
						'localBasePath' => 'modules',
						'remoteExtPath' => 'FooBar/modules',
					],
					'ResourceModules' => [
						// No paths
						'test.foo' => [
							'styles' => 'foo.js',
						],
						// Different paths set
						'test.bar' => [
							'styles' => 'bar.js',
							'localBasePath' => 'subdir',
							'remoteExtPath' => 'FooBar/subdir',
						],
						// Custom class with no paths set
						'test.class' => [
							'class' => 'FooBarModule',
							'extra' => 'argument',
						],
						// Custom class with a localBasePath
						'test.class.with.path' => [
							'class' => 'FooBarPathModule',
							'extra' => 'argument',
							'localBasePath' => '',
						]
					],
				],
				// Expected
				[
					'wgResourceModules' => [
						'test.foo' => [
							'styles' => 'foo.js',
							'localBasePath' => "$dir/modules",
							'remoteExtPath' => 'FooBar/modules',
						],
						'test.bar' => [
							'styles' => 'bar.js',
							'localBasePath' => "$dir/subdir",
							'remoteExtPath' => 'FooBar/subdir',
						],
						'test.class' => [
							'class' => 'FooBarModule',
							'extra' => 'argument',
							'localBasePath' => "$dir/modules",
							'remoteExtPath' => 'FooBar/modules',
						],
						'test.class.with.path' => [
							'class' => 'FooBarPathModule',
							'extra' => 'argument',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar/modules',
						]
					],
				],
			],
			// ResourceModuleSkinStyles with file module paths
			[
				// Input
				[
					'ResourceFileModulePaths' => [
						'localBasePath' => '',
						'remoteSkinPath' => 'FooBar',
					],
					'ResourceModuleSkinStyles' => [
						'foobar' => [
							'test.foo' => 'foo.css',
						]
					],
				],
				// Expected
				[
					'wgResourceModuleSkinStyles' => [
						'foobar' => [
							'test.foo' => 'foo.css',
							'localBasePath' => $dir,
							'remoteSkinPath' => 'FooBar',
						],
					],
				],
			],
			// ResourceModuleSkinStyles with file module paths and an override
			[
				// Input
				[
					'ResourceFileModulePaths' => [
						'localBasePath' => '',
						'remoteSkinPath' => 'FooBar',
					],
					'ResourceModuleSkinStyles' => [
						'foobar' => [
							'test.foo' => 'foo.css',
							'remoteSkinPath' => 'BarFoo'
						],
					],
				],
				// Expected
				[
					'wgResourceModuleSkinStyles' => [
						'foobar' => [
							'test.foo' => 'foo.css',
							'localBasePath' => $dir,
							'remoteSkinPath' => 'BarFoo',
						],
					],
				],
			],
			'QUnit test module' => [
				// Input
				[
					'QUnitTestModule' => [
						'localBasePath' => '',
						'remoteExtPath' => 'Foo',
						'scripts' => 'bar.js',
					],
				],
				// Expected
				[],
				[
					'QUnitTestModules' => [
						'test.FooBar' => [
							'localBasePath' => $dir,
							'remoteExtPath' => 'Foo',
							'scripts' => 'bar.js',
						],
					],
				],
			],
		];
	}

	public static function provideSetToGlobal() {
		return [
			[
				[ 'wgAPIModules', 'wgAvailableRights' ],
				[],
				[
					'APIModules' => [ 'foobar' => 'ApiFooBar' ],
					'AvailableRights' => [ 'foobar', 'unfoobar' ],
				],
				[
					'wgAPIModules' => [ 'foobar' => 'ApiFooBar' ],
					'wgAvailableRights' => [ 'foobar', 'unfoobar' ],
				],
			],
			[
				[ 'wgAPIModules', 'wgAvailableRights' ],
				[
					'wgAPIModules' => [ 'barbaz' => 'ApiBarBaz' ],
					'wgAvailableRights' => [ 'barbaz' ]
				],
				[
					'APIModules' => [ 'foobar' => 'ApiFooBar' ],
					'AvailableRights' => [ 'foobar', 'unfoobar' ],
				],
				[
					'wgAPIModules' => [ 'barbaz' => 'ApiBarBaz', 'foobar' => 'ApiFooBar' ],
					'wgAvailableRights' => [ 'barbaz', 'foobar', 'unfoobar' ],
				],
			],
			[
				[ 'wgGroupPermissions' ],
				[
					'wgGroupPermissions' => [
						'sysop' => [ 'delete' ]
					],
				],
				[
					'GroupPermissions' => [
						'sysop' => [ 'undelete' ],
						'user' => [ 'edit' ]
					],
				],
				[
					'wgGroupPermissions' => [
						'sysop' => [ 'delete', 'undelete' ],
						'user' => [ 'edit' ]
					],
				]
			]
		];
	}

	/**
	 * Attributes under manifest_version 2
	 */
	public function testExtractAttributes() {
		$processor = new ExtensionProcessor();
		// Load FooBar extension
		$processor->extractInfo( $this->dir, [ 'name' => 'FooBar' ], 2 );
		$processor->extractInfo(
			$this->dir,
			[
				'name' => 'Baz',
				'attributes' => [
					// Loaded
					'FooBar' => [
						'Plugins' => [
							'ext.baz.foobar',
						],
					],
					// Not loaded
					'FizzBuzz' => [
						'MorePlugins' => [
							'ext.baz.fizzbuzz',
						],
					],
				],
			],
			2
		);

		$info = $processor->getExtractedInfo();
		$this->assertArrayHasKey( 'FooBarPlugins', $info['attributes'] );
		$this->assertSame( [ 'ext.baz.foobar' ], $info['attributes']['FooBarPlugins'] );
		$this->assertArrayNotHasKey( 'FizzBuzzMorePlugins', $info['attributes'] );
	}

	/**
	 * Attributes under manifest_version 1
	 */
	public function testAttributes1() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo(
			$this->dir,
			[
				'name' => 'FooBar',
				'FooBarPlugins' => [
					'ext.baz.foobar',
				],
				'FizzBuzzMorePlugins' => [
					'ext.baz.fizzbuzz',
				],
			],
			1
		);
		$processor->extractInfo(
			$this->dir,
			[
				'name' => 'FooBar2',
				'FizzBuzzMorePlugins' => [
					'ext.bar.fizzbuzz',
				]
			],
			1
		);

		$info = $processor->getExtractedInfo();
		$this->assertArrayHasKey( 'FooBarPlugins', $info['attributes'] );
		$this->assertSame( [ 'ext.baz.foobar' ], $info['attributes']['FooBarPlugins'] );
		$this->assertArrayHasKey( 'FizzBuzzMorePlugins', $info['attributes'] );
		$this->assertSame(
			[ 'ext.baz.fizzbuzz', 'ext.bar.fizzbuzz' ],
			$info['attributes']['FizzBuzzMorePlugins']
		);
	}

	public function testAttributes1_notarray() {
		$processor = new ExtensionProcessor();
		$this->setExpectedException(
			InvalidArgumentException::class,
			"The value for 'FooBarPlugins' should be an array (from {$this->dir})"
		);
		$processor->extractInfo(
			$this->dir,
			[
				'FooBarPlugins' => 'ext.baz.foobar',
			] + self::$default,
			1
		);
	}

	public function testExtractPathBasedGlobal() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo(
			$this->dir,
			[
				'ParserTestFiles' => [
					'tests/parserTests.txt',
					'tests/extraParserTests.txt',
				],
				'ServiceWiringFiles' => [
					'includes/ServiceWiring.php'
				],
			] + self::$default,
			1
		);
		$globals = $processor->getExtractedInfo()['globals'];
		$this->assertArrayHasKey( 'wgParserTestFiles', $globals );
		$this->assertSame( [
			"{$this->dirname}/tests/parserTests.txt",
			"{$this->dirname}/tests/extraParserTests.txt"
		], $globals['wgParserTestFiles'] );
		$this->assertArrayHasKey( 'wgServiceWiringFiles', $globals );
		$this->assertSame( [
			"{$this->dirname}/includes/ServiceWiring.php"
		], $globals['wgServiceWiringFiles'] );
	}

	public function testGetRequirements() {
		$info = self::$default + [
			'requires' => [
				'MediaWiki' => '>= 1.25.0',
				'platform' => [
					'php' => '>= 5.5.9'
				],
				'extensions' => [
					'Bar' => '*'
				]
			]
		];
		$processor = new ExtensionProcessor();
		$this->assertSame(
			$info['requires'],
			$processor->getRequirements( $info )
		);
		$this->assertSame(
			[],
			$processor->getRequirements( [] )
		);
	}

	public function testGetExtraAutoloaderPaths() {
		$processor = new ExtensionProcessor();
		$this->assertSame(
			[ "{$this->dirname}/vendor/autoload.php" ],
			$processor->getExtraAutoloaderPaths( $this->dirname, [
				'load_composer_autoloader' => true,
			] )
		);
	}

	/**
	 * Verify that extension.schema.json is in sync with ExtensionProcessor
	 *
	 * @coversNothing
	 */
	public function testGlobalSettingsDocumentedInSchema() {
		global $IP;
		$globalSettings = TestingAccessWrapper::newFromClass(
			ExtensionProcessor::class )->globalSettings;

		$version = ExtensionRegistry::MANIFEST_VERSION;
		$schema = FormatJson::decode(
			file_get_contents( "$IP/docs/extension.schema.v$version.json" ),
			true
		);
		$missing = [];
		foreach ( $globalSettings as $global ) {
			if ( !isset( $schema['properties'][$global] ) ) {
				$missing[] = $global;
			}
		}

		$this->assertEquals( [], $missing,
			"The following global settings are not documented in docs/extension.schema.json" );
	}
}

/**
 * Allow overriding the default value of $this->globals
 * so we can test merging
 */
class MockExtensionProcessor extends ExtensionProcessor {
	public function __construct( $globals = [] ) {
		$this->globals = $globals + $this->globals;
	}
}
