<?php

namespace MediaWiki\Tests\ResourceLoader;

use Exception;
use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Html\HtmlJsCode;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\ResourceLoader\StartUpModule;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\Minify\IdentityMinifierState;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\ResourceLoader
 */
class ResourceLoaderTest extends ResourceLoaderTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowExceptionDetails, true );
	}

	/**
	 * Ensure the ResourceLoaderRegisterModules hook is called.
	 * @coversNothing
	 */
	public function testServiceWiring() {
		$ranHook = 0;
		$this->setTemporaryHook(
			'ResourceLoaderRegisterModules',
			static function ( &$resourceLoader ) use ( &$ranHook ) {
				$ranHook++;
			}
		);

		$this->getServiceContainer()->getResourceLoader();

		$this->assertSame( 1, $ranHook, 'Hook was called' );
	}

	public static function provideInvalidModuleName() {
		return [
			'name with 300 chars' => [ str_repeat( 'x', 300 ) ],
			'name with bang' => [ 'this!that' ],
			'name with comma' => [ 'this,that' ],
			'name with pipe' => [ 'this|that' ],
		];
	}

	public static function provideValidModuleName() {
		return [
			'empty string' => [ '' ],
			'simple name' => [ 'this.and-that2' ],
			'name with 100 chars' => [ str_repeat( 'x', 100 ) ],
			'name with hash' => [ 'this#that' ],
			'name with slash' => [ 'this/that' ],
			'name with at' => [ 'this@that' ],
		];
	}

	/**
	 * @dataProvider provideInvalidModuleName
	 */
	public function testIsValidModuleName_invalid( $name ) {
		$this->assertFalse( ResourceLoader::isValidModuleName( $name ) );
	}

	/**
	 * @dataProvider provideValidModuleName
	 */
	public function testIsValidModuleName_valid( $name ) {
		$this->assertTrue( ResourceLoader::isValidModuleName( $name ) );
	}

	public function testRegisterValidArray() {
		$resourceLoader = new EmptyResourceLoader();
		// Covers case of register() setting $rl->moduleInfos,
		// but $rl->modules lazy-populated by getModule()
		$resourceLoader->register( 'test', [ 'class' => ResourceLoaderTestModule::class ] );
		$this->assertInstanceOf(
			ResourceLoaderTestModule::class,
			$resourceLoader->getModule( 'test' )
		);
	}

	/**
	 * @group medium
	 */
	public function testRegisterEmptyString() {
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( '', [ 'class' => ResourceLoaderTestModule::class ] );
		$this->assertInstanceOf(
			ResourceLoaderTestModule::class,
			$resourceLoader->getModule( '' )
		);
	}

	/**
	 * @group medium
	 */
	public function testRegisterInvalidName() {
		$resourceLoader = new EmptyResourceLoader();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "name 'test!invalid' is invalid" );
		$resourceLoader->register( 'test!invalid', [] );
	}

	public function testRegisterInvalidNameStartingWithDot() {
		$resourceLoader = new EmptyResourceLoader();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "name '../test' is invalid" );
		$resourceLoader->register( '../test', [] );
	}

	public function testRegisterInvalidType() {
		$resourceLoader = new EmptyResourceLoader();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid module info' );
		$resourceLoader->register( [ 'test' => (object)[] ] );
	}

	public function testRegisterDuplicate() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )
			->method( 'warning' );
		$resourceLoader = new EmptyResourceLoader( null, $logger );

		$resourceLoader->register( 'test', [ 'class' => SkinModule::class ] );
		$resourceLoader->register( 'test', [ 'class' => StartUpModule::class ] );
		$this->assertInstanceOf(
			StartUpModule::class,
			$resourceLoader->getModule( 'test' ),
			'last one wins'
		);
	}

	public function testGetModuleNames() {
		// Use an empty one so that core and extension modules don't get in.
		$resourceLoader = new EmptyResourceLoader();
		$resourceLoader->register( 'test.foo', [] );
		$resourceLoader->register( 'test.bar', [] );
		$this->assertEquals(
			[ 'startup', 'test.foo', 'test.bar' ],
			$resourceLoader->getModuleNames()
		);
	}

	public function testIsModuleRegistered() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [] );
		$this->assertTrue( $rl->isModuleRegistered( 'test' ) );
		$this->assertFalse( $rl->isModuleRegistered( 'test.unknown' ) );
	}

	public function testGetModuleUnknown() {
		$rl = new EmptyResourceLoader();
		$this->assertSame( null, $rl->getModule( 'test' ) );
	}

	public function testGetModuleClass() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [ 'class' => ResourceLoaderTestModule::class ] );
		$this->assertInstanceOf(
			ResourceLoaderTestModule::class,
			$rl->getModule( 'test' )
		);
	}

	public function testGetModuleFactory() {
		$factory = function ( array $info ) {
			$this->assertArrayHasKey( 'kitten', $info );
			unset( $info['kitten'] );
			return new ResourceLoaderTestModule( $info );
		};

		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [ 'factory' => $factory, 'kitten' => 'little ball of fur' ] );
		$this->assertInstanceOf(
			ResourceLoaderTestModule::class,
			$rl->getModule( 'test' )
		);
	}

	public function testGetModuleClassDefault() {
		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [] );
		$this->assertInstanceOf(
			FileModule::class,
			$rl->getModule( 'test' ),
			'Array-style module registrations default to FileModule'
		);
	}

	public function testGetVersionHash_length() {
		$hash = ResourceLoader::makeHash(
			'Anything you do could have serious repercussions on future events.'
		);
		$this->assertSame( 'xhh1x', $hash, 'Hash' );
		$this->assertSame( ResourceLoader::HASH_LENGTH, strlen( $hash ), 'Hash length' );
	}

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

	public static function provideLessImportRemappingCases() {
		$basePath = dirname( dirname( __DIR__ ) ) . '/data/less';
		return [
			[
				'input' => "$basePath/import-codex-icons.less",
				'expected' => "$basePath/import-codex-icons.css"
			],
			[
				'input' => "$basePath/import-codex-icons.less",
				'expected' => "$basePath/import-codex-icons-devmode.css",
				'exception' => null,
				'devmode' => true
			],
			[
				'input' => "$basePath/import-codex-tokens.less",
				'expected' => "$basePath/import-codex-tokens.css"
			],
			[
				'input' => "$basePath/import-codex-tokens.less",
				'expected' => "$basePath/import-codex-tokens-devmode.css",
				'exception' => null,
				'devmode' => true
			],
			[
				'input' => "$basePath/import-codex-tokens-npm.less",
				'expected' => null,
				'exception' => [
					'class' => Exception::class,
					'message' => 'Importing from @wikimedia/codex-design-tokens is not supported. ' .
						"To use the Codex tokens, use `@import 'mediawiki.skin.variables.less';` instead."
				]
			]
		];
	}

	/**
	 * @dataProvider provideLessImportRemappingCases
	 */
	public function testLessImportRemapping( $input, $expected, $exception = null, $devmode = false ) {
		$configOverrides = [];
		if ( $devmode ) {
			$devDir = MW_INSTALL_PATH . '/tests/phpunit/data/resourceloader/codex-devmode';
			$configOverrides += [
				MainConfigNames::CodexDevelopmentDir => $devDir
			];
		}

		$this->overrideConfigValues( $configOverrides );
		// Unfortunately the EmptyResourceLoader constructor doesn't pick up the overridden config
		// values, we have to do that separately
		$baseConfig = static::getSettings();
		$rl = new EmptyResourceLoader( new HashConfig( $configOverrides + $baseConfig ) );
		$lc = $rl->getLessCompiler();

		if ( $exception !== null ) {
			if ( isset( $exception['class'] ) ) {
				$this->expectException( $exception['class'] );
			}
			if ( isset( $exception['message'] ) ) {
				$this->expectExceptionMessage( $exception['message'] );
			}
		}

		$css = $lc->parseFile( $input )->getCss();
		if ( $expected !== null ) {
			$this->assertStringEqualsFile( $expected, $css );
		}
	}

	public static function provideMediaWikiVariablesCases() {
		$basePath = __DIR__ . '/../../data/less';
		return [
			[
				'config' => [],
				'importPaths' => [],
				'skin' => 'fallback',
				'expected' => "$basePath/use-variables-default.css",
			],
			[
				'config' => [
					MainConfigNames::ValidSkinNames => [
						// Required to make Context::getSkin work
						'example' => 'Example',
					],
				],
				'importPaths' => [
					'example' => "$basePath/testvariables/",
				],
				'skin' => 'example',
				'expected' => "$basePath/use-variables-test.css",
			]
		];
	}

	/**
	 * @dataProvider provideMediaWikiVariablesCases
	 */
	public function testMediaWikiVariablesDefault( array $config, array $importPaths, $skin, $expectedFile ) {
		$this->overrideConfigValues( $config );
		$reset = ExtensionRegistry::getInstance()->setAttributeForTest( 'SkinLessImportPaths', $importPaths );

		$context = $this->getResourceLoaderContext( [ 'skin' => $skin ] );
		$module = new FileModule( [
			'localBasePath' => __DIR__ . '/../../data/less',
			'styles' => [ 'use-variables.less' ],
		] );
		$module->setConfig( $context->getResourceLoader()->getConfig() );
		$module->setName( 'test.less' );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $expectedFile, $styles['all'] );
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
	 */
	public function testMakePackedModulesString( $desc, $modules, $packed ) {
		$this->assertEquals( $packed, ResourceLoader::makePackedModulesString( $modules ), $desc );
	}

	/**
	 * @dataProvider providePackedModules
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
	 */
	public function testAddSource( $name, $info, $expected ) {
		$rl = new EmptyResourceLoader;
		$rl->addSource( $name, $info );
		if ( is_array( $expected ) ) {
			foreach ( $expected as $source ) {
				$this->assertArrayHasKey( $source, $rl->getSources() );
			}
		} else {
			$this->assertArrayHasKey( $expected, $rl->getSources() );
		}
	}

	public function testAddSourceDupe() {
		$rl = new EmptyResourceLoader;
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Cannot register source' );
		$rl->addSource( 'foo', 'https://example.org/w/load.php' );
		$rl->addSource( 'foo', 'https://example.com/w/load.php' );
	}

	public function testAddSourceInvalid() {
		$rl = new EmptyResourceLoader;
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'must have a "loadScript" key' );
		$rl->addSource( 'foo', [ 'x' => 'https://example.org/w/load.php' ] );
	}

	public static function provideLoaderImplement() {
		return [
			'Implement scripts, styles and messages' => [ [
				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => [ 'css' => [ '.mw-example {}' ] ],
				'messages' => [ 'example' => '' ],
				'templates' => [],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();
},{"css":[".mw-example {}"]},{"example":""}];});',
			] ],
			'Implement scripts' => [ [
				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => [],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();
}];});',
			] ],
			'Implement scripts with newline at end' => [ [
				'name' => 'test.example',
				'scripts' => "mw.example();\n",
				'styles' => [],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();
}];});',
			] ],
			'Implement scripts with comment at end' => [ [
				'name' => 'test.example',
				'scripts' => "mw.example();//Foo",
				'styles' => [],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();//Foo
}];});',
			] ],
			'Implement styles' => [ [
				'name' => 'test.example',
				'scripts' => [],
				'styles' => [ 'css' => [ '.mw-example {}' ] ],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",[],{"css":[".mw-example {}"]}];});',
			] ],
			'Implement scripts and messages' => [ [
				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'messages' => [ 'example' => '' ],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();
},{},{"example":""}];});',
			] ],
			'Implement scripts and templates' => [ [
				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'templates' => [ 'example.html' => '' ],

				'expected' => 'mw.loader.impl(function(){return["test.example@1",function($,jQuery,require,module){mw.example();
},{},{},{"example.html":""}];});',
			] ],
			'Implement unwrapped user script' => [ [
				'name' => 'user',
				'scripts' => 'mw.example( 1 );',
				'wrap' => false,

				'expected' => 'mw.loader.impl(function(){return["user@1","mw.example( 1 );"];});',
			] ],
			'Implement multi-file script' => [ [
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
							'content' => 'mw.example( 3 ); // Comment'
						],
						'four.js' => [
							'type' => 'script',
							'content' => "mw.example( 4 );\n"
						],
						'five.js' => [
							'type' => 'script',
							'content' => 'mw.example( 5 );'
						],
					],
					'main' => 'five.js',
				],

				'expected' => <<<END
