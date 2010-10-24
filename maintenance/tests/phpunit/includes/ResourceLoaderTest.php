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
	 * Allthough ResourceLoader::register uses type hinting to prevent arbitrary information being passed through as a
	 * ResourceLoaderModule object, null can still get through.
	 *
	 * @depends testCreatingNewResourceLoaderCallsRegistrationHook
	 * @covers ResourceLoader::register
	 * @covers ResourceLoader::getModule
	 * @expectedException MWException
	 */
	public function testRegisteringNullModuleThrowsAnException( ResourceLoader $resourceLoader ) {
		$resourceLoader->register( 'TEST.nullModule', null );
	}
}

/* Stubs */

class ResourceLoaderTestModule extends ResourceLoaderModule { }

/* Hooks */
global $wgHooks;
$wgHooks['ResourceLoaderRegisterModules'][] = 'ResourceLoaderTest::resourceLoaderRegisterModules';