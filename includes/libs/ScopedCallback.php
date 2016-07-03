<?php
/**
 * This file deals with RAII style scoped callbacks.
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
 * Class for asserting that a callback happens when an dummy object leaves scope
 *
 * @since 1.21
 */
class ScopedCallback {
	/** @var callable */
	protected $callback;
	/** @var array */
	protected $params;

	/**
	 * @param callable|null $callback
	 * @param array $params Callback arguments (since 1.25)
	 * @throws Exception
	 */
	public function __construct( $callback, array $params = [] ) {
		if ( $callback !== null && !is_callable( $callback ) ) {
			throw new InvalidArgumentException( "Provided callback is not valid." );
		}
		$this->callback = $callback;
		$this->params = $params;
	}

	/**
	 * Trigger a scoped callback and destroy it.
	 * This is the same is just setting it to null.
	 *
	 * @param ScopedCallback $sc
	 */
	public static function consume( ScopedCallback &$sc = null ) {
		$sc = null;
	}

	/**
	 * Destroy a scoped callback without triggering it
	 *
	 * @param ScopedCallback $sc
	 */
	public static function cancel( ScopedCallback &$sc = null ) {
		if ( $sc ) {
			$sc->callback = null;
		}
		$sc = null;
	}

	/**
	 * Trigger the callback when this leaves scope
	 */
	function __destruct() {
		if ( $this->callback !== null ) {
			call_user_func_array( $this->callback, $this->params );
		}
	}
}
