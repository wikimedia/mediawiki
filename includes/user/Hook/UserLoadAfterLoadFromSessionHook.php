<?php

namespace MediaWiki\User\Hook;

use User;

/**
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
