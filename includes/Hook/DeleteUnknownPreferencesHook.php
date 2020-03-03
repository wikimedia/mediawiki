<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DeleteUnknownPreferencesHook {
	/**
	 * Called by the cleanupPreferences.php maintenance
	 * script to build a WHERE clause with which to delete preferences that are not
	 * known about. This hook is used by extensions that have dynamically-named
	 * preferences that should not be deleted in the usual cleanup process. For
	 * example, the Gadgets extension creates preferences prefixed with 'gadget-', and
	 * so anything with that prefix is excluded from the deletion.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$where An array that will be passed as the $cond parameter to
	 *   IDatabase::select() to determine what will be deleted from the user_properties
	 *   table.
	 * @param ?mixed $db The IDatabase object, useful for accessing $db->buildLike() etc.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDeleteUnknownPreferences( &$where, $db );
}
