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
 * @since 1.21
 */
class RedisLockManager extends QuorumLockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var RedisConnectionPool */
	protected $redisPool = array();
	/** @var Array Map server names to hostname/IP and port numbers */
	protected $lockServers = array();
	/** @var Array */
	protected $serversUp = array(); // (server name => bool)

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

		$this->redisPool = RedisConnectionPool::singleton( $config['redisConfig'] );

		$this->session = UIDGenerator::newRawUUIDv4( UIDGenerator::QUICK_RAND );
	}

	protected function getLocksOnServer( $lockSrv, array $paths, $type ) {
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
			for i,resourceKey in ipairs(KEYS) do
				local acquired = true
				local locks = redis.call('hGetAll',resourceKey)
				for lockKey,lockTimestamp in ipairs(locks) do
					typeAndSession = split(lockKey,':',2)
					-- Ignore any locks owned by this session
					if typeAndSession[2] ~= ARGV[2] then
						if lockTimestamp < ( ARGV[4] - ARGV[3] ) then
							-- Lock is stale, so just prune it out
							redis.call('hDel',resourceKey,lockKey)
						elseif ARGV[1] == 'EX' or typeAndSession[1] == 'EX' then
							acquired = false
						end
					end
				end
				if acquired then
					redis.call('hSet',resourceKey,ARGV[1] .. ':' .. ARGV[2],ARGV[4])
					-- In addition to invalidation logic, be sure to garbage collect
					redis.call('expire',resourceKey,ARGV[3])
				else
					failed[#failed] = resourceKey
				end
			end
			return failed
LUA;
			$res = $this->redisEval( $conn, $script,
				array_merge(
					$keys, // KEYS[0], KEYS[1],...KEYS[N]
					$type === self::LOCK_SH ? 'SH' : 'EX', // ARGV[1]
					$this->session, // ARGV[2]
					$this->lockTTL, // ARGV[3]
					time() // ARGV[4]
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

	protected function freeLocksOnServer( $lockSrv, array $paths, $type ) {
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
				local released = false
				local locks = redis.call('hGetAll',resourceKey)
				for lockKey,lockTimestamp in ipairs(locks) do
					typeAndSession = split(lockKey,':',2)
					if typeAndSession[1] == ARGV[1] and typeAndSession[2] == ARGV[2] then
						released = true
					end
				end
				if released then
					redis.call('hDel',resourceKey,ARGV[1] .. ':' .. ARGV[2])
				else
					failed[#failed] = resourceKey
				end
			end
			return failed
LUA;
			$res = $this->redisEval( $conn, $script,
				array_merge(
					$keys, // KEYS[0], KEYS[1],...KEYS[N]
					$type === self::LOCK_SH ? 'SH' : 'EX', // ARGV[1]
					$this->session // ARGV[2]
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
	 * @param RedisConnRef $conn
	 * @param string $script
	 * @param array $params
	 * @param integer $numKeys
	 * @return mixed
	 */
	protected function redisEval( RedisConnRef $conn, $script, array $params, $numKeys ) {
		$sha1 = sha1( $script ); // 40 char hex

		// Try to run the server-side cached copy of the script
		$conn->clearLastError();
		$res = $conn->evalSha( $sha1, $params, $numKeys );
		// If the script is not in cache, use eval() to retry and cache it
		if ( $conn->getLastError() && $conn->script( 'exists', $sha1 ) === array( 0 ) ) {
			$conn->clearLastError();
			$res = $conn->eval( $script, $params, $numKeys );
			wfDebugLog( 'RedisLockManager', "Used eval() for Lua script $sha1." );
		}

		if ( $conn->getLastError() ) { // script bug?
			wfDebugLog( 'RedisLockManager', "Lua script error: " . $conn->getLastError() );
		}

		return $res;
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
