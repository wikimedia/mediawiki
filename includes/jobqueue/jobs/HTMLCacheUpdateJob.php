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

/**
 * Job to purge the cache for all pages that link to or use another page or file
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to purge caches for backlink pages for a given title.
 *        These jobs have (recursive:true,table:<table>) set.
 *   - b) Jobs to purge caches for a set of titles (the job title is ignored).
 *	      These jobs have (pages:(<page ID>:(<namespace>,<title>),...) set.
 *
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params );
		// Base backlink purge jobs can be de-duplicated
		$this->removeDuplicates = ( !isset( $params['range'] ) && !isset( $params['pages'] ) );
	}

	function run() {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery;

		if ( isset( $this->params['table'] ) && !isset( $this->params['pages'] ) ) {
			$this->params['recursive'] = true; // b/c; base job
		}

		// Job to purge all (or a range of) backlink pages for a page
		if ( !empty( $this->params['recursive'] ) ) {
			// Convert this into no more than $wgUpdateRowsPerJob HTMLCacheUpdateJob per-title
			// jobs and possibly a recursive HTMLCacheUpdateJob job for the rest of the backlinks
			$jobs = BacklinkJobUtils::partitionBacklinkJob(
				$this,
				$wgUpdateRowsPerJob,
				$wgUpdateRowsPerQuery, // jobs-per-title
				// Carry over information for de-duplication
				array( 'params' => $this->getRootJobParams() )
			);
			JobQueueGroup::singleton()->push( $jobs );
		// Job to purge pages for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			$this->invalidateTitles( $this->params['pages'] );
		// Job to update a single title
		} else {
			$t = $this->title;
			$this->invalidateTitles( array(
				$t->getArticleID() => array( $t->getNamespace(), $t->getDBkey() )
			) );
		}

		return true;
	}

	/**
	 * @param array $pages Map of (page ID => (namespace, DB key)) entries
	 */
	protected function invalidateTitles( array $pages ) {
		global $wgUpdateRowsPerQuery, $wgUseFileCache, $wgUseSquid;

		// Get all page IDs in this query into an array
		$pageIds = array_keys( $pages );
		if ( !$pageIds ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );

		// The page_touched field will need to be bumped for these pages.
		// Only bump it to the present time if no "rootJobTimestamp" was known.
		// If it is known, it can be used instead, which avoids invalidating output
		// that was in fact generated *after* the relevant dependency change time
		// (e.g. template edit). This is particularily useful since refreshLinks jobs
		// save back parser output and usually run along side htmlCacheUpdate jobs;
		// their saved output would be invalidated by using the current timestamp.
		if ( isset( $this->params['rootJobTimestamp'] ) ) {
			$touchTimestamp = $this->params['rootJobTimestamp'];
		} else {
			$touchTimestamp = wfTimestampNow();
		}

		// Update page_touched (skipping pages already touched since the root job).
		// Check $wgUpdateRowsPerQuery for sanity; batch jobs are sized by that already.
		foreach ( array_chunk( $pageIds, $wgUpdateRowsPerQuery ) as $batch ) {
			$dbw->update( 'page',
				array( 'page_touched' => $dbw->timestamp( $touchTimestamp ) ),
				array( 'page_id' => $batch,
					// don't invalidated pages that were already invalidated
					"page_touched < " . $dbw->addQuotes( $dbw->timestamp( $touchTimestamp ) )
				),
				__METHOD__
			);
		}
		// Get the list of affected pages (races only mean something else did the purge)
		$titleArray = TitleArray::newFromResult( $dbw->select(
			'page',
			array( 'page_namespace', 'page_title' ),
			array( 'page_id' => $pageIds, 'page_touched' => $dbw->timestamp( $touchTimestamp ) ),
			__METHOD__
		) );

		// Update squid
		if ( $wgUseSquid ) {
			$u = SquidUpdate::newFromTitles( $titleArray );
			$u->doUpdate();
		}

		// Update file cache
		if ( $wgUseFileCache ) {
			foreach ( $titleArray as $title ) {
				HTMLFileCache::clearFileCache( $title );
			}
		}
	}

	public function workItemCount() {
		return isset( $this->params['pages'] ) ? count( $this->params['pages'] ) : 1;
	}
}
