<?php
/**
 * Dummy object caching.
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
 * @ingroup Cache
 */

/**
 * A BagOStuff object with no objects in it. Used to provide a no-op object to calling code.
 *
 * @ingroup Cache
 */
class EmptyBagOStuff extends BagOStuff {
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		return false;
	}

	public function add( $key, $value, $exp = 0, $flags = 0 ) {
		return true;
	}

	public function set( $key, $value, $exp = 0, $flags = 0 ) {
		return true;
	}

	public function delete( $key, $flags = 0 ) {
		return true;
	}

	public function incr( $key, $value = 1 ) {
		return false;
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return true; // faster
	}
}
