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

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
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

	/** @var PageIdentity Page from which history will be merged */
	protected $source;

	/** @var PageIdentity Page to which history will be merged */
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

	/** @var int Number of revisions merged (for Special:MergeHistory success message) */
	protected $revisionsMerged;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var SpamChecker */
	private $spamChecker;

	/** @var HookRunner */
	private $hookRunner;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param PageIdentity $source Page from which history will be merged
	 * @param PageIdentity $dest Page to which history will be merged
	 * @param ?string $timestamp Timestamp up to which history from the source will be merged
	 * @param ILoadBalancer $loadBalancer
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param RevisionStore $revisionStore
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param SpamChecker $spamChecker
	 * @param HookContainer $hookContainer
	 * @param WikiPageFactory $wikiPageFactory
	 * @param TitleFormatter $titleFormatter
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		PageIdentity $source,
		PageIdentity $dest,
		?string $timestamp,
		ILoadBalancer $loadBalancer,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStore $revisionStore,
		WatchedItemStoreInterface $watchedItemStore,
		SpamChecker $spamChecker,
		HookContainer $hookContainer,
		WikiPageFactory $wikiPageFactory,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		// Save the parameters
		$this->source = $source;
		$this->dest = $dest;
		$this->timestamp = $timestamp;

		// Get the database
		$this->dbw = $loadBalancer->getConnectionRef( DB_PRIMARY );

		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStore = $revisionStore;
		$this->watchedItemStore = $watchedItemStore;
		$this->spamChecker = $spamChecker;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->wikiPageFactory = $wikiPageFactory;
		$this->titleFormatter = $titleFormatter;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * Get the number of revisions that will be moved
	 * @return int
	 */
	public function getRevisionCount() {
		$count = $this->dbw->selectRowCount( 'revision', '1',
			[ 'rev_page' => $this->source->getId(), $this->getTimeWhere() ],
			__METHOD__,
			[ 'LIMIT' => self::REVISION_LIMIT + 1 ]
		);

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
	public function probablyCanMerge( Authority $performer, string $reason = null ): PermissionStatus {
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
	public function authorizeMerge( Authority $performer, string $reason = null ): PermissionStatus {
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

		$this->dbw->startAtomic( __METHOD__ );

		$this->dbw->update(
			'revision',
			[ 'rev_page' => $this->dest->getId() ],
			[ 'rev_page' => $this->source->getId(), $this->getTimeWhere() ],
			__METHOD__
		);

		// Check if this did anything
		$this->revisionsMerged = $this->dbw->affectedRows();
		if ( $this->revisionsMerged < 1 ) {
			$this->dbw->endAtomic( __METHOD__ );
			return $status->fatal( 'mergehistory-fail-no-change' );
		}

		$haveRevisions = $this->dbw->lockForUpdate(
			'revision',
			[ 'rev_page' => $this->source->getId() ],
			__METHOD__
		);

		$legacySource = $this->titleFactory->castFromPageIdentity( $this->source );
		$legacyDest = $this->titleFactory->castFromPageIdentity( $this->dest );

		// Update source page, histories and invalidate caches
		if ( !$haveRevisions ) {
			if ( $reason ) {
				$reason = wfMessage(
					'mergehistory-comment',
					$this->titleFormatter->getPrefixedText( $this->source ),
					$this->titleFormatter->getPrefixedText( $this->dest ),
					$reason
				)->inContentLanguage()->text();
			} else {
				$reason = wfMessage(
					'mergehistory-autocomment',
					$this->titleFormatter->getPrefixedText( $this->source ),
					$this->titleFormatter->getPrefixedText( $this->dest )
				)->inContentLanguage()->text();
			}

			$this->updateSourcePage( $status, $performer->getUser(), $reason );

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
			'5::mergepoint' => $this->getTimestampLimit()->getTimestamp( TS_MW )
		] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
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
	 * @param UserIdentity $user
	 * @param string $reason
	 *
	 * @return Status
	 */
	private function updateSourcePage( $status, $user, $reason ) {
		$deleteSource = false;
		$legacySourceTitle = $this->titleFactory->castFromPageIdentity( $this->source );
		$legacyDestTitle = $this->titleFactory->castFromPageIdentity( $this->dest );
		$sourceModel = $legacySourceTitle->getContentModel();
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $sourceModel );

		if ( !$contentHandler->supportsRedirects() ) {
			$deleteSource = true;
			$newContent = $contentHandler->makeEmptyContent();
		} else {
			$msg = wfMessage( 'mergehistory-redirect-text' )->inContentLanguage()->plain();
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
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
					'value' => gettype( $newContent ),
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
		$comment = CommentStoreComment::newUnsavedComment( $reason );
		$revRecord = new MutableRevisionRecord( $this->source );
		$revRecord->setContent( SlotRecord::MAIN, $newContent )
			->setPageId( $this->source->getId() )
			->setComment( $comment )
			->setUser( $user )
			->setTimestamp( wfTimestampNow() );

		$insertedRevRecord = $this->revisionStore->insertRevisionOn( $revRecord, $this->dbw );

		$newPage = $this->wikiPageFactory->newFromTitle( $this->source );
		$newPage->updateRevisionOn( $this->dbw, $insertedRevRecord );

		if ( !$deleteSource ) {
			// We have created a redirect page so let's
			// record the link from the page to the new title.
			// It should have no other outgoing links...
			$this->dbw->delete(
				'pagelinks',
				[ 'pl_from' => $this->dest->getId() ],
				__METHOD__
			);
			$this->dbw->insert( 'pagelinks',
				[
					'pl_from' => $this->dest->getId(),
					'pl_from_namespace' => $this->dest->getNamespace(),
					'pl_namespace' => $this->dest->getNamespace(),
					'pl_title' => $this->dest->getDBkey() ],
				__METHOD__
			);

		} else {
			// T263340/T93469: Delete the source page to prevent errors because its
			// revisions are now tied to a different title and its content model
			// does not support redirects, so we cannot leave a new revision on it.
			// This deletion does not depend on userright but may still fails. If it
			// fails, it will be communicated in the status response.
			$reason = wfMessage( 'mergehistory-source-deleted-reason' )->inContentLanguage()->plain();
			$deletionStatus = $newPage->doDeleteArticleReal( $reason, $user );
			// Notify callers that the source page has been deleted.
			$status->value = 'source-deleted';
			$status->merge( $deletionStatus );
		}

		return $status;
	}

	/**
	 * Get the maximum timestamp that we can use (oldest timestamp of dest)
	 *
	 * @return MWTimestamp
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
	 *
	 * @return ?MWTimestamp
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
	 *
	 * @return ?string
	 */
	private function getTimeWhere(): ?string {
		if ( $this->timeWhere === false ) {
			$this->initTimestampLimits();
		}
		return $this->timeWhere;
	}

	/**
	 * Lazily initializes timestamp limits and conditions.
	 */
	private function initTimestampLimits() {
		// Max timestamp should be min of destination page
		$firstDestTimestamp = $this->dbw->selectField(
			'revision',
			'MIN(rev_timestamp)',
			[ 'rev_page' => $this->dest->getId() ],
			__METHOD__
		);
		$this->maxTimestamp = new MWTimestamp( $firstDestTimestamp );

		// Get the timestamp pivot condition
		try {
			if ( $this->timestamp ) {
				// If we have a requested timestamp, use the
				// latest revision up to that point as the insertion point
				$mwTimestamp = new MWTimestamp( $this->timestamp );
				$lastWorkingTimestamp = $this->dbw->selectField(
					'revision',
					'MAX(rev_timestamp)',
					[
						'rev_timestamp <= ' .
							$this->dbw->addQuotes( $this->dbw->timestamp( $mwTimestamp ) ),
						'rev_page' => $this->source->getId()
					],
					__METHOD__
				);
				$mwLastWorkingTimestamp = new MWTimestamp( $lastWorkingTimestamp );

				$timeInsert = $mwLastWorkingTimestamp;
				$this->timestampLimit = $mwLastWorkingTimestamp;
			} else {
				// If we don't, merge entire source page history into the
				// beginning of destination page history

				// Get the latest timestamp of the source
				$lastSourceTimestamp = $this->dbw->selectField(
					[ 'page', 'revision' ],
					'rev_timestamp',
					[ 'page_id' => $this->source->getId(),
						'page_latest = rev_id'
					],
					__METHOD__
				);
				$lasttimestamp = new MWTimestamp( $lastSourceTimestamp );

				$timeInsert = $this->maxTimestamp;
				$this->timestampLimit = $lasttimestamp;
			}

			$this->timeWhere = "rev_timestamp <= " .
				$this->dbw->addQuotes( $this->dbw->timestamp( $timeInsert ) );
		} catch ( TimestampException $ex ) {
			// The timestamp we got is screwed up and merge cannot continue
			// This should be detected by $this->isValidMerge()
			$this->timestampLimit = null;
			$this->timeWhere = null;
		}
	}
}
