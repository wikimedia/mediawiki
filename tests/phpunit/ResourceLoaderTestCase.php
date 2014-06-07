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
			// For ResourceLoader::inDebugMode since it doesn't have context
			'wgResourceLoaderDebug' => true,

			// Avoid influence from wgInvalidateCacheOnLocalSettingsChange
			'wgCacheEpoch' => '20140101000000',

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

class ResourceLoaderTestModule extends ResourceLoaderModule {

	protected $dependencies = array();
	protected $group = null;
	protected $source = 'local';
	protected $targets = array( 'test' );

	public function __construct( $options = array() ) {
		foreach ( $options as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function getDependencies() {
		return $this->dependencies;
	}

	public function getGroup() {
		return $this->group;
	}

	public function getSource() {
		return $this->source;
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {}
