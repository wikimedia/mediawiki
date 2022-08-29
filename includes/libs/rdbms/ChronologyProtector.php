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
 */
namespace Wikimedia\Rdbms;

use BagOStuff;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
 * The store used by ChronologyProtector, as configured via {@link $wgChronologyProtectorStash},
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
class ChronologyProtector implements LoggerAwareInterface {
	/** @var BagOStuff */
	protected $store;
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
	/** @var bool Whether waiting on DB servers to reach replication positions is enabled */
	protected $positionWaitsEnabled = true;
	/** @var float|null UNIX timestamp when the client data was loaded */
	protected $startupTimestamp;

	/** @var array<string,DBPrimaryPos> Map of (primary server name => position) */
	protected $startupPositionsByPrimary = [];
	/** @var array<string,DBPrimaryPos> Map of (primary server name => position) */
	protected $shutdownPositionsByPrimary = [];
	/** @var array<string,float> Map of (DB cluster name => UNIX timestamp) */
	protected $startupTimestampsByCluster = [];
	/** @var array<string,float> Map of (DB cluster name => UNIX timestamp) */
	protected $shutdownTimestampsByCluster = [];

	/** @var float|null */
	private $wallClockOverride;

	/** Seconds to store position write index cookies (safely less than POSITION_STORE_TTL) */
	public const POSITION_COOKIE_TTL = 10;
	/** Seconds to store replication positions */
	private const POSITION_STORE_TTL = 60;

	/** Lock timeout to use for key updates */
	private const LOCK_TIMEOUT = 3;
	/** Lock expiry to use for key updates */
	private const LOCK_TTL = 6;

	private const FLD_POSITIONS = 'positions';
	private const FLD_TIMESTAMPS = 'timestamps';
	private const FLD_WRITE_INDEX = 'writeIndex';

