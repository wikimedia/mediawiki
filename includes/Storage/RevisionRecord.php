<?php
/**
 * Lazy loading representation of a page revision.
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

namespace MediaWiki\Storage;

use CommentStore;
use CommentStoreComment;
use Content;
use InvalidArgumentException;
use LogicException;
use MWException;
use OutOfBoundsException;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * FIXME: update doc!
 * Lazy loading representation of a page revision.
 *
 * Callbacks are used for lazy loading, so this class
 * has no knowledge of the actual storage mechanism.
 *
 * @since 1.31
 */
abstract class RevisionRecord implements TransientDataAccess {

	// RevisionRecord deletion constants
	const DELETED_TEXT = 1;
	const DELETED_COMMENT = 2;
	const DELETED_USER = 4;
	const DELETED_RESTRICTED = 8;
	const SUPPRESSED_USER = 12; // convenience
	const SUPPRESSED_ALL = 15; // convenience

	// Audience options for accessors
	const FOR_PUBLIC = 1;
	const FOR_THIS_USER = 2;
	const RAW = 3;

	/** @var string Wiki ID; false means the current wiki */
	protected $mWiki = false;
	/** @var int|null */
	protected $mId;
	/** @var int|null */
	protected $mPageId;
	/** @var string */
	protected $mUserText;
	/** @var string */
	protected $mOrigUserText;
	/** @var int */
	protected $mUserId;
	/** @var bool */
	protected $mMinorEdit = false;
	/** @var string */
	protected $mTimestamp;
	/** @var int */
	protected $mDeleted = 0;
	/** @var int|null */
	protected $mSize;
	/** @var string|null */
	protected $mSha1;
	/** @var int */
	protected $mParentId;
	/** @var CommentStoreComment */
	protected $mComment;

	/**  @var Title */
	protected $mTitle;

	/** @var RevisionSlots */
	protected $mSlots;

	/** @var array An associative array of transient data associated with this revision object */
	protected $transientData = [];

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 *
	 * @throws MWException
	 */
	function __construct( Title $title, RevisionSlots $slots, $wikiId = false ) {
		Assert::parameterType( 'integer|boolean', $wikiId, '$wikiId' );

		$this->mTitle = $title;
		$this->mSlots = $slots;
		$this->mWiki = $wikiId;
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
	 * @return Content|null The content of the given slot, or null if access is forbidden.
	 * @throws RevisionLookupException
	 */
	public function getContent( $role, $audience = self::FOR_PUBLIC, User $user = null ) {
		// XXX: throwing an exception would be nicer, but would a further
		// departure from the signature of Revision::getContent(), and thus
		// more complex and error prone refactoring.
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return null;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT, $user ) ) {
			return null;
		}

		return $this->getSlot( $role, $audience, $user )->getContent();
	}

	/**
	 * Returns meta-data for the given slot.
	 *
	 * @note This does not perform audience checks!
	 *
	 * @param string $role The role name of the desired slot
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return SlotRecord
	 */
	public function getSlot( $role, $audience = self::FOR_PUBLIC, User $user = null  ) {
		$slot = $this->mSlots->getSlot( $role );

		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return SlotRecord::newWithSuppressedContent( $slot );
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT, $user ) ) {
			return SlotRecord::newWithSuppressedContent( $slot );
		}

		return $slot;
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotNames() {
		return $this->mSlots->getSlotNames();
	}

	/**
	 * Get revision ID
	 *
	 * MCR migration note: this replaces Revision::getId
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * MCR migration note: this replaces Revision::getParentId
	 *
	 * @return int|null
	 */
	public function getParentId() {
		return $this->mParentId;
	}

	/**
	 * Returns the length of the text in this revision.
	 *
	 * MCR migration note: this replaces Revision::getSize
	 *
	 * @return int|null
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * MCR migration note: this replaces Revision::getSha1
	 *
	 * @return string|null
	 */
	public function getSha1() {
		return $this->mSha1;
	}

	/**
	 * Get the page ID. If the page does not yet exist, the page ID is 0.
	 *
	 * MCR migration note: this replaces Revision::getPage
	 *
	 * @return int
	 */
	public function getPageId() {
		if ( $this->mPageId === null ) {
			return $this->getTitle()->getArticleID();
		}
		return $this->mPageId;
	}

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false
	 */
	public function getWikiId() {
		return $this->mWiki;
	}

	/**
	 * Returns the title of the page this revision is associated with.
	 *
	 * MCR migration note: this replaces Revision::getTitle
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * MCR migration note: this replaces Revision::getUser
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int|null
	 */
	public function getUserId( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return 0;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return 0;
		} else {
			return $this->mUserId;
		}
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
	 *
	 * MCR migration note: this replaces Revision::getUserText
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string|null
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return ''; // XXX: ugly, but we keep it for compatibility with Revision's old behavior
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return ''; // XXX: ugly, but we keep it for compatibility with Revision's old behavior
		} else {
			if ( $this->mUserText === null ) {
				$this->mUserText = User::whoIs( $this->mUserId ); // load on demand // FIXME! Don't!
				if ( $this->mUserText === false ) {
					# This shouldn't happen, but it can if the wiki was recovered
					# via importing revs and there is no user table entry yet.
					$this->mUserText = $this->mOrigUserText; // FIXME: don't do this here!
				}
			}
			return $this->mUserText;
		}
	}

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an
	 * empty string will be returned.
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
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return CommentStoreComment::newEmpty(); // FIXME
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_COMMENT, $user ) ) {
			return CommentStoreComment::newEmpty(); // FIXME
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
	 * MCR migration note: this replaces Revision::getTimestamp
	 *
	 * @return string|null
	 */
	public function getTimestamp() {
		return $this->mTimestamp;
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
		return self::userCanBitfield( $this->getVisibility(), $field, $user, $this->getTitle() );
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
			$permissionlist = implode( ', ', $permissions );
			if ( $title === null ) {
				wfDebug( "Checking for $permissionlist due to $field match on $bitfield\n" );
				return call_user_func_array( [ $user, 'isAllowedAny' ], $permissions );
			} else {
				$text = $title->getPrefixedText();
				wfDebug( "Checking for $permissionlist on $text due to $field match on $bitfield\n" );
				foreach ( $permissions as $perm ) {
					if ( $title->userCan( $perm, $user ) ) {
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
	 * @param string $key
	 * @param mixed $value
	 */
	public function setTransientData( $key, $value ) {
		$this->transientData[$key] = $value;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getTransientData( $key, $default = null ) {
		return array_key_exists( $key, $this->transientData )
			? $this->transientData[$key]
			: $default;
	}

}
