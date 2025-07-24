<?php

namespace MediaWiki\Page;

use BadMethodCallException;
use Exception;
use LogicException;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Deferred\DeferrableUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use StatusValue;
use Wikimedia\IPUtils;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Backend logic for performing a page delete action.
 *
 * @since 1.37
 * @ingroup Page
 */
class DeletePage {
	/**
	 * @internal For use by PageCommandFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DeleteRevisionsBatchSize,
		MainConfigNames::DeleteRevisionsLimit,
	];

	/**
	 * Constants used for the return value of getSuccessfulDeletionsIDs() and deletionsWereScheduled()
	 */
	public const PAGE_BASE = 'base';
	public const PAGE_TALK = 'talk';

	/** @var bool */
	private $isDeletePageUnitTest = false;
	/** @var bool */
	private $suppress = false;
	/** @var string[] */
	private $tags = [];
	/** @var string */
	private $logSubtype = 'delete';
	/** @var bool */
	private $forceImmediate = false;
	/** @var WikiPage|null If not null, it means that we have to delete it. */
	private $associatedTalk;

	/** @var string|array */
	private $legacyHookErrors = '';
	/** @var bool */
	private $mergeLegacyHookErrors = true;

	/**
	 * @var array<int|null>|null Keys are the self::PAGE_* constants. Values are null if the deletion couldn't happen
	 * (e.g. due to lacking perms) or was scheduled. PAGE_TALK is only set when deleting the associated talk.
	 */
	private $successfulDeletionsIDs;
	/**
	 * @var array<bool|null>|null Keys are the self::PAGE_* constants. Values are null if the deletion couldn't happen
	 * (e.g. due to lacking perms). PAGE_TALK is only set when deleting the associated talk.
	 */
	private $wasScheduled;
	/** @var bool Whether a deletion was attempted */
	private $attemptedDeletion = false;

	private HookRunner $hookRunner;
	private DomainEventDispatcher $eventDispatcher;
	private RevisionStore $revisionStore;
	private LBFactory $lbFactory;
	private JobQueueGroup $jobQueueGroup;
	private CommentStore $commentStore;
	private ServiceOptions $options;
	private BagOStuff $recentDeletesCache;
	private string $localWikiID;
	private string $webRequestID;
	private WikiPageFactory $wikiPageFactory;
	private UserFactory $userFactory;
	private BacklinkCacheFactory $backlinkCacheFactory;
	private NamespaceInfo $namespaceInfo;
	private ITextFormatter $contLangMsgTextFormatter;
	private RedirectStore $redirectStore;
	private WikiPage $page;
	private Authority $deleter;

	/**
	 * @internal Create via the PageDeleteFactory service.
	 */
	public function __construct(
		HookContainer $hookContainer,
		DomainEventDispatcher $eventDispatcher,
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
		BacklinkCacheFactory $backlinkCacheFactory,
		NamespaceInfo $namespaceInfo,
		ITextFormatter $contLangMsgTextFormatter,
		RedirectStore $redirectStore,
		ProperPageIdentity $page,
		Authority $deleter
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->eventDispatcher = $eventDispatcher;
		$this->revisionStore = $revisionStore;
		$this->lbFactory = $lbFactory;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->commentStore = $commentStore;
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $serviceOptions;
		$this->recentDeletesCache = $recentDeletesCache;
		$this->localWikiID = $localWikiID;
		$this->webRequestID = $webRequestID;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->userFactory = $userFactory;
		$this->backlinkCacheFactory = $backlinkCacheFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->contLangMsgTextFormatter = $contLangMsgTextFormatter;

		$this->page = $wikiPageFactory->newFromTitle( $page );
		$this->deleter = $deleter;
		$this->redirectStore = $redirectStore;
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
	 * Tests whether it's probably possible to delete the associated talk page. This checks the replica,
	 * so it may not see the latest master change, and is useful e.g. for building the UI.
	 */
	public function canProbablyDeleteAssociatedTalk(): StatusValue {
		if ( $this->namespaceInfo->isTalk( $this->page->getNamespace() ) ) {
			return StatusValue::newFatal( 'delete-error-associated-alreadytalk' );
		}
		// FIXME NamespaceInfo should work with PageIdentity
		$talkPage = $this->wikiPageFactory->newFromLinkTarget(
			$this->namespaceInfo->getTalkPage( $this->page->getTitle() )
		);
		if ( !$talkPage->exists() ) {
			return StatusValue::newFatal( 'delete-error-associated-doesnotexist' );
		}
		return StatusValue::newGood();
	}

	/**
	 * If set to true and the page has a talk page, delete that one too. Callers should call
	 * canProbablyDeleteAssociatedTalk first to make sure this is a valid operation. Note that the checks
	 * here are laxer than those in canProbablyDeleteAssociatedTalk. In particular, this doesn't check
	 * whether the page exists as that may be subject to race condition, and it's checked later on (in deleteInternal,
	 * using latest data) anyway.
	 *
	 * @param bool $delete
	 * @return self For chaining
	 * @throws BadMethodCallException If $delete is true and the given page is not a talk page.
	 */
	public function setDeleteAssociatedTalk( bool $delete ): self {
		if ( !$delete ) {
			$this->associatedTalk = null;
			return $this;
		}

		if ( $this->namespaceInfo->isTalk( $this->page->getNamespace() ) ) {
			throw new BadMethodCallException( "Cannot delete associated talk page of a talk page! ($this->page)" );
		}
		// FIXME NamespaceInfo should work with PageIdentity
		$this->associatedTalk = $this->wikiPageFactory->newFromLinkTarget(
			$this->namespaceInfo->getTalkPage( $this->page->getTitle() )
		);
		return $this;
	}

	/**
	 * @internal FIXME: Hack used when running the DeletePage unit test to disable some legacy code.
	 * @codeCoverageIgnore
	 * @param bool $test
	 */
	public function setIsDeletePageUnitTest( bool $test ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can only be used in tests!' );
		}
		$this->isDeletePageUnitTest = $test;
	}

