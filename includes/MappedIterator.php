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
	/** @var Iterator|Array */
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
	 * @param Closure $callback
	 * @throws MWException
	 */
    public function __construct( $iter, Closure $vCallback ) {
		if ( is_array( $iter ) ) {
			$this->baseIterator = $iter;
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
		if ( is_array( $this->baseIterator ) ) {
			rewind( $this->baseIterator );
		} else {
			$this->baseIterator->rewind();
		}
	}

	/**
	 * @return Mixed|null Returns null if out of range
	 */
	public function current() {
		if ( is_array( $this->baseIterator ) ) {
			if ( key( $this->baseIterator ) === null ) {
				return null; // out of range
			}
			$value = current( $this->baseIterator );
		} else {
			if ( !$this->baseIterator->valid() ) {
				return null; // out of range
			}
			$value = $this->baseIterator->current();
		}
		return call_user_func_array( $this->vCallback, array( $value ) );
	}

	/**
	 * @return Mixed|null Returns null if out of range
	 */
	public function key() {
		if ( is_array( $this->baseIterator ) ) {
			$key = key( $this->baseIterator );
			if ( $key === null ) {
				return null; // out of range
			}
		} else {
			if ( !$this->baseIterator->valid() ) {
				return null; // out of range
			}
			$key = $this->baseIterator->key();
		}
		return $key;
	}

	/**
	 * @return Mixed|null Returns null if out of range
	 */
	public function next() {
		if ( is_array( $this->baseIterator ) ) {
			next( $this->baseIterator );
		} else {
			$this->baseIterator->next();
		}
		return $this->current();
	}

	/**
	 * @return bool
	 */
	public function valid() {
		if ( is_array( $this->baseIterator ) ) {
			return valid( $this->baseIterator );
		} else {
			return $this->baseIterator->valid();
		}
	}
}
