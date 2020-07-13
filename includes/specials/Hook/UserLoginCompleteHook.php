<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoginCompleteHook {
	/**
	 * Use this hook to show custom content after a user has logged in via the web interface.
	 *
	 * For functionality that needs to run after any login (API or web) use UserLoggedIn.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user object that was created on login
	 * @param string &$inject_html Any HTML to inject after the "logged in" message.
	 * @param bool $direct (bool) The hook is called directly after a successful login. This will
	 *   only happen once per login. A UserLoginComplete call with direct=false can
	 *   happen when the user visits the login page while already logged in.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoginComplete( $user, &$inject_html, $direct );
}
