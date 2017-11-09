<?php
/**
 * HTML cache invalidation of all pages linking to a given title.
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
 * @ingroup Cache
 */

use MediaWiki\MediaWikiServices;

/**
 * Job to purge the cache for all pages that link to or use another page or file
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to purge caches for backlink pages for a given title.
 *        These jobs have (recursive:true,table:<table>) set.
 *   - b) Jobs to purge caches for a set of titles (the job title is ignored).
 *        These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
 *
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params );
		// Avoid the overhead of de-duplication when it would be pointless.
		// Note that these jobs always set page_touched to the current time,
		// so letting the older existing job "win" is still correct.
		$this->removeDuplicates = (
			// Ranges rarely will line up
			!isset( $params['range'] ) &&
			// Multiple pages per job make matches unlikely
			!( isset( $params['pages'] ) && count( $params['pages'] ) != 1 )
		);
		$this->params += [ 'causeAction' => 'unknown', 'causeAgent' => 'unknown' ];
	}

	/**
	 * @param Title $title Title to purge backlink pages from
	 * @param string $table Backlink table name
	 * @param array $params Additional job parameters
	 * @return HTMLCacheUpdateJob
	 */
	public static function newForBacklinks( Title $title, $table, $params = [] ) {
		return new self(
			$title,
			[
				'table' => $table,
				'recursive' => true
			] + Job::newRootJobParams( // "overall" refresh links job info
				"htmlCacheUpdate:{$table}:{$title->getPrefixedText()}"
			) + $params
		);
	}

	function run() {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery;

		if ( isset( $this->params['table'] ) && !isset( $this->params['pages'] ) ) {
			$this->params['recursive'] = true; // b/c; base job
		}

		// Job to purge all (or a range of) backlink pages for a page
		if ( !empty( $this->params['recursive'] ) ) {
			// Carry over information for de-duplication
			$extraParams = $this->getRootJobParams();
			// Carry over cause information for logging
			$extraParams['causeAction'] = $this->params['causeAction'];
			$extraParams['causeAgent'] = $this->params['causeAgent'];
			// Convert this into no more than $wgUpdateRowsPerJob HTMLCacheUpdateJob per-title
			// jobs and possibly a recursive HTMLCacheUpdateJob job for the rest of the backlinks
			$jobs = BacklinkJobUtils::partitionBacklinkJob(
				$this,
				$wgUpdateRowsPerJob,
				$wgUpdateRowsPerQuery, // jobs-per-title
				// Carry over information for de-duplication
				[ 'params' => $extraParams ]
			);
			JobQueueGroup::singleton()->push( $jobs );
		// Job to purge pages for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			$this->invalidateTitles( $this->params['pages'] );
		// Job to update a single title
		} else {
			$t = $this->title;
			$this->invalidateTitles( [
				$t->getArticleID() => [ $t->getNamespace(), $t->getDBkey() ]
			] );
		}

		return true;
	}

	/**
	 * @param array $pages Map of (page ID => (namespace, DB key)) entries
	 */
	protected function invalidateTitles( array $pages ) {
		global $wgUpdateRowsPerQuery, $wgUseFileCache, $wgPageLanguageUseDB;

		// Get all page IDs in this query into an array
		$pageIds = array_keys( $pages );
		if ( !$pageIds ) {
			return;
		}

		// Bump page_touched to the current timestamp. This used to use the root job timestamp
		// (e.g. template/file edit time), which was a bit more efficient when template edits are
		// rare and don't effect the same pages much. However, this way allows for better
		// de-duplication, which is much more useful for wikis with high edit rates. Note that
		// RefreshLinksJob, which is enqueued alongside HTMLCacheUpdateJob, saves the parser output
		// since it has to parse anyway. We assume that vast majority of the cache jobs finish
		// before the link jobs, so using the current timestamp instead of the root timestamp is
		// not expected to invalidate these cache entries too often.
		$touchTimestamp = wfTimestampNow();
		// If page_touched is higher than this, then something else already bumped it after enqueue
		$condTimestamp = isset( $this->params['rootJobTimestamp'] )
			? $this->params['rootJobTimestamp']
			: $touchTimestamp;

		$dbw = wfGetDB( DB_MASTER );
		$factory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$ticket = $factory->getEmptyTransactionTicket( __METHOD__ );
		// Update page_touched (skipping pages already touched since the root job).
		// Check $wgUpdateRowsPerQuery for sanity; batch jobs are sized by that already.
		foreach ( array_chunk( $pageIds, $wgUpdateRowsPerQuery ) as $batch ) {
			$factory->commitAndWaitForReplication( __METHOD__, $ticket );

			$dbw->update( 'page',
				[ 'page_touched' => $dbw->timestamp( $touchTimestamp ) ],
				[ 'page_id' => $batch,
					// don't invalidated pages that were already invalidated
					"page_touched < " . $dbw->addQuotes( $dbw->timestamp( $condTimestamp ) )
				],
				__METHOD__
			);
		}
		// Get the list of affected pages (races only mean something else did the purge)
		$titleArray = TitleArray::newFromResult( $dbw->select(
			'page',
			array_merge(
				[ 'page_namespace', 'page_title' ],
				$wgPageLanguageUseDB ? [ 'page_lang' ] : []
			),
			[ 'page_id' => $pageIds, 'page_touched' => $dbw->timestamp( $touchTimestamp ) ],
			__METHOD__
		) );

		// Update CDN; call purge() directly so as to not bother with secondary purges
		$urls = [];
		foreach ( $titleArray as $title ) {
			/** @var Title $title */
			$urls = array_merge( $urls, $title->getCdnUrls() );
		}
		CdnCacheUpdate::purge( $urls );

		// Update file cache
		if ( $wgUseFileCache ) {
			foreach ( $titleArray as $title ) {
				HTMLFileCache::clearFileCache( $title );
			}
		}
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
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
			return 0; // nothing actually purged
		} elseif ( isset( $this->params['pages'] ) ) {
			return count( $this->params['pages'] );
		}

		return 1; // one title
	}
}
