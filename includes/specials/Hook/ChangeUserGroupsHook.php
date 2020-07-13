<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeUserGroupsHook {
	/**
	 * This hook is called before user groups are changed.
	 *
	 * @since 1.35
	 *
	 * @param User $performer The User who will perform the change
	 * @param User $user The User whose groups will be changed
	 * @param array &$add The groups that will be added
	 * @param array &$remove The groups that will be removed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeUserGroups( $performer, $user, &$add, &$remove );
}
