<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetAutoPromoteGroups" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetAutoPromoteGroupsHook {
	/**
	 * This hook is called when determining which autopromote groups a user is entitled to be in.
	 *
	 * @since 1.35
	 *
	 * @param User $user User to promote
	 * @param string[] &$promote Groups that will be added
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetAutoPromoteGroups( $user, &$promote );
}
