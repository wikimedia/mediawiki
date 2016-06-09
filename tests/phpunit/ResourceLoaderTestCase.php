<?php

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {
	/**
	 * @param string $lang
	 * @param string $dir
	 * @return ResourceLoaderContext
	 */
	protected function getResourceLoaderContext( $lang = 'en', $dir = 'ltr' ) {
		$resourceLoader = new ResourceLoader();
		$request = new FauxRequest( [
				'lang' => $lang,
				'modules' => 'startup',
				'only' => 'scripts',
				'skin' => 'vector',
				'target' => 'phpunit',
		] );
		$ctx = $this->getMockBuilder( 'ResourceLoaderContext' )
			->setConstructorArgs( [ $resourceLoader, $request ] )
			->setMethods( [ 'getDirection' ] )
			->getMock();
		$ctx->expects( $this->any() )->method( 'getDirection' )->will(
			$this->returnValue( $dir )
		);
		return $ctx;
	}

	public static function getSettings() {
		return [
			// For ResourceLoader::inDebugMode since it doesn't have context
			'ResourceLoaderDebug' => true,

			// Avoid influence from wgInvalidateCacheOnLocalSettingsChange
			'CacheEpoch' => '20140101000000',

			// For ResourceLoader::__construct()
			'ResourceLoaderSources' => [],

			// For wfScript()
			'ScriptPath' => '/w',
			'ScriptExtension' => '.php',
			'Script' => '/w/index.php',
			'LoadScript' => '/w/load.php',
		];
	}

	protected function setUp() {
		parent::setUp();

		ResourceLoader::clearCache();

		$globals = [];
		foreach ( self::getSettings() as $key => $value ) {
			$globals['wg' . $key] = $value;
		}
		$this->setMwGlobals( $globals );
	}
}

/* Stubs */

class ResourceLoaderTestModule extends ResourceLoaderModule {
	protected $messages = [];
	protected $dependencies = [];
	protected $group = null;
	protected $source = 'local';
	protected $script = '';
	protected $styles = '';
	protected $skipFunction = null;
	protected $isRaw = false;
	protected $targets = [ 'phpunit' ];

	public function __construct( $options = [] ) {
		foreach ( $options as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function getScript( ResourceLoaderContext $context ) {
		return $this->validateScriptFile( 'input', $this->script );
	}

	public function getStyles( ResourceLoaderContext $context ) {
		return [ '' => $this->styles ];
	}

	public function getMessages() {
		return $this->messages;
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
