<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLoadDefaults" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoadDefaultsHook {
	/**
	 * This hook is called when loading a default user.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param string $name User name
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadDefaults( $user, $name );
}
