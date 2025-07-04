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

use MappedIterator;
use MediaWiki\JobQueue\Exceptions\JobQueueConnectionError;
use MediaWiki\JobQueue\Exceptions\JobQueueError;
use MediaWiki\MediaWikiServices;
use Profiler;
use stdClass;
use UnexpectedValueException;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\ServerInfo;
use Wikimedia\ScopedCallback;

/**
 * Database-backed job queue storage.
 *
 * @since 1.21
 * @ingroup JobQueue
 */
class JobQueueDB extends JobQueue {
	/* seconds to cache info without re-validating */
	private const CACHE_TTL_SHORT = 30;
	/* seconds a job can live once claimed */
	private const MAX_AGE_PRUNE = 7 * 24 * 3600;
	/**
	 * Used for job_random, the highest safe 32-bit signed integer.
	 * Equivalent to `( 2 ** 31 ) - 1` on 64-bit.
	 */
	private const MAX_JOB_RANDOM = 2_147_483_647;
	/* maximum number of rows to skip */
	private const MAX_OFFSET = 255;

	/** @var IMaintainableDatabase|DBError|null */
	protected $conn;

	/** @var array|null Server configuration array */
	protected $server;
	/** @var string|null Name of an external DB cluster or null for the local DB cluster */
	protected $cluster;

	/**
	 * Additional parameters include:
	 *   - server  : Server configuration array for Database::factory. Overrides "cluster".
	 *   - cluster : The name of an external cluster registered via LBFactory.
	 *               If not specified, the primary DB cluster for the wiki will be used.
	 *               This can be overridden with a custom cluster so that DB handles will
	 *               be retrieved via LBFactory::getExternalLB() and getConnection().
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );

		if ( isset( $params['server'] ) ) {
			$this->server = $params['server'];
			// Always use autocommit mode, even if DBO_TRX is configured
			$this->server['flags'] ??= 0;
			$this->server['flags'] &= ~( IDatabase::DBO_TRX | IDatabase::DBO_DEFAULT );
		} elseif ( isset( $params['cluster'] ) && is_string( $params['cluster'] ) ) {
			$this->cluster = $params['cluster'];
		}
	}

	/** @inheritDoc */
	protected function supportedOrders() {
		return [ 'random', 'timestamp', 'fifo' ];
	}

