<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRequiresHTTPSHook {
	/**
	 * Called to determine whether a user needs
	 * to be switched to HTTPS.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User in question.
	 * @param ?mixed &$https Boolean whether $user should be switched to HTTPS.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserRequiresHTTPS( $user, &$https );
}
