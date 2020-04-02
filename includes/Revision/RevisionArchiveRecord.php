<?php
/**
 * A RevisionRecord representing a revision of a deleted page persisted in the archive table.
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
use MediaWiki\User\UserIdentity;
use MWTimestamp;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * A RevisionRecord representing a revision of a deleted page persisted in the archive table.
 * Most getters on RevisionArchiveRecord will never return null. However, getId() and
 * getParentId() may indeed return null if this information was not stored when the archive entry
 * was created.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionArchiveRecord
 */
class RevisionArchiveRecord extends RevisionRecord {

	/**
	 * @var int
	 */
	protected $mArchiveId;

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param object $row An archive table row. Use RevisionStore::getArchiveQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $dbDomain DB domain of the relevant wiki or false for the current one.
	 */
	public function __construct(
		Title $title,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$dbDomain = false
	) {
		parent::__construct( $title, $slots, $dbDomain );
		Assert::parameterType( 'object', $row, '$row' );

		$timestamp = MWTimestamp::convert( TS_MW, $row->ar_timestamp );
		Assert::parameter( is_string( $timestamp ), '$row->rev_timestamp', 'must be a valid timestamp' );

		$this->mArchiveId = intval( $row->ar_id );

		// NOTE: ar_page_id may be different from $this->mTitle->getArticleID() in some cases,
		// notably when a partially restored page has been moved, and a new page has been created
		// with the same title. Archive rows for that title will then have the wrong page id.
		$this->mPageId = isset( $row->ar_page_id ) ? intval( $row->ar_page_id ) : $title->getArticleID();

		// NOTE: ar_parent_id = 0 indicates that there is no parent revision, while null
		// indicates that the parent revision is unknown. As per MW 1.31, the database schema
		// allows ar_parent_id to be NULL.
		$this->mParentId = isset( $row->ar_parent_id ) ? intval( $row->ar_parent_id ) : null;
		$this->mId = isset( $row->ar_rev_id ) ? intval( $row->ar_rev_id ) : null;
		$this->mComment = $comment;
		$this->mUser = $user;
		$this->mTimestamp = $timestamp;
		$this->mMinorEdit = boolval( $row->ar_minor_edit );
		$this->mDeleted = intval( $row->ar_deleted );
		$this->mSize = isset( $row->ar_len ) ? intval( $row->ar_len ) : null;
		$this->mSha1 = !empty( $row->ar_sha1 ) ? $row->ar_sha1 : null;
	}

	/**
	 * Get archive row ID
	 *
	 * @return int
	 */
	public function getArchiveId() {
		return $this->mArchiveId;
	}

	/**
	 * @return int|null The revision id, or null if the original revision ID
	 *         was not recorded in the archive table.
	 */
	public function getId() {
		// overwritten just to refine the contract specification.
		return parent::getId();
	}

	/**
	 * @throws RevisionAccessException if the size was unknown and could not be calculated.
	 * @return int The nominal revision size, never null. May be computed on the fly.
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
	 * @return string never null
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
class_alias( RevisionArchiveRecord::class, 'MediaWiki\Storage\RevisionArchiveRecord' );
