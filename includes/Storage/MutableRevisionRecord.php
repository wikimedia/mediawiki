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
 * @since 1.30
 */
class MutableRevisionRecord extends RevisionRecord {

	/**
	 * Constructs a MutableRevisionRecordbased on pre-1.30 array syntax.
	 *
	 * @param Title $title
	 * @param array $array
	 * @param RevisionSlots $slots
	 * @param bool $wikiId
	 *
	 * @return RevisionStoreRecord
	 */
	public static function newFromArray_1_29( Title $title, array $array, RevisionSlots $slots, $wikiId = false ) {
		Assert::parameterType( 'array', $array, '$row' );

		$record = new MutableRevisionRecord( $title, $wikiId );

		$userText = isset( $row['user_text'] ) ? strval( $row['user_text'] ) : null;
		$userId = isset( $row['user'] ) ? intval( $row['user'] ) : null;
		$timestamp = isset( $row['timestamp'] )
			? strval( $row['timestamp'] ) : wfTimestampNow(); // XXX: can we get rid of this?

		$pageId = isset( $row['page'] ) ? intval( $row['page'] ) : null;

		if ( $title->exists() && $pageId === null ) {
			$pageId = $title->getArticleID();
		}

		$record->setUserIdAndName( $userId, $userText );
		$record->setTimestamp( $timestamp );

		if ( $pageId ) $record->setPageId( $pageId );

		if ( isset( $row['id'] ) ) $record->setId( intval( $row['id'] ) );
		if ( isset( $row['parent_id'] ) ) $record->setParentId( intval( $row['parent_id'] ) );

		if ( isset( $row['sha1'] ) ) $record->setSha1( $row['sha1'] );
		if ( isset( $row['size'] ) ) $record->setSize( intval( $row['size'] ) );

		if ( isset( $row['minor_edit'] ) ) $record->setMinorEdit( intval( $row['minor_edit'] ) !== 0 );
		if ( isset( $row['deleted'] ) ) $record->setDeleted( intval( $row['deleted'] ) );


		if ( isset( $row['comment'] ) ) {
			// FIXME: make sure the below is right!
			if ( $row['comment'] instanceof CommentStoreComment ) {
				$comment = $row['comment'];
			} elseif ( isset( $row['comment_cid'] ) ) {
				$comment = CommentStore::newKey( 'comment' )
					->getCommentLegacy( wfGetDB( DB_REPLICA ), $row, false ); // FIXME: no wfGetDB!
			} else {
				$commentText = trim( strval( $row['comment'] ) );
				$comment = new CommentStoreComment( null, $commentText );
			}

			$record->setComment( $comment );
		}

		return $record;
	}

	/**
	 * Returns an incomplete MutableRevisionRecord which uses $parent as its
	 * parent revision, and inherits all slots form it. If saved unchaned,
	 * the new revision will act as a null-revision.
	 *
	 * @param RevisionRecord $parent
	 * @param CommentStoreComment $comment
	 * @param User $user
	 * @param string $timestamp
	 *
	 * @return MutableRevisionRecord
	 */
	public static function newFromParentRevision(
		RevisionRecord $parent,
		CommentStoreComment $comment,
		User $user,
		$timestamp
	) {
		$rev = new MutableRevisionRecord( $parent->getTitle(), $parent->getWikiId() );

		$rev->setComment( $comment );
		$rev->setUserIdAndName( $user->getId(), $user->getName() );
		$rev->setTimestamp( $timestamp );

		foreach ( $parent->getSlotNames() as $role ) {
			$slot = $parent->getSlot( $role, self::RAW );
			$rev->inheritSlot( $slot );
		}

		$rev->setPageId( $parent->getPageId() );
		$rev->setParentId( $parent->getId() );

		return $rev;
	}

	/**
	 * @note Avoid calling this constructor directly. Use the appropriate methods
	 * in RevisionStore instead.
	 *
	 * @param Title $title The title of the page this Revision is associated with.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 *
	 * @throws MWException
	 */
	function __construct( Title $title, $wikiId = false ) {
		$slots = new MutableRevisionSlots();

		parent::__construct( $title, $slots, $wikiId );

		$this->mSlots = $slots; // redundant, but nice for static analysis
	}

	/**
	 * @param int $parentId
	 */
	public function setParentId( $parentId ) {
		Assert::parameterType( 'int', $parentId, '$parentId' );

		$this->mParentId = $parentId;
	}

	/**
	 * @param SlotRecord $slot
	 */
	public function setSlot( SlotRecord $slot ) {
		$this->mSlots->setSlot( $slot );
	}

	/**
	 * @param SlotRecord $parentSlot
	 */
	public function inheritSlot( SlotRecord $parentSlot ) {
		$slot = SlotRecord::newInherited( $parentSlot );
		$this->mSlots->setSlot( $slot );
	}

	/**
	 * @param string $role
	 * @param Content $content
	 */
	public function setContent( $role, Content $content ) {
		$this->mSlots->setContent( $role, $content );
	}

	/**
	 * @param string $role
	 */
	public function removeSlot( $role ) {
		$this->mSlots->removeSlot( $role );
	}

	/**
	 * @param CommentStoreComment $comment
	 */
	public function setComment( CommentStoreComment $comment ) {
		$this->mComment = $comment;
	}

	/**
	 * Set revision hash if known. Prevents getSha1() from re-calculating the hash.
	 *
	 * @param string $sha1
	 */
	public function setSha1( $sha1 ) {
		Assert::parameterType( 'string', $sha1, '$sha1' );

		$this->mSha1 = $sha1;
	}

	/**
	 * Set nominal revision size known. Prevents getSize() from re-calculating the size.
	 *
	 * @param int|null $size
	 */
	public function setSize( $size ) {
		Assert::parameterType( 'int', $size, '$size' );

		$this->mSize = $size;
	}

