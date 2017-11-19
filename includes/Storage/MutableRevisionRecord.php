<?php
/**
 * Mutable RevisionRecord implementation, for building new revision entries programmatically.
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
use MediaWiki\User\UserIdentity;
use MWException;
use Wikimedia\Assert\Assert;

/**
 * Mutable RevisionRecord implementation, for building new revision entries programmatically.
 * Provides setters for all fields.
 *
 * @since 1.31
 */
class MutableRevisionRecord extends RevisionRecord {

	/**
	 * Returns an incomplete MutableRevisionRecord which uses $parent as its
	 * parent revision, and inherits all slots form it. If saved unchanged,
	 * the new revision will act as a null-revision.
	 *
	 * @param RevisionRecord $parent
	 * @param CommentStoreComment $comment
	 * @param UserIdentity $user
	 * @param string $timestamp
	 *
	 * @return MutableRevisionRecord
	 */
	public static function newFromParentRevision(
		RevisionRecord $parent,
		CommentStoreComment $comment,
		UserIdentity $user,
		$timestamp
	) {
		$rev = new MutableRevisionRecord( $parent->getPageIdentity(), $parent->getWikiId() );

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
	 * @param PageIdentity $pageIdentity The identity of the page this Revision is associated with.
	 * @param bool|string $wikiId the wiki ID of the site this Revision belongs to,
	 *        or false for the local site.
	 *
	 * @throws MWException
	 */
	function __construct( PageIdentity $pageIdentity, $wikiId = false ) {
		$slots = new MutableRevisionSlots();

		parent::__construct( $pageIdentity, $slots, $wikiId );

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
	 * Sets the given slot. If a slot with the same role is already present in the revision,
	 * it is replaced.
	 *
	 * @note This can only be used with a fresh "unattached" SlotRecord. Calling code that has a
	 * SlotRecord from another revision should use inheritSlot(). Calling code that has access to
	 * a Content object can use setContent().
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param SlotRecord $slot
	 */
	public function setSlot( SlotRecord $slot ) {
		if ( $slot->hasRevision() && $slot->getRevision() !== $this->getId() ) {
			throw new InvalidArgumentException(
				'The given slot must be an unsaved, unattached one. '
				. 'This slot is already attached to revision ' . $slot->getRevision() . '. '
				. 'Use inheritSlot() instead to preserve a slot from a previous revision.'
			);
		}

		$this->mSlots->setSlot( $slot );
	}

	/**
	 * "Inherits" the given slot's content.
	 *
	 * If a slot with the same role is already present in the revision, it is replaced.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param SlotRecord $parentSlot
	 */
	public function inheritSlot( SlotRecord $parentSlot ) {
		$slot = SlotRecord::newInherited( $parentSlot );
		$this->setSlot( $slot );
	}

	/**
	 * Sets the content for the slot with the given role.
	 *
	 * If a slot with the same role is already present in the revision, it is replaced.
	 * Calling code that has access to a SlotRecord can use inheritSlot() instead.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
	 * @param string $role
	 * @param Content $content
	 */
	public function setContent( $role, Content $content ) {
		$this->mSlots->setContent( $role, $content );
	}

	/**
	 * Removes the slot with the given role from this revision.
	 * This effectively ends the "stream" with that role on the revision's page.
	 * Future revisions will no longer inherit this slot, unless it is added back explicitly.
	 *
	 * @note This may cause the slot meta-data for the revision to be lazy-loaded.
	 *
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
	 * Set revision hash, for optimization. Prevents getSha1() from re-calculating the hash.
	 *
	 * @note This should only be used if the calling code is sure that the given hash is correct
	 * for the revision's content, and there is no chance of the content being manipulated
	 * later. When in doubt, this method should not be called.
	 *
	 * @param string $sha1 SHA1 hash as a base36 string.
	 */
	public function setSha1( $sha1 ) {
		Assert::parameterType( 'string', $sha1, '$sha1' );

		$this->mSha1 = $sha1;
	}

	/**
	 * Set nominal revision size, for optimization. Prevents getSize() from re-calculating the size.
	 *
	 * @note This should only be used if the calling code is sure that the given size is correct
	 * for the revision's content, and there is no chance of the content being manipulated
	 * later. When in doubt, this method should not be called.
	 *
	 * @param int $size nominal size in bogo-bytes
	 */
	public function setSize( $size ) {
		Assert::parameterType( 'integer', $size, '$size' );

		$this->mSize = $size;
	}

	/**
	 * @param int $visibility
	 */
	public function setVisibility( $visibility ) {
		Assert::parameterType( 'integer', $visibility, '$visibility' );

		$this->mDeleted = $visibility;
	}

	/**
	 * @param string $timestamp A timestamp understood by wfTimestamp
	 */
	public function setTimestamp( $timestamp ) {
		Assert::parameterType( 'string', $timestamp, '$timestamp' );

		$this->mTimestamp = wfTimestamp( TS_MW, $timestamp );
	}

	/**
	 * @param bool $minorEdit
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
	 * Sets the user identity associated with the revision
	 *
	 * @param UserIdentity $user
	 */
	public function setUser( UserIdentity $user ) {
		$this->mUser = $user;
	}

	/**
	 * @param int $pageId
	 */
	public function setPageId( $pageId ) {
		Assert::parameterType( 'integer', $pageId, '$pageId' );

		if ( $this->mPageIdentity->exists() && $pageId !== $this->mPageIdentity->getId() ) {
			throw new InvalidArgumentException(
				'The given page ID does not match the PageIdentity provided to the constructor: '
					. $this->mPageId
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
		if ( $this->mSize === null ) {
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
		if ( $this->mSha1 === null ) {
			return $this->mSlots->computeSha1();
		}

		return $this->mSha1;
	}

}
