<?php
/**
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

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\Actions\InfoAction;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Deferred\RefreshSecondaryDataUpdate;
use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\Utils\BacklinkJobUtils;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageAssertionException;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Stats\StatsFactory;

/**
 * Job to update link tables for rerendered wiki pages.
 *
 * This job comes in a few variants:
 *
 * - a) Recursive jobs to update links for backlink pages for a given title.
 *      Scheduled by {@see LinksUpdate::queueRecursiveJobsForTable()}; used to
 *      refresh pages which link/transclude a given title.
 *      These jobs have (recursive:true,table:<table>) set. They just look up
 *      which pages link to the job title and schedule them as a set of non-recursive
 *      RefreshLinksJob jobs (and possible one new recursive job as a way of
 *      continuation).
 * - b) Jobs to update links for a set of pages (the job title is ignored).
 *      These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
 * - c) Jobs to update links for a single page (the job title).
 *      These jobs need no extra fields set.
 *
 * Job parameters for all jobs:
 * - recursive (bool): When false, updates the current page. When true, updates
 *   the pages which link/transclude the current page.
 * - triggeringRevisionId (int): The revision of the edit which caused the link
 *   refresh. For manually triggered updates, the last revision of the page (at the
 *   time of scheduling).
 * - triggeringUser (array): The user who triggered the refresh, in the form of a
 *   [ 'userId' => int, 'userName' => string ] array. This is not necessarily the user
 *   who created the revision.
 * - triggeredRecursive (bool): Set on all jobs which were partitioned from another,
 *   recursive job. For debugging.
 * - Standard deduplication params (see {@see JobQueue::deduplicateRootJob()}).
 * For recursive jobs:
 * - table (string): Which table to use (imagelinks or templatelinks) when searching for
 *   affected pages.
 * - range (array): Used for recursive jobs when some pages have already been partitioned
 *    into separate jobs. Contains the list of ranges that still need to be partitioned.
 *    See {@see BacklinkJobUtils::partitionBacklinkJob()}.
 * - division: Number of times the job was partitioned already (for debugging).
 * For non-recursive jobs:
 * - pages (array): Associative array of [ <page ID> => [ <namespace>, <dbkey> ] ].
 *   Might be omitted, then the job title will be used.
 * - isOpportunistic (bool): Set for opportunistic single-page updates. These are "free"
 *   updates that are queued when most of the work needed to be performed anyway for
 *   non-linkrefresh-related reasons, and can be more easily discarded if they don't seem
 *   useful. See {@see WikiPage::triggerOpportunisticLinksUpdate()}.
 * - useRecursiveLinksUpdate (bool): When true, triggers recursive jobs for each page.
 *
 * Metrics:
 * - `refreshlinks_superseded_updates_total`: The number of times the job was cancelled
 *    because the target page had already been refreshed by a different edit or job.
 *    The job is considered to have succeeded in this case.
 *
 * - `refreshlinks_warnings_total`: The number of times the job failed due to a recoverable issue.
 *    Possible `reason` label values include:
 *    - `lag_wait_failed`: The job timed out while waiting for replication.
 *
 * - `refreshlinks_failures_total`: The number of times the job failed.
 *   The `reason` label may be:
 *   - `page_not_found`: The target page did not exist.
 *   - `rev_not_current`: The target revision was no longer the latest revision for the target page.
 *   - `rev_not_found`: The target revision was not found.
 *   - `lock_failure`: The job failed to acquire an exclusive lock to refresh the target page.
 *
 * - `refreshlinks_parsercache_operations_total`: The number of times the job attempted
 *   to fetch parser output from the parser cache.
 *   Possible `status` label values include:
 *   - `cache_hit`: The parser output was found in the cache.
 *   - `cache_miss`: The parser output was not found in the cache.
 *
 * @ingroup JobQueue
 * @see RefreshSecondaryDataUpdate
 * @see WikiPage::doSecondaryDataUpdates()
 */
class RefreshLinksJob extends Job {
	/** @var int Lag safety margin when comparing root job times to last-refresh times */
	private const NORMAL_MAX_LAG = 10;
	/** @var int How many seconds to wait for replica DBs to catch up */
	private const LAG_WAIT_TIMEOUT = 15;

