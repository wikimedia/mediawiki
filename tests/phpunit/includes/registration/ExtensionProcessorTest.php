<?php

class ExtensionProcessorTest extends MediaWikiTestCase {

	private $dir;

	public function setUp() {
		parent::setUp();
		$this->dir = __DIR__ . '/FooBar/extension.json';
	}

	/**
	 * 'name' is absolutely required
	 *
	 * @var array
	 */
	public static $default = [
		'name' => 'FooBar',
	];

	/**
	 * @covers ExtensionProcessor::extractInfo
	 */
	public function testExtractInfo() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default + [
			'@metadata' => [ 'foobarbaz' ],
			'AnAttribute' => [ 'omg' ],
			'AutoloadClasses' => [ 'FooBar' => 'includes/FooBar.php' ],
		], 1 );

		$extracted = $processor->getExtractedInfo();
		$attributes = $extracted['attributes'];
		$this->assertArrayHasKey( 'AnAttribute', $attributes );
		$this->assertArrayNotHasKey( '@metadata', $attributes );
		$this->assertArrayNotHasKey( 'AutoloadClasses', $attributes );
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
	 * @covers ExtensionProcessor::extractHooks
	 * @dataProvider provideRegisterHooks
	 */
	public function testRegisterHooks( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( [ 'wgHooks' => $pre ] );
		$processor->extractInfo( $this->dir, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['globals']['wgHooks'] );
	}

	/**
	 * @covers ExtensionProcessor::extractConfig
	 */
	public function testExtractConfig() {
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
	 * @covers ExtensionProcessor::extractExtensionMessagesFiles
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
	 * @covers ExtensionProcessor::extractMessagesDirs
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

	/**
	 * @covers ExtensionProcessor::extractCredits
	 */
	public function testExtractCredits() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default, 1 );
		$this->setExpectedException( 'Exception' );
		$processor->extractInfo( $this->dir, self::$default, 1 );
	}

	/**
	 * @covers ExtensionProcessor::extractResourceLoaderModules
	 * @dataProvider provideExtractResourceLoaderModules
	 */
	public function testExtractResourceLoaderModules( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
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
						'localBasePath' => '',
						'remoteExtPath' => 'FooBar',
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
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						],
						'test.bar' => [
							'styles' => 'bar.js',
							'localBasePath' => "$dir/subdir",
							'remoteExtPath' => 'FooBar/subdir',
						],
						'test.class' => [
							'class' => 'FooBarModule',
							'extra' => 'argument',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						],
						'test.class.with.path' => [
							'class' => 'FooBarPathModule',
							'extra' => 'argument',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
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
