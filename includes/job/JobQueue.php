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
 * @author Aaron Schulz
 */

/**
 * Class to handle enqueueing and running of background jobs
 *
 * @ingroup JobQueue
 * @since 1.21
 */
abstract class JobQueue {
	protected $wiki; // string; wiki ID
	protected $type; // string; job type
	protected $order; // string; job priority for pop()

	const QoS_Atomic = 1; // integer; "all-or-nothing" job insertions

	/**
	 * @param $params array
	 */
	protected function __construct( array $params ) {
		$this->wiki  = $params['wiki'];
		$this->type  = $params['type'];
		$this->order = isset( $params['order'] ) ? $params['order'] : 'random';
	}

	/**
	 * Get a job queue object of the specified type.
	 * $params includes:
	 *     class : What job class to use (determines job type)
	 *     wiki  : wiki ID of the wiki the jobs are for (defaults to current wiki)
	 *     type  : The name of the job types this queue handles
	 *     order : Order that pop() selects jobs, either "timestamp" or "random".
	 *             If "timestamp" is used, the queue will effectively be FIFO. Note that
	 *             pop() will not be exactly FIFO, and even if it was, job completion would
	 *             not appear to be exactly FIFO since jobs can take different times to finish.
	 *             If "random" is used, pop() will pick jobs in random order. This might be
	 *             useful for improving concurrency depending on the queue storage medium.
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
	 * @return string Wiki ID
	 */
	final public function getWiki() {
		return $this->wiki;
	}

	/**
	 * @return string Job type that this queue handles
	 */
	final public function getType() {
		return $this->type;
	}

	/**
	 * Quickly check if the queue is empty.
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * @return bool
	 */
	final public function isEmpty() {
		wfProfileIn( __METHOD__ );
		$res = $this->doIsEmpty();
		wfProfileOut( __METHOD__ );
		return $res;
	}

	/**
	 * @see JobQueue::isEmpty()
	 * @return bool
	 */
	abstract protected function doIsEmpty();

	/**
	 * Push a batch of jobs into the queue
	 *
	 * @param $jobs array List of Jobs
	 * @param $flags integer Bitfield (supports JobQueue::QoS_Atomic)
	 * @return bool
	 */
	final public function batchPush( array $jobs, $flags = 0 ) {
		foreach ( $jobs as $job ) {
			if ( $job->getType() !== $this->type ) {
				throw new MWException( "Got '{$job->getType()}' job; expected '{$this->type}'." );
			}
		}
		wfProfileIn( __METHOD__ );
		$ok = $this->doBatchPush( $jobs, $flags );
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
	abstract protected function doBatchPush( array $jobs, $flags );

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
	 * Register the "root job" of a given job into the queue for de-duplication.
	 * This should only be called right *after* all the new jobs have been inserted.
	 * This is used to turn older, duplicate, job entries into no-ops. The root job
	 * information will remain in the registry until it simply falls out of cache.
	 *
	 * This requires that $job has two special fields in the "params" array:
	 *   - rootJobSignature : hash (e.g. SHA1) that identifies the task
	 *   - rootJobTimestamp : TS_MW timestamp of this instance of the task
	 *
	 * A "root job" is a conceptual job that consist of potentially many smaller jobs
	 * that are actually inserted into the queue. For example, "refreshLinks" jobs are
	 * spawned when a template is edited. One can think of the task as "update links
	 * of pages that use template X" and an instance of that task as a "root job".
	 * However, what actually goes into the queue are potentially many refreshLinks2 jobs.
	 * Since these jobs include things like page ID ranges and DB master positions, and morph
	 * into smaller refreshLinks2 jobs recursively, simple duplicate detection (like job_sha1)
	 * for individual jobs being identical is not useful.
	 *
	 * In the case of "refreshLinks", if these jobs are still in the queue when the template
	 * is edited again, we want all of these old refreshLinks jobs for that template to become
	 * no-ops. This can greatly reduce server load, since refreshLinks jobs involves parsing.
	 * Essentially, the new batch of jobs belong to a new "root job" and the older ones to a
	 * previous "root job" for the same task of "update links of pages that use template X".
	 *
	 * @param $job Job
	 * @return bool
	 */
	final public function deduplicateRootJob( Job $job ) {
		if ( $job->getType() !== $this->type ) {
			throw new MWException( "Got '{$job->getType()}' job; expected '{$this->type}'." );
		}
		wfProfileIn( __METHOD__ );
		$ok = $this->doDeduplicateRootJob( $job );
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @param $job Job
	 * @return bool
	 */
	protected function doDeduplicateRootJob( Job $job ) {
		return true;
	}

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
