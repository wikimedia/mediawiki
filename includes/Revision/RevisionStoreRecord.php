<?php
/**
 * A RevisionRecord representing an existing revision persisted in the revision table.
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
use InvalidArgumentException;
use MediaWiki\User\UserIdentity;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * A RevisionRecord representing an existing revision persisted in the revision table.
 * RevisionStoreRecord has no optional fields, getters will never return null.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionStoreRecord
 */
class RevisionStoreRecord extends RevisionRecord {

	/** @var bool */
	protected $mCurrent = false;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param object $row A row from the revision table. Use RevisionStore::getQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 */
	function __construct(
		Title $title,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiId = false
	) {
		parent::__construct( $title, $slots, $wikiId );
		Assert::parameterType( 'object', $row, '$row' );

		$this->mId = intval( $row->rev_id );
		$this->mPageId = intval( $row->rev_page );
		$this->mComment = $comment;

		$timestamp = wfTimestamp( TS_MW, $row->rev_timestamp );
		Assert::parameter( is_string( $timestamp ), '$row->rev_timestamp', 'must be a valid timestamp' );

		$this->mUser = $user;
		$this->mMinorEdit = boolval( $row->rev_minor_edit );
		$this->mTimestamp = $timestamp;
		$this->mDeleted = intval( $row->rev_deleted );

		// NOTE: rev_parent_id = 0 indicates that there is no parent revision, while null
		// indicates that the parent revision is unknown. As per MW 1.31, the database schema
		// allows rev_parent_id to be NULL.
		$this->mParentId = isset( $row->rev_parent_id ) ? intval( $row->rev_parent_id ) : null;
		$this->mSize = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
		$this->mSha1 = !empty( $row->rev_sha1 ) ? $row->rev_sha1 : null;

		// NOTE: we must not call $this->mTitle->getLatestRevID() here, since the state of
		// page_latest may be in limbo during revision creation. In that case, calling
		// $this->mTitle->getLatestRevID() would cause a bad value to be cached in the Title
		// object. During page creation, that bad value would be 0.
		if ( isset( $row->page_latest ) ) {
			$this->mCurrent = ( $row->rev_id == $row->page_latest );
		}

		// sanity check
		if (
			$this->mPageId && $this->mTitle->exists()
			&& $this->mPageId !== $this->mTitle->getArticleID()
		) {
			throw new InvalidArgumentException(
				'The given Title does not belong to page ID ' . $this->mPageId .
				' but actually belongs to ' . $this->mTitle->getArticleID()
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

	protected function userCan( $field, User $user ) {
		if ( $this->isCurrent() && $field === self::DELETED_TEXT ) {
			// Current revisions of pages cannot have the content hidden. Skipping this
			// check is very useful for Parser as it fetches templates using newKnownCurrent().
			// Calling getVisibility() in that case triggers a verification database query.
			return true; // no need to check
		}

		return parent::userCan( $field, $user );
	}

	/**
	 * @return int The revision id, never null.
	 */
	public function getId() {
		// overwritten just to add a guarantee to the contract
		return parent::getId();
	}

	/**
	 * @throws RevisionAccessException if the size was unknown and could not be calculated.
	 * @return string The nominal revision size, never null. May be computed on the fly.
	 */
	public function getSize() {
		// If length is null, calculate and remember it (potentially SLOW!).
		// This is for compatibility with old database rows that don't have the field set.
		if ( $this->mSize === null ) {
			$this->mSize = $this->mSlots->computeSize();
		}

		return $this->mSize;
	}

	/**
	 * @throws RevisionAccessException if the hash was unknown and could not be calculated.
	 * @return string The revision hash, never null. May be computed on the fly.
	 */
	public function getSha1() {
		// If hash is null, calculate it and remember (potentially SLOW!)
		// This is for compatibility with old database rows that don't have the field set.
		if ( $this->mSha1 === null ) {
			$this->mSha1 = $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return UserIdentity The identity of the revision author, null if access is forbidden.
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUser( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return CommentStoreComment The revision comment, null if access is forbidden.
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

	/**
	 * @see RevisionStore::isComplete
	 *
	 * @return bool always true.
	 */
	public function isReadyForInsertion() {
		return true;
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.32
 */
class_alias( RevisionStoreRecord::class, 'MediaWiki\Storage\RevisionStoreRecord' );
