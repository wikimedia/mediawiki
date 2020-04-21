<?php

namespace MediaWiki\Preferences\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetPreferencesHook {
	/**
	 * Modify user preferences.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User whose preferences are being modified.
	 * @param ?mixed &$preferences Preferences description array, to be fed to an HTMLForm object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetPreferences( $user, &$preferences );
}
