<?php

namespace MediaWiki\RenameUser\Hook;

use MediaWiki\RenameUser\RenameuserSQL;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RenameUserSQL" to register handlers implementing this interface.
 *
 * Between MediaWiki 1.36 and 1.39, this interface was part of the Renameuser extension,
 * with the namespace MediaWiki\Extension\Renameuser\Hook.
 *
 * @stable to implement
 * @ingroup Hooks
 * @since 1.40
 */
interface RenameUserSQLHook {

	/**
	 * Called in the constructer of RenameuserSQL (which performs the actual renaming of users).
	 *
	 * @param RenameuserSQL $renameUserSql
	 */
	public function onRenameUserSQL( RenameuserSQL $renameUserSql ): void;

}
