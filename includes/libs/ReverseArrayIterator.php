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
 * @author C. Scott Ananian
 */

/**
 * Convenience class for iterating over an array in reverse order.
 */
class ReverseArrayIterator extends IteratorIterator {
    protected $arr;
    public function __construct( &$arr ) {
        parent::__construct( new ArrayIterator( $arr ) );
        $this->arr = $arr;
    }
    public function current() {
        return $this->arr[ $this->key() ];
    }
    public function key() {
        return count( $this->arr ) - parent::key() - 1;
    }
}
