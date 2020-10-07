<?php

namespace MediaWiki\User\Hook;

use stdClass;
use User;

/**
 * @stable to implement
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
