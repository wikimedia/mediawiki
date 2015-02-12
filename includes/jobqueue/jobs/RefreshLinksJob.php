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
	const PARSE_THRESHOLD_SEC = 1.0;

	function __construct( $title, $params = '' ) {
		parent::__construct( 'refreshLinks', $title, $params );
		// A separate type is used just for cascade-protected backlinks
		if ( !empty( $this->params['prioritize'] ) ) {
			$this->command .= 'Prioritized';
		}
		// Base backlink update jobs and per-title update jobs can be de-duplicated.
		// If template A changes twice before any jobs run, a clean queue will have:
		//		(A base, A base)
		// The second job is ignored by the queue on insertion.
		// Suppose, many pages use template A, and that template itself uses template B.
		// An edit to both will first create two base jobs. A clean FIFO queue will have:
		//		(A base, B base)
		// When these jobs run, the queue will have per-title and remnant partition jobs:
		//		(titleX,titleY,titleZ,...,A remnant,titleM,titleN,titleO,...,B remnant)
		// Some these jobs will be the same, and will automatically be ignored by
		// the queue upon insertion. Some title jobs will run before the duplicate is
		// inserted, so the work will still be done twice in those cases. More titles
		// can be de-duplicated as the remnant jobs continue to be broken down. This
		// works best when $wgUpdateRowsPerJob, and either the pages have few backlinks
		// and/or the backlink sets for pages A and B are almost identical.
		$this->removeDuplicates = !isset( $params['range'] )
			&& ( !isset( $params['pages'] ) || count( $params['pages'] ) == 1 );
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
			// Convert this into no more than $wgUpdateRowsPerJob RefreshLinks per-title
			// jobs and possibly a recursive RefreshLinks job for the rest of the backlinks
			$jobs = BacklinkJobUtils::partitionBacklinkJob(
				$this,
				$wgUpdateRowsPerJob,
				1, // job-per-title
				array( 'params' => $extraParams )
			);
			JobQueueGroup::singleton()->push( $jobs );
		// Job to update link tables for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			foreach ( $this->params['pages'] as $pageId => $nsAndKey ) {
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
	protected function runForTitle( Title $title = null ) {
		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $title ) ) {
			$this->setLastError( "refreshLinks: Invalid title" );
			return false;
		}

		// Wait for the DB of the current/next slave DB handle to catch up to the master.
		// This way, we get the correct page_latest for templates or files that just changed
		// milliseconds ago, having triggered this job to begin with.
		if ( isset( $this->params['masterPos'] ) && $this->params['masterPos'] !== false ) {
			wfGetLB()->waitFor( $this->params['masterPos'] );
		}

		$page = WikiPage::factory( $title );

		// Fetch the current revision...
		$revision = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			$this->setLastError( "refreshLinks: Article not found {$title->getPrefixedDBkey()}" );
			return false; // XXX: what if it was just deleted?
		}
		$content = $revision->getContent( Revision::RAW );
		if ( !$content ) {
			// If there is no content, pretend the content is empty
			$content = $revision->getContentHandler()->makeEmptyContent();
		}

		$parserOutput = false;
		$parserOptions = $page->makeParserOptions( 'canonical' );
		// If page_touched changed after this root job (with a good slave lag skew factor),
		// then it is likely that any views of the pages already resulted in re-parses which
		// are now in cache. This can be reused to avoid expensive parsing in some cases.
		if ( isset( $this->params['rootJobTimestamp'] ) ) {
			$skewedTimestamp = wfTimestamp( TS_UNIX, $this->params['rootJobTimestamp'] ) + 5;
			if ( $page->getLinksTimestamp() > wfTimestamp( TS_MW, $skewedTimestamp ) ) {
				// Something already updated the backlinks since this job was made
				return true;
			}
			if ( $page->getTouched() > wfTimestamp( TS_MW, $skewedTimestamp ) ) {
				$parserOutput = ParserCache::singleton()->getDirty( $page, $parserOptions );
				if ( $parserOutput && $parserOutput->getCacheTime() <= $skewedTimestamp ) {
					$parserOutput = false; // too stale
				}
			}
		}
		// Fetch the current revision and parse it if necessary...
		if ( $parserOutput == false ) {
			$start = microtime( true );
			// Revision ID must be passed to the parser output to get revision variables correct
			$parserOutput = $content->getParserOutput(
				$title, $revision->getId(), $parserOptions, false );
			$ellapsed = microtime( true ) - $start;
			// If it took a long time to render, then save this back to the cache to avoid
			// wasted CPU by other apaches or job runners. We don't want to always save to
			// cache as this can cause high cache I/O and LRU churn when a template changes.
			if ( $ellapsed >= self::PARSE_THRESHOLD_SEC
				&& $page->isParserCacheUsed( $parserOptions, $revision->getId() )
				&& $parserOutput->isCacheable()
			) {
				$ctime = wfTimestamp( TS_MW, (int)$start ); // cache time
				ParserCache::singleton()->save(
					$parserOutput, $page, $parserOptions, $ctime, $revision->getId()
				);
			}
		}

		$updates = $content->getSecondaryDataUpdates( $title, null, false, $parserOutput );
		DataUpdate::runUpdates( $updates );

		InfoAction::invalidateCache( $title );

		return true;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		if ( is_array( $info['params'] ) ) {
			// Don't let highly unique "masterPos" values ruin duplicate detection
			unset( $info['params']['masterPos'] );
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
