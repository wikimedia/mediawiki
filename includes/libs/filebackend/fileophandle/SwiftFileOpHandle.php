<?php
/**
 * OpenStack Swift based file backend.
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
 * @ingroup FileBackend
 * @author Russ Nelson
 */

/**
 * @see FileBackendStoreOpHandle
 */
class SwiftFileOpHandle extends FileBackendStoreOpHandle {
	/** @var array List of Requests for MultiHttpClient */
	public $httpOp;
	/** @var Closure */
	public $callback;

	/**
	 * @param SwiftFileBackend $backend
	 * @param Closure $callback Function that takes (HTTP request array, status)
	 * @param array $httpOp MultiHttpClient op
	 */
	public function __construct( SwiftFileBackend $backend, Closure $callback, array $httpOp ) {
		$this->backend = $backend;
		$this->callback = $callback;
		$this->httpOp = $httpOp;
	}
}
