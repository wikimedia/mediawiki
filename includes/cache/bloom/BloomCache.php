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
	 * @throws MWException
	 */
	public function __construct( array $config ) {
		$this->cacheID = $config['cacheId'];
		if ( !preg_match( '!^[a-zA-Z0-9-_]{1,32}$!', $this->cacheID ) ) {
			throw new Exception( "Cache ID '{$this->cacheID}' is invalid." );
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
	 *		(BloomCacheServer $bcache, $domain, $virtualKey, array $status)
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
		$ps = Profiler::instance()->scopedProfileIn( get_class( $this ) . '::' . __FUNCTION__ );

		if ( method_exists( "BloomFilter{$type}", 'mergeAndCheck' ) ) {
			try {
				$virtualKey = "$domain:$type";

				$replica = $this->getConnection();
				if ( !$replica ) {
					return true;
				}

				$status = $replica->getStatus( $virtualKey );
				if ( $status == false ) {
					wfDebug( "Could not query virtual bloom filter '$virtualKey'." );
					return true;
				}

				$useFilter = call_user_func_array(
					array( "BloomFilter{$type}", 'mergeAndCheck' ),
					array( $replica, $domain, $virtualKey, $status )
				);

				if ( $useFilter ) {
					return ( $replica->isHit( 'shared', "$virtualKey:$member" ) !== false );
				}
			} catch ( Exception $e ) {
				MWExceptionHandler::logException( $e );
				return true;
			}
		}

		return true;
	}

	/**
	 * Create a new bloom filter at $key if needed (on all replicas)
	 *
	 * @param string $key
	 * @param integer $size Bit length [default: 1000000]
	 * @param float $precision [default: .001]
	 * @return bool Success
	 */
	final public function init( $key, $size = 1000000, $precision = .001 ) {
		$ps = Profiler::instance()->scopedProfileIn( get_class( $this ) . '::' . __FUNCTION__ );

		$ok = true;
		foreach ( $this->getServers() as $server ) {
			$replica = $this->getConnection( $server );
			if ( $replica ) {
				$ok = $replica->init( $key, $size, min( .1, $precision ) ) && $ok;
			} else {
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * Destroy a bloom filter at $key (on all replicas)
	 *
	 * @param string $key
	 * @return bool Success
	 */
	final public function delete( $key ) {
		$ps = Profiler::instance()->scopedProfileIn( get_class( $this ) . '::' . __FUNCTION__ );

		$ok = true;
		foreach ( $this->getServers() as $server ) {
			$replica = $this->getConnection( $server );
			if ( $replica ) {
				$ok = $replica->delete( $key ) && $ok;
			} else {
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * Get all replica servers for this bloom cache
	 *
	 * @return array Server names of all replicas
	 * @since 1.25
	 */
	abstract public function getServers();

	/**
	 * Get a replica server connection for this bloom cache
	 *
	 * @param string $server Server name; random if not provided
	 * @return BloomCacheServer|null Returns null on error
	 * @since 1.25
	 */
	abstract public function getConnection( $server = null );
}
