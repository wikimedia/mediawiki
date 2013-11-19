<?php
/**
 * Job to update pages that link to a given title.
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
 * @author Aaron Schulz
 */

/**
 * Background job to update links for titles in by backlink range and page title.
 *
 * This is useful for high use templates with many backlinks, where jobs that make
 * jobs are required to avoid queue flood or timeouts on queue insertion. These jobs
 * start off for the full backlink range for a title and divide into jobs for smaller
 * ranges until they spawn refreshLinks jobs to do the real work.
 *
 * @ingroup JobQueue
 * @since 1.23
 */
class RefreshLinksPartitionJob extends Job {
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'refreshLinksPartition', $title, $params, $id );
		// Base jobs for large templates can easily be de-duplicated
		$this->removeDuplicates = !isset( $params['range'] );
	}

	function run() {
		global $wgUpdateRowsPerJob;

		if ( is_null( $this->title ) ) {
			$this->setLastError( "Invalid page title" );
			return false;
		}

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
		// Convert this into no more than $wgUpdateRowsPerJob RefreshLinksPartitionJob jobs,
		// making the ranges of each as large as needed. This will happen recursively until
		// jobs start covering ranges less than $wgUpdateRowsPerJob. Those jobs will then
		// broken down into per-title RefreshLinks jobs, which do the actual link updates.
		list( $level, $jobs ) = BacklinkJobUtils::partitionBacklinkJob(
			$this,
			get_class( $this ), // class for job to make jobs
			'RefreshLinksJob', // class for leaf (main work) jobs
			$wgUpdateRowsPerJob,
			$wgUpdateRowsPerJob,
			1, // job-per-title
			array( 'params' => $extraParams )
		);
		JobQueueGroup::singleton()->push( $jobs );

		return true;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// Don't let highly unique "masterPos" values ruin duplicate detection
		if ( is_array( $info['params'] ) ) {
			unset( $info['params']['masterPos'] );
		}
		return $info;
	}
}
