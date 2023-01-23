<?php

namespace MediaWiki\Rest;

use AppendIterator;
use BagOStuff;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use NullStatsdDataFactory;
use Throwable;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory\ObjectFactory;

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
	private $privateBaseUrl;

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

	/** @var Authority */
	private $authority;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var Validator */
	private $restValidator;

	/** @var CorsUtils|null */
	private $cors;

	/** @var ErrorReporter */
	private $errorReporter;

	/** @var HookContainer */
	private $hookContainer;

	/** @var Session */
	private $session;

	/** @var StatsdDataFactoryInterface */
	private $stats;

	/**
	 * @internal
	 * @var array
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CanonicalServer,
		MainConfigNames::InternalServer,
		MainConfigNames::RestPath,
	];

	/**
	 * @param string[] $routeFiles List of names of JSON files containing routes
	 * @param array $extraRoutes Extension route array
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
		$routeFiles,
		$extraRoutes,
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
		$this->cacheBag = $cacheBag;
		$this->responseFactory = $responseFactory;
		$this->basicAuth = $basicAuth;
		$this->authority = $authority;
		$this->objectFactory = $objectFactory;
		$this->restValidator = $restValidator;
		$this->errorReporter = $errorReporter;
		$this->hookContainer = $hookContainer;
		$this->session = $session;

		$this->stats = new NullStatsdDataFactory();
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
		if ( !str_starts_with( $path, $this->rootPath ) ) {
			return false;
		}
		return substr( $path, strlen( $this->rootPath ) );
	}

	/**
	 * Returns a full URL for the given route.
	 * Intended for use in redirects and when including links to endpoints in output.
	 *
	 * @param string $route
	 * @param array $pathParams
	 * @param array $queryParams
	 *
	 * @return string
	 * @see getPrivateRouteUrl
	 *
	 */
	public function getRouteUrl(
		string $route,
		array $pathParams = [],
		array $queryParams = []
	): string {
		$route = $this->substPathParams( $route, $pathParams );
		$url = $this->baseUrl . $this->rootPath . $route;
		return wfAppendQuery( $url, $queryParams );
	}

	/**
	 * Returns a full private URL for the given route.
	 * Private URLs are for use within the local subnet, they may use host names or ports
	 * or paths that are not publicly accessible.
	 * Intended for use in redirects and when including links to endpoints in output.
	 *
	 * @note Only private endpoints should use this method for redirects or links to
	 *       include on the response! Public endpoints should not expose the URLs
	 *       of private endpoints to the public!
	 *
	 * @since 1.39
	 * @see getRouteUrl
	 *
	 * @param string $route
	 * @param array $pathParams
	 * @param array $queryParams
	 *
	 * @return string
	 */
	public function getPrivateRouteUrl(
		string $route,
		array $pathParams = [],
		array $queryParams = []
	): string {
		$route = $this->substPathParams( $route, $pathParams );
		$url = $this->privateBaseUrl . $this->rootPath . $route;
		return wfAppendQuery( $url, $queryParams );
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
			$allowed = $this->getAllowedMethods( $relPath );

			// Check for CORS Preflight. This response will *not* allow the request unless
			// an Access-Control-Allow-Origin header is added to this response.
			if ( $this->cors && $requestMethod === 'OPTIONS' ) {
				return $this->cors->createPreflightResponse( $allowed );
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

		$handler = null;
		try {
			// Use rawurldecode so a "+" in path params is not interpreted as a space character.
			$request->setPathParams( array_map( 'rawurldecode', $match['params'] ) );
			$handler = $this->createHandler( $request, $match['userData'] );

			// Replace any characters that may have a special meaning in the metrics DB.
			$pathForMetrics = $handler->getPath();
			$pathForMetrics = strtr( $pathForMetrics, '{}:', '-' );
			$pathForMetrics = strtr( $pathForMetrics, '/.', '_' );

			$statTime = microtime( true );

			$response = $this->executeHandler( $handler );
		} catch ( HttpException $e ) {
			$response = $this->responseFactory->createFromException( $e );
		} catch ( Throwable $e ) {
			$this->errorReporter->reportError( $e, $handler, $request );
			$response = $this->responseFactory->createFromException( $e );
		}

		// gather metrics
		if ( $response->getStatusCode() >= 400 ) {
			// count how often we return which error code
			$statusCode = $response->getStatusCode();
			$this->stats->increment( "rest_api_errors.$pathForMetrics.$requestMethod.$statusCode" );
		} else {
			// measure how long it takes to generate a response
			$microtime = ( microtime( true ) - $statTime ) * 1000;
			$this->stats->timing( "rest_api_latency.$pathForMetrics.$requestMethod", $microtime );
		}

		return $response;
	}

	/**
	 * Get the allow methods for a path.
	 *
	 * @param string $relPath
	 * @return array
	 */
	private function getAllowedMethods( string $relPath ): array {
		// Check for 405 wrong method
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

	/**
	 * Create a handler from its spec
	 * @param RequestInterface $request
	 * @param array $spec
	 * @return Handler
	 */
	private function createHandler( RequestInterface $request, array $spec ): Handler {
		$objectFactorySpec = array_intersect_key(
			$spec,
			[
				'factory' => true,
				'class' => true,
				'args' => true,
				'services' => true,
				'optional_services' => true
			]
		);
		/** @var $handler Handler (annotation for PHPStorm) */
		$handler = $this->objectFactory->createObject( $objectFactorySpec );
		$handler->init( $this, $request, $spec, $this->authority, $this->responseFactory,
			$this->hookContainer, $this->session
		);

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

		// Check session (and session provider)
		$handler->checkSession();

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

		$handler->applyCacheControl( $response );

		return $response;
	}

	/**
	 * @param CorsUtils $cors
	 * @return self
	 */
	public function setCors( CorsUtils $cors ): self {
		$this->cors = $cors;

		return $this;
	}

	/**
	 * @param StatsdDataFactoryInterface $stats
	 *
	 * @return self
	 */
	public function setStats( StatsdDataFactoryInterface $stats ): self {
		$this->stats = $stats;

		return $this;
	}

}
