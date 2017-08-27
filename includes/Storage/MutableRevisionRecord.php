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
 * Mutable RevisionRecord implementation, for building new revision entries programmatically.
 * Provides setters for all fields.
 *
 * @since 1.31
 */
class MutableRevisionRecord extends RevisionRecord {

	/**
	 * Constructs a MutableRevisionRecordbased on pre-1.30 array syntax.
	 *
	 * @param Title $title
	 * @param array $fields
	 * @param bool $wikiId
	 *
	 * @return MutableRevisionRecord
	 */
	public static function newFromArray_1_29( Title $title, array $fields, $wikiId = false ) {
		Assert::parameterType( 'array', $fields, '$row' );

		$record = new MutableRevisionRecord( $title, $wikiId );

		$userText = isset( $fields['user_text'] ) ? strval( $fields['user_text'] ) : null;
		$userId = isset( $fields['user'] ) ? intval( $fields['user'] ) : null;
		$timestamp = isset( $fields['timestamp'] )
			? strval( $fields['timestamp'] ) : wfTimestampNow(); // XXX: can we get rid of this?

		$pageId = isset( $fields['page'] ) ? intval( $fields['page'] ) : null;

		if ( $title->exists() && $pageId === null ) {
			$pageId = $title->getArticleID();
		}

		$record->setUserIdAndName( $userId, $userText );
		$record->setTimestamp( $timestamp );

		if ( $pageId ) {
			$record->setPageId( $pageId );
		}

		if ( isset( $fields['id'] ) ) {
			$record->setId( intval( $fields['id'] ) );
		}
		if ( isset( $fields['parent_id'] ) ) {
			$record->setParentId( intval( $fields['parent_id'] ) );
		}

		if ( isset( $fields['sha1'] ) ) {
			$record->setSha1( $fields['sha1'] );
		}
		if ( isset( $fields['size'] ) ) {
			$record->setSize( intval( $fields['size'] ) );
		}

		if ( isset( $fields['minor_edit'] ) ) {
			$record->setMinorEdit( intval( $fields['minor_edit'] ) !== 0 );
		}
		if ( isset( $fields['deleted'] ) ) {
			$record->setDeleted( intval( $fields['deleted'] ) );
		}

		if ( isset( $fields['comment'] ) ) {
			Assert::parameterType( CommentStoreComment::class, $fields['comment'], '$row[\'comment\']' );
			$record->setComment( $fields['comment'] );
		}

		return $record;
	}

	/**
	 * Returns an incomplete MutableRevisionRecord which uses $parent as its
	 * parent revision, and inherits all slots form it. If saved unchanged,
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
		// TODO: ideally, we wouldn't need a Title here
		$title = Title::newFromLinkTarget( $parent->getTitle() );
		$rev = new MutableRevisionRecord( $title, $parent->getWikiId() );

		$rev->setComment( $comment );
		$rev->setUser( $user );
		$rev->setTimestamp( $timestamp );

		foreach ( $parent->getSlotRoles() as $role ) {
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
		Assert::parameterType( 'integer', $parentId, '$parentId' );

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
	 * @param string $timestamp A timestamp understood by wfTimestamp
	 */
	public function setTimestamp( $timestamp ) {
		Assert::parameterType( 'string', $timestamp, '$timestamp' );

		$this->mTimestamp = wfTimestamp( $timestamp );
	}

	/**
	 * @param boolean $minorEdit
	 */
	public function setMinorEdit( $minorEdit ) {
		Assert::parameterType( 'boolean', $minorEdit, '$minorEdit' );

		$this->mMinorEdit = $minorEdit;
	}

	/**
	 * Set the revision ID.
	 *
	 * MCR migration note: this replaces Revision::setId()
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
	 * Sets the user associated with the revision
	 *
	 * @param User $user
	 */
	public function setUser( User $user ) {
		$this->setUserIdAndName( $user->getId(), $user->getName() );
	}

	/**
	 * Sets the user associated with the revision
	 *
	 * MCR migration note: this replaces Revision::setUserIdAndName()
	 *
	 * @deprecated Use setUser() instead.
	 *
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
	 * Returns the nominal size of this revision.
	 *
	 * MCR migration note: this replaces Revision::getSize
	 *
	 * @return int The nominal size, computed on the fly unless set by calling setSize().
	 */
	public function getSize() {
		// If no fixed value is given, re-calculate on every call, since slots may change
		if ( $this->mSize === null  ) {
			return $this->mSlots->computeSize();
		}

		return $this->mSize;
	}

	/**
	 * Returns the base36 sha1 of this revision.
	 *
	 * MCR migration note: this replaces Revision::getSha1
	 *
	 * @return string The revision hash, computed on the fly unless set by calling setSha1().
	 */
	public function getSha1() {
		// If no fixed value is given, re-calculate on every call, since slots may change
		if ( $this->mSha1 === null  ) {
			return $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

}
