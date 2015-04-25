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
 */

class ChangeTagsContext {

	/**
	 * Hitcount caches are set to expire after a week
	 */
	const HITCOUNT_CACHE_DURATION = 604800;

	/**
	 * @var array Array mapping tags stored in change_tag table
	 * to their params
	 */
	protected $storedTags = null;

	/**
	 * @var array Array mapping tags registered by extensions
	 * to their params
	 */
	protected $registeredTags = null;

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
	 * @return bool Indicates whether stored tags were retrieved
	 * @since 1.26
	 */
	public function storedTagsAreSet() {
		return ( $this->storedTags !== null );
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
	 * @return bool Indicates whether registered tags were retrieved
	 * @since 1.26
	 */
	public function registeredTagsAreSet() {
		return ( $this->registeredTags !== null );
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public function getDefined() {
		global $wgCoreTags;
		return array_merge( $this->getStored(), $this->getRegistered(), $wgCoreTags );
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
			$this->tagStats = self::tagStats( true );
		}
		return $this->tagStats;
	}

	/**
	 * @return bool Indicates whether tag stats were retrieved
	 * @since 1.26
	 */
	public function tagStatsAreSet() {
		return ( $this->tagStats !== null );
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 * The keys are the tag names and the values are arrays of params.
	 * For now, stored tags only have an 'active' param, always set to true.
	 *
	 * Tries memcache first.
	 *
	 * @return Array of strings: tags => array of params
	 * @since 1.26
	 */
	public static function storedTags() {
		global $wgMemc;

		// Try memcache...
		$key = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$storedTags = $wgMemc->get( $key );

		// If not in memcache, db query
		if ( $storedTags === false ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'valid_tag', array( 'vt_tag'),
				array(), __METHOD__ );

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

			// Caching for a long time (a week), since
			// operations on valid_tag clear this cache
			$wgMemc->set( $key, $storedTags, 60*60*24*7 );
		}

		return $storedTags;
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have been actively used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * Tries memcache first.
	 *
	 * @return Array of strings: tags => arrays of params
	 * @since 1.26
	 */
	public static function registeredTags() {
		global $wgMemc, $wgTagUsageCacheDuration;

		// Try memcache...
		$key = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$registeredTags = $wgMemc->get( $key );

		// If not in memcache, ask extensions
		if ( $registeredTags === false ) {
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

			// Caching for a moderate duration (24 hours by default)
			// No longer than a day since extensions may register or unregister
			// tags without clearing the cache (which should be done with the
			// purgeRegisteredTagsCache function). (AbuseFilter does it.)
			$duration = min( $wgTagUsageCacheDuration, 60*60*24 );
			$wgMemc->set( $key, $registeredTags, $duration );
		}

		return $registeredTags;
	}

	/**
	 * Returns all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public static function definedTags() {
		global $wgCoreTags;
		return array_merge( self::storedTags(), self::registeredTags(), $wgCoreTags );
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount as of the
	 * latest caching. The ordering may on rare occasions be incorrect
	 * since hitcounts are updated when tags are applied.
	 * Does not include tags defined somewhere but not applied
	 *
	 * @param bool $upToDateHitcounts whether to fetch the most up to date hitcounts
	 * or only hitcounts of an older cache (delay of up to 24 hours)
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.26
	 */
	public static function tagStats( $upToDateHitcounts = true ) {
		global $wgMemc, $wgTagUsageCacheDuration;

		// Try to retrieve cached array mapping tags to their hitcount
		$commonKey = wfMemcKey( 'ChangeTags', 'tag-usage-stats' );
		$changeTags = $wgMemc->get( $commonKey );

		// If the common cache exists and we want up to date hitcounts, get them
		if ( $changeTags !== false && $upToDateHitcounts ) {
			foreach ( $changeTags as $tag => $hitcount ) {
				$hitcountKey = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
				// getting most up to date hitcount from specific hitcount cache
				$hitcount = $wgMemc->get( $hitcountKey );
				if ( $hitcount !== false ) {
					// Mapping tags to their hitcount
					$changeTags[$tag] = $hitcount;
				} else {
					// Cache attempt failed
					// (normally, this should not happen)
					// So do DB query
					$changeTags = false;
					break;
				}
			}
		}

		// Database query and cache rebuild
		if ( $changeTags === false ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'change_tag',
				array( 'ct_tag', 'hitcount' => 'count(*)' ),
				array(),
				__METHOD__,
				array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' )
			);

			$changeTags = array();
			foreach ( $res as $row ) {
				$tag = $row->ct_tag;
				$hitcount = $row->hitcount;
				// Mapping tags to their hitcount
				$changeTags[$tag] = $hitcount;
				// Caching for a week (gets incremented when applied, decremented when unapplied)
				$hitcountKey = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
				$wgMemc->set( $hitcountKey, $hitcount, HITCOUNT_CACHE_DURATION );
			}

			// removing nulls inserted as keys
			unset( $changeTags[''] );

			// Caching for a moderate duration (24 hours by default)
			// No longer than a week to avoid outlasting hitcount caches
			$duration = min( $wgTagUsageCacheDuration, HITCOUNT_CACHE_DURATION );
			$wgMemc->set( $commonKey, $changeTags, $duration );
		}

		return $changeTags;
	}

	/**
	 * Increments hitcount cache of given tag
	 *
	 * @param string $tag: tag to increment the hitcount cache of
	 * @since 1.26
	 */
	public static function incrementHitcountCache( $tag ) {
		global $wgMemc;
		$key = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
		$hitcount = $wgMemc->get( $key );
		if ( $hitcount !== false ) {
			// Increment hitcount cache
			$wgMemc->set( $key, $hitcount + 1, HITCOUNT_CACHE_DURATION );
		} else {
			// If the hitcount cache doesn't exist, we purge the tag usage cache
			// since it might be a newly defined tag applied for the first time
			self::purgeTagUsageCache();
			// Extensions might not have purged the cache
			self::purgeRegisteredTagsCache();
		}
	}

	/**
	 * Decrements hitcount cache of given tag
	 *
	 * @param string $tag: tag to decrement the hitcount cache of
	 * @since 1.26
	 */
	public static function decrementHitcountCache( $tag ) {
		global $wgMemc;
		$key = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
		$hitcount = $wgMemc->get( $key );
		if ( $hitcount !== false && $hitcount > 0 ) {
			// Decrement hitcount cache
			$wgMemc->set( $key, $hitcount - 1, HITCOUNT_CACHE_DURATION );
		} else {
			// If the hitcount cache doesn't exist, or hitcount is 0,
			// something is wrong, so we'd better purge the cache
			self::purgeTagUsageCache( $tag );
			// Extensions might not have purged the cache
			self::purgeRegisteredTagsCache();
		}
	}

	/**
	 * Initializes hitcount cache of given tag
	 * Use case : creating a user defined tag
	 *
	 * @param string $tag: tag to initialize the hitcount cache of
	 * @since 1.26
	 */
	public static function initializeHitcountCache( $tag ) {
		global $wgMemc;
		// delete now outdated caches
		self::purgeStoredTagsCache( $tag );
		self::purgeTagUsageCache();
		// set hitcount cache
		$wgMemc->set( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ),
			0, HITCOUNT_CACHE_DURATION );
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * Use case : activating or deactivating tags
	 *
	 * @since 1.26
	 */
	public static function purgeStoredTagsCache() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
	}

