<?php
/**
 * A RevisionRecord representing a revision of a deleted page persisted in the archive table.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use stdClass;
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
	 * @param PageIdentity $page The page this RevisionRecord is associated with.
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param stdClass $row An archive table row. Use RevisionStore::getArchiveQueryInfo() to build
	 *        a query that yields the required fields.
	 * @param RevisionSlots $slots The slots of this revision.
	 * @param false|string $wikiId Relevant wiki or self::LOCAL for the current one.
	 */
	public function __construct(
		PageIdentity $page,
		UserIdentity $user,
		CommentStoreComment $comment,
		stdClass $row,
		RevisionSlots $slots,
		$wikiId = self::LOCAL
	) {
		parent::__construct( $page, $slots, $wikiId );

		$timestamp = MWTimestamp::convert( TS_MW, $row->ar_timestamp );
		Assert::parameter( is_string( $timestamp ), '$row->rev_timestamp', 'must be a valid timestamp' );

		$this->mArchiveId = intval( $row->ar_id );

		// NOTE: ar_page_id may be different from $this->mPage->getId() in some cases,
		// notably when a partially restored page has been moved, and a new page has been created
		// with the same title. Archive rows for that title will then have the wrong page id.
		$this->mPageId = isset( $row->ar_page_id ) ? intval( $row->ar_page_id ) : $this->getArticleId( $this->mPage );

		// NOTE: ar_parent_id = 0 indicates that there is no parent revision, while null
		// indicates that the parent revision is unknown. As per MW 1.31, the database schema
		// allows ar_parent_id to be NULL.
		$this->mParentId = isset( $row->ar_parent_id ) ? intval( $row->ar_parent_id ) : null;
		$this->mId = isset( $row->ar_rev_id ) ? intval( $row->ar_rev_id ) : null;
		$this->mComment = $comment;
		$this->mUser = $user;
		$this->mTimestamp = $timestamp;
		$this->mMinorEdit = (bool)$row->ar_minor_edit;
		$this->mDeleted = intval( $row->ar_deleted );
		$this->mSize = isset( $row->ar_len ) ? intval( $row->ar_len ) : null;

		Assert::parameter(
			$page->canExist(),
			'$page',
			'must represent a proper page'
		);
		Assert::postcondition(
			parent::getPage() instanceof ProperPageIdentity,
			'The parent constructor should have ensured that we have a ProperPageIdentity now.'
		);
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
	 * Get archive row ID
	 *
	 * @return int
	 */
	public function getArchiveId() {
		return $this->mArchiveId;
	}

	/**
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int|null The revision id, or null if the original revision ID
	 *         was not recorded in the archive table.
	 */
	public function getId( $wikiId = self::LOCAL ) {
		// overwritten just to refine the contract specification.
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
		return $this->mSlots->computeSha1();
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
	 * @return string never null
	 */
	public function getTimestamp() {
		// overwritten just to add a guarantee to the contract
		return parent::getTimestamp();
	}

	/** @inheritDoc */
	public function userCan( $field, Authority $performer ) {
		// This revision belongs to a deleted page, so check the relevant permissions as well. (T345777)

		// Viewing the content requires either 'deletedtext' or 'undelete' (for legacy reasons)
		if (
			$field === self::DELETED_TEXT &&
			!$performer->authorizeRead( 'deletedtext', $this->getPage() ) &&
			!$performer->authorizeRead( 'undelete', $this->getPage() )
		) {
			return false;
		}

		// Viewing the edit summary requires 'deletedhistory'
		if (
			$field === self::DELETED_COMMENT &&
			!$performer->authorizeRead( 'deletedhistory', $this->getPage() )
		) {
			return false;
		}

		// Other fields of revisions of deleted pages are public, per T232389 (unless revision-deleted)

		return parent::userCan( $field, $performer );
	}

	/** @inheritDoc */
	public function audienceCan( $field, $audience, ?Authority $performer = null ) {
		// This revision belongs to a deleted page, so check the relevant permissions as well. (T345777)
		// See userCan().
		if (
			$audience == self::FOR_PUBLIC &&
			( $field === self::DELETED_TEXT || $field === self::DELETED_COMMENT )
		) {
			// TODO: Should this use PermissionManager::isEveryoneAllowed() or something?
			// But RevisionRecord::audienceCan() doesn't do that either…
			return false;
		}

		// This calls userCan(), which checks the user's permissions
		return parent::audienceCan( $field, $audience, $performer );
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
