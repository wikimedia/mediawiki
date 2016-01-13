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
 * @ingroup JobQueue
 * @since 1.27
 */
class JobQueueMemory extends JobQueue {
	/** @var IJobSpecification[] */
	protected $unclaimedJobs = array();
	/** @var IJobSpecification[] */
	protected $claimedJobs = array();

	protected function doBatchPush( array $jobs, $flags ) {
		/** @var IJobSpecification[] $jobs */
		foreach ( $jobs as $job ) {
			if ( $job->ignoreDuplicates() ) {
				$sha1 = Wikimedia\base_convert(
					sha1( serialize( $job->getDeduplicationInfo() ) ),
					16, 36, 31
				);
				if ( !isset( $this->unclaimedJobs[$sha1] ) ) {
					$this->unclaimedJobs[$sha1] = $job;
				}
			} else {
				$this->unclaimedJobs[] = $job;
			}
		}
	}

	protected function supportedOrders() {
		return array( 'random', 'timestamp', 'fifo' );
	}

	protected function optimalOrder() {
		return array( 'fifo' );
	}

	protected function doIsEmpty() {
		return !count( $this->unclaimedJobs );
	}

	protected function doGetSize() {
		return count( $this->unclaimedJobs );
	}

	protected function doGetAcquiredCount() {
		return count( $this->claimedJobs );
	}

	protected function doPop() {
		if ( !count( $this->unclaimedJobs ) ) {
			return false;
		}

		if ( $this->order === 'random' ) {
			$key = array_rand( $this->unclaimedJobs );
		} else {
			reset( $this->unclaimedJobs );
			$key = key( $this->unclaimedJobs );
		}

		$spec = $this->unclaimedJobs[$key];
		unset( $this->unclaimedJobs[$key] );
		$this->claimedJobs[] = $spec;

		$job = $this->jobFromSpecInternal( $spec );
		$job->metadata['claimId'] = count( $this->claimedJobs ) - 1;

		return $job;
	}

	protected function doAck( Job $job ) {
		unset( $this->claimedJobs[$job->metadata['claimId']] );
	}

	protected function doDelete() {
		$this->claimedJobs = array();
		$this->unclaimedJobs = array();
	}

	public function getAllQueuedJobs() {
		$that = $this;

		return new MappedIterator(
			$this->unclaimedJobs,
			function ( $value ) use ( $that ) {
				$that->jobFromSpecInternal( $value );
			}
		);
	}

	public function getAllAcquiredJobs() {
		$that = $this;

		return new MappedIterator(
			$this->claimedJobs,
			function ( $value ) use ( $that ) {
				$that->jobFromSpecInternal( $value );
			}
		);
	}

	public function jobFromSpecInternal( IJobSpecification $spec ) {
		return Job::factory( $spec->getType(), $spec->getTitle(), $spec->getParams() );
	}
}