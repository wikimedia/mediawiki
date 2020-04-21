<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InvalidateEmailCompleteHook {
	/**
	 * Called after a user's email has been invalidated
	 * successfully.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user (object) whose email is being invalidated
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInvalidateEmailComplete( $user );
}
