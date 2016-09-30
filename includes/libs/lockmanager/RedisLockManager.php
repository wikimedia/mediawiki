<?php
/**
 * Version of LockManager based on using redis servers.
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
 * @ingroup LockManager
 */

/**
 * Manage locks using redis servers.
 *
 * Version of LockManager based on using redis servers.
 * This is meant for multi-wiki systems that may share files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * All lock requests for a resource, identified by a hash string, will map to one
 * bucket. Each bucket maps to one or several peer servers, each running redis.
 * A majority of peers must agree for a lock to be acquired.
 *
 * This class requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 *
 * @ingroup LockManager
 * @since 1.22
 */
class RedisLockManager extends QuorumLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	/** @var RedisConnectionPool */
	protected $redisPool;

	/** @var array Map server names to hostname/IP and port numbers */
	protected $lockServers = [];

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Parameters include:
	 *   - lockServers  : Associative array of server names to "<IP>:<port>" strings.
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - redisConfig  : Configuration for RedisConnectionPool::__construct().
	 * @throws Exception
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->lockServers = $config['lockServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$config['redisConfig']['serializer'] = 'none';
		$this->redisPool = RedisConnectionPool::singleton( $config['redisConfig'] );
	}

	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = StatusValue::newGood();

		$pathList = call_user_func_array( 'array_merge', array_values( $pathsByType ) );

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server, $this->logger );
		if ( !$conn ) {
			foreach ( $pathList as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}

			return $status;
		}

		$pathsByKey = []; // (type:hash => path) map
		foreach ( $pathsByType as $type => $paths ) {
			$typeString = ( $type == LockManager::LOCK_SH ) ? 'SH' : 'EX';
			foreach ( $paths as $path ) {
				$pathsByKey[$this->recordKeyForPath( $path, $typeString )] = $path;
			}
		}

		try {
			static $script =
			/** @lang Lua */
<<<LUA
			local failed = {}
			-- Load input params (e.g. session, ttl, time of request)
			local rSession, rTTL, rMaxTTL, rTime = unpack(ARGV)
			-- Check that all the locks can be acquired
			for i,requestKey in ipairs(KEYS) do
				local _, _, rType, resourceKey = string.find(requestKey,"(%w+):(%w+)$")
				local keyIsFree = true
				local currentLocks = redis.call('hKeys',resourceKey)
				for i,lockKey in ipairs(currentLocks) do
					-- Get the type and session of this lock
					local _, _, type, session = string.find(lockKey,"(%w+):(%w+)")
					-- Check any locks that are not owned by this session
					if session ~= rSession then
						local lockExpiry = redis.call('hGet',resourceKey,lockKey)
						if 1*lockExpiry < 1*rTime then
							-- Lock is stale, so just prune it out
							redis.call('hDel',resourceKey,lockKey)
						elseif rType == 'EX' or type == 'EX' then
							keyIsFree = false
							break
						end
					end
				end
				if not keyIsFree then
					failed[#failed+1] = requestKey
				end
			end
			-- If all locks could be acquired, then do so
			if #failed == 0 then
				for i,requestKey in ipairs(KEYS) do
					local _, _, rType, resourceKey = string.find(requestKey,"(%w+):(%w+)$")
					redis.call('hSet',resourceKey,rType .. ':' .. rSession,rTime + rTTL)
					-- In addition to invalidation logic, be sure to garbage collect
					redis.call('expire',resourceKey,rMaxTTL)
				end
			end
			return failed
LUA;
			$res = $conn->luaEval( $script,
				array_merge(
					array_keys( $pathsByKey ), // KEYS[0], KEYS[1],...,KEYS[N]
					[
						$this->session, // ARGV[1]
						$this->lockTTL, // ARGV[2]
						self::MAX_LOCK_TTL, // ARGV[3]
						time() // ARGV[4]
					]
				),
				count( $pathsByKey ) # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleError( $conn, $e );
		}

		if ( $res === false ) {
			foreach ( $pathList as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}
		} else {
			foreach ( $res as $key ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $pathsByKey[$key] );
			}
		}

		return $status;
	}

	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = StatusValue::newGood();

		$pathList = call_user_func_array( 'array_merge', array_values( $pathsByType ) );

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server, $this->logger );
		if ( !$conn ) {
			foreach ( $pathList as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}

			return $status;
		}

		$pathsByKey = []; // (type:hash => path) map
		foreach ( $pathsByType as $type => $paths ) {
			$typeString = ( $type == LockManager::LOCK_SH ) ? 'SH' : 'EX';
			foreach ( $paths as $path ) {
				$pathsByKey[$this->recordKeyForPath( $path, $typeString )] = $path;
			}
		}

		try {
			static $script =
			/** @lang Lua */
<<<LUA
			local failed = {}
			-- Load input params (e.g. session)
			local rSession = unpack(ARGV)
			for i,requestKey in ipairs(KEYS) do
				local _, _, rType, resourceKey = string.find(requestKey,"(%w+):(%w+)$")
				local released = redis.call('hDel',resourceKey,rType .. ':' .. rSession)
				if released > 0 then
					-- Remove the whole structure if it is now empty
					if redis.call('hLen',resourceKey) == 0 then
						redis.call('del',resourceKey)
					end
				else
					failed[#failed+1] = requestKey
				end
			end
			return failed
LUA;
			$res = $conn->luaEval( $script,
				array_merge(
					array_keys( $pathsByKey ), // KEYS[0], KEYS[1],...,KEYS[N]
					[
						$this->session, // ARGV[1]
					]
				),
				count( $pathsByKey ) # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleError( $conn, $e );
		}

		if ( $res === false ) {
			foreach ( $pathList as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
		} else {
			foreach ( $res as $key ) {
				$status->fatal( 'lockmanager-fail-releaselock', $pathsByKey[$key] );
			}
		}

		return $status;
	}

	protected function releaseAllLocks() {
		return StatusValue::newGood(); // not supported
	}

	protected function isServerUp( $lockSrv ) {
		$conn = $this->redisPool->getConnection( $this->lockServers[$lockSrv], $this->logger );

		return (bool)$conn;
	}

	/**
	 * @param string $path
	 * @param string $type One of (EX,SH)
	 * @return string
	 */
	protected function recordKeyForPath( $path, $type ) {
		return implode( ':',
			[ __CLASS__, 'locks', "$type:" . $this->sha1Base36Absolute( $path ) ] );
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		while ( count( $this->locksHeld ) ) {
			$pathsByType = [];
			foreach ( $this->locksHeld as $path => $locks ) {
				foreach ( $locks as $type => $count ) {
					$pathsByType[$type][] = $path;
				}
			}
			$this->unlockByType( $pathsByType );
		}
	}
}
