<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeUserGroupsHook {
	/**
	 * Called before user groups are changed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $performer The User who will perform the change
	 * @param ?mixed $user The User whose groups will be changed
	 * @param ?mixed &$add The groups that will be added
	 * @param ?mixed &$remove The groups that will be removed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeUserGroups( $performer, $user, &$add, &$remove );
}
