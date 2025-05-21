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

use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\Utils\BacklinkJobUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;

/**
 * Job to purge the HTML/file cache for all pages that link to or use another page or file
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to purge caches for backlink pages for a given title.
 *        These jobs have (recursive:true,table:<table>) set.
 *   - b) Jobs to purge caches for a set of titles (the job title is ignored).
 *        These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
 *
 * @ingroup JobQueue
 * @ingroup Cache
 */
class HTMLCacheUpdateJob extends Job {
	/** @var int Lag safety margin when comparing root job time age to CDN max-age */
	private const NORMAL_MAX_LAG = 10;

	public function __construct( Title $title, array $params ) {
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
		$this->params += [ 'causeAction' => 'HTMLCacheUpdateJob', 'causeAgent' => 'unknown' ];
	}

	/**
	 * @param PageReference $page Page to purge backlink pages from
	 * @param string $table Backlink table name
	 * @param array $params Additional job parameters
	 *
	 * @return HTMLCacheUpdateJob
	 */
	public static function newForBacklinks( PageReference $page, $table, $params = [] ) {
		$title = Title::newFromPageReference( $page );
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

	public function run() {
		$updateRowsPerJob = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::UpdateRowsPerJob );
		$updateRowsPerQuery = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::UpdateRowsPerQuery );
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
				$updateRowsPerJob,
				$updateRowsPerQuery, // jobs-per-title
				// Carry over information for de-duplication
				[ 'params' => $extraParams ]
			);
			MediaWikiServices::getInstance()->getJobQueueGroup()->push( $jobs );
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
		// Get all page IDs in this query into an array
		$pageIds = array_keys( $pages );
		if ( !$pageIds ) {
			return;
		}

		$rootTsUnix = wfTimestampOrNull( TS_UNIX, $this->params['rootJobTimestamp'] ?? null );
		// Bump page_touched to the current timestamp. This previously used the root job timestamp
		// (e.g. template/file edit time), which is a bit more efficient when template edits are
		// rare and don't effect the same pages much. However, this way better de-duplicates jobs,
		// which is much more useful for wikis with high edit rates. Note that RefreshLinksJob,
		// enqueued alongside HTMLCacheUpdateJob, saves the parser output since it has to parse
		// anyway. We assume that vast majority of the cache jobs finish before the link jobs,
		// so using the current timestamp instead of the root timestamp is not expected to
		// invalidate these cache entries too often.
		$newTouchedUnix = (int)wfTimestamp();
		// Timestamp used to bypass pages already invalided since the triggering event
		$casTsUnix = $rootTsUnix ?? $newTouchedUnix;

		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		$dbProvider = $services->getConnectionProvider();
		$dbw = $dbProvider->getPrimaryDatabase();
		$ticket = $dbProvider->getEmptyTransactionTicket( __METHOD__ );
		// Update page_touched (skipping pages already touched since the root job).
		// Check $wgUpdateRowsPerQuery; batch jobs are sized by that already.
		$batches = array_chunk( $pageIds, $config->get( MainConfigNames::UpdateRowsPerQuery ) );
		foreach ( $batches as $batch ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_touched' => $dbw->timestamp( $newTouchedUnix ) ] )
				->where( [ 'page_id' => $batch ] )
				->andWhere( $dbw->expr( 'page_touched', '<', $dbw->timestamp( $casTsUnix ) ) )
				->caller( __METHOD__ )->execute();
			if ( count( $batches ) > 1 ) {
				$dbProvider->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}
		// Get the list of affected pages (races only mean something else did the purge)
		$queryBuilder = $dbw->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( [ 'page_id' => $pageIds, 'page_touched' => $dbw->timestamp( $newTouchedUnix ) ] );
		if ( $config->get( MainConfigNames::PageLanguageUseDB ) ) {
			$queryBuilder->field( 'page_lang' );
		}
		$titleArray = $services->getTitleFactory()->newTitleArrayFromResult(
			$queryBuilder->caller( __METHOD__ )->fetchResultSet()
		);

		// Update CDN and file caches
		$htmlCache = $services->getHtmlCacheUpdater();
		$htmlCache->purgeTitleUrls(
			$titleArray,
			$htmlCache::PURGE_NAIVE | $htmlCache::PURGE_URLS_LINKSUPDATE_ONLY,
			[ $htmlCache::UNLESS_CACHE_MTIME_AFTER => $casTsUnix + self::NORMAL_MAX_LAG ]
		);
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

/** @deprecated class alias since 1.44 */
class_alias( HTMLCacheUpdateJob::class, 'HTMLCacheUpdateJob' );
