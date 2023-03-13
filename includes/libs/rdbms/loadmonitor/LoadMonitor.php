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
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use NullStatsdDataFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use WANObjectCache;
use Wikimedia\ScopedCallback;

/**
 * Basic DB load monitor with no external dependencies
 *
 * Uses both server-local and shared caches for server state information.
 *
 * The "domain" parameters are unused, though they might be used in the future.
 * Therefore, at present, this assumes one channel of replication per server.
 *
 * @ingroup Database
 */
class LoadMonitor implements ILoadMonitor {
	/** @var ILoadBalancer */
	protected $lb;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var WANObjectCache */
	protected $wanCache;
	/** @var LoggerInterface */
	protected $logger;
	/** @var StatsdDataFactoryInterface */
	protected $statsd;

	/** @var float Maximum new gauge coefficient for moving averages */
	private $movingAveRatio;
	/** @var int Amount of replication lag in seconds before warnings are logged */
	private $lagWarnThreshold;

	/** @var float|null */
	private $wallClockOverride;

	/** @var bool Whether the "server states" cache key is in the process of being updated */
	private $serverStatesKeyLocked = false;

	/** @var int cache key version */
	private const VERSION = 2;

	/** Server cache target time-till-refresh for DB server state info */
	private const STATE_TARGET_TTL = 1.0;
	/** Server cache physical TTL for DB server state info */
	private const STATE_PRESERVE_TTL = 60;
	/** @var int Max interval within which a server state refresh should happen */
	private const TIME_TILL_REFRESH = 1;

