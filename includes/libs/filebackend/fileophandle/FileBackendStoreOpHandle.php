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
 * @ingroup FileBackend
 */

/**
 * FileBackendStore helper class for performing asynchronous file operations.
 *
 * For example, calling FileBackendStore::createInternal() with the "async"
 * param flag may result in a StatusValue that contains this object as a value.
 * This class is largely backend-specific and is mostly just "magic" to be
 * passed to FileBackendStore::executeOpHandlesInternal().
 */
abstract class FileBackendStoreOpHandle {
	/** @var array */
	public $params = []; // params to caller functions
	/** @var FileBackendStore */
	public $backend;
	/** @var array */
	public $resourcesToClose = [];
	/** @var callable name that identifies the function called */
	public $call;

	/**
	 * Close all open file handles
	 */
	public function closeResources() {
		array_map( 'fclose', $this->resourcesToClose );
	}
}
