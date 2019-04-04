<?php
/**
 * Redis-backed job queue code.
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
 */
use Psr\Log\LoggerInterface;

/**
 * Class to handle job queues stored in Redis
 *
 * This is a faster and less resource-intensive job queue than JobQueueDB.
 * All data for a queue using this class is placed into one redis server.
 * The mediawiki/services/jobrunner background service must be set up and running.
 *
 * There are eight main redis keys (per queue) used to track jobs:
 *   - l-unclaimed  : A list of job IDs used for ready unclaimed jobs
 *   - z-claimed    : A sorted set of (job ID, UNIX timestamp as score) used for job retries
 *   - z-abandoned  : A sorted set of (job ID, UNIX timestamp as score) used for broken jobs
 *   - z-delayed    : A sorted set of (job ID, UNIX timestamp as score) used for delayed jobs
 *   - h-idBySha1   : A hash of (SHA1 => job ID) for unclaimed jobs used for de-duplication
 *   - h-sha1ById   : A hash of (job ID => SHA1) for unclaimed jobs used for de-duplication
 *   - h-attempts   : A hash of (job ID => attempt count) used for job claiming/retries
 *   - h-data       : A hash of (job ID => serialized blobs) for job storage
 * A job ID can be in only one of z-delayed, l-unclaimed, z-claimed, and z-abandoned.
 * If an ID appears in any of those lists, it should have a h-data entry for its ID.
 * If a job has a SHA1 de-duplication value and its ID is in l-unclaimed or z-delayed, then
 * there should be no other such jobs with that SHA1. Every h-idBySha1 entry has an h-sha1ById
 * entry and every h-sha1ById must refer to an ID that is l-unclaimed. If a job has its
 * ID in z-claimed or z-abandoned, then it must also have an h-attempts entry for its ID.
 *
 * The following keys are used to track queue states:
 *   - s-queuesWithJobs : A set of all queues with non-abandoned jobs
 *
 * The background service takes care of undelaying, recycling, and pruning jobs as well as
 * removing s-queuesWithJobs entries as queues empty.
 *
 * Additionally, "rootjob:* keys track "root jobs" used for additional de-duplication.
 * Aside from root job keys, all keys have no expiry, and are only removed when jobs are run.
 * All the keys are prefixed with the relevant wiki ID information.
 *
 * This class requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 * Additionally, it should be noted that redis has different persistence modes, such
 * as rdb snapshots, journaling, and no persistence. Appropriate configuration should be
 * made on the servers based on what queues are using it and what tolerance they have.
 *
 * @ingroup JobQueue
 * @ingroup Redis
 * @since 1.22
 */
class JobQueueRedis extends JobQueue {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var LoggerInterface */
	protected $logger;

	/** @var string Server address */
	protected $server;
	/** @var string Compression method to use */
	protected $compression;

	const MAX_PUSH_SIZE = 25; // avoid tying up the server

