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
	protected $bag = array();
	/** @var integer Max entries allowed */
	protected $maxCacheKeys;

	const KEY_VAL = 0;
	const KEY_EXP = 1;

	/**
	 * @param array $params Additional parameters include:
	 *   - maxKeys : only allow this many keys (using oldest-first eviction)
	 */
	function __construct( $params = array() ) {
		parent::__construct( $params );

		$this->maxCacheKeys = isset( $params['maxKeys'] ) ? $params['maxKeys'] : INF;
		Assert::parameter( $this->maxCacheKeys > 0, 'maxKeys', 'must be above zero' );
	}

	protected function expire( $key ) {
		$et = $this->bag[$key][self::KEY_EXP];
		if ( $et == self::TTL_INDEFINITE || $et > time() ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	protected function doGet( $key, $flags = 0 ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		} elseif ( $this->expire( $key ) ) {
			return false;
		}

		// Refresh key position for maxCacheKeys eviction
		$temp = $this->bag[$key];
		unset( $this->bag[$key] );
		$this->bag[$key] = $temp;

		return $this->bag[$key][self::KEY_VAL];
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->bag[$key] = array(
			self::KEY_VAL => $value,
			self::KEY_EXP => $this->convertExpiry( $exptime )
		);

		if ( count( $this->bag ) > $this->maxCacheKeys ) {
			reset( $this->bag );
			$evictKey = key( $this->bag );
			unset( $this->bag[$evictKey] );
		}

		return true;
	}

	public function delete( $key ) {
		unset( $this->bag[$key] );

		return true;
	}
}
