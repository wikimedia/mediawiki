<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsBotHook {
	/**
	 * Use this hook to establish whether a user is a bot account
	 *
	 * @since 1.35
	 *
	 * @param User $user the user
	 * @param bool &$isBot whether this is user a bot or not (boolean)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBot( $user, &$isBot );
}
