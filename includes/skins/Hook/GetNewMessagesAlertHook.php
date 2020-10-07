<?php

namespace MediaWiki\Hook;

use OutputPage;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetNewMessagesAlertHook {
	/**
	 * Use this hook to disable or modify the new messages alert.
	 *
	 * @since 1.35
	 *
	 * @param string &$newMessagesAlert Empty string by default. If the user has new talk page
	 *   messages, this should be populated with an alert message to that effect.
	 * @param array $newtalks Empty array if the user has no new messages, or an array
	 *   containing links and revisions if there are new messages. (See
	 *   User::getNewMessageLinks.)
	 * @param User $user User who is loading the page
	 * @param OutputPage $out To check what type of page the user is on
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetNewMessagesAlert( &$newMessagesAlert, $newtalks, $user,
		$out
	);
}
