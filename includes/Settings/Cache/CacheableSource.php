<?php

namespace MediaWiki\Settings\Cache;

use MediaWiki\Settings\Source\SettingsSource;

/**
 * A {@link SettingsSource} that can be cached. It must return a unique
 * (enough) and deterministic hash key for cache indexing.
 *
 * @since 1.38
 * @stable to implement
 */
interface CacheableSource extends SettingsSource {
	/**
	 * Allow the caching layer to attempt to return stale results in the event
	 * that loading from the original source fails.
	 *
	 * Note that allowing stale results will result in cache items being
	 * stored indefinitely regardless of the {@link getExpiryTtl()} value, and
	 * since there is currently no pruning of cache items, it is advised that
	 * sources allowing stale results also implement an immutable
	 * `getHashKey()` based only on constructor arguments.
	 *
	 * @return bool
	 */
	public function allowsStaleLoad(): bool;

	/**
	 * Returns the cache TTL (in seconds) for this source.
	 *
	 * @return int
	 */
	public function getExpiryTtl(): int;

	/**
	 * Coefficient used in determining early expiration of cached settings to
	 * avoid stampedes.
	 *
	 * Increasing this value will cause the random early election to happen by
	 * a larger margin of lead time before normal expiry, relative to the
	 * cache value's generation duration. Conversely, returning a lesser value
	 * will narrow the margin of lead time, making the cache hold items for
	 * slightly longer but with more likelihood that concurrent regenerations
	 * and set overwrites will occur. Returning <code>0</code> will
	 * effectively disable early expiration, and by extension disable stampede
	 * mitigation altogether.
	 *
	 * A greater value may be suitable if a source has a highly variable
	 * generation duration, but most implementations should simply return
	 * <code>1.0</code>.
	 *
	 * @link https://en.wikipedia.org/wiki/Cache_stampede#Probabilistic_early_expiration
	 * @link https://cseweb.ucsd.edu/~avattani/papers/cache_stampede.pdf
	 *
	 * Optimal Probabilistic Cache Stampede Prevention
	 * Vattani, A.; Chierichetti, F.; Lowenstein, K. (2015), "Optimal
	 * Probabilistic Cache Stampede Prevention" (PDF), Proceedings of the VLDB
	 * Endowment, VLDB, 8 (8): 886–897, doi:10.14778/2757807.2757813, ISSN
	 * 2150-8097 https://cseweb.ucsd.edu/~avattani/papers/cache_stampede.pdf
	 *
	 * @return float
	 */
	public function getExpiryWeight(): float;

	/**
	 * Returns a deterministically computed key for use in caching settings
	 * from this source.
	 *
	 * @return string
	 */
	public function getHashKey(): string;
}
