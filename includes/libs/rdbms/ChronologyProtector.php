<?php
/**
 * Generator of database load balancing objects.
 *
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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use BagOStuff;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\WaitConditionLoop;

/**
 * Helper class for mitigating DB replication lag in order to provide "session consistency"
 *
 * This helps to ensure a consistent ordering of events as seen by an client
 *
 * Kind of like Hawking's [[Chronology Protection Agency]].
 *
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

	/** @var array<string,DBMasterPos> Map of (DB master name => position) */
	protected $startupPositionsByMaster = [];
	/** @var array<string,DBMasterPos> Map of (DB master name => position) */
	protected $shutdownPositionsByMaster = [];
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
	/** Max seconds to wait for positions write indexes to appear (e.g. replicate) in storage */
	private const POSITION_INDEX_WAIT_TIMEOUT = 5;

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
	 * @param int|null $posIndex Write counter index
	 * @param string $secret Secret string for HMAC hashing [optional]
	 * @since 1.27
	 */
	public function __construct( BagOStuff $store, array $client, $posIndex, $secret = '' ) {
		$this->store = $store;

		if ( isset( $client['clientId'] ) ) {
			$this->clientId = $client['clientId'];
		} else {
			$this->clientId = ( $secret != '' )
				? hash_hmac( 'md5', $client['ip'] . "\n" . $client['agent'], $secret )
				: md5( $client['ip'] . "\n" . $client['agent'] );
		}
		$this->key = $store->makeGlobalKey( __CLASS__, $this->clientId, 'v2' );
		$this->waitForPosIndex = $posIndex;

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
	 * Apply the "session consistency" DB replication position to a new ILoadBalancer
	 *
	 * If the stash has a previous master position recorded, this will try to make
	 * sure that the next query to a replica DB of that master will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged replica DBs are available.
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
		$masterName = $lb->getServerName( $lb->getWriterIndex() );

		$pos = $this->getStartupSessionPositions()[$masterName] ?? null;
		if ( $pos instanceof DBMasterPos ) {
			$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position is '$pos'" );
			$lb->waitFor( $pos );
		} else {
			$this->logger->debug( __METHOD__ . ": $cluster ($masterName) has no position" );
		}
	}

	/**
	 * Update the "session consistency" DB replication position for an end-of-life ILoadBalancer
	 *
	 * This remarks the replication position of the master DB if this request made writes to
	 * it using the provided ILoadBalancer instance.
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param ILoadBalancer $lb
	 * @return void
	 */
	public function stageSessionReplicationPosition( ILoadBalancer $lb ) {
		if ( !$this->enabled || !$lb->hasOrMadeRecentMasterChanges( INF ) ) {
			return;
		}

		$cluster = $lb->getClusterName();
		$masterName = $lb->getServerName( $lb->getWriterIndex() );

		if ( $lb->hasStreamingReplicaServers() ) {
			$pos = $lb->getReplicaResumePos();
			if ( $pos ) {
				$this->logger->debug( __METHOD__ . ": $cluster ($masterName) position now '$pos'" );
				$this->shutdownPositionsByMaster[$masterName] = $pos;
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
	 * Save any remarked "session consistency" DB replication positions to persistent storage
	 *
	 * @internal This method should only be called from LBFactory.
	 *
	 * @param int|null &$cpIndex DB position key write counter; incremented on update
	 * @return DBMasterPos[] Empty on success; map of (db name => unsaved position) on failure
	 */
	public function shutdown( &$cpIndex = null ) {
		if ( !$this->enabled ) {
			return [];
		}

		if ( !$this->shutdownTimestampsByCluster ) {
			$this->logger->debug( __METHOD__ . ": no master positions/timestamps to save" );

			return [];
		}

		$scopeLock = $this->store->getScopedLock( $this->key, self::LOCK_TIMEOUT, self::LOCK_TTL );
		if ( $scopeLock ) {
			$ok = $this->store->set(
				$this->key,
				$this->mergePositions(
					$this->store->get( $this->key ),
					$this->shutdownPositionsByMaster,
					$this->shutdownTimestampsByCluster,
					$cpIndex
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
				__METHOD__ . ": saved master positions/timestamp for DB cluster(s) $clusterList"
			);

		} else {
			$cpIndex = null; // nothing saved
			$bouncedPositions = $this->shutdownPositionsByMaster;
			// Raced out too many times or stash is down
			$this->logger->warning(
				__METHOD__ . ": failed to save master positions for DB cluster(s) $clusterList"
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
	 * @return array<string,DBMasterPos>
	 */
	protected function getStartupSessionPositions() {
		$this->lazyStartup();

		return $this->startupPositionsByMaster;
	}

	/**
	 * @return array<string,float>
	 */
	protected function getStartupSessionTimestamps() {
		$this->lazyStartup();

		return $this->startupTimestampsByCluster;
	}

	/**
	 * Load the stored DB replication positions and touch timestamps for the client
	 *
	 * @return void
	 */
	protected function lazyStartup() {
		if ( $this->startupTimestamp !== null ) {
			return;
		}

		$this->startupTimestamp = $this->getCurrentTime();
		$this->logger->debug(
			__METHOD__ .
			": client ID is {$this->clientId}; key is {$this->key}"
		);

		// If there is an expectation to see master positions from a certain write
		// index or higher, then block until it appears, or until a timeout is reached.
		// Since the write index restarts each time the key is created, it is possible that
		// a lagged store has a matching key write index. However, in that case, it should
		// already be expired and thus treated as non-existing, maintaining correctness.
		if ( $this->positionWaitsEnabled && $this->waitForPosIndex > 0 ) {
			$data = null;
			$indexReached = null; // highest index reached in the position store
			$loop = new WaitConditionLoop(
				function () use ( &$data, &$indexReached ) {
					$data = $this->store->get( $this->key );
					if ( !is_array( $data ) ) {
						return WaitConditionLoop::CONDITION_CONTINUE; // not found yet
					} elseif ( !isset( $data[self::FLD_WRITE_INDEX] ) ) {
						return WaitConditionLoop::CONDITION_REACHED; // b/c
					}
					$indexReached = max( $data[self::FLD_WRITE_INDEX], $indexReached );

					return ( $data[self::FLD_WRITE_INDEX] >= $this->waitForPosIndex )
						? WaitConditionLoop::CONDITION_REACHED
						: WaitConditionLoop::CONDITION_CONTINUE;
				},
				self::POSITION_INDEX_WAIT_TIMEOUT
			);
			$result = $loop->invoke();
			$waitedMs = $loop->getLastWaitTime() * 1e3;

			if ( $result == $loop::CONDITION_REACHED ) {
				$this->logger->debug(
					__METHOD__ . ": expected and found position index {cpPosIndex}.",
					[
						'cpPosIndex' => $this->waitForPosIndex,
						'waitTimeMs' => $waitedMs
					] + $this->clientLogInfo
				);
			} else {
				$this->logger->warning(
					__METHOD__ . ": expected but failed to find position index {cpPosIndex}.",
					[
						'cpPosIndex' => $this->waitForPosIndex,
						'indexReached' => $indexReached,
						'waitTimeMs' => $waitedMs
					] + $this->clientLogInfo
				);
			}
		} else {
			$data = $this->store->get( $this->key );
			$indexReached = $data[self::FLD_WRITE_INDEX] ?? null;
			if ( $indexReached ) {
				$this->logger->debug(
					__METHOD__ . ": found position/timestamp data with index {indexReached}.",
					[ 'indexReached' => $indexReached ] + $this->clientLogInfo
				);
			}
		}

		$this->startupPositionsByMaster = $data ? $data[self::FLD_POSITIONS] : [];
		$this->startupTimestampsByCluster = $data[self::FLD_TIMESTAMPS] ?? [];
	}

	/**
	 * Merge the new DB replication positions with the currently stored ones (highest wins)
	 *
	 * @param array<string,mixed>|false $storedValue Current DB replication position data
	 * @param array<string,DBMasterPos> $shutdownPositions New DB replication positions
	 * @param array<string,float> $shutdownTimestamps New DB post-commit shutdown timestamps
	 * @param int|null &$cpIndex New position write index
	 * @return array<string,mixed> Combined DB replication position data
	 */
	protected function mergePositions(
		$storedValue,
		array $shutdownPositions,
		array $shutdownTimestamps,
		&$cpIndex = null
	) {
		/** @var array<string,DBMasterPos> $mergedPositions */
		$mergedPositions = $storedValue[self::FLD_POSITIONS] ?? [];
		// Use the newest positions for each DB master
		foreach ( $shutdownPositions as $masterName => $pos ) {
			if (
				!isset( $mergedPositions[$masterName] ) ||
				!( $mergedPositions[$masterName] instanceof DBMasterPos ) ||
				$pos->asOfTime() > $mergedPositions[$masterName]->asOfTime()
			) {
				$mergedPositions[$masterName] = $pos;
			}
		}

		/** @var array<string,float> $mergedTimestamps */
		$mergedTimestamps = $storedValue[self::FLD_TIMESTAMPS] ?? [];
		// Use the newest touch timestamp for each DB master
		foreach ( $shutdownTimestamps as $cluster => $timestamp ) {
			if (
				!isset( $mergedTimestamps[$cluster] ) ||
				$timestamp > $mergedTimestamps[$cluster]
			) {
				$mergedTimestamps[$cluster] = $timestamp;
			}
		}

		$cpIndex = $storedValue[self::FLD_WRITE_INDEX] ?? 0;

		return [
			self::FLD_POSITIONS => $mergedPositions,
			self::FLD_TIMESTAMPS => $mergedTimestamps,
			self::FLD_WRITE_INDEX => ++$cpIndex
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
