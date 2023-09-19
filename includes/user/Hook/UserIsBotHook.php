<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserIsBot" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserIsBotHook {
	/**
	 * Use this hook to establish whether a user is a bot account.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param bool &$isBot Whether this is user a bot or not (boolean)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBot( $user, &$isBot );
}
