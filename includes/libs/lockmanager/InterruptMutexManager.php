<?php
/**
 * @defgroup InterruptMutexManager High-performance blocking mutex management
 * @ingroup FileBackend
 */

/**
 * High-performance blocking mutex management
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
 * @ingroup LockManager
 * @author Aaron Schulz
 */

/**
 * @brief Class for handling high-performance blocking mutexes
 *
 * Mutexes are acquired via native blocking and are released automatically on disconnect.
 * These are suitable for high-traffic items that need FIFO-style blocking without polling.
 *
 * @ingroup LockManager
 * @since 1.19
 */
interface InterruptMutexManager {
	/**
	 * @param string $key
	 * @param int $timeout Timeout in seconds
	 * @return StatusValue
	 */
	public function acquireQueuedMutex( $key, $timeout = 0 );

	/**
	 * @param string $key
	 * @return StatusValue
	 */
	public function releaseQueuedMutex( $key );
}
