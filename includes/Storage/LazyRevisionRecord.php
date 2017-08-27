<?php

namespace MediaWiki\Storage;

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

use Content;
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
 */
class LazyRevisionRecord implements RevisionRecord {
	/** @var string Wiki ID; false means the current wiki */
	protected $mWiki = false;
	/** @var int|null */
	protected $mId;
	/** @var int|null */
	protected $mPage;
	/** @var string */
	protected $mUserText;
	/** @var string */
	protected $mOrigUserText;
	/** @var int */
	protected $mUser;
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

	/** @var bool */
	protected $mCurrent;

	/** @var object[]|callable */
	protected $mSlots;

	/** @var bool|callable Used for cached values to reload mutable fields*/
	protected $mRefreshMutableFields = null;

	/**
	 * @note Avoid calling this constructor directly. Use teh appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param object|array $row Either a database row or an array.
	 *        Structure is subject to change without notice!
	 * @param object[]|callable $slots content rows from the database (one per slot),
	 *        or a callback that returns content rows from the database. Key are slot names.
	 *        Structure is subject to change without notice!
	 * @param bool|string $wikiId
	 *
	 * @throws MWException
	 */
	function __construct( $row, $slots, $wikiId = false ) {
		Assert::parameterType( 'array|object', $row, '$row' );
		Assert::parameterType( 'array|callable', $slots, '$slots' );

		$this->mSlots = $slots;
		$this->mWiki = $wikiId;

		if ( is_object( $row ) ) {
			$this->mId = intval( $row->rev_id );
			$this->mPage = intval( $row->rev_page );
			$this->mComment = $row->rev_comment;
			$this->mUser = intval( $row->rev_user );
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
				// FIXME: remember page_xxx hint
				$this->mCurrent = ( $row->rev_id == $row->page_latest );
			} else {
				$this->mCurrent = false;
			}

			// Use user_name for users and rev_user_text for IPs...
			$this->mUserText = null; // lazy load if left null
			if ( $this->mUser == 0 ) {
				$this->mUserText = $row->rev_user_text; // IP user
			} elseif ( isset( $row->user_name ) ) {
				$this->mUserText = $row->user_name; // logged-in user
			}
			$this->mOrigUserText = $row->rev_user_text;

			// FIXME: remember old_text hint, etc... full row?
		} elseif ( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser; // ugh

			$this->mId = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$this->mPage = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$this->mUserText = isset( $row['user_text'] )
				? strval( $row['user_text'] ) : $wgUser->getName();
			$this->mUser = isset( $row['user'] ) ? intval( $row['user'] ) : $wgUser->getId();
			$this->mMinorEdit = isset( $row['minor_edit'] ) ? intval( $row['minor_edit'] ) : 0;
			$this->mTimestamp = isset( $row['timestamp'] )
				? strval( $row['timestamp'] ) : wfTimestampNow();
			$this->mDeleted = isset( $row['deleted'] ) ? intval( $row['deleted'] ) : 0;
			$this->mSize = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$this->mParentId = isset( $row['parent_id'] ) ? intval( $row['parent_id'] ) : null;
			$this->mSha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			// Enforce spacing trimming on supplied text
			$this->mComment = isset( $row['comment'] ) ? trim( strval( $row['comment'] ) ) : null;

			/** @var Title $title */
			$title = isset( $row['title'] ) ? $row['title'] : null;

			// If we have a Title object, make sure it is consistent with mPage.
			if ( $title && $title->exists() ) {
				if ( $this->mPage === null ) {
					// if the page ID wasn't known, set it now
					$this->mPage = $title->getArticleID();
				} elseif ( $title->getArticleID() !== $this->mPage ) {
					// Got different page IDs. This may be legit (e.g. during undeletion),
					// but it seems worth mentioning it in the log.
					wfDebug( "Page ID " . $this->mPage . " mismatches the ID " .
						$title->getArticleID() . " provided by the Title object." );
				}

				// FIXME: remember Title hint
			}

			$this->mCurrent = false;
		} else {
			throw new MWException( 'RevisionRecord constructor passed invalid row format.' );
		}
	}

	/**
	 * @param object &$row
	 *
	 * @throws RevisionLookupException
	 * @return Content
	 */
	private function resolveRowContent( &$row ) {
		if ( isset( $row->cont_object ) && $row->cont_object instanceof Content ) {
			return $row->cont_object;
		}

		if ( !isset( $row->cont_callback ) ) {
			throw new OutOfBoundsException(
				'Slot info has no cont_object nor a cont_callback field.'
			);
		}

		// TODO: document callback signature
		$obj = call_user_func( $row->cont_callback, $this, $row );

		Assert::postcondition(
			$obj instanceof Content,
			'Slot content callback should return a Content object'
		);

		$row->cont_object = $obj;
		return $row->cont_object;
	}

	/**
	 * Returns the Content of the given slot of this revision.
	 * Call getSlotNames() to get a list of available slots.
	 *
	 * @param string $slot
	 *
	 * @throws RevisionLookupException
	 * @return Content
	 */
	public function getContent( $slot ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$slot] ) ) {
			return $this->resolveRowContent( $slots[$slot] );
		} else {
			throw new RevisionLookupException( 'No such slot: ' . $slot );
		}
	}

	/**
	 * Returns the slot names (roles) of all slots present in this revision.
	 * getContent() will succeed only for the names returned by this method.
	 *
	 * @return string[]
	 */
	public function getSlotNames() {
		$slots = $this->getSlots();
		return array_keys( $slots );
	}

	/**
	 * @return object[] revision slot/content rows
	 */
	private function getSlots() {
		if ( is_callable( $this->mSlots ) ) {
			// TODO: document callback signature
			$this->mSlots = call_user_func( $this->mSlots, $this );
			Assert::postcondition(
				is_array( $this->mSlots ),
				'Slots info callback should return an array of objects'
			);
		}

		return $this->mSlots;
	}

	/**
	 * Get revision ID
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int|null
	 */
	public function getParentId() {
		return $this->mParentId;
	}

	/**
	 * Returns the length of the text in this revision.
	 *
	 * @return int
	 */
	public function getSize() {

		// If length is null, calculate it (potentially SLOW!).
		if ( $this->mSize === null  ) {
			$slots = $this->getSlots();

			$this->mSize = array_reduce( $slots, function ( $accu, $row ) {
				if ( !isset( $row->cont_size ) ) {
					$content = $this->resolveRowContent( $row );
					$row->cont_size = $content->getSize();
				}

				return $accu + $row->cont_size;
			}, 0 );
		}

		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * @return string
	 */
	public function getSha1() {
		// If hash is null, calculate it (potentially SLOW!)
		if ( $this->mSha1 === null  ) {
			$slots = $this->getSlots();
			ksort( $slots );

			// FIXME: centralize this algorithm!
			$this->mSha1 = array_reduce( $slots, function ( $accu, $row ) {
				if ( !isset( $row->cont_sha1 ) ) {
					$content = $this->resolveRowContent( $row );
					$row->cont_sha1 = RevisionStore::base36Sha1( $content->serialize() );
				}

				return $accu === null
					? $row->cont_sha1
					: RevisionStore::base36Sha1( $accu . $row->cont_sha1 );
			}, null );
		}

		return $this->mSha1;
	}

	/**
	 * Get the page ID
	 *
	 * @return int|null
	 */
	public function getPage() {
		return $this->mPage;
	}

	/**
	 * Get the wiki ID
	 *
	 * @return int|null
	 */
	public function getWiki() {
		return $this->mWiki;
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return 0;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return 0;
		} else {
			return $this->mUser;
		}
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
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
		$this->refresh();

		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $user ) ) {
			return '';
		} else {
			if ( $this->mUserText === null ) {
				$this->mUserText = User::whoIs( $this->mUser ); // load on demand
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
	 * @return bool
	 */
	public function isMinor() {
		return (bool)$this->mMinorEdit;
	}

	/**
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
	 * @return int
	 */
	public function getVisibility() {
		$this->refresh();

		return (int)$this->mDeleted;
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * @return bool
	 */
	public function isCurrent() {
		return $this->mCurrent;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	protected function userCan( $field, User $user = null ) {
		return self::userCanBitfield( $this->getVisibility(), $field, $user );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * @param int $bitfield Current field
	 * @param int $field One of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @param Title|null $title A Title object to check for per-page restrictions on,
	 *                          instead of just plain userrights
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, User $user = null,
		Title $title = null
	) {
		if ( $bitfield & $field ) { // aspect is deleted
			if ( $user === null ) {
				global $wgUser;
				$user = $wgUser;
			}
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
	 * Provide a callback for refreshing mutable fields on demand (once).
	 *
	 * @param callable $refresh
	 */
	public function setRefreshCallback( callable $refresh ) {
		$this->mRefreshMutableFields = $refresh;
	}

	/**
	 * For cached revisions, make sure mutable fields are up-to-date.
	 */
	private function refresh() {
		if ( !$this->mRefreshMutableFields ) {
			return; // not needed
		}

		$row = call_user_func( $this->mRefreshMutableFields, $this );

		if ( $row ) { // update values
			$this->mDeleted = (int)$row->rev_deleted;
			$this->mUserText = $row->user_name;

			$this->mRefreshMutableFields = null;
		}
	}

}
