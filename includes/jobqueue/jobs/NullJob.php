<?php
/**
 * Degenerate job that does nothing.
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
 * @ingroup JobQueue
 */

/**
 * Degenerate job that does nothing, but can optionally replace itself
 * in the queue and/or sleep for a brief time period. These can be used
 * to represent "no-op" jobs or test lock contention and performance.
 *
 * @par Example:
 * Inserting a null job in the configured job queue:
 * @code
 * $ php maintenance/eval.php
 * > $queue = JobQueueGroup::singleton();
 * > $job = new NullJob( Title::newMainPage(), array( 'lives' => 10 ) );
 * > $queue->push( $job );
 * @endcode
 * You can then confirm the job has been enqueued by using the showJobs.php
 * maintenance utility:
 * @code
 * $ php maintenance/showJobs.php --group
 * null: 1 queue; 0 claimed (0 active, 0 abandoned)
 * $
 * @endcode
 *
 * @ingroup JobQueue
 */
class NullJob extends Job {
	/**
	 * @param Title $title
	 * @param array $params Job parameters (lives, usleep)
	 */
	function __construct( $title, $params ) {
		parent::__construct( 'null', $title, $params );
		if ( !isset( $this->params['lives'] ) ) {
			$this->params['lives'] = 1;
		}
		if ( !isset( $this->params['usleep'] ) ) {
			$this->params['usleep'] = 0;
		}
		$this->removeDuplicates = !empty( $this->params['removeDuplicates'] );
	}

	public function run() {
		if ( $this->params['usleep'] > 0 ) {
			usleep( $this->params['usleep'] );
		}
		if ( $this->params['lives'] > 1 ) {
			$params = $this->params;
			$params['lives']--;
			$job = new self( $this->title, $params );
			JobQueueGroup::singleton()->push( $job );
		}

		return true;
	}
}
