<?php

namespace MediaWiki\Permissions\Hook;

use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TitleReadWhitelist" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleReadWhitelistHook {
	/**
	 * This hook is called at the end of read permissions checks, just before
	 * adding the default error message if nothing allows the user to read the page.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being checked against
	 * @param User $user Current user
	 * @param bool &$whitelisted Whether this title is whitelisted
	 * @return bool|void True or no return value to continue, or false to *not* whitelist
	 *   the title
	 */
	public function onTitleReadWhitelist( $title, $user, &$whitelisted );
}
