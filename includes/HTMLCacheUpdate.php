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
	public $mTitle, $mTable, $mPrefix;
	public $mRowsPerJob, $mRowsPerQuery;

	function __construct( $titleTo, $table ) {
		global $wgUpdateRowsPerJob, $wgUpdateRowsPerQuery;

		$this->mTitle = $titleTo;
		$this->mTable = $table;
		$this->mRowsPerJob = $wgUpdateRowsPerJob;
		$this->mRowsPerQuery = $wgUpdateRowsPerQuery;
		$this->mCache = $this->mTitle->getBacklinkCache();
	}

	public function doUpdate() {
		# Fetch the IDs
		$numRows = $this->mCache->getNumLinks( $this->mTable );

		if ( $numRows != 0 ) {
			if ( $numRows > $this->mRowsPerJob ) {
				$this->insertJobs();
			} else {
				$this->invalidate();
			}
		}
		wfRunHooks( 'HTMLCacheUpdate::doUpdate', array($this->mTitle) );
	}

	protected function insertJobs() {
		$batches = $this->mCache->partition( $this->mTable, $this->mRowsPerJob );
		if ( !$batches ) {
			return;
		}
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
	 * Invalidate a set of pages, right now
	 */
	public function invalidate( $startId = false, $endId = false ) {
		global $wgUseFileCache, $wgUseSquid;

		$titleArray = $this->mCache->getLinks( $this->mTable, $startId, $endId );
		if ( $titleArray->count() == 0 ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		$timestamp = $dbw->timestamp();

		# Get all IDs in this query into an array
		$ids = array();
		foreach ( $titleArray as $title ) {
			$ids[] = $title->getArticleID();
		}
		# Update page_touched
		$dbw->update( 'page',
			array( 'page_touched' => $timestamp ),
			array( 'page_id IN (' . $dbw->makeList( $ids ) . ')' ),
			__METHOD__
		);

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
	 * @param Title $title The title linked to
	 * @param array $params Job parameters (table, start and end page_ids)
	 * @param integer $id job_id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params, $id );
		$this->table = $params['table'];
		$this->start = $params['start'];
		$this->end = $params['end'];
	}

	public function run() {
		$update = new HTMLCacheUpdate( $this->title, $this->table );
		$update->invalidate( $this->start, $this->end );
		return true;
	}
}