	/**
	 * Invalidates the cache of tags registered by extensions.
	 * Use case 1) alone : updating tag params
	 * Use case 2) in combination with purgeTagUsageCache :
	 * defining or undefining tags
	 *
	 * @since 1.26
	 */
	public static function purgeRegisteredTagsCache() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
	}

	/**
	 * Invalidates caches related to tag usage stats
	 * Use case : defining, or undefining a tag
	 * This should not be used when applying the tag, the hitcount cache should
	 * be incremented instead. (And when the tag gets removed, decremented.)
	 *
	 * @param string|false $tag: (optional) tag to clear the hitcount cache of
	 * @since 1.26
	 */
	public static function purgeTagUsageCache( $tag = false ) {
		global $wgMemc;
		// delete tag usage cache
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'tag-usage-stats' ) );
		if ( $tag ) {
			// delete hitcount cache
			$wgMemc->delete( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ) );
		}
	}

	/**
	 * Invalidates all tags-related caches.
	 * Use case : deleting a tag
	 *
	 * @param string|false $tag: (optional) tag to clear the hitcount cache of
	 * @since 1.26
	 */
	public static function purgeTagCacheAll( $tag = false ) {
		self::purgeStoredTagsCache();
		self::purgeRegisteredTagsCache();
		self::purgeTagUsageCache( $tag );
	}
}
