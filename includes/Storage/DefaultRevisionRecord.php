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

use Content;
use InvalidArgumentException;
use MWException;
use OutOfBoundsException;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * Lazy loading representation of a page revision.
 *
 * Callbacks are used for lazy loading, so this class
 * has no knowledge of the actual storage mechanism.
 *
 * @since 1.30
 */
class DefaultRevisionRecord implements RevisionRecord, TransientDataAccess {
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
	protected $mMinorEdit;
	/** @var string */
	protected $mTimestamp;
	/** @var int */
	protected $mDeleted;
	/** @var int|null */
	protected $mSize;
	/** @var string|null */
	protected $mSha1;
	/** @var int */
	protected $mParentId;
	/** @var string */
	protected $mComment;

	/**  @var Title */
	protected $mTitle;
	/** @var bool */
	protected $mCurrent;

	/** @var RevisionSlots */
	protected $mSlots;

	/** @var array An associative array of transient data associated with this revision object */
	protected $transientData = [];

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param object|array $row Either a database row or an array.
	 *        Structure is subject to change without notice!
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 *
	 * @throws MWException
	 */
	function __construct( Title $title, $row, RevisionSlots $slots, $wikiId = false ) {
		Assert::parameterType( 'array|object', $row, '$row' );
		Assert::parameterType( 'integer|bool', $wikiId, '$wikiId' );

		$this->mTitle = $$title;
		$this->mSlots = $slots;
		$this->mWiki = $wikiId;

		// TODO: MCR: move crazy dual constructor logic to separate methods in RevisionStore
		if ( is_object( $row ) ) {
			$this->mId = intval( $row->rev_id );
			$this->mPageId = intval( $row->rev_page );
			$this->mComment = $row->rev_comment;
			$this->mUserId = intval( $row->rev_user );
			$this->mMinorEdit = intval( $row->rev_minor_edit );
			$this->mTimestamp = $row->rev_timestamp;
			$this->mDeleted = intval( $row->rev_deleted );

			if ( !isset( $row->rev_parent_id ) ) {
				$this->mParentId = null;
			} else {
				$this->mParentId = intval( $row->rev_parent_id );
			}

			if ( !isset( $row->rev_len ) ) {
				$this->mSize = null;
			} else {
				$this->mSize = intval( $row->rev_len );
			}

			if ( !isset( $row->rev_sha1 ) ) {
				$this->mSha1 = null;
			} else {
				$this->mSha1 = $row->rev_sha1;
			}

			if ( isset( $row->page_latest ) ) {
				$this->mCurrent = ( $row->rev_id == $row->page_latest );
			} else {
				$this->mCurrent = false;
			}

			if ( isset( $row->page_id ) ) {
				$this->setTransientData( 'page_row', $row );
			}

			// Use user_name for users and rev_user_text for IPs...
			$this->mUserText = null; // lazy load if left null
			if ( $this->mUserId == 0 ) {
				$this->mUserText = $row->rev_user_text; // IP user
			} elseif ( isset( $row->user_name ) ) {
				$this->mUserText = $row->user_name; // logged-in user
			}
			$this->mOrigUserText = $row->rev_user_text;

			$this->setTransientData( 'revision_row', $row );
		} elseif ( is_array( $row ) ) {
			// Build a new revision to be saved...
			$this->mId = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$this->mPageId = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$this->mUserText = isset( $row['user_text'] ) ? strval( $row['user_text'] ) : null;
			$this->mUserId = isset( $row['user'] ) ? intval( $row['user'] ) : null;
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? intval( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp'] )
				? strval( $row['timestamp'] ) : wfTimestampNow();
			$this->mDeleted = isset( $row['deleted'] ) ? intval( $row['deleted'] ) : 0;
			$this->mSize = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$this->mParentId = isset( $row['parent_id'] ) ? intval( $row['parent_id'] ) : null;
			$this->mSha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			// Enforce spacing trimming on supplied text
			// TODO: MCR: allow a CommentStoreComment or Message here.
			$this->mComment = isset( $row['comment'] ) ? trim( strval( $row['comment'] ) ) : null;

			/** @var Title $title */
			$title = isset( $row['title'] ) ? $row['title'] : null;

			// If we have a Title object, make sure it is consistent with mPage.
			if ( $title && $title->exists() ) {
				if ( $this->mPageId === null ) {
					// if the page ID wasn't known, set it now
					$this->mPageId = $title->getArticleID();
				} elseif ( $title->getArticleID() !== $this->mPageId ) {
					// Got different page IDs. This may be legit (e.g. during undeletion),
					// but it seems worth mentioning it in the log.
					wfDebug( "Page ID " . $this->mPageId . " mismatches the ID " .
						$title->getArticleID() . " provided by the Title object." );
				}

				$this->setTransientData( 'title', $title );
			}

			$this->mCurrent = false;
		} else {
			throw new MWException( 'RevisionRecord constructor passed invalid row format.' );
		}

		if ( $this->mPageId && $this->mPageId !== $this->mTitle->getArticleID()) {
			throw new InvalidArgumentException(
				'The given Title does not belong to page ID ' . $this->mPageId
			);
		}
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
	 * @param string $slot The role name of the desired slot
	 * @param int $audience
	 * @param User $user
	 *
	 * @return Content
	 * @throws RevisionLookupException
	 */
	public function getContent( $slot, $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return null;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT, $user ) ) {
			return null;
		}

		return $this->mSlots->getContent( $slot );
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
	 * @return SlotRecord[] revision slot/content rows
	 */
	public function getSlots() {
		return $this->mSlots->getSlots();
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
	 * @return int
	 */
	public function getSize() {

		// If length is null, calculate it (potentially SLOW!).
		if ( $this->mSize === null  ) {
			$this->mSize = $this->mSlots->computeSize();
		}

		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * MCR migration note: this replaces Revision::getSha1
	 *
	 * @return string
	 */
	public function getSha1() {
		// If hash is null, calculate it (potentially SLOW!)
		if ( $this->mSha1 === null  ) {
			$this->mSha1 = $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

	/**
	 * Get the page ID
	 *
	 * MCR migration note: this replaces Revision::getPage
	 *
	 * @return int|null
	 */
	public function getPageId() {
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
	 * @return int
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
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return '';
		} else {
			if ( $this->mUserText === null ) {
				$this->mUserText = User::whoIs( $this->mUserId ); // load on demand
				if ( $this->mUserText === false ) {
					# This shouldn't happen, but it can if the wiki was recovered
					# via importing revs and there is no user table entry yet.
					$this->mUserText = $this->mOrigUserText;
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
	 * @return string
	 */
	function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_COMMENT, $user ) ) {
			return '';
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
		if ( $this->isCurrent() && $field === self::DELETED_TEXT ) {
			// Current revisions of pages cannot have the content hidden. Skipping this
			// check is very useful for Parser as it fetches templates using newKnownCurrent().
			// Calling getVisibility() in that case triggers a verification database query.
			return false; // no need to check
		}

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
	 * @return string
	 */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * MCR migration note: this replaces Revision::isCurrent
	 *
	 * @return bool
	 */
	public function isCurrent() {
		return $this->mCurrent;
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
	 * @param User $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	protected function userCan( $field, User $user ) {
		return self::userCanBitfield( $this->getVisibility(), $field, $user );
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
	 * @param User $user User object to check, or null to use $wgUser
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
