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
	/** @var JobQueueGroup[] */
	protected static $instances = [];

	/** @var ProcessCacheLRU */
	protected $cache;

	/** @var string Wiki ID */
	protected $wiki;
	/** @var string|bool Read only rationale (or false if r/w) */
	protected $readOnlyReason;

	/** @var array Map of (bucket => (queue => JobQueue, types => list of types) */
	protected $coalescedQueues;

	/** @var Job[] */
	protected $bufferedJobs = [];

	const TYPE_DEFAULT = 1; // integer; jobs popped by default
	const TYPE_ANY = 2; // integer; any job

	const USE_CACHE = 1; // integer; use process or persistent cache

	const PROC_CACHE_TTL = 15; // integer; seconds

	const CACHE_VERSION = 1; // integer; cache version

	/**
	 * @param string $wiki Wiki ID
	 * @param string|bool $readOnlyReason Read-only reason or false
	 */
	protected function __construct( $wiki, $readOnlyReason ) {
		$this->wiki = $wiki;
		$this->readOnlyReason = $readOnlyReason;
		$this->cache = new ProcessCacheLRU( 10 );
	}

	/**
	 * @param bool|string $wiki Wiki ID
	 * @return JobQueueGroup
	 */
	public static function singleton( $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( self::$instances[$wiki] ) ) {
			self::$instances[$wiki] = new self( $wiki, wfConfiguredReadOnlyReason() );
		}

		return self::$instances[$wiki];
	}

	/**
	 * Destroy the singleton instances
	 *
	 * @return void
	 */
	public static function destroySingletons() {
		self::$instances = [];
	}

	/**
	 * Get the job queue object for a given queue type
	 *
	 * @param string $type
	 * @return JobQueue
	 */
	public function get( $type ) {
		global $wgJobTypeConf;

		$conf = [ 'wiki' => $this->wiki, 'type' => $type ];
		if ( isset( $wgJobTypeConf[$type] ) ) {
			$conf = $conf + $wgJobTypeConf[$type];
		} else {
			$conf = $conf + $wgJobTypeConf['default'];
		}
		$conf['aggregator'] = JobQueueAggregator::singleton();
		if ( $this->readOnlyReason !== false ) {
			$conf['readOnlyReason'] = $this->readOnlyReason;
		}

		return JobQueue::factory( $conf );
	}

	/**
	 * Insert jobs into the respective queues of which they belong
	 *
	 * This inserts the jobs into the queue specified by $wgJobTypeConf
	 * and updates the aggregate job queue information cache as needed.
	 *
	 * @param IJobSpecification|IJobSpecification[] $jobs A single Job or a list of Jobs
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function push( $jobs ) {
		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];
		if ( !count( $jobs ) ) {
			return;
		}

		$this->assertValidJobs( $jobs );

		$jobsByType = []; // (job type => list of jobs)
		foreach ( $jobs as $job ) {
			$jobsByType[$job->getType()][] = $job;
		}

		foreach ( $jobsByType as $type => $jobs ) {
			$this->get( $type )->push( $jobs );
		}

		if ( $this->cache->has( 'queues-ready', 'list' ) ) {
			$list = $this->cache->get( 'queues-ready', 'list' );
			if ( count( array_diff( array_keys( $jobsByType ), $list ) ) ) {
				$this->cache->clear( 'queues-ready' );
			}
		}
	}

	/**
	 * Buffer jobs for insertion via push() or call it now if in CLI mode
	 *
	 * Note that MediaWiki::restInPeace() calls pushLazyJobs()
	 *
	 * @param IJobSpecification|IJobSpecification[] $jobs A single Job or a list of Jobs
	 * @return void
	 * @since 1.26
	 */
	public function lazyPush( $jobs ) {
		if ( PHP_SAPI === 'cli' ) {
			$this->push( $jobs );
			return;
		}

		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];

		// Throw errors now instead of on push(), when other jobs may be buffered
		$this->assertValidJobs( $jobs );

		$this->bufferedJobs = array_merge( $this->bufferedJobs, $jobs );
	}

	/**
	 * Push all jobs buffered via lazyPush() into their respective queues
	 *
	 * @return void
	 * @since 1.26
	 */
	public static function pushLazyJobs() {
		foreach ( self::$instances as $group ) {
			try {
				$group->push( $group->bufferedJobs );
				$group->bufferedJobs = [];
			} catch ( Exception $e ) {
				// Get in as many jobs as possible and let other post-send updates happen
				MWExceptionHandler::logException( $e );
			}
		}
	}

	/**
	 * Pop a job off one of the job queues
	 *
	 * This pops a job off a queue as specified by $wgJobTypeConf and
	 * updates the aggregate job queue information cache as needed.
	 *
	 * @param int|string $qtype JobQueueGroup::TYPE_* constant or job type string
	 * @param int $flags Bitfield of JobQueueGroup::USE_* constants
	 * @param array $blacklist List of job types to ignore
	 * @return Job|bool Returns false on failure
	 */
	public function pop( $qtype = self::TYPE_DEFAULT, $flags = 0, array $blacklist = [] ) {
		$job = false;

		if ( is_string( $qtype ) ) { // specific job type
			if ( !in_array( $qtype, $blacklist ) ) {
				$job = $this->get( $qtype )->pop();
			}
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

			$types = array_diff( $types, $blacklist ); // avoid selected types
			shuffle( $types ); // avoid starvation

			foreach ( $types as $type ) { // for each queue...
				$job = $this->get( $type )->pop();
				if ( $job ) { // found
					break;
				} else { // not found
					$this->cache->clear( 'queues-ready' );
				}
			}
		}

		return $job;
	}

	/**
	 * Acknowledge that a job was completed
	 *
	 * @param Job $job
	 * @return void
	 */
	public function ack( Job $job ) {
		$this->get( $job->getType() )->ack( $job );
	}

	/**
	 * Register the "root job" of a given job into the queue for de-duplication.
	 * This should only be called right *after* all the new jobs have been inserted.
	 *
	 * @param Job $job
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
	 */
	public function waitForBackups() {
		global $wgJobTypeConf;

		// Try to avoid doing this more than once per queue storage medium
		foreach ( $wgJobTypeConf as $type => $conf ) {
			$this->get( $type )->waitForBackups();
		}
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
	 * Check if there are any queues with jobs (this is cached)
	 *
	 * @param int $type JobQueueGroup::TYPE_* constant
	 * @return bool
	 * @since 1.23
	 */
	public function queuesHaveJobs( $type = self::TYPE_ANY ) {
		$key = wfMemcKey( 'jobqueue', 'queueshavejobs', $type );
		$cache = ObjectCache::getLocalClusterInstance();

		$value = $cache->get( $key );
		if ( $value === false ) {
			$queues = $this->getQueuesWithJobs();
			if ( $type == self::TYPE_DEFAULT ) {
				$queues = array_intersect( $queues, $this->getDefaultQueueTypes() );
			}
			$value = count( $queues ) ? 'true' : 'false';
			$cache->add( $key, $value, 15 );
		}

		return ( $value === 'true' );
	}

	/**
	 * Get the list of job types that have non-empty queues
	 *
	 * @return array List of job types that have non-empty queues
	 */
	public function getQueuesWithJobs() {
		$types = [];
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
	 * @return array Map of (job type => size)
	 */
	public function getQueueSizes() {
		$sizeMap = [];
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
			$this->coalescedQueues = [];
			foreach ( $wgJobTypeConf as $type => $conf ) {
				$queue = JobQueue::factory(
					[ 'wiki' => $this->wiki, 'type' => 'null' ] + $conf );
				$loc = $queue->getCoalesceLocationInternal();
				if ( !isset( $this->coalescedQueues[$loc] ) ) {
					$this->coalescedQueues[$loc]['queue'] = $queue;
					$this->coalescedQueues[$loc]['types'] = [];
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
	 * @param string $name
	 * @return mixed
	 */
	private function getCachedConfigVar( $name ) {
		// @TODO: cleanup this whole method with a proper config system
		if ( $this->wiki === wfWikiID() ) {
			return $GLOBALS[$name]; // common case
		} else {
			$wiki = $this->wiki;
			$cache = ObjectCache::getMainWANInstance();
			$value = $cache->getWithSetCallback(
				$cache->makeGlobalKey( 'jobqueue', 'configvalue', $wiki, $name ),
				$cache::TTL_DAY + mt_rand( 0, $cache::TTL_DAY ),
				function () use ( $wiki, $name ) {
					global $wgConf;

					return [ 'v' => $wgConf->getConfig( $wiki, $name ) ];
				},
				[ 'pcTTL' => 30 ]
			);

			return $value['v'];
		}
	}

	/**
	 * @param array $jobs
	 * @throws InvalidArgumentException
	 */
	private function assertValidJobs( array $jobs ) {
		foreach ( $jobs as $job ) { // sanity checks
			if ( !( $job instanceof IJobSpecification ) ) {
				throw new InvalidArgumentException( "Expected IJobSpecification objects" );
			}
		}
	}

	function __destruct() {
		$n = count( $this->bufferedJobs );
		if ( $n > 0 ) {
			$type = implode( ', ', array_unique( array_map( 'get_class', $this->bufferedJobs ) ) );
			trigger_error( __METHOD__ . ": $n buffered job(s) of type(s) $type never inserted." );
		}
	}
}
