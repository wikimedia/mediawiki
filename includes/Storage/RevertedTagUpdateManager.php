<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Storage;

use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\RevertedTagUpdateJob;

/**
 * Class for managing delayed RevertedTagUpdateJob waiting for user approval.
 *
 * This is intended to be used by the patrol subsystem and various content
 * management extensions.
 *
 * @since 1.36
 * @author Ostrzyciel
 */
class RevertedTagUpdateManager {

	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var EditResultCache */
	private $editResultCache;

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
		if ( $editResult === null || !$editResult->isRevert() ) {
			return false;
		}

		$spec = RevertedTagUpdateJob::newSpec( $revertRevisionId, $editResult );
		$this->jobQueueGroup->lazyPush( $spec );
		return true;
	}
}