	/**
	 * Called before attempting a deletion, allows the result getters to be used
	 * @internal The only external caller allowed is DeletePageJob.
	 * @return self
	 */
	public function setDeletionAttempted(): self {
		$this->attemptedDeletion = true;
		$this->successfulDeletionsIDs = [ self::PAGE_BASE => null ];
		$this->wasScheduled = [ self::PAGE_BASE => null ];
		if ( $this->associatedTalk ) {
			$this->successfulDeletionsIDs[self::PAGE_TALK] = null;
			$this->wasScheduled[self::PAGE_TALK] = null;
		}
		return $this;
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
	 * @return bool[] Whether the deletions were scheduled
	 * @throws BadMethodCallException If no deletions were attempted
	 */
	public function deletionsWereScheduled(): array {
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

	private function authorizeDeletion(): PermissionStatus {
		$status = PermissionStatus::newEmpty();
		$this->deleter->authorizeWrite( 'delete', $this->page, $status );
		if ( $this->associatedTalk ) {
			$this->deleter->authorizeWrite( 'delete', $this->associatedTalk, $status );
		}
		if ( !$this->deleter->isAllowed( 'bigdelete' ) && $this->isBigDeletion() ) {
			$status->fatal(
				'delete-toomanyrevisions',
				Message::numParam( $this->options->get( MainConfigNames::DeleteRevisionsLimit ) )
			);
		}
		if ( $this->tags ) {
			$status->merge( ChangeTags::canAddTagsAccompanyingChange( $this->tags, $this->deleter ) );
		}
		return $status;
	}

	private function isBigDeletion(): bool {
		$revLimit = $this->options->get( MainConfigNames::DeleteRevisionsLimit );
		if ( !$revLimit ) {
			return false;
		}

		$dbr = $this->lbFactory->getReplicaDatabase();
		$revCount = $this->revisionStore->countRevisionsByPageId( $dbr, $this->page->getId() );
		if ( $this->associatedTalk ) {
			$revCount += $this->revisionStore->countRevisionsByPageId( $dbr, $this->associatedTalk->getId() );
		}

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
		$dbr = $this->lbFactory->getReplicaDatabase();
		$revCount = $this->revisionStore->countRevisionsByPageId( $dbr, $this->page->getId() );
		$revCount += $safetyMargin;

		if ( $revCount >= $this->options->get( MainConfigNames::DeleteRevisionsBatchSize ) ) {
			return true;
		} elseif ( !$this->associatedTalk ) {
			return false;
		}

		$talkRevCount = $this->revisionStore->countRevisionsByPageId( $dbr, $this->associatedTalk->getId() );
		$talkRevCount += $safetyMargin;

		return $talkRevCount >= $this->options->get( MainConfigNames::DeleteRevisionsBatchSize );
	}

	/**
	 * Back-end article deletion: deletes the article with database consistency, writes logs, purges caches.
	 * @note This method doesn't check user permissions. Use deleteIfAllowed for that.
	 *
	 * @param string $reason Delete reason for deletion log
	 * @return Status Status object:
	 *   - If successful (or scheduled), a good Status
	 *   - If a page couldn't be deleted because it wasn't found, a Status with a non-fatal 'cannotdelete' error.
	 *   - A fatal Status otherwise.
	 */
	public function deleteUnsafe( string $reason ): Status {
		$this->setDeletionAttempted();
		$origReason = $reason;
		$hookStatus = $this->runPreDeleteHooks( $this->page, $reason );
		if ( !$hookStatus->isGood() ) {
			return $hookStatus;
		}
		if ( $this->associatedTalk ) {
			$talkReason = $this->contLangMsgTextFormatter->format(
				MessageValue::new( 'delete-talk-summary-prefix' )->plaintextParams( $origReason )
			);
			$talkHookStatus = $this->runPreDeleteHooks( $this->associatedTalk, $talkReason );
			if ( !$talkHookStatus->isGood() ) {
				return $talkHookStatus;
			}
		}

		$status = $this->deleteInternal( $this->page, self::PAGE_BASE, $reason );
		if ( !$this->associatedTalk || !$status->isGood() ) {
			return $status;
		}
		// NOTE: If the page deletion above failed because the page is no longer there (e.g. race condition) we'll
		// still try to delete the talk page, since it was the user's intention anyway.
		// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable talkReason is set when used
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable talkReason is set when used
		$status->merge( $this->deleteInternal( $this->associatedTalk, self::PAGE_TALK, $talkReason ) );
		return $status;
	}

	/**
	 * @param WikiPage $page
	 * @param string &$reason
	 * @return Status
	 */
	private function runPreDeleteHooks( WikiPage $page, string &$reason ): Status {
		$status = Status::newGood();

		$legacyDeleter = $this->userFactory->newFromAuthority( $this->deleter );
		if ( !$this->hookRunner->onArticleDelete(
			$page, $legacyDeleter, $reason, $this->legacyHookErrors, $status, $this->suppress )
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
		$hookRes = $this->hookRunner->onPageDelete( $page, $this->deleter, $reason, $status, $this->suppress );
		if ( !$hookRes && !$status->isGood() ) {
			// Note: as per the PageDeleteHook documentation, `return false` is ignored if $status is good.
			return $status;
		}
		return Status::newGood();
	}

	/**
	 * @internal The only external caller allowed is DeletePageJob.
	 * Back-end article deletion
	 *
	 * Only invokes batching via the job queue if necessary per DeleteRevisionsBatchSize.
	 * Deletions can often be completed inline without involving the job queue.
	 *
	 * Potentially called many times per deletion operation for pages with many revisions.
	 * @param WikiPage $page
	 * @param string $pageRole
	 * @param string $reason
	 * @param string|null $webRequestId
	 * @param mixed|null $ticket Result of ILBFactory::getEmptyTransactionTicket() or null
	 * @return Status
	 */
	public function deleteInternal(
		WikiPage $page,
		string $pageRole,
		string $reason,
		?string $webRequestId = null,
		$ticket = null
	): Status {
		$title = $page->getTitle();
		$status = Status::newGood();

		$dbw = $this->lbFactory->getPrimaryDatabase();
		$dbw->startAtomic( __METHOD__ );

		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$id = $page->getId();
		// T98706: lock the page from various other updates but avoid using
		// IDBAccessObject::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $page->lockAndGetLatest();
		if ( $id === 0 || $page->getLatest() !== $lockedLatest ) {
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
		$revisionRecord = $page->getRevisionRecord();
		if ( !$revisionRecord ) {
			throw new LogicException( "No revisions for $page?" );
		}
		try {
			$content = $page->getContent( RevisionRecord::RAW );
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception $ex ) {
			wfLogWarning( __METHOD__ . ': failed to load content during deletion! '
				. $ex->getMessage() );

			$content = null;
		}

		// Archive revisions.  In immediate mode, archive all revisions.  Otherwise, archive
		// one batch of revisions and defer archival of any others to the job queue.
		while ( true ) {
			$done = $this->archiveRevisions( $page, $id );
			if ( $done || !$this->forceImmediate ) {
				break;
			}
			$dbw->endAtomic( __METHOD__ );
			$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
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
				'pageRole' => $pageRole,
			];

			$job = new DeletePageJob( $jobParams );
			$this->jobQueueGroup->push( $job );
			$this->wasScheduled[$pageRole] = true;
			return $status;
		}
		$this->wasScheduled[$pageRole] = false;

		// Get archivedRevisionCount by db query, because there's no better alternative.
		// Jobs cannot pass a count of archived revisions to the next job, because additional
		// deletion operations can be started while the first is running.  Jobs from each
		// gracefully interleave, but would not know about each other's count.  Deduplication
		// in the job queue to avoid simultaneous deletion operations would add overhead.
		// Number of archived revisions cannot be known beforehand, because edits can be made
		// while deletion operations are being processed, changing the number of archivals.
		$archivedRevisionCount = $dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'archive' )
			->where( [
				'ar_namespace' => $title->getNamespace(),
				'ar_title' => $title->getDBkey(),
				'ar_page_id' => $id
			] )
			->caller( __METHOD__ )->fetchRowCount();

		// Look up the redirect target before deleting the page to avoid inconsistent state (T348881).
		// The cloning business below is specifically to allow hook handlers to check the redirect
		// status before the deletion (see I715046dc8157047aff4d5bd03ea6b5a47aee58bb).
		$page->getRedirectTarget();
		// Clone the title and wikiPage, so we have the information we need when
		// we log and run the ArticleDeleteComplete hook.
		$logTitle = clone $title;
		$wikiPageBeforeDelete = clone $page;
		$pageBeforeDelete = $page->toPageRecord();

		// Now that it's safely backed up, delete it
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'page' )
			->where( [ 'page_id' => $id ] )
			->caller( __METHOD__ )->execute();

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

		$this->eventDispatcher->dispatch( new PageDeletedEvent(
			$pageBeforeDelete,
			$revisionRecord,
			$this->deleter->getUser(),
			$this->tags,
			[ PageDeletedEvent::FLAG_SUPPRESSED => $this->suppress ],
			$logEntry->getTimestamp(),
			$reason,
			$archivedRevisionCount,
			$pageBeforeDelete->isRedirect() ? $this->redirectStore->getRedirectTarget( $page ) : null
		), $this->lbFactory );

		$dbw->endAtomic( __METHOD__ );

		$this->doDeleteUpdates( $wikiPageBeforeDelete, $revisionRecord );

		// Reset the page object and the Title object
		$page->loadFromRow( false, IDBAccessObject::READ_LATEST );

		// Make sure there are no cached title instances that refer to the same page.
		Title::clearCaches();

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
			$pageBeforeDelete,
			$this->deleter,
			$reason,
			$id,
			$revisionRecord,
			$logEntry,
			$archivedRevisionCount
		);
		$this->successfulDeletionsIDs[$pageRole] = $logid;

		// Clear any cached redirect status for the now-deleted page.
		$this->redirectStore->clearCache( $page );

		// Show log excerpt on 404 pages rather than just a link
		$key = $this->recentDeletesCache->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
		$this->recentDeletesCache->set( $key, 1, BagOStuff::TTL_DAY );

		return $status;
	}

