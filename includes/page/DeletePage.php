<?php

namespace MediaWiki\Page;

use BadMethodCallException;
use BagOStuff;
use ChangeTags;
use CommentStore;
use Content;
use DeferrableUpdate;
use DeferredUpdates;
use DeletePageJob;
use Exception;
use JobQueueGroup;
use LinksDeletionUpdate;
use LinksUpdate;
use LogicException;
use ManualLogEntry;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserFactory;
use Message;
use RawMessage;
use ResourceLoaderWikiModule;
use SearchUpdate;
use SiteStatsUpdate;
use Status;
use StatusValue;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use WikiPage;

/**
 * @since 1.37
 * @package MediaWiki\Page
 */
class DeletePage {
	/**
	 * @internal For use by PageCommandFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'DeleteRevisionsBatchSize',
		'ActorTableSchemaMigrationStage',
		'DeleteRevisionsLimit',
	];

	/** @var HookRunner */
	private $hookRunner;
	/** @var RevisionStore */
	private $revisionStore;
	/** @var LBFactory */
	private $lbFactory;
	/** @var ILoadBalancer */
	private $loadBalancer;
	/** @var JobQueueGroup */
	private $jobQueueGroup;
	/** @var CommentStore */
	private $commentStore;
	/** @var ServiceOptions */
	private $options;
	/** @var BagOStuff */
	private $recentDeletesCache;
	/** @var string */
	private $localWikiID;
	/** @var string */
	private $webRequestID;
	/** @var UserFactory */
	private $userFactory;
	/** @var BacklinkCacheFactory */
	private $backlinkCacheFactory;

	/** @var bool */
	private $isDeletePageUnitTest = false;

	/** @var WikiPage */
	private $page;
	/** @var Authority */
	private $deleter;

	/** @var bool */
	private $suppress = false;
	/** @var string[] */
	private $tags = [];
	/** @var string */
	private $logSubtype = 'delete';
	/** @var bool */
	private $forceImmediate = false;

	/** @var string|array */
	private $legacyHookErrors = '';
	/** @var bool */
	private $mergeLegacyHookErrors = true;

	/** @var int[]|null */
	private $successfulDeletionsIDs;
	/** @var bool|null */
	private $wasScheduled;
	/** @var bool Whether a deletion was attempted */
	private $attemptedDeletion = false;

	/**
	 * @param HookContainer $hookContainer
	 * @param RevisionStore $revisionStore
	 * @param LBFactory $lbFactory
	 * @param JobQueueGroup $jobQueueGroup
	 * @param CommentStore $commentStore
	 * @param ServiceOptions $serviceOptions
	 * @param BagOStuff $recentDeletesCache
	 * @param string $localWikiID
	 * @param string $webRequestID
	 * @param WikiPageFactory $wikiPageFactory
	 * @param UserFactory $userFactory
	 * @param ProperPageIdentity $page
	 * @param Authority $deleter
	 * @param BacklinkCacheFactory $backlinkCacheFactory
	 */
	public function __construct(
		HookContainer $hookContainer,
		RevisionStore $revisionStore,
		LBFactory $lbFactory,
		JobQueueGroup $jobQueueGroup,
		CommentStore $commentStore,
		ServiceOptions $serviceOptions,
		BagOStuff $recentDeletesCache,
		string $localWikiID,
		string $webRequestID,
		WikiPageFactory $wikiPageFactory,
		UserFactory $userFactory,
		ProperPageIdentity $page,
		Authority $deleter,
		BacklinkCacheFactory $backlinkCacheFactory
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->revisionStore = $revisionStore;
		$this->lbFactory = $lbFactory;
		$this->loadBalancer = $this->lbFactory->getMainLB();
		$this->jobQueueGroup = $jobQueueGroup;
		$this->commentStore = $commentStore;
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $serviceOptions;
		$this->recentDeletesCache = $recentDeletesCache;
		$this->localWikiID = $localWikiID;
		$this->webRequestID = $webRequestID;
		$this->userFactory = $userFactory;

		$this->page = $wikiPageFactory->newFromTitle( $page );
		$this->deleter = $deleter;
		$this->backlinkCacheFactory = $backlinkCacheFactory;
	}

