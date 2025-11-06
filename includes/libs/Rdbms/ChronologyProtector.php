<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * Provide a given client with protection against visible database lag.
 *
 * ### In a nut shell
 *
 * This class tries to hide visible effects of database lag. It does this by temporarily remembering
 * the database positions after a client makes a write, and on their next web request we will prefer
 * non-lagged database replicas. When replica connections are established, we wait up to a few seconds
 * for sufficient replication to have occurred, if they were not yet caught up to that same point.
 *
 * This ensures a consistent ordering of events as seen by a client. Kind of like Hawking's
 * [Chronology Protection Agency](https://en.wikipedia.org/wiki/Chronology_protection_conjecture).
 *
 * ### Purpose
 *
 * For performance and scalability reasons, almost all data is queried from replica databases.
 * Only queries relating to writing data, are sent to a primary database. When rendering a web page
 * with content or activity feeds on it, the very latest information may thus not yet be there.
 * That's okay in general, but if, for example, a client recently changed their preferences or
 * submitted new data, we do our best to make sure their next web response does reflect at least
 * their own recent changes.
 *
 * ### How
 *
 * To explain how it works, we will look at an example lifecycle for a client.
 *
 * A client is browsing the site. Their web requests are generally read-only and display data from
 * database replicas, which may be a few seconds out of date if a client elsewhere in the world
 * recently modified that same data. If the application is run from multiple data centers, then
 * these web requests may be served from the nearest secondary DC.
 *
 * A client performs a POST request, perhaps to publish an edit or change their preferences. This
 * request is routed to the primary DC (this is the responsibility of infrastructure outside
 * the web app). There, the data is saved to the primary database, after which the database
 * host will asynchronously replicate this to its replicas in the same and any other DCs.
 *
 * Toward the end of the response to this POST request, the application takes note of the primary
 * database's current "position", and save this under a "clientId" key in the ChronologyProtector
 * store. The web response will also set two cookies that are similarly short-lived (about ten
 * seconds): `UseDC=master` and `cpPosIndex=<posIndex>@<write time>#<clientId>`.
 *
 * The ten seconds window is meant to account for the time needed for the database writes to have
 * replicated across all active database replicas, including the cross-dc latency for those
 * further away in any secondary DCs. The "clientId" is placed in the cookie to handle the case
 * where the client IP addresses frequently changes between web requests.
 *
 * Future web requests from the client should fall in one of two categories:
 *
 * 1. Within the ten second window. Their UseDC cookie will make them return
 *    to the primary DC where we access the ChronologyProtector store and use
 *    the database "position" to decide which local database replica to use
 *    and on-demand wait a split second for replication to catch up if needed.
 * 2. After the ten second window. They will be routed to the nearest and
 *    possibly different DC. Any local ChronologyProtector store existing there
 *    will not be interacted with. A random database replica may be used as
 *    the client's own writes are expected to have been applied here by now.
 *
 * @anchor ChronologyProtector-storage-requirements
 *
 * ### Storage requirements
 *
 * The store used by ChronologyProtector, as configured via {@link $wgMicroStashType},
 * should meet the following requirements:
 *
 * - Low latencies. Nearly all web requests that involve a database connection will
 *   unconditionally query this store first. It is expected to respond within the order
 *   of one millisecond.
 * - Best effort persistence, without active eviction pressure. Data stored here cannot be
 *   obtained elsewhere or recomputed. As such, under normal operating conditions, this store
 *   should not be full, and should not evict values before their intended expiry time elapsed.
 * - No replication, local consistency. Each DC may have a fully independent dc-local store
 *   associated with ChronologyProtector (no replication across DCs is needed). Local writes
 *   must be immediately reflected in subsequent local reads. No intra-dc read lag is allowed.
 * - No redundancy, fast failure. Loss of data will likely be noticeable and disruptive to
 *   clients, but the data is not considered essential. Under maintenance or unprecedented load,
 *   it is recommended to lose some data, instead of compromising other requirements such as
 *   latency or availability for new writes. The fallback is that users may be temporary
 *   confused as they observe their own actions as not being immediately reflected.
 *   For example, they might change their skin or language preference but still get a one or two
 *   page views afterward with the old settings. Or they might have published an edit and briefly
 *   not yet see it appear in their contribution history.
 *
 * ### Operational requirements
 *
 * These are the expectations a site administrator must meet for chronology protection:
 *
 * - If the application is run from multiple data centers, then you must designate one of them
 *   as the "primary DC". The primary DC is where the primary database is located, from which
 *   replication propagates to replica databases in that same DC and any other DCs.
 *
 * - Web requests that use the POST verb, or carry a `UseDC=master` cookie, must be routed to
 *   the primary DC only.
 *
 *   An exception is requests carrying the `Promise-Non-Write-API-Action: true` header,
 *   which use the POST verb for large read queries, but don't actually require the primary DC.
 *
 *   If you have legacy extensions deployed that perform queries on the primary database during
 *   GET requests, then you will have to identify a way to route any of its relevant URLs to the
 *   primary DC as well, or to accept that their reads do not enjoy chronology protection, and
 *   that writes may be slower (due to cross-dc latency).
 *   See [T91820](https://phabricator.wikimedia.org/T91820) for %Wikimedia Foundation's routing.
 *
 * @ingroup Database
 * @internal
 */
class ChronologyProtector {
	/** @var array Web request information about the client */
	private $requestInfo;
	/** @var string Secret string for HMAC hashing */
	private string $secret;
	private bool $cliMode;
	/** @var BagOStuff */
	private $store;
	/** @var LoggerInterface */
	protected $logger;

	/** @var string Storage key name */
	protected $key;
	/** @var string Hash of client parameters */
	protected $clientId;
	/** @var string[] Map of client information fields for logging */
	protected $clientLogInfo;
	/** @var int|null Expected minimum index of the last write to the position store */
	protected $waitForPosIndex;

	/** @var bool Whether reading/writing session consistency replication positions is enabled */
	protected $enabled = true;

	/** @var array<string,DBPrimaryPos|null> Map of (primary server name => position) */
	protected $startupPositionsByPrimary = null;
	/** @var array<string,DBPrimaryPos|null> Map of (primary server name => position) */
	protected $shutdownPositionsByPrimary = [];

	/**
	 * Whether a clientId is new during this request.
	 *
	 * If the clientId wasn't passed by the incoming request, lazyStartup()
	 * can skip fetching position data, and thus LoadBalancer can skip
	 * its IDatabaseForOwner::primaryPosWait() call.
	 *
	 * See also: <https://phabricator.wikimedia.org/T314434>
	 *
	 * @var bool
	 */
	private $hasNewClientId = false;

	/** Seconds to store position write index cookies (safely less than POSITION_STORE_TTL) */
	public const POSITION_COOKIE_TTL = 10;
	/** Seconds to store replication positions */
	private const POSITION_STORE_TTL = 60;

	/** Lock timeout to use for key updates */
	private const LOCK_TIMEOUT = 3;
	/** Lock expiry to use for key updates */
	private const LOCK_TTL = 6;

	private const FLD_POSITIONS = 'positions';
	private const FLD_WRITE_INDEX = 'writeIndex';

	/**
	 * @param BagOStuff|null $cpStash
	 * @param string|null $secret Secret string for HMAC hashing [optional]
	 * @param bool|null $cliMode Whether the context is CLI or not, setting it to true would disable CP
	 * @param LoggerInterface|null $logger
	 * @since 1.27
	 */
	public function __construct( $cpStash = null, $secret = null, $cliMode = null, $logger = null ) {
		$this->requestInfo = [
			'IPAddress' => $_SERVER['REMOTE_ADDR'] ?? '',
			'UserAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
			// Headers application can inject via LBFactory::setRequestInfo()
			'ChronologyClientId' => null, // prior $cpClientId value from LBFactory::shutdown()
			'ChronologyPositionIndex' => null // prior $cpIndex value from LBFactory::shutdown()
		];
		$this->store = $cpStash ?? new EmptyBagOStuff();
		$this->secret = $secret ?? '';
		$this->logger = $logger ?? new NullLogger();
		$this->cliMode = $cliMode ?? ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
	}

	private function load() {
		// Not enabled or already loaded, short-circuit.
		if ( !$this->enabled || $this->clientId ) {
			return;
		}
		$client = [
			'ip' => $this->requestInfo['IPAddress'],
			'agent' => $this->requestInfo['UserAgent'],
			'clientId' => $this->requestInfo['ChronologyClientId'] ?: null
		];
		if ( $this->cliMode ) {
			$this->setEnabled( false );
		} elseif ( $this->store instanceof EmptyBagOStuff ) {
			// No where to store any DB positions and wait for them to appear
			$this->setEnabled( false );
			$this->logger->debug( 'Cannot use ChronologyProtector with EmptyBagOStuff' );
		}

		if ( isset( $client['clientId'] ) ) {
			$this->clientId = $client['clientId'];
		} else {
			$this->hasNewClientId = true;
			$this->clientId = ( $this->secret != '' )
				? hash_hmac( 'md5', $client['ip'] . "\n" . $client['agent'], $this->secret )
				: md5( $client['ip'] . "\n" . $client['agent'] );
		}
		$this->key = $this->store->makeGlobalKey( __CLASS__, $this->clientId, 'v4' );
		$this->waitForPosIndex = $this->requestInfo['ChronologyPositionIndex'];

		$this->clientLogInfo = [
			'clientIP' => $client['ip'],
			'clientAgent' => $client['agent'],
			'clientId' => $client['clientId'] ?? null
		];
	}

	public function setRequestInfo( array $info ) {
		if ( $this->clientId ) {
			throw new LogicException( 'ChronologyProtector already initialized' );
		}

		$this->requestInfo = $info + $this->requestInfo;
	}

	/**
	 * @return string Client ID hash
	 * @since 1.32
	 */
	public function getClientId() {
		$this->load();
		return $this->clientId;
	}

	/**
	 * @param bool $enabled Whether reading/writing session replication positions is enabled
	 * @since 1.27
	 */
	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}

	/**
	 * Yield client "session consistency" replication position for a new ILoadBalancer
	 *
	 * If the stash has a previous primary position recorded, this will try to make
	 * sure that the next query to a replica server of that primary will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged replica servers are available.
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param ILoadBalancer $lb
	 * @return DBPrimaryPos|null
	 */
	public function getSessionPrimaryPos( ILoadBalancer $lb ) {
		$this->load();
		if ( !$this->enabled ) {
			return null;
		}

		$cluster = $lb->getClusterName();
		$primaryName = $lb->getServerName( ServerInfo::WRITER_INDEX );

		$pos = $this->getStartupSessionPositions()[$primaryName] ?? null;
		if ( $pos instanceof DBPrimaryPos ) {
			$this->logger->debug( "ChronologyProtector will wait for '$pos' on $cluster ($primaryName)'" );
		} else {
			$this->logger->debug( "ChronologyProtector skips wait on $cluster ($primaryName)" );
		}

		return $pos;
	}

	/**
	 * Update client "session consistency" replication position for an end-of-life ILoadBalancer
	 *
	 * This remarks the replication position of the primary DB if this request made writes to
	 * it using the provided ILoadBalancer instance.
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param ILoadBalancer $lb
	 * @return void
	 */
	public function stageSessionPrimaryPos( ILoadBalancer $lb ) {
		$this->load();
		if ( !$this->enabled || !$lb->hasOrMadeRecentPrimaryChanges( INF ) ) {
			return;
		}

		$cluster = $lb->getClusterName();
		$masterName = $lb->getServerName( ServerInfo::WRITER_INDEX );

		// If LoadBalancer::hasStreamingReplicaServers is false (single DB host),
		// or if the database type has no replication (i.e. SQLite), then we do not need to
		// save any position data, as it would never be loaded or waited for. When we save this
		// data, it is for DB_REPLICA queries in future requests, which load it via
		// ChronologyProtector::getSessionPrimaryPos (from LoadBalancer::getReaderIndex)
		// and wait for that position. In a single-server setup, all queries go the primary DB.
		//
		// In that case we still store a null value, so that ChronologyProtector::getTouched
		// reliably detects recent writes for non-database purposes,
		// such as ParserOutputAccess/PoolWorkArticleViewCurrent. This also makes getTouched()
		// easier to setup and test, as we it work work always once CP is enabled
		// (e.g. wgMicroStashType or wgMainCacheType set to a non-DB cache).
		if ( $lb->hasStreamingReplicaServers() ) {
			$pos = $lb->getPrimaryPos();
			if ( $pos ) {
				$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position now '$pos'" );
				$this->shutdownPositionsByPrimary[$masterName] = $pos;
			} else {
				$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position unknown" );
				$this->shutdownPositionsByPrimary[$masterName] = null;
			}
		} else {
			$this->logger->debug( __METHOD__ . ": $cluster ($masterName) has no replication" );
			$this->shutdownPositionsByPrimary[$masterName] = null;
		}
	}

	/**
	 * Persist any staged client "session consistency" replication positions
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param int|null &$clientPosIndex DB position key write counter; incremented on update
	 * @return DBPrimaryPos[] Empty on success; map of (db name => unsaved position) on failure
	 */
	public function persistSessionReplicationPositions( &$clientPosIndex = null ) {
		$this->load();
		if ( !$this->enabled ) {
			return [];
		}

		if ( !$this->shutdownPositionsByPrimary ) {
			$this->logger->debug( __METHOD__ . ": no primary positions data to save" );

			return [];
		}

		$scopeLock = $this->store->getScopedLock( $this->key, self::LOCK_TIMEOUT, self::LOCK_TTL );
		if ( $scopeLock ) {
			$positions = $this->mergePositions(
				$this->unmarshalPositions( $this->store->get( $this->key ) ),
				$this->shutdownPositionsByPrimary,
				$clientPosIndex
			);

			$ok = $this->store->set(
				$this->key,
				$this->marshalPositions( $positions ),
				self::POSITION_STORE_TTL
			);
			unset( $scopeLock );
		} else {
			$ok = false;
		}

		$primaryList = implode( ', ', array_keys( $this->shutdownPositionsByPrimary ) );

		if ( $ok ) {
			$this->logger->debug( "ChronologyProtector saved position data for $primaryList" );
			$bouncedPositions = [];
		} else {
			// Maybe position store is down
			$this->logger->warning( "ChronologyProtector failed to save position data for $primaryList" );
			$clientPosIndex = null;
			$bouncedPositions = $this->shutdownPositionsByPrimary;
		}

		return $bouncedPositions;
	}

	/**
	 * Whether the request came from a client that recently made database changes (last 10 seconds).
	 *
	 * When a user saves an edit or makes other changes to the database, the response to that
	 * request contains a short-lived ChronologyProtector cookie (or cpPosIndex query parameter).
	 *
	 * If we find such cookie on the current request,
	 * and we find any corresponding database positions in the MicroStash,
	 * and they are not expired,
	 * then we return true.
	 *
	 * @since 1.28 Changed parameter in 1.35. Removed parameter in 1.44.
	 * @return bool
	 */
	public function getTouched() {
		$this->load();

		if ( !$this->enabled ) {
			return false;
		}

		if ( $this->getStartupSessionPositions() ) {
			$this->logger->debug( __METHOD__ . ": found recent writes" );
			return true;
		}

		$this->logger->debug( __METHOD__ . ": found no recent writes" );
		return false;
	}

	/**
	 * @return array<string,DBPrimaryPos>
	 */
	protected function getStartupSessionPositions() {
		$this->lazyStartup();

		return $this->startupPositionsByPrimary;
	}

	/**
	 * Load the stored replication positions and touch timestamps for the client
	 *
	 * @return void
	 */
	protected function lazyStartup() {
		if ( $this->startupPositionsByPrimary !== null ) {
			return;
		}

		// There wasn't a client id in the cookie so we built one
		// There is no point in looking it up.
		if ( $this->hasNewClientId ) {
			$this->startupPositionsByPrimary = [];
			return;
		}

		$this->logger->debug( 'ChronologyProtector using store ' . get_class( $this->store ) );
		$this->logger->debug( "ChronologyProtector fetching positions for {$this->clientId}" );

		$data = $this->unmarshalPositions( $this->store->get( $this->key ) );

		$this->startupPositionsByPrimary = $data ? $data[self::FLD_POSITIONS] : [];

		// When a stored array expires and is re-created under the same (deterministic) key,
		// the array value naturally starts again from index zero. As such, it is possible
		// that if certain store writes were lost (e.g. store down), that we unintentionally
		// point to an offset in an older incarnation of the array.
		// We don't try to detect or do something about this because:
		// 1. Waiting for an older offset is harmless and generally no-ops.
		// 2. The older value will have expired by now and thus treated as non-existing,
		//    which means we wouldn't even "see" it here.
		$indexReached = is_array( $data ) ? $data[self::FLD_WRITE_INDEX] : null;
		if ( $this->waitForPosIndex > 0 ) {
			if ( $indexReached >= $this->waitForPosIndex ) {
				$this->logger->debug( 'expected and found position index {cpPosIndex}', [
					'cpPosIndex' => $this->waitForPosIndex,
				] + $this->clientLogInfo );
			} else {
				$this->logger->warning( 'expected but failed to find position index {cpPosIndex}', [
					'cpPosIndex' => $this->waitForPosIndex,
					'indexReached' => $indexReached,
					'exception' => new \RuntimeException(),
				] + $this->clientLogInfo );
			}
		} else {
			if ( $indexReached ) {
				$this->logger->debug( 'found position data with index {indexReached}', [
					'indexReached' => $indexReached
				] + $this->clientLogInfo );
			}
		}
	}

	/**
	 * Merge the new replication positions with the currently stored ones (highest wins)
	 *
	 * @param array<string,mixed>|false $storedValue Current replication position data
	 * @param array<string,DBPrimaryPos> $shutdownPositions New replication positions
	 * @param int|null &$clientPosIndex New position write index
	 * @return array<string,mixed> Combined replication position data
	 */
	protected function mergePositions(
		$storedValue,
		array $shutdownPositions,
		?int &$clientPosIndex = null
	) {
		/** @var array<string,DBPrimaryPos> $mergedPositions */
		$mergedPositions = $storedValue[self::FLD_POSITIONS] ?? [];
		// Use the newest positions for each DB primary
		foreach ( $shutdownPositions as $masterName => $pos ) {
			if (
				!isset( $mergedPositions[$masterName] ) ||
				!( $mergedPositions[$masterName] instanceof DBPrimaryPos ) ||
				$pos->asOfTime() > $mergedPositions[$masterName]->asOfTime()
			) {
				$mergedPositions[$masterName] = $pos;
			}
		}

		$clientPosIndex = ( $storedValue[self::FLD_WRITE_INDEX] ?? 0 ) + 1;

		return [
			self::FLD_POSITIONS => $mergedPositions,
			self::FLD_WRITE_INDEX => $clientPosIndex
		];
	}

	private function marshalPositions( array $positions ): array {
		foreach ( $positions[ self::FLD_POSITIONS ] as $key => $pos ) {
			if ( $pos ) {
				$positions[ self::FLD_POSITIONS ][ $key ] = $pos->toArray();
			}
		}

		return $positions;
	}

	/**
	 * @param array|false $positions
	 * @return array|false
	 */
	private function unmarshalPositions( $positions ) {
		if ( !$positions ) {
			return $positions;
		}

		foreach ( $positions[ self::FLD_POSITIONS ] as $key => $pos ) {
			if ( $pos ) {
				$class = $pos[ '_type_' ];
				$positions[ self::FLD_POSITIONS ][ $key ] = $class::newFromArray( $pos );
			}
		}

		return $positions;
	}

	/**
	 * Build a string conveying the client and write index of the chronology protector data
	 *
	 * @param int $writeIndex
	 * @param int $time UNIX timestamp; can be used to detect stale cookies (T190082)
	 * @param string $clientId Client ID hash from ILBFactory::shutdown()
	 * @return string Value to use for "cpPosIndex" cookie
	 * @since 1.32 in LBFactory, moved to CP in 1.41
	 */
	public static function makeCookieValueFromCPIndex(
		int $writeIndex,
		int $time,
		string $clientId
	) {
		// Format is "<write index>@<write timestamp>#<client ID hash>"
		return "{$writeIndex}@{$time}#{$clientId}";
	}

	/**
	 * Parse a string conveying the client and write index of the chronology protector data
	 *
	 * @param string|null $value Value of "cpPosIndex" cookie
	 * @param int $minTimestamp Lowest UNIX timestamp that a non-expired value can have
	 * @return array (index: int or null, clientId: string or null)
	 * @since 1.32 in LBFactory, moved to CP in 1.41
	 */
	public static function getCPInfoFromCookieValue( ?string $value, int $minTimestamp ) {
		static $placeholder = [ 'index' => null, 'clientId' => null ];

		if ( $value === null ) {
			return $placeholder; // not set
		}

		// Format is "<write index>@<write timestamp>#<client ID hash>"
		if ( !preg_match( '/^(\d+)@(\d+)#([0-9a-f]{32})$/', $value, $m ) ) {
			return $placeholder; // invalid
		}

		$index = (int)$m[1];
		if ( $index <= 0 ) {
			return $placeholder; // invalid
		} elseif ( isset( $m[2] ) && $m[2] !== '' && (int)$m[2] < $minTimestamp ) {
			return $placeholder; // expired
		}

		$clientId = ( isset( $m[3] ) && $m[3] !== '' ) ? $m[3] : null;

		return [ 'index' => $index, 'clientId' => $clientId ];
	}
}
