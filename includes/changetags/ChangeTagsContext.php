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
 * @since 1.29
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
	protected $usedTags = null;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @param Config $config
	 * @since 1.29
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
	 * @since 1.29
	 */
	public function getUserTags() {
		// Save in class if not already done
		if ( $this->userTags === null ) {
			$this->userTags = self::fetchStored();
		}
		return $this->userTags;
	}

	/**
	 * Returns whether the tag is user-defined.
	 *
	 * @param string $tag
	 * @return bool
	 * @since 1.29
	 */
	public function isUserDefined( $tag ) {
		$storedTags = $this->getUserTags();
		return isset( $storedTags[$tag] );
	}

	/**
	 * Gets software-defined tags mapped to their params
	 * These tags are defined and applied from core or by extensions.
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.29
	 */
	public function getSoftwareTags() {
		// Save in class if not already done
		if ( $this->softwareTags === null ) {
			$coreTags = $this->config->get( 'CoreTags' );
			// Using + to properly handle numeric tag names
			$this->softwareTags = $coreTags + self::fetchRegistered( $this );
		}
		return $this->softwareTags;
	}

	/**
	 * Returns whether the tag is software-defined.
	 *
	 * @param string $tag
	 * @return bool
	 * @since 1.29
	 */
	public function isSoftwareDefined( $tag ) {
		$registeredTags = $this->getSoftwareTags();
		return isset( $registeredTags[$tag] );
	}

	/**
	 * Gets all defined tags mapped to their params
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.29
	 */
	public function getDefinedTags() {
		if ( $this->definedTags === null ) {
			// Using + to properly handle numeric tag names
			$this->definedTags = $this->getUserTags() + $this->getSoftwareTags();
		}
		return $this->definedTags;
	}

	/**
	 * Returns whether the tag is defined.
	 *
	 * @param string $tag
	 * @return bool
	 * @since 1.29
	 */
	public function isDefined( $tag ) {
		$definedTags = $this->getDefinedTags( $tag );
		return isset( $definedTags[$tag] );
	}

	/**
	 * Retrieves 'active' status for tags
	 *
	 * @param string $tag
	 * @return bool
	 * @since 1.29
	 */
	public function isActive( $tag ) {
		$definedTags = $this->getDefinedTags( $tag );
		return isset( $definedTags[$tag]['active'] ) &&
			$definedTags[$tag]['active'];
	}

	/**
	 * Returns name of the extension defining the tag, if provided by the hook
	 * False if not provided or string is empty
	 *
	 * @param string $tag
	 * @return string|false
	 * @since 1.29
	 */
	public function getExtensionName( $tag ) {
		$registeredTags = $this->getSoftwareTags();
		if ( isset( $registeredTags[$tag]['extName'] ) ) {
			$res = (string) $registeredTags[$tag]['extName'];
			if ( $res !== '' ) {
				return $res;
			}
		}
		return false;
	}

	/**
	 * Returns array of params for the extension-specific description, if provided by the hook
	 * False if not provided or array is empty
	 *
	 * @param string $tag
	 * @return array|false
	 * @since 1.29
	 */
	public function getExtensionDescriptionMessageParams( $tag ) {
		$registeredTags = $this->getSoftwareTags();
		if ( isset( $registeredTags[$tag]['descParams'] ) ) {
			$res = (array) $registeredTags[$tag]['descParams'];
			if ( count( $res ) ) {
				return $res;
			}
		}
		return false;
	}

	/**
	 * Gets tags used on the wiki
	 *
	 * @return array Array of tags
	 * @since 1.29
	 */
	public function getUsedTags() {
		// Save in class if not already done
		if ( $this->usedTags === null ) {
			$this->usedTags = self::fetchUsed();
		}
		return $this->usedTags;
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 *
	 * @return Array of strings: tags => array of params
	 */
	private static function fetchStored() {
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
		return ChangeTagsCache::getStoredFromCache( $callBack );
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have already been used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * The result is cached for 1 hour, so newly defined tags might not appear registered
	 * for that period, or recently undefined tags might still appear registered.
	 * If a tag should be updated quickly, this cache can be purged with the
	 * purgeRegisteredTagsCache function.
	 *
	 * @param ChangeTagsContext $changeTagsContext Caller
	 * @return Array of strings: tags => arrays of params
	 */
	private static function fetchRegistered( ChangeTagsContext $changeTagsContext ) {
		if ( !Hooks::isRegistered( 'ChangeTagsRegister' ) &&
			!Hooks::isRegistered( 'ListDefinedTags' ) &&
			!Hooks::isRegistered( 'ChangeTagsListActive' ) ) {
			return [];
		}
		$callBack = function( $oldValue, &$ttl, array &$setOpts ) use ( $changeTagsContext ) {
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
			// Pass ChangeTagsContext so extensions can e.g. call getUsedTags()
			// if they want to define only already-applied tags
			Hooks::run( 'ChangeTagsRegister', [ &$registeredTags, $changeTagsContext ] );

			// Removing nulls inserted as keys
			unset( $registeredTags[''] );

			return $registeredTags;
		};
		return ChangeTagsCache::getRegisteredFromCache( $callBack );
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
	private static function fetchUsed() {
		$fname = __METHOD__;
		$callBack = function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
			$dbr = wfGetDB( DB_REPLICA );
			$setOpts += Database::getCacheSetOptions( $dbr );
			$res = $dbr->select(
				'change_tag',
				[ 'ct_tag' ],
				[],
				$fname,
				[ 'DISTINCT' ]
			);

			$changeTags = [];
			foreach ( $res as $row ) {
				$changeTags[] = $row->ct_tag;
			}
			return $changeTags;
		};
		return ChangeTagsCache::getUsedFromCache( $callBack );
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
	 * @since 1.29
	 */
	public function showTagEditingUI( User $user ) {
		return $user->isAllowed( 'changetags' ) && $this->getUserTags();
	}
}
