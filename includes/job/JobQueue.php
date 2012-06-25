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
 * Class to handle enqueueing and running of background jobs
 *
 * @ingroup JobQueue
 * @since 1.20
 */
abstract class JobQueue {
	protected $wiki; // string; wiki ID
	protected $type; // string; job type

	/**
	 * @param $params array
	 */
	protected function __construct( array $params ) {
		$this->wiki = $params['wiki'];
		$this->type = $params['type'];
	}

	/**
	 * Get a job queue object of the specified type.
	 * $params includes:
	 *     class : what job class to use (determines job type)
	 *     wiki  : wiki ID of the wiki the jobs are for (defaults to current wiki)
	 *     type  : The name of the job types this queue handles
	 *
	 * @param $params array
	 * @return JobQueue
	 * @throws MWException
	 */
	final public static function factory( array $params ) {
		$class = $params['class'];
		if ( !MWInit::classExists( $class ) ) {
			throw new MWException( "Invalid job queue class '$class'." );
		}
		$obj = new $class( $params );
		if ( !( $obj instanceof self ) ) {
			throw new MWException( "Class '$class' is not a " . __CLASS__ . " class." );
		}
		return $obj;
	}

	/**
	 * @return string
	 */
	final public function getWiki() {
		return $this->wiki;
	}

	/**
	 * Get the job type that this queue handles
	 *
	 * @return string
	 */
	final public function getType() {
		return $this->type;
	}

	/**
	 * Push a batch of jobs into the queue
	 *
	 * @param $jobs array List of Jobs
	 * @return bool
	 */
	final public function batchPush( array $jobs ) {
		foreach ( $jobs as $job ) {
			if ( $job->getType() !== $this->type ) {
				throw new MWException( "Got '{$job->getType()}' job; expected '{$this->type}'." );
			}
		}
		wfProfileIn( __METHOD__ );
		$ok = $this->doBatchPush( $jobs );
		if ( $ok ) {
			wfIncrStats( 'job-insert', count( $jobs ) );
		}
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueue::batchPush()
	 * @return bool
	 */
	abstract protected function doBatchPush( array $jobs );

	/**
	 * Pop a job off of the queue
	 *
	 * @return Job|bool Returns false on failure
	 */
	final public function pop() {
		wfProfileIn( __METHOD__ );
		$job = $this->doPop();
		if ( $job ) {
			wfIncrStats( 'job-pop' );
		}
		wfProfileOut( __METHOD__ );
		return $job;
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job
	 */
	abstract protected function doPop();

	/**
	 * Acknowledge that a job was completed
	 *
	 * @param $job Job
	 * @return bool
	 */
	final public function ack( Job $job ) {
		if ( $job->getType() !== $this->type ) {
			throw new MWException( "Got '{$job->getType()}' job; expected '{$this->type}'." );
		}
		wfProfileIn( __METHOD__ );
		$ok = $this->doAck( $job );
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueue::ack()
	 * @return bool
	 */
	abstract protected function doAck( Job $job );

	/**
	 * Wait for any slaves or backup servers to catch up
	 *
	 * @return void
	 */
	final public function waitForBackups() {
		wfProfileIn( __METHOD__ );
		$this->doWaitForBackups();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @see JobQueue::waitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {}
}
