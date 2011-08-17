<?php

/**
 *  When you have many workers (threads/servers) giving service, and a
 * cached item expensive to produce expires, you may get several workers
 * doing the job at the same time.
 *
 *  Given enough requests and the item expiring fast (non-cacheable,
 * lots of edits...) that single work can end up unfairly using most (all)
 * of the cpu of the pool. This is also known as 'Michael Jackson effect'
 * since this effect triggered on the english wikipedia on the day Michael
 * Jackson died, the biographical article got hit with several edits per
 * minutes and hundreds of read hits.
 *
 *  The PoolCounter provides semaphore semantics for restricting the number
 * of workers that may be concurrently performing such single task.
 *
 *  By default PoolCounter_Stub is used, which provides no locking. You
 * can get a useful one in the PoolCounter extension.
 */
abstract class PoolCounter {

	/* Return codes */
	const LOCKED   = 1; /* Lock acquired */
	const RELEASED = 2; /* Lock released */
	const DONE     = 3; /* Another worker did the work for you */

	const ERROR      = -1; /* Indeterminate error */
	const NOT_LOCKED = -2; /* Called release() with no lock held */
	const QUEUE_FULL = -3; /* There are already maxqueue workers on this lock */
	const TIMEOUT    = -4; /* Timeout exceeded */
	const LOCK_HELD  = -5; /* Cannot acquire another lock while you have one lock held */

	/**
	 * I want to do this task and I need to do it myself.
	 *
	 * @return Locked/Error
	 */
	abstract function acquireForMe();

	/**
	 * I want to do this task, but if anyone else does it
	 * instead, it's also fine for me. I will read its cached data.
	 *
	 * @return Locked/Done/Error
	 */
	abstract function acquireForAnyone();

	/**
	 * I have successfully finished my task.
	 * Lets another one grab the lock, and returns the workers
	 * waiting on acquireForAnyone()
	 *
	 * @return Released/NotLocked/Error
	 */
	abstract function release();

	/**
	 *  $key: All workers with the same key share the lock.
	 *  $workers: It wouldn't be a good idea to have more than this number of
	 * workers doing the task simultaneously.
	 *  $maxqueue: If this number of workers are already working/waiting,
	 * fail instead of wait.
	 *  $timeout: Maximum time in seconds to wait for the lock.
	 */
	protected $key, $workers, $maxqueue, $timeout;

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

	protected function __construct( $conf, $type, $key ) {
		$this->key = $key;
		$this->workers  = $conf['workers'];
		$this->maxqueue = $conf['maxqueue'];
		$this->timeout  = $conf['timeout'];
	}
}

class PoolCounter_Stub extends PoolCounter {

	/**
	 * @return Status
	 */
	function acquireForMe() {
		return Status::newGood( PoolCounter::LOCKED );
	}

	/**
	 * @return Status
	 */
	function acquireForAnyone() {
		return Status::newGood( PoolCounter::LOCKED );
	}

	/**
	 * @return Status
	 */
	function release() {
		return Status::newGood( PoolCounter::RELEASED );
	}

	public function __construct() {
		/* No parameters needed */
	}
}

/**
 * Handy class for dealing with PoolCounters using class members instead of callbacks.
 */
abstract class PoolCounterWork {
	protected $cacheable = false; //Does this override getCachedWork() ?

	/**
	 * Actually perform the work, caching it if needed.
	 */
	abstract function doWork();

	/**
	 * Retrieve the work from cache
	 * @return mixed work result or false
	 */
	function getCachedWork() {
		return false;
	}

	/**
	 * A work not so good (eg. expired one) but better than an error
	 * message.
	 * @return mixed work result or false
	 */
	function fallback() {
		return false;
	}

	/**
	 * Do something with the error, like showing it to the user.
	 */
	function error( $status ) {
		return false;
	}

	/**
	 * Log an error
	 *
	 * @param $status Status
	 */
	function logError( $status ) {
		wfDebugLog( 'poolcounter', $status->getWikiText() );
	}

	/**
	 * Get the result of the work (whatever it is), or false.
	 * @param $skipcache bool
	 * @return bool|mixed
	 */
	function execute( $skipcache = false ) {
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
				$errors = array( PoolCounter::QUEUE_FULL => 'pool-queuefull', PoolCounter::TIMEOUT => 'pool-timeout' );

				$status = Status::newFatal( isset( $errors[$status->value] ) ? $errors[$status->value] : 'pool-errorunknown' );
				$this->logError( $status );
				return $this->error( $status );
		}
	}

	function __construct( $type, $key ) {
		$this->poolCounter = PoolCounter::factory( $type, $key );
	}
}
