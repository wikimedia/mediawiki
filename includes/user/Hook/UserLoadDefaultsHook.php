<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoadDefaultsHook {
	/**
	 * This hook is called when loading a default user.
	 *
	 * @since 1.35
	 *
	 * @param User $user user object
	 * @param string $name user name
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadDefaults( $user, $name );
}
