<?php
/**
 * Cache of various elements in a single cache entry.
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
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Interface for all classes implementing CacheHelper functionality.
 *
 * @since 1.20
 */
interface ICacheHelper {
	/**
	 * Sets if the cache should be enabled or not.
	 *
	 * @since 1.20
	 * @param bool $cacheEnabled
	 */
	public function setCacheEnabled( $cacheEnabled );

	/**
	 * Initializes the caching.
	 * Should be called before the first time anything is added via addCachedHTML.
	 *
	 * @since 1.20
	 *
	 * @param int|null $cacheExpiry Sets the cache expiry, either ttl in seconds or unix timestamp.
	 * @param bool|null $cacheEnabled Sets if the cache should be enabled or not.
	 */
	public function startCache( $cacheExpiry = null, $cacheEnabled = null );

	/**
	 * Get a cached value if available or compute it if not and then cache it if possible.
	 * The provided $computeFunction is only called when the computation needs to happen
	 * and should return a result value. $args are arguments that will be passed to the
	 * compute function when called.
	 *
	 * @since 1.20
	 *
	 * @param callable $computeFunction
	 * @param array|mixed $args
	 * @param string|null $key
	 *
	 * @return mixed
	 */
	public function getCachedValue( $computeFunction, $args = [], $key = null );

	/**
	 * Saves the HTML to the cache in case it got recomputed.
	 * Should be called after the last time anything is added via addCachedHTML.
	 *
	 * @since 1.20
	 */
	public function saveCache();

	/**
	 * Sets the time to live for the cache, in seconds or a unix timestamp
	 * indicating the point of expiry...
	 *
	 * @since 1.20
	 *
	 * @param int $cacheExpiry
	 */
	public function setExpiry( $cacheExpiry );
}
