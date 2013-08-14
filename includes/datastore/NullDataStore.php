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
 * @since 1.23
 *
 * @file
 */

/**
 * Store that stores nothing. Fastest NoSQL in the world.
 */
class NullDataStore extends DataStore {

	/**
	 * Returns value for a given key or null if not found
	 *
	 * @param string $key Data key
	 * @param bool $latest Whether a replicated or distributed store should ensure that the data returned is latest
	 *
	 * @return mixed
	 */
	public function get( $key, $latest = false ) {
		return null;
	}

	/**
	 * Sets value for a given key
	 *
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
	}

	/**
	 * Returns all values whose keys start with a given string
	 *
	 * @param string $prefix
	 * @param bool $latest Whether a replicated or distributed store should ensure that the data returned is latest
	 * @return Iterator
	 */
	public function getByPrefix( $prefix, $latest = false ) {
		return new EmptyIterator();
	}

	public function delete( $keys ) {
	}

	protected function deleteByPrefixInternal( $prefix ) {
	}
}
