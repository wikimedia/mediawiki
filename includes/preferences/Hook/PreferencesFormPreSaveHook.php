<?php

namespace MediaWiki\Preferences\Hook;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PreferencesFormPreSave" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PreferencesFormPreSaveHook {
	/**
	 * Use this hook to override preferences being saved.
	 *
	 * @since 1.35
	 *
	 * @param array $formData Array of user submitted data
	 * @param HTMLForm $form HTMLForm object, also a ContextSource
	 * @param User $user User with preferences to be saved
	 * @param bool &$result Boolean indicating success
	 * @param array $oldUserOptions Array with user's old options (before save)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPreferencesFormPreSave( $formData, $form, $user, &$result,
		$oldUserOptions
	);
}
