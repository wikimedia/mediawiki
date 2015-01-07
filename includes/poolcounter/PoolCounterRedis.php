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
 * Version of PoolCounter that uses Redis
 *
 * There are four main redis keys used to track each pool counter key:
 *   - poolcounter:l-slots-*     : A list of available slot IDs for a pool.
 *   - poolcounter:z-renewtime-* : A sorted set of (slot ID, UNIX timestamp as score)
 *                                 used for tracking the next time a slot should be
 *                                 released. This is -1 when a slot is created, and is
 *                                 set when released (expired), locked, and unlocked.
 *   - poolcounter:z-wait-*      : A sorted set of (slot ID, UNIX timestamp as score)
 *                                 used for tracking waiting processes (and wait time).
 *   - poolcounter:l-wakeup-*    : A list pushed to for the sake of waking up processes
 *                                 when a any process in the pool finishes (lasts for 1ms).
 * For a given pool key, all the redis keys start off non-existing and are deleted if not
 * used for a while to prevent garbage from building up on the server. They are atomically
 * re-initialized as needed. The "z-renewtime" key is used for detecting sessions which got
 * slots but then disappeared. Stale entries from there have their timestamp updated and the
 * corresponding slots freed up. The "z-wait" key is used for detecting processes registered
 * as waiting but that disappeared. Stale entries from there are deleted and the corresponding
 * slots are freed up. The worker count is included in all the redis key names as it does not
 * vary within each $wgPoolCounterConf type and doing so handles configuration changes.
 *
 * This class requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 * Also this should be on a server plenty of RAM for the working set to avoid evictions.
 * Evictions could temporarily allow wait queues to double in size or temporarily cause
 * pools to appear as full when they are not. Using volatile-ttl and bumping memory-samples
 * in redis.conf can be helpful otherwise.
 *
 * @ingroup Redis
 * @since 1.23
 */
class PoolCounterRedis extends PoolCounter {
	/** @var HashRing */
	protected $ring;
	/** @var RedisConnectionPool */
	protected $pool;
	/** @var array (server label => host) map */
	protected $serversByLabel;
	/** @var string SHA-1 of the key */
	protected $keySha1;
	/** @var int TTL for locks to expire (work should finish in this time) */
	protected $lockTTL;

	/** @var RedisConnRef */
	protected $conn;
	/** @var string Pool slot value */
	protected $slot;
	/** @var int AWAKE_* constant */
	protected $onRelease;
	/** @var string Unique string to identify this process */
	protected $session;
	/** @var int UNIX timestamp */
	protected $slotTime;

	const AWAKE_ONE = 1; // wake-up if when a slot can be taken from an existing process
	const AWAKE_ALL = 2; // wake-up if an existing process finishes and wake up such others

	/** @var array List of active PoolCounterRedis objects in this script */
	protected static $active = null;

	function __construct( $conf, $type, $key ) {
		parent::__construct( $conf, $type, $key );

		$this->serversByLabel = $conf['servers'];
		$this->ring = new HashRing( array_fill_keys( array_keys( $conf['servers'] ), 100 ) );

		$conf['redisConfig']['serializer'] = 'none'; // for use with Lua
		$this->pool = RedisConnectionPool::singleton( $conf['redisConfig'] );

		$this->keySha1 = sha1( $this->key );
		$met = ini_get( 'max_execution_time' ); // usually 0 in CLI mode
		$this->lockTTL = $met ? 2 * $met : 3600;

		if ( self::$active === null ) {
			self::$active = array();
			register_shutdown_function( array( __CLASS__, 'releaseAll' ) );
		}
	}

	/**
	 * @return Status Uses RediConnRef as value on success
	 */
	protected function getConnection() {
		if ( !isset( $this->conn ) ) {
			$conn = false;
			$servers = $this->ring->getLocations( $this->key, 3 );
			ArrayUtils::consistentHashSort( $servers, $this->key );
			foreach ( $servers as $server ) {
				$conn = $this->pool->getConnection( $this->serversByLabel[$server] );
				if ( $conn ) {
					break;
				}
			}
			if ( !$conn ) {
				return Status::newFatal( 'pool-servererror', implode( ', ', $servers ) );
			}
			$this->conn = $conn;
		}
		return Status::newGood( $this->conn );
	}

	function acquireForMe() {

		$status = $this->precheckAcquire();
		if ( !$status->isGood() ) {
			return $status;
		}

		return $this->waitForSlotOrNotif( self::AWAKE_ONE );
	}

	function acquireForAnyone() {

		$status = $this->precheckAcquire();
		if ( !$status->isGood() ) {
			return $status;
		}

		return $this->waitForSlotOrNotif( self::AWAKE_ALL );
	}

