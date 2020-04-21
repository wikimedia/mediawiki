<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoadOptionsHook {
	/**
	 * When user options/preferences are being loaded from the
	 * database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$options Options, can be modified.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoadOptions( $user, &$options );
}
