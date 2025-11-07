<?php
/**
 * @license GPL-2.0-or-later
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship (that file was removed entirely in 1.37,
 * but its history can still be found in prior versions of MediaWiki).
 *
 * @file
 */

namespace MediaWiki\Revision;

use InvalidArgumentException;
use LogicException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Exception\MWException;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\LegacyArticleIdAccess;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStore;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Storage\BadBlobException;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\ActorStore;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use StatusValue;
use stdClass;
use Traversable;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\IPUtils;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Service for looking up page revisions.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionStore
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in the old Revision class (which was later removed in 1.37).
 */
class RevisionStore implements RevisionFactory, RevisionLookup, LoggerAwareInterface {

	use LegacyArticleIdAccess;

	public const ROW_CACHE_KEY = 'revision-row-1.29';

	public const ORDER_OLDEST_TO_NEWEST = 'ASC';
	public const ORDER_NEWEST_TO_OLDEST = 'DESC';

	// Constants for get(...)Between methods
	public const INCLUDE_OLD = 'include_old';
	public const INCLUDE_NEW = 'include_new';
	public const INCLUDE_BOTH = 'include_both';

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

	/**
	 * @var false|string
	 */
	private $wikiId;

	private ILoadBalancer $loadBalancer;
	private WANObjectCache $cache;
	private BagOStuff $localCache;
	private CommentStore $commentStore;
	private ActorStore $actorStore;
	private LoggerInterface $logger;
	private NameTableStore $contentModelStore;
	private NameTableStore $slotRoleStore;
	private SlotRoleRegistry $slotRoleRegistry;
	private IContentHandlerFactory $contentHandlerFactory;
	private HookRunner $hookRunner;
	private PageStore $pageStore;
	private TitleFactory $titleFactory;
	private RecentChangeLookup $recentChangeLookup;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $cache A cache for caching revision rows. This can be the local
	 *        wiki's default instance even if $wikiId refers to a different wiki, since
	 *        makeGlobalKey() is used to constructed a key that allows cached revision rows from
	 *        the same database to be re-used between wikis. For example, enwiki and frwiki will
	 *        use the same cache keys for revision rows from the wikidatawiki database, regardless
	 *        of the cache's default key space.
	 * @param BagOStuff $localCache Another layer of cache, best to use APCu here.
	 * @param CommentStore $commentStore
	 * @param NameTableStore $contentModelStore
	 * @param NameTableStore $slotRoleStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param ActorStore $actorStore
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param PageStore $pageStore
	 * @param TitleFactory $titleFactory
	 * @param HookContainer $hookContainer
	 * @param RecentChangeLookup $recentChangeLookup
	 * @param false|string $wikiId Relevant wiki id or WikiAwareEntity::LOCAL for the current one
	 *
	 * @todo $blobStore should be allowed to be any BlobStore!
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		WANObjectCache $cache,
		BagOStuff $localCache,
		CommentStore $commentStore,
		NameTableStore $contentModelStore,
		NameTableStore $slotRoleStore,
		SlotRoleRegistry $slotRoleRegistry,
		ActorStore $actorStore,
		IContentHandlerFactory $contentHandlerFactory,
		PageStore $pageStore,
		TitleFactory $titleFactory,
		HookContainer $hookContainer,
		RecentChangeLookup $recentChangeLookup,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		Assert::parameterType( [ 'string', 'false' ], $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
		$this->cache = $cache;
		$this->localCache = $localCache;
		$this->commentStore = $commentStore;
		$this->contentModelStore = $contentModelStore;
		$this->slotRoleStore = $slotRoleStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->actorStore = $actorStore;
		$this->wikiId = $wikiId;
		$this->logger = new NullLogger();
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->pageStore = $pageStore;
		$this->titleFactory = $titleFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->recentChangeLookup = $recentChangeLookup;
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * @return bool Whether the store is read-only
	 */
	public function isReadOnly() {
		return $this->blobStore->isReadOnly();
	}

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false The wiki's logical name, of false to indicate the local wiki.
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 *
	 * @return IReadableDatabase
	 */
	private function getDBConnectionRefForQueryFlags( $queryFlags ) {
		if ( ( $queryFlags & IDBAccessObject::READ_LATEST ) == IDBAccessObject::READ_LATEST ) {
			return $this->getPrimaryConnection();
		} else {
			return $this->getReplicaConnection();
		}
	}

	/**
	 * @param string|array $groups
	 * @return IReadableDatabase
	 */
	private function getReplicaConnection( $groups = [] ) {
		// TODO: Replace with ICP
		return $this->loadBalancer->getConnection( DB_REPLICA, $groups, $this->wikiId );
	}

	private function getPrimaryConnection(): IDatabase {
		// TODO: Replace with ICP
		return $this->loadBalancer->getConnection( DB_PRIMARY, [], $this->wikiId );
	}

	/**
	 * Determines the page Title based on the available information.
	 *
	 * MCR migration note: this corresponded to Revision::getTitle
	 *
	 * @deprecated since 1.36, Use RevisionRecord::getPage() instead.
	 * @note The resulting Title object will be misleading if the RevisionStore is not
	 *        for the local wiki.
	 *
	 * @param int|null $pageId
	 * @param int|null $revId
	 * @param int $queryFlags
	 *
	 * @return Title
	 * @throws RevisionAccessException
	 */
	public function getTitle( $pageId, $revId, $queryFlags = IDBAccessObject::READ_NORMAL ) {
		// TODO: Hard-deprecate this once getPage() returns a PageRecord. T195069
		if ( $this->wikiId !== WikiAwareEntity::LOCAL ) {
			wfDeprecatedMsg( 'Using a Title object to refer to a page on another site.', '1.36' );
		}

		$page = $this->getPage( $pageId, $revId, $queryFlags );
		return $this->titleFactory->newFromPageIdentity( $page );
	}

	/**
	 * Determines the page based on the available information.
	 *
	 * @param int|null $pageId
	 * @param int|null $revId
	 * @param int $queryFlags
	 *
	 * @return PageIdentity
	 * @throws RevisionAccessException
	 */
	private function getPage( ?int $pageId, ?int $revId, int $queryFlags = IDBAccessObject::READ_NORMAL ) {
		if ( !$pageId && !$revId ) {
			throw new InvalidArgumentException( '$pageId and $revId cannot both be 0 or null' );
		}

		// This method recalls itself with READ_LATEST if READ_NORMAL doesn't get us a Title
		// So ignore READ_LATEST_IMMUTABLE flags and handle the fallback logic in this method
		if ( DBAccessObjectUtils::hasFlags( $queryFlags, IDBAccessObject::READ_LATEST_IMMUTABLE ) ) {
			$queryFlags = IDBAccessObject::READ_NORMAL;
		}

		// Loading by ID is best
		if ( $pageId !== null && $pageId > 0 ) {
			$page = $this->pageStore->getPageById( $pageId, $queryFlags );
			if ( $page ) {
				return $this->wrapPage( $page );
			}
		}

		// rev_id is defined as NOT NULL, but this revision may not yet have been inserted.
		if ( $revId !== null && $revId > 0 ) {
			$pageQuery = $this->pageStore->newSelectQueryBuilder( $queryFlags )
				->join( 'revision', null, 'page_id=rev_page' )
				->conds( [ 'rev_id' => $revId ] )
				->caller( __METHOD__ );

			$page = $pageQuery->fetchPageRecord();
			if ( $page ) {
				return $this->wrapPage( $page );
			}
		}

		// If we still don't have a title, fallback to primary DB if that wasn't already happening.
		if ( $queryFlags === IDBAccessObject::READ_NORMAL ) {
			$title = $this->getPage( $pageId, $revId, IDBAccessObject::READ_LATEST );
			if ( $title ) {
				$this->logger->info(
					__METHOD__ . ' fell back to READ_LATEST and got a Title.',
					[ 'exception' => new RuntimeException() ]
				);
				return $title;
			}
		}

		throw new RevisionAccessException(
			'Could not determine title for page ID {page_id} and revision ID {rev_id}',
			[
				'page_id' => $pageId,
				'rev_id' => $revId,
			]
		);
	}

	private function wrapPage( PageIdentity $page ): PageIdentity {
		if ( $this->wikiId === WikiAwareEntity::LOCAL ) {
			// NOTE: since there is still a lot of code that needs a full Title,
			//       and uses Title::castFromPageIdentity() to get one, it's beneficial
			//       to create a Title right away if we can, so we don't have to convert
			//       over and over later on.
			//       When there is less need to convert to Title, this special case can
			//       be removed.
			return $this->titleFactory->newFromPageIdentity( $page );
		} else {
			return $page;
		}
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
	 * This should be followed up by a WikiPage::updateRevisionOn on call to update
	 * page_latest on the page the revision is added to.
	 *
	 * MCR migration note: this replaced Revision::insertOn
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (primary connection)
	 *
	 * @return RevisionRecord the new revision record.
	 */
	public function insertRevisionOn( RevisionRecord $rev, IDatabase $dbw ) {
		// TODO: pass in a DBTransactionContext instead of a database connection.
		$this->checkDatabaseDomain( $dbw );

		$slotRoles = $rev->getSlotRoles();

		// Make sure the main slot is always provided throughout migration
		if ( !in_array( SlotRecord::MAIN, $slotRoles ) ) {
			throw new IncompleteRevisionException(
				'main slot must be provided'
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

		if ( $slotRoles == [ SlotRecord::MAIN ] ) {
			// T239717: If the main slot is the only slot, make sure the revision's nominal size
			// and hash match the main slot's nominal size and hash.
			$mainSlot = $rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );
			Assert::precondition(
				$mainSlot->getSize() === $rev->getSize(),
				'The revisions\'s size must match the main slot\'s size (see T239717)'
			);
			Assert::precondition(
				$mainSlot->getSha1() === $rev->getSha1(),
				'The revisions\'s SHA1 hash must match the main slot\'s SHA1 hash (see T239717)'
			);
		}

		$pageId = $this->failOnEmpty( $rev->getPageId( $this->wikiId ), 'rev_page field' ); // check this early

		$parentId = $rev->getParentId() ?? $this->getPreviousRevisionId( $dbw, $rev );

		Assert::precondition( $pageId > 0, 'revision must have an ID' );

		/** @var RevisionRecord $rev */
		$rev = $dbw->doAtomicSection(
			__METHOD__,
			function ( IDatabase $dbw, $fname ) use (
				$rev,
				$user,
				$comment,
				$pageId,
				$parentId
			) {
				return $this->insertRevisionInternal(
					$rev,
					$dbw,
					$user,
					$comment,
					$rev->getPage(),
					$pageId,
					$parentId
				);
			}
		);

		Assert::postcondition( $rev->getId( $this->wikiId ) > 0, 'revision must have an ID' );
		Assert::postcondition( $rev->getPageId( $this->wikiId ) > 0, 'revision must have a page ID' );
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

		$this->hookRunner->onRevisionRecordInserted( $rev );

		return $rev;
	}

	/**
	 * Update derived slots in an existing revision into the database, returning the modified
	 * slots on success.
	 *
	 * @param RevisionRecord $revision After this method returns, the $revision object will be
	 *                                 obsolete in that it does not have the new slots.
	 * @param RevisionSlotsUpdate $revisionSlotsUpdate
	 * @param IDatabase $dbw (primary connection)
	 *
	 * @return SlotRecord[] the new slot records.
	 * @internal
	 */
	public function updateSlotsOn(
		RevisionRecord $revision,
		RevisionSlotsUpdate $revisionSlotsUpdate,
		IDatabase $dbw
	): array {
		$this->checkDatabaseDomain( $dbw );

		// Make sure all modified and removed slots are derived slots
		foreach ( $revisionSlotsUpdate->getModifiedRoles() as $role ) {
			Assert::precondition(
				$this->slotRoleRegistry->getRoleHandler( $role )->isDerived(),
				'Trying to modify a slot that is not derived'
			);
		}
		foreach ( $revisionSlotsUpdate->getRemovedRoles() as $role ) {
			$isDerived = $this->slotRoleRegistry->getRoleHandler( $role )->isDerived();
			Assert::precondition(
				$isDerived,
				'Trying to remove a slot that is not derived'
			);
			throw new LogicException( 'Removing derived slots is not yet implemented. See T277394.' );
		}

		/** @var SlotRecord[] $slotRecords */
		$slotRecords = $dbw->doAtomicSection(
			__METHOD__,
			function ( IDatabase $dbw, $fname ) use (
				$revision,
				$revisionSlotsUpdate
			) {
				return $this->updateSlotsInternal(
					$revision,
					$revisionSlotsUpdate,
					$dbw
				);
			}
		);

		foreach ( $slotRecords as $role => $slot ) {
			Assert::postcondition(
				$slot->getContent() !== null,
				$role . ' slot must have content'
			);
			Assert::postcondition(
				$slot->hasRevision(),
				$role . ' slot must have a revision associated'
			);
		}

		return $slotRecords;
	}

	/**
	 * @param RevisionRecord $revision
	 * @param RevisionSlotsUpdate $revisionSlotsUpdate
	 * @param IDatabase $dbw
	 * @return SlotRecord[]
	 */
	private function updateSlotsInternal(
		RevisionRecord $revision,
		RevisionSlotsUpdate $revisionSlotsUpdate,
		IDatabase $dbw
	): array {
		$page = $revision->getPage();
		$revId = $revision->getId( $this->wikiId );
		$blobHints = [
			BlobStore::PAGE_HINT => $page->getId( $this->wikiId ),
			BlobStore::REVISION_HINT => $revId,
			BlobStore::PARENT_HINT => $revision->getParentId( $this->wikiId ),
		];

		$newSlots = [];
		foreach ( $revisionSlotsUpdate->getModifiedRoles() as $role ) {
			$slot = $revisionSlotsUpdate->getModifiedSlot( $role );
			$newSlots[$role] = $this->insertSlotOn( $dbw, $revId, $slot, $page, $blobHints );
		}

		return $newSlots;
	}

	private function insertRevisionInternal(
		RevisionRecord $rev,
		IDatabase $dbw,
		UserIdentity $user,
		CommentStoreComment $comment,
		PageIdentity $page,
		int $pageId,
		int $parentId
	): RevisionRecord {
		$slotRoles = $rev->getSlotRoles();

		$revisionRow = $this->insertRevisionRowOn(
			$dbw,
			$rev,
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
			} else {
				$newSlots[$role] = $this->insertSlotOn( $dbw, $revisionId, $slot, $page, $blobHints );
			}
		}

		$this->insertIpChangesRow( $dbw, $user, $rev, $revisionId );

		$rev = new RevisionStoreRecord(
			$page,
			$user,
			$comment,
			(object)$revisionRow,
			new RevisionSlots( $newSlots ),
			$this->wikiId
		);

		return $rev;
	}

	/**
	 * @param IDatabase $dbw
	 * @param int $revisionId
	 * @param SlotRecord $protoSlot
	 * @param PageIdentity $page
	 * @param array $blobHints See the BlobStore::XXX_HINT constants
	 * @return SlotRecord
	 */
	private function insertSlotOn(
		IDatabase $dbw,
		$revisionId,
		SlotRecord $protoSlot,
		PageIdentity $page,
		array $blobHints = []
	) {
		if ( $protoSlot->hasAddress() ) {
			$blobAddress = $protoSlot->getAddress();
		} else {
			$blobAddress = $this->storeContentBlob( $protoSlot, $page, $blobHints );
		}

		if ( $protoSlot->hasContentId() ) {
			$contentId = $protoSlot->getContentId();
		} else {
			$contentId = $this->insertContentRowOn( $protoSlot, $dbw, $blobAddress );
		}

		$this->insertSlotRowOn( $protoSlot, $dbw, $revisionId, $contentId );

		return SlotRecord::newSaved(
			$revisionId,
			$contentId,
			$blobAddress,
			$protoSlot
		);
	}

	/**
	 * Insert IP revision into ip_changes for use when querying for a range.
	 * @param IDatabase $dbw
	 * @param UserIdentity $user
	 * @param RevisionRecord $rev
	 * @param int $revisionId
	 */
	private function insertIpChangesRow(
		IDatabase $dbw,
		UserIdentity $user,
		RevisionRecord $rev,
		$revisionId
	) {
		if ( !$user->isRegistered() && IPUtils::isValid( $user->getName() ) ) {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'ip_changes' )
				->row( [
					'ipc_rev_id'        => $revisionId,
					'ipc_rev_timestamp' => $dbw->timestamp( $rev->getTimestamp() ),
					'ipc_hex'           => IPUtils::toHex( $user->getName() ),
				] )
				->caller( __METHOD__ )->execute();

		}
	}

	/**
	 * @param IDatabase $dbw
	 * @param RevisionRecord $rev
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
		$parentId
	) {
		$revisionRow = $this->getBaseRevisionRow( $dbw, $rev, $parentId );

		$revisionRow += $this->commentStore->insert(
			$dbw,
			'rev_comment',
			$rev->getComment( RevisionRecord::RAW )
		);

		$dbw->newInsertQueryBuilder()
			->insertInto( 'revision' )
			->row( $revisionRow )
			->caller( __METHOD__ )->execute();

		if ( !isset( $revisionRow['rev_id'] ) ) {
			// only if auto-increment was used
			$revisionRow['rev_id'] = intval( $dbw->insertId() );

			if ( $dbw->getType() === 'mysql' ) {
				// (T202032) MySQL until 8.0 and MariaDB until some version after 10.1.34 don't save the
				// auto-increment value to disk, so on server restart it might reuse IDs from deleted
				// revisions. We can fix that with an insert with an explicit rev_id value, if necessary.

				$maxRevId = intval( $dbw->newSelectQueryBuilder()
					->select( 'MAX(ar_rev_id)' )
					->from( 'archive' )
					->caller( __METHOD__ )
					->fetchField() );
				$table = 'archive';
				$maxRevId2 = intval( $dbw->newSelectQueryBuilder()
					->select( 'MAX(slot_revision_id)' )
					->from( 'slots' )
					->caller( __METHOD__ )
					->fetchField() );
				if ( $maxRevId2 >= $maxRevId ) {
					$maxRevId = $maxRevId2;
					$table = 'slots';
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
						static fn () => $dbw->unlock( 'fix-for-T202032', $fname ),
						__METHOD__
					);

					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'revision' )
						->where( [ 'rev_id' => $revisionRow['rev_id'] ] )
						->caller( __METHOD__ )->execute();

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
						$dbw->selectSQLText( 'archive', [ 'v' => "MAX(ar_rev_id)" ], '', __METHOD__ ) . ' FOR UPDATE',
						__METHOD__
					)->fetchObject();

					$row2 = $dbw->query(
						$dbw->selectSQLText( 'slots', [ 'v' => "MAX(slot_revision_id)" ], '', __METHOD__ )
							. ' FOR UPDATE',
						__METHOD__
					)->fetchObject();

					$maxRevId = max(
						$maxRevId,
						$row1 ? intval( $row1->v ) : 0,
						$row2 ? intval( $row2->v ) : 0
					);

					// If we don't have SCHEMA_COMPAT_WRITE_NEW, all except the first of any concurrent
					// transactions will throw a duplicate key error here. It doesn't seem worth trying
					// to avoid that.
					$revisionRow['rev_id'] = $maxRevId + 1;
					$dbw->newInsertQueryBuilder()
						->insertInto( 'revision' )
						->row( $revisionRow )
						->caller( __METHOD__ )->execute();
				}
			}
		}

		return $revisionRow;
	}

	/**
	 * @param IDatabase $dbw
	 * @param RevisionRecord $rev
	 * @param int $parentId
	 *
	 * @return array a revision table row
	 */
	private function getBaseRevisionRow(
		IDatabase $dbw,
		RevisionRecord $rev,
		$parentId
	) {
		// Record the edit in revisions
		$revisionRow = [
			'rev_page'       => $rev->getPageId( $this->wikiId ),
			'rev_parent_id'  => $parentId,
			'rev_actor'      => $this->actorStore->acquireActorId(
				$rev->getUser( RevisionRecord::RAW ),
				$dbw
			),
			'rev_minor_edit' => $rev->isMinor() ? 1 : 0,
			'rev_timestamp'  => $dbw->timestamp( $rev->getTimestamp() ),
			'rev_deleted'    => $rev->getVisibility(),
			'rev_len'        => $rev->getSize(),
			'rev_sha1'       => $rev->getSha1(),
		];

		if ( $rev->getId( $this->wikiId ) !== null ) {
			// Needed to restore revisions with their original ID
			$revisionRow['rev_id'] = $rev->getId( $this->wikiId );
		}

		return $revisionRow;
	}

	/**
	 * @param SlotRecord $slot
	 * @param PageIdentity $page
	 * @param array $blobHints See the BlobStore::XXX_HINT constants
	 *
	 * @throws MWException
	 * @return string the blob address
	 */
	private function storeContentBlob(
		SlotRecord $slot,
		PageIdentity $page,
		array $blobHints = []
	) {
		$content = $slot->getContent();
		$format = $content->getDefaultFormat();
		$model = $content->getModel();

		$this->checkContent( $content, $page, $slot->getRole() );

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
		$dbw->newInsertQueryBuilder()
			->insertInto( 'slots' )
			->row( [
				'slot_revision_id' => $revisionId,
				'slot_role_id' => $this->slotRoleStore->acquireId( $slot->getRole() ),
				'slot_content_id' => $contentId,
				// If the slot has a specific origin use that ID, otherwise use the ID of the revision
				// that we just inserted.
				'slot_origin' => $slot->hasOrigin() ? $slot->getOrigin() : $revisionId,
			] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * @param SlotRecord $slot
	 * @param IDatabase $dbw
	 * @param string $blobAddress
	 * @return int content row ID
	 */
	private function insertContentRowOn( SlotRecord $slot, IDatabase $dbw, $blobAddress ) {
		$dbw->newInsertQueryBuilder()
			->insertInto( 'content' )
			->row( [
				'content_size' => $slot->getSize(),
				'content_sha1' => $slot->getSha1(),
				'content_model' => $this->contentModelStore->acquireId( $slot->getModel() ),
				'content_address' => $blobAddress,
			] )
			->caller( __METHOD__ )->execute();
		return intval( $dbw->insertId() );
	}

	/**
	 * MCR migration note: this corresponded to Revision::checkContentModel
	 *
	 * @param Content $content
	 * @param PageIdentity $page
	 * @param string $role
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function checkContent( Content $content, PageIdentity $page, string $role ) {
		// Note: may return null for revisions that have not yet been inserted

		$model = $content->getModel();
		$format = $content->getDefaultFormat();
		$handler = $content->getContentHandler();

		if ( !$handler->isSupportedFormat( $format ) ) {
			throw new MWException(
				"Can't use format $format with content model $model on $page role $role"
			);
		}

		if ( !$content->isValid() ) {
			throw new MWException(
				"New content for $page role $role is not valid! Content model is $model"
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
	 * MCR migration note: this replaced Revision::newNullRevision
	 *
	 * @deprecated since 1.44, use PageUpdater::saveDummyRevision() instead.
	 *
	 * @param IDatabase $dbw used for obtaining the lock on the page table row
	 * @param PageIdentity $page the page to read from
	 * @param CommentStoreComment $comment RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param UserIdentity $user The user to attribute the revision to
	 *
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision(
		IDatabase $dbw,
		PageIdentity $page,
		CommentStoreComment $comment,
		$minor,
		UserIdentity $user
	) {
		$this->checkDatabaseDomain( $dbw );

		$pageId = $this->getArticleId( $page );

		// T51581: Lock the page table row to ensure no other process
		// is adding a revision to the page at the same time.
		// Avoid locking extra tables, compare T191892.
		$pageLatest = $dbw->newSelectQueryBuilder()
			->select( 'page_latest' )
			->forUpdate()
			->from( 'page' )
			->where( [ 'page_id' => $pageId ] )
			->caller( __METHOD__ )->fetchField();

		if ( !$pageLatest ) {
			$msg = 'T235589: Failed to select table row during null revision creation' .
				" Page id '$pageId' does not exist.";
			$this->logger->error(
				$msg,
				[ 'exception' => new RuntimeException( $msg ) ]
			);

			return null;
		}

		// Fetch the actual revision row from primary DB, without locking all extra tables.
		$oldRevision = $this->loadRevisionFromConds(
			$dbw,
			[ 'rev_id' => intval( $pageLatest ) ],
			IDBAccessObject::READ_LATEST,
			$page
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
		$timestamp = MWTimestamp::now( TS_MW );
		$newRevision = MutableRevisionRecord::newFromParentRevision( $oldRevision );

		$newRevision->setComment( $comment );
		$newRevision->setUser( $user );
		$newRevision->setTimestamp( $timestamp );
		$newRevision->setMinorEdit( $minor );

		return $newRevision;
	}

	/**
	 * MCR migration note: this replaced Revision::isUnpatrolled
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
	 * MCR migration note: this replaced Revision::getRecentChange
	 *
	 * @todo move this somewhere else?
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *
	 * @return null|RecentChange
	 */
	public function getRecentChange( RevisionRecord $rev, $flags = 0 ) {
		if ( $this->wikiId !== RevisionRecord::LOCAL ) {
			throw new PreconditionException( 'RecentChangeLookup is only available for the local wiki' );
		}

		$rc = $this->recentChangeLookup->getRecentChangeByConds(
			[
				'rc_this_oldid' => $rev->getId( $this->wikiId ),
				// rc_this_oldid does not have to be unique,
				// in particular, it is shared with categorization
				// changes. Prefer the original change because callers
				// often expect a change for patrolling.
				'rc_source' => [ RecentChange::SRC_EDIT, RecentChange::SRC_NEW, RecentChange::SRC_LOG ],
			],
			__METHOD__,
			( $flags & IDBAccessObject::READ_LATEST ) == IDBAccessObject::READ_LATEST
		);

		// XXX: cache this locally? Glue it to the RevisionRecord?
		return $rc;
	}

	/**
	 * Loads a Content object based on a slot row.
	 *
	 * This method does not call $slot->getContent(), and may be used as a callback
	 * called by $slot->getContent().
	 *
	 * MCR migration note: this roughly corresponded to Revision::getContentInternal
	 *
	 * @param SlotRecord $slot The SlotRecord to load content for
	 * @param string|null $blobData The content blob, in the form indicated by $blobFlags
	 * @param string|null $blobFlags Flags indicating how $blobData needs to be processed.
	 *        Use null if no processing should happen. That is in contrast to the empty string,
	 *        which causes the blob to be decoded according to the configured legacy encoding.
	 * @param string|null $blobFormat MIME type indicating how $dataBlob is encoded
	 * @param int $queryFlags
	 *
	 * @throws RevisionAccessException
	 * @return Content
	 */
	private function loadSlotContent(
		SlotRecord $slot,
		?string $blobData = null,
		?string $blobFlags = null,
		?string $blobFormat = null,
		int $queryFlags = 0
	) {
		if ( $blobData !== null ) {
			$blobAddress = $slot->hasAddress() ? $slot->getAddress() : null;

			if ( $blobFlags === null ) {
				// No blob flags, so use the blob verbatim.
				$data = $blobData;
			} else {
				try {
					$data = $this->blobStore->expandBlob( $blobData, $blobFlags, $blobAddress );
				} catch ( BadBlobException $e ) {
					throw new BadRevisionException( $e->getMessage(), [], 0, $e );
				}

				if ( $data === false ) {
					throw new RevisionAccessException(
						'Failed to expand blob data using flags {flags} (key: {cache_key})',
						[
							'flags' => $blobFlags,
							'cache_key' => $blobAddress,
						]
					);
				}
			}

		} else {
			$address = $slot->getAddress();
			try {
				$data = $this->blobStore->getBlob( $address, $queryFlags );
			} catch ( BadBlobException $e ) {
				throw new BadRevisionException( $e->getMessage(), [], 0, $e );
			} catch ( BlobAccessException $e ) {
				throw new RevisionAccessException(
					'Failed to load data blob from {address} for revision {revision}. '
						. 'If this problem persists, use the findBadBlobs maintenance script '
						. 'to investigate the issue and mark the bad blobs.',
					[ 'address' => $e->getMessage(), 'revision' => $slot->getRevision() ],
					0,
					$e
				);
			}
		}

		$model = $slot->getModel();

		// If the content model is not known, don't fail here (T220594, T220793, T228921)
		if ( !$this->contentHandlerFactory->isDefinedModel( $model ) ) {
			$this->logger->warning(
				"Undefined content model '$model', falling back to FallbackContent",
				[
					'content_address' => $slot->getAddress(),
					'rev_id' => $slot->getRevision(),
					'role_name' => $slot->getRole(),
					'model_name' => $model,
					'exception' => new RuntimeException()
				]
			);

			return new FallbackContent( $data, $model );
		}

		return $this->contentHandlerFactory
			->getContentHandler( $model )
			->unserializeContent( $data, $blobFormat );
	}

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaced Revision::newFromId
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the primary DB
	 *
	 * @param int $id
	 * @param int $flags (optional)
	 * @param PageIdentity|null $page The page the revision belongs to.
	 *        Providing the page may improve performance.
	 *
	 * @return RevisionRecord|null
	 */
	public function getRevisionById( $id, $flags = 0, ?PageIdentity $page = null ) {
		return $this->newRevisionFromConds( [ 'rev_id' => intval( $id ) ], $flags, $page );
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * MCR migration note: this replaced Revision::newFromTitle
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the primary DB
	 *
	 * @param LinkTarget|PageReference $page Calling with LinkTarget is deprecated since 1.36
	 * @param int $revId (optional)
	 * @param int $flags Bitfield (optional)
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTitle( $page, $revId = 0, $flags = 0 ) {
		$conds = $this->getPageConditions( $page );
		if ( !$conds ) {
			return null;
		}
		if ( !( $page instanceof PageIdentity ) ) {
			if ( !( $page instanceof PageReference ) ) {
				wfDeprecated( __METHOD__ . ' with a LinkTarget', '1.45' );
			}
			$page = null;
		}

		if ( $revId ) {
			// Use the specified revision ID.
			// Note that we use newRevisionFromConds here because we want to retry
			// and fall back to primary DB if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags, $page );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to primary DB. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
			$db = $this->getDBConnectionRefForQueryFlags( $flags );
			$conds[] = 'rev_id=page_latest';
			return $this->loadRevisionFromConds( $db, $conds, $flags, $page );
		}
	}

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaced Revision::newFromPageId
	 *
	 * $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB (since 1.20)
	 *      IDBAccessObject::READ_LOCKING : Select & lock the data from the primary DB
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
			// and fall back to primary DB if the page is not found on a replica.
			// Since the caller supplied a revision ID, we are pretty sure the revision is
			// supposed to exist, so we should try hard to find it.
			$conds['rev_id'] = $revId;
			return $this->newRevisionFromConds( $conds, $flags );
		} else {
			// Use a join to get the latest revision.
			// Note that we don't use newRevisionFromConds here because we don't want to retry
			// and fall back to primary DB. The assumption is that we only want to force the fallback
			// if we are quite sure the revision exists because the caller supplied a revision ID.
			// If the page isn't found at all on a replica, it probably simply does not exist.
			$db = $this->getDBConnectionRefForQueryFlags( $flags );

			$conds[] = 'rev_id=page_latest';

			return $this->loadRevisionFromConds( $db, $conds, $flags );
		}
	}

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * MCR migration note: this replaced Revision::loadFromTimestamp
	 *
	 * @param LinkTarget|PageReference $page Calling with LinkTarget is deprecated since 1.36
	 * @param string $timestamp
	 * @param int $flags Bitfield (optional) include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *      IDBAccessObject::READ_LOCKING: Select & lock the data from the primary DB
	 *      Default: IDBAccessObject::READ_NORMAL
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTimestamp(
		$page,
		string $timestamp,
		int $flags = IDBAccessObject::READ_NORMAL
	): ?RevisionRecord {
		$conds = $this->getPageConditions( $page );
		if ( !$conds ) {
			return null;
		}
		if ( !( $page instanceof PageIdentity ) ) {
			if ( !( $page instanceof PageReference ) ) {
				wfDeprecated( __METHOD__ . ' with a LinkTarget', '1.45' );
			}
			$page = null;
		}

		$db = $this->getDBConnectionRefForQueryFlags( $flags );
		$conds['rev_timestamp'] = $db->timestamp( $timestamp );
		return $this->newRevisionFromConds( $conds, $flags, $page );
	}

	/**
	 * @param int $revId The revision to load slots for.
	 * @param int $queryFlags
	 * @param PageIdentity $page
	 *
	 * @return SlotRecord[]
	 */
	private function loadSlotRecords( $revId, $queryFlags, PageIdentity $page ) {
		// TODO: Find a way to add NS_MODULE from Scribunto here
		if ( $page->getNamespace() !== NS_TEMPLATE ) {
			$res = $this->loadSlotRecordsFromDb( $revId, $queryFlags, $page );
			return $this->constructSlotRecords( $revId, $res, $queryFlags, $page );
		}

		// TODO: These caches should not be needed. See T297147#7563670
		$res = $this->localCache->getWithSetCallback(
			$this->localCache->makeKey(
				'revision-slots',
				$page->getWikiId(),
				$page->getId( $page->getWikiId() ),
				$revId
			),
			$this->localCache::TTL_HOUR,
			function () use ( $revId, $queryFlags, $page ) {
				return $this->cache->getWithSetCallback(
					$this->cache->makeKey(
						'revision-slots',
						$page->getWikiId(),
						$page->getId( $page->getWikiId() ),
						$revId
					),
					WANObjectCache::TTL_DAY,
					function () use ( $revId, $queryFlags, $page ) {
						$res = $this->loadSlotRecordsFromDb( $revId, $queryFlags, $page );
						if ( !$res ) {
							// Avoid caching
							return false;
						}
						return $res;
					}
				);
			}
		);
		if ( !$res ) {
			$res = [];
		}

		return $this->constructSlotRecords( $revId, $res, $queryFlags, $page );
	}

	private function loadSlotRecordsFromDb( int $revId, int $queryFlags, PageIdentity $page ): array {
		$revQuery = $this->getSlotsQueryInfo( [ 'content' ] );

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$res = $db->newSelectQueryBuilder()
			->queryInfo( $revQuery )
			->where( [ 'slot_revision_id' => $revId ] )
			->recency( $queryFlags )
			->caller( __METHOD__ )->fetchResultSet();

		if ( !$res->numRows() && !( $queryFlags & IDBAccessObject::READ_LATEST ) ) {
			// If we found no slots, try looking on the primary database (T212428, T252156)
			$this->logger->info(
				__METHOD__ . ' falling back to READ_LATEST.',
				[
					'revid' => $revId,
					'exception' => new RuntimeException(),
				]
			);
			return $this->loadSlotRecordsFromDb(
				$revId,
				$queryFlags | IDBAccessObject::READ_LATEST,
				$page
			);
		}
		return iterator_to_array( $res );
	}

	/**
	 * Factory method for SlotRecords based on known slot rows.
	 *
	 * @param int $revId The revision to load slots for.
	 * @param \stdClass[]|IResultWrapper $slotRows
	 * @param int $queryFlags
	 * @param PageIdentity $page
	 * @param array|null $slotContents a map from blobAddress to slot
	 * 	content blob or Content object.
	 *
	 * @return SlotRecord[]
	 */
	private function constructSlotRecords(
		$revId,
		$slotRows,
		$queryFlags,
		PageIdentity $page,
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
					$row->model_name = $slotRoleHandler->getDefaultModel( $page );
				}
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
			$this->logger->error(
				__METHOD__ . ': Main slot of revision not found in database. See T212428.',
				[
					'revid' => $revId,
					'queryFlags' => $queryFlags,
					'exception' => new RuntimeException(),
				]
			);

			throw new RevisionAccessException(
				'Main slot of revision not found in database. See T212428.'
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
	 * @param \stdClass[]|null $slotRows
	 * @param int $queryFlags
	 * @param PageIdentity $page
	 *
	 * @return RevisionSlots
	 */
	private function newRevisionSlots(
		$revId,
		$slotRows,
		$queryFlags,
		PageIdentity $page
	) {
		if ( $slotRows ) {
			$slots = new RevisionSlots(
				$this->constructSlotRecords( $revId, $slotRows, $queryFlags, $page )
			);
		} else {
			$slots = new RevisionSlots( function () use( $revId, $queryFlags, $page ) {
				return $this->loadSlotRecords( $revId, $queryFlags, $page );
			} );
		}

		return $slots;
	}

	/**
	 * Make a fake RevisionRecord object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * The user ID and user name may optionally be supplied using the aliases
	 * ar_user and ar_user_text (the names of fields which existed before
	 * MW 1.34).
	 *
	 * MCR migration note: this replaced Revision::newFromArchiveRow
	 *
	 * @param \stdClass $row
	 * @param int $queryFlags
	 * @param PageIdentity|null $page
	 * @param array $overrides associative array with fields of $row to override. This may be
	 *   used e.g. to force the parent revision ID or page ID. Keys in the array are fields
	 *   names from the archive table without the 'ar_' prefix, i.e. use 'parent_id' to
	 *   override ar_parent_id.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRow(
		$row,
		$queryFlags = 0,
		?PageIdentity $page = null,
		array $overrides = []
	) {
		return $this->newRevisionFromArchiveRowAndSlots( $row, null, $queryFlags, $page, $overrides );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaced Revision::newFromRow
	 *
	 * @param \stdClass $row A database row generated from a query based on RevisionSelectQueryBuilder
	 * @param int $queryFlags
	 * @param PageIdentity|null $page Preloaded page object
	 * @param bool $fromCache if true, the returned RevisionRecord will ensure that no stale
	 *   data is returned from getters, by querying the database as needed
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow(
		$row,
		$queryFlags = 0,
		?PageIdentity $page = null,
		$fromCache = false
	) {
		return $this->newRevisionFromRowAndSlots( $row, null, $queryFlags, $page, $fromCache );
	}

	/**
	 * @see newRevisionFromArchiveRow()
	 * @since 1.35
	 *
	 * @param stdClass $row
	 * @param null|stdClass[]|RevisionSlots $slots
	 *  - Database rows generated from a query based on getSlotsQueryInfo
	 *    with the 'content' flag set. Or
	 *  - RevisionSlots instance
	 * @param int $queryFlags
	 * @param PageIdentity|null $page
	 * @param array $overrides associative array with fields of $row to override. This may be
	 *   used e.g. to force the parent revision ID or page ID. Keys in the array are fields
	 *   names from the archive table without the 'ar_' prefix, i.e. use 'parent_id' to
	 *   override ar_parent_id.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRowAndSlots(
		stdClass $row,
		$slots,
		int $queryFlags = 0,
		?PageIdentity $page = null,
		array $overrides = []
	) {
		if ( !$page && isset( $overrides['title'] ) ) {
			if ( !( $overrides['title'] instanceof PageIdentity ) ) {
				throw new InvalidArgumentException( 'title field override must contain a PageIdentity object.' );
			}

			$page = $overrides['title'];
		}

		if ( $page === null ) {
			if ( isset( $row->ar_namespace ) && isset( $row->ar_title ) ) {
				// Represent a non-existing page.
				// NOTE: The page title may be invalid by current rules (T384628).
				$page = PageIdentityValue::localIdentity( 0, $row->ar_namespace, $row->ar_title );
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
			$user = $this->actorStore->newActorFromRowFields(
				$row->ar_user ?? null,
				$row->ar_user_text ?? null,
				$row->ar_actor ?? null
			);
		} catch ( InvalidArgumentException $ex ) {
			$this->logger->warning( 'Could not load user for archive revision {rev_id}', [
				'ar_rev_id' => $row->ar_rev_id,
				'ar_actor' => $row->ar_actor ?? 'null',
				'ar_user_text' => $row->ar_user_text ?? 'null',
				'ar_user' => $row->ar_user ?? 'null',
				'exception' => $ex
			] );
			$user = $this->actorStore->getUnknownActor();
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		// Legacy because $row may have come from self::selectFields()
		$comment = $this->commentStore->getCommentLegacy( $db, 'ar_comment', $row, true );

		if ( !( $slots instanceof RevisionSlots ) ) {
			$slots = $this->newRevisionSlots( (int)$row->ar_rev_id, $slots, $queryFlags, $page );
		}
		return new RevisionArchiveRecord( $page, $user, $comment, $row, $slots, $this->wikiId );
	}

	/**
	 * @see newFromRevisionRow()
	 *
	 * @param stdClass $row A database row generated from a query based on RevisionSelectQueryBuilder
	 * @param null|stdClass[]|RevisionSlots $slots
	 *  - Database rows generated from a query based on getSlotsQueryInfo
	 *    with the 'content' flag set. Or
	 *  - RevisionSlots instance
	 * @param int $queryFlags
	 * @param PageIdentity|null $page
	 * @param bool $fromCache if true, the returned RevisionRecord will ensure that no stale
	 *   data is returned from getters, by querying the database as needed
	 *
	 * @return RevisionRecord
	 * @throws RevisionAccessException
	 * @see RevisionFactory::newRevisionFromRow
	 */
	public function newRevisionFromRowAndSlots(
		stdClass $row,
		$slots,
		int $queryFlags = 0,
		?PageIdentity $page = null,
		bool $fromCache = false
	) {
		if ( !$page ) {
			if ( isset( $row->page_id )
				&& isset( $row->page_namespace )
				&& isset( $row->page_title )
			) {
				$page = new PageIdentityValue(
					(int)$row->page_id,
					(int)$row->page_namespace,
					$row->page_title,
					$this->wikiId
				);

				$page = $this->wrapPage( $page );
			} else {
				$pageId = (int)( $row->rev_page ?? 0 );
				$revId = (int)( $row->rev_id ?? 0 );

				$page = $this->getPage( $pageId, $revId, $queryFlags );
			}
		} else {
			$page = $this->ensureRevisionRowMatchesPage( $row, $page );
		}

		if ( !$page ) {
			// This should already have been caught about, but apparently
			// it not always is, see T286877.
			throw new RevisionAccessException(
				"Failed to determine page associated with revision {$row->rev_id}"
			);
		}

		try {
			$user = $this->actorStore->newActorFromRowFields(
				$row->rev_user ?? null,
				$row->rev_user_text ?? null,
				$row->rev_actor ?? null
			);
		} catch ( InvalidArgumentException $ex ) {
			$this->logger->warning( 'Could not load user for revision {rev_id}', [
				'rev_id' => $row->rev_id,
				'rev_actor' => $row->rev_actor ?? 'null',
				'rev_user_text' => $row->rev_user_text ?? 'null',
				'rev_user' => $row->rev_user ?? 'null',
				'exception' => $ex
			] );
			$user = $this->actorStore->getUnknownActor();
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		// Legacy because $row may have come from self::selectFields()
		$comment = $this->commentStore->getCommentLegacy( $db, 'rev_comment', $row, true );

		if ( !( $slots instanceof RevisionSlots ) ) {
			$slots = $this->newRevisionSlots( (int)$row->rev_id, $slots, $queryFlags, $page );
		}

		// If this is a cached row, instantiate a cache-aware RevisionRecord to avoid stale data.
		if ( $fromCache ) {
			$rev = new RevisionStoreCacheRecord(
				function ( $revId ) use ( $queryFlags ) {
					$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
					$row = $this->fetchRevisionRowFromConds(
						$db,
						[ 'rev_id' => intval( $revId ) ]
					);
					if ( !$row && !( $queryFlags & IDBAccessObject::READ_LATEST ) ) {
						// If we found no slots, try looking on the primary database (T259738)
						$this->logger->info(
							'RevisionStoreCacheRecord refresh callback falling back to READ_LATEST.',
							[
								'revid' => $revId,
								'exception' => new RuntimeException(),
							]
						);
						$dbw = $this->getDBConnectionRefForQueryFlags( IDBAccessObject::READ_LATEST );
						$row = $this->fetchRevisionRowFromConds(
							$dbw,
							[ 'rev_id' => intval( $revId ) ]
						);
					}
					if ( !$row ) {
						return [ null, null ];
					}
					return [
						$row->rev_deleted,
						$this->actorStore->newActorFromRowFields(
							$row->rev_user ?? null,
							$row->rev_user_text ?? null,
							$row->rev_actor ?? null
						)
					];
				},
				$page, $user, $comment, $row, $slots, $this->wikiId
			);
		} else {
			$rev = new RevisionStoreRecord(
				$page, $user, $comment, $row, $slots, $this->wikiId );
		}
		return $rev;
	}

	/**
	 * Check that the given row matches the given Title object.
	 * When a mismatch is detected, this tries to re-load the title from primary DB,
	 * to avoid spurious errors during page moves.
	 *
	 * @param \stdClass $row
	 * @param PageIdentity $page
	 * @param array $context
	 *
	 * @return Pageidentity
	 */
	private function ensureRevisionRowMatchesPage( $row, PageIdentity $page, $context = [] ) {
		$revId = (int)( $row->rev_id ?? 0 );
		$revPageId = (int)( $row->rev_page ?? 0 ); // XXX: also check $row->page_id?
		$expectedPageId = $page->getId( $this->wikiId );
		// Avoid fatal error when the Title's ID changed, T246720
		if ( $revPageId && $expectedPageId && $revPageId !== $expectedPageId ) {
			// NOTE: PageStore::getPageByReference may use the page ID, which we don't want here.
			$pageRec = $this->pageStore->getPageByName(
				$page->getNamespace(),
				$page->getDBkey(),
				IDBAccessObject::READ_LATEST
			);
			$masterPageId = $pageRec->getId( $this->wikiId );
			$masterLatest = $pageRec->getLatest( $this->wikiId );
			if ( $revPageId === $masterPageId ) {
				if ( $page instanceof Title ) {
					// If we were using a Title object, keep using it, but update the page ID.
					// This way, we don't unexpectedly mix Titles with immutable value objects.
					$page->resetArticleID( $masterPageId );

				} else {
					$page = $pageRec;
				}

				$this->logger->info(
					"Encountered stale Title object",
					[
						'page_id_stale' => $expectedPageId,
						'page_id_reloaded' => $masterPageId,
						'page_latest' => $masterLatest,
						'rev_id' => $revId,
						'exception' => new RuntimeException(),
					] + $context
				);
			} else {
				$expectedTitle = (string)$page;
				if ( $page instanceof Title ) {
					// If we started with a Title, keep using a Title.
					$page = $this->titleFactory->newFromID( $revPageId );
				} else {
					$page = $pageRec;
				}

				// This could happen if a caller to e.g. getRevisionById supplied a Title that is
				// plain wrong. In this case, we should ideally throw an IllegalArgumentException.
				// However, it is more likely that we encountered a race condition during a page
				// move (T268910, T279832) or database corruption (T263340). That situation
				// should not be ignored, but we can allow the request to continue in a reasonable
				// manner without breaking things for the user.
				$this->logger->error(
					"Encountered mismatching Title object (see T259022, T268910, T279832, T263340)",
					[
						'expected_page_id' => $masterPageId,
						'expected_page_title' => $expectedTitle,
						'rev_page' => $revPageId,
						'rev_page_title' => (string)$page,
						'page_latest' => $masterLatest,
						'rev_id' => $revId,
						'exception' => new RuntimeException(),
					] + $context
				);
			}
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable getPageByName/newFromID should not return null
		return $page;
	}

	/**
	 * Construct a RevisionRecord instance for each row in $rows,
	 * and return them as an associative array indexed by revision ID.
	 * Use RevisionSelectQueryBuilder or getArchiveQueryInfo() to construct the
	 * query that produces the rows.
	 *
	 * @param IResultWrapper|\stdClass[] $rows the rows to construct revision records from
	 * @param array $options Supports the following options:
	 *               'slots' - whether metadata about revision slots should be
	 *               loaded immediately. Supports falsy or truthy value as well
	 *               as an explicit list of slot role names. The main slot will
	 *               always be loaded.
	 *               'content' - whether the actual content of the slots should be
	 *               preloaded.
	 *               'archive' - whether the rows where generated using getArchiveQueryInfo(),
	 *                           rather than getQueryInfo.
	 * @param int $queryFlags
	 * @param PageIdentity|null $page The page to which all the revision rows belong, if there
	 *        is such a page and the caller has it handy, so we don't have to look it up again.
	 *        If this parameter is given and any of the rows has a rev_page_id that is different
	 *        from Article Id associated with the page, an InvalidArgumentException is thrown.
	 *
	 * @return StatusValue<array<int,?RevisionRecord>> a status with an array of successfully fetched revisions
	 *                     (keyed by revision IDs) and with warnings for the revisions failed to fetch.
	 */
	public function newRevisionsFromBatch(
		$rows,
		array $options = [],
		$queryFlags = 0,
		?PageIdentity $page = null
	) {
		$result = new StatusValue();
		$archiveMode = $options['archive'] ?? false;

		if ( $archiveMode ) {
			$revIdField = 'ar_rev_id';
		} else {
			$revIdField = 'rev_id';
		}

		$rowsByRevId = [];
		$pageIdsToFetchTitles = [];
		$titlesByPageKey = [];
		foreach ( $rows as $row ) {
			if ( isset( $rowsByRevId[$row->$revIdField] ) ) {
				$result->warning(
					'internalerror_info',
					"Duplicate rows in newRevisionsFromBatch, $revIdField {$row->$revIdField}"
				);
			}

			// Attach a page key to the row, so we can find and reuse Title objects easily.
			$row->_page_key =
				$archiveMode ? $row->ar_namespace . ':' . $row->ar_title : $row->rev_page;

			if ( $page ) {
				if ( !$archiveMode && $row->rev_page != $this->getArticleId( $page ) ) {
					throw new InvalidArgumentException(
						"Revision {$row->$revIdField} doesn't belong to page "
							. $this->getArticleId( $page )
					);
				}

				if ( $archiveMode
					&& ( $row->ar_namespace != $page->getNamespace()
						|| $row->ar_title !== $page->getDBkey() )
				) {
					throw new InvalidArgumentException(
						"Revision {$row->$revIdField} doesn't belong to page "
							. $page
					);
				}
			} elseif ( !isset( $titlesByPageKey[ $row->_page_key ] ) ) {
				if ( isset( $row->page_namespace ) && isset( $row->page_title )
					// This should always be true, but just in case we don't have a page_id
					// set or it doesn't match rev_page, let's fetch the title again.
					&& isset( $row->page_id ) && isset( $row->rev_page )
					&& $row->rev_page === $row->page_id
				) {
					$titlesByPageKey[ $row->_page_key ] = Title::newFromRow( $row );
				} elseif ( $archiveMode ) {
					// Can't look up deleted pages by ID, but we have namespace and title
					$titlesByPageKey[ $row->_page_key ] =
						Title::makeTitle( $row->ar_namespace, $row->ar_title );
				} else {
					$pageIdsToFetchTitles[] = $row->rev_page;
				}
			}
			$rowsByRevId[$row->$revIdField] = $row;
		}

		if ( !$rowsByRevId ) {
			$result->setResult( true, [] );
			return $result;
		}

		// If the page is not supplied, batch-fetch Title objects.
		if ( $page ) {
			// same logic as for $row->_page_key above
			$pageKey = $archiveMode
				? $page->getNamespace() . ':' . $page->getDBkey()
				: $this->getArticleId( $page );

			$titlesByPageKey[$pageKey] = $page;
		} elseif ( $pageIdsToFetchTitles ) {
			// Note: when we fetch titles by ID, the page key is also the ID.
			// We should never get here if $archiveMode is true.
			Assert::invariant( !$archiveMode, 'Titles are not loaded by ID in archive mode.' );

			$pageIdsToFetchTitles = array_unique( $pageIdsToFetchTitles );
			$pageRecords = $this->pageStore
				->newSelectQueryBuilder()
				->wherePageIds( $pageIdsToFetchTitles )
				->caller( __METHOD__ )
				->fetchPageRecordArray();
			// Cannot array_merge because it re-indexes entries
			$titlesByPageKey = $pageRecords + $titlesByPageKey;
		}

		// which method to use for creating RevisionRecords
		$newRevisionRecord = $archiveMode
			? $this->newRevisionFromArchiveRowAndSlots( ... )
			: $this->newRevisionFromRowAndSlots( ... );

		if ( !isset( $options['slots'] ) ) {
			$result->setResult(
				true,
				array_map(
					static function ( $row )
					use ( $queryFlags, $titlesByPageKey, $result, $newRevisionRecord, $revIdField ) {
						try {
							if ( !isset( $titlesByPageKey[$row->_page_key] ) ) {
								$result->warning(
									'internalerror_info',
									"Couldn't find title for rev {$row->$revIdField} "
									. "(page key {$row->_page_key})"
								);
								return null;
							}
							return $newRevisionRecord( $row, null, $queryFlags,
								$titlesByPageKey[ $row->_page_key ] );
						} catch ( MWException $e ) {
							$result->warning( 'internalerror_info', $e->getMessage() );
							return null;
						}
					},
					$rowsByRevId
				)
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

		$result->setResult(
			true,
			array_map(
				function ( $row )
				use ( $slotRowsByRevId, $queryFlags, $titlesByPageKey, $result,
					$revIdField, $newRevisionRecord
				) {
					if ( !isset( $slotRowsByRevId[$row->$revIdField] ) ) {
						$result->warning(
							'internalerror_info',
							"Couldn't find slots for rev {$row->$revIdField}"
						);
						return null;
					}
					if ( !isset( $titlesByPageKey[$row->_page_key] ) ) {
						$result->warning(
							'internalerror_info',
							"Couldn't find title for rev {$row->$revIdField} "
								. "(page key {$row->_page_key})"
						);
						return null;
					}
					try {
						return $newRevisionRecord(
							$row,
							new RevisionSlots(
								$this->constructSlotRecords(
									$row->$revIdField,
									$slotRowsByRevId[$row->$revIdField],
									$queryFlags,
									$titlesByPageKey[$row->_page_key]
								)
							),
							$queryFlags,
							$titlesByPageKey[$row->_page_key]
						);
					} catch ( MWException $e ) {
						$result->warning( 'internalerror_info', $e->getMessage() );
						return null;
					}
				},
				$rowsByRevId
			)
		);
		return $result;
	}

	/**
	 * Gets the slot rows associated with a batch of revisions.
	 * The serialized content of each slot can be included by setting the 'blobs' option.
	 * Callers are responsible for unserializing and interpreting the content blobs
	 * based on the model_name and role_name fields.
	 *
	 * @param Traversable|array $rowsOrIds list of revision ids, or revision or archive rows
	 *        from a db query.
	 * @param array{slots?: string[], blobs?: bool} $options Supports the following options:
	 *               'slots' - a list of slot role names to fetch. If omitted or true or null,
	 *                         all slots are fetched
	 *               'blobs' - whether the serialized content of each slot should be loaded.
	 *                        If true, the serialized content will be present in the slot row
	 *                        in the blob_data field.
	 * @param int $queryFlags
	 *
	 * @return StatusValue<array<int,array<string,stdClass>>>
	 *         a status containing, if isOK() returns true, a two-level nested
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
		$result = new StatusValue();

		$revIds = [];
		foreach ( $rowsOrIds as $id ) {
			if ( $id instanceof stdClass ) {
				$id = $id->ar_rev_id ?? $id->rev_id;
			}
			$revIds[] = (int)$id;
		}

		// Nothing to do.
		// Note that $rowsOrIds may not be "empty" even if $revIds is, e.g. if it's a ResultWrapper.
		if ( !$revIds ) {
			$result->setResult( true, [] );
			return $result;
		}

		// We need to set the `content` flag to join in content meta-data
		$slotQueryInfo = $this->getSlotsQueryInfo( [ 'content' ] );
		$revIdField = $slotQueryInfo['keys']['rev_id'];
		$slotQueryConds = [ $revIdField => $revIds ];

		if ( isset( $options['slots'] ) && is_array( $options['slots'] ) ) {
			$slotIds = [];
			foreach ( $options['slots'] as $slot ) {
				try {
					$slotIds[] = $this->slotRoleStore->getId( $slot );
				} catch ( NameTableAccessException ) {
					// Do not fail when slot has no id (unused slot)
					// This also means for this slot are never data in the database
				}
			}
			if ( $slotIds === [] ) {
				// Degenerate case: return no slots for each revision.
				$result->setResult( true, array_fill_keys( $revIds, [] ) );
				return $result;
			}

			$roleIdField = $slotQueryInfo['keys']['role_id'];
			$slotQueryConds[$roleIdField] = $slotIds;
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$slotRows = $db->newSelectQueryBuilder()
			->queryInfo( $slotQueryInfo )
			->where( $slotQueryConds )
			->caller( __METHOD__ )
			->fetchResultSet();

		$slotContents = null;
		if ( $options['blobs'] ?? false ) {
			$blobAddresses = [];
			foreach ( $slotRows as $slotRow ) {
				$blobAddresses[] = $slotRow->content_address;
			}
			$slotContentFetchStatus = $this->blobStore
				->getBlobBatch( $blobAddresses, $queryFlags );
			foreach ( $slotContentFetchStatus->getMessages() as $msg ) {
				$result->warning( $msg );
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
					'internalerror_info',
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
	 * @param string[]|null $slots the role names for which to get slots.
	 * @param int $queryFlags
	 *
	 * @return StatusValue<array<int,array<string,stdClass>>>
	 *         a status containing, if isOK() returns true, a two-level nested
	 *         associative array, mapping from revision ID to an associative array that maps from
	 *         role name to an anonymous object containing two fields:
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
	 * Given a set of conditions, fetch a revision
	 *
	 * This method should be used if we are pretty sure the revision exists.
	 * Unless $flags has READ_LATEST set, this method will first try to find the revision
	 * on a replica before hitting the primary database.
	 *
	 * MCR migration note: this corresponded to Revision::newFromConds
	 *
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @param PageIdentity|null $page (optional)
	 * @param array $options (optional) additional query options
	 *
	 * @return RevisionRecord|null
	 */
	private function newRevisionFromConds(
		array $conditions,
		int $flags = IDBAccessObject::READ_NORMAL,
		?PageIdentity $page = null,
		array $options = []
	) {
		$db = $this->getDBConnectionRefForQueryFlags( $flags );
		$rev = $this->loadRevisionFromConds( $db, $conditions, $flags, $page, $options );

		// Make sure new pending/committed revision are visible later on
		// within web requests to certain avoid bugs like T93866 and T94407.
		if ( !$rev
			&& !( $flags & IDBAccessObject::READ_LATEST )
			&& $this->loadBalancer->hasStreamingReplicaServers()
			&& $this->loadBalancer->hasOrMadeRecentPrimaryChanges()
		) {
			$flags = IDBAccessObject::READ_LATEST;
			$dbw = $this->getPrimaryConnection();
			$rev = $this->loadRevisionFromConds( $dbw, $conditions, $flags, $page, $options );
		}

		return $rev;
	}

	/**
	 * Given a set of conditions, fetch a revision from
	 * the given database connection.
	 *
	 * MCR migration note: this corresponded to Revision::loadFromConds
	 *
	 * @param IReadableDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @param PageIdentity|null $page (optional) additional query options
	 * @param array $options (optional) additional query options
	 *
	 * @return RevisionRecord|null
	 */
	private function loadRevisionFromConds(
		IReadableDatabase $db,
		array $conditions,
		int $flags = IDBAccessObject::READ_NORMAL,
		?PageIdentity $page = null,
		array $options = []
	) {
		$row = $this->fetchRevisionRowFromConds( $db, $conditions, $flags, $options );
		if ( $row ) {
			return $this->newRevisionFromRow( $row, $flags, $page );
		}

		return null;
	}

	/**
	 * Throws an exception if the given database connection does not belong to the wiki this
	 * RevisionStore is bound to.
	 */
	private function checkDatabaseDomain( IReadableDatabase $db ) {
		$dbDomain = $db->getDomainID();
		$storeDomain = $this->loadBalancer->resolveDomainID( $this->wikiId );
		if ( $dbDomain === $storeDomain ) {
			return;
		}

		throw new RuntimeException( "DB connection domain '$dbDomain' does not match '$storeDomain'" );
	}

	/**
	 * Given a set of conditions, return a row with the
	 * fields necessary to build RevisionRecord objects.
	 *
	 * MCR migration note: this corresponded to Revision::fetchFromConds
	 *
	 * @param IReadableDatabase $db
	 * @param array $conditions
	 * @param int $flags (optional)
	 * @param array $options (optional) additional query options
	 *
	 * @return \stdClass|false data row as a raw object
	 */
	private function fetchRevisionRowFromConds(
		IReadableDatabase $db,
		array $conditions,
		int $flags = IDBAccessObject::READ_NORMAL,
		array $options = []
	) {
		$this->checkDatabaseDomain( $db );

		$queryBuilder = $this->newSelectQueryBuilder( $db )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( $conditions )
			->options( $options );
		if ( ( $flags & IDBAccessObject::READ_LOCKING ) == IDBAccessObject::READ_LOCKING ) {
			$queryBuilder->forUpdate();
		}
		return $queryBuilder->caller( __METHOD__ )->fetchRow();
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionStoreRecord object.
	 *
	 * MCR migration note: this replaced Revision::getQueryInfo
	 *
	 * If the format of fields returned changes in any way then the cache key provided by
	 * self::getRevisionRowCacheKey should be updated.
	 *
	 * @since 1.31
	 * @deprecated since 1.41 use RevisionStore::newSelectQueryBuilder() instead.
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *
	 * @return array[] With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getQueryInfo( $options = [] ) {
		$ret = [
			'tables' => [
				'revision',
				'actor_rev_user' => 'actor',
			],
			'fields' => [
				'rev_id',
				'rev_page',
				'rev_actor' => 'rev_actor',
				'rev_user' => 'actor_rev_user.actor_user',
				'rev_user_text' => 'actor_rev_user.actor_name',
				'rev_timestamp',
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
			],
			'joins' => [
				'actor_rev_user' => [ 'JOIN', "actor_rev_user.actor_id = rev_actor" ],
			]
		];

		$commentQuery = $this->commentStore->getJoin( 'rev_comment' );
		$ret['tables'] = array_merge( $ret['tables'], $commentQuery['tables'] );
		$ret['fields'] = array_merge( $ret['fields'], $commentQuery['fields'] );
		$ret['joins'] = array_merge( $ret['joins'], $commentQuery['joins'] );

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
			$ret['fields'][] = 'user_name';
			$ret['joins']['user'] = [
				'LEFT JOIN',
				[ 'actor_rev_user.actor_user != 0', 'user_id = actor_rev_user.actor_user' ]
			];
		}

		if ( in_array( 'text', $options, true ) ) {
			throw new InvalidArgumentException(
				'The `text` option is no longer supported in MediaWiki 1.35 and later.'
			);
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 */
	public function newSelectQueryBuilder( IReadableDatabase $dbr ): RevisionSelectQueryBuilder {
		return new RevisionSelectQueryBuilder( $dbr );
	}

	/**
	 * @inheritDoc
	 */
	public function newArchiveSelectQueryBuilder( IReadableDatabase $dbr ): ArchiveSelectQueryBuilder {
		return new ArchiveSelectQueryBuilder( $dbr );
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
	 * @return array[] With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 *  - keys: (associative array) to look up fields to match against.
	 *          In particular, the field that can be used to find slots by rev_id
	 *          can be found in ['keys']['rev_id'].
	 * @phan-return array{tables:string[],fields:string[],joins:array,keys:array}
	 */
	public function getSlotsQueryInfo( $options = [] ) {
		$ret = [
			'tables' => [],
			'fields' => [],
			'joins'  => [],
			'keys'  => [],
		];

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

		return $ret;
	}

	/**
	 * Determine whether the parameter is a row containing all the fields
	 * that RevisionStore needs to create a RevisionRecord from the row.
	 *
	 * @param mixed $row
	 * @param string $table 'archive' or empty
	 * @return bool
	 */
	public function isRevisionRow( $row, string $table = '' ) {
		if ( !( $row instanceof stdClass ) ) {
			return false;
		}
		$queryInfo = $table === 'archive' ? $this->getArchiveQueryInfo() : $this->getQueryInfo();
		foreach ( $queryInfo['fields'] as $alias => $field ) {
			$name = is_numeric( $alias ) ? $field : $alias;
			if ( $name === 'rev_sha1' || $name === 'ar_sha1' ) {
				// Do not require rev_sha1 or ar_sha1 to be present, it will be dropped in the future - T389026
				continue;
			}
			if ( !property_exists( $row, $name ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionArchiveRecord object.
	 *
	 * Since 1.34, ar_user and ar_user_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * MCR migration note: this replaced Revision::getArchiveQueryInfo
	 *
	 * @since 1.31
	 * @deprecated since 1.41 use RevisionStore::newArchiveSelectQueryBuilder() instead.
	 *
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getArchiveQueryInfo() {
		$commentQuery = $this->commentStore->getJoin( 'ar_comment' );
		$ret = [
			'tables' => [
				'archive',
				'archive_actor' => 'actor'
			] + $commentQuery['tables'],
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
				'ar_actor',
				'ar_user' => 'archive_actor.actor_user',
				'ar_user_text' => 'archive_actor.actor_name',
			] + $commentQuery['fields'],
			'joins' => [
				'archive_actor' => [ 'JOIN', 'actor_id=ar_actor' ]
			] + $commentQuery['joins'],
		];

		return $ret;
	}

	/**
	 * Do a batched query for the sizes of a set of revisions.
	 *
	 * MCR migration note: this replaced Revision::getParentLengths
	 *
	 * @param int[] $revIds
	 * @return int[] associative array mapping revision IDs from $revIds to the nominal size
	 *         of the corresponding revision.
	 */
	public function getRevisionSizes( array $revIds ) {
		$dbr = $this->getReplicaConnection();
		$revLens = [];
		if ( !$revIds ) {
			return $revLens; // empty
		}

		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'rev_id', 'rev_len' ] )
			->from( 'revision' )
			->where( [ 'rev_id' => $revIds ] )
			->caller( __METHOD__ )->fetchResultSet();

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

		$revisionIdValue = $rev->getId( $this->wikiId );

		if ( !$revisionIdValue || !$rev->getPageId( $this->wikiId ) ) {
			// revision is unsaved or otherwise incomplete
			return null;
		}

		if ( $rev instanceof RevisionArchiveRecord ) {
			// revision is deleted, so it's not part of the page history
			return null;
		}

		$db = $this->getDBConnectionRefForQueryFlags( $flags );

		$ts = $rev->getTimestamp() ?? $this->getTimestampFromId( $revisionIdValue, $flags );
		if ( $ts === false ) {
			// XXX Should this be moved into getTimestampFromId?
			$ts = $db->newSelectQueryBuilder()
				->select( 'ar_timestamp' )
				->from( 'archive' )
				->where( [ 'ar_rev_id' => $revisionIdValue ] )
				->caller( __METHOD__ )->fetchField();
			if ( $ts === false ) {
				// XXX Is this reachable? How can we have a page id but no timestamp?
				return null;
			}
		}

		$revId = $db->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( [
				'rev_page' => $rev->getPageId( $this->wikiId ),
				$db->buildComparison( $op, [
					'rev_timestamp' => $db->timestamp( $ts ),
					'rev_id' => $revisionIdValue,
				] ),
			] )
			->orderBy( [ 'rev_timestamp', 'rev_id' ], $sort )
			->ignoreIndex( 'rev_timestamp' ) // Probably needed for T159319
			->caller( __METHOD__ )
			->fetchField();

		if ( $revId === false ) {
			return null;
		}

		return $this->getRevisionById( intval( $revId ), $flags );
	}

	/**
	 * Get the revision before $rev in the page's history, if any.
	 * Will return null for the first revision but also for deleted or unsaved revisions.
	 *
	 * MCR migration note: this replaced Revision::getPrevious
	 *
	 * @see PageArchive::getPreviousRevisionRecord
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev, $flags = IDBAccessObject::READ_NORMAL ) {
		return $this->getRelativeRevision( $rev, $flags, 'prev' );
	}

	/**
	 * Get the revision after $rev in the page's history, if any.
	 * Will return null for the latest revision but also for deleted or unsaved revisions.
	 *
	 * MCR migration note: this replaced Revision::getNext
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev, $flags = IDBAccessObject::READ_NORMAL ) {
		return $this->getRelativeRevision( $rev, $flags, 'next' );
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * MCR migration note: this corresponded to Revision::getPreviousRevisionId
	 *
	 * @param IReadableDatabase $db
	 * @param RevisionRecord $rev
	 *
	 * @return int
	 */
	private function getPreviousRevisionId( IReadableDatabase $db, RevisionRecord $rev ) {
		$this->checkDatabaseDomain( $db );

		if ( $rev->getPageId( $this->wikiId ) === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$rev->getId( $this->wikiId ) ) {
			$prevId = $db->newSelectQueryBuilder()
				->select( 'page_latest' )
				->from( 'page' )
				->where( [ 'page_id' => $rev->getPageId( $this->wikiId ) ] )
				->caller( __METHOD__ )->fetchField();
		} else {
			$prevId = $db->newSelectQueryBuilder()
				->select( 'rev_id' )
				->from( 'revision' )
				->where( [ 'rev_page' => $rev->getPageId( $this->wikiId ) ] )
				->andWhere( $db->expr( 'rev_id', '<', $rev->getId( $this->wikiId ) ) )
				->orderBy( 'rev_id DESC' )
				->caller( __METHOD__ )->fetchField();
		}
		return intval( $prevId );
	}

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row.
	 *
	 * Historically, there was an extra Title parameter that was passed before $id. This is no
	 * longer needed and is deprecated in 1.34.
	 *
	 * MCR migration note: this replaced Revision::getTimestampFromId
	 *
	 * @param int $id
	 * @param int $flags
	 * @return string|false False if not found
	 */
	public function getTimestampFromId( $id, $flags = 0 ) {
		if ( $id instanceof Title ) {
			// Old deprecated calling convention supported for backwards compatibility
			$id = $flags;
			$flags = func_num_args() > 2 ? func_get_arg( 2 ) : 0;
		}

		// T270149: Bail out if we know the query will definitely return false. Some callers are
		// passing RevisionRecord::getId() call directly as $id which can possibly return null.
		// Null $id or $id <= 0 will lead to useless query with WHERE clause of 'rev_id IS NULL'
		// or 'rev_id = 0', but 'rev_id' is always greater than zero and cannot be null.
		// @todo typehint $id and remove the null check
		if ( $id === null || $id <= 0 ) {
			return false;
		}

		$db = $this->getDBConnectionRefForQueryFlags( $flags );

		$timestamp =
			$db->newSelectQueryBuilder()
				->select( 'rev_timestamp' )
				->from( 'revision' )
				->where( [ 'rev_id' => $id ] )
				->caller( __METHOD__ )->fetchField();

		return ( $timestamp !== false ) ? MWTimestamp::convert( TS_MW, $timestamp ) : false;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * MCR migration note: this replaced Revision::countByPageId
	 *
	 * @param IReadableDatabase $db
	 * @param int $id Page id
	 * @return int
	 */
	public function countRevisionsByPageId( IReadableDatabase $db, $id ) {
		$this->checkDatabaseDomain( $db );

		$row = $db->newSelectQueryBuilder()
			->select( [ 'revCount' => 'COUNT(*)' ] )
			->from( 'revision' )
			->where( [ 'rev_page' => $id ] )
			->caller( __METHOD__ )->fetchRow();
		if ( $row ) {
			return intval( $row->revCount );
		}
		return 0;
	}

	/**
	 * Get count of revisions per page...not very efficient
	 *
	 * MCR migration note: this replaced Revision::countByTitle
	 *
	 * @param IReadableDatabase $db
	 * @param PageIdentity $page
	 * @return int
	 */
	public function countRevisionsByTitle( IReadableDatabase $db, PageIdentity $page ) {
		$id = $this->getArticleId( $page );
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
	 * MCR migration note: this replaced Revision::userWasLastToEdit
	 *
	 * @deprecated since 1.31; Can possibly be removed, since the self-conflict suppression
	 *       logic in EditPage that uses this seems conceptually dubious. Revision::userWasLastToEdit
	 *       had been deprecated since 1.24 (the Revision class was removed entirely in 1.37).
	 *
	 * @param IReadableDatabase $db The Database to perform the check on.
	 * @param int $pageId The ID of the page in question
	 * @param int $userId The ID of the user in question
	 * @param string $since Look at edits since this time
	 *
	 * @return bool True if the given user was the only one to edit since the given timestamp
	 */
	public function userWasLastToEdit( IReadableDatabase $db, $pageId, $userId, $since ) {
		$this->checkDatabaseDomain( $db );

		if ( !$userId ) {
			return false;
		}

		$queryBuilder = $this->newSelectQueryBuilder( $db )
			->where( [
				'rev_page' => $pageId,
				$db->expr( 'rev_timestamp', '>', $db->timestamp( $since ) )
			] )
			->orderBy( 'rev_timestamp', SelectQueryBuilder::SORT_ASC )
			->limit( 50 );
		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
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
	 * MCR migration note: this replaced Revision::newKnownCurrent
	 *
	 * @param PageIdentity $page the associated page
	 * @param int $revId current revision of this page. Defaults to $title->getLatestRevID().
	 *
	 * @return RevisionRecord|false Returns false if missing
	 */
	public function getKnownCurrentRevision( PageIdentity $page, $revId = 0 ) {
		$db = $this->getReplicaConnection();
		$revIdPassed = $revId;
		$pageId = $this->getArticleId( $page );
		if ( !$pageId ) {
			return false;
		}

		if ( !$revId ) {
			if ( $page instanceof Title ) {
				$revId = $page->getLatestRevID();
			} else {
				$pageRecord = $this->pageStore->getPageByReference( $page );
				if ( $pageRecord ) {
					$revId = $pageRecord->getLatest( $this->getWikiId() );
				}
			}
		}

		if ( !$revId ) {
			$this->logger->warning(
				'No latest revision known for page {page} even though it exists with page ID {page_id}', [
					'page' => $page->__toString(),
					'page_id' => $pageId,
					'wiki_id' => $this->getWikiId() ?: 'local',
				] );
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
				$db, $revId, &$fromCache
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
			$title = $this->ensureRevisionRowMatchesPage( $row, $page, [
				'from_cache_flag' => $fromCache,
				'page_id_initial' => $pageId,
				'rev_id_used' => $revId,
				'rev_id_requested' => $revIdPassed,
			] );

			return $this->newRevisionFromRow( $row, 0, $title, $fromCache );
		} else {
			return false;
		}
	}

	/**
	 * Get the first revision of a given page.
	 *
	 * @since 1.35
	 * @param LinkTarget|PageReference $page Calling with LinkTarget is deprecated since 1.36
	 * @param int $flags
	 * @return RevisionRecord|null
	 */
	public function getFirstRevision(
		$page,
		int $flags = IDBAccessObject::READ_NORMAL
	): ?RevisionRecord {
		$conds = $this->getPageConditions( $page );
		if ( !$conds ) {
			return null;
		}
		if ( !( $page instanceof PageIdentity ) ) {
			if ( !( $page instanceof PageReference ) ) {
				wfDeprecated( __METHOD__ . ' with a LinkTarget', '1.45' );
			}
			$page = null;
		}

		return $this->newRevisionFromConds( $conds, $flags, $page,
			[
				'ORDER BY' => [ 'rev_timestamp ASC', 'rev_id ASC' ],
				'IGNORE INDEX' => [ 'revision' => 'rev_timestamp' ], // See T159319
			]
		);
	}

	/**
	 * Get a cache key for use with a row as selected with getQueryInfo( [ 'page', 'user' ] )
	 * Caching rows without 'page' or 'user' could lead to issues.
	 * If the format of the rows returned by the query provided by getQueryInfo changes the
	 * cache key should be updated to avoid conflicts.
	 *
	 * @param IReadableDatabase $db
	 * @param int $pageId
	 * @param int $revId
	 * @return string
	 */
	private function getRevisionRowCacheKey( IReadableDatabase $db, $pageId, $revId ) {
		return $this->cache->makeGlobalKey(
			self::ROW_CACHE_KEY,
			$db->getDomainID(),
			$pageId,
			$revId
		);
	}

	/**
	 * Asserts that if revision is provided, it's saved and belongs to the page with provided pageId.
	 * @param string $paramName
	 * @param int $pageId
	 * @param RevisionRecord|null $rev
	 */
	private function assertRevisionParameter( $paramName, $pageId, ?RevisionRecord $rev = null ) {
		if ( $rev ) {
			if ( $rev->getId( $this->wikiId ) === null ) {
				throw new InvalidArgumentException( "Unsaved {$paramName} revision passed" );
			}
			if ( $rev->getPageId( $this->wikiId ) !== $pageId ) {
				throw new InvalidArgumentException(
					"Revision {$rev->getId( $this->wikiId )} doesn't belong to page {$pageId}"
				);
			}
		}
	}

	/**
	 * @param LinkTarget|PageReference $page Calling with LinkTarget is deprecated since 1.36
	 * @return array|null
	 */
	private function getPageConditions( LinkTarget|PageReference $page ): ?array {
		if ( $page instanceof PageIdentity ) {
			return $page->exists() ? [ 'page_id' => $page->getId( $this->wikiId ) ] : null;
		} elseif ( $page instanceof PageReference ) {
			if ( $page->getWikiId() !== $this->wikiId ) {
				throw new InvalidArgumentException( 'Non-matching wiki ID for PageReference' );
			}
			return [
				'page_namespace' => $page->getNamespace(),
				'page_title' => $page->getDBkey(),
			];
		} else {
			// Only resolve LinkTarget when operating in the context of the local wiki (T248756)
			if ( $this->wikiId !== WikiAwareEntity::LOCAL ) {
				throw new InvalidArgumentException( 'Cannot use non-local LinkTarget' );
			}
			return [
				'page_namespace' => $page->getNamespace(),
				'page_title' => $page->getDBkey(),
			];
		}
	}

	/**
	 * Converts revision limits to query conditions.
	 *
	 * @param ISQLPlatform $dbr
	 * @param RevisionRecord|null $old Old revision.
	 *  If null is provided, count starting from the first revision (inclusive).
	 * @param RevisionRecord|null $new New revision.
	 *  If null is provided, count until the last revision (inclusive).
	 * @param string|array $options Single option, or an array of options:
	 *     RevisionStore::INCLUDE_OLD Include $old in the range; $new is excluded.
	 *     RevisionStore::INCLUDE_NEW Include $new in the range; $old is excluded.
	 *     RevisionStore::INCLUDE_BOTH Include both $old and $new in the range.
	 * @return array
	 */
	private function getRevisionLimitConditions(
		ISQLPlatform $dbr,
		?RevisionRecord $old = null,
		?RevisionRecord $new = null,
		$options = []
	) {
		$options = (array)$options;
		if ( in_array( self::INCLUDE_OLD, $options ) || in_array( self::INCLUDE_BOTH, $options ) ) {
			$oldCmp = '>=';
		} else {
			$oldCmp = '>';
		}
		if ( in_array( self::INCLUDE_NEW, $options ) || in_array( self::INCLUDE_BOTH, $options ) ) {
			$newCmp = '<=';
		} else {
			$newCmp = '<';
		}

		$conds = [];
		if ( $old ) {
			$conds[] = $dbr->buildComparison( $oldCmp, [
				'rev_timestamp' => $dbr->timestamp( $old->getTimestamp() ),
				'rev_id' => $old->getId( $this->wikiId ),
			] );
		}
		if ( $new ) {
			$conds[] = $dbr->buildComparison( $newCmp, [
				'rev_timestamp' => $dbr->timestamp( $new->getTimestamp() ),
				'rev_id' => $new->getId( $this->wikiId ),
			] );
		}
		return $conds;
	}

	/**
	 * Get IDs of revisions between the given revisions.
	 *
	 * @since 1.36
	 *
	 * @param int $pageId The id of the page
	 * @param RevisionRecord|null $old Old revision.
	 *  If null is provided, count starting from the first revision (inclusive).
	 * @param RevisionRecord|null $new New revision.
	 *  If null is provided, count until the last revision (inclusive).
	 * @param int|null $max Limit of Revisions to count, will be incremented by
	 *  one to detect truncations.
	 * @param string|array $options Single option, or an array of options:
	 *     RevisionStore::INCLUDE_OLD Include $old in the range; $new is excluded.
	 *     RevisionStore::INCLUDE_NEW Include $new in the range; $old is excluded.
	 *     RevisionStore::INCLUDE_BOTH Include both $old and $new in the range.
	 * @param string|null $order The direction in which the revisions should be sorted.
	 *  Possible values:
	 *   - RevisionStore::ORDER_OLDEST_TO_NEWEST
	 *   - RevisionStore::ORDER_NEWEST_TO_OLDEST
	 *   - null for no specific ordering (default value)
	 * @param int $flags
	 * @throws InvalidArgumentException in case either revision is unsaved or
	 *  the revisions do not belong to the same page or unknown option is passed.
	 * @return int[]
	 */
	public function getRevisionIdsBetween(
		int $pageId,
		?RevisionRecord $old = null,
		?RevisionRecord $new = null,
		?int $max = null,
		$options = [],
		?string $order = null,
		int $flags = IDBAccessObject::READ_NORMAL
	): array {
		$this->assertRevisionParameter( 'old', $pageId, $old );
		$this->assertRevisionParameter( 'new', $pageId, $new );

		$options = (array)$options;
		$includeOld = in_array( self::INCLUDE_OLD, $options ) ||
			in_array( self::INCLUDE_BOTH, $options );
		$includeNew = in_array( self::INCLUDE_NEW, $options ) ||
			in_array( self::INCLUDE_BOTH, $options );

		// No DB query needed if old and new are the same revision.
		// Can't check for consecutive revisions with 'getParentId' for a similar
		// optimization as edge cases exist when there are revisions between
		// a revision and it's parent. See T185167 for more details.
		if ( $old && $new && $new->getId( $this->wikiId ) === $old->getId( $this->wikiId ) ) {
			return $includeOld || $includeNew ? [ $new->getId( $this->wikiId ) ] : [];
		}

		$db = $this->getDBConnectionRefForQueryFlags( $flags );
		$queryBuilder = $db->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( [
				'rev_page' => $pageId,
				$db->bitAnd( 'rev_deleted', RevisionRecord::DELETED_TEXT ) . ' = 0'
			] )
			->andWhere( $this->getRevisionLimitConditions( $db, $old, $new, $options ) );

		if ( $order !== null ) {
			$queryBuilder->orderBy( [ 'rev_timestamp', 'rev_id' ], $order );
		}
		if ( $max !== null ) {
			// extra to detect truncation
			$queryBuilder->limit( $max + 1 );
		}

		$values = $queryBuilder->caller( __METHOD__ )->fetchFieldValues();
		return array_map( 'intval', $values );
	}

	/**
	 * Get the authors between the given revisions or revisions.
	 * Used for diffs and other things that really need it.
	 *
	 * @since 1.35
	 *
	 * @param int $pageId The id of the page
	 * @param RevisionRecord|null $old Old revision.
	 *  If null is provided, count starting from the first revision (inclusive).
	 * @param RevisionRecord|null $new New revision.
	 *  If null is provided, count until the last revision (inclusive).
	 * @param Authority|null $performer the user whose access rights to apply
	 * @param int|null $max Limit of Revisions to count, will be incremented to detect truncations.
	 * @param string|array $options Single option, or an array of options:
	 *     RevisionStore::INCLUDE_OLD Include $old in the range; $new is excluded.
	 *     RevisionStore::INCLUDE_NEW Include $new in the range; $old is excluded.
	 *     RevisionStore::INCLUDE_BOTH Include both $old and $new in the range.
	 * @throws InvalidArgumentException in case either revision is unsaved or
	 *  the revisions do not belong to the same page or unknown option is passed.
	 * @return UserIdentity[] Names of revision authors in the range
	 */
	public function getAuthorsBetween(
		$pageId,
		?RevisionRecord $old = null,
		?RevisionRecord $new = null,
		?Authority $performer = null,
		$max = null,
		$options = []
	) {
		$this->assertRevisionParameter( 'old', $pageId, $old );
		$this->assertRevisionParameter( 'new', $pageId, $new );
		$options = (array)$options;

		// No DB query needed if old and new are the same revision.
		// Can't check for consecutive revisions with 'getParentId' for a similar
		// optimization as edge cases exist when there are revisions between
		//a revision and it's parent. See T185167 for more details.
		if ( $old && $new && $new->getId( $this->wikiId ) === $old->getId( $this->wikiId ) ) {
			if ( !$options ) {
				return [];
			} elseif ( $performer ) {
				return [ $new->getUser( RevisionRecord::FOR_THIS_USER, $performer ) ];
			} else {
				return [ $new->getUser() ];
			}
		}

		$dbr = $this->getReplicaConnection();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [
				'rev_actor',
				'rev_user' => 'revision_actor.actor_user',
				'rev_user_text' => 'revision_actor.actor_name',
			] )
			->distinct()
			->from( 'revision' )
			->join( 'actor', 'revision_actor', 'revision_actor.actor_id = rev_actor' )
			->where( [
				'rev_page' => $pageId,
				$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . " = 0"
			] )
			->andWhere( $this->getRevisionLimitConditions( $dbr, $old, $new, $options ) )
			->caller( __METHOD__ );
		if ( $max !== null ) {
			$queryBuilder->limit( $max + 1 );
		}

		return array_map(
			function ( $row ) {
				return $this->actorStore->newActorFromRowFields(
					$row->rev_user,
					$row->rev_user_text,
					$row->rev_actor
				);
			},
			iterator_to_array( $queryBuilder->fetchResultSet() )
		);
	}

	/**
	 * Get the number of authors between the given revisions.
	 * Used for diffs and other things that really need it.
	 *
	 * @since 1.35
	 *
	 * @param int $pageId The id of the page
	 * @param RevisionRecord|null $old Old revision .
	 *  If null is provided, count starting from the first revision (inclusive).
	 * @param RevisionRecord|null $new New revision.
	 *  If null is provided, count until the last revision (inclusive).
	 * @param Authority|null $performer the user whose access rights to apply
	 * @param int|null $max Limit of Revisions to count, will be incremented to detect truncations.
	 * @param string|array $options Single option, or an array of options:
	 *     RevisionStore::INCLUDE_OLD Include $old in the range; $new is excluded.
	 *     RevisionStore::INCLUDE_NEW Include $new in the range; $old is excluded.
	 *     RevisionStore::INCLUDE_BOTH Include both $old and $new in the range.
	 * @throws InvalidArgumentException in case either revision is unsaved or
	 *  the revisions do not belong to the same page or unknown option is passed.
	 * @return int Number of revisions authors in the range.
	 */
	public function countAuthorsBetween(
		$pageId,
		?RevisionRecord $old = null,
		?RevisionRecord $new = null,
		?Authority $performer = null,
		$max = null,
		$options = []
	) {
		// TODO: Implement with a separate query to avoid cost of selecting unneeded fields
		// and creation of UserIdentity stuff.
		return count( $this->getAuthorsBetween( $pageId, $old, $new, $performer, $max, $options ) );
	}

	/**
	 * Get the number of revisions between the given revisions.
	 * Used for diffs and other things that really need it.
	 *
	 * @since 1.35
	 *
	 * @param int $pageId The id of the page
	 * @param RevisionRecord|null $old Old revision.
	 *  If null is provided, count starting from the first revision (inclusive).
	 * @param RevisionRecord|null $new New revision.
	 *  If null is provided, count until the last revision (inclusive).
	 * @param int|null $max Limit of Revisions to count, will be incremented to detect truncations.
	 * @param string|array $options Single option, or an array of options:
	 *     RevisionStore::INCLUDE_OLD Include $old in the range; $new is excluded.
	 *     RevisionStore::INCLUDE_NEW Include $new in the range; $old is excluded.
	 *     RevisionStore::INCLUDE_BOTH Include both $old and $new in the range.
	 * @throws InvalidArgumentException in case either revision is unsaved or
	 *  the revisions do not belong to the same page.
	 * @return int Number of revisions between these revisions.
	 */
	public function countRevisionsBetween(
		$pageId,
		?RevisionRecord $old = null,
		?RevisionRecord $new = null,
		$max = null,
		$options = []
	) {
		$this->assertRevisionParameter( 'old', $pageId, $old );
		$this->assertRevisionParameter( 'new', $pageId, $new );

		// No DB query needed if old and new are the same revision.
		// Can't check for consecutive revisions with 'getParentId' for a similar
		// optimization as edge cases exist when there are revisions between
		//a revision and it's parent. See T185167 for more details.
		if ( $old && $new && $new->getId( $this->wikiId ) === $old->getId( $this->wikiId ) ) {
			return 0;
		}

		$dbr = $this->getReplicaConnection();
		$conds = [
			'rev_page' => $pageId,
			$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_TEXT ) . ' = 0',
			...$this->getRevisionLimitConditions( $dbr, $old, $new, $options ),
		];
		if ( $max !== null ) {
			return $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'revision' )
				->where( $conds )
				->caller( __METHOD__ )
				->limit( $max + 1 ) // extra to detect truncation
				->fetchRowCount();
		} else {
			return (int)$dbr->newSelectQueryBuilder()
				->select( 'count(*)' )
				->from( 'revision' )
				->where( $conds )
				->caller( __METHOD__ )->fetchField();
		}
	}

	/**
	 * Tries to find a revision identical to $revision in $searchLimit most recent revisions
	 * of this page. The comparison is based on SHA1s of these revisions.
	 *
	 * @since 1.37
	 *
	 * @param RevisionRecord $revision which revision to compare to
	 * @param int $searchLimit How many recent revisions should be checked
	 *
	 * @return RevisionRecord|null
	 */
	public function findIdenticalRevision(
		RevisionRecord $revision,
		int $searchLimit
	): ?RevisionRecord {
		$revision->assertWiki( $this->wikiId );
		$db = $this->getReplicaConnection();

		// Build the target slot role ID => sha1 array from the provided revision
		// (this is seemingly necessary *before* fetching the candidate revisions: T406027)
		$searchedSlotHashes = [];
		$slots = $revision->getSlots()->getPrimarySlots();
		foreach ( $slots as $slot ) {
			$roleId = $this->slotRoleStore->acquireId( $slot->getRole() );
			$searchedSlotHashes[$roleId] = $slot->getSha1();
		}
		ksort( $searchedSlotHashes );

		// First fetch the IDs of the most recent revisions for this page, limited by the search limit.
		$candidateRevIds = $db->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( [ 'rev_page' => $revision->getPageId( $this->wikiId ) ] )
			// Include 'rev_id' in the ordering in case there are multiple revs with same timestamp
			->orderBy( [ 'rev_timestamp', 'rev_id' ], SelectQueryBuilder::SORT_DESC )
			// T354015
			->useIndex( [ 'revision' => 'rev_page_timestamp' ] )
			->limit( $searchLimit )
			// skip the most recent edit, we can't revert to it anyway
			->offset( 1 )
			->caller( __METHOD__ )
			->fetchFieldValues();

		if ( $candidateRevIds === [] ) {
			return null;
		}

		// Then, for only those revisions, fetch their slot role ID => sha1 data.
		$candidateRevisions = $db->newSelectQueryBuilder()
			->select( [ 'slot_revision_id', 'slot_role_id', 'content_sha1' ] )
			->from( 'slots' )
			->join( 'content', null, 'content_id = slot_content_id' )
			->where( [ 'slot_revision_id' => $candidateRevIds ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		// Build a map of candidate revisions to their slot role ID => sha1 arrays
		$candidateSlotHashes = [];
		foreach ( $candidateRevisions as $candidate ) {
			$candidateSlotHashes[$candidate->slot_revision_id][$candidate->slot_role_id] = $candidate->content_sha1;
		}

		// Find the first revision that has the same slot role ID => sha1 array as the provided revision.
		// We use $candidateRevIds, which are ordered by the revision timestamps, to ensure we return
		// the most recent revision that matches.
		$matchRevId = null;
		foreach ( $candidateRevIds as $revId ) {
			ksort( $candidateSlotHashes[$revId] );
			if ( $candidateSlotHashes[$revId] === $searchedSlotHashes ) {
				$matchRevId = $revId;
				break;
			}
		}

		$revisionRow = null;
		if ( $matchRevId !== null ) {
			$revisionRow = $this->newSelectQueryBuilder( $db )
				->joinComment()
				->where( [ 'rev_id' => $matchRevId ] )
				->caller( __METHOD__ )
				->fetchRow();
		}

		return $revisionRow ? $this->newRevisionFromRow( $revisionRow ) : null;
	}

	// TODO: move relevant methods from Title here, e.g. isBigDeletion, etc.
}
