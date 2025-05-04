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

use InvalidArgumentException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\JobQueueEnqueueUpdate;
use MediaWiki\JobQueue\Exceptions\JobQueueError;
use MediaWiki\MediaWikiServices;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Handle enqueueing of background jobs.
 *
 * @warning This service class supports queuing jobs to foreign wikis via JobQueueGroupFactory,
 * but other operations may be called for the local wiki only. Exceptions may be thrown if you
 * attempt to inspect, pop, or execute a foreign wiki's job queue.
 *
 * @since 1.21
 * @ingroup JobQueue
 */
class JobQueueGroup {
	/** @var MapCacheLRU */
	protected $cache;

	/** @var string Wiki domain ID */
	protected $domain;
	/** @var ReadOnlyMode Read only mode */
	protected $readOnlyMode;
	/** @var array|null */
	private $localJobClasses;
	/** @var array */
	private $jobTypeConfiguration;
	/** @var array */
	private $jobTypesExcludedFromDefaultQueue;
	/** @var StatsFactory */
	private $statsFactory;
	/** @var WANObjectCache */
	private $wanCache;
	/** @var GlobalIdGenerator */
	private $globalIdGenerator;

	/** @var array Map of (bucket => (queue => JobQueue, types => list of types) */
	protected $coalescedQueues;

	public const TYPE_DEFAULT = 1; // integer; jobs popped by default
	private const TYPE_ANY = 2; // integer; any job

	public const USE_CACHE = 1; // integer; use process or persistent cache

	private const PROC_CACHE_TTL = 15; // integer; seconds

	/**
	 * @internal Use MediaWikiServices::getJobQueueGroupFactory
	 *
	 * @param string $domain Wiki domain ID
	 * @param ReadOnlyMode $readOnlyMode Read-only mode
	 * @param array|null $localJobClasses
	 * @param array $jobTypeConfiguration
	 * @param array $jobTypesExcludedFromDefaultQueue
	 * @param StatsFactory $statsFactory
	 * @param WANObjectCache $wanCache
	 * @param GlobalIdGenerator $globalIdGenerator
	 *
	 */
	public function __construct(
		$domain,
		ReadOnlyMode $readOnlyMode,
		?array $localJobClasses,
		array $jobTypeConfiguration,
		array $jobTypesExcludedFromDefaultQueue,
		StatsFactory $statsFactory,
		WANObjectCache $wanCache,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->domain = $domain;
		$this->readOnlyMode = $readOnlyMode;
		$this->cache = new MapCacheLRU( 10 );
		$this->localJobClasses = $localJobClasses;
		$this->jobTypeConfiguration = $jobTypeConfiguration;
		$this->jobTypesExcludedFromDefaultQueue = $jobTypesExcludedFromDefaultQueue;
		$this->statsFactory = $statsFactory;
		$this->wanCache = $wanCache;
		$this->globalIdGenerator = $globalIdGenerator;
	}

	/**
	 * Get the job queue object for a given queue type
	 *
	 * @param string $type
	 * @return JobQueue
	 */
	public function get( $type ) {
		$conf = [ 'domain' => $this->domain, 'type' => $type ];
		$conf += $this->jobTypeConfiguration[$type] ?? $this->jobTypeConfiguration['default'];
		if ( !isset( $conf['readOnlyReason'] ) ) {
			$conf['readOnlyReason'] = $this->readOnlyMode->getConfiguredReason();
		}

		$conf['stats'] = $this->statsFactory;
		$conf['wanCache'] = $this->wanCache;
		$conf['idGenerator'] = $this->globalIdGenerator;

		return JobQueue::factory( $conf );
	}

	/**
	 * Insert jobs into the respective queues of which they belong
	 *
	 * This inserts the jobs into the queue specified by $wgJobTypeConf
	 * and updates the aggregate job queue information cache as needed.
	 *
	 * @param IJobSpecification|IJobSpecification[] $jobs A single Job or a list of Jobs
	 * @return void
	 */
	public function push( $jobs ) {
		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];
		if ( $jobs === [] ) {
			return;
		}

		$this->assertValidJobs( $jobs );

