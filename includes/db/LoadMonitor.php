<?php
/**
 * Database load monitoring.
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

/**
 * An interface for database load monitoring
 *
 * @ingroup Database
 */
interface LoadMonitor {
	/**
	 * Construct a new LoadMonitor with a given LoadBalancer parent
	 *
	 * @param LoadBalancer $parent
	 */
	public function __construct( $parent );

	/**
	 * Perform pre-connection load ratio adjustment.
	 * @param array $loads
	 * @param string|bool $group The selected query group. Default: false
	 * @param string|bool $wiki Default: false
	 */
	public function scaleLoads( &$loads, $group = false, $wiki = false );

	/**
	 * Return an estimate of replication lag for each server
	 *
	 * @param array $serverIndexes
	 * @param string $wiki
	 *
	 * @return array Map of (server index => seconds)
	 */
	public function getLagTimes( $serverIndexes, $wiki );
}

class LoadMonitorNull implements LoadMonitor {
	public function __construct( $parent ) {
	}

	public function scaleLoads( &$loads, $group = false, $wiki = false ) {
	}

	public function getLagTimes( $serverIndexes, $wiki ) {
		return array_fill_keys( $serverIndexes, 0 );
	}
}

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
	protected $cache;

	public function __construct( $parent ) {
		global $wgMemc;

		$this->parent = $parent;
		$this->cache = $wgMemc ?: wfGetMainCache();
	}

	public function scaleLoads( &$loads, $group = false, $wiki = false ) {
	}

	public function getLagTimes( $serverIndexes, $wiki ) {
		if ( count( $serverIndexes ) == 1 && reset( $serverIndexes ) == 0 ) {
			// Single server only, just return zero without caching
			return array( 0 => 0 );
		}

		$expiry = 5;
		$requestRate = 10;

		$cache = $this->cache;
		$masterName = $this->parent->getServerName( 0 );
		$memcKey = wfMemcKey( 'lag_times', $masterName );
		$times = $cache->get( $memcKey );
		if ( is_array( $times ) ) {
			# Randomly recache with probability rising over $expiry
			$elapsed = time() - $times['timestamp'];
			$chance = max( 0, ( $expiry - $elapsed ) * $requestRate );
			if ( mt_rand( 0, $chance ) != 0 ) {
				unset( $times['timestamp'] ); // hide from caller

				return $times;
			}
			wfIncrStats( 'lag_cache_miss_expired' );
		} else {
			wfIncrStats( 'lag_cache_miss_absent' );
		}

		# Cache key missing or expired
		if ( $cache->add( "$memcKey:lock", 1, 10 ) ) {
			# Let this process alone update the cache value
			$unlocker = new ScopedCallback( function () use ( $cache, $memcKey ) {
				$cache->delete( $memcKey );
			} );
		} elseif ( is_array( $times ) ) {
			# Could not acquire lock but an old cache exists, so use it
			unset( $times['timestamp'] ); // hide from caller

			return $times;
		}

		$times = array();
		foreach ( $serverIndexes as $i ) {
			if ( $i == 0 ) { # Master
				$times[$i] = 0;
			} elseif ( false !== ( $conn = $this->parent->getAnyOpenConnection( $i ) ) ) {
				$times[$i] = $conn->getLag();
			} elseif ( false !== ( $conn = $this->parent->openConnection( $i, $wiki ) ) ) {
				$times[$i] = $conn->getLag();
				// Close the connection to avoid sleeper connections piling up.
				// Note that the caller will pick one of these DBs and reconnect,
				// which is slightly inefficient, but this only matters for the lag
				// time cache miss cache, which is far less common that cache hits.
				$this->parent->closeConnection( $conn );
			}
		}

		# Add a timestamp key so we know when it was cached
		$times['timestamp'] = time();
		$cache->set( $memcKey, $times, $expiry + 10 );
		unset( $times['timestamp'] ); // hide from caller

		return $times;
	}
}
