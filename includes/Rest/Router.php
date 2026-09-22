<?php

namespace MediaWiki\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Http\Telemetry;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Module\ExtraRoutesModule;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\Module\SpecBasedModule;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use MediaWiki\Utils\UrlUtils;
use Throwable;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Stats\StatsFactory;

/**
 * The REST router is responsible for gathering module configuration, matching
 * an input path against the defined modules, and constructing
 * and executing the relevant module for a request.
 */
class Router {
	public const ROUTE_MODULE_SPEC = '/specs/v0/module/{module}';

	private const PREFIX_PATTERN = '!^/([-_.\w]+(?:/v[-_.\w]+)?)(/.*)$!';

	public const DEFAULT_ERROR_SCHEMA = '1.0';

	private const ERROR_FORMATTERS = [
		'restbase' => [ 'class' => RestbaseCompatErrorFormatter::class ],
		'1.0' => [ 'class' => ErrorFormatterV1::class ],
		'2.0' => [ 'class' => ErrorFormatterV2::class ],
	];

	/** @var string[] */
	private $routeFiles;

	/** @var null|array[] */
	private $moduleMap = null;

	/** @var Module[] */
	private $modules = [];

	/** @var int[]|null */
	private $moduleFileTimestamps = null;

	/** @var string */
	private $baseUrl;

	/** @var string */
	private $privateBaseUrl;

	/** @var string */
	private $rootPath;

	/** @var string */
	private $scriptPath;

	/** @var string|null */
	private $configHash = null;

	/** @var CorsUtils|null */
	private $cors = null;