	public function __construct( PageIdentity $page, array $params ) {
		if ( empty( $params['pages'] ) && !$page->canExist() ) {
			// BC with the Title class
			throw new PageAssertionException(
				'The given PageIdentity {pageIdentity} does not represent a proper page',
				[ 'pageIdentity' => $page ]
			);
		}

		parent::__construct( 'refreshLinks', $page, $params );
		// Avoid the overhead of de-duplication when it would be pointless
		$this->removeDuplicates = (
			// Ranges rarely will line up
			!isset( $params['range'] ) &&
			// Multiple pages per job make matches unlikely
			!( isset( $params['pages'] ) && count( $params['pages'] ) != 1 )
		);
		$this->params += [ 'causeAction' => 'RefreshLinksJob', 'causeAgent' => 'unknown' ];
		// Tell JobRunner to not automatically wrap run() in a transaction round.
		// Each runForTitle() call will manage its own rounds in order to run DataUpdates
		// and to avoid contention as well.
		$this->executionFlags |= self::JOB_NO_EXPLICIT_TRX_ROUND;
	}

	/**
	 * @param PageIdentity $page
	 * @param array $params
	 * @return RefreshLinksJob
	 */
	public static function newPrioritized( PageIdentity $page, array $params ) {
		$job = new self( $page, $params );
		$job->command = 'refreshLinksPrioritized';

		return $job;
	}

	/**
	 * @param PageIdentity $page
	 * @param array $params
	 * @return RefreshLinksJob
	 */
	public static function newDynamic( PageIdentity $page, array $params ) {
		$job = new self( $page, $params );
		$job->command = 'refreshLinksDynamic';

		return $job;
	}

	public function run() {
		$ok = true;

		if ( !empty( $this->params['recursive'] ) ) {
			// Job to update all (or a range of) backlink pages for a page

			// When the base job branches, wait for the replica DBs to catch up to the primary.
			// From then on, we know that any template changes at the time the base job was
			// enqueued will be reflected in backlink page parses when the leaf jobs run.
			$services = MediaWikiServices::getInstance();
			if ( !isset( $this->params['range'] ) ) {
				$lbFactory = $services->getDBLoadBalancerFactory();
				if ( !$lbFactory->waitForReplication( [
					'timeout' => self::LAG_WAIT_TIMEOUT
				] ) ) {
					// only try so hard, keep going with what we have
					$stats = $services->getStatsFactory();
					$stats->getCounter( 'refreshlinks_warnings_total' )
						->setLabel( 'reason', 'lag_wait_failed' )
						->copyToStatsdAt( 'refreshlinks_warning.lag_wait_failed' )
						->increment();
				}
			}
			// Carry over information for de-duplication
			$extraParams = $this->getRootJobParams();
			$extraParams['triggeredRecursive'] = true;
			// Carry over cause information for logging
			$extraParams['causeAction'] = $this->params['causeAction'];
			$extraParams['causeAgent'] = $this->params['causeAgent'];
			// Convert this into no more than $wgUpdateRowsPerJob RefreshLinks per-title
			// jobs and possibly a recursive RefreshLinks job for the rest of the backlinks
			$jobs = BacklinkJobUtils::partitionBacklinkJob(
				$this,
				$services->getMainConfig()->get( MainConfigNames::UpdateRowsPerJob ),
				1, // job-per-title
				[ 'params' => $extraParams ]
			);
			$services->getJobQueueGroup()->push( $jobs );

		} elseif ( isset( $this->params['pages'] ) ) {
			// Job to update link tables for a set of titles
			foreach ( $this->params['pages'] as [ $ns, $dbKey ] ) {
				$title = Title::makeTitleSafe( $ns, $dbKey );
				if ( $title && $title->canExist() ) {
					$ok = $this->runForTitle( $title ) && $ok;
				} else {
					$ok = false;
					$this->setLastError( "Invalid title ($ns,$dbKey)." );
				}
			}

		} else {
			// Job to update link tables for a given title
			$ok = $this->runForTitle( $this->title );
		}

		return $ok;
	}

