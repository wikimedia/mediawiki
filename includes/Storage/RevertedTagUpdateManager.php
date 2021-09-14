<?php
/**
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
 *
 * @author Ostrzyciel
 */

namespace MediaWiki\Storage;

use JobQueueGroup;
use RevertedTagUpdateJob;

/**
 * Class for managing delayed RevertedTagUpdateJob waiting for user approval.
 *
 * This is intended to be used by the patrol subsystem and various content
 * management extensions.
 *
 * @since 1.36
 */
class RevertedTagUpdateManager {

	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var EditResultCache */
	private $editResultCache;

	/**
	 * @param EditResultCache $editResultCache
	 * @param JobQueueGroup $jobQueueGroup
	 */
	public function __construct(
		EditResultCache $editResultCache,
		JobQueueGroup $jobQueueGroup
	) {
		$this->jobQueueGroup = $jobQueueGroup;
		$this->editResultCache = $editResultCache;
	}

	/**
	 * Enqueue a RevertedTagUpdateJob for the given revision, if needed. Call this when the
	 * user "approves" the edit.
	 *
	 * This method is also called whenever the edit is patrolled or autopatrolled.
	 *
	 * @param int $revertRevisionId
	 *
	 * @return bool Whether the update was enqueued successfully
	 */
	public function approveRevertedTagForRevision( int $revertRevisionId ): bool {
		$editResult = $this->editResultCache->get( $revertRevisionId );
		if ( $editResult === null ) {
			return false;
		}

		$spec = RevertedTagUpdateJob::newSpec( $revertRevisionId, $editResult );
		$this->jobQueueGroup->lazyPush( $spec );
		return true;
	}
}
