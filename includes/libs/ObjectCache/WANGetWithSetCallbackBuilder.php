<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\ObjectCache;

use LogicException;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;

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
 * The callback receives the previous value (false if there was none) and a
 * UpdateContext, which replaces the by-reference parameters of the plain
 * WANObjectCache::getWithSetCallback() callback:
 *
 * @code
 *     $row = $cache->buildGetWithSetCallback()
 *         ->key( 'user-profile', $userId )
 *         ->keepForADay()
 *         ->allowStale( 30 )
 *         ->callback( function ( $oldValue, UpdateContext $context ) {
 *             $row = $this->loadProfileRow();
 *             if ( !$row ) {
 *                 $context->doNotCache();
 *             }
 *
 *             return $row;
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
	/** @var array Custom field/value map to pass to the callback */
	private array $callbackParams = [];

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
		return $this->lifetime( ExpirationAwareness::TTL_INDEFINITE );
	}

	/**
	 * Reuse a newly generated value for one minute
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForAMinute() {
		return $this->lifetime( ExpirationAwareness::TTL_MINUTE );
	}

	/**
	 * Reuse a newly generated value for one hour
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForAnHour() {
		return $this->lifetime( ExpirationAwareness::TTL_HOUR );
	}

	/**
	 * Reuse a newly generated value for one day
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForADay() {
		return $this->lifetime( ExpirationAwareness::TTL_DAY );
	}

	/**
	 * Reuse a newly generated value for one week
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForAWeek() {
		return $this->lifetime( ExpirationAwareness::TTL_WEEK );
	}

	/**
	 * Reuse a newly generated value for one month
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForAMonth() {
		return $this->lifetime( ExpirationAwareness::TTL_MONTH );
	}

	/**
	 * Reuse a newly generated value for one year
	 *
	 * @see WANGetWithSetCallbackBuilder::lifetime()
	 *
	 * @return $this
	 */
	public function keepForAYear() {
		return $this->lifetime( ExpirationAwareness::TTL_YEAR );
	}

	/**
	 * Treat the value as stale whenever the given "check" key is touched
	 *
	 * The value is seen as stale when either touchCheckKey() or resetCheckKey() is called on
	 * the given key. This is useful when thousands or millions of keys depend on the same
	 * entity: that entity can simply have its "check" key updated whenever it is modified.
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
	 *
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
	 * Reuse a value that a purge made stale less than this many seconds ago
	 *
	 * The odds of regenerating instead become more likely over time, becoming certain once the
	 * grace period is reached. This spreads out the load when millions of keys are compared to
	 * the same "check" key. It does not apply to values that merely reached the end of their
	 * lifetime; use refreshBeforeExpiry() for that.
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function gracePeriod( int $seconds ) {
		$this->options['graceTTL'] = $seconds;

		return $this;
	}

	/**
	 * Reuse a recently expired value while another thread regenerates it
	 *
	 * This applies when the value expired less than the given number of seconds ago and another
	 * thread holds the regeneration lock. A short interval implies a high enough access rate to
	 * justify avoiding a stampede. Note that no value exists at all after deletion, expiry, or
	 * eviction at the storage layer; use busyValue() to cover those cases.
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
	 * Reject values that were generated before the given UNIX timestamp
	 *
	 * This is useful when the source of the value is suspected of having changed recently and
	 * the caller wants any such change to be reflected.
	 *
	 * @param float $unixTimestamp
	 * @return $this
	 */
	public function rejectValuesOlderThan( int|float $unixTimestamp ) {
		$this->options['minAsOf'] = $unixTimestamp;

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
		return $this->processCacheLifetime( ExpirationAwareness::TTL_PROC_SHORT );
	}

	/**
	 * Keep the value in this PHP process for long enough to survive a slow request
	 *
	 * @see WANGetWithSetCallbackBuilder::processCacheLifetime()
	 *
	 * @return $this
	 */
	public function longProcessCache() {
		return $this->processCacheLifetime( ExpirationAwareness::TTL_PROC_LONG );
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
	 * Pass a custom field/value map to the callback
	 *
	 * The callback reads it via CacheRegenerationContext::getParam().
	 *
	 * @param array $params
	 * @return $this
	 */
	public function callbackParams( array $params ) {
		$this->callbackParams = $params;

		return $this;
	}

	/**
	 * Set the callback
	 *
	 * The callback is given the previous value (false if there was none) and a
	 * CacheRegenerationContext. Returning false means "not cacheable"; to store a negative
	 * result, return some other value, and to skip storing anything at all, call
	 * CacheRegenerationContext::doNotCache().
	 *
	 * @param callable $callback Takes ( mixed $oldValue, CacheRegenerationContext $context )
	 * @return $this
	 */
	public function callback( callable $callback ) {
		$this->callback = static function ( $oldValue, &$ttl, array &$setOpts, $oldAsOf, array $params )
		use ( $callback )
		{
			$context = new UpdateContext( $ttl, $oldValue, $oldAsOf, $params );
			$value = $callback( $oldValue, $context );
			$ttl = $context->getLifetime();

			return $value;
		};

		return $this;
	}

	/**
	 * Get the value at the key, generating and storing it via the callback if needed
	 *
	 * @return mixed Value found or written to the key
	 */
	public function fetch() {
		if ( $this->key === null ) {
			throw new LogicException( 'No cache key set; call key() or globalKey() first' );
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
			$this->options,
			$this->callbackParams
		);
	}
}
