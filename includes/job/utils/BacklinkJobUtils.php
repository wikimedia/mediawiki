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
 * @author Aaron Schulz
 */

/**
 * Class with Backlink related Job helper methods
 * @since 1.23
 */
class BacklinkJobUtils {
	const LEVEL_LEAF    = 1;
	const LEVEL_NONLEAF = 2;

	/**
	 * Break down $job into no more than $bFactor sub-jobs, making the ranges of each
	 * sub-job as large as needed. These jobs will be of the $childClass class. When
	 * called recursively on the resulting jobs, this yeilds sub-jobs of smaller ranges.
	 *
	 * Once a job no longer covers $bSize or more backlinks, this will return leaf jobs
	 * of the $leafClass class. The leaf jobs will each be for approximately $cSize pages.
	 * Each such job will have the 'pages' param set to a (<page ID>:(namespace,DB key),...)
	 * map so that the run() function knows what pages to act on. If $cSize is 1, then the
	 * leaf job titles will be that of the one backlink title each job is for; otherwise,
	 * the job title will remain that of the parent job (e.g. that of $job).
	 *
	 * The job provided must have the 'table' parameter set. The job title will be used
	 * as the title to find backlinks for. Any 'range' parameter must follow the format
	 * of (start:int, end:int, batchSize:int, subranges:((start,end),...)). This is taken
	 * care of already for sub-jobs created by this method.
	 *
	 * $opts includes:
	 *   - params : extra job parameters to include in each job
	 *
	 * @param Job $job
	 * @param string $childClass Job class name
	 * @param string $leafClass Job class name
	 * @param int $bFactor Job branching factor; usually $wgUpdateRowsPerJob
	 * @param int $bSize BacklinkCache partition size; usually $wgUpdateRowsPerJob
	 * @param int $cSize Titles per job; Usually 1 or a modest value
	 * @param array $opts Optional parameters
	 * @return array (BacklinkJobUtils::LEVEL_* constant, list of Job objects)
	 */
	public static function partitionBacklinkJob(
		Job $job, $childClass, $leafClass, $bFactor, $bSize, $cSize, $opts = array()
	) {
		$params = $job->getParams();

		if ( isset( $params['pages'] ) ) {
			$ranges = array(); // sanity; this is a leaf node
			wfWarn( __METHOD__ . " called on {$job->getType()} leaf job (explosive recursion)." );
		} elseif ( isset( $params['range'] ) ) {
			// This is a range job to trigger the insertion of partitioned/title jobs...
			$ranges = self::getBranchRangesFromFlatRanges(
				$params['range']['subranges'], $bFactor, $params['range']['batchSize'] );
		} else {
			# This is a base job to trigger the insertion of partitioned jobs...
			$tbc = $job->getTitle()->getBacklinkCache();
			$flatRanges = $tbc->partition( $params['table'], $bSize );
			$ranges = self::getBranchRangesFromFlatRanges( $flatRanges, $bFactor, $bSize );
		}

		$extraParams = isset( $opts['params'] ) ? $opts['params'] : array();

		$jobs = array();
		if ( count( $ranges ) == 0 ) {
			// No backlinks in range
			$level = self::LEVEL_LEAF;
		} elseif ( count( $ranges ) == 1 ) {
			// Less than (or equal to) $bSize backlinks in range
			$level = self::LEVEL_LEAF;
			$titles = $job->getTitle()->getBacklinkCache()->getLinks(
				$params['table'],
				// The "start"/"end" fields are not set for the base jobs
				isset( $params['range']['start'] ) ? $params['range']['start'] : false,
				isset( $params['range']['end'] ) ? $params['range']['end'] : false
			);
			foreach ( array_chunk( iterator_to_array( $titles ), $cSize ) as $titleBatch ) {
				$pages = array();
				foreach ( $titleBatch as $tl ) {
					$pages[$tl->getArticleId()] = array( $tl->getNamespace(), $tl->getDBKey() );
				}
				$jobs[] = new $leafClass(
					( $cSize > 1 ) ? $job->getTitle() : $titleBatch[0], // job title
					array( 'pages' => $pages ) + $extraParams
				);
			}
		} else {
			// More than $bSize backlinks in range
			$level = self::LEVEL_NONLEAF;
			foreach ( $ranges as $range ) {
				$jobs[] = new $childClass(
					$job->getTitle(),
					array(
						'table' => $params['table'],
						'range' => $range,
					) + $extraParams
				);
			}
		}

		return array( $level, $jobs );
	}

	/**
	 * Break up an ordered list of flat ranges into a smaller ordered list of ranges.
	 * Each range has a list of ordered sub-ranges from the flat ranges that it covers.
	 * The size of these ranges is determined by $bfactor, which is maximum number of
	 * ranges that this method returns. Therefore, each of the ranges include as many
	 * sub-ranges as needed for this to be the case. All but that last should have the
	 * same number sub-ranges (the last range having any leftover sub-ranges).
	 *
	 * @param array $ranges List of (start,end) flat ranges, e.g. from BackLinkCache::partition()
	 * @param int $bfactor Branching factor to use (number of ranges returned)
	 * @param int $bsize Approximate number of entries per flat range
	 * @return array List of (start:int, end:int, batchSize:int, subranges:((start,end),...))
	 */
	public static function getBranchRangesFromFlatRanges( array $ranges, $bfactor, $bsize ) {
		$branchRanges = array();
		$subrangesPerRange = max( 1, ceil( count( $ranges ) / $bfactor ) );
		foreach ( array_chunk( $ranges, $subrangesPerRange ) as $rangeChunk ) {
			$firstSubrange = reset( $rangeChunk );
			$lastSubrange = end( $rangeChunk );
			$branchRanges[] = array(
				'start'     => $firstSubrange[0],
				'end'       => $lastSubrange[1],
				'subranges' => $rangeChunk,
				'batchSize' => $bsize,
			);
		}
		return $branchRanges;
	}
}
