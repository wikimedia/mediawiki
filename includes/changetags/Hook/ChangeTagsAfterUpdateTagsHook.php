<?php

namespace MediaWiki\ChangeTags\Hook;

use RecentChange;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeTagsAfterUpdateTagsHook {
	/**
	 * This hook is called after tags have been updated with the ChangeTags::updateTags function.
	 *
	 * @since 1.35
	 *
	 * @param string[] $addedTags Tags effectively added in the update
	 * @param string[] $removedTags Tags effectively removed in the update
	 * @param string[] $prevTags Tags that were present prior to the update
	 * @param int $rc_id Recentchanges table id
	 * @param int $rev_id Revision table id
	 * @param int $log_id Logging table id
	 * @param string|null $params Tag params
	 * @param RecentChange|null $rc RecentChange being tagged when the tagging accompanies the
	 *   action, or null
	 * @param User|null $user User who performed the tagging when the tagging is subsequent to the
	 *   action, or null
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagsAfterUpdateTags( $addedTags, $removedTags,
		$prevTags, $rc_id, $rev_id, $log_id, $params, $rc, $user
	);
}
