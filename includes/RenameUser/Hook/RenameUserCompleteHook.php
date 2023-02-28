<?php

namespace MediaWiki\RenameUser\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RenameUserComplete" to register handlers implementing this interface.
 *
 * Between MediaWiki 1.36 and 1.39, this interface was part of the Renameuser extension,
 * with the namespace MediaWiki\Extension\Renameuser\Hook.
 *
 * @stable to implement
 * @ingroup Hooks
 * @since 1.40
 */
interface RenameUserCompleteHook {

	/**
	 * Called after a user was renamed.
	 *
	 * @param int $uid The user ID
	 * @param string $old The old username
	 * @param string $new The new username
	 */
	public function onRenameUserComplete( int $uid, string $old, string $new ): void;

}
