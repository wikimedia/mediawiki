<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EmailConfirmedHook {
	/**
	 * This hook is called when checking that the user's email address is "confirmed".
	 *
	 * This runs before the other checks, such as anonymity and the real check; return
	 * true to allow those checks to occur, and false if checking is done.
	 *
	 * @since 1.35
	 *
	 * @param User $user User being checked
	 * @param bool &$confirmed Whether or not the email address is confirmed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailConfirmed( $user, &$confirmed );
}
