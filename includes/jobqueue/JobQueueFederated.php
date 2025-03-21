<?php
/**
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
 */

namespace MediaWiki\JobQueue;

use AppendIterator;
use Exception;
use InvalidArgumentException;
use MediaWiki\JobQueue\Exceptions\JobQueueError;
use UnexpectedValueException;
use Wikimedia\ArrayUtils\ArrayUtils;
use Wikimedia\HashRing\HashRing;

/**
 * Enqueue and run background jobs via a federated queue, for wiki farms.
 *
 * This class allows for queues to be partitioned into smaller queues.
 * A partition is defined by the configuration for a JobQueue instance.
 * For example, one can set $wgJobTypeConf['refreshLinks'] to point to a
 * JobQueueFederated instance, which itself would consist of three JobQueueRedis
 * instances, each using their own redis server. This would allow for the jobs
 * to be split (evenly or based on weights) across multiple servers if a single
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
 * @since 1.22
 * @ingroup JobQueue
 */
class JobQueueFederated extends JobQueue {
	/** @var HashRing */
	protected $partitionRing;
	/** @var JobQueue[] (partition name => JobQueue) reverse sorted by weight */
	protected $partitionQueues = [];

	/** @var int Maximum number of partitions to try */
	protected $maxPartitionsTry;

	/**
	 * @param array $params Possible keys:
	 *  - sectionsByWiki      : A map of wiki IDs to section names.
	 *                          Wikis will default to using the section "default".
	 *  - partitionsBySection : Map of section names to maps of (partition name => weight).
	 *                          A section called 'default' must be defined if not all wikis
	 *                          have explicitly defined sections.
	 *  - configByPartition   : Map of queue partition names to configuration arrays.
	 *                          These configuration arrays are passed to JobQueue::factory().
	 *                          The options set here are overridden by those passed to this
	 *                          the federated queue itself (e.g. 'order' and 'claimTTL').
	 *  - maxPartitionsTry    : Maximum number of times to attempt job insertion using
	 *                          different partition queues. This improves availability
	 *                          during failure, at the cost of added latency and somewhat
	 *                          less reliable job de-duplication mechanisms.
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$section = $params['sectionsByWiki'][$this->domain] ?? 'default';
		if ( !isset( $params['partitionsBySection'][$section] ) ) {
			throw new InvalidArgumentException( "No configuration for section '$section'." );
		}
		$this->maxPartitionsTry = $params['maxPartitionsTry'] ?? 2;
		// Get the full partition map
		$partitionMap = $params['partitionsBySection'][$section];
		arsort( $partitionMap, SORT_NUMERIC );
		// Get the config to pass to merge into each partition queue config
		$baseConfig = $params;
		foreach ( [ 'class', 'sectionsByWiki', 'maxPartitionsTry',
			'partitionsBySection', 'configByPartition', ] as $o
		) {
			unset( $baseConfig[$o] ); // partition queue doesn't care about this
		}
		// Get the partition queue objects
		foreach ( $partitionMap as $partition => $w ) {
			if ( !isset( $params['configByPartition'][$partition] ) ) {
				throw new InvalidArgumentException( "No configuration for partition '$partition'." );
			}
			$this->partitionQueues[$partition] = JobQueue::factory(
				$baseConfig + $params['configByPartition'][$partition] );
		}
		// Ring of all partitions
		$this->partitionRing = new HashRing( $partitionMap );
	}

	protected function supportedOrders() {
		// No FIFO due to partitioning, though "rough timestamp order" is supported
		return [ 'undefined', 'random', 'timestamp' ];
	}

	protected function optimalOrder() {
		return 'undefined'; // defer to the partitions
	}

	protected function supportsDelayedJobs() {
		foreach ( $this->partitionQueues as $queue ) {
			if ( !$queue->supportsDelayedJobs() ) {
				return false;
			}
		}

		return true;
	}

	protected function doIsEmpty() {
		$empty = true;
		$failed = 0;
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$empty = $empty && $queue->doIsEmpty();
			} catch ( JobQueueError $e ) {
				++$failed;
				$this->logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

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
		$count = 0;
		$failed = 0;
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$count += $queue->$method();
			} catch ( JobQueueError $e ) {
				++$failed;
				$this->logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return $count;
	}

	protected function doBatchPush( array $jobs, $flags ) {
		// Local ring variable that may be changed to point to a new ring on failure
		$partitionRing = $this->partitionRing;
		// Try to insert the jobs and update $partitionsTry on any failures.
		// Retry to insert any remaining jobs again, ignoring the bad partitions.
		$jobsLeft = $jobs;
		for ( $i = $this->maxPartitionsTry; $i > 0 && count( $jobsLeft ); --$i ) {
			try {
				$partitionRing->getLiveLocationWeights();
			} catch ( UnexpectedValueException $e ) {
				break; // all servers down; nothing to insert to
			}
			$jobsLeft = $this->tryJobInsertions( $jobsLeft, $partitionRing, $flags );
		}
		if ( count( $jobsLeft ) ) {
			throw new JobQueueError(
				"Could not insert job(s), {$this->maxPartitionsTry} partitions tried." );
		}
	}

	/**
	 * @param array $jobs
	 * @param HashRing &$partitionRing
	 * @param int $flags
	 * @throws JobQueueError
	 * @return IJobSpecification[] List of Job object that could not be inserted
	 */
	protected function tryJobInsertions( array $jobs, HashRing &$partitionRing, $flags ) {
		$jobsLeft = [];

		// Because jobs are spread across partitions, per-job de-duplication needs
		// to use a consistent hash to avoid allowing duplicate jobs per partition.
		// When inserting a batch of de-duplicated jobs, QOS_ATOMIC is disregarded.
		$uJobsByPartition = []; // (partition name => job list)
		/** @var Job $job */
		foreach ( $jobs as $key => $job ) {
			if ( $job->ignoreDuplicates() ) {
				$sha1 = sha1( serialize( $job->getDeduplicationInfo() ) );
				$uJobsByPartition[$partitionRing->getLiveLocation( $sha1 )][] = $job;
				unset( $jobs[$key] );
			}
		}
		// Get the batches of jobs that are not de-duplicated
		if ( $flags & self::QOS_ATOMIC ) {
			$nuJobBatches = [ $jobs ]; // all or nothing
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
				$ok = true;
				$queue->doBatchPush( $jobBatch, $flags | self::QOS_ATOMIC );
			} catch ( JobQueueError $e ) {
				$ok = false;
				$this->logException( $e );
			}
			if ( !$ok ) {
				if ( !$partitionRing->ejectFromLiveRing( $partition, 5 ) ) {
					throw new JobQueueError( "Could not insert job(s), no partitions available." );
				}
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			}
		}

