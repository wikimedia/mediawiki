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
use IStoreKeyEncoder;
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
 * This uses both local server and local datacenter caches for DB server state information.
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

	/** Cache key version */
	private const VERSION = 2;

	/** Target time-till-refresh for DB server states */
	private const STATE_TARGET_TTL = 1;
	/** Seconds to persist DB server states on cache (fresh or stale) */
	private const STATE_PRESERVE_TTL = 60;

	/**
	 * @param ILoadBalancer $lb
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wCache
	 * @param array $options Additional parameters include:
	 *   - movingAveRatio: maximum new gauge coefficient for moving averages
	 *      when the new gauge is 1 second newer than the prior one [default: .8]
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
		$this->movingAveRatio = (float)( $options['movingAveRatio'] ?? 0.8 );
		$this->lagWarnThreshold = $options['lagWarnThreshold'] ?? LoadBalancer::MAX_LAG_DEFAULT;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function setStatsdDataFactory( StatsdDataFactoryInterface $statsFactory ) {
		$this->statsd = $statsFactory;
	}

	final public function scaleLoads( array &$weightByServer ) {
		if ( count( $weightByServer ) <= 1 ) {
			// Single-server group; relative adjustments are pointless since
			return;
		}

		$serverIndexes = array_keys( $weightByServer );
		$stateByServerIndex = $this->getServerStates( $serverIndexes );
		foreach ( $weightByServer as $i => $weight ) {
			$scale = $this->getWeightScale( $stateByServerIndex[$i] );
			$weightByServer[$i] = (int)round( $weight * $scale );
		}
	}

	final public function getLagTimes( array $serverIndexes ): array {
		$lagByServerIndex = [];

		$stateByServerIndex = $this->getServerStates( $serverIndexes );
		foreach ( $stateByServerIndex as $i => $state ) {
			$lagByServerIndex[$i] = $state[self::STATE_LAG];
		}

		return $lagByServerIndex;
	}

	public function getServerStates( array $serverIndexes ): array {
		$stateByServerIndex = array_fill_keys( $serverIndexes, null );
		// Perform any cache regenerations in randomized order so that the
		// DB servers will each have similarly up-to-date state cache entries.
		$shuffledServerIndexes = $serverIndexes;
		shuffle( $shuffledServerIndexes );
		$now = $this->getCurrentTime();

		$scopeLocks = [];
		$serverIndexesCompute = [];
		$scKeyByServerIndex = [];
		$wcKeyByServerIndex = [];
		foreach ( $shuffledServerIndexes as $i ) {
			$scKeyByServerIndex[$i] = $this->makeStateKey( $this->srvCache, $i );
			$stateByServerIndex[$i] = $this->srvCache->get( $scKeyByServerIndex[$i] ) ?: null;
			if ( $this->isStateFresh( $stateByServerIndex[$i], $now ) ) {
				$this->logger->debug(
					__METHOD__ . ": fresh local cache hit for '{db_server}'",
					[ 'db_server' => $this->lb->getServerName( $i ) ]
				);
			} else {
				$scopeLocks[$i] = $this->srvCache->getScopedLock( $scKeyByServerIndex[$i], 0, 10 );
				if ( $scopeLocks[$i] || !$stateByServerIndex[$i] ) {
					$wcKeyByServerIndex[$i] = $this->makeStateKey( $this->wanCache, $i );
				}
			}
		}

		$valueByKey = $this->wanCache->getMulti( $wcKeyByServerIndex );
		foreach ( $wcKeyByServerIndex as $i => $wcKey ) {
			$value = $valueByKey[$wcKey] ?? null;
			if ( $value ) {
				$stateByServerIndex[$i] = $value;
				if ( $scopeLocks[$i] ) {
					$this->srvCache->set( $scKeyByServerIndex[$i], $value );
				}
				if ( $this->isStateFresh( $value, $now ) ) {
					$this->logger->debug(
						__METHOD__ . ": fresh WAN cache hit for '{db_server}'",
						[ 'db_server' => $this->lb->getServerName( $i ) ]
					);
				} elseif ( $scopeLocks[$i] ) {
					$serverIndexesCompute[] = $i;
				} else {
					$this->logger->info(
						__METHOD__ . ": mutex busy, stale WAN cache hit for '{db_server}'",
						[ 'db_server' => $this->lb->getServerName( $i ) ]
					);
				}
			} elseif ( $scopeLocks[$i] ) {
				$serverIndexesCompute[] = $i;
			} elseif ( $stateByServerIndex[$i] ) {
				$this->logger->info(
					__METHOD__ . ": mutex busy, stale local cache hit for '{db_server}'",
					[ 'db_server' => $this->lb->getServerName( $i ) ]
				);
			} else {
				$stateByServerIndex[$i] = $this->newInitialServerState();
			}
		}

		foreach ( $serverIndexesCompute as $i ) {
			$state = $this->computeServerState( $i, $stateByServerIndex[$i] );
			$stateByServerIndex[$i] = $state;
			$this->srvCache->set( $scKeyByServerIndex[$i], $state, self::STATE_PRESERVE_TTL );
			$this->wanCache->set( $wcKeyByServerIndex[$i], $state, self::STATE_PRESERVE_TTL );
			$this->logger->info(
				__METHOD__ . ": mutex acquired; regenerated cache for '{db_server}'",
				[ 'db_server' => $this->lb->getServerName( $i ) ]
			);
		}

		return $stateByServerIndex;
	}

	protected function makeStateKey( IStoreKeyEncoder $cache, int $i ) {
		return $cache->makeGlobalKey(
			'rdbms-gauge',
			self::VERSION,
			$this->lb->getClusterName(),
			$this->lb->getServerName( $this->lb->getWriterIndex() ),
			$this->lb->getServerName( $i )
		);
	}

	/**
	 * @param int $i
	 * @param array|null $priorState
	 * @return array<string,mixed>
	 * @phan-return array{up:float,lag:float|int|false,time:float}
	 * @throws DBAccessError
	 */
	protected function computeServerState( int $i, ?array $priorState ) {
		$startTime = $this->getCurrentTime();
		// Double check for circular recursion in computeServerStates()/getWeightScale().
		// Mainly, connection attempts should use LoadBalancer::getServerConnection()
		// rather than something that will pick a server based on the server states.
		$scopedGuard = $this->acquireServerStatesLoopGuard();

		$cluster = $this->lb->getClusterName();
		$serverName = $this->lb->getServerName( $i );
		$statServerName = str_replace( '.', '_', $serverName );
		$isPrimary = ( $i == $this->lb->getWriterIndex() );

		$newState = $this->newInitialServerState();

		if ( $isPrimary && $this->lb->getServerInfo( $i )['load'] <= 0 ) {
			// Callers only use this server when they have *no choice* anyway (e.g. writes)
			$newState[self::STATE_AS_OF] = $this->getCurrentTime();
			// Avoid connecting, especially since it might reside in a remote datacenter
			return $newState;
		}

		// Get a new, untracked, connection in order to gauge server health
		$flags = $this->lb::CONN_UNTRACKED_GAUGE | $this->lb::CONN_SILENCE_ERRORS;
		// Get a connection to this server without triggering other server connections
		$conn = $this->lb->getServerConnection( $i, $this->lb::DOMAIN_ANY, $flags );
		// Check if the server is up
		$gaugeUp = $conn ? 1.0 : 0.0;
		// Determine the amount of replication lag on this server
		if ( $isPrimary ) {
			$gaugeLag = 0;
		} elseif ( $conn ) {
			try {
				$gaugeLag = $conn->getLag();
			} catch ( DBError $e ) {
				$gaugeLag = false;
			}
		} else {
			$gaugeLag = false;
		}
		// Only keep one connection open at a time
		if ( $conn ) {
			$conn->close( __METHOD__ );
		}

		$endTime = $this->getCurrentTime();
		$newState[self::STATE_AS_OF] = $endTime;
		$newState[self::STATE_LAG] = $gaugeLag;
		if ( $priorState ) {
			$newState[self::STATE_UP] = $this->movingAverage(
				$priorState[self::STATE_UP],
				$gaugeUp,
				max( $endTime - $priorState[self::STATE_AS_OF], 0.0 ),
				$this->movingAveRatio
			);
		} else {
			$newState[self::STATE_UP] = $gaugeUp;
		}
		$newState[self::STATE_GEN_DELAY] = max( $endTime - $startTime, 0.0 );

		// Get new weight scale
		$newScale = $this->getWeightScale( $newState );
		$this->statsd->gauge( "loadbalancer.weight.$cluster.$statServerName", $newScale );

		if ( $gaugeLag === false ) {
			$this->logger->error(
				__METHOD__ . ": host {db_server} is not replicating?",
				[ 'db_server' => $serverName ]
			);
		} else {
			$this->statsd->timing( "loadbalancer.lag.$cluster.$statServerName", $gaugeLag * 1000 );
			if ( $gaugeLag > $this->lagWarnThreshold ) {
				$this->logger->warning(
					"Server {db_server} has {lag} seconds of lag (>= {maxlag})",
					[
						'db_server' => $serverName,
						'lag' => $gaugeLag,
						'maxlag' => $this->lagWarnThreshold
					]
				);
			}
		}

		return $newState;
	}

	/**
	 * @param array $state
	 * @return float
	 */
	protected function getWeightScale( array $state ) {
		// Use the connectivity as a coefficient
		return $state[self::STATE_UP];
	}

	/**
	 * @return array<string,mixed>
	 * @phan-return array{up:float,lag:float|int|false,time:float,delay:float}
	 */
	protected function newInitialServerState() {
		return [
			// Moving average of connectivity; treat as good
			self::STATE_UP => 1.0,
			// Seconds of replication lag; treat as none
			self::STATE_LAG => 0,
			// UNIX timestamp of state generation completion; treat as "outdated"
			self::STATE_AS_OF => 0.0,
			// Seconds elapsed during state generation; treat as "fast"
			self::STATE_GEN_DELAY => 0.0
		];
	}

	/**
	 * @param array|null $state
	 * @param float $now
	 * @return bool
	 */
	protected function isStateFresh( $state, $now ) {
		return (
			$state &&
			!$this->isStateRefreshDue(
				$state[self::STATE_AS_OF],
				$state[self::STATE_GEN_DELAY],
				self::STATE_TARGET_TTL,
				$now
			)
		);
	}

	/**
	 * @param float $priorAsOf
	 * @param float $priorGenDelay
	 * @param float|int $referenceTTL
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
		// the value might not refresh until a modest time after the nominal expiry.
		$chance = exp( -64 * min( $genRatio, 0.1 ) ) * ( $ttrRatio ** 4 );

		return ( mt_rand( 1, 1000000000 ) <= 1000000000 * $chance );
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
	 * @return ScopedCallback
	 */
	private function acquireServerStatesLoopGuard() {
		if ( $this->serverStatesKeyLocked ) {
			throw new RuntimeException(
				"Circular recursion detected while regenerating server states cache. " .
				"This may indicate improper connection handling in " . get_class( $this )
			);
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