	/**
	 * @param PageIdentity $pageIdentity
	 * @return bool
	 */
	protected function runForTitle( PageIdentity $pageIdentity ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsFactory();
		$renderer = $services->getRevisionRenderer();
		$parserCache = $services->getParserCache();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		// Load the page from the primary DB
		$page = $services->getWikiPageFactory()->newFromTitle( $pageIdentity );
		$page->loadPageData( IDBAccessObject::READ_LATEST );

		if ( !$page->exists() ) {
			// Probably due to concurrent deletion or renaming of the page
			$logger = LoggerFactory::getInstance( 'RefreshLinksJob' );
			$logger->warning(
				'The page does not exist. Perhaps it was deleted?',
				[
					'page_title' => $this->title->getPrefixedDBkey(),
					'job_params' => $this->getParams(),
					'job_metadata' => $this->getMetadata()
				]
			);
			$this->incrementFailureCounter( $stats, 'page_not_found' );

			// retry later to handle unlucky race condition
			return false;
		}

		// Serialize link update job by page ID so they see each others' changes.
		// The page ID and latest revision ID will be queried again after the lock
		// is acquired to bail if they are changed from that of loadPageData() above.
		// Serialize links updates by page ID so they see each others' changes
		$dbw = $lbFactory->getPrimaryDatabase();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopedLock = LinksUpdate::acquirePageLock( $dbw, $page->getId(), 'job' );
		if ( $scopedLock === null ) {
			// Another job is already updating the page, likely for a prior revision (T170596)
			$this->setLastError( 'LinksUpdate already running for this page, try again later.' );
			$this->incrementFailureCounter( $stats, 'lock_failure' );

			// retry later when overlapping job for previous rev is done
			return false;
		}

		if ( $this->isAlreadyRefreshed( $page ) ) {
			// this job has been superseded, e.g. by overlapping recursive job
			// for a different template edit, or by direct edit or purge.
			$stats->getCounter( 'refreshlinks_superseded_updates_total' )
				->copyToStatsdAt( 'refreshlinks_outcome.good_update_superseded' )
				->increment();
			// treat as success
			return true;
		}

		// Parse during a fresh transaction round for better read consistency
		$lbFactory->beginPrimaryChanges( __METHOD__ );
		$output = $this->getParserOutput( $renderer, $parserCache, $page, $stats );
		$options = $this->getDataUpdateOptions();
		$lbFactory->commitPrimaryChanges( __METHOD__ );

		if ( !$output ) {
			// probably raced out.
			// Specific refreshlinks_outcome metric sent by getCurrentRevisionIfUnchanged().
			// Don't retry job.
			return true;
		}

		// Tell DerivedPageDataUpdater to use this parser output
		$options['known-revision-output'] = $output;
		// Execute corresponding DataUpdates immediately
		$page->doSecondaryDataUpdates( $options );
		InfoAction::invalidateCache( $page );

		// NOTE: Since 2019 (f588586e) this no longer saves the new ParserOutput to the ParserCache!
		//       This means the page will have to be rendered on-the-fly when it is next viewed.
		//       This is to avoid spending limited ParserCache capacity on rarely visited pages.
		// TODO: Save the ParserOutput to ParserCache by calling WikiPage::updateParserCache()
		//       for pages that are likely to benefit (T327162).

		// Commit any writes here in case this method is called in a loop.
		// In that case, the scoped lock will fail to be acquired.
		$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

		return true;
	}

	/**
	 * @return string|null Minimum lag-safe TS_MW timestamp with regard to root job creation
	 */
	private function getLagAwareRootTimestamp() {
		// Get the timestamp of the change that triggered this job
		$rootTimestamp = $this->params['rootJobTimestamp'] ?? null;
		if ( $rootTimestamp === null ) {
			return null;
		}

		if ( !empty( $this->params['isOpportunistic'] ) ) {
			// Neither clock skew nor DB snapshot/replica DB lag matter much for
			// such updates; focus on reusing the (often recently updated) cache
			$lagAwareTimestamp = $rootTimestamp;
		} else {
			// For transclusion updates, the template changes must be reflected
			$lagAwareTimestamp = wfTimestamp(
				TS_MW,
				(int)wfTimestamp( TS_UNIX, $rootTimestamp ) + self::NORMAL_MAX_LAG
			);
		}

		return $lagAwareTimestamp;
	}

