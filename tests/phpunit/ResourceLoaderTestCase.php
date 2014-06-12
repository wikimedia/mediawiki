<?php

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {
	protected static function getResourceLoaderContext() {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
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

		ResourceLoader::clearCache();

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
	protected $skipFunction = null;
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

	public function getSkipFunction() {
		return $this->skipFunction;
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {
}
