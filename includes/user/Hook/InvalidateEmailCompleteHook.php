<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InvalidateEmailComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface InvalidateEmailCompleteHook {
	/**
	 * This hook is called after a user's email has been invalidated successfully.
	 *
	 * @since 1.35
	 *
	 * @param User $user User whose email is being invalidated
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInvalidateEmailComplete( $user );
}
