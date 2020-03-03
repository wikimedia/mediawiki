<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoadFromDatabaseHook {
	/**
	 * Called when loading a user from the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user object
	 * @param ?mixed &$s database query object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadFromDatabase( $user, &$s );
}
