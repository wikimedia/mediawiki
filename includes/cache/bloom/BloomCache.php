<?php
/**
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
	protected static $instances = array();

	/**
	 * @param string $id
	 * @return BloomCache
	 */
	final public static function get( $id ) {
		global $wgBloomFilterStores;

		if ( !isset( self::$instances[$id] ) ) {
			if ( isset( $wgBloomFilterStores[$id] ) ) {
				$class = $wgBloomFilterStores[$id]['class'];
				self::$instances[$id] = new $class( $wgBloomFilterStores[$id] );
			} else {
				wfDebug( "No bloom filter store '$id'; using EmptyBloomCache." );
				return new EmptyBloomCache( array() );
			}
		}

		return self::$instances[$id];
	}

	/**
	 * @param array $config
	 */
	public function __construct( array $config ) {
	}

	/**
	 * Check if a member is set in the bloom filter.
	 *
	 * A member being set means that it *might* have been added.
	 * A member not being set means it *could not* have been added.
	 *
	 * This abstracts over isHit() to deal with filter updates and readiness.
	 * A class must exist with the name BloomFilter<type> and a static public
	 * merge() method. The later takes the following arguments:
	 *		(BloomCache $bcache, $domain, $virtualKey, $memberPrefix, array $status)
	 * The method should return a bool indicating whether to use the filter.
	 *
	 * These classes might use the 'primary' bloom key. This can be set by
	 * a site admin. E.g. in eval.php:
	 * <code>
	 *		$bcache = BloomCache::get( 'main' );
	 *		$bcache->init( 'primary', 1000000000, .001 );
	 * </code>
	 *
	 * @param string $domain
	 * @param string $type
	 * @param string $member
	 * @return bool True if set, false if not, null on error
	 */
	final public function checkWithMerge( $domain, $type, $member ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		if ( method_exists( "BloomFilter{$type}", 'merge' ) ) {
			try {
				$virtualKey = "$domain:$type";
				$memberPrefix = "$domain:$type";

				$status = $this->getStatus( $virtualKey );
				if ( $status == false ) {
					wfDebug( "Could not query virtual bloom filter '$virtualKey'." );
					return null;
				}

				$useFilter = call_user_func_array(
					array( "BloomFilter{$type}", 'merge' ),
					array( $this, $domain, $virtualKey, $memberPrefix, $status )
				);

				if ( $useFilter ) {
					return $this->isHit( 'primary', "$memberPrefix:$member" );
				}
			} catch ( MWException $e ) {
				MWExceptionHandler::logException( $e );
				return null;
			}
		}

		return true;
	}

	/**
	 * Get an exclusive lock on a filter for updates
	 *
	 * @param string $virtualKey
	 * @return ScopedCallback|ScopedLock|null Returns null if acquisition failed
	 */
	public function getScopedLock( $virtualKey ) {
		return null;
	}

	/**
	 * Create a new bloom filter at $key (if one does not exist yet)
	 *
	 * @param string $key
	 * @param integer $size Bit length
	 * @param float $precision
	 * @return bool Success
	 */
	final public function init( $key, $size = 1000000, $precision = .001 ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doInit( $key, $size, min( .1, $precision ) );
	}

	/**
	 * Add a member to the bloom filter at $key
	 *
	 * @param string $key
	 * @param string|array $members
	 * @return bool Success
	 */
	final public function add( $key, $members ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doAdd( $key, (array)$members );
	}

	/**
	 * Mark the bloom filter at $key as ready to use
	 *
	 * @param string $virtualKey
	 * @param array $values Map including some of (ready, lastID, asOfTime)
	 * @return bool Success
	 */
	final public function setStatus( $virtualKey, array $values ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doSetStatus( $virtualKey, $values );
	}

	/**
	 * Get the status map of the virtual bloom filter at $key
	 *
	 * The map includes:
	 *   - ready     : whether the filter is ready for use or not
	 *   - lastID    : the last item ID (or null)
	 *   - asOfTIme  : UNIX timestamp that the filter is up-to-date as of
	 *
	 * @param string $key
	 * @return array|bool False on failure
	 */
	final public function getStatus( $key ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doGetStatus( $key );
	}

	/**
	 * Check if a member is set in the bloom filter.
	 *
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
	final public function isHit( $key, $member ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doIsHit( $key, $member );
	}

	/**
	 * Destroy a bloom filter at $key
	 *
	 * @param string $key
	 * @return bool Success
	 */
	final public function delete( $key ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doDelete( $key );
	}

	/**
	 * @param string $key
	 * @param integer $size Bit length
	 * @param float $precision
	 * @return bool Success
	 */
	abstract protected function doInit( $key, $size, $precision );

	/**
	 * @param string $key
	 * @param array $members
	 * @return bool Success
	 */
	abstract protected function doAdd( $key, array $members );

	/**
	 * @param string $virtualKey
	 * @param array $values
	 * @return bool Success
	 */
	abstract protected function doSetStatus( $virtualKey, array $values );

	/**
	 * @param string $key
	 * @return array|bool False on failure
	 */
	abstract protected function doGetStatus( $key );

	/**
	 * @param string $key
	 * @param string $member
	 * @return bool True if set, false if not, null on error (e.g. the filter does not exist)
	 */
	abstract protected function doIsHit( $key, $member );

	/**
	 * @param string $key
	 * @return bool Success
	 */
	abstract protected function doDelete( $key );
}

class EmptyBloomCache extends BloomCache {
	protected function doInit( $key, $size, $precision ) {
		return true;
	}

	protected function doDelete( $key ) {
		return true;
	}

	protected function doAdd( $key, array $members ) {
		return true;
	}

	protected function doSetStatus( $virtualKey, array $values ) {
		return true;
	}

	protected function doGetStatus( $virtualKey ) {
		return array( 'precision' => 1.0, 'size' => 0, 'ready' => false, 'lastId' => null ) ;
	}

	protected function doIsHit( $key, $member ) {
		return true;
	}
}
