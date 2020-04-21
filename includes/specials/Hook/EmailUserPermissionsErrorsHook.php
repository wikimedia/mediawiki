<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EmailUserPermissionsErrorsHook {
	/**
	 * to retrieve permissions errors for emailing a
	 * user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user The user who is trying to email another user.
	 * @param ?mixed $editToken The user's edit token.
	 * @param ?mixed &$hookErr Out-param for the error. Passed as the parameters to
	 *   OutputPage::showErrorPage.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserPermissionsErrors( $user, $editToken, &$hookErr );
}