		$jobsByType = []; // (job type => list of jobs)
		foreach ( $jobs as $job ) {
			$type = $job->getType();
			if ( isset( $this->jobTypeConfiguration[$type] ) ) {
				$jobsByType[$type][] = $job;
			} else {
				if (
					isset( $this->jobTypeConfiguration['default']['typeAgnostic'] ) &&
					$this->jobTypeConfiguration['default']['typeAgnostic']
				) {
					$jobsByType['default'][] = $job;
				} else {
					$jobsByType[$type][] = $job;
				}
			}
		}

		foreach ( $jobsByType as $type => $jobs ) {
			$this->get( $type )->push( $jobs );
		}

		if ( $this->cache->hasField( 'queues-ready', 'list' ) ) {
			$list = $this->cache->getField( 'queues-ready', 'list' );
			if ( count( array_diff( array_keys( $jobsByType ), $list ) ) ) {
				$this->cache->clear( 'queues-ready' );
			}
		}

		$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()->getLocalClusterInstance();
		$cache->set(
			$cache->makeGlobalKey( 'jobqueue', $this->domain, 'hasjobs', self::TYPE_ANY ),
			'true',
			15
		);
		if ( array_diff( array_keys( $jobsByType ), $this->jobTypesExcludedFromDefaultQueue ) ) {
			$cache->set(
				$cache->makeGlobalKey( 'jobqueue', $this->domain, 'hasjobs', self::TYPE_DEFAULT ),
				'true',
				15
			);
		}
	}

	/**
	 * Buffer jobs for insertion via push() or call it now if in CLI mode
	 *
	 * @param IJobSpecification|IJobSpecification[] $jobs A single Job or a list of Jobs
	 * @return void
	 * @since 1.26
	 */
	public function lazyPush( $jobs ) {
		if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
			$this->push( $jobs );
			return;
		}

		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];

		// Throw errors now instead of on push(), when other jobs may be buffered
		$this->assertValidJobs( $jobs );

		DeferredUpdates::addUpdate( new JobQueueEnqueueUpdate( $this->domain, $jobs ) );
	}

	/**
	 * Pop one job off a job queue
	 *
	 * @warning May not be called on foreign wikis!
	 *
	 * This pops a job off a queue as specified by $wgJobTypeConf and
	 * updates the aggregate job queue information cache as needed.
	 *
	 * @param int|string $qtype JobQueueGroup::TYPE_* constant or job type string
	 * @param int $flags Bitfield of JobQueueGroup::USE_* constants
	 * @param array $ignored List of job types to ignore
	 * @return RunnableJob|false Returns false on failure
	 * @throws JobQueueError
	 */
	public function pop( $qtype = self::TYPE_DEFAULT, $flags = 0, array $ignored = [] ) {
		$job = false;

		if ( !$this->localJobClasses ) {
			throw new JobQueueError(
				"Cannot pop '{$qtype}' job off foreign '{$this->domain}' wiki queue." );
		}
		if ( is_string( $qtype ) && !isset( $this->localJobClasses[$qtype] ) ) {
			// Do not pop jobs if there is no class for the queue type
			throw new JobQueueError( "Unrecognized job type '$qtype'." );
		}

		if ( is_string( $qtype ) ) { // specific job type
			if ( !in_array( $qtype, $ignored ) ) {
				$job = $this->get( $qtype )->pop();
			}
		} else { // any job in the "default" jobs types
			if ( $flags & self::USE_CACHE ) {
				if ( !$this->cache->hasField( 'queues-ready', 'list', self::PROC_CACHE_TTL ) ) {
					$this->cache->setField( 'queues-ready', 'list', $this->getQueuesWithJobs() );
				}
				$types = $this->cache->getField( 'queues-ready', 'list' );
			} else {
				$types = $this->getQueuesWithJobs();
			}

			if ( $qtype == self::TYPE_DEFAULT ) {
				$types = array_intersect( $types, $this->getDefaultQueueTypes() );
			}

			$types = array_diff( $types, $ignored ); // avoid selected types
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
	 * @param RunnableJob $job
	 * @return void
	 */
	public function ack( RunnableJob $job ) {
		$this->get( $job->getType() )->ack( $job );
	}

	/**
	 * Get the list of queue types
	 *
	 * @warning May not be called on foreign wikis!
	 *
	 * @return string[]
	 */
	public function getQueueTypes() {
		if ( !$this->localJobClasses ) {
			throw new JobQueueError( 'Cannot inspect job queue from foreign wiki' );
		}
		return array_keys( $this->localJobClasses );
	}

	/**
	 * Get the list of default queue types
	 *
	 * @warning May not be called on foreign wikis!
	 *
	 * @return string[]
	 */
	public function getDefaultQueueTypes() {
		return array_diff( $this->getQueueTypes(), $this->jobTypesExcludedFromDefaultQueue );
	}

	/**
	 * Check if there are any queues with jobs (this is cached)
	 *
	 * @warning May not be called on foreign wikis!
	 *
	 * @since 1.23
	 * @param int $type JobQueueGroup::TYPE_* constant
	 * @return bool
	 */
	public function queuesHaveJobs( $type = self::TYPE_ANY ) {
		$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()->getLocalClusterInstance();
		$key = $cache->makeGlobalKey( 'jobqueue', $this->domain, 'hasjobs', $type );

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
	 * @warning May not be called on foreign wikis!
	 *
	 * @return string[] List of job types that have non-empty queues
	 */
	public function getQueuesWithJobs() {
		$types = [];
		foreach ( $this->getCoalescedQueues() as $info ) {
			/** @var JobQueue $queue */
			$queue = $info['queue'];
			$nonEmpty = $queue->getSiblingQueuesWithJobs( $this->getQueueTypes() );
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
	 * Get the size of the queues for a list of job types
	 *
	 * @warning May not be called on foreign wikis!
	 *
	 * @return int[] Map of (job type => size)
	 */
	public function getQueueSizes() {
		$sizeMap = [];
		foreach ( $this->getCoalescedQueues() as $info ) {
			/** @var JobQueue $queue */
			$queue = $info['queue'];
			$sizes = $queue->getSiblingQueueSizes( $this->getQueueTypes() );
			if ( is_array( $sizes ) ) { // batching features supported
				$sizeMap += $sizes;
			} else { // we have to go through the queues in the bucket one-by-one
				foreach ( $info['types'] as $type ) {
					$sizeMap[$type] = $this->get( $type )->getSize();
				}
			}
		}

		return $sizeMap;
	}

	/**
	 * @return array[]
	 * @phan-return array<string,array{queue:JobQueue,types:array<string,class-string>}>
	 */
	protected function getCoalescedQueues() {
		if ( $this->coalescedQueues === null ) {
			$this->coalescedQueues = [];
			foreach ( $this->jobTypeConfiguration as $type => $conf ) {
				$conf['domain'] = $this->domain;
				$conf['type'] = 'null';
				$conf['stats'] = $this->statsFactory;
				$conf['wanCache'] = $this->wanCache;
				$conf['idGenerator'] = $this->globalIdGenerator;

				$queue = JobQueue::factory( $conf );
				$loc = $queue->getCoalesceLocationInternal();
				if ( !isset( $this->coalescedQueues[$loc] ) ) {
					$this->coalescedQueues[$loc]['queue'] = $queue;
					$this->coalescedQueues[$loc]['types'] = [];
				}
				if ( $type === 'default' ) {
					$this->coalescedQueues[$loc]['types'] = array_merge(
						$this->coalescedQueues[$loc]['types'],
						array_diff( $this->getQueueTypes(), array_keys( $this->jobTypeConfiguration ) )
					);
				} else {
					$this->coalescedQueues[$loc]['types'][] = $type;
				}
			}
		}

		return $this->coalescedQueues;
	}

	private function assertValidJobs( array $jobs ) {
		foreach ( $jobs as $job ) {
			if ( !( $job instanceof IJobSpecification ) ) {
				$type = get_debug_type( $job );
				throw new InvalidArgumentException( "Expected IJobSpecification objects, got " . $type );
			}
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( JobQueueGroup::class, 'JobQueueGroup' );