	/**
	 * Archives revisions as part of page deletion.
	 *
	 * @param WikiPage $page
	 * @param int $id
	 * @return bool
	 */
	private function archiveRevisions( WikiPage $page, int $id ): bool {
		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $page->getTitle()->getNamespace();
		$dbKey = $page->getTitle()->getDBkey();

		$dbw = $this->lbFactory->getPrimaryDatabase();

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
		$lockQuery = $revQuery;
		$lockQuery['tables'] = array_intersect(
			$revQuery['tables'],
			[ 'revision', 'revision_comment_temp' ]
		);
		unset( $lockQuery['fields'] );
		$dbw->newSelectQueryBuilder()
			->queryInfo( $lockQuery )
			->where( [ 'rev_page' => $id ] )
			->forUpdate()
			->caller( __METHOD__ )
			->acquireRowLocks();

		$deleteBatchSize = $this->options->get( MainConfigNames::DeleteRevisionsBatchSize );
		// Get as many of the page revisions as we are allowed to.  The +1 lets us recognize the
		// unusual case where there were exactly $deleteBatchSize revisions remaining.
		$res = $dbw->newSelectQueryBuilder()
			->queryInfo( $revQuery )
			->where( [ 'rev_page' => $id ] )
			->orderBy( [ 'rev_timestamp', 'rev_id' ] )
			->limit( $deleteBatchSize + 1 )
			->caller( __METHOD__ )
			->fetchResultSet();

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] $ipRevIds Revision IDs of edits that were made by IPs */
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

