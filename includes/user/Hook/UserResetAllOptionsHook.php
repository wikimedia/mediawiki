<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserResetAllOptionsHook {
	/**
	 * This hook is called when user preferences have been requested to be reset.
	 *
	 * This hook can be used to exclude certain options from being reset even when the user
	 * has requested that all preferences to be reset, because certain options might be stored
	 * in the user_properties database table despite not being visible and editable via
	 * Special:Preferences.
	 *
	 * @since 1.35
	 *
	 * @param User $user the User (object) whose preferences are being reset
	 * @param array &$newOptions array of new (site default) preferences
	 * @param array $options array of the user's old preferences
	 * @param array $resetKinds array containing the kinds of preferences to reset
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserResetAllOptions(
		$user,
		&$newOptions,
		$options,
		$resetKinds
	);
}
