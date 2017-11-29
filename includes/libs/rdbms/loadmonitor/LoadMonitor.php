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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use BagOStuff;
use WANObjectCache;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * Basic DB load monitor with no external dependencies
 *
 * Uses local and shared caches for server state information
 *
 * The "domain" parameters are unused, though they might be used in the future.
 * Therefore, at present, this assumes one channel of replication per server.
 *
 * @ingroup Database
 */
class LoadMonitor implements ILoadMonitor {
	/** @var ILoadBalancer */
	protected $parent;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var WANObjectCache */
	protected $wanCache;
	/** @var LoggerInterface */
	protected $replLogger;

	/** @var float Moving average ratio for reported failures */
	private $movingAveRatioConnFail = 0.1;
	/** @var float Moving average ratio for reported failures */
	private $movingAveRatioSyncFail = 0.1;
	/** @var int Amount of replication lag in seconds before warnings are logged */
	private $lagWarnThreshold;

	/** @var float|null */
	private $wallClockOverride;

	/** @var int Avoid failure ping cache updates while cache updates are running */
	private $recursionGuard = 0;

	/** @var int Default 'max lag' in seconds when unspecified */
	const LAG_WARN_THRESHOLD = 10;

	/** @var int cache key version */
	const VERSION = 1;
	/** @var int Maximum effective logical TTL for server state cache */
	const POLL_PERIOD_MS = 500;
	/** @var int How long to cache server states including time past logical expiration */
	const STATE_PRESERVE_TTL = 60;
	/** @var int Max interval within which a server state refresh should happen */
	const TIME_TILL_REFRESH = 1;

	const DOMAIN_ANY = ILoadBalancer::DOMAIN_ANY;

	/**
	 * @param ILoadBalancer $lb
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wCache
	 * @param array $options
	 *   - movingAveRatioConnFail: moving average constant for reported connection failures
	 *   - movingAveRatioSyncFail: moving average constant for reported query failures
	 *   - lagWarnThreshold: how many seconds of lag trigger warnings
	 */
	public function __construct(
		ILoadBalancer $lb, BagOStuff $srvCache, WANObjectCache $wCache, array $options = []
	) {
		$this->parent = $lb;
		$this->srvCache = $srvCache;
		$this->wanCache = $wCache;
		$this->replLogger = new NullLogger();

		$fields = [ 'movingAveRatioConnFail', 'movingAveRatioSyncFail' ];
		foreach ( $fields as $field ) {
			if ( isset( $options[$field] ) ) {
				$this->$field = $options[$field];
			}
		}

		$this->lagWarnThreshold = $options['lagWarnThreshold'] ?? self::LAG_WARN_THRESHOLD;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->replLogger = $logger;
	}

	final public function scaleLoads( array &$weightByServer, $domain ) {
		$serverIndexes = array_keys( $weightByServer );
		$states = $this->getServerStates( $serverIndexes, $domain );
		$newScalesByServer = $states['weightScales'];
		foreach ( $weightByServer as $i => $weight ) {
			if ( isset( $newScalesByServer[$i] ) ) {
				$weightByServer[$i] = (int)ceil( $weight * $newScalesByServer[$i] );
			} else { // server recently added to config?
				$host = $this->parent->getServerName( $i );
				$this->replLogger->error( __METHOD__ . ": host $host not in cache" );
			}
		}
	}

	final public function getLagTimes( array $serverIndexes, $domain ) {
		return $this->getServerStates( $serverIndexes, $domain )['lagTimes'];
	}

	final public function pingFailure( $serverIndex, $domain, $type ) {
		if ( $this->recursionGuard ) {
			return; // getServerStates() is already updating the cache
		}

		if ( $type === self::TYPE_CONNECTION ) {
			$this->pingConnFailure( $serverIndex, $domain );
		} elseif ( $type === self::TYPE_POS_SYNC ) {
			$this->pingSyncFailure( $serverIndex, $domain );
		} else {
			throw new UnexpectedValueException( __METHOD__  . ": got bad failure type '$type'" );
		}
	}

	final public function getSyncFailureRate( $serverIndex, $domain ) {
		$state = $this->wanCache->get( $this->getFailureKey( $this->wanCache, $serverIndex ) );

		return $state ? $state['failRate'] : 0.0;
	}

