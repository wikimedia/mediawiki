<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BlockIpHook {
	/**
	 * Before an IP address or user is blocked.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $block the Block object about to be saved
	 * @param ?mixed $user the user _doing_ the block (not the one being blocked)
	 * @param ?mixed &$reason if the hook is aborted, the error message to be returned in an array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBlockIp( $block, $user, &$reason );
}
