<?php

namespace MediaWiki\User\Options\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LocalUserOptionsStoreSave" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalUserOptionsStoreSaveHook {
	/**
	 * This hook is called just after saving preferences in {@link LocalUserOptionsStore}
	 *
	 * Hook handlers cannot modify the new preferences as the changes have been saved.
	 * This will only allow handlers to see the changes in specifically local options
	 * storage as opposed to any storge (which would be handled using {@link SaveUserOptionsHook}).
	 *
	 * @since 1.45
	 *
	 * @param UserIdentity $user The user for which the options have been saved
	 * @param array $oldOptions The user's old local options
	 * @param array $newOptions The user's new local options
	 * @return void
	 */
	public function onLocalUserOptionsStoreSave( UserIdentity $user, array $oldOptions, array $newOptions ): void;
}