	/**
	 * @param int $serverIndex
	 * @param string|bool $domain
	 */
	protected function pingConnFailure( $serverIndex, $domain ) {
		// Server index list is part of the cache key so keep that consistent for all uses
		$serverIndexes = range( 0, $this->parent->getServerCount() - 1 );
		// Update the cache to lower the weight scale value for this server
		$this->wanCache->getWithSetCallback(
			$this->getStatesCacheKey( $this->wanCache, $serverIndexes ),
			self::TIME_TILL_REFRESH, // logical expiry in seconds
			function ( $curState ) use ( $serverIndex ) {
				if ( !isset( $curState['weightScales'][$serverIndex] ) ) {
					return false; // getServerStates() should have populated this
				}

				// Get new weight scale using a moving average of the naïve and prior values
				$curState['weightScales'][$serverIndex] = $this->getNewScaleViaMovingAve(
					$curState['weightScales'][$serverIndex],
					0.0, // e.g. "not usable"
					$this->movingAveRatioConnFail
				);

				return $curState;
			},
			[
				// One thread can update at a time; others use the old value
				'lockTSE' => self::STATE_PRESERVE_TTL,
				'staleTTL' => self::STATE_PRESERVE_TTL,
				// Force a cache miss to make the callback always run
				'minAsOf' => INF
			]
		);
	}

	/**
	 * @param int $serverIndex
	 * @param string|bool $domain
	 */
	protected function pingSyncFailure( $serverIndex, $domain ) {
		$this->wanCache->getWithSetCallback(
			$this->getFailureKey( $this->wanCache, $serverIndex ),
			WANObjectCache::TTL_MINUTE,
			function ( $curState ) {
				$now = $this->getCurrentTime();
				$curState = $curState ?: [ 'failRate' => 0.0, 'lastFailTime' => null ];
				// Get new weight scale using a moving average of the naïve and prior values
				return [
					'failRate' => $this->getNewScaleViaMovingAve(
						$curState['failRate'],
						$curState['lastFailTime']
							? 1 / ( $now - $curState['lastFailTime'] )
							: 1 / 60, // a priori starting rate
						$this->movingAveRatioSyncFail
					),
					'lastFailTime' => $now
				];
			},
			[
				// Force a cache miss to make the callback always run
				'minAsOf' => INF
			]
		);
	}

