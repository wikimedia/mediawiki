<?php

namespace MediaWiki\Rest;

use AppendIterator;
use BagOStuff;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory;

/**
 * The REST router is responsible for gathering handler configuration, matching
 * an input path and HTTP method against the defined routes, and constructing
 * and executing the relevant handler for a request.
 */
class Router {
	/** @var string[] */
	private $routeFiles;

	/** @var array */
	private $extraRoutes;

	/** @var array|null */
	private $routesFromFiles;

	/** @var int[]|null */
	private $routeFileTimestamps;

	/** @var string */
	private $baseUrl;

	/** @var string */
	private $rootPath;

	/** @var \BagOStuff */
	private $cacheBag;

	/** @var PathMatcher[]|null Path matchers by method */
	private $matchers;

	/** @var string|null */
	private $configHash;

	/** @var ResponseFactory */
	private $responseFactory;

	/** @var BasicAuthorizerInterface */
	private $basicAuth;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var Validator */
	private $restValidator;

	/** @var HookContainer */
	private $hookContainer;

	/**
	 * @internal
	 * @param string[] $routeFiles List of names of JSON files containing routes
	 * @param array $extraRoutes Extension route array
	 * @param string $baseUrl The base URL
	 * @param string $rootPath The base path for routes, relative to the base URL
	 * @param BagOStuff $cacheBag A cache in which to store the matcher trees
	 * @param ResponseFactory $responseFactory
	 * @param BasicAuthorizerInterface $basicAuth
	 * @param ObjectFactory $objectFactory
	 * @param Validator $restValidator
	 * @param HookContainer|null $hookContainer
	 */
	public function __construct( $routeFiles, $extraRoutes, $baseUrl, $rootPath,
		BagOStuff $cacheBag, ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth, ObjectFactory $objectFactory,
		Validator $restValidator, HookContainer $hookContainer = null
	) {
		$this->routeFiles = $routeFiles;
		$this->extraRoutes = $extraRoutes;
		$this->baseUrl = $baseUrl;
		$this->rootPath = $rootPath;
		$this->cacheBag = $cacheBag;
		$this->responseFactory = $responseFactory;
		$this->basicAuth = $basicAuth;
		$this->objectFactory = $objectFactory;
		$this->restValidator = $restValidator;

		if ( !$hookContainer ) {
			// b/c for OAuth extension
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		$this->hookContainer = $hookContainer;
	}

	/**
	 * Get the cache data, or false if it is missing or invalid
	 *
	 * @return bool|array
	 */
	private function fetchCacheData() {
		$cacheData = $this->cacheBag->get( $this->getCacheKey() );
		if ( $cacheData && $cacheData['CONFIG-HASH'] === $this->getConfigHash() ) {
			unset( $cacheData['CONFIG-HASH'] );
			return $cacheData;
		} else {
			return false;
		}
	}

	/**
	 * @return string The cache key
	 */
	private function getCacheKey() {
		return $this->cacheBag->makeKey( __CLASS__, '1' );
	}

	/**
	 * Get a config version hash for cache invalidation
	 *
	 * @return string
	 */
	private function getConfigHash() {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				$this->extraRoutes,
				$this->getRouteFileTimestamps()
			] ) );
		}
		return $this->configHash;
	}

	/**
	 * Load the defined JSON files and return the merged routes
	 *
	 * @return array
	 */
	private function getRoutesFromFiles() {
		if ( $this->routesFromFiles === null ) {
			$this->routeFileTimestamps = [];
			foreach ( $this->routeFiles as $fileName ) {
				$this->routeFileTimestamps[$fileName] = filemtime( $fileName );
				$routes = json_decode( file_get_contents( $fileName ), true );
				if ( $this->routesFromFiles === null ) {
					$this->routesFromFiles = $routes;
				} else {
					$this->routesFromFiles = array_merge( $this->routesFromFiles, $routes );
				}
			}
		}
		return $this->routesFromFiles;
	}

	/**
	 * Get an array of last modification times of the defined route files.
	 *
	 * @return int[] Last modification times
	 */
	private function getRouteFileTimestamps() {
		if ( $this->routeFileTimestamps === null ) {
			$this->routeFileTimestamps = [];
			foreach ( $this->routeFiles as $fileName ) {
				$this->routeFileTimestamps[$fileName] = filemtime( $fileName );
			}
		}
		return $this->routeFileTimestamps;
	}

	/**
	 * Get an iterator for all defined routes, including loading the routes from
	 * the JSON files.
	 *
	 * @return AppendIterator
	 */
	private function getAllRoutes() {
		$iterator = new AppendIterator;
		$iterator->append( new \ArrayIterator( $this->getRoutesFromFiles() ) );
		$iterator->append( new \ArrayIterator( $this->extraRoutes ) );
		return $iterator;
	}

	/**
	 * Get an array of PathMatcher objects indexed by HTTP method
	 *
	 * @return PathMatcher[]
	 */
	private function getMatchers() {
		if ( $this->matchers === null ) {
			$cacheData = $this->fetchCacheData();
			$matchers = [];
			if ( $cacheData ) {
				foreach ( $cacheData as $method => $data ) {
					$matchers[$method] = PathMatcher::newFromCache( $data );
				}
			} else {
				foreach ( $this->getAllRoutes() as $spec ) {
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

				$cacheData = [ 'CONFIG-HASH' => $this->getConfigHash() ];
				foreach ( $matchers as $method => $matcher ) {
					$cacheData[$method] = $matcher->getCacheData();
				}
				$this->cacheBag->set( $this->getCacheKey(), $cacheData );
			}
			$this->matchers = $matchers;
		}
		return $this->matchers;
	}

	/**
	 * Remove the path prefix $this->rootPath. Return the part of the path with the
	 * prefix removed, or false if the prefix did not match.
	 *
	 * @param string $path
	 * @return false|string
	 */
	private function getRelativePath( $path ) {
		if ( strlen( $this->rootPath ) > strlen( $path ) ||
			substr_compare( $path, $this->rootPath, 0, strlen( $this->rootPath ) ) !== 0
		) {
			return false;
		}
		return substr( $path, strlen( $this->rootPath ) );
	}

	/**
	 * Returns a full URL for the given route.
	 * Intended for use in redirects.
	 *
	 * @param string $route
	 * @param array $pathParams
	 * @param array $queryParams
	 *
	 * @return false|string
	 */
	public function getRouteUrl( $route, $pathParams = [], $queryParams = [] ) {
		foreach ( $pathParams as $param => $value ) {
			// NOTE: we use rawurlencode here, since execute() uses rawurldecode().
			// Spaces in path params must be encoded to %20 (not +).
			$route = str_replace( '{' . $param . '}', rawurlencode( $value ), $route );
		}

		$url = $this->baseUrl . $this->rootPath . $route;
		return wfAppendQuery( $url, $queryParams );
	}

	/**
	 * Find the handler for a request and execute it
	 *
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 */
	public function execute( RequestInterface $request ) {
		$path = $request->getUri()->getPath();
		$relPath = $this->getRelativePath( $path );
		if ( $relPath === false ) {
			return $this->responseFactory->createLocalizedHttpError( 404,
				( new MessageValue( 'rest-prefix-mismatch' ) )
					->plaintextParams( $path, $this->rootPath )
			);
		}

		$requestMethod = $request->getMethod();
		$matchers = $this->getMatchers();
		$matcher = $matchers[$requestMethod] ?? null;
		$match = $matcher ? $matcher->match( $relPath ) : null;

		// For a HEAD request, execute the GET handler instead if one exists.
		// The webserver will discard the body.
		if ( !$match && $requestMethod === 'HEAD' && isset( $matchers['GET'] ) ) {
			$match = $matchers['GET']->match( $relPath );
		}

		if ( !$match ) {
			// Check for 405 wrong method
			$allowed = [];
			foreach ( $matchers as $allowedMethod => $allowedMatcher ) {
				if ( $allowedMethod === $requestMethod ) {
					continue;
				}
				if ( $allowedMatcher->match( $relPath ) ) {
					$allowed[] = $allowedMethod;
				}
			}
			if ( $allowed ) {
				$response = $this->responseFactory->createLocalizedHttpError( 405,
					( new MessageValue( 'rest-wrong-method' ) )
						->textParams( $requestMethod )
						->commaListParams( $allowed )
						->numParams( count( $allowed ) )
				);
				$response->setHeader( 'Allow', $allowed );
				return $response;
			} else {
				// Did not match with any other method, must be 404
				return $this->responseFactory->createLocalizedHttpError( 404,
					( new MessageValue( 'rest-no-match' ) )
						->plaintextParams( $relPath )
				);
			}
		}

		// Use rawurldecode so a "+" in path params is not interpreted as a space character.
		$request->setPathParams( array_map( 'rawurldecode', $match['params'] ) );
		$handler = $this->createHandler( $request, $match['userData'] );

		try {
			return $this->executeHandler( $handler );
		} catch ( HttpException $e ) {
			return $this->responseFactory->createFromException( $e );
		}
	}

	/**
	 * Create a handler from its spec
	 * @param RequestInterface $request
	 * @param array $spec
	 * @return Handler
	 */
	private function createHandler( RequestInterface $request, array $spec ): Handler {
		$objectFactorySpec = array_intersect_key( $spec,
			[ 'factory' => true, 'class' => true, 'args' => true, 'services' => true ] );
		/** @var $handler Handler (annotation for PHPStorm) */
		$handler = $this->objectFactory->createObject( $objectFactorySpec );
		$handler->init( $this, $request, $spec, $this->responseFactory, $this->hookContainer );

		return $handler;
	}

	/**
	 * Execute a fully-constructed handler
	 *
	 * @param Handler $handler
	 * @return ResponseInterface
	 */
	private function executeHandler( $handler ): ResponseInterface {
		// Check for basic authorization, to avoid leaking data from private wikis
		$authResult = $this->basicAuth->authorize( $handler->getRequest(), $handler );
		if ( $authResult ) {
			return $this->responseFactory->createHttpError( 403, [ 'error' => $authResult ] );
		}

		// Validate the parameters
		$handler->validate( $this->restValidator );

		// Check conditional request headers
		$earlyResponse = $handler->checkPreconditions();
		if ( $earlyResponse ) {
			return $earlyResponse;
		}

		// Run the main part of the handler
		$response = $handler->execute();
		if ( !( $response instanceof ResponseInterface ) ) {
			$response = $this->responseFactory->createFromReturnValue( $response );
		}

		// Set Last-Modified and ETag headers in the response if available
		$handler->applyConditionalResponseHeaders( $response );

		return $response;
	}
}
