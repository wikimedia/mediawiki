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
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->contentStore = $blobStore;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return bool
	 */
	public function getContentHandlerUseDB() {
		return $this->contentHandlerUseDB;
	}

	/**
	 * @param bool $contentHandlerUseDB
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
	 * @throws RevisionAccessException
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
			list( $dbMode, $dbOptions, , ) = DBAccessObjectUtils::getDBOptions( $queryFlags );

			$dbr = $this->getDbConnectionRef( $dbMode );
			// @todo: Title::getSelectFields(), or Title::getQueryInfo(), or something like that
			$row = $dbr->selectRow(
				[ 'revision', 'page' ],
				[
					'page_namespace',
					'page_title',
					'page_id',
					'page_latest',
					'page_is_redirect',
					'page_len',
				],
				[ 'rev_id' => $revId ],
				__METHOD__,
				[],
				[ 'page' => [ 'JOIN', 'page_id=rev_page' ] ]
			);
			if ( $row ) {
				// TODO: better foreign title handling (introduce TitleFactory)
				$title = Title::newFromRow( $row );
			}
		}

		if ( !$title ) {
			throw new RevisionAccessException(
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
				"$name must not be null!"
			);
		}

		return $value;
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is null
	 * @return mixed $value, if $value is not null
	 */
	private function failOnEmpty( $value, $name ) {
		if ( $value === null || $value === 0 || $value === '' ) {
			throw new IncompleteRevisionException(
				"$name must not be $value!"
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

		if ( empty( $rev->getSlotRoles() ) ) {
			throw new MWException( 'At least one slot needs to be defined!' );
		}

		if ( $rev->getSlotRoles() !== [ 'main' ] ) {
			throw new MWException( 'Only the main slot is supposed for now!' );
		}

		// TODO: we shouldn't need an actual Title here.
		$title = Title::newFromLinkTarget( $rev->getTitle() );
		$pageId = $this->failOnEmpty( $rev->getPageId(), 'rev_page field' ); // check this early

		$parentId = $rev->getParentId() === null
			? $this->getPreviousRevisionId( $dbw, $rev )
			: $rev->getParentId();

		// Record the text (or external storage URL) to the text table
		$slot = $rev->getSlot( 'main', RevisionRecord::RAW );

		$size = $this->failOnNull( $rev->getSize(), 'size field' );
		$sha1 = $this->failOnEmpty( $rev->getSha1(), 'sha1 field' );

		if ( !$slot->hasAddress() ) {
			$content = $rev->getContent( 'main', RevisionRecord::RAW );
			$format = $content->getDefaultFormat();
			$model = $content->getModel();

			$this->checkContentModel( $content, $title, $format );

			$data = $content->serialize( $format );

			// TODO: Declare and document these hints.
			// Hints allow the blob store to optimize be "leaking" application level information to it.
			$blobHints = [
				'designation' => 'page-content',
				'page_id' => $pageId,
				'page_namespace' => $title->getNamespace(),
				'role_name' => $slot->getRole(),
				// 'rev_id' => $rev->getId(), // XXX: would be nice, but we don't have that yet!
				'rev_parent_id' => $parentId,
				'cont_sha1' => $sha1,
				'cont_model' => $model,
				'cont_format' => $format,
			];
			$blobAddress = $this->contentStore->storeBlob( $data, $blobHints );
		} else {
			$blobAddress = $slot->getAddress();
			$model = $slot->getModel();
			$format = $slot->getFormat();
		}

		list( $schema, $id, ) = BlobStore::splitBlobAddress( $blobAddress );

		if ( $schema !== 'tt' ) {
			throw new UnexpectedValueException( "Unsupported blob address schema: $schema" );
		}

		$textId = intval( $id );

		if ( !$textId ) {
			throw new UnexpectedValueException( "Malformed text_id: $id" );
		}

		$comment = $this->failOnNull( $rev->getComment(), 'comment' );
		$timestamp = $this->failOnEmpty( $rev->getTimestamp(), 'timestamp field' );

		# Record the edit in revisions
		$row = [
			'rev_page'       => $pageId,
			'rev_parent_id'  => $parentId,
			'rev_text_id'    => $textId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_user'       => $this->failOnNull( $rev->getUserId(), 'user field' ),
			'rev_user_text'  => $this->failOnEmpty( $rev->getUserText(), 'user_text field' ),
			'rev_timestamp'  => $dbw->timestamp( $timestamp ),
			'rev_deleted'    => $rev->getVisibility(),
			'rev_len'        => $size,
			'rev_sha1'       => $sha1,
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
			$row['rev_id'] = intval( $dbw->insertId() );
		}
		$commentCallback( $row['rev_id'] );

		// Insert IP revision into ip_changes for use when querying for a range.
		if ( $row['rev_user'] === 0 && IP::isValid( $row['rev_user_text'] ) ) {
			$ipcRow = [
				'ipc_rev_id'        => $row['rev_id'],
				'ipc_rev_timestamp' => $row['rev_timestamp'],
				'ipc_hex'           => IP::toHex( $row['rev_user_text'] ),
			];
			$dbw->insert( 'ip_changes', $ipcRow, __METHOD__ );
		}

		$newSlot = SlotRecord::newSaved( $row['rev_id'], $blobAddress, $slot );
		$slots = new RevisionSlots( [ 'main' => $newSlot ] );

		$rev = new RevisionStoreRecord( $title, $comment, (object)$row, $slots, $this->wikiId );
		$newSlot = $rev->getSlot( 'main', RevisionRecord::RAW );

		// sanity checks
		Assert::postcondition( $rev->getId() > 0, 'revision must have an ID' );
		Assert::postcondition( $rev->getPageId() > 0, 'revision must have a page ID' );
		Assert::postcondition( $rev->getComment() !== null, 'revision must have a comment' );
		Assert::postcondition( $rev->getUserText() !== null, 'revision must have a user name' );

		Assert::postcondition( $newSlot !== null, 'revision must have a main slot' );
		Assert::postcondition( $newSlot->getAddress() !== null, 'main slot must have an addess' );

		Hooks::run( 'RevisionRecordInserted', [ $rev ] );

		return $rev;
	}

	/**
	 * MCR migration note: this corresponds to Revision::checkContentModel
	 *
	 * @throws MWException
	 */
	private function checkContentModel( Content $content, LinkTarget $title, $format ) {
		// Note: may return null for revisions that have not yet been inserted

		$model = $content->getModel();
		$format = $content->getDefaultFormat();
		$handler = $content->getContentHandler();

		$name = "$title";

		if ( !$handler->isSupportedFormat( $format ) ) {
			throw new MWException( "Can't use format $format with content model $model on $name" );
		}

		if ( !$this->contentHandlerUseDB ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $model != $defaultModel ) {
				throw new MWException( "Can't save non-default content model with "
									   . "\$wgContentHandlerUseDB disabled: model is $model, "
									   . "default for $name is $defaultModel" );
			}

			if ( $format != $defaultFormat ) {
				throw new MWException( "Can't use non-default content format with "
									   . "\$wgContentHandlerUseDB disabled: format is $format, "
									   . "default for $name is $defaultFormat" );
			}
		}

		if ( !$content->isValid() ) {
			throw new MWException(
				"New content for $name is not valid! Content model is $model"
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
	 * @param CommentStoreComment $comment RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User $user The user to attribute the revision to
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision(
		IDatabase $dbw,
		Title $title,
		CommentStoreComment $comment,
		$minor,
		User $user
	) {
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
			$row = [
				'page'       => $title->getArticleID(),
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $comment,
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

			// TODO: MCR: use MutableRevisionRecord::newFromParentRevision (and setters)
			$mainSlot = $this->emulateMainSlot_1_29( $row, 0, $title );
			$revision = MutableRevisionRecord::newFromArray( $title, $row );
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

		// XXX: cache this locally? Glue it to the RevisionRecord?
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
	 * @param Title $title
	 *
	 * @return SlotRecord The main slot, extracted from the MW 1.29 style row.
	 * @throws MWException
	 */
	private function emulateMainSlot_1_29( $row, $queryFlags, Title $title ) {
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
			} elseif ( isset( $row->ar_text ) && empty( $row->ar_text_id ) ) {
				$blobData = isset( $row->ar_text ) ? strval( $row->ar_text ) : null;
				$blobFlags = isset( $row->ar_flags ) ? strval( $row->ar_flags ) : '';

				// May not be needed, but useful as a cache key, and for completeness
				$mainSlotRow->cont_address = 'ar:' . $row->ar_id;
			}

			// archive row
			if ( !isset( $row->rev_id ) && isset( $row->ar_user ) ) {
				$row = clone $row; // don't write into the caller's view.

				$row->rev_id = $row->ar_rev_id; // set rev_id to ar_rev_id
				$row->rev_page = $row->ar_page_id; // set rev_page to ar_page_id

				unset( $row->ar_id ); // don't override rev_id with ar_id
				unset( $row->ar_rev_id ); // don't define rev_rev_id
				unset( $row->ar_page_id ); // don't define rev_page_id

				// adjust prefixes ar_ -> rev_
				$row = (object)$this->preg_replace_keys( '/^ar_/', 'rev_', (array)$row );
			}

			if ( isset( $row->rev_text_id ) && $row->rev_text_id > 0 ) {
				$mainSlotRow->cont_address = 'tt:' . $row->rev_text_id;
			}

			$mainSlotRow->slot_revision = intval( $row->rev_id );

			$mainSlotRow->cont_size = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
			$mainSlotRow->cont_sha1 = isset( $row->rev_sha1 ) ? intval( $row->rev_sha1 ) : null;
			$mainSlotRow->model_name = isset( $row->rev_content_model )
				? strval( $row->rev_content_model )
				: null;
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row->rev_content_format )
				? strval( $row->rev_content_format )
				: null;
		} elseif ( is_array( $row ) ) {
			$mainSlotRow->slot_revision = isset( $row['id'] ) ? intval( $row['id'] ) : null;

			$mainSlotRow->cont_address = isset( $row['text_id'] )
				? 'tt:' . intval( $row['text_id'] )
				: null;
			$mainSlotRow->cont_size = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$mainSlotRow->cont_sha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$mainSlotRow->model_name = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;  // XXX: must be a string!
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;
			$blobData = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			$blobFlags = isset( $row['flags'] ) ? trim( strval( $row['flags'] ) ) : '';

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( 'content field must contain a Content object.' );
				}

				/** @var Content $content */
				$content = $row['content'];
				$handler = $content->getContentHandler();

				$mainSlotRow->model_name = $content->getModel();

				// XXX: in the future, we'll probably always use the default format.
				if ( $mainSlotRow->format_name === null ) {
					$mainSlotRow->format_name = $handler->getDefaultFormat();
				}
			}
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}

		// With the old schema, the content changes with every revision.
		// ...except for null-revisions. Would be nice if we could detect them.
		$mainSlotRow->slot_inherited = 0;

		if ( $mainSlotRow->model_name === null ) {
			$mainSlotRow->model_name = function ( SlotRecord $slot ) use ( $title ) {
				// TODO: MCR: consider slot role in getDefaultModelFor()!
				// TODO: MCR: deprecate $title->getModel().
				return ContentHandler::getDefaultModelFor( $title );
			};
		}

		if ( !$content ) {
			$content = function ( SlotRecord $slot )
				use ( $blobData, $blobFlags, $queryFlags, $mainSlotRow )
			{
				return $this->loadSlotContent(
					$slot,
					$blobData,
					$blobFlags,
					$mainSlotRow->format_name,
					$queryFlags
				);
			};
		}

		return new SlotRecord( $mainSlotRow, $content );
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
	private function loadSlotContent(
		SlotRecord $slot,
		$blobData = null,
		$blobFlags = '',
		$blobFormat = null,
		$queryFlags = 0
	) {
		if ( $blobData !== null ) {
			Assert::parameterType( 'string', $blobData, '$blobData' );
			Assert::parameterType( 'string', $blobFlags, '$blobFlags' );

			$cacheKey = $slot->hasAddress() ? $slot->getAddress() : null;

			$data = $this->contentStore->expandBlob( $blobData, $blobFlags, $cacheKey );

			if ( $data === false ) {
				throw new RevisionAccessException(
					"Failed to expand blob data using flags $blobFlags (key: $cacheKey)"
				);
			}
		} else {
			$address = $slot->getAddress();
			$data = $this->contentStore->getBlob( $address, $queryFlags );

			if ( $data === false ) {
				throw new RevisionAccessException( "Failed to load data blob from $address" );
			}
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
	public function newRevisionFromArchiveRow(
		$row,
		$queryFlags = 0,
		Title $title = null,
		array $overrides = []
	) {
		Assert::parameterType( 'object', $row, '$row' );

		// check second argument, since Revision::newFromArchiveRow had $overrides in that spot.
		Assert::parameterType( 'integer', $queryFlags, '$queryFlags' );

		if ( !$title && isset( $overrides['title'] ) ) {
			if ( !( $overrides['title'] instanceof Title ) ) {
				throw new MWException( 'title field override must contain a Title object.' );
			}

			$title = $overrides['title'];
		}

		if ( !isset( $title ) ) {
			if ( isset( $row->ar_namespace ) && isset( $row->ar_title ) ) {
				$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
			} else {
				throw new InvalidArgumentException(
					'A Title or ar_namespace and ar_title must be given'
				);
			}
		}

		foreach ( $overrides as $key => $value ) {
			$field = "ar_$key";
			$row->$field = $value;
		}

		// Replaces old lazy loading logic in Revision::getUserText.
		// TODO: wrap this in a callback to make it lazy again.
		if ( !isset( $row->user_name ) && $row->ar_user !== 0 ) {
			$name = User::whoIs( $row->ar_user );
			if ( $name !== false ) {
				$row->user_name = $name;
			}
		}

		$comment = CommentStore::newKey( 'ar_comment' )
			// Legacy because $row may have come from self::selectFields()
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
	 * @throws RevisionAccessException
	 */
	private function newRevisionFromRow_1_29( $row, $queryFlags = 0, Title $title = null ) {
		Assert::parameterType( 'object', $row, '$row' );

		if ( !$title ) {
			$pageId = isset( $row->rev_page ) ? $row->rev_page : 0; // XXX: also check page_id?
			$revId = isset( $row->rev_id ) ? $row->rev_id : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		// Replaces old lazy loading logic in Revision::getUserText.
		// TODO: wrap this in a callback to make it lazy again.
		if ( !isset( $row->user_name ) && $row->rev_user !== 0 ) {
			$name = User::whoIs( $row->rev_user );
			if ( $name !== false ) {
				$row->user_name = $name;
			}
		}

		if ( !isset( $row->page_latest ) ) {
			$row->page_latest = $title->getLatestRevID();
			if ( $row->page_latest === 0 && $title->exists() ) {
				wfWarn( 'Encountered title object in limbo: ID ' . $title->getArticleID() );
			}
		}

		$comment = CommentStore::newKey( 'rev_comment' )
			// Legacy because $row may have come from self::selectFields()
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
	 * @throws RevisionAccessException
	 */
	public function newRevisionFromArray( array $fields, $queryFlags = 0, Title $title = null ) {
		if ( !$title && isset( $fields['title'] ) ) {
			if ( !( $fields['title'] instanceof Title ) ) {
				throw new MWException( 'title field must contain a Title object.' );
			}

			$title = $fields['title'];
		}

		if ( !$title ) {
			$pageId = isset( $fields['page'] ) ? $fields['page'] : 0;
			$revId = isset( $fields['id'] ) ? $fields['id'] : 0;

			$title = $this->getTitle( $pageId, $revId );
		}

		// if we have a content object, use it to set the model and type
		if ( !empty( $fields['content'] ) ) {
			if ( !( $fields['content'] instanceof Content ) ) {
				throw new MWException( 'content field must contain a Content object.' );
			}

			if ( !empty( $fields['text_id'] ) ) {
				throw new MWException(
					"Text already stored in external store (id {$fields['text_id']}), " .
					"can't serialize content object"
				);
			}
		}

		// Replaces old lazy loading logic in Revision::getUserText.
		if ( !isset( $fields['user_text'] ) && isset( $fields['user'] ) ) {
			if ( $fields['user'] instanceof User ) {
				/** @var User $user */
				$user = $fields['user'];
				$fields['user_text'] = $user->getName();
				$fields['user'] = $user->getId();
			} else {
				// TODO: wrap this in a callback to make it lazy again.
				$name = $fields['user'] === 0 ? false : User::whoIs( $fields['user'] );

				if ( $name === false ) {
					throw new MWException(
						'user_text not given, and unknown user ID ' . $fields['user']
					);
				}

				$fields['user_text'] = $name;
			}
		}

		if ( isset( $fields['comment'] ) && !( $fields['comment'] instanceof CommentStoreComment ) ) {
			$commentData = isset( $fields['comment_data'] ) ? $fields['comment_data'] : null;

			if ( $fields['comment'] instanceof Message ) {
				$fields['comment'] = CommentStoreComment::newUnsavedComment(
					$fields['comment'],
					$commentData
				);
			} else {
				$commentText = trim( strval( $fields['comment'] ) );
				$fields['comment'] = CommentStoreComment::newUnsavedComment(
					$commentText,
					$commentData
				);
			}
		}

		$mainSlot = $this->emulateMainSlot_1_29( $fields, $queryFlags, $title );

		$revision = MutableRevisionRecord::newFromArray( $title, $fields, $this->wikiId );
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
	private function loadRevisionFromConds(
		IDatabase $db,
		$conditions,
		$flags = 0,
		Title $title = null
	) {
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
		$storeWiki = $this->wikiId;
		$dbWiki = $db->getDomainID();

		if ( $dbWiki === $storeWiki ) {
			return;
		}

		// XXX: we really want the default database ID...
		$storeWiki = $storeWiki ?: wfWikiID();
		$dbWiki = $dbWiki ?: wfWikiID();

		if ( $dbWiki !== $storeWiki ) {
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

		$revQuery = self::getQueryInfo( [ 'page', 'user' ] );
		$options = [];
		if ( ( $flags & self::READ_LOCKING ) == self::READ_LOCKING ) {
			$options[] = 'FOR UPDATE';
		}
		return $db->selectRow(
			$revQuery['tables'],
			$revQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$revQuery['joins']
		);
	}

	/**
	 * Return the value of a select() JOIN conds array for the user table.
	 * This will get user table rows for logged-in users.
	 *
	 * MCR migration note: this replaces Revision::userJoinCond
	 * @deprecated since 1.31, use self::getQueryInfo( ['user'] ) instead.
	 *
	 * @return array
	 */
	public function userJoinCond() {
		return $this->getQueryInfo( ['user'] )['joins'];
	}

	/**
	 * Return the value of a select() page conds array for the page table.
	 * This will assure that the revision(s) are not orphaned from live pages.
	 *
	 * MCR migration note: this replaces Revision::pageJoinCond
	 * @deprecated since 1.31, use self::getQueryInfo( ['page'] ) instead.
	 *
	 * @return array
	 */
	public function pageJoinCond() {
		return $this->getQueryInfo( ['page'] )['joins'];
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 *
	 * MCR migration note: this replaces Revision::selectFields
	 * @deprecated since 1.31, use self::getQueryInfo() instead.
	 *
	 * @return array
	 */
	public function selectRevisionFields() {
		return $this->getQueryInfo()['fields'];
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision from an archive row.
	 *
	 * MCR migration note: this replaces Revision::selectArchiveFields
	 * @deprecated since 1.31, use self::getArchiveQueryInfo() instead.
	 *
	 * @return array
	 */
	public function selectArchiveFields() {
		return $this->getArchiveQueryInfo()['fields'];
	}

	/**
	 * Return the list of text fields that should be selected to read the
	 * revision text
	 *
	 * MCR migration note: this replaces Revision::selectTextFields
	 * @deprecated since 1.31, use self::getQueryInfo( [ 'text' ] ) instead.
	 *
	 * @return array
	 */
	public function selectTextFields() {
		return $this->getQueryInfo( ['text'] )['fields'];
	}

	/**
	 * Return the list of page fields that should be selected from page table
	 *
	 * MCR migration note: this replaces Revision::selectPageFields
	 * @deprecated since 1.31, use self::getQueryInfo( [ 'page' ] ) instead.
	 *
	 * @return array
	 */
	public function selectPageFields() {
		return $this->getQueryInfo( ['page'] )['fields'];
	}

	/**
	 * Return the list of user fields that should be selected from user table
	 *
	 * MCR migration note: this replaces Revision::selectUserFields
	 * @deprecated since 1.31, use self::getQueryInfo( [ 'user' ] ) instead.
	 *
	 * @return array
	 */
	public function selectUserFields() {
		return $this->getQueryInfo( ['user'] )['fields'];
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new revision object.
	 *
	 * MCR migration note: this replaces Revision::getQueryInfo
	 *
	 * @since 1.31
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *  - 'text': Join with the text table, and select fields to load page text
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getQueryInfo( $options = [] ) {
		$commentQuery = CommentStore::newKey( 'rev_comment' )->getJoin();
		$ret = [
			'tables' => [ 'revision' ] + $commentQuery['tables'],
			'fields' => [
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
				] + $commentQuery['fields'],
			'joins' => $commentQuery['joins'],
		];

		if ( $this->contentHandlerUseDB ) {
			$ret['fields'][] = 'rev_content_format';
			$ret['fields'][] = 'rev_content_model';
		}

		if ( in_array( 'page', $options, true ) ) {
			$ret['tables'][] = 'page';
			$ret['fields'] = array_merge( $ret['fields'], [
				'page_namespace',
				'page_title',
				'page_id',
				'page_latest',
				'page_is_redirect',
				'page_len',
			] );
			$ret['joins']['page'] = [ 'INNER JOIN', [ 'page_id = rev_page' ] ];
		}

		if ( in_array( 'user', $options, true ) ) {
			$ret['tables'][] = 'user';
			$ret['fields'] = array_merge( $ret['fields'], [
				'user_name',
			] );
			$ret['joins']['user'] = [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ];
		}

		if ( in_array( 'text', $options, true ) ) {
			$ret['tables'][] = 'text';
			$ret['fields'] = array_merge( $ret['fields'], [
				'old_text',
				'old_flags'
			] );
			$ret['joins']['text'] = [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ];
		}

		return $ret;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new archived revision object.
	 *
	 * MCR migration note: this replaces Revision::getArchiveQueryInfo
	 *
	 * @since 1.31
	 *
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getArchiveQueryInfo() {
		$commentQuery = CommentStore::newKey( 'ar_comment' )->getJoin();
		$ret = [
			'tables' => [ 'archive' ] + $commentQuery['tables'],
			'fields' => [
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
				] + $commentQuery['fields'],
			'joins' => $commentQuery['joins'],
		];

		if ( $this->contentHandlerUseDB ) {
			$ret['fields'][] = 'ar_content_format';
			$ret['fields'][] = 'ar_content_model';
		}

		return $ret;
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
			$prevId = $db->selectField(
				'page', 'page_latest',
				[ 'page_id' => $rev->getPageId() ],
				__METHOD__
			);
		} else {
			$prevId = $db->selectField(
				'revision', 'rev_id',
				[ 'rev_page' => $rev->getPageId(), 'rev_id < ' . $rev->getId() ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_id DESC' ]
			);
		}
		return intval( $prevId );
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

		$res = $db->select(
			'revision',
			'rev_user',
			[
				'rev_page' => $pageId,
				'rev_timestamp > ' . $db->addQuotes( $db->timestamp( $since ) )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ]
		);
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
	 *
	 * MCR migration note: this replaces Revision::newKnownCurrent
	 *
	 * @param IDatabase $db
	 * @param Title $title the associated page title
	 * @param int $revId current revision of this page. Defaults to $title->getLatestRevID().
	 *
	 * @return RevisionRecord|bool Returns false if missing
	 */
	public function getKnownCurrentRevision( IDatabase $db, Title $title, $revId = 0 ) {
		$this->checkDatabaseWikiId( $db );

		$pageId = $title->getArticleID();

		if ( !$pageId ) {
			return false;
		}

		if ( !$revId ) {
			$revId = $title->getLatestRevID();
		}

		if ( !$revId ) {
			wfWarn(
				'No latest revision known for page ' . $title->getPrefixedDBkey()
				. ' even though it exists with page ID ' . $pageId
			);
			return false;
		}

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
			return $this->newRevisionFromRow( $row, 0, $title );
		} else {
			return false;
		}
	}

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
