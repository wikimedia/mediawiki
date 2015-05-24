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
 * @since 1.28
 */
class ChangeTagsContext {

	/**
	 * @var array Tags applied by users (stored in valid_tag table)
	 */
	protected $userTags = null;

	/**
	 * @var array Tags applied in core or by extensions
	 */
	protected $softwareTags = null;

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
	 * @since 1.28
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Gets stored tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getUserTags() {
		// Save in class if not already done
		if ( $this->userTags === null ) {
			$this->userTags = $this->fetchStored();
		}
		return $this->userTags;
	}

	/**
	 * Gets registered tags mapped to their params
	 *
	 * @warning Involves potentially expensive queries, indirectly calls self::fetchStats()
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getSoftwareTags() {
		// Save in class if not already done
		if ( $this->softwareTags === null ) {
			$coreTags = $this->config->get( 'CoreTags' );
			$this->softwareTags = array_merge( $coreTags, $this->fetchRegistered() );
		}
		return $this->softwareTags;
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getDefinedTags() {
		if ( $this->definedTags === null ) {
			$this->definedTags = array_merge( $this->getUserTags(), $this->getSoftwareTags() );
		}
		return $this->definedTags;
	}

	/**
	 * Gets tag usage statistics with up to date hitcounts
	 *
	 * @warning Involves potentially expensive queries
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.28
	 */
	public function getTagStats() {
		// Save in class if not already done
		if ( $this->tagStats === null ) {
			$this->tagStats = $this->fetchStats();
		}
		return $this->tagStats;
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 *
	 * @return Array of strings: tags => array of params
	 */
	private function fetchStored() {
		$keyDB = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$fname = __METHOD__;
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_REPLICA );
			$setOpts += Database::getCacheSetOptions( $dbr );
			$res = $dbr->select( 'valid_tag', [ 'vt_tag' ], [], $fname );

			// Stored tags are always assumed to be active.
			// We need this filled so that this array can be
			// seamlessly merged with getRegisteredTags() when we
			// want all defined tags (with their active status).
			$storedTags = [];
			foreach ( $res as $row ) {
				$storedTags[$row->vt_tag] = [ 'active' => true ];
			}

			// Removing nulls inserted as keys
			unset( $storedTags[''] );

			return $storedTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyDB,
			WANObjectCache::TTL_WEEK,
			$callBack,
			[
				'checkKeys' => [ $keyDB ],
				'lockTSE' => WANObjectCache::TTL_WEEK,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have already been used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * The stats retrieved with the getTagStats() method are passed to the hook.
	 * This allows extensions to define their tags only if they have hits.
	 *
	 * The result is cached for 1 hour, so newly defined tags might not appear registered
	 * for that period, or recently undefined tags might still appear registered.
	 * If a tag should be updated quickly, this cache can be purged with the
	 * purgeRegisteredTagsCache function.
	 *
	 * @return Array of strings: tags => arrays of params
	 */
	private function fetchRegistered() {
		if ( !Hooks::isRegistered( 'ChangeTagsRegister' ) ) {
			return [];
		}
		$keyHook = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) {
			$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );
			// Hack for ListDefinedTags hook until deprecated
			$extensionDefined = [];
			Hooks::run( 'ListDefinedTags', [ &$extensionDefined ] );
			// Filling with param 'active' set to false
			$extensionDefined = array_fill_keys( $extensionDefined, [] );

			// Hack for ChangeTagsListActive hook until deprecated
			$extensionActive = [];
			Hooks::run( 'ChangeTagsListActive', [ &$extensionActive ] );
			// Filling with arrays with param 'active' set to true
			$extensionActive = array_fill_keys( $extensionActive, [ 'active' => true ] );

			// Merging, with ChangeTagsListActive overriding ListDefinedTags
			$registeredTags = array_merge( $extensionDefined, $extensionActive );

			// Applying the new hook, tags as keys and array of params as values
			// Pass $this so extensions can e.g. call getTagStats() if they want to define
			// only already-applied tags
			Hooks::run( 'ChangeTagsRegister', [ &$registeredTags, $this ] );

			// Removing nulls inserted as keys
			unset( $registeredTags[''] );

			return $registeredTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyHook,
			WANObjectCache::TTL_HOUR,
			$callBack,
			[
				'checkKeys' => [ $keyHook ],
				'lockTSE' => WANObjectCache::TTL_HOUR,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
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
	 * The cache expires after 24 hours.
	 *
	 * @return array Array of tags mapped to their hitcount
	 */
	private function fetchStats() {
		$keyStats = wfMemcKey( 'ChangeTags', 'tag-stats' );
		$fname = __METHOD__;
		$callBack = function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_REPLICA, 'vslow' );
			$setOpts += Database::getCacheSetOptions( $dbr );
			$res = $dbr->select(
				'change_tag',
				[ 'ct_tag', 'hitcount' => 'count(*)' ],
				[],
				$fname,
				[ 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' ]
			);

			$changeTags = [];
			foreach ( $res as $row ) {
				$changeTags[$row->ct_tag] = $row->hitcount;
			}

			// removing nulls inserted as keys
			unset( $changeTags[''] );

			return $changeTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyStats,
			WANObjectCache::TTL_DAY,
			$callBack,
			[
				'checkKeys' => [ $keyStats ],
				'lockTSE' => WANObjectCache::TTL_DAY,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount as of the
	 * latest caching.
	 * Does not include tags defined somewhere but not applied
	 *
	 * This cache is invalidated only for first hits of a tag.
	 * Updates may be delayed by up to 30 hours by default
	 * ($wgTagUsageCacheDuration + $wgSecondaryTagUsageCacheDuration).
	 *
	 * @param Config|null $config
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.27
	 */
	public static function cachedStats( Config $config = null ) {
		$keyCached = wfMemcKey( 'ChangeTags', 'tag-stats-cached' );
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) use ( $config ) {
			$changeTagsContext = new self( $config );
			return $changeTagsContext->fetchStats();
		};

		$expiry = WANObjectCache::TTL_MINUTE * $config->get( 'SecondaryTagUsageCacheDuration' );
		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyCached,
			$expiry,
			$callBack,
			[
				'checkKeys' => [ $keyCached ],
				'lockTSE' => $expiry,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
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
		$keyStats = wfMemcKey( 'ChangeTags', 'tag-stats' );

		// Retrieve cached stats
		$stats = $cache->get( $keyStats, $ttl );

		$updatedTags = array_merge( $tagsToAdd, $tagsToRemove );
		// If the cache does not exist or is invalidated,
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
			$cache->touchCheckKey( $keyStats );
			// The "cached" stats cache is purged as well so that the new tag appears in
			// drop down menus.
			$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-cached' ) );
			// We also purge the cache of extensions since they might not have purged it
			// and we don't want the tag to appear out of nowhere at Special:Tags.
			$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );

			return [ 'tag-stats', 'tag-stats-cached', 'valid-tags-hook' ];
		}

		// In other cases, we purge the cache unless all of the updated tags
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
			$cache->touchCheckKey( $keyStats );

			return [ 'tag-stats' ];
		}
		return [];
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * This should be called after writes on the valid_tag table.
	 *
	 * @since 1.28
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
	 * @since 1.28
	 */
	public static function purgeRegisteredTagsCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
	}

	/**
	 * Invalidates the cache of tag usage stats.
	 * This should be called when we really need the up to date stats (e.g. deletion).
	 *
	 * @since 1.28
	 */
	public static function purgeTagUsageCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stat' ) );
	}

	/**
	 * Invalidates all tags-related caches.
	 * This should be called when deleting a tag.
	 *
	 * @since 1.28
	 */
	public static function purgeTagCacheAll() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats' ) );
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats-cached' ) );
	}

