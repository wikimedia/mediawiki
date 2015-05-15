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
	private $name;

	/**
	 * @var ChangeTagsContext Context for this ChangeTag instance
	 */
	protected $context = null;

	/**
	 * @param string $tag Tag's name
	 * @param ChangeTagsContext|null $context Provides context for the tag :
	 * 1) tag usage statistics, to retrieve hitcount
	 * 2) stored tags, to check if it is defined in valid_tag table
	 * 3) registered tags, to check if it is defined by an extension,
	 * and in that case, retrieve active status and other params
	 *
	 * It is not needed to provide context for an isolated ChangeTag instance.
	 * If not provided, one is automatically generated and necessary data is
	 * fetched as needed.
	 *
	 * @since 1.26
	 */
	function __construct( $tag, ChangeTagsContext $context = null ) {
		$this->name = $tag;

		// Use provided context, or instantiate one
		if ( $context === null ) {
			$this->context = new ChangeTagsContext;
		} else {
			$this->context = $context;
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
	 * Returns whether the tag is user-defined.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isUserDefined() {
		$storedTags = $this->context->getStored();
		return isset( $storedTags[$this->name] );
	}

	/**
	 * Returns whether the tag is extension-defined.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isExtensionDefined() {
		$registeredTags = $this->context->getRegistered();
		return isset( $registeredTags[$this->name] );
	}

	/**
	 * Returns whether the tag is defined.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isDefined() {
		$definedTags = $this->context->getDefined();
		return isset( $definedTags[$this->name] );
	}

	/**
	 * Returns hitcount
	 *
	 * @return int
	 * @since 1.26
	 */
	public function getHitcount() {
		$stats = $this->context->getStats();
		return isset( $stats[$this->name] ) ? $stats[$this->name] : 0;
	}

	/**
	 * Determines whether a tag 'exists', i.e. is defined or has been applied.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function exists() {
		return $this->isDefined() || ( $this->getHitcount() > 0 );
	}

	/**
	 * Retrieves 'active' status for tags
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isActive() {
		$definedTags = $this->context->getDefined();
		return isset( $definedTags[$this->name]['active'] ) &&
			$definedTags[$this->name]['active'];
	}

	/**
	 * Returns name of the extension defining the tag, if provided by the hook
	 * False if not provided or string is empty
	 *
	 * @return string|false
	 * @since 1.26
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
	 * @since 1.26
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
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canActivate( User $user = null ) {

		// user permission check
		if ( $user !== null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// defined tags cannot be activated
		if ( $this->isDefined() ) {
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
		if ( $user !== null && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->exists() ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
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
	 *
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.26
	 */
	public function canDelete( User $user = null ) {

		// user permission check
		if ( $user !== null && !$user->isAllowed( 'managechangetags' ) ) {
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
	 * @since 1.26
	 */
	public function canCreate( User $user = null ) {

		// user permission check
		if ( $user !== null && !$user->isAllowed( 'managechangetags' ) ) {
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

		// make sure that the name given does not map to an integer key in PHP arrays
		// to avoid issues e.g. when merging arrays, so integers are not allowed
		$testArray = array_merge( array(), array( $tag => 1 ) );
		if ( $testArray[0] === 1 ) {
			return Status::newFatal( 'tags-create-invalid-integer' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if (  $title === null ) {
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
