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
 * Persistent bloom filter server used to avoid expensive lookups
 *
 * @since 1.25
 */
abstract class BloomCacheServer {
	/** @var string Unique ID for key namespacing */
	protected $cacheID;

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
	 * Create a new bloom filter at $key (if one does not exist yet)
	 *
	 * @param string $key
	 * @param integer $size Bit length [default: 1000000]
	 * @param float $precision [default: .001]
	 * @return bool Success
	 */
	abstract public function init( $key, $size, $precision );

	/**
	 * Add a member to the bloom filter at $key
	 *
	 * @param string $key
	 * @param string|array $members
	 * @return bool Success
	 */
	abstract public function add( $key, array $members );

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
	abstract public function isHit( $key, $member );

	/**
	 * Destroy a bloom filter at $key
	 *
	 * @param string $key
	 * @return bool Success
	 */
	abstract public function delete( $key );

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
	abstract public function getStatus( $virtualKey );

	/**
	 * Set the status map of the virtual bloom filter at $key
	 *
	 * @param string $virtualKey
	 * @param array $values Map including some of (lastID, asOfTime, epoch)
	 * @return bool Success
	 */
	abstract public function setStatus( $virtualKey, array $values );

	/**
	 * Get an exclusive lock on a filter for updates
	 *
	 * @param string $virtualKey
	 * @return ScopedCallback|ScopedLock|null Returns null if acquisition failed
	 */
	abstract public function getScopedLock( $virtualKey );
}
