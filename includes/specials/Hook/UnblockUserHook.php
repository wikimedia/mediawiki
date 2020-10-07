<?php

namespace MediaWiki\Hook;

use MediaWiki\Block\DatabaseBlock;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UnblockUserHook {
	/**
	 * This hook is called before an IP address or user is unblocked.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseBlock $block The Block object about to be saved
	 * @param User $user The user performing the unblock (not the one being unblocked)
	 * @param array &$reason If the hook is aborted, the error message to be returned in an array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnblockUser( $block, $user, &$reason );
}
