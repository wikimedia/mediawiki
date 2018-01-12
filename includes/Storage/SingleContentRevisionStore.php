<?php

namespace MediaWiki\Storage;

use CommentStore;
use Content;
use ContentHandler;
use DBAccessObjectUtils;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use IP;
use LogicException;
use MediaWiki\User\UserIdentityValue;
use MWException;
use MWUnknownContentModelException;
use RecentChange;
use Title;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * RevisionStore implementation using the old single content schema.
 */
class SingleContentRevisionStore
	implements IDBAccessObject, RevisionStore {

	use DatabaseWikiIdChecker;
	use SingleContentRevisionQueryInfo;

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
	 * @var RevisionTitleLookup
	 */
	private $revisionTitleLookup;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param RevisionTitleLookup $revisionTitleLookup
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		RevisionTitleLookup $revisionTitleLookup,
		$wikiId = false
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
		$this->revisionTitleLookup = $revisionTitleLookup;
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
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $mode ) {
		return $this->loadBalancer->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * @param IDatabase $connection
	 */
	private function releaseDBConnection( IDatabase $connection ) {
		$this->loadBalancer->reuseConnection( $connection );
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
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
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
		$this->checkDatabaseWikiId( $dbw, $this->wikiId );

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

		# Record the edit in revisions
		$row = [
			'rev_page'       => $pageId,
			'rev_parent_id'  => $parentId,
			'rev_text_id'    => $textId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_user'       => $this->failOnNull( $user->getId(), 'user field' ),
			'rev_user_text'  => $this->failOnEmpty( $user->getName(), 'user_text field' ),
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
			// only if auto-increment was used
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

		$user = new UserIdentityValue( intval( $row['rev_user'] ), $row['rev_user_text'] );

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
		$rc = RecentChange::newFromConds(
			[
				'rc_user_text' => $userIdentity->getName(),
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
		$this->checkDatabaseWikiId( $db, $this->wikiId );

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
		$this->checkDatabaseWikiId( $db, $this->wikiId );

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
		$this->checkDatabaseWikiId( $db, $this->wikiId );

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
		$this->checkDatabaseWikiId( $db, $this->wikiId );

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

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
