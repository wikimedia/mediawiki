<?php

namespace MediaWiki\Rest\Module;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\JsonLocalizer;
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
		'includes/Rest/fragments.v0-internal.json',
		'includes/Rest/content.v2-beta.json',
	];

	/**
	 * Static route path prefix for local OpenAPI specification endpoints.
	 * Corresponds to the route template in Router::ROUTE_MODULE_SPEC.
	 */
	private const ROUTE_MODULE_SPEC_PREFIX = '/specs/v0/module/';

	// These specs will be available in the REST Sandbox. No config change is needed.
	// Preserved for legacy compatibility with Special:RestSandbox. Can and should
	// update for consistency as a dedicated extension, and remove once MediaWiki
	// transitions to Unified Developer Front Door
	// (https://phabricator.wikimedia.org/project/board/9020/).
	private const CORE_SPECS = [
		'mw-extra' => [
			'url' => self::ROUTE_MODULE_SPEC_PREFIX . '-',
			'name' => 'MediaWiki REST API (routes not in modules)',
		],
		'specs.v0' => [
			'file' => "./includes/Rest/specs.v0.json",
		],
		'site.v1' => [
			'file' => "./includes/Rest/site.v1.json",
		],
		'fragments.v0-internal' => [
			'file' => "./includes/Rest/fragments.v0-internal.json",
		],
		'content.v2-beta' => [
			'file' => "./includes/Rest/content.v2-beta.json",
		],
	];

	/** Seconds to persist module definitions on cache */
	private const MODULE_DEFINITION_TTL = 60;

	/** @var string[]|null */
	private ?array $routeFiles = null;
	private ?array $disabledRouteFiles = null;

	private readonly string $extensionDirectory;

	private readonly array $restApiAdditionalRouteFiles;
	private readonly array $restExternalModules;
	private readonly array $restModuleOverrides;

	private string $rootPath;
	private ?array $moduleInfos = null;

	/**
	 * Map of local module IDs to their definition file basenames (without .json).
	 * Preserved for legacy compatibility with Special:RestSandbox. Can and should
	 * update for consistency as a dedicated extension, and remove once MediaWiki
	 * transitions to Unified Developer Front Door.
	 *
	 * @var array<string,string>
	 */
	private array $localModuleFileBasenames = [];

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ExtensionDirectory,
		MainConfigNames::RestAPIAdditionalRouteFiles,
		MainConfigNames::RestExternalModules,
		MainConfigNames::RestModuleOverrides,
		MainConfigNames::RestPath
	];

	/**
	 * @param ServiceOptions $options
	 * @param string[] $extensionModuleFiles
	 * @param BagOStuff $srvCache Optional BagOStuff instance to an APC-style cache.
	 * @param JsonLocalizer $jsonLocalizer
	 *
	 * @internal
	 */
	public function __construct(
		ServiceOptions $options,
		private readonly array $extensionModuleFiles,
		private readonly BagOStuff $srvCache,
		private readonly JsonLocalizer $jsonLocalizer,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->extensionDirectory = $options->get( MainConfigNames::ExtensionDirectory );
		$this->restApiAdditionalRouteFiles = $options->get( MainConfigNames::RestAPIAdditionalRouteFiles );
		$this->restExternalModules = $options->get( MainConfigNames::RestExternalModules );
		$this->restModuleOverrides = $options->get( MainConfigNames::RestModuleOverrides );
		$this->rootPath = $options->get( MainConfigNames::RestPath );
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
		if ( isset( $this->restModuleOverrides[$moduleId]['availability'] ) ) {
			$mm = ModuleMode::tryFrom( $this->restModuleOverrides[$moduleId]['availability'] );
			$mm ??= ModuleMode::DISABLED;
		}

		if ( $moduleId === '' ) {
			return $mm ?? ModuleMode::PUBLISHED;
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
		unset( $overrideParams['availability'] );

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
	 * Returns an array of ModuleInfo objects for all registered modules, indexed by module ID.
	 * This method aggregates and returns both local (Core and Extension) and external modules.
	 *
	 * @return ModuleInfo[]
	 * @since 1.47
	 */
	public function getModuleInfos(): array {
		if ( $this->moduleInfos !== null ) {
			return $this->moduleInfos;
		}

		$modules = [];
		$this->localModuleFileBasenames = [];

		// Gather local modules.
		$routeFiles = $this->getRouteFiles();
		$disabledRouteFiles = $this->getDisabledRouteFiles();
		$allLocalRouteFiles = array_merge( $routeFiles, $disabledRouteFiles );

		foreach ( $allLocalRouteFiles as $file ) {
			$moduleDefInfo = $this->getModuleDefinitionInfo( $file );
			if ( !$moduleDefInfo ) {
				continue;
			}

			$moduleId = $moduleDefInfo['moduleId'];
			$this->localModuleFileBasenames[$moduleId] = basename( $file, '.json' );
			$availability = $this->getModuleMode( $moduleId );
			$params = $this->getModeParams( $moduleId );
			$groups = (array)( $params['groups'] ?? $moduleDefInfo['groups'] ?? [] );

			$modules[$moduleId] = new ModuleInfo(
				$moduleId,
				$availability,
				false, // isExternal
				$moduleDefInfo['title'] ?? $moduleId,
				$moduleDefInfo['description'] ?? null,
				$moduleDefInfo['version'] ?? null,
				$groups
			);
		}

		// Add the prefix-less module.
		$emptyModuleAvailability = $this->getModuleMode( '' );
		$emptyParams = $this->getModeParams( '' );
		$modules[''] = new ModuleInfo(
			'',
			$emptyModuleAvailability,
			false, // isExternal
			self::CORE_SPECS['mw-extra']['name'],
			$this->jsonLocalizer->getFormattedMessage( 'rest-module-extra-routes-desc' ),
			'0.1.0',
			(array)( $emptyParams['groups'] ?? [] )
		);

		// Gather external modules.
		foreach ( $this->restExternalModules as $externalModuleId => $externalModuleConfig ) {
			$availability = $this->getModuleMode( $externalModuleId );
			$externalModuleConfig = $this->jsonLocalizer->localizeJson( $externalModuleConfig );
			$params = $this->getModeParams( $externalModuleId );
			$groups = (array)( $params['groups'] ?? [] );

			$modules[$externalModuleId] = new ModuleInfo(
				$externalModuleId,
				$availability,
				true, // isExternal
				$externalModuleConfig['info']['title'] ?? $externalModuleId,
				$externalModuleConfig['info']['description'] ?? null,
				$externalModuleConfig['info']['version'] ?? null,
				$groups,
				$externalModuleConfig['base'] ?? null,
				$externalModuleConfig['spec'] ?? null
			);
		}

		// Sort modules case-insensitively, putting prefix-less module first.
		uksort( $modules, static function ( $a, $b ) {
			if ( $a === '' ) {
				return -1;
			}
			if ( $b === '' ) {
				return 1;
			}
			return strnatcasecmp( $a, $b );
		} );

		$this->moduleInfos = $modules;
		return $this->moduleInfos;
	}

	/**
	 * Gets the ModuleInfo for a single module by ID, or null if not found.
	 *
	 * @param string $moduleId
	 * @return ?ModuleInfo
	 * @since 1.47
	 */
	public function getModuleInfo( string $moduleId ): ?ModuleInfo {
		$infos = $this->getModuleInfos();
		return $infos[$moduleId] ?? null;
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
	 * Returns the available choices for APIs to explore in the REST Sandbox.
	 *
	 * Preserved for legacy compatibility with Special:RestSandbox. Can and should
	 * update for consistency as a dedicated extension, and remove once MediaWiki
	 * transitions to Unified Developer Front Door.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function getApiSpecs(): array {
		$specs = [];
		$defaultName = self::CORE_SPECS['mw-extra']['name'];

		foreach ( $this->getModuleInfos() as $info ) {
			if ( $info->getAvailability() !== ModuleMode::PUBLISHED ) {
				continue;
			}

			if ( $info->getId() === '' ) {
				$key = 'mw-extra';
			} elseif ( $info->isExternal() ) {
				$key = $info->getId();
			} else {
				$key = $this->localModuleFileBasenames[$info->getId()]
					?? str_replace( '/', '.', $info->getId() );
			}

			if ( $info->isExternal() ) {
				$url = $info->getExternalSpecUrl();
			} else {
				$moduleParam = $info->getId() === '' ? '-' : $info->getId();
				$url = $this->rootPath . self::ROUTE_MODULE_SPEC_PREFIX . $moduleParam;
			}

			$specs[$key] = [
				'url' => $url,
				'name' => $info->getTitle() ?? ( $info->getId() === '' ? $defaultName : $info->getId() ),
				'groups' => $info->getGroups(),
			];
		}

		// This will put the "routes not in modules" entry first.
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
	 * Gets necessary info from the module definition info, from cache if possible,
	 * from the definition file otherwise.
	 *
	 * @param string $file The module definition file to load
	 *
	 * @return array<string,mixed> The module definition info, or an empty array for flat routes
	 */
	private function getModuleDefinitionInfo( string $file ): array {
		$key = $this->srvCache->makeKey(
			__CLASS__,
			'definition',
			'v2',
			sha1( $file ),
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			(int)@filemtime( $file ),
			$this->jsonLocalizer->getLangCode()
		);

		return $this->srvCache->getWithSetCallback(
			$key,
			self::MODULE_DEFINITION_TTL,
			function () use ( $file ) {
				// An exception here almost certainly means this is an old-style flat route file.
				try {
					$md = SpecBasedModule::loadModuleDefinition( $file, $this->jsonLocalizer );
					return [
						'moduleId' => $md['moduleId'],
						'title' => $md['info']['title'] ?? null,
						'version' => $md['info']['version'] ?? null,
						'description' => $md['info']['description'] ?? null,
						'deprecationSettings' => $md['info']['deprecationSettings'] ?? null,
						'groups' => (array)( $md['info']['groups'] ?? [] ),
					];
				} catch ( ModuleFormatException ) {
					return [];
				}
			}
		);
	}
}
