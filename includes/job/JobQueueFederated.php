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
 * is inherited by the partition queues. Additional configuration defines the available
 * partition queues, their weight, and remaining JobQueue::factory() configuration.
 *
 * If used for performance, then $wgMainCacheType should be set to memcached/redis.
 * Note that "fifo" cannot be used for the ordering, since the data is distributed.
 * One can still use "timestamp" instead, as in "roughly timestamp ordered".
 *
 * @ingroup JobQueue
 * @since 1.22
 */
class JobQueueFederated extends JobQueue {
	/** @var Array (partition name => weight)) */
	protected $partitionWeights = array();
	/** @var Array (partition name => config array) */
	protected $configByPartition = array();
	/** @var Array (partition names => integer) */
	protected $partitionsNoPush = array();

	/** @var Array (partition name => JobQueue) */
	protected $partitionQueues = array();
	/** @var BagOStuff */
	protected $cache;

	protected $loadedWeights = 0; // integer; UNIX timestamp or 0

	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const CACHE_TTL_LONG = 300; // integer; seconds to cache info that is kept up to date

	/**
	 * @params include:
	 *  - partitionWeights  : Map of (partition name => weight).
	 *  - configByPartition : Map of queue partition names to configuration arrays.
	 *                        These configuration arrays are passed to JobQueue::factory().
	 *                        The options set here are overriden by those passed to this
	 *                        the federated queue itself (e.g. 'order' and 'claimTTL').
	 *  - partitionsNoPush  : List of partition names that can handle pop() but not push().
	 *                        This can be used to migrate away from a certain partition.
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->partitionWeights = $params['partitionWeights'];
		$this->configByPartition = $params['configByPartition'];
		if ( isset( $params['partitionsNoPush'] ) ) {
			$this->partitionsNoPush = array_flip( $params['partitionsNoPush'] );
		}
		$baseConfig = $params;
		foreach ( array( 'class', 'partitionWeights',
			'configByPartition', 'partitionsNoPush' ) as $o )
		{
			unset( $baseConfig[$o] );
		}
		foreach ( $this->partitionWeights as $partition => $w ) {
			if ( !isset( $this->configByPartition[$partition] ) ) {
				throw new MWException( "No configuration for partition '$partition'." );
			}
			$this->partitionQueues[$partition] = JobQueue::factory(
				$baseConfig + $this->configByPartition[$partition]
			);
		}
		// Aggregate cache some per-queue values if there are multiple partition queues
		if ( count( $this->partitionQueues ) > 1 ) {
			$this->cache = wfGetMainCache();
		} else {
			$this->cache = new EmptyBagOStuff();
		}
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

		list( $pPartitions, $npPartitions ) = $this->getPartitionQueues();
		foreach ( $pPartitions as $queue ) { // pushable partitions
			if ( !$queue->doIsEmpty() ) {
				$this->cache->add( $key, 'false', self::CACHE_TTL_LONG );
				return false;
			}
		}
		if ( count( $npPartitions ) ) {
			$npKey = $this->getCacheKey( 'nopush-size' );
			if ( $this->cache->get( $npKey ) !== 0 ) { // non-pushable partitions
				foreach ( $npPartitions as $queue ) {
					if ( !$queue->doIsEmpty() ) {
						$this->cache->add( $key, 'false', self::CACHE_TTL_LONG );
						return false;
					}
				}
				$this->cache->set( $npKey, 0, mt_rand( 43200, 86400 ) );
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
		if ( is_integer( $count ) ) {
			return $count;
		}

		$count = 0;
		list( $pPartitions, $npPartitions ) = $this->getPartitionQueues();
		foreach ( $pPartitions as $queue ) { // pushable partitions
			$count += $queue->$method();
		}
		if ( count( $npPartitions ) ) {
			$npKey = $this->getCacheKey( "nopush-{$type}" );
			if ( $this->cache->get( $npKey ) !== 0 ) { // non-pushable partitions
				$npCount = 0;
				foreach ( $npPartitions as $queue ) {
					$npCount += $queue->$method();
				}
				$this->cache->set( $npKey, $npCount, mt_rand( 43200, 86400 ) );
				$count += $npCount;
			}
		}

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );
		return $count;
	}

	protected function doBatchPush( array $jobs, $flags ) {
		if ( !count( $jobs ) ) {
			return true; // nothing to do
		}

		$partitionsTry = array_diff_key(
			$this->getPartitionMap(),
			$this->partitionsNoPush
		); // (partition => weight)

		// Try to insert the jobs and update $partitionsTry on any failures
		$jobsLeft = $this->tryJobInsertions( $jobs, $partitionsTry, $flags );
		if ( count( $jobsLeft ) ) { // some jobs failed to insert?
			// Try to insert the remaning jobs once more, ignoring the bad partitions
			$jobsLeft = $this->tryJobInsertions( $jobsLeft, $partitionsTry, $flags );
		}

		return !count( $jobsLeft );
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

		$partitionsTry = $this->getPartitionMap(); // (partition => weight)

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

	protected function doWaitForBackups() {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->waitForBackups();
		}
	}

	protected function doGetPeriodicTasks() {
		$tasks = array();
		if ( count( $this->partitionQueues ) > 1 ) {
			$tasks['checkSizeAndAdjustWeights'] = array(
				'callback' => array( $this, 'checkSizeAndAdjustWeights' ),
				'period'   => 3600
			);
		}
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
	 * Update queue size stats and adjust the partition weights if needed
	 *
	 * @return bool
	 */
	public function checkSizeAndAdjustWeights() {
		$size = 0;
		list( $pPartitions, ) = $this->getPartitionQueues();
		foreach ( $pPartitions as $queue ) { // pushable partitions
			$size += $queue->getSize();
		}
		$callback = function( $cache, $key, $value ) use ( $size ) {
			$now = time();
			if ( is_array( $value ) ) {
				$value['aveSize'] = .1 * $size + .9 * $value['aveSize'];
			} else {
				$value = array( 'aveSize' => $size, 'coalesce' => false, 'sTime' => $now );
			}
			$value['eTime'] = $now;
			if ( ( $value['eTime'] - $value['sTime'] ) >= 12*3600 ) {
				$isSmall = ( $value['aveSize'] < 1000 ); // queue doesn't need partitions?
			} else {
				$isSmall = false;
			}
			if ( $value['coalesce'] !== $isSmall ) {
				$msg = $isSmall ? 'Coalescing' : 'De-coalescing';
				wfDebugLog( 'JobQueueFederated', "$msg queue {$this->wiki}/{$this->type}" );
			}
			$value['coalesce'] = $isSmall; // shift weight to one partition?
			return $value;
		};
		return $this->cache->merge( $this->getCacheKey( 'queue-size-stats' ), $callback, 0, 1 );
	}

