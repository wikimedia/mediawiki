<?php

namespace MediaWiki\Hook;

use MediaWiki\Session\Session;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TempUserCreatedRedirect" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface TempUserCreatedRedirectHook {
	/**
	 * This hook is called after an action causes a temporary user to be
	 * created or after the first successful edit for a temporary account.
	 * The handler may modify the redirect URL.
	 *
	 * To differentiate between invocations of this hook where a) the account
	 * was created in the current request and b) the account existed, but made
	 * a first successful edit, one can examine the registration date of the
	 * account.
	 *
	 * @since 1.39
	 *
	 * @param Session $session
	 * @param UserIdentity $user
	 * @param string $returnTo The prefixed DB key of the title to redirect to
	 * @param string $returnToQuery An extra query part
	 * @param string $returnToAnchor Either an empty string or a fragment beginning with "#"
	 * @param string &$redirectUrl The URL to redirect to
	 * @return bool|null
	 */
	public function onTempUserCreatedRedirect(
		Session $session,
		UserIdentity $user,
		string $returnTo,
		string $returnToQuery,
		string $returnToAnchor,
		&$redirectUrl
	);
}
