<?php

namespace MediaWiki\Tests\Registration;

use Exception;
use InvalidArgumentException;
use MediaWiki\Json\FormatJson;
use MediaWiki\Registration\ExtensionProcessor;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWikiUnitTestCase;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Registration\ExtensionProcessor
 */
class ExtensionProcessorTest extends MediaWikiUnitTestCase {

	private string $extensionPath;
	private string $dirname;

	protected function setUp(): void {
		parent::setUp();
		$this->extensionPath = self::getExtensionPath();
		$this->dirname = dirname( $this->extensionPath );
	}

	private static function getExtensionPath() {
		return __DIR__ . '/fixtures/FooBar/extension.json';
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
	 * 'name' is absolutely required, and sometimes we require two distinct ones...
	 * @var array
	 */
	public static $default2 = [
		'name' => 'FooBar2',
	];

	public function testExtractInfo() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, self::$default + [
			'@metadata' => [ 'foobarbaz' ],
			'AnAttribute' => [ 'omg' ],
			'AutoloadClasses' => [ 'FooBar' => 'includes/FooBar.php' ],
			'AutoloadNamespaces' => [ '\Foo\Bar\\' => 'includes/foo/bar/' ],
			'ForeignResourcesDir' => 'lib',
			'SpecialPages' => [ 'Foo' => 'SpecialFoo' ],
			'callback' => 'FooBar::onRegistration',
		], 1 );

