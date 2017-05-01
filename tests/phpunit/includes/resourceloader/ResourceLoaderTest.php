<?php

class ResourceLoaderTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgResourceLoaderLESSImportPaths' => [
				dirname( dirname( __DIR__ ) ) . '/data/less/common',
			],
			'wgResourceLoaderLESSVars' => [
				'foo'  => '2px',
				'Foo' => '#eeeeee',
				'bar' => 5,
			],
		] );
	}

	/**
	 * Ensure the ResourceLoaderRegisterModules hook is called.
	 *
	 * @covers ResourceLoader::__construct
	 */
	public function testConstructRegistrationHook() {
		$resourceLoaderRegisterModulesHook = false;

		$this->setMwGlobals( 'wgHooks', [
			'ResourceLoaderRegisterModules' => [
				function ( &$resourceLoader ) use ( &$resourceLoaderRegisterModulesHook ) {
					$resourceLoaderRegisterModulesHook = true;
				}
			]
		] );

		$unused = new ResourceLoader();
		$this->assertTrue(
			$resourceLoaderRegisterModulesHook,
			'Hook ResourceLoaderRegisterModules called'
		);
	}

	/**
	 * @covers ResourceLoader::register
	 * @covers ResourceLoader::getModule
	 */
	public function testRegisterValid() {
		$module = new ResourceLoaderTestModule();
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( 'test', $module );
		$this->assertEquals( $module, $resourceLoader->getModule( 'test' ) );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterInvalidName() {
		$resourceLoader = new EmptyResourceLoader();
		$this->setExpectedException( 'MWException', "name 'test!invalid' is invalid" );
		$resourceLoader->register( 'test!invalid', new ResourceLoaderTestModule() );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterInvalidType() {
		$resourceLoader = new EmptyResourceLoader();
		$this->setExpectedException( 'MWException', 'ResourceLoader module info type error' );
		$resourceLoader->register( 'test', new stdClass() );
	}

	/**
	 * @covers ResourceLoader::getModuleNames
	 */
	public function testGetModuleNames() {
		// Use an empty one so that core and extension modules don't get in.
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( 'test.foo', new ResourceLoaderTestModule() );
		$resourceLoader->register( 'test.bar', new ResourceLoaderTestModule() );
		$this->assertEquals(
			[ 'test.foo', 'test.bar' ],
			$resourceLoader->getModuleNames()
		);
	}

	/**
	 * @covers ResourceLoader::isModuleRegistered
	 */
	public function testIsModuleRegistered() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', new ResourceLoaderTestModule() );
		$this->assertTrue( $rl->isModuleRegistered( 'test' ) );
		$this->assertFalse( $rl->isModuleRegistered( 'test.unknown' ) );
	}

	/**
	 * @covers ResourceLoader::getModule
	 */
	public function testGetModuleUnknown() {
		$rl = new EmptyResourceLoader();
		$this->assertSame( null, $rl->getModule( 'test' ) );
	}

	/**
	 * @covers ResourceLoader::getModule
	 */
	public function testGetModuleClass() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [ 'class' => ResourceLoaderTestModule::class ] );
		$this->assertInstanceOf(
			ResourceLoaderTestModule::class,
			$rl->getModule( 'test' )
		);
	}

	/**
	 * @covers ResourceLoader::getModule
	 */
	public function testGetModuleClassDefault() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [] );
		$this->assertInstanceOf(
			ResourceLoaderFileModule::class,
			$rl->getModule( 'test' ),
			'Array-style module registrations default to FileModule'
		);
	}

	/**
	 * @covers ResourceLoaderFileModule::compileLessFile
	 */
	public function testLessFileCompilation() {
		$context = $this->getResourceLoaderContext();
		$basePath = __DIR__ . '/../../data/less/module';
		$module = new ResourceLoaderFileModule( [
			'localBasePath' => $basePath,
			'styles' => [ 'styles.less' ],
		] );
		$module->setName( 'test.less' );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
	}

	public static function providePackedModules() {
		return [
			[
				'Example from makePackedModulesString doc comment',
				[ 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' ],
				'foo.bar,baz|bar.baz,quux',
			],
			[
				'Example from expandModuleNames doc comment',
				[ 'jquery.foo', 'jquery.bar', 'jquery.ui.baz', 'jquery.ui.quux' ],
				'jquery.foo,bar|jquery.ui.baz,quux',
			],
			[
				'Regression fixed in r88706 with dotless names',
				[ 'foo', 'bar', 'baz' ],
				'foo,bar,baz',
			],
			[
				'Prefixless modules after a prefixed module',
				[ 'single.module', 'foobar', 'foobaz' ],
				'single.module|foobar,foobaz',
			],
			[
				'Ordering',
				[ 'foo', 'foo.baz', 'baz.quux', 'foo.bar' ],
				'foo|foo.baz,bar|baz.quux',
				[ 'foo', 'foo.baz', 'foo.bar', 'baz.quux' ],
			]
		];
	}

	/**
	 * @dataProvider providePackedModules
	 * @covers ResourceLoader::makePackedModulesString
	 */
	public function testMakePackedModulesString( $desc, $modules, $packed ) {
		$this->assertEquals( $packed, ResourceLoader::makePackedModulesString( $modules ), $desc );
	}

	/**
	 * @dataProvider providePackedModules
	 * @covers ResourceLoaderContext::expandModuleNames
	 */
	public function testExpandModuleNames( $desc, $modules, $packed, $unpacked = null ) {
		$this->assertEquals(
			$unpacked ?: $modules,
			ResourceLoaderContext::expandModuleNames( $packed ),
			$desc
		);
	}

	public static function provideAddSource() {
		return [
			[ 'foowiki', 'https://example.org/w/load.php', 'foowiki' ],
			[ 'foowiki', [ 'loadScript' => 'https://example.org/w/load.php' ], 'foowiki' ],
			[
				[
					'foowiki' => 'https://example.org/w/load.php',
					'bazwiki' => 'https://example.com/w/load.php',
				],
				null,
				[ 'foowiki', 'bazwiki' ]
			]
		];
	}

	/**
	 * @dataProvider provideAddSource
	 * @covers ResourceLoader::addSource
	 * @covers ResourceLoader::getSources
	 */
	public function testAddSource( $name, $info, $expected ) {
		$rl = new ResourceLoader;
		$rl->addSource( $name, $info );
		if ( is_array( $expected ) ) {
			foreach ( $expected as $source ) {
				$this->assertArrayHasKey( $source, $rl->getSources() );
			}
		} else {
			$this->assertArrayHasKey( $expected, $rl->getSources() );
		}
	}

	/**
	 * @covers ResourceLoader::addSource
	 */
	public function testAddSourceDupe() {
		$rl = new ResourceLoader;
		$this->setExpectedException( 'MWException', 'ResourceLoader duplicate source addition error' );
		$rl->addSource( 'foo', 'https://example.org/w/load.php' );
		$rl->addSource( 'foo', 'https://example.com/w/load.php' );
	}

	/**
	 * @covers ResourceLoader::addSource
	 */
	public function testAddSourceInvalid() {
		$rl = new ResourceLoader;
		$this->setExpectedException( 'MWException', 'with no "loadScript" key' );
		$rl->addSource( 'foo',  [ 'x' => 'https://example.org/w/load.php' ] );
	}

	public static function provideLoaderImplement() {
		return [
			[ [
				'title' => 'Implement scripts, styles and messages',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => [ 'css' => [ '.mw-example {}' ] ],
				'messages' => [ 'example' => '' ],
				'templates' => [],

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery, require, module ) {
mw.example();
}, {
    "css": [
        ".mw-example {}"
    ]
}, {
    "example": ""
} );',
			] ],
			[ [
				'title' => 'Implement scripts',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => [],

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery, require, module ) {
mw.example();
} );',
			] ],
			[ [
				'title' => 'Implement styles',

				'name' => 'test.example',
				'scripts' => [],
				'styles' => [ 'css' => [ '.mw-example {}' ] ],

				'expected' => 'mw.loader.implement( "test.example", [], {
    "css": [
        ".mw-example {}"
    ]
} );',
			] ],
			[ [
				'title' => 'Implement scripts and messages',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'messages' => [ 'example' => '' ],

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery, require, module ) {
mw.example();
}, {}, {
    "example": ""
} );',
			] ],
			[ [
				'title' => 'Implement scripts and templates',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'templates' => [ 'example.html' => '' ],

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery, require, module ) {
mw.example();
}, {}, {}, {
    "example.html": ""
} );',
			] ],
			[ [
				'title' => 'Implement unwrapped user script',

				'name' => 'user',
				'scripts' => 'mw.example( 1 );',
				'wrap' => false,

				'expected' => 'mw.loader.implement( "user", "mw.example( 1 );" );',
			] ],
		];
	}

	/**
	 * @dataProvider provideLoaderImplement
	 * @covers ResourceLoader::makeLoaderImplementScript
	 * @covers ResourceLoader::trimArray
	 */
	public function testMakeLoaderImplementScript( $case ) {
		$case += [
			'wrap' => true,
			'styles' => [], 'templates' => [], 'messages' => new XmlJsCode( '{}' )
		];
		ResourceLoader::clearCache();
		$this->setMwGlobals( 'wgResourceLoaderDebug', true );

		$rl = TestingAccessWrapper::newFromClass( 'ResourceLoader' );
		$this->assertEquals(
			$case['expected'],
			$rl->makeLoaderImplementScript(
				$case['name'],
				( $case['wrap'] && is_string( $case['scripts'] ) )
					? new XmlJsCode( $case['scripts'] )
					: $case['scripts'],
				$case['styles'],
				$case['messages'],
				$case['templates']
			)
		);
	}

	/**
	 * @covers ResourceLoader::makeLoaderImplementScript
	 */
	public function testMakeLoaderImplementScriptInvalid() {
		$this->setExpectedException( 'MWException', 'Invalid scripts error' );
		$rl = TestingAccessWrapper::newFromClass( 'ResourceLoader' );
		$rl->makeLoaderImplementScript(
			'test', // name
			123, // scripts
			null, // styles
			null, // messages
			null // templates
		);
	}

	/**
	 * @covers ResourceLoader::makeLoaderSourcesScript
	 */
	public function testMakeLoaderSourcesScript() {
		$this->assertEquals(
			'mw.loader.addSource( "local", "/w/load.php" );',
			ResourceLoader::makeLoaderSourcesScript( 'local', '/w/load.php' )
		);
		$this->assertEquals(
			'mw.loader.addSource( {
    "local": "/w/load.php"
} );',
			ResourceLoader::makeLoaderSourcesScript( [ 'local' => '/w/load.php' ] )
		);
		$this->assertEquals(
			'mw.loader.addSource( {
    "local": "/w/load.php",
    "example": "https://example.org/w/load.php"
} );',
			ResourceLoader::makeLoaderSourcesScript( [
				'local' => '/w/load.php',
				'example' => 'https://example.org/w/load.php'
			] )
		);
		$this->assertEquals(
			'mw.loader.addSource( [] );',
			ResourceLoader::makeLoaderSourcesScript( [] )
		);
	}

	private static function fakeSources() {
		return [
			'examplewiki' => [
				'loadScript' => '//example.org/w/load.php',
				'apiScript' => '//example.org/w/api.php',
			],
			'example2wiki' => [
				'loadScript' => '//example.com/w/load.php',
				'apiScript' => '//example.com/w/api.php',
			],
		];
	}

	/**
	 * @covers ResourceLoader::getLoadScript
	 */
	public function testGetLoadScript() {
		$this->setMwGlobals( 'wgResourceLoaderSources', [] );
		$rl = new ResourceLoader();
		$sources = self::fakeSources();
		$rl->addSource( $sources );
		foreach ( [ 'examplewiki', 'example2wiki' ] as $name ) {
			$this->assertEquals( $rl->getLoadScript( $name ), $sources[$name]['loadScript'] );
		}

		try {
			$rl->getLoadScript( 'thiswasneverreigstered' );
			$this->assertTrue( false, 'ResourceLoader::getLoadScript should have thrown an exception' );
		} catch ( MWException $e ) {
			$this->assertTrue( true );
		}
	}
}
