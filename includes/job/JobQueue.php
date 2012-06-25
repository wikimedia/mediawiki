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
 * Class to handle enqueueing of background jobs
 *
 * @ingroup JobQueue
 * @since 1.20
 */
class JobQueueGroup {
	/** @var Array */
	protected static $instances = array();

	protected $wiki; // string; wiki ID

	/**
	 * @param $wiki string Wiki ID
	 */
	protected function __construct( $wiki ) {
		$this->wiki = $wiki;
	}

	/**
	 * @param $wiki string Wiki ID
	 * @return JobQueueGroup
	 */
	public static function singleton( $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( self::$instances[$wiki] ) ) {
			self::$instances[$wiki] = new self( $wiki );
		}
		return self::$instances[$wiki];
	}

	/**
	 * Insert jobs into the respective queues of with the belong
	 *
	 * @param $jobs Job|array A single Job or a list of Jobs
	 * @return bool
	 */
	public function push( $jobs ) {
		$jobs = (array)$jobs;

		$jobsByType = array();
		foreach ( $jobs as $job ) {
			$jobsByType[$job->getType()][] = $job;
		}

		$ok = true;
		foreach ( $jobsByType as $type => $jobs ) {
			if ( !$this->get( $type )->batchPush( $jobs ) ) {
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * Get the queue object for a given queue type
	 *
	 * @param $type string
	 * @return JobQueue
	 */
	public function get( $type ) {
		global $wgJobTypeConf;
		$conf = false;
		if ( isset( $wgJobTypeConf[$type] ) ) {
			$conf = $wgJobTypeConf[$type];
		} else {
			$conf = $wgJobTypeConf['default'];
		}

		return JobQueue::factory( array(
			'class' => $conf['class'],
			'wiki'  => $this->wiki,
			'store' => $conf['store'],
			'type' => $type,
		) );
	}

	/**
	 * Get the list of queue types
	 *
	 * @return array List of strings
	 */
	public function getQueueTypes() {
		global $wgJobClasses;

		return array_keys( $wgJobClasses );
	}

	/**
	 * Get the list of default queue types
	 *
	 * @return array List of strings
	 */
	public function getDefaultQueueTypes() {
		global $wgJobTypesExcludedFromDefaultQueue;

		return array_diff( $this->getQueueTypes(), $wgJobTypesExcludedFromDefaultQueue );
	}
}

/**
 * Class to handle enqueueing and running of background jobs
 *
 * @ingroup JobQueue
 * @since 1.20
 */
abstract class JobQueue {
	/** @var JobQueueStore */
	protected $store;

	protected $wiki; // string; wiki ID

	/**
	 * @param $params array
	 */
	protected function __construct( array $params ) {
		$this->wiki  = $params['wiki'];
		$this->store = JobQueueStore::factory( $params['store'], $this->wiki );
	}

	/**
	 * Get a job queue object of the specified type.
	 * $params includes:
	 *     class : what job class to use (determines job type)
	 *     wiki  : wiki ID of the wiki the jobs are for (defaults to current wiki)
	 *     store : name of the job queue storage system
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
	abstract public function getType();

	/**
	 * Push a batch of jobs into the queue
	 *
	 * @param $jobs array List of Jobs
	 * @return bool
	 */
	public function batchPush( array $jobs ) {
		$type = $this->getType();
		foreach ( $jobs as $job ) {
			if ( $job->getType() !== $type ) {
				throw new MWException( "Got '{$job->getType()}' job; expected '{$type}'." );
			}
		}

		return $this->store->batchInsert( $this->getType(), $jobs );
	}

	/**
	 * Pop a job off of the queue
	 *
	 * @return Job|bool Returns false on failure
	 */
	public function pop() {
		return $this->store->pop( $this->getType() );
	}

	/**
	 * Acknowledge that a job was completed
	 *
	 * @param $job Job
	 * @return bool
	 */
	public function ack( Job $job ) {
		return $this->store->ack( $this->getType(), $job );
	}

	/**
	 * Wait for any slaves or disk backup servers to catch up
	 *
	 * @return void
	 */
	public function waitForBackup() {
		$this->store->waitForBackup( $this->getType() );
	}
}

/**
 * Class to handle storage of job queues
 *
 * @ingroup JobQueue
 * @since 1.20
 */
abstract class JobQueueStore {
	/**
	 * @param $params array
	 * @param $wiki string Wiki ID
	 */
	protected function __construct( array $params, $wiki ) {
		$this->wiki = $wiki;
	}

	/**
	 * @param $name string Store name
	 * @param $wiki string Wiki ID
	 * @return JobQueueStore
	 * @throws MWException
	 */
	final public static function factory( $name, $wiki ) {
		global $wgJobQueueStores;

		if ( !isset( $wgJobQueueStores[$name] ) ) {
			throw new MWException( "No job queue store name '$name'." );
		}
		$conf = $wgJobQueueStores[$name];

		$class = $conf['class'];
		$obj = new $class( $conf, $wiki );
		if ( !( $obj instanceof self ) ) {
			throw new MWException( "Class '$class' is not a " . __CLASS__ . " class." );
		}
		return $obj;
	}

	/**
	 * Push a batch of jobs into the queue
	 *
	 * @param $type string Job type (each should have its own internal queue)
	 * @param $jobs array List of Jobs
	 * @return bool
	 */
	final public function batchInsert( $type, array $jobs ) {
		wfProfileIn( __METHOD__ );
		$ok = $this->doBatchInsert( $jobs );
		if ( $ok ) {
			wfIncrStats( 'job-insert', count( $jobs ) );
		}
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueueStore::batchInsert()
	 * @return bool
	 */
	abstract protected function doBatchInsert( $type, array $jobs );

	/**
	 * Pop a job off the queue
	 *
	 * @param $type string Job type (each should have its own internal queue)
	 * @return Job|bool Returns false on failure
	 */
	final public function pop( $type ) {
		wfProfileIn( __METHOD__ );
		$job = $this->doPop( $type );
		if ( $job ) {
			wfIncrStats( 'job-pop' );
		}
		wfProfileOut( __METHOD__ );
		return $job;
	}

	/**
	 * @see JobQueueStore::pop()
	 * @return Job
	 */
	abstract protected function doPop( $type );

	/**
	 * Acknowledge a completed job that was popped from the queue
	 *
	 * @param $type string Job type (each should have its own internal queue)
	 * @param $job Job
	 * @return bool
	 */
	final public function ack( $type, Job $job ) {
		wfProfileIn( __METHOD__ );
		$ok = $this->doAck( $type, $job );
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueueStore::pop()
	 * @return bool
	 */
	abstract protected function doAck( $type, Job $job );

	/**
	 * Wait for any slaves or disk backup servers to catch up
	 *
	 * @param $type string Job type (each should have its own internal queue)
	 * @return void
	 */
	final public function waitForBackup( $type ) {
		wfProfileIn( __METHOD__ );
		$this->doWaitForBackup( $type );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @see JobQueueStore::waitForBackup()
	 * @return void
	 */
	protected function doWaitForBackup( $type ) {}
}
