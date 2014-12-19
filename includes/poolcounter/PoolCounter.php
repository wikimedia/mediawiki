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
 */

/**
 * When you have many workers (threads/servers) giving service, and a
 * cached item expensive to produce expires, you may get several workers
 * doing the job at the same time.
 *
 * Given enough requests and the item expiring fast (non-cacheable,
 * lots of edits...) that single work can end up unfairly using most (all)
 * of the cpu of the pool. This is also known as 'Michael Jackson effect'
 * since this effect triggered on the english wikipedia on the day Michael
 * Jackson died, the biographical article got hit with several edits per
 * minutes and hundreds of read hits.
 *
 * The PoolCounter provides semaphore semantics for restricting the number
 * of workers that may be concurrently performing such single task. Only one
 * key can be locked by any PoolCounter instance of a process, except for keys
 * that start with "nowait:". However, only 0 timeouts (non-blocking requests)
 * can be used with "nowait:" keys.
 *
 * By default PoolCounter_Stub is used, which provides no locking. You
 * can get a useful one in the PoolCounter extension.
 */
abstract class PoolCounter {
	/* Return codes */
	const LOCKED = 1; /* Lock acquired */
	const RELEASED = 2; /* Lock released */
	const DONE = 3; /* Another worker did the work for you */

	const ERROR = -1; /* Indeterminate error */
	const NOT_LOCKED = -2; /* Called release() with no lock held */
	const QUEUE_FULL = -3; /* There are already maxqueue workers on this lock */
	const TIMEOUT = -4; /* Timeout exceeded */
	const LOCK_HELD = -5; /* Cannot acquire another lock while you have one lock held */

	/** @var string All workers with the same key share the lock */
	protected $key;
	/** @var int Maximum number of workers working on tasks with the same key simultaneously */
	protected $workers;
	/**
	 * Maximum number of workers working on this task type, regardless of key.
	 * 0 means unlimited. Max allowed value is 65536.
	 * The way the slot limit is enforced is overzealous - this option should be used with caution.
	 * @var int
	 */
	protected $slots = 0;
	/** @var int If this number of workers are already working/waiting, fail instead of wait */
	protected $maxqueue;
	/** @var float Maximum time in seconds to wait for the lock */
	protected $timeout;

	/**
	 * @var boolean Whether the key is a "might wait" key
	 */
	private $isMightWaitKey;
	/**
	 * @var boolean Whether this process holds a "might wait" lock key
	 */
	private static $acquiredMightWaitKey = 0;

	/**
	 * @param array $conf
	 * @param string $type
	 * @param string $key
	 */
	protected function __construct( $conf, $type, $key ) {
		$this->workers = $conf['workers'];
		$this->maxqueue = $conf['maxqueue'];
		$this->timeout = $conf['timeout'];
		if ( isset( $conf['slots'] ) ) {
			$this->slots = $conf['slots'];
		}

		if ( $this->slots ) {
			$key = $this->hashKeyIntoSlots( $key, $this->slots );
		}
		$this->key = $key;
		$this->isMightWaitKey = !preg_match( '/^nowait:/', $this->key );
	}

	/**
	 * Create a Pool counter. This should only be called from the PoolWorks.
	 *
	 * @param string $type
	 * @param string $key
	 *
	 * @return PoolCounter
	 */
	public static function factory( $type, $key ) {
		global $wgPoolCounterConf;
		if ( !isset( $wgPoolCounterConf[$type] ) ) {
			return new PoolCounter_Stub;
		}
		$conf = $wgPoolCounterConf[$type];
		$class = $conf['class'];

		return new $class( $conf, $type, $key );
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * I want to do this task and I need to do it myself.
	 *
	 * @return Status Value is one of Locked/Error
	 */
	abstract public function acquireForMe();

	/**
	 * I want to do this task, but if anyone else does it
	 * instead, it's also fine for me. I will read its cached data.
	 *
	 * @return Status Value is one of Locked/Done/Error
	 */
	abstract public function acquireForAnyone();

	/**
	 * I have successfully finished my task.
	 * Lets another one grab the lock, and returns the workers
	 * waiting on acquireForAnyone()
	 *
	 * @return Status Value is one of Released/NotLocked/Error
	 */
	abstract public function release();

	/**
	 * Checks that the lock request is sane.
	 * @return Status - good for sane requests fatal for insane
	 * @since 1.25
	 */
	final protected function precheckAcquire() {
		if ( $this->isMightWaitKey ) {
			if ( self::$acquiredMightWaitKey ) {
				/*
				 * The poolcounter itself is quite happy to allow you to wait
				 * on another lock while you have a lock you waited on already
				 * but we think that it is unlikely to be a good idea.  So we
				 * made it an error.  If you are _really_ _really_ sure it is a
				 * good idea then feel free to implement an unsafe flag or
				 * something.
				 */
				return Status::newFatal( 'poolcounter-usage-error',
					'You may only aquire a single non-nowait lock.' );
			}
		} elseif ( $this->timeout !== 0 ) {
			return Status::newFatal( 'poolcounter-usage-error',
				'Locks starting in nowait: must have 0 timeout.' );
		}
		return Status::newGood();
	}

	/**
	 * Update any lock tracking information when the lock is acquired
	 * @since 1.25
	 */
	final protected function onAcquire() {
		self::$acquiredMightWaitKey |= $this->isMightWaitKey;
	}

	/**
	 * Update any lock tracking information when the lock is released
	 * @since 1.25
	 */
	final protected function onRelease() {
		self::$acquiredMightWaitKey &= !$this->isMightWaitKey;
	}

	/**
	 * Given a key (any string) and the number of lots, returns a slot number (an integer from the [0..($slots-1)] range).
	 * This is used for a global limit on the number of instances  of a given type that can acquire a lock.
	 * The hashing is deterministic so that PoolCounter::$workers is always an upper limit of how many instances with
	 * the same key can acquire a lock.
	 *
	 * @param string $key PoolCounter instance key (any string)
	 * @param int $slots The number of slots (max allowed value is 65536)
	 * @return int
	 */
	protected function hashKeyIntoSlots( $key, $slots ) {
		return hexdec( substr( sha1( $key ), 0, 4 ) ) % $slots;
	}
}

// @codingStandardsIgnoreStart Squiz.Classes.ValidClassName.NotCamelCaps
class PoolCounter_Stub extends PoolCounter {
	// @codingStandardsIgnoreEnd

	public function __construct() {
		/* No parameters needed */
	}

	public function acquireForMe() {
		return Status::newGood( PoolCounter::LOCKED );
	}

	public function acquireForAnyone() {
		return Status::newGood( PoolCounter::LOCKED );
	}

	public function release() {
		return Status::newGood( PoolCounter::RELEASED );
	}
}
