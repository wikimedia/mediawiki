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

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;

/**
 * @deprecated since 1.31, use RevisionRecord, RevisionStore, and BlobStore instead.
 * Hard deprecated since 1.35
 */
class Revision implements IDBAccessObject {

	/** @var RevisionRecord */
	private $mRecord;

	// Revision deletion constants
	public const DELETED_TEXT = RevisionRecord::DELETED_TEXT;
	public const DELETED_COMMENT = RevisionRecord::DELETED_COMMENT;
	public const DELETED_USER = RevisionRecord::DELETED_USER;
	public const DELETED_RESTRICTED = RevisionRecord::DELETED_RESTRICTED;
	public const SUPPRESSED_USER = RevisionRecord::SUPPRESSED_USER;
	public const SUPPRESSED_ALL = RevisionRecord::SUPPRESSED_ALL;

	// Audience options for accessors
	public const FOR_PUBLIC = RevisionRecord::FOR_PUBLIC;
	public const FOR_THIS_USER = RevisionRecord::FOR_THIS_USER;
	public const RAW = RevisionRecord::RAW;

	public const TEXT_CACHE_GROUP = SqlBlobStore::TEXT_CACHE_GROUP;

	/**
	 * @param string|false $wiki
	 * @return RevisionStore
	 */
	private static function getRevisionStore( $wiki = false ) {
		if ( $wiki ) {
			return MediaWikiServices::getInstance()->getRevisionStoreFactory()
				->getRevisionStore( $wiki );
		} else {
			return MediaWikiServices::getInstance()->getRevisionStore();
		}
	}

	/**
	 * @return RevisionLookup
	 */
	private static function getRevisionLookup() {
		return MediaWikiServices::getInstance()->getRevisionLookup();
	}

	/**
	 * @return RevisionFactory
	 */
	private static function getRevisionFactory() {
		return MediaWikiServices::getInstance()->getRevisionFactory();
	}

