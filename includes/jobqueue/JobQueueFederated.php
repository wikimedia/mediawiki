<?php
/**
 * Job queue code for federated queues.
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
 * Class to handle enqueueing and running of background jobs for federated queues
 *
 * This class allows for queues to be partitioned into smaller queues.
 * A partition is defined by the configuration for a JobQueue instance.
 * For example, one can set $wgJobTypeConf['refreshLinks'] to point to a
 * JobQueueFederated instance, which itself would consist of three JobQueueRedis
 * instances, each using their own redis server. This would allow for the jobs
 * to be split (evenly or based on weights) accross multiple servers if a single
 * server becomes impractical or expensive. Different JobQueue classes can be mixed.
 *
 * The basic queue configuration (e.g. "order", "claimTTL") of a federated queue
 * is inherited by the partition queues. Additional configuration defines what
 * section each wiki is in, what partition queues each section uses (and their weight),
 * and the JobQueue configuration for each partition. Some sections might only need a
 * single queue partition, like the sections for groups of small wikis.
 *
 * If used for performance, then $wgMainCacheType should be set to memcached/redis.
 * Note that "fifo" cannot be used for the ordering, since the data is distributed.
 * One can still use "timestamp" instead, as in "roughly timestamp ordered". Also,
 * queue classes used by this should ignore down servers (with TTL) to avoid slowness.
 *
 * @ingroup JobQueue
 * @since 1.22
 */
class JobQueueFederated extends JobQueue {
	/** @var array (partition name => weight) reverse sorted by weight */
	protected $partitionMap = array();

	/** @var array (partition name => JobQueue) reverse sorted by weight */
	protected $partitionQueues = array();

	/** @var HashRing */
	protected $partitionPushRing;

	/** @var BagOStuff */
	protected $cache;

	/** @var int Maximum number of partitions to try */
	protected $maxPartitionsTry;

	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const CACHE_TTL_LONG = 300; // integer; seconds to cache info that is kept up to date

