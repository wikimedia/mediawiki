<?php

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {
	/**
	 * @param string $lang
	 * @param string $dir
	 * @return ResourceLoaderContext
	 */
	protected function getResourceLoaderContext( $lang = 'en', $dir = 'ltr' ) {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( array(
				'lang' => $lang,
				'modules' => 'startup',
				'only' => 'scripts',
				'skin' => 'vector',
				'target' => 'test',
		) );
		$ctx = $this->getMockBuilder( 'ResourceLoaderContext' )
			->setConstructorArgs( array( $resourceLoader, $request ) )
			->setMethods( array( 'getDirection' ) )
			->getMock();
		$ctx->expects( $this->any() )->method( 'getDirection' )->will(
			$this->returnValue( $dir )
		);
		return $ctx;
	}

	public static function getSettings() {
		return array(
			// For ResourceLoader::inDebugMode since it doesn't have context
			'ResourceLoaderDebug' => true,

			// Avoid influence from wgInvalidateCacheOnLocalSettingsChange
			'CacheEpoch' => '20140101000000',

			// For ResourceLoader::__construct()
			'ResourceLoaderSources' => array(),

			// For wfScript()
			'ScriptPath' => '/w',
			'ScriptExtension' => '.php',
			'Script' => '/w/index.php',
			'LoadScript' => '/w/load.php',
		);
	}

	protected function setUp() {
		parent::setUp();

		ResourceLoader::clearCache();

		$globals = array();
		foreach ( self::getSettings() as $key => $value ) {
			$globals['wg' . $key] = $value;
		}
		$this->setMwGlobals( $globals );
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
		return $this->validateScriptFile( 'input', $this->script );
	}

	public function getStyles( ResourceLoaderContext $context ) {
		return array( '' => $this->styles );
	}

	public function getDependencies( ResourceLoaderContext $context = null ) {
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

	public function enableModuleContentVersion() {
		return true;
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {
}
