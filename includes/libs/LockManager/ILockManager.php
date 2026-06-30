<?php

namespace Wikimedia\LockManager;

use Wikimedia\ScopedCallback;

/**
 * Interface representing MediaWiki's LockManager.
 */
interface ILockManager {
	/**
	 * Provide a mutex of the key
	 *
	 * @param string $key Key to lock
	 * @param int $timeout Time to wait in seconds
	 * @return bool true if the lock is acquired, false otherwise
	 */
	public function lockKey( string $key, int $timeout = 0 ): bool;

	/**
	 * @param string $key Key to unlock
	 * @return bool true if the lock is released, false otherwise
	 */
	public function unlockKey( string $key ): bool;

	/**
	 * Lock a key and unlock it automatically when the object is destructed
	 *
	 * @param string $key
	 * @param int $timeout
	 * @return ScopedCallback|null returns null when we failed to acquire the lock
	 */
	public function scopedLock( string $key, int $timeout = 0 ): ?ScopedCallback;
}
