<?php

namespace MediaWiki\Rest\Module;

use InvalidArgumentException;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;

/**
 * MatcherBasedModules respond to requests by matching the requested path
 * against a list of known routes to identify the appropriate handler.
 *
 * @see Matcher
 *
 * @since 1.43
 */
abstract class MatcherBasedModule extends Module {

	/** @var PathMatcher[] Path matchers by method */
	private ?array $matchers = [];

	private bool $matchersInitialized = false;

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

		$this->matchersInitialized = true;
		return true;
	}

	/**
	 * Get a config version hash for cache invalidation
	 *
	 * @return string
	 */
	abstract protected function getConfigHash(): string;

	/**
	 * Get an array of PathMatcher objects indexed by HTTP method
	 *
	 * @return PathMatcher[]
	 */
	protected function getMatchers() {
		if ( !$this->matchersInitialized ) {
			$this->initRoutes();
			$this->matchersInitialized = true;
		}

		return $this->matchers;
	}

	/**
	 * Initialize matchers by calling addRoute() for each known route.
	 */
	abstract protected function initRoutes(): void;

	/**
	 * @param string|string[] $method The method(s) the route should be registered for
	 * @param string $path The path pattern for the route
	 * @param array $info Information to be associated with the route. Supported keys:
	 *        - "spec": an object spec for use with ObjectFactory for constructing a Handler object.
	 *        - "config": an array of configuration valies to be passed to Handler::initContext.
	 */
	protected function addRoute( $method, string $path, array $info ) {
		$methods = (array)$method;

		// Make sure the matched path is known.
		if ( !isset( $info['spec'] ) ) {
			throw new InvalidArgumentException( 'Missing key in $info: "spec"' );
		}

		$info['path'] = $path;

		foreach ( $methods as $method ) {
			$method = strtoupper( $method );

			if ( !isset( $this->matchers[$method] ) ) {
				$this->matchers[$method] = new PathMatcher;
			}

			$this->matchers[$method]->add( $path, $info );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function findHandlerMatch(
		string $path,
		string $requestMethod
	): array {
		$requestMethod = strtoupper( $requestMethod );

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
			$info = $match['userData'];
			$info['found'] = true;
			$info['method'] = $requestMethod;
			$info['params'] = $match['params'] ?? [];

			return $info;
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