	/** @var ?StatsFactory */
	private $stats = null;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CanonicalServer,
		MainConfigNames::InternalServer,
		MainConfigNames::RestPath,
		MainConfigNames::ScriptPath,
		MainConfigNames::Sitename,
		MainConfigNames::EmergencyContact,
		MainConfigNames::RestTermsOfServiceUrl,
	];

	/**
	 * @param ModuleManager $moduleManager
	 * @param array[] $extraRoutes
	 * @param ServiceOptions $options
	 * @param BagOStuff $cacheBag A cache in which to store the matcher trees
	 * @param array $textFormatters
	 * @param bool $showExceptionDetails
	 * @param BasicAuthorizerInterface $basicAuth
	 * @param Authority $authority
	 * @param ObjectFactory $objectFactory
	 * @param Validator $restValidator
	 * @param ErrorReporter $errorReporter
	 * @param HookContainer $hookContainer
	 * @param Session $session
	 * @param UrlUtils $urlUtils
	 * @internal
	 */
	public function __construct(
		private readonly ModuleManager $moduleManager,
		private readonly array $extraRoutes,
		private readonly ServiceOptions $options,
		private readonly BagOStuff $cacheBag,
		private readonly array $textFormatters,
		private readonly bool $showExceptionDetails,
		private readonly BasicAuthorizerInterface $basicAuth,
		private readonly Authority $authority,
		private readonly ObjectFactory $objectFactory,
		private readonly Validator $restValidator,
		private ErrorReporter $errorReporter,
		private readonly HookContainer $hookContainer,
		private readonly Session $session,
		private readonly UrlUtils $urlUtils,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->routeFiles = $moduleManager->getRouteFiles();
		$this->baseUrl = $options->get( MainConfigNames::CanonicalServer );
		$this->privateBaseUrl = $options->get( MainConfigNames::InternalServer );
		$this->rootPath = $options->get( MainConfigNames::RestPath );
		$this->scriptPath = $options->get( MainConfigNames::ScriptPath );

		Assert::parameter( count( $textFormatters ) > 0, '$textFormatters', 'must not be empty' );
	}

	/**
	 * Remove the REST path prefix. Return the part of the path with the
	 * prefix removed, or false if the prefix did not match.
	 * Both the $this->rootPath and the default REST path are accepted,
	 * so on a site that uses /api as the RestPath, requests to /w/rest.php
	 * still work. This is equivalent to supporting both /wiki and /w/index.php
	 * for page views.
	 *
	 * @param string $path
	 * @return false|string
	 */
	private function getRelativePath( $path ) {
		$allowed = [
			$this->rootPath,
			MainConfigSchema::getDefaultRestPath( $this->scriptPath )
		];

		foreach ( $allowed as $prefix ) {
			if ( str_starts_with( $path, $prefix ) ) {
				return substr( $path, strlen( $prefix ) );
			}
		}

		return false;
	}

	/**
	 * @param string $fullPath
	 *
	 * @return string[] [ string $module, string $path ]
	 */
	private function splitPath( string $fullPath ): array {
		$pathWithModule = $this->getRelativePath( $fullPath );

		if ( $pathWithModule === false ) {
			throw new LocalizedHttpException(
				( new MessageValue( 'rest-prefix-mismatch' ) )
					->plaintextParams( $fullPath, $this->rootPath ),
				404
			);
		}

		if ( preg_match( self::PREFIX_PATTERN, $pathWithModule, $matches ) ) {
			[ , $module, $pathUnderModule ] = $matches;
		} else {
			// No prefix found in the given path, assume prefix-less module.
			$module = '';
			$pathUnderModule = $pathWithModule;
		}

		if ( $module !== '' && !$this->getModuleInfo( $module ) ) {
			// Prefix doesn't match any module, try the prefix-less module...
			// TODO: At some point in the future, we'll want to warn and redirect...
			$module = '';
			$pathUnderModule = $pathWithModule;
		}

		return [ $module, $pathUnderModule ];
	}

	/**
	 * Get the cache data, or false if it is missing or invalid
	 */
	private function fetchCachedModuleMap(): ?array {
		$moduleMapCacheKey = $this->getModuleMapCacheKey();
		$cacheData = $this->cacheBag->get( $moduleMapCacheKey );
		if ( $cacheData && $cacheData[Module::CACHE_CONFIG_HASH_KEY] === $this->getModuleMapHash() ) {
			unset( $cacheData[Module::CACHE_CONFIG_HASH_KEY] );
			return $cacheData;
		} else {
			return null;
		}
	}

	private function fetchCachedModuleData( string $module ): ?array {
		$moduleDataCacheKey = $this->getModuleDataCacheKey( $module );
		$cacheData = $this->cacheBag->get( $moduleDataCacheKey );
		return $cacheData ?: null;
	}

	private function cacheModuleMap( array $map ) {
		$map[Module::CACHE_CONFIG_HASH_KEY] = $this->getModuleMapHash();
		$moduleMapCacheKey = $this->getModuleMapCacheKey();
		$this->cacheBag->set( $moduleMapCacheKey, $map );
	}

	private function cacheModuleData( string $module, array $map ) {
		$moduleDataCacheKey = $this->getModuleDataCacheKey( $module );
		$this->cacheBag->set( $moduleDataCacheKey, $map );
	}

	private function getModuleDataCacheKey( string $module ): string {
		if ( $module === '' ) {
			// Proper key for the prefix-less module.
			$module = '-';
		}
		return $this->cacheBag->makeKey( __CLASS__, 'module', $module );
	}

	private function getModuleMapCacheKey(): string {
		return $this->cacheBag->makeKey( __CLASS__, 'map', '2' );
	}

	/**
	 * Get a config version hash for cache invalidation
	 */
	private function getModuleMapHash(): string {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				$this->extraRoutes,
				$this->getModuleFileTimestamps()
			] ) );
		}
		return $this->configHash;
	}

	private function buildModuleMap(): array {
		$modules = [];
		$noPrefixFiles = [];
		$id = ''; // should not be used, make Phan happy

		foreach ( $this->routeFiles as $file ) {
			// NOTE: we end up loading the file here (for the meta-data) as well
			// as in the Module object (for the routes). But since we have
			// caching on both levels, that shouldn't matter.
			$spec = Module::loadJsonFile( $file );

			if ( isset( $spec['mwapi'] ) || isset( $spec['moduleId'] ) || isset( $spec['routes'] ) ) {
				// OpenAPI 3, with some extras like the "module" field
				if ( !isset( $spec['moduleId'] ) ) {
					throw new ModuleConfigurationException(
						"Missing 'moduleId' field in $file"
					);
				}

				$id = $spec['moduleId'];

				$moduleInfo = [
					'class' => SpecBasedModule::class,
					'pathPrefix' => $id,
					'specFile' => $file,
					'errorSchemaVersion' => $spec['errorSchemaVersion'] ?? null,
				];
			} else {
				// Old-style route file containing a flat list of routes.
				$noPrefixFiles[] = $file;
				$moduleInfo = null;
			}

			if ( $moduleInfo ) {
				// Config and ModuleManager's CORE_ROUTE_FILES may legitimately refer to the same
				// module file by different routes. The same moduleId in files with different
				// names is almost certainly an error.
				if (
					isset( $modules[$id] ) &&
					basename( $modules[$id]['specFile'] ) !== basename( $file )
				) {
					throw new ModuleConfigurationException(
						"Duplicate module $id in $file"
					);
				}

				$modules[$id] = $moduleInfo;
			}
		}

		// The prefix-less module will be used when no prefix is matched.
		// It provides a mechanism to integrate extra routes and route files
		// registered by extensions.
		if ( $noPrefixFiles || $this->extraRoutes ) {
			$modules[''] = [
				'class' => ExtraRoutesModule::class,
				'pathPrefix' => '',
				'routeFiles' => $noPrefixFiles,
				'extraRoutes' => $this->extraRoutes,
				'errorSchemaVersion' => null,
			];
		}

		return $modules;
	}

	/**
	 * Get an array of last modification times of the defined route files.
	 *
	 * @return int[] Last modification times
	 */
	private function getModuleFileTimestamps() {
		if ( $this->moduleFileTimestamps === null ) {
			$this->moduleFileTimestamps = [];
			foreach ( $this->routeFiles as $fileName ) {
				$this->moduleFileTimestamps[$fileName] = filemtime( $fileName );
			}
		}
		return $this->moduleFileTimestamps;
	}

	private function getModuleMap(): array {
		if ( !$this->moduleMap ) {
			$map = $this->fetchCachedModuleMap();

			if ( !$map ) {
				$map = $this->buildModuleMap();
				$this->cacheModuleMap( $map );
			}

			$this->moduleMap = $map;
		}
		return $this->moduleMap;
	}

	private function getModuleInfo( string $module ): ?array {
		$map = $this->getModuleMap();
		return $map[$module] ?? null;
	}

	/**
	 * @return ModuleManager
	 */
	public function getModuleManager(): ModuleManager {
		return $this->moduleManager;
	}

	/**
	 * @return string[]
	 */
	public function getModuleIds(): array {
		return array_keys( $this->getModuleMap() );
	}

	/**
	 * Returns an uninitialized module by full path.
	 * @deprecated since 1.47, use getModuleForRequest() instead.
	 */
	public function getModuleForPath( string $fullPath ): ?Module {
		wfDeprecated( __METHOD__, '1.47' );

		[ $moduleName, ] = $this->splitPath( $fullPath );
		return $this->getModule( $moduleName );
	}

	/**
	 * Returns a module suitable for handling the given request.
	 *
	 * @param RequestInterface $request
	 * @param string|null $name The module name, if known. Will be derived from
	 *        $request if not given.
	 *
	 * @return Module|null
	 * @since since 1.47
	 */
	public function getModuleForRequest( RequestInterface $request, ?string $name ): ?Module {
		if ( $name === null ) {
			$fullPath = $request->getUri()->getPath();
			[ $name, ] = $this->splitPath( $fullPath );
		}

		$module = $this->getModule( $name );
		$info = $this->getModuleInfo( $name );

		if ( !$module || !$info ) {
			return null;
		}

		$responseFactory = $this->getModuleResponseFactory( $info, $request );
		$module->initForExecute( $responseFactory );

		if ( $this->cors ) {
			$module->setCors( $this->cors );
		}

		if ( $this->stats ) {
			$module->setStats( $this->stats );
		}

		return $module;
	}

	/**
	 * Returns an uninitialized module by name.
	 * @note To get a module that can be used for handling a request,
	 * use getModuleForRequest() instead.
	 */
	public function getModule( string $name ): ?Module {
		if ( isset( $this->modules[$name] ) ) {
			return $this->modules[$name];
		}

		$info = $this->getModuleInfo( $name );

		if ( !$info ) {
			return null;
		}

		$module = $this->instantiateModule( $info, $name );

		$cacheData = $this->fetchCachedModuleData( $name );

		if ( $cacheData !== null ) {
			$cacheOk = $module->initFromCacheData( $cacheData );
		} else {
			$cacheOk = false;
		}

		if ( !$cacheOk ) {
			$cacheData = $module->getCacheData();
			$this->cacheModuleData( $name, $cacheData );
		}

		$this->modules[$name] = $module;
		return $module;
	}

	/**
	 * @since 1.42
	 * @todo This should be called getRelativeRouteUrl() since query parameters are included
	 */
	public function getRoutePath(
		string $pathWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		$pathWithModulePrefix = self::substPathParams( $pathWithModulePrefix, $pathParams );
		$path = $this->rootPath . $pathWithModulePrefix;
		return wfAppendQuery( $path, $queryParams );
	}

	public function getRouteUrl(
		string $pathWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->baseUrl . $this->getRoutePath( $pathWithModulePrefix, $pathParams, $queryParams );
	}

	public function getPrivateRouteUrl(
		string $pathWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->privateBaseUrl . $this->getRoutePath( $pathWithModulePrefix, $pathParams, $queryParams );
	}

	/**
	 * Gets the absolute base URL for a given module ID.
	 *
	 * For external modules, UrlUtils expands any relative URL.
	 * For local modules, the route URL is generated from the module ID.
	 *
	 * @param string $moduleId The module ID
	 * @return string|null The absolute base URL, or null if the module is unresolvable
	 * @throws UnexpectedValueException If an external module has no base URL configured
	 * @since 1.47
	 */
	public function getModuleBaseUrl( string $moduleId ): ?string {
		$info = $this->moduleManager->getModuleInfo( $moduleId );
		if ( !$info ) {
			return null;
		}

		if ( $info->isExternal() ) {
			$baseUrl = $info->getExternalBaseUrl();
			if ( $baseUrl === null ) {
				throw new UnexpectedValueException(
					"External module '$moduleId' has no base URL configured"
				);
			}
			return $this->urlUtils->expand( $baseUrl );
		}

		return $this->getRouteUrl( '/' . $moduleId );
	}

	/**
	 * Gets the absolute OpenAPI specification URL for a given module ID.
	 *
	 * For external modules, UrlUtils expands the spec URL.
	 * For local modules, returns the default route URL defined by self::ROUTE_MODULE_SPEC.
	 *
	 * @param string $moduleId The module ID
	 * @return string|null The absolute spec URL, or null if the module is unresolvable
	 * @throws UnexpectedValueException If an external module has no spec URL configured
	 * @since 1.47
	 */
	public function getModuleSpecUrl( string $moduleId ): ?string {
		$info = $this->moduleManager->getModuleInfo( $moduleId );
		if ( !$info ) {
			return null;
		}

		if ( $info->isExternal() ) {
			$specUrl = $info->getExternalSpecUrl();
			if ( $specUrl === null ) {
				throw new UnexpectedValueException(
					"External module '$moduleId' has no spec URL configured"
				);
			}
			return $this->urlUtils->expand( $specUrl );
		}

		$moduleParam = $moduleId === '' ? '-' : $moduleId;
		return $this->getRouteUrl( self::ROUTE_MODULE_SPEC, [ 'module' => $moduleParam ] );
	}

	/**
	 * Substitute parameters into a template string, following the
	 * requirements for path parameters: Spaces are encoded as %20 (not +)
	 * and slashes are encoded as %2F, other characters with special meaning
	 * are encoded as they would be for query parameters.
	 *
	 * @param string $route A path with {placeholders}
	 * @param array $pathParams
	 *
	 * @return string
	 * @since 1.47 (was protected/internal before that)
	 */
	public static function substPathParams( string $route, array $pathParams ): string {
		foreach ( $pathParams as $param => $value ) {
			// NOTE: we use rawurlencode here, since execute() uses rawurldecode().
			// Spaces in path params must be encoded to %20 (not +).
			// Slashes must be encoded as %2F.
			$route = str_replace( '{' . $param . '}', rawurlencode( (string)$value ), $route );
		}
		return $route;
	}

	public function execute( RequestInterface $request ): ResponseInterface {
		try {
			$fullPath = $request->getUri()->getPath();
			$response = $this->doExecute( $fullPath, $request );
		} catch ( HttpException $e ) {
			$response = $this->createResponseFromException( $e, $request );
		} catch ( Throwable $e ) {
			$this->errorReporter->reportError( $e, null, $request );
			$response = $this->createResponseFromException( $e, $request );
		}

		// TODO: Only send the vary header for handlers that opt into
		//       restbase compat!
		$this->varyOnRestbaseCompat( $response );

		// Apply CORS headers to every response, including router-level errors
		// (unknown module, prefix mismatch), redirects, and top-level
		// exceptions that never reach a Module. Preflight responses created in
		// Module::throwNoMatch() also pass through here to gain their
		// Access-Control-Allow-Origin header.
		if ( $this->cors ) {
			$this->cors->modifyResponse( $request, $response );
		}

		return $response;
	}

	private function createResponseFromException( Throwable $e, RequestInterface $request ): ResponseInterface {
		$responseFactory = $this->getModuleResponseFactory( [], $request );
		return $responseFactory->createFromException( $e );
	}

	private function createRedirectResponse( string $target, int $code, RequestInterface $request ): ResponseInterface {
		$responseFactory = $this->getModuleResponseFactory( [], $request );
		return $responseFactory->createRedirect( $target, $code );
	}

	private function doExecute( string $fullPath, RequestInterface $request ): ResponseInterface {
		[ $modulePrefix, $path ] = $this->splitPath( $fullPath );

		// If there is no path at all, redirect to "/".
		// That's the minimal path that can be routed.
		if ( $modulePrefix === '' && $path === '' ) {
			$target = $this->getRoutePath( '/' );
			return $this->createRedirectResponse( $target, 308, $request );
		}

		$module = $this->getModuleForRequest( $request, $modulePrefix );

		if ( !$module ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->plaintextParams( $modulePrefix ),
				404,
				[ 'prefix' => $modulePrefix ]
			);
		}

		return $module->execute( $path, $request );
	}

	/**
	 * Prepare the handler by injecting relevant service objects and state
	 * into $handler.
	 *
	 * @internal
	 */
	public function prepareHandler( Handler $handler ) {
		// Injecting services in the Router class means we don't have to inject
		// them into each Module.
		$handler->initServices(
			$this->authority,
			$this->hookContainer
		);

		$handler->initSession( $this->session );
	}

	public function setCors( CorsUtils $cors ): self {
		$this->cors = $cors;

		return $this;
	}

	/**
	 * @internal
	 *
	 * @param StatsFactory $stats
	 *
	 * @return self
	 */
	public function setStats( StatsFactory $stats ): self {
		$this->stats = $stats;

		return $this;
	}

	private function instantiateModule( array $info, string $name ): Module {
		// NOTE: $this->textFormatters are in the order of preference.
		//       See EntryPoint::getTextFormaters().
		//       Use the first one.
		$defaultFormatter = array_first( $this->textFormatters );
		$jsonLocalizer = new JsonLocalizer( $defaultFormatter );

		if ( $info['class'] === SpecBasedModule::class ) {
			$module = new SpecBasedModule(
				$info['specFile'],
				$this,
				$info['pathPrefix'] ?? $name,
				$jsonLocalizer,
				$this->basicAuth,
				$this->objectFactory,
				$this->restValidator,
				$this->errorReporter,
				$this->hookContainer
			);
		} else {
			$module = new ExtraRoutesModule(
				$info['routeFiles'] ?? [],
				$info['extraRoutes'] ?? [],
				$this,
				$jsonLocalizer,
				$this->basicAuth,
				$this->objectFactory,
				$this->restValidator,
				$this->errorReporter,
				$this->hookContainer,
				new ServiceOptions( ExtraRoutesModule::CONSTRUCTOR_OPTIONS, $this->options )
			);
		}

		return $module;
	}

	/**
	 * @internal
	 *
	 * @return bool
	 */
	public function isRestbaseCompatEnabled( RequestInterface $request ): bool {
		// See T374136
		return $request->getHeaderLine( 'x-restbase-compat' ) === 'true';
	}

	private function varyOnRestbaseCompat( ResponseInterface $response ) {
		// See T374136
		$response->addHeader( 'Vary', 'x-restbase-compat' );
	}

	/**
	 * Provide information about the request, for use by ErrorFormatters.
	 * All data returned by this method may be sent to the client verbatim.
	 * However, the content of this array is not an API contract, it may
	 * change at any time. It's intended for diagnostic purposes on the
	 * client side, and, more importantly, when clients report errors
	 * upstream.
	 */
	private function getTracingData( string $method, string $uri ): array {
		$tracingData = [
			'module' => 'mediawiki',
			'method' => strtolower( $method ),
			'uri' => $uri,
			'request_id' => Telemetry::getInstance()->getRequestId()
		];

		$url = $this->urlUtils->expand( $uri, PROTO_CANONICAL );
		if ( $url !== null ) {
			$tracingData['url'] = $url;
		}

		return $tracingData;
	}

	private function getModuleResponseFactory( array $moduleInfo, RequestInterface $request ): ResponseFactory {
		$schemaVer = $moduleInfo['errorSchemaVersion'] ?? null;

		if ( $this->isRestbaseCompatEnabled( $request ) ) {
			$schemaVer = 'restbase';
		}

		$schemaVer ??= self::DEFAULT_ERROR_SCHEMA;
		$formatterSpec = self::ERROR_FORMATTERS[ $schemaVer ] ?? null;

		if ( !$formatterSpec ) {
			throw new ModuleConfigurationException( "Unsupported errorSchemaVersion: $schemaVer" );
		}

		$errorFormatter = $this->objectFactory->createObject(
			$formatterSpec,
			[
				'assertClass' => ErrorFormatter::class,
				'extraArgs' => [
					$this->textFormatters,
					$this->showExceptionDetails,
					$this->getTracingData( $request->getMethod(), (string)$request->getUri() )
				],
			]
		);

		return new ResponseFactory( $this->textFormatters, $errorFormatter );
	}
}
