<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoadDefaultsHook {
	/**
	 * Called when loading a default user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user object
	 * @param ?mixed $name user name
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadDefaults( $user, $name );
}
