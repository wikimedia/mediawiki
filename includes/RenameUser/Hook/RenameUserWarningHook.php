<?php

namespace MediaWiki\RenameUser\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RenameUserWarning" to register handlers implementing this interface.
 *
 * Between MediaWiki 1.36 and 1.39, this interface was part of the Renameuser extension,
 * with the namespace MediaWiki\Extension\Renameuser\Hook.
 *
 * @stable to implement
 * @ingroup Hooks
 * @since 1.40
 */
interface RenameUserWarningHook {

	/**
	 * Called on Special:Renameuser before a user is renamed.
	 * Will show the given warnings to the user and ask for a confirmation.
	 *
	 * @param string $oldUsername The old username as a page title.
	 * @param string $newUsername The new username as a page title.
	 * @param array &$warnings An array with 1 or more message keys, and 1 or more parameters
	 * for the warnings to be shown
	 */
	public function onRenameUserWarning( string $oldUsername, string $newUsername, array &$warnings ): void;

}
