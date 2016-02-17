<?php
/**
 * PHP memory-backed job queue code.
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
 * Class to handle job queues stored in PHP memory for testing
 *
 * JobQueueGroup does not remember every queue instance, so statically track it here
 *
 * @ingroup JobQueue
 * @since 1.27
 */
class JobQueueMemory extends JobQueue {
	/** @var array[] */
	protected static $data = [];

	/**
	 * @see JobQueue::doBatchPush
	 *
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		$unclaimed =& $this->getQueueData( 'unclaimed', [] );

		foreach ( $jobs as $job ) {
			if ( $job->ignoreDuplicates() ) {
				$sha1 = Wikimedia\base_convert(
					sha1( serialize( $job->getDeduplicationInfo() ) ),
					16, 36, 31
				);
				if ( !isset( $unclaimed[$sha1] ) ) {
					$unclaimed[$sha1] = $job;
				}
			} else {
				$unclaimed[] = $job;
			}
		}
	}

	/**
	 * @see JobQueue::supportedOrders
	 *
	 * @return string[]
	 */
	protected function supportedOrders() {
		return [ 'random', 'timestamp', 'fifo' ];
	}

	/**
	 * @see JobQueue::optimalOrder
	 *
	 * @return string
	 */
	protected function optimalOrder() {
		return 'fifo';
	}

	/**
	 * @see JobQueue::doIsEmpty
	 *
	 * @return bool
	 */
	protected function doIsEmpty() {
		return ( $this->doGetSize() == 0 );
	}

	/**
	 * @see JobQueue::doGetSize
	 *
	 * @return int
	 */
	protected function doGetSize() {
		$unclaimed = $this->getQueueData( 'unclaimed' );

		return $unclaimed ? count( $unclaimed ) : 0;
	}

	/**
	 * @see JobQueue::doGetAcquiredCount
	 *
	 * @return int
	 */
	protected function doGetAcquiredCount() {
		$claimed = $this->getQueueData( 'claimed' );

		return $claimed ? count( $claimed ) : 0;
	}

	/**
	 * @see JobQueue::doPop
	 *
	 * @return Job|bool
	 */
	protected function doPop() {
		if ( $this->doGetSize() == 0 ) {
			return false;
		}

		$unclaimed =& $this->getQueueData( 'unclaimed' );
		$claimed =& $this->getQueueData( 'claimed', [] );

		if ( $this->order === 'random' ) {
			$key = array_rand( $unclaimed );
		} else {
			reset( $unclaimed );
			$key = key( $unclaimed );
		}

		$spec = $unclaimed[$key];
		unset( $unclaimed[$key] );
		$claimed[] = $spec;

		$job = $this->jobFromSpecInternal( $spec );

		end( $claimed );
		$job->metadata['claimId'] = key( $claimed );

		return $job;
	}

	/**
	 * @see JobQueue::doAck
	 *
	 * @param Job $job
	 */
	protected function doAck( Job $job ) {
		if ( $this->getAcquiredCount() == 0 ) {
			return;
		}

		$claimed =& $this->getQueueData( 'claimed' );
		unset( $claimed[$job->metadata['claimId']] );
	}

	/**
	 * @see JobQueue::doDelete
	 */
	protected function doDelete() {
		if ( isset( self::$data[$this->type][$this->wiki] ) ) {
			unset( self::$data[$this->type][$this->wiki] );
			if ( !self::$data[$this->type] ) {
				unset( self::$data[$this->type] );
			}
		}
	}

	/**
	 * @see JobQueue::getAllQueuedJobs
	 *
	 * @return Iterator of Job objects.
	 */
	public function getAllQueuedJobs() {
		$unclaimed = $this->getQueueData( 'unclaimed' );
		if ( !$unclaimed ) {
			return new ArrayIterator( [] );
		}

		return new MappedIterator(
			$unclaimed,
			function ( $value ) {
				$this->jobFromSpecInternal( $value );
			}
		);
	}

	/**
	 * @see JobQueue::getAllAcquiredJobs
	 *
	 * @return Iterator of Job objects.
	 */
	public function getAllAcquiredJobs() {
		$claimed = $this->getQueueData( 'claimed' );
		if ( !$claimed ) {
			return new ArrayIterator( [] );
		}

		return new MappedIterator(
			$claimed,
			function ( $value ) {
				$this->jobFromSpecInternal( $value );
			}
		);
	}

	/**
	 * @param IJobSpecification $spec
	 *
	 * @return Job
	 */
	public function jobFromSpecInternal( IJobSpecification $spec ) {
		return Job::factory( $spec->getType(), $spec->getTitle(), $spec->getParams() );
	}

	/**
	 * @param string $field
	 * @param mixed $init
	 *
	 * @return mixed
	 */
	private function &getQueueData( $field, $init = null ) {
		if ( !isset( self::$data[$this->type][$this->wiki][$field] ) ) {
			if ( $init !== null ) {
				self::$data[$this->type][$this->wiki][$field] = $init;
			} else {
				return $init;
			}
		}

		return self::$data[$this->type][$this->wiki][$field];
	}
}
