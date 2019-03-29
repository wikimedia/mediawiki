<?php
/**
 * Router job that takes jobs and enqueues them.
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
 * Router job that takes jobs and enqueues them to their proper queues
 *
 * This can be used for getting sets of multiple jobs or sets of jobs intended for multiple
 * queues to be inserted more robustly. This is a single job that, upon running, enqueues the
 * wrapped jobs. If some of those fail to enqueue then the EnqueueJob will be retried. Due to
 * the possibility of duplicate enqueues, the wrapped jobs should be idempotent.
 *
 * @ingroup JobQueue
 * @since 1.25
 */
final class EnqueueJob extends Job {
	/**
	 * Callers should use the factory methods instead
	 *
	 * @param Title $title
	 * @param array $params Job parameters
	 */
	function __construct( Title $title, array $params ) {
		parent::__construct( 'enqueue', $title, $params );
	}

	/**
	 * @param JobSpecification|JobSpecification[] $jobs
	 * @return EnqueueJob
	 */
	public static function newFromLocalJobs( $jobs ) {
		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];

		return self::newFromJobsByDomain( [
			WikiMap::getCurrentWikiDbDomain()->getId() => $jobs
		] );
	}

	/**
	 * @param array $jobsByDomain Map of (wiki => JobSpecification list)
	 * @return EnqueueJob
	 */
	public static function newFromJobsByDomain( array $jobsByDomain ) {
		$deduplicate = true;

		$jobMapsByDomain = [];
		foreach ( $jobsByDomain as $domain => $jobs ) {
			$jobMapsByDomain[$domain] = [];
			foreach ( $jobs as $job ) {
				if ( $job instanceof JobSpecification ) {
					$jobMapsByDomain[$domain][] = $job->toSerializableArray();
				} else {
					throw new InvalidArgumentException( "Jobs must be of type JobSpecification." );
				}
				$deduplicate = $deduplicate && $job->ignoreDuplicates();
			}
		}

		$eJob = new self(
			Title::makeTitle( NS_SPECIAL, 'Badtitle/' . __CLASS__ ),
			[ 'jobsByDomain' => $jobMapsByDomain ]
		);
		// If *all* jobs to be pushed are to be de-duplicated (a common case), then
		// de-duplicate this whole job itself to avoid build up in high traffic cases
		$eJob->removeDuplicates = $deduplicate;

		return $eJob;
	}

	/**
	 * @param array $jobsByWiki
	 * @return EnqueueJob
	 * @deprecated Since 1.33; use newFromJobsByDomain()
	 */
	public static function newFromJobsByWiki( array $jobsByWiki ) {
		return self::newFromJobsByDomain( $jobsByWiki );
	}

	public function run() {
		$jobsByDomain = $this->params['jobsByDomain'] ?? $this->params['jobsByWiki']; // b/c

		foreach ( $jobsByDomain as $domain => $jobMaps ) {
			$jobSpecs = [];
			foreach ( $jobMaps as $jobMap ) {
				$jobSpecs[] = JobSpecification::newFromArray( $jobMap );
			}
			JobQueueGroup::singleton( $domain )->push( $jobSpecs );
		}

		return true;
	}
}
