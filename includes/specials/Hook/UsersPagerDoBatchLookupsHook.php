<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\IReadableDatabase;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UsersPagerDoBatchLookups" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UsersPagerDoBatchLookupsHook {
	/**
	 * This hook is called in UsersPager::doBatchLookups()
	 *
	 * It is used to give extensions providing user group data from an alternate source a
	 * chance to add their data into the cache array so that things like global user groups are
	 * displayed correctly in Special:ListUsers.
	 *
	 * @since 1.35
	 *
	 * @param IReadableDatabase $dbr Read-only database handle
	 * @param int[] $userIds Array of user IDs whose groups we should look up
	 * @param array &$cache Array of user ID -> (array of internal group name (e.g. 'sysop') ->
	 *   UserGroupMembership object)
	 * @param array &$groups Array of group name -> bool true mappings for members of a given user
	 *   group
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUsersPagerDoBatchLookups( $dbr, $userIds, &$cache, &$groups );
}
