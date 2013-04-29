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
 * Job wrapper for HTMLCacheUpdate. Gets run whenever a related
 * job gets called from the queue.
 *
 * This class is designed to work efficiently with small numbers of links, and
 * to work reasonably well with up to ~10^5 links. Above ~10^6 links, the memory
 * and time requirements of loading all backlinked IDs in doUpdate() might become
 * prohibitive. The requirements measured at Wikimedia are approximately:
 *
 *   memory: 48 bytes per row
 *   time: 16us per row for the query plus processing
 *
 * The reason this query is done is to support partitioning of the job
 * by backlinked ID. The memory issue could be allieviated by doing this query in
 * batches, but of course LIMIT with an offset is inefficient on the DB side.
 *
 * The class is nevertheless a vast improvement on the previous method of using
 * File::getLinksTo() and Title::touchArray(), which uses about 2KB of memory per
 * link.
 *
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	/** @var BacklinkCache */
	protected $blCache;

	protected $rowsPerJob, $rowsPerQuery;

	/**
	 * Construct a job
	 * @param $title Title: the title linked to
	 * @param array $params job parameters (table, start and end page_ids)
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery;

		parent::__construct( 'htmlCacheUpdate', $title, $params, $id );

		$this->rowsPerJob = $wgUpdateRowsPerJob;
		$this->rowsPerQuery = $wgUpdateRowsPerQuery;
		$this->blCache = $title->getBacklinkCache();
	}

	public function run() {
		if ( isset( $this->params['start'] ) && isset( $this->params['end'] ) ) {
			# This is hit when a job is actually performed
			return $this->doPartialUpdate();
		} else {
			# This is hit when the jobs have to be inserted
			return $this->doFullUpdate();
		}
	}

	/**
	 * Update all of the backlinks
	 */
	protected function doFullUpdate() {
		global $wgMaxBacklinksInvalidate;

		# Get an estimate of the number of rows from the BacklinkCache
		$max = max( $this->rowsPerJob * 2, $wgMaxBacklinksInvalidate ) + 1;
		$numRows = $this->blCache->getNumLinks( $this->params['table'], $max );
		if ( $wgMaxBacklinksInvalidate !== false && $numRows > $wgMaxBacklinksInvalidate ) {
			wfDebug( "Skipped HTML cache invalidation of {$this->title->getPrefixedText()}." );
			return true;
		}

		if ( $numRows > $this->rowsPerJob * 2 ) {
			# Do fast cached partition
			$this->insertPartitionJobs();
		} else {
			# Get the links from the DB
			$titleArray = $this->blCache->getLinks( $this->params['table'] );
			# Check if the row count estimate was correct
			if ( $titleArray->count() > $this->rowsPerJob * 2 ) {
				# Not correct, do accurate partition
				wfDebug( __METHOD__ . ": row count estimate was incorrect, repartitioning\n" );
				$this->insertJobsFromTitles( $titleArray );
			} else {
				$this->invalidateTitles( $titleArray ); // just do the query
			}
		}

		return true;
	}

	/**
	 * Update some of the backlinks, defined by a page ID range
	 */
	protected function doPartialUpdate() {
		$titleArray = $this->blCache->getLinks(
			$this->params['table'], $this->params['start'], $this->params['end'] );
		if ( $titleArray->count() <= $this->rowsPerJob * 2 ) {
			# This partition is small enough, do the update
			$this->invalidateTitles( $titleArray );
		} else {
			# Partitioning was excessively inaccurate. Divide the job further.
			# This can occur when a large number of links are added in a short
			# period of time, say by updating a heavily-used template.
			$this->insertJobsFromTitles( $titleArray );
		}
		return true;
	}

	/**
	 * Partition the current range given by $this->params['start'] and $this->params['end'],
	 * using a pre-calculated title array which gives the links in that range.
	 * Queue the resulting jobs.
	 *
	 * @param $titleArray array
	 * @param $rootJobParams array
	 * @return void
	 */
	protected function insertJobsFromTitles( $titleArray, $rootJobParams = array() ) {
		// Carry over any "root job" information
		$rootJobParams = $this->getRootJobParams();
		# We make subpartitions in the sense that the start of the first job
		# will be the start of the parent partition, and the end of the last
		# job will be the end of the parent partition.
		$jobs = array();
		$start = $this->params['start']; # start of the current job
		$numTitles = 0;
		foreach ( $titleArray as $title ) {
			$id = $title->getArticleID();
			# $numTitles is now the number of titles in the current job not
			# including the current ID
			if ( $numTitles >= $this->rowsPerJob ) {
				# Add a job up to but not including the current ID
				$jobs[] = new HTMLCacheUpdateJob( $this->title,
					array(
						'table' => $this->params['table'],
						'start' => $start,
						'end' => $id - 1
					) + $rootJobParams // carry over information for de-duplication
				);
				$start = $id;
				$numTitles = 0;
			}
			$numTitles++;
		}
		# Last job
		$jobs[] = new HTMLCacheUpdateJob( $this->title,
			array(
				'table' => $this->params['table'],
				'start' => $start,
				'end' => $this->params['end']
			) + $rootJobParams // carry over information for de-duplication
		);
		wfDebug( __METHOD__ . ": repartitioning into " . count( $jobs ) . " jobs\n" );

		if ( count( $jobs ) < 2 ) {
			# I don't think this is possible at present, but handling this case
			# makes the code a bit more robust against future code updates and
			# avoids a potential infinite loop of repartitioning
			wfDebug( __METHOD__ . ": repartitioning failed!\n" );
			$this->invalidateTitles( $titleArray );
		} else {
			JobQueueGroup::singleton()->push( $jobs );
		}
	}

	/**
	 * @param $rootJobParams array
	 * @return void
	 */
	protected function insertPartitionJobs( $rootJobParams = array() ) {
		// Carry over any "root job" information
		$rootJobParams = $this->getRootJobParams();

		$batches = $this->blCache->partition( $this->params['table'], $this->rowsPerJob );
		if ( !count( $batches ) ) {
			return; // no jobs to insert
		}

		$jobs = array();
		foreach ( $batches as $batch ) {
			list( $start, $end ) = $batch;
			$jobs[] = new HTMLCacheUpdateJob( $this->title,
				array(
					'table' => $this->params['table'],
					'start' => $start,
					'end' => $end,
				) + $rootJobParams // carry over information for de-duplication
			);
		}

		JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * Invalidate an array (or iterator) of Title objects, right now
	 * @param $titleArray array
	 */
	protected function invalidateTitles( $titleArray ) {
		global $wgUseFileCache, $wgUseSquid;

		$dbw = wfGetDB( DB_MASTER );
		$timestamp = $dbw->timestamp();

		# Get all IDs in this query into an array
		$ids = array();
		foreach ( $titleArray as $title ) {
			$ids[] = $title->getArticleID();
		}

		if ( !$ids ) {
			return;
		}

		# Don't invalidated pages that were already invalidated
		$touchedCond = isset( $this->params['rootJobTimestamp'] )
			? array( "page_touched < " .
				$dbw->addQuotes( $dbw->timestamp( $this->params['rootJobTimestamp'] ) ) )
			: array();

		# Update page_touched
		$batches = array_chunk( $ids, $this->rowsPerQuery );
		foreach ( $batches as $batch ) {
			$dbw->update( 'page',
				array( 'page_touched' => $timestamp ),
				array( 'page_id' => $batch ) + $touchedCond,
				__METHOD__
			);
		}

		# Update squid
		if ( $wgUseSquid ) {
			$u = SquidUpdate::newFromTitles( $titleArray );
			$u->doUpdate();
		}

		# Update file cache
		if ( $wgUseFileCache ) {
			foreach ( $titleArray as $title ) {
				HTMLFileCache::clearFileCache( $title );
			}
		}
	}
}
