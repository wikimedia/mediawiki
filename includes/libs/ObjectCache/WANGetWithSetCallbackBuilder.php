<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\ObjectCache;

use LogicException;

/**
 * Fluent builder for a WANObjectCache::getWithSetCallback() call
 *
 * Obtain one via WANObjectCache::buildGetWithSetCallback(). The cache key is built by the
 * builder itself, so callers do not have to reach for makeKey()/makeGlobalKey(), and the
 * options map is replaced by named methods, so that the meaning of each value is visible at
 * the call site:
 *
 * @code
 *     $stats = $cache->buildGetWithSetCallback()
 *         ->key( 'language-stats' )
 *         ->keepIndefinitely()
 *         ->invalidatedByKey( 'language-stats' )
 *         ->shortProcessCache()
 *         ->getWithSetCallback( static function () {
 *             return self::getAllLanguageStats();
 *         } );
 * @endcode
 *
 * The callback receives the previous value (false if there was none),
 * which replaces the `$oldValue` argument of the plain
 * WANObjectCache::getWithSetCallback() callback:
 *
 * @code
 *     $row = $cache->buildGetWithSetCallback()
 *         ->key( 'revision-count', $pageId )
 *         ->keepForADay()
 *         ->allowStale( 30 )
 *         ->callback( function ( $oldValue ) use ( $pageId ) {
 *             if ( $oldValue && $oldValue['latest'] === $this->getLatest( $pageId ) ) {
 *                 $return $oldValue;
 *             }
 *             return $this->computeRevisionCount( $pageId );
 *         } )
 *         ->fetch();
 * @endcode
 *
 * Method chaining is only required for options that a call actually cares about; anything
 * left unset keeps the default documented by WANObjectCache::getWithSetCallback().
 *
 * @see WANObjectCache::getWithSetCallback()
 * @since 1.47
 * @ingroup Cache
 */
class WANGetWithSetCallbackBuilder {
	/** @var callable|null Callback producing the result */
	private $callback = null;
	private WANObjectCache $cache;
	/** @var string|null Cache key, as built by key() or globalKey() */
	private ?string $key = null;
	/** @var int|null Seconds that a newly generated value may be reused for */
	private ?int $lifetime = null;
	/** @var array Options map for WANObjectCache::getWithSetCallback() */
	private array $options = [];

