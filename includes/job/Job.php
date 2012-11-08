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
	 * @param $command String: Job command
	 * @param $title Title: Associated title
	 * @param $params Array|bool: Job parameters
	 * @param $id Int: Job identifier
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
	 * @param $jobs array of Job objects
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
	 * @param $jobs array of Job objects
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
	 * @return Job
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
	 * @return bool
	 */
	public function ignoreDuplicates() {
		return $this->removeDuplicates;
	}

	/**
	 * Subclasses may need to override this to make duplication detection work
	 *
	 * @return Array
	 */
	public function getDeduplicationFields() {
		return array_diff_key(
			array(
				'type'      => $this->getType(),
				'namespace' => $this->getTitle()->getNamespace(),
				'title'     => $this->getTitle()->getDBkey(),
				'params'    => $this->getParams(),
			),
			// Identical jobs with different "root" jobs should count as duplicates
			array( 'rootJobSignature', 'rootJobTimestamp' )
		);
	}

	/**
	 * @param $key string A key that identifies the task
	 * @return Array
	 */
	public static function newRootJobParams( $key ) {
		return array(
			'rootJobSignature' => sha1( $key ),
			'rootJobTimestamp' => wfTimestamp( TS_MW )
		);
	}

	/**
	 * Re-align the start/end parameter of a list of jobs ordered by range partition.
	 * This will make sure that none of the ranges overlap with each other. This is
	 * useful for avoiding trivial range differences that can break job de-duplication.
	 *
	 * @param $jobs array
	 * @param string $sfield
	 * @param string $efield
	 * @param integer $align
	 * @return Array List of the new (start,end) tuples
	 */
	public function alignRangePartitions( array &$jobs, $sfield, $efield, $align ) {
		$ranges = array();
		$lastEnd = 0; // previous range ending
		foreach ( $jobs as $job ) {
			if ( !isset( $job->params[$sfield] ) || !isset( $job->params[$efield] ) ) {
				throw new MWException( "Job parameters do not include start/end field." );
			}
			if ( $align > 1 ) {
				if ( $lastEnd === false ) {
					throw new MWException( "Expected end of partition ranges." );
				}
				if ( $job->params[$sfield] !== false ) {
					// Align the start parameter to $align
					$job->params[$sfield] -= ( $job->params[$sfield] % $align );
					// Don't overlap with the last partition range end
					$job->params[$sfield] = max( $lastEnd + 1, $job->params[$sfield] );
				}
				if ( $job->params[$efield] !== false ) {
					// Partition range end must be greater then or equal to start
					if ( $job->params[$sfield] !== false ) {
						$job->params[$efield] = max( $job->params[$sfield], $job->params[$efield] );
					}
					// Align the end parameter to $align...
					$remainder = ( $job->params[$efield] + 1 ) % $align;
					if ( $remainder != 0 ) {
						$job->params[$efield] += ( $align - $remainder );
					}
				}
				$lastEnd = $job->params[$efield];
			}
			if ( $job->params[$efield] && $job->params[$efield] < $job->params[$sfield] ) {
				throw new MWException( "Job start/end parameters are in the wrong order." );
			}
			$ranges[] = array( $job->params[$sfield], $job->params[$efield] );
		}
		#var_dump( $ranges ); moo();
		return $ranges;
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
