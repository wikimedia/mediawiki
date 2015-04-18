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
	 * @var string $name Internal name of the tag
	 */
	public $name = null;

	/**
	 * @var int $hitcount Hitcount of the tag, obtained from change_tag table
	 */
	public $hitcount = null;

	/**
	 * @var bool $isDefined Whether the tag is defined
	 */
	public $isDefined = null;

	/**
	 * @var bool $isStored Whether the tag is stored in the valid_tag table
	 */
	public $isStored = null;

	/**
	 * @var bool $extensionDefined Whether the tag is registered by an extension
	 */
	public $extensionDefined = null;

	/**
	 * @var bool $userDefined Whether the tag is user defined
	 */
	public $userDefined = null;

	/**
	 * @var object $context References tags defined on the wiki, listed by source
	 */
	protected $context = null;

	/**
	 * @var array $params Params for the tags, as defined by controlling source
	 */
	protected $params = null;

	/**
	 * @param string $tag tag's name
	 * @since 1.26
	 */
	function __construct( $tag ) {
		$this->name = $tag;
	}

	/**
	 * This creates a change tag object with specified context.
	 * If $tag is already a tag instance, this does nothing.
	 *
	 * @param string $tag tag's name or changeTag instance
	 * @param object|null $context context to set (retrieved if not provided)
	 * @param int|null $hitcount hitcount to set (retrieved if not provided)
	 * @return change tag object
	 * @since 1.26
	 */
	public static function getChangeTagObject( $tag, $context = null, $hitcount = null ) {

		$changeTag = new ChangeTag( $tag );

		// get hitcount if not provided
		if ( is_null( $hitcount ) ) {
			$stats = ChangeTags::getTagUsageStatistics();
			$hitcount = $stats[$tag];
			unset( $stats );
		}

		// set hitcount
		$changeTag->setHitcount( $hitcount );

		// get full context if not provided
		if ( is_null( $context ) ) {
			$context = array(
				'storedTags' => self::getStoredTags(),
				'registeredTags' => self::getRegisteredTags()
			);
		}

		// set context
		$changeTag->setContext( $context );

		return $changeTag;
	}

	/**
	 * Set hitcount for the current instance, by checking tag stats.
	 *
	 * @param int $value
	 * @since 1.26
	 */
	public function setHitcount( $value ) {
		$this->hitcount = $value;
	}

	/**
	 * Context is set by providing the lists of stored tags and of registered tags
	 * as an array
	 *
	 * @param array $changeTagContext Array of the form :
	 * array(
	 * 	'storedTags' => self::getStoredTags(),
	 * 	'registeredTags' => self::getRegisteredTags()
	 * );
	 * They are not modified internally so can be passed by reference.
	 * @since 1.26
	 */
	public function setContext( $changeTagContext ) {
		$this->context = (object) $changeTagContext;
		$stored = isset( $this->context->storedTags );
		$registered = isset( $this->context->registeredTags );

		if ( $stored ) {
			$this->isStored = isset( $this->context->storedTags[$this->name] );
			$this->params =& $this->context->storedTags[$this->name];
		}

		if ( $registered ) {
			$this->extensionDefined = isset( $this->context->registeredTags[$this->name] );
			// extensions params override those from valid_tag
			$this->params =& $this->context->registeredTags[$this->name];
		}

		if ( $stored && $registered ) {
			$this->isDefined =  $this->isStored || $this->extensionDefined;
			// a tag is user defined if it is stored in valid_tag and not extension defined
			$this->userDefined = $this->isStored && !$this->extensionDefined;
		}
	}

	/**
	 * Returns name of the extension defining the tag, if provided by the hook
	 * False if not provided or string is empty
	 *
	 * @return string|false
	 * @since 1.26
	 */
	public function getExtName() {
		if ( !isset( $this->context->registeredTags ) ) {
			throw new Exception( "Cannot retrieve extension name if registered tags
				have not been set." );
		}
		if ( $this->extensionDefined && isset( $this->params['extName'] ) ) {
			$res = (string) $this->params['extName'];
			return !empty( $res ) ? $res : false;
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
	public function getExtMsgPar() {
		if ( !isset( $this->context->registeredTags ) ) {
			throw new Exception( "Cannot retrieve extension-specific message params if
				registered tags have not been set." );
		}
		if ( $this->extensionDefined && isset( $this->params['descParams'] ) ) {
			$res = (array) $this->params['descParams'];
			return !empty( $res ) ? $res : false;
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
		if ( !isset( $this->context ) ) {
			throw new Exception( "Cannot determine active status if context is not set." );
		}
		if ( $this->extensionDefined ) {
			// is the tag in active use by the extension ?
			return isset( $this->params['active'] ) &&
				$this->params['active'];
		} elseif ( $this->userDefined ) {
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
	 * @since 1.25
	 */
	public function canActivate( User $user = null ) {

		// user permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->isDefined && ( $this->hitcount == 0 ) ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// defined tags cannot be activated
		if ( $this->extensionDefined || $this->userDefined ) {
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
	 * @since 1.25
	 */
	public function canDeactivate( User $user = null ) {

		// user permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->isDefined && ( $this->hitcount == 0 ) ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// only tags stored in valid_tag can be deactivated, provided, for user defined
		// tags, that they have been applied at least once
		if ( !$this->isStored || ( $this->userDefined && $this->hitcount == 0 ) ) {
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
	 * @since 1.25
	 */
	public function canDelete( User $user = null ) {

		// user permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be managed
		if ( !$this->isDefined && ( $this->hitcount == 0 ) ) {
			return Status::newFatal( 'tags-manage-not-found', $this->name );
		}

		// tags with too many uses cannot be deleted
		if ( $this->hitcount > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $this->name, self::MAX_DELETE_USES );
		}

		if ( $this->extensionDefined && ( !isset( $this->params['canDelete'] ) ||
			!$this->params['canDelete'] ) ) {
			// extension-defined tags can't be deleted unless the extension
			// specifically allows it
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
	 * @since 1.25
	 */
	public function canCreate( User $user = null ) {
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
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
		if ( is_null( $title ) ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// does the tag already exist?
		if ( $this->isDefined || ( $this->hitcount > 0 ) ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', array( $tag, $user, &$canCreateResult ) );
		return $canCreateResult;
	}

	/**
	 * Defines a tag in the valid_tag table, without checking that the tag name
	 * is valid.
	 * Extensions should NOT use this function; they can use the ChangeTagsRegister
	 * hook instead.
	 *
	 * @param string $tag Tag to create
	 * @since 1.25
	 */
	public static function defineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			array( 'vt_tag' ),
			array( 'vt_tag' => $tag ),
			__METHOD__ );

		// clear the memcache of stored tags
		ChangeTags::purgeStoredTagsCache();
	}

	/**
	 * Removes a tag from the valid_tag table. The tag may remain in use by
	 * extensions, and may still show up as 'defined' if an extension is setting
	 * it from the ChangeTagsRegister hook.
	 *
	 * @param string $tag Tag to remove
	 * @since 1.25
	 */
	public static function undefineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag', array( 'vt_tag' => $tag ), __METHOD__ );

		// clear the memcache of stored tags
		ChangeTags::purgeStoredTagsCache();
	}

	/**
	 * Permanently removes all traces of a tag from the DB. Good for removing
	 * misspelt or temporary tags.
	 *
	 * This function should be directly called by maintenance scripts only, never
	 * by user-facing code. See deleteTagWithChecks() for functionality that can
	 * safely be exposed to users.
	 *
	 * @param string $tag Tag to remove
	 * @return Status The returned status will be good unless a hook changed it
	 * @since 1.25
	 */
	public static function deleteTagEverywhere( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );

		// delete from valid_tag
		// we don't call self::undefineTag since the purging of caches should occur
		// at the same time after all operations have been performed
		$dbw->delete( 'valid_tag',
			array( 'vt_tag' => $tag ),
			__METHOD__ );

		// find out which revisions use this tag, so we can delete from tag_summary
		$result = $dbw->select( 'change_tag',
			array( 'ct_rc_id', 'ct_log_id', 'ct_rev_id', 'ct_tag' ),
			array( 'ct_tag' => $tag ),
			__METHOD__ );
		foreach ( $result as $row ) {
			if ( $row->ct_rev_id ) {
				$field = 'ts_rev_id';
				$fieldValue = $row->ct_rev_id;
			} elseif ( $row->ct_log_id ) {
				$field = 'ts_log_id';
				$fieldValue = $row->ct_log_id;
			} elseif ( $row->ct_rc_id ) {
				$field = 'ts_rc_id';
				$fieldValue = $row->ct_rc_id;
			} else {
				// don't know what's up; just skip it
				continue;
			}

			// remove the tag from the relevant row of tag_summary
			$tsResult = $dbw->selectField( 'tag_summary',
				'ts_tags',
				array( $field => $fieldValue ),
				__METHOD__ );
			$tsValues = explode( ',', $tsResult );
			$tsValues = array_values( array_diff( $tsValues, array( $tag ) ) );
			if ( !$tsValues ) {
				// no tags left, so delete the row altogether
				$dbw->delete( 'tag_summary',
					array( $field => $fieldValue ),
					__METHOD__ );
			} else {
				$dbw->update( 'tag_summary',
					array( 'ts_tags' => implode( ',', $tsValues ) ),
					array( $field => $fieldValue ),
					__METHOD__ );
			}
		}

		// delete from change_tag
		$dbw->delete( 'change_tag', array( 'ct_tag' => $tag ), __METHOD__ );

		$dbw->commit( __METHOD__ );

		// give extensions a chance
		$status = Status::newGood();
		Hooks::run( 'ChangeTagAfterDelete', array( $tag, &$status ) );
		// let's not allow error results, as the actual tag deletion succeeded
		if ( !$status->isOK() ) {
			wfDebug( 'ChangeTagAfterDelete error condition downgraded to warning' );
			$status->ok = true;
		}

		// Clearing tag caches
		ChangeTags::purgeTagCacheAll( $tag );

		return $status;
	}
}
