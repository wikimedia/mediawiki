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

	function __construct( $params = array() ) {
		parent::__construct( $params );
		$this->bag = array();
	}

	protected function expire( $key ) {
		$et = $this->bag[$key][1];

		if ( ( $et == 0 ) || ( $et > time() ) ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	public function get( $key, &$casToken = null ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		if ( $this->expire( $key ) ) {
			return false;
		}

		$casToken = $this->bag[$key][0];

		return $this->bag[$key][0];
	}

	public function set( $key, $value, $exptime = 0 ) {
		$this->bag[$key] = array( $value, $this->convertExpiry( $exptime ) );
		return true;
	}

	function delete( $key ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		unset( $this->bag[$key] );

		return true;
	}

	public function lock( $key, $timeout = 6, $expiry = 6 ) {
		return true;
	}

	function unlock( $key ) {
		return true;
	}
}
