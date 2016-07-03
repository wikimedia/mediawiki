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

/**
 * Basic MySQL load monitor with no external dependencies
 * Uses memcached to cache the replication lag for a short time
 *
 * @ingroup Database
 */
class LoadMonitorMySQL implements LoadMonitor {
	/** @var LoadBalancer */
	public $parent;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var BagOStuff */
	protected $mainCache;

	public function __construct( $parent ) {
		$this->parent = $parent;

		$this->srvCache = ObjectCache::getLocalServerInstance( 'hash' );
		$this->mainCache = ObjectCache::getLocalClusterInstance();
	}

	public function scaleLoads( &$loads, $group = false, $wiki = false ) {
	}

	public function getLagTimes( $serverIndexes, $wiki ) {
		if ( count( $serverIndexes ) == 1 && reset( $serverIndexes ) == 0 ) {
			# Single server only, just return zero without caching
			return [ 0 => 0 ];
		}

		$key = $this->getLagTimeCacheKey();
		# Randomize TTLs to reduce stampedes (4.0 - 5.0 sec)
		$ttl = mt_rand( 4e6, 5e6 ) / 1e6;
		# Keep keys around longer as fallbacks
		$staleTTL = 60;

		# (a) Check the local APC cache
		$value = $this->srvCache->get( $key );
		if ( $value && $value['timestamp'] > ( microtime( true ) - $ttl ) ) {
			wfDebugLog( 'replication', __METHOD__ . ": got lag times ($key) from local cache" );
			return $value['lagTimes']; // cache hit
		}
		$staleValue = $value ?: false;

		# (b) Check the shared cache and backfill APC
		$value = $this->mainCache->get( $key );
		if ( $value && $value['timestamp'] > ( microtime( true ) - $ttl ) ) {
			$this->srvCache->set( $key, $value, $staleTTL );
			wfDebugLog( 'replication', __METHOD__ . ": got lag times ($key) from main cache" );

			return $value['lagTimes']; // cache hit
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
			return $staleValue['lagTimes'];
		}

		$lagTimes = [];
		foreach ( $serverIndexes as $i ) {
			if ( $i == $this->parent->getWriterIndex() ) {
				$lagTimes[$i] = 0; // master always has no lag
				continue;
			}

			$conn = $this->parent->getAnyOpenConnection( $i );
			if ( $conn ) {
				$close = false; // already open
			} else {
				$conn = $this->parent->openConnection( $i, $wiki );
				$close = true; // new connection
			}

			if ( !$conn ) {
				$lagTimes[$i] = false;
				$host = $this->parent->getServerName( $i );
				wfDebugLog( 'replication', __METHOD__ . ": host $host (#$i) is unreachable" );
				continue;
			}

			$lagTimes[$i] = $conn->getLag();
			if ( $lagTimes[$i] === false ) {
				$host = $this->parent->getServerName( $i );
				wfDebugLog( 'replication', __METHOD__ . ": host $host (#$i) is not replicating?" );
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
		$value = [ 'lagTimes' => $lagTimes, 'timestamp' => microtime( true ) ];
		$this->mainCache->set( $key, $value, $staleTTL );
		$this->srvCache->set( $key, $value, $staleTTL );
		wfDebugLog( 'replication', __METHOD__ . ": re-calculated lag times ($key)" );

		return $value['lagTimes'];
	}

	public function clearCaches() {
		$key = $this->getLagTimeCacheKey();
		$this->srvCache->delete( $key );
		$this->mainCache->delete( $key );
	}

	private function getLagTimeCacheKey() {
		$writerIndex = $this->parent->getWriterIndex();
		// Lag is per-server, not per-DB, so key on the master DB name
		return $this->srvCache->makeGlobalKey(
			'lag-times', $this->parent->getServerName( $writerIndex )
		);
	}
}
