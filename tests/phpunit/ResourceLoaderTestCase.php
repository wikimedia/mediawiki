<?php

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {
	protected static function getResourceLoaderContext( $lang = 'en' ) {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
				'lang' => $lang,
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
	protected $script = '';
	protected $styles = '';
	protected $skipFunction = null;
	protected $isRaw = false;
	protected $targets = array( 'test' );

	public function __construct( $options = array() ) {
		foreach ( $options as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function getScript( ResourceLoaderContext $context ) {
		return $this->script;
	}

	public function getStyles( ResourceLoaderContext $context ) {
		return array( '' => $this->styles );
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

	public function isRaw() {
		return $this->isRaw;
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {
}

class ResourceLoaderWikiModuleTestModule extends ResourceLoaderWikiModule {
	// Override expected via PHPUnit mocks and stubs
	protected function getPages( ResourceLoaderContext $context ) {
		return array();
	}
}
