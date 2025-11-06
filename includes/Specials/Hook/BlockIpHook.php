<?php

namespace MediaWiki\Hook;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BlockIp" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BlockIpHook {
	/**
	 * This hook is called before an IP address or user is blocked.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseBlock $block the Block object about to be saved
	 * @param User $user the user _doing_ the block (not the one being blocked)
	 * @param array &$reason if the hook is aborted, the error message to be returned in an array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBlockIp( $block, $user, &$reason );
}
