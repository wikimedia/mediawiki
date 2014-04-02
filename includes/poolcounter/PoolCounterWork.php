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
	public function error( $status ) {
		return false;
	}

	/**
	 * Log an error
	 *
	 * @param $status Status
	 * @return void
	 */
	public function logError( $status ) {
		$key = $this->poolCounter->getKey();

		wfDebugLog( 'poolcounter', "Pool key '$key': "
			. $status->getMessage()->inLanguage( 'en' )->useDatabase( false )->text() );
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

	public function fallback() {
		if ( $this->fallback ) {
			return call_user_func_array( $this->fallback, array() );
		}
		return false;
	}

	public function error( $status ) {
		if ( $this->error ) {
			return call_user_func_array( $this->error, array( $status ) );
		}
		return false;
	}
}
