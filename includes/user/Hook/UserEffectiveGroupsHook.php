<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserEffectiveGroupsHook {
	/**
	 * Called in User::getEffectiveGroups().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User to get groups for
	 * @param ?mixed &$groups Current effective groups
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserEffectiveGroups( $user, &$groups );
}
