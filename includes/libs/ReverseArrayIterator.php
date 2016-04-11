<?php
/**
 * Convenience class for iterating over an array in reverse order.
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
 * @since 1.27
 */

/**
 * Convenience class for iterating over an array in reverse order.
 *
 * @since 1.27
 */
class ReverseArrayIterator implements Iterator, Countable {
	/** @var array $array */
	protected $array;

	/**
	 * Creates an iterator which will visit the keys in $array in
	 * reverse order.  If given an object, will visit the properties
	 * of the object in reverse order.  (Note that the default order
	 * for PHP arrays and objects is declaration/assignment order.)
	 *
	 * @param array|object $array
	 */
	public function __construct( $array = [] ) {
		if ( is_array( $array ) ) {
			$this->array = $array;
		} elseif ( is_object( $array ) ) {
			$this->array = get_object_vars( $array );
		} else {
			throw new InvalidArgumentException( __METHOD__ . ' requires an array or object' );
		}

		$this->rewind();
	}

	 public function current() {
		return current( $this->array );
	 }

	 public function key() {
		return key( $this->array );
	 }

	 public function next() {
		prev( $this->array );
	 }

	 public function rewind() {
		end( $this->array );
	 }

	 public function valid() {
		return key( $this->array ) !== null;
	 }

	 public function count() {
		return count( $this->array );
	 }
}