	/**
	 * Is it OK to allow the user to adds and remove the given tags tags to/from a
	 * change?
	 *
	 * @param array $tagsToAdd Tags that you are interested in adding
	 * @param array $tagsToRemove Tags that you are interested in removing
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.28
	 */
	public function canUpdateTags( array $tagsToAdd, array $tagsToRemove,
		User $user = null ) {

		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'changetags' ) ) {
				return Status::newFatal( 'tags-update-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-update-blocked' );
			}
		}

		if ( $tagsToAdd ) {
			// to be added, a tag has to be stored in valid_tag
			// @todo Allow extensions to define tags that can be applied by users...
			$storedTags = $this->getUserTags();
			$disallowedTags = [];
			foreach ( $tagsToRemove as $tag ) {
				if ( !isset( $storedTags[$tag] ) ) {
					$disallowedTags[] = $tag;
				}
			}
			if ( $disallowedTags ) {
				return ChangeTags::restrictedTagError( 'tags-update-add-not-allowed-one',
					'tags-update-add-not-allowed-multi', $disallowedTags );
			}
		}

		if ( $tagsToRemove ) {
			// to be removed, a tag must not be registered by extensions
			$registeredTags = $this->getSoftwareTags();
			$disallowedTags = [];
			foreach ( $tagsToRemove as $tag ) {
				if ( isset( $registeredTags[$tag] ) ) {
					$disallowedTags[] = $tag;
				}
			}
			if ( $disallowedTags ) {
				return ChangeTags::restrictedTagError( 'tags-update-remove-not-allowed-one',
					'tags-update-remove-not-allowed-multi', $disallowedTags );
			}
		}

		return Status::newGood();
	}
}
