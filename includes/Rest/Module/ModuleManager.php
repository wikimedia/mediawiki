<?php

namespace MediaWiki\Rest\Module;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\ResponseFactory;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Manages information related to REST modules, such as routes and specs
 *
 * TODO: consider whether some code in Router or SpecBasedModule might be better placed here.
 * TODO: consider whether we should inject a ModuleManager instance into Router for shared caching.
 *
 * @since 1.46
 */
class ModuleManager {
	// These modules will be enabled. No config entry is needed.
	private const CORE_ROUTE_FILES = [
		'includes/Rest/coreRoutes.json',
		'includes/Rest/site.v1.json',
	];

	// These specs will be available in the Rest Sandbox. No config change is needed.
	private const CORE_SPECS = [
		'mw-extra' => [
			'url' => '/rest.php/specs/v0/module/-',
			'name' => 'MediaWiki REST API (routes not in modules)',
		]
	];

	/** Seconds to persist module definitions on cache */
	private const MODULE_DEFINITION_TTL = 60;

	/** @var string[]|null */
	private ?array $routeFiles = null;

	private string $extensionDirectory;

	private array $restApiAdditionalRouteFiles;
	private array $restSandboxSpecs;

	private string $scriptPath;

	/** Persistent local server/host cache (e.g. APCu) */
	private BagOStuff $srvCache;

	private ResponseFactory $responseFactory;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ExtensionDirectory,
		MainConfigNames::RestAPIAdditionalRouteFiles,
		MainConfigNames::RestSandboxSpecs,
		MainConfigNames::ScriptPath
	];

	/**
	 * @param ServiceOptions $options
	 * @param BagOStuff $srvCache Optional BagOStuff instance to an APC-style cache.
	 * @param ResponseFactory $responseFactory
	 *
	 * @internal
	 */
	public function __construct( ServiceOptions $options, BagOStuff $srvCache, ResponseFactory $responseFactory ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->extensionDirectory = $options->get( MainConfigNames::ExtensionDirectory );
		$this->restApiAdditionalRouteFiles = $options->get( MainConfigNames::RestAPIAdditionalRouteFiles );
		$this->restSandboxSpecs = $options->get( MainConfigNames::RestSandboxSpecs );
		$this->scriptPath = $options->get( MainConfigNames::ScriptPath );

		$this->srvCache = $srvCache;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * @return string[]
	 */
	public function getRouteFiles(): array {
		if ( $this->routeFiles === null ) {
			$this->routeFiles = $this->initRouteFiles();
		}

		return $this->routeFiles;
	}

	/**
	 * @return string[]
	 */
	private function initRouteFiles(): array {
		global $IP;

		// Always include the "official" routes. Include additional routes if specified.
		// Routes in extensions are added to RestAPIAdditionalRouteFiles by ExtensionProcessor.
		$routeFiles = array_merge(
			self::CORE_ROUTE_FILES,
			$this->restApiAdditionalRouteFiles
		);

		foreach ( $routeFiles as &$file ) {
			if ( str_starts_with( $file, '/' ) ) {
				// Allow absolute paths on non-Windows
			} elseif ( str_starts_with( $file, 'extensions/' ) ) {
				// Support hacks like Wikibase.ci.php
				$file = substr_replace( $file, $this->extensionDirectory,
					0, strlen( 'extensions' ) );
			} else {
				$file = "$IP/$file";
			}
		}

		return $routeFiles;
	}

	/**
	 * Returns true if any api specs are available, or false otherwise.
	 *
	 * @return bool
	 */
	public function hasApiSpecs(): bool {
		return $this->getApiSpecDefs() !== [];
	}

	/**
	 * Returns the available choices for APIs to explore.
	 *
	 * @return array<string,array<string,string>>
	 */
	public function getApiSpecs(): array {
		$specs = $this->getApiSpecDefs();

		foreach ( $specs as $key => &$spec ) {
			// Translate any message keys from config to a displayable name string
			$localizer = new JsonLocalizer( $this->responseFactory );
			if ( isset( $spec['msg'] ) ) {
				$spec['name'] = $localizer->getFormattedMessage( $spec['msg'] );
				unset( $spec['msg'] );
			}

			// Extract values from module definition files. Only load a file if necessary.
			if ( isset( $spec['file'] ) ) {
				$spec = $this->populateFromFile( $spec, $this->scriptPath );
			} elseif ( !isset( $spec['name'] ) ) {
				// If we were otherwise unable to get a name, use the key
				$spec['name'] = $key;
			}
		}

		return $specs;
	}

	/**
	 * Gets the available API spec definition information.
	 * These need further processing before use by the REST Sandbox.
	 *
	 * @see MainConfigSchema::RestSandboxSpecs for the structure of the array
	 *
	 * @return array<string,array<string,string>>
	 */
	private function getApiSpecDefs(): array {
		$coreSpecs = self::CORE_SPECS;

		// Adjust core specs and merge with specs from config, giving config priority
		foreach ( $coreSpecs as &$coreSpec ) {
			if ( isset( $coreSpec['url'] ) ) {
				$coreSpec['url'] = $this->scriptPath . $coreSpec['url'];
			}
		}

		return array_merge( $coreSpecs, $this->restSandboxSpecs );
	}

	/**
	 * Populates any missing spec details from the input module definition file
	 *
	 * @param array<string,string> $spec The sandbox spec. Must have a 'file' key.
	 * @param string $scriptPath
	 *
	 * @return array<string,string>
	 */
	private function populateFromFile( array $spec, string $scriptPath ): array {
		$hasUrl = isset( $spec['url'] );
		$hasName = isset( $spec['name'] ) || isset( $spec['msg'] );
		if ( !$hasUrl || !$hasName ) {
			// Get any missing information from the module definition file, giving config priority
			$moduleDefInfo = $this->getModuleDefinitionInfo( $spec['file'] );
			if ( !$hasName ) {
				$spec['name'] = $moduleDefInfo['title'];
			}

			if ( !$hasUrl ) {
				$spec['url'] = $scriptPath . '/rest.php/specs/v0/module/' . $moduleDefInfo['moduleId'];
			}
		}

		return $spec;
	}

	/**
	 * Gets necessary info from the module definition info, from cache if possible,
	 * from the definition file otherwise.
	 *
	 * @param string $file The module definition file to load
	 *
	 * @return array<string,string>
	 */
	private function getModuleDefinitionInfo( string $file ): array {
		$key = $this->srvCache->makeKey(
			__CLASS__,
			'definition',
			sha1( $file ),
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			(int)@filemtime( $file ),
			implode( ',', $this->responseFactory->getLangCodes() )
		);

		return $this->srvCache->getWithSetCallback(
			$key,
			self::MODULE_DEFINITION_TTL,
			function () use ( $file ) {
				$md = SpecBasedModule::loadModuleDefinition( $file, $this->responseFactory );
				return [
					'moduleId' => $md['moduleId'],
					'title' => $md['info']['title']
				];
			}
		);
	}
}