	/**
	 * @params include:
	 *  - sectionsByWiki      : A map of wiki IDs to section names.
	 *                          Wikis will default to using the section "default".
	 *  - partitionsBySection : Map of section names to maps of (partition name => weight).
	 *                          A section called 'default' must be defined if not all wikis
	 *                          have explicitly defined sections.
	 *  - configByPartition   : Map of queue partition names to configuration arrays.
	 *                          These configuration arrays are passed to JobQueue::factory().
	 *                          The options set here are overriden by those passed to this
	 *                          the federated queue itself (e.g. 'order' and 'claimTTL').
	 *  - partitionsNoPush    : List of partition names that can handle pop() but not push().
	 *                          This can be used to migrate away from a certain partition.
	 *  - maxPartitionsTry    : Maximum number of times to attempt job insertion using
	 *                          different partition queues. This improves availability
	 *                          during failure, at the cost of added latency and somewhat
	 *                          less reliable job de-duplication mechanisms.
	 * @param array $params
	 * @throws MWException
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$section = isset( $params['sectionsByWiki'][$this->wiki] )
			? $params['sectionsByWiki'][$this->wiki]
			: 'default';
		if ( !isset( $params['partitionsBySection'][$section] ) ) {
			throw new MWException( "No configuration for section '$section'." );
		}
		$this->maxPartitionsTry = isset( $params['maxPartitionsTry'] )
			? $params['maxPartitionsTry']
			: 2;
		// Get the full partition map
		$this->partitionMap = $params['partitionsBySection'][$section];
		arsort( $this->partitionMap, SORT_NUMERIC );
		// Get the partitions jobs can actually be pushed to
		$partitionPushMap = $this->partitionMap;
		if ( isset( $params['partitionsNoPush'] ) ) {
			foreach ( $params['partitionsNoPush'] as $partition ) {
				unset( $partitionPushMap[$partition] );
			}
		}
		// Get the config to pass to merge into each partition queue config
		$baseConfig = $params;
		foreach ( array( 'class', 'sectionsByWiki', 'maxPartitionsTry',
			'partitionsBySection', 'configByPartition', 'partitionsNoPush' ) as $o
		) {
			unset( $baseConfig[$o] ); // partition queue doesn't care about this
		}
		// Get the partition queue objects
		foreach ( $this->partitionMap as $partition => $w ) {
			if ( !isset( $params['configByPartition'][$partition] ) ) {
				throw new MWException( "No configuration for partition '$partition'." );
			}
			$this->partitionQueues[$partition] = JobQueue::factory(
				$baseConfig + $params['configByPartition'][$partition] );
		}
		// Get the ring of partitions to push jobs into
		$this->partitionPushRing = new HashRing( $partitionPushMap );
		// Aggregate cache some per-queue values if there are multiple partition queues
		$this->cache = count( $this->partitionMap ) > 1 ? wfGetMainCache() : new EmptyBagOStuff();
	}

	protected function supportedOrders() {
		// No FIFO due to partitioning, though "rough timestamp order" is supported
		return array( 'undefined', 'random', 'timestamp' );
	}

	protected function optimalOrder() {
		return 'undefined'; // defer to the partitions
	}

	protected function supportsDelayedJobs() {
		return true; // defer checks to the partitions
	}

	protected function doIsEmpty() {
		$key = $this->getCacheKey( 'empty' );

		$isEmpty = $this->cache->get( $key );
		if ( $isEmpty === 'true' ) {
			return true;
		} elseif ( $isEmpty === 'false' ) {
			return false;
		}

		$empty = true;
		$failed = 0;
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$empty = $empty && $queue->doIsEmpty();
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		$this->cache->add( $key, $empty ? 'true' : 'false', self::CACHE_TTL_LONG );
		return $empty;
	}

	protected function doGetSize() {
		return $this->getCrossPartitionSum( 'size', 'doGetSize' );
	}

	protected function doGetAcquiredCount() {
		return $this->getCrossPartitionSum( 'acquiredcount', 'doGetAcquiredCount' );
	}

	protected function doGetDelayedCount() {
		return $this->getCrossPartitionSum( 'delayedcount', 'doGetDelayedCount' );
	}

	protected function doGetAbandonedCount() {
		return $this->getCrossPartitionSum( 'abandonedcount', 'doGetAbandonedCount' );
	}

	/**
	 * @param string $type
	 * @param string $method
	 * @return int
	 */
	protected function getCrossPartitionSum( $type, $method ) {
		$key = $this->getCacheKey( $type );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$failed = 0;
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$count += $queue->$method();
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	protected function doBatchPush( array $jobs, $flags ) {
		// Local ring variable that may be changed to point to a new ring on failure
		$partitionRing = $this->partitionPushRing;
		// Try to insert the jobs and update $partitionsTry on any failures.
		// Retry to insert any remaning jobs again, ignoring the bad partitions.
		$jobsLeft = $jobs;
		for ( $i = $this->maxPartitionsTry; $i > 0 && count( $jobsLeft ); --$i ) {
			$jobsLeft = $this->tryJobInsertions( $jobsLeft, $partitionRing, $flags );
		}
		if ( count( $jobsLeft ) ) {
			throw new JobQueueError(
				"Could not insert job(s), {$this->maxPartitionsTry} partitions tried." );
		}

		return true;
	}

	/**
	 * @param array $jobs
	 * @param HashRing $partitionRing
	 * @param int $flags
	 * @throws JobQueueError
	 * @return array List of Job object that could not be inserted
	 */
	protected function tryJobInsertions( array $jobs, HashRing &$partitionRing, $flags ) {
		$jobsLeft = array();

		// Because jobs are spread across partitions, per-job de-duplication needs
		// to use a consistent hash to avoid allowing duplicate jobs per partition.
		// When inserting a batch of de-duplicated jobs, QOS_ATOMIC is disregarded.
		$uJobsByPartition = array(); // (partition name => job list)
		/** @var Job $job */
		foreach ( $jobs as $key => $job ) {
			if ( $job->ignoreDuplicates() ) {
				$sha1 = sha1( serialize( $job->getDeduplicationInfo() ) );
				$uJobsByPartition[$partitionRing->getLocation( $sha1 )][] = $job;
				unset( $jobs[$key] );
			}
		}
		// Get the batches of jobs that are not de-duplicated
		if ( $flags & self::QOS_ATOMIC ) {
			$nuJobBatches = array( $jobs ); // all or nothing
		} else {
			// Split the jobs into batches and spread them out over servers if there
			// are many jobs. This helps keep the partitions even. Otherwise, send all
			// the jobs to a single partition queue to avoids the extra connections.
			$nuJobBatches = array_chunk( $jobs, 300 );
		}

		// Insert the de-duplicated jobs into the queues...
		foreach ( $uJobsByPartition as $partition => $jobBatch ) {
			/** @var JobQueue $queue */
			$queue = $this->partitionQueues[$partition];
			try {
				$ok = $queue->doBatchPush( $jobBatch, $flags | self::QOS_ATOMIC );
			} catch ( JobQueueError $e ) {
				$ok = false;
				MWExceptionHandler::logException( $e );
			}
			if ( $ok ) {
				$key = $this->getCacheKey( 'empty' );
				$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
			} else {
				$partitionRing = $partitionRing->newWithoutLocation( $partition ); // blacklist
				if ( !$partitionRing ) {
					throw new JobQueueError( "Could not insert job(s), no partitions available." );
				}
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			}
		}

		// Insert the jobs that are not de-duplicated into the queues...
		foreach ( $nuJobBatches as $jobBatch ) {
			$partition = ArrayUtils::pickRandom( $partitionRing->getLocationWeights() );
			$queue = $this->partitionQueues[$partition];
			try {
				$ok = $queue->doBatchPush( $jobBatch, $flags | self::QOS_ATOMIC );
			} catch ( JobQueueError $e ) {
				$ok = false;
				MWExceptionHandler::logException( $e );
			}
			if ( $ok ) {
				$key = $this->getCacheKey( 'empty' );
				$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
			} else {
				$partitionRing = $partitionRing->newWithoutLocation( $partition ); // blacklist
				if ( !$partitionRing ) {
					throw new JobQueueError( "Could not insert job(s), no partitions available." );
				}
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			}
		}

		return $jobsLeft;
	}

	protected function doPop() {
		$key = $this->getCacheKey( 'empty' );

		$isEmpty = $this->cache->get( $key );
		if ( $isEmpty === 'true' ) {
			return false;
		}

		$partitionsTry = $this->partitionMap; // (partition => weight)

		$failed = 0;
		while ( count( $partitionsTry ) ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			if ( $partition === false ) {
				break; // all partitions at 0 weight
			}

			/** @var JobQueue $queue */
			$queue = $this->partitionQueues[$partition];
			try {
				$job = $queue->pop();
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
				$job = false;
			}
			if ( $job ) {
				$job->metadata['QueuePartition'] = $partition;

				return $job;
			} else {
				unset( $partitionsTry[$partition] ); // blacklist partition
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		$this->cache->set( $key, 'true', JobQueueDB::CACHE_TTL_LONG );

		return false;
	}

	protected function doAck( Job $job ) {
		if ( !isset( $job->metadata['QueuePartition'] ) ) {
			throw new MWException( "The given job has no defined partition name." );
		}

		return $this->partitionQueues[$job->metadata['QueuePartition']]->ack( $job );
	}

	protected function doIsRootJobOldDuplicate( Job $job ) {
		$params = $job->getRootJobParams();
		$partitions = $this->partitionPushRing->getLocations( $params['rootJobSignature'], 2 );
		try {
			return $this->partitionQueues[$partitions[0]]->doIsRootJobOldDuplicate( $job );
		} catch ( JobQueueError $e ) {
			if ( isset( $partitions[1] ) ) { // check fallback partition
				return $this->partitionQueues[$partitions[1]]->doIsRootJobOldDuplicate( $job );
			}
		}

		return false;
	}

	protected function doDeduplicateRootJob( Job $job ) {
		$params = $job->getRootJobParams();
		$partitions = $this->partitionPushRing->getLocations( $params['rootJobSignature'], 2 );
		try {
			return $this->partitionQueues[$partitions[0]]->doDeduplicateRootJob( $job );
		} catch ( JobQueueError $e ) {
			if ( isset( $partitions[1] ) ) { // check fallback partition
				return $this->partitionQueues[$partitions[1]]->doDeduplicateRootJob( $job );
			}
		}

		return false;
	}

	protected function doDelete() {
		$failed = 0;
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$queue->doDelete();
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );
		return true;
	}

	protected function doWaitForBackups() {
		$failed = 0;
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$queue->waitForBackups();
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );
	}

	protected function doGetPeriodicTasks() {
		$tasks = array();
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $partition => $queue ) {
			foreach ( $queue->getPeriodicTasks() as $task => $def ) {
				$tasks["{$partition}:{$task}"] = $def;
			}
		}

		return $tasks;
	}

