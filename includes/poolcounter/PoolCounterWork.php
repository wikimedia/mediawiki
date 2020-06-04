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
 * Class for dealing with PoolCounters using class members
 */
abstract class PoolCounterWork {
	/** @var string */
	protected $type = 'generic';
	/** @var bool */
	protected $cacheable = false; // does this override getCachedWork() ?
	/** @var PoolCounter */
	private $poolCounter;

	/**
	 * @param string $type The class of actions to limit concurrency for (task type)
	 * @param string $key Key that identifies the queue this work is placed on
	 * @param PoolCounter|null $poolCounter
	 */
	public function __construct( string $type, string $key, PoolCounter $poolCounter = null ) {
		$this->type = $type;
		// MW >= 1.35
		$this->poolCounter = $poolCounter ?? PoolCounter::factory( $type, $key );
	}

	/**
	 * Actually perform the work, caching it if needed
	 *
	 * @return mixed Work result or false
	 */
	abstract public function doWork();

	/**
	 * Retrieve the work from cache
	 *
	 * @return mixed Work result or false
	 */
	public function getCachedWork() {
		return false;
	}

	/**
	 * A work not so good (eg. expired one) but better than an error
	 * message.
	 *
	 * @param bool $fast True if PoolCounter is requesting a fast stale response (pre-wait)
	 * @return mixed Work result or false
	 */
	public function fallback( $fast ) {
		return false;
	}

	/**
	 * Do something with the error, like showing it to the user.
	 *
	 * @param Status $status
	 * @return bool
	 */
	public function error( $status ) {
		return false;
	}

	/**
	 * Should fast stale mode be used?
	 *
	 * @return bool
	 */
	protected function isFastStaleEnabled() {
		return $this->poolCounter->isFastStaleEnabled();
	}

	/**
	 * Log an error
	 *
	 * @param Status $status
	 * @return void
	 */
	public function logError( $status ) {
		$key = $this->poolCounter->getKey();

		wfDebugLog( 'poolcounter', "Pool key '$key' ({$this->type}): "
			. $status->getMessage()->inLanguage( 'en' )->useDatabase( false )->text() );
	}

	/**
	 * Get the result of the work (whatever it is), or the result of the error() function.
	 * This returns the result of the one of the following methods:
	 *   - a) doWork()       : Applies if the work is exclusive or no another process
	 *                         is doing it, and on the condition that either this process
	 *                         successfully entered the pool or the pool counter is down.
	 *   - b) doCachedWork() : Applies if the work is cacheable and this blocked on another
	 *                         process which finished the work.
	 *   - c) fallback()     : Applies for all remaining cases.
	 * If these all fall through (by returning false), then the result of error() is returned.
	 *
	 * In slow stale mode, these three methods are called in the sequence given above, and
	 * the first non-false response is used.
	 *
	 * In fast stale mode, fallback() is called first if the lock acquisition would block.
	 * If fallback() returns false, the lock is waited on, then the three methods are
	 * called in the same sequence as for slow stale mode, including potentially calling
	 * fallback() a second time.
	 *
	 * @param bool $skipcache
	 * @return mixed
	 */
	public function execute( $skipcache = false ) {
		if ( $this->cacheable && !$skipcache ) {
			if ( $this->isFastStaleEnabled() ) {
				// In fast stale mode, do not wait if fallback() would succeed.
				// Try to acquire the lock with timeout=0
				$status = $this->poolCounter->acquireForAnyone( 0 );
				if ( $status->isOK() && $status->value === PoolCounter::TIMEOUT ) {
					// Lock acquisition would block: try fallback
					$staleResult = $this->fallback( true );
					if ( $staleResult !== false ) {
						return $staleResult;
					}
					// No fallback available, so wait for the lock
					$status = $this->poolCounter->acquireForAnyone();
				} // else behave as if $status were returned in slow mode
			} else {
				$status = $this->poolCounter->acquireForAnyone();
			}
		} else {
			$status = $this->poolCounter->acquireForMe();
		}

		if ( !$status->isOK() ) {
			// Respond gracefully to complete server breakage: just log it and do the work
			$this->logError( $status );
			return $this->doWork();
		}

		switch ( $status->value ) {
			case PoolCounter::LOCK_HELD:
				// Better to ignore nesting pool counter limits than to fail.
				// Assume that the outer pool limiting is reasonable enough.
				/* no break */
			case PoolCounter::LOCKED:
				try {
					$result = $this->doWork();
				} finally {
					$this->poolCounter->release();
				}
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
				$result = $this->fallback( false );

				if ( $result !== false ) {
					return $result;
				}
				/* no break */

			/* These two cases should never be hit... */
			case PoolCounter::ERROR:
			default:
				$errors = [
					PoolCounter::QUEUE_FULL => 'pool-queuefull',
					PoolCounter::TIMEOUT => 'pool-timeout' ];

				$status = Status::newFatal( $errors[$status->value] ?? 'pool-errorunknown' );
				$this->logError( $status );
				return $this->error( $status );
		}
	}
}
