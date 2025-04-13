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

use MediaWiki\Status\Status;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Telemetry\NoopTracer;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\TracerInterface;

/**
 * Semaphore semantics to restrict how many workers may concurrently perform a task.
 *
 * When you have many workers (threads/servers) in service, and a
 * cached item expensive to produce expires, you may get several workers
 * computing the same expensive item at the same time.
 *
 * Given enough incoming requests and the item expiring quickly (non-cacheable,
 * or lots of edits or other invalidation events) that single task can end up
 * unfairly using most (or all) of the CPUs of the server cluster.
 * This is also known as "Michael Jackson effect", as this scenario happened on
 * the English Wikipedia in 2009 on the day Michael Jackson died.
 * See also <https://wikitech.wikimedia.org/wiki/Michael_Jackson_effect>.
 *
 * PoolCounter was created to provide semaphore semantics to restrict the number
 * of workers that may be concurrently performing a given task. Only one key
 * can be locked by any PoolCounter instance of a process, except for keys
 * that start with "nowait:". However, only non-blocking requests (timeout=0)
 * may be used with a "nowait:" key.
 *
 * By default PoolCounterNull is used, which provides no locking.
 * Install the poolcounterd service from
 * <https://gerrit.wikimedia.org/g/mediawiki/services/poolcounter> to
 * enable this feature.
 *
 * @since 1.16
 * @stable to extend
 */
abstract class PoolCounter implements LoggerAwareInterface {
	/* Return codes */
	public const LOCKED = 1; /* Lock acquired */
	public const RELEASED = 2; /* Lock released */
	public const DONE = 3; /* Another worker did the work for you */

	public const ERROR = -1; /* Indeterminate error */
	public const NOT_LOCKED = -2; /* Called release() with no lock held */
	public const QUEUE_FULL = -3; /* There are already maxqueue workers on this lock */
	public const TIMEOUT = -4; /* Timeout exceeded */
	public const LOCK_HELD = -5; /* Cannot acquire another lock while you have one lock held */

	/** @var string All workers with the same key share the lock */
	protected $key;
	protected string $type;
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
	/** @var int Maximum time in seconds to wait for the lock */
	protected $timeout;
	protected LoggerInterface $logger;
	protected TracerInterface $tracer;
	protected ?SpanInterface $heldLockSpan = null;

	/**
	 * @var bool Whether the key is a "might wait" key
	 */
	private $isMightWaitKey;
	/**
	 * @var int Whether this process holds a "might wait" lock key
	 */
	private static $acquiredMightWaitKey = 0;

	/**
	 * @var bool Enable fast stale mode (T250248). This may be overridden by the work class.
	 */
	private $fastStale;

	/**
	 * @param array $conf
	 * @param string $type The class of actions to limit concurrency for (task type)
	 * @param string $key
	 */
	public function __construct( array $conf, string $type, string $key ) {
		$this->workers = $conf['workers'];
		$this->maxqueue = $conf['maxqueue'];
		$this->timeout = $conf['timeout'];
		if ( isset( $conf['slots'] ) ) {
			$this->slots = $conf['slots'];
		}
		$this->fastStale = $conf['fastStale'] ?? false;
		$this->logger = new NullLogger();
		$this->tracer = new NoopTracer();

		if ( $this->slots ) {
			$key = $this->hashKeyIntoSlots( $type, $key, $this->slots );
		}

		$this->type = $type;
		$this->key = $key;
		$this->isMightWaitKey = !preg_match( '/^nowait:/', $this->key );
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * I want to do this task and I need to do it myself.
	 *
	 * @param int|null $timeout Wait timeout, or null to use value passed to
	 *   the constructor
	 * @return Status Value is one of Locked/Error
	 */
	abstract public function acquireForMe( $timeout = null );

	/**
	 * I want to do this task, but if anyone else does it
	 * instead, it's also fine for me. I will read its cached data.
	 *
	 * @param int|null $timeout Wait timeout, or null to use value passed to
	 *   the constructor
	 * @return Status Value is one of Locked/Done/Error
	 */
	abstract public function acquireForAnyone( $timeout = null );

	/**
	 * I have successfully finished my task.
	 * Lets another one grab the lock, and returns the workers
	 * waiting on acquireForAnyone()
	 *
	 * @return Status Value is one of Released/NotLocked/Error
	 */
	abstract public function release();

	/**
	 * Checks that the lock request is sensible.
	 * @return Status good for sensible requests, fatal for the not so sensible
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
				return Status::newFatal(
					'poolcounter-usage-error',
					'You may only aquire a single non-nowait lock.'
				);
			}
		} elseif ( $this->timeout !== 0 ) {
			return Status::newFatal(
				'poolcounter-usage-error',
				'Locks starting in nowait: must have 0 timeout.'
			);
		}
		return Status::newGood();
	}

	/**
	 * Update any lock tracking information when the lock is acquired
	 * @since 1.25
	 */
	final protected function onAcquire() {
		self::$acquiredMightWaitKey |= $this->isMightWaitKey;
		$this->heldLockSpan = $this->tracer->createSpan( "PoolCounterLocked::{$this->type}" )->start();
		$this->heldLockSpan->activate();
		if ( $this->heldLockSpan->getContext()->isSampled() ) {
			$this->heldLockSpan->setAttributes( [
				'org.wikimedia.poolcounter.key' => $this->key,
			] );
		}
	}

	/**
	 * Update any lock tracking information when the lock is released
	 * @since 1.25
	 */
	final protected function onRelease() {
		self::$acquiredMightWaitKey &= !$this->isMightWaitKey;
		if ( $this->heldLockSpan ) {
			$this->heldLockSpan->end();
			$this->heldLockSpan = null;
		}
	}

	/**
	 * Given a key (any string) and the number of lots, returns a slot key (a prefix with a suffix
	 * integer from the [0..($slots-1)] range). This is used for a global limit on the number of
	 * instances of a given type that can acquire a lock. The hashing is deterministic so that
	 * PoolCounter::$workers is always an upper limit of how many instances with the same key
	 * can acquire a lock.
	 *
	 * @param string $type The class of actions to limit concurrency for (task type)
	 * @param string $key PoolCounter instance key (any string)
	 * @param int $slots The number of slots (max allowed value is 65536)
	 * @return string Slot key with the type and slot number
	 */
	protected function hashKeyIntoSlots( $type, $key, $slots ) {
		return $type . ':' . ( hexdec( substr( sha1( $key ), 0, 4 ) ) % $slots );
	}

	/**
	 * Is fast stale mode (T250248) enabled? This may be overridden by the
	 * PoolCounterWork subclass.
	 *
	 * @return bool
	 */
	public function isFastStaleEnabled() {
		return $this->fastStale;
	}

	/**
	 * @since 1.42
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @since 1.45
	 * @param TracerInterface $tracer
	 * @return void
	 */
	public function setTracer( TracerInterface $tracer ) {
		$this->tracer = $tracer;
	}

	/**
	 * @internal For use in PoolCounterWork only
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface {
		return $this->logger;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( PoolCounter::class, 'PoolCounter' );
