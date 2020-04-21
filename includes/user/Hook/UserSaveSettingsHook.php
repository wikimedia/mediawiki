<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserSaveSettingsHook {
	/**
	 * Called directly after user preferences (user_properties in
	 * the database) have been saved. Compare to the UserSaveOptions hook, which is
	 * called before.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user The User for which the options have been saved
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSaveSettings( $user );
}
