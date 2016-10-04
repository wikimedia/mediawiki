<?php
/**
 * Allows extending classes to decorate an Iterator with
 * reduced boilerplate.
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
 * @ingroup Maintenance
 */
abstract class IteratorDecorator implements Iterator {
	protected $iterator;

	public function __construct( Iterator $iterator ) {
		$this->iterator = $iterator;
	}

	public function current() {
		return $this->iterator->current();
	}

	public function key() {
		return $this->iterator->key();
	}

	public function next() {
		$this->iterator->next();
	}

	public function rewind() {
		$this->iterator->rewind();
	}

	public function valid() {
		return $this->iterator->valid();
	}
}
