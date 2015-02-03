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
 * @ingroup Stash
 * @author Aaron Schulz
 */

/**
 * @defgroup Stash Stash
 */

/**
 * Multi-datacenter aware stashing interface
 *
 * All operations write to the local cluster stash immediately,
 * and are broadcasted to the other data centers asynchronously.
 *
 * This class is intended for use as an ephemeral primary store.
 * Callers should also be aware of the increased risk of race
 * conditions as writes to the same key can happen in different
 * data centers at the same time, one of them eventually winning.
 * This store is generally appropriate for temporary data where:
 *   - a) The value is immutable (stored once); or
 *   - b) The value is validated on get(); or
 *   - c) Anti-dependencies are expected to be serialized with
 *        sufficient delay between r-w pairs to avoid races; or
 *   - d) Race conditions are expected to be otherwise rare
 *        or don't really matter that much
 *
 * Instances of this class must be configured to point to a valid
 * PubSub endpoint, and there must be listeners that subscribe to
 * the endpoint and update the caches. The listeners should be aware
 * of any consistent hashing scheme used by the client so that writes
 * do not need to go to every server.
 *
 * When used with memcached/redis, it might be simplest to point
 * both MediaWiki and the purge daemon to twemproxy, mcrouter,
 * or the like, to handle the consistent hashing. With memcached,
 * one must be careful to deal with the usage of the "flags" field.
 *
 * @ingroup Cache
 * @since 1.25
 */
abstract class WANObjectStash {
	/** Possible values for getLastError() */
	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error

	/**
	 * Fetch the value of a key from the stash
	 *
	 * @param string $key Cache key
	 * @return mixed Returns false on failure
	 */
	abstract public function get( $key );

	/**
	 * Set the value of a key from stash
	 *
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @return bool Success
	 */
	abstract public function set( $key, $value, $ttl = 0 );

	/**
	 * Delete the value of a key from stash
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	abstract public function delete( $key );

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 */
	abstract public function getLastError();

	/**
	 * Clear the "last error" registry
	 */
	abstract public function clearLastError();
}
