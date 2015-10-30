<?php

namespace MediaWiki\Cache;

use Cdb\Reader;
use Wikimedia\Assert\Assert;

/**
 * FauxCdbReader implements the CdbReader interface based on a simple
 * PHP array.
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
 * FauxCdbReader implements the CdbReader interface based on a simple
 * PHP array.
 *
 * @ingroup Cache
 */
class FauxCdbReader extends Reader {

	/**
	 * @var string
	 */
	private $data;

	/**
	 * A queue of keys to return from nextkey(),
	 * initialized by firstkey();
	 *
	 * @var string[]|null
	 */
	private $keys = null;

	/**
	 * Create the object and open the file
	 *
	 * @param string[] $data An associative PHP array.
	 */
	public function __construct( $data ) {
		Assert::parameterType( 'array', $data, '$data' );
		Assert::parameterElementType( 'string', $data, '$data' );

		$this->data = $data;
	}

	/**
	 * Close the file. Optional, you can just let the variable go out of scope.
	 */
	public function close() {
		$this->data = [];
		$this->keys = null;
	}

	/**
	 * Get a value with a given key. Only string values are supported.
	 *
	 * @param string $key
	 */
	public function get( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
	}

	/**
	 * Check whether key exists
	 *
	 * @param string $key
	 */
	public function exists( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Fetch first key
	 */
	public function firstkey() {
		$this->keys = array_keys( $this->data );
		return $this->nextkey();
	}

	/**
	 * Fetch next key
	 */
	public function nextkey() {
		if ( $this->keys === null ) {
			return $this->firstkey();
		}

		return empty( $this->keys ) ? null : array_shift( $this->keys );
	}

}
