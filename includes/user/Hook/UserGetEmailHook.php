<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetEmailHook {
	/**
	 * Called when getting an user email address.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$email email, change this to override local email
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmail( $user, &$email );
}
