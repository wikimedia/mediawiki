<?php

namespace MediaWiki\Settings\Cache;

use BagOStuff;
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
	 * @return array
	 */
	public function load(): array {
		$key = $this->cache->makeGlobalKey(
			__CLASS__,
			$this->source->getHashKey()
		);
		$item = $this->cache->get( $key );

		$miss =
			$item === false ||
			$this->expiresEarly( $item, $this->source->getExpiryWeight() );

		if ( $miss ) {
			$item = $this->loadWithMetadata();
			$this->cache->set( $key, $item, $this->source->getExpiryTtl() );
		}

		// This shouldn't be possible but let's make phan happy
		if ( !is_array( $item ) || !array_key_exists( 'value', $item ) ) {
			return [];
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
	 * Decide whether the cached source should be expired early according to a
	 * probabilistic calculation that becomes more likely as the normal expiry
	 * approaches.
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
		if ( !isset( $item['expiry'] ) || !isset( $item['generation'] ) ) {
			return false;
		}

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
