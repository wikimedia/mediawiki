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

	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed
	const MAX_ATTEMPTS  = 3; // integer; number of times to try a job

	/**
	 * @params include:
	 *   - redisConf : Redis configuration; see RedisBagOStuff::__construct()
	 * @param array $params
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$this->redis = ObjectCache::newFromParams(
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
				$ok = ( call_user_func_array( array( $conn, 'lPush' ), $params ) > 0 );
				wfIncrStats( 'job-insert', count( $items ) );
				return $ok;
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
			if ( $this->claimTTL > 0 && mt_rand( 0, 99 ) == 0 ) {
				// Periodically recycle jobs that were claimed for too long
				$this->recycleStaleJobs();
			}
			do {
				if ( $this->claimTTL > 0 ) {
					// Atomically pop an item off the queue and onto the "claimed" list
					$item = $conn->rpoplpush(
						$this->getQueueKey( 'unclaimed' ), $this->getQueueKey( 'claimed' ) );
				} else {
					// Atomically pop an item off the queue
					$item = $conn->rPop( $this->getQueueKey( 'unclaimed' ) );
				}
				if ( is_array( $item ) ) { // found a job
					wfIncrStats( 'job-pop' );
					$job = $this->getJobFromFields( $item );
					if ( !$job && $this->claimTTL > 0 ) { // remove if invalid
						$conn->lRem( $this->getQueueKey( 'claimed' ), $item, -1 );
					}
				} else {
					break; // nothing to do
				}
			} while ( !$job ); // job may be false if invalid
		} catch ( RedisException $e ) {
			$this->throwException( $server, $e );
		}

		// Flag this job as an old duplicate based on its "root" job...
		if ( $job && $this->isRootJobOldDuplicate( $job ) ) {
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
				// Remove the first instance of this job scanning right-to-left.
				// This is O(N) in the worst case, but is likely to be much faster since
				// jobs are pushed to the left and we are starting from the right, where
				// the longest running jobs are likely to be. These should be the first
				// jobs to be acknowledged assuming that job run times are roughly equal.
				if ( $conn->lRem( $this->getQueueKey( 'claimed' ), $item, -1 ) > 0 ) {
					return true;
				} else {
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
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 * @throws MWException
	 */
	protected function recycleStaleJobs() {
		list( $server, $conn ) = $this->getConnection();

		$cKey = $this->getQueueKey( 'claimed' );
		$uKey = $this->getQueueKey( 'unclaimed' );

		$count = 0;
		// Avoid duplication insertions of items to be re-enqueued
		if ( !$this->redis->add( "$cKey:lock", 1, 3600 ) ) { // lock
			return $count; // already in progress
		}
		try {
			$claimCutoff = time() - $this->claimTTL;
			$pruneCutoff = time() - self::MAX_AGE_PRUNE;
			// For each job item that can be retried, we need to add it back to the
			// main queue and remove it from the list of currenty claimed job items.
			$itemsRemove = array(); // items to remove from "claimed" queue
			$itemsInsert = array(); // items to add back to the "unclaimed" queue
			foreach ( $conn->lRange( $cKey, 0, -1 ) as $item ) { // all claimed items
				if ( $item['timestamp'] < $claimCutoff ) { // item claimed for too long
					if ( $item['attempts'] < self::MAX_ATTEMPTS ) {
						$itemsRemove[] = $item;
						++$item['attempts'];
						$itemsInsert[] = $item;
					} elseif ( $item['timestamp'] < $pruneCutoff ) {
						$itemsRemove[] = $item; // just remove it
					}
				}
			}
			$conn->multi( Redis::MULTI ); // begin (atomic trx)
			if ( count( $itemsInsert ) ) { // copy jobs to unclaimed queue
				$params = array_merge( array( $uKey ), $itemsInsert );
				call_user_func_array( array( $conn, 'lPush' ), $params );
			}
			foreach ( $itemsRemove as $item ) { // remove jobs from claimed queue
				$conn->lRem( $cKey, $item, -1 ); // items likely to be near end
			}
			$res = $conn->exec(); // commit (atomic trx)
			if ( in_array( false, $res, true ) ) {
				trigger_error( __METHOD__ . ": Could not recycle {$this->type} job(s)." );
			} else {
				$count += ( count( $itemsInsert ) + count( $itemsRemove ) );
			}
		} catch ( RedisException $e ) {
			$this->redis->delete( "$cKey:lock" ); // unlock
			$this->throwException( $server, $e );
		}
		$this->redis->delete( "$cKey:lock" ); // unlock

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
