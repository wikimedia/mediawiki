<?php
/**
 * Representation of a page version.
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

use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\ImmutableRevisionException;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\RevisionStoreRecord;
use MediaWiki\Storage\SlotRecord;
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * @deprecated since 1.31, use RevisionRecord, RevisionStore, and BlobStore instead.
 */
class Revision implements IDBAccessObject {

	/** @var RevisionRecord */
	protected $mRecord;

	// Revision deletion constants
	const DELETED_TEXT = RevisionRecord::DELETED_TEXT;
	const DELETED_COMMENT = RevisionRecord::DELETED_COMMENT;
	const DELETED_USER = RevisionRecord::DELETED_USER;
	const DELETED_RESTRICTED = RevisionRecord::DELETED_RESTRICTED;
	const SUPPRESSED_USER = RevisionRecord::SUPPRESSED_USER;
	const SUPPRESSED_ALL = RevisionRecord::SUPPRESSED_ALL;

	// Audience options for accessors
	const FOR_PUBLIC = RevisionRecord::FOR_PUBLIC;
	const FOR_THIS_USER = RevisionRecord::FOR_THIS_USER;
	const RAW = RevisionRecord::RAW;

	const TEXT_CACHE_GROUP = BlobStore::TEXT_CACHE_GROUP;

	/**
	 * @return RevisionStore
	 */
	protected static function getRevisionStore() {
		return MediaWikiServices::getInstance()->getRevisionStore();
	}

