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
	const CACHE_TTL      = 300; // integer; seconds to cache queue information
	const MAX_AGE_PRUNE  = 604800; // integer; seconds a job can live once claimed
	const MAX_ATTEMPTS   = 3; // integer; number of times to try a job
	const MAX_JOB_RANDOM = 2147483647; // integer; 2^31 - 1, used for job_random

	/**
	 * @see JobQueue::doIsEmpty()
	 * @return bool
	 */
	protected function doIsEmpty() {
		global $wgMemc;

		$key = $this->getEmptinessCacheKey();

		$isEmpty = $wgMemc->get( $key );
		if ( $isEmpty === 'true' ) {
			return true;
		} elseif ( $isEmpty === 'false' ) {
			return false;
		}

		$found = $this->getSlaveDB()->selectField(
			'job', '1', array( 'job_cmd' => $this->type ), __METHOD__
		);

		$wgMemc->add( $key, $found ? 'false' : 'true', self::CACHE_TTL );
	}

	/**
	 * @see JobQueue::doBatchPush()
	 * @return bool
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		if ( count( $jobs ) ) {
			$dbw = $this->getMasterDB();

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

			$atomic = ( $flags & self::QoS_Atomic );
			$key    = $this->getEmptinessCacheKey();
			$ttl    = self::CACHE_TTL;

			$dbw->onTransactionIdle(
				function() use ( $dbw, $rowSet, $rowList, $atomic, $key, $ttl
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
				} catch ( DBError $e ) {
					if ( $atomic ) {
						$dbw->rollback( __METHOD__ );
					}
					throw $e;
				}
				if ( $atomic ) {
					$dbw->commit( __METHOD__ );
				}

				$wgMemc->set( $key, 'false', $ttl ); // queue is not empty
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

		if ( $wgMemc->get( $this->getEmptinessCacheKey() ) === 'true' ) {
			return false; // queue is empty
		}

		$dbw = $this->getMasterDB();
		$dbw->commit( __METHOD__, 'flush' ); // flush existing transaction

		$uuid = wfRandomString( 32 ); // pop attempt
		$job = false; // job popped off
		// Occasionally recycle jobs back into the queue that have been claimed too long
		if ( mt_rand( 0, 99 ) == 0 ) {
			$this->recycleStaleJobs();
		}
		do { // retry when our row is invalid or deleted as a duplicate
			// Try to reserve a row in the DB...
			if ( in_array( $this->order, array( 'fifo', 'timestamp' ) ) ) {
				$row = $this->claimOldest( $uuid );
			} else { // random first
				$rand = mt_rand( 0, self::MAX_JOB_RANDOM ); // encourage concurrent UPDATEs
				$gte  = (bool)mt_rand( 0, 1 ); // find rows with rand before/after $rand
				$row  = $this->claimRandom( $uuid, $rand, $gte );
				if ( !$row ) { // need to try the other direction
					$row = $this->claimRandom( $uuid, $rand, !$gte );
				}
			}
			// Check if we found a row to reserve...
			if ( !$row ) {
				$wgMemc->set( $this->getEmptinessCacheKey(), 'true', self::CACHE_TTL );
				break; // nothing to do
			}
			// Get the job object from the row...
			$title = Title::makeTitleSafe( $row->job_namespace, $row->job_title );
			if ( !$title ) {
				$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
				wfIncrStats( 'job-pop' );
				wfDebugLog( 'JobQueueDB', "Row has invalid title '{$row->job_title}'." );
				continue; // try again
			}
			$job = Job::factory( $row->job_cmd, $title,
				self::extractBlob( $row->job_params ), $row->job_id );
			// Flag this job as an old duplicate based on its "root" job...
			if ( $this->isRootJobOldDuplicate( $job ) ) {
				$job = DuplicateJob::newFromJob( $job ); // convert to a no-op
			}
			break; // done
		} while( true );

		return $job;
	}

	/**
	 * Reserve a row with a single UPDATE without holding row locks over RTTs...
	 *
	 * @param $uuid string 32 char hex string
	 * @param $rand integer Random unsigned integer (31 bits)
	 * @param $gte bool Search for job_random >= $random (otherwise job_random <= $random)
	 * @return Row|false
	 */
	protected function claimRandom( $uuid, $rand, $gte ) {
		$dbw  = $this->getMasterDB();
		$dir  = $gte ? 'ASC' : 'DESC';
		$ineq = $gte ? '>=' : '<=';

		$row = false; // the row acquired
		// This uses a replication safe method for acquiring jobs. One could use UPDATE+LIMIT
		// instead, but that either uses ORDER BY (in which case it deadlocks in MySQL) or is
		// not replication safe. Due to http://bugs.mysql.com/bug.php?id=6980, subqueries cannot
		// be used here with MySQL.
		do {
			$row = $dbw->selectRow( 'job', '*', // find a random job
				array(
					'job_cmd'   => $this->type,
					'job_token' => '',
					"job_random {$ineq} {$dbw->addQuotes( $rand )}" ),
				__METHOD__,
				array( 'ORDER BY' => "job_random {$dir}" )
			);
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
	 * @param $uuid string 32 char hex string
	 * @return Row|false
	 */
	protected function claimOldest( $uuid ) {
		$dbw  = $this->getMasterDB();

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
	protected function recycleStaleJobs() {
		$now   = time();
		$dbw   = $this->getMasterDB();
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
					"job_attempts < {$dbw->addQuotes( self::MAX_ATTEMPTS )}" ), // retries left
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
			$conds[] = "job_attempts >= {$dbw->addQuotes( self::MAX_ATTEMPTS )}";
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
	 * @return Job|bool
	 */
	protected function doAck( Job $job ) {
		$dbw = $this->getMasterDB();
		$dbw->commit( __METHOD__, 'flush' ); // flush existing transaction

		// Delete a row with a single DELETE without holding row locks over RTTs...
		$dbw->delete( 'job', array( 'job_cmd' => $this->type, 'job_id' => $job->getId() ) );

		return true;
	}

	/**
	 * @see JobQueue::doDeduplicateRootJob()
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
		// deferred till "transaction idle", do that same here, so that the ordering is
		// maintained. Having only the de-duplication registration succeed would cause
		// jobs to become no-ops without any actual jobs that made them redundant.
		$this->getMasterDB()->onTransactionIdle( function() use ( $params, $key ) {
			global $wgMemc;

			$timestamp = $wgMemc->get( $key ); // current last timestamp of this job
			if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
				return true; // a newer version of this root job was enqueued
			}

			// Update the timestamp of the last root job started at the location...
			return $wgMemc->set( $key, $params['rootJobTimestamp'], 14*86400 ); // 2 weeks
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
	 * @return DatabaseBase
	 */
	protected function getSlaveDB() {
		return wfGetDB( DB_SLAVE, array(), $this->wiki );
	}

	/**
	 * @return DatabaseBase
	 */
	protected function getMasterDB() {
		return wfGetDB( DB_MASTER, array(), $this->wiki );
	}

	/**
	 * @param $job Job
	 * @return array
	 */
	protected function insertFields( Job $job ) {
		$dbw = $this->getMasterDB();
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
	private function getEmptinessCacheKey() {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, 'isempty' );
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
