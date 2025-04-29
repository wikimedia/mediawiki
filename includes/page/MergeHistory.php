<?php

/**
 * Copyright © 2015 Geoffrey Mon <geofbot@gmail.com>
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

namespace MediaWiki\Page;

use InvalidArgumentException;
use MediaWiki;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Timestamp\TimestampException;

/**
 * Handles the backend logic of merging the histories of two
 * pages.
 *
 * @since 1.27
 */
class MergeHistory {

	/** Maximum number of revisions that can be merged at once */
	public const REVISION_LIMIT = 5000;

	/** @var ProperPageIdentity Page from which history will be merged */
	protected $source;

	/** @var ProperPageIdentity Page to which history will be merged */
	protected $dest;

	/** @var IDatabase Database that we are using */
	protected $dbw;

	/** @var ?string Timestamp up to which history from the source will be merged */
	private $timestamp;

	/**
	 * @var MWTimestamp|false Maximum timestamp that we can use (oldest timestamp of dest).
	 * Use ::getMaxTimestamp to lazily initialize.
	 */
	protected $maxTimestamp = false;

	/**
	 * @var string|false|null SQL WHERE condition that selects source revisions
	 * to insert into destination. Use ::getTimeWhere to lazy-initialize.
	 */
	protected $timeWhere = false;

	/**
	 * @var MWTimestamp|false|null Timestamp upto which history from the source will be merged.
	 * Use getTimestampLimit to lazily initialize.
	 */
	protected $timestampLimit = false;

	/**
	 * @var string|null
	 */
	private $revidLimit = null;

	/** @var int Number of revisions merged (for Special:MergeHistory success message) */
	protected $revisionsMerged;

	private IContentHandlerFactory $contentHandlerFactory;
	private WatchedItemStoreInterface $watchedItemStore;
	private SpamChecker $spamChecker;
	private HookRunner $hookRunner;
	private PageUpdaterFactory $pageUpdaterFactory;
	private TitleFormatter $titleFormatter;
	private TitleFactory $titleFactory;
	private DeletePageFactory $deletePageFactory;

	/**
	 * @param PageIdentity $source Page from which history will be merged
	 * @param PageIdentity $dest Page to which history will be merged
	 * @param ?string $timestamp Timestamp up to which history from the source will be merged
	 * @param IConnectionProvider $dbProvider
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param SpamChecker $spamChecker
	 * @param HookContainer $hookContainer
	 * @param PageUpdaterFactory $pageUpdaterFactory
	 * @param TitleFormatter $titleFormatter
	 * @param TitleFactory $titleFactory
	 * @param DeletePageFactory $deletePageFactory
	 */
	public function __construct(
		PageIdentity $source,
		PageIdentity $dest,
		?string $timestamp,
		IConnectionProvider $dbProvider,
		IContentHandlerFactory $contentHandlerFactory,
		WatchedItemStoreInterface $watchedItemStore,
		SpamChecker $spamChecker,
		HookContainer $hookContainer,
		PageUpdaterFactory $pageUpdaterFactory,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory,
		DeletePageFactory $deletePageFactory
	) {
		// Save the parameters
		$this->source = self::toProperPageIdentity( $source, '$source' );
		$this->dest = self::toProperPageIdentity( $dest, '$dest' );
		$this->timestamp = $timestamp;

		// Get the database
		$this->dbw = $dbProvider->getPrimaryDatabase();

		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->watchedItemStore = $watchedItemStore;
		$this->spamChecker = $spamChecker;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->pageUpdaterFactory = $pageUpdaterFactory;
		$this->titleFormatter = $titleFormatter;
		$this->titleFactory = $titleFactory;
		$this->deletePageFactory = $deletePageFactory;
	}

	private static function toProperPageIdentity(
		PageIdentity $page,
		string $name
	): ProperPageIdentity {
		// Make sure $source and $dest are proper pages
		if ( $page instanceof Title ) {
			$page = $page->toPageIdentity();
		}

		Assert::parameterType(
			ProperPageIdentity::class,
			$page,
			$name
		);
		'@phan-var ProperPageIdentity $page';

		return $page;
	}

