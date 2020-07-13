<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ConfirmEmailCompleteHook {
	/**
	 * This hook is called after a user's email has been confirmed successfully.
	 *
	 * @since 1.35
	 *
	 * @param User $user User whose email is being confirmed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onConfirmEmailComplete( $user );
}
