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
 * @ingroup Cache
 */

/**
 * Job to purge the cache for all pages that link to or use another page or file
 *
 * This job comes in a few variants:
 *   - a) Recursive jobs to purge caches for backlink pages for a given title.
 *        These jobs have have (recursive:true,table:<table>) set.
 *   - b) Jobs to purge caches for a set of titles (the job title is ignored).
 *	      These jobs have have (pages:(<page ID>:(<namespace>,<title>),...) set.
 *
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	function __construct( $title, $params = '' ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params );
		// Base backlink purge jobs can be de-duplicated
		$this->removeDuplicates = ( !isset( $params['range'] ) && !isset( $params['pages'] ) );
	}

	function run() {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery, $wgMaxBacklinksInvalidate;

		static $expected = array( 'recursive', 'pages' ); // new jobs have one of these

		$oldRangeJob = false;
		if ( !array_intersect( array_keys( $this->params ), $expected ) ) {
			// B/C for older job params formats that lack these fields:
			// a) base jobs with just ("table") and b) range jobs with ("table","start","end")
			if ( isset( $this->params['start'] ) && isset( $this->params['end'] ) ) {
				$oldRangeJob = true;
			} else {
				$this->params['recursive'] = true; // base job
			}
		}

		// Job to purge all (or a range of) backlink pages for a page
		if ( !empty( $this->params['recursive'] ) ) {
			// @TODO: try to use delayed jobs if possible?
			if ( !isset( $this->params['range'] ) && $wgMaxBacklinksInvalidate !== false ) {
				$numRows = $this->title->getBacklinkCache()->getNumLinks(
					$this->params['table'], $wgMaxBacklinksInvalidate );
				if ( $numRows > $wgMaxBacklinksInvalidate ) {
					return true;
				}
			}
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
		// Job to purge pages for for a set of titles
		} elseif ( isset( $this->params['pages'] ) ) {
			$this->invalidateTitles( $this->params['pages'] );
		// B/C for job to purge a range of backlink pages for a given page
		} elseif ( $oldRangeJob ) {
			$titleArray = $this->title->getBacklinkCache()->getLinks(
				$this->params['table'], $this->params['start'], $this->params['end'] );

			$pages = array(); // same format BacklinkJobUtils uses
			foreach ( $titleArray as $tl ) {
				$pages[$tl->getArticleId()] = array( $tl->getNamespace(), $tl->getDbKey() );
			}

			$jobs = array();
			foreach ( array_chunk( $pages, $wgUpdateRowsPerJob ) as $pageChunk ) {
				$jobs[] = new HTMLCacheUpdateJob( $this->title,
					array(
						'table' => $this->params['table'],
						'pages' => $pageChunk
					) + $this->getRootJobParams() // carry over information for de-duplication
				);
			}
			JobQueueGroup::singleton()->push( $jobs );
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
		$timestamp = $dbw->timestamp();

		// Don't invalidated pages that were already invalidated
		$touchedCond = isset( $this->params['rootJobTimestamp'] )
			? array( "page_touched < " .
				$dbw->addQuotes( $dbw->timestamp( $this->params['rootJobTimestamp'] ) ) )
			: array();

		// Update page_touched (skipping pages already touched since the root job).
		// Check $wgUpdateRowsPerQuery for sanity; batch jobs are sized by that already.
		foreach ( array_chunk( $pageIds, $wgUpdateRowsPerQuery ) as $batch ) {
			$dbw->update( 'page',
				array( 'page_touched' => $timestamp ),
				array( 'page_id' => $batch ) + $touchedCond,
				__METHOD__
			);
		}
		// Get the list of affected pages (races only mean something else did the purge)
		$titleArray = TitleArray::newFromResult( $dbw->select(
			'page',
			array( 'page_namespace', 'page_title' ),
			array( 'page_id' => $pageIds, 'page_touched' => $timestamp ),
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

	function advisedBackoff() {
		// Avoid too many purges, which cause higher CPU usage under normal traffic
		// due to more proxy and parser cache misses. This helps avoid huge spikes.
		if ( isset( $this->params['pages'] ) ) {
			// Aim for about 100 pages/sec per job runner process
			$seconds = floor( count( $this->params['pages'] ) / 100 );
			$percentChance = count( $this->params['pages'] ) % 100;
			$seconds += ( mt_rand( 1, 100 ) <= $percentChance ) ? 1 : 0;
			return $seconds;
		}
		return 0;
	}
}
