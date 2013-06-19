<?php

class ResourceLoaderTest extends MediaWikiTestCase {

	protected static $resourceLoaderRegisterModulesHook;

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
			array( 'TEST.validModule.instance', new ResourceLoaderTestModule() ),
			array( 'TEST.validModule.instanceInArray', array( 'object' => new ResourceLoaderTestModule() ) ),
			array( 'TEST.validModule.class', array( 'class' => 'ResourceLoaderTestModule' ) ),
			array( 'TEST.validModule.callback', array( 'callback' => 'ResourceLoaderTest::makeResourceLoaderTestModule' ) ),
		);
	}

	public static function makeResourceLoaderTestModule( array $info ) {
		return new ResourceLoaderTestModule();
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
		$name, $info, ResourceLoader $resourceLoader
	) {
		$resourceLoader->register( $name, $info );
		$module = $resourceLoader->getModule( $name );
		$this->assertInstanceOf( 'ResourceLoaderModule', $module );
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