	/**
	 * @internal BC method for use by WikiPage::doDeleteArticleReal only.
	 * @return array|string
	 */
	public function getLegacyHookErrors() {
		return $this->legacyHookErrors;
	}

	/**
	 * @internal BC method for use by WikiPage::doDeleteArticleReal only.
	 * @return self
	 */
	public function keepLegacyHookErrorsSeparate(): self {
		$this->mergeLegacyHookErrors = false;
		return $this;
	}

	/**
	 * If true, suppress all revisions and log the deletion in the suppression log instead of
	 * the deletion log.
	 *
	 * @param bool $suppress
	 * @return self For chaining
	 */
	public function setSuppress( bool $suppress ): self {
		$this->suppress = $suppress;
		return $this;
	}

	/**
	 * Change tags to apply to the deletion action
	 *
	 * @param string[] $tags
	 * @return self For chaining
	 */
	public function setTags( array $tags ): self {
		$this->tags = $tags;
		return $this;
	}

	/**
	 * Set a specific log subtype for the deletion log entry.
	 *
	 * @param string $logSubtype
	 * @return self For chaining
	 */
	public function setLogSubtype( string $logSubtype ): self {
		$this->logSubtype = $logSubtype;
		return $this;
	}

	/**
	 * If false, allows deleting over time via the job queue
	 *
	 * @param bool $forceImmediate
	 * @return self For chaining
	 */
	public function forceImmediate( bool $forceImmediate ): self {
		$this->forceImmediate = $forceImmediate;
		return $this;
	}

