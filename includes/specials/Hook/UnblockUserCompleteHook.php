<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UnblockUserCompleteHook {
	/**
	 * After an IP address or user has been unblocked.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $block The Block object that was saved
	 * @param ?mixed $user The user who performed the unblock (not the one being unblocked)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnblockUserComplete( $block, $user );
}
