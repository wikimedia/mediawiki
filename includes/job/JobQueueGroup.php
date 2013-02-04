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
 * @author Aaron Schulz
 */

/**
 * Class to handle enqueueing of background jobs
 *
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueGroup {
	/** @var Array */
	protected static $instances = array();

	/** @var ProcessCacheLRU */
	protected $cache;

	protected $wiki; // string; wiki ID

	const TYPE_DEFAULT = 1; // integer; jobs popped by default
	const TYPE_ANY     = 2; // integer; any job

	const USE_CACHE = 1; // integer; use process cache

	const PROC_CACHE_TTL = 15; // integer; seconds

	/**
	 * @param $wiki string Wiki ID
	 */
	protected function __construct( $wiki ) {
		$this->wiki = $wiki;
		$this->cache = new ProcessCacheLRU( 1 );
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
	 * Destroy the singleton instances
	 *
	 * @return void
	 */
	public static function destroySingletons() {
		self::$instances = array();
	}

	/**
	 * @param $type string
	 * @return JobQueue Job queue object for a given queue type
	 */
	public function get( $type ) {
		global $wgJobTypeConf;

		$conf = array( 'wiki' => $this->wiki, 'type' => $type );
		if ( isset( $wgJobTypeConf[$type] ) ) {
			$conf = $conf + $wgJobTypeConf[$type];
		} else {
			$conf = $conf + $wgJobTypeConf['default'];
		}

		return JobQueue::factory( $conf );
	}

	/**
	 * Insert jobs into the respective queues of with the belong.
	 * This inserts the jobs into the queue specified by $wgJobTypeConf.
	 *
	 * @param $jobs Job|array A single Job or a list of Jobs
	 * @throws MWException
	 * @return bool
	 */
	public function push( $jobs ) {
		$jobs = is_array( $jobs ) ? $jobs : array( $jobs );

		$jobsByType = array(); // (job type => list of jobs)
		foreach ( $jobs as $job ) {
			if ( $job instanceof Job ) {
				$jobsByType[$job->getType()][] = $job;
			} else {
				throw new MWException( "Attempted to push a non-Job object into a queue." );
			}
		}

		$ok = true;
		foreach ( $jobsByType as $type => $jobs ) {
			if ( !$this->get( $type )->push( $jobs ) ) {
				$ok = false;
			}
		}

		if ( $this->cache->has( 'queues-ready', 'list' ) ) {
			$list = $this->cache->get( 'queues-ready', 'list' );
			if ( count( array_diff( array_keys( $jobsByType ), $list ) ) ) {
				$this->cache->clear( 'queues-ready' );
			}
		}

		return $ok;
	}

	/**
	 * Pop a job off one of the job queues
	 *
	 * @param $queueType integer JobQueueGroup::TYPE_* constant
	 * @param $flags integer Bitfield of JobQueueGroup::USE_* constants
	 * @return Job|bool Returns false on failure
	 */
	public function pop( $queueType = self::TYPE_DEFAULT, $flags = 0 ) {
		if ( $flags & self::USE_CACHE ) {
			if ( !$this->cache->has( 'queues-ready', 'list', self::PROC_CACHE_TTL ) ) {
				$this->cache->set( 'queues-ready', 'list', $this->getQueuesWithJobs() );
			}
			$types = $this->cache->get( 'queues-ready', 'list' );
		} else {
			$types = $this->getQueuesWithJobs();
		}

		if ( $queueType == self::TYPE_DEFAULT ) {
			$types = array_intersect( $types, $this->getDefaultQueueTypes() );
		}
		shuffle( $types ); // avoid starvation

		foreach ( $types as $type ) { // for each queue...
			$job = $this->get( $type )->pop();
			if ( $job ) { // found
				return $job;
			} else { // not found
				$this->cache->clear( 'queues-ready' );
			}
		}

		return false; // no jobs found
	}

	/**
	 * Acknowledge that a job was completed
	 *
	 * @param $job Job
	 * @return bool
	 */
	public function ack( Job $job ) {
		return $this->get( $job->getType() )->ack( $job );
	}

	/**
	 * Register the "root job" of a given job into the queue for de-duplication.
	 * This should only be called right *after* all the new jobs have been inserted.
	 *
	 * @param $job Job
	 * @return bool
	 */
	public function deduplicateRootJob( Job $job ) {
		return $this->get( $job->getType() )->deduplicateRootJob( $job );
	}

	/**
	 * Get the list of queue types
	 *
	 * @return array List of strings
	 */
	public function getQueueTypes() {
		return array_keys( $this->getCachedConfigVar( 'wgJobClasses' ) );
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

	/**
	 * @return Array List of job types that have non-empty queues
	 */
	public function getQueuesWithJobs() {
		$types = array();
		foreach ( $this->getQueueTypes() as $type ) {
			if ( !$this->get( $type )->isEmpty() ) {
				$types[] = $type;
			}
		}
		return $types;
	}

	/**
	 * @return Array List of default job types that have non-empty queues
	 */
	public function getDefaultQueuesWithJobs() {
		$types = array();
		foreach ( $this->getDefaultQueueTypes() as $type ) {
			if ( !$this->get( $type )->isEmpty() ) {
				$types[] = $type;
			}
		}
		return $types;
	}

	private function getCachedConfigVar( $name ) {
		global $wgConf, $wgMemc;

		if ( $this->wiki === wfWikiID() ) {
			return $GLOBALS[$name]; // common case
		} else {
			list( $db, $prefix ) = wfSplitWikiID( $this->wiki );
			$key = wfForeignMemcKey( $db, $prefix, 'configvalue', $name );
			$value = $wgMemc->get( $key ); // ('v' => ...) or false
			if ( is_array( $value ) ) {
				return $value['v'];
			} else {
				$value = $wgConf->getConfig( $this->wiki, $name );
				$wgMemc->set( $key, array( 'v' => $value ), 86400 + mt_rand( 0, 86400 ) );
				return $value;
			}
		}
	}
}
