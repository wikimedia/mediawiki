<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoadAfterLoadFromSessionHook {
	/**
	 * Called to authenticate users on external or
	 * environmental means; occurs after session is loaded.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user object being loaded
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadAfterLoadFromSession( $user );
}
