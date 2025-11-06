<?php

namespace MediaWiki\Hook;

use MediaWiki\Output\OutputPage;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetNewMessagesAlert" to register handlers implementing this interface.
 *
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
	 *   Skin::getNewtalks().)
	 * @param User $user User who is loading the page
	 * @param OutputPage $out To check what type of page the user is on
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetNewMessagesAlert( &$newMessagesAlert, $newtalks, $user,
		$out
	);
}
