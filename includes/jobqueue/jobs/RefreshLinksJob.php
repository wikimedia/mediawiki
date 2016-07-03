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

/**
 * Job to update link tables for pages
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to update links for backlink pages for a given title.
 *        These jobs have (recursive:true,table:<table>) set.
 *   - b) Jobs to update links for a set of pages (the job title is ignored).
 *	      These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
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

	function __construct( Title $title, array $params ) {
		parent::__construct( 'refreshLinks', $title, $params );
		// Avoid the overhead of de-duplication when it would be pointless
		$this->removeDuplicates = (
			// Master positions won't match
			!isset( $params['masterPos'] ) &&
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
			// Carry over information for de-duplication
			$extraParams = $this->getRootJobParams();
			// Avoid slave lag when fetching templates.
			// When the outermost job is run, we know that the caller that enqueued it must have
			// committed the relevant changes to the DB by now. At that point, record the master
			// position and pass it along as the job recursively breaks into smaller range jobs.
			// Hopefully, when leaf jobs are popped, the slaves will have reached that position.
			if ( isset( $this->params['masterPos'] ) ) {
				$extraParams['masterPos'] = $this->params['masterPos'];
			} elseif ( wfGetLB()->getServerCount() > 1 ) {
				$extraParams['masterPos'] = wfGetLB()->getMasterPos();
			} else {
				$extraParams['masterPos'] = false;
			}
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
			$this->waitForMasterPosition();
			foreach ( $this->params['pages'] as $pageId => $nsAndKey ) {
				list( $ns, $dbKey ) = $nsAndKey;
				$this->runForTitle( Title::makeTitleSafe( $ns, $dbKey ) );
			}
		// Job to update link tables for a given title
		} else {
			$this->waitForMasterPosition();
			$this->runForTitle( $this->title );
		}

		return true;
	}

	protected function waitForMasterPosition() {
		if ( !empty( $this->params['masterPos'] ) && wfGetLB()->getServerCount() > 1 ) {
			// Wait for the current/next slave DB handle to catch up to the master.
			// This way, we get the correct page_latest for templates or files that just
			// changed milliseconds ago, having triggered this job to begin with.
			wfGetLB()->waitFor( $this->params['masterPos'] );
		}
	}

	/**
	 * @param Title $title
	 * @return bool
	 */
	protected function runForTitle( Title $title ) {
		$page = WikiPage::factory( $title );
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
			$this->setLastError( "Revision not found for {$title->getPrefixedDBkey()}" );
			return false; // just deleted?
		}

		if ( !$revision->isCurrent() ) {
			// If the revision isn't current, there's no point in doing a bunch
			// of work just to fail at the lockAndGetLatest() check later.
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
				// Neither clock skew nor DB snapshot/slave lag matter much for such
				// updates; focus on reusing the (often recently updated) cache
			} else {
				// For transclusion updates, the template changes must be reflected
				$skewedTimestamp = wfTimestamp( TS_MW,
					wfTimestamp( TS_UNIX, $skewedTimestamp ) + self::CLOCK_FUDGE
				);
			}

			if ( $page->getLinksTimestamp() > $skewedTimestamp ) {
				// Something already updated the backlinks since this job was made
				return true;
			}

			if ( $page->getTouched() >= $skewedTimestamp || $opportunistic ) {
				// Something bumped page_touched since this job was made or the cache is
				// otherwise suspected to be up-to-date. As long as the cache rev ID matches
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
		if ( !$parserOutput ) {
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
		}

		$updates = $content->getSecondaryDataUpdates(
			$title,
			null,
			!empty( $this->params['useRecursiveLinksUpdate'] ),
			$parserOutput
		);

		foreach ( $updates as $key => $update ) {
			// FIXME: This code probably shouldn't be here?
			// Needed by things like Echo notifications which need
			// to know which user caused the links update
			if ( $update instanceof LinksUpdate ) {
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

		$latestNow = $page->lockAndGetLatest();
		if ( !$latestNow || $revision->getId() != $latestNow ) {
			// Do not clobber over newer updates with older ones. If all jobs where FIFO and
			// serialized, it would be OK to update links based on older revisions since it
			// would eventually get to the latest. Since that is not the case (by design),
			// only update the link tables to a state matching the current revision's output.
			$this->setLastError( "page_latest changed from {$revision->getId()} to $latestNow" );
			return false;
		}

		DataUpdate::runUpdates( $updates );

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
