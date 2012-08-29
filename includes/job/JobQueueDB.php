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
	const CACHE_TTL = 30; // integer; seconds

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

		$uuid = wfRandomString( 32 ); // pop attempt

		$dbw = $this->getMasterDB();
		if ( $dbw->trxLevel() ) {
			wfWarn( "Attempted to pop a job in a transaction; committing first." );
			$dbw->commit(); // push existing transaction
		}

		$job = false; // job popped off
		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		try {
			do { // retry when our row is invalid or deleted as a duplicate
				$row = false; // row claimed
				$rand = mt_rand( 0, 2147483648 ); // encourage concurrent UPDATEs
				$gte  = (bool)mt_rand( 0, 1 ); // find rows with rand before/after $rand
				// Try to reserve a DB row...
				if ( $this->claim( $uuid, $rand, $gte ) || $this->claim( $uuid, $rand, !$gte ) ) {
					// Fetch any row that we just reserved...
					$row = $dbw->selectRow( 'job', '*',
						array( 'job_cmd' => $this->type, 'job_token' => $uuid ), __METHOD__ );
					// Check if another process deleted it as a duplicate
					if ( !$row ) {
						wfDebugLog( 'JobQueueDB', "Row deleted as duplicate by another process." );
						continue; // try again
					}
					// Get the job object from the row...
					$title = Title::makeTitleSafe( $row->job_namespace, $row->job_title );
					if ( !$title ) {
						$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
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
					}
				} else {
					$wgMemc->set( $this->getEmptinessCacheKey(), 'true', self::CACHE_TTL );
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
	 * @param $uuid string 32 char hex string
	 * @param $rand integer Random unsigned integer (31 bits)
	 * @param $gte bool Search for job_random >= $random (otherwise job_random <= $random)
	 * @return integer Number of affected rows
	 */
	protected function claim( $uuid, $rand, $gte ) {
		$dbw  = $this->getMasterDB();
		$dir  = $gte ? 'ASC' : 'DESC';
		$ineq = $gte ? '>=' : '<=';
		if ( $dbw->getType() === 'mysql' ) {
			// Per http://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
			// same table being changed in an UPDATE query in MySQL (gives Error: 1093).
			// Oracle and Postgre have no such limitation. However, MySQL offers an
			// alternative here by supporting ORDER BY + LIMIT for UPDATE queries.
			// The DB wrapper functions do not support this, so it's done manually.
			$dbw->query( "UPDATE {$dbw->tableName( 'job' )}
				SET
					job_token = {$dbw->addQuotes( $uuid ) },
					job_token_timestamp = {$dbw->addQuotes( $dbw->timestamp() )}
				WHERE (
					job_cmd = {$dbw->addQuotes( $this->type )}
					AND job_token = {$dbw->addQuotes( '' )}
					AND job_random {$ineq} {$dbw->addQuotes( $rand )}
				) ORDER BY job_random {$dir} LIMIT 1",
				__METHOD__
			);
		} else {
			// Use a subquery to find the job, within an UPDATE to claim it.
			// This uses as much of the DB wrapper functions as possible.
			$dbw->update( 'job',
				array( 'job_token' => $uuid, 'job_token_timestamp' => $dbw->timestamp() ),
				array( 'job_id = (' .
					$dbw->selectSQLText( 'job', 'job_id',
						array(
							'job_cmd'   => $this->type,
							'job_token' => '',
							"job_random {$ineq} {$dbw->addQuotes( $rand )}" ),
						__METHOD__,
						array( 'ORDER BY' => "job_random {$dir}", 'LIMIT' => 1 ) ) .
					')'
				),
				__METHOD__
			);
		}
		return $dbw->affectedRows();
	}

	/**
	 * @see JobQueue::doAck()
	 * @return Job|bool
	 */
	protected function doAck( Job $job ) {
		$dbw = $this->getMasterDB();
		if ( $dbw->trxLevel() ) {
			wfWarn( "Attempted to ack a job in a transaction; committing first." );
			$dbw->commit(); // push existing transaction
		}

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
			'job_sha1'      => wfBaseConvert( sha1( serialize( $descFields ) ), 16, 32, 32 ),
			'job_random'    => mt_rand( 0, 2147483647 ) // [0, 2^31 - 1]
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
