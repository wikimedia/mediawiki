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
 * Allows to manage change tags, checking permissions for doing so
 * @since 1.29
 */
class ChangeTagsManager {

	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	const MAX_DELETE_USES = 5000;

	/**
	 * @var ChangeTagsContext
	 */
	protected $context;

	/**
	 * @var User
	 */
	protected $performer;

	/**
	 * @var bool
	 */
	protected $ignoreWarnings;

	/**
	 * @var array
	 */
	protected $loggingChangeTags = [];

	/**
	 * @param ChangeTagsContext $context
	 * @param User|null $performer
	 * @param bool $ignoreWarnings
	 * @since 1.29
	 */
	public function __construct( ChangeTagsContext $context, User $performer = null,
		$ignoreWarnings = false ) {
		$this->context = $context;
		$this->performer = $performer;
		$this->ignoreWarnings = $ignoreWarnings;
	}

	/**
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param string $tag Tag that you are interested in activating
	 * @param bool $forWrite Set to true if you check to actually activate the tag
	 * @return Status
	 * @since 1.29
	 */
	public function canActivateTag( $tag, $forWrite = false ) {

		if ( $this->performer !== null ) {
			if ( !$this->performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $this->performer->getName() );
			}
		}

		// non-existing tags cannot be managed
		if ( $forWrite && !$this->context->isDefined( $tag ) &&
			!$this->context->getHitcount( $tag ) ) {
			return Status::newFatal( 'tags-manage-not-found', $tag );
		}

		// defined tags cannot be activated
		if ( $this->context->isDefined( $tag ) ) {
			return Status::newFatal( 'tags-activate-not-allowed', $tag );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 *
	 * @param string $tag Tag that you are interested in deactivating
	 * @param bool $forWrite Set to true if you check to actually deactivate the tag
	 * @return Status
	 * @since 1.29
	 */
	public function canDeactivateTag( $tag, $forWrite = false ) {

		if ( $this->performer !== null ) {
			if ( !$this->performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $this->performer->getName() );
			}
		}

		// non-existing tags cannot be managed
		if ( $forWrite && !$this->context->isDefined( $tag ) &&
			!$this->context->getHitcount( $tag ) ) {
			return Status::newFatal( 'tags-manage-not-found', $tag );
		}

		// only tags stored in valid_tag can be deactivated
		if ( !$this->context->isUserDefined( $tag ) ) {
			return Status::newFatal( 'tags-deactivate-not-allowed', $tag );
		}
		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to create this tag?
	 *
	 * @param string $tag Tag that you are interested in creating
	 * @param bool $forWrite Set to true if you check to actually create the tag
	 * @return Status
	 * @since 1.29
	 */
	public function canCreateTag( $tag, $forWrite = false ) {

		if ( $this->performer !== null ) {
			if ( !$this->performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $this->performer->getName() );
			}
		}

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
		if ( $title === null ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// does the tag already exist?
		if ( $forWrite && ( $this->context->isDefined( $tag ) ||
			$this->context->getHitcount( $tag ) ) ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', [ $tag, $this->performer, &$canCreateResult ] );
		return $canCreateResult;
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 *
	 * @param string $tag Tag that you are interested in deleting
	 * @param bool $forWrite Set to true if you check to actually delete the tag
	 * @param int $hitcount Set to true if you check to actually delete the tag
	 * @return Status
	 * @since 1.29
	 */
	public function canDeleteTag( $tag, $forWrite = false, &$hitcount = null ) {

		if ( $this->performer !== null ) {
			if ( !$this->performer->isAllowed( 'deletechangetags' ) ) {
				return Status::newFatal( 'tags-delete-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $this->performer->getName() );
			}
		}

		if ( $forWrite ) {
			$hitcount = $this->context->getHitcount( $tag );
		}

		// non-existing tags cannot be managed
		if ( $forWrite && !$this->context->isDefined( $tag ) && !$hitcount ) {
			return Status::newFatal( 'tags-manage-not-found', $tag );
		}

		// tags with too many uses cannot be deleted
		if ( $hitcount > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		// extension-defined tags can't be deleted unless the extension specifically allows it
		if ( $this->context->isSoftwareDefined( $tag ) ) {
			$registeredTags = $this->context->getSoftwareTags();
			if ( !isset( $registeredTags[$tag]['canDelete'] ) ||
				!$registeredTags[$tag]['canDelete'] ) {
				return Status::newFatal( 'tags-delete-not-allowed' );
			}
		}

		// user-defined tags, extension defined tags when allowed, or undefined tags can be deleted
		return Status::newGood();
	}

	/**
	 * Activates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to self::canActivateTag(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.29
	 */
	public function activateTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsCache::purgeRegisteredTagsCache();

		// are we allowed to do this?
		$result = $this->canActivateTag( $tag, true );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
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
		ChangeTagsCache::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'activate', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Deactivates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to self::canDeactivateTag(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.29
	 */
	public function deactivateTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsCache::purgeRegisteredTagsCache();

		// are we allowed to do this?
		$result = $this->canDeactivateTag( $tag, true );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag', [ 'vt_tag' => $tag ], __METHOD__ );

		// clear the memcache of stored tags
		ChangeTagsCache::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'deactivate', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Creates a tag by adding a row to the `valid_tag` table.
	 *
	 * Includes a call to self::canCreateTag(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.29
	 */
	public function createTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsCache::purgeRegisteredTagsCache();

		// are we allowed to do this?
		$result = $this->canCreateTag( $tag, true );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
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
		ChangeTagsCache::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'create', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Deletes a tag, checking whether it is allowed first, and adding a log entry
	 * afterwards.
	 *
	 * Includes a call to self::canDeleteTag(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.29
	 */
	public function deleteTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsCache::purgeRegisteredTagsCache();
		// purging stats cache to get the up to date hitcount
		ChangeTagsCache::purgeTagUsageCache();

		// are we allowed to do this?
		$hitcount = null;
		$result = $this->canDeleteTag( $tag, true, $hitcount );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$deleteResult = ChangeTagsUpdater::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = $this->logTagManagementAction( 'delete', $tag, $reason, $hitcount );
		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Add change tags to apply to the tag management log entry
	 *
	 * @param array $changeTags
	 * @since 1.29
	 */
	public function setChangeTagsForLogging( array $changeTags ) {
		$this->loggingChangeTags = $changeTags;
	}

	/**
	 * Writes a tag action into the tag management log.
	 *
	 * @param string $action
	 * @param string $tag
	 * @param string $reason
	 * @param int $tagCount For deletion only, how many usages the tag had before
	 * it was deleted.
	 * @return int ID of the inserted log entry
	 * @since 1.29
	 */
	protected function logTagManagementAction( $action, $tag, $reason, $tagCount = null ) {

		$dbw = wfGetDB( DB_MASTER );

		$logEntry = new ManualLogEntry( 'managetags', $action );
		$logEntry->setPerformer( $this->performer );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( Title::newFromText( 'Special:Tags' ) );
		$logEntry->setComment( $reason );

		$params = [ '4::tag' => $tag ];
		if ( !is_null( $tagCount ) ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'Tag' => $tag ] );
		$logEntry->setTags( $this->loggingChangeTags );

		$logId = $logEntry->insert( $dbw );
		$logEntry->publish( $logId );
		return $logId;
	}
}
