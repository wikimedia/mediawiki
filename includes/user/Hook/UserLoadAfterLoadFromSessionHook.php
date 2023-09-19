<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLoadAfterLoadFromSession" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoadAfterLoadFromSessionHook {
	/**
	 * This hook is called to authenticate users on external or environmental means.
	 *
	 * This hook is called after session is loaded.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object being loaded
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadAfterLoadFromSession( $user );
}
