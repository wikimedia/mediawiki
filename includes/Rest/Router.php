<?php

namespace MediaWiki\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Module\ExtraRoutesModule;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\SpecBasedModule;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use Throwable;
use Wikimedia\Http\HttpStatus;
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
	private const PREFIX_PATTERN = '!^/([-_.\w]+(?:/v[-_.\w]+)?)(/.*)$!';

	/** @var string[] */
	private $routeFiles;

	/** @var array[] */
	private $extraRoutes;

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
	private $cors;

	private BagOStuff $cacheBag;
	private ResponseFactory $responseFactory;
	private BasicAuthorizerInterface $basicAuth;
	private Authority $authority;
	private ObjectFactory $objectFactory;
	private Validator $restValidator;
	private ErrorReporter $errorReporter;
	private HookContainer $hookContainer;
	private Session $session;

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
	];

	/**
	 * @param string[] $routeFiles
	 * @param array[] $extraRoutes
	 * @param ServiceOptions $options
	 * @param BagOStuff $cacheBag A cache in which to store the matcher trees
	 * @param ResponseFactory $responseFactory
	 * @param BasicAuthorizerInterface $basicAuth
	 * @param Authority $authority
	 * @param ObjectFactory $objectFactory
	 * @param Validator $restValidator
	 * @param ErrorReporter $errorReporter
	 * @param HookContainer $hookContainer
	 * @param Session $session
	 * @internal
	 */
	public function __construct(
		array $routeFiles,
		array $extraRoutes,
		ServiceOptions $options,
		BagOStuff $cacheBag,
		ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth,
		Authority $authority,
		ObjectFactory $objectFactory,
		Validator $restValidator,
		ErrorReporter $errorReporter,
		HookContainer $hookContainer,
		Session $session
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->routeFiles = $routeFiles;
		$this->extraRoutes = $extraRoutes;
		$this->baseUrl = $options->get( MainConfigNames::CanonicalServer );
		$this->privateBaseUrl = $options->get( MainConfigNames::InternalServer );
		$this->rootPath = $options->get( MainConfigNames::RestPath );
		$this->scriptPath = $options->get( MainConfigNames::ScriptPath );
		$this->cacheBag = $cacheBag;
		$this->responseFactory = $responseFactory;
		$this->basicAuth = $basicAuth;
		$this->authority = $authority;
		$this->objectFactory = $objectFactory;
		$this->restValidator = $restValidator;
		$this->errorReporter = $errorReporter;
		$this->hookContainer = $hookContainer;
		$this->session = $session;
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
		return $this->cacheBag->makeKey( __CLASS__, 'map', '1' );
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
					'specFile' => $file
				];
			} else {
				// Old-style route file containing a flat list of routes.
				$noPrefixFiles[] = $file;
				$moduleInfo = null;
			}

			if ( $moduleInfo ) {
				if ( isset( $modules[$id] ) ) {
					$otherFiles = implode( ' and ', $modules[$id]['routeFiles'] );
					throw new ModuleConfigurationException(
						"Duplicate module $id in $file, also used in $otherFiles"
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
	 * @return string[]
	 */
	public function getModuleIds(): array {
		return array_keys( $this->getModuleMap() );
	}

	public function getModuleForPath( string $fullPath ): ?Module {
		[ $moduleName, ] = $this->splitPath( $fullPath );
		return $this->getModule( $moduleName );
	}

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

		if ( $this->cors ) {
			$module->setCors( $this->cors );
		}

		if ( $this->stats ) {
			$module->setStats( $this->stats );
		}

		$this->modules[$name] = $module;
		return $module;
	}

	/**
	 * @since 1.42
	 */
	public function getRoutePath(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		$routeWithModulePrefix = $this->substPathParams( $routeWithModulePrefix, $pathParams );
		$path = $this->rootPath . $routeWithModulePrefix;
		return wfAppendQuery( $path, $queryParams );
	}

	public function getRouteUrl(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->baseUrl . $this->getRoutePath( $routeWithModulePrefix, $pathParams, $queryParams );
	}

	public function getPrivateRouteUrl(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->privateBaseUrl . $this->getRoutePath( $routeWithModulePrefix, $pathParams, $queryParams );
	}

	/**
	 * @param string $route
	 * @param array $pathParams
	 *
	 * @return string
	 */
	protected function substPathParams( string $route, array $pathParams ): string {
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
			$extraData = [];
			if ( $this->isRestbaseCompatEnabled( $request )
				&& $e instanceof LocalizedHttpException
			) {
				$extraData = $this->getRestbaseCompatErrorData( $request, $e );
			}
			$response = $this->responseFactory->createFromException( $e, $extraData );
		} catch ( Throwable $e ) {
			$this->errorReporter->reportError( $e, null, $request );
			$response = $this->responseFactory->createFromException( $e );
		}

		// TODO: Only send the vary header for handlers that opt into
		//       restbase compat!
		$this->varyOnRestbaseCompat( $response );

		return $response;
	}

	private function doExecute( string $fullPath, RequestInterface $request ): ResponseInterface {
		[ $modulePrefix, $path ] = $this->splitPath( $fullPath );

		// If there is no path at all, redirect to "/".
		// That's the minimal path that can be routed.
		if ( $modulePrefix === '' && $path === '' ) {
			$target = $this->getRoutePath( '/' );
			return $this->responseFactory->createRedirect( $target, 308 );
		}

		$module = $this->getModule( $modulePrefix );

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
			$this->responseFactory,
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
		if ( $info['class'] === SpecBasedModule::class ) {
			$module = new SpecBasedModule(
				$info['specFile'],
				$this,
				$info['pathPrefix'] ?? $name,
				$this->responseFactory,
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
				$this->responseFactory,
				$this->basicAuth,
				$this->objectFactory,
				$this->restValidator,
				$this->errorReporter,
				$this->hookContainer
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
	 * @internal
	 *
	 * @return array
	 */
	public function getRestbaseCompatErrorData( RequestInterface $request, LocalizedHttpException $e ): array {
		$msg = $e->getMessageValue();

		// Match error fields emitted by the RESTBase endpoints.
		// EntryPoint::getTextFormatters() ensures 'en' is always available.
		return [
			'type' => "MediaWikiError/" .
				str_replace( ' ', '_', HttpStatus::getMessage( $e->getCode() ) ),
			'title' => $msg->getKey(),
			'method' => strtolower( $request->getMethod() ),
			'detail' => $this->responseFactory->getFormattedMessage( $msg, 'en' ),
			'uri' => (string)$request->getUri()
		];
	}
}
