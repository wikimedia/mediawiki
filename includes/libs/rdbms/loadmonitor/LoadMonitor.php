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
	/** @var BagOStuff */
	protected $mainCache;
	/** @var LoggerInterface */
	protected $replLogger;

	/** @var float Moving average ratio (e.g. 0.1 for 10% weight to new weight) */
	private $movingAveRatio;

	const VERSION = 1; // cache key version

	public function __construct(
		ILoadBalancer $lb, BagOStuff $srvCache, BagOStuff $cache, array $options = []
	) {
		$this->parent = $lb;
		$this->srvCache = $srvCache;
		$this->mainCache = $cache;
		$this->replLogger = new NullLogger();

		$this->movingAveRatio = isset( $options['movingAveRatio'] )
			? $options['movingAveRatio']
			: 0.1;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->replLogger = $logger;
	}

	public function scaleLoads( array &$weightByServer, $domain ) {
		$serverIndexes = array_keys( $weightByServer );
		$states = $this->getServerStates( $serverIndexes, $domain );
		$coefficientsByServer = $states['weightScales'];
		foreach ( $weightByServer as $i => $weight ) {
			if ( isset( $coefficientsByServer[$i] ) ) {
				$weightByServer[$i] = $weight * $coefficientsByServer[$i];
			} else { // server recently added to config?
				$host = $this->parent->getServerName( $i );
				$this->replLogger->error( __METHOD__ . ": host $host not in cache" );
			}
		}
	}

	public function getLagTimes( array $serverIndexes, $domain ) {
		$states = $this->getServerStates( $serverIndexes, $domain );

		return $states['lagTimes'];
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
		$value = $this->mainCache->get( $key );
		if ( $value && $value['timestamp'] > ( microtime( true ) - $ttl ) ) {
			$this->srvCache->set( $key, $value, $staleTTL );
			$this->replLogger->debug( __METHOD__ . ": got lag times ($key) from main cache" );

			return $value; // cache hit
		}
		$staleValue = $value ?: $staleValue;

		# (c) Cache key missing or expired; regenerate and backfill
		if ( $this->mainCache->lock( $key, 0, 10 ) ) {
			# Let this process alone update the cache value
			$cache = $this->mainCache;
			/** @noinspection PhpUnusedLocalVariableInspection */
			$unlocker = new ScopedCallback( function () use ( $cache, $key ) {
				$cache->unlock( $key );
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

			$conn = $this->parent->getAnyOpenConnection( $i );
			if ( $conn ) {
				$close = false; // already open
			} else {
				$conn = $this->parent->openConnection( $i, $domain );
				$close = true; // new connection
			}

			$lastWeight = isset( $staleValue['weightScales'][$i] )
				? $staleValue['weightScales'][$i]
				: 1.0;
			$coefficient = $this->getWeightScale( $i, $conn ?: null );
			$newWeight = $movAveRatio * $coefficient + ( 1 - $movAveRatio ) * $lastWeight;

			// Scale from 10% to 100% of nominal weight
			$weightScales[$i] = max( $newWeight, .10 );

			if ( !$conn ) {
				$lagTimes[$i] = false;
				$host = $this->parent->getServerName( $i );
				$this->replLogger->error( __METHOD__ . ": host $host is unreachable" );
				continue;
			}

			if ( $conn->getLBInfo( 'is static' ) ) {
				$lagTimes[$i] = 0;
			} else {
				$lagTimes[$i] = $conn->getLag();
				if ( $lagTimes[$i] === false ) {
					$host = $this->parent->getServerName( $i );
					$this->replLogger->error( __METHOD__ . ": host $host is not replicating?" );
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
		$this->mainCache->set( $key, $value, $staleTTL );
		$this->srvCache->set( $key, $value, $staleTTL );
		$this->replLogger->info( __METHOD__ . ": re-calculated lag times ($key)" );

		return $value;
	}

	/**
	 * @param integer $index Server index
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
