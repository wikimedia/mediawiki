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
		$uuid = wfRandomString( 31 ); // pop attempt

		$dbw = $this->getMasterDB();
		if ( $dbw->trxLevel() ) {
			wfWarn( "Attempted to pop a job in a transaction; commiting first." );
			$dbw->commit(); // push existing transaction
		}

		// Reserve a row with a single UPDATE without holding row locks over RTTs...
		$autoTrx = $dbw->getFlag( DBO_TRX ); // automatic begin() enabled?
		$dbw->clearFlag( DBO_TRX ); // disable automatic begin()
		// Per http://bugs.mysql.com/bug.php?id=6980, we can't use subqueries on the
		// same table being changed in an UPDATE query in MySQL (gives Error: 1093).
		// Oracle and Postgre have no such limitation. However, MySQL offers an
		// alternative here by supporting ORDER BY + LIMIT for UPDATE queries.
		if ( $dbw->getType() == 'mysql' ) {
			$dbw->update( 'job',
				array( 'job_session' => $uuid ),
				array( 'job_cmd' => $type, 'job_session' => '' ),
				__METHOD__,
				array( 'ORDER BY' => 'job_id ASC', 'LIMIT' => 1 )
			);
		} else {
			$dbw->update( 'job',
				array( 'job_session' => $uuid ),
				array( 'job_id = (' .
					$dbw->selectSQLText( 'job', 'MIN(job_id)',
						array( 'job_cmd' => $type, 'job_session' => '' ) ) .
					')'
				),
				__METHOD__
			);
		}
		$affectedRows = $dbw->affectedRows();
		$dbw->setFlag( $autoTrx ? DBO_TRX : 0 ); // re-enable automatic begin()

		if ( !$affectedRows ) {
			return false; // no rows available
		}

		// Fetch the row that we just reserved...
		$row = $dbw->selectRow( 'job', '*', array( 'job_cmd' => $type, 'job_session' => $uuid ) );
		if ( !$row ) {
			throw new MWException( "Expected row with UUID $uuid; got none." );
		}

		// Remove any duplicates it may have later in the queue
		if ( $row->job_sha1 != '' ) {
			$dbw->begin();
			$dbw->delete( 'job', array( 'job_sha1' => $row->job_sha1 ), __METHOD__ );
			$dbw->commit();
		}

		// Check if the job still has a valid title...
		$title = Title::makeTitleSafe( $row->job_namespace, $row->job_title );
		if ( !$title ) {
			wfDebugLog( 'JobQueueDB', "Got row with invalid title '{$row->job_title}'." );
			return false;
		}

		$job = Job::factory( $row->job_cmd, $title,
			Job::extractBlob( $row->job_params ), $row->job_id );

		return $job;
	}

	/**
	 * @see JobQueueStore::doAck()
	 * @return Job|bool
	 */
	protected function doAck( $type, Job $job ) {
		$dbw = $this->getMasterDB();
		$dbw->begin();
		$dbw->delete( 'job', array( 'job_type' => $type, 'job_id' => $job->getId() ) );
		$dbw->commit();
		return true;
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
		return wfGetDB( DB_SLAVE, array(), $this->wiki );
	}
}
