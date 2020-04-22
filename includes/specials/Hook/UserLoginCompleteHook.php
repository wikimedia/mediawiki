<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoginCompleteHook {
	/**
	 * Use this hook to show custom content after a user has logged in via the Web interface.
	 *
	 * For functionality that needs to run after any login (API or web) use UserLoggedIn.
	 *
	 * @since 1.35
	 *
	 * @param User $user the user object that was created on login
	 * @param string &$inject_html Any HTML to inject after the "logged in" message.
	 * @param bool $direct (bool) The hook is called directly after a successful login. This will
	 *   only happen once per login. A UserLoginComplete call with direct=false can
	 *   happen when the user visits the login page while already logged in.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoginComplete( $user, &$inject_html, $direct );
}
