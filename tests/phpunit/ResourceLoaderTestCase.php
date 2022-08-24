<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\ResourceLoader;
use Psr\Log\LoggerInterface;

abstract class ResourceLoaderTestCase extends MediaWikiIntegrationTestCase {
	// Version hash for a blank file module.
	// Result of ResourceLoader::makeHash(), ResourceLoaderTestModule
	// and ResourceLoaderFileModule::getDefinitionSummary().
	public const BLANK_VERSION = '9p30q';
	// Result of ResoureLoader::makeVersionQuery() for a blank file module.
	// In other words, result of ResourceLoader::makeHash( BLANK_VERSION );
	public const BLANK_COMBI = 'rbml8';

	/**
	 * @param array|string $options Language code or options array
	 * - string 'lang' Language code
	 * - string 'dir' Language direction (ltr or rtl)
	 * - string 'modules' Pipe-separated list of module names
	 * - string|null 'only' "scripts" (unwrapped script), "styles" (stylesheet), or null
	 *    (mw.loader.implement).
	 * @param ResourceLoader|null $rl
	 * @return Context
	 */
	protected function getResourceLoaderContext( $options = [], ResourceLoader $rl = null ) {
		if ( is_string( $options ) ) {
			// Back-compat for extension tests
			$options = [ 'lang' => $options ];
		}
		$options += [
			'debug' => 'true',
			'lang' => 'en',
			'dir' => 'ltr',
			'skin' => 'fallback',
			'modules' => 'startup',
			'only' => 'scripts',
			'safemode' => null,
		];
		$resourceLoader = $rl ?: new ResourceLoader(
			MediaWikiServices::getInstance()->getMainConfig(),
			null,
			null,
			[
				'loadScript' => '/w/load.php',
			]
		);
		$request = new FauxRequest( [
			'debug' => $options['debug'],
			'lang' => $options['lang'],
			'modules' => $options['modules'],
			'only' => $options['only'],
			'safemode' => $options['safemode'],
			'skin' => $options['skin'],
			'target' => 'phpunit',
		] );
		$ctx = $this->getMockBuilder( Context::class )
			->setConstructorArgs( [ $resourceLoader, $request ] )
			->onlyMethods( [ 'getDirection' ] )
			->getMock();
		$ctx->method( 'getDirection' )->willReturn( $options['dir'] );
		return $ctx;
	}

	public static function getSettings() {
		return [
			// For ResourceLoaderModule
			MainConfigNames::ResourceLoaderValidateJS => false,

			// For ResourceLoaderSkinModule
			MainConfigNames::Logos => false,
			MainConfigNames::Logo => '/logo.png',
			MainConfigNames::BaseDirectory => MW_INSTALL_PATH,
			MainConfigNames::ResourceBasePath => '/w',
			MainConfigNames::ParserEnableLegacyMediaDOM => true,

			// For  ResourceLoader::getSiteConfigSettings and ResourceLoaderStartUpModule
			MainConfigNames::Server => 'https://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ResourceLoaderEnableJSProfiler => false,
		];
	}

	public static function getMinimalConfig() {
		return new HashConfig( self::getSettings() );
	}

	/**
	 * The annotation causes this to be called immediately before setUp()
	 * @before
	 */
	final protected function mediaWikiResourceLoaderSetUp(): void {
		ResourceLoader::clearCache();

		$this->overrideConfigValues( self::getSettings() );
	}
}

/* Stubs */

class ResourceLoaderTestModule extends Module {
	protected $messages = [];
	protected $dependencies = [];
	protected $group = null;
	protected $source = 'local';
	protected $script = '';
	protected $styles = '';
	protected $skipFunction = null;
	protected $es6 = false;
	protected $isRaw = false;
	protected $isKnownEmpty = false;
	protected $type = Module::LOAD_GENERAL;
	protected $targets = [ 'phpunit' ];
	protected $shouldEmbed = null;
	protected $mayValidateScript = false;

	public function __construct( $options = [] ) {
		foreach ( $options as $key => $value ) {
			$this->$key = $value;
		}
	}

	public function getScript( Context $context ) {
		if ( $this->mayValidateScript ) {
			// This enables the validation check that replaces invalid
			// scripts with a warning message.
			// Based on $wgResourceLoaderValidateJS
			return $this->validateScriptFile( 'input', $this->script );
		} else {
			return $this->script;
		}
	}

	public function getStyles( Context $context ) {
		return [ '' => $this->styles ];
	}

	public function getMessages() {
		return $this->messages;
	}

	public function getDependencies( Context $context = null ) {
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

	public function requiresES6() {
		return $this->es6;
	}

	public function isRaw() {
		return $this->isRaw;
	}

	public function isKnownEmpty( Context $context ) {
		return $this->isKnownEmpty;
	}

	public function shouldEmbedModule( Context $context ) {
		return $this->shouldEmbed ?? parent::shouldEmbedModule( $context );
	}

	public function enableModuleContentVersion() {
		return true;
	}
}

/**
 * A more constrained and testable variant of ResourceLoaderFileModule.
 *
 * - Implements getLessVars() support.
 * - Disables database persistance of discovered file dependencies.
 */
class ResourceLoaderFileTestModule extends FileModule {
	protected $lessVars = [];

	public function __construct( $options = [] ) {
		if ( isset( $options['lessVars'] ) ) {
			$this->lessVars = $options['lessVars'];
			unset( $options['lessVars'] );
		}

		parent::__construct( $options );
	}

	public function getLessVars( Context $context ) {
		return $this->lessVars;
	}

	/**
	 * @param Context $context
	 * @return array
	 */
	protected function getFileDependencies( Context $context ) {
		// No-op
		return [];
	}

	protected function saveFileDependencies( Context $context, $refs ) {
		// No-op
	}
}

class ResourceLoaderFileModuleTestingSubclass extends FileModule {
}

class EmptyResourceLoader extends ResourceLoader {
	public function __construct( Config $config = null, LoggerInterface $logger = null ) {
		parent::__construct( $config ?: ResourceLoaderTestCase::getMinimalConfig(), $logger );
	}

	public function getErrors() {
		return $this->errors;
	}
}
