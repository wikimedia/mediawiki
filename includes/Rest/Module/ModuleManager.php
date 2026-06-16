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
 * TODO: consider whether ModuleManager and Router can share caching.
 *
 * @since 1.46
 */
class ModuleManager {
	// These modules will be enabled. No config entry is needed.
	private const CORE_ROUTE_FILES = [
		'includes/Rest/coreRoutes.json',
		'includes/Rest/site.v1.json',
		'includes/Rest/specs.v0.json',
	];

	// These specs will be available in the Rest Sandbox. No config change is needed.
	private const CORE_SPECS = [
		'mw-extra' => [
			'url' => '/rest.php/specs/v0/module/-',
			'name' => 'MediaWiki REST API (routes not in modules)',
		],
		'specs.v0' => [
			'file' => "./includes/Rest/specs.v0.json",
		],
		'site.v1' => [
			'file' => "./includes/Rest/site.v1.json",
		]
	];

	/** Seconds to persist module definitions on cache */
	private const MODULE_DEFINITION_TTL = 60;

	/** @var string[]|null */
	private ?array $routeFiles = null;
	private ?array $disabledRouteFiles = null;

	private string $extensionDirectory;

	private array $extensionModuleFiles;
	private array $restApiAdditionalRouteFiles;
	private array $restSandboxSpecs;
	private array $restModuleOverrides;

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
		MainConfigNames::RestModuleOverrides,
		MainConfigNames::ScriptPath
	];

	/**
	 * @param ServiceOptions $options
	 * @param string[] $extensionModuleFiles
	 * @param BagOStuff $srvCache Optional BagOStuff instance to an APC-style cache.
	 * @param ResponseFactory $responseFactory
	 *
	 * @internal
	 */
	public function __construct(
		ServiceOptions $options, array $extensionModuleFiles, BagOStuff $srvCache, ResponseFactory $responseFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->extensionDirectory = $options->get( MainConfigNames::ExtensionDirectory );
		$this->restApiAdditionalRouteFiles = $options->get( MainConfigNames::RestAPIAdditionalRouteFiles );
		$this->restSandboxSpecs = $options->get( MainConfigNames::RestSandboxSpecs );
		$this->restModuleOverrides = $options->get( MainConfigNames::RestModuleOverrides );
		$this->scriptPath = $options->get( MainConfigNames::ScriptPath );

		$this->extensionModuleFiles = $extensionModuleFiles;
		$this->srvCache = $srvCache;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * Gets the module mode for a given module id.
	 *
	 * @param string $moduleId The module id
	 *
	 * @return ModuleMode
	 * @since 1.47
	 */
	public function getModuleMode( string $moduleId ): ModuleMode {
		// If an override is attempted, but the mode is unrecognized, disable the module. This
		// helps guard against undesired module activation/publishing due to configuration typos.
		if ( isset( $this->restModuleOverrides[$moduleId]['mode'] ) ) {
			$mm = ModuleMode::tryFrom( $this->restModuleOverrides[$moduleId]['mode'] );
			$mm ??= ModuleMode::DISABLED;
		}

		return $mm ?? ModuleMode::getModuleMode( AudienceDesignation::fromModuleId( $moduleId ) );
	}

	/**
	 * Gets the configured override parameters (if any) for a particular module
	 *
	 * @param string $moduleId The module id
	 *
	 * @return array<string,string>
	 */
	private function getModeParams( string $moduleId ): array {
		$adParams = ModuleMode::getModeParams( AudienceDesignation::fromModuleId( $moduleId ) );
		$overrideParams = $this->restModuleOverrides[$moduleId] ?? [];
		unset( $overrideParams['mode'] );

		// Config overrides audience designation
		return $overrideParams + $adParams;
	}

	/**
	 * @return string[]
	 */
	public function getRouteFiles(): array {
		if ( $this->routeFiles === null ) {
			$this->initRouteFiles();
		}

		return $this->routeFiles;
	}

	/**
	 * Useful for testing or error status. The return value will include route files that
	 * were disabled by either audience designation or configuration.
	 *
	 * @since 1.47
	 * @return string[]
	 */
	public function getDisabledRouteFiles(): array {
		if ( $this->disabledRouteFiles === null ) {
			$this->initRouteFiles();
		}

		return $this->disabledRouteFiles;
	}

	private function initRouteFiles() {
		// Always include the "official" routes. Include additional routes if specified.
		// Extension module files are added to extension.json via the RestModuleFiles attribute
		// and passed to ModuleManager via the extensionModuleFiles constructor parameter.
		$routeFiles = array_merge(
			self::CORE_ROUTE_FILES,
			$this->extensionModuleFiles,
			$this->restApiAdditionalRouteFiles
		);
		$disabledRouteFiles = [];

		foreach ( $routeFiles as &$file ) {
			if ( str_starts_with( $file, 'extensions/' ) ) {
				// Support hacks like Wikibase.ci.php
				$file = substr_replace( $file, $this->extensionDirectory,
					0, strlen( 'extensions' ) );
			} elseif ( !str_starts_with( $file, '/' )
				&& !( wfIsWindows() && preg_match( '!^[a-zA-Z]:[/\\\\]!', $file ) )
			) {
				$file = MW_INSTALL_PATH . '/' . $file;
			}
		}
		unset( $file );

		// If the module's audience designation or configuration settings say it should be
		// disabled, then don't include it at all. Old-style flat route files cannot be disabled.
		foreach ( $routeFiles as $key => $file ) {
			$moduleDefInfo = $this->getModuleDefinitionInfo( $file );
			if (
				isset( $moduleDefInfo['moduleId'] ) &&
				$this->getModuleMode( $moduleDefInfo['moduleId'] ) === ModuleMode::DISABLED
			) {
				$disabledRouteFiles[$key] = $file;
				unset( $routeFiles[$key] );
			}
		}

		$this->routeFiles = $routeFiles;
		$this->disabledRouteFiles = $disabledRouteFiles;
	}

	/**
	 * Returns true if any api specs are available, or false otherwise.
	 *
	 * @return bool
	 */
	public function hasApiSpecs(): bool {
		// mw-extra is always available, so there is no need for logic here.
		//
		// TODO: deprecate this function once audience designations are fully implemented and
		//  rolled out, and it is clear that this function is no longer useful.
		return true;
	}

	/**
	 * Returns the available choices for APIs to explore.
	 *
	 * @return array<string,array<string,string>>
	 */
	public function getApiSpecs(): array {
		$coreSpecs = self::CORE_SPECS;
		foreach ( $coreSpecs as $key => &$spec ) {
			if ( isset( $spec['url'] ) ) {
				$spec['url'] = $this->scriptPath . $spec['url'];
			}
			$spec = $this->normalizeSpec( $key, $spec );

			if ( $spec['mode'] !== ModuleMode::PUBLISHED ) {
				unset( $coreSpecs[$key] );
			}
		}
		unset( $spec );

		$extensionSpecs = [];
		foreach ( $this->extensionModuleFiles as $file ) {
			$key = basename( $file, '.json' );
			$extensionSpecs[$key] = $this->normalizeSpec( $key, [ 'file' => $file ] );
		}

		// RestSandboxSpecs overrides everything else. If RestSandboxSpecs includes a module,
		// it will be published to the REST Sandbox, regardless of its audience designation or
		// any RestModuleOverrides configuration. This is for backwards compatibility.
		$rssSpecs = $this->restSandboxSpecs;
		foreach ( $rssSpecs as $key => &$spec ) {
			$spec = $this->normalizeSpec( $key, $spec );
		}
		unset( $spec );

		$specs = array_merge( $coreSpecs, $extensionSpecs, $rssSpecs );
		foreach ( $specs as $key => &$spec ) {
			unset( $spec['mode'] );
			$spec['group'] = $spec['params']['group'] ?? '';
			unset( $spec['params'] );
		}

		// This will put the "routes not in modules" entry first.
		$defaultName = self::CORE_SPECS['mw-extra']['name'];
		uasort( $specs, static function ( $a, $b ) use ( $defaultName ) {
			if ( $a['name'] === $defaultName ) {
				return -1;
			} elseif ( $b['name'] === $defaultName ) {
				return 1;
			} else {
				return strnatcasecmp( $a['name'], $b['name'] );
			}
		} );

		return $specs;
	}

	/**
	 * Normalizes a single spec definition, performing localization and loading from module
	 * definition files as needed.
	 *
	 * @param string $key spec definition array key, used as a fallback name if necessary
	 * @param array $spec the spec definition array
	 *
	 * @return array<string,mixed> the normalized spec definition
	 */
	private function normalizeSpec( string $key, array $spec ): array {
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

		// Always include sensible defaults
		$spec['mode'] ??= ModuleMode::PUBLISHED;
		$spec['params'] ??= [];

		return $spec;
	}

	/**
	 * Populates any missing spec details from the input module definition file.
	 * The return value also includes the ModuleMode that should be applied.
	 *
	 * @param array<string,string> $spec The sandbox spec. Must have a 'file' key.
	 * @param string $scriptPath
	 *
	 * @return array<string,string>
	 */
	private function populateFromFile( array $spec, string $scriptPath ): array {
		$hasUrl = isset( $spec['url'] );
		$hasName = isset( $spec['name'] ) || isset( $spec['msg'] );

		$moduleDefInfo = $this->getModuleDefinitionInfo( $spec['file'] );

		// Get any missing information from the module definition file, giving config priority
		if ( !$hasName ) {
			$spec['name'] = $moduleDefInfo['title'];
		}
		if ( !$hasUrl ) {
			$spec['url'] = $scriptPath . '/rest.php/specs/v0/module/' . $moduleDefInfo['moduleId'];
		}

		$spec['mode'] = $this->getModuleMode( $moduleDefInfo['moduleId'] );
		$spec['params'] = $this->getModeParams( $moduleDefInfo['moduleId'] );

		return $spec;
	}

	/**
	 * Gets necessary info from the module definition info, from cache if possible,
	 * from the definition file otherwise.
	 *
	 * @param string $file The module definition file to load
	 *
	 * @return array<string,string> The module definition info, or an empty array for flat routes
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
				// An exception here almost certainly means this is an old-style flat route file.
				try {
					$md = SpecBasedModule::loadModuleDefinition( $file, $this->responseFactory );
					return [
						'moduleId' => $md['moduleId'],
						'title' => $md['info']['title']
					];
				} catch ( ModuleFormatException ) {
					return [];
				}
			}
		);
	}
}
