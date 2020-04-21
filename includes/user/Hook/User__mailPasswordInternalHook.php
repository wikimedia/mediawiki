<?php

namespace MediaWiki\User\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface User__mailPasswordInternalHook {
	/**
	 * before creation and mailing of a user's new
	 * temporary password
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user who sent the message out
	 * @param ?mixed $ip IP of the user who sent the message out
	 * @param ?mixed $u the account whose new password will be set
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUser__mailPasswordInternal( $user, $ip, $u );
}
