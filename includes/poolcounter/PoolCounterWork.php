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
 */

namespace MediaWiki\PoolCounter;

use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;

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
	public function __construct( string $type, string $key, ?PoolCounter $poolCounter = null ) {
		$this->type = $type;
		// MW >= 1.35
		$this->poolCounter = $poolCounter ??
			MediaWikiServices::getInstance()->getPoolCounterFactory()->create( $type, $key );
	}

	/**
	 * Actually perform the work, caching it if needed
	 *
	 * @return mixed|false Work result or false
	 */
	abstract public function doWork();

	/**
	 * Retrieve the work from cache
	 *
	 * @return mixed|false Work result or false
	 */
	public function getCachedWork() {
		return false;
	}

	/**
	 * A work not so good (eg. expired one) but better than an error
	 * message.
	 *
	 * @param bool $fast True if PoolCounter is requesting a fast stale response (pre-wait)
	 * @return mixed|false Work result or false
	 */
	public function fallback( $fast ) {
		return false;
	}

	/**
	 * Do something with the error, like showing it to the user.
	 *
	 * @param Status $status
	 * @return mixed|false
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

		$this->poolCounter->getLogger()->info(
			"Pool key '$key' ({$this->type}): " .
			$status->getMessage()->inLanguage( 'en' )->useDatabase( false )->text()
		);
	}

	/**
	 * Get the result of the work (whatever it is), or the result of the error() function.
	 *
	 * This returns the result of the one of the following methods:
	 *
	 * - doWork(): Applies if the work is exclusive or no other process
	 *   is doing it, and on the condition that either this process
	 *   successfully entered the pool or the pool counter is down.
	 * - doCachedWork(): Applies if the work is cacheable and this blocked on another
	 *   process which finished the work.
	 * - fallback(): Applies for all remaining cases.
	 *
	 * If these all return false, then the result of error() is returned.
	 *
	 * In slow-stale mode, these three methods are called in the sequence given above, and
	 * the first non-false response is used. This means in case of concurrent cache-miss requests
	 * for the same revision, later ones will load on DBs and other backend services, and wait for
	 * earlier requests to succeed and then read out their saved result.
	 *
	 * In fast-stale mode, if other requests hold doWork lock already, we call fallback() first
	 * to let it try to find an acceptable return value. If fallback() returns false, then we
	 * will wait for the doWork lock, as for slow stale mode, including potentially calling
	 * fallback() a second time.
	 *
	 * @param bool $skipcache
	 * @return mixed
	 */
	public function execute( $skipcache = false ) {
		if ( !$this->cacheable || $skipcache ) {
			$status = $this->poolCounter->acquireForMe();
			$skipcache = true;
		} else {
			if ( $this->isFastStaleEnabled() ) {
				// In fast stale mode, check for existing locks by acquiring lock with 0 timeout
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
					return $this->doWork();
				} finally {
					$this->poolCounter->release();
				}
			// no fall-through, because try returns or throws
			case PoolCounter::DONE:
				$result = $this->getCachedWork();
				if ( $result === false ) {
					if ( $skipcache ) {
						// We shouldn't get here, because we called acquireForMe().
						// which should not return DONE. If we do get here, this
						// indicates a faulty test mock. Report the issue instead
						// of calling $this->execute( true ) in endless recursion.
						throw new LogicException(
							'Got PoolCounter::DONE from acquireForMe() and ' .
							'getCachedWork() returned nothing'
						);
					}

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
					PoolCounter::TIMEOUT => 'pool-timeout',
				];

				$status = Status::newFatal( $errors[$status->value] ?? 'pool-errorunknown' );
				$this->logError( $status );
				return $this->error( $status );
		}
	}
}

/** @deprecated class alias since 1.42 */
class_alias( PoolCounterWork::class, 'PoolCounterWork' );
