<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserSaveSettings" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSaveSettingsHook {
	/**
	 * This hook is called directly after user settings have been saved to the database.
	 *
	 * Compare to the SaveUserOptions hook, which is called before saving and is only
	 * called for options managed by UserOptionsManager.
	 *
	 * @since 1.35
	 *
	 * @param User $user The User for which the settings have been saved
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSaveSettings( $user );
}
