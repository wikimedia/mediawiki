<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EmailConfirmedHook {
	/**
	 * When checking that the user's email address is "confirmed".
	 * This runs before the other checks, such as anonymity and the real check; return
	 * true to allow those checks to occur, and false if checking is done.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User being checked
	 * @param ?mixed &$confirmed Whether or not the email address is confirmed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailConfirmed( $user, &$confirmed );
}
