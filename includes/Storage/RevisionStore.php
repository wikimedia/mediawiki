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
use CommentStoreComment;
use Content;
use ContentHandler;
use DBAccessObjectUtils;
use Hooks;
use \IDBAccessObject;
use InvalidArgumentException;
use IP;
use MediaWiki\Linker\LinkTarget;
use Message;
use MWException;
use MWUnknownContentModelException;
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
 * @since 1.31
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionStore implements IDBAccessObject, RevisionFactory, RevisionLookup {

	/**
	 * @var BlobStore
	 */
	private $contentStore;

	/**
	 * @var bool|string
	 */
	private $wikiId;

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
	 * @param BlobStore $blobStore
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		BlobStore $blobStore,
		WANObjectCache $cache,
		$wikiId = false
	) {
		Assert::parameterType( 'integer|boolean', $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->contentStore = $blobStore;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
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
	 * @param int $queryFlags
	 *
	 * @return Title
	 * @throws StorageException
	 */
	private function getTitle( $pageId, $revId, $queryFlags = 0 ) {
		if ( !$pageId && !$revId ) {
			throw new InvalidArgumentException( '$pageId and $revId cannot both be 0 or null' );
		}

		$title = null;

		// Loading by ID is best, though not possible for foreign titles
		if ( $pageId !== null && $pageId > 0 && $this->wikiId === false ) {
			$title = Title::newFromID( $pageId, $queryFlags );
		}

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		if ( !$title && $revId !== null && $revId > 0 ) {
			$dbr = $this->getDbConnectionRef( DB_REPLICA ); // FIXME: use $queryFlags
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

		if ( !$title ) {
			throw new StorageException(
				"Could not determine title for page ID $pageId and revision ID $revId"
			);
		}

		return $title;
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is null
	 * @return mixed $value, if $value is not null
	 */
	private function failOnNull( $value, $name ) {
		if ( $value === null ) {
			throw new IncompleteRevisionException(
				"$name is undefined!"
			);
		}

		return $value;
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * MCR migration note: this replaces Revision::insertOn
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 *
	 * @throws MWException
	 * @return RevisionRecord the new revision record.
	 */
	public function insertRevisionOn( RevisionRecord $rev, IDatabase $dbw ) {
		// TODO: pass in a DBTransactionContext instead of a database connection.
		$this->checkDatabaseWikiId( $dbw );

		$title = $rev->getTitle();
		$this->failOnNull( $rev->getPageId(), 'rev_page field' ); // check this early

		if ( $rev->getSlotRoles() !== [ 'main' ] ) {
			throw new MWException( 'Only the `main` slot is supposed for now!' );
		}

		// TODO: MCR: pass Content to the BlobStore, let it do the serialization and store the blob.

		# Record the text (or external storage URL) to the text table
		$main = $rev->getSlot( 'main', RevisionRecord::RAW );

		if ( !$main->hasAddress() ) {
			$content = $rev->getContent( 'main', RevisionRecord::RAW );
			$format = $content->getDefaultFormat();
			$model = $content->getModel();

			$this->checkContentModel( $content, $title, $format );

			$data = $content->serialize( $format );

			$blobHints = []; // FIXME: model, format, role, revision, page, ...
			$blobAddress = $this->contentStore->storeBlob( $data, $blobHints );
		} else {
			$blobAddress = $main->getAddress();
			$model = $main->getModel();
			$format = $main->getFormat();
		}

		list( $schema, $id, ) = BlobStore::splitBlobAddress( $blobAddress );

		if ( $schema !== 'tt' ) {
			throw new UnexpectedValueException( "Unsupported blob address schema: $schema" );
		}

		$textId = intval( $id );

		if ( !$textId ) {
			throw new UnexpectedValueException( "Malformed text_id: $id" );
		}

		// TODO: MCR: Anomie wrote:
		// Pretty much everything down to here belongs in BlobStore, IMO, except perhaps
		// for the basic decision as to whether BlobStore actually needs to be called or
		// if we can reuse an existing address.
		// BlobStore should return the address that's needed to be put into the slot table.
		// It needn't restrict itself to returning only "tt:" addresses. For turning the address
		// (either an existing one or the one it just returned) into an old_id, the legacy code
		// path here should call a new method on BlobStore that does the work, inserting into
		// the 'text' table with an 'external' flag if necessary.

		$comment = $this->failOnNull( $rev->getComment(), 'comment' );

		$parentId = $rev->getParentId() === null
			? $this->getPreviousRevisionId( $dbw, $rev )
			: $rev->getParentId();

		# Record the edit in revisions
		$row = [
			'rev_page'       => $this->failOnNull( $rev->getPageId(), 'page field' ),
			'rev_parent_id'  => $parentId,
			'rev_text_id'    => $textId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_user'       => $this->failOnNull( $rev->getUserId(), 'user field' ),
			'rev_user_text'  => $this->failOnNull( $rev->getUserText(), 'user_text field' ),
			'rev_timestamp'  => $dbw->timestamp( $this->failOnNull( $rev->getTimestamp(), 'timestamp field' ) ),
			'rev_deleted'    => $rev->getVisibility(),
			'rev_len'        => $this->failOnNull( $rev->getSize(), 'size field' ),
			'rev_sha1'       => $this->failOnNull( $rev->getSha1(), 'sha1 field' ),
		];

		if ( $rev->getId() !== null ) {
			// Needed to restore revisions with their original ID
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

		// Insert IP revision into ip_changes for use when querying for a range.
		if ( $row['rev_user'] === 0 && IP::isValid( $row['rev_user_text'] ) ) {
			$ipcRow = [
				'ipc_rev_id'        => $row['rev_id'],
				'ipc_rev_timestamp' => $row['rev_timestamp'],
				'ipc_hex'           => IP::toHex( $row['rev_user_text'] ),
			];
			$dbw->insert( 'ip_changes', $ipcRow, __METHOD__ );
		}

		$slots = new RevisionSlots( [ 'main' => $main ] );
		$rev = new RevisionStoreRecord( $title, $comment, (object)$row, $slots, $this->wikiId );

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

			// FIXME: MCR: use MutableRevisionRecord::newFromParentRevision (and setters)
			$mainSlot = $this->emulateMainSlot_1_29( $row );
			$revision = MutableRevisionRecord::newFromArray_1_29( $title, $row );
			$revision->setSlot( $mainSlot );
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
	 * @param string $expression PCRE regular expression
	 * @param string $replacement
	 * @param array $array
	 *
	 * @return array
	 */
	private static function preg_replace_keys( $expression, $replacement, array $array ) {
		$result = [];

		foreach ( $array as $key => $value ) {
			$key = preg_replace( $expression, $replacement, $key );
			$result[$key] = $value;
		}

		return $result;
	}

	/**
	 * Constructs a RevisionRecord for the revisions main slot, based on the MW1.29 schema.
	 *
	 * @param object|array $row Either a database row or an array
	 * @param int $queryFlags for callbacks
	 * @param Title|null $title
	 *
	 * @return SlotRecord The main slot, extracted from the MW 1.29 style row.
	 * @throws MWException
	 */
	private function emulateMainSlot_1_29( $row, $queryFlags = 0, Title $title = null ) {
		$mainSlotRow = new \stdClass();
		$mainSlotRow->role_name = 'main';

		$content = null;
		$blobData = null;
		$blobFlags = '';

		if ( is_object( $row ) ) {

			if ( isset( $row->old_text ) ) {
				// this happens when the text-table gets joined directly, in the pre-1.30 schema
				$blobData = isset( $row->old_text ) ? strval( $row->old_text ) : null;
				$blobFlags = isset( $row->old_flags ) ? strval( $row->old_flags ) : '';
			} elseif ( isset( $row->ar_text ) ) {
				$blobData = isset( $row->ar_text ) ? strval( $row->ar_text ) : null;
				$blobFlags = isset( $row->ar_flags ) ? strval( $row->ar_flags ) : '';

				// May not be needed, but useful as a cache key, and for completeness
				$mainSlotRow->cont_address = 'ar:' . $row->ar_id;
			}

			// archive row
			if ( !isset( $row->rev_id ) && isset( $row->ar_user ) ) {
				$row->rev_id = $row->ar_rev_id; // set rev_id to ar_rev_id
				unset( $row->ar_id ); // don't override rev_id with ar_id
				unset( $row->ar_rev_id ); // don't define rev_rev_id

				// adjust prefixes ar_ -> rev_
				$row = (object)$this->preg_replace_keys( '/^ar_/', 'rev_', (array)$row );
			}

			if ( isset( $row->rev_text_id ) && $row->rev_text_id > 0 ) {
				$mainSlotRow->cont_address = 'tt:' . $row->rev_text_id;
			}

			$mainSlotRow->slot_page = intval( $row->rev_page );
			$mainSlotRow->slot_revision = intval( $row->rev_id );
			$mainSlotRow->slot_inherited = 0;

			$mainSlotRow->cont_size = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
			$mainSlotRow->cont_sha1 = isset( $row->rev_sha1 ) ? intval( $row->rev_sha1 ) : null;
			$mainSlotRow->model_name = isset( $row->rev_content_model ) ? strval( $row->rev_content_model ) : null;
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->cont_format = isset( $row->rev_content_format ) ? strval( $row->rev_content_format ) : null;
		} elseif ( is_array( $row ) ) {
			$mainSlotRow->slot_page = isset( $row['page'] ) ? intval( $row['page'] ) : null;
			$mainSlotRow->slot_revision = isset( $row['id'] ) ? intval( $row['id'] ) : null;
			$mainSlotRow->slot_inherited = 0;

			$mainSlotRow->cont_address = isset( $row['text_id'] ) ? 'tt:' . intval( $row['text_id'] ) : null;
			$mainSlotRow->cont_size = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$mainSlotRow->cont_sha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$mainSlotRow->model_name = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;  // XXX: must be a string!
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->cont_format = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;
			$blobData = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			$blobFlags = isset( $row['flags'] ) ? trim( strval( $row['flags'] ) ) : '';

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( '`content` field must contain a Content object.' );
				}

				/** @var Content $content */
				$content = $row['content'];
				$handler = $content->getContentHandler();

				$mainSlotRow->model_name =  $content->getModel();

				// XXX: in the future, we'll probably always use the default format.
				if ( $mainSlotRow->cont_format === null ) {
					$mainSlotRow->cont_format = $handler->getDefaultFormat();
				}
			}
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}

		// With the old schema, the content changes with every revision.
		// ...except for null-revisions. We could do something special for them.
		$mainSlotRow->slot_since = $mainSlotRow->slot_revision; // FIXME: kill slot_since, use slot_inherited!

		if ( $mainSlotRow->model_name === null ) {
			$mainSlotRow->model_name = function ( SlotRecord $slot ) use ( $title, $queryFlags ) {
				if ( !$title ) {
					$title = $this->getTitleForSlot( $slot, $queryFlags );
				}

				// TODO: MCR: consider slot role in getDefaultModelFor()!
				return ContentHandler::getDefaultModelFor( $title );
			};
		}

		if ( !$content ) {
			$content = function ( SlotRecord $slot ) use ( $blobData, $blobFlags, $queryFlags, $mainSlotRow ) {
				return $this->loadSlotContent(
					$slot,
					$blobData,
					$blobFlags,
					$mainSlotRow->cont_format,
					$queryFlags
				);
			};
		}

		return new SlotRecord( $mainSlotRow, $content );
	}

	/**
	 * Determines the Title of the page a given slot is associated with.
	 *
	 * @note The sole purpose of doing this is to find out the default content model for the page,
	 * if no content model was recorded for the slot. This need should go away with the new schema.
	 *
	 * @param SlotRecord $slot
	 * @param int $queryFlags
	 *
	 * @return Title
	 * @throws StorageException
	 */
	private function getTitleForSlot( SlotRecord $slot, $queryFlags = 0 ) {
		return $this->getTitle( $slot->getPage(), $slot->getRevision(), $queryFlags );
	}

	/**
	 * Loads a Content object based on a slot row.
	 *
	 * This method does not call $slot->getContent(), and may be used as a callback
	 * called by $slot->getContent().
	 *
	 * MCR migration note: this roughly corresponds to Revision::getContentInternal
	 *
	 * @param SlotRecord $slot The SlotRecord to load content for
	 * @param string|null $blobData The content blob, in the form indicated by $blobFlags
	 * @param string $blobFlags Flags indicating how $blobData needs to be processed
	 * @param string|null $blobFormat MIME type indicating how $dataBlob is encoded
	 * @param int $queryFlags
	 *
	 * @return Content
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function loadSlotContent( SlotRecord $slot, $blobData = null, $blobFlags = '', $blobFormat = null, $queryFlags = 0 ) {
		// FIXME: take queryFlags param, and pass it on!
		// Database result rows based on some legacy schemas may contain the blob data directly.
		if ( $blobData !== null ) {
			$cacheKey = $slot->hasAddress() ? $slot->getAddress() : null;

			$data = $this->contentStore->expandBlob( $blobData, $blobFlags, $cacheKey );
		} else {
			$data = $this->contentStore->getBlob( $slot->getAddress(), $queryFlags );
		}

		// Unserialize content
		$handler = ContentHandler::getForModelID( $slot->getModel() );

		$content = $handler->unserializeContent( $data, $blobFormat );
		return $content;
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
			],
			0,
			$title
		);
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 * @param array $overrides
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 */
	public function newRevisionFromArchiveRow( $row, $queryFlags = 0, Title $title = null, $overrides = [] ) {
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

		$comment = CommentStore::newKey( 'ar_comment' )
			// Legacy because $row probably came from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionArchiveRecord( $title, $comment, $row, $slots, $this->wikiId );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow_1_29
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 * @throws StorageException
	 */
	private function newRevisionFromRow_1_29( $row, $queryFlags = 0, Title $title = null ) {
		Assert::parameterType( 'object', $row, '$row' );

		if ( !$title ) {
			$pageId = isset( $row->rev_page ) ? $row->rev_page : 0; // XXX: also check page_id?
			$revId = isset( $row->rev_id ) ? $row->rev_id : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		$comment = CommentStore::newKey( 'rev_comment' )
			// Legacy because $row probably came from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionStoreRecord( $title, $comment, $row, $slots, $this->wikiId );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, $queryFlags = 0, Title $title = null ) {
		return $this->newRevisionFromRow_1_29( $row, $queryFlags, $title );
	}

	/**
	 * Constructs a new RevisionRecord based on the given associative array following the MW1.29
	 * database convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @deprecated since 1.31. Use a MutableRevisionRecord instead.
	 *
	 * @param array $fields
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 * @throws StorageException
	 */
	public function newRevisionFromArray_1_29( array $fields, $queryFlags = 0, Title $title = null ) {
		if ( !$title ) {
			$pageId = isset( $fields['page'] ) ? $fields['page'] : 0;
			$revId = isset( $fields['id'] ) ? $fields['id'] : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		# if we have a content object, use it to set the model and type
		if ( !empty( $fields['content'] ) ) {
			if ( !( $fields['content'] instanceof Content ) ) {
				throw new MWException( '`content` field must contain a Content object.' );
			}

			if ( !empty( $fields['text_id'] ) ) {
				throw new MWException( "Text already stored in external store (id {$fields['text_id']}), " .
					"can't serialize content object" );
			}
		}

		if ( isset( $fields['comment'] ) && !( $fields['comment'] instanceof CommentStoreComment ) ) {
			$commentData = isset( $fields['comment_data'] ) ? $fields['comment_data'] : null;

			if ( $fields['comment'] instanceof Message ) {
				$fields['comment'] = CommentStoreComment::newFromMessage( $fields['comment'], $commentData ); // FIXME: method does not exist
			} else {
				$commentText = trim( strval( $fields['comment'] ) );
				$fields['comment'] = CommentStoreComment::newUnsavedComment( $commentText, $commentData );
			}
		}

		$mainSlot = $this->emulateMainSlot_1_29( $fields, $queryFlags, $title );

		$revision = MutableRevisionRecord::newFromArray_1_29( $title, $fields, $this->wikiId );
		$revision->setSlot( $mainSlot );

		return $revision;
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
			],
			0,
			$title
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
			],
			0,
			$title
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
	 * @param Title $title
	 *
	 * @return RevisionRecord|null
	 */
	private function newRevisionFromConds( $conditions, $flags = 0, Title $title = null ) {
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
	 * @param Title $title
	 *
	 * @return RevisionRecord|null
	 */
	private function loadRevisionFromConds( IDatabase $db, $conditions, $flags = 0, Title $title = null ) {
		$row = $this->fetchRevisionRowFromConds( $db, $conditions, $flags );
		if ( $row ) {
			$rev = $this->newRevisionFromRow( $row, $flags, $title );

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
		// FIXME: we really want the default database ID...
		$storeWiki = $this->wikiId === false ? wfWikiID() : $this->wikiId;
		$dbWiki = $db->getDomainID() === false ? wfWikiID() : $db->getDomainID();

		if ( $dbWiki !== $storeWiki ) {
			$dbWiki = $dbWiki ?: 'local wiki';
			$storeWiki = $storeWiki ?: 'local wiki';

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
			'ar_user_text',
			'ar_user',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];

		$fields += CommentStore::newKey( 'ar_comment' )->getFields();

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
	 * @deprecated since 1.31; Can possibly be removed, since the self-conflict suppression
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
			$this->cache->makeGlobalKey( 'revision-row-1.29', $db->getDomainID(), $pageId, $revId ),
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

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
