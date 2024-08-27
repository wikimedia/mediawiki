<?php

namespace MediaWiki\Auth\Hook;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AuthManagerVerifyAuthentication" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AuthManagerVerifyAuthenticationHook {
	/**
	 * This hook is called before the end of a successful login or account creation attempt.
	 * It can be used to deny authentication.
	 * (For account creation, "before end" means after the primary provider returned
	 * PASS. For login, it is after all secondaries have passed or abstained.)
	 *
	 * The hook is only called during AuthManager workflows (i.e. not when using the
	 * action=login API). After an account creation (which is implemented internally by
	 * AuthManager as a separate account creation + login), it is only called for the account
	 * creation part. It is not called at all for account linking (where AuthManager does not
	 * do any work).
	 *
	 * @param UserIdentity|null $user The user who has successfully authenticated. For login,
	 *   the user will exist at this point (possibly autocreated during the authentication
	 *   process). For account creation, the user will not exist yet, and returning false will
	 *   prevent its creation. (There is no guarantee it will prevent the primary provider from
	 *   creating some sort of central account, though - that probably happened already by the
	 *   time the hook is called.)
	 *   Null is only possible during a login attempt, when the response did not contain a
	 *   username (i.e. the primary provider identified the user via their remote account, but
	 *   did not suggest any local username). When not interrupted by the hook, such a login
	 *   attempt will be treated as a failure but the following login or signup might result
	 *   in the identified remote account being linked to a local one.
	 * @param AuthenticationResponse &$response For login, the final response returned by
	 *   AuthManager. For account creation, the response returned by the primary provider.
	 *   Always a PASS or (when $user is null) a RESTART.
	 *   When the hook handler returns false, it must replace this with a FAIL response  that
	 *   describes the reason for the failure.
	 * @param AuthManager $authManager The AuthManager instance that is handling the request.
	 *   This can be used to access the request object, or to access authentication session data
	 *   (the hook is guaranteed to be called when the authentication session still exists).
	 * @param array $info Additional information:
	 *   - 'action': (string) The action being performed, one of the AuthManager::ACTION_*
	 *     constants (LOGIN or CREATE).
	 *   - 'primaryId': (string) The unique ID of the primary authentication provider that
	 *     processed the request.
	 * @return bool|void True or no return value to continue or false to abort authentication
	 *   (in which case $response must be updated).
	 * @since 1.43
	 */
	public function onAuthManagerVerifyAuthentication(
		?UserIdentity $user,
		AuthenticationResponse &$response,
		AuthManager $authManager,
		array $info
	);
}
