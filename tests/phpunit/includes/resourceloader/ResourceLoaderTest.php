<?php

use Wikimedia\TestingAccessWrapper;

class ResourceLoaderTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgShowExceptionDetails' => true,
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
	public function testRegisterValidObject() {
		$module = new ResourceLoaderTestModule();
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( 'test', $module );
		$this->assertEquals( $module, $resourceLoader->getModule( 'test' ) );
	}

	/**
	 * @covers ResourceLoader::register
	 * @covers ResourceLoader::getModule
	 */
	public function testRegisterValidArray() {
		$module = new ResourceLoaderTestModule();
		$resourceLoader = new EmptyResourceLoader();
		// Covers case of register() setting $rl->moduleInfos,
		// but $rl->modules lazy-populated by getModule()
		$resourceLoader->register( 'test', [ 'object' => $module ] );
		$this->assertEquals( $module, $resourceLoader->getModule( 'test' ) );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterEmptyString() {
		$module = new ResourceLoaderTestModule();
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( '', $module );
		$this->assertEquals( $module, $resourceLoader->getModule( '' ) );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterInvalidName() {
		$resourceLoader = new EmptyResourceLoader();
		$this->setExpectedException( MWException::class, "name 'test!invalid' is invalid" );
		$resourceLoader->register( 'test!invalid', new ResourceLoaderTestModule() );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterInvalidType() {
		$resourceLoader = new EmptyResourceLoader();
		$this->setExpectedException( MWException::class, 'ResourceLoader module info type error' );
		$resourceLoader->register( 'test', new stdClass() );
	}

	/**
	 * @covers ResourceLoader::register
	 */
	public function testRegisterDuplicate() {
		$logger = $this->getMockBuilder( Psr\Log\LoggerInterface::class )->getMock();
		$logger->expects( $this->once() )
			->method( 'warning' );
		$resourceLoader = new EmptyResourceLoader( null, $logger );

		$module1 = new ResourceLoaderTestModule();
		$module2 = new ResourceLoaderTestModule();
		$resourceLoader->register( 'test', $module1 );
		$resourceLoader->register( 'test', $module2 );
		$this->assertSame( $module2, $resourceLoader->getModule( 'test' ) );
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

	public function provideTestIsFileModule() {
		$fileModuleObj = $this->getMockBuilder( ResourceLoaderFileModule::class )
			->disableOriginalConstructor()
			->getMock();
		return [
			'object' => [ false,
				new ResourceLoaderTestModule()
			],
			'FileModule object' => [ false,
				$fileModuleObj
			],
			'simple empty' => [ true,
				[]
			],
			'simple scripts' => [ true,
				[ 'scripts' => 'example.js' ]
			],
			'simple scripts, raw and targets' => [ true, [
				'scripts' => [ 'a.js', 'b.js' ],
				'raw' => true,
				'targets' => [ 'desktop', 'mobile' ],
			] ],
			'FileModule' => [ true,
				[ 'class' => ResourceLoaderFileModule::class, 'scripts' => 'example.js' ]
			],
			'TestModule' => [ false,
				[ 'class' => ResourceLoaderTestModule::class, 'scripts' => 'example.js' ]
			],
			'SkinModule (FileModule subclass)' => [ true,
				[ 'class' => ResourceLoaderSkinModule::class, 'scripts' => 'example.js' ]
			],
			'WikiModule' => [ false, [
				'class' => ResourceLoaderWikiModule::class,
				'scripts' => [ 'MediaWiki:Example.js' ],
			] ],
		];
	}

	/**
	 * @dataProvider provideTestIsFileModule
	 * @covers ResourceLoader::isFileModule
	 */
	public function testIsFileModule( $expected, $module ) {
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader() );
		$rl->register( 'test', $module );
		$this->assertSame( $expected, $rl->isFileModule( 'test' ) );
	}

	/**
	 * @covers ResourceLoader::isFileModule
	 */
	public function testIsFileModuleUnknown() {
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader() );
		$this->assertSame( false, $rl->isFileModule( 'unknown' ) );
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
	public function testGetModuleFactory() {
		$factory = function ( array $info ) {
			$this->assertArrayHasKey( 'kitten', $info );
			return new ResourceLoaderTestModule( $info );
		};

		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [ 'factory' => $factory, 'kitten' => 'little ball of fur' ] );
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
	 * @covers ResourceLoader::getLessCompiler
	 */
	public function testLessImportDirs() {
		$rl = new EmptyResourceLoader();
		$lc = $rl->getLessCompiler( [ 'foo'  => '2px', 'Foo' => '#eeeeee' ] );
		$basePath = dirname( dirname( __DIR__ ) ) . '/data/less';
		$lc->SetImportDirs( [
			 "$basePath/common" => '',
		] );
		$css = $lc->parseFile( "$basePath/module/use-import-dir.less" )->getCss();
		$this->assertStringEqualsFile( "$basePath/module/styles.css", $css );
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
				'Regression fixed in r87497 (7fee86c38e) with dotless names',
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
	 * @covers ResourceLoader::expandModuleNames
	 */
	public function testExpandModuleNames( $desc, $modules, $packed, $unpacked = null ) {
		$this->assertEquals(
			$unpacked ?: $modules,
			ResourceLoader::expandModuleNames( $packed ),
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
		$this->setExpectedException(
			MWException::class, 'ResourceLoader duplicate source addition error'
		);
		$rl->addSource( 'foo', 'https://example.org/w/load.php' );
		$rl->addSource( 'foo', 'https://example.com/w/load.php' );
	}

	/**
	 * @covers ResourceLoader::addSource
	 */
	public function testAddSourceInvalid() {
		$rl = new ResourceLoader;
		$this->setExpectedException( MWException::class, 'with no "loadScript" key' );
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
			[ [
				'title' => 'Implement multi-file script',

				'name' => 'test.multifile',
				'scripts' => [
					'files' => [
						'one.js' => [
							'type' => 'script',
							'content' => 'mw.example( 1 );',
						],
						'two.json' => [
							'type' => 'data',
							'content' => [ 'n' => 2 ],
						],
						'three.js' => [
							'type' => 'script',
							'content' => 'mw.example( 3 );'
						],
					],
					'main' => 'three.js',
				],

				'expected' => <<<END
mw.loader.implement( "test.multifile", {
    "main": "three.js",
    "files": {
    "one.js": function ( require, module ) {
mw.example( 1 );
},
    "two.json": {
    "n": 2
},
    "three.js": function ( require, module ) {
mw.example( 3 );
}
}
} );
END
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
			'styles' => [], 'templates' => [], 'messages' => new XmlJsCode( '{}' ), 'packageFiles' => [],
		];
		ResourceLoader::clearCache();
		$this->setMwGlobals( 'wgResourceLoaderDebug', true );

		$rl = TestingAccessWrapper::newFromClass( ResourceLoader::class );
		$this->assertEquals(
			$case['expected'],
			$rl->makeLoaderImplementScript(
				$case['name'],
				( $case['wrap'] && is_string( $case['scripts'] ) )
					? new XmlJsCode( $case['scripts'] )
					: $case['scripts'],
				$case['styles'],
				$case['messages'],
				$case['templates'],
				$case['packageFiles']
			)
		);
	}

	/**
	 * @covers ResourceLoader::makeLoaderImplementScript
	 */
	public function testMakeLoaderImplementScriptInvalid() {
		$this->setExpectedException( MWException::class, 'Invalid scripts error' );
		$rl = TestingAccessWrapper::newFromClass( ResourceLoader::class );
		$rl->makeLoaderImplementScript(
			'test', // name
			123, // scripts
			null, // styles
			null, // messages
			null, // templates
			null // package files
		);
	}

	/**
	 * @covers ResourceLoader::makeLoaderRegisterScript
	 */
	public function testMakeLoaderRegisterScript() {
		$this->assertEquals(
			'mw.loader.register( [
    [
        "test.name",
        "1234567"
    ]
] );',
			ResourceLoader::makeLoaderRegisterScript( [
				[ 'test.name', '1234567' ],
			] ),
			'Nested array parameter'
		);

		$this->assertEquals(
			'mw.loader.register( [
    [
        "test.foo",
        "100"
    ],
    [
        "test.bar",
        "200",
        [
            "test.unknown"
        ]
    ],
    [
        "test.baz",
        "300",
        [
            3,
            0
        ]
    ],
    [
        "test.quux",
        "400",
        [],
        null,
        null,
        "return true;"
    ]
] );',
			ResourceLoader::makeLoaderRegisterScript( [
				[ 'test.foo', '100' , [], null, null ],
				[ 'test.bar', '200', [ 'test.unknown' ], null ],
				[ 'test.baz', '300', [ 'test.quux', 'test.foo' ], null ],
				[ 'test.quux', '400', [], null, null, 'return true;' ],
			] ),
			'Compact dependency indexes'
		);
	}

	/**
	 * @covers ResourceLoader::makeLoaderSourcesScript
	 */
	public function testMakeLoaderSourcesScript() {
		$this->assertEquals(
			'mw.loader.addSource( {
    "local": "/w/load.php"
} );',
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

	protected function getFailFerryMock( $getter = 'getScript' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ $getter ] )
			->getMock();
		$mock->method( $getter )->will( $this->throwException(
			new Exception( 'Ferry not found' )
		) );
		return $mock;
	}

	protected function getSimpleModuleMock( $script = '' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ 'getScript' ] )
			->getMock();
		$mock->method( 'getScript' )->willReturn( $script );
		return $mock;
	}

	protected function getSimpleStyleModuleMock( $styles = '' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ 'getStyles' ] )
			->getMock();
		$mock->method( 'getStyles' )->willReturn( [ '' => $styles ] );
		return $mock;
	}

	/**
	 * @covers ResourceLoader::getCombinedVersion
	 */
	public function testGetCombinedVersion() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			// Disable log from outputErrorAndLog
			->setMethods( [ 'outputErrorAndLog' ] )->getMock();
		$rl->register( [
			'foo' => self::getSimpleModuleMock(),
			'ferry' => self::getFailFerryMock(),
			'bar' => self::getSimpleModuleMock(),
		] );
		$context = $this->getResourceLoaderContext( [], $rl );

		$this->assertEquals(
			'',
			$rl->getCombinedVersion( $context, [] ),
			'empty list'
		);

		$this->assertEquals(
			ResourceLoader::makeHash( self::BLANK_VERSION ),
			$rl->getCombinedVersion( $context, [ 'foo' ] ),
			'compute foo'
		);

		// Verify that getCombinedVersion() does not throw when ferry fails.
		// Instead it gracefully continues to combine the remaining modules.
		$this->assertEquals(
			ResourceLoader::makeHash( self::BLANK_VERSION . self::BLANK_VERSION ),
			$rl->getCombinedVersion( $context, [ 'foo', 'ferry', 'bar' ] ),
			'compute foo+ferry+bar (T152266)'
		);
	}

	public static function provideMakeModuleResponseConcat() {
		$testcases = [
			[
				'modules' => [
					'foo' => 'foo()',
				],
				'expected' => "foo()\n" . 'mw.loader.state( {
    "foo": "ready"
} );',
				'minified' => "foo()\n" . 'mw.loader.state({"foo":"ready"});',
				'message' => 'Script without semi-colon',
			],
			[
				'modules' => [
					'foo' => 'foo()',
					'bar' => 'bar()',
				],
				'expected' => "foo()\nbar()\n" . 'mw.loader.state( {
    "foo": "ready",
    "bar": "ready"
} );',
				'minified' => "foo()\nbar()\n" . 'mw.loader.state({"foo":"ready","bar":"ready"});',
				'message' => 'Two scripts without semi-colon',
			],
			[
				'modules' => [
					'foo' => "foo()\n// bar();"
				],
				'expected' => "foo()\n// bar();\n" . 'mw.loader.state( {
    "foo": "ready"
} );',
				'minified' => "foo()\n" . 'mw.loader.state({"foo":"ready"});',
				'message' => 'Script with semi-colon in comment (T162719)',
			],
		];
		$ret = [];
		foreach ( $testcases as $i => $case ) {
			$ret["#$i"] = [
				$case['modules'],
				$case['expected'],
				true, // debug
				$case['message'],
			];
			$ret["#$i (minified)"] = [
				$case['modules'],
				$case['minified'],
				false, // debug
				$case['message'],
			];
		}
		return $ret;
	}

	/**
	 * Verify how multiple scripts and mw.loader.state() calls are concatenated.
	 *
	 * @dataProvider provideMakeModuleResponseConcat
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseConcat( $scripts, $expected, $debug, $message = null ) {
		$rl = new EmptyResourceLoader();
		$modules = array_map( function ( $script ) {
			return self::getSimpleModuleMock( $script );
		}, $scripts );
		$rl->register( $modules );

		$context = $this->getResourceLoaderContext(
			[
				'modules' => implode( '|', array_keys( $modules ) ),
				'only' => 'scripts',
				'debug' => $debug ? 'true' : 'false',
			],
			$rl
		);

		$response = $rl->makeModuleResponse( $context, $modules );
		$this->assertSame( [], $rl->getErrors(), 'Errors' );
		$this->assertEquals( $expected, $response, $message ?: 'Response' );
	}

	/**
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseEmpty() {
		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => '', 'only' => 'scripts' ],
			$rl
		);

		$response = $rl->makeModuleResponse( $context, [] );
		$this->assertSame( [], $rl->getErrors(), 'Errors' );
		$this->assertRegExp( '/^\/\*.+no modules were requested.+\*\/$/ms', $response );
	}

	/**
	 * Verify that when building module content in a load.php response,
	 * an exception from one module will not break script output from
	 * other modules.
	 *
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseError() {
		$modules = [
			'foo' => self::getSimpleModuleMock( 'foo();' ),
			'ferry' => self::getFailFerryMock(),
			'bar' => self::getSimpleModuleMock( 'bar();' ),
		];
		$rl = new EmptyResourceLoader();
		$rl->register( $modules );
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'foo|ferry|bar',
				'only' => 'scripts',
			],
			$rl
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new Psr\Log\NullLogger() );

		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertCount( 1, $errors );
		$this->assertRegExp( '/Ferry not found/', $errors[0] );
		$this->assertEquals(
			"foo();\nbar();\n" . 'mw.loader.state( {
    "ferry": "error",
    "foo": "ready",
    "bar": "ready"
} );',
			$response
		);
	}

	/**
	 * Verify that exceptions in PHP for one module will not break others
	 * (stylesheet response).
	 *
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseErrorCSS() {
		$modules = [
			'foo' => self::getSimpleStyleModuleMock( '.foo{}' ),
			'ferry' => self::getFailFerryMock( 'getStyles' ),
			'bar' => self::getSimpleStyleModuleMock( '.bar{}' ),
		];
		$rl = new EmptyResourceLoader();
		$rl->register( $modules );
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'foo|ferry|bar',
				'only' => 'styles',
				'debug' => 'false',
			],
			$rl
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new Psr\Log\NullLogger() );

		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertCount( 2, $errors );
		$this->assertRegExp( '/Ferry not found/', $errors[0] );
		$this->assertRegExp( '/Problem.+"ferry":\s*"error"/ms', $errors[1] );
		$this->assertEquals(
			'.foo{}.bar{}',
			$response
		);
	}

	/**
	 * Verify that when building the startup module response,
	 * an exception from one module class will not break the entire
	 * startup module response. See T152266.
	 *
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseStartupError() {
		$rl = new EmptyResourceLoader();
		$rl->register( [
			'foo' => self::getSimpleModuleMock( 'foo();' ),
			'ferry' => self::getFailFerryMock(),
			'bar' => self::getSimpleModuleMock( 'bar();' ),
			'startup' => [ 'class' => ResourceLoaderStartUpModule::class ],
		] );
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'startup',
				'only' => 'scripts',
			],
			$rl
		);

		$this->assertEquals(
			[ 'foo', 'ferry', 'bar', 'startup' ],
			$rl->getModuleNames(),
			'getModuleNames'
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new Psr\Log\NullLogger() );

		$modules = [ 'startup' => $rl->getModule( 'startup' ) ];
		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertRegExp( '/Ferry not found/', $errors[0] );
		$this->assertCount( 1, $errors );
		$this->assertRegExp(
			'/isCompatible.*window\.RLQ/s',
			$response,
			'startup response undisrupted (T152266)'
		);
		$this->assertRegExp(
			'/register\([^)]+"ferry",\s*""/s',
			$response,
			'startup response registers broken module'
		);
		$this->assertRegExp(
			'/state\([^)]+"ferry":\s*"error"/s',
			$response,
			'startup response sets state to error'
		);
	}

	/**
	 * Integration test for modules sending extra HTTP response headers.
	 *
	 * @covers ResourceLoaderModule::getHeaders
	 * @covers ResourceLoaderModule::buildContent
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseExtraHeaders() {
		$module = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ 'getPreloadLinks' ] )->getMock();
		$module->method( 'getPreloadLinks' )->willReturn( [
			 'https://example.org/script.js' => [ 'as' => 'script' ],
		] );

		$rl = new EmptyResourceLoader();
		$rl->register( [
			'foo' => $module,
		] );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'foo', 'only' => 'scripts' ],
			$rl
		);

		$modules = [ 'foo' => $rl->getModule( 'foo' ) ];
		$response = $rl->makeModuleResponse( $context, $modules );
		$extraHeaders = TestingAccessWrapper::newFromObject( $rl )->extraHeaders;

		$this->assertEquals(
			[
				'Link: <https://example.org/script.js>;rel=preload;as=script'
			],
			$extraHeaders,
			'Extra headers'
		);
	}

	/**
	 * @covers ResourceLoaderModule::getHeaders
	 * @covers ResourceLoaderModule::buildContent
	 * @covers ResourceLoader::makeModuleResponse
	 */
	public function testMakeModuleResponseExtraHeadersMulti() {
		$foo = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ 'getPreloadLinks' ] )->getMock();
		$foo->method( 'getPreloadLinks' )->willReturn( [
			 'https://example.org/script.js' => [ 'as' => 'script' ],
		] );

		$bar = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->setMethods( [ 'getPreloadLinks' ] )->getMock();
		$bar->method( 'getPreloadLinks' )->willReturn( [
			 '/example.png' => [ 'as' => 'image' ],
			 '/example.jpg' => [ 'as' => 'image' ],
		] );

		$rl = new EmptyResourceLoader();
		$rl->register( [ 'foo' => $foo, 'bar' => $bar ] );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'foo|bar', 'only' => 'scripts' ],
			$rl
		);

		$modules = [ 'foo' => $rl->getModule( 'foo' ), 'bar' => $rl->getModule( 'bar' ) ];
		$response = $rl->makeModuleResponse( $context, $modules );
		$extraHeaders = TestingAccessWrapper::newFromObject( $rl )->extraHeaders;
		$this->assertEquals(
			[
				'Link: <https://example.org/script.js>;rel=preload;as=script',
				'Link: </example.png>;rel=preload;as=image,</example.jpg>;rel=preload;as=image'
			],
			$extraHeaders,
			'Extra headers'
		);
	}

	/**
	 * @covers ResourceLoader::respond
	 */
	public function testRespondEmpty() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->setMethods( [
				'tryRespondNotModified',
				'sendResponseHeaders',
				'measureResponseTime',
			] )
			->getMock();
		$context = $this->getResourceLoaderContext( [ 'modules' => '' ], $rl );

		$rl->expects( $this->once() )->method( 'measureResponseTime' );
		$this->expectOutputRegex( '/no modules were requested/' );

		$rl->respond( $context );
	}

	/**
	 * @covers ResourceLoader::respond
	 */
	public function testRespondSimple() {
		$module = new ResourceLoaderTestModule( [ 'script' => 'foo();' ] );
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->setMethods( [
				'measureResponseTime',
				'tryRespondNotModified',
				'sendResponseHeaders',
				'makeModuleResponse',
			] )
			->getMock();
		$rl->register( 'test', $module );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test', 'only' => null ],
			$rl
		);

		$rl->expects( $this->once() )->method( 'makeModuleResponse' )
			->with( $context, [ 'test' => $module ] )
			->willReturn( 'implement_foo;' );
		$this->expectOutputRegex( '/^implement_foo;/' );

		$rl->respond( $context );
	}

	/**
	 * @covers ResourceLoader::respond
	 */
	public function testRespondInternalFailures() {
		$module = new ResourceLoaderTestModule( [ 'script' => 'foo();' ] );
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->setMethods( [
				'measureResponseTime',
				'preloadModuleInfo',
				'getCombinedVersion',
				'tryRespondNotModified',
				'makeModuleResponse',
				'sendResponseHeaders',
			] )
			->getMock();
		$rl->register( 'test', $module );
		$context = $this->getResourceLoaderContext( [ 'modules' => 'test' ], $rl );
		// Disable logging from outputErrorAndLog
		$this->setLogger( 'exception', new Psr\Log\NullLogger() );

		$rl->expects( $this->once() )->method( 'preloadModuleInfo' )
			->willThrowException( new Exception( 'Preload error' ) );
		$rl->expects( $this->once() )->method( 'getCombinedVersion' )
			->willThrowException( new Exception( 'Version error' ) );
		$rl->expects( $this->once() )->method( 'makeModuleResponse' )
			->with( $context, [ 'test' => $module ] )
			->willReturn( 'foo;' );
		// Internal errors should be caught and logged without affecting module output
		$this->expectOutputRegex( '/^\/\*.+Preload error.+Version error.+\*\/.*foo;/ms' );

		$rl->respond( $context );
	}

	/**
	 * @covers ResourceLoader::measureResponseTime
	 */
	public function testMeasureResponseTime() {
		$stats = $this->getMockBuilder( NullStatsdDataFactory::class )
			->setMethods( [ 'timing' ] )->getMock();
		$this->setService( 'StatsdDataFactory', $stats );

		$stats->expects( $this->once() )->method( 'timing' )
			->with( 'resourceloader.responseTime', $this->anything() );

		$timing = new Timing();
		$timing->mark( 'requestShutdown' );
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader );
		$rl->measureResponseTime( $timing );
		DeferredUpdates::doUpdates();
	}
}
