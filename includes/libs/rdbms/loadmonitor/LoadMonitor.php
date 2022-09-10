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
	protected $replLogger;
	/** @var StatsdDataFactoryInterface */
	protected $statsd;

	/** @var float Moving average ratio (e.g. 0.1 for 10% weight to new weight) */
	private $movingAveRatio;
	/** @var int Amount of replication lag in seconds before warnings are logged */
	private $lagWarnThreshold;

	/** @var float|null */
	private $wallClockOverride;

	/** @var bool Whether the "server states" cache key is in the process of being updated */
	private $serverStatesKeyLocked = false;

	/** @var int cache key version */
	private const VERSION = 1;
	/** @var int Maximum effective logical TTL for server state cache */
	private const POLL_PERIOD_MS = 500;
	/** @var int How long to cache server states including time past logical expiration */
	private const STATE_PRESERVE_TTL = 60;
	/** @var int Max interval within which a server state refresh should happen */
	private const TIME_TILL_REFRESH = 1;

	/**
	 * @param ILoadBalancer $lb
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wCache
	 * @param array $options
	 *   - movingAveRatio: moving average constant for server weight updates based on lag
	 *   - lagWarnThreshold: how many seconds of lag trigger warnings
	 */
	public function __construct(
		ILoadBalancer $lb, BagOStuff $srvCache, WANObjectCache $wCache, array $options = []
	) {
		$this->lb = $lb;
		$this->srvCache = $srvCache;
		$this->wanCache = $wCache;
		$this->replLogger = new NullLogger();
		$this->statsd = new NullStatsdDataFactory();

		$this->movingAveRatio = $options['movingAveRatio'] ?? 0.1;
		$this->lagWarnThreshold = $options['lagWarnThreshold'] ?? LoadBalancer::MAX_LAG_DEFAULT;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->replLogger = $logger;
	}

	public function setStatsdDataFactory( StatsdDataFactoryInterface $statsFactory ) {
		$this->statsd = $statsFactory;
	}

	final public function scaleLoads( array &$weightByServer, $domain ) {
		$serverIndexes = array_keys( $weightByServer );
		$states = $this->getServerStates( $serverIndexes, $domain );
		$newScalesByServer = $states['weightScales'];
		foreach ( $weightByServer as $i => $weight ) {
			if ( isset( $newScalesByServer[$i] ) ) {
				$weightByServer[$i] = (int)ceil( $weight * $newScalesByServer[$i] );
			} else { // server recently added to config?
				$host = $this->lb->getServerName( $i );
				$this->replLogger->error( __METHOD__ . ": host $host not in cache" );
			}
		}
	}

	final public function getLagTimes( array $serverIndexes, $domain ) {
		return $this->getServerStates( $serverIndexes, $domain )['lagTimes'];
	}

	/**
	 * @param array $serverIndexes
	 * @param string|false $domain
	 * @return array
	 * @throws DBAccessError
	 */
	protected function getServerStates( array $serverIndexes, $domain ) {
		// Represent the cluster by the name of the primary DB
		$cluster = $this->lb->getServerName( $this->lb->getWriterIndex() );

		// Randomize logical TTLs to reduce stampedes
		$ageStaleSec = mt_rand( 1, self::POLL_PERIOD_MS ) / 1e3;
		$minAsOfTime = $this->getCurrentTime() - $ageStaleSec;

		// (a) Check the local server cache
		$srvCacheKey = $this->getStatesCacheKey( $this->srvCache, $serverIndexes );
		$value = $this->srvCache->get( $srvCacheKey );
		if ( $value && $value['timestamp'] > $minAsOfTime ) {
			$this->replLogger->debug( __METHOD__ . ": used fresh '$cluster' cluster status" );

			return $value; // cache hit
		}

		// (b) Value is stale/missing; try to use/refresh the shared cache
		$scopedLock = $this->srvCache->getScopedLock( $srvCacheKey, 0, 10 );
		if ( !$scopedLock && $value ) {
			$this->replLogger->debug( __METHOD__ . ": used stale '$cluster' cluster status" );
			// (b1) Another thread on this server is already checking the shared cache
			return $value;
		}

		// (b2) This thread gets to check the shared cache or (b3) value is missing
		$staleValue = $value;
		$updated = false; // whether the regeneration callback ran
		$value = $this->wanCache->getWithSetCallback(
			$this->getStatesCacheKey( $this->wanCache, $serverIndexes ),
			self::TIME_TILL_REFRESH, // 1 second logical expiry
			function ( $oldValue, &$ttl ) use ( $serverIndexes, $domain, $staleValue, &$updated ) {
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
					$domain,
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
			$this->replLogger->info( __METHOD__ . ": regenerated '$cluster' cluster status" );
		} else {
			$this->replLogger->debug( __METHOD__ . ": used cached '$cluster' cluster status" );
		}

		// Backfill the local server cache
		if ( $scopedLock ) {
			$this->srvCache->set( $srvCacheKey, $value, self::STATE_PRESERVE_TTL );
		}

		return $value;
	}

	/**
	 * @param array $serverIndexes
	 * @param string|false $domain
	 * @param array|false $priorStates
	 * @return array
	 * @throws DBAccessError
	 */
	protected function computeServerStates( array $serverIndexes, $domain, $priorStates ) {
		// Check if there is just a primary DB (no replication involved)
		if ( $this->lb->getServerCount() <= 1 ) {
			return $this->getPlaceholderServerStates( $serverIndexes );
		}

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
			# Handles with open transactions are avoided since they might be subject
			# to REPEATABLE-READ snapshots, which could affect the lag estimate query.
			$flags = ILoadBalancer::CONN_TRX_AUTOCOMMIT | ILoadBalancer::CONN_SILENCE_ERRORS;
			$conn = $this->lb->getAnyOpenConnection( $i, $flags );
			if ( $conn ) {
				$close = false; // already open
			} else {
				// Get a connection to this server without triggering other server connections
				$conn = $this->lb->getServerConnection( $i, ILoadBalancer::DOMAIN_ANY, $flags );
				$close = true; // new connection
			}

			// Get new weight scale using a moving average of the naïve and prior values
			$lastScale = $priorScales[$i] ?? 1.0;
			$naiveScale = $this->getWeightScale( $i, $conn ?: null );
			$newScale = $this->getNewScaleViaMovingAve(
				$lastScale,
				$naiveScale,
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
				$this->replLogger->error(
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
				$this->replLogger->error(
					__METHOD__ . ": host {db_server} is not replicating?",
					[ 'db_server' => $host ]
				);
			} else {
				$this->statsd->timing( "loadbalancer.lag.$cluster.$statHost", $lag * 1000 );
				if ( $lag > $this->lagWarnThreshold ) {
					$this->replLogger->warning(
						"Server {db_server} has {lag} seconds of lag (>= {maxlag})",
						[
							'db_server' => $host,
							'lag' => $lag,
							'maxlag' => $this->lagWarnThreshold
						]
					);
				}
			}

			if ( $close ) {
				# Close the connection to avoid sleeper connections piling up.
				# Note that the caller will pick one of these DBs and reconnect,
				# which is slightly inefficient, but this only matters for the lag
				# time cache miss cache, which is far less common that cache hits.
				$this->lb->closeConnection( $conn );
			}
		}

		return [
			'lagTimes' => $lagTimes,
			'weightScales' => $weightScales,
			'timestamp' => $this->getCurrentTime()
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
			'timestamp' => $this->getCurrentTime()
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
	 * Get the moving average weight scale given a naive and the last iteration value
	 *
	 * One case of particular note is if a server totally cannot have its state queried.
	 * Ideally, the scale should be able to drop from 1.0 to a miniscule amount (say 0.001)
	 * fairly quickly. To get the time to reach 0.001, some calculations can be done:
	 *
	 * SCALE = $naiveScale * $movAveRatio + $lastScale * (1 - $movAveRatio)
	 * SCALE = 0 * $movAveRatio + $lastScale * (1 - $movAveRatio)
	 * SCALE = $lastScale * (1 - $movAveRatio)
	 *
	 * Given a starting weight scale of 1.0:
	 * 1.0 * (1 - $movAveRatio)^(# iterations) = 0.001
	 * ceil( log<1 - $movAveRatio>(0.001) ) = (# iterations)
	 * t = (# iterations) * (POLL_PERIOD + SHARED_CACHE_TTL)
	 * t = (# iterations) * (1e3 * POLL_PERIOD_MS + SHARED_CACHE_TTL)
	 *
	 * If $movAveRatio is 0.5, then:
	 * t = ceil( log<0.5>(0.01) ) * 1.5 = 7 * 1.5 = 10.5 seconds [for 1% scale]
	 * t = ceil( log<0.5>(0.001) ) * 1.5 = 10 * 1.5 = 15 seconds [for 0.1% scale]
	 *
	 * If $movAveRatio is 0.8, then:
	 * t = ceil( log<0.2>(0.01) ) * 1.5 = 3 * 1.5 = 4.5 seconds [for 1% scale]
	 * t = ceil( log<0.2>(0.001) ) * 1.5 = 5 * 1.5 = 7.5 seconds [for 0.1% scale]
	 *
	 * Use of connection failure rate can greatly speed this process up
	 *
	 * @param float $lastScale Current moving average of scaling factors
	 * @param float $naiveScale New scaling factor
	 * @param float $movAveRatio Weight given to the new value
	 * @return float
	 * @since 1.35
	 */
	protected function getNewScaleViaMovingAve( $lastScale, $naiveScale, $movAveRatio ) {
		return $movAveRatio * $naiveScale + ( 1 - $movAveRatio ) * $lastScale;
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
