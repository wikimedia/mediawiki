<?php
/**
 * Handler for triggering the enqueuing of lazy-pushed jobs
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
 */

use Wikimedia\Assert\Assert;

/**
 * Enqueue lazy-pushed jobs that have accumulated from JobQueueGroup
 *
 * @ingroup JobQueue
 * @since 1.33
 */
class JobQueueEnqueueUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (domain ID => IJobSpecification[]) */
	private $jobsByDomain;

	/**
	 * @param string $domain DB domain ID
	 * @param IJobSpecification[] $jobs
	 */
	public function __construct( $domain, array $jobs ) {
		$this->jobsByDomain[$domain] = $jobs;
	}

	public function merge( MergeableUpdate $update ) {
		/** @var self $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var self $update';

		foreach ( $update->jobsByDomain as $domain => $jobs ) {
			$this->jobsByDomain[$domain] = $this->jobsByDomain[$domain] ?? [];
			$this->jobsByDomain[$domain] = array_merge( $this->jobsByDomain[$domain], $jobs );
		}
	}

	public function doUpdate() {
		foreach ( $this->jobsByDomain as $domain => $jobs ) {
			$group = JobQueueGroup::singleton( $domain );
			try {
				$group->push( $jobs );
			} catch ( Exception $e ) {
				// Get in as many jobs as possible and let other post-send updates happen
				MWExceptionHandler::logException( $e );
			}
		}
	}
}
