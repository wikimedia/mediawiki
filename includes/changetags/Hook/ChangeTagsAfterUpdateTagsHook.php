<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagsAfterUpdateTagsHook {
	/**
	 * Called after tags have been updated with the
	 * ChangeTags::updateTags function. Params:
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $addedTags tags effectively added in the update
	 * @param ?mixed $removedTags tags effectively removed in the update
	 * @param ?mixed $prevTags tags that were present prior to the update
	 * @param ?mixed $rc_id recentchanges table id
	 * @param ?mixed $rev_id revision table id
	 * @param ?mixed $log_id logging table id
	 * @param ?mixed $params tag params
	 * @param ?mixed $rc RecentChange being tagged when the tagging accompanies the action, or null
	 * @param ?mixed $user User who performed the tagging when the tagging is subsequent to the
	 *   action, or null
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagsAfterUpdateTags( $addedTags, $removedTags,
		$prevTags, $rc_id, $rev_id, $log_id, $params, $rc, $user
	);
}
