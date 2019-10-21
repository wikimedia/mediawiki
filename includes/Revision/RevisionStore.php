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

namespace MediaWiki\Revision;

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
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Message;
use MWException;
use MWUnknownContentModelException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChange;
use Revision;
use RuntimeException;
use StatusValue;
use stdClass;
use Title;
use Traversable;
use User;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Service for looking up page revisions.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionStore
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionStore
	implements IDBAccessObject, RevisionFactory, RevisionLookup, LoggerAwareInterface {

	const ROW_CACHE_KEY = 'revision-row-1.29';

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

	/**
	 * @var bool|string
	 */
	private $dbDomain;

	/**
	 * @var boolean
	 * @see $wgContentHandlerUseDB
	 */
	private $contentHandlerUseDB = true;

	/**
	 * @var ILoadBalancer
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
	 * @var NameTableStore
	 */
	private $contentModelStore;

	/**
	 * @var NameTableStore
	 */
	private $slotRoleStore;

	/** @var int An appropriate combination of SCHEMA_COMPAT_XXX flags. */
	private $mcrMigrationStage;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param ILoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $cache A cache for caching revision rows. This can be the local
	 *        wiki's default instance even if $dbDomain refers to a different wiki, since
	 *        makeGlobalKey() is used to constructed a key that allows cached revision rows from
	 *        the same database to be re-used between wikis. For example, enwiki and frwiki will
	 *        use the same cache keys for revision rows from the wikidatawiki database, regardless
	 *        of the cache's default key space.
	 * @param CommentStore $commentStore
	 * @param NameTableStore $contentModelStore
	 * @param NameTableStore $slotRoleStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param int $mcrMigrationStage An appropriate combination of SCHEMA_COMPAT_XXX flags
	 * @param ActorMigration $actorMigration
	 * @param bool|string $dbDomain DB domain of the relevant wiki or false for the current one
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		WANObjectCache $cache,
		CommentStore $commentStore,
		NameTableStore $contentModelStore,
		NameTableStore $slotRoleStore,
		SlotRoleRegistry $slotRoleRegistry,
		$mcrMigrationStage,
		ActorMigration $actorMigration,
		$dbDomain = false
	) {
		Assert::parameterType( 'string|boolean', $dbDomain, '$dbDomain' );
		Assert::parameterType( 'integer', $mcrMigrationStage, '$mcrMigrationStage' );
		Assert::parameter(
			( $mcrMigrationStage & SCHEMA_COMPAT_READ_BOTH ) !== SCHEMA_COMPAT_READ_BOTH,
			'$mcrMigrationStage',
			'Reading from the old and the new schema at the same time is not supported.'
		);
		Assert::parameter(
			( $mcrMigrationStage & SCHEMA_COMPAT_READ_BOTH ) !== 0,
			'$mcrMigrationStage',
			'Reading needs to be enabled for the old or the new schema.'
		);
		Assert::parameter(
			( $mcrMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) !== 0,
			'$mcrMigrationStage',
			'Writing needs to be enabled for the new schema.'
		);
		Assert::parameter(
			( $mcrMigrationStage & SCHEMA_COMPAT_READ_OLD ) === 0
			|| ( $mcrMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) !== 0,
			'$mcrMigrationStage',
			'Cannot read the old schema when not also writing it.'
		);

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
		$this->cache = $cache;
		$this->commentStore = $commentStore;
		$this->contentModelStore = $contentModelStore;
		$this->slotRoleStore = $slotRoleStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->mcrMigrationStage = $mcrMigrationStage;
		$this->actorMigration = $actorMigration;
		$this->dbDomain = $dbDomain;
		$this->logger = new NullLogger();
	}

	/**
	 * @param int $flags A combination of SCHEMA_COMPAT_XXX flags.
	 * @return bool True if all the given flags were set in the $mcrMigrationStage
	 *         parameter passed to the constructor.
	 */
	private function hasMcrSchemaFlags( $flags ) {
		return ( $this->mcrMigrationStage & $flags ) === $flags;
	}

	/**
	 * Throws a RevisionAccessException if this RevisionStore is configured for cross-wiki loading
	 * and still reading from the old DB schema.
	 *
	 * @throws RevisionAccessException
	 */
	private function assertCrossWikiContentLoadingIsSafe() {
		if ( $this->dbDomain !== false && $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
			throw new RevisionAccessException(
				"Cross-wiki content loading is not supported by the pre-MCR schema"
			);
		}
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
	 * @see $wgContentHandlerUseDB
	 * @param bool $contentHandlerUseDB
	 * @throws MWException
	 */
	public function setContentHandlerUseDB( $contentHandlerUseDB ) {
		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW )
			|| $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_NEW )
		) {
			if ( !$contentHandlerUseDB ) {
				throw new MWException(
					'Content model must be stored in the database for multi content revision migration.'
				);
			}
		}
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @return ILoadBalancer
	 */
	private function getDBLoadBalancer() {
		return $this->loadBalancer;
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRefForQueryFlags( $queryFlags ) {
		list( $mode, ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return $this->getDBConnectionRef( $mode );
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @param array $groups
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode, $groups = [] ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnectionRef( $mode, $groups, $this->dbDomain );
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

		$canUseTitleNewFromId = ( $pageId !== null && $pageId > 0 && $this->dbDomain === false );
		list( $dbMode, $dbOptions ) = DBAccessObjectUtils::getDBOptions( $queryFlags );

		// Loading by ID is best, but Title::newFromID does not support that for foreign IDs.
		if ( $canUseTitleNewFromId ) {
			$titleFlags = ( $dbMode == DB_MASTER ? Title::READ_LATEST : 0 );
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
	 * @throws IncompleteRevisionException if $value is null
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
	 * @throws IncompleteRevisionException if $value is empty
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
		$this->checkDatabaseDomain( $dbw );

		$slotRoles = $rev->getSlotRoles();

		// Make sure the main slot is always provided throughout migration
		if ( !in_array( SlotRecord::MAIN, $slotRoles ) ) {
			throw new InvalidArgumentException(
				'main slot must be provided'
			);
		}

		// If we are not writing into the new schema, we can't support extra slots.
		if ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW )
			&& $slotRoles !== [ SlotRecord::MAIN ]
		) {
			throw new InvalidArgumentException(
				'Only the main slot is supported when not writing to the MCR enabled schema!'
			);
		}

		// As long as we are not reading from the new schema, we don't want to write extra slots.
		if ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_NEW )
			&& $slotRoles !== [ SlotRecord::MAIN ]
		) {
			throw new InvalidArgumentException(
				'Only the main slot is supported when not reading from the MCR enabled schema!'
			);
		}

		// Checks
		$this->failOnNull( $rev->getSize(), 'size field' );
		$this->failOnEmpty( $rev->getSha1(), 'sha1 field' );
		$this->failOnEmpty( $rev->getTimestamp(), 'timestamp field' );
		$comment = $this->failOnNull( $rev->getComment( RevisionRecord::RAW ), 'comment' );
		$user = $this->failOnNull( $rev->getUser( RevisionRecord::RAW ), 'user' );
		$this->failOnNull( $user->getId(), 'user field' );
		$this->failOnEmpty( $user->getName(), 'user_text field' );

		if ( !$rev->isReadyForInsertion() ) {
			// This is here for future-proofing. At the time this check being added, it
			// was redundant to the individual checks above.
			throw new IncompleteRevisionException( 'Revision is incomplete' );
		}

		// TODO: we shouldn't need an actual Title here.
		$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
		$pageId = $this->failOnEmpty( $rev->getPageId(), 'rev_page field' ); // check this early

		$parentId = $rev->getParentId() === null
			? $this->getPreviousRevisionId( $dbw, $rev )
			: $rev->getParentId();

		/** @var RevisionRecord $rev */
		$rev = $dbw->doAtomicSection(
			__METHOD__,
			function ( IDatabase $dbw, $fname ) use (
				$rev,
				$user,
				$comment,
				$title,
				$pageId,
				$parentId
			) {
				return $this->insertRevisionInternal(
					$rev,
					$dbw,
					$user,
					$comment,
					$title,
					$pageId,
					$parentId
				);
			}
		);

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

		// Trigger exception if the main slot is missing.
		// Technically, this could go away after MCR migration: while
		// calling code may require a main slot to exist, RevisionStore
		// really should not know or care about that requirement.
		$rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );

		foreach ( $slotRoles as $role ) {
			$slot = $rev->getSlot( $role, RevisionRecord::RAW );
			Assert::postcondition(
				$slot->getContent() !== null,
				$role . ' slot must have content'
			);
			Assert::postcondition(
				$slot->hasRevision(),
				$role . ' slot must have a revision associated'
			);
		}

		Hooks::run( 'RevisionRecordInserted', [ $rev ] );

		// TODO: deprecate in 1.32!
		$legacyRevision = new Revision( $rev );
		Hooks::run( 'RevisionInsertComplete', [ &$legacyRevision, null, null ] );

		return $rev;
	}

	private function insertRevisionInternal(
		RevisionRecord $rev,
		IDatabase $dbw,
		User $user,
		CommentStoreComment $comment,
		Title $title,
		$pageId,
		$parentId
	) {
		$slotRoles = $rev->getSlotRoles();

		$revisionRow = $this->insertRevisionRowOn(
			$dbw,
			$rev,
			$title,
			$parentId
		);

		$revisionId = $revisionRow['rev_id'];

		$blobHints = [
			BlobStore::PAGE_HINT => $pageId,
			BlobStore::REVISION_HINT => $revisionId,
			BlobStore::PARENT_HINT => $parentId,
		];

		$newSlots = [];
		foreach ( $slotRoles as $role ) {
			$slot = $rev->getSlot( $role, RevisionRecord::RAW );

			// If the SlotRecord already has a revision ID set, this means it already exists
			// in the database, and should already belong to the current revision.
			// However, a slot may already have a revision, but no content ID, if the slot
			// is emulated based on the archive table, because we are in SCHEMA_COMPAT_READ_OLD
			// mode, and the respective archive row was not yet migrated to the new schema.
			// In that case, a new slot row (and content row) must be inserted even during
			// undeletion.
			if ( $slot->hasRevision() && $slot->hasContentId() ) {
				// TODO: properly abort transaction if the assertion fails!
				Assert::parameter(
					$slot->getRevision() === $revisionId,
					'slot role ' . $slot->getRole(),
					'Existing slot should belong to revision '
					. $revisionId . ', but belongs to revision ' . $slot->getRevision() . '!'
				);

				// Slot exists, nothing to do, move along.
				// This happens when restoring archived revisions.

				$newSlots[$role] = $slot;

				// Write the main slot's text ID to the revision table for backwards compatibility
				if ( $slot->getRole() === SlotRecord::MAIN
					&& $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_OLD )
				) {
					$blobAddress = $slot->getAddress();
					$this->updateRevisionTextId( $dbw, $revisionId, $blobAddress );
				}
			} else {
				$newSlots[$role] = $this->insertSlotOn( $dbw, $revisionId, $slot, $title, $blobHints );
			}
		}

		$this->insertIpChangesRow( $dbw, $user, $rev, $revisionId );

		$rev = new RevisionStoreRecord(
			$title,
			$user,
			$comment,
			(object)$revisionRow,
			new RevisionSlots( $newSlots ),
			$this->dbDomain
		);

		return $rev;
	}

	/**
	 * @param IDatabase $dbw
	 * @param int $revisionId
	 * @param string &$blobAddress (may change!)
	 *
	 * @return int the text row id
	 */
	private function updateRevisionTextId( IDatabase $dbw, $revisionId, &$blobAddress ) {
		$textId = $this->blobStore->getTextIdFromAddress( $blobAddress );
		if ( !$textId ) {
			throw new LogicException(
				'Blob address not supported in 1.29 database schema: ' . $blobAddress
			);
		}

		// getTextIdFromAddress() is free to insert something into the text table, so $textId
		// may be a new value, not anything already contained in $blobAddress.
		$blobAddress = SqlBlobStore::makeAddressFromTextId( $textId );

		$dbw->update(
			'revision',
			[ 'rev_text_id' => $textId ],
			[ 'rev_id' => $revisionId ],
			__METHOD__
		);

		return $textId;
	}

	/**
	 * @param IDatabase $dbw
	 * @param int $revisionId
	 * @param SlotRecord $protoSlot
	 * @param Title $title
	 * @param array $blobHints See the BlobStore::XXX_HINT constants
	 * @return SlotRecord
	 */
	private function insertSlotOn(
		IDatabase $dbw,
		$revisionId,
		SlotRecord $protoSlot,
		Title $title,
		array $blobHints = []
	) {
		if ( $protoSlot->hasAddress() ) {
			$blobAddress = $protoSlot->getAddress();
		} else {
			$blobAddress = $this->storeContentBlob( $protoSlot, $title, $blobHints );
		}

		$contentId = null;

		// Write the main slot's text ID to the revision table for backwards compatibility
		if ( $protoSlot->getRole() === SlotRecord::MAIN
			&& $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_OLD )
		) {
			// If SCHEMA_COMPAT_WRITE_NEW is also set, the fake content ID is overwritten
			// with the real content ID below.
			$textId = $this->updateRevisionTextId( $dbw, $revisionId, $blobAddress );
			$contentId = $this->emulateContentId( $textId );
		}

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW ) ) {
			if ( $protoSlot->hasContentId() ) {
				$contentId = $protoSlot->getContentId();
			} else {
				$contentId = $this->insertContentRowOn( $protoSlot, $dbw, $blobAddress );
			}

			$this->insertSlotRowOn( $protoSlot, $dbw, $revisionId, $contentId );
		}

		$savedSlot = SlotRecord::newSaved(
			$revisionId,
			$contentId,
			$blobAddress,
			$protoSlot
		);

		return $savedSlot;
	}

	/**
	 * Insert IP revision into ip_changes for use when querying for a range.
	 * @param IDatabase $dbw
	 * @param User $user
	 * @param RevisionRecord $rev
	 * @param int $revisionId
	 */
	private function insertIpChangesRow(
		IDatabase $dbw,
		User $user,
		RevisionRecord $rev,
		$revisionId
	) {
		if ( $user->getId() === 0 && IP::isValid( $user->getName() ) ) {
			$ipcRow = [
				'ipc_rev_id'        => $revisionId,
				'ipc_rev_timestamp' => $dbw->timestamp( $rev->getTimestamp() ),
				'ipc_hex'           => IP::toHex( $user->getName() ),
			];
			$dbw->insert( 'ip_changes', $ipcRow, __METHOD__ );
		}
	}

	/**
	 * @param IDatabase $dbw
	 * @param RevisionRecord $rev
	 * @param Title $title
	 * @param int $parentId
	 *
	 * @return array a revision table row
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function insertRevisionRowOn(
		IDatabase $dbw,
		RevisionRecord $rev,
		Title $title,
		$parentId
	) {
		$revisionRow = $this->getBaseRevisionRow( $dbw, $rev, $title, $parentId );

		list( $commentFields, $commentCallback ) =
			$this->commentStore->insertWithTempTable(
				$dbw,
				'rev_comment',
				$rev->getComment( RevisionRecord::RAW )
			);
		$revisionRow += $commentFields;

		list( $actorFields, $actorCallback ) =
			$this->actorMigration->getInsertValuesWithTempTable(
				$dbw,
				'rev_user',
				$rev->getUser( RevisionRecord::RAW )
			);
		$revisionRow += $actorFields;

		$dbw->insert( 'revision', $revisionRow, __METHOD__ );

		if ( !isset( $revisionRow['rev_id'] ) ) {
			// only if auto-increment was used
			$revisionRow['rev_id'] = intval( $dbw->insertId() );

			if ( $dbw->getType() === 'mysql' ) {
				// (T202032) MySQL until 8.0 and MariaDB until some version after 10.1.34 don't save the
				// auto-increment value to disk, so on server restart it might reuse IDs from deleted
				// revisions. We can fix that with an insert with an explicit rev_id value, if necessary.

				$maxRevId = intval( $dbw->selectField( 'archive', 'MAX(ar_rev_id)', '', __METHOD__ ) );
				$table = 'archive';
				if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW ) ) {
					$maxRevId2 = intval( $dbw->selectField( 'slots', 'MAX(slot_revision_id)', '', __METHOD__ ) );
					if ( $maxRevId2 >= $maxRevId ) {
						$maxRevId = $maxRevId2;
						$table = 'slots';
					}
				}

				if ( $maxRevId >= $revisionRow['rev_id'] ) {
					$this->logger->debug(
						'__METHOD__: Inserted revision {revid} but {table} has revisions up to {maxrevid}.'
							. ' Trying to fix it.',
						[
							'revid' => $revisionRow['rev_id'],
							'table' => $table,
							'maxrevid' => $maxRevId,
						]
					);

					if ( !$dbw->lock( 'fix-for-T202032', __METHOD__ ) ) {
						throw new MWException( 'Failed to get database lock for T202032' );
					}
					$fname = __METHOD__;
					$dbw->onTransactionResolution(
						function ( $trigger, IDatabase $dbw ) use ( $fname ) {
							$dbw->unlock( 'fix-for-T202032', $fname );
						}
					);

					$dbw->delete( 'revision', [ 'rev_id' => $revisionRow['rev_id'] ], __METHOD__ );

					// The locking here is mostly to make MySQL bypass the REPEATABLE-READ transaction
					// isolation (weird MySQL "feature"). It does seem to block concurrent auto-incrementing
					// inserts too, though, at least on MariaDB 10.1.29.
					//
					// Don't try to lock `revision` in this way, it'll deadlock if there are concurrent
					// transactions in this code path thanks to the row lock from the original ->insert() above.
					//
					// And we have to use raw SQL to bypass the "aggregation used with a locking SELECT" warning
					// that's for non-MySQL DBs.
					$row1 = $dbw->query(
						$dbw->selectSQLText( 'archive', [ 'v' => "MAX(ar_rev_id)" ], '', __METHOD__ ) . ' FOR UPDATE'
					)->fetchObject();
					if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW ) ) {
						$row2 = $dbw->query(
							$dbw->selectSQLText( 'slots', [ 'v' => "MAX(slot_revision_id)" ], '', __METHOD__ )
								. ' FOR UPDATE'
						)->fetchObject();
					} else {
						$row2 = null;
					}
					$maxRevId = max(
						$maxRevId,
						$row1 ? intval( $row1->v ) : 0,
						$row2 ? intval( $row2->v ) : 0
					);

					// If we don't have SCHEMA_COMPAT_WRITE_NEW, all except the first of any concurrent
					// transactions will throw a duplicate key error here. It doesn't seem worth trying
					// to avoid that.
					$revisionRow['rev_id'] = $maxRevId + 1;
					$dbw->insert( 'revision', $revisionRow, __METHOD__ );
				}
			}
		}

		$commentCallback( $revisionRow['rev_id'] );
		$actorCallback( $revisionRow['rev_id'], $revisionRow );

		return $revisionRow;
	}

	/**
	 * @param IDatabase $dbw
	 * @param RevisionRecord $rev
	 * @param Title $title
	 * @param int $parentId
	 *
	 * @return array [ 0 => array $revisionRow, 1 => callable  ]
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function getBaseRevisionRow(
		IDatabase $dbw,
		RevisionRecord $rev,
		Title $title,
		$parentId
	) {
		// Record the edit in revisions
		$revisionRow = [
			'rev_page'       => $rev->getPageId(),
			'rev_parent_id'  => $parentId,
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_timestamp'  => $dbw->timestamp( $rev->getTimestamp() ),
			'rev_deleted'    => $rev->getVisibility(),
			'rev_len'        => $rev->getSize(),
			'rev_sha1'       => $rev->getSha1(),
		];

		if ( $rev->getId() !== null ) {
			// Needed to restore revisions with their original ID
			$revisionRow['rev_id'] = $rev->getId();
		}

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_OLD ) ) {
			// In non MCR mode this IF section will relate to the main slot
			$mainSlot = $rev->getSlot( SlotRecord::MAIN );
			$model = $mainSlot->getModel();
			$format = $mainSlot->getFormat();

			// MCR migration note: rev_content_model and rev_content_format will go away
			if ( $this->contentHandlerUseDB ) {
				$this->assertCrossWikiContentLoadingIsSafe();

				$defaultModel = ContentHandler::getDefaultModelFor( $title );
				$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

				$revisionRow['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
				$revisionRow['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
			}
		}

		return $revisionRow;
	}

	/**
	 * @param SlotRecord $slot
	 * @param Title $title
	 * @param array $blobHints See the BlobStore::XXX_HINT constants
	 *
	 * @throws MWException
	 * @return string the blob address
	 */
	private function storeContentBlob(
		SlotRecord $slot,
		Title $title,
		array $blobHints = []
	) {
		$content = $slot->getContent();
		$format = $content->getDefaultFormat();
		$model = $content->getModel();

		$this->checkContent( $content, $title, $slot->getRole() );

		return $this->blobStore->storeBlob(
			$content->serialize( $format ),
			// These hints "leak" some information from the higher abstraction layer to
			// low level storage to allow for optimization.
			array_merge(
				$blobHints,
				[
					BlobStore::DESIGNATION_HINT => 'page-content',
					BlobStore::ROLE_HINT => $slot->getRole(),
					BlobStore::SHA1_HINT => $slot->getSha1(),
					BlobStore::MODEL_HINT => $model,
					BlobStore::FORMAT_HINT => $format,
				]
			)
		);
	}

	/**
	 * @param SlotRecord $slot
	 * @param IDatabase $dbw
	 * @param int $revisionId
	 * @param int $contentId
	 */
	private function insertSlotRowOn( SlotRecord $slot, IDatabase $dbw, $revisionId, $contentId ) {
		$slotRow = [
			'slot_revision_id' => $revisionId,
			'slot_role_id' => $this->slotRoleStore->acquireId( $slot->getRole() ),
			'slot_content_id' => $contentId,
			// If the slot has a specific origin use that ID, otherwise use the ID of the revision
			// that we just inserted.
			'slot_origin' => $slot->hasOrigin() ? $slot->getOrigin() : $revisionId,
		];
		$dbw->insert( 'slots', $slotRow, __METHOD__ );
	}

	/**
	 * @param SlotRecord $slot
	 * @param IDatabase $dbw
	 * @param string $blobAddress
	 * @return int content row ID
	 */
	private function insertContentRowOn( SlotRecord $slot, IDatabase $dbw, $blobAddress ) {
		$contentRow = [
			'content_size' => $slot->getSize(),
			'content_sha1' => $slot->getSha1(),
			'content_model' => $this->contentModelStore->acquireId( $slot->getModel() ),
			'content_address' => $blobAddress,
		];
		$dbw->insert( 'content', $contentRow, __METHOD__ );
		return intval( $dbw->insertId() );
	}

	/**
	 * MCR migration note: this corresponds to Revision::checkContentModel
	 *
	 * @param Content $content
	 * @param Title $title
	 * @param string $role
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function checkContent( Content $content, Title $title, $role ) {
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

			$this->assertCrossWikiContentLoadingIsSafe();

			$roleHandler = $this->slotRoleRegistry->getRoleHandler( $role );
			$defaultModel = $roleHandler->getDefaultModel( $title );
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
	 * @note This method grabs a FOR UPDATE lock on the relevant row of the page table,
	 * to prevent a new revision from being inserted before the null revision has been written
	 * to the database.
	 *
	 * MCR migration note: this replaces Revision::newNullRevision
	 *
	 * @todo Introduce newFromParentRevision(). newNullRevision can then be based on that
	 * (or go away).
	 *
	 * @param IDatabase $dbw used for obtaining the lock on the page table row
	 * @param Title $title Title of the page to read from
	 * @param CommentStoreComment $comment RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User $user The user to attribute the revision to
	 *
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision(
		IDatabase $dbw,
		Title $title,
		CommentStoreComment $comment,
		$minor,
		User $user
	) {
		$this->checkDatabaseDomain( $dbw );

		$pageId = $title->getArticleID();

		// T51581: Lock the page table row to ensure no other process
		// is adding a revision to the page at the same time.
		// Avoid locking extra tables, compare T191892.
		$pageLatest = $dbw->selectField(
			'page',
			'page_latest',
			[ 'page_id' => $pageId ],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);

		if ( !$pageLatest ) {
			return null;
		}

		// Fetch the actual revision row from master, without locking all extra tables.
		$oldRevision = $this->loadRevisionFromConds(
			$dbw,
			[ 'rev_id' => intval( $pageLatest ) ],
			self::READ_LATEST,
			$title
		);

		if ( !$oldRevision ) {
			$msg = "Failed to load latest revision ID $pageLatest of page ID $pageId.";
			$this->logger->error(
				$msg,
				[ 'exception' => new RuntimeException( $msg ) ]
			);
			return null;
		}

		// Construct the new revision
		$timestamp = wfTimestampNow(); // TODO: use a callback, so we can override it for testing.
		$newRevision = MutableRevisionRecord::newFromParentRevision( $oldRevision );

		$newRevision->setComment( $comment );
		$newRevision->setUser( $user );
		$newRevision->setTimestamp( $timestamp );
		$newRevision->setMinorEdit( $minor );

		return $newRevision;
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
		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = $this->getDBConnectionRef( $dbType );

		$userIdentity = $rev->getUser( RevisionRecord::RAW );

		if ( !$userIdentity ) {
			// If the revision has no user identity, chances are it never went
			// into the database, and doesn't have an RC entry.
			return null;
		}

		// TODO: Select by rc_this_oldid alone - but as of Nov 2017, there is no index on that!
		$actorWhere = $this->actorMigration->getWhere( $db, 'rc_user', $rev->getUser(), false );
		$rc = RecentChange::newFromConds(
			[
				$actorWhere['conds'],
				'rc_timestamp' => $db->timestamp( $rev->getTimestamp() ),
				'rc_this_oldid' => $rev->getId()
			],
			__METHOD__,
			$dbType
		);

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
		$mainSlotRow->role_name = SlotRecord::MAIN;
		$mainSlotRow->model_name = null;
		$mainSlotRow->slot_revision_id = null;
		$mainSlotRow->slot_content_id = null;
		$mainSlotRow->content_address = null;

		$content = null;
		$blobData = null;
		$blobFlags = null;

		if ( is_object( $row ) ) {
			if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_NEW ) ) {
				// Don't emulate from a row when using the new schema.
				// Emulating from an array is still OK.
				throw new LogicException( 'Can\'t emulate the main slot when using MCR schema.' );
			}

			// archive row
			if ( !isset( $row->rev_id ) && ( isset( $row->ar_user ) || isset( $row->ar_actor ) ) ) {
				$row = $this->mapArchiveFields( $row );
			}

			if ( isset( $row->rev_text_id ) && $row->rev_text_id > 0 ) {
				$mainSlotRow->content_address = SqlBlobStore::makeAddressFromTextId(
					$row->rev_text_id
				);
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
				$blobFlags = $row->old_flags ?? '';
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

			if ( isset( $row->rev_text_id ) && intval( $row->rev_text_id ) > 0 ) {
				// Overwritten below for SCHEMA_COMPAT_WRITE_NEW
				$mainSlotRow->slot_content_id
					= $this->emulateContentId( intval( $row->rev_text_id ) );
			}
		} elseif ( is_array( $row ) ) {
			$mainSlotRow->slot_revision_id = isset( $row['id'] ) ? intval( $row['id'] ) : null;

			$mainSlotRow->slot_origin = isset( $row['slot_origin'] )
				? intval( $row['slot_origin'] )
				: null;
			$mainSlotRow->content_address = isset( $row['text_id'] )
				? SqlBlobStore::makeAddressFromTextId( intval( $row['text_id'] ) )
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

			if ( isset( $row['text_id'] ) && intval( $row['text_id'] ) > 0 ) {
				// Overwritten below for SCHEMA_COMPAT_WRITE_NEW
				$mainSlotRow->slot_content_id
					= $this->emulateContentId( intval( $row['text_id'] ) );
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
				$this->assertCrossWikiContentLoadingIsSafe();

				return $this->slotRoleRegistry->getRoleHandler( $slot->getRole() )
					->getDefaultModel( $title );
			};
		}

		if ( !$content ) {
			// XXX: We should perhaps fail if $blobData is null and $mainSlotRow->content_address
			// is missing, but "empty revisions" with no content are used in some edge cases.

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

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW ) ) {
			// NOTE: this callback will be looped through RevisionSlot::newInherited(), allowing
			// the inherited slot to have the same content_id as the original slot. In that case,
			// $slot will be the inherited slot, while $mainSlotRow still refers to the original slot.
			$mainSlotRow->slot_content_id =
				function ( SlotRecord $slot ) use ( $queryFlags, $mainSlotRow ) {
					$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
					return $this->findSlotContentId( $db, $mainSlotRow->slot_revision_id, SlotRecord::MAIN );
				};
		}

		return new SlotRecord( $mainSlotRow, $content );
	}

	/**
	 * Provides a content ID to use with emulated SlotRecords in SCHEMA_COMPAT_OLD mode,
	 * based on the revision's text ID (rev_text_id or ar_text_id, respectively).
	 * Note that in SCHEMA_COMPAT_WRITE_BOTH, a callback to findSlotContentId() should be used
	 * instead, since in that mode, some revision rows may already have a real content ID,
	 * while other's don't - and for the ones that don't, we should indicate that it
	 * is missing and cause SlotRecords::hasContentId() to return false.
	 *
	 * @param int $textId
	 * @return int The emulated content ID
	 */
	private function emulateContentId( $textId ) {
		// Return a negative number to ensure the ID is distinct from any real content IDs
		// that will be assigned in SCHEMA_COMPAT_WRITE_NEW mode and read in SCHEMA_COMPAT_READ_NEW
		// mode.
		return -$textId;
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
	 * @throws RevisionAccessException
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
		// TODO should not require Title in future (T206498)
		$title = Title::newFromLinkTarget( $linkTarget );
		$conds = [
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getDBkey()
		];
		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to master if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags, $title );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to master. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
			$db = $this->getDBConnectionRefForQueryFlags( $flags );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags, $title );

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
			$db = $this->getDBConnectionRefForQueryFlags( $flags );

			$conds[] = 'rev_id=page_latest';
			$rev = $this->loadRevisionFromConds( $db, $conds, $flags );

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
		$db = $this->getDBConnectionRef( DB_REPLICA );
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
	 * @param int $revId The revision to load slots for.
	 * @param int $queryFlags
	 * @param Title $title
	 *
	 * @return SlotRecord[]
	 */
	private function loadSlotRecords( $revId, $queryFlags, Title $title ) {
		$revQuery = self::getSlotsQueryInfo( [ 'content' ] );

		list( $dbMode, $dbOptions ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		$db = $this->getDBConnectionRef( $dbMode );

		$res = $db->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[
				'slot_revision_id' => $revId,
			],
			__METHOD__,
			$dbOptions,
			$revQuery['joins']
		);

		$slots = $this->constructSlotRecords( $revId, $res, $queryFlags, $title );

		return $slots;
	}

	/**
	 * Factory method for SlotRecords based on known slot rows.
	 *
	 * @param int $revId The revision to load slots for.
	 * @param object[]|IResultWrapper $slotRows
	 * @param int $queryFlags
	 * @param Title $title
	 * @param array|null $slotContents a map from blobAddress to slot
	 * 	content blob or Content object.
	 *
	 * @return SlotRecord[]
	 */
	private function constructSlotRecords(
		$revId,
		$slotRows,
		$queryFlags,
		Title $title,
		$slotContents = null
	) {
		$slots = [];

		foreach ( $slotRows as $row ) {
			// Resolve role names and model names from in-memory cache, if they were not joined in.
			if ( !isset( $row->role_name ) ) {
				$row->role_name = $this->slotRoleStore->getName( (int)$row->slot_role_id );
			}

			if ( !isset( $row->model_name ) ) {
				if ( isset( $row->content_model ) ) {
					$row->model_name = $this->contentModelStore->getName( (int)$row->content_model );
				} else {
					// We may get here if $row->model_name is set but null, perhaps because it
					// came from rev_content_model, which is NULL for the default model.
					$slotRoleHandler = $this->slotRoleRegistry->getRoleHandler( $row->role_name );
					$row->model_name = $slotRoleHandler->getDefaultModel( $title );
				}
			}

			if ( !isset( $row->content_id ) && isset( $row->rev_text_id ) ) {
				$row->slot_content_id
					= $this->emulateContentId( intval( $row->rev_text_id ) );
			}

			// We may have a fake blob_data field from getSlotRowsForBatch(), use it!
			if ( isset( $row->blob_data ) ) {
				$slotContents[$row->content_address] = $row->blob_data;
			}

			$contentCallback = function ( SlotRecord $slot ) use ( $slotContents, $queryFlags ) {
				$blob = null;
				if ( isset( $slotContents[$slot->getAddress()] ) ) {
					$blob = $slotContents[$slot->getAddress()];
					if ( $blob instanceof Content ) {
						return $blob;
					}
				}
				return $this->loadSlotContent( $slot, $blob, null, null, $queryFlags );
			};

			$slots[$row->role_name] = new SlotRecord( $row, $contentCallback );
		}

		if ( !isset( $slots[SlotRecord::MAIN] ) ) {
			throw new RevisionAccessException(
				'Main slot of revision ' . $revId . ' not found in database!'
			);
		}

		return $slots;
	}

	/**
	 * Factory method for RevisionSlots based on a revision ID.
	 *
	 * @note If other code has a need to construct RevisionSlots objects, this should be made
	 * public, since RevisionSlots instances should not be constructed directly.
	 *
	 * @param int $revId
	 * @param object $revisionRow
	 * @param object[]|null $slotRows
	 * @param int $queryFlags
	 * @param Title $title
	 *
	 * @return RevisionSlots
	 * @throws MWException
	 */
	private function newRevisionSlots(
		$revId,
		$revisionRow,
		$slotRows,
		$queryFlags,
		Title $title
	) {
		if ( $slotRows ) {
			$slots = new RevisionSlots(
				$this->constructSlotRecords( $revId, $slotRows, $queryFlags, $title )
			);
		} elseif ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_NEW ) ) {
			$mainSlot = $this->emulateMainSlot_1_29( $revisionRow, $queryFlags, $title );
			// @phan-suppress-next-line PhanTypeInvalidCallableArraySize false positive
			$slots = new RevisionSlots( [ SlotRecord::MAIN => $mainSlot ] );
		} else {
			// XXX: do we need the same kind of caching here
			// that getKnownCurrentRevision uses (if $revId == page_latest?)

			$slots = new RevisionSlots( function () use( $revId, $queryFlags, $title ) {
				return $this->loadSlotRecords( $revId, $queryFlags, $title );
			} );
		}

		return $slots;
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
				$row->ar_user ?? null,
				$row->ar_user_text ?? null,
				$row->ar_actor ?? null,
				$this->dbDomain
			);
		} catch ( InvalidArgumentException $ex ) {
			wfWarn( __METHOD__ . ': ' . $title->getPrefixedDBkey() . ': ' . $ex->getMessage() );
			$user = new UserIdentityValue( 0, 'Unknown user', 0 );
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		// Legacy because $row may have come from self::selectFields()
		$comment = $this->commentStore->getCommentLegacy( $db, 'ar_comment', $row, true );

		$slots = $this->newRevisionSlots( $row->ar_rev_id, $row, null, $queryFlags, $title );

		return new RevisionArchiveRecord( $title, $user, $comment, $row, $slots, $this->dbDomain );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row A database row generated from a query based on getQueryInfo()
	 * @param int $queryFlags
	 * @param Title|null $title
	 * @param bool $fromCache if true, the returned RevisionRecord will ensure that no stale
	 *   data is returned from getters, by querying the database as needed
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow(
		$row,
		$queryFlags = 0,
		Title $title = null,
		$fromCache = false
	) {
		return $this->newRevisionFromRowAndSlots( $row, null, $queryFlags, $title, $fromCache );
	}

	/**
	 * @param object $row A database row generated from a query based on getQueryInfo()
	 * @param null|object[]|RevisionSlots $slots
	 * 	- Database rows generated from a query based on getSlotsQueryInfo
	 * 	  with the 'content' flag set. Or
	 *  - RevisionSlots instance
	 * @param int $queryFlags
	 * @param Title|null $title
	 * @param bool $fromCache if true, the returned RevisionRecord will ensure that no stale
	 *   data is returned from getters, by querying the database as needed
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 */
	public function newRevisionFromRowAndSlots(
		$row,
		$slots,
		$queryFlags = 0,
		Title $title = null,
		$fromCache = false
	) {
		Assert::parameterType( 'object', $row, '$row' );

		if ( !$title ) {
			$pageId = $row->rev_page ?? 0; // XXX: also check page_id?
			$revId = $row->rev_id ?? 0;

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
				$row->rev_user ?? null,
				$row->rev_user_text ?? null,
				$row->rev_actor ?? null,
				$this->dbDomain
			);
		} catch ( InvalidArgumentException $ex ) {
			wfWarn( __METHOD__ . ': ' . $title->getPrefixedDBkey() . ': ' . $ex->getMessage() );
			$user = new UserIdentityValue( 0, 'Unknown user', 0 );
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		// Legacy because $row may have come from self::selectFields()
		$comment = $this->commentStore->getCommentLegacy( $db, 'rev_comment', $row, true );

		if ( !( $slots instanceof RevisionSlots ) ) {
			$slots = $this->newRevisionSlots( $row->rev_id, $row, $slots, $queryFlags, $title );
		}

		// If this is a cached row, instantiate a cache-aware revision class to avoid stale data.
		if ( $fromCache ) {
			$rev = new RevisionStoreCacheRecord(
				function ( $revId ) use ( $queryFlags ) {
					$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
					return $this->fetchRevisionRowFromConds(
						$db,
						[ 'rev_id' => intval( $revId ) ]
					);
				},
				$title, $user, $comment, $row, $slots, $this->dbDomain
			);
		} else {
			$rev = new RevisionStoreRecord(
				$title, $user, $comment, $row, $slots, $this->dbDomain );
		}
		return $rev;
	}

	/**
	 * Construct a RevisionRecord instance for each row in $rows,
	 * and return them as an associative array indexed by revision ID.
	 * @param Traversable|array $rows the rows to construct revision records from
	 * @param array $options Supports the following options:
	 *               'slots' - whether metadata about revision slots should be
	 *               loaded immediately. Supports falsy or truthy value as well
	 *               as an explicit list of slot role names. The main slot will
	 *               always be loaded.
	 *               'content'- whether the actual content of the slots should be
	 *               preloaded.
	 * @param int $queryFlags
	 * @param Title|null $title The title to which all the revision rows belong, if there
	 *        is such a title and the caller has it handy, so we don't have to look it up again.
	 *        If this parameter is given and any of the rows has a rev_page_id that is different
	 *        from $title->getArticleID(), an InvalidArgumentException is thrown.
	 *
	 * @return StatusValue a status with a RevisionRecord[] of successfully fetched revisions
	 * 					   and an array of errors for the revisions failed to fetch.
	 */
	public function newRevisionsFromBatch(
		$rows,
		array $options = [],
		$queryFlags = 0,
		Title $title = null
	) {
		$result = new StatusValue();

		$rowsByRevId = [];
		$pageIdsToFetchTitles = [];
		$titlesByPageId = [];
		foreach ( $rows as $row ) {
			if ( isset( $rowsByRevId[$row->rev_id] ) ) {
				$result->warning(
					'internalerror',
					"Duplicate rows in newRevisionsFromBatch, rev_id {$row->rev_id}"
				);
			}
			if ( $title && $row->rev_page != $title->getArticleID() ) {
				throw new InvalidArgumentException(
					"Revision {$row->rev_id} doesn't belong to page {$title->getArticleID()}"
				);
			} elseif ( !$title && !isset( $titlesByPageId[ $row->rev_page ] ) ) {
				if ( isset( $row->page_namespace ) && isset( $row->page_title ) &&
					// This should not happen, but just in case we don't have a page_id
					// set or it doesn't match rev_page, let's fetch the title again.
					isset( $row->page_id ) && $row->rev_page === $row->page_id
				) {
					$titlesByPageId[ $row->rev_page ] = Title::newFromRow( $row );
				} else {
					$pageIdsToFetchTitles[] = $row->rev_page;
				}
			}
			$rowsByRevId[$row->rev_id] = $row;
		}

		if ( empty( $rowsByRevId ) ) {
			$result->setResult( true, [] );
			return $result;
		}

		// If the title is not supplied, batch-fetch Title objects.
		if ( $title ) {
			$titlesByPageId[$title->getArticleID()] = $title;
		} elseif ( !empty( $pageIdsToFetchTitles ) ) {
			$pageIdsToFetchTitles = array_unique( $pageIdsToFetchTitles );
			foreach ( Title::newFromIDs( $pageIdsToFetchTitles ) as $t ) {
				$titlesByPageId[$t->getArticleID()] = $t;
			}
		}

		if ( !isset( $options['slots'] ) || $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
			$result->setResult( true,
				array_map( function ( $row ) use ( $queryFlags, $titlesByPageId, $result ) {
					try {
						return $this->newRevisionFromRow(
							$row,
							$queryFlags,
							$titlesByPageId[$row->rev_page]
						);
					} catch ( MWException $e ) {
						$result->warning( 'internalerror', $e->getMessage() );
						return null;
					}
				}, $rowsByRevId )
			);
			return $result;
		}

		$slotRowOptions = [
			'slots' => $options['slots'] ?? true,
			'blobs' => $options['content'] ?? false,
		];

		if ( is_array( $slotRowOptions['slots'] )
			&& !in_array( SlotRecord::MAIN, $slotRowOptions['slots'] )
		) {
			// Make sure the main slot is always loaded, RevisionRecord requires this.
			$slotRowOptions['slots'][] = SlotRecord::MAIN;
		}

		$slotRowsStatus = $this->getSlotRowsForBatch( $rowsByRevId, $slotRowOptions, $queryFlags );

		$result->merge( $slotRowsStatus );
		$slotRowsByRevId = $slotRowsStatus->getValue();

		$result->setResult( true, array_map( function ( $row ) use
			( $slotRowsByRevId, $queryFlags, $titlesByPageId, $result ) {
				if ( !isset( $slotRowsByRevId[$row->rev_id] ) ) {
					$result->warning(
						'internalerror',
						"Couldn't find slots for rev {$row->rev_id}"
					);
					return null;
				}
				try {
					return $this->newRevisionFromRowAndSlots(
						$row,
						new RevisionSlots(
							$this->constructSlotRecords(
								$row->rev_id,
								$slotRowsByRevId[$row->rev_id],
								$queryFlags,
								$titlesByPageId[$row->rev_page]
							)
						),
						$queryFlags,
						$titlesByPageId[$row->rev_page]
					);
				} catch ( MWException $e ) {
					$result->warning( 'internalerror', $e->getMessage() );
					return null;
				}
		}, $rowsByRevId ) );
		return $result;
	}

	/**
	 * Gets the slot rows associated with a batch of revisions.
	 * The serialized content of each slot can be included by setting the 'blobs' option.
	 * Callers are responsible for unserializing and interpreting the content blobs
	 * based on the model_name and role_name fields.
	 *
	 * @param Traversable|array $rowsOrIds list of revision ids, or revision rows from a db query.
	 * @param array $options Supports the following options:
	 *               'slots' - a list of slot role names to fetch. If omitted or true or null,
	 *                         all slots are fetched
	 *               'blobs'- whether the serialized content of each slot should be loaded.
	 *                        If true, the serialiezd content will be present in the slot row
	 *                        in the blob_data field.
	 * @param int $queryFlags
	 *
	 * @return StatusValue a status containing, if isOK() returns true, a two-level nested
	 *         associative array, mapping from revision ID to an associative array that maps from
	 *         role name to a database row object. The database row object will contain the fields
	 *         defined by getSlotQueryInfo() with the 'content' flag set, plus the blob_data field
	 *         if the 'blobs' is set in $options. The model_name and role_name fields will also be
	 *         set.
	 */
	private function getSlotRowsForBatch(
		$rowsOrIds,
		array $options = [],
		$queryFlags = 0
	) {
		$readNew = $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_NEW );
		$result = new StatusValue();

		$revIds = [];
		foreach ( $rowsOrIds as $row ) {
			$revIds[] = is_object( $row ) ? (int)$row->rev_id : (int)$row;
		}

		// Nothing to do.
		// Note that $rowsOrIds may not be "empty" even if $revIds is, e.g. if it's a ResultWrapper.
		if ( empty( $revIds ) ) {
			$result->setResult( true, [] );
			return $result;
		}

		// We need to set the `content` flag to join in content meta-data
		$slotQueryInfo = self::getSlotsQueryInfo( [ 'content' ] );
		$revIdField = $slotQueryInfo['keys']['rev_id'];
		$slotQueryConds = [ $revIdField => $revIds ];

		if ( $readNew && isset( $options['slots'] ) && is_array( $options['slots'] ) ) {
			if ( empty( $options['slots'] ) ) {
				// Degenerate case: return no slots for each revision.
				$result->setResult( true, array_fill_keys( $revIds, [] ) );
				return $result;
			}

			$roleIdField = $slotQueryInfo['keys']['role_id'];
			$slotQueryConds[$roleIdField] = array_map( function ( $slot_name ) {
				return $this->slotRoleStore->getId( $slot_name );
			}, $options['slots'] );
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$slotRows = $db->select(
			$slotQueryInfo['tables'],
			$slotQueryInfo['fields'],
			$slotQueryConds,
			__METHOD__,
			[],
			$slotQueryInfo['joins']
		);

		$slotContents = null;
		if ( $options['blobs'] ?? false ) {
			$blobAddresses = [];
			foreach ( $slotRows as $slotRow ) {
				$blobAddresses[] = $slotRow->content_address;
			}
			$slotContentFetchStatus = $this->blobStore
				->getBlobBatch( $blobAddresses, $queryFlags );
			foreach ( $slotContentFetchStatus->getErrors() as $error ) {
				$result->warning( $error['message'], ...$error['params'] );
			}
			$slotContents = $slotContentFetchStatus->getValue();
		}

		$slotRowsByRevId = [];
		foreach ( $slotRows as $slotRow ) {
			if ( $slotContents === null ) {
				// nothing to do
			} elseif ( isset( $slotContents[$slotRow->content_address] ) ) {
				$slotRow->blob_data = $slotContents[$slotRow->content_address];
			} else {
				$result->warning(
					'internalerror',
					"Couldn't find blob data for rev {$slotRow->slot_revision_id}"
				);
				$slotRow->blob_data = null;
			}

			// conditional needed for SCHEMA_COMPAT_READ_OLD
			if ( !isset( $slotRow->role_name ) && isset( $slotRow->slot_role_id ) ) {
				$slotRow->role_name = $this->slotRoleStore->getName( (int)$slotRow->slot_role_id );
			}

			// conditional needed for SCHEMA_COMPAT_READ_OLD
			if ( !isset( $slotRow->model_name ) && isset( $slotRow->content_model ) ) {
				$slotRow->model_name = $this->contentModelStore->getName( (int)$slotRow->content_model );
			}

			$slotRowsByRevId[$slotRow->slot_revision_id][$slotRow->role_name] = $slotRow;
		}

		$result->setResult( true, $slotRowsByRevId );
		return $result;
	}

	/**
	 * Gets raw (serialized) content blobs for the given set of revisions.
	 * Callers are responsible for unserializing and interpreting the content blobs
	 * based on the model_name field and the slot role.
	 *
	 * This method is intended for bulk operations in maintenance scripts.
	 * It may be chosen over newRevisionsFromBatch by code that are only interested
	 * in raw content, as opposed to meta data. Code that needs to access meta data of revisions,
	 * slots, or content objects should use newRevisionsFromBatch() instead.
	 *
	 * @param Traversable|array $rowsOrIds list of revision ids, or revision rows from a db query.
	 * @param array|null $slots the role names for which to get slots.
	 * @param int $queryFlags
	 *
	 * @return StatusValue a status containing, if isOK() returns true, a two-level nested
	 *         associative array, mapping from revision ID to an associative array that maps from
	 *         role name to an anonymous object object containing two fields:
	 *         - model_name: the name of the content's model
	 *         - blob_data: serialized content data
	 */
	public function getContentBlobsForBatch(
		$rowsOrIds,
		$slots = null,
		$queryFlags = 0
	) {
		$result = $this->getSlotRowsForBatch(
			$rowsOrIds,
			[ 'slots' => $slots, 'blobs' => true ],
			$queryFlags
		);

		if ( $result->isOK() ) {
			// strip out all internal meta data that we don't want to expose
			foreach ( $result->value as $revId => $rowsByRole ) {
				foreach ( $rowsByRole as $role => $slotRow ) {
					if ( is_array( $slots ) && !in_array( $role, $slots ) ) {
						// In SCHEMA_COMPAT_READ_OLD mode we may get the main slot even
						// if we didn't ask for it.
						unset( $result->value[$revId][$role] );
						continue;
					}

					$result->value[$revId][$role] = (object)[
						'blob_data' => $slotRow->blob_data,
						'model_name' => $slotRow->model_name,
					];
				}
			}
		}

		return $result;
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
			$pageId = $fields['page'] ?? 0;
			$revId = $fields['id'] ?? 0;

			$title = $this->getTitle( $pageId, $revId, $queryFlags );
		}

		if ( !isset( $fields['page'] ) ) {
			$fields['page'] = $title->getArticleID( $queryFlags );
		}

		// if we have a content object, use it to set the model and type
		if ( !empty( $fields['content'] ) && !( $fields['content'] instanceof Content )
			&& !is_array( $fields['content'] )
		) {
			throw new MWException(
				'content field must contain a Content object or an array of Content objects.'
			);
		}

		if ( !empty( $fields['text_id'] ) ) {
			if ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
				throw new MWException( "The text_id field is only available in the pre-MCR schema" );
			}

			if ( !empty( $fields['content'] ) ) {
				throw new MWException(
					"Text already stored in external store (id {$fields['text_id']}), " .
					"can't specify content object"
				);
			}
		}

		if (
			isset( $fields['comment'] )
			&& !( $fields['comment'] instanceof CommentStoreComment )
		) {
			$commentData = $fields['comment_data'] ?? null;

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

		$revision = new MutableRevisionRecord( $title, $this->dbDomain );
		$this->initializeMutableRevisionFromArray( $revision, $fields );

		if ( isset( $fields['content'] ) && is_array( $fields['content'] ) ) {
			// @phan-suppress-next-line PhanTypeNoPropertiesForeach
			foreach ( $fields['content'] as $role => $content ) {
				$revision->setContent( $role, $content );
			}
		} else {
			$mainSlot = $this->emulateMainSlot_1_29( $fields, $queryFlags, $title );
			$revision->setSlot( $mainSlot );
		}

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

		// If a user is passed in, use it if possible. We cannot use a user from a
		// remote wiki with unsuppressed ids, due to issues described in T222212.
		if ( isset( $fields['user'] ) &&
			( $fields['user'] instanceof UserIdentity ) &&
			( $this->dbDomain === false ||
				( !$fields['user']->getId() && !$fields['user']->getActorId() ) )
		) {
			$user = $fields['user'];
		} else {
			try {
				$user = User::newFromAnyId(
					$fields['user'] ?? null,
					$fields['user_text'] ?? null,
					$fields['actor'] ?? null,
					$this->dbDomain
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
	 * @param Title|null $title
	 *
	 * @return RevisionRecord|null
	 */
	private function newRevisionFromConds( $conditions, $flags = 0, Title $title = null ) {
		$db = $this->getDBConnectionRefForQueryFlags( $flags );
		$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $title );

		$lb = $this->getDBLoadBalancer();

		// Make sure new pending/committed revision are visibile later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & self::READ_LATEST )
			&& $lb->hasStreamingReplicaServers()
			&& $lb->hasOrMadeRecentMasterChanges()
		) {
			$flags = self::READ_LATEST;
			$dbw = $this->getDBConnectionRef( DB_MASTER );
			$rev = $this->loadRevisionFromConds( $dbw, $conditions, $flags, $title );
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
	 * @param Title|null $title
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
	private function checkDatabaseDomain( IDatabase $db ) {
		$dbDomain = $db->getDomainID();
		$storeDomain = $this->loadBalancer->resolveDomainID( $this->dbDomain );
		if ( $dbDomain === $storeDomain ) {
			return;
		}

		throw new MWException( "DB connection domain '$dbDomain' does not match '$storeDomain'" );
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
		$this->checkDatabaseDomain( $db );

		$revQuery = $this->getQueryInfo( [ 'page', 'user' ] );
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
	 * Finds the ID of a content row for a given revision and slot role.
	 * This can be used to re-use content rows even while the content ID
	 * is still missing from SlotRecords, when writing to both the old and
	 * the new schema during MCR schema migration.
	 *
	 * @todo remove after MCR schema migration is complete.
	 *
	 * @param IDatabase $db
	 * @param int $revId
	 * @param string $role
	 *
	 * @return int|null
	 */
	private function findSlotContentId( IDatabase $db, $revId, $role ) {
		if ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_NEW ) ) {
			return null;
		}

		try {
			$roleId = $this->slotRoleStore->getId( $role );
			$conditions = [
				'slot_revision_id' => $revId,
				'slot_role_id' => $roleId,
			];

			$contentId = $db->selectField( 'slots', 'slot_content_id', $conditions, __METHOD__ );

			return $contentId ?: null;
		} catch ( NameTableAccessException $ex ) {
			// If the role is missing from the slot_roles table,
			// the corresponding row in slots cannot exist.
			return null;
		}
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionStoreRecord object.
	 *
	 * MCR migration note: this replaces Revision::getQueryInfo
	 *
	 * If the format of fields returned changes in any way then the cache key provided by
	 * self::getRevisionRowCacheKey should be updated.
	 *
	 * @since 1.31
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *  - 'text': Join with the text table, and select fields to load page text. This
	 *    option is deprecated in MW 1.32 when the MCR migration flag SCHEMA_COMPAT_WRITE_NEW
	 *    is set, and disallowed when SCHEMA_COMPAT_READ_OLD is not set.
	 *
	 * @return array With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
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

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
			$ret['fields'][] = 'rev_text_id';

			if ( $this->contentHandlerUseDB ) {
				$ret['fields'][] = 'rev_content_format';
				$ret['fields'][] = 'rev_content_model';
			}
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
			$ret['joins']['page'] = [ 'JOIN', [ 'page_id = rev_page' ] ];
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
			if ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_WRITE_OLD ) ) {
				throw new InvalidArgumentException( 'text table can no longer be joined directly' );
			} elseif ( !$this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
				// NOTE: even when this class is set to not read from the old schema, callers
				// should still be able to join against the text table, as long as we are still
				// writing the old schema for compatibility.
				// TODO: This should trigger a deprecation warning eventually (T200918), but not
				// before all known usages are removed (see T198341 and T201164).
				// wfDeprecated( __METHOD__ . ' with `text` option', '1.32' );
			}

			$ret['tables'][] = 'text';
			$ret['fields'] = array_merge( $ret['fields'], [
				'old_text',
				'old_flags'
			] );
			$ret['joins']['text'] = [ 'JOIN', [ 'rev_text_id=old_id' ] ];
		}

		return $ret;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new SlotRecord.
	 *
	 * @since 1.32
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'content': Join with the content table, and select content meta-data fields
	 *  - 'model': Join with the content_models table, and select the model_name field.
	 *             Only applicable if 'content' is also set.
	 *  - 'role': Join with the slot_roles table, and select the role_name field
	 *
	 * @return array With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *  - keys: (associative array) to look up fields to match against.
	 *          In particular, the field that can be used to find slots by rev_id
	 *          can be found in ['keys']['rev_id'].
	 */
	public function getSlotsQueryInfo( $options = [] ) {
		$ret = [
			'tables' => [],
			'fields' => [],
			'joins'  => [],
			'keys'  => [],
		];

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
			$db = $this->getDBConnectionRef( DB_REPLICA );
			$ret['keys']['rev_id'] = 'rev_id';

			$ret['tables'][] = 'revision';

			$ret['fields']['slot_revision_id'] = 'rev_id';
			$ret['fields']['slot_content_id'] = 'NULL';
			$ret['fields']['slot_origin'] = 'rev_id';
			$ret['fields']['role_name'] = $db->addQuotes( SlotRecord::MAIN );

			if ( in_array( 'content', $options, true ) ) {
				$ret['fields']['content_size'] = 'rev_len';
				$ret['fields']['content_sha1'] = 'rev_sha1';
				$ret['fields']['content_address']
					= $db->buildConcat( [ $db->addQuotes( 'tt:' ), 'rev_text_id' ] );

				// Allow the content_id field to be emulated later
				$ret['fields']['rev_text_id'] = 'rev_text_id';

				if ( $this->contentHandlerUseDB ) {
					$ret['fields']['model_name'] = 'rev_content_model';
				} else {
					$ret['fields']['model_name'] = 'NULL';
				}
			}
		} else {
			$ret['keys']['rev_id'] = 'slot_revision_id';
			$ret['keys']['role_id'] = 'slot_role_id';

			$ret['tables'][] = 'slots';
			$ret['fields'] = array_merge( $ret['fields'], [
				'slot_revision_id',
				'slot_content_id',
				'slot_origin',
				'slot_role_id',
			] );

			if ( in_array( 'role', $options, true ) ) {
				// Use left join to attach role name, so we still find the revision row even
				// if the role name is missing. This triggers a more obvious failure mode.
				$ret['tables'][] = 'slot_roles';
				$ret['joins']['slot_roles'] = [ 'LEFT JOIN', [ 'slot_role_id = role_id' ] ];
				$ret['fields'][] = 'role_name';
			}

			if ( in_array( 'content', $options, true ) ) {
				$ret['keys']['model_id'] = 'content_model';

				$ret['tables'][] = 'content';
				$ret['fields'] = array_merge( $ret['fields'], [
					'content_size',
					'content_sha1',
					'content_address',
					'content_model',
				] );
				$ret['joins']['content'] = [ 'JOIN', [ 'slot_content_id = content_id' ] ];

				if ( in_array( 'model', $options, true ) ) {
					// Use left join to attach model name, so we still find the revision row even
					// if the model name is missing. This triggers a more obvious failure mode.
					$ret['tables'][] = 'content_models';
					$ret['joins']['content_models'] = [ 'LEFT JOIN', [ 'content_model = model_id' ] ];
					$ret['fields'][] = 'model_name';
				}

			}
		}

		return $ret;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionArchiveRecord object.
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
					'ar_timestamp',
					'ar_minor_edit',
					'ar_deleted',
					'ar_len',
					'ar_parent_id',
					'ar_sha1',
				] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
		];

		if ( $this->hasMcrSchemaFlags( SCHEMA_COMPAT_READ_OLD ) ) {
			$ret['fields'][] = 'ar_text_id';

			if ( $this->contentHandlerUseDB ) {
				$ret['fields'][] = 'ar_content_format';
				$ret['fields'][] = 'ar_content_model';
			}
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
		return $this->listRevisionSizes( $this->getDBConnectionRef( DB_REPLICA ), $revIds );
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
		$this->checkDatabaseDomain( $db );

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
	 * Implementation of getPreviousRevision and getNextRevision.
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags
	 * @param string $dir 'next' or 'prev'
	 * @return RevisionRecord|null
	 */
	private function getRelativeRevision( RevisionRecord $rev, $flags, $dir ) {
		$op = $dir === 'next' ? '>' : '<';
		$sort = $dir === 'next' ? 'ASC' : 'DESC';

		if ( !$rev->getId() || !$rev->getPageId() ) {
			// revision is unsaved or otherwise incomplete
			return null;
		}

		if ( $rev instanceof RevisionArchiveRecord ) {
			// revision is deleted, so it's not part of the page history
			return null;
		}

		list( $dbType, ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = $this->getDBConnectionRef( $dbType, [ 'contributions' ] );

		$ts = $this->getTimestampFromId( $rev->getId(), $flags );
		if ( $ts === false ) {
			// XXX Should this be moved into getTimestampFromId?
			$ts = $db->selectField( 'archive', 'ar_timestamp',
				[ 'ar_rev_id' => $rev->getId() ], __METHOD__ );
			if ( $ts === false ) {
				// XXX Is this reachable? How can we have a page id but no timestamp?
				return null;
			}
		}
		$ts = $db->addQuotes( $db->timestamp( $ts ) );

		$revId = $db->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $rev->getPageId(),
				"rev_timestamp $op $ts OR (rev_timestamp = $ts AND rev_id $op {$rev->getId()})"
			],
			__METHOD__,
			[
				'ORDER BY' => "rev_timestamp $sort, rev_id $sort",
				'IGNORE INDEX' => 'rev_timestamp', // Probably needed for T159319
			]
		);

		if ( $revId === false ) {
			return null;
		}

		return $this->getRevisionById( intval( $revId ) );
	}

	/**
	 * Get the revision before $rev in the page's history, if any.
	 * Will return null for the first revision but also for deleted or unsaved revisions.
	 *
	 * MCR migration note: this replaces Revision::getPrevious
	 *
	 * @see Title::getPreviousRevisionID
	 * @see PageArchive::getPreviousRevision
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev, $flags = 0 ) {
		if ( $flags instanceof Title ) {
			// Old calling convention, we don't use Title here anymore
			wfDeprecated( __METHOD__ . ' with Title', '1.34' );
			$flags = 0;
		}

		return $this->getRelativeRevision( $rev, $flags, 'prev' );
	}

	/**
	 * Get the revision after $rev in the page's history, if any.
	 * Will return null for the latest revision but also for deleted or unsaved revisions.
	 *
	 * MCR migration note: this replaces Revision::getNext
	 *
	 * @see Title::getNextRevisionID
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the master
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev, $flags = 0 ) {
		if ( $flags instanceof Title ) {
			// Old calling convention, we don't use Title here anymore
			wfDeprecated( __METHOD__ . ' with Title', '1.34' );
			$flags = 0;
		}

		return $this->getRelativeRevision( $rev, $flags, 'next' );
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
		$this->checkDatabaseDomain( $db );

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
	 * Get rev_timestamp from rev_id, without loading the rest of the row.
	 *
	 * Historically, there was an extra Title parameter that was passed before $id. This is no
	 * longer needed and is deprecated in 1.34.
	 *
	 * MCR migration note: this replaces Revision::getTimestampFromId
	 *
	 * @param int $id
	 * @param int $flags
	 * @return string|bool False if not found
	 */
	public function getTimestampFromId( $id, $flags = 0 ) {
		if ( $id instanceof Title ) {
			// Old deprecated calling convention supported for backwards compatibility
			$id = $flags;
			$flags = func_num_args() > 2 ? func_get_arg( 2 ) : 0;
		}
		$db = $this->getDBConnectionRefForQueryFlags( $flags );

		$timestamp =
			$db->selectField( 'revision', 'rev_timestamp', [ 'rev_id' => $id ], __METHOD__ );

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
		$this->checkDatabaseDomain( $db );

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
		$this->checkDatabaseDomain( $db );

		if ( !$userId ) {
			return false;
		}

		$revQuery = $this->getQueryInfo();
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

		// Load the row from cache if possible.  If not possible, populate the cache.
		// As a minor optimization, remember if this was a cache hit or miss.
		// We can sometimes avoid a database query later if this is a cache miss.
		$fromCache = true;
		$row = $this->cache->getWithSetCallback(
			// Page/rev IDs passed in from DB to reflect history merges
			$this->getRevisionRowCacheKey( $db, $pageId, $revId ),
			WANObjectCache::TTL_WEEK,
			function ( $curValue, &$ttl, array &$setOpts ) use (
				$db, $pageId, $revId, &$fromCache
			) {
				$setOpts += Database::getCacheSetOptions( $db );
				$row = $this->fetchRevisionRowFromConds( $db, [ 'rev_id' => intval( $revId ) ] );
				if ( $row ) {
					$fromCache = false;
				}
				return $row; // don't cache negatives
			}
		);

		// Reflect revision deletion and user renames.
		if ( $row ) {
			return $this->newRevisionFromRow( $row, 0, $title, $fromCache );
		} else {
			return false;
		}
	}

	/**
	 * Get a cache key for use with a row as selected with getQueryInfo( [ 'page', 'user' ] )
	 * Caching rows without 'page' or 'user' could lead to issues.
	 * If the format of the rows returned by the query provided by getQueryInfo changes the
	 * cache key should be updated to avoid conflicts.
	 *
	 * @param IDatabase $db
	 * @param int $pageId
	 * @param int $revId
	 * @return string
	 */
	private function getRevisionRowCacheKey( IDatabase $db, $pageId, $revId ) {
		return $this->cache->makeGlobalKey(
			self::ROW_CACHE_KEY,
			$db->getDomainID(),
			$pageId,
			$revId
		);
	}

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.32
 */
class_alias( RevisionStore::class, 'MediaWiki\Storage\RevisionStore' );
