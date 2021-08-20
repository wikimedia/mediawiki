<?php

namespace MediaWiki\Page;

use BadMethodCallException;
use CommentStore;
use Content;
use DeferrableUpdate;
use DeferredUpdates;
use DeletePageJob;
use Exception;
use InvalidArgumentException;
use JobQueueGroup;
use LinksDeletionUpdate;
use LinksUpdate;
use ManualLogEntry;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use ObjectCache;
use ResourceLoaderWikiModule;
use SearchUpdate;
use SiteStatsUpdate;
use Status;
use WebRequest;
use WikiMap;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use WikiPage;

/**
 * @since 1.37
 * @package MediaWiki\Page
 * @unstable
 */
class DeletePage {
	/** @var HookRunner */
	private $hookRunner;
	/** @var RevisionStore */
	private $revisionStore;
	/** @var WikiPage */
	private $page;
	/** @var UserIdentity */
	private $deleter;

	/**
	 * @param WikiPage $page
	 * @param UserIdentity $deleter
	 * @todo Should use ProperPageIdentity, not WikiPage
	 */
	public function __construct( WikiPage $page, UserIdentity $deleter ) {
		$this->hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
		$this->revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->page = $page;
		$this->deleter = $deleter;
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param string $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @param array|string &$error Array of errors to append to
	 * @param string[] $tags Tags to apply to the deletion action
	 * @param string $logsubtype
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @return Status Status object; if successful, $status->value is the log_id of the
	 *   deletion log entry. If the page couldn't be deleted because it wasn't
	 *   found, $status is a non-fatal 'cannotdelete' error
	 */
	public function delete(
		$reason,
		$suppress = false,
		&$error = '',
		$tags = [],
		$logsubtype = 'delete',
		$immediate = false
	) {
		wfDebug( __METHOD__ );
		if ( !$this->page->getTitle()->canExist() ) {
			throw new BadMethodCallException( __METHOD__ . ' requires a proper page identity.' );
		}

		$status = Status::newGood();

		$legacyDeleter = MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromUserIdentity( $this->deleter );
		if ( !$this->hookRunner->onArticleDelete(
			$this->page, $legacyDeleter, $reason, $error, $status, $suppress )
		) {
			if ( $status->isOK() ) {
				// Hook aborted but didn't set a fatal status
				$status->fatal( 'delete-hook-aborted' );
			}
			return $status;
		}

		return $this->deleteBatched( $reason, $suppress, $tags,
			$logsubtype, $immediate );
	}

	/**
	 * @private Public for BC only
	 * Back-end article deletion
	 *
	 * Only invokes batching via the job queue if necessary per $wgDeleteRevisionsBatchSize.
	 * Deletions can often be completed inline without involving the job queue.
	 *
	 * Potentially called many times per deletion operation for pages with many revisions.
	 * @param string $reason
	 * @param bool $suppress
	 * @param string[] $tags
	 * @param string $logsubtype
	 * @param bool $immediate
	 * @param string|null $webRequestId
	 * @return Status
	 */
	public function deleteBatched(
		$reason, $suppress, $tags,
		$logsubtype, $immediate = false, $webRequestId = null
	) {
		wfDebug( __METHOD__ );

		$status = Status::newGood();

		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__ );

		$this->page->loadPageData( WikiPage::READ_LATEST );
		$id = $this->page->getId();
		// T98706: lock the page from various other updates but avoid using
		// WikiPage::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $this->page->lockAndGetLatest();
		if ( $id == 0 || $this->page->getLatest() != $lockedLatest ) {
			$dbw->endAtomic( __METHOD__ );
			// Page not there or trx snapshot is stale
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->page->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// At this point we are now committed to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// we need to remember the old content so we can use it to generate all deletion updates.
		$revisionRecord = $this->page->getRevisionRecord();
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
			$done = $this->archiveRevisions( $dbw, $id, $suppress );
			if ( $done || !$immediate ) {
				break;
			}
			$dbw->endAtomic( __METHOD__ );
			if ( $dbw->explicitTrxActive() ) {
				// Explict transactions may never happen here in practice.  Log to be sure.
				if ( !$explictTrxLogged ) {
					$explictTrxLogged = true;
					LoggerFactory::getInstance( 'wfDebug' )->debug(
						'explicit transaction active in ' . __METHOD__ . ' while deleting {title}', [
						'title' => $this->page->getTitle()->getText(),
					] );
				}
				continue;
			}
			if ( $dbw->trxLevel() ) {
				$dbw->commit( __METHOD__ );
			}
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$lbFactory->waitForReplication();
			$dbw->startAtomic( __METHOD__ );
		}

		// If done archiving, also delete the article.
		if ( !$done ) {
			$dbw->endAtomic( __METHOD__ );

			$jobParams = [
				'namespace' => $this->page->getTitle()->getNamespace(),
				'title' => $this->page->getTitle()->getDBkey(),
				'wikiPageId' => $id,
				'requestId' => $webRequestId ?? WebRequest::getRequestId(),
				'reason' => $reason,
				'suppress' => $suppress,
				'userId' => $this->deleter->getId(),
				'tags' => json_encode( $tags ),
				'logsubtype' => $logsubtype,
			];

			$job = new DeletePageJob( $jobParams );
			JobQueueGroup::singleton()->push( $job );

			$status->warning( 'delete-scheduled',
				wfEscapeWikiText( $this->page->getTitle()->getPrefixedText() ) );
		} else {
			// Get archivedRevisionCount by db query, because there's no better alternative.
			// Jobs cannot pass a count of archived revisions to the next job, because additional
			// deletion operations can be started while the first is running.  Jobs from each
			// gracefully interleave, but would not know about each other's count.  Deduplication
			// in the job queue to avoid simultaneous deletion operations would add overhead.
			// Number of archived revisions cannot be known beforehand, because edits can be made
			// while deletion operations are being processed, changing the number of archivals.
			$archivedRevisionCount = (int)$dbw->selectField(
				'archive', 'COUNT(*)',
				[
					'ar_namespace' => $this->page->getTitle()->getNamespace(),
					'ar_title' => $this->page->getTitle()->getDBkey(),
					'ar_page_id' => $id
				], __METHOD__
			);

			// Clone the title and wikiPage, so we have the information we need when
			// we log and run the ArticleDeleteComplete hook.
			$logTitle = clone $this->page->getTitle();
			$wikiPageBeforeDelete = clone $this->page;

			// Now that it's safely backed up, delete it
			$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );

			// Log the deletion, if the page was suppressed, put it in the suppression log instead
			$logtype = $suppress ? 'suppress' : 'delete';

			$logEntry = new ManualLogEntry( $logtype, $logsubtype );
			$logEntry->setPerformer( $this->deleter );
			$logEntry->setTarget( $logTitle );
			$logEntry->setComment( $reason );
			$logEntry->addTags( $tags );
			$logid = $logEntry->insert();

			$dbw->onTransactionPreCommitOrIdle(
				static function () use ( $logEntry, $logid ) {
					// T58776: avoid deadlocks (especially from FileDeleteForm)
					$logEntry->publish( $logid );
				},
				__METHOD__
			);

			$dbw->endAtomic( __METHOD__ );

			$this->doDeleteUpdates(
				$id,
				$content,
				$revisionRecord
			);

			$legacyDeleter = MediaWikiServices::getInstance()
				->getUserFactory()
				->newFromUserIdentity( $this->deleter );
			$this->hookRunner->onArticleDeleteComplete(
				$wikiPageBeforeDelete,
				$legacyDeleter,
				$reason,
				$id,
				$content,
				$logEntry,
				$archivedRevisionCount
			);
			$status->value = $logid;

			// Show log excerpt on 404 pages rather than just a link
			$dbCache = ObjectCache::getInstance( 'db-replicated' );
			$key = $dbCache->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
			$dbCache->set( $key, 1, $dbCache::TTL_DAY );
		}

