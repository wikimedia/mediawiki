<?php

namespace MediaWiki\Block\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AbortAutoblockHook {
	/**
	 * Return false to cancel an autoblock.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $autoblockip The IP going to be autoblocked.
	 * @param ?mixed $block The block from which the autoblock is coming.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortAutoblock( $autoblockip, $block );
}
