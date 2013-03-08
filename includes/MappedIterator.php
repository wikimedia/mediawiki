<?php
/**
 * Convenience class for generating iterators from iterators.
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
 * @author Aaron Schulz
 */

/**
 * Convenience class for generating iterators from iterators.
 *
 * @since 1.21
 */
class MappedIterator implements Iterator {
	/** @var Iterator */
	protected $baseIterator;
	/** @var Closure */
	protected $vCallback;

	/**
	 * Build an new iterator from a base iterator by having the former wrap the
	 * later, returning the result of "value" callback for each current() invocation.
	 * The callback takes the result of current() on the base iterator as an argument.
	 * The keys of the base iterator are reused verbatim.
	 *
	 * @param Iterator|Array $iter
	 * @param Closure $vCallback
	 * @throws MWException
	 */
    public function __construct( $iter, Closure $vCallback ) {
		if ( is_array( $iter ) ) {
			$this->baseIterator = new ArrayIterator( $iter );
		} elseif ( $iter instanceof Iterator ) {
			$this->baseIterator = $iter;
		} else {
			throw new MWException( "Invalid base iterator provided." );
		}
		$this->vCallback = $vCallback;
    }

	/**
	 * @return void
	 */
	public function rewind() {
		$this->baseIterator->rewind();
	}

	/**
	 * @return Mixed|null Returns null if out of range
	 */
	public function current() {
		if ( !$this->baseIterator->valid() ) {
			return null; // out of range
		}
		return call_user_func_array( $this->vCallback, array( $this->baseIterator->current() ) );
	}

	/**
	 * @return Mixed|null Returns null if out of range
	 */
	public function key() {
		if ( !$this->baseIterator->valid() ) {
			return null; // out of range
		}
		return $this->baseIterator->key();
	}

	/**
	 * @return void
	 */
	public function next() {
		$this->baseIterator->next();
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->baseIterator->valid();
	}
}
