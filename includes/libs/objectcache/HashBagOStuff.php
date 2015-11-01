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
use Wikimedia\Assert\Assert;

/**
 * This is a test of the interface, mainly. It stores things in an associative
 * array, which is not going to persist between program runs.
 *
 * @ingroup Cache
 */
class HashBagOStuff extends BagOStuff {
	/** @var mixed[] */
	protected $bag;
	/** @var integer[] */
	protected $expiries = array();
	/** @var integer Max entries allowed */
	protected $maxCacheKeys;

	/**
	 * @param array $params Additional parameters include:
	 *   - maxKeys : only allow this many keys (using oldest-first eviction)
	 *   - initialData : an associative array of data that should be preloaded.
	 */
	function __construct( $params = array() ) {
		parent::__construct( $params );

		$this->maxCacheKeys = isset( $params['maxKeys'] ) ? $params['maxKeys'] : INF;
		Assert::parameter( $this->maxCacheKeys > 0, 'maxKeys', 'must be above zero' );

		$this->bag = isset( $params['initialData'] ) ? $params['initialData'] : array();
		Assert::parameter( is_array( $this->bag ), 'initialData', 'must be an array' );
	}

	protected function expire( $key ) {
		if ( empty( $this->expiries[$key] ) || $this->expiries[$key] > time() ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	protected function doGet( $key, $flags = 0 ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		if ( $this->expire( $key ) ) {
			return false;
		}

		return $this->bag[$key];
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->bag[$key] = $value;
		$this->expiries[$key] = $this->convertExpiry( $exptime );

		if ( count( $this->bag ) > $this->maxCacheKeys ) {
			reset( $this->bag );
			$evictKey = key( $this->bag );
			$this->delete( $evictKey );
		}

		return true;
	}

	public function delete( $key ) {
		unset( $this->bag[$key] );
		unset( $this->expiries[$key] );

		return true;
	}
}
