<?php

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {

	protected static function getResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
				'debug' => 'true',
				'lang' => 'en',
				'modules' => 'startup',
				'only' => 'scripts',
				'skin' => 'vector',
				'target' => 'test',
		) );
		return new ResourceLoaderContext( $resourceLoader, $request );
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			// For ResourceLoader::__construct()
			'wgResourceLoaderSources' => array(),

			// For wfScript()
			'wgScriptPath' => '/w',
			'wgScriptExtension' => '.php',
			'wgScript' => '/w/index.php',
			'wgLoadScript' => '/w/load.php',
		) );
	}
}

/* Stubs */

class ResourceLoaderTestModule extends ResourceLoaderModule {}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {}