	/**
	 * @param bool|string $wiki The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return SqlBlobStore
	 */
	private static function getBlobStore( $wiki = false ) {
		$store = MediaWikiServices::getInstance()
			->getBlobStoreFactory()
			->newSqlBlobStore( $wiki );

		if ( !$store instanceof SqlBlobStore ) {
			throw new RuntimeException(
				'The backwards compatibility code in Revision currently requires the BlobStore '
				. 'service to be an SqlBlobStore instance, but it is a ' . get_class( $store )
			);
		}

		return $store;
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @deprecated since 1.31 together with the class. Hard deprecated since 1.35
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
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionLookup()->getRevisionById( $id, $flags );
		return $rec ? new Revision( $rec, $flags ) : null;
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * @deprecated since 1.31 together with the class. Hard deprecated since 1.35
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
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionLookup()->getRevisionByTitle( $linkTarget, $id, $flags );
		return $rec ? new Revision( $rec, $flags ) : null;
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
	 * @deprecated since 1.31 together with the class. Hard deprecated since 1.35
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return Revision|null
	 */
	public static function newFromPageId( $pageId, $revId = 0, $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionLookup()->getRevisionByPageId( $pageId, $revId, $flags );
		return $rec ? new Revision( $rec, $flags ) : null;
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param object $row
	 * @param array $overrides
	 *
	 * @throws MWException
	 * @return Revision
	 */
	public static function newFromArchiveRow( $row, $overrides = [] ) {
		wfDeprecated( __METHOD__, '1.31' );

		/**
		 * MCR Migration: https://phabricator.wikimedia.org/T183564
		 * This method used to overwrite attributes, then passed to Revision::__construct
		 * RevisionStore::newRevisionFromArchiveRow instead overrides row field names
		 * So do a conversion here.
		 */
		if ( array_key_exists( 'page', $overrides ) ) {
			$overrides['page_id'] = $overrides['page'];
			unset( $overrides['page'] );
		}

		/**
		 * We require a Title for both the Revision object and the RevisionRecord.
		 * Below is duplicated logic from RevisionStore::newRevisionFromArchiveRow
		 * to fetch a title in order pass it into the Revision object.
		 */
		$title = null;
		if ( isset( $overrides['title'] ) ) {
			if ( !( $overrides['title'] instanceof Title ) ) {
				throw new MWException( 'title field override must contain a Title object.' );
			}

			$title = $overrides['title'];
		}
		if ( $title !== null ) {
			if ( isset( $row->ar_namespace ) && isset( $row->ar_title ) ) {
				$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
			} else {
				throw new InvalidArgumentException(
					'A Title or ar_namespace and ar_title must be given'
				);
			}
		}

		$rec = self::getRevisionFactory()->newRevisionFromArchiveRow( $row, 0, $title, $overrides );
		return new Revision( $rec, self::READ_NORMAL, $title );
	}

	/**
	 * @since 1.19
	 *
	 * MCR migration note: replaced by RevisionStore::newRevisionFromRow(). Note that
	 * newFromRow() also accepts arrays, while newRevisionFromRow() does not. Instead,
	 * a MutableRevisionRecord should be constructed directly.
	 * RevisionStore::newMutableRevisionFromArray() can be used as a temporary replacement,
	 * but should be avoided.
	 *
	 * @deprecated since 1.31 together with the Revision class. Hard deprecated since 1.35
	 * @param object|array $row
	 * @return Revision
	 */
	public static function newFromRow( $row ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( is_array( $row ) ) {
			$rec = self::getRevisionFactory()->newMutableRevisionFromArray( $row );
		} else {
			$rec = self::getRevisionFactory()->newRevisionFromRow( $row );
		}

		return new Revision( $rec );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionByPageId() instead.
	 * Hard deprecated since 1.35
	 *
	 * @param IDatabase $db
	 * @param int $pageid
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromPageId( $db, $pageid, $id = 0 ) {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionStore()->loadRevisionFromPageId( $db, $pageid, $id );
		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionByTitle() instead.
	 * Hard deprecated in 1.35
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param int $id
	 * @return Revision|null
	 */
	public static function loadFromTitle( $db, $title, $id = 0 ) {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionStore()->loadRevisionFromTitle( $db, $title, $id );
		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @deprecated since 1.31, use RevisionStore::getRevisionByTimestamp()
	 *   or RevisionStore::loadRevisionFromTimestamp() instead. Hard deprecated since 1.35
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return Revision|null
	 */
	public static function loadFromTimestamp( $db, $title, $timestamp ) {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionStore()->loadRevisionFromTimestamp( $db, $title, $timestamp );
		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new revision object.
	 * @since 1.31
	 * @deprecated since 1.31 (soft), 1.35 (hard), use RevisionStore::getQueryInfo() instead.
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *  - 'text': Join with the text table, and select fields to load page text
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo( $options = [] ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->getQueryInfo( $options );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new archived revision object.
	 * @since 1.31
	 * @deprecated since 1.31 (soft), 1.35 (hard), use RevisionStore::getArchiveQueryInfo()
	 *   instead.
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getArchiveQueryInfo() {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->getArchiveQueryInfo();
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 *
	 * @deprecated in 1.31, use RevisionStore::getRevisionSizes instead.
	 * Hard deprecated since 1.35.
	 *
	 * @param IDatabase $db
	 * @param array $revIds
	 * @return array
	 */
	public static function getParentLengths( $db, array $revIds ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->getRevisionSizes( $revIds );
	}

	/**
	 * @param object|array|RevisionRecord $row Either a database row or an array
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * Since 1.35, constructing with anything other than a RevisionRecord is hard deprecated
	 * (since 1.31 the entire class is deprecated)
	 *
	 * @internal
	 */
	public function __construct( $row, $queryFlags = 0, Title $title = null ) {
		wfDeprecated( __METHOD__, '1.31' );

		global $wgUser;

		if ( $row instanceof RevisionRecord ) {
			$this->mRecord = $row;
		} elseif ( is_array( $row ) ) {
			// If no user is specified, fall back to using the global user object, to stay
			// compatible with pre-1.31 behavior.
			if ( !isset( $row['user'] ) && !isset( $row['user_text'] ) ) {
				$row['user'] = $wgUser;
			}

			$this->mRecord = self::getRevisionFactory()->newMutableRevisionFromArray(
				$row,
				$queryFlags,
				$this->ensureTitle( $row, $queryFlags, $title )
			);
		} elseif ( is_object( $row ) ) {
			$this->mRecord = self::getRevisionFactory()->newRevisionFromRow(
				$row,
				$queryFlags,
				$this->ensureTitle( $row, $queryFlags, $title )
			);
		} else {
			throw new InvalidArgumentException(
				'$row must be a row object, an associative array, or a RevisionRecord'
			);
		}

		Assert::postcondition( $this->mRecord !== null, 'Failed to construct a RevisionRecord' );
	}

	/**
	 * Make sure we have *some* Title object for use by the constructor.
	 * For B/C, the constructor shouldn't fail even for a bad page ID or bad revision ID.
	 *
	 * @param array|object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return Title $title if not null, or a Title constructed from information in $row.
	 */
	private function ensureTitle( $row, $queryFlags, $title = null ) {
		if ( $title ) {
			return $title;
		}

		if ( is_array( $row ) ) {
			if ( isset( $row['title'] ) ) {
				if ( !( $row['title'] instanceof Title ) ) {
					throw new MWException( 'title field must contain a Title object.' );
				}

				return $row['title'];
			}

			$pageId = $row['page'] ?? 0;
			$revId = $row['id'] ?? 0;
		} else {
			$pageId = $row->rev_page ?? 0;
			$revId = $row->rev_id ?? 0;
		}

		try {
			$title = self::getRevisionStore()->getTitle( $pageId, $revId, $queryFlags );
		} catch ( RevisionAccessException $ex ) {
			// construct a dummy title!
			wfLogWarning( __METHOD__ . ': ' . $ex->getMessage() );

			// NOTE: this Title will only be used inside RevisionRecord
			$title = Title::makeTitleSafe( NS_SPECIAL, "Badtitle/ID=$pageId" );
			$title->resetArticleID( $pageId );
		}

		return $title;
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 * @return RevisionRecord
	 */
	public function getRevisionRecord() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord;
	}

	/**
	 * Get revision ID
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return int|null
	 */
	public function getId() {
		wfDeprecated( __METHOD__, '1.31' );
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
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 * @since 1.19
	 * @param int|string $id
	 * @throws MWException
	 */
	public function setId( $id ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $this->mRecord instanceof MutableRevisionRecord ) {
			$this->mRecord->setId( intval( $id ) );
		} else {
			throw new MWException( __METHOD__ . ' is not supported on this instance' );
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
	 * @deprecated since 1.31 (soft), 1.35 (hard), please reuse old Revision object
	 * @param int $id User ID
	 * @param string $name User name
	 * @throws MWException
	 */
	public function setUserIdAndName( $id, $name ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $this->mRecord instanceof MutableRevisionRecord ) {
			$user = User::newFromAnyId( intval( $id ), $name, null );
			$this->mRecord->setUser( $user );
		} else {
			throw new MWException( __METHOD__ . ' is not supported on this instance' );
		}
	}

	/**
	 * @return SlotRecord|null
	 */
	private function getMainSlotRaw() {
		if ( !$this->mRecord->hasSlot( SlotRecord::MAIN ) ) {
			return null;
		}

		return $this->mRecord->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );
	}

	/**
	 * Get the ID of the row of the text table that contains the content of the
	 * revision's main slot, if that content is stored in the text table.
	 *
	 * If the content is stored elsewhere, this returns null.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard), use
	 * RevisionRecord()->getSlot()->getContentAddress() to
	 * get that actual address that can be used with BlobStore::getBlob(); or use
	 * RevisionRecord::hasSameContent() to check if two revisions have the same content.
	 *
	 * @return int|null
	 */
	public function getTextId() {
		wfDeprecated( __METHOD__, '1.31' );
		$slot = $this->getMainSlotRaw();
		return $slot && $slot->hasAddress()
			? self::getBlobStore()->getTextIdFromAddress( $slot->getAddress() )
			: null;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return int|null The ID of the parent revision. 0 indicates that there is no
	 * parent revision. Null indicates that the parent revision is not known.
	 */
	public function getParentId() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->getParentId();
	}

	/**
	 * Returns the length of the text in this revision, or null if unknown.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return int|null
	 */
	public function getSize() {
		wfDeprecated( __METHOD__, '1.31' );
		try {
			return $this->mRecord->getSize();
		} catch ( RevisionAccessException $ex ) {
			return null;
		}
	}

	/**
	 * Returns the base36 sha1 of the content in this revision, or null if unknown.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return string|null
	 */
	public function getSha1() {
		wfDeprecated( __METHOD__, '1.31' );
		try {
			return $this->mRecord->getSha1();
		} catch ( RevisionAccessException $ex ) {
			return null;
		}
	}

	/**
	 * Returns the title of the page associated with this entry.
	 * Since 1.31, this will never return null.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * Will do a query, when title is not set and id is given.
	 *
	 * @return Title
	 */
	public function getTitle() {
		wfDeprecated( __METHOD__, '1.31' );
		$linkTarget = $this->mRecord->getPageAsLinkTarget();
		return Title::newFromLinkTarget( $linkTarget );
	}

	/**
	 * Set the title of the revision
	 *
	 * @deprecated since 1.31, this is now a noop. Pass the Title to the constructor instead.
	 * hard deprecated since 1.35
	 *
	 * @param Title $title
	 */
	public function setTitle( $title ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( !$title->equals( $this->getTitle() ) ) {
			throw new InvalidArgumentException(
				$title->getPrefixedText()
					. ' is not the same as '
					. $this->mRecord->getPageAsLinkTarget()->__toString()
			);
		}
	}

	/**
	 * Get the page ID
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return int|null
	 */
	public function getPage() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->getPageId();
	}

	/**
	 * Fetch revision's user id if it's available to the specified audience.
	 * If the specified audience does not have access to it, zero will be
	 * returned.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the ID regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (not passing for FOR_THIS_USER is deprecated since 1.35)
	 * @return int
	 */
	public function getUser( $audience = self::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $audience === self::FOR_THIS_USER && !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$user = $this->mRecord->getUser( $audience, $user );
		return $user ? $user->getId() : 0;
	}

	/**
	 * Fetch revision's username if it's available to the specified audience.
	 * If the specified audience does not have access to the username, an
	 * empty string will be returned.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (not passing for FOR_THIS_USER is deprecated since 1.35)
	 * @return string
	 */
	public function getUserText( $audience = self::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $audience === self::FOR_THIS_USER && !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$user = $this->mRecord->getUser( $audience, $user );
		return $user ? $user->getName() : '';
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (not passing for FOR_THIS_USER is deprecated since 1.35)
	 *
	 * @return string|null Returns null if the specified audience does not have access to the
	 *  comment.
	 */
	public function getComment( $audience = self::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $audience === self::FOR_THIS_USER && !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$comment = $this->mRecord->getComment( $audience, $user );
		return $comment === null ? null : $comment->text;
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return bool
	 */
	public function isMinor() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->isMinor();
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled() {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->getRcIdIfUnpatrolled( $this->mRecord );
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $flags (optional) $flags include:
	 *      Revision::READ_LATEST  : Select the data from the master
	 *
	 * @since 1.22
	 * @return RecentChange|null
	 */
	public function getRecentChange( $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->getRecentChange( $this->mRecord, $flags );
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->isDeleted( $field );
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return int
	 */
	public function getVisibility() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->getVisibility();
	}

	/**
	 * Fetch revision content if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this
	 * revision, or the content could not be loaded, null will be returned.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to $user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @since 1.21
	 * @return Content|null
	 */
	public function getContent( $audience = self::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.31' );

		global $wgUser;

		if ( $audience === self::FOR_THIS_USER && !$user ) {
			$user = $wgUser;
		}

		try {
			return $this->mRecord->getContent( SlotRecord::MAIN, $audience, $user );
		}
		catch ( RevisionAccessException $e ) {
			return null;
		}
	}

	/**
	 * Get original serialized data (without checking view restrictions)
	 *
	 * @since 1.21
	 * @deprecated since 1.31 (soft), 1.35 (hard), use BlobStore::getBlob instead.
	 *
	 * @return string
	 */
	public function getSerializedData() {
		wfDeprecated( __METHOD__, '1.31' );
		$slot = $this->getMainSlotRaw();
		return $slot ? $slot->getContent()->serialize() : '';
	}

	/**
	 * Returns the content model for the main slot of this revision.
	 *
	 * If no content model was stored in the database, the default content model for the title is
	 * used to determine the content model to use. If no title is know, CONTENT_MODEL_WIKITEXT
	 * is used as a last resort.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return string The content model id associated with this revision,
	 *     see the CONTENT_MODEL_XXX constants.
	 */
	public function getContentModel() {
		wfDeprecated( __METHOD__, '1.31' );

		$slot = $this->getMainSlotRaw();

		if ( $slot ) {
			return $slot->getModel();
		} else {
			$slotRoleRegistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();
			$slotRoleHandler = $slotRoleRegistry->getRoleHandler( SlotRecord::MAIN );
			return $slotRoleHandler->getDefaultModel( $this->getTitle() );
		}
	}

	/**
	 * Returns the content format for the main slot of this revision.
	 *
	 * If no content format was stored in the database, the default format for this
	 * revision's content model is returned.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return string The content format id associated with this revision,
	 *     see the CONTENT_FORMAT_XXX constants.
	 */
	public function getContentFormat() {
		wfDeprecated( __METHOD__, '1.31' );

		$slot = $this->getMainSlotRaw();
		$format = $slot ? $this->getMainSlotRaw()->getFormat() : null;

		if ( $format === null ) {
			// if no format was stored along with the blob, fall back to default format
			$format = $this->getContentHandler()->getDefaultFormat();
		}

		return $format;
	}

	/**
	 * Returns the content handler appropriate for this revision's content model.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @throws MWException
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		wfDeprecated( __METHOD__, '1.31' );

		return MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $this->getContentModel() );
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return string
	 */
	public function getTimestamp() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->getTimestamp();
	}

	/**
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return bool
	 */
	public function isCurrent() {
		wfDeprecated( __METHOD__, '1.31' );
		return $this->mRecord->isCurrent();
	}

	/**
	 * Get previous revision for this title
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @return Revision|null
	 */
	public function getPrevious() {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionLookup()->getPreviousRevision( $this->mRecord );
		return $rec ? new Revision( $rec, self::READ_NORMAL, $this->getTitle() ) : null;
	}

	/**
	 * Get next revision for this title
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard), use RevisionLookup::getNextRevision instead
	 *
	 * @return Revision|null
	 */
	public function getNext() {
		wfDeprecated( __METHOD__, '1.31' );
		$rec = self::getRevisionLookup()->getNextRevision( $this->mRecord );
		return $rec ? new Revision( $rec, self::READ_NORMAL, $this->getTitle() ) : null;
	}

	/**
	 * Get revision text associated with an old or archive row
	 *
	 * If the text field is not included, this uses RevisionStore to load the appropriate slot
	 * and return its serialized content. This is the default backwards-compatibility behavior
	 * when reading from the MCR aware database schema is enabled. For this to work, either
	 * the revision ID or the page ID must be included in the row.
	 *
	 * When using the old text field, the flags field must also be set. Including the old_id
	 * field will activate cache usage as long as the $wiki parameter is not set.
	 *
	 * @deprecated since 1.32, use RevisionStore::newRevisionFromRow instead.
	 *
	 * @param stdClass $row The text data. If a falsy value is passed instead, false is returned.
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string|false Text the text requested or false on failure
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {
		wfDeprecated( __METHOD__, '1.32' );

		if ( !$row ) {
			return false;
		}

		$textField = $prefix . 'text';

		if ( isset( $row->$textField ) ) {
			throw new LogicException(
				'Cannot use ' . __METHOD__ . ' with the ' . $textField . ' field since 1.35.'
			);
		}

		// Missing text field, we are probably looking at the MCR-enabled DB schema.
		$store = self::getRevisionStore( $wiki );
		$rev = $prefix === 'ar_'
			? $store->newRevisionFromArchiveRow( $row )
			: $store->newRevisionFromRow( $row );

		$content = $rev->getContent( SlotRecord::MAIN );
		return $content ? $content->serialize() : false;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param string &$text
	 * @return string
	 */
	public static function compressRevisionText( &$text ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getBlobStore()->compressData( $text );
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param string|false $text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public static function decompressRevisionText( $text, $flags ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( $text === false ) {
			// Text failed to be fetched; nothing to do
			return false;
		}

		return self::getBlobStore()->decompressData( $text, $flags );
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param IDatabase $dbw (master connection)
	 * @throws MWException
	 * @return int The revision ID
	 */
	public function insertOn( $dbw ) {
		wfDeprecated( __METHOD__, '1.31' );

		global $wgUser;

		// Note that $this->mRecord->getId() will typically return null here, but not always,
		// e.g. not when restoring a revision.

		if ( $this->mRecord->getUser( RevisionRecord::RAW ) === null ) {
			if ( $this->mRecord instanceof MutableRevisionRecord ) {
				$this->mRecord->setUser( $wgUser );
			} else {
				throw new MWException( 'Cannot insert revision with no associated user.' );
			}
		}

		$rec = self::getRevisionStore()->insertRevisionOn( $this->mRecord, $dbw );

		$this->mRecord = $rec;
		Assert::postcondition( $this->mRecord !== null, 'Failed to acquire a RevisionRecord' );

		return $rec->getId();
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 * @param string $text
	 * @return string
	 */
	public static function base36Sha1( $text ) {
		wfDeprecated( __METHOD__, '1.31' );
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
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId ID number of the page to read from
	 * @param string $summary Revision's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User|null $user User object to use or null for $wgUser
	 * @return Revision|null Revision or null on error
	 */
	public static function newNullRevision( $dbw, $pageId, $summary, $minor, $user = null ) {
		wfDeprecated( __METHOD__, '1.35' );

		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$comment = CommentStoreComment::newUnsavedComment( $summary, null );

		$title = Title::newFromID( $pageId, Title::READ_LATEST );
		if ( $title === null ) {
			return null;
		}

		$rec = self::getRevisionStore()->newNullRevision( $dbw, $title, $comment, $minor, $user );

		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param int $field One of self::DELETED_TEXT,
	 *                              self::DELETED_COMMENT,
	 *                              self::DELETED_USER
	 * @param User|null $user User object to check, or null to use $wgUser (deprecated since 1.35)
	 * @return bool
	 */
	public function userCan( $field, User $user = null ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}
		return RevisionRecord::userCanBitfield( $this->mRecord->getVisibility(), $field, $user );
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
		wfDeprecated( __METHOD__, '1.31' );
		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		return RevisionRecord::userCanBitfield( $bitfield, $field, $user, $title );
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * @deprecated since 1.35
	 *
	 * @param Title $title (ignored since 1.34)
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	public static function getTimestampFromId( $title, $id, $flags = 0 ) {
		wfDeprecated( __METHOD__, '1.35' );
		return self::getRevisionStore()->getTimestampFromId( $id, $flags );
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	public static function countByPageId( $db, $id ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->countRevisionsByPageId( $db, $id );
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @deprecated since 1.31 (soft), 1.35 (hard)
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	public static function countByTitle( $db, $title ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getRevisionStore()->countRevisionsByTitle( $db, $title );
	}

	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 *
	 * @since 1.20
	 * @deprecated since 1.24 (soft), 1.35 (hard)
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
		wfDeprecated( __METHOD__, '1.24' );
		if ( is_int( $db ) ) {
			$db = wfGetDB( $db );
		}

		return self::getRevisionStore()->userWasLastToEdit( $db, $pageId, $userId, $since );
	}

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 * The title will also be loaded if $pageIdOrTitle is an integer ID.
	 *
	 * @param IDatabase $db ignored!
	 * @param int|Title $pageIdOrTitle Page ID or Title object
	 * @param int $revId Known current revision of this page. Determined automatically if not given.
	 * @return Revision|bool Returns false if missing
	 * @since 1.28
	 * @deprecated since 1.31 and hard deprecated since 1.35
	 */
	public static function newKnownCurrent( IDatabase $db, $pageIdOrTitle, $revId = 0 ) {
		wfDeprecated( __METHOD__, '1.31' );
		$title = $pageIdOrTitle instanceof Title
			? $pageIdOrTitle
			: Title::newFromID( $pageIdOrTitle );

		if ( !$title ) {
			return false;
		}

		$record = self::getRevisionLookup()->getKnownCurrentRevision( $title, $revId );
		return $record ? new Revision( $record ) : false;
	}
}
