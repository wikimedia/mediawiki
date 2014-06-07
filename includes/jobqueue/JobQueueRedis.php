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
 * @author Aaron Schulz
 */

/**
 * Class to handle job queues stored in Redis
 *
 * This is faster, less resource intensive, queue that JobQueueDB.
 * All data for a queue using this class is placed into one redis server.
 *
 * There are eight main redis keys used to track jobs:
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
 * Additionally, "rootjob:* keys track "root jobs" used for additional de-duplication.
 * Aside from root job keys, all keys have no expiry, and are only removed when jobs are run.
 * All the keys are prefixed with the relevant wiki ID information.
 *
 * This class requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 * Additionally, it should be noted that redis has different persistence modes, such
 * as rdb snapshots, journaling, and no persistent. Appropriate configuration should be
 * made on the servers based on what queues are using it and what tolerance they have.
 *
 * @ingroup JobQueue
 * @ingroup Redis
 * @since 1.22
 */
class JobQueueRedis extends JobQueue {
	/** @var RedisConnectionPool */
	protected $redisPool;

	/** @var string Server address */
	protected $server;

	/** @var string Compression method to use */
	protected $compression;

	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed (7 days)

	/** @var string Key to prefix the queue keys with (used for testing) */
	protected $key;

	/**
	 * @var null|int maximum seconds between execution of periodic tasks.  Used to speed up
	 * testing but should otherwise be left unset.
	 */
	protected $maximumPeriodicTaskSeconds;

