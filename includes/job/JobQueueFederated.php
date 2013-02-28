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
	/** @var Array (wiki ID => section name) */
	protected $sectionsByWiki = array();
	/** @var Array (section name => (partition name => weight)) */
	protected $partitionsBySection = array();
	/** @var Array (section name => config array) */
	protected $configByPartition = array();
	/** @var Array (partition names => integer) */
	protected $partitionsPopOnly = array();

	/** @var Array (partition name => JobQueue) */
	protected $partitionQueues = array();
	/** @var BagOStuff */
	protected $cache;

	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const CACHE_TTL_LONG  = 300; // integer; seconds to cache info that is kept up to date

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
	 *  - partitionsPopOnly   : List of partition names that can handle pop() but not push().
	 *                          This can be used to migrate away from a certain partition.
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->sectionsByWiki = $params['sectionsByWiki'];
		$this->partitionsBySection = $params['partitionsBySection'];
		$this->configByPartition = $params['configByPartition'];
		if ( isset( $params['partitionsPopOnly'] ) ) {
			$this->partitionsPopOnly = array_flip( $params['partitionsPopOnly'] );
		}
		$baseConfig = $params;
		foreach ( array( 'class', 'sectionsByWiki',
			'partitionsBySection', 'configByPartition', 'partitionsPopOnly' ) as $o )
		{
			unset( $baseConfig[$o] );
		}
		foreach ( $this->getPartitionMap() as $partition => $w ) {
			if ( !isset( $this->configByPartition[$partition] ) ) {
				throw new MWException( "No configuration for partition '$partition'." );
			}
			$this->partitionQueues[$partition] = JobQueue::factory(
				$baseConfig + $this->configByPartition[$partition]
			);
		}
		$this->cache = count( $this->getPartitionMap() ) > 1
			? wfGetMainCache()
			: new EmptyBagOStuff(); // don't bother if there is only one partition

	}

	protected function supportedOrders() {
		// No FIFO due to partitioning, though "rough timestamp order" is supported
		return array( 'random', 'timestamp' );
	}

	protected function optimalOrder() {
		return 'undefined'; // defer to the partitions
	}

	protected function supportsDelayedJobs() {
		return true; // defer checks to the partitions
	}

	/**
	 * @see JobQueue::isEmpty()
	 * @return bool
	 */
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

	/**
	 * @see JobQueue::getSize()
	 * @return integer
	 */
	protected function doGetSize() {
		$key = $this->getCacheKey( 'size' );

		$size = $this->cache->get( $key );
		if ( is_int( $size ) ) {
			return $size;
		}

		$size = 0;
		foreach ( $this->partitionQueues as $queue ) {
			$size += $queue->doGetSize();
		}

		$this->cache->set( $key, $size, self::CACHE_TTL_SHORT );
		return $size;
	}

	/**
	 * @see JobQueue::getAcquiredCount()
	 * @return integer
	 */
	protected function doGetAcquiredCount() {
		$key = $this->getCacheKey( 'acquiredcount' );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$count = 0;
		foreach ( $this->partitionQueues as $queue ) {
			$count += $queue->doGetAcquiredCount();
		}

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );
		return $count;
	}

	/**
	 * @see JobQueue::doGetDelayedCount()
	 * @return integer
	 */
	protected function doGetDelayedCount() {
		$key = $this->getCacheKey( 'delayedcount' );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$count = 0;
		foreach ( $this->partitionQueues as $queue ) {
			$count += $queue->doGetDelayedCount();
		}

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );
		return $count;
	}

	/**
	 * @see JobQueue::batchPush()
	 * @return bool
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		if ( !count( $jobs ) ) {
			return true; // nothing to do
		}

		$partitionsTry = array_diff_key(
			$this->getPartitionMap(),
			$this->partitionsPopOnly
		); // (partition => weight)

		if ( $flags & self::QoS_Atomic ) {
			$jobBatches = array( $jobs ); // all or nothing
		} else {
			// Split the jobs into batches and spread them out over servers if there
			// are many jobs. This helps keep the partitions even. Otherwise, send all
			// the jobs to a single partition queue to avoids the extra connections.
			$jobBatches = array_chunk( $jobs, 500 );
		}

		$bSuccessCount = 0;
		foreach ( $jobBatches as $jobBatch ) {
			// Try up to two partition queues for each batch on failure
			for ( $attempts=1; $attempts <= 2 && count( $partitionsTry ); ++$attempts ) {
				$partition = ArrayUtils::pickRandom( $partitionsTry );
				$queue = $this->partitionQueues[$partition];
				if ( $queue->doBatchPush( $jobBatch, $flags ) ) {
					++$bSuccessCount;
					// Because jobs are spread accross partitions, per-job de-duplication
					// might allow up to N duplicates, where N is the number of partitions.
					// Reuse "root job" de-duplication to no-op all but one of those jobs.
					foreach ( $jobBatch as $job ) {
						if ( $job->ignoreDuplicates() && !$job->hasRootJobParams() ) {
							$sha1 = sha1( serialize( $job->getDeduplicationInfo() ) );
							$job->params = Job::newRootJobParams( $sha1 ) + $job->params;
							$this->doDeduplicateRootJob( $job );
						}
					}
					break; // move to next batch
				}
				unset( $partitionsTry[$partition] );
			}
			$key = $this->getCacheKey( 'empty' );
			$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
		}

		return ( $bSuccessCount == count( $jobBatches ) );
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job
	 */
	protected function doPop() {
		$key = $this->getCacheKey( 'empty' );

		$isEmpty = $this->cache->get( $key );
		if ( $isEmpty === 'true' ) {
			return false;
		}

		$partitionsTry = $this->getPartitionMap(); // (partition => weight)

		while ( count( $partitionsTry ) ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			$queue = $this->partitionQueues[$partition];
			$job = $queue->pop();
			if ( $job ) {
				$job->metadata['QueuePartition'] = $partition;
				return $job;
			}
			unset( $partitionsTry[$partition] );
		}

		$this->cache->set( $key, 'true', JobQueueDB::CACHE_TTL_LONG );
		return false;
	}

	/**
	 * @see JobQueue::ack()
	 * @return bool
	 */
	protected function doAck( Job $job ) {
		if ( !isset( $job->metadata['QueuePartition'] ) ) {
			throw new MWException( "The given job has no defined partition name." );
		}
		return $this->partitionQueues[$job->metadata['QueuePartition']]->ack( $job );
	}

	/**
	 * @see JobQueue::waitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->waitForBackups();
		}
	}

	/**
	 * @see JobQueue::getPeriodicTasks()
	 * @return Array
	 */
	protected function doGetPeriodicTasks() {
		$tasks = array();
		foreach ( $this->partitionQueues as $partition => $queue ) {
			foreach ( $queue->getPeriodicTasks() as $task => $def ) {
				$tasks["{$partition}:{$task}"] = $def;
			}
		}
		return $tasks;
	}

	/**
	 * @see JobQueue::flushCaches()
	 * @return void
	 */
	protected function doFlushCaches() {
		foreach ( array( 'empty', 'size', 'acquiredcount', 'delayedcount' ) as $type ) {
			$this->cache->delete( $this->getCacheKey( $type ) );
		}
		foreach ( $this->partitionQueues as $queue ) {
			$queue->doFlushCaches();
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllQueuedJobs() {
		$iterator = new AppendIterator();
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllQueuedJobs() );
		}
		return $iterator;
	}

	/**
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $property );
	}

	/**
	 * @return Array Map of (partition name => weight)
	 */
	protected function getPartitionMap() {
		$section = isset( $this->sectionsByWiki[$this->wiki] )
			? $this->sectionsByWiki[$this->wiki]
			: 'default';
		if ( !isset( $this->partitionsBySection[$section] ) ) {
			throw new MWException( "No configuration for section '$section'." );
		}
		return $this->partitionsBySection[$section];
	}

	/**
	 * @see JobQueue::setTestingPrefix()
	 * @param $key string
	 * @return void
	 * @throws MWException
	 */
	public function setTestingPrefix( $key ) {
		foreach ( $this->partitionQueues as $queue ) {
			$queue->setTestingPrefix( $key );
		}
	}
}
