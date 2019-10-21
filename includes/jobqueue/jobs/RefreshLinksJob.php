<?php
/**
 * Job to update link tables for pages
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
 * @ingroup JobQueue
 */
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * Job to update link tables for pages
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to update links for backlink pages for a given title.
 *        These jobs have (recursive:true,table:<table>) set.
 *   - b) Jobs to update links for a set of pages (the job title is ignored).
 *        These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
 *   - c) Jobs to update links for a single page (the job title)
 *        These jobs need no extra fields set.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob extends Job {
	/** @var int Lag safety margin when comparing root job times to last-refresh times */
	const NORMAL_MAX_LAG = 10;
	/** @var int How many seconds to wait for replica DBs to catch up */
	const LAG_WAIT_TIMEOUT = 15;

	function __construct( Title $title, array $params ) {
		parent::__construct( 'refreshLinks', $title, $params );
		// Avoid the overhead of de-duplication when it would be pointless
		$this->removeDuplicates = (
			// Ranges rarely will line up
			!isset( $params['range'] ) &&
			// Multiple pages per job make matches unlikely
			!( isset( $params['pages'] ) && count( $params['pages'] ) != 1 )
		);
		$this->params += [ 'causeAction' => 'unknown', 'causeAgent' => 'unknown' ];
		// Tell JobRunner to not automatically wrap run() in a transaction round.
		// Each runForTitle() call will manage its own rounds in order to run DataUpdates
		// and to avoid contention as well.
		$this->executionFlags |= self::JOB_NO_EXPLICIT_TRX_ROUND;
	}

	/**
	 * @param Title $title
	 * @param array $params
	 * @return RefreshLinksJob
	 */
	public static function newPrioritized( Title $title, array $params ) {
		$job = new self( $title, $params );
		$job->command = 'refreshLinksPrioritized';

		return $job;
	}

	/**
	 * @param Title $title
	 * @param array $params
	 * @return RefreshLinksJob
	 */
	public static function newDynamic( Title $title, array $params ) {
		$job = new self( $title, $params );
		$job->command = 'refreshLinksDynamic';

		return $job;
	}

	function run() {
		$ok = true;

		// Job to update all (or a range of) backlink pages for a page
		if ( !empty( $this->params['recursive'] ) ) {
			$services = MediaWikiServices::getInstance();
			// When the base job branches, wait for the replica DBs to catch up to the master.
			// From then on, we know that any template changes at the time the base job was
			// enqueued will be reflected in backlink page parses when the leaf jobs run.
			if ( !isset( $this->params['range'] ) ) {
				$lbFactory = $services->getDBLoadBalancerFactory();
				if ( !$lbFactory->waitForReplication( [
					'domain'  => $lbFactory->getLocalDomainID(),
					'timeout' => self::LAG_WAIT_TIMEOUT
				] ) ) { // only try so hard
					$stats = $services->getStatsdDataFactory();
					$stats->increment( 'refreshlinks.lag_wait_failed' );
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
				$services->getMainConfig()->get( 'UpdateRowsPerJob' ),
				1, // job-per-title
				[ 'params' => $extraParams ]
			);
			JobQueueGroup::singleton()->push( $jobs );
		// Job to update link tables for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			foreach ( $this->params['pages'] as list( $ns, $dbKey ) ) {
				$title = Title::makeTitleSafe( $ns, $dbKey );
				if ( $title ) {
					$ok = $this->runForTitle( $title ) && $ok;
				} else {
					$ok = false;
					$this->setLastError( "Invalid title ($ns,$dbKey)." );
				}
			}
		// Job to update link tables for a given title
		} else {
			$ok = $this->runForTitle( $this->title );
		}

		return $ok;
	}

	/**
	 * @param Title $title
	 * @return bool
	 */
	protected function runForTitle( Title $title ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$renderer = $services->getRevisionRenderer();
		$parserCache = $services->getParserCache();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		// Load the page from the master DB
		$page = WikiPage::factory( $title );
		$page->loadPageData( WikiPage::READ_LATEST );

		// Serialize link update job by page ID so they see each others' changes.
		// The page ID and latest revision ID will be queried again after the lock
		// is acquired to bail if they are changed from that of loadPageData() above.
		// Serialize links updates by page ID so they see each others' changes
		$dbw = $lbFactory->getMainLB()->getConnectionRef( DB_MASTER );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopedLock = LinksUpdate::acquirePageLock( $dbw, $page->getId(), 'job' );
		if ( $scopedLock === null ) {
			// Another job is already updating the page, likely for a prior revision (T170596)
			$this->setLastError( 'LinksUpdate already running for this page, try again later.' );
			$stats->increment( 'refreshlinks.lock_failure' );

			return false;
		}

		if ( $this->isAlreadyRefreshed( $page ) ) {
			$stats->increment( 'refreshlinks.update_skipped' );

			return true;
		}

		// Parse during a fresh transaction round for better read consistency
		$lbFactory->beginMasterChanges( __METHOD__ );
		$output = $this->getParserOutput( $renderer, $parserCache, $page, $stats );
		$options = $this->getDataUpdateOptions();
		$lbFactory->commitMasterChanges( __METHOD__ );

		if ( !$output ) {
			return false; // raced out?
		}

		// Tell DerivedPageDataUpdater to use this parser output
		$options['known-revision-output'] = $output;
		// Execute corresponding DataUpdates immediately
		$page->doSecondaryDataUpdates( $options );
		InfoAction::invalidateCache( $title );

		// Commit any writes here in case this method is called in a loop.
		// In that case, the scoped lock will fail to be acquired.
		$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

		return true;
	}

	/**
	 * @param WikiPage $page
	 * @return bool Whether something updated the backlinks with data newer than this job
	 */
	private function isAlreadyRefreshed( WikiPage $page ) {
		// Get the timestamp of the change that triggered this job
		$rootTimestamp = $this->params['rootJobTimestamp'] ?? null;
		if ( $rootTimestamp === null ) {
			return false;
		}

		if ( !empty( $this->params['isOpportunistic'] ) ) {
			// Neither clock skew nor DB snapshot/replica DB lag matter much for
			// such updates; focus on reusing the (often recently updated) cache
			$lagAwareTimestamp = $rootTimestamp;
		} else {
			// For transclusion updates, the template changes must be reflected
			$lagAwareTimestamp = wfTimestamp(
				TS_MW,
				wfTimestamp( TS_UNIX, $rootTimestamp ) + self::NORMAL_MAX_LAG
			);
		}

		return ( $page->getLinksTimestamp() > $lagAwareTimestamp );
	}

	/**
	 * Get the parser output if the page is unchanged from what was loaded in $page
	 *
	 * @param RevisionRenderer $renderer
	 * @param ParserCache $parserCache
	 * @param WikiPage $page Page already loaded with READ_LATEST
	 * @param StatsdDataFactoryInterface $stats
	 * @return ParserOutput|null Combined output for all slots; might only contain metadata
	 */
	private function getParserOutput(
		RevisionRenderer $renderer,
		ParserCache $parserCache,
		WikiPage $page,
		StatsdDataFactoryInterface $stats
	) {
		$revision = $this->getCurrentRevisionIfUnchanged( $page, $stats );
		if ( !$revision ) {
			return null; // race condition?
		}

		$cachedOutput = $this->getParserOutputFromCache( $parserCache, $page, $revision, $stats );
		if ( $cachedOutput ) {
			return $cachedOutput;
		}

		$renderedRevision = $renderer->getRenderedRevision(
			$revision,
			$page->makeParserOptions( 'canonical' ),
			null,
			[ 'audience' => $revision::RAW ]
		);

		$parseTimestamp = wfTimestampNow(); // timestamp that parsing started
		$output = $renderedRevision->getRevisionParserOutput( [ 'generate-html' => false ] );
		$output->setCacheTime( $parseTimestamp ); // notify LinksUpdate::doUpdate()

		return $output;
	}

	/**
	 * Get the current revision record if it is unchanged from what was loaded in $page
	 *
	 * @param WikiPage $page Page already loaded with READ_LATEST
	 * @param StatsdDataFactoryInterface $stats
	 * @return RevisionRecord|null The same instance that $page->getRevisionRecord() uses
	 */
	private function getCurrentRevisionIfUnchanged(
		WikiPage $page,
		StatsdDataFactoryInterface $stats
	) {
		$title = $page->getTitle();
		// Get the latest ID since acquirePageLock() in runForTitle() flushed the transaction.
		// This is used to detect edits/moves after loadPageData() but before the scope lock.
		// The works around the chicken/egg problem of determining the scope lock key name
		$latest = $title->getLatestRevID( Title::READ_LATEST );

		$triggeringRevisionId = $this->params['triggeringRevisionId'] ?? null;
		if ( $triggeringRevisionId && $triggeringRevisionId !== $latest ) {
			// This job is obsolete and one for the latest revision will handle updates
			$stats->increment( 'refreshlinks.rev_not_current' );
			$this->setLastError( "Revision $triggeringRevisionId is not current" );

			return null;
		}

		// Load the current revision. Note that $page should have loaded with READ_LATEST.
		// This instance will be reused in WikiPage::doSecondaryDataUpdates() later on.
		$revision = $page->getRevisionRecord();
		if ( !$revision ) {
			$stats->increment( 'refreshlinks.rev_not_found' );
			$this->setLastError( "Revision not found for {$title->getPrefixedDBkey()}" );

			return null; // just deleted?
		} elseif ( $revision->getId() !== $latest || $revision->getPageId() !== $page->getId() ) {
			// Do not clobber over newer updates with older ones. If all jobs where FIFO and
			// serialized, it would be OK to update links based on older revisions since it
			// would eventually get to the latest. Since that is not the case (by design),
			// only update the link tables to a state matching the current revision's output.
			$stats->increment( 'refreshlinks.rev_not_current' );
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
	 * @param StatsdDataFactoryInterface $stats
	 * @return ParserOutput|null
	 */
	private function getParserOutputFromCache(
		ParserCache $parserCache,
		WikiPage $page,
		RevisionRecord $currentRevision,
		StatsdDataFactoryInterface $stats
	) {
		$cachedOutput = null;
		// If page_touched changed after this root job, then it is likely that
		// any views of the pages already resulted in re-parses which are now in
		// cache. The cache can be reused to avoid expensive parsing in some cases.
		$rootTimestamp = $this->params['rootJobTimestamp'] ?? null;
		if ( $rootTimestamp !== null ) {
			$opportunistic = !empty( $this->params['isOpportunistic'] );
			if ( $opportunistic ) {
				// Neither clock skew nor DB snapshot/replica DB lag matter much for
				// such updates; focus on reusing the (often recently updated) cache
				$lagAwareTimestamp = $rootTimestamp;
			} else {
				// For transclusion updates, the template changes must be reflected
				$lagAwareTimestamp = wfTimestamp(
					TS_MW,
					wfTimestamp( TS_UNIX, $rootTimestamp ) + self::NORMAL_MAX_LAG
				);
			}

			if ( $page->getTouched() >= $rootTimestamp || $opportunistic ) {
				// Cache is suspected to be up-to-date so it's worth the I/O of checking.
				// As long as the cache rev ID matches the current rev ID and it reflects
				// the job's triggering change, then it is usable.
				$parserOptions = $page->makeParserOptions( 'canonical' );
				$output = $parserCache->getDirty( $page, $parserOptions );
				if (
					$output &&
					$output->getCacheRevisionId() == $currentRevision->getId() &&
					$output->getCacheTime() >= $lagAwareTimestamp
				) {
					$cachedOutput = $output;
				}
			}
		}

		if ( $cachedOutput ) {
			$stats->increment( 'refreshlinks.parser_cached' );
		} else {
			$stats->increment( 'refreshlinks.parser_uncached' );
		}

		return $cachedOutput;
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
