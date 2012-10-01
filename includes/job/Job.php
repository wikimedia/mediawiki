<?php
/**
 * Job queue base code.
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
 * Class to both describe a background job and handle jobs.
 *
 * @ingroup JobQueue
 */
abstract class Job {

	/**
	 * @var Title
	 */
	var $title;

	var $command,
		$params,
		$id,
		$removeDuplicates,
		$error;

	/*-------------------------------------------------------------------------
	 * Abstract functions
	 *------------------------------------------------------------------------*/

	/**
	 * Run the job
	 * @return boolean success
	 */
	abstract function run();

	/*-------------------------------------------------------------------------
	 * Static functions
	 *------------------------------------------------------------------------*/

	/**
	 * Pop a job of a certain type.  This tries less hard than pop() to
	 * actually find a job; it may be adversely affected by concurrent job
	 * runners.
	 *
	 * @param $type string
	 *
	 * @return Job
	 */
	static function pop_type( $type ) {
		wfProfilein( __METHOD__ );

		$dbw = wfGetDB( DB_MASTER );

		$dbw->begin( __METHOD__ );

		$row = $dbw->selectRow(
			'job',
			'*',
			array( 'job_cmd' => $type ),
			__METHOD__,
			array( 'LIMIT' => 1, 'FOR UPDATE' )
		);

		if ( $row === false ) {
			$dbw->commit( __METHOD__ );
			wfProfileOut( __METHOD__ );
			return false;
		}

		/* Ensure we "own" this row */
		$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
		$affected = $dbw->affectedRows();
		$dbw->commit( __METHOD__ );

		if ( $affected == 0 ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfIncrStats( 'job-pop' );
		$namespace = $row->job_namespace;
		$dbkey = $row->job_title;
		$title = Title::makeTitleSafe( $namespace, $dbkey );
		$job = Job::factory( $row->job_cmd, $title, Job::extractBlob( $row->job_params ),
			$row->job_id );

		$job->removeDuplicates();

		wfProfileOut( __METHOD__ );
		return $job;
	}

	/**
	 * Pop a job off the front of the queue
	 *
	 * @param $offset Integer: Number of jobs to skip
	 * @return Job or false if there's no jobs
	 */
	static function pop( $offset = 0 ) {
		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );

		/* Get a job from the slave, start with an offset,
			scan full set afterwards, avoid hitting purged rows

			NB: If random fetch previously was used, offset
				will always be ahead of few entries
		*/

		$conditions = self::defaultQueueConditions();

		$offset = intval( $offset );
		$options = array( 'ORDER BY' => 'job_id', 'USE INDEX' => 'PRIMARY' );

		$row = $dbr->selectRow( 'job', '*',
			array_merge( $conditions, array( "job_id >= $offset" ) ),
			__METHOD__,
			$options
		);

		// Refetching without offset is needed as some of job IDs could have had delayed commits
		// and have lower IDs than jobs already executed, blame concurrency :)
		//
		if ( $row === false ) {
			if ( $offset != 0 ) {
				$row = $dbr->selectRow( 'job', '*', $conditions, __METHOD__, $options );
			}

			if ( $row === false ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
		}

		// Try to delete it from the master
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
		$affected = $dbw->affectedRows();

		if ( !$affected ) {
			// Failed, someone else beat us to it
			// Try getting a random row
			$row = $dbw->selectRow( 'job', array( 'minjob' => 'MIN(job_id)',
				'maxjob' => 'MAX(job_id)' ), '1=1', __METHOD__ );
			if ( $row === false || is_null( $row->minjob ) || is_null( $row->maxjob ) ) {
				// No jobs to get
				wfProfileOut( __METHOD__ );
				return false;
			}
			// Get the random row
			$row = $dbw->selectRow( 'job', '*',
				'job_id >= ' . mt_rand( $row->minjob, $row->maxjob ), __METHOD__ );
			if ( $row === false ) {
				// Random job gone before we got the chance to select it
				// Give up
				wfProfileOut( __METHOD__ );
				return false;
			}
			// Delete the random row
			$dbw->delete( 'job', array( 'job_id' => $row->job_id ), __METHOD__ );
			$affected = $dbw->affectedRows();

			if ( !$affected ) {
				// Random job gone before we exclusively deleted it
				// Give up
				wfProfileOut( __METHOD__ );
				return false;
			}
		}

		// If execution got to here, there's a row in $row that has been deleted from the database
		// by this thread. Hence the concurrent pop was successful.
		wfIncrStats( 'job-pop' );
		$namespace = $row->job_namespace;
		$dbkey = $row->job_title;
		$title = Title::makeTitleSafe( $namespace, $dbkey );

		if ( is_null( $title ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$job = Job::factory( $row->job_cmd, $title, Job::extractBlob( $row->job_params ), $row->job_id );

		// Remove any duplicates it may have later in the queue
		$job->removeDuplicates();

		wfProfileOut( __METHOD__ );
		return $job;
	}

	/**
	 * Create the appropriate object to handle a specific job
	 *
	 * @param $command String: Job command
	 * @param $title Title: Associated title
	 * @param $params Array|bool: Job parameters
	 * @param $id Int: Job identifier
	 * @throws MWException
	 * @return Job
	 */
	static function factory( $command, Title $title, $params = false, $id = 0 ) {
		global $wgJobClasses;
		if( isset( $wgJobClasses[$command] ) ) {
			$class = $wgJobClasses[$command];
			return new $class( $title, $params, $id );
		}
		throw new MWException( "Invalid job command `{$command}`" );
	}

	/**
	 * @param $params
	 * @return string
	 */
	static function makeBlob( $params ) {
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
	static function extractBlob( $blob ) {
		if ( (string)$blob !== '' ) {
			return unserialize( $blob );
		} else {
			return false;
		}
	}

	/**
	 * Batch-insert a group of jobs into the queue.
	 * This will be wrapped in a transaction with a forced commit.
	 *
	 * This may add duplicate at insert time, but they will be
	 * removed later on, when the first one is popped.
	 *
	 * @param $jobs array of Job objects
	 */
	static function batchInsert( $jobs ) {
		if ( !count( $jobs ) ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();

		/**
		 * @var $job Job
		 */
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
		wfIncrStats( 'job-insert', count( $jobs ) );
	}

	/**
	 * Insert a group of jobs into the queue.
	 *
	 * Same as batchInsert() but does not commit and can thus
	 * be rolled-back as part of a larger transaction. However,
	 * large batches of jobs can cause slave lag.
	 *
	 * @param $jobs array of Job objects
	 */
	static function safeBatchInsert( $jobs ) {
		if ( !count( $jobs ) ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();
		foreach ( $jobs as $job ) {
			$rows[] = $job->insertFields();
			if ( count( $rows ) >= 500 ) {
				$dbw->insert( 'job', $rows, __METHOD__, 'IGNORE' );
				$rows = array();
			}
		}
		if ( $rows ) { // last chunk
			$dbw->insert( 'job', $rows, __METHOD__, 'IGNORE' );
		}
		wfIncrStats( 'job-insert', count( $jobs ) );
	}


	/**
	 * SQL conditions to apply on most JobQueue queries
	 *
	 * Whenever we exclude jobs types from the default queue, we want to make
	 * sure that queries to the job queue actually ignore them.
	 *
	 * @return array SQL conditions suitable for Database:: methods
	 */
	static function defaultQueueConditions( ) {
		global $wgJobTypesExcludedFromDefaultQueue;
		$conditions = array();
		if ( count( $wgJobTypesExcludedFromDefaultQueue ) > 0 ) {
			$dbr = wfGetDB( DB_SLAVE );
			foreach ( $wgJobTypesExcludedFromDefaultQueue as $cmdType ) {
				$conditions[] = "job_cmd != " . $dbr->addQuotes( $cmdType );
			}
		}
		return $conditions;
	}

	/*-------------------------------------------------------------------------
	 * Non-static functions
	 *------------------------------------------------------------------------*/

	/**
	 * @param $command
	 * @param $title
	 * @param $params array|bool
	 * @param $id int
	 */
	function __construct( $command, $title, $params = false, $id = 0 ) {
		$this->command = $command;
		$this->title = $title;
		$this->params = $params;
		$this->id = $id;

		// A bit of premature generalisation
		// Oh well, the whole class is premature generalisation really
		$this->removeDuplicates = true;
	}

	/**
	 * Insert a single job into the queue.
	 * @return bool true on success
	 */
	function insert() {
		$fields = $this->insertFields();

		$dbw = wfGetDB( DB_MASTER );

		if ( $this->removeDuplicates ) {
			$res = $dbw->select( 'job', array( '1' ), $fields, __METHOD__ );
			if ( $dbw->numRows( $res ) ) {
				return true;
			}
		}
		wfIncrStats( 'job-insert' );
		return $dbw->insert( 'job', $fields, __METHOD__ );
	}

	/**
	 * @return array
	 */
	protected function insertFields() {
		$dbw = wfGetDB( DB_MASTER );
		return array(
			'job_id' => $dbw->nextSequenceValue( 'job_job_id_seq' ),
			'job_cmd' => $this->command,
			'job_namespace' => $this->title->getNamespace(),
			'job_title' => $this->title->getDBkey(),
			'job_timestamp' => $dbw->timestamp(),
			'job_params' => Job::makeBlob( $this->params )
		);
	}

	/**
	 * Remove jobs in the job queue which are duplicates of this job.
	 * This is deadlock-prone and so starts its own transaction.
	 */
	function removeDuplicates() {
		if ( !$this->removeDuplicates ) {
			return;
		}

		$fields = $this->insertFields();
		unset( $fields['job_id'] );
		unset( $fields['job_timestamp'] );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );
		$dbw->delete( 'job', $fields, __METHOD__ );
		$affected = $dbw->affectedRows();
		$dbw->commit( __METHOD__ );
		if ( $affected ) {
			wfIncrStats( 'job-dup-delete', $affected );
		}
	}

	/**
	 * @return string
	 */
	function toString() {
		$paramString = '';
		if ( $this->params ) {
			foreach ( $this->params as $key => $value ) {
				if ( $paramString != '' ) {
					$paramString .= ' ';
				}
				$paramString .= "$key=$value";
			}
		}

		if ( is_object( $this->title ) ) {
			$s = "{$this->command} " . $this->title->getPrefixedDBkey();
			if ( $paramString !== '' ) {
				$s .= ' ' . $paramString;
			}
			return $s;
		} else {
			return "{$this->command} $paramString";
		}
	}

	protected function setLastError( $error ) {
		$this->error = $error;
	}

	function getLastError() {
		return $this->error;
	}
}
