<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsLockedHook {
	/**
	 * Check if the user is locked. See User::isLocked().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User in question.
	 * @param ?mixed &$locked Set true if the user should be locked.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsLocked( $user, &$locked );
}
