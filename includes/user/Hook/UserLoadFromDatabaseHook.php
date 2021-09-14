<?php

namespace MediaWiki\User\Hook;

use stdClass;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLoadFromDatabase" to register handlers implementing this interface.
 *
 * @deprecated since 1.37
 * @ingroup Hooks
 */
interface UserLoadFromDatabaseHook {
	/**
	 * This hook is called when loading a user from the database.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param stdClass|bool &$s Database query object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadFromDatabase( $user, &$s );
}
