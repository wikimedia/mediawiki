<?php

namespace MediaWiki\Session\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserSetCookiesHook {
	/**
	 * DEPRECATED since 1.27! If you're trying to replace core
	 * session cookie handling, you want to create a subclass of
	 * MediaWiki\Session\CookieSessionProvider instead. Otherwise, you can no longer
	 * count on user data being saved to cookies versus some other mechanism.
	 * Called when setting user cookies.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$session session array, will be added to the session
	 * @param ?mixed &$cookies cookies array mapping cookie name to its value
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetCookies( $user, &$session, &$cookies );
}
