<?php
/**
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
 * @ingroup Change tagging
 */

use MediaWiki\MediaWikiServices;

/**
 * Handles caches for change tags
 * @since 1.29
 */
class ChangeTagsCache {

	/**
	 * Retrieves stored tags from cache, if absent set it based on given callback
	 *
	 * @param $callBack Callable
	 * @since 1.29
	 */
	public static function getStoredFromCache( Callable $callBack ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'valid-tags-db' );
		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			WANObjectCache::TTL_WEEK,
			$callBack,
			[
				'checkKeys' => [ $key ],
				'lockTSE' => WANObjectCache::TTL_WEEK,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * This should be called after writes on the valid_tag table.
	 *
	 * @since 1.29
	 */
	public static function purgeStoredTagsCache() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'valid-tags-db' ) );
	}

	/**
	 * Retrieves registered tags from cache, if absent set it based on given callback
	 *
	 * @param $callBack Callable
	 * @since 1.29
	 */
	public static function getRegisteredFromCache( Callable $callBack ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'valid-tags-hook' );
		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			WANObjectCache::TTL_WEEK,
			$callBack,
			[
				'checkKeys' => [ $key ],
				'lockTSE' => WANObjectCache::TTL_WEEK,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Invalidates the cache of tags registered by extensions.
	 * This should be called after extensions register or unregister tags,
	 * or tweak their params, when the usual delay of 24 hours is excessive
	 * for end users.
	 * Note that the cache is invalidated when checking permissions for tag
	 * management operations, so doing so only for this purpose is unnecessary.
	 *
	 * @since 1.29
	 */
	public static function purgeRegisteredTagsCache() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'valid-tags-hook' ) );
	}

	/**
	 * Retrieves registered tags from cache, if absent set it based on given callback
	 *
	 * @param $callBack Callable
	 * @since 1.29
	 */
	public static function getUsedFromCache( Callable $callBack ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'used-tags' );
		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			WANObjectCache::TTL_WEEK,
			$callBack,
			[
				'checkKeys' => [ $key ],
				'lockTSE' => WANObjectCache::TTL_WEEK,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Invalidates the cache of used tags.
	 * This should be called when we really need the up to date stats (e.g. deletion).
	 *
	 * @since 1.29
	 */
	public static function purgeTagUsageCache() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'used-tags' ) );
	}

	/**
	 * Invalidates the secondary tag statistics cache only, if
	 * added tags are not present in the cache or removed tags are present in the cache
	 * @param array $addedTags
	 * @param array $removedTags
	 * @since 1.29
	 */
	public static function purgeTagUsageCacheOnUpdate( $addedTags, $removedTags ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'used-tags' );
		$cachedTags = $cache->get( $key );
		$usageCachePurged = false;
		foreach ( $addedTags as $tag ) {
			self::purgeHitcountCache( $tag );
			if ( !$usageCachePurged && !isset( $cachedTags[$tag] ) ) {
				$usageCachePurged = true;
				self::purgeTagUsageCache();
			}
		}
		foreach ( $removedTags as $tag ) {
			self::purgeHitcountCache( $tag );
			if ( !$usageCachePurged && isset( $cachedTags[$tag] ) ) {
				$usageCachePurged = true;
				self::purgeTagUsageCache();
			}
		}
	}

	/**
	 * Retrieves estimated hitcount from cache, if absent set it based on given callback
	 *
	 * @param $tag String
	 * @param $callBack Callable
	 * @since 1.29
	 */
	public static function getHitcountFromCache( $tag, Callable $callBack ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'hitcount', $tag );
		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			WANObjectCache::TTL_WEEK,
			$callBack,
			[
				'checkKeys' => [ $key ],
				'lockTSE' => WANObjectCache::TTL_WEEK,
				'pcTTL' => WANObjectCache::TTL_PROC_SHORT
			]
		);
	}

	/**
	 * Invalidates all tags-related caches.
	 * This should be called when deleting a tag.
	 *
	 * @param $tag String
	 * @since 1.29
	 */
	public static function purgeHitcountCache( $tag ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'hitcount', $tag ) );
	}

	/**
	 * Invalidates all tags-related caches.
	 * This should be called when deleting a tag.
	 *
	 * @since 1.29
	 */
	public static function purgeTagCacheAll() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'valid-tags-db' ) );
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'valid-tags-hook' ) );
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'used-tags' ) );
	}

}
