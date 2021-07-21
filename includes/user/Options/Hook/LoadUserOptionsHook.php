<?php

namespace MediaWiki\User\Options\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LoadUserOptions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LoadUserOptionsHook {
	/**
	 * This hook is called when user options/preferences are being loaded from the database.
	 *
	 * @since 1.37
	 *
	 * @param UserIdentity $user
	 * @param array &$options Options, can be modified.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onLoadUserOptions( UserIdentity $user, array &$options ): void;
}
