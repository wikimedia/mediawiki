<?php

namespace MediaWiki\Rest\Module;

use AppendIterator;
use ArrayIterator;
use Iterator;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Handler\RedirectHandler;
use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\RouteDefinitionException;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A Module that is based on flat route definitions in the form originally
 * introduced in MW 1.35. This module acts as a "catch all" since it doesn't
 * use a module prefix. So it handles all routes that do not explicitly belong
 * to a module.
 *
 * This module responds to requests by matching the requested path against a
 * list of known routes to identify the appropriate handler.
 * The routes are loaded from the route definition files or in extension.json
 * files using the RestRoutes key.
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
 *   that specifies the target "path", and optionally the redirect "code".
 *
 * More shorthands may be added in the future.
 *
 * Route definitions can contain additional fields to configure the handler.
 * The handler can access the route definition by calling getConfig().
 *
 * @internal
 * @since 1.43
 */
class ExtraRoutesModule extends MatcherBasedModule {

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

	private ?string $configHash = null;

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
		ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth,
		ObjectFactory $objectFactory,
		Validator $restValidator,
		ErrorReporter $errorReporter,
		HookContainer $hookContainer
	) {
		parent::__construct(
			$router,
			'',
			$responseFactory,
			$basicAuth,
			$objectFactory,
			$restValidator,
			$errorReporter,
			$hookContainer
		);
		$this->routeFiles = $routeFiles;
		$this->extraRoutes = $extraRoutes;
	}

	/**
	 * Get a config version hash for cache invalidation
	 */
	protected function getConfigHash(): string {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				'class' => __CLASS__,
				'version' => 1,
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

			$routes = $this->loadJsonFile( $fileName );

			$this->routesFromFiles = array_merge( $this->routesFromFiles, $routes );
		}

		return $this->routesFromFiles;
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

	protected function initRoutes(): void {
		$routeDefs = $this->getAllRoutes();

		foreach ( $routeDefs as $route ) {
			if ( !isset( $route['path'] ) ) {
				throw new RouteDefinitionException( 'Missing path' );
			}

			$path = $route['path'];
			$method = $route['method'] ?? 'GET';
			$info = $this->makeRouteInfo( $route );

			$this->addRoute( $method, $path, $info );
		}
	}

	/**
	 * Generate a route info array to be stored in the matcher tree,
	 * in the form expected by MatcherBasedModule::addRoute()
	 * and ultimately Module::getHandlerForPath().
	 */
	private function makeRouteInfo( array $route ): array {
		static $objectSpecKeys = [
			'class',
			'factory',
			'services',
			'optional_services',
			'args',
		];

		if ( isset( $route['redirect'] ) ) {
			// Redirect shorthand
			$info = [
				'spec' => [ 'class' => RedirectHandler::class ],
				'config' => $route,
			];
		} else {
			// Object spec at the top level
			$info = [
				'spec' => array_intersect_key( $route, array_flip( $objectSpecKeys ) ),
				'config' => array_diff_key( $route, array_flip( $objectSpecKeys ) ),
			];
		}

		if ( isset( $route['openApiSpec'] ) ) {
			$info['openApiSpec'] = $route['openApiSpec'];
		}

		$info['path'] = $route['path'];
		return $info;
	}

	public function getOpenApiInfo() {
		// Note that mwapi-1.0 is based on OAS 3.0, so it doesn't support the
		// "summary" property introduced in 3.1.
		$localizer = new JsonLocalizer( $this->responseFactory );
		return [
			'title' => $localizer->getFormattedMessage( 'rest-module-extra-routes-title' ),
			'description' => $localizer->getFormattedMessage( 'rest-module-extra-routes-desc' ),
			'version' => 'undefined',
		];
	}

}
