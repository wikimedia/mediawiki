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

/**
 * Simple lock management based on in-process reference counting.
 *
 * @since 1.19
 * @ingroup LockManager
 */
class NullLockManager extends LockManager {
	/** @inheritDoc */
	protected function doLock( array $paths, $type ) {
		foreach ( $paths as $path ) {
			if ( isset( $this->locksHeld[$path][$type] ) ) {
				++$this->locksHeld[$path][$type];
			} else {
				$this->locksHeld[$path][$type] = 1;
			}
		}

		return StatusValue::newGood();
	}

	/** @inheritDoc */
	protected function doUnlock( array $paths, $type ) {
		$status = StatusValue::newGood();

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

		return $status;
	}
}
