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
 * Bloom filter server implemented using Redis
 *
 * The Redis server must be >= 2.6 and should have volatile-lru or volatile-ttl
 * if there is any eviction policy. It should not be allkeys-* in any case.
 *
 * Some bits are based on https://github.com/ErikDubbelboer/redis-lua-scaling-bloom-filter
 * but are simplified to use a single filter instead of up to 32 filters.
 *
 * @since 1.25
 */
class BloomCacheServerRedis extends BloomCacheServer {
	/** @var RedisConnRef */
	protected $conn;
	/** @var RedisConnectionPool */
	protected $redisPool;

	/** @var integer Federate each filter into this many redis bitfield objects */
	protected $segments = 128;

	const LOCK_TTL = 10; // seconds before locks expire

	/**
	 * @params include:
	 *   - conn : RedisConnRef object
	 *   - pool : RedisConnectionPool object
	 *
	 * @param array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->conn = $config['conn'];
		$this->redisPool = $config['pool'];
	}

	public function init( $key, $size, $precision ) {
		$key = "{$this->cacheID}:$key";

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

		$ok = false;
		try {
			$this->conn->script( 'load', $script );
			$this->conn->multi( Redis::MULTI );
			for ( $i = 0; $i < $this->segments; ++$i ) {
				$this->conn->luaEval( $script,
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
			$results = $this->conn->exec();
			$ok = $results && !in_array( false, $results, true );
		} catch ( RedisException $e ) {
			$this->redisPool->handleError( $this->conn, $e );
		}

		return $ok;
	}

	public function add( $key, array $members ) {
		$key = "{$this->cacheID}:$key";

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

		$ok = false;
		try {
			$this->conn->script( 'load', $script );
			$this->conn->multi( Redis::PIPELINE );
			foreach ( $members as $member ) {
				$i = $this->getSegment( $member );
				$this->conn->luaEval( $script,
					array(
						"$key:$i:bloom-metadata", # KEYS[1],
						"$key:$i:bloom-data", # KEYS[2]
						$member # ARGV[1]
					),
					2 # number of first argument(s) that are keys
				);
			}
			$results = $this->conn->exec();
			$ok = $results && !in_array( false, $results, true );
		} catch ( RedisException $e ) {
			$this->redisPool->handleError( $this->conn, $e );
		}

		if ( !$ok ) {
			wfDebug( "Could not add to the '$key' bloom filter; it may be missing." );
		}

		return $ok;
	}

	public function setStatus( $virtualKey, array $values ) {
		$virtualKey = "{$this->cacheID}:$virtualKey";

		try {
			$res = $this->conn->hMSet( "$virtualKey:filter-metadata", $values );
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleError( $this->conn, $e );
		}

		return ( $res !== false );
	}

	public function getStatus( $virtualKey ) {
		$virtualKey = "{$this->cacheID}:$virtualKey";

		try {
			$res = $this->conn->hGetAll( "$virtualKey:filter-metadata" );
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleError( $this->conn, $e );
		}

		if ( is_array( $res ) ) {
			$res['lastID'] = isset( $res['lastID'] ) ? $res['lastID'] : null;
			$res['asOfTime'] = isset( $res['asOfTime'] ) ? $res['asOfTime'] : null;
			$res['epoch'] = isset( $res['epoch'] ) ? $res['epoch'] : null;
		}

		return $res;
	}

	public function isHit( $key, $member ) {
		$key = "{$this->cacheID}:$key";

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

		try {
			$i = $this->getSegment( $member );
			$res = $this->conn->luaEval( $script,
				array(
					"$key:$i:bloom-metadata", # KEYS[1],
					"$key:$i:bloom-data", # KEYS[2]
					$member # ARGV[1]
				),
				2 # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$this->redisPool->handleError( $this->conn, $e );
			$res = null;
		}

		return is_int( $res ) ? (bool)$res : null;
	}

	public function delete( $key ) {
		$key = "{$this->cacheID}:$key";

		try {
			$keys = array();
			for ( $i = 0; $i < $this->segments; ++$i ) {
				$keys[] = "$key:$i:bloom-metadata";
				$keys[] = "$key:$i:bloom-data";
			}
			$res = $this->conn->delete( $keys );
		} catch ( RedisException $e ) {
			$this->redisPool->handleError( $this->conn, $e );
			$res = false;
		}

		return ( $res !== false );
	}

	public function getScopedLock( $virtualKey ) {
		$lockKey = "{$this->cacheID}:$virtualKey:lock";

		static $script =
<<<LUA
		local kLock = unpack(KEYS)
		local aTTL = unpack(ARGV)
		if redis.call('EXISTS',kLock) == 0 then
			redis.call('SETEX',aTTL,kLock)
			return 1
		end
		return 0
LUA;

		$res = $this->conn->luaEval( $script,
			array(
				$lockKey, # KEYS[1]
				self::LOCK_TTL # ARGV[1]
			),
			1 # number of first argument(s) that are keys
		);

		if ( !$res ) {
			return null;
		}

		$conn = $this->conn;
		return new ScopedCallback( function() use ( $conn, $lockKey ) {
			$conn->delete( $lockKey );
		} );
	}

	/**
	 * @param string $member
	 * @return integer
	 */
	protected function getSegment( $member ) {
		return hexdec( substr( md5( $member ), 0, 2 ) ) % $this->segments;
	}
}