	/**
	 * @return Array Map of (partition name => weight) in descending weight order
	 */
	protected function getPartitionMap() {
		$nPartitions = count( $this->partitionWeights );
		if ( $nPartitions <= 1 || ( time() - $this->loadedWeights ) <= 10 ) {
			return $this->partitionWeights;
		}
		$this->loadedWeights = time(); // cache for some time

		$info = $this->cache->get( $this->getCacheKey( 'queue-size-stats' ) );
		if ( is_array( $info ) && $info['coalesce'] === true ) {
			// Find the partition (and a fallback) for this entire queue
			// and do so in a way that respects the partitions weights.
			$partitionRing = new HashRing( array_diff_key(
				$this->partitionWeights, $this->partitionsNoPush ) );
			$partitionsPush = $partitionRing->getLocations( "{$this->wiki}:{$this->type}", 2 );
			// Statistically map the queue push/pop load onto a single partition.
			// This is useful when there are many queues/wiki that don't get much use.
			$qPartition = $partitionsPush[0];
			$sum = array_sum( $this->partitionWeights ) - $this->partitionWeights[$qPartition];
			$this->partitionWeights[$qPartition] = max( 1, 99 * $sum ); // 99% load
			// Stop pushing to any of the other partitions
			$this->partitionsNoPush = array_diff_key(
				$this->partitionWeights, array_flip( $partitionsPush ) );
		}

		// Sort by highest weight first, which is best for pop()/isEmpty()
		arsort( $this->partitionWeights, SORT_NUMERIC );

		return $this->partitionWeights;
	}

	/**
	 * Get the map of (partition name => JobQueue) in descending weight order
	 * for all the pushable partitions and non-pushable partitions, respectively.
	 *
	 * @return array (array map, array map)
	 */
	protected function getPartitionQueues() {
		$res = array( array(), array() );
		foreach ( $this->getPartitionMap() as $partition => $w ) {
			if ( isset( $this->partitionsNoPush[$partition] ) ) {
				$res[1][$partition] = $this->partitionQueues[$partition];
			} else {
				$res[0][$partition] = $this->partitionQueues[$partition];
			}
		}
		return $res;
	}

	/**
	 * @param string $property
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $property );
	}
}
