<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRetrieveNewTalksHook {
	/**
	 * This hook is called when retrieving "You have new messages!" message(s).
	 *
	 * @since 1.35
	 *
	 * @param User $user user retrieving new talks messages
	 * @param array &$talks array of new talks page(s)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserRetrieveNewTalks( $user, &$talks );
}
