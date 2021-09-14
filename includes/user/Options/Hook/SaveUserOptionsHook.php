<?php

namespace MediaWiki\User\Options\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SaveUserOptions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SaveUserOptionsHook {
	/**
	 * This hook is called just before saving user preferences.
	 *
	 * Hook handlers can either add or manipulate options, or reset one back to its default
	 * to block changing it. Hook handlers are also allowed to abort the process by returning
	 * false, e.g. to save to a global profile instead. Compare to the UserSaveSettings
	 * hook, which is called after the preferences have been saved.
	 *
	 * @since 1.37
	 *
	 * @param UserIdentity $user The user for which the options are going to be saved
	 * @param array &$modifiedOptions The user's options as an associative array, modifiable.
	 *  To reset the preference value to default, set the preference to null.
	 *  To block the preference from changing, unset the key from the array.
	 *  To modify a preference value, set a new value.
	 * @param array $originalOptions The user's original options being replaced
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSaveUserOptions( UserIdentity $user, array &$modifiedOptions, array $originalOptions );
}
