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
	static $default = array(
		'name' => 'FooBar',
	);

	/**
	 * @covers ExtensionProcessor::extractInfo
	 */
	public function testExtractInfo() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default + array(
			'@metadata' => array( 'foobarbaz' ),
			'AnAttribute' => array( 'omg' ),
			'AutoloadClasses' => array( 'FooBar' => 'includes/FooBar.php' ),
		) );

		$extracted = $processor->getExtractedInfo();
		$attributes = $extracted['attributes'];
		$this->assertArrayHasKey( 'AnAttribute', $attributes );
		$this->assertArrayNotHasKey( '@metadata', $attributes );
		$this->assertArrayNotHasKey( 'AutoloadClasses', $attributes );
	}

	public static function provideRegisterHooks() {
		$merge = array( ExtensionRegistry::MERGE_STRATEGY => 'array_merge_recursive' );
		// Format:
		// Current $wgHooks
		// Content in extension.json
		// Expected value of $wgHooks
		return array(
			// No hooks
			array(
				array(),
				self::$default,
				$merge,
			),
			// No current hooks, adding one for "FooBaz"
			array(
				array(),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array( 'FooBaz' => array( 'FooBazCallback' ) ) + $merge,
			),
			// Hook for "FooBaz", adding another one
			array(
				array( 'FooBaz' => array( 'PriorCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array( 'FooBaz' => array( 'PriorCallback', 'FooBazCallback' ) ) + $merge,
			),
			// Hook for "BarBaz", adding one for "FooBaz"
			array(
				array( 'BarBaz' => array( 'BarBazCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array(
					'BarBaz' => array( 'BarBazCallback' ),
					'FooBaz' => array( 'FooBazCallback' ),
				) + $merge,
			),
			// Callbacks for FooBaz wrapped in an array
			array(
				array(),
				array( 'Hooks' => array( 'FooBaz' => array( 'Callback1' ) ) ) + self::$default,
				array(
					'FooBaz' => array( 'Callback1' ),
				) + $merge,
			),
			// Multiple callbacks for FooBaz hook
			array(
				array(),
				array( 'Hooks' => array( 'FooBaz' => array( 'Callback1', 'Callback2' ) ) ) + self::$default,
				array(
					'FooBaz' => array( 'Callback1', 'Callback2' ),
				) + $merge,
			),
		);
	}

	/**
	 * @covers ExtensionProcessor::extractHooks
	 * @dataProvider provideRegisterHooks
	 */
	public function testRegisterHooks( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( array( 'wgHooks' => $pre ) );
		$processor->extractInfo( $this->dir, $info );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['globals']['wgHooks'] );
	}

	/**
	 * @covers ExtensionProcessor::extractConfig
	 */
	public function testExtractConfig() {
		$processor = new ExtensionProcessor;
		$info = array(
			'config' => array(
				'Bar' => 'somevalue',
				'Foo' => 10,
				'@IGNORED' => 'yes',
			),
		) + self::$default;
		$info2 = array(
			'config' => array(
				'_prefix' => 'eg',
				'Bar' => 'somevalue'
			),
		) + self::$default;
		$processor->extractInfo( $this->dir, $info );
		$processor->extractInfo( $this->dir, $info2 );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
		$this->assertArrayNotHasKey( 'wg@IGNORED', $extracted['globals'] );
		// Custom prefix:
		$this->assertEquals( 'somevalue', $extracted['globals']['egBar'] );
	}

	public static function provideExtracttExtensionMessagesFiles() {
		$dir = __DIR__ . '/FooBar/';
		return array(
			array(
				array( 'ExtensionMessagesFiles' => array( 'FooBarAlias' => 'FooBar.alias.php' ) ),
				array( 'wgExtensionMessagesFiles' => array( 'FooBarAlias' => $dir . 'FooBar.alias.php' ) )
			),
			array(
				array(
					'ExtensionMessagesFiles' => array(
						'FooBarAlias' => 'FooBar.alias.php',
						'FooBarMagic' => 'FooBar.magic.i18n.php',
					),
				),
				array(
					'wgExtensionMessagesFiles' => array(
						'FooBarAlias' => $dir . 'FooBar.alias.php',
						'FooBarMagic' => $dir . 'FooBar.magic.i18n.php',
					),
				),
			),
		);
	}

	/**
	 * @covers ExtensionProcessor::extracttExtensionMessagesFiles
	 * @dataProvider provideExtracttExtensionMessagesFiles
	 */
	public function testExtracttExtensionMessagesFiles( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}


	public static function provideExtractMessagesDirs() {
		$dir = __DIR__ . '/FooBar/';
		return array(
			array(
				array( 'MessagesDirs' => array( 'VisualEditor' => 'i18n' ) ),
				array( 'wgMessagesDirs' => array( 'VisualEditor' => array( $dir . 'i18n' ) ) )
			),
			array(
				array( 'MessagesDirs' => array( 'VisualEditor' => array( 'i18n', 'foobar' ) ) ),
				array( 'wgMessagesDirs' => array( 'VisualEditor' => array( $dir . 'i18n', $dir . 'foobar' ) ) )
			),
		);
	}

	/**
	 * @covers ExtensionProcessor::extractMessagesDirs
	 * @dataProvider provideExtractMessagesDirs
	 */
	public function testExtractMessagesDirs( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}

	/**
	 * @covers ExtensionProcessor::extractResourceLoaderModules
	 * @dataProvider provideExtractResourceLoaderModules
	 */
	public function testExtractResourceLoaderModules( $input, $expected ) {
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, $input + self::$default );
		$out = $processor->getExtractedInfo();
		foreach ( $expected as $key => $value ) {
			$this->assertEquals( $value, $out['globals'][$key] );
		}
	}

	public static function provideExtractResourceLoaderModules() {
		$dir = __DIR__ . '/FooBar/';
		return array(
			// Generic module with localBasePath/remoteExtPath specified
			array(
				// Input
				array(
					'ResourceModules' => array(
						'test.foo' => array(
							'styles' => 'foobar.js',
							'localBasePath' => '',
							'remoteExtPath' => 'FooBar',
						),
					),
				),
				// Expected
				array(
					'wgResourceModules' => array(
						'test.foo' => array(
							'styles' => 'foobar.js',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						),
					),
				),
			),
			// ResourceFileModulePaths specified:
			array(
				// Input
				array(
					'ResourceFileModulePaths' => array(
						'localBasePath' => '',
						'remoteExtPath' => 'FooBar',
					),
					'ResourceModules' => array(
						// No paths
						'test.foo' => array(
							'styles' => 'foo.js',
						),
						// Different paths set
						'test.bar' => array(
							'styles' => 'bar.js',
							'localBasePath' => 'subdir',
							'remoteExtPath' => 'FooBar/subdir',
						),
						// Custom class with no paths set
						'test.class' => array(
							'class' => 'FooBarModule',
							'extra' => 'argument',
						),
						// Custom class with a localBasePath
						'test.class.with.path' => array(
							'class' => 'FooBarPathModule',
							'extra' => 'argument',
							'localBasePath' => '',
						)
					),
				),
				// Expected
				array(
					'wgResourceModules' => array(
						'test.foo' => array(
							'styles' => 'foo.js',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						),
						'test.bar' => array(
							'styles' => 'bar.js',
							'localBasePath' => $dir . 'subdir',
							'remoteExtPath' => 'FooBar/subdir',
						),
						'test.class' => array(
							'class' => 'FooBarModule',
							'extra' => 'argument',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						),
						'test.class.with.path' => array(
							'class' => 'FooBarPathModule',
							'extra' => 'argument',
							'localBasePath' => $dir,
							'remoteExtPath' => 'FooBar',
						)
					),
				),
			),
			// ResourceModuleSkinStyles with file module paths
			array(
				// Input
				array(
					'ResourceFileModulePaths' => array(
						'localBasePath' => '',
						'remoteSkinPath' => 'FooBar',
					),
					'ResourceModuleSkinStyles' => array(
						'foobar' => array(
							'test.foo' => 'foo.css',
						)
					),
				),
				// Expected
				array(
					'wgResourceModuleSkinStyles' => array(
						'foobar' => array(
							'test.foo' => 'foo.css',
							'localBasePath' => $dir,
							'remoteSkinPath' => 'FooBar',
						),
					),
				),
			),
			// ResourceModuleSkinStyles with file module paths and an override
			array(
				// Input
				array(
					'ResourceFileModulePaths' => array(
						'localBasePath' => '',
						'remoteSkinPath' => 'FooBar',
					),
					'ResourceModuleSkinStyles' => array(
						'foobar' => array(
							'test.foo' => 'foo.css',
							'remoteSkinPath' => 'BarFoo'
						),
					),
				),
				// Expected
				array(
					'wgResourceModuleSkinStyles' => array(
						'foobar' => array(
							'test.foo' => 'foo.css',
							'localBasePath' => $dir,
							'remoteSkinPath' => 'BarFoo',
						),
					),
				),
			),
		);
	}

	public static function provideSetToGlobal() {
		return array(
			array(
				array( 'wgAPIModules', 'wgAvailableRights' ),
				array(),
				array(
					'APIModules' => array( 'foobar' => 'ApiFooBar' ),
					'AvailableRights' => array( 'foobar', 'unfoobar' ),
				),
				array(
					'wgAPIModules' => array( 'foobar' => 'ApiFooBar' ),
					'wgAvailableRights' => array( 'foobar', 'unfoobar' ),
				),
			),
			array(
				array( 'wgAPIModules', 'wgAvailableRights' ),
				array(
					'wgAPIModules' => array( 'barbaz' => 'ApiBarBaz' ),
					'wgAvailableRights' => array( 'barbaz' )
				),
				array(
					'APIModules' => array( 'foobar' => 'ApiFooBar' ),
					'AvailableRights' => array( 'foobar', 'unfoobar' ),
				),
				array(
					'wgAPIModules' => array( 'barbaz' => 'ApiBarBaz', 'foobar' => 'ApiFooBar' ),
					'wgAvailableRights' => array( 'barbaz', 'foobar', 'unfoobar' ),
				),
			),
			array(
				array( 'wgGroupPermissions' ),
				array(
					'wgGroupPermissions' => array( 'sysop' => array( 'delete' ) ),
				),
				array(
					'GroupPermissions' => array( 'sysop' => array( 'undelete' ), 'user' => array( 'edit' ) ),
				),
				array(
					'wgGroupPermissions' => array( 'sysop' => array( 'delete', 'undelete' ), 'user' => array( 'edit' ) ),
				)
			)
		);
	}
}


/**
 * Allow overriding the default value of $this->globals
 * so we can test merging
 */
class MockExtensionProcessor extends ExtensionProcessor {
	public function __construct( $globals = array() ) {
		$this->globals = $globals + $this->globals;
	}
}
