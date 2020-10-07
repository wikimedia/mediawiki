<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\LightweightObjectStore\StorageAwareness;

/**
 * Multi-datacenter aware caching interface
 *
 * ### Using WANObjectCache
 *
 * All operations go to the local datacenter cache, except for delete(),
 * touchCheckKey(), and resetCheckKey(), which broadcast to all datacenters.
 *
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * The preferred way to do this logic is through getWithSetCallback().
 * When querying the store on cache miss, the closest DB replica
 * should be used. Try to avoid heavyweight DB master or quorum reads.
 *
 * To ensure consumers of the cache see new values in a timely manner,
 * you either need to follow either the validation strategy, or the
 * purge strategy.
 *
 * The validation strategy refers to the natural avoidance of stale data
 * by one of the following means:
 *
 *   - A) The cached value is immutable.
 *        If the consumer has access to an identifier that uniquely describes a value,
 *        cached value need not change. Instead, the key can change. This also allows
 *        all servers to access their perceived current version. This is important
 *        in context of multiple deployed versions of your application and/or cross-dc
 *        database replication, to ensure deterministic values without oscillation.
 *   - B) Validity is checked against the source after get().
 *        This is the inverse of A. The unique identifier is embedded inside the value
 *        and validated after on retreival. If outdated, the value is recomputed.
 *   - C) The value is cached with a modest TTL (without validation).
 *        If value recomputation is reasonably performant, and the value is allowed to
 *        be stale, one should consider using TTL only – using the value's age as
 *        method of validation.
 *
 * The purge strategy refers to the approach whereby your application knows that
 * source data has changed and can react by purging the relevant cache keys.
 * As purges are expensive, this strategy should be avoided if possible.
 * The simplest purge method is delete().
 *
 * No matter which strategy you choose, callers must not rely on updates or purges
 * being immediately visible to other servers. It should be treated similarly as
 * one would a database replica.
 *
 * The need for immediate updates should be avoided. If needed, solutions must be
 * sought outside WANObjectCache.
 *
 * @anchor wanobjectcache-deployment
 * ### Deploying %WANObjectCache
 *
 * There are two supported ways to set up broadcasted operations:
 *
 *   - A) Set up mcrouter as the underlying cache backend, using a memcached BagOStuff class
 *        for the 'cache' parameter. The 'region' and 'cluster' parameters must be provided
 *        and 'mcrouterAware' must be set to `true`.
 *        Configure mcrouter as follows:
 *          - 1) Use Route Prefixing based on region (datacenter) and cache cluster.
 *               See https://github.com/facebook/mcrouter/wiki/Routing-Prefix and
 *               https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup.
 *          - 2) To increase the consistency of delete() and touchCheckKey() during cache
 *               server membership changes, you can use the OperationSelectorRoute to
 *               configure 'set' and 'delete' operations to go to all servers in the cache
 *               cluster, instead of just one server determined by hashing.
 *               See https://github.com/facebook/mcrouter/wiki/List-of-Route-Handles.
 *   - B) Set up dynomite as a cache middleware between the web servers and either memcached
 *        or redis and use it as the underlying cache backend, using a memcached BagOStuff
 *        class for the 'cache' parameter. This will broadcast all key setting operations,
 *        not just purges, which can be useful for cache warming. Writes are eventually
 *        consistent via the Dynamo replication model. See https://github.com/Netflix/dynomite.
 *
 * Broadcasted operations like delete() and touchCheckKey() are intended to run
 * immediately in the local datacenter and asynchronously in remote datacenters.
 *
 * This means that callers in all datacenters may see older values for however many
 * milliseconds that the purge took to reach that datacenter. As with any cache, this
 * should not be relied on for cases where reads are used to determine writes to source
 * (e.g. non-cache) data stores, except when reading immutable data.
 *
 * All values are wrapped in metadata arrays. Keys use a "WANCache:" prefix to avoid
 * collisions with keys that are not wrapped as metadata arrays. For any given key that
 * a caller uses, there are several "sister" keys that might be involved under the hood.
 * Each "sister" key differs only by a single-character:
 *   - v: used for regular value keys
 *   - i: used for temporarily storing values of tombstoned keys
 *   - t: used for storing timestamp "check" keys
 *   - m: used for temporary mutex keys to avoid cache stampedes
 *
 * @ingroup Cache
 * @since 1.26
 */
