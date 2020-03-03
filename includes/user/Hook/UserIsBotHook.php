<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsBotHook {
	/**
	 * when determining whether a user is a bot account
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user
	 * @param ?mixed &$isBot whether this is user a bot or not (boolean)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBot( $user, &$isBot );
}
