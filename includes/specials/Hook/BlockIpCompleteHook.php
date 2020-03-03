<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BlockIpCompleteHook {
	/**
	 * After an IP address or user is blocked.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $block the Block object that was saved
	 * @param ?mixed $user the user who did the block (not the one being blocked)
	 * @param ?mixed $priorBlock the Block object for the prior block or null if there was none
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBlockIpComplete( $block, $user, $priorBlock );
}
