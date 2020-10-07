<?php

namespace MediaWiki\Hook;

use MediaWiki\Block\DatabaseBlock;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BlockIpCompleteHook {
	/**
	 * This hook is called after an IP address or user is blocked.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseBlock $block the block object that was saved
	 * @param User $user the user who did the block (not the one being blocked)
	 * @param ?DatabaseBlock $priorBlock the block object for the prior block, if there was one
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBlockIpComplete( $block, $user, $priorBlock );
}
