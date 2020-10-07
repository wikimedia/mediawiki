<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLogoutCompleteHook {
	/**
	 * This hook is called after a user has logged out.
	 *
	 * @since 1.35
	 *
	 * @param User $user the user object _after_ logout (won't have name, ID, etc.)
	 * @param string &$inject_html Any HTML to inject after the "logged out" message.
	 * @param string $oldName name of the user before logout (string)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLogoutComplete( $user, &$inject_html, $oldName );
}
