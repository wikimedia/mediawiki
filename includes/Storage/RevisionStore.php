<?php
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

namespace MediaWiki\Storage;

use CommentStore;
use Content;
use ContentHandler;
use DBAccessObjectUtils;
use ExternalStore;
use Hooks;
use \IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MWException;
use RecentChange;
use Title;
use UnexpectedValueException;
use User;
use WANObjectCache;
use Wikibase\Lib\Store\StorageException;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Service for looking up page revisions.
 *
 * @since 1.30
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionStore implements IDBAccessObject, RevisionFactory, RevisionLookup {

	/**
	 * @var ContentStore
	 */
	private $contentStore;

	/**
	 * @var bool|string
	 */
	private $wikiId;

	/**
	 * @var boolean
	 */
	private $useExternalStore = false;

	/**
	 * @var boolean
	 */
	private $contentHandlerUseDB = true;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * RevisionLookup constructor.
	 *
	 * @param ContentStore $blobStore
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		ContentStore $blobStore,
		WANObjectCache $cache,
		$wikiId = false
	) {
		$this->loadBalancer = $loadBalancer;
		$this->contentStore = $blobStore;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return boolean
	 */
	public function getUseExternalStore() {
		return $this->useExternalStore;
	}

	/**
	 * @param boolean $useExternalStore
	 */
	public function setUseExternalStore( $useExternalStore ) {
		$this->useExternalStore = $useExternalStore;
	}

	/**
	 * @return boolean
	 */
	public function getContentHandlerUseDB() {
		return $this->contentHandlerUseDB;
	}

	/**
	 * @param boolean $contentHandlerUseDB
	 */
	public function setContentHandlerUseDB( $contentHandlerUseDB ) {
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @return LoadBalancer
	 */
	public function getLoadBalancer() {
		return $this->loadBalancer;
	}

	/**
	 * @param LoadBalancer $loadBalancer
	 */
	public function setLoadBalancer( $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @return LoadBalancer
	 */
	private function getDBLoadBalancer() {
		return $this->loadBalancer;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $mode ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * @param IDatabase $connection
	 */
	private function releaseDBConnection( IDatabase $connection ) {
		$lb = $this->getDBLoadBalancer();
		$lb->reuseConnection( $connection );
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * Determines the page Title based on the available information.
	 *
	 * MCR migration note: this corresponds to Revision::getTitle
	 *
	 * @param int|null $pageId
	 * @param int|null $revId
	 *
	 * @throws StorageException
	 * @return Title
	 */
	private function getTitle( $pageId, $revId ) {
		if ( !$pageId && !$revId ) {
			throw new InvalidArgumentException( '$pageId and $revId cannot both be 0 or null' );
		}

		$title = null;

		// Loading by ID is best, though not possible for foreign titles
		if ( $pageId !== null && $pageId > 0 && $this->wikiId === false ) {
			$title = Title::newFromID( $pageId );
		}

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		if ( !$title && $revId !== null && $revId > 0 ) {
			$dbr = $this->getDbConnectionRef( DB_REPLICA );
			$row = $dbr->selectRow(
				[ 'page', 'revision' ],
				$this->selectPageFields(),
				[ 'page_id=rev_page', 'rev_id' => $revId ],
				__METHOD__
			);
			if ( $row ) {
				// TODO: better foreign title handling (introduce TitleFactory)
				$title = Title::newFromRow( $row );
			}
		}

		if ( $title ) {
			throw new StorageException(
				"Could not determine title for page ID $pageId and revision ID $revId"
			);
		}

		return $title;
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * MCR migration note: this replaces Revision::insertOn
	 *
	 * @param MutableRevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 * @param int $flags
	 *
	 * @throws MWException
	 * @return RevisionRecord the new revision record.
	 */
	public function insertRevisionOn( MutableRevisionRecord $rev, IDatabase $dbw, $flags = 0 ) {
		// TODO: pass in a DBTransactionContext instead of a database connection!
		$this->checkDatabaseWikiId( $dbw );

		// We're inserting a new revision, so we have to use master anyway.
		// If it's a null revision, it may have references to rows that
		// are not in the replica yet (the text row).
		$flags |= self::READ_LATEST; // FIXME: this probably isn't needed, but make sure it isn't!

		$title = $rev->getTitle();
		$rev->guaranteePageId(); // check this early

		if ( $rev->getSlotNames() !== [ 'main' ] ) {
			throw new MWException( 'Only the `main` slot is supposed for now!' );
		}

		// TODO: MCR: pass Content to the ContentStore, let it do the serialization and store the blob!
		$content = $rev->getContent( 'main', RevisionRecord::RAW );
		$format = $content->getDefaultFormat();
		$model = $content->getModel();

		$this->checkContentModel( $content, $title, $format );

		$data = $content->serialize( $format );
		$flags = $this->contentStore->compressRevisionData( $data );

		# Write to external storage if required
		if ( $this->useExternalStore ) {
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
		$main = $rev->guaranteeSlot( 'main' ); // FIXME

		$blobAddress = $main->getAddress();
		if ( $blobAddress === null ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
			              [
				              'old_id' => $old_id,
				              'old_text' => $data,
				              'old_flags' => $flags,
			              ], __METHOD__
			);
			$textId = $dbw->insertId();
		} else {
			list( $schema, $id, ) = ContentStore::splitBlobAddress( $blobAddress );

			if ( $schema !== 'tt' ) {
				throw new MWException( "Unsupported blob address schema: $schema" );
			}

			$textId = intval( $id );

			if ( !$textId ) {
				throw new MWException( "Malformed text_id: $id" );
			}
		}

		// TODO: MCR: Anomie wrote:
		// Pretty much everything down to here belongs in ContentStore, IMO, except perhaps
		// for the basic decision as to whether ContentStore actually needs to be called or
		// if we can reuse an existing address.
		// ContentStore should return the address that's needed to be put into the slot table.
		// It needn't restrict itself to returning only "tt:" addresses. For turning the address
		// (either an existing one or the one it just returned) into an old_id, the legacy code
		// path here should call a new method on ContentStore that does the work, inserting into
		// the 'text' table with an 'external' flag if necessary.

		$comment = $rev->guaranteeComment();

		$parentId = $rev->getParentId() === null
			? $this->getPreviousRevisionId( $dbw, $rev )
			: $rev->getParentId();

		# Record the edit in revisions
		$row = [
			'rev_page'       => $rev->guaranteePageId(),
			'rev_parent_id'  => $parentId,
			'rev_text_id'    => $textId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_user'       => $rev->guaranteeUserId(),
			'rev_user_text'  => $rev->guaranteeUserText(),
			'rev_timestamp'  => $dbw->timestamp( $rev->guaranteeTimestamp() ),
			'rev_deleted'    => $rev->getVisibility(),
			'rev_len'        => $rev->guaranteeSize(),
			'rev_sha1'       => $rev->guaranteeSha1(),
		];

		if ( $rev->getId() !== null ) {
			// Why is this allowed? Is it needed to restore revisions with their original ID?
			$row['rev_id'] = $rev->getId();
		}

		list( $commentFields, $commentCallback ) =
			CommentStore::newKey( 'rev_comment' )->insertWithTempTable( $dbw, $comment );
		$row += $commentFields;

		if ( $this->contentHandlerUseDB ) {
			// MCR migration note: rev_content_model and rev_content_format will go away

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

			$row['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
			$row['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
		}

		$dbw->insert( 'revision', $row, __METHOD__ );

		if ( !isset( $row['rev_id'] ) ) {
			// Only if nextSequenceValue() was called
			$row['rev_id'] = $dbw->insertId();
		}
		$commentCallback( $rev->getId() );

		$slots = new RevisionSlots( [ 'main' => $content ] );
		$rev = new RevisionStoreRecord( $title, (object)$row, $slots, $this->wikiId );

		Hooks::run( 'RevisionInserted', [ $rev ] );

		return $rev;
	}

	/**
	 * MCR migration note: this corresponds to Revision::checkContentModel
	 *
	 * @throws MWException
	 */
	private function checkContentModel( Content $content, Title $title, $format ) {
		// Note: may return null for revisions that have not yet been inserted

		$model = $content->getModel();
		$format = $content->getDefaultFormat();
		$handler = $content->getContentHandler();

		if ( !$handler->isSupportedFormat( $format ) ) {
			$t = $title->getPrefixedDBkey();

			throw new MWException( "Can't use format $format with content model $model on $t" );
		}

		if ( !$this->contentHandlerUseDB && $title ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $model != $defaultModel ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't save non-default content model with "
				                       . "\$wgContentHandlerUseDB disabled: model is $model, "
				                       . "default for $t is $defaultModel" );
			}

			if ( $format != $defaultFormat ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't use non-default content format with "
				                       . "\$wgContentHandlerUseDB disabled: format is $format, "
				                       . "default for $t is $defaultFormat" );
			}
		}

		$prefixedDBkey = $title->getPrefixedDBkey();

		if ( !$content->isValid() ) {
			throw new MWException(
				"New content for $prefixedDBkey is not valid! Content model is $model"
			);
		}
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * MCR migration note: this replaces Revision::newNullRevision
	 *
	 * @param IDatabase $dbw
	 * @param Title $title Title of the page to read from
	 * @param string $summary RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User $user The user to attribute the revision to
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision( IDatabase $dbw, Title $title, $summary, $minor, User $user ) {
		global $wgContLang; // FIXME: inject??
		// TODO: MCR: allow $summary to be a Message or CommentStoreComment, to accommodate Ic3a434c (T166732)

		$this->checkDatabaseWikiId( $dbw );

		$fields = [ 'page_latest', 'page_namespace', 'page_title',
			'rev_id', 'rev_text_id', 'rev_len', 'rev_sha1' ];

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'rev_content_model';
			$fields[] = 'rev_content_format';
		}

		$current = $dbw->selectRow(
			[ 'page', 'revision' ],
			$fields,
			[
				'page_id' => $title->getArticleID(),
				'page_latest=rev_id',
			],
			__METHOD__,
			[ 'FOR UPDATE' ] // T51581
		);

		if ( $current ) {
			// Truncate for whole multibyte characters
			// FIXME: belongs into CommentStore as per Ic3a434c06 (T166732)
			$summary = $wgContLang->truncate( $summary, 255 );

			$row = [
				'page'       => $title->getArticleID(),
				'since'      => $current->rev_id, // mark slot content as inherited
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len,
				'sha1'       => $current->rev_sha1
			];

			if ( $this->contentHandlerUseDB ) {
				$row['content_model'] = $current->rev_content_model;
				$row['content_format'] = $current->rev_content_format;
			}

			$row['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			// TODO: use setters instead of newFromArray
			$revision = MutableRevisionRecord::newFromArray( $title, $row, $this->emulateSlots_1_29( $row ) );
		} else {
			$revision = null;
		}

		return $revision;
	}

	/**
	 * MCR migration note: this replaces Revision::isUnpatrolled
	 *
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function isUnpatrolled( RevisionRecord $rev ) {
		$rc = $this->getRecentChange( $rev );
		if ( $rc && $rc->getAttribute( 'rc_patrolled' ) == 0 ) {
			return $rc->getAttribute( 'rc_id' );
		} else {
			return 0;
		}
	}

	/**
	 * Get the RC object belonging to the current revision, if there's one
	 *
	 * MCR migration note: this replaces Revision::getRecentChange
	 *
	 * @todo move this somewhere else?
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *
	 * @return null|RecentChange
	 */
	public function getRecentChange( RevisionRecord $rev, $flags = 0 ) {
		$rc = $this->getTransientData( $rev, 'rc', false );

		if ( $rc !== false ) {
			return $rc;
		}

		$dbr = $this->getDBConnection( DB_REPLICA );

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );

		$rc = RecentChange::newFromConds(
			[
				'rc_user_text' => $rev->getUserText( RevisionRecord::RAW ),
				'rc_timestamp' => $dbr->timestamp( $rev->getTimestamp() ),
				'rc_this_oldid' => $rev->getId()
			],
			__METHOD__,
			$dbType
		);

		$this->releaseDBConnection( $dbr );

		$this->setTransientData( $rev, 'rc', $rc );
		return $rc;
	}

	/**
	 * Constructs RevisionSlots containing a SlotRecord for the main slot based on the MW1.29 schema.
	 * This provides a mapping between the M1.29 and the MCR enabled database schema.
	 *
	 * @param object|array $row Either a database row or an array
	 *
	 * @return RevisionSlots A list of emulated slot rows. Contains at least the main slot.
	 * @throws MWException
	 */
	private function emulateSlots_1_29( $row ) {
		$main = new \stdClass();
		$main->slot_role = 'main';

		$content = null;

		if ( is_object( $row ) ) {
			$main->slot_page = intval( $row->rev_page );
			$main->slot_revision = intval( $row->rev_id );
			$main->slot_since = $main->slot_revision; // no way to properly detect null-revisions

			$main->cont_address = 'tt:' . intval( $row->rev_text_id );
			$main->cont_size = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
			$main->cont_sha1 = isset( $row->rev_sha1 ) ? intval( $row->rev_sha1 ) : null;
			// XXX: model and format as int or string?!
			$main->cont_model = isset( $row->rev_content_model ) ? strval( $row->rev_content_model ) : null;
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$main->cont_format = isset( $row->rev_content_format ) ? strval( $row->rev_content_format ) : null;
			$main->blob = isset( $row->old_text ) ? strval( $row->old_text ) : null;
			$main->blob_flags = isset( $row->old_flags ) ? strval( $row->old_flags ) : 0;
		} elseif ( is_array( $row ) ) {
			$main->slot_page = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$main->slot_revision = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$main->slot_since = isset( $row['since'] ) ? intval( $row['since'] ) : $main->slot_revision;

			$main->cont_address = isset( $row['text_id'] ) ? 'tt:' . intval( $row['text_id'] ) : null;
			$main->cont_size = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$main->cont_sha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$main->cont_model = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;  // XXX: int or string?!
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$main->cont_format = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;
			$main->blob = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			$main->blob_flags = isset( $row['flags'] ) ? trim( strval( $row['flags'] ) ) : null;;

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( '`content` field must contain a Content object.' );
				}

				/** @var Content $content */
				$content = $row['content'];
				$handler = $content->getContentHandler();

				$main->cont_model =  $content->getModel();

				// XXX: in the future, we'll probably always use the default format.
				if ( $main->cont_format === null ) {
					$main->cont_format = $handler->getDefaultFormat();
				}
			}
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}

		// With the old schema, the content changes with every revision.
		// ...except for null-revisions. We could do something special for them.
		$main->slot_since = $main->slot_revision;

		if ( $main->cont_model === null ) {
			$main->cont_model = function ( SlotRecord $slot ) {
				// TODO: MCR: consider slot role in getDefaultModelFor()!
				$title = $this->getTitleForSlot( $slot );
				return ContentHandler::getDefaultModelFor( $title );
			};
		}

		if ( $main->cont_format === null ) {
			$main->cont_format = function ( SlotRecord $slot ) {
				$model = $slot->getModel();
				return ContentHandler::getForModelID( $model );
			};
		}

		if ( !$content ) {
			$content = function ( SlotRecord $slot ) {
				return $this->contentStore->getSlotContent( $slot );
			};
		}

		return new RevisionSlots( [ 'main' => new SlotRecord( $main, $content ) ] );
	}

	/**
	 * Determines the Title of the page a given slot is associated with.
	 *
	 * @note The sole purpose of doing this is to find out the default content model for the page,
	 * if no content model was recorded for the slot. This need should go away with the new schema.
	 *
	 * @param SlotRecord $slot
	 *
	 * @throws MWException
	 * @return Title
	 */
	private function getTitleForSlot( SlotRecord $slot ) {
		return $this->getTitle( $slot->getPage(), $slot->getRevision() );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromId
	 *
	 * $flags include:
	 *      RevisionRecord::READ_LATEST  : Select the data from the master
	 *      RevisionRecord::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionById( $id, $flags = 0 ) {
		return $this->newRevisionFromConds( [ 'rev_id' => intval( $id ) ], $flags );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * MCR migration note: this replaces Revision::newFromTitle
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
	public function getRevisionByTitle( LinkTarget $linkTarget, $id = 0, $flags = 0 ) {
		$conds = [
			'page_namespace' => $linkTarget->getNamespace(),
			'page_title' => $linkTarget->getDBkey()
		];
		if ( $id ) {
			// Use the specified ID
			$conds['rev_id'] = $id;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags );

			$this->releaseDBConnection( $db );
			return $rev;
		}
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaces Revision::newFromPageId
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
	public function getRevisionByPageId( $pageId, $revId = 0, $flags = 0 ) {
		$conds = [ 'page_id' => $pageId ];
		if ( $revId ) {
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision
			$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags );

			$this->releaseDBConnection( $db );
			return $rev;
		}
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * MCR migration note: this replaces Revision::loadFromTimestamp
	 *
	 * @param Title $title
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function getRevisionFromTimestamp( $title, $timestamp ) {
		return $this->newRevisionFromConds(
			[
				'rev_timestamp' => $timestamp,
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey()
			]
		);
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row
	 * @param Title $title
	 * @param array $overrides
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRow( $row, Title $title = null, $overrides = [] ) {
		global $wgContentHandlerUseDB;

		$attribs = $overrides + [ // FIXME: move this into the RevisionArchiveRecord constructor!
				'page'       => isset( $row->ar_page_id ) ? $row->ar_page_id : null,
				'id'         => isset( $row->ar_rev_id ) ? $row->ar_rev_id : null,
				'comment'    => CommentStore::newKey( 'ar_comment' )
					->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true )->text,
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

		if ( !isset( $title )
			&& isset( $row->ar_namespace )
			&& isset( $row->ar_title )
		) {
			$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
		}

		// XXX: Pre-1.5 ar_text row. Is this still needed?
		if ( isset( $row->ar_text ) && !$row->ar_text_id ) {
			$attribs['text'] = $row->ar_text;
			$attribs['flags'] = $row->ar_flags;
		}

		// FIXME: this doesn't support archive row format atm!
		$slots = $this->emulateSlots_1_29( $row );

		return new RevisionArchiveRecord( $title, $row, $slots, $this->wikiId );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow_1_29
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	private function newRevisionFromRow_1_29( $row, Title $title = null ) {
		Assert::parameterType( 'object', $row, '$row' );

		if ( !$title ) {
			$pageId = isset( $row->rev_page ) ? $row->rev_page : 0; // XXX: also check page_id?
			$revId = isset( $row->rev_id ) ? $row->rev_id : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		$slots = $this->emulateSlots_1_29( $row );

		$row->rev_comment = CommentStore::newKey( 'rev_comment' )
			// Legacy because $row probably came from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true )->text;

		return new RevisionStoreRecord( $title, $row, $slots, $this->wikiId );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param RevisionSlots $slots
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, Title $title = null ) {
		return $this->newRevisionFromRow_1_29( $row, $title );
	}

	/**
	 * Constructs a new RevisionRecord based on the given associative array following the MW1.29
	 * database convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @deprecated since 1.30. Use a MutableRevisionRecord instead.
	 *
	 * @param array $fields
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 */
	public function newRevisionFromArray_1_29( array $fields, Title $title = null ) {
		$slots = $this->emulateSlots_1_29( $fields );

		if ( !$title ) {
			$pageId = isset( $row['page'] ) ? $row['page'] : 0;
			$revId = isset( $row['id'] ) ? $row['id'] : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		return RevisionStoreRecord::newFromArray_1_29( $title, $fields, $slots, $this->wikiId );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this corresponds to Revision::loadFromId
	 *
	 * @note direct use is deprecated!
	 * @todo remove when unused! there seem to be no callers of Revision::loadFromId
	 *
	 * @param IDatabase $db
	 * @param int $id
	 *
	 * @return RevisionRecord|null
	 */
	public function loadRevisionFromId( IDatabase $db, $id ) {
		return $this->loadRevisionFromConds( $db, [ 'rev_id' => intval( $id ) ] );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * MCR migration note: this replaces Revision::loadFromPageId
	 *
	 * @note direct use is deprecated!
	 * @todo remove when unused!
	 *
	 * @param IDatabase $db
	 * @param int $pageid
	 * @param int $id
	 * @return RevisionRecord|null
	 */
	public function loadRevisionFromPageId( IDatabase $db, $pageid, $id = 0 ) {
		$conds = [ 'rev_page' => intval( $pageid ), 'page_id' => intval( $pageid ) ];
		if ( $id ) {
			$conds['rev_id'] = intval( $id );
		} else {
			$conds[] = 'rev_id=page_latest';
		}
		return $this->loadRevisionFromConds( $db, $conds );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page. If not attached
	 * to that page, will return null.
	 *
	 * MCR migration note: this replaces Revision::loadFromTitle
	 *
	 * @note direct use is deprecated!
	 * @todo remove when unused!
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param int $id
	 *
	 * @return RevisionRecord|null
	 */
	public function loadRevisionFromTitle( IDatabase $db, $title, $id = 0 ) {
		if ( $id ) {
			$matchId = intval( $id );
		} else {
			$matchId = 'page_latest';
		}
		return $this->loadRevisionFromConds( $db,
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
	 * MCR migration note: this replaces Revision::loadFromTimestamp
	 *
	 * @note direct use is deprecated! Use getRevisionFromTimestamp instead!
	 * @todo remove when unused!
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function loadRevisionFromTimestamp( IDatabase $db, $title, $timestamp ) {
		return $this->loadRevisionFromConds( $db,
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
	 * MCR migration note: this corresponds to Revision::newFromConds
	 *
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	private function newRevisionFromConds( $conditions, $flags = 0 ) {
		$db = $this->getDBConnection( ( $flags & self::READ_LATEST ) ? DB_MASTER : DB_REPLICA );
		$rev = $this->loadRevisionFromConds( $db, $conditions, $flags );
		$this->releaseDBConnection( $db );

		$lb = $this->getDBLoadBalancer();

		// Make sure new pending/committed revision are visibile later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & self::READ_LATEST )
			&& $lb->getServerCount() > 1
			&& $lb->hasOrMadeRecentMasterChanges()
		) {
			$flags = self::READ_LATEST;
			$db = $this->getDBConnection( DB_MASTER );
			$rev = $this->loadRevisionFromConds( $db, $conditions, $flags );
			$this->releaseDBConnection( $db );
		}

		if ( $rev ) {
			// FIXME: this can probably go away. Make sure we understand the old
			// logic around mQueryFlags properly!
			$this->setTransientData( $rev, 'queryFlags', $flags );
		}

		return $rev;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * MCR migration note: this corresponds to Revision::loadFromConds
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @return RevisionRecord|null
	 */
	private function loadRevisionFromConds( IDatabase $db, $conditions, $flags = 0 ) {
		$row = $this->fetchRevisionRowFromConds( $db, $conditions, $flags );
		if ( $row ) {
			$rev = $this->newRevisionFromRow( $row );

			return $rev;
		}

		return null;
	}

	/**
	 * Throws an exception if the given database connection does not belong to the wiki this
	 * RevisionStore is bound to.
	 *
	 * @param IDatabase $db
	 * @throws MWException
	 */
	private function checkDatabaseWikiId( IDatabase $db ) {
		if ( $db->getWikiID() !== $this->wikiId ) {
			$storeWiki = $this->wikiId === false ? 'local wiki' : $this->wikiId;
			$dbWiki = $db->getWikiID() === false ? 'local wiki' : $db->getWikiID();

			throw new MWException( "RevisionStore for $storeWiki "
				. "cannot be used with a DB connection for $dbWiki" );
		}
	}

	/**
	 * Given a set of conditions, return a row with the
	 * fields necessary to build RevisionRecord objects.
	 *
	 * MCR migration note: this corresponds to Revision::fetchFromConds
	 *
	 * @param IDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 *
	 * @return object|false data row as a raw object
	 */
	private function fetchRevisionRowFromConds( IDatabase $db, $conditions, $flags = 0 ) {
		$this->checkDatabaseWikiId( $db );

		$fields = array_merge(
			$this->selectRevisionFields(),
			$this->selectPageFields(),
			$this->selectUserFields()
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
			[ 'page' => $this->pageJoinCond(), 'user' => $this->userJoinCond() ]
		);
	}

	/**
	 * Return the value of a select() JOIN conds array for the user table.
	 * This will get user table rows for logged-in users.
	 *
	 * MCR migration note: this replaces Revision::userJoinCond
	 *
	 * @return array
	 */
	public function userJoinCond() {
		return [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ];
	}

	/**
	 * Return the value of a select() page conds array for the page table.
	 * This will assure that the revision(s) are not orphaned from live pages.
	 *
	 * MCR migration note: this replaces Revision::pageJoinCond
	 *
	 * @return array
	 */
	public function pageJoinCond() {
		return [ 'INNER JOIN', [ 'page_id = rev_page' ] ];
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 *
	 * MCR migration note: this replaces Revision::selectFields
	 *
	 * @todo Deprecate this in favor of a method that returns tables and joins
	 *  as well, and use CommentStore::getJoin().
	 * @return array
	 */
	public function selectRevisionFields() {
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

		$fields += CommentStore::newKey( 'rev_comment' )->getFields();

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'rev_content_format';
			$fields[] = 'rev_content_model';
		}

		return $fields;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision from an archive row.
	 *
	 * MCR migration note: this replaces Revision::selectArchiveFields
	 *
	 * @todo Deprecate this in favor of a method that returns tables and joins
	 *  as well, and use CommentStore::getJoin().
	 * @return array
	 */
	public function selectArchiveFields() {
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

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}
		return $fields;
	}

	/**
	 * Return the list of text fields that should be selected to read the
	 * revision text
	 *
	 * MCR migration note: this replaces Revision::selectTextFields
	 *
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
	 *
	 * MCR migration note: this replaces Revision::selectPageFields
	 *
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
	 *
	 * MCR migration note: this replaces Revision::selectUserFields
	 *
	 * @return array
	 */
	public function selectUserFields() {
		return [ 'user_name' ];
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 *
	 * MCR migration note: this replaces Revision::getParentLengths
	 *
	 * @param IDatabase $db
	 * @param array $revIds
	 * @return array
	 */
	public function getParentLengths( IDatabase $db, array $revIds ) {
		$this->checkDatabaseWikiId( $db );

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
	 * MCR migration note: this replaces Revision::getPrevious
	 *
	 * @param RevisionRecord $rev
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev ) {
		$title = $this->getTitle( $rev->getPageId(), $rev->getId() );
		$prev = $title->getPreviousRevisionID( $rev->getId() );
		if ( $prev ) {
			return $this->getRevisionByTitle( $title, $prev );
		}
		return null;
	}

	/**
	 * Get next revision for this title
	 *
	 * MCR migration note: this replaces Revision::getNext
	 *
	 * @param RevisionRecord $rev
	 *
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev ) {
		$title = $this->getTitle( $rev->getPageId(), $rev->getId() );
		$next = $title->getNextRevisionID( $rev->getId() );
		if ( $next ) {
			return $this->getRevisionByTitle( $title, $next );
		}
		return null;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * MCR migration note: this corresponds to Revision::getPreviousRevisionId
	 *
	 * @param IDatabase $db
	 * @param RevisionRecord $rev
	 *
	 * @return int
	 */
	private function getPreviousRevisionId( IDatabase $db, RevisionRecord $rev ) {
		$this->checkDatabaseWikiId( $db );

		if ( $rev->getPageId() === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$rev->getId() ) {
			$prevId = $db->selectField( 'page', 'page_latest',
			                            [ 'page_id' => $rev->getPageId() ],
			                            __METHOD__ );
		} else {
			$prevId = $db->selectField( 'revision', 'rev_id',
			                            [ 'rev_page' => $rev->getPageId(), 'rev_id < ' . $rev->getId() ],
			                            __METHOD__,
			                            [ 'ORDER BY' => 'rev_id DESC' ] );
		}
		return intval( $prevId );
	}

	/**
	 * Utility for associating transient data with an object
	 *
	 * @param RevisionRecord $rev
	 * @param string $key
	 * @param mixed $value
	 */
	private function setTransientData( RevisionRecord $rev, $key, $value ) {
		if ( $rev instanceof TransientDataAccess ) {
			$rev->setTransientData( $key, $value );
		}
	}

	/**
	 * Utility for retrieving transient data from an object
	 *
	 * @param RevisionRecord $rev
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	private function getTransientData( RevisionRecord $rev, $key, $default = null ) {
		if ( $rev instanceof TransientDataAccess ) {
			return $rev->getTransientData( $key, $default );
		} else {
			return $default;
		}
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row
	 *
	 * MCR migration note: this replaces Revision::getTimestampFromId
	 *
	 * @param Title $title
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	public function getTimestampFromId( $title, $id, $flags = 0 ) {
		$db = $this->getDBConnection(
			( $flags & IDBAccessObject::READ_LATEST ) ? DB_MASTER : DB_REPLICA
		);

		// Casting fix for databases that can't take '' for rev_id
		if ( $id == '' ) {
			$id = 0;
		}
		$conds = [ 'rev_id' => $id ];
		$conds['rev_page'] = $title->getArticleID();
		$timestamp = $db->selectField( 'revision', 'rev_timestamp', $conds, __METHOD__ );

		$this->releaseDBConnection( $db );
		return ( $timestamp !== false ) ? wfTimestamp( TS_MW, $timestamp ) : false;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * MCR migration note: this replaces Revision::countByPageId
	 *
	 * @param IDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	public function countRevisionsByPageId( IDatabase $db, $id ) {
		$this->checkDatabaseWikiId( $db );

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
	 * MCR migration note: this replaces Revision::countByTitle
	 *
	 * @param IDatabase $db
	 * @param Title $title
	 * @return int
	 */
	public function countRevisionsByTitle( IDatabase $db, $title ) {
		$id = $title->getArticleID();
		if ( $id ) {
			return $this->countRevisionsByPageId( $db, $id );
		}
		return 0;
	}

	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 *
	 * MCR migration note: this replaces Revision::userWasLastToEdit
	 *
	 * @deprecated since 1.30; Can possibly be removed, since the self-conflict suppression
	 *       logic in EditPage that uses this seems conceptually dubious. Revision::userWasLastToEdit
	 *       has been deprecated since 1.24.
	 *
	 * @param IDatabase $db The Database to perform the check on.
	 * @param int $pageId The ID of the page in question
	 * @param int $userId The ID of the user in question
	 * @param string $since Look at edits since this time
	 *
	 * @return bool True if the given user was the only one to edit since the given timestamp
	 */
	public function userWasLastToEdit( IDatabase $db, $pageId, $userId, $since ) {
		$this->checkDatabaseWikiId( $db );

		if ( !$userId ) {
			return false;
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
	 * MCR migration note: this replaces Revision::newKnownCurrent
	 *
	 * @param IDatabase $db
	 * @param int $pageId Page ID
	 * @param int $revId 1 current revision of this page
	 * @return RevisionRecord|bool Returns false if missing
	 */
	public function getKnownCurrentRevision( IDatabase $db, $pageId, $revId ) {
		$this->checkDatabaseWikiId( $db );

		$row = $this->cache->getWithSetCallback(
			// Page/rev IDs passed in from DB to reflect history merges
			$this->cache->makeGlobalKey( 'revision-row-1.29', $db->getWikiID(), $pageId, $revId ),
			WANObjectCache::TTL_WEEK,
			function ( $curValue, &$ttl, array &$setOpts ) use ( $db, $pageId, $revId ) {
				$setOpts += Database::getCacheSetOptions( $db );

				$conds = [
					'rev_page' => intval( $pageId ),
					'page_id' => intval( $pageId ),
					'rev_id' => intval( $revId ),
				];

				$row = $this->fetchRevisionRowFromConds( $db, $conds );
				return $row ?: false; // don't cache negatives
			}
		);

		// Reflect revision deletion and user renames
		if ( $row ) {
			return $this->newRevisionFromRow( $row );
		} else {
			return false;
		}
	}

}
