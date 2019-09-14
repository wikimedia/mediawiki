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
	/** @var array[] List of HTTP request maps for MultiHttpClient */
	public $httpOp;
	/** @var Closure Function to run after each HTTP request finishes */
	public $callback;

	/** @var int Class CONTINUE_* constant */
	public $state = self::CONTINUE_IF_OK;

	/** @var int Continue with the next requests stages if no errors occured */
	const CONTINUE_IF_OK = 0;
	/** @var int Cancel the next requests stages */
	const CONTINUE_NO = 1;

	/**
	 * Construct a handle to be use with SwiftFileOpHandle::doExecuteOpHandlesInternal()
	 *
	 * The callback returns a class CONTINUE_* constant and takes the following parameters:
	 *   - An HTTP request map array with 'response' filled
	 *   - A StatusValue instance to be updated as needed
	 *
	 * @param SwiftFileBackend $backend
	 * @param Closure $callback
	 * @param array $httpOp MultiHttpClient op
	 */
	public function __construct( SwiftFileBackend $backend, Closure $callback, array $httpOp ) {
		$this->backend = $backend;
		$this->callback = $callback;
		$this->httpOp = $httpOp;
	}
}
