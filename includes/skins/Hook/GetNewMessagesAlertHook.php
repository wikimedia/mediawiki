<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetNewMessagesAlertHook {
	/**
	 * Disable or modify the new messages alert
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$newMessagesAlert An empty string by default. If the user has new talk page
	 *   messages, this should be populated with an alert message to that effect
	 * @param ?mixed $newtalks An empty array if the user has no new messages or an array
	 *   containing links and revisions if there are new messages (See
	 *   User::getNewMessageLinks)
	 * @param ?mixed $user The user object of the user who is loading the page
	 * @param ?mixed $out OutputPage object (to check what type of page the user is on)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetNewMessagesAlert( &$newMessagesAlert, $newtalks, $user,
		$out
	);
}
