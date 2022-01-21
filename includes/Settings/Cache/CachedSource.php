<?php

namespace MediaWiki\Settings\Cache;

use BagOStuff;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\SettingsIncludeLocator;
use MediaWiki\Settings\Source\SettingsSource;

/**
 * Provides a caching layer for a {@link CacheableSource}.
 *
 * @since 1.38
 * @todo mark as stable before the 1.38 release
 */
class CachedSource implements SettingsSource, SettingsIncludeLocator {
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
		$item = $this->cache->get( $key );
		$miss = $this->isMiss( $item );

		if ( $miss || $this->isExpired( $item ) ) {
			$allowsStale = $this->source->allowsStaleLoad();

			try {
				$item = $this->loadWithMetadata();
				$this->cache->set(
					$key,
					$item,
					$allowsStale ? BagOStuff::TTL_INDEFINITE : $this->source->getExpiryTtl()
				);
			} catch ( SettingsBuilderException $e ) {
				if ( $miss || !$allowsStale ) {
					throw $e;
				}
				// Return the stale results since the source allows it and
				// this was only a soft miss
			}
		}

		return $item['value'];
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
	 * Whether the given cache item is considered a real miss, in other words:
	 *  - it is a falsey value
	 *  - it has the wrong type or structure for this cache implementation
	 *
	 * @param mixed $item Cache item.
	 *
	 * @return bool
	 */
	private function isMiss( $item ): bool {
		return !$item ||
			!is_array( $item ) ||
			!isset( $item['expiry'] ) ||
			!isset( $item['generation'] ) ||
			!isset( $item['value'] );
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
