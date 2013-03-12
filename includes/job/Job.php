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
 * The queue aspects of this class are now deprecated.
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

	/** @var Array Additional queue metadata */
	public $metadata = array();

	/*-------------------------------------------------------------------------
	 * Abstract functions
	 *------------------------------------------------------------------------*/

	/**
	 * Run the job
	 * @return boolean success
	 */
	abstract public function run();

	/*-------------------------------------------------------------------------
	 * Static functions
	 *------------------------------------------------------------------------*/

	/**
	 * Create the appropriate object to handle a specific job
	 *
	 * @param string $command Job command
	 * @param $title Title: Associated title
	 * @param array|bool $params Job parameters
	 * @param int $id Job identifier
	 * @throws MWException
	 * @return Job
	 */
	public static function factory( $command, Title $title, $params = false, $id = 0 ) {
		global $wgJobClasses;
		if( isset( $wgJobClasses[$command] ) ) {
			$class = $wgJobClasses[$command];
			return new $class( $title, $params, $id );
		}
		throw new MWException( "Invalid job command `{$command}`" );
	}

	/**
	 * Batch-insert a group of jobs into the queue.
	 * This will be wrapped in a transaction with a forced commit.
	 *
	 * This may add duplicate at insert time, but they will be
	 * removed later on, when the first one is popped.
	 *
	 * @param array $jobs of Job objects
	 * @return bool
	 * @deprecated 1.21
	 */
	public static function batchInsert( $jobs ) {
		return JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * Insert a group of jobs into the queue.
	 *
	 * Same as batchInsert() but does not commit and can thus
	 * be rolled-back as part of a larger transaction. However,
	 * large batches of jobs can cause slave lag.
	 *
	 * @param array $jobs of Job objects
	 * @return bool
	 * @deprecated 1.21
	 */
	public static function safeBatchInsert( $jobs ) {
		return JobQueueGroup::singleton()->push( $jobs, JobQueue::QoS_Atomic );
	}

	/**
	 * Pop a job of a certain type.  This tries less hard than pop() to
	 * actually find a job; it may be adversely affected by concurrent job
	 * runners.
	 *
	 * @param $type string
	 * @return Job|bool Returns false if there are no jobs
	 * @deprecated 1.21
	 */
	public static function pop_type( $type ) {
		return JobQueueGroup::singleton()->get( $type )->pop();
	}

	/**
	 * Pop a job off the front of the queue.
	 * This is subject to $wgJobTypesExcludedFromDefaultQueue.
	 *
	 * @return Job or false if there's no jobs
	 * @deprecated 1.21
	 */
	public static function pop() {
		return JobQueueGroup::singleton()->pop();
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
	public function __construct( $command, $title, $params = false, $id = 0 ) {
		$this->command = $command;
		$this->title = $title;
		$this->params = $params;
		$this->id = $id;

		$this->removeDuplicates = false; // expensive jobs may set this to true
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
	 * @return bool Whether only one of each identical set of jobs should be run
	 */
	public function ignoreDuplicates() {
		return $this->removeDuplicates;
	}

	/**
	 * @return bool Whether this job can be retried on failure by job runners
	 */
	public function allowRetries() {
		return true;
	}

	/**
	 * Subclasses may need to override this to make duplication detection work
	 *
	 * @return Array Map of key/values
	 */
	public function getDeduplicationInfo() {
		$info = array(
			'type'      => $this->getType(),
			'namespace' => $this->getTitle()->getNamespace(),
			'title'     => $this->getTitle()->getDBkey(),
			'params'    => $this->getParams()
		);
		// Identical jobs with different "root" jobs should count as duplicates
		if ( is_array( $info['params'] ) ) {
			unset( $info['params']['rootJobSignature'] );
			unset( $info['params']['rootJobTimestamp'] );
		}
		return $info;
	}

	/**
	 * @param string $key A key that identifies the task
	 * @return Array
	 */
	public static function newRootJobParams( $key ) {
		return array(
			'rootJobSignature' => sha1( $key ),
			'rootJobTimestamp' => wfTimestampNow()
		);
	}

	/**
	 * @return Array
	 */
	public function getRootJobParams() {
		return array(
			'rootJobSignature' => isset( $this->params['rootJobSignature'] )
				? $this->params['rootJobSignature']
				: null,
			'rootJobTimestamp' => isset( $this->params['rootJobTimestamp'] )
				? $this->params['rootJobTimestamp']
				: null
		);
	}

	/**
	 * Insert a single job into the queue.
	 * @return bool true on success
	 * @deprecated 1.21
	 */
	public function insert() {
		return JobQueueGroup::singleton()->push( $this );
	}

	/**
	 * @return string
	 */
	public function toString() {
		$paramString = '';
		if ( $this->params ) {
			foreach ( $this->params as $key => $value ) {
				if ( $paramString != '' ) {
					$paramString .= ' ';
				}
				if ( is_array( $value ) ) {
					$value = "array(" . count( $value ) . ")";
				} elseif ( is_object( $value ) && !method_exists( $value, '__toString' ) ) {
					$value = "object(" . get_class( $value ) . ")";
				}
				$value = (string)$value;
				if ( mb_strlen( $value ) > 1024 ) {
					$value = "string(" . mb_strlen( $value ) . ")";
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

	public function getLastError() {
		return $this->error;
	}
}
