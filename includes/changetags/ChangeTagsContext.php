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

/**
 * Retrieves definitions and statistics on change tags from the valid_tag and
 * change_tag tables, the site configuration and from extensions
 * @since 1.27
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
	 * @var Config
	 */
	private $config;

	/**
	 * @param Config $config
	 * @since 1.27
	 */
	public function __construct( Config $config = null ) {
		// Use provided config, or get main config
		if ( $config === null ) {
			$this->config = RequestContext::getMain()->getConfig();
		} else {
			$this->config = $config;
		}
	}

	/**
	 * Gets stored tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getStored() {
		// Save in class if not already done
		if ( $this->storedTags === null ) {
			$this->storedTags = $this->storedTags();
		}
		return $this->storedTags;
	}

	/**
	 * Gets registered tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getRegistered() {
		// Save in class if not already done
		if ( $this->registeredTags === null ) {
			$this->registeredTags = $this->registeredTags();
		}
		return $this->registeredTags;
	}

	/**
	 * Gets tags defined in core mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getCoreDefined() {
		// No need to save in class (method is fast)
		if ( !$this->config->get( 'UseAutoTagging' ) ) {
			return array();
		}
		return $this->config->get( 'CoreTags' );
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getDefined() {
		// Save in class if not already done
		if ( $this->definedTags === null ) {
			$this->definedTags = array_merge(
				$this->getStored(),
				$this->getRegistered(),
				$this->getCoreDefined()
			);
		}
		return $this->definedTags;
	}

	/**
	 * Gets tag usage statistics with up to date hitcounts
	 *
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.27
	 */
	public function getStats() {
		// Save in class if not already done
		if ( $this->tagStats === null ) {
			$this->tagStats = $this->tagStats();
		}
		return $this->tagStats;
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 *
	 * @return Array of strings: tags => array of params
	 * @since 1.27
	 */
	private function storedTags() {
		$cacheDuration = $this->config->get( 'TagDefinitionCacheDuration' );

		$keyDB = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$fname = __METHOD__;
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE );
			$setOpts += Database::getCacheSetOptions( $dbr );
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
			$keyDB,
			$cacheDuration,
			$callBack,
			array(
				'checkKeys' => array( $keyDB ),
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			)
		);
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have been actively used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * The stats retrieved with the getStats() method are passed to the hook.
	 * This allows extensions to define their tags only if they have hits.
	 *
	 * The result is cached for 6 hours by default, so newly defined tags
	 * might not appear registered for that period, or recently undefined tags
	 * might still appear registered.
	 * If a tag should be updated quickly, this cache can be purged with the
	 * purgeRegisteredTagsCache function.
	 *
	 * @return Array of strings: tags => arrays of params
	 * @since 1.27
	 */
	private function registeredTags() {
		$cacheDuration = $this->config->get( 'TagDefinitionCacheDuration' );

		$keyHook = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) {
			$setOpts += Database::getCacheSetOptions( wfGetDB( DB_SLAVE ) );
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
			Hooks::run( 'ChangeTagsRegister', array( &$registeredTags, $this->getStats() ) );

			// Removing nulls inserted as keys
			unset( $registeredTags[''] );

			return $registeredTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyHook,
			$cacheDuration,
			$callBack,
			array(
				'checkKeys' => array( $keyHook ),
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			)
		);
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * Does not include tags defined somewhere but not applied
	 *
	 * The result is cached, and the cache is invalidated every time an
	 * operation on change_tag is performed unless $wgTagMaxHitcountUpdate
	 * is > 0. In that case, tags with a greater hitcount do not trigger
	 * a cache purge and therefore are not updated.
	 * The cache expires after 24 hours by default ($wgTagUsageCacheDuration).
	 *
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.27
	 */
	private function tagStats() {
		$cacheDuration = $this->config->get( 'TagUsageCacheDuration' );

		$keyReactive = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );
		$fname = __METHOD__;
		$callBack = function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE, 'vslow' );
			$setOpts += Database::getCacheSetOptions( $dbr );
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
			$cacheDuration,
			$callBack,
			array(
				'checkKeys' => array( $keyReactive ),
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			)
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
	 * @since 1.27
	 */
	public function getCachedStats() {
		$cacheDuration = $this->config->get( 'SecondaryTagUsageCacheDuration' );

		$keyStable = wfMemcKey( 'ChangeTags', 'tag-stats-stable' );
		$callBack = function () {
			return $this->tagStats();
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyStable,
			$cacheDuration,
			$callBack,
			array(
				'checkKeys' => array( $keyStable ),
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			)
		);
	}

	/**
	 * Clear caches after tags have been updated
	 * This should be called after writes on the change_tag table.
	 *
	 * @param array $tagsToAdd: tags that were added
	 * @param array $tagsToRemove: tags that were removed
	 *
	 * @return array Array of invalidated 'ChangeTags' wfMemc keys
	 * @since 1.27
	 */
	public static function clearCachesAfterUpdate( $tagsToAdd, $tagsToRemove ) {
		$config = RequestContext::getMain()->getConfig();
		$maxHitcount = $config->get( 'TagMaxHitcountUpdate' );
		$cache = ObjectCache::getMainWANInstance();
		$keyReactive = wfMemcKey( 'ChangeTags', 'tag-stats-reactive' );

		// Retrieve cached stats
		$stats = $cache->get( $keyReactive, $ttl );

		$updatedTags = array_merge( $tagsToAdd, $tagsToRemove );
		// If the reactive cache does not exist or is invalidated,
		// or one of the updated tags doesn't appear in it, we purge it
		// since it might be a newly defined tag applied for the first time.
		$doFullPurge = ( $ttl === null ) || ( $ttl < 0 );
		if ( !$doFullPurge ) {
			foreach ( $updatedTags as $tag ) {
				if ( !isset( $stats[$tag] ) ) {
					$doFullPurge = true;
					break;
				}
			}
		}
		if ( $doFullPurge ) {
			$cache->touchCheckKey( $keyReactive );
			// The stable cache is purged as well so that the new tag appears in drop down menus.
			$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-stable' ) );
			// We also purge the cache of extensions since they might not have purged it
			// and we don't want the tag to appear out of nowhere at Special:Tags.
			$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );

			return array( 'tag-stats-reactive', 'tag-stats-stable', 'valid-tags-hook' );
		}

		// In other cases, we purge the reactive cache unless all of the updated tags
		// have more hits than $wgTagMaxHitcountUpdate.
		$doBasicPurge = ( $maxHitcount == 0 );
		if ( !$doBasicPurge ) {
			foreach ( $updatedTags as $tag ) {
				if ( $stats[$tag] < $maxHitcount ) {
					$doBasicPurge = true;
					break;
				}
			}
		}
		if ( $doBasicPurge ) {
			$cache->touchCheckKey( $keyReactive );

			return array( 'tag-stats-reactive' );
		}
		return array();
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * This should be called after writes on the valid_tag table.
	 *
	 * @since 1.27
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
	 * @since 1.27
	 */
	public static function purgeRegisteredTagsCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
	}

	/**
	 * Invalidates the reactive cache of tag usage stats.
	 * This should be called when we really need the up to date stats (e.g. deletion).
	 *
	 * @since 1.27
	 */
	public static function purgeTagUsageCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-reactive' ) );
	}

	/**
	 * Invalidates all tags-related caches, including the stable stats cache.
	 * This should be called when deleting a tag.
	 *
	 * @since 1.27
	 */
	public static function purgeTagCacheAll() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-reactive' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-stable' ) );
	}
}