	/**
	 * Get the number of revisions that will be moved
	 * @return int
	 */
	public function getRevisionCount() {
		$count = $this->dbw->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->where( [ 'rev_page' => $this->source->getId(), $this->getTimeWhere() ] )
			->limit( self::REVISION_LIMIT + 1 )
			->caller( __METHOD__ )->fetchRowCount();

		return $count;
	}

	/**
	 * Get the number of revisions that were moved
	 * Used in the SpecialMergeHistory success message
	 * @return int
	 */
	public function getMergedRevisionCount() {
		return $this->revisionsMerged;
	}

	/**
	 * @param callable $authorizer ( string $action, PageIdentity $target, PermissionStatus $status )
	 * @param Authority $performer
	 * @param string $reason
	 * @return PermissionStatus
	 */
	private function authorizeInternal(
		callable $authorizer,
		Authority $performer,
		string $reason
	) {
		$status = PermissionStatus::newEmpty();

		$authorizer( 'edit', $this->source, $status );
		$authorizer( 'edit', $this->dest, $status );

		// Anti-spam
		if ( $this->spamChecker->checkSummary( $reason ) !== false ) {
			// This is kind of lame, won't display nice
			$status->fatal( 'spamprotectiontext' );
		}

		// Check mergehistory permission
		if ( !$performer->isAllowed( 'mergehistory' ) ) {
			// User doesn't have the right to merge histories
			$status->fatal( 'mergehistory-fail-permission' );
		}
		return $status;
	}

	/**
	 * Check whether $performer can execute the merge.
	 *
	 * @note this method does not guarantee full permissions check, so it should
	 * only be used to to decide whether to show a merge form. To authorize the merge
	 * action use {@link self::authorizeMerge} instead.
	 *
	 * @param Authority $performer
	 * @param string|null $reason
	 * @return PermissionStatus
	 */
	public function probablyCanMerge( Authority $performer, ?string $reason = null ): PermissionStatus {
		return $this->authorizeInternal(
			static function ( string $action, PageIdentity $target, PermissionStatus $status ) use ( $performer ) {
				return $performer->probablyCan( $action, $target, $status );
			},
			$performer,
			$reason
		);
	}

	/**
	 * Authorize the merge by $performer.
	 *
	 * @note this method should be used right before the actual merge is performed.
	 * To check whether a current performer has the potential to merge the history,
	 * use {@link self::probablyCanMerge} instead.
	 *
	 * @param Authority $performer
	 * @param string|null $reason
	 * @return PermissionStatus
	 */
	public function authorizeMerge( Authority $performer, ?string $reason = null ): PermissionStatus {
		return $this->authorizeInternal(
			static function ( string $action, PageIdentity $target, PermissionStatus $status ) use ( $performer ) {
				return $performer->authorizeWrite( $action, $target, $status );
			},
			$performer,
			$reason
		);
	}

