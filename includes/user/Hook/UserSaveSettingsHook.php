<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSaveSettingsHook {
	/**
	 * This hook is called directly after user preferences have been saved to the database.
	 *
	 * Compare to the UserSaveOptions hook, which is called before saving.
	 *
	 * @since 1.35
	 *
	 * @param User $user The User for which the options have been saved
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSaveSettings( $user );
}
