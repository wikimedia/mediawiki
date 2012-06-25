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
 * This queue aspects of this class are now deprecated.
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
	 * @deprecated 1.20
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
	 * @deprecated 1.20
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
	 * @deprecated 1.20
	 */
	static function batchInsert( $jobs ) {
		return JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * Insert a group of jobs into the queue.
	 *
	 * Same as batchInsert() but does not commit and can thus
	 * be rolled-back as part of a larger transaction. However,
	 * large batches of jobs can cause slave lag.
	 *
	 * @param $jobs array of Job objects
	 * @deprecated 1.20
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
	 * @deprecated 1.20
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
	 * @return integer May be 0 for jobs stored outside the DB
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->command;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @return bool
	 */
	public function ignoreDuplicates() {
		return $this->removeDuplicates;
	}

	/**
	 * Insert a single job into the queue.
	 * @return bool true on success
	 * @deprecated 1.20
	 */
	function insert() {
		return JobQueueGroup::singleton()->push( $this );
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
	 * @deprecated 1.20
	 */
	protected function removeDuplicates() {
		if ( !$this->removeDuplicates ) {
			return;
		}

		$fields = $this->insertFields();
		unset( $fields['job_id'] );
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
