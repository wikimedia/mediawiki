<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagsAllowedAddHook {
	/**
	 * Called when checking if a user can add tags to a change.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$allowedTags List of all the tags the user is allowed to add. Any tags the
	 *   user wants to add ($addTags) that are not in this array will cause it to fail.
	 *   You may add or remove tags to this array as required.
	 * @param ?mixed $addTags List of tags user intends to add.
	 * @param ?mixed $user User who is adding the tags.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagsAllowedAdd( &$allowedTags, $addTags, $user );
}
