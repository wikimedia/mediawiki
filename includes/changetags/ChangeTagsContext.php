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
 * change_tag tables and from extensions
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
			$this->storedTags = $this->fetchStored();
		}
		return $this->storedTags;
	}

	/**
	 * Gets registered tags mapped to their params
	 *
	 * @warning Involves potentially expensive queries, indirectly calls self::fetchStats()
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getRegistered() {
		// Save in class if not already done
		if ( $this->registeredTags === null ) {
			$this->registeredTags = $this->fetchRegistered();
		}
		return $this->registeredTags;
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.27
	 */
	public function getDefined() {
		if ( $this->definedTags === null ) {
			$this->definedTags = array_merge( $this->getStored(), $this->getRegistered() );
		}
		return $this->definedTags;
	}

	/**
	 * Gets tag usage statistics with up to date hitcounts
	 *
	 * @warning Involves potentially expensive queries
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.27
	 */
	public function getStats() {
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
		$cacheDuration = $this->config->get( 'TagDefinitionCacheDuration' );

		$keyDB = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$fname = __METHOD__;
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE );
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
			$cacheDuration,
			$callBack,
			[
				'checkKeys' => [ $keyDB ],
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			]
		);
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have already been used in the past and are likely to be
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
	 */
	private function fetchRegistered() {
		$cacheDuration = $this->config->get( 'TagDefinitionCacheDuration' );

		$keyHook = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) {
			$setOpts += Database::getCacheSetOptions( wfGetDB( DB_SLAVE ) );
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
			// Pass $this so extensions can e.g. call getStats() if they want to define
			// only already-applied tags
			Hooks::run( 'ChangeTagsRegister', [ &$registeredTags, $this ] );

			// Removing nulls inserted as keys
			unset( $registeredTags[''] );

			return $registeredTags;
		};

		return ObjectCache::getMainWANInstance()->getWithSetCallback(
			$keyHook,
			$cacheDuration,
			$callBack,
			[
				'checkKeys' => [ $keyHook ],
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			]
		);
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * Does not include tags defined somewhere but not applied
	 *
	 * The result is cached, and the cache is invalidated every time an
	 * operation on change_tag is performed.
	 * The cache expires after 24 hours by default ($wgTagUsageCacheDuration).
	 *
	 * @return array Array of tags mapped to their hitcount
	 */
	private function fetchStats() {
		$cacheDuration = $this->config->get( 'TagUsageCacheDuration' );

		$key = wfMemcKey( 'ChangeTags', 'tag-stats' );
		$fname = __METHOD__;
		$callBack = function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_SLAVE, 'vslow' );
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
			$key,
			$cacheDuration,
			$callBack,
			[
				'checkKeys' => [ $key ],
				'lockTSE' => $cacheDuration,
				'pcTTL' => 30
			]
		);
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
	 * Invalidates the cache of tag usage stats.
	 * This should be called when we really need the up to date stats (e.g. deletion).
	 *
	 * @since 1.27
	 */
	public static function purgeTagUsageCache() {
		$cache = ObjectCache::getMainWANInstance();
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats' ) );
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
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats' ) );
	}
}
