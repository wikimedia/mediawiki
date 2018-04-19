<?php

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class ResourceLoaderTestCase extends MediaWikiTestCase {
	// Version hash for a blank file module.
	// Result of ResourceLoader::makeHash(), ResourceLoaderTestModule
	// and ResourceLoaderFileModule::getDefinitionSummary().
	const BLANK_VERSION = '09p30q0';

	/**
	 * @param array|string $options Language code or options array
	 * - string 'lang' Language code
	 * - string 'dir' Language direction (ltr or rtl)
	 * - string 'modules' Pipe-separated list of module names
	 * - string|null 'only' "scripts" (unwrapped script), "styles" (stylesheet), or null
	 *    (mw.loader.implement).
	 * @param ResourceLoader|null $rl
	 * @return ResourceLoaderContext
	 */
	protected function getResourceLoaderContext( $options = [], ResourceLoader $rl = null ) {
		if ( is_string( $options ) ) {
			// Back-compat for extension tests
			$options = [ 'lang' => $options ];
		}
		$options += [
			'lang' => 'en',
			'dir' => 'ltr',
			'skin' => 'vector',
			'modules' => 'startup',
			'only' => 'scripts',
		];
		$resourceLoader = $rl ?: new ResourceLoader();
		$request = new FauxRequest( [
				'lang' => $options['lang'],
				'modules' => $options['modules'],
				'only' => $options['only'],
				'skin' => $options['skin'],
				'target' => 'phpunit',
		] );
		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->setConstructorArgs( [ $resourceLoader, $request ] )
			->setMethods( [ 'getDirection' ] )
			->getMock();
		$ctx->method( 'getDirection' )->willReturn( $options['dir'] );
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
	protected $isKnownEmpty = false;
	protected $type = ResourceLoaderModule::LOAD_GENERAL;
	protected $targets = [ 'phpunit' ];
	protected $shouldEmbed = null;

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

	public function getType() {
		return $this->type;
	}

	public function getSkipFunction() {
		return $this->skipFunction;
	}

	public function isRaw() {
		return $this->isRaw;
	}
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		return $this->isKnownEmpty;
	}

	public function shouldEmbedModule( ResourceLoaderContext $context ) {
		return $this->shouldEmbed !== null ? $this->shouldEmbed : parent::shouldEmbedModule( $context );
	}

	public function enableModuleContentVersion() {
		return true;
	}
}

class ResourceLoaderFileTestModule extends ResourceLoaderFileModule {
	protected $lessVars = [];

	public function __construct( $options = [], $test = [] ) {
		parent::__construct( $options );

		foreach ( $test as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function getLessVars( ResourceLoaderContext $context ) {
		return $this->lessVars;
	}
}

class ResourceLoaderFileModuleTestModule extends ResourceLoaderFileModule {
}

class EmptyResourceLoader extends ResourceLoader {
	// TODO: This won't be needed once ResourceLoader is empty by default
	// and default registrations are done from ServiceWiring instead.
	public function __construct( Config $config = null, LoggerInterface $logger = null ) {
		$this->setLogger( $logger ?: new NullLogger() );
		$this->config = $config ?: MediaWikiServices::getInstance()->getMainConfig();
		// Source "local" is required by StartupModule
		$this->addSource( 'local', $this->config->get( 'LoadScript' ) );
		$this->setMessageBlobStore( new MessageBlobStore( $this, $this->getLogger() ) );
	}

	public function getErrors() {
		return $this->errors;
	}
}
