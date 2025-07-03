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

use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\IStoreKeyEncoder;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;

/**
 * Basic DB load monitor with no external dependencies
 *
 * This uses both local server and local datacenter caches for DB server state information.
 *
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
	/** @var StatsFactory */
	protected $statsFactory;
	/** @var int|float */
	private $maxConnCount;
	private int $totalConnectionsAdjustment;

	/** @var float|null */
	private $wallClockOverride;

	/** @var bool Whether the "server states" cache key is in the process of being updated */
	private $serverStatesKeyLocked = false;

	/** Cache key version */
	private const VERSION = 3;

	/** Target time-till-refresh for DB server states */
	private const STATE_TARGET_TTL = 10;
	/** Seconds to persist DB server states on cache (fresh or stale) */
	private const STATE_PRESERVE_TTL = 60;

	/**
	 * @inheritDoc
	 */
	public function __construct(
		ILoadBalancer $lb,
		BagOStuff $srvCache,
		WANObjectCache $wCache,
		LoggerInterface $logger,
		StatsFactory $statsFactory,
		$options
	) {
		$this->lb = $lb;
		$this->srvCache = $srvCache;
		$this->wanCache = $wCache;
		$this->logger = $logger;
		$this->statsFactory = $statsFactory;
		if ( isset( $options['maxConnCount'] ) ) {
			$this->maxConnCount = (int)( $options['maxConnCount'] );
		} else {
			$this->maxConnCount = INF;
		}

		$this->totalConnectionsAdjustment = (int)( $options['totalConnectionsAdjustment'] ?? 10 );
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	public function scaleLoads( array &$weightByServer ) {
		if ( count( $weightByServer ) <= 1 ) {
			// Single-server group; relative adjustments are pointless
			return;
		}

		$serverIndexes = array_keys( $weightByServer );
		$stateByServerIndex = $this->getServerStates( $serverIndexes );
		$totalConnections = 0;
		$totalWeights = 0;
		$circuitBreakingEnabled = true;
		foreach ( $weightByServer as $i => $weight ) {
			$serverState = $stateByServerIndex[$i];
			$totalConnections += (int)$serverState[self::STATE_CONN_COUNT] * $serverState[self::STATE_UP];
			$totalWeights += $weight;

			// Set up circuit breaking. If at least one replica can take more connections
			// allow the flow.
			if (
				$serverState[self::STATE_UP] &&
				$weight > 0 &&
				$serverState[self::STATE_CONN_COUNT] < $this->maxConnCount
			) {
				$circuitBreakingEnabled = false;
			}
		}

		if ( $circuitBreakingEnabled ) {
			throw new DBConnectionError(
				null, 'Database servers in ' . $this->lb->getClusterName() . ' are overloaded. ' .
				'In order to protect application servers, the circuit breaking to databases of this section ' .
				'have been activated. Please try again a few seconds.'
			);
		}

		foreach ( $weightByServer as $i => $weight ) {
			if (
				// host is down or
				!$stateByServerIndex[$i][self::STATE_UP] ||
				// host is primary or explicitly set to zero
				$weight <= 0
			) {
				$weightByServer[$i] = 0;
				continue;
			}
			$connRatio = $stateByServerIndex[$i][self::STATE_CONN_COUNT] /
				( $totalConnections + $this->totalConnectionsAdjustment );
			$weightRatio = $weight / $totalWeights;
			$diffRatio = $connRatio - $weightRatio;
			$adjustedRatio = max( $weightRatio - ( $diffRatio / 2.0 ), 0 );
			$weightByServer[$i] = (int)round( $totalWeights * $adjustedRatio );
		}
	}

	protected function getServerStates( array $serverIndexes ): array {
		$stateByServerIndex = array_fill_keys( $serverIndexes, null );
		// Perform any cache regenerations in randomized order so that the
		// DB servers will each have similarly up-to-date state cache entries.
		$shuffledServerIndexes = $serverIndexes;
		shuffle( $shuffledServerIndexes );

		foreach ( $shuffledServerIndexes as $i ) {
			$key = $this->makeStateKey( $this->srvCache, $i );
			$state = $this->srvCache->get( $key ) ?: null;
			if ( $this->isStateFresh( $state ) ) {
				$this->logger->debug(
					__METHOD__ . ": fresh local cache hit for '{db_server}'",
					[ 'db_server' => $this->lb->getServerName( $i ) ]
				);
			} else {
				$lock = $this->srvCache->getScopedLock( $key, 0, 10 );
				if ( $lock || !$state ) {
					$state = $this->getStateFromWanCache( $i, $state );
					$this->srvCache->set( $key, $state, self::STATE_PRESERVE_TTL );
				}
			}
			$stateByServerIndex[$i] = $state;
		}
		return $stateByServerIndex;
	}

	protected function getStateFromWanCache( int $i, ?array $srvPrevState ): array {
		$hit = true;
		$key = $this->makeStateKey( $this->wanCache, $i );
		$state = $this->wanCache->getWithSetCallback(
			$key,
			self::STATE_PRESERVE_TTL,
			function ( $wanPrevState ) use ( $srvPrevState, $i, &$hit ) {
				$prevState = $wanPrevState ?: $srvPrevState ?: null;
				$hit = false;
				return $this->computeServerState( $i, $prevState );
			},
			[ 'lockTSE' => 30 ]
		);
		if ( $hit ) {
			$this->logger->debug(
				__METHOD__ . ": WAN cache hit for '{db_server}'",
				[ 'db_server' => $this->lb->getServerName( $i ) ]
			);
		} else {
			$this->logger->info(
				__METHOD__ . ": mutex acquired; regenerated cache for '{db_server}'",
				[ 'db_server' => $this->lb->getServerName( $i ) ]
			);
		}
		return $state;
	}

	protected function makeStateKey( IStoreKeyEncoder $cache, int $i ): string {
		return $cache->makeGlobalKey(
			'rdbms-gauge',
			self::VERSION,
			$this->lb->getClusterName(),
			$this->lb->getServerName( ServerInfo::WRITER_INDEX ),
			$this->lb->getServerName( $i )
		);
	}

	/**
	 * @param int $i
	 * @param array|null $previousState
	 * @return array<string,mixed>
	 * @phan-return array{up:float,conn_count:float|int|false,time:float}
	 * @throws DBAccessError
	 */
	protected function computeServerState( int $i, ?array $previousState ) {
		// Double check for circular recursion in computeServerStates()/getWeightScale().
		// Mainly, connection attempts should use LoadBalancer::getServerConnection()
		// rather than something that will pick a server based on the server states.
		$this->acquireServerStatesLoopGuard();

		$cluster = $this->lb->getClusterName();
		$serverName = $this->lb->getServerName( $i );
		$statServerName = str_replace( '.', '_', $serverName );

		$newState = $this->newInitialServerState();

		if ( $this->lb->getServerInfo( $i )['load'] <= 0 ) {
			// Callers only use this server when they have *no choice* anyway (e.g. primary)
			$newState[self::STATE_AS_OF] = $this->getCurrentTime();
			// Avoid connecting, especially since it might reside in a remote datacenter
			return $newState;
		}

		// Get a new, untracked, connection in order to gauge server health
		$flags = ILoadBalancer::CONN_UNTRACKED_GAUGE | ILoadBalancer::CONN_SILENCE_ERRORS;
		// Get a connection to this server without triggering other server connections
		$conn = $this->lb->getServerConnection( $i, ILoadBalancer::DOMAIN_ANY, $flags );
		// Determine the number of open connections in this server
		if ( $conn ) {
			try {
				$connCount = $this->getConnCountForDb( $conn );
			} catch ( DBError ) {
				$connCount = false;
			}
		} else {
			$connCount = false;
		}
		// Only keep one connection open at a time
		if ( $conn ) {
			$conn->close( __METHOD__ );
		}

		$endTime = $this->getCurrentTime();
		$newState[self::STATE_AS_OF] = $endTime;
		$newState[self::STATE_CONN_COUNT] = $connCount;
		$newState[self::STATE_UP] = $conn ? 1.0 : 0.0;
		if ( $previousState ) {
			$newState[self::STATE_CONN_COUNT] = ( $previousState[self::STATE_CONN_COUNT] + $connCount ) / 2;
		}

		if ( $connCount === false ) {
			$this->logger->error(
				__METHOD__ . ": host {db_server} is not up?",
				[ 'db_server' => $serverName ]
			);
		} else {
			$this->statsFactory->getGauge( 'rdbms_open_connection_total' )
				->setLabel( 'db_cluster', $cluster )
				->setLabel( 'db_server', $serverName )
				->copyToStatsdAt( "loadbalancer.connCount.$cluster.$statServerName" )
				->set( (int)$connCount );

			if ( $connCount > $this->maxConnCount ) {
				$this->logger->warning(
					"Server {db_server} has {conn_count} open connections (>= {max_conn})",
					[
						'db_server' => $serverName,
						'conn_count' => $connCount,
						'max_conn' => $this->maxConnCount
					]
				);
			}
		}

		return $newState;
	}

	/**
	 * @return array<string,mixed>
	 * @phan-return array{up:float,conn_count:int|int|false,time:float}
	 */
	protected function newInitialServerState() {
		return [
			// Moving average of connectivity; treat as good
			self::STATE_UP => 1.0,
			// Number of connections to that replica; treat as none
			self::STATE_CONN_COUNT => 0,
			// UNIX timestamp of state generation completion; treat as "outdated"
			self::STATE_AS_OF => 0.0,
		];
	}

	/**
	 * @param array|null $state
	 * @return bool
	 */
	private function isStateFresh( $state ) {
		if ( !$state ) {
			return false;
		}
		return $this->getCurrentTime() - $state[self::STATE_AS_OF] > self::STATE_TARGET_TTL;
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

	private function getConnCountForDb( IDatabase $conn ): int {
		if ( $conn->getType() !== 'mysql' ) {
			return 0;
		}
		$query = new Query(
			'SELECT COUNT(*) AS pcount FROM INFORMATION_SCHEMA.PROCESSLIST',
			ISQLPlatform::QUERY_SILENCE_ERRORS | ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
			'SELECT',
			null,
			'SELECT COUNT(*) AS pcount FROM INFORMATION_SCHEMA.PROCESSLIST'
		);
		$res = $conn->query( $query, __METHOD__ );
		$row = $res ? $res->fetchObject() : false;
		return $row ? (int)$row->pcount : 0;
	}
}
