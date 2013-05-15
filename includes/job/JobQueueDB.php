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
 * @author Aaron Schulz
 */

/**
 * Class to handle job queues stored in the DB
 *
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueDB extends JobQueue {
	const ROOTJOB_TTL = 1209600; // integer; seconds to remember root jobs (14 days)
	const CACHE_TTL_SHORT = 30; // integer; seconds to cache info without re-validating
	const CACHE_TTL_LONG = 300; // integer; seconds to cache info that is kept up to date
	const MAX_AGE_PRUNE = 604800; // integer; seconds a job can live once claimed
	const MAX_JOB_RANDOM = 2147483647; // integer; 2^31 - 1, used for job_random
	const MAX_OFFSET = 255; // integer; maximum number of rows to skip

	protected $cluster = false; // string; name of an external DB cluster

	/**
	 * Additional parameters include:
	 *   - cluster : The name of an external cluster registered via LBFactory.
	 *               If not specified, the primary DB cluster for the wiki will be used.
	 *               This can be overridden with a custom cluster so that DB handles will
	 *               be retrieved via LBFactory::getExternalLB() and getConnection().
	 * @param $params array
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->cluster = isset( $params['cluster'] ) ? $params['cluster'] : false;
	}

	protected function supportedOrders() {
		return array( 'random', 'timestamp', 'fifo' );
	}

	protected function optimalOrder() {
		return 'random';
	}

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 */
	protected function doIsEmpty() {
		global $wgMemc;

		$key = $this->getCacheKey( 'empty' );

		$isEmpty = $wgMemc->get( $key );
		if ( $isEmpty === 'true' ) {
			return true;
		} elseif ( $isEmpty === 'false' ) {
			return false;
		}

		list( $dbr, $scope ) = $this->getSlaveDB();
		$found = $dbr->selectField( // unclaimed job
			'job', '1', array( 'job_cmd' => $this->type, 'job_token' => '' ), __METHOD__
		);
		$wgMemc->add( $key, $found ? 'false' : 'true', self::CACHE_TTL_LONG );

		return !$found;
	}

	/**
	 * @see JobQueue::doGetSize()
	 * @return integer
	 */
	protected function doGetSize() {
		global $wgMemc;

		$key = $this->getCacheKey( 'size' );

		$size = $wgMemc->get( $key );
		if ( is_int( $size ) ) {
			return $size;
		}

		list( $dbr, $scope ) = $this->getSlaveDB();
		$size = (int)$dbr->selectField( 'job', 'COUNT(*)',
			array( 'job_cmd' => $this->type, 'job_token' => '' ),
			__METHOD__
		);
		$wgMemc->set( $key, $size, self::CACHE_TTL_SHORT );

		return $size;
	}

	/**
	 * @see JobQueue::doGetAcquiredCount()
	 * @return integer
	 */
	protected function doGetAcquiredCount() {
		global $wgMemc;

		if ( $this->claimTTL <= 0 ) {
			return 0; // no acknowledgements
		}

		$key = $this->getCacheKey( 'acquiredcount' );

		$count = $wgMemc->get( $key );
		if ( is_int( $count ) ) {
			return $count;
		}

		list( $dbr, $scope ) = $this->getSlaveDB();
		$count = (int)$dbr->selectField( 'job', 'COUNT(*)',
			array( 'job_cmd' => $this->type, "job_token != {$dbr->addQuotes( '' )}" ),
			__METHOD__
		);
		$wgMemc->set( $key, $count, self::CACHE_TTL_SHORT );

		return $count;
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @param array $jobs
	 * @param $flags
	 * @throws DBError|Exception
	 * @return bool
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		if ( count( $jobs ) ) {
			list( $dbw, $scope ) = $this->getMasterDB();

			$rowSet = array(); // (sha1 => job) map for jobs that are de-duplicated
			$rowList = array(); // list of jobs for jobs that are are not de-duplicated

			foreach ( $jobs as $job ) {
				$row = $this->insertFields( $job );
				if ( $job->ignoreDuplicates() ) {
					$rowSet[$row['job_sha1']] = $row;
				} else {
					$rowList[] = $row;
				}
			}

			$key = $this->getCacheKey( 'empty' );
			$atomic = ( $flags & self::QoS_Atomic );

			$dbw->onTransactionIdle(
				function() use ( $dbw, $rowSet, $rowList, $atomic, $key, $scope
			) {
				global $wgMemc;

				if ( $atomic ) {
					$dbw->begin( __METHOD__ ); // wrap all the job additions in one transaction
				}
				try {
					// Strip out any duplicate jobs that are already in the queue...
					if ( count( $rowSet ) ) {
						$res = $dbw->select( 'job', 'job_sha1',
							array(
								// No job_type condition since it's part of the job_sha1 hash
								'job_sha1'  => array_keys( $rowSet ),
								'job_token' => '' // unclaimed
							),
							__METHOD__
						);
						foreach ( $res as $row ) {
							wfDebug( "Job with hash '{$row->job_sha1}' is a duplicate." );
							unset( $rowSet[$row->job_sha1] ); // already enqueued
						}
					}
					// Build the full list of job rows to insert
					$rows = array_merge( $rowList, array_values( $rowSet ) );
					// Insert the job rows in chunks to avoid slave lag...
					foreach ( array_chunk( $rows, 50 ) as $rowBatch ) {
						$dbw->insert( 'job', $rowBatch, __METHOD__ );
					}
					wfIncrStats( 'job-insert', count( $rows ) );
					wfIncrStats( 'job-insert-duplicate',
						count( $rowSet ) + count( $rowList ) - count( $rows ) );
				} catch ( DBError $e ) {
					if ( $atomic ) {
						$dbw->rollback( __METHOD__ );
					}
					throw $e;
				}
				if ( $atomic ) {
					$dbw->commit( __METHOD__ );
				}

				$wgMemc->set( $key, 'false', JobQueueDB::CACHE_TTL_LONG );
			} );
		}

		return true;
	}

	/**
	 * @see JobQueue::doPop()
	 * @return Job|bool
	 */
	protected function doPop() {
		global $wgMemc;

		if ( $wgMemc->get( $this->getCacheKey( 'empty' ) ) === 'true' ) {
			return false; // queue is empty
		}

		list( $dbw, $scope ) = $this->getMasterDB();
		$dbw->commit( __METHOD__, 'flush' ); // flush existing transaction
		$autoTrx = $dbw->getFlag( DBO_TRX ); // get current setting
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		$scopedReset = new ScopedCallback( function() use ( $dbw, $autoTrx ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore old setting
		} );

		$uuid = wfRandomString( 32 ); // pop attempt
		$job = false; // job popped off
		do { // retry when our row is invalid or deleted as a duplicate
			// Try to reserve a row in the DB...
			if ( in_array( $this->order, array( 'fifo', 'timestamp' ) ) ) {
				$row = $this->claimOldest( $uuid );
			} else { // random first
				$rand = mt_rand( 0, self::MAX_JOB_RANDOM ); // encourage concurrent UPDATEs
				$gte = (bool)mt_rand( 0, 1 ); // find rows with rand before/after $rand
				$row = $this->claimRandom( $uuid, $rand, $gte );
			}
			// Check if we found a row to reserve...
			if ( !$row ) {
				$wgMemc->set( $this->getCacheKey( 'empty' ), 'true', self::CACHE_TTL_LONG );
				break; // nothing to do
			}
			wfIncrStats( 'job-pop' );
			// Get the job object from the row...
			$title = Title::makeTitleSafe( $row->job_namespace, $row->job_title );
			if ( !$title ) {
				$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
				wfDebugLog( 'JobQueueDB', "Row has invalid title '{$row->job_title}'." );
				continue; // try again
			}
			$job = Job::factory( $row->job_cmd, $title,
				self::extractBlob( $row->job_params ), $row->job_id );
			$job->id = $row->job_id; // XXX: work around broken subclasses
			// Flag this job as an old duplicate based on its "root" job...
			if ( $this->isRootJobOldDuplicate( $job ) ) {
				wfIncrStats( 'job-pop-duplicate' );
				$job = DuplicateJob::newFromJob( $job ); // convert to a no-op
			}
			break; // done
		} while( true );

		return $job;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param string $uuid 32 char hex string
	 * @param $rand integer Random unsigned integer (31 bits)
	 * @param bool $gte Search for job_random >= $random (otherwise job_random <= $random)
	 * @return Row|false
	 */
	protected function claimRandom( $uuid, $rand, $gte ) {
		global $wgMemc;

		list( $dbw, $scope ) = $this->getMasterDB();
		// Check cache to see if the queue has <= OFFSET items
		$tinyQueue = $wgMemc->get( $this->getCacheKey( 'small' ) );

		$row = false; // the row acquired
		$invertedDirection = false; // whether one job_random direction was already scanned
		// This uses a replication safe method for acquiring jobs. One could use UPDATE+LIMIT
		// instead, but that either uses ORDER BY (in which case it deadlocks in MySQL) or is
		// not replication safe. Due to http://bugs.mysql.com/bug.php?id=6980, subqueries cannot
		// be used here with MySQL.
		do {
			if ( $tinyQueue ) { // queue has <= MAX_OFFSET rows
				// For small queues, using OFFSET will overshoot and return no rows more often.
				// Instead, this uses job_random to pick a row (possibly checking both directions).
				$ineq = $gte ? '>=' : '<=';
				$dir = $gte ? 'ASC' : 'DESC';
				$row = $dbw->selectRow( 'job', '*', // find a random job
					array(
						'job_cmd'   => $this->type,
						'job_token' => '', // unclaimed
						"job_random {$ineq} {$dbw->addQuotes( $rand )}" ),
					__METHOD__,
					array( 'ORDER BY' => "job_random {$dir}" )
				);
				if ( !$row && !$invertedDirection ) {
					$gte = !$gte;
					$invertedDirection = true;
					continue; // try the other direction
				}
			} else { // table *may* have >= MAX_OFFSET rows
				// Bug 42614: "ORDER BY job_random" with a job_random inequality causes high CPU
				// in MySQL if there are many rows for some reason. This uses a small OFFSET
				// instead of job_random for reducing excess claim retries.
				$row = $dbw->selectRow( 'job', '*', // find a random job
					array(
						'job_cmd'   => $this->type,
						'job_token' => '', // unclaimed
					),
					__METHOD__,
					array( 'OFFSET' => mt_rand( 0, self::MAX_OFFSET ) )
				);
				if ( !$row ) {
					$tinyQueue = true; // we know the queue must have <= MAX_OFFSET rows
					$wgMemc->set( $this->getCacheKey( 'small' ), 1, 30 );
					continue; // use job_random
				}
			}
			if ( $row ) { // claim the job
				$dbw->update( 'job', // update by PK
					array(
						'job_token'           => $uuid,
						'job_token_timestamp' => $dbw->timestamp(),
						'job_attempts = job_attempts+1' ),
					array( 'job_cmd' => $this->type, 'job_id' => $row->job_id, 'job_token' => '' ),
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
	 * @return Row|false
	 */
	protected function claimOldest( $uuid ) {
		list( $dbw, $scope ) = $this->getMasterDB();

		$row = false; // the row acquired
		do {
			if ( $dbw->getType() === 'mysql' ) {
				// Per http://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
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
					array(
						'job_token'           => $uuid,
						'job_token_timestamp' => $dbw->timestamp(),
						'job_attempts = job_attempts+1' ),
					array( 'job_id = (' .
						$dbw->selectSQLText( 'job', 'job_id',
							array( 'job_cmd' => $this->type, 'job_token' => '' ),
							__METHOD__,
							array( 'ORDER BY' => 'job_id ASC', 'LIMIT' => 1 ) ) .
						')'
					),
					__METHOD__
				);
			}
			// Fetch any row that we just reserved...
			if ( $dbw->affectedRows() ) {
				$row = $dbw->selectRow( 'job', '*',
					array( 'job_cmd' => $this->type, 'job_token' => $uuid ), __METHOD__
				);
				if ( !$row ) { // raced out by duplicate job removal
					wfDebugLog( 'JobQueueDB', "Row deleted as duplicate by another process." );
				}
			} else {
				break; // nothing to do
			}
		} while ( !$row );

		return $row;
	}

	/**
	 * Recycle or destroy any jobs that have been claimed for too long
	 *
	 * @return integer Number of jobs recycled/deleted
	 */
	public function recycleAndDeleteStaleJobs() {
		global $wgMemc;

		$now = time();
		list( $dbw, $scope ) = $this->getMasterDB();
		$count = 0; // affected rows

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
				array(
					'job_cmd' => $this->type,
					"job_token != {$dbw->addQuotes( '' )}", // was acquired
					"job_token_timestamp < {$dbw->addQuotes( $claimCutoff )}", // stale
					"job_attempts < {$dbw->addQuotes( $this->maxTries )}" ), // retries left
				__METHOD__
			);
			$ids = array_map( function( $o ) { return $o->job_id; }, iterator_to_array( $res ) );
			if ( count( $ids ) ) {
				// Reset job_token for these jobs so that other runners will pick them up.
				// Set the timestamp to the current time, as it is useful to now that the job
				// was already tried before (the timestamp becomes the "released" time).
				$dbw->update( 'job',
					array(
						'job_token' => '',
						'job_token_timestamp' => $dbw->timestamp( $now ) ), // time of release
					array(
						'job_id' => $ids ),
					__METHOD__
				);
				$count += $dbw->affectedRows();
				wfIncrStats( 'job-recycle', $dbw->affectedRows() );
				$wgMemc->set( $this->getCacheKey( 'empty' ), 'false', self::CACHE_TTL_LONG );
			}
		}

		// Just destroy any stale jobs...
		$pruneCutoff = $dbw->timestamp( $now - self::MAX_AGE_PRUNE );
		$conds = array(
			'job_cmd' => $this->type,
			"job_token != {$dbw->addQuotes( '' )}", // was acquired
			"job_token_timestamp < {$dbw->addQuotes( $pruneCutoff )}" // stale
		);
		if ( $this->claimTTL > 0 ) { // only prune jobs attempted too many times...
			$conds[] = "job_attempts >= {$dbw->addQuotes( $this->maxTries )}";
		}
		// Get the IDs of jobs that are considered stale and should be removed. Selecting
		// the IDs first means that the UPDATE can be done by primary key (less deadlocks).
		$res = $dbw->select( 'job', 'job_id', $conds, __METHOD__ );
		$ids = array_map( function( $o ) { return $o->job_id; }, iterator_to_array( $res ) );
		if ( count( $ids ) ) {
			$dbw->delete( 'job', array( 'job_id' => $ids ), __METHOD__ );
			$count += $dbw->affectedRows();
		}

		$dbw->unlock( "jobqueue-recycle-{$this->type}", __METHOD__ );

		return $count;
	}

	/**
	 * @see JobQueue::doAck()
	 * @param Job $job
	 * @throws MWException
	 * @return Job|bool
	 */
	protected function doAck( Job $job ) {
		if ( !$job->getId() ) {
			throw new MWException( "Job of type '{$job->getType()}' has no ID." );
		}

		list( $dbw, $scope ) = $this->getMasterDB();
		$dbw->commit( __METHOD__, 'flush' ); // flush existing transaction
		$autoTrx = $dbw->getFlag( DBO_TRX ); // get current setting
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		$scopedReset = new ScopedCallback( function() use ( $dbw, $autoTrx ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore old setting
		} );

		// Delete a row with a single DELETE without holding row locks over RTTs...
		$dbw->delete( 'job',
			array( 'job_cmd' => $this->type, 'job_id' => $job->getId() ), __METHOD__ );

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
		$key = $this->getRootJobCacheKey( $params['rootJobSignature'] );
		// Callers should call batchInsert() and then this function so that if the insert
		// fails, the de-duplication registration will be aborted. Since the insert is
		// deferred till "transaction idle", do the same here, so that the ordering is
		// maintained. Having only the de-duplication registration succeed would cause
		// jobs to become no-ops without any actual jobs that made them redundant.
		list( $dbw, $scope ) = $this->getMasterDB();
		$dbw->onTransactionIdle( function() use ( $params, $key, $scope ) {
			global $wgMemc;

			$timestamp = $wgMemc->get( $key ); // current last timestamp of this job
			if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
				return true; // a newer version of this root job was enqueued
			}

			// Update the timestamp of the last root job started at the location...
			return $wgMemc->set( $key, $params['rootJobTimestamp'], JobQueueDB::ROOTJOB_TTL );
		} );

		return true;
	}

	/**
	 * Check if the "root" job of a given job has been superseded by a newer one
	 *
	 * @param $job Job
	 * @return bool
	 */
	protected function isRootJobOldDuplicate( Job $job ) {
		global $wgMemc;

		$params = $job->getParams();
		if ( !isset( $params['rootJobSignature'] ) ) {
			return false; // job has no de-deplication info
		} elseif ( !isset( $params['rootJobTimestamp'] ) ) {
			trigger_error( "Cannot check root job; missing 'rootJobTimestamp'." );
			return false;
		}

		// Get the last time this root job was enqueued
		$timestamp = $wgMemc->get( $this->getRootJobCacheKey( $params['rootJobSignature'] ) );

		// Check if a new root job was started at the location after this one's...
		return ( $timestamp && $timestamp > $params['rootJobTimestamp'] );
	}

	/**
	 * @see JobQueue::doWaitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		wfWaitForSlaves();
	}

	/**
	 * @return Array
	 */
	protected function doGetPeriodicTasks() {
		return array(
			'recycleAndDeleteStaleJobs' => array(
				'callback' => array( $this, 'recycleAndDeleteStaleJobs' ),
				'period'   => ceil( $this->claimTTL / 2 )
			)
		);
	}

	/**
	 * @return void
	 */
	protected function doFlushCaches() {
		global $wgMemc;

		foreach ( array( 'empty', 'size', 'acquiredcount' ) as $type ) {
			$wgMemc->delete( $this->getCacheKey( $type ) );
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs()
	 * @return Iterator
	 */
	public function getAllQueuedJobs() {
		list( $dbr, $scope ) = $this->getSlaveDB();
		return new MappedIterator(
			$dbr->select( 'job', '*', array( 'job_cmd' => $this->getType(), 'job_token' => '' ) ),
			function( $row ) use ( $scope ) {
				$job = Job::factory(
					$row->job_cmd,
					Title::makeTitle( $row->job_namespace, $row->job_title ),
					strlen( $row->job_params ) ? unserialize( $row->job_params ) : false,
					$row->job_id
				);
				$job->id = $row->job_id; // XXX: work around broken subclasses
				return $job;
			}
		);
	}

	/**
	 * @return Array (DatabaseBase, ScopedCallback)
	 */
	protected function getSlaveDB() {
		return $this->getDB( DB_SLAVE );
	}

	/**
	 * @return Array (DatabaseBase, ScopedCallback)
	 */
	protected function getMasterDB() {
		return $this->getDB( DB_MASTER );
	}

	/**
	 * @param $index integer (DB_SLAVE/DB_MASTER)
	 * @return Array (DatabaseBase, ScopedCallback)
	 */
	protected function getDB( $index ) {
		$lb = ( $this->cluster !== false )
			? wfGetLBFactory()->getExternalLB( $this->cluster, $this->wiki )
			: wfGetLB( $this->wiki );
		$conn = $lb->getConnection( $index, array(), $this->wiki );
		return array(
			$conn,
			new ScopedCallback( function() use ( $lb, $conn ) {
				$lb->reuseConnection( $conn );
			} )
		);
	}

	/**
	 * @param $job Job
	 * @return array
	 */
	protected function insertFields( Job $job ) {
		list( $dbw, $scope ) = $this->getMasterDB();
		return array(
			// Fields that describe the nature of the job
			'job_cmd'       => $job->getType(),
			'job_namespace' => $job->getTitle()->getNamespace(),
			'job_title'     => $job->getTitle()->getDBkey(),
			'job_params'    => self::makeBlob( $job->getParams() ),
			// Additional job metadata
			'job_id'        => $dbw->nextSequenceValue( 'job_job_id_seq' ),
			'job_timestamp' => $dbw->timestamp(),
			'job_sha1'      => wfBaseConvert(
				sha1( serialize( $job->getDeduplicationInfo() ) ),
				16, 36, 31
			),
			'job_random'    => mt_rand( 0, self::MAX_JOB_RANDOM )
		);
	}

	/**
	 * @return string
	 */
	private function getCacheKey( $property ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, $property );
	}

	/**
	 * @param string $signature Hash identifier of the root job
	 * @return string
	 */
	private function getRootJobCacheKey( $signature ) {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, 'rootjob', $signature );
	}

	/**
	 * @param $params
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
	 * @param $blob
	 * @return bool|mixed
	 */
	protected static function extractBlob( $blob ) {
		if ( (string)$blob !== '' ) {
			return unserialize( $blob );
		} else {
			return false;
		}
	}
}
