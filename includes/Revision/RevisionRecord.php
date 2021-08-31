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
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\LegacyArticleIdAccess;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use Title;
use Wikimedia\NonSerializable\NonSerializableTrait;

/**
 * Page revision base class.
 *
 * RevisionRecords are considered value objects, but they may use callbacks for lazy loading.
 * Note that while the base class has no setters, subclasses may offer a mutable interface.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionRecord
 */
abstract class RevisionRecord implements WikiAwareEntity {
	use LegacyArticleIdAccess;
	use NonSerializableTrait;
	use WikiAwareEntityTrait;

	// RevisionRecord deletion constants
	public const DELETED_TEXT = 1;
	public const DELETED_COMMENT = 2;
	public const DELETED_USER = 4;
	public const DELETED_RESTRICTED = 8;
	public const SUPPRESSED_USER = self::DELETED_USER | self::DELETED_RESTRICTED; // convenience
	public const SUPPRESSED_ALL = self::DELETED_TEXT | self::DELETED_COMMENT | self::DELETED_USER |
		self::DELETED_RESTRICTED; // convenience

	// Audience options for accessors
	public const FOR_PUBLIC = 1;
	public const FOR_THIS_USER = 2;
	public const RAW = 3;

	/** @var string|false Wiki ID; false means the current wiki */
	protected $wikiId = false;
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

	/** @var PageIdentity */
	protected $mPage;

	/** @var RevisionSlots */
	protected $mSlots;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param PageIdentity $page The page this RevisionRecord is associated with.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param false|string $wikiId Relevant wiki id or self::LOCAL for the current one.
	 */
	public function __construct( PageIdentity $page, RevisionSlots $slots, $wikiId = self::LOCAL ) {
		$this->assertWikiIdParam( $wikiId );

		$this->mPage = $page;
		$this->mSlots = $slots;
		$this->wikiId = $wikiId;
		$this->mPageId = $this->getArticleId( $page );
	}

