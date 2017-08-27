<?php

namespace MediaWiki\Storage;

/**
 * Service for looking up page revisions.
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

use DBAccessObjectUtils;
use \IDBAccessObject;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MWException;
use RecentChange;
use Title;
use User;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * Service for looking up page revisions.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in RevisionRecord.
 */
class RevisionStore implements IDBAccessObject {

	/**
	 * @var RevisionContentStore
	 */
	private $blobStore;

	/**
	 * RevisionLookup constructor.
	 *
	 * @param RevisionContentStore $blobStore
	 */
	public function __construct( RevisionContentStore $blobStore ) {
		$this->blobStore = $blobStore;
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	public function newFromId( $id, $flags = 0 ) {
		return self::newFromConds( [ 'rev_id' => intval( $id ) ], $flags );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $id (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function newFromTitle( LinkTarget $linkTarget, $id = 0, $flags = 0 ) {
		$conds = [
			'page_namespace' => $linkTarget->getNamespace(),
			'page_title' => $linkTarget->getDBkey()
		];
		if ( $id ) {
			// Use the specified ID
			$conds['rev_id'] = $id;
			return self::newFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$conds[] = 'rev_id=page_latest';
			$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
			return self::loadFromConds( $db, $conds, $flags );
		}
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master (since 1.20)
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function newFromPageId( $pageId, $revId = 0, $flags = 0 ) {
		$conds = [ 'page_id' => $pageId ];
		if ( $revId ) {
			$conds['rev_id'] = $revId;
			return self::newFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$conds[] = 'rev_id = page_latest';
			$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
			return self::loadFromConds( $db, $conds, $flags );
		}
	}
	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 * @todo FIXME: Should be a subclass for RevisionDelete. [TS]
	 *
	 * @param object $row
	 * @param array $overrides
	 *
	 * @throws MWException
	 * @return RevisionRecord
	 */
	public function newFromArchiveRow( $row, $overrides = [] ) {
		global $wgContentHandlerUseDB;

		$attribs = $overrides + [
				'page'       => isset( $row->ar_page_id ) ? $row->ar_page_id : null,
				'id'         => isset( $row->ar_rev_id ) ? $row->ar_rev_id : null,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'text_id'    => isset( $row->ar_text_id ) ? $row->ar_text_id : null,
				'deleted'    => $row->ar_deleted,
				'len'        => $row->ar_len,
				'sha1'       => isset( $row->ar_sha1 ) ? $row->ar_sha1 : null,
				'content_model'   => isset( $row->ar_content_model ) ? $row->ar_content_model : null,
				'content_format'  => isset( $row->ar_content_format ) ? $row->ar_content_format : null,
			];

		if ( !$wgContentHandlerUseDB ) {
			unset( $attribs['content_model'] );
			unset( $attribs['content_format'] );
		}

		if ( !isset( $attribs['title'] )
			&& isset( $row->ar_namespace )
			&& isset( $row->ar_title )
		) {
			$attribs['title'] = Title::makeTitle( $row->ar_namespace, $row->ar_title );
		}

		if ( isset( $row->ar_text ) && !$row->ar_text_id ) {
			// Pre-1.5 ar_text row
			$attribs['text'] = $this->blobStore->getRevisionText( $row, 'ar_' );
			if ( $attribs['text'] === false ) {
				throw new MWException( 'Unable to load text from archive row (possibly T24624)' );
			}
		}

		// FIXME: audience!
		return new LazyRevisionRecord( $attribs );
	}

	/**
	 * @since 1.19
	 *
	 * @param object $row
	 * @return RevisionRecord
	 */
	public function newFromRow( $row ) {
		// FIXME: text_row / text_callback; audience!
		return new LazyRevisionRecord( $row );
	}


	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param int $id
	 * @return RevisionRecord|null
	 */
	public function loadFromTitle( $db, $title, $id = 0 ) {
		if ( $id ) {
			$matchId = intval( $id );
		} else {
			$matchId = 'page_latest';
		}
		return self::loadFromConds( $db,
			[
				"rev_id=$matchId",
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function loadFromTimestamp( $db, $title, $timestamp ) {
		return self::loadFromConds( $db,
			[
				'rev_timestamp' => $db->timestamp( $timestamp ),
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);
	}

	/**
	 * Given a set of conditions, fetch a revision
	 *
	 * This method is used then a revision ID is qualified and
	 * will incorporate some basic replica DB/master fallback logic
	 *
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	private function newFromConds( $conditions, $flags = 0 ) {
		$db = wfGetDB( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

		$rev = self::loadFromConds( $db, $conditions, $flags );
		// Make sure new pending/committed revision are visibile later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & self::READ_LATEST )
			&& wfGetLB()->getServerCount() > 1
			&& wfGetLB()->hasOrMadeRecentMasterChanges()
		) {
			$flags = self::READ_LATEST;
			$db = wfGetDB( DB_MASTER );
			$rev = self::loadFromConds( $db, $conditions, $flags );
		}

		if ( $rev ) {
			$rev->mQueryFlags = $flags;
		}

		return $rev;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	private function loadFromConds( $db, $conditions, $flags = 0 ) {
		$row = self::fetchFromConds( $db, $conditions, $flags );
		if ( $row ) {
			// FIXME: text_row / text_callback; audience!
			$rev = new LazyRevisionRecord( $row, $db->getWikiID() );

			return $rev;
		}

		return null;
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * @param IDatabase $db
	 * @param int $id
	 * @return RevisionRecord|null
	 */
	public function loadFromId( $db, $id ) {
		return self::loadFromConds( $db, [ 'rev_id' => intval( $id ) ] );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * @param IDatabase $db
	 * @param int $pageid
	 * @param int $id
	 * @return RevisionRecord|null
	 */
	public function loadFromPageId( $db, $pageid, $id = 0 ) {
		$conds = [ 'rev_page' => intval( $pageid ), 'page_id' => intval( $pageid ) ];
		if ( $id ) {
			$conds['rev_id'] = intval( $id );
		} else {
			$conds[] = 'rev_id=page_latest';
		}
		return self::loadFromConds( $db, $conds );
	}

	/**
	 * Return a wrapper for a series of database rows to
	 * fetch all of a given page's revisions in turn.
	 * Each row can be fed to the constructor to get objects.
	 *
	 * @param LinkTarget $title
	 * @return ResultWrapper
	 * @deprecated Since 1.28
	 */
	public function fetchRevision( LinkTarget $title ) {
		$row = self::fetchFromConds(
			wfGetDB( DB_REPLICA ),
			[
				'rev_id=page_latest',
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);

		return new FakeResultWrapper( $row ? [ $row ] : [] );
	}

	/**
	 * Given a set of conditions, return a ResultWrapper
	 * which will return matching database rows with the
	 * fields necessary to build RevisionRecord objects.
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 *
	 * @return object data row as a raw object
	 */
	private function fetchFromConds( $db, $conditions, $flags = 0 ) {
		$fields = array_merge(
			self::selectFields(),
			self::selectPageFields(),
			self::selectUserFields()
		);
		$options = [];
		if ( ( $flags & self::READ_LOCKING ) == self::READ_LOCKING ) {
			$options[] = 'FOR UPDATE';
		}
		return $db->selectRow(
			[ 'revision', 'page', 'user' ],
			$fields,
			$conditions,
			__METHOD__,
			$options,
			[ 'page' => self::pageJoinCond(), 'user' => self::userJoinCond() ]
		);
	}

	/**
	 * Return the value of a select() JOIN conds array for the user table.
	 * This will get user table rows for logged-in users.
	 * @since 1.19
	 * @return array
	 */
	public function userJoinCond() {
		return [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ];
	}

	/**
	 * Return the value of a select() page conds array for the page table.
	 * This will assure that the revision(s) are not orphaned from live pages.
	 * @since 1.19
	 * @return array
	 */
	public function pageJoinCond() {
		return [ 'INNER JOIN', [ 'page_id = rev_page' ] ];
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 * @return array
	 */
	public function selectFields() {
		global $wgContentHandlerUseDB;

		$fields = [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_comment',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'rev_content_format';
			$fields[] = 'rev_content_model';
		}

		return $fields;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision from an archive row.
	 * @return array
	 */
	public function selectArchiveFields() {
		global $wgContentHandlerUseDB;
		$fields = [
			'ar_id',
			'ar_page_id',
			'ar_rev_id',
			'ar_text',
			'ar_text_id',
			'ar_timestamp',
			'ar_comment',
			'ar_user_text',
			'ar_user',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}
		return $fields;
	}

	/**
	 * Return the list of text fields that should be selected to read the
	 * revision text
	 * @return array
	 */
	public function selectTextFields() {
		return [
			'old_text',
			'old_flags'
		];
	}

	/**
	 * Return the list of page fields that should be selected from page table
	 * @return array
	 */
	public function selectPageFields() {
		return [
			'page_namespace',
			'page_title',
			'page_id',
			'page_latest',
			'page_is_redirect',
			'page_len',
		];
	}

	/**
	 * Return the list of user fields that should be selected from user table
	 * @return array
	 */
	public function selectUserFields() {
		return [ 'user_name' ];
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 * @param IDatabase $db
	 * @param array $revIds
	 * @return array
	 */
	public function getParentLengths( $db, array $revIds ) {
		$revLens = [];
		if ( !$revIds ) {
			return $revLens; // empty
		}
		$res = $db->select( 'revision',
			[ 'rev_id', 'rev_len' ],
			[ 'rev_id' => $revIds ],
			__METHOD__ );
		foreach ( $res as $row ) {
			$revLens[$row->rev_id] = $row->rev_len;
		}
		return $revLens;
	}

	/**
	 * Get previous revision for this title
	 *
	 * @param Title $title
	 * @param $revId
	 *
	 * @return RevisionRecord|null
	 */
	public function getPrevious( Title $title, $revId ) {
		$prev = $title->getPreviousRevisionID( $revId );
		if ( $prev ) {
			return $this->newFromTitle( $title, $prev );
		}
		return null;
	}

	/**
	 * Get next revision for this title
	 *
	 * @return RevisionRecord|null
	 */
	public function getNext( Title $title, $revId ) {
		$next = $title->getNextRevisionID( $revId );
		if ( $next ) {
			return self::newFromTitle( $title, $next );
		}
		return null;
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * @todo move this somewhere else
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *
	 * @return null|RecentChange
	 * @since 1.22
	 */
	public function getRecentChange( RevisionRecord $rev, $flags = 0 ) {
		$dbr = wfGetDB( DB_REPLICA );

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );

		return RecentChange::newFromConds(
			[
				'rc_user_text' => $rev->getUserText( RevisionRecord::RAW ),
				'rc_timestamp' => $dbr->timestamp( $rev->getTimestamp() ),
				'rc_this_oldid' => $rev->getId()
			],
			__METHOD__,
			$dbType
		);
	}

	/**
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled( RevisionRecord $rev ) {
		if ( $this->mUnpatrolled !== null ) {
			return $this->mUnpatrolled;
		}
		$rc = $this->getRecentChange();
		if ( $rc && $rc->getAttribute( 'rc_patrolled' ) == 0 ) {
			$this->mUnpatrolled = $rc->getAttribute( 'rc_id' );
		} else {
			$this->mUnpatrolled = 0;
		}
		return $this->mUnpatrolled;
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 *
	 * @throws MWException
	 * @throws UnexpectedValueException
	 * @return int
	 */
	public function insertOn( RevisionRecord $rev, $dbw, $flags = 0 ) {
		global $wgDefaultExternalStore, $wgContentHandlerUseDB;

		// We're inserting a new revision, so we have to use master anyway.
		// If it's a null revision, it may have references to rows that
		// are not in the replica yet (the text row).
		$flags |= self::READ_LATEST;

		// Not allowed to have rev_page equal to 0, false, etc.
		if ( !$this->mPage ) {
			$title = $this->getTitle();
			if ( $title instanceof Title ) {
				$titleText = ' for page ' . $title->getPrefixedText();
			} else {
				$titleText = '';
			}
			throw new MWException( "Cannot insert revision$titleText: page ID must be nonzero" );
		}

		$this->checkContentModel();

		$data = $this->mText;
		$flags = self::compressRevisionText( $data );

		# Write to external storage if required
		if ( $wgDefaultExternalStore ) {
			// Store and get the URL
			$data = ExternalStore::insertToDefault( $data );
			if ( !$data ) {
				throw new MWException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		# Record the text (or external storage URL) to the text table
		if ( $this->mTextId === null ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
			              [
				              'old_id' => $old_id,
				              'old_text' => $data,
				              'old_flags' => $flags,
			              ], __METHOD__
			);
			$this->mTextId = $dbw->insertId();
		}

		if ( $this->mComment === null ) {
			$this->mComment = "";
		}

		# Record the edit in revisions
		$rev_id = $this->mId !== null
			? $this->mId
			: $dbw->nextSequenceValue( 'revision_rev_id_seq' );
		$row = [
			'rev_id'         => $rev_id,
			'rev_page'       => $this->mPage,
			'rev_text_id'    => $this->mTextId,
			'rev_comment'    => $this->mComment,
			'rev_minor_edit' => $this->mMinorEdit ? 1 : 0,
			'rev_user'       => $this->mUser,
			'rev_user_text'  => $this->mUserText,
			'rev_timestamp'  => $dbw->timestamp( $this->mTimestamp ),
			'rev_deleted'    => $this->mDeleted,
			'rev_len'        => $this->mSize,
			'rev_parent_id'  => $this->mParentId === null
				? $this->getPreviousRevisionId( $dbw )
				: $this->mParentId,
			'rev_sha1'       => $this->mSha1 === null
				? self::base36Sha1( $this->mText )
				: $this->mSha1,
		];

		if ( $wgContentHandlerUseDB ) {
			// NOTE: Store null for the default model and format, to save space.
			// XXX: Makes the DB sensitive to changed defaults.
			// Make this behavior optional? Only in miser mode?

			$model = $this->getContentModel();
			$format = $this->getContentFormat();

			$title = $this->getTitle();

			if ( $title === null ) {
				throw new MWException( "Insufficient information to determine the title of the "
				                       . "revision's page!" );
			}

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

			$row['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
			$row['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
		}

		$dbw->insert( 'revision', $row, __METHOD__ );

		if ( $this->mId === null ) {
			// Only if nextSequenceValue() was called
			$this->mId = $dbw->insertId();
		}

		// Assertion to try to catch T92046
		if ( (int)$this->mId === 0 ) {
			throw new UnexpectedValueException(
				'After insert, RevisionRecord mId is ' . var_export( $this->mId, 1 ) . ': ' .
				var_export( $row, 1 )
			);
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$revision = $this;
		Hooks::run( 'RevisionInsertComplete', [ &$revision, $data, $flags ] );

		return $this->mId;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * @param IDatabase $db
	 * @return int
	 */
	private function getPreviousRevisionId( $db ) {
		if ( $this->mPage === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$this->mId ) {
			$prevId = $db->selectField( 'page', 'page_latest',
			                            [ 'page_id' => $this->mPage ],
			                            __METHOD__ );
		} else {
			$prevId = $db->selectField( 'revision', 'rev_id',
			                            [ 'rev_page' => $this->mPage, 'rev_id < ' . $this->mId ],
			                            __METHOD__,
			                            [ 'ORDER BY' => 'rev_id DESC' ] );
		}
		return intval( $prevId );
	}

	/**
	 * @todo FIXME move to page updater
	 * @throws MWException
	 */
	private function checkContentModel() {
		global $wgContentHandlerUseDB;

		// Note: may return null for revisions that have not yet been inserted
		$title = $this->getTitle();

		$model = $this->getContentModel();
		$format = $this->getContentFormat();
		$handler = $this->getContentHandler();

		if ( !$handler->isSupportedFormat( $format ) ) {
			$t = $title->getPrefixedDBkey();

			throw new MWException( "Can't use format $format with content model $model on $t" );
		}

		if ( !$wgContentHandlerUseDB && $title ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $this->getContentModel() != $defaultModel ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't save non-default content model with "
				                       . "\$wgContentHandlerUseDB disabled: model is $model, "
				                       . "default for $t is $defaultModel" );
			}

			if ( $this->getContentFormat() != $defaultFormat ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't use non-default content format with "
				                       . "\$wgContentHandlerUseDB disabled: format is $format, "
				                       . "default for $t is $defaultFormat" );
			}
		}

		$content = $this->getContent( self::RAW );
		$prefixedDBkey = $title->getPrefixedDBkey();
		$revId = $this->mId;

		if ( !$content ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) could not be loaded for validation!"
			);
		}
		if ( !$content->isValid() ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) is not valid! Content model is $model"
			);
		}
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 * @param string $text
	 * @return string
	 */
	public static function base36Sha1( $text ) {
		return \Wikimedia\base_convert( sha1( $text ), 16, 36, 31 );
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * @todo FIXME: move this to the page update interface!
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId ID number of the page to read from
	 * @param string $summary RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User|null $user User object to use or null for $wgUser
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision( $dbw, $pageId, $summary, $minor, $user = null ) {
		global $wgContentHandlerUseDB, $wgContLang;

		$fields = [ 'page_latest', 'page_namespace', 'page_title',
			'rev_text_id', 'rev_len', 'rev_sha1' ];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'rev_content_model';
			$fields[] = 'rev_content_format';
		}

		$current = $dbw->selectRow(
			[ 'page', 'revision' ],
			$fields,
			[
				'page_id' => $pageId,
				'page_latest=rev_id',
			],
			__METHOD__,
			[ 'FOR UPDATE' ] // T51581
		);

		if ( $current ) {
			if ( !$user ) {
				global $wgUser;
				$user = $wgUser;
			}

			// Truncate for whole multibyte characters
			$summary = $wgContLang->truncate( $summary, 255 );

			$row = [
				'page'       => $pageId,
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len,
				'sha1'       => $current->rev_sha1
			];

			if ( $wgContentHandlerUseDB ) {
				$row['content_model'] = $current->rev_content_model;
				$row['content_format'] = $current->rev_content_format;
			}

			$row['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			// FIXME: audience!
			$revision = new LazyRevisionRecord( $row );
		} else {
			$revision = null;
		}

		return $revision;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	static function countByPageId( $db, $id ) {
		$row = $db->selectRow( 'revision', [ 'revCount' => 'COUNT(*)' ],
		                       [ 'rev_page' => $id ], __METHOD__ );
		if ( $row ) {
			return $row->revCount;
		}
		return 0;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	public function countByTitle( $db, $title ) {
		$id = $title->getArticleID();
		if ( $id ) {
			return self::countByPageId( $db, $id );
		}
		return 0;
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
	public function userWasLastToEdit( $db, $pageId, $userId, $since ) {
		if ( !$userId ) {
			return false;
		}

		if ( is_int( $db ) ) {
			$db = wfGetDB( $db );
		}

		$res = $db->select( 'revision',
		                    'rev_user',
		                    [
			                    'rev_page' => $pageId,
			                    'rev_timestamp > ' . $db->addQuotes( $db->timestamp( $since ) )
		                    ],
		                    __METHOD__,
		                    [ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ] );
		foreach ( $res as $row ) {
			if ( $row->rev_user != $userId ) {
				return false;
			}
		}
		return true;
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
	 * @return RevisionRecord|bool Returns false if missing
	 * @since 1.28
	 */
	public function newKnownCurrent( IDatabase $db, $pageId, $revId ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache(); !!!serialzize!!!
		return $cache->getWithSetCallback(
		// Page/rev IDs passed in from DB to reflect history merges
			$cache->makeGlobalKey( 'revision', $db->getWikiID(), $pageId, $revId ),
			$cache::TTL_WEEK,
			function ( $curValue, &$ttl, array &$setOpts ) use ( $db, $pageId, $revId ) {
				$setOpts += Database::getCacheSetOptions( $db );

				$rev = $this->loadFromPageId( $db, $pageId, $revId );
				// Reflect revision deletion and user renames
				if ( $rev ) {
					$rev->mTitle = null; // mutable; lazy-load
					$rev->setRefreshCallback( function( LazyRevisionRecord $rev ) {
						return $this->loadMutableFields( $rev->getId(), $rev->getWiki() );
					} );
				}

				return $rev ?: false; // don't cache negatives
			}
		);
	}

	/**
	 * For cached revisions, make sure mutable fields are up-to-date
	 *
	 * @returns object a revision row as a raw object
	 */
	private function loadMutableFields( $revId, $wikiId = false ) {
		$dbr = wfGetLB( $wikiId )->getConnectionRef( DB_REPLICA, [], $wikiId );
		$row = $dbr->selectRow(
			[ 'revision', 'user' ],
			[ 'rev_deleted', 'user_name' ],
			[ 'rev_id' => $revId, 'user_id = rev_user' ],
			__METHOD__
		);

		return $row;
	}

	/**
	 * Returns the title of the page associated with this entry or null.
	 *
	 * Will do a query, when title is not set and id is given.
	 *
	 * @param RevisionRecord $rev
	 * @return null|Title
	 */
	public function getTitle( RevisionRecord $rev ) {
		$title = null;
		$wikiId = $rev->getWiki();

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		$dbr = wfGetLB( $wikiId )->getConnectionRef( DB_REPLICA, [], $wikiId );
		$row = $dbr->selectRow(
			[ 'page', 'revision' ],
			self::selectPageFields(),
			[ 'page_id=rev_page', 'rev_id' => $rev->getId() ],
			__METHOD__
		);
		if ( $row ) {
			// @TODO: better foreign title handling
			$title = Title::newFromRow( $row );
		}

		if ( $wikiId === false || $wikiId === wfWikiID() ) {
			// Loading by ID is best, though not possible for foreign titles
			if ( !$title && $rev->getPage() !== null && $rev->getPage() > 0 ) {
				$this->mTitle = Title::newFromID( $this->mPage );
			}
		}

		return $title;
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * @param Title $title
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	public function getTimestampFromId( $title, $id, $flags = 0 ) {
		$db = ( $flags & IDBAccessObject::READ_LATEST )
			? wfGetDB( DB_MASTER )
			: wfGetDB( DB_REPLICA );
		// Casting fix for databases that can't take '' for rev_id
		if ( $id == '' ) {
			$id = 0;
		}
		$conds = [ 'rev_id' => $id ];
		$conds['rev_page'] = $title->getArticleID();
		$timestamp = $db->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );

		return ( $timestamp !== false ) ? wfTimestamp( TS_MW, $timestamp ) : false;
	}

}
