<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\LockManager;

use StatusValue;

/**
 * Simple lock management based on in-process reference counting.
 *
 * @since 1.19
 * @ingroup LockManager
 */
class NullLockManager extends LockManager {
	/** @inheritDoc */
	protected function doLockByType( array $pathsByType ) {
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				if ( isset( $this->locksHeld[$path][$type] ) ) {
					++$this->locksHeld[$path][$type];
				} else {
					$this->locksHeld[$path][$type] = 1;
				}
			}
		}

		return StatusValue::newGood();
	}

	/** @inheritDoc */
	protected function doUnlockByType( array $pathsByType ) {
		$status = StatusValue::newGood();
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				if ( isset( $this->locksHeld[$path][$type] ) ) {
					if ( --$this->locksHeld[$path][$type] <= 0 ) {
						unset( $this->locksHeld[$path][$type] );
						if ( !$this->locksHeld[$path] ) {
							unset( $this->locksHeld[$path] ); // clean up
						}
					}
				} else {
					$status->warning( 'lockmanager-notlocked', $path );
				}
			}
		}

		return $status;
	}
}
/** @deprecated class alias since 1.46 */
class_alias( NullLockManager::class, 'NullLockManager' );
