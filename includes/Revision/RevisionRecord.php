<?php
/**
 * Page revision base class.
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
 */

namespace MediaWiki\Revision;

use CommentStoreComment;
use Content;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use MWException;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * Page revision base class.
 *
 * RevisionRecords are considered value objects, but they may use callbacks for lazy loading.
 * Note that while the base class has no setters, subclasses may offer a mutable interface.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionRecord
 */
abstract class RevisionRecord {

	// RevisionRecord deletion constants
	const DELETED_TEXT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const SUPPRESSED_USER = self::DELETED_USER | self::DELETED_RESTRICTED; // convenience
	const SUPPRESSED_ALL = self::DELETED_TEXT | self::DELETED_COMMENT | self::DELETED_USER |
		self::DELETED_RESTRICTED; // convenience

	// Audience options for accessors
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	/** @var string|false Wiki ID; false means the current wiki */
	protected $mWiki = false;
	/** @var int|null */
	protected $mId;
	/** @var int */
	protected $mPageId;
	/** @var UserIdentity|null */
	protected $mUser;
	/** @var bool */
	protected $mMinorEdit = false;
	/** @var string|null */
	protected $mTimestamp;
	/** @var int using the DELETED_XXX and SUPPRESSED_XXX flags */
	protected $mDeleted = 0;
	/** @var int|null */
	protected $mSize;
	/** @var string|null */
	protected $mSha1;
	/** @var int|null */
	protected $mParentId;
	/** @var CommentStoreComment|null */
	protected $mComment;

	/** @var Title */
	protected $mTitle; // TODO: we only need the title for permission checks!

	/** @var RevisionSlots */
	protected $mSlots;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $dbDomain DB domain of the relevant wiki or false for the current one.
	 *
	 * @throws MWException
	 */
	function __construct( Title $title, RevisionSlots $slots, $dbDomain = false ) {
		Assert::parameterType( 'string|boolean', $dbDomain, '$dbDomain' );

		$this->mTitle = $title;
		$this->mSlots = $slots;
		$this->mWiki = $dbDomain;

		// XXX: this is a sensible default, but we may not have a Title object here in the future.
		$this->mPageId = $title->getArticleID();
	}

	/**
	 * Implemented to defy serialization.
	 *
	 * @throws LogicException always
	 */
	public function __sleep() {
		throw new LogicException( __CLASS__ . ' is not serializable.' );
	}

