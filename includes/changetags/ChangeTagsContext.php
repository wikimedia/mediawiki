<?php
/**
 * Recent changes tagging.
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
 * @since 1.26
 */

class ChangeTagsContext {

	/**
	 * @var array Tags stored in valid_tag table
	 */
	protected $storedTags = null;

	/**
	 * @var array Tags registered by extensions
	 */
	protected $registeredTags = null;

	/**
	 * @var array All defined tags
	 */
	protected $definedTags = null;

	/**
	 * @var array Array mapping tags to their hitcount
	 */
	protected $tagStats = null;

	/**
	 * Gets stored tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public function getStored() {
		// Save in class if not already done
		if ( $this->storedTags === null ) {
			$this->storedTags = self::storedTags();
		}
		return $this->storedTags;
	}

	/**
	 * Gets registered tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public function getRegistered() {
		// Save in class if not already done
		if ( $this->registeredTags === null ) {
			$this->registeredTags = self::registeredTags();
		}
		return $this->registeredTags;
	}

	/**
	 * Gets core tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public function getCore() {
		return self::coreTags();
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public function getDefined() {
		// Save in class if not already done
		if ( $this->definedTags === null ) {
			$this->definedTags = array_merge( $this->getStored(),
				$this->getRegistered(), $this->getCore() );
		}
		return $this->definedTags;
	}

	/**
	 * Gets tag usage statistics with up to date hitcounts
	 *
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.26
	 */
	public function getStats() {
		// Save in class if not already done
		if ( $this->tagStats === null ) {
			$this->tagStats = self::tagStats();
		}
		return $this->tagStats;
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 *
	 * @return Array of strings: tags => array of params
	 * @since 1.26
	 */
	public static function storedTags() {
		global $wgTagDefinitionCacheDuration;

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$fname = __METHOD__;
		$callBack = function() use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'valid_tag', array( 'vt_tag' ),
				array(), $fname );

			// Stored tags are always assumed to be active.
			// We need this filled so that this array can be
			// seamlessly merged with getRegisteredTags() when we
			// want all defined tags (with their active status).
			$storedTags = array();
			foreach ( $res as $row ) {
				$storedTags[$row->vt_tag] = array(
					'active' => true
				);
			}

			// Removing nulls inserted as keys
			unset( $storedTags[''] );

			return $storedTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			$callBack,
			$wgTagDefinitionCacheDuration,
			array( $key ),
			array( 'lockTSE' => INF )
		);
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have been actively used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * The result is cached for 24 hours by default, so newly defined tags
	 * might not appear registered for that period, or recently undefined tags
	 * might still appear registered.
	 * If a tag should be updated quickly, this cache can be purged with the
	 * purgeRegisteredTagsCache function.
	 *
	 * @return Array of strings: tags => arrays of params
	 * @since 1.26
	 */
	public static function registeredTags() {
		global $wgTagDefinitionCacheDuration;

		$key = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$callBack = function() {
			// Hack for ListDefinedTags hook until deprecated
			$extensionDefined = array();
			Hooks::run( 'ListDefinedTags', array( &$extensionDefined ) );
			// Filling with param 'active' set to false
			$extensionDefined = array_fill_keys( $extensionDefined,
				array() );

			// Hack for ChangeTagsListActive hook until deprecated
			$extensionActive = array();
			Hooks::run( 'ChangeTagsListActive', array( &$extensionActive ) );
			// Filling with arrays with param 'active' set to true
			$extensionActive = array_fill_keys( $extensionActive,
				array( 'active' => true ) );

			// Merging, with ChangeTagsListActive overriding ListDefinedTags
			$registeredTags = array_merge( $extensionDefined, $extensionActive );

			// Applying the new hook, tags as keys and array of params as values
			Hooks::run( 'ChangeTagsRegister', array( &$registeredTags ) );

			// Removing nulls inserted as keys
			unset( $registeredTags[''] );

			return $registeredTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$key,
			$callBack,
			$wgTagDefinitionCacheDuration,
			array( $key ),
			array( 'lockTSE' => INF )
		);
	}

