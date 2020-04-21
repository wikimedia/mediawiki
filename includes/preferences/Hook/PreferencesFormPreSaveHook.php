<?php

namespace MediaWiki\Preferences\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PreferencesFormPreSaveHook {
	/**
	 * Override preferences being saved
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $formData array of user submitted data
	 * @param ?mixed $form HTMLForm object, also a ContextSource
	 * @param ?mixed $user User object with preferences to be saved set
	 * @param ?mixed &$result boolean indicating success
	 * @param ?mixed $oldUserOptions array with user old options (before save)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesFormPreSave( $formData, $form, $user, &$result,
		$oldUserOptions
	);
}
