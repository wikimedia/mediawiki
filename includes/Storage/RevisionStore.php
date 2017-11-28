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
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship.
 *
 * @file
 */

namespace MediaWiki\Storage;

use ActorMigration;
use CommentStore;
use CommentStoreComment;
use Content;
use ContentHandler;
use DBAccessObjectUtils;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use IP;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Message;
use MWException;
use MWUnknownContentModelException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChange;
use stdClass;
use Title;
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
class RevisionStore
	implements IDBAccessObject, RevisionFactory, RevisionLookup, LoggerAwareInterface {

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

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
	 * @var CommentStore
	 */
	private $commentStore;

	/**
	 * @var ActorMigration
	 */
	private $actorMigration;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $cache
	 * @param CommentStore $commentStore
	 * @param ActorMigration $actorMigration
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		WANObjectCache $cache,
		CommentStore $commentStore,
		ActorMigration $actorMigration,
		$wikiId = false
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
		$this->cache = $cache;
		$this->commentStore = $commentStore;
		$this->actorMigration = $actorMigration;
		$this->wikiId = $wikiId;
		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return bool Whether the store is read-only
	 */
	public function isReadOnly() {
		return $this->blobStore->isReadOnly();
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
	 * @note this method should be private, external use should be avoided!
	 *
	 * @param int|null $pageId
	 * @param int|null $revId
	 * @param int $queryFlags
	 *
	 * @return Title
	 * @throws RevisionAccessException
	 */
	public function getTitle( $pageId, $revId, $queryFlags = self::READ_NORMAL ) {
		if ( !$pageId && !$revId ) {
			throw new InvalidArgumentException( '$pageId and $revId cannot both be 0 or null' );
		}

		// This method recalls itself with READ_LATEST if READ_NORMAL doesn't get us a Title
		// So ignore READ_LATEST_IMMUTABLE flags and handle the fallback logic in this method
		if ( DBAccessObjectUtils::hasFlags( $queryFlags, self::READ_LATEST_IMMUTABLE ) ) {
			$queryFlags = self::READ_NORMAL;
		}

		$canUseTitleNewFromId = ( $pageId !== null && $pageId > 0 && $this->wikiId === false );
		list( $dbMode, $dbOptions ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		$titleFlags = ( $dbMode == DB_MASTER ? Title::GAID_FOR_UPDATE : 0 );

		// Loading by ID is best, but Title::newFromID does not support that for foreign IDs.
		if ( $canUseTitleNewFromId ) {
			// TODO: better foreign title handling (introduce TitleFactory)
			$title = Title::newFromID( $pageId, $titleFlags );
			if ( $title ) {
				return $title;
			}
		}

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		$canUseRevId = ( $revId !== null && $revId > 0 );

		if ( $canUseRevId ) {
			$dbr = $this->getDBConnectionRef( $dbMode );
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
				$dbOptions,
				[ 'page' => [ 'JOIN', 'page_id=rev_page' ] ]
			);
			if ( $row ) {
				// TODO: better foreign title handling (introduce TitleFactory)
				return Title::newFromRow( $row );
			}
		}

		// If we still don't have a title, fallback to master if that wasn't already happening.
		if ( $dbMode !== DB_MASTER ) {
			$title = $this->getTitle( $pageId, $revId, self::READ_LATEST );
			if ( $title ) {
				$this->logger->info(
					__METHOD__ . ' fell back to READ_LATEST and got a Title.',
					[ 'trace' => wfBacktrace() ]
				);
				return $title;
			}
		}

		throw new RevisionAccessException(
			"Could not determine title for page ID $pageId and revision ID $revId"
		);
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
				"$name must not be " . var_export( $value, true ) . "!"
			);
		}

		return $value;
	}

	/**
	 * @param mixed $value
	 * @param string $name
	 *
	 * @throw IncompleteRevisionException if $value is empty
	 * @return mixed $value, if $value is not null
	 */
	private function failOnEmpty( $value, $name ) {
		if ( $value === null || $value === 0 || $value === '' ) {
			throw new IncompleteRevisionException(
				"$name must not be " . var_export( $value, true ) . "!"
			);
		}

		return $value;
	}

	/**
	 * Insert a new revision into the database, returning the new revision record
	 * on success and dies horribly on failure.
	 *
	 * MCR migration note: this replaces Revision::insertOn
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 *
	 * @throws InvalidArgumentException
	 * @return RevisionRecord the new revision record.
	 */
	public function insertRevisionOn( RevisionRecord $rev, IDatabase $dbw ) {
		// TODO: pass in a DBTransactionContext instead of a database connection.
		$this->checkDatabaseWikiId( $dbw );

		if ( !$rev->getSlotRoles() ) {
			throw new InvalidArgumentException( 'At least one slot needs to be defined!' );
		}

		if ( $rev->getSlotRoles() !== [ 'main' ] ) {
			throw new InvalidArgumentException( 'Only the main slot is supported for now!' );
		}

		// TODO: we shouldn't need an actual Title here.
		$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
		$pageId = $this->failOnEmpty( $rev->getPageId(), 'rev_page field' ); // check this early

		$parentId = $rev->getParentId() === null
			? $this->getPreviousRevisionId( $dbw, $rev )
			: $rev->getParentId();

		// Record the text (or external storage URL) to the blob store
		$slot = $rev->getSlot( 'main', RevisionRecord::RAW );

		$size = $this->failOnNull( $rev->getSize(), 'size field' );
		$sha1 = $this->failOnEmpty( $rev->getSha1(), 'sha1 field' );

		if ( !$slot->hasAddress() ) {
			$content = $slot->getContent();
			$format = $content->getDefaultFormat();
			$model = $content->getModel();

			$this->checkContentModel( $content, $title );

			$data = $content->serialize( $format );

			// Hints allow the blob store to optimize by "leaking" application level information to it.
			// TODO: with the new MCR storage schema, we rev_id have this before storing the blobs.
			// When we have it, add rev_id as a hint. Can be used with rev_parent_id for
			// differential storage or compression of subsequent revisions.
			$blobHints = [
				BlobStore::DESIGNATION_HINT => 'page-content', // BlobStore may be used for other things too.
				BlobStore::PAGE_HINT => $pageId,
				BlobStore::ROLE_HINT => $slot->getRole(),
				BlobStore::PARENT_HINT => $parentId,
				BlobStore::SHA1_HINT => $slot->getSha1(),
				BlobStore::MODEL_HINT => $model,
				BlobStore::FORMAT_HINT => $format,
			];

			$blobAddress = $this->blobStore->storeBlob( $data, $blobHints );
		} else {
			$blobAddress = $slot->getAddress();
			$model = $slot->getModel();
			$format = $slot->getFormat();
		}

		$textId = $this->blobStore->getTextIdFromAddress( $blobAddress );

		if ( !$textId ) {
			throw new LogicException(
				'Blob address not supported in 1.29 database schema: ' . $blobAddress
			);
		}

		// getTextIdFromAddress() is free to insert something into the text table, so $textId
		// may be a new value, not anything already contained in $blobAddress.
		$blobAddress = 'tt:' . $textId;

		$comment = $this->failOnNull( $rev->getComment( RevisionRecord::RAW ), 'comment' );
		$user = $this->failOnNull( $rev->getUser( RevisionRecord::RAW ), 'user' );
		$timestamp = $this->failOnEmpty( $rev->getTimestamp(), 'timestamp field' );

		// Checks.
		$this->failOnNull( $user->getId(), 'user field' );
		$this->failOnEmpty( $user->getName(), 'user_text field' );

		# Record the edit in revisions
		$row = [
			'rev_page'       => $pageId,
			'rev_parent_id'  => $parentId,
			'rev_text_id'    => $textId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
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
			$this->commentStore->insertWithTempTable( $dbw, 'rev_comment', $comment );
		$row += $commentFields;

		list( $actorFields, $actorCallback ) =
			$this->actorMigration->getInsertValuesWithTempTable( $dbw, 'rev_user', $user );
		$row += $actorFields;

		if ( $this->contentHandlerUseDB ) {
			// MCR migration note: rev_content_model and rev_content_format will go away

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

			$row['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
			$row['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
		}

		$dbw->insert( 'revision', $row, __METHOD__ );

		if ( !isset( $row['rev_id'] ) ) {
			// only if auto-increment was used
			$row['rev_id'] = intval( $dbw->insertId() );
		}
		$commentCallback( $row['rev_id'] );
		$actorCallback( $row['rev_id'], $row );

		// Insert IP revision into ip_changes for use when querying for a range.
		if ( $user->getId() === 0 && IP::isValid( $user->getName() ) ) {
			$ipcRow = [
				'ipc_rev_id'        => $row['rev_id'],
				'ipc_rev_timestamp' => $row['rev_timestamp'],
				'ipc_hex'           => IP::toHex( $user->getName() ),
			];
			$dbw->insert( 'ip_changes', $ipcRow, __METHOD__ );
		}

		$newSlot = SlotRecord::newSaved( $row['rev_id'], $textId, $blobAddress, $slot );
		$slots = new RevisionSlots( [ 'main' => $newSlot ] );

		$rev = new RevisionStoreRecord(
			$title,
			$user,
			$comment,
			(object)$row,
			$slots,
			$this->wikiId
		);

		$newSlot = $rev->getSlot( 'main', RevisionRecord::RAW );

		// sanity checks
		Assert::postcondition( $rev->getId() > 0, 'revision must have an ID' );
		Assert::postcondition( $rev->getPageId() > 0, 'revision must have a page ID' );
		Assert::postcondition(
			$rev->getComment( RevisionRecord::RAW ) !== null,
			'revision must have a comment'
		);
		Assert::postcondition(
			$rev->getUser( RevisionRecord::RAW ) !== null,
			'revision must have a user'
		);

		Assert::postcondition( $newSlot !== null, 'revision must have a main slot' );
		Assert::postcondition(
			$newSlot->getAddress() !== null,
			'main slot must have an addess'
		);

		Hooks::run( 'RevisionRecordInserted', [ $rev ] );

		return $rev;
	}

	/**
	 * MCR migration note: this corresponds to Revision::checkContentModel
	 *
	 * @param Content $content
	 * @param Title $title
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function checkContentModel( Content $content, Title $title ) {
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
					. "default for $name is $defaultModel"
				);
			}

			if ( $format != $defaultFormat ) {
				throw new MWException( "Can't use non-default content format with "
					. "\$wgContentHandlerUseDB disabled: format is $format, "
					. "default for $name is $defaultFormat"
				);
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
	 * @todo Introduce newFromParentRevision(). newNullRevision can then be based on that
	 * (or go away).
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
			$fields = [
				'page'        => $title->getArticleID(),
				'user_text'   => $user->getName(),
				'user'        => $user->getId(),
				'actor'       => $user->getActorId(),
				'comment'     => $comment,
				'minor_edit'  => $minor,
				'text_id'     => $current->rev_text_id,
				'parent_id'   => $current->page_latest,
				'slot_origin' => $current->page_latest,
				'len'         => $current->rev_len,
				'sha1'        => $current->rev_sha1
			];

			if ( $this->contentHandlerUseDB ) {
				$fields['content_model'] = $current->rev_content_model;
				$fields['content_format'] = $current->rev_content_format;
			}

			$fields['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			$mainSlot = $this->emulateMainSlot_1_29( $fields, self::READ_LATEST, $title );
			$revision = new MutableRevisionRecord( $title, $this->wikiId );
			$this->initializeMutableRevisionFromArray( $revision, $fields );
			$revision->setSlot( $mainSlot );
		} else {
			$revision = null;
		}

		return $revision;
	}

	/**
	 * MCR migration note: this replaces Revision::isUnpatrolled
	 *
	 * @todo This is overly specific, so move or kill this method.
	 *
	 * @param RevisionRecord $rev
	 *
	 * @return int Rcid of the unpatrolled row, zero if there isn't one
	 */
	public function getRcIdIfUnpatrolled( RevisionRecord $rev ) {
		$rc = $this->getRecentChange( $rev );
		if ( $rc && $rc->getAttribute( 'rc_patrolled' ) == RecentChange::PRC_UNPATROLLED ) {
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
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *
	 * @return null|RecentChange
	 */
	public function getRecentChange( RevisionRecord $rev, $flags = 0 ) {
		$dbr = $this->getDBConnection( DB_REPLICA );

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );

		$userIdentity = $rev->getUser( RevisionRecord::RAW );

		if ( !$userIdentity ) {
			// If the revision has no user identity, chances are it never went
			// into the database, and doesn't have an RC entry.
			return null;
		}

		// TODO: Select by rc_this_oldid alone - but as of Nov 2017, there is no index on that!
		$actorWhere = $this->actorMigration->getWhere( $dbr, 'rc_user', $rev->getUser(), false );
		$rc = RecentChange::newFromConds(
			[
				$actorWhere['conds'],
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
	 * Maps fields of the archive row to corresponding revision rows.
	 *
	 * @param object $archiveRow
	 *
	 * @return object a revision row object, corresponding to $archiveRow.
	 */
	private static function mapArchiveFields( $archiveRow ) {
		$fieldMap = [
			// keep with ar prefix:
			'ar_id'        => 'ar_id',

			// not the same suffix:
			'ar_page_id'        => 'rev_page',
			'ar_rev_id'         => 'rev_id',

			// same suffix:
			'ar_text_id'        => 'rev_text_id',
			'ar_timestamp'      => 'rev_timestamp',
			'ar_user_text'      => 'rev_user_text',
			'ar_user'           => 'rev_user',
			'ar_actor'          => 'rev_actor',
			'ar_minor_edit'     => 'rev_minor_edit',
			'ar_deleted'        => 'rev_deleted',
			'ar_len'            => 'rev_len',
			'ar_parent_id'      => 'rev_parent_id',
			'ar_sha1'           => 'rev_sha1',
			'ar_comment'        => 'rev_comment',
			'ar_comment_cid'    => 'rev_comment_cid',
			'ar_comment_id'     => 'rev_comment_id',
			'ar_comment_text'   => 'rev_comment_text',
			'ar_comment_data'   => 'rev_comment_data',
			'ar_comment_old'    => 'rev_comment_old',
			'ar_content_format' => 'rev_content_format',
			'ar_content_model'  => 'rev_content_model',
		];

		$revRow = new stdClass();
		foreach ( $fieldMap as $arKey => $revKey ) {
			if ( property_exists( $archiveRow, $arKey ) ) {
				$revRow->$revKey = $archiveRow->$arKey;
			}
		}

		return $revRow;
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
		$mainSlotRow = new stdClass();
		$mainSlotRow->role_name = 'main';
		$mainSlotRow->model_name = null;
		$mainSlotRow->slot_revision_id = null;
		$mainSlotRow->content_address = null;
		$mainSlotRow->slot_content_id = null;

		$content = null;
		$blobData = null;
		$blobFlags = null;

		if ( is_object( $row ) ) {
			// archive row
			if ( !isset( $row->rev_id ) && ( isset( $row->ar_user ) || isset( $row->ar_actor ) ) ) {
				$row = $this->mapArchiveFields( $row );
			}

			if ( isset( $row->rev_text_id ) && $row->rev_text_id > 0 ) {
				$mainSlotRow->slot_content_id = $row->rev_text_id;
				$mainSlotRow->content_address = 'tt:' . $row->rev_text_id;
			}

			// This is used by null-revisions
			$mainSlotRow->slot_origin = isset( $row->slot_origin )
				? intval( $row->slot_origin )
				: null;

			if ( isset( $row->old_text ) ) {
				// this happens when the text-table gets joined directly, in the pre-1.30 schema
				$blobData = isset( $row->old_text ) ? strval( $row->old_text ) : null;
				// Check against selects that might have not included old_flags
				if ( !property_exists( $row, 'old_flags' ) ) {
					throw new InvalidArgumentException( 'old_flags was not set in $row' );
				}
				$blobFlags = ( $row->old_flags === null ) ? '' : $row->old_flags;
			}

			$mainSlotRow->slot_revision_id = intval( $row->rev_id );

			$mainSlotRow->content_size = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
			$mainSlotRow->content_sha1 = isset( $row->rev_sha1 ) ? strval( $row->rev_sha1 ) : null;
			$mainSlotRow->model_name = isset( $row->rev_content_model )
				? strval( $row->rev_content_model )
				: null;
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row->rev_content_format )
				? strval( $row->rev_content_format )
				: null;
		} elseif ( is_array( $row ) ) {
			$mainSlotRow->slot_revision_id = isset( $row['id'] ) ? intval( $row['id'] ) : null;

			$mainSlotRow->slot_content_id = isset( $row['text_id'] )
				? intval( $row['text_id'] )
				: null;
			$mainSlotRow->slot_origin = isset( $row['slot_origin'] )
				? intval( $row['slot_origin'] )
				: null;
			$mainSlotRow->content_address = isset( $row['text_id'] )
				? 'tt:' . intval( $row['text_id'] )
				: null;
			$mainSlotRow->content_size = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$mainSlotRow->content_sha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$mainSlotRow->model_name = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;  // XXX: must be a string!
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;
			$blobData = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			// XXX: If the flags field is not set then $blobFlags should be null so that no
			// decoding will happen. An empty string will result in default decodings.
			$blobFlags = isset( $row['flags'] ) ? trim( strval( $row['flags'] ) ) : null;

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

		// With the old schema, the content changes with every revision,
		// except for null-revisions.
		if ( !isset( $mainSlotRow->slot_origin ) ) {
			$mainSlotRow->slot_origin = $mainSlotRow->slot_revision_id;
		}

		if ( $mainSlotRow->model_name === null ) {
			$mainSlotRow->model_name = function ( SlotRecord $slot ) use ( $title ) {
				// TODO: MCR: consider slot role in getDefaultModelFor()! Use LinkTarget!
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

		$mainSlotRow->slot_id = $mainSlotRow->slot_revision_id;
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
	 * @param string|null $blobFlags Flags indicating how $blobData needs to be processed.
	 *        Use null if no processing should happen. That is in constrast to the empty string,
	 *        which causes the blob to be decoded according to the configured legacy encoding.
	 * @param string|null $blobFormat MIME type indicating how $dataBlob is encoded
	 * @param int $queryFlags
	 *
	 * @throw RevisionAccessException
	 * @return Content
	 */
	private function loadSlotContent(
		SlotRecord $slot,
		$blobData = null,
		$blobFlags = null,
		$blobFormat = null,
		$queryFlags = 0
	) {
		if ( $blobData !== null ) {
			Assert::parameterType( 'string', $blobData, '$blobData' );
			Assert::parameterType( 'string|null', $blobFlags, '$blobFlags' );

			$cacheKey = $slot->hasAddress() ? $slot->getAddress() : null;

			if ( $blobFlags === null ) {
				// No blob flags, so use the blob verbatim.
				$data = $blobData;
			} else {
				$data = $this->blobStore->expandBlob( $blobData, $blobFlags, $cacheKey );
				if ( $data === false ) {
					throw new RevisionAccessException(
						"Failed to expand blob data using flags $blobFlags (key: $cacheKey)"
					);
				}
			}

		} else {
			$address = $slot->getAddress();
			try {
				$data = $this->blobStore->getBlob( $address, $queryFlags );
			} catch ( BlobAccessException $e ) {
				throw new RevisionAccessException(
					"Failed to load data blob from $address: " . $e->getMessage(), 0, $e
				);
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
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
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
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param LinkTarget $linkTarget
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTitle( LinkTarget $linkTarget, $revId = 0, $flags = 0 ) {
		$conds = [
			'page_namespace' => $linkTarget->getNamespace(),
			'page_title' => $linkTarget->getDBkey()
		];
		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to master if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to master. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
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
	 *      IDBAccessObject::READ_LATEST: Select the data from the master (since 1.20)
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the master
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByPageId( $pageId, $revId = 0, $flags = 0 ) {
		$conds = [ 'page_id' => $pageId ];
		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to master if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to master. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
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
	public function getRevisionByTimestamp( $title, $timestamp ) {
		$db = $this->getDBConnection( DB_REPLICA );
		return $this->newRevisionFromConds(
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
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 * @param array $overrides associative array with fields of $row to override. This may be
	 *   used e.g. to force the parent revision ID or page ID. Keys in the array are fields
	 *   names from the archive table without the 'ar_' prefix, i.e. use 'parent_id' to
	 *   override ar_parent_id.
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

		try {
			$user = User::newFromAnyId(
				isset( $row->ar_user ) ? $row->ar_user : null,
				isset( $row->ar_user_text ) ? $row->ar_user_text : null,
				isset( $row->ar_actor ) ? $row->ar_actor : null
			);
		} catch ( InvalidArgumentException $ex ) {
			wfWarn( __METHOD__ . ': ' . $ex->getMessage() );
			$user = new UserIdentityValue( 0, '', 0 );
		}

		$comment = $this->commentStore
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), 'ar_comment', $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionArchiveRecord( $title, $user, $comment, $row, $slots, $this->wikiId );
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

			$title = $this->getTitle( $pageId, $revId, $queryFlags );
		}

		if ( !isset( $row->page_latest ) ) {
			$row->page_latest = $title->getLatestRevID();
			if ( $row->page_latest === 0 && $title->exists() ) {
				wfWarn( 'Encountered title object in limbo: ID ' . $title->getArticleID() );
			}
		}

		try {
			$user = User::newFromAnyId(
				isset( $row->rev_user ) ? $row->rev_user : null,
				isset( $row->rev_user_text ) ? $row->rev_user_text : null,
				isset( $row->rev_actor ) ? $row->rev_actor : null
			);
		} catch ( InvalidArgumentException $ex ) {
			wfWarn( __METHOD__ . ': ' . $ex->getMessage() );
			$user = new UserIdentityValue( 0, '', 0 );
		}

		$comment = $this->commentStore
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), 'rev_comment', $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionStoreRecord( $title, $user, $comment, $row, $slots, $this->wikiId );
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
	 * Constructs a new MutableRevisionRecord based on the given associative array following
	 * the MW1.29 convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param array $fields
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return MutableRevisionRecord
	 * @throws MWException
	 * @throws RevisionAccessException
	 */
	public function newMutableRevisionFromArray(
		array $fields,
		$queryFlags = 0,
		Title $title = null
	) {
		if ( !$title && isset( $fields['title'] ) ) {
			if ( !( $fields['title'] instanceof Title ) ) {
				throw new MWException( 'title field must contain a Title object.' );
			}

			$title = $fields['title'];
		}

		if ( !$title ) {
			$pageId = isset( $fields['page'] ) ? $fields['page'] : 0;
			$revId = isset( $fields['id'] ) ? $fields['id'] : 0;

			$title = $this->getTitle( $pageId, $revId, $queryFlags );
		}

		if ( !isset( $fields['page'] ) ) {
			$fields['page'] = $title->getArticleID( $queryFlags );
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

		if (
			isset( $fields['comment'] )
			&& !( $fields['comment'] instanceof CommentStoreComment )
		) {
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

		$revision = new MutableRevisionRecord( $title, $this->wikiId );
		$this->initializeMutableRevisionFromArray( $revision, $fields );
		$revision->setSlot( $mainSlot );

		return $revision;
	}

	/**
	 * @param MutableRevisionRecord $record
	 * @param array $fields
	 */
	private function initializeMutableRevisionFromArray(
		MutableRevisionRecord $record,
		array $fields
	) {
		/** @var UserIdentity $user */
		$user = null;

		if ( isset( $fields['user'] ) && ( $fields['user'] instanceof UserIdentity ) ) {
			$user = $fields['user'];
		} else {
			try {
				$user = User::newFromAnyId(
					isset( $fields['user'] ) ? $fields['user'] : null,
					isset( $fields['user_text'] ) ? $fields['user_text'] : null,
					isset( $fields['actor'] ) ? $fields['actor'] : null
				);
			} catch ( InvalidArgumentException $ex ) {
				$user = null;
			}
		}

		if ( $user ) {
			$record->setUser( $user );
		}

		$timestamp = isset( $fields['timestamp'] )
			? strval( $fields['timestamp'] )
			: wfTimestampNow(); // TODO: use a callback, so we can override it for testing.

		$record->setTimestamp( $timestamp );

		if ( isset( $fields['page'] ) ) {
			$record->setPageId( intval( $fields['page'] ) );
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
			$record->setVisibility( intval( $fields['deleted'] ) );
		}

		if ( isset( $fields['comment'] ) ) {
			Assert::parameterType(
				CommentStoreComment::class,
				$fields['comment'],
				'$row[\'comment\']'
			);
			$record->setComment( $fields['comment'] );
		}
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

		return $this->loadRevisionFromConds(
			$db,
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
	 * This method should be used if we are pretty sure the revision exists.
	 * Unless $flags has READ_LATEST set, this method will first try to find the revision
	 * on a replica before hitting the master database.
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
		$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $title );
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
			$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $title );
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

		if ( $dbWiki === $storeWiki ) {
			return;
		}

		// HACK: counteract encoding imposed by DatabaseDomain
		$storeWiki = str_replace( '?h', '-', $storeWiki );
		$dbWiki = str_replace( '?h', '-', $dbWiki );

		if ( $dbWiki === $storeWiki ) {
			return;
		}

		throw new MWException( "RevisionStore for $storeWiki "
			. "cannot be used with a DB connection for $dbWiki" );
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
	 *
	 * @return array With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getQueryInfo( $options = [] ) {
		$ret = [
			'tables' => [],
			'fields' => [],
			'joins'  => [],
		];

		$ret['tables'][] = 'revision';
		$ret['fields'] = array_merge( $ret['fields'], [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		] );

		$commentQuery = $this->commentStore->getJoin( 'rev_comment' );
		$ret['tables'] = array_merge( $ret['tables'], $commentQuery['tables'] );
		$ret['fields'] = array_merge( $ret['fields'], $commentQuery['fields'] );
		$ret['joins'] = array_merge( $ret['joins'], $commentQuery['joins'] );

		$actorQuery = $this->actorMigration->getJoin( 'rev_user' );
		$ret['tables'] = array_merge( $ret['tables'], $actorQuery['tables'] );
		$ret['fields'] = array_merge( $ret['fields'], $actorQuery['fields'] );
		$ret['joins'] = array_merge( $ret['joins'], $actorQuery['joins'] );

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
			$u = $actorQuery['fields']['rev_user'];
			$ret['joins']['user'] = [ 'LEFT JOIN', [ "$u != 0", "user_id = $u" ] ];
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
		$commentQuery = $this->commentStore->getJoin( 'ar_comment' );
		$actorQuery = $this->actorMigration->getJoin( 'ar_user' );
		$ret = [
			'tables' => [ 'archive' ] + $commentQuery['tables'] + $actorQuery['tables'],
			'fields' => [
					'ar_id',
					'ar_page_id',
					'ar_namespace',
					'ar_title',
					'ar_rev_id',
					'ar_text_id',
					'ar_timestamp',
					'ar_minor_edit',
					'ar_deleted',
					'ar_len',
					'ar_parent_id',
					'ar_sha1',
				] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
		];

		if ( $this->contentHandlerUseDB ) {
			$ret['fields'][] = 'ar_content_format';
			$ret['fields'][] = 'ar_content_model';
		}

		return $ret;
	}

	/**
	 * Do a batched query for the sizes of a set of revisions.
	 *
	 * MCR migration note: this replaces Revision::getParentLengths
	 *
	 * @param int[] $revIds
	 * @return int[] associative array mapping revision IDs from $revIds to the nominal size
	 *         of the corresponding revision.
	 */
	public function getRevisionSizes( array $revIds ) {
		return $this->listRevisionSizes( $this->getDBConnection( DB_REPLICA ), $revIds );
	}

	/**
	 * Do a batched query for the sizes of a set of revisions.
	 *
	 * MCR migration note: this replaces Revision::getParentLengths
	 *
	 * @deprecated use RevisionStore::getRevisionSizes instead.
	 *
	 * @param IDatabase $db
	 * @param int[] $revIds
	 * @return int[] associative array mapping revision IDs from $revIds to the nominal size
	 *         of the corresponding revision.
	 */
	public function listRevisionSizes( IDatabase $db, array $revIds ) {
		$this->checkDatabaseWikiId( $db );

		$revLens = [];
		if ( !$revIds ) {
			return $revLens; // empty
		}

		$res = $db->select(
			'revision',
			[ 'rev_id', 'rev_len' ],
			[ 'rev_id' => $revIds ],
			__METHOD__
		);

		foreach ( $res as $row ) {
			$revLens[$row->rev_id] = intval( $row->rev_len );
		}

		return $revLens;
	}

	/**
	 * Get previous revision for this title
	 *
	 * MCR migration note: this replaces Revision::getPrevious
	 *
	 * @param RevisionRecord $rev
	 * @param Title $title if known (optional)
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev, Title $title = null ) {
		if ( $title === null ) {
			$title = $this->getTitle( $rev->getPageId(), $rev->getId() );
		}
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
	 * @param Title $title if known (optional)
	 *
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev, Title $title = null ) {
		if ( $title === null ) {
			$title = $this->getTitle( $rev->getPageId(), $rev->getId() );
		}
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

		$row = $db->selectRow( 'revision',
			[ 'revCount' => 'COUNT(*)' ],
			[ 'rev_page' => $id ],
			__METHOD__
		);
		if ( $row ) {
			return intval( $row->revCount );
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

		$revQuery = self::getQueryInfo();
		$res = $db->select(
			$revQuery['tables'],
			[
				'rev_user' => $revQuery['fields']['rev_user'],
			],
			[
				'rev_page' => $pageId,
				'rev_timestamp > ' . $db->addQuotes( $db->timestamp( $since ) )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ],
			$revQuery['joins']
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
	 * @param Title $title the associated page title
	 * @param int $revId current revision of this page. Defaults to $title->getLatestRevID().
	 *
	 * @return RevisionRecord|bool Returns false if missing
	 */
	public function getKnownCurrentRevision( Title $title, $revId ) {
		$db = $this->getDBConnectionRef( DB_REPLICA );

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
