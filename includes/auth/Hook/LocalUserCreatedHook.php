<?php

namespace MediaWiki\Auth\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalUserCreatedHook {
	/**
	 * This hook is called when a local user has been created.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object for the created user
	 * @param bool $autocreated Whether this was an auto-creation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalUserCreated( $user, $autocreated );
}