	/**
	 * @params include:
	 *   - redisConfig : An array of parameters to RedisConnectionPool::__construct().
	 *                   Note that the serializer option is ignored as "none" is always used.
	 *   - redisServer : A hostname/port combination or the absolute path of a UNIX socket.
	 *                   If a hostname is specified but no port, the standard port number
	 *                   6379 will be used. Required.
	 *   - compression : The type of compression to use; one of (none,gzip).
	 *   - maximumPeriodicTaskSeconds : Maximum seconds between check periodic tasks.  Set to
	 *                   force faster execution of periodic tasks for inegration tests that
	 *                   rely on checkDelay.  Without this the integration tests are very very
	 *                   slow.  This really shouldn't be set in production.
	 * @param array $params
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$params['redisConfig']['serializer'] = 'none'; // make it easy to use Lua
		$this->server = $params['redisServer'];
		$this->compression = isset( $params['compression'] ) ? $params['compression'] : 'none';
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConfig'] );
		$this->maximumPeriodicTaskSeconds = isset( $params['maximumPeriodicTaskSeconds'] ) ?
			$params['maximumPeriodicTaskSeconds'] : null;
	}

	protected function supportedOrders() {
		return array( 'timestamp', 'fifo' );
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
	 * @throws MWException
	 */
	protected function doIsEmpty() {
		return $this->doGetSize() == 0;
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return int
	 * @throws MWException
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
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}
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
		if ( !$this->checkDelay ) {
			return 0; // no delayed jobs
		}
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
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}
		$conn = $this->getConnection();
		try {
			return $conn->zSize( $this->getQueueKey( 'z-abandoned' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param array $jobs
	 * @param $flags
	 * @return bool
	 * @throws JobQueueError
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		// Convert the jobs into field maps (de-duplicated against each other)
		$items = array(); // (job ID => job fields map)
		foreach ( $jobs as $job ) {
			$item = $this->getNewJobFields( $job );
			if ( strlen( $item['sha1'] ) ) { // hash identifier => de-duplicate
				$items[$item['sha1']] = $item;
			} else {
				$items[$item['uuid']] = $item;
			}
		}

		if ( !count( $items ) ) {
			return true; // nothing to do
		}

		$conn = $this->getConnection();
		try {
			// Actually push the non-duplicate jobs into the queue...
			if ( $flags & self::QOS_ATOMIC ) {
				$batches = array( $items ); // all or nothing
			} else {
				$batches = array_chunk( $items, 500 ); // avoid tying up the server
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
			if ( $failed > 0 ) {
				wfDebugLog( 'JobQueueRedis', "Could not insert {$failed} {$this->type} job(s)." );

				return false;
			}
			JobQueue::incrStats( 'job-insert', $this->type, count( $items ), $this->wiki );
			JobQueue::incrStats( 'job-insert-duplicate', $this->type,
				count( $items ) - $failed - $pushed, $this->wiki );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return true;
	}

	/**
	 * @param RedisConnRef $conn
	 * @param array $items List of results from JobQueueRedis::getNewJobFields()
	 * @return int Number of jobs inserted (duplicates are ignored)
	 * @throws RedisException
	 */
	protected function pushBlobs( RedisConnRef $conn, array $items ) {
		$args = array(); // ([id, sha1, rtime, blob [, id, sha1, rtime, blob ... ] ] )
		foreach ( $items as $item ) {
			$args[] = (string)$item['uuid'];
			$args[] = (string)$item['sha1'];
			$args[] = (string)$item['rtimestamp'];
			$args[] = (string)$this->serialize( $item );
		}
		static $script =
<<<LUA
		local kUnclaimed, kSha1ById, kIdBySha1, kDelayed, kData = unpack(KEYS)
		if #ARGV % 4 ~= 0 then return redis.error_reply('Unmatched arguments') end
		local pushed = 0
		for i = 1,#ARGV,4 do
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
		return pushed
LUA;
		return $conn->luaEval( $script,
			array_merge(
				array(
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
					$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
					$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
					$this->getQueueKey( 'z-delayed' ), # KEYS[4]
					$this->getQueueKey( 'h-data' ), # KEYS[5]
				),
				$args
			),
			5 # number of first argument(s) that are keys
		);
	}

	/**
	 * @see JobQueue::doPop()
	 * @return Job|bool
	 * @throws JobQueueError
	 */
	protected function doPop() {
		$job = false;

		// Push ready delayed jobs into the queue every 10 jobs to spread the load.
		// This is also done as a periodic task, but we don't want too much done at once.
		if ( $this->checkDelay && mt_rand( 0, 9 ) == 0 ) {
			$this->recyclePruneAndUndelayJobs();
		}

		$conn = $this->getConnection();
		try {
			do {
				if ( $this->claimTTL > 0 ) {
					// Keep the claimed job list down for high-traffic queues
					if ( mt_rand( 0, 99 ) == 0 ) {
						$this->recyclePruneAndUndelayJobs();
					}
					$blob = $this->popAndAcquireBlob( $conn );
				} else {
					$blob = $this->popAndDeleteBlob( $conn );
				}
				if ( $blob === false ) {
					break; // no jobs; nothing to do
				}

				JobQueue::incrStats( 'job-pop', $this->type, 1, $this->wiki );
				$item = $this->unserialize( $blob );
				if ( $item === false ) {
					wfDebugLog( 'JobQueueRedis', "Could not unserialize {$this->type} job." );
					continue;
				}

				// If $item is invalid, recyclePruneAndUndelayJobs() will cleanup as needed
				$job = $this->getJobFromFields( $item ); // may be false
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $job;
	}

	/**
	 * @param RedisConnRef $conn
	 * @return array serialized string or false
	 * @throws RedisException
	 */
	protected function popAndDeleteBlob( RedisConnRef $conn ) {
		static $script =
<<<LUA
		local kUnclaimed, kSha1ById, kIdBySha1, kData = unpack(KEYS)
		-- Pop an item off the queue
		local id = redis.call('rpop',kUnclaimed)
		if not id then return false end
		-- Get the job data and remove it
		local item = redis.call('hGet',kData,id)
		redis.call('hDel',kData,id)
		-- Allow new duplicates of this job
		local sha1 = redis.call('hGet',kSha1ById,id)
		if sha1 then redis.call('hDel',kIdBySha1,sha1) end
		redis.call('hDel',kSha1ById,id)
		-- Return the job data
		return item
LUA;
		return $conn->luaEval( $script,
			array(
				$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
				$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
				$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
				$this->getQueueKey( 'h-data' ), # KEYS[4]
			),
			4 # number of first argument(s) that are keys
		);
	}

	/**
	 * @param RedisConnRef $conn
	 * @return array serialized string or false
	 * @throws RedisException
	 */
	protected function popAndAcquireBlob( RedisConnRef $conn ) {
		static $script =
<<<LUA
		local kUnclaimed, kSha1ById, kIdBySha1, kClaimed, kAttempts, kData = unpack(KEYS)
		-- Pop an item off the queue
		local id = redis.call('rPop',kUnclaimed)
		if not id then return false end
		-- Allow new duplicates of this job
		local sha1 = redis.call('hGet',kSha1ById,id)
		if sha1 then redis.call('hDel',kIdBySha1,sha1) end
		redis.call('hDel',kSha1ById,id)
		-- Mark the jobs as claimed and return it
		redis.call('zAdd',kClaimed,ARGV[1],id)
		redis.call('hIncrBy',kAttempts,id,1)
		return redis.call('hGet',kData,id)
LUA;
		return $conn->luaEval( $script,
			array(
				$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
				$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
				$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
				$this->getQueueKey( 'z-claimed' ), # KEYS[4]
				$this->getQueueKey( 'h-attempts' ), # KEYS[5]
				$this->getQueueKey( 'h-data' ), # KEYS[6]
				time(), # ARGV[1] (injected to be replication-safe)
			),
			6 # number of first argument(s) that are keys
		);
	}

	/**
	 * @see JobQueue::doAck()
	 * @param Job $job
	 * @return Job|bool
	 * @throws MWException|JobQueueError
	 */
	protected function doAck( Job $job ) {
		if ( !isset( $job->metadata['uuid'] ) ) {
			throw new MWException( "Job of type '{$job->getType()}' has no UUID." );
		}
		if ( $this->claimTTL > 0 ) {
			$conn = $this->getConnection();
			try {
				static $script =
<<<LUA
				local kClaimed, kAttempts, kData = unpack(KEYS)
				-- Unmark the job as claimed
				redis.call('zRem',kClaimed,ARGV[1])
				redis.call('hDel',kAttempts,ARGV[1])
				-- Delete the job data itself
				return redis.call('hDel',kData,ARGV[1])
LUA;
				$res = $conn->luaEval( $script,
					array(
						$this->getQueueKey( 'z-claimed' ), # KEYS[1]
						$this->getQueueKey( 'h-attempts' ), # KEYS[2]
						$this->getQueueKey( 'h-data' ), # KEYS[3]
						$job->metadata['uuid'] # ARGV[1]
					),
					3 # number of first argument(s) that are keys
				);

				if ( !$res ) {
					wfDebugLog( 'JobQueueRedis', "Could not acknowledge {$this->type} job." );

					return false;
				}
			} catch ( RedisException $e ) {
				$this->throwRedisException( $conn, $e );
			}
		}

		return true;
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
	 * @param Job $job
	 * @return bool
	 * @throws MWException|JobQueueError
	 */
	protected function doDeduplicateRootJob( Job $job ) {
		if ( !$job->hasRootJobParams() ) {
			throw new MWException( "Cannot register root job; missing parameters." );
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
		static $props = array( 'l-unclaimed', 'z-claimed', 'z-abandoned',
			'z-delayed', 'h-idBySha1', 'h-sha1ById', 'h-attempts', 'h-data' );

		$conn = $this->getConnection();
		try {
			$keys = array();
			foreach ( $props as $prop ) {
				$keys[] = $this->getQueueKey( $prop );
			}

			return ( $conn->delete( $keys ) !== false );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllQueuedJobs() {
		$conn = $this->getConnection();
		try {
			$that = $this;

			return new MappedIterator(
				$conn->lRange( $this->getQueueKey( 'l-unclaimed' ), 0, -1 ),
				function ( $uid ) use ( $that, $conn ) {
					return $that->getJobFromUidInternal( $uid, $conn );
				},
				array( 'accept' => function ( $job ) {
					return is_object( $job );
				} )
			);
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllDelayedJobs() {
		$conn = $this->getConnection();
		try {
			$that = $this;

			return new MappedIterator( // delayed jobs
				$conn->zRange( $this->getQueueKey( 'z-delayed' ), 0, -1 ),
				function ( $uid ) use ( $that, $conn ) {
					return $that->getJobFromUidInternal( $uid, $conn );
				},
				array( 'accept' => function ( $job ) {
					return is_object( $job );
				} )
			);
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	public function getCoalesceLocationInternal() {
		return "RedisServer:" . $this->server;
	}

	protected function doGetSiblingQueuesWithJobs( array $types ) {
		return array_keys( array_filter( $this->doGetSiblingQueueSizes( $types ) ) );
	}

	protected function doGetSiblingQueueSizes( array $types ) {
		$sizes = array(); // (type => size)
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
	 * @param $uid string
	 * @param $conn RedisConnRef
	 * @return Job|bool Returns false if the job does not exist
	 * @throws MWException|JobQueueError
	 */
	public function getJobFromUidInternal( $uid, RedisConnRef $conn ) {
		try {
			$data = $conn->hGet( $this->getQueueKey( 'h-data' ), $uid );
			if ( $data === false ) {
				return false; // not found
			}
			$item = $this->unserialize( $conn->hGet( $this->getQueueKey( 'h-data' ), $uid ) );
			if ( !is_array( $item ) ) { // this shouldn't happen
				throw new MWException( "Could not find job with ID '$uid'." );
			}
			$title = Title::makeTitle( $item['namespace'], $item['title'] );
			$job = Job::factory( $item['type'], $title, $item['params'] );
			$job->metadata['uuid'] = $item['uuid'];

			return $job;
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 * and release any ready delayed jobs into the queue
	 *
	 * @return int Number of jobs recycled/deleted/undelayed
	 * @throws MWException|JobQueueError
	 */
	public function recyclePruneAndUndelayJobs() {
		$count = 0;
		// For each job item that can be retried, we need to add it back to the
		// main queue and remove it from the list of currenty claimed job items.
		// For those that cannot, they are marked as dead and kept around for
		// investigation and manual job restoration but are eventually deleted.
		$conn = $this->getConnection();
		try {
			$now = time();
			static $script =
<<<LUA
			local kClaimed, kAttempts, kUnclaimed, kData, kAbandoned, kDelayed = unpack(KEYS)
			local released,abandoned,pruned,undelayed = 0,0,0,0
			-- Get all non-dead jobs that have an expired claim on them.
			-- The score for each item is the last claim timestamp (UNIX).
			local staleClaims = redis.call('zRangeByScore',kClaimed,0,ARGV[1])
			for k,id in ipairs(staleClaims) do
				local timestamp = redis.call('zScore',kClaimed,id)
				local attempts = redis.call('hGet',kAttempts,id)
				if attempts < ARGV[3] then
					-- Claim expired and retries left: re-enqueue the job
					redis.call('lPush',kUnclaimed,id)
					redis.call('hIncrBy',kAttempts,id,1)
					released = released + 1
				else
					-- Claim expired and no retries left: mark the job as dead
					redis.call('zAdd',kAbandoned,timestamp,id)
					abandoned = abandoned + 1
				end
				redis.call('zRem',kClaimed,id)
			end
			-- Get all of the dead jobs that have been marked as dead for too long.
			-- The score for each item is the last claim timestamp (UNIX).
			local deadClaims = redis.call('zRangeByScore',kAbandoned,0,ARGV[2])
			for k,id in ipairs(deadClaims) do
				-- Stale and out of retries: remove any traces of the job
				redis.call('zRem',kAbandoned,id)
				redis.call('hDel',kAttempts,id)
				redis.call('hDel',kData,id)
				pruned = pruned + 1
			end
			-- Get the list of ready delayed jobs, sorted by readiness (UNIX timestamp)
			local ids = redis.call('zRangeByScore',kDelayed,0,ARGV[4])
			-- Migrate the jobs from the "delayed" set to the "unclaimed" list
			for k,id in ipairs(ids) do
				redis.call('lPush',kUnclaimed,id)
				redis.call('zRem',kDelayed,id)
			end
			undelayed = #ids
			return {released,abandoned,pruned,undelayed}
LUA;
			$res = $conn->luaEval( $script,
				array(
					$this->getQueueKey( 'z-claimed' ), # KEYS[1]
					$this->getQueueKey( 'h-attempts' ), # KEYS[2]
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[3]
					$this->getQueueKey( 'h-data' ), # KEYS[4]
					$this->getQueueKey( 'z-abandoned' ), # KEYS[5]
					$this->getQueueKey( 'z-delayed' ), # KEYS[6]
					$now - $this->claimTTL, # ARGV[1]
					$now - self::MAX_AGE_PRUNE, # ARGV[2]
					$this->maxTries, # ARGV[3]
					$now # ARGV[4]
				),
				6 # number of first argument(s) that are keys
			);
			if ( $res ) {
				list( $released, $abandoned, $pruned, $undelayed ) = $res;
				$count += $released + $pruned + $undelayed;
				JobQueue::incrStats( 'job-recycle', $this->type, $released, $this->wiki );
				JobQueue::incrStats( 'job-abandon', $this->type, $abandoned, $this->wiki );
			}
		} catch ( RedisException $e ) {
			$this->throwRedisException( $conn, $e );
		}

		return $count;
	}

	/**
	 * @return array
	 */
	protected function doGetPeriodicTasks() {
		$periods = array( 3600 ); // standard cleanup (useful on config change)
		if ( $this->claimTTL > 0 ) {
			$periods[] = ceil( $this->claimTTL / 2 ); // avoid bad timing
		}
		if ( $this->checkDelay ) {
			$periods[] = 300; // 5 minutes
		}
		$period = min( $periods );
		$period = max( $period, 30 ); // sanity
		// Support override for faster testing
		if ( $this->maximumPeriodicTaskSeconds !== null ) {
			$period = min( $period, $this->maximumPeriodicTaskSeconds );
		}
		return array(
			'recyclePruneAndUndelayJobs' => array(
				'callback' => array( $this, 'recyclePruneAndUndelayJobs' ),
				'period'   => $period,
			)
		);
	}

	/**
	 * @param IJobSpecification $job
	 * @return array
	 */
	protected function getNewJobFields( IJobSpecification $job ) {
		return array(
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
				? wfBaseConvert( sha1( serialize( $job->getDeduplicationInfo() ) ), 16, 36, 31 )
				: '',
			'timestamp' => time() // UNIX timestamp
		);
	}

	/**
	 * @param $fields array
	 * @return Job|bool
	 */
	protected function getJobFromFields( array $fields ) {
		$title = Title::makeTitleSafe( $fields['namespace'], $fields['title'] );
		if ( $title ) {
			$job = Job::factory( $fields['type'], $title, $fields['params'] );
			$job->metadata['uuid'] = $fields['uuid'];

			return $job;
		}

		return false;
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
			$object = (object)array( 'blob' => gzdeflate( $blob ), 'enc' => 'gzip' );
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
		$conn = $this->redisPool->getConnection( $this->server );
		if ( !$conn ) {
			throw new JobQueueConnectionError( "Unable to connect to redis server." );
		}

		return $conn;
	}

	/**
	 * @param $conn RedisConnRef
	 * @param $e RedisException
	 * @throws JobQueueError
	 */
	protected function throwRedisException( RedisConnRef $conn, $e ) {
		$this->redisPool->handleError( $conn, $e );
		throw new JobQueueError( "Redis server error: {$e->getMessage()}\n" );
	}

	/**
	 * @param $prop string
	 * @param $type string|null
	 * @return string
	 */
	private function getQueueKey( $prop, $type = null ) {
		$type = is_string( $type ) ? $type : $this->type;
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		if ( strlen( $this->key ) ) { // namespaced queue (for testing)
			return wfForeignMemcKey( $db, $prefix, 'jobqueue', $type, $this->key, $prop );
		} else {
			return wfForeignMemcKey( $db, $prefix, 'jobqueue', $type, $prop );
		}
	}

	/**
	 * @param $key string
	 * @return void
	 */
	public function setTestingPrefix( $key ) {
		$this->key = $key;
	}
}
