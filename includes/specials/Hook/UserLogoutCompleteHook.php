<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLogoutCompleteHook {
	/**
	 * After a user has logged out.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user object _after_ logout (won't have name, ID, etc.)
	 * @param ?mixed &$inject_html Any HTML to inject after the "logged out" message.
	 * @param ?mixed $oldName name of the user before logout (string)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLogoutComplete( $user, &$inject_html, $oldName );
}
