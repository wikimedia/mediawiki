<?php

class ResourceLoaderTest extends PHPUnit_Framework_TestCase {

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
	public function provideValidModules() {
		return array(
			array( 'TEST.validModule1', new ResourceLoaderTestModule() ),
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

	public function providePackedModules() {
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
			)
		);
	}
}

/* Stubs */

class ResourceLoaderTestModule extends ResourceLoaderModule { }

/* Hooks */
global $wgHooks;
$wgHooks['ResourceLoaderRegisterModules'][] = 'ResourceLoaderTest::resourceLoaderRegisterModules';
