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
 * should be used. Try to avoid heavyweight DB primary or quorum reads.
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
 *        and validated after on retrieval. If outdated, the value is recomputed.
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
 * ### Deploying WANObjectCache
 *
 * There are two supported ways to set up broadcasted operations:
 *
 *   - A) Set up mcrouter as the cache backend, with a memcached BagOStuff class for the 'cache'
 *        parameter, and a wildcard routing prefix for the 'broadcastRoutingPrefix' parameter.
 *        Configure mcrouter as follows:
 *          - Define a "<datacenter>" pool of memcached servers for each datacenter.
 *          - Define a "<datacenter>/wan" route to each datacenter, using "AllSyncRoute" for the
 *            routes that go to the local datacenter pool and "AllAsyncRoute" for the routes that
 *            go to remote datacenter pools. The child routes should use "HashRoute|<datacenter>".
 *            This allows for the use of a wildcard route for 'broadcastRoutingPrefix'. See
 *            https://github.com/facebook/mcrouter/wiki/Routing-Prefix and
 *            https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup.
 *          - In order to reroute operations from "down" servers to spare ("gutter") servers, use
 *            "FailoverWithExptimeRoute" (failover_exptime=60) instead of "HashRoute|<datacenter>"
 *            in the "AllSyncRoute"/"AllAsyncRoute" child routes.
 *            The "gutter" pool is a set of memcached servers that only handle failover traffic.
 *            Such servers should be carefully spread over different rows and racks. See
 *            https://github.com/facebook/mcrouter/wiki/List-of-Route-Handles#failoverroute
 *   - B) Set up dynomite as the cache backend, using a memcached BagOStuff class for the 'cache'
 *        parameter. Note that with this setup, all key setting operations will be broadcasted,
 *        rather than just purges. Writes will be eventually consistent via the Dynamo replication
 *        model. See https://github.com/Netflix/dynomite.
 *
 * Broadcasted operations like delete() and touchCheckKey() are intended to run
 * immediately in the local datacenter and asynchronously in remote datacenters.
 *
 * This means that callers in all datacenters may see older values for however many
 * milliseconds that the purge took to reach that datacenter. As with any cache, this
 * should not be relied on for cases where reads are used to determine writes to source
 * (e.g. non-cache) data stores, except when reading immutable data.
 *
 * Internally, access to a given key actually involves the use of one or more "sister" keys.
 * A sister key is constructed by prefixing the base key with "WANCache:" (used to distinguish
 * WANObjectCache formatted keys) and suffixing a colon followed by a single-character sister
 * key type. The sister key types include the following:
 *
 * - `v`: used to store "regular" values (metadata-wrapped) and temporary purge "tombstones".
 * - `t`: used to store "last purge" timestamps for "check" keys.
 * - `m`: used to store temporary mutex locks to avoid cache stampedes.
 * - `i`: used to store temporary interim values (metadata-wrapped) for tombstoned keys.
 * - `c`: used to store temporary "cool-off" indicators, which specify a period during which
 *        values cannot be stored, neither regularly nor using interim keys.
 *
 * @ingroup Cache
 * @newable
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

	/**
	 * Routing prefix for operations that should be broadcasted to all data centers.
	 *
	 * If null, the there is only one datacenter or a backend proxy broadcasts everything.
	 *
	 * @var string|null
	 */
	protected $broadcastRoute;
	/** @var bool Whether to use "interim" caching while keys are tombstoned */
	protected $useInterimHoldOffCaching = true;
	/** @var float Unix timestamp of the oldest possible valid values */
	protected $epoch;
	/** @var string Stable secret used for hashing long strings into key components */
	protected $secret;
	/** @var int Scheme to use for key coalescing (Hash Tags or Hash Stops) */
	protected $coalesceScheme;

	/** @var int Reads/second assumed during a hypothetical cache write stampede for a key */
	private $keyHighQps;
	/** @var float Max tolerable bytes/second to spend on a cache write stampede for a key */
	private $keyHighUplinkBps;

	/** @var array<int,array> List of (key, UNIX timestamp) tuples for get() cache misses */
	private $missLog;

	/** @var int Callback stack depth for getWithSetCallback() */
	private $callbackDepth = 0;
	/** @var mixed[] Temporary warm-up cache */
	private $warmupCache = [];
	/** @var int Key fetched */
	private $warmupKeyMisses = 0;

	/** @var float|null */
	private $wallClockOverride;

	/** Max expected seconds to pass between delete() and DB commit finishing */
	private const MAX_COMMIT_DELAY = 3;
	/** Max expected seconds of combined lag from replication and "view snapshots" */
	private const MAX_READ_LAG = 7;
	/** Seconds to tombstone keys on delete() and to treat keys as volatile after purges */
	public const HOLDOFF_TTL = self::MAX_COMMIT_DELAY + self::MAX_READ_LAG + 1;

	/** Consider regeneration if the key will expire within this many seconds */
	private const LOW_TTL = 30;
	/** Max TTL, in seconds, to store keys when a data source has high replication lag */
	public const TTL_LAGGED = 30;

	/** Expected time-till-refresh, in seconds, if the key is accessed once per second */
	private const HOT_TTR = 900;
	/** Minimum key age, in seconds, for expected time-till-refresh to be considered */
	private const AGE_NEW = 60;

	/** Idiom for getWithSetCallback() meaning "no cache stampede mutex" */
	private const TSE_NONE = -1;

	/** Idiom for set()/getWithSetCallback() meaning "no post-expiration persistence" */
	public const STALE_TTL_NONE = 0;
	/** Idiom for set()/getWithSetCallback() meaning "no post-expiration grace period" */
	public const GRACE_TTL_NONE = 0;
	/** Idiom for delete()/touchCheckKey() meaning "no hold-off period" */
	public const HOLDOFF_TTL_NONE = 0;

	/** @var float Idiom for getWithSetCallback() meaning "no minimum required as-of timestamp" */
	public const MIN_TIMESTAMP_NONE = 0.0;

	/** Default process cache name and max key count */
	private const PC_PRIMARY = 'primary:1000';

	/** Idiom for get()/getMulti() to return extra information by reference */
	public const PASS_BY_REF = [];

	/** Use twemproxy-style Hash Tag key scheme (e.g. "{...}") */
	private const SCHEME_HASH_TAG = 1;
	/** Use mcrouter-style Hash Stop key scheme (e.g. "...|#|") */
	private const SCHEME_HASH_STOP = 2;

	/** Seconds to keep dependency purge keys around */
	private const CHECK_KEY_TTL = self::TTL_YEAR;
	/** Seconds to keep interim value keys for tombstoned keys around */
	private const INTERIM_KEY_TTL = 1;

	/** Seconds to keep lock keys around */
	private const LOCK_TTL = 10;
	/** Seconds to no-op key set() calls to avoid large blob I/O stampedes */
	private const COOLOFF_TTL = 1;
	/** Seconds to ramp up the chance of regeneration due to expected time-till-refresh */
	private const RAMPUP_TTL = 30;

	/** @var float Tiny negative float to use when CTL comes up >= 0 due to clock skew */
	private const TINY_NEGATIVE = -0.000001;
	/** @var float Tiny positive float to use when using "minTime" to assert an inequality */
	private const TINY_POSTIVE = 0.000001;

	/** Min millisecond set() backoff during hold-off (far less than INTERIM_KEY_TTL) */
	private const RECENT_SET_LOW_MS = 50;
	/** Max millisecond set() backoff during hold-off (far less than INTERIM_KEY_TTL) */
	private const RECENT_SET_HIGH_MS = 100;

	/** Consider value generation somewhat high if it takes this many seconds or more */
	private const GENERATION_HIGH_SEC = 0.2;
	/** Consider value generation slow if it takes this many seconds or more */
	private const GENERATION_SLOW_SEC = 3.0;

	/** Key to the tombstone entry timestamp */
	private const PURGE_TIME = 0;
	/** Key to the tombstone entry hold-off TTL */
	private const PURGE_HOLDOFF = 1;

	/** Cache format version number */
	private const VERSION = 1;

	/** Version number attribute for a key; keep value for b/c (< 1.36) */
	public const KEY_VERSION = 'version';
	/** Generation completion timestamp attribute for a key; keep value for b/c (< 1.36) */
	public const KEY_AS_OF = 'asOf';
	/** Logical TTL attribute for a key */
	public const KEY_TTL = 'ttl';
	/** Remaining TTL attribute for a key; keep value for b/c (< 1.36) */
	public const KEY_CUR_TTL = 'curTTL';
	/** Tomstone timestamp attribute for a key; keep value for b/c (< 1.36) */
	public const KEY_TOMB_AS_OF = 'tombAsOf';
	/** Highest "check" key timestamp for a key; keep value for b/c (< 1.36) */
	public const KEY_CHECK_AS_OF = 'lastCKPurge';

	/** Value for a key */
	private const RES_VALUE = 0;
	/** Version number attribute for a key */
	private const RES_VERSION = 1;
	/** Generation completion timestamp attribute for a key */
	private const RES_AS_OF = 2;
	/** Logical TTL attribute for a key */
	private const RES_TTL = 3;
	/** Tomstone timestamp attribute for a key */
	private const RES_TOMB_AS_OF = 4;
	/** Highest "check" key timestamp for a key */
	private const RES_CHECK_AS_OF = 5;
	/** Highest "touched" timestamp for a key */
	private const RES_TOUCH_AS_OF = 6;
	/** Remaining TTL attribute for a key */
	private const RES_CUR_TTL = 7;

	/** Key to WAN cache version number; stored in blobs */
	private const FLD_FORMAT_VERSION = 0;
	/** Key to the cached value; stored in blobs */
	private const FLD_VALUE = 1;
	/** Key to the original TTL; stored in blobs */
	private const FLD_TTL = 2;
	/** Key to the cache timestamp; stored in blobs */
	private const FLD_TIME = 3;
	/** Key to the flags bit field (reserved number) */
	private const /** @noinspection PhpUnusedPrivateFieldInspection */ FLD_FLAGS = 4;
	/** Key to collection cache version number; stored in blobs */
	private const FLD_VALUE_VERSION = 5;
	/** Key to how long it took to generate the value; stored in blobs */
	private const FLD_GENERATION_TIME = 6;

	/** Single character component for value keys */
	private const TYPE_VALUE = 'v';
	/** Single character component for timestamp check keys */
	private const TYPE_TIMESTAMP = 't';
	/** Single character component for mutex lock keys */
	private const TYPE_MUTEX = 'm';
	/** Single character component for interium value keys */
	private const TYPE_INTERIM = 'i';
	/** Single character component for cool-off bounce keys */
	private const TYPE_COOLOFF = 'c';

	/** Value prefix of purge values */
	private const PURGE_VAL_PREFIX = 'PURGED';

	/**
	 * @stable to call
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
	 *   - broadcastRoutingPrefix: a routing prefix used to broadcast certain operations to all
	 *       datacenters; See also <https://github.com/facebook/mcrouter/wiki/Config-Files>.
	 *       This prefix takes the form `/<datacenter>/<name of wan route>/`, where `datacenter`
	 *       is usually a wildcard to select all matching routes (e.g. the WAN cluster in all DCs).
	 *       See also <https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup>.
	 *       This is required when using mcrouter as a multi-region backing store proxy. [optional]
	 *   - epoch: lowest UNIX timestamp a value/tombstone must have to be valid. [optional]
	 *   - secret: stable secret used for hashing long strings into key components. [optional]
	 *   - coalesceScheme: which key scheme to use in order to encourage the backend to place any
	 *       "helper" keys for a "value" key within the same cache server. This reduces network
	 *       overhead and reduces the chance the a single downed cache server causes disruption.
	 *       Use "hash_stop" with mcrouter and "hash_tag" with dynomite. [default: "hash_stop"]
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
		$this->broadcastRoute = $params['broadcastRoutingPrefix'] ?? null;
		$this->epoch = $params['epoch'] ?? 0;
		$this->secret = $params['secret'] ?? (string)$this->epoch;
		if ( ( $params['coalesceScheme'] ?? '' ) === 'hash_tag' ) {
			// https://redis.io/topics/cluster-spec
			// https://github.com/twitter/twemproxy/blob/v0.4.1/notes/recommendation.md#hash-tags
			// https://github.com/Netflix/dynomite/blob/v0.7.0/notes/recommendation.md#hash-tags
			$this->coalesceScheme = self::SCHEME_HASH_TAG;
		} else {
			// https://github.com/facebook/mcrouter/wiki/Key-syntax
			$this->coalesceScheme = self::SCHEME_HASH_STOP;
		}

		$this->keyHighQps = $params['keyHighQps'] ?? 100;
		$this->keyHighUplinkBps = $params['keyHighUplinkBps'] ?? ( 1e9 / 8 / 100 );

		$this->setLogger( $params['logger'] ?? new NullLogger() );
		$this->stats = $params['stats'] ?? new NullStatsdDataFactory();
		$this->asyncHandler = $params['asyncHandler'] ?? null;

		$this->missLog = array_fill( 0, 10, [ '', 0.0 ] );

		$this->cache->registerWrapperInfoForStats(
			'WANCache',
			'wanobjectcache',
			[ __CLASS__, 'getCollectionFromSisterKey' ]
		);
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
	 *   - a) INF; if $key exists, has no TTL, and is not purged by $checkKeys
	 *   - b) float (>=0); if $key exists, has a TTL, and is not purged by $checkKeys
	 *   - c) float (<0); if $key is tombstoned, stale, or existing but purged by $checkKeys
	 *   - d) null; if $key does not exist and is not tombstoned
	 *
	 * If a key is tombstoned, $curTTL will reflect the time since delete().
	 *
	 * The timestamp of $key will be checked against the last-purge timestamp
	 * of each of $checkKeys. Those $checkKeys not in cache will have the last-purge
	 * initialized to the current timestamp. If any of $checkKeys have a timestamp
	 * greater than that of $key, then $curTTL will reflect how long ago $key
	 * became invalid. Callers can use $curTTL to know when the value is stale.
	 * The $checkKeys parameter allow mass key purges by updating a single key:
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
	 * Consider using getWithSetCallback(), which has cache slam avoidance and key
	 * versioning features, instead of bare get()/set() calls.
	 *
	 * Do not use this method on versioned keys accessed via getWithSetCallback().
	 *
	 * When using the $info parameter, it should be passed in as WANObjectCache::PASS_BY_REF.
	 * In that case, it becomes a key metadata map. Otherwise, for backwards compatibility,
	 * $info becomes the value generation timestamp (null if the key is nonexistant/tombstoned).
	 * Key metadata map fields include:
	 *   - WANObjectCache::KEY_VERSION: value version number; null if key is nonexistant
	 *   - WANObjectCache::KEY_AS_OF: value generation timestamp (UNIX); null if key is nonexistant
	 *   - WANObjectCache::KEY_TTL: assigned TTL (seconds); null if key is nonexistant/tombstoned
	 *   - WANObjectCache::KEY_CUR_TTL: remaining TTL (seconds); null if key is nonexistant
	 *   - WANObjectCache::KEY_TOMB_AS_OF: tombstone timestamp (UNIX); null if key is not tombstoned
	 *   - WANObjectCache::KEY_CHECK_AS_OF: highest "check" key timestamp (UNIX); null if none
	 *
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param float|null &$curTTL Seconds of TTL left [returned]
	 * @param string[] $checkKeys Map of (integer or cache key => "check" key(s));
	 *  "check" keys must also be made with makeKey()/makeGlobalKey()
	 * @param array &$info Metadata map [returned]
	 * @return mixed Value of cache key; false on failure
	 */
	final public function get( $key, &$curTTL = null, array $checkKeys = [], &$info = [] ) {
		// Note that an undeclared variable passed as $info starts as null (not the default).
		// Also, if no $info parameter is provided, then it doesn't matter how it changes here.
		$legacyInfo = ( $info !== self::PASS_BY_REF );

		$res = $this->fetchKeys( [ $key ], $checkKeys )[$key];

		$curTTL = $res[self::RES_CUR_TTL];
		$info = $legacyInfo
			? $res[self::RES_AS_OF]
			: [
				self::KEY_VERSION => $res[self::RES_VERSION],
				self::KEY_AS_OF => $res[self::RES_AS_OF],
				self::KEY_TTL => $res[self::RES_TTL],
				self::KEY_CUR_TTL => $res[self::RES_CUR_TTL],
				self::KEY_TOMB_AS_OF => $res[self::RES_TOMB_AS_OF],
				self::KEY_CHECK_AS_OF => $res[self::RES_CHECK_AS_OF]
			];

		if ( $curTTL === null || $curTTL <= 0 ) {
			// Log the timestamp in case a corresponding set() call does not provide "walltime"
			reset( $this->missLog );
			unset( $this->missLog[key( $this->missLog )] );
			$this->missLog[] = [ $key, $this->getCurrentTime() ];
		}

		return $res[self::RES_VALUE];
	}

	/**
	 * Fetch the value of several keys from cache
	 *
	 * $curTTLs becomes a map of only present/tombstoned $keys to their current time-to-live.
	 *
	 * $checkKeys holds the "check" keys used to validate values of applicable keys. The
	 * integer indexes hold "check" keys that apply to all of $keys while the string indexes
	 * hold "check" keys that only apply to the cache key with that name. The logic of "check"
	 * keys otherwise works the same as in WANObjectCache::get().
	 *
	 * When using the $info parameter, it should be passed in as WANObjectCache::PASS_BY_REF.
	 * In that case, it becomes a mapping of all the $keys to their metadata maps, each in the
	 * style of WANObjectCache::get(). Otherwise, for backwards compatibility, $info becomes a
	 * map of only present/tombstoned $keys to their value generation timestamps.
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string[] $keys List/map with makeKey()/makeGlobalKey() cache keys as values
	 * @param array<string,float> &$curTTLs Map of (key => seconds of TTL left) [returned]
	 * @param string[]|string[][] $checkKeys Map of (integer or cache key => "check" key(s));
	 *  "check" keys must also be made with makeKey()/makeGlobalKey()
	 * @param array<string,array> &$info Map of (key => metadata map) [returned]
	 * @return array<string,mixed> Map of (key => value) for existing values in order of $keys
	 */
	final public function getMulti(
		array $keys,
		&$curTTLs = [],
		array $checkKeys = [],
		&$info = []
	) {
		// Note that an undeclared variable passed as $info starts as null (not the default).
		// Also, if no $info parameter is provided, then it doesn't matter how it changes here.
		$legacyInfo = ( $info !== self::PASS_BY_REF );

		$curTTLs = [];
		$info = [];
		$valuesByKey = [];

		$resByKey = $this->fetchKeys( $keys, $checkKeys );
		foreach ( $resByKey as $key => $res ) {
			if ( $res[self::RES_VALUE] !== false ) {
				$valuesByKey[$key] = $res[self::RES_VALUE];
			}

			if ( $res[self::RES_CUR_TTL] !== null ) {
				$curTTLs[$key] = $res[self::RES_CUR_TTL];
			}
			$info[$key] = $legacyInfo
				? $res[self::RES_AS_OF]
				: [
					self::KEY_VERSION => $res[self::RES_VERSION],
					self::KEY_AS_OF => $res[self::RES_AS_OF],
					self::KEY_TTL => $res[self::RES_TTL],
					self::KEY_CUR_TTL => $res[self::RES_CUR_TTL],
					self::KEY_TOMB_AS_OF => $res[self::RES_TOMB_AS_OF],
					self::KEY_CHECK_AS_OF => $res[self::RES_CHECK_AS_OF]
				];
		}

		return $valuesByKey;
	}

	/**
	 * Fetch the value and key metadata of several keys from cache
	 *
	 * $checkKeys holds the "check" keys used to validate values of applicable keys.
	 * The integer indexes hold "check" keys that apply to all of $keys while the string
	 * indexes hold "check" keys that only apply to the cache key with that name.
	 *
	 * @param string[] $keys List/map with makeKey()/makeGlobalKey() cache keys as values
	 * @param string[]|string[][] $checkKeys Map of (integer or cache key => "check" key(s));
	 *  "check" keys must also be made with makeKey()/makeGlobalKey()
	 * @param callable|null $touchedCb Callback yielding a UNIX timestamp from a value, or null
	 * @return array<string,array> Map of (key => WANObjectCache::RESULT_* map) in order of $keys
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	protected function fetchKeys( array $keys, array $checkKeys, $touchedCb = null ) {
		$resByKey = [];

		// List of all sister keys that need to be fetched from cache
		$allSisterKeys = [];
		// Order-corresponding value sister key list for the base key list ($keys)
		$valueSisterKeys = [];
		// List of "check" sister keys to compare all value sister keys against
		$checkSisterKeysForAll = [];
		// Map of (base key => additional "check" sister key(s) to compare against)
		$checkSisterKeysByKey = [];

		foreach ( $keys as $key ) {
			$sisterKey = $this->makeSisterKey( $key, self::TYPE_VALUE );
			$allSisterKeys[] = $sisterKey;
			$valueSisterKeys[] = $sisterKey;
		}

		foreach ( $checkKeys as $i => $checkKeyOrKeyGroup ) {
			// Note: avoid array_merge() inside loop in case there are many keys
			if ( is_int( $i ) ) {
				// Single "check" key that applies to all base keys
				$sisterKey = $this->makeSisterKey( $checkKeyOrKeyGroup, self::TYPE_TIMESTAMP );
				$allSisterKeys[] = $sisterKey;
				$checkSisterKeysForAll[] = $sisterKey;
			} else {
				// List of "check" keys that apply to a specific base key
				foreach ( (array)$checkKeyOrKeyGroup as $checkKey ) {
					$sisterKey = $this->makeSisterKey( $checkKey, self::TYPE_TIMESTAMP );
					$allSisterKeys[] = $sisterKey;
					$checkSisterKeysByKey[$i][] = $sisterKey;
				}
			}
		}

		if ( $this->warmupCache ) {
			// Get the wrapped values of the sister keys from the warmup cache
			$wrappedBySisterKey = $this->warmupCache;
			$sisterKeysMissing = array_diff( $allSisterKeys, array_keys( $wrappedBySisterKey ) );
			if ( $sisterKeysMissing ) {
				$this->warmupKeyMisses += count( $sisterKeysMissing );
				$wrappedBySisterKey += $this->cache->getMulti( $sisterKeysMissing );
			}
		} else {
			// Fetch the wrapped values of the sister keys from the backend
			$wrappedBySisterKey = $this->cache->getMulti( $allSisterKeys );
		}

		// Pessimistically treat the "current time" as the time when any network I/O finished
		$now = $this->getCurrentTime();

		// List of "check" sister key purge timestamps to compare all value sister keys against
		$ckPurgesForAll = $this->processCheckKeys(
			$checkSisterKeysForAll,
			$wrappedBySisterKey,
			$now
		);
		// Map of (base key => extra "check" sister key purge timestamp(s) to compare against)
		$ckPurgesByKey = [];
		foreach ( $checkSisterKeysByKey as $keyWithCheckKeys => $checkKeysForKey ) {
			$ckPurgesByKey[$keyWithCheckKeys] = $this->processCheckKeys(
				$checkKeysForKey,
				$wrappedBySisterKey,
				$now
			);
		}

		// Unwrap and validate any value found for each base key (under the value sister key)
		reset( $keys );
		foreach ( $valueSisterKeys as $valueSisterKey ) {
			// Get the corresponding base key for this value sister key
			$key = current( $keys );
			next( $keys );

			if ( array_key_exists( $valueSisterKey, $wrappedBySisterKey ) ) {
				// Key exists as either a live value or tombstone value
				$wrapped = $wrappedBySisterKey[$valueSisterKey];
			} else {
				// Key does not exist
				$wrapped = false;
			}

			$res = $this->unwrap( $wrapped, $now );
			$value = $res[self::RES_VALUE];

			foreach ( array_merge( $ckPurgesForAll, $ckPurgesByKey[$key] ?? [] ) as $ckPurge ) {
				$res[self::RES_CHECK_AS_OF] = max(
					$ckPurge[self::PURGE_TIME],
					$res[self::RES_CHECK_AS_OF]
				);
				// Timestamp marking the end of the hold-off period for this purge
				$holdoffDeadline = $ckPurge[self::PURGE_TIME] + $ckPurge[self::PURGE_HOLDOFF];
				// Check if the value was generated during the hold-off period
				if ( $value !== false && $holdoffDeadline >= $res[self::RES_AS_OF] ) {
					// How long ago this value was purged by *this* "check" key
					$ago = min( $ckPurge[self::PURGE_TIME] - $now, self::TINY_NEGATIVE );
					// How long ago this value was purged by *any* known "check" key
					$res[self::RES_CUR_TTL] = min( $res[self::RES_CUR_TTL], $ago );
				}
			}

			if ( $touchedCb !== null && $value !== false ) {
				$touched = $touchedCb( $value );
				if ( $touched !== null && $touched >= $res[self::RES_AS_OF] ) {
					$res[self::RES_CUR_TTL] = min(
						$res[self::RES_CUR_TTL],
						$res[self::RES_AS_OF] - $touched,
						self::TINY_NEGATIVE
					);
				}
			} else {
				$touched = null;
			}

			$res[self::RES_TOUCH_AS_OF] = max( $res[self::RES_TOUCH_AS_OF], $touched );

			$resByKey[$key] = $res;
		}

		return $resByKey;
	}

	/**
	 * @param string[] $checkSisterKeys List of "check" sister keys
	 * @param mixed[] $wrappedBySisterKey Preloaded map of (sister key => wrapped value)
	 * @param float $now UNIX timestamp
	 * @return array[] List of purge value arrays
	 */
	private function processCheckKeys(
		array $checkSisterKeys,
		array $wrappedBySisterKey,
		float $now
	) {
		$purges = [];

		foreach ( $checkSisterKeys as $timeKey ) {
			$purge = isset( $wrappedBySisterKey[$timeKey] )
				? $this->parsePurgeValue( $wrappedBySisterKey[$timeKey] )
				: null;

			if ( $purge === null ) {
				$wrapped = $this->makeCheckPurgeValue( $now, self::HOLDOFF_TTL, $purge );
				$this->cache->add( $timeKey, $wrapped, self::CHECK_KEY_TTL );
			}

			$purges[] = $purge;
		}

		return $purges;
	}

	/**
	 * Set the value of a key in cache
	 *
	 * Simply calling this method when source data changes is not valid because
	 * the changes do not replicate to the other WAN sites. In that case, delete()
	 * should be used instead. This method is intended for use on cache misses.
	 *
	 * If data was read using "view snapshots" (e.g. innodb REPEATABLE-READ),
	 * use 'since' to avoid the following race condition:
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
	 * Consider using getWithSetCallback(), which has cache slam avoidance and key
	 * versioning features, instead of bare get()/set() calls.
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param mixed $value Value to set for the cache key
	 * @param int $ttl Seconds to live. Special values are:
	 *   - WANObjectCache::TTL_INDEFINITE: Cache forever (default)
	 *   - WANObjectCache::TTL_UNCACHEABLE: Do not cache (if the key exists, it is not deleted)
	 * @param array $opts Options map:
	 *   - lag: Highest seconds of replication lag potentially affecting reads used to generate
	 *      the value. This should not be affected by the duration of transaction "view snapshots"
	 *      (e.g. innodb REPEATABLE-READ) nor the time elapsed since the first read (though both
	 *      increase staleness). For reads using view snapshots, only the replication lag during
	 *      snapshot initialization matters. Use false if replication is stopped/broken on a
	 *      replica server involved in the reads.
	 *      Default: 0 seconds
	 *   - since: UNIX timestamp indicative of the highest possible staleness caused by the
	 *      duration of transaction "view snapshots" (e.g. innodb REPEATABLE-READ) and the time
	 *      elapsed since the first read. This should not be affected by replication lag.
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
	 *      will call the generation callback in such cases, passing in the old value
	 *      and its as-of time to the callback. This is useful if adaptiveTTL() is used
	 *      on the old value's as-of time when it is verified as still being correct.
	 *      Default: WANObjectCache::STALE_TTL_NONE
	 *   - creating: Optimize for the case where the key does not already exist.
	 *      Default: false
	 *   - version: Integer version number signifying the format of the value.
	 *      Default: null
	 *   - walltime: How long the value took to generate in seconds. Default: null
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{lag?:int,since?:int,pending?:bool,lockTSE?:int,staleTTL?:int,creating?:bool,version?:int,walltime?:int|float} $opts
	 * @note Options added in 1.28: staleTTL
	 * @note Options added in 1.33: creating
	 * @note Options added in 1.34: version, walltime
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = self::TTL_INDEFINITE, array $opts = [] ) {
		$now = $this->getCurrentTime();
		$dataReplicaLag = $opts['lag'] ?? 0;
		$dataSnapshotLag = isset( $opts['since'] ) ? max( 0, $now - $opts['since'] ) : 0;
		$dataCombinedLag = $dataReplicaLag + $dataSnapshotLag;
		$dataPendingCommit = $opts['pending'] ?? null;
		$lockTSE = $opts['lockTSE'] ?? self::TSE_NONE;
		$staleTTL = $opts['staleTTL'] ?? self::STALE_TTL_NONE;
		$creating = $opts['creating'] ?? false;
		$version = $opts['version'] ?? null;
		$walltime = $opts['walltime'] ?? $this->timeSinceLoggedMiss( $key, $now );

		if ( $ttl < 0 ) {
			// not cacheable
			return true;
		}

		// Forbid caching data that only exists within an uncommitted transaction. Also, lower
		// the TTL when the data has a "since" time so far in the past that a delete() tombstone,
		// made after that time, could have already expired (the key is no longer write-holed).
		// The mitigation TTL depends on whether this data lag is assumed to systemically effect
		// regeneration attempts in the near future. The TTL also reflects regeneration wall time.
		if ( $dataPendingCommit ) {
			// Case A: data comes from an uncommitted write transaction
			$mitigated = 'pending writes';
			// Data might never be committed; rely on a less problematic regeneration attempt
			$mitigationTTL = self::TTL_UNCACHEABLE;
		} elseif ( $dataSnapshotLag > self::MAX_READ_LAG ) {
			// Case B: high snapshot lag
			$pregenSnapshotLag = ( $walltime !== null ) ? ( $dataSnapshotLag - $walltime ) : 0;
			if ( ( $pregenSnapshotLag + self::GENERATION_HIGH_SEC ) > self::MAX_READ_LAG ) {
				// Case B1: generation started when transaction duration was already long
				$mitigated = 'snapshot lag (late generation)';
				// Probably non-systemic; rely on a less problematic regeneration attempt
				$mitigationTTL = self::TTL_UNCACHEABLE;
			} else {
				// Case B2: slow generation made transaction duration long
				$mitigated = 'snapshot lag (high generation time)';
				// Probably systemic; use a low TTL to avoid stampedes/uncacheability
				$mitigationTTL = self::LOW_TTL;
			}
		} elseif ( $dataReplicaLag === false || $dataReplicaLag > self::MAX_READ_LAG ) {
			// Case C: low/medium snapshot lag with high replication lag
			$mitigated = 'replication lag';
			// Probably systemic; use a low TTL to avoid stampedes/uncacheability
			$mitigationTTL = self::TTL_LAGGED;
		} elseif ( $dataCombinedLag > self::MAX_READ_LAG ) {
			$pregenCombinedLag = ( $walltime !== null ) ? ( $dataCombinedLag - $walltime ) : 0;
			// Case D: medium snapshot lag with medium replication lag
			if ( ( $pregenCombinedLag + self::GENERATION_HIGH_SEC ) > self::MAX_READ_LAG ) {
				// Case D1: generation started when read lag was too high
				$mitigated = 'read lag (late generation)';
				// Probably non-systemic; rely on a less problematic regeneration attempt
				$mitigationTTL = self::TTL_UNCACHEABLE;
			} else {
				// Case D2: slow generation made read lag too high
				$mitigated = 'read lag (high generation time)';
				// Probably systemic; use a low TTL to avoid stampedes/uncacheability
				$mitigationTTL = self::LOW_TTL;
			}
		} else {
			// Case E: new value generated with recent data
			$mitigated = null;
			// Nothing to mitigate
			$mitigationTTL = null;
		}

		if ( $mitigationTTL === self::TTL_UNCACHEABLE ) {
			$this->logger->warning(
				"Rejected set() for {cachekey} due to $mitigated.",
				[
					'cachekey' => $key,
					'lag' => $dataReplicaLag,
					'age' => $dataSnapshotLag,
					'walltime' => $walltime
				]
			);

			// no-op the write for being unsafe
			return true;
		}

		// TTL to use in staleness checks (does not effect persistence layer TTL)
		$logicalTTL = null;

		if ( $mitigationTTL !== null ) {
			// New value was generated from data that is old enough to be risky
			if ( $lockTSE >= 0 ) {
				// Persist the value as long as normal, but make it count as stale sooner
				$logicalTTL = min( $ttl ?: INF, $mitigationTTL );
			} else {
				// Persist the value for a shorter duration
				$ttl = min( $ttl ?: INF, $mitigationTTL );
			}

			$this->logger->warning(
				"Lowered set() TTL for {cachekey} due to $mitigated.",
				[
					'cachekey' => $key,
					'lag' => $dataReplicaLag,
					'age' => $dataSnapshotLag,
					'walltime' => $walltime
				]
			);
		}

		// Wrap that value with time/TTL/version metadata
		$wrapped = $this->wrap( $value, $logicalTTL ?: $ttl, $version, $now, $walltime );
		$storeTTL = $ttl + $staleTTL;

		if ( $creating ) {
			$ok = $this->cache->add(
				$this->makeSisterKey( $key, self::TYPE_VALUE ),
				$wrapped,
				$storeTTL
			);
		} else {
			$ok = $this->cache->merge(
				$this->makeSisterKey( $key, self::TYPE_VALUE ),
				static function ( $cache, $key, $cWrapped ) use ( $wrapped ) {
					// A string value means that it is a tombstone; do nothing in that case
					return ( is_string( $cWrapped ) ) ? false : $wrapped;
				},
				$storeTTL,
				( $this->cache )::MAX_CONFLICTS_ONE
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
	 * recently. For example, user-requested purges or cache cleanup scripts might not need
	 * to invoke a hold-off period on cache backfills, so they can use HOLDOFF_TTL_NONE.
	 *
	 * Note that $ttl limits the effective range of 'lockTSE' for getWithSetCallback().
	 *
	 * If called twice on the same key, then the last hold-off TTL takes precedence. For
	 * idempotence, the $ttl should not vary for different delete() calls on the same key.
	 *
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param int $ttl Tombstone TTL; Default: WANObjectCache::HOLDOFF_TTL
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function delete( $key, $ttl = self::HOLDOFF_TTL ) {
		// Purge values must be stored under the value key so that WANObjectCache::set()
		// can atomically merge values without accidentally undoing a recent purge and thus
		// violating the holdoff TTL restriction.
		$valueSisterKey = $this->makeSisterKey( $key, self::TYPE_VALUE );

		if ( $ttl <= 0 ) {
			// A client or cache cleanup script is requesting a cache purge, so there is no
			// volatility period due to replica DB lag. Any recent change to an entity cached
			// in this key should have triggered an appropriate purge event.
			$ok = $this->relayNonVolatilePurge( $valueSisterKey );
		} else {
			// A cacheable entity recently changed, so there might be a volatility period due
			// to replica DB lag. Clients usually expect their actions to be reflected in any
			// of their subsequent web request. This is attainable if (a) purge relay lag is
			// lower than the time it takes for subsequent request by the client to arrive,
			// and, (b) DB replica queries have "read-your-writes" consistency due to DB lag
			// mitigation systems.
			$now = $this->getCurrentTime();
			// Set the key to the purge value in all datacenters
			$purgeBySisterKey = [ $valueSisterKey => $this->makeTombstonePurgeValue( $now ) ];
			$ok = $this->relayVolatilePurges( $purgeBySisterKey, $ttl );
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
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
	 * The later entities can each use a "check" key to purge their dependee entities.
	 * However, it is expensive for the former entities to verify against all of the relevant
	 * "check" keys during each getWithSetCallback() call. A less expensive approach is to do
	 * these verifications only after a "time-till-verify" (TTV) has passed. This is a middle
	 * ground between using blind TTLs and using constant verification. The adaptiveTTL() method
	 * can be used to dynamically adjust the TTV. Also, the initial TTV can make use of the
	 * last-modified times of the dependent entities (either from the DB or the "check" keys).
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
	 * @param string[] $keys Cache keys made with makeKey()/makeGlobalKey()
	 * @return float[] Map of (key => UNIX timestamp)
	 * @since 1.31
	 */
	final public function getMultiCheckKeyTime( array $keys ) {
		$checkSisterKeysByKey = [];
		foreach ( $keys as $key ) {
			$checkSisterKeysByKey[$key] = $this->makeSisterKey( $key, self::TYPE_TIMESTAMP );
		}

		$wrappedBySisterKey = $this->cache->getMulti( $checkSisterKeysByKey );
		$wrappedBySisterKey += array_fill_keys( $checkSisterKeysByKey, false );

		$now = $this->getCurrentTime();
		$times = [];
		foreach ( $checkSisterKeysByKey as $key => $checkSisterKey ) {
			$purge = $this->parsePurgeValue( $wrappedBySisterKey[$checkSisterKey] );
			if ( $purge === null ) {
				$wrapped = $this->makeCheckPurgeValue( $now, self::HOLDOFF_TTL, $purge );
				$this->cache->add( $checkSisterKey, $wrapped, self::CHECK_KEY_TTL );
			}

			$times[$key] = $purge[self::PURGE_TIME];
		}

		return $times;
	}

	/**
	 * Increase the last-purge timestamp of a "check" key in all datacenters
	 *
	 * This method should only be called when some heavily referenced data changes in
	 * a significant way, such that it is impractical to call delete() on all the cache
	 * keys that should be purged. The get*() method calls used to fetch these keys must
	 * include the given "check" key in the relevant "check" keys argument/option.
	 *
	 * A "check" key essentially represents a last-modified time of an entity. When the
	 * key is touched, the timestamp will be updated to the current time. Keys fetched
	 * using get*() calls, that include the "check" key, will be seen as purged.
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param int $holdoff HOLDOFF_TTL or HOLDOFF_TTL_NONE constant
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function touchCheckKey( $key, $holdoff = self::HOLDOFF_TTL ) {
		$checkSisterKey = $this->makeSisterKey( $key, self::TYPE_TIMESTAMP );

		$now = $this->getCurrentTime();
		$purgeBySisterKey = [ $checkSisterKey => $this->makeCheckPurgeValue( $now, $holdoff ) ];
		$ok = $this->relayVolatilePurges( $purgeBySisterKey, self::CHECK_KEY_TTL );

		$kClass = $this->determineKeyClassForStats( $key );
		$this->stats->increment( "wanobjectcache.$kClass.ck_touch." . ( $ok ? 'ok' : 'error' ) );

		return $ok;
	}

	/**
	 * Clear the last-purge timestamp of a "check" key in all datacenters
	 *
	 * Similar to touchCheckKey(), in that keys fetched using get*() calls, that include
	 * the given "check" key, will be seen as purged. However, there are some differences:
	 *   - a) The "check" key will be deleted from all caches and lazily
	 *        re-initialized when accessed (rather than set everywhere)
	 *   - b) Thus, dependent keys will be known to be stale, but not
	 *        for how long (they are treated as "just" purged), which
	 *        effects any lockTSE logic in getWithSetCallback()
	 *   - c) Since "check" keys are initialized only on the server the key hashes
	 *        to, any temporary ejection of that server will cause the value to be
	 *        seen as purged as a new server will initialize the "check" key.
	 *
	 * The advantage over touchCheckKey() is that the "check" keys, which have high TTLs,
	 * will only be created when a get*() method actually uses those keys. This is better
	 * when a large number of "check" keys must be changed in a short period of time.
	 *
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::touchCheckKey()
	 *
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function resetCheckKey( $key ) {
		$checkSisterKey = $this->makeSisterKey( $key, self::TYPE_TIMESTAMP );
		$ok = $this->relayNonVolatilePurge( $checkSisterKey );

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
	 * The $ttl argument and "hotTTR" option (in $opts) use time-dependent randomization
	 * to avoid stampedes. Keys that are slow to regenerate and either heavily used
	 * or subject to explicit (unpredictable) purges, may need additional mechanisms.
	 * The simplest way to avoid stampedes for such keys is to use 'lockTSE' (in $opts).
	 * If explicit purges are needed, also:
	 *   - a) Pass $key into $checkKeys
	 *   - b) Use touchCheckKey( $key ) instead of delete( $key )
	 *
	 * This applies cache server I/O stampede protection against duplicate cache sets.
	 * This is important when the callback is slow and/or yields large values for a key.
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
	 *             // Calling touchCheckKey() on this key purges the cache
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
	 *              // Calling touchCheckKey() on any of them purges the cache
	 *             'checkKeys' => [
	 *                 $cache->makeKey( 'sustenance-bowls', $cat->getRoomId() ),
	 *                 $cache->makeKey( 'people-present', $cat->getHouseId() ),
	 *                 $cache->makeKey( 'cat-laws', $cat->getCityId() ),
	 *             ]
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (key that is expensive with too many DB dependencies for "check" keys):
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
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
	 *   - graceTTL: If the key is purged (by "checkKeys" or "touchedCallback") less than
	 *      this many seconds ago, consider reusing the stale value. The odds of a refresh become
	 *      more likely over time, becoming certain once the grace period is reached. This can
	 *      reduce traffic spikes when millions of keys are compared to the same "check" key and
	 *      touchCheckKey() or resetCheckKey() is called on that "check" key. This option is not
	 *      useful for avoiding traffic spikes in the case of the key simply expiring on account
	 *      of its TTL (use "lowTTL" instead).
	 *      Default: WANObjectCache::GRACE_TTL_NONE.
	 *   - lockTSE: Prefer the use of a mutex during value regeneration of the key if its TSE
	 *      ("time since expiry") is less than the given number of seconds ago. The TSE of the
	 *      key is influenced by purges (e.g. via delete(), "checkKeys", and "touchedCallback"),
	 *      and various other options (e.g. "staleTTL"). A low enough TSE is assumed to indicate
	 *      a high enough key access rate to justify stampede avoidance. A thread that tries and
	 *      fails to acquire the mutex will use a stale value for the key, if there is one, and,
	 *      if not, it will execute the callback. Note that no cache value exists after deletion
	 *      or storage-layer expiration/eviction; to prevent stampedes during these cases, avoid
	 *      using delete(), keep "lowTTL" enabled, and consider using "busyValue".
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
	 *      as non-existant. Therefore, delete() calls purge the provided key's variant keys.
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
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{checkKeys?:string[],graceTTL?:int,lockTSE?:int,busyValue?:mixed,pcTTL?:int,pcGroup?:string,version?:int,minAsOf?:float|int,hotTTR?:int,lowTTL?:int,ageNew?:int,staleTTL?:int,touchedCallback?:callable} $opts
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
			$cached = $pCache->get( $key, $pcTTL, false );
			if ( $cached !== false ) {
				$this->logger->debug( "getWithSetCallback($key): process cache hit" );
				return $cached;
			}
		}

		[ $value, $valueVersion, $curAsOf ] = $this->fetchOrRegenerate( $key, $ttl, $callback, $opts, $cbParams );
		if ( $valueVersion !== $version ) {
			// Current value has a different version; use the variant key for this version.
			// Regenerate the variant value if it is not newer than the main value at $key
			// so that purges to the main key propagate to the variant value.
			$this->logger->debug( "getWithSetCallback($key): using variant key" );
			list( $value ) = $this->fetchOrRegenerate(
				$this->makeGlobalKey( 'WANCache-key-variant', md5( $key ), (string)$version ),
				$ttl,
				$callback,
				[ 'version' => null, 'minAsOf' => $curAsOf ] + $opts,
				$cbParams
			);
		}

		// Update the process cache if enabled
		if ( $pCache && $value !== false ) {
			$pCache->set( $key, $value );
		}

		return $value;
	}

	/**
	 * Do the actual I/O for getWithSetCallback() when needed
	 *
	 * @see WANObjectCache::getWithSetCallback()
	 *
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
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
		$startTime = $this->getCurrentTime();

		$kClass = $this->determineKeyClassForStats( $key );

		// Get the current key value and its metadata
		$curState = $this->fetchKeys( [ $key ], $checkKeys, $touchedCb )[$key];
		$curValue = $curState[self::RES_VALUE];
		// Use the cached value if it exists and is not due for synchronous regeneration
		if ( $this->isAcceptablyFreshValue( $curState, $graceTTL, $minAsOf ) ) {
			if ( !$this->isLotteryRefreshDue( $curState, $lowTTL, $ageNew, $hotTTR, $startTime ) ) {
				$this->stats->timing(
					"wanobjectcache.$kClass.hit.good",
					1e3 * ( $this->getCurrentTime() - $startTime )
				);

				return [ $curValue, $curState[self::RES_VERSION], $curState[self::RES_AS_OF] ];
			} elseif ( $this->scheduleAsyncRefresh( $key, $ttl, $callback, $opts, $cbParams ) ) {
				$this->logger->debug( "fetchOrRegenerate($key): hit with async refresh" );
				$this->stats->timing(
					"wanobjectcache.$kClass.hit.refresh",
					1e3 * ( $this->getCurrentTime() - $startTime )
				);

				return [ $curValue, $curState[self::RES_VERSION], $curState[self::RES_AS_OF] ];
			} else {
				$this->logger->debug( "fetchOrRegenerate($key): hit with sync refresh" );
			}
		}

		$isKeyTombstoned = ( $curState[self::RES_TOMB_AS_OF] !== null );
		// Use the interim key as an temporary alternative if the key is tombstoned
		if ( $isKeyTombstoned ) {
			$volState = $this->getInterimValue( $key, $minAsOf, $startTime, $touchedCb );
			$volValue = $volState[self::RES_VALUE];
		} else {
			$volState = $curState;
			$volValue = $curValue;
		}

		// During the volatile "hold-off" period that follows a purge of the key, the value
		// will be regenerated many times if frequently accessed. This is done to mitigate
		// the effects of backend replication lag as soon as possible. However, throttle the
		// overhead of locking and regeneration by reusing values recently written to cache
		// tens of milliseconds ago. Verify the "as of" time against the last purge event.
		$lastPurgeTime = max(
			// RES_TOUCH_AS_OF depends on the value (possibly from the interim key)
			$volState[self::RES_TOUCH_AS_OF],
			$curState[self::RES_TOMB_AS_OF],
			$curState[self::RES_CHECK_AS_OF]
		);
		$safeMinAsOf = max( $minAsOf, $lastPurgeTime + self::TINY_POSTIVE );
		if ( $this->isExtremelyNewValue( $volState, $safeMinAsOf, $startTime ) ) {
			$this->logger->debug( "fetchOrRegenerate($key): volatile hit" );
			$this->stats->timing(
				"wanobjectcache.$kClass.hit.volatile",
				1e3 * ( $this->getCurrentTime() - $startTime )
			);

			return [ $volValue, $volState[self::RES_VERSION], $curState[self::RES_AS_OF] ];
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
			// Assume a key is hot if requested soon ($lockTSE seconds) after purge.
			// This avoids stampedes when timestamps from $checkKeys/$touchedCb bump.
			(
				$curState[self::RES_CUR_TTL] !== null &&
				$curState[self::RES_CUR_TTL] <= 0 &&
				abs( $curState[self::RES_CUR_TTL] ) <= $lockTSE
			) ||
			// Assume a key is hot if there is no value and a busy fallback is given.
			// This avoids stampedes on eviction or preemptive regeneration taking too long.
			( $busyValue !== null && $volValue === false );

		// If a regeneration lock is required, threads that do not get the lock will try to use
		// the stale value, the interim value, or the $busyValue placeholder, in that order. If
		// none of those are set then all threads will bypass the lock and regenerate the value.
		$hasLock = $useRegenerationLock && $this->claimStampedeLock( $key );
		if ( $useRegenerationLock && !$hasLock ) {
			// Determine if there is stale or volatile cached value that is still usable
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
			if ( $this->isValid( $volValue, $volState[self::RES_AS_OF], $minAsOf ) ) {
				$this->logger->debug( "fetchOrRegenerate($key): returning stale value" );
				$this->stats->timing(
					"wanobjectcache.$kClass.hit.stale",
					1e3 * ( $this->getCurrentTime() - $startTime )
				);

				return [ $volValue, $volState[self::RES_VERSION], $curState[self::RES_AS_OF] ];
			} elseif ( $busyValue !== null ) {
				$miss = is_infinite( $minAsOf ) ? 'renew' : 'miss';
				$this->logger->debug( "fetchOrRegenerate($key): busy $miss" );
				$this->stats->timing(
					"wanobjectcache.$kClass.$miss.busy",
					1e3 * ( $this->getCurrentTime() - $startTime )
				);
				$placeholderValue = $this->resolveBusyValue( $busyValue );

				return [ $placeholderValue, $version, $curState[self::RES_AS_OF] ];
			}
		}

		// Generate the new value given any prior value with a matching version
		$setOpts = [];
		$preCallbackTime = $this->getCurrentTime();
		++$this->callbackDepth;
		try {
			$value = $callback(
				( $curState[self::RES_VERSION] === $version ) ? $curValue : false,
				$ttl,
				$setOpts,
				( $curState[self::RES_VERSION] === $version ) ? $curState[self::RES_AS_OF] : null,
				$cbParams
			);
		} finally {
			--$this->callbackDepth;
		}
		$postCallbackTime = $this->getCurrentTime();

		// How long it took to fetch, validate, and generate the value
		$elapsed = max( $postCallbackTime - $startTime, 0.0 );

		// How long it took to generate the value
		$walltime = max( $postCallbackTime - $preCallbackTime, 0.0 );
		$this->stats->timing( "wanobjectcache.$kClass.regen_walltime", 1e3 * $walltime );

		// Attempt to save the newly generated value if applicable
		if (
			// Callback yielded a cacheable value
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
			( $value !== false && $ttl >= 0 ) &&
			// Current thread was not raced out of a regeneration lock or key is tombstoned
			( !$useRegenerationLock || $hasLock || $isKeyTombstoned ) &&
			// Key does not appear to be undergoing a set() stampede
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
			$this->checkAndSetCooloff( $key, $kClass, $value, $elapsed, $hasLock )
		) {
			// If the key is write-holed then use the (volatile) interim key as an alternative
			if ( $isKeyTombstoned ) {
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
				$this->setInterimValue( $key, $value, $lockTSE, $version, $postCallbackTime, $walltime );
			} else {
				$finalSetOpts = [
					// @phan-suppress-next-line PhanUselessBinaryAddRight,PhanCoalescingAlwaysNull
					'since' => $setOpts['since'] ?? $preCallbackTime,
					'version' => $version,
					'staleTTL' => $staleTTL,
					// informs lag vs performance trade-offs
					'lockTSE' => $lockTSE,
					// optimization
					'creating' => ( $curValue === false ),
					'walltime' => $walltime
				] + $setOpts;
				// @phan-suppress-next-line PhanTypeMismatchArgument,PhanPossiblyUndeclaredVariable False positive
				$this->set( $key, $value, $ttl, $finalSetOpts );
			}
		}

		$this->yieldStampedeLock( $key, $hasLock );

		$miss = is_infinite( $minAsOf ) ? 'renew' : 'miss';
		$this->logger->debug( "fetchOrRegenerate($key): $miss, new value computed" );
		$this->stats->timing(
			"wanobjectcache.$kClass.$miss.compute",
			1e3 * ( $this->getCurrentTime() - $startTime )
		);

		// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
		return [ $value, $version, $curState[self::RES_AS_OF] ];
	}

	/**
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @return bool Success
	 */
	private function claimStampedeLock( $key ) {
		$checkSisterKey = $this->makeSisterKey( $key, self::TYPE_MUTEX );
		// Note that locking is not bypassed due to I/O errors; this avoids stampedes
		return $this->cache->add( $checkSisterKey, 1, self::LOCK_TTL );
	}

	/**
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param bool $hasLock
	 */
	private function yieldStampedeLock( $key, $hasLock ) {
		if ( $hasLock ) {
			$checkSisterKey = $this->makeSisterKey( $key, self::TYPE_MUTEX );
			$this->cache->changeTTL( $checkSisterKey, (int)$this->getCurrentTime() - 60 );
		}
	}

	/**
	 * Get sister keys that should be collocated with their corresponding base cache keys
	 *
	 * The key will bear the WANCache prefix and use the configured coalescing scheme
	 *
	 * @param string[] $baseKeys Cache keys made with makeKey()/makeGlobalKey()
	 * @param string $type Consistent hashing agnostic suffix character matching [a-zA-Z]
	 * @param string|null $route Routing prefix (optional)
	 * @return string[] Order-corresponding list of sister keys
	 */
	private function makeSisterKeys( array $baseKeys, string $type, string $route = null ) {
		$sisterKeys = [];
		foreach ( $baseKeys as $baseKey ) {
			$sisterKeys[] = $this->makeSisterKey( $baseKey, $type, $route );
		}

		return $sisterKeys;
	}

	/**
	 * Get a sister key that should be collocated with a base cache key
	 *
	 * The keys will bear the WANCache prefix and use the configured coalescing scheme
	 *
	 * @param string $baseKey Cache key made with makeKey()/makeGlobalKey()
	 * @param string $typeChar Consistent hashing agnostic suffix character matching [a-zA-Z]
	 * @param string|null $route Routing prefix (optional)
	 * @return string Sister key
	 */
	private function makeSisterKey( string $baseKey, string $typeChar, string $route = null ) {
		if ( $this->coalesceScheme === self::SCHEME_HASH_STOP ) {
			// Key style: "WANCache:<base key>|#|<character>"
			$sisterKey = 'WANCache:' . $baseKey . '|#|' . $typeChar;
		} else {
			// Key style: "WANCache:{<base key>}:<character>"
			$sisterKey = 'WANCache:{' . $baseKey . '}:' . $typeChar;
		}

		if ( $route !== null ) {
			$sisterKey = $this->prependRoute( $sisterKey, $route );
		}

		return $sisterKey;
	}

	/**
	 * @param string $sisterKey Sister key from makeSisterKey()
	 * @return string Key collection name
	 * @internal For use by WANObjectCache/BagOStuff only
	 * @since 1.36
	 */
	public static function getCollectionFromSisterKey( string $sisterKey ) {
		if ( substr( $sisterKey, -4 ) === '|#|v' ) {
			// Key style: "WANCache:<base key>|#|<character>"
			$collection = substr( $sisterKey, 9, strcspn( $sisterKey, ':|', 9 ) );
		} elseif ( substr( $sisterKey, -3 ) === '}:v' ) {
			// Key style: "WANCache:{<base key>}:<character>"
			$collection = substr( $sisterKey, 10, strcspn( $sisterKey, ':}', 10 ) );
		} else {
			$collection = 'internal';
		}

		return $collection;
	}

	/**
	 * Check if a key value is non-false, new enough, and has an "as of" time almost equal to now
	 *
	 * If the value was just written to cache, and it did not take an unusually long time to
	 * generate, then it is probably not worth regenerating yet. For example, replica databases
	 * might still return lagged pre-purge values anyway.
	 *
	 * @param array $res Current value WANObjectCache::RES_* data map
	 * @param float $minAsOf Minimum acceptable value "as of" UNIX timestamp
	 * @param float $now Current UNIX timestamp
	 * @return bool Whether the age of a volatile value is negligible
	 */
	private function isExtremelyNewValue( $res, $minAsOf, $now ) {
		if ( $res[self::RES_VALUE] === false || $res[self::RES_AS_OF] < $minAsOf ) {
			return false;
		}

		$age = $now - $res[self::RES_AS_OF];

		return ( $age < mt_rand( self::RECENT_SET_LOW_MS, self::RECENT_SET_HIGH_MS ) / 1e3 );
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param string $kClass
	 * @param mixed $value The regenerated value
	 * @param float $elapsed Seconds spent fetching, validating, and regenerating the value
	 * @param bool $hasLock Whether this thread has an exclusive regeneration lock
	 * @return bool Whether it is OK to proceed with a key set operation
	 */
	private function checkAndSetCooloff( $key, $kClass, $value, $elapsed, $hasLock ) {
		$valueSisterKey = $this->makeSisterKey( $key, self::TYPE_VALUE );
		list( $estimatedSize ) = $this->cache->setNewPreparedValues( [
			$valueSisterKey => $value
		] );

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
				$cooloffSisterKey = $this->makeSisterKey( $key, self::TYPE_COOLOFF );
				$watchPoint = $this->cache->watchErrors();
				if (
					!$this->cache->add( $cooloffSisterKey, 1, self::COOLOFF_TTL ) &&
					// Don't treat failures due to I/O errors as the key being in cool-off
					$this->cache->getLastError( $watchPoint ) === self::ERR_NONE
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param float $minAsOf Minimum acceptable value "as of" UNIX timestamp
	 * @param float $now Fetch time to determine "age" metadata
	 * @param callable|null $touchedCb Function to find the max "dependency touched" UNIX timestamp
	 * @return array<int,mixed> Result map/n-tuple from unwrap()
	 * @phan-return array{0:mixed,1:mixed,2:?float,3:?int,4:?float,5:?float,6:?float,7:?float}
	 * @note Callable type hints are not used to avoid class-autoloading
	 */
	private function getInterimValue( $key, $minAsOf, $now, $touchedCb ) {
		if ( $this->useInterimHoldOffCaching ) {
			$interimSisterKey = $this->makeSisterKey( $key, self::TYPE_INTERIM );
			$wrapped = $this->cache->get( $interimSisterKey );
			$res = $this->unwrap( $wrapped, $now );
			if ( $res[self::RES_VALUE] !== false && $res[self::RES_AS_OF] >= $minAsOf ) {
				if ( $touchedCb !== null ) {
					// Update "last purge time" since the $touchedCb timestamp depends on $value
					// Get the new "touched timestamp", accounting for callback-checked dependencies
					$res[self::RES_TOUCH_AS_OF] = max(
						$touchedCb( $res[self::RES_VALUE] ),
						$res[self::RES_TOUCH_AS_OF]
					);
				}

				return $res;
			}
		}

		return $this->unwrap( false, $now );
	}

	/**
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param mixed $value
	 * @param int $ttl
	 * @param int|null $version Value version number
	 * @param float $now Time after value regen
	 * @param float $walltime How long it took to generate the value in seconds
	 */
	private function setInterimValue( $key, $value, $ttl, $version, $now, $walltime ) {
		$ttl = max( self::INTERIM_KEY_TTL, (int)$ttl );

		$wrapped = $this->wrap( $value, $ttl, $version, $now, $walltime );
		$this->cache->set(
			$this->makeSisterKey( $key, self::TYPE_INTERIM ),
			$wrapped,
			$ttl
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
	 *   - b) The $callback argument expects a function that returns an entity value, using
	 *        boolean "false" if it does not exist. The callback takes the following arguments:
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
	 * @param callable $callback Callback that yields entity generation callbacks
	 * @param array $opts Options map similar to that of getWithSetCallback()
	 * @return mixed[] Map of (cache key => value) in the same order as $keyedIds
	 * @since 1.28
	 */
	final public function getMultiWithSetCallback(
		ArrayIterator $keyedIds, $ttl, callable $callback, array $opts = []
	) {
		// Batch load required keys into the in-process warmup cache
		$this->warmupCache = $this->fetchWrappedValuesForWarmupCache(
			$this->getNonProcessCachedMultiKeys( $keyedIds, $opts ),
			$opts['checkKeys'] ?? []
		);
		$this->warmupKeyMisses = 0;

		// The required callback signature includes $id as the first argument for convenience
		// to distinguish different items. To reuse the code in getWithSetCallback(), wrap the
		// callback with a proxy callback that has the standard getWithSetCallback() signature.
		// This is defined only once per batch to avoid closure creation overhead.
		$proxyCb = static function ( $oldValue, &$ttl, &$setOpts, $oldAsOf, $params )
			use ( $callback )
		{
			return $callback( $params['id'], $oldValue, $ttl, $setOpts, $oldAsOf );
		};

		// Get the order-preserved result map using the warm-up cache
		$values = [];
		foreach ( $keyedIds as $key => $id ) {
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
	 *   - b) The $callback argument expects a function that returns a map of (ID => new value),
	 *        using boolean "false" for entities that could not be found, for all entity IDs in
	 *        $ids. The callback takes the following arguments:
	 *          - $ids: list of entity IDs that require value generation
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
	 *             $rows = array_fill_keys( $ids, false );
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
	 * @param callable $callback Callback that yields entity generation callbacks
	 * @param array $opts Options map similar to that of getWithSetCallback()
	 * @return mixed[] Map of (cache key => value) in the same order as $keyedIds
	 * @since 1.30
	 */
	final public function getMultiWithUnionSetCallback(
		ArrayIterator $keyedIds, $ttl, callable $callback, array $opts = []
	) {
		$checkKeys = $opts['checkKeys'] ?? [];
		$minAsOf = $opts['minAsOf'] ?? self::MIN_TIMESTAMP_NONE;

		// unset incompatible keys
		unset( $opts['lockTSE'] );
		unset( $opts['busyValue'] );

		// Batch load required keys into the in-process warmup cache
		$keysByIdGet = $this->getNonProcessCachedMultiKeys( $keyedIds, $opts );
		$this->warmupCache = $this->fetchWrappedValuesForWarmupCache( $keysByIdGet, $checkKeys );
		$this->warmupKeyMisses = 0;

		// IDs of entities known to be in need of generation
		$idsRegen = [];

		// Find out which keys are missing/deleted/stale
		$resByKey = $this->fetchKeys( $keysByIdGet, $checkKeys );
		foreach ( $keysByIdGet as $id => $key ) {
			$res = $resByKey[$key];
			if (
				$res[self::RES_VALUE] === false ||
				$res[self::RES_CUR_TTL] < 0 ||
				$res[self::RES_AS_OF] < $minAsOf
			) {
				$idsRegen[] = $id;
			}
		}

		// Run the callback to populate the generation value map for all required IDs
		$newSetOpts = [];
		$newTTLsById = array_fill_keys( $idsRegen, $ttl );
		$newValsById = $idsRegen ? $callback( $idsRegen, $newTTLsById, $newSetOpts ) : [];

		$method = __METHOD__;
		// The required callback signature includes $id as the first argument for convenience
		// to distinguish different items. To reuse the code in getWithSetCallback(), wrap the
		// callback with a proxy callback that has the standard getWithSetCallback() signature.
		// This is defined only once per batch to avoid closure creation overhead.
		$proxyCb = function ( $oldValue, &$ttl, &$setOpts, $oldAsOf, $params )
			use ( $callback, $newValsById, $newTTLsById, $newSetOpts, $method )
		{
			$id = $params['id'];

			if ( array_key_exists( $id, $newValsById ) ) {
				// Value was already regenerated as expected, so use the value in $newValsById
				$newValue = $newValsById[$id];
				$ttl = $newTTLsById[$id];
				$setOpts = $newSetOpts;
			} else {
				// Pre-emptive/popularity refresh and version mismatch cases are not detected
				// above and thus $newValsById has no entry. Run $callback on this single entity.
				$ttls = [ $id => $ttl ];
				$result = $callback( [ $id ], $ttls, $setOpts );
				if ( !isset( $result[$id] ) ) {
					// T303092
					$this->logger->warning(
						$method . ' failed due to {id} not set in result {result}', [
						'id' => $id,
						'result' => json_encode( $result )
					] );
				}
				$newValue = $result[$id];
				$ttl = $ttls[$id];
			}

			return $newValue;
		};

		// Get the order-preserved result map using the warm-up cache
		$values = [];
		foreach ( $keyedIds as $key => $id ) {
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param int|float $purgeTimestamp UNIX timestamp of purge
	 * @param bool &$isStale Whether the key is stale
	 * @return bool Success
	 * @since 1.28
	 */
	final public function reap( $key, $purgeTimestamp, &$isStale = false ) {
		$valueSisterKey = $this->makeSisterKey( $key, self::TYPE_VALUE );

		$minAsOf = $purgeTimestamp + self::HOLDOFF_TTL;
		$wrapped = $this->cache->get( $valueSisterKey );
		if ( is_array( $wrapped ) && $wrapped[self::FLD_TIME] < $minAsOf ) {
			$isStale = true;
			$this->logger->warning( "Reaping stale value key '$key'." );
			// avoids races with tombstone creation
			$ttlReap = self::HOLDOFF_TTL;
			$ok = $this->cache->changeTTL( $valueSisterKey, $ttlReap );
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
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param int $purgeTimestamp UNIX timestamp of purge
	 * @param bool &$isStale Whether the key is stale
	 * @return bool Success
	 * @since 1.28
	 */
	final public function reapCheckKey( $key, $purgeTimestamp, &$isStale = false ) {
		$checkSisterKey = $this->makeSisterKey( $key, self::TYPE_TIMESTAMP );

		$wrapped = $this->cache->get( $checkSisterKey );
		$purge = $this->parsePurgeValue( $wrapped );
		if ( $purge !== null && $purge[self::PURGE_TIME] < $purgeTimestamp ) {
			$isStale = true;
			$this->logger->warning( "Reaping stale check key '$key'." );
			$ok = $this->cache->changeTTL( $checkSisterKey, self::TTL_SECOND );
			if ( !$ok ) {
				$this->logger->error( "Could not complete reap of check key '$key'." );
			}

			return $ok;
		}

		$isStale = false;

		return false;
	}

	/**
	 * Make a cache key for the global keyspace and given components
	 *
	 * @see IStoreKeyEncoder::makeGlobalKey()
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 * @since 1.27
	 */
	public function makeGlobalKey( $collection, ...$components ) {
		return $this->cache->makeGlobalKey( ...func_get_args() );
	}

	/**
	 * Make a cache key using the "global" keyspace for the given components
	 *
	 * @see IStoreKeyEncoder::makeKey()
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 * @since 1.27
	 */
	public function makeKey( $collection, ...$components ) {
		return $this->cache->makeKey( ...func_get_args() );
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
	 *             return self::isCentral( $filter )
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
	 * Key generation method must utilize the *full* entity ID in the key (not a hash of it).
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
			$ids = array_keys( array_fill_keys( $ids, true ) );
			if ( count( $ids ) !== count( $res ) ) {
				throw new UnexpectedValueException( "Multi-key result does not match ID list" );
			}
		}

		return array_combine( $ids, $res );
	}

	/**
	 * Get a "watch point" token that can be used to get the "last error" to occur after now
	 *
	 * @return int A token that the current error event
	 * @since 1.38
	 */
	public function watchErrors() {
		return $this->cache->watchErrors();
	}

	/**
	 * Get the "last error" registry
	 *
	 * The method should be invoked by a caller as part of the following pattern:
	 *   - The caller invokes watchErrors() to get a "since token"
	 *   - The caller invokes a sequence of cache operation methods
	 *   - The caller invokes getLastError() with the "since token"
	 *
	 * External callers can also invoke this method as part of the following pattern:
	 *   - The caller invokes clearLastError()
	 *   - The caller invokes a sequence of cache operation methods
	 *   - The caller invokes getLastError()
	 *
	 * @param int $watchPoint Only consider errors from after this "watch point" [optional]
	 * @return int BagOStuff:ERR_* constant for the "last error" registry
	 * @note Parameters added in 1.38: $watchPoint
	 */
	final public function getLastError( $watchPoint = 0 ) {
		$code = $this->cache->getLastError( $watchPoint );
		switch ( $code ) {
			case self::ERR_NONE:
				return self::ERR_NONE;
			case self::ERR_NO_RESPONSE:
				return self::ERR_NO_RESPONSE;
			case self::ERR_UNREACHABLE:
				return self::ERR_UNREACHABLE;
			default:
				return self::ERR_UNEXPECTED;
		}
	}

	/**
	 * Clear the "last error" registry
	 * @deprecated Since 1.38
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
	 * @param int|float|string|null $mtime UNIX timestamp; null if none
	 * @param int $maxTTL Maximum TTL (seconds)
	 * @param int $minTTL Minimum TTL (seconds); Default: 30
	 * @param float $factor Value in the range (0,1); Default: .2
	 * @return int Adaptive TTL
	 * @since 1.28
	 */
	public function adaptiveTTL( $mtime, $maxTTL, $minTTL = 30, $factor = 0.2 ) {
		// handle fractional seconds and string integers
		$mtime = (int)$mtime;
		if ( $mtime <= 0 ) {
			// no last-modified time provided
			return $minTTL;
		}

		$age = (int)$this->getCurrentTime() - $mtime;

		return (int)min( $maxTTL, max( $minTTL, $factor * $age ) );
	}

	/**
	 * @internal For use by unit tests only
	 * @return int
	 * @since 1.30
	 */
	final public function getWarmupKeyMisses() {
		// Number of misses in $this->warmupCache during the last call to certain methods
		return $this->warmupKeyMisses;
	}

	/**
	 * Set a sister key to a purge value in all datacenters
	 *
	 * This method should not wait for the operation to complete on remote datacenters
	 *
	 * Since older purge values can sometimes arrive after newer ones, use a relative expiry
	 * so that even if the older value replaces the newer value, the TTL will greater than the
	 * remaining TTL on the older value (assuming that all purges for a key use the same TTL).
	 *
	 * @param array<string,string> $purgeBySisterKey Map of
	 *  (sister key => result of makeTombstonePurgeValue()/makeCheckKeyPurgeValue())
	 * @param int $ttl Seconds to keep the purge value around
	 * @return bool Success
	 */
	protected function relayVolatilePurges( array $purgeBySisterKey, int $ttl ) {
		$purgeByRouteKey = [];
		foreach ( $purgeBySisterKey as $sisterKey => $purge ) {
			if ( $this->broadcastRoute !== null ) {
				$routeKey = $this->prependRoute( $sisterKey, $this->broadcastRoute );
			} else {
				$routeKey = $sisterKey;
			}
			$purgeByRouteKey[$routeKey] = $purge;
		}

		if ( count( $purgeByRouteKey ) == 1 ) {
			$purge = reset( $purgeByRouteKey );
			$ok = $this->cache->set( key( $purgeByRouteKey ), $purge, $ttl );
		} else {
			$ok = $this->cache->setMulti( $purgeByRouteKey, $ttl );
		}

		return $ok;
	}

	/**
	 * Remove a sister key from all datacenters
	 *
	 * This method should not wait for the operation to complete on remote datacenters
	 *
	 * @param string $sisterKey A value key or "check" key
	 * @return bool Success
	 */
	protected function relayNonVolatilePurge( string $sisterKey ) {
		if ( $this->broadcastRoute !== null ) {
			$routeKey = $this->prependRoute( $sisterKey, $this->broadcastRoute );
		} else {
			$routeKey = $sisterKey;
		}

		return $this->cache->delete( $routeKey );
	}

	/**
	 * @param string $sisterKey
	 * @param string $route Key routing prefix
	 * @return string
	 */
	protected function prependRoute( string $sisterKey, string $route ) {
		if ( $sisterKey[0] === '/' ) {
			throw new RuntimeException( "Sister key '$sisterKey' already contains a route." );
		}

		return $route . $sisterKey;
	}

	/**
	 * Schedule a deferred cache regeneration if possible
	 *
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
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
			try {
				$this->fetchOrRegenerate( $key, $ttl, $callback, $opts, $cbParams );
			} catch ( Exception $e ) {
				// Log some context for easier debugging
				$this->logger->error( 'Async refresh failed for {key}', [
					'key' => $key,
					'ttl' => $ttl,
					'exception' => $e
				] );
				throw $e;
			}
		} );

		return true;
	}

	/**
	 * Check if a key value is non-false, new enough, and either fresh or "gracefully" stale
	 *
	 * @param array $res Current value WANObjectCache::RES_* data map
	 * @param int $graceTTL Consider using stale values if $curTTL is greater than this
	 * @param float $minAsOf Minimum acceptable value "as of" UNIX timestamp
	 * @return bool
	 */
	private function isAcceptablyFreshValue( $res, $graceTTL, $minAsOf ) {
		if ( !$this->isValid( $res[self::RES_VALUE], $res[self::RES_AS_OF], $minAsOf ) ) {
			// Value does not exists or is too old
			return false;
		}

		$curTTL = $res[self::RES_CUR_TTL];
		if ( $curTTL > 0 ) {
			// Value is definitely still fresh
			return true;
		}

		// Remaining seconds during which this stale value can be used
		$curGraceTTL = $graceTTL + $curTTL;

		return ( $curGraceTTL > 0 )
			// Chance of using the value decreases as $curTTL goes from 0 to -$graceTTL
			? !$this->worthRefreshExpiring( $curGraceTTL, $graceTTL, $graceTTL )
			// Value is too stale to fall in the grace period
			: false;
	}

	/**
	 * Check if a key is due for randomized regeneration due to near-expiration/popularity
	 *
	 * @param array $res Current value WANObjectCache::RES_* data map
	 * @param float $lowTTL Consider a refresh when $curTTL is less than this; the "low" threshold
	 * @param int $ageNew Age of key when this might recommend refreshing (seconds)
	 * @param int $hotTTR Age of key when it should be refreshed if popular (seconds)
	 * @param float $now The current UNIX timestamp
	 * @return bool
	 */
	protected function isLotteryRefreshDue( $res, $lowTTL, $ageNew, $hotTTR, $now ) {
		$curTTL = $res[self::RES_CUR_TTL];
		$logicalTTL = $res[self::RES_TTL];
		$asOf = $res[self::RES_AS_OF];

		return (
			$this->worthRefreshExpiring( $curTTL, $logicalTTL, $lowTTL ) ||
			$this->worthRefreshPopular( $asOf, $ageNew, $hotTTR, $now )
		);
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
		$refreshWindowSec = max( $timeTillRefresh - $ageNew - self::RAMPUP_TTL / 2, 1 );
		// P(refresh) * (# hits in $refreshWindowSec) = (expected # of refreshes)
		// P(refresh) * ($refreshWindowSec * $popularHitsPerSec) = 1 (by definition)
		// P(refresh) = 1/($refreshWindowSec * $popularHitsPerSec)
		$chance = 1 / ( $popularHitsPerSec * $refreshWindowSec );
		// Ramp up $chance from 0 to its nominal value over RAMPUP_TTL seconds to avoid stampedes
		$chance *= ( $timeOld <= self::RAMPUP_TTL ) ? $timeOld / self::RAMPUP_TTL : 1;

		return ( mt_rand( 1, 1000000000 ) <= 1000000000 * $chance );
	}

	/**
	 * Check if a key is nearing expiration and thus due for randomized regeneration
	 *
	 * If $curTTL is greater than the "low" threshold (e.g. not nearing expiration) then this
	 * returns false. If $curTTL <= 0 (e.g. value already expired), then this returns false.
	 * Otherwise, the chance of this returning true increases steadily from 0% to 100% as
	 * $curTTL moves from the "low" threshold down to 0 seconds.
	 *
	 * The logical TTL will be used as the "low" threshold if it is less than $lowTTL.
	 *
	 * This method uses deadline-aware randomization in order to handle wide variations
	 * of cache access traffic without the need for configuration or expensive state.
	 *
	 * @param float $curTTL Approximate TTL left on the key
	 * @param float $logicalTTL Full logical TTL assigned to the key
	 * @param float $lowTTL Consider a refresh when $curTTL is less than this; the "low" threshold
	 * @return bool
	 */
	protected function worthRefreshExpiring( $curTTL, $logicalTTL, $lowTTL ) {
		if ( $lowTTL <= 0 ) {
			return false;
		}

		// T264787: avoid having keys start off with a high chance of being refreshed;
		// the point where refreshing becomes possible cannot precede the key lifetime.
		$effectiveLowTTL = min( $lowTTL, $logicalTTL ?: INF );

		if ( $curTTL >= $effectiveLowTTL || $curTTL <= 0 ) {
			return false;
		}

		$chance = ( 1 - $curTTL / $effectiveLowTTL );

		return ( mt_rand( 1, 1000000000 ) <= 1000000000 * $chance );
	}

	/**
	 * Check that a wrapper value exists and has an acceptable age
	 *
	 * @param array|false $value Value wrapper or false
	 * @param float $asOf Value generation "as of" timestamp
	 * @param float $minAsOf Minimum acceptable value "as of" UNIX timestamp
	 * @return bool
	 */
	protected function isValid( $value, $asOf, $minAsOf ) {
		return ( $value !== false && $asOf >= $minAsOf );
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
			self::FLD_FORMAT_VERSION => self::VERSION,
			self::FLD_VALUE => $value,
			self::FLD_TTL => $ttl,
			self::FLD_TIME => $now
		];
		if ( $version !== null ) {
			$wrapped[self::FLD_VALUE_VERSION] = $version;
		}
		if ( $walltime >= self::GENERATION_SLOW_SEC ) {
			$wrapped[self::FLD_GENERATION_TIME] = $walltime;
		}

		return $wrapped;
	}

	/**
	 * @param array|string|false $wrapped The entry at a cache key (false if key is nonexistant)
	 * @param float $now Unix Current timestamp (preferably pre-query)
	 * @return array<int,mixed> Result map/n-tuple that includes the following:
	 *   - WANObjectCache::RES_VALUE: value or false if absent/tombstoned/malformed
	 *   - WANObjectCache::KEY_VERSION: value version number; null if there is no value
	 *   - WANObjectCache::KEY_AS_OF: value generation timestamp (UNIX); null if there is no value
	 *   - WANObjectCache::KEY_TTL: assigned logical TTL (seconds); null if there is no value
	 *   - WANObjectCache::KEY_TOMB_AS_OF: tombstone timestamp (UNIX); null if not tombstoned
	 *   - WANObjectCache::RES_CHECK_AS_OF: null placeholder for highest "check" key timestamp
	 *   - WANObjectCache::RES_TOUCH_AS_OF: null placeholder for highest "touched" timestamp
	 *   - WANObjectCache::KEY_CUR_TTL: remaining logical TTL (seconds) (negative if tombstoned)
	 * @phan-return array{0:mixed,1:mixed,2:?float,3:?int,4:?float,5:?float,6:?float,7:?float}
	 */
	private function unwrap( $wrapped, $now ) {
		// https://nikic.github.io/2014/12/22/PHPs-new-hashtable-implementation.html
		$res = [
			// Attributes that only depend on the fetched key value
			self::RES_VALUE => false,
			self::RES_VERSION => null,
			self::RES_AS_OF => null,
			self::RES_TTL => null,
			self::RES_TOMB_AS_OF => null,
			// Attributes that depend on caller-specific "check" keys or "touched callbacks"
			self::RES_CHECK_AS_OF => null,
			self::RES_TOUCH_AS_OF => null,
			self::RES_CUR_TTL => null
		];

		if ( is_array( $wrapped ) ) {
			// Entry expected to be a cached value; validate it
			if (
				( $wrapped[self::FLD_FORMAT_VERSION] ?? null ) === self::VERSION &&
				$wrapped[self::FLD_TIME] >= $this->epoch
			) {
				if ( $wrapped[self::FLD_TTL] > 0 ) {
					// Get the approximate time left on the key
					$age = $now - $wrapped[self::FLD_TIME];
					$curTTL = max( $wrapped[self::FLD_TTL] - $age, 0.0 );
				} else {
					// Key had no TTL, so the time left is unbounded
					$curTTL = INF;
				}
				$res[self::RES_VALUE] = $wrapped[self::FLD_VALUE];
				$res[self::RES_VERSION] = $wrapped[self::FLD_VALUE_VERSION] ?? null;
				$res[self::RES_AS_OF] = $wrapped[self::FLD_TIME];
				$res[self::RES_CUR_TTL] = $curTTL;
				$res[self::RES_TTL] = $wrapped[self::FLD_TTL];
			}
		} else {
			// Entry expected to be a tombstone; parse it
			$purge = $this->parsePurgeValue( $wrapped );
			if ( $purge !== null ) {
				// Tombstoned keys should always have a negative "current TTL"
				$curTTL = min( $purge[self::PURGE_TIME] - $now, self::TINY_NEGATIVE );
				$res[self::RES_CUR_TTL] = $curTTL;
				$res[self::RES_TOMB_AS_OF] = $purge[self::PURGE_TIME];
			}
		}

		return $res;
	}

	/**
	 * @param string $key String of the format <scope>:<collection>[:<constant or variable>]...
	 * @return string A collection name to describe this class of key
	 */
	private function determineKeyClassForStats( $key ) {
		$parts = explode( ':', $key, 3 );
		// Fallback in case the key was not made by makeKey.
		// Replace dots because they are special in StatsD (T232907)
		return strtr( $parts[1] ?? $parts[0], '.', '_' );
	}

	/**
	 * Extract purge metadata from cached value if it is a valid purge value
	 *
	 * Valid purge values come from makeTombstonePurgeValue()/makeCheckKeyPurgeValue()
	 *
	 * @param mixed $value Cached value
	 * @return array|null Tuple of (UNIX timestamp, hold-off seconds); null if value is invalid
	 */
	private function parsePurgeValue( $value ) {
		if ( !is_string( $value ) ) {
			return null;
		}

		$segments = explode( ':', $value, 3 );
		$prefix = $segments[0];
		if ( $prefix !== self::PURGE_VAL_PREFIX ) {
			// Not a purge value
			return null;
		}

		$timestamp = (float)$segments[1];
		// makeTombstonePurgeValue() doesn't store hold-off TTLs
		$holdoff = isset( $segments[2] ) ? (int)$segments[2] : self::HOLDOFF_TTL;

		if ( $timestamp < $this->epoch ) {
			// Purge value is too old
			return null;
		}

		return [ self::PURGE_TIME => $timestamp, self::PURGE_HOLDOFF => $holdoff ];
	}

	/**
	 * @param float $timestamp UNIX timestamp
	 * @return string Wrapped purge value; format is "PURGED:<timestamp>"
	 */
	private function makeTombstonePurgeValue( float $timestamp ) {
		return self::PURGE_VAL_PREFIX . ':' . (int)$timestamp;
	}

	/**
	 * @param float $timestamp UNIX timestamp
	 * @param int $holdoff In seconds
	 * @param array|null &$purge Unwrapped purge value array [returned]
	 * @return string Wrapped purge value; format is "PURGED:<timestamp>:<holdoff>"
	 */
	private function makeCheckPurgeValue( float $timestamp, int $holdoff, array &$purge = null ) {
		$normalizedTime = (int)$timestamp;
		// Purge array that matches what parsePurgeValue() would have returned
		$purge = [ self::PURGE_TIME => (float)$normalizedTime, self::PURGE_HOLDOFF => $holdoff ];

		return self::PURGE_VAL_PREFIX . ":$normalizedTime:$holdoff";
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
	 * @param ArrayIterator $keys
	 * @param array $opts
	 * @return string[] Map of (ID => cache key)
	 */
	private function getNonProcessCachedMultiKeys( ArrayIterator $keys, array $opts ) {
		$pcTTL = $opts['pcTTL'] ?? self::TTL_UNCACHEABLE;

		$keysMissing = [];
		if ( $pcTTL > 0 && $this->callbackDepth == 0 ) {
			$pCache = $this->getProcessCache( $opts['pcGroup'] ?? self::PC_PRIMARY );
			foreach ( $keys as $key => $id ) {
				if ( !$pCache->has( $key, $pcTTL ) ) {
					$keysMissing[$id] = $key;
				}
			}
		}

		return $keysMissing;
	}

	/**
	 * @param string[] $keys Cache keys made with makeKey()/makeGlobalKey()
	 * @param string[]|string[][] $checkKeys Map of (integer or cache key => "check" key(s));
	 *  "check" keys must also be made with makeKey()/makeGlobalKey()
	 * @return array<string,mixed> Map of (sister key => value, or, false if not found)
	 */
	private function fetchWrappedValuesForWarmupCache( array $keys, array $checkKeys ) {
		if ( !$keys ) {
			return [];
		}

		// Get all the value keys to fetch...
		$sisterKeys = $this->makeSisterKeys( $keys, self::TYPE_VALUE );
		// Get all the "check" keys to fetch...
		foreach ( $checkKeys as $i => $checkKeyOrKeyGroup ) {
			// Note: avoid array_merge() inside loop in case there are many keys
			if ( is_int( $i ) ) {
				// Single "check" key that applies to all value keys
				$sisterKeys[] = $this->makeSisterKey( $checkKeyOrKeyGroup, self::TYPE_TIMESTAMP );
			} else {
				// List of "check" keys that apply to a specific value key
				foreach ( (array)$checkKeyOrKeyGroup as $checkKey ) {
					$sisterKeys[] = $this->makeSisterKey( $checkKey, self::TYPE_TIMESTAMP );
				}
			}
		}

		$wrappedBySisterKey = $this->cache->getMulti( $sisterKeys );
		$wrappedBySisterKey += array_fill_keys( $sisterKeys, false );

		return $wrappedBySisterKey;
	}

	/**
	 * @param string $key Cache key made with makeKey()/makeGlobalKey()
	 * @param float $now Current UNIX timestamp
	 * @return float|null Seconds since the last logged get() miss for this key, or, null
	 */
	private function timeSinceLoggedMiss( $key, $now ) {
		for ( end( $this->missLog ); $miss = current( $this->missLog ); prev( $this->missLog ) ) {
			if ( $miss[0] === $key ) {
				return ( $now - $miss[1] );
			}
		}

		return null;
	}

	/**
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	protected function getCurrentTime() {
		return $this->wallClockOverride ?: microtime( true );
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
