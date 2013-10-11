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

	/** @var array Map of (bucket => (queue => JobQueue, types => list of types) */
	protected $coalescedQueues;

	const TYPE_DEFAULT = 1; // integer; jobs popped by default
	const TYPE_ANY = 2; // integer; any job

	const USE_CACHE = 1; // integer; use process or persistent cache
	const USE_PRIORITY = 2; // integer; respect deprioritization

	const PROC_CACHE_TTL = 15; // integer; seconds

	const CACHE_VERSION = 1; // integer; cache version

	/**
	 * @param string $wiki Wiki ID
	 */
	protected function __construct( $wiki ) {
		$this->wiki = $wiki;
		$this->cache = new ProcessCacheLRU( 10 );
	}

	/**
	 * @param string $wiki Wiki ID
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
	 * Get the job queue object for a given queue type
	 *
	 * @param $type string
	 * @return JobQueue
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
	 *
	 * This inserts the jobs into the queue specified by $wgJobTypeConf
	 * and updates the aggregate job queue information cache as needed.
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
	 * This pops a job off a queue as specified by $wgJobTypeConf and
	 * updates the aggregate job queue information cache as needed.
	 *
	 * @param $qtype integer|string JobQueueGroup::TYPE_DEFAULT or type string
	 * @param $flags integer Bitfield of JobQueueGroup::USE_* constants
	 * @return Job|bool Returns false on failure
	 */
	public function pop( $qtype = self::TYPE_DEFAULT, $flags = 0 ) {
		if ( is_string( $qtype ) ) { // specific job type
			if ( ( $flags & self::USE_PRIORITY ) && $this->isQueueDeprioritized( $qtype ) ) {
				return false; // back off
			}
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
				if ( ( $flags & self::USE_PRIORITY ) && $this->isQueueDeprioritized( $type ) ) {
					continue; // back off
				}
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
	 * Wait for any slaves or backup queue servers to catch up.
	 *
	 * This does nothing for certain queue classes.
	 *
	 * @return void
	 * @throws MWException
	 */
	public function waitForBackups() {
		global $wgJobTypeConf;

		wfProfileIn( __METHOD__ );
		// Try to avoid doing this more than once per queue storage medium
		foreach ( $wgJobTypeConf as $type => $conf ) {
			$this->get( $type )->waitForBackups();
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get the list of queue types
	 *
	 * @return array List of strings
	 */
	public function getQueueTypes() {
		return array_keys( $this->getCachedConfigVar( 'wgJobClasses' ) );
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
		foreach ( $this->getCoalescedQueues() as $info ) {
			$nonEmpty = $info['queue']->getSiblingQueuesWithJobs( $this->getQueueTypes() );
			if ( is_array( $nonEmpty ) ) { // batching features supported
				$types = array_merge( $types, $nonEmpty );
			} else { // we have to go through the queues in the bucket one-by-one
				foreach ( $info['types'] as $type ) {
					if ( !$this->get( $type )->isEmpty() ) {
						$types[] = $type;
					}
				}
			}
		}
		return $types;
	}

	/**
	 * Get the size of the queus for a list of job types
	 *
	 * @return Array Map of (job type => size)
	 */
	public function getQueueSizes() {
		$sizeMap = array();
		foreach ( $this->getCoalescedQueues() as $info ) {
			$sizes = $info['queue']->getSiblingQueueSizes( $this->getQueueTypes() );
			if ( is_array( $sizes ) ) { // batching features supported
				$sizeMap = $sizeMap + $sizes;
			} else { // we have to go through the queues in the bucket one-by-one
				foreach ( $info['types'] as $type ) {
					$sizeMap[$type] = $this->get( $type )->getSize();
				}
			}
		}
		return $sizeMap;
	}

	/**
	 * @return array
	 */
	protected function getCoalescedQueues() {
		global $wgJobTypeConf;

		if ( $this->coalescedQueues === null ) {
			$this->coalescedQueues = array();
			foreach ( $wgJobTypeConf as $type => $conf ) {
				$queue = JobQueue::factory(
					array( 'wiki' => $this->wiki, 'type' => 'null' ) + $conf );
				$loc = $queue->getCoalesceLocationInternal();
				if ( !isset( $this->coalescedQueues[$loc] ) ) {
					$this->coalescedQueues[$loc]['queue'] = $queue;
					$this->coalescedQueues[$loc]['types'] = array();
				}
				if ( $type === 'default' ) {
					$this->coalescedQueues[$loc]['types'] = array_merge(
						$this->coalescedQueues[$loc]['types'],
						array_diff( $this->getQueueTypes(), array_keys( $wgJobTypeConf ) )
					);
				} else {
					$this->coalescedQueues[$loc]['types'][] = $type;
				}
			}
		}

		return $this->coalescedQueues;
	}

	/**
	 * Check if jobs should not be popped of a queue right now.
	 * This is only used for performance, such as to avoid spamming
	 * the queue with many sub-jobs before they actually get run.
	 *
	 * @param $type string
	 * @return bool
	 */
	public function isQueueDeprioritized( $type ) {
		if ( $this->cache->has( 'isDeprioritized', $type, 5 ) ) {
			return $this->cache->get( 'isDeprioritized', $type );
		}
		if ( $type === 'refreshLinks2' ) {
			// Don't keep converting refreshLinks2 => refreshLinks jobs if the
			// later jobs have not been done yet. This helps throttle queue spam.
			$deprioritized = !$this->get( 'refreshLinks' )->isEmpty();
			$this->cache->set( 'isDeprioritized', $type, $deprioritized );
			return $deprioritized;
		}
		return false;
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
					try {
						if ( call_user_func( $definition['callback'] ) !== null ) {
							$tasksRun[$type][$task] = time();
							++$count;
						}
					} catch ( JobQueueError $e ) {
						MWExceptionHandler::logException( $e );
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

	/**
	 * @param $name string
	 * @return mixed
	 */
	private function getCachedConfigVar( $name ) {
		global $wgConf, $wgMemc;

		if ( $this->wiki === wfWikiID() ) {
			return $GLOBALS[$name]; // common case
		} else {
			list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
			$key = wfForeignMemcKey( $db, $prefix, 'configvalue', $name );
			$value = $wgMemc->get( $key ); // ('v' => ...) or false
			if ( is_array( $value ) ) {
				return $value['v'];
			} else {
				$value = $wgConf->getConfig( $this->wiki, $name );
				$wgMemc->set( $key, array( 'v' => $value ), 86400 + mt_rand( 0, 86400 ) );
				return $value;
			}
		}
	}
}
