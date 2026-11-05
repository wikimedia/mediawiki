<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UnblockUser" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UnblockUserHook {
	/**
	 * This hook is called before an IP address or user is unblocked.
	 *
	 * Since 1.48, this hook is also called for deferred blocks. A deferred block
	 * is not enforced until the target attempts an action that the block prevents.
	 * Use DatabaseBlock::isDeferred() to find these blocks.
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

/** @deprecated class alias since 1.46 */
class_alias( UnblockUserHook::class, 'MediaWiki\\Hook\\UnblockUserHook' );
