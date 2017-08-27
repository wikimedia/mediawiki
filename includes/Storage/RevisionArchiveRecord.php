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

namespace MediaWiki\Storage;

use CommentStoreComment;
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
	 * @param CommentStoreComment $comment
	 * @param object $row An archive table row
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 */
	function __construct(
		Title $title,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiId = false
	) {
		parent::__construct( $title, $slots, $wikiId );
		Assert::parameterType( 'object', $row, '$row' );

		$this->mArchiveId = intval( $row->ar_id );

		// NOTE: ar_page_id may be different from $this->mTitle->getArticleID() in some cases,
		// notably when a partially restored page has been moved, and a new page has been created
		// with the same title. Archive rows for that title will then have the wrong page id.
		$this->mPageId = isset( $row->ar_page_id ) ? intval( $row->ar_page_id ) : $title->getArticleID();

		$this->mParentId = isset( $row->ar_parent_id ) ? intval( $row->ar_parent_id ) : null;
		$this->mId = isset( $row->ar_rev_id ) ? intval( $row->ar_rev_id ) : null;
		$this->mComment = $comment;
		$this->mUserId = intval( $row->ar_user );
		$this->mUserText = $row->ar_user_text;
		$this->mTimestamp = wfTimestamp( TS_MW, $row->ar_timestamp );
		$this->mMinorEdit = boolval( $row->ar_minor_edit );
		$this->mDeleted = intval( $row->ar_deleted );
		$this->mSize = intval( $row->ar_len );
		$this->mSha1 = isset( $row->ar_sha1 ) ? $row->ar_sha1 : null;
	}

	/**
	 * Get archive row ID
	 *
	 * @return int
	 */
	public function getArchiveId() {
		return $this->mId;
	}

	/**
	 * @return int|null The revision id, or null if the original revision ID
	 *         was not recorded in the archive table.
	 * .
	 */
	public function getId() {
		// overwritten just to refien the contract specification.
		return parent::getId();
	}

	/**
	 * @return int|null The ID of the parent revision, or null if the original parent revision
	 *         was not recorded in the archive table.
	 */
	public function getParentId() {
		// overwritten just to refien the contract specification.
		return parent::getParentId();
	}

	/**
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
	 * @return int The user ID, never null. May be 0 if the user is anonymous or unknown.
	 */
	public function getUserId( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserId( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return string The user name, never null. Empty if access is forbidden.
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserText( $audience, $user );
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

}
