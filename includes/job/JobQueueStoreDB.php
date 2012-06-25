<?php
/**
 * Job queue DB store code.
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
 * @defgroup JobQueue JobQueue
 */

/**
 * Class to handle storage of job queues in a DB
 *
 * @ingroup JobQueue
 * @since 1.20
 */
class JobQueueStoreDB extends JobQueueStore {
	/**
	 * @see JobQueueStore::doBatchInsert()
	 * @return bool
	 */
	protected function doBatchInsert( $type, array $jobs ) {
		if ( count( $jobs ) ) {
			$dbw = $this->getMasterDB();

			$rows = array();
			foreach ( $jobs as $job ) {
				$rows[] = $job->insertFields();
				if ( count( $rows ) >= 50 ) {
					# Do a small transaction to avoid slave lag
					$dbw->begin( __METHOD__ );
					$dbw->insert( 'job', $rows, __METHOD__, 'IGNORE' );
					$dbw->commit( __METHOD__ );
					$rows = array();
				}
			}

			if ( $rows ) { // last chunk
				$dbw->begin( __METHOD__ );
				$dbw->insert( 'job', $rows, __METHOD__, 'IGNORE' );
				$dbw->commit( __METHOD__ );
			}
		}
		return true;
	}

	/**
	 * @see JobQueueStore::doPop()
	 * @return Job|bool
	 */
	protected function doPop( $type ) {
		$uuid = wfRandomString( 32 ); // pop attempt

		$dbw = $this->getMasterDB();
		if ( $dbw->trxLevel() ) {
			wfWarn( "Attempted to pop a job in a transaction; commiting first." );
			$dbw->commit(); // push existing transaction
		}

		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		try {
			$row = false;
			// Reserve a row with a single UPDATE without holding row locks over RTTs...
			if ( $dbw->getType() === 'mysql' ) {
				// Per http://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
				// same table being changed in an UPDATE query in MySQL (gives Error: 1093).
				// Oracle and Postgre have no such limitation. However, MySQL offers an
				// alternative here by supporting ORDER BY + LIMIT for UPDATE queries.
				$dbw->query( "UPDATE {$dbw->tableName( 'job' )}
					SET job_token = {$dbw->addQuotes( $uuid) } WHERE (
					job_cmd = {$dbw->addQuotes( $type )} AND job_token = {$dbw->addQuotes( '' )}
					) ORDER BY job_id ASC LIMIT 1", __METHOD__ );
			} else {
				$dbw->update( 'job',
					array( 'job_token' => $uuid ),
					array( 'job_id = (' .
						$dbw->selectSQLText( 'job', 'MIN(job_id)',
							array( 'job_cmd' => $type, 'job_token' => '' ) ) .
						')'
					),
					__METHOD__
				);
			}
			if ( $dbw->affectedRows() > 0 ) {
				// Fetch the row that we just reserved...
				$row = $dbw->selectRow( 'job', '*',
					array( 'job_cmd' => $type, 'job_token' => $uuid ), __METHOD__ );
				// Remove any duplicates the job may in the queue...
				if ( !$row ) {
					wfDebug( __METHOD__ . ": row deleted as duplicate by another process." );
				} elseif ( $row->job_sha1 != '' ) {
					$dbw->delete( 'job', array( 'job_sha1' => $row->job_sha1 ), __METHOD__ );
				}
			}
		} catch ( Exception $e ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
			throw $e;
		}
		$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()

		if ( $row ) {
			$title = Title::makeTitleSafe( $row->job_namespace, $row->job_title );
			if ( $title ) {
				return Job::factory( $row->job_cmd, $title,
					Job::extractBlob( $row->job_params ), $row->job_id );
			} else {
				wfDebugLog( 'JobQueueDB', "Got row with invalid title '{$row->job_title}'." );
			}
		}

		return false;
	}

	/**
	 * @see JobQueueStore::doAck()
	 * @return Job|bool
	 */
	protected function doAck( $type, Job $job ) {
		$dbw = $this->getMasterDB();
		if ( $dbw->trxLevel() ) {
			wfWarn( "Attempted to ack a job in a transaction; commiting first." );
			$dbw->commit(); // push existing transaction
		}

		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // make each query its own transaction
		try {
			// Delete a row with a single DELETE without holding row locks over RTTs...
			$dbw->delete( 'job', array( 'job_cmd' => $type, 'job_id' => $job->getId() ) );
		} catch ( Exception $e ) {
			$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()
			throw $e;
		}
		$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // restore automatic begin()

		return true;
	}

	/**
	 * @see JobQueueStore::doWaitForBackup()
	 * @return void
	 */
	protected function doWaitForBackup( $type ) {
		wfWaitForSlaves();
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
			'job_params'    => Job::makeBlob( $job->getParams() ),
		);
		// Additional job metadata
		$dbw = $this->getMasterDB();
		$metaFields = array(
			'job_id'        => $dbw->nextSequenceValue( 'job_job_id_seq' ),
			'job_timestamp' => $dbw->timestamp(),
			'job_sha1'      => sha1( serialize( $descFields ) )
		);
		return ( $descFields + $metaFields );
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

	/**
	 * @return DatabaseBase
	 */
	protected function getMasterDB() {
		return wfGetDB( DB_MASTER, array(), $this->wiki );
	}
}
