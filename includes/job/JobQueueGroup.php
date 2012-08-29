<?php
/**
 * Job queue base code.
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
 * @author Aaron Schulz
 */

/**
 * Class to handle enqueueing of background jobs
 *
 * @ingroup JobQueue
 * @since 1.20
 */
class JobQueueGroup {
	/** @var Array */
	protected static $instances = array();

	protected $wiki; // string; wiki ID

	const TYPE_DEFAULT = 1; // integer; job not in $wgJobTypesExcludedFromDefaultQueue
	const TYPE_ANY     = 2; // integer; any job

	/**
	 * @param $wiki string Wiki ID
	 */
	protected function __construct( $wiki ) {
		$this->wiki = $wiki;
	}

	/**
	 * @param $wiki string Wiki ID
	 * @return JobQueueGroup
	 */
	public static function singleton( $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( self::$instances[$wiki] ) ) {
			self::$instances[$wiki] = new self( $wiki );
		}
		return self::$instances[$wiki];
	}

	/**
	 * @param $type string
	 * @return JobQueue Job queue object for a given queue type
	 */
	public function get( $type ) {
		global $wgJobTypeConf;

		$conf = false;
		if ( isset( $wgJobTypeConf[$type] ) ) {
			$conf = $wgJobTypeConf[$type];
		} else {
			$conf = $wgJobTypeConf['default'];
		}

		return JobQueue::factory( array(
			'class' => $conf['class'],
			'wiki'  => $this->wiki,
			'type'  => $type,
		) );
	}

	/**
	 * Insert jobs into the respective queues of with the belong
	 *
	 * @param $jobs Job|array A single Job or a list of Jobs
	 * @return bool
	 */
	public function push( $jobs ) {
		$jobs = (array)$jobs;

		$jobsByType = array(); // (job type => list of jobs)
		foreach ( $jobs as $job ) {
			$jobsByType[$job->getType()][] = $job;
		}

		$ok = true;
		foreach ( $jobsByType as $type => $jobs ) {
			if ( !$this->get( $type )->batchPush( $jobs ) ) {
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * Pop a job off one of the job queues
	 *
	 * @param $type integer JobQueueGroup::TYPE_* constant
	 * @return Job|bool Returns false on failure
	 */
	public function pop( $type = self::TYPE_DEFAULT ) {
		$types = ( $type == self::TYPE_DEFAULT )
			? $this->getDefaultQueueTypes()
			: $this->getQueueTypes();
		shuffle( $types ); // avoid starvation

		foreach ( $types as $type ) { // for each queue...
			$job = $this->get( $type )->pop();
			if ( $job ) {
				return $job; // found
			}
		}

		return false; // no jobs found
	}

	/**
	 * Acknowledge that a job was completed
	 *
	 * @param $job Job
	 * @return bool
	 */
	public function ack( Job $job ) {
		return $this->get( $job->getType() )->ack( $job );
	}

	/**
	 * Get the list of queue types
	 *
	 * @return array List of strings
	 */
	public function getQueueTypes() {
		global $wgJobClasses;

		return array_keys( $wgJobClasses );
	}

	/**
	 * Get the list of default queue types
	 *
	 * @return array List of strings
	 */
	public function getDefaultQueueTypes() {
		global $wgJobTypesExcludedFromDefaultQueue;

		return array_diff( $this->getQueueTypes(), $wgJobTypesExcludedFromDefaultQueue );
	}
}
