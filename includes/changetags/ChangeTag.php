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
 * Represents a change tag object, with its basic properties such as hitcount
 * and how it is defined (such as by an extension, or by valid_tag), as retrieved
 * from a ChangeTagsContext object
 * Deduces of those if it can be activated, deleted, etc
 * @since 1.27
 */
class ChangeTag {

	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	const MAX_DELETE_USES = 5000;

	/**
	 * Key usable in MediaWiki:Tags-settings.json to indicate default settings
	 */
	const DEFAULT_SETTINGS = '#default';

	/**
	 * @var string Internal name of the tag
	 */
	private $name;

	/**
	 * @var ChangeTagsContext Context for this ChangeTag instance
	 */
	private $context;

	/**
	 * @param string $tag Tag's name
	 * @param ChangeTagsContext $context ChangeTagsContext for this tag
	 * @since 1.27
	 */
	public function __construct( $tag, ChangeTagsContext $context ) {
		$this->name = $tag;
		$this->context = $context;
	}

	/**
	 * Returns name of the tag
	 *
	 * @return string
	 * @since 1.27
	 */
	final public function getName() {
		return $this->name;
	}

	/**
	 * Returns whether the tag is user-defined.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isUserDefined() {
		$storedTags = $this->context->getStored();
		return isset( $storedTags[$this->name] );
	}

	/**
	 * Returns whether the tag is extension-defined.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isExtensionDefined() {
		$registeredTags = $this->context->getRegistered();
		return isset( $registeredTags[$this->name] );
	}

	/**
	 * Returns whether the tag is core-defined.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isCoreDefined() {
		$coreTags = $this->context->getCoreDefined();
		return isset( $coreTags[$this->name] );
	}

	/**
	 * Returns whether the tag is defined.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isDefined() {
		$definedTags = $this->context->getDefined();
		return isset( $definedTags[$this->name] );
	}

	/**
	 * Returns hitcount
	 *
	 * @return int
	 * @since 1.27
	 */
	public function getHitcount() {
		$stats = $this->context->getStats();
		return isset( $stats[$this->name] ) ? $stats[$this->name] : 0;
	}

	/**
	 * Determines whether a tag 'exists', i.e. is defined or has been applied.
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function exists() {
		return $this->isDefined() || ( $this->getHitcount() > 0 );
	}

	/**
	 * Retrieves 'active' status for tags
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isActive() {
		$definedTags = $this->context->getDefined();
		return isset( $definedTags[$this->name]['active'] ) &&
			$definedTags[$this->name]['active'];
	}

	/**
	 * Retrieves user-defined ChangeTags settings from MediaWiki:Tags-settings.json
	 * The key for self::DEFAULT_SETTINGS is used as a default.
	 *
	 * @return array
	 * @since 1.27
	 */
	public function getSettings() {
		$tagSettings = $this->context->getSettings();
		// get default settings
		$default = [];
		if ( isset( $tagSettings[self::DEFAULT_SETTINGS] ) ) {
			$default = $tagSettings[self::DEFAULT_SETTINGS];
			if ( !is_array( $default ) ) {
				$default = [];
			}
		}
		// override with this tag's settings if defined and valid
		$settings = $default;
		if ( isset( $tagSettings[$this->name] ) ) {
			$settings = $tagSettings[$this->name];
			if ( !is_array( $settings ) ) {
				$settings = $default;
			}
		}
		return $settings;
	}

