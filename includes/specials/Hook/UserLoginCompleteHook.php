<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoginCompleteHook {
	/**
	 * Show custom content after a user has logged in via the Web
	 * interface. For functionality that needs to run after any login (API or web) use
	 * UserLoggedIn.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user object that was created on login
	 * @param ?mixed &$inject_html Any HTML to inject after the "logged in" message.
	 * @param ?mixed $direct (bool) The hook is called directly after a successful login. This will
	 *   only happen once per login. A UserLoginComplete call with direct=false can
	 *   happen when the user visits the login page while already logged in.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoginComplete( $user, &$inject_html, $direct );
}
