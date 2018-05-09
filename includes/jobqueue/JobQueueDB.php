<?php
/**
 * Database-backed job queue code.
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
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBError;
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

/**
 * Class to handle job queues stored in the DB
 *
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueDB extends JobQueue {
	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed
	const MAX_JOB_RANDOM = 2147483647; // integer; 2^31 - 1, used for job_random
	const MAX_OFFSET = 255; // integer; maximum number of rows to skip

	/** @var WANObjectCache */
	protected $cache;

	/** @var bool|string Name of an external DB cluster. False if not set */
	protected $cluster = false;

	/**
	 * Additional parameters include:
	 *   - cluster : The name of an external cluster registered via LBFactory.
	 *               If not specified, the primary DB cluster for the wiki will be used.
	 *               This can be overridden with a custom cluster so that DB handles will
	 *               be retrieved via LBFactory::getExternalLB() and getConnection().
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );

		$this->cluster = isset( $params['cluster'] ) ? $params['cluster'] : false;
		$this->cache = ObjectCache::getMainWANInstance();
	}

	protected function supportedOrders() {
		return [ 'random', 'timestamp', 'fifo' ];
	}

	protected function optimalOrder() {
		return 'random';
	}

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 */
	protected function doIsEmpty() {
		$dbr = $this->getReplicaDB();
		try {
			$found = $dbr->selectField( // unclaimed job
				'job', '1', [ 'job_cmd' => $this->type, 'job_token' => '' ], __METHOD__
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}

		return !$found;
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return int
	 */
	protected function doGetSize() {
		$key = $this->getCacheKey( 'size' );

		$size = $this->cache->get( $key );
		if ( is_int( $size ) ) {
			return $size;
		}

		try {
			$dbr = $this->getReplicaDB();
			$size = (int)$dbr->selectField( 'job', 'COUNT(*)',
				[ 'job_cmd' => $this->type, 'job_token' => '' ],
				__METHOD__
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}
		$this->cache->set( $key, $size, self::CACHE_TTL_SHORT );

		return $size;
	}

	/**
	 * @see JobQueue::doGetAcquiredCount()
	 * @return int
	 */
	protected function doGetAcquiredCount() {
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}

		$key = $this->getCacheKey( 'acquiredcount' );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$dbr = $this->getReplicaDB();
		try {
			$count = (int)$dbr->selectField( 'job', 'COUNT(*)',
				[ 'job_cmd' => $this->type, "job_token != {$dbr->addQuotes( '' )}" ],
				__METHOD__
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}
		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	/**
	 * @see JobQueue::doGetAbandonedCount()
	 * @return int
	 * @throws MWException
	 */
	protected function doGetAbandonedCount() {
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}

		$key = $this->getCacheKey( 'abandonedcount' );

		$count = $this->cache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$dbr = $this->getReplicaDB();
		try {
			$count = (int)$dbr->selectField( 'job', 'COUNT(*)',
				[
					'job_cmd' => $this->type,
					"job_token != {$dbr->addQuotes( '' )}",
					"job_attempts >= " . $dbr->addQuotes( $this->maxTries )
				],
				__METHOD__
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}

		$this->cache->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 * @throws DBError|Exception
	 * @return void
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		$dbw = $this->getMasterDB();
		// In general, there will be two cases here:
		// a) sqlite; DB connection is probably a regular round-aware handle.
		// If the connection is busy with a transaction, then defer the job writes
		// until right before the main round commit step. Any errors that bubble
		// up will rollback the main commit round.
		// b) mysql/postgres; DB connection is generally a separate CONN_TRX_AUTOCOMMIT handle.
		// No transaction is active nor will be started by writes, so enqueue the jobs
		// now so that any errors will show up immediately as the interface expects. Any
		// errors that bubble up will rollback the main commit round.
		$fname = __METHOD__;
		$dbw->onTransactionPreCommitOrIdle(
			function ( IDatabase $dbw ) use ( $jobs, $flags, $fname ) {
				$this->doBatchPushInternal( $dbw, $jobs, $flags, $fname );
			},
			$fname
		);
	}

	/**
	 * This function should *not* be called outside of JobQueueDB
	 *
	 * @param IDatabase $dbw
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 * @param string $method
	 * @throws DBError
	 * @return void
	 */
	public function doBatchPushInternal( IDatabase $dbw, array $jobs, $flags, $method ) {
		if ( !count( $jobs ) ) {
			return;
		}

		$rowSet = []; // (sha1 => job) map for jobs that are de-duplicated
		$rowList = []; // list of jobs for jobs that are not de-duplicated
		foreach ( $jobs as $job ) {
			$row = $this->insertFields( $job );
			if ( $job->ignoreDuplicates() ) {
				$rowSet[$row['job_sha1']] = $row;
			} else {
				$rowList[] = $row;
			}
		}

		if ( $flags & self::QOS_ATOMIC ) {
			$dbw->startAtomic( $method ); // wrap all the job additions in one transaction
		}
		try {
			// Strip out any duplicate jobs that are already in the queue...
			if ( count( $rowSet ) ) {
				$res = $dbw->select( 'job', 'job_sha1',
					[
						// No job_type condition since it's part of the job_sha1 hash
						'job_sha1' => array_keys( $rowSet ),
						'job_token' => '' // unclaimed
					],
					$method
				);
				foreach ( $res as $row ) {
					wfDebug( "Job with hash '{$row->job_sha1}' is a duplicate.\n" );
					unset( $rowSet[$row->job_sha1] ); // already enqueued
				}
			}
			// Build the full list of job rows to insert
			$rows = array_merge( $rowList, array_values( $rowSet ) );
			// Insert the job rows in chunks to avoid replica DB lag...
			foreach ( array_chunk( $rows, 50 ) as $rowBatch ) {
				$dbw->insert( 'job', $rowBatch, $method );
			}
			JobQueue::incrStats( 'inserts', $this->type, count( $rows ) );
			JobQueue::incrStats( 'dupe_inserts', $this->type,
				count( $rowSet ) + count( $rowList ) - count( $rows )
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}
		if ( $flags & self::QOS_ATOMIC ) {
			$dbw->endAtomic( $method );
		}

		return;
	}

	/**
	 * @see JobQueue::doPop()
	 * @return Job|bool
	 */
	protected function doPop() {
		$dbw = $this->getMasterDB();
		try {
			$autoTrx = $dbw->getFlag( DBO_TRX ); // get current setting
			$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
			$scopedReset = new ScopedCallback( function () use ( $dbw, $autoTrx ) {
				$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore old setting
			} );

			$uuid = wfRandomString( 32 ); // pop attempt
			$job = false; // job popped off
			do { // retry when our row is invalid or deleted as a duplicate
				// Try to reserve a row in the DB...
				if ( in_array( $this->order, [ 'fifo', 'timestamp' ] ) ) {
					$row = $this->claimOldest( $uuid );
				} else { // random first
					$rand = mt_rand( 0, self::MAX_JOB_RANDOM ); // encourage concurrent UPDATEs
					$gte = (bool)mt_rand( 0, 1 ); // find rows with rand before/after $rand
					$row = $this->claimRandom( $uuid, $rand, $gte );
				}
				// Check if we found a row to reserve...
				if ( !$row ) {
					break; // nothing to do
				}
				JobQueue::incrStats( 'pops', $this->type );
				// Get the job object from the row...
				$title = Title::makeTitle( $row->job_namespace, $row->job_title );
				$job = Job::factory( $row->job_cmd, $title,
					self::extractBlob( $row->job_params ), $row->job_id );
				$job->metadata['id'] = $row->job_id;
				$job->metadata['timestamp'] = $row->job_timestamp;
				break; // done
			} while ( true );

			if ( !$job || mt_rand( 0, 9 ) == 0 ) {
				// Handled jobs that need to be recycled/deleted;
				// any recycled jobs will be picked up next attempt
				$this->recycleAndDeleteStaleJobs();
			}
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}

		return $job;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param string $uuid 32 char hex string
	 * @param int $rand Random unsigned integer (31 bits)
	 * @param bool $gte Search for job_random >= $random (otherwise job_random <= $random)
	 * @return stdClass|bool Row|false
	 */
	protected function claimRandom( $uuid, $rand, $gte ) {
		$dbw = $this->getMasterDB();
		// Check cache to see if the queue has <= OFFSET items
		$tinyQueue = $this->cache->get( $this->getCacheKey( 'small' ) );

		$row = false; // the row acquired
		$invertedDirection = false; // whether one job_random direction was already scanned
		// This uses a replication safe method for acquiring jobs. One could use UPDATE+LIMIT
		// instead, but that either uses ORDER BY (in which case it deadlocks in MySQL) or is
		// not replication safe. Due to https://bugs.mysql.com/bug.php?id=6980, subqueries cannot
		// be used here with MySQL.
		do {
			if ( $tinyQueue ) { // queue has <= MAX_OFFSET rows
				// For small queues, using OFFSET will overshoot and return no rows more often.
				// Instead, this uses job_random to pick a row (possibly checking both directions).
				$ineq = $gte ? '>=' : '<=';
				$dir = $gte ? 'ASC' : 'DESC';
				$row = $dbw->selectRow( 'job', self::selectFields(), // find a random job
					[
						'job_cmd' => $this->type,
						'job_token' => '', // unclaimed
						"job_random {$ineq} {$dbw->addQuotes( $rand )}" ],
					__METHOD__,
					[ 'ORDER BY' => "job_random {$dir}" ]
				);
				if ( !$row && !$invertedDirection ) {
					$gte = !$gte;
					$invertedDirection = true;
					continue; // try the other direction
				}
			} else { // table *may* have >= MAX_OFFSET rows
				// T44614: "ORDER BY job_random" with a job_random inequality causes high CPU
				// in MySQL if there are many rows for some reason. This uses a small OFFSET
				// instead of job_random for reducing excess claim retries.
				$row = $dbw->selectRow( 'job', self::selectFields(), // find a random job
					[
						'job_cmd' => $this->type,
						'job_token' => '', // unclaimed
					],
					__METHOD__,
					[ 'OFFSET' => mt_rand( 0, self::MAX_OFFSET ) ]
				);
				if ( !$row ) {
					$tinyQueue = true; // we know the queue must have <= MAX_OFFSET rows
					$this->cache->set( $this->getCacheKey( 'small' ), 1, 30 );
					continue; // use job_random
				}
			}

			if ( $row ) { // claim the job
				$dbw->update( 'job', // update by PK
					[
						'job_token' => $uuid,
						'job_token_timestamp' => $dbw->timestamp(),
						'job_attempts = job_attempts+1' ],
					[ 'job_cmd' => $this->type, 'job_id' => $row->job_id, 'job_token' => '' ],
					__METHOD__
				);
				// This might get raced out by another runner when claiming the previously
				// selected row. The use of job_random should minimize this problem, however.
				if ( !$dbw->affectedRows() ) {
					$row = false; // raced out
				}
			} else {
				break; // nothing to do
			}
		} while ( !$row );

		return $row;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param string $uuid 32 char hex string
	 * @return stdClass|bool Row|false
	 */
	protected function claimOldest( $uuid ) {
		$dbw = $this->getMasterDB();

		$row = false; // the row acquired
		do {
			if ( $dbw->getType() === 'mysql' ) {
				// Per https://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
				// same table being changed in an UPDATE query in MySQL (gives Error: 1093).
				// Oracle and Postgre have no such limitation. However, MySQL offers an
				// alternative here by supporting ORDER BY + LIMIT for UPDATE queries.
				$dbw->query( "UPDATE {$dbw->tableName( 'job' )} " .
					"SET " .
						"job_token = {$dbw->addQuotes( $uuid ) }, " .
						"job_token_timestamp = {$dbw->addQuotes( $dbw->timestamp() )}, " .
						"job_attempts = job_attempts+1 " .
					"WHERE ( " .
						"job_cmd = {$dbw->addQuotes( $this->type )} " .
						"AND job_token = {$dbw->addQuotes( '' )} " .
					") ORDER BY job_id ASC LIMIT 1",
					__METHOD__
				);
			} else {
				// Use a subquery to find the job, within an UPDATE to claim it.
				// This uses as much of the DB wrapper functions as possible.
				$dbw->update( 'job',
					[
						'job_token' => $uuid,
						'job_token_timestamp' => $dbw->timestamp(),
						'job_attempts = job_attempts+1' ],
					[ 'job_id = (' .
						$dbw->selectSQLText( 'job', 'job_id',
							[ 'job_cmd' => $this->type, 'job_token' => '' ],
							__METHOD__,
							[ 'ORDER BY' => 'job_id ASC', 'LIMIT' => 1 ] ) .
						')'
					],
					__METHOD__
				);
			}
			// Fetch any row that we just reserved...
			if ( $dbw->affectedRows() ) {
				$row = $dbw->selectRow( 'job', self::selectFields(),
					[ 'job_cmd' => $this->type, 'job_token' => $uuid ], __METHOD__
				);
				if ( !$row ) { // raced out by duplicate job removal
					wfDebug( "Row deleted as duplicate by another process.\n" );
				}
			} else {
				break; // nothing to do
			}
		} while ( !$row );

		return $row;
	}

	/**
	 * @see JobQueue::doAck()
	 * @param Job $job
	 * @throws MWException
	 */
	protected function doAck( Job $job ) {
		if ( !isset( $job->metadata['id'] ) ) {
			throw new MWException( "Job of type '{$job->getType()}' has no ID." );
		}

		$dbw = $this->getMasterDB();
		try {
			$autoTrx = $dbw->getFlag( DBO_TRX ); // get current setting
			$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
			$scopedReset = new ScopedCallback( function () use ( $dbw, $autoTrx ) {
				$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore old setting
			} );

			// Delete a row with a single DELETE without holding row locks over RTTs...
			$dbw->delete( 'job',
				[ 'job_cmd' => $this->type, 'job_id' => $job->metadata['id'] ], __METHOD__ );

			JobQueue::incrStats( 'acks', $this->type );
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
	 * @param IJobSpecification $job
	 * @throws MWException
	 * @return bool
	 */
	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		$params = $job->getParams();
		if ( !isset( $params['rootJobSignature'] ) ) {
			throw new MWException( "Cannot register root job; missing 'rootJobSignature'." );
		} elseif ( !isset( $params['rootJobTimestamp'] ) ) {
			throw new MWException( "Cannot register root job; missing 'rootJobTimestamp'." );
		}
		$key = $this->getRootJobCacheKey( $params['rootJobSignature'] );
		// Callers should call batchInsert() and then this function so that if the insert
		// fails, the de-duplication registration will be aborted. Since the insert is
		// deferred till "transaction idle", do the same here, so that the ordering is
		// maintained. Having only the de-duplication registration succeed would cause
		// jobs to become no-ops without any actual jobs that made them redundant.
		$dbw = $this->getMasterDB();
		$cache = $this->dupCache;
		$dbw->onTransactionCommitOrIdle(
			function () use ( $cache, $params, $key ) {
				$timestamp = $cache->get( $key ); // current last timestamp of this job
				if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
					return true; // a newer version of this root job was enqueued
				}

				// Update the timestamp of the last root job started at the location...
				return $cache->set( $key, $params['rootJobTimestamp'], JobQueueDB::ROOTJOB_TTL );
			},
			__METHOD__
		);

		return true;
	}

	/**
	 * @see JobQueue::doDelete()
	 * @return bool
	 */
	protected function doDelete() {
		$dbw = $this->getMasterDB();
		try {
			$dbw->delete( 'job', [ 'job_cmd' => $this->type ] );
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}

		return true;
	}

	/**
	 * @see JobQueue::doWaitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->waitForReplication( [ 'wiki' => $this->wiki, 'cluster' => $this->cluster ] );
	}

	/**
	 * @return void
	 */
	protected function doFlushCaches() {
		foreach ( [ 'size', 'acquiredcount' ] as $type ) {
			$this->cache->delete( $this->getCacheKey( $type ) );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllQueuedJobs() {
		return $this->getJobIterator( [ 'job_cmd' => $this->getType(), 'job_token' => '' ] );
	}

	/**
	 * @see JobQueue::getAllAcquiredJobs()
	 * @return Iterator
	 */
	public function getAllAcquiredJobs() {
		return $this->getJobIterator( [ 'job_cmd' => $this->getType(), "job_token > ''" ] );
	}

	/**
	 * @param array $conds Query conditions
	 * @return Iterator
	 */
	protected function getJobIterator( array $conds ) {
		$dbr = $this->getReplicaDB();
		try {
			return new MappedIterator(
				$dbr->select( 'job', self::selectFields(), $conds ),
				function ( $row ) {
					$job = Job::factory(
						$row->job_cmd,
						Title::makeTitle( $row->job_namespace, $row->job_title ),
						strlen( $row->job_params ) ? unserialize( $row->job_params ) : []
					);
					$job->metadata['id'] = $row->job_id;
					$job->metadata['timestamp'] = $row->job_timestamp;

					return $job;
				}
			);
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}
	}

	public function getCoalesceLocationInternal() {
		return $this->cluster
			? "DBCluster:{$this->cluster}:{$this->wiki}"
			: "LBFactory:{$this->wiki}";
	}

	protected function doGetSiblingQueuesWithJobs( array $types ) {
		$dbr = $this->getReplicaDB();
		// @note: this does not check whether the jobs are claimed or not.
		// This is useful so JobQueueGroup::pop() also sees queues that only
		// have stale jobs. This lets recycleAndDeleteStaleJobs() re-enqueue
		// failed jobs so that they can be popped again for that edge case.
		$res = $dbr->select( 'job', 'DISTINCT job_cmd',
			[ 'job_cmd' => $types ], __METHOD__ );

		$types = [];
		foreach ( $res as $row ) {
			$types[] = $row->job_cmd;
		}

		return $types;
	}

	protected function doGetSiblingQueueSizes( array $types ) {
		$dbr = $this->getReplicaDB();
		$res = $dbr->select( 'job', [ 'job_cmd', 'COUNT(*) AS count' ],
			[ 'job_cmd' => $types ], __METHOD__, [ 'GROUP BY' => 'job_cmd' ] );

		$sizes = [];
		foreach ( $res as $row ) {
			$sizes[$row->job_cmd] = (int)$row->count;
		}

		return $sizes;
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return int Number of jobs recycled/deleted
	 */
	public function recycleAndDeleteStaleJobs() {
		$now = time();
		$count = 0; // affected rows
		$dbw = $this->getMasterDB();

		try {
			if ( !$dbw->lock( "jobqueue-recycle-{$this->type}", __METHOD__, 1 ) ) {
				return $count; // already in progress
			}

			// Remove claims on jobs acquired for too long if enabled...
			if ( $this->claimTTL > 0 ) {
				$claimCutoff = $dbw->timestamp( $now - $this->claimTTL );
				// Get the IDs of jobs that have be claimed but not finished after too long.
				// These jobs can be recycled into the queue by expiring the claim. Selecting
				// the IDs first means that the UPDATE can be done by primary key (less deadlocks).
				$res = $dbw->select( 'job', 'job_id',
					[
						'job_cmd' => $this->type,
						"job_token != {$dbw->addQuotes( '' )}", // was acquired
						"job_token_timestamp < {$dbw->addQuotes( $claimCutoff )}", // stale
						"job_attempts < {$dbw->addQuotes( $this->maxTries )}" ], // retries left
					__METHOD__
				);
				$ids = array_map(
					function ( $o ) {
						return $o->job_id;
					}, iterator_to_array( $res )
				);
				if ( count( $ids ) ) {
					// Reset job_token for these jobs so that other runners will pick them up.
					// Set the timestamp to the current time, as it is useful to now that the job
					// was already tried before (the timestamp becomes the "released" time).
					$dbw->update( 'job',
						[
							'job_token' => '',
							'job_token_timestamp' => $dbw->timestamp( $now ) ], // time of release
						[
							'job_id' => $ids ],
						__METHOD__
					);
					$affected = $dbw->affectedRows();
					$count += $affected;
					JobQueue::incrStats( 'recycles', $this->type, $affected );
					$this->aggr->notifyQueueNonEmpty( $this->wiki, $this->type );
				}
			}

			// Just destroy any stale jobs...
			$pruneCutoff = $dbw->timestamp( $now - self::MAX_AGE_PRUNE );
			$conds = [
				'job_cmd' => $this->type,
				"job_token != {$dbw->addQuotes( '' )}", // was acquired
				"job_token_timestamp < {$dbw->addQuotes( $pruneCutoff )}" // stale
			];
			if ( $this->claimTTL > 0 ) { // only prune jobs attempted too many times...
				$conds[] = "job_attempts >= {$dbw->addQuotes( $this->maxTries )}";
			}
			// Get the IDs of jobs that are considered stale and should be removed. Selecting
			// the IDs first means that the UPDATE can be done by primary key (less deadlocks).
			$res = $dbw->select( 'job', 'job_id', $conds, __METHOD__ );
			$ids = array_map(
				function ( $o ) {
					return $o->job_id;
				}, iterator_to_array( $res )
			);
			if ( count( $ids ) ) {
				$dbw->delete( 'job', [ 'job_id' => $ids ], __METHOD__ );
				$affected = $dbw->affectedRows();
				$count += $affected;
				JobQueue::incrStats( 'abandons', $this->type, $affected );
			}

			$dbw->unlock( "jobqueue-recycle-{$this->type}", __METHOD__ );
		} catch ( DBError $e ) {
			$this->throwDBException( $e );
		}

		return $count;
	}

	/**
	 * @param IJobSpecification $job
	 * @return array
	 */
	protected function insertFields( IJobSpecification $job ) {
		$dbw = $this->getMasterDB();

		return [
			// Fields that describe the nature of the job
			'job_cmd' => $job->getType(),
			'job_namespace' => $job->getTitle()->getNamespace(),
			'job_title' => $job->getTitle()->getDBkey(),
			'job_params' => self::makeBlob( $job->getParams() ),
			// Additional job metadata
			'job_timestamp' => $dbw->timestamp(),
			'job_sha1' => Wikimedia\base_convert(
				sha1( serialize( $job->getDeduplicationInfo() ) ),
				16, 36, 31
			),
			'job_random' => mt_rand( 0, self::MAX_JOB_RANDOM )
		];
	}

	/**
	 * @throws JobQueueConnectionError
	 * @return DBConnRef
	 */
	protected function getReplicaDB() {
		try {
			return $this->getDB( DB_REPLICA );
		} catch ( DBConnectionError $e ) {
			throw new JobQueueConnectionError( "DBConnectionError:" . $e->getMessage() );
		}
	}

	/**
	 * @throws JobQueueConnectionError
	 * @return DBConnRef
	 */
	protected function getMasterDB() {
		try {
			return $this->getDB( DB_MASTER );
		} catch ( DBConnectionError $e ) {
			throw new JobQueueConnectionError( "DBConnectionError:" . $e->getMessage() );
		}
	}

	/**
	 * @param int $index (DB_REPLICA/DB_MASTER)
	 * @return DBConnRef
	 */
	protected function getDB( $index ) {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lb = ( $this->cluster !== false )
			? $lbFactory->getExternalLB( $this->cluster )
			: $lbFactory->getMainLB( $this->wiki );

		return ( $lb->getServerType( $lb->getWriterIndex() ) !== 'sqlite' )
			// Keep a separate connection to avoid contention and deadlocks;
			// However, SQLite has the opposite behavior due to DB-level locking.
			? $lb->getConnectionRef( $index, [], $this->wiki, $lb::CONN_TRX_AUTOCOMMIT )
			// Jobs insertion will be defered until the PRESEND stage to reduce contention.
			: $lb->getConnectionRef( $index, [], $this->wiki );
	}

	/**
	 * @param string $property
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		$cluster = is_string( $this->cluster ) ? $this->cluster : 'main';

		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $cluster, $this->type, $property );
	}

	/**
	 * @param array|bool $params
	 * @return string
	 */
	protected static function makeBlob( $params ) {
		if ( $params !== false ) {
			return serialize( $params );
		} else {
			return '';
		}
	}

	/**
	 * @param string $blob
	 * @return bool|mixed
	 */
	protected static function extractBlob( $blob ) {
		if ( (string)$blob !== '' ) {
			return unserialize( $blob );
		} else {
			return false;
		}
	}

	/**
	 * @param DBError $e
	 * @throws JobQueueError
	 */
	protected function throwDBException( DBError $e ) {
		throw new JobQueueError( get_class( $e ) . ": " . $e->getMessage() );
	}

	/**
	 * Return the list of job fields that should be selected.
	 * @since 1.23
	 * @return array
	 */
	public static function selectFields() {
		return [
			'job_id',
			'job_cmd',
			'job_namespace',
			'job_title',
			'job_timestamp',
			'job_params',
			'job_random',
			'job_attempts',
			'job_token',
			'job_token_timestamp',
			'job_sha1',
		];
	}
}
