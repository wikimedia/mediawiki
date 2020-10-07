<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoadOptionsHook {
	/**
	 * This hook is called when user options/preferences are being loaded from the database.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param array &$options Options, can be modified.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadOptions( $user, &$options );
}
