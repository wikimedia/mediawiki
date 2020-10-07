<?php

namespace MediaWiki\Auth\Hook;

use MediaWiki\Auth\AuthenticationResponse;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AuthManagerLoginAuthenticateAuditHook {
	/**
	 * This hook is called when a login attempt either succeeds or fails
	 * for a reason other than misconfiguration or session loss. No return data is
	 * accepted; this hook is for auditing only.
	 *
	 * @since 1.35
	 *
	 * @param AuthenticationResponse $response Response in either a PASS or FAIL state
	 * @param User|null $user User being authenticated against, or null if authentication
	 *   failed before getting that far
	 * @param string $username A guess at the username being authenticated, or null if we can't
	 *   even determine that. When $user is not null, it can be in the form of
	 *   <username>@<more info> (e.g. for bot passwords).
	 * @param string[] $extraData Array (string => string) with extra information, intended to be
	 *   added to log contexts. Fields it might include:
	 *   - appId: application ID, only if the login was with a bot password
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthManagerLoginAuthenticateAudit( $response, $user,
		$username, $extraData
	);
}
