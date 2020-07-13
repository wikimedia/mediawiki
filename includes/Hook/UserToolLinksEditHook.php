<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserToolLinksEditHook {
	/**
	 * This hook is called when generating a list of user tool links, e.g.
	 * "Foobar (Talk | Contribs | Block)".
	 *
	 * @since 1.35
	 *
	 * @param int $userId User ID of the current user
	 * @param string $userText Username of the current user
	 * @param string[] &$items Array of user tool links as HTML fragments
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserToolLinksEdit( $userId, $userText, &$items );
}