	protected function doFlushCaches() {
		static $types = array(
			'empty',
			'size',
			'acquiredcount',
			'delayedcount',
			'abandonedcount'
		);

		foreach ( $types as $type ) {
			$this->cache->delete( $this->getCacheKey( $type ) );
		}

		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$queue->doFlushCaches();
		}
	}

	public function getAllQueuedJobs() {
		$iterator = new AppendIterator();

		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllQueuedJobs() );
		}

		return $iterator;
	}

	public function getAllDelayedJobs() {
		$iterator = new AppendIterator();

		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllDelayedJobs() );
		}

		return $iterator;
	}

	public function getCoalesceLocationInternal() {
		return "JobQueueFederated:wiki:{$this->wiki}" .
			sha1( serialize( array_keys( $this->partitionMap ) ) );
	}

	protected function doGetSiblingQueuesWithJobs( array $types ) {
		$result = array();

		$failed = 0;
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$nonEmpty = $queue->doGetSiblingQueuesWithJobs( $types );
				if ( is_array( $nonEmpty ) ) {
					$result = array_unique( array_merge( $result, $nonEmpty ) );
				} else {
					return null; // not supported on all partitions; bail
				}
				if ( count( $result ) == count( $types ) ) {
					break; // short-circuit
				}
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return array_values( $result );
	}

	protected function doGetSiblingQueueSizes( array $types ) {
		$result = array();
		$failed = 0;
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$sizes = $queue->doGetSiblingQueueSizes( $types );
				if ( is_array( $sizes ) ) {
					foreach ( $sizes as $type => $size ) {
						$result[$type] = isset( $result[$type] ) ? $result[$type] + $size : $size;
					}
				} else {
					return null; // not supported on all partitions; bail
				}
			} catch ( JobQueueError $e ) {
				++$failed;
				MWExceptionHandler::logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return $result;
	}

	/**
	 * Throw an error if no partitions available
	 *
	 * @param int $down The number of up partitions down
	 * @return void
	 * @throws JobQueueError
	 */
	protected function throwErrorIfAllPartitionsDown( $down ) {
		if ( $down >= count( $this->partitionQueues ) ) {
			throw new JobQueueError( 'No queue partitions available.' );
		}
	}

	public function setTestingPrefix( $key ) {
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$queue->setTestingPrefix( $key );
		}
	}

	/**
	 * @param $property
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );

		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $property );
	}
}
