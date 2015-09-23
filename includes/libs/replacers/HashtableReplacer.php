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
 * Class to perform replacement based on a simple hashtable lookup
 */
class HashtableReplacer extends Replacer {
	private $table, $index;

	/**
	 * @param array $table
	 * @param int $index
	 */
	public function __construct( $table, $index = 0 ) {
		$this->table = $table;
		$this->index = $index;
	}

	/**
	 * @param array $matches
	 * @return mixed
	 */
	public function replace( array $matches ) {
		return $this->table[$matches[$this->index]];
	}
}