	/**
	 * @param WikiPage $page
	 * @return bool Whether something updated the backlinks with data newer than this job
	 */
	private function isAlreadyRefreshed( WikiPage $page ) {
		$lagAwareTimestamp = $this->getLagAwareRootTimestamp();

		return ( $lagAwareTimestamp !== null && $page->getLinksTimestamp() > $lagAwareTimestamp );
	}

	/**
	 * @see DerivedPageDataUpdater::shouldGenerateHTMLOnEdit
	 * @return bool true if at least one of slots require rendering HTML on edit, false otherwise.
	 *              This is needed for example in populating ParserCache.
	 */
	private function shouldGenerateHTMLOnEdit( RevisionRecord $revision ): bool {
		$services = MediaWikiServices::getInstance();
		foreach ( $revision->getSlots()->getSlotRoles() as $role ) {
			$slot = $revision->getSlots()->getSlot( $role );
			$contentHandler = $services->getContentHandlerFactory()->getContentHandler( $slot->getModel() );
			if ( $contentHandler->generateHTMLOnEdit() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the parser output if the page is unchanged from what was loaded in $page
	 *
	 * @param RevisionRenderer $renderer
	 * @param ParserCache $parserCache
	 * @param WikiPage $page Page already loaded with READ_LATEST
	 * @param StatsFactory $stats
	 * @return ParserOutput|null Combined output for all slots; might only contain metadata
	 */
	private function getParserOutput(
		RevisionRenderer $renderer,
		ParserCache $parserCache,
		WikiPage $page,
		StatsFactory $stats
	) {
		$revision = $this->getCurrentRevisionIfUnchanged( $page, $stats );
		if ( !$revision ) {
			// race condition?
			return null;
		}

		$cachedOutput = $this->getParserOutputFromCache( $parserCache, $page, $revision, $stats );
		$statsCounter = $stats->getCounter( 'refreshlinks_parsercache_operations_total' );

		if ( $cachedOutput && $this->canUseParserOutputFromCache( $cachedOutput, $revision ) ) {
			$statsCounter
				->setLabel( 'status', 'cache_hit' )
				->setLabel( 'html_changed', 'n/a' )
				->copyToStatsdAt( 'refreshlinks.parser_cached' )
				->increment();

			return $cachedOutput;
		}

		$causeAction = $this->params['causeAction'] ?? 'RefreshLinksJob';
		$parserOptions = $page->makeParserOptions( 'canonical' );

		// T371713: Temporary statistics collection code to determine
		// feasibility of Parsoid selective update
		$sampleRate = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParsoidSelectiveUpdateSampleRate
		);
		$doSample = $sampleRate && mt_rand( 1, $sampleRate ) === 1;
		if ( $doSample && $cachedOutput === null ) {
			// In order to collect accurate statistics, check for
			// a dirty copy in the cache even if we wouldn't have
			// to otherwise.
			$cachedOutput = $parserCache->getDirty( $page, $parserOptions ) ?: null;
		}

		$renderedRevision = $renderer->getRenderedRevision(
			$revision,
			$parserOptions,
			null,
			[
				'audience' => $revision::RAW,
				'causeAction' => $causeAction,
				// Providing a previous parse potentially allows for
				// selective updates
				'previous-output' => $cachedOutput,
			]
		);

		$parseTimestamp = wfTimestampNow(); // timestamp that parsing started
		$output = $renderedRevision->getRevisionParserOutput( [
			// To avoid duplicate parses, this must match DerivedPageDataUpdater::shouldGenerateHTMLOnEdit() (T301309)
			'generate-html' => $this->shouldGenerateHTMLOnEdit( $revision )
		] );
		$output->setCacheTime( $parseTimestamp ); // notify LinksUpdate::doUpdate()
		// T371713: Temporary statistics collection code to determine
		// feasibility of Parsoid selective update
		if ( $doSample ) {
			$content = $revision->getContent( SlotRecord::MAIN );
			$labels = [
				'source' => 'RefreshLinksJob',
				'type' => $cachedOutput === null ? 'full' : 'selective',
				'reason' => $causeAction,
				'parser' => $parserOptions->getUseParsoid() ? 'parsoid' : 'legacy',
				'opportunistic' => empty( $this->params['isOpportunistic'] ) ? 'false' : 'true',
				'wiki' => WikiMap::getCurrentWikiId(),
				'model' => $content ? $content->getModel() : 'unknown',
			];
			$stats
				->getCounter( 'ParserCache_selective_total' )
				->setLabels( $labels )
				->increment();
			$stats
				->getCounter( 'ParserCache_selective_cpu_seconds' )
				->setLabels( $labels )
				->incrementBy( $output->getTimeProfile( 'cpu' ) );
		}

		// Collect stats on parses that don't actually change the page content.
		// In that case, we could abort here, and perhaps we could also avoid
		// triggering CDN purges (T369898).
		if ( !$cachedOutput ) {
			// There was no cached output
			$htmlChanged = 'unknown';
		} elseif ( $cachedOutput->getRawText() === $output->getRawText() ) {
			// We have cached output, but we couldn't be sure that it was still good.
			// So we parsed again, but the result turned out to be the same HTML as
			// before.
			$htmlChanged = 'no';
		} else {
			// Re-parsing yielded HTML different from the cached output.
			$htmlChanged = 'yes';
		}

		$statsCounter
			->setLabel( 'status', 'cache_miss' )
			->setLabel( 'html_changed', $htmlChanged )
			->setLabel( 'has_async_content',
				$output->getOutputFlag( ParserOutputFlags::HAS_ASYNC_CONTENT ) ? 'true' : 'false' )
			->setLabel( 'async_not_ready',
				$output->getOutputFlag( ParserOutputFlags::ASYNC_NOT_READY ) ? 'true' : 'false' )
			->copyToStatsdAt( 'refreshlinks.parser_uncached' )
			->increment();

		return $output;
	}

	/**
	 * Get the current revision record if it is unchanged from what was loaded in $page
	 *
	 * @param WikiPage $page Page already loaded with READ_LATEST
	 * @param StatsFactory $stats
	 * @return RevisionRecord|null The same instance that $page->getRevisionRecord() uses
	 */
	private function getCurrentRevisionIfUnchanged(
		WikiPage $page,
		StatsFactory $stats
	) {
		$title = $page->getTitle();
		// Get the latest ID since acquirePageLock() in runForTitle() flushed the transaction.
		// This is used to detect edits/moves after loadPageData() but before the scope lock.
		// The works around the chicken/egg problem of determining the scope lock key name
		$latest = $title->getLatestRevID( IDBAccessObject::READ_LATEST );

		$triggeringRevisionId = $this->params['triggeringRevisionId'] ?? null;
		if ( $triggeringRevisionId && $triggeringRevisionId !== $latest ) {
			// This job is obsolete and one for the latest revision will handle updates
			$this->incrementFailureCounter( $stats, 'rev_not_current' );
			$this->setLastError( "Revision $triggeringRevisionId is not current" );
			return null;
		}

		// Load the current revision. Note that $page should have loaded with READ_LATEST.
		// This instance will be reused in WikiPage::doSecondaryDataUpdates() later on.
		$revision = $page->getRevisionRecord();
		if ( !$revision ) {
			// revision just got deleted?
			$this->incrementFailureCounter( $stats, 'rev_not_found' );
			$this->setLastError( "Revision not found for {$title->getPrefixedDBkey()}" );
			return null;

		} elseif ( $revision->getId() !== $latest || $revision->getPageId() !== $page->getId() ) {
			// Do not clobber over newer updates with older ones. If all jobs where FIFO and
			// serialized, it would be OK to update links based on older revisions since it
			// would eventually get to the latest. Since that is not the case (by design),
			// only update the link tables to a state matching the current revision's output.
			$this->incrementFailureCounter( $stats, 'rev_not_current' );
			$this->setLastError( "Revision {$revision->getId()} is not current" );

			return null;
		}

		return $revision;
	}

	/**
	 * Get the parser output from cache if it reflects the change that triggered this job
	 *
	 * @param ParserCache $parserCache
	 * @param WikiPage $page
	 * @param RevisionRecord $currentRevision
	 * @param StatsFactory $stats
	 * @return ParserOutput|null
	 */
	private function getParserOutputFromCache(
		ParserCache $parserCache,
		WikiPage $page,
		RevisionRecord $currentRevision,
		StatsFactory $stats
	): ?ParserOutput {
		// Parsoid can do selective updates, so it is always worth the I/O
		// to check for a previous parse.
		$parserOptions = $page->makeParserOptions( 'canonical' );
		if ( $parserOptions->getUseParsoid() ) {
			return $parserCache->getDirty( $page, $parserOptions ) ?: null;
		}
		// If page_touched changed after this root job, then it is likely that
		// any views of the pages already resulted in re-parses which are now in
		// cache. The cache can be reused to avoid expensive parsing in some cases.
		$rootTimestamp = $this->params['rootJobTimestamp'] ?? null;
		if ( $rootTimestamp !== null ) {
			$opportunistic = !empty( $this->params['isOpportunistic'] );
			if ( $page->getTouched() >= $rootTimestamp || $opportunistic ) {
				// Cache is suspected to be up-to-date so it's worth the I/O of checking.
				// We call canUseParserOutputFromCache() later to check if it's usable.
				return $parserCache->getDirty( $page, $parserOptions ) ?: null;
			}
		}

		return null;
	}

	private function canUseParserOutputFromCache(
		ParserOutput $cachedOutput,
		RevisionRecord $currentRevision
	): bool {
		// As long as the cache rev ID matches the current rev ID and it reflects
		// the job's triggering change, then it is usable.
		return $cachedOutput->getCacheRevisionId() == $currentRevision->getId()
			&& $cachedOutput->getCacheTime() >= $this->getLagAwareRootTimestamp();
	}

	/**
	 * Increment the RefreshLinks failure counter metric with the given reason.
	 *
	 * @param StatsFactory $stats
	 * @param string $reason Well-known failure reason string
	 * @return void
	 */
	private function incrementFailureCounter( StatsFactory $stats, $reason ): void {
		$stats->getCounter( 'refreshlinks_failures_total' )
			->setLabel( 'reason', $reason )
			->copyToStatsdAt( "refreshlinks_outcome.bad_$reason" )
			->increment();
	}

	/**
	 * @return array
	 */
	private function getDataUpdateOptions() {
		$options = [
			'recursive' => !empty( $this->params['useRecursiveLinksUpdate'] ),
			// Carry over cause so the update can do extra logging
			'causeAction' => $this->params['causeAction'],
			'causeAgent' => $this->params['causeAgent']
		];
		if ( !empty( $this->params['triggeringUser'] ) ) {
			$userInfo = $this->params['triggeringUser'];
			if ( $userInfo['userId'] ) {
				$options['triggeringUser'] = User::newFromId( $userInfo['userId'] );
			} else {
				// Anonymous, use the username
				$options['triggeringUser'] = User::newFromName( $userInfo['userName'], false );
			}
		}

		return $options;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		unset( $info['causeAction'] );
		unset( $info['causeAgent'] );
		if ( is_array( $info['params'] ) ) {
			// For per-pages jobs, the job title is that of the template that changed
			// (or similar), so remove that since it ruins duplicate detection
			if ( isset( $info['params']['pages'] ) ) {
				unset( $info['namespace'] );
				unset( $info['title'] );
			}
		}

		return $info;
	}

	public function workItemCount() {
		if ( !empty( $this->params['recursive'] ) ) {
			return 0; // nothing actually refreshed
		} elseif ( isset( $this->params['pages'] ) ) {
			return count( $this->params['pages'] );
		}

		return 1; // one title
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RefreshLinksJob::class, 'RefreshLinksJob' );
