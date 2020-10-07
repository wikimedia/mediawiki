<?php

namespace MediaWiki\Preferences\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetPreferencesHook {
	/**
	 * Use this hook to modify user preferences.
	 *
	 * @since 1.35
	 *
	 * @param User $user User whose preferences are being modified
	 * @param array &$preferences Preferences description array, to be fed to an HTMLForm object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetPreferences( $user, &$preferences );
}