	/**
	 * @param int $deleted
	 */
	public function setDeleted( $deleted ) {
		Assert::parameterType( 'bool', $deleted, '$deleted' );

		$this->mDeleted = $deleted;
	}

	/**
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		Assert::parameterType( 'string', $timestamp, '$timestamp' );

		$this->mTimestamp = $timestamp;
	}

	/**
	 * @param boolean $minorEdit
	 */
	public function setMinorEdit( $minorEdit ) {
		Assert::parameterType( 'bool', $minorEdit, '$minorEdit' );

		$this->mMinorEdit = $minorEdit;
	}

	/**
	 * @param int|null $userId
	 * @param string|null $userName
	 */
	public function setUserIdAndName( $userId, $userName ) {
		Assert::parameterType( 'integer|null', $userId, '$userId' );
		Assert::parameterType( 'string|null', $userName, '$userName' );

		$this->mUserId = $userId;
		$this->mUserText = $userName;
	}

	/**
	 * @param int $pageId
	 */
	public function setPageId( $pageId ) {
		Assert::parameterType( 'integer', $pageId, '$pageId' );

		if ( $this->mTitle->exists() && $pageId !== $this->mTitle->getArticleID() ) {
			throw new InvalidArgumentException(
				'The given Title does not belong to page ID ' . $this->mPageId
			);
		}

		$this->mPageId = $pageId;
	}

	/**
	 * Returns the length of the text in this revision.
	 *
	 * MCR migration note: this replaces Revision::getSize
	 *
	 * @return int
	 */
	public function getSize() {
		// If no fixed value is given, re-calculate on every call, since slots may change
		if ( $this->mSize === null  ) {
			return $this->mSlots->computeSize();
		}

		return $this->mSize;
	}

	/**
	 * Set the revision ID.
	 *
	 * @warning Use this with care, especially when preparing a revision for insertion
	 *          into the database! The revision ID should only be fixed in special cases
	 *          like preserving the original ID when restoring a revision.
	 *
	 * @param int $id
	 */
	public function setId( $id ) {
		Assert::parameterType( 'integer', $id, '$id' );

		$this->mId = $id;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * MCR migration note: this replaces Revision::getSha1
	 *
	 * @return string
	 */
	public function getSha1() {
		// If no fixed value is given, re-calculate on every call, since slots may change
		if ( $this->mSha1 === null  ) {
			return $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is null
	 * @return mixed $value, if $value is not null
	 */
	private function guarantee( $value, $name ) {
		if ( $value === null ) {
			throw new IncompleteRevisionException(
				"`$name` has not been set on this MutableRevisionRecord"
			);
		}

		return $value;
	}

	/**
	 * Returns the SlotRecord for the given role, with no audience checks applied.
	 *
	 * @param string $role
	 *
	 * @throw OutOfBoundsException if no slot is set for the given role.
	 * @return SlotRecord
	 */
	public function guaranteeSlot( $role ) {
		return $this->getSlot( $role, self::RAW );
	}

	/**
	 * Returns the Content for the given role, with no audience checks applied.
	 *
	 * @param string $role
	 *
	 * @throw OutOfBoundsException if no slot is set for the given role.
	 * @return Content
	 */
	public function guaranteeContent( $role ) {
		return $this->guaranteeSlot( $role )->getContent();
	}

	/**
	 * @return int The ID of the parent revision, never null. May be 0 if there is no parent revision.
	 * @throw IncompleteRevisionException if the respective field is was not set.
	 */
	public function guaranteeParentId() {
		return $this->guarantee( $this->getParentId(), 'ParentId' );
	}

	/**
	 * @return int the nominal revision size, never null
	 *         Will be calculated if not set.
	 */
	public function guaranteeSize() {
		return $this->guarantee( $this->getSize(), "Size" );
	}

	/**
	 * @return string The revision hash, never null.
	 *         Will be calculated if not set.
	 */
	public function guaranteeSha1() {
		return $this->guarantee( $this->getSha1(), "Sha1" );
	}

	/**
	 * @return int The ID of the page this revision belongs to, never null, always greater than 0.
	 *         Will be calculated if not set needed.
	 */
	public function guaranteePageId() {
		$pageId = $this->guarantee( $this->getPageId(), "PageId" );

		// make sure this is really a page ID
		if ( $pageId <= 0 ) {
			throw new IncompleteRevisionException( 'PageId must be positive' );
		}

		return $pageId;
	}

	/**
	 * Returns the user id, with no audience checks applied.
	 *
	 * @return int The user ID, never null. May be 0 if the user is anonymous or unknown.
	 * @throw IncompleteRevisionException if the respective field is was not set.
	 */
	public function guaranteeUserId() {
		return $this->guarantee( $this->getUserId( self::RAW ), "UserId" );
	}

	/**
	 * Returns the user name, with no audience checks applied.
	 *
	 * @return string The user text, never null.
	 * @throw IncompleteRevisionException if the respective field is was not set.
	 */
	public function guaranteeUserText() {
		return $this->guarantee( $this->getUserText( self::RAW ), "UserText" );
	}

	/**
	 * Returns the revision comment, with no audience checks applied.
	 *
	 * @return CommentStoreComment The revision comment, never null. Empty if access is forbidden.
	 * @throw IncompleteRevisionException if the respective field is was not set.
	 */
	public function guaranteeComment() {
		return $this->guarantee( $this->getComment( self::RAW ), "Comment" );
	}

	/**
	 * @return string timestamp, never null
	 * @throw IncompleteRevisionException if the respective field is was not set.
	 */
	public function guaranteeTimestamp() {
		return $this->guarantee( $this->getTimestamp(), "Timestamp" );
	}

}
