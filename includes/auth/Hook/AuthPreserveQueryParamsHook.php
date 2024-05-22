<?php

namespace MediaWiki\Auth\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AuthPreserveQueryParams" to register handlers implementing this interface.
 *
 * @unstable to implement
 * @ingroup Hooks
 */
interface AuthPreserveQueryParamsHook {
	/**
	 * This hook gets called any time an authentication page generates an URL which
	 * is in some sense part of the authentication process (e.g. a language switcher
	 * link, the login form's action URL, or the return URL for a remote identity provider)
	 * and needs to determine which of the current query parameters to preserve in that URL.
	 *
	 * @since 1.43
	 *
	 * @param array &$params Query parameters to preserve, name => value
	 * @param array $options Information about the purpose of the URL that's being generated
	 *    - reset (bool, default false): Reset the authentication process, i.e. omit
	 *      parameters which are related to continuing in-progress authentication.
	 *      This is used e.g. in the link for switching from the login form to the
	 *      signup form.
	 *
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthPreserveQueryParams( array &$params, array $options );
}