	/**
	 * @param BagOStuff $store
	 * @param array $client Map of (ip: <IP>, agent: <user-agent> [, clientId: <hash>] )
	 * @param int|null $clientPosIndex Write counter index of replication positions for this client
	 * @param string $secret Secret string for HMAC hashing [optional]
	 * @since 1.27
	 */
	public function __construct(
		BagOStuff $store,
		array $client,
		?int $clientPosIndex,
		string $secret = ''
	) {
		$this->store = $store;

		if ( isset( $client['clientId'] ) ) {
			$this->clientId = $client['clientId'];
		} else {
			$this->clientId = ( $secret != '' )
				? hash_hmac( 'md5', $client['ip'] . "\n" . $client['agent'], $secret )
				: md5( $client['ip'] . "\n" . $client['agent'] );
		}
		$this->key = $store->makeGlobalKey( __CLASS__, $this->clientId, 'v2' );
		$this->waitForPosIndex = $clientPosIndex;

		$this->clientLogInfo = [
			'clientIP' => $client['ip'],
			'clientAgent' => $client['agent'],
			'clientId' => $client['clientId'] ?? null
		];

		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return string Client ID hash
	 * @since 1.32
	 */
	public function getClientId() {
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
	 * @param bool $enabled Whether session replication position wait barriers are enable
	 * @since 1.27
	 */
	public function setWaitEnabled( $enabled ) {
		$this->positionWaitsEnabled = $enabled;
	}

	/**
	 * Apply client "session consistency" replication position to a new ILoadBalancer
	 *
	 * If the stash has a previous primary position recorded, this will try to make
	 * sure that the next query to a replica server of that primary will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged replica servers are available.
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param ILoadBalancer $lb
	 * @return void
	 */
	public function applySessionReplicationPosition( ILoadBalancer $lb ) {
		if ( !$this->enabled || !$this->positionWaitsEnabled ) {
			return;
		}

		$cluster = $lb->getClusterName();
		$primaryName = $lb->getServerName( $lb->getWriterIndex() );

		$pos = $this->getStartupSessionPositions()[$primaryName] ?? null;
		if ( $pos instanceof DBPrimaryPos ) {
			$this->logger->debug( __METHOD__ . ": $cluster ($primaryName) position is '$pos'" );
			$lb->waitFor( $pos );
		} else {
			$this->logger->debug( __METHOD__ . ": $cluster ($primaryName) has no position" );
		}
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
	public function stageSessionReplicationPosition( ILoadBalancer $lb ) {
		if ( !$this->enabled || !$lb->hasOrMadeRecentPrimaryChanges( INF ) ) {
			return;
		}

		$cluster = $lb->getClusterName();
		$masterName = $lb->getServerName( $lb->getWriterIndex() );

		if ( $lb->hasStreamingReplicaServers() ) {
			$pos = $lb->getReplicaResumePos();
			if ( $pos ) {
				$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position now '$pos'" );
				$this->shutdownPositionsByPrimary[$masterName] = $pos;
				$this->shutdownTimestampsByCluster[$cluster] = $pos->asOfTime();
			} else {
				$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position unknown" );
				$this->shutdownTimestampsByCluster[$cluster] = $this->getCurrentTime();
			}
		} else {
			$this->logger->debug( __METHOD__ . ": $cluster ($masterName) has no replication" );
			$this->shutdownTimestampsByCluster[$cluster] = $this->getCurrentTime();
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
		if ( !$this->enabled ) {
			return [];
		}

		if ( !$this->shutdownTimestampsByCluster ) {
			$this->logger->debug( __METHOD__ . ": no primary positions/timestamps to save" );

			return [];
		}

		$scopeLock = $this->store->getScopedLock( $this->key, self::LOCK_TIMEOUT, self::LOCK_TTL );
		if ( $scopeLock ) {
			$ok = $this->store->set(
				$this->key,
				$this->mergePositions(
					$this->store->get( $this->key ),
					$this->shutdownPositionsByPrimary,
					$this->shutdownTimestampsByCluster,
					$clientPosIndex
				),
				self::POSITION_STORE_TTL
			);
			unset( $scopeLock );
		} else {
			$ok = false;
		}

		$clusterList = implode( ', ', array_keys( $this->shutdownTimestampsByCluster ) );

		if ( $ok ) {
			$bouncedPositions = [];
			$this->logger->debug(
				__METHOD__ . ": saved primary positions/timestamp for DB cluster(s) $clusterList"
			);

		} else {
			$clientPosIndex = null; // nothing saved
			$bouncedPositions = $this->shutdownPositionsByPrimary;
			// Raced out too many times or stash is down
			$this->logger->warning(
				__METHOD__ . ": failed to save primary positions for DB cluster(s) $clusterList"
			);
		}

		return $bouncedPositions;
	}

	/**
	 * Get the UNIX timestamp when the client last touched the DB, if they did so recently
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param ILoadBalancer $lb
	 * @return float|false UNIX timestamp; false if not recent or on record
	 * @since 1.35
	 */
	public function getTouched( ILoadBalancer $lb ) {
		if ( !$this->enabled ) {
			return false;
		}

		$cluster = $lb->getClusterName();

		$timestampsByCluster = $this->getStartupSessionTimestamps();
		$timestamp = $timestampsByCluster[$cluster] ?? null;
		if ( $timestamp === null ) {
			$recentTouchTimestamp = false;
		} elseif ( ( $this->startupTimestamp - $timestamp ) > self::POSITION_COOKIE_TTL ) {
			// If the position store is not replicated among datacenters and the cookie that
			// sticks the client to the primary datacenter expires, then the touch timestamp
			// will be found for requests in one datacenter but not others. For consistency,
			// return false once the user is no longer routed to the primary datacenter.
			$recentTouchTimestamp = false;
			$this->logger->debug( __METHOD__ . ": old timestamp ($timestamp) for $cluster" );
		} else {
			$recentTouchTimestamp = $timestamp;
			$this->logger->debug( __METHOD__ . ": recent timestamp ($timestamp) for $cluster" );
		}

		return $recentTouchTimestamp;
	}

	/**
	 * @return array<string,DBPrimaryPos>
	 */
	protected function getStartupSessionPositions() {
		$this->lazyStartup();

		return $this->startupPositionsByPrimary;
	}

	/**
	 * @return array<string,float>
	 */
	protected function getStartupSessionTimestamps() {
		$this->lazyStartup();

		return $this->startupTimestampsByCluster;
	}

	/**
	 * Load the stored replication positions and touch timestamps for the client
	 *
	 * @return void
	 */
	protected function lazyStartup() {
		if ( $this->startupTimestamp !== null ) {
			return;
		}

		$this->startupTimestamp = $this->getCurrentTime();
		$this->logger->debug( 'ChronologyProtector using store ' . get_class( $this->store ) );
		$this->logger->debug( "ChronologyProtector fetching positions for {$this->clientId}" );

		$data = $this->store->get( $this->key );

		$this->startupPositionsByPrimary = $data ? $data[self::FLD_POSITIONS] : [];
		$this->startupTimestampsByCluster = $data[self::FLD_TIMESTAMPS] ?? [];

		// When a stored array expires and is re-created under the same (deterministic) key,
		// the array value naturally starts again from index zero. As such, it is possible
		// that if certain store writes were lost (e.g. store down), that we unintentionally
		// point to an offset in an older incarnation of the array.
		// We don't try to detect or do something about this because:
		// 1. Waiting for an older offset is harmless and generally no-ops.
		// 2. The older value will have expired by now and thus treated as non-existing,
		//    which means we wouldn't even "see" it here.
		$indexReached = is_array( $data ) ? $data[self::FLD_WRITE_INDEX] : null;
		if ( $this->positionWaitsEnabled && $this->waitForPosIndex > 0 ) {
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
	 * @param array<string,float> $shutdownTimestamps New DB post-commit shutdown timestamps
	 * @param int|null &$clientPosIndex New position write index
	 * @return array<string,mixed> Combined replication position data
	 */
	protected function mergePositions(
		$storedValue,
		array $shutdownPositions,
		array $shutdownTimestamps,
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

		/** @var array<string,float> $mergedTimestamps */
		$mergedTimestamps = $storedValue[self::FLD_TIMESTAMPS] ?? [];
		// Use the newest touch timestamp for each DB primary
		foreach ( $shutdownTimestamps as $cluster => $timestamp ) {
			if (
				!isset( $mergedTimestamps[$cluster] ) ||
				$timestamp > $mergedTimestamps[$cluster]
			) {
				$mergedTimestamps[$cluster] = $timestamp;
			}
		}

		$clientPosIndex = ( $storedValue[self::FLD_WRITE_INDEX] ?? 0 ) + 1;

		return [
			self::FLD_POSITIONS => $mergedPositions,
			self::FLD_TIMESTAMPS => $mergedTimestamps,
			self::FLD_WRITE_INDEX => $clientPosIndex
		];
	}

	/**
	 * @internal For testing only
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	protected function getCurrentTime() {
		if ( $this->wallClockOverride ) {
			return $this->wallClockOverride;
		}

		$clockTime = (float)time(); // call this first
		// microtime() can severely drift from time() and the microtime() value of other threads.
		// Instead of seeing the current time as being in the past, use the value of time().
		return max( microtime( true ), $clockTime );
	}

	/**
	 * @internal For testing only
	 * @param float|null &$time Mock UNIX timestamp
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->wallClockOverride =& $time;
	}
}
