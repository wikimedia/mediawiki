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
 * @author Aaron Schulz
 */

/**
 * Bloom filter implemented using Redis
 *
 * The Redis server must be >= 2.6 and should have volatile-lru or volatile-ttl
 * if there is any eviction policy. It should not be allkeys-* in any case.
 *
 * @since 1.24
 */
class BloomCacheRedis extends BloomCache {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var array */
	protected $servers;
	/** @var BloomCacheServerRedis|null */
	protected $bloomConn;

	/**
	 * @params include:
	 *   - redisServers : list of servers (address:<port>)
	 *   - redisConf    : additional redis configuration
	 *
	 * @param array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$redisConf = $config['redisConfig'];
		$redisConf['serializer'] = 'none'; // manage that in this class
		$this->redisPool = RedisConnectionPool::singleton( $redisConf );
		$this->servers = $config['redisServers'];
	}

	public function getServers() {
		return $this->servers;
	}

	public function getConnection( $server = null ) {
		if ( $this->bloomConn && !$server ) {
			return $this->bloomConn;
		} else {
			$conn = $this->getRedisConn( $server );

			$replica = $conn
				? new BloomCacheServerRedis( array(
					'conn'    => $conn,
					'pool'    => $this->redisPool,
					'cacheID' => $this->cacheID
				) )
				: null;

			if ( !$server || !$this->bloomConn ) {
				$this->bloomConn = $replica;
			}
		}

		return $this->bloomConn;
	}

	/**
	 * @param string|null $server
	 * @return RedisConnRef|bool Returns false on failure
	 */
	protected function getRedisConn( $server = null ) {
		if ( $server ) {
			$conn = $this->redisPool->getConnection( $server );
		} else {
			$conn = false;

			$servers = $this->servers;
			$attempts = min( 3, count( $servers ) );
			for ( $i = 1; $i <= $attempts; ++$i ) {
				$index = mt_rand( 0, count( $servers ) - 1 );
				$conn = $this->redisPool->getConnection( $servers[$index] );
				if ( $conn ) {
					return $conn;
				}
				unset( $servers[$index] ); // skip next time
				$servers = array_values( $servers ); // reindex
			}
		}

		return $conn;
	}
}