	/**
	 * @param array $serverIndexes
	 * @param string|bool $domain
	 * @return array
	 */
	protected function getServerStates( array $serverIndexes, $domain ) {
		// Represent the cluster by the name of the master DB
		$cluster = $this->parent->getServerName( $this->parent->getWriterIndex() );

		// Randomize TTLs to reduce stampedes
		$ttlMs = mt_rand( 1 * 1e6, self::POLL_PERIOD_MS * 1e6 ) / 1e6;
		$minAsOfTime = $this->getCurrentTime() - $ttlMs / 1e3;

		// (a) Check the local server cache
		$srvCacheKey = $this->getStatesCacheKey( $this->srvCache, $serverIndexes );
		$value = $this->srvCache->get( $srvCacheKey );
		if ( $value && $value['timestamp'] > $minAsOfTime ) {
			$this->replLogger->debug( __METHOD__ . ": used fresh $cluster lag times" );

			return $value; // cache hit
		}

		// (b) Value is stale/missing; try to use/refresh the shared cache
		$scopedLock = $this->srvCache->getScopedLock( $srvCacheKey, 0, 10 );
		if ( !$scopedLock && $value ) {
			$this->replLogger->debug( __METHOD__ . ": used stale $cluster lag times" );
			// (b1) Another thread on this server is already checking the shared cache
			return $value;
		}

		// (b2) This thread gets to check the shared cache or (b3) value is missing
		$staleValue = $value;
		$ran = false; // whether the regeneration callback ran
		$value = $this->wanCache->getWithSetCallback(
			$this->getStatesCacheKey( $this->wanCache, $serverIndexes ),
			self::TIME_TILL_REFRESH, // 1 second logical expiry
			function ( $oldValue ) use ( $serverIndexes, $domain, $staleValue, $cluster, &$ran ) {
				$this->replLogger->info( __METHOD__ . ": re-calculated $cluster lag times" );

				$ran = true;
				$this->recursionGuard++; // ignore failure ping updates
				try {
					$states = $this->computeServerStates(
						$serverIndexes,
						$domain,
						$oldValue ?: $staleValue // fallback to local cache stale value
					);
				} finally {
					$this->recursionGuard--;
				}

				return $states;
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

		if ( $ran ) {
			$this->replLogger->info( __METHOD__ . ": used WAN cache $cluster lag times" );
		}

		// Backfill the local server cache
		$this->srvCache->set( $srvCacheKey, $value, self::STATE_PRESERVE_TTL );

		return $value;
	}

	/**
	 * @param array $serverIndexes
	 * @param string|bool $domain
	 * @param array|false $priorStates
	 * @return array
	 * @throws DBAccessError
	 */
	protected function computeServerStates( array $serverIndexes, $domain, $priorStates ) {
		// Check if there is just a master DB (no replication involved)
		if ( $this->parent->getServerCount() <= 1 ) {
			return $this->getPlaceholderServerStates( $serverIndexes );
		}

		$priorScales = $priorStates ? $priorStates['weightScales'] : [];

		$lagTimes = [];
		$weightScales = [];
		foreach ( $serverIndexes as $i ) {
			$isMaster = ( $i == $this->parent->getWriterIndex() );
			// If the master DB has zero load, then typical read queries do not use it.
			// In that case, avoid connecting to it since this method might run in any
			// datacenter, and the master DB might be geographically remote.
			if ( $isMaster && !$this->parent->isNonZeroLoad( $i ) ) {
				$lagTimes[$i] = 0;
				// Callers only use this DB if they have *no choice* anyway (e.g. writes)
				$weightScales[$i] = 1.0;
				continue;
			}

			$host = $this->parent->getServerName( $i );
			# Handles with open transactions are avoided since they might be subject
			# to REPEATABLE-READ snapshots, which could affect the lag estimate query.
			$flags = ILoadBalancer::CONN_TRX_AUTOCOMMIT;
			$conn = $this->parent->getAnyOpenConnection( $i, $flags );
			if ( $conn ) {
				$close = false; // already open
			} else {
				$conn = $this->parent->openConnection( $i, ILoadBalancer::DOMAIN_ANY, $flags );
				$close = true; // new connection
			}

			// Get new weight scale using a moving average of the naïve and prior values
			$lastScale = $priorScales[$i] ?? 1.0;
			$naiveScale = $this->getWeightScale( $i, $conn ?: null );
			$newScale = $this->getNewScaleViaMovingAve(
				$lastScale,
				$naiveScale,
				$this->movingAveRatioConnFail
			);

			// Scale from 0% to 100% of nominal weight (sanity)
			$weightScales[$i] = max( $newScale, 0.0 );

			// Mark replication lag on this server as "false" if it is unreacheable
			if ( !$conn ) {
				$lagTimes[$i] = $isMaster ? 0 : false;
				$this->replLogger->error(
					__METHOD__ . ": host {db_server} is unreachable",
					[ 'db_server' => $host ]
				);
				continue;
			}

			// Determine the amount of replication lag on this server
			if ( $isMaster || $conn->getLBInfo( 'is static' ) ) {
				$lagTimes[$i] = 0; // server is master or data never changes
			} else {
				try {
					$lagTimes[$i] = $conn->getLag();
				} catch ( DBError $e ) {
					// Mark the lag time as "false" if it cannot be queried
					$lagTimes[$i] = false;
				}

				if ( $lagTimes[$i] === false ) {
					$this->replLogger->error(
						__METHOD__ . ": host {db_server} is not replicating?",
						[ 'db_server' => $host ]
					);
				} elseif ( $lagTimes[$i] > $this->lagWarnThreshold ) {
					$this->replLogger->error(
						"Server {host} has {lag} seconds of lag (>= {maxlag})",
						[
							'host' => $host,
							'lag' => $lagTimes[$i],
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
				$this->parent->closeConnection( $conn );
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
		// Lag is per-server, not per-DB, so key on the master DB name
		return $cache->makeGlobalKey(
			'rdbms-server-states',
			self::VERSION,
			$this->parent->getServerName( $this->parent->getWriterIndex() ),
			implode( '-', $serverIndexes )
		);
	}

	/**
	 * @param WANObjectCache|BagOStuff $cache
	 * @param int $serverIndex
	 * @return string
	 */
	private function getFailureKey( $cache, $serverIndex ) {
		// Lag is per-server, not per-DB, so key on the master DB name
		return $cache->makeGlobalKey(
			'rdbms-server-failure-rate',
			self::VERSION,
			$this->parent->getServerName( $this->parent->getWriterIndex() ),
			$serverIndex
		);
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
