<?php

namespace MediaWiki\Auth\Hook;

use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LocalUserCreated" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalUserCreatedHook {
	/**
	 * This hook is called when a local user has been created.
	 *
	 * After this hook User::saveSettings is called to save all modification done in the hook handlers,
	 * like changed user properties or changed user preferences.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object for the created user
	 * @param bool $autocreated Whether this was an auto-creation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalUserCreated( $user, $autocreated );
}