		// Insert the jobs that are not de-duplicated into the queues...
		foreach ( $nuJobBatches as $jobBatch ) {
			$partition = ArrayUtils::pickRandom( $partitionRing->getLiveLocationWeights() );
			$queue = $this->partitionQueues[$partition];
			try {
				$ok = true;
				$queue->doBatchPush( $jobBatch, $flags | self::QOS_ATOMIC );
			} catch ( JobQueueError $e ) {
				$ok = false;
				$this->logException( $e );
			}
			if ( !$ok ) {
				if ( !$partitionRing->ejectFromLiveRing( $partition, 5 ) ) {
					throw new JobQueueError( "Could not insert job(s), no partitions available." );
				}
				$jobsLeft = array_merge( $jobsLeft, $jobBatch ); // not inserted
			}
		}

		return $jobsLeft;
	}

	protected function doPop() {
		$partitionsTry = $this->partitionRing->getLiveLocationWeights(); // (partition => weight)

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
				$this->logException( $e );
				$job = false;
			}
			if ( $job ) {
				$job->setMetadata( 'QueuePartition', $partition );

				return $job;
			} else {
				unset( $partitionsTry[$partition] );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return false;
	}

	protected function doAck( RunnableJob $job ) {
		$partition = $job->getMetadata( 'QueuePartition' );
		if ( $partition === null ) {
			throw new UnexpectedValueException( "The given job has no defined partition name." );
		}

		$this->partitionQueues[$partition]->ack( $job );
	}

	protected function doIsRootJobOldDuplicate( IJobSpecification $job ) {
		$signature = $job->getRootJobParams()['rootJobSignature'];
		$partition = $this->partitionRing->getLiveLocation( $signature );
		try {
			return $this->partitionQueues[$partition]->doIsRootJobOldDuplicate( $job );
		} catch ( JobQueueError $e ) {
			if ( $this->partitionRing->ejectFromLiveRing( $partition, 5 ) ) {
				$partition = $this->partitionRing->getLiveLocation( $signature );
				return $this->partitionQueues[$partition]->doIsRootJobOldDuplicate( $job );
			}
		}

		return false;
	}

	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		$signature = $job->getRootJobParams()['rootJobSignature'];
		$partition = $this->partitionRing->getLiveLocation( $signature );
		try {
			return $this->partitionQueues[$partition]->doDeduplicateRootJob( $job );
		} catch ( JobQueueError $e ) {
			if ( $this->partitionRing->ejectFromLiveRing( $partition, 5 ) ) {
				$partition = $this->partitionRing->getLiveLocation( $signature );
				return $this->partitionQueues[$partition]->doDeduplicateRootJob( $job );
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
				$this->logException( $e );
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
				$this->logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );
	}

	protected function doFlushCaches() {
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

	public function getAllAcquiredJobs() {
		$iterator = new AppendIterator();

		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllAcquiredJobs() );
		}

		return $iterator;
	}

	public function getAllAbandonedJobs() {
		$iterator = new AppendIterator();

		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			$iterator->append( $queue->getAllAbandonedJobs() );
		}

		return $iterator;
	}

	public function getCoalesceLocationInternal() {
		return "JobQueueFederated:wiki:{$this->domain}" .
			sha1( serialize( array_keys( $this->partitionQueues ) ) );
	}

	protected function doGetSiblingQueuesWithJobs( array $types ) {
		$result = [];

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
				$this->logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return array_values( $result );
	}

	protected function doGetSiblingQueueSizes( array $types ) {
		$result = [];
		$failed = 0;
		/** @var JobQueue $queue */
		foreach ( $this->partitionQueues as $queue ) {
			try {
				$sizes = $queue->doGetSiblingQueueSizes( $types );
				if ( is_array( $sizes ) ) {
					foreach ( $sizes as $type => $size ) {
						$result[$type] = ( $result[$type] ?? 0 ) + $size;
					}
				} else {
					return null; // not supported on all partitions; bail
				}
			} catch ( JobQueueError $e ) {
				++$failed;
				$this->logException( $e );
			}
		}
		$this->throwErrorIfAllPartitionsDown( $failed );

		return $result;
	}

	protected function logException( Exception $e ) {
		wfDebugLog( 'JobQueue', $e->getMessage() . "\n" . $e->getTraceAsString() );
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
}

/** @deprecated class alias since 1.44 */
class_alias( JobQueueFederated::class, 'JobQueueFederated' );
