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
	 * @param object|array $row An archive table row
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 */
	function __construct( Title $title, CommentStoreComment $comment, $row, RevisionSlots $slots, $wikiId = false ) {
		parent::__construct( $title, $slots, $wikiId );
		Assert::parameterType( 'object', $row, '$row' );

		// FIXME: require most fiedls! Use archvie row schema!

		$this->mArchiveId = isset( $row->ar_id ) ? $row->ar_id : null;

		$this->mPageId = isset( $row->ar_page_id ) ? $row->ar_page_id : null;
		$this->mParentId = isset( $row->ar_parent_id ) ? $row->ar_parent_id : null;
		$this->mId = isset( $row->ar_rev_id ) ? $row->ar_rev_id : null;
		$this->mComment = $comment;
		$this->mUserId = $row->ar_user;
		$this->mUserText = $row->ar_user_text; // XXX: look this up before
		$this->mTimestamp = $row->ar_timestamp;
		$this->mMinorEdit = $row->ar_minor_edit;
		$this->mDeleted = $row->ar_deleted;
		$this->mSize = $row->ar_len;
		$this->mSha1 = isset( $row->ar_sha1 ) ? $row->ar_sha1 : null;

		$this->mId = intval( $row->rev_id );
		$this->mPageId = intval( $row->rev_page );
		$this->mComment = $row->rev_comment;

		if ( isset( $row->page_id ) ) {
			$this->setTransientData( 'page_row', $row );
		}

		if ( $this->mPageId && $this->mPageId !== $this->mTitle->getArticleID()) {
			throw new InvalidArgumentException(
				'The given Title does not belong to page ID ' . $this->mPageId
			);
		}
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
	 * @return int
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
	 * @return string
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
	 * @return int
	 */
	public function getUserId( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserId( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUserText( $audience, $user );
	}

	/**
	 * @param int $audience
	 * @param User|null $user
	 *
	 * @return CommentStoreComment
	 */
	public function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getComment( $audience, $user );
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		// overwritten just to add a guarantee to the contract
		return parent::getTimestamp();
	}

}
