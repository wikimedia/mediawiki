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
 * of workers that may be concurrently performing such single task.
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
	/** @var integer Maximum number of workers doing the task simultaneously */
	protected $workers;
	/** @var integer If this number of workers are already working/waiting, fail instead of wait */
	protected $maxqueue;
	/** @var float Maximum time in seconds to wait for the lock */
	protected $timeout;

	/**
	 * @param array $conf
	 * @param string $type
	 * @param string $key
	 */
	protected function __construct( $conf, $type, $key ) {
		$this->key = $key;
		$this->workers = $conf['workers'];
		$this->maxqueue = $conf['maxqueue'];
		$this->timeout = $conf['timeout'];
	}

	/**
	 * Create a Pool counter. This should only be called from the PoolWorks.
	 *
	 * @param $type
	 * @param $key
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
	 * @return Status value is one of Released/NotLocked/Error
	 */
	abstract public function release();
}

class PoolCounter_Stub extends PoolCounter {
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

/**
 * Class for dealing with PoolCounters using class members
 */
abstract class PoolCounterWork {
	protected $cacheable = false; //Does this override getCachedWork() ?

	/**
	 * @param string $type The type of PoolCounter to use
	 * @param string $key Key that identifies the queue this work is placed on
	 */
	public function __construct( $type, $key ) {
		$this->poolCounter = PoolCounter::factory( $type, $key );
	}

	/**
	 * Actually perform the work, caching it if needed
	 * @return mixed work result or false
	 */
	abstract public function doWork();

	/**
	 * Retrieve the work from cache
	 * @return mixed work result or false
	 */
	public function getCachedWork() {
		return false;
	}

	/**
	 * A work not so good (eg. expired one) but better than an error
	 * message.
	 * @return mixed work result or false
	 */
	public function fallback() {
		return false;
	}

	/**
	 * Do something with the error, like showing it to the user.
	 * @return bool
	 */
	function error( $status ) {
		return false;
	}

	/**
	 * Log an error
	 *
	 * @param $status Status
	 * @return void
	 */
	function logError( $status ) {
		wfDebugLog( 'poolcounter', $status->getWikiText() );
	}

	/**
	 * Get the result of the work (whatever it is), or the result of the error() function.
	 * This returns the result of the first applicable method that returns a non-false value,
	 * where the methods are checked in the following order:
	 *   - a) doWork()       : Applies if the work is exclusive or no another process
	 *                         is doing it, and on the condition that either this process
	 *                         successfully entered the pool or the pool counter is down.
	 *   - b) doCachedWork() : Applies if the work is cacheable and this blocked on another
	 *                         process which finished the work.
	 *   - c) fallback()     : Applies for all remaining cases.
	 * If these all fall through (by returning false), then the result of error() is returned.
	 *
	 * @param $skipcache bool
	 * @return mixed
	 */
	public function execute( $skipcache = false ) {
		if ( $this->cacheable && !$skipcache ) {
			$status = $this->poolCounter->acquireForAnyone();
		} else {
			$status = $this->poolCounter->acquireForMe();
		}

		if ( !$status->isOK() ) {
			// Respond gracefully to complete server breakage: just log it and do the work
			$this->logError( $status );
			return $this->doWork();
		}

		switch ( $status->value ) {
			case PoolCounter::LOCKED:
				$result = $this->doWork();
				$this->poolCounter->release();
				return $result;

			case PoolCounter::DONE:
				$result = $this->getCachedWork();
				if ( $result === false ) {
					/* That someone else work didn't serve us.
					 * Acquire the lock for me
					 */
					return $this->execute( true );
				}
				return $result;

			case PoolCounter::QUEUE_FULL:
			case PoolCounter::TIMEOUT:
				$result = $this->fallback();

				if ( $result !== false ) {
					return $result;
				}
				/* no break */

			/* These two cases should never be hit... */
			case PoolCounter::ERROR:
			default:
				$errors = array(
					PoolCounter::QUEUE_FULL => 'pool-queuefull',
					PoolCounter::TIMEOUT => 'pool-timeout' );

				$status = Status::newFatal( isset( $errors[$status->value] )
					? $errors[$status->value]
					: 'pool-errorunknown' );
				$this->logError( $status );
				return $this->error( $status );
		}
	}
}

/**
 * Convenience class for dealing with PoolCounters using callbacks
 * @since 1.22
 */
class PoolCounterWorkViaCallback extends PoolCounterWork {
	/** @var callable */
	protected $doWork;
	/** @var callable|null */
	protected $doCachedWork;
	/** @var callable|null */
	protected $fallback;
	/** @var callable|null */
	protected $error;

	/**
	 * Build a PoolCounterWork class from a type, key, and callback map.
	 *
	 * The callback map must at least have a callback for the 'doWork' method.
	 * Additionally, callbacks can be provided for the 'doCachedWork', 'fallback',
	 * and 'error' methods. Methods without callbacks will be no-ops that return false.
	 * If a 'doCachedWork' callback is provided, then execute() may wait for any prior
	 * process in the pool to finish and reuse its cached result.
	 *
	 * @param string $type
	 * @param string $key
	 * @param array $callbacks Map of callbacks
	 * @throws MWException
	 */
	public function __construct( $type, $key, array $callbacks ) {
		parent::__construct( $type, $key );
		foreach ( array( 'doWork', 'doCachedWork', 'fallback', 'error' ) as $name ) {
			if ( isset( $callbacks[$name] ) ) {
				if ( !is_callable( $callbacks[$name] ) ) {
					throw new MWException( "Invalid callback provided for '$name' function." );
				}
				$this->$name = $callbacks[$name];
			}
		}
		if ( !isset( $this->doWork ) ) {
			throw new MWException( "No callback provided for 'doWork' function." );
		}
		$this->cacheable = isset( $this->doCachedWork );
	}

	public function doWork() {
		return call_user_func_array( $this->doWork, array() );
	}

	public function getCachedWork() {
		if ( $this->doCachedWork ) {
			return call_user_func_array( $this->doCachedWork, array() );
		}
		return false;
	}

	function fallback() {
		if ( $this->fallback ) {
			return call_user_func_array( $this->fallback, array() );
		}
		return false;
	}

	function error( $status ) {
		if ( $this->error ) {
			return call_user_func_array( $this->error, array( $status ) );
		}
		return false;
	}
}
