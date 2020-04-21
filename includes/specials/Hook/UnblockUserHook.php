<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UnblockUserHook {
	/**
	 * Before an IP address or user is unblocked.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $block The Block object about to be saved
	 * @param ?mixed $user The user performing the unblock (not the one being unblocked)
	 * @param ?mixed &$reason If the hook is aborted, the error message to be returned in an array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnblockUser( $block, $user, &$reason );
}