	/** @inheritDoc */
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
			// unclaimed job
			$found = (bool)$dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'job' )
				->where( [ 'job_cmd' => $this->type, 'job_token' => '' ] )
				->caller( __METHOD__ )->fetchField();
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}

		return !$found;
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return int
	 */
	protected function doGetSize() {
		$key = $this->getCacheKey( 'size' );

		$size = $this->wanCache->get( $key );
		if ( is_int( $size ) ) {
			return $size;
		}

		$dbr = $this->getReplicaDB();
		try {
			$size = $dbr->newSelectQueryBuilder()
				->from( 'job' )
				->where( [ 'job_cmd' => $this->type, 'job_token' => '' ] )
				->caller( __METHOD__ )->fetchRowCount();
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}
		$this->wanCache->set( $key, $size, self::CACHE_TTL_SHORT );

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

		$count = $this->wanCache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$dbr = $this->getReplicaDB();
		try {
			$count = $dbr->newSelectQueryBuilder()
				->from( 'job' )
				->where( [
					'job_cmd' => $this->type,
					$dbr->expr( 'job_token', '!=', '' ),
				] )
				->caller( __METHOD__ )->fetchRowCount();
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}
		$this->wanCache->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	/**
	 * @see JobQueue::doGetAbandonedCount()
	 * @return int
	 * @throws JobQueueConnectionError
	 * @throws JobQueueError
	 */
	protected function doGetAbandonedCount() {
		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}

		$key = $this->getCacheKey( 'abandonedcount' );

		$count = $this->wanCache->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		$dbr = $this->getReplicaDB();
		try {
			$count = $dbr->newSelectQueryBuilder()
				->from( 'job' )
				->where(
					[
						'job_cmd' => $this->type,
						$dbr->expr( 'job_token', '!=', '' ),
						$dbr->expr( 'job_attempts', '>=', $this->maxTries ),
					]
				)
				->caller( __METHOD__ )->fetchRowCount();
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}

		$this->wanCache->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 * @throws DBError|\Exception
	 * @return void
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		// Silence expectations related to getting a primary DB, as we have to get a primary DB to insert the job.
		$transactionProfiler = Profiler::instance()->getTransactionProfiler();
		$scope = $transactionProfiler->silenceForScope();
		$dbw = $this->getPrimaryDB();
		ScopedCallback::consume( $scope );
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
			fn () => $this->doBatchPushInternal( $dbw, $jobs, $flags, $fname ),
			$fname
		);
	}

	/**
	 * This function should *not* be called outside of JobQueueDB
	 *
	 * @suppress SecurityCheck-SQLInjection Bug in phan-taint-check handling bulk inserts
	 * @param IDatabase $dbw
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 * @param string $method
	 * @throws DBError
	 * @return void
	 */
	public function doBatchPushInternal( IDatabase $dbw, array $jobs, $flags, $method ) {
		if ( $jobs === [] ) {
			return;
		}

		$rowSet = []; // (sha1 => job) map for jobs that are de-duplicated
		$rowList = []; // list of jobs for jobs that are not de-duplicated
		foreach ( $jobs as $job ) {
			$row = $this->insertFields( $job, $dbw );
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
				$res = $dbw->newSelectQueryBuilder()
					->select( 'job_sha1' )
					->from( 'job' )
					->where(
						[
							// No job_type condition since it's part of the job_sha1 hash
							'job_sha1' => array_map( 'strval', array_keys( $rowSet ) ),
							'job_token' => '' // unclaimed
						]
					)
					->caller( $method )->fetchResultSet();
				foreach ( $res as $row ) {
					wfDebug( "Job with hash '{$row->job_sha1}' is a duplicate." );
					unset( $rowSet[$row->job_sha1] ); // already enqueued
				}
			}
			// Build the full list of job rows to insert
			$rows = array_merge( $rowList, array_values( $rowSet ) );
			// Silence expectations related to inserting to the job table, because we have to perform the inserts to
			// track the job.
			$transactionProfiler = Profiler::instance()->getTransactionProfiler();
			$scope = $transactionProfiler->silenceForScope();
			// Insert the job rows in chunks to avoid replica DB lag...
			foreach ( array_chunk( $rows, 50 ) as $rowBatch ) {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'job' )
					->rows( $rowBatch )
					->caller( $method )->execute();
			}
			ScopedCallback::consume( $scope );
			$this->incrStats( 'inserts', $this->type, count( $rows ) );
			$this->incrStats( 'dupe_inserts', $this->type,
				count( $rowSet ) + count( $rowList ) - count( $rows )
			);
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}
		if ( $flags & self::QOS_ATOMIC ) {
			$dbw->endAtomic( $method );
		}
	}

	/**
	 * @see JobQueue::doPop()
	 * @return RunnableJob|false
	 */
	protected function doPop() {
		$job = false; // job popped off
		try {
			$uuid = wfRandomString( 32 ); // pop attempt
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
				$this->incrStats( 'pops', $this->type );

				// Get the job object from the row...
				$job = $this->jobFromRow( $row );
				break; // done
			} while ( true );

			if ( !$job || mt_rand( 0, 9 ) == 0 ) {
				// Handled jobs that need to be recycled/deleted;
				// any recycled jobs will be picked up next attempt
				$this->recycleAndDeleteStaleJobs();
			}
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}

		return $job;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param string $uuid 32 char hex string
	 * @param int $rand Random unsigned integer (31 bits)
	 * @param bool $gte Search for job_random >= $random (otherwise job_random <= $random)
	 * @return stdClass|false Row|false
	 */
	protected function claimRandom( $uuid, $rand, $gte ) {
		$dbw = $this->getPrimaryDB();
		// Check cache to see if the queue has <= OFFSET items
		$tinyQueue = $this->wanCache->get( $this->getCacheKey( 'small' ) );

		$invertedDirection = false; // whether one job_random direction was already scanned
		// This uses a replication safe method for acquiring jobs. One could use UPDATE+LIMIT
		// instead, but that either uses ORDER BY (in which case it deadlocks in MySQL) or is
		// not replication safe. Due to https://bugs.mysql.com/bug.php?id=6980, subqueries cannot
		// be used here with MySQL.
		do {
			if ( $tinyQueue ) { // queue has <= MAX_OFFSET rows
				// For small queues, using OFFSET will overshoot and return no rows more often.
				// Instead, this uses job_random to pick a row (possibly checking both directions).
				$row = $dbw->newSelectQueryBuilder()
					->select( self::selectFields() )
					->from( 'job' )
					->where(
						[
							'job_cmd' => $this->type,
							'job_token' => '', // unclaimed
							$dbw->expr( 'job_random', $gte ? '>=' : '<=', $rand )
						]
					)
					->orderBy(
						'job_random',
						$gte ? SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC
					)
					->caller( __METHOD__ )->fetchRow();
				if ( !$row && !$invertedDirection ) {
					$gte = !$gte;
					$invertedDirection = true;
					continue; // try the other direction
				}
			} else { // table *may* have >= MAX_OFFSET rows
				// T44614: "ORDER BY job_random" with a job_random inequality causes high CPU
				// in MySQL if there are many rows for some reason. This uses a small OFFSET
				// instead of job_random for reducing excess claim retries.
				$row = $dbw->newSelectQueryBuilder()
					->select( self::selectFields() )
					->from( 'job' )
					->where(
						[
							'job_cmd' => $this->type,
							'job_token' => '', // unclaimed
						]
					)
					->offset( mt_rand( 0, self::MAX_OFFSET ) )
					->caller( __METHOD__ )->fetchRow();
				if ( !$row ) {
					$tinyQueue = true; // we know the queue must have <= MAX_OFFSET rows
					$this->wanCache->set( $this->getCacheKey( 'small' ), 1, 30 );
					continue; // use job_random
				}
			}

			if ( !$row ) {
				break;
			}

			$dbw->newUpdateQueryBuilder()
				->update( 'job' ) // update by PK
				->set( [
					'job_token' => $uuid,
					'job_token_timestamp' => $dbw->timestamp(),
					'job_attempts' => new RawSQLValue( 'job_attempts+1' ),
				] )
				->where( [
					'job_cmd' => $this->type,
					'job_id' => $row->job_id,
					'job_token' => ''
				] )
				->caller( __METHOD__ )->execute();

			// This might get raced out by another runner when claiming the previously
			// selected row. The use of job_random should minimize this problem, however.
			if ( !$dbw->affectedRows() ) {
				$row = false; // raced out
			}
		} while ( !$row );

		return $row;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param string $uuid 32 char hex string
	 * @return stdClass|false Row|false
	 */
	protected function claimOldest( $uuid ) {
		$dbw = $this->getPrimaryDB();

		$row = false; // the row acquired
		do {
			if ( $dbw->getType() === 'mysql' ) {
				// Per https://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
				// same table being changed in an UPDATE query in MySQL (gives Error: 1093).
				// Postgres has no such limitation. However, MySQL offers an
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
				$qb = $dbw->newSelectQueryBuilder()
					->select( 'job_id' )
					->from( 'job' )
					->where( [ 'job_cmd' => $this->type, 'job_token' => '' ] )
					->orderBy( 'job_id', SelectQueryBuilder::SORT_ASC )
					->limit( 1 );

				$dbw->newUpdateQueryBuilder()
					->update( 'job' )
					->set( [
						'job_token' => $uuid,
						'job_token_timestamp' => $dbw->timestamp(),
						'job_attempts' => new RawSQLValue( 'job_attempts+1' ),
					] )
					->where( [ 'job_id' => new RawSQLValue( '(' . $qb->getSQL() . ')' ) ] )
					->caller( __METHOD__ )->execute();
			}

			if ( !$dbw->affectedRows() ) {
				break;
			}

			// Fetch any row that we just reserved...
			$row = $dbw->newSelectQueryBuilder()
				->select( self::selectFields() )
				->from( 'job' )
				->where( [ 'job_cmd' => $this->type, 'job_token' => $uuid ] )
				->caller( __METHOD__ )->fetchRow();
			if ( !$row ) { // raced out by duplicate job removal
				wfDebug( "Row deleted as duplicate by another process." );
			}
		} while ( !$row );

		return $row;
	}

	/**
	 * @see JobQueue::doAck()
	 * @param RunnableJob $job
	 * @throws JobQueueConnectionError
	 * @throws JobQueueError
	 */
	protected function doAck( RunnableJob $job ) {
		$id = $job->getMetadata( 'id' );
		if ( $id === null ) {
			throw new UnexpectedValueException( "Job of type '{$job->getType()}' has no ID." );
		}

		$dbw = $this->getPrimaryDB();
		try {
			// Delete a row with a single DELETE without holding row locks over RTTs...
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'job' )
				->where( [ 'job_cmd' => $this->type, 'job_id' => $id ] )
				->caller( __METHOD__ )->execute();

			$this->incrStats( 'acks', $this->type );
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
	 * @param IJobSpecification $job
	 * @throws JobQueueConnectionError
	 * @return bool
	 */
	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		// Callers should call JobQueueGroup::push() before this method so that if the
		// insert fails, the de-duplication registration will be aborted. Since the insert
		// is deferred till "transaction idle", do the same here, so that the ordering is
		// maintained. Having only the de-duplication registration succeed would cause
		// jobs to become no-ops without any actual jobs that made them redundant.
		$dbw = $this->getPrimaryDB();
		$dbw->onTransactionCommitOrIdle(
			function () use ( $job ) {
				parent::doDeduplicateRootJob( $job );
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
		$dbw = $this->getPrimaryDB();
		try {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'job' )
				->where( [ 'job_cmd' => $this->type ] )
				->caller( __METHOD__ )->execute();
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}

		return true;
	}

	/**
	 * @see JobQueue::doWaitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		if ( $this->server ) {
			return; // not using LBFactory instance
		}

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->waitForReplication();
	}

	/**
	 * @return void
	 */
	protected function doFlushCaches() {
		foreach ( [ 'size', 'acquiredcount' ] as $type ) {
			$this->wanCache->delete( $this->getCacheKey( $type ) );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return \Iterator<RunnableJob>
	 */
	public function getAllQueuedJobs() {
		return $this->getJobIterator( [ 'job_cmd' => $this->getType(), 'job_token' => '' ] );
	}

	/**
	 * @see JobQueue::getAllAcquiredJobs()
	 * @return \Iterator<RunnableJob>
	 */
	public function getAllAcquiredJobs() {
		$dbr = $this->getReplicaDB();
		return $this->getJobIterator( [ 'job_cmd' => $this->getType(), $dbr->expr( 'job_token', '>', '' ) ] );
	}

	/**
	 * @see JobQueue::getAllAbandonedJobs()
	 * @return \Iterator<RunnableJob>
	 */
	public function getAllAbandonedJobs() {
		$dbr = $this->getReplicaDB();
		return $this->getJobIterator( [
			'job_cmd' => $this->getType(),
			$dbr->expr( 'job_token', '>', '' ),
			$dbr->expr( 'job_attempts', '>=', intval( $this->maxTries ) ),
		] );
	}

	/**
	 * @param array $conds Query conditions
	 * @return \Iterator<RunnableJob>
	 */
	protected function getJobIterator( array $conds ) {
		$dbr = $this->getReplicaDB();
		$qb = $dbr->newSelectQueryBuilder()
			->select( self::selectFields() )
			->from( 'job' )
			->where( $conds );
		try {
			return new MappedIterator(
				$qb->caller( __METHOD__ )->fetchResultSet(),
				function ( $row ) {
					return $this->jobFromRow( $row );
				}
			);
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}
	}

	/** @inheritDoc */
	public function getCoalesceLocationInternal() {
		if ( $this->server ) {
			return null; // not using the LBFactory instance
		}

		return is_string( $this->cluster )
			? "DBCluster:{$this->cluster}:{$this->domain}"
			: "LBFactory:{$this->domain}";
	}

	/** @inheritDoc */
	protected function doGetSiblingQueuesWithJobs( array $types ) {
		$dbr = $this->getReplicaDB();
		// @note: this does not check whether the jobs are claimed or not.
		// This is useful so JobQueueGroup::pop() also sees queues that only
		// have stale jobs. This lets recycleAndDeleteStaleJobs() re-enqueue
		// failed jobs so that they can be popped again for that edge case.
		$res = $dbr->newSelectQueryBuilder()
			->select( 'job_cmd' )
			->distinct()
			->from( 'job' )
			->where( [ 'job_cmd' => $types ] )
			->caller( __METHOD__ )->fetchResultSet();

		$types = [];
		foreach ( $res as $row ) {
			$types[] = $row->job_cmd;
		}

		return $types;
	}

	/** @inheritDoc */
	protected function doGetSiblingQueueSizes( array $types ) {
		$dbr = $this->getReplicaDB();

		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'job_cmd', 'count' => 'COUNT(*)' ] )
			->from( 'job' )
			->where( [ 'job_cmd' => $types ] )
			->groupBy( 'job_cmd' )
			->caller( __METHOD__ )->fetchResultSet();

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
		$dbw = $this->getPrimaryDB();

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
				$res = $dbw->newSelectQueryBuilder()
					->select( 'job_id' )
					->from( 'job' )
					->where(
						[
							'job_cmd' => $this->type,
							$dbw->expr( 'job_token', '!=', '' ), // was acquired
							$dbw->expr( 'job_token_timestamp', '<', $claimCutoff ), // stale
							$dbw->expr( 'job_attempts', '<', $this->maxTries ), // retries left
						]
					)
					->caller( __METHOD__ )->fetchResultSet();
				$ids = array_map(
					static function ( $o ) {
						return $o->job_id;
					}, iterator_to_array( $res )
				);
				if ( count( $ids ) ) {
					// Reset job_token for these jobs so that other runners will pick them up.
					// Set the timestamp to the current time, as it is useful to now that the job
					// was already tried before (the timestamp becomes the "released" time).
					$dbw->newUpdateQueryBuilder()
						->update( 'job' )
						->set( [
							'job_token' => '',
							'job_token_timestamp' => $dbw->timestamp( $now ) // time of release
						] )
						->where( [
							'job_id' => $ids,
							$dbw->expr( 'job_token', '!=', '' ),
						] )
						->caller( __METHOD__ )->execute();

					$affected = $dbw->affectedRows();
					$count += $affected;
					$this->incrStats( 'recycles', $this->type, $affected );
				}
			}

			// Just destroy any stale jobs...
			$pruneCutoff = $dbw->timestamp( $now - self::MAX_AGE_PRUNE );
			$qb = $dbw->newSelectQueryBuilder()
				->select( 'job_id' )
				->from( 'job' )
				->where(
					[
						'job_cmd' => $this->type,
						$dbw->expr( 'job_token', '!=', '' ), // was acquired
						$dbw->expr( 'job_token_timestamp', '<', $pruneCutoff ) // stale
					]
				);
			if ( $this->claimTTL > 0 ) { // only prune jobs attempted too many times...
				$qb->andWhere( $dbw->expr( 'job_attempts', '>=', $this->maxTries ) );
			}
			// Get the IDs of jobs that are considered stale and should be removed. Selecting
			// the IDs first means that the UPDATE can be done by primary key (less deadlocks).
			$res = $qb->caller( __METHOD__ )->fetchResultSet();
			$ids = array_map(
				static function ( $o ) {
					return $o->job_id;
				}, iterator_to_array( $res )
			);
			if ( count( $ids ) ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'job' )
					->where( [ 'job_id' => $ids ] )
					->caller( __METHOD__ )->execute();
				$affected = $dbw->affectedRows();
				$count += $affected;
				$this->incrStats( 'abandons', $this->type, $affected );
			}

			$dbw->unlock( "jobqueue-recycle-{$this->type}", __METHOD__ );
		} catch ( DBError $e ) {
			throw $this->getDBException( $e );
		}

		return $count;
	}

	/**
	 * @param IJobSpecification $job
	 * @param IReadableDatabase $db
	 * @return array
	 */
	protected function insertFields( IJobSpecification $job, IReadableDatabase $db ) {
		return [
			// Fields that describe the nature of the job
			'job_cmd' => $job->getType(),
			'job_namespace' => $job->getParams()['namespace'] ?? NS_SPECIAL,
			'job_title' => $job->getParams()['title'] ?? '',
			'job_params' => self::makeBlob( $job->getParams() ),
			// Additional job metadata
			'job_timestamp' => $db->timestamp(),
			'job_sha1' => \Wikimedia\base_convert(
				sha1( serialize( $job->getDeduplicationInfo() ) ),
				16, 36, 31
			),
			'job_random' => mt_rand( 0, self::MAX_JOB_RANDOM )
		];
	}

	/**
	 * @throws JobQueueConnectionError
	 * @return IDatabase
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
	 * @return IMaintainableDatabase
	 * @since 1.37
	 */
	protected function getPrimaryDB() {
		try {
			return $this->getDB( DB_PRIMARY );
		} catch ( DBConnectionError $e ) {
			throw new JobQueueConnectionError( "DBConnectionError:" . $e->getMessage() );
		}
	}

	/**
	 * @param int $index (DB_REPLICA/DB_PRIMARY)
	 * @return IMaintainableDatabase
	 */
	protected function getDB( $index ) {
		if ( $this->server ) {
			if ( $this->conn instanceof IDatabase ) {
				return $this->conn;
			} elseif ( $this->conn instanceof DBError ) {
				throw $this->conn;
			}

			try {
				$this->conn = MediaWikiServices::getInstance()->getDatabaseFactory()->create(
					$this->server['type'],
					$this->server
				);
			} catch ( DBError $e ) {
				$this->conn = $e;
				throw $e;
			}

			return $this->conn;
		} else {
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$lb = is_string( $this->cluster )
				? $lbFactory->getExternalLB( $this->cluster )
				: $lbFactory->getMainLB( $this->domain );

			if ( $lb->getServerType( ServerInfo::WRITER_INDEX ) !== 'sqlite' ) {
				// Keep a separate connection to avoid contention and deadlocks;
				// However, SQLite has the opposite behavior due to DB-level locking.
				$flags = $lb::CONN_TRX_AUTOCOMMIT;
			} else {
				// Jobs insertion will be deferred until the PRESEND stage to reduce contention.
				$flags = 0;
			}

			return $lb->getMaintenanceConnectionRef( $index, [], $this->domain, $flags );
		}
	}

	/**
	 * @param string $property
	 * @return string
	 */
	private function getCacheKey( $property ) {
		$cluster = is_string( $this->cluster ) ? $this->cluster : 'main';

		return $this->wanCache->makeGlobalKey(
			'jobqueue',
			$this->domain,
			$cluster,
			$this->type,
			$property
		);
	}

	/**
	 * @param array|false $params
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
	 * @param stdClass $row
	 * @return RunnableJob
	 */
	protected function jobFromRow( $row ) {
		$params = ( (string)$row->job_params !== '' ) ? unserialize( $row->job_params ) : [];
		if ( !is_array( $params ) ) { // this shouldn't happen
			throw new UnexpectedValueException(
				"Could not unserialize job with ID '{$row->job_id}'." );
		}

		$params += [ 'namespace' => $row->job_namespace, 'title' => $row->job_title ];
		$job = $this->factoryJob( $row->job_cmd, $params );
		$job->setMetadata( 'id', $row->job_id );
		$job->setMetadata( 'timestamp', $row->job_timestamp );

		return $job;
	}

	/**
	 * @param DBError $e
	 * @return JobQueueError
	 */
	protected function getDBException( DBError $e ) {
		return new JobQueueError( get_class( $e ) . ": " . $e->getMessage() );
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

/** @deprecated class alias since 1.44 */
class_alias( JobQueueDB::class, 'JobQueueDB' );
