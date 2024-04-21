<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\IReadableDatabase;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DeleteUnknownPreferences" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DeleteUnknownPreferencesHook {
	/**
	 * This hook is called by the cleanupPreferences.php maintenance script
	 * to build a WHERE clause with which to delete preferences that are not
	 * known about. This hook is used by extensions that have dynamically-named
	 * preferences that should not be deleted in the usual cleanup process.
	 * For example, the Gadgets extension creates preferences prefixed with
	 * 'gadget-', so anything with that prefix is excluded from the deletion.
	 *
	 * @since 1.35
	 *
	 * @param array &$where Array that will be passed as the $cond parameter to
	 *   IReadableDatabase::select() to determine what will be deleted from the user_properties
	 *   table
	 * @param IReadableDatabase $db IReadableDatabase object,
	 *  useful for accessing $db->expr() to build expressions with IExpression::LIKE and LikeValue
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDeleteUnknownPreferences( &$where, $db );
}
