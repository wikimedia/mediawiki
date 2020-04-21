<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AuthManagerLoginAuthenticateAuditHook {
	/**
	 * A login attempt either succeeded or failed
	 * for a reason other than misconfiguration or session loss. No return data is
	 * accepted; this hook is for auditing only.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $response The MediaWiki\Auth\AuthenticationResponse in either a PASS or FAIL
	 *   state.
	 * @param ?mixed $user The User object being authenticated against, or null if authentication
	 *   failed before getting that far.
	 * @param ?mixed $username A guess at the user name being authenticated, or null if we can't
	 *   even determine that. When $user is not null, it can be in the form of
	 *   <username>@<more info> (e.g. for bot passwords).
	 * @param ?mixed $extraData An array (string => string) with extra information, intended to be
	 *   added to log contexts. Fields it might include:
	 *   - appId: the application ID, only if the login was with a bot password
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthManagerLoginAuthenticateAudit( $response, $user,
		$username, $extraData
	);
}
