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
use Wikimedia\Rdbms\DBReplicationWaitError;

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
	/** @var float Cache parser output when it takes this long to render */
	const PARSE_THRESHOLD_SEC = 1.0;
	/** @var integer Lag safety margin when comparing root job times to last-refresh times */
	const CLOCK_FUDGE = 10;
	/** @var integer How many seconds to wait for replica DBs to catch up */
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
		global $wgUpdateRowsPerJob;

		// Job to update all (or a range of) backlink pages for a page
		if ( !empty( $this->params['recursive'] ) ) {
			// When the base job branches, wait for the replica DBs to catch up to the master.
			// From then on, we know that any template changes at the time the base job was
			// enqueued will be reflected in backlink page parses when the leaf jobs run.
			if ( !isset( $this->params['range'] ) ) {
				try {
					$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
					$lbFactory->waitForReplication( [
						'wiki'    => wfWikiID(),
						'timeout' => self::LAG_WAIT_TIMEOUT
					] );
				} catch ( DBReplicationWaitError $e ) { // only try so hard
					$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
					$stats->increment( 'refreshlinks.lag_wait_failed' );
				}
			}
			// Carry over information for de-duplication
			$extraParams = $this->getRootJobParams();
			$extraParams['triggeredRecursive'] = true;
			// Convert this into no more than $wgUpdateRowsPerJob RefreshLinks per-title
			// jobs and possibly a recursive RefreshLinks job for the rest of the backlinks
			$jobs = BacklinkJobUtils::partitionBacklinkJob(
				$this,
				$wgUpdateRowsPerJob,
				1, // job-per-title
				[ 'params' => $extraParams ]
			);
			JobQueueGroup::singleton()->push( $jobs );
		// Job to update link tables for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			foreach ( $this->params['pages'] as $nsAndKey ) {
				list( $ns, $dbKey ) = $nsAndKey;
				$this->runForTitle( Title::makeTitleSafe( $ns, $dbKey ) );
			}
		// Job to update link tables for a given title
		} else {
			$this->runForTitle( $this->title );
		}

		return true;
	}

	/**
	 * @param Title $title
	 * @return bool
	 */
	protected function runForTitle( Title $title ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		$page = WikiPage::factory( $title );
		$page->loadPageData( WikiPage::READ_LATEST );

		// Serialize links updates by page ID so they see each others' changes
		$dbw = $lbFactory->getMainLB()->getConnection( DB_MASTER );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopedLock = LinksUpdate::acquirePageLock( $dbw, $page->getId(), 'job' );
		// Get the latest ID *after* acquirePageLock() flushed the transaction.
		// This is used to detect edits/moves after loadPageData() but before the scope lock.
		// The works around the chicken/egg problem of determining the scope lock key.
		$latest = $title->getLatestRevID( Title::GAID_FOR_UPDATE );

		if ( !empty( $this->params['triggeringRevisionId'] ) ) {
			// Fetch the specified revision; lockAndGetLatest() below detects if the page
			// was edited since and aborts in order to avoid corrupting the link tables
			$revision = Revision::newFromId(
				$this->params['triggeringRevisionId'],
				Revision::READ_LATEST
			);
		} else {
			// Fetch current revision; READ_LATEST reduces lockAndGetLatest() check failures
			$revision = Revision::newFromTitle( $title, false, Revision::READ_LATEST );
		}

		if ( !$revision ) {
			$stats->increment( 'refreshlinks.rev_not_found' );
			$this->setLastError( "Revision not found for {$title->getPrefixedDBkey()}" );
			return false; // just deleted?
		} elseif ( $revision->getId() != $latest || $revision->getPage() !== $page->getId() ) {
			// Do not clobber over newer updates with older ones. If all jobs where FIFO and
			// serialized, it would be OK to update links based on older revisions since it
			// would eventually get to the latest. Since that is not the case (by design),
			// only update the link tables to a state matching the current revision's output.
			$stats->increment( 'refreshlinks.rev_not_current' );
			$this->setLastError( "Revision {$revision->getId()} is not current" );
			return false;
		}

		$content = $revision->getContent( Revision::RAW );
		if ( !$content ) {
			// If there is no content, pretend the content is empty
			$content = $revision->getContentHandler()->makeEmptyContent();
		}

		$parserOutput = false;
		$parserOptions = $page->makeParserOptions( 'canonical' );
		// If page_touched changed after this root job, then it is likely that
		// any views of the pages already resulted in re-parses which are now in
		// cache. The cache can be reused to avoid expensive parsing in some cases.
		if ( isset( $this->params['rootJobTimestamp'] ) ) {
			$opportunistic = !empty( $this->params['isOpportunistic'] );

			$skewedTimestamp = $this->params['rootJobTimestamp'];
			if ( $opportunistic ) {
				// Neither clock skew nor DB snapshot/replica DB lag matter much for such
				// updates; focus on reusing the (often recently updated) cache
			} else {
				// For transclusion updates, the template changes must be reflected
				$skewedTimestamp = wfTimestamp( TS_MW,
					wfTimestamp( TS_UNIX, $skewedTimestamp ) + self::CLOCK_FUDGE
				);
			}

			if ( $page->getLinksTimestamp() > $skewedTimestamp ) {
				// Something already updated the backlinks since this job was made
				$stats->increment( 'refreshlinks.update_skipped' );
				return true;
			}

			if ( $page->getTouched() >= $this->params['rootJobTimestamp'] || $opportunistic ) {
				// Cache is suspected to be up-to-date. As long as the cache rev ID matches
				// and it reflects the job's triggering change, then it is usable.
				$parserOutput = ParserCache::singleton()->getDirty( $page, $parserOptions );
				if ( !$parserOutput
					|| $parserOutput->getCacheRevisionId() != $revision->getId()
					|| $parserOutput->getCacheTime() < $skewedTimestamp
				) {
					$parserOutput = false; // too stale
				}
			}
		}

		// Fetch the current revision and parse it if necessary...
		if ( $parserOutput ) {
			$stats->increment( 'refreshlinks.parser_cached' );
		} else {
			$start = microtime( true );
			// Revision ID must be passed to the parser output to get revision variables correct
			$parserOutput = $content->getParserOutput(
				$title, $revision->getId(), $parserOptions, false );
			$elapsed = microtime( true ) - $start;
			// If it took a long time to render, then save this back to the cache to avoid
			// wasted CPU by other apaches or job runners. We don't want to always save to
			// cache as this can cause high cache I/O and LRU churn when a template changes.
			if ( $elapsed >= self::PARSE_THRESHOLD_SEC
				&& $page->shouldCheckParserCache( $parserOptions, $revision->getId() )
				&& $parserOutput->isCacheable()
			) {
				$ctime = wfTimestamp( TS_MW, (int)$start ); // cache time
				ParserCache::singleton()->save(
					$parserOutput, $page, $parserOptions, $ctime, $revision->getId()
				);
			}
			$stats->increment( 'refreshlinks.parser_uncached' );
		}

		$updates = $content->getSecondaryDataUpdates(
			$title,
			null,
			!empty( $this->params['useRecursiveLinksUpdate'] ),
			$parserOutput
		);

		// For legacy hook handlers doing updates via LinksUpdateConstructed, make sure
		// any pending writes they made get flushed before the doUpdate() calls below.
		// This avoids snapshot-clearing errors in LinksUpdate::acquirePageLock().
		$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

		foreach ( $updates as $update ) {
			// FIXME: This code probably shouldn't be here?
			// Needed by things like Echo notifications which need
			// to know which user caused the links update
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $revision );
				if ( !empty( $this->params['triggeringUser'] ) ) {
					$userInfo = $this->params['triggeringUser'];
					if ( $userInfo['userId'] ) {
						$user = User::newFromId( $userInfo['userId'] );
					} else {
						// Anonymous, use the username
						$user = User::newFromName( $userInfo['userName'], false );
					}
					$update->setTriggeringUser( $user );
				}
			}
		}

		foreach ( $updates as $update ) {
			$update->setTransactionTicket( $ticket );
			$update->doUpdate();
		}

		InfoAction::invalidateCache( $title );

		return true;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		if ( is_array( $info['params'] ) ) {
			// For per-pages jobs, the job title is that of the template that changed
			// (or similar), so remove that since it ruins duplicate detection
			if ( isset( $info['pages'] ) ) {
				unset( $info['namespace'] );
				unset( $info['title'] );
			}
		}

		return $info;
	}

	public function workItemCount() {
		return isset( $this->params['pages'] ) ? count( $this->params['pages'] ) : 1;
	}
}
