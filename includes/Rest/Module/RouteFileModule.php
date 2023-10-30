<?php

namespace MediaWiki\Rest\Module;

use AppendIterator;
use ArrayIterator;
use Iterator;
use LogicException;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Handler\RedirectHandler;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\RouteDefinitionException;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A Module that is based on route definition files. This module responds to
 * requests by matching the requested path against a list of known routes to
 * identify the appropriate handler. The routes are loaded for route definition
 * files.
 *
 * Two versions of route declaration files are currently supported, "flat"
 * route files and "annotated" route files. Both use JSON syntax. Flat route
 * files are supported for backwards compatibility, and should be avoided.
 *
 * Flat files just contain a list (a JSON array) or route definitions (see below).
 * Annotated route definition files contain a map (a JSON object) with the
 * following fields:
 * - "module": the module name (string). The router uses this name to find the
 *   correct module for handling a request by matching it against the prefix
 *   of the request path. The module name must be unique.
 * - "routes": a list (JSON array) or route definitions (see below).
 *
 * Each route definition maps a path pattern to a handler class. It is given as
 * a map (JSON object) with the following fields:
 * - "path": the path pattern (string) relative to the module prefix. Required.
 *   The path may contain placeholders for path parameters.
 * - "method": the HTTP method(s) or "verbs" supported by the route. If not given,
 *   it is assumed that the route supports the "GET" method. The "OPTIONS" method
 *   for CORS is supported implicitly.
 * - "class" or "factory": The handler class (string) or factory function
 *   (callable) of an "object spec" for use with ObjectFactory::createObject.
 *   See there for the usage of additional fields like "services". If a shorthand
 *   is used (see below), no object spec is needed.
 *
 * The following fields are supported as a shorthand notation:
 * - "redirect": the route represents a redirect and will be handled by
 *   the RedirectHandler class. The redirect is specified as a JSON object
 *   that specifies the target "path", and optional the redirect "code".
 *
 * More shorthands may be added in the future.
 *
 * Route definitions can contain additional fields to configure the handler.
 * The handler can access the route definition by calling getConfig().
 *
 * @internal
 * @since 1.43
 */
class RouteFileModule extends Module {

	/** @var string[] */
	private array $routeFiles;

	/**
	 * @var array<int,array> A list of route definitions
	 */
	private array $extraRoutes;

	/**
	 * @var array<int,array>|null A list of route definitions loaded from
	 * the files specified by $routeFiles
	 */
	private ?array $routesFromFiles = null;

	/** @var int[]|null */
	private ?array $routeFileTimestamps = null;

	/** @var string|null */
	private ?string $configHash = null;

	/** @var PathMatcher[]|null Path matchers by method */
	private ?array $matchers = null;

	/**
	 * @param string[] $routeFiles List of names of JSON files containing routes
	 *        See the documentation of this class for a description of the file
	 *        format.
	 * @param array<int,array> $extraRoutes Extension route array. The content of
	 *        this array must be a list of route definitions. See the documentation
	 *        of this class for a description of the expected structure.
	 */
	public function __construct(
		array $routeFiles,
		array $extraRoutes,
		Router $router,
		string $pathPrefix,
		ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth,
		ObjectFactory $objectFactory,
		Validator $restValidator,
		ErrorReporter $errorReporter
	) {
		parent::__construct(
			$router,
			$pathPrefix,
			$responseFactory,
			$basicAuth,
			$objectFactory,
			$restValidator,
			$errorReporter
		);
		$this->routeFiles = $routeFiles;
		$this->extraRoutes = $extraRoutes;
	}

	public function getCacheData(): array {
		$cacheData = [];

		foreach ( $this->getMatchers() as $method => $matcher ) {
			$cacheData[$method] = $matcher->getCacheData();
		}

		$cacheData[self::CACHE_CONFIG_HASH_KEY] = $this->getConfigHash();
		return $cacheData;
	}

	public function initFromCacheData( array $cacheData ): bool {
		if ( $cacheData[self::CACHE_CONFIG_HASH_KEY] !== $this->getConfigHash() ) {
			return false;
		}

		unset( $cacheData[self::CACHE_CONFIG_HASH_KEY] );
		$this->matchers = [];

		foreach ( $cacheData as $method => $data ) {
			$this->matchers[$method] = PathMatcher::newFromCache( $data );
		}

		return true;
	}

