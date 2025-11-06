<?php

namespace MediaWiki\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLoginComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoginCompleteHook {
	/**
	 * Use this hook to show custom content after a user has logged in via the web interface.
	 * This includes both login and signup. It's also called by the action=login API (but not
	 * action=clientlogin or action=createaccount) for legacy reasons.
	 *
	 * For functionality that needs to run after any login (API or web) use UserLoggedIn.
	 *
	 * Before 1.42 this hook was also called when the user visited the login page with a returnto
	 * parameter while already logged in. Code that needs to run in that situation should use the
	 * PostLoginRedirect hook instead.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user object that was created on login
	 * @param string &$inject_html Any HTML to inject after the "logged in" message. (Setting it
	 *   to a non-empty value will also prevent redirects: instead of the user being sent back to
	 *   the page indicated by the returnto URL parameter, they will see a success page with
	 *   $inject_html and a link to the returnto page.)
	 *   On signup, the BeforeWelcomeCreation hook can further modify this value.
	 * @param bool $direct (bool) Unused, always true. Before 1.42, it was false when the hook
	 *   was called in situations other than a successful login or signup; since 1.42 those don't
	 *   happen anymore.
	 * @return bool|void True or no return value to continue or false to abort
	 * @see BeforeWelcomeCreationHook::onBeforeWelcomeCreation
	 */
	public function onUserLoginComplete( $user, &$inject_html, $direct );
}
