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
 * @todo RevisionRecord is probably not needed as a separate interface.
 * It's useful for now to be explicit about what should be exposed, and what shouldn't.
 *
 * Callbacks are used for lazy loading, so this class
 * has no knowledge of the actual storage mechanism.
 *
 * @since 1.31
 */
class RevisionStoreRecord extends RevisionRecord {

	/** @var bool */
	protected $mCurrent;

	/**
	 * Constructs a RevisionStoreRecord for a known revision based on pre-1.30 array syntax.
	 *
	 * @param Title $title
	 * @param CommentStoreComment $comment
	 * @param array $array
	 * @param RevisionSlots $slots
	 * @param bool $wikiId
	 * @return RevisionStoreRecord
	 */
	public static function newFromArray_1_29( Title $title, CommentStoreComment $comment, array $array, RevisionSlots $slots, $wikiId = false ) {
		Assert::parameterType( 'array', $array, '$row' );

		$row = (object)[];

		$row->rev_id = intval( $array['id'] );
		$row->rev_page = intval( $array['page'] );
		$row->rev_user_text = strval( $array['user_text'] );
		$row->rev_user = intval( $array['user'] );
		$row->rev_minor = isset( $array['minor_edit'] ) ? intval( $array['minor_edit'] ) : 0;
		$row->rev_timestamp = strval( $array['timestamp'] );
		$row->rev_deleted = isset( $array['deleted'] ) ? intval( $array['deleted'] ) : 0;
		$row->rev_size = isset( $array['len'] ) ? intval( $array['len'] ) : null;
		$row->rev_parent_id = isset( $array['parent_id'] ) ? intval( $array['parent_id'] ) : 0;
		$row->rev_sha1 = isset( $array['sha1'] ) ? strval( $array['sha1'] ) : null;

		return new RevisionStoreRecord( $title, $comment, $row, $slots, $wikiId );
	}

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param CommentStoreComment $comment
	 * @param object|array $row Either a database row or an array.
	 *        Structure is subject to change without notice!
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 */
	function __construct( Title $title, CommentStoreComment $comment, $row, RevisionSlots $slots, $wikiId = false ) {
		parent::__construct( $title, $slots, $wikiId );
		Assert::parameterType( 'object', $row, '$row' );

		$this->mId = intval( $row->rev_id );
		$this->mPageId = intval( $row->rev_page );
		$this->mComment = $comment;

		$this->mUserId = intval( $row->rev_user );
		$this->mMinorEdit = intval( $row->rev_minor_edit );
		$this->mTimestamp = wfTimestamp( TS_MW, $row->rev_timestamp );
		$this->mDeleted = intval( $row->rev_deleted );

		if ( !isset( $row->rev_parent_id ) ) {
			$this->mParentId = 0;
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
		$this->mUserText = null; // lazy load if left null // FIXME: callback!
		if ( $this->mUserId == 0 ) {
			$this->mUserText = $row->rev_user_text; // IP user
		} elseif ( isset( $row->user_name ) ) {
			$this->mUserText = $row->user_name; // logged-in user
		}
		$this->mOrigUserText = $row->rev_user_text;

		$this->setTransientData( 'revision_row', $row );

		if ( $this->mPageId && $this->mPageId !== $this->mTitle->getArticleID()) {
			throw new InvalidArgumentException(
				'The given Title does not belong to page ID ' . $this->mPageId
			);
		}
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

		return parent::isDeleted( $field );
	}

	/**
	 * @return int The revision id, never null.
	 */
	public function getId() {
		// overwritten just to add a guarantee to the contract
		return parent::getId();
	}

	/**
	 * @return int The ID of the parent revision, never null. May be 0 if there is no
	 *         parent revision, or the parent revision was not tracked.
	 */
	public function getParentId() {
		// overwritten just to add a guarantee to the contract
		return parent::getParentId();
	}

	/**
	 * @return string the nominal revision size, never null
	 */
	public function getSize() {
		// If length is null, calculate and remember it (potentially SLOW!).
		// This is for compatibility with old database rows that don't have the field set.
		if ( $this->mSize === null  ) {
			$this->mSize = $this->mSlots->computeSize();
		}

		return $this->mSize;
	}

	/**
	 * @return string the revision hash, never null
	 */
	public function getSha1() {
		// If hash is null, calculate it and remember (potentially SLOW!)
		// This is for compatibility with old database rows that don't have the field set.
		if ( $this->mSha1 === null  ) {
			$this->mSha1 = $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return int The user ID, never null. May be 0 if the user is anonymous or unknonw.
	 */
	public function getUserId( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserId( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return string The user text, never null. Empty if access is forbidden.
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserText( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return CommentStoreComment The revision comment, never null. Empty if access is forbidden.
	 */
	public function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getComment( $audience, $user );
	}

	/**
	 * @return string timestamp, never null
	 */
	public function getTimestamp() {
		// overwritten just to add a guarantee to the contract
		return parent::getTimestamp();
	}

}
