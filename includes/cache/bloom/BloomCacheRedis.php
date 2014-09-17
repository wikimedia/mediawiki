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
 * if there is any eviction policy. It should not be allkeys-* in any case. Also,
 * this can be used in a simple master/slave setup or with Redis Sentinel preferably.
 *
 * Some bits are based on https://github.com/ErikDubbelboer/redis-lua-scaling-bloom-filter
 * but are simplified to use a single filter instead of up to 32 filters.
 *
 * @since 1.24
 */
class BloomCacheRedis extends BloomCache {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var RedisLockManager */
	protected $lockMgr;
	/** @var array */
	protected $servers;
	/** @var integer Federate each filter into this many redis bitfield objects */
	protected $segments = 128;

	/**
	 * @params include:
	 *   - redisServers : list of servers (address:<port>) (the first is the master)
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
		$this->lockMgr = new RedisLockManager( array(
			'lockServers'  => array( 'srv1' => $this->servers[0] ),
			'srvsByBucket' => array( 0 => array( 'srv1' ) ),
			'redisConfig'  => $config['redisConfig']
		) );
	}

	protected function doInit( $key, $size, $precision ) {
		$conn = $this->getConnection( 'master' );
		if ( !$conn ) {
			return false;
		}

		// 80000000 items at p = .001 take up 500MB and fit into one value.
		// Do not hit the 512MB redis value limit by reducing the demands.
		$size = min( $size, 80000000 * $this->segments );
		$precision = max( round( $precision, 3 ), .001 );
		$epoch = microtime( true );

		static $script =
<<<LUA
		local kMetadata, kData = unpack(KEYS)
		local aEntries, aPrec, aEpoch = unpack(ARGV)
		if redis.call('EXISTS',kMetadata) == 0 or redis.call('EXISTS',kData) == 0 then
			redis.call('DEL',kMetadata)
			redis.call('HSET',kMetadata,'entries',aEntries)
			redis.call('HSET',kMetadata,'precision',aPrec)
			redis.call('HSET',kMetadata,'epoch',aEpoch)
			redis.call('SET',kData,'')
			return 1
		end
		return 0
LUA;

		$res = false;
		try {
			$conn->script( 'load', $script );
			$conn->multi( Redis::MULTI );
			for ( $i = 0; $i < $this->segments; ++$i ) {
				$res = $conn->luaEval( $script,
					array(
						"$key:$i:bloom-metadata", # KEYS[1]
						"$key:$i:bloom-data", # KEYS[2]
						ceil( $size / $this->segments ), # ARGV[1]
						$precision, # ARGV[2]
						$epoch # ARGV[3]
					),
					2 # number of first argument(s) that are keys
				);
			}
			$results = $conn->exec();
			$res = $results && !in_array( false, $results, true );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		return ( $res !== false );
	}

	protected function doAdd( $key, array $members ) {
		$conn = $this->getConnection( 'master' );
		if ( !$conn ) {
			return false;
		}

		static $script =
<<<LUA
		local kMetadata, kData = unpack(KEYS)
		local aMember = unpack(ARGV)

		-- Check if the filter was initialized
		if redis.call('EXISTS',kMetadata) == 0 or redis.call('EXISTS',kData) == 0 then
			return false
		end

		-- Initial expected entries and desired precision
		local entries = 1*redis.call('HGET',kMetadata,'entries')
		local precision = 1*redis.call('HGET',kMetadata,'precision')
		local hash = redis.sha1hex(aMember)

		-- Based on the math from: http://en.wikipedia.org/wiki/Bloom_filter#Probability_of_false_positives
		-- 0.480453013 = ln(2)^2
		local bits = math.ceil((entries * math.log(precision)) / -0.480453013)

		-- 0.693147180 = ln(2)
		local k = math.floor(0.693147180 * bits / entries)

		-- This uses a variation on:
		-- 'Less Hashing, Same Performance: Building a Better Bloom Filter'
		-- http://www.eecs.harvard.edu/~kirsch/pubs/bbbf/esa06.pdf
		local h = { }
		h[0] = tonumber(string.sub(hash, 1, 8 ), 16)
		h[1] = tonumber(string.sub(hash, 9, 16), 16)
		h[2] = tonumber(string.sub(hash, 17, 24), 16)
		h[3] = tonumber(string.sub(hash, 25, 32), 16)

		for i=1, k do
			local pos = (h[i % 2] + i * h[2 + (((i + (i % 2)) % 4) / 2)]) % bits
			redis.call('SETBIT', kData, pos, 1)
		end

		return 1
LUA;

		$res = false;
		try {
			$conn->script( 'load', $script );
			$conn->multi( Redis::PIPELINE );
			foreach ( $members as $member ) {
				$i = $this->getSegment( $member );
				$conn->luaEval( $script,
					array(
						"$key:$i:bloom-metadata", # KEYS[1],
						"$key:$i:bloom-data", # KEYS[2]
						$member # ARGV[1]
					),
					2 # number of first argument(s) that are keys
				);
			}
			$results = $conn->exec();
			$res = $results && !in_array( false, $results, true );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		if ( $res === false ) {
			wfDebug( "Could not add to the '$key' bloom filter; it may be missing." );
		}

		return ( $res !== false );
	}

	protected function doSetStatus( $virtualKey, array $values ) {
		$conn = $this->getConnection( 'master' );
		if ( !$conn ) {
			return null;
		}

		$res = false;
		try {
			$res = $conn->hMSet( "$virtualKey:filter-metadata", $values );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		return ( $res !== false );
	}

	protected function doGetStatus( $virtualKey ) {
		$conn = $this->getConnection( 'slave' );
		if ( !$conn ) {
			return false;
		}

		$res = false;
		try {
			$res = $conn->hGetAll( "$virtualKey:filter-metadata" );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		if ( is_array( $res ) ) {
			$res['lastID'] = isset( $res['lastID'] ) ? $res['lastID'] : null;
			$res['asOfTime'] = isset( $res['asOfTime'] ) ? $res['asOfTime'] : null;
			$res['epoch'] = isset( $res['epoch'] ) ? $res['epoch'] : null;
		}

		return $res;
	}

	protected function doIsHit( $key, $member ) {
		$conn = $this->getConnection( 'slave' );
		if ( !$conn ) {
			return null;
		}

		static $script =
<<<LUA
		local kMetadata, kData = unpack(KEYS)
		local aMember = unpack(ARGV)

		-- Check if the filter was initialized
		if redis.call('EXISTS',kMetadata) == 0 or redis.call('EXISTS',kData) == 0 then
			return false
		end

		-- Initial expected entries and desired precision.
		-- This determines the size of the first and subsequent filters.
		local entries = redis.call('HGET',kMetadata,'entries')
		local precision = redis.call('HGET',kMetadata,'precision')
		local hash = redis.sha1hex(aMember)

		-- This uses a variation on:
		-- 'Less Hashing, Same Performance: Building a Better Bloom Filter'
		-- http://www.eecs.harvard.edu/~kirsch/pubs/bbbf/esa06.pdf
		local h = { }
		h[0] = tonumber(string.sub(hash, 1, 8 ), 16)
		h[1] = tonumber(string.sub(hash, 9, 16), 16)
		h[2] = tonumber(string.sub(hash, 17, 24), 16)
		h[3] = tonumber(string.sub(hash, 25, 32), 16)

		-- 0.480453013 = ln(2)^2
		local bits = math.ceil((entries * math.log(precision)) / -0.480453013)

		-- 0.693147180 = ln(2)
		local k = math.floor(0.693147180 * bits / entries)

		local found = 1
		for i=1, k do
			local pos = (h[i % 2] + i * h[2 + (((i + (i % 2)) % 4) / 2)]) % bits
			if redis.call('GETBIT', kData, pos) == 0 then
				found = 0
				break
			end
		end

		return found
LUA;

		$res = null;
		try {
			$i = $this->getSegment( $member );
			$res = $conn->luaEval( $script,
				array(
					"$key:$i:bloom-metadata", # KEYS[1],
					"$key:$i:bloom-data", # KEYS[2]
					$member # ARGV[1]
				),
				2 # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		return is_int( $res ) ? (bool)$res : null;
	}

	protected function doDelete( $key ) {
		$conn = $this->getConnection( 'master' );
		if ( !$conn ) {
			return false;
		}

		$res = false;
		try {
			$keys = array();
			for ( $i = 0; $i < $this->segments; ++$i ) {
				$keys[] = "$key:$i:bloom-metadata";
				$keys[] = "$key:$i:bloom-data";
			}
			$res = $conn->delete( $keys );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
		}

		return ( $res !== false );
	}

	public function getScopedLock( $virtualKey ) {
		$status = Status::newGood();
		return ScopedLock::factory( $this->lockMgr,
			array( $virtualKey ), LockManager::LOCK_EX, $status );
	}

	/**
	 * @param string $member
	 * @return integer
	 */
	protected function getSegment( $member ) {
		return hexdec( substr( md5( $member ), 0, 2 ) ) % $this->segments;
	}

	/**
	 * $param string $to (master/slave)
	 * @return RedisConnRef|bool Returns false on failure
	 */
	protected function getConnection( $to ) {
		if ( $to === 'master' ) {
			$conn = $this->redisPool->getConnection( $this->servers[0] );
		} else {
			static $lastServer = null;

			$conn = false;
			if ( $lastServer ) {
				$conn = $this->redisPool->getConnection( $lastServer );
				if ( $conn ) {
					return $conn; // reuse connection
				}
			}
			$servers = $this->servers;
			$attempts = min( 3, count( $servers ) );
			for ( $i = 1; $i <= $attempts; ++$i ) {
				$index = mt_rand( 0, count( $servers ) - 1 );
				$conn = $this->redisPool->getConnection( $servers[$index] );
				if ( $conn ) {
					$lastServer = $servers[$index];
					return $conn;
				}
				unset( $servers[$index] ); // skip next time
			}
		}

		return $conn;
	}

	/**
	 * @param RedisConnRef $conn
	 * @param Exception $e
	 */
	protected function handleException( RedisConnRef $conn, $e ) {
		$this->redisPool->handleError( $conn, $e );
	}
}
