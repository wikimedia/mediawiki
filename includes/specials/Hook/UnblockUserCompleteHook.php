<?php

namespace MediaWiki\Hook;

use MediaWiki\Block\DatabaseBlock;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UnblockUserCompleteHook {
	/**
	 * This hook is called after an IP address or user has been unblocked.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseBlock $block The Block object that was saved
	 * @param User $user The user who performed the unblock (not the one being unblocked)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnblockUserComplete( $block, $user );
}