	/**
	 * Does various checks that the merge is
	 * valid. Only things based on the two pages
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMerge() {
		$status = new Status();

		// If either article ID is 0, then revisions cannot be reliably selected
		if ( $this->source->getId() === 0 ) {
			$status->fatal( 'mergehistory-fail-invalid-source' );
		}
		if ( $this->dest->getId() === 0 ) {
			$status->fatal( 'mergehistory-fail-invalid-dest' );
		}

		// Make sure page aren't the same
		if ( $this->source->isSamePageAs( $this->dest ) ) {
			$status->fatal( 'mergehistory-fail-self-merge' );
		}

		// Make sure the timestamp is valid
		if ( !$this->getTimestampLimit() ) {
			$status->fatal( 'mergehistory-fail-bad-timestamp' );
		}

		// $this->timestampLimit must be older than $this->maxTimestamp
		if ( $this->getTimestampLimit() > $this->getMaxTimestamp() ) {
			$status->fatal( 'mergehistory-fail-timestamps-overlap' );
		}

		// Check that there are not too many revisions to move
		if ( $this->getTimestampLimit() && $this->getRevisionCount() > self::REVISION_LIMIT ) {
			$status->fatal( 'mergehistory-fail-toobig', Message::numParam( self::REVISION_LIMIT ) );
		}

		return $status;
	}

	/**
	 * Actually attempt the history move
	 *
	 * @todo if all versions of page A are moved to B and then a user
	 * tries to do a reverse-merge via the "unmerge" log link, then page
	 * A will still be a redirect (as it was after the original merge),
	 * though it will have the old revisions back from before (as expected).
	 * The user may have to "undo" the redirect manually to finish the "unmerge".
	 * Maybe this should delete redirects at the source page of merges?
	 *
	 * @param Authority $performer
	 * @param string $reason
	 * @return Status status of the history merge
	 */
	public function merge( Authority $performer, $reason = '' ) {
		$status = new Status();

		// Check validity and permissions required for merge
		$validCheck = $this->isValidMerge(); // Check this first to check for null pages
		if ( !$validCheck->isOK() ) {
			return $validCheck;
		}
		$permCheck = $this->authorizeMerge( $performer, $reason );
		if ( !$permCheck->isOK() ) {
			return Status::wrap( $permCheck );
		}

		$updater = $this->pageUpdaterFactory->newPageUpdater(
			$this->source,
			$performer->getUser()
		);

		$this->dbw->startAtomic( __METHOD__ );
		$updater->grabParentRevision(); // preserve latest revision for later

		$this->dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => $this->dest->getId() ] )
			->where( [ 'rev_page' => $this->source->getId(), $this->getTimeWhere() ] )
			->caller( __METHOD__ )->execute();

		// Check if this did anything
		$this->revisionsMerged = $this->dbw->affectedRows();
		if ( $this->revisionsMerged < 1 ) {
			$this->dbw->endAtomic( __METHOD__ );
			return $status->fatal( 'mergehistory-fail-no-change' );
		}

		$haveRevisions = $this->dbw->newSelectQueryBuilder()
			->from( 'revision' )
			->where( [ 'rev_page' => $this->source->getId() ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchRowCount();

		$legacySource = $this->titleFactory->newFromPageIdentity( $this->source );
		$legacyDest = $this->titleFactory->newFromPageIdentity( $this->dest );

		// Update source page, histories and invalidate caches
		if ( !$haveRevisions ) {
			if ( $reason ) {
				$revisionComment = wfMessage(
					'mergehistory-comment',
					$this->titleFormatter->getPrefixedText( $this->source ),
					$this->titleFormatter->getPrefixedText( $this->dest ),
					$reason
				)->inContentLanguage()->text();
			} else {
				$revisionComment = wfMessage(
					'mergehistory-autocomment',
					$this->titleFormatter->getPrefixedText( $this->source ),
					$this->titleFormatter->getPrefixedText( $this->dest )
				)->inContentLanguage()->text();
			}

			$this->updateSourcePage( $status, $performer, $revisionComment, $updater );

		} else {
			$legacySource->invalidateCache();
		}
		$legacyDest->invalidateCache();

		// Duplicate watchers of the old article to the new article
		$this->watchedItemStore->duplicateAllAssociatedEntries( $this->source, $this->dest );

		// Update our logs
		$logEntry = new ManualLogEntry( 'merge', 'merge' );
		$logEntry->setPerformer( $performer->getUser() );
		$logEntry->setComment( $reason );
		$logEntry->setTarget( $this->source );
		$logEntry->setParameters( [
			'4::dest' => $this->titleFormatter->getPrefixedText( $this->dest ),
			'5::mergepoint' => $this->getTimestampLimit()->getTimestamp( TS_MW ),
			'6::mergerevid' => $this->revidLimit
		] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		$this->hookRunner->onArticleMergeComplete( $legacySource, $legacyDest );

		$this->dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * Do various cleanup work and updates to the source page. This method
	 * will only be called if no revision is remaining on the page.
	 *
	 * At the end, there would be either a redirect page or a deleted page,
	 * depending on whether the content model of the page supports redirects or not.
	 *
	 * @param Status $status
	 * @param Authority $performer
	 * @param string $revisionComment Edit summary for the redirect or empty revision
	 *   to be created in place of the source page
	 * @param PageUpdater $updater For turning the source page into a redirect
	 */
	private function updateSourcePage( $status, $performer, $revisionComment, PageUpdater $updater ): void {
		$deleteSource = false;
		$legacySourceTitle = $this->titleFactory->newFromPageIdentity( $this->source );
		$legacyDestTitle = $this->titleFactory->newFromPageIdentity( $this->dest );
		$sourceModel = $legacySourceTitle->getContentModel();
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $sourceModel );

		if ( !$contentHandler->supportsRedirects() || (
			// Do not create redirects for wikitext message overrides (T376399).
			// Maybe one day they will have a custom content model and this special case won't be needed.
			$legacySourceTitle->getNamespace() === NS_MEDIAWIKI &&
			$legacySourceTitle->getContentModel() === CONTENT_MODEL_WIKITEXT
		) ) {
			$deleteSource = true;
			$newContent = $contentHandler->makeEmptyContent();
		} else {
			$msg = wfMessage( 'mergehistory-redirect-text' )->inContentLanguage()->plain();
			$newContent = $contentHandler->makeRedirectContent( $legacyDestTitle, $msg );
		}

		if ( !$newContent instanceof Content ) {
			// Handler supports redirect but cannot create new redirect content?
			// Not possible to proceed without Content.

			// @todo. Remove this once there's no evidence it's happening or if it's
			// determined all violating handlers have been fixed.
			// This is mostly kept because previous code was also blindly checking
			// existing of the Content for both content models that supports redirects
			// and those that that don't, so it's hard to know what it was masking.
			$logger = MediaWiki\Logger\LoggerFactory::getInstance( 'ContentHandler' );
			$logger->warning(
				'ContentHandler for {model} says it supports redirects but failed '
				. 'to return Content object from ContentHandler::makeRedirectContent().'
				. ' {value} returned instead.',
				[
					'value' => get_debug_type( $newContent ),
					'model' => $sourceModel
				]
			);

			throw new InvalidArgumentException(
				"ContentHandler for '$sourceModel' supports redirects" .
				' but cannot create redirect content during History merge.'
			);
		}

		// T263340/T93469: Create revision record to also serve as the page revision.
		// This revision will be used to create page content. If the source page's
		// content model supports redirects, then it will be the redirect content.
		// If the content model does not supports redirect, this content will aid
		// proper deletion of the page below.

		$updater->setContent( SlotRecord::MAIN, $newContent )
			->setHints( [ 'suppressDerivedDataUpdates' => $deleteSource ] )
			->saveRevision( $revisionComment, EDIT_INTERNAL | EDIT_IMPLICIT | EDIT_SILENT );

		if ( $deleteSource ) {
			// T263340/T93469: Delete the source page to prevent errors because its
			// revisions are now tied to a different title and its content model
			// does not support redirects, so we cannot leave a new revision on it.
			// This deletion does not depend on userright but may still fails. If it
			// fails, it will be communicated in the status response.
			$reason = wfMessage( 'mergehistory-source-deleted-reason' )->inContentLanguage()->plain();
			$delPage = $this->deletePageFactory->newDeletePage( $this->source, $performer );
			$deletionStatus = $delPage->deleteUnsafe( $reason );
			if ( $deletionStatus->isGood() && $delPage->deletionsWereScheduled()[DeletePage::PAGE_BASE] ) {
				$deletionStatus->warning(
					'delete-scheduled',
					wfEscapeWikiText( $this->titleFormatter->getPrefixedText( $this->source ) )
				);
			}
			// Notify callers that the source page has been deleted.
			$status->value = 'source-deleted';
			$status->merge( $deletionStatus );
		}
	}

	/**
	 * Get the maximum timestamp that we can use (oldest timestamp of dest)
	 */
	private function getMaxTimestamp(): MWTimestamp {
		if ( $this->maxTimestamp === false ) {
			$this->initTimestampLimits();
		}
		return $this->maxTimestamp;
	}

	/**
	 * Get the timestamp upto which history from the source will be merged,
	 * or null if something went wrong
	 */
	private function getTimestampLimit(): ?MWTimestamp {
		if ( $this->timestampLimit === false ) {
			$this->initTimestampLimits();
		}
		return $this->timestampLimit;
	}

	/**
	 * Get the SQL WHERE condition that selects source revisions to insert into destination,
	 * or null if something went wrong
	 */
	private function getTimeWhere(): ?string {
		if ( $this->timeWhere === false ) {
			$this->initTimestampLimits();
		}
		return $this->timeWhere;
	}

	/**
	 * Lazily initializes timestamp (and possibly revid) limits and conditions.
	 */
	private function initTimestampLimits() {
		// Max timestamp should be min of destination page
		$firstDestTimestamp = $this->dbw->newSelectQueryBuilder()
			->select( 'MIN(rev_timestamp)' )
			->from( 'revision' )
			->where( [ 'rev_page' => $this->dest->getId() ] )
			->caller( __METHOD__ )->fetchField();
		$this->maxTimestamp = new MWTimestamp( $firstDestTimestamp );
		$this->revidLimit = null;
		// Get the timestamp pivot condition
		try {
			if ( $this->timestamp ) {
				$parts = explode( '|', $this->timestamp );
				if ( count( $parts ) == 2 ) {
					$timestamp = $parts[0];
					$this->revidLimit = $parts[1];
				} else {
					$timestamp = $this->timestamp;
				}
				// If we have a requested timestamp, use the
				// latest revision up to that point as the insertion point
				$mwTimestamp = new MWTimestamp( $timestamp );

				$lastWorkingTimestamp = $this->dbw->newSelectQueryBuilder()
					->select( 'MAX(rev_timestamp)' )
					->from( 'revision' )
					->where( [
						$this->dbw->expr( 'rev_timestamp', '<=', $this->dbw->timestamp( $mwTimestamp ) ),
						'rev_page' => $this->source->getId()
					] )
					->caller( __METHOD__ )->fetchField();
				$mwLastWorkingTimestamp = new MWTimestamp( $lastWorkingTimestamp );

				$timeInsert = $mwLastWorkingTimestamp;
				$this->timestampLimit = $mwLastWorkingTimestamp;
			} else {
				// If we don't, merge entire source page history into the
				// beginning of destination page history

				// Get the latest timestamp of the source
				$row = $this->dbw->newSelectQueryBuilder()
					->select( [ 'rev_timestamp', 'rev_id' ] )
					->from( 'page' )
					->join( 'revision', null, 'page_latest = rev_id' )
					->where( [ 'page_id' => $this->source->getId() ] )
					->caller( __METHOD__ )->fetchRow();
				$timeInsert = $this->maxTimestamp;
				if ( $row ) {
					$lasttimestamp = new MWTimestamp( $row->rev_timestamp );
					$this->timestampLimit = $lasttimestamp;
					$this->revidLimit = $row->rev_id;
				} else {
					$this->timestampLimit = null;
				}
			}
			$dbLimit = $this->dbw->timestamp( $timeInsert );
			if ( $this->revidLimit ) {
				$this->timeWhere = $this->dbw->buildComparison( '<=',
					[ 'rev_timestamp' => $dbLimit, 'rev_id' => $this->revidLimit ]
				);
			} else {
				$this->timeWhere = $this->dbw->buildComparison( '<=',
					[ 'rev_timestamp' => $dbLimit ]
				);
			}
		} catch ( TimestampException $ex ) {
			// The timestamp we got is screwed up and merge cannot continue
			// This should be detected by $this->isValidMerge()
			$this->timestampLimit = null;
			$this->timeWhere = null;
		}
	}
}
