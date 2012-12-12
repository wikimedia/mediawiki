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
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueRedis extends JobQueue {
	/** @var RedisConnectionPool */
	protected $redisPool;

	protected $server; // string; server address

	const MAX_JOB_AGE   = 15724800; // integer; seconds a job can live (182*86400)
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed
	const MAX_ATTEMPTS  = 3; // integer; number of times to try a job

	/**
	 * @params include:
	 *   - redisConf : An array of parameters to RedisConnectionPool::__construct().
	 *   - server    : A hostname/port combination or the absolute path of a UNIX socket.
	 *                 If a hostname is specified but no port, the standard port number
	 *                 6379 will be used. Required.
	 * @param array $params
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$this->server = $params['redisConf']['server'];
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConf'] );
	}

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 * @throws MWException
	 */
	protected function doIsEmpty() {
		$conn = $this->getConnection();
		try {
			return ( $conn->lSize( $this->getQueueKey( 'l-unclaimed' ) ) == 0 );
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return integer
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
	 */
	protected function doGetAcquiredCount() {
		$conn = $this->getConnection();
		try {
			return $conn->lSize( $this->getQueueKey( 'l-claimed' ) );
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
		if ( !count( $jobs ) ) {
			return true;
		}

		// Convert the jobs into a list of field maps
		$items = array(); // (job sha1 or uid => job fields map)
		foreach ( $jobs as $job ) {
			$item = $this->getNewJobFields( $job );
			$items[$item['uid']] = $item;
		}

		$conn = $this->getConnection();
		try {
			// Actually insert the jobs
			if ( $flags & self::QoS_Atomic ) {
				$conn->multi( Redis::MULTI ); // begin (atomic trx)
			} else {
				$conn->multi( Redis::PIPELINE ); // start
			}
			foreach ( $this->prefixKeysWithQueueKey( 'data', $items ) as $key => $item ) {
				$conn->setex( $key, self::MAX_JOB_AGE, $item );
			}
			call_user_func_array(
				array( $conn, 'lPush' ),
				array_merge( array( $this->getQueueKey( 'l-unclaimed' ) ), array_keys( $items ) )
			);
			$res = $conn->exec(); // finish (commit any atomic trx)

			if ( in_array( false, $res, true ) ) {
				wfDebugLog( 'JobQueueRedis', "Could not insert {$this->type} job(s)." );
				return false;
			} else {
				wfIncrStats( 'job-insert', count( $items ) );
			}
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		return true;
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
			// Periodically recycle jobs that were claimed for too long
			if ( $this->claimTTL > 0 && mt_rand( 0, 99 ) == 0 ) {
				$this->recycleStaleJobs();
			}
			do {
				if ( $this->claimTTL > 0 ) {
					// Atomically pop an item off the queue and onto the "claimed" list
					$uid = $conn->rpoplpush(
						$this->getQueueKey( 'l-unclaimed' ),
						$this->getQueueKey( 'l-claimed' )
					);
				} else {
					// Atomically pop an item off the queue
					$uid = $conn->rPop( $this->getQueueKey( 'l-unclaimed' ) );
				}
				if ( $uid === false ) {
					break; // no jobs; nothing to do
				}

				wfIncrStats( 'job-pop' );
				$conn->multi( Redis::PIPELINE );
				$conn->get( $this->prefixWithQueueKey( 'data', $uid ) );
				if ( $this->claimTTL > 0 ) {
					// Set the claim timestamp metadata. If this step fails, then
					// the timestamp will be assumed to be the current timestamp by
					// recycleStaleJobs() as of the next time that function runs.
					// If two runners claim duplicate jobs, one will abort here.
					$conn->hSetNx( $this->getQueueKey( 'h-claimtimes' ), $uid, time() );
				} else {
					// If this fails, the useless data key will eventually expire.
					// If two runners claim duplicate jobs, one will abort here.
					$conn->delete( $this->prefixWithQueueKey( 'data', $uid ) );
				}
				list( $item, $ok ) = $conn->exec();
				if ( $item === false || ( $this->claimTTL && !$ok ) ) {
					continue; // job was probably a duplicate
				}

				$job = $this->getJobFromFields( $item );
				if ( !$job && $this->claimTTL > 0 ) { // remove if invalid
					$conn->multi( Redis::MULTI ); // begin (atomic trx)
					$conn->lRem( $this->getQueueKey( 'l-claimed' ), $item, -1 );
					$conn->hDel( $this->getQueueKey( 'h-claimtimes' ), $uid );
					$conn->delete( $this->prefixWithQueueKey( 'data', $uid ) );
					$res = $conn->exec(); // commit (atomic trx)
				}
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		// Flag this job as an old duplicate based on its "root" job...
		if ( $job && $this->isRootJobOldDuplicate( $job ) ) {
			wfIncrStats( 'job-pop-duplicate' );
			return DuplicateJob::newFromJob( $job ); // convert to a no-op
		} else {
			return $job;
		}
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

				$conn->multi( Redis::MULTI ); // begin (atomic trx)
				// Remove the first instance of this job scanning right-to-left.
				// This is O(N) in the worst case, but is likely to be much faster since
				// jobs are pushed to the left and we are starting from the right, where
				// the longest running jobs are likely to be. These should be the first
				// jobs to be acknowledged assuming that job run times are roughly equal.
				$conn->lRem( $this->getQueueKey( 'l-claimed' ), $item['uid'], -1 );
				// Delete the claim timestamp metadata
				$conn->hDel( $this->getQueueKey( 'h-claimtimes' ), $item['uid'] );
				// Delete the job message itself
				$conn->delete( $this->prefixWithQueueKey( 'data', $item['uid'] ) );
				$res = $conn->exec(); // commit (atomic trx)

				if ( in_array( false, $res, true ) ) {
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
	 * @throws MWException
	 * @return bool
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
			return $conn->set( $key, $params['rootJobTimestamp'], 14*86400 ); // 2 weeks
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}
	}

	/**
	 * Check if the "root" job of a given job has been superseded by a newer one
	 *
	 * @param $job Job
	 * @return bool
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
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	protected function recycleStaleJobs() {
		$conn = $this->getConnection();

		$count = 0;
		// For each job item that can be retried, we need to add it back to the
		// main queue and remove it from the list of currenty claimed job items.
		try {
			// Avoid duplicate insertions of items to be re-enqueued
			$conn->multi( Redis::MULTI );
			$conn->setnx( $this->getQueueKey( 'lock' ), 1 );
			$conn->expire( $this->getQueueKey( 'lock' ), 3600 );
			if ( $conn->exec() !== array( true, true ) ) { // lock
				return $count; // already in progress
			}

			$claimCutoff = time() - $this->claimTTL;
			$pruneCutoff = time() - self::MAX_AGE_PRUNE;

			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			// Get the list of all claimed jobs
			$conn->lRange( $this->getQueueKey( 'l-claimed' ), 0, -1 );
			// Get a map of (uid => claim timestamp) for all claimed jobs
			$conn->hGetAll( $this->getQueueKey( 'h-claimtimes' ) );
			list( $claimedUids, $claimTimes ) = $conn->exec(); // commit (atomic trx)

			$uidsRemove = array(); // item IDs to remove from "claimed" queue
			$itemsAdd = array(); // items to add to the "unclaimed" queue
			foreach ( $claimedUids as $uid ) { // all claimed items
				$item = $conn->get( $this->prefixWithQueueKey( 'data', $uid ) );
				// Job is in list but is not defined?
				if ( !is_array( $item ) ) {
					$uidsRemove[] = $uid; // just remove it
				// Claimed job has no "claim timestamp" set?
				} elseif ( !isset( $claimTimes[$uid] ) ) {
					// If pop() failed to set the claim timestamp, set it to the current time.
					// Since that function sets this non-atomically *after* moving the job to
					// the "claimed" queue, it may be the case that it just didn't set it yet.
					$conn->hSetNx( $this->getQueueKey( 'h-claimtimes' ), $uid, time() );
				// Claimed job claimed for too long?
				} elseif ( $claimTimes[$uid] < $claimCutoff ) {
					if ( $item['attempts'] < self::MAX_ATTEMPTS ) { // can be retried
						$uidsRemove[] = $uid;
						++$item['attempts'];
						$itemsAdd[$uid] = $item;
					} elseif ( $claimTimes[$uid] < $pruneCutoff ) {
						$uidsRemove[] = $uid; // just remove it
					}
				}
			}

			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			if ( count( $itemsAdd ) ) { // copy jobs to unclaimed queue
				$uidsInsert = array_keys( $itemsAdd );
				foreach ( $this->prefixKeysWithQueueKey( 'data', $itemsAdd ) as $key => $item ) {
					$conn->setex( $key, self::MAX_JOB_AGE, $item );
				}
				call_user_func_array(
					array( $conn, 'lPush' ),
					array_merge( array( $this->getQueueKey( 'l-unclaimed' ) ), $uidsInsert )
				);
			}
			foreach ( $uidsRemove as $uid ) { // remove jobs from claimed queue
				$conn->lRem( $this->getQueueKey( 'l-claimed' ), $uid, -1 ); // item likely near end
				$conn->hDel( $this->getQueueKey( 'h-claimtimes' ), $uid );
			}
			$res = $conn->exec(); // commit (atomic trx)

			if ( in_array( false, $res, true ) ) {
				wfDebugLog( 'JobQueueRedis', "Could not recycle {$this->type} job(s)." );
			} else {
				$count += ( count( $itemsAdd ) + count( $uidsRemove ) );
			}
			$conn->delete( $this->getQueueKey( 'lock' ) ); // unlock
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		return $count;
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
			// Additional metadata
			'uid'       => $job->ignoreDuplicates()
				? wfBaseConvert( sha1( serialize( $job->getDeduplicationInfo() ) ), 16, 36, 31 )
				: wfRandomString( 32 ),
			'timestamp' => time(), // UNIX timestamp
			'attempts'  => 0
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
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $prop );
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
	 * @param $prop string
	 * @param $string string
	 * @return string
	 */
	private function prefixWithQueueKey( $prop, $string ) {
		return $this->getQueueKey( $prop ) . ':' . $string;
	}

	/**
	 * @param $prop string
	 * @param $items array
	 * @return Array
	 */
	private function prefixKeysWithQueueKey( $prop, array $items ) {
		$res = array();
		foreach ( $items as $key => $item ) {
			$res[$this->prefixWithQueueKey( $prop, $key )] = $item;
		}
		return $res;
	}
}
