<?php

class ResourceLoaderTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgResourceLoaderLESSImportPaths' => array(
				dirname( dirname( __DIR__ ) ) . '/data/less/common',
			),
			'wgResourceLoaderLESSVars' => array(
				'foo'  => '2px',
				'Foo' => '#eeeeee',
				'bar' => 5,
			),
		) );
	}

	public static function provideValidModules() {
		return array(
			array( 'TEST.validModule1', new ResourceLoaderTestModule() ),
		);
	}

	/**
	 * Ensures that the ResourceLoaderRegisterModules hook is called when a new
	 * ResourceLoader object is constructed.
	 * @covers ResourceLoader::__construct
	 */
	public function testCreatingNewResourceLoaderCallsRegistrationHook() {
		$resourceLoaderRegisterModulesHook = false;

		$this->setMwGlobals( 'wgHooks', array(
			'ResourceLoaderRegisterModules' => array(
				function ( &$resourceLoader ) use ( &$resourceLoaderRegisterModulesHook ) {
					$resourceLoaderRegisterModulesHook = true;
				}
			)
		) );

		$resourceLoader = new ResourceLoader();
		$this->assertTrue(
			$resourceLoaderRegisterModulesHook,
			'Hook ResourceLoaderRegisterModules called'
		);

		return $resourceLoader;
	}

	/**
	 * @dataProvider provideValidModules
	 * @depends testCreatingNewResourceLoaderCallsRegistrationHook
	 * @covers ResourceLoader::register
	 * @covers ResourceLoader::getModule
	 */
	public function testRegisteredValidModulesAreAccessible(
		$name, ResourceLoaderModule $module, ResourceLoader $resourceLoader
	) {
		$resourceLoader->register( $name, $module );
		$this->assertEquals( $module, $resourceLoader->getModule( $name ) );
	}

	/**
	 * @covers ResourceLoaderFileModule::compileLessFile
	 */
	public function testLessFileCompilation() {
		$context = $this->getResourceLoaderContext();
		$basePath = __DIR__ . '/../../data/less/module';
		$module = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'styles.less' ),
		) );
		$module->setName( 'test.less' );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
	}

	/**
	 * Strip @noflip annotations from CSS code.
	 * @param string $css
	 * @return string
	 */
	private static function stripNoflip( $css ) {
		return str_replace( '/*@noflip*/ ', '', $css );
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
	public function testexpandModuleNames( $desc, $modules, $packed ) {
		$this->assertEquals( $modules, ResourceLoaderContext::expandModuleNames( $packed ), $desc );
	}

	public static function providePackedModules() {
		return array(
			array(
				'Example from makePackedModulesString doc comment',
				array( 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' ),
				'foo.bar,baz|bar.baz,quux',
			),
			array(
				'Example from expandModuleNames doc comment',
				array( 'jquery.foo', 'jquery.bar', 'jquery.ui.baz', 'jquery.ui.quux' ),
				'jquery.foo,bar|jquery.ui.baz,quux',
			),
			array(
				'Regression fixed in r88706 with dotless names',
				array( 'foo', 'bar', 'baz' ),
				'foo,bar,baz',
			),
			array(
				'Prefixless modules after a prefixed module',
				array( 'single.module', 'foobar', 'foobaz' ),
				'single.module|foobar,foobaz',
			),
		);
	}

	public static function provideAddSource() {
		return array(
			array( 'examplewiki', '//example.org/w/load.php', 'examplewiki' ),
			array( 'example2wiki', array( 'loadScript' => '//example.com/w/load.php' ), 'example2wiki' ),
			array(
				array( 'foowiki' => '//foo.org/w/load.php', 'bazwiki' => '//baz.org/w/load.php' ),
				null,
				array( 'foowiki', 'bazwiki' )
			),
			array(
				array( 'foowiki' => '//foo.org/w/load.php' ),
				null,
				false,
			),
		);
	}

	/**
	 * @dataProvider provideAddSource
	 * @covers ResourceLoader::addSource
	 * @covers ResourceLoader::getSources
	 */
	public function testAddSource( $name, $info, $expected ) {
		$rl = new ResourceLoader;
		if ( $expected === false ) {
			$this->setExpectedException( 'MWException', 'ResourceLoader duplicate source addition error' );
			$rl->addSource( $name, $info );
		}
		$rl->addSource( $name, $info );
		if ( is_array( $expected ) ) {
			foreach ( $expected as $source ) {
				$this->assertArrayHasKey( $source, $rl->getSources() );
			}
		} else {
			$this->assertArrayHasKey( $expected, $rl->getSources() );
		}
	}

	public static function fakeSources() {
		return array(
			'examplewiki' => array(
				'loadScript' => '//example.org/w/load.php',
				'apiScript' => '//example.org/w/api.php',
			),
			'example2wiki' => array(
				'loadScript' => '//example.com/w/load.php',
				'apiScript' => '//example.com/w/api.php',
			),
		);
	}

	public static function provideLoaderImplement() {
		return array(
			array( array(
				'title' => 'Implement scripts, styles and messages',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => array( 'css' => array( '.mw-example {}' ) ),
				'messages' => array( 'example' => '' ),
				'templates' => array(),

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery ) {
mw.example();
}, {
    "css": [
        ".mw-example {}"
    ]
}, {
    "example": ""
} );',
			) ),
			array( array(
				'title' => 'Implement scripts',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => array(),
				'messages' => new XmlJsCode( '{}' ),
				'templates' => array(),
				'title' => 'scripts, styles and messags',

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery ) {
mw.example();
} );',
			) ),
			array( array(
				'title' => 'Implement styles',

				'name' => 'test.example',
				'scripts' => array(),
				'styles' => array( 'css' => array( '.mw-example {}' ) ),
				'messages' => new XmlJsCode( '{}' ),
				'templates' => array(),

				'expected' => 'mw.loader.implement( "test.example", [], {
    "css": [
        ".mw-example {}"
    ]
} );',
			) ),
			array( array(
				'title' => 'Implement scripts and messages',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => array(),
				'messages' => array( 'example' => '' ),
				'templates' => array(),

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery ) {
mw.example();
}, {}, {
    "example": ""
} );',
			) ),
			array( array(
				'title' => 'Implement scripts and templates',

				'name' => 'test.example',
				'scripts' => 'mw.example();',
				'styles' => array(),
				'messages' => new XmlJsCode( '{}' ),
				'templates' => array( 'example.html' => '' ),

				'expected' => 'mw.loader.implement( "test.example", function ( $, jQuery ) {
mw.example();
}, {}, {}, {
    "example.html": ""
} );',
			) ),
		);
	}

	/**
	 * @dataProvider provideLoaderImplement
	 * @covers ResourceLoader::makeLoaderImplementScript
	 */
	public function testMakeLoaderImplementScript( $case ) {
		$this->assertEquals(
			$case['expected'],
			ResourceLoader::makeLoaderImplementScript(
				$case['name'],
				$case['scripts'],
				$case['styles'],
				$case['messages'],
				$case['templates']
			)
		);
	}

	/**
	 * @covers ResourceLoader::getLoadScript
	 */
	public function testGetLoadScript() {
		$this->setMwGlobals( 'wgResourceLoaderSources', array() );
		$rl = new ResourceLoader();
		$sources = self::fakeSources();
		$rl->addSource( $sources );
		foreach ( array( 'examplewiki', 'example2wiki' ) as $name ) {
			$this->assertEquals( $rl->getLoadScript( $name ), $sources[$name]['loadScript'] );
		}

		try {
			$rl->getLoadScript( 'thiswasneverreigstered' );
			$this->assertTrue( false, 'ResourceLoader::getLoadScript should have thrown an exception' );
		} catch ( MWException $e ) {
			$this->assertTrue( true );
		}
	}

	/**
	 * @covers ResourceLoader::isModuleRegistered
	 */
	public function testIsModuleRegistered() {
		$rl = new ResourceLoader();
		$rl->register( 'test.module', new ResourceLoaderTestModule() );
		$this->assertTrue( $rl->isModuleRegistered( 'test.module' ) );
		$this->assertFalse( $rl->isModuleRegistered( 'test.modulenotregistered' ) );
	}
}