		return $status;
	}

	/**
	 * Archives revisions as part of page deletion.
	 *
	 * @param IDatabase $dbw
	 * @param int $id
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @return bool
	 */
	private function archiveRevisions( $dbw, $id, $suppress ) {
		global $wgDeleteRevisionsBatchSize, $wgActorTableSchemaMigrationStage;

		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->page->getTitle()->getNamespace();
		$dbKey = $this->page->getTitle()->getDBkey();

		$commentStore = CommentStore::getStore();

		$revQuery = $this->revisionStore->getQueryInfo();
		$bitfield = false;

		// Bitfields to further suppress the content
		if ( $suppress ) {
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

		// Get as many of the page revisions as we are allowed to.  The +1 lets us recognize the
		// unusual case where there were exactly $wgDeleteRevisionBatchSize revisions remaining.
		$res = $dbw->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $id ],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC, rev_id ASC', 'LIMIT' => $wgDeleteRevisionsBatchSize + 1 ],
			$revQuery['joins']
		);

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] Revision IDs of edits that were made by IPs */
		$ipRevIds = [];

		$done = true;
		foreach ( $res as $row ) {
			if ( count( $revids ) >= $wgDeleteRevisionsBatchSize ) {
				$done = false;
				break;
			}

			$comment = $commentStore->getComment( 'rev_comment', $row );
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
					'ar_deleted'    => $suppress ? $bitfield : $row->rev_deleted,
					'ar_sha1'       => $row->rev_sha1,
				] + $commentStore->insert( $dbw, 'ar_comment', $comment );

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
			if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_TEMP ) {
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
	 * @param int $id The page_id value of the page being deleted
	 * @param Content|null $content Page content to be used when determining
	 *   the required updates. This may be needed because $this->page->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param RevisionRecord|null $revRecord The current page revision at the time of
	 *   deletion, used when determining the required updates. This may be needed because
	 *   $this->page->getRevisionRecord() may already return null when the page proper was deleted.
	 * @param UserIdentity|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		$id,
		Content $content = null,
		RevisionRecord $revRecord = null,
		UserIdentity $user = null
	) {
		if ( $id !== $this->page->getId() ) {
			throw new InvalidArgumentException( 'Mismatching page ID' );
		}

		try {
			$countable = $this->page->isCountable();
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => 1, 'articles' => -$countable, 'pages' => -1 ]
		) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates( $revRecord ?: $content );
		foreach ( $updates as $update ) {
			DeferredUpdates::addUpdate( $update );
		}

		$causeAgent = $user ? $user->getName() : 'unknown';
		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$this->page->getTitle(), 'templatelinks', 'delete-page', $causeAgent );
		// Reparse any pages including this image
		if ( $this->page->getTitle()->getNamespace() === NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$this->page->getTitle(), 'imagelinks', 'delete-page', $causeAgent );
		}

		// Clear caches
		WikiPage::onArticleDelete( $this->page->getTitle() );

		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->page->getTitle(),
			$revRecord,
			null,
			WikiMap::getCurrentWikiDbDomain()->getId()
		);

		// Reset the page object and the Title object
		$this->page->loadFromRow( false, WikiPage::READ_LATEST );

		// Search engine
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $this->page->getTitle() ) );
	}

	/**
	 * @private Public for BC only
	 * Returns a list of updates to be performed when the page is deleted. The
	 * updates should remove any information about this page from secondary data
	 * stores such as links tables.
	 *
	 * @param RevisionRecord|Content|null $rev The revision being deleted. Also accepts a Content
	 *       object for backwards compatibility.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( $rev = null ) {
		if ( !$rev ) {
			wfDeprecated( __METHOD__ . ' without a RevisionRecord', '1.32' );

			try {
				$rev = $this->page->getRevisionRecord();
			} catch ( Exception $ex ) {
				// If we can't load the content, something is wrong. Perhaps that's why
				// the user is trying to delete the page, so let's not fail in that case.
				// Note that doDeleteArticleReal() will already have logged an issue with
				// loading the content.
				wfDebug( __METHOD__ . ' failed to load current revision of page ' . $this->page->getId() );
			}
		}

		if ( !$rev ) {
			$slotContent = [];
		} elseif ( $rev instanceof Content ) {
			wfDeprecated( __METHOD__ . ' with a Content object instead of a RevisionRecord', '1.32' );

			$slotContent = [ SlotRecord::MAIN => $rev ];
		} else {
			$slotContent = array_map( static function ( SlotRecord $slot ) {
				return $slot->getContent();
			}, $rev->getSlots()->getSlots() );
		}

		$allUpdates = [ new LinksDeletionUpdate( $this->page ) ];

		// NOTE: once Content::getDeletionUpdates() is removed, we only need to content
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
