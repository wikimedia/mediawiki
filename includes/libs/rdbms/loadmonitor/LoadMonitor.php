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
use Wikimedia\ScopedCallback;
use BagOStuff;
use WANObjectCache;

/**
 * Basic DB load monitor with no external dependencies
 * Uses memcached to cache the replication lag for a short time
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

	/** @var float Moving average ratio (e.g. 0.1 for 10% weight to new weight) */
	private $movingAveRatio;
	/** @var int Amount of replication lag in seconds before warnings are logged */
	private $lagWarnThreshold;

	/** @var int cache key version */
	const VERSION = 1;
	/** @var int Default 'max lag' in seconds when unspecified */
	const LAG_WARN_THRESHOLD = 10;

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
		$this->parent = $lb;
		$this->srvCache = $srvCache;
		$this->wanCache = $wCache;
		$this->replLogger = new NullLogger();

		$this->movingAveRatio = $options['movingAveRatio'] ?? 0.1;
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
				$weightByServer[$i] = $weight * $newScalesByServer[$i];
			} else { // server recently added to config?
				$host = $this->parent->getServerName( $i );
				$this->replLogger->error( __METHOD__ . ": host $host not in cache" );
			}
		}
	}

	final public function getLagTimes( array $serverIndexes, $domain ) {
		return $this->getServerStates( $serverIndexes, $domain )['lagTimes'];
	}

	protected function getServerStates( array $serverIndexes, $domain ) {
		$writerIndex = $this->parent->getWriterIndex();
		if ( count( $serverIndexes ) == 1 && reset( $serverIndexes ) == $writerIndex ) {
			# Single server only, just return zero without caching
			return [
				'lagTimes' => [ $writerIndex => 0 ],
				'weightScales' => [ $writerIndex => 1.0 ]
			];
		}

		$key = $this->getCacheKey( $serverIndexes );
		# Randomize TTLs to reduce stampedes (4.0 - 5.0 sec)
		$ttl = mt_rand( 4e6, 5e6 ) / 1e6;
		# Keep keys around longer as fallbacks
		$staleTTL = 60;

		# (a) Check the local APC cache
		$value = $this->srvCache->get( $key );
		if ( $value && $value['timestamp'] > ( microtime( true ) - $ttl ) ) {
			$this->replLogger->debug( __METHOD__ . ": got lag times ($key) from local cache" );
			return $value; // cache hit
		}
		$staleValue = $value ?: false;

		# (b) Check the shared cache and backfill APC
		$value = $this->wanCache->get( $key );
		if ( $value && $value['timestamp'] > ( microtime( true ) - $ttl ) ) {
			$this->srvCache->set( $key, $value, $staleTTL );
			$this->replLogger->debug( __METHOD__ . ": got lag times ($key) from main cache" );

			return $value; // cache hit
		}
		$staleValue = $value ?: $staleValue;

		# (c) Cache key missing or expired; regenerate and backfill
		if ( $this->srvCache->lock( $key, 0, 10 ) ) {
			# Let only this process update the cache value on this server
			$sCache = $this->srvCache;
			/** @noinspection PhpUnusedLocalVariableInspection */
			$unlocker = new ScopedCallback( function () use ( $sCache, $key ) {
				$sCache->unlock( $key );
			} );
		} elseif ( $staleValue ) {
			# Could not acquire lock but an old cache exists, so use it
			return $staleValue;
		}

		$lagTimes = [];
		$weightScales = [];
		$movAveRatio = $this->movingAveRatio;
		foreach ( $serverIndexes as $i ) {
			if ( $i == $this->parent->getWriterIndex() ) {
				$lagTimes[$i] = 0; // master always has no lag
				$weightScales[$i] = 1.0; // nominal weight
				continue;
			}

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

			$lastWeight = $staleValue['weightScales'][$i] ?? 1.0;
			$coefficient = $this->getWeightScale( $i, $conn ?: null );
			$newWeight = $movAveRatio * $coefficient + ( 1 - $movAveRatio ) * $lastWeight;

			// Scale from 10% to 100% of nominal weight
			$weightScales[$i] = max( $newWeight, 0.10 );

			$host = $this->parent->getServerName( $i );

			if ( !$conn ) {
				$lagTimes[$i] = false;
				$this->replLogger->error(
					__METHOD__ . ": host {db_server} is unreachable",
					[ 'db_server' => $host ]
				);
				continue;
			}

			if ( $conn->getLBInfo( 'is static' ) ) {
				$lagTimes[$i] = 0;
			} else {
				$lagTimes[$i] = $conn->getLag();
				if ( $lagTimes[$i] === false ) {
					$this->replLogger->error(
						__METHOD__ . ": host {db_server} is not replicating?",
						[ 'db_server' => $host ]
					);
				} elseif ( $lagTimes[$i] > $this->lagWarnThreshold ) {
					$this->replLogger->warning(
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

		# Add a timestamp key so we know when it was cached
		$value = [
			'lagTimes' => $lagTimes,
			'weightScales' => $weightScales,
			'timestamp' => microtime( true )
		];
		$this->wanCache->set( $key, $value, $staleTTL );
		$this->srvCache->set( $key, $value, $staleTTL );
		$this->replLogger->info( __METHOD__ . ": re-calculated lag times ($key)" );

		return $value;
	}

	/**
	 * @param int $index Server index
	 * @param IDatabase|null $conn Connection handle or null on connection failure
	 * @return float
	 */
	protected function getWeightScale( $index, IDatabase $conn = null ) {
		return $conn ? 1.0 : 0.0;
	}

	private function getCacheKey( array $serverIndexes ) {
		sort( $serverIndexes );
		// Lag is per-server, not per-DB, so key on the master DB name
		return $this->srvCache->makeGlobalKey(
			'lag-times',
			self::VERSION,
			$this->parent->getServerName( $this->parent->getWriterIndex() ),
			implode( '-', $serverIndexes )
		);
	}
}
