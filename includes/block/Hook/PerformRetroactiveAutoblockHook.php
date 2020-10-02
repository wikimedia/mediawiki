<?php

namespace MediaWiki\Block\Hook;

use MediaWiki\Block\DatabaseBlock;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PerformRetroactiveAutoblock" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PerformRetroactiveAutoblockHook {
	/**
	 * This hook is called before a retroactive autoblock is applied to a user.
	 *
	 * @since 1.35
	 *
	 * @param DatabaseBlock $block Block object which is set to be autoblocking
	 * @param array &$blockIds Array of block IDs of the autoblock
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPerformRetroactiveAutoblock( $block, &$blockIds );
}