		$extracted = $processor->getExtractedInfo();
		$attributes = $extracted['attributes'];
		$this->assertArrayHasKey( 'AnAttribute', $attributes );
		$this->assertArrayNotHasKey( '@metadata', $attributes );
		$this->assertArrayNotHasKey( 'AutoloadClasses', $attributes );
		$this->assertArrayNotHasKey( 'AutoloadNamespaces', $attributes );
		$this->assertArrayHasKey( 'autoloaderPaths', $extracted );
		$this->assertArrayHasKey( 'autoloaderClasses', $extracted );
		$this->assertArrayHasKey( 'autoloaderNS', $extracted );
		$this->assertSame(
			[ 'FooBar' => dirname( $this->extensionPath ) . '/lib' ],
			$attributes['ForeignResourcesDir']
		);
		$this->assertSame(
			[ 'FooBar' => 'FooBar::onRegistration' ],
			$extracted['callbacks']
		);
		$this->assertSame(
			[ 'Foo' => 'SpecialFoo' ],
			$extracted['globals']['wgSpecialPages']
		);
	}

	public function testExtractAutoload() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, self::$default + [
			'AutoloadClasses' => [ 'FooBar' => 'includes/FooBar.php' ],
			'AutoloadNamespaces' => [
				'\Foo\Bar\\' => 'includes/foo/bar/',
				// Test case to ensure a missing / is added
				'\Foo\Bar2\\' => 'includes/foo/bar2',
			],
			'TestAutoloadClasses' => [ 'FooBarTest' => 'tests/FooBarTest.php' ],
			'TestAutoloadNamespaces' => [ '\Foo\Bar\Test\\' => 'tests/foo/bar/' ],
			'load_composer_autoloader' => true,
		], 1 );

		$extracted = $processor->getExtractedInfo();
		$this->assertArrayHasKey( 'autoloaderPaths', $extracted );
		$this->assertArrayHasKey( 'autoloaderClasses', $extracted );
		$this->assertArrayHasKey( 'autoloaderNS', $extracted );
		$this->assertSame(
			[ 'FooBar' => $this->dirname . '/includes/FooBar.php' ],
			$extracted['autoloaderClasses']
		);
		$this->assertSame(
			[
				'\Foo\Bar\\' => $this->dirname . '/includes/foo/bar/',
				'\Foo\Bar2\\' => $this->dirname . '/includes/foo/bar2/',
			],
			$extracted['autoloaderNS']
		);
		$this->assertSame(
			[ $this->dirname . '/vendor/autoload.php' ],
			$extracted['autoloaderPaths']
		);

		$extracted = $processor->getExtractedInfo( true );
		$this->assertArrayHasKey( 'autoloaderPaths', $extracted );
		$this->assertArrayHasKey( 'autoloaderClasses', $extracted );
		$this->assertArrayHasKey( 'autoloaderNS', $extracted );
		$this->assertSame(
			[
				'FooBar' => $this->dirname . '/includes/FooBar.php',
				'FooBarTest' => $this->dirname . '/tests/FooBarTest.php'
			],
			$extracted['autoloaderClasses']
		);
		$this->assertSame(
			[
				'\Foo\Bar\\' => $this->dirname . '/includes/foo/bar/',
				'\Foo\Bar2\\' => $this->dirname . '/includes/foo/bar2/',
				'\Foo\Bar\Test\\' => $this->dirname . '/tests/foo/bar/'
			],
			$extracted['autoloaderNS']
		);
		$this->assertSame(
			[ $this->dirname . '/vendor/autoload.php' ],
			$extracted['autoloaderPaths']
		);
	}

	public function testExtractSkins() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, self::$default + [
			'ValidSkinNames' => [
				'test-vector' => [
					'class' => 'SkinTestVector',
				],
				'test-vector-empty-args' => [
					'class' => 'SkinTestVector',
					'args' => []
				],
				'test-vector-empty-options' => [
					'class' => 'SkinTestVector',
					'args' => [
						[]
					]
				],
				'test-vector-skin-relative' => [
					'class' => 'SkinTestVector',
					'args' => [
						[
							'templateDirectory' => 'templates',
						]
					]
				],
				'test-vector-string' => 'TestVector',
			]
		], 1 );
		$extracted = $processor->getExtractedInfo();
		$validSkins = $extracted['globals']['wgValidSkinNames'];

		$this->assertArrayHasKey( 'test-vector', $validSkins );
		$this->assertArrayHasKey( 'test-vector-empty-args', $validSkins );
		$this->assertArrayHasKey( 'test-vector-skin-relative', $validSkins );
		$this->assertSame(
			$this->dirname . '/templates',
			$validSkins['test-vector-empty-options']['args'][0]['templateDirectory'],
			'A sensible default is provided.'
		);
		$this->assertSame(
			$this->dirname . '/templates',
			$validSkins['test-vector-skin-relative']['args'][0]['templateDirectory'],
			'modified'
		);
		$this->assertSame( 'TestVector', $validSkins['test-vector-string'] );
	}

	public function testExtractNamespaces() {
		// Test that namespace IDs defined in extension.json can be overwritten locally
		if ( !defined( 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X' ) ) {
			define( 'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X', 123456 );
		}

		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, self::$default + [
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
		$this->assertArrayHasKey(
			'MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X',
			$extracted['defines']
		);

		$this->assertSame(
			123456,
			$extracted['defines']['MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_X']
		);

		$this->assertSame(
			332200,
			$extracted['defines']['MW_EXTENSION_PROCESSOR_TEST_EXTRACT_INFO_A']
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

	public function testRateLimits() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo(
			'',
			[
				'name' => 'Foo',
				'RateLimits' => [
					'test1' => [
						"user" => [ 1, 7200 ]
					],
					'test2' => [
						"user" => [ 2, 7200 ]
					],
				],
				'AvailableRights' => [
					'test2',
				]
			] + self::$default,
			2
		);

		$processor->extractInfo(
			'Bar',
			[
				'name' => 'Bar',
				'RateLimits' => [
					'test3' => [
						"user" => [ 1, 7200 ]
					],
				],
			] + self::$default,
			2
		);

		$extracted = $processor->getExtractedInfo();

		$this->assertArrayHasKey( 'wgRateLimits', $extracted['globals'] );
		$this->assertArrayHasKey( 'wgImplicitRights', $extracted['globals'] );
		$this->assertArrayHasKey( 'wgAvailableRights', $extracted['globals'] );

		$this->assertArrayHasKey( 'test1', $extracted['globals']['wgRateLimits'] );
		$this->assertArrayHasKey( 'test2', $extracted['globals']['wgRateLimits'] );
		$this->assertArrayHasKey( 'test3', $extracted['globals']['wgRateLimits'] );

		$this->assertContains( 'test2', $extracted['globals']['wgAvailableRights'] );
		$this->assertNotContains( 'test1', $extracted['globals']['wgAvailableRights'] );

		$this->assertContains( 'test1', $extracted['globals']['wgImplicitRights'] );
		$this->assertContains( 'test3', $extracted['globals']['wgImplicitRights'] );
		$this->assertNotContains( 'test2', $extracted['globals']['wgImplicitRights'] );
	}

	public static function provideMixedStyleHooks() {
		// Format:
		// Content in extension.json
		// Expected wgHooks
		// Expected Hooks
		return [
			[
				[
					'Hooks' => [ 'FooBaz' => [
						[ 'handler' => 'HandlerObjectCallback' ],
						[ 'handler' => 'HandlerObjectCallback', 'deprecated' => true ],
						'HandlerObjectCallback',
						[ 'FooClass', 'FooMethod' ],
						'GlobalLegacyFunction',
						'FooClass',
						"FooClass::staticMethod"
					] ],
					'HookHandlers' => [
						'HandlerObjectCallback' => [ 'class' => 'FooClass', 'services' => [] ]
					]
				] + self::$default,
				[
					'FooBaz' => [
						[ 'FooClass', 'FooMethod' ],
						'GlobalLegacyFunction',
						'FooClass',
						'FooClass::staticMethod'
					]
				] + [ ExtensionRegistry::MERGE_STRATEGY => 'array_merge_recursive' ],
				[
					'FooBaz' => [
						[
							'handler' => [
								'class' => 'FooClass',
								'services' => [],
								'name' => 'FooBar-HandlerObjectCallback'
							],
							'extensionPath' => self::getExtensionPath()
						],
						[
							'handler' => [
								'class' => 'FooClass',
								'services' => [],
								'name' => 'FooBar-HandlerObjectCallback'
							],
							'deprecated' => true,
							'extensionPath' => self::getExtensionPath()
						],
						[
							'handler' => [
								'class' => 'FooClass',
								'services' => [],
								'name' => 'FooBar-HandlerObjectCallback'
							],
							'extensionPath' => self::getExtensionPath()
						]
					]
				]
			]
		];
	}

	public static function provideNonLegacyHooks() {
		// Format:
		// Current Hooks attribute
		// Content in extension.json
		// Expected Hooks attribute
		return [
			// Hook for "FooBaz": object with handler attribute
			[
				[ 'FooBaz' => [ 'PriorCallback' ] ],
				[
					'Hooks' => [ 'FooBaz' => [ 'handler' => 'HandlerObjectCallback', 'deprecated' => true ] ],
					'HookHandlers' => [
						'HandlerObjectCallback' => [
							'class' => 'FooClass',
							'services' => [],
							'name' => 'FooBar-HandlerObjectCallback'
						]
					]
				] + self::$default,
				[ 'FooBaz' =>
					[
						'PriorCallback',
						[
							'handler' => [
								'class' => 'FooClass',
								'services' => [],
								'name' => 'FooBar-HandlerObjectCallback'
							],
							'deprecated' => true,
							'extensionPath' => self::getExtensionPath()
						]
					]
				],
				[]
			],
			// Hook for "FooBaz": string corresponding to a handler definition
			[
				[ 'FooBaz' => [ 'PriorCallback' ] ],
				[
					'Hooks' => [ 'FooBaz' => [ 'HandlerObjectCallback' ] ],
					'HookHandlers' => [
						'HandlerObjectCallback' => [ 'class' => 'FooClass', 'services' => [] ],
					]
				] + self::$default,
				[ 'FooBaz' =>
					[
						'PriorCallback',
						[
							'handler' => [
								'class' => 'FooClass',
								'services' => [],
								'name' => 'FooBar-HandlerObjectCallback'
							],
							'extensionPath' => self::getExtensionPath()
						],
					]
				],
				[]
			],
			// Hook for "FooBaz", string corresponds to handler def. and object with handler attribute
			[
				[ 'FooBaz' => [ 'PriorCallback' ] ],
				[
					'Hooks' => [ 'FooBaz' => [
						[ 'handler' => 'HandlerObjectCallback', 'deprecated' => true ],
						'HandlerObjectCallback2'
					] ],
					'HookHandlers' => [
						'HandlerObjectCallback2' => [ 'class' => 'FooClass', 'services' => [] ],
						'HandlerObjectCallback' => [ 'class' => 'FooClass', 'services' => [] ],
					]
				] + self::$default,
				[ 'FooBaz' =>
					[
						'PriorCallback',
						[
							'handler' => [
								'name' => 'FooBar-HandlerObjectCallback',
								'class' => 'FooClass',
								'services' => []
							],
							'deprecated' => true,
							'extensionPath' => self::getExtensionPath()
						],
						[
							'handler' => [
								'name' => 'FooBar-HandlerObjectCallback2',
								'class' => 'FooClass',
								'services' => [],
							],
							'extensionPath' => self::getExtensionPath()
						]
					]
				],
				[]
			],
			// Hook for "FooBaz": string corresponding to a new-style handler definition
			// and legacy style object and method array
			[
				[ 'FooBaz' => [ 'PriorCallback' ] ],
				[
					'Hooks' => [ 'FooBaz' => [
						'HandlerObjectCallback',
						[ 'FooClass', 'FooMethod ' ]
					] ],
					'HookHandlers' => [
						'HandlerObjectCallback' => [ 'class' => 'FooClass', 'services' => [] ],
					]
				] + self::$default,
				[ 'FooBaz' =>
					[
						'PriorCallback',
						[
							'handler' => [
								'name' => 'FooBar-HandlerObjectCallback',
								'class' => 'FooClass',
								'services' => []
							],
							'extensionPath' => self::getExtensionPath()
						],
					]
				],
				[ 'FooClass', 'FooMethod' ]
			]
		];
	}

	public static function provideLegacyHooks() {
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
	 * @dataProvider provideNonLegacyHooks
	 */
	public function testNonLegacyHooks( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( [ 'attributes' => [ 'Hooks' => $pre ] ] );
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['attributes']['Hooks'] );
	}

	/**
	 * @dataProvider provideMixedStyleHooks
	 */
	public function testMixedStyleHooks( $info, $expectedWgHooks, $expectedNewHooks ) {
		$processor = new MockExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expectedWgHooks, $extracted['globals']['wgHooks'] );
		$this->assertEquals( $expectedNewHooks, $extracted['attributes']['Hooks'] );
	}

	/**
	 * @dataProvider provideLegacyHooks
	 */
	public function testLegacyHooks( $pre, $info, $expected ) {
		$preset = [ 'globals' => [ 'wgHooks' => $pre ] ];
		$processor = new MockExtensionProcessor( $preset );
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['globals']['wgHooks'] );
	}

	public function testRegisterHandlerWithoutDefinition() {
		$info = [
			'Hooks' => [ 'FooBaz' => [ 'handler' => 'NoHandlerDefinition' ] ],
			'HookHandlers' => []
		] + self::$default;
		$processor = new MockExtensionProcessor();
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage(
			'Missing handler definition for FooBaz in HookHandlers attribute'
		);
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$processor->getExtractedInfo();
	}

	public static function provideDomainEventDomainEventIngresses() {
		// Format:
		// Current attributes
		// Content in extension.json
		// Expected DomainEventIngresses attribute
		return [
			'DomainEventIngresses' => [
				[
					'DomainEventIngresses' => [
						[ 'events' => [ 'FooDone' ], 'factory' => 'PriorCallback' ]
					]
				],
				[
					'DomainEventIngresses' => [
						[
							'events' => [ 'FooDone', 'BarDone', ],
							'class' => 'FooClass',
							'services' => [],
						],
					]
				] + self::$default,
				[
					[ 'events' => [ 'FooDone' ], 'factory' => 'PriorCallback' ],
					[
						'events' => [ 'FooDone', 'BarDone', ],
						'class' => 'FooClass',
						'services' => [],
						'extensionPath' => self::getExtensionPath()
					]
				]
			],
		];
	}

	/**
	 * @dataProvider provideDomainEventDomainEventIngresses
	 */
	public function testDomainEventDomainEventIngresses( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( [ 'attributes' => $pre ] );
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['attributes']['DomainEventIngresses'] );
	}

	public function testExtractConfig1() {
		$processor = new ExtensionProcessor();
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
		] + self::$default2;
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$processor->extractInfo( $this->extensionPath, $info2, 1 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
		$this->assertArrayNotHasKey( 'wg@IGNORED', $extracted['globals'] );
		// Custom prefix:
		$this->assertEquals( 'somevalue', $extracted['globals']['egBar'] );
	}

	public function testExtractConfig2() {
		$processor = new ExtensionProcessor();
		$info = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
				'Foo' => [ 'value' => 10 ],
				'Path' => [ 'value' => 'foo.txt', 'path' => true ],
				'PathArray' => [ 'value' => [ 'foo.bar', 'bar.foo', 'bar/foo.txt' ], 'path' => true ],
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
		] + self::$default2;
		$processor->extractInfo( $this->extensionPath, $info, 2 );
		$processor->extractInfo( $this->extensionPath, $info2, 2 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
		$this->assertEquals( "{$this->dirname}/foo.txt", $extracted['globals']['wgPath'] );
		$this->assertEquals(
			[
				"{$this->dirname}/foo.bar",
				"{$this->dirname}/bar.foo",
				"{$this->dirname}/bar/foo.txt"
			],
			$extracted['globals']['wgPathArray']
		);
		// Custom prefix:
		$this->assertEquals( 'somevalue', $extracted['globals']['egBar'] );
		$this->assertSame(
			[ 10 => true, 12 => false, ExtensionRegistry::MERGE_STRATEGY => 'array_plus' ],
			$extracted['globals']['wgNamespaces']
		);
	}

	public function testDuplicateConfigKey1() {
		$processor = new ExtensionProcessor();
		$info = [
			'config' => [
				'Bar' => '',
			]
		] + self::$default;
		$info2 = [
			'config' => [
				'Bar' => 'g',
			],
		] + self::$default2;
		$this->expectException( RuntimeException::class );
		$processor->extractInfo( $this->extensionPath, $info, 1 );
		$processor->extractInfo( $this->extensionPath, $info2, 1 );
	}

	public function testDuplicateConfigKey2() {
		$processor = new ExtensionProcessor();
		$info = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
			]
		] + self::$default;
		$info2 = [
			'config' => [
				'Bar' => [ 'value' => 'somevalue' ],
			],
		] + self::$default2;
		$this->expectException( RuntimeException::class );
		$processor->extractInfo( $this->extensionPath, $info, 2 );
		$processor->extractInfo( $this->extensionPath, $info2, 2 );
	}

	public static function provideExtractExtensionMessagesFiles() {
		$dir = dirname( self::getExtensionPath() );
		return [
			[
				[ 'ExtensionMessagesFiles' => [ 'FooBarAlias' => 'FooBar.alias.php' ] ],
				[ 'wgExtensionMessagesFiles' => [ 'FooBarAlias' => $dir . '/FooBar.alias.php' ] ]
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
						'FooBarAlias' => $dir . '/FooBar.alias.php',
						'FooBarMagic' => $dir . '/FooBar.magic.i18n.php',
					],
				],
			],
		];
	}

	public static function provideExtractRestModuleFiles() {
		$dir = dirname( self::getExtensionPath() );
		return [
			[
				[ 'RestModuleFiles' => [ 'FooBar.json' ] ],
				[ 'wgRestAPIAdditionalRouteFiles' => [ $dir . '/FooBar.json' ] ]
			],
			[
				[
					'RestModuleFiles' => [
						'FooBar.json',
						'Xyzzy.json',
					],
				],
				[
					'wgRestAPIAdditionalRouteFiles' => [
						$dir . '/FooBar.json',
						$dir . '/Xyzzy.json',
					],
				],
			],
		];
	}

	public static function provideExtractMessagesDirs() {
		$dir = dirname( self::getExtensionPath() );
		return [
			[
				[ 'MessagesDirs' => [ 'VisualEditor' => 'i18n' ] ],
				[ 'wgMessagesDirs' => [ 'VisualEditor' => [ $dir . '/i18n' ] ] ]
			],
			[
				[ 'MessagesDirs' => [ 'VisualEditor' => [ 'i18n', 'foobar' ] ] ],
				[ 'wgMessagesDirs' => [ 'VisualEditor' => [ $dir . '/i18n', $dir . '/foobar' ] ] ]
			],
		];
	}

	/**
	 * @dataProvider provideExtractExtensionMessagesFiles
	 * @dataProvider provideExtractRestModuleFiles
	 * @dataProvider provideExtractMessagesDirs
	 */
	public function testExtractFilesAndDirs( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}

	public function testExtractCredits() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, self::$default, 1 );
		$this->expectException( Exception::class );
		$processor->extractInfo( $this->extensionPath, self::$default, 1 );
	}

	/**
	 * @dataProvider provideExtractResourceLoaderModules
	 */
	public function testExtractResourceLoaderModules(
		$input,
		array $expectedAttribs = []
	) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->extensionPath, $input + self::$default, 1 );
		$out = $processor->getExtractedInfo();
		foreach ( $expectedAttribs as $key => $value ) {
			$this->assertEquals( $value, $out['attributes'][$key] );
		}
	}

	public static function provideExtractResourceLoaderModules() {
		$dir = dirname( self::getExtensionPath() );
		return [
			'Generic module with localBasePath/remoteExtPath specified' => [
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
					'ResourceModules' => [
						'test.foo' => [
							'styles' => 'foobar.js',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						],
					],
				],
			],
			'ResourceFileModulePaths specified' => [
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
					'ResourceModules' => [
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
			'ResourceModuleSkinStyles with file module paths' => [
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
					'ResourceModuleSkinStyles' => [
						'foobar' => [
							'test.foo' => 'foo.css',
							'localBasePath' => $dir,
							'remoteSkinPath' => 'FooBar',
						],
					],
				],
			],
			'ResourceModuleSkinStyles with file module paths and an override' => [
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
					'ResourceModuleSkinStyles' => [
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
				[
					'QUnitTestModule' => [
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

	/**
	 * Attributes under manifest_version 2
	 */
	public function testExtractAttributes() {
		$processor = new ExtensionProcessor();
		// Load FooBar extension
		$processor->extractInfo( $this->extensionPath, self::$default, 2 );
		$processor->extractInfo(
			$this->extensionPath,
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
			$this->extensionPath,
			[
				'FooBarPlugins' => [
					'ext.baz.foobar',
				],
				'FizzBuzzMorePlugins' => [
					'ext.baz.fizzbuzz',
				],
			] + self::$default,
			1
		);
		$processor->extractInfo(
			$this->extensionPath,
			[
				'FizzBuzzMorePlugins' => [
					'ext.bar.fizzbuzz',
				]
			] + self::$default2,
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
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			"The value for 'FooBarPlugins' should be an array (from {$this->extensionPath})"
		);
		$processor->extractInfo(
			$this->extensionPath,
			[
				'FooBarPlugins' => 'ext.baz.foobar',
			] + self::$default,
			1
		);
	}

	public function testExtractPathBasedGlobal() {
		$processor = new ExtensionProcessor();
		$processor->extractInfo(
			$this->extensionPath,
			[
				'ServiceWiringFiles' => [
					'includes/ServiceWiring.php'
				],
			] + self::$default,
			1
		);
		$globals = $processor->getExtractedInfo()['globals'];
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
			$processor->getRequirements( $info, false )
		);
		$this->assertSame(
			[],
			$processor->getRequirements( [], false )
		);
	}

	public function testGetDevRequirements() {
		$info = self::$default + [
			'dev-requires' => [
				'MediaWiki' => '>= 1.31.0',
				'platform' => [
					'ext-foo' => '*',
				],
				'skins' => [
					'Baz' => '*',
				],
				'extensions' => [
					'Biz' => '*',
				],
			],
		];
		$processor = new ExtensionProcessor();
		$this->assertSame(
			$info['dev-requires'],
			$processor->getRequirements( $info, true )
		);
		// Set some standard requirements, so we can test merging
		$info['requires'] = [
			'MediaWiki' => '>= 1.25.0',
			'platform' => [
				'php' => '>= 5.5.9'
			],
			'extensions' => [
				'Bar' => '*'
			]
		];
		$this->assertSame(
			[
				'MediaWiki' => '>= 1.25.0 >= 1.31.0',
				'platform' => [
					'php' => '>= 5.5.9',
					'ext-foo' => '*',
				],
				'extensions' => [
					'Bar' => '*',
					'Biz' => '*',
				],
				'skins' => [
					'Baz' => '*',
				],
			],
			$processor->getRequirements( $info, true )
		);

		// If there's no dev-requires, it just returns requires
		unset( $info['dev-requires'] );
		$this->assertSame(
			$info['requires'],
			$processor->getRequirements( $info, true )
		);
	}

	public function testGetExtractedAutoloadInfo() {
		$processor = new ExtensionProcessor();

		$processor->extractInfo( $this->extensionPath, [
			'name' => 'Test',
			'AutoloadClasses' => [ 'FooBar' => 'includes/FooBar.php' ],
			'AutoloadNamespaces' => [ '\Foo\Bar\\' => 'includes/foo/bar/' ],
			'TestAutoloadClasses' => [ 'FooBarTest' => 'tests/FooBarTest.php' ],
			'TestAutoloadNamespaces' => [ '\Foo\Bar\Test\\' => 'tests/foo/bar/' ],
			'load_composer_autoloader' => true,
		], 2 );

		$autoload = $processor->getExtractedAutoloadInfo();
		$this->assertSame(
			[ 'FooBar' => $this->dirname . '/includes/FooBar.php' ],
			$autoload['classes']
		);
		$this->assertSame(
			[ '\Foo\Bar\\' => $this->dirname . '/includes/foo/bar/' ],
			$autoload['namespaces']
		);
		$this->assertSame(
			[ $this->dirname . '/vendor/autoload.php' ],
			$autoload['files']
		);

		$autoload = $processor->getExtractedAutoloadInfo( true );
		$this->assertSame(
			[
				'FooBar' => $this->dirname . '/includes/FooBar.php',
				'FooBarTest' => $this->dirname . '/tests/FooBarTest.php'
			],
			$autoload['classes']
		);
		$this->assertSame(
			[
				'\Foo\Bar\\' => $this->dirname . '/includes/foo/bar/',
				'\Foo\Bar\Test\\' => $this->dirname . '/tests/foo/bar/'
			],
			$autoload['namespaces']
		);
		$this->assertSame(
			[ $this->dirname . '/vendor/autoload.php' ],
			$autoload['files']
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

	/**
	 * Verify that extension.schema.v1.json is unchanged
	 *
	 * Frozen since MediaWiki 1.43; see T258668 for details.
	 *
	 * @coversNothing
	 */
	public function testVersion1SchemaIsFrozen() {
		global $IP;

		$schemaFileHash = md5_file( "$IP/docs/extension.schema.v1.json", false );

		$this->assertSame(
			'51b7eb8503c163fb1381110bc995cdd5',
			$schemaFileHash,
			"Manifest_version 1 is frozen and should not be changed or given new features" );
	}

	public function testGetCoreAttribsMerging() {
		$processor = new ExtensionProcessor();

		$info = self::$default + [
			'TrackingCategories' => [
				'Foo'
			]
		];

		$info2 = self::$default2 + [
			'TrackingCategories' => [
				'Bar'
			]
		];

		$processor->extractInfo( $this->extensionPath, $info, 2 );
		$processor->extractInfo( $this->extensionPath, $info2, 2 );

		$attributes = $processor->getExtractedInfo()['attributes'];

		$this->assertEquals(
			[ 'Foo', 'Bar' ],
			$attributes['TrackingCategories']
		);
	}
}

/**
 * Allow overriding the default value of $this->globals and $this->attributes
 * so we can test merging and hook extraction
 */
class MockExtensionProcessor extends ExtensionProcessor {

	public function __construct( $preset = [] ) {
		if ( isset( $preset['globals'] ) ) {
			$this->globals = $preset['globals'] + $this->globals;
		}
		if ( isset( $preset['attributes'] ) ) {
			$this->attributes = $preset['attributes'] + $this->attributes;
		}
	}
}