	/**
	 * @param RevisionRecord $rec
	 *
	 * @return bool True if this RevisionRecord is known to have same content as $rec.
	 *         False if the content is different (or not known to be the same).
	 */
	public function hasSameContent( RevisionRecord $rec ): bool {
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
	 * MCR migration note: this replaced Revision::getContent
	 *
	 * @param string $role The role name of the desired slot
	 * @param int $audience
	 * @param Authority|null $performer user on whose behalf to check
	 *
	 * @return Content|null The content of the given slot, or null if access is forbidden.
	 */
	public function getContent( $role, $audience = self::FOR_PUBLIC, Authority $performer = null ): ?Content {
		// XXX: throwing an exception would be nicer, but would a further
		// departure from the old signature of Revision::getContent() when it existed,
		// and thus result in more complex and error prone refactoring.
		if ( !$this->audienceCan( self::DELETED_TEXT, $audience, $performer ) ) {
			return null;
		}

		$content = $this->getSlot( $role, $audience, $performer )->getContent();
		return $content->copy();
	}

	/**
	 * Returns meta-data for the given slot.
	 *
	 * @param string $role The role name of the desired slot
	 * @param int $audience
	 * @param Authority|null $performer user on who's behalf to check
	 *
	 * @throws RevisionAccessException if the slot does not exist or slot data
	 *        could not be lazy-loaded.
	 * @return SlotRecord The slot meta-data. If access to the slot's content is forbidden,
	 *         calling getContent() on the SlotRecord will throw an exception.
	 */
	public function getSlot( $role, $audience = self::FOR_PUBLIC, Authority $performer = null ): SlotRecord {
		$slot = $this->mSlots->getSlot( $role );

		if ( !$this->audienceCan( self::DELETED_TEXT, $audience, $performer ) ) {
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
	public function hasSlot( $role ): bool {
		return $this->mSlots->hasSlot( $role );
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotRoles(): array {
		return $this->mSlots->getSlotRoles();
	}

	/**
	 * Returns the slots defined for this revision.
	 *
	 * @note This provides access to slot content with no audience checks applied.
	 * Calling getContent() on the RevisionSlots object returned here, or on any
	 * SlotRecord it returns from getSlot(), will not fail due to access restrictions.
	 * If audience checks are desired, use getSlot( $role, $audience, $performer )
	 * or getContent( $role, $audience, $performer ) instead.
	 *
	 * @return RevisionSlots
	 */
	public function getSlots(): RevisionSlots {
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
	public function getOriginalSlots(): RevisionSlots {
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
	public function getInheritedSlots(): RevisionSlots {
		return new RevisionSlots( $this->mSlots->getInheritedSlots() );
	}

	/**
	 * Returns primary slots (those that are not derived).
	 *
	 * @return RevisionSlots
	 * @since 1.36
	 */
	public function getPrimarySlots(): RevisionSlots {
		return new RevisionSlots( $this->mSlots->getPrimarySlots() );
	}

	/**
	 * Get revision ID. Depending on the concrete subclass, this may return null if
	 * the revision ID is not known (e.g. because the revision does not yet exist
	 * in the database).
	 *
	 * MCR migration note: this replaced Revision::getId
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int|null
	 */
	public function getId( $wikiId = self::LOCAL ) {
		$this->deprecateInvalidCrossWiki( $wikiId, '1.36' );
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
	 * MCR migration note: this replaced Revision::getParentId
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int|null
	 */
	public function getParentId( $wikiId = self::LOCAL ) {
		$this->deprecateInvalidCrossWiki( $wikiId, '1.36' );
		return $this->mParentId;
	}

	/**
	 * Returns the nominal size of this revision, in bogo-bytes.
	 * May be calculated on the fly if not known, which may in the worst
	 * case may involve loading all content.
	 *
	 * MCR migration note: this replaced Revision::getSize
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
	 * MCR migration note: this replaced Revision::getSha1
	 *
	 * @throws RevisionAccessException if the hash was unknown and could not be calculated.
	 * @return string
	 */
	abstract public function getSha1();

	/**
	 * Get the page ID. If the page does not yet exist, the page ID is 0.
	 *
	 * MCR migration note: this replaced Revision::getPage
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int
	 */
	public function getPageId( $wikiId = self::LOCAL ) {
		$this->deprecateInvalidCrossWiki( $wikiId, '1.36' );
		return $this->mPageId;
	}

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false The wiki's logical name, of false to indicate the local wiki.
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * Returns the title of the page this revision is associated with as a LinkTarget object.
	 *
	 * @throws InvalidArgumentException if this revision does not belong to a local wiki
	 * @return LinkTarget
	 */
	public function getPageAsLinkTarget() {
		// TODO: Should be TitleValue::newFromPage( $this->mPage ),
		// but Title is used too much still, so let's keep propagating it
		return Title::castFromPageIdentity( $this->mPage );
	}

	/**
	 * Returns the page this revision belongs to.
	 *
	 * MCR migration note: this replaced Revision::getTitle
	 *
	 * @since 1.36
	 *
	 * @return PageIdentity
	 */
	public function getPage(): PageIdentity {
		return $this->mPage;
	}

	/**
	 * Fetch revision's author's user identity, if it's available to the specified audience.
	 * If the specified audience does not have access to it, null will be
	 * returned. Depending on the concrete subclass, null may also be returned if the user is
	 * not yet specified.
	 *
	 * MCR migration note: this replaced Revision::getUser
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the ID regardless of permissions
	 * @param Authority|null $performer user on who's behalf to check
	 * @return UserIdentity|null
	 */
	public function getUser( $audience = self::FOR_PUBLIC, Authority $performer = null ) {
		if ( !$this->audienceCan( self::DELETED_USER, $audience, $performer ) ) {
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
	 * MCR migration note: this replaced Revision::getComment
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer user on who's behalf to check
	 *
	 * @return CommentStoreComment|null
	 */
	public function getComment( $audience = self::FOR_PUBLIC, Authority $performer = null ) {
		if ( !$this->audienceCan( self::DELETED_COMMENT, $audience, $performer ) ) {
			return null;
		} else {
			return $this->mComment;
		}
	}

	/**
	 * MCR migration note: this replaced Revision::isMinor
	 *
	 * @return bool
	 */
	public function isMinor() {
		return (bool)$this->mMinorEdit;
	}

	/**
	 * MCR migration note: this replaced Revision::isDeleted
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
	 * MCR migration note: this replaced Revision::getVisibility
	 *
	 * @return int
	 */
	public function getVisibility() {
		return (int)$this->mDeleted;
	}

	/**
	 * MCR migration note: this replaced Revision::getTimestamp.
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
	 * MCR migration note: this corresponded to Revision::userCan
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *        self::DELETED_COMMENT,
	 *        self::DELETED_USER
	 * @param int $audience One of:
	 *        RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *        RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *        RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer user on who's behalf to check
	 *
	 * @return bool
	 */
	public function audienceCan( $field, $audience, Authority $performer = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( $field ) ) {
			return false;
		} elseif ( $audience == self::FOR_THIS_USER ) {
			if ( !$performer ) {
				throw new InvalidArgumentException(
					'An Authority object must be given when checking FOR_THIS_USER audience.'
				);
			}

			if ( !$this->userCan( $field, $performer ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if the give authority is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * MCR migration note: this corresponded to Revision::userCan
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param Authority $performer user on who's behalf to check
	 * @return bool
	 */
	public function userCan( $field, Authority $performer ) {
		return self::userCanBitfield( $this->getVisibility(), $field, $performer, $this->mPage );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * MCR migration note: this replaced Revision::userCanBitfield
	 *
	 * @param int $bitfield Current field
	 * @param int $field One of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @param Authority $performer user on who's behalf to check
	 * @param PageIdentity|null $page A PageIdentity object to check for per-page restrictions on,
	 *                          instead of just plain user rights
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, Authority $performer, PageIdentity $page = null ) {
		if ( $bitfield & $field ) { // aspect is deleted
			if ( $bitfield & self::DELETED_RESTRICTED ) {
				$permissions = [ 'suppressrevision', 'viewsuppressed' ];
			} elseif ( $field & self::DELETED_TEXT ) {
				$permissions = [ 'deletedtext' ];
			} else {
				$permissions = [ 'deletedhistory' ];
			}

			$permissionlist = implode( ', ', $permissions );
			if ( $page === null ) {
				wfDebug( "Checking for $permissionlist due to $field match on $bitfield" );
				return $performer->isAllowedAny( ...$permissions );
			} else {
				wfDebug( "Checking for $permissionlist on $page due to $field match on $bitfield" );
				foreach ( $permissions as $perm ) {
					if ( $performer->authorizeRead( $perm, $page ) ) {
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

	/**
	 * Checks whether the revision record is a stored current revision.
	 * @since 1.35
	 * @return bool
	 */
	public function isCurrent() {
		return false;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.32
 */
class_alias( RevisionRecord::class, 'MediaWiki\Storage\RevisionRecord' );
