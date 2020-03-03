<?php

namespace MediaWiki\Block\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PerformRetroactiveAutoblockHook {
	/**
	 * Called before a retroactive autoblock is applied
	 * to a user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $block Block object (which is set to be autoblocking)
	 * @param ?mixed &$blockIds Array of block IDs of the autoblock
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPerformRetroactiveAutoblock( $block, &$blockIds );
}
