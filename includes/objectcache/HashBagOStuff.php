<?php
/**
 * Object caching using PHP arrays.
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
 * This is a test of the interface, mainly. It stores things in an associative
 * array, which is not going to persist between program runs.
 *
 * @ingroup Cache
 */
class HashBagOStuff extends BagOStuff {
	/** @var array */
	protected $bag;

	function __construct() {
		$this->bag = array();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	protected function expire( $key ) {
		$et = $this->bag[$key][1];

		if ( ( $et == 0 ) || ( $et > time() ) ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	/**
	 * @param string $key
	 * @param mixed $casToken [optional]
	 * @return bool|mixed
	 */
	function get( $key, &$casToken = null ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		if ( $this->expire( $key ) ) {
			return false;
		}

		$casToken = serialize( $this->bag[$key][0] );

		return $this->bag[$key][0];
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	function set( $key, $value, $exptime = 0 ) {
		$this->bag[$key] = array( $value, $this->convertExpiry( $exptime ) );
		return true;
	}

	/**
	 * @param mixed $casToken
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	function cas( $casToken, $key, $value, $exptime = 0 ) {
		if ( serialize( $this->get( $key ) ) === $casToken ) {
			return $this->set( $key, $value, $exptime );
		}

		return false;
	}

	/**
	 * @param string $key
	 * @param int $time
	 * @return bool
	 */
	function delete( $key, $time = 0 ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		unset( $this->bag[$key] );

		return true;
	}
}
