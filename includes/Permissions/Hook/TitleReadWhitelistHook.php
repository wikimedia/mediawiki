<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleReadWhitelistHook {
	/**
	 * Called at the end of read permissions checks, just before
	 * adding the default error message if nothing allows the user to read the page. If
	 * a handler wants a title to *not* be whitelisted, it should also return false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object being checked against
	 * @param ?mixed $user Current user object
	 * @param ?mixed &$whitelisted Boolean value of whether this title is whitelisted
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleReadWhitelist( $title, $user, &$whitelisted );
}
