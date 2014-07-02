<?php
/**
 * Class for fetching backlink lists, approximate backlink counts and
 * partitions.
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
 * Persistent bloom filter used to avoid expensive lookups
 *
 * @since 1.24
 */
abstract class BloomCache {
	/** @var array Map of (id => BloomCache) */
	protected $instances;

	final public static function get( $id ) {
		global $wgBloomFilterStores;

		if ( !isset( $wgBloomFilterStores[$id] ) ) {
			throw new MWException( "No bloom filter store '$id'." );
		}

		$class = $wgBloomFilterStores[$id]['class'];
		return new $class( $wgBloomFilterStores[$id] );
	}

	public function __construct( array $config ) {
	}

	/**
	 * Create a new bloom filter at $key (if one does not exist yet)
	 *
	 * @param string $key
	 * @param integer $len Bit length
	 * @return bool Success
	 */
	abstract public function init( $key, $len );

	/**
	 * Destroy a bloom filter at $key
	 *
	 * @param string $key
	 * @return bool Success
	 */
	abstract public function delete( $key );

	/**
	 * Add a member to the bloom filter at $key
	 *
	 * @param string $key
	 * @param string $member
	 * @return bool Success
	 */
	abstract public function add( $key, $member );

	/**
	 * Check if a member is set in the bloom filter.
	 * A member being set means that it *might* have been added.
	 * A member not being set means it *could not* have been added.
	 *
	 * If this returns true, then the caller usually should do the
	 * expensive check (whatever that may be). It can be avoided otherwise.
	 *
	 * @param string $key
	 * @param string $member
	 * @return bool True if set, false if not, null on error (e.g. the filter does not exist)
	 */
	abstract public function isPositive( $key, $member );

	/**
	 * Get the false positive rate of the filter at $key for a given size
	 *
	 * @param integer $size
	 * @return float|bool Returns false on error
	 */
	abstract public function getFalsePositiveRate( $size );
}

class EmptyBloomCache extends BloomCache {
	public function init( $key, $len ) {
		return true;
	}

	public function delete( $key ) {
		return true;
	}

	public function add( $key, $member ) {
		return true;
	}

	public function isPositive( $key, $member ) {
		return true;
	}

	public function getFalsePositiveRate( $key ) {
		return 1.0;
	}
}
