<?php
/**
 * Job to update links for a given title.
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
 * Background job to update links for a given title.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob extends Job {
	function __construct( $title, $params = '', $id = 0 ) {
		parent::__construct( 'refreshLinks', $title, $params, $id );
		$this->removeDuplicates = true; // job is expensive
	}

	/**
	 * Run a refreshLinks job
	 * @return boolean success
	 */
	function run() {
		wfProfileIn( __METHOD__ );

		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks: Invalid title";
			wfProfileOut( __METHOD__ );
			return false;
		}

		# Wait for the DB of the current/next slave DB handle to catch up to the master.
		# This way, we get the correct page_latest for templates or files that just changed
		# milliseconds ago, having triggered this job to begin with.
		if ( isset( $this->params['masterPos'] ) ) {
			wfGetLB()->waitFor( $this->params['masterPos'] );
		}

		$revision = Revision::newFromTitle( $this->title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			$this->error = 'refreshLinks: Article not found "' .
				$this->title->getPrefixedDBkey() . '"';
			wfProfileOut( __METHOD__ );
			return false; // XXX: what if it was just deleted?
		}

		self::runForTitleInternal( $this->title, $revision, __METHOD__ );

		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * @return Array
	 */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// Don't let highly unique "masterPos" values ruin duplicate detection
		if ( is_array( $info['params'] ) ) {
			unset( $info['params']['masterPos'] );
		}
		return $info;
	}

	/**
	 * @param $title Title
	 * @param $revision Revision
	 * @param $fname string
	 * @return void
	 */
	public static function runForTitleInternal( Title $title, Revision $revision, $fname ) {
		wfProfileIn( $fname );
		$content = $revision->getContent( Revision::RAW );

		if ( !$content ) {
			// if there is no content, pretend the content is empty
			$content = $revision->getContentHandler()->makeEmptyContent();
		}

		// Revision ID must be passed to the parser output to get revision variables correct
		$parserOutput = $content->getParserOutput( $title, $revision->getId(), null, false );

		$updates = $content->getSecondaryDataUpdates( $title, null, false, $parserOutput );
		DataUpdate::runUpdates( $updates );
		wfProfileOut( $fname );
	}
}

/**
 * Background job to update links for a given title.
 * Newer version for high use templates.
 *
 * @ingroup JobQueue
 */
class RefreshLinksJob2 extends Job {
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'refreshLinks2', $title, $params, $id );
	}

	/**
	 * Run a refreshLinks2 job
	 * @return boolean success
	 */
	function run() {
		global $wgUpdateRowsPerJob;

		wfProfileIn( __METHOD__ );

		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		if ( is_null( $this->title ) ) {
			$this->error = "refreshLinks2: Invalid title";
			wfProfileOut( __METHOD__ );
			return false;
		}

		// Back compat for pre-r94435 jobs
		$table = isset( $this->params['table'] ) ? $this->params['table'] : 'templatelinks';

		// Avoid slave lag when fetching templates.
		// When the outermost job is run, we know that the caller that enqueued it must have
		// committed the relevant changes to the DB by now. At that point, record the master
		// position and pass it along as the job recursively breaks into smaller range jobs.
		// Hopefully, when leaf jobs are popped, the slaves will have reached that position.
		if ( isset( $this->params['masterPos'] ) ) {
			$masterPos = $this->params['masterPos'];
		} elseif ( wfGetLB()->getServerCount() > 1  ) {
			$masterPos = wfGetLB()->getMasterPos();
		} else {
			$masterPos = false;
		}

		$tbc = $this->title->getBacklinkCache();

		$jobs = array(); // jobs to insert
		if ( isset( $this->params['start'] ) && isset( $this->params['end'] ) ) {
			# This is a partition job to trigger the insertion of leaf jobs...
			$jobs = array_merge( $jobs, $this->getSingleTitleJobs( $table, $masterPos ) );
		} else {
			# This is a base job to trigger the insertion of partitioned jobs...
			if ( $tbc->getNumLinks( $table ) <= $wgUpdateRowsPerJob ) {
				# Just directly insert the single per-title jobs
				$jobs = array_merge( $jobs, $this->getSingleTitleJobs( $table, $masterPos ) );
			} else {
				# Insert the partition jobs to make per-title jobs
				foreach ( $tbc->partition( $table, $wgUpdateRowsPerJob ) as $batch ) {
					list( $start, $end ) = $batch;
					$jobs[] = new RefreshLinksJob2( $this->title,
						array(
							'table'            => $table,
							'start'            => $start,
							'end'              => $end,
							'masterPos'        => $masterPos,
						) + $this->getRootJobParams() // carry over information for de-duplication
					);
				}
			}
		}

		if ( count( $jobs ) ) {
			JobQueueGroup::singleton()->push( $jobs );
		}

		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * @param $table string
	 * @param $masterPos mixed
	 * @return Array
	 */
	protected function getSingleTitleJobs( $table, $masterPos ) {
		# The "start"/"end" fields are not set for the base jobs
		$start = isset( $this->params['start'] ) ? $this->params['start'] : false;
		$end = isset( $this->params['end'] ) ? $this->params['end'] : false;
		$titles = $this->title->getBacklinkCache()->getLinks( $table, $start, $end );
		# Convert into single page refresh links jobs.
		# This handles well when in sapi mode and is useful in any case for job
		# de-duplication. If many pages use template A, and that template itself
		# uses template B, then an edit to both will create many duplicate jobs.
		# Roughly speaking, for each page, one of the "RefreshLinksJob" jobs will
		# get run first, and when it does, it will remove the duplicates. Of course,
		# one page could have its job popped when the other page's job is still
		# buried within the logic of a refreshLinks2 job.
		$jobs = array();
		foreach ( $titles as $title ) {
			$jobs[] = new RefreshLinksJob( $title,
				array( 'masterPos' => $masterPos ) + $this->getRootJobParams()
			); // carry over information for de-duplication
		}
		return $jobs;
	}

	/**
	 * @return Array
	 */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// Don't let highly unique "masterPos" values ruin duplicate detection
		if ( is_array( $info['params'] ) ) {
			unset( $info['params']['masterPos'] );
		}
		return $info;
	}
}