	function release() {

		if ( $this->slot === null ) {
			return Status::newGood( PoolCounter::NOT_LOCKED ); // not locked
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		static $script =
<<<LUA
		local kSlots,kSlotsNextRelease,kWakeup,kWaiting = unpack(KEYS)
		local rMaxWorkers,rExpiry,rSlot,rSlotTime,rAwakeAll,rTime = unpack(ARGV)
		-- Add the slots back to the list (if rSlot is "w" then it is not a slot).
		-- Treat the list as expired if the "next release" time sorted-set is missing.
		if rSlot ~= 'w' and redis.call('exists',kSlotsNextRelease) == 1 then
			if 1*redis.call('zScore',kSlotsNextRelease,rSlot) ~= (rSlotTime + rExpiry) then
				-- Slot lock expired and was released already
			elseif redis.call('lLen',kSlots) >= 1*rMaxWorkers then
				-- Slots somehow got out of sync; reset the list for sanity
				redis.call('del',kSlots,kSlotsNextRelease)
			elseif redis.call('lLen',kSlots) == (1*rMaxWorkers - 1) and redis.call('zCard',kWaiting) == 0 then
				-- Slot list will be made full; clear it to save space (it re-inits as needed)
				-- since nothing is waiting on being unblocked by a push to the list
				redis.call('del',kSlots,kSlotsNextRelease)
			else
				-- Add slot back to pool and update the "next release" time
				redis.call('rPush',kSlots,rSlot)
				redis.call('zAdd',kSlotsNextRelease,rTime + 30,rSlot)
				-- Always keep renewing the expiry on use
				redis.call('expireAt',kSlots,math.ceil(rTime + rExpiry))
				redis.call('expireAt',kSlotsNextRelease,math.ceil(rTime + rExpiry))
			end
		end
		-- Update an ephemeral list to wake up other clients that can
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
					$this->getSlotRTimeSetKey(),
					$this->getWakeupListKey(),
					$this->getWaitSetKey(),
					$this->workers,
					$this->lockTTL,
					$this->slot,
					$this->slotTime, // used for CAS-style sanity check
					( $this->onRelease === self::AWAKE_ALL ) ? 1 : 0,
					microtime( true )
				),
				4 # number of first argument(s) that are keys
			);
		} catch ( RedisException $e ) {
			return Status::newFatal( 'pool-error-unknown', $e->getMessage() );
		}

		$this->slot = null;
		$this->slotTime = null;
		$this->onRelease = null;
		unset( self::$active[$this->session] );

		$this->onRelease();

		return Status::newGood( PoolCounter::RELEASED );
	}

	/**
	 * @param int $doWakeup AWAKE_* constant
	 * @return Status
	 */
	protected function waitForSlotOrNotif( $doWakeup ) {
		if ( $this->slot !== null ) {
			return Status::newGood( PoolCounter::LOCK_HELD ); // already acquired
		}

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		$now = microtime( true );
		try {
			$slot = $this->initAndPopPoolSlotList( $conn, $now );
			if ( ctype_digit( $slot ) ) {
				// Pool slot acquired by this process
				$slotTime = $now;
			} elseif ( $slot === 'QUEUE_FULL' ) {
				// Too many processes are waiting for pooled processes to finish
				return Status::newGood( PoolCounter::QUEUE_FULL );
			} elseif ( $slot === 'QUEUE_WAIT' ) {
				// This process is now registered as waiting
				$keys = ( $doWakeup == self::AWAKE_ALL )
					// Wait for an open slot or wake-up signal (preferring the later)
					? array( $this->getWakeupListKey(), $this->getSlotListKey() )
					// Just wait for an actual pool slot
					: array( $this->getSlotListKey() );

				$res = $conn->blPop( $keys, $this->timeout );
				if ( $res === array() ) {
					$conn->zRem( $this->getWaitSetKey(), $this->session ); // no longer waiting
					return Status::newGood( PoolCounter::TIMEOUT );
				}

				$slot = $res[1]; // pool slot or "w" for wake-up notifications
				$slotTime = microtime( true ); // last microtime() was a few RTTs ago
				// Unregister this process as waiting and bump slot "next release" time
				$this->registerAcquisitionTime( $conn, $slot, $slotTime );
			} else {
				return Status::newFatal( 'pool-error-unknown', "Server gave slot '$slot'." );
			}
		} catch ( RedisException $e ) {
			return Status::newFatal( 'pool-error-unknown', $e->getMessage() );
		}

		if ( $slot !== 'w' ) {
			$this->slot = $slot;
			$this->slotTime = $slotTime;
			$this->onRelease = $doWakeup;
			self::$active[$this->session] = $this;
		}

		$this->onAcquire();

		return Status::newGood( $slot === 'w' ? PoolCounter::DONE : PoolCounter::LOCKED );
	}

	/**
	 * @param RedisConnRef $conn
	 * @param float $now UNIX timestamp
	 * @return string|bool False on failure
	 */
	protected function initAndPopPoolSlotList( RedisConnRef $conn, $now ) {
		static $script =
<<<LUA
		local kSlots,kSlotsNextRelease,kSlotWaits = unpack(KEYS)
		local rMaxWorkers,rMaxQueue,rTimeout,rExpiry,rSess,rTime = unpack(ARGV)
		-- Initialize if the "next release" time sorted-set is empty. The slot key
		-- itself is empty if all slots are busy or when nothing is initialized.
		-- If the list is empty but the set is not, then it is the later case.
		-- For sanity, if the list exists but not the set, then reset everything.
		if redis.call('exists',kSlotsNextRelease) == 0 then
			redis.call('del',kSlots)
			for i = 1,1*rMaxWorkers do
				redis.call('rPush',kSlots,i)
				redis.call('zAdd',kSlotsNextRelease,-1,i)
			end
		-- Otherwise do maintenance to clean up after network partitions
		else
			-- Find stale slot locks and add free them (avoid duplicates for sanity)
			local staleLocks = redis.call('zRangeByScore',kSlotsNextRelease,0,rTime)
			for k,slot in ipairs(staleLocks) do
				redis.call('lRem',kSlots,0,slot)
				redis.call('rPush',kSlots,slot)
				redis.call('zAdd',kSlotsNextRelease,rTime + 30,slot)
			end
			-- Find stale wait slot entries and remove them
			redis.call('zRemRangeByScore',kSlotWaits,0,rTime - 2*rTimeout)
		end
		local slot
		-- Try to acquire a slot if possible now
		if redis.call('lLen',kSlots) > 0 then
			slot = redis.call('lPop',kSlots)
			-- Update the slot "next release" time
			redis.call('zAdd',kSlotsNextRelease,rTime + rExpiry,slot)
		elseif redis.call('zCard',kSlotWaits) >= 1*rMaxQueue then
			slot = 'QUEUE_FULL'
		else
			slot = 'QUEUE_WAIT'
			-- Register this process as waiting
			redis.call('zAdd',kSlotWaits,rTime,rSess)
			redis.call('expireAt',kSlotWaits,math.ceil(rTime + 2*rTimeout))
		end
		-- Always keep renewing the expiry on use
		redis.call('expireAt',kSlots,math.ceil(rTime + rExpiry))
		redis.call('expireAt',kSlotsNextRelease,math.ceil(rTime + rExpiry))
		return slot
LUA;
		return $conn->luaEval( $script,
			array(
				$this->getSlotListKey(),
				$this->getSlotRTimeSetKey(),
				$this->getWaitSetKey(),
				$this->workers,
				$this->maxqueue,
				$this->timeout,
				$this->lockTTL,
				$this->session,
				$now
			),
			3 # number of first argument(s) that are keys
		);
	}

	/**
	 * @param RedisConnRef $conn
	 * @param string $slot
	 * @param float $now
	 * @return int|bool False on failure
	 */
	protected function registerAcquisitionTime( RedisConnRef $conn, $slot, $now ) {
		static $script =
<<<LUA
		local kSlots,kSlotsNextRelease,kSlotWaits = unpack(KEYS)
		local rSlot,rExpiry,rSess,rTime = unpack(ARGV)
		-- If rSlot is 'w' then the client was told to wake up but got no slot
		if rSlot ~= 'w' then
			-- Update the slot "next release" time
			redis.call('zAdd',kSlotsNextRelease,rTime + rExpiry,rSlot)
			-- Always keep renewing the expiry on use
			redis.call('expireAt',kSlots,math.ceil(rTime + rExpiry))
			redis.call('expireAt',kSlotsNextRelease,math.ceil(rTime + rExpiry))
		end
		-- Unregister this process as waiting
		redis.call('zRem',kSlotWaits,rSess)
		return 1
LUA;
		return $conn->luaEval( $script,
			array(
				$this->getSlotListKey(),
				$this->getSlotRTimeSetKey(),
				$this->getWaitSetKey(),
				$slot,
				$this->lockTTL,
				$this->session,
				$now
			),
			3 # number of first argument(s) that are keys
		);
	}

	/**
	 * @return string
	 */
	protected function getSlotListKey() {
		return "poolcounter:l-slots-{$this->keySha1}-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getSlotRTimeSetKey() {
		return "poolcounter:z-renewtime-{$this->keySha1}-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getWaitSetKey() {
		return "poolcounter:z-wait-{$this->keySha1}-{$this->workers}";
	}

	/**
	 * @return string
	 */
	protected function getWakeupListKey() {
		return "poolcounter:l-wakeup-{$this->keySha1}-{$this->workers}";
	}

	/**
	 * Try to make sure that locks get released (even with exceptions and fatals)
	 */
	public static function releaseAll() {
		foreach ( self::$active as $poolCounter ) {
			try {
				if ( $poolCounter->slot !== null ) {
					$poolCounter->release();
				}
			} catch ( Exception $e ) {
			}
		}
	}
}
