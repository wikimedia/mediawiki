<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSaveOptionsHook {
	/**
	 * This hook is called just before saving user preferences.
	 *
	 * Hook handlers can either add or manipulate options, or reset one back to its default
	 * to block changing it. Hook handlers are also allowed to abort the process by returning
	 * false, e.g. to save to a global profile instead. Compare to the UserSaveSettings
	 * hook, which is called after the preferences have been saved.
	 *
	 * @since 1.35
	 *
	 * @param User $user The User for which the options are going to be saved
	 * @param array &$options The user's options as an associative array, modifiable
	 * @param array $originalOptions The user's original options being replaced
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSaveOptions( $user, &$options, $originalOptions );
}
