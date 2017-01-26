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
	 * Retrieves stored tags from cache, on miss set it based on given callback
	 *
	 * @param $callBack callable
	 * @since 1.29
	 */
	public static function getStoredFromCache( callable $callBack ) {
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
	 * Retrieves registered tags from cache, on miss set it based on given callback
	 *
	 * @param $callBack callable
	 * @since 1.29
	 */
	public static function getRegisteredFromCache( callable $callBack ) {
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
	 * Retrieves tag hitcounts from cache, on miss set it based on given callback
	 *
	 * @param $callBack callable
	 * @since 1.29
	 */
	public static function getStatsFromCache( callable $callBack ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'changetags', 'tag-stats' );
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
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'tag-stats' ) );
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
		$cache->touchCheckKey( $cache->makeKey( 'changetags', 'tag-stats' ) );
	}

}
