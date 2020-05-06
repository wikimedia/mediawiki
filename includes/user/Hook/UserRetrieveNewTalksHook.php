<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface UserRetrieveNewTalksHook {
	/**
	 * This hook is called when retrieving "You have new messages!" message(s).
	 *
	 * To override the notification, populate the $talks array and then return
	 * false. If the hook returns true, $talks will not be used.
	 *
	 * @since 1.35
	 *
	 * @param User $user User retrieving new talks messages
	 * @param array[] &$talks Array in which each element is an associative
	 *   array describing a notification, with the following keys:
	 *     - wiki: The database name of the wiki
	 *     - link: Root-relative link to the user's talk page
	 *     - rev: The last talk page revision that the user has seen or null. This
	 *       is useful for building diff links.
	 * @return bool|void False to use $talks, true or no return value to continue
	 */
	public function onUserRetrieveNewTalks( $user, &$talks );
}
