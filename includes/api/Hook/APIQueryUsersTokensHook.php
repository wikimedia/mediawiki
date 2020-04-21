<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIQueryUsersTokensHook {
	/**
	 * DEPRECATED since 1.24! Use ApiQueryTokensRegisterTypes
	 * instead.
	 * Use this hook to add custom token to list=users. Every token has an action,
	 * which will be used in the ustoken parameter and in the output
	 * (actiontoken="..."), and a callback function which should return the token, or
	 * false if the user isn't allowed to obtain it. The prototype of the callback
	 * function is func($user) where $user is the User object. In the hook, just add
	 * your callback to the $tokenFunctions array and return true (returning false
	 * makes no sense).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tokenFunctions [ action => callback ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQueryUsersTokens( &$tokenFunctions );
}
