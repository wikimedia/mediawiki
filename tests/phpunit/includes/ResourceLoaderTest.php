<?php

class ResourceLoaderTest extends MediaWikiTestCase {

	protected static $resourceLoaderRegisterModulesHook;

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
				dirname( __DIR__ ) . '/data/less/common',
			),
			'wgResourceLoaderLESSVars' => array(
				'foo'  => '2px',
				'Foo' => '#eeeeee',
				'bar' => 5,
			),
		) );
	}

	/* Hook Methods */

	/**
	 * ResourceLoaderRegisterModules hook
	 */
	public static function resourceLoaderRegisterModules( &$resourceLoader ) {
		self::$resourceLoaderRegisterModulesHook = true;

		return true;
	}

	/* Provider Methods */
	public static function provideValidModules() {
		return array(
			array( 'TEST.validModule1', new ResourceLoaderTestModule() ),
		);
	}

	public static function provideResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest();
		return array(
			array( new ResourceLoaderContext( $resourceLoader, $request ) ),
		);
	}

	/* Test Methods */

	/**
	 * Ensures that the ResourceLoaderRegisterModules hook is called when a new ResourceLoader object is constructed
	 * @covers ResourceLoader::__construct
	 */
	public function testCreatingNewResourceLoaderCallsRegistrationHook() {
		self::$resourceLoaderRegisterModulesHook = false;
		$resourceLoader = new ResourceLoader();
		$this->assertTrue( self::$resourceLoaderRegisterModulesHook );

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
	 * @dataProvider provideResourceLoaderContext
	 * @covers ResourceLoaderFileModule::compileLessFile
	 */
	public function testLessFileCompilation( $context ) {
		$basePath = __DIR__ . '/../data/less/module';
		$module = new ResourceLoaderFileModule( array(
			'localBasePath' => $basePath,
			'styles' => array( 'styles.less' ),
		) );
		$styles = $module->getStyles( $context );
		$this->assertStringEqualsFile( $basePath . '/styles.css', $styles['all'] );
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
}

/* Stubs */

class ResourceLoaderTestModule extends ResourceLoaderModule {
}

/* Hooks */
global $wgHooks;
$wgHooks['ResourceLoaderRegisterModules'][] = 'ResourceLoaderTest::resourceLoaderRegisterModules';
