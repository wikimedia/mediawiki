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
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * Lazy loading representation of a page revision.
 * Callbacks are used for lazy loading, so this class
 * has no knowledge of the actual storage mechanism.
 *
 * @todo split off an alternative implementation that is fully initialized via the constructor
 */
class LazyRevisionRecord implements RevisionRecord {
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
	/** @var int */
	protected $mSize;
	/** @var string */
	protected $mSha1;
	/** @var int */
	protected $mParentId;
	/** @var string */
	protected $mComment;
	/** @var string */
	protected $mText;
	/** @var int */
	protected $mTextId;

	/** @var object|null */
	protected $mTextRow;

	/** @var callable|null */
	protected $mTextCallback = null;

	/**  @var null|Title */
	protected $mTitle;

	/** @var bool */
	protected $mCurrent;

	/** @var object[]|callable */
	protected $mSlots;

	/** @var bool|callable Used for cached values to reload mutable fields*/
	protected $mRefreshMutableFields = null;

	/** @var string Wiki ID; false means the current wiki */
	protected $mWiki = false;

	/**
	 * @param object|array $row Either a database row or an array
	 * @param object[]|callable $slots content rows from the database (one per slot),
	 *        or a callback that returns content rows from the database. Key are slot names.
	 *        FIXME: document structure / indirection.
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
			$this->mTextId = intval( $row->rev_text_id );
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
				$this->mCurrent = ( $row->rev_id == $row->page_latest );
				$this->mTitle = Title::newFromRow( $row );
			} else {
				$this->mCurrent = false;
				$this->mTitle = null;
			}

			if ( !isset( $row->rev_content_model ) ) {
				$this->mContentModel = null; # determine on demand if needed
			} else {
				$this->mContentModel = strval( $row->rev_content_model );
			}

			if ( !isset( $row->rev_content_format ) ) {
				$this->mContentFormat = null; # determine on demand if needed
			} else {
				$this->mContentFormat = strval( $row->rev_content_format );
			}

			// Lazy extraction...
			$this->mText = null;
			if ( isset( $row->old_text ) ) {
				$this->mTextRow = $row;
			} else {
				// 'text' table row entry will be lazy-loaded
				$this->mTextRow = null;
			}

			// Use user_name for users and rev_user_text for IPs...
			$this->mUserText = null; // lazy load if left null
			if ( $this->mUser == 0 ) {
				$this->mUserText = $row->rev_user_text; // IP user
			} elseif ( isset( $row->user_name ) ) {
				$this->mUserText = $row->user_name; // logged-in user
			}
			$this->mOrigUserText = $row->rev_user_text;
		} elseif ( is_array( $row ) ) {
			// Build a new revision to be saved...
			global $wgUser; // ugh

			# if we have a content object, use it to set the model and type
			if ( !empty( $row['content'] ) ) {
				// @todo when is that set? test with external store setup! check out insertOn() [dk]
				if ( !empty( $row['text_id'] ) ) {
					throw new MWException( "Text already stored in external store (id {$row['text_id']}), " .
						"can't serialize content object" );
				}

				$row['content_model'] = $row['content']->getModel();
				# note: mContentFormat is initializes later accordingly
				# note: content is serialized later in this method!
				# also set text to null?
			}

			$this->mId = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$this->mPage = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$this->mTextId = isset( $row['text_id'] ) ? intval( $row['text_id'] ) : null;
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

			$this->mContentModel = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;
			$this->mContentFormat = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;

			// Enforce spacing trimming on supplied text
			$this->mComment = isset( $row['comment'] ) ? trim( strval( $row['comment'] ) ) : null;
			$this->mText = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			$this->mTextRow = null;

			$this->mTitle = isset( $row['title'] ) ? $row['title'] : null;

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( '`content` field must contain a Content object.' );
				}

				$handler = $this->getContentHandler();
				$this->mContent = $row['content'];

				$this->mContentModel = $this->mContent->getModel();
				$this->mContentHandler = null;

				$this->mText = $handler->serializeContent( $row['content'], $this->getContentFormat() );
			} elseif ( $this->mText !== null ) {
				$handler = $this->getContentHandler();
				$this->mContent = $handler->unserializeContent( $this->mText );
			}

			// If we have a Title object, make sure it is consistent with mPage.
			if ( $this->mTitle && $this->mTitle->exists() ) {
				if ( $this->mPage === null ) {
					// if the page ID wasn't known, set it now
					$this->mPage = $this->mTitle->getArticleID();
				} elseif ( $this->mTitle->getArticleID() !== $this->mPage ) {
					// Got different page IDs. This may be legit (e.g. during undeletion),
					// but it seems worth mentioning it in the log.
					wfDebug( "Page ID " . $this->mPage . " mismatches the ID " .
						$this->mTitle->getArticleID() . " provided by the Title object." );
				}
			}

			$this->mCurrent = false;

			// If we still have no length, see it we have the text to figure it out
			if ( !$this->mSize && $this->mContent !== null ) {
				$this->mSize = $this->mContent->getSize();
			}

			// Same for sha1
			if ( $this->mSha1 === null ) {
				$this->mSha1 = $this->mText === null ? null : RevisionStore::base36Sha1( $this->mText );
			}

			// force lazy init
			$this->getContentModel();
			$this->getContentFormat();
		} else {
			throw new MWException( 'RevisionRecord constructor passed invalid row format.' );
		}
	}

	/**
	 * @param string $slot
	 *
	 * @throws RevisionLookupException
	 * @return Content
	 */
	public function getContent( $slot ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$slot] ) ) {
			if ( isset( $slots[$slot]->cont_callback ) ) {
				// TODO: document callback signature
				$obj = call_user_func( $slots[$slot]->cont_callback, $this, $slots[$slot] );

				Assert::postcondition(
					$obj instanceof Content,
					'Slot content callback should return a Content object'
				);
				$slots[$slot]->cont_object = $obj;
			}

			if ( isset( $slots[$slot]->cont_object ) ) {
				return $slots[$slot]->cont_object;
			} else {
				throw new RevisionLookupException(
					'Slot info for slot `' . $slot . '` has no cont_model field.'
				);
			}
		} else {
			throw new RevisionLookupException( 'No such slot: ' . $slot );
		}
	}

	/**
	 * @param string $slot
	 *
	 * @throws RevisionLookupException
	 * @return string
	 */
	public function getContentModel( $slot ) {
		$slots = $this->getSlots();

		if ( isset( $slots[$slot] ) ) {
			if ( isset( $slots[$slot]->cont_model ) ) {
				return $slots[$slot]->cont_model;
			} else {
				throw new RevisionLookupException(
					'Slot info for slot `' . $slot . '` has no cont_model field.'
				);
			}
		} else {
			throw new RevisionLookupException( 'No such slot: ' . $slot );
		}
	}

	/**
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
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @return int|null
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision, or null if unknown.
	 *
	 * @return string|null
	 */
	public function getSha1() {
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

	public function setRefreshCallback( callable $refresh ) {
		$this->mRefreshMutableFields = $refresh;
	}

	/**
	 * For cached revisions, make sure mutable fields are up-to-date
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
