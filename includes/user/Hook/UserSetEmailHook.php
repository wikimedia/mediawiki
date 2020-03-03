<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserSetEmailHook {
	/**
	 * Called when changing user email address.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$email new email, change this to override new email address
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetEmail( $user, &$email );
}