class WANObjectCache implements
	ExpirationAwareness,
	StorageAwareness,
	IStoreKeyEncoder,
	LoggerAwareInterface
{
	/** @var BagOStuff The local datacenter cache */
	protected $cache;
	/** @var MapCacheLRU[] Map of group PHP instance caches */
	protected $processCaches = [];
	/** @var LoggerInterface */
	protected $logger;
	/** @var StatsdDataFactoryInterface */
	protected $stats;
	/** @var callable|null Function that takes a WAN cache callback and runs it later */
	protected $asyncHandler;

	/** @var bool Whether to use mcrouter key prefixing for routing */
	protected $mcrouterAware;
	/** @var string Physical region for mcrouter use */
	protected $region;
	/** @var string Cache cluster name for mcrouter use */
	protected $cluster;
	/** @var bool Whether to use "interim" caching while keys are tombstoned */
	protected $useInterimHoldOffCaching = true;
	/** @var float Unix timestamp of the oldest possible valid values */
	protected $epoch;
	/** @var string Stable secret used for hasing long strings into key components */
	protected $secret;
	/** @var string|bool Whether "sister" keys should be coalesced to the same cache server */
	protected $coalesceKeys;
	/** @var int Scheme to use for key coalescing (Hash Tags or Hash Stops) */
	protected $coalesceScheme;

	/** @var int Reads/second assumed during a hypothetical cache write stampede for a key */
	private $keyHighQps;
	/** @var float Max tolerable bytes/second to spend on a cache write stampede for a key */
	private $keyHighUplinkBps;

	/** @var int Callback stack depth for getWithSetCallback() */
	private $callbackDepth = 0;
	/** @var mixed[] Temporary warm-up cache */
	private $warmupCache = [];
	/** @var int Key fetched */
	private $warmupKeyMisses = 0;

	/** @var float|null */
	private $wallClockOverride;

	/** @var int Max expected seconds to pass between delete() and DB commit finishing */
	private const MAX_COMMIT_DELAY = 3;
	/** @var int Max expected seconds of combined lag from replication and view snapshots */
	private const MAX_READ_LAG = 7;
	/** @var int Seconds to tombstone keys on delete() and treat as volatile after invalidation */
	public const HOLDOFF_TTL = self::MAX_COMMIT_DELAY + self::MAX_READ_LAG + 1;

	/** @var int Consider regeneration if the key will expire within this many seconds */
	private const LOW_TTL = 30;
	/** @var int Max TTL, in seconds, to store keys when a data sourced is lagged */
	public const TTL_LAGGED = 30;

	/** @var int Expected time-till-refresh, in seconds, if the key is accessed once per second */
	private const HOT_TTR = 900;
	/** @var int Minimum key age, in seconds, for expected time-till-refresh to be considered */
	private const AGE_NEW = 60;

	/** @var int Idiom for getWithSetCallback() meaning "no cache stampede mutex required" */
	private const TSE_NONE = -1;

	/** @var int Idiom for set()/getWithSetCallback() meaning "no post-expiration persistence" */
	private const STALE_TTL_NONE = 0;
	/** @var int Idiom for set()/getWithSetCallback() meaning "no post-expiration grace period" */
	private const GRACE_TTL_NONE = 0;
	/** @var int Idiom for delete()/touchCheckKey() meaning "no hold-off period" */
	public const HOLDOFF_TTL_NONE = 0;
	/** @var int Alias for HOLDOFF_TTL_NONE (b/c) (deprecated since 1.34) */
	public const HOLDOFF_NONE = self::HOLDOFF_TTL_NONE;

	/** @var float Idiom for getWithSetCallback() meaning "no minimum required as-of timestamp" */
	public const MIN_TIMESTAMP_NONE = 0.0;

	/** @var string Default process cache name and max key count */
	private const PC_PRIMARY = 'primary:1000';

	/** @var int Idion for get()/getMulti() to return extra information by reference */
	public const PASS_BY_REF = -1;

	/** @var int Use twemproxy-style Hash Tag key scheme (e.g. "{...}") */
	private const SCHEME_HASH_TAG = 1;
	/** @var int Use mcrouter-style Hash Stop key scheme (e.g. "...|#|") */
	private const SCHEME_HASH_STOP = 2;

	/** @var int Seconds to keep dependency purge keys around */
	private static $CHECK_KEY_TTL = self::TTL_YEAR;
	/** @var int Seconds to keep interim value keys for tombstoned keys around */
	private static $INTERIM_KEY_TTL = 1;

	/** @var int Seconds to keep lock keys around */
	private static $LOCK_TTL = 10;
	/** @var int Seconds to no-op key set() calls to avoid large blob I/O stampedes */
	private static $COOLOFF_TTL = 1;
	/** @var int Seconds to ramp up the chance of regeneration due to expected time-till-refresh */
	private static $RAMPUP_TTL = 30;

	/** @var float Tiny negative float to use when CTL comes up >= 0 due to clock skew */
	private static $TINY_NEGATIVE = -0.000001;
	/** @var float Tiny positive float to use when using "minTime" to assert an inequality */
	private static $TINY_POSTIVE = 0.000001;

	/** @var int Min millisecond set() backoff during hold-off (far less than INTERIM_KEY_TTL) */
	private static $RECENT_SET_LOW_MS = 50;
	/** @var int Max millisecond set() backoff during hold-off (far less than INTERIM_KEY_TTL) */
	private static $RECENT_SET_HIGH_MS = 100;

	/** @var int Consider value generation slow if it takes more than this many seconds */
	private static $GENERATION_SLOW_SEC = 3;

	/** @var int Key to the tombstone entry timestamp */
	private static $PURGE_TIME = 0;
	/** @var int Key to the tombstone entry hold-off TTL */
	private static $PURGE_HOLDOFF = 1;

	/** @var int Cache format version number */
	private static $VERSION = 1;

	/** @var int Key to WAN cache version number */
	private static $FLD_FORMAT_VERSION = 0;
	/** @var int Key to the cached value */
	private static $FLD_VALUE = 1;
	/** @var int Key to the original TTL */
	private static $FLD_TTL = 2;
	/** @var int Key to the cache timestamp */
	private static $FLD_TIME = 3;
	/** @var int Key to the flags bit field (reserved number) */
	private static /** @noinspection PhpUnusedPrivateFieldInspection */ $FLD_FLAGS = 4;
	/** @var int Key to collection cache version number */
	private static $FLD_VALUE_VERSION = 5;
	/** @var int Key to how long it took to generate the value */
	private static $FLD_GENERATION_TIME = 6;

	/** @var string Single character value mutex key component */
	private static $TYPE_VALUE = 'v';
	/** @var string Single character timestamp key component */
	private static $TYPE_TIMESTAMP = 't';
	/** @var string Single character mutex key component */
	private static $TYPE_MUTEX = 'm';
	/** @var string Single character interium key component */
	private static $TYPE_INTERIM = 'i';
	/** @var string Single character cool-off key component */
	private static $TYPE_COOLOFF = 'c';

	/** @var string Prefix for tombstone key values */
	private static $PURGE_VAL_PREFIX = 'PURGED:';

	/**
	 * @param array $params
	 *   - cache    : BagOStuff object for a persistent cache
	 *   - logger   : LoggerInterface object
	 *   - stats    : StatsdDataFactoryInterface object
	 *   - asyncHandler : A function that takes a callback and runs it later. If supplied,
	 *       whenever a preemptive refresh would be triggered in getWithSetCallback(), the
	 *       current cache value is still used instead. However, the async-handler function
	 *       receives a WAN cache callback that, when run, will execute the value generation
	 *       callback supplied by the getWithSetCallback() caller. The result will be saved
	 *       as normal. The handler is expected to call the WAN cache callback at an opportune
	 *       time (e.g. HTTP post-send), though generally within a few 100ms. [optional]
	 *   - region: the current physical region. This is required when using mcrouter as the
	 *       backing store proxy. [optional]
	 *   - cluster: name of the cache cluster used by this WAN cache. The name must be the
	 *       same in all datacenters; the ("region","cluster") tuple is what distinguishes
	 *       the counterpart cache clusters among all the datacenter. The contents of
	 *       https://github.com/facebook/mcrouter/wiki/Config-Files give background on this.
	 *       This is required when using mcrouter as the backing store proxy. [optional]
	 *   - mcrouterAware: set as true if mcrouter is the backing store proxy and mcrouter
	 *       is configured to interpret /<region>/<cluster>/ key prefixes as routes. This
	 *       requires that "region" and "cluster" are both set above. [optional]
	 *   - epoch: lowest UNIX timestamp a value/tombstone must have to be valid. [optional]
	 *   - secret: stable secret used for hashing long strings into key components. [optional]
	 *   - coalesceKeys: whether to use a key scheme that encourages the backend to place any
	 *       "helper" keys for a "value" key within the same cache server. This reduces network
	 *       overhead and reduces the chance the a single downed cache server causes disruption.
	 *       Set this to "non-global" to only apply the scheme to non-global keys. [default: false]
	 *   - keyHighQps: reads/second assumed during a hypothetical cache write stampede for
	 *       a single key. This is used to decide when the overhead of checking short-lived
	 *       write throttling keys is worth it.
	 *       [default: 100]
	 *   - keyHighUplinkBps: maximum tolerable bytes/second to spend on a cache write stampede
	 *       for a single key. This is used to decide when the overhead of checking short-lived
	 *       write throttling keys is worth it. [default: (1/100 of a 1Gbps link)]
	 */
	public function __construct( array $params ) {
		$this->cache = $params['cache'];
		$this->region = $params['region'] ?? 'main';
		$this->cluster = $params['cluster'] ?? 'wan-main';
		$this->mcrouterAware = !empty( $params['mcrouterAware'] );
		$this->epoch = $params['epoch'] ?? 0;
		$this->secret = $params['secret'] ?? (string)$this->epoch;
		$this->coalesceKeys = $params['coalesceKeys'] ?? false;
		if ( !empty( $params['mcrouterAware'] ) ) {
			// https://github.com/facebook/mcrouter/wiki/Key-syntax
			$this->coalesceScheme = self::SCHEME_HASH_STOP;
		} else {
			// https://redis.io/topics/cluster-spec
			// https://github.com/twitter/twemproxy/blob/v0.4.1/notes/recommendation.md#hash-tags
			// https://github.com/Netflix/dynomite/blob/v0.7.0/notes/recommendation.md#hash-tags
			$this->coalesceScheme = self::SCHEME_HASH_TAG;
		}

		$this->keyHighQps = $params['keyHighQps'] ?? 100;
		$this->keyHighUplinkBps = $params['keyHighUplinkBps'] ?? ( 1e9 / 8 / 100 );

		$this->setLogger( $params['logger'] ?? new NullLogger() );
		$this->stats = $params['stats'] ?? new NullStatsdDataFactory();
		$this->asyncHandler = $params['asyncHandler'] ?? null;
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get an instance that wraps EmptyBagOStuff
	 *
	 * @return WANObjectCache
	 */
	public static function newEmpty() {
		return new static( [ 'cache' => new EmptyBagOStuff() ] );
	}

	/**
	 * Fetch the value of a key from cache
	 *
	 * If supplied, $curTTL is set to the remaining TTL (current time left):
	 *   - a) INF; if $key exists, has no TTL, and is not invalidated by $checkKeys
	 *   - b) float (>=0); if $key exists, has a TTL, and is not invalidated by $checkKeys
	 *   - c) float (<0); if $key is tombstoned, stale, or existing but invalidated by $checkKeys
	 *   - d) null; if $key does not exist and is not tombstoned
	 *
	 * If a key is tombstoned, $curTTL will reflect the time since delete().
	 *
	 * The timestamp of $key will be checked against the last-purge timestamp
	 * of each of $checkKeys. Those $checkKeys not in cache will have the last-purge
	 * initialized to the current timestamp. If any of $checkKeys have a timestamp
	 * greater than that of $key, then $curTTL will reflect how long ago $key
	 * became invalid. Callers can use $curTTL to know when the value is stale.
	 * The $checkKeys parameter allow mass invalidations by updating a single key:
	 *   - a) Each "check" key represents "last purged" of some source data
	 *   - b) Callers pass in relevant "check" keys as $checkKeys in get()
	 *   - c) When the source data that "check" keys represent changes,
	 *        the touchCheckKey() method is called on them
	 *
	 * Source data entities might exists in a DB that uses snapshot isolation
	 * (e.g. the default REPEATABLE-READ in innoDB). Even for mutable data, that
	 * isolation can largely be maintained by doing the following:
	 *   - a) Calling delete() on entity change *and* creation, before DB commit
	 *   - b) Keeping transaction duration shorter than the delete() hold-off TTL
	 *   - c) Disabling interim key caching via useInterimHoldOffCaching() before get() calls
	 *
	 * However, pre-snapshot values might still be seen if an update was made
	 * in a remote datacenter but the purge from delete() didn't relay yet.
	 *
	 * Consider using getWithSetCallback() instead of get() and set() cycles.
	 * That method has cache slam avoiding features for hot/expensive keys.
	 *
	 * Pass $info as WANObjectCache::PASS_BY_REF to transform it into a cache key metadata map.
	 * This map includes the following metadata:
	 *   - asOf: UNIX timestamp of the value or null if the key is nonexistant
	 *   - tombAsOf: UNIX timestamp of the tombstone or null if the key is not tombstoned
	 *   - lastCKPurge: UNIX timestamp of the highest check key or null if none provided
	 *   - version: cached value version number or null if the key is nonexistant
	 *
	 * Otherwise, $info will transform into the cached value timestamp.
	 *
	 * @param string $key Cache key made from makeKey()/makeGlobalKey()
	 * @param mixed|null &$curTTL Approximate TTL left on the key if present/tombstoned [returned]
	 * @param string[] $checkKeys The "check" keys used to validate the value
	 * @param mixed|null &$info Key info if WANObjectCache::PASS_BY_REF [returned]
	 * @return mixed Value of cache key or false on failure
	 */
	final public function get(
		$key, &$curTTL = null, array $checkKeys = [], &$info = null
	) {
		$curTTLs = self::PASS_BY_REF;
		$infoByKey = self::PASS_BY_REF;
		$values = $this->getMulti( [ $key ], $curTTLs, $checkKeys, $infoByKey );

		$curTTL = $curTTLs[$key] ?? null;
		if ( $info === self::PASS_BY_REF ) {
			$info = [
				'asOf' => $infoByKey[$key]['asOf'] ?? null,
				'tombAsOf' => $infoByKey[$key]['tombAsOf'] ?? null,
				'lastCKPurge' => $infoByKey[$key]['lastCKPurge'] ?? null,
				'version' => $infoByKey[$key]['version'] ?? null
			];
		} else {
			$info = $infoByKey[$key]['asOf'] ?? null; // b/c
		}

		return array_key_exists( $key, $values ) ? $values[$key] : false;
	}

	/**
	 * Fetch the value of several keys from cache
	 *
	 * Pass $info as WANObjectCache::PASS_BY_REF to transform it into a map of cache keys
	 * to cache key metadata maps, each having the same style as those of WANObjectCache::get().
	 * All the cache keys listed in $keys will have an entry.
	 *
	 * Othwerwise, $info will transform into a map of (cache key => cached value timestamp).
	 * Only the cache keys listed in $keys that exists or are tombstoned will have an entry.
	 *
	 * $checkKeys holds the "check" keys used to validate values of applicable keys. The integer
	 * indexes hold "check" keys that apply to all of $keys while the string indexes hold "check"
	 * keys that only apply to the cache key with that name.
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string[] $keys List/map with cache keys made from makeKey()/makeGlobalKey() as values
	 * @param mixed|null &$curTTLs Map of (key => TTL left) for existing/tombstoned keys [returned]
	 * @param string[]|string[][] $checkKeys Map of (integer or cache key => "check" key(s))
	 * @param mixed|null &$info Map of (key => info) if WANObjectCache::PASS_BY_REF [returned]
	 * @return mixed[] Map of (key => value) for existing values; order of $keys is preserved
	 */
	final public function getMulti(
		array $keys,
		&$curTTLs = [],
		array $checkKeys = [],
		&$info = null
	) {
		$result = [];
		$curTTLs = [];
		$infoByKey = [];

		// Order-corresponding list of value keys for the provided base keys
		$valueKeys = $this->makeSisterKeys( $keys, self::$TYPE_VALUE );

		$fullKeysNeeded = $valueKeys;
		$checkKeysForAll = [];
		$checkKeysByKey = [];
		foreach ( $checkKeys as $i => $checkKeyOrKeyGroup ) {
			// Note: avoid array_merge() inside loop in case there are many keys
			if ( is_int( $i ) ) {
				// Single check key that applies to all value keys
				$fullKey = $this->makeSisterKey( $checkKeyOrKeyGroup, self::$TYPE_TIMESTAMP );
				$fullKeysNeeded[] = $fullKey;
				$checkKeysForAll[] = $fullKey;
			} else {
				// List of check keys that apply to a specific value key
				foreach ( (array)$checkKeyOrKeyGroup as $checkKey ) {
					$fullKey = $this->makeSisterKey( $checkKey, self::$TYPE_TIMESTAMP );
					$fullKeysNeeded[] = $fullKey;
					$checkKeysByKey[$i][] = $fullKey;
				}
			}
		}

		if ( $this->warmupCache ) {
			// Get the raw values of the keys from the warmup cache
			$wrappedValues = $this->warmupCache;
			$fullKeysMissing = array_diff( $fullKeysNeeded, array_keys( $wrappedValues ) );
			if ( $fullKeysMissing ) { // sanity
				$this->warmupKeyMisses += count( $fullKeysMissing );
				$wrappedValues += $this->cache->getMulti( $fullKeysMissing );
			}
		} else {
			// Fetch the raw values of the keys from the backend
			$wrappedValues = $this->cache->getMulti( $fullKeysNeeded );
		}

		// Time used to compare/init "check" keys (derived after getMulti() to be pessimistic)
		$now = $this->getCurrentTime();

		// Collect timestamps from all "check" keys
		$purgeValuesForAll = $this->processCheckKeys( $checkKeysForAll, $wrappedValues, $now );
		$purgeValuesByKey = [];
		foreach ( $checkKeysByKey as $cacheKey => $checks ) {
			$purgeValuesByKey[$cacheKey] = $this->processCheckKeys( $checks, $wrappedValues, $now );
		}

		// Get the main cache value for each key and validate them
		reset( $keys );
		foreach ( $valueKeys as $i => $vKey ) {
			// Get the corresponding base key for this value key
			$key = current( $keys );
			next( $keys );

			list( $value, $keyInfo ) = $this->unwrap(
				array_key_exists( $vKey, $wrappedValues ) ? $wrappedValues[$vKey] : false,
				$now
			);
			// Force dependent keys to be seen as stale for a while after purging
			// to reduce race conditions involving stale data getting cached
			$purgeValues = $purgeValuesForAll;
			if ( isset( $purgeValuesByKey[$key] ) ) {
				$purgeValues = array_merge( $purgeValues, $purgeValuesByKey[$key] );
			}

			$lastCKPurge = null; // timestamp of the highest check key
			foreach ( $purgeValues as $purge ) {
				$lastCKPurge = max( $purge[self::$PURGE_TIME], $lastCKPurge );
				$safeTimestamp = $purge[self::$PURGE_TIME] + $purge[self::$PURGE_HOLDOFF];
				if ( $value !== false && $safeTimestamp >= $keyInfo['asOf'] ) {
					// How long ago this value was invalidated by *this* check key
					$ago = min( $purge[self::$PURGE_TIME] - $now, self::$TINY_NEGATIVE );
					// How long ago this value was invalidated by *any* known check key
					$keyInfo['curTTL'] = min( $keyInfo['curTTL'], $ago );
				}
			}
			$keyInfo[ 'lastCKPurge'] = $lastCKPurge;

			if ( $value !== false ) {
				$result[$key] = $value;
			}
			if ( $keyInfo['curTTL'] !== null ) {
				$curTTLs[$key] = $keyInfo['curTTL'];
			}

			$infoByKey[$key] = ( $info === self::PASS_BY_REF )
				? $keyInfo
				: $keyInfo['asOf']; // b/c
		}

		$info = $infoByKey;

		return $result;
	}

	/**
	 * @param string[] $timeKeys List of prefixed time check keys
	 * @param mixed[] $wrappedValues Preloaded map of (key => value)
	 * @param float $now
	 * @return array[] List of purge value arrays
	 * @since 1.27
	 */
	private function processCheckKeys( array $timeKeys, array $wrappedValues, $now ) {
		$purgeValues = [];
		foreach ( $timeKeys as $timeKey ) {
			$purge = isset( $wrappedValues[$timeKey] )
				? $this->parsePurgeValue( $wrappedValues[$timeKey] )
				: false;
			if ( $purge === false ) {
				// Key is not set or malformed; regenerate
				$newVal = $this->makePurgeValue( $now, self::HOLDOFF_TTL );
				$this->cache->add( $timeKey, $newVal, self::$CHECK_KEY_TTL );
				$purge = $this->parsePurgeValue( $newVal );
			}
			$purgeValues[] = $purge;
		}

		return $purgeValues;
	}

	/**
	 * Set the value of a key in cache
	 *
	 * Simply calling this method when source data changes is not valid because
	 * the changes do not replicate to the other WAN sites. In that case, delete()
	 * should be used instead. This method is intended for use on cache misses.
	 *
	 * If the data was read from a snapshot-isolated transactions (e.g. the default
	 * REPEATABLE-READ in innoDB), use 'since' to avoid the following race condition:
	 *   - a) T1 starts
	 *   - b) T2 updates a row, calls delete(), and commits
	 *   - c) The HOLDOFF_TTL passes, expiring the delete() tombstone
	 *   - d) T1 reads the row and calls set() due to a cache miss
	 *   - e) Stale value is stuck in cache
	 *
	 * Setting 'lag' and 'since' help avoids keys getting stuck in stale states.
	 *
	 * Be aware that this does not update the process cache for getWithSetCallback()
	 * callers. Keys accessed via that method are not generally meant to also be set
	 * using this primitive method.
	 *
	 * Do not use this method on versioned keys accessed via getWithSetCallback().
	 *
	 * Example usage:
	 * @code
	 *     $dbr = wfGetDB( DB_REPLICA );
	 *     $setOpts = Database::getCacheSetOptions( $dbr );
	 *     // Fetch the row from the DB
	 *     $row = $dbr->selectRow( ... );
	 *     $key = $cache->makeKey( 'building', $buildingId );
	 *     $cache->set( $key, $row, $cache::TTL_DAY, $setOpts );
	 * @endcode
	 *
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param int $ttl Seconds to live. Special values are:
	 *   - WANObjectCache::TTL_INDEFINITE: Cache forever (default)
	 *   - WANObjectCache::TTL_UNCACHEABLE: Do not cache (if the key exists, it is not deleted)
	 * @param array $opts Options map:
	 *   - lag: Seconds of replica DB lag. Typically, this is either the replica DB lag
	 *      before the data was read or, if applicable, the replica DB lag before
	 *      the snapshot-isolated transaction the data was read from started.
	 *      Use false to indicate that replication is not running.
	 *      Default: 0 seconds
	 *   - since: UNIX timestamp of the data in $value. Typically, this is either
	 *      the current time the data was read or (if applicable) the time when
	 *      the snapshot-isolated transaction the data was read from started.
	 *      Default: 0 seconds
	 *   - pending: Whether this data is possibly from an uncommitted write transaction.
	 *      Generally, other threads should not see values from the future and
	 *      they certainly should not see ones that ended up getting rolled back.
	 *      Default: false
	 *   - lockTSE: If excessive replication/snapshot lag is detected, then store the value
	 *      with this TTL and flag it as stale. This is only useful if the reads for this key
	 *      use getWithSetCallback() with "lockTSE" set. Note that if "staleTTL" is set
	 *      then it will still add on to this TTL in the excessive lag scenario.
	 *      Default: WANObjectCache::TSE_NONE
	 *   - staleTTL: Seconds to keep the key around if it is stale. The get()/getMulti()
	 *      methods return such stale values with a $curTTL of 0, and getWithSetCallback()
	 *      will call the regeneration callback in such cases, passing in the old value
	 *      and its as-of time to the callback. This is useful if adaptiveTTL() is used
	 *      on the old value's as-of time when it is verified as still being correct.
	 *      Default: WANObjectCache::STALE_TTL_NONE
	 *   - creating: Optimize for the case where the key does not already exist.
	 *      Default: false
	 *   - version: Integer version number signifiying the format of the value.
	 *      Default: null
	 *   - walltime: How long the value took to generate in seconds. Default: null
	 * @codingStandardsIgnoreStart
	 * @phan-param array{lag?:int,since?:int,pending?:bool,lockTSE?:int,staleTTL?:int,creating?:bool,version?:?string,walltime?:int|float} $opts
	 * @codingStandardsIgnoreEnd
	 * @note Options added in 1.28: staleTTL
	 * @note Options added in 1.33: creating
	 * @note Options added in 1.34: version, walltime
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = self::TTL_INDEFINITE, array $opts = [] ) {
		$now = $this->getCurrentTime();
		$lag = $opts['lag'] ?? 0;
		$age = isset( $opts['since'] ) ? max( 0, $now - $opts['since'] ) : 0;
		$pending = $opts['pending'] ?? false;
		$lockTSE = $opts['lockTSE'] ?? self::TSE_NONE;
		$staleTTL = $opts['staleTTL'] ?? self::STALE_TTL_NONE;
		$creating = $opts['creating'] ?? false;
		$version = $opts['version'] ?? null;
		$walltime = $opts['walltime'] ?? null;

		if ( $ttl < 0 ) {
			return true; // not cacheable
		}

		// Do not cache potentially uncommitted data as it might get rolled back
		if ( $pending ) {
			$this->logger->info(
				'Rejected set() for {cachekey} due to pending writes.',
				[ 'cachekey' => $key ]
			);

			return true; // no-op the write for being unsafe
		}

		// Check if there is a risk of caching (stale) data that predates the last delete()
		// tombstone due to the tombstone having expired. If so, then the behavior should depend
		// on whether the problem is specific to this regeneration attempt or systemically affects
		// attempts to regenerate this key. For systemic cases, the cache writes should set a low
		// TTL so that the value at least remains cacheable. For non-systemic cases, the cache
		// write can simply be rejected.
		if ( $age > self::MAX_READ_LAG ) {
			// Case A: high snapshot lag
			if ( $walltime === null ) {
				// Case A0: high snapshot lag without regeneration wall time info.
				// Probably systemic; use a low TTL to avoid stampedes/uncacheability.
				$mitigated = 'snapshot lag';
				$mitigationTTL = self::TTL_SECOND;
			} elseif ( ( $age - $walltime ) > self::MAX_READ_LAG ) {
				// Case A1: value regeneration during an already long-running transaction.
				// Probably non-systemic; rely on a less problematic regeneration attempt.
				$mitigated = 'snapshot lag (late regeneration)';
				$mitigationTTL = self::TTL_UNCACHEABLE;
			} else {
				// Case A2: value regeneration takes a long time.
				// Probably systemic; use a low TTL to avoid stampedes/uncacheability.
				$mitigated = 'snapshot lag (high regeneration time)';
				$mitigationTTL = self::TTL_SECOND;
			}
		} elseif ( $lag === false || $lag > self::MAX_READ_LAG ) {
			// Case B: high replication lag without high snapshot lag
			// Probably systemic; use a low TTL to avoid stampedes/uncacheability
			$mitigated = 'replication lag';
			$mitigationTTL = self::TTL_LAGGED;
		} elseif ( ( $lag + $age ) > self::MAX_READ_LAG ) {
			// Case C: medium length request with medium replication lag
			// Probably non-systemic; rely on a less problematic regeneration attempt
			$mitigated = 'read lag';
			$mitigationTTL = self::TTL_UNCACHEABLE;
		} else {
			// New value generated with recent enough data
			$mitigated = null;
			$mitigationTTL = null;
		}

		if ( $mitigationTTL === self::TTL_UNCACHEABLE ) {
			$this->logger->warning(
				"Rejected set() for {cachekey} due to $mitigated.",
				[ 'cachekey' => $key, 'lag' => $lag, 'age' => $age, 'walltime' => $walltime ]
			);

			return true; // no-op the write for being unsafe
		}

		// TTL to use in staleness checks (does not effect persistence layer TTL)
		$logicalTTL = null;

		if ( $mitigationTTL !== null ) {
			// New value generated from data that is old enough to be risky
			if ( $lockTSE >= 0 ) {
				// Value will have the normal expiry but will be seen as stale sooner
				$logicalTTL = min( $ttl ?: INF, $mitigationTTL );
			} else {
				// Value expires sooner (leaving enough TTL for preemptive refresh)
				$ttl = min( $ttl ?: INF, max( $mitigationTTL, self::LOW_TTL ) );
			}

			$this->logger->warning(
				"Lowered set() TTL for {cachekey} due to $mitigated.",
				[ 'cachekey' => $key, 'lag' => $lag, 'age' => $age, 'walltime' => $walltime ]
			);
		}

		// Wrap that value with time/TTL/version metadata
		$wrapped = $this->wrap( $value, $logicalTTL ?: $ttl, $version, $now, $walltime );
		$storeTTL = $ttl + $staleTTL;

		if ( $creating ) {
			$ok = $this->cache->add(
				$this->makeSisterKey( $key, self::$TYPE_VALUE ),
				$wrapped,
				$storeTTL
			);
		} else {
			$ok = $this->cache->merge(
				$this->makeSisterKey( $key, self::$TYPE_VALUE ),
				function ( $cache, $key, $cWrapped ) use ( $wrapped ) {
					// A string value means that it is a tombstone; do nothing in that case
					return ( is_string( $cWrapped ) ) ? false : $wrapped;
				},
				$storeTTL,
				1 // 1 attempt
			);
		}

		return $ok;
	}

	/**
	 * Purge a key from all datacenters
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way. This deletes the key and starts a hold-off
	 * period where the key cannot be written to for a few seconds (HOLDOFF_TTL).
	 * This is done to avoid the following race condition:
	 *   - a) Some DB data changes and delete() is called on a corresponding key
	 *   - b) A request refills the key with a stale value from a lagged DB
	 *   - c) The stale value is stuck there until the key is expired/evicted
	 *
	 * This is implemented by storing a special "tombstone" value at the cache
	 * key that this class recognizes; get() calls will return false for the key
	 * and any set() calls will refuse to replace tombstone values at the key.
	 * For this to always avoid stale value writes, the following must hold:
	 *   - a) Replication lag is bounded to being less than HOLDOFF_TTL; or
	 *   - b) If lag is higher, the DB will have gone into read-only mode already
	 *
	 * Note that set() can also be lag-aware and lower the TTL if it's high.
	 *
	 * Be aware that this does not clear the process cache. Even if it did, callbacks
	 * used by getWithSetCallback() might still return stale data in the case of either
	 * uncommitted or not-yet-replicated changes (callback generally use replica DBs).
	 *
	 * When using potentially long-running ACID transactions, a good pattern is
	 * to use a pre-commit hook to issue the delete. This means that immediately
	 * after commit, callers will see the tombstone in cache upon purge relay.
	 * It also avoids the following race condition:
	 *   - a) T1 begins, changes a row, and calls delete()
	 *   - b) The HOLDOFF_TTL passes, expiring the delete() tombstone
	 *   - c) T2 starts, reads the row and calls set() due to a cache miss
	 *   - d) T1 finally commits
	 *   - e) Stale value is stuck in cache
	 *
	 * Example usage:
	 * @code
	 *     $dbw->startAtomic( __METHOD__ ); // start of request
	 *     ... <execute some stuff> ...
	 *     // Update the row in the DB
	 *     $dbw->update( ... );
	 *     $key = $cache->makeKey( 'homes', $homeId );
	 *     // Purge the corresponding cache entry just before committing
	 *     $dbw->onTransactionPreCommitOrIdle( function() use ( $cache, $key ) {
	 *         $cache->delete( $key );
	 *     } );
	 *     ... <execute some stuff> ...
	 *     $dbw->endAtomic( __METHOD__ ); // end of request
	 * @endcode
	 *
	 * The $ttl parameter can be used when purging values that have not actually changed
	 * recently. For example, a cleanup script to purge cache entries does not really need
	 * a hold-off period, so it can use HOLDOFF_TTL_NONE. Likewise for user-requested purge.
	 * Note that $ttl limits the effective range of 'lockTSE' for getWithSetCallback().
	 *
	 * If called twice on the same key, then the last hold-off TTL takes precedence. For
	 * idempotence, the $ttl should not vary for different delete() calls on the same key.
	 *
	 * @param string $key Cache key
	 * @param int $ttl Tombstone TTL; Default: WANObjectCache::HOLDOFF_TTL
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function delete( $key, $ttl = self::HOLDOFF_TTL ) {
		if ( $ttl <= 0 ) {
			// Publish the purge to all datacenters
			$ok = $this->relayDelete( $this->makeSisterKey( $key, self::$TYPE_VALUE ) );
		} else {
			// Publish the purge to all datacenters
			$ok = $this->relayPurge(
				$this->makeSisterKey( $key, self::$TYPE_VALUE ),
				$ttl,
				self::HOLDOFF_TTL_NONE
			);
		}

		$kClass = $this->determineKeyClassForStats( $key );
		$this->stats->increment( "wanobjectcache.$kClass.delete." . ( $ok ? 'ok' : 'error' ) );

		return $ok;
	}

	/**
	 * Fetch the value of a timestamp "check" key
	 *
	 * The key will be *initialized* to the current time if not set,
	 * so only call this method if this behavior is actually desired
	 *
	 * The timestamp can be used to check whether a cached value is valid.
	 * Callers should not assume that this returns the same timestamp in
	 * all datacenters due to relay delays.
	 *
	 * The level of staleness can roughly be estimated from this key, but
	 * if the key was evicted from cache, such calculations may show the
	 * time since expiry as ~0 seconds.
	 *
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @param string $key
	 * @return float UNIX timestamp
	 */
	final public function getCheckKeyTime( $key ) {
		return $this->getMultiCheckKeyTime( [ $key ] )[$key];
	}

	/**
	 * Fetch the values of each timestamp "check" key
	 *
	 * This works like getCheckKeyTime() except it takes a list of keys
	 * and returns a map of timestamps instead of just that of one key
	 *
	 * This might be useful if both:
	 *   - a) a class of entities each depend on hundreds of other entities
	 *   - b) these other entities are depended upon by millions of entities
	 *
	 * The later entities can each use a "check" key to invalidate their dependee entities.
	 * However, it is expensive for the former entities to verify against all of the relevant
	 * "check" keys during each getWithSetCallback() call. A less expensive approach is to do
	 * these verifications only after a "time-till-verify" (TTV) has passed. This is a middle
	 * ground between using blind TTLs and using constant verification. The adaptiveTTL() method
	 * can be used to dynamically adjust the TTV. Also, the initial TTV can make use of the
	 * last-modified times of the dependant entities (either from the DB or the "check" keys).
	 *
	 * Example usage:
	 * @code
	 *     $value = $cache->getWithSetCallback(
	 *         $cache->makeGlobalKey( 'wikibase-item', $id ),
	 *         self::INITIAL_TTV, // initial time-till-verify
	 *         function ( $oldValue, &$ttv, &$setOpts, $oldAsOf ) use ( $checkKeys, $cache ) {
	 *             $now = microtime( true );
	 *             // Use $oldValue if it passes max ultimate age and "check" key comparisons
	 *             if ( $oldValue &&
	 *                 $oldAsOf > max( $cache->getMultiCheckKeyTime( $checkKeys ) ) &&
	 *                 ( $now - $oldValue['ctime'] ) <= self::MAX_CACHE_AGE
	 *             ) {
	 *                 // Increase time-till-verify by 50% of last time to reduce overhead
	 *                 $ttv = $cache->adaptiveTTL( $oldAsOf, self::MAX_TTV, self::MIN_TTV, 1.5 );
	 *                 // Unlike $oldAsOf, "ctime" is the ultimate age of the cached data
	 *                 return $oldValue;
	 *             }
	 *
	 *             $mtimes = []; // dependency last-modified times; passed by reference
	 *             $value = [ 'data' => $this->fetchEntityData( $mtimes ), 'ctime' => $now ];
	 *             // Guess time-till-change among the dependencies, e.g. 1/(total change rate)
	 *             $ttc = 1 / array_sum( array_map(
	 *                 function ( $mtime ) use ( $now ) {
	 *                     return 1 / ( $mtime ? ( $now - $mtime ) : 900 );
	 *                 },
	 *                 $mtimes
	 *             ) );
	 *             // The time-to-verify should not be overly pessimistic nor optimistic
	 *             $ttv = min( max( $ttc, self::MIN_TTV ), self::MAX_TTV );
	 *
	 *             return $value;
	 *         },
	 *         [ 'staleTTL' => $cache::TTL_DAY ] // keep around to verify and re-save
	 *     );
	 * @endcode
	 *
	 * @see WANObjectCache::getCheckKeyTime()
	 * @see WANObjectCache::getWithSetCallback()
	 *
	 * @param string[] $keys
	 * @return float[] Map of (key => UNIX timestamp)
	 * @since 1.31
	 */
	final public function getMultiCheckKeyTime( array $keys ) {
		$rawKeys = [];
		foreach ( $keys as $key ) {
			$rawKeys[$key] = $this->makeSisterKey( $key, self::$TYPE_TIMESTAMP );
		}

		$rawValues = $this->cache->getMulti( $rawKeys );
		$rawValues += array_fill_keys( $rawKeys, false );

		$times = [];
		foreach ( $rawKeys as $key => $rawKey ) {
			$purge = $this->parsePurgeValue( $rawValues[$rawKey] );
			if ( $purge !== false ) {
				$time = $purge[self::$PURGE_TIME];
			} else {
				// Casting assures identical floats for the next getCheckKeyTime() calls
				$now = (string)$this->getCurrentTime();
				$this->cache->add(
					$rawKey,
					$this->makePurgeValue( $now, self::HOLDOFF_TTL ),
					self::$CHECK_KEY_TTL
				);
				$time = (float)$now;
			}

			$times[$key] = $time;
		}

		return $times;
	}

	/**
	 * Purge a "check" key from all datacenters, invalidating keys that use it
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way, and it is impractical to call delete()
	 * on all keys that should be changed. When get() is called on those
	 * keys, the relevant "check" keys must be supplied for this to work.
	 *
	 * The "check" key essentially represents a last-modified time of an entity.
	 * When the key is touched, the timestamp will be updated to the current time.
	 * Keys using the "check" key via get(), getMulti(), or getWithSetCallback() will
	 * be invalidated. This approach is useful if many keys depend on a single entity.
	 *
	 * The timestamp of the "check" key is treated as being HOLDOFF_TTL seconds in the
	 * future by get*() methods in order to avoid race conditions where keys are updated
	 * with stale values (e.g. from a lagged replica DB). A high TTL is set on the "check"
	 * key, making it possible to know the timestamp of the last change to the corresponding
	 * entities in most cases. This might use more cache space than resetCheckKey().
	 *
	 * When a few important keys get a large number of hits, a high cache time is usually
	 * desired as well as "lockTSE" logic. The resetCheckKey() method is less appropriate
	 * in such cases since the "time since expiry" cannot be inferred, causing any get()
	 * after the reset to treat the key as being "hot", resulting in more stale value usage.
	 *
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::resetCheckKey()
	 *
	 * @param string $key Cache key
	 * @param int $holdoff HOLDOFF_TTL or HOLDOFF_TTL_NONE constant
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function touchCheckKey( $key, $holdoff = self::HOLDOFF_TTL ) {
		// Publish the purge to all datacenters
		$ok = $this->relayPurge(
			$this->makeSisterKey( $key, self::$TYPE_TIMESTAMP ),
			self::$CHECK_KEY_TTL,
			$holdoff
		);

		$kClass = $this->determineKeyClassForStats( $key );
		$this->stats->increment( "wanobjectcache.$kClass.ck_touch." . ( $ok ? 'ok' : 'error' ) );

		return $ok;
	}

	/**
	 * Delete a "check" key from all datacenters, invalidating keys that use it
	 *
	 * This is similar to touchCheckKey() in that keys using it via get(), getMulti(),
	 * or getWithSetCallback() will be invalidated. The differences are:
	 *   - a) The "check" key will be deleted from all caches and lazily
	 *        re-initialized when accessed (rather than set everywhere)
	 *   - b) Thus, dependent keys will be known to be stale, but not
	 *        for how long (they are treated as "just" purged), which
	 *        effects any lockTSE logic in getWithSetCallback()
	 *   - c) Since "check" keys are initialized only on the server the key hashes
	 *        to, any temporary ejection of that server will cause the value to be
	 *        seen as purged as a new server will initialize the "check" key.
	 *
	 * The advantage here is that the "check" keys, which have high TTLs, will only
	 * be created when a get*() method actually uses that key. This is better when
	 * a large number of "check" keys are invalided in a short period of time.
	 *
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::touchCheckKey()
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function resetCheckKey( $key ) {
		// Publish the purge to all datacenters
		$ok = $this->relayDelete( $this->makeSisterKey( $key, self::$TYPE_TIMESTAMP ) );

		$kClass = $this->determineKeyClassForStats( $key );
		$this->stats->increment( "wanobjectcache.$kClass.ck_reset." . ( $ok ? 'ok' : 'error' ) );

		return $ok;
	}

	/**
	 * Method to fetch/regenerate a cache key
	 *
	 * On cache miss, the key will be set to the callback result via set()
	 * (unless the callback returns false) and that result will be returned.
	 * The arguments supplied to the callback are:
	 *   - $oldValue: prior cache value or false if none was present
	 *   - &$ttl: alterable reference to the TTL to be assigned to the new value
	 *   - &$setOpts: alterable reference to the set() options to be used with the new value
	 *   - $oldAsOf: generation UNIX timestamp of $oldValue or null if not present (since 1.28)
	 *   - $params: custom field/value map as defined by $cbParams (since 1.35)
	 *
	 * It is strongly recommended to set the 'lag' and 'since' fields to avoid race conditions
	 * that can cause stale values to get stuck at keys. Usually, callbacks ignore the current
	 * value, but it can be used to maintain "most recent X" values that come from time or
	 * sequence based source data, provided that the "as of" id/time is tracked. Note that
	 * preemptive regeneration and $checkKeys can result in a non-false current value.
	 *
	 * Usage of $checkKeys is similar to get() and getMulti(). However, rather than the caller
	 * having to inspect a "current time left" variable (e.g. $curTTL, $curTTLs), a cache
	 * regeneration will automatically be triggered using the callback.
	 *
	 * The $ttl argument and "hotTTR" option (in $opts) use time-dependant randomization
	 * to avoid stampedes. Keys that are slow to regenerate and either heavily used
	 * or subject to explicit (unpredictable) purges, may need additional mechanisms.
	 * The simplest way to avoid stampedes for such keys is to use 'lockTSE' (in $opts).
	 * If explicit purges are needed, also:
	 *   - a) Pass $key into $checkKeys
	 *   - b) Use touchCheckKey( $key ) instead of delete( $key )
	 *
	 * Example usage (typical key):
	 * @code
	 *     $catInfo = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-attributes', $catId ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_MINUTE,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return $dbr->selectRow( ... );
	 *        }
	 *     );
	 * @endcode
	 *
	 * Example usage (key that is expensive and hot):
	 * @code
	 *     $catConfig = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'site-cat-config' ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_DAY,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatConfig::newFromRow( $dbr->selectRow( ... ) );
	 *         },
	 *         [
	 *             // Calling touchCheckKey() on this key invalidates the cache
	 *             'checkKeys' => [ $cache->makeKey( 'site-cat-config' ) ],
	 *             // Try to only let one datacenter thread manage cache updates at a time
	 *             'lockTSE' => 30,
	 *             // Avoid querying cache servers multiple times in a web request
	 *             'pcTTL' => $cache::TTL_PROC_LONG
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (key with dynamic dependencies):
	 * @code
	 *     $catState = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-state', $cat->getId() ),
	 *         // Time-to-live (seconds)
	 *         $cache::TTL_HOUR,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             // Determine new value from the DB
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatState::newFromResults( $dbr->select( ... ) );
	 *         },
	 *         [
	 *              // The "check" keys that represent things the value depends on;
	 *              // Calling touchCheckKey() on any of them invalidates the cache
	 *             'checkKeys' => [
	 *                 $cache->makeKey( 'sustenance-bowls', $cat->getRoomId() ),
	 *                 $cache->makeKey( 'people-present', $cat->getHouseId() ),
	 *                 $cache->makeKey( 'cat-laws', $cat->getCityId() ),
	 *             ]
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (key that is expensive with too many DB dependencies for "check keys"):
	 * @code
	 *     $catToys = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-toys', $catId ),
	 *         // Time-to-live (seconds)
	 *         $cache::TTL_HOUR,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             // Determine new value from the DB
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatToys::newFromResults( $dbr->select( ... ) );
	 *         },
	 *         [
	 *              // Get the highest timestamp of any of the cat's toys
	 *             'touchedCallback' => function ( $value ) use ( $catId ) {
	 *                 $dbr = wfGetDB( DB_REPLICA );
	 *                 $ts = $dbr->selectField( 'cat_toys', 'MAX(ct_touched)', ... );
	 *
	 *                 return wfTimestampOrNull( TS_UNIX, $ts );
	 *             },
	 *             // Avoid DB queries for repeated access
	 *             'pcTTL' => $cache::TTL_PROC_SHORT
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (hot key holding most recent 100 events):
	 * @code
	 *     $lastCatActions = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-last-actions', 100 ),
	 *         // Time-to-live (in seconds)
	 *         10,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             // Start off with the last cached list
	 *             $list = $oldValue ?: [];
	 *             // Fetch the last 100 relevant rows in descending order;
	 *             // only fetch rows newer than $list[0] to reduce scanning
	 *             $rows = iterator_to_array( $dbr->select( ... ) );
	 *             // Merge them and get the new "last 100" rows
	 *             return array_slice( array_merge( $new, $list ), 0, 100 );
	 *        },
	 *        [
	 *             // Try to only let one datacenter thread manage cache updates at a time
	 *             'lockTSE' => 30,
	 *             // Use a magic value when no cache value is ready rather than stampeding
	 *             'busyValue' => 'computing'
	 *        ]
	 *     );
	 * @endcode
	 *
	 * Example usage (key holding an LRU subkey:value map; this can avoid flooding cache with
	 * keys for an unlimited set of (constraint,situation) pairs, thereby avoiding elevated
	 * cache evictions and wasted memory):
	 * @code
	 *     $catSituationTolerabilityCache = $this->cache->getWithSetCallback(
	 *         // Group by constraint ID/hash, cat family ID/hash, or something else useful
	 *         $this->cache->makeKey( 'cat-situation-tolerability-checks', $groupKey ),
	 *         WANObjectCache::TTL_DAY, // rarely used groups should fade away
	 *         // The $scenarioKey format is $constraintId:<ID/hash of $situation>
	 *         function ( $cacheMap ) use ( $scenarioKey, $constraintId, $situation ) {
	 *             $lruCache = MapCacheLRU::newFromArray( $cacheMap ?: [], self::CACHE_SIZE );
	 *             $result = $lruCache->get( $scenarioKey ); // triggers LRU bump if present
	 *             if ( $result === null || $this->isScenarioResultExpired( $result ) ) {
	 *                 $result = $this->checkScenarioTolerability( $constraintId, $situation );
	 *                 $lruCache->set( $scenarioKey, $result, 3 / 8 );
	 *             }
	 *             // Save the new LRU cache map and reset the map's TTL
	 *             return $lruCache->toArray();
	 *         },
	 *         [
	 *             // Once map is > 1 sec old, consider refreshing
	 *             'ageNew' => 1,
	 *             // Update within 5 seconds after "ageNew" given a 1hz cache check rate
	 *             'hotTTR' => 5,
	 *             // Avoid querying cache servers multiple times in a request; this also means
	 *             // that a request can only alter the value of any given constraint key once
	 *             'pcTTL' => WANObjectCache::TTL_PROC_LONG
	 *         ]
	 *     );
	 *     $tolerability = isset( $catSituationTolerabilityCache[$scenarioKey] )
	 *         ? $catSituationTolerabilityCache[$scenarioKey]
	 *         : $this->checkScenarioTolerability( $constraintId, $situation );
	 * @endcode
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::set()
	 *
	 * @param string $key Cache key made from makeKey()/makeGlobalKey()
	 * @param int $ttl Nominal seconds-to-live for newly computed values. Special values are:
	 *   - WANObjectCache::TTL_INDEFINITE: Cache forever (subject to LRU-style evictions)
	 *   - WANObjectCache::TTL_UNCACHEABLE: Do not cache (if the key exists, it is not deleted)
	 * @param callable $callback Value generation function
	 * @param array $opts Options map:
	 *   - checkKeys: List of "check" keys. The key at $key will be seen as stale when either
	 *      touchCheckKey() or resetCheckKey() is called on any of the keys in this list. This
	 *      is useful if thousands or millions of keys depend on the same entity. The entity can
	 *      simply have its "check" key updated whenever the entity is modified.
	 *      Default: [].
	 *   - graceTTL: If the key is invalidated (by "checkKeys"/"touchedCallback") less than this
	 *      many seconds ago, consider reusing the stale value. The odds of a refresh becomes
	 *      more likely over time, becoming certain once the grace period is reached. This can
	 *      reduce traffic spikes when millions of keys are compared to the same "check" key and
	 *      touchCheckKey() or resetCheckKey() is called on that "check" key. This option is not
	 *      useful for avoiding traffic spikes in the case of the key simply expiring on account
	 *      of its TTL (use "lowTTL" instead).
	 *      Default: WANObjectCache::GRACE_TTL_NONE.
	 *   - lockTSE: If the key is tombstoned or invalidated (by "checkKeys"/"touchedCallback")
	 *      less than this many seconds ago, try to have a single thread handle cache regeneration
	 *      at any given time. Other threads will use stale values if possible. If, on miss,
	 *      the time since expiration is low, the assumption is that the key is hot and that a
	 *      stampede is worth avoiding. Note that if the key falls out of cache then concurrent
	 *      threads will all run the callback on cache miss until the value is saved in cache.
	 *      The only stampede protection in that case is from duplicate cache sets when the
	 *      callback is slow and/or yields large values; consider using "busyValue" if such
	 *      stampedes are a problem (e.g. high query load). Note that the higher "lockTSE" is
	 *      set, the higher the worst-case staleness of returned values can be. Also note that
	 *      this option does not by itself handle the case of the key simply expiring on account
	 *      of its TTL, so make sure that "lowTTL" is not disabled when using this option. Avoid
	 *      combining this option with delete() as it can always cause a stampede due to their
	 *      being no stale value available until after a thread completes the callback.
	 *      Use WANObjectCache::TSE_NONE to disable this logic.
	 *      Default: WANObjectCache::TSE_NONE.
	 *   - busyValue: Specify a placeholder value to use when no value exists and another thread
	 *      is currently regenerating it. This assures that cache stampedes cannot happen if the
	 *      value falls out of cache. This also mitigates stampedes when value regeneration
	 *      becomes very slow (greater than $ttl/"lowTTL"). If this is a closure, then it will
	 *      be invoked to get the placeholder when needed.
	 *      Default: null.
	 *   - pcTTL: Process cache the value in this PHP instance for this many seconds. This avoids
	 *      network I/O when a key is read several times. This will not cache when the callback
	 *      returns false, however. Note that any purges will not be seen while process cached;
	 *      since the callback should use replica DBs and they may be lagged or have snapshot
	 *      isolation anyway, this should not typically matter.
	 *      Default: WANObjectCache::TTL_UNCACHEABLE.
	 *   - pcGroup: Process cache group to use instead of the primary one. If set, this must be
	 *      of the format ALPHANUMERIC_NAME:MAX_KEY_SIZE, e.g. "mydata:10". Use this for storing
	 *      large values, small yet numerous values, or some values with a high cost of eviction.
	 *      It is generally preferable to use a class constant when setting this value.
	 *      This has no effect unless pcTTL is used.
	 *      Default: WANObjectCache::PC_PRIMARY.
	 *   - version: Integer version number. This lets callers make breaking changes to the format
	 *      of cached values without causing problems for sites that use non-instantaneous code
	 *      deployments. Old and new code will recognize incompatible versions and purges from
	 *      both old and new code will been seen by each other. When this method encounters an
	 *      incompatibly versioned value at the provided key, a "variant key" will be used for
	 *      reading from and saving to cache. The variant key is specific to the key and version
	 *      number provided to this method. If the variant key value is older than that of the
	 *      provided key, or the provided key is non-existant, then the variant key will be seen
	 *      as non-existant. Therefore, delete() calls invalidate the provided key's variant keys.
	 *      The "checkKeys" and "touchedCallback" options still apply to variant keys as usual.
	 *      Avoid storing class objects, as this reduces compatibility (due to serialization).
	 *      Default: null.
	 *   - minAsOf: Reject values if they were generated before this UNIX timestamp.
	 *      This is useful if the source of a key is suspected of having possibly changed
	 *      recently, and the caller wants any such changes to be reflected.
	 *      Default: WANObjectCache::MIN_TIMESTAMP_NONE.
	 *   - hotTTR: Expected time-till-refresh (TTR) in seconds for keys that average ~1 hit per
	 *      second (e.g. 1Hz). Keys with a hit rate higher than 1Hz will refresh sooner than this
	 *      TTR and vise versa. Such refreshes won't happen until keys are "ageNew" seconds old.
	 *      This uses randomization to avoid triggering cache stampedes. The TTR is useful at
	 *      reducing the impact of missed cache purges, since the effect of a heavily referenced
	 *      key being stale is worse than that of a rarely referenced key. Unlike simply lowering
	 *      $ttl, seldomly used keys are largely unaffected by this option, which makes it
	 *      possible to have a high hit rate for the "long-tail" of less-used keys.
	 *      Default: WANObjectCache::HOT_TTR.
	 *   - lowTTL: Consider pre-emptive updates when the current TTL (seconds) of the key is less
	 *      than this. It becomes more likely over time, becoming certain once the key is expired.
	 *      This helps avoid cache stampedes that might be triggered due to the key expiring.
	 *      Default: WANObjectCache::LOW_TTL.
	 *   - ageNew: Consider popularity refreshes only once a key reaches this age in seconds.
	 *      Default: WANObjectCache::AGE_NEW.
	 *   - staleTTL: Seconds to keep the key around if it is stale. This means that on cache
	 *      miss the callback may get $oldValue/$oldAsOf values for keys that have already been
	 *      expired for this specified time. This is useful if adaptiveTTL() is used on the old
	 *      value's as-of time when it is verified as still being correct.
	 *      Default: WANObjectCache::STALE_TTL_NONE
	 *   - touchedCallback: A callback that takes the current value and returns a UNIX timestamp
	 *      indicating the last time a dynamic dependency changed. Null can be returned if there
	 *      are no relevant dependency changes to check. This can be used to check against things
	 *      like last-modified times of files or DB timestamp fields. This should generally not be
	 *      used for small and easily queried values in a DB if the callback itself ends up doing
	 *      a similarly expensive DB query to check a timestamp. Usages of this option makes the
	 *      most sense for values that are moderately to highly expensive to regenerate and easy
	 *      to query for dependency timestamps. The use of "pcTTL" reduces timestamp queries.
	 *      Default: null.
	 * @param array $cbParams Custom field/value map to pass to the callback (since 1.35)
	 * @codingStandardsIgnoreStart
	 * @phan-param array{checkKeys?:string[],graceTTL?:int,lockTSE?:int,busyValue?:mixed,pcTTL?:int,pcGroup?:string,version?:int,minAsOf?:int,hotTTR?:int,lowTTL?:int,ageNew?:int,staleTTL?:int,touchedCallback?:callable} $opts
	 * @codingStandardsIgnoreEnd
	 * @return mixed Value found or written to the key
	 * @note Options added in 1.28: version, busyValue, hotTTR, ageNew, pcGroup, minAsOf
	 * @note Options added in 1.31: staleTTL, graceTTL
	 * @note Options added in 1.33: touchedCallback
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	final public function getWithSetCallback(
		$key, $ttl, $callback, array $opts = [], array $cbParams = []
	) {
		$version = $opts['version'] ?? null;
		$pcTTL = $opts['pcTTL'] ?? self::TTL_UNCACHEABLE;
		$pCache = ( $pcTTL >= 0 )
			? $this->getProcessCache( $opts['pcGroup'] ?? self::PC_PRIMARY )
			: null;

		// Use the process cache if requested as long as no outer cache callback is running.
		// Nested callback process cache use is not lag-safe with regard to HOLDOFF_TTL since
		// process cached values are more lagged than persistent ones as they are not purged.
		if ( $pCache && $this->callbackDepth == 0 ) {
			$cached = $pCache->get( $this->getProcessCacheKey( $key, $version ), $pcTTL, false );
			if ( $cached !== false ) {
				$this->logger->debug( "getWithSetCallback($key): process cache hit" );
				return $cached;
			}
		}

		$res = $this->fetchOrRegenerate( $key, $ttl, $callback, $opts, $cbParams );
		list( $value, $valueVersion, $curAsOf ) = $res;
		if ( $valueVersion !== $version ) {
			// Current value has a different version; use the variant key for this version.
			// Regenerate the variant value if it is not newer than the main value at $key
			// so that purges to the main key propagate to the variant value.
			$this->logger->debug( "getWithSetCallback($key): using variant key" );
			list( $value ) = $this->fetchOrRegenerate(
				$this->makeGlobalKey( 'WANCache-key-variant', md5( $key ), $version ),
				$ttl,
				$callback,
				[ 'version' => null, 'minAsOf' => $curAsOf ] + $opts,
				$cbParams
			);
		}

		// Update the process cache if enabled
		if ( $pCache && $value !== false ) {
			$pCache->set( $this->getProcessCacheKey( $key, $version ), $value );
		}

		return $value;
	}

	/**
	 * Do the actual I/O for getWithSetCallback() when needed
	 *
	 * @see WANObjectCache::getWithSetCallback()
	 *
	 * @param string $key
	 * @param int $ttl
	 * @param callable $callback
	 * @param array $opts
	 * @param array $cbParams
	 * @return array Ordered list of the following:
	 *   - Cached or regenerated value
	 *   - Cached or regenerated value version number or null if not versioned
	 *   - Timestamp of the current cached value at the key or null if there is no value
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	private function fetchOrRegenerate( $key, $ttl, $callback, array $opts, array $cbParams ) {
		$checkKeys = $opts['checkKeys'] ?? [];
		$graceTTL = $opts['graceTTL'] ?? self::GRACE_TTL_NONE;
		$minAsOf = $opts['minAsOf'] ?? self::MIN_TIMESTAMP_NONE;
		$hotTTR = $opts['hotTTR'] ?? self::HOT_TTR;
		$lowTTL = $opts['lowTTL'] ?? min( self::LOW_TTL, $ttl );
		$ageNew = $opts['ageNew'] ?? self::AGE_NEW;
		$touchedCb = $opts['touchedCallback'] ?? null;
		$initialTime = $this->getCurrentTime();

		$kClass = $this->determineKeyClassForStats( $key );

		// Get the current key value and its metadata
		$curTTL = self::PASS_BY_REF;
		$curInfo = self::PASS_BY_REF;
		$curValue = $this->get( $key, $curTTL, $checkKeys, $curInfo );
		/** @var array $curInfo */
		'@phan-var array $curInfo';
		// Apply any $touchedCb invalidation timestamp to get the "last purge timestamp"
		list( $curTTL, $LPT ) = $this->resolveCTL( $curValue, $curTTL, $curInfo, $touchedCb );
		// Use the cached value if it exists and is not due for synchronous regeneration
		if (
			$this->isValid( $curValue, $curInfo['asOf'], $minAsOf ) &&
			$this->isAliveOrInGracePeriod( $curTTL, $graceTTL )
		) {
			$preemptiveRefresh = (
				$this->worthRefreshExpiring( $curTTL, $lowTTL ) ||
				$this->worthRefreshPopular( $curInfo['asOf'], $ageNew, $hotTTR, $initialTime )
			);
			if ( !$preemptiveRefresh ) {
				$this->stats->increment( "wanobjectcache.$kClass.hit.good" );

				return [ $curValue, $curInfo['version'], $curInfo['asOf'] ];
			} elseif ( $this->scheduleAsyncRefresh( $key, $ttl, $callback, $opts, $cbParams ) ) {
				$this->logger->debug( "fetchOrRegenerate($key): hit with async refresh" );
				$this->stats->increment( "wanobjectcache.$kClass.hit.refresh" );

				return [ $curValue, $curInfo['version'], $curInfo['asOf'] ];
			} else {
				$this->logger->debug( "fetchOrRegenerate($key): hit with sync refresh" );
			}
		}

		// Determine if there is stale or volatile cached value that is still usable
		$isKeyTombstoned = ( $curInfo['tombAsOf'] !== null );
		if ( $isKeyTombstoned ) {
			// Key is write-holed; use the (volatile) interim key as an alternative
			list( $possValue, $possInfo ) = $this->getInterimValue( $key, $minAsOf );
			// Update the "last purge time" since the $touchedCb timestamp depends on $value
			$LPT = $this->resolveTouched( $possValue, $LPT, $touchedCb );
		} else {
			$possValue = $curValue;
			$possInfo = $curInfo;
		}

		// Avoid overhead from callback runs, regeneration locks, and cache sets during
		// hold-off periods for the key by reusing very recently generated cached values
		if (
			$this->isValid( $possValue, $possInfo['asOf'], $minAsOf, $LPT ) &&
			$this->isVolatileValueAgeNegligible( $initialTime - $possInfo['asOf'] )
		) {
			$this->logger->debug( "fetchOrRegenerate($key): volatile hit" );
			$this->stats->increment( "wanobjectcache.$kClass.hit.volatile" );

			return [ $possValue, $possInfo['version'], $curInfo['asOf'] ];
		}

		$lockTSE = $opts['lockTSE'] ?? self::TSE_NONE;
		$busyValue = $opts['busyValue'] ?? null;
		$staleTTL = $opts['staleTTL'] ?? self::STALE_TTL_NONE;
		$version = $opts['version'] ?? null;

		// Determine whether one thread per datacenter should handle regeneration at a time
		$useRegenerationLock =
			// Note that since tombstones no-op set(), $lockTSE and $curTTL cannot be used to
			// deduce the key hotness because |$curTTL| will always keep increasing until the
			// tombstone expires or is overwritten by a new tombstone. Also, even if $lockTSE
			// is not set, constant regeneration of a key for the tombstone lifetime might be
			// very expensive. Assume tombstoned keys are possibly hot in order to reduce
			// the risk of high regeneration load after the delete() method is called.
			$isKeyTombstoned ||
			// Assume a key is hot if requested soon ($lockTSE seconds) after invalidation.
			// This avoids stampedes when timestamps from $checkKeys/$touchedCb bump.
			( $curTTL !== null && $curTTL <= 0 && abs( $curTTL ) <= $lockTSE ) ||
			// Assume a key is hot if there is no value and a busy fallback is given.
			// This avoids stampedes on eviction or preemptive regeneration taking too long.
			( $busyValue !== null && $possValue === false );

		// If a regeneration lock is required, threads that do not get the lock will try to use
		// the stale value, the interim value, or the $busyValue placeholder, in that order. If
		// none of those are set then all threads will bypass the lock and regenerate the value.
		$hasLock = $useRegenerationLock && $this->claimStampedeLock( $key );
		if ( $useRegenerationLock && !$hasLock ) {
			if ( $this->isValid( $possValue, $possInfo['asOf'], $minAsOf ) ) {
				$this->logger->debug( "fetchOrRegenerate($key): returning stale value" );
				$this->stats->increment( "wanobjectcache.$kClass.hit.stale" );

				return [ $possValue, $possInfo['version'], $curInfo['asOf'] ];
			} elseif ( $busyValue !== null ) {
				$miss = is_infinite( $minAsOf ) ? 'renew' : 'miss';
				$this->logger->debug( "fetchOrRegenerate($key): busy $miss" );
				$this->stats->increment( "wanobjectcache.$kClass.$miss.busy" );

				return [ $this->resolveBusyValue( $busyValue ), $version, $curInfo['asOf'] ];
			}
		}

		// Generate the new value given any prior value with a matching version
		$setOpts = [];
		$preCallbackTime = $this->getCurrentTime();
		++$this->callbackDepth;
		try {
			$value = $callback(
				( $curInfo['version'] === $version ) ? $curValue : false,
				$ttl,
				$setOpts,
				( $curInfo['version'] === $version ) ? $curInfo['asOf'] : null,
				$cbParams
			);
		} finally {
			--$this->callbackDepth;
		}
		$postCallbackTime = $this->getCurrentTime();

		// How long it took to fetch, validate, and generate the value
		$elapsed = max( $postCallbackTime - $initialTime, 0.0 );

		// Attempt to save the newly generated value if applicable
		if (
			// Callback yielded a cacheable value
			( $value !== false && $ttl >= 0 ) &&
			// Current thread was not raced out of a regeneration lock or key is tombstoned
			( !$useRegenerationLock || $hasLock || $isKeyTombstoned ) &&
			// Key does not appear to be undergoing a set() stampede
			$this->checkAndSetCooloff( $key, $kClass, $value, $elapsed, $hasLock )
		) {
			// How long it took to generate the value
			$walltime = max( $postCallbackTime - $preCallbackTime, 0.0 );
			$this->stats->timing( "wanobjectcache.$kClass.regen_walltime", 1e3 * $walltime );
			// If the key is write-holed then use the (volatile) interim key as an alternative
			if ( $isKeyTombstoned ) {
				$this->setInterimValue( $key, $value, $lockTSE, $version, $walltime );
			} else {
				$finalSetOpts = [
					// @phan-suppress-next-line PhanUselessBinaryAddRight
					'since' => $setOpts['since'] ?? $preCallbackTime,
					'version' => $version,
					'staleTTL' => $staleTTL,
					'lockTSE' => $lockTSE, // informs lag vs performance trade-offs
					'creating' => ( $curValue === false ), // optimization
					'walltime' => $walltime
				] + $setOpts;
				$this->set( $key, $value, $ttl, $finalSetOpts );
			}
		}

		$this->yieldStampedeLock( $key, $hasLock );

		$miss = is_infinite( $minAsOf ) ? 'renew' : 'miss';
		$this->logger->debug( "fetchOrRegenerate($key): $miss, new value computed" );
		$this->stats->increment( "wanobjectcache.$kClass.$miss.compute" );

		return [ $value, $version, $curInfo['asOf'] ];
	}

	/**
	 * @param string $key
	 * @return bool Success
	 */
	private function claimStampedeLock( $key ) {
		// Note that locking is not bypassed due to I/O errors; this avoids stampedes
		return $this->cache->add(
			$this->makeSisterKey( $key, self::$TYPE_MUTEX ),
			1,
			self::$LOCK_TTL
		);
	}

	/**
	 * @param string $key
	 * @param bool $hasLock
	 */
	private function yieldStampedeLock( $key, $hasLock ) {
		if ( $hasLock ) {
			// The backend might be a mcrouter proxy set to broadcast DELETE to *all* the local
			// datacenter cache servers via OperationSelectorRoute (for increased consistency).
			// Since that would be excessive for these locks, use TOUCH to expire the key.
			$this->cache->changeTTL(
				$this->makeSisterKey( $key, self::$TYPE_MUTEX ),
				$this->getCurrentTime() - 60
			);
		}
	}

	/**
	 * Get cache keys that should be collocated with their corresponding base keys
	 *
	 * @param string[] $baseKeys Cache keys made from makeKey()/makeGlobalKey()
	 * @param string $type Consistent hashing agnostic suffix character matching [a-zA-Z]
	 * @return string[] List of cache keys
	 */
	private function makeSisterKeys( array $baseKeys, $type ) {
		$keys = [];
		foreach ( $baseKeys as $baseKey ) {
			$keys[] = $this->makeSisterKey( $baseKey, $type );
		}

		return $keys;
	}

	/**
	 * Get a cache key that should be collocated with a base key
	 *
	 * @param string $baseKey Cache key made from makeKey()/makeGlobalKey()
	 * @param string $typeChar Consistent hashing agnostic suffix character matching [a-zA-Z]
	 * @return string Cache key
	 */
	private function makeSisterKey( $baseKey, $typeChar ) {
		if ( $this->coalesceKeys === 'non-global' ) {
			$useColocationScheme = ( strncmp( $baseKey, "global:", 7 ) !== 0 );
		} else {
			$useColocationScheme = ( $this->coalesceKeys === true );
		}

		if ( !$useColocationScheme ) {
			// Old key style: "WANCache:<character>:<base key>"
			$fullKey = 'WANCache:' . $typeChar . ':' . $baseKey;
		} elseif ( $this->coalesceScheme === self::SCHEME_HASH_STOP ) {
			// Key style: "WANCache:<base key>|#|<character>"
			$fullKey = 'WANCache:' . $baseKey . '|#|' . $typeChar;
		} else {
			// Key style: "WANCache:{<base key>}:<character>"
			$fullKey = 'WANCache:{' . $baseKey . '}:' . $typeChar;
		}

		return $fullKey;
	}

	/**
	 * @param float $age Age of volatile/interim key in seconds
	 * @return bool Whether the age of a volatile value is negligible
	 */
	private function isVolatileValueAgeNegligible( $age ) {
		return ( $age < mt_rand( self::$RECENT_SET_LOW_MS, self::$RECENT_SET_HIGH_MS ) / 1e3 );
	}

	/**
	 * Check whether set() is rate-limited to avoid concurrent I/O spikes
	 *
	 * This mitigates problems caused by popular keys suddenly becoming unavailable due to
	 * unexpected evictions or cache server outages. These cases are not handled by the usual
	 * preemptive refresh logic.
	 *
	 * With a typical scale-out infrastructure, CPU and query load from getWithSetCallback()
	 * invocations is distributed among appservers and replica DBs, but cache operations for
	 * a given key route to a single cache server (e.g. striped consistent hashing). A set()
	 * stampede to a key can saturate the network link to its cache server. The intensity of
	 * the problem is proportionate to the value size and access rate. The duration of the
	 * problem is proportionate to value regeneration time.
	 *
	 * @param string $key
	 * @param string $kClass
	 * @param mixed $value The regenerated value
	 * @param float $elapsed Seconds spent fetching, validating, and regenerating the value
	 * @param bool $hasLock Whether this thread has an exclusive regeneration lock
	 * @return bool Whether it is OK to proceed with a key set operation
	 */
	private function checkAndSetCooloff( $key, $kClass, $value, $elapsed, $hasLock ) {
		$valueKey = $this->makeSisterKey( $key, self::$TYPE_VALUE );
		list( $estimatedSize ) = $this->cache->setNewPreparedValues( [ $valueKey => $value ] );

		if ( !$hasLock ) {
			// Suppose that this cache key is very popular (KEY_HIGH_QPS reads/second).
			// After eviction, there will be cache misses until it gets regenerated and saved.
			// If the time window when the key is missing lasts less than one second, then the
			// number of misses will not reach KEY_HIGH_QPS. This window largely corresponds to
			// the key regeneration time. Estimate the count/rate of cache misses, e.g.:
			//  - 100 QPS, 20ms regeneration => ~2 misses (< 1s)
			//  - 100 QPS, 100ms regeneration => ~10 misses (< 1s)
			//  - 100 QPS, 3000ms regeneration => ~300 misses (100/s for 3s)
			$missesPerSecForHighQPS = ( min( $elapsed, 1 ) * $this->keyHighQps );

			// Determine whether there is enough I/O stampede risk to justify throttling set().
			// Estimate unthrottled set() overhead, as bps, from miss count/rate and value size,
			// comparing it to the per-key uplink bps limit (KEY_HIGH_UPLINK_BPS), e.g.:
			//  - 2 misses (< 1s), 10KB value, 1250000 bps limit => 160000 bits (low risk)
			//  - 2 misses (< 1s), 100KB value, 1250000 bps limit => 1600000 bits (high risk)
			//  - 10 misses (< 1s), 10KB value, 1250000 bps limit => 800000 bits (low risk)
			//  - 10 misses (< 1s), 100KB value, 1250000 bps limit => 8000000 bits (high risk)
			//  - 300 misses (100/s), 1KB value, 1250000 bps limit => 800000 bps (low risk)
			//  - 300 misses (100/s), 10KB value, 1250000 bps limit => 8000000 bps (high risk)
			//  - 300 misses (100/s), 100KB value, 1250000 bps limit => 80000000 bps (high risk)
			if ( ( $missesPerSecForHighQPS * $estimatedSize ) >= $this->keyHighUplinkBps ) {
				$this->cache->clearLastError();
				if (
					!$this->cache->add(
						$this->makeSisterKey( $key, self::$TYPE_COOLOFF ),
						1,
						self::$COOLOFF_TTL
					) &&
					// Don't treat failures due to I/O errors as the key being in cooloff
					$this->cache->getLastError() === BagOStuff::ERR_NONE
				) {
					$this->stats->increment( "wanobjectcache.$kClass.cooloff_bounce" );

					return false;
				}
			}
		}

		// Corresponding metrics for cache writes that actually get sent over the write
		$this->stats->timing( "wanobjectcache.$kClass.regen_set_delay", 1e3 * $elapsed );
		$this->stats->updateCount( "wanobjectcache.$kClass.regen_set_bytes", $estimatedSize );

		return true;
	}

	/**
	 * @param mixed $value
	 * @param float|null $curTTL
	 * @param array $curInfo
	 * @param callable|null $touchedCallback
	 * @return array (current time left or null, UNIX timestamp of last purge or null)
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	private function resolveCTL( $value, $curTTL, $curInfo, $touchedCallback ) {
		if ( $touchedCallback === null || $value === false ) {
			return [ $curTTL, max( $curInfo['tombAsOf'], $curInfo['lastCKPurge'] ) ];
		}

		$touched = $touchedCallback( $value );
		if ( $touched !== null && $touched >= $curInfo['asOf'] ) {
			$curTTL = min( $curTTL, self::$TINY_NEGATIVE, $curInfo['asOf'] - $touched );
		}

		return [ $curTTL, max( $curInfo['tombAsOf'], $curInfo['lastCKPurge'], $touched ) ];
	}

	/**
	 * @param mixed $value
	 * @param float|null $lastPurge
	 * @param callable|null $touchedCallback
	 * @return float|null UNIX timestamp of last purge or null
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	private function resolveTouched( $value, $lastPurge, $touchedCallback ) {
		return ( $touchedCallback === null || $value === false )
			? $lastPurge // nothing to derive the "touched timestamp" from
			: max( $touchedCallback( $value ), $lastPurge );
	}

	/**
	 * @param string $key
	 * @param float $minAsOf Minimum acceptable "as of" timestamp
	 * @return array (cached value or false, cache key metadata map)
	 */
	private function getInterimValue( $key, $minAsOf ) {
		$now = $this->getCurrentTime();

		if ( $this->useInterimHoldOffCaching ) {
			$wrapped = $this->cache->get(
				$this->makeSisterKey( $key, self::$TYPE_INTERIM )
			);

			list( $value, $keyInfo ) = $this->unwrap( $wrapped, $now );
			if ( $this->isValid( $value, $keyInfo['asOf'], $minAsOf ) ) {
				return [ $value, $keyInfo ];
			}
		}

		return $this->unwrap( false, $now );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param int|null $version Value version number
	 * @param float $walltime How long it took to generate the value in seconds
	 */
	private function setInterimValue( $key, $value, $ttl, $version, $walltime ) {
		$ttl = max( self::$INTERIM_KEY_TTL, (int)$ttl );

		$wrapped = $this->wrap( $value, $ttl, $version, $this->getCurrentTime(), $walltime );
		$this->cache->merge(
			$this->makeSisterKey( $key, self::$TYPE_INTERIM ),
			function () use ( $wrapped ) {
				return $wrapped;
			},
			$ttl,
			1
		);
	}

	/**
	 * @param mixed $busyValue
	 * @return mixed
	 */
	private function resolveBusyValue( $busyValue ) {
		return ( $busyValue instanceof Closure ) ? $busyValue() : $busyValue;
	}

	/**
	 * Method to fetch multiple cache keys at once with regeneration
	 *
	 * This works the same as getWithSetCallback() except:
	 *   - a) The $keys argument must be the result of WANObjectCache::makeMultiKeys()
	 *   - b) The $callback argument must be a callback that takes the following arguments:
	 *         - $id: ID of the entity to query
	 *         - $oldValue: prior cache value or false if none was present
	 *         - &$ttl: reference to the TTL to be assigned to the new value (alterable)
	 *         - &$setOpts: reference to the new value set() options (alterable)
	 *         - $oldAsOf: generation UNIX timestamp of $oldValue or null if not present
	 *   - c) The return value is a map of (cache key => value) in the order of $keyedIds
	 *
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::getMultiWithUnionSetCallback()
	 *
	 * Example usage:
	 * @code
	 *     $rows = $cache->getMultiWithSetCallback(
	 *         // Map of cache keys to entity IDs
	 *         $cache->makeMultiKeys(
	 *             $this->fileVersionIds(),
	 *             function ( $id ) use ( $cache ) {
	 *                 return $cache->makeKey( 'file-version', $id );
	 *             }
	 *         ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_DAY,
	 *         // Function that derives the new key value
	 *         function ( $id, $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             // Load the row for this file
	 *             $queryInfo = File::getQueryInfo();
	 *             $row = $dbr->selectRow(
	 *                 $queryInfo['tables'],
	 *                 $queryInfo['fields'],
	 *                 [ 'id' => $id ],
	 *                 __METHOD__,
	 *                 [],
	 *                 $queryInfo['joins']
	 *             );
	 *
	 *             return $row ? (array)$row : false;
	 *         },
	 *         [
	 *             // Process cache for 30 seconds
	 *             'pcTTL' => 30,
	 *             // Use a dedicated 500 item cache (initialized on-the-fly)
	 *             'pcGroup' => 'file-versions:500'
	 *         ]
	 *     );
	 *     $files = array_map( [ __CLASS__, 'newFromRow' ], $rows );
	 * @endcode
	 *
	 * @param ArrayIterator $keyedIds Result of WANObjectCache::makeMultiKeys()
	 * @param int $ttl Seconds to live for key updates
	 * @param callable $callback Callback the yields entity regeneration callbacks
	 * @param array $opts Options map
	 * @return mixed[] Map of (cache key => value) in the same order as $keyedIds
	 * @since 1.28
	 */
	final public function getMultiWithSetCallback(
		ArrayIterator $keyedIds, $ttl, callable $callback, array $opts = []
	) {
		// Load required keys into process cache in one go
		$this->warmupCache = $this->getRawKeysForWarmup(
			$this->getNonProcessCachedMultiKeys( $keyedIds, $opts ),
			$opts['checkKeys'] ?? []
		);
		$this->warmupKeyMisses = 0;

		// The required callback signature includes $id as the first argument for convenience
		// to distinguish different items. To reuse the code in getWithSetCallback(), wrap the
		// callback with a proxy callback that has the standard getWithSetCallback() signature.
		// This is defined only once per batch to avoid closure creation overhead.
		$proxyCb = function ( $oldValue, &$ttl, &$setOpts, $oldAsOf, $params ) use ( $callback ) {
			return $callback( $params['id'], $oldValue, $ttl, $setOpts, $oldAsOf );
		};

		$values = [];
		foreach ( $keyedIds as $key => $id ) { // preserve order
			$values[$key] = $this->getWithSetCallback(
				$key,
				$ttl,
				$proxyCb,
				$opts,
				[ 'id' => $id ]
			);
		}

		$this->warmupCache = [];

		return $values;
	}

	/**
	 * Method to fetch/regenerate multiple cache keys at once
	 *
	 * This works the same as getWithSetCallback() except:
	 *   - a) The $keys argument expects the result of WANObjectCache::makeMultiKeys()
	 *   - b) The $callback argument expects a callback returning a map of (ID => new value)
	 *        for all entity IDs in $ids and it takes the following arguments:
	 *          - $ids: list of entity IDs that require cache regeneration
	 *          - &$ttls: reference to the (entity ID => new TTL) map (alterable)
	 *          - &$setOpts: reference to the new value set() options (alterable)
	 *   - c) The return value is a map of (cache key => value) in the order of $keyedIds
	 *   - d) The "lockTSE" and "busyValue" options are ignored
	 *
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::getMultiWithSetCallback()
	 *
	 * Example usage:
	 * @code
	 *     $rows = $cache->getMultiWithUnionSetCallback(
	 *         // Map of cache keys to entity IDs
	 *         $cache->makeMultiKeys(
	 *             $this->fileVersionIds(),
	 *             function ( $id ) use ( $cache ) {
	 *                 return $cache->makeKey( 'file-version', $id );
	 *             }
	 *         ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_DAY,
	 *         // Function that derives the new key value
	 *         function ( array $ids, array &$ttls, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_REPLICA );
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             // Load the rows for these files
	 *             $rows = [];
	 *             $queryInfo = File::getQueryInfo();
	 *             $res = $dbr->select(
	 *                 $queryInfo['tables'],
	 *                 $queryInfo['fields'],
	 *                 [ 'id' => $ids ],
	 *                 __METHOD__,
	 *                 [],
	 *                 $queryInfo['joins']
	 *             );
	 *             foreach ( $res as $row ) {
	 *                 $rows[$row->id] = $row;
	 *                 $mtime = wfTimestamp( TS_UNIX, $row->timestamp );
	 *                 $ttls[$row->id] = $this->adaptiveTTL( $mtime, $ttls[$row->id] );
	 *             }
	 *
	 *             return $rows;
	 *         },
	 *         ]
	 *     );
	 *     $files = array_map( [ __CLASS__, 'newFromRow' ], $rows );
	 * @endcode
	 *
	 * @param ArrayIterator $keyedIds Result of WANObjectCache::makeMultiKeys()
	 * @param int $ttl Seconds to live for key updates
	 * @param callable $callback Callback the yields entity regeneration callbacks
	 * @param array $opts Options map
	 * @return mixed[] Map of (cache key => value) in the same order as $keyedIds
	 * @since 1.30
	 */
	final public function getMultiWithUnionSetCallback(
		ArrayIterator $keyedIds, $ttl, callable $callback, array $opts = []
	) {
		$checkKeys = $opts['checkKeys'] ?? [];
		unset( $opts['lockTSE'] ); // incompatible
		unset( $opts['busyValue'] ); // incompatible

		// Load required keys into process cache in one go
		$keysByIdGet = $this->getNonProcessCachedMultiKeys( $keyedIds, $opts );
		$this->warmupCache = $this->getRawKeysForWarmup( $keysByIdGet, $checkKeys );
		$this->warmupKeyMisses = 0;

		// IDs of entities known to be in need of regeneration
		$idsRegen = [];

		// Find out which keys are missing/deleted/stale
		$curTTLs = [];
		$asOfs = [];
		$curByKey = $this->getMulti( $keysByIdGet, $curTTLs, $checkKeys, $asOfs );
		foreach ( $keysByIdGet as $id => $key ) {
			if ( !array_key_exists( $key, $curByKey ) || $curTTLs[$key] < 0 ) {
				$idsRegen[] = $id;
			}
		}

		// Run the callback to populate the regeneration value map for all required IDs
		$newSetOpts = [];
		$newTTLsById = array_fill_keys( $idsRegen, $ttl );
		$newValsById = $idsRegen ? $callback( $idsRegen, $newTTLsById, $newSetOpts ) : [];

		// The required callback signature includes $id as the first argument for convenience
		// to distinguish different items. To reuse the code in getWithSetCallback(), wrap the
		// callback with a proxy callback that has the standard getWithSetCallback() signature.
		// This is defined only once per batch to avoid closure creation overhead.
		$proxyCb = function ( $oldValue, &$ttl, &$setOpts, $oldAsOf, $params )
			use ( $callback, $newValsById, $newTTLsById, $newSetOpts )
		{
			$id = $params['id'];

			if ( array_key_exists( $id, $newValsById ) ) {
				// Value was already regerated as expected, so use the value in $newValsById
				$newValue = $newValsById[$id];
				$ttl = $newTTLsById[$id];
				$setOpts = $newSetOpts;
			} else {
				// Pre-emptive/popularity refresh and version mismatch cases are not detected
				// above and thus $newValsById has no entry. Run $callback on this single entity.
				$ttls = [ $id => $ttl ];
				$newValue = $callback( [ $id ], $ttls, $setOpts )[$id];
				$ttl = $ttls[$id];
			}

			return $newValue;
		};

		// Run the cache-aside logic using warmupCache instead of persistent cache queries
		$values = [];
		foreach ( $keyedIds as $key => $id ) { // preserve order
			$values[$key] = $this->getWithSetCallback(
				$key,
				$ttl,
				$proxyCb,
				$opts,
				[ 'id' => $id ]
			);
		}

		$this->warmupCache = [];

		return $values;
	}

	/**
	 * Set a key to soon expire in the local cluster if it pre-dates $purgeTimestamp
	 *
	 * This sets stale keys' time-to-live at HOLDOFF_TTL seconds, which both avoids
	 * broadcasting in mcrouter setups and also avoids races with new tombstones.
	 *
	 * @param string $key Cache key
	 * @param int $purgeTimestamp UNIX timestamp of purge
	 * @param bool &$isStale Whether the key is stale
	 * @return bool Success
	 * @since 1.28
	 */
	final public function reap( $key, $purgeTimestamp, &$isStale = false ) {
		$minAsOf = $purgeTimestamp + self::HOLDOFF_TTL;
		$wrapped = $this->cache->get( $this->makeSisterKey( $key, self::$TYPE_VALUE ) );
		if ( is_array( $wrapped ) && $wrapped[self::$FLD_TIME] < $minAsOf ) {
			$isStale = true;
			$this->logger->warning( "Reaping stale value key '$key'." );
			$ttlReap = self::HOLDOFF_TTL; // avoids races with tombstone creation
			$ok = $this->cache->changeTTL(
				$this->makeSisterKey( $key, self::$TYPE_VALUE ),
				$ttlReap
			);
			if ( !$ok ) {
				$this->logger->error( "Could not complete reap of key '$key'." );
			}

			return $ok;
		}

		$isStale = false;

		return true;
	}

	/**
	 * Set a "check" key to soon expire in the local cluster if it pre-dates $purgeTimestamp
	 *
	 * @param string $key Cache key
	 * @param int $purgeTimestamp UNIX timestamp of purge
	 * @param bool &$isStale Whether the key is stale
	 * @return bool Success
	 * @since 1.28
	 */
	final public function reapCheckKey( $key, $purgeTimestamp, &$isStale = false ) {
		$purge = $this->parsePurgeValue(
			$this->cache->get( $this->makeSisterKey( $key, self::$TYPE_TIMESTAMP ) )
		);
		if ( $purge && $purge[self::$PURGE_TIME] < $purgeTimestamp ) {
			$isStale = true;
			$this->logger->warning( "Reaping stale check key '$key'." );
			$ok = $this->cache->changeTTL(
				$this->makeSisterKey( $key, self::$TYPE_TIMESTAMP ),
				self::TTL_SECOND
			);
			if ( !$ok ) {
				$this->logger->error( "Could not complete reap of check key '$key'." );
			}

			return $ok;
		}

		$isStale = false;

		return false;
	}

	/**
	 * @see BagOStuff::makeKey()
	 * @param string $class Key class
	 * @param string|int ...$components Key components (starting with a key collection name)
	 * @return string Colon-delimited list of $keyspace followed by escaped components
	 * @since 1.27
	 */
	public function makeKey( $class, ...$components ) {
		return $this->cache->makeKey( ...func_get_args() );
	}

	/**
	 * @see BagOStuff::makeGlobalKey()
	 * @param string $class Key class
	 * @param string|int ...$components Key components (starting with a key collection name)
	 * @return string Colon-delimited list of $keyspace followed by escaped components
	 * @since 1.27
	 */
	public function makeGlobalKey( $class, ...$components ) {
		return $this->cache->makeGlobalKey( ...func_get_args() );
	}

	/**
	 * Hash a possibly long string into a suitable component for makeKey()/makeGlobalKey()
	 *
	 * @param string $component A raw component used in building a cache key
	 * @return string 64 character HMAC using a stable secret for public collision resistance
	 * @since 1.34
	 */
	public function hash256( $component ) {
		return hash_hmac( 'sha256', $component, $this->secret );
	}

	/**
	 * Get an iterator of (cache key => entity ID) for a list of entity IDs
	 *
	 * The callback takes an ID string and returns a key via makeKey()/makeGlobalKey().
	 * There should be no network nor filesystem I/O used in the callback. The entity
	 * ID/key mapping must be 1:1 or an exception will be thrown. If hashing is needed,
	 * then use the hash256() method.
	 *
	 * Example usage for the default keyspace:
	 * @code
	 *     $keyedIds = $cache->makeMultiKeys(
	 *         $modules,
	 *         function ( $module ) use ( $cache ) {
	 *             return $cache->makeKey( 'module-info', $module );
	 *         }
	 *     );
	 * @endcode
	 *
	 * Example usage for mixed default and global keyspace:
	 * @code
	 *     $keyedIds = $cache->makeMultiKeys(
	 *         $filters,
	 *         function ( $filter ) use ( $cache ) {
	 *             return ( strpos( $filter, 'central:' ) === 0 )
	 *                 ? $cache->makeGlobalKey( 'regex-filter', $filter )
	 *                 : $cache->makeKey( 'regex-filter', $filter )
	 *         }
	 *     );
	 * @endcode
	 *
	 * Example usage with hashing:
	 * @code
	 *     $keyedIds = $cache->makeMultiKeys(
	 *         $urls,
	 *         function ( $url ) use ( $cache ) {
	 *             return $cache->makeKey( 'url-info', $cache->hash256( $url ) );
	 *         }
	 *     );
	 * @endcode
	 *
	 * @see WANObjectCache::makeKey()
	 * @see WANObjectCache::makeGlobalKey()
	 * @see WANObjectCache::hash256()
	 *
	 * @param string[]|int[] $ids List of entity IDs
	 * @param callable $keyCallback Function returning makeKey()/makeGlobalKey() on the input ID
	 * @return ArrayIterator Iterator of (cache key => ID); order of $ids is preserved
	 * @throws UnexpectedValueException
	 * @since 1.28
	 */
	final public function makeMultiKeys( array $ids, $keyCallback ) {
		$idByKey = [];
		foreach ( $ids as $id ) {
			// Discourage triggering of automatic makeKey() hashing in some backends
			if ( strlen( $id ) > 64 ) {
				$this->logger->warning( __METHOD__ . ": long ID '$id'; use hash256()" );
			}
			$key = $keyCallback( $id, $this );
			// Edge case: ignore key collisions due to duplicate $ids like "42" and 42
			if ( !isset( $idByKey[$key] ) ) {
				$idByKey[$key] = $id;
			} elseif ( (string)$id !== (string)$idByKey[$key] ) {
				throw new UnexpectedValueException(
					"Cache key collision; IDs ('$id','{$idByKey[$key]}') map to '$key'"
				);
			}
		}

		return new ArrayIterator( $idByKey );
	}

	/**
	 * Get an (ID => value) map from (i) a non-unique list of entity IDs, and (ii) the list
	 * of corresponding entity values by first appearance of each ID in the entity ID list
	 *
	 * For use with getMultiWithSetCallback() and getMultiWithUnionSetCallback().
	 *
	 * *Only* use this method if the entity ID/key mapping is trivially 1:1 without exception.
	 * Key generation method must utitilize the *full* entity ID in the key (not a hash of it).
	 *
	 * Example usage:
	 * @code
	 *     $poems = $cache->getMultiWithSetCallback(
	 *         $cache->makeMultiKeys(
	 *             $uuids,
	 *             function ( $uuid ) use ( $cache ) {
	 *                 return $cache->makeKey( 'poem', $uuid );
	 *             }
	 *         ),
	 *         $cache::TTL_DAY,
	 *         function ( $uuid ) use ( $url ) {
	 *             return $this->http->run( [ 'method' => 'GET', 'url' => "$url/$uuid" ] );
	 *         }
	 *     );
	 *     $poemsByUUID = $cache->multiRemap( $uuids, $poems );
	 * @endcode
	 *
	 * @see WANObjectCache::makeMultiKeys()
	 * @see WANObjectCache::getMultiWithSetCallback()
	 * @see WANObjectCache::getMultiWithUnionSetCallback()
	 *
	 * @param string[]|int[] $ids Entity ID list makeMultiKeys()
	 * @param mixed[] $res Result of getMultiWithSetCallback()/getMultiWithUnionSetCallback()
	 * @return mixed[] Map of (ID => value); order of $ids is preserved
	 * @since 1.34
	 */
	final public function multiRemap( array $ids, array $res ) {
		if ( count( $ids ) !== count( $res ) ) {
			// If makeMultiKeys() is called on a list of non-unique IDs, then the resulting
			// ArrayIterator will have less entries due to "first appearance" de-duplication
			$ids = array_keys( array_flip( $ids ) );
			if ( count( $ids ) !== count( $res ) ) {
				throw new UnexpectedValueException( "Multi-key result does not match ID list" );
			}
		}

		return array_combine( $ids, $res );
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* class constant for the "last error" registry
	 */
	final public function getLastError() {
		$code = $this->cache->getLastError();
		switch ( $code ) {
			case BagOStuff::ERR_NONE:
				return self::ERR_NONE;
			case BagOStuff::ERR_NO_RESPONSE:
				return self::ERR_NO_RESPONSE;
			case BagOStuff::ERR_UNREACHABLE:
				return self::ERR_UNREACHABLE;
			default:
				return self::ERR_UNEXPECTED;
		}
	}

	/**
	 * Clear the "last error" registry
	 */
	final public function clearLastError() {
		$this->cache->clearLastError();
	}

	/**
	 * Clear the in-process caches; useful for testing
	 *
	 * @since 1.27
	 */
	public function clearProcessCache() {
		$this->processCaches = [];
	}

	/**
	 * Enable or disable the use of brief caching for tombstoned keys
	 *
	 * When a key is purged via delete(), there normally is a period where caching
	 * is hold-off limited to an extremely short time. This method will disable that
	 * caching, forcing the callback to run for any of:
	 *   - WANObjectCache::getWithSetCallback()
	 *   - WANObjectCache::getMultiWithSetCallback()
	 *   - WANObjectCache::getMultiWithUnionSetCallback()
	 *
	 * This is useful when both:
	 *   - a) the database used by the callback is known to be up-to-date enough
	 *        for some particular purpose (e.g. replica DB has applied transaction X)
	 *   - b) the caller needs to exploit that fact, and therefore needs to avoid the
	 *        use of inherently volatile and possibly stale interim keys
	 *
	 * @see WANObjectCache::delete()
	 * @param bool $enabled Whether to enable interim caching
	 * @since 1.31
	 */
	final public function useInterimHoldOffCaching( $enabled ) {
		$this->useInterimHoldOffCaching = $enabled;
	}

	/**
	 * @param int $flag ATTR_* class constant
	 * @return int QOS_* class constant
	 * @since 1.28
	 */
	public function getQoS( $flag ) {
		return $this->cache->getQoS( $flag );
	}

	/**
	 * Get a TTL that is higher for objects that have not changed recently
	 *
	 * This is useful for keys that get explicit purges and DB or purge relay
	 * lag is a potential concern (especially how it interacts with CDN cache)
	 *
	 * Example usage:
	 * @code
	 *     // Last-modified time of page
	 *     $mtime = wfTimestamp( TS_UNIX, $page->getTimestamp() );
	 *     // Get adjusted TTL. If $mtime is 3600 seconds ago and $minTTL/$factor left at
	 *     // defaults, then $ttl is 3600 * .2 = 720. If $minTTL was greater than 720, then
	 *     // $ttl would be $minTTL. If $maxTTL was smaller than 720, $ttl would be $maxTTL.
	 *     $ttl = $cache->adaptiveTTL( $mtime, $cache::TTL_DAY );
	 * @endcode
	 *
	 * Another use case is when there are no applicable "last modified" fields in the DB,
	 * and there are too many dependencies for explicit purges to be viable, and the rate of
	 * change to relevant content is unstable, and it is highly valued to have the cached value
	 * be as up-to-date as possible.
	 *
	 * Example usage:
	 * @code
	 *     $query = "<some complex query>";
	 *     $idListFromComplexQuery = $cache->getWithSetCallback(
	 *         $cache->makeKey( 'complex-graph-query', $hashOfQuery ),
	 *         GraphQueryClass::STARTING_TTL,
	 *         function ( $oldValue, &$ttl, array &$setOpts, $oldAsOf ) use ( $query, $cache ) {
	 *             $gdb = $this->getReplicaGraphDbConnection();
	 *             // Account for any snapshot/replica DB lag
	 *             $setOpts += GraphDatabase::getCacheSetOptions( $gdb );
	 *
	 *             $newList = iterator_to_array( $gdb->query( $query ) );
	 *             sort( $newList, SORT_NUMERIC ); // normalize
	 *
	 *             $minTTL = GraphQueryClass::MIN_TTL;
	 *             $maxTTL = GraphQueryClass::MAX_TTL;
	 *             if ( $oldValue !== false ) {
	 *                 // Note that $oldAsOf is the last time this callback ran
	 *                 $ttl = ( $newList === $oldValue )
	 *                     // No change: cache for 150% of the age of $oldValue
	 *                     ? $cache->adaptiveTTL( $oldAsOf, $maxTTL, $minTTL, 1.5 )
	 *                     // Changed: cache for 50% of the age of $oldValue
	 *                     : $cache->adaptiveTTL( $oldAsOf, $maxTTL, $minTTL, .5 );
	 *             }
	 *
	 *             return $newList;
	 *        },
	 *        [
	 *             // Keep stale values around for doing comparisons for TTL calculations.
	 *             // High values improve long-tail keys hit-rates, though might waste space.
	 *             'staleTTL' => GraphQueryClass::GRACE_TTL
	 *        ]
	 *     );
	 * @endcode
	 *
	 * @param int|float $mtime UNIX timestamp
	 * @param int $maxTTL Maximum TTL (seconds)
	 * @param int $minTTL Minimum TTL (seconds); Default: 30
	 * @param float $factor Value in the range (0,1); Default: .2
	 * @return int Adaptive TTL
	 * @since 1.28
	 */
	public function adaptiveTTL( $mtime, $maxTTL, $minTTL = 30, $factor = 0.2 ) {
		if ( is_float( $mtime ) || ctype_digit( $mtime ) ) {
			$mtime = (int)$mtime; // handle fractional seconds and string integers
		}

		if ( !is_int( $mtime ) || $mtime <= 0 ) {
			return $minTTL; // no last-modified time provided
		}

		$age = $this->getCurrentTime() - $mtime;

		return (int)min( $maxTTL, max( $minTTL, $factor * $age ) );
	}

	/**
	 * @return int Number of warmup key cache misses last round
	 * @since 1.30
	 */
	final public function getWarmupKeyMisses() {
		return $this->warmupKeyMisses;
	}

	/**
	 * Do the actual async bus purge of a key
	 *
	 * This must set the key to "PURGED:<UNIX timestamp>:<holdoff>"
	 *
	 * @param string $key Sister cache key
	 * @param int $ttl Seconds to keep the tombstone around
	 * @param int $holdoff HOLDOFF_* constant controlling how long to ignore sets for this key
	 * @return bool Success
	 */
	protected function relayPurge( $key, $ttl, $holdoff ) {
		if ( $this->mcrouterAware ) {
			// See https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup
			// Wildcards select all matching routes, e.g. the WAN cluster on all DCs
			$ok = $this->cache->set(
				"/*/{$this->cluster}/{$key}",
				$this->makePurgeValue( $this->getCurrentTime(), $holdoff ),
				$ttl
			);
		} else {
			// Some other proxy handles broadcasting or there is only one datacenter
			$ok = $this->cache->set(
				$key,
				$this->makePurgeValue( $this->getCurrentTime(), $holdoff ),
				$ttl
			);
		}

		return $ok;
	}

	/**
	 * Do the actual async bus delete of a key
	 *
	 * @param string $key Sister cache key
	 * @return bool Success
	 */
	protected function relayDelete( $key ) {
		if ( $this->mcrouterAware ) {
			// See https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup
			// Wildcards select all matching routes, e.g. the WAN cluster on all DCs
			$ok = $this->cache->delete( "/*/{$this->cluster}/{$key}" );
		} else {
			// Some other proxy handles broadcasting or there is only one datacenter
			$ok = $this->cache->delete( $key );
		}

		return $ok;
	}

	/**
	 * Schedule a deferred cache regeneration if possible
	 *
	 * @param string $key
	 * @param int $ttl Seconds to live
	 * @param callable $callback
	 * @param array $opts
	 * @param array $cbParams
	 * @return bool Success
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	private function scheduleAsyncRefresh( $key, $ttl, $callback, array $opts, array $cbParams ) {
		if ( !$this->asyncHandler ) {
			return false;
		}
		// Update the cache value later, such during post-send of an HTTP request. This forces
		// cache regeneration by setting "minAsOf" to infinity, meaning that no existing value
		// is considered valid. Furthermore, note that preemptive regeneration is not applicable
		// to invalid values, so there is no risk of infinite preemptive regeneration loops.
		$func = $this->asyncHandler;
		$func( function () use ( $key, $ttl, $callback, $opts, $cbParams ) {
			$opts['minAsOf'] = INF;
			$this->fetchOrRegenerate( $key, $ttl, $callback, $opts, $cbParams );
		} );

		return true;
	}

	/**
	 * Check if a key is fresh or in the grace window and thus due for randomized reuse
	 *
	 * If $curTTL > 0 (e.g. not expired) this returns true. Otherwise, the chance of returning
	 * true decrease steadily from 100% to 0% as the |$curTTL| moves from 0 to $graceTTL seconds.
	 * This handles widely varying levels of cache access traffic.
	 *
	 * If $curTTL <= -$graceTTL (e.g. already expired), then this returns false.
	 *
	 * @param float $curTTL Approximate TTL left on the key if present
	 * @param int $graceTTL Consider using stale values if $curTTL is greater than this
	 * @return bool
	 */
	private function isAliveOrInGracePeriod( $curTTL, $graceTTL ) {
		if ( $curTTL > 0 ) {
			return true;
		} elseif ( $graceTTL <= 0 ) {
			return false;
		}

		$ageStale = abs( $curTTL ); // seconds of staleness
		$curGTTL = ( $graceTTL - $ageStale ); // current grace-time-to-live
		if ( $curGTTL <= 0 ) {
			return false; // already out of grace period
		}

		// Chance of using a stale value is the complement of the chance of refreshing it
		return !$this->worthRefreshExpiring( $curGTTL, $graceTTL );
	}

	/**
	 * Check if a key is nearing expiration and thus due for randomized regeneration
	 *
	 * This returns false if $curTTL >= $lowTTL. Otherwise, the chance of returning true
	 * increases steadily from 0% to 100% as the $curTTL moves from $lowTTL to 0 seconds.
	 * This handles widely varying levels of cache access traffic.
	 *
	 * If $curTTL <= 0 (e.g. already expired), then this returns false.
	 *
	 * @param float $curTTL Approximate TTL left on the key if present
	 * @param float $lowTTL Consider a refresh when $curTTL is less than this
	 * @return bool
	 */
	protected function worthRefreshExpiring( $curTTL, $lowTTL ) {
		if ( $lowTTL <= 0 ) {
			return false;
		} elseif ( $curTTL >= $lowTTL ) {
			return false;
		} elseif ( $curTTL <= 0 ) {
			return false;
		}

		$chance = ( 1 - $curTTL / $lowTTL );

		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal
		$decision = ( mt_rand( 1, 1e9 ) <= 1e9 * $chance );

		$this->logger->debug(
			"worthRefreshExpiring($curTTL, $lowTTL): " .
			"p = $chance; refresh = " . ( $decision ? 'Y' : 'N' )
		);

		return $decision;
	}

	/**
	 * Check if a key is due for randomized regeneration due to its popularity
	 *
	 * This is used so that popular keys can preemptively refresh themselves for higher
	 * consistency (especially in the case of purge loss/delay). Unpopular keys can remain
	 * in cache with their high nominal TTL. This means popular keys keep good consistency,
	 * whether the data changes frequently or not, and long-tail keys get to stay in cache
	 * and get hits too. Similar to worthRefreshExpiring(), randomization is used.
	 *
	 * @param float $asOf UNIX timestamp of the value
	 * @param int $ageNew Age of key when this might recommend refreshing (seconds)
	 * @param int $timeTillRefresh Age of key when it should be refreshed if popular (seconds)
	 * @param float $now The current UNIX timestamp
	 * @return bool
	 */
	protected function worthRefreshPopular( $asOf, $ageNew, $timeTillRefresh, $now ) {
		if ( $ageNew < 0 || $timeTillRefresh <= 0 ) {
			return false;
		}

		$age = $now - $asOf;
		$timeOld = $age - $ageNew;
		if ( $timeOld <= 0 ) {
			return false;
		}

		$popularHitsPerSec = 1;
		// Lifecycle is: new, ramp-up refresh chance, full refresh chance.
		// Note that the "expected # of refreshes" for the ramp-up time range is half
		// of what it would be if P(refresh) was at its full value during that time range.
		$refreshWindowSec = max( $timeTillRefresh - $ageNew - self::$RAMPUP_TTL / 2, 1 );
		// P(refresh) * (# hits in $refreshWindowSec) = (expected # of refreshes)
		// P(refresh) * ($refreshWindowSec * $popularHitsPerSec) = 1 (by definition)
		// P(refresh) = 1/($refreshWindowSec * $popularHitsPerSec)
		$chance = 1 / ( $popularHitsPerSec * $refreshWindowSec );
		// Ramp up $chance from 0 to its nominal value over RAMPUP_TTL seconds to avoid stampedes
		$chance *= ( $timeOld <= self::$RAMPUP_TTL ) ? $timeOld / self::$RAMPUP_TTL : 1;

		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal
		$decision = ( mt_rand( 1, 1e9 ) <= 1e9 * $chance );

		$this->logger->debug(
			"worthRefreshPopular($asOf, $ageNew, $timeTillRefresh, $now): " .
			"p = $chance; refresh = " . ( $decision ? 'Y' : 'N' )
		);

		return $decision;
	}

	/**
	 * Check if $value is not false, versioned (if needed), and not older than $minTime (if set)
	 *
	 * @param array|bool $value
	 * @param float $asOf The time $value was generated
	 * @param float $minAsOf Minimum acceptable "as of" timestamp
	 * @param float|null $purgeTime The last time the value was invalidated
	 * @return bool
	 */
	protected function isValid( $value, $asOf, $minAsOf, $purgeTime = null ) {
		// Avoid reading any key not generated after the latest delete() or touch
		$safeMinAsOf = max( $minAsOf, $purgeTime + self::$TINY_POSTIVE );

		if ( $value === false ) {
			return false;
		} elseif ( $safeMinAsOf > 0 && $asOf < $minAsOf ) {
			return false;
		}

		return true;
	}

	/**
	 * @param mixed $value
	 * @param int $ttl Seconds to live or zero for "indefinite"
	 * @param int|null $version Value version number or null if not versioned
	 * @param float $now Unix Current timestamp just before calling set()
	 * @param float|null $walltime How long it took to generate the value in seconds
	 * @return array
	 */
	private function wrap( $value, $ttl, $version, $now, $walltime ) {
		// Returns keys in ascending integer order for PHP7 array packing:
		// https://nikic.github.io/2014/12/22/PHPs-new-hashtable-implementation.html
		$wrapped = [
			self::$FLD_FORMAT_VERSION => self::$VERSION,
			self::$FLD_VALUE => $value,
			self::$FLD_TTL => $ttl,
			self::$FLD_TIME => $now
		];
		if ( $version !== null ) {
			$wrapped[self::$FLD_VALUE_VERSION] = $version;
		}
		if ( $walltime >= self::$GENERATION_SLOW_SEC ) {
			$wrapped[self::$FLD_GENERATION_TIME] = $walltime;
		}

		return $wrapped;
	}

	/**
	 * @param array|string|bool $wrapped The entry at a cache key
	 * @param float $now Unix Current timestamp (preferrably pre-query)
	 * @return array (value or false if absent/tombstoned/malformed, value metadata map).
	 * The cache key metadata includes the following metadata:
	 *   - asOf: UNIX timestamp of the value or null if there is no value
	 *   - curTTL: remaining time-to-live (negative if tombstoned) or null if there is no value
	 *   - version: value version number or null if the if there is no value
	 *   - tombAsOf: UNIX timestamp of the tombstone or null if there is no tombstone
	 * @phan-return array{0:mixed,1:array{asOf:?mixed,curTTL:?int|float,version:?mixed,tombAsOf:?mixed}}
	 */
	private function unwrap( $wrapped, $now ) {
		$value = false;
		$info = [ 'asOf' => null, 'curTTL' => null, 'version' => null, 'tombAsOf' => null ];

		if ( is_array( $wrapped ) ) {
			// Entry expected to be a cached value; validate it
			if (
				( $wrapped[self::$FLD_FORMAT_VERSION] ?? null ) === self::$VERSION &&
				$wrapped[self::$FLD_TIME] >= $this->epoch
			) {
				if ( $wrapped[self::$FLD_TTL] > 0 ) {
					// Get the approximate time left on the key
					$age = $now - $wrapped[self::$FLD_TIME];
					$curTTL = max( $wrapped[self::$FLD_TTL] - $age, 0.0 );
				} else {
					// Key had no TTL, so the time left is unbounded
					$curTTL = INF;
				}
				$value = $wrapped[self::$FLD_VALUE];
				$info['version'] = $wrapped[self::$FLD_VALUE_VERSION] ?? null;
				$info['asOf'] = $wrapped[self::$FLD_TIME];
				$info['curTTL'] = $curTTL;
			}
		} else {
			// Entry expected to be a tombstone; parse it
			$purge = $this->parsePurgeValue( $wrapped );
			if ( $purge !== false ) {
				// Tombstoned keys should always have a negative current $ttl
				$info['curTTL'] = min( $purge[self::$PURGE_TIME] - $now, self::$TINY_NEGATIVE );
				$info['tombAsOf'] = $purge[self::$PURGE_TIME];
			}
		}

		return [ $value, $info ];
	}

	/**
	 * @param string $key String of the format <scope>:<class>[:<class or variable>]...
	 * @return string A collection name to describe this class of key
	 */
	private function determineKeyClassForStats( $key ) {
		$parts = explode( ':', $key, 3 );
		// Sanity fallback in case the key was not made by makeKey.
		// Replace dots because they are special in StatsD (T232907)
		return strtr( $parts[1] ?? $parts[0], '.', '_' );
	}

	/**
	 * @param string|array|bool $value Possible string of the form "PURGED:<timestamp>:<holdoff>"
	 * @return array|bool Array containing a UNIX timestamp (float) and holdoff period (integer),
	 *  or false if value isn't a valid purge value
	 */
	private function parsePurgeValue( $value ) {
		if ( !is_string( $value ) ) {
			return false;
		}

		$segments = explode( ':', $value, 3 );
		if (
			!isset( $segments[0] ) ||
			!isset( $segments[1] ) ||
			"{$segments[0]}:" !== self::$PURGE_VAL_PREFIX
		) {
			return false;
		}

		if ( !isset( $segments[2] ) ) {
			// Back-compat with old purge values without holdoff
			$segments[2] = self::HOLDOFF_TTL;
		}

		if ( $segments[1] < $this->epoch ) {
			// Values this old are ignored
			return false;
		}

		return [
			self::$PURGE_TIME => (float)$segments[1],
			self::$PURGE_HOLDOFF => (int)$segments[2],
		];
	}

	/**
	 * @param float $timestamp
	 * @param int $holdoff In seconds
	 * @return string Wrapped purge value
	 */
	private function makePurgeValue( $timestamp, $holdoff ) {
		return self::$PURGE_VAL_PREFIX . (float)$timestamp . ':' . (int)$holdoff;
	}

	/**
	 * @param string $group
	 * @return MapCacheLRU
	 */
	private function getProcessCache( $group ) {
		if ( !isset( $this->processCaches[$group] ) ) {
			list( , $size ) = explode( ':', $group );
			$this->processCaches[$group] = new MapCacheLRU( (int)$size );
			if ( $this->wallClockOverride !== null ) {
				$this->processCaches[$group]->setMockTime( $this->wallClockOverride );
			}
		}

		return $this->processCaches[$group];
	}

	/**
	 * @param string $key
	 * @param int $version
	 * @return string
	 */
	private function getProcessCacheKey( $key, $version ) {
		return $key . ' ' . (int)$version;
	}

	/**
	 * @param ArrayIterator $keys
	 * @param array $opts
	 * @return string[] Map of (ID => cache key)
	 */
	private function getNonProcessCachedMultiKeys( ArrayIterator $keys, array $opts ) {
		$pcTTL = $opts['pcTTL'] ?? self::TTL_UNCACHEABLE;

		$keysMissing = [];
		if ( $pcTTL > 0 && $this->callbackDepth == 0 ) {
			$version = $opts['version'] ?? null;
			$pCache = $this->getProcessCache( $opts['pcGroup'] ?? self::PC_PRIMARY );
			foreach ( $keys as $key => $id ) {
				if ( !$pCache->has( $this->getProcessCacheKey( $key, $version ), $pcTTL ) ) {
					$keysMissing[$id] = $key;
				}
			}
		}

		return $keysMissing;
	}

	/**
	 * @param string[] $keys
	 * @param string[]|string[][] $checkKeys
	 * @return string[] List of cache keys
	 */
	private function getRawKeysForWarmup( array $keys, array $checkKeys ) {
		if ( !$keys ) {
			return [];
		}

		// Get all the value keys to fetch...
		$keysWarmup = $this->makeSisterKeys( $keys, self::$TYPE_VALUE );
		// Get all the check keys to fetch...
		foreach ( $checkKeys as $i => $checkKeyOrKeyGroup ) {
			// Note: avoid array_merge() inside loop in case there are many keys
			if ( is_int( $i ) ) {
				// Single check key that applies to all value keys
				$keysWarmup[] = $this->makeSisterKey( $checkKeyOrKeyGroup, self::$TYPE_TIMESTAMP );
			} else {
				// List of check keys that apply to a specific value key
				foreach ( (array)$checkKeyOrKeyGroup as $checkKey ) {
					$keysWarmup[] = $this->makeSisterKey( $checkKey, self::$TYPE_TIMESTAMP );
				}
			}
		}

		$warmupCache = $this->cache->getMulti( $keysWarmup );
		$warmupCache += array_fill_keys( $keysWarmup, false );

		return $warmupCache;
	}

	/**
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	protected function getCurrentTime() {
		if ( $this->wallClockOverride ) {
			return $this->wallClockOverride;
		}

		$clockTime = (float)time(); // call this first
		// microtime() uses an initial gettimeofday() call added to usage clocks.
		// This can severely drift from time() and the microtime() value of other threads
		// due to undercounting of the amount of time elapsed. Instead of seeing the current
		// time as being in the past, use the value of time(). This avoids setting cache values
		// that will immediately be seen as expired and possibly cause stampedes.
		return max( microtime( true ), $clockTime );
	}

	/**
	 * @param float|null &$time Mock UNIX timestamp for testing
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->wallClockOverride =& $time;
		$this->cache->setMockTime( $time );
		foreach ( $this->processCaches as $pCache ) {
			$pCache->setMockTime( $time );
		}
	}
}
