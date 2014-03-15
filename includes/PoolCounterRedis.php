<?php
/**
 * Provides of semaphore semantics for restricting the number
 * of workers that may be concurrently performing the same task.
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
 * @author Aaron Schulz
 */

/**
 * Version of PoolCounter that uses Redis
 *
 * There are four main redis keys used to track each pool counter key:
 *   - poolcounter:l-slots-*    : A list of available slot IDs for a pool
 *   - poolcounter:z-lastfree-* : A sorted set of (slot ID, UNIX timestamp as score)
 *                                used for tracking the last time a slot was available
 *   - poolcounter:z-wait-*     : A sorted set of (slot ID, UNIX timestamp as score)
 *                                used for tracking waiting processes (and wait time)
 *   - poolcounter:l-wakeup-*   : A list pushed to for the sake of waking up processes
 *                                when a any process in the pool finishes (lasts for 1ms)
 * For a given pool key, all the redis keys start off non-existing and are deleted if not
 * used for a while to prevent garbage from building up on the server. They are atomically
 * re-initialized as needed. The "z-lastfree" key is used for detecting processes acquired
 * slots but that disappeared. Stale entries from there have their timestamp updated and the
 * corresponding slots freed up. The "z-wait" key is used for detecting processes registered
 * as waiting but that disappeared. Stale entries from there are deleted and the corresponding
 * slots are freed up.
 *
 * This class requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 * Also this should be on a server plenty of RAM for the working set to avoid evictions.
 * Evictions could temporarily allow wait queues to double in size or temporarily cause
 * pools to appear as full when they are not. Using volatile-ttl and bumping memory-samples
 * ind redis.conf can be helpful otherwise.
 *
 * @ingroup Redis
 * @since 1.23
 */
class PoolCounterRedis extends PoolCounter {
	/** @var HashRing */
	protected $ring;
	/** @var RedisConnectionPool */
	protected $pool;
	/** @var array (server name => host) map */
	protected $servers;

	/** @var RedisConnRef */
	protected $conn;

	/** @var string Pool slot value */
	protected $slot;
	/** @var integer AWAKE_* constant */
	protected $onRelease;
	/** @var integer TTL for locks to expire */
	protected $lockTTL;
	/** @var string Unique string to identify this process */
	protected $session;

	const AWAKE_ONE = 1;
	const AWAKE_ALL = 2;

	function __construct( $conf, $type, $key ) {
		parent::__construct( $conf, $type, $key );

		$this->servers = $conf['servers'];
		$this->ring = new HashRing( array_fill_keys( array_keys( $conf['servers'] ), 100 ) );

		$conf['redisConfig']['serializer'] = 'none'; // for use with Lua
		$this->pool = RedisConnectionPool::singleton( $conf['redisConfig'] );

		$met = ini_get( 'max_execution_time' ); // this is 0 in CLI mode
		$this->lockTTL = max( 5 * 60, 2 * (int)$met );

		$this->session = wfRandomString( 32 );
	}

	/**
	 * @return Status
	 */
	protected function getConnection() {
		if ( !isset( $this->conn ) ) {
			$conn = false;
			$servers = $this->ring->getLocations( $this->key, 3 );
			ArrayUtils::consistentHashSort( $servers, $this->key );
			foreach ( $servers as $server ) {
				$address = $this->servers[$server];
				$conn = $this->pool->getConnection( $address );
				if ( $conn ) {
					break;
				}
			}
			if ( !$conn ) {
				return Status::newFatal( 'pool-errorunknown', $server );
			}
			$this->conn = $conn;
		}
		return Status::newGood( $this->conn );
	}

