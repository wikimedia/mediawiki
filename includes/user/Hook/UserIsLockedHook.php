<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserIsLockedHook {
	/**
	 * Use this hook to establish that a user is locked. See User::isLocked().
	 *
	 * @since 1.35
	 *
	 * @param User $user User in question.
	 * @param bool &$locked Set true if the user should be locked.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsLocked( $user, &$locked );
}
