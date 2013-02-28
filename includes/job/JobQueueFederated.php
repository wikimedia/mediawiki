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
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueFederated extends JobQueue {
	/** @var Array (wiki ID => section name) */
	protected $sectionsByWiki = array();
	/** @var Array (section name => (partition name => weight)) */
	protected $partitionsBySection = array();
	/** @var Array (section name => config array) */
	protected $configByPartition = array();
	/** @var Array **/
	protected $baseConfig;

	/** @var BagOStuff */
	protected $cache;

	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const CACHE_TTL_LONG  = 300; // integer; seconds to cache info that is kept up to date

	/**
	 * @params include:
	 *  - sectionsByWiki      : A map of wiki IDs to section names.
	 *                          Wikis will default to using the section "DEFAULT".
	 *  - partitionsBySection : Map of section names to maps of (partition name => weight).
	 *                          A section called 'default' must be defined if not all wikis
	 *                          have explicitly defined sections.
	 *  - configByPartition   : Map of queue partition names to configuration arrays.
	 *                          These configuration arrays are passed to JobQueue::factory().
	 *                          The options set here are overriden by those passed to this
	 *                          the federated queue itself (e.g. 'order' and 'claimTTL').
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->sectionsByWiki = $params['sectionsByWiki'];
		$this->partitionsBySection = $params['partitionsBySection'];
		$this->configByPartition = $params['configByPartition'];
		if ( $this->order === 'fifo' ) {
			throw new MWException( 'Federated queues do not support FIFO.' );
		}
		$this->baseConfig = $params;
		unset( $this->baseConfig['class'] );
		foreach ( array( 'sectionsByWiki', 'partitionsBySection', 'configByPartition' ) as $o ) {
			unset( $this->baseConfig[$o] );
		}
		$this->cache = wfGetMainCache();
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

		foreach ( $this->getQueuePartitions() as $queue ) {
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
		foreach ( $this->getQueuePartitions() as $queue ) {
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
		foreach ( $this->getQueuePartitions() as $queue ) {
			$count += $queue->doGetAcquiredCount();
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

		// Insert *all* the jobs in to one random parition queue to avoid RTTs.
		// The also means that the JobQueue::QoS_Atomic flag is respected if set.
		// A side effect is that massive insertions make the partitions less even.
		$partitionsTry = $this->getPartitionMap(); // (partition => weight)
		for ( $attempts=1; $attempts <= 2 && count( $partitionsTry ); ++$attempts ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			$queue = $this->getPartitionQueue( $partition );
			if ( $queue->doBatchPush( $jobs, $flags ) ) {
				$key = $this->getCacheKey( 'empty' );
				$this->cache->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
				return true;
			}
			unset( $partitionsTry[$partition] );
		}

		return false;
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job
	 */
	protected function doPop() {
		$partitionsTry = $this->getPartitionMap(); // (partition => weight)
		while ( count( $partitionsTry ) ) {
			$partition = ArrayUtils::pickRandom( $partitionsTry );
			$queue = $this->getPartitionQueue( $partition );
			$job = $queue->pop();
			if ( $job ) {
				$job->metadata['QueuePartition'] = $partition;
				return $job;
			}
			unset( $partitionsTry[$partition] );
		}
		$key = $this->getCacheKey( 'empty' );
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
		return $this->getPartitionQueue( $job->metadata['QueuePartition'] )->ack( $job );
	}

	/**
	 * @see JobQueue::waitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		foreach ( $this->getQueuePartitions() as $queue ) {
			$queue->waitForBackups();
		}
	}

	/**
	 * @see JobQueue::getPeriodicTasks()
	 * @return Array
	 */
	protected function doGetPeriodicTasks() {
		$tasks = array();
		foreach ( $this->getQueuePartitions() as $partition => $queue ) {
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
		foreach ( $this->getQueuePartitions() as $queue ) {
			$queue->doFlushCaches();
		}
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
	 * @param $partition string
	 * @return JobQueue
	 */
	protected function getPartitionQueue( $partition ) {
		if ( !isset( $this->configByPartition[$partition] ) ) {
			throw new MWException( "No configuration for partition '$partition'." );
		}
		return JobQueue::factory(
			$this->baseConfig + $this->configByPartition[$partition]
		);
	}

	/**
	 * @return Array Map of (partition name => JobQueue)
	 */
	protected function getQueuePartitions() {
		$queues = array();
		foreach ( $this->getPartitionMap( $this->wiki ) as $partition => $weight ) {
			$queues[$partition] = $this->getPartitionQueue( $partition );
		}
		return $queues;
	}

	/**
	 * @see JobQueue::setTestingPrefix()
	 * @param $key string
	 * @return void
	 * @throws MWException
	 */
	public function setTestingPrefix( $key ) {
		foreach ( $this->getQueuePartitions() as $queue ) {
			$queue->setTestingPrefix( $key );
		}
	}
}
