<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EmailUserPermissionsErrorsHook {
	/**
	 * Use this hook to retrieve permissions errors for emailing a user.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user who is trying to email another user.
	 * @param string $editToken The user's edit token.
	 * @param bool|string|array &$hookErr Out-param for the error. Passed as the parameters to
	 *   OutputPage::showErrorPage.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserPermissionsErrors( $user, $editToken, &$hookErr );
}