	/**
	 * Returns all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public static function coreTags() {
		global $wgCoreTags, $wgUseAutoTagging;
		if ( !$wgUseAutoTagging ) {
			return array();
		}
		return $wgCoreTags;
	}

	/**
	 * Returns all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public static function definedTags() {
		$context = new ChangeTagsContext;
		return $context->getDefined();
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * Does not include tags defined somewhere but not applied
	 *
	 * The result is cached, and the cache is invalidated every time an
	 * operation on change_tag is performed unless $wgMaxTagHitcountUpdate
	 * is > 0. In that case, tags with a greater hitcount do not trigger
	 * a cache purge and therefore are not updated.
	 * The cache expires after 24 hours by default ($wgTagUsageCacheDuration).
	 *
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.26
	 */
	public static function tagStats() {
		global $wgTagUsageCacheDuration;

		$keyReactive = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );
		$fname = __METHOD__;
		$callBack = function () use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE, 'vslow' );
			$res = $dbr->select(
				'change_tag',
				array( 'ct_tag', 'hitcount' => 'count(*)' ),
				array(),
				$fname,
				array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' )
			);

			$changeTags = array();
			foreach ( $res as $row ) {
				$changeTags[$row->ct_tag] = $row->hitcount;
			}

			// removing nulls inserted as keys
			unset( $changeTags[''] );

			return $changeTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyReactive,
			$callBack,
			$wgTagUsageCacheDuration,
			array( $keyReactive, $keyStable ),
			array( 'lockTSE' => INF )
		);
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount as of the
	 * latest caching.
	 * Does not include tags defined somewhere but not applied
	 *
	 * This cache is invalidated only for first hits of a tag.
	 * Updates may be delayed by up to 48 hours by default
	 * (twice $wgTagUsageCacheDuration).
	 *
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.26
	 */
	public static function cachedTagStats() {
		global $wgTagUsageCacheDuration;
		$keyStable = wfMemcKey( 'ChangeTags', 'tag-stats-stable' );

		$callBack = function () {
			return self::tagStats();
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyStable,
			$callBack,
			$wgTagUsageCacheDuration,
			array( $keyStable ),
			array( 'lockTSE' => INF )
		);
	}

	/**
	 * Clear caches after tags have been updated
	 * This should be called after writes on the change_tag table.
	 *
	 * @param array $tagsToAdd: tags that were added
	 * @param array $tagsToRemove: tags that were removed
	 * @since 1.26
	 */
	public static function clearCachesAfterUpdate( $tagsToAdd, $tagsToRemove ) {
		global $wgTagMaxHitcountUpdate;
		$cache = ObjectCache::getMainWANInstance();
		$key = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );

		// Retrieve cached stats
		$stats = $cache->get( $key, $ttl );

		// We do the basic purge of the reactive cache unless all of the added tags
		// have more hits than $wgTagMaxHitcountUpdate.
		$doBasicPurge = true;
		// If the reactive cache was no longer valid, or one of the added tags doesn't
		// appear in it, we purge the stable cache too since it might be a newly
		// defined tag applied for the first time.
		// We also purge the cache of extensions since they might not have purged it
		// and we don't want the tag to appear out of nowhere at Special:Tags.
		$doExtraPurge = ( $ttl === null ) || ( $ttl < 0 );
		if ( count( $tagsToAdd ) && ( $wgTagMaxHitcountUpdate > 0 || !$doExtraPurge ) ) {
			foreach ( $tagsToAdd as $tag ) {
				if ( !isset( $stats[$tag] ) ) {
					$doBasicPurge = true;
					$doExtraPurge = true;
					break;
				} elseif ( $stats[$tag] > $wgTagMaxHitcountUpdate ) {
					$doBasicPurge = false;
				} else {
					$doBasicPurge = true;
				}
			}
		}
		if ( $doBasicPurge ) {
			$cache->touchCheckKey( $key );
			if ( $doExtraPurge ) {
				$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-stable' ) );
				$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
			}
		}
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * This should be called after writes on the valid_tag table.
	 *
	 * @since 1.26
	 */
	public static function purgeStoredTagsCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
	}

	/**
	 * Invalidates the cache of tags registered by extensions.
	 * This should be called after extensions register or unregister tags,
	 * or tweak their params, when the usual delay of 24 hours is excessive
	 * for end users.
	 * Note that the cache is invalidated when checking permissions for tag
	 * management operations, so doing so only for this purpose is unnecessary.
	 *
	 * @since 1.26
	 */
	public static function purgeRegisteredTagsCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
	}

	/**
	 * Invalidates the reactive cache of tag usage stats.
	 * This should be called when we really need the up to date stats (e.g. deletion).
	 *
	 * @since 1.26
	 */
	public static function purgeTagUsageCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-reactive' ) );
	}

	/**
	 * Invalidates all tags-related caches, including the stable stats cache.
	 * This should be called when deleting a tag.
	 *
	 * @since 1.26
	 */
	public static function purgeTagCacheAll() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-reactive' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-stable' ) );
	}
}