	/**
	 * @return BlobStore
	 */
	protected static function getBlobStore() {
		return MediaWikiServices::getInstance()->getBlobStore();
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return Revision|null
	 */
	public static function newFromId( $id, $flags = 0 ) {
		$rec = self::getRevisionStore()->getRevisionById( $id, $flags );
		return $rec === null ? null : new Revision( $rec, $flags );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $id (optional)
	 * @param int $flags Bitfield (optional)
	 * @return Revision|null
	 */
	public static function newFromTitle( LinkTarget $linkTarget, $id = 0, $flags = 0 ) {
		$rec = self::getRevisionStore()->getRevisionByTitle( $linkTarget, $id, $flags );
		return $rec === null ? null : new Revision( $rec, $flags );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master (since 1.20)
	 *      Revision::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return Revision|null
	 */
	public static function newFromPageId( $pageId, $revId = 0, $flags = 0 ) {
		$rec = self::getRevisionStore()->getRevisionByPageId( $pageId, $revId, $flags );
		return $rec === null ? null : new Revision( $rec, $flags );
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * @param object $row
	 * @param array $overrides
	 *
	 * @throws MWException
	 * @return Revision
	 */
	public static function newFromArchiveRow( $row, $overrides = [] ) {
		$rec = self::getRevisionStore()->newRevisionFromArchiveRow( $row, $overrides );
		return new Revision( $rec );
	}

	/**
	 * @since 1.19
	 *
	 * @param object $row
	 * @return Revision
	 */
	public static function newFromRow( $row ) {
		$rec = self::getRevisionStore()->newRevisionFromRow( $row );
		return new Revision( $rec );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionById() instead.
	 *
	 * @param IDatabase $db
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromId( $db, $id ) {
		wfDeprecated( __METHOD__, '1.31' ); // no known callers
		$rec = self::getRevisionStore()->loadRevisionFromId( $db, $id );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionByPageId() instead.
	 *
	 * @param IDatabase $db
	 * @param int $pageid
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromPageId( $db, $pageid, $id = 0 ) {
		$rec = self::getRevisionStore()->loadRevisionFromPageId( $db, $pageid, $id );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionByTitle() instead.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromTitle( $db, $title, $id = 0 ) {
		$rec = self::getRevisionStore()->loadRevisionFromTitle( $db, $title, $id );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @deprecated since 1.31, use RevisionStore::loadRevisionFromTimestamp() instead.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return Revision|null
	 */
	public static function loadFromTimestamp( $db, $title, $timestamp ) {
		// XXX: replace loadRevisionFromTimestamp by getRevisionByTimestamp?
		$rec = self::getRevisionStore()->loadRevisionFromTimestamp( $db, $title, $timestamp );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param LinkTarget $title
	 * @return ResultWrapper
	 * @deprecated Since 1.28, no callers in core nor in known extensions. No-op since 1.31.
	 */
	public static function fetchRevision( LinkTarget $title ) {
		wfDeprecated( __METHOD__, '1.31' );
		return new FakeResultWrapper( [] );
	}

	/**
	 * Return the value of a select() JOIN conds array for the user table.
	 * This will get user table rows for logged-in users.
	 * @since 1.19
	 * @return array
	 */
	public static function userJoinCond() {
		return self::getRevisionStore()->userJoinCond();
	}

	/**
	 * Return the value of a select() page conds array for the page table.
	 * This will assure that the revision(s) are not orphaned from live pages.
	 * @since 1.19
	 * @return array
	 */
	public static function pageJoinCond() {
		return self::getRevisionStore()->pageJoinCond();
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 * @return array
	 */
	public static function selectFields() {
		return self::getRevisionStore()->selectRevisionFields();
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision from an archive row.
	 * @return array
	 */
	public static function selectArchiveFields() {
		return self::getRevisionStore()->selectArchiveFields();
	}

	/**
	 * Return the list of text fields that should be selected to read the
	 * revision text
	 * @return array
	 */
	public static function selectTextFields() {
		return self::getRevisionStore()->selectTextFields();
	}

	/**
	 * Return the list of page fields that should be selected from page table
	 * @return array
	 */
	public static function selectPageFields() {
		return self::getRevisionStore()->selectPageFields();
	}

	/**
	 * Return the list of user fields that should be selected from user table
	 * @return array
	 */
	public static function selectUserFields() {
		return self::getRevisionStore()->selectUserFields();
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 * @param IDatabase $db
	 * @param array $revIds
	 * @return array
	 */
	public static function getParentLengths( $db, array $revIds ) {
		return self::getRevisionStore()->getParentLengths( $db, $revIds );
	}

	/**
	 * @param object|array|RevisionRecord $row Either a database row or an array
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @access private
	 */
	function __construct( $row, $queryFlags = 0, Title $title = null ) {
		if ( $row instanceof RevisionRecord ) {
			$this->mRecord = $row;
		} elseif ( is_array( $row ) ) {
			$this->mRecord = self::getRevisionStore()->newRevisionFromArray_1_29( $row, $queryFlags, $title );
		} elseif ( is_object( $row ) ) {
			$this->mRecord = self::getRevisionStore()->newRevisionFromRow( $row, $queryFlags, $title );
		} else {
			throw new InvalidArgumentException(
				'$row must be a row object, an associative array, or a RevisionRecord'
			);
		}
	}

	/**
	 * Get revision ID
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->mRecord->getId();
	}

	/**
	 * Set the revision ID
	 *
	 * This should only be used for proposed revisions that turn out to be null edits.
	 *
	 * @note Only supported on Revisions that were constructed based on associative arrays,
	 *       since they are mutable.
	 *
	 * @since 1.19
	 * @param int $id
	 */
	public function setId( $id ) {
		if ( $this->mRecord instanceof MutableRevisionRecord ) {
			$this->mRecord->setId( $id );
		} else {
			throw new ImmutableRevisionException( __METHOD__ . ' is not supported on this instance' );
		}
	}

	/**
	 * Set the user ID/name
	 *
	 * This should only be used for proposed revisions that turn out to be null edits
	 *
	 * @note Only supported on Revisions that were constructed based on associative arrays,
	 *       since they are mutable.
	 *
	 * @since 1.28
	 * @param int $id User ID
	 * @param string $name User name
	 * @throws MWException
	 */
	public function setUserIdAndName( $id, $name ) {
		if ( $this->mRecord instanceof MutableRevisionRecord ) {
			$this->mRecord->setUserIdAndName( $id, $name );
		} else {
			throw new ImmutableRevisionException( __METHOD__ . ' is not supported on this instance' );
		}
	}

	/**
	 * @return SlotRecord
	 */
	private function getMainSlot() {
		return $this->mRecord->getSlot( 'main' );
	}

	/**
	 * Get the main slot's content address.
	 *
	 * @warn Since 1.31, this returns the main slot's content address as a string.
	 * Previously, this used to return the ID of the text row as an int.
	 * This change was made in the light of the fact that calling code seems to use the
	 * return value solely to check whether two revisions have the same content.
	 * Code checking for null edits should consider comparing SHA1 hashes instead.
	 *
	 * @return string|null
	 */
	public function getTextId() {
		$slot = $this->getMainSlot();
		return $slot->getAddress();
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int|null
	 */
	public function getParentId() {
		return $this->mRecord->getParentId();
	}

	/**
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @return int
	 */
	public function getSize() {
		return $this->mRecord->getSize();
	}

	/**
	 * Returns the base36 sha1 of the content in this revision, or null if unknown.
	 *
	 * @return string
	 */
	public function getSha1() {
		// XXX: we may want to drop all the hashing logic, it's not worth the overhead.
		return $this->mRecord->getSha1();
	}

	/**
	 * Returns the title of the page associated with this entry or null.
	 *
	 * Will do a query, when title is not set and id is given.
	 *
	 * @return Title|null
	 */
	public function getTitle() {
		return $this->mRecord->getTitle();
	}

	/**
	 * Set the title of the revision
	 *
	 * @note: since 1.31, this is now a noop.
	 *
	 * @param Title $title
	 */
	public function setTitle( $title ) {
		if ( !$title->equals( $this->mRecord->getTitle() ) ) {
			throw new InvalidArgumentException(
				$title->getPrefixedText()
					. ' is not the same as '
					. $this->mRecord->getTitle()->getPrefixedText()
			);
		}
	}

	/**
	 * Get the page ID
	 *
	 * @return int|null
	 */
	public function getPage() {
		return $this->mRecord->getPageId();
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		return $this->mRecord->getUserId( $audience, $user );
	}

	/**
	 * Fetch revision's user id without regard for the current user's permissions
	 *
	 * @return int
	 * @deprecated since 1.25, use getUser( Revision::RAW )
	 */
	public function getRawUser() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getUser( self::RAW );
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		return $this->mRecord->getUserText( $audience, $user );
	}

	/**
	 * Fetch revision's username without regard for view restrictions
	 *
	 * @return string
	 * @deprecated since 1.25, use getUserText( Revision::RAW )
	 */
	public function getRawUserText() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getUserText( self::RAW );
	}

	/**
	 * Fetch revision comment if it's available to the specified audience.
	 * If the specified audience does not have access to the comment, an
	 * empty string will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string
	 */
	function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		return $this->mRecord->getComment( $audience, $user );
	}

	/**
	 * Fetch revision comment without regard for the current user's permissions
	 *
	 * @return string
	 * @deprecated since 1.25, use getComment( Revision::RAW )
	 */
	public function getRawComment() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getComment( self::RAW );
	}

	/**
	 * @return bool
	 */
	public function isMinor() {
		return $this->mRecord->isMinor();
	}

	/**
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled() {
		return self::getRevisionStore()->isUnpatrolled( $this->mRecord );
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * @param int $flags (optional) $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *
	 * @since 1.22
	 * @return RecentChange|null
	 */
	public function getRecentChange( $flags = 0 ) {
		return self::getRevisionStore()->getRecentChange( $this->mRecord, $flags );
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		return $this->mRecord->isDeleted( $field );
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility() {
		return $this->mRecord->getVisibility();
	}

	/**
	 * Fetch revision content if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this
	 * revision, null will be returned.
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @since 1.21
	 * @return Content|null
	 */
	public function getContent( $audience = self::FOR_PUBLIC, User $user = null ) {
		return $this->mRecord->getContent( 'main', $audience, $user );
	}

	/**
	 * Get original serialized data (without checking view restrictions)
	 *
	 * @since 1.21
	 * @deprecated since 1.31, use BlobStore::getBlob instead.
	 *
	 * @return string
	 */
	public function getSerializedData() {
		$slot = $this->getMainSlot();
		return $slot->getContent()->serialize();
	}

	/**
	 * Returns the content model for the main slot of this revision.
	 *
	 * If no content model was stored in the database, the default content model for the title is
	 * used to determine the content model to use. If no title is know, CONTENT_MODEL_WIKITEXT
	 * is used as a last resort.
	 *
	 * @todo: drop this, with MCR, there no longer is a single model associated with a revision.
	 *
	 * @return string The content model id associated with this revision,
	 *     see the CONTENT_MODEL_XXX constants.
	 */
	public function getContentModel() {
		return $this->getMainSlot()->getModel();
	}

	/**
	 * Returns the content format for the main slot of this revision.
	 *
	 * If no content format was stored in the database, the default format for this
	 * revision's content model is returned.
	 *
	 * @todo: drop this, the format is irrelevant to the revision!
	 *
	 * @return string The content format id associated with this revision,
	 *     see the CONTENT_FORMAT_XXX constants.
	 */
	public function getContentFormat() {
		$format = $this->getMainSlot()->getFormat();

		if ( $format === null ) {
			// if no format was stored along with the blob, fall back to default format
			$format = $this->getContentHandler()->getDefaultFormat();
		}

		return $format;
	}

	/**
	 * Returns the content handler appropriate for this revision's content model.
	 *
	 * @throws MWException
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		return ContentHandler::getForModelID( $this->getContentModel() );
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return $this->mRecord->getTimestamp();
	}

	/**
	 * @return bool
	 */
	public function isCurrent() {
		return ( $this->mRecord instanceof RevisionStoreRecord ) && $this->mRecord->isCurrent();
	}

	/**
	 * Get previous revision for this title
	 *
	 * @return Revision|null
	 */
	public function getPrevious() {
		$rec = self::getRevisionStore()->getPreviousRevision( $this->mRecord );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Get next revision for this title
	 *
	 * @return Revision|null
	 */
	public function getNext() {
		$rec = self::getRevisionStore()->getNextRevision( $this->mRecord );
		return $rec === null ? null : new Revision( $rec );
	}

	/**
	 * Get revision text associated with an old or archive row
	 *
	 * Both the flags and the text field must be included. Including the old_id
	 * field will activate cache usage as long as the $wiki parameter is not set.
	 *
	 * @param stdClass $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string|false Text the text requested or false on failure
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = [];
		}

		if ( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			return false;
		}

		$cacheKey = isset( $row->old_id ) ? ( 'tt:' . $row->old_id ) : null;

		return self::getBlobStore()->expandBlob( $text, $flags, $cacheKey );
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @param mixed &$text Reference to a text
	 * @return string
	 */
	public static function compressRevisionText( &$text ) {
		return self::getBlobStore()->compressRevisionData( $text );
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @param mixed $text Reference to a text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public static function decompressRevisionText( $text, $flags ) {
		return self::getBlobStore()->decompressRevisionData( $text, $flags );
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param IDatabase $dbw (master connection)
	 * @throws MWException
	 * @return int The revision ID
	 */
	public function insertOn( $dbw ) {
		if ( !( $this->mRecord instanceof MutableRevisionRecord ) ) {
			throw new ImmutableRevisionException( 'This revision cannot be saved since it already exists' );
		}

		if ( $this->mRecord->getId() !== null ) {
			throw new UnexpectedValueException( 'The revision ID must not be set prior to saving' );
		}

		$rec = self::getRevisionStore()->insertRevisionOn( $this->mRecord, $dbw );

		$this->mRecord = $rec;

		return $rec->getId();
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 * @param string $text
	 * @return string
	 */
	public static function base36Sha1( $text ) {
		return SlotRecord::base36Sha1( $text );
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId ID number of the page to read from
	 * @param string $summary Revision's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User|null $user User object to use or null for $wgUser
	 * @return Revision|null Revision or null on error
	 */
	public static function newNullRevision( $dbw, $pageId, $summary, $minor, $user = null ) {
		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$title = Title::newFromID( $pageId );
		$rec = self::getRevisionStore()->newNullRevision( $dbw, $title, $summary, $minor, $user );

		return new Revision( $rec );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public function userCan( $field, User $user = null ) {
		return self::userCanBitfield( $this->getVisibility(), $field, $user );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted. This is used
	 * by various classes to avoid duplication.
	 *
	 * @param int $bitfield Current field
	 * @param int $field One of self::DELETED_TEXT = File::DELETED_FILE,
	 *                               self::DELETED_COMMENT = File::DELETED_COMMENT,
	 *                               self::DELETED_USER = File::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @param Title|null $title A Title object to check for per-page restrictions on,
	 *                          instead of just plain userrights
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, User $user = null,
		Title $title = null
	) {
		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		return RevisionRecord::userCanBitfield( $bitfield, $field, $user, $title );
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * @param Title $title
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	static function getTimestampFromId( $title, $id, $flags = 0 ) {
		return self::getRevisionStore()->getTimestampFromId( $title, $id, $flags );
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	static function countByPageId( $db, $id ) {
		return self::getRevisionStore()->countRevisionsByPageId( $db, $id );
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	static function countByTitle( $db, $title ) {
		return self::getRevisionStore()->countRevisionsByTitle( $db, $title );
	}

	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 *
	 * @since 1.20
	 * @deprecated since 1.24
	 *
	 * @param IDatabase|int $db The Database to perform the check on. May be given as a
	 *        Database object or a database identifier usable with wfGetDB.
	 * @param int $pageId The ID of the page in question
	 * @param int $userId The ID of the user in question
	 * @param string $since Look at edits since this time
	 *
	 * @return bool True if the given user was the only one to edit since the given timestamp
	 */
	public static function userWasLastToEdit( $db, $pageId, $userId, $since ) {
		return self::getRevisionStore()->userWasLastToEdit( $db, $pageId, $userId, $since );
	}

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 * The title will also be lazy loaded, though setTitle() can be used to preload it.
	 *
	 * @param IDatabase $db
	 * @param int $pageId Page ID
	 * @param int $revId Known current revision of this page
	 * @return Revision|bool Returns false if missing
	 * @since 1.28
	 */
	public static function newKnownCurrent( IDatabase $db, $pageId, $revId ) {
		return self::getRevisionStore()->getKnownCurrentRevision( $db, $pageId, $revId );
	}
}
