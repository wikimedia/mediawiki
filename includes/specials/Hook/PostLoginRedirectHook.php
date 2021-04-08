<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PostLoginRedirect" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PostLoginRedirectHook {
	/**
	 * Use this hook to modify the post login redirect behavior.
	 *
	 * Occurs after signing up or logging in, allows for interception of redirect.
	 *
	 * @since 1.35
	 *
	 * @param string &$returnTo The page name to return to, as a string
	 * @param string[] &$returnToQuery Array of url parameters, mapping parameter names to values
	 * @param string &$type Type of login redirect as string:
	 *   - error: display a return to link ignoring $wgRedirectOnLogin
	 *   - signup: display a return to link using $wgRedirectOnLogin if needed
	 *   - success: display a return to link using $wgRedirectOnLogin if needed
	 *   - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPostLoginRedirect( &$returnTo, &$returnToQuery, &$type );
}
