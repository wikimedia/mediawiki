<?php

namespace MediaWiki\Session\Hook;

use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserSetCookies" to register handlers implementing this interface.
 *
 * @deprecated since 1.27 If you're trying to replace core session
 *   cookie handling, you want to create a subclass of
 *   MediaWiki\Session\CookieSessionProvider instead. Otherwise,
 *   you can no longer count on user data being saved to cookies
 *   versus some other mechanism.
 * @ingroup Hooks
 */
interface UserSetCookiesHook {
	/**
	 * This hook is called when setting user cookies.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param array &$session Session array, will be added to the session
	 * @param string[] &$cookies Cookies array mapping cookie name to its value
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetCookies( $user, &$session, &$cookies );
}
