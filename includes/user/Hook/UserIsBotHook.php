<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserIsBotHook {
	/**
	 * Use this hook to establish whether a user is a bot account.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user
	 * @param bool &$isBot Whether this is user a bot or not (boolean)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBot( $user, &$isBot );
}
