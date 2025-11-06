<?php

namespace MediaWiki\Block\Hook;

use MediaWiki\Block\DatabaseBlock;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AbortAutoblock" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AbortAutoblockHook {
	/**
	 * Use this hook to cancel an autoblock.
	 *
	 * @since 1.35
	 *
	 * @param string $autoblockip IP going to be autoblocked
	 * @param DatabaseBlock $block Block from which the autoblock is coming
	 * @return bool|void True or no return value to continue, or false to cancel an autoblock
	 */
	public function onAbortAutoblock( $autoblockip, $block );
}
