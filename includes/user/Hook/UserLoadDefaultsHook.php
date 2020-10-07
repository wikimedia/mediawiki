<?php

namespace MediaWiki\User\Hook;

use User;

/**
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