	/**
	 * Retrieves 'problem' status for this tag
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isProblem() {
		$settings = $this->getSettings();
		return isset( $settings['problem'] );
	}

	/**
	 * Returns name of the extension defining the tag, if provided by the hook
	 * False if not provided or string is empty
	 *
	 * @return string|false
	 * @since 1.27
	 */
	public function getExtensionName() {
		$registeredTags = $this->context->getRegistered();
		if ( isset( $registeredTags[$this->name]['extName'] ) ) {
			$res = (string) $registeredTags[$this->name]['extName'];
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
	 * @return array|false
	 * @since 1.27
	 */
	public function getExtensionDescriptionMessageParams() {
		$registeredTags = $this->context->getRegistered();
		if ( isset( $registeredTags[$this->name]['descParams'] ) ) {
			$res = (array) $registeredTags[$this->name]['descParams'];
			if ( count( $res ) ) {
				return $res;
			}
		}
		return false;
	}

	/**
	 * Is it OK to allow the user to manage this tag?
	 *
	 * @param User|null $user User whose permission you wish to check or null
	 * @param Bool $checkExist Whether to check existence of the tag
	 * @return Status
	 * @since 1.27
	 */
	protected function canManage( User $user, $checkExist = true ) {
		// user permission check
		if ( !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		// block check
		} elseif ( $user->isBlocked() ) {
			return Status::newFatal( 'tags-manage-blocked' );
		}

		// non-existing tags cannot be managed, unless ignored
		if ( $checkExist && !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to activate this tag?
	 * Assumes tag existence if no user is passed
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.27
	 */
	public function canActivate( User $user = null ) {

		if ( $user !== null ) {
			// check if user is allowed to manage this tag
			$manageStatus = $this->canManage( $user );
			if ( !$manageStatus->isGood() ) {
				return $manageStatus;
			}
		}

		// defined tags cannot be activated
		if ( $this->isDefined() ) {
			return Status::newFatal( 'tags-activate-not-allowed', $this->name );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 * Assumes tag existence if no user is passed
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.27
	 */
	public function canDeactivate( User $user = null ) {

		if ( $user !== null ) {
			// check if user is allowed to manage this tag
			$manageStatus = $this->canManage( $user );
			if ( !$manageStatus->isGood() ) {
				return $manageStatus;
			}
		}

		// only tags stored in valid_tag can be deactivated, provided, for tags not
		// also registered by extensions, that they have been applied previously
		if ( !$this->isUserDefined() || ( $this->getHitcount() === 0 &&
			!$this->isExtensionDefined() ) ) {
			return Status::newFatal( 'tags-deactivate-not-allowed', $this->name );
		}
		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 * Assumes tag existence if no user is passed
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.27
	 */
	public function canDelete( User $user = null ) {

		if ( $user !== null ) {
			// check if user is allowed to manage this tag
			$manageStatus = $this->canManage( $user );
			if ( !$manageStatus->isGood() ) {
				return $manageStatus;
			}
		}

		// tags with too many uses cannot be deleted
		if ( $this->getHitcount() > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $this->name, self::MAX_DELETE_USES );
		}

		// core tags cannot be deleted
		if ( $this->isCoreDefined() ) {
			return Status::newFatal( 'tags-delete-core' );
		}

		// extension-defined tags can't be deleted unless the extension specifically allows it
		if ( $this->isExtensionDefined() ) {
			$registeredTags = $this->context->getRegistered();
			if ( !isset( $registeredTags[$this->name]['canDelete'] ) ||
				!$registeredTags[$this->name]['canDelete'] ) {
				return Status::newFatal( 'tags-delete-not-allowed' );
			}
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
	 * @since 1.27
	 */
	public function canCreate( User $user = null ) {

		if ( $user !== null ) {
			// check if user is allowed to manage this tag, bypass existence check
			$manageStatus = $this->canManage( $user, false );
			if ( !$manageStatus->isGood() ) {
				return $manageStatus;
			}
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

		// make sure that the name given does not map to an integer key in PHP arrays
		// to avoid issues e.g. when merging arrays, so integers are not allowed
		$testArray = array_merge( [], [ $tag => 1 ] );
		if ( isset( $testArray[0] ) ) {
			return Status::newFatal( 'tags-create-invalid-integer' );
		}

		// tags cannot contain some strings reserved for core tags, or system messages
		if ( strpos( $tag, 'core-' ) === 0 || $tag === self::DEFAULT_SETTINGS ||
			strpos( $tag, '-appearance' ) !== false ||
			strpos( $tag, '-description' ) !== false ) {
			return Status::newFatal( 'tags-create-invalid-reserved' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( $title === null ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// does the tag already exist?
		if ( $this->exists() ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', [ $tag, $user, &$canCreateResult ] );
		return $canCreateResult;
	}
}
