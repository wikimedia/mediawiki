<?php

namespace MediaWiki\User\Hook;

use User;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface User__mailPasswordInternalHook {
	/**
	 * This hook is called before creation and mailing of a user's new temporary password.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user who sent the message out
	 * @param string $ip IP of the user who sent the message out
	 * @param User $u The account whose new password will be set
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUser__mailPasswordInternal( $user, $ip, $u );
}
