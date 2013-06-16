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
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var Array Map server names to hostname/IP and port numbers */
	protected $lockServers = array();

	protected $session = ''; // string; random UUID

	/**
	 * Construct a new instance from configuration.
	 *
	 * $config paramaters include:
	 *   - lockServers  : Associative array of server names to "<IP>:<port>" strings.
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - redisConfig  : Configuration for RedisConnectionPool::__construct().
	 *
	 * @param Array $config
	 * @throws MWException
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->lockServers = $config['lockServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$config['redisConfig']['serializer'] = 'none';
		$this->redisPool = RedisConnectionPool::singleton( $config['redisConfig'] );

		$this->session = wfRandomString( 32 );
	}

	// @TODO: change this code to work in one batch
	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = Status::newGood();

		$lockedPaths = array();
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doGetLocksOnServer( $lockSrv, $paths, $type ) );
			if ( $status->isOK() ) {
				$lockedPaths[$type] = isset( $lockedPaths[$type] )
					? array_merge( $lockedPaths[$type], $paths )
					: $paths;
			} else {
				foreach ( $lockedPaths as $type => $paths ) {
					$status->merge( $this->doFreeLocksOnServer( $lockSrv, $paths, $type ) );
				}
				break;
			}
		}

		return $status;
	}

	// @TODO: change this code to work in one batch
	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = Status::newGood();

		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doFreeLocksOnServer( $lockSrv, $paths, $type ) );
		}

		return $status;
	}

	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server );
		if ( !$conn ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}
			return $status;
		}

		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records

		try {
			static $script =
<<<LUA
			if ARGV[1] ~= 'EX' and ARGV[1] ~= 'SH' then
				return redis.error_reply('Unrecognized lock type given (must be EX or SH)')
			end
			local failed = {}
			-- Check that all the locks can be acquired
			for i,resourceKey in ipairs(KEYS) do
				local keyIsFree = true
				local currentLocks = redis.call('hKeys',resourceKey)
				for i,lockKey in ipairs(currentLocks) do
					local _, _, type, session = string.find(lockKey,"(%w+):(%w+)")
					-- Check any locks that are not owned by this session
					if session ~= ARGV[2] then
						local lockTimestamp = redis.call('hGet',resourceKey,lockKey)
						if 1*lockTimestamp < ( ARGV[4] - ARGV[3] ) then
							-- Lock is stale, so just prune it out
							redis.call('hDel',resourceKey,lockKey)
						elseif ARGV[1] == 'EX' or type == 'EX' then
							keyIsFree = false
							break
						end
					end
				end
				if not keyIsFree then
					failed[#failed+1] = resourceKey
				end
			end
			-- If all locks could be acquired, then do so
			if #failed == 0 then
				for i,resourceKey in ipairs(KEYS) do
					redis.call('hSet',resourceKey,ARGV[1] .. ':' .. ARGV[2],ARGV[4])
					-- In addition to invalidation logic, be sure to garbage collect
					redis.call('expire',resourceKey,ARGV[3])
				end
			end
			return failed
LUA;
			$res = $conn->luaEval( $script,
				array_merge(
					$keys, // KEYS[0], KEYS[1],...KEYS[N]
					array(
						$type === self::LOCK_SH ? 'SH' : 'EX', // ARGV[1]
						$this->session, // ARGV[2]
						$this->lockTTL, // ARGV[3]
						time() // ARGV[4]
					)
				),
				count( $keys ) # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleException( $server, $conn, $e );
		}

		if ( $res === false ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}
		} else {
			$pathsByKey = array_combine( $keys, $paths );
			foreach ( $res as $key ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $pathsByKey[$key] );
			}
		}

		return $status;
	}

	protected function doFreeLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server );
		if ( !$conn ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
			return $status;
		}

		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records

		try {
			static $script =
<<<LUA
			if ARGV[1] ~= 'EX' and ARGV[1] ~= 'SH' then
				return redis.error_reply('Unrecognized lock type given (must be EX or SH)')
			end
			local failed = {}
			for i,resourceKey in ipairs(KEYS) do
				local released = redis.call('hDel',resourceKey,ARGV[1] .. ':' .. ARGV[2])
				if released > 0 then
					-- Remove the whole structure if it is now empty
					if redis.call('hLen',resourceKey) == 0 then
						redis.call('del',resourceKey)
					end
				else
					failed[#failed+1] = resourceKey
				end
			end
			return failed
LUA;
			$res = $conn->luaEval( $script,
				array_merge(
					$keys, // KEYS[0], KEYS[1],...KEYS[N]
					array(
						$type === self::LOCK_SH ? 'SH' : 'EX', // ARGV[1]
						$this->session // ARGV[2]
					)
				),
				count( $keys ) # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			$res = false;
			$this->redisPool->handleException( $server, $conn, $e );
		}

		if ( $res === false ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
		} else {
			$pathsByKey = array_combine( $keys, $paths );
			foreach ( $res as $key ) {
				$status->fatal( 'lockmanager-fail-releaselock', $pathsByKey[$key] );
			}
		}

		return $status;
	}

	protected function releaseAllLocks() {
		return Status::newGood(); // not supported
	}

	protected function isServerUp( $lockSrv ) {
		return (bool)$this->redisPool->getConnection( $this->lockServers[$lockSrv] );
	}

	/**
	 * @param $path string
	 * @return string
	 */
	protected function recordKeyForPath( $path ) {
		return implode( ':', array( __CLASS__, 'locks', $this->sha1Base36Absolute( $path ) ) );
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		while ( count( $this->locksHeld ) ) {
			foreach ( $this->locksHeld as $path => $locks ) {
				$this->doUnlock( array( $path ), self::LOCK_EX );
				$this->doUnlock( array( $path ), self::LOCK_SH );
			}
		}
	}
}