	/**
	 * @param array $params Possible keys:
	 *   - redisConfig : An array of parameters to RedisConnectionPool::__construct().
	 *                   Note that the serializer option is ignored as "none" is always used.
	 *   - redisServer : A hostname/port combination or the absolute path of a UNIX socket.
	 *                   If a hostname is specified but no port, the standard port number
	 *                   6379 will be used. Required.
	 *   - compression : The type of compression to use; one of (none,gzip).
	 *   - daemonized  : Set to true if the redisJobRunnerService runs in the background.
	 *                   This will disable job recycling/undelaying from the MediaWiki side
	 *                   to avoid redundance and out-of-sync configuration.
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$params['redisConfig']['serializer'] = 'none'; // make it easy to use Lua
		$this->server = $params['redisServer'];
		$this->compression = $params['compression'] ?? 'none';
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConfig'] );
		if ( empty( $params['daemonized'] ) ) {
			throw new InvalidArgumentException(
				"Non-daemonized mode is no longer supported. Please install the " .
				"mediawiki/services/jobrunner service and update \$wgJobTypeConf as needed." );
		}
		$this->logger = \MediaWiki\Logger\LoggerFactory::getInstance( 'redis' );
	}

	protected function supportedOrders() {
		return [ 'timestamp', 'fifo' ];
	}

	protected function optimalOrder() {
		return 'fifo';
	}

	protected function supportsDelayedJobs() {
		return true;
	}

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 * @throws JobQueueError
	 */
	protected function doIsEmpty() {
		return $this->doGetSize() == 0;
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return int
	 * @throws JobQueueError
	 */
	protected function doGetSize() {
		$conn = $this->getConnection();
		try {
			return $conn->lSize( $this->getQueueKey( 'l-unclaimed' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doGetAcquiredCount()
	 * @return int
	 * @throws JobQueueError
	 */
	protected function doGetAcquiredCount() {
		$conn = $this->getConnection();
		try {
			$conn->multi( Redis::PIPELINE );
			$conn->zSize( $this->getQueueKey( 'z-claimed' ) );
			$conn->zSize( $this->getQueueKey( 'z-abandoned' ) );

			return array_sum( $conn->exec() );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doGetDelayedCount()
	 * @return int
	 * @throws JobQueueError
	 */
	protected function doGetDelayedCount() {
		$conn = $this->getConnection();
		try {
			return $conn->zSize( $this->getQueueKey( 'z-delayed' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doGetAbandonedCount()
	 * @return int
	 * @throws JobQueueError
	 */
	protected function doGetAbandonedCount() {
		$conn = $this->getConnection();
		try {
			return $conn->zSize( $this->getQueueKey( 'z-abandoned' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 * @return void
	 * @throws JobQueueError
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		// Convert the jobs into field maps (de-duplicated against each other)
		$items = []; // (job ID => job fields map)
		foreach ( $jobs as $job ) {
			$item = $this->getNewJobFields( $job );
			if ( strlen( $item['sha1'] ) ) { // hash identifier => de-duplicate
				$items[$item['sha1']] = $item;
			} else {
				$items[$item['uuid']] = $item;
			}
		}

		if ( $items === [] ) {
			return; // nothing to do
		}

		$conn = $this->getConnection();
		try {
			// Actually push the non-duplicate jobs into the queue...
			if ( $flags & self::QOS_ATOMIC ) {
				$batches = [ $items ]; // all or nothing
			} else {
				$batches = array_chunk( $items, self::MAX_PUSH_SIZE );
			}
			$failed = 0;
			$pushed = 0;
			foreach ( $batches as $itemBatch ) {
				$added = $this->pushBlobs( $conn, $itemBatch );
				if ( is_int( $added ) ) {
					$pushed += $added;
				} else {
					$failed += count( $itemBatch );
				}
			}
			$this->incrStats( 'inserts', $this->type, count( $items ) );
			$this->incrStats( 'inserts_actual', $this->type, $pushed );
			$this->incrStats( 'dupe_inserts', $this->type,
				count( $items ) - $failed - $pushed );
			if ( $failed > 0 ) {
				$err = "Could not insert {$failed} {$this->type} job(s).";
				wfDebugLog( 'JobQueueRedis', $err );
				throw new RedisException( $err );
			}
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @param RedisConnRef $conn
	 * @param array $items List of results from JobQueueRedis::getNewJobFields()
	 * @return int Number of jobs inserted (duplicates are ignored)
	 * @throws RedisException
	 */
	protected function pushBlobs( RedisConnRef $conn, array $items ) {
		$args = [ $this->encodeQueueName() ];
		// Next args come in 4s ([id, sha1, rtime, blob [, id, sha1, rtime, blob ... ] ] )
		foreach ( $items as $item ) {
			$args[] = (string)$item['uuid'];
			$args[] = (string)$item['sha1'];
			$args[] = (string)$item['rtimestamp'];
			$args[] = (string)$this->serialize( $item );
		}
		static $script =
		/** @lang Lua */
<<<LUA
		local kUnclaimed, kSha1ById, kIdBySha1, kDelayed, kData, kQwJobs = unpack(KEYS)
		-- First argument is the queue ID
		local queueId = ARGV[1]
		-- Next arguments all come in 4s (one per job)
		local variadicArgCount = #ARGV - 1
		if variadicArgCount % 4 ~= 0 then
			return redis.error_reply('Unmatched arguments')
		end
		-- Insert each job into this queue as needed
		local pushed = 0
		for i = 2,#ARGV,4 do
			local id,sha1,rtimestamp,blob = ARGV[i],ARGV[i+1],ARGV[i+2],ARGV[i+3]
			if sha1 == '' or redis.call('hExists',kIdBySha1,sha1) == 0 then
				if 1*rtimestamp > 0 then
					-- Insert into delayed queue (release time as score)
					redis.call('zAdd',kDelayed,rtimestamp,id)
				else
					-- Insert into unclaimed queue
					redis.call('lPush',kUnclaimed,id)
				end
				if sha1 ~= '' then
					redis.call('hSet',kSha1ById,id,sha1)
					redis.call('hSet',kIdBySha1,sha1,id)
				end
				redis.call('hSet',kData,id,blob)
				pushed = pushed + 1
			end
		end
		-- Mark this queue as having jobs
		redis.call('sAdd',kQwJobs,queueId)
		return pushed
LUA;
		return $conn->luaEval( $script,
			array_merge(
				[
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
					$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
					$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
					$this->getQueueKey( 'z-delayed' ), # KEYS[4]
					$this->getQueueKey( 'h-data' ), # KEYS[5]
					$this->getGlobalKey( 's-queuesWithJobs' ), # KEYS[6]
				],
				$args
			),
			6 # number of first argument(s) that are keys
		);
	}

	/**
	 * @see JobQueue::doPop()
	 * @return Job|bool
	 * @throws JobQueueError
	 */
	protected function doPop() {
		$job = false;

		$conn = $this->getConnection();
		try {
			do {
				$blob = $this->popAndAcquireBlob( $conn );
				if ( !is_string( $blob ) ) {
					break; // no jobs; nothing to do
				}

				$this->incrStats( 'pops', $this->type );
				$item = $this->unserialize( $blob );
				if ( $item === false ) {
					wfDebugLog( 'JobQueueRedis', "Could not unserialize {$this->type} job." );
					continue;
				}

				// If $item is invalid, the runner loop recyling will cleanup as needed
				$job = $this->getJobFromFields( $item ); // may be false
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $job;
	}

	/**
	 * @param RedisConnRef $conn
	 * @return array Serialized string or false
	 * @throws RedisException
	 */
	protected function popAndAcquireBlob( RedisConnRef $conn ) {
		static $script =
		/** @lang Lua */
<<<LUA
		local kUnclaimed, kSha1ById, kIdBySha1, kClaimed, kAttempts, kData = unpack(KEYS)
		local rTime = unpack(ARGV)
		-- Pop an item off the queue
		local id = redis.call('rPop',kUnclaimed)
		if not id then
			return false
		end
		-- Allow new duplicates of this job
		local sha1 = redis.call('hGet',kSha1ById,id)
		if sha1 then redis.call('hDel',kIdBySha1,sha1) end
		redis.call('hDel',kSha1ById,id)
		-- Mark the jobs as claimed and return it
		redis.call('zAdd',kClaimed,rTime,id)
		redis.call('hIncrBy',kAttempts,id,1)
		return redis.call('hGet',kData,id)
LUA;
		return $conn->luaEval( $script,
			[
				$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
				$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
				$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
				$this->getQueueKey( 'z-claimed' ), # KEYS[4]
				$this->getQueueKey( 'h-attempts' ), # KEYS[5]
				$this->getQueueKey( 'h-data' ), # KEYS[6]
				time(), # ARGV[1] (injected to be replication-safe)
			],
			6 # number of first argument(s) that are keys
		);
	}

	/**
	 * @see JobQueue::doAck()
	 * @param Job $job
	 * @return Job|bool
	 * @throws UnexpectedValueException
	 * @throws JobQueueError
	 */
	protected function doAck( Job $job ) {
		$uuid = $job->getMetadata( 'uuid' );
		if ( $uuid === null ) {
			throw new UnexpectedValueException( "Job of type '{$job->getType()}' has no UUID." );
		}

		$conn = $this->getConnection();
		try {
			static $script =
			/** @lang Lua */
<<<LUA
			local kClaimed, kAttempts, kData = unpack(KEYS)
			local id = unpack(ARGV)
			-- Unmark the job as claimed
			local removed = redis.call('zRem',kClaimed,id)
			-- Check if the job was recycled
			if removed == 0 then
				return 0
			end
			-- Delete the retry data
			redis.call('hDel',kAttempts,id)
			-- Delete the job data itself
			return redis.call('hDel',kData,id)
LUA;
			$res = $conn->luaEval( $script,
				[
					$this->getQueueKey( 'z-claimed' ), # KEYS[1]
					$this->getQueueKey( 'h-attempts' ), # KEYS[2]
					$this->getQueueKey( 'h-data' ), # KEYS[3]
					$uuid # ARGV[1]
				],
				3 # number of first argument(s) that are keys
			);

			if ( !$res ) {
				wfDebugLog( 'JobQueueRedis', "Could not acknowledge {$this->type} job $uuid." );

				return false;
			}

			$this->incrStats( 'acks', $this->type );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return true;
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
	 * @param IJobSpecification $job
	 * @return bool
	 * @throws JobQueueError
	 * @throws LogicException
	 */
	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		if ( !$job->hasRootJobParams() ) {
			throw new LogicException( "Cannot register root job; missing parameters." );
		}
		$params = $job->getRootJobParams();

		$key = $this->getRootJobCacheKey( $params['rootJobSignature'] );

		$conn = $this->getConnection();
		try {
			$timestamp = $conn->get( $key ); // current last timestamp of this job
			if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
				return true; // a newer version of this root job was enqueued
			}

			// Update the timestamp of the last root job started at the location...
			return $conn->set( $key, $params['rootJobTimestamp'], self::ROOTJOB_TTL ); // 2 weeks
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doIsRootJobOldDuplicate()
	 * @param Job $job
	 * @return bool
	 * @throws JobQueueError
	 */
	protected function doIsRootJobOldDuplicate( Job $job ) {
		if ( !$job->hasRootJobParams() ) {
			return false; // job has no de-deplication info
		}
		$params = $job->getRootJobParams();

		$conn = $this->getConnection();
		try {
			// Get the last time this root job was enqueued
			$timestamp = $conn->get( $this->getRootJobCacheKey( $params['rootJobSignature'] ) );
		} catch ( RedisException $e ) {
			$timestamp = false;
			$this->throwRedisException( $conn, $e );
		}

		// Check if a new root job was started at the location after this one's...
		return ( $timestamp && $timestamp > $params['rootJobTimestamp'] );
	}

	/**
	 * @see JobQueue::doDelete()
	 * @return bool
	 * @throws JobQueueError
	 */
	protected function doDelete() {
		static $props = [ 'l-unclaimed', 'z-claimed', 'z-abandoned',
			'z-delayed', 'h-idBySha1', 'h-sha1ById', 'h-attempts', 'h-data' ];

		$conn = $this->getConnection();
		try {
			$keys = [];
			foreach ( $props as $prop ) {
				$keys[] = $this->getQueueKey( $prop );
			}

			$ok = ( $conn->delete( $keys ) !== false );
			$conn->sRem( $this->getGlobalKey( 's-queuesWithJobs' ), $this->encodeQueueName() );

			return $ok;
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 * @throws JobQueueError
	 */
	public function getAllQueuedJobs() {
		$conn = $this->getConnection();
		try {
			$uids = $conn->lRange( $this->getQueueKey( 'l-unclaimed' ), 0, -1 );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $this->getJobIterator( $conn, $uids );
	}

	/**
	 * @see JobQueue::getAllDelayedJobs()
	 * @return Iterator
	 * @throws JobQueueError
	 */
	public function getAllDelayedJobs() {
		$conn = $this->getConnection();
		try {
			$uids = $conn->zRange( $this->getQueueKey( 'z-delayed' ), 0, -1 );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $this->getJobIterator( $conn, $uids );
	}

	/**
	 * @see JobQueue::getAllAcquiredJobs()
	 * @return Iterator
	 * @throws JobQueueError
	 */
	public function getAllAcquiredJobs() {
		$conn = $this->getConnection();
		try {
			$uids = $conn->zRange( $this->getQueueKey( 'z-claimed' ), 0, -1 );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $this->getJobIterator( $conn, $uids );
	}

	/**
	 * @see JobQueue::getAllAbandonedJobs()
	 * @return Iterator
	 * @throws JobQueueError
	 */
	public function getAllAbandonedJobs() {
		$conn = $this->getConnection();
		try {
			$uids = $conn->zRange( $this->getQueueKey( 'z-abandoned' ), 0, -1 );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $this->getJobIterator( $conn, $uids );
	}

	/**
	 * @param RedisConnRef $conn
	 * @param array $uids List of job UUIDs
	 * @return MappedIterator
	 */
	protected function getJobIterator( RedisConnRef $conn, array $uids ) {
		return new MappedIterator(
			$uids,
			function ( $uid ) use ( $conn ) {
				return $this->getJobFromUidInternal( $uid, $conn );
			},
			[ 'accept' => function ( $job ) {
				return is_object( $job );
			} ]
		);
	}

	public function getCoalesceLocationInternal() {
		return "RedisServer:" . $this->server;
	}

	protected function doGetSiblingQueuesWithJobs( array $types ) {
		return array_keys( array_filter( $this->doGetSiblingQueueSizes( $types ) ) );
	}

	protected function doGetSiblingQueueSizes( array $types ) {
		$sizes = []; // (type => size)
		$types = array_values( $types ); // reindex
		$conn = $this->getConnection();
		try {
			$conn->multi( Redis::PIPELINE );
			foreach ( $types as $type ) {
				$conn->lSize( $this->getQueueKey( 'l-unclaimed', $type ) );
			}
			$res = $conn->exec();
			if ( is_array( $res ) ) {
				foreach ( $res as $i => $size ) {
					$sizes[$types[$i]] = $size;
				}
			}
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $sizes;
	}

	/**
	 * This function should not be called outside JobQueueRedis
	 *
	 * @param string $uid
	 * @param RedisConnRef $conn
	 * @return Job|bool Returns false if the job does not exist
	 * @throws JobQueueError
	 * @throws UnexpectedValueException
	 */
	public function getJobFromUidInternal( $uid, RedisConnRef $conn ) {
		try {
			$data = $conn->hGet( $this->getQueueKey( 'h-data' ), $uid );
			if ( $data === false ) {
				return false; // not found
			}
			$item = $this->unserialize( $data );
			if ( !is_array( $item ) ) { // this shouldn't happen
				throw new UnexpectedValueException( "Could not find job with ID '$uid'." );
			}
			$title = Title::makeTitle( $item['namespace'], $item['title'] );
			$job = Job::factory( $item['type'], $title, $item['params'] );
			$job->setMetadata( 'uuid', $item['uuid'] );
			$job->setMetadata( 'timestamp', $item['timestamp'] );
			// Add in attempt count for debugging at showJobs.php
			$job->setMetadata( 'attempts',
				$conn->hGet( $this->getQueueKey( 'h-attempts' ), $uid ) );

			return $job;
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @return array List of (wiki,type) tuples for queues with non-abandoned jobs
	 * @throws JobQueueConnectionError
	 * @throws JobQueueError
	 */
	public function getServerQueuesWithJobs() {
		$queues = [];

		$conn = $this->getConnection();
		try {
			$set = $conn->sMembers( $this->getGlobalKey( 's-queuesWithJobs' ) );
			foreach ( $set as $queue ) {
				$queues[] = $this->decodeQueueName( $queue );
			}
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $queues;
	}

	/**
	 * @param IJobSpecification $job
	 * @return array
	 */
	protected function getNewJobFields( IJobSpecification $job ) {
		return [
			// Fields that describe the nature of the job
			'type' => $job->getType(),
			'namespace' => $job->getTitle()->getNamespace(),
			'title' => $job->getTitle()->getDBkey(),
			'params' => $job->getParams(),
			// Some jobs cannot run until a "release timestamp"
			'rtimestamp' => $job->getReleaseTimestamp() ?: 0,
			// Additional job metadata
			'uuid' => UIDGenerator::newRawUUIDv4( UIDGenerator::QUICK_RAND ),
			'sha1' => $job->ignoreDuplicates()
				? Wikimedia\base_convert( sha1( serialize( $job->getDeduplicationInfo() ) ), 16, 36, 31 )
				: '',
			'timestamp' => time() // UNIX timestamp
		];
	}

	/**
	 * @param array $fields
	 * @return Job|bool
	 */
	protected function getJobFromFields( array $fields ) {
		$title = Title::makeTitle( $fields['namespace'], $fields['title'] );
		$job = Job::factory( $fields['type'], $title, $fields['params'] );
		$job->setMetadata( 'uuid', $fields['uuid'] );
		$job->setMetadata( 'timestamp', $fields['timestamp'] );

		return $job;
	}

	/**
	 * @param array $fields
	 * @return string Serialized and possibly compressed version of $fields
	 */
	protected function serialize( array $fields ) {
		$blob = serialize( $fields );
		if ( $this->compression === 'gzip'
			&& strlen( $blob ) >= 1024
			&& function_exists( 'gzdeflate' )
		) {
			$object = (object)[ 'blob' => gzdeflate( $blob ), 'enc' => 'gzip' ];
			$blobz = serialize( $object );

			return ( strlen( $blobz ) < strlen( $blob ) ) ? $blobz : $blob;
		} else {
			return $blob;
		}
	}

	/**
	 * @param string $blob
	 * @return array|bool Unserialized version of $blob or false
	 */
	protected function unserialize( $blob ) {
		$fields = unserialize( $blob );
		if ( is_object( $fields ) ) {
			if ( $fields->enc === 'gzip' && function_exists( 'gzinflate' ) ) {
				$fields = unserialize( gzinflate( $fields->blob ) );
			} else {
				$fields = false;
			}
		}

		return is_array( $fields ) ? $fields : false;
	}

	/**
	 * Get a connection to the server that handles all sub-queues for this queue
	 *
	 * @return RedisConnRef
	 * @throws JobQueueConnectionError
	 */
	protected function getConnection() {
		$conn = $this->redisPool->getConnection( $this->server, $this->logger );
		if ( !$conn ) {
			throw new JobQueueConnectionError(
				"Unable to connect to redis server {$this->server}." );
		}

		return $conn;
	}

	/**
	 * @param RedisConnRef $conn
	 * @param RedisException $e
	 * @throws JobQueueError
	 */
	protected function throwRedisException( RedisConnRef $conn, $e ) {
		$this->redisPool->handleError( $conn, $e );
		throw new JobQueueError( "Redis server error: {$e->getMessage()}\n" );
	}

	/**
	 * @return string JSON
	 */
	private function encodeQueueName() {
		return json_encode( [ $this->type, $this->domain ] );
	}

	/**
	 * @param string $name JSON
	 * @return array (type, wiki)
	 */
	private function decodeQueueName( $name ) {
		return json_decode( $name );
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function getGlobalKey( $name ) {
		$parts = [ 'global', 'jobqueue', $name ];
		foreach ( $parts as $part ) {
			if ( !preg_match( '/[a-zA-Z0-9_-]+/', $part ) ) {
				throw new InvalidArgumentException( "Key part characters are out of range." );
			}
		}

		return implode( ':', $parts );
	}

	/**
	 * @param string $prop
	 * @param string|null $type Override this for sibling queues
	 * @return string
	 */
	private function getQueueKey( $prop, $type = null ) {
		$type = is_string( $type ) ? $type : $this->type;

		// Use wiki ID for b/c
		$keyspace = WikiMap::getWikiIdFromDbDomain( $this->domain );

		$parts = [ $keyspace, 'jobqueue', $type, $prop ];

		// Parts are typically ASCII, but encode for sanity to escape ":"
		return implode( ':', array_map( 'rawurlencode', $parts ) );
	}
}
