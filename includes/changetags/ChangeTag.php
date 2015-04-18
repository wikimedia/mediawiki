<?php
/**
 * Class for a change tag object.
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

class ChangeTag {

	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	const MAX_DELETE_USES = 5000;

	/**
	 * @var string Internal name of the tag
	 */
	protected $name = null;

	/**
	 * @var int Hitcount of the tag
	 */
	protected $hitcount = null;

	/**
	 * @var bool Whether the tag is stored in the valid_tag table
	 */
	protected $isStored = null;

	/**
	 * @var bool Whether the tag is registered by an extension
	 */
	protected $isRegistered = null;

	/**
	 * @var array Params for tags defined by extensions
	 * This includes 'active' status, whether the tag can be deleted,
	 * the name of the extension, and message params for the
	 * extension-specific description.
	 */
	protected $extensionParams = null;

	/**
	 * @var bool Whether the tag exists, i.e. is defined or has been applied
	 */
	protected $exists = null;

	/**
	 * @param string $tag Tag's name
	 * @param ChangeTagsContext|null $context Provides context for the tag :
	 * 1) tag usage statistics, to retrieve hitcount
	 * 2) stored tags, to check if it is defined in valid_tag table
	 * 3) registered tags, to check if it is defined by an extension,
	 * and in that case, retrieve active status and other params
	 *
	 * Providing a unique context is useful when multiple ChangeTag
	 * instances are created.
	 * It is not necessary for an isolated ChangeTag instance.
	 * @since 1.26
	 */
	function __construct( $tag, ChangeTagsContext $context = null ) {
		$this->name = $tag;

		// we only retrieve properties initially when the necessary
		// data is set in context
		if ( $context != null ) {
			// retrieve hitcount
			if (  $context->tagStatsAreSet() ) {
				$tagStats = $context->getStats();
				$this->hitcount = isset( $tagStats[$this->name] ) ?
					$tagStats[$this->name] : 0;
			}

			// retrieve whether tag is stored in valid_tag
			if ( $context->storedTagsAreSet() ) {
				$storedTags = $context->getStored();
				$this->isStored = isset( $storedTags[$this->name] );
			}

			// retrieve whether tag is registered by an extension,
			// and if it is, params provided by the extension
			if ( $context->registeredTagsAreSet() ) {
				$registeredTags = $context->getRegistered();
				$this->isRegistered = isset( $registeredTags[$this->name] );
				if ( $this->isRegistered ) {
					$this->extensionParams = $registeredTags[$this->name];
				}
			}
		}
	}

	/**
	 * Returns name of the tag
	 *
	 * @return string
	 * @since 1.26
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns hitcount
	 * If stats were not provided in context when the ChangeTag instance
	 * was created, it retrieves them and saves the hitcount.
	 *
	 * @return int
	 * @since 1.26
	 */
	public function getHitcount() {
		if ( $this->hitcount == null ) {
			$stats = ChangeTagsContext::tagStats( true );
			$this->hitcount = isset( $stats[$this->name] ) ? $stats[$this->name] : 0;
		}
		return $this->hitcount;
	}

	/**
	 * Returns whether the tag is user-defined.
	 * It retrieves the necessary data if it was not provided in context.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isUserDefined() {
		if ( $this->isStored == null ) {
			$storedTags = ChangeTagsContext::storedTags();
			$this->isStored = isset( $storedTags[$this->name] );
		}
		return $this->isStored;
	}

	/**
	 * Returns whether the tag is extension-defined.
	 * It retrieves the necessary data if it was not provided in context,
	 * and sets extension params.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isExtensionDefined() {
		if ( $this->isRegistered == null ) {
			$registeredTags = ChangeTagsContext::registeredTags();
			$this->isRegistered = isset( $registeredTags[$this->name] );
			if ( $this->isRegistered ) {
				$this->extensionParams = $registeredTags[$this->name];
			}
		}
		return $this->isRegistered;
	}

	/**
	 * Returns whether the tag is defined.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isDefined() {
		return $this->isExtensionDefined() || $this->isUserDefined();
	}

	/**
	 * Determines whether a tag 'exists', i.e. is defined or has been applied.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function exists() {
		if ( $this->exists == null ) {
			$this->exists = $this->isDefined() || ( $this->getHitcount() > 0 );
		}
		return $this->exists;
	}

	/**
	 * Returns name of the extension defining the tag, if provided by the hook
	 * False if not provided or string is empty
	 *
	 * @return string|false
	 * @since 1.26
	 */
	public function getExtensionName() {
		if ( $this->isExtensionDefined() && isset( $this->extensionParams['extName'] ) ) {
			$res = (string) $this->extensionParams['extName'];
			if ( $res != '' ) {
				return $res;
			}
		}
		return false;
	}

	/**
	 * Returns array of params for the extension-specific description, if provided by the hook
	 * False if not provided or array is empty
	 *
	 * @return array|false
	 * @since 1.26
	 */
	public function getExtensionDescriptionMessageParams() {
		if ( $this->isExtensionDefined() && isset( $this->extensionParams['descParams'] ) ) {
			$res = (array) $this->extensionParams['descParams'];
			if ( count( $res ) ) {
				return $res;
			}
		}
		return false;
	}

	/**
	 * Retrieves 'active' status for tags
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isActive() {
		if ( $this->isExtensionDefined() ) {
			// is the tag in active use by the extension ?
			return isset( $this->extensionParams['active'] ) &&
				$this->extensionParams['active'];
		} elseif ( $this->isUserDefined() ) {
			// user defined tags are assumed to be active
			return true;
		} else {
			// for undefined tags
			return false;
		}
	}

	/**
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canActivate( User $user = null ) {

		// user permission check
		if ( $user != null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// defined tags cannot be activated
		if ( $this->isExtensionDefined() || $this->isUserDefined() ) {
			return Status::newFatal( 'tags-activate-not-allowed', $this->name );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canDeactivate( User $user = null ) {

		// user permission check
		if ( $user != null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// only tags stored in valid_tag can be deactivated, provided, for tags not
		// also registered by extensions, that they have been applied previously
		if ( !$this->isUserDefined() || ( $this->getHitcount() == 0 &&
			!$this->isExtensionDefined() ) ) {
			return Status::newFatal( 'tags-deactivate-not-allowed', $this->name );
		}
		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canDelete( User $user = null ) {

		// user permission check
		if ( $user != null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// tags with too many uses cannot be deleted
		if ( $this->getHitcount() > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $this->name, self::MAX_DELETE_USES );
		}

		// extension-defined tags can't be deleted unless the extension specifically allows it
		if ( $this->isExtensionDefined() && ( !isset( $this->extensionParams['canDelete'] ) ||
			!$this->extensionParams['canDelete'] ) ) {
			return Status::newFatal( 'tags-delete-not-allowed' );
		}

		// user-defined tags, extension defined tags when allowed, or undefined tags can be deleted
		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to create this tag?
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canCreate( User $user = null ) {
		if ( $user != null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		$tag = $this->name;

		// no empty tags
		if ( $tag === '' ) {
			return Status::newFatal( 'tags-create-no-name' );
		}

		// tags cannot contain commas (used as a delimiter in tag_summary table) or
		// slashes (would break tag description messages in MediaWiki namespace)
		if ( strpos( $tag, ',' ) !== false || strpos( $tag, '/' ) !== false ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if (  $title == null ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// does the tag already exist?
		if ( $this->exists() ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', array( $tag, $user, &$canCreateResult ) );
		return $canCreateResult;
	}
}
