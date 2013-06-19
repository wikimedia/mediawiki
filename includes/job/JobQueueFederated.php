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
 * One can still use "timestamp" instead, as in "roughly timestamp ordered".
 *
 * @ingroup JobQueue
 * @since 1.22
 */
class JobQueueFederated extends JobQueue {
	/** @var Array (partition name => weight) */
	protected $partitionMap = array();
	/** @var Array (partition name => weight) */
	protected $partitionPushMap = array();
	/** @var Array (partition name => JobQueue) */
	protected $partitionQueues = array();
	/** @var BagOStuff */
	protected $cache;

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
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$section = isset( $params['sectionsByWiki'][$this->wiki] )
			? $params['sectionsByWiki'][$this->wiki]
			: 'default';
		if ( !isset( $params['partitionsBySection'][$section] ) ) {
			throw new MWException( "No configuration for section '$section'." );
		}
		$this->partitionMap = $params['partitionsBySection'][$section];
		$this->partitionPushMap = $this->partitionMap;
		if ( isset( $params['partitionsNoPush'] ) ) {
			foreach ( $params['partitionsNoPush'] as $partition ) {
				unset( $this->partitionPushMap[$partition] );
			}
		}
		// Get the config to pass to merge into each partition queue config
		$baseConfig = $params;
		foreach ( array( 'class', 'sectionsByWiki',
			'partitionsBySection', 'configByPartition', 'partitionsNoPush' ) as $o )
		{
			unset( $baseConfig[$o] );
		}
		// Get the partition queue objects
		foreach ( $this->partitionMap as $partition => $w ) {
			if ( !isset( $params['configByPartition'][$partition] ) ) {
				throw new MWException( "No configuration for partition '$partition'." );
			}
			$this->partitionQueues[$partition] = JobQueue::factory(
				$baseConfig + $params['configByPartition'][$partition] );
		}
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

		foreach ( $this->partitionQueues as $queue ) {
			if ( !$queue->doIsEmpty() ) {
				$this->cache->add( $key, 'false', self::CACHE_TTL_LONG );
				return false;
			}
		}

		$this->cache->add( $key, 'true', self::CACHE_TTL_LONG );
		return true;
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
	 * @return integer
	 */
	protected function getCrossPartitionSum( $type, $method ) {
		$key = $this->getCacheKey( $type );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$count = 0;
		foreach ( $this->partitionQueues as $queue ) {
			$count += $queue->$method();
		}

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );
		return $count;
	}

	protected function doBatchPush( array $jobs, $flags ) {
		if ( !count( $jobs ) ) {
			return true; // nothing to do
		}

		$partitionsTry = $this->partitionPushMap;
		// Try to insert the jobs and update $partitionsTry on any failures
		$jobsLeft = $this->tryJobInsertions( $jobs, $partitionsTry, $flags );
		if ( count( $jobsLeft ) ) { // some jobs failed to insert?
			// Try to insert the remaning jobs once more, ignoring the bad partitions
			return !count( $this->tryJobInsertions( $jobsLeft, $partitionsTry, $flags ) );
		} else {
			return true;
		}
	}

	/**
	 * @param array $jobs
	 * @param array $partitionsTry
	 * @param integer $flags
	 * @return array List of Job object that could not be inserted
	 */
	protected function tryJobInsertions( array $jobs, array &$partitionsTry, $flags ) {
		if ( !count( $partitionsTry ) ) {
			return $jobs; // can't insert anything
		}

		$jobsLeft = array();

		$partitionRing = new HashRing( $partitionsTry );
		// Because jobs are spread across partitions, per-job de-duplication needs
		// to use a consistent hash to avoid allowing duplicate jobs per partition.
		// When inserting a batch of de-duplicated jobs, QOS_ATOMIC is disregarded.
		$uJobsByPartition = array(); // (partition name => job list)
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
			$queue = $this->partitionQueues[$partition];
			if ( $queue->doBatchPush( $jobBatch, $flags ) ) {
				$key = $this->getCacheKey( 'empty' );
				$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
			} else {
				unset( $partitionsTry[$partition] ); // blacklist partition
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			}
		}
		// Insert the jobs that are not de-duplicated into the queues...
		foreach ( $nuJobBatches as $jobBatch ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			if ( $partition === false ) { // all partitions at 0 weight?
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			} else {
				$queue = $this->partitionQueues[$partition];
				if ( $queue->doBatchPush( $jobBatch, $flags ) ) {
					$key = $this->getCacheKey( 'empty' );
					$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
				} else {
					unset( $partitionsTry[$partition] ); // blacklist partition
					$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
				}
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

		while ( count( $partitionsTry ) ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			if ( $partition === false ) {
				break; // all partitions at 0 weight
			}
			$queue = $this->partitionQueues[$partition];
			$job = $queue->pop();
			if ( $job ) {
				$job->metadata['QueuePartition'] = $partition;
				return $job;
			} else {
				unset( $partitionsTry[$partition] ); // blacklist partition
			}
		}

		$this->cache->set( $key, 'true', JobQueueDB::CACHE_TTL_LONG );
		return false;
	}

	protected function doAck( Job $job ) {
		if ( !isset( $job->metadata['QueuePartition'] ) ) {
			throw new MWException( "The given job has no defined partition name." );
		}
		return $this->partitionQueues[$job->metadata['QueuePartition']]->ack( $job );
	}

	protected function doDelete() {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->doDelete();
		}
	}

	protected function doWaitForBackups() {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->waitForBackups();
		}
	}

	protected function doGetPeriodicTasks() {
		$tasks = array();
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
		foreach ( $this->partitionQueues as $queue ) {
			$queue->doFlushCaches();
		}
	}

	public function getAllQueuedJobs() {
		$iterator = new AppendIterator();
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllQueuedJobs() );
		}
		return $iterator;
	}

	public function getAllDelayedJobs() {
		$iterator = new AppendIterator();
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllDelayedJobs() );
		}
		return $iterator;
	}

	public function setTestingPrefix( $key ) {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->setTestingPrefix( $key );
		}
	}

	/**
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $property );
	}
}
