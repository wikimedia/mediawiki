<?php

namespace MediaWiki\Settings\Cache;

use BagOStuff;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\SettingsIncludeLocator;
use MediaWiki\Settings\Source\SettingsSource;
use Wikimedia\WaitConditionLoop;

/**
 * Provides a caching layer for a {@link CacheableSource}.
 *
 * @since 1.38
 * @todo mark as stable before the 1.38 release
 */
class CachedSource implements SettingsSource, SettingsIncludeLocator {
	/**
	 * Cached source generation timeout (in seconds).
	 */
	private const TIMEOUT = 2;

	/** @var BagOStuff */
	private $cache;

	/** @var CacheableSource */
	private $source;

	/**
	 * Constructs a new CachedSource using an instantiated cache and
	 * {@link CacheableSource}.
	 *
	 * @param BagOStuff $cache
	 * @param CacheableSource $source
	 */
	public function __construct(
		BagOStuff $cache,
		CacheableSource $source
	) {
		$this->cache = $cache;
		$this->source = $source;
	}

	/**
	 * Queries cache for source contents and performs loading/caching of the
	 * source contents on miss.
	 *
	 * If the load fails but the source implements {@link
	 * CacheableSource::allowsStaleLoad()} as <code>true</code>, stale results
	 * may be returned if still present in the cache store.
	 *
	 * @return array
	 */
	public function load(): array {
		$key = $this->cache->makeGlobalKey(
			__CLASS__,
			$this->source->getHashKey()
		);

		$result = null;
		$loop = new WaitConditionLoop(
			function () use ( $key, &$result ) {
				$item = $this->cache->get( $key );

				if ( $this->isValidHit( $item ) ) {
					if ( $this->isExpired( $item ) ) {
						// The cached item is stale but use it as a default in
						// case of failure if the source allows that
						if ( $this->source->allowsStaleLoad() ) {
							$result = $item['value'];
						}
					} else {
						$result = $item['value'];
						return WaitConditionLoop::CONDITION_REACHED;
					}
				}

				if ( $this->cache->lock( $key, 0, self::TIMEOUT ) ) {
					try {
						$result = $this->loadAndCache( $key );
					} catch ( SettingsBuilderException $e ) {
						if ( $result === null ) {
							// We have a failure and no stale result to fall
							// back on, so throw
							throw $e;
						}
					} finally {
						$this->cache->unlock( $key );
					}
				}

				return $result === null
					? WaitConditionLoop::CONDITION_CONTINUE
					: WaitConditionLoop::CONDITION_REACHED;
			},
			self::TIMEOUT
		);

		if ( $loop->invoke() !== WaitConditionLoop::CONDITION_REACHED ) {
			throw new SettingsBuilderException(
				'Exceeded {timeout}s timeout attempting to load and cache source {source}',
				[
					'timeout' => self::TIMEOUT,
					'source' => $this->source,
				]

			);
		}

		return $result;
	}

	/**
	 * Returns the string representation of the encapsulated source.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->source->__toString();
	}

	/**
	 * Whether the given cache item is considered a cache hit, in other words:
	 *  - it is not a falsey value
	 *  - it has the correct type and structure for this cache implementation
	 *
	 * @param mixed $item Cache item.
	 *
	 * @return bool
	 */
	private function isValidHit( $item ): bool {
		return $item &&
			is_array( $item ) &&
			isset( $item['expiry'] ) &&
			isset( $item['generation'] ) &&
			isset( $item['value'] );
	}

	/**
	 * Whether the given cache item is considered expired, in other words:
	 *	- its expiry timestamp has passed
	 *	- it is deemed to expire early so as to mitigate cache stampedes
	 *
	 * @param array $item Cache item.
	 *
	 * @return bool
	 */
	private function isExpired( $item ): bool {
		return $item['expiry'] < microtime( true ) ||
			$this->expiresEarly( $item, $this->source->getExpiryWeight() );
	}

	/**
	 * Decide whether the cached source should be expired early according to a
	 * probabilistic calculation that becomes more likely as the normal expiry
	 * approaches.
	 *
	 * In other words, we're going to pretend we're a bit further into the
	 * future than we are so that we might expire and regenerate the cached
	 * settings before other threads attempt to the do the same. The number of
	 * threads that will pretend to be far into the future (and thus will
	 * concurrently reload/cache the settings) will most probably be so
	 * exponentially fewer than the number of threads pretending to be near
	 * into the future that it will approach optimal stampede protection
	 * without the use of an exclusive lock.
	 *
	 * @param array $item Cached source with expiry metadata.
	 * @param float $weight Coefficient used to increase/decrease the
	 *  likelihood of early expiration.
	 *
	 * @link https://en.wikipedia.org/wiki/Cache_stampede#Probabilistic_early_expiration
	 *
	 * @return bool
	 */
	private function expiresEarly( array $item, float $weight ): bool {
		if ( $weight == 0 || !isset( $item['expiry'] ) || !isset( $item['generation'] ) ) {
			return false;
		}

		// Calculate a negative expiry offset using generation time, expiry
		// weight, and a random number within the exponentially distributed
		// range of log n where n: (0, 1] (which is always negative)
		$expiryOffset =
			$item['generation'] *
			$weight *
			log( random_int( 1, PHP_INT_MAX ) / PHP_INT_MAX );

		return ( $item['expiry'] + $expiryOffset ) <= microtime( true );
	}

	/**
	 * Loads the source and caches the result.
	 *
	 * @param string $key
	 *
	 * @return array
	 */
	private function loadAndCache( string $key ): array {
		$ttl =
			$this->source->allowsStaleLoad()
			? BagOStuff::TTL_INDEFINITE
			: $this->source->getExpiryTtl();

		$item = $this->loadWithMetadata();
		$this->cache->set( $key, $item, $ttl );
		return $item['value'];
	}

	/**
	 * Wraps cached source with the metadata needed to perform probabilistic
	 * early expiration to help mitigate cache stampedes.
	 *
	 * @return array
	 */
	private function loadWithMetadata(): array {
		$start = microtime( true );
		$value = $this->source->load();
		$finish = microtime( true );

		return [
			'value' => $value,
			'expiry' => $start + $this->source->getExpiryTtl(),
			'generation' => $finish - $start,
		];
	}

	public function locateInclude( string $location ): string {
		if ( $this->source instanceof SettingsIncludeLocator ) {
			return $this->source->locateInclude( $location );
		} else {
			// Just return the location as-is
			return $location;
		}
	}
}
