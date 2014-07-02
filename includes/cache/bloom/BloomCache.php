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
	/** @var string Unique ID for key namespacing */
	protected $cacheID;

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
	 * Create a new bloom cache instance from configuration.
	 * This should only be called from within BloomCache.
	 *
	 * @param array $config Parameters include:
	 *   - cacheID : Prefix to all bloom filter names that is unique to this cache.
	 *               It should only consist of alphanumberic, '-', and '_' characters.
	 *               This ID is what avoids collisions if multiple logical caches
	 *               use the same storage system, so this should be set carefully.
	 */
	public function __construct( array $config ) {
		$this->cacheID = $config['cacheId'];
		if ( !preg_match( '!^[a-zA-Z0-9-_]{1,32}$!', $this->cacheID ) ) {
			throw new MWException( "Cache ID '{$this->cacheID}' is invalid." );
		}
	}

	/**
	 * Check if a member is set in the bloom filter
	 *
	 * A member being set means that it *might* have been added.
	 * A member not being set means it *could not* have been added.
	 *
	 * This abstracts over isHit() to deal with filter updates and readiness.
	 * A class must exist with the name BloomFilter<type> and a static public
	 * mergeAndCheck() method. The later takes the following arguments:
	 *		(BloomCache $bcache, $domain, $virtualKey, array $status)
	 * The method should return a bool indicating whether to use the filter.
	 *
	 * The 'shared' bloom key must be used for any updates and will be used
	 * for the membership check if the method returns true. Since the key is shared,
	 * the method should never use delete(). The filter cannot be used in cases where
	 * membership in the filter needs to be taken away. In such cases, code *cannot*
	 * use this method - instead, it can directly use the other BloomCache methods
	 * to manage custom filters with their own keys (e.g. not 'shared').
	 *
	 * @param string $domain
	 * @param string $type
	 * @param string $member
	 * @return bool True if set, false if not (also returns true on error)
	 */
	final public function check( $domain, $type, $member ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		if ( method_exists( "BloomFilter{$type}", 'mergeAndCheck' ) ) {
			try {
				$virtualKey = "$domain:$type";

				$status = $this->getStatus( $virtualKey );
				if ( $status == false ) {
					wfDebug( "Could not query virtual bloom filter '$virtualKey'." );
					return null;
				}

				$useFilter = call_user_func_array(
					array( "BloomFilter{$type}", 'mergeAndCheck' ),
					array( $this, $domain, $virtualKey, $status )
				);

				if ( $useFilter ) {
					return ( $this->isHit( 'shared', "$virtualKey:$member" ) !== false );
				}
			} catch ( MWException $e ) {
				MWExceptionHandler::logException( $e );
				return true;
			}
		}

		return true;
	}

	/**
	 * Inform the bloom filter of a new member in order to keep it up to date
	 *
	 * @param string $domain
	 * @param string $type
	 * @param string|array $members
	 * @return bool Success
	 */
	final public function insert( $domain, $type, $members ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		if ( method_exists( "BloomFilter{$type}", 'mergeAndCheck' ) ) {
			try {
				$virtualKey = "$domain:$type";
				$prefixedMembers = array();
				foreach ( (array)$members as $member ) {
					$prefixedMembers[] = "$virtualKey:$member";
				}

				return $this->add( 'shared', $prefixedMembers );
			} catch ( MWException $e ) {
				MWExceptionHandler::logException( $e );
				return false;
			}
		}

		return true;
	}

	/**
	 * Create a new bloom filter at $key (if one does not exist yet)
	 *
	 * @param string $key
	 * @param integer $size Bit length [default: 1000000]
	 * @param float $precision [default: .001]
	 * @return bool Success
	 */
	final public function init( $key, $size = 1000000, $precision = .001 ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doInit( "{$this->cacheID}:$key", $size, min( .1, $precision ) );
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

		return $this->doAdd( "{$this->cacheID}:$key", (array)$members );
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
	 * @return bool|null True if set, false if not, null on error
	 */
	final public function isHit( $key, $member ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doIsHit( "{$this->cacheID}:$key", $member );
	}

	/**
	 * Destroy a bloom filter at $key
	 *
	 * @param string $key
	 * @return bool Success
	 */
	final public function delete( $key ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doDelete( "{$this->cacheID}:$key" );
	}

	/**
	 * Set the status map of the virtual bloom filter at $key
	 *
	 * @param string $virtualKey
	 * @param array $values Map including some of (lastID, asOfTime, epoch)
	 * @return bool Success
	 */
	final public function setStatus( $virtualKey, array $values ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doSetStatus( "{$this->cacheID}:$virtualKey", $values );
	}

	/**
	 * Get the status map of the virtual bloom filter at $key
	 *
	 * The map includes:
	 *   - lastID    : the highest ID of the items merged in
	 *   - asOfTime  : UNIX timestamp that the filter is up-to-date as of
	 *   - epoch     : UNIX timestamp that filter started being populated
	 * Unset fields will have a null value.
	 *
	 * @param string $virtualKey
	 * @return array|bool False on failure
	 */
	final public function getStatus( $virtualKey ) {
		$section = new ProfileSection( get_class( $this ) . '::' . __FUNCTION__ );

		return $this->doGetStatus( "{$this->cacheID}:$virtualKey" );
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
	 * @param string $key
	 * @param string $member
	 * @return bool|null
	 */
	abstract protected function doIsHit( $key, $member );

	/**
	 * @param string $key
	 * @return bool Success
	 */
	abstract protected function doDelete( $key );

	/**
	 * @param string $virtualKey
	 * @param array $values
	 * @return bool Success
	 */
	abstract protected function doSetStatus( $virtualKey, array $values );

	/**
	 * @param string $key
	 * @return array|bool
	 */
	abstract protected function doGetStatus( $key );
}

class EmptyBloomCache extends BloomCache {
	public function __construct( array $config ) {
		parent::__construct( array( 'cacheId' => 'none' ) );
	}

	protected function doInit( $key, $size, $precision ) {
		return true;
	}

	protected function doAdd( $key, array $members ) {
		return true;
	}

	protected function doIsHit( $key, $member ) {
		return true;
	}

	protected function doDelete( $key ) {
		return true;
	}

	protected function doSetStatus( $virtualKey, array $values ) {
		return true;
	}

	protected function doGetStatus( $virtualKey ) {
		return array( 'lastID' => null, 'asOfTime' => null, 'epoch' => null ) ;
	}
}
