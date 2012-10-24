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
 * @since 1.20
 */
class JobQueueDB extends JobQueue {
	const CACHE_TTL      = 30; // integer; seconds to cache queue information
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

			$rows = array();
			foreach ( $jobs as $job ) {
				$rows[] = $this->insertFields( $job );
			}
			$atomic = ( $flags & self::QoS_Atomic );
			$key    = $this->getEmptinessCacheKey();
			$ttl    = self::CACHE_TTL;

			$dbw->onTransactionIdle( function() use ( $dbw, $rows, $atomic, $key, $ttl ) {
				global $wgMemc;

				$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
				if ( $atomic ) {
					$dbw->begin(); // wrap all the job additions in one transaction
				} else {
					$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
				}
				try {
					foreach ( array_chunk( $rows, 50 ) as $rowBatch ) { // avoid slave lag
						$dbw->insert( 'job', $rowBatch, __METHOD__ );
					}
				} catch ( DBError $e ) {
					if ( $atomic ) {
						$dbw->rollback();
					} else {
						$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
					}
					throw $e;
				}
				if ( $atomic ) {
					$dbw->commit();
				} else {
					$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
				}

				$wgMemc->set( $key, 'false', $ttl );
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
		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		try {
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
				// Delete any *other* duplicate jobs in the queue...
				if ( $job->ignoreDuplicates() && strlen( $row->job_sha1 ) ) {
					$dbw->delete( 'job',
						array( 'job_sha1' => $row->job_sha1,
							"job_id != {$dbw->addQuotes( $row->job_id )}" ),
						__METHOD__
					);
					wfIncrStats( 'job-pop', $dbw->affectedRows() );
				}
				break; // done
			} while( true );
		} catch ( DBError $e ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
			throw $e;
		}
		$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()

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
		$now    = time();
		$dbw    = $this->getMasterDB();

		if ( $this->claimTTL > 0 ) { // re-try stale jobs...
			$claimCutoff = $dbw->timestamp( $now - $this->claimTTL );
			// Reset job_token for these jobs so that other runners will pick them up.
			// Set the timestamp to the current time, as it is useful to now that the job
			// was already tried before.
			$dbw->update( 'job',
				array(
					'job_token' => '',
					'job_token_timestamp' => $dbw->timestamp( $now ) ), // time of release
				array(
					'job_cmd' => $this->type,
					"job_token != {$dbw->addQuotes( '' )}", // was acquired
					"job_token_timestamp < {$dbw->addQuotes( $claimCutoff )}", // stale
					"job_attempts < {$dbw->addQuotes( self::MAX_ATTEMPTS )}" ),
				__METHOD__
			);
		}

		// Just destroy stale jobs...
		$pruneCutoff = $dbw->timestamp( $now - self::MAX_AGE_PRUNE );
		$conds = array(
			'job_cmd' => $this->type,
			"job_token != {$dbw->addQuotes( '' )}", // was acquired
			"job_token_timestamp < {$dbw->addQuotes( $pruneCutoff )}" // stale
		);
		if ( $this->claimTTL > 0 ) { // only prune jobs attempted too many times...
			$conds[] = "job_attempts >= {$dbw->addQuotes( self::MAX_ATTEMPTS )}";
		}

		return $dbw->affectedRows();
	}

	/**
	 * @see JobQueue::doAck()
	 * @return Job|bool
	 */
	protected function doAck( Job $job ) {
		$dbw = $this->getMasterDB();
		$dbw->commit( __METHOD__, 'flush' ); // flush existing transaction

		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		try {
			// Delete a row with a single DELETE without holding row locks over RTTs...
			$dbw->delete( 'job', array( 'job_cmd' => $this->type, 'job_id' => $job->getId() ) );
		} catch ( Exception $e ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
			throw $e;
		}
		$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()

		return true;
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
		// Rows that describe the nature of the job
		$descFields = array(
			'job_cmd'       => $job->getType(),
			'job_namespace' => $job->getTitle()->getNamespace(),
			'job_title'     => $job->getTitle()->getDBkey(),
			'job_params'    => self::makeBlob( $job->getParams() ),
		);
		// Additional job metadata
		$dbw = $this->getMasterDB();
		$metaFields = array(
			'job_id'        => $dbw->nextSequenceValue( 'job_job_id_seq' ),
			'job_timestamp' => $dbw->timestamp(),
			'job_sha1'      => wfBaseConvert( sha1( serialize( $descFields ) ), 16, 36, 32 ),
			'job_random'    => mt_rand( 0, self::MAX_JOB_RANDOM )
		);
		return ( $descFields + $metaFields );
	}

	/**
	 * @return string
	 */
	private function getEmptinessCacheKey() {
		list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
		return wfForeignMemcKey( $db, $prefix, 'jobqueue', $this->type, 'isempty' );
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
