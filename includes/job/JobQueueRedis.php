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
 * There are six main redis key names used to track jobs:
 *   - l-unclaimed  : A list of job IDs used for push/pop
 *   - h-idBySha1   : A hash of (SHA1 => job ID) for unclaimed jobs used for de-duplication
 *   - h-sha1Byid   : A hash of (job ID => SHA1) for unclaimed jobs used for de-duplication
 *   - h-claimed    : A hash of (job ID => UNIX timestamp) used for job claiming/retries
 *   - h-attempts   : A hash of (job ID => attempt count) used for job claiming/retries
 *   - h-data       : A hash of (job ID => serialized blobs) for job storage
 *
 * Additionally, "rootjob:* keys to track "root jobs" used for additional de-duplication.
 * Aside from root job keys, all keys have no expiry, and are only removed when jobs are run.
 * All the keys are prefixed with the relevant wiki ID information.
 *
 * This requires Redis 2.6 as it makes use Lua scripts for fast atomic operations.
 * Additionally, it should be noted that redis has different persistence modes, such
 * as rdb snapshots, journaling, and no persistent. Appropriate configuration should be
 * made on the servers based on what queues are using it and what tolerance they have.
 *
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueRedis extends JobQueue {
	/** @var RedisConnectionPool */
	protected $redisPool;

	protected $server; // string; server address

	const ROOTJOB_TTL = 1209600; // integer; seconds to remember root jobs (14 days)
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed (7 days)

	protected $key; // string; key to prefix the queue keys with (used for testing)

	/**
	 * @params include:
	 *   - redisConfig : An array of parameters to RedisConnectionPool::__construct().
	 *                   Note that the serializer option is ignored "none" is always used.
	 *   - redisServer : A hostname/port combination or the absolute path of a UNIX socket.
	 *                   If a hostname is specified but no port, the standard port number
	 *                   6379 will be used. Required.
	 * @param array $params
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$params['redisConfig']['serializer'] = 'none'; // make it easy to use Lua
		$this->server = $params['redisServer'];
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConfig'] );
	}

	protected function supportedOrders() {
		return array( 'timestamp', 'fifo' );
	}

	protected function optimalOrder() {
		return 'fifo';
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
	 * @return integer
	 * @throws MWException
	 */
	protected function doGetSize() {
		$conn = $this->getConnection();
		try {
			return $conn->lSize( $this->getQueueKey( 'l-unclaimed' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doGetAcquiredCount()
	 * @return integer
	 * @throws MWException
	 */
	protected function doGetAcquiredCount() {
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}
		$conn = $this->getConnection();
		try {
			return $conn->hLen( $this->getQueueKey( 'h-claimed' ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param array $jobs
	 * @param $flags
	 * @return bool
	 * @throws MWException
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
		// Convert the field maps into serialized blobs
		$blobs = array(); // (job ID => string)
		$sha1ById = array(); // (job ID => SHA1) (this should be 1:1)
		foreach ( $items as $item ) {
			$blobs[$item['uuid']] = serialize( $item );
			if ( strlen( $item['sha1'] ) ) {
				$sha1ById[$item['uuid']] = $item['sha1'];
			}
		}

		if ( !count( $blobs ) ) {
			return true; // nothing to do
		}

		$conn = $this->getConnection();
		try {
			// Actually push the non-duplicate jobs into the queue...
			$res = $this->pushBlobs( $conn, $blobs, $sha1ById );
			if ( !$res ) {
				wfDebugLog( 'JobQueueRedis', "Could not insert {$this->type} job(s)." );
				return false;
			}
			list( $pushed, $ignored ) = $res;
			wfIncrStats( 'job-insert', count( $blobs ) );
			wfIncrStats( 'job-insert-duplicate', count( $blobs ) - $pushed );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		return true;
	}

	/**
	 * @param RedisConnRef $conn
	 * @return array serialized string or false
	 * @throws RedisException
	 */
	protected function pushBlobs( RedisConnRef $conn, array $blobs, array $sha1ById ) {
		$args = array(); // ([id, sha1, blob [, id, sha1, blob ... ] ] )
		foreach ( $blobs as $id => $blob ) {
			$args[] = $id;
			$args[] = isset( $sha1ById[$id] ) ? $sha1ById[$id] : '';
			$args[] = $blob;
		}
		return $conn->eval(
			"if #ARGV % 3 ~= 0 then return redis.error_reply('Unmatched arguments') end\n" .
			"local pushed,ignored = 0,0\n" .
			"for i = 1,#ARGV,3 do\n" .
			"	local id,sha1,blob = ARGV[i],ARGV[i+1],ARGV[i+2]\n" .
			"	if sha1 ~= '' and redis.call('hExists',KEYS[3],sha1) ~= 0 then\n" .
			"		ignored = ignored + 1\n" . // duplicate job
			"	else\n" .
			"		redis.call('lPush',KEYS[1],id)\n" .
			"		if sha1 ~= '' then\n" .
			"			redis.call('hSet',KEYS[2],id,sha1)\n" .
			"			redis.call('hSet',KEYS[3],sha1,id)\n" .
			"		end\n" .
			"		redis.call('hSet',KEYS[4],id,blob)\n" .
			"		pushed = pushed + 1\n" .
			"	end\n" .
			"end\n" .
			"return {pushed,ignored}\n",
			array_merge(
				array(
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
					$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
					$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
					$this->getQueueKey( 'h-data' ), # KEYS[4]
				),
				$args
			),
			4 # number of first argument(s) that are keys
		);
	}

	/**
	 * @see JobQueue::doPop()
	 * @return Job|bool
	 * @throws MWException
	 */
	protected function doPop() {
		$job = false;

		$conn = $this->getConnection();
		try {
			do {
				if ( $this->claimTTL > 0 ) {
					$blob = $this->popAndAcquireBlob( $conn );
				} else {
					$blob = $this->popAndDeleteBlob( $conn );
				}
				if ( $blob === false ) {
					break; // no jobs; nothing to do
				}

				wfIncrStats( 'job-pop' );
				$item = unserialize( $blob );
				if ( $item === false ) {
					wfDebugLog( 'JobQueueRedis', "Could not unserialize {$this->type} job." );
					continue;
				}

				// If $item is invalid, recycleAndDeleteStaleJobs() will cleanup as needed
				$job = $this->getJobFromFields( $item ); // may be false
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		// Flag this job as an old duplicate based on its "root" job...
		try {
			if ( $job && $this->isRootJobOldDuplicate( $job ) ) {
				wfIncrStats( 'job-pop-duplicate' );
				return DuplicateJob::newFromJob( $job ); // convert to a no-op
			}
		} catch ( MWException $e ) {} // don't lose jobs over this

		return $job;
	}

	/**
	 * @param RedisConnRef $conn
	 * @return array serialized string or false
	 * @throws RedisException
	 */
	protected function popAndDeleteBlob( RedisConnRef $conn ) {
		return $conn->eval(
			// Pop an item off the queue
			"local id = redis.call('rpop',KEYS[1])\n" .
			"if not id then return false end\n" .
			// Get the job data and remove it
			"local item = redis.call('hGet',KEYS[4],id)\n" .
			"redis.call('hDel',KEYS[4],id)\n" .
			// Allow new duplicates of this job
			"local sha1 = redis.call('hGet',KEYS[2],id)\n" .
			"if sha1 then redis.call('hDel',KEYS[3],sha1) end\n" .
			"redis.call('hDel',KEYS[2],id)\n" .
			// Return the job data
			"return item",
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
		return $conn->eval(
			// Pop an item off the queue
			"local id = redis.call('rPop',KEYS[1])\n" .
			"if not id then return false end\n" .
			// Allow new duplicates of this job
			"local sha1 = redis.call('hGet',KEYS[2],id)\n" .
			"if sha1 then redis.call('hDel',KEYS[3],sha1) end\n" .
			"redis.call('hDel',KEYS[2],id)\n" .
			// Mark the jobs as claimed and return it
			"redis.call('hSet',KEYS[4],id,ARGV[1])\n" .
			"redis.call('hIncrBy',KEYS[5],id,1)\n" .
			"return redis.call('hGet',KEYS[6],id)",
			array(
				$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
				$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
				$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
				$this->getQueueKey( 'h-claimed' ), # KEYS[4]
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
	 * @throws MWException
	 */
	protected function doAck( Job $job ) {
		if ( $this->claimTTL > 0 ) {
			$conn = $this->getConnection();
			try {
				// Get the exact field map this Job came from, regardless of whether
				// the job was transformed into a DuplicateJob or anything of the sort.
				$item = $job->metadata['sourceFields'];

				$res = $conn->eval(
					// Unmark the job as claimed
					"redis.call('hDel',KEYS[1],ARGV[1])\n" .
					"redis.call('hDel',KEYS[2],ARGV[1])\n" .
					// Delete the job data itself
					"return redis.call('hDel',KEYS[3],ARGV[1])\n",
					array(
						$this->getQueueKey( 'h-claimed' ), # KEYS[1]
						$this->getQueueKey( 'h-attempts' ), # KEYS[2]
						$this->getQueueKey( 'h-data' ), # KEYS[3]
						$item['uuid'] # ARGV[1]
					),
					3 # number of first argument(s) that are keys
				);

				if ( !$res ) {
					wfDebugLog( 'JobQueueRedis', "Could not acknowledge {$this->type} job." );
					return false;
				}
			} catch ( RedisException $e ) {
				$this->throwRedisException( $this->server, $conn, $e );
			}
		}
		return true;
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
	 * @param Job $job
	 * @return bool
	 * @throws MWException
	 */
	protected function doDeduplicateRootJob( Job $job ) {
		$params = $job->getParams();
		if ( !isset( $params['rootJobSignature'] ) ) {
			throw new MWException( "Cannot register root job; missing 'rootJobSignature'." );
		} elseif ( !isset( $params['rootJobTimestamp'] ) ) {
			throw new MWException( "Cannot register root job; missing 'rootJobTimestamp'." );
		}
		$key = $this->getRootJobKey( $params['rootJobSignature'] );

		$conn = $this->getConnection();
		try {
			$timestamp = $conn->get( $key ); // current last timestamp of this job
			if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
				return true; // a newer version of this root job was enqueued
			}
			// Update the timestamp of the last root job started at the location...
			return $conn->set( $key, $params['rootJobTimestamp'], self::ROOTJOB_TTL ); // 2 weeks
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * Check if the "root" job of a given job has been superseded by a newer one
	 *
	 * @param $job Job
	 * @return bool
	 * @throws MWException
	 */
	protected function isRootJobOldDuplicate( Job $job ) {
		$params = $job->getParams();
		if ( !isset( $params['rootJobSignature'] ) ) {
			return false; // job has no de-deplication info
		} elseif ( !isset( $params['rootJobTimestamp'] ) ) {
			wfDebugLog( 'JobQueueRedis', "Cannot check root job; missing 'rootJobTimestamp'." );
			return false;
		}

		$conn = $this->getConnection();
		try {
			// Get the last time this root job was enqueued
			$timestamp = $conn->get( $this->getRootJobKey( $params['rootJobSignature'] ) );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		// Check if a new root job was started at the location after this one's...
		return ( $timestamp && $timestamp > $params['rootJobTimestamp'] );
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllQueuedJobs() {
		$conn = $this->getConnection();
		if ( !$conn ) {
			throw new MWException( "Unable to connect to redis server." );
		}
		try {
			$that = $this;
			return new MappedIterator(
				$conn->lRange( $this->getQueueKey( 'l-unclaimed' ), 0, -1 ),
				function( $uid ) use ( $that, $conn ) {
					return $that->getJobFromUidInternal( $uid, $conn );
				}
			);
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * This function should not be called outside RedisJobQueue
	 *
	 * @param $uid string
	 * @param $conn RedisConnRef
	 * @return Job
	 * @throws MWException
	 */
	public function getJobFromUidInternal( $uid, RedisConnRef $conn ) {
		try {
			$item = unserialize( $conn->hGet( $this->getQueueKey( 'h-data' ), $uid ) );
			if ( !is_array( $item ) ) { // this shouldn't happen
				$conn->hDel( $this->getQueueKey( 'h-data' ), $uid ); // garbage
				throw new MWException( "Could not find job with ID '$uid'." );
			}
			$title = Title::makeTitle( $item['namespace'], $item['title'] );
			$job = Job::factory( $item['type'], $title, $item['params'] );
			$job->metadata['sourceFields'] = $item;
			return $job;
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	public function recycleAndDeleteStaleJobs() {
		if ( $this->claimTTL <= 0 ) { // sanity
			throw new MWException( "Cannot recycle jobs since acknowledgements are disabled." );
		}
		$count = 0;
		// For each job item that can be retried, we need to add it back to the
		// main queue and remove it from the list of currenty claimed job items.
		$conn = $this->getConnection();
		try {
			// Avoid duplicate insertions of items to be re-enqueued
			$conn->multi( Redis::MULTI );
			$conn->setnx( $this->getQueueKey( 'lock' ), 1 );
			$conn->expire( $this->getQueueKey( 'lock' ), 3600 );
			if ( $conn->exec() !== array( true, true ) ) { // lock
				return $count; // already in progress
			}

			$now = time();
			list( $released, $pruned ) = $conn->eval(
				"local released,pruned = 0,0\n" .
				"local idsClaimTimes = redis.call('hGetAll',KEYS[1])\n" .
				"for id,timestamp in ipairs(idsClaimTimes) do\n" .
				"	local attempts = redis.call('hGet',KEYS[2],id)\n" .
				"	if attempts >= ARGV[3] and timestamp <= ARGV[2] then\n" .
						// Stale and out of retries: remove any traces of the job
				"		redis.call('hDel',KEYS[1],id)\n" .
				"		redis.call('hDel',KEYS[2],id)\n" .
				"		redis.call('hDel',KEYS[4],id)\n" .
				"		pruned = pruned + 1\n" .
				"	elseif attempts < ARGV[3] and timestamp <= ARGV[1] then\n" .
						// Claim expired and retries left: re-enqueue the job
				"		redis.call('lPush',KEYS[3],id)\n" .
				"		redis.call('hDel',KEYS[1],id)\n" .
				"		redis.call('hIncrBy',KEYS[2],id,1)\n" .
				"		released = released + 1\n" .
				"	end\n" .
				"end\n" .
				"return {released,pruned}",
				array(
					$this->getQueueKey( 'h-claimed' ), # KEYS[1]
					$this->getQueueKey( 'h-attempts' ), # KEYS[2]
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[3]
					$this->getQueueKey( 'h-data' ), # KEYS[4]
					$now - $this->claimTTL, # ARGV[1]
					$now - self::MAX_AGE_PRUNE, # ARGV[2]
					$this->maxTries # ARGV[3]
				),
				4 # number of first argument(s) that are keys
			);

			$count += $released + $pruned;
			wfIncrStats( 'job-recycle', count( $released ) );

			$conn->delete( $this->getQueueKey( 'lock' ) ); // unlock
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		return $count;
	}

	/**
	 * @return Array
	 */
	protected function doGetPeriodicTasks() {
		if ( $this->claimTTL > 0 ) {
			return array(
				'recycleAndDeleteStaleJobs' => array(
					'callback' => array( $this, 'recycleAndDeleteStaleJobs' ),
					'period'   => ceil( $this->claimTTL / 2 )
				)
			);
		} else {
			return array();
		}
	}

	/**
	 * @param $job Job
	 * @return array
	 */
	protected function getNewJobFields( Job $job ) {
		return array(
			// Fields that describe the nature of the job
			'type'      => $job->getType(),
			'namespace' => $job->getTitle()->getNamespace(),
			'title'     => $job->getTitle()->getDBkey(),
			'params'    => $job->getParams(),
			// Additional job metadata
			'uuid'      => UIDGenerator::newRawUUIDv4( UIDGenerator::QUICK_RAND ),
			'sha1'      => $job->ignoreDuplicates()
				? wfBaseConvert( sha1( serialize( $job->getDeduplicationInfo() ) ), 16, 36, 31 )
				: null,
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
			$job->metadata['sourceFields'] = $fields;
			return $job;
		}
		return false;
	}

	/**
	 * Get a connection to the server that handles all sub-queues for this queue
	 *
	 * @return Array (server name, Redis instance)
	 * @throws MWException
	 */
	protected function getConnection() {
		$conn = $this->redisPool->getConnection( $this->server );
		if ( !$conn ) {
			throw new MWException( "Unable to connect to redis server." );
		}
		return $conn;
	}

	/**
	 * @param $server string
	 * @param $conn RedisConnRef
	 * @param $e RedisException
	 * @throws MWException
	 */
	protected function throwRedisException( $server, RedisConnRef $conn, $e ) {
		$this->redisPool->handleException( $server, $conn, $e );
		throw new MWException( "Redis server error: {$e->getMessage()}\n" );
	}

	/**
	 * @param $prop string
	 * @return string
	 */
	private function getQueueKey( $prop ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		if ( strlen( $this->key ) ) { // namespaced queue (for testing)
			return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $this->key, $prop );
		} else {
			return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $prop );
		}
	}

	/**
	 * @param string $signature Hash identifier of the root job
	 * @return string
	 */
	private function getRootJobKey( $signature ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, 'rootjob', $signature );
	}

	/**
	 * @param $key string
	 * @return void
	 */
	public function setTestingPrefix( $key ) {
		$this->key = $key;
	}
}
