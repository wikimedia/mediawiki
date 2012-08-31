<?php
/**
 * Classes for URI-related handling
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 */

/**
 * The URI query format used in HTML forms (www-x-urlencoded) submitted to PHP.
 * Ex: a=b&d=c
 *
 * @since 1.23
 * @author Tyler Romeo
 */
class UriPhpFormQuery extends UriQuery implements ArrayAccess, Iterator
{
	private $elements;

	function getQueryString() {
		return http_build_query( $this->elements );
	}

	/**
	 * Get the array of query elements.
	 *
	 * @return array
	 */
	public function getArray() {
		return $this->elements;
	}

	function setQuery( $query ) {
		if ( is_array( $query ) ) {
			foreach ( $query as $key => $val ) {
				if ( $val === null || $val === false ) {
					unset( $query[ $key ] );
				}
			}
			$this->elements = $query;
		} else {
			$this->elements = array();
			parse_str( $query, $this->elements );
		}
	}

	function extendQuery( $parameters ) {
		if ( is_array( $parameters ) ) {
			foreach ( $parameters as $key => $val ) {
				if ( $val === null || $val === false ) {
					unset( $parameters[ $key ] );
				}
			}
			$this->elements = array_merge( $this->elements, $parameters );
		} else {
			parse_str( $parameters, $this->elements );
		}
	}

	function offsetExists( $offset ) {
		return array_key_exists( $offset, $this->elements );
	}

	function offsetGet( $offset ) {
		return $this->elements[ $offset ];
	}

	function offsetSet( $offset, $value ) {
		$this->elements[ $offset ] = $value;
	}

	function offsetUnset( $offset ) {
		unset( $this->elements[ $offset ] );
	}

	function current() {
		return current( $this->elements );
	}

	function key() {
		return key( $this->elements );
	}

	function next() {
		next( $this->elements );
	}

	function rewind() {
		reset( $this->elements );
	}

	function valid() {
		return $this->current() !== false;
	}
}