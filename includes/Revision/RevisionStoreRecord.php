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

use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
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
	 * @param PageIdentity $page The page this RevisionRecord is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param \stdClass $row A row from the revision table. Use RevisionStore::getQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param false|string $wikiId Relevant wiki id or self::LOCAL for the current one.
	 */
	public function __construct(
		PageIdentity $page,
		UserIdentity $user,
		CommentStoreComment $comment,
		\stdClass $row,
		RevisionSlots $slots,
		$wikiId = self::LOCAL
	) {
		parent::__construct( $page, $slots, $wikiId );
		$this->mId = intval( $row->rev_id );
		$this->mPageId = intval( $row->rev_page );
		$this->mComment = $comment;

		Assert::parameter(
			$page->exists(),
			'$page',
			'must represent an existing page'
		);
		Assert::parameter(
			$page->getId( $wikiId ) === $this->mPageId,
			'$page',
			'must match the rev_page field in $row'
		);
		Assert::postcondition(
			parent::getPage() instanceof ProperPageIdentity,
			'The parent constructor should have ensured that we have a ProperPageIdentity now.'
		);

		// Don't use MWTimestamp::convert, instead let any detailed exception from MWTimestamp
		// bubble up (T254210)
		$timestamp = ( new MWTimestamp( $row->rev_timestamp ) )->getTimestamp( TS_MW );

		$this->mUser = $user;
		$this->mMinorEdit = (bool)$row->rev_minor_edit;
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

		$pageIdBasedOnPage = $this->getArticleId( $this->mPage );
		if ( $this->mPageId && $pageIdBasedOnPage && $this->mPageId !== $pageIdBasedOnPage ) {
			throw new InvalidArgumentException(
				'The given page (' . $this->mPage . ')' .
				' does not belong to page ID ' . $this->mPageId .
				' but actually belongs to ' . $this->getArticleId( $this->mPage )
			);
		}
	}

	/**
	 * Returns the page this revision belongs to.
	 *
	 * @return ProperPageIdentity (before 1.44, this was returning a PageIdentity)
	 */
	public function getPage(): ProperPageIdentity {
		// Override to narrow the return type.
		// We checked in the constructor that the page is a proper page.
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return parent::getPage();
	}

	/**
	 * @inheritDoc
	 */
	public function isCurrent() {
		return $this->mCurrent;
	}

	/**
	 * MCR migration note: this replaced Revision::isDeleted
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

	/** @inheritDoc */
	public function userCan( $field, Authority $performer ) {
		if ( $this->isCurrent() && $field === self::DELETED_TEXT ) {
			// Current revisions of pages cannot have the content hidden. Skipping this
			// check is very useful for Parser as it fetches templates using newKnownCurrent().
			// Calling getVisibility() in that case triggers a verification database query.
			return true; // no need to check
		}

		return parent::userCan( $field, $performer );
	}

	/**
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int|null The revision id, never null.
	 */
	public function getId( $wikiId = self::LOCAL ) {
		// overwritten just to add a guarantee to the contract
		return parent::getId( $wikiId );
	}

	/**
	 * @throws RevisionAccessException if the size was unknown and could not be calculated.
	 * @return int The nominal revision size, never null. May be computed on the fly.
	 */
	public function getSize() {
		// If length is null, calculate and remember it (potentially SLOW!).
		// This is for compatibility with old database rows that don't have the field set.
		$this->mSize ??= $this->mSlots->computeSize();

		return $this->mSize;
	}

	/**
	 * @throws RevisionAccessException if the hash was unknown and could not be calculated.
	 * @return string The revision hash, never null. May be computed on the fly.
	 */
	public function getSha1() {
		// If hash is null, calculate it and remember (potentially SLOW!)
		// This is for compatibility with old database rows that don't have the field set.
		$this->mSha1 ??= $this->mSlots->computeSha1();

		return $this->mSha1;
	}

	/**
	 * @param int $audience
	 * @param Authority|null $performer
	 *
	 * @return UserIdentity The identity of the revision author, null if access is forbidden.
	 */
	public function getUser( $audience = self::FOR_PUBLIC, ?Authority $performer = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getUser( $audience, $performer );
	}

	/**
	 * @param int $audience
	 * @param Authority|null $performer
	 *
	 * @return CommentStoreComment The revision comment, null if access is forbidden.
	 */
	public function getComment( $audience = self::FOR_PUBLIC, ?Authority $performer = null ) {
		// overwritten just to add a guarantee to the contract
		return parent::getComment( $audience, $performer );
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
