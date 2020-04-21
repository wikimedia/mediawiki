<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UsersPagerDoBatchLookupsHook {
	/**
	 * Called in UsersPager::doBatchLookups() to give
	 * extensions providing user group data from an alternate source a chance to add
	 * their data into the cache array so that things like global user groups are
	 * displayed correctly in Special:ListUsers.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $dbr Read-only database handle
	 * @param ?mixed $userIds Array of user IDs whose groups we should look up
	 * @param ?mixed &$cache Array of user ID -> (array of internal group name (e.g. 'sysop') ->
	 *   UserGroupMembership object)
	 * @param ?mixed &$groups Array of group name -> bool true mappings for members of a given user
	 *   group
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUsersPagerDoBatchLookups( $dbr, $userIds, &$cache, &$groups );
}