	/**
	 * @internal FIXME: Hack used when running the DeletePage unit test to disable some legacy code.
	 * @codeCoverageIgnore
	 * @param bool $test
	 */
	public function setIsDeletePageUnitTest( bool $test ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new BadMethodCallException( __METHOD__ . ' can only be used in tests!' );
		}
		$this->isDeletePageUnitTest = $test;
	}

	/**
	 * Called before attempting a deletion, allows the result getters to be used
	 */
	private function setDeletionAttempted(): void {
		$this->attemptedDeletion = true;
		$this->successfulDeletionsIDs = [];
		$this->wasScheduled = false;
	}

	/**
	 * Asserts that a deletion operation was attempted
	 * @throws BadMethodCallException
	 */
	private function assertDeletionAttempted(): void {
		if ( !$this->attemptedDeletion ) {
			throw new BadMethodCallException( 'No deletion was attempted' );
		}
	}

	/**
	 * @return int[] Array of log IDs of successful deletions
	 * @throws BadMethodCallException If no deletions were attempted
	 */
	public function getSuccessfulDeletionsIDs(): array {
		$this->assertDeletionAttempted();
		return $this->successfulDeletionsIDs;
	}

	/**
	 * @return bool Whether (part of) the deletion was scheduled
	 * @throws BadMethodCallException If no deletions were attempted
	 */
	public function deletionWasScheduled(): bool {
		$this->assertDeletionAttempted();
		return $this->wasScheduled;
	}

	/**
	 * Same as deleteUnsafe, but checks permissions.
	 *
	 * @param string $reason
	 * @return StatusValue
	 */
	public function deleteIfAllowed( string $reason ): StatusValue {
		$this->setDeletionAttempted();
		$status = $this->authorizeDeletion();
		if ( !$status->isGood() ) {
			return $status;
		}

		return $this->deleteUnsafe( $reason );
	}

	/**
	 * @return PermissionStatus
	 */
	private function authorizeDeletion(): PermissionStatus {
		$status = PermissionStatus::newEmpty();
		$this->deleter->authorizeWrite( 'delete', $this->page, $status );
		if (
			!$this->deleter->authorizeWrite( 'bigdelete', $this->page ) &&
			$this->isBigDeletion()
		) {
			$status->fatal( 'delete-toobig', Message::numParam( $this->options->get( 'DeleteRevisionsLimit' ) ) );
		}
		if ( $this->tags ) {
			$status->merge( ChangeTags::canAddTagsAccompanyingChange( $this->tags, $this->deleter ) );
		}
		return $status;
	}

	/**
	 * @return bool
	 */
	private function isBigDeletion(): bool {
		$revLimit = $this->options->get( 'DeleteRevisionsLimit' );
		if ( !$revLimit ) {
			return false;
		}

		$revCount = $this->revisionStore->countRevisionsByPageId(
			$this->loadBalancer->getConnectionRef( DB_REPLICA ),
			$this->page->getId()
		);

		return $revCount > $revLimit;
	}

	/**
	 * Determines if this deletion would be batched (executed over time by the job queue)
	 * or not (completed in the same request as the delete call).
	 *
	 * It is unlikely but possible that an edit from another request could push the page over the
	 * batching threshold after this function is called, but before the caller acts upon the
	 * return value. Callers must decide for themselves how to deal with this. $safetyMargin
	 * is provided as an unreliable but situationally useful help for some common cases.
	 *
	 * @param int $safetyMargin Added to the revision count when checking for batching
	 * @return bool True if deletion would be batched, false otherwise
	 */
	public function isBatchedDelete( int $safetyMargin = 0 ): bool {
		$revCount = $this->revisionStore->countRevisionsByPageId(
			$this->loadBalancer->getConnectionRef( DB_REPLICA ),
			$this->page->getId()
		);
		$revCount += $safetyMargin;

		return $revCount >= $this->options->get( 'DeleteRevisionsBatchSize' );
	}

	/**
	 * Back-end article deletion: deletes the article with database consistency, writes logs, purges caches.
	 * @note This method doesn't check user permissions. Use deleteIfAllowed for that.
	 *
	 * @param string $reason Delete reason for deletion log
	 * @return Status Status object:
	 *   - If successful (or scheduled), a good Status
	 *   - If the page couldn't be deleted because it wasn't found, a Status with a non-fatal 'cannotdelete' error.
	 *   - A fatal Status otherwise.
	 */
	public function deleteUnsafe( string $reason ): Status {
		$this->setDeletionAttempted();
		$status = Status::newGood();

		$legacyDeleter = $this->userFactory->newFromAuthority( $this->deleter );
		if ( !$this->hookRunner->onArticleDelete(
			$this->page, $legacyDeleter, $reason, $this->legacyHookErrors, $status, $this->suppress )
		) {
			if ( $this->mergeLegacyHookErrors && $this->legacyHookErrors !== '' ) {
				if ( is_string( $this->legacyHookErrors ) ) {
					$this->legacyHookErrors = [ $this->legacyHookErrors ];
				}
				foreach ( $this->legacyHookErrors as $legacyError ) {
					$status->fatal( new RawMessage( $legacyError ) );
				}
			}
			if ( $status->isOK() ) {
				// Hook aborted but didn't set a fatal status
				$status->fatal( 'delete-hook-aborted' );
			}
			return $status;
		}

		// Use a new Status in case a hook handler put something here without aborting.
		$status = Status::newGood();
		$hookRes = $this->hookRunner->onPageDelete( $this->page, $this->deleter, $reason, $status, $this->suppress );
		if ( !$hookRes && !$status->isGood() ) {
			// Note: as per the PageDeleteHook documentation, `return false` is ignored if $status is good.
			return $status;
		}

		return $this->deleteInternal( $reason );
	}

	/**
	 * @internal The only external caller allowed is DeletePageJob.
	 * Back-end article deletion
	 *
	 * Only invokes batching via the job queue if necessary per DeleteRevisionsBatchSize.
	 * Deletions can often be completed inline without involving the job queue.
	 *
	 * Potentially called many times per deletion operation for pages with many revisions.
	 * @param string $reason
	 * @param string|null $webRequestId
	 * @return Status
	 */
	public function deleteInternal( string $reason, ?string $webRequestId = null ): Status {
		// The following is necessary for direct calls from the outside
		$this->setDeletionAttempted();

		$title = $this->page->getTitle();
		$status = Status::newGood();

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__ );

		$this->page->loadPageData( WikiPage::READ_LATEST );
		$id = $this->page->getId();
		// T98706: lock the page from various other updates but avoid using
		// WikiPage::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $this->page->lockAndGetLatest();
		if ( $id === 0 || $this->page->getLatest() !== $lockedLatest ) {
			$dbw->endAtomic( __METHOD__ );
			// Page not there or trx snapshot is stale
			$status->error( 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) );
			return $status;
		}

		// At this point we are now committed to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// we need to remember the old content so we can use it to generate all deletion updates.
		$revisionRecord = $this->page->getRevisionRecord();
		if ( !$revisionRecord ) {
			throw new LogicException( "No revisions for $this->page?" );
		}
		try {
			$content = $this->page->getContent( RevisionRecord::RAW );
		} catch ( Exception $ex ) {
			wfLogWarning( __METHOD__ . ': failed to load content during deletion! '
				. $ex->getMessage() );

			$content = null;
		}

		// Archive revisions.  In immediate mode, archive all revisions.  Otherwise, archive
		// one batch of revisions and defer archival of any others to the job queue.
		$explictTrxLogged = false;
		while ( true ) {
			$done = $this->archiveRevisions( $id );
			if ( $done || !$this->forceImmediate ) {
				break;
			}
			$dbw->endAtomic( __METHOD__ );
			if ( $dbw->explicitTrxActive() ) {
				// Explict transactions may never happen here in practice.  Log to be sure.
				if ( !$explictTrxLogged ) {
					$explictTrxLogged = true;
					LoggerFactory::getInstance( 'wfDebug' )->debug(
						'explicit transaction active in ' . __METHOD__ . ' while deleting {title}', [
						'title' => $title->getText(),
					] );
				}
				continue;
			}
			if ( $dbw->trxLevel() ) {
				$dbw->commit( __METHOD__ );
			}
			$this->lbFactory->waitForReplication();
			$dbw->startAtomic( __METHOD__ );
		}

		if ( !$done ) {
			$dbw->endAtomic( __METHOD__ );

			$jobParams = [
				'namespace' => $title->getNamespace(),
				'title' => $title->getDBkey(),
				'wikiPageId' => $id,
				'requestId' => $webRequestId ?? $this->webRequestID,
				'reason' => $reason,
				'suppress' => $this->suppress,
				'userId' => $this->deleter->getUser()->getId(),
				'tags' => json_encode( $this->tags ),
				'logsubtype' => $this->logSubtype,
			];

			$job = new DeletePageJob( $jobParams );
			$this->jobQueueGroup->push( $job );
			$this->wasScheduled = true;
			return $status;
		}

		// Get archivedRevisionCount by db query, because there's no better alternative.
		// Jobs cannot pass a count of archived revisions to the next job, because additional
		// deletion operations can be started while the first is running.  Jobs from each
		// gracefully interleave, but would not know about each other's count.  Deduplication
		// in the job queue to avoid simultaneous deletion operations would add overhead.
		// Number of archived revisions cannot be known beforehand, because edits can be made
		// while deletion operations are being processed, changing the number of archivals.
		$archivedRevisionCount = $dbw->selectRowCount(
			'archive',
			'*',
			[
				'ar_namespace' => $title->getNamespace(),
				'ar_title' => $title->getDBkey(),
				'ar_page_id' => $id
			], __METHOD__
		);

		// Clone the title and wikiPage, so we have the information we need when
		// we log and run the ArticleDeleteComplete hook.
		$logTitle = clone $title;
		$wikiPageBeforeDelete = clone $this->page;

		// Now that it's safely backed up, delete it
		$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );

		// Log the deletion, if the page was suppressed, put it in the suppression log instead
		$logtype = $this->suppress ? 'suppress' : 'delete';

		$logEntry = new ManualLogEntry( $logtype, $this->logSubtype );
		$logEntry->setPerformer( $this->deleter->getUser() );
		$logEntry->setTarget( $logTitle );
		$logEntry->setComment( $reason );
		$logEntry->addTags( $this->tags );
		if ( !$this->isDeletePageUnitTest ) {
			// TODO: Remove conditional once ManualLogEntry is servicified (T253717)
			$logid = $logEntry->insert();

			$dbw->onTransactionPreCommitOrIdle(
				static function () use ( $logEntry, $logid ) {
					// T58776: avoid deadlocks (especially from FileDeleteForm)
					$logEntry->publish( $logid );
				},
				__METHOD__
			);
		} else {
			$logid = 42;
		}

		$dbw->endAtomic( __METHOD__ );

		$this->doDeleteUpdates( $revisionRecord );

		$legacyDeleter = $this->userFactory->newFromAuthority( $this->deleter );
		$this->hookRunner->onArticleDeleteComplete(
			$wikiPageBeforeDelete,
			$legacyDeleter,
			$reason,
			$id,
			$content,
			$logEntry,
			$archivedRevisionCount
		);
		$this->hookRunner->onPageDeleteComplete(
			$wikiPageBeforeDelete,
			$this->deleter,
			$reason,
			$id,
			$revisionRecord,
			$logEntry,
			$archivedRevisionCount
		);
		$this->successfulDeletionsIDs[] = $logid;

		// Show log excerpt on 404 pages rather than just a link
		$key = $this->recentDeletesCache->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
		$this->recentDeletesCache->set( $key, 1, BagOStuff::TTL_DAY );

		return $status;
	}

	/**
	 * Archives revisions as part of page deletion.
	 *
	 * @param int $id
	 * @return bool
	 */
	private function archiveRevisions( int $id ): bool {
		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->page->getTitle()->getNamespace();
		$dbKey = $this->page->getTitle()->getDBkey();

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );

		$revQuery = $this->revisionStore->getQueryInfo();
		$bitfield = false;

		// Bitfields to further suppress the content
		if ( $this->suppress ) {
			$bitfield = RevisionRecord::SUPPRESSED_ALL;
			$revQuery['fields'] = array_diff( $revQuery['fields'], [ 'rev_deleted' ] );
		}

		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.

		// Lock rows in `revision` and its temp tables, but not any others.
		// Note array_intersect() preserves keys from the first arg, and we're
		// assuming $revQuery has `revision` primary and isn't using subtables
		// for anything we care about.
		$dbw->lockForUpdate(
			array_intersect(
				$revQuery['tables'],
				[ 'revision', 'revision_comment_temp', 'revision_actor_temp' ]
			),
			[ 'rev_page' => $id ],
			__METHOD__,
			[],
			$revQuery['joins']
		);

		$deleteBatchSize = $this->options->get( 'DeleteRevisionsBatchSize' );
		// Get as many of the page revisions as we are allowed to.  The +1 lets us recognize the
		// unusual case where there were exactly $deleteBatchSize revisions remaining.
		$res = $dbw->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $id ],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC, rev_id ASC', 'LIMIT' => $deleteBatchSize + 1 ],
			$revQuery['joins']
		);

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] Revision IDs of edits that were made by IPs */
		$ipRevIds = [];

		$done = true;
		foreach ( $res as $row ) {
			if ( count( $revids ) >= $deleteBatchSize ) {
				$done = false;
				break;
			}

			$comment = $this->commentStore->getComment( 'rev_comment', $row );
			$rowInsert = [
					'ar_namespace'  => $namespace,
					'ar_title'      => $dbKey,
					'ar_actor'      => $row->rev_actor,
					'ar_timestamp'  => $row->rev_timestamp,
					'ar_minor_edit' => $row->rev_minor_edit,
					'ar_rev_id'     => $row->rev_id,
					'ar_parent_id'  => $row->rev_parent_id,
					'ar_len'        => $row->rev_len,
					'ar_page_id'    => $id,
					'ar_deleted'    => $this->suppress ? $bitfield : $row->rev_deleted,
					'ar_sha1'       => $row->rev_sha1,
				] + $this->commentStore->insert( $dbw, 'ar_comment', $comment );

			$rowsInsert[] = $rowInsert;
			$revids[] = $row->rev_id;

			// Keep track of IP edits, so that the corresponding rows can
			// be deleted in the ip_changes table.
			if ( (int)$row->rev_user === 0 && IPUtils::isValid( $row->rev_user_text ) ) {
				$ipRevIds[] = $row->rev_id;
			}
		}

		// This conditional is just a sanity check
		if ( count( $revids ) > 0 ) {
			// Copy them into the archive table
			$dbw->insert( 'archive', $rowsInsert, __METHOD__ );

			$dbw->delete( 'revision', [ 'rev_id' => $revids ], __METHOD__ );
			$dbw->delete( 'revision_comment_temp', [ 'revcomment_rev' => $revids ], __METHOD__ );
			if ( $this->options->get( 'ActorTableSchemaMigrationStage' ) & SCHEMA_COMPAT_WRITE_TEMP ) {
				$dbw->delete( 'revision_actor_temp', [ 'revactor_rev' => $revids ], __METHOD__ );
			}

			// Also delete records from ip_changes as applicable.
			if ( count( $ipRevIds ) > 0 ) {
				$dbw->delete( 'ip_changes', [ 'ipc_rev_id' => $ipRevIds ], __METHOD__ );
			}
		}

		return $done;
	}

	/**
	 * @private Public for BC only
	 * Do some database updates after deletion
	 *
	 * @param RevisionRecord $revRecord The current page revision at the time of
	 *   deletion, used when determining the required updates. This may be needed because
	 *   $this->page->getRevisionRecord() may already return null when the page proper was deleted.
	 */
	public function doDeleteUpdates( RevisionRecord $revRecord ): void {
		try {
			$countable = $this->page->isCountable();
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		if ( !$this->isDeletePageUnitTest ) {
			// TODO Remove conditional once DeferredUpdates is servicified (T265749)
			DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
				[ 'edits' => 1, 'articles' => -$countable, 'pages' => -1 ]
			) );

			// Delete pagelinks, update secondary indexes, etc
			$updates = $this->getDeletionUpdates( $revRecord );
			foreach ( $updates as $update ) {
				DeferredUpdates::addUpdate( $update );
			}
		}

		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$this->page->getTitle(),
			'templatelinks',
			'delete-page',
			$this->deleter->getUser()->getName(),
			$this->backlinkCacheFactory->getBacklinkCache( $this->page->getTitle() )
		);
		// Reparse any pages including this image
		if ( $this->page->getTitle()->getNamespace() === NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$this->page->getTitle(),
				'imagelinks',
				'delete-page',
				$this->deleter->getUser()->getName(),
				$this->backlinkCacheFactory->getBacklinkCache( $this->page->getTitle() )
			);
		}

		if ( !$this->isDeletePageUnitTest ) {
			// TODO Remove conditional once WikiPage::onArticleDelete is moved to a proper service
			// Clear caches
			WikiPage::onArticleDelete( $this->page->getTitle() );
		}

		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->page->getTitle(),
			$revRecord,
			null,
			$this->localWikiID
		);

		// Reset the page object and the Title object
		$this->page->loadFromRow( false, WikiPage::READ_LATEST );

		if ( !$this->isDeletePageUnitTest ) {
			// TODO Remove conditional once DeferredUpdates is servicified (T265749)
			// Search engine
			DeferredUpdates::addUpdate( new SearchUpdate( $this->page->getId(), $this->page->getTitle() ) );
		}
	}

	/**
	 * @private Public for BC only
	 * Returns a list of updates to be performed when the page is deleted. The
	 * updates should remove any information about this page from secondary data
	 * stores such as links tables.
	 *
	 * @param RevisionRecord $rev The revision being deleted.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( RevisionRecord $rev ): array {
		$slotContent = array_map( static function ( SlotRecord $slot ) {
			return $slot->getContent();
		}, $rev->getSlots()->getSlots() );

		$allUpdates = [ new LinksDeletionUpdate( $this->page ) ];

		// NOTE: once Content::getDeletionUpdates() is removed, we only need the content
		// model here, not the content object!
		// TODO: consolidate with similar logic in DerivedPageDataUpdater::getSecondaryDataUpdates()
		/** @var ?Content $content */
		$content = null; // in case $slotContent is zero-length
		foreach ( $slotContent as $role => $content ) {
			$handler = $content->getContentHandler();

			$updates = $handler->getDeletionUpdates(
				$this->page->getTitle(),
				$role
			);

			$allUpdates = array_merge( $allUpdates, $updates );
		}

		$this->hookRunner->onPageDeletionDataUpdates(
			$this->page->getTitle(), $rev, $allUpdates );

		// TODO: hard deprecate old hook in 1.33
		$this->hookRunner->onWikiPageDeletionUpdates( $this->page, $content, $allUpdates );
		return $allUpdates;
	}
}
