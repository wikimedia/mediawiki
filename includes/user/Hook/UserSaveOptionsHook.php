<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserSaveOptionsHook {
	/**
	 * This hook is called just before saving user preferences.
	 *
	 * Hook handlers can either add or manipulate options, or reset one back to it's default
	 * to block changing it. Hook handlers are also allowed to abort the process by returning
	 * false, e.g. to save to a global profile instead. Compare to the UserSaveSettings
	 * hook, which is called after the preferences have been saved.
	 *
	 * @since 1.35
	 *
	 * @param User $user The User for which the options are going to be saved
	 * @param array &$options The users options as an associative array, modifiable
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSaveOptions( $user, &$options );
}
