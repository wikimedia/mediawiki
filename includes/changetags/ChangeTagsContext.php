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
 * A ChangeTag object can be created from any instance of a class implementing this interface.
 * @since 1.28
 */
interface IChangeTagsContextSource {

	/**
	 * Get user-defined tags (defined at Special:Tags by tag managers)
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getUserTags();

	/**
	 * Get software-defined tags (includes tags defined in core and by extensions)
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getSoftwareTags();

	/**
	 * Get all defined tags, both user-defined and software-defined
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.28
	 */
	public function getDefinedTags();

	/**
	 * Get hitcount for each tag
	 *
	 * @return array Array of strings: tags mapped to hitcounts
	 * @since 1.28
	 */
	public function getTagStats();
}

/**
 * Implements IChangeTagsContextSource and allows to manipulate change tags
 * @since 1.28
 */
class ChangeTagsContext implements IChangeTagsContextSource {

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
	 * These tags are defined by users with managechangetags permission and applied
	 * by users.
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
	 * Gets software-defined tags mapped to their params
	 * These tags are defined and applied from core or by extensions.
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
	 * operation on change_tag is performed.
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
		$cache->touchCheckKey( wfMemcKey( 'ChangeTags', 'tag-stats' ) );
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
	}

	/**
	 * Activates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canActivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function activateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// purging cache for the sake of extensions that might not do it
		self::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this );

		// are we allowed to do this?
		$result = $changeTag->canActivate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			[ 'vt_tag' ],
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// clear the memcache of stored tags
		self::purgeStoredTagsCache();

		// log it
		$logId = ChangeTags::logTagManagementAction( 'activate', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Deactivates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canDeactivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function deactivateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// purging cache for the sake of extensions that might not do it
		self::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this );

		// are we allowed to do this?
		$result = $changeTag->canDeactivate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag', [ 'vt_tag' => $tag ], __METHOD__ );

		// clear the memcache of stored tags
		self::purgeStoredTagsCache();

		// log it
		$logId = ChangeTags::logTagManagementAction( 'deactivate', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Creates a tag by adding a row to the `valid_tag` table.
	 *
	 * Includes a call to ChangeTag::canCreate(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function createTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// purging cache for the sake of extensions that might not do it
		self::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this );

		// are we allowed to do this?
		$result = $changeTag->canCreate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			[ 'vt_tag' ],
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// purge stored tags cache
		self::purgeStoredTagsCache();

		// log it
		$logId = ChangeTags::logTagManagementAction( 'create', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Deletes a tag, checking whether it is allowed first, and adding a log entry
	 * afterwards.
	 *
	 * Includes a call to ChangeTag::canDelete(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function deleteTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// purging cache for the sake of extensions that might not do it
		self::purgeRegisteredTagsCache();
		// purging stats cache to get the up to date hitcount
		self::purgeTagUsageCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this );
		$hitcount = $changeTag->getHitcount();

		// are we allowed to do this?
		$result = $changeTag->canDelete( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$deleteResult = ChangeTags::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = ChangeTags::logTagManagementAction( 'delete', $tag, $reason, $user, $hitcount );
		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Is it OK to allow the user to apply all the specified tags at the same time
	 * as they edit/make the change?
	 *
	 * @param array $tags Tags that you are interested in applying
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @param Language $lang Language of user interface
	 * @return Status
	 * @since 1.28
	 */
	public function canAddTagsAccompanyingChange( array $tags,
		User $user = null, Language $lang ) {

		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'applychangetags' ) ) {
				return Status::newFatal( 'tags-apply-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-apply-blocked' );
			}
		}

		// to be applied, a tag has to be stored in valid_tag
		// @todo Allow extensions to define tags that can be applied by users...
		$allowedTags = $this->getUserTags();
		$disallowedTags = [];
		foreach ( $tags as $tag ) {
			if ( !isset( $allowedTags[$tag] ) ) {
				$disallowedTags[] = $tag;
			}
		}
		if ( $disallowedTags ) {
			return self::restrictedTagError( 'tags-apply-not-allowed-one',
				'tags-apply-not-allowed-multi', $disallowedTags, $lang );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to adds and remove the given tags tags to/from a
	 * change?
	 *
	 * @param array $tagsToAdd Tags that you are interested in adding
	 * @param array $tagsToRemove Tags that you are interested in removing
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @param Language $lang Language of user interface
	 * @return Status
	 * @since 1.28
	 */
	public function canUpdateTags( array $tagsToAdd, array $tagsToRemove,
		User $user = null, Language $lang ) {

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
			foreach ( $tagsToAdd as $tag ) {
				if ( !isset( $storedTags[$tag] ) ) {
					$disallowedTags[] = $tag;
				}
			}
			if ( $disallowedTags ) {
				return self::restrictedTagError( 'tags-update-add-not-allowed-one',
					'tags-update-add-not-allowed-multi', $disallowedTags, $lang );
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
				return self::restrictedTagError( 'tags-update-remove-not-allowed-one',
					'tags-update-remove-not-allowed-multi', $disallowedTags, $lang );
			}
		}

		return Status::newGood();
	}

	/**
	 * Helper function to generate a fatal status with a 'not-allowed' type error.
	 *
	 * @param string $msgOne Message key to use in the case of one tag
	 * @param string $msgMulti Message key to use in the case of more than one tag
	 * @param array $tags Restricted tags (passed as $1 into the message, count of
	 * $tags passed as $2)
	 * @param Language $lang Language of user interface
	 * @return Status
	 * @since 1.28
	 */
	protected static function restrictedTagError( $msgOne, $msgMulti, $tags, $lang ) {
		$count = count( $tags );
		return Status::newFatal( ( $count > 1 ) ? $msgMulti : $msgOne,
			$lang->commaList( $tags ), $count );
	}

	/**
	 * Adds and/or removes tags to/from a given change, checking whether it is
	 * allowed first, and adding a log entry afterwards.
	 *
	 * Includes a call to ChangeTagsContext::canUpdateTags(), so your code doesn't need
	 * to do that. However, it doesn't check whether the *_id parameters are a
	 * valid combination. That is up to you to enforce. See ApiTag::execute() for
	 * an example.
	 *
	 * @param array|null $tagsToAdd If none, pass array() or null
	 * @param array|null $tagsToRemove If none, pass array() or null
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string $params Params to put in the ct_params field of table
	 * 'change_tag' when adding tags
	 * @param string $reason Comment for the log
	 * @param User $user Who to give credit for the action
	 * @param Language $lang Language of user interface
	 * @return Status If successful, the value of this Status object will be an
	 * object (stdClass) with the following fields:
	 *  - logId: the ID of the added log entry, or null if no log entry was added
	 *    (i.e. no operation was performed)
	 *  - addedTags: an array containing the tags that were actually added
	 *  - removedTags: an array containing the tags that were actually removed
	 * @since 1.28
	 */
	public function updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
		$rc_id, $rev_id, $log_id, $params, $reason, User $user, Language $lang ) {

		if ( is_null( $tagsToAdd ) ) {
			$tagsToAdd = [];
		}
		if ( is_null( $tagsToRemove ) ) {
			$tagsToRemove = [];
		}
		if ( !$tagsToAdd && !$tagsToRemove ) {
			// no-op, don't bother
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}

		// are we allowed to do this?
		$result = $this->canUpdateTags( $tagsToAdd, $tagsToRemove, $user, $lang );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// basic rate limiting
		if ( $user->pingLimiter( 'changetag' ) ) {
			return Status::newFatal( 'actionthrottledtext' );
		}

		// do it!
		list( $tagsAdded, $tagsRemoved, $initialTags ) = ChangeTags::updateTags( $tagsToAdd,
			$tagsToRemove, $rc_id, $rev_id, $log_id, $params, null, $user );
		if ( !$tagsAdded && !$tagsRemoved ) {
			// no-op, don't log it
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}
		// log it
		$logId = ChangeTags::logTagUpdateAction( $tagsAdded, $tagsRemoved, $initialTags,
			$rev_id, $log_id, $reason, $user );

		return Status::newGood( (object)[
			'logId' => $logId,
			'addedTags' => $tagsAdded,
			'removedTags' => $tagsRemoved,
		] );
	}

	/**
	 * Indicate whether change tag editing UI is relevant
	 *
	 * Returns true if the user has the necessary right and there are any
	 * editable tags defined.
	 *
	 * This intentionally doesn't check "any addable || any deletable", because
	 * it seems like it would be more confusing than useful if the checkboxes
	 * suddenly showed up because some abuse filter stopped defining a tag and
	 * then suddenly disappeared when someone deleted all uses of that tag.
	 *
	 * @param User $user
	 * @return bool
	 * @since 1.28
	 */
	public function showTagEditingUI( User $user ) {
		return $user->isAllowed( 'changetags' ) && $this->getUserTags();
	}
}
