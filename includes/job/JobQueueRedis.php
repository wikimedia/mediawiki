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
	/** @var RedisBagOStuff */
	protected $redis;
	/** @var Array */
	protected $servers; // list of redis server addresses

	const REDIST_PERIOD = 3600; // integer; seconds between redistribution checks
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed
	const MAX_ATTEMPTS  = 3; // integer; number of times to try a job

	/**
	 * @params include:
	 *   - redisConf : Redis configuration; see RedisBagOStuff::__construct()
	 * @param array $params
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$this->servers = $params['redisConf']['servers'];
		$this->redis   = ObjectCache::newFromParams(
			array( 'class' => 'RedisBagOStuff' ) + $params['redisConf'] );
	}

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 * @throws MWException
	 */
	protected function doIsEmpty() {
		list( $server, $conn ) = $this->getConnection();
		try {
			return ( $conn->lSize( $this->getQueueKey( 'unclaimed' ) ) == 0 );
		} catch ( RedisException $e ) {
			$this->throwException( $server, $e );
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
		if ( count( $jobs ) ) {
			list( $server, $conn ) = $this->getConnection();
			$items = array_map( array( $this, 'getNewJobFields' ), $jobs );
			try {
				$params = array_merge( array( $this->getQueueKey( 'unclaimed' ) ), $items );
				$newSize = call_user_func_array( array( $conn, 'lPush' ), $params );
				wfIncrStats( 'job-insert', count( $items ) );
				return ( $newSize > 0 );
			} catch ( RedisException $e ) {
				$this->throwException( $server, $e );
			}
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

		list( $server, $conn ) = $this->getConnection();
		try {
			// Periodically recycle jobs that were claimed for too long
			if ( $this->claimTTL > 0 && mt_rand( 0, 99 ) == 0 ) {
				$this->recycleStaleJobs();
			}
			do {
				if ( $this->claimTTL > 0 ) {
					// Atomically pop an item off the queue and onto the "claimed" list
					$item = $conn->rpoplpush(
						$this->getQueueKey( 'unclaimed' ),
						$this->getQueueKey( 'claimed' )
					);
				} else {
					// Atomically pop an item off the queue
					$item = $conn->rPop( $this->getQueueKey( 'unclaimed' ) );
				}
				if ( !is_array( $item ) ) {
					break; // no jobs; nothing to do
				}
				wfIncrStats( 'job-pop' );
				$job = $this->getJobFromFields( $item );
				if ( $this->claimTTL > 0 ) {
					if ( $job ) { // job was valid
						// Set the claim timestamp metadata. If this step fails, then
						// the timestamp will be assumed to be the current timestamp by
						// recycleStaleJobs() as of the next time that function runs.
						$conn->hSet( $this->getQueueKey( 'claim-times' ), $item['uuid'], time() );
					} else { // remove if invalid
						$conn->lRem( $this->getQueueKey( 'claimed' ), $item, -1 );
					}
				}
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwException( $server, $e );
		}

		// Periodically check to see if jobs need to be moved around servers
		if ( ( !$job || mt_rand( 0, 99 ) == 0 ) && $this->redistributionNeeded() ) {
			$this->redistribute();
		}

		// Flag this job as an old duplicate based on its "root" job...
		if ( $job && $this->isRootJobOldDuplicate( $job ) ) {
			wfIncrStats( 'job-duplicate' );
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
			list( $server, $conn ) = $this->getConnection();
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
				$conn->lRem( $this->getQueueKey( 'claimed' ), $item, -1 );
				// Delete the claim timestamp metadata
				$conn->hDel( $this->getQueueKey( 'claim-times' ), $item['uuid'] );
				$res = $conn->exec(); // commit (atomic trx)

				if ( in_array( false, $res, true ) ) {
					trigger_error( __METHOD__ . ": Could not acknowledge {$this->type} job." );
					return false;
				}
			} catch ( RedisException $e ) {
				$this->throwException( $server, $e );
			}
		}
		return true;
	}

	/**
	 * @see JobQueue::redistribute()
	 * @return integer
	 */
	protected function doRedistribute() {
		list( $cServer, $cConn ) = $this->getConnection(); // connection to correct server

		$count = 0;
		// Avoid duplication insertions of items to be re-enqueued
		if ( !$this->redis->add( $this->getQueueKey( 'lock' ), 1, 3600 ) ) { // lock
			return $count; // already in progress
		}
		try {
			$batchSize = 200;
			// Check if any keys are on the wrong server and migrate data if needed.
			// Since there are only 3 keys per queue, we can just scan each server.
			// This is O(N), where N is the number of servers, but should be simple.
			foreach ( $this->servers as $server ) { // for each server
				if ( $server === $cServer ) {
					continue; // this is the right server for this queue
				}
				$sConn = $this->redis->getConnectionToServer( $server );
				// Move all redis lists over to the proper place...
				foreach ( array( 'claimed', 'unclaimed' ) as $type ) {
					$key = $this->getQueueKey( $type );
					while ( $sConn->lSize( $key ) > 0 ) { // subqueue exists on wrong server
						$items = $sConn->lRange( $key, 0, $batchSize - 1 );
						// Copy this batch of items to the queue on the right server
						$params = array_merge( array( $key ), $items );
						$newSize = call_user_func_array( array( $cConn, 'lPush' ), $params );
						// Remove the items from the queue on the wrong server
						if ( $newSize > 0 ) {
							$sConn->lTrim( $key, $batchSize, -1 );
						} else {
							throw new MWException( "Could not migrate items for $key." );
						}
					} // redis will delete the empty list automatically
				}
				// Move all redis hashes over to the proper place...
				foreach ( array( 'claim-times' ) as $type ) {
					$key = $this->getQueueKey( $type );
					$map = $sConn->hGetAll( $key );
					if ( count( $map ) ) {
						// Copy over all the old key/values to the right server
						if ( $cConn->hMSet( $key, $map ) ) {
							// Delete all the old key/values from the wrong server
							$params = array_merge( array( $key ), array_keys( $map ) );
							call_user_func_array( array( $sConn, 'hDel' ), $params );
						} else {
							throw new MWException( "Could not migrate items for $key." );
						}
					}
				}
			}
			// Update server distribution key to mark it as "up to date"
			$hash = sha1( serialize( $this->servers ) );
			$this->redis->set( $this->getQueueKey( 'distribution' ), $hash, self::REDIST_PERIOD );
		} catch ( RedisException $e ) {
			$this->redis->delete( $this->getQueueKey( 'lock' ) ); // unlock
			$this->throwException( $cServer, $e );
		}
		$this->redis->delete( $this->getQueueKey( 'lock' ) ); // unlock

		return $count;
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
		$timestamp = $this->redis->get( $key ); // current last timestamp of this job
		if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
			return true; // a newer version of this root job was enqueued
		}

		// Update the timestamp of the last root job started at the location...
		return $this->redis->set( $key, $params['rootJobTimestamp'], 14*86400 ); // 2 weeks
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
			trigger_error( "Cannot check root job; missing 'rootJobTimestamp'." );
			return false;
		}

		// Get the last time this root job was enqueued
		$timestamp = $this->redis->get( $this->getRootJobKey( $params['rootJobSignature'] ) );

		// Check if a new root job was started at the location after this one's...
		return ( $timestamp && $timestamp > $params['rootJobTimestamp'] );
	}

	/**
	 * Check if servers may have been added or removed, thus changing the distribution.
	 * Running redistribute() will move misplaced jobs around to their new proper locations.
	 *
	 * @return bool Server distribution changed
	 */
	protected function redistributionNeeded() {
		if ( count( $this->servers ) <= 1 ) {
			return false; // if servers were just removed, nothing can be done anyway
		}
		$hash = sha1( serialize( $this->servers ) );
		return ( $this->redis->get( $this->getQueueKey( 'distribution') ) !== $hash );
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	protected function recycleStaleJobs() {
		list( $server, $conn ) = $this->getConnection();

		$count = 0;
		// Avoid duplication insertions of items to be re-enqueued
		if ( !$this->redis->add( $this->getQueueKey( 'lock' ), 1, 3600 ) ) { // lock
			return $count; // already in progress
		}
		try {
			$claimCutoff = time() - $this->claimTTL;
			$pruneCutoff = time() - self::MAX_AGE_PRUNE;
			// For each job item that can be retried, we need to add it back to the
			// main queue and remove it from the list of currenty claimed job items.
			$itemsRemove = array(); // items to remove from "claimed" queue
			$itemsInsert = array(); // items to add back to the "unclaimed" queue

			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			// Get the list of all claimed jobs
			$conn->lRange( $this->getQueueKey( 'claimed' ), 0, -1 );
			// Get a map of (uuid => claim timestamp) for all claimed jobs
			$conn->hGetAll( $this->getQueueKey( 'claim-times' ) );
			list( $claimTimes, $claimedItems ) = $conn->exec(); // commit (atomic trx)

			foreach ( $claimedItems as $item ) { // all claimed items
				// Claimed job has no "claim timestamp" set?
				if ( !isset( $claimTimes[$item['uuid']] ) ) {
					// If pop() failed to set the claim timestamp, set it to the current time.
					// Since that function sets this non-atomically *after* moving the job to
					// the "claimed" queue, it may be the case that it just didn't set it yet.
					// This is handled by having this function use an atomic funtion to set
					// the hash key only if unset and having pop() set the hash key regardless.
					$conn->hSetNx( $this->getQueueKey( 'claim-times' ), $item['uuid'], time() );
				// Claimed job claimed for too long?
				} elseif ( $claimTimes[$item['uuid']] < $claimCutoff ) {
					if ( $item['attempts'] < self::MAX_ATTEMPTS ) { // can be retried
						$itemsRemove[] = $item;
						++$item['attempts'];
						$itemsInsert[] = $item;
					} elseif ( $claimTimes[$item['uuid']] < $pruneCutoff ) {
						$itemsRemove[] = $item; // just remove it
					}
				}
			}

			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			if ( count( $itemsInsert ) ) { // copy jobs to unclaimed queue
				$params = array_merge( array( $this->getQueueKey( 'unclaimed' ) ), $itemsInsert );
				call_user_func_array( array( $conn, 'lPush' ), $params );
			}
			foreach ( $itemsRemove as $item ) { // remove jobs from claimed queue
				$conn->lRem( $this->getQueueKey( 'claimed' ), $item, -1 ); // item likely near end
				$conn->hDel( $this->getQueueKey( 'claim-times' ), $item['uuid'] );
			}
			$res = $conn->exec(); // commit (atomic trx)

			if ( in_array( false, $res, true ) ) {
				trigger_error( __METHOD__ . ": Could not recycle {$this->type} job(s)." );
			} else {
				$count += ( count( $itemsInsert ) + count( $itemsRemove ) );
			}
		} catch ( RedisException $e ) {
			$this->redis->delete( $this->getQueueKey( 'lock' ) ); // unlock
			$this->throwException( $server, $e );
		}
		$this->redis->delete( $this->getQueueKey( 'lock' ) ); // unlock

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
			'uuid'      => wfRandomString( 32 ),
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
		list( $server, $conn ) = $this->redis->getConnection( $this->getQueueKey( '' ) );
		if ( $server === false || !$conn ) {
			throw new MWException( "Unable to connect to redis server." );
		}
		return array( $server, $conn );
	}

	/**
	 * @param $server string
	 * @param $e RedisException
	 * @throws MWException
	 */
	protected function throwException( $server, $e ) {
		$this->redis->handleException( $server, $e );
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
}
