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
 * Class with Backlink related Job helper methods
 *
 * When an asset changes, a base job can be inserted to update all assets that depend on it.
 * The base job splits into per-title "leaf" jobs and a "remnant" job to handle the remaining
 * range of backlinks. This recurs until the remnant job's backlink range is small enough that
 * only leaf jobs are created from it.
 *
 * For example, if templates A and B are edited (at the same time) the queue will have:
 *     (A base, B base)
 * When these jobs run, the queue will have per-title and remnant partition jobs:
 *     (titleX,titleY,titleZ,...,A remnant,titleM,titleN,titleO,...,B remnant)
 *
 * This works best when the queue is FIFO, for several reasons:
 *   - a) Since the remnant jobs are enqueued after the leaf jobs, the slower leaf jobs have to
 *        get popped prior to the fast remnant jobs. This avoids flooding the queue with leaf jobs
 *        for every single backlink of widely used assets (which can be millions).
 *   - b) Other jobs going in the queue still get a chance to run after a widely used asset changes.
 *        This is due to the large remnant job pushing to the end of the queue with each division.
 *
 * The size of the queues used in this manner depend on the number of assets changes and the
 * number of workers. Also, with FIFO-per-partition queues, the queue size can be somewhat larger,
 * depending on the number of queue partitions.
 *
 * @ingroup JobQueue
 * @since 1.23
 */
class BacklinkJobUtils {
	/**
	 * Break down $job into approximately ($bSize/$cSize) leaf jobs and a single partition
	 * job that covers the remaining backlink range (if needed). Jobs for the first $bSize
	 * titles are collated ($cSize per job) into leaf jobs to do actual work. All the
	 * resulting jobs are of the same class as $job. No partition job is returned if the
	 * range covered by $job was less than $bSize, as the leaf jobs have full coverage.
	 *
	 * The leaf jobs have the 'pages' param set to a (<page ID>:(<namespace>,<DB key>),...)
	 * map so that the run() function knows what pages to act on. The leaf jobs will keep
	 * the same job title as the parent job (e.g. $job).
	 *
	 * The partition jobs have the 'range' parameter set to a map of the format
	 * (start:<integer>, end:<integer>, batchSize:<integer>, subranges:((<start>,<end>),...)),
	 * the 'table' parameter set to that of $job, and the 'recursive' parameter set to true.
	 * This method can be called on the resulting job to repeat the process again.
	 *
	 * The job provided ($job) must have the 'recursive' parameter set to true and the 'table'
	 * parameter must be set to a backlink table. The job title will be used as the title to
	 * find backlinks for. Any 'range' parameter must follow the same format as mentioned above.
	 * This should be managed by recursive calls to this method.
	 *
	 * The first jobs return are always the leaf jobs. This lets the caller use push() to
	 * put them directly into the queue and works well if the queue is FIFO. In such a queue,
	 * the leaf jobs have to get finished first before anything can resolve the next partition
	 * job, which keeps the queue very small.
	 *
	 * $opts includes:
	 *   - params : extra job parameters to include in each job
	 *
	 * @param Job $job
	 * @param int $bSize BacklinkCache partition size; usually $wgUpdateRowsPerJob
	 * @param int $cSize Max titles per leaf job; Usually 1 or a modest value
	 * @param array $opts Optional parameter map
	 * @return Job[] List of Job objects
	 */
	public static function partitionBacklinkJob( Job $job, $bSize, $cSize, $opts = [] ) {
		$class = get_class( $job );
		$title = $job->getTitle();
		$params = $job->getParams();

		if ( isset( $params['pages'] ) || empty( $params['recursive'] ) ) {
			$ranges = []; // sanity; this is a leaf node
			$realBSize = 0;
			wfWarn( __METHOD__ . " called on {$job->getType()} leaf job (explosive recursion)." );
		} elseif ( isset( $params['range'] ) ) {
			// This is a range job to trigger the insertion of partitioned/title jobs...
			$ranges = $params['range']['subranges'];
			$realBSize = $params['range']['batchSize'];
		} else {
			// This is a base job to trigger the insertion of partitioned jobs...
			$ranges = $title->getBacklinkCache()->partition( $params['table'], $bSize );
			$realBSize = $bSize;
		}

		$extraParams = isset( $opts['params'] ) ? $opts['params'] : [];

		$jobs = [];
		// Combine the first range (of size $bSize) backlinks into leaf jobs
		if ( isset( $ranges[0] ) ) {
			list( $start, $end ) = $ranges[0];
			$iter = $title->getBacklinkCache()->getLinks( $params['table'], $start, $end );
			$titles = iterator_to_array( $iter );
			/** @var Title[] $titleBatch */
			foreach ( array_chunk( $titles, $cSize ) as $titleBatch ) {
				$pages = [];
				foreach ( $titleBatch as $tl ) {
					$pages[$tl->getArticleID()] = [ $tl->getNamespace(), $tl->getDBkey() ];
				}
				$jobs[] = new $class(
					$title, // maintain parent job title
					[ 'pages' => $pages ] + $extraParams
				);
			}
		}
		// Take all of the remaining ranges and build a partition job from it
		if ( isset( $ranges[1] ) ) {
			$jobs[] = new $class(
				$title, // maintain parent job title
				[
					'recursive'     => true,
					'table'         => $params['table'],
					'range'         => [
						'start'     => $ranges[1][0],
						'end'       => $ranges[count( $ranges ) - 1][1],
						'batchSize' => $realBSize,
						'subranges' => array_slice( $ranges, 1 )
					],
					// Track how many times the base job divided for debugging
					'division'      => isset( $params['division'] )
						? ( $params['division'] + 1 )
						: 1
				] + $extraParams
			);
		}

		return $jobs;
	}
}
