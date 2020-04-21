<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsBlockedFromHook {
	/**
	 * Check if a user is blocked from a specific page (for
	 * specific block exemptions if a user is already blocked).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User in question
	 * @param ?mixed $title Title of the page in question
	 * @param ?mixed &$blocked Out-param, whether or not the user is blocked from that page.
	 * @param ?mixed &$allowUsertalk If the user is blocked, whether or not the block allows users
	 *   to edit their own user talk pages.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBlockedFrom( $user, $title, &$blocked, &$allowUsertalk );
}
