<?php

namespace MediaWiki\Storage;

use CommentStore;
use ContentHandler;
use Hooks;
use InvalidArgumentException;
use IP;
use LogicException;
use MediaWiki\User\UserIdentityValue;
use Title;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * RevisionStore implementation using the old single content schema.
 */
class SingleContentRevisionStore extends AbstractRevisionStore {

	use SingleContentRevisionQueryInfo;

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

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
		parent::__construct( $loadBalancer, $wikiId );

		$this->blobStore = $blobStore;
		$this->revisionTitleLookup = $revisionTitleLookup;
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

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
