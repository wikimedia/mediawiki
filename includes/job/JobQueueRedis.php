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

	const ROOTJOB_TTL   = 1209600; // integer; seconds to remember root jobs (14 days)
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed (7 days)
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
		if ( mt_rand( 0, 99 ) == 0 ) {
			$this->doInternalMaintenance();
		}

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
	 * @throws MWException
	 */
	protected function doGetSize() {
		if ( mt_rand( 0, 99 ) == 0 ) {
			$this->doInternalMaintenance();
		}

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
		if ( mt_rand( 0, 99 ) == 0 ) {
			$this->doInternalMaintenance();
		}

		$conn = $this->getConnection();
		try {
			if ( $this->claimTTL > 0 ) {
				return $conn->lSize( $this->getQueueKey( 'l-claimed' ) );
			} else {
				return 0;
			}
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
		$items = array(); // (uid => job fields map)
		foreach ( $jobs as $job ) {
			$item = $this->getNewJobFields( $job );
			$items[$item['uid']] = $item;
		}

		$dedupUids = array(); // list of uids to check for duplicates
		foreach ( $items as $item ) {
			if ( $this->isHashUid( $item['uid'] ) ) { // hash identifier => de-duplicate
				$dedupUids[] = $item['uid'];
			}
		}

		$conn = $this->getConnection();
		try {
			// Find which of these jobs are duplicates unclaimed jobs in the queue...
			if ( count( $dedupUids ) ) {
				$conn->multi( Redis::PIPELINE );
				foreach ( $dedupUids as $uid ) { // check if job data exists
					$conn->exists( $this->prefixWithQueueKey( 'data', $uid ) );
				}
				if ( $this->claimTTL > 0 ) { // check which jobs were claimed
					foreach ( $dedupUids as $uid ) {
						$conn->hExists( $this->prefixWithQueueKey( 'h-meta', $uid ), 'ctime' );
					}
					list( $exists, $claimed ) = array_chunk( $conn->exec(), count( $dedupUids ) );
				} else {
					$exists = $conn->exec();
					$claimed = array(); // no claim system
				}
				// Remove the duplicate jobs to cut down on pushing duplicate uids...
				foreach ( $dedupUids as $k => $uid ) {
					if ( $exists[$k] && empty( $claimed[$k] ) ) {
						unset( $items[$uid] );
					}
				}
			}
			// Actually push the non-duplicate jobs into the queue...
			if ( count( $items ) ) {
				$uids = array_keys( $items );
				$conn->multi( Redis::MULTI ); // begin (atomic trx)
				$conn->mSet( $this->prefixKeysWithQueueKey( 'data', $items ) );
				call_user_func_array(
					array( $conn, 'lPush' ),
					array_merge( array( $this->getQueueKey( 'l-unclaimed' ) ), $uids )
				);
				$res = $conn->exec(); // commit (atomic trx)
				if ( in_array( false, $res, true ) ) {
					wfDebugLog( 'JobQueueRedis', "Could not insert {$this->type} job(s)." );
					return false;
				}
			}
			wfIncrStats( 'job-insert', count( $items ) );
			wfIncrStats( 'job-insert-duplicate', count( $jobs ) - count( $items ) );
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

		if ( mt_rand( 0, 99 ) == 0 ) {
			$this->doInternalMaintenance();
		}

		$conn = $this->getConnection();
		try {
			do {
				// Atomically pop an item off the queue and onto the "claimed" list
				$uid = $conn->rpoplpush(
					$this->getQueueKey( 'l-unclaimed' ),
					$this->getQueueKey( 'l-claimed' )
				);
				if ( $uid === false ) {
					break; // no jobs; nothing to do
				}

				wfIncrStats( 'job-pop' );
				$conn->multi( Redis::PIPELINE );
				$conn->get( $this->prefixWithQueueKey( 'data', $uid ) );
				if ( $this->claimTTL > 0 ) {
					// Set the claim timestamp metadata. If this step fails, then
					// the timestamp will be assumed to be the current timestamp by
					// recycleAndDeleteStaleJobs() as of the next time that it runs.
					// If two runners claim duplicate jobs, one will abort here.
					$conn->hSetNx( $this->prefixWithQueueKey( 'h-meta', $uid ), 'ctime', time() );
				} else {
					// If this fails, the message key will be deleted in cleanupClaimedJobs().
					// If two runners claim duplicate jobs, one of them will abort here.
					$conn->delete(
						$this->prefixWithQueueKey( 'h-meta', $uid ),
						$this->prefixWithQueueKey( 'data', $uid ) );
				}
				list( $item, $ok ) = $conn->exec();
				if ( $item === false || ( $this->claimTTL && !$ok ) ) {
					wfDebug( "Could not find or delete job $uid; probably was a duplicate." );
					continue; // job was probably a duplicate
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
				// Delete the job data and its claim metadata
				$conn->delete(
					$this->prefixWithQueueKey( 'h-meta', $item['uid'] ),
					$this->prefixWithQueueKey( 'data', $item['uid'] ) );
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
	 * Do any job recycling or queue cleanup as needed
	 *
	 * @return void
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	protected function doInternalMaintenance() {
		return ( $this->claimTTL > 0 ) ?
			$this->recycleAndDeleteStaleJobs() : $this->cleanupClaimedJobs();
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	protected function recycleAndDeleteStaleJobs() {
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
			$claimCutoff = $now - $this->claimTTL;
			$pruneCutoff = $now - self::MAX_AGE_PRUNE;

			// Get the list of all claimed jobs
			$claimedUids = $conn->lRange( $this->getQueueKey( 'l-claimed' ), 0, -1 );
			// Get a map of (uid => claim metadata) for all claimed jobs
			$metadata = $conn->mGet( $this->prefixValuesWithQueueKey( 'h-meta', $claimedUids ) );

			$uidsPush = array(); // items IDs to move to the "unclaimed" queue
			$uidsRemove = array(); // item IDs to remove from "claimed" queue
			foreach ( $claimedUids as $i => $uid ) { // all claimed items
				$info = $metadata[$i] ? $metadata[$i] : array();
				if ( isset( $info['ctime'] ) || isset( $info['rctime'] ) ) {
					// Prefer "ctime" (set by pop()) over "rctime" (set by this function)
					$ctime = isset( $info['ctime'] ) ? $info['ctime'] : $info['rctime'];
					// Claimed job claimed for too long?
					if ( $ctime < $claimCutoff ) {
						// Get the number of failed attempts
						$attempts = isset( $info['attempts'] ) ? $info['attempts'] : 0;
						if ( $attempts < self::MAX_ATTEMPTS ) {
							$uidsPush[] = $uid; // retry it
						} elseif ( $ctime < $pruneCutoff ) {
							$uidsRemove[] = $uid; // just remove it
						}
					}
				} else {
					// If pop() failed to set the claim timestamp, set it to the current time.
					// Since that function sets this non-atomically *after* moving the job to
					// the "claimed" queue, it may be the case that it just didn't set it yet.
					$conn->hSet( $this->prefixWithQueueKey( 'h-meta', $uid ), 'rctime', $now );
				}
			}

			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			if ( count( $uidsPush ) ) { // move from "l-claimed" to "l-unclaimed"
				call_user_func_array(
					array( $conn, 'lPush' ),
					array_merge( array( $this->getQueueKey( 'l-unclaimed' ) ), $uidsPush )
				);
				foreach ( $uidsPush as $uid ) {
					$conn->lRem( $this->getQueueKey( 'l-claimed' ), $uid, -1 );
					$conn->hDel( $this->prefixWithQueueKey( 'h-meta', $uid ), 'ctime', 'rctime' );
					$conn->hIncrBy( $this->prefixWithQueueKey( 'h-meta', $uid ), 'attempts', 1 );
				}
			}
			foreach ( $uidsRemove as $uid ) { // remove from "l-claimed"
				$conn->lRem( $this->getQueueKey( 'l-claimed' ), $uid, -1 );
				$conn->delete( // delete job data and metadata
					$this->prefixWithQueueKey( 'h-meta', $uid ),
					$this->prefixWithQueueKey( 'data', $uid ) );
			}
			$res = $conn->exec(); // commit (atomic trx)

			if ( in_array( false, $res, true ) ) {
				wfDebugLog( 'JobQueueRedis', "Could not recycle {$this->type} job(s)." );
			} else {
				$count += ( count( $uidsPush ) + count( $uidsRemove ) );
				wfIncrStats( 'job-recycle', count( $uidsPush ) );
			}

			$conn->delete( $this->getQueueKey( 'lock' ) ); // unlock
		} catch ( RedisException $e ) {
			$this->throwRedisException( $this->server, $conn, $e );
		}

		return $count;
	}

	/**
	 * Destroy any jobs that have been claimed
	 *
	 * @return integer Number of jobs deleted
	 * @throws MWException
	 */
	protected function cleanupClaimedJobs() {
		$count = 0;
		// Make sure the message for claimed jobs was deleted
		// and remove the claimed job IDs from the "claimed" list.
		$conn = $this->getConnection();
		try {
			// Avoid races and duplicate effort
			$conn->multi( Redis::MULTI );
			$conn->setnx( $this->getQueueKey( 'lock' ), 1 );
			$conn->expire( $this->getQueueKey( 'lock' ), 3600 );
			if ( $conn->exec() !== array( true, true ) ) { // lock
				return $count; // already in progress
			}
			// Get the list of all claimed jobs
			$uids = $conn->lRange( $this->getQueueKey( 'l-claimed' ), 0, -1 );
			if ( count( $uids ) ) {
				// Delete the message keys and delist the corresponding ids.
				// Since the only other changes to "l-claimed" are left pushes, we can just strip
				// off the elements read here using a right trim based on the number of ids read.
				$conn->multi( Redis::MULTI ); // begin (atomic trx)
				$conn->lTrim( $this->getQueueKey( 'l-claimed' ), 0, -count( $uids ) - 1 );
				$conn->delete( array_merge(
					$this->prefixValuesWithQueueKey( 'h-meta', $uids ),
					$this->prefixValuesWithQueueKey( 'data', $uids )
				) );
				$res = $conn->exec(); // commit (atomic trx)

				if ( in_array( false, $res, true ) ) {
					wfDebugLog( 'JobQueueRedis', "Could not purge {$this->type} job(s)." );
				} else {
					$count += count( $uids );
				}
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
	 * @param $uid string Job UID
	 * @return bool Whether $uid is a SHA-1 hash based identifier for de-duplication
	 */
	protected function isHashUid( $uid ) {
		return strlen( $uid ) == 31;
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
	private function prefixValuesWithQueueKey( $prop, array $items ) {
		$res = array();
		foreach ( $items as $item ) {
			$res[] = $this->prefixWithQueueKey( $prop, $item );
		}
		return $res;
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