mw.loader.impl(function(){return["test.multifile@1",{"main":"five.js","files":{"one.js":function(require,module,exports){mw.example( 1 );
},"two.json":{
    "n": 2
},"three.js":function(require,module,exports){mw.example( 3 ); // Comment
},"four.js":function(require,module,exports){mw.example( 4 );
},"five.js":function(require,module,exports){mw.example( 5 );
}}}];});
END
			] ],
		];
	}

	/**
	 * @dataProvider provideLoaderImplement
	 */
	public function testAddImplementScript( $case ) {
		$case += [
			'version' => '1',
			'wrap' => true,
			'styles' => [],
			'templates' => [],
			'messages' => new HtmlJsCode( '{}' ),
			'packageFiles' => [],
		];
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader );
		$minifier = new IdentityMinifierState;
		$rl->addImplementScript(
			$minifier,
			$case['name'],
			$case['version'],
			( $case['wrap'] && is_string( $case['scripts'] ) )
				? [ 'plainScripts' => [ [ 'content' => $case['scripts'] ] ] ]
				: $case['scripts'],
			$case['styles'],
			$case['messages'],
			$case['templates'],
			$case['packageFiles']
		);
		$this->assertEquals( $case['expected'], $minifier->getMinifiedOutput() );
	}

	public function testAddImplementScriptInvalid() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Script must be a' );
		$minifier = new IdentityMinifierState;
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader );
		$rl->addImplementScript(
			$minifier,
			'test', // name
			'1', // version
			123, // scripts
			null, // styles
			null, // messages
			null, // templates
			null // package files
		);
	}

	public function testMakeLoaderRegisterScript() {
		$context = new Context( new EmptyResourceLoader(), new FauxRequest( [
			'debug' => 'true',
		] ) );
		$this->assertEquals(
			'mw.loader.register([
    [
        "test.name",
        "1234567"
    ]
]);',
			ResourceLoader::makeLoaderRegisterScript( $context, [
				[ 'test.name', '1234567' ],
			] ),
			'Nested array parameter'
		);

		$this->assertEquals(
			'mw.loader.register([
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
]);',
			ResourceLoader::makeLoaderRegisterScript( $context, [
				[ 'test.foo', '100', [], null, null ],
				[ 'test.bar', '200', [ 'test.unknown' ], null ],
				[ 'test.baz', '300', [ 'test.quux', 'test.foo' ], null ],
				[ 'test.quux', '400', [], null, null, 'return true;' ],
			] ),
			'Compact dependency indexes'
		);
	}

	public function testMakeLoaderSourcesScript() {
		$context = new Context( new EmptyResourceLoader(), new FauxRequest( [
			'debug' => 'true',
		] ) );
		$this->assertEquals(
			'mw.loader.addSource({
    "local": "/w/load.php"
});',
			ResourceLoader::makeLoaderSourcesScript( $context, [ 'local' => '/w/load.php' ] )
		);
		$this->assertEquals(
			'mw.loader.addSource({
    "local": "/w/load.php",
    "example": "https://example.org/w/load.php"
});',
			ResourceLoader::makeLoaderSourcesScript( $context, [
				'local' => '/w/load.php',
				'example' => 'https://example.org/w/load.php'
			] )
		);
		$this->assertEquals(
			'mw.loader.addSource([]);',
			ResourceLoader::makeLoaderSourcesScript( $context, [] )
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

	public function testGetLoadScript() {
		$rl = new EmptyResourceLoader();
		$sources = self::fakeSources();
		$rl->addSource( $sources );
		foreach ( [ 'examplewiki', 'example2wiki' ] as $name ) {
			$this->assertEquals( $rl->getLoadScript( $name ), $sources[$name]['loadScript'] );
		}

		$this->expectException( UnexpectedValueException::class );
		$rl->getLoadScript( 'thiswasneverregistered' );
	}

	protected function getFailFerryMock( $getter = 'getScript' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ $getter, 'getName' ] )
			->getMock();
		$mock->method( $getter )->willThrowException(
			new Exception( 'Ferry not found' )
		);
		$mock->method( 'getName' )->willReturn( __METHOD__ );
		return $mock;
	}

	protected function getSimpleModuleMock( $script = '' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getScript', 'getName' ] )
			->getMock();
		$mock->method( 'getScript' )->willReturn( $script );
		$mock->method( 'getName' )->willReturn( __METHOD__ );
		return $mock;
	}

	protected function getSimpleStyleModuleMock( $styles = '' ) {
		$mock = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getStyles', 'getName' ] )
			->getMock();
		$mock->method( 'getStyles' )->willReturn( [ '' => $styles ] );
		$mock->method( 'getName' )->willReturn( __METHOD__ );
		return $mock;
	}

	public function testGetCombinedVersion() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			// Disable log from outputErrorAndLog
			->onlyMethods( [ 'outputErrorAndLog' ] )->getMock();
		$rl->register( [
			'foo' => [ 'class' => ResourceLoaderTestModule::class ],
			'ferry' => [
				'factory' => function () {
					return $this->getFailFerryMock();
				}
			],
			'bar' => [ 'class' => ResourceLoaderTestModule::class ],
		] );
		$context = $this->getResourceLoaderContext( [ 'debug' => 'false' ], $rl );

		$this->assertSame(
			'',
			$rl->getCombinedVersion( $context, [] ),
			'empty list'
		);

		$this->assertEquals(
			self::BLANK_COMBI,
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
				'expected' => "foo()\n" . 'mw.loader.state({
    "foo": "ready"
});',
				'minified' => "foo()\n" . 'mw.loader.state({"foo":"ready"});',
				'message' => 'Script without semi-colon',
			],
			[
				'modules' => [
					'foo' => 'foo()',
					'bar' => 'bar()',
				],
				'expected' => "foo()\nbar()\n" . 'mw.loader.state({
    "foo": "ready",
    "bar": "ready"
});',
				'minified' => "foo()\nbar()\n" . 'mw.loader.state({"foo":"ready","bar":"ready"});',
				'message' => 'Two scripts without semi-colon',
			],
			[
				'modules' => [
					'foo' => "foo()\n// bar();"
				],
				'expected' => "foo()\n// bar();\n" . 'mw.loader.state({
    "foo": "ready"
});',
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
	 */
	public function testMakeModuleResponseConcat( $scripts, $expected, $debug, $message = null ) {
		$rl = new EmptyResourceLoader();
		$modules = array_map( function ( $script ) {
			return $this->getSimpleModuleMock( $script );
		}, $scripts );

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

	public function testMakeModuleResponseNomin() {
		// Regression test for FILTER_NOMIN in source-mapped JavaScript code (T373990).
		$rl = new EmptyResourceLoader();
		$rl->register( [
			'test1' => [ 'scripts' => [
					[ 'name' => '1a.js', 'content' => "// Comment\nconsole.log( 'A' );" ],
					[ 'name' => '1b.js', 'content' => "/*@nomin*/\nconsole.log( 'B' );" ],
					[ 'name' => '1c.js', 'content' => "// Comment\nconsole.log( 'C' );" ],
			] ],
			'test2' => [ 'scripts' => [
				[ 'name' => '2a.js', 'content' => "// Comment\nconsole.log( 'A' );" ],
				[ 'name' => '2b.js', 'content' => "// Comment\nconsole.log( 'B' );" ],
				[ 'name' => '2c.js', 'content' => "// Comment\nconsole.log( 'C' );" ],
			] ],
		] );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test1|test2', 'debug' => 'false', 'only' => null ],
			$rl
		);
		$modules = [ 'test1' => $rl->getModule( 'test1' ), 'test2' => $rl->getModule( 'test2' ) ];
		$response = $rl->makeModuleResponse( $context, $modules );

		$expected = <<<JS
mw.loader.impl(function(){return["test1@cv4dm",function($,jQuery,require,module){// Comment
console.log( 'A' );
/*@nomin*/
console.log( 'B' );
// Comment
console.log( 'C' );
}];});
mw.loader.impl(function(){return["test2@1yyxt",function($,jQuery,require,module){console.log('A');
console.log('B');
console.log('C');
}];});

JS;
		$this->assertSame( $expected, $response );
	}

	public function testMakeModuleResponseEmpty() {
		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => '', 'only' => 'scripts' ],
			$rl
		);

		$response = $rl->makeModuleResponse( $context, [] );
		$this->assertSame( [], $rl->getErrors(), 'Errors' );
		$this->assertMatchesRegularExpression( '/^\/\*.+no modules were requested.+\*\/$/ms', $response );
	}

	/**
	 * Verify that when building module content in a load.php response,
	 * an exception from one module will not break script output from
	 * other modules.
	 */
	public function testMakeModuleResponseError() {
		$modules = [
			'foo' => $this->getSimpleModuleMock( 'foo();' ),
			'ferry' => $this->getFailFerryMock(),
			'bar' => $this->getSimpleModuleMock( 'bar();' ),
		];
		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'foo|ferry|bar',
				'only' => 'scripts',
			],
			$rl
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new NullLogger() );

		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertCount( 1, $errors );
		$this->assertMatchesRegularExpression( '/Ferry not found/', $errors[0] );
		$this->assertEquals(
			"foo();\nbar();\n" . 'mw.loader.state({
    "ferry": "error",
    "foo": "ready",
    "bar": "ready"
});',
			$response
		);
	}

	/**
	 * Verify that exceptions in PHP for one module will not break others
	 * (stylesheet response).
	 */
	public function testMakeModuleResponseErrorCSS() {
		$modules = [
			'foo' => self::getSimpleStyleModuleMock( '.foo{}' ),
			'ferry' => $this->getFailFerryMock( 'getStyles' ),
			'bar' => self::getSimpleStyleModuleMock( '.bar{}' ),
		];
		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'foo|ferry|bar',
				'only' => 'styles',
				'debug' => 'false',
			],
			$rl
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new NullLogger() );

		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertCount( 2, $errors );
		$this->assertMatchesRegularExpression( '/Ferry not found/', $errors[0] );
		$this->assertMatchesRegularExpression( '/Problem.+"ferry":\s*"error"/ms', $errors[1] );
		$this->assertEquals(
			'.foo{}.bar{}',
			$response
		);
	}

	/**
	 * Verify that when building the startup module response,
	 * an exception from one module class will not break the entire
	 * startup module response. See T152266.
	 */
	public function testMakeModuleResponseStartupError() {
		// This is an integration test that uses a lot of MediaWiki state,
		// provide the full Config object here.
		$rl = new EmptyResourceLoader( $this->getServiceContainer()->getMainConfig() );
		$rl->register( [
			'foo' => [ 'factory' => function () {
				return $this->getSimpleModuleMock( 'foo();' );
			} ],
			'ferry' => [ 'factory' => function () {
				return $this->getFailFerryMock();
			} ],
			'bar' => [ 'factory' => function () {
				return $this->getSimpleModuleMock( 'bar();' );
			} ],
		] );
		$context = $this->getResourceLoaderContext(
			[
				'modules' => 'startup',
				'only' => 'scripts',
				// No module build for version hash in debug mode
				'debug' => 'false',
			],
			$rl
		);

		$this->assertEquals(
			[ 'startup', 'foo', 'ferry', 'bar' ],
			$rl->getModuleNames(),
			'getModuleNames'
		);

		// Disable log from makeModuleResponse via outputErrorAndLog
		$this->setLogger( 'exception', new NullLogger() );

		$modules = [ 'startup' => $rl->getModule( 'startup' ) ];
		$response = $rl->makeModuleResponse( $context, $modules );
		$errors = $rl->getErrors();

		$this->assertMatchesRegularExpression( '/Ferry not found/', $errors[0] ?? '' );
		$this->assertCount( 1, $errors );
		$this->assertMatchesRegularExpression(
			'/isCompatible.*window\.RLQ/s',
			$response,
			'startup response undisrupted (T152266)'
		);
		$this->assertMatchesRegularExpression(
			'/register\([^)]+"ferry",\s*""/',
			$response,
			'startup response registers broken module'
		);
		$this->assertMatchesRegularExpression(
			'/state\([^)]+"ferry":\s*"error"/',
			$response,
			'startup response sets state to error'
		);
	}

	/**
	 * Integration test for modules sending extra HTTP response headers.
	 */
	public function testMakeModuleResponseExtraHeaders() {
		$module = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getPreloadLinks', 'getName' ] )->getMock();
		$module->method( 'getPreloadLinks' )->willReturn( [
			'https://example.org/script.js' => [ 'as' => 'script' ],
		] );
		$module->method( 'getName' )->willReturn( __METHOD__ );

		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'foo', 'only' => 'scripts' ],
			$rl
		);

		$modules = [ 'foo' => $module ];
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

	public function testMakeModuleResponseExtraHeadersMulti() {
		$foo = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getPreloadLinks', 'getName' ] )->getMock();
		$foo->method( 'getPreloadLinks' )->willReturn( [
			'https://example.org/script.js' => [ 'as' => 'script' ],
		] );
		$foo->method( 'getName' )->willReturn( __METHOD__ );

		$bar = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getPreloadLinks', 'getName' ] )->getMock();
		$bar->method( 'getPreloadLinks' )->willReturn( [
			'/example.png' => [ 'as' => 'image' ],
			'/example.jpg' => [ 'as' => 'image' ],
		] );
		$bar->method( 'getName' )->willReturn( __METHOD__ );

		$rl = new EmptyResourceLoader();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'foo|bar', 'only' => 'scripts' ],
			$rl
		);

		$modules = [ 'foo' => $foo, 'bar' => $bar ];
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

	public function testRespondEmpty() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
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

	public function testRespondSimple() {
		$module = new ResourceLoaderTestModule( [ 'script' => 'foo();' ] );
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
				'measureResponseTime',
				'tryRespondNotModified',
				'sendResponseHeaders',
				'makeModuleResponse',
			] )
			->getMock();
		$rl->register( 'test', [
			'factory' => static function () use ( $module ) {
				return $module;
			}
		] );
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

	public function testRespondMissingModule() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
				'measureResponseTime',
				'tryRespondNotModified',
				'sendResponseHeaders',
			] )
			->getMock();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'unknown', 'only' => null ],
			$rl
		);

		$this->expectOutputRegex( '/mw\.loader\.state.*"unknown": "missing"/s' );

		$rl->respond( $context );
	}

	/**
	 * Silently ignore invalid UTF-8 injected into random query parameters.
	 *
	 * @see https://phabricator.wikimedia.org/T331641
	 */
	public function testRespondInvalidMissingModule() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
				'measureResponseTime',
				'tryRespondNotModified',
				'sendResponseHeaders',
			] )
			->getMock();

		// Cover the JS-response which formats via mw.loader.state()
		$context = $this->getResourceLoaderContext(
			[ 'modules' => "foo|bar\x80\xf0bara|quux", 'only' => null ],
			$rl
		);
		$this->expectOutputRegex( '/mw\.loader\.state.*"foo": "missing"/s' );
		$rl->respond( $context );

		// Cover the CSS-response which formats via a block comment
		$context = $this->getResourceLoaderContext(
			[ 'modules' => "foo|bar\x80\xf0bara|quux", 'only' => 'styles' ],
			$rl
		);
		$this->expectOutputRegex( '/Problematic modules.*"foo": "missing"/s' );
		$rl->respond( $context );
	}

	/**
	 * Refuse requests for private modules.
	 */
	public function testRespondErrorPrivate() {
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
				'measureResponseTime',
				'tryRespondNotModified',
				'sendResponseHeaders',
			] )
			->getMock();
		$rl->register( [
			'foo' => [ 'class' => ResourceLoaderTestModule::class ],
			'bar' => [ 'class' => ResourceLoaderTestModule::class, 'group' => 'private' ],
		] );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'foo|bar', 'only' => null ],
			$rl
		);

		$this->expectOutputRegex( '/\/\*.+Cannot build private module/s' );
		$rl->respond( $context );
	}

	public function testRespondInternalFailures() {
		$module = $this->getMockBuilder( ResourceLoaderTestModule::class )
			->onlyMethods( [ 'getDefinitionSummary', 'enableModuleContentVersion' ] )
			->getMock();
		$module->method( 'enableModuleContentVersion' )
			->willReturn( false );
		$module->method( 'getDefinitionSummary' )
			->willThrowException( new Exception( 'Version error' ) );
		$rl = $this->getMockBuilder( EmptyResourceLoader::class )
			->onlyMethods( [
				'measureResponseTime',
				'preloadModuleInfo',
				'tryRespondNotModified',
				'makeModuleResponse',
				'sendResponseHeaders',
			] )
			->getMock();
		$rl->register( 'test', [
			'factory' => static function () use ( $module ) {
				return $module;
			}
		] );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test', 'debug' => 'false' ],
			$rl
		);
		// Disable logging from outputErrorAndLog
		$this->setLogger( 'exception', new NullLogger() );

		$rl->expects( $this->once() )->method( 'preloadModuleInfo' )
			->willThrowException( new Exception( 'Preload error' ) );
		$rl->expects( $this->once() )->method( 'makeModuleResponse' )
			->with( $context, [ 'test' => $module ] )
			->willReturn( 'foo;' );
		// Internal errors should be caught and logged without affecting module output
		$this->expectOutputRegex( '/foo;.*\/\*.+Preload error.+Version error.+\*\//ms' );

		$rl->respond( $context );
	}

	private function getResourceLoaderWithTestModules( ?Config $config = null ) {
		$localBasePath = __DIR__ . '/../../data/resourceloader';
		$remoteBasePath = '/w';
		$rl = new EmptyResourceLoader( $config );
		$rl->register( 'test1', [
			'localBasePath' => $localBasePath,
			'remoteBasePath' => $remoteBasePath,
			'scripts' => [ 'script-nosemi.js', 'script-comment.js' ],
		] );
		$rl->register( 'test2', [
			'localBasePath' => $localBasePath,
			'remoteBasePath' => $remoteBasePath,
			'scripts' => [ 'script-nosemi-nonl.js' ],
		] );
		return $rl;
	}

	public function testRespondSourceMap() {
		$rl = $this->getResourceLoaderWithTestModules();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test1', 'sourcemap' => '1', 'debug' => '' ],
			$rl
		);
		$this->expectOutputString( <<<JSON
{
"version": 3,
"file": "/load.php?lang=en&modules=test1&only=scripts",
"sources": ["/w/script-nosemi.js","/w/script-comment.js"],
"sourcesContent": ["/* eslint-disable */\\nmw.foo()\\n","/* eslint-disable */\\nmw.foo()\\n// mw.bar();\\n"],
"names": [],
"mappings": "AACA,EAAE,CAAC,GAAG,CAAC;ACAP,EAAE,CAAC,GAAG,CAAC"
}

JSON
		);
		$rl->respond( $context );
	}

	public function testRespondIndexMap() {
		$rl = $this->getResourceLoaderWithTestModules();
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test1|test2', 'sourcemap' => '1', 'debug' => '' ],
			$rl
		);
		$this->expectOutputString( <<<JSON
{
"version": 3,
"sections": [
{"offset":{"line":0,"column":0},"map":{
"version": 3,
"file": "/load.php?lang=en&modules=test1%2Ctest2&only=scripts",
"sources": ["/w/script-nosemi.js","/w/script-comment.js"],
"sourcesContent": ["/* eslint-disable */\\nmw.foo()\\n","/* eslint-disable */\\nmw.foo()\\n// mw.bar();\\n"],
"names": [],
"mappings": "AACA,EAAE,CAAC,GAAG,CAAC;ACAP,EAAE,CAAC,GAAG,CAAC"
}
},
{"offset":{"line":2,"column":0},"map":{
"version": 3,
"file": "/load.php?lang=en&modules=test1%2Ctest2&only=scripts",
"sources": ["/w/script-nosemi-nonl.js"],
"sourcesContent": ["/* eslint-disable */\\nmw.foo()"],
"names": [],
"mappings": "AACA,EAAE,CAAC,GAAG,CAAC"
}
}
]
}
JSON
		);
		$rl->respond( $context );
	}

	public function testRespondSourceMapLink() {
		$rl = $this->getResourceLoaderWithTestModules( new HashConfig(
			[
				MainConfigNames::ResourceLoaderEnableSourceMapLinks => true,
			]
		) );
		$context = $this->getResourceLoaderContext(
			[ 'modules' => 'test1|test2', 'debug' => '' ],
			$rl
		);
		$this->expectOutputString( <<<JS
mw.foo()
mw.foo()
mw.foo()
mw.loader.state({"test1":"ready","test2":"ready"});
JS
		);
		$rl->respond( $context );

		$extraHeaders = TestingAccessWrapper::newFromObject( $rl )->extraHeaders;
		$this->assertEquals(
			[
				'SourceMap: /load.php?lang=en&modules=test1%2Ctest2&only=scripts&sourcemap=1&version=pq39u'
			],
			$extraHeaders,
			'Extra headers'
		);
	}

	public function testMeasureResponseTime() {
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$this->setService( 'StatsFactory', $statsHelper->getStatsFactory() );
		$rl = TestingAccessWrapper::newFromObject( new EmptyResourceLoader );
		$rl->measureResponseTime();
		$this->assertSame( 1, $statsHelper->count( 'resourceloader_response_time_seconds' ) );
	}

	public function testGetUserDefaults() {
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[],
			[
				'include' => 1,
				'exclude' => 1,
			]
		) );
		$ctx = $this->createStub( Context::class );
		$this->setTemporaryHook( 'ResourceLoaderExcludeUserOptions', function (
			array &$keysToExclude,
			Context $context
		) use ( $ctx ): void {
			$this->assertSame( $ctx, $context );
			$keysToExclude[] = 'exclude';
		}, true );

		$defaults = ResourceLoader::getUserDefaults(
			$ctx,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getUserOptionsLookup()
		);
		$this->assertSame( [ 'include' => 1 ], $defaults );
	}
}
