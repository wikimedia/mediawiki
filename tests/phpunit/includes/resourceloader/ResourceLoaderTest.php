<?php

class ResourceLoaderTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		// $wgResourceLoaderLESSFunctions, $wgResourceLoaderLESSImportPaths; $wgResourceLoaderLESSVars;

		$this->setMwGlobals( array(
			'wgResourceLoaderLESSFunctions' => array(
				'test-sum' => function ( $frame, $less ) {
					$sum = 0;
					foreach ( $frame[2] as $arg ) {
						$sum += (int)$arg[1];
					}
					return $sum;
				},
			),
			'wgResourceLoaderLESSImportPaths' => array(
				dirname( dirname( __DIR__  ) ) . '/data/less/common',
			),
			'wgResourceLoaderLESSVars' => array(
				'foo'  => '2px',
				'Foo' => '#eeeeee',
				'bar' => 5,
			),
		) );
	}

	/* Provider Methods */
	public static function provideValidModules() {
		return array(
			array( 'TEST.validModule1', new ResourceLoaderTestModule() ),
		);
	}

	/* Test Methods */

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
		$context = self::getResourceLoaderContext();
		$basePath = __DIR__ . '/../../data/less/module';
		$module = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'styles.less' ),
		) );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
	}

	/**
	 * Strip @noflip annotations from CSS code.
	 * @param string $css
	 * @return string
	 */
	private function stripNoflip( $css ) {
		return str_replace( '/*@noflip*/ ', '', $css );
	}

	/**
	 * What happens when you mix @embed and @noflip?
	 * This really is an integration test, but oh well.
	 */
	public function testMixedCssAnnotations(  ) {
		$basePath = __DIR__ . '/../../data/css';
		$testModule = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'test.css' ),
		) );
		$expectedModule = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'expected.css' ),
		) );

		$contextLtr = self::getResourceLoaderContext( 'en' );
		$contextRtl = self::getResourceLoaderContext( 'he' );

		// Since we want to compare the effect of @noflip+@embed against the effect of just @embed, and
		// the @noflip annotations are always preserved, we need to strip them first.
		$this->assertEquals(
			$expectedModule->getStyles( $contextLtr ),
			$this->stripNoflip( $testModule->getStyles( $contextLtr ) ),
			"/*@noflip*/ with /*@embed*/ gives correct results in LTR mode"
		);
		$this->assertEquals(
			$expectedModule->getStyles( $contextLtr ),
			$this->stripNoflip( $testModule->getStyles( $contextRtl ) ),
			"/*@noflip*/ with /*@embed*/ gives correct results in RTL mode"
		);
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
}
