<?php

namespace MediaWiki\Tests\ResourceLoader;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FileModule;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWikiIntegrationTestCase;
use Psr\Log\LoggerInterface;

abstract class ResourceLoaderTestCase extends MediaWikiIntegrationTestCase {
	// Version hash for a blank file module.
	// Result of ResourceLoader::makeHash(), ResourceLoaderTestModule
	// and FileModule::getDefinitionSummary().
	public const BLANK_VERSION = 'dukpe';
	// Result of ResourceLoader::makeVersionQuery() for a blank file module.
	// In other words, result of ResourceLoader::makeHash( BLANK_VERSION );
	public const BLANK_COMBI = '1xz0a';

	/**
	 * @param array|string $options Language code or options array
	 * - string 'lang' Language code
	 * - string 'modules' Pipe-separated list of module names
	 * - string|null 'only' "scripts" (unwrapped script), "styles" (stylesheet), or null
	 *    (mw.loader.implement).
	 * @param ResourceLoader|null $rl
	 * @return Context
	 */
	protected function getResourceLoaderContext( $options = [], ?ResourceLoader $rl = null ) {
		if ( is_string( $options ) ) {
			$options = [ 'lang' => $options ];
		}
		$options += [
			'debug' => 'true',
			'lang' => 'en',
			'skin' => 'fallback',
			'modules' => 'startup',
			'only' => 'scripts',
			'safemode' => null,
			'sourcemap' => null,
		];
		$resourceLoader = $rl ?: new ResourceLoader(
			$this->getServiceContainer()->getMainConfig(),
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
			'sourcemap' => $options['sourcemap'],
			'target' => 'phpunit',
		] );
		return new Context( $resourceLoader, $request );
	}

	public static function getSettings() {
		return [
			// For ResourceLoader::respond
			MainConfigNames::ResourceLoaderEnableSourceMapLinks => false,

			// For Module
			MainConfigNames::ResourceLoaderValidateJS => false,

			// For SkinModule
			MainConfigNames::Logos => false,
			MainConfigNames::Logo => '/logo.png',
			MainConfigNames::ResourceBasePath => '/w',

			// For ResourceLoader::getSiteConfigSettings and StartUpModule
			MainConfigNames::Server => 'https://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ResourceLoaderEnableJSProfiler => false,

			// For CodexModule
			MainConfigNames::CodexDevelopmentDir => null,
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
	/** @var string[] */
	protected $messages = [];
	/** @var string[] */
	protected $dependencies = [];
	/** @var string|null */
	protected $group = null;
	/** @var string */
	protected $source = 'local';
	/** @var string */
	protected $script = '';
	/** @var string */
	protected $styles = '';
	/** @var string|null */
	protected $skipFunction = null;
	/** @var bool */
	protected $isKnownEmpty = false;
	/** @var string */
	protected $type = Module::LOAD_GENERAL;
	/** @var bool|null */
	protected $shouldEmbed = null;
	/** @var bool */
	protected $mayValidateScript = false;

	public function __construct( $options = [] ) {
		foreach ( $options as $key => $value ) {
			if ( $key === 'class' || $key === 'factory' ) {
				continue;
			}
			$this->$key = $value;
		}
	}

	public function getScript( Context $context ) {
		if ( $this->mayValidateScript ) {
			// This enables the validation check that replaces invalid
			// scripts with a warning message.
			// Based on $wgResourceLoaderValidateJS
			return $this->validateScriptFile( 'input.js', $this->script );
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

	public function getDependencies( ?Context $context = null ) {
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
		return true;
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
 * A more constrained and testable variant of FileModule.
 *
 * - Implements getLessVars() support.
 * - Disables database persistance of discovered file dependencies.
 */
class ResourceLoaderFileTestModule extends FileModule {
	/** @var array */
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

}

class ResourceLoaderFileModuleTestingSubclass extends FileModule {
}

class EmptyResourceLoader extends ResourceLoader {
	public function __construct( ?Config $config = null, ?LoggerInterface $logger = null ) {
		parent::__construct( $config ?: ResourceLoaderTestCase::getMinimalConfig(), $logger );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( ResourceLoaderTestModule::class, 'ResourceLoaderTestModule' );

/** @deprecated class alias since 1.42 */
class_alias( ResourceLoaderTestCase::class, 'ResourceLoaderTestCase' );

/** @deprecated class alias since 1.42 */
class_alias( ResourceLoaderFileTestModule::class, 'ResourceLoaderFileTestModule' );

/** @deprecated class alias since 1.42 */
class_alias( ResourceLoaderFileModuleTestingSubclass::class, 'ResourceLoaderFileModuleTestingSubclass' );

/** @deprecated class alias since 1.42 */
class_alias( EmptyResourceLoader::class, 'EmptyResourceLoader' );
