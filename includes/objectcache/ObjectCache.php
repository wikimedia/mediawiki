<?php
/**
 * Functions to get cache objects.
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
 * Utility class with static methods for accessing the default ObjectCacheManager.
 *
 * @deprecated since 1.27. Inject an ObjectCacheManager instead.
 *
 * @ingroup Cache
 */
class ObjectCache {

	/**
	 * @return ObjectCacheManager
	 */
	private static function getManager() {
		return \MediaWiki\MediaWikiServices::getInstance()->getObjectCacheManager();
	}

	/**
	 * @see ObjectCacheManager::getInstance()
	 * @param string $id
	 * @return BagOStuff
	 */
	public static function getInstance( $id ) {
		return self::getManager()->getInstance( $id );
	}

	/**
	 * @see ObjectCacheManager::getWANInstance()
	 * @since 1.26
	 * @param string $id A key in $wgWANObjectCaches.
	 * @return WANObjectCache
	 */
	public static function getWANInstance( $id ) {
		return self::getManager()->getWANInstance( $id );
	}

	/**
	 * @see ObjectCacheManager::newAnything()
	 * @param array $params
	 * @return BagOStuff
	 */
	public static function newAnything( $params ) {
		return self::getManager()->newAnything( $params );
	}

	/**
	 * @see ObjectCacheManager::getLocalServerInstance()
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws MWException
	 * @since 1.27
	 */
	public static function getLocalServerInstance( $fallback = CACHE_NONE ) {
		return self::getManager()->getLocalServerInstance( $fallback );
	}

	/**
	 * @param array $params [optional] Array key 'fallback' for $fallback.
	 * @param int|string $fallback Fallback cache, e.g. (CACHE_NONE, "hash") (since 1.24)
	 * @return BagOStuff
	 * @deprecated 1.27
	 */
	public static function newAccelerator( $params = [], $fallback = null ) {
		if ( $fallback === null ) {
			if ( is_array( $params ) && isset( $params['fallback'] ) ) {
				$fallback = $params['fallback'];
			} elseif ( !is_array( $params ) ) {
				$fallback = $params;
			}
		}

		return self::getLocalServerInstance( $fallback );
	}

	/**
	 * @see ObjectCacheManager::getLocalClusterInstance()
	 * @since 1.27
	 * @return BagOStuff
	 */
	public static function getLocalClusterInstance() {
		return self::getManager()->getLocalClusterInstance();
	}

	/**
	 * @see ObjectCacheManager::getMainWANInstance()
	 * @since 1.26
	 * @return WANObjectCache
	 */
	public static function getMainWANInstance() {
		return self::getManager()->getMainWANInstance();
	}

	/**
	 * @see ObjectCacheManager::getMainStashInstance()
	 * @return BagOStuff
	 * @since 1.26
	 */
	public static function getMainStashInstance() {
		return self::getManager()->getMainStashInstance();
	}

	/**
	 * @see ObjectCacheManager::clear()
	 * Clear all the cached instances.
	 */
	public static function clear() {
		self::getManager()->clear();
	}
}