	/**
	 * Get a config version hash for cache invalidation
	 *
	 * @return string
	 */
	private function getConfigHash(): string {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				'version' => 5,
				'extraRoutes' => $this->extraRoutes,
				'fileTimestamps' => $this->getRouteFileTimestamps()
			] ) );
		}
		return $this->configHash;
	}

	/**
	 * Load the defined JSON files and return the merged routes.
	 *
	 * @return array<int,array> A list of route definitions. See this class's
	 *         documentation for a description of the format of route definitions.
	 * @throws ModuleConfigurationException If a route file cannot be loaded or processed.
	 */
	private function getRoutesFromFiles(): array {
		if ( $this->routesFromFiles !== null ) {
			return $this->routesFromFiles;
		}

		$this->routesFromFiles = [];
		$this->routeFileTimestamps = [];
		foreach ( $this->routeFiles as $fileName ) {
			$this->routeFileTimestamps[$fileName] = filemtime( $fileName );

			$routes = $this->loadRoutes( $fileName );

			$this->routesFromFiles = array_merge( $this->routesFromFiles, $routes );
		}

		return $this->routesFromFiles;
	}

	/**
	 * Loads route definitions from the given file
	 *
	 * @param string $fileName
	 *
	 * @return array<int,array> A list of route definitions. See this class's
	 *         documentation for a description of the format of route definitions.
	 * @throws ModuleConfigurationException
	 */
	private function loadRoutes( string $fileName ) {
		$spec = $this->loadJsonFile( $fileName );

		if ( isset( $spec['routes'] ) ) {
			if ( !isset( $spec['module'] ) ) {
				throw new ModuleConfigurationException(
					"Missing module name in $fileName"
				);
			}

			if ( $spec['module'] !== $this->pathPrefix ) {
				// The Router gave us a route file that doesn't match the module name.
				// This is a programming error, the Router should get this right.
				throw new LogicException(
					"Module name mismatch in $fileName: " .
					"expected {$this->pathPrefix} but got {$spec['module']}."
				);
			}

			// intermediate format with meta-data
			$routes = $spec['routes'];
		} else {
			// old, flat format
			$routes = $spec;
		}

		return $routes;
	}

	/**
	 * Get an array of last modification times of the defined route files.
	 *
	 * @return int[] Last modification times
	 */
	private function getRouteFileTimestamps(): array {
		if ( $this->routeFileTimestamps === null ) {
			$this->routeFileTimestamps = [];
			foreach ( $this->routeFiles as $fileName ) {
				$this->routeFileTimestamps[$fileName] = filemtime( $fileName );
			}
		}
		return $this->routeFileTimestamps;
	}

	/**
	 * @internal for testing and for generating OpenAPI specs
	 *
	 * @return array[]
	 */
	public function getDefinedPaths(): array {
		$paths = [];
		foreach ( $this->getAllRoutes() as $spec ) {
			$key = $spec['path'];

			$methods = isset( $spec['method'] ) ? (array)$spec['method'] : [ 'GET' ];

			$paths[$key] = array_merge( $paths[$key] ?? [], $methods );
		}

		return $paths;
	}

	/**
	 * @return Iterator<array>
	 */
	private function getAllRoutes() {
		$iterator = new AppendIterator;
		$iterator->append( new ArrayIterator( $this->getRoutesFromFiles() ) );
		$iterator->append( new ArrayIterator( $this->extraRoutes ) );
		return $iterator;
	}

	/**
	 * Get an array of PathMatcher objects indexed by HTTP method
	 *
	 * @return PathMatcher[]
	 */
	private function getMatchers() {
		if ( $this->matchers === null ) {
			$routeDefs = $this->getAllRoutes();

			$matchers = [];

			foreach ( $routeDefs as $spec ) {
				$methods = $spec['method'] ?? [ 'GET' ];
				if ( !is_array( $methods ) ) {
					$methods = [ $methods ];
				}
				foreach ( $methods as $method ) {
					if ( !isset( $matchers[$method] ) ) {
						$matchers[$method] = new PathMatcher;
					}
					$matchers[$method]->add( $spec['path'], $spec );
				}
			}
			$this->matchers = $matchers;
		}

		return $this->matchers;
	}

	/**
	 * @inheritDoc
	 */
	public function findHandlerMatch(
		string $path,
		string $requestMethod
	): array {
		$matchers = $this->getMatchers();
		$matcher = $matchers[$requestMethod] ?? null;
		$match = $matcher ? $matcher->match( $path ) : null;

		if ( !$match ) {
			// Return allowed methods, to support CORS and 405 responses.
			return [
				'found' => false,
				'methods' => $this->getAllowedMethods( $path ),
			];
		} else {
			$spec = $match['userData'];

			if ( !isset( $spec['class'] ) && !isset( $spec['factory'] ) ) {
				// Inject well known handler class for shorthand definition
				if ( isset( $spec['redirect'] ) ) {
					$spec['class'] = RedirectHandler::class;
				} else {
					throw new RouteDefinitionException(
						'Route handler definition must specify "class" or ' .
						'"factory" or "redirect"'
					);
				}
			}

			return [
				'found' => true,
				'spec' => $spec,
				'params' => $match['params'] ?? [],
				'config' => $spec,
				'path' => $spec['path'],
			];
		}
	}

	/**
	 * Get the allowed methods for a path.
	 * Useful to check for 405 wrong method.
	 *
	 * @param string $relPath A concrete request path.
	 * @return string[]
	 */
	public function getAllowedMethods( string $relPath ): array {
		$allowed = [];
		foreach ( $this->getMatchers() as $allowedMethod => $allowedMatcher ) {
			if ( $allowedMatcher->match( $relPath ) ) {
				$allowed[] = $allowedMethod;
			}
		}

		return array_unique(
			in_array( 'GET', $allowed ) ? array_merge( [ 'HEAD' ], $allowed ) : $allowed
		);
	}

}