		if ( count( $revids ) > 0 ) {
			// Copy them into the archive table
			$dbw->newInsertQueryBuilder()
				->insertInto( 'archive' )
				->rows( $rowsInsert )
				->caller( __METHOD__ )->execute();

			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'revision' )
				->where( [ 'rev_id' => $revids ] )
				->caller( __METHOD__ )->execute();
			// Also delete records from ip_changes as applicable.
			if ( count( $ipRevIds ) > 0 ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ip_changes' )
					->where( [ 'ipc_rev_id' => $ipRevIds ] )
					->caller( __METHOD__ )->execute();
			}
		}

		return $done;
	}

	/**
	 * Do some database updates after deletion
	 *
	 * @param WikiPage $pageBeforeDelete
	 * @param RevisionRecord $revRecord The current page revision at the time of
	 *   deletion, used when determining the required updates. This may be needed because
	 *   $page->getRevisionRecord() may already return null when the page proper was deleted.
	 */
	private function doDeleteUpdates( WikiPage $pageBeforeDelete, RevisionRecord $revRecord ): void {
		try {
			$countable = $pageBeforeDelete->isCountable();
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		// TODO: Move to ChangeTrackingEventIngress,
		//       see https://gerrit.wikimedia.org/r/c/mediawiki/core/+/1099177
		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => 1, 'articles' => $countable ? -1 : 0, 'pages' => -1 ]
		) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates( $pageBeforeDelete, $revRecord );
		foreach ( $updates as $update ) {
			DeferredUpdates::addUpdate( $update );
		}

		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$pageBeforeDelete->getTitle(),
			'templatelinks',
			'delete-page',
			$this->deleter->getUser()->getName(),
			$this->backlinkCacheFactory->getBacklinkCache( $pageBeforeDelete->getTitle() )
		);
		// Reparse any pages including this image
		if ( $pageBeforeDelete->getTitle()->getNamespace() === NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$pageBeforeDelete->getTitle(),
				'imagelinks',
				'delete-page',
				$this->deleter->getUser()->getName(),
				$this->backlinkCacheFactory->getBacklinkCache( $pageBeforeDelete->getTitle() )
			);
		}

		if ( !$this->isDeletePageUnitTest ) {
			// TODO Remove conditional once WikiPage::onArticleDelete is moved to a proper service
			// Clear caches
			WikiPage::onArticleDelete( $pageBeforeDelete->getTitle() );
		}
	}

	/**
	 * Returns a list of updates to be performed when the page is deleted. The
	 * updates should remove any information about this page from secondary data
	 * stores such as links tables.
	 *
	 * @param WikiPage $page
	 * @param RevisionRecord $rev The revision being deleted.
	 * @return DeferrableUpdate[]
	 */
	private function getDeletionUpdates( WikiPage $page, RevisionRecord $rev ): array {
		if ( $this->isDeletePageUnitTest ) {
			// Hack: LinksDeletionUpdate reads from the global state in the constructor
			return [];
		}
		$slotContent = array_map( static function ( SlotRecord $slot ) {
			return $slot->getContent();
		}, $rev->getSlots()->getSlots() );

		$allUpdates = [ new LinksDeletionUpdate( $page ) ];

		// NOTE: once Content::getDeletionUpdates() is removed, we only need the content
		// model here, not the content object!
		// TODO: consolidate with similar logic in DerivedPageDataUpdater::getSecondaryDataUpdates()
		/** @var ?Content $content */
		$content = null; // in case $slotContent is zero-length
		foreach ( $slotContent as $role => $content ) {
			$handler = $content->getContentHandler();

			$updates = $handler->getDeletionUpdates(
				$page->getTitle(),
				$role
			);

			$allUpdates = array_merge( $allUpdates, $updates );
		}

		$this->hookRunner->onPageDeletionDataUpdates(
			$page->getTitle(), $rev, $allUpdates );

		// TODO: hard deprecate old hook in 1.33
		$this->hookRunner->onWikiPageDeletionUpdates( $page, $content, $allUpdates );
		return $allUpdates;
	}
}
