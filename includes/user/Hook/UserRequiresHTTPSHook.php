<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRequiresHTTPSHook {
	/**
	 * This hook is called to determine whether a user needs to be switched to HTTPS.
	 *
	 * @since 1.35
	 *
	 * @param User $user User in question.
	 * @param bool &$https Boolean whether $user should be switched to HTTPS.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserRequiresHTTPS( $user, &$https );
}
