<?php

/**
 * Class to invalidate the HTML cache of all the pages linking to a given title.
 * Small numbers of links will be done immediately, large numbers are pushed onto
 * the job queue.
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
 * Image::getLinksTo() and Title::touchArray(), which uses about 2KB of memory per
 * link.
 *
 * @ingroup Cache
 */
class HTMLCacheUpdate
{
	public $mTitle, $mTable, $mPrefix, $mStart, $mEnd;
	public $mRowsPerJob, $mRowsPerQuery;

	function __construct( $titleTo, $table, $start = false, $end = false ) {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery;

		$this->mTitle = $titleTo;
		$this->mTable = $table;
		$this->mStart = $start;
		$this->mEnd = $end;
		$this->mRowsPerJob = $wgUpdateRowsPerJob;
		$this->mRowsPerQuery = $wgUpdateRowsPerQuery;
		$this->mCache = $this->mTitle->getBacklinkCache();
	}

	public function doUpdate() {
		if ( $this->mStart || $this->mEnd ) {
			$this->doPartialUpdate();
			return;
		}

		# Get an estimate of the number of rows from the BacklinkCache
		$numRows = $this->mCache->getNumLinks( $this->mTable );
		if ( $numRows > $this->mRowsPerJob * 2 ) {
			# Do fast cached partition
			$this->insertJobs();
		} else {
			# Get the links from the DB
			$titleArray = $this->mCache->getLinks( $this->mTable );
			# Check if the row count estimate was correct
			if ( $titleArray->count() > $this->mRowsPerJob * 2 ) {
				# Not correct, do accurate partition
				wfDebug( __METHOD__.": row count estimate was incorrect, repartitioning\n" );
				$this->insertJobsFromTitles( $titleArray );
			} else {
				$this->invalidateTitles( $titleArray );
			}
		}
	}

	/**
	 * Update some of the backlinks, defined by a page ID range
	 */
	protected function doPartialUpdate() {
		$titleArray = $this->mCache->getLinks( $this->mTable, $this->mStart, $this->mEnd );
		if ( $titleArray->count() <= $this->mRowsPerJob * 2 ) {
			# This partition is small enough, do the update
			$this->invalidateTitles( $titleArray );
		} else {
			# Partitioning was excessively inaccurate. Divide the job further.
			# This can occur when a large number of links are added in a short 
			# period of time, say by updating a heavily-used template.
			$this->insertJobsFromTitles( $titleArray );
		}
	}

	/**
	 * Partition the current range given by $this->mStart and $this->mEnd,
	 * using a pre-calculated title array which gives the links in that range.
	 * Queue the resulting jobs.
	 */
	protected function insertJobsFromTitles( $titleArray ) {
		# We make subpartitions in the sense that the start of the first job
		# will be the start of the parent partition, and the end of the last
		# job will be the end of the parent partition.
		$jobs = array();
		$start = $this->mStart; # start of the current job
		$numTitles = 0;
		foreach ( $titleArray as $title ) {
			$id = $title->getArticleID();
			# $numTitles is now the number of titles in the current job not 
			# including the current ID
			if ( $numTitles >= $this->mRowsPerJob ) {
				# Add a job up to but not including the current ID
				$params = array(
					'table' => $this->mTable,
					'start' => $start,
					'end' => $id - 1
				);
				$jobs[] = new HTMLCacheUpdateJob( $this->mTitle, $params );
				$start = $id;
				$numTitles = 0;
			}
			$numTitles++;
		}
		# Last job
		$params = array(
			'table' => $this->mTable,
			'start' => $start,
			'end' => $this->mEnd
		);
		$jobs[] = new HTMLCacheUpdateJob( $this->mTitle, $params );
		wfDebug( __METHOD__.": repartitioning into " . count( $jobs ) . " jobs\n" );

		if ( count( $jobs ) < 2 ) {
			# I don't think this is possible at present, but handling this case
			# makes the code a bit more robust against future code updates and 
			# avoids a potential infinite loop of repartitioning
			wfDebug( __METHOD__.": repartitioning failed!\n" );
			$this->invalidateTitles( $titleArray );
			return;
		}

		Job::batchInsert( $jobs );
	}

	protected function insertJobs() {
		$batches = $this->mCache->partition( $this->mTable, $this->mRowsPerJob );
		if ( !$batches ) {
			return;
		}
		$jobs = array();
		foreach ( $batches as $batch ) {
			$params = array(
				'table' => $this->mTable,
				'start' => $batch[0],
				'end' => $batch[1],
			);
			$jobs[] = new HTMLCacheUpdateJob( $this->mTitle, $params );
		}
		Job::batchInsert( $jobs );
	}

	/**
	 * Invalidate a range of pages, right now
	 * @deprecated
	 */
	public function invalidate( $startId = false, $endId = false ) {
		$titleArray = $this->mCache->getLinks( $this->mTable, $startId, $endId );
		$this->invalidateTitles( $titleArray );
	}

	/**
	 * Invalidate an array (or iterator) of Title objects, right now
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

		# Update page_touched
		$batches = array_chunk( $ids, $this->mRowsPerQuery );
		foreach ( $batches as $batch ) {
			$dbw->update( 'page',
				array( 'page_touched' => $timestamp ),
				array( 'page_id IN (' . $dbw->makeList( $batch ) . ')' ),
				__METHOD__
			);
		}

		# Update squid
		if ( $wgUseSquid ) {
			$u = SquidUpdate::newFromTitles( $titleArray );
			$u->doUpdate();
		}

		# Update file cache
		if  ( $wgUseFileCache ) {
			foreach ( $titleArray as $title ) {
				HTMLFileCache::clearFileCache( $title );
			}
		}
	}

}

/**
 * Job wrapper for HTMLCacheUpdate. Gets run whenever a related
 * job gets called from the queue.
 * 
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	var $table, $start, $end;

	/**
	 * Construct a job
	 * @param $title Title: the title linked to
	 * @param $params Array: job parameters (table, start and end page_ids)
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params, $id );
		$this->table = $params['table'];
		$this->start = $params['start'];
		$this->end = $params['end'];
	}

	public function run() {
		$update = new HTMLCacheUpdate( $this->title, $this->table, $this->start, $this->end );
		$update->doUpdate();
		return true;
	}
}
