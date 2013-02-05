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
 * @since 1.21
 */
class JobQueueGroup {
	/** @var Array */
	protected static $instances = array();

	/** @var ProcessCacheLRU */
	protected $cache;

	protected $wiki; // string; wiki ID

	const TYPE_DEFAULT = 1; // integer; jobs popped by default
	const TYPE_ANY     = 2; // integer; any job

	const USE_CACHE = 1; // integer; use process or persistent cache

	const PROC_CACHE_TTL = 15; // integer; seconds

	const CACHE_VERSION = 1; // integer; cache version

	/**
	 * @param $wiki string Wiki ID
	 */
	protected function __construct( $wiki ) {
		$this->wiki = $wiki;
		$this->cache = new ProcessCacheLRU( 10 );
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
	 * Destroy the singleton instances
	 *
	 * @return void
	 */
	public static function destroySingletons() {
		self::$instances = array();
	}

	/**
	 * @param $type string
	 * @return JobQueue Job queue object for a given queue type
	 */
	public function get( $type ) {
		global $wgJobTypeConf;

		$conf = array( 'wiki' => $this->wiki, 'type' => $type );
		if ( isset( $wgJobTypeConf[$type] ) ) {
			$conf = $conf + $wgJobTypeConf[$type];
		} else {
			$conf = $conf + $wgJobTypeConf['default'];
		}

		return JobQueue::factory( $conf );
	}

	/**
	 * Insert jobs into the respective queues of with the belong.
	 * This inserts the jobs into the queue specified by $wgJobTypeConf.
	 *
	 * @param $jobs Job|array A single Job or a list of Jobs
	 * @throws MWException
	 * @return bool
	 */
	public function push( $jobs ) {
		$jobs = is_array( $jobs ) ? $jobs : array( $jobs );

		$jobsByType = array(); // (job type => list of jobs)
		foreach ( $jobs as $job ) {
			if ( $job instanceof Job ) {
				$jobsByType[$job->getType()][] = $job;
			} else {
				throw new MWException( "Attempted to push a non-Job object into a queue." );
			}
		}

		$ok = true;
		foreach ( $jobsByType as $type => $jobs ) {
			if ( $this->get( $type )->push( $jobs ) ) {
				JobQueueAggregator::singleton()->notifyQueueNonEmpty( $this->wiki, $type );
			} else {
				$ok = false;
			}
		}

		if ( $this->cache->has( 'queues-ready', 'list' ) ) {
			$list = $this->cache->get( 'queues-ready', 'list' );
			if ( count( array_diff( array_keys( $jobsByType ), $list ) ) ) {
				$this->cache->clear( 'queues-ready' );
			}
		}

		return $ok;
	}

	/**
	 * Pop a job off one of the job queues
	 *
	 * @param $qtype integer|string JobQueueGroup::TYPE_DEFAULT or type string
	 * @param $flags integer Bitfield of JobQueueGroup::USE_* constants
	 * @return Job|bool Returns false on failure
	 */
	public function pop( $qtype = self::TYPE_DEFAULT, $flags = 0 ) {
		if ( is_string( $qtype ) ) { // specific job type
			$job = $this->get( $qtype )->pop();
			if ( !$job ) {
				JobQueueAggregator::singleton()->notifyQueueEmpty( $this->wiki, $qtype );
			}
			return $job;
		} else { // any job in the "default" jobs types
			if ( $flags & self::USE_CACHE ) {
				if ( !$this->cache->has( 'queues-ready', 'list', self::PROC_CACHE_TTL ) ) {
					$this->cache->set( 'queues-ready', 'list', $this->getQueuesWithJobs() );
				}
				$types = $this->cache->get( 'queues-ready', 'list' );
			} else {
				$types = $this->getQueuesWithJobs();
			}

			if ( $qtype == self::TYPE_DEFAULT ) {
				$types = array_intersect( $types, $this->getDefaultQueueTypes() );
			}
			shuffle( $types ); // avoid starvation

			foreach ( $types as $type ) { // for each queue...
				$job = $this->get( $type )->pop();
				if ( $job ) { // found
					return $job;
				} else { // not found
					JobQueueAggregator::singleton()->notifyQueueEmpty( $this->wiki, $type );
					$this->cache->clear( 'queues-ready' );
				}
			}

			return false; // no jobs found
		}
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
	 * Register the "root job" of a given job into the queue for de-duplication.
	 * This should only be called right *after* all the new jobs have been inserted.
	 *
	 * @param $job Job
	 * @return bool
	 */
	public function deduplicateRootJob( Job $job ) {
		return $this->get( $job->getType() )->deduplicateRootJob( $job );
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

	/**
	 * Get the list of job types that have non-empty queues
	 *
	 * @return Array List of job types that have non-empty queues
	 */
	public function getQueuesWithJobs() {
		$types = array();
		foreach ( $this->getQueueTypes() as $type ) {
			if ( !$this->get( $type )->isEmpty() ) {
				$types[] = $type;
			}
		}
		return $types;
	}

	/**
	 * Execute any due periodic queue maintenance tasks for all queues.
	 *
	 * A task is "due" if the time ellapsed since the last run is greater than
	 * the defined run period. Concurrent calls to this function will cause tasks
	 * to be attempted twice, so they may need their own methods of mutual exclusion.
	 *
	 * @return integer Number of tasks run
	 */
	public function executeReadyPeriodicTasks() {
		global $wgMemc;

		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		$key = wfForeignMemcKey( $db, $prefix, 'jobqueuegroup', 'taskruns', 'v1' );
		$lastRuns = $wgMemc->get( $key ); // (queue => task => UNIX timestamp)

		$count = 0;
		$tasksRun = array(); // (queue => task => UNIX timestamp)
		foreach ( $this->getQueueTypes() as $type ) {
			$queue = $this->get( $type );
			foreach ( $queue->getPeriodicTasks() as $task => $definition ) {
				if ( $definition['period'] <= 0 ) {
					continue; // disabled
				} elseif ( !isset( $lastRuns[$type][$task] )
					|| $lastRuns[$type][$task] < ( time() - $definition['period'] ) )
				{
					if ( call_user_func( $definition['callback'] ) !== null ) {
						$tasksRun[$type][$task] = time();
						++$count;
					}
				}
			}
		}

		$wgMemc->merge( $key, function( $cache, $key, $lastRuns ) use ( $tasksRun ) {
			if ( is_array( $lastRuns ) ) {
				foreach ( $tasksRun as $type => $tasks ) {
					foreach ( $tasks as $task => $timestamp ) {
						if ( !isset( $lastRuns[$type][$task] )
							|| $timestamp > $lastRuns[$type][$task] )
						{
							$lastRuns[$type][$task] = $timestamp;
						}
					}
				}
			} else {
				$lastRuns = $tasksRun;
			}
			return $lastRuns;
		} );

		return $count;
	}
}