	/**
	 * @param RevisionRecord $rec
	 *
	 * @return bool True if this RevisionRecord is known to have same content as $rec.
	 *         False if the content is different (or not known to be the same).
	 */
	public function hasSameContent( RevisionRecord $rec ) {
		if ( $rec === $this ) {
			return true;
		}

		if ( $this->getId() !== null && $this->getId() === $rec->getId() ) {
			return true;
		}

		// check size before hash, since size is quicker to compute
		if ( $this->getSize() !== $rec->getSize() ) {
			return false;
		}

		// instead of checking the hash, we could also check the content addresses of all slots.

		if ( $this->getSha1() === $rec->getSha1() ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the Content of the given slot of this revision.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * MCR migration note: this replaces Revision::getContent
	 *
	 * @param string $role The role name of the desired slot
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @throws RevisionAccessException if the slot does not exist or slot data
	 *        could not be lazy-loaded.
	 * @return Content|null The content of the given slot, or null if access is forbidden.
	 */
	public function getContent( $role, $audience = self::FOR_PUBLIC, User $user = null ) {
		// XXX: throwing an exception would be nicer, but would a further
		// departure from the signature of Revision::getContent(), and thus
		// more complex and error prone refactoring.
		if ( !$this->audienceCan( self::DELETED_TEXT, $audience, $user ) ) {
			return null;
		}

		$content = $this->getSlot( $role, $audience, $user )->getContent();
		return $content->copy();
	}

	/**
	 * Returns meta-data for the given slot.
	 *
	 * @param string $role The role name of the desired slot
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @throws RevisionAccessException if the slot does not exist or slot data
	 *        could not be lazy-loaded.
	 * @return SlotRecord The slot meta-data. If access to the slot content is forbidden,
	 *         calling getContent() on the SlotRecord will throw an exception.
	 */
	public function getSlot( $role, $audience = self::FOR_PUBLIC, User $user = null ) {
		$slot = $this->mSlots->getSlot( $role );

		if ( !$this->audienceCan( self::DELETED_TEXT, $audience, $user ) ) {
			return SlotRecord::newWithSuppressedContent( $slot );
		}

		return $slot;
	}

	/**
	 * Returns whether the given slot is defined in this revision.
	 *
	 * @param string $role The role name of the desired slot
	 *
	 * @return bool
	 */
	public function hasSlot( $role ) {
		return $this->mSlots->hasSlot( $role );
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotRoles() {
		return $this->mSlots->getSlotRoles();
	}

	/**
	 * Returns the slots defined for this revision.
	 *
	 * @return RevisionSlots
	 */
	public function getSlots() {
		return $this->mSlots;
	}

	/**
	 * Returns the slots that originate in this revision.
	 *
	 * Note that this does not include any slots inherited from some earlier revision,
	 * even if they are different from the slots in the immediate parent revision.
	 * This is the case for rollbacks: slots of a rollback revision are inherited from
	 * the rollback target, and are different from the slots in the parent revision,
	 * which was rolled back.
	 *
	 * To find all slots modified by this revision against its immediate parent
	 * revision, use RevisionSlotsUpdate::newFromRevisionSlots().
	 *
	 * @return RevisionSlots
	 */
	public function getOriginalSlots() {
		return new RevisionSlots( $this->mSlots->getOriginalSlots() );
	}

	/**
	 * Returns slots inherited from some previous revision.
	 *
	 * "Inherited" slots are all slots that do not originate in this revision.
	 * Note that these slots may still differ from the one in the parent revision.
	 * This is the case for rollbacks: slots of a rollback revision are inherited from
	 * the rollback target, and are different from the slots in the parent revision,
	 * which was rolled back.
	 *
	 * @return RevisionSlots
	 */
	public function getInheritedSlots() {
		return new RevisionSlots( $this->mSlots->getInheritedSlots() );
	}

	/**
	 * Get revision ID. Depending on the concrete subclass, this may return null if
	 * the revision ID is not known (e.g. because the revision does not yet exist
	 * in the database).
	 *
	 * MCR migration note: this replaces Revision::getId
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Get parent revision ID (the original previous page revision).
	 * If there is no parent revision, this returns 0.
	 * If the parent revision is undefined or unknown, this returns null.
	 *
	 * @note As of MW 1.31, the database schema allows the parent ID to be
	 * NULL to indicate that it is unknown.
	 *
	 * MCR migration note: this replaces Revision::getParentId
	 *
	 * @return int|null
	 */
	public function getParentId() {
		return $this->mParentId;
	}

	/**
	 * Returns the nominal size of this revision, in bogo-bytes.
	 * May be calculated on the fly if not known, which may in the worst
	 * case may involve loading all content.
	 *
	 * MCR migration note: this replaces Revision::getSize
	 *
	 * @throws RevisionAccessException if the size was unknown and could not be calculated.
	 * @return int
	 */
	abstract public function getSize();

	/**
	 * Returns the base36 sha1 of this revision. This hash is derived from the
	 * hashes of all slots associated with the revision.
	 * May be calculated on the fly if not known, which may in the worst
	 * case may involve loading all content.
	 *
	 * MCR migration note: this replaces Revision::getSha1
	 *
	 * @throws RevisionAccessException if the hash was unknown and could not be calculated.
	 * @return string
	 */
	abstract public function getSha1();

	/**
	 * Get the page ID. If the page does not yet exist, the page ID is 0.
	 *
	 * MCR migration note: this replaces Revision::getPage
	 *
	 * @return int
	 */
	public function getPageId() {
		return $this->mPageId;
	}

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false The wiki's logical name, of false to indicate the local wiki.
	 */
	public function getWikiId() {
		return $this->mWiki;
	}

	/**
	 * Returns the title of the page this revision is associated with as a LinkTarget object.
	 *
	 * MCR migration note: this replaces Revision::getTitle
	 *
	 * @return LinkTarget
	 */
	public function getPageAsLinkTarget() {
		return $this->mTitle;
	}

	/**
	 * Fetch revision's author's user identity, if it's available to the specified audience.
	 * If the specified audience does not have access to it, null will be
	 * returned. Depending on the concrete subclass, null may also be returned if the user is
	 * not yet specified.
	 *
	 * MCR migration note: this replaces Revision::getUser
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return UserIdentity|null
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( !$this->audienceCan( self::DELETED_USER, $audience, $user ) ) {
			return null;
		} else {
			return $this->mUser;
		}
	}

	/**
	 * Fetch revision comment, if it's available to the specified audience.
	 * If the specified audience does not have access to the comment,
	 * this will return null. Depending on the concrete subclass, null may also be returned
	 * if the comment is not yet specified.
	 *
	 * MCR migration note: this replaces Revision::getComment
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 *
	 * @return CommentStoreComment|null
	 */
	public function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( !$this->audienceCan( self::DELETED_COMMENT, $audience, $user ) ) {
			return null;
		} else {
			return $this->mComment;
		}
	}

	/**
	 * MCR migration note: this replaces Revision::isMinor
	 *
	 * @return bool
	 */
	public function isMinor() {
		return (bool)$this->mMinorEdit;
	}

	/**
	 * MCR migration note: this replaces Revision::isDeleted
	 *
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		return ( $this->getVisibility() & $field ) == $field;
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * MCR migration note: this replaces Revision::getVisibility
	 *
	 * @return int
	 */
	public function getVisibility() {
		return (int)$this->mDeleted;
	}

	/**
	 * MCR migration note: this replaces Revision::getTimestamp.
	 *
	 * May return null if the timestamp was not specified.
	 *
	 * @return string|null
	 */
	public function getTimestamp() {
		return $this->mTimestamp;
	}

	/**
	 * Check that the given audience has access to the given field.
	 *
	 * MCR migration note: this corresponds to Revision::userCan
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *        self::DELETED_COMMENT,
	 *        self::DELETED_USER
	 * @param int $audience One of:
	 *        RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *        RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *        RevisionRecord::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check. Required if $audience is FOR_THIS_USER,
	 *        ignored otherwise.
	 *
	 * @return bool
	 */
	public function audienceCan( $field, $audience, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( $field ) ) {
			return false;
		} elseif ( $audience == self::FOR_THIS_USER ) {
			if ( !$user ) {
				throw new InvalidArgumentException(
					'A User object must be given when checking FOR_THIS_USER audience.'
				);
			}

			if ( !$this->userCan( $field, $user ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * MCR migration note: this corresponds to Revision::userCan
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User $user User object to check
	 * @return bool
	 */
	protected function userCan( $field, User $user ) {
		// TODO: use callback for permission checks, so we don't need to know a Title object!
		return self::userCanBitfield( $this->getVisibility(), $field, $user, $this->mTitle );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * MCR migration note: this replaces Revision::userCanBitfield
	 *
	 * @param int $bitfield Current field
	 * @param int $field One of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @param User $user User object to check
	 * @param Title|null $title A Title object to check for per-page restrictions on,
	 *                          instead of just plain userrights
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, User $user, Title $title = null ) {
		if ( $bitfield & $field ) { // aspect is deleted
			if ( $bitfield & self::DELETED_RESTRICTED ) {
				$permissions = [ 'suppressrevision', 'viewsuppressed' ];
			} elseif ( $field & self::DELETED_TEXT ) {
				$permissions = [ 'deletedtext' ];
			} else {
				$permissions = [ 'deletedhistory' ];
			}

			// XXX: How can we avoid global scope here?
			//      Perhaps the audience check should be done in a callback.
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			$permissionlist = implode( ', ', $permissions );
			if ( $title === null ) {
				wfDebug( "Checking for $permissionlist due to $field match on $bitfield\n" );
				foreach ( $permissions as $perm ) {
					if ( $permissionManager->userHasRight( $user, $perm ) ) {
						return true;
					}
				}
				return false;
			} else {
				$text = $title->getPrefixedText();
				wfDebug( "Checking for $permissionlist on $text due to $field match on $bitfield\n" );

				foreach ( $permissions as $perm ) {
					if ( $permissionManager->userCan( $perm, $user, $title ) ) {
						return true;
					}
				}
				return false;
			}
		} else {
			return true;
		}
	}

	/**
	 * Returns whether this RevisionRecord is ready for insertion, that is, whether it contains all
	 * information needed to save it to the database. This should trivially be true for
	 * RevisionRecords loaded from the database.
	 *
	 * Note that this may return true even if getId() or getPage() return null or 0, since these
	 * are generally assigned while the revision is saved to the database, and may not be available
	 * before.
	 *
	 * @return bool
	 */
	public function isReadyForInsertion() {
		// NOTE: don't check getSize() and getSha1(), since that may cause the full content to
		// be loaded in order to calculate the values. Just assume these methods will not return
		// null if mSlots is not empty.

		// NOTE: getId() and getPageId() may return null before a revision is saved, so don't
		// check them.

		return $this->getTimestamp() !== null
			&& $this->getComment( self::RAW ) !== null
			&& $this->getUser( self::RAW ) !== null
			&& $this->mSlots->getSlotRoles() !== [];
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.32
 */
class_alias( RevisionRecord::class, 'MediaWiki\Storage\RevisionRecord' );