	/**
	 * @param ILoadBalancer $lb
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wCache
	 * @param array $options Additional parameters include:
	 *   - movingAveRatio: maximum new gauge coefficient for moving averages
	 *      when the new gauge is 1 second newer than the prior one [default: .54]
	 *   - lagWarnThreshold: how many seconds of lag trigger warnings [default: 10]
	 */
	public function __construct(
		ILoadBalancer $lb, BagOStuff $srvCache, WANObjectCache $wCache, array $options = []
	) {
		$this->lb = $lb;
		$this->srvCache = $srvCache;
		$this->wanCache = $wCache;
		$this->logger = new NullLogger();
		$this->statsd = new NullStatsdDataFactory();

		$this->movingAveRatio = (float)( $options['movingAveRatio'] ?? 0.54 );
		$this->lagWarnThreshold = $options['lagWarnThreshold'] ?? LoadBalancer::MAX_LAG_DEFAULT;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function setStatsdDataFactory( StatsdDataFactoryInterface $statsFactory ) {
		$this->statsd = $statsFactory;
	}

	final public function scaleLoads( array &$weightByServer ) {
		$serverIndexes = array_keys( $weightByServer );
		$states = $this->getServerStates( $serverIndexes );
		$newScalesByServer = $states['weightScales'];
		foreach ( $weightByServer as $i => $weight ) {
			if ( isset( $newScalesByServer[$i] ) ) {
				$weightByServer[$i] = (int)ceil( $weight * $newScalesByServer[$i] );
			} else { // server recently added to config?
				$host = $this->lb->getServerName( $i );
				$this->logger->error( __METHOD__ . ": host $host not in cache" );
			}
		}
	}

	final public function getLagTimes( array $serverIndexes ) {
		return $this->getServerStates( $serverIndexes )['lagTimes'];
	}

	/**
	 * @param array $serverIndexes
	 * @return array
	 * @throws DBAccessError
	 */
	protected function getServerStates( array $serverIndexes ) {
		$now = $this->getCurrentTime();
		// Represent the cluster by the name of the primary DB
		$cluster = $this->lb->getServerName( $this->lb->getWriterIndex() );

		// (a) Check the local server cache
		$srvCacheKey = $this->getStatesCacheKey( $this->srvCache, $serverIndexes );
		$value = $this->srvCache->get( $srvCacheKey );
		if (
			$value &&
			!$this->isStateRefreshDue(
				$value['timestamp'],
				$value['genTime'],
				self::STATE_TARGET_TTL,
				$now
			)
		) {
			$this->logger->debug( __METHOD__ . ": used fresh '$cluster' cluster status" );

			return $value; // cache hit
		}

		// (b) Value is stale/missing; try to use/refresh the shared cache
		$scopedLock = $this->srvCache->getScopedLock( $srvCacheKey, 0, 10 );
		if ( !$scopedLock && $value ) {
			$this->logger->debug( __METHOD__ . ": used stale '$cluster' cluster status" );
			// (b1) Another thread on this server is already checking the shared cache
			return $value;
		}

		// (b2) This thread gets to check the shared cache or (b3) value is missing
		$staleValue = $value;
		$updated = false; // whether the regeneration callback ran
		$value = $this->wanCache->getWithSetCallback(
			$this->getStatesCacheKey( $this->wanCache, $serverIndexes ),
			self::TIME_TILL_REFRESH, // 1 second logical expiry
			function ( $oldValue, &$ttl ) use ( $serverIndexes, $staleValue, &$updated ) {
				// Double check for circular recursion in computeServerStates()/getWeightScale().
				// Mainly, connection attempts should use LoadBalancer::getServerConnection()
				// rather than something that will pick a server based on the server states.
				$scopedLock = $this->acquireServerStatesLoopGuard();
				if ( !$scopedLock ) {
					throw new RuntimeException(
						"Circular recursion detected while regenerating server states cache. " .
						"This may indicate improper connection handling in " . get_class( $this )
					);
				}

				$updated = true;

				return $this->computeServerStates(
					$serverIndexes,
					$oldValue ?: $staleValue // fallback to local cache stale value
				);
			},
			[
				// One thread can update at a time; others use the old value
				'lockTSE' => self::STATE_PRESERVE_TTL,
				'staleTTL' => self::STATE_PRESERVE_TTL,
				// If there is no shared stale value then use the local cache stale value;
				// When even that is not possible, then use the trivial value below.
				'busyValue' => $staleValue ?: $this->getPlaceholderServerStates( $serverIndexes )
			]
		);

		if ( $updated ) {
			$this->logger->info( __METHOD__ . ": regenerated '$cluster' cluster status" );
		} else {
			$this->logger->debug( __METHOD__ . ": used cached '$cluster' cluster status" );
		}

		// Backfill the local server cache
		if ( $scopedLock ) {
			$this->srvCache->set( $srvCacheKey, $value, self::STATE_PRESERVE_TTL );
		}

		return $value;
	}

	/**
	 * @param float $priorAsOf
	 * @param float $priorGenDelay
	 * @param float $referenceTTL
	 * @param float $now
	 * @return bool
	 */
	protected function isStateRefreshDue( $priorAsOf, $priorGenDelay, $referenceTTL, $now ) {
		$age = max( $now - $priorAsOf, 0.0 );
		// Ratio of the nominal TTL that has elapsed (r)
		$ttrRatio = $age / $referenceTTL;
		// Ratio of the nominal TTL that elapses during regeneration (g)
		$genRatio = $priorGenDelay / $referenceTTL;
		// Use p(r,g) as the monotonically increasing "chance of refresh" function,
		// having p(0,g)=0. Normally, g~=0, in which case p(1,g)~=1. If g >> 0, then
		// the value might not refresh until a small amount after the nominal expiry.
		$chance = exp( -128 * $genRatio ) * ( $ttrRatio ** 4 );
		return ( mt_rand( 1, 1000000000 ) <= 1000000000 * $chance );
	}

	/**
	 * @param array $serverIndexes
	 * @param array|false $priorStates
	 * @return array
	 * @throws DBAccessError
	 */
	protected function computeServerStates( array $serverIndexes, $priorStates ) {
		$startTime = $this->getCurrentTime();
		// Check if there is just a primary DB (no replication involved)
		if ( $this->lb->getServerCount() <= 1 ) {
			return $this->getPlaceholderServerStates( $serverIndexes );
		}

		$priorAsOf = $priorStates['timestamp'] ?? 0;
		$priorScales = $priorStates ? $priorStates['weightScales'] : [];
		$cluster = $this->lb->getClusterName();

		$lagTimes = [];
		$weightScales = [];
		foreach ( $serverIndexes as $i ) {
			$isPrimary = ( $i == $this->lb->getWriterIndex() );
			// If the primary DB has zero load, then typical read queries do not use it.
			// In that case, avoid connecting to it since this method might run in any
			// datacenter, and the primary DB might be geographically remote.
			if ( $isPrimary && $this->lb->getServerInfo( $i )['load'] <= 0 ) {
				$lagTimes[$i] = 0;
				// Callers only use this DB if they have *no choice* anyway (e.g. writes)
				$weightScales[$i] = 1.0;
				continue;
			}

			$host = $this->lb->getServerName( $i );

			// Get a new, untracked, connection in order to gauge server health
			$flags = $this->lb::CONN_UNTRACKED_GAUGE | $this->lb::CONN_SILENCE_ERRORS;
			// Get a connection to this server without triggering other server connections
			$conn = $this->lb->getServerConnection( $i, $this->lb::DOMAIN_ANY, $flags );

			// Get new weight scale using a moving average of the naïve and prior values
			$lastScale = $priorScales[$i] ?? 1.0;
			$naiveScale = $this->getWeightScale( $i, $conn ?: null );
			$newScale = $this->movingAverage(
				$lastScale,
				$naiveScale,
				max( $this->getCurrentTime() - $priorAsOf, 0.0 ),
				$this->movingAveRatio
			);
			// Scale from 0% to 100% of nominal weight
			$newScale = max( $newScale, 0.0 );

			$weightScales[$i] = $newScale;
			$statHost = str_replace( '.', '_', $host );
			$this->statsd->gauge( "loadbalancer.weight.$cluster.$statHost", $newScale );

			// Mark replication lag on this server as "false" if it is unreachable
			if ( !$conn ) {
				$lagTimes[$i] = $isPrimary ? 0 : false;
				$this->logger->error(
					__METHOD__ . ": host {db_server} is unreachable",
					[ 'db_server' => $host ]
				);
				continue;
			}

			// Determine the amount of replication lag on this server
			try {
				$lag = $conn->getLag();
			} catch ( DBError $e ) {
				// Mark the lag time as "false" if it cannot be queried
				$lag = false;
			}
			$lagTimes[$i] = $lag;

			if ( $lag === false ) {
				$this->logger->error(
					__METHOD__ . ": host {db_server} is not replicating?",
					[ 'db_server' => $host ]
				);
			} else {
				$this->statsd->timing( "loadbalancer.lag.$cluster.$statHost", $lag * 1000 );
				if ( $lag > $this->lagWarnThreshold ) {
					$this->logger->warning(
						"Server {db_server} has {lag} seconds of lag (>= {maxlag})",
						[
							'db_server' => $host,
							'lag' => $lag,
							'maxlag' => $this->lagWarnThreshold
						]
					);
				}
			}

			// Only keep one connection open at a time
			$conn->close( __METHOD__ );
		}

		$endTime = $this->getCurrentTime();

		return [
			'lagTimes' => $lagTimes,
			'weightScales' => $weightScales,
			'timestamp' => $endTime,
			'genTime' => max( $endTime - $startTime, 0.0 )
		];
	}

	/**
	 * @param int[] $serverIndexes
	 * @return array
	 */
	private function getPlaceholderServerStates( array $serverIndexes ) {
		return [
			'lagTimes' => array_fill_keys( $serverIndexes, 0 ),
			'weightScales' => array_fill_keys( $serverIndexes, 1.0 ),
			'timestamp' => $this->getCurrentTime(),
			'genTime' => 0.0
		];
	}

	/**
	 * @param int $index Server index
	 * @param IDatabase|null $conn Connection handle or null on connection failure
	 * @return float
	 * @since 1.28
	 */
	protected function getWeightScale( $index, IDatabase $conn = null ) {
		return $conn ? 1.0 : 0.0;
	}

	/**
	 * Update a moving average for a gauge, accounting for the time delay since the last gauge
	 *
	 * @param float|int|null $priorValue Prior moving average of value or null
	 * @param float|int|null $gaugeValue Newly gauged value or null
	 * @param float $delay Seconds between the new gauge and the prior one
	 * @param float $movAveRatio New gauge weight when it is 1 second newer than the prior one
	 * @return float|false New moving average of value
	 */
	public function movingAverage(
		$priorValue,
		$gaugeValue,
		float $delay,
		float $movAveRatio
	) {
		if ( $gaugeValue === null ) {
			return $priorValue;
		} elseif ( $priorValue === null ) {
			return $gaugeValue;
		}

		// Apply more weight to the newer gauge the more outdated the prior gauge is.
		// The rate of state updates generally depends on the amount of site traffic.
		// Smaller will get less frequent updates, but the gauges still still converge
		// within reasonable time bounds so that unreachable DB servers are avoided.
		$delayAwareRatio = 1 - pow( 1 - $movAveRatio, $delay );

		return max( $delayAwareRatio * $gaugeValue + ( 1 - $delayAwareRatio ) * $priorValue, 0.0 );
	}

	/**
	 * @param WANObjectCache|BagOStuff $cache
	 * @param array $serverIndexes
	 * @return string
	 */
	private function getStatesCacheKey( $cache, array $serverIndexes ) {
		sort( $serverIndexes );
		// Lag is per-server, not per-DB, so key on the primary DB name
		return $cache->makeGlobalKey(
			'rdbms-server-states',
			self::VERSION,
			$this->lb->getServerName( $this->lb->getWriterIndex() ),
			implode( '-', $serverIndexes )
		);
	}

	/**
	 * @return ScopedCallback|null
	 */
	private function acquireServerStatesLoopGuard() {
		if ( $this->serverStatesKeyLocked ) {
			return null; // locked
		}

		$this->serverStatesKeyLocked = true; // lock

		return new ScopedCallback( function () {
			$this->serverStatesKeyLocked = false; // unlock
		} );
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
	}
}