	/**
	 * @internal Use WANObjectCache::buildGetWithSetCallback()
	 * @param WANObjectCache $cache
	 */
	public function __construct( WANObjectCache $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Set the cache key, scoped to the current wiki
	 *
	 * @param string $keygroup Key group component, which must be constant (not user input)
	 * @param string|int ...$components Additional key components
	 * @return $this
	 */
	public function key( string $keygroup, ...$components ) {
		$this->key = $this->cache->makeKey( $keygroup, ...$components );

		return $this;
	}

	/**
	 * Set the cache key, shared by all wikis of the farm
	 *
	 * @param string $keygroup Key group component, which must be constant (not user input)
	 * @param string|int ...$components Additional key components
	 * @return $this
	 */
	public function globalKey( string $keygroup, ...$components ) {
		$this->key = $this->cache->makeGlobalKey( $keygroup, ...$components );
		return $this;
	}

	/**
	 * Set the cache key from a key that was already built by makeKey() or makeGlobalKey()
	 *
	 * Prefer key() or globalKey(), which build the key from its components. Use this only
	 * when the key is built elsewhere because it is also needed outside of this call, such as
	 * for a matching WANObjectCache::getMulti() or WANObjectCache::delete() call. Passing a
	 * ready-made key to key() would encode it a second time and thus address a different entry.
	 *
	 * @param string $key Key from WANObjectCache::makeKey() or WANObjectCache::makeGlobalKey()
	 * @return $this
	 */
	public function rawKey( string $key ) {
		$this->key = $key;

		return $this;
	}

	/**
	 * Set how long a newly generated value may be reused for
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function lifetime( int $seconds ) {
		$this->lifetime = $seconds;
		return $this;
	}

	/**
	 * Reuse a newly generated value indefinitely, subject to LRU-style evictions
	 *
	 * @return $this
	 */
	public function keepIndefinitely() {
		return $this->lifetime( WANObjectCache::TTL_INDEFINITE );
	}

	/**
	 * Reuse a newly generated value for one minute
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForAMinute() {
		return $this->lifetime( WANObjectCache::TTL_MINUTE );
	}

	/**
	 * Reuse a newly generated value for one hour
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForAnHour() {
		return $this->lifetime( WANObjectCache::TTL_HOUR );
	}

	/**
	 * Reuse a newly generated value for one day
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForADay() {
		return $this->lifetime( WANObjectCache::TTL_DAY );
	}

	/**
	 * Reuse a newly generated value for one week
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForAWeek() {
		return $this->lifetime( WANObjectCache::TTL_WEEK );
	}

	/**
	 * Reuse a newly generated value for one month
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForAMonth() {
		return $this->lifetime( WANObjectCache::TTL_MONTH );
	}

	/**
	 * Reuse a newly generated value for one year
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 * @return $this
	 */
	public function keepForAYear() {
		return $this->lifetime( WANObjectCache::TTL_YEAR );
	}

	/**
	 * Treat the value as stale whenever the given "check" key is touched
	 *
	 * The value is seen as stale when either WANObjectCache::touchCheckKey() or
	 * WANObjectCache::resetCheckKey() is called on the given key.
	 * This is useful when thousands or millions of keys depend on the same entity:
	 * that entity can simply have its "check" key updated whenever it is modified.
	 *
	 * This may be called more than once, in which case any of the keys invalidates the value.
	 *
	 * @param string $keygroup Key group component, which must be constant (not user input)
	 * @param string|int ...$components Additional key components
	 * @return $this
	 */
	public function invalidatedByKey( string $keygroup, ...$components ) {
		$this->options['checkKeys'][] = $this->cache->makeKey( $keygroup, ...$components );

		return $this;
	}

	/**
	 * Treat the value as stale whenever the given global "check" key is touched
	 *
	 * @see WANGetWithSetCallbackBuilder::invalidatedByKey()
	 * @param string $keygroup Key group component, which must be constant (not user input)
	 * @param string|int ...$components Additional key components
	 * @return $this
	 */
	public function invalidatedByGlobalKey( string $keygroup, ...$components ) {
		$this->options['checkKeys'][] = $this->cache->makeGlobalKey( $keygroup, ...$components );

		return $this;
	}

	/**
	 * Treat the value as stale whenever a dynamic dependency has changed since it was generated
	 *
	 * The callback takes the current value and returns the UNIX timestamp of when a dependency
	 * last changed, or null if there is nothing to check. This suits values that are moderately
	 * to highly expensive to regenerate, but whose dependency timestamps are cheap to query,
	 * such as the last modification time of a file.
	 *
	 * @param callable $callback Takes the current value, returns a UNIX timestamp or null
	 * @return $this
	 */
	public function lastModifiedCallback( callable $callback ) {
		$this->options['touchedCallback'] = $callback;

		return $this;
	}

	/**
	 * Allow reuse of a recently expired value while another thread regenerates it
	 *
	 * This enables use of a regeneration lock if the value expired less than the given
	 * number of seconds ago, and returns the stale value if another thread holds the
	 * regeneration lock already. If a value is used within a short interval after expiry,
	 * it is assumed the key has a high enough access rate to justify avoiding a stampede.
	 *
	 * If no previous value exists, this setting is ignored and no regen lock is used (e.g. after
	 * deletion, expiry, or eviction at the storage layer). Use busyValue() to enable use of
	 * a regen lock to avoid stempedes in those cases.
	 *
	 * This corresponds to the "lockTSE" option of WANObjectCache::getWithSetCallback().
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function allowStale( int $seconds = 30 ) {
		$this->options['lockTSE'] = $seconds;

		return $this;
	}

	/**
	 * Return this placeholder when no value exists and another thread is generating one
	 *
	 * This ensures that a stampede cannot happen when the value falls out of cache entirely.
	 *
	 * @param mixed $value Placeholder value, or a closure that returns one when needed
	 * @return $this
	 */
	public function busyValue( $value ) {
		$this->options['busyValue'] = $value;

		return $this;
	}

	/**
	 * Keep an expired value around for this many seconds
	 *
	 * On a miss, the callback then still sees the expired value as its previous value, which
	 * suits callbacks that can cheaply verify that it is still correct.
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function keepStaleFor( int $seconds ) {
		$this->options['staleTTL'] = $seconds;

		return $this;
	}

	/**
	 * Consider regenerating early once the value has less than this long left to live
	 *
	 * Regeneration becomes more likely as the value approaches expiry, and more likely still
	 * when the key is popular. This avoids a stampede of threads all regenerating at once when
	 * a hot key expires. Pass 0 to disable, or leave unset for the default of 60 seconds.
	 *
	 * This corresponds to the "lowTTL" option of WANObjectCache::getWithSetCallback().
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function refreshBeforeExpiry( int $seconds ) {
		$this->options['lowTTL'] = $seconds;

		return $this;
	}

	/**
	 * Schedule an asynchronous refresh of popular keys about this often
	 *
	 * For keys read more than once per second, a refresh is scheduled on a random request
	 * roughly every $seconds; for even hotter keys it happens sooner. Pass 0 to disable, or
	 * leave unset for the default of 900 seconds.
	 *
	 * This corresponds to the "hotTTR" option of WANObjectCache::getWithSetCallback().
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function refreshPopularEvery( int $seconds ) {
		$this->options['hotTTR'] = $seconds;

		return $this;
	}

	/**
	 * Only start refreshing a popular key once the value is this old
	 *
	 * This corresponds to the "ageNew" option of WANObjectCache::getWithSetCallback().
	 *
	 * @see WANGetWithSetCallbackBuilder::refreshPopularEvery()
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function refreshPopularAfterAge( int $seconds ) {
		$this->options['ageNew'] = $seconds;

		return $this;
	}

	/**
	 * Also keep the value in this PHP process for this many seconds
	 *
	 * This avoids network I/O when a key is read several times within one request. Purges are
	 * not seen while a value is held in the process cache. False values are not kept.
	 *
	 * This corresponds to the "pcTTL" option of WANObjectCache::getWithSetCallback().
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function processCacheLifetime( int $seconds ) {
		$this->options['pcTTL'] = $seconds;

		return $this;
	}

	/**
	 * Keep the value in this PHP process for the life of a quick request
	 *
	 * @see WANGetWithSetCallbackBuilder::processCacheLifetime()
	 *
	 * @return $this
	 */
	public function shortProcessCache() {
		return $this->processCacheLifetime( WANObjectCache::TTL_PROC_SHORT );
	}

	/**
	 * Keep the value in this PHP process for long enough to survive a slow request
	 *
	 * @see WANGetWithSetCallbackBuilder::processCacheLifetime()
	 *
	 * @return $this
	 */
	public function longProcessCache() {
		return $this->processCacheLifetime( WANObjectCache::TTL_PROC_LONG );
	}

	/**
	 * Use a dedicated process cache group instead of the primary one
	 *
	 * Use this for large values, for small yet numerous values, or for values with a high cost
	 * of eviction. This has no effect unless the value is process cached.
	 *
	 * @param string $group Of the format ALPHANUMERIC_NAME:MAX_KEY_SIZE, e.g. "mydata:10"
	 * @return $this
	 */
	public function processCacheGroup( string $group ) {
		$this->options['pcGroup'] = $group;

		return $this;
	}

	/**
	 * Set the format version of the value
	 *
	 * This lets callers make breaking changes to the format of cached values without causing
	 * problems for sites that deploy code gradually. Old and new code recognise each other's
	 * versions as incompatible and read from and write to separate variant keys, while purges
	 * from either are seen by both.
	 *
	 * @param int $version
	 * @return $this
	 */
	public function valueVersion( int $version ) {
		$this->options['version'] = $version;

		return $this;
	}

	/**
	 * Allow a large string value to be split over several cache entries
	 *
	 * @param bool $enabled
	 * @return $this
	 */
	public function allowSegmentation( bool $enabled = true ) {
		$this->options['segmentable'] = $enabled;

		return $this;
	}

	/**
	 * Set the callback
	 *
	 * @param callable $callback Takes ( mixed $oldValue )
	 * @return $this
	 */
	public function callback( callable $callback ) {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * Get the value at the key, generating and storing it via the callback if needed
	 *
	 * @return mixed Value found or written to the key
	 */
	public function fetch() {
		if ( $this->key === null ) {
			throw new LogicException( 'No cache key set; call key(), globalKey(), or rawKey() first' );
		}
		if ( $this->lifetime === null ) {
			throw new LogicException( 'No lifetime set; call lifetime(), keepIndefinitely(), or e.g. keepForADay()' );
		}
		if ( $this->callback === null ) {
			throw new LogicException( 'No callback set; call callback()' );
		}

		return $this->cache->getWithSetCallback(
			$this->key,
			$this->lifetime,
			$this->callback,
			$this->options
		);
	}
}
