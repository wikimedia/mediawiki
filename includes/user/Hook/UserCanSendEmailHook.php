<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserCanSendEmail" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @deprecated since 1.41, handle the EmailUserAuthorizeSend hook instead.
 */
interface UserCanSendEmailHook {
	/**
	 * Use this hook to override User::canSendEmail() permission check.
	 *
	 * @since 1.35
	 *
	 * @param User $user User (object) whose permission is being checked
	 * @param bool|string|array &$hookErr Out-param for the error. Passed as the parameters to
	 *   OutputPage::showErrorPage.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCanSendEmail( $user, &$hookErr );
}