	function acquireForMe() {
		$section = new ProfileSection( __METHOD__ );

		if ( $this->slot !== null ) {
			return Status::newGood( PoolCounter::LOCK_HELD ); // already acquired
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->waitForSlotOrNotif( $status->value, false );
	}

	function acquireForAnyone() {
		$section = new ProfileSection( __METHOD__ );

		if ( $this->slot !== null ) {
			return Status::newGood( PoolCounter::LOCK_HELD ); // already acquired
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->waitForSlotOrNotif( $status->value, true );
	}

	function release() {
		$section = new ProfileSection( __METHOD__ );

		if ( !$this->slot ) {
			return Status::newGood( PoolCounter::NOT_LOCKED ); // not locked
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		static $script =
<<<LUA
		local kSlots,kSlotsLastFree,kWakeup,kWaiting = unpack(KEYS)
		local rMaxWorkers, rExpiry, rSlot, rAwakeAll, rTime = unpack(ARGV)
		-- If rSlot is 'w', then the client was told to wake up but
		-- did not get an actual slot. Only add slots back to the list
		-- if an actual slot was acquired by the client. Treat the list
		-- as expired if the "last free" time sorted-set is missing.
		if rSlot ~= 'w' and redis.call('exists',kSlotsLastFree) == 1 then
			if redis.call('lLen',kSlots) >= (1*rMaxWorkers - 1) then
				-- Clear list to save space; it will re-init as needed.
				redis.call('del',kSlots,kSlotsLastFree)
			elseif redis.call('lLen',kSlots) < 1*rMaxWorkers then
				-- Add slot back to pool and update the "last free" time
				redis.call('rPush',kSlots,rSlot)
				redis.call('zAdd',kSlotsLastFree,rTime,i)
				-- Always keep renewing the expiry on use
				redis.call('expire',kSlots,rExpiry)
				redis.call('expire',kSlotsLastFree,rExpiry)
			end
		end
		-- Update an ephemeral list to wake up other clients  that can
		-- reuse any cached work from this process. Only do this if no
		-- slots are currently free (e.g. clients could be waiting).
		if 1*rAwakeAll == 1 then
			local count = redis.call('zCard',kWaiting)
			for i = 1,count do
				redis.call('rPush',kWakeup,'w')
			end
			redis.call('pexpire',kWakeup,1)
		end
		return 1
LUA;
		try {
			$res = $conn->luaEval( $script,
				array(
					$this->getSlotListKey(),
					$this->getSlotFTimeSetKey(),
					$this->getWakeupListKey(),
					$this->getWaitSetKey(),
					$this->workers,
					$this->lockTTL, // how long a job should take to finish
					$this->slot,
					( $this->onRelease === self::AWAKE_ALL ) ? 1 : 0,
					time()
				),
				4 # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			return Status::newFatal( 'pool-error-unknown', $e->getMessage() );
		}

		$this->slot = null;
		$this->onRelease = null;

		return Status::newGood( PoolCounter::RELEASED );
	}

	/**
	 * @param RedisConnRef $conn
	 * @param bool $doWakeup Wake-up if an existing process finishes and wake up such others
	 * @return Status
	 */
	protected function waitForSlotOrNotif( RedisConnRef $conn, $doWakeup ) {
		try {
			$slot = $this->initAndPopPoolSlotList( $conn );
			if ( ctype_digit( $slot ) ) {
				// Pool slot acquired by this process
			} elseif ( $slot === 'QUEUE_FULL' ) {
				return Status::newGood( PoolCounter::QUEUE_FULL );
			} elseif ( $slot === 'QUEUE_WAIT' ) {
				// This process is now registed as waiting
				if ( $doWakeup ) {
					// The order of the keys to blPop() matters; the wale-up takes priority.
					// When a process relents and we get a 'w' slot (from $wakeKey), then we
					// know that the work that process did can be reused by the caller.
					$keys = array( $this->getWakeupListKey(), $this->getSlotListKey() );
				} else {
					$keys = array( $this->getSlotListKey() );
				}
				$res = $conn->blPop( $keys, $this->timeout ); // (list key,value) or ()
				if ( $res === array() ) {
					return Status::newGood( PoolCounter::TIMEOUT );
				}
				$slot = $res[1];
				// Unregister this process as waiting
				$conn->sRem( $this->getWaitSetKey(), $this->session );
			} else {
				return Status::newFatal( 'pool-error-unknown', "Server gave '$slot'." );
			}
		} catch ( RedisException $e ) {
			return Status::newFatal( 'pool-error-unknown', $e->getMessage() );
		}

		$this->slot = $slot;
		$this->onRelease = $doWakeup ? self::AWAKE_ALL : self::AWAKE_ONE;

		if ( $slot === 'w' ) {
			// Got a "process done" notification instead of a pool slot (only for $doWakeup)
			return Status::newGood( PoolCounter::DONE );
		} else {
			return Status::newGood( PoolCounter::LOCKED );
		}
	}

	/**
	 * @param RedisConnRef $conn
	 * @return int|string
	 */
	protected function initAndPopPoolSlotList( RedisConnRef $conn ) {
		static $script =
<<<LUA
		local kSlots,kSlotsLastFree,kSlotWaits = unpack(KEYS)
		local rMaxWorkers,rMaxQueue,rTimeout,rExpiry,rSess,rTime = unpack(ARGV)
		-- Initialize if the last-free time sorted-set is empty. The slot key
		-- itself is empty if all slots are busy or when nothing is initialized.
		-- If the list is empty but the set is not, then it is the later case.
		-- For sanity, if the list exists but not the set, then reset everything.
		if redis.call('exists',kSlotsLastFree) == 0 then
			redis.call('del',kSlots)
			for i = 1,1*rMaxWorkers do
				redis.call('rPush',kSlots,i)
				redis.call('zAdd',kSlotsLastFree,rTime,i)
			end
		-- Otherwise do maintenance to clean up after network partitions
		else
			-- Find stale slot locks and add free them as needed
			local staleLocks = redis.call('zRangeByScore',kSlotsLastFree,0,1*rTime - 1*rExpiry)
			for k,slot in ipairs(staleLocks) do
				redis.call('rPush',kSlots,slot)
				redis.call('zAdd',kSlotsLastFree,rTime,slot)
			end
			-- Find stale wait slot entries and remove them
			redis.call('zRemRangeByScore',kSlotWaits,0,1*rTime - 2*rTimeout)
		end
		local slot
		-- Try to acquire a slot if possible now
		if redis.call('lLen',kSlots) > 0 then
			slot = redis.call('lPop',kSlots)
		elseif redis.call('zCard',kSlotWaits) >= 1*rMaxQueue then
			slot = 'QUEUE_FULL'
		else
			slot = 'QUEUE_WAIT'
			redis.call('zAdd',kSlotWaits,rTime,rSess)
		end
		-- Always keep renewing the expiry on use
		redis.call('expire',kSlots,rExpiry)
		redis.call('expire',kSlotsLastFree,rExpiry)
		return slot
LUA;
		return $conn->luaEval( $script,
			array(
				$this->getSlotListKey(),
				$this->getSlotFTimeSetKey(),
				$this->getWaitSetKey(),
				$this->workers,
				$this->maxqueue,
				$this->timeout,
				$this->lockTTL, // how long a job should take to finish
				$this->session,
				time()
			),
			3 # number of first argument(s) that are keys
		);
	}

	/**
	 * @return string
	 */
	protected function getSlotListKey() {
		// @note: worker count does not vary within each $wgPoolCounterConf type
		return 'poolcounter:l-slots-' . sha1( $this->key ) . "-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getSlotFTimeSetKey() {
		// @note: worker count does not vary within each $wgPoolCounterConf type
		return 'poolcounter:z-lastfree-' . sha1( $this->key ) . "-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getWaitSetKey() {
		// @note: worker count does not vary within each $wgPoolCounterConf type
		return 'poolcounter:z-wait-' . sha1( $this->key ) . "-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getWakeupListKey() {
		// @note: worker count does not vary within each $wgPoolCounterConf type
		return 'poolcounter:l-wakeup-' . sha1( $this->key ) . "-{$this->workers}";
	}

	/**
	 * Try to make sure that locks get released
	 */
	function __destruct() {
		try {
			if ( $this->slot !== null ) {
				$this->release();
			}
		} catch ( Exception $e ) {}
	}
}
