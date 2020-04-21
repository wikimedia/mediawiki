<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserToolLinksEditHook {
	/**
	 * Called when generating a list of user tool links, e.g.
	 * "Foobar (Talk | Contribs | Block)".
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $userId User id of the current user
	 * @param ?mixed $userText User name of the current user
	 * @param ?mixed &$items Array of user tool links as HTML fragments
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserToolLinksEdit( $userId, $userText, &$items );
}
